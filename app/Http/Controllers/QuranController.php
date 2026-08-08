<?php

namespace App\Http\Controllers;

use App\Models\Surah;
use App\Models\Ayah;
use App\Models\Juz;
use App\Queries\Quran\SurahJuzQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuranController extends Controller
{
    /**
     * عرض جميع السور
     */
    public function index()
    {
        $surahs = Surah::paginate(20);
        
        return view('quran.index', [
            'surahs' => $surahs,
            'totalSurahs' => Surah::count(),
            'totalAyahs' => Ayah::where('ayah_number', '>', 0)->count(),
            'totalJuz' => Juz::count(),
        ]);
    }

    /**
     * عرض تفاصيل سورة واحدة
     */
    public function showSurah(Surah $surah)
    {
        $ayahs = $surah->ayahs()->with('juz')->get();
        
        Log::info('Quran showSurah', [
            'surah_id' => $surah->id,
            'name_ar' => $surah->name_ar,
            'verses_count' => $surah->verses_count,
            'total_records' => $ayahs->count(),
            'numbered_ayahs' => $ayahs->where('ayah_number', '>', 0)->count(),
            'has_basmala' => $ayahs->where('ayah_number', 0)->isNotEmpty(),
            'min_ayah_number' => $ayahs->min('ayah_number'),
            'max_ayah_number' => $ayahs->max('ayah_number'),
        ]);
        
        return view('quran.surah-detail', [
            'surah' => $surah,
            'ayahs' => $ayahs,
            'statistics' => [
                'ayahs_count' => $surah->ayahs()->where('ayah_number', '>', 0)->count(),
                'juz_list' => $surah->ayahs()->where('ayah_number', '>', 0)->distinct('juz_id')->pluck('juz.name', 'juz_id'),
            ]
        ]);
    }

    /**
     * عرض جميع الأجزاء
     */
    public function indexJuz()
    {
        $juzList = Juz::paginate(15);
        
        return view('quran.juz-index', [
            'juzList' => $juzList,
            'totalJuz' => Juz::count(),
        ]);
    }

    /**
     * عرض تفاصيل جزء واحد - السور فقط
     */
    public function showJuz(Juz $juz)
    {
        $juz->load('ayahs.surah');
        
        // الحصول على السور التي لها آيات في هذا الجزء
        $surahs = Surah::whereHas('ayahs', function($query) use ($juz) {
            $query->where('juz_id', $juz->id);
        })
        ->with(['ayahs' => function($query) use ($juz) {
            $query->where('juz_id', $juz->id)->orderBy('ayah_number');
        }])
        ->get();
        
        return view('quran.juz-detail', [
            'juz' => $juz,
            'surahs' => $surahs,
            'statistics' => [
                'ayahs_count' => $juz->ayahs()->where('ayah_number', '>', 0)->count(),
                'surahs_count' => $surahs->count(),
            ]
        ]);
    }

    /**
     * البحث عن السور بناءً على الاسم أو الآيات
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (strlen($query) < 2) {
            return back()->with('error', 'يجب أن تكون كلمة البحث أطول من حرف واحد');
        }

        // البحث عن السور حسب الاسم أو الآيات
        $surahs = Surah::where('name_ar', 'like', '%' . $query . '%')
            ->orWhere('name_en', 'like', '%' . $query . '%')
            ->orWhereHas('ayahs', function($q) use ($query) {
                $q->where('text', 'like', '%' . $query . '%');
            })
            ->with(['ayahs' => function($q) use ($query) {
                $q->where('text', 'like', '%' . $query . '%')->orderBy('ayah_number');
            }])
            ->distinct()
            ->paginate(12);
        
        // عد الآيات المطابقة
        $ayahsCount = Ayah::where('text', 'like', '%' . $query . '%')->count();

        return view('quran.search-results', [
            'query' => $query,
            'surahs' => $surahs,
            'total' => $surahs->count(),
            'ayahsCount' => $ayahsCount,
        ]);
    }

    /**
     * إحصائيات عامة
     */
    public function statistics()
    {
        $stats = [
            'summary' => [
                'total_surahs' => Surah::count(),
                'total_ayahs' => Ayah::where('ayah_number', '>', 0)->count(),
                'total_juz' => Juz::count(),
                'meccan_surahs' => Surah::where('revelation_place', 'مكية')->count(),
                'madinan_surahs' => Surah::where('revelation_place', 'مدنية')->count(),
            ],
            
            // أطول سورة
            'longest_surah' => Surah::orderBy('verses_count', 'desc')->first(),
            'shortest_surah' => Surah::orderBy('verses_count', 'asc')->first(),
            
            // توزيع الآيات على الأجزاء
            'ayahs_per_juz' => Juz::withCount(['ayahs' => function ($q) {
                $q->where('ayah_number', '>', 0);
            }])->get(),
            
            // توزيع الآيات على السور
            'surahs_list' => Surah::withCount(['ayahs' => fn($q) => $q->where('ayah_number', '>', 0)])
                ->get()->map(function($surah) {
                    return [
                        'id' => $surah->id,
                        'name' => $surah->name_ar,
                        'verses' => $surah->verses_count,
                        'revelation' => $surah->revelation_place,
                        'actual_verses' => (int) $surah->ayahs_count,
                    ];
                })
        ];

        return view('quran.statistics', ['stats' => $stats]);
    }

    /**
     * عرض آية معينة مع السياق
     */
    public function showAyah($surahId, $ayahNumber)
    {
        $ayah = Ayah::where('surah_id', $surahId)
            ->where('ayah_number', $ayahNumber)
            ->with('surah', 'juz')
            ->firstOrFail();

        // الآيات حول هذه الآية
        $previousAyah = Ayah::where('surah_id', $surahId)
            ->where('ayah_number', '<', $ayahNumber)
            ->orderBy('ayah_number', 'desc')
            ->first();

        $nextAyah = Ayah::where('surah_id', $surahId)
            ->where('ayah_number', '>', $ayahNumber)
            ->orderBy('ayah_number', 'asc')
            ->first();

        return view('quran.ayah-detail', [
            'ayah' => $ayah,
            'previousAyah' => $previousAyah,
            'nextAyah' => $nextAyah,
            'surah' => $ayah->surah,
            'juz' => $ayah->juz,
        ]);
    }

}
