<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CircleStudent extends Model
{
    protected $fillable = [
        'circle_id',
        'student_id',
        'status',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    public function circle(): BelongsTo
    {
        return $this->belongsTo(Circle::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function primaryTeacher()
    {
        return $this->circle
            ->teachers()
            ->wherePivot('role', 'primary')
            ->first();
    }
}
