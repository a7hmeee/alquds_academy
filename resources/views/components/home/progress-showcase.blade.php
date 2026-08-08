<section id="progress-showcase" class="relative py-24 lg:py-32 bg-gradient-to-br from-deep-green-900 via-deep-green-800 to-emerald-premium-900 overflow-hidden">
    <div class="absolute inset-0 pattern-islamic-dark opacity-40"></div>
    <div class="absolute -top-32 -right-32 w-[30rem] h-[30rem] bg-emerald-premium-500/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-0 -left-32 w-[28rem] h-[28rem] bg-gold-accent-500/10 rounded-full blur-[110px]"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-14 lg:gap-16 items-center">

            <!-- Dashboard mockup -->
            <div class="reveal order-2 lg:order-1">
                <div class="relative rounded-[2rem] bg-white/5 backdrop-blur-xl border border-white/10 p-6 sm:p-8 shadow-[0_40px_80px_-20px_rgba(0,0,0,0.5)]">
                    <div class="absolute -top-4 left-8 flex gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-red-400/70"></span>
                        <span class="w-3 h-3 rounded-full bg-gold-accent-400/70"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-premium-400/70"></span>
                    </div>

                    <!-- Dashboard header -->
                    <div class="flex items-center justify-between mb-8 mt-2">
                        <div>
                            <p class="text-soft-mint-100/70 text-sm font-semibold">لوحة تقدم الطالب</p>
                            <p class="text-white font-extrabold text-xl mt-0.5">شاهد تطورك مع كل تلاوة</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1.5 rounded-full bg-emerald-premium-500/20 text-emerald-premium-300 text-xs font-bold border border-emerald-premium-400/30">شهري</span>
                            <span class="px-3 py-1.5 rounded-full bg-white/5 text-soft-mint-100/60 text-xs font-bold border border-white/10">أسبوعي</span>
                        </div>
                    </div>

                    <!-- KPI cards -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">
                        @php
                            $kpis = [
                                ['التقدم العام', '78%', 'text-emerald-premium-300'],
                                ['التسجيلات', '32', 'text-soft-mint-100'],
                                ['ساعات التعلم', '18.5', 'text-soft-mint-100'],
                                ['متوسط التقييم', '4.8', 'text-gold-accent-300'],
                            ];
                        @endphp
                        @foreach ($kpis as $kpi)
                            <div class="bg-white/5 rounded-2xl border border-white/10 p-4 text-center">
                                <p class="text-xl font-black {{ $kpi[2] }}">{{ $kpi[1] }}</p>
                                <p class="text-[11px] text-soft-mint-100/60 font-semibold mt-1">{{ $kpi[0] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Chart -->
                    <div class="bg-white/5 rounded-2xl border border-white/10 p-5 mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-sm font-bold text-soft-mint-100">مستوى التلاوة — آخر 8 أسابيع</p>
                            <span class="text-emerald-premium-300 text-xs font-bold">↑ 18%</span>
                        </div>
                        <div class="flex items-end gap-2 h-36">
                            @foreach ([42, 55, 48, 63, 58, 72, 66, 78] as $i => $h)
                                <div class="flex-1 flex flex-col items-center gap-2">
                                    <div class="w-full rounded-t-lg bg-gradient-to-t from-emerald-premium-600 to-emerald-premium-400 group relative"
                                         style="height: {{ $h }}%;"
                                         @mouseenter="$el.querySelector('span').classList.remove('opacity-0')"
                                         @mouseleave="$el.querySelector('span').classList.add('opacity-0')">
                                        <span class="absolute -top-7 left-1/2 -translate-x-1/2 bg-white text-deep-green-800 text-[10px] font-bold px-2 py-0.5 rounded-lg opacity-0 transition-opacity duration-200 whitespace-nowrap">{{ $h }}%</span>
                                    </div>
                                    <span class="text-[10px] text-soft-mint-100/50 font-semibold">{{ $i + 1 }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recent recording -->
                    <div class="bg-white/5 rounded-2xl border border-white/10 p-5 flex items-center gap-4">
                        <div class="shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-premium-500 to-emerald-premium-600 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-white font-extrabold">آخر تسجيل — سورة الرحمن</p>
                            <p class="text-soft-mint-100/60 text-xs font-semibold mt-0.5">الآيات 1 - 20 · تمت المراجعة</p>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="flex gap-0.5">
                                @for ($i = 0; $i < 5; $i++)
                                    <svg class="w-4 h-4 text-gold-accent-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-white font-black text-sm">4.9</span>
                        </div>
                    </div>
                </div>

                <!-- Floating badge -->
                <div class="absolute -right-4 -bottom-6 hidden sm:block animate-float">
                    <div class="glass rounded-2xl shadow-premium px-5 py-3.5 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gold-accent-400 to-gold-accent-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-deep-green-800 font-extrabold text-sm">إنجاز جديد</p>
                            <p class="text-deep-green-600 text-xs font-semibold">أحسنت، استمر بالتميز!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Text side -->
            <div class="order-1 lg:order-2">
                <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 border border-white/15 text-soft-mint-100 text-sm font-bold mb-5">متابعة التقدم</span>
                <h2 class="reveal text-4xl lg:text-5xl font-black text-white leading-tight">
                    شاهد تطورك
                    <span class="text-gradient-mint block mt-1">مع كل تلاوة</span>
                </h2>
                <p class="reveal mt-6 text-lg text-soft-mint-100/80 leading-relaxed" style="--reveal-delay: 100ms">
                    لوحة تحكم مصممة بعناية تعرض تقدمك، تسجيلاتك، ساعات تعلمك وتقييماتك في لمحة واحدة — لتبقى دائمًا على دراية بمستواك.
                </p>

                <div class="reveal mt-10 space-y-4" style="--reveal-delay: 200ms">
                    @php
                        $features = [
                            ['تقدم دقيق', 'مؤشرات واضحة لكل سورة وجزء تدرسه.'],
                            ['تقييمات متراكمة', 'احتفظ بتاريخ تقييماتك وتتبع تحسنك.'],
                            ['تقارير أسبوعية', 'ملخصات دورية لتطورك تُرسل إليك.'],
                        ];
                    @endphp
                    @foreach ($features as $i => [$title, $desc])
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-premium-500 to-emerald-premium-600 flex items-center justify-center shadow-glow-sm mt-0.5">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-extrabold text-white">{{ $title }}</p>
                                <p class="text-soft-mint-100/70 text-sm leading-relaxed mt-1">{{ $desc }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="reveal mt-10" style="--reveal-delay: 300ms">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-3 px-9 py-4 rounded-2xl font-extrabold text-lg text-white bg-gradient-to-l from-emerald-premium-500 to-emerald-premium-600 shadow-glow-md hover:shadow-glow-lg hover:-translate-y-1 transition-all duration-300">
                        ابدأ المتابعة الآن
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
