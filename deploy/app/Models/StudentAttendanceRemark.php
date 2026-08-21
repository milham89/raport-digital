<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StudentAttendanceRemark extends Model
{
    protected $fillable = ['student_id','class_id','academic_year_id','sick','permission','unexcused','homeroom_remark','status'];

    public function student()      { return $this->belongsTo(Student::class); }
    public function schoolClass()  { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
}
