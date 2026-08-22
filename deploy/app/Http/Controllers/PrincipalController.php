<?php
namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Grade;
use App\Models\StudentAttendanceRemark;
use App\Models\SchoolSetting;


class PrincipalController extends Controller
{
    public function dashboard()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $classes = SchoolClass::with(['homeroomTeacher','academicYear'])->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))->get();
        return view('principal.dashboard', compact('activeYear','classes'));
    }

    public function classReport(SchoolClass $class)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $students = Student::where('class_id', $class->id)->orderBy('name')->get();
        $grades = Grade::where('academic_year_id', $activeYear?->id)->whereIn('student_id', $students->pluck('id'))->with(['subject','student'])->get()->groupBy('student_id');
        $remarks = StudentAttendanceRemark::where('class_id', $class->id)->where('academic_year_id', $activeYear?->id)->get()->keyBy('student_id');
        return view('principal.class-report', compact('class','activeYear','students','grades','remarks'));
    }

    public function reportCard(SchoolClass $class, Student $student)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $grades = Grade::with(['subject', 'teacher'])
            ->where('student_id', $student->id)
            ->where('academic_year_id', $activeYear?->id)
            ->get();

        $remark = StudentAttendanceRemark::where('student_id', $student->id)
            ->where('academic_year_id', $activeYear?->id)
            ->first();

        $setting = SchoolSetting::getSettings();
        return view('report.card', compact('student', 'class', 'activeYear', 'grades', 'remark', 'setting'));
    }
}
