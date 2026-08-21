<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TeacherAssignment;
use App\Models\Grade;
use App\Models\Student;
use App\Models\AcademicYear;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $activeYear = AcademicYear::where('is_active', true)->first();
        $assignments = TeacherAssignment::with(['schoolClass','subject'])
            ->where('teacher_id', $user->id)
            ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->get();
        return view('teacher.dashboard', compact('user','activeYear','assignments'));
    }

    public function grades(TeacherAssignment $assignment)
    {
        if ($assignment->teacher_id !== Auth::id()) abort(403);
        $students = Student::where('class_id', $assignment->class_id)->orderBy('name')->get();
        $grades = Grade::where('subject_id', $assignment->subject_id)
            ->where('academic_year_id', $assignment->academic_year_id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()->keyBy('student_id');
        return view('teacher.grades', compact('assignment','students','grades'));
    }

    public function saveGrades(Request $request, TeacherAssignment $assignment)
    {
        if ($assignment->teacher_id !== Auth::id()) abort(403);
        $data = $request->validate([
            'grades'                  => 'array',
            'grades.*.tp1'            => 'nullable|numeric|min:0|max:100',
            'grades.*.tp2'            => 'nullable|numeric|min:0|max:100',
            'grades.*.formatif'       => 'nullable|numeric|min:0|max:100',
            'grades.*.sumatif'        => 'nullable|numeric|min:0|max:100',
            'grades.*.pas'            => 'nullable|numeric|min:0|max:100',
            'grades.*.description'    => 'nullable|string|max:500',
        ]);
        foreach ($data['grades'] as $studentId => $scores) {
            $calc = Grade::calculateFinal(
                $scores['tp1'] ?? null, $scores['tp2'] ?? null,
                $scores['formatif'] ?? null, $scores['sumatif'] ?? null, $scores['pas'] ?? null
            );
            Grade::updateOrCreate(
                ['student_id'=>$studentId,'subject_id'=>$assignment->subject_id,'academic_year_id'=>$assignment->academic_year_id],
                array_merge($scores, $calc, ['teacher_id'=>Auth::id()])
            );
        }
        return back()->with('success','Nilai berhasil disimpan.');
    }
}

