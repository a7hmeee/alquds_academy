@extends('layouts.app')

@section('title','تعديل الطالب')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 to-slate-800 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- الرأس -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-400 to-yellow-500 flex items-center justify-center text-slate-900">
                    <i class="fas fa-edit text-lg"></i>
                </div>
                <h1 class="text-2xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-300">
                    تعديل بيانات الطالب
                </h1>
            </div>
            <p class="text-slate-400 text-lg break-words">{{ $student->full_name }}</p>
        </div>

        <!-- رسالة الأخطاء -->
        @if ($errors->any())
        <div class="mb-6 bg-red-900/30 border border-red-500/50 rounded-lg p-4">
            <div class="flex items-center gap-2 mb-3">
                <i class="fas fa-exclamation-circle text-red-400"></i>
                <span class="text-red-300 font-semibold">يوجد أخطاء في النموذج</span>
            </div>
            <ul class="space-y-2">
                @foreach ($errors->all() as $error)
                    <li class="text-red-300 text-sm flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST"
              action="{{ route('students.update',$student) }}"
              enctype="multipart/form-data"
              class="space-y-6">
        @csrf
        @method('PUT')

            <!-- البطاقة 1: المعلومات الأساسية -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl hover:border-yellow-400/40 transition duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center">
                        <i class="fas fa-user text-yellow-400"></i>
                    </div>
                    <h2 class="text-xl font-bold text-yellow-400">المعلومات الأساسية</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- الاسم الكامل -->
                    <div>
                        <label class="block text-slate-300 font-semibold mb-2">الاسم الكامل</label>
                        <input name="full_name"
                               value="{{ $student->full_name }}"
                               placeholder="أدخل الاسم الكامل"
                               class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 placeholder-slate-400 focus:border-yellow-400 focus:outline-none transition"
                               required>
                    </div>

                    <!-- المعلم -->
                    <div>
                        <label class="block text-slate-300 font-semibold mb-2">المعلم</label>
                        <select name="teacher_id" class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 focus:border-yellow-400 focus:outline-none transition">
                            <option value="">— بدون معلم —</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                    @selected($student->teacher_id==$teacher->id)>
                                    {{ $teacher->user?->name ?? $teacher->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- الحالة -->
                    <div>
                        <label class="block text-slate-300 font-semibold mb-2">الحالة</label>
                        <select name="status" class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 focus:border-yellow-400 focus:outline-none transition">
                            <option value="active" @selected($student->status=='active')>نشط</option>
                            <option value="paused" @selected($student->status=='paused')>موقوف</option>
                            <option value="archived" @selected($student->status=='archived')>مؤرشف</option>
                        </select>
                    </div>

                    <!-- الصورة -->
                    <div>
                        <label class="block text-slate-300 font-semibold mb-2">صورة الطالب</label>
                        <input type="file" name="photo" accept="image/*"
                               class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 focus:border-yellow-400 focus:outline-none transition file:bg-yellow-400 file:text-slate-900 file:font-bold file:rounded file:border-0 file:px-4 file:py-2 file:cursor-pointer hover:file:bg-yellow-300 transition">
                    </div>
                </div>

                <!-- الملاحظات -->
                <div class="mt-4">
                    <label class="block text-slate-300 font-semibold mb-2">ملاحظات</label>
                    <textarea name="notes"
                              placeholder="أضف أي ملاحظات عن الطالب"
                              rows="3"
                              class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 placeholder-slate-400 focus:border-yellow-400 focus:outline-none transition resize-none"></textarea>
                </div>
            </div>

            <!-- البطاقة 2: معلومات التحفظ القرآني -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl hover:border-yellow-400/40 transition duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center">
                        <i class="fas fa-quran text-yellow-400"></i>
                    </div>
                    <h2 class="text-xl font-bold text-yellow-400">معلومات التحفظ القرآني</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- مستوى التحفظ -->
                    <div>
                        <label class="block text-slate-300 font-semibold mb-2">مستوى التحفظ</label>
                        <select id="memorization_level" name="memorization_level" 
                                class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 focus:border-yellow-400 focus:outline-none transition">
                            <option value="لا يحفظ" @selected($student->memorization_level == 'لا يحفظ')>لا يحفظ</option>
                            <option value="جزء" @selected($student->memorization_level == 'جزء')>جزء واحد</option>
                            <option value="عدة أجزاء" @selected($student->memorization_level == 'عدة أجزاء')>عدة أجزاء</option>
                            <option value="ختمة" @selected($student->memorization_level == 'ختمة')>ختمة كاملة</option>
                        </select>
                    </div>

                    <!-- مستوى التجويد -->
                    <div>
                        <label class="block text-slate-300 font-semibold mb-2">مستوى التجويد</label>
                        <select name="tajweed_level" 
                                class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 focus:border-yellow-400 focus:outline-none transition">
                            <option value="" @selected(!$student->tajweed_level)>— لم يتحدد —</option>
                            <option value="ضعيف" @selected($student->tajweed_level == 'ضعيف')>ضعيف</option>
                            <option value="متوسط" @selected($student->tajweed_level == 'متوسط')>متوسط</option>
                            <option value="جيد" @selected($student->tajweed_level == 'جيد')>جيد</option>
                            <option value="ممتاز" @selected($student->tajweed_level == 'ممتاز')>ممتاز</option>
                        </select>
                    </div>
                </div>

                <!-- نموذج التحفظ - يظهر فقط إذا كان الطالب يحفظ -->
                <div id="memorization_form" class="mt-6 p-4 bg-slate-700/30 border border-slate-600/50 rounded-lg hidden">
                    <h3 class="text-yellow-400 font-bold mb-4">اختر السورة والجزء والآيات المحفوظة</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- البحث عن السورة -->
                        <div>
                            <label class="block text-slate-300 font-semibold mb-2">السورة</label>
                            <div class="relative">
                                <input type="text" id="surah_search" 
                                       placeholder="ابحث عن السورة..."
                                       class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 placeholder-slate-400 focus:border-yellow-400 focus:outline-none transition">
                                <div id="surah_dropdown" class="absolute top-full left-0 right-0 mt-1 bg-slate-800 border border-slate-600 rounded-lg shadow-lg max-h-64 overflow-y-auto hidden z-50">
                                    <!-- سيتم ملؤه من جانب JavaScript -->
                                </div>
                            </div>
                            <input type="hidden" id="surah_id" name="surah_id">
                            <div id="selected_surah" class="text-slate-400 text-sm mt-2">— لم يتم الاختيار —</div>
                        </div>

                        <!-- الجزء -->
                        <div>
                            <label class="block text-slate-300 font-semibold mb-2">الجزء</label>
                            <select id="juz_id" name="juz_id" 
                                    class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 focus:border-yellow-400 focus:outline-none transition disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                <option value="">— اختر الجزء —</option>
                            </select>
                        </div>

                        <!-- الآيات -->
                        <div>
                            <label class="block text-slate-300 font-semibold mb-2">الآيات</label>
                            <select id="ayah" name="ayah" 
                                    class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 focus:border-yellow-400 focus:outline-none transition disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                <option value="">— اختر الآية —</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الأزرار -->
            <div class="flex flex-wrap justify-end gap-3">
                <a href="{{ route('students.show', $student) }}" 
                   class="px-6 py-3 bg-slate-700/50 border border-slate-600 text-slate-100 rounded-lg font-bold hover:border-yellow-400 hover:text-yellow-400 transition duration-300">
                    <i class="fas fa-times mr-2"></i>إلغاء
                </a>
                <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-slate-900 rounded-lg font-bold hover:shadow-lg hover:shadow-yellow-400/50 transition duration-300 transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i>حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // العناصر
    const memorizationLevel = document.getElementById('memorization_level');
    const memorizationForm = document.getElementById('memorization_form');
    const surahSearch = document.getElementById('surah_search');
    const surahDropdown = document.getElementById('surah_dropdown');
    const surahId = document.getElementById('surah_id');
    const selectedSurah = document.getElementById('selected_surah');
    const juzSelect = document.getElementById('juz_id');
    const ayahSelect = document.getElementById('ayah');
    let allSurahs = [];

    // جلب جميع السور
    async function fetchAllSurahs() {
        try {
            const response = await fetch('/api/quran/surahs');
            allSurahs = await response.json();
        } catch (error) {
            console.error('خطأ في جلب السور:', error);
        }
    }

    // البحث عن السور
    surahSearch.addEventListener('input', () => {
        const query = surahSearch.value.toLowerCase();
        surahDropdown.innerHTML = '';

        if (!query) {
            surahDropdown.classList.add('hidden');
            return;
        }

        const filtered = allSurahs.filter(surah => 
            surah.name_ar.includes(query) || surah.name_en.toLowerCase().includes(query)
        );

        if (filtered.length === 0) {
            surahDropdown.innerHTML = '<div class="p-3 text-slate-400">لا توجد نتائج</div>';
        } else {
            filtered.forEach(surah => {
                const div = document.createElement('div');
                div.className = 'p-3 hover:bg-slate-700 cursor-pointer text-slate-200 border-b border-slate-700 last:border-b-0';
                div.innerHTML = `<strong>${surah.name_ar}</strong> (${surah.name_en}) - ${surah.verses_count} آية`;
                div.addEventListener('click', () => selectSurah(surah));
                surahDropdown.appendChild(div);
            });
        }

        surahDropdown.classList.remove('hidden');
    });

    // اختيار السورة
    function selectSurah(surah) {
        surahId.value = surah.id;
        selectedSurah.textContent = `${surah.name_ar} (${surah.name_en})`;
        surahSearch.value = '';
        surahDropdown.classList.add('hidden');
        
        // تحميل الأجزاء المرتبطة
        loadJuzForSurah(surah.id);
        
        // إعادة تعيين الآيات
        ayahSelect.innerHTML = '<option value="">— اختر الآية —</option>';
        ayahSelect.disabled = true;
    }

    // تحميل الأجزاء لسورة معينة
    async function loadJuzForSurah(surahId) {
        try {
            const response = await fetch(`/api/quran/surah/${surahId}/juz`);
            const juzList = await response.json();

            juzSelect.innerHTML = '<option value="">— اختر الجزء —</option>';
            juzList.forEach(juz => {
                const option = document.createElement('option');
                option.value = juz.id;
                option.textContent = juz.name;
                juzSelect.appendChild(option);
            });

            juzSelect.disabled = false;
        } catch (error) {
            console.error('خطأ في جلب الأجزاء:', error);
        }
    }

    // عند اختيار جزء
    juzSelect.addEventListener('change', async () => {
        if (!surahId.value || !juzSelect.value) {
            ayahSelect.innerHTML = '<option value="">— اختر الآية —</option>';
            ayahSelect.disabled = true;
            return;
        }

        try {
            const response = await fetch(`/api/quran/surah/${surahId.value}/juz/${juzSelect.value}/ayahs`);
            const ayahs = await response.json();

            ayahSelect.innerHTML = '<option value="">— اختر الآية —</option>';
            ayahs.forEach(ayah => {
                const option = document.createElement('option');
                option.value = ayah.ayah_number;
                option.textContent = `الآية ${ayah.ayah_number}`;
                ayahSelect.appendChild(option);
            });

            ayahSelect.disabled = false;
        } catch (error) {
            console.error('خطأ في جلب الآيات:', error);
        }
    });

    // إظهار/إخفاء نموذج التحفظ
    memorizationLevel.addEventListener('change', () => {
        if (memorizationLevel.value !== 'لا يحفظ') {
            memorizationForm.classList.remove('hidden');
        } else {
            memorizationForm.classList.add('hidden');
            surahId.value = '';
            juzSelect.value = '';
            ayahSelect.value = '';
            selectedSurah.textContent = '— لم يتم الاختيار —';
        }
    });

    // إغلاق القائمة المنسدلة عند النقر خارجها
    document.addEventListener('click', (e) => {
        if (e.target !== surahSearch && e.target !== surahDropdown) {
            surahDropdown.classList.add('hidden');
        }
    });

    // تحميل البيانات الأولية
    fetchAllSurahs();

    // تحديث حالة النموذج عند التحميل
    if (memorizationLevel.value !== 'لا يحفظ') {
        memorizationForm.classList.remove('hidden');
    }
});
</script>
@endsection