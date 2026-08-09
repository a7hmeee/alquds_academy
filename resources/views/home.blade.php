<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="أكاديمية القدس للقرآن الكريم — منصة رقمية حديثة لتعلّم القرآن الكريم وإتقان التلاوة، تسجيل تلاوتك، متابعة تقدمك والحصول على تقييم مباشر من معلميك.">

    <title>{{ config('app.name', 'أكاديمية القدس للقرآن الكريم') }} | أتقن تلاوة القرآن خطوة بخطوة</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E%F0%9F%95%8C%3C/text%3E%3C/svg%3E">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-warm-white text-deep-green-900 antialiased overflow-x-hidden">

    <x-home.navbar />

    <main>
        <x-home.hero />
        <x-home.stats :stats="$stats" />
        <x-home.features />
        <x-home.audio-showcase />
        <x-home.how-it-works />
        <x-home.courses :courses="$courses" />
        <x-home.progress-showcase :recent-reviews="$recentReviews" />
        <x-home.teachers :teachers="$teachers" />
        <x-home.testimonials />
        <x-home.academy-message />
        <x-home.memorial />
        <x-home.faq />
        <x-home.cta />
    </main>

    <x-home.footer />

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuBtn = document.querySelector('[data-menu-btn]');
            const mobileMenu = document.querySelector('[data-mobile-menu]');
            const icon = document.querySelector('[data-menu-icon]');

            function closeMenu() {
                mobileMenu?.classList.remove('open');
                menuBtn?.setAttribute('aria-expanded', 'false');
                mobileMenu?.setAttribute('aria-hidden', 'true');
                if (icon) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
                }
                document.body.style.overflow = '';
            }

            if (menuBtn && mobileMenu) {
                menuBtn.addEventListener('click', () => {
                    const open = mobileMenu.classList.toggle('open');
                    menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
                    mobileMenu.setAttribute('aria-hidden', open ? 'false' : 'true');
                    document.body.style.overflow = open ? 'hidden' : '';
                    if (icon) {
                        icon.innerHTML = open
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
                    }
                });

                document.querySelectorAll('[data-menu-link]').forEach((link) => {
                    link.addEventListener('click', closeMenu);
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') closeMenu();
                });
            }
        });
    </script>
</body>
</html>
