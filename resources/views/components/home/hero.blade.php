<section id="home" class="relative lg:min-h-svh flex items-center overflow-hidden bg-gradient-to-br from-deep-green-900 via-deep-green-800 to-emerald-premium-900">
    <!-- Background layers -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 pattern-islamic-dark"></div>

        <!-- Radial glows -->
        <div class="absolute -top-32 -right-32 w-[34rem] h-[34rem] bg-emerald-premium-500/25 rounded-full blur-[120px] animate-float-very-slow"></div>
        <div class="absolute top-1/3 -left-40 w-[30rem] h-[30rem] bg-teal-400/15 rounded-full blur-[110px] animate-float-slow"></div>
        <div class="absolute bottom-0 right-1/4 w-[26rem] h-[26rem] bg-emerald-premium-600/20 rounded-full blur-[100px]"></div>

        <!-- Animated gradient veil -->
        <div class="absolute inset-0 bg-gradient-to-br from-transparent via-emerald-premium-800/10 to-deep-green-900/60 animate-gradient-x"></div>

        <!-- Particles -->
        <div data-particles class="absolute inset-0 overflow-hidden pointer-events-none"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 sm:pt-28 pb-16 sm:pb-20 w-full">
        <div class="grid lg:grid-cols-2 gap-10 sm:gap-12 lg:gap-20 items-center">

            <!-- Text content -->
            <div class="text-center lg:text-right lg:order-1">
                <div class="reveal">
                    <span class="inline-flex items-center gap-2.5 px-4 sm:px-5 py-2 rounded-full glass-dark text-soft-mint-100 text-xs sm:text-sm font-semibold border border-white/15">
                        <span class="w-2 h-2 rounded-full bg-emerald-premium-400 animate-pulse"></span>
                        رحلتك لإتقان كتاب الله تبدأ هنا
                    </span>
                </div>

                <h1 class="reveal mt-5 sm:mt-7 text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-black text-white leading-[1.2] tracking-tight" style="--reveal-delay: 100ms">
                    أتقن تلاوة
                    <span class="text-gradient-emerald block mt-1 pb-2">القرآن الكريم</span>
                    خطوة بخطوة
                </h1>

                <p class="reveal mt-4 sm:mt-6 text-base sm:text-lg lg:text-xl text-soft-mint-100/85 leading-relaxed max-w-xl mx-auto lg:mx-0 font-medium" style="--reveal-delay: 200ms">
                    منصة تعليمية متكاملة تساعدك على التعلّم، تسجيل تلاوتك، متابعة تقدمك والحصول على تقييم مباشر من معلميك.
                </p>

                <div class="reveal mt-7 sm:mt-10 flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center lg:justify-start" style="--reveal-delay: 300ms">
                    <a href="{{ route('register') }}"
                       class="group w-full sm:w-auto inline-flex items-center justify-center gap-3 px-6 sm:px-8 py-3.5 sm:py-4 rounded-2xl font-extrabold text-base sm:text-lg text-white bg-gradient-to-l from-emerald-premium-500 to-emerald-premium-600 shadow-glow-md hover:shadow-glow-lg hover:-translate-y-1 transition-all duration-300">
                        ابدأ التعلم الآن
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                    </a>
                    <a href="#about"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-6 sm:px-8 py-3.5 sm:py-4 rounded-2xl font-extrabold text-base sm:text-lg text-white glass-dark border border-white/20 hover:bg-white/15 hover:-translate-y-1 transition-all duration-300">
                        اكتشف الأكاديمية
                    </a>
                </div>

                <!-- Trust indicators -->
                <div class="reveal mt-9 sm:mt-12 flex items-center justify-center lg:justify-start gap-6 sm:gap-8 flex-wrap" style="--reveal-delay: 400ms">
                    <div class="flex items-center gap-2 text-soft-mint-100">
                        <svg class="w-5 h-5 text-gold-accent-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="font-bold text-white">4.8</span>
                        <span class="text-sm">/ 5.0</span>
                    </div>
                    <div class="w-px h-8 bg-white/15"></div>
                    <div class="flex -space-x-2 space-x-reverse">
                        @foreach (['#10B981', '#0F766E', '#D4AF37', '#34D399'] as $i => $color)
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border-2 border-deep-green-800 flex items-center justify-center text-white text-xs font-bold" style="background: {{ $color }}">ط</div>
                        @endforeach
                    </div>
                    <div class="text-soft-mint-100 text-sm">
                        <span class="font-bold text-white">+500</span> طالب حولوا
                        <span class="font-bold text-white">تلاوتهم</span>
                    </div>
                </div>
            </div>

            <!-- Dashboard Mockup -->
            <div class="relative order-2 lg:order-2 flex justify-center lg:justify-end" data-tilt>
                <div class="relative w-full max-w-md">
                    <!-- Glow behind card -->
                    <div class="absolute -inset-4 sm:-inset-6 bg-emerald-premium-500/20 rounded-[3rem] blur-3xl"></div>

                    <!-- Main card -->
                    <div class="relative rounded-[1.75rem] sm:rounded-[2rem] bg-white/95 backdrop-blur-2xl border border-white/60 shadow-[0_40px_80px_-20px_rgba(0,0,0,0.5)] p-5 sm:p-7">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <p class="text-sm text-emerald-premium-600 font-bold">السلام عليكم، أحمد 👋</p>
                                <p class="text-xs text-deep-green-600 mt-1">تقدمك هذا الأسبوع</p>
                            </div>
                            <div class="relative w-16 h-16">
                                <svg class="w-16 h-16 -rotate-90" viewBox="0 0 64 64">
                                    <circle cx="32" cy="32" r="28" fill="none" stroke="#E6F2EF" stroke-width="7"/>
                                    <circle cx="32" cy="32" r="28" fill="none" stroke="#10B981" stroke-width="7" stroke-linecap="round"
                                            stroke-dasharray="176" stroke-dashoffset="44"/>
                                </svg>
                                <span class="absolute inset-0 flex items-center justify-center text-sm font-extrabold text-deep-green-800">75%</span>
                            </div>
                        </div>

                        <!-- Surah card -->
                        <div class="bg-gradient-to-br from-soft-mint-100/80 to-white rounded-2xl p-5 border border-emerald-premium-100 mb-4">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="font-extrabold text-deep-green-800">سورة البقرة</p>
                                    <p class="text-xs text-emerald-premium-600 font-semibold mt-1">الآيات 1 - 20</p>
                                </div>
                                <div class="flex gap-0.5">
                                    @for ($i = 0; $i < 5; $i++)
                                        <svg class="w-4 h-4 text-gold-accent-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>

                            <!-- Waveform -->
                            <div class="flex items-center gap-3">
                                <button class="shrink-0 w-11 h-11 rounded-full bg-gradient-to-br from-emerald-premium-500 to-emerald-premium-600 flex items-center justify-center text-white shadow-glow-sm hover:scale-105 transition-transform duration-300" aria-label="تشغيل التسجيل">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                    </svg>
                                </button>
                                <div class="flex-1 flex items-center gap-[3px] h-12" aria-hidden="true">
                                    @foreach ([8, 16, 11, 20, 14, 9, 18, 13, 22, 10, 17, 12, 19, 8, 15, 21, 12, 9, 18, 14] as $i => $height)
                                        <div class="eq-bar flex-1 rounded-full bg-gradient-to-t from-emerald-premium-500 to-emerald-premium-400"
                                             style="height: {{ $height }}px; animation-delay: {{ $i * 90 }}ms"></div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-2 text-xs font-semibold text-emerald-premium-600">
                                <span>00:42</span>
                                <span>02:15</span>
                            </div>
                        </div>

                        <!-- Teacher feedback -->
                        <div class="bg-white rounded-2xl p-5 border border-emerald-premium-100">
                            <div class="flex items-start gap-3">
                                <div class="shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-emerald-premium-500 to-deep-green-800 flex items-center justify-center text-white text-sm font-bold">م</div>
                                <div>
                                    <p class="text-sm font-extrabold text-deep-green-800">ملاحظة المعلم</p>
                                    <p class="text-xs text-deep-green-600 leading-relaxed mt-1">
                                        تلاوتك ممتازة، ركز على أحكام المد.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating cards -->
                    <div class="absolute -right-3 sm:-right-6 -top-6 animate-float hidden min-[420px]:block" style="animation-delay: 0s">
                        <div class="glass rounded-2xl shadow-premium px-4 py-3 flex items-center gap-3">
                            <span class="text-2xl">🔥</span>
                            <div>
                                <p class="text-[11px] text-deep-green-600 font-semibold">سلسلة متواصلة</p>
                                <p class="text-base font-extrabold text-deep-green-800">7 أيام</p>
                            </div>
                        </div>
                    </div>

                    <div class="absolute -left-3 sm:-left-8 top-1/4 animate-float-slow hidden min-[420px]:block" style="animation-delay: 0.8s">
                        <div class="glass rounded-2xl shadow-premium px-4 py-3 flex items-center gap-3">
                            <span class="text-2xl">🎧</span>
                            <div>
                                <p class="text-[11px] text-deep-green-600 font-semibold">تسجيلات</p>
                                <p class="text-base font-extrabold text-deep-green-800">24 تسجيل</p>
                            </div>
                        </div>
                    </div>

                    <div class="absolute -right-2 bottom-24 animate-float-very-slow hidden min-[420px]:block" style="animation-delay: 1.6s">
                        <div class="glass rounded-2xl shadow-premium px-4 py-3 flex items-center gap-3">
                            <span class="text-2xl">📖</span>
                            <div>
                                <p class="text-[11px] text-deep-green-600 font-semibold">سور مكتملة</p>
                                <p class="text-base font-extrabold text-deep-green-800">8 سور</p>
                            </div>
                        </div>
                    </div>

                    <div class="absolute -left-2 sm:-left-6 -bottom-6 animate-float" style="animation-delay: 2.2s">
                        <div class="glass rounded-2xl shadow-premium px-4 py-3 flex items-center gap-3">
                            <span class="text-2xl">⭐</span>
                            <div>
                                <p class="text-[11px] text-deep-green-600 font-semibold">تقييم المعلم</p>
                                <p class="text-base font-extrabold text-deep-green-800">4.8 / 5.0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <a href="#stats" class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-soft-mint-100/80 hover:text-white transition-colors duration-300 z-10" aria-label="استكشف المزيد">
        <span class="text-xs font-semibold">استكشف</span>
        <div class="w-6 h-10 rounded-full border-2 border-current flex items-start justify-center p-1.5">
            <div class="w-1.5 h-2.5 rounded-full bg-current animate-bounce"></div>
        </div>
    </a>
</section>
