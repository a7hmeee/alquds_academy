<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemorizationMistake extends Model
{
    protected $fillable = [
        'memorization_session_id', 'student_id', 'surah_id', 'ayah_number',
        'mistake_type', 'severity', 'word_text', 'correct_text',
        'teacher_note', 'is_resolved', 'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'ayah_number' => 'integer',
            'is_resolved' => 'boolean',
            'resolved_at' => 'datetime',
        ];
    }

    public function session()
    {
        return $this->belongsTo(MemorizationSession::class, 'memorization_session_id');
    }

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function surah()
    {
        return $this->belongsTo(Surah::class);
    }
}
