<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'circle_session_id', 'student_id', 'status',
        'arrival_time', 'excuse', 'note', 'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'arrival_time' => 'datetime',
        ];
    }

    public function session()
    {
        return $this->belongsTo(CircleSession::class, 'circle_session_id');
    }

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
