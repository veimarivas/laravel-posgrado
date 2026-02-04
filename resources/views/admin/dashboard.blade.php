<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="green">

<head>
    <meta charset="utf-8" />
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="" />
    <meta content="" name="" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- jsvectormap css -->
    <link href="{{ asset('backend/assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!--Swiper slider css-->
    <link href="{{ asset('backend/assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="{{ asset('backend/assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('backend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('backend/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        /* Mejoras para la navegación */
        #scrollbar {
            padding: 0 0 20px;
        }

        .menu-title {
            padding: 20px 24px 12px;
            margin-bottom: 8px;
        }

        .menu-title-text {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.05em;
            color: #878a99 !important;
            text-transform: uppercase;
        }

        .navbar-nav .nav-link.menu-link {
            padding: 12px 24px;
            margin: 2px 0;
            border-radius: 0;
            transition: all 0.2s ease;
            position: relative;
            display: flex;
            align-items: center;
        }

        .navbar-nav .nav-link.menu-link:hover {
            background-color: rgba(10, 179, 156, 0.08);
            padding-left: 28px;
        }

        .navbar-nav .nav-link.menu-link.active {
            background-color: rgba(10, 179, 156, 0.12);
            color: #0ab39c;
            font-weight: 500;
        }

        .navbar-nav .nav-link.menu-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background-color: #0ab39c;
            border-radius: 0 3px 3px 0;
        }

        .menu-item-text {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
        }

        .menu-arrow {
            transition: transform 0.2s ease;
            font-size: 16px;
            color: #878a99;
        }

        .nav-link[aria-expanded="true"] .menu-arrow {
            transform: rotate(90deg);
        }

        /* Submenús */
        .menu-dropdown {
            background: rgba(10, 179, 156, 0.03);
            margin: 0;
            border-left: 2px solid rgba(10, 179, 156, 0.1);
        }

        .menu-dropdown .nav-link {
            padding: 10px 24px 10px 48px;
            font-size: 13.5px;
            color: #495057;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .menu-dropdown .nav-link:hover {
            background-color: rgba(10, 179, 156, 0.05);
            padding-left: 52px;
            color: #0ab39c;
        }

        .menu-dropdown .nav-link.active {
            background-color: rgba(10, 179, 156, 0.08);
            color: #0ab39c;
            font-weight: 500;
        }

        .menu-dropdown .menu-dropdown .nav-link {
            padding-left: 60px;
        }

        .menu-dropdown .menu-dropdown .nav-link:hover {
            padding-left: 64px;
        }

        .submenu-arrow {
            font-size: 14px;
            color: #adb5bd;
            transition: transform 0.2s ease;
        }

        .nav-link[aria-expanded="true"] .submenu-arrow {
            transform: rotate(90deg);
        }

        /* Iconos */
        .navbar-nav .nav-link.menu-link i {
            font-size: 18px;
            width: 24px;
            margin-right: 12px;
        }

        .menu-dropdown .nav-link i {
            font-size: 14px;
            width: 20px;
            margin-right: 8px;
        }

        /* Badges */
        .navbar-nav .badge {
            font-size: 10px;
            padding: 3px 6px;
            font-weight: 500;
        }

        /* Scrollbar personalizada */
        #scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        #scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        #scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        #scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Responsive */
        @media (max-width: 1199.98px) {
            .navbar-nav .nav-link.menu-link {
                padding: 10px 16px;
            }

            .menu-dropdown .nav-link {
                padding-left: 40px;
            }
        }

        /* Estilos para los logos */
        .logo-sm img {
            transition: all 0.3s ease;
        }

        .logo-lg img {
            transition: all 0.3s ease;
        }
    </style>
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('admin.body.header')

        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo (para sidebar comprimido y expandido en modo dark) -->
                <a href="{{ url('/admin/dashboard') }}" class="logo logo-dark">
                    <!-- Logo pequeño (sidebar comprimido) -->
                    <span class="logo-sm">
                        <img src="{{ asset('backend/assets/images/logo.png') }}" alt="UNIP" height="22">
                    </span>
                    <!-- Logo grande (sidebar expandido) -->
                    <span class="logo-lg">
                        <img src="{{ asset('backend/assets/images/logo_principal.png') }}" alt="UNIP"
                            height="30">
                    </span>
                </a>

                <!-- Light Logo (para sidebar comprimido y expandido en modo light) -->
                <a href="{{ url('/admin/dashboard') }}" class="logo logo-light">
                    <!-- Logo pequeño (sidebar comprimido) -->
                    <span class="logo-sm">
                        <img src="{{ asset('backend/assets/images/logo.png') }}" alt="UNIP" height="22">
                    </span>
                    <!-- Logo grande (sidebar expandido) -->
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
                <!-- Aquí puedes agregar información del usuario si es necesario -->
            </div>

            @include('admin.body.sidebar')

            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->

        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    @yield('admin')

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            @include('admin.body.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script>
        feather.replace();
    </script>
    <script src="{{ asset('backend/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('backend/assets/js/plugins.js') }}"></script>

    <!-- apexcharts -->
    <script src="{{ asset('backend/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Vector map-->
    <script src="{{ asset('backend/assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/jsvectormap/maps/world-merc.js') }}"></script>

    <!--Swiper slider js-->
    <script src="{{ asset('backend/assets/libs/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Dashboard init -->
    <script src="{{ asset('backend/assets/js/pages/dashboard-ecommerce.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('backend/assets/js/app.js') }}"></script>

    <!-- Script para manejar el cambio de logos -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener el botón que controla el sidebar
            const verticalHoverBtn = document.getElementById('vertical-hover');
            const appMenu = document.querySelector('.app-menu');
            const logoSm = document.querySelector('.logo-sm img');
            const logoLg = document.querySelector('.logo-lg img');

            // Función para actualizar los logos según el estado del sidebar
            function updateLogos() {
                // Verificar si el sidebar está comprimido (tiene la clase 'hover')
                if (appMenu.classList.contains('hover')) {
                    // Sidebar comprimido - mostrar logo.jpeg en ambos
                    if (logoSm) {
                        logoSm.src = "{{ asset('backend/assets/images/logo.png') }}";
                    }
                    if (logoLg) {
                        logoLg.src = "{{ asset('backend/assets/images/logo.png') }}";
                    }
                } else {
                    // Sidebar expandido - mostrar diferentes logos
                    if (logoSm) {
                        logoSm.src = "{{ asset('backend/assets/images/logo.png') }}";
                    }
                    if (logoLg) {
                        logoLg.src = "{{ asset('backend/assets/images/logo_principal.png') }}";
                    }
                }
            }

            // Actualizar logos al cargar la página
            updateLogos();

            // Actualizar logos cuando se cambie el tamaño del sidebar
            if (verticalHoverBtn) {
                verticalHoverBtn.addEventListener('click', function() {
                    // Esperar un momento para que se aplique la clase
                    setTimeout(updateLogos, 100);
                });
            }

            // También actualizar cuando se redimensione la ventana
            window.addEventListener('resize', updateLogos);

            // Observar cambios en las clases del sidebar para detectar cambios
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        updateLogos();
                    }
                });
            });

            if (appMenu) {
                observer.observe(appMenu, {
                    attributes: true
                });
            }
        });
    </script>

    @stack('script')
</body>

</html>
