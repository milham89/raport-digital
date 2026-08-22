<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentAttendanceRemark;
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
        $finalized = $class && $activeYear ? StudentAttendanceRemark::where('class_id', $class->id)->where('academic_year_id', $activeYear->id)->where('status','FINALIZED')->count() : 0;
        return view('homeroom.dashboard', compact('class','activeYear','students','finalized'));
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
        $request->validate(['remarks'=>'array','remarks.*.sick'=>'nullable|integer|min:0','remarks.*.permission'=>'nullable|integer|min:0','remarks.*.unexcused'=>'nullable|integer|min:0','remarks.*.homeroom_remark'=>'nullable|string|max:1000']);
        foreach ($request->remarks as $studentId => $data) {
            StudentAttendanceRemark::updateOrCreate(
                ['student_id'=>$studentId,'class_id'=>$class->id,'academic_year_id'=>$activeYear->id],
                array_merge($data, ['status'=>'FINALIZED'])
            );
        }
        return back()->with('success','Catatan berhasil disimpan.');
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
