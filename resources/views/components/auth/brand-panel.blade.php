@props(['tagline' => 'رحلتك مع كتاب الله تبدأ بخطوة.'])

<div class="relative h-full w-full overflow-hidden bg-gradient-to-br from-deep-green-900 via-deep-green-800 to-emerald-premium-900">
    {{-- Background layers --}}
    <div class="absolute inset-0">
        <div class="absolute inset-0 pattern-islamic-dark"></div>

        <div class="absolute -top-24 -right-24 h-[26rem] w-[26rem] rounded-full bg-emerald-premium-500/25 blur-[120px] animate-float-very-slow"></div>
        <div class="absolute bottom-1/4 -left-32 h-[24rem] w-[24rem] rounded-full bg-teal-400/15 blur-[110px] animate-float-slow"></div>
        <div class="absolute bottom-0 right-1/4 h-[20rem] w-[20rem] rounded-full bg-emerald-premium-600/20 blur-[100px]"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-deep-green-900/70 via-transparent to-transparent"></div>
    </div>

    <div class="relative z-10 flex h-full flex-col p-8 sm:p-10 xl:p-14">
        <x-auth.brand-logo variant="light" />

        {{-- Headline --}}
        <div class="mt-10 xl:mt-12">
            <h2 class="text-3xl font-black leading-[1.3] text-white xl:text-4xl">
                أتقن تلاوة
                <span class="text-gradient-emerald mt-1 block pb-1">كتاب الله</span>
                خطوة بخطوة
            </h2>
            <p class="mt-4 font-medium leading-relaxed text-soft-mint-100/85">{{ $tagline }}</p>
        </div>

        {{-- Showcase slot --}}
        <div class="mt-10 flex flex-1 items-center xl:mt-14">
            {{ $slot }}
        </div>

        {{-- Trust footer --}}
        <div class="mt-8 flex items-center gap-2 text-sm text-soft-mint-100/60">
            <svg class="h-5 w-5 text-emerald-premium-400" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd"/>
            </svg>
            منصة آمنة لمتابعة رحلتك التعليمية
        </div>
    </div>
</div>
