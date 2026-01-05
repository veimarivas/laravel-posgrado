@extends('admin.dashboard')

@section('admin')
    @php
        use Carbon\Carbon;
    @endphp

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">Detalle del Módulo</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.ofertas.listar') }}">Ofertas</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.ofertas.dashboard', $modulo->oferta_academica->id) }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Módulo {{ $modulo->n_modulo }}</li>
                        </ol>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.ofertas.dashboard', $modulo->oferta_academica->id) }}"
                        class="btn btn-light btn-sm">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Volver
                    </a>
                    <button class="btn btn-primary btn-sm" onclick="window.print()">
                        <i class="ri-printer-line align-middle me-1"></i> Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Info -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h2 class="card-title mb-1">Módulo {{ $modulo->n_modulo }}: {{ $modulo->nombre }}</h2>
                            <p class="text-muted mb-0">
                                <i class="ri-book-open-line align-middle me-1"></i>
                                {{ $modulo->oferta_academica->programa->nombre ?? 'Programa' }} |
                                Código: {{ $modulo->oferta_academica->codigo }}
                            </p>
                        </div>
                        <div class="avatar-xl">
                            <div class="avatar-title rounded"
                                style="background-color: {{ $modulo->color }}; width: 80px; height: 80px;">
                                <span class="fs-2 text-white">M{{ $modulo->n_modulo }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="border rounded p-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded">
                                                <i class="ri-calendar-event-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 fs-13">Fecha Inicio</h6>
                                        <p class="text-muted mb-0 fs-12">
                                            {{ $modulo->fecha_inicio ? Carbon::parse($modulo->fecha_inicio)->format('d/m/Y') : 'Sin fecha' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="border rounded p-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-success-subtle text-success rounded">
                                                <i class="ri-calendar-check-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 fs-13">Fecha Fin</h6>
                                        <p class="text-muted mb-0 fs-12">
                                            {{ $modulo->fecha_fin ? Carbon::parse($modulo->fecha_fin)->format('d/m/Y') : 'Sin fecha' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="border rounded p-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-info-subtle text-info rounded">
                                                <i class="ri-user-star-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 fs-13">Docente</h6>
                                        <p class="text-muted mb-0 fs-12">
                                            {{ $modulo->docente ? $modulo->docente->persona->nombres . ' ' . $modulo->docente->persona->apellido_paterno : 'Sin asignar' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="border rounded p-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-warning-subtle text-warning rounded">
                                                <i class="ri-time-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 fs-13">Duración</h6>
                                        <p class="text-muted mb-0 fs-12">
                                            @if ($modulo->fecha_inicio && $modulo->fecha_fin)
                                                @php
                                                    $inicio = Carbon::parse($modulo->fecha_inicio);
                                                    $fin = Carbon::parse($modulo->fecha_fin);
                                                    $dias = $inicio->diffInDays($fin);
                                                @endphp
                                                {{ $dias }} días
                                            @else
                                                --
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pestañas de Información -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#tab-detalles" role="tab">
                                <i class="ri-information-line align-middle me-1"></i> Detalles
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-horarios" role="tab">
                                <i class="ri-calendar-event-line align-middle me-1"></i> Horarios
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-participantes" role="tab">
                                <i class="ri-user-list-line align-middle me-1"></i> Participantes
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-docente" role="tab">
                                <i class="ri-user-star-line align-middle me-1"></i> Docente
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Pestaña 1: Detalles -->
                        <div class="tab-pane fade show active" id="tab-detalles" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <h5 class="card-title mb-0 fs-16">Información del Módulo</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-medium" width="40%">Número de Módulo:</td>
                                                            <td class="text-end">
                                                                <span
                                                                    class="badge bg-primary">M{{ $modulo->n_modulo }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-medium">Nombre:</td>
                                                            <td class="text-end">{{ $modulo->nombre }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-medium">Color:</td>
                                                            <td class="text-end">
                                                                <span class="d-inline-block rounded-circle"
                                                                    style="width: 15px; height: 15px; background-color: {{ $modulo->color }};"></span>
                                                                <span class="ms-2">{{ $modulo->color }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-medium">Oferta Académica:</td>
                                                            <td class="text-end">
                                                                {{ $modulo->oferta_academica->programa->nombre ?? 'N/A' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-medium">Código Oferta:</td>
                                                            <td class="text-end">{{ $modulo->oferta_academica->codigo }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-header border-bottom bg-light">
                                            <h5 class="card-title mb-0 fs-16">Estadísticas</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row text-center">
                                                <div class="col-6">
                                                    <div class="py-2">
                                                        <h3 class="text-primary">{{ $matriculaciones->count() }}</h3>
                                                        <p class="text-muted mb-0 fs-12">Total Participantes</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="py-2">
                                                        <h3 class="text-success">
                                                            {{ $matriculaciones->where('nota_regular', '!=', null)->count() }}
                                                        </h3>
                                                        <p class="text-muted mb-0 fs-12">Con Nota</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="py-2">
                                                        <h3 class="text-warning">
                                                            {{ $matriculaciones->where('nota_nivelacion', '!=', null)->count() }}
                                                        </h3>
                                                        <p class="text-muted mb-0 fs-12">Con Nivelación</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="py-2">
                                                        @php
                                                            $aprobados = $matriculaciones
                                                                ->filter(function ($mat) {
                                                                    return $mat->nota_regular >= 61;
                                                                })
                                                                ->count();
                                                        @endphp
                                                        <h3 class="text-info">{{ $aprobados }}</h3>
                                                        <p class="text-muted mb-0 fs-12">Aprobados</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña 2: Horarios -->
                        <div class="tab-pane fade" id="tab-horarios" role="tabpanel">
                            <div class="card border">
                                <div class="card-header border-bottom bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0 fs-16">Horarios del Módulo</h5>
                                        <span class="badge bg-primary">{{ $modulo->horarios->count() }} sesiones</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if ($modulo->horarios && $modulo->horarios->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th>Fecha</th>
                                                        <th>Hora Inicio</th>
                                                        <th>Hora Fin</th>
                                                        <th>Responsable</th>
                                                        <th>Estado</th>
                                                        <th>Lugar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($modulo->horarios as $index => $horario)
                                                        @php
                                                            $fechaHorario = $horario->fecha
                                                                ? Carbon::parse($horario->fecha)->format('d/m/Y')
                                                                : '--';
                                                            $estadoColor = match ($horario->estado) {
                                                                'Confirmado' => 'success',
                                                                'Desarrollado' => 'primary',
                                                                'Postergado' => 'warning',
                                                                default => 'secondary',
                                                            };
                                                        @endphp
                                                        <tr>
                                                            <td class="text-center">{{ $index + 1 }}</td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="ri-calendar-line text-primary me-2"></i>
                                                                    {{ $fechaHorario }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-light text-dark">
                                                                    <i class="ri-time-line me-1"></i>
                                                                    {{ $horario->hora_inicio }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-light text-dark">
                                                                    <i class="ri-time-line me-1"></i>
                                                                    {{ $horario->hora_fin }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                {{ $horario->trabajador_cargo->trabajador->persona->nombres ?? 'Sin responsable' }}
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-{{ $estadoColor }}">
                                                                    {{ $horario->estado }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                {{ $horario->sucursal_cuenta->cuenta->nombre ?? 'Sin lugar' }}
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
                                                    <i class="ri-calendar-line fs-2"></i>
                                                </div>
                                            </div>
                                            <h5 class="mb-2">No hay horarios asignados</h5>
                                            <p class="text-muted mb-0">Este módulo no tiene horarios programados</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña 3: Participantes -->
                        <div class="tab-pane fade" id="tab-participantes" role="tabpanel">
                            <div class="card border">
                                <div class="card-header border-bottom bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0 fs-16">Participantes y Notas</h5>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-light btn-sm" onclick="window.print()">
                                                <i class="ri-printer-line align-middle me-1"></i> Imprimir
                                            </button>
                                            <button class="btn btn-light btn-sm">
                                                <i class="ri-download-line align-middle me-1"></i> Exportar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" width="5%">#</th>
                                                    <th>Estudiante</th>
                                                    <th class="text-center">Carnet</th>
                                                    <th class="text-center">Nota Regular</th>
                                                    <th class="text-center">Nota Nivelación</th>
                                                    <th class="text-center">Estado</th>
                                                    <th class="text-center" width="15%">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($matriculaciones as $index => $matriculacion)
                                                    @php
                                                        $persona = $matriculacion->inscripcion->estudiante->persona;
                                                        $notaRegular = $matriculacion->nota_regular;
                                                        $notaNivelacion = $matriculacion->nota_nivelacion;

                                                        $colorNota = match (true) {
                                                            $notaRegular >= 90 => 'success',
                                                            $notaRegular >= 80 => 'info',
                                                            $notaRegular >= 70 => 'warning',
                                                            $notaRegular >= 60 => 'primary',
                                                            $notaRegular !== null => 'danger',
                                                            default => 'secondary',
                                                        };

                                                        $estadoNota = match (true) {
                                                            $notaRegular >= 61 => 'Aprobado',
                                                            $notaRegular !== null => 'Reprobado',
                                                            default => 'Pendiente',
                                                        };

                                                        $colorEstado = match ($estadoNota) {
                                                            'Aprobado' => 'success',
                                                            'Reprobado' => 'danger',
                                                            default => 'warning',
                                                        };
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.estudiantes.detalle', ['id' => $matriculacion->inscripcion->estudiante->id]) }}"
                                                                class="text-decoration-none">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <div class="avatar-xs">
                                                                            <div
                                                                                class="avatar-title bg-light text-primary rounded-circle">
                                                                                {{ substr($persona->nombres, 0, 1) }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-2">
                                                                        <h6 class="mb-0 fs-14">{{ $persona->nombres }}
                                                                            {{ $persona->apellido_paterno }}</h6>
                                                                        <small
                                                                            class="text-muted">{{ $persona->correo ?? 'Sin correo' }}</small>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-secondary">{{ $persona->carnet }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($notaRegular !== null)
                                                                <span
                                                                    class="badge bg-{{ $colorNota }} rounded-pill px-2 py-1"
                                                                    style="font-size: 0.9rem;">
                                                                    {{ $notaRegular }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-light text-muted">--</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($notaNivelacion !== null)
                                                                <span class="badge bg-info rounded-pill px-2 py-1"
                                                                    style="font-size: 0.9rem;">
                                                                    {{ $notaNivelacion }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-light text-muted">--</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <span
                                                                class="badge bg-{{ $colorEstado }}">{{ $estadoNota }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-primary registrar-nota-btn"
                                                                data-matriculacion-id="{{ $matriculacion->id }}"
                                                                data-estudiante-nombre="{{ $persona->nombres }} {{ $persona->apellido_paterno }}"
                                                                data-nota-regular="{{ $notaRegular ?? '' }}"
                                                                data-nota-nivelacion="{{ $notaNivelacion ?? '' }}">
                                                                <i class="ri-edit-line"></i>
                                                                {{ $notaRegular !== null ? 'Editar' : 'Registrar' }}
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña 4: Docente -->
                        <div class="tab-pane fade" id="tab-docente" role="tabpanel">
                            <div class="card border">
                                <div class="card-header border-bottom bg-light">
                                    <h5 class="card-title mb-0 fs-16">Información del Docente</h5>
                                </div>
                                <div class="card-body">
                                    @if ($modulo->docente)
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="text-center">
                                                    <div class="avatar-xxl mx-auto mb-3">
                                                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                                            <i class="ri-user-3-line fs-1"></i>
                                                        </div>
                                                    </div>
                                                    <h4 class="mb-1">{{ $modulo->docente->persona->nombres }}
                                                        {{ $modulo->docente->persona->apellido_paterno }}</h4>
                                                    <p class="text-muted mb-0">Docente del Módulo</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="table-responsive">
                                                    <table class="table table-borderless">
                                                        <tbody>
                                                            <tr>
                                                                <td class="fw-medium" width="30%">Correo:</td>
                                                                <td>
                                                                    <a href="mailto:{{ $modulo->docente->persona->correo }}"
                                                                        class="text-primary">
                                                                        {{ $modulo->docente->persona->correo ?? 'Sin correo' }}
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-medium">Celular:</td>
                                                                <td>{{ $modulo->docente->persona->celular ?? 'Sin celular' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-medium">Teléfono:</td>
                                                                <td>{{ $modulo->docente->persona->telefono ?? 'Sin teléfono' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-medium">Dirección:</td>
                                                                <td>{{ $modulo->docente->persona->direccion ?? 'Sin dirección' }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <div class="avatar-lg mx-auto mb-3">
                                                <div class="avatar-title bg-light text-secondary rounded-circle">
                                                    <i class="ri-user-unfollow-line fs-2"></i>
                                                </div>
                                            </div>
                                            <h5 class="mb-2">No hay docente asignado</h5>
                                            <p class="text-muted mb-0">Este módulo no tiene docente asignado actualmente
                                            </p>
                                            <button class="btn btn-primary mt-3">
                                                <i class="ri-user-add-line me-1"></i> Asignar Docente
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para registrar nota -->
    <div class="modal fade" id="modalRegistrarNota" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Nota - <span id="estudianteNombreNota"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formRegistrarNota">
                        @csrf
                        <input type="hidden" id="matriculacionId" name="matriculacion_id">

                        <div class="mb-3">
                            <label class="form-label">Nota Regular (0-100)</label>
                            <input type="number" name="nota_regular" id="notaRegular" class="form-control"
                                min="0" max="100" step="0.01" placeholder="Ej: 85.5" required>
                            <div class="form-text">Nota mínima para aprobar: 61</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nota Nivelación (0-100)</label>
                            <input type="number" name="nota_nivelacion" id="notaNivelacion" class="form-control"
                                min="0" max="100" step="0.01" placeholder="Ej: 75.0">
                            <div class="form-text">Opcional - Solo si aplica nivelación</div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">
                                <i class="ri-save-line me-1"></i> Guardar Notas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Registrar nota
            $(document).on('click', '.registrar-nota-btn', function() {
                const matriculacionId = $(this).data('matriculacion-id');
                const estudianteNombre = $(this).data('estudiante-nombre');
                const notaRegular = $(this).data('nota-regular');
                const notaNivelacion = $(this).data('nota-nivelacion');

                $('#estudianteNombreNota').text(estudianteNombre);
                $('#matriculacionId').val(matriculacionId);
                $('#notaRegular').val(notaRegular);
                $('#notaNivelacion').val(notaNivelacion);

                $('#modalRegistrarNota').modal('show');
            });

            // Enviar formulario de nota
            $('#formRegistrarNota').submit(function(e) {
                e.preventDefault();

                const formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('admin.inscripciones.registrar-nota', ['matriculacion' => ':id']) }}"
                        .replace(':id', $('#matriculacionId').val()),
                    method: 'POST',
                    data: formData,
                    success: function(res) {
                        if (res.success) {
                            $('#modalRegistrarNota').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: res.msg,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: res.msg
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.msg || 'Error al registrar la nota.'
                        });
                    }
                });
            });
        });
    </script>

    <style>
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

        .card {
            border-radius: 12px;
            border: 1px solid var(--bs-border-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
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
    </style>
@endpush
