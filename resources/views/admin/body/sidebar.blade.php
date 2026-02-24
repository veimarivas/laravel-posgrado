<div id="scrollbar">
    <div class="container-fluid">
        <div id="two-column-menu"></div>
        <ul class="navbar-nav" id="navbar-nav">
            <li class="menu-title">
                <span class="menu-title-text" data-key="t-menu">MENÚ PRINCIPAL</span>
            </li>

            <!-- Dashboard Principal -->
            <li class="nav-item">
                <a class="nav-link menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="ri-dashboard-2-line"></i>
                    <span data-key="t-dashboards">Dashboard</span>
                    <span class="badge bg-success-subtle text-success ms-2">Principal</span>
                </a>
            </li>

            <!-- Administración del Sistema -->
            <li class="nav-item">
                <a class="nav-link menu-link collapsed {{ request()->routeIs('admin.permissions.*', 'admin.roles.*', 'admin.role-permissions.*', 'admin.users.*', 'admin.sedes.*', 'admin.departamentos.*', 'admin.cargos.*', 'admin.fases.*', 'admin.cuentas.*') ? 'active' : '' }}"
                    href="#sidebarAdmin" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ request()->routeIs('admin.permissions.*', 'admin.roles.*', 'admin.role-permissions.*', 'admin.users.*', 'admin.sedes.*', 'admin.departamentos.*', 'admin.cargos.*', 'admin.fases.*', 'admin.cuentas.*') ? 'true' : 'false' }}"
                    aria-controls="sidebarAdmin">
                    <i class="ri-settings-3-line"></i>
                    <span>Administración</span>

                </a>
                <div class="collapse menu-dropdown {{ request()->routeIs('admin.permissions.*', 'admin.roles.*', 'admin.role-permissions.*', 'admin.users.*', 'admin.sedes.*', 'admin.departamentos.*', 'admin.cargos.*', 'admin.fases.*', 'admin.cuentas.*') ? 'show' : '' }}"
                    id="sidebarAdmin">
                    <ul class="nav nav-sm flex-column">
                        @if (Auth::guard('web')->user()->can('permisos.general'))
                            <!-- Gestión de Usuarios y Permisos -->
                            <li class="nav-item">
                                <a href="#sidebarPermisos" class="nav-link collapsed" data-bs-toggle="collapse"
                                    role="button"
                                    aria-expanded="{{ request()->routeIs('admin.permissions.*', 'admin.roles.*', 'admin.role-permissions.*', 'admin.users.*') ? 'true' : 'false' }}"
                                    aria-controls="sidebarPermisos">
                                    <i class="ri-shield-keyhole-line"></i>
                                    Roles y Permisos

                                </a>
                                <div class="collapse menu-dropdown {{ request()->routeIs('admin.permissions.*', 'admin.roles.*', 'admin.role-permissions.*', 'admin.users.*') ? 'show' : '' }}"
                                    id="sidebarPermisos">
                                    <ul class="nav nav-sm flex-column">
                                        @if (Auth::guard('web')->user()->can('permisos.listar'))
                                            <li class="nav-item">
                                                <a href="{{ route('admin.permissions.listar') }}"
                                                    class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                                                    Permisos
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::guard('web')->user()->can('permisos.roles.listar'))
                                            <li class="nav-item">
                                                <a href="{{ route('admin.roles.listar') }}"
                                                    class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                                    Roles
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::guard('web')->user()->can('permisos.asignar'))
                                            <li class="nav-item">
                                                <a href="{{ route('admin.role-permissions.index') }}"
                                                    class="nav-link {{ request()->routeIs('admin.role-permissions.*') ? 'active' : '' }}">
                                                    Asignar Permisos
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::guard('web')->user()->can('permisos.usuarios'))
                                            <li class="nav-item">
                                                <a href="{{ route('admin.users.listar') }}"
                                                    class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                                    Usuarios
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        @endif

                        <!-- Gestión de Sedes y Estructura -->
                        <li class="nav-item">
                            <a href="#sidebarEstructura" class="nav-link collapsed" data-bs-toggle="collapse"
                                role="button"
                                aria-expanded="{{ request()->routeIs('admin.sedes.*', 'admin.departamentos.*', 'admin.cargos.*') ? 'true' : 'false' }}"
                                aria-controls="sidebarEstructura">
                                <i class="ri-building-line"></i>
                                Estructura Organizacional

                            </a>
                            <div class="collapse menu-dropdown {{ request()->routeIs('admin.sedes.*', 'admin.departamentos.*', 'admin.cargos.*') ? 'show' : '' }}"
                                id="sidebarEstructura">
                                <ul class="nav nav-sm flex-column">
                                    @if (Auth::guard('web')->user()->can('sedes.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.sedes.listar') }}"
                                                class="nav-link {{ request()->routeIs('admin.sedes.*') ? 'active' : '' }}">
                                                Sedes
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('departamentos.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.departamentos.listar') }}"
                                                class="nav-link {{ request()->routeIs('admin.departamentos.*') ? 'active' : '' }}">
                                                Departamentos
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('cargos.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.cargos.listar') }}"
                                                class="nav-link {{ request()->routeIs('admin.cargos.*') ? 'active' : '' }}">
                                                Cargos Laborales
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>

                        <!-- Configuración General -->
                        <li class="nav-item">
                            <a href="#sidebarConfig" class="nav-link collapsed" data-bs-toggle="collapse" role="button"
                                aria-expanded="{{ request()->routeIs('admin.fases.*', 'admin.cuentas.*') ? 'true' : 'false' }}"
                                aria-controls="sidebarConfig">
                                <i class="ri-cpu-line"></i>
                                Configuración General

                            </a>
                            <div class="collapse menu-dropdown {{ request()->routeIs('admin.fases.*', 'admin.cuentas.*') ? 'show' : '' }}"
                                id="sidebarConfig">
                                <ul class="nav nav-sm flex-column">
                                    @if (Auth::guard('web')->user()->can('fases.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.fases.listar') }}"
                                                class="nav-link {{ request()->routeIs('admin.fases.*') ? 'active' : '' }}">
                                                Fases
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('cuentas.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.cuentas.listar') }}"
                                                class="nav-link {{ request()->routeIs('admin.cuentas.*') ? 'active' : '' }}">
                                                Cuentas Video Llamada
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Gestión de Personas -->
            <li class="nav-item">
                <a class="nav-link menu-link collapsed {{ request()->routeIs('admin.ofertas.cronograma', 'admin.personas.*', 'admin.estudiantes.*', 'admin.trabajadores.*', 'admin.vendedores.*', 'admin.grados.*', 'admin.profesiones.*', 'admin.universidades.*') ? 'active' : '' }}"
                    href="#sidebarPersonas" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ request()->routeIs('admin.ofertas.cronograma', 'admin.personas.*', 'admin.estudiantes.*', 'admin.trabajadores.*', 'admin.vendedores.*', 'admin.grados.*', 'admin.profesiones.*', 'admin.universidades.*') ? 'true' : 'false' }}"
                    aria-controls="sidebarPersonas">
                    <i class="ri-user-line"></i>
                    <span>Gestión de Personas</span>

                </a>
                <div class="collapse menu-dropdown {{ request()->routeIs('admin.ofertas.cronograma', 'admin.personas.*', 'admin.estudiantes.*', 'admin.trabajadores.*', 'admin.vendedores.*', 'admin.grados.*', 'admin.profesiones.*', 'admin.universidades.*') ? 'show' : '' }}"
                    id="sidebarPersonas">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.ofertas.cronograma') }}"
                                class="nav-link {{ request()->routeIs('admin.ofertas.cronograma') ? 'active' : '' }}">
                                <i class="ri-calendar-line"></i>
                                Cronograma
                            </a>
                        </li>

                        <!-- Personas -->
                        <li class="nav-item">
                            <a href="#sidebarGestionPersonas" class="nav-link collapsed" data-bs-toggle="collapse"
                                role="button"
                                aria-expanded="{{ request()->routeIs('admin.personas.*', 'admin.estudiantes.*', 'admin.trabajadores.*', 'admin.vendedores.*') ? 'true' : 'false' }}"
                                aria-controls="sidebarGestionPersonas">
                                <i class="ri-user-settings-line"></i>
                                Personas

                            </a>
                            <div class="collapse menu-dropdown {{ request()->routeIs('admin.personas.*', 'admin.estudiantes.*', 'admin.trabajadores.*', 'admin.vendedores.*') ? 'show' : '' }}"
                                id="sidebarGestionPersonas">
                                <ul class="nav nav-sm flex-column">
                                    @if (Auth::guard('web')->user()->can('personas.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.personas.listar') }}"
                                                class="nav-link {{ request()->routeIs('admin.personas.*') ? 'active' : '' }}">
                                                Listar Personas
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('estudiantes.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.estudiantes.listar') }}"
                                                class="nav-link {{ request()->routeIs('admin.estudiantes.*') ? 'active' : '' }}">
                                                Estudiantes
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('trabajadores.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.trabajadores.listar') }}"
                                                class="nav-link {{ request()->routeIs('admin.trabajadores.*') ? 'active' : '' }}">
                                                Trabajadores
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('asesores.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.vendedores.listar') }}"
                                                class="nav-link {{ request()->routeIs('admin.vendedores.*') ? 'active' : '' }}">
                                                Asesores
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>

                        <!-- Formación Académica -->
                        <li class="nav-item">
                            <a href="#sidebarFormacion" class="nav-link collapsed" data-bs-toggle="collapse"
                                role="button"
                                aria-expanded="{{ request()->routeIs('admin.grados.*', 'admin.profesiones.*', 'admin.universidades.*') ? 'true' : 'false' }}"
                                aria-controls="sidebarFormacion">
                                <i class="ri-graduation-cap-line"></i>
                                Formación Académica

                            </a>
                            <div class="collapse menu-dropdown {{ request()->routeIs('admin.grados.*', 'admin.profesiones.*', 'admin.universidades.*') ? 'show' : '' }}"
                                id="sidebarFormacion">
                                <ul class="nav nav-sm flex-column">
                                    @if (Auth::guard('web')->user()->can('grados.academicos.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.grados.listar') }}"
                                                class="nav-link {{ request()->routeIs('admin.grados.*') ? 'active' : '' }}">
                                                Grados Académicos
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('profesiones.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.profesiones.listar') }}"
                                                class="nav-link {{ request()->routeIs('admin.profesiones.*') ? 'active' : '' }}">
                                                Profesiones
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('universidades.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.universidades.listar') }}"
                                                class="nav-link {{ request()->routeIs('admin.universidades.*') ? 'active' : '' }}">
                                                Universidades
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>


            <!-- Área Académica -->
            <li class="nav-item">
                <a class="nav-link menu-link collapsed {{ request()->routeIs('admin.areas.*', 'admin.convenios.*', 'admin.posgrados.*', 'admin.ofertas.*', 'admin.tipos.*', 'admin.modalidades.*') ? 'active' : '' }}"
                    href="#sidebarAcademica" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ request()->routeIs('admin.areas.*', 'admin.convenios.*', 'admin.posgrados.*', 'admin.ofertas.*', 'admin.tipos.*', 'admin.modalidades.*') ? 'true' : 'false' }}"
                    aria-controls="sidebarAcademica">
                    <i class="ri-book-line"></i>
                    <span>Área Académica</span>

                </a>
                <div class="collapse menu-dropdown {{ request()->routeIs('admin.areas.*', 'admin.convenios.*', 'admin.posgrados.*', 'admin.ofertas.*', 'admin.tipos.*', 'admin.modalidades.*') ? 'show' : '' }}"
                    id="sidebarAcademica">
                    <ul class="nav nav-sm flex-column">
                        @if (Auth::guard('web')->user()->can('areas.listar'))
                            <li class="nav-item">
                                <a href="{{ route('admin.areas.listar') }}"
                                    class="nav-link {{ request()->routeIs('admin.areas.*') ? 'active' : '' }}">
                                    Áreas
                                </a>
                            </li>
                        @endif
                        @if (Auth::guard('web')->user()->can('convenios.listar'))
                            <li class="nav-item">
                                <a href="{{ route('admin.convenios.listar') }}"
                                    class="nav-link {{ request()->routeIs('admin.convenios.*') ? 'active' : '' }}">
                                    Convenios
                                </a>
                            </li>
                        @endif
                        @if (Auth::guard('web')->user()->can('posgrados.listar'))
                            <li class="nav-item">
                                <a href="{{ route('admin.posgrados.listar') }}"
                                    class="nav-link {{ request()->routeIs('admin.posgrados.*') ? 'active' : '' }}">
                                    Posgrados
                                </a>
                            </li>
                        @endif
                        @if (Auth::guard('web')->user()->can('ofertas.academicas.listar'))
                            <li class="nav-item">
                                <a href="{{ route('admin.ofertas.listar') }}"
                                    class="nav-link {{ request()->routeIs('admin.ofertas.*') ? 'active' : '' }}">
                                    Programas
                                </a>
                            </li>
                        @endif
                        @if (Auth::guard('web')->user()->can('tipos.programas.listar'))
                            <li class="nav-item">
                                <a href="{{ route('admin.tipos.listar') }}"
                                    class="nav-link {{ request()->routeIs('admin.tipos.*') ? 'active' : '' }}">
                                    Tipos de Programas
                                </a>
                            </li>
                        @endif

                        @if (Auth::guard('web')->user()->can('modalidades.listar'))
                            <li class="nav-item">
                                <a href="{{ route('admin.modalidades.listar') }}"
                                    class="nav-link {{ request()->routeIs('admin.modalidades.*') ? 'active' : '' }}">
                                    Modalidades
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>

            @if (Auth::guard('web')->user()->can('contabilidad.gestion'))
                <!-- Área Contable -->
                <li class="nav-item">
                    <a class="nav-link menu-link collapsed {{ request()->routeIs('admin.planes.*', 'admin.conceptos.*', 'admin.recibos.*', 'admin.contabilidad.*') ? 'active' : '' }}"
                        href="#sidebarContable" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('admin.planes.*', 'admin.conceptos.*', 'admin.recibos.*', 'admin.contabilidad.*') ? 'true' : 'false' }}"
                        aria-controls="sidebarContable">
                        <i class="ri-money-dollar-circle-line"></i>
                        <span>Área Contable</span>

                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('admin.planes.*', 'admin.conceptos.*', 'admin.recibos.*', 'admin.contabilidad.*') ? 'show' : '' }}"
                        id="sidebarContable">
                        <ul class="nav nav-sm flex-column">
                            @if (Auth::guard('web')->user()->can('planes.pagos.listar'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.planes.listar') }}"
                                        class="nav-link {{ request()->routeIs('admin.planes.*') ? 'active' : '' }}">
                                        Planes de Pago
                                    </a>
                                </li>
                            @endif
                            @if (Auth::guard('web')->user()->can('conceptos.pagos.listar'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.conceptos.listar') }}"
                                        class="nav-link {{ request()->routeIs('admin.conceptos.*') ? 'active' : '' }}">
                                        Conceptos de Pago
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.recibos.*') ? 'active' : '' }}"
                                    href="{{ route('admin.recibos.historial') }}">
                                    <i class="ri-file-text-line"></i> Historial de Recibos
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.comprobantes.*') ? 'active' : '' }}"
                                    href="{{ route('admin.comprobantes.index') }}">
                                    <i class="ri-file-text-line"></i> Comprobantes de pago
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.contabilidad.*') ? 'active' : '' }}"
                                    href="{{ route('admin.contabilidad.buscar') }}">
                                    <i class="ri-calculator-line"></i> Área Contable
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.bancos.*') ? 'active' : '' }}"
                                    href="{{ route('admin.bancos.listar') }}">
                                    <i class="ri-calculator-line"></i> Bancos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.cuentas-bancarias.*') ? 'active' : '' }}"
                                    href="{{ route('admin.cuentas-bancarias.listar') }}">
                                    <i class="ri-calculator-line"></i> Cuentas Bancarias
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
        </ul>
    </div>
</div>

<style>
    /* Sobrescribir algunos estilos para asegurar visibilidad en tema oscuro */
    [data-sidebar="dark"] .navbar-nav .menu-title span {
        color: rgba(255, 255, 255, 0.5) !important;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.05em;
    }

    [data-sidebar="dark"] .navbar-nav .nav-link {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    [data-sidebar="dark"] .navbar-nav .nav-link:hover {
        color: #ffffff !important;
        background-color: rgba(255, 255, 255, 0.05);
    }

    [data-sidebar="dark"] .navbar-nav .nav-link.active {
        color: #ffffff !important;
        background-color: rgba(10, 179, 156, 0.2);
    }

    [data-sidebar="dark"] .navbar-nav .nav-link.active::before {
        background-color: #0ab39c;
    }

    [data-sidebar="dark"] .menu-dropdown {
        background-color: rgba(0, 0, 0, 0.2);
        border-left-color: rgba(10, 179, 156, 0.3);
    }

    [data-sidebar="dark"] .menu-dropdown .nav-link {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    [data-sidebar="dark"] .menu-dropdown .nav-link:hover,
    [data-sidebar="dark"] .menu-dropdown .nav-link.active {
        color: #ffffff !important;
        background-color: rgba(255, 255, 255, 0.05);
    }

    /* Asegurar que los iconos sean visibles */
    [data-sidebar="dark"] .navbar-nav .nav-link i {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    [data-sidebar="dark"] .navbar-nav .nav-link:hover i,
    [data-sidebar="dark"] .navbar-nav .nav-link.active i {
        color: #ffffff !important;
    }

    /* Flechas del menú */
    .menu-arrow {
        display: inline-block;
        transition: transform 0.2s ease;
        margin-left: auto;
    }

    .menu-arrow::before {
        content: "\EA6E";
        font-family: remixicon;
        font-size: 1.125rem;
        line-height: 1;
    }

    .nav-link[aria-expanded="true"] .menu-arrow {
        transform: rotate(90deg);
    }

    /* Badge para el dashboard */
    .badge.bg-success-subtle.text-success {
        background-color: rgba(10, 179, 156, 0.15) !important;
        border: 1px solid rgba(10, 179, 156, 0.3);
    }

    /* Ajustes para mantener consistencia visual */
    .navbar-nav .nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .navbar-nav .nav-link span:not(.badge) {
        flex: 1;
    }

    /* Iconos en submenús */
    .menu-dropdown .nav-link i {
        font-size: 14px;
        width: 20px;
        text-align: center;
    }

    /* Mejorar la indentación visual */
    .menu-dropdown .menu-dropdown .nav-link {
        padding-left: 60px !important;
    }

    .menu-dropdown .menu-dropdown .menu-dropdown .nav-link {
        padding-left: 80px !important;
    }

    /* Scrollbar personalizada para sidebar oscuro */
    #scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    #scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    #scrollbar::-webkit-scrollbar-thumb {
        background: rgba(10, 179, 156, 0.5);
        border-radius: 4px;
    }

    #scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(10, 179, 156, 0.8);
    }
</style>

<script>
    // Script para manejar el estado activo y las flechas
    document.addEventListener('DOMContentLoaded', function() {
        // Función para actualizar el estado activo basado en la URL
        function updateActiveState() {
            const currentPath = window.location.pathname;
            const allLinks = document.querySelectorAll('.navbar-nav a[href]');

            // Remover estado activo de todos los enlaces
            allLinks.forEach(link => {
                link.classList.remove('active');
            });

            // Marcar el enlace activo actual
            allLinks.forEach(link => {
                const href = link.getAttribute('href');

                // Solo procesar enlaces que no sean para colapsar
                if (href && !href.startsWith('#')) {
                    try {
                        // Si es una URL completa
                        const url = new URL(href, window.location.origin);
                        if (currentPath === url.pathname) {
                            link.classList.add('active');
                            expandParentMenus(link);
                        }
                    } catch (e) {
                        // Si es una ruta relativa
                        if (currentPath === href || currentPath.startsWith(href)) {
                            link.classList.add('active');
                            expandParentMenus(link);
                        }
                    }
                }
            });

            // Actualizar flechas basado en menús expandidos
            updateMenuArrows();
        }

        // Función para expandir menús padres
        function expandParentMenus(element) {
            let parentCollapse = element.closest('.collapse');

            while (parentCollapse) {
                const collapseId = parentCollapse.id;
                const trigger = document.querySelector(`[href="#${collapseId}"]`);

                if (trigger) {
                    // Expandir el menú
                    trigger.classList.remove('collapsed');
                    trigger.setAttribute('aria-expanded', 'true');
                    parentCollapse.classList.add('show');

                    // Marcar como activo
                    trigger.classList.add('active');
                }

                parentCollapse = parentCollapse.parentElement.closest('.collapse');
            }
        }

        // Función para actualizar flechas del menú
        function updateMenuArrows() {
            const collapseTriggers = document.querySelectorAll('[data-bs-toggle="collapse"]');

            collapseTriggers.forEach(trigger => {
                const targetId = trigger.getAttribute('href');
                const target = document.querySelector(targetId);

                if (target && target.classList.contains('show')) {
                    trigger.setAttribute('aria-expanded', 'true');
                    const arrow = trigger.querySelector('.menu-arrow');
                    if (arrow) {
                        arrow.style.transform = 'rotate(90deg)';
                    }
                }
            });
        }

        // Manejar clics en elementos del menú
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(trigger => {
            trigger.addEventListener('click', function() {
                const arrow = this.querySelector('.menu-arrow');
                if (arrow) {
                    if (this.getAttribute('aria-expanded') === 'true') {
                        arrow.style.transform = 'rotate(0deg)';
                    } else {
                        arrow.style.transform = 'rotate(90deg)';
                    }
                }

                // Marcar como activo al hacer clic
                this.classList.add('active');
            });
        });

        // Inicializar estado
        updateActiveState();

        // Escuchar cambios de navegación (útil si usas AJAX o SPA)
        window.addEventListener('popstate', updateActiveState);

        // Opcional: Si usas navegación AJAX, llama a updateActiveState después de cada navegación
    });
</script>
