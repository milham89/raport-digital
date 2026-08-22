<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentAttendanceRemark;
use App\Models\DailyAttendance;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\SchoolSetting;


class HomeroomController extends Controller
{
    private function myClass()
    {
        return SchoolClass::where('homeroom_teacher_id', Auth::id())->first();
    }

    public function dashboard()
    {
        $class = $this->myClass();
        $activeYear = AcademicYear::where('is_active', true)->first();
        $students = $class ? Student::where('class_id', $class->id)->orderBy('name')->get() : collect();
        $grades = collect();
        $remarks = collect();

        if ($class && $activeYear) {
            $grades = Grade::whereIn('student_id', $students->pluck('id'))
                ->where('academic_year_id', $activeYear->id)
                ->get()
                ->groupBy('student_id');

            $remarks = StudentAttendanceRemark::where('class_id', $class->id)
                ->where('academic_year_id', $activeYear->id)
                ->get()
                ->keyBy('student_id');
        }

        $finalized = ($class && $activeYear) ? StudentAttendanceRemark::where('class_id', $class->id)->where('academic_year_id', $activeYear->id)->where('status','FINALIZED')->count() : 0;
        return view('homeroom.dashboard', compact('class','activeYear','students','grades','remarks','finalized'));
    }

    public function dailyAttendance(Request $request)
    {
        $class = $this->myClass();
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$class || !$activeYear) return redirect()->route('homeroom.dashboard')->withErrors('Kelas atau tahun ajaran tidak ditemukan.');

        $dateStr = $request->input('date', date('Y-m-d'));
        try {
            $selectedDate = Carbon::parse($dateStr);
        } catch (\Exception $e) {
            $selectedDate = Carbon::today();
        }

        $isWeekend = $selectedDate->isWeekend();

        $prevDate = $selectedDate->copy()->subDay();
        while ($prevDate->isWeekend()) {
            $prevDate->subDay();
        }

        $nextDate = $selectedDate->copy()->addDay();
        while ($nextDate->isWeekend()) {
            $nextDate->addDay();
        }

        $students = Student::where('class_id', $class->id)->orderBy('name')->get();
        $attendances = DailyAttendance::where('class_id', $class->id)
            ->where('date', $selectedDate->format('Y-m-d'))
            ->get()
            ->keyBy('student_id');

