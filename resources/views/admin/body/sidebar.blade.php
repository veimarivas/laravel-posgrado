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
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse menu-dropdown {{ request()->routeIs('admin.permissions.*', 'admin.roles.*', 'admin.role-permissions.*', 'admin.users.*', 'admin.sedes.*', 'admin.departamentos.*', 'admin.cargos.*', 'admin.fases.*', 'admin.cuentas.*') ? 'show' : '' }}"
                    id="sidebarAdmin">
                    <ul class="nav nav-sm flex-column">
                        @if (Auth::guard('web')->user()->can('permisos.general'))
                            <li class="nav-item">
                                <a href="#sidebarPermisos" class="nav-link collapsed" data-bs-toggle="collapse"
                                    role="button"
                                    aria-expanded="{{ request()->routeIs('admin.permissions.*', 'admin.roles.*', 'admin.role-permissions.*', 'admin.users.*') ? 'true' : 'false' }}"
                                    aria-controls="sidebarPermisos">
                                    <i class="ri-shield-keyhole-line"></i>
                                    Roles y Permisos
                                    <span class="submenu-arrow"></span>
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

                        <li class="nav-item">
                            <a href="#sidebarEstructura" class="nav-link collapsed" data-bs-toggle="collapse"
                                role="button"
                                aria-expanded="{{ request()->routeIs('admin.sedes.*', 'admin.departamentos.*', 'admin.cargos.*') ? 'true' : 'false' }}"
                                aria-controls="sidebarEstructura">
                                <i class="ri-building-line"></i>
                                Estructura Organizacional
                                <span class="submenu-arrow"></span>
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

                        <li class="nav-item">
                            <a href="#sidebarConfig" class="nav-link collapsed" data-bs-toggle="collapse" role="button"
                                aria-expanded="{{ request()->routeIs('admin.fases.*', 'admin.cuentas.*') ? 'true' : 'false' }}"
                                aria-controls="sidebarConfig">
                                <i class="ri-cpu-line"></i>
                                Configuración General
                                <span class="submenu-arrow"></span>
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
                    <span class="menu-arrow"></span>
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

                        <li class="nav-item">
                            <a href="#sidebarGestionPersonas" class="nav-link collapsed" data-bs-toggle="collapse"
                                role="button"
                                aria-expanded="{{ request()->routeIs('admin.personas.*', 'admin.estudiantes.*', 'admin.trabajadores.*', 'admin.vendedores.*') ? 'true' : 'false' }}"
                                aria-controls="sidebarGestionPersonas">
                                <i class="ri-user-settings-line"></i>
                                Personas
                                <span class="submenu-arrow"></span>
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

                        <li class="nav-item">
                            <a href="#sidebarFormacion" class="nav-link collapsed" data-bs-toggle="collapse"
                                role="button"
                                aria-expanded="{{ request()->routeIs('admin.grados.*', 'admin.profesiones.*', 'admin.universidades.*') ? 'true' : 'false' }}"
                                aria-controls="sidebarFormacion">
                                <i class="ri-graduation-cap-line"></i>
                                Formación Académica
                                <span class="submenu-arrow"></span>
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
                    <span class="menu-arrow"></span>
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
                        <span class="menu-arrow"></span>
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
                                    <i class="ri-bank-line"></i> Bancos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.cuentas-bancarias.*') ? 'active' : '' }}"
                                    href="{{ route('admin.cuentas-bancarias.listar') }}">
                                    <i class="ri-bank-card-line"></i> Cuentas Bancarias
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
    /* ===== MENU TITLES ===== */
    .menu-title {
        padding: 20px 20px 8px;
        margin-bottom: 4px;
        position: relative;
    }

    .menu-title::after {
        content: '';
        position: absolute;
        bottom: 6px;
        left: 20px;
        right: 20px;
        height: 1px;
        background: var(--sidebar-border, rgba(255, 255, 255, 0.06));
    }

    .menu-title-text {
        font-family: var(--heading-font, 'Outfit', sans-serif);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.1em;
        color: var(--sidebar-text-muted, rgba(255, 255, 255, 0.45)) !important;
        text-transform: uppercase;
    }

    /* ===== MAIN NAV LINKS ===== */
    .navbar-nav .nav-link.menu-link {
        padding: 10px 20px;
        margin: 2px 10px;
        border-radius: 8px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        display: flex;
        align-items: center;
        font-family: var(--body-font, 'DM Sans', sans-serif);
        font-size: 13.5px;
        font-weight: 500;
        letter-spacing: 0.01em;
        color: var(--sidebar-text, rgba(255, 255, 255, 0.75)) !important;
    }

    .navbar-nav .nav-link.menu-link:hover {
        background-color: var(--sidebar-accent-hover, rgba(10, 179, 156, 0.08));
        color: var(--sidebar-text-active, #ffffff) !important;
    }

    .navbar-nav .nav-link.menu-link:hover i {
        color: var(--sidebar-accent, #0ab39c);
    }

    .navbar-nav .nav-link.menu-link.active {
        background-color: var(--sidebar-accent-light, rgba(10, 179, 156, 0.12));
        color: var(--sidebar-accent, #0ab39c) !important;
        font-weight: 600;
    }

    .navbar-nav .nav-link.menu-link.active i {
        color: var(--sidebar-accent, #0ab39c);
    }

    .navbar-nav .nav-link.menu-link.active::after {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 60%;
        background: var(--sidebar-accent, #0ab39c);
        border-radius: 0 3px 3px 0;
    }

    /* ===== ICONS ===== */
    .navbar-nav .nav-link.menu-link i {
        font-size: 18px;
        width: 22px;
        margin-right: 12px;
        text-align: center;
        transition: color 0.25s ease;
        color: var(--sidebar-text, rgba(255, 255, 255, 0.75)) !important;
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
        color: var(--sidebar-text-muted, rgba(255, 255, 255, 0.45));
    }

    .nav-link[aria-expanded="true"] .menu-arrow {
        transform: rotate(90deg);
        color: var(--sidebar-text, rgba(255, 255, 255, 0.75));
    }

    /* ===== SUBMENUS ===== */
    .menu-dropdown {
        background: transparent;
        margin: 0 0 0 16px;
        padding: 4px 0;
        border-left: 2px solid var(--sidebar-border, rgba(255, 255, 255, 0.06));
    }

    .menu-dropdown .nav-link {
        padding: 7px 20px 7px 32px;
        font-size: 13px;
        color: var(--sidebar-text-muted, rgba(255, 255, 255, 0.55)) !important;
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
        background: var(--sidebar-text-muted, rgba(255, 255, 255, 0.45));
        opacity: 0;
        transition: all 0.2s ease;
    }

    .menu-dropdown .nav-link:hover {
        color: var(--sidebar-text-active, #ffffff) !important;
        background: transparent;
        padding-left: 36px;
    }

    .menu-dropdown .nav-link:hover::before {
        opacity: 1;
        background: var(--sidebar-accent, #0ab39c);
    }

    .menu-dropdown .nav-link.active {
        color: var(--sidebar-accent, #0ab39c) !important;
        font-weight: 600;
    }

    .menu-dropdown .nav-link.active::before {
        opacity: 1;
        background: var(--sidebar-accent, #0ab39c);
    }

    .menu-dropdown .nav-link i {
        font-size: 14px;
        width: 20px;
        margin-right: 8px;
        text-align: center;
        color: var(--sidebar-text-muted, rgba(255, 255, 255, 0.55));
    }

    .menu-dropdown .nav-link:hover i {
        color: var(--sidebar-accent, #0ab39c);
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
        color: var(--sidebar-text-muted, rgba(255, 255, 255, 0.45));
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

    /* ===== BADGE ===== */
    .badge.bg-success-subtle.text-success {
        background-color: rgba(10, 179, 156, 0.15) !important;
        border: 1px solid rgba(10, 179, 156, 0.3);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1199.98px) {
        .navbar-nav .nav-link.menu-link {
            padding: 10px 16px;
            margin: 2px 8px;
        }
        .menu-dropdown .nav-link {
            padding-left: 32px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateActiveState() {
            const currentPath = window.location.pathname;
            const allLinks = document.querySelectorAll('.navbar-nav a[href]');

            allLinks.forEach(link => link.classList.remove('active'));

            allLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && !href.startsWith('#')) {
                    try {
                        const url = new URL(href, window.location.origin);
                        if (currentPath === url.pathname) {
                            link.classList.add('active');
                            expandParentMenus(link);
                        }
                    } catch (e) {
                        if (currentPath === href || currentPath.startsWith(href)) {
                            link.classList.add('active');
                            expandParentMenus(link);
                        }
                    }
                }
            });

            updateMenuArrows();
        }

        function expandParentMenus(element) {
            let parentCollapse = element.closest('.collapse');
            while (parentCollapse) {
                const collapseId = parentCollapse.id;
                const trigger = document.querySelector(`[href="#${collapseId}"]`);
                if (trigger) {
                    trigger.classList.remove('collapsed');
                    trigger.setAttribute('aria-expanded', 'true');
                    parentCollapse.classList.add('show');
                    trigger.classList.add('active');
                }
                parentCollapse = parentCollapse.parentElement.closest('.collapse');
            }
        }

        function updateMenuArrows() {
            const collapseTriggers = document.querySelectorAll('[data-bs-toggle="collapse"]');
            collapseTriggers.forEach(trigger => {
                const targetId = trigger.getAttribute('href');
                const target = document.querySelector(targetId);
                if (target && target.classList.contains('show')) {
                    trigger.setAttribute('aria-expanded', 'true');
                    const arrow = trigger.querySelector('.menu-arrow');
                    if (arrow) arrow.style.transform = 'rotate(90deg)';
                }
            });
        }

        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(trigger => {
            trigger.addEventListener('click', function() {
                const arrow = this.querySelector('.menu-arrow');
                if (arrow) {
                    arrow.style.transform = this.getAttribute('aria-expanded') === 'true' ? 'rotate(0deg)' : 'rotate(90deg)';
                }
                this.classList.add('active');
            });
        });

        updateActiveState();
        window.addEventListener('popstate', updateActiveState);
    });
</script>
