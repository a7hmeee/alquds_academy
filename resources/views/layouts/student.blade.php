<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session('dir', 'rtl') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة الطالب - أكاديمية القدس')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --sidebar-w: 250px;
            --sidebar-collapsed-w: 68px;
            --header-h: 52px;
            --gold: #C9A84C;
            --gold-light: #E8D48B;
            --green-900: #0B1F14;
            --green-800: #122A1C;
            --green-700: #1A3828;
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

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            flex-shrink: 0;
        }

        .brand-icon {
            width: 34px; height: 34px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            display: flex; align-items: center; justify-content: center;
            color: var(--green-900);
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .brand-text {
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s var(--ease);
        }

        .brand-text h1 { font-size: 0.95rem; font-weight: 700; color: var(--text-primary); line-height: 1.3; }
        .brand-text p { font-size: 0.65rem; color: var(--gold); }

        .sidebar.collapsed .brand-text { opacity: 0; width: 0; }

        .sidebar-toggle {
            position: absolute;
            top: 14px;
            left: -13px;
            width: 26px; height: 26px;
            border-radius: 50%;
            background: var(--green-700);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 0.65rem;
            z-index: 1001;
            transition: all 0.3s var(--ease);
        }

        .sidebar-toggle:hover {
            background: var(--gold);
            color: var(--green-900);
            border-color: var(--gold);
        }

        .sidebar.collapsed .sidebar-toggle i { transform: rotate(180deg); }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 10px 0;
        }

        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 3px; }

        .nav-group { margin-bottom: 2px; }

        .nav-group-label {
            padding: 8px 16px 3px;
            font-size: 0.6rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s var(--ease);
        }

        .sidebar.collapsed .nav-group-label { opacity: 0; height: 0; padding: 0; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            margin: 1px 6px;
            border-radius: 7px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
            white-space: nowrap;
            transition: all 0.2s var(--ease);
            position: relative;
            border: none;
            background: none;
            width: calc(100% - 12px);
            cursor: pointer;
            font-family: inherit;
        }

        .nav-item i {
            width: 17px;
            text-align: center;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .nav-item:hover { background: var(--green-700); color: var(--text-primary); }

        .nav-item.active {
            background: rgba(201, 168, 76, 0.1);
            color: var(--gold);
        }

        .nav-item.active i { color: var(--gold); }

        .nav-item.logout:hover {
            background: rgba(220, 80, 80, 0.1);
            color: #E05555;
        }

        .sidebar.collapsed .nav-item {
            justify-content: center;
            padding: 9px 0;
            margin: 1px 4px;
            width: calc(100% - 8px);
        }

        .sidebar.collapsed .nav-item span { display: none; }

        .sidebar.collapsed .nav-item::after {
            content: attr(data-tip);
            position: absolute;
            right: calc(100% + 8px);
            top: 50%;
            transform: translateY(-50%);
            background: var(--green-700);
            color: var(--text-primary);
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 0.7rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.15s var(--ease);
            border: 1px solid var(--border-color);
            z-index: 9999;
        }

        .sidebar.collapsed .nav-item:hover::after { opacity: 1; }

        /* User */
        .sidebar-user {
            padding: 10px 12px;
            border-top: 1px solid var(--border-color);
            flex-shrink: 0;
        }

        .sidebar-user-card {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px;
            border-radius: 8px;
            transition: background 0.2s var(--ease);
        }

        .sidebar-user-card:hover { background: var(--green-700); }

        .user-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            display: flex; align-items: center; justify-content: center;
            color: var(--green-900);
            font-weight: 700;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .user-details {
            flex: 1; min-width: 0; overflow: hidden;
            transition: opacity 0.2s var(--ease);
        }

        .user-details .name {
            font-size: 0.78rem; font-weight: 600; color: var(--text-primary);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .user-details .role { font-size: 0.62rem; color: var(--gold); }

        .sidebar.collapsed .user-details { opacity: 0; width: 0; }

        /* ── MAIN ── */
        .main-wrapper {
            margin-right: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-right 0.3s var(--ease);
        }

        .main-wrapper.expanded { margin-right: var(--sidebar-collapsed-w); }

        .top-header {
            height: var(--header-h);
            background: var(--green-800);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: sticky; top: 0; z-index: 100;
        }

        .header-right {
            display: flex; align-items: center; gap: 10px;
        }

        .mobile-toggle {
            display: none;
            width: 34px; height: 34px;
            border: 1px solid var(--border-color);
            border-radius: 7px;
            background: transparent;
            color: var(--text-secondary);
            cursor: pointer;
            align-items: center; justify-content: center;
            font-size: 0.9rem;
        }

        .header-title { font-size: 0.95rem; font-weight: 600; }

        .header-left { display: flex; align-items: center; gap: 10px; }

        .header-user { position: relative; }

        .header-user-btn {
            display: flex; align-items: center; gap: 6px;
            padding: 5px 10px 5px 7px;
            border-radius: 7px;
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-primary);
            cursor: pointer;
            font-family: inherit;
            font-size: 0.78rem;
            transition: all 0.2s var(--ease);
        }

        .header-user-btn:hover { border-color: var(--border-hover); background: var(--green-700); }

        .header-user-btn .avatar-sm {
            width: 24px; height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            display: flex; align-items: center; justify-content: center;
            color: var(--green-900);
            font-weight: 700; font-size: 0.65rem;
        }

        .header-dropdown {
            position: absolute;
            top: calc(100% + 5px);
            left: 0;
            min-width: 180px;
            background: var(--green-800);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 5px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
            opacity: 0; visibility: hidden;
            transform: translateY(5px);
            transition: all 0.2s var(--ease);
            z-index: 1000;
        }

        .header-user:hover .header-dropdown {
            opacity: 1; visibility: visible; transform: translateY(0);
        }

        .dropdown-link {
            display: flex; align-items: center; gap: 7px;
            padding: 7px 10px;
            border-radius: 5px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.78rem;
            transition: all 0.15s var(--ease);
            border: none; background: none; width: 100%;
            cursor: pointer; font-family: inherit;
        }

        .dropdown-link:hover { background: var(--green-700); color: var(--text-primary); }
        .dropdown-link.danger:hover { background: rgba(220,80,80,0.1); color: #E05555; }
        .dropdown-link i { width: 14px; text-align: center; font-size: 0.75rem; }

        .dropdown-divider { height: 1px; background: var(--border-color); margin: 3px 0; }

        .page-content { flex: 1; padding: 20px; }

        .page-footer {
            padding: 14px 20px;
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            font-size: 0.7rem;
            text-align: center;
        }

        .page-footer span { color: var(--gold); }

        /* Common UI */
        .card {
            background: var(--green-800);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 16px;
        }

        .btn {
            padding: 8px 14px;
            border-radius: 7px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-family: inherit;
            font-size: 0.85rem;
            transition: all 0.2s var(--ease);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary { background: var(--gold); color: var(--green-900); }
        .btn-primary:hover { opacity: 0.9; }

        .form-control {
            width: 100%;
            padding: 8px 10px;
            background: var(--green-900);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-primary);
            font-size: 0.85rem;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 2px rgba(201, 168, 76, 0.15);
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: var(--green-900);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--gold);
            transition: width 0.3s var(--ease);
        }

        .table { width: 100%; border-collapse: separate; border-spacing: 0 4px; }
        .table th { color: var(--text-muted); font-weight: 600; padding: 8px; text-align: right; font-size: 0.7rem; text-transform: uppercase; }
        .table td { padding: 8px; color: var(--text-primary); }
        .table tr { background: rgba(11, 31, 20, 0.3); border-radius: 6px; }

        /* Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(11, 31, 20, 0.85);
            z-index: 999;
        }

        .sidebar-overlay.active { display: block; }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(100%); }
            .sidebar.open { transform: translateX(0); box-shadow: -4px 0 16px rgba(0,0,0,0.5); }
            .sidebar-toggle { display: none; }
            .main-wrapper { margin-right: 0 !important; }
            .mobile-toggle { display: flex; }
        }

        @media (max-width: 640px) {
            .page-content { padding: 14px; }
            .header-user-btn span.user-name-text { display: none; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in { animation: fadeIn 0.3s var(--ease); }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <button class="sidebar-toggle" onclick="toggleSidebar()" title="طي/فتح القائمة">
            <i class="fas fa-chevron-left"></i>
        </button>

        <div class="sidebar-brand">
            <div class="brand-icon"><i class="fas fa-mosque"></i></div>
            <div class="brand-text">
                <h1>أكاديمية القدس</h1>
                <p>بوابة الطالب</p>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-group">
                <div class="nav-group-label">الرئيسية</div>
                <a href="{{ route('student.dashboard') }}" class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" data-tip="لوحة التحكم">
                    <i class="fas fa-home"></i>
                    <span>لوحة التحكم</span>
                </a>
            </div>

            <div class="nav-group">
                <div class="nav-group-label">التسجيلات</div>
                <a href="{{ route('student.submissions') }}" class="nav-item {{ request()->routeIs('student.submissions') ? 'active' : '' }}" data-tip="تسليماتي">
                    <i class="fas fa-file-audio"></i>
                    <span>تسليماتي</span>
                </a>
                <a href="{{ route('recordings.upload') }}" class="nav-item {{ request()->routeIs('recordings.upload') ? 'active' : '' }}" data-tip="رفع تسجيل">
                    <i class="fas fa-microphone"></i>
                    <span>رفع تسجيل</span>
                </a>
                <a href="{{ route('recordings.dashboard') }}" class="nav-item {{ request()->routeIs('recordings.dashboard') ? 'active' : '' }}" data-tip="لوحة التسجيلات">
                    <i class="fas fa-music"></i>
                    <span>لوحة التسجيلات</span>
                </a>
                <a href="{{ route('student.recordings.list') }}" class="nav-item {{ request()->routeIs('student.recordings.list') ? 'active' : '' }}" data-tip="سجل التسجيلات">
                    <i class="fas fa-list-ul"></i>
                    <span>سجل التسجيلات</span>
                </a>
            </div>

            <div class="nav-group">
                <div class="nav-group-label">الحلقات والتقدم</div>
                <a href="{{ route('student.circles') }}" class="nav-item {{ request()->routeIs('student.circles') ? 'active' : '' }}" data-tip="حلقاتي">
                    <i class="fas fa-mosque"></i>
                    <span>حلقاتي</span>
                </a>
                <a href="{{ route('student.progress') }}" class="nav-item {{ request()->routeIs('student.progress') ? 'active' : '' }}" data-tip="تقدمي">
                    <i class="fas fa-chart-line"></i>
                    <span>تقدمي</span>
                </a>
            </div>

            <div class="nav-group">
                <div class="nav-group-label">المهام والمراجعة</div>
                <a href="{{ route('student.assignments') }}" class="nav-item {{ request()->routeIs('student.assignments*') ? 'active' : '' }}" data-tip="مهام الحفظ">
                    <i class="fas fa-tasks"></i>
                    <span>مهام الحفظ</span>
                </a>
            </div>

            <div class="nav-group">
                <div class="nav-group-label">الحساب</div>
                <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}" data-tip="ملفي الشخصي">
                    <i class="fas fa-user-cog"></i>
                    <span>ملفي الشخصي</span>
                </a>
            </div>

            <div class="nav-group" style="margin-top: auto; padding-top: 6px; border-top: 1px solid var(--border-color);">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-item logout" data-tip="تسجيل الخروج">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>تسجيل الخروج</span>
                    </button>
                </form>
            </div>
        </nav>

        <div class="sidebar-user">
            <div class="sidebar-user-card">
                <div class="user-avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</div>
                <div class="user-details">
                    <div class="name">{{ auth()->user()->name }}</div>
                    <div class="role">طالب</div>
                </div>
            </div>
        </div>
    </aside>

    <div class="main-wrapper" id="mainWrapper">
        <header class="top-header">
            <div class="header-right">
                <button class="mobile-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="header-title">@yield('page-title', 'لوحة الطالب')</span>
            </div>

            <div class="header-left">
                <div class="header-user">
                    <button class="header-user-btn">
                        <div class="avatar-sm">{{ mb_substr(auth()->user()->name, 0, 1) }}</div>
                        <span class="user-name-text">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down" style="font-size:0.55rem; color:var(--text-muted);"></i>
                    </button>
                    <div class="header-dropdown">
                        <a href="{{ route('profile.edit') }}" class="dropdown-link">
                            <i class="fas fa-user"></i> ملفي الشخصي
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
                if (icon) icon.style.transform = sidebar.classList.contains('collapsed') ? 'rotate(180deg)' : '';
                localStorage.setItem('student-sidebar-collapsed', sidebar.classList.contains('collapsed'));
            }
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'b') { e.preventDefault(); toggleSidebar(); }
            if (e.key === 'Escape') closeSidebar();
        });

        (function() {
            if (window.innerWidth > 1024 && localStorage.getItem('student-sidebar-collapsed') === 'true') {
                sidebar.classList.add('collapsed');
                mainWrapper.classList.add('expanded');
                const icon = sidebar.querySelector('.sidebar-toggle i');
                if (icon) icon.style.transform = 'rotate(180deg)';
            }
        })();
    </script>
</body>
</html>
