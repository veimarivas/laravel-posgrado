@extends('admin.dashboard')

@section('admin')
    <!-- Encabezado de la Oferta -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">Dashboard Académico</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.ofertas.listar') }}">Ofertas</a></li>
                            <li class="breadcrumb-item active">{{ $oferta->codigo }}</li>
                        </ol>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill fs-12 p-2"
                        style="background-color: {{ $oferta->color }}20; color: {{ $oferta->color }}; border: 1px solid {{ $oferta->color }}40;">
                        <i class="ri-bookmark-fill align-middle me-1"></i> {{ $oferta->fase->nombre ?? 'Sin fase' }}
                    </span>
                    <a href="{{ route('admin.ofertas.vermodulos', $oferta->id) }}" class="btn btn-light btn-sm">
                        <i class="ri-calendar-2-line align-middle me-1"></i> Módulos
                    </a>
                    <a href="{{ route('admin.ofertas.planes-pago', $oferta->id) }}" class="btn btn-light btn-sm">
                        <i class="ri-money-dollar-circle-line align-middle me-1"></i> Planes de Pago
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Información Principal de la Oferta -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h3 class="card-title mb-1">{{ $oferta->programa->nombre ?? 'Programa' }}</h3>
                            <p class="text-muted mb-0">
                                <i class="ri-code-s-slash-line align-middle me-1"></i>
                                <strong>Código:</strong> {{ $oferta->codigo }}
                                <span class="mx-2">•</span>
                                <i class="ri-building-line align-middle me-1"></i>
                                {{ $oferta->sucursal->nombre ?? 'Sin sucursal' }}
                            </p>
                        </div>
                        <div class="avatar-xl">
                            @if ($oferta->portada)
                                <img src="{{ asset($oferta->portada) }}" alt="Portada" class="img-fluid rounded"
                                    style="max-height: 80px;">
                            @else
                                <div class="avatar-title bg-light rounded" style="width: 80px; height: 80px;">
                                    <i class="ri-book-2-line fs-24 text-primary"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded fs-16">
                                            <i class="ri-calendar-event-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0 fs-13">Inicio de Programa</h6>
                                    <p class="text-muted mb-0 fs-12">
                                        {{ $oferta->fecha_inicio_programa?->format('d/m/Y') ?? 'No definido' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <div class="avatar-title bg-info-subtle text-info rounded fs-16">
                                            <i class="ri-group-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0 fs-13">Modalidad</h6>
                                    <p class="text-muted mb-0 fs-12">{{ $oferta->modalidad->nombre ?? 'No definida' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <div class="avatar-title bg-warning-subtle text-warning rounded fs-16">
                                            <i class="ri-bar-chart-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0 fs-13">Nota Mínima</h6>
                                    <p class="text-muted mb-0 fs-12">{{ $oferta->nota_minima ?? 61 }} pts</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-primary bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0 text-primary">Resumen Académico</h5>
                        <div class="avatar-sm">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <i class="ri-graduation-cap-line"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="border rounded p-2 text-center">
                                <h3 class="mb-0 text-success">{{ $totalInscritos }}</h3>
                                <p class="text-muted mb-0 fs-12">Inscritos Activos</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2 text-center">
                                <h3 class="mb-0 text-warning">{{ $totalPreInscritos }}</h3>
                                <p class="text-muted mb-0 fs-12">Pre-Inscritos</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2 text-center">
                                <h6 class="mb-0">{{ $oferta->modulos->count() }}</h6>
                                <p class="text-muted mb-0 fs-12">Módulos</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2 text-center">
                                <h6 class="mb-0">{{ $hombres + $mujeres }}</h6>
                                <p class="text-muted mb-0 fs-12">Total Estudiantes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pestañas de Navegación -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#tab-resumen" role="tab">
                                <i class="ri-dashboard-line align-middle me-1"></i> Resumen
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-finanzas" role="tab">
                                <i class="ri-money-dollar-circle-line align-middle me-1"></i> Finanzas
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-academico" role="tab">
                                <i class="ri-book-open-line align-middle me-1"></i> Académico
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-demografico" role="tab">
                                <i class="ri-user-line align-middle me-1"></i> Demográfico
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Pestaña 1: Resumen -->
                        <!-- Pestaña 1: Resumen - MODIFICADA -->
                        <div class="tab-pane fade show active" id="tab-resumen" role="tabpanel">
                            <div class="row">
                                <!-- Estadísticas Rápidas -->
                                <div class="col-lg-3">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <h5 class="card-title mb-0 fs-16">
                                                <i class="ri-dashboard-line align-middle me-2"></i>
                                                Resumen Rápido
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <div class="border rounded p-3 text-center bg-success-subtle">
                                                        <h4 class="mb-1 text-success">{{ $totalInscritos }}</h4>
                                                        <p class="text-muted mb-0 fs-12">Inscritos Activos</p>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="border rounded p-3 text-center bg-warning-subtle">
                                                        <h4 class="mb-1 text-warning">{{ $totalPreInscritos }}</h4>
                                                        <p class="text-muted mb-0 fs-12">Pre-Inscritos</p>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="border rounded p-3 text-center bg-info-subtle">
                                                        <h4 class="mb-1 text-info">{{ number_format($totalRecaudado, 2) }}
                                                            Bs</h4>
                                                        <p class="text-muted mb-0 fs-12">Total Recaudado</p>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="border rounded p-3 text-center bg-danger-subtle">
                                                        <h4 class="mb-1 text-danger">{{ number_format($totalDeuda, 2) }}
                                                            Bs</h4>
                                                        <p class="text-muted mb-0 fs-12">Deuda Pendiente</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Gráfico de Inscripciones por Mes -->
                                <div class="col-lg-5">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <h5 class="card-title mb-0 fs-16">
                                                <i class="ri-bar-chart-line align-middle me-2"></i>
                                                Inscripciones Mensuales
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="inscripcionesChart" height="150"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <!-- Distribución por Estado -->
                                <div class="col-lg-4">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <h5 class="card-title mb-0 fs-16">
                                                <i class="ri-pie-chart-line align-middle me-2"></i>
                                                Distribución por Estado
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-7">
                                                    <canvas id="estadoChart" height="180"></canvas>
                                                </div>
                                                <div class="col-5">
                                                    <div class="mt-3">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div class="flex-shrink-0">
                                                                <div class="avatar-xs">
                                                                    <div class="avatar-title rounded-circle bg-success">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1 ms-2">
                                                                <p class="text-muted mb-0 fs-12">Inscritos</p>
                                                                <h6 class="mb-0">{{ $totalInscritos }}</h6>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div class="flex-shrink-0">
                                                                <div class="avatar-xs">
                                                                    <div class="avatar-title rounded-circle bg-warning">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1 ms-2">
                                                                <p class="text-muted mb-0 fs-12">Pre-Inscritos</p>
                                                                <h6 class="mb-0">{{ $totalPreInscritos }}</h6>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <div class="avatar-xs">
                                                                    <div class="avatar-title rounded-circle bg-light">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1 ms-2">
                                                                <p class="text-muted mb-0 fs-12">Total</p>
                                                                <h6 class="mb-0">
                                                                    {{ $totalInscritos + $totalPreInscritos }}</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabla de Pre-Inscritos con Asesor -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card border">
                                        <div
                                            class="card-header border-bottom bg-light d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0 fs-16">
                                                <i class="ri-user-add-line align-middle me-2"></i>
                                                Pre-Inscritos y sus Asesores
                                            </h5>
                                            <span class="badge bg-warning">{{ count($preInscritosConAsesor) }}
                                                registros</span>
                                        </div>
                                        <div class="card-body">
                                            @if (count($preInscritosConAsesor) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover align-middle mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th width="5%">#</th>
                                                                <th width="25%">Estudiante</th>
                                                                <th width="15%">Carnet</th>
                                                                <th width="25%">Asesor</th>
                                                                <th width="20%">Fecha de Registro</th>
                                                                <th width="10%" class="text-center">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($preInscritosConAsesor as $index => $preInscrito)
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar-xs me-2">
                                                                                <div
                                                                                    class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                                                                    {{ substr(trim($preInscrito['estudiante']), 0, 1) }}
                                                                                </div>
                                                                            </div>
                                                                            <span
                                                                                class="fw-medium">{{ $preInscrito['estudiante'] }}</span>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            class="badge bg-secondary">{{ $preInscrito['carnet'] }}</span>
                                                                    </td>
                                                                    <td>
                                                                        @if ($preInscrito['asesor_persona_id'])
                                                                            <a href="{{ route('admin.vendedor.inscripciones', $preInscrito['asesor_persona_id']) }}"
                                                                                class="text-decoration-none d-flex align-items-center">
                                                                                <div class="avatar-xs me-2">
                                                                                    <div
                                                                                        class="avatar-title bg-info-subtle text-info rounded-circle">
                                                                                        <i class="ri-user-line"></i>
                                                                                    </div>
                                                                                </div>
                                                                                <span
                                                                                    class="text-info fw-medium">{{ $preInscrito['asesor'] }}</span>
                                                                            </a>
                                                                        @else
                                                                            <span class="text-muted">Sin asesor</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            class="text-muted">{{ \Carbon\Carbon::parse($preInscrito['fecha_registro'])->format('d/m/Y H:i') }}</span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <a href="{{ route('admin.estudiantes.detalle', $preInscrito['estudiante_id']) }}"
                                                                            class="btn btn-sm btn-light"
                                                                            title="Ver detalle">
                                                                            <i class="ri-eye-line"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="text-center py-5">
                                                    <div class="avatar-lg mx-auto mb-3">
                                                        <div class="avatar-title bg-light text-warning rounded-circle">
                                                            <i class="ri-user-line fs-24"></i>
                                                        </div>
                                                    </div>
                                                    <h5 class="text-muted">No hay pre-inscritos en esta oferta</h5>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña 2: Finanzas (MODIFICADA) -->
                        <div class="tab-pane fade" id="tab-finanzas" role="tabpanel">
                            <!-- Sección: Resumen por Concepto -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <h5 class="card-title mb-0 fs-16">
                                                <i class="ri-money-dollar-circle-line align-middle me-2"></i>
                                                Resumen Financiero por Concepto
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                @foreach ($resumenPorConcepto as $concepto => $datos)
                                                    @php
                                                        $color = match ($concepto) {
                                                            'Matrícula' => 'primary',
                                                            'Colegiatura' => 'info',
                                                            'Certificación' => 'warning',
                                                            default => 'secondary',
                                                        };
                                                        $icono = match ($concepto) {
                                                            'Matrícula' => 'ri-file-text-line',
                                                            'Colegiatura' => 'ri-calendar-line',
                                                            'Certificación' => 'ri-award-line',
                                                            default => 'ri-money-dollar-circle-line',
                                                        };
                                                    @endphp
                                                    <div class="col-lg-4">
                                                        <div
                                                            class="card border border-{{ $color }} bg-{{ $color }}-subtle">
                                                            <div class="card-body">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center mb-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="avatar-xs me-3">
                                                                            <div
                                                                                class="avatar-title bg-{{ $color }} text-white rounded">
                                                                                <i class="{{ $icono }}"></i>
                                                                            </div>
                                                                        </div>
                                                                        <h5 class="mb-0 text-{{ $color }}">
                                                                            {{ $concepto }}</h5>
                                                                    </div>
                                                                    <span class="badge bg-{{ $color }}">
                                                                        {{ number_format($datos['porcentaje'], 1) }}%
                                                                    </span>
                                                                </div>

                                                                <!-- Montos -->
                                                                <div class="row g-2">
                                                                    <div class="col-12">
                                                                        <div class="border rounded p-2 bg-white">
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center">
                                                                                <span class="text-muted fs-12">Total</span>
                                                                                <span
                                                                                    class="fw-bold fs-13">{{ number_format($datos['total'], 2) }}
                                                                                    Bs</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="border rounded p-2 bg-success-subtle">
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center">
                                                                                <span
                                                                                    class="text-success fs-12">Pagado</span>
                                                                                <span
                                                                                    class="fw-bold text-success fs-13">{{ number_format($datos['pagado'], 2) }}
                                                                                    Bs</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="border rounded p-2 bg-danger-subtle">
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center">
                                                                                <span
                                                                                    class="text-danger fs-12">Pendiente</span>
                                                                                <span
                                                                                    class="fw-bold text-danger fs-13">{{ number_format($datos['pendiente'], 2) }}
                                                                                    Bs</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Barra de progreso -->
                                                                <div class="mt-3">
                                                                    <div class="progress" style="height: 8px;">
                                                                        <div class="progress-bar bg-success"
                                                                            role="progressbar"
                                                                            style="width: {{ $datos['porcentaje'] }}%">
                                                                        </div>
                                                                        <div class="progress-bar bg-danger"
                                                                            role="progressbar"
                                                                            style="width: {{ 100 - $datos['porcentaje'] }}%">
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mt-1">
                                                                        <small
                                                                            class="text-success">{{ number_format($datos['porcentaje'], 1) }}%
                                                                            Cobrado</small>
                                                                        <small
                                                                            class="text-danger">{{ number_format(100 - $datos['porcentaje'], 1) }}%
                                                                            Pendiente</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección: Gráficos por Concepto -->
                            <div class="row mb-4">
                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <h5 class="card-title mb-0 fs-16">
                                                <i class="ri-pie-chart-line align-middle me-2"></i>
                                                Distribución de Ingresos por Concepto
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="ingresosConceptoChart" height="250"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <h5 class="card-title mb-0 fs-16">
                                                <i class="ri-bar-chart-horizontal-line align-middle me-2"></i>
                                                Estado de Cobranza por Concepto
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="cobranzaConceptoChart" height="250"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabla de Participantes con Estado Financiero -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title mb-0 fs-16">
                                                    <i class="ri-user-line align-middle me-2"></i>
                                                    Estado Financiero de Participantes
                                                </h5>
                                                <button type="button" class="btn btn-light btn-sm" id="exportarTabla">
                                                    <i class="ri-download-line align-middle me-1"></i> Exportar Excel
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover align-middle mb-0" id="tablaFinanzas">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th class="text-center" width="4%">#</th>
                                                            <th width="16%">Estudiante</th>
                                                            <th class="text-center" width="8%">Carnet</th>
                                                            <th class="text-center" width="10%">Plan de Pago</th>
                                                            <th class="text-center" width="12%">Vendedor</th>
                                                            <th class="text-end" width="8%">Total Plan (Bs)</th>

                                                            <!-- Columnas en ORDEN FIJO -->
                                                            <th class="text-end" width="10%">Matrícula (Bs)</th>
                                                            <th class="text-end" width="10%">Colegiatura (Bs)</th>
                                                            <th class="text-end" width="10%">Certificación (Bs)</th>

                                                            <th class="text-end" width="8%">Total Pagado (Bs)</th>
                                                            <th class="text-end" width="8%">Saldo Deuda (Bs)</th>
                                                            <th class="text-center" width="6%">% Pagado</th>
                                                            <th width="8%">Progreso</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($participantesFinanzas as $index => $participante)
                                                            @php
                                                                $color = match (true) {
                                                                    $participante['porcentaje_pagado'] >= 100
                                                                        => 'success',
                                                                    $participante['porcentaje_pagado'] >= 70 => 'info',
                                                                    $participante['porcentaje_pagado'] >= 50
                                                                        => 'warning',
                                                                    default => 'danger',
                                                                };
                                                            @endphp
                                                            <tr>
                                                                <td class="text-center">{{ $index + 1 }}</td>
                                                                <td>
                                                                    <a href="{{ route('admin.estudiantes.detalle', $participante['estudiante_id']) }}"
                                                                        class="text-decoration-none">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar-xs">
                                                                                <div
                                                                                    class="avatar-title bg-light text-primary rounded-circle">
                                                                                    {{ substr(trim($participante['estudiante']), 0, 1) }}
                                                                                </div>
                                                                            </div>
                                                                            <div class="ms-2">
                                                                                <h6 class="mb-0 fs-14">
                                                                                    {{ $participante['estudiante'] }}</h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span
                                                                        class="badge bg-secondary">{{ $participante['carnet'] }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span
                                                                        class="badge bg-primary">{{ $participante['plan_pago'] }}</span>
                                                                </td>
                                                                <!-- En la tabla de participantes financieros, modificar la columna del vendedor -->
                                                                <td class="text-center">
                                                                    @if ($participante['vendedor_persona_id'] ?? null)
                                                                        <a href="{{ route('admin.vendedor.inscripciones', $participante['vendedor_persona_id']) }}"
                                                                            class="badge bg-info d-flex align-items-center justify-content-center gap-1"
                                                                            title="Ver inscripciones del vendedor">
                                                                            <i class="ri-user-line"></i>
                                                                            {{ $participante['vendedor'] ?? 'N/A' }}
                                                                        </a>
                                                                    @else
                                                                        <span class="badge bg-info"
                                                                            title="Vendedor que realizó la inscripción">
                                                                            {{ $participante['vendedor'] ?? 'N/A' }}
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-end fw-bold">
                                                                    {{ number_format($participante['total_plan'], 2) }}
                                                                </td>

                                                                <!-- Matrícula -->
                                                                <td class="text-end">
                                                                    @php $matricula = $participante['conceptos']['Matrícula']; @endphp
                                                                    <div class="d-flex flex-column">
                                                                        @if ($matricula['total'] > 0)
                                                                            <span class="text-success fw-bold">
                                                                                {{ number_format($matricula['pagado'], 2) }}
                                                                            </span>
                                                                            <div class="d-flex justify-content-between">
                                                                                <small class="text-muted">Total:</small>
                                                                                <small
                                                                                    class="text-muted">{{ number_format($matricula['total'], 2) }}</small>
                                                                            </div>
                                                                            @if ($matricula['pendiente'] > 0)
                                                                                <div
                                                                                    class="d-flex justify-content-between">
                                                                                    <small
                                                                                        class="text-danger">Pendiente:</small>
                                                                                    <small
                                                                                        class="text-danger">{{ number_format($matricula['pendiente'], 2) }}</small>
                                                                                </div>
                                                                            @endif
                                                                            @if ($matricula['n_cuotas'] > 1)
                                                                                <div
                                                                                    class="d-flex justify-content-between">
                                                                                    <small
                                                                                        class="text-info">Cuotas:</small>
                                                                                    <small
                                                                                        class="text-info">{{ $matricula['n_cuotas'] }}</small>
                                                                                </div>
                                                                            @endif
                                                                        @else
                                                                            <span class="text-muted">-</span>
                                                                        @endif
                                                                    </div>
                                                                </td>

                                                                <!-- Colegiatura -->
                                                                <td class="text-end">
                                                                    @php $colegiatura = $participante['conceptos']['Colegiatura']; @endphp
                                                                    <div class="d-flex flex-column">
                                                                        @if ($colegiatura['total'] > 0)
                                                                            <span class="text-success fw-bold">
                                                                                {{ number_format($colegiatura['pagado'], 2) }}
                                                                            </span>
                                                                            <div class="d-flex justify-content-between">
                                                                                <small class="text-muted">Total:</small>
                                                                                <small
                                                                                    class="text-muted">{{ number_format($colegiatura['total'], 2) }}</small>
                                                                            </div>
                                                                            @if ($colegiatura['pendiente'] > 0)
                                                                                <div
                                                                                    class="d-flex justify-content-between">
                                                                                    <small
                                                                                        class="text-danger">Pendiente:</small>
                                                                                    <small
                                                                                        class="text-danger">{{ number_format($colegiatura['pendiente'], 2) }}</small>
                                                                                </div>
                                                                            @endif
                                                                            @if ($colegiatura['n_cuotas'] > 1)
                                                                                <div
                                                                                    class="d-flex justify-content-between">
                                                                                    <small
                                                                                        class="text-info">Cuotas:</small>
                                                                                    <small
                                                                                        class="text-info">{{ $colegiatura['n_cuotas'] }}</small>
                                                                                </div>
                                                                            @endif
                                                                        @else
                                                                            <span class="text-muted">-</span>
                                                                        @endif
                                                                    </div>
                                                                </td>

                                                                <!-- Certificación -->
                                                                <td class="text-end">
                                                                    @php $certificacion = $participante['conceptos']['Certificación']; @endphp
                                                                    <div class="d-flex flex-column">
                                                                        @if ($certificacion['total'] > 0)
                                                                            <span class="text-success fw-bold">
                                                                                {{ number_format($certificacion['pagado'], 2) }}
                                                                            </span>
                                                                            <div class="d-flex justify-content-between">
                                                                                <small class="text-muted">Total:</small>
                                                                                <small
                                                                                    class="text-muted">{{ number_format($certificacion['total'], 2) }}</small>
                                                                            </div>
                                                                            @if ($certificacion['pendiente'] > 0)
                                                                                <div
                                                                                    class="d-flex justify-content-between">
                                                                                    <small
                                                                                        class="text-danger">Pendiente:</small>
                                                                                    <small
                                                                                        class="text-danger">{{ number_format($certificacion['pendiente'], 2) }}</small>
                                                                                </div>
                                                                            @endif
                                                                            @if ($certificacion['n_cuotas'] > 1)
                                                                                <div
                                                                                    class="d-flex justify-content-between">
                                                                                    <small
                                                                                        class="text-info">Cuotas:</small>
                                                                                    <small
                                                                                        class="text-info">{{ $certificacion['n_cuotas'] }}</small>
                                                                                </div>
                                                                            @endif
                                                                        @else
                                                                            <span class="text-muted">-</span>
                                                                        @endif
                                                                    </div>
                                                                </td>

                                                                <td class="text-end">
                                                                    <span class="fw-bold text-success">
                                                                        {{ number_format($participante['total_pagado'], 2) }}
                                                                    </span>
                                                                </td>
                                                                <td class="text-end">
                                                                    <span class="fw-bold text-danger">
                                                                        {{ number_format($participante['saldo'], 2) }}
                                                                    </span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span
                                                                        class="badge bg-{{ $color }}-subtle text-{{ $color }}">
                                                                        {{ number_format($participante['porcentaje_pagado'], 1) }}%
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <div class="progress" style="height: 6px;">
                                                                        <div class="progress-bar bg-{{ $color }}"
                                                                            role="progressbar"
                                                                            style="width: {{ $participante['porcentaje_pagado'] }}%;"
                                                                            aria-valuenow="{{ $participante['porcentaje_pagado'] }}"
                                                                            aria-valuemin="0" aria-valuemax="100">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <!-- En la sección de tfoot, reemplaza los cálculos con: -->
                                                    <tfoot class="table-light">
                                                        <tr>
                                                            <td colspan="5" class="fw-bold text-end">TOTALES:</td>
                                                            <td class="text-end fw-bold">
                                                                {{ number_format(collect($participantesFinanzas)->sum('total_plan'), 2) }}
                                                            </td>

                                                            <!-- Totales Matrícula - CALCULAR BASADO EN CUOTAS REALES -->
                                                            @php
                                                                $totalMatricula = 0;
                                                                $pagadoMatricula = 0;
                                                                $pendienteMatricula = 0;
                                                                $cuotasMatricula = 0;

                                                                foreach ($participantesFinanzas as $participante) {
                                                                    $matricula =
                                                                        $participante['conceptos']['Matrícula'];
                                                                    $totalMatricula += $matricula['total'];
                                                                    $pagadoMatricula += $matricula['pagado'];
                                                                    $pendienteMatricula += $matricula['pendiente'];
                                                                    $cuotasMatricula += $matricula['n_cuotas'];
                                                                }
                                                            @endphp
                                                            <td class="text-end fw-bold">
                                                                <div class="d-flex flex-column">
                                                                    <span class="text-success">
                                                                        {{ number_format($pagadoMatricula, 2) }}
                                                                    </span>
                                                                    <div class="d-flex justify-content-between">
                                                                        <small class="text-muted">Total:</small>
                                                                        <small
                                                                            class="text-muted">{{ number_format($totalMatricula, 2) }}</small>
                                                                    </div>
                                                                    @if ($pendienteMatricula > 0)
                                                                        <div class="d-flex justify-content-between">
                                                                            <small class="text-danger">Pendiente:</small>
                                                                            <small
                                                                                class="text-danger">{{ number_format($pendienteMatricula, 2) }}</small>
                                                                        </div>
                                                                    @endif
                                                                    @if ($cuotasMatricula > 0)
                                                                        <div class="d-flex justify-content-between">
                                                                            <small class="text-info">Total Cuotas:</small>
                                                                            <small
                                                                                class="text-info">{{ $cuotasMatricula }}</small>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </td>

                                                            <!-- Totales Colegiatura -->
                                                            @php
                                                                $totalColegiatura = 0;
                                                                $pagadoColegiatura = 0;
                                                                $pendienteColegiatura = 0;
                                                                $cuotasColegiatura = 0;

                                                                foreach ($participantesFinanzas as $participante) {
                                                                    $colegiatura =
                                                                        $participante['conceptos']['Colegiatura'];
                                                                    $totalColegiatura += $colegiatura['total'];
                                                                    $pagadoColegiatura += $colegiatura['pagado'];
                                                                    $pendienteColegiatura += $colegiatura['pendiente'];
                                                                    $cuotasColegiatura += $colegiatura['n_cuotas'];
                                                                }
                                                            @endphp
                                                            <td class="text-end fw-bold">
                                                                <div class="d-flex flex-column">
                                                                    <span class="text-success">
                                                                        {{ number_format($pagadoColegiatura, 2) }}
                                                                    </span>
                                                                    <div class="d-flex justify-content-between">
                                                                        <small class="text-muted">Total:</small>
                                                                        <small
                                                                            class="text-muted">{{ number_format($totalColegiatura, 2) }}</small>
                                                                    </div>
                                                                    @if ($pendienteColegiatura > 0)
                                                                        <div class="d-flex justify-content-between">
                                                                            <small class="text-danger">Pendiente:</small>
                                                                            <small
                                                                                class="text-danger">{{ number_format($pendienteColegiatura, 2) }}</small>
                                                                        </div>
                                                                    @endif
                                                                    @if ($cuotasColegiatura > 0)
                                                                        <div class="d-flex justify-content-between">
                                                                            <small class="text-info">Total Cuotas:</small>
                                                                            <small
                                                                                class="text-info">{{ $cuotasColegiatura }}</small>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </td>

                                                            <!-- Totales Certificación -->
                                                            @php
                                                                $totalCertificacion = 0;
                                                                $pagadoCertificacion = 0;
                                                                $pendienteCertificacion = 0;
                                                                $cuotasCertificacion = 0;

                                                                foreach ($participantesFinanzas as $participante) {
                                                                    $certificacion =
                                                                        $participante['conceptos']['Certificación'];
                                                                    $totalCertificacion += $certificacion['total'];
                                                                    $pagadoCertificacion += $certificacion['pagado'];
                                                                    $pendienteCertificacion +=
                                                                        $certificacion['pendiente'];
                                                                    $cuotasCertificacion += $certificacion['n_cuotas'];
                                                                }
                                                            @endphp
                                                            <td class="text-end fw-bold">
                                                                <div class="d-flex flex-column">
                                                                    <span class="text-success">
                                                                        {{ number_format($pagadoCertificacion, 2) }}
                                                                    </span>
                                                                    <div class="d-flex justify-content-between">
                                                                        <small class="text-muted">Total:</small>
                                                                        <small
                                                                            class="text-muted">{{ number_format($totalCertificacion, 2) }}</small>
                                                                    </div>
                                                                    @if ($pendienteCertificacion > 0)
                                                                        <div class="d-flex justify-content-between">
                                                                            <small class="text-danger">Pendiente:</small>
                                                                            <small
                                                                                class="text-danger">{{ number_format($pendienteCertificacion, 2) }}</small>
                                                                        </div>
                                                                    @endif
                                                                    @if ($cuotasCertificacion > 0)
                                                                        <div class="d-flex justify-content-between">
                                                                            <small class="text-info">Total Cuotas:</small>
                                                                            <small
                                                                                class="text-info">{{ $cuotasCertificacion }}</small>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </td>

                                                            <!-- Resto del código permanece igual -->
                                                            <td class="text-end fw-bold text-success">
                                                                {{ number_format(collect($participantesFinanzas)->sum('total_pagado'), 2) }}
                                                            </td>
                                                            <td class="text-end fw-bold text-danger">
                                                                {{ number_format(collect($participantesFinanzas)->sum('saldo'), 2) }}
                                                            </td>
                                                            <td class="text-center">
                                                                @php
                                                                    $totalGeneral = collect(
                                                                        $participantesFinanzas,
                                                                    )->sum('total_plan');
                                                                    $pagadoGeneral = collect(
                                                                        $participantesFinanzas,
                                                                    )->sum('total_pagado');
                                                                    $porcentajeGeneral =
                                                                        $totalGeneral > 0
                                                                            ? ($pagadoGeneral / $totalGeneral) * 100
                                                                            : 0;
                                                                @endphp
                                                                <span
                                                                    class="badge bg-primary">{{ number_format($porcentajeGeneral, 1) }}%</span>
                                                            </td>
                                                            <td>
                                                                <div class="progress" style="height: 6px;">
                                                                    <div class="progress-bar bg-primary"
                                                                        style="width: {{ $porcentajeGeneral }}%;"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña 3: Académico -->
                        <div class="tab-pane fade" id="tab-academico" role="tabpanel">
                            <div class="card border">
                                <div class="card-header border-bottom bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0 fs-16">
                                            <i class="ri-book-open-line align-middle me-2"></i>
                                            Rendimiento Académico
                                        </h5>
                                        <div>
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="ri-information-line align-middle me-1"></i>
                                                Nota mínima: {{ $oferta->nota_minima ?? 61 }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover align-middle mb-0">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th rowspan="2" class="text-center align-middle" width="5%">#
                                                    </th>
                                                    <th rowspan="2" class="align-middle">Estudiante</th>
                                                    <th rowspan="2" class="text-center align-middle" width="10%">
                                                        Carnet</th>
                                                    <th colspan="{{ $oferta->modulos->count() }}" class="text-center">
                                                        Módulos</th>
                                                    <th rowspan="2" class="text-center align-middle" width="8%">
                                                        Prom.</th>
                                                </tr>
                                                <tr>
                                                    @foreach ($oferta->modulos as $modulo)
                                                        <th class="text-center modulo-header"
                                                            data-modulo-id="{{ $modulo->id }}"
                                                            data-oferta-id="{{ $oferta->id }}"
                                                            title="{{ $modulo->nombre }}"
                                                            style="cursor: pointer; min-width: 80px;">
                                                            <div>M{{ $modulo->n_modulo }}</div>
                                                            <small
                                                                class="text-muted fs-10">{{ Str::limit($modulo->nombre, 12) }}</small>
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($tablaAcademica as $index => $fila)
                                                    @php
                                                        $promedioEstudiante = 0;
                                                        $totalModulos = 0;
                                                        foreach ($oferta->modulos as $modulo) {
                                                            $nota =
                                                                $fila['modulos'][$modulo->nombre]['nota_regular'] ??
                                                                null;
                                                            if ($nota !== null) {
                                                                $promedioEstudiante += $nota;
                                                                $totalModulos++;
                                                            }
                                                        }
                                                        $promedioEstudiante =
                                                            $totalModulos > 0 ? $promedioEstudiante / $totalModulos : 0;

                                                        $colorPromedio = match (true) {
                                                            $promedioEstudiante >= 90 => 'success',
                                                            $promedioEstudiante >= 80 => 'info',
                                                            $promedioEstudiante >= 70 => 'warning',
                                                            $promedioEstudiante >= 60 => 'primary',
                                                            $promedioEstudiante > 0 => 'danger',
                                                            default => 'secondary',
                                                        };
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.estudiantes.detalle', $fila['estudiante_id']) }}"
                                                                class="text-decoration-none">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <div class="avatar-xs">
                                                                            <div
                                                                                class="avatar-title bg-light text-primary rounded-circle">
                                                                                {{ substr(trim($fila['estudiante']), 0, 1) }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-2">
                                                                        <h6 class="mb-0 fs-14">{{ $fila['estudiante'] }}
                                                                        </h6>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-secondary">{{ $fila['carnet'] }}</span>
                                                        </td>
                                                        @foreach ($oferta->modulos as $modulo)
                                                            @php
                                                                $nota =
                                                                    $fila['modulos'][$modulo->nombre]['nota_regular'] ??
                                                                    null;
                                                                $notaNivelacion =
                                                                    $fila['modulos'][$modulo->nombre][
                                                                        'nota_nivelacion'
                                                                    ] ?? null;

                                                                $color = match (true) {
                                                                    $nota >= 90 => 'success',
                                                                    $nota >= 80 => 'info',
                                                                    $nota >= 70 => 'warning',
                                                                    $nota >= 60 => 'primary',
                                                                    $nota !== null => 'danger',
                                                                    default => 'secondary',
                                                                };
                                                            @endphp
                                                            <td class="text-center modulo-cell"
                                                                data-modulo-id="{{ $modulo->id }}"
                                                                data-oferta-id="{{ $oferta->id }}"
                                                                style="cursor: pointer;">
                                                                @if ($nota !== null)
                                                                    <div class="d-flex flex-column align-items-center">
                                                                        <span
                                                                            class="badge bg-{{ $color }} rounded-pill px-2 py-1">
                                                                            {{ $nota }}
                                                                        </span>
                                                                        @if ($notaNivelacion)
                                                                            <small class="text-muted fs-10"
                                                                                title="Nota de nivelación">
                                                                                N: {{ $notaNivelacion }}
                                                                            </small>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <span class="badge bg-light text-muted">--</span>
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                        <td class="text-center">
                                                            @if ($promedioEstudiante > 0)
                                                                <span
                                                                    class="badge bg-{{ $colorPromedio }} rounded-pill px-2 py-1">
                                                                    {{ number_format($promedioEstudiante, 1) }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-light text-muted">--</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <td colspan="3" class="fw-bold text-end">Promedio por módulo:</td>
                                                    @foreach ($oferta->modulos as $modulo)
                                                        @php
                                                            $notasModulo = collect($tablaAcademica)
                                                                ->map(function ($fila) use ($modulo) {
                                                                    return $fila['modulos'][$modulo->nombre][
                                                                        'nota_regular'
                                                                    ] ?? null;
                                                                })
                                                                ->filter(function ($nota) {
                                                                    return $nota !== null;
                                                                });
                                                            $promedio =
                                                                $notasModulo->count() > 0 ? $notasModulo->avg() : 0;
                                                            $promedioColor = match (true) {
                                                                $promedio >= 90 => 'success',
                                                                $promedio >= 80 => 'info',
                                                                $promedio >= 70 => 'warning',
                                                                $promedio >= 60 => 'primary',
                                                                $promedio > 0 => 'danger',
                                                                default => 'secondary',
                                                            };
                                                        @endphp
                                                        <td class="text-center">
                                                            @if ($notasModulo->count() > 0)
                                                                <div class="d-flex flex-column align-items-center">
                                                                    <span
                                                                        class="badge bg-{{ $promedioColor }} rounded-pill px-2">
                                                                        {{ number_format($promedio, 1) }}
                                                                    </span>
                                                                    <small
                                                                        class="text-muted fs-10">{{ $notasModulo->count() }}
                                                                        est.</small>
                                                                </div>
                                                            @else
                                                                <span class="badge bg-light text-muted">--</span>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                    <td class="text-center">
                                                        @php
                                                            $totalPromedios = 0;
                                                            $totalModulosConNotas = 0;
                                                            foreach ($oferta->modulos as $modulo) {
                                                                $notasModulo = collect($tablaAcademica)
                                                                    ->map(function ($fila) use ($modulo) {
                                                                        return $fila['modulos'][$modulo->nombre][
                                                                            'nota_regular'
                                                                        ] ?? null;
                                                                    })
                                                                    ->filter(function ($nota) {
                                                                        return $nota !== null;
                                                                    });
                                                                if ($notasModulo->count() > 0) {
                                                                    $totalPromedios += $notasModulo->avg();
                                                                    $totalModulosConNotas++;
                                                                }
                                                            }
                                                            $promedioGeneral =
                                                                $totalModulosConNotas > 0
                                                                    ? $totalPromedios / $totalModulosConNotas
                                                                    : 0;
                                                            $promedioGeneralColor = match (true) {
                                                                $promedioGeneral >= 90 => 'success',
                                                                $promedioGeneral >= 80 => 'info',
                                                                $promedioGeneral >= 70 => 'warning',
                                                                $promedioGeneral >= 60 => 'primary',
                                                                $promedioGeneral > 0 => 'danger',
                                                                default => 'secondary',
                                                            };
                                                        @endphp
                                                        <span
                                                            class="badge bg-{{ $promedioGeneralColor }} rounded-pill px-2 py-1">
                                                            {{ number_format($promedioGeneral, 1) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña 4: Demográfico - MEJORADA -->
                        <div class="tab-pane fade" id="tab-demografico" role="tabpanel">
                            <div class="row">
                                <!-- Distribución por Sexo -->
                                <div class="col-lg-4">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title mb-0 fs-16">
                                                    <i class="ri-user-line align-middle me-2"></i>
                                                    Distribución por Género
                                                </h5>
                                                <span
                                                    class="badge bg-primary">{{ $estadisticasDemograficas['totalEstudiantes'] }}
                                                    estudiantes</span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-7">
                                                    <canvas id="sexoChart" height="180"></canvas>
                                                </div>
                                                <div class="col-5">
                                                    <div class="text-center">
                                                        <div class="mb-3">
                                                            <div class="avatar-lg mx-auto mb-2">
                                                                <div
                                                                    class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                                    <i class="ri-men-line fs-24"></i>
                                                                </div>
                                                            </div>
                                                            <h3 class="text-primary mb-1">
                                                                {{ $estadisticasDemograficas['hombres'] }}</h3>
                                                            <p class="text-muted mb-0 fs-12">Hombres</p>
                                                        </div>
                                                        <div>
                                                            <div class="avatar-lg mx-auto mb-2">
                                                                <div
                                                                    class="avatar-title bg-pink-subtle text-pink rounded-circle">
                                                                    <i class="ri-women-line fs-24"></i>
                                                                </div>
                                                            </div>
                                                            <h3 class="text-pink mb-1">
                                                                {{ $estadisticasDemograficas['mujeres'] }}</h3>
                                                            <p class="text-muted mb-0 fs-12">Mujeres</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edad Promedio -->
                                <div class="col-lg-4">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title mb-0 fs-16">
                                                    <i class="ri-calendar-line align-middle me-2"></i>
                                                    Estadísticas de Edad
                                                </h5>
                                                <span
                                                    class="badge bg-info">{{ $estadisticasDemograficas['totalEstudiantes'] }}
                                                    estudiantes</span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center py-4">
                                                <div class="position-relative d-inline-block mb-3">
                                                    <div class="avatar-xxl">
                                                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                                            <i class="ri-user-5-line fs-36"></i>
                                                        </div>
                                                    </div>
                                                    <div class="position-absolute top-0 end-0">
                                                        <span class="badge bg-info rounded-pill fs-12">Promedio</span>
                                                    </div>
                                                </div>
                                                <h1 class="display-4 text-info mb-2">
                                                    {{ number_format($estadisticasDemograficas['promedioEdad'], 1) }}
                                                </h1>
                                                <p class="text-muted mb-3">Años de edad promedio</p>

                                                @if ($estadisticasDemograficas['promedioEdad'] > 0)
                                                    <div class="progress mt-4" style="height: 10px;">
                                                        @php
                                                            $porcentajeEdad = min(
                                                                100,
                                                                ($estadisticasDemograficas['promedioEdad'] / 60) * 100,
                                                            );
                                                        @endphp
                                                        <div class="progress-bar bg-info progress-bar-striped progress-bar-animated"
                                                            role="progressbar" style="width: {{ $porcentajeEdad }}%"
                                                            aria-valuenow="{{ $estadisticasDemograficas['promedioEdad'] }}"
                                                            aria-valuemin="0" aria-valuemax="60">
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <small class="text-muted">0 años</small>
                                                        <small
                                                            class="text-muted">{{ number_format($estadisticasDemograficas['promedioEdad'], 1) }}
                                                            años</small>
                                                        <small class="text-muted">60+ años</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Top Departamentos -->
                                <div class="col-lg-4">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title mb-0 fs-16">
                                                    <i class="ri-map-pin-2-line align-middle me-2"></i>
                                                    Distribución por Departamento
                                                </h5>
                                                <span
                                                    class="badge bg-success">{{ count($estadisticasDemograficas['topDepartamentos']) }}
                                                    departamentos</span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="departamentosChart" height="180"></canvas>
                                            <div class="mt-3">
                                                @foreach ($estadisticasDemograficas['topDepartamentos'] as $departamento => $cantidad)
                                                    @php
                                                        $porcentaje =
                                                            $estadisticasDemograficas['totalEstudiantes'] > 0
                                                                ? ($cantidad /
                                                                        $estadisticasDemograficas['totalEstudiantes']) *
                                                                    100
                                                                : 0;
                                                    @endphp
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <span class="fw-medium fs-13">{{ $departamento }}</span>
                                                                <span class="fw-bold text-success">{{ $cantidad }}
                                                                    ({{ number_format($porcentaje, 1) }}%)
                                                                </span>
                                                            </div>
                                                            <div class="progress" style="height: 6px;">
                                                                <div class="progress-bar bg-success" role="progressbar"
                                                                    style="width: {{ $porcentaje }}%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Estadísticas Adicionales -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <h5 class="card-title mb-0 fs-16">
                                                <i class="ri-bar-chart-grouped-line align-middle me-2"></i>
                                                Resumen Demográfico
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="text-center p-3 border rounded bg-light">
                                                        <div class="avatar-lg mx-auto mb-2">
                                                            <div
                                                                class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                                <i class="ri-group-line fs-24"></i>
                                                            </div>
                                                        </div>
                                                        <h3 class="mb-1 text-primary">
                                                            {{ $estadisticasDemograficas['totalEstudiantes'] }}</h3>
                                                        <p class="text-muted mb-0 fs-12">Total Estudiantes</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-center p-3 border rounded bg-light">
                                                        <div class="avatar-lg mx-auto mb-2">
                                                            <div
                                                                class="avatar-title bg-success-subtle text-success rounded-circle">
                                                                <i class="ri-men-line fs-24"></i>
                                                            </div>
                                                        </div>
                                                        <h3 class="mb-1 text-success">
                                                            {{ $estadisticasDemograficas['hombres'] }}</h3>
                                                        <p class="text-muted mb-0 fs-12">Hombres</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-center p-3 border rounded bg-light">
                                                        <div class="avatar-lg mx-auto mb-2">
                                                            <div
                                                                class="avatar-title bg-pink-subtle text-pink rounded-circle">
                                                                <i class="ri-women-line fs-24"></i>
                                                            </div>
                                                        </div>
                                                        <h3 class="mb-1 text-pink">
                                                            {{ $estadisticasDemograficas['mujeres'] }}</h3>
                                                        <p class="text-muted mb-0 fs-12">Mujeres</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-center p-3 border rounded bg-light">
                                                        <div class="avatar-lg mx-auto mb-2">
                                                            <div
                                                                class="avatar-title bg-info-subtle text-info rounded-circle">
                                                                <i class="ri-map-pin-line fs-24"></i>
                                                            </div>
                                                        </div>
                                                        <h3 class="mb-1 text-info">
                                                            {{ count($estadisticasDemograficas['topDepartamentos']) }}
                                                        </h3>
                                                        <p class="text-muted mb-0 fs-12">Departamentos</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function() {
            // Configurar gráficos cuando se activa una pestaña
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                // Re-renderizar gráficos si es necesario
                window.dispatchEvent(new Event('resize'));
            });

            // Gráfico de inscripciones por mes
            const ctx1 = document.getElementById('inscripcionesChart');
            if (ctx1) {
                const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                const inscritosData = @json(array_column($inscripcionesPorMes, 'Inscrito'));
                const preInscritosData = @json(array_column($inscripcionesPorMes, 'Pre-Inscrito'));

                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: meses,
                        datasets: [{
                                label: 'Inscritos',
                                data: inscritosData,
                                borderColor: 'rgba(40, 167, 69, 1)',
                                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                                tension: 0.3,
                                fill: true
                            },
                            {
                                label: 'Pre-Inscritos',
                                data: preInscritosData,
                                borderColor: 'rgba(255, 193, 7, 1)',
                                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                                tension: 0.3,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }

            // Gráfico de distribución por estado
            const ctx2 = document.getElementById('estadoChart');
            if (ctx2) {
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: ['Inscritos', 'Pre-Inscritos'],
                        datasets: [{
                            data: [@json($totalInscritos), @json($totalPreInscritos)],
                            backgroundColor: [
                                'rgba(40, 167, 69, 0.8)',
                                'rgba(255, 193, 7, 0.8)'
                            ],
                            borderColor: [
                                'rgba(40, 167, 69, 1)',
                                'rgba(255, 193, 7, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            // Gráfico de distribución por sexo
            const ctx3 = document.getElementById('sexoChart');
            if (ctx3) {
                new Chart(ctx3, {
                    type: 'doughnut',
                    data: {
                        labels: ['Hombres', 'Mujeres'],
                        datasets: [{
                            data: [@json($hombres), @json($mujeres)],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 99, 132, 0.8)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Gráfico de distribución por departamento
            const ctx4 = document.getElementById('departamentosChart');
            if (ctx4) {
                const departamentosLabels = @json(array_keys($estadisticasDemograficas['topDepartamentos']));
                const departamentosData = @json(array_values($estadisticasDemograficas['topDepartamentos']));

                // Colores para el gráfico de departamentos
                const departamentosColors = [
                    'rgba(13, 110, 253, 0.8)',
                    'rgba(25, 135, 84, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)',
                    'rgba(111, 66, 193, 0.8)'
                ];

                new Chart(ctx4, {
                    type: 'doughnut',
                    data: {
                        labels: departamentosLabels,
                        datasets: [{
                            data: departamentosData,
                            backgroundColor: departamentosColors,
                            borderColor: departamentosColors.map(color => color.replace('0.8',
                                '1')),
                            borderWidth: 1,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed;
                                        const total = departamentosData.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} estudiantes (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        cutout: '65%'
                    }
                });
            }

            // Manejar clic en módulos
            $(document).on('click', '.modulo-header, .modulo-cell', function() {
                const moduloId = $(this).data('modulo-id');
                const ofertaId = $(this).data('oferta-id');

                if (moduloId && ofertaId) {
                    window.location.href = `/admin/ofertas/${ofertaId}/modulo/${moduloId}/detalle`;
                }
            });
        });
    </script>

    <script>
        // Gráfico de distribución de ingresos por concepto
        const ingresosConceptoChart = document.getElementById('ingresosConceptoChart');
        if (ingresosConceptoChart) {
            const conceptos = @json(array_keys($resumenPorConcepto));
            const totales = @json(array_column($resumenPorConcepto, 'total'));

            // Colores personalizados para cada concepto
            const backgroundColors = [
                'rgba(13, 110, 253, 0.8)', // Matrícula - Azul
                'rgba(25, 135, 84, 0.8)', // Colegiatura - Verde
                'rgba(255, 193, 7, 0.8)' // Certificación - Amarillo
            ];

            new Chart(ingresosConceptoChart, {
                type: 'doughnut',
                data: {
                    labels: conceptos,
                    datasets: [{
                        data: totales,
                        backgroundColor: backgroundColors,
                        borderColor: backgroundColors.map(color => color.replace('0.8', '1')),
                        borderWidth: 1,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: Bs ${value.toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        // Gráfico de estado de cobranza por concepto
        const cobranzaConceptoChart = document.getElementById('cobranzaConceptoChart');
        if (cobranzaConceptoChart) {
            const conceptos = @json(array_keys($resumenPorConcepto));
            const pagado = @json(array_column($resumenPorConcepto, 'pagado'));
            const pendiente = @json(array_column($resumenPorConcepto, 'pendiente'));

            new Chart(cobranzaConceptoChart, {
                type: 'bar',
                data: {
                    labels: conceptos,
                    datasets: [{
                            label: 'Pagado (Bs)',
                            data: pagado,
                            backgroundColor: 'rgba(25, 135, 84, 0.7)',
                            borderColor: 'rgba(25, 135, 84, 1)',
                            borderWidth: 1,
                            borderRadius: 4,
                            barPercentage: 0.6
                        },
                        {
                            label: 'Pendiente (Bs)',
                            data: pendiente,
                            backgroundColor: 'rgba(220, 53, 69, 0.7)',
                            borderColor: 'rgba(220, 53, 69, 1)',
                            borderWidth: 1,
                            borderRadius: 4,
                            barPercentage: 0.6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += 'Bs ' + context.parsed.y.toLocaleString('es-BO', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });

                                    // Calcular porcentaje
                                    const total = pagado[context.dataIndex] + pendiente[context.dataIndex];
                                    if (total > 0) {
                                        const percentage = Math.round((context.parsed.y / total) * 100);
                                        label += ` (${percentage}%)`;
                                    }

                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Bs ' + value.toLocaleString('es-BO');
                                },
                                font: {
                                    size: 11
                                }
                            },
                            title: {
                                display: true,
                                text: 'Monto en Bolivianos (Bs)'
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }
    </script>

    <style>
        .card {
            border-radius: 12px;
            border: 1px solid var(--bs-border-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .nav-tabs-custom .nav-link {
            border: none;
            color: var(--bs-gray-600);
            font-weight: 500;
            padding: 12px 20px;
            transition: all 0.2s;
        }

        .nav-tabs-custom .nav-link.active {
            color: var(--bs-primary);
            border-bottom: 3px solid var(--bs-primary);
            background-color: transparent;
        }

        .nav-tabs-custom .nav-link:hover {
            color: var(--bs-primary);
        }

        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .progress {
            border-radius: 6px;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.04);
        }

        .modulo-header:hover,
        .modulo-cell:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
        }

        .fs-10 {
            font-size: 0.7rem !important;
        }

        .fs-12 {
            font-size: 0.75rem !important;
        }

        .fs-13 {
            font-size: 0.8125rem !important;
        }

        .fs-14 {
            font-size: 0.875rem !important;
        }

        .fs-16 {
            font-size: 1rem !important;
        }

        .text-pink {
            color: #e83e8c !important;
        }

        .bg-pink-subtle {
            background-color: rgba(232, 62, 140, 0.1) !important;
        }

        /* Agrega estos estilos al final del bloque de estilos */
        .fs-11 {
            font-size: 0.6875rem !important;
        }

        .table-finanzas th {
            white-space: nowrap;
        }

        .progress-thin {
            height: 4px !important;
        }

        /* Mejora para tablas responsivas */
        @media (max-width: 768px) {
            .table-finanzas {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .badge-responsive {
                font-size: 0.65rem;
                padding: 0.25em 0.5em;
            }
        }

        /* Estilos adicionales para la sección de conceptos */
        .bg-primary-subtle {
            background-color: rgba(13, 110, 253, 0.1) !important;
        }

        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }

        .bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.1) !important;
        }

        .bg-warning-subtle {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }

        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }

        .card-concepto {
            transition: all 0.3s ease;
        }

        .card-concepto:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Mejora para las barras de progreso */
        .progress-bar-striped {
            background-image: linear-gradient(45deg,
                    rgba(255, 255, 255, 0.15) 25%,
                    transparent 25%,
                    transparent 50%,
                    rgba(255, 255, 255, 0.15) 50%,
                    rgba(255, 255, 255, 0.15) 75%,
                    transparent 75%,
                    transparent);
            background-size: 1rem 1rem;
        }

        /* Animación para las barras de progreso */
        @keyframes progress-bar-stripes {
            0% {
                background-position-x: 1rem;
            }
        }

        .progress-bar-animated {
            animation: progress-bar-stripes 1s linear infinite;
        }

        /* Agregar al final de los estilos existentes */

        /* Estilos para gráficos */
        .chart-container {
            position: relative;
            margin: auto;
        }

        /* Animaciones para tarjetas */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Mejoras para tablas */
        .table-hover tbody tr {
            transition: background-color 0.2s ease;
        }

        /* Badges mejorados */
        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
            padding: 0.35em 0.65em;
        }

        /* Avatar con iconos */
        .avatar-title i {
            font-size: 1.2em;
        }

        /* Progreso animado */
        @keyframes progress-bar-stripes {
            0% {
                background-position: 1rem 0;
            }

            100% {
                background-position: 0 0;
            }
        }

        .progress-bar-animated {
            animation: progress-bar-stripes 1s linear infinite;
        }

        /* Responsive para móviles */
        @media (max-width: 768px) {
            .display-4 {
                font-size: 2.5rem;
            }

            .avatar-xxl {
                width: 100px !important;
                height: 100px !important;
            }
        }
    </style>
@endpush
