<nav
    class="fixed top-0 inset-x-0 z-50 transition-all duration-500"
    id="home-nav"
    aria-label="التنقل الرئيسي"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <a href="#home" class="flex items-center gap-3 group" aria-label="أكاديمية القدس - الصفحة الرئيسية">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-premium-500 to-deep-green-800 flex items-center justify-center shadow-glow-sm group-hover:shadow-glow-md transition-shadow duration-300 relative overflow-hidden">
                    <svg class="w-7 h-7 text-soft-mint-100 relative z-10" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 18c-3.31-.91-6-4.93-6-9v-7.5l6-3 6 3V11c0 4.07-2.69 8.09-6 9z"/>
                    </svg>
                    <div class="absolute inset-0 opacity-20 pattern-islamic-dark"></div>
                </div>
                <div class="leading-tight">
                    <span class="block text-lg font-extrabold text-deep-green-800">أكاديمية القدس</span>
                    <span class="block text-xs font-medium text-emerald-premium-600">للقرآن الكريم</span>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center gap-8">
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
                       class="nav-underline font-semibold text-deep-green-700 hover:text-emerald-premium-600 transition-colors duration-300 text-[15px]">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <!-- Auth Buttons -->
            <div class="hidden lg:flex items-center gap-3">
                @auth
                    @php
                        $dashboardRoute = auth()->user()->isStudent()
                            ? 'student.dashboard'
                            : (auth()->user()->isParent() ? 'parent.dashboard' : 'admin.dashboard');
                    @endphp
                    <a href="{{ route($dashboardRoute) }}"
                       class="px-5 py-2.5 rounded-xl font-bold text-white bg-gradient-to-l from-emerald-premium-500 to-emerald-premium-600 hover:shadow-glow-md hover:-translate-y-0.5 transition-all duration-300">
                        لوحة التحكم
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 font-bold text-deep-green-700 hover:text-emerald-premium-600 transition-colors duration-300">
                        تسجيل الدخول
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-5 py-2.5 rounded-xl font-bold text-white bg-gradient-to-l from-emerald-premium-500 to-emerald-premium-600 shadow-glow-sm hover:shadow-glow-md hover:-translate-y-0.5 transition-all duration-300">
                        ابدأ رحلتك
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button
                data-menu-btn
                class="lg:hidden w-11 h-11 rounded-xl glass flex items-center justify-center text-deep-green-800 transition-transform duration-300 hover:scale-105"
                aria-label="فتح القائمة"
                aria-expanded="false"
                aria-controls="mobile-menu"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-menu-icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div
        data-mobile-menu
        id="mobile-menu"
        class="lg:hidden max-h-0 overflow-hidden transition-all duration-500 ease-out glass border-t border-white/50"
        aria-hidden="true"
    >
        <div class="px-6 py-6 space-y-1">
            @foreach ($links as $key => [$label, $href])
                <a href="{{ $href }}"
                   data-menu-link
                   class="block px-4 py-3 rounded-xl font-bold text-deep-green-800 hover:bg-soft-mint-100/70 hover:text-emerald-premium-700 transition-colors duration-300">
                    {{ $label }}
                </a>
            @endforeach

            <div class="pt-4 mt-2 border-t border-emerald-premium-100 space-y-3">
                @auth
                    <a href="{{ route($dashboardRoute ?? 'admin.dashboard') }}"
                       data-menu-link
                       class="block w-full px-5 py-3.5 rounded-xl font-bold text-center text-white bg-gradient-to-l from-emerald-premium-500 to-emerald-premium-600">
                        لوحة التحكم
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       data-menu-link
                       class="block w-full px-5 py-3.5 rounded-xl font-bold text-center text-deep-green-700 border-2 border-emerald-premium-200 hover:bg-soft-mint-100/70 transition-colors duration-300">
                        تسجيل الدخول
                    </a>
                    <a href="{{ route('register') }}"
                       data-menu-link
                       class="block w-full px-5 py-3.5 rounded-xl font-bold text-center text-white bg-gradient-to-l from-emerald-premium-500 to-emerald-premium-600 shadow-glow-sm">
                        ابدأ رحلتك
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<style>
    #home-nav.scrolled {
        background: rgba(255, 255, 255, 0.82);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        box-shadow: 0 8px 30px rgba(6, 44, 36, 0.08);
        border-bottom: 1px solid rgba(255, 255, 255, 0.6);
    }
    #home-nav.scrolled .glass {
        background: rgba(255, 255, 255, 0.9);
    }
    [data-mobile-menu].open {
        max-height: 480px !important;
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
