@extends('layouts.app')
@section('title', 'تفاصيل خطة المراجعة')
@section('page-title', 'تفاصيل خطة المراجعة')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">{{ $plan->name }}</h1>
            <p class="text-[var(--slate-blue)]">تفاصيل خطة المراجعة وبنودها</p>
        </div>
        <a href="{{ route('revision-plans.index') }}"
           class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            رجوع
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-gradient-to-r from-green-900/30 to-emerald-900/20 border border-green-500/30 text-green-200 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-400"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">الطالب</div>
            <div class="text-[var(--cream)] font-bold">{{ $plan->student?->user?->name ?? '—' }}</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">الحلقة</div>
            <div class="text-[var(--cream)] font-bold">{{ $plan->circle?->name ?? '—' }}</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">تاريخ البداية</div>
            <div class="text-[var(--cream)] font-bold">{{ \Carbon\Carbon::parse($plan->start_date)->format('d/m/Y') }}</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">تاريخ النهاية</div>
            <div class="text-[var(--cream)] font-bold">{{ \Carbon\Carbon::parse($plan->end_date)->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-4 flex items-center gap-2">
            <i class="fas fa-list text-[var(--gold)]"></i>
            بنود المراجعة
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[var(--border)]">
                        <th class="py-3 px-4 text-right text-sm text-[var(--slate-blue)]">#</th>
                        <th class="py-3 px-4 text-right text-sm text-[var(--slate-blue)]">النوع</th>
                        <th class="py-3 px-4 text-right text-sm text-[var(--slate-blue)]">السورة</th>
                        <th class="py-3 px-4 text-right text-sm text-[var(--slate-blue)]">الجزء</th>
                        <th class="py-3 px-4 text-right text-sm text-[var(--slate-blue)]">الآيات</th>
                        <th class="py-3 px-4 text-right text-sm text-[var(--slate-blue)]">التكرار</th>
                        <th class="py-3 px-4 text-right text-sm text-[var(--slate-blue)]">الحالة</th>
                        <th class="py-3 px-4 text-right text-sm text-[var(--slate-blue)]"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plan->items as $item)
                        <tr class="border-b border-[var(--border)] hover:bg-[var(--deep-green)]/5">
                            <td class="py-3 px-4 text-[var(--slate-blue)]">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">
                                @php
                                    $types = ['new_memorization'=>'حفظ جديد','close_revision'=>'مراجعة قريبة','far_revision'=>'مراجعة بعيدة','consolidation'=>'تثبيت'];
                                @endphp
                                <span class="text-[var(--cream)]">{{ $types[$item->assignment_type] ?? $item->assignment_type }}</span>
                            </td>
                            <td class="py-3 px-4 text-[var(--cream)]">{{ $item->surah?->name_ar ?? '—' }}</td>
                            <td class="py-3 px-4 text-[var(--slate-blue)]">{{ $item->juz?->name ?? '—' }}</td>
                            <td class="py-3 px-4 text-[var(--cream)]">{{ $item->ayah_from }} → {{ $item->ayah_to }}</td>
                            <td class="py-3 px-4 text-[var(--slate-blue)]">{{ $item->repetition_target }}</td>
                            <td class="py-3 px-4">
                                @if($item->status === 'completed')
                                    <span class="text-green-400">مكتمل</span>
                                @else
                                    <span class="text-yellow-400">معلق</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($item->status !== 'completed')
                                    <form method="POST" action="{{ route('revision-plans.items.complete', $item) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-green-900/20 to-green-900/10 text-green-300 border border-green-500/20 hover:border-green-500/40 transition-all duration-200 text-sm">
                                            <i class="fas fa-check"></i> إكمال
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($plan->notes)
    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-3 flex items-center gap-2">
            <i class="fas fa-sticky-note text-[var(--gold)]"></i>
            ملاحظات
        </h3>
        <p class="text-[var(--cream)]">{{ $plan->notes }}</p>
    </div>
    @endif
</div>
@endsection
