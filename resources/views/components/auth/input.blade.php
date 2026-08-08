@props([
    'name' => null,
    'label' => null,
    'type' => 'text',
    'icon' => null,
    'value' => null,
    'autocomplete' => null,
    'required' => false,
    'autofocus' => false,
    'optional' => false,
    'min' => null,
    'max' => null,
])

@php
    $hasError = $errors->has($name);
    $icons = [
        'mail' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>',
        'lock' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>',
        'user' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>',
        'phone' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>',
        'calendar' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>',
        'globe' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>',
    ];
@endphp

<div>
    @if ($label)
        <label for="{{ $name }}" class="mb-2 block text-sm font-bold text-deep-green-800">
            {{ $label }}
            @if ($optional)
                <span class="font-medium text-deep-green-500/60">(اختياري)</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if ($icon)
            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 {{ $hasError ? 'text-red-400' : 'text-emerald-premium-500/70' }}">
                <svg class="h-[22px] w-[22px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icons[$icon] !!}</svg>
            </span>
        @endif

        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ $value }}"
            @if ($required) required @endif
            @if ($autofocus) autofocus @endif
            @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            @if ($min) min="{{ $min }}" @endif
            @if ($max) max="{{ $max }}" @endif
            aria-invalid="{{ $hasError ? 'true' : 'false' }}"
            class="h-[54px] w-full rounded-2xl border-2 bg-white/95 pl-4 pr-12 text-[15px] font-medium text-deep-green-900 shadow-sm transition-all duration-300 outline-none placeholder:text-deep-green-500/40
                {{ $hasError
                    ? 'border-red-300 focus:border-red-400 focus:ring-4 focus:ring-red-400/10'
                    : 'border-emerald-premium-100 hover:border-emerald-premium-300 focus:border-emerald-premium-500 focus:ring-4 focus:ring-emerald-premium-500/15' }}"
        >

        @if ($hasError)
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-red-400">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" clip-rule="evenodd"/>
                </svg>
            </span>
        @endif
    </div>

    @if ($hasError)
        <div class="mt-2">
            <x-input-error :messages="$errors->get($name)" class="!text-red-500" />
        </div>
    @endif
</div>
