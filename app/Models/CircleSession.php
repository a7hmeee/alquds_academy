<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CircleSession extends Model
{
    protected $fillable = [
        'circle_id', 'teacher_id', 'title', 'session_date',
        'starts_at', 'ends_at', 'session_type', 'status',
        'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'date',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'circle_session_id');
    }
}
