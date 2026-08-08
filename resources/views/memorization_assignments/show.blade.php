@extends('layouts.app')
@section('title', 'تفاصيل المهمة')

@section('page-title', 'تفاصيل المهمة')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)]">تفاصيل المهمة</h1>
            <p class="text-sm text-[var(--slate-blue)] mt-1">
                {{ $assignment->student?->full_name ?? '—' }} • {{ $assignment->circle?->name ?? '—' }}
            </p>
        </div>
        <div class="flex gap-2">
            @can('update', $assignment)
            <a href="{{ route('memorization-assignments.edit', $assignment) }}"
               class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 flex items-center gap-2">
                <i class="fas fa-edit"></i> تعديل
            </a>
            @endcan
            <a href="{{ route('memorization-assignments.index') }}"
               class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20">
                ← رجوع
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-gradient-to-r from-green-900/30 to-emerald-900/20 border border-green-500/30 text-green-200">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Assignment Info -->
    <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)] space-y-4">
        <h2 class="text-lg font-bold text-[var(--cream)]"><i class="fas fa-info-circle text-[var(--gold)] ml-2"></i>معلومات المهمة</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-[var(--slate-blue)]">نوع المهمة:</span>
                <span class="text-[var(--cream)] font-bold mr-1">
                    @php
                        $types = ['new_memorization'=>'حفظ جديد','close_revision'=>'مراجعة قريبة','far_revision'=>'مراجعة بعيدة','consolidation'=>'تثبيت','test'=>'اختبار'];
                    @endphp
                    {{ $types[$assignment->assignment_type] ?? $assignment->assignment_type }}
                </span>
            </div>
            <div>
                <span class="text-[var(--slate-blue)]">الحالة:</span>
                <span class="font-bold mr-1">{{ $assignment->status }}</span>
            </div>
            <div>
                <span class="text-[var(--slate-blue)]">الأولوية:</span>
                <span class="text-[var(--cream)] font-bold mr-1">{{ $assignment->priority }}</span>
            </div>
            <div>
                <span class="text-[var(--slate-blue)]">نسبة الإنجاز:</span>
                <span class="text-[var(--gold)] font-bold mr-1">{{ $assignment->completion_percent }}%</span>
            </div>
        </div>
    </div>

    <!-- Quran Range -->
    <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)] space-y-4">
        <h2 class="text-lg font-bold text-[var(--cream)]"><i class="fas fa-book-quran text-[var(--gold)] ml-2"></i>نطاق الحفظ</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-[var(--slate-blue)]">السورة:</span>
                <span class="text-[var(--cream)] font-bold mr-1">{{ $assignment->surah?->name_ar ?? '—' }}</span>
            </div>
            <div>
                <span class="text-[var(--slate-blue)]">الجزء:</span>
                <span class="text-[var(--cream)] font-bold mr-1">{{ $assignment->juz?->name ?? '—' }}</span>
            </div>
            <div>
                <span class="text-[var(--slate-blue)]">من آية:</span>
                <span class="text-[var(--cream)] font-bold mr-1">{{ $assignment->ayah_from }}</span>
            </div>
            <div>
                <span class="text-[var(--slate-blue)]">إلى آية:</span>
                <span class="text-[var(--cream)] font-bold mr-1">{{ $assignment->ayah_to }}</span>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)] space-y-4">
        <h2 class="text-lg font-bold text-[var(--cream)]"><i class="fas fa-clock text-[var(--gold)] ml-2"></i>الجدول الزمني</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-[var(--slate-blue)]">تاريخ التكليف:</span>
                <span class="text-[var(--cream)] mr-1">{{ $assignment->assigned_at?->format('Y-m-d') ?? '—' }}</span>
            </div>
            <div>
                <span class="text-[var(--slate-blue)]">تاريخ الاستحقاق:</span>
                <span class="text-[var(--cream)] mr-1">{{ $assignment->due_at?->format('Y-m-d') ?? '—' }}</span>
            </div>
            <div>
                <span class="text-[var(--slate-blue)]">تاريخ الإكمال:</span>
                <span class="text-[var(--cream)] mr-1">{{ $assignment->completed_at?->format('Y-m-d') ?? '—' }}</span>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    @if($assignment->instructions)
    <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)] space-y-4">
        <h2 class="text-lg font-bold text-[var(--cream)]"><i class="fas fa-sticky-note text-[var(--gold)] ml-2"></i>تعليمات المعلم</h2>
        <p class="text-[var(--cream)] text-sm">{{ $assignment->instructions }}</p>
    </div>
    @endif

    <!-- Submissions -->
    @if($assignment->submissions->count())
    <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)] space-y-4">
        <h2 class="text-lg font-bold text-[var(--cream)]"><i class="fas fa-microphone text-[var(--gold)] ml-2"></i>التسجيلات المرتبطة</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-[var(--slate-blue)]">
                        <th class="px-4 py-2 text-right">السورة</th>
                        <th class="px-4 py-2 text-right">الآيات</th>
                        <th class="px-4 py-2 text-right">الدرجة</th>
                        <th class="px-4 py-2 text-right">الحالة</th>
                        <th class="px-4 py-2 text-right">التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignment->submissions as $sub)
                    <tr class="border-t border-[var(--border)]">
                        <td class="px-4 py-3 text-[var(--cream)]">{{ $sub->surah_display }}</td>
                        <td class="px-4 py-3 text-[var(--cream)]">{{ $sub->ayah_from ?? '—' }} - {{ $sub->ayah_to ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $sub->score ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $sub->status }}</td>
                        <td class="px-4 py-3 text-[var(--slate-blue)]">{{ $sub->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
