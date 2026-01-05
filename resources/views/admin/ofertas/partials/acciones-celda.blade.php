@php
    $faseId = $oferta->fase?->n_fase;
    $faseColor = $oferta->fase?->color ?? '#cccccc';
@endphp

<div class="d-flex flex-column gap-2">
    {{-- Fila 1: Acciones principales según fase --}}
    <div class="d-flex flex-wrap gap-1 justify-content-center">
        {{-- Botón Ver Planes de Pago --}}
        <button type="button" class="btn btn-teal btn-sm verPlanesPagoBtn px-2 py-1" data-oferta-id="{{ $oferta->id }}"
            data-oferta-codigo="{{ $oferta->codigo }}" title="Ver Planes de Pago"
            style="min-width: 36px; font-size: 0.8rem;">
            <i class="ri-bank-card-line"></i>
        </button>

        {{-- Botón Dashboard --}}
        <a href="{{ route('admin.ofertas.dashboard', $oferta->id) }}" class="btn btn-purple btn-sm px-2 py-1"
            title="Dashboard de Oferta" style="min-width: 36px; font-size: 0.8rem;">
            <i class="ri-dashboard-line"></i>
        </a>

        {{-- Botones según fase --}}
        @if ($faseId == 1)
            @if (Auth::guard('web')->user()->can('ofertas.academicas.editar'))
                <button type="button" title="Editar Oferta académica"
                    class="btn btn-warning btn-sm px-2 py-1 editOfertaBtn" data-oferta-id="{{ $oferta->id }}"
                    data-bs-toggle="modal" data-bs-target="#modalEditarOferta"
                    style="min-width: 36px; font-size: 0.8rem;">
                    <i class="ri-edit-line"></i>
                </button>
            @endif
        @elseif ($faseId == 2)
            @if (Auth::guard('web')->user()->can('ofertas.academicas.editar'))
                <button type="button" title="Editar fase 2" class="btn btn-orange btn-sm px-2 py-1 editFase2Btn"
                    data-oferta-id="{{ $oferta->id }}" data-bs-toggle="modal" data-bs-target="#modalEditarFase2"
                    style="min-width: 36px; font-size: 0.8rem;">
                    <i class="ri-settings-4-line"></i>
                </button>
            @endif

            @if (Auth::guard('web')->user()->can('planes.pagos.asignar'))
                <button type="button" title="Agregar plan de pago"
                    class="btn btn-success btn-sm px-2 py-1 addPlanPagoBtn" data-oferta-id="{{ $oferta->id }}"
                    data-bs-toggle="modal" data-bs-target="#modalAgregarPlanPago"
                    style="min-width: 36px; font-size: 0.8rem;">
                    <i class="ri-add-circle-line"></i>
                </button>
                <!-- NUEVO: Botón para Editar Planes de Pago -->
                <!-- En la sección de acciones de la tabla, agrega este botón -->
                <button class="btn btn-sm btn-warning editarPlanesPagoBtn" data-oferta-id="{{ $oferta->id }}"
                    data-oferta-codigo="{{ $oferta->codigo }}" title="Editar planes de pago">
                    <i class="ri-edit-line"></i>
                </button>
            @endif

            @if (Auth::guard('web')->user()->can('ofertas.academicas.modulos.ver'))
                <a href="{{ route('admin.ofertas.vermodulos', $oferta->id) }}" class="btn btn-info btn-sm px-2 py-1"
                    title="Ver Módulos" style="min-width: 36px; font-size: 0.8rem;">
                    <i class="ri-book-open-line"></i>
                </a>
            @endif
        @elseif ($faseId == 3)
            @if (Auth::guard('web')->user()->can('ofertas.academicas.modulos.ver'))
                <a href="{{ route('admin.ofertas.vermodulos', $oferta->id) }}" class="btn btn-info btn-sm px-2 py-1"
                    title="Ver Módulos" style="min-width: 36px; font-size: 0.8rem;">
                    <i class="ri-book-open-line"></i>
                </a>
            @endif

            @if (Auth::guard('web')->user()->can('ofertas.academicas.inscritos'))
                <a href="{{ route('admin.ofertas.inscritos', $oferta->id) }}" title="Ver Inscritos"
                    class="btn btn-secondary btn-sm px-2 py-1" style="min-width: 36px; font-size: 0.8rem;">
                    <i class="ri-eye-line"></i>
                </a>
            @endif

            @if (Auth::guard('web')->user()->can('ofertas.academicas.inscripciones'))
                <button type="button" title="Inscribir estudiante"
                    class="btn btn-success btn-sm px-2 py-1 inscribirEstudianteBtn"
                    data-oferta-id="{{ $oferta->id }}" data-bs-toggle="modal"
                    data-bs-target="#modalInscribirEstudiante" style="min-width: 36px; font-size: 0.8rem;">
                    <i class="ri-user-add-line"></i>
                </button>
            @endif

            @if (Auth::guard('web')->user()->can('ofertas.academicas.deuda'))
                <button type="button" title="Detalle contable" class="btn btn-indigo btn-sm px-2 py-1" data-bs-obj=''
                    data-bs-toggle="modal" data-bs-target=".modificar" style="min-width: 36px; font-size: 0.8rem;">
                    <i class="ri-money-dollar-circle-line"></i>
                </button>
            @endif
        @endif
    </div>

    {{-- Fila 2: Cambio de fase y acciones adicionales --}}
    @if (Auth::guard('web')->user()->can('ofertas.academicas.cambiar.fase'))
        <div class="d-flex justify-content-center gap-1">
            @if ($oferta->fase_id > 1)
                <button type="button" class="btn btn-outline-primary btn-sm change-phase px-2 py-0"
                    data-oferta-id="{{ $oferta->id }}" data-direction="-1" title="Fase anterior"
                    style="font-size: 0.75rem; height: 24px;">
                    <i class="ri-arrow-left-line"></i>
                </button>
            @endif

            @if ($oferta->fase_id < \App\Models\Fase::max('n_fase'))
                <button type="button" class="btn btn-outline-success btn-sm change-phase px-2 py-0"
                    data-oferta-id="{{ $oferta->id }}" data-direction="1" title="Fase siguiente"
                    style="font-size: 0.75rem; height: 24px;">
                    <i class="ri-arrow-right-line"></i>
                </button>
            @endif
        </div>
    @endif

    {{-- Fila 3: Info rápida --}}
    <div class="text-center mt-1">
        <small class="text-muted d-block" style="font-size: 0.7rem;">
            <i class="ri-calendar-line me-1"></i>
            {{ $oferta->gestion ?? date('Y') }}
            @if ($oferta->version)
                | v{{ $oferta->version }}
            @endif
            @if ($oferta->grupo)
                | G{{ $oferta->grupo }}
            @endif
        </small>
    </div>
</div>
