import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/* ─────────────────────────────────────────────
   Scroll reveal (IntersectionObserver)
───────────────────────────────────────────── */
(function () {
    const els = document.querySelectorAll('.reveal');
    if (!els.length) return;

    if (!('IntersectionObserver' in window)) {
        els.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -6% 0px' }
    );

    els.forEach((el) => observer.observe(el));
})();

/* ─────────────────────────────────────────────
   3D tilt for hero mockup
───────────────────────────────────────────── */
(function () {
    const tiltEl = document.querySelector('[data-tilt]');
    if (!tiltEl) return;

    const strength = 8;

    tiltEl.addEventListener('mousemove', (e) => {
        const rect = tiltEl.getBoundingClientRect();
        const px = (e.clientX - rect.left) / rect.width;
        const py = (e.clientY - rect.top) / rect.height;

        const rotateY = (px - 0.5) * strength * 2;
        const rotateX = (0.5 - py) * strength * 2;

        tiltEl.style.transform = `perspective(1200px) rotateY(${rotateY}deg) rotateX(${rotateX}deg)`;
    });

    tiltEl.addEventListener('mouseleave', () => {
        tiltEl.style.transform = 'perspective(1200px) rotateY(0deg) rotateX(0deg)';
    });
})();

/* ─────────────────────────────────────────────
   Floating particles in hero
───────────────────────────────────────────── */
(function () {
    const container = document.querySelector('[data-particles]');
    if (!container) return;

    const count = window.innerWidth < 768 ? 10 : 22;

    for (let i = 0; i < count; i++) {
        const span = document.createElement('span');
        span.className = 'particle';

        const size = 3 + Math.random() * 6;
        span.style.width = size + 'px';
        span.style.height = size + 'px';
        span.style.left = Math.random() * 100 + '%';
        span.style.setProperty('--particle-drift', (Math.random() * 80 - 40) + 'px');
        span.style.setProperty('--particle-opacity', (0.25 + Math.random() * 0.4));
        span.style.animationDuration = 12 + Math.random() * 14 + 's';
        span.style.animationDelay = -(Math.random() * 20) + 's';

        container.appendChild(span);
    }
})();
