@extends('layouts.app')
@section('title', 'تفاصيل الجلسة')
@section('page-title', 'تفاصيل الجلسة')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">{{ $session->title ?? 'جلسة ' . \Carbon\Carbon::parse($session->session_date)->format('d/m/Y') }}</h1>
            <p class="text-[var(--slate-blue)]">{{ $circle->name }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('circle-sessions.attendance', [$circle, $session]) }}"
               class="px-4 py-2.5 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-user-check"></i>
                تعديل الحضور
            </a>
            <a href="{{ route('circle-sessions.index', $circle) }}"
               class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                رجوع
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)] text-center">
            <div class="text-3xl font-bold text-green-400">{{ $stats['present'] }}</div>
            <div class="text-sm text-[var(--slate-blue)] mt-1">حاضر</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)] text-center">
            <div class="text-3xl font-bold text-red-400">{{ $stats['absent'] }}</div>
            <div class="text-sm text-[var(--slate-blue)] mt-1">غائب</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)] text-center">
            <div class="text-3xl font-bold text-yellow-400">{{ $stats['late'] }}</div>
            <div class="text-sm text-[var(--slate-blue)] mt-1">متأخر</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)] text-center">
            <div class="text-3xl font-bold text-blue-400">{{ $stats['excused'] }}</div>
            <div class="text-sm text-[var(--slate-blue)] mt-1">معذور</div>
        </div>
    </div>

    {{-- Session Info --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">التاريخ</div>
            <div class="text-[var(--cream)] font-bold">{{ \Carbon\Carbon::parse($session->session_date)->format('d/m/Y') }}</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">الوقت</div>
            <div class="text-[var(--cream)] font-bold">
                {{ $session->starts_at ? substr($session->starts_at, 0, 5) : '—' }}
                {{ $session->ends_at ? '→ ' . substr($session->ends_at, 0, 5) : '' }}
            </div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">النوع</div>
            <div class="text-[var(--cream)] font-bold">{{ $session->session_type === 'exam' ? 'امتحان' : ($session->session_type === 'review' ? 'مراجعة' : ($session->session_type === 'event' ? 'فعالية' : 'عادية')) }}</div>
        </div>
    </div>

    {{-- Attendance Records --}}
    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-4 flex items-center gap-2">
            <i class="fas fa-users text-[var(--gold)]"></i>
            سجل الحضور
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[var(--border)]">
                        <th class="py-3 px-4 text-right text-sm text-[var(--slate-blue)]">الطالب</th>
                        <th class="py-3 px-4 text-center text-sm text-[var(--slate-blue)]">الحالة</th>
                        <th class="py-3 px-4 text-right text-sm text-[var(--slate-blue)]">ملاحظة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($session->attendanceRecords as $record)
                        <tr class="border-b border-[var(--border)] hover:bg-[var(--deep-green)]/5">
                            <td class="py-3 px-4 font-bold text-[var(--cream)]">{{ $record->student?->user?->name ?? '—' }}</td>
                            <td class="py-3 px-4 text-center">
                                @php
                                    $statusData = ['present'=>['color'=>'text-green-400','label'=>'حاضر'],'absent'=>['color'=>'text-red-400','label'=>'غائب'],'late'=>['color'=>'text-yellow-400','label'=>'متأخر'],'excused'=>['color'=>'text-blue-400','label'=>'معذور']];
                                @endphp
                                <span class="{{ $statusData[$record->status]['color'] ?? '' }}">{{ $statusData[$record->status]['label'] ?? $record->status }}</span>
                            </td>
                            <td class="py-3 px-4 text-[var(--slate-blue)]">{{ $record->note ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($session->notes)
    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-3 flex items-center gap-2">
            <i class="fas fa-sticky-note text-[var(--gold)]"></i>
            ملاحظات
        </h3>
        <p class="text-[var(--cream)]">{{ $session->notes }}</p>
    </div>
    @endif
</div>
@endsection
