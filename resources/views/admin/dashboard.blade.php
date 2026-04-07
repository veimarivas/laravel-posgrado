<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="green" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="" />
    <meta content="" name="" />
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <link href="{{ asset('backend/assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('backend/assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
    <script>
        // Apply saved theme IMMEDIATELY before layout.js runs
        (function() {
            var savedTheme = localStorage.getItem('data-bs-theme');
            if (savedTheme) {
                document.documentElement.setAttribute('data-bs-theme', savedTheme);
                sessionStorage.setItem('data-bs-theme', savedTheme);
            }
        })();
    </script>
    <script src="{{ asset('backend/assets/js/layout.js') }}"></script>
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --dash-primary: #0ab39c;
            --dash-primary-light: #f0fdfa;
            --dash-primary-dark: #0d5f59;
            --dash-accent: #f59e0b;
            --dash-accent-light: #fffbeb;
            --dash-surface: #f8fafc;
            --dash-border: #e2e8f0;
            --dash-text: #1e293b;
            --dash-text-muted: #64748b;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.05);
            --shadow-lg: 0 10px 25px -3px rgba(0,0,0,0.08), 0 4px 6px -4px rgba(0,0,0,0.04);
            --sidebar-accent: #0ab39c;
            --sidebar-accent-light: rgba(10, 179, 156, 0.12);
            --sidebar-accent-hover: rgba(10, 179, 156, 0.08);
            --sidebar-text-muted: rgba(255, 255, 255, 0.55);
            --sidebar-text: rgba(255, 255, 255, 0.82);
            --sidebar-text-active: #ffffff;
            --sidebar-bg-dark: #1a1d2e;
            --sidebar-bg-darker: #141624;
            --sidebar-submenu-bg: rgba(0, 0, 0, 0.15);
            --sidebar-border: rgba(255, 255, 255, 0.06);
            --body-font: 'DM Sans', sans-serif;
            --heading-font: 'Outfit', sans-serif;
            --header-bg: #ffffff;
            --header-text: #495057;
            --header-border: #e9ebec;
            --footer-bg: #f8f9fa;
            --footer-text: #878a99;
            --card-bg: #ffffff;
            --card-border: #e9ebec;
        }

        [data-bs-theme="dark"] {
            --dash-surface: #1e1e2d;
            --dash-border: #2d2d3a;
            --dash-text: #e9ecef;
            --dash-text-muted: #9ca3af;
            --sidebar-text-muted: rgba(255, 255, 255, 0.45);
            --sidebar-text: rgba(255, 255, 255, 0.75);
            --sidebar-text-active: #ffffff;
            --sidebar-bg-dark: #212229;
            --sidebar-bg-darker: #191a21;
            --sidebar-submenu-bg: rgba(0, 0, 0, 0.25);
            --sidebar-border: rgba(255, 255, 255, 0.08);
            --header-bg: #212229;
            --header-text: #e9ecef;
            --header-border: #2d2d3a;
            --footer-bg: #212229;
            --footer-text: #9ca3af;
            --card-bg: #212229;
            --card-border: #2d2d3a;
            --dash-primary-light: rgba(10, 179, 156, 0.15);
        }

        body {
            font-family: var(--body-font);
        }

        /* Dark Mode Global Styles */
        [data-bs-theme="dark"] body {
            background-color: #191919 !important;
            color: var(--dash-text);
        }

        [data-bs-theme="dark"] .card {
            background-color: var(--card-bg);
            border-color: var(--card-border);
            color: var(--dash-text);
        }

        [data-bs-theme="dark"] .table {
            --vz-table-bg: transparent;
            --vz-table-color: var(--dash-text);
            --vz-table-border-color: var(--card-border);
        }

        [data-bs-theme="dark"] .table thead th {
            background-color: rgba(255, 255, 255, 0.03);
            color: var(--dash-text-muted);
            border-bottom-color: var(--card-border);
        }

        [data-bs-theme="dark"] .table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }

        [data-bs-theme="dark"] .table td {
            border-bottom-color: var(--card-border);
        }

        [data-bs-theme="dark"] .dropdown-menu {
            background-color: var(--card-bg);
            border-color: var(--card-border);
            color: var(--dash-text);
        }

        [data-bs-theme="dark"] .dropdown-menu .dropdown-item {
            color: var(--dash-text);
        }

        [data-bs-theme="dark"] .dropdown-menu .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background-color: #2d2d3a;
            border-color: var(--card-border);
            color: var(--dash-text);
        }

        [data-bs-theme="dark"] .modal-content {
            background-color: var(--card-bg);
            border-color: var(--card-border);
            color: var(--dash-text);
        }

        [data-bs-theme="dark"] .btn-close {
            filter: invert(1);
        }

        [data-bs-theme="dark"] #scrollbar {
            background-color: var(--sidebar-bg-dark);
        }

        [data-bs-theme="dark"] .navbar-nav .menu-title span {
            color: var(--sidebar-text-muted) !important;
        }

        [data-bs-theme="dark"] .navbar-nav .nav-link {
            color: var(--sidebar-text) !important;
        }

        [data-bs-theme="dark"] .navbar-nav .nav-link:hover {
            color: var(--sidebar-text-active) !important;
            background-color: var(--sidebar-accent-hover);
        }

        [data-bs-theme="dark"] .navbar-nav .nav-link.active {
            color: var(--sidebar-accent) !important;
            background-color: var(--sidebar-accent-light);
        }

        [data-bs-theme="dark"] .navbar-nav .nav-link i {
            color: var(--sidebar-text) !important;
        }

        [data-bs-theme="dark"] .navbar-nav .nav-link:hover i,
        [data-bs-theme="dark"] .navbar-nav .nav-link.active i {
            color: var(--sidebar-accent) !important;
        }

        [data-bs-theme="dark"] .menu-dropdown {
            background-color: var(--sidebar-submenu-bg);
            border-left-color: var(--sidebar-accent);
        }

        [data-bs-theme="dark"] .menu-dropdown .nav-link {
            color: var(--sidebar-text-muted) !important;
        }

        [data-bs-theme="dark"] .menu-dropdown .nav-link:hover,
        [data-bs-theme="dark"] .menu-dropdown .nav-link.active {
            color: var(--sidebar-text-active) !important;
            background-color: rgba(255, 255, 255, 0.03);
        }

        [data-bs-theme="dark"] .menu-arrow,
        [data-bs-theme="dark"] .submenu-arrow {
            color: var(--sidebar-text-muted);
        }

        /* Header Dark Mode */
        [data-bs-theme="dark"] #page-topbar {
            background-color: var(--header-bg) !important;
            border-bottom-color: var(--header-border) !important;
        }

        [data-bs-theme="dark"] .header-profile-user {
            border-color: rgba(255, 255, 255, 0.3);
        }

        [data-bs-theme="dark"] .user-name-text {
            color: var(--header-text) !important;
        }

        [data-bs-theme="dark"] .user-name-sub-text {
            color: var(--header-text) !important;
            opacity: 0.7;
        }

        [data-bs-theme="dark"] .btn-ghost-secondary {
            color: var(--header-text) !important;
        }

        [data-bs-theme="dark"] .btn-ghost-secondary:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Footer Dark Mode */
        [data-bs-theme="dark"] .footer {
            background-color: var(--footer-bg) !important;
            color: var(--footer-text) !important;
            border-top-color: var(--header-border) !important;
        }

        /* Card Styles */
        [data-bs-theme="dark"] .chart-card {
            background-color: var(--card-bg);
            border-color: var(--card-border);
        }

        [data-bs-theme="dark"] .chart-card-header {
            border-bottom-color: var(--card-border);
        }

        [data-bs-theme="dark"] .chart-card-header h6 {
            color: var(--dash-text);
        }

        [data-bs-theme="dark"] .table-card {
            background-color: var(--card-bg);
            border-color: var(--card-border);
        }

        [data-bs-theme="dark"] .table-card-header {
            background-color: rgba(255, 255, 255, 0.02);
            border-bottom-color: var(--card-border);
        }

        [data-bs-theme="dark"] .table-card-header h5 {
            color: var(--dash-text);
        }

        [data-bs-theme="dark"] .filter-bar {
            background-color: var(--card-bg);
            border-color: var(--card-border);
        }

        [data-bs-theme="dark"] .filter-group label {
            color: var(--dash-text-muted);
        }

        [data-bs-theme="dark"] .filter-select {
            background-color: #2d2d3a;
            border-color: var(--card-border);
            color: var(--dash-text);
        }

        [data-bs-theme="dark"] .dashboard-header {
            background: linear-gradient(135deg, var(--sidebar-accent) 0%, #0d5f59 100%);
        }

        [data-bs-theme="dark"] .branch-header {
            background-color: var(--sidebar-accent-light);
        }

        [data-bs-theme="dark"] .branch-header h6 {
            color: var(--dash-text);
        }

        [data-bs-theme="dark"] .empty-state i {
            color: #4b5563;
        }

        /* ===== SIDEBAR BASE ===== */
        #scrollbar {
            padding: 0 0 24px;
        }

        .menu-title {
            padding: 24px 24px 10px;
            margin-bottom: 4px;
            position: relative;
        }

        .menu-title::after {
            content: '';
            position: absolute;
            bottom: 8px;
            left: 24px;
            right: 24px;
            height: 1px;
            background: var(--sidebar-border);
        }

        .menu-title-text {
            font-family: var(--heading-font);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: var(--sidebar-text-muted) !important;
            text-transform: uppercase;
        }

        [data-sidebar="dark"] .menu-title-text {
            color: var(--sidebar-text-muted) !important;
        }

        /* ===== MAIN NAV LINKS ===== */
        .navbar-nav .nav-link.menu-link {
            padding: 11px 20px;
            margin: 2px 10px;
            border-radius: 8px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            align-items: center;
            font-family: var(--body-font);
            font-size: 13.5px;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        .navbar-nav .nav-link.menu-link:hover {
            background-color: var(--sidebar-accent-hover);
            color: var(--sidebar-text-active);
        }

        .navbar-nav .nav-link.menu-link:hover i {
            color: var(--sidebar-accent);
        }

        .navbar-nav .nav-link.menu-link.active {
            background-color: var(--sidebar-accent-light);
            color: var(--sidebar-accent);
            font-weight: 600;
        }

        .navbar-nav .nav-link.menu-link.active i {
            color: var(--sidebar-accent);
        }

        .navbar-nav .nav-link.menu-link.active::after {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: var(--sidebar-accent);
            border-radius: 0 3px 3px 0;
        }

        /* ===== ICONS ===== */
        .navbar-nav .nav-link.menu-link i {
            font-size: 18px;
            width: 22px;
            margin-right: 12px;
            text-align: center;
            transition: color 0.25s ease;
        }

        .navbar-nav .nav-link.menu-link .badge {
            font-size: 9px;
            padding: 2px 7px;
            font-weight: 600;
            letter-spacing: 0.03em;
            border-radius: 4px;
        }

        /* ===== MENU ARROW ===== */
        .menu-arrow {
            display: inline-block;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: auto;
            font-size: 14px;
            color: var(--sidebar-text-muted);
        }

        .nav-link[aria-expanded="true"] .menu-arrow {
            transform: rotate(90deg);
            color: var(--sidebar-text);
        }

        /* ===== SUBMENUS ===== */
        .menu-dropdown {
            background: transparent;
            margin: 0 0 0 16px;
            padding: 4px 0;
            border-left: 2px solid var(--sidebar-border);
        }

        .menu-dropdown .nav-link {
            padding: 8px 20px 8px 32px;
            font-size: 13px;
            color: var(--sidebar-text-muted) !important;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            border-radius: 0;
            margin: 0;
            font-weight: 400;
            position: relative;
        }

        .menu-dropdown .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--sidebar-text-muted);
            opacity: 0;
            transition: all 0.2s ease;
        }

        .menu-dropdown .nav-link:hover {
            color: var(--sidebar-text-active) !important;
            background: transparent;
            padding-left: 36px;
        }

        .menu-dropdown .nav-link:hover::before {
            opacity: 1;
            background: var(--sidebar-accent);
        }

        .menu-dropdown .nav-link.active {
            color: var(--sidebar-accent) !important;
            font-weight: 600;
        }

        .menu-dropdown .nav-link.active::before {
            opacity: 1;
            background: var(--sidebar-accent);
        }

        .menu-dropdown .nav-link i {
            font-size: 14px;
            width: 20px;
            margin-right: 8px;
            text-align: center;
            color: var(--sidebar-text-muted);
        }

        .menu-dropdown .nav-link:hover i {
            color: var(--sidebar-accent);
        }

        /* ===== NESTED SUBMENUS ===== */
        .menu-dropdown .menu-dropdown {
            margin-left: 16px;
        }

        .menu-dropdown .menu-dropdown .nav-link {
            padding-left: 48px;
        }

        .menu-dropdown .menu-dropdown .nav-link:hover {
            padding-left: 52px;
        }

        .submenu-arrow {
            font-size: 13px;
            color: var(--sidebar-text-muted);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-link[aria-expanded="true"] .submenu-arrow {
            transform: rotate(90deg);
        }

        /* ===== SCROLLBAR ===== */
        #scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        #scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        #scrollbar::-webkit-scrollbar-thumb {
            background: rgba(10, 179, 156, 0.4);
            border-radius: 4px;
        }

        #scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(10, 179, 156, 0.7);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991px) {
            #page-topbar {
                background-color: var(--header-bg) !important;
            }
        }
    </style>
    @stack('style')
