<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>أكاديمية القدس لتحفيظ القرآن الكريم</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            :root {
                --gold: #FFD700;
                --deep-green: #0A5C36;
                --slate-blue: #6C8EA0;
                --cream: #F5F1E8;
                --dark-bg: #0C1A14;
                --surface: #13281E;
            }

            * {
                margin: 0;
                padding: 0;
            }

            body {
                font-family: 'Tajawal', sans-serif;
                background: linear-gradient(135deg, var(--dark-bg) 0%, #0a1410 100%);
                color: var(--cream);
                overflow-x: hidden;
            }

            .hero-gradient {
                background: linear-gradient(135deg, var(--deep-green) 0%, var(--dark-bg) 100%);
            }

            .btn-primary {
                background: linear-gradient(135deg, var(--gold) 0%, #FFE54C 100%);
                color: var(--dark-bg);
                padding: 12px 32px;
                border-radius: 8px;
                font-weight: 600;
                transition: transform 0.3s, box-shadow 0.3s;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(255, 215, 0, 0.3);
            }

            .btn-secondary {
                background: transparent;
                border: 2px solid var(--gold);
                color: var(--gold);
                padding: 12px 32px;
                border-radius: 8px;
                font-weight: 600;
                transition: all 0.3s;
            }

            .btn-secondary:hover {
                background: var(--gold);
                color: var(--dark-bg);
                transform: translateY(-2px);
            }

            .service-card {
                background: rgba(255, 215, 0, 0.05);
                border: 1px solid rgba(255, 215, 0, 0.2);
                padding: 32px;
                border-radius: 12px;
                transition: all 0.3s;
            }

            .service-card:hover {
                background: rgba(255, 215, 0, 0.1);
                border-color: rgba(255, 215, 0, 0.4);
                transform: translateY(-5px);
            }

            .service-icon {
                font-size: 48px;
                color: var(--gold);
                margin-bottom: 16px;
            }

            .feature-item {
                display: flex;
                align-items: flex-start;
                gap: 16px;
                margin-bottom: 24px;
            }

            .feature-icon {
                font-size: 24px;
                color: var(--gold);
                margin-top: 4px;
                flex-shrink: 0;
            }

            .feature-text {
                flex: 1;
            }

            .feature-text h4 {
                font-weight: 600;
                margin-bottom: 8px;
                color: var(--cream);
                font-size: 16px;
            }

            .feature-text p {
                color: var(--slate-blue);
                font-size: 14px;
                line-height: 1.6;
            }

            .step-number {
                width: 48px;
                height: 48px;
                background: linear-gradient(135deg, var(--gold) 0%, #FFE54C 100%);
                color: var(--dark-bg);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                font-size: 20px;
                flex-shrink: 0;
            }

            .step-box {
                display: flex;
                gap: 20px;
                align-items: flex-start;
                margin-bottom: 32px;
            }

            .step-content {
                flex: 1;
            }

            .step-content h4 {
                font-weight: 600;
                margin-bottom: 8px;
                color: var(--cream);
                font-size: 16px;
            }

            .step-content p {
                color: var(--slate-blue);
                font-size: 14px;
                line-height: 1.6;
            }

            .footer-info {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 16px;
                color: var(--slate-blue);
            }

            .footer-icon {
                color: var(--gold);
                font-size: 18px;
                flex-shrink: 0;
            }

            .section-title {
                text-align: center;
                margin-bottom: 48px;
            }

            .section-title h2 {
                font-size: 36px;
                font-weight: 700;
                color: var(--cream);
                margin-bottom: 16px;
            }

            .section-title p {
                font-size: 16px;
                color: var(--slate-blue);
                max-width: 500px;
                margin: 0 auto;
            }

            @media (max-width: 768px) {
                .section-title h2 {
                    font-size: 24px;
                }

                .btn-primary, .btn-secondary {
                    padding: 10px 24px;
                    font-size: 14px;
                }

                .service-card {
                    padding: 24px;
                }
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="fixed top-0 w-full z-50 backdrop-blur-md bg-rgba(12, 26, 20, 0.8)" style="background: rgba(12, 26, 20, 0.9);">
            <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <i class="fas fa-quran" style="color: var(--gold); font-size: 28px;"></i>
                    <div>
                        <h1 style="font-size: 16px; font-weight: 700; color: var(--cream);">أكاديمية القدس</h1>
                        <p style="font-size: 11px; color: var(--slate-blue);">تحفيظ القرآن الكريم</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary">لوحة التحكم</a>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-secondary">تسجيل خروج</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-secondary">دخول</a>
                        <a href="{{ route('register') }}" class="btn-primary">تسجيل جديد</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-gradient min-h-screen flex items-center justify-center px-6 pt-20">
            <div class="max-w-4xl w-full text-center">
                <div style="margin-bottom: 32px;">
                    <i class="fas fa-mosque" style="color: var(--gold); font-size: 80px; display: block; margin-bottom: 24px;"></i>
                </div>
                <h1 style="font-size: 48px; font-weight: 700; margin-bottom: 16px; color: var(--cream);">
                    أكاديمية القدس<br>لتحفيظ القرآن الكريم
                </h1>
                <p style="font-size: 18px; color: var(--slate-blue); margin-bottom: 32px; max-width: 600px; margin-left: auto; margin-right: auto;">
                    منصة متكاملة لتعليم وتحفيظ القرآن الكريم بطرق حديثة وفعّالة
                </p>
                <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('register') }}" class="btn-primary">ابدأ رحلتك الآن</a>
                    <a href="#services" class="btn-secondary">اعرف المزيد</a>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" style="padding: 80px 32px; background: var(--dark-bg);">
            <div class="max-w-6xl mx-auto">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center;">
                    <div>
                        <h2 style="font-size: 36px; font-weight: 700; margin-bottom: 24px; color: var(--cream);">من نحن؟</h2>
                        <p style="color: var(--slate-blue); line-height: 1.8; margin-bottom: 20px;">
                            أكاديمية القدس هي مؤسسة تعليمية متخصصة في تحفيظ القرآن الكريم، تجمع بين الطرق التقليدية الأصيلة والتكنولوجيا الحديثة لتوفير تجربة تعليمية متميزة.
                        </p>
                        <p style="color: var(--slate-blue); line-height: 1.8; margin-bottom: 20px;">
                            نؤمن أن كل طالب قادر على حفظ القرآن الكريم، وبالتالي نوفر بيئة تعليمية داعمة وحلقات منظمة برعاية معلمين متخصصين.
                        </p>
                        <p style="color: var(--slate-blue); line-height: 1.8;">
                            هدفنا تخريج حفاظ متقنين للقرآن الكريم قادرين على نشره والعمل به.
                        </p>
                    </div>
                    <div style="background: rgba(255, 215, 0, 0.05); border: 2px solid rgba(255, 215, 0, 0.2); border-radius: 12px; padding: 40px; text-align: center;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div style="padding: 20px; background: rgba(255, 215, 0, 0.1); border-radius: 8px;">
                                <div style="font-size: 36px; font-weight: 700; color: var(--gold); margin-bottom: 8px;">+500</div>
                                <p style="color: var(--slate-blue); font-size: 14px;">طالب ملتحق</p>
                            </div>
                            <div style="padding: 20px; background: rgba(255, 215, 0, 0.1); border-radius: 8px;">
                                <div style="font-size: 36px; font-weight: 700; color: var(--gold); margin-bottom: 8px;">+30</div>
                                <p style="color: var(--slate-blue); font-size: 14px;">معلم متخصص</p>
                            </div>
                            <div style="padding: 20px; background: rgba(255, 215, 0, 0.1); border-radius: 8px;">
                                <div style="font-size: 36px; font-weight: 700; color: var(--gold); margin-bottom: 8px;">+50</div>
                                <p style="color: var(--slate-blue); font-size: 14px;">حلقة تعليمية</p>
                            </div>
                            <div style="padding: 20px; background: rgba(255, 215, 0, 0.1); border-radius: 8px;">
                                <div style="font-size: 36px; font-weight: 700; color: var(--gold); margin-bottom: 8px;">24/7</div>
                                <p style="color: var(--slate-blue); font-size: 14px;">دعم فني</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" style="padding: 80px 32px; background: linear-gradient(180deg, var(--dark-bg) 0%, rgba(10, 92, 54, 0.1) 100%);">
            <div class="max-w-6xl mx-auto">
                <div class="section-title">
                    <h2>خدماتنا</h2>
                    <p>نقدم مجموعة شاملة من الخدمات المتطورة لدعم رحلتك في تحفيظ القرآن الكريم</p>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 32px;">
                    <div class="service-card">
                        <i class="fas fa-book-quran service-icon"></i>
                        <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 12px; color: var(--cream);">تحفيظ القرآن الكريم</h3>
                        <p style="color: var(--slate-blue); line-height: 1.6;">
                            برنامج منظم متدرج لتحفيظ القرآن الكريم مع متابعة دقيقة للتقدم والحفظ
                        </p>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-users service-icon"></i>
                        <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 12px; color: var(--cream);">حلقات تعليمية منظمة</h3>
                        <p style="color: var(--slate-blue); line-height: 1.6;">
                            حلقات صغيرة ومنظمة مع معلمين متخصصين لضمان الاستفادة القصوى
                        </p>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-chart-line service-icon"></i>
                        <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 12px; color: var(--cream);">متابعة التقدم</h3>
                        <p style="color: var(--slate-blue); line-height: 1.6;">
                            نظام متطور لمتابعة تقدم الطالب والتقييم المستمر للأداء
                        </p>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-microphone service-icon"></i>
                        <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 12px; color: var(--cream);">رفع التسجيلات</h3>
                        <p style="color: var(--slate-blue); line-height: 1.6;">
                            منصة آمنة لرفع تسجيلاتك الصوتية والحصول على ملاحظات المعلم
                        </p>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-star service-icon"></i>
                        <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 12px; color: var(--cream);">تقييم احترافي</h3>
                        <p style="color: var(--slate-blue); line-height: 1.6;">
                            تقييم دقيق للتجويد والحفظ مع ملاحظات بناءة للتحسين المستمر
                        </p>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-question-circle service-icon"></i>
                        <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 12px; color: var(--cream);">دعم مستمر</h3>
                        <p style="color: var(--slate-blue); line-height: 1.6;">
                            فريق دعم متفاني يجيب على أسئلتك في أي وقت
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section style="padding: 80px 32px; background: var(--dark-bg);">
            <div class="max-w-6xl mx-auto">
                <div class="section-title">
                    <h2>كيف يعمل النظام؟</h2>
                    <p>خطوات بسيطة وسهلة للبدء مع أكاديمية القدس</p>
                </div>
                <div style="max-width: 700px; margin: 0 auto;">
                    <div class="step-box">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4>إنشاء حساب</h4>
                            <p>انضم إلى الأكاديمية بسهولة عن طريق التسجيل بالبيانات الأساسية</p>
                        </div>
                    </div>
                    <div class="step-box">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4>اختيار الحلقة المناسبة</h4>
                            <p>اختر الحلقة التي تتناسب مع مستواك من بين عشرات الحلقات المتاحة</p>
                        </div>
                    </div>
                    <div class="step-box">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4>رفع التسجيلات</h4>
                            <p>رفع تسجيل صوتي لتلاوتك للسورة الحالية للمراجعة من المعلم</p>
                        </div>
                    </div>
                    <div class="step-box">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h4>الحصول على التقييم</h4>
                            <p>احصل على ملاحظات مفصلة والتقييم من معلمك المتخصص</p>
                        </div>
                    </div>
                    <div class="step-box">
                        <div class="step-number">5</div>
                        <div class="step-content">
                            <h4>المتابعة المستمرة</h4>
                            <p>تابع تقدمك عبر لوحة التحكم الشاملة وتحسن مستمر مع معلمك</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section style="padding: 80px 32px; background: linear-gradient(135deg, var(--deep-green) 0%, var(--dark-bg) 100%); text-align: center;">
            <div class="max-w-2xl mx-auto">
                <h2 style="font-size: 36px; font-weight: 700; margin-bottom: 16px; color: var(--cream);">
                    جاهز للانضمام؟
                </h2>
                <p style="font-size: 16px; color: var(--slate-blue); margin-bottom: 32px;">
                    ابدأ رحلتك في تحفيظ القرآن الكريم اليوم مع أكاديمية القدس
                </p>
                <a href="{{ route('register') }}" class="btn-primary" style="display: inline-block; font-size: 16px;">
                    تسجيل طالب جديد
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer style="padding: 60px 32px; background: rgba(0, 0, 0, 0.3); border-top: 1px solid rgba(255, 215, 0, 0.1);">
            <div class="max-w-6xl mx-auto">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 40px;">
                    <div>
                        <h3 style="color: var(--cream); font-weight: 600; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-mosque" style="color: var(--gold);"></i>
                            أكاديمية القدس
                        </h3>
                        <p style="color: var(--slate-blue); line-height: 1.6; font-size: 14px;">
                            منصة متكاملة لتحفيظ القرآن الكريم وتعليمه بطرق حديثة وفعّالة
                        </p>
                    </div>
                    <div>
                        <h3 style="color: var(--cream); font-weight: 600; margin-bottom: 16px;">روابط سريعة</h3>
                        <ul style="list-style: none;">
                            <li style="margin-bottom: 8px;"><a href="#about" style="color: var(--slate-blue); text-decoration: none; transition: color 0.3s;">من نحن</a></li>
                            <li style="margin-bottom: 8px;"><a href="#services" style="color: var(--slate-blue); text-decoration: none; transition: color 0.3s;">الخدمات</a></li>
                            <li style="margin-bottom: 8px;"><a href="{{ route('login') }}" style="color: var(--slate-blue); text-decoration: none; transition: color 0.3s;">دخول</a></li>
                            <li><a href="{{ route('register') }}" style="color: var(--gold); text-decoration: none; transition: color 0.3s;">تسجيل جديد</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 style="color: var(--cream); font-weight: 600; margin-bottom: 16px;">التواصل</h3>
                        <div class="footer-info">
                            <i class="fas fa-phone footer-icon"></i>
                            <span>+966 XX XXX XXXX</span>
                        </div>
                        <div class="footer-info">
                            <i class="fas fa-envelope footer-icon"></i>
                            <span>info@alquds-academy.com</span>
                        </div>
                        <div class="footer-info">
                            <i class="fas fa-map-marker-alt footer-icon"></i>
                            <span>المملكة العربية السعودية</span>
                        </div>
                    </div>
                </div>
                <div style="border-top: 1px solid rgba(255, 215, 0, 0.1); padding-top: 32px; text-align: center; color: var(--slate-blue); font-size: 14px;">
                    <p>جميع الحقوق محفوظة © 2026 أكاديمية القدس لتحفيظ القرآن الكريم</p>
                </div>
            </div>
        </footer>
    </body>
</html>
