@php
    $faseId    = $oferta->fase ? $oferta->fase->n_fase : 1;
    $faseColor = $oferta->fase->color ?? '#cccccc';
@endphp

<div class="d-flex flex-column gap-1 align-items-start">

    {{-- Acciones principales según fase --}}
    <div class="btn-group" role="group">

        <button type="button" class="btn btn-teal btn-sm verPlanesPagoBtn"
                data-oferta-id="{{ $oferta->id }}"
                data-oferta-codigo="{{ $oferta->codigo }}"
                title="Ver Planes de Pago">
            <i class="ri-bank-card-line"></i>
        </button>

        <a href="{{ route('admin.ofertas.dashboard', $oferta->id) }}"
           class="btn btn-purple btn-sm" title="Dashboard de Oferta">
            <i class="ri-dashboard-line"></i>
        </a>

        @if ($faseId == 1)
            @if (Auth::guard('web')->user()->can('ofertas.academicas.editar'))
                <button type="button" title="Editar Oferta académica"
                        class="btn btn-warning btn-sm editOfertaBtn"
                        data-oferta-id="{{ $oferta->id }}">
                    <i class="ri-edit-line"></i>
                </button>
            @endif

        @elseif ($faseId == 2)
            @if (Auth::guard('web')->user()->can('ofertas.academicas.editar'))
                <button type="button" title="Editar fase 2"
                        class="btn btn-orange btn-sm editFase2Btn"
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
            <a href="{{ route('admin.ofertas.vermodulos', $oferta->id) }}"
               class="btn btn-info btn-sm" title="Ver Módulos">
                <i class="ri-book-open-line"></i>
            </a>

        @elseif ($faseId == 3)
            @if (Auth::guard('web')->user()->can('contabilidad.gestion'))
                <a href="{{ route('admin.ofertas.contabilidad.planes-pago', $oferta->id) }}"
                   class="btn btn-warning btn-sm" title="Gestión Contable">
                    <i class="ri-money-dollar-circle-line"></i>
                </a>
            @endif
            <a href="{{ route('admin.ofertas.vermodulos', $oferta->id) }}"
               class="btn btn-info btn-sm" title="Ver Módulos">
                <i class="ri-book-open-line"></i>
            </a>
            <a href="{{ route('admin.ofertas.inscritos', $oferta->id) }}"
               class="btn btn-secondary btn-sm" title="Ver Inscritos">
                <i class="ri-eye-line"></i>
            </a>
            <button type="button" title="Inscribir estudiante"
                    class="btn btn-success btn-sm inscribirEstudianteBtn"
                    data-oferta-id="{{ $oferta->id }}">
                <i class="ri-user-add-line"></i>
            </button>

        @elseif ($faseId == 4)
            @if (Auth::guard('web')->user()->can('contabilidad.gestion'))
                <a href="{{ route('admin.ofertas.contabilidad.planes-pago', $oferta->id) }}"
                   class="btn btn-warning btn-sm" title="Gestión Contable">
                    <i class="ri-money-dollar-circle-line"></i>
                </a>
            @endif
            <a href="{{ route('admin.ofertas.vermodulos', $oferta->id) }}"
               class="btn btn-info btn-sm" title="Ver Módulos">
                <i class="ri-book-open-line"></i>
            </a>
            <a href="{{ route('admin.ofertas.inscritos', $oferta->id) }}"
               class="btn btn-secondary btn-sm" title="Ver Inscritos">
                <i class="ri-eye-line"></i>
            </a>
        @endif

    </div>

    {{-- Cambio de fase --}}
    @if (Auth::guard('web')->user()->can('fases.administrar'))
        @php $maxFase = \App\Models\Fase::max('n_fase') ?? 3; @endphp
        @if ($oferta->fase_id > 1 || $oferta->fase_id < $maxFase)
            <div class="btn-group" role="group">
                @if ($oferta->fase_id > 1)
                    <button type="button" class="btn btn-outline-primary btn-sm change-phase"
                            data-oferta-id="{{ $oferta->id }}"
                            data-direction="-1" title="Fase anterior">
                        <i class="ri-arrow-left-line"></i>
                    </button>
                @endif
                @if ($oferta->fase_id < $maxFase)
                    <button type="button" class="btn btn-outline-success btn-sm change-phase"
                            data-oferta-id="{{ $oferta->id }}"
                            data-direction="1" title="Fase siguiente">
                        <i class="ri-arrow-right-line"></i>
                    </button>
                @endif
            </div>
        @endif
    @endif

</div>
