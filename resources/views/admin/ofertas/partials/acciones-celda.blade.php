@php
    $faseId = $oferta->fase ? $oferta->fase->n_fase : 1;
    $faseColor = $oferta->fase->color ?? '#cccccc';
@endphp

<div class="d-flex flex-column gap-1">
    {{-- Fila 1: Acciones principales según fase - TODOS JUNTOS EN UN GRUPO --}}
    <div class="btn-group" role="group">
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
        {{-- Botones según fase --}}
        @if ($faseId == 1)
            @if (Auth::guard('web')->user()->can('ofertas.academicas.editar'))
                <button type="button" title="Editar Oferta académica" class="btn btn-warning btn-sm editOfertaBtn"
                    data-oferta-id="{{ $oferta->id }}">
                    <i class="ri-edit-line"></i>
                </button>
            @endif
        @elseif ($faseId == 2)
            @if (Auth::guard('web')->user()->can('ofertas.academicas.editar'))
                <button type="button" title="Editar fase 2" class="btn btn-orange btn-sm editFase2Btn"
                    data-oferta-id="{{ $oferta->id }}">
                    <i class="ri-settings-4-line"></i>
                </button>
            @endif
            @if (Auth::guard('web')->user()->can('contabilidad.gestion'))
                <a href="{{ route('admin.ofertas.contabilidad.planes-pago', $oferta->id) }}"
                    class="btn btn-warning btn-sm" title="Gestión Contable">
                    <i class="ri-money-dollar-circle-line"></i>
                </a>
            @endif

            <a href="{{ route('admin.ofertas.vermodulos', $oferta->id) }}" class="btn btn-info btn-sm"
                title="Ver Módulos">
                <i class="ri-book-open-line"></i>
            </a>
        @elseif ($faseId == 3)
            {{-- Fase 3: Inscripciones --}}
            @if (Auth::guard('web')->user()->can('contabilidad.gestion'))
                <a href="{{ route('admin.ofertas.contabilidad.planes-pago', $oferta->id) }}"
                    class="btn btn-warning btn-sm" title="Gestión Contable">
                    <i class="ri-money-dollar-circle-line"></i>
                </a>
            @endif

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
        @elseif ($faseId == 4)
            {{-- Fase 4: En Desarrollo --}}
            @if (Auth::guard('web')->user()->can('contabilidad.gestion'))
                <a href="{{ route('admin.ofertas.contabilidad.planes-pago', $oferta->id) }}"
                    class="btn btn-warning btn-sm" title="Gestión Contable">
                    <i class="ri-money-dollar-circle-line"></i>
                </a>
            @endif

            <a href="{{ route('admin.ofertas.vermodulos', $oferta->id) }}" class="btn btn-info btn-sm"
                title="Ver Módulos">
                <i class="ri-book-open-line"></i>
            </a>

            <a href="{{ route('admin.ofertas.inscritos', $oferta->id) }}" title="Ver Inscritos"
                class="btn btn-secondary btn-sm">
                <i class="ri-eye-line"></i>
            </a>
        @endif
    </div>

    @if (Auth::guard('web')->user()->can('fases.administrar'))
        {{-- Fila 2: Cambio de fase - SEPARADOS DEL GRUPO PRINCIPAL --}}
        <div class="btn-group" role="group">
            @if ($oferta->fase_id > 1)
                <button type="button" class="btn btn-primary btn-sm change-phase" data-oferta-id="{{ $oferta->id }}"
                    data-direction="-1" title="Fase anterior">
                    <i class="ri-arrow-left-line"></i>
                </button>
            @endif

            @php
                $maxFase = \App\Models\Fase::max('n_fase') ?? 3;
            @endphp

            @if ($oferta->fase_id < $maxFase)
                <button type="button" class="btn btn-success btn-sm change-phase" data-oferta-id="{{ $oferta->id }}"
                    data-direction="1" title="Fase siguiente">
                    <i class="ri-arrow-right-line"></i>
                </button>
            @endif
        </div>
    @endif

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

<style>
    /* Estilos para los botones - Consistencia total */
    .btn-group {
        margin: 2px 0;
    }

    .btn-group .btn {
        border-radius: 0;
        border-left: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-group .btn:first-child {
        border-radius: 0.25rem 0 0 0.25rem;
        border-left: none;
    }

    .btn-group .btn:last-child {
        border-radius: 0 0.25rem 0.25rem 0;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
    }

    /* Colores consistentes */
    .btn-teal {
        background-color: #20c997;
        border-color: #20c997;
        color: white;
    }

    .btn-teal:hover {
        background-color: #1baa7e;
        border-color: #1baa7e;
        color: white;
    }

    .btn-purple {
        background-color: #6f42c1;
        border-color: #6f42c1;
        color: white;
    }

    .btn-purple:hover {
        background-color: #5e33a6;
        border-color: #5e33a6;
        color: white;
    }

    .btn-orange {
        background-color: #fd7e14;
        border-color: #fd7e14;
        color: white;
    }

    .btn-orange:hover {
        background-color: #e66a03;
        border-color: #e66a03;
        color: white;
    }

    .btn-indigo {
        background-color: #6610f2;
        border-color: #6610f2;
        color: white;
    }

    .btn-indigo:hover {
        background-color: #560bd0;
        border-color: #560bd0;
        color: white;
    }

    /* Botones de cambio de fase (separados) */
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0b5ed7;
        color: white;
    }

    .btn-success {
        background-color: #198754;
        border-color: #198754;
        color: white;
    }

    .btn-success:hover {
        background-color: #157347;
        border-color: #157347;
        color: white;
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #e0a800;
        color: #000;
    }

    .btn-info {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
        color: #000;
    }

    .btn-info:hover {
        background-color: #0bb5d4;
        border-color: #0bb5d4;
        color: #000;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5c636a;
        border-color: #5c636a;
        color: white;
    }

    /* Iconos dentro de botones */
    .btn-sm i {
        font-size: 14px;
        line-height: 1;
    }

    /* Hover effect para todos los botones */
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        transition: all 0.2s ease;
    }

    /* Estilo para enlaces dentro de botones */
    a.btn {
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .btn-sm {
            min-width: 32px;
            height: 30px;
            padding: 0.2rem 0.4rem;
        }

        .btn-sm i {
            font-size: 12px;
        }

        /* Para grupos de botones muy largos en móvil */
        .btn-group {
            display: flex;
            flex-wrap: wrap;
        }

        .btn-group .btn {
            flex: 1;
            min-width: 40px;
            margin: 1px;
        }

        .btn-group .btn:first-child,
        .btn-group .btn:last-child {
            border-radius: 0.25rem;
        }
    }

    /* Espaciado entre grupos de botones */
    .d-flex.flex-column.gap-1 {
        gap: 6px !important;
    }

    /* Separador visual entre grupos */
    .btn-group:not(:first-child) {
        margin-top: 4px;
    }

    /* Para mantener el hover consistente entre botones pegados */
    .btn-group .btn:hover {
        z-index: 2;
    }
</style>
