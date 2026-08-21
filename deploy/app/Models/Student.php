<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['nis', 'nisn', 'name', 'gender', 'class_id'];

    public function schoolClass()       { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function grades()            { return $this->hasMany(Grade::class); }
    public function attendanceRemarks() { return $this->hasMany(StudentAttendanceRemark::class); }
    public function userAccount()       { return $this->hasOne(User::class, 'student_id'); }
}
