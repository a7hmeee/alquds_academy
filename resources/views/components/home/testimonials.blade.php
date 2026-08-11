<section id="testimonials" class="relative py-16 sm:py-20 lg:py-28 bg-gradient-to-br from-white via-soft-mint-50/50 to-warm-white overflow-hidden">
    <div class="absolute -top-20 -right-20 w-96 h-96 bg-emerald-premium-200/20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-12 lg:mb-16">
            <span class="inline-block px-4 py-1.5 rounded-full bg-soft-mint-100 text-emerald-premium-700 text-sm font-bold border border-emerald-premium-200 mb-4 sm:mb-5">قصص نجاح</span>
            <h2 class="reveal text-3xl sm:text-4xl lg:text-5xl font-black text-deep-green-800 leading-tight">
                قصص من
                <span class="text-gradient-emerald block mt-2">طلابنا</span>
            </h2>
            <p class="reveal mt-4 sm:mt-6 text-base sm:text-lg text-deep-green-600 leading-relaxed" style="--reveal-delay: 100ms">
                تجارب حقيقية لطلاب بدأوا رحلتهم معنا وحققوا إتقانًا في التلاوة.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8" x-data="{ active: 0 }">
            @php
                $testimonials = [
                    [
                        'name' => 'أحمد النابلسي',
                        'course' => 'دورة أحكام التجويد',
                        'quote' => 'أكثر ما أدهشني هو التقييم التفصيلي من المعلم على كل تسجيل. شعرت أنني أتحسن كل أسبوع بشكل ملحوظ.',
                        'rating' => 5,
                    ],
                    [
                        'name' => 'سارة الخالدي',
                        'course' => 'أساسيات التلاوة',
                        'quote' => 'بدأت من الصفر، وبعد 6 أشهر أصبحت أقرأ بثقة. منصة سهلة جدًا ومنظمة، والمعلمات متابعات بشكل ممتاز.',
                        'rating' => 5,
                    ],
                    [
                        'name' => 'محمد الراشد',
                        'course' => 'إتقان التلاوة',
                        'quote' => 'متابعة التقدم والتقارير الأسبوعية جعلتني ملتزمًا أكثر. أخيرًا وجدت طريقة تجمع بين الحفظ والمراجعة بشكل منظم.',
                        'rating' => 5,
                    ],
                ];
            @endphp

            @foreach ($testimonials as $i => $t)
                <figure class="reveal relative bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-6 border border-emerald-premium-100/80 shadow-card hover:shadow-premium hover:-translate-y-2 transition-all duration-500 flex flex-col"
                        style="--reveal-delay: {{ $i * 100 }}ms">
                    <div class="absolute top-5 right-5 sm:top-6 sm:right-8 text-gold-accent-500/20">
                        <svg class="w-8 h-8 sm:w-12 sm:h-12" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                        </svg>
                    </div>

                    <div class="flex gap-0.5 mb-3 sm:mb-5">
                        @for ($s = 0; $s < $t['rating']; $s++)
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gold-accent-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>

                    <blockquote class="flex-1 text-deep-green-700 leading-relaxed text-sm sm:text-[15px] mb-4 sm:mb-6">
                        "{{ $t['quote'] }}"
                    </blockquote>

                    <figcaption class="flex items-center gap-3 pt-4 sm:pt-5 border-t border-emerald-premium-100/70">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-emerald-premium-500 to-deep-green-800 flex items-center justify-center text-white font-black">
                            {{ mb_substr($t['name'], 0, 1) }}
                        </div>
                        <div>
                            <p class="font-extrabold text-deep-green-800">{{ $t['name'] }}</p>
                            <p class="text-xs text-emerald-premium-600 font-semibold">{{ $t['course'] }}</p>
                        </div>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
