@extends('admin.dashboard')

@push('style')
<style>
.modulo-tabs { border-bottom: 2px solid #e5e7eb; gap: 3px; }
.modulo-tabs .nav-link { border: none; color: #6b7280; font-weight: 500; padding: 6px 12px;
    border-bottom: 2px solid transparent; border-radius: 6px 6px 0 0; font-size: .75rem; transition: all .15s; }
.modulo-tabs .nav-link:hover { color: var(--dash-primary); background: var(--dash-primary-light); }
.modulo-tabs .nav-link.active { color: var(--dash-primary); border-bottom-color: var(--dash-primary); background: transparent; font-weight: 600; }
.modulo-tabs .nav-link i { font-size: .85rem; }

.tbl-modulo th { font-size: .65rem; text-transform: uppercase; font-weight: 600; color: #6b7280;
    padding: 8px 10px !important; background: #f9fafb; border: none !important; letter-spacing: .03em; }
.tbl-modulo td { font-size: .75rem; padding: 8px 10px !important; vertical-align: middle; }

.nota-circle { display:inline-flex; align-items:center; justify-content:center;
    width:30px; height:30px; border-radius:50%; font-weight:700; font-size:.7rem; }

.row-info { display:flex; align-items:center; gap:8px; padding:6px 0; border-bottom:1px solid #f3f4f6; }
.row-info:last-child { border-bottom:none; }
.icon-box { width:26px; height:26px; border-radius:5px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.label-info { font-size:.6rem; text-transform:uppercase; font-weight: 600; color: #9ca3af; margin-bottom:0; }
.value-info { font-size:.75rem; font-weight: 500; color: #1f2937; }
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
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-2 mb-2" style="border-bottom: 1px solid #e5e7eb;">
    <div class="d-flex align-items-center gap-2">
        <nav>
            <ol class="breadcrumb mb-0" style="font-size:.7rem;">
                <li class="breadcrumb-item"><a href="{{ route('admin.ofertas.listar') }}" class="text-decoration-none text-muted">Ofertas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.ofertas.dashboard', $oferta->id) }}" class="text-decoration-none text-muted">{{ $oferta->codigo }}</a></li>
                <li class="breadcrumb-item active text-dark fw-semibold">M{{ $modulo->n_modulo }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.ofertas.dashboard', $oferta->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:.7rem;">
        <i class="ri-arrow-left-line me-1"></i>Volver
    </a>
</div>

{{-- Banner Principal --}}
<div class="card border-0 shadow-sm mb-2" style="border-radius:12px;overflow:hidden;">
    <div class="card-body p-0">
        <div class="d-flex align-items-center gap-3 p-3" style="background: linear-gradient(135deg, {{ $modulo->color }}08 0%, {{ $modulo->color }}15 100%); border-left: 4px solid {{ $modulo->color }};">
            <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold text-white flex-shrink-0"
                 style="width:52px;height:52px;background:{{ $modulo->color }};font-size:1.2rem;box-shadow: 0 4px 12px {{ $modulo->color }}40;">
                M{{ $modulo->n_modulo }}
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-0 fw-bold" style="font-size:1rem;">{{ $modulo->nombre }}</h5>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="text-muted" style="font-size:.75rem;">{{ $programa }}</span>
                    <span class="text-secondary">•</span>
                    <span class="badge" style="background:{{ $oferta->color }}20;color:{{ $oferta->color }};font-size:.65rem;">{{ $oferta->fase->nombre ?? 'Sin fase' }}</span>
                </div>
            </div>
            @if ($docente)
            <div class="d-flex align-items-center gap-2 d-none d-lg-flex">
                <div class="d-flex align-items-center justify-content-center rounded-circle bg-white flex-shrink-0"
                     style="width:36px;height:36px;border:2px solid {{ $modulo->color }};">
                    <i class="ri-user-star-line" style="color:{{ $modulo->color }};font-size:.9rem;"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted" style="font-size:.55rem;text-transform:uppercase;font-weight:700;">Docente</p>
                    <span class="fw-semibold" style="font-size:.75rem;">{{ $persona->nombres }}</span>
                </div>
            </div>
            @endif
        </div>

        {{-- Stats Row --}}
        <div class="row g-0 p-2" style="background:#fafafa;">
            @php
            $stats = [
                ['icon'=>'ri-calendar-2-line',    'color'=>'#3b82f6', 'label'=>'Inicio',   'value'=> $modulo->fecha_inicio ? Carbon::parse($modulo->fecha_inicio)->format('d M') : '—'],
                ['icon'=>'ri-calendar-check-line', 'color'=>'#10b981', 'label'=>'Fin',      'value'=> $modulo->fecha_fin    ? Carbon::parse($modulo->fecha_fin)->format('d M')    : '—'],
                ['icon'=>'ri-time-line',            'color'=>'#f59e0b', 'label'=>'Duración','value'=> $duracion !== null ? $duracion.' días' : '—'],
                ['icon'=>'ri-team-line',            'color'=>'#8b5cf6', 'label'=>'Estudiantes','value'=> $totalParticipantes],
            ];
            @endphp
            @foreach ($stats as $i => $s)
            <div class="col-6 col-md-3" style="{{ $i < 3 ? 'border-right:1px solid #e5e7eb;' : '' }}">
                <div class="text-center py-1">
                    <i class="{{ $s['icon'] }}" style="color:{{ $s['color'] }};font-size:.9rem;"></i>
                    <div class="fw-bold mt-1" style="font-size:.85rem;">{{ $s['value'] }}</div>
                    <div class="text-muted" style="font-size:.55rem;text-transform:uppercase;font-weight:600;">{{ $s['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Tabs Container --}}
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-body p-0">
        <ul class="nav modulo-tabs px-2 pt-2">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#tab-detalles">
                    <i class="ri-file-list-3-line me-1"></i>Detalles
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-horarios">
                    <i class="ri-calendar-event-line me-1"></i>Horarios
                    <span class="badge bg-warning-subtle text-warning rounded-pill ms-1" style="font-size:.55rem;">{{ $modulo->horarios->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-participantes">
                    <i class="ri-user-star-line me-1"></i>Notas
                    <span class="badge bg-purple-subtle text-purple rounded-pill ms-1" style="font-size:.55rem;background:#8b5cf620;color:#8b5cf6;">{{ $totalParticipantes }}</span>
                </a>
            </li>
            @if ($docente)
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-docente">
                    <i class="ri-contacts-line me-1"></i>Docente
                </a>
            </li>
            @endif
        </ul>

        <div class="tab-content p-3">

            {{-- Tab Detalles --}}
            <div class="tab-pane fade show active" id="tab-detalles">
                <div class="row g-3">
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm h-100" style="border-radius:10px;">
                            <div class="card-header border-bottom bg-transparent py-2 px-3" style="background:linear-gradient(135deg, var(--dash-primary-light), #f0fdfa);">
                                <h6 class="mb-0 fw-semibold" style="font-size:.75rem;">
                                    <i class="ri-book-2-line me-2" style="color:var(--dash-primary);"></i>Información del Módulo
                                </h6>
                            </div>
                            <div class="card-body p-2">
                                <div class="row-info">
                                    <div class="icon-box" style="background:var(--dash-primary-light);"><i class="ri-hashtag" style="color:var(--dash-primary);font-size:.75rem;"></i></div>
                                    <div><div class="label-info">Número</div><div class="value-info">Módulo {{ $modulo->n_modulo }}</div></div>
                                </div>
                                <div class="row-info">
                                    <div class="icon-box" style="background:#8b5cf620;"><i class="ri-book-open-line" style="color:#8b5cf6;font-size:.75rem;"></i></div>
                                    <div><div class="label-info">Nombre</div><div class="value-info">{{ $modulo->nombre }}</div></div>
                                </div>
                                <div class="row-info">
                                    <div class="icon-box" style="background:{{ $modulo->color }}20;">
                                        <i class="ri-palette-line" style="color:{{ $modulo->color }};font-size:.75rem;"></i>
                                    </div>
                                    <div>
                                        <div class="label-info">Color</div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="rounded-circle d-inline-block" style="width:10px;height:10px;background:{{ $modulo->color }};"></span>
                                            <span class="value-info">{{ $modulo->color }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-info">
                                    <div class="icon-box" style="background:#10b98120;"><i class="ri-building-line" style="color:#10b981;font-size:.75rem;"></i></div>
                                    <div><div class="label-info">Programa</div><div class="value-info">{{ $programa }}</div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm h-100" style="border-radius:10px;">
                            <div class="card-header border-bottom bg-transparent py-2 px-3" style="background:linear-gradient(135deg, #dcfce7, #f0fdf4);">
                                <h6 class="mb-0 fw-semibold" style="font-size:.75rem;">
                                    <i class="ri-bar-chart-line me-2 text-success"></i>Resumen de Calificaciones
                                </h6>
                            </div>
                            <div class="card-body p-2">
                                <div class="row g-2">
                                    @php
                                    $statCards = [
                                        ['value'=>$totalParticipantes, 'label'=>'Estudiantes', 'color'=>'#8b5cf6', 'bg'=>'#8b5cf620', 'icon'=>'ri-user-star-line'],
                                        ['value'=>$aprobados,          'label'=>'Aprobados',  'color'=>'#10b981', 'bg'=>'#10b98120', 'icon'=>'ri-checkbox-circle-line'],
                                        ['value'=>$reprobados,         'label'=>'Reprobados',   'color'=>'#ef4444', 'bg'=>'#ef444420', 'icon'=>'ri-close-circle-line'],
                                        ['value'=>$pendientes,         'label'=>'Pendientes',  'color'=>'#f59e0b', 'bg'=>'#f59e0b20', 'icon'=>'ri-time-line'],
                                    ];
                                    @endphp
                                    @foreach ($statCards as $sc)
                                    <div class="col-6 col-md-3">
                                        <div class="p-2 rounded-2 text-center h-100" style="background:{{ $sc['bg'] }};">
                                            <i class="{{ $sc['icon'] }}" style="color:{{ $sc['color'] }};font-size:1rem;"></i>
                                            <div class="fw-bold mt-1" style="font-size:1.1rem;color:{{ $sc['color'] }};">{{ $sc['value'] }}</div>
                                            <div class="text-muted" style="font-size:.6rem;">{{ $sc['label'] }}</div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @if ($totalParticipantes > 0)
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted" style="font-size:.65rem;">Tasa de aprobación</span>
                                        <span class="fw-bold" style="font-size:.7rem;color:#10b981;">{{ round(($aprobados / $totalParticipantes) * 100) }}%</span>
                                    </div>
                                    <div class="progress rounded-pill" style="height:8px;background:#e5e7eb;">
                                        <div class="progress-bar rounded-pill bg-success" role="progressbar" style="width: {{ ($aprobados / $totalParticipantes) * 100 }}%;">
                                        </div>
                                    </div>
                                </div>
                                @endif
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
                        <h6 class="mb-0 fw-semibold" style="font-size:.75rem;">
                            <i class="ri-calendar-event-line me-2" style="color:var(--dash-primary);"></i>Sesiones Programadas
                        </h6>
                        <span class="badge" style="background:var(--dash-primary-light);color:var(--dash-primary);font-size:.6rem;">
                            {{ $modulo->horarios->count() }} sesiones
                        </span>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 tbl-modulo">
                            <thead>
                                <tr>
                                    <th class="text-center" width="30">#</th>
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
                                        'Confirmado'   => ['#10b981', '#10b98120', 'ri-checkbox-circle-line'],
                                        'Desarrollado' => ['#3b82f6', '#3b82f620', 'ri-check-double-line'],
                                        'Postergado'   => ['#f59e0b', '#f59e0b20', 'ri-pause-circle-line'],
                                        default        => ['#6b7280', '#f3f4f6', 'ri-question-line'],
                                    };
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <span class="d-flex align-items-center justify-content-center rounded-circle mx-auto fw-semibold"
                                              style="width:22px;height:22px;background:#f3f4f6;font-size:.6rem;color:#6b7280;">{{ $index + 1 }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold" style="font-size:.7rem;">{{ $fechaHorario }}</div>
                                        <div class="text-muted" style="font-size:.6rem;">{{ $diaSemana }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border" style="font-size:.65rem;">
                                            <i class="ri-time-line me-1 text-muted"></i>
                                            {{ substr($horario->hora_inicio, 0, 5) }} - {{ substr($horario->hora_fin, 0, 5) }}
                                        </span>
                                    </td>
                                    <td style="font-size:.7rem;">
                                        {{ $horario->trabajador_cargo->trabajador->persona->nombres ?? '—' }}
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill px-2" style="background:{{ $eBg }};color:{{ $eColor }};font-size:.6rem;">
                                            <i class="{{ $eIcon }} me-1"></i>{{ $horario->estado }}
                                        </span>
                                    </td>
                                    <td style="font-size:.7rem;color:#6b7280;">
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
                    <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3" style="width:56px;height:56px;background:#f3f4f6;">
                        <i class="ri-calendar-line fs-3 text-muted"></i>
                    </div>
                    <h6 class="fw-semibold text-muted">No hay horarios</h6>
                    <p class="text-muted mb-0" style="font-size:.75rem;">Este módulo no tiene sesiones programadas</p>
                </div>
                @endif
            </div>

            {{-- Tab Participantes --}}
            <div class="tab-pane fade" id="tab-participantes">
                <div class="card border-0 shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="card-header border-bottom bg-transparent py-2 px-3 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold" style="font-size:.75rem;">
                            <i class="ri-user-star-line me-2" style="color:var(--dash-primary);"></i>Calificaciones
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 tbl-modulo">
                            <thead>
                                <tr>
                                    <th class="text-center" width="30">#</th>
                                    <th>Estudiante</th>
                                    <th class="text-center">Nota</th>
                                    <th class="text-center">Nivel.</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center" width="50">Acción</th>
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
                                        $nr >= 90             => ['#10b981','#10b98120'],
                                        $nr >= 80             => ['#3b82f6','#3b82f620'],
                                        $nr >= 70             => ['#f59e0b','#f59e0b20'],
                                        $nr >= 61             => ['#eab308','#eab30820'],
                                        $nr !== null          => ['#ef4444','#ef444420'],
                                        default               => ['#9ca3af','#f3f4f6'],
                                    };

                                    [$eLabel, $eColor, $eBg] = match(true) {
                                        $nr >= 61             => ['Aprobado', '#10b981','#10b98120'],
                                        $nr !== null          => ['Reprobado','#ef4444','#ef444420'],
                                        default               => ['Pendiente','#f59e0b','#f59e0b20'],
                                    };
                                @endphp
                                <tr>
                                    <td class="text-center text-muted" style="font-size:.65rem;">{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ route('admin.estudiantes.detalle', ['id' => $matriculacion->inscripcion->estudiante->id]) }}"
                                           class="text-decoration-none d-flex align-items-center gap-2">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold flex-shrink-0"
                                                 style="width:28px;height:28px;background:var(--dash-primary-light);color:var(--dash-primary);font-size:.65rem;">
                                                {{ $inicial }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark" style="font-size:.7rem;">
                                                    {{ $per->nombres }} {{ $per->apellido_paterno }}
                                                </div>
                                                <div class="text-muted" style="font-size:.6rem;">{{ $per->carnet }}</div>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        @if ($nr !== null)
                                            <div class="nota-circle mx-auto" style="background:{{ $nrBg }};color:{{ $nrColor }};">{{ $nr }}</div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($nn !== null)
                                            <div class="nota-circle mx-auto" style="background:#0891b220;color:#0891b2;">{{ $nn }}</div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill px-2" style="background:{{ $eBg }};color:{{ $eColor }};font-size:.6rem;">
                                            {{ $eLabel }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-sm registrar-nota-btn"
                                            style="font-size:.65rem;background:var(--dash-primary-light);color:var(--dash-primary);border:none;padding:4px 8px;"
                                            data-matriculacion-id="{{ $matriculacion->id }}"
                                            data-estudiante-nombre="{{ $per->nombres }} {{ $per->apellido_paterno }}"
                                            data-nota-regular="{{ $nr ?? '' }}"
                                            data-nota-nivelacion="{{ $nn ?? '' }}">
                                            <i class="{{ $nr !== null ? 'ri-edit-line' : 'ri-add-line' }}"></i>
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
                <div class="row g-3">
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm text-center h-100" style="border-radius:10px;overflow:hidden;">
                            <div style="height:4px;background:linear-gradient(90deg,var(--dash-primary),#14b8a6);"></div>
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-2 fw-bold text-white"
                                     style="width:60px;height:60px;background:linear-gradient(135deg,var(--dash-primary),#14b8a6);font-size:1.3rem;">
                                    {{ strtoupper(substr($persona->nombres, 0, 1)) }}
                                </div>
                                <h6 class="fw-bold mb-1" style="font-size:.85rem;">{{ $persona->nombres }} {{ $persona->apellido_paterno }}</h6>
                                <span class="badge" style="background:var(--dash-primary-light);color:var(--dash-primary);font-size:.6rem;">
                                    Docente del Módulo
                                </span>
                                @if ($persona->correo)
                                <div class="mt-3">
                                    <a href="mailto:{{ $persona->correo }}" class="btn btn-outline-primary btn-sm w-100" style="font-size:.7rem;">
                                        <i class="ri-mail-line me-1"></i>Enviar Correo
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm h-100" style="border-radius:10px;">
                            <div class="card-header border-bottom bg-transparent py-2 px-3">
                                <h6 class="mb-0 fw-semibold" style="font-size:.75rem;">
                                    <i class="ri-contacts-line me-2" style="color:var(--dash-primary);"></i>Información de Contacto
                                </h6>
                            </div>
                            <div class="card-body p-2">
                                @foreach ([
                                    ['ri-mail-line',       '#3b82f6', 'Correo',    $persona->correo    ?? 'Sin correo'],
                                    ['ri-phone-line',      '#10b981', 'Celular',   $persona->celular   ?? 'Sin celular'],
                                    ['ri-map-pin-line',    '#8b5cf6', 'Dirección', $persona->direccion ?? 'Sin dirección'],
                                    ['ri-id-card-line',    '#06b6d4', 'Carnet',    $persona->carnet    ?? '—'],
                                ] as [$ic, $col, $lbl, $val])
                                <div class="row-info">
                                    <div class="icon-box" style="background:{{ $col }}15;">
                                        <i class="{{ $ic }}" style="color:{{ $col }};font-size:.75rem;"></i>
                                    </div>
                                    <div>
                                        <div class="label-info">{{ $lbl }}</div>
                                        <div class="value-info">{{ $val }}</div>
                                    </div>
                                </div>
                                @endforeach

                                @if ($docente->estudios && $docente->estudios->count() > 0)
                                <hr class="my-2">
                                <h6 class="fw-semibold mb-2" style="font-size:.7rem;"><i class="ri-graduation-cap-line me-1" style="color:var(--dash-primary);"></i>Formación Académica</h6>
                                @foreach ($docente->estudios as $estudio)
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <div class="d-flex align-items-center justify-content-center rounded flex-shrink-0"
                                         style="width:22px;height:22px;background:var(--dash-primary-light);">
                                        <i class="ri-award-line" style="color:var(--dash-primary);font-size:.7rem;"></i>
                                    </div>
                                    <div style="font-size:.7rem;">
                                        <span class="fw-semibold">{{ $estudio->grado->nombre ?? '—' }}</span>
                                        @if($estudio->profesion)
                                            — {{ $estudio->profesion->nombre }}
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
                    <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3" style="width:56px;height:56px;background:#f3f4f6;">
                        <i class="ri-user-unfollow-line fs-3 text-muted"></i>
                    </div>
                    <h6 class="fw-semibold text-muted">Sin docente asignado</h6>
                    <p class="text-muted mb-0" style="font-size:.75rem;">Este módulo no tiene docente asignado</p>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- Modal Registrar Nota --}}
<div class="modal fade" id="modalRegistrarNota" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0" style="border-radius:12px;overflow:hidden;">
            <div class="modal-header py-2 border-0" style="background:linear-gradient(135deg,var(--dash-primary),#14b8a6);">
                <h6 class="mb-0 text-white fw-bold" style="font-size:.85rem;">
                    <i class="ri-edit-2-line me-2"></i>Registrar Nota
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <form id="formRegistrarNota">
                    @csrf
                    <input type="hidden" id="matriculacionId" name="matriculacion_id">
                    <div class="mb-2">
                        <label class="form-label fw-semibold mb-1" style="font-size:.75rem;">Estudiante</label>
                        <div class="p-2 rounded" style="background:#f3f4f6;" id="estudianteNombreNota"></div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold mb-1" style="font-size:.75rem;">
                            <i class="ri-star-line me-1 text-warning"></i>Nota Regular <span class="text-muted">(0-100)</span>
                        </label>
                        <input type="number" name="nota_regular" id="notaRegular" class="form-control"
                               min="0" max="100" step="0.01" placeholder="Ej: 85" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold mb-1" style="font-size:.75rem;">
                            <i class="ri-refresh-line me-1 text-info"></i>Nota Nivelación <span class="text-muted">(opcional)</span>
                        </label>
                        <input type="number" name="nota_nivelacion" id="notaNivelacion" class="form-control"
                               min="0" max="100" step="0.01" placeholder="Ej: 75">
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm" style="background:var(--dash-primary);border:none;">
                            <i class="ri-save-line me-1"></i>Guardar
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