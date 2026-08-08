<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Circle extends Model
{
    protected $fillable = [
        'name',
        'organization_id',
        'type',
        'level',
        'capacity',
        'status',
        'description',
        'juz_id',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function juz(): BelongsTo
    {
        return $this->belongsTo(Juz::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            StudentProfile::class,
            'circle_students',
            'circle_id',
            'student_id'
        )
        ->withPivot(['status', 'joined_at'])
        ->withTimestamps();
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(
            TeacherProfile::class,
            'circle_teachers',
            'circle_id',
            'teacher_id'
        )
        ->withPivot(['role', 'status'])
        ->withTimestamps();
    }

    public function circleTeachers(): HasMany
    {
        return $this->hasMany(CircleTeacher::class, 'circle_id');
    }

    public function circleStudents(): HasMany
    {
        return $this->hasMany(CircleStudent::class, 'circle_id');
    }

    public function studentProgresses(): HasMany
    {
        return $this->hasMany(StudentProgress::class, 'circle_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(StudentSubmission::class, 'circle_id');
    }

    public function primaryTeacher()
    {
        return $this->teachers()
            ->wherePivot('role', 'primary')
            ->first();
    }

    public function getTeacherAttribute()
    {
        $primary = $this->primaryTeacher();
        if ($primary) {
            return $primary;
        }
        $ct = $this->circleTeachers()->with('teacher.user')->first();
        return $ct?->teacher;
    }

    public function isStudentEnrolled(int $studentId): bool
    {
        return $this->circleStudents()
            ->where('student_id', $studentId)
            ->where('status', 'active')
            ->exists();
    }

    public function isTeacherAssigned(int $teacherId): bool
    {
        return $this->circleTeachers()
            ->where('teacher_id', $teacherId)
            ->where('status', 'active')
            ->exists();
    }

    public function hasCapacity(): bool
    {
        if (!$this->capacity) {
            return true;
        }
        return $this->circleStudents()->where('status', 'active')->count() < $this->capacity;
    }

    public function circleSessions(): HasMany
    {
        return $this->hasMany(\App\Models\CircleSession::class, 'circle_id');
    }

    public function memorizationAssignments(): HasMany
    {
        return $this->hasMany(\App\Models\MemorizationAssignment::class, 'circle_id');
    }

    public function memorizationSessions(): HasMany
    {
        return $this->hasMany(\App\Models\MemorizationSession::class, 'circle_id');
    }
}
