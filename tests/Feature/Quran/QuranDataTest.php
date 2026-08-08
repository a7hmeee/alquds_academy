<?php

namespace Tests\Feature\Quran;

use Tests\TestCase;
use App\Models\Surah;
use App\Models\Ayah;
use App\Models\Juz;

class QuranDataTest extends TestCase
{
    // لا نستخدم RefreshDatabase - بيانات القرآن مثبتة مسبقًا ولا نريد مسحها

    public function test_total_surahs_is_114(): void
    {
        $this->assertEquals(114, Surah::count());
    }

    public function test_total_juz_is_30(): void
    {
        $this->assertEquals(30, Juz::count());
    }

    public function test_numbered_ayahs_is_6236(): void
    {
        $this->assertEquals(6236, Ayah::where('ayah_number', '>', 0)->count());
    }

    public function test_basmala_records_is_112(): void
    {
        $this->assertEquals(112, Ayah::where('ayah_number', 0)->count());
    }

    public function test_total_records_is_6348(): void
    {
        $this->assertEquals(6348, Ayah::count());
    }

    public function test_fatiha_has_7_numbered_ayahs_and_no_basmala(): void
    {
        $surah = Surah::find(1);
        $this->assertNotNull($surah);
        $this->assertEquals(7, $surah->verses_count);
        $this->assertEquals(7, $surah->ayahs()->where('ayah_number', '>', 0)->count());
        $this->assertFalse($surah->ayahs()->where('ayah_number', 0)->exists(),
            'الفاتحة لا تحتوي بسملة مستقلة لأن البسملة هي الآية 1');
    }

    public function test_baqarah_has_basmala_and_286_numbered_ayahs(): void
    {
        $surah = Surah::find(2);
        $this->assertNotNull($surah);
        $this->assertEquals(286, $surah->verses_count);
        $this->assertEquals(286, $surah->ayahs()->where('ayah_number', '>', 0)->count());
        $this->assertTrue($surah->ayahs()->where('ayah_number', 0)->exists(),
            'البقرة تحتوي بسملة مستقلة');
    }

    public function test_tawbah_has_no_basmala(): void
    {
        $surah = Surah::find(9);
        $this->assertNotNull($surah);
        $this->assertFalse($surah->ayahs()->where('ayah_number', 0)->exists(),
            'التوبة لا تحتوي بسملة');
    }

    public function test_all_surahs_ayahs_are_ordered_ascending(): void
    {
        $surahs = Surah::all();
        foreach ($surahs as $surah) {
            $ayahNumbers = $surah->ayahs()->orderBy('ayah_number')->get()->pluck('ayah_number')->toArray();
            $sorted = $ayahNumbers;
            sort($sorted);
            $this->assertEquals($sorted, $ayahNumbers,
                "سورة {$surah->name_ar} (id={$surah->id}) ليست مرتبة تصاعديا");
        }
    }

    public function test_numbered_ayahs_per_surah_equals_verses_count(): void
    {
        $surahs = Surah::all();
        foreach ($surahs as $surah) {
            $numbered = $surah->ayahs()->where('ayah_number', '>', 0)->count();
            $this->assertEquals($surah->verses_count, $numbered,
                "سورة {$surah->name_ar} (id={$surah->id}): verses_count({$surah->verses_count}) != numbered({$numbered})");
        }
    }

    public function test_full_surah_does_not_drop_first_or_last_ayah(): void
    {
        $surahs = Surah::all();
        foreach ($surahs as $surah) {
            $ayahs = $surah->ayahs()->orderBy('ayah_number')->get();
            $this->assertNotNull($ayahs->first(), "سورة {$surah->name_ar} ليس بها أي سجلات");
            $this->assertNotNull($ayahs->last(), "سورة {$surah->name_ar} ليس بها أي سجلات");
            $this->assertContains($ayahs->first()->ayah_number, [0, 1],
                "أول ayah_number في سورة {$surah->name_ar} هو {$ayahs->first()->ayah_number} وليس 0 أو 1");
            $this->assertEquals($surah->verses_count, $ayahs->last()->ayah_number,
                "آخر ayah_number في سورة {$surah->name_ar} هو {$ayahs->last()->ayah_number} وليس {$surah->verses_count}");
        }
    }

    public function test_juz_ayahs_are_ordered_by_surah_then_ayah(): void
    {
        $juzs = Juz::all();
        foreach ($juzs as $juz) {
            $ayahs = $juz->ayahs()->orderBy('surah_id')->orderBy('ayah_number')->get();
            for ($i = 1; $i < $ayahs->count(); $i++) {
                $prev = $ayahs[$i - 1];
                $curr = $ayahs[$i];
                $ok = ($prev->surah_id < $curr->surah_id) ||
                    ($prev->surah_id == $curr->surah_id && $prev->ayah_number <= $curr->ayah_number);
                $this->assertTrue($ok,
                    "الجزء {$juz->id}: ترتيب خاطئ بعد id={$prev->id} (سورة={$prev->surah_id}, آية={$prev->ayah_number}) -> id={$curr->id} (سورة={$curr->surah_id}, آية={$curr->ayah_number})");
            }
        }
    }

    public function test_no_empty_text_in_ayahs(): void
    {
        $this->assertEquals(0, Ayah::whereNull('text')->orWhere('text', '')->count());
    }

    public function test_surah_relation_returns_ordered_ayahs(): void
    {
        $surah = Surah::find(2);
        $this->assertNotNull($surah);
        $ayahs = $surah->ayahs;
        for ($i = 1; $i < $ayahs->count(); $i++) {
            $this->assertGreaterThanOrEqual($ayahs[$i - 1]->ayah_number, $ayahs[$i]->ayah_number,
                "الآيات غير مرتبة في علاقة ayahs() لسورة البقرة");
        }
    }

    public function test_relation_does_not_lose_records(): void
    {
        $surahs = Surah::take(5)->get();
        foreach ($surahs as $surah) {
            $this->assertNotNull($surah);
            $fromRelation = $surah->ayahs()->count();
            $fromDirect = Ayah::where('surah_id', $surah->id)->count();
            $this->assertEquals($fromDirect, $fromRelation,
                "سورة {$surah->id}: ayahs() ترجع {$fromRelation} بينما المباشر يرجع {$fromDirect}");
        }
    }

    public function test_every_surah_id_in_ayahs_points_to_existing_surah(): void
    {
        $surahIds = Surah::pluck('id')->toArray();
        $ayahSurahIds = Ayah::distinct('surah_id')->pluck('surah_id')->toArray();
        foreach ($ayahSurahIds as $sid) {
            $this->assertContains($sid, $surahIds, "surah_id {$sid} في ayahs لا يشير لسورة موجودة");
        }
    }

    public function test_every_juz_id_in_ayahs_points_to_existing_juz(): void
    {
        $juzIds = Juz::pluck('id')->toArray();
        $ayahJuzIds = Ayah::distinct('juz_id')->pluck('juz_id')->toArray();
        foreach ($ayahJuzIds as $jid) {
            $this->assertContains($jid, $juzIds, "juz_id {$jid} في ayahs لا يشير لجزء موجود");
        }
    }
}
