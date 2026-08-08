<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    protected $table = 'student_progress';

    protected $fillable = [
        'circle_id',
        'student_id',
        'teacher_id',
        'surah_id',
        'juz_id',
        'juz',
        'surah',
        'ayah',
        'notes',
        'created_by',
    ];

    /* Relations */
    public function circle()
    {
        return $this->belongsTo(Circle::class);
    }

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(TeacherProfile::class, 'teacher_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function surah()
    {
        return $this->belongsTo(Surah::class, 'surah_id');
    }

    public function juz()
    {
        return $this->belongsTo(Juz::class, 'juz_id');
    }
}