</head>

<body>

    <div id="layout-wrapper">

        @include('admin.body.header')

        <div class="app-menu navbar-menu">
            <div class="navbar-brand-box">
                <a href="{{ url('/admin/dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('backend/assets/images/logo.png') }}" alt="UNIP" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('backend/assets/images/logo_principal.png') }}" alt="UNIP"
                            height="30">
                    </span>
                </a>
                <a href="{{ url('/admin/dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('backend/assets/images/logo.png') }}" alt="UNIP" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('backend/assets/images/logo_principal.png') }}" alt="UNIP"
                            height="30">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div class="dropdown sidebar-user m-1 rounded">
            </div>

            @include('admin.body.sidebar')

            <div class="sidebar-background"></div>
        </div>

        <div class="vertical-overlay"></div>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('admin')
                </div>
            </div>
            @include('admin.body.footer')
        </div>

    </div>

    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script>
        feather.replace();
    </script>
    <script src="{{ asset('backend/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('backend/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/pages/dashboard-ecommerce.init.js') }}"></script>
    <script src="{{ asset('backend/assets/js/app.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var verticalHoverBtn = document.getElementById('vertical-hover');
            var appMenu = document.querySelector('.app-menu');
            var logoSm = document.querySelector('.logo-sm img');
            var logoLg = document.querySelector('.logo-lg img');

            if (appMenu) {
                function updateLogos() {
                    if (appMenu.classList.contains('hover')) {
                        if (logoSm) logoSm.src = "{{ asset('backend/assets/images/logo.png') }}";
                        if (logoLg) logoLg.src = "{{ asset('backend/assets/images/logo.png') }}";
                    } else {
                        if (logoSm) logoSm.src = "{{ asset('backend/assets/images/logo.png') }}";
                        if (logoLg) logoLg.src = "{{ asset('backend/assets/images/logo_principal.png') }}";
                    }
                }

                updateLogos();

                if (verticalHoverBtn) {
                    verticalHoverBtn.addEventListener('click', function() {
                        setTimeout(updateLogos, 100);
                    });
                }

                window.addEventListener('resize', updateLogos);

                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === 'class') updateLogos();
                    });
                });

                observer.observe(appMenu, { attributes: true });
            }

            // Theme Toggle - handled by app.js (light-dark-mode class)
            // Just update icon when theme changes
            var themeIcon = document.getElementById('theme-icon');
            var htmlEl = document.documentElement;

            function updateThemeIcon() {
                var isDark = htmlEl.getAttribute('data-bs-theme') === 'dark';
                if (themeIcon) {
                    themeIcon.className = isDark ? 'bx bx-sun fs-22' : 'bx bx-moon fs-22';
                }
            }

            // Set initial icon state
            updateThemeIcon();

            // Listen for theme changes from app.js
            window.addEventListener('storage', updateThemeIcon);
            
            // Use MutationObserver to detect attribute changes
            var observer = new MutationObserver(updateThemeIcon);
            observer.observe(htmlEl, { attributes: true, attributeFilter: ['data-bs-theme'] });
        });
    </script>

    @stack('script')
</body>

</html>
