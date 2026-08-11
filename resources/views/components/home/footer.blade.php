<footer class="relative bg-deep-green-900 text-soft-mint-100/80 overflow-hidden">
    <div class="absolute inset-0 pattern-islamic-dark opacity-40 pointer-events-none"></div>
    <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-l from-transparent via-emerald-premium-400/40 to-transparent"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-16 lg:py-20">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">
            <!-- Brand -->
            <div class="lg:col-span-1">
                <a href="#home" class="block w-fit mb-5" aria-label="أكاديمية القدس - الصفحة الرئيسية">
                    <img
                        src="{{ asset('logo.png') }}"
                        alt="أكاديمية القدس للقرآن الكريم"
                        class="w-auto max-w-[170px] sm:max-w-[190px] lg:max-w-[210px] h-auto object-contain"
                        style="aspect-ratio: 537 / 330;"
                    >
                </a>
                <p class="text-sm leading-relaxed">
                    منصة رقمية حديثة لتعلّم القرآن الكريم وإتقان التلاوة، مع متابعة دقيقة وتقييم مباشر من معلميك.
                </p>
            </div>

            <!-- Academy -->
            <nav aria-label="الأكاديمية">
                <h3 class="text-white font-extrabold mb-5 text-base">الأكاديمية</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="#courses" class="hover:text-emerald-premium-300 transition-colors duration-300">الدورات</a></li>
                    <li><a href="#teachers" class="hover:text-emerald-premium-300 transition-colors duration-300">المعلمون</a></li>
                    <li><a href="#about" class="hover:text-emerald-premium-300 transition-colors duration-300">عن الأكاديمية</a></li>
                    <li><a href="#how-it-works" class="hover:text-emerald-premium-300 transition-colors duration-300">كيف تعمل المنصة</a></li>
                </ul>
            </nav>

            <!-- Support -->
            <nav aria-label="الدعم">
                <h3 class="text-white font-extrabold mb-5 text-base">الدعم</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="#faq" class="hover:text-emerald-premium-300 transition-colors duration-300">الأسئلة الشائعة</a></li>
                    <li><a href="#features" class="hover:text-emerald-premium-300 transition-colors duration-300">مميزات المنصة</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-emerald-premium-300 transition-colors duration-300">تواصل معنا</a></li>
                </ul>
            </nav>

            <!-- Account -->
            <nav aria-label="الحساب">
                <h3 class="text-white font-extrabold mb-5 text-base">الحساب</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('login') }}" class="hover:text-emerald-premium-300 transition-colors duration-300">تسجيل الدخول</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-emerald-premium-300 transition-colors duration-300">إنشاء حساب</a></li>
                </ul>

                <div class="mt-8">
                    <h3 class="text-white font-extrabold mb-4 text-base">تابعنا</h3>
                    <div class="flex flex-wrap gap-3">
                        @php
                            $socials = [
                                ['X', '<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>'],
                                ['WhatsApp', '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>'],
                                ['Telegram', '<path d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>'],
                                ['Email', '<path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z"/><path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z"/>'],
                            ];
                        @endphp
                        @foreach ($socials as [$name, $icon])
                            <a href="#" class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-emerald-premium-500 hover:border-emerald-premium-500 hover:-translate-y-1 transition-all duration-300" aria-label="{{ $name }}">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            </nav>
        </div>

        <div class="mt-10 sm:mt-14 pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-soft-mint-100/50">
            <p>© {{ date('Y') }} أكاديمية القدس للقرآن الكريم — جميع الحقوق محفوظة</p>
            <p class="flex items-center gap-2">
                صُنع بحب لإتقان كتاب الله
                <svg class="w-4 h-4 text-gold-accent-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                </svg>
            </p>
        </div>
    </div>
</footer>
