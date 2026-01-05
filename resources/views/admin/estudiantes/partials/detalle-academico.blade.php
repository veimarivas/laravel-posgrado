@if ($estudiante->inscripciones && $estudiante->inscripciones->count() > 0)
    <div class="card border">
        <div class="card-header border-bottom bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fs-16">Programas Académicos</h5>
                <span class="badge bg-primary">{{ $estudiante->inscripciones->count() }}
                    Programas</span>
            </div>
        </div>
        <div class="card-body p-0">
            <!-- Acordeón para múltiples programas -->
            <div class="accordion accordion-flush" id="accordionProgramas">
                @foreach ($estudiante->inscripciones->sortByDesc('fecha_registro') as $index => $inscripcion)
                    @php
                        $oferta = $inscripcion->ofertaAcademica;
                        $programa = $oferta->programa ?? null;
                        $matriculaciones = $inscripcion->matriculaciones ?? collect();

                        // Estadísticas del programa
                        $modulosTotales = $matriculaciones->count();
                        $modulosNoInicio = $matriculaciones
                            ->filter(function ($mat) {
                                $modulo = $mat->modulo;
                                return $modulo && $modulo->estado === 'No Inicio';
                            })
                            ->count();
                        $modulosEnDesarrollo = $matriculaciones
                            ->filter(function ($mat) {
                                $modulo = $mat->modulo;
                                return $modulo && $modulo->estado === 'En desarrollo';
                            })
                            ->count();
                        $modulosConcluidos = $matriculaciones
                            ->filter(function ($mat) {
                                $modulo = $mat->modulo;
                                return $modulo && $modulo->estado === 'Concluido';
                            })
                            ->count();

                        // Notas para módulos concluidos
                        $modulosConNotas = $matriculaciones->filter(function ($mat) {
                            $modulo = $mat->modulo;
                            return $modulo &&
                                $modulo->estado === 'Concluido' &&
                                ($mat->nota_regular !== null || $mat->nota_nivelacion !== null);
                        });

                        $promedioPrograma = $modulosConNotas->avg(function ($mat) {
                            return max($mat->nota_regular ?? 0, $mat->nota_nivelacion ?? 0);
                        });

                        // Determinar color del estado general del programa
                        $estadoColor = match ($inscripcion->estado) {
                            'Inscrito' => 'success',
                            'Pre-Inscrito' => 'warning',
                            'Eliminado' => 'danger',
                            'Finalizado' => 'info',
                            default => 'secondary',
                        };

                        // Determinar icono según estado del programa
                        $estadoIcon = match ($inscripcion->estado) {
                            'Inscrito' => 'ri-checkbox-circle-line',
                            'Pre-Inscrito' => 'ri-time-line',
                            'Eliminado' => 'ri-close-circle-line',
                            'Finalizado' => 'ri-award-line',
                            default => 'ri-question-line',
                        };
                    @endphp

                    <div class="accordion-item border-bottom">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}"
                                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-controls="collapse{{ $index }}">
                                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded">
                                                    <i class="ri-book-2-line fs-16"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">
                                                {{ $programa->nombre ?? 'Programa no especificado' }}
                                            </h6>
                                            <div class="text-muted small">
                                                <span class="me-2">
                                                    <i class="ri-map-pin-line me-1"></i>
                                                    {{ $oferta->sucursal->nombre ?? 'N/A' }}
                                                </span>
                                                •
                                                <span class="mx-2">
                                                    <i class="ri-calendar-line me-1"></i>
                                                    {{ $oferta->gestion ?? 'N/A' }}
                                                </span>
                                                •
                                                <span class="ms-2">
                                                    <i class="ri-user-line me-1"></i>
                                                    {{ $inscripcion->trabajador_cargo->trabajador->persona->nombres ?? 'Asesor N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $estadoColor }} mb-1">
                                            <i class="{{ $estadoIcon }} me-1"></i>{{ $inscripcion->estado }}
                                        </span>
                                        <div class="text-muted small">
                                            {{ \Carbon\Carbon::parse($inscripcion->fecha_registro)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}"
                            class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                            aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionProgramas">
                            <div class="accordion-body p-4">
                                <!-- Encabezado del programa -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="d-flex flex-column">
                                            <span class="text-muted small">Modalidad</span>
                                            <span class="fw-medium">{{ $oferta->modalidad->nombre ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column">
                                            <span class="text-muted small">Fecha Inicio</span>
                                            <span class="fw-medium">
                                                {{ $oferta->fecha_inicio_programa ? \Carbon\Carbon::parse($oferta->fecha_inicio_programa)->format('d/m/Y') : 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column">
                                            <span class="text-muted small">Fecha Fin</span>
                                            <span class="fw-medium">
                                                {{ $oferta->fecha_fin_programa ? \Carbon\Carbon::parse($oferta->fecha_fin_programa)->format('d/m/Y') : 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Resumen de progreso -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card bg-light border">
                                            <div class="card-body p-3">
                                                <div class="row align-items-center">
                                                    <div class="col-md-8">
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <span class="fw-medium">Progreso
                                                                del Programa</span>
                                                            <span class="fw-bold text-primary">
                                                                {{ $modulosConcluidos }}/{{ $modulosTotales }}
                                                                Módulos
                                                            </span>
                                                        </div>
                                                        <div class="progress" style="height: 8px;">
                                                            @php
                                                                $porcentaje =
                                                                    $modulosTotales > 0
                                                                        ? ($modulosConcluidos / $modulosTotales) * 100
                                                                        : 0;
                                                            @endphp
                                                            <div class="progress-bar bg-success" role="progressbar"
                                                                style="width: {{ $porcentaje }}%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                                        @if ($modulosConcluidos > 0)
                                                            <span class="badge bg-primary">
                                                                <i class="ri-star-line me-1"></i>
                                                                Promedio:
                                                                {{ number_format($promedioPrograma, 1) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Estadísticas rápidas -->
                                <div class="row mb-4">
                                    <div class="col-md-3 col-6">
                                        <div class="border rounded p-3 text-center">
                                            <h5 class="text-primary mb-1">
                                                {{ $modulosTotales }}</h5>
                                            <p class="text-muted mb-0 small">Total Módulos</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="border rounded p-3 text-center">
                                            <h5 class="text-warning mb-1">
                                                {{ $modulosNoInicio }}</h5>
                                            <p class="text-muted mb-0 small">No Iniciados</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="border rounded p-3 text-center">
                                            <h5 class="text-info mb-1">
                                                {{ $modulosEnDesarrollo }}</h5>
                                            <p class="text-muted mb-0 small">En Desarrollo</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="border rounded p-3 text-center">
                                            <h5 class="text-success mb-1">
                                                {{ $modulosConcluidos }}</h5>
                                            <p class="text-muted mb-0 small">Concluidos</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabla de módulos -->
                                @if ($matriculaciones->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="8%" class="text-center">#
                                                    </th>
                                                    <th width="32%">Módulo</th>
                                                    <th width="15%" class="text-center">
                                                        Estado</th>
                                                    <th width="15%" class="text-center">
                                                        Fecha Inicio</th>
                                                    <th width="15%" class="text-center">
                                                        Fecha Fin</th>
                                                    <th width="15%" class="text-center">
                                                        Notas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($matriculaciones->sortBy('modulo.n_modulo') as $matriculacion)
                                                    @php
                                                        $modulo = $matriculacion->modulo;
                                                        $notaRegular = $matriculacion->nota_regular;
                                                        $notaNivelacion = $matriculacion->nota_nivelacion;
                                                        $estadoModulo = $modulo->estado ?? 'No Inicio';

                                                        // Determinar color según estado
                                                        $estadoColorModulo = match ($estadoModulo) {
                                                            'No Inicio' => 'warning',
                                                            'En desarrollo' => 'primary',
                                                            'Concluido' => 'success',
                                                            default => 'secondary',
                                                        };

                                                        // Determinar icono según estado
                                                        $estadoIconModulo = match ($estadoModulo) {
                                                            'No Inicio' => 'ri-time-line',
                                                            'En desarrollo' => 'ri-play-circle-line',
                                                            'Concluido' => 'ri-checkbox-circle-line',
                                                            default => 'ri-question-line',
                                                        };

                                                        // Función para formatear nota
                                                        $formatearNota = function ($nota, $estado) use ($oferta) {
                                                            if ($estado !== 'Concluido') {
                                                                return '<span class="text-muted">-</span>';
                                                            }
                                                            if ($nota === null || $nota === 0) {
                                                                return '<span class="text-muted">Pendiente</span>';
                                                            }
                                                            $color =
                                                                $nota >= ($oferta->nota_minima ?? 51)
                                                                    ? 'text-success fw-bold'
                                                                    : 'text-danger';
                                                            return '<span class="' .
                                                                $color .
                                                                '">' .
                                                                number_format($nota, 1) .
                                                                '</span>';
                                                        };
                                                    @endphp

                                                    <tr>
                                                        <td class="text-center fw-medium">
                                                            <span class="badge bg-light text-dark">
                                                                {{ $modulo->n_modulo ?? 'N/A' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if ($modulo && $modulo->color)
                                                                    <div class="flex-shrink-0 me-2">
                                                                        <div
                                                                            style="width: 12px; height: 12px; background-color: {{ $modulo->color }}; border-radius: 3px;">
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                <div class="flex-grow-1">
                                                                    <div class="fw-medium">
                                                                        {{ $modulo->nombre ?? 'Módulo no especificado' }}
                                                                    </div>
                                                                    @if ($modulo && $modulo->docente)
                                                                        <small class="text-muted">
                                                                            <i class="ri-user-3-line me-1"></i>
                                                                            {{ $modulo->docente->persona->nombres ?? '' }}
                                                                        </small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-{{ $estadoColorModulo }}">
                                                                <i class="{{ $estadoIconModulo }} me-1"></i>
                                                                {{ $estadoModulo }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($modulo && $modulo->fecha_inicio)
                                                                <span
                                                                    class="text-muted">{{ \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y') }}</span>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($modulo && $modulo->fecha_fin)
                                                                <span
                                                                    class="text-muted">{{ \Carbon\Carbon::parse($modulo->fecha_fin)->format('d/m/Y') }}</span>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="d-flex justify-content-center gap-2">
                                                                <div class="text-center">
                                                                    <small class="text-muted d-block">Regular</small>
                                                                    {!! $formatearNota($notaRegular, $estadoModulo) !!}
                                                                </div>
                                                                <div class="border-start px-2">
                                                                </div>
                                                                <div class="text-center">
                                                                    <small
                                                                        class="text-muted d-block">Nivelación</small>
                                                                    {!! $formatearNota($notaNivelacion, $estadoModulo) !!}
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Leyenda -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="alert alert-light border small">
                                                <div class="d-flex flex-wrap gap-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-warning rounded-circle me-2"
                                                            style="width: 12px; height: 12px;">
                                                        </div>
                                                        <span>Módulo no iniciado</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary rounded-circle me-2"
                                                            style="width: 12px; height: 12px;">
                                                        </div>
                                                        <span>Módulo en desarrollo</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success rounded-circle me-2"
                                                            style="width: 12px; height: 12px;">
                                                        </div>
                                                        <span>Módulo concluido</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <span class="text-success me-2">●</span>
                                                        <span>Nota aprobatoria</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <span class="text-danger me-2">●</span>
                                                        <span>Nota reprobatoria</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-light text-secondary rounded-circle">
                                                <i class="ri-book-2-line fs-2"></i>
                                            </div>
                                        </div>
                                        <h5 class="mb-2">Sin módulos matriculados</h5>
                                        <p class="text-muted mb-0">Este programa no tiene
                                            módulos asignados aún.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Resumen general de todos los programas -->
        <div class="card-footer bg-light">
            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary-subtle text-primary rounded">
                                    <i class="ri-book-open-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">{{ $estudiante->inscripciones->count() }}</h6>
                            <small class="text-muted">Programas Totales</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-success-subtle text-success rounded">
                                    <i class="ri-checkbox-circle-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            @php
                                $inscritosActivos = $estudiante->inscripciones->where('estado', 'Inscrito')->count();
                            @endphp
                            <h6 class="mb-0">{{ $inscritosActivos }}</h6>
                            <small class="text-muted">Programas Activos</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-info-subtle text-info rounded">
                                    <i class="ri-award-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            @php
                                $programasFinalizados = $estudiante->inscripciones
                                    ->where('estado', 'Finalizado')
                                    ->count();
                            @endphp
                            <h6 class="mb-0">{{ $programasFinalizados }}</h6>
                            <small class="text-muted">Programas Finalizados</small>
                        </div>
                    </div>
                </div>
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
            <p class="text-muted mb-0">El estudiante no está inscrito en ningún programa
                académico.</p>
            <a href="{{ route('admin.inscripciones.registrar') }}" class="btn btn-primary mt-3">
                <i class="ri-user-add-line me-1"></i> Realizar Inscripción
            </a>
        </div>
    </div>
@endif
