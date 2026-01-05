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
                        <div class="tab-pane fade show active" id="tab-resumen" role="tabpanel">
                            <div class="row">
                                <!-- Estadísticas Financieras -->
                                <div class="col-lg-2">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <h5 class="card-title mb-0 fs-16">
                                                <i class="ri-money-dollar-circle-line align-middle me-2"></i>
                                                Estadísticas Financieras
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <div class="border rounded p-3 text-center">
                                                        <div class="avatar-sm mx-auto mb-2">
                                                            <div
                                                                class="avatar-title bg-success-subtle text-success rounded-circle">
                                                                <i class="ri-wallet-3-line"></i>
                                                            </div>
                                                        </div>
                                                        <h4 class="mb-1 text-success">
                                                            {{ number_format($totalRecaudado, 2) }} Bs</h4>
                                                        <p class="text-muted mb-0 fs-12">Total Recaudado</p>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="border rounded p-3 text-center">
                                                        <div class="avatar-sm mx-auto mb-2">
                                                            <div
                                                                class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                                                <i class="ri-bill-line"></i>
                                                            </div>
                                                        </div>
                                                        <h4 class="mb-1 text-danger">{{ number_format($totalDeuda, 2) }}
                                                            Bs</h4>
                                                        <p class="text-muted mb-0 fs-12">Deuda Pendiente</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="progress mt-3" style="height: 10px;">
                                                @php
                                                    $total = $totalRecaudado + $totalDeuda;
                                                    $porcentajeCobrado =
                                                        $total > 0 ? ($totalRecaudado / $total) * 100 : 0;
                                                @endphp
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $porcentajeCobrado }}%">
                                                    {{ number_format($porcentajeCobrado, 1) }}%
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2">
                                                <small class="text-muted">Cobrado</small>
                                                <small class="text-muted">Pendiente</small>
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
                                <div class="col-lg-5">
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
                        </div>

                        <!-- Pestaña 2: Finanzas -->
                        <div class="tab-pane fade" id="tab-finanzas" role="tabpanel">
                            <div class="card border">
                                <div class="card-header border-bottom bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0 fs-16">
                                            <i class="ri-money-dollar-box-line align-middle me-2"></i>
                                            Ingresos por Conceptos
                                        </h5>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-download-line align-middle me-1"></i> Exportar
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#"><i
                                                            class="ri-file-pdf-line align-middle me-2"></i> PDF</a></li>
                                                <li><a class="dropdown-item" href="#"><i
                                                            class="ri-file-excel-line align-middle me-2"></i> Excel</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" width="5%">#</th>
                                                    <th>Concepto</th>
                                                    <th class="text-end">Cobrado (Bs)</th>
                                                    <th class="text-end">Deuda (Bs)</th>
                                                    <th class="text-end">Total (Bs)</th>
                                                    <th class="text-center">% Cobrado</th>
                                                    <th width="20%">Progreso</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ingresosPorConcepto as $index => $ingreso)
                                                    @php
                                                        $porcentaje =
                                                            $ingreso['total'] > 0
                                                                ? ($ingreso['cobrado'] / $ingreso['total']) * 100
                                                                : 0;
                                                        $color = match (true) {
                                                            $porcentaje >= 90 => 'success',
                                                            $porcentaje >= 70 => 'info',
                                                            $porcentaje >= 50 => 'warning',
                                                            default => 'danger',
                                                        };
                                                        $icon = match (true) {
                                                            $porcentaje >= 90 => 'ri-checkbox-circle-fill',
                                                            $porcentaje >= 70 => 'ri-alert-line',
                                                            $porcentaje >= 50 => 'ri-time-line',
                                                            default => 'ri-close-circle-fill',
                                                        };
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-shrink-0">
                                                                    <div class="avatar-xs">
                                                                        <div
                                                                            class="avatar-title bg-{{ $color }}-subtle text-{{ $color }} rounded">
                                                                            <i class="{{ $icon }}"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <h6 class="mb-0 fs-14">{{ $ingreso['concepto'] }}</h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-end">
                                                            <span
                                                                class="fw-semibold text-success">{{ number_format($ingreso['cobrado'], 2) }}</span>
                                                        </td>
                                                        <td class="text-end">
                                                            <span
                                                                class="fw-semibold text-danger">{{ number_format($ingreso['deuda'], 2) }}</span>
                                                        </td>
                                                        <td class="text-end">
                                                            <span
                                                                class="fw-bold">{{ number_format($ingreso['total'], 2) }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span
                                                                class="badge bg-{{ $color }}-subtle text-{{ $color }}">{{ number_format($porcentaje, 1) }}%</span>
                                                        </td>
                                                        <td>
                                                            <div class="progress" style="height: 6px;">
                                                                <div class="progress-bar bg-{{ $color }}"
                                                                    role="progressbar"
                                                                    style="width: {{ $porcentaje }}%;"
                                                                    aria-valuenow="{{ $porcentaje }}" aria-valuemin="0"
                                                                    aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <td colspan="2" class="fw-bold">TOTAL GENERAL</td>
                                                    <td class="text-end fw-bold text-success">
                                                        {{ number_format($totalRecaudado, 2) }}</td>
                                                    <td class="text-end fw-bold text-danger">
                                                        {{ number_format($totalDeuda, 2) }}</td>
                                                    <td class="text-end fw-bold">
                                                        {{ number_format($totalRecaudado + $totalDeuda, 2) }}</td>
                                                    <td class="text-center">
                                                        @php
                                                            $totalPorcentaje =
                                                                $totalRecaudado + $totalDeuda > 0
                                                                    ? ($totalRecaudado /
                                                                            ($totalRecaudado + $totalDeuda)) *
                                                                        100
                                                                    : 0;
                                                        @endphp
                                                        <span
                                                            class="badge bg-primary">{{ number_format($totalPorcentaje, 1) }}%</span>
                                                    </td>
                                                    <td>
                                                        <div class="progress" style="height: 6px;">
                                                            <div class="progress-bar bg-primary"
                                                                style="width: {{ $totalPorcentaje }}%;"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
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

                        <!-- Pestaña 4: Demográfico -->
                        <div class="tab-pane fade" id="tab-demografico" role="tabpanel">
                            <div class="row">
                                <!-- Distribución por Sexo -->
                                <div class="col-lg-4">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <h5 class="card-title mb-0 fs-16">
                                                <i class="ri-genderless-line align-middle me-2"></i>
                                                Distribución por Sexo
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="sexoChart" height="200"></canvas>
                                            <div class="row text-center mt-3">
                                                <div class="col-6">
                                                    <h3 class="text-primary">{{ $hombres }}</h3>
                                                    <p class="text-muted mb-0 fs-12">Hombres</p>
                                                </div>
                                                <div class="col-6">
                                                    <h3 class="text-pink">{{ $mujeres }}</h3>
                                                    <p class="text-muted mb-0 fs-12">Mujeres</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edad Promedio -->
                                <div class="col-lg-4">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <h5 class="card-title mb-0 fs-16">
                                                <i class="ri-user-5-line align-middle me-2"></i>
                                                Edad Promedio
                                            </h5>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="py-4">
                                                <div class="avatar-lg mx-auto mb-3">
                                                    <div
                                                        class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                        <i class="ri-user-line fs-24"></i>
                                                    </div>
                                                </div>
                                                <h1 class="display-4 text-primary mb-2">
                                                    {{ number_format($promedioEdad, 1) }}</h1>
                                                <p class="text-muted mb-0">Años de edad promedio</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Top Ciudades -->
                                <div class="col-lg-4">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <h5 class="card-title mb-0 fs-16">
                                                <i class="ri-map-pin-line align-middle me-2"></i>
                                                Top 5 Ciudades
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="ciudadesChart" height="200"></canvas>
                                            <div class="mt-3">
                                                @foreach ($topCiudades as $ciudad => $cantidad)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="flex-grow-1">
                                                            <p class="text-muted mb-0 fs-12">{{ $ciudad }}</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <span class="badge bg-primary">{{ $cantidad }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
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

            // Gráfico de ciudades
            const ctx4 = document.getElementById('ciudadesChart');
            if (ctx4) {
                const ciudadesLabels = @json(array_keys($topCiudades));
                const ciudadesData = @json(array_values($topCiudades));

                new Chart(ctx4, {
                    type: 'bar',
                    data: {
                        labels: ciudadesLabels,
                        datasets: [{
                            label: 'Estudiantes',
                            data: ciudadesData,
                            backgroundColor: 'rgba(153, 102, 255, 0.7)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
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
    </style>
@endpush
