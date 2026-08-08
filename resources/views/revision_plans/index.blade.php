@extends('layouts.app')
@section('title', 'خطط المراجعة')
@section('page-title', 'خطط المراجعة')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">خطط المراجعة</h1>
            <p class="text-[var(--slate-blue)]">إدارة خطط مراجعة الحفظ للطلاب</p>
        </div>
        <a href="{{ route('revision-plans.create') }}"
           class="px-4 py-2.5 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-plus-circle"></i>
            إنشاء خطة مراجعة
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
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الاسم</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الطالب</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الحلقة</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الفترة</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">التقدم</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الحالة</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($plans as $plan)
                        <tr class="hover:bg-[var(--deep-green)]/5 transition-colors duration-150 group">
                            <td class="py-4 px-6 font-bold text-[var(--cream)]">{{ $plan->name }}</td>
                            <td class="py-4 px-6 text-[var(--cream)]">{{ $plan->student?->user?->name ?? '—' }}</td>
                            <td class="py-4 px-6 text-[var(--slate-blue)]">{{ $plan->circle?->name ?? '—' }}</td>
                            <td class="py-4 px-6 text-[var(--cream)] whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($plan->start_date)->format('d/m/Y') }} → {{ \Carbon\Carbon::parse($plan->end_date)->format('d/m/Y') }}
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $total = $plan->items_count ?? $plan->items()->count();
                                    $completed = $plan->items()->where('status', 'completed')->count();
                                    $pct = $total > 0 ? round(($completed / $total) * 100) : 0;
                                @endphp
                                <div class="flex items-center gap-2">
                                    <div class="w-20 bg-[var(--border)] rounded-full h-2 overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-[var(--gold)] to-[#D4B85C]" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-sm text-[var(--slate-blue)]">{{ $completed }}/{{ $total }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $statuses = ['active'=>'text-green-300','completed'=>'text-blue-300','paused'=>'text-yellow-300','cancelled'=>'text-red-300'];
                                    $labels = ['active'=>'نشط','completed'=>'مكتمل','paused'=>'متوقف','cancelled'=>'ملغي'];
                                @endphp
                                <span class="{{ $statuses[$plan->status] ?? 'text-[var(--slate-blue)]' }}">{{ $labels[$plan->status] ?? $plan->status }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <a href="{{ route('revision-plans.show', $plan) }}"
                                       class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-blue-900/20 to-blue-900/10 text-blue-300 border border-blue-500/20 hover:border-blue-500/40 transition-all duration-200 flex items-center gap-1">
                                        <i class="fas fa-eye"></i>
                                        <span class="hidden sm:inline">عرض</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 px-6 text-center">
                                <div class="max-w-md mx-auto">
                                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                                        <i class="fas fa-calendar-check text-3xl text-[var(--slate-blue)]"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-[var(--cream)] mb-2">لا توجد خطط مراجعة</h3>
                                    <p class="text-[var(--slate-blue)] mb-6">أنشئ خطة مراجعة جديدة للطلاب</p>
                                    <a href="{{ route('revision-plans.create') }}"
                                       class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 inline-flex items-center gap-2">
                                        <i class="fas fa-plus-circle"></i>
                                        إنشاء خطة مراجعة
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($plans->hasPages())
            <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                <div class="text-sm text-[var(--slate-blue)]">
                    عرض {{ $plans->firstItem() ?? 0 }} - {{ $plans->lastItem() ?? 0 }} من {{ $plans->total() }}
                </div>
                {{ $plans->onEachSide(1)->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>
@endsection
