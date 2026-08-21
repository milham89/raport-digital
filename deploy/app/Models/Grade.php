<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = ['student_id','subject_id','academic_year_id','teacher_id','tp1','tp2','formatif','sumatif','pas','final_score','letter_grade','description'];

    public function student()      { return $this->belongsTo(Student::class); }
    public function subject()      { return $this->belongsTo(Subject::class); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function teacher()      { return $this->belongsTo(User::class, 'teacher_id'); }

    public static function calculateFinal($tp1, $tp2, $formatif, $sumatif, $pas): array
    {
        $tp = (($tp1 ?? 0) + ($tp2 ?? 0)) / 2;
        $nhb = ($tp * 0.2) + (($formatif ?? 0) * 0.2) + (($sumatif ?? 0) * 0.4) + (($pas ?? 0) * 0.2);
        $nhb = round($nhb, 2);
        if ($nhb >= 90) $letter = 'A';
        elseif ($nhb >= 80) $letter = 'B';
        elseif ($nhb >= 70) $letter = 'C';
        else $letter = 'D';
        return ['final_score' => $nhb, 'letter_grade' => $letter];
    }
}
