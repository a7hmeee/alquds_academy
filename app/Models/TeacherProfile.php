<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeacherProfile extends Model
{
    protected $fillable = [
        'user_id',
        'photo',
        'academic_degree',
        'years_of_experience',
        'specialization',
        'riwayat',
        'teaching_language',
        'gender',
        'bio',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(StudentProfile::class);
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(StudentProgress::class, 'teacher_id');
    }

    public function circleTeachers(): HasMany
    {
        return $this->hasMany(CircleTeacher::class, 'teacher_id');
    }

    public function circles()
    {
        return $this->belongsToMany(
            Circle::class,
            'circle_teachers',
            'teacher_id',
            'circle_id'
        )
        ->withPivot(['role', 'status'])
        ->withTimestamps();
    }
}
