<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemorizationSession extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'memorization_assignment_id', 'student_id', 'teacher_id', 'circle_id',
        'submission_id', 'session_type', 'surah_id', 'juz_id',
        'ayah_from', 'ayah_to', 'session_date', 'duration_minutes',
        'memorization_score', 'tajweed_score', 'fluency_score', 'total_score',
        'status', 'teacher_notes', 'student_notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'date',
            'duration_minutes' => 'integer',
            'memorization_score' => 'integer',
            'tajweed_score' => 'integer',
            'fluency_score' => 'integer',
            'total_score' => 'integer',
        ];
    }

    public function assignment()
    {
        return $this->belongsTo(MemorizationAssignment::class, 'memorization_assignment_id');
    }

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(TeacherProfile::class, 'teacher_id');
    }

    public function circle()
    {
        return $this->belongsTo(Circle::class);
    }

    public function submission()
    {
        return $this->belongsTo(StudentSubmission::class, 'submission_id');
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

    public function mistakes()
    {
        return $this->hasMany(MemorizationMistake::class, 'memorization_session_id');
    }
}
