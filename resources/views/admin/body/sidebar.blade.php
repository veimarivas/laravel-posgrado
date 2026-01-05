<div id="scrollbar">
    <div class="container-fluid">
        <div id="two-column-menu"></div>
        <ul class="navbar-nav" id="navbar-nav">
            <li class="menu-title"><span data-key="t-menu">Menu</span></li>

            <!-- Dashboard Principal -->
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{ route('admin.dashboard') }}">
                    <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                </a>
            </li>


            <!-- Administración del Sistema -->
            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarAdmin" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarAdmin">
                    <i class="ri-settings-3-line"></i> <span>Administración</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarAdmin">
                    <ul class="nav nav-sm flex-column">
                        @if (Auth::guard('web')->user()->can('permisos.general'))
                            <!-- Gestión de Usuarios y Permisos -->
                            <li class="nav-item">
                                <a href="#sidebarPermisos" class="nav-link" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarPermisos">
                                    Roles y Permisos
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarPermisos">
                                    <ul class="nav nav-sm flex-column">
                                        @if (Auth::guard('web')->user()->can('permisos.listar'))
                                            <li class="nav-item">
                                                <a href="{{ route('admin.permissions.listar') }}" class="nav-link">
                                                    Permisos
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::guard('web')->user()->can('permisos.roles.listar'))
                                            <li class="nav-item">
                                                <a href="{{ route('admin.roles.listar') }}" class="nav-link">
                                                    Roles
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::guard('web')->user()->can('permisos.asignar'))
                                            <li class="nav-item">
                                                <a href="{{ route('admin.role-permissions.index') }}" class="nav-link">
                                                    Asignar Permisos
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::guard('web')->user()->can('permisos.usuarios'))
                                            <li class="nav-item">
                                                <a href="{{ route('admin.users.listar') }}" class="nav-link">
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
                            <a href="#sidebarEstructura" class="nav-link" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarEstructura">
                                Estructura Organizacional
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarEstructura">
                                <ul class="nav nav-sm flex-column">
                                    @if (Auth::guard('web')->user()->can('sedes.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.sedes.listar') }}" class="nav-link">
                                                Sedes
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('departamentos.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.departamentos.listar') }}" class="nav-link">
                                                Departamentos
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('cargos.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.cargos.listar') }}" class="nav-link">
                                                Cargos Laborales
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>

                        <!-- Configuración General -->
                        <li class="nav-item">
                            <a href="#sidebarConfig" class="nav-link" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarConfig">
                                Configuración General
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarConfig">
                                <ul class="nav nav-sm flex-column">
                                    @if (Auth::guard('web')->user()->can('fases.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.fases.listar') }}" class="nav-link">
                                                Fases
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('cuentas.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.cuentas.listar') }}" class="nav-link">
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
                <a class="nav-link menu-link" href="#sidebarPersonas" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarPersonas">
                    <i class="ri-user-line"></i> <span>Gestión de Personas</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarPersonas">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.ofertas.cronograma') }}" class="nav-link">
                                Cronograma
                            </a>
                        </li>

                        <!-- Personas -->
                        <li class="nav-item">
                            <a href="#sidebarGestionPersonas" class="nav-link" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarGestionPersonas">
                                Personas
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarGestionPersonas">
                                <ul class="nav nav-sm flex-column">
                                    @if (Auth::guard('web')->user()->can('personas.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.personas.listar') }}" class="nav-link">
                                                Listar Personas
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('estudiantes.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.estudiantes.listar') }}" class="nav-link">
                                                Estudiantes
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('trabajadores.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.trabajadores.listar') }}" class="nav-link">
                                                Trabajadores
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a href="{{ route('admin.vendedores.listar') }}" class="nav-link">
                                            Asesores
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Formación Académica -->
                        <li class="nav-item">
                            <a href="#sidebarFormacion" class="nav-link" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarFormacion">
                                Formación Académica
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarFormacion">
                                <ul class="nav nav-sm flex-column">
                                    @if (Auth::guard('web')->user()->can('grados.academicos.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.grados.listar') }}" class="nav-link">
                                                Grados Académicos
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('profesiones.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.profesiones.listar') }}" class="nav-link">
                                                Profesiones
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('universidades.listar'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.universidades.listar') }}" class="nav-link">
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
                <a class="nav-link menu-link" href="#sidebarAcademica" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarAcademica">
                    <i class="ri-book-line"></i> <span>Área Académica</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarAcademica">
                    <ul class="nav nav-sm flex-column">
                        @if (Auth::guard('web')->user()->can('areas.listar'))
                            <li class="nav-item">
                                <a href="{{ route('admin.areas.listar') }}" class="nav-link">
                                    Áreas
                                </a>
                            </li>
                        @endif
                        @if (Auth::guard('web')->user()->can('convenios.listar'))
                            <li class="nav-item">
                                <a href="{{ route('admin.convenios.listar') }}" class="nav-link">
                                    Convenios
                                </a>
                            </li>
                        @endif
                        @if (Auth::guard('web')->user()->can('posgrados.listar'))
                            <li class="nav-item">
                                <a href="{{ route('admin.posgrados.listar') }}" class="nav-link">
                                    Posgrados
                                </a>
                            </li>
                        @endif
                        @if (Auth::guard('web')->user()->can('ofertas.academicas.listar'))
                            <li class="nav-item">
                                <a href="{{ route('admin.ofertas.listar') }}" class="nav-link">
                                    Programas
                                </a>
                            </li>
                        @endif
                        @if (Auth::guard('web')->user()->can('tipos.programas.listar'))
                            <li class="nav-item">
                                <a href="{{ route('admin.tipos.listar') }}" class="nav-link">
                                    Tipos de Programas
                                </a>
                            </li>
                        @endif

                        @if (Auth::guard('web')->user()->can('modalidades.listar'))
                            <li class="nav-item">
                                <a href="{{ route('admin.modalidades.listar') }}" class="nav-link">
                                    Modalidades
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>

            <!-- Área Contable -->
            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarContable" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarContable">
                    <i class="ri-money-dollar-circle-line"></i> <span>Área Contable</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarContable">
                    <ul class="nav nav-sm flex-column">
                        @if (Auth::guard('web')->user()->can('planes.pagos.listar'))
                            <li class="nav-item">
                                <a href="{{ route('admin.planes.listar') }}" class="nav-link">
                                    Planes de Pago
                                </a>
                            </li>
                        @endif
                        @if (Auth::guard('web')->user()->can('conceptos.pagos.listar'))
                            <li class="nav-item">
                                <a href="{{ route('admin.conceptos.listar') }}" class="nav-link">
                                    Conceptos de Pago
                                </a>
                            </li>
                        @endif
                        {{-- Historial de Recibos --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.recibos.historial') }}">
                                <i class="ri-file-text-line"></i> Historial de Recibos
                            </a>
                        </li>

                        {{-- Área Contable --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.contabilidad.buscar') }}">
                                <i class="ri-calculator-line"></i> Área Contable
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
