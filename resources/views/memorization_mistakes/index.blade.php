@extends('layouts.app')
@section('title', 'الأخطاء')
@section('page-title', 'الأخطاء')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">الأخطاء</h1>
            <p class="text-[var(--slate-blue)]">سجل أخطاء الطلاب أثناء التسميع</p>
        </div>
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
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الطالب</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">السورة</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الآية</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">نوع الخطأ</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الدرجة</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الحالة</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">التاريخ</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($mistakes as $mistake)
                        <tr class="hover:bg-[var(--deep-green)]/5 transition-colors duration-150">
                            <td class="py-4 px-6 font-bold text-[var(--cream)]">{{ $mistake->student?->user?->name ?? '—' }}</td>
                            <td class="py-4 px-6 text-[var(--cream)]">{{ $mistake->surah?->name_ar ?? '—' }}</td>
                            <td class="py-4 px-6 text-[var(--slate-blue)]">{{ $mistake->ayah_number }}</td>
                            <td class="py-4 px-6">
                                @php
                                    $types = ['memorization'=>'حفظ','tajweed'=>'تجويد','haraka'=>'حركة','madd'=>'مد','ghunnah'=>'غنّة','makhraj'=>'مخرج','waqf_ibtida'=>'وقف وابتداء','omission'=>'حذف','repetition'=>'تكرار','hesitation'=>'تردد','other'=>'أخرى'];
                                @endphp
                                <span class="text-[var(--cream)]">{{ $types[$mistake->mistake_type] ?? $mistake->mistake_type }}</span>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $severityColors = ['minor'=>'text-green-400','moderate'=>'text-yellow-400','major'=>'text-orange-400','critical'=>'text-red-400'];
                                    $severityLabels = ['minor'=>'بسيط','moderate'=>'متوسط','major'=>'كبير','critical'=>'خطير'];
                                @endphp
                                <span class="{{ $severityColors[$mistake->severity] ?? '' }}">
                                    {{ $severityLabels[$mistake->severity] ?? $mistake->severity }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if($mistake->is_resolved)
                                    <span class="px-3 py-1.5 rounded-full text-sm font-medium from-green-900/20 to-emerald-900/10 border border-white/10 text-green-300 bg-gradient-to-r">محلول</span>
                                @else
                                    <span class="px-3 py-1.5 rounded-full text-sm font-medium from-yellow-900/20 to-yellow-900/10 border border-white/10 text-yellow-300 bg-gradient-to-r">معلق</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-[var(--slate-blue)] whitespace-nowrap">{{ $mistake->created_at->format('d/m/Y') }}</td>
                            <td class="py-4 px-6">
                                @if(!$mistake->is_resolved)
                                    <form method="POST" action="{{ route('memorization-mistakes.resolve', $mistake) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-green-900/20 to-green-900/10 text-green-300 border border-green-500/20 hover:border-green-500/40 transition-all duration-200 text-sm">
                                            <i class="fas fa-check"></i> حل
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 px-6 text-center">
                                <div class="max-w-md mx-auto">
                                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                                        <i class="fas fa-check-circle text-3xl text-green-400"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-[var(--cream)] mb-2">لا توجد أخطاء</h3>
                                    <p class="text-[var(--slate-blue)]">لم يتم تسجيل أي أخطاء بعد</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mistakes->hasPages())
            <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                <div class="text-sm text-[var(--slate-blue)]">
                    عرض {{ $mistakes->firstItem() ?? 0 }} - {{ $mistakes->lastItem() ?? 0 }} من {{ $mistakes->total() }}
                </div>
                {{ $mistakes->onEachSide(1)->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>
@endsection
