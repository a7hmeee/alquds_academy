<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'circle_id',
        'file_path',
        'image_path',
        'notes',
        'surah',
        'ayah',
        'juz',
        'surah_id',
        'juz_id',
        'ayah_from',
        'ayah_to',
        'self_rating',
        'self_notes',
        'status',
        'reviewed_by',
        'review_notes',
        'rating',
        'score',
    ];

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function circle()
    {
        return $this->belongsTo(Circle::class);
    }

    public function surahModel()
    {
        return $this->belongsTo(Surah::class, 'surah_id');
    }

    public function juzModel()
    {
        return $this->belongsTo(Juz::class, 'juz_id');
    }

    /**
     * اسم السورة: من العمود النصي أو العلاقة
     */
    public function getSurahDisplayAttribute()
    {
        return $this->attributes['surah'] ?: ($this->surahModel?->name_ar);
    }

    /**
     * اسم الجزء: من العمود النصي أو العلاقة
     */
    public function getJuzDisplayAttribute()
    {
        return $this->attributes['juz'] ?: ($this->juzModel?->name);
    }

    public function reviewer()
    {
        return $this->belongsTo(TeacherProfile::class, 'reviewed_by');
    }

    public function memorizationAssignment()
    {
        return $this->belongsTo(MemorizationAssignment::class, 'memorization_assignment_id');
    }
}
