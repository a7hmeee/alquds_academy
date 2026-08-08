<?php

namespace App\Services;

use App\Models\Surah;
use App\Models\Juz;
use App\Models\Ayah;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    private const TTL = 86400; // 24 hours

    public static function getAllSurahs()
    {
        return Cache::remember('surahs.all.v2', self::TTL, function () {
            return Surah::withCount(['ayahs' => function ($q) {
                $q->where('ayah_number', '>', 0);
            }])->orderBy('id')->get();
        });
    }

    public static function getSurah(int $id)
    {
        return Cache::remember("surah.{$id}.v2", self::TTL, function () use ($id) {
            return Surah::with(['ayahs' => function ($q) {
                $q->orderBy('ayah_number');
            }])->findOrFail($id);
        });
    }

    public static function getSurahAyahs(int $surahId)
    {
        return Cache::remember("surah.{$surahId}.ayahs.v2", self::TTL, function () use ($surahId) {
            return Ayah::where('surah_id', $surahId)->with('juz')->orderBy('ayah_number')->get();
        });
    }

    public static function getAllJuz()
    {
        return Cache::remember('juz.all.v2', self::TTL, function () {
            return Juz::withCount(['ayahs' => function ($q) {
                $q->where('ayah_number', '>', 0);
            }])->orderBy('id')->get();
        });
    }

    public static function getJuz(int $id)
    {
        return Cache::remember("juz.{$id}.v2", self::TTL, function () use ($id) {
            return Juz::with(['ayahs' => function ($q) {
                $q->orderBy('surah_id')->orderBy('ayah_number');
            }, 'ayahs.surah'])->findOrFail($id);
        });
    }

    public static function getJuzAyahs(int $juzId)
    {
        return Cache::remember("juz.{$juzId}.ayahs.v2", self::TTL, function () use ($juzId) {
            return Ayah::where('juz_id', $juzId)->with('surah')->orderBy('surah_id')->orderBy('ayah_number')->get();
        });
    }

    public static function getQuranStatistics()
    {
        return Cache::remember('quran.statistics.v2', self::TTL, function () {
            return [
                'total_surahs' => Surah::count(),
                'total_ayahs' => Ayah::where('ayah_number', '>', 0)->count(),
                'total_juz' => Juz::count(),
                'meccan_surahs' => Surah::where('revelation_place', 'مكية')->count(),
                'madinan_surahs' => Surah::where('revelation_place', 'مدنية')->count(),
                'ayahs_per_juz' => Juz::withCount(['ayahs' => function ($q) {
                    $q->where('ayah_number', '>', 0);
                }])->get(),
            ];
        });
    }

    public static function getSurahSearchResults(string $query)
    {
        $cacheKey = 'surah.search.' . md5($query);
        return Cache::remember($cacheKey, 3600, function () use ($query) {
            return Surah::where('name_ar', 'like', '%' . $query . '%')
                ->orWhere('name_en', 'like', '%' . $query . '%')
                ->orWhereHas('ayahs', function ($q) use ($query) {
                    $q->where('text', 'like', '%' . $query . '%');
                })
                ->with(['ayahs' => function ($q) use ($query) {
                    $q->where('text', 'like', '%' . $query . '%')->orderBy('ayah_number');
                }])
                ->distinct()
                ->get();
        });
    }

    public static function clearQuranCache(): void
    {
        Cache::forget('surahs.all');
        Cache::forget('surahs.all.v2');
        Cache::forget('juz.all');
        Cache::forget('juz.all.v2');
        Cache::forget('quran.statistics');
        Cache::forget('quran.statistics.v2');
    }
}
