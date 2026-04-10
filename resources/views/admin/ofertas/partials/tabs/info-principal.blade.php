<!-- Información Principal de la Oferta - Diseño Compacto -->
<div class="row mb-2 g-2">
    <!-- Card Programa - Izquierda -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
            <div class="card-body p-2">
                <div class="row g-2 align-items-center">
                    <!-- Imagen -->
                    <div class="col-md-2 col-3">
                        @if ($oferta->portada)
                            <img src="{{ asset($oferta->portada) }}" alt="Portada" 
                                class="img-fluid rounded" style="object-fit: cover; height: 60px; width: 100%;">
                        @else
                            <div class="rounded d-flex align-items-center justify-content-center"
                                style="height: 60px; background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);">
                                <i class="ri-book-open-line text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Info Programa -->
                    <div class="col-md-10 col-9">
                        <h5 class="mb-1 fw-semibold" style="font-size: 0.95rem;">{{ $oferta->programa->nombre ?? 'Programa' }}</h5>
                        <div class="d-flex flex-wrap align-items-center gap-2 fs-10 text-muted">
                            <span><i class="ri-code-s-slash-line me-1" style="color: var(--dash-primary);"></i><strong>{{ $oferta->codigo }}</strong></span>
                            <span class="text-secondary">|</span>
                            <span><i class="ri-building-2-line me-1" style="color: var(--dash-primary);"></i>{{ $oferta->sucursal->nombre ?? 'Sin sucursal' }}</span>
                            <span class="text-secondary">|</span>
                            <span><i class="ri-time-line me-1" style="color: var(--dash-primary);"></i>{{ $oferta->fase->nombre ?? 'Sin fase' }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Métricas -->
                <div class="row g-2 mt-1">
                    <div class="col-4 col-md-3">
                        <div class="p-2 rounded" style="background: #fefce8;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center justify-content-center rounded bg-warning" style="width: 22px; height: 22px;">
                                    <i class="ri-calendar-event-line text-white fs-9"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fs-8 text-muted">Inicio</p>
                                    <p class="mb-0 fw-bold fs-9" style="color: #92400e;">{{ $oferta->fecha_inicio_programa?->format('d M') ?? 'Por definir' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 col-md-3">
                        <div class="p-2 rounded" style="background: #ecfeff;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center justify-content-center rounded bg-info" style="width: 22px; height: 22px;">
                                    <i class="ri-group-line text-white fs-9"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fs-8 text-muted">Modalidad</p>
                                    <p class="mb-0 fw-bold fs-9" style="color: #0e7490;">{{ $oferta->modalidad->nombre ?? 'N/D' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 col-md-3">
                        <div class="p-2 rounded" style="background: #f0fdf4;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center justify-content-center rounded bg-success" style="width: 22px; height: 22px;">
                                    <i class="ri-star-line text-white fs-9"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fs-8 text-muted">Nota mín.</p>
                                    <p class="mb-0 fw-bold fs-9" style="color: #166534;">{{ $oferta->nota_minima ?? 61 }} pts</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Resumen - Derecha -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 10px; background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);">
            <div class="card-body p-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 fw-semibold text-white fs-11">Resumen Académico</h6>
                    <i class="ri-graduation-cap-line text-white fs-14"></i>
                </div>

                <div class="row g-1">
                    <div class="col-6">
                        <div class="rounded p-2 text-center" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(4px);">
                            <div class="fs-4 fw-bold text-white">{{ $totalInscritos }}</div>
                            <div class="fs-8 text-white-50">Inscritos</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="rounded p-2 text-center" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(4px);">
                            <div class="fs-4 fw-bold text-white">{{ $totalPreInscritos }}</div>
                            <div class="fs-8 text-white-50">Pre-Insc.</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="rounded p-2 text-center" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(4px);">
                            <div class="fs-4 fw-bold text-white">{{ $oferta->modulos->count() }}</div>
                            <div class="fs-8 text-white-50">Módulos</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="rounded p-2 text-center" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(4px);">
                            <div class="fs-4 fw-bold text-white">{{ $hombres + $mujeres }}</div>
                            <div class="fs-8 text-white-50">Estudiantes</div>
                        </div>
                    </div>
                </div>

                <div class="mt-2 pt-2" style="border-top: 1px solid rgba(255,255,255,0.2);">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fs-9 text-white-50">Progreso académicas</span>
                        <span class="badge bg-white bg-opacity-25 text-white fs-10 fw-semibold">
                            @php
                                $progress = $oferta->modulos->count() > 0 
                                    ? round(($oferta->modulos->where('estado', 'Finalizado')->count() / $oferta->modulos->count()) * 100) 
                                    : 0;
                            @endphp
                            {{ $progress }}%
                        </span>
                    </div>
                    <div class="progress rounded-pill" style="height: 6px; background: rgba(255,255,255,0.25);">
                        <div class="progress-bar bg-white rounded-pill" role="progressbar" style="width: {{ $progress }}%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>