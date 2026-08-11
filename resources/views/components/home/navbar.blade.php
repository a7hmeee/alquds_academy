<nav
    class="fixed top-0 inset-x-0 z-50 bg-warm-white/95 backdrop-blur-md border-b border-emerald-premium-100/60 shadow-[0_1px_0_rgba(255,255,255,0.6)_inset] transition-all duration-500"
    id="home-nav"
    aria-label="التنقل الرئيسي"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-4 sm:gap-6 lg:gap-2 xl:gap-6 h-[72px] sm:h-20 lg:h-[88px] xl:h-24">
            <!-- Brand: icon + name -->
            <a href="#home" class="flex items-center gap-2.5 sm:gap-3 lg:gap-2.5 xl:gap-4 shrink-0 min-w-0" aria-label="أكاديمية القدس - الصفحة الرئيسية">
                <img
                    src="{{ asset('academy-icon.png') }}"
                    alt="أكاديمية القدس"
                    class="w-14 h-14 sm:w-16 sm:h-16 lg:w-[72px] lg:h-[72px] xl:w-[84px] xl:h-[84px] object-contain shrink-0"
                >
                <span class="flex flex-col justify-center leading-none min-w-0">
                    <span class="font-extrabold text-deep-green-900 text-[15px] sm:text-lg lg:text-lg xl:text-xl whitespace-nowrap">أكاديمية القدس</span>
                    <span class="font-semibold text-emerald-premium-600 text-xs sm:text-sm lg:text-sm xl:text-base whitespace-nowrap mt-1">للقرآن الكريم</span>
                </span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center gap-1 xl:gap-1.5 mx-auto">
                @php
                    $links = [
                        'home' => ['الرئيسية', '#home'],
                        'courses' => ['الدورات', '#courses'],
                        'about' => ['عن الأكاديمية', '#about'],
                        'teachers' => ['المعلمون', '#teachers'],
                        'how' => ['كيف تعمل المنصة', '#how-it-works'],
                        'faq' => ['الأسئلة الشائعة', '#faq'],
                    ];
                @endphp
                @foreach ($links as $key => [$label, $href])
                    <a href="{{ $href }}"
                       class="px-2 xl:px-3.5 py-2 rounded-lg font-semibold text-deep-green-700 hover:text-emerald-premium-700 hover:bg-soft-mint-100/80 transition-colors duration-300 text-[13px] xl:text-[15px] whitespace-nowrap">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <!-- Auth Buttons -->
            <div class="hidden lg:flex items-center gap-2 shrink-0">
                @auth
                    @php
                        $dashboardRoute = auth()->user()->isStudent()
                            ? 'student.dashboard'
                            : (auth()->user()->isParent() ? 'parent.dashboard' : 'admin.dashboard');
                    @endphp
                    <a href="{{ route($dashboardRoute) }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl font-bold text-white bg-gradient-to-l from-emerald-premium-500 to-emerald-premium-600 hover:shadow-glow-md hover:-translate-y-0.5 transition-all duration-300">
                        لوحة التحكم
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-2.5 xl:px-4 py-2 rounded-lg font-bold text-deep-green-700 hover:text-emerald-premium-600 hover:bg-soft-mint-100/70 transition-colors duration-300 text-sm">
                        تسجيل الدخول
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center px-3 xl:px-5 py-2.5 rounded-xl font-bold text-white bg-gradient-to-l from-emerald-premium-500 to-emerald-premium-600 shadow-glow-sm hover:shadow-glow-md hover:-translate-y-0.5 transition-all duration-300 text-sm">
                        ابدأ رحلتك
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button
                data-menu-btn
                class="lg:hidden shrink-0 w-11 h-11 rounded-xl border border-emerald-premium-100 bg-white/80 flex items-center justify-center text-deep-green-800 active:scale-95 transition-transform duration-300"
                aria-label="فتح القائمة"
                aria-expanded="false"
                aria-controls="mobile-menu"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-menu-icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>
</nav>

<!-- Mobile Overlay -->
<div
    data-menu-overlay
    class="fixed inset-0 z-40 bg-deep-green-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden"
    aria-hidden="true"
></div>

<!-- Mobile Drawer -->
<div
    data-mobile-menu
    id="mobile-menu"
    class="fixed top-0 bottom-0 right-0 z-50 w-[min(85vw,320px)] bg-warm-white/95 backdrop-blur-xl border-l border-emerald-premium-100/60 shadow-2xl transform translate-x-full transition-transform duration-300 ease-out lg:hidden overflow-y-auto"
    aria-hidden="true"
>
    <div class="px-4 py-4 sm:px-5">
        <div class="flex items-center justify-between mb-6 border-b border-emerald-premium-100/70 pb-4">
            <div class="flex items-center gap-2.5 min-w-0">
                <img
                    src="{{ asset('academy-icon.png') }}"
                    alt="أكاديمية القدس"
                    class="w-12 h-12 object-contain shrink-0"
                >
                <span class="flex flex-col justify-center leading-none">
                    <span class="font-extrabold text-deep-green-900 text-base whitespace-nowrap">أكاديمية القدس</span>
                    <span class="font-semibold text-emerald-premium-600 text-xs mt-1">للقرآن الكريم</span>
                </span>
            </div>
            <button
                data-menu-close
                class="w-9 h-9 rounded-xl bg-soft-mint-100 flex items-center justify-center text-deep-green-800"
                aria-label="إغلاق القائمة"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="space-y-1" aria-label="قائمة الهاتف">
            @foreach ($links as $key => [$label, $href])
                <a href="{{ $href }}"
                   data-menu-link
                   class="block px-4 py-3 rounded-xl font-bold text-deep-green-800 hover:bg-soft-mint-100/80 hover:text-emerald-premium-700 transition-colors duration-300">
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <div class="pt-4 mt-4 border-t border-emerald-premium-100/70 space-y-3">
            @auth
                <a href="{{ route($dashboardRoute ?? 'admin.dashboard') }}"
                   data-menu-link
                   class="block w-full px-5 py-3 rounded-xl font-bold text-center text-white bg-gradient-to-l from-emerald-premium-500 to-emerald-premium-600">
                    لوحة التحكم
                </a>
            @else
                <a href="{{ route('login') }}"
                   data-menu-link
                   class="block w-full px-5 py-3 rounded-xl font-bold text-center text-deep-green-700 border-2 border-emerald-premium-200 hover:bg-soft-mint-100/70 transition-colors duration-300">
                    تسجيل الدخول
                </a>
                <a href="{{ route('register') }}"
                   data-menu-link
                   class="block w-full px-5 py-3 rounded-xl font-bold text-center text-white bg-gradient-to-l from-emerald-premium-500 to-emerald-premium-600 shadow-glow-sm">
                    ابدأ رحلتك
                </a>
            @endauth
        </div>
    </div>
</div>

<style>
    #home-nav.scrolled {
        background: rgba(255, 250, 244, 0.92);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        box-shadow: 0 8px 30px rgba(6, 44, 36, 0.08);
        border-bottom: 1px solid rgba(16, 185, 129, 0.14);
    }
    [data-mobile-menu].open {
        transform: translateX(0);
    }
    [data-menu-overlay].open {
        opacity: 1;
        pointer-events: auto;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nav = document.getElementById('home-nav');
        if (!nav) return;

        const onScroll = () => {
            if (window.scrollY > 40) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    });
</script>
