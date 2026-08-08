<section id="teachers" class="relative py-24 lg:py-32 bg-warm-white overflow-hidden">
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-premium-200/20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 lg:mb-20">
            <span class="inline-block px-4 py-1.5 rounded-full bg-soft-mint-100 text-emerald-premium-700 text-sm font-bold border border-emerald-premium-200 mb-5">فريق التدريس</span>
            <h2 class="reveal text-4xl lg:text-5xl font-black text-deep-green-800 leading-tight">
                تعلم على يد
                <span class="text-gradient-emerald block mt-2">معلمين متخصصين</span>
            </h2>
            <p class="reveal mt-6 text-lg text-deep-green-600 leading-relaxed" style="--reveal-delay: 100ms">
                نخبة من المعلمين والمعلمات الحاصلين على إجازات في القرآن الكريم وعلوم التجويد.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($teachers as $i => $teacher)
                <article class="reveal group relative bg-white rounded-[1.75rem] p-8 border border-emerald-premium-100/80 shadow-card hover:shadow-premium hover:-translate-y-2 transition-all duration-500 text-center overflow-hidden"
                         style="--reveal-delay: {{ $i * 100 }}ms">
                    <div class="absolute -top-16 -right-16 w-40 h-40 bg-soft-mint-100 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

                    <div class="relative">
                        <!-- Avatar -->
                        <div class="relative mx-auto w-28 h-28 mb-6">
                            <div class="absolute inset-0 rounded-full bg-gradient-to-br from-emerald-premium-400 to-emerald-premium-600 opacity-20 group-hover:opacity-40 transition-opacity duration-500 blur-md"></div>
                            @if ($teacher['photo'])
                                <img src="{{ $teacher['photo'] }}" alt="{{ $teacher['name'] }}" class="relative w-28 h-28 rounded-full object-cover border-4 border-white shadow-premium" loading="lazy">
                            @else
                                <div class="relative w-28 h-28 rounded-full bg-gradient-to-br from-emerald-premium-500 to-deep-green-800 border-4 border-white shadow-premium flex items-center justify-center">
                                    <span class="text-4xl font-black text-white">{{ mb_substr($teacher['name'], 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="absolute bottom-1 right-1 w-8 h-8 rounded-full bg-gradient-to-br from-gold-accent-400 to-gold-accent-600 border-2 border-white flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>

                        <h3 class="text-2xl font-extrabold text-deep-green-800">{{ $teacher['name'] }}</h3>
                        <p class="mt-2 inline-block px-4 py-1 rounded-full bg-soft-mint-100 text-emerald-premium-700 text-xs font-bold">{{ $teacher['specialization'] }}</p>

                        <div class="mt-5 flex items-center justify-center gap-6 text-center">
                            <div>
                                <p class="font-black text-deep-green-800 text-lg">{{ $teacher['experience'] }}+</p>
                                <p class="text-[11px] text-deep-green-500 font-semibold">سنة خبرة</p>
                            </div>
                            <div class="w-px h-8 bg-emerald-premium-100"></div>
                            <div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gold-accent-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="font-black text-deep-green-800 text-lg">{{ $teacher['rating'] }}</span>
                                </div>
                                <p class="text-[11px] text-deep-green-500 font-semibold">تقييم</p>
                            </div>
                        </div>

                        <div class="mt-7">
                            <a href="{{ route('register') }}"
                               class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold text-emerald-premium-600 border-2 border-emerald-premium-200 hover:bg-emerald-premium-600 hover:text-white hover:border-emerald-premium-600 transition-all duration-300">
                                عرض الملف الشخصي
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
