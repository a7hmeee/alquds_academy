<?php

namespace App\Queries\Quran;

use App\Models\Surah;
use App\Models\Ayah;
use App\Models\Juz;
use Illuminate\Support\Collection;

class SurahJuzQuery
{
    public function allSurahs(): Collection
    {
        $surahs = Surah::withCount(['ayahs' => function ($q) {
            $q->where('ayah_number', '>', 0);
        }])->get();

        return $surahs->map(fn($surah) => [
            'id' => $surah->id,
            'name_ar' => $surah->name_ar,
            'name_en' => $surah->name_en,
            'revelation_place' => $surah->revelation_place,
            'verses_count' => $surah->verses_count,
            'actual_verses' => (int) $surah->ayahs_count,
        ]);
    }

    public function juzForSurah(int $surahId): Collection
    {
        return Ayah::where('surah_id', $surahId)
            ->distinct('juz_id')
            ->with('juz')
            ->get()
            ->pluck('juz')
            ->unique('id')
            ->values()
            ->map(fn($juz) => [
                'id' => $juz->id,
                'name' => $juz->name,
            ]);
    }

    public function ayahsForSurahJuz(int $surahId, int $juzId): Collection
    {
        return Ayah::where('surah_id', $surahId)
            ->where('juz_id', $juzId)
            ->orderBy('ayah_number')
            ->get(['id', 'ayah_number', 'text', 'juz_id']);
    }

    public function surahSearch(string $query): Collection
    {
        return Surah::where('name_ar', 'like', '%' . $query . '%')
            ->orWhere('name_en', 'like', '%' . $query . '%')
            ->get()
            ->map(fn($surah) => [
                'id' => $surah->id,
                'name_ar' => $surah->name_ar,
                'name_en' => $surah->name_en,
                'verses_count' => $surah->verses_count,
            ]);
    }
}
