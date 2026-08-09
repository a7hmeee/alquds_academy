<section id="features" class="relative py-16 sm:py-20 lg:py-28 bg-warm-white overflow-hidden">
    <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[50rem] h-[30rem] bg-soft-mint-100/60 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-12 lg:mb-16">
            <span class="inline-block px-4 py-1.5 rounded-full bg-soft-mint-100 text-emerald-premium-700 text-sm font-bold border border-emerald-premium-200 mb-4 sm:mb-5">لماذا أكاديمية القدس؟</span>
            <h2 class="reveal text-3xl sm:text-4xl lg:text-5xl font-black text-deep-green-800 leading-tight">
                كل ما تحتاجه لإتقان تلاوتك
                <span class="text-gradient-emerald block mt-2">في مكان واحد</span>
            </h2>
            <p class="reveal mt-4 sm:mt-6 text-base sm:text-lg text-deep-green-600 leading-relaxed" style="--reveal-delay: 100ms">
                أدوات متكاملة صُممت خصيصًا لرحلة تعلم القرآن الكريم، من التسجيل الأول حتى الإتقان.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            @php
                $features = [
                    [
                        'title' => 'تسجيل تلاوتك',
                        'desc' => 'سجل قراءتك مباشرة من المتصفح أو ارفع ملفك الصوتي وأرسله لمعلمك في ثوانٍ.',
                        'color' => 'from-emerald-premium-400 to-emerald-premium-600',
                        'icon' => '<path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd"/>',
                    ],
                    [
                        'title' => 'استمع لتلاوتك',
                        'desc' => 'راجع تسجيلاتك في أي وقت، قارن تلاواتك وتابع تطورك بأذنك وأذن معلمك.',
                        'color' => 'from-emerald-premium-500 to-emerald-premium-700',
                        'icon' => '<path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"/>',
                    ],
                    [
                        'title' => 'تقييم المعلم',
                        'desc' => 'احصل على تقييم دقيق وملاحظات واضحة على كل تسجيل لتحسين أدائك باستمرار.',
                        'color' => 'from-emerald-premium-600 to-deep-green-800',
                        'icon' => '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>',
                    ],
                    [
                        'title' => 'متابعة تقدمك',
                        'desc' => 'اعرف مستواك والإنجازات التي حققتها من خلال لوحة تحكم واضحة ومحفزة.',
                        'color' => 'from-deep-green-700 to-emerald-premium-800',
                        'icon' => '<path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd"/>',
                    ],
                    [
                        'title' => 'دورات منظمة',
                        'desc' => 'مسار تعليمي واضح من البداية وحتى الإتقان مع معلمين متخصصين.',
                        'color' => 'from-emerald-premium-400 to-deep-green-700',
                        'icon' => '<path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>',
                    ],
                    [
                        'title' => 'إنجازات وتحفيز',
                        'desc' => 'شاهد تقدمك واحصل على شارات وإنجازات تحفزك على الاستمرار.',
                        'color' => 'from-emerald-premium-500 to-gold-accent-600',
                        'icon' => '<path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>',
                    ],
                ];
            @endphp

            @foreach ($features as $i => $feature)
                <div class="reveal group relative bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-6 lg:p-8 border border-emerald-premium-100/80 shadow-card hover:shadow-premium hover:-translate-y-2 transition-all duration-500 overflow-hidden"
                     style="--reveal-delay: {{ $i * 80 }}ms">
                    <div class="absolute -top-16 -left-16 w-40 h-40 bg-soft-mint-100 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

                    <div class="relative">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br {{ $feature['color'] }} flex items-center justify-center mb-4 sm:mb-6 shadow-lg group-hover:shadow-glow-md group-hover:scale-110 transition-all duration-300">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="currentColor" viewBox="0 0 20 20">{!! $feature['icon'] !!}</svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-extrabold text-deep-green-800 mb-2 sm:mb-3">{{ $feature['title'] }}</h3>
                        <p class="text-deep-green-600 leading-relaxed text-sm sm:text-[15px]">{{ $feature['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
