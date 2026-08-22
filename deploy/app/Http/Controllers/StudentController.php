<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Grade;
use App\Models\StudentAttendanceRemark;
use App\Models\SchoolSetting;

use App\Models\AcademicYear;

class StudentController extends Controller
{
    private function myStudent(): ?Student
    {
        $user = Auth::user();
        if ($user->student_id) {
            return Student::with('schoolClass')->find($user->student_id);
        }
        return null;
    }

    public function dashboard()
    {
        $student    = $this->myStudent();
        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$student) {
            return view('student.no-data');
        }

        $grades = Grade::with(['subject', 'teacher'])
            ->where('student_id', $student->id)
            ->where('academic_year_id', $activeYear?->id)
            ->get();

        $remark = StudentAttendanceRemark::where('student_id', $student->id)
            ->where('academic_year_id', $activeYear?->id)
            ->first();

        $avgScore = $grades->whereNotNull('final_score')->count() > 0
            ? round($grades->whereNotNull('final_score')->avg('final_score'), 1)
            : 0;

        $passedCount = $grades->filter(fn($g) => $g->final_score >= ($g->subject->kkm ?? 75))->count();

        return view('student.dashboard', compact('student', 'activeYear', 'grades', 'remark', 'avgScore', 'passedCount'));
    }

    public function reportCard()
    {
        $student    = $this->myStudent();
        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$student) {
            return redirect()->route('student.dashboard')->withErrors('Data profil siswa tidak ditemukan.');
        }

        $grades = Grade::with(['subject', 'teacher'])
            ->where('student_id', $student->id)
            ->where('academic_year_id', $activeYear?->id)
            ->get();

        $remark = StudentAttendanceRemark::where('student_id', $student->id)
            ->where('academic_year_id', $activeYear?->id)
            ->first();

        $class = $student->schoolClass;
        $setting = SchoolSetting::getSettings();

        return view('report.card', compact('student', 'class', 'activeYear', 'grades', 'remark', 'setting'));
    }
}

