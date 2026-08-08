@extends('layouts.app')
@section('title', 'مهام الحفظ والمراجعة')

@section('page-title', 'مهام الحفظ والمراجعة')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">مهام الحفظ والمراجعة</h1>
            <p class="text-[var(--slate-blue)]">إدارة مهام الحفظ والمراجعة للطلاب</p>
        </div>
        <a href="{{ route('memorization-assignments.create') }}"
           class="px-4 py-2.5 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-plus-circle"></i>
            مهمة جديدة
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

    <!-- Stats -->
    @php
        $stats = [
            ['label' => 'مهام اليوم', 'count' => $assignments->where('due_at', '>=', now()->startOfDay())->whereIn('status', ['assigned','in_progress'])->count(), 'icon' => 'fa-tasks', 'color' => 'from-blue-900/20 to-blue-900/10'],
            ['label' => 'قيد التنفيذ', 'count' => $assignments->where('status', 'in_progress')->count(), 'icon' => 'fa-spinner', 'color' => 'from-purple-900/20 to-purple-900/10'],
            ['label' => 'بانتظار المراجعة', 'count' => $assignments->where('status', 'submitted')->count(), 'icon' => 'fa-clock', 'color' => 'from-yellow-900/20 to-yellow-900/10'],
            ['label' => 'مكتملة', 'count' => $assignments->where('status', 'completed')->count(), 'icon' => 'fa-check-circle', 'color' => 'from-green-900/20 to-green-900/10'],
        ];
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($stats as $stat)
        <div class="stats-card p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-gradient-to-br {{ $stat['color'] }}">
                    <i class="fas {{ $stat['icon'] }} text-xl text-[var(--gold)]"></i>
                </div>
                <span class="text-sm text-[var(--slate-blue)]">{{ $stat['label'] }}</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">{{ $stat['count'] }}</div>
        </div>
        @endforeach
    </div>

    <!-- Assignments Table -->
    <div class="main-content-section">
        <div class="overflow-x-auto rounded-xl border border-[var(--border)]">
            <table class="w-full min-w-full">
                <thead class="bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 border-b border-[var(--border)]">
                    <tr>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الطالب</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">النوع</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">السورة</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الآيات</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">تاريخ الاستحقاق</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الحالة</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($assignments as $assignment)
                        <tr class="hover:bg-[var(--deep-green)]/5 transition-colors duration-150 group">
                            <td class="py-4 px-6">
                                <div class="font-bold text-[var(--cream)]">{{ $assignment->student?->full_name ?? '—' }}</div>
                                <div class="text-xs text-[var(--slate-blue)]">{{ $assignment->circle?->name ?? '—' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $typeLabels = [
                                        'new_memorization' => ['label' => 'حفظ جديد', 'color' => 'text-green-300'],
                                        'close_revision' => ['label' => 'مراجعة قريبة', 'color' => 'text-blue-300'],
                                        'far_revision' => ['label' => 'مراجعة بعيدة', 'color' => 'text-purple-300'],
                                        'consolidation' => ['label' => 'تثبيت', 'color' => 'text-yellow-300'],
                                        'test' => ['label' => 'اختبار', 'color' => 'text-red-300'],
                                    ];
                                    $type = $typeLabels[$assignment->assignment_type] ?? ['label' => $assignment->assignment_type, 'color' => 'text-white'];
                                @endphp
                                <span class="px-3 py-1.5 rounded-full text-sm font-medium border border-white/10 {{ $type['color'] }}">
                                    {{ $type['label'] }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-[var(--cream)]">{{ $assignment->surah?->name_ar ?? '—' }}</td>
                            <td class="py-4 px-6 text-[var(--cream)]">{{ $assignment->ayah_from }} - {{ $assignment->ayah_to }}</td>
                            <td class="py-4 px-6">
                                @if($assignment->due_at)
                                    <span class="{{ $assignment->due_at->isPast() && !in_array($assignment->status, ['completed', 'cancelled']) ? 'text-red-300' : 'text-[var(--cream)]' }}">
                                        {{ $assignment->due_at->format('Y/m/d') }}
                                    </span>
                                @else
                                    <span class="text-[var(--slate-blue)]">—</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $statusMap = [
                                        'draft' => ['label' => 'مسودة', 'color' => 'from-gray-900/20 to-gray-900/10', 'text' => 'text-gray-300'],
                                        'assigned' => ['label' => 'مُسندة', 'color' => 'from-blue-900/20 to-blue-900/10', 'text' => 'text-blue-300'],
                                        'in_progress' => ['label' => 'قيد التنفيذ', 'color' => 'from-purple-900/20 to-purple-900/10', 'text' => 'text-purple-300'],
                                        'submitted' => ['label' => 'بانتظار المراجعة', 'color' => 'from-yellow-900/20 to-yellow-900/10', 'text' => 'text-yellow-300'],
                                        'reviewed' => ['label' => 'تمت المراجعة', 'color' => 'from-cyan-900/20 to-cyan-900/10', 'text' => 'text-cyan-300'],
                                        'completed' => ['label' => 'مكتملة', 'color' => 'from-green-900/20 to-green-900/10', 'text' => 'text-green-300'],
                                        'needs_revision' => ['label' => 'تحتاج مراجعة', 'color' => 'from-red-900/20 to-red-900/10', 'text' => 'text-red-300'],
                                        'cancelled' => ['label' => 'ملغية', 'color' => 'from-gray-900/20 to-gray-900/10', 'text' => 'text-gray-400'],
                                    ];
                                    $s = $statusMap[$assignment->status] ?? ['label' => $assignment->status, 'color' => '', 'text' => ''];
                                @endphp
                                <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $s['color'] }} border border-white/10 {{ $s['text'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <a href="{{ route('memorization-assignments.show', $assignment) }}"
                                       class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-blue-900/20 to-blue-900/10 text-blue-300 border border-blue-500/20 hover:border-blue-500/40 transition-all duration-200 flex items-center gap-1">
                                        <i class="fas fa-eye"></i>
                                        <span class="hidden sm:inline">عرض</span>
                                    </a>
                                    @can('update', $assignment)
                                    <a href="{{ route('memorization-assignments.edit', $assignment) }}"
                                       class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 text-[var(--gold)] border border-[var(--border)] hover:border-[var(--gold)]/50 transition-all duration-200 flex items-center gap-1">
                                        <i class="fas fa-edit"></i>
                                        <span class="hidden sm:inline">تعديل</span>
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 px-6 text-center">
                                <div class="max-w-md mx-auto">
                                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                                        <i class="fas fa-tasks text-3xl text-[var(--slate-blue)]"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-[var(--cream)] mb-2">لا توجد مهام حالياً</h3>
                                    <p class="text-[var(--slate-blue)] mb-6">يمكنك إنشاء مهمة حفظ أو مراجعة للطلاب</p>
                                    <a href="{{ route('memorization-assignments.create') }}"
                                       class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 inline-flex items-center gap-2">
                                        <i class="fas fa-plus-circle"></i>
                                        إنشاء أول مهمة
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($assignments->hasPages())
            <div class="mt-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="text-sm text-[var(--slate-blue)]">
                        عرض {{ $assignments->firstItem() ?? 0 }} - {{ $assignments->lastItem() ?? 0 }} من {{ $assignments->total() }}
                    </div>
                    {{ $assignments->onEachSide(1)->links('vendor.pagination.custom') }}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.stats-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}
@media (max-width: 768px) {
    table { display: block; }
    thead { display: none; }
    tbody tr { display: block; margin-bottom: 1rem; padding: 1rem; border: 1px solid var(--border-color); border-radius: 12px; background: var(--surface); }
    tbody td { display: block; padding: 0.5rem 0; border-bottom: 1px solid var(--border-color); }
    tbody td:last-child { border-bottom: none; }
    .group-hover\:opacity-100 { opacity: 1 !important; }
}
</style>
@endsection
