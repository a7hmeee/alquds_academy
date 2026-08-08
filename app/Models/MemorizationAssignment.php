<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemorizationAssignment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id', 'teacher_id', 'circle_id',
        'assignment_type', 'surah_id', 'juz_id',
        'ayah_from', 'ayah_to', 'priority', 'status',
        'instructions', 'assigned_at', 'due_at',
        'started_at', 'submitted_at', 'reviewed_at', 'completed_at',
        'completion_percent', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'due_at' => 'datetime',
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'completed_at' => 'datetime',
            'completion_percent' => 'integer',
            'priority' => 'integer',
            'ayah_from' => 'integer',
            'ayah_to' => 'integer',
        ];
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

    public function submissions()
    {
        return $this->hasMany(StudentSubmission::class, 'memorization_assignment_id');
    }

    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForCircle($query, $circleId)
    {
        return $query->where('circle_id', $circleId);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['assigned', 'in_progress']);
    }

    public function scopeOverdue($query)
    {
        return $query->whereIn('status', ['assigned', 'in_progress'])
            ->where('due_at', '<', now());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function canTransitionTo(string $newStatus): bool
    {
        $allowed = [
            'draft' => ['assigned', 'cancelled'],
            'assigned' => ['in_progress', 'cancelled'],
            'in_progress' => ['submitted', 'cancelled'],
            'submitted' => ['reviewed', 'needs_revision'],
            'reviewed' => ['completed', 'needs_revision'],
            'needs_revision' => ['in_progress', 'cancelled'],
            'completed' => [],
            'cancelled' => [],
        ];

        return in_array($newStatus, $allowed[$this->status] ?? []);
    }
}
