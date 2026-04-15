@extends('admin.dashboard')

@push('style')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap');

        :root {
            --inscr-primary: #0d9488;
            --inscr-primary-light: #e6fffa;
            --inscr-primary-dark: #0f766e;
            --inscr-accent: #f59e0b;
            --inscr-surface: #f8fafc;
            --inscr-border: #e2e8f0;
            --inscr-text: #1e293b;
            --inscr-text-muted: #64748b;
            --inscr-success: #10b981;
            --inscr-warning: #f59e0b;
            --inscr-danger: #ef4444;
            --inscr-info: #3b82f6;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
        }

        .inscr-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--inscr-text);
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .inscripcion-row {
            transition: all 0.3s ease;
        }

        .hidden-row {
            display: none !important;
        }

        /* Header */
        .inscr-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
            padding: 24px 28px;
            background: linear-gradient(135deg, var(--inscr-primary) 0%, var(--inscr-primary-dark) 100%);
            border-radius: var(--radius-lg);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .inscr-header::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -5%;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .inscr-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
            color: white;
        }

        .inscr-header .breadcrumb {
            position: relative;
            z-index: 1;
        }

        .inscr-header .breadcrumb a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
        }

        .inscr-header .breadcrumb a:hover {
            color: white;
        }

        .inscr-header .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.9);
        }

        .inscr-header .btn {
            position: relative;
            z-index: 1;
            border-radius: var(--radius-sm);
            font-weight: 500;
            padding: 8px 16px;
        }

        .inscr-header .btn-light {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .inscr-header .btn-light:hover {
            background: rgba(255, 255, 255, 0.25);
            color: white;
        }

        .inscr-header .btn-secondary {
            background: rgba(0, 0, 0, 0.2);
            border: none;
            color: white;
        }

        .inscr-header .btn-secondary:hover {
            background: rgba(0, 0, 0, 0.3);
            color: white;
        }

        /* Cards */
        .inscr-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--inscr-border);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .inscr-card:hover {
            box-shadow: var(--shadow-md);
        }

        .inscr-card-header {
            padding: 16px 20px;
            border-bottom: 1px dashed var(--inscr-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--inscr-surface);
        }

        .inscr-card-header h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            margin: 0;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .inscr-card-header h6 i {
            color: var(--inscr-primary);
        }

        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--inscr-border);
            transition: all 0.2s ease;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .stat-card .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--inscr-text-muted);
        }

        .stat-card .stat-value {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--inscr-text);
        }

        .stat-card .stat-primary .stat-icon {
            background: var(--inscr-primary-light);
            color: var(--inscr-primary);
        }

        .stat-card .stat-warning .stat-icon {
            background: #fef3c7;
            color: var(--inscr-warning);
        }

        .stat-card .stat-success .stat-icon {
            background: #d1fae5;
            color: var(--inscr-success);
        }

        .stat-card .stat-info .stat-icon {
            background: #dbeafe;
            color: var(--inscr-info);
        }

        /* Filtros */
        .filtro-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filtro-group .form-group {
            flex: 1;
            min-width: 180px;
        }

        .filtro-group .form-label {
            font-weight: 500;
            font-size: 0.8rem;
            color: var(--inscr-text);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filtro-group .form-label i {
            color: var(--inscr-primary);
        }

        .filtro-group .form-select,
        .filtro-group .form-control {
            border: 1px solid var(--inscr-border);
            border-radius: var(--radius-sm);
            padding: 8px 12px;
            font-size: 0.85rem;
            transition: all 0.15s ease;
        }

        .filtro-group .form-select:focus,
        .filtro-group .form-control:focus {
            border-color: var(--inscr-primary);
            box-shadow: 0 0 0 3px var(--inscr-primary-light);
            outline: none;
        }

        .filtro-group .btn {
            border-radius: var(--radius-sm);
            font-weight: 500;
            font-size: 0.85rem;
            padding: 8px 16px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .filtro-group .btn-primary {
            background: var(--inscr-primary);
            border-color: var(--inscr-primary);
        }

        .filtro-group .btn-primary:hover {
            background: var(--inscr-primary-dark);
            border-color: var(--inscr-primary-dark);
        }

        .filtro-group .btn-light {
            background: var(--inscr-surface);
            border: 1px solid var(--inscr-border);
            color: var(--inscr-text-muted);
        }

        .filtro-group .btn-light:hover {
            background: var(--inscr-border);
            color: var(--inscr-text);
        }

        /* Tabla */
        .inscr-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .inscr-table thead th {
            font-family: 'Outfit', sans-serif;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--inscr-text-muted);
            background: var(--inscr-surface);
            padding: 12px 16px;
            border-bottom: 2px solid var(--inscr-border);
            text-align: center;
        }

        .inscr-table thead th:first-child {
            border-radius: var(--radius-sm) 0 0 0;
        }

        .inscr-table thead th:last-child {
            border-radius: 0 var(--radius-sm) 0 0;
        }

        .inscr-table tbody td {
            padding: 14px 16px;
            font-size: 0.85rem;
            color: var(--inscr-text);
            border-bottom: 1px solid var(--inscr-border);
            vertical-align: middle;
        }

        .inscr-table tbody tr:hover {
            background: var(--inscr-surface);
        }

        .inscr-table tbody tr:last-child td {
            border-bottom: none;
        }

        .inscr-table .badge {
            font-weight: 600;
            font-size: 0.7rem;
            padding: 5px 10px;
            border-radius: 50px;
        }

        .inscr-table .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .inscr-table .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .inscr-table .btn {
            border-radius: var(--radius-sm);
            padding: 6px 10px;
            font-size: 0.8rem;
        }

        .inscr-table .btn-warning {
            background: #fef3c7;
            border-color: #fef3c7;
            color: #92400e;
        }

        .inscr-table .btn-warning:hover {
            background: #fde68a;
            border-color: #fde68a;
        }

        .inscr-table .btn-info {
            background: #dbeafe;
            border-color: #dbeafe;
            color: #1e40af;
        }

        .inscr-table .btn-info:hover {
            background: #bfdbfe;
            border-color: #bfdbfe;
        }

        .inscr-table .btn-secondary {
            background: #f1f5f9;
            border-color: #f1f5f9;
            color: #475569;
        }

        .inscr-table .btn-secondary:hover {
            background: #e2e8f0;
            border-color: #e2e8f0;
        }

        @media (max-width: 767.98px) {
            .inscr-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .filtro-group {
                flex-direction: column;
            }

            .filtro-group .form-group {
                width: 100%;
            }

            .inscr-table {
                font-size: 0.75rem;
            }

            .inscr-table thead th,
            .inscr-table tbody td {
                padding: 8px 10px;
            }
        }
    </style>
@endpush

@section('admin')
    <div class="inscr-page">
        {{-- Header --}}
        <div class="inscr-header">
            <div>
                <ol class="breadcrumb mb-2" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.ofertas.listar') }}">Ofertas</a></li>
                    <li class="breadcrumb-item active">Inscritos</li>
                </ol>
                <h1><i class="ri-user-follow-line me-2"></i>Gestión de Inscripciones</h1>
                <p style="opacity: 0.85; font-size: 0.9rem; margin: 4px 0 0 0;">{{ $oferta->programa?->nombre ?? 'N/A' }} -
                    {{ $oferta->codigo }}</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-light" onclick="window.print()">
                    <i class="ri-printer-line me-1"></i> Imprimir
                </button>
                <a href="{{ route('admin.ofertas.listar') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line me-1"></i> Volver
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="row mb-4 g-3">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card stat-primary p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon">
                            <i class="ri-user-line"></i>
                        </div>
                        <div>
                            <div class="stat-label">Total Inscritos</div>
                            <div class="stat-value">{{ $inscripciones->where('estado', 'Inscrito')->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card stat-warning p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon">
                            <i class="ri-time-line"></i>
                        </div>
                        <div>
                            <div class="stat-label">Pre-Inscritos</div>
                            <div class="stat-value">{{ $inscripciones->where('estado', 'Pre-Inscrito')->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card stat-success p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon">
                            <i class="ri-check-double-line"></i>
                        </div>
                        <div>
                            <div class="stat-label">Total Estudiantes</div>
                            <div class="stat-value">{{ $inscripciones->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card stat-info p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon">
                            <i class="ri-calendar-line"></i>
                        </div>
                        <div>
                            <div class="stat-label">Último Registro</div>
                            <div class="stat-value" style="font-size: 1rem;">
                                @if ($inscripciones->count() > 0)
                                    {{ $inscripciones->sortByDesc('fecha_registro')->first()->fecha_registro?->format('d/m/Y') ?? 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="inscr-card mb-4">
            <div class="inscr-card-header">
                <h6><i class="ri-filter-3-line"></i>Filtros de Búsqueda</h6>
                <button type="button" class="btn btn-sm btn-info" 
                    onclick="abrirModalCambioMasivo({{ $oferta->id }})">
                    <i class="ri-calendar-event-line me-1"></i> Cambio Masivo de Fechas
                </button>
            </div>
            <div class="card-body">
                <div class="filtro-group">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="ri-checkbox-multiple-line"></i>Estado
                        </label>
                        <select class="form-select" id="filter-estado">
                            <option value="">Todos los estados</option>
                            <option value="Inscrito">Inscrito</option>
                            <option value="Pre-Inscrito">Pre-Inscrito</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="ri-search-line"></i>Buscar
                        </label>
                        <input type="text" class="form-control" id="filter-search"
                            placeholder="Nombre, apellido o carnet...">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary w-100" id="btn-filter">
                            <i class="ri-search-line me-1"></i> Buscar
                        </button>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-light w-100" id="btn-reset">
                            <i class="ri-refresh-line me-1"></i> Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="inscr-card">
            <div class="inscr-card-header">
                <h6><i class="ri-user-list-line"></i>Lista de Inscritos</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="inscr-table" id="inscritos-table">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col">Nombre Completo</th>
                                <th scope="col" class="text-center">Carnet</th>
                                <th scope="col" class="text-center">Estado</th>
                                <th scope="col" class="text-center">Plan de Pago</th>
                                <th scope="col" class="text-center">Fecha Registro</th>
                                <th scope="col" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inscripciones as $index => $inscripcion)
                                @php
                                    $persona = $inscripcion->estudiante->persona ?? null;
                                    $nombreCompleto = $persona
                                        ? $persona->apellido_paterno .
                                            ' ' .
                                            $persona->apellido_materno .
                                            ', ' .
                                            $persona->nombres
                                        : 'N/A';
                                @endphp

                                <tr class="inscripcion-row" data-estado="{{ $inscripcion->estado }}"
                                    data-nombre="{{ strtolower($nombreCompleto) }}"
                                    data-carnet="{{ $persona->carnet ?? '' }}">

                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $nombreCompleto }}</td>
                                    <td class="text-center">{{ $persona->carnet ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $inscripcion->estado == 'Inscrito' ? 'badge-success' : 'badge-warning' }}">
                                            {{ $inscripcion->estado }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($inscripcion->planesPago)
                                            {{ $inscripcion->planesPago->nombre }}
                                        @else
                                            <span class="text-muted">Sin plan</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $inscripcion->fecha_registro->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            @if ($inscripcion->estado == 'Pre-Inscrito' && Auth::guard('web')->user()->can('inscripciones.convertir'))
                                                <button type="button"
                                                    class="btn btn-warning btn-sm convertir-inscrito-btn"
                                                    data-inscripcion-id="{{ $inscripcion->id }}"
                                                    data-estudiante-id="{{ $inscripcion->estudiante_id }}"
                                                    data-oferta-id="{{ $oferta->id }}" title="Convertir a Inscrito">
                                                    <i class="ri-check-circle-line"></i>
                                                </button>
                                            @endif

                                            @if (Auth::guard('web')->user()->can('inscripciones.modulos-notas'))
                                                <button type="button" class="btn btn-info btn-sm ver-modulos-btn"
                                                    data-inscripcion-id="{{ $inscripcion->id }}"
                                                    data-estudiante-nombre="{{ $nombreCompleto }}" title="Ver Módulos">
                                                    <i class="ri-file-text-line"></i>
                                                </button>
                                            @endif

                                            @if (Auth::guard('web')->user()->can('inscripciones.cuotas'))
                                                <button type="button" class="btn btn-secondary btn-sm ver-cuotas-btn"
                                                    data-inscripcion-id="{{ $inscripcion->id }}"
                                                    data-estudiante-nombre="{{ $nombreCompleto }}" title="Ver Cuotas">
                                                    <i class="ri-money-dollar-circle-line"></i>
                                                </button>
                                            @endif

                                            @if (Auth::guard('web')->user()->can('inscripciones.cuotas'))
                                                <button type="button" class="btn btn-primary btn-sm editar-fechas-btn"
                                                    data-inscripcion-id="{{ $inscripcion->id }}"
                                                    data-estudiante-nombre="{{ $nombreCompleto }}" title="Editar Fechas">
                                                    <i class="ri-calendar-edit-line"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Módulos y Notas -->
    <div class="modal fade" id="modalVerModulos" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-book-open-line me-1"></i> Módulos y Notas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="avatar-md">
                                <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                    <i class="ri-user-line fs-20"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0" id="estudiante-nombre-modulos"></h6>
                            <p class="text-muted mb-0">Registro de calificaciones por módulo</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="40%">Módulo</th>
                                    <th class="text-center" width="20%">Nota Regular</th>
                                    <th class="text-center" width="20%">Nota Nivelación</th>
                                    <th class="text-center" width="20%">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-modulos-body">
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                        <p class="mt-2 mb-0 text-muted">Cargando módulos...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Cuotas -->
    <div class="modal fade" id="modalVerCuotas" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-money-dollar-circle-line me-1"></i> Cuotas y Pagos
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="avatar-md">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                    <i class="ri-user-line fs-20"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0" id="estudiante-nombre-cuotas"></h6>
                            <p class="text-muted mb-0">Estado de cuotas y pagos registrados</p>
                        </div>
                    </div>

                    <input type="hidden" id="inscripcion_id_cuotas" value="">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Concepto</th>
                                    <th class="text-center">Cuota</th>
                                    <th class="text-center">Monto Total</th>
                                    <th class="text-center">Pendiente</th>
                                    <th class="text-center">Fecha Pago</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-cuotas-body">
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                        <p class="mt-2 mb-0 text-muted">Cargando cuotas...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn btn-success" id="btn-registrar-pago">
                                <i class="ri-add-line me-1"></i> Registrar Pago
                            </button>
                            <button type="button" class="btn btn-primary ms-2" id="btn-editar-fechas-cuotas">
                                <i class="ri-calendar-edit-line me-1"></i> Editar Fechas
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                <i class="ri-close-line me-1"></i> Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Fechas de Cuotas Individual -->
    <div class="modal fade" id="modalEditarFechasCuota" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #0f766e 0%, #0d5f59 100%); color: white;">
                    <h5 class="modal-title"><i class="ri-calendar-edit-line me-2"></i>Editar Fechas de Cuotas</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Estudiante:</label>
                        <span id="editar-fechas-estudiante"></span>
                        <input type="hidden" id="editar-fechas-cuotas-inscripcion-id">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm" id="tabla-editar-fechas">
                            <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th class="text-center">N°</th>
                                    <th class="text-end">Monto</th>
                                    <th class="text-center">Fecha Pago</th>
                                </tr>
                            </thead>
                            <tbody id="editar-fechas-body"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btn-guardar-fechas-individual"
                        style="background: #0d9488; border-color: #0d9488;">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Cambio Masivo de Fechas de Cuotas -->
    <div class="modal fade" id="modalCambioMasivoFechas" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%); color: white;">
                    <h5 class="modal-title"><i class="ri-calendar-event-line me-2"></i>Cambio Masivo de Fechas de Cuotas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-3">
                        <i class="ri-information-line me-2"></i>
                        Esta opción permite cambiar las fechas de pago de las cuotas para todos los estudiantes Inscritos en
                        esta oferta.
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Plan de Pago:</label>
                            <select class="form-select" id="masivo-plan-pago">
                                <option value="">Todos los planes</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Concepto:</label>
                            <select class="form-select" id="masivo-concepto" disabled>
                                <option value="">Seleccione un plan primero</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Número de Cuota:</label>
                            <select class="form-select" id="masivo-n-cuota" disabled>
                                <option value="">Seleccione un concepto</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Nueva Fecha:</label>
                            <input type="date" class="form-control" id="masivo-nueva-fecha">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100" id="btn-aplicar-masivo">
                                <i class="ri-check-line me-1"></i> Aplicar
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm" id="tabla-masivo-fechas">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Estudiante</th>
                                    <th>Plan de Pago</th>
                                    <th>Concepto</th>
                                    <th class="text-center">Cuota</th>
                                    <th class="text-end">Monto</th>
                                    <th class="text-center">Fecha Actual</th>
                                </tr>
                            </thead>
                            <tbody id="masivo-fechas-body">
                                <tr>
                                    <td colspan="7" class="text-center py-3">Seleccione un plan, concepto y cuota para
                                        ver los estudiantes</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function abrirModalCambioMasivo(ofertaId) {
        console.log('=== abrirModalCambioMasivo llamado ===');
        console.log('ofertaId:', ofertaId);
        
        var $modal = document.getElementById('modalCambioMasivoFechas');
        if (!$modal) {
            console.error('Modal no encontrado');
            return;
        }
        
        $modal.setAttribute('data-oferta-id', ofertaId);

        document.getElementById('masivo-plan-pago').innerHTML = '<option value="">Todos los planes</option>';
        var conceploSelect = document.getElementById('masivo-concepto');
        conceploSelect.innerHTML = '<option value="">Seleccione un plan primero</option>';
        conceploSelect.disabled = true;
        
        var cuotaSelect = document.getElementById('masivo-n-cuota');
        cuotaSelect.innerHTML = '<option value="">Seleccione un concepto</option>';
        cuotaSelect.disabled = true;
        
        document.getElementById('masivo-fechas-body').innerHTML = '<tr><td colspan="7" class="text-center py-3">Seleccione un plan, concepto y cuota para ver los estudiantes</td></tr>';

        fetch('/admin/ofertas/' + ofertaId + '/datos')
            .then(function(response) { return response.json(); })
            .then(function(data) {
                var planesMap = {};
                if (data.plan_concepto) {
                    data.plan_concepto.forEach(function(pc) {
                        if (pc.plan_pago && pc.plan_pago.nombre) {
                            planesMap[pc.planes_pago_id] = pc.plan_pago.nombre;
                        }
                    });
                }
                var selectPlan = document.getElementById('masivo-plan-pago');
                Object.keys(planesMap).forEach(function(id) {
                    var opt = document.createElement('option');
                    opt.value = id;
                    opt.textContent = planesMap[id];
                    selectPlan.appendChild(opt);
                });
            })
            .catch(function() {
                alert('Error: No se pudieron cargar los planes de pago');
            });

        var bsModal = new bootstrap.Modal($modal);
        bsModal.show();
        console.log('Modal mostrado');
    }

    // Event listeners para los selects
    document.getElementById('masivo-plan-pago').addEventListener('change', function() {
        var planId = this.value;
        var ofertaId = document.getElementById('modalCambioMasivoFechas').getAttribute('data-oferta-id');
        console.log('Plan cambiado:', planId, 'Oferta:', ofertaId);
        
        var conceptoSelect = document.getElementById('masivo-concepto');
        var cuotaSelect = document.getElementById('masivo-n-cuota');
        
        if (!planId) {
            conceptoSelect.innerHTML = '<option value="">Seleccione un plan</option>';
            conceptoSelect.disabled = true;
            cuotaSelect.disabled = true;
            return;
        }
        
        fetch('/admin/ofertas/' + ofertaId + '/datos')
            .then(function(response) { return response.json(); })
            .then(function(data) {
                var opts = '<option value="">Todos los conceptos</option>';
                if (data.plan_concepto) {
                    data.plan_concepto.forEach(function(pc) {
                        if (pc.planes_pago_id == planId && pc.concepto) {
                            opts += '<option value="' + pc.concepto.id + '" data-n-cuotas="' + pc.n_cuotas + '">' + pc.concepto.nombre + ' (' + pc.n_cuotas + ' cuotas)</option>';
                        }
                    });
                }
                conceptoSelect.innerHTML = opts;
                conceptoSelect.disabled = false;
            });
        
        cuotaSelect.innerHTML = '<option value="">Seleccione un concepto</option>';
        cuotaSelect.disabled = true;
    });

    document.getElementById('masivo-concepto').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var nCuotas = parseInt(selectedOption.getAttribute('data-n-cuotas')) || 0;
        console.log('Concepto cambiado, nCuotas:', nCuotas);
        
        var cuotaSelect = document.getElementById('masivo-n-cuota');
        
        if (!this.value) {
            cuotaSelect.innerHTML = '<option value="">Seleccione un concepto</option>';
            cuotaSelect.disabled = true;
            return;
        }
        
        var opts = '<option value="">Todas las cuotas</option>';
        for (var i = 1; i <= nCuotas; i++) {
            opts += '<option value="' + i + '">Cuota ' + i + '</option>';
        }
        cuotaSelect.innerHTML = opts;
        cuotaSelect.disabled = false;
    });

    document.getElementById('masivo-n-cuota').addEventListener('change', function() {
        var ofertaId = document.getElementById('modalCambioMasivoFechas').getAttribute('data-oferta-id');
        var planId = document.getElementById('masivo-plan-pago').value;
        var conceptoId = document.getElementById('masivo-concepto').value;
        var nCuota = this.value;
        
        console.log('Cuota seleccionada:', nCuota, 'Plan:', planId, 'Concepto:', conceptoId);
        
        if (!planId || !conceptoId) {
            document.getElementById('masivo-fechas-body').innerHTML = '<tr><td colspan="7" class="text-center py-3">Seleccione un plan, concepto y cuota</td></tr>';
            return;
        }
        
        var url = '/admin/ofertas/' + ofertaId + '/cuotas-filtro?plan_id=' + planId + '&concepto_id=' + conceptoId;
        if (nCuota) url += '&n_cuota=' + nCuota;
        
        fetch(url)
            .then(function(response) { return response.json(); })
            .then(function(data) {
                console.log('Respuesta cuotas:', data);
                
                var cuotas = data.cuotas || data || [];
                if (data.success === false) {
                    alert(data.msg || 'Error');
                    return;
                }
                var tbody = document.getElementById('masivo-fechas-body');
                if (!data || data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-3">No hay estudiantes con estos criterios</td></tr>';
                    return;
                }
                var html = '';
                cuotas.forEach(function(est) {
                    html += '<tr>';
                    html += '<td>' + (est.estudiante_nombre || 'N/A') + '</td>';
                    html += '<td>' + (est.plan_nombre || '') + '</td>';
                    html += '<td class="text-center">' + (est.n_cuota || '') + '</td>';
                    html += '<td class="text-end">$' + (est.pago_total_bs || 0) + '</td>';
                    html += '<td class="text-center">' + (est.fecha_pago || '') + '</td>';
                    html += '<td class="text-center">Pendiente</td>';
                    html += '<td class="text-center"><input type="checkbox" class="estudiante-cuota" value="' + est.id + '"></td>';
                    html += '</tr>';
                });
                tbody.innerHTML = html;
            });
    });

    // Botón aplicar cambio masivo
    document.getElementById('btn-aplicar-masivo').addEventListener('click', function() {
        var ofertaId = document.getElementById('modalCambioMasivoFechas').getAttribute('data-oferta-id');
        var nuevaFecha = document.getElementById('masivo-nueva-fecha').value;
        
        console.log('Aplicar clicked, ofertaId:', ofertaId, 'nuevaFecha:', nuevaFecha);
        
        if (!nuevaFecha) {
            alert('Seleccione una nueva fecha');
            return;
        }
        
        if (!confirm('¿Aplicar esta fecha a las cuotas seleccionadas?')) return;
        
        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Aplicando...';
        
        var formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('nueva_fecha', nuevaFecha);
        formData.append('n_cuota', document.getElementById('masivo-n-cuota').value);
        formData.append('plan_id', document.getElementById('masivo-plan-pago').value);
        formData.append('concepto_id', document.getElementById('masivo-concepto').value);
        
        fetch('/admin/ofertas/' + ofertaId + '/actualizar-fechas-cuotas', {
            method: 'POST',
            body: formData
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            console.log('Respuesta:', data);
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-check-line me-1"></i> Aplicar';
            
            if (data.success) {
                alert(data.message || 'Fechas actualizadas correctamente');
                $('#modalCambioMasivoFechas').modal('hide');
                location.reload();
            } else {
                alert(data.message || 'Error al actualizar');
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-check-line me-1"></i> Aplicar';
            alert('Error al actualizar las fechas');
        });
    });
    </script>

    <!-- Modal: Registrar Pago -->
    <div class="modal fade" id="modalRegistrarPago" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-money-dollar-circle-line me-1"></i>
                        Registrar Pago
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-4" role="alert">
                        <i class="ri-information-line me-1"></i>
                        <span id="estudiante-nombre-pago"></span>
                    </div>

                    <form id="formRegistrarPago">
                        @csrf
                        <input type="hidden" name="inscripcion_id" id="inscripcion_id_pago">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Fecha de Pago <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="fecha_pago" class="form-control"
                                    value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tipo de Pago <span
                                        class="text-danger">*</span></label>
                                <select name="tipo_pago" class="form-select" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="qr">QR</option>
                                    <option value="parcial">Parcial (Efectivo + QR)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Detalles para pago parcial -->
                        <div id="detalles-parciales" class="row mb-3" style="display:none;">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Efectivo (Bs) <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="detalle_efectivo" class="form-control"
                                    min="0" placeholder="0.00">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">QR (Bs) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="detalle_qr" class="form-control"
                                    min="0" placeholder="0.00">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Monto Total (Bs) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Bs.</span>
                                    <input type="number" step="0.01" name="pago_bs" id="pago_bs_input"
                                        class="form-control" required min="0.01" placeholder="400.00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Descuento (Bs)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Bs.</span>
                                    <input type="number" step="0.01" name="descuento_bs" class="form-control"
                                        min="0" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <h6 class="mb-3 fw-semibold">Cuotas Pendientes</h6>
                        <p class="text-muted mb-3 fs-11">Seleccione las cuotas a las que desea asignar este pago (opcional)
                        </p>

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Concepto</th>
                                        <th class="text-center">Cuota</th>
                                        <th class="text-center">Total (Bs)</th>
                                        <th class="text-center">Pendiente (Bs)</th>
                                        <th class="text-center">Fecha Pago</th>
                                        <th class="text-center">Seleccionar</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-cuotas-pendientes">
                                    <tr>
                                        <td colspan="6" class="text-center py-3">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Cargando...</span>
                                            </div>
                                            <span class="ms-2 text-muted">Cargando cuotas pendientes...</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                <i class="ri-close-line me-1"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="ri-check-line me-1"></i> Registrar Pago
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Convertir a Inscrito -->
    <div class="modal fade" id="modalConvertirInscrito" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Convertir a Inscrito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formConvertirInscrito">
                        @csrf
                        <input type="hidden" name="inscripcion_id" id="convertir_inscripcion_id">
                        <input type="hidden" name="estudiante_id" id="convertir_estudiante_id">
                        <input type="hidden" name="oferta_id" id="convertir_oferta_id" value="{{ $oferta->id }}">

                        <div class="mb-3">
                            <label class="form-label">Plan de Pago *</label>
                            <select name="planes_pago_id" class="form-control" id="convertir_planes_pago_select"
                                required>
                                <option value="">Seleccione un plan</option>
                                <!-- Se llenará dinámicamente -->
                            </select>
                        </div>

                        <!-- Sección de vista previa de cuotas -->
                        <div id="convertir_cuotas-preview-section" style="display:none;">
                            <h6 class="mt-3">Vista Previa de Cuotas</h6>
                            <div id="convertir_cuotas-preview-container"></div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-outline-secondary"
                                    id="convertir_generar-cuotas-btn">Generar Cuotas</button>
                                <button type="button" class="btn btn-success" id="convertir_confirmar-cuotas-btn"
                                    style="display:none;">Confirmar Cuotas</button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success" id="convertir_submit_btn"
                                style="display:none;">Convertir a Inscrito</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card-animate {
            transition: transform 0.2s;
        }

        .card-animate:hover {
            transform: translateY(-5px);
        }

        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fs-11 {
            font-size: 11px !important;
        }

        .fs-13 {
            font-size: 13px !important;
        }

        .fs-17 {
            font-size: 17px !important;
        }

        .fs-20 {
            font-size: 20px !important;
        }

        .counter-value {
            font-weight: 600;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.05);
        }

        .hidden-row {
            display: none !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const PLANES_PAGOS = @json($planesPagos ?? []);
        const CONCEPTOS = @json($conceptos ?? []);

        // === CAMBIO MASIVO DE FECHAS ===
        function abrirModalCambioMasivo(ofertaId) {
            console.log('=== abrirModalCambioMasivo llamado ===');
            console.log('ofertaId:', ofertaId);
            
            var $modal = $('#modalCambioMasivoFechas');
            $modal.attr('data-oferta-id', ofertaId);

            $('#masivo-plan-pago').html('<option value="">Todos los planes</option>');
            $('#masivo-concepto').html('<option value="">Seleccione un plan primero</option>').prop('disabled', true);
            $('#masivo-n-cuota').html('<option value="">Seleccione un concepto</option>').prop('disabled', true);
            $('#masivo-fechas-body').html('<tr><td colspan="7" class="text-center py-3">Seleccione un plan, concepto y cuota para ver los estudiantes</td></tr>');

            $.ajax({
                url: '/admin/ofertas/' + ofertaId + '/datos',
                success: function(data) {
                    var planesMap = {};
                    if (data.plan_concepto) {
                        data.plan_concepto.forEach(function(pc) {
                            if (pc.plan_pago && pc.plan_pago.nombre) {
                                planesMap[pc.planes_pago_id] = pc.plan_pago.nombre;
                            }
                        });
                    }
                    var $selectPlan = $('#masivo-plan-pago');
                    Object.keys(planesMap).forEach(function(id) {
                        $selectPlan.append('<option value="' + id + '">' + planesMap[id] + '</option>');
                    });
                },
                error: function() {
                    showToast('error', 'Error', 'No se pudieron cargar los planes de pago');
                }
            });

            $modal.modal('show');
            console.log('Modal mostrado');
        }

        function showToast(type, title, message) {
            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div class="toast align-items-center text-bg-${type} border-0" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body"><strong>${title}</strong>: ${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>`;
            let container = document.querySelector('.toast-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'toast-container position-fixed top-0 end-0 p-3';
                document.body.appendChild(container);
            }
            container.insertAdjacentHTML('beforeend', toastHtml);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                animation: true,
                autohide: true,
                delay: 3000
            });
            toast.show();
            toastElement.addEventListener('hidden.bs.toast', function() {
                this.remove();
            });
        }

        $(document).ready(function() {
            console.log('jQuery listo, buscando botones...');
            console.log('btn-cambio-masivo-fechas existe:', $('#btn-cambio-masivo-fechas').length);
            console.log('btn-filter existe:', $('#btn-filter').length);
            if (typeof window.feather !== 'undefined') window.feather.replace();

            // ===== FILTRADO DE TABLA (CORREGIDO) =====
            function filterTable() {
                const estadoValue = $('#filter-estado').val();
                const searchValue = $('#filter-search').val().toLowerCase().trim();

                $('.inscripcion-row').each(function() {
                    const $row = $(this);
                    const estado = $row.data('estado');
                    const nombre = $row.data('nombre') || '';
                    const carnet = ($row.data('carnet') || '').toString();

                    const estadoMatch = !estadoValue || estado === estadoValue;
                    const searchMatch = !searchValue ||
                        nombre.includes(searchValue) ||
                        carnet.includes(searchValue);

                    $row.toggle(estadoMatch && searchMatch);
                });
            }

            // Asignación de eventos (asegurando que se ejecuten)
            $('#btn-filter').on('click', filterTable);
            $('#btn-reset').on('click', function() {
                $('#filter-estado').val('');
                $('#filter-search').val('');
                $('.inscripcion-row').show();
            });
            $('#filter-search').on('keyup input', function(e) {
                filterTable();
            });
            $('#filter-estado').on('change', filterTable);

            // ===== CONVERTIR PRE-INSCRITO A INSCRITO =====
            $(document).on('click', '.convertir-inscrito-btn', function() {
                $('#convertir_inscripcion_id').val($(this).data('inscripcion-id'));
                $('#convertir_estudiante_id').val($(this).data('estudiante-id'));
                $('#convertir_oferta_id').val($(this).data('oferta-id'));
                cargarPlanesPagoConvertir($(this).data('oferta-id'));
                $('#modalConvertirInscrito').modal('show');
            });

            function cargarPlanesPagoConvertir(ofertaId) {
                $.ajax({
                    url: `/admin/ofertas/${ofertaId}/datos`,
                    success: function(res) {
                        let planes = new Set();
                        if (res.plan_concepto) res.plan_concepto.forEach(pc => planes.add(pc
                            .planes_pago_id));
                        let opts = '<option value="">Seleccione un plan</option>';
                        PLANES_PAGOS.filter(p => planes.has(p.id)).forEach(p => {
                            opts += `<option value="${p.id}">${p.nombre}</option>`;
                        });
                        $('#convertir_planes_pago_select').html(opts);
                    }
                });
            }

            $('#convertir_generar-cuotas-btn').on('click', function() {
                if (!$('#convertir_planes_pago_select').val()) {
                    alert('Seleccione un plan');
                    return;
                }
                $('#convertir_cuotas-preview-container').html(
                    '<div class="text-center"><div class="spinner-border text-primary"></div></div>');
                $.ajax({
                    url: "{{ route('admin.inscripciones.generar-cuotas-preview') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        oferta_id: $('#convertir_oferta_id').val(),
                        planes_pago_id: $('#convertir_planes_pago_select').val()
                    },
                    success: function(res) {
                        if (res.success) {
                            renderizarCuotasPreviewConvertir(res.cuotas_preview);
                            $('#convertir_confirmar-cuotas-btn').show();
                            $('#convertir_generar-cuotas-btn').hide();
                        } else {
                            $('#convertir_cuotas-preview-container').html(
                                `<div class="alert alert-danger">${res.msg}</div>`);
                        }
                    }
                });
            });

            function renderizarCuotasPreviewConvertir(cuotas) {
                let html =
                    '<div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Concepto</th><th>N° Cuota</th><th>Monto</th><th>Fecha Pago</th></tr></thead><tbody>';
                cuotas.forEach(c => {
                    html +=
                        `<tr><td>${c.concepto_nombre}</td><td>${c.n_cuota}</td><td><input type="number" class="form-control" value="${c.pago_total_bs}" readonly></td><td><input type="date" class="form-control fecha-pago-input" value="${c.fecha_pago}" data-concepto-id="${c.concepto_id}" data-n-cuota="${c.n_cuota}"></td></tr>`;
                });
                html += '</tbody></table></div>';
                $('#convertir_cuotas-preview-container').html(html);
            }

            $('#convertir_confirmar-cuotas-btn').on('click', function() {
                const cuotasData = [];
                $('#convertir_cuotas-preview-container tbody tr').each(function() {
                    cuotasData.push({
                        concepto_id: $(this).find('.fecha-pago-input').data('concepto-id'),
                        n_cuota: $(this).find('.fecha-pago-input').data('n-cuota'),
                        fecha_pago: $(this).find('.fecha-pago-input').val(),
                        monto_bs: parseFloat($(this).find('input[type="number"]').val())
                    });
                });
                $.ajax({
                    url: "{{ route('admin.inscripciones.confirmar-cuotas') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        oferta_id: $('#convertir_oferta_id').val(),
                        estudiante_id: $('#convertir_estudiante_id').val(),
                        planes_pago_id: $('#convertir_planes_pago_select').val(),
                        cuotas_data: cuotasData
                    },
                    success: function(res) {
                        if (res.success) {
                            $.ajax({
                                url: "{{ route('admin.inscripciones.convertir-a-inscrito') }}",
                                method: 'POST',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    inscripcion_id: $('#convertir_inscripcion_id')
                                        .val(),
                                    planes_pago_id: $('#convertir_planes_pago_select')
                                        .val()
                                },
                                success: function(res2) {
                                    alert(res2.msg);
                                    if (res2.success) {
                                        $('#modalConvertirInscrito').modal('hide');
                                        location.reload();
                                    }
                                }
                            });
                        }
                    }
                });
            });

            // ===== VER MÓDULOS =====
            $(document).on('click', '.ver-modulos-btn', function() {
                $('#estudiante-nombre-modulos').text($(this).data('estudiante-nombre'));
                $('#tabla-modulos-body').html(
                    '<tr><td colspan="4" class="text-center py-4"><div class="spinner-border text-primary"></div></td></tr>'
                );
                $.ajax({
                    url: `/admin/inscripciones/${$(this).data('inscripcion-id')}/modulos-notas`,
                    success: function(data) {
                        let html = '';
                        if (!data || data.length === 0) {
                            html =
                                '<tr><td colspan="4" class="text-center py-4 text-muted">No hay módulos</td></tr>';
                        } else {
                            data.forEach(m => {
                                html +=
                                    `<tr><td class="fw-semibold">${m.modulo?.nombre || 'Módulo ' + m.modulo_id}</td><td class="text-center"><input type="number" class="form-control form-control-sm nota-regular text-center" value="${m.nota_regular || ''}" data-matriculacion-id="${m.id}" min="0" max="100"></td><td class="text-center"><input type="number" class="form-control form-control-sm nota-nivelacion text-center" value="${m.nota_nivelacion || ''}" data-matriculacion-id="${m.id}" min="0" max="100"></td><td class="text-center"><button class="btn btn-sm btn-outline-primary guardar-nota" data-matriculacion-id="${m.id}"><i class="ri-save-line"></i></button></td></tr>`;
                            });
                        }
                        $('#tabla-modulos-body').html(html);
                    }
                });
                new bootstrap.Modal(document.getElementById('modalVerModulos')).show();
            });

            $(document).on('click', '.guardar-nota', function() {
                const btn = $(this);
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
                $.ajax({
                    url: `/admin/inscripciones/${$(this).data('matriculacion-id')}/registrar-nota`,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        nota_regular: $(
                            `.nota-regular[data-matriculacion-id="${$(this).data('matriculacion-id')}"]`
                        ).val(),
                        nota_nivelacion: $(
                            `.nota-nivelacion[data-matriculacion-id="${$(this).data('matriculacion-id')}"]`
                        ).val()
                    }),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        showToast('success', 'Éxito', data.msg || 'Nota guardada');
                    },
                    error: function() {
                        showToast('error', 'Error', 'Error al guardar');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('<i class="ri-save-line"></i>');
                    }
                });
            });

            // ===== VER CUOTAS =====
            $(document).on('click', '.ver-cuotas-btn', function() {
                $('#estudiante-nombre-cuotas').text($(this).data('estudiante-nombre'));
                $('#inscripcion_id_cuotas').val($(this).data('inscripcion-id'));
                $('#tabla-cuotas-body').html(
                    '<tr><td colspan="6" class="text-center py-4"><div class="spinner-border text-primary"></div></td></tr>'
                );
                $.ajax({
                    url: `/admin/inscripciones/${$(this).data('inscripcion-id')}/cuotas-pendientes`,
                    success: function(data) {
                        let html = '';
                        if (!data || data.length === 0) {
                            html =
                                '<tr><td colspan="6" class="text-center py-4 text-muted">No hay cuotas pendientes</td></tr>';
                        } else {
                            data.forEach(c => {
                                html +=
                                    `<tr><td class="fw-semibold">${c.nombre}</td><td class="text-center">${c.n_cuota}</td><td class="text-center">Bs. ${parseFloat(c.pago_total_bs).toFixed(2)}</td><td class="text-center">Bs. ${parseFloat(c.pago_pendiente_bs).toFixed(2)}</td><td class="text-center">${c.fecha_pago}</td><td class="text-center">${c.pago_terminado === 'si' ? '<span class="badge bg-success">Pagado</span>' : '<span class="badge bg-warning">Pendiente</span>'}</td></tr>`;
                            });
                        }
                        $('#tabla-cuotas-body').html(html);
                    }
                });
                new bootstrap.Modal(document.getElementById('modalVerCuotas')).show();
            });

            // ===== EDITAR FECHAS =====
            $('#btn-editar-fechas-cuotas').on('click', function() {
                const inscripcionId = $('#inscripcion_id_cuotas').val();
                if (!inscripcionId) {
                    alert('Seleccione un estudiante');
                    return;
                }
                $('#editar-fechas-estudiante').text($('#estudiante-nombre-cuotas').text());
                $('#editar-fechas-body').html(
                    '<tr><td colspan="4" class="text-center py-3"><div class="spinner-border spinner-border-sm"></div></td></tr>'
                );
                $.ajax({
                    url: `/admin/inscripciones/${inscripcionId}/cuotas`,
                    success: function(data) {
                        let html = '';
                        if (data && data.length > 0) {
                            data.forEach(c => {
                                const esPagada = c.pago_terminado === 'si' || c
                                    .pago_terminado === true;
                                html +=
                                    `<tr><td>${c.concepto_nombre || '-'}</td><td class="text-center">${c.n_cuota || '-'}</td><td class="text-end">${parseFloat(c.pago_total_bs).toFixed(2)}</td><td class="text-center"><input type="date" class="form-control form-control-sm fecha-cuota-input" value="${c.fecha_pago}" data-cuota-id="${c.id}"${esPagada ? ' disabled' : ''}>${esPagada ? ' <span class="badge bg-success">Pagada</span>' : ''}</td></tr>`;
                            });
                        } else {
                            html =
                                '<tr><td colspan="4" class="text-center py-3 text-muted">No hay cuotas</td></tr>';
                        }
                        $('#editar-fechas-body').html(html);
                    }
                });
                new bootstrap.Modal(document.getElementById('modalEditarFechasCuota')).show();
            });

            $('#btn-guardar-fechas-individual').on('click', function() {
                const btn = $(this);
                const updates = [];
                $('#editar-fechas-body .fecha-cuota-input:not(:disabled)').each(function() {
                    updates.push({
                        cuota_id: $(this).data('cuota-id'),
                        fecha_pago: $(this).val()
                    });
                });
                if (updates.length === 0) {
                    alert('No hay cuotas para actualizar');
                    return;
                }
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm"></span> Guardando...');
                $.ajax({
                    url: "{{ route('admin.inscripciones.actualizar-fechas-individual') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        actualizaciones: updates
                    },
                    success: function(res) {
                        alert(res.msg);
                        if (res.success) {
                            $('#modalEditarFechasCuota').modal('hide');
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('Error');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('Guardar Cambios');
                    }
                });
            });

            // ===== CAMBIO MASIVO DE FECHAS =====
            // Usar setInterval para asegurar que el botón existe cuando se ejecuta
            var checkInterval = setInterval(function() {
                var $btn = $('#btn-cambio-masivo-fechas');
                if ($btn.length) {
                    clearInterval(checkInterval);
                    console.log('Botón encontrado, attachando evento...');
                    
                    $btn.on('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        console.log('=== Click en cambio masivo detectado ===');
                        var ofertaId = $(this).data('oferta-id');
                        console.log('Oferta ID:', ofertaId);
                        
                        if (!ofertaId) {
                            console.error('ERROR: No se encontró oferta-id en el botón');
                            alert('Error: No se pudo obtener el ID de la oferta');
                            return;
                        }

                        var $modal = $('#modalCambioMasivoFechas');
                        $modal.attr('data-oferta-id', ofertaId);

                        $('#masivo-plan-pago').html('<option value="">Todos los planes</option>');
                        $('#masivo-concepto').html('<option value="">Seleccione un plan primero</option>').prop('disabled', true);
                        $('#masivo-n-cuota').html('<option value="">Seleccione un concepto</option>').prop('disabled', true);
                        $('#masivo-fechas-body').html('<tr><td colspan="7" class="text-center py-3">Seleccione un plan, concepto y cuota para ver los estudiantes</td></tr>');

                        $.ajax({
                            url: '/admin/ofertas/' + ofertaId + '/datos',
                            success: function(data) {
                                var planesMap = {};
                                if (data.plan_concepto) {
                                    data.plan_concepto.forEach(function(pc) {
                                        if (pc.plan_pago && pc.plan_pago.nombre) {
                                            planesMap[pc.planes_pago_id] = pc.plan_pago.nombre;
                                        }
                                    });
                                }
                                var $selectPlan = $('#masivo-plan-pago');
                                Object.keys(planesMap).forEach(function(id) {
                                    $selectPlan.append('<option value="' + id + '">' + planesMap[id] + '</option>');
                                });
                            },
                            error: function() {
                                showToast('error', 'Error', 'No se pudieron cargar los planes de pago');
                            }
                        });

                        $modal.modal('show');
                        console.log('Modal mostrado correctamente');
                    });
                }
            }, 100);

            $('#masivo-plan-pago').on('change', function() {
                const planId = $(this).val();
                const ofertaId = $('#modalCambioMasivoFechas').attr('data-oferta-id');
                if (!planId) {
                    $('#masivo-concepto').html('<option value="">Seleccione un plan</option>').prop(
                        'disabled', true);
                    $('#masivo-n-cuota').prop('disabled', true);
                    return;
                }
                $.ajax({
                    url: `/admin/ofertas/${ofertaId}/datos`,
                    success: function(data) {
                        let opts = '<option value="">Todos los conceptos</option>';
                        if (data.plan_concepto) {
                            data.plan_concepto.forEach(pc => {
                                if (pc.planes_pago_id == planId && pc.concepto) {
                                    opts +=
                                        `<option value="${pc.concepto.id}" data-n-cuotas="${pc.n_cuotas}">${pc.concepto.nombre} (${pc.n_cuotas} cuotas)</option>`;
                                }
                            });
                        }
                        $('#masivo-concepto').html(opts).prop('disabled', false);
                    }
                });
                $('#masivo-n-cuota').html('<option value="">Seleccione un concepto</option>').prop(
                    'disabled', true);
            });

            $('#masivo-concepto').on('change', function() {
                const nCuotas = $(this).find(':selected').data('n-cuotas') || 0;
                if (!$(this).val()) {
                    $('#masivo-n-cuota').html('<option value="">Seleccione un concepto</option>').prop(
                        'disabled', true);
                    return;
                }
                let opts = '<option value="">Todas las cuotas</option>';
                for (let i = 1; i <= nCuotas; i++) opts += `<option value="${i}">Cuota ${i}</option>`;
                $('#masivo-n-cuota').html(opts).prop('disabled', false);
            });

            $('#masivo-n-cuota').on('change', function() {
                const ofertaId = $('#modalCambioMasivoFechas').attr('data-oferta-id');
                const url =
                    `/admin/ofertas/${ofertaId}/cuotas-filtro?plan_id=${$('#masivo-plan-pago').val()}&concepto_id=${$('#masivo-concepto').val()}&n_cuota=${$(this).val()}`;
                $('#masivo-fechas-body').html(
                    '<tr><td colspan="7" class="text-center py-3"><div class="spinner-border spinner-border-sm"></div></td></tr>'
                );
                $.ajax({
                    url: url,
                    success: function(data) {
                        let html = '';
                        if (data.cuotas && data.cuotas.length > 0) {
                            data.cuotas.forEach((c, i) => {
                                html +=
                                    `<tr><td class="text-center">${i+1}</td><td>${c.estudiante_nombre}</td><td>${c.plan_nombre || '-'}</td><td>${c.concepto_nombre}</td><td class="text-center">${c.n_cuota}</td><td class="text-end">${parseFloat(c.pago_total_bs).toFixed(2)}</td><td class="text-center">${c.fecha_pago}</td></tr>`;
                            });
                        } else {
                            html =
                                '<tr><td colspan="7" class="text-center py-3 text-muted">No hay resultados</td></tr>';
                        }
                        $('#masivo-fechas-body').html(html);
                    },
                    error: function() {
                        $('#masivo-fechas-body').html(
                            '<tr><td colspan="7" class="text-center py-3 text-danger">Error</td></tr>'
                        );
                    }
                });
            });

            $(document).on('click', '#btn-aplicar-masivo', function() {
                const ofertaId = $('#modalCambioMasivoFechas').attr('data-oferta-id');
                const nuevaFecha = $('#masivo-nueva-fecha').val();
                if (!nuevaFecha) {
                    alert('Seleccione una nueva fecha');
                    return;
                }
                if (!confirm('¿Aplicar esta fecha a las cuotas seleccionadas?')) return;
                const btn = $(this);
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm"></span> Aplicando...');
                console.log('Enviando peticion con ofertaId:', ofertaId, 'nuevaFecha:', nuevaFecha);
                $.ajax({
                    url: `/admin/ofertas/${ofertaId}/actualizar-fechas-cuotas`,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        nueva_fecha: nuevaFecha,
                        n_cuota: $('#masivo-n-cuota').val(),
                        plan_id: $('#masivo-plan-pago').val(),
                        concepto_id: $('#masivo-concepto').val()
                    },
                    success: function(res) {
                        console.log('Respuesta:', res);
                        if (res.success) {
                            alert(res.message || 'Fechas actualizadas correctamente');
                            $('#modalCambioMasivoFechas').modal('hide');
                            location.reload();
                        } else {
                            alert(res.message || 'Error al actualizar');
                        }
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr);
                        alert('Error al aplicar los cambios');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(
                            '<i class="ri-check-line me-1"></i> Aplicar');
                    }
                });
            });

            // ===== REGISTRAR PAGO =====
            $('#btn-registrar-pago').on('click', function() {
                $('#estudiante-nombre-pago').text($('#estudiante-nombre-cuotas').text());
                $('#inscripcion_id_pago').val($('#inscripcion_id_cuotas').val());
                $('#tabla-cuotas-pendientes').html(
                    '<tr><td colspan="6" class="text-center py-3"><div class="spinner-border"></div></td></tr>'
                );
                $.ajax({
                    url: `/admin/inscripciones/${$('#inscripcion_id_cuotas').val()}/cuotas-pendientes`,
                    success: function(data) {
                        let html = '';
                        if (!data || data.length === 0) {
                            html =
                                '<tr><td colspan="6" class="text-center py-3 text-muted">No hay cuotas pendientes</td></tr>';
                        } else {
                            data.forEach(c => {
                                html +=
                                    `<tr><td>${c.nombre}</td><td class="text-center">${c.n_cuota}</td><td class="text-center">Bs. ${parseFloat(c.pago_total_bs).toFixed(2)}</td><td class="text-center">Bs. ${parseFloat(c.pago_pendiente_bs).toFixed(2)}</td><td class="text-center">${c.fecha_pago}</td><td class="text-center"><input type="checkbox" class="form-check-input cuota-seleccionada" data-cuota-id="${c.id}" data-monto-max="${c.pago_pendiente_bs}"></td></tr>`;
                            });
                        }
                        $('#tabla-cuotas-pendientes').html(html);
                    }
                });
                new bootstrap.Modal(document.getElementById('modalRegistrarPago')).show();
            });

            $('select[name="tipo_pago"]').on('change', function() {
                $('#detalles-parciales').toggle($(this).val() === 'parcial');
            });

            $(document).on('change', '.cuota-seleccionada', function() {
                let total = 0;
                $('.cuota-seleccionada:checked').each(function() {
                    total += parseFloat($(this).data('monto-max')) || 0;
                });
                $('#pago_bs_input').val(total.toFixed(2));
            });

            $('#formRegistrarPago').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const cuotasSel = [];
                $('.cuota-seleccionada:checked').each(function() {
                    cuotasSel.push({
                        cuota_id: $(this).data('cuota-id'),
                        monto: $(this).data('monto-max')
                    });
                });
                if (cuotasSel.length > 0) formData.append('cuotas_seleccionadas', JSON.stringify(
                    cuotasSel));
                const btn = $(this).find('button[type="submit"]');
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
                $.ajax({
                    url: "{{ route('admin.pagos.registrar') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.success) {
                            showToast('success', 'Éxito', data.msg);
                            $('#modalRegistrarPago').modal('hide');
                            $('#modalVerCuotas').modal('hide');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showToast('error', 'Error', data.msg);
                        }
                    },
                    error: function() {
                        showToast('error', 'Error', 'Error');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(
                            '<i class="ri-check-line me-1"></i> Registrar Pago');
                    }
                });
            });
        });
    </script>
@endpush
