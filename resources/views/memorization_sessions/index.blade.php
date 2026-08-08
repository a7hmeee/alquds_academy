@extends('layouts.app')
@section('title', 'جلسات التسميع')
@section('page-title', 'جلسات التسميع')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">جلسات التسميع</h1>
            <p class="text-[var(--slate-blue)]">إدارة جلسات الاستماع والتقييم للطلاب</p>
        </div>
        <a href="{{ route('memorization-sessions.create') }}"
           class="px-4 py-2.5 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-plus-circle"></i>
            تسجيل جلسة جديدة
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-gradient-to-r from-green-900/30 to-emerald-900/20 border border-green-500/30 text-green-200">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="main-content-section">
        <div class="overflow-x-auto rounded-xl border border-[var(--border)]">
            <table class="w-full min-w-full">
                <thead class="bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 border-b border-[var(--border)]">
                    <tr>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الطالب</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">المعلم</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">السورة</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الدرجات</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الحالة</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">التاريخ</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-[var(--deep-green)]/5 transition-colors duration-150 group">
                            <td class="py-4 px-6">
                                <div class="font-bold text-[var(--cream)]">{{ $session->student?->user?->name ?? '—' }}</div>
                            </td>
                            <td class="py-4 px-6 text-[var(--cream)]">{{ $session->teacher?->user?->name ?? '—' }}</td>
                            <td class="py-4 px-6 text-[var(--cream)]">{{ $session->surah?->name_ar ?? '—' }}</td>
                            <td class="py-4 px-6">
                                @if($session->total_score)
                                    <span class="font-bold {{ $session->total_score >= 90 ? 'text-green-400' : ($session->total_score >= 70 ? 'text-[var(--gold)]' : ($session->total_score >= 50 ? 'text-yellow-400' : 'text-red-400')) }}">
                                        {{ $session->total_score }}
                                    </span>
                                    <span class="text-[var(--slate-blue)] text-sm">/100</span>
                                @else
                                    <span class="text-[var(--slate-blue)]">—</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $statuses = [
                                        'scheduled' => ['color' => 'from-blue-900/20 to-blue-900/10', 'text' => 'text-blue-300', 'label' => 'مجدول'],
                                        'in_progress' => ['color' => 'from-yellow-900/20 to-yellow-900/10', 'text' => 'text-yellow-300', 'label' => 'جاري'],
                                        'completed' => ['color' => 'from-green-900/20 to-emerald-900/10', 'text' => 'text-green-300', 'label' => 'مكتمل'],
                                        'cancelled' => ['color' => 'from-red-900/20 to-red-900/10', 'text' => 'text-red-300', 'label' => 'ملغي'],
                                    ];
                                    $s = $statuses[$session->status] ?? $statuses['scheduled'];
                                @endphp
                                <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $s['color'] }} border border-white/10 {{ $s['text'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-[var(--slate-blue)] whitespace-nowrap">
                                {{ $session->session_date ? \Carbon\Carbon::parse($session->session_date)->format('d/m/Y') : $session->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <a href="{{ route('memorization-sessions.show', $session) }}"
                                       class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-blue-900/20 to-blue-900/10 text-blue-300 border border-blue-500/20 hover:border-blue-500/40 transition-all duration-200 flex items-center gap-1">
                                        <i class="fas fa-eye"></i>
                                        <span class="hidden sm:inline">عرض</span>
                                    </a>
                                    <a href="{{ route('memorization-sessions.edit', $session) }}"
                                       class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 text-[var(--gold)] border border-[var(--border)] hover:border-[var(--gold)]/50 transition-all duration-200 flex items-center gap-1">
                                        <i class="fas fa-edit"></i>
                                        <span class="hidden sm:inline">تعديل</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 px-6 text-center">
                                <div class="max-w-md mx-auto">
                                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                                        <i class="fas fa-chalkboard-teacher text-3xl text-[var(--slate-blue)]"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-[var(--cream)] mb-2">لا توجد جلسات تسميع</h3>
                                    <p class="text-[var(--slate-blue)] mb-6">سجل جلسة تسميع جديدة للطلاب</p>
                                    <a href="{{ route('memorization-sessions.create') }}"
                                       class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 inline-flex items-center gap-2">
                                        <i class="fas fa-plus-circle"></i>
                                        تسجيل جلسة جديدة
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sessions->hasPages())
            <div class="mt-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="text-sm text-[var(--slate-blue)]">
                        عرض {{ $sessions->firstItem() ?? 0 }} - {{ $sessions->lastItem() ?? 0 }} من {{ $sessions->total() }}
                    </div>
                    {{ $sessions->onEachSide(1)->links('vendor.pagination.custom') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
