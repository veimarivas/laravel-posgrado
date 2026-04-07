@extends('admin.dashboard')

@push('style')
<style>
/* ── detalle-modulo ── */
.dash-nav-tabs { border-bottom: 2px solid #e9ecef; gap: 4px; }
.dash-nav-tabs .nav-link { border: none; color: #6c757d; font-weight: 500; padding: 10px 18px;
    border-bottom: 3px solid transparent; border-radius: 0; font-size: .85rem; transition: all .15s; }
.dash-nav-tabs .nav-link:hover  { color: #0d6efd; background: #f8f9ff; }
.dash-nav-tabs .nav-link.active { color: #0d6efd; border-bottom-color: #0d6efd; background: transparent; font-weight: 600; }
.dash-nav-tabs .nav-link i { font-size: .95rem; }

.stat-mini { border-left: 3px solid; border-radius: 8px; }

.tbl-sm th { font-size: .7rem; text-transform: uppercase; font-weight: 700; color: #6c757d;
    padding: 7px 10px !important; background: #f8f9fa; border: none !important; letter-spacing: .03em; }
.tbl-sm td { font-size: .8rem; padding: 7px 10px !important; vertical-align: middle; }

.nota-badge { display:inline-flex; align-items:center; justify-content:center;
    width:38px; height:38px; border-radius:50%; font-weight:700; font-size:.85rem; }

.info-row { display:flex; align-items:center; gap:10px; padding:10px 0; border-bottom:1px solid #f0f0f0; }
.info-row:last-child { border-bottom:none; }
.info-icon { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.info-label { font-size:.7rem; text-transform:uppercase; font-weight:700; color:#9ca3af; margin-bottom:1px; }
.info-value { font-size:.85rem; font-weight:500; color:#1f2937; }
</style>
@endpush

@section('admin')
@php
    use Carbon\Carbon;
    $oferta   = $modulo->oferta_academica;
    $programa = $oferta->programa->nombre ?? 'Programa';
    $docente  = $modulo->docente;
    $persona  = $docente?->persona;

    $totalParticipantes = $matriculaciones->count();
    $conNota     = $matriculaciones->where('nota_regular', '!=', null)->count();
    $conNivel    = $matriculaciones->where('nota_nivelacion', '!=', null)->count();
    $aprobados   = $matriculaciones->filter(fn($m) => $m->nota_regular >= 61)->count();
    $reprobados  = $matriculaciones->filter(fn($m) => $m->nota_regular !== null && $m->nota_regular < 61)->count();
    $pendientes  = $totalParticipantes - $conNota;

    $duracion = null;
    if ($modulo->fecha_inicio && $modulo->fecha_fin) {
        $duracion = Carbon::parse($modulo->fecha_inicio)->diffInDays(Carbon::parse($modulo->fecha_fin));
    }
@endphp

{{-- Header --}}
<div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <ol class="breadcrumb mb-1" style="font-size:.8rem;">
            <li class="breadcrumb-item"><a href="{{ route('admin.ofertas.listar') }}" class="text-decoration-none">Ofertas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.ofertas.dashboard', $oferta->id) }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active text-muted">Módulo {{ $modulo->n_modulo }}</li>
        </ol>
        <h4 class="mb-1 fw-bold">Módulo {{ $modulo->n_modulo }}: {{ $modulo->nombre }}</h4>
        <div class="d-flex align-items-center gap-2 flex-wrap" style="font-size:.82rem;color:#6c757d;">
            <span><i class="ri-book-2-line me-1"></i>{{ $programa }}</span>
            <span class="badge rounded-pill px-2" style="background:{{ $oferta->color }}18;color:{{ $oferta->color }};border:1px solid {{ $oferta->color }}40;font-size:.72rem;">
                {{ $oferta->codigo }}
            </span>
        </div>
    </div>
    <div class="d-flex gap-2 align-self-start">
        <a href="{{ route('admin.ofertas.dashboard', $oferta->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="ri-arrow-left-line me-1"></i>Volver
        </a>
        <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
            <i class="ri-printer-line me-1"></i>Imprimir
        </button>
    </div>
</div>

{{-- Stat cards + módulo banner --}}
<div class="card border-0 shadow-sm mb-3" style="border-radius:12px;overflow:hidden;">
    <div class="card-body p-0">
        {{-- Banner color del módulo --}}
        <div class="d-flex align-items-center gap-4 p-4" style="background:{{ $oferta->color }}12;border-bottom:3px solid {{ $modulo->color }};">
            <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold text-white flex-shrink-0"
                 style="width:64px;height:64px;background:{{ $modulo->color }};font-size:1.4rem;">
                M{{ $modulo->n_modulo }}
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-0 fw-bold">{{ $modulo->nombre }}</h5>
                <p class="mb-0 text-muted" style="font-size:.83rem;">{{ $programa }} — {{ $oferta->codigo }}</p>
            </div>
            @if ($docente)
            <div class="d-flex align-items-center gap-2 d-none d-md-flex">
                <div class="d-flex align-items-center justify-content-center rounded-circle bg-white flex-shrink-0"
                     style="width:40px;height:40px;border:2px solid {{ $modulo->color }};">
                    <i class="ri-user-star-line" style="color:{{ $modulo->color }};font-size:1.1rem;"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted" style="font-size:.7rem;text-transform:uppercase;font-weight:700;">Docente</p>
                    <span class="fw-semibold" style="font-size:.85rem;">{{ $persona->nombres }} {{ $persona->apellido_paterno }}</span>
                </div>
            </div>
            @endif
        </div>

        {{-- Mini stats --}}
        <div class="row g-0">
            @php
            $stats = [
                ['icon'=>'ri-calendar-2-line',    'color'=>'#0d6efd', 'label'=>'Fecha Inicio', 'value'=> $modulo->fecha_inicio ? Carbon::parse($modulo->fecha_inicio)->format('d/m/Y') : 'Sin fecha'],
                ['icon'=>'ri-calendar-check-line', 'color'=>'#198754', 'label'=>'Fecha Fin',    'value'=> $modulo->fecha_fin    ? Carbon::parse($modulo->fecha_fin)->format('d/m/Y')    : 'Sin fecha'],
                ['icon'=>'ri-time-line',            'color'=>'#fd7e14', 'label'=>'Duración',     'value'=> $duracion !== null ? $duracion.' días' : '—'],
                ['icon'=>'ri-team-line',            'color'=>'#6f42c1', 'label'=>'Participantes','value'=> $totalParticipantes],
                ['icon'=>'ri-checkbox-circle-line', 'color'=>'#20c997', 'label'=>'Aprobados',    'value'=> $aprobados],
            ];
            @endphp
            @foreach ($stats as $i => $s)
            <div class="col" style="{{ $i > 0 ? 'border-left:1px solid #f0f0f0;' : '' }}">
                <div class="p-3 text-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-2"
                         style="width:36px;height:36px;background:{{ $s['color'] }}18;">
                        <i class="{{ $s['icon'] }}" style="color:{{ $s['color'] }};font-size:1rem;"></i>
                    </div>
                    <div class="fw-bold" style="font-size:1rem;color:#1f2937;">{{ $s['value'] }}</div>
                    <div class="text-muted" style="font-size:.68rem;text-transform:uppercase;font-weight:600;">{{ $s['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Tabs --}}
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-body p-0">
        <ul class="nav dash-nav-tabs px-3 pt-2">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#tab-detalles">
                    <i class="ri-information-line me-1"></i>Detalles
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-horarios">
                    <i class="ri-calendar-event-line me-1"></i>Horarios
                    <span class="badge rounded-pill ms-1" style="background:#0d6efd18;color:#0d6efd;font-size:.68rem;">{{ $modulo->horarios->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-participantes">
                    <i class="ri-user-list-line me-1"></i>Participantes
                    <span class="badge rounded-pill ms-1" style="background:#6f42c118;color:#6f42c1;font-size:.68rem;">{{ $totalParticipantes }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-docente">
                    <i class="ri-user-star-line me-1"></i>Docente
                </a>
            </li>
        </ul>

        <div class="tab-content p-4">

            {{-- Tab Detalles --}}
            <div class="tab-pane fade show active" id="tab-detalles">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius:10px;">
                            <div class="card-header border-bottom bg-transparent py-2 px-3">
                                <h6 class="mb-0 fw-semibold" style="font-size:.85rem;">
                                    <i class="ri-file-list-3-line me-2 text-primary"></i>Información del Módulo
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="info-row">
                                    <div class="info-icon" style="background:#0d6efd18;"><i class="ri-hashtag text-primary"></i></div>
                                    <div><div class="info-label">Número</div><div class="info-value">Módulo {{ $modulo->n_modulo }}</div></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-icon" style="background:#6f42c118;"><i class="ri-book-2-line" style="color:#6f42c1;"></i></div>
                                    <div><div class="info-label">Nombre</div><div class="info-value">{{ $modulo->nombre }}</div></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-icon" style="background:{{ $modulo->color }}22;"><i class="ri-palette-line" style="color:{{ $modulo->color }};"></i></div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div>
                                            <div class="info-label">Color</div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="rounded-circle d-inline-block" style="width:14px;height:14px;background:{{ $modulo->color }};border:1px solid #dee2e6;"></span>
                                                <span class="info-value">{{ $modulo->color }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-icon" style="background:#19875418;"><i class="ri-building-line text-success"></i></div>
                                    <div><div class="info-label">Programa</div><div class="info-value">{{ $programa }}</div></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-icon" style="background:#fd7e1418;"><i class="ri-barcode-line" style="color:#fd7e14;"></i></div>
                                    <div><div class="info-label">Código Oferta</div><div class="info-value">{{ $oferta->codigo }}</div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius:10px;">
                            <div class="card-header border-bottom bg-transparent py-2 px-3">
                                <h6 class="mb-0 fw-semibold" style="font-size:.85rem;">
                                    <i class="ri-bar-chart-line me-2 text-success"></i>Estadísticas
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    @php
                                    $statCards = [
                                        ['value'=>$totalParticipantes, 'label'=>'Total Participantes', 'color'=>'#6f42c1', 'bg'=>'#6f42c118', 'icon'=>'ri-team-line'],
                                        ['value'=>$aprobados,          'label'=>'Aprobados',           'color'=>'#198754', 'bg'=>'#19875418', 'icon'=>'ri-checkbox-circle-line'],
                                        ['value'=>$reprobados,         'label'=>'Reprobados',          'color'=>'#dc3545', 'bg'=>'#dc354518', 'icon'=>'ri-close-circle-line'],
                                        ['value'=>$pendientes,         'label'=>'Sin Nota',            'color'=>'#fd7e14', 'bg'=>'#fd7e1418', 'icon'=>'ri-time-line'],
                                        ['value'=>$conNivel,           'label'=>'Con Nivelación',      'color'=>'#0dcaf0', 'bg'=>'#0dcaf018', 'icon'=>'ri-refresh-line'],
                                        ['value'=>$conNota,            'label'=>'Con Nota',            'color'=>'#0d6efd', 'bg'=>'#0d6efd18', 'icon'=>'ri-edit-line'],
                                    ];
                                    @endphp
                                    @foreach ($statCards as $sc)
                                    <div class="col-6">
                                        <div class="p-3 rounded-3 d-flex align-items-center gap-3" style="background:{{ $sc['bg'] }};">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                                 style="width:38px;height:38px;background:{{ $sc['color'] }}22;">
                                                <i class="{{ $sc['icon'] }}" style="color:{{ $sc['color'] }};font-size:1rem;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold" style="font-size:1.25rem;color:{{ $sc['color'] }};line-height:1;">{{ $sc['value'] }}</div>
                                                <div class="text-muted" style="font-size:.7rem;">{{ $sc['label'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab Horarios --}}
            <div class="tab-pane fade" id="tab-horarios">
                @if ($modulo->horarios->count() > 0)
                <div class="card border-0 shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="card-header border-bottom bg-transparent py-2 px-3 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold" style="font-size:.85rem;">
                            <i class="ri-calendar-event-line me-2 text-primary"></i>Sesiones del Módulo
                        </h6>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size:.72rem;">
                            {{ $modulo->horarios->count() }} sesiones
                        </span>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 tbl-sm">
                            <thead>
                                <tr>
                                    <th class="text-center" width="40">#</th>
                                    <th>Fecha</th>
                                    <th>Horario</th>
                                    <th>Responsable</th>
                                    <th>Estado</th>
                                    <th>Cuenta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($modulo->horarios as $index => $horario)
                                @php
                                    $fechaHorario = $horario->fecha ? Carbon::parse($horario->fecha)->format('d/m/Y') : '--';
                                    $diaSemana    = $horario->fecha ? Carbon::parse($horario->fecha)->locale('es')->isoFormat('ddd') : '';
                                    [$eColor, $eBg, $eIcon] = match ($horario->estado) {
                                        'Confirmado'   => ['#198754', '#19875418', 'ri-checkbox-circle-line'],
                                        'Desarrollado' => ['#0d6efd', '#0d6efd18', 'ri-check-double-line'],
                                        'Postergado'   => ['#fd7e14', '#fd7e1418', 'ri-pause-circle-line'],
                                        default        => ['#6c757d', '#6c757d18', 'ri-question-line'],
                                    };
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <span class="d-flex align-items-center justify-content-center rounded-circle mx-auto fw-semibold"
                                              style="width:24px;height:24px;background:#f8f9fa;font-size:.72rem;color:#6c757d;">{{ $index + 1 }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold" style="font-size:.82rem;">{{ $fechaHorario }}</div>
                                        <div class="text-muted" style="font-size:.7rem;">{{ $diaSemana }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border" style="font-size:.75rem;">
                                            <i class="ri-time-line me-1 text-muted"></i>
                                            {{ substr($horario->hora_inicio, 0, 5) }} — {{ substr($horario->hora_fin, 0, 5) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="font-size:.8rem;">
                                            {{ $horario->trabajador_cargo->trabajador->persona->nombres ?? '—' }}
                                            {{ $horario->trabajador_cargo->trabajador->persona->apellido_paterno ?? '' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill px-2" style="background:{{ $eBg }};color:{{ $eColor }};border:1px solid {{ $eColor }}40;font-size:.72rem;">
                                            <i class="{{ $eIcon }} me-1"></i>{{ $horario->estado }}
                                        </span>
                                    </td>
                                    <td style="font-size:.79rem;color:#6c757d;">
                                        {{ $horario->sucursal_cuenta->cuenta->nombre ?? '—' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3 bg-light"
                         style="width:64px;height:64px;">
                        <i class="ri-calendar-line fs-2 text-muted"></i>
                    </div>
                    <h6 class="fw-semibold text-muted">No hay horarios asignados</h6>
                    <p class="text-muted mb-0" style="font-size:.83rem;">Este módulo no tiene sesiones programadas</p>
                </div>
                @endif
            </div>

            {{-- Tab Participantes --}}
            <div class="tab-pane fade" id="tab-participantes">
                <div class="card border-0 shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="card-header border-bottom bg-transparent py-2 px-3 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold" style="font-size:.85rem;">
                            <i class="ri-user-list-line me-2 text-primary"></i>Participantes y Notas
                        </h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary btn-sm" style="font-size:.75rem;" onclick="window.print()">
                                <i class="ri-printer-line me-1"></i>Imprimir
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 tbl-sm">
                            <thead>
                                <tr>
                                    <th class="text-center" width="40">#</th>
                                    <th>Estudiante</th>
                                    <th class="text-center">Carnet</th>
                                    <th class="text-center">Nota Regular</th>
                                    <th class="text-center">Nivelación</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center" width="100">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($matriculaciones as $index => $matriculacion)
                                @php
                                    $per  = $matriculacion->inscripcion->estudiante->persona;
                                    $nr   = $matriculacion->nota_regular;
                                    $nn   = $matriculacion->nota_nivelacion;
                                    $inicial = strtoupper(substr($per->nombres, 0, 1));

                                    [$nrColor, $nrBg] = match(true) {
                                        $nr >= 90             => ['#198754','#19875420'],
                                        $nr >= 80             => ['#0d6efd','#0d6efd20'],
                                        $nr >= 70             => ['#fd7e14','#fd7e1420'],
                                        $nr >= 61             => ['#ffc107','#ffc10720'],
                                        $nr !== null          => ['#dc3545','#dc354520'],
                                        default               => ['#9ca3af','#f3f4f6'],
                                    };

                                    [$eLabel, $eColor, $eBg] = match(true) {
                                        $nr >= 61             => ['Aprobado', '#198754','#19875418'],
                                        $nr !== null          => ['Reprobado','#dc3545','#dc354518'],
                                        default               => ['Pendiente','#fd7e14','#fd7e1418'],
                                    };
                                @endphp
                                <tr>
                                    <td class="text-center text-muted" style="font-size:.75rem;">{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ route('admin.estudiantes.detalle', ['id' => $matriculacion->inscripcion->estudiante->id]) }}"
                                           class="text-decoration-none d-flex align-items-center gap-2">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold flex-shrink-0"
                                                 style="width:30px;height:30px;background:#0d6efd18;color:#0d6efd;font-size:.75rem;">
                                                {{ $inicial }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark" style="font-size:.82rem;">
                                                    {{ $per->nombres }} {{ $per->apellido_paterno }}
                                                </div>
                                                <div class="text-muted" style="font-size:.7rem;">{{ $per->correo ?? 'Sin correo' }}</div>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill" style="font-size:.72rem;">
                                            {{ $per->carnet }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($nr !== null)
                                            <div class="nota-badge mx-auto" style="background:{{ $nrBg }};color:{{ $nrColor }};">{{ $nr }}</div>
                                        @else
                                            <span class="text-muted" style="font-size:.8rem;">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($nn !== null)
                                            <div class="nota-badge mx-auto" style="background:#0dcaf018;color:#0c8896;">{{ $nn }}</div>
                                        @else
                                            <span class="text-muted" style="font-size:.8rem;">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill px-2" style="background:{{ $eBg }};color:{{ $eColor }};border:1px solid {{ $eColor }}40;font-size:.72rem;">
                                            {{ $eLabel }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-sm registrar-nota-btn"
                                            style="font-size:.73rem;background:#0d6efd12;color:#0d6efd;border:1px solid #0d6efd40;"
                                            data-matriculacion-id="{{ $matriculacion->id }}"
                                            data-estudiante-nombre="{{ $per->nombres }} {{ $per->apellido_paterno }}"
                                            data-nota-regular="{{ $nr ?? '' }}"
                                            data-nota-nivelacion="{{ $nn ?? '' }}">
                                            <i class="{{ $nr !== null ? 'ri-edit-line' : 'ri-add-line' }} me-1"></i>
                                            {{ $nr !== null ? 'Editar' : 'Nota' }}
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Tab Docente --}}
            <div class="tab-pane fade" id="tab-docente">
                @if ($docente)
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm text-center h-100" style="border-radius:10px;overflow:hidden;">
                            <div style="height:6px;background:linear-gradient(90deg,#0d6efd,#6f42c1);"></div>
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3 fw-bold text-white"
                                     style="width:72px;height:72px;background:linear-gradient(135deg,#0d6efd,#6f42c1);font-size:1.6rem;">
                                    {{ strtoupper(substr($persona->nombres, 0, 1)) }}
                                </div>
                                <h5 class="fw-bold mb-1">{{ $persona->nombres }} {{ $persona->apellido_paterno }}</h5>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size:.72rem;">
                                    Docente del Módulo
                                </span>
                                @if ($persona->correo)
                                <div class="mt-3">
                                    <a href="mailto:{{ $persona->correo }}" class="btn btn-outline-primary btn-sm w-100" style="font-size:.78rem;">
                                        <i class="ri-mail-line me-1"></i>Enviar correo
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm h-100" style="border-radius:10px;">
                            <div class="card-header border-bottom bg-transparent py-2 px-3">
                                <h6 class="mb-0 fw-semibold" style="font-size:.85rem;">
                                    <i class="ri-contacts-line me-2 text-primary"></i>Información de Contacto
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                @foreach ([
                                    ['ri-mail-line',       '#0d6efd', 'Correo',    $persona->correo    ?? 'Sin correo'],
                                    ['ri-phone-line',      '#198754', 'Celular',   $persona->celular   ?? 'Sin celular'],
                                    ['ri-phone-fill',      '#fd7e14', 'Teléfono',  $persona->telefono  ?? 'Sin teléfono'],
                                    ['ri-map-pin-line',    '#6f42c1', 'Dirección', $persona->direccion ?? 'Sin dirección'],
                                    ['ri-id-card-line',    '#0dcaf0', 'Carnet',    $persona->carnet    ?? '—'],
                                ] as [$ic, $col, $lbl, $val])
                                <div class="info-row">
                                    <div class="info-icon" style="background:{{ $col }}18;">
                                        <i class="{{ $ic }}" style="color:{{ $col }};font-size:.9rem;"></i>
                                    </div>
                                    <div>
                                        <div class="info-label">{{ $lbl }}</div>
                                        <div class="info-value">{{ $val }}</div>
                                    </div>
                                </div>
                                @endforeach

                                @if ($docente->estudios && $docente->estudios->count() > 0)
                                <hr class="my-3">
                                <h6 class="fw-semibold mb-3" style="font-size:.82rem;"><i class="ri-graduation-cap-line me-1 text-primary"></i>Estudios Académicos</h6>
                                @foreach ($docente->estudios as $estudio)
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="d-flex align-items-center justify-content-center rounded flex-shrink-0"
                                         style="width:28px;height:28px;background:#0d6efd12;">
                                        <i class="ri-award-line text-primary" style="font-size:.8rem;"></i>
                                    </div>
                                    <div style="font-size:.8rem;">
                                        <span class="fw-semibold">{{ $estudio->grado->nombre ?? '—' }}</span>
                                        @if($estudio->profesion)
                                            — {{ $estudio->profesion->nombre }}
                                        @endif
                                        @if($estudio->universidad)
                                            <span class="text-muted">({{ $estudio->universidad->sigla ?? $estudio->universidad->nombre }})</span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3"
                         style="width:64px;height:64px;background:#f8f9fa;">
                        <i class="ri-user-unfollow-line fs-2 text-muted"></i>
                    </div>
                    <h6 class="fw-semibold text-muted">Sin docente asignado</h6>
                    <p class="text-muted mb-0" style="font-size:.83rem;">Este módulo no tiene docente asignado actualmente</p>
                </div>
                @endif
            </div>

        </div>{{-- /tab-content --}}
    </div>
</div>

{{-- Modal Registrar Nota --}}
<div class="modal fade" id="modalRegistrarNota" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0" style="border-radius:12px;overflow:hidden;">
            <div class="modal-header py-3 border-0" style="background:linear-gradient(135deg,#0d6efd,#6f42c1);">
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25" style="width:36px;height:36px;">
                        <i class="ri-edit-2-line text-white fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-white fw-bold">Registrar / Editar Nota</h6>
                        <small class="text-white-50" id="estudianteNombreNota"></small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formRegistrarNota">
                    @csrf
                    <input type="hidden" id="matriculacionId" name="matriculacion_id">
                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-1" style="font-size:.83rem;">
                            <i class="ri-star-line me-1 text-primary"></i>Nota Regular <span class="text-muted fw-normal">(0–100)</span>
                        </label>
                        <input type="number" name="nota_regular" id="notaRegular" class="form-control"
                               min="0" max="100" step="0.01" placeholder="Ej: 85.5" required>
                        <div class="form-text"><i class="ri-information-line me-1"></i>Mínimo para aprobar: <strong>61</strong></div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-1" style="font-size:.83rem;">
                            <i class="ri-refresh-line me-1 text-info"></i>Nota Nivelación <span class="text-muted fw-normal">(opcional)</span>
                        </label>
                        <input type="number" name="nota_nivelacion" id="notaNivelacion" class="form-control"
                               min="0" max="100" step="0.01" placeholder="Ej: 75.0">
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm" style="background:linear-gradient(135deg,#0d6efd,#6f42c1);border:none;">
                            <i class="ri-save-line me-1"></i>Guardar Nota
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
    $(document).on('click', '.registrar-nota-btn', function() {
        $('#estudianteNombreNota').text($(this).data('estudiante-nombre'));
        $('#matriculacionId').val($(this).data('matriculacion-id'));
        $('#notaRegular').val($(this).data('nota-regular'));
        $('#notaNivelacion').val($(this).data('nota-nivelacion'));
        $('#modalRegistrarNota').modal('show');
    });

    $('#formRegistrarNota').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('admin.inscripciones.registrar-nota', ['matriculacion' => ':id']) }}"
                .replace(':id', $('#matriculacionId').val()),
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.success) {
                    $('#modalRegistrarNota').modal('hide');
                    Swal.fire({ icon:'success', title:'¡Éxito!', text:res.msg, timer:2000, showConfirmButton:false })
                        .then(() => location.reload());
                } else {
                    Swal.fire({ icon:'error', title:'Error', text:res.msg });
                }
            },
            error: function(xhr) {
                Swal.fire({ icon:'error', title:'Error', text:xhr.responseJSON?.msg || 'Error al registrar la nota.' });
            }
        });
    });
});
</script>
@endpush
