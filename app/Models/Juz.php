<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Juz extends Model
{
    public $timestamps = true;
    protected $table = 'juz';
    protected $fillable = ['name'];

    /**
     * علاقة الجزء مع الآيات
     */
    public function ayahs(): HasMany
    {
        return $this->hasMany(Ayah::class, 'juz_id')
            ->orderBy('surah_id')
            ->orderBy('ayah_number');
    }

    /**
     * السور الموجودة في هذا الجزء مع عدد الآيات لكل سورة
     */
    public function getSurahsBreakdownAttribute(): \Illuminate\Support\Collection
    {
        return Ayah::where('juz_id', $this->id)
            ->selectRaw('surah_id, SUM(CASE WHEN ayah_number > 0 THEN 1 ELSE 0 END) as ayah_count, MIN(CASE WHEN ayah_number > 0 THEN ayah_number END) as min_ayah, MAX(ayah_number) as max_ayah')
            ->groupBy('surah_id')
            ->orderBy('surah_id')
            ->with('surah:id,name_ar,name_en,verses_count')
            ->get()
            ->map(function ($row) {
                return [
                    'surah_id' => $row->surah_id,
                    'surah_name' => $row->surah?->name_ar ?? '',
                    'total_ayahs' => (int) $row->ayah_count,
                    'min_ayah' => $row->min_ayah,
                    'max_ayah' => $row->max_ayah,
                ];
            });
    }
}
