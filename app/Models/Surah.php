<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Surah extends Model
{
    public $timestamps = true;
    protected $table = 'surahs';
    protected $fillable = ['name_ar', 'name_en', 'revelation_place', 'verses_count'];

    /**
     * علاقة السورة مع الآيات
     */
    public function ayahs(): HasMany
    {
        return $this->hasMany(Ayah::class, 'surah_id')->orderBy('ayah_number');
    }
}
