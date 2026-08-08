@props(['type' => 'submit'])

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'group relative inline-flex h-[54px] w-full items-center justify-center gap-2.5 overflow-hidden rounded-2xl bg-gradient-to-l from-emerald-premium-500 to-emerald-premium-600 text-base font-extrabold text-white shadow-glow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-glow-md active:translate-y-0 active:scale-[0.99] focus:outline-none focus:ring-4 focus:ring-emerald-premium-500/30']) }}
>
    <span class="pointer-events-none absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent transition-transform duration-700 group-hover:translate-x-full"></span>
    <span class="relative inline-flex items-center gap-2.5">{{ $slot }}</span>
</button>
