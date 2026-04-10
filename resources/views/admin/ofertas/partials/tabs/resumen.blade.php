<!-- Pestaña 1: Resumen - Diseño Premium -->
<div class="tab-pane fade show active" id="tab-resumen" role="tabpanel">
    <!-- Stats Cards -->
    <div class="row g-3 mb-3">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <p class="text-muted mb-1 fs-12 fw-medium">Inscritos Activos</p>
                    <h3 class="mb-0 fw-bold" style="color: #16a34a;">{{ $totalInscritos }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <p class="text-muted mb-1 fs-12 fw-medium">Pre-Inscritos</p>
                    <h3 class="mb-0 fw-bold" style="color: #d97706;">{{ $totalPreInscritos }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <p class="text-muted mb-1 fs-12 fw-medium">Total Recaudado</p>
                    <h3 class="mb-0 fw-bold" style="color: #0891b2;">{{ number_format($totalRecaudado, 0) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <p class="text-muted mb-1 fs-12 fw-medium">Deuda Pendiente</p>
                    <h3 class="mb-0 fw-bold" style="color: #dc2626;">{{ number_format($totalDeuda, 0) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos - Altura fija -->
    <div class="row g-3 mb-3">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; max-height: 240px;">
                <div class="card-header border-0 bg-transparent py-2 px-3">
                    <h6 class="card-title mb-0 fw-semibold">
                        <i class="ri-bar-chart-line align-middle me-2" style="color: var(--dash-primary);"></i>
                        Inscripciones Mensuales
                    </h6>
                </div>
                <div class="card-body py-2" style="max-height: 180px; overflow: hidden;">
                    <canvas id="inscripcionesChart" class="w-100" style="max-height: 160px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; max-height: 240px;">
                <div class="card-header border-0 bg-transparent py-2 px-3">
                    <h6 class="card-title mb-0 fw-semibold">
                        <i class="ri-pie-chart-line align-middle me-2" style="color: var(--dash-primary);"></i>
                        Distribución por Estado
                    </h6>
                </div>
                <div class="card-body py-2 d-flex align-items-center justify-content-center" style="max-height: 180px; overflow: hidden;">
                    <canvas id="estadoChart" class="w-100" style="max-height: 140px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pre-Inscritos y sus Asesores -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-header border-0 bg-transparent py-3 px-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 fw-semibold">
                    <i class="ri-user-add-line align-middle me-2" style="color: var(--dash-primary);"></i>
                    Pre-Inscritos y sus Asesores
                </h6>
                @if(count($preInscritosConAsesor) > 0)
                <span class="badge fs-11" style="background: var(--dash-primary-light); color: var(--dash-primary);">
                    {{ count($preInscritosConAsesor) }} {{ count($preInscritosConAsesor) == 1 ? 'registro' : 'registros' }}
                </span>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            @if(count($preInscritosConAsesor) > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 0.8rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="px-2 py-2 fw-semibold text-center" width="5%">#</th>
                                <th class="px-2 py-2 fw-semibold" width="20%">Estudiante</th>
                                <th class="px-2 py-2 fw-semibold" width="10%">Carnet</th>
                                <th class="px-2 py-2 fw-semibold" width="15%">Asesor</th>
                                <th class="px-2 py-2 fw-semibold" width="15%">Plan de Pago</th>
                                <th class="px-2 py-2 fw-semibold text-end" width="10%">Adelanto (Bs)</th>
                                <th class="px-2 py-2 fw-semibold" width="10%">Fecha</th>
                                <th class="px-2 py-2 fw-semibold text-center" width="15%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($preInscritosConAsesor as $index => $pi)
                            <tr>
                                <td class="px-2 py-2 text-center">{{ $index + 1 }}</td>
                                <td class="px-2 py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2 d-flex align-items-center justify-content-center rounded-circle fw-bold"
                                            style="width: 28px; height: 28px; background: #fef3c7; color: #92400e; font-size: 0.7rem;">
                                            {{ substr(trim($pi['estudiante']), 0, 1) }}
                                        </div>
                                        <span class="fw-medium">{{ $pi['estudiante'] }}</span>
                                    </div>
                                </td>
                                <td class="px-2 py-2">
                                    <span class="badge fs-10 bg-light border">{{ $pi['carnet'] }}</span>
                                </td>
                                <td class="px-2 py-2">
                                    @if($pi['asesor_persona_id'])
                                    <a href="{{ route('admin.vendedor.inscripciones', $pi['asesor_persona_id']) }}" class="text-decoration-none" style="color: #0284c7;">
                                        {{ $pi['asesor'] }}
                                    </a>
                                    @else
                                    <span class="text-muted">Sin asesor</span>
                                    @endif
                                </td>
                                <td class="px-2 py-2">
                                    <span class="badge fs-10" style="background: var(--dash-primary-light); color: var(--dash-primary);">
                                        {{ $pi['plan_pago'] }}
                                    </span>
                                </td>
                                <td class="px-2 py-2 text-end">
                                    @if($pi['adelanto_bs'] > 0)
                                    <span class="fw-bold text-success">{{ number_format($pi['adelanto_bs'], 2) }}</span>
                                    @else
                                    <span class="text-muted">0.00</span>
                                    @endif
                                </td>
                                <td class="px-2 py-2">
                                    <span class="text-muted fs-11">{{ \Carbon\Carbon::parse($pi['fecha_registro'])->format('d/m/Y') }}</span>
                                </td>
                                <td class="px-2 py-2 text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('admin.estudiantes.detalle', $pi['estudiante_id']) }}"
                                            class="btn btn-sm btn-outline-primary py-1 px-2" title="Ver detalle" style="border-radius: 6px;">
                                            <i class="ri-eye-line fs-10"></i>
                                        </a>
                                        @if($pi['estado'] == 'Pre-Inscrito')
                                        <button class="btn btn-sm btn-outline-warning py-1 px-2 cambiar-plan-pago-btn"
                                            data-inscripcion-id="{{ $pi['inscripcion_id'] ?? '' }}"
                                            data-estudiante-id="{{ $pi['estudiante_id'] }}"
                                            data-oferta-id="{{ $oferta->id }}"
                                            data-plan-actual-id="{{ $pi['plan_pago_id'] }}"
                                            data-plan-actual-nombre="{{ $pi['plan_pago'] }}"
                                            title="Cambiar Plan" style="border-radius: 6px;">
                                            <i class="ri-exchange-dollar-line fs-10"></i>
                                        </button>
                                        @endif
                                        <button class="btn btn-sm btn-outline-success py-1 px-2 convertir-inscrito-btn"
                                            data-inscripcion-id="{{ $pi['inscripcion_id'] }}"
                                            data-oferta-id="{{ $oferta->id }}"
                                            data-plan-pago-id="{{ $pi['plan_pago_id'] }}"
                                            title="Convertir a Inscrito" style="border-radius: 6px;">
                                            <i class="ri-check-double-line fs-10"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="ri-user-line text-muted" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    <p class="text-muted mt-2 mb-0">No hay pre-inscritos en esta oferta</p>
                </div>
            @endif
        </div>
    </div>
</div>