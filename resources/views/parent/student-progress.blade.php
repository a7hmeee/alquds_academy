@extends('layouts.app')
@section('title', 'تقدم الطالب')
@section('page-title', 'تقدم الطالب: {{ $student->user?->name ?? '' }}')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">{{ $student->user?->name ?? 'طالب' }}</h1>
            <p class="text-[var(--slate-blue)]">متابعة تقدم الطالب في الحفظ والتقييمات</p>
        </div>
        <a href="{{ route('parent.dashboard') }}"
           class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            رجوع
        </a>
    </div>

    {{-- Student Info --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">الحلقة</div>
            <div class="text-[var(--cream)] font-bold">{{ $student->circle?->name ?? 'غير مسجل' }}</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">إجمالي التسجيلات</div>
            <div class="text-[var(--cream)] font-bold">{{ $submissions->count() }}</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">سجل الحضور</div>
            <div class="text-[var(--cream)] font-bold">{{ $attendance->count() }} جلسة</div>
        </div>
    </div>

    {{-- Progress Timeline --}}
    @if($progress->count() > 0)
    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-4 flex items-center gap-2">
            <i class="fas fa-chart-line text-[var(--gold)]"></i>
            التقدم الأكاديمي
        </h3>
        <div class="space-y-3">
            @foreach($progress as $entry)
                <div class="flex flex-wrap items-center justify-between gap-2 p-3 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
                    <div>
                        <span class="text-[var(--cream)] font-medium">{{ $entry->surah?->name_ar ?? '—' }}</span>
                        <span class="text-[var(--slate-blue)] mx-2">|</span>
                        <span class="text-[var(--slate-blue)]">{{ $entry->juz?->name ?? '' }}</span>
                    </div>
                    <div class="flex items-center gap-4">
                        @if($entry->rating)
                            <span class="text-[var(--gold)]">{{ $entry->rating }}/5</span>
                        @endif
                        <span class="text-[var(--slate-blue)] text-sm">{{ $entry->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Recent Submissions --}}
    @if($submissions->count() > 0)
    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-4 flex items-center gap-2">
            <i class="fas fa-microphone text-[var(--gold)]"></i>
            آخر التسجيلات
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[var(--border)]">
                        <th class="py-3 px-4 text-right text-sm text-[var(--slate-blue)]">السورة</th>
                        <th class="py-3 px-4 text-center text-sm text-[var(--slate-blue)]">الدرجة</th>
                        <th class="py-3 px-4 text-center text-sm text-[var(--slate-blue)]">الحالة</th>
                        <th class="py-3 px-4 text-left text-sm text-[var(--slate-blue)]">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @foreach($submissions as $sub)
                        <tr>
                            <td class="py-3 px-4 text-[var(--cream)]">{{ $sub->surah_display ?? '—' }}</td>
                            <td class="py-3 px-4 text-center">{{ $sub->score ? $sub->score . '/100' : '—' }}</td>
                            <td class="py-3 px-4 text-center">{{ $sub->status }}</td>
                            <td class="py-3 px-4 text-left text-[var(--slate-blue)] whitespace-nowrap">{{ $sub->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Attendance Records --}}
    @if($attendance->count() > 0)
    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-4 flex items-center gap-2">
            <i class="fas fa-calendar-check text-[var(--gold)]"></i>
            سجل الحضور
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[var(--border)]">
                        <th class="py-3 px-4 text-right text-sm text-[var(--slate-blue)]">الجلسة</th>
                        <th class="py-3 px-4 text-center text-sm text-[var(--slate-blue)]">التاريخ</th>
                        <th class="py-3 px-4 text-center text-sm text-[var(--slate-blue)]">الحالة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @foreach($attendance as $record)
                        <tr>
                            <td class="py-3 px-4 text-[var(--cream)]">{{ $record->session?->title ?? 'جلسة' }}</td>
                            <td class="py-3 px-4 text-center text-[var(--slate-blue)]">{{ $record->session?->session_date ? \Carbon\Carbon::parse($record->session->session_date)->format('d/m/Y') : '' }}</td>
                            <td class="py-3 px-4 text-center">
                                @php $s = ['present'=>'text-green-400','absent'=>'text-red-400','late'=>'text-yellow-400','excused'=>'text-blue-400']; $l = ['present'=>'حاضر','absent'=>'غائب','late'=>'متأخر','excused'=>'معذور']; @endphp
                                <span class="{{ $s[$record->status] ?? '' }}">{{ $l[$record->status] ?? $record->status }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
