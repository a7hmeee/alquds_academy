@extends('layouts.student')

@section('title','لوحة الطالب')
@section('page-title', 'لوحة الطالب')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-4xl font-bold text-[var(--cream)]">مرحبًا، {{ auth()->user()->name }} 👋</h1>
            <p class="text-[var(--slate-blue)] mt-2">لوحة متابعة تقدمك في حفظ القرآن الكريم</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('student-submissions.index') }}" class="px-4 py-3 rounded-lg bg-[var(--gold)] text-[var(--dark-bg)] font-bold hover:bg-[var(--gold)]/90 transition-colors">📋 صوتياتي</a>
        </div>
    </div>

    {{-- Main Circle Info --}}
    @php $student = auth()->user()->studentProfile; $circle = $student?->circle ?? null; @endphp

    @if($circle)
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Circle Card --}}
        <div class="md:col-span-2 p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--surface)] to-[var(--dark-bg)]/50 shadow-lg">
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-sm font-semibold text-[var(--slate-blue)] uppercase tracking-wider">الحلقة الحالية</h3>
                <span class="px-3 py-1 rounded-full bg-[var(--gold)]/20 text-[var(--gold)] text-xs font-bold">نشطة</span>
            </div>
            <h2 class="text-2xl font-bold text-[var(--cream)] mb-1">{{ $circle->name }}</h2>
            <div class="flex items-center text-[var(--slate-blue)] text-sm mb-4">
                <span class="ml-2">👨‍🏫</span>
                <span>{{ $circle->teacher?->user?->name ?? 'بدون معلم' }}</span>
            </div>
            <div class="flex gap-3 mt-4">
                <a href="{{ route('circles.show', $circle) }}" class="flex-1 text-center px-3 py-2 rounded-lg bg-[var(--deep-green)]/20 border border-[var(--deep-green)]/30 text-[var(--deep-green)] hover:bg-[var(--deep-green)]/30 transition-colors font-medium">📖 تفاصيل الحلقة</a>
                <a href="{{ route('circles.submissions.create', $circle) }}" class="flex-1 text-center px-3 py-2 rounded-lg bg-[var(--gold)]/20 border border-[var(--gold)]/30 text-[var(--gold)] hover:bg-[var(--gold)]/30 transition-colors font-medium">⬆️ رفع صوتية</a>
            </div>
        </div>

        {{-- Progress Card with Visual Indicator --}}
        <div class="md:col-span-2 p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--surface)] to-[var(--dark-bg)]/50 shadow-lg">
            <h3 class="text-sm font-semibold text-[var(--slate-blue)] uppercase tracking-wider mb-4">التقدم الحالي</h3>
            
            <div class="flex items-center gap-6">
                {{-- Circular Progress --}}
                <div class="relative w-24 h-24 flex-shrink-0">
                    <svg class="transform -rotate-90 w-24 h-24" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="2" class="text-[var(--dark-bg)]/30"/>
                        <circle 
                            cx="50" cy="50" r="45" 
                            fill="none" 
                            stroke="currentColor" 
                            stroke-width="3" 
                            stroke-dasharray="282.6" 
                            stroke-dashoffset="{{ 282.6 * (1 - ($student->progress_percent ?? 0) / 100) }}"
                            class="text-[var(--gold)] transition-all duration-500"
                            stroke-linecap="round"
                        />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-xl font-bold text-[var(--cream)]">{{ $student->progress_percent ?? 0 }}%</span>
                    </div>
                </div>

                {{-- Text Info --}}
                <div>
                    <div class="mb-4">
                        <p class="text-[var(--slate-blue)] text-xs mb-1">الموضع الحالي</p>
                        <p class="text-[var(--cream)] text-lg font-bold">جزء {{ $student->current_juz ?? '-' }} • {{ $student->current_surah ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[var(--slate-blue)] text-xs mb-1">مستوى التجويد</p>
                        <p class="text-[var(--deep-green)] font-semibold">{{ $student->tajweed_level ?? 'لم تُحدد' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Latest Rating Card --}}
    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--gold)]/5 to-[var(--dark-bg)]/50">
        <h3 class="text-sm font-semibold text-[var(--slate-blue)] uppercase tracking-wider mb-4">⭐ آخر تقييم</h3>
        @if($student->latestProgress)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-[var(--slate-blue)] text-sm mb-2">التقييم الأخير</p>
                    <p class="text-3xl font-bold text-[var(--gold)]">{{ $student->latestProgress->rating ?? '-' }}/5</p>
                </div>
                <div>
                    <p class="text-[var(--slate-blue)] text-sm mb-2">التاريخ</p>
                    <p class="text-[var(--cream)] font-semibold">{{ $student->latestProgress->created_at->format('d/m/Y') }}</p>
                </div>
                <div>
                    <a href="{{ route('circles.my-progress', $circle) }}" class="inline-block px-4 py-2 rounded-lg bg-[var(--deep-green)]/20 border border-[var(--deep-green)]/30 text-[var(--deep-green)] hover:bg-[var(--deep-green)]/30 transition-colors font-medium">
                        📊 عرض التقدم الكامل
                    </a>
                </div>
            </div>
        @else
            <p class="text-[var(--slate-blue)] py-3">لم تُسجَّل تقييمات بعد. قدّم صوتيات لتحصل على ملاحظات من المعلم.</p>
        @endif
    </div>

    {{-- Recent Submissions --}}
    @php
        $recentSubmissions = $student?->submissions()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get() ?? collect();
    @endphp

    @if($recentSubmissions->count() > 0)
    <div class="border-t border-[var(--border)] pt-8">
        <h2 class="text-2xl font-bold text-[var(--cream)] mb-4">📁 آخر الصوتيات المرفوعة</h2>
        <div class="space-y-3">
            @foreach($recentSubmissions as $submission)
                <div class="p-4 rounded-lg border border-[var(--border)] bg-[var(--dark-bg)]/30 hover:bg-[var(--dark-bg)]/50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-[var(--cream)] font-semibold">
                                🎵 {{ $submission->surah ?? 'سورة غير محددة' }}
                            </p>
                            <div class="flex gap-4 mt-2 text-sm text-[var(--slate-blue)]">
                                <span>📅 {{ $submission->created_at->format('d M Y') }}</span>
                                @if($submission->reviewed_at)
                                    <span class="text-[var(--gold)]">✓ تمت المراجعة</span>
                                @else
                                    <span class="text-[var(--slate-blue)]">⏳ في انتظار المراجعة</span>
                                @endif
                            </div>
                        </div>
                        @if($submission->status === 'reviewed')
                            <div class="text-right mr-4">
                                <p class="text-xs text-[var(--slate-blue)] mb-1">التقييم</p>
                                <p class="text-xl font-bold text-[var(--gold)]">{{ $submission->rating }}/5</p>
                            </div>
                        @endif
                    </div>
                    @if($submission->review_notes)
                        <div class="mt-3 p-3 rounded bg-[var(--dark-bg)]/50 text-sm text-[var(--slate-blue)] border-r-2 border-[var(--gold)]">
                            <p class="font-semibold text-[var(--gold)] mb-1">📝 ملاحظات المعلم:</p>
                            <p>{{ $submission->review_notes }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Other Circles --}}
    @php
        $allCircles = $student?->circles()
            ->with('circle')
            ->get()
            ->pluck('circle')
            ->filter(fn($c) => $c && $c->id !== $circle?->id)
            ->values() ?? collect();
    @endphp

    @if($allCircles->count() > 0)
    <div class="border-t border-[var(--border)] pt-8">
        <h2 class="text-2xl font-bold text-[var(--cream)] mb-4">🎓 حلقاتي الأخرى</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($allCircles as $c)
                <div class="p-5 rounded-lg border border-[var(--border)] bg-[var(--dark-bg)]/30 hover:bg-[var(--dark-bg)]/50 transition-colors">
                    <h3 class="font-bold text-[var(--cream)] mb-2">{{ $c->name }}</h3>
                    <div class="flex items-center text-sm text-[var(--slate-blue)] mb-4">
                        <span class="ml-2">👨‍🏫</span>
                        <span>{{ $c->teacher?->user?->name ?? 'بدون معلم' }}</span>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('circles.my-progress', $c) }}" class="flex-1 text-center px-3 py-2 rounded bg-[var(--deep-green)]/20 text-[var(--deep-green)] text-sm hover:bg-[var(--deep-green)]/30 transition-colors font-medium">📊 تقدمي</a>
                        <a href="{{ route('circles.submissions.create', $c) }}" class="flex-1 text-center px-3 py-2 rounded bg-[var(--gold)]/20 text-[var(--gold)] text-sm hover:bg-[var(--gold)]/30 transition-colors font-medium">⬆️ رفع</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Tips & Reminders --}}
    <div class="border-t border-[var(--border)] pt-8">
        <h2 class="text-lg font-bold text-[var(--cream)] mb-4">💡 نصائح للنجاح</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-lg bg-[var(--deep-green)]/10 border border-[var(--deep-green)]/20">
                <p class="text-[var(--deep-green)] font-semibold mb-2">📚 اقرأ بانتظام</p>
                <p class="text-[var(--slate-blue)] text-sm">حاول تسميع لا أقل من مرة واحدة أسبوعياً لتحسين تقدمك</p>
            </div>
            <div class="p-4 rounded-lg bg-[var(--gold)]/10 border border-[var(--gold)]/20">
                <p class="text-[var(--gold)] font-semibold mb-2">🎤 سجّل بجودة عالية</p>
                <p class="text-[var(--slate-blue)] text-sm">تأكد من أن جودة الصوت واضحة والخلفية هادئة عند التسجيل</p>
            </div>
            <div class="p-4 rounded-lg bg-[var(--slate-blue)]/10 border border-[var(--slate-blue)]/20">
                <p class="text-[var(--slate-blue)] font-semibold mb-2">⏰ اتبع الجدول</p>
                <p class="text-[var(--slate-blue)] text-sm">احرص على متابعة جدول الحلقة وإرسال الصوتيات في الوقت المحدد</p>
            </div>
        </div>
    </div>
    @else
        <div class="p-8 rounded-xl border border-[var(--border)] bg-[var(--surface)] text-center">
            <p class="text-2xl text-[var(--cream)] font-bold mb-4">👋 أهلاً بك في أكاديمية القدس</p>
            <p class="text-[var(--slate-blue)] mb-6">أنت حالياً غير مسجل في أي حلقة. يرجى التواصل مع المسؤولين لتسجيلك في حلقة مناسبة.</p>
        </div>
    @endif
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap');

    * {
        font-family: 'Tajawal', sans-serif;
    }
</style>
@endsection
