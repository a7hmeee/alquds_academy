<!-- Navigation Bar -->
<nav class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white shadow-lg">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="text-3xl">📖</span>
                <h1 class="text-2xl font-bold">أكاديمية القدس</h1>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('quran.index') }}" class="hover:text-emerald-100 transition">السور</a>
                <a href="{{ route('quran.juz.index') }}" class="hover:text-emerald-100 transition">الأجزاء</a>
                <a href="{{ route('quran.statistics') }}" class="hover:text-emerald-100 transition">الإحصائيات</a>
            </div>
        </div>
    </div>
</nav>

<!-- Color Scheme
    Primary: Emerald (emerald-600/700) - أخضر
    Secondary: Blue (blue-600/700) - أزرق
    Accent: Amber (amber-600/700) - ذهبي
    Danger: Rose (rose-600/700) - وردي
    Background: Slate (slate-50/100) - رمادي فاتح
-->

<style>
    /* Color Palette */
    :root {
        --primary: #059669;
        --primary-dark: #047857;
        --secondary: #2563eb;
        --accent: #d97706;
        --danger: #e11d48;
        --bg-light: #f8fafc;
        --bg-white: #ffffff;
        --text-dark: #1f2937;
        --text-gray: #6b7280;
        --border: #e5e7eb;
    }

    /* Smooth Animations */
    * {
        transition: all 0.3s ease;
    }

    body {
        background-color: var(--bg-light);
        color: var(--text-dark);
    }

    /* Card Styles */
    .card {
        background: var(--bg-white);
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        border-left: 4px solid var(--primary);
    }

    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Button Styles */
    .btn {
        padding: 0.5rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
    }

    .btn-secondary {
        background-color: var(--secondary);
        color: white;
    }

    .btn-secondary:hover {
        background-color: #1d4ed8;
    }

    .btn-accent {
        background-color: var(--accent);
        color: white;
    }

    .btn-accent:hover {
        background-color: #b45309;
    }

    /* Header Section */
    .header-section {
        background: linear-gradient(to right, #059669, #047857);
        color: white;
        padding: 3rem 0;
        margin-bottom: 3rem;
        text-align: center;
    }

    .header-section h1 {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        text-align: center;
        border-top: 4px solid var(--primary);
    }

    .stat-card.secondary {
        border-top-color: var(--secondary);
    }

    .stat-card.accent {
        border-top-color: var(--accent);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: var(--primary);
    }

    .stat-card.secondary .stat-number {
        color: var(--secondary);
    }

    .stat-card.accent .stat-number {
        color: var(--accent);
    }

    /* Ayah Display */
    .ayah-container {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        border-right: 4px solid var(--primary);
    }

    .ayah-text {
        font-size: 1.2rem;
        line-height: 2;
        text-align: right;
        direction: rtl;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .ayah-number {
        display: inline-block;
        background-color: #ecfdf5;
        color: var(--primary);
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 600;
        margin-left: 1rem;
    }
</style>
