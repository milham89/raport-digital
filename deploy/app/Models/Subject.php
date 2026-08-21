<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['code', 'name', 'kkm'];
    protected $casts    = ['kkm' => 'decimal:2'];

    public function assignments() { return $this->hasMany(TeacherAssignment::class); }
    public function grades()      { return $this->hasMany(Grade::class); }
}
