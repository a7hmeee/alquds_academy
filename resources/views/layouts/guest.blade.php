<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'أكاديمية القدس للقرآن الكريم') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

        <!-- Favicon -->
        <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E%F0%9F%95%8C%3C/text%3E%3C/svg%3E">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-warm-white font-sans text-deep-green-900 antialiased overflow-x-hidden">
        <div class="min-h-svh flex flex-col lg:flex-row">

            {{-- Form column (right side in RTL) --}}
            <main class="relative flex flex-1 flex-col justify-center px-5 py-12 sm:px-8 lg:w-[55%] lg:px-14 xl:px-20">
                {{-- soft background accents --}}
                <div class="pointer-events-none absolute -top-24 -right-24 h-72 w-72 rounded-full bg-emerald-premium-500/10 blur-3xl"></div>
                <div class="pointer-events-none absolute bottom-0 -left-24 h-72 w-72 rounded-full bg-soft-mint-200/60 blur-3xl"></div>

                {{-- Mobile brand header --}}
                <div class="mb-10 flex justify-center lg:hidden">
                    <x-auth.brand-logo variant="dark" />
                </div>

                <div class="relative w-full max-w-md mx-auto">
                    {{ $slot }}
                </div>

                <p class="relative mt-10 text-center text-xs text-deep-green-500/60">
                    أكاديمية القدس للقرآن الكريم © {{ date('Y') }} — جميع الحقوق محفوظة
                </p>
            </main>

            {{-- Visual column (left side in RTL) --}}
            <aside class="hidden lg:block lg:w-[45%]">
                @isset($visual)
                    {{ $visual }}
                @else
                    <x-auth.brand-panel>
                        <div class="relative w-full max-w-sm">
                            <div class="absolute -inset-5 rounded-[3rem] bg-emerald-premium-500/20 blur-3xl"></div>
                            <div class="relative rounded-3xl glass-dark border border-white/15 p-6">
                                <p class="text-2xl leading-relaxed text-soft-mint-100/90">
                                    "أصبحت متابعة تقدمي أسهل بكثير، وكل تسجيل يمنحني دافعًا لأكمل."
                                </p>
                                <div class="mt-5 flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-emerald-premium-500 to-deep-green-800 text-sm font-bold text-white">ط</div>
                                    <div>
                                        <p class="text-sm font-bold text-white">طالب من الأكاديمية</p>
                                        <p class="text-xs text-soft-mint-100/60">حلقة حفظ القرآن</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-auth.brand-panel>
                @endisset
            </aside>
        </div>
    </body>
</html>
