@props(['variant' => 'light'])

@php
    $title = $variant === 'dark' ? 'text-deep-green-900' : 'text-white';
    $sub = $variant === 'dark' ? 'text-emerald-premium-600' : 'text-soft-mint-100/80';
@endphp

<div class="inline-flex items-center gap-3">
    <div class="relative flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-premium-500 to-deep-green-800 shadow-glow-sm">
        <svg class="relative z-10 h-7 w-7 text-soft-mint-100" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 18c-3.31-.91-6-4.93-6-9v-7.5l6-3 6 3V11c0 4.07-2.69 8.09-6 9z"/>
        </svg>
        <div class="absolute inset-0 opacity-20 pattern-islamic-dark"></div>
    </div>
    <div class="leading-tight text-right">
        <span class="block text-lg font-extrabold {{ $title }}">أكاديمية القدس</span>
        <span class="block text-xs font-medium {{ $sub }}">للقرآن الكريم</span>
    </div>
</div>
