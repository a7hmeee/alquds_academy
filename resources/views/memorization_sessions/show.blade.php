@extends('layouts.app')
@section('title', 'تفاصيل جلسة التسميع')
@section('page-title', 'تفاصيل جلسة التسميع')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">تفاصيل جلسة التسميع</h1>
            <p class="text-[var(--slate-blue)]">عرض تفاصيل جلسة التسميع والأخطاء والنتائج</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('memorization-sessions.edit', $session) }}"
               class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-edit"></i>
                تعديل
            </a>
            <a href="{{ route('memorization-sessions.index') }}"
               class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                رجوع
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-gradient-to-r from-green-900/30 to-emerald-900/20 border border-green-500/30 text-green-200 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-400"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Session Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">الطالب</div>
            <div class="text-[var(--cream)] font-bold">{{ $session->student?->user?->name ?? '—' }}</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">المعلم</div>
            <div class="text-[var(--cream)] font-bold">{{ $session->teacher?->user?->name ?? '—' }}</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">الحلقة</div>
            <div class="text-[var(--cream)] font-bold">{{ $session->circle?->name ?? '—' }}</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">التاريخ</div>
            <div class="text-[var(--cream)] font-bold">{{ $session->session_date ? \Carbon\Carbon::parse($session->session_date)->format('d/m/Y') : $session->created_at->format('d/m/Y') }}</div>
        </div>
    </div>

    {{-- Quran Range --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">السورة</div>
            <div class="text-[var(--cream)] font-bold text-lg">{{ $session->surah?->name_ar ?? '—' }}</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">الجزء</div>
            <div class="text-[var(--cream)] font-bold text-lg">{{ $session->juz?->name ?? '—' }}</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">الآيات</div>
            <div class="text-[var(--cream)] font-bold text-lg">
                {{ $session->ayah_from ?? '—' }} → {{ $session->ayah_to ?? '—' }}
            </div>
        </div>
    </div>

    {{-- Scores --}}
    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-4 flex items-center gap-2">
            <i class="fas fa-star text-[var(--gold)]"></i>
            نتائج التقييم
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center p-4 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
                <div class="text-sm text-[var(--slate-blue)] mb-2">الحفظ</div>
                <div class="text-2xl font-bold {{ ($session->memorization_score ?? 0) >= 70 ? 'text-green-400' : 'text-[var(--gold)]' }}">
                    {{ $session->memorization_score ?? '—' }}
                </div>
            </div>
            <div class="text-center p-4 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
                <div class="text-sm text-[var(--slate-blue)] mb-2">التجويد</div>
                <div class="text-2xl font-bold {{ ($session->tajweed_score ?? 0) >= 70 ? 'text-green-400' : 'text-[var(--gold)]' }}">
                    {{ $session->tajweed_score ?? '—' }}
                </div>
            </div>
            <div class="text-center p-4 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
                <div class="text-sm text-[var(--slate-blue)] mb-2">الطلاقة</div>
                <div class="text-2xl font-bold {{ ($session->fluency_score ?? 0) >= 70 ? 'text-green-400' : 'text-[var(--gold)]' }}">
                    {{ $session->fluency_score ?? '—' }}
                </div>
            </div>
            <div class="text-center p-4 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
                <div class="text-sm text-[var(--slate-blue)] mb-2">المجموع</div>
                <div class="text-2xl font-bold {{ ($session->total_score ?? 0) >= 70 ? 'text-green-400' : 'text-[var(--gold)]' }}">
                    {{ $session->total_score ?? '—' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Teacher Notes --}}
    @if($session->teacher_notes)
    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-3 flex items-center gap-2">
            <i class="fas fa-sticky-note text-[var(--gold)]"></i>
            ملاحظات المعلم
        </h3>
        <p class="text-[var(--cream)]">{{ $session->teacher_notes }}</p>
    </div>
    @endif

    {{-- Mistakes --}}
    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <h3 class="text-lg font-bold text-[var(--cream)] flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-[var(--gold)]"></i>
                الأخطاء المسجلة
            </h3>
            @if(auth()->user()->isTeacher() || auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            <button onclick="document.getElementById('addMistakeForm').classList.toggle('hidden')"
                    class="px-3 py-1.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 text-sm flex items-center gap-1">
                <i class="fas fa-plus"></i>
                إضافة خطأ
            </button>
            @endif
        </div>

        <div id="addMistakeForm" class="hidden mb-6 p-4 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
            <form method="POST" action="{{ route('memorization-sessions.mistakes.store', $session) }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-[var(--cream)]">رقم الآية</label>
                        <input type="number" name="ayah_number" required min="0"
                               class="w-full px-3 py-2 rounded-lg bg-[var(--green-900)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-[var(--cream)]">نوع الخطأ</label>
                        <select name="mistake_type" required
                                class="w-full px-3 py-2 rounded-lg bg-[var(--green-900)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="memorization">حفظ</option>
                            <option value="tajweed">تجويد</option>
                            <option value="haraka">حركة</option>
                            <option value="madd">مد</option>
                            <option value="ghunnah">غنّة</option>
                            <option value="makhraj">مخرج</option>
                            <option value="waqf_ibtida">وقف وابتداء</option>
                            <option value="omission">حذف</option>
                            <option value="repetition">تكرار</option>
                            <option value="hesitation">تردد</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-[var(--cream)]">الدرجة</label>
                        <select name="severity"
                                class="w-full px-3 py-2 rounded-lg bg-[var(--green-900)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="minor">بسيط</option>
                            <option value="moderate">متوسط</option>
                            <option value="major">كبير</option>
                            <option value="critical">خطير</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-[var(--cream)]">الكلمة (اختياري)</label>
                        <input type="text" name="word_text"
                               class="w-full px-3 py-2 rounded-lg bg-[var(--green-900)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]"
                               placeholder="الكلمة كما قرئت">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block mb-1 text-sm font-medium text-[var(--cream)]">تصويب المعلم (اختياري)</label>
                        <input type="text" name="correct_text"
                               class="w-full px-3 py-2 rounded-lg bg-[var(--green-900)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]"
                               placeholder="النص الصحيح">
                    </div>
                </div>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200">
                    <i class="fas fa-save"></i> حفظ الخطأ
                </button>
            </form>
        </div>

        @if($session->mistakes && $session->mistakes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[var(--border)]">
                            <th class="py-2 px-3 text-right text-sm text-[var(--slate-blue)]">#</th>
                            <th class="py-2 px-3 text-right text-sm text-[var(--slate-blue)]">الآية</th>
                            <th class="py-2 px-3 text-right text-sm text-[var(--slate-blue)]">النوع</th>
                            <th class="py-2 px-3 text-right text-sm text-[var(--slate-blue)]">الدرجة</th>
                            <th class="py-2 px-3 text-right text-sm text-[var(--slate-blue)]">الكلمة</th>
                            <th class="py-2 px-3 text-right text-sm text-[var(--slate-blue)]">الحالة</th>
                            <th class="py-2 px-3 text-right text-sm text-[var(--slate-blue)]"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($session->mistakes as $mistake)
                            <tr class="border-b border-[var(--border)] hover:bg-[var(--deep-green)]/5">
                                <td class="py-2 px-3 text-[var(--slate-blue)]">{{ $loop->iteration }}</td>
                                <td class="py-2 px-3 text-[var(--cream)]">{{ $mistake->ayah_number }}</td>
                                <td class="py-2 px-3 text-[var(--cream)]">
                                    @php
                                        $types = ['memorization'=>'حفظ','tajweed'=>'تجويد','haraka'=>'حركة','madd'=>'مد','ghunnah'=>'غنّة','makhraj'=>'مخرج','waqf_ibtida'=>'وقف وابتداء','omission'=>'حذف','repetition'=>'تكرار','hesitation'=>'تردد','other'=>'أخرى'];
                                    @endphp
                                    {{ $types[$mistake->mistake_type] ?? $mistake->mistake_type }}
                                </td>
                                <td class="py-2 px-3">
                                    @php
                                        $severityColors = ['minor'=>'text-green-400','moderate'=>'text-yellow-400','major'=>'text-orange-400','critical'=>'text-red-400'];
                                        $severityLabels = ['minor'=>'بسيط','moderate'=>'متوسط','major'=>'كبير','critical'=>'خطير'];
                                    @endphp
                                    <span class="{{ $severityColors[$mistake->severity] ?? 'text-[var(--slate-blue)]' }}">
                                        {{ $severityLabels[$mistake->severity] ?? $mistake->severity }}
                                    </span>
                                </td>
                                <td class="py-2 px-3 text-[var(--cream)]">{{ $mistake->word_text ?? '—' }}</td>
                                <td class="py-2 px-3">
                                    @if($mistake->is_resolved)
                                        <span class="text-green-400">محلول</span>
                                    @else
                                        <span class="text-yellow-400">معلق</span>
                                    @endif
                                </td>
                                <td class="py-2 px-3">
                                    @if(!$mistake->is_resolved)
                                        <form method="POST" action="{{ route('memorization-mistakes.resolve', $mistake) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-green-400 hover:text-green-300 text-sm">حل</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-[var(--slate-blue)] text-center py-4">لا توجد أخطاء مسجلة</p>
        @endif
    </div>

    {{-- Linked Submission --}}
    @if($session->submission)
    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-3 flex items-center gap-2">
            <i class="fas fa-file-audio text-[var(--gold)]"></i>
            التسجيل المرتبط
        </h3>
        <p class="text-[var(--cream)]">تسجيل الطالب: {{ $session->submission->surah_display ?? '—' }}</p>
        <a href="{{ route('submissions.review', $session->submission) }}" class="text-[var(--gold)] hover:underline text-sm">عرض التسجيل</a>
    </div>
    @endif

    {{-- Linked Assignment --}}
    @if($session->assignment)
    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-3 flex items-center gap-2">
            <i class="fas fa-tasks text-[var(--gold)]"></i>
            المهمة المرتبطة
        </h3>
        <p class="text-[var(--cream)]">{{ $session->assignment->surah?->name_ar ?? 'مهمة' }}</p>
        <a href="{{ route('memorization-assignments.show', $session->assignment) }}" class="text-[var(--gold)] hover:underline text-sm">عرض المهمة</a>
    </div>
    @endif
</div>
@endsection
