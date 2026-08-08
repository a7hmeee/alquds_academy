@extends('layouts.app')

@section('title', 'إضافة طالب')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 to-slate-800 py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- الرأس -->
        <div class="mb-8">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-400 to-yellow-500 flex items-center justify-center text-slate-900">
                        <i class="fas fa-user-plus text-lg"></i>
                    </div>
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-300">
                        إضافة طالب جديد
                    </h1>
                </div>
                <a href="{{ route('students.import.show') }}" class="px-4 py-2 bg-slate-700/50 border border-slate-600 text-slate-300 rounded-lg hover:border-blue-400 hover:text-blue-400 transition text-sm font-bold">
                    <i class="fas fa-file-import mr-2"></i>استيراد بحري
                </a>
            </div>
            <p class="text-slate-400 text-lg">قم بملء النموذج لإضافة طالب جديد للنظام</p>
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

        <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- البطاقة 1: حساب الدخول -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl hover:border-yellow-400/40 transition duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center">
                        <i class="fas fa-key text-yellow-400"></i>
                    </div>
                    <h2 class="text-xl font-bold text-yellow-400">حساب الدخول</h2>
                    <span class="text-xs text-slate-400 font-semibold">(اختياري)</span>
                </div>

                <div class="space-y-4">
                    <!-- ربط بمستخدم موجود -->
                    <div>
                        <label class="block text-slate-300 font-semibold mb-2">ربط بمستخدم موجود</label>
                        <select name="user_id" class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 focus:border-yellow-400 focus:outline-none transition">
                            <option value="" class="bg-slate-800">— إنشاء مستخدم جديد —</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" class="bg-slate-800">{{ $user->email }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="relative flex items-center my-4">
                        <div class="flex-1 border-t border-slate-600"></div>
                        <span class="px-3 text-slate-500 text-sm">أو</span>
                        <div class="flex-1 border-t border-slate-600"></div>
                    </div>

                    <!-- البريد الإلكتروني -->
                    <div>
                        <label class="block text-slate-300 font-semibold mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 placeholder-slate-500 focus:border-yellow-400 focus:outline-none transition"
                               placeholder="student@email.com">
                    </div>

                    <!-- كلمة السر -->
                    <div>
                        <label class="block text-slate-300 font-semibold mb-2">كلمة السر</label>
                        <input type="password" name="password" 
                               class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 placeholder-slate-500 focus:border-yellow-400 focus:outline-none transition"
                               placeholder="••••••••">
                    </div>

                    <p class="text-xs text-slate-400 bg-slate-700/30 rounded p-3 border border-slate-600">
                        <i class="fas fa-info-circle ml-2 text-yellow-400"></i>
                        سيتم إنشاء مستخدم جديد باستخدام البريد الإلكتروني وكلمة السر
                    </p>
                </div>
            </div>

            <!-- البطاقة 2: معلومات الطالب -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl hover:border-yellow-400/40 transition duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center">
                        <i class="fas fa-user text-yellow-400"></i>
                    </div>
                    <h2 class="text-xl font-bold text-yellow-400">معلومات الطالب</h2>
                </div>

                <div class="space-y-4">
                    <!-- الاسم الكامل -->
                    <div>
                        <label class="block text-slate-300 font-semibold mb-2">الاسم الكامل <span class="text-red-400">*</span></label>
                        <input type="text" name="full_name" required value="{{ old('full_name') }}"
                               class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 placeholder-slate-500 focus:border-yellow-400 focus:outline-none transition"
                               placeholder="أدخل الاسم الكامل">
                    </div>

                    <!-- الهاتف -->
                    <div>
                        <label class="block text-slate-300 font-semibold mb-2">رقم الهاتف</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 placeholder-slate-500 focus:border-yellow-400 focus:outline-none transition"
                               placeholder="+966 xx xxx xxxx">
                    </div>

                    <!-- تاريخ الميلاد والجنس -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-300 font-semibold mb-2">تاريخ الميلاد</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                                   class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 focus:border-yellow-400 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-slate-300 font-semibold mb-2">الجنس</label>
                            <select name="gender" class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 focus:border-yellow-400 focus:outline-none transition">
                                <option value="" class="bg-slate-800">— اختر —</option>
                                <option value="male" class="bg-slate-800">ذكر</option>
                                <option value="female" class="bg-slate-800">أنثى</option>
                            </select>
                        </div>
                    </div>

                    <!-- معلومات ولي الأمر -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-300 font-semibold mb-2">اسم ولي الأمر</label>
                            <input type="text" name="guardian_name" value="{{ old('guardian_name') }}"
                                   class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 placeholder-slate-500 focus:border-yellow-400 focus:outline-none transition"
                                   placeholder="اسم ولي الأمر">
                        </div>
                        <div>
                            <label class="block text-slate-300 font-semibold mb-2">هاتف ولي الأمر</label>
                            <input type="text" name="guardian_phone" value="{{ old('guardian_phone') }}"
                                   class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 placeholder-slate-500 focus:border-yellow-400 focus:outline-none transition"
                                   placeholder="+966 xx xxx xxxx">
                        </div>
                    </div>
                </div>
            </div>

            <!-- البطاقة 3: معلومات التحفيظ القرآني -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl hover:border-yellow-400/40 transition duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center">
                        <i class="fas fa-quran text-yellow-400"></i>
                    </div>
                    <h2 class="text-xl font-bold text-yellow-400">معلومات التحفيظ القرآني</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- مستوى التحفظ -->
                    <div>
                        <label class="block text-slate-300 font-semibold mb-2">مستوى التحفظ</label>
                        <select id="memorization_level" name="memorization_level" 
                                class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 focus:border-yellow-400 focus:outline-none transition">
                            <option value="لا يحفظ" class="bg-slate-800">لا يحفظ</option>
                            <option value="جزء" class="bg-slate-800">جزء واحد</option>
                            <option value="عدة أجزاء" class="bg-slate-800">عدة أجزاء</option>
                            <option value="ختمة" class="bg-slate-800">ختمة كاملة</option>
                        </select>
                    </div>

                    <!-- مستوى التجويد -->
                    <div>
                        <label class="block text-slate-300 font-semibold mb-2">مستوى التجويد</label>
                        <select name="tajweed_level" 
                                class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 focus:border-yellow-400 focus:outline-none transition">
                            <option value="" class="bg-slate-800">— لم يتحدد —</option>
                            <option value="ضعيف" class="bg-slate-800">ضعيف</option>
                            <option value="متوسط" class="bg-slate-800">متوسط</option>
                            <option value="جيد" class="bg-slate-800">جيد</option>
                            <option value="ممتاز" class="bg-slate-800">ممتاز</option>
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
                                <option value="" class="bg-slate-800">— اختر الجزء —</option>
                            </select>
                        </div>

                        <!-- الآيات -->
                        <div>
                            <label class="block text-slate-300 font-semibold mb-2">الآيات</label>
                            <select id="ayah" name="ayah" 
                                    class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 focus:border-yellow-400 focus:outline-none transition disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                <option value="" class="bg-slate-800">— اختر الآية —</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- الخيارات الإضافية -->
                <div class="flex flex-wrap gap-4 sm:gap-6 pt-4 mt-4 border-t border-slate-600">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_smart_mode" value="1" class="w-4 h-4 rounded border-slate-600 bg-slate-700 cursor-pointer">
                        <span class="text-slate-300 group-hover:text-yellow-400 transition">وضع ذكي</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="needs_assistance" value="1" class="w-4 h-4 rounded border-slate-600 bg-slate-700 cursor-pointer">
                        <span class="text-slate-300 group-hover:text-yellow-400 transition">يحتاج مساعدة</span>
                    </label>
                </div>
            </div>

            <!-- البطاقة 4: الإعدادات الإضافية -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl hover:border-yellow-400/40 transition duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center">
                        <i class="fas fa-cog text-yellow-400"></i>
                    </div>
                    <h2 class="text-xl font-bold text-yellow-400">الإعدادات الإضافية</h2>
                </div>

                <div class="space-y-4">
                    <!-- الحالة -->
                    <div>
                        <label class="block text-slate-300 font-semibold mb-2">الحالة</label>
                        <select name="status" class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 focus:border-yellow-400 focus:outline-none transition">
                            <option value="active" class="bg-slate-800">🟢 نشط</option>
                            <option value="paused" class="bg-slate-800">⏸️ موقوف</option>
                            <option value="archived" class="bg-slate-800">📦 مؤرشف</option>
                        </select>
                    </div>

                    <!-- الصورة -->
                    <div>
                        <label class="block text-slate-300 font-semibold mb-2">صورة الطالب</label>
                        <input type="file" name="photo" accept="image/*"
                               class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-yellow-400 file:text-slate-900 hover:file:bg-yellow-500 transition">
                    </div>

                    <!-- الملاحظات -->
                    <div>
                        <label class="block text-slate-300 font-semibold mb-2">ملاحظات</label>
                        <textarea name="notes" rows="3"
                                  class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2.5 text-slate-100 placeholder-slate-500 focus:border-yellow-400 focus:outline-none transition resize-none"
                                  placeholder="أي ملاحظات إضافية..."></textarea>
                    </div>
                </div>
            </div>

            <!-- الأزرار -->
            <div class="flex flex-wrap justify-end gap-4 pt-6">
                <a href="{{ route('students.index') }}" class="px-6 py-3 border-2 border-slate-600 text-slate-300 rounded-lg font-semibold hover:border-yellow-400 hover:text-yellow-400 transition duration-300">
                    <i class="fas fa-times mr-2"></i>إلغاء
                </a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-slate-900 rounded-lg font-bold hover:shadow-lg hover:shadow-yellow-400/50 transition duration-300 transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i>حفظ الطالب
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
});
</script>
@endsection