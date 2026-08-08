<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAchievement extends Model
{
    protected $fillable = [
        'student_id', 'achievement_type', 'title', 'description',
        'surah_id', 'juz_id', 'achieved_at', 'metadata',
    ];

    protected function casts(): array
    {
        return [
            'achieved_at' => 'datetime',
            'metadata' => 'json',
        ];
    }

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function surah()
    {
        return $this->belongsTo(Surah::class);
    }

    public function juz()
    {
        return $this->belongsTo(Juz::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class, 'student_achievement_id');
    }
}
