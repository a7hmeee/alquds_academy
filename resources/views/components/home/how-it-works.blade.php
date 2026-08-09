<section id="how-it-works" class="relative py-16 sm:py-20 lg:py-28 bg-gradient-to-br from-deep-green-900 via-deep-green-800 to-emerald-premium-900 overflow-hidden">
    <div class="absolute inset-0 pattern-islamic-dark opacity-60"></div>
    <div class="absolute top-1/4 -left-32 w-96 h-96 bg-emerald-premium-500/20 rounded-full blur-[100px]"></div>
    <div class="absolute bottom-0 -right-32 w-96 h-96 bg-gold-accent-500/10 rounded-full blur-[100px]"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-12 lg:mb-16">
            <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 border border-white/15 text-soft-mint-100 text-sm font-bold mb-4 sm:mb-5">كيف تعمل المنصة</span>
            <h2 class="reveal text-3xl sm:text-4xl lg:text-5xl font-black text-white leading-tight">
                رحلة بسيطة نحو
                <span class="text-gradient-mint block mt-2">تلاوة أفضل</span>
            </h2>
            <p class="reveal mt-4 sm:mt-6 text-base sm:text-lg text-soft-mint-100/80 leading-relaxed" style="--reveal-delay: 100ms">
                أربع خطوات واضحة تأخذك من البداية وحتى الإتقان.
            </p>
        </div>

        @php
            $steps = [
                ['01', 'اختر دورتك', 'ابدأ من المستوى المناسب لك من بين دورات منظمة تغطي جميع المراحل.',
                    '<path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>'],
                ['02', 'تعلم الدرس', 'ادرس الدرس مع معلمك في حلقات منظمة وجدول واضح يناسبك.',
                    '<path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>'],
                ['03', 'سجل تلاوتك', 'سجل قراءتك مباشرة وارفعها للمعلم لمراجعتها وتقييمها.',
                    '<path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd"/>'],
                ['04', 'احصل على تقييم معلمك', 'تلقَّ تقييمًا دقيقًا وملاحظات بناءة تساعدك على التطور.',
                    '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>'],
            ];
        @endphp

        <!-- Desktop horizontal timeline -->
        <div class="hidden lg:block relative">
            <div class="absolute top-16 left-[12%] right-[12%] h-0.5 bg-gradient-to-l from-emerald-premium-400/20 via-emerald-premium-400/60 to-emerald-premium-400/20"></div>
            <div class="grid grid-cols-4 gap-8">
                @foreach ($steps as $i => [$num, $title, $desc, $icon])
                    <div class="reveal text-center group" style="--reveal-delay: {{ $i * 120 }}ms">
                        <div class="relative mx-auto w-32 h-32 rounded-[1.75rem] glass-dark border border-white/15 flex items-center justify-center mb-6 group-hover:shadow-glow-md group-hover:-translate-y-2 transition-all duration-500">
                            <div class="absolute -top-3 -right-3 w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-premium-400 to-emerald-premium-600 flex items-center justify-center text-white font-black text-lg shadow-lg">{{ $num }}</div>
                            <svg class="w-14 h-14 text-soft-mint-100" fill="currentColor" viewBox="0 0 20 20">{!! $icon !!}</svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-white mb-3">{{ $title }}</h3>
                        <p class="text-soft-mint-100/75 text-[15px] leading-relaxed max-w-xs mx-auto">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Mobile vertical timeline -->
        <div class="lg:hidden relative max-w-md mx-auto">
            <div class="absolute top-0 bottom-0 right-7 w-0.5 bg-emerald-premium-400/30"></div>
            <div class="space-y-8 sm:space-y-10">
                @foreach ($steps as $i => [$num, $title, $desc, $icon])
                    <div class="reveal relative flex gap-5" style="--reveal-delay: {{ $i * 100 }}ms">
                        <div class="relative shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-premium-400 to-emerald-premium-600 flex items-center justify-center text-white font-black shadow-lg z-10">
                            {{ $num }}
                        </div>
                        <div class="glass-dark rounded-2xl border border-white/15 p-5 flex-1">
                            <h3 class="text-lg font-extrabold text-white mb-2">{{ $title }}</h3>
                            <p class="text-soft-mint-100/75 text-sm leading-relaxed">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="reveal text-center mt-10 sm:mt-12 lg:mt-16">
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-3 px-8 sm:px-9 py-3.5 sm:py-4 rounded-xl sm:rounded-2xl font-extrabold text-base sm:text-lg bg-white text-deep-green-800 hover:shadow-glow-md hover:-translate-y-1 transition-all duration-300">
                ابدأ رحلتك الآن
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
