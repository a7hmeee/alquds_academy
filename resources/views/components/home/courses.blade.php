<section id="courses" class="relative py-16 sm:py-20 lg:py-28 bg-warm-white overflow-hidden">
    <div class="absolute inset-0 pattern-islamic-light opacity-50 pointer-events-none"></div>
    <div class="absolute -top-24 left-1/4 w-96 h-96 bg-emerald-premium-200/20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-12 lg:mb-16">
            <span class="inline-block px-4 py-1.5 rounded-full bg-soft-mint-100 text-emerald-premium-700 text-sm font-bold border border-emerald-premium-200 mb-4 sm:mb-5">الدورات التعليمية</span>
            <h2 class="reveal text-3xl sm:text-4xl lg:text-5xl font-black text-deep-green-800 leading-tight">
                ابدأ من المستوى
                <span class="text-gradient-emerald block mt-2">المناسب لك</span>
            </h2>
            <p class="reveal mt-4 sm:mt-6 text-base sm:text-lg text-deep-green-600 leading-relaxed" style="--reveal-delay: 100ms">
                مسارات تعليمية منظمة تناسب جميع المستويات، من أول مرة حتى الإتقان.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            @foreach ($courses as $i => $course)
                <article class="reveal group relative bg-white rounded-2xl sm:rounded-[1.75rem] overflow-hidden border border-emerald-premium-100/80 shadow-card hover:shadow-premium hover:-translate-y-2 transition-all duration-500"
                         style="--reveal-delay: {{ $i * 100 }}ms">
                    <!-- Gradient header -->
                    <div class="relative h-40 sm:h-44 lg:h-48 bg-gradient-to-br {{ $course['gradient'] }} overflow-hidden">
                        <div class="absolute inset-0 pattern-islamic-dark opacity-40"></div>
                        <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>

                        <div class="relative z-10 h-full flex flex-col items-center justify-center text-white p-5 sm:p-6">
                            <svg class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 opacity-90 mb-3 drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                            </svg>
                            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-sm text-sm font-bold">
                                {{ $course['level'] }}
                            </span>
                        </div>

                        <!-- Rating badge -->
                        <div class="absolute top-4 left-4 flex items-center gap-1 bg-white/20 backdrop-blur-md rounded-full px-3 py-1.5 text-white text-sm font-bold">
                            <svg class="w-4 h-4 text-gold-accent-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            {{ $course['rating'] }}
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-extrabold text-deep-green-800 mb-2 sm:mb-3">{{ $course['name'] }}</h3>
                        <p class="text-sm sm:text-[15px] text-deep-green-600 leading-relaxed mb-4 sm:mb-6">{{ $course['description'] }}</p>

                        <div class="grid grid-cols-3 gap-2 sm:gap-3 mb-5 sm:mb-7">
                            <div class="bg-soft-mint-100/70 rounded-xl p-3 text-center">
                                <p class="font-black text-deep-green-800 text-base sm:text-lg">{{ $course['lessons'] }}</p>
                                <p class="text-[11px] text-emerald-premium-600 font-semibold mt-0.5">درس</p>
                            </div>
                            <div class="bg-soft-mint-100/70 rounded-xl p-3 text-center">
                                <p class="font-black text-deep-green-800 text-sm leading-snug pt-1">{{ $course['duration'] }}</p>
                                <p class="text-[11px] text-emerald-premium-600 font-semibold mt-0.5">المدة</p>
                            </div>
                            <div class="bg-soft-mint-100/70 rounded-xl p-3 text-center">
                                <p class="font-black text-deep-green-800 text-base sm:text-lg">{{ $course['students'] }}</p>
                                <p class="text-[11px] text-emerald-premium-600 font-semibold mt-0.5">طالب</p>
                            </div>
                        </div>

                        <a href="{{ route('register') }}"
                           class="group/btn w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl font-bold text-white bg-gradient-to-l from-emerald-premium-500 to-emerald-premium-600 hover:shadow-glow-md transition-all duration-300">
                            اكتشف الدورة
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
