<!-- Pestaña 4: Académico - Diseño Premium -->
<div class="tab-pane fade" id="tab-academico" role="tabpanel">
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-header border-0 bg-transparent py-3 px-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-semibold">
                    <i class="ri-book-open-line align-middle me-2" style="color: var(--dash-primary);"></i>
                    Rendimiento Académico
                </h5>
                <span class="badge fs-11" style="background: #e0f2fe; color: #0284c7;">
                    <i class="ri-information-line align-middle me-1"></i>
                    Nota mínima: {{ $oferta->nota_minima ?? 61 }}
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0" style="font-size: 0.75rem;">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center align-middle py-2" width="3%">#</th>
                            <th rowspan="2" class="align-middle py-2" width="12%">Estudiante</th>
                            <th rowspan="2" class="text-center align-middle py-2" width="5%">Carnet</th>
                            <th colspan="{{ $oferta->modulos->count() }}" class="text-center py-2">Módulos</th>
                            <th rowspan="2" class="text-center align-middle py-2" width="5%">Prom.</th>
                        </tr>
                        <tr>
                            @foreach ($oferta->modulos as $modulo)
                                <th class="text-center modulo-header py-1" data-modulo-id="{{ $modulo->id }}"
                                    data-oferta-id="{{ $oferta->id }}" title="{{ $modulo->nombre }}"
                                    style="cursor: pointer; min-width: 50px;">
                                    <span class="badge fs-10 text-white"
                                        style="background: #0284c7;">M{{ $modulo->n_modulo }}</span>
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
                                    $nota = $fila['modulos'][$modulo->nombre]['nota_regular'] ?? null;
                                    if ($nota !== null) {
                                        $promedioEstudiante += $nota;
                                        $totalModulos++;
                                    }
                                }
                                $promedioEstudiante = $totalModulos > 0 ? $promedioEstudiante / $totalModulos : 0;

                                $colorPromedio = match (true) {
                                    $promedioEstudiante >= 90 => '#16a34a',
                                    $promedioEstudiante >= 80 => '#0891b2',
                                    $promedioEstudiante >= 70 => '#d97706',
                                    $promedioEstudiante >= 60 => '#2563eb',
                                    $promedioEstudiante > 0 => '#dc2626',
                                    default => '#94a3b8',
                                };
                            @endphp
                            <tr>
                                <td class="text-center py-2">{{ $index + 1 }}</td>
                                <td class="py-2">
                                    <a href="{{ route('admin.estudiantes.detalle', $fila['estudiante_id']) }}"
                                        class="text-decoration-none">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-medium">{{ $fila['estudiante'] }}</span>
                                        </div>
                                    </a>
                                </td>
                                <td class="text-center py-2">
                                    <span class="badge fs-9 text-white"
                                        style="background: #16a34a;">{{ $fila['carnet'] }}</span>
                                </td>
                                @foreach ($oferta->modulos as $modulo)
                                    @php
                                        $nota = $fila['modulos'][$modulo->nombre]['nota_regular'] ?? null;
                                        $notaNivelacion = $fila['modulos'][$modulo->nombre]['nota_nivelacion'] ?? null;

                                        $color = match (true) {
                                            $nota >= 90 => '#16a34a',
                                            $nota >= 80 => '#0891b2',
                                            $nota >= 70 => '#d97706',
                                            $nota >= 60 => '#2563eb',
                                            $nota !== null => '#dc2626',
                                            default => '#94a3b8',
                                        };
                                    @endphp
                                    <td class="text-center modulo-cell py-2" data-modulo-id="{{ $modulo->id }}"
                                        data-oferta-id="{{ $oferta->id }}" style="cursor: pointer;">
                                        @if ($nota !== null)
                                            <span class="badge fs-10 px-2 py-1"
                                                style="background: {{ $color }}20; color: {{ $color }};">
                                                {{ $nota }}
                                            </span>
                                            @if ($notaNivelacion)
                                                <div class="fs-8 text-muted">N: {{ $notaNivelacion }}</div>
                                            @endif
                                        @else
                                            <span class="badge fs-10 bg-light text-muted">--</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="text-center py-2">
                                    @if ($promedioEstudiante > 0)
                                        <span class="badge fs-10 px-2 py-1"
                                            style="background: {{ $colorPromedio }}20; color: {{ $colorPromedio }};">
                                            {{ number_format($promedioEstudiante, 1) }}
                                        </span>
                                    @else
                                        <span class="badge fs-10 bg-light text-muted">--</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end py-2 fw-semibold">Promedio por módulo:</td>
                            @foreach ($oferta->modulos as $modulo)
                                @php
                                    $notasModulo = collect($tablaAcademica)
                                        ->map(fn($fila) => $fila['modulos'][$modulo->nombre]['nota_regular'] ?? null)
                                        ->filter(fn($nota) => $nota !== null);
                                    $promedio = $notasModulo->count() > 0 ? $notasModulo->avg() : 0;
                                    $promedioColor = match (true) {
                                        $promedio >= 90 => '#16a34a',
                                        $promedio >= 80 => '#0891b2',
                                        $promedio >= 70 => '#d97706',
                                        $promedio >= 60 => '#2563eb',
                                        $promedio > 0 => '#dc2626',
                                        default => '#94a3b8',
                                    };
                                @endphp
                                <td class="text-center py-2">
                                    @if ($notasModulo->count() > 0)
                                        <span class="badge fs-9"
                                            style="background: {{ $promedioColor }}20; color: {{ $promedioColor }};">
                                            {{ number_format($promedio, 1) }}
                                        </span>
                                    @else
                                        <span class="badge fs-9 bg-light text-muted">--</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="text-center py-2">
                                @php
                                    $totalPromedios = 0;
                                    $totalModulosConNotas = 0;
                                    foreach ($oferta->modulos as $modulo) {
                                        $notasModulo = collect($tablaAcademica)
                                            ->map(
                                                fn($fila) => $fila['modulos'][$modulo->nombre]['nota_regular'] ?? null,
                                            )
                                            ->filter(fn($nota) => $nota !== null);
                                        if ($notasModulo->count() > 0) {
                                            $totalPromedios += $notasModulo->avg();
                                            $totalModulosConNotas++;
                                        }
                                    }
                                    $promedioGeneral =
                                        $totalModulosConNotas > 0 ? $totalPromedios / $totalModulosConNotas : 0;
                                    $color = match (true) {
                                        $promedioGeneral >= 90 => '#16a34a',
                                        $promedioGeneral >= 80 => '#0891b2',
                                        $promedioGeneral >= 70 => '#d97706',
                                        $promedioGeneral >= 60 => '#2563eb',
                                        $promedioGeneral > 0 => '#dc2626',
                                        default => '#94a3b8',
                                    };
                                @endphp
                                <span class="badge fs-10 px-2 py-1"
                                    style="background: {{ $color }}20; color: {{ $color }};">
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

<style>
    .fs-8 {
        font-size: 0.65rem;
    }

    .badge.fs-9 {
        font-size: 0.7rem;
    }

    .badge.fs-10 {
        font-size: 0.75rem;
    }

    .table th,
    .table td {
        vertical-align: middle;
        padding: 0.5rem 0.25rem !important;
    }

    .table thead th {
        font-size: 0.7rem;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
    }
</style>
