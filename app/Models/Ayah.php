<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ayah extends Model
{
    public $timestamps = true;
    protected $table = 'ayahs';
    protected $fillable = ['surah_id', 'juz_id', 'ayah_number', 'text'];

    /**
     * علاقة الآية مع السورة
     */
    public function surah(): BelongsTo
    {
        return $this->belongsTo(Surah::class, 'surah_id');
    }

    /**
     * علاقة الآية مع الجزء
     */
    public function juz(): BelongsTo
    {
        return $this->belongsTo(Juz::class, 'juz_id');
    }
}
