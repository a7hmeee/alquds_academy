<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'student_id', 'student_achievement_id', 'certificate_type',
        'title', 'issued_at', 'issued_by', 'verification_code',
        'file_path', 'metadata',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'metadata' => 'json',
        ];
    }

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function achievement()
    {
        return $this->belongsTo(StudentAchievement::class, 'student_achievement_id');
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
