<x-guest-layout>
    {{-- Visual side --}}
    <x-slot:visual>
        <x-auth.brand-panel tagline="أنشئ حسابك وابدأ خطواتك نحو تلاوة أكثر إتقانًا.">
            <div class="relative w-full max-w-sm">
                <div class="absolute -inset-5 rounded-[3rem] bg-emerald-premium-500/15 blur-3xl"></div>

                {{-- Student journey timeline --}}
                <div class="relative rounded-3xl glass-dark border border-white/15 p-6">
                    <p class="text-lg font-extrabold text-white">رحلتك خطوة بخطوة</p>

                    <div class="relative mt-6 space-y-6">
                        <span class="absolute right-[17px] top-2 bottom-2 w-px bg-white/10" aria-hidden="true"></span>

                        @php
                            $steps = [
                                ['01', 'أنشئ حسابك', 'سجّل بياناتك في دقيقة واحدة'],
                                ['02', 'اختر دورتك', 'خطط تعليمية تناسب مستواك'],
                                ['03', 'سجّل تلاوتك', 'من أي جهاز، في أي وقت'],
                                ['04', 'احصل على تقييم', 'ملاحظات معلمك مباشرة'],
                            ];
                        @endphp

                        @foreach ($steps as $i => [$num, $title, $desc])
                            <div class="relative flex items-start gap-4">
                                <span class="relative z-10 flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-emerald-premium-400/50 bg-deep-green-800 text-xs font-black text-emerald-premium-300 shadow-glow-sm">{{ $num }}</span>
                                <div class="pt-1">
                                    <p class="text-sm font-bold text-white">{{ $title }}</p>
                                    <p class="mt-1 text-xs text-soft-mint-100/60">{{ $desc }}</p>
                                </div>
                            </div>
                        @endforeach
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

        <h1 class="mt-6 text-3xl sm:text-4xl font-black text-deep-green-900">ابدأ رحلتك مع أكاديمية القدس</h1>
        <p class="mt-3 text-deep-green-600/80 leading-relaxed">أنشئ حسابك وابدأ خطواتك نحو تلاوة أكثر إتقانًا.</p>

        <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
            @csrf

            <!-- Name -->
            <x-auth.input label="الاسم الكامل" name="name" type="text" icon="user" :value="old('name')" autocomplete="name" required autofocus />

            <!-- Email Address -->
            <x-auth.input label="البريد الإلكتروني" name="email" type="email" icon="mail" :value="old('email')" autocomplete="username" required />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Phone (Optional) -->
                <x-auth.input label="رقم الهاتف" name="phone" type="tel" icon="phone" :value="old('phone')" autocomplete="tel" optional />

                <!-- Age (Optional) -->
                <x-auth.input label="السن" name="age" type="number" icon="calendar" :value="old('age')" min="1" max="120" optional />
            </div>

            <!-- Country (Optional) -->
            <x-auth.input label="الدولة" name="country" type="text" icon="globe" :value="old('country')" optional />

            <!-- Password -->
            <x-auth.input label="كلمة المرور" name="password" type="password" icon="lock" autocomplete="new-password" required />

            <!-- Confirm Password -->
            <x-auth.input label="تأكيد كلمة المرور" name="password_confirmation" type="password" icon="lock" autocomplete="new-password" required />

            <x-auth.primary-button>
                {{ __('إنشاء الحساب') }}
                <svg class="w-5 h-5 transition-transform duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </x-auth.primary-button>
        </form>

        <p class="mt-8 text-center text-sm text-deep-green-600">
            {{ __('مسجل بالفعل؟') }}
            <a href="{{ route('login') }}" class="font-extrabold text-emerald-premium-600 hover:text-emerald-premium-700 transition-colors duration-200 underline underline-offset-4">
                {{ __('تسجيل الدخول') }}
            </a>
        </p>
    </div>
</x-guest-layout>
