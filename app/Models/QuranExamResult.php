<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuranExamResult extends Model
{
    protected $fillable = [
        'quran_exam_id', 'student_id', 'score', 'percentage',
        'passed', 'tajweed_score', 'memorization_score',
        'teacher_notes', 'status', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'percentage' => 'decimal:2',
            'passed' => 'boolean',
            'tajweed_score' => 'integer',
            'memorization_score' => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    public function exam()
    {
        return $this->belongsTo(QuranExam::class, 'quran_exam_id');
    }

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }
}
