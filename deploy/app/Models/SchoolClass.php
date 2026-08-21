<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table    = 'classes';
    protected $fillable = ['name', 'grade_level', 'homeroom_teacher_id', 'academic_year_id'];

    public function academicYear()    { return $this->belongsTo(AcademicYear::class, 'academic_year_id'); }
    public function homeroomTeacher() { return $this->belongsTo(User::class, 'homeroom_teacher_id'); }
    public function students()        { return $this->hasMany(Student::class, 'class_id'); }
    public function assignments()     { return $this->hasMany(TeacherAssignment::class, 'class_id'); }
    public function attendanceRemarks(){ return $this->hasMany(StudentAttendanceRemark::class, 'class_id'); }
}
