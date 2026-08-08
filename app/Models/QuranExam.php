<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuranExam extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'circle_id', 'teacher_id', 'title', 'exam_type',
        'surah_id', 'juz_id', 'total_score', 'passing_score',
        'exam_date', 'status', 'instructions', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'exam_date' => 'date',
            'total_score' => 'integer',
            'passing_score' => 'integer',
        ];
    }

    public function circle()
    {
        return $this->belongsTo(Circle::class);
    }

    public function teacher()
    {
        return $this->belongsTo(TeacherProfile::class, 'teacher_id');
    }

    public function surah()
    {
        return $this->belongsTo(Surah::class);
    }

    public function juz()
    {
        return $this->belongsTo(Juz::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function results()
    {
        return $this->hasMany(QuranExamResult::class);
    }
}
