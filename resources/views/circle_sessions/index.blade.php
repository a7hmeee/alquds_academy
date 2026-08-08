@extends('layouts.app')
@section('title', 'جلسات الحلقة')
@section('page-title', 'جلسات الحلقة: {{ $circle->name }}')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">جلسات الحلقة: {{ $circle->name }}</h1>
            <p class="text-[var(--slate-blue)]">إدارة جلسات الحضور والغياب للحلقة</p>
        </div>
        <a href="{{ route('circle-sessions.create', $circle) }}"
           class="px-4 py-2.5 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-plus-circle"></i>
            جلسة جديدة
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-gradient-to-r from-green-900/30 to-emerald-900/20 border border-green-500/30 text-green-200 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-400"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="main-content-section">
        <div class="overflow-x-auto rounded-xl border border-[var(--border)]">
            <table class="w-full min-w-full">
                <thead class="bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 border-b border-[var(--border)]">
                    <tr>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">العنوان</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">التاريخ</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الوقت</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">النوع</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الحضور</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-[var(--deep-green)]/5 transition-colors duration-150 group">
                            <td class="py-4 px-6 font-bold text-[var(--cream)]">{{ $session->title ?? 'جلسة ' . \Carbon\Carbon::parse($session->session_date)->format('d/m/Y') }}</td>
                            <td class="py-4 px-6 text-[var(--cream)] whitespace-nowrap">{{ \Carbon\Carbon::parse($session->session_date)->format('d/m/Y') }}</td>
                            <td class="py-4 px-6 text-[var(--slate-blue)]">
                                @if($session->starts_at)
                                    {{ substr($session->starts_at, 0, 5) }}{{ $session->ends_at ? ' → ' . substr($session->ends_at, 0, 5) : '' }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $types = ['regular'=>'regular','exam'=>'exam','review'=>'review','event'=>'event'];
                                    $labels = ['regular'=>'عادية','exam'=>'امتحان','review'=>'مراجعة','event'=>'فعالية'];
                                @endphp
                                <span class="px-3 py-1.5 rounded-full text-sm font-medium border border-white/10
                                    {{ $session->session_type === 'exam' ? 'from-red-900/20 to-red-900/10 text-red-300 bg-gradient-to-r' : ($session->session_type === 'event' ? 'from-purple-900/20 to-purple-900/10 text-purple-300 bg-gradient-to-r' : 'from-blue-900/20 to-blue-900/10 text-blue-300 bg-gradient-to-r') }}">
                                    {{ $labels[$session->session_type] ?? $session->session_type }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $total = $session->attendance_records_count ?? $session->attendanceRecords()->count();
                                @endphp
                                <span class="text-[var(--cream)]">{{ $total }} طالب</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <a href="{{ route('circle-sessions.show', [$circle, $session]) }}"
                                       class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-blue-900/20 to-blue-900/10 text-blue-300 border border-blue-500/20 hover:border-blue-500/40 transition-all duration-200 flex items-center gap-1">
                                        <i class="fas fa-eye"></i>
                                        <span class="hidden sm:inline">عرض</span>
                                    </a>
                                    <a href="{{ route('circle-sessions.attendance', [$circle, $session]) }}"
                                       class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 text-[var(--gold)] border border-[var(--border)] hover:border-[var(--gold)]/50 transition-all duration-200 flex items-center gap-1">
                                        <i class="fas fa-user-check"></i>
                                        <span class="hidden sm:inline">حضور</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center">
                                <div class="max-w-md mx-auto">
                                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-3xl text-[var(--slate-blue)]"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-[var(--cream)] mb-2">لا توجد جلسات</h3>
                                    <p class="text-[var(--slate-blue)] mb-6">أنشئ أول جلسة للحلقة</p>
                                    <a href="{{ route('circle-sessions.create', $circle) }}"
                                       class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 inline-flex items-center gap-2">
                                        <i class="fas fa-plus-circle"></i>
                                        إنشاء جلسة
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sessions->hasPages())
            <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                <div class="text-sm text-[var(--slate-blue)]">
                    عرض {{ $sessions->firstItem() ?? 0 }} - {{ $sessions->lastItem() ?? 0 }} من {{ $sessions->total() }}
                </div>
                {{ $sessions->onEachSide(1)->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>
@endsection
