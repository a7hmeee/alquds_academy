<?php

namespace App\Services;

use App\Models\Surah;
use App\Models\StudentSubmission;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class JuzProgressService
{
    private const JUZ_STARTS = [
        1  => [1, 1],
        2  => [2, 142],
        3  => [2, 253],
        4  => [3, 93],
        5  => [4, 24],
        6  => [4, 148],
        7  => [5, 82],
        8  => [6, 111],
        9  => [7, 88],
        10 => [8, 41],
        11 => [9, 93],
        12 => [11, 6],
        13 => [12, 53],
        14 => [15, 1],
        15 => [17, 1],
        16 => [18, 75],
        17 => [21, 1],
        18 => [23, 1],
        19 => [25, 21],
        20 => [27, 56],
        21 => [29, 46],
        22 => [33, 31],
        23 => [36, 28],
        24 => [39, 32],
        25 => [41, 47],
        26 => [46, 1],
        27 => [51, 31],
        28 => [58, 1],
        29 => [67, 1],
        30 => [78, 1],
    ];

    private static function juzSurahRangesCacheKey(int $juzId): string
    {
        return "juz.{$juzId}.surah_ranges";
    }

    public static function getJuzSurahRanges(int $juzId): Collection
    {
        return Cache::rememberForever(self::juzSurahRangesCacheKey($juzId), function () use ($juzId) {
            if (!isset(self::JUZ_STARTS[$juzId])) {
                return collect();
            }

            [$startSurah, $startAyah] = self::JUZ_STARTS[$juzId];

            if ($juzId < 30 && isset(self::JUZ_STARTS[$juzId + 1])) {
                [$nextSurah, $nextAyah] = self::JUZ_STARTS[$juzId + 1];
                $endAyah = $nextAyah - 1;
                $endSurah = $nextSurah;
                if ($endAyah < 1) {
                    $endSurah = $nextSurah - 1;
                    $endAyah = Surah::find($endSurah)?->verses_count ?? 1;
                }
            } else {
                $endSurah = 114;
                $endAyah = Surah::find(114)?->verses_count ?? 6;
            }

            $surahs = Surah::whereBetween('id', [$startSurah, $endSurah])
                ->orderBy('id')
                ->get();

            $result = collect();

            foreach ($surahs as $surah) {
                if ($surah->id == $startSurah && $surah->id == $endSurah) {
                    $from = $startAyah;
                    $to = $endAyah;
                } elseif ($surah->id == $startSurah) {
                    $from = $startAyah;
                    $to = $surah->verses_count;
                } elseif ($surah->id == $endSurah) {
                    $from = 1;
                    $to = $endAyah;
                } else {
                    $from = 1;
                    $to = $surah->verses_count;
                }

                $result->push([
                    'surah_id' => $surah->id,
                    'surah_name' => $surah->name_ar,
                    'from_ayah' => $from,
                    'to_ayah' => $to,
                    'total_ayahs' => $to - $from + 1,
                ]);
            }

            return $result;
        });
    }

    public static function calculate(int $studentId, int $juzId, ?int $circleId = null, int $passingScore = 70): array
    {
        $cacheKey = "juz_progress.{$studentId}.{$juzId}." . ($circleId ?? 'all');

        return Cache::remember($cacheKey, 300, function () use ($studentId, $juzId, $circleId, $passingScore) {
            $surahRanges = self::getJuzSurahRanges($juzId);

            if ($surahRanges->isEmpty()) {
                return [
                    'total_percent' => 0,
                    'total_ayahs' => 0,
                    'covered_ayahs' => 0,
                    'surahs' => collect(),
                ];
            }

            $surahIds = $surahRanges->pluck('surah_id')->toArray();

            $query = StudentSubmission::where('student_id', $studentId)
                ->where('juz_id', $juzId)
                ->whereNotNull('score')
                ->where('score', '>=', $passingScore)
                ->whereIn('surah_id', $surahIds)
                ->whereNotNull('ayah_from');

            if ($circleId) {
                $query->where('circle_id', $circleId);
            }

            $approvedSubmissions = $query->get();

            $totalAyahsInJuz = 0;
            $totalCoveredInJuz = 0;
            $surahDetails = collect();

            foreach ($surahRanges as $range) {
                $surahId = $range['surah_id'];
                $fromAyah = $range['from_ayah'];
                $toAyah = $range['to_ayah'];
                $ayahCount = $range['total_ayahs'];
                $surahName = $range['surah_name'];

                $totalAyahsInJuz += $ayahCount;

                $surahSubmissions = $approvedSubmissions->where('surah_id', $surahId);
                $coveredAyahs = self::countCoveredAyahs($surahSubmissions, $fromAyah, $toAyah);
                $totalCoveredInJuz += $coveredAyahs;

                $allQuery = StudentSubmission::where('student_id', $studentId)
                    ->where('juz_id', $juzId)
                    ->where('surah_id', $surahId)
                    ->latest();
                if ($circleId) {
                    $allQuery->where('circle_id', $circleId);
                }
                $allSurahSubmissions = $allQuery->get();

                $surahPercent = $ayahCount > 0 ? round(($coveredAyahs / $ayahCount) * 100, 1) : 0;

                $surahDetails->push([
                    'surah_id' => $surahId,
                    'surah_name' => $surahName,
                    'total_ayahs' => $ayahCount,
                    'covered_ayahs' => $coveredAyahs,
                    'percent' => $surahPercent,
                    'min_ayah' => $fromAyah,
                    'max_ayah' => $toAyah,
                    'submissions' => $allSurahSubmissions,
                    'approved_count' => $surahSubmissions->count(),
                    'avg_score' => $surahSubmissions->count() > 0 ? round($surahSubmissions->avg('score'), 1) : null,
                ]);
            }

            $totalPercent = $totalAyahsInJuz > 0 ? round(($totalCoveredInJuz / $totalAyahsInJuz) * 100, 1) : 0;

            return [
                'total_percent' => $totalPercent,
                'total_ayahs' => $totalAyahsInJuz,
                'covered_ayahs' => $totalCoveredInJuz,
                'surahs' => $surahDetails,
            ];
        });
    }

    public static function clearStudentCache(int $studentId, ?int $juzId = null): void
    {
        if ($juzId) {
            Cache::forget("juz_progress.{$studentId}.{$juzId}.all");
            for ($c = 1; $c <= 50; $c++) {
                Cache::forget("juz_progress.{$studentId}.{$juzId}.{$c}");
            }
        }
    }

    private static function countCoveredAyahs(Collection $submissions, int $rangeMin, int $rangeMax): int
    {
        if ($submissions->isEmpty()) {
            return 0;
        }

        $covered = [];
        foreach ($submissions as $sub) {
            $from = $sub->ayah_from ?? $rangeMin;
            $to = $sub->ayah_to ?? $from;

            $from = max($from, $rangeMin);
            $to = min($to, $rangeMax);

            for ($i = $from; $i <= $to; $i++) {
                $covered[$i] = true;
            }
        }

        return count($covered);
    }
}
