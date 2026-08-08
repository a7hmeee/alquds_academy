@extends('layouts.app')
@section('title', 'الشهادات')
@section('page-title', 'الشهادات')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">الشهادات</h1>
            <p class="text-[var(--slate-blue)]">شهادات إتمام الحفظ للطلاب</p>
        </div>
        <a href="{{ route('achievements.index') }}"
           class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            الإنجازات
        </a>
    </div>

    <div class="main-content-section">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($certificates as $certificate)
                <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent hover:border-[var(--gold)] transition-all duration-200">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[var(--gold)] to-[#D4B85C] flex items-center justify-center text-2xl">
                            <i class="fas fa-certificate text-[var(--dark-bg)]"></i>
                        </div>
                        <div>
                            <div class="font-bold text-[var(--cream)] text-lg">{{ $certificate->title ?? 'شهادة' }}</div>
                            <div class="text-sm text-[var(--slate-blue)]">{{ $certificate->student?->user?->name ?? '—' }}</div>
                        </div>
                    </div>
                    @if($certificate->description)
                        <p class="text-[var(--slate-blue)] text-sm mb-3">{{ $certificate->description }}</p>
                    @endif
                    <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                        <span class="text-[var(--slate-blue)]">
                            @if($certificate->verification_code)
                                <i class="fas fa-shield-alt text-green-400"></i>
                                رمز التحقق: {{ $certificate->verification_code }}
                            @endif
                        </span>
                        <span class="text-[var(--slate-blue)]">{{ $certificate->issued_at?->format('d/m/Y') ?? $certificate->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                        <i class="fas fa-certificate text-3xl text-[var(--slate-blue)]"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[var(--cream)] mb-2">لا توجد شهادات بعد</h3>
                    <p class="text-[var(--slate-blue)]">سيتم عرض شهادات الطلاب هنا</p>
                </div>
            @endforelse
        </div>

        @if($certificates->hasPages())
            <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                <div class="text-sm text-[var(--slate-blue)]">
                    عرض {{ $certificates->firstItem() ?? 0 }} - {{ $certificates->lastItem() ?? 0 }} من {{ $certificates->total() }}
                </div>
                {{ $certificates->onEachSide(1)->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>
@endsection
