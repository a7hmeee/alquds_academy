<x-guest-layout>
    {{-- Visual side --}}
    <x-slot:visual>
        <x-auth.brand-panel tagline="عد إلى رحلتك نحو تلاوة أفضل.">
            <div class="relative w-full max-w-sm animate-float-very-slow">
                <div class="absolute -inset-6 rounded-[3rem] bg-emerald-premium-500/20 blur-3xl"></div>

                {{-- Audio showcase card --}}
                <div class="relative rounded-3xl bg-white/95 backdrop-blur-2xl border border-white/60 shadow-[0_40px_80px_-20px_rgba(0,0,0,0.5)] p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <p class="text-sm font-extrabold text-deep-green-800">تلاوتك الأخيرة</p>
                            <p class="mt-1 text-xs text-emerald-premium-600 font-semibold">سورة الرحمن · الآيات 1 – 12</p>
                        </div>
                        <div class="flex gap-0.5">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-gold-accent-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>

                    {{-- Waveform --}}
                    <div class="flex items-center gap-3 rounded-2xl bg-gradient-to-br from-soft-mint-100/80 to-white border border-emerald-premium-100 p-4">
                        <button type="button" class="shrink-0 w-11 h-11 rounded-full bg-gradient-to-br from-emerald-premium-500 to-emerald-premium-600 flex items-center justify-center text-white shadow-glow-sm hover:scale-105 transition-transform duration-300" aria-label="تشغيل التسجيل">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                            </svg>
                        </button>
                        <div class="flex-1 flex items-center gap-[3px] h-12" aria-hidden="true">
                            @foreach ([8, 16, 11, 20, 14, 9, 18, 13, 22, 10, 17, 12, 19, 8, 15, 21, 12, 9, 18, 14] as $i => $height)
                                <div class="eq-bar flex-1 rounded-full bg-gradient-to-t from-emerald-premium-500 to-emerald-premium-400"
                                     style="height: {{ $height }}px; animation-delay: {{ $i * 90 }}ms"></div>
                            @endforeach
                        </div>
                        <span class="text-xs font-bold text-deep-green-700">02:14</span>
                    </div>

                    {{-- Feedback --}}
                    <div class="mt-4 flex items-start gap-3 rounded-2xl bg-white border border-emerald-premium-100 p-4">
                        <div class="shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-emerald-premium-500 to-deep-green-800 flex items-center justify-center text-white text-sm font-bold">م</div>
                        <div>
                            <p class="text-sm font-extrabold text-deep-green-800">تقييم معلمك</p>
                            <p class="mt-1 text-xs leading-relaxed text-deep-green-600">أحسنت، تقدم واضح في أحكام المد.</p>
                        </div>
                    </div>
                </div>
            </div>
        </x-auth.brand-panel>
    </x-slot:visual>

    {{-- Form side --}}
    <div class="animate-fade-up">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-soft-mint-100 text-emerald-premium-700 text-sm font-bold">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 18c-3.31-.91-6-4.93-6-9v-7.5l6-3 6 3V11c0 4.07-2.69 8.09-6 9z"/>
            </svg>
            أكاديمية القدس
        </span>

        <h1 class="mt-6 text-3xl sm:text-4xl font-black text-deep-green-900">مرحبًا بعودتك</h1>
        <p class="mt-3 text-deep-green-600/80 leading-relaxed">سجل دخولك لمتابعة رحلتك التعليمية وتسجيلاتك.</p>

        <!-- Session Status -->
        <x-auth-session-status class="mt-6" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
            @csrf

            <!-- Email Address -->
            <x-auth.input label="البريد الإلكتروني" name="email" type="email" icon="mail" :value="old('email')" autocomplete="username" required autofocus />

            <!-- Password -->
            <x-auth.input label="كلمة المرور" name="password" type="password" icon="lock" autocomplete="current-password" required />

            <!-- Remember Me + Forgot -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2.5 select-none group">
                    <input id="remember_me" type="checkbox" name="remember" class="peer h-5 w-5 rounded-md border-2 border-emerald-premium-200 bg-white text-emerald-premium-600 transition-colors duration-200 focus:ring-2 focus:ring-emerald-premium-500/40 focus:ring-offset-2 peer-checked:border-emerald-premium-500">
                    <span class="text-sm font-semibold text-deep-green-700 group-hover:text-deep-green-900 transition-colors duration-200">{{ __('تذكرني') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-bold text-emerald-premium-600 hover:text-emerald-premium-700 transition-colors duration-200 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-premium-500/40 focus:ring-offset-2" href="{{ route('password.request') }}">
                        {{ __('نسيت كلمة المرور؟') }}
                    </a>
                @endif
            </div>

            <x-auth.primary-button>
                {{ __('تسجيل الدخول') }}
                <svg class="w-5 h-5 transition-transform duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </x-auth.primary-button>
        </form>

        <p class="mt-8 text-center text-sm text-deep-green-600">
            {{ __('ليس لديك حساب؟') }}
            <a href="{{ route('register') }}" class="font-extrabold text-emerald-premium-600 hover:text-emerald-premium-700 transition-colors duration-200 underline underline-offset-4">
                {{ __('إنشاء حساب جديد') }}
            </a>
        </p>
    </div>
</x-guest-layout>
