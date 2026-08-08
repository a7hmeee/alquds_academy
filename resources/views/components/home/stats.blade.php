<section id="stats" class="relative py-16 lg:py-20 bg-warm-white overflow-hidden">
    <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-l from-transparent via-emerald-premium-400/30 to-transparent"></div>
    <div class="absolute inset-0 pattern-islamic-light opacity-40 pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8" x-data="statsCounter({{ json_encode([
            'students' => $stats['students'],
            'courses' => $stats['courses'],
            'teachers' => $stats['teachers'],
            'recordings' => $stats['recordings'],
        ]) }})">
            @php
                $statsData = [
                    'students' => ['عدد الطلاب والطالبات', 'طالب وطالبة'],
                    'courses' => ['الدورات التعليمية', 'دورة تعليمية'],
                    'teachers' => ['المعلمين والمعلمات', 'معلمًا ومعلمة'],
                    'recordings' => ['التسجيلات التي تمت مراجعتها', 'تسجيل تمت مراجعته'],
                ];
                $icons = [
                    'students' => '<path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>',
                    'courses' => '<path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>',
                    'teachers' => '<path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>',
                    'recordings' => '<path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd"/>',
                ];
            @endphp

            @foreach ($statsData as $key => [$label, $suffix])
                <div class="reveal text-center group">
                    <div class="mx-auto mb-5 w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-premium-500 to-emerald-premium-600 flex items-center justify-center shadow-lg group-hover:shadow-glow-md group-hover:scale-105 transition-all duration-300">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">{!! $icons[$key] !!}</svg>
                    </div>
                    <div class="text-4xl lg:text-5xl font-black text-deep-green-800 leading-none">
                        +<span x-text="display('{{ $key }}')">0</span>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-emerald-premium-600" x-text="'{{ $label }}'"></p>
                    <p class="text-xs text-deep-green-500">{{ $suffix }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('statsCounter', (targets) => ({
                targets,
                values: { students: 0, courses: 0, teachers: 0, recordings: 0 },
                started: false,
                init() {
                    const io = new IntersectionObserver((entries) => {
                        if (entries[0].isIntersecting && !this.started) {
                            this.started = true;
                            this.animate();
                            io.disconnect();
                        }
                    }, { threshold: 0.3 });
                    io.observe(this.$el);
                },
                animate() {
                    const duration = 1600;
                    const start = performance.now();
                    const tick = (now) => {
                        const progress = Math.min((now - start) / duration, 1);
                        const eased = 1 - Math.pow(1 - progress, 4);
                        Object.keys(this.targets).forEach((key) => {
                            this.values[key] = Math.round(this.targets[key] * eased);
                        });
                        if (progress < 1) requestAnimationFrame(tick);
                    };
                    requestAnimationFrame(tick);
                },
                display(key) {
                    return this.values[key].toLocaleString('en-US');
                },
            }));
        });
    </script>
</section>
