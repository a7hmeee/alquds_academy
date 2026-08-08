<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'teacher_id',
        'full_name',
        'photo',
        'birth_date',
        'gender',
        'nationality',
        'level',
        'school',
        'education_stage',
        'memorization_level',
        'tajweed_level',
        'current_juz',
        'current_surah',
        'current_ayah',
        'is_smart_mode',
        'needs_assistance',
        'phone',
        'guardian_name',
        'guardian_phone',
        'status',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(TeacherProfile::class);
    }

    public function circles(): HasMany
    {
        return $this->hasMany(CircleStudent::class, 'student_id');
    }

    public function getCircleAttribute()
    {
        return $this->circles()
            ->where('status', 'active')
            ->orderBy('joined_at', 'desc')
            ->first()
            ?->circle;
    }

    public function enrolledCircles(): Collection
    {
        return $this->circles()
            ->with('circle')
            ->get()
            ->pluck('circle');
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(StudentProgress::class, 'student_id');
    }

    public function progressRecords(): HasMany
    {
        return $this->hasMany(StudentProgress::class, 'student_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(StudentSubmission::class, 'student_id');
    }

    public function latestProgress(): HasOne
    {
        return $this->hasOne(StudentProgress::class, 'student_id')->latestOfMany();
    }

    public function getProgressPercentAttribute(): int
    {
        return (int) ($this->memorization_level ?? 0);
    }

    public function isEnrolledInCircle(int $circleId): bool
    {
        return $this->circles()
            ->where('circle_id', $circleId)
            ->where('status', 'active')
            ->exists();
    }

    public function getCircleTeacher(): ?TeacherProfile
    {
        $circleStudent = $this->circles()
            ->where('status', 'active')
            ->with(['circle.circleTeachers' => function ($q) {
                $q->where('role', 'primary');
            }, 'circle.circleTeachers.teacher'])
            ->first();

        return $circleStudent?->circle?->circleTeachers?->first()?->teacher;
    }
}
