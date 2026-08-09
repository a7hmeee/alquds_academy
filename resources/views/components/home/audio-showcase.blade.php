<section id="audio-showcase" class="relative py-16 sm:py-20 lg:py-28 bg-gradient-to-br from-white via-soft-mint-50 to-warm-white overflow-hidden">
    <div class="absolute -top-20 -right-20 w-96 h-96 bg-emerald-premium-200/30 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-gold-accent-100/40 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 sm:gap-12 lg:gap-16 items-center">

            <!-- Text side -->
            <div>
                <span class="inline-block px-4 py-1.5 rounded-full bg-soft-mint-100 text-emerald-premium-700 text-sm font-bold border border-emerald-premium-200 mb-4 sm:mb-5">تجربة استماع</span>
                <h2 class="reveal text-3xl sm:text-4xl lg:text-5xl font-black text-deep-green-800 leading-tight">
                    تلاوتك..
                    <span class="text-gradient-emerald block mt-1">طريقك للتطور</span>
                </h2>
                <p class="reveal mt-4 sm:mt-6 text-base sm:text-lg text-deep-green-600 leading-relaxed" style="--reveal-delay: 100ms">
                    كل تسجيل ترفعه يحصل على تقييم تفصيلي من معلمك: تلاوة، تجويد، مخارج ووقف وابتداء — مع ملاحظات واضحة تساعدك على التحسن في كل مرة.
                </p>

                <div class="reveal mt-8 sm:mt-10 space-y-3 sm:space-y-4" style="--reveal-delay: 200ms">
                    @php
                        $points = [
                            ['تجويد', 'التزام بأحكام التجويد والمدود', 92],
                            ['مخارج الحروف', 'دقة في مخارج الحروف وصفاتها', 88],
                            ['الوقف والابتداء', 'سلامة الوقف وابتداء القراءة', 90],
                        ];
                    @endphp
                    @foreach ($points as [$name, $note, $pct])
                        <div class="bg-white rounded-2xl border border-emerald-premium-100/70 p-4 sm:p-5 shadow-card">
                            <div class="flex items-center justify-between mb-3">
                                <p class="font-bold text-deep-green-800">{{ $name }}</p>
                                <p class="font-black text-emerald-premium-600">{{ $pct }}%</p>
                            </div>
                            <div class="h-2.5 rounded-full bg-soft-mint-100 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-l from-emerald-premium-500 to-emerald-premium-400"
                                     style="width: {{ $pct }}%; --reveal-delay: 0ms"
                                     x-data x-init="() => { const el = $el; const io = new IntersectionObserver((e) => { if (e[0].isIntersecting) { el.style.transition = 'width 1.2s cubic-bezier(0.16,1,0.3,1)'; el.style.width = '0%'; requestAnimationFrame(() => { requestAnimationFrame(() => el.style.width = '{{ $pct }}%'); }); io.disconnect(); } }); io.observe(el); }"></div>
                            </div>
                            <p class="mt-2.5 text-xs text-deep-green-500">{{ $note }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Interactive audio player -->
            <div class="reveal" style="--reveal-delay: 150ms">
                <div class="relative rounded-[1.75rem] sm:rounded-[2rem] p-5 sm:p-6 lg:p-8 bg-gradient-to-br from-deep-green-800 via-deep-green-900 to-emerald-premium-900 overflow-hidden shadow-premium" x-data="audioPlayer()">
                    <div class="absolute inset-0 pattern-islamic-dark opacity-50"></div>
                    <div class="absolute -top-24 -left-24 w-72 h-72 bg-emerald-premium-500/25 rounded-full blur-3xl animate-float-very-slow"></div>
                    <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-gold-accent-500/10 rounded-full blur-3xl"></div>

                    <div class="relative z-10">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-5 sm:mb-8">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-br from-emerald-premium-400 to-emerald-premium-600 flex items-center justify-center shadow-glow-sm">
                                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 18c-3.31-.91-6-4.93-6-9v-7.5l6-3 6 3V11c0 4.07-2.69 8.09-6 9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-extrabold text-base sm:text-lg">سورة الملك</p>
                                    <p class="text-soft-mint-100/70 text-sm font-semibold">الآيات 1 - 10</p>
                                </div>
                            </div>
                            <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/15 text-soft-mint-100 text-xs font-bold">تلاوة جديدة</span>
                        </div>

                        <!-- Waveform -->
                        <div class="relative bg-white/5 rounded-2xl p-4 sm:p-5 border border-white/10 mb-5 sm:mb-6">
                            <div class="flex items-center gap-3">
                                <button
                                    @click="toggle()"
                                    class="shrink-0 w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-emerald-premium-400 to-emerald-premium-600 flex items-center justify-center text-white shadow-glow-md hover:scale-110 active:scale-95 transition-all duration-300"
                                    :aria-label="playing ? 'إيقاف مؤقت' : 'تشغيل'"
                                    aria-label="تشغيل">
                                    <svg x-show="!playing" class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                    </svg>
                                    <svg x-show="playing" x-cloak class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>

                                <div class="flex-1 flex items-center gap-[3px] h-12 sm:h-14" aria-hidden="true">
                                    @foreach ([10, 18, 13, 22, 15, 11, 20, 14, 24, 12, 19, 15, 10, 22, 16, 12, 21, 13, 17, 9, 20, 14, 11, 18, 15] as $i => $height)
                                        <div class="eq-bar flex-1 rounded-full bg-gradient-to-t from-emerald-premium-500 to-soft-mint-200"
                                             :class="playing ? '' : 'opacity-60'"
                                             style="height: {{ $height }}px; animation-delay: {{ $i * 80 }}ms"></div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Progress -->
                            <div class="mt-5">
                                <div class="relative h-1.5 bg-white/10 rounded-full cursor-pointer group" @click="seek($event)">
                                    <div class="absolute inset-y-0 right-0 rounded-full bg-gradient-to-l from-emerald-premium-400 to-soft-mint-200"
                                         :style="'width: ' + progressPercent() + '%'">
                                        <div class="absolute -left-1 top-1/2 -translate-y-1/2 w-3.5 h-3.5 rounded-full bg-white shadow-md opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between mt-2 text-xs font-bold text-soft-mint-100/80">
                                    <span x-text="formatTime(progress)">00:42</span>
                                    <span>02:15</span>
                                </div>
                            </div>
                        </div>

                        <!-- Controls -->
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <button class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center text-soft-mint-100 hover:bg-white/20 transition-colors duration-300" aria-label="خفض السرعة">−</button>
                                <button class="px-3 py-2 rounded-xl bg-white/10 border border-white/15 text-soft-mint-100 text-sm font-bold hover:bg-white/20 transition-colors duration-300" x-text="speed + '×'">1×</button>
                                <button class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center text-soft-mint-100 hover:bg-white/20 transition-colors duration-300" aria-label="رفع السرعة">+</button>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3">
                                <svg class="w-5 h-5 text-soft-mint-100/70" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM12.293 7.293a1 1 0 011.414 0L15 8.586l1.293-1.293a1 1 0 111.414 1.414L16.414 10l1.293 1.293a1 1 0 01-1.414 1.414L15 11.414l-1.293 1.293a1 1 0 01-1.414-1.414L13.586 10l-1.293-1.293a1 1 0 010-1.414z"/>
                                </svg>
                                <div class="w-16 sm:w-24 h-1.5 bg-white/10 rounded-full relative">
                                    <div class="absolute inset-y-0 right-0 rounded-full bg-emerald-premium-400" style="width: 70%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rating card -->
                <div class="mt-5 sm:mt-6 bg-white rounded-2xl border border-emerald-premium-100/70 p-4 sm:p-6 shadow-card flex items-start gap-3 sm:gap-4">
                    <div class="shrink-0 w-11 h-11 rounded-full bg-gradient-to-br from-gold-accent-400 to-gold-accent-600 flex items-center justify-center text-white text-sm font-bold">م</div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1.5">
                            <p class="text-sm font-extrabold text-deep-green-800">تقييم المعلم</p>
                            <div class="flex gap-0.5">
                                @for ($i = 0; $i < 5; $i++)
                                    <svg class="w-4 h-4 text-gold-accent-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm text-deep-green-600 leading-relaxed">
                            أداء ممتاز، حاول الانتباه أكثر إلى المد الطبيعي في الآية الخامسة.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('audioPlayer', () => ({
                playing: false,
                speed: 1,
                progress: 42,
                duration: 135,
                timer: null,
                toggle() {
                    this.playing = !this.playing;
                    if (this.playing) {
                        this.timer = setInterval(() => {
                            if (this.progress >= this.duration) {
                                this.progress = 0;
                                this.playing = false;
                                clearInterval(this.timer);
                            } else {
                                this.progress += 0.5 * this.speed;
                            }
                        }, 300);
                    } else if (this.timer) {
                        clearInterval(this.timer);
                    }
                },
                seek(event) {
                    const rect = event.currentTarget.getBoundingClientRect();
                    const x = event.clientX - rect.left;
                    this.progress = Math.max(0, Math.min(this.duration, (x / rect.width) * this.duration));
                },
                progressPercent() {
                    return Math.min(100, (this.progress / this.duration) * 100);
                },
                formatTime(seconds) {
                    const m = Math.floor(seconds / 60);
                    const s = Math.floor(seconds % 60);
                    return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                },
            }));
        });
    </script>
</section>