        return view('homeroom.attendance-daily', compact(
            'class',
            'activeYear',
            'students',
            'selectedDate',
            'isWeekend',
            'prevDate',
            'nextDate',
            'attendances'
        ));
    }

    public function saveDailyAttendance(Request $request)
    {
        $class = $this->myClass();
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$class || !$activeYear) return redirect()->route('homeroom.dashboard')->withErrors('Kelas atau tahun ajaran tidak ditemukan.');

        $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.status' => 'required|in:H,S,I,A',
            'attendances.*.note' => 'nullable|string|max:255',
        ]);

        $date = $request->input('date');

        foreach ($request->attendances as $studentId => $data) {
            DailyAttendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $date,
                ],
                [
                    'class_id' => $class->id,
                    'academic_year_id' => $activeYear->id,
                    'status' => $data['status'] ?? 'H',
                    'note' => $data['note'] ?? null,
                ]
            );

            $this->recalculateStudentSemesterAttendance($studentId, $class->id, $activeYear->id);
        }

        return redirect()->route('homeroom.attendance.daily', ['date' => $date])
            ->with('success', 'Absensi tanggal ' . Carbon::parse($date)->format('d/m/Y') . ' berhasil disimpan dan otomatis disinkronkan ke rekap semester.');
    }

    public function monthlyAttendance(Request $request)
    {
        $class = $this->myClass();
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$class || !$activeYear) return redirect()->route('homeroom.dashboard')->withErrors('Kelas atau tahun ajaran tidak ditemukan.');

        $monthStr = $request->input('month', date('Y-m'));
        try {
            $selectedMonth = Carbon::parse($monthStr . '-01')->startOfMonth();
        } catch (\Exception $e) {
            $selectedMonth = Carbon::today()->startOfMonth();
        }

        $daysInMonth = $selectedMonth->daysInMonth;
        $weekdays = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = $selectedMonth->copy()->startOfMonth()->addDays($day - 1);
            if (!$currentDate->isWeekend()) {
                $weekdays[] = $currentDate;
            }
        }

        $students = Student::where('class_id', $class->id)->orderBy('name')->get();
        $startOfMonth = $selectedMonth->copy()->startOfMonth()->format('Y-m-d');
        $endOfMonth = $selectedMonth->copy()->endOfMonth()->format('Y-m-d');

        $dailyRecords = DailyAttendance::where('class_id', $class->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        $matrix = [];
        foreach ($dailyRecords as $rec) {
            $d = is_string($rec->date) ? substr($rec->date, 0, 10) : (is_object($rec->date) ? $rec->date->format('Y-m-d') : (string)$rec->date);
            $matrix[$rec->student_id][$d] = $rec->status;
        }

        $summaries = [];
        foreach ($students as $student) {
            $sId = $student->id;
            $h = 0; $s = 0; $i = 0; $a = 0;
            foreach ($weekdays as $wDate) {
                $d = $wDate->format('Y-m-d');
                $st = $matrix[$sId][$d] ?? null;
                if ($st === 'H') $h++;
                elseif ($st === 'S') $s++;
                elseif ($st === 'I') $i++;
                elseif ($st === 'A') $a++;
            }
            $totalRecorded = $h + $s + $i + $a;
            $presentRate = $totalRecorded > 0 ? round(($h / $totalRecorded) * 100, 1) : 0;

            $summaries[$sId] = [
                'h' => $h,
                's' => $s,
                'i' => $i,
                'a' => $a,
                'total' => $totalRecorded,
                'rate' => $presentRate,
            ];
        }

        return view('homeroom.attendance-monthly', compact(
            'class',
            'activeYear',
            'students',
            'selectedMonth',
            'weekdays',
            'matrix',
            'summaries'
        ));
    }

    public function syncAttendance(Request $request)
    {
        $class = $this->myClass();
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$class || !$activeYear) return redirect()->route('homeroom.dashboard')->withErrors('Kelas atau tahun ajaran tidak ditemukan.');

        $students = Student::where('class_id', $class->id)->get();
        foreach ($students as $student) {
            $this->recalculateStudentSemesterAttendance($student->id, $class->id, $activeYear->id);
        }

        return back()->with('success', 'Rekap absensi semester berhasil disinkronkan dari absensi harian.');
    }

    private function recalculateStudentSemesterAttendance($studentId, $classId, $academicYearId)
    {
        $records = DailyAttendance::where('student_id', $studentId)
            ->where('academic_year_id', $academicYearId)
            ->get();

        $sick = $records->where('status', 'S')->count();
        $permission = $records->where('status', 'I')->count();
        $unexcused = $records->where('status', 'A')->count();

        $remark = StudentAttendanceRemark::firstOrNew([
            'student_id' => $studentId,
            'class_id' => $classId,
            'academic_year_id' => $academicYearId,
        ]);

        $remark->sick = $sick;
        $remark->permission = $permission;
        $remark->unexcused = $unexcused;
        $remark->save();
    }

    public function remarks()
    {
        $class = $this->myClass();
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$class || !$activeYear) return redirect()->route('homeroom.dashboard')->withErrors('Kelas atau tahun ajaran tidak ditemukan.');

        $students = Student::where('class_id', $class->id)->orderBy('name')->get();
        $remarks = StudentAttendanceRemark::where('class_id', $class->id)->where('academic_year_id', $activeYear->id)
            ->get()->keyBy('student_id');
        return view('homeroom.remarks', compact('class','activeYear','students','remarks'));
    }

    public function saveRemarks(Request $request)
    {
        $class = $this->myClass();
        $activeYear = AcademicYear::where('is_active', true)->first();
        $request->validate([
            'remarks' => 'array',
            'remarks.*.sick' => 'nullable|integer|min:0',
            'remarks.*.permission' => 'nullable|integer|min:0',
            'remarks.*.unexcused' => 'nullable|integer|min:0',
            'remarks.*.homeroom_note' => 'nullable|string|max:1000',
            'remarks.*.homeroom_remark' => 'nullable|string|max:1000'
        ]);

        foreach ($request->remarks as $studentId => $data) {
            $homeroomRemark = $data['homeroom_note'] ?? ($data['homeroom_remark'] ?? '');
            StudentAttendanceRemark::updateOrCreate(
                ['student_id'=>$studentId,'class_id'=>$class->id,'academic_year_id'=>$activeYear->id],
                [
                    'sick' => $data['sick'] ?? 0,
                    'permission' => $data['permission'] ?? 0,
                    'unexcused' => $data['unexcused'] ?? 0,
                    'homeroom_remark' => $homeroomRemark,
                    'status' => 'FINALIZED'
                ]
            );
        }
        return back()->with('success','Catatan & Rekap Absensi berhasil disimpan.');
    }

    public function reportCard(Student $student)
    {
        $class = $this->myClass();
        $activeYear = AcademicYear::where('is_active', true)->first();
        $grades = Grade::with(['subject','teacher'])->where('student_id',$student->id)->where('academic_year_id',$activeYear->id)->get();
        $remark = StudentAttendanceRemark::where('student_id',$student->id)->where('academic_year_id',$activeYear->id)->first();
        $setting = SchoolSetting::getSettings();
        return view('report.card', compact('student','class','activeYear','grades','remark','setting'));
    }
}
