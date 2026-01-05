@extends('admin.dashboard')
@section('admin')
    <style>
        .profile-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 12px 20px;
            border-bottom: 3px solid transparent;
        }

        .nav-tabs .nav-link.active {
            color: #4361ee;
            border-bottom: 3px solid #4361ee;
            background-color: transparent;
        }

        .nav-tabs .nav-link:hover:not(.active) {
            color: #4361ee;
            border-bottom: 3px solid rgba(67, 97, 238, 0.3);
        }

        .marketing-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            border: none;
        }

        .filter-card {
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }

        .stats-card {
            border-left: 4px solid;
            transition: all 0.3s ease;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chart-container {
            position: relative;
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .inscription-row:hover {
            background-color: rgba(67, 97, 238, 0.05);
            transform: translateX(2px);
        }

        .badge-status {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 600;
            border-radius: 6px;
        }

        .programa-badge {
            background-color: rgba(67, 97, 238, 0.1);
            color: #4361ee;
            border: 1px solid rgba(67, 97, 238, 0.2);
        }

        .sede-badge {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }

        /* Mejoras para los filtros */
        .filter-group {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #e9ecef;
        }

        .filter-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #495057;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-avatar {
                width: 120px;
                height: 120px;
            }

            .stats-card .d-flex {
                flex-direction: column;
                text-align: center;
            }

            .stats-card .flex-shrink-0 {
                margin-top: 10px;
            }

            .nav-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
            }

            .nav-tabs .nav-link {
                white-space: nowrap;
                padding: 10px 15px;
            }
        }

        /* Mejoras para tablas */
        .table-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        .table-actions {
            min-width: 100px;
        }

        /* Animaciones de carga */
        .loading-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border 0.75s linear infinite;
        }

        @keyframes spinner-border {
            to {
                transform: rotate(360deg);
            }
        }

        /* Estilos para la sección de ofertas activas */
        .oferta-card {
            transition: all 0.3s ease;
            border-left: 4px solid #4361ee;
            border-radius: 10px;
            overflow: hidden;
        }

        .oferta-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.15);
        }

        .qr-container {
            width: 100px;
            height: 100px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px;
            background: white;
        }

        .enlace-badge {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.85em;
            background: #f8f9fa;
            border: 1px dashed #dee2e6;
            border-radius: 6px;
            padding: 4px 8px;
        }

        .programa-tag {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75em;
            font-weight: 600;
            margin-right: 5px;
        }

        .sucursal-badge {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }

        .fase-badge {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }

        /* Responsive para tarjetas de oferta */
        @media (max-width: 768px) {
            .oferta-card .row>div {
                margin-bottom: 10px;
            }

            .qr-container {
                width: 80px;
                height: 80px;
            }
        }

        /* Añade al inicio de la sección de estilos */
        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Mejorar las tabs para muchas pestañas */
        .nav-tabs {
            flex-wrap: nowrap;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }

        .nav-tabs .nav-link {
            white-space: nowrap;
            min-width: 120px;
            text-align: center;
        }

        /* Mejorar responsive */
        @media (max-width: 768px) {
            .nav-tabs .nav-link span.d-none.d-md-inline {
                display: inline !important;
                font-size: 0.85em;
            }

            .nav-tabs .nav-link {
                min-width: 100px;
                padding: 10px 12px;
            }

            .stats-card h3 {
                font-size: 1.5rem;
            }

            .profile-avatar {
                width: 120px;
                height: 120px;
            }
        }

        /* Estilos para el botón de formulario PDF */
        .btn-outline-primary:hover .ri-file-text-line {
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Estilos responsivos para la tabla */
        @media (max-width: 768px) {
            .table-actions .btn {
                padding: 0.25rem 0.4rem;
                font-size: 0.75rem;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col-12">
                <div
                    class="page-title-box d-sm-flex align-items-center justify-content-between bg-primary bg-opacity-10 rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">
                            <i class="ri-user-line me-2"></i>Mi Perfil
                        </h4>
                        <p class="text-muted mb-0">Gestiona tu información personal y consulta tus actividades</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary">
                            <i class="ri-shield-user-line me-1"></i>
                            {{ auth()->user()->roles->first()->name ?? 'Usuario' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="row">
            <!-- Perfil Lateral -->
            <div class="col-xl-3 col-lg-3">
                <div class="card profile-card mb-4">
                    <div class="card-body text-center p-4">
                        <!-- Sección del Avatar - Actualizada -->
                        <div class="position-relative d-inline-block mb-3">
                            @php
                                // Si hay una ruta guardada en la base de datos, usamos asset() para crear la URL completa
                                $fotoUrl = auth()->user()->persona->fotografia
                                    ? asset(auth()->user()->persona->fotografia)
                                    : asset('backend/assets/images/users/user-dummy-img.jpg');
                            @endphp

                            <img id="profileAvatar" src="{{ $fotoUrl }}" class="profile-avatar" alt="Avatar"
                                onerror="this.src='{{ asset('backend/assets/images/users/user-dummy-img.jpg') }}'">

                            <div class="position-absolute bottom-0 end-0">
                                <!-- Botón para cambiar foto -->
                                <button class="btn btn-primary btn-sm rounded-circle shadow" data-bs-toggle="modal"
                                    data-bs-target="#uploadFotoModal" data-bs-tooltip="tooltip" title="Cambiar foto">
                                    <i class="ri-camera-line"></i>
                                </button>
                            </div>
                        </div>

                        <h4 id="profileName" class="mb-2">
                            {{ auth()->user()->persona->nombres ?? 'Usuario' }}
                            {{ auth()->user()->persona->apellido_paterno ?? '' }}
                        </h4>

                        <p id="profileCargo" class="text-primary fw-medium mb-3">
                            @php
                                $cargoPrincipal =
                                    auth()
                                        ->user()
                                        ->persona->trabajador->trabajadores_cargos->where('principal', 1)
                                        ->where('estado', 'Vigente')
                                        ->first()->cargo->nombre ?? 'Sin cargo asignado';
                            @endphp
                            {{ $cargoPrincipal }}
                        </p>

                        <!-- Badges de información -->
                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                            <span class="badge bg-info-subtle text-info">
                                <i class="ri-id-card-line me-1"></i>
                                {{ auth()->user()->persona->carnet ?? 'Sin carnet' }}
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary">
                                <i class="ri-genderless-line me-1"></i>
                                {{ auth()->user()->persona->sexo ?? '' }}
                            </span>
                            @if (auth()->user()->persona->fecha_nacimiento)
                                <span class="badge bg-warning-subtle text-warning">
                                    <i class="ri-cake-line me-1"></i>
                                    {{ \Carbon\Carbon::parse(auth()->user()->persona->fecha_nacimiento)->age }} años
                                </span>
                            @endif
                        </div>

                        <!-- Información de contacto -->
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-mail-line text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small class="text-muted d-block">Correo</small>
                                        <span class="fw-medium">{{ auth()->user()->persona->correo ?? 'Sin correo' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-phone-line text-success"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small class="text-muted d-block">Celular</small>
                                        <span
                                            class="fw-medium">{{ auth()->user()->persona->celular ?? 'Sin celular' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-map-pin-line text-info"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small class="text-muted d-block">Ubicación</small>
                                        <span class="fw-medium">
                                            {{ auth()->user()->persona->ciudad->nombre ?? '' }}
                                            @if (auth()->user()->persona->ciudad && auth()->user()->persona->ciudad->departamento)
                                                ({{ auth()->user()->persona->ciudad->departamento->nombre }})
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mini Dashboard Marketing -->
                @php
                    $tieneMarketing = false;
                    $cargosMarketingIds = [];

                    if (auth()->user()->persona->trabajador) {
                        $cargosMarketingIds = auth()
                            ->user()
                            ->persona->trabajador->trabajadores_cargos->whereIn('cargo_id', [2, 3, 6])
                            ->where('estado', 'Vigente')
                            ->pluck('id');

                        $tieneMarketing = $cargosMarketingIds->count() > 0;
                    }
                @endphp

                <!-- Información rápida -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-3">
                            <i class="ri-information-line me-2"></i> Información Rápida
                        </h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Cargos Activos:</span>
                            <span class="fw-medium">
                                {{ auth()->user()->persona->trabajador->trabajadores_cargos->where('estado', 'Vigente')->count() ?? 0 }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Estudios:</span>
                            <span class="fw-medium">
                                {{ auth()->user()->persona->estudios->count() ?? 0 }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Miembro desde:</span>
                            <span class="fw-medium">
                                {{ auth()->user()->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="col-xl-9 col-lg-9">
                <!-- Tabs de Navegación -->
                <div class="card">
                    <div class="card-body p-0">
                        <div class="border-bottom">
                            <ul class="nav nav-tabs nav-justified mb-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#personal" role="tab">
                                        <i class="ri-user-line me-1"></i>
                                        <span class="d-none d-md-inline">Información Personal</span>
                                        <span class="d-md-none">Personal</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#cargos" role="tab">
                                        <i class="ri-briefcase-line me-1"></i>
                                        <span class="d-none d-md-inline">Mis Cargos</span>
                                        <span class="d-md-none">Cargos</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#estudios" role="tab">
                                        <i class="ri-graduation-cap-line me-1"></i>
                                        <span class="d-none d-md-inline">Estudios</span>
                                        <span class="d-md-none">Estudios</span>
                                    </a>
                                </li>
                                @if ($tieneMarketing)
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#marketing" role="tab">
                                            <i class="ri-bar-chart-line me-1"></i>
                                            <span class="d-none d-md-inline">Marketing</span>
                                            <span class="d-md-none">Marketing</span>
                                        </a>
                                    </li>
                                @endif
                                @if ($tieneMarketing)
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#ofertas-activas" role="tab">
                                            <i class="ri-gift-line me-1"></i>
                                            <span class="d-none d-md-inline">Ofertas Activas</span>
                                            <span class="d-md-none">Ofertas</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="tab-content p-4">
                            <!-- Tab Personal -->
                            <div class="tab-pane active" id="personal" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">Nombres</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri-user-line"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="{{ auth()->user()->persona->nombres ?? '' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-medium">Apellido Paterno</label>
                                        <input type="text" class="form-control"
                                            value="{{ auth()->user()->persona->apellido_paterno ?? '' }}" readonly>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-medium">Apellido Materno</label>
                                        <input type="text" class="form-control"
                                            value="{{ auth()->user()->persona->apellido_materno ?? '' }}" readonly>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-medium">Sexo</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri-genderless-line"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="{{ auth()->user()->persona->sexo ?? '' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-medium">Estado Civil</label>
                                        <input type="text" class="form-control"
                                            value="{{ auth()->user()->persona->estado_civil ?? '' }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">Fecha de Nacimiento</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri-calendar-line"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="{{ auth()->user()->persona->fecha_nacimiento ? \Carbon\Carbon::parse(auth()->user()->persona->fecha_nacimiento)->format('d/m/Y') : '' }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">Correo Electrónico</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri-mail-line"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="{{ auth()->user()->persona->correo ?? '' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">Celular</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri-phone-line"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="{{ auth()->user()->persona->celular ?? '' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">Teléfono</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri-phone-fill"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="{{ auth()->user()->persona->telefono ?? '' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-medium">Dirección</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri-home-line"></i>
                                            </span>
                                            <textarea class="form-control" rows="2" readonly>{{ auth()->user()->persona->direccion ?? '' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-medium">Ciudad y Departamento</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri-map-pin-line"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="{{ auth()->user()->persona->ciudad->nombre ?? '' }} 
                                                      @if (auth()->user()->persona->ciudad && auth()->user()->persona->ciudad->departamento) - {{ auth()->user()->persona->ciudad->departamento->nombre }} @endif"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Cargos -->
                            <div class="tab-pane" id="cargos" role="tabpanel">
                                @if (auth()->user()->persona->trabajador && auth()->user()->persona->trabajador->trabajadores_cargos->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="25%">Cargo</th>
                                                    <th width="25%">Sucursal</th>
                                                    <th width="15%">Estado</th>
                                                    <th width="20%">Fechas</th>
                                                    <th width="15%" class="text-center">Principal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach (auth()->user()->persona->trabajador->trabajadores_cargos as $cargo)
                                                    <tr class="{{ $cargo->principal ? 'table-primary' : '' }}">
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-shrink-0">
                                                                    <div class="avatar-xs">
                                                                        <div
                                                                            class="avatar-title bg-primary bg-opacity-10 text-primary rounded">
                                                                            <i class="ri-briefcase-line"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <strong>{{ $cargo->cargo->nombre ?? 'N/A' }}</strong>
                                                                    @if (in_array($cargo->cargo_id, [2, 3, 6]))
                                                                        <span
                                                                            class="badge bg-info ms-1 badge-status">Marketing</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if ($cargo->sucursal)
                                                                <div>
                                                                    <strong>{{ $cargo->sucursal->nombre ?? 'N/A' }}</strong>
                                                                    @if ($cargo->sucursal->sede)
                                                                        <br>
                                                                        <small
                                                                            class="text-muted">{{ $cargo->sucursal->sede->nombre }}</small>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge {{ $cargo->estado == 'Vigente' ? 'bg-success' : 'bg-secondary' }} badge-status">
                                                                {{ $cargo->estado }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="small">
                                                                <div class="text-muted">Inicio:</div>
                                                                <strong>{{ $cargo->fecha_ingreso ? \Carbon\Carbon::parse($cargo->fecha_ingreso)->format('d/m/Y') : 'N/A' }}</strong>
                                                                @if ($cargo->fecha_termino)
                                                                    <div class="text-muted mt-1">Fin:</div>
                                                                    <strong>{{ \Carbon\Carbon::parse($cargo->fecha_termino)->format('d/m/Y') }}</strong>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($cargo->principal)
                                                                <span class="badge bg-primary badge-status">
                                                                    <i class="ri-star-fill"></i> Principal
                                                                </span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-light text-secondary rounded-circle">
                                                <i class="ri-briefcase-line display-4"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-muted">No tienes cargos asignados</h5>
                                        <p class="text-muted mb-0">Contacta con el administrador para asignarte un cargo.
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Tab Estudios -->
                            <div class="tab-pane" id="estudios" role="tabpanel">
                                @if (auth()->user()->persona->estudios->count() > 0)
                                    <div class="row">
                                        @foreach (auth()->user()->persona->estudios as $estudio)
                                            <div class="col-md-6 mb-3">
                                                <div class="card border">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-start">
                                                            <div class="flex-shrink-0">
                                                                <div class="avatar-md">
                                                                    <div
                                                                        class="avatar-title bg-primary bg-opacity-10 text-primary rounded">
                                                                        <i class="ri-graduation-cap-line display-5"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h5 class="card-title mb-1">
                                                                    {{ $estudio->grado_academico->nombre ?? 'N/A' }}</h5>
                                                                <p class="text-muted mb-2">
                                                                    <i class="ri-award-line me-1"></i>
                                                                    {{ $estudio->profesion->nombre ?? 'N/A' }}
                                                                </p>
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <i class="ri-building-line me-2 text-muted"></i>
                                                                    <span
                                                                        class="fw-medium">{{ $estudio->universidad->nombre ?? 'N/A' }}</span>
                                                                </div>
                                                                <span
                                                                    class="badge {{ $estudio->estado == 'Concluido' ? 'bg-success' : ($estudio->estado == 'En curso' ? 'bg-warning' : 'bg-danger') }} badge-status">
                                                                    {{ $estudio->estado }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-light text-secondary rounded-circle">
                                                <i class="ri-graduation-cap-line display-4"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-muted">No tienes estudios registrados</h5>
                                        <p class="text-muted mb-0">Registra tus estudios en la sección de administración.
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Tab Marketing -->
                            @if ($tieneMarketing)
                                <div class="tab-pane" id="marketing" role="tabpanel">
                                    <!-- Filtros Mejorados -->
                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-body">
                                            <h6 class="card-title mb-3">
                                                <i class="ri-filter-3-line me-2"></i> Filtros Avanzados
                                            </h6>
                                            <form id="marketingFilterForm">
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <div class="filter-group">
                                                            <label class="filter-label">Año</label>
                                                            <select name="year" id="marketingYear"
                                                                class="form-select">
                                                                @for ($i = date('Y'); $i >= 2020; $i--)
                                                                    <option value="{{ $i }}"
                                                                        {{ $i == date('Y') ? 'selected' : '' }}>
                                                                        {{ $i }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="filter-group">
                                                            <label class="filter-label">Mes</label>
                                                            <select name="month" id="marketingMonth"
                                                                class="form-select">
                                                                <option value="todos">Todos los meses</option>
                                                                @php
                                                                    $meses = [
                                                                        1 => 'Enero',
                                                                        2 => 'Febrero',
                                                                        3 => 'Marzo',
                                                                        4 => 'Abril',
                                                                        5 => 'Mayo',
                                                                        6 => 'Junio',
                                                                        7 => 'Julio',
                                                                        8 => 'Agosto',
                                                                        9 => 'Septiembre',
                                                                        10 => 'Octubre',
                                                                        11 => 'Noviembre',
                                                                        12 => 'Diciembre',
                                                                    ];
                                                                @endphp
                                                                @foreach ($meses as $key => $mes)
                                                                    <option value="{{ $key }}">
                                                                        {{ $mes }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="filter-group">
                                                            <label class="filter-label">Programa</label>
                                                            <select name="programa_id" id="marketingPrograma"
                                                                class="form-select">
                                                                <option value="">Todos los programas</option>
                                                                @foreach (\App\Models\Programa::orderBy('nombre')->get() as $programa)
                                                                    <option value="{{ $programa->id }}">
                                                                        {{ $programa->nombre }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="filter-group">
                                                            <label class="filter-label">Estado</label>
                                                            <select name="estado" id="marketingEstado"
                                                                class="form-select">
                                                                <option value="">Todos los estados</option>
                                                                <option value="Inscrito">Inscrito</option>
                                                                <option value="Pre-Inscrito">Pre-Inscrito</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-8">
                                                        <div class="filter-group">
                                                            <label class="filter-label">Buscar estudiante</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text">
                                                                    <i class="ri-search-line"></i>
                                                                </span>
                                                                <input type="text" name="search" id="marketingSearch"
                                                                    class="form-control"
                                                                    placeholder="Nombre, apellido o carnet...">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 d-flex align-items-end">
                                                        <div class="d-flex gap-2 w-100">
                                                            <button type="submit" id="applyMarketingFilter"
                                                                class="btn btn-primary flex-grow-1">
                                                                <i class="ri-filter-line me-1"></i> Aplicar
                                                            </button>
                                                            <button type="button" id="resetMarketingFilter"
                                                                class="btn btn-outline-secondary">
                                                                <i class="ri-refresh-line"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Estadísticas Rápidas -->
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <div class="card stats-card border-primary">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <h3 class="mb-0" id="totalInscripcionesCard">0</h3>
                                                            <p class="text-muted mb-0">Total Inscripciones</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                                                <i class="ri-user-add-line fs-24"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="card stats-card border-success">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <h3 class="mb-0" id="totalInscritosCard">0</h3>
                                                            <p class="text-muted mb-0">Inscritos</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <div class="stats-icon bg-success bg-opacity-10 text-success">
                                                                <i class="ri-checkbox-circle-line fs-24"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="card stats-card border-warning">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <h3 class="mb-0" id="totalPreInscritosCard">0</h3>
                                                            <p class="text-muted mb-0">Pre-Inscritos</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                                                <i class="ri-time-line fs-24"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="card stats-card border-info">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <h4 class="mb-0" id="periodoActualCard">-</h4>
                                                            <p class="text-muted mb-0">Período Actual</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <div class="stats-icon bg-info bg-opacity-10 text-info">
                                                                <i class="ri-calendar-line fs-24"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Gráficos -->
                                    <div class="row mb-4">
                                        <div class="col-md-8">
                                            <div class="card h-100">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h5 class="card-title mb-0">
                                                        <i class="ri-bar-chart-line me-1"></i>
                                                        <span id="chartTitle">Inscripciones por Mes
                                                            ({{ date('Y') }})</span>
                                                    </h5>
                                                    <div class="dropdown">
                                                        <button class="btn btn-soft-secondary btn-sm dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown">
                                                            <i class="ri-more-2-line"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="#" id="exportChart">
                                                                    <i class="ri-download-line me-2"></i> Exportar
                                                                </a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="chart-container" style="height: 300px;">
                                                        <canvas id="marketingChart"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card h-100">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">
                                                        <i class="ri-pie-chart-line me-1"></i> Top 5 Programas
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="chart-container" style="height: 300px;">
                                                        <canvas id="programasChart"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tabla de Inscripciones -->
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0">
                                                <i class="ri-list-check me-1"></i> Lista de Inscripciones
                                                <span id="tableCount" class="badge bg-primary ms-2">0</span>
                                            </h5>
                                            <div class="d-flex gap-2">
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown">
                                                        <i class="ri-download-line me-1"></i> Exportar
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#" id="exportPDF">
                                                                <i class="ri-file-pdf-line me-2"></i> PDF
                                                            </a></li>
                                                        <li><a class="dropdown-item" href="#" id="exportExcel">
                                                                <i class="ri-file-excel-line me-2"></i> Excel
                                                            </a></li>
                                                    </ul>
                                                </div>
                                                <button id="refreshMarketing" class="btn btn-outline-secondary btn-sm">
                                                    <i class="ri-refresh-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="marketingTableContainer">
                                                <div class="text-center py-5">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Cargando...</span>
                                                    </div>
                                                    <p class="mt-2 text-muted">Cargando datos de marketing...</p>
                                                </div>
                                            </div>
                                            <div id="marketingPagination" class="mt-3"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Tab Ofertas Activas -->
                            @if ($tieneMarketing)
                                <div class="tab-pane" id="ofertas-activas" role="tabpanel">
                                    <!-- Header de la sección -->
                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="card-title mb-1">
                                                        <i class="ri-gift-line me-2"></i>Ofertas Académicas Activas
                                                    </h5>
                                                    <p class="text-muted mb-0">Programas en fase de inscripciones - Genera
                                                        enlaces personalizados</p>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <button id="refreshOfertas" class="btn btn-outline-primary btn-sm">
                                                        <i class="ri-refresh-line"></i> Actualizar
                                                    </button>
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown">
                                                            <i class="ri-download-line me-1"></i> Exportar
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="#"
                                                                    id="exportOfertasCSV">CSV</a></li>
                                                            <li><a class="dropdown-item" href="#"
                                                                    id="exportOfertasPDF">PDF</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Filtros -->
                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="ri-search-line"></i>
                                                        </span>
                                                        <input type="text" class="form-control" id="searchOfertas"
                                                            placeholder="Buscar por código o nombre de programa...">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <select class="form-select" id="filterSucursal">
                                                        <option value="">Todas las sucursales</option>
                                                        @foreach (\App\Models\Sucursale::all() as $sucursal)
                                                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <button id="applyFilters" class="btn btn-primary w-100">
                                                        <i class="ri-filter-line me-1"></i> Filtrar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contador y estadísticas -->
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <div class="card stats-card border-info">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <h3 class="mb-0" id="totalOfertas">0</h3>
                                                            <p class="text-muted mb-0">Ofertas Activas</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <div class="stats-icon bg-info bg-opacity-10 text-info">
                                                                <i class="ri-gift-line fs-24"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card stats-card border-success">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <h3 class="mb-0" id="totalProgramas">0</h3>
                                                            <p class="text-muted mb-0">Programas Diferentes</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <div class="stats-icon bg-success bg-opacity-10 text-success">
                                                                <i class="ri-book-line fs-24"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card stats-card border-warning">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <h3 class="mb-0" id="totalSucursales">0</h3>
                                                            <p class="text-muted mb-0">Sucursales</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                                                <i class="ri-building-line fs-24"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card stats-card border-primary">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <h4 class="mb-0" id="cargoActual">-</h4>
                                                            <p class="text-muted mb-0">Cargo Principal</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                                                <i class="ri-briefcase-line fs-24"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Lista de ofertas -->
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0">
                                                <i class="ri-list-check me-1"></i> Lista de Ofertas
                                                <span id="ofertasCount" class="badge bg-primary ms-2">0</span>
                                            </h5>
                                            <div class="d-flex gap-2">
                                                <div class="input-group input-group-sm" style="width: 200px;">
                                                    <span class="input-group-text">Mostrar</span>
                                                    <select class="form-select" id="itemsPerPage">
                                                        <option value="10">10</option>
                                                        <option value="25">25</option>
                                                        <option value="50">50</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="ofertasContainer">
                                                <div class="text-center py-5">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Cargando...</span>
                                                    </div>
                                                    <p class="mt-2 text-muted">Cargando ofertas activas...</p>
                                                </div>
                                            </div>
                                            <div id="ofertasPagination" class="mt-3"></div>
                                        </div>
                                    </div>

                                    <!-- Modal para enlace y QR -->
                                    <div class="modal fade" id="enlaceModal" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Enlace Personalizado</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6 class="mb-3">Información del Programa</h6>
                                                            <div id="modalProgramaInfo"></div>
                                                        </div>
                                                        <div class="col-md-6 text-center">
                                                            <h6 class="mb-3">Código QR</h6>
                                                            <div id="modalQRCode" class="mb-3"></div>
                                                            <small class="text-muted">Escanea para acceder
                                                                directamente</small>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-medium">Enlace Personalizado</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="modalEnlace"
                                                                readonly>
                                                            <button class="btn btn-outline-primary" id="copyEnlaceBtn">
                                                                <i class="ri-file-copy-line"></i> Copiar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cerrar</button>
                                                    <a href="#" id="visitLinkBtn" target="_blank"
                                                        class="btn btn-primary">
                                                        <i class="ri-external-link-line me-1"></i> Visitar Enlace
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para subir foto -->
    <div class="modal fade" id="uploadFotoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Foto de Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadFotoForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="fotografia" class="form-label">Seleccionar imagen</label>
                            <input type="file" class="form-control" id="fotografia" name="fotografia"
                                accept="image/*" required>
                            <div class="form-text">
                                Formatos permitidos: JPG, PNG, GIF, WEBP. Tamaño máximo: 5MB
                            </div>
                        </div>

                        <div id="imagePreview" class="text-center mb-3" style="display: none;">
                            <img id="previewImage" src="#" alt="Vista previa" class="img-fluid rounded"
                                style="max-height: 200px;">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="submitFotoBtn">
                        <i class="ri-upload-cloud-line me-1"></i> Subir Foto
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para convertir Pre-Inscrito a Inscrito -->
    <div class="modal fade" id="convertirModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-user-add-line me-2"></i>Convertir a Inscrito
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Información del estudiante -->
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h6 class="mb-0">Información del Estudiante</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <strong>Estudiante:</strong>
                                        <span id="convertirEstudianteNombre" class="ms-2">-</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Carnet:</strong>
                                        <span id="convertirEstudianteCarnet" class="ms-2 badge bg-info">-</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <strong>Programa:</strong>
                                        <span id="convertirProgramaNombre" class="ms-2">-</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Estado Actual:</strong>
                                        <span class="badge bg-warning ms-2">Pre-Inscrito</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Advertencia -->
                    <div class="alert alert-info mb-4">
                        <i class="ri-information-line me-2"></i>
                        <strong>Importante:</strong> Al convertir a inscrito, se generarán automáticamente:
                        <ul class="mb-0 mt-2">
                            <li>Matriculaciones en todos los módulos del programa</li>
                            <li>Cuotas de pago según el plan seleccionado</li>
                            <li>El estado cambiará a "Inscrito" permanentemente</li>
                        </ul>
                    </div>

                    <!-- Selección de Plan de Pago -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="ri-money-dollar-circle-line me-2"></i>Seleccionar Plan de Pago
                            <span class="text-danger">*</span>
                        </h6>
                        <div id="planesPagoContainer">
                            <!-- Aquí se cargarán los planes de pago -->
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-4">
                        <label for="observacionConversion" class="form-label">
                            <i class="ri-chat-1-line me-2"></i>Observaciones (Opcional)
                        </label>
                        <textarea class="form-control" id="observacionConversion" rows="3"
                            placeholder="Agregar observaciones sobre la conversión..."></textarea>
                        <div class="form-text">Esta observación quedará registrada en la inscripción.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" id="confirmarConversionBtn">
                        <i class="ri-check-double-line me-1"></i>Confirmar Conversión
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Variables globales
            let marketingChart = null;
            let programasChart = null;
            let currentPage = 1;
            let filters = {
                year: new Date().getFullYear(),
                month: 'todos',
                programa_id: '',
                estado: '',
                search: ''
            };

            // Variables para ofertas activas
            let ofertasCurrentPage = 1;
            let ofertasFilters = {
                search: '',
                sucursal_id: '',
                per_page: 10
            };

            // Inicializar tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // ==============================
            // FUNCIONALIDADES DE MARKETING
            // ==============================

            // Cargar datos de marketing cuando se muestra el tab
            $(document).on('shown.bs.tab', 'a[href="#marketing"]', function() {
                if (!marketingChart) {
                    loadMarketingData();
                }
            });

            // Cargar mini dashboard si existe marketing
            @if ($tieneMarketing)
                loadMiniDashboard();
            @endif

            // Función para cargar mini dashboard
            function loadMiniDashboard() {
                $.ajax({
                    url: '{{ route('admin.profile.marketing.estadisticas') }}',
                    method: 'GET',
                    data: {
                        year: new Date().getFullYear(),
                        month: 'todos'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#miniTotalInscripciones').text(response.resumen.total);
                            $('#miniInscritos').text(response.resumen.inscritos);
                            $('#miniPreInscritos').text(response.resumen.pre_inscritos);
                        }
                    }
                });
            }

            // Aplicar filtros de marketing
            $('#applyMarketingFilter').on('click', function(e) {
                e.preventDefault();
                applyMarketingFilters();
            });

            // También aplicar filtros al enviar el formulario
            $('#marketingFilterForm').on('submit', function(e) {
                e.preventDefault();
                applyMarketingFilters();
            });

            // Función para aplicar filtros
            function applyMarketingFilters() {
                filters = {
                    year: $('#marketingYear').val(),
                    month: $('#marketingMonth').val(),
                    programa_id: $('#marketingPrograma').val(),
                    estado: $('#marketingEstado').val(),
                    search: $('#marketingSearch').val()
                };
                currentPage = 1;
                loadMarketingData();
            }

            // Limpiar filtros
            $('#resetMarketingFilter').on('click', function() {
                $('#marketingYear').val(new Date().getFullYear());
                $('#marketingMonth').val('todos');
                $('#marketingPrograma').val('');
                $('#marketingEstado').val('');
                $('#marketingSearch').val('');

                filters = {
                    year: new Date().getFullYear(),
                    month: 'todos',
                    programa_id: '',
                    estado: '',
                    search: ''
                };
                currentPage = 1;
                loadMarketingData();
            });

            // Refrescar datos
            $('#refreshMarketing').on('click', function() {
                loadMarketingData(currentPage);
            });

            // Función para cargar datos de marketing
            function loadMarketingData(page = 1) {
                currentPage = page;

                // Mostrar loading en la tabla
                $('#marketingTableContainer').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando datos de marketing...</p>
                </div>
            `);

                // Actualizar título del gráfico
                updateChartTitle();

                // Cargar estadísticas y gráficos
                $.ajax({
                    url: '{{ route('admin.profile.marketing.estadisticas') }}',
                    method: 'GET',
                    data: filters,
                    success: function(response) {
                        if (response.success) {
                            updateMarketingCharts(response.grafico, response.programas);
                            updateMarketingSummary(response.resumen);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error al cargar estadísticas:', xhr);
                        showToast('error', 'Error al cargar las estadísticas');
                    }
                });

                // Cargar tabla de inscripciones
                $.ajax({
                    url: '{{ route('admin.profile.marketing.inscripciones-filtradas') }}',
                    method: 'GET',
                    data: {
                        ...filters,
                        page: page
                    },
                    success: function(response) {
                        if (response.success) {
                            renderMarketingTable(response.inscripciones, response.pagination);
                        } else {
                            showToast('error', response.message || 'Error al cargar las inscripciones');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error al cargar inscripciones:', xhr);
                        showToast('error', 'Error al cargar las inscripciones');
                    }
                });
            }

            // Actualizar título del gráfico
            function updateChartTitle() {
                let title = 'Inscripciones por Mes';
                if (filters.month !== 'todos') {
                    const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                    ];
                    title += ` - ${meses[filters.month - 1]}`;
                }
                title += ` (${filters.year})`;
                $('#chartTitle').text(title);
            }

            // Actualizar gráficos
            function updateMarketingCharts(graficoData, programasData) {
                // Gráfico de barras - Inscripciones por mes
                const ctx = document.getElementById('marketingChart').getContext('2d');
                if (marketingChart) marketingChart.destroy();

                // Si el mes es "todos", mostrar todos los meses
                let meses = graficoData.meses || ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
                    'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'
                ];
                let inscritos = graficoData.inscritos || Array(12).fill(0);
                let pre_inscritos = graficoData.pre_inscritos || Array(12).fill(0);

                // Si solo se muestra un mes, ajustar datos
                if (filters.month !== 'todos' && meses.length === 1) {
                    meses = [meses[0]];
                    inscritos = [inscritos[0] || 0];
                    pre_inscritos = [pre_inscritos[0] || 0];
                }

                marketingChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: meses,
                        datasets: [{
                                label: 'Inscritos',
                                data: inscritos,
                                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                                borderColor: 'rgba(40, 167, 69, 1)',
                                borderWidth: 1,
                                borderRadius: 4
                            },
                            {
                                label: 'Pre-Inscritos',
                                data: pre_inscritos,
                                backgroundColor: 'rgba(255, 193, 7, 0.7)',
                                borderColor: 'rgba(255, 193, 7, 1)',
                                borderWidth: 1,
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                                titleColor: '#fff',
                                bodyColor: '#fff'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    precision: 0
                                },
                                grid: {
                                    drawBorder: false
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });

                // Gráfico de dona - Top programas
                const ctx2 = document.getElementById('programasChart').getContext('2d');
                if (programasChart) programasChart.destroy();

                // Si no hay datos, mostrar mensaje
                if (!programasData || programasData.length === 0) {
                    programasData = [{
                        programa_nombre: 'Sin datos',
                        total: 1
                    }];
                }

                const labels = programasData.map(p => p.programa_nombre);
                const data = programasData.map(p => p.total);

                // Colores para el gráfico
                const backgroundColors = [
                    '#4361ee', '#3a0ca3', '#7209b7', '#f72585', '#4cc9f0',
                    '#4895ef', '#560bad', '#b5179e', '#3f37c9', '#480ca8'
                ];

                programasChart = new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: backgroundColors.slice(0, data.length),
                            borderWidth: 1,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 12,
                                    padding: 15,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const value = context.raw;
                                        const percentage = Math.round((value / total) * 100);
                                        return `${context.label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Actualizar resumen
            function updateMarketingSummary(resumen) {
                $('#totalInscripcionesCard').text(resumen.total || 0);
                $('#totalInscritosCard').text(resumen.inscritos || 0);
                $('#totalPreInscritosCard').text(resumen.pre_inscritos || 0);

                let periodo = '';
                if (filters.month !== 'todos') {
                    const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                    ];
                    periodo = `${meses[filters.month - 1]} ${filters.year}`;
                } else {
                    periodo = `Año ${filters.year}`;
                }
                $('#periodoActualCard').text(periodo);
            }

            // Renderizar tabla de inscripciones
            function renderMarketingTable(inscripciones, pagination) {
                if (!inscripciones.data || inscripciones.data.length === 0) {
                    $('#marketingTableContainer').html(`
                    <div class="text-center py-5">
                        <i class="ri-emotion-sad-line display-4 text-muted"></i>
                        <h5 class="mt-3 text-muted">No se encontraron inscripciones</h5>
                        <p class="text-muted">Intenta con otros filtros de búsqueda</p>
                    </div>
                `);
                    $('#marketingPagination').html('');
                    $('#tableCount').text('0');
                    return;
                }

                $('#tableCount').text(pagination.total);

                let html = `
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Estudiante</th>
                                <th width="20%">Programa</th>
                                <th width="20%">Sede - Sucursal</th>
                                <th width="10%">Estado</th>
                                <th width="10%">Fecha</th>
                                <th width="10%" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

                inscripciones.data.forEach((inscripcion, index) => {
                    const estudiante = inscripcion.estudiante?.persona;
                    const programa = inscripcion.oferta_academica?.programa;
                    const sucursal = inscripcion.oferta_academica?.sucursal;
                    const sede = sucursal?.sede;
                    const fecha = new Date(inscripcion.fecha_registro);
                    const rowNumber = (pagination.current_page - 1) * pagination.per_page + index + 1;

                    // Generar botones de acción
                    let accionesHtml = `
                    <div class="d-flex flex-wrap gap-1 justify-content-center">
        <a href="/admin/profile/marketing/inscripcion/${inscripcion.id}/formulario-pdf" 
           class="btn btn-sm btn-outline-primary" 
           data-bs-toggle="tooltip"
           title="Generar Formulario PDF"
           target="_blank">
            <i class="ri-file-text-line me-1"></i>PDF
        </a>
        <a href="/admin/estudiantes/detalle/${inscripcion.estudiante_id}" 
           class="btn btn-sm btn-outline-info" 
           data-bs-toggle="tooltip"
           title="Ver detalles del estudiante">
            <i class="ri-eye-line"></i>
        </a>
                `;

                    // Para Pre-Inscritos, mantener el botón de convertir
                    // Para Pre-Inscritos, agregar el botón de conversión
                    if (inscripcion.estado === 'Pre-Inscrito') {
                        accionesHtml += `
        <button class="btn btn-sm btn-success btn-convertir-inscrito" 
                data-inscripcion-id="${inscripcion.id}"
                data-bs-toggle="tooltip"
                title="Convertir a Inscrito">
            <i class="ri-user-add-line"></i>
        </button>
    `;
                    } else {
                        // Para Inscritos, agregar botón para ver cuotas si quieres
                        accionesHtml += `
        <a href="/admin/inscripciones/${inscripcion.id}/cuotas" 
           class="btn btn-sm btn-outline-info" 
           data-bs-toggle="tooltip"
           title="Ver cuotas de pago">
            <i class="ri-money-dollar-circle-line"></i>
        </a>
    `;
                    }

                    accionesHtml += `</div>`;

                    html += `
                    <tr class="inscription-row">
                        <td class="fw-semibold text-muted">${rowNumber}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2">
                                    <div class="avatar-xs">
                                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                            <i class="ri-user-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">${estudiante?.nombres || 'N/A'} ${estudiante?.apellido_paterno || ''}</h6>
                                    <p class="text-muted mb-0 small">
                                        <i class="ri-id-card-line me-1"></i>
                                        ${estudiante?.carnet || 'N/A'}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge programa-badge">
                                <i class="ri-book-line me-1"></i>
                                ${programa?.nombre || 'N/A'}
                            </span>
                        </td>
                        <td>
                            <div>
                                <span class="badge sede-badge mb-1">
                                    <i class="ri-building-line me-1"></i>
                                    ${sede?.nombre || 'N/A'}
                                </span>
                                <br>
                                <small class="text-muted">${sucursal?.nombre || 'N/A'}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge ${inscripcion.estado === 'Inscrito' ? 'bg-success' : 'bg-warning'} badge-status">
                                ${inscripcion.estado}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">${fecha.toLocaleDateString('es-ES')}</small>
                            <br>
                            <small class="text-muted">${fecha.toLocaleTimeString('es-ES', {hour: '2-digit', minute:'2-digit'})}</small>
                        </td>
                        <td class="text-center">
                            ${accionesHtml}
                        </td>
                    </tr>
                `;
                });

                html += `
                    </tbody>
                </table>
            </div>
            `;

                $('#marketingTableContainer').html(html);
                renderMarketingPagination(pagination);

                // Re-inicializar tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
            }

            // Renderizar paginación
            function renderMarketingPagination(pagination) {
                if (pagination.last_page <= 1) {
                    $('#marketingPagination').html('');
                    return;
                }

                let html = `
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="text-muted">
                        Mostrando <span class="fw-medium">${pagination.from}</span> a 
                        <span class="fw-medium">${pagination.to}</span> de 
                        <span class="fw-medium">${pagination.total}</span> resultados
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
            `;

                // Botón anterior
                if (pagination.current_page > 1) {
                    html += `
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="${pagination.current_page - 1}">
                            <i class="ri-arrow-left-s-line"></i>
                        </a>
                    </li>
                `;
                } else {
                    html += `
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="ri-arrow-left-s-line"></i>
                        </span>
                    </li>
                `;
                }

                // Números de página
                let startPage = Math.max(1, pagination.current_page - 2);
                let endPage = Math.min(pagination.last_page, pagination.current_page + 2);

                for (let i = startPage; i <= endPage; i++) {
                    if (i === pagination.current_page) {
                        html += `
                        <li class="page-item active">
                            <span class="page-link">${i}</span>
                        </li>
                    `;
                    } else {
                        html += `
                        <li class="page-item">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `;
                    }
                }

                // Botón siguiente
                if (pagination.current_page < pagination.last_page) {
                    html += `
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="${pagination.current_page + 1}">
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                    </li>
                `;
                } else {
                    html += `
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </li>
                `;
                }

                html += `
                        </ul>
                    </nav>
                </div>
            `;

                $('#marketingPagination').html(html);
            }

            // ==============================
            // MODAL PARA CONVERTIR PRE-INSCRITO
            // ==============================

            // Modal para convertir pre-inscrito a inscrito
            // Evento para abrir el modal de conversión
            $(document).on('click', '.btn-convertir-inscrito', function() {
                const inscripcionId = $(this).data('inscripcion-id');
                const ofertaId = $(this).data('oferta-id');
                const estudianteNombre = $(this).data('estudiante-nombre');
                const estudianteCarnet = $(this).data('estudiante-carnet');
                const programaNombre = $(this).data('programa-nombre');

                // Guardar datos en el modal
                $('#convertirModal').data('inscripcion-id', inscripcionId);
                $('#convertirModal').data('oferta-id', ofertaId);

                // Mostrar información básica
                $('#convertirEstudianteNombre').text(estudianteNombre);
                $('#convertirEstudianteCarnet').text(estudianteCarnet);
                $('#convertirProgramaNombre').text(programaNombre);

                // Resetear estado del modal
                $('#confirmarConversionBtn').prop('disabled', false)
                    .html('<i class="ri-check-double-line me-1"></i> Confirmar Conversión');

                // Cargar planes de pago
                cargarPlanesPagoOferta(ofertaId);

                // Mostrar modal
                $('#convertirModal').modal('show');
            });

            // Función mejorada para cargar planes de pago
            function cargarPlanesPagoOferta(ofertaId) {
                $('#planesPagoContainer').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2 text-muted">Cargando planes de pago disponibles...</p>
        </div>
    `);

                $.ajax({
                    url: '/admin/profile/marketing/oferta/' + ofertaId + '/planes-pago',
                    method: 'GET',
                    success: function(response) {
                        if (response.success && response.planes.length > 0) {
                            let html = '';

                            response.planes.forEach((plan, index) => {
                                let conceptosHtml = '';
                                let totalMonto = 0;
                                let totalCuotas = 0;

                                // En la función cargarPlanesPagoOferta, actualiza cómo se muestran los montos:
                                plan.conceptos.forEach(concepto => {
                                    // Parsear montos como enteros para mostrar sin decimales
                                    const totalConcepto = Math.round(parseFloat(concepto
                                        .pago_bs));
                                    const montoPorCuota = Math.round(parseFloat(concepto
                                        .monto_por_cuota));

                                    // Calcular cuotas reales (sin decimales)
                                    const montoBase = Math.floor(totalConcepto /
                                        concepto.n_cuotas);
                                    const diferencia = totalConcepto - (montoBase * (
                                        concepto.n_cuotas - 1));

                                    conceptosHtml += `
        <tr>
            <td>${concepto.concepto_nombre}</td>
            <td class="text-center">${concepto.n_cuotas}</td>
            <td class="text-end">${totalConcepto.toLocaleString('es-BO')} Bs</td>
            <td class="text-end">
                ${concepto.n_cuotas === 1 ? 
                    totalConcepto.toLocaleString('es-BO') : 
                    `${montoBase.toLocaleString('es-BO')} Bs (primeras ${concepto.n_cuotas - 1} cuotas)<br>
                                            <small class="text-info">Última cuota: ${(montoBase + diferencia).toLocaleString('es-BO')} Bs</small>`
                }
            </td>
        </tr>
    `;
                                    totalMonto += totalConcepto;

                                    // Verificar que no hay decimales
                                    if (totalConcepto % 1 !== 0) {
                                        console.warn(
                                            `El concepto ${concepto.concepto_nombre} tiene decimales: ${totalConcepto}`
                                        );
                                    }
                                });

                                html += `
                        <div class="card mb-3 plan-pago-card ${index === 0 ? 'border-primary' : ''}" data-plan-id="${plan.id}">
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input plan-radio" 
                                           type="radio" 
                                           name="plan_pago" 
                                           id="plan_${plan.id}" 
                                           value="${plan.id}"
                                           ${index === 0 ? 'checked' : ''}>
                                    <label class="form-check-label fw-bold" for="plan_${plan.id}">
                                        ${plan.nombre}
                                    </label>
                                    <span class="badge bg-primary float-end">
                                        Total: ${totalMonto.toFixed(2)} Bs
                                        ${totalCuotas > 1 ? `(${totalCuotas} cuotas)` : ''}
                                    </span>
                                </div>
                                
                                <div class="mt-3">
                                    <h6 class="mb-2 text-muted">Detalles de Cuotas:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Concepto</th>
                                                    <th class="text-center">Cuotas</th>
                                                    <th class="text-end">Total</th>
                                                    <th class="text-end">Monto/Cuota</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${conceptosHtml}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                            });

                            $('#planesPagoContainer').html(html);

                            // Evento para cambiar estilo al seleccionar plan
                            $('.plan-radio').on('change', function() {
                                $('.plan-pago-card').removeClass('border-primary');
                                $(this).closest('.plan-pago-card').addClass('border-primary');
                            });
                        } else {
                            $('#planesPagoContainer').html(`
                    <div class="alert alert-warning">
                        <i class="ri-alert-line me-2"></i> 
                        No hay planes de pago configurados para esta oferta.
                        <div class="mt-2">
                            <small>Contacte al administrador para configurar los planes de pago.</small>
                        </div>
                    </div>
                `);

                            // Deshabilitar botón de confirmación
                            $('#confirmarConversionBtn').prop('disabled', true)
                                .html('<i class="ri-forbid-line me-1"></i> No hay planes disponibles');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        $('#planesPagoContainer').html(`
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line me-2"></i> 
                    Error al cargar los planes de pago.
                    <div class="mt-2">
                        <small>${xhr.responseJSON?.message || 'Error de conexión'}</small>
                    </div>
                </div>
            `);

                        // Deshabilitar botón de confirmación
                        $('#confirmarConversionBtn').prop('disabled', true)
                            .html('<i class="ri-forbid-line me-1"></i> Error al cargar planes');
                    }
                });
            }

            // Cambiar estilo al seleccionar plan
            $(document).on('click', '.plan-radio', function() {
                $('.plan-pago-card').removeClass('border-primary');
                $(this).closest('.plan-pago-card').addClass('border-primary');
            });

            // Confirmar conversión con mejor manejo de errores
            $('#confirmarConversionBtn').on('click', function() {
                const btn = $(this);
                const originalText = btn.html();
                const inscripcionId = $('#convertirModal').data('inscripcion-id');
                const planPagoId = $('input[name="plan_pago"]:checked').val();
                const observacion = $('#observacionConversion').val();

                if (!planPagoId) {
                    showToast('error', 'Por favor selecciona un plan de pago para continuar');
                    return;
                }

                // Deshabilitar botón y mostrar loading
                btn.prop('disabled', true).html(`
        <span class="spinner-border spinner-border-sm me-1"></span>
        Generando inscripción, matriculaciones y cuotas...
    `);

                $.ajax({
                    url: '/admin/profile/marketing/convertir-inscrito',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        inscripcion_id: inscripcionId,
                        plan_pago_id: planPagoId,
                        observacion: observacion
                    },
                    success: function(response) {
                        if (response.success) {
                            showToast('success', response.message);

                            // Cerrar modal después de un breve retraso
                            setTimeout(() => {
                                $('#convertirModal').modal('hide');

                                // Recargar la tabla de inscripciones
                                loadMarketingData(currentPage);

                                // Resetear formulario
                                $('input[name="plan_pago"]').prop('checked', false);
                                $('#observacionConversion').val('');
                                $('.plan-pago-card').removeClass('border-primary');
                            }, 1500);
                        } else {
                            showToast('error', response.message ||
                                'Error al convertir la inscripción');
                            btn.prop('disabled', false).html(originalText);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Error al procesar la solicitud';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                                '<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showToast('error', errorMessage);
                        btn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // ==============================
            // FUNCIONALIDADES DE OFERTAS ACTIVAS
            // ==============================

            // Cargar ofertas activas cuando se muestra el tab
            $(document).on('shown.bs.tab', 'a[href="#ofertas-activas"]', function() {
                loadOfertasActivas();
            });

            // Refrescar ofertas
            $('#refreshOfertas').on('click', function() {
                loadOfertasActivas();
            });

            // Aplicar filtros
            $('#applyFilters').on('click', function() {
                ofertasFilters.search = $('#searchOfertas').val();
                ofertasFilters.sucursal_id = $('#filterSucursal').val();
                ofertasFilters.per_page = $('#itemsPerPage').val();
                ofertasCurrentPage = 1;
                loadOfertasActivas();
            });

            // Cambiar items por página
            $('#itemsPerPage').on('change', function() {
                ofertasFilters.per_page = $(this).val();
                ofertasCurrentPage = 1;
                loadOfertasActivas();
            });

            // Buscar al presionar Enter
            $('#searchOfertas').on('keypress', function(e) {
                if (e.which === 13) {
                    $('#applyFilters').click();
                }
            });

            // Función para cargar ofertas activas
            function loadOfertasActivas() {
                showLoading('#ofertasContainer');

                $.ajax({
                    url: '{{ route('admin.profile.marketing.ofertas-activas') }}',
                    method: 'GET',
                    data: {
                        ...ofertasFilters,
                        page: ofertasCurrentPage
                    },
                    success: function(response) {
                        console.log('Respuesta exitosa:', response);
                        if (response.success) {
                            renderOfertasTable(response.ofertas);
                            updateOfertasStats(response);
                            renderOfertasPagination(response.ofertas);
                            updateCargoInfo(response.cargo_principal);
                        } else {
                            console.error('Error en respuesta:', response);
                            showToast('error', response.message || 'Error al cargar las ofertas');
                            $('#ofertasContainer').html(`
                            <div class="alert alert-danger">
                                <h5>Error: ${response.message || 'Desconocido'}</h5>
                                <p>${response.debug ? JSON.stringify(response.debug) : ''}</p>
                            </div>
                        `);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error AJAX:', xhr, status, error);
                        showToast('error', 'Error de conexión: ' + error);
                        $('#ofertasContainer').html(`
                        <div class="alert alert-danger">
                            <h5>Error ${xhr.status}: ${xhr.statusText}</h5>
                            <p>${xhr.responseText ? xhr.responseText.substring(0, 200) : 'Sin respuesta del servidor'}</p>
                            <p><strong>URL:</strong> {{ route('admin.profile.marketing.ofertas-activas') }}</p>
                        </div>
                    `);
                    }
                });
            }

            // Renderizar tabla de ofertas
            function renderOfertasTable(ofertas) {
                let html = '';

                if (ofertas.data.length === 0) {
                    html = `
                    <div class="text-center py-5">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-light text-secondary rounded-circle">
                                <i class="ri-search-line display-4"></i>
                            </div>
                        </div>
                        <h5 class="text-muted">No se encontraron ofertas activas</h5>
                        <p class="text-muted mb-0">No hay ofertas académicas en fase de inscripciones en este momento.</p>
                    </div>
                `;
                } else {
                    html = `
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="20%">Código</th>
                                    <th width="25%">Programa</th>
                                    <th width="15%">Sucursal</th>
                                    <th width="15%">Modalidad</th>
                                    <th width="15%">Fechas</th>
                                    <th width="10%" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                    ofertas.data.forEach(oferta => {
                        const fechas = `
                        Inscripciones:<br>
                        <small>${oferta.fecha_inicio_formateada || 'Sin fecha'} - ${oferta.fecha_fin_formateada || 'Sin fecha'}</small>
                    `;

                        html += `
                        <tr class="oferta-card">
                            <td>
                                <strong class="text-primary">${oferta.codigo || 'N/A'}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <strong>${oferta.programa_nombre || 'Sin programa'}</strong>
                                        <br>
                                        <small class="text-muted">${oferta.version ? 'v' + oferta.version : ''} ${oferta.grupo ? 'Grupo ' + oferta.grupo : ''}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge sucursal-badge programa-tag">
                                    ${oferta.sucursal_nombre || 'Sin sucursal'}
                                </span>
                                ${oferta.sede_nombre ? '<br><small class="text-muted">' + oferta.sede_nombre + '</small>' : ''}
                            </td>
                            <td>
                                <span class="badge bg-light text-dark programa-tag">
                                    ${oferta.modalidad_nombre || 'Sin modalidad'}
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    ${fechas}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <button class="btn btn-sm btn-outline-primary btn-ver-enlace" 
                                            data-oferta-id="${oferta.id}"
                                            data-enlace="${oferta.enlace_personalizado || '#'}"
                                            data-programa="${oferta.programa_nombre || 'Programa'}"
                                            data-sucursal="${oferta.sucursal_nombre || 'Sin sucursal'}"
                                            data-modalidad="${oferta.modalidad_nombre || 'Sin modalidad'}"
                                            data-qr="${oferta.enlace_qr || ''}">
                                        <i class="ri-link"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    });

                    html += `
                        </tbody>
                    </table>
                </div>
                `;
                }

                $('#ofertasContainer').html(html);
                attachEnlaceEvents();
            }

            // Actualizar estadísticas de ofertas
            function updateOfertasStats(response) {
                $('#totalOfertas').text(response.ofertas.total || 0);
                $('#ofertasCount').text(response.ofertas.total || 0);

                // Contar programas únicos
                const programasUnicos = [...new Set(response.ofertas.data.map(o => o.programa_id))];
                $('#totalProgramas').text(programasUnicos.length);

                // Contar sucursales únicas
                const sucursalesUnicas = [...new Set(response.ofertas.data.map(o => o.sucursale_id))];
                $('#totalSucursales').text(sucursalesUnicas.length);
            }

            // Actualizar información del cargo
            function updateCargoInfo(cargo) {
                $('#cargoActual').text(cargo.cargo_nombre || '-');
            }

            // Renderizar paginación
            function renderOfertasPagination(ofertas) {
                if (ofertas.last_page <= 1) {
                    $('#ofertasPagination').html('');
                    return;
                }

                let html = `
                <nav>
                    <ul class="pagination justify-content-center">
            `;

                // Botón anterior
                html += `
                <li class="page-item ${ofertas.current_page === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${ofertas.current_page - 1}">
                        <i class="ri-arrow-left-s-line"></i>
                    </a>
                </li>
            `;

                // Números de página
                for (let i = 1; i <= ofertas.last_page; i++) {
                    if (i === 1 || i === ofertas.last_page || (i >= ofertas.current_page - 2 && i <= ofertas
                            .current_page + 2)) {
                        html += `
                        <li class="page-item ${ofertas.current_page === i ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `;
                    } else if (i === ofertas.current_page - 3 || i === ofertas.current_page + 3) {
                        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                }

                // Botón siguiente
                html += `
                <li class="page-item ${ofertas.current_page === ofertas.last_page ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${ofertas.current_page + 1}">
                        <i class="ri-arrow-right-s-line"></i>
                    </a>
                </li>
            `;

                html += `
                    </ul>
                </nav>
            `;

                $('#ofertasPagination').html(html);

                // Eventos de paginación
                $('#ofertasPagination .page-link').on('click', function(e) {
                    e.preventDefault();
                    const page = $(this).data('page');
                    if (page && page !== ofertasCurrentPage) {
                        ofertasCurrentPage = page;
                        loadOfertasActivas();
                    }
                });
            }

            // Adjuntar eventos a los botones de enlace
            function attachEnlaceEvents() {
                $('.btn-ver-enlace').on('click', function() {
                    const enlace = $(this).data('enlace');
                    const programa = $(this).data('programa');
                    const sucursal = $(this).data('sucursal');
                    const modalidad = $(this).data('modalidad');
                    const qr = $(this).data('qr');

                    $('#modalProgramaInfo').html(`
                    <div class="mb-2">
                        <strong>Programa:</strong> ${programa}
                    </div>
                    <div class="mb-2">
                        <strong>Sucursal:</strong> ${sucursal}
                    </div>
                    <div class="mb-2">
                        <strong>Modalidad:</strong> ${modalidad}
                    </div>
                    <div class="mb-2">
                        <strong>Enlace único personalizado para tu cargo</strong>
                    </div>
                `);

                    if (qr) {
                        $('#modalQRCode').html(
                            `<img src="${qr}" alt="QR Code" class="img-fluid" style="max-width: 150px;">`
                        );
                    } else {
                        $('#modalQRCode').html('<div class="text-muted">No se pudo generar QR</div>');
                    }

                    $('#modalEnlace').val(enlace);
                    $('#visitLinkBtn').attr('href', enlace);

                    $('#enlaceModal').modal('show');
                });

                // Copiar enlace al portapapeles
                $('#copyEnlaceBtn').on('click', function() {
                    const enlaceInput = $('#modalEnlace');
                    enlaceInput.select();
                    document.execCommand('copy');

                    // Cambiar temporalmente el texto del botón
                    const originalText = $(this).html();
                    $(this).html('<i class="ri-check-line"></i> Copiado');

                    setTimeout(() => {
                        $(this).html(originalText);
                    }, 2000);

                    showToast('success', 'Enlace copiado al portapapeles');
                });
            }

            // ==============================
            // FUNCIONES AUXILIARES COMUNES
            // ==============================

            // Manejar clic en paginación de marketing
            $(document).on('click', '#marketingPagination .page-link', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (page) {
                    loadMarketingData(page);
                }
            });

            // Función para mostrar toast
            function showToast(type, message) {
                // Usar Toastr si está disponible
                if (typeof toastr !== 'undefined') {
                    toastr[type](message);
                }
                // Usar SweetAlert si está disponible
                else if (typeof Swal !== 'undefined') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });

                    Toast.fire({
                        icon: type,
                        title: message
                    });
                }
                // Fallback a alert nativo
                else {
                    alert(`${type.toUpperCase()}: ${message}`);
                }
            }

            // Funciones auxiliares
            function showLoading(selector) {
                $(selector).html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando ofertas activas...</p>
                </div>
            `);
            }

            function hideLoading(selector) {
                // Se maneja en cada función específica
            }

            // Exportar a PDF
            $('#exportPDF').on('click', function(e) {
                e.preventDefault();
                alert('Funcionalidad de exportar a PDF en desarrollo');
            });

            // Exportar a Excel
            $('#exportExcel').on('click', function(e) {
                e.preventDefault();
                alert('Funcionalidad de exportar a Excel en desarrollo');
            });

            // Exportar gráfico
            $('#exportChart').on('click', function(e) {
                e.preventDefault();
                alert('Funcionalidad de exportar gráfico en desarrollo');
            });

            // Exportar ofertas
            $('#exportOfertasCSV').on('click', function(e) {
                e.preventDefault();
                exportOfertas('csv');
            });

            $('#exportOfertasPDF').on('click', function(e) {
                e.preventDefault();
                exportOfertas('pdf');
            });

            function exportOfertas(format) {
                const params = new URLSearchParams({
                    ...ofertasFilters,
                    format: format,
                    _token: '{{ csrf_token() }}'
                });

                window.open('{{ route('admin.profile.marketing.ofertas-activas') }}?' + params.toString(),
                    '_blank');
            }

            // ==============================
            // SUBIDA DE FOTOGRAFÍA
            // ==============================

            // Vista previa de la imagen antes de subir
            $('#fotografia').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').show();
                        $('#previewImage').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Subir foto
            $('#submitFotoBtn').on('click', function() {
                const formData = new FormData($('#uploadFotoForm')[0]);
                const btn = $(this);
                const originalText = btn.html();

                // Deshabilitar botón y mostrar loading
                btn.prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                Subiendo...
            `);

                $.ajax({
                    url: '{{ route('admin.profile.upload-foto') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            // Actualizar imagen en la vista
                            $('#profileAvatar').attr('src', response.foto_url);

                            // Mostrar notificación de éxito
                            showToast('success', response.message);

                            // Cerrar modal
                            $('#uploadFotoModal').modal('hide');

                            // Resetear formulario
                            $('#uploadFotoForm')[0].reset();
                            $('#imagePreview').hide();
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Error al subir la imagen';

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = xhr.responseJSON.errors.fotografia ?
                                xhr.responseJSON.errors.fotografia[0] :
                                errorMessage;
                        }

                        showToast('error', errorMessage);
                    },
                    complete: function() {
                        // Restaurar botón
                        btn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Cerrar modal al hacer click fuera
            $('#uploadFotoModal').on('hidden.bs.modal', function() {
                $('#uploadFotoForm')[0].reset();
                $('#imagePreview').hide();
            });
        });
    </script>
@endpush
