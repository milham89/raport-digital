<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = ['year', 'semester', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function classes()  { return $this->hasMany(SchoolClass::class, 'academic_year_id'); }
    public function subjects() { return $this->hasMany(Subject::class); }
}
