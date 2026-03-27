@if ($estudiante->inscripciones && $estudiante->inscripciones->count() > 0)

    {{-- Cabecera general --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h5 class="mb-0 fw-semibold">Programas Académicos</h5>
            <p class="text-muted small mb-0">
                {{ $estudiante->inscripciones->count() }} programa(s) registrado(s)
            </p>
        </div>
        <span class="badge bg-primary rounded-pill px-3 py-2 fs-12">
            <i class="ri-graduation-cap-line me-1"></i>
            {{ $estudiante->inscripciones->count() }} Programas
        </span>
    </div>

    {{-- Acordeón por programa --}}
    <div class="accordion" id="accordionProgramas" style="display:flex; flex-direction:column; gap:.75rem;">
        @foreach ($estudiante->inscripciones->sortByDesc('fecha_registro') as $index => $inscripcion)
            @php
                $oferta   = $inscripcion->ofertaAcademica;
                $programa = $oferta->programa ?? null;
                $matriculaciones = $inscripcion->matriculaciones ?? collect();

                $modulosTotales      = $matriculaciones->count();
                $modulosNoInicio     = $matriculaciones->filter(fn($m) => optional($m->modulo)->estado === 'No Inicio')->count();
                $modulosEnDesarrollo = $matriculaciones->filter(fn($m) => optional($m->modulo)->estado === 'En desarrollo')->count();
                $modulosConcluidos   = $matriculaciones->filter(fn($m) => optional($m->modulo)->estado === 'Concluido')->count();

                $modulosConNotas = $matriculaciones->filter(function ($mat) {
                    $mod = $mat->modulo;
                    return $mod && $mod->estado === 'Concluido'
                        && ($mat->nota_regular !== null || $mat->nota_nivelacion !== null);
                });
                $promedioPrograma = $modulosConNotas->avg(function ($mat) {
                    return max($mat->nota_regular ?? 0, $mat->nota_nivelacion ?? 0);
                });

                $porcentaje = $modulosTotales > 0 ? ($modulosConcluidos / $modulosTotales) * 100 : 0;

                $estadoColor = match ($inscripcion->estado) {
                    'Inscrito'     => 'success',
                    'Pre-Inscrito' => 'warning',
                    'Eliminado'    => 'danger',
                    'Finalizado'   => 'info',
                    default        => 'secondary',
                };
                $estadoIcon = match ($inscripcion->estado) {
                    'Inscrito'     => 'ri-checkbox-circle-line',
                    'Pre-Inscrito' => 'ri-time-line',
                    'Eliminado'    => 'ri-close-circle-line',
                    'Finalizado'   => 'ri-award-line',
                    default        => 'ri-question-line',
                };

                // Color de la inicial del programa
                $avatarColors = ['bg-primary','bg-success','bg-info','bg-warning','bg-danger','bg-purple'];
                $avatarColor  = $avatarColors[$index % count($avatarColors)];
                $inicial      = strtoupper(mb_substr($programa->nombre ?? 'P', 0, 1, 'UTF-8'));

                $asesor = optional(optional(optional($inscripcion->trabajador_cargo)->trabajador)->persona)->nombres ?? 'N/A';
            @endphp

            <div class="accordion-item border-0 rounded-3 overflow-hidden"
                 style="box-shadow: 0 2px 8px rgba(0,0,0,.08); border-left: 4px solid var(--bs-{{ $estadoColor }}) !important;">

                {{-- Header --}}
                <h2 class="accordion-header" id="acadHeading{{ $index }}">
                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }} py-3 px-4"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#acadCollapse{{ $index }}"
                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-controls="acadCollapse{{ $index }}"
                            style="background:transparent;">
                        <div class="d-flex align-items-center w-100 me-2 gap-3">

                            {{-- Avatar inicial --}}
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title {{ $avatarColor }} text-white rounded-2 fw-bold fs-16">
                                    {{ $inicial }}
                                </div>
                            </div>

                            {{-- Info principal --}}
                            <div class="flex-grow-1 min-w-0">
                                <h6 class="mb-1 fw-semibold text-truncate">
                                    {{ $programa->nombre ?? 'Programa no especificado' }}
                                </h6>
                                <div class="d-flex flex-wrap align-items-center gap-3 text-muted" style="font-size:.77rem;">
                                    <span>
                                        <i class="ri-map-pin-line me-1 text-primary"></i>
                                        {{ optional($oferta->sucursal)->nombre ?? 'N/A' }}
                                    </span>
                                    <span>
                                        <i class="ri-calendar-2-line me-1 text-primary"></i>
                                        {{ $oferta->gestion ?? 'N/A' }}
                                    </span>
                                    <span>
                                        <i class="ri-user-line me-1 text-primary"></i>
                                        {{ $asesor }}
                                    </span>
                                </div>
                            </div>

                            {{-- Derecha: estado + fecha + progreso --}}
                            <div class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                                <span class="badge bg-{{ $estadoColor }} rounded-pill px-2">
                                    <i class="{{ $estadoIcon }} me-1"></i>{{ $inscripcion->estado }}
                                </span>
                                <span class="text-muted" style="font-size:.72rem;">
                                    {{ \Carbon\Carbon::parse($inscripcion->fecha_registro)->format('d/m/Y') }}
                                </span>
                                @if ($modulosTotales > 0)
                                    <div style="width:90px;">
                                        <div class="d-flex justify-content-between mb-1" style="font-size:.68rem;">
                                            <span class="text-muted">Progreso</span>
                                            <span class="fw-semibold">{{ number_format($porcentaje, 0) }}%</span>
                                        </div>
                                        <div class="progress" style="height:5px;">
                                            <div class="progress-bar bg-success" style="width:{{ $porcentaje }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </button>
                </h2>

                {{-- Body --}}
                <div id="acadCollapse{{ $index }}"
                     class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                     aria-labelledby="acadHeading{{ $index }}">
                    <div class="accordion-body p-0">

                        {{-- Tira de info del programa --}}
                        <div class="px-4 py-3 bg-light border-top border-bottom d-flex flex-wrap gap-4">
                            <div>
                                <div class="text-muted" style="font-size:.72rem;">MODALIDAD</div>
                                <div class="fw-medium small">{{ optional($oferta->modalidad)->nombre ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="text-muted" style="font-size:.72rem;">INICIO</div>
                                <div class="fw-medium small">
                                    {{ $oferta->fecha_inicio_programa ? \Carbon\Carbon::parse($oferta->fecha_inicio_programa)->format('d/m/Y') : 'N/A' }}
                                </div>
                            </div>
                            <div>
                                <div class="text-muted" style="font-size:.72rem;">FIN</div>
                                <div class="fw-medium small">
                                    {{ $oferta->fecha_fin_programa ? \Carbon\Carbon::parse($oferta->fecha_fin_programa)->format('d/m/Y') : 'N/A' }}
                                </div>
                            </div>
                            @if ($modulosConcluidos > 0)
                                <div>
                                    <div class="text-muted" style="font-size:.72rem;">PROMEDIO</div>
                                    <div class="fw-semibold small text-primary">
                                        {{ number_format($promedioPrograma, 1) }} pts
                                    </div>
                                </div>
                            @endif
                            <div class="ms-auto">
                                <a href="{{ route('admin.ofertas.dashboard', $oferta->id) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   target="_blank">
                                    <i class="ri-external-link-line me-1"></i>Ver Oferta
                                </a>
                            </div>
                        </div>

                        {{-- Stats de módulos --}}
                        @if ($modulosTotales > 0)
                            <div class="px-4 py-3 border-bottom">
                                <div class="row g-2">
                                    <div class="col-6 col-md-3">
                                        <div class="border rounded-2 p-2 text-center h-100">
                                            <div class="fs-5 fw-bold text-primary mb-0">{{ $modulosTotales }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">Total Módulos</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="border rounded-2 p-2 text-center h-100" style="border-color:#ffc107 !important; background:#fffdf0;">
                                            <div class="fs-5 fw-bold text-warning mb-0">{{ $modulosNoInicio }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">No Iniciados</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="border rounded-2 p-2 text-center h-100" style="border-color:#0dcaf0 !important; background:#f0fbff;">
                                            <div class="fs-5 fw-bold text-info mb-0">{{ $modulosEnDesarrollo }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">En Desarrollo</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="border rounded-2 p-2 text-center h-100" style="border-color:#198754 !important; background:#f0fff5;">
                                            <div class="fs-5 fw-bold text-success mb-0">{{ $modulosConcluidos }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">Concluidos</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Barra de progreso visual --}}
                                @if ($modulosTotales > 0)
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between mb-1" style="font-size:.78rem;">
                                            <span class="text-muted">Progreso del programa</span>
                                            <span class="fw-semibold">{{ $modulosConcluidos }} / {{ $modulosTotales }} módulos</span>
                                        </div>
                                        <div class="progress rounded-pill" style="height:8px;">
                                            @php
                                                $pctNoInicio     = $modulosTotales > 0 ? ($modulosNoInicio / $modulosTotales) * 100 : 0;
                                                $pctEnDesarrollo = $modulosTotales > 0 ? ($modulosEnDesarrollo / $modulosTotales) * 100 : 0;
                                                $pctConcluido    = $modulosTotales > 0 ? ($modulosConcluidos / $modulosTotales) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar bg-success" style="width:{{ $pctConcluido }}%"></div>
                                            <div class="progress-bar bg-info" style="width:{{ $pctEnDesarrollo }}%"></div>
                                            <div class="progress-bar bg-warning" style="width:{{ $pctNoInicio }}%"></div>
                                        </div>
                                        <div class="d-flex gap-3 mt-2" style="font-size:.7rem;">
                                            <span><span class="badge bg-success me-1">&nbsp;</span>Concluido</span>
                                            <span><span class="badge bg-info me-1">&nbsp;</span>En desarrollo</span>
                                            <span><span class="badge bg-warning me-1">&nbsp;</span>No iniciado</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Tabla de módulos --}}
                        @if ($matriculaciones->count() > 0)
                            <div class="px-3 py-3">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" style="font-size:.85rem;">
                                        <thead>
                                            <tr style="background:#f8f9fa;">
                                                <th width="6%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.72rem;">#</th>
                                                <th width="34%" class="border-0 py-2 text-muted fw-semibold" style="font-size:.72rem;">MÓDULO</th>
                                                <th width="14%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.72rem;">ESTADO</th>
                                                <th width="14%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.72rem;">INICIO</th>
                                                <th width="14%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.72rem;">FIN</th>
                                                <th width="18%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.72rem;">NOTAS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($matriculaciones->sortBy('modulo.n_modulo') as $matriculacion)
                                                @php
                                                    $modulo         = $matriculacion->modulo;
                                                    $notaRegular    = $matriculacion->nota_regular;
                                                    $notaNivelacion = $matriculacion->nota_nivelacion;
                                                    $estadoModulo   = optional($modulo)->estado ?? 'No Inicio';

                                                    $estadoColorModulo = match ($estadoModulo) {
                                                        'No Inicio'     => 'warning',
                                                        'En desarrollo' => 'primary',
                                                        'Concluido'     => 'success',
                                                        default         => 'secondary',
                                                    };
                                                    $estadoIconModulo = match ($estadoModulo) {
                                                        'No Inicio'     => 'ri-time-line',
                                                        'En desarrollo' => 'ri-play-circle-line',
                                                        'Concluido'     => 'ri-checkbox-circle-line',
                                                        default         => 'ri-question-line',
                                                    };

                                                    $formatearNota = function ($nota, $estado) use ($oferta) {
                                                        if ($estado !== 'Concluido') return '<span class="text-muted">—</span>';
                                                        if ($nota === null || $nota == 0) return '<span class="badge bg-light text-muted border">S/N</span>';
                                                        $aprueba = $nota >= ($oferta->nota_minima ?? 51);
                                                        $cls = $aprueba ? 'bg-success' : 'bg-danger';
                                                        return '<span class="badge ' . $cls . '">' . number_format($nota, 1) . '</span>';
                                                    };
                                                @endphp
                                                <tr>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-dark border">{{ optional($modulo)->n_modulo ?? '—' }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            @if ($modulo && $modulo->color)
                                                                <div class="flex-shrink-0 rounded-1"
                                                                     style="width:8px;height:32px;background-color:{{ $modulo->color }};"></div>
                                                            @endif
                                                            <div>
                                                                <div class="fw-medium">{{ optional($modulo)->nombre ?? 'Módulo no especificado' }}</div>
                                                                @if ($modulo && $modulo->docente)
                                                                    <div class="text-muted" style="font-size:.72rem;">
                                                                        <i class="ri-user-3-line me-1"></i>
                                                                        {{ optional(optional($modulo->docente)->persona)->nombres ?? '' }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-{{ $estadoColorModulo }}-subtle text-{{ $estadoColorModulo }} border border-{{ $estadoColorModulo }}-subtle">
                                                            <i class="{{ $estadoIconModulo }} me-1"></i>{{ $estadoModulo }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center text-muted small">
                                                        {{ $modulo && $modulo->fecha_inicio ? \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y') : '—' }}
                                                    </td>
                                                    <td class="text-center text-muted small">
                                                        {{ $modulo && $modulo->fecha_fin ? \Carbon\Carbon::parse($modulo->fecha_fin)->format('d/m/Y') : '—' }}
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                                            <div class="text-center">
                                                                <div class="text-muted" style="font-size:.65rem;">Regular</div>
                                                                {!! $formatearNota($notaRegular, $estadoModulo) !!}
                                                            </div>
                                                            <div class="vr opacity-25"></div>
                                                            <div class="text-center">
                                                                <div class="text-muted" style="font-size:.65rem;">Niv.</div>
                                                                {!! $formatearNota($notaNivelacion, $estadoModulo) !!}
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5 px-4">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title bg-light text-secondary rounded-circle">
                                        <i class="ri-book-2-line fs-2"></i>
                                    </div>
                                </div>
                                <h5 class="mb-1">Sin módulos matriculados</h5>
                                <p class="text-muted mb-0 small">Este programa no tiene módulos asignados aún.</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Footer resumen general --}}
    <div class="mt-3 p-3 rounded-3 border bg-light d-flex flex-wrap gap-4">
        @php
            $inscritosActivos    = $estudiante->inscripciones->where('estado', 'Inscrito')->count();
            $programasFinalizados = $estudiante->inscripciones->where('estado', 'Finalizado')->count();
        @endphp
        <div class="d-flex align-items-center gap-2">
            <div class="avatar-xs">
                <div class="avatar-title bg-primary-subtle text-primary rounded">
                    <i class="ri-book-open-line fs-14"></i>
                </div>
            </div>
            <div>
                <div class="fw-semibold lh-1">{{ $estudiante->inscripciones->count() }}</div>
                <div class="text-muted" style="font-size:.72rem;">Programas Totales</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="avatar-xs">
                <div class="avatar-title bg-success-subtle text-success rounded">
                    <i class="ri-checkbox-circle-line fs-14"></i>
                </div>
            </div>
            <div>
                <div class="fw-semibold lh-1">{{ $inscritosActivos }}</div>
                <div class="text-muted" style="font-size:.72rem;">Programas Activos</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="avatar-xs">
                <div class="avatar-title bg-info-subtle text-info rounded">
                    <i class="ri-award-line fs-14"></i>
                </div>
            </div>
            <div>
                <div class="fw-semibold lh-1">{{ $programasFinalizados }}</div>
                <div class="text-muted" style="font-size:.72rem;">Finalizados</div>
            </div>
        </div>
    </div>

@else
    <div class="card border">
        <div class="card-body text-center py-5">
            <div class="avatar-lg mx-auto mb-3">
                <div class="avatar-title bg-light text-secondary rounded-circle">
                    <i class="ri-book-2-line fs-2"></i>
                </div>
            </div>
            <h5 class="mb-2">No hay programas académicos</h5>
            <p class="text-muted mb-0">El estudiante no está inscrito en ningún programa académico.</p>
            <a href="{{ route('admin.inscripciones.registrar') }}" class="btn btn-primary mt-3">
                <i class="ri-user-add-line me-1"></i> Realizar Inscripción
            </a>
        </div>
    </div>
@endif
