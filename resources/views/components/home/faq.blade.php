<section id="faq" class="relative py-24 lg:py-32 bg-warm-white overflow-hidden">
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-premium-200/20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14 lg:mb-16">
            <span class="inline-block px-4 py-1.5 rounded-full bg-soft-mint-100 text-emerald-premium-700 text-sm font-bold border border-emerald-premium-200 mb-5">الأسئلة الشائعة</span>
            <h2 class="reveal text-4xl lg:text-5xl font-black text-deep-green-800 leading-tight">
                لديك سؤال؟
                <span class="text-gradient-emerald block mt-2">لدينا الإجابة</span>
            </h2>
        </div>

        @php
            $faqs = [
                [
                    'q' => 'كيف أسجل في الأكاديمية؟',
                    'a' => 'اضغط على زر "ابدأ رحلتك" وأنشئ حسابًا جديدًا ببياناتك الأساسية، ثم أكمل خطوات التسجيل واختر الدورة المناسبة لمستواك.',
                ],
                [
                    'q' => 'كيف أرسل تسجيل تلاوتي؟',
                    'a' => 'من لوحة الطالب اضغط على "رفع تسجيل"، يمكنك التسجيل مباشرة من المتصفح أو رفع ملف صوتي جاهز، ثم حدد السورة والآيات وأرسل التسجيل للمعلم.',
                ],
                [
                    'q' => 'هل يقوم المعلم بتقييم التسجيلات؟',
                    'a' => 'نعم، يتم تقييم كل تسجيل ترفعه بشكل تفصيلي يشمل التجويد والمخارج والوقف والابتداء، مع ملاحظات واضحة تساعدك على التحسين.',
                ],
                [
                    'q' => 'هل يمكنني متابعة تقدمي؟',
                    'a' => 'بالتأكيد، لوحة التقدم تعرض تطورك أسبوعيًا: عدد التسجيلات، ساعات التعلم، متوسط التقييم، والتقدم في كل سورة تدرسها.',
                ],
                [
                    'q' => 'هل يمكنني استخدام المنصة من الهاتف؟',
                    'a' => 'نعم، المنصة مصممة بالكامل لتكون متجاوبة وتعمل بسلاسة على الهاتف والجهاز اللوحي والحاسوب.',
                ],
            ];
        @endphp

        <div class="space-y-4" x-data="{ open: 0 }">
            @foreach ($faqs as $i => $faq)
                <div class="reveal rounded-2xl border border-emerald-premium-100/80 bg-white overflow-hidden shadow-card transition-all duration-300 hover:shadow-premium"
                     style="--reveal-delay: {{ $i * 60 }}ms"
                     :class="open === {{ $i }} ? 'border-emerald-premium-300' : ''">
                    <button
                        @click="open = open === {{ $i }} ? null : {{ $i }}"
                        class="w-full flex items-center justify-between gap-4 p-5 sm:p-6 text-right"
                        :aria-expanded="open === {{ $i }}"
                        aria-controls="faq-{{ $i }}"
                    >
                        <span class="font-extrabold text-deep-green-800 text-[15px] sm:text-lg flex items-center gap-3">
                            <span class="shrink-0 w-8 h-8 rounded-xl bg-soft-mint-100 text-emerald-premium-700 flex items-center justify-center text-sm font-black">{{ $i + 1 }}</span>
                            {{ $faq['q'] }}
                        </span>
                        <svg class="shrink-0 w-5 h-5 text-emerald-premium-600 transition-transform duration-300"
                             :class="open === {{ $i }} ? 'rotate-45' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                    <div
                        x-show="open === {{ $i }}"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        x-cloak
                        id="faq-{{ $i }}"
                        class="px-5 sm:px-6 pb-6"
                    >
                        <div class="pr-11">
                            <p class="text-deep-green-600 leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
