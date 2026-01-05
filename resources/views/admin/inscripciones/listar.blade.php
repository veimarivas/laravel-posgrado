@extends('admin.dashboard')

@section('admin')
    <style>
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
    </style>
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.ofertas.listar') }}">Ofertas</a></li>
            <li class="breadcrumb-item active">Inscritos - {{ $oferta->codigo }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Gestión de Inscripciones</h4>
                        <p class="text-muted mb-0">Oferta: {{ $oferta->programa?->nombre ?? 'N/A' }} - {{ $oferta->codigo }}
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light btn-sm" onclick="window.print()">
                            <i class="ri-printer-line align-middle me-1"></i> Imprimir
                        </button>
                        <a href="{{ route('admin.ofertas.listar') }}" class="btn btn-secondary btn-sm">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Resumen de inscripciones -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded fs-17">
                                                    <i class="ri-user-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                Total Inscritos
                                            </p>
                                            <h4 class="mb-0">
                                                <span class="counter-value"
                                                    data-target="{{ $inscripciones->where('estado', 'Inscrito')->count() }}">0</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-warning-subtle text-warning rounded fs-17">
                                                    <i class="ri-time-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                Pre-Inscritos
                                            </p>
                                            <h4 class="mb-0">
                                                <span class="counter-value"
                                                    data-target="{{ $inscripciones->where('estado', 'Pre-Inscrito')->count() }}">0</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-success-subtle text-success rounded fs-17">
                                                    <i class="ri-check-double-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                Total Estudiantes
                                            </p>
                                            <h4 class="mb-0">
                                                <span class="counter-value"
                                                    data-target="{{ $inscripciones->count() }}">0</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-info-subtle text-info rounded fs-17">
                                                    <i class="ri-calendar-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                Último Registro
                                            </p>
                                            <h6 class="mb-0 text-truncate">
                                                @if ($inscripciones->count() > 0)
                                                    {{ $inscripciones->sortByDesc('fecha_registro')->first()->fecha_registro?->format('d/m/Y') ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros y búsqueda -->
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Estado</label>
                                            <select class="form-select" id="filter-estado">
                                                <option value="">Todos los estados</option>
                                                <option value="Inscrito">Inscrito</option>
                                                <option value="Pre-Inscrito">Pre-Inscrito</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Buscar por nombre o carnet</label>
                                            <input type="text" class="form-control" id="filter-search"
                                                placeholder="Nombre, apellido o carnet...">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button class="btn btn-primary w-100" id="btn-filter">
                                                <i class="ri-search-line align-middle me-1"></i> Buscar
                                            </button>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button class="btn btn-light w-100" id="btn-reset">
                                                <i class="ri-refresh-line align-middle me-1"></i> Limpiar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de inscritos -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <!-- En la sección de la tabla (líneas ~140-160) -->
                                        <table class="table table-hover table-centered align-middle table-nowrap mb-0"
                                            id="inscritos-table">
                                            <thead class="table-light">
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
                                                                class="badge bg-{{ $inscripcion->estado == 'Inscrito' ? 'success' : 'warning' }}">
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
                                                            <div class="btn-group" role="group">
                                                                @if ($inscripcion->estado == 'Pre-Inscrito' && Auth::guard('web')->user()->can('inscripciones.convertir'))
                                                                    <button type="button"
                                                                        class="btn btn-warning btn-sm convertir-inscrito-btn"
                                                                        data-inscripcion-id="{{ $inscripcion->id }}"
                                                                        data-estudiante-id="{{ $inscripcion->estudiante_id }}"
                                                                        data-oferta-id="{{ $oferta->id }}"
                                                                        title="Convertir a Inscrito">
                                                                        <i data-feather="check-circle"></i>
                                                                    </button>
                                                                @endif

                                                                @if (Auth::guard('web')->user()->can('inscripciones.modulos-notas'))
                                                                    <button type="button"
                                                                        class="btn btn-info btn-sm ver-modulos-btn"
                                                                        data-inscripcion-id="{{ $inscripcion->id }}"
                                                                        data-estudiante-nombre="{{ $nombreCompleto }}">
                                                                        <i data-feather="file-text"></i>
                                                                    </button>
                                                                @endif

                                                                @if (Auth::guard('web')->user()->can('inscripciones.cuotas'))
                                                                    <button type="button"
                                                                        class="btn btn-secondary btn-sm ver-cuotas-btn"
                                                                        data-inscripcion-id="{{ $inscripcion->id }}"
                                                                        data-estudiante-nombre="{{ $nombreCompleto }}">
                                                                        <i data-feather="dollar-sign"></i>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para seleccionar plan de pago y confirmar conversión -->
    <div class="modal fade" id="modalConvertirInscrito" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-check-double-line me-1"></i> Convertir a Inscrito
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info" role="alert">
                        <i class="ri-information-line me-1"></i>
                        Al convertir a inscrito, se generarán las cuotas correspondientes al plan de pago seleccionado.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Estudiante:</label>
                        <p class="mb-0 text-primary" id="nombre-estudiante"></p>
                    </div>

                    <input type="hidden" id="inscripcion-id">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Plan de Pago <span class="text-danger">*</span></label>
                        <select id="planes-pago-convertir" class="form-select" required>
                            <option value="">Cargando planes disponibles...</option>
                        </select>
                        <div class="invalid-feedback">Debe seleccionar un plan de pago.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btn-confirmar-conversion">
                        <i class="ri-check-line align-middle me-1"></i> Confirmar Conversión
                    </button>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Contador animado para las tarjetas
            function animateCounters() {
                const counters = document.querySelectorAll('.counter-value');
                counters.forEach(counter => {
                    const target = parseInt(counter.getAttribute('data-target'));
                    const duration = 1000;
                    const step = target / (duration / 16);
                    let current = 0;

                    const updateCounter = () => {
                        current += step;
                        if (current < target) {
                            counter.textContent = Math.round(current);
                            requestAnimationFrame(updateCounter);
                        } else {
                            counter.textContent = target;
                        }
                    };

                    updateCounter();
                });
            }

            // Ejecutar animación después de un pequeño delay
            setTimeout(animateCounters, 500);

            // ===== FILTRADO DE TABLA =====
            const filterEstado = document.getElementById('filter-estado');
            const filterSearch = document.getElementById('filter-search');
            const btnFilter = document.getElementById('btn-filter');
            const btnReset = document.getElementById('btn-reset');
            const tableRows = document.querySelectorAll('#inscritos-table tbody tr.inscripcion-row');

            function filterTable() {
                const estadoValue = filterEstado.value;
                const searchValue = filterSearch.value.toLowerCase().trim();

                tableRows.forEach(row => {
                    const estado = row.getAttribute('data-estado');
                    const nombre = row.getAttribute('data-nombre');
                    const carnet = row.getAttribute('data-carnet');

                    // Verificar estado
                    const estadoMatch = !estadoValue || estado === estadoValue;

                    // Verificar búsqueda
                    let searchMatch = true;
                    if (searchValue) {
                        searchMatch = nombre.includes(searchValue) ||
                            carnet.toLowerCase().includes(searchValue);
                    }

                    // Mostrar u ocultar fila
                    if (estadoMatch && searchMatch) {
                        row.classList.remove('hidden-row');
                    } else {
                        row.classList.add('hidden-row');
                    }
                });
            }

            // Event listeners para filtros
            if (btnFilter) {
                btnFilter.addEventListener('click', filterTable);
            }

            if (btnReset) {
                btnReset.addEventListener('click', function() {
                    filterEstado.value = '';
                    filterSearch.value = '';
                    tableRows.forEach(row => {
                        row.classList.remove('hidden-row');
                    });
                });
            }

            // Buscar al presionar Enter
            if (filterSearch) {
                filterSearch.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') {
                        filterTable();
                    }
                });
            }

            // ===== MODALES =====

            // 1. Modal: Convertir a Inscrito
            document.addEventListener('click', function(e) {
                // Botón de convertir a inscrito
                if (e.target.closest('.convertir-a-inscrito')) {
                    const button = e.target.closest('.convertir-a-inscrito');
                    const inscripcionId = button.getAttribute('data-inscripcion-id');
                    const ofertaId = button.getAttribute('data-oferta-id');
                    const nombre = button.closest('tr').querySelector('h6.mb-0').textContent + ' ' +
                        button.closest('tr').querySelector('p.text-muted').textContent;

                    document.getElementById('nombre-estudiante').textContent = nombre;
                    document.getElementById('inscripcion-id').value = inscripcionId;

                    // Cargar planes de pago disponibles
                    fetch(`/admin/ofertas/${ofertaId}/datos`)
                        .then(response => response.json())
                        .then(oferta => {
                            const planesUsados = new Set();
                            if (oferta.plan_concepto && Array.isArray(oferta.plan_concepto)) {
                                oferta.plan_concepto.forEach(pc => {
                                    if (pc.planes_pago_id) {
                                        planesUsados.add(pc.planes_pago_id);
                                    }
                                });
                            }

                            let options = '<option value="">Seleccione un plan</option>';
                            // Necesitas pasar $planes desde el controlador
                            @if (isset($planes) && is_array($planes))
                                @foreach ($planes as $plan)
                                    if (planesUsados.has({{ $plan->id }})) {
                                        options +=
                                            `<option value="{{ $plan->id }}">{{ $plan->nombre }}</option>`;
                                    }
                                @endforeach
                            @endif

                            document.getElementById('planes-pago-convertir').innerHTML = options;
                        })
                        .catch(error => {
                            console.error('Error al cargar planes:', error);
                            document.getElementById('planes-pago-convertir').innerHTML =
                                '<option value="">Error al cargar planes</option>';
                        });

                    // Mostrar modal
                    const modal = new bootstrap.Modal(document.getElementById('modalConvertirInscrito'));
                    modal.show();
                }

                // 2. Botón de ver módulos
                if (e.target.closest('.ver-modulos-btn')) {
                    const button = e.target.closest('.ver-modulos-btn');
                    const inscripcionId = button.getAttribute('data-inscripcion-id');
                    const nombre = button.getAttribute('data-estudiante-nombre');

                    document.getElementById('estudiante-nombre-modulos').textContent = nombre;

                    // Cargar módulos
                    fetch(`/admin/inscripciones/${inscripcionId}/modulos-notas`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la respuesta del servidor');
                            }
                            return response.json();
                        })
                        .then(matriculaciones => {
                            let html = '';
                            if (!matriculaciones || matriculaciones.length === 0) {
                                html = `
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="avatar-lg mx-auto mb-3">
                                                <div class="avatar-title bg-light text-secondary rounded-circle">
                                                    <i class="ri-book-open-line fs-20"></i>
                                                </div>
                                            </div>
                                            <h6 class="text-muted">No hay módulos matriculados</h6>
                                        </td>
                                    </tr>`;
                            } else {
                                matriculaciones.forEach(m => {
                                    const modulo = m.modulo?.nombre || `Módulo ${m.modulo_id}`;
                                    const notaRegular = m.nota_regular || '';
                                    const notaNivelacion = m.nota_nivelacion || '';

                                    html += `
                                    <tr>
                                        <td class="fw-semibold">${modulo}</td>
                                        <td class="text-center">
                                            <input type="number" class="form-control form-control-sm nota-regular text-center" 
                                                   value="${notaRegular}" 
                                                   data-matriculacion-id="${m.id}" 
                                                   min="0" max="100" step="0.1" placeholder="0-100">
                                        </td>
                                        <td class="text-center">
                                            <input type="number" class="form-control form-control-sm nota-nivelacion text-center" 
                                                   value="${notaNivelacion}" 
                                                   data-matriculacion-id="${m.id}" 
                                                   min="0" max="100" step="0.1" placeholder="0-100">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-primary guardar-nota"
                                                    data-matriculacion-id="${m.id}">
                                                <i class="ri-save-line"></i>
                                            </button>
                                        </td>
                                    </tr>`;
                                });
                            }
                            document.getElementById('tabla-modulos-body').innerHTML = html;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            document.getElementById('tabla-modulos-body').innerHTML = `
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="text-danger">
                                            <i class="ri-error-warning-line fs-20"></i>
                                            <p class="mt-2 mb-0">Error al cargar los módulos</p>
                                        </div>
                                    </td>
                                </tr>`;
                        });

                    // Mostrar modal
                    const modal = new bootstrap.Modal(document.getElementById('modalVerModulos'));
                    modal.show();
                }

                // 3. Botón de ver cuotas
                if (e.target.closest('.ver-cuotas-btn')) {
                    const button = e.target.closest('.ver-cuotas-btn');
                    const inscripcionId = button.getAttribute('data-inscripcion-id');
                    const nombre = button.getAttribute('data-estudiante-nombre');

                    document.getElementById('estudiante-nombre-cuotas').textContent = nombre;
                    document.getElementById('inscripcion_id_cuotas').value = inscripcionId;

                    // Cargar cuotas
                    fetch(`/admin/inscripciones/${inscripcionId}/cuotas-pendientes`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la respuesta del servidor');
                            }
                            return response.json();
                        })
                        .then(cuotas => {
                            let html = '';
                            if (!cuotas || cuotas.length === 0) {
                                html = `
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="avatar-lg mx-auto mb-3">
                                                <div class="avatar-title bg-light text-secondary rounded-circle">
                                                    <i class="ri-money-dollar-circle-line fs-20"></i>
                                                </div>
                                            </div>
                                            <h6 class="text-muted">No hay cuotas pendientes</h6>
                                            <p class="text-muted mb-0 fs-11">Todas las cuotas han sido pagadas</p>
                                        </td>
                                    </tr>`;
                            } else {
                                cuotas.forEach(c => {
                                    const estado = c.pago_terminado === 'si' ?
                                        '<span class="badge bg-success-subtle text-success">Pagado</span>' :
                                        '<span class="badge bg-warning-subtle text-warning">Pendiente</span>';

                                    html += `
                                    <tr>
                                        <td class="fw-semibold">${c.nombre}</td>
                                        <td class="text-center">${c.n_cuota}</td>
                                        <td class="text-center">Bs. ${parseFloat(c.pago_total_bs).toFixed(2)}</td>
                                        <td class="text-center fw-bold">Bs. ${parseFloat(c.pago_pendiente_bs).toFixed(2)}</td>
                                        <td class="text-center">${c.fecha_pago}</td>
                                        <td class="text-center">${estado}</td>
                                    </tr>`;
                                });
                            }
                            document.getElementById('tabla-cuotas-body').innerHTML = html;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            document.getElementById('tabla-cuotas-body').innerHTML = `
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-danger">
                                            <i class="ri-error-warning-line fs-20"></i>
                                            <p class="mt-2 mb-0">Error al cargar las cuotas</p>
                                        </div>
                                    </td>
                                </tr>`;
                        });

                    // Mostrar modal
                    const modal = new bootstrap.Modal(document.getElementById('modalVerCuotas'));
                    modal.show();
                }

                // 4. Botón de guardar nota (dentro del modal de módulos)
                if (e.target.closest('.guardar-nota')) {
                    const button = e.target.closest('.guardar-nota');
                    const matriculacionId = button.getAttribute('data-matriculacion-id');
                    const regularInput = document.querySelector(
                        `.nota-regular[data-matriculacion-id="${matriculacionId}"]`);
                    const nivelacionInput = document.querySelector(
                        `.nota-nivelacion[data-matriculacion-id="${matriculacionId}"]`);

                    const notaRegular = regularInput ? regularInput.value : null;
                    const notaNivelacion = nivelacionInput ? nivelacionInput.value : null;

                    // Deshabilitar botón
                    button.disabled = true;
                    button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                    // Enviar datos
                    fetch(`/admin/inscripciones/${matriculacionId}/registrar-nota`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({
                                nota_regular: notaRegular,
                                nota_nivelacion: notaNivelacion
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            showToast('success', 'Éxito', data.msg || 'Nota guardada correctamente');
                        })
                        .catch(error => {
                            showToast('error', 'Error', 'Error al guardar la nota');
                        })
                        .finally(() => {
                            // Restaurar botón
                            button.disabled = false;
                            button.innerHTML = '<i class="ri-save-line"></i>';
                        });
                }
            });

            // ===== CONFIRMAR CONVERSIÓN A INSCRITO =====
            document.getElementById('btn-confirmar-conversion').addEventListener('click', function() {
                const planId = document.getElementById('planes-pago-convertir').value;
                const inscripcionId = document.getElementById('inscripcion-id').value;
                const selectEl = document.getElementById('planes-pago-convertir');

                if (!planId) {
                    selectEl.classList.add('is-invalid');
                    return;
                }

                selectEl.classList.remove('is-invalid');

                // Deshabilitar botón
                this.disabled = true;
                this.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span> Procesando...';

                fetch("{{ route('admin.inscripciones.convertir-a-inscrito') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            inscripcion_id: inscripcionId,
                            planes_pago_id: planId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', 'Éxito', data.msg);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showToast('error', 'Error', data.msg || 'Error desconocido');
                            this.disabled = false;
                            this.innerHTML =
                                '<i class="ri-check-line align-middle me-1"></i> Confirmar Conversión';
                        }
                    })
                    .catch(error => {
                        showToast('error', 'Error', 'Error al convertir la inscripción');
                        this.disabled = false;
                        this.innerHTML =
                            '<i class="ri-check-line align-middle me-1"></i> Confirmar Conversión';
                    });
            });

            // ===== REGISTRAR PAGO =====
            // 1. Abrir modal de registrar pago
            document.getElementById('btn-registrar-pago').addEventListener('click', function() {
                const inscripcionId = document.getElementById('inscripcion_id_cuotas').value;
                if (!inscripcionId) {
                    showToast('error', 'Error', 'ID de inscripción no disponible');
                    return;
                }

                const nombre = document.getElementById('estudiante-nombre-cuotas').textContent;
                document.getElementById('estudiante-nombre-pago').textContent = nombre;
                document.getElementById('inscripcion_id_pago').value = inscripcionId;

                // Cargar cuotas pendientes
                fetch(`/admin/inscripciones/${inscripcionId}/cuotas-pendientes`)
                    .then(response => response.json())
                    .then(cuotas => {
                        let html = '';
                        if (!cuotas || cuotas.length === 0) {
                            html = `
                                <tr>
                                    <td colspan="6" class="text-center py-3">
                                        <div class="text-muted">
                                            <i class="ri-check-double-line fs-16"></i>
                                            <p class="mt-1 mb-0 fs-11">No hay cuotas pendientes</p>
                                        </div>
                                    </td>
                                </tr>`;
                        } else {
                            cuotas.forEach(c => {
                                const pendiente = parseFloat(c.pago_pendiente_bs).toFixed(2);
                                html += `
                                <tr>
                                    <td>${c.nombre}</td>
                                    <td class="text-center">${c.n_cuota}</td>
                                    <td class="text-center">Bs. ${parseFloat(c.pago_total_bs).toFixed(2)}</td>
                                    <td class="text-center fw-semibold">Bs. ${pendiente}</td>
                                    <td class="text-center">${c.fecha_pago}</td>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input cuota-seleccionada" 
                                               data-cuota-id="${c.id}" 
                                               data-monto-max="${pendiente}"
                                               style="cursor: pointer;">
                                    </td>
                                </tr>`;
                            });
                        }
                        document.getElementById('tabla-cuotas-pendientes').innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('tabla-cuotas-pendientes').innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center py-3">
                                    <div class="text-danger">
                                        <i class="ri-error-warning-line fs-16"></i>
                                        <p class="mt-1 mb-0 fs-11">Error al cargar cuotas</p>
                                    </div>
                                </td>
                            </tr>`;
                    });

                // Mostrar modal
                const modal = new bootstrap.Modal(document.getElementById('modalRegistrarPago'));
                modal.show();
            });

            // 2. Manejar tipo de pago parcial
            document.querySelector('select[name="tipo_pago"]')?.addEventListener('change', function() {
                if (this.value === 'parcial') {
                    document.getElementById('detalles-parciales').style.display = 'block';
                } else {
                    document.getElementById('detalles-parciales').style.display = 'none';
                    document.querySelector('input[name="detalle_efectivo"]').value = '';
                    document.querySelector('input[name="detalle_qr"]').value = '';
                }
            });

            // 3. Calcular total al seleccionar cuotas (delegación de eventos)
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('cuota-seleccionada')) {
                    let total = 0;
                    document.querySelectorAll('.cuota-seleccionada:checked').forEach(checkbox => {
                        const montoMax = parseFloat(checkbox.getAttribute('data-monto-max'));
                        if (!isNaN(montoMax)) {
                            total += montoMax;
                        }
                    });
                    document.getElementById('pago_bs_input').value = total.toFixed(2);
                }
            });

            // 4. Enviar formulario de pago
            document.getElementById('formRegistrarPago')?.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                // Validar pago parcial
                const tipoPago = document.querySelector('select[name="tipo_pago"]').value;
                if (tipoPago === 'parcial') {
                    const efectivo = parseFloat(document.querySelector('input[name="detalle_efectivo"]')
                        .value) || 0;
                    const qr = parseFloat(document.querySelector('input[name="detalle_qr"]').value) || 0;
                    const total = parseFloat(document.getElementById('pago_bs_input').value) || 0;

                    if (Math.abs(efectivo + qr - total) > 0.01) {
                        showToast('error', 'Error',
                            'La suma de efectivo y QR debe ser igual al monto total');
                        return;
                    }
                }

                // Obtener cuotas seleccionadas
                const cuotasSeleccionadas = [];
                document.querySelectorAll('.cuota-seleccionada:checked').forEach(checkbox => {
                    const cuotaId = checkbox.getAttribute('data-cuota-id');
                    const montoMax = parseFloat(checkbox.getAttribute('data-monto-max'));
                    cuotasSeleccionadas.push({
                        cuota_id: cuotaId,
                        monto: montoMax
                    });
                });

                if (cuotasSeleccionadas.length > 0) {
                    formData.append('cuotas_seleccionadas', JSON.stringify(cuotasSeleccionadas));
                }

                // Validar monto
                const montoTotal = parseFloat(document.getElementById('pago_bs_input').value);
                if (isNaN(montoTotal) || montoTotal <= 0) {
                    showToast('error', 'Error', 'Ingrese un monto total válido');
                    return;
                }

                const submitBtn = this.querySelector('button[type="submit"]');

                // Deshabilitar botón
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span> Procesando...';

                fetch("{{ route('admin.pagos.registrar') }}", {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', 'Éxito', data.msg);
                            // Cerrar modales
                            bootstrap.Modal.getInstance(document.getElementById('modalRegistrarPago'))
                                .hide();
                            bootstrap.Modal.getInstance(document.getElementById('modalVerCuotas'))
                                .hide();
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            showToast('error', 'Error', data.msg || 'Error al registrar el pago');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="ri-check-line me-1"></i> Registrar Pago';
                        }
                    })
                    .catch(error => {
                        showToast('error', 'Error', 'Error al registrar el pago');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="ri-check-line me-1"></i> Registrar Pago';
                    });
            });

            // ===== FUNCIÓN PARA MOSTRAR TOAST =====
            function showToast(type, title, message) {
                // Crear toast dinámico
                const toastId = 'toast-' + Date.now();
                const toastHtml = `
                    <div class="toast align-items-center text-bg-${type} border-0" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <strong>${title}</strong>: ${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `;

                // Agregar al contenedor
                let container = document.querySelector('.toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.className = 'toast-container position-fixed top-0 end-0 p-3';
                    document.body.appendChild(container);
                }
                container.insertAdjacentHTML('beforeend', toastHtml);

                // Mostrar toast
                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement, {
                    animation: true,
                    autohide: true,
                    delay: 3000
                });
                toast.show();

                // Eliminar después de ocultar
                toastElement.addEventListener('hidden.bs.toast', function() {
                    this.remove();
                });
            }
        });
    </script>
    {{-- En la sección de scripts de inscripciones.listar.blade.php --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Variables globales necesarias
        const PLANES_PAGOS = @json($planesPagos ?? []);
        const CONCEPTOS = @json($conceptos ?? []);

        $(document).ready(function() {
            // Inicializar feather icons
            if (typeof window.feather !== 'undefined') {
                window.feather.replace();
            }

            // Función para refrescar feather icons
            function refreshFeather() {
                if (typeof window.feather !== 'undefined') {
                    window.feather.replace();
                }
            }

            // === MODAL PARA CONVERTIR PRE-INSCRITO A INSCRITO ===
            $(document).on('click', '.convertir-inscrito-btn', function() {
                const inscripcionId = $(this).data('inscripcion-id');
                const estudianteId = $(this).data('estudiante-id');
                const ofertaId = $(this).data('oferta-id');

                // Llenar campos del modal
                $('#convertir_inscripcion_id').val(inscripcionId);
                $('#convertir_estudiante_id').val(estudianteId);
                $('#convertir_oferta_id').val(ofertaId);

                // Cargar planes de pago disponibles
                cargarPlanesPagoConvertir(ofertaId);

                // Mostrar modal
                $('#modalConvertirInscrito').modal('show');
            });

            // Cargar planes de pago para la conversión
            function cargarPlanesPagoConvertir(ofertaId) {
                $.ajax({
                    url: `/admin/ofertas/${ofertaId}/datos`,
                    method: 'GET',
                    success: function(res) {
                        let planes = new Set();
                        if (res.plan_concepto) {
                            res.plan_concepto.forEach(pc => planes.add(pc.planes_pago_id));
                        }

                        let opts = '<option value="">Seleccione un plan</option>';
                        PLANES_PAGOS.filter(p => planes.has(p.id)).forEach(p => {
                            opts += `<option value="${p.id}">${p.nombre}</option>`;
                        });
                        $('#convertir_planes_pago_select').html(opts);
                    }
                });
            }

            // Generar vista previa de cuotas para conversión
            $('#convertir_generar-cuotas-btn').on('click', function() {
                const planId = $('#convertir_planes_pago_select').val();
                const ofertaId = $('#convertir_oferta_id').val();

                if (!planId) {
                    alert('Por favor, seleccione un plan de pago');
                    return;
                }

                // Mostrar spinner
                $('#convertir_cuotas-preview-container').html(
                    '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>'
                );

                $.ajax({
                    url: "{{ route('admin.inscripciones.generar-cuotas-preview') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        oferta_id: ofertaId,
                        planes_pago_id: planId
                    },
                    success: function(res) {
                        if (res.success) {
                            renderizarCuotasPreviewConvertir(res.cuotas_preview);
                            $('#convertir_confirmar-cuotas-btn').show();
                            $('#convertir_generar-cuotas-btn').hide();
                        } else {
                            $('#convertir_cuotas-preview-container').html(
                                `<div class="alert alert-danger">${res.msg}</div>`
                            );
                        }
                    }
                });
            });

            // Renderizar vista previa de cuotas en conversión
            function renderizarCuotasPreviewConvertir(cuotas) {
                let html = `
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Concepto</th>
                            <th>N° Cuota</th>
                            <th>Monto por Cuota</th>
                            <th>Fecha de Pago</th>
                        </tr>
                    </thead>
                    <tbody>`;

                cuotas.forEach(cuota => {
                    html += `
                <tr>
                    <td>${cuota.concepto_nombre}</td>
                    <td>${cuota.n_cuota}</td>
                    <td>
                        <input type="number" class="form-control" value="${cuota.pago_total_bs}" readonly>
                    </td>
                    <td>
                        <input type="date" class="form-control fecha-pago-input" 
                               value="${cuota.fecha_pago}" 
                               data-concepto-id="${cuota.concepto_id}" 
                               data-n-cuota="${cuota.n_cuota}">
                    </td>
                </tr>`;
                });

                html += `</tbody></table></div>`;
                $('#convertir_cuotas-preview-container').html(html);
            }

            // Confirmar conversión a inscrito
            $('#convertir_confirmar-cuotas-btn').on('click', function() {
                const inscripcionId = $('#convertir_inscripcion_id').val();
                const planId = $('#convertir_planes_pago_select').val();
                const ofertaId = $('#convertir_oferta_id').val();
                const estudianteId = $('#convertir_estudiante_id').val();

                // Recoger datos de cuotas
                const cuotasData = [];
                $('#convertir_cuotas-preview-container tbody tr').each(function() {
                    const conceptoId = $(this).find('.fecha-pago-input').data('concepto-id');
                    const nCuota = $(this).find('.fecha-pago-input').data('n-cuota');
                    const fechaPago = $(this).find('.fecha-pago-input').val();
                    const montoPorCuota = parseFloat($(this).find('input[type="number"]').val());

                    cuotasData.push({
                        concepto_id: conceptoId,
                        n_cuota: nCuota,
                        fecha_pago: fechaPago,
                        monto_bs: montoPorCuota
                    });
                });

                // Primero confirmar las cuotas
                $.ajax({
                    url: "{{ route('admin.inscripciones.confirmar-cuotas') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        oferta_id: ofertaId,
                        estudiante_id: estudianteId,
                        planes_pago_id: planId,
                        cuotas_data: cuotasData
                    },
                    success: function(res) {
                        if (res.success) {
                            // Ahora convertir la inscripción
                            $.ajax({
                                url: "{{ route('admin.inscripciones.convertir-a-inscrito') }}",
                                method: 'POST',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    inscripcion_id: inscripcionId,
                                    planes_pago_id: planId
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

            // Filtro de estado
            $('#filter-estado').on('change', function() {
                const estado = $(this).val();
                $('.inscripcion-row').each(function() {
                    const rowEstado = $(this).data('estado');
                    if (!estado || rowEstado === estado) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Buscador por nombre
            $('#search-nombre').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('.inscripcion-row').each(function() {
                    const nombre = $(this).data('nombre').toLowerCase();
                    if (nombre.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
@endpush
