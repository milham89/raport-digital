<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'nip', 'student_id', 'role', 'is_active'];
    protected $hidden   = ['password', 'remember_token'];
    protected $casts    = ['is_active' => 'boolean', 'email_verified_at' => 'datetime'];

    public function homeroomClasses()    { return $this->hasMany(SchoolClass::class, 'homeroom_teacher_id'); }
    public function teacherAssignments() { return $this->hasMany(TeacherAssignment::class, 'teacher_id'); }
    public function grades()             { return $this->hasMany(Grade::class, 'teacher_id'); }
    public function student()            { return $this->belongsTo(Student::class, 'student_id'); }

    public function isAdmin()     { return $this->role === 'admin'; }
    public function isTeacher()   { return $this->role === 'teacher'; }
    public function isHomeroom()  { return $this->role === 'homeroom'; }
    public function isPrincipal() { return $this->role === 'principal'; }
    public function isStudent()   { return $this->role === 'student'; }
}

