<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Surah;
use App\Models\Juz;
use App\Queries\Quran\SurahJuzQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuranAjaxController extends Controller
{
    public function __construct(private readonly SurahJuzQuery $query) {}

    public function surahs(): JsonResponse
    {
        return response()->json($this->query->allSurahs());
    }

    public function surahJuz(Surah $surah): JsonResponse
    {
        return response()->json($this->query->juzForSurah($surah->id));
    }

    public function surahJuzAyahs(Surah $surah, Juz $juz): JsonResponse
    {
        return response()->json($this->query->ayahsForSurahJuz($surah->id, $juz->id));
    }

    public function surahAyahs(Surah $surah): JsonResponse
    {
        $ayahs = $surah->ayahs()->with('juz')->get()->map(fn($ayah) => [
            'ayah_number' => $ayah->ayah_number,
            'text' => $ayah->text,
            'juz' => $ayah->juz->name,
            'juz_id' => $ayah->juz_id,
        ]);

        return response()->json([
            'surah' => [
                'id' => $surah->id,
                'name_ar' => $surah->name_ar,
                'name_en' => $surah->name_en,
                'verses_count' => $surah->verses_count,
            ],
            'ayahs' => $ayahs,
            'total' => $surah->ayahs()->where('ayah_number', '>', 0)->count(),
        ]);
    }

    public function juzList(): JsonResponse
    {
        $juzList = Juz::all()->map(fn($juz) => [
            'id' => $juz->id,
            'name' => $juz->name,
            'ayahs_count' => $juz->ayahs()->where('ayah_number', '>', 0)->count(),
        ]);

        return response()->json($juzList);
    }

    public function juzAyahs(Juz $juz): JsonResponse
    {
        $ayahs = $juz->ayahs()->with('surah')->orderBy('surah_id')->orderBy('ayah_number')->get()->map(fn($ayah) => [
            'ayah_number' => $ayah->ayah_number,
            'text' => $ayah->text,
            'surah_id' => $ayah->surah_id,
            'surah_name' => $ayah->surah->name_ar,
            'juz' => $ayah->juz->name,
        ]);

        return response()->json([
            'juz' => ['id' => $juz->id, 'name' => $juz->name],
            'ayahs' => $ayahs,
            'total' => $ayahs->count(),
        ]);
    }

    public function statistics(): JsonResponse
    {
        return response()->json([
            'summary' => [
                'total_surahs' => \App\Models\Surah::count(),
                'total_ayahs' => \App\Models\Ayah::where('ayah_number', '>', 0)->count(),
                'total_juz' => Juz::count(),
                'meccan_surahs' => \App\Models\Surah::where('revelation_place', 'مكية')->count(),
                'madinan_surahs' => \App\Models\Surah::where('revelation_place', 'مدنية')->count(),
            ],
        ]);
    }

    public function searchSurahs(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json($this->query->allSurahs()->map(fn($s) => [
                'id' => $s['id'],
                'name_ar' => $s['name_ar'],
                'name_en' => $s['name_en'],
                'verses_count' => $s['verses_count'],
            ]));
        }

        return response()->json($this->query->surahSearch($query));
    }
}
