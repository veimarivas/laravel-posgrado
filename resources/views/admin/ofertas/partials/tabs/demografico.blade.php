<!-- Pestaña 5: Demográfico - Diseño Premium -->
<div class="tab-pane fade" id="tab-demografico" role="tabpanel">
    <!-- Stats Cards -->
    <div class="row g-3 mb-3">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="avatar-xs d-flex align-items-center justify-content-center rounded-2"
                            style="background: #16a34a20;">
                            <i class="ri-group-line" style="color: #16a34a;"></i>
                        </div>
                        <span class="text-muted fs-11">Total Estudiantes</span>
                    </div>
                    <h3 class="mb-0 fw-bold" style="color: #16a34a;">{{ $estadisticasDemograficas['totalEstudiantes'] }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="avatar-xs d-flex align-items-center justify-content-center rounded-2"
                            style="background: #2563eb20;">
                            <i class="ri-men-line" style="color: #2563eb;"></i>
                        </div>
                        <span class="text-muted fs-11">Hombres</span>
                    </div>
                    <h3 class="mb-0 fw-bold" style="color: #2563eb;">{{ $estadisticasDemograficas['hombres'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="avatar-xs d-flex align-items-center justify-content-center rounded-2"
                            style="background: #e83e8c20;">
                            <i class="ri-women-line" style="color: #e83e8c;"></i>
                        </div>
                        <span class="text-muted fs-11">Mujeres</span>
                    </div>
                    <h3 class="mb-0 fw-bold" style="color: #e83e8c;">{{ $estadisticasDemograficas['mujeres'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="avatar-xs d-flex align-items-center justify-content-center rounded-2"
                            style="background: #0891b220;">
                            <i class="ri-map-pin-line" style="color: #0891b2;"></i>
                        </div>
                        <span class="text-muted fs-11">Promedio Edad</span>
                    </div>
                    <h3 class="mb-0 fw-bold" style="color: #0891b2;">
                        {{ number_format($estadisticasDemograficas['promedioEdad'], 1) }}</h3>
                    <span class="text-muted fs-9">años</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row g-3 mb-3">
        <!-- Distribución por Género -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header border-0 bg-transparent py-2 px-3">
                    <h6 class="card-title mb-0 fw-semibold">
                        <i class="ri-user-line align-middle me-2" style="color: var(--dash-primary);"></i>
                        Distribución por Género
                    </h6>
                </div>
                <div class="card-body py-2">
                    <div class="row g-2">
                        <div class="col-12">
                            <canvas id="sexoChart" height="120"></canvas>
                        </div>
                        <div class="col-6 text-center">
                            <div class="p-2 rounded-2" style="background: #2563eb20;">
                                <span class="fs-4 fw-bold"
                                    style="color: #2563eb;">{{ $estadisticasDemograficas['hombres'] }}</span>
                                <div class="text-muted fs-10">Hombres</div>
                            </div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="p-2 rounded-2" style="background: #e83e8c20;">
                                <span class="fs-4 fw-bold"
                                    style="color: #e83e8c;">{{ $estadisticasDemograficas['mujeres'] }}</span>
                                <div class="text-muted fs-10">Mujeres</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Departamentos -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header border-0 bg-transparent py-2 px-3">
                    <h6 class="card-title mb-0 fw-semibold">
                        <i class="ri-map-pin-2-line align-middle me-2" style="color: var(--dash-primary);"></i>
                        Top Departamentos
                    </h6>
                </div>
                <div class="card-body py-2">
                    <canvas id="departamentosChart" height="100"></canvas>
                    <div class="mt-2">
                        @foreach ($estadisticasDemograficas['topDepartamentos'] as $departamento => $cantidad)
                            @php $porcentaje = $estadisticasDemograficas['totalEstudiantes'] > 0 ? ($cantidad / $estadisticasDemograficas['totalEstudiantes']) * 100 : 0; @endphp
                            <div class="mb-2">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fs-11">{{ $departamento }}</span>
                                    <span class="fs-11 fw-bold" style="color: #16a34a;">{{ $cantidad }}
                                        ({{ number_format($porcentaje, 0) }}%)
                                    </span>
                                </div>
                                <div class="progress" style="height: 4px; background: #e2e8f0;">
                                    <div class="progress-bar" style="width: {{ $porcentaje }}%; background: #16a34a;">
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
