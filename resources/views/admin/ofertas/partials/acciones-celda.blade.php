@php
    $faseId = $oferta->fase ? $oferta->fase->n_fase : 1;
    $faseColor = $oferta->fase->color ?? '#cccccc';
@endphp

<div class="d-flex flex-column gap-1">
    {{-- Fila 1: Acciones principales según fase --}}
    <div class="d-flex flex-wrap gap-1 justify-content-center">
        {{-- Botón Ver Planes de Pago --}}
        <button type="button" class="btn btn-teal btn-sm verPlanesPagoBtn" data-oferta-id="{{ $oferta->id }}"
            data-oferta-codigo="{{ $oferta->codigo }}" title="Ver Planes de Pago">
            <i class="ri-bank-card-line"></i>
        </button>

        {{-- Botón Dashboard --}}
        <a href="{{ route('admin.ofertas.dashboard', $oferta->id) }}" class="btn btn-purple btn-sm"
            title="Dashboard de Oferta">
            <i class="ri-dashboard-line"></i>
        </a>

        {{-- Botones según fase --}}
        @if ($faseId == 1)
            <button type="button" title="Editar Oferta académica" class="btn btn-warning btn-sm editOfertaBtn"
                data-oferta-id="{{ $oferta->id }}">
                <i class="ri-edit-line"></i>
            </button>
        @elseif ($faseId == 2)
            <button type="button" title="Editar fase 2" class="btn btn-orange btn-sm editFase2Btn"
                data-oferta-id="{{ $oferta->id }}">
                <i class="ri-settings-4-line"></i>
            </button>

            <button type="button" title="Agregar plan de pago" class="btn btn-success btn-sm addPlanPagoBtn"
                data-oferta-id="{{ $oferta->id }}">
                <i class="ri-add-circle-line"></i>
            </button>

            <button class="btn btn-sm btn-warning editarPlanesPagoBtn" data-oferta-id="{{ $oferta->id }}"
                data-oferta-codigo="{{ $oferta->codigo }}" title="Editar planes de pago">
                <i class="ri-edit-line"></i>
            </button>

            <a href="{{ route('admin.ofertas.vermodulos', $oferta->id) }}" class="btn btn-info btn-sm"
                title="Ver Módulos">
                <i class="ri-book-open-line"></i>
            </a>
        @elseif ($faseId == 3)
            <a href="{{ route('admin.ofertas.vermodulos', $oferta->id) }}" class="btn btn-info btn-sm"
                title="Ver Módulos">
                <i class="ri-book-open-line"></i>
            </a>

            <a href="{{ route('admin.ofertas.inscritos', $oferta->id) }}" title="Ver Inscritos"
                class="btn btn-secondary btn-sm">
                <i class="ri-eye-line"></i>
            </a>

            <button type="button" title="Inscribir estudiante" class="btn btn-success btn-sm inscribirEstudianteBtn"
                data-oferta-id="{{ $oferta->id }}">
                <i class="ri-user-add-line"></i>
            </button>

            <button type="button" title="Detalle contable" class="btn btn-indigo btn-sm" data-bs-obj=''>
                <i class="ri-money-dollar-circle-line"></i>
            </button>
        @endif
    </div>

    {{-- Fila 2: Cambio de fase --}}
    <div class="d-flex justify-content-center gap-1">
        @if ($oferta->fase_id > 1)
            <button type="button" class="btn btn-outline-primary btn-sm change-phase"
                data-oferta-id="{{ $oferta->id }}" data-direction="-1" title="Fase anterior">
                <i class="ri-arrow-left-line"></i>
            </button>
        @endif

        @php
            $maxFase = \App\Models\Fase::max('n_fase') ?? 3;
        @endphp

        @if ($oferta->fase_id < $maxFase)
            <button type="button" class="btn btn-outline-success btn-sm change-phase"
                data-oferta-id="{{ $oferta->id }}" data-direction="1" title="Fase siguiente">
                <i class="ri-arrow-right-line"></i>
            </button>
        @endif
    </div>

    {{-- Fila 3: Info rápida --}}
    <div class="text-center">
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
