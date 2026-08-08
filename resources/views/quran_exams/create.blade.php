@extends('layouts.app')
@section('title', 'إنشاء اختبار')
@section('page-title', 'إنشاء اختبار جديد')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">إنشاء اختبار جديد</h1>
            <p class="text-[var(--slate-blue)]">إنشاء اختبار قرآن للطلاب</p>
        </div>
        <a href="{{ route('quran-exams.index') }}"
           class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            رجوع
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-lg bg-gradient-to-r from-red-900/30 to-rose-900/20 border border-red-500/30 text-red-200">
            <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-exclamation-triangle text-red-400"></i>
                <span class="font-medium">يوجد أخطاء في النموذج:</span>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="main-content-section">
        <form method="POST" action="{{ route('quran-exams.store') }}" class="space-y-6">
            @csrf

            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">عنوان الاختبار <span class="text-red-400">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]"
                               placeholder="اختبار سورة...">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الحلقة <span class="text-red-400">*</span></label>
                        <select name="circle_id" required
                                class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="">اختر الحلقة</option>
                            @foreach($circles as $circle)
                                <option value="{{ $circle->id }}" @selected(old('circle_id') == $circle->id)>{{ $circle->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">نوع الاختبار <span class="text-red-400">*</span></label>
                        <select name="exam_type" required
                                class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="surah">سورة</option>
                            <option value="juz">جزء</option>
                            <option value="multiple_surahs">سور متعددة</option>
                            <option value="review">مراجعة</option>
                            <option value="oral">شفوي</option>
                            <option value="tajweed">تجويد</option>
                            <option value="random">عشوائي</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">تاريخ الاختبار <span class="text-red-400">*</span></label>
                        <input type="date" name="exam_date" value="{{ old('exam_date', date('Y-m-d')) }}" required
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">السورة (اختياري)</label>
                        <select name="surah_id"
                                class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="">— بدون —</option>
                            @foreach(\App\Models\Surah::orderBy('id')->get() as $surah)
                                <option value="{{ $surah->id }}" @selected(old('surah_id') == $surah->id)>{{ $surah->name_ar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الجزء (اختياري)</label>
                        <select name="juz_id"
                                class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="">— بدون —</option>
                            @foreach(\App\Models\Juz::orderBy('id')->get() as $juz)
                                <option value="{{ $juz->id }}" @selected(old('juz_id') == $juz->id)>{{ $juz->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الدرجة القصوى</label>
                        <input type="number" name="total_score" value="{{ old('total_score', 100) }}" min="1" max="1000"
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">درجة النجاح</label>
                        <input type="number" name="passing_score" value="{{ old('passing_score', 70) }}" min="1" max="1000"
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block mb-2 text-sm font-medium text-[var(--cream)]">تعليمات (اختياري)</label>
                    <textarea name="instructions" rows="3"
                              class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] resize-none"
                              placeholder="تعليمات الاختبار...">{{ old('instructions') }}</textarea>
                </div>
            </div>

            <div class="flex flex-wrap justify-between gap-4 pt-6 border-t border-[var(--border)]">
                <a href="{{ route('quran-exams.index') }}"
                   class="px-6 py-3 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-times"></i> إلغاء
                </a>
                <button type="submit"
                        class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-save"></i> إنشاء الاختبار
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
