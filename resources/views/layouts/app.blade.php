<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'أكاديمية القدس')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --sidebar-w: 260px;
            --sidebar-collapsed-w: 72px;
            --header-h: 56px;
            --gold: #C9A84C;
            --gold-light: #E8D48B;
            --green-900: #0B1F14;
            --green-800: #122A1C;
            --green-700: #1A3828;
            --green-600: #245035;
            --text-primary: #E8E6E1;
            --text-secondary: #8A9A8E;
            --text-muted: #5C6D60;
            --border-color: rgba(201, 168, 76, 0.12);
            --border-hover: rgba(201, 168, 76, 0.25);
            --ease: cubic-bezier(0.4, 0, 0.2, 1);
            --cream: #F5F1E8;
            --slate-blue: #6C8EA0;
            --dark-bg: #0C1A14;
            --surface: #13281E;
            --deep-green: #0A5C36;
            --border: rgba(201, 168, 76, 0.12);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--green-900);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            right: 0; top: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--green-800);
            border-left: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: width 0.3s var(--ease);
            overflow: hidden;
        }

        .sidebar.collapsed { width: var(--sidebar-collapsed-w); }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 18px;
            border-bottom: 1px solid var(--border-color);
            min-height: 56px;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            display: flex; align-items: center; justify-content: center;
            color: var(--green-900);
            font-size: 1rem;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-text {
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s var(--ease), width 0.3s var(--ease);
        }

        .sidebar-brand .brand-text h1 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.3;
        }

        .sidebar-brand .brand-text p {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        .sidebar.collapsed .brand-text { opacity: 0; width: 0; }

        /* Toggle button */
        .sidebar-toggle {
            position: absolute;
            top: 16px;
            left: -14px;
            width: 28px; height: 28px;
            border-radius: 50%;
            background: var(--green-700);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 0.7rem;
            z-index: 1001;
            transition: all 0.3s var(--ease);
        }

        .sidebar-toggle:hover {
            background: var(--gold);
            color: var(--green-900);
            border-color: var(--gold);
        }

        .sidebar.collapsed .sidebar-toggle i { transform: rotate(180deg); }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 12px 0;
        }

        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 3px; }

        .nav-group { margin-bottom: 4px; }

        .nav-group-label {
            padding: 8px 18px 4px;
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s var(--ease);
        }

        .sidebar.collapsed .nav-group-label { opacity: 0; height: 0; padding: 0; margin: 0; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 18px;
            margin: 1px 8px;
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            white-space: nowrap;
            transition: all 0.2s var(--ease);
            border: none;
            background: none;
            width: calc(100% - 16px);
            cursor: pointer;
            font-family: inherit;
        }

        .nav-item i {
            width: 18px;
            text-align: center;
            font-size: 0.9rem;
            flex-shrink: 0;
            transition: color 0.2s var(--ease);
        }

        .nav-item:hover {
            background: var(--green-700);
            color: var(--text-primary);
        }

        .nav-item.active {
            background: rgba(201, 168, 76, 0.1);
            color: var(--gold);
        }

        .nav-item.active i { color: var(--gold); }

        .nav-item.active::before {
            content: '';
            position: absolute;
            right: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 20px;
            background: var(--gold);
            border-radius: 0 3px 3px 0;
        }

        .nav-item { position: relative; }

        .nav-item.logout:hover {
            background: rgba(220, 80, 80, 0.1);
            color: #E05555;
        }

        .sidebar.collapsed .nav-item {
            justify-content: center;
            padding: 10px 0;
            margin: 1px 6px;
            width: calc(100% - 12px);
        }

        .sidebar.collapsed .nav-item span { display: none; }
        .sidebar.collapsed .nav-item::before { display: none; }

        /* Tooltip on collapsed */
        .sidebar.collapsed .nav-item {
            position: relative;
        }

        .sidebar.collapsed .nav-item::after {
            content: attr(data-tip);
            position: absolute;
            right: calc(100% + 10px);
            top: 50%;
            transform: translateY(-50%);
            background: var(--green-700);
            color: var(--text-primary);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.15s var(--ease);
            border: 1px solid var(--border-color);
            z-index: 9999;
        }

        .sidebar.collapsed .nav-item:hover::after { opacity: 1; }

        /* User section */
        .sidebar-user {
            padding: 12px 14px;
            border-top: 1px solid var(--border-color);
            flex-shrink: 0;
        }

        .sidebar-user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
            border-radius: 10px;
            transition: background 0.2s var(--ease);
        }

        .sidebar-user-card:hover { background: var(--green-700); }

        .user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            display: flex; align-items: center; justify-content: center;
            color: var(--green-900);
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .user-details {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            transition: opacity 0.2s var(--ease);
        }

        .user-details .name {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-details .role {
            font-size: 0.65rem;
            color: var(--gold);
            font-weight: 500;
        }

        .sidebar.collapsed .user-details { opacity: 0; width: 0; }

        /* ── MAIN AREA ── */
        .main-wrapper {
            margin-right: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-right 0.3s var(--ease);
        }

        .main-wrapper.expanded { margin-right: var(--sidebar-collapsed-w); }

        /* Header */
        .top-header {
            height: var(--header-h);
            background: var(--green-800);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mobile-toggle {
            display: none;
            width: 36px; height: 36px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: transparent;
            color: var(--text-secondary);
            cursor: pointer;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .header-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-btn {
            width: 36px; height: 36px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-secondary);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: all 0.2s var(--ease);
            font-size: 0.9rem;
        }

        .header-btn:hover {
            border-color: var(--border-hover);
            color: var(--text-primary);
            background: var(--green-700);
        }

        /* User dropdown */
        .header-user {
            position: relative;
        }

        .header-user-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px 6px 8px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-primary);
            cursor: pointer;
            font-family: inherit;
            font-size: 0.8rem;
            transition: all 0.2s var(--ease);
        }

        .header-user-btn:hover { border-color: var(--border-hover); background: var(--green-700); }

        .header-user-btn .avatar-sm {
            width: 26px; height: 26px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            display: flex; align-items: center; justify-content: center;
            color: var(--green-900);
            font-weight: 700;
            font-size: 0.7rem;
        }

        .header-dropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            min-width: 200px;
            background: var(--green-800);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 6px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            opacity: 0;
            visibility: hidden;
            transform: translateY(6px);
            transition: all 0.2s var(--ease);
            z-index: 1000;
        }

        .header-user:hover .header-dropdown,
        .header-dropdown.show {
            opacity: 1; visibility: visible; transform: translateY(0);
        }

        .dropdown-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 6px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.8rem;
            transition: all 0.15s var(--ease);
            border: none;
            background: none;
            width: 100%;
            cursor: pointer;
            font-family: inherit;
        }

        .dropdown-link:hover { background: var(--green-700); color: var(--text-primary); }
        .dropdown-link.danger:hover { background: rgba(220,80,80,0.1); color: #E05555; }
        .dropdown-link i { width: 16px; text-align: center; font-size: 0.8rem; }

        .dropdown-divider {
            height: 1px;
            background: var(--border-color);
            margin: 4px 0;
        }

        /* Content */
        .page-content {
            flex: 1;
            padding: 24px;
        }

        /* Footer */
        .page-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            font-size: 0.75rem;
            text-align: center;
        }

        .page-footer span { color: var(--gold); }

        /* ── OVERLAY ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(11, 31, 20, 0.85);
            z-index: 999;
        }

        .sidebar-overlay.active { display: block; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(100%);
                box-shadow: none;
            }

            .sidebar.open {
                transform: translateX(0);
                box-shadow: -4px 0 20px rgba(0,0,0,0.5);
            }

            .sidebar-toggle { display: none; }

            .main-wrapper {
                margin-right: 0 !important;
            }

            .mobile-toggle { display: flex; }
        }

        @media (max-width: 640px) {
            .page-content { padding: 16px; }
            .top-header { padding: 0 16px; }
            .header-user-btn span.user-name-text { display: none; }
        }

        /* ── UTILITIES ── */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in { animation: fadeIn 0.3s var(--ease); }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <button class="sidebar-toggle" onclick="toggleSidebar()" title="طي/فتح القائمة">
            <i class="fas fa-chevron-left"></i>
        </button>

        <div class="sidebar-brand">
            <div class="brand-icon"><i class="fas fa-mosque"></i></div>
            <div class="brand-text">
                <h1>أكاديمية القدس</h1>
                <p>نظام إدارة الحلقات</p>
            </div>
        </div>

        <nav class="sidebar-nav">
            @include('components.sidebar-nav')
        </nav>

        <div class="sidebar-user">
            <div class="sidebar-user-card">
                <div class="user-avatar">{{ mb_substr(auth()->user()?->name ?? 'م', 0, 1) }}</div>
                <div class="user-details">
                    <div class="name">{{ auth()->user()?->name ?? 'مستخدم' }}</div>
                    @php $userRole = auth()->user()?->roles?->first()?->name; @endphp
                    <div class="role">{{ $userRole === 'super admin' ? 'مدير النظام' : ($userRole === 'teacher' ? 'معلم' : ($userRole ?? '')) }}</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main -->
    <div class="main-wrapper" id="mainWrapper">
        <header class="top-header">
            <div class="header-right">
                <button class="mobile-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="header-title">@yield('page-title', 'لوحة التحكم')</span>
            </div>

            <div class="header-left">
                <div class="header-user">
                    <button class="header-user-btn">
                        <div class="avatar-sm">{{ mb_substr(auth()->user()?->name ?? 'م', 0, 1) }}</div>
                        <span class="user-name-text">{{ auth()->user()?->name ?? 'مستخدم' }}</span>
                        <i class="fas fa-chevron-down" style="font-size:0.6rem; color:var(--text-muted);"></i>
                    </button>
                    <div class="header-dropdown">
                        <a href="{{ route('profile.edit') }}" class="dropdown-link">
                            <i class="fas fa-user"></i> الملف الشخصي
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-link danger">
                                <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="page-content animate-in">
            @yield('content')
        </main>

        <footer class="page-footer">
            <span>أكاديمية القدس</span> &copy; {{ date('Y') }}
        </footer>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const overlay = document.getElementById('overlay');

        function toggleSidebar() {
            const isMobile = window.innerWidth <= 1024;
            if (isMobile) {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
            } else {
                sidebar.classList.toggle('collapsed');
                mainWrapper.classList.toggle('expanded');
                const icon = sidebar.querySelector('.sidebar-toggle i');
                if (icon) {
                    icon.style.transform = sidebar.classList.contains('collapsed') ? 'rotate(180deg)' : '';
                }
                localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
            }
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Keyboard: Ctrl+B to toggle, Esc to close on mobile
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'b') { e.preventDefault(); toggleSidebar(); }
            if (e.key === 'Escape') closeSidebar();
        });

        // Restore sidebar state
        (function() {
            if (window.innerWidth > 1024 && localStorage.getItem('sidebar-collapsed') === 'true') {
                sidebar.classList.add('collapsed');
                mainWrapper.classList.add('expanded');
                const icon = sidebar.querySelector('.sidebar-toggle i');
                if (icon) icon.style.transform = 'rotate(180deg)';
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>