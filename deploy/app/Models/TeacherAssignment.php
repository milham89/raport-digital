<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TeacherAssignment extends Model
{
    protected $fillable = ['teacher_id', 'subject_id', 'class_id', 'academic_year_id'];

    public function teacher()      { return $this->belongsTo(User::class, 'teacher_id'); }
    public function subject()      { return $this->belongsTo(Subject::class); }
    public function schoolClass()  { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
}
