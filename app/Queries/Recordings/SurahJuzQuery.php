<?php

namespace App\Queries\Recordings;

use App\Models\Surah;
use App\Models\Juz;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class SurahJuzQuery
{
    /**
     * Get all surahs with ayah count and juz count.
     */
    public static function allSurahsWithCounts(): Collection
    {
        $surahs = Surah::withCount(['ayahs' => function ($q) {
            $q->where('ayah_number', '>', 0);
        }])->get();

        $juzCounts = DB::table('ayahs')
            ->selectRaw('surah_id, COUNT(DISTINCT juz_id) as juz_count')
            ->groupBy('surah_id')
            ->pluck('juz_count', 'surah_id');

        return $surahs->map(fn($s) => [
            'id' => $s->id,
            'number' => $s->id,
            'name_ar' => $s->name_ar,
            'name_en' => $s->name_en,
            'revelation_place' => $s->revelation_place,
            'juz_count' => $juzCounts[$s->id] ?? 0,
            'ayah_count' => $s->ayahs_count,
        ]);
    }

    /**
     * Search surahs by name (Arabic or English).
     */
    public static function searchSurahs(string $query): Collection
    {
        $surahs = Surah::withCount(['ayahs' => function ($q) {
                $q->where('ayah_number', '>', 0);
            }])
            ->where('name_ar', 'like', "%{$query}%")
            ->orWhere('name_en', 'like', "%{$query}%")
            ->limit(20)
            ->get();

        $surahIds = $surahs->pluck('id');
        $juzCounts = DB::table('ayahs')
            ->whereIn('surah_id', $surahIds)
            ->selectRaw('surah_id, COUNT(DISTINCT juz_id) as juz_count')
            ->groupBy('surah_id')
            ->pluck('juz_count', 'surah_id');

        return $surahs->map(fn($s) => [
            'id' => $s->id,
            'number' => $s->id,
            'name_ar' => $s->name_ar,
            'name_en' => $s->name_en,
            'revelation_place' => $s->revelation_place,
            'juz_count' => $juzCounts[$s->id] ?? 0,
            'ayah_count' => $s->ayahs_count,
        ]);
    }

    /**
     * Get juz list for a specific surah.
     *
     * @return Collection<int, array{id: int, number: int, name_ar: string}>
     */
    public static function juzForSurah(int $surahId): Collection
    {
        $surah = Surah::findOrFail($surahId);

        $juzIds = $surah->ayahs()
            ->distinct('juz_id')
            ->pluck('juz_id')
            ->filter()
            ->toArray();

        if (empty($juzIds)) {
            return collect();
        }

        return Juz::whereIn('id', $juzIds)
            ->orderBy('id', 'asc')
            ->get()
            ->map(fn($juz) => [
                'id' => $juz->id,
                'number' => $juz->id,
                'name_ar' => $juz->name ?? "الجزء " . $juz->id,
            ]);
    }

    /**
     * Get ayahs for a surah + juz combination.
     *
     * @return array{ayahs: Collection, count: int, from: int, to: int}
     */
    public static function ayahsForSurahJuz(int $surahId, int $juzId): array
    {
        $surah = Surah::findOrFail($surahId);
        $juz = Juz::findOrFail($juzId);

        $ayahs = $surah->ayahs()
            ->where('juz_id', $juz->id)
            ->orderBy('ayah_number')
            ->get()
            ->map(fn($a) => [
                'id' => (int) $a->id,
                'ayah_number' => (int) $a->ayah_number,
                'text' => (string) $a->text,
            ]);

        $numberedAyahs = $ayahs->where('ayah_number', '>', 0);

        return [
            'ayahs' => $ayahs,
            'count' => $ayahs->count(),
            'from' => $numberedAyahs->isNotEmpty() ? $numberedAyahs->first()['ayah_number'] : 1,
            'to' => $numberedAyahs->isNotEmpty() ? $numberedAyahs->last()['ayah_number'] : 1,
        ];
    }

    /**
     * Resolve surah and juz names from IDs.
     */
    public static function resolveNames(int $surahId, int $juzId): array
    {
        $surah = Surah::find($surahId);
        $juz = Juz::find($juzId);

        return [
            'surah_name' => $surah?->name_ar ?? '',
            'juz_name' => $juz?->name ?? $juzId,
        ];
    }
}
