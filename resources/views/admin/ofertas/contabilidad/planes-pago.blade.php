@extends('admin.dashboard')

@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.ofertas.listar') }}">Ofertas Académicas</a></li>
            <li class="breadcrumb-item"><a
                    href="{{ route('admin.ofertas.dashboard', $oferta->id) }}">{{ $oferta->codigo }}</a></li>
            <li class="breadcrumb-item active"><strong>Gestión Contable - Planes de Pago</strong></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <!-- Card Principal -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary bg-gradient text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0 text-white">
                                <i class="ri-money-dollar-circle-fill me-2"></i>
                                Gestión Contable de Planes de Pago
                            </h4>
                            <p class="mb-0 text-white-50 fs-14">
                                <i class="ri-bookmark-line me-1"></i> {{ $oferta->programa->nombre ?? 'Sin programa' }} |
                                <i class="ri-bank-card-line me-1"></i>
                                {{ $oferta->posgrado->convenio->nombre ?? 'Sin convenio' }}
                            </p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-white text-primary fs-12 fw-semibold py-2 px-3">
                                <i class="ri-hashtag me-1"></i> {{ $oferta->codigo }}
                            </span>
                            <span class="badge bg-light text-dark fs-12 ms-2 py-2 px-3">
                                <i class="ri-building-line me-1"></i> {{ $oferta->sucursal->sede->nombre ?? 'Sin sede' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Resumen Financiero - Estilo Dashboard -->
                <div class="card-body border-bottom">
                    <h5 class="mb-3 text-dark">
                        <i class="ri-pie-chart-line me-2"></i> Resumen Financiero
                    </h5>
                    <div class="row g-3">
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-hover border-start border-primary border-4 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <span class="text-muted fs-12 fw-semibold text-uppercase">Total Inscritos</span>
                                            <h2 class="mt-1 mb-0 text-primary">
                                                {{ $informacionFinanciera['total_inscritos'] }}
                                            </h2>
                                            <span class="text-muted fs-11">Participantes activos</span>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle bg-primary bg-opacity-10 p-3">
                                                <i class="ri-group-line text-primary display-6"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card card-hover border-start border-success border-4 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <span class="text-muted fs-12 fw-semibold text-uppercase">Total Esperado</span>
                                            <h2 class="mt-1 mb-0 text-success">
                                                Bs. {{ number_format($informacionFinanciera['total_esperado'], 2) }}
                                            </h2>
                                            <span class="text-muted fs-11">Deuda total asignada</span>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle bg-success bg-opacity-10 p-3">
                                                <i class="ri-money-dollar-circle-line text-success display-6"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card card-hover border-start border-warning border-4 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <span class="text-muted fs-12 fw-semibold text-uppercase">Total Recaudado</span>
                                            <h2 class="mt-1 mb-0 text-warning">
                                                Bs. {{ number_format($informacionFinanciera['total_recaudado'], 2) }}
                                            </h2>
                                            <span class="text-muted fs-11">
                                                {{ $informacionFinanciera['total_esperado'] > 0 ? round(($informacionFinanciera['total_recaudado'] / $informacionFinanciera['total_esperado']) * 100, 1) : 0 }}%
                                                del total
                                            </span>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle bg-warning bg-opacity-10 p-3">
                                                <i class="ri-bank-card-line text-warning display-6"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card card-hover border-start border-danger border-4 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <span class="text-muted fs-12 fw-semibold text-uppercase">Deuda Pendiente</span>
                                            <h2 class="mt-1 mb-0 text-danger">
                                                Bs. {{ number_format($informacionFinanciera['total_deuda'], 2) }}
                                            </h2>
                                            <span class="text-muted fs-11">
                                                {{ $informacionFinanciera['total_esperado'] > 0 ? round(($informacionFinanciera['total_deuda'] / $informacionFinanciera['total_esperado']) * 100, 1) : 0 }}%
                                                del total
                                            </span>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle bg-danger bg-opacity-10 p-3">
                                                <i class="ri-alarm-warning-line text-danger display-6"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Planes de Pago Configurados -->
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="card-title mb-1 text-dark">
                                <i class="ri-bank-card-2-line me-2"></i>
                                Planes de Pago Configurados
                            </h5>
                            <p class="text-muted mb-0 fs-13">
                                Gestiona y configura los planes de pago disponibles para esta oferta académica
                            </p>
                        </div>
                        @if (count($planesDisponibles) > 0)
                            <button type="button" class="btn btn-primary btn-lg shadow-sm" id="add-new-plan-btn"
                                data-bs-toggle="modal" data-bs-target="#modalNuevoPlan">
                                <i class="ri-add-circle-fill me-2"></i> Nuevo Plan
                            </button>
                        @else
                            <button type="button" class="btn btn-secondary btn-lg" disabled>
                                <i class="ri-add-circle-line me-2"></i> Todos los planes asignados
                            </button>
                        @endif
                    </div>

                    <!-- Contenedor para Planes Existente -->
                    <div id="planes-container">
                        @php
                            $planColors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary', 'dark'];
                            $colorIndex = 0;
                        @endphp

                        @if (count($planesAgrupados) == 0)
                            <!-- Estado vacío -->
                            <div class="text-center py-5 my-5">
                                <div class="mb-4">
                                    <i class="ri-inbox-line display-4 text-muted opacity-50"></i>
                                </div>
                                <h4 class="text-muted mb-3">No hay planes de pago configurados</h4>
                                <p class="text-muted mb-4 w-50 mx-auto">
                                    Comience agregando un nuevo plan de pago para habilitar las inscripciones en esta oferta
                                    académica.
                                </p>
                                @if (count($planesDisponibles) > 0)
                                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                        data-bs-target="#modalNuevoPlan">
                                        <i class="ri-add-circle-fill me-2"></i> Agregar Primer Plan
                                    </button>
                                @endif
                            </div>
                        @else
                            @foreach ($planesAgrupados as $planId => $planData)
                                @php
                                    $plan = $planData['plan'];
                                    $conceptosPlan = $planData['conceptos'];
                                    $tieneInscripciones = in_array($plan->id, $planesConInscripciones);
                                    $totalPlan = $planData['total_plan'];
                                    $planColor = $planColors[$colorIndex % count($planColors)];
                                    $colorIndex++;

                                    // Datos de promoción
                                    $esPromocion = $planData['es_promocion'];
                                    $fechaInicioPromocion = $planData['fecha_inicio_promocion'];
                                    $fechaFinPromocion = $planData['fecha_fin_promocion'];
                                    $promocionVigente = $planData['promocion_vigente'];
                                @endphp

                                <!-- Tarjeta de Plan -->
                                <div class="card mb-4 border shadow-sm" id="plan-{{ $plan->id }}"
                                    data-plan-id="{{ $plan->id }}">
                                    <div
                                        class="card-header bg-{{ $planColor }} bg-opacity-10 border-bottom border-{{ $planColor }} border-3 py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar-sm rounded-circle bg-{{ $planColor }} bg-opacity-25 p-2 me-3">
                                                    <i class="ri-bank-card-2-line text-{{ $planColor }} fs-18"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-0 text-dark">
                                                        {{ $plan->nombre }}
                                                        @if ($esPromocion)
                                                            <span class="badge bg-warning text-dark ms-2 fs-11"
                                                                data-bs-toggle="tooltip"
                                                                title="Promoción vigente desde {{ date('d/m/Y', strtotime($fechaInicioPromocion)) }} hasta {{ date('d/m/Y', strtotime($fechaFinPromocion)) }}">
                                                                <i class="ri-flashlight-fill me-1"></i> PROMOCIÓN
                                                                @if ($promocionVigente)
                                                                    <span class="badge bg-success ms-1">VIGENTE</span>
                                                                @else
                                                                    <span class="badge bg-secondary ms-1">NO VIGENTE</span>
                                                                @endif
                                                            </span>
                                                        @endif
                                                        @if ($tieneInscripciones)
                                                            <span class="badge bg-warning ms-2 fs-11"
                                                                data-bs-toggle="tooltip"
                                                                title="Este plan ya tiene inscripciones registradas">
                                                                <i class="ri-user-follow-line me-1"></i> Con Inscritos
                                                            </span>
                                                        @endif
                                                    </h5>
                                                    <div class="d-flex align-items-center mt-1">
                                                        <span class="text-muted fs-12 me-3">
                                                            <i class="ri-list-check-2 me-1"></i>
                                                            {{ $conceptosPlan->count() }} concepto(s)
                                                        </span>
                                                        <span class="text-dark fs-12 fw-semibold">
                                                            <i class="ri-money-dollar-circle-line me-1"></i> Total: Bs.
                                                            {{ number_format($totalPlan, 2) }}
                                                        </span>
                                                        @if ($esPromocion)
                                                            <span class="text-muted fs-12 ms-3">
                                                                <i class="ri-calendar-event-line me-1"></i>
                                                                {{ date('d/m/Y', strtotime($fechaInicioPromocion)) }} -
                                                                {{ date('d/m/Y', strtotime($fechaFinPromocion)) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @if (!$tieneInscripciones)
                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm delete-plan-btn shadow-sm"
                                                    data-plan-id="{{ $plan->id }}">
                                                    <i class="ri-delete-bin-line me-1"></i> Eliminar Plan
                                                </button>
                                            @else
                                                <span class="badge bg-light text-dark fs-11">
                                                    <i class="ri-lock-line me-1"></i> Bloqueado para edición
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <!-- Tabla de Conceptos -->
                                        <div class="table-responsive mb-4">
                                            <table class="table table-hover table-borderless mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="fw-semibold text-dark border-0" width="25%">Concepto
                                                        </th>
                                                        <th class="fw-semibold text-dark border-0 text-center"
                                                            width="15%">N° Cuotas</th>
                                                        @if ($esPromocion)
                                                            {{-- SOLO para planes promocionales --}}
                                                            <th class="fw-semibold text-dark border-0 text-center"
                                                                width="20%">Precio Regular (Bs.)</th>
                                                            <th class="fw-semibold text-dark border-0 text-center"
                                                                width="20%">Descuento (Bs.)</th>
                                                        @endif
                                                        <th class="fw-semibold text-dark border-0 text-center"
                                                            width="{{ $esPromocion ? '20%' : '40%' }}">Precio Final (Bs.)
                                                        </th>
                                                        <th class="fw-semibold text-dark border-0 text-center"
                                                            width="10%">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($conceptosPlan as $index => $concepto)
                                                        @php
                                                            $precioRegular = $concepto->precio_regular ?? 0;
                                                            $descuentoBs = $concepto->descuento_bs ?? 0;
                                                            $pagoBs = $concepto->pago_bs ?? 0;
                                                        @endphp
                                                        <tr class="concepto-item" data-index="{{ $index }}">
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div
                                                                        class="avatar-xs rounded-circle bg-light p-1 me-2">
                                                                        <i
                                                                            class="ri-price-tag-3-line text-{{ $planColor }}"></i>
                                                                    </div>
                                                                    <span
                                                                        class="fw-medium">{{ $concepto->concepto->nombre ?? 'Sin nombre' }}</span>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <span
                                                                    class="badge bg-{{ $planColor }} bg-opacity-10 text-{{ $planColor }} py-2 px-3 fs-12">
                                                                    {{ $concepto->n_cuotas }} cuota(s)
                                                                </span>
                                                            </td>
                                                            @if ($esPromocion)
                                                                {{-- SOLO para planes promocionales --}}
                                                                <td class="text-center">
                                                                    <span class="fw-semibold text-dark fs-14">
                                                                        Bs. {{ number_format($precioRegular, 2) }}
                                                                    </span>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($descuentoBs > 0)
                                                                        <span
                                                                            class="badge bg-danger bg-opacity-10 text-danger py-2 px-3 fs-12">
                                                                            - Bs. {{ number_format($descuentoBs, 2) }}
                                                                        </span>
                                                                    @else
                                                                        <span
                                                                            class="badge bg-light text-muted py-2 px-3 fs-12">
                                                                            Sin descuento
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                            @endif
                                                            <td class="text-center">
                                                                <span class="fw-bold text-{{ $planColor }} fs-14">
                                                                    Bs. {{ number_format($pagoBs, 2) }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                @if (!$tieneInscripciones)
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-danger remove-concepto-btn"
                                                                        data-index="{{ $index }}"
                                                                        data-plan-id="{{ $plan->id }}">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
                                                                @else
                                                                    <i class="ri-lock-line text-muted"></i>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot class="table-light">
                                                    <tr>
                                                        <td colspan="{{ $esPromocion ? '6' : '4' }}"
                                                            class="text-end py-3">
                                                            <span class="fw-semibold text-dark">Total del Plan:</span>
                                                            <span class="fw-bold text-{{ $planColor }} fs-16 ms-2">
                                                                Bs. {{ number_format($totalPlan, 2) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                        <!-- Formulario de Edición (Oculto por defecto) -->
                                        <div class="edit-conceptos-form d-none" id="edit-form-{{ $plan->id }}">
                                            <form class="plan-form" data-plan-id="{{ $plan->id }}"
                                                data-tiene-inscripciones="{{ $tieneInscripciones ? 'true' : 'false' }}"
                                                data-es-promocion="{{ $esPromocion ? 'true' : 'false' }}">
                                                @csrf
                                                <input type="hidden" name="oferta_id" value="{{ $oferta->id }}">
                                                <input type="hidden" name="plan_pago_id" value="{{ $plan->id }}">
                                                <input type="hidden" name="es_promocion"
                                                    value="{{ $esPromocion ? '1' : '0' }}">

                                                @if ($esPromocion)
                                                    <input type="hidden" name="fecha_inicio_promocion"
                                                        value="{{ $fechaInicioPromocion }}">
                                                    <input type="hidden" name="fecha_fin_promocion"
                                                        value="{{ $fechaFinPromocion }}">
                                                @endif

                                                <div class="conceptos-container mb-3">
                                                    @foreach ($conceptosPlan as $index => $concepto)
                                                        <div class="card mb-3 border">
                                                            <div class="card-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-{{ $esPromocion ? '3' : '4' }}">
                                                                        <label class="form-label fw-semibold">Concepto
                                                                            *</label>
                                                                        <select
                                                                            name="conceptos[{{ $index }}][concepto_id]"
                                                                            class="form-select"
                                                                            {{ $tieneInscripciones ? 'disabled' : 'required' }}>
                                                                            <option value="">Seleccione concepto
                                                                            </option>
                                                                            @foreach ($conceptos as $c)
                                                                                <option value="{{ $c->id }}"
                                                                                    {{ $c->id == $concepto->concepto_id ? 'selected' : '' }}>
                                                                                    {{ $c->nombre }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-md-{{ $esPromocion ? '2' : '3' }}">
                                                                        <label class="form-label fw-semibold">N° Cuotas
                                                                            *</label>
                                                                        <input type="number"
                                                                            name="conceptos[{{ $index }}][n_cuotas]"
                                                                            class="form-control"
                                                                            value="{{ $concepto->n_cuotas }}"
                                                                            min="1"
                                                                            {{ $tieneInscripciones ? 'readonly' : 'required' }}>
                                                                    </div>

                                                                    {{-- SOLO mostrar para promociones --}}
                                                                    @if ($esPromocion)
                                                                        <!-- Precio Regular -->
                                                                        <div class="col-md-2">
                                                                            <label class="form-label fw-semibold">Precio
                                                                                Regular (Bs.) *</label>
                                                                            <input type="number" step="0.01"
                                                                                name="conceptos[{{ $index }}][precio_regular]"
                                                                                class="form-control precio-regular-input"
                                                                                value="{{ $concepto->precio_regular ?? 0 }}"
                                                                                min="0"
                                                                                {{ $tieneInscripciones ? 'readonly' : 'required' }}>
                                                                        </div>

                                                                        <!-- Descuento en Bs. -->
                                                                        <div class="col-md-2">
                                                                            <label class="form-label fw-semibold">Descuento
                                                                                (Bs.)
                                                                            </label>
                                                                            <input type="number" step="0.01"
                                                                                name="conceptos[{{ $index }}][descuento_bs]"
                                                                                class="form-control descuento-bs-input"
                                                                                value="{{ $concepto->descuento_bs ?? 0 }}"
                                                                                min="0"
                                                                                {{ $tieneInscripciones ? 'readonly' : '' }}>
                                                                        </div>
                                                                    @endif

                                                                    <!-- Precio Final (Bs.) -->
                                                                    <div class="col-md-{{ $esPromocion ? '2' : '3' }}">
                                                                        <label class="form-label fw-semibold">Precio Final
                                                                            (Bs.) *</label>
                                                                        <input type="number" step="0.01"
                                                                            name="conceptos[{{ $index }}][pago_bs]"
                                                                            class="form-control pago-bs-input"
                                                                            value="{{ $concepto->pago_bs }}"
                                                                            min="0"
                                                                            {{ $tieneInscripciones ? 'readonly' : 'required' }}
                                                                            {{ $esPromocion ? 'readonly' : '' }}>
                                                                        <small class="text-muted">
                                                                            @if ($esPromocion)
                                                                                Calculado automáticamente (Precio Regular -
                                                                                Descuento)
                                                                            @else
                                                                                Monto total del concepto
                                                                            @endif
                                                                        </small>
                                                                    </div>

                                                                    <div class="col-md-1 text-center">
                                                                        @if (!$tieneInscripciones)
                                                                            <button type="button"
                                                                                class="btn btn-outline-danger btn-sm remove-concepto-form-btn"
                                                                                data-index="{{ $index }}"
                                                                                data-plan-id="{{ $plan->id }}">
                                                                                <i class="ri-delete-bin-line"></i>
                                                                            </button>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                @if (!$tieneInscripciones)
                                                    <div
                                                        class="d-flex justify-content-between align-items-center pt-3 border-top">
                                                        <button type="button"
                                                            class="btn btn-outline-primary add-concepto-btn"
                                                            data-plan-id="{{ $plan->id }}"
                                                            data-es-promocion="{{ $esPromocion ? 'true' : 'false' }}">
                                                            <i class="ri-add-line me-1"></i> Agregar Concepto
                                                        </button>

                                                        <div>
                                                            <button type="button"
                                                                class="btn btn-outline-secondary cancel-edit-btn me-2"
                                                                data-plan-id="{{ $plan->id }}">
                                                                Cancelar
                                                            </button>
                                                            <button type="submit" class="btn btn-success save-plan-btn"
                                                                data-plan-id="{{ $plan->id }}">
                                                                <i class="ri-save-line me-1"></i> Guardar Cambios
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </form>
                                        </div>

                                        <!-- Botones de Acción -->
                                        @if (!$tieneInscripciones)
                                            <div class="d-flex justify-content-end pt-3 border-top">
                                                <button type="button"
                                                    class="btn btn-outline-{{ $planColor }} edit-plan-btn"
                                                    data-plan-id="{{ $plan->id }}">
                                                    <i class="ri-edit-2-line me-1"></i> Editar Plan
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para nuevo plan -->
    <div class="modal fade" id="modalNuevoPlan" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="ri-add-circle-fill me-2"></i>Agregar Nuevo Plan de Pago
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="formNuevoPlan">
                    @csrf
                    <input type="hidden" name="oferta_id" value="{{ $oferta->id }}">

                    <div class="modal-body">
                        <!-- Sección 1: Selección del plan -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Seleccionar Plan de Pago *</label>
                                <select name="planes_pago_id" class="form-select form-select-lg" id="selectPlanPago"
                                    required>
                                    <option value="">Seleccione un tipo de plan</option>
                                    @foreach ($planesDisponibles as $plan)
                                        <option value="{{ $plan->id }}" data-nombre="{{ $plan->nombre }}"
                                            data-principal="{{ $plan->principal }}">
                                            {{ $plan->nombre }}
                                            @if ($plan->principal)
                                                <span class="text-success">(Plan Principal)</span>
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted mt-2">
                                    <i class="ri-information-line me-1"></i>
                                    Solo se muestran planes de pago que aún no están registrados en esta oferta.
                                </div>
                            </div>
                        </div>

                        <!-- Sección 2: Configuración de Promoción -->
                        <div class="card mb-4 border-primary">
                            <div class="card-header bg-primary bg-opacity-10 text-primary">
                                <h6 class="mb-0">
                                    <i class="ri-flashlight-line me-2"></i>Configuración de Promoción
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" id="es_promocion"
                                                name="es_promocion" value="1">
                                            <label class="form-check-label fw-semibold" for="es_promocion">
                                                ¿Este plan es una promoción con descuento?
                                            </label>
                                            <div class="form-text text-muted">
                                                Marque esta opción si este plan tiene precios promocionales con descuento
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos de fecha (ocultos inicialmente) -->
                                <div id="fechas_promocion_container" class="row g-3" style="display: none;">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Fecha Inicio Promoción *</label>
                                        <input type="date" name="fecha_inicio_promocion" id="fecha_inicio_promocion"
                                            class="form-control">
                                        <div class="form-text">
                                            Fecha desde la cual la promoción estará activa
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Fecha Fin Promoción *</label>
                                        <input type="date" name="fecha_fin_promocion" id="fecha_fin_promocion"
                                            class="form-control">
                                        <div class="form-text">
                                            Fecha hasta la cual la promoción estará vigente
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 3: Conceptos del Plan -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-dark">
                                        <i class="ri-list-check-2 me-2"></i>
                                        Conceptos del Plan
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        id="add-concepto-nuevo">
                                        <i class="ri-add-line me-1"></i> Agregar Concepto
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Información sobre plan principal -->
                                <div id="info-plan-principal" class="alert alert-info mb-3" style="display: none;">
                                    <i class="ri-information-line me-2"></i>
                                    Este plan es promocional. Los precios regulares se cargarán automáticamente desde el
                                    plan principal.
                                </div>

                                <div id="conceptos-nuevo-plan" class="mb-3">
                                    <!-- Los conceptos se agregarán dinámicamente aquí -->
                                </div>

                                <div class="alert alert-warning mb-0">
                                    <i class="ri-alert-line me-2"></i>
                                    Recuerde que cada plan debe tener al menos un concepto configurado.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btn-guardar-plan">
                            <i class="ri-save-line me-1"></i> Guardar Nuevo Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Plantilla para un concepto (usada en JavaScript) -->
    <script type="text/template" id="template-concepto">
        <div class="card mb-3 border concepto-item" data-index="__INDEX__">
            <div class="card-header bg-light py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Concepto #__NUM__</span>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-concepto">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Concepto -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Concepto *</label>
                        <select name="conceptos[__INDEX__][concepto_id]" 
                                class="form-select concepto-select" required>
                            <option value="">Seleccione concepto</option>
                            @foreach ($conceptos as $concepto)
                                <option value="{{ $concepto->id }}">{{ $concepto->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Número de cuotas -->
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">N° Cuotas *</label>
                        <input type="number" name="conceptos[__INDEX__][n_cuotas]" 
                            class="form-control n-cuotas-input" value="1" min="1" required>
                    </div>
                    
                    <!-- Monto Total (Bs.) - Se llenará automáticamente si es promoción -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Monto Total (Bs.) *</label>
                        <input type="number" step="0.01" name="conceptos[__INDEX__][pago_bs]" 
                            class="form-control monto-input" value="0" min="0" required>
                        <div class="form-text">
                            <span class="text-muted">Precio final del concepto</span>
                        </div>
                    </div>
                    
                    <!-- Campos de Promoción (ocultos inicialmente) -->
                    <div class="col-md-3">
                        <div class="promo-fields-concepto" style="display: none;">
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="form-label fs-12 mb-1">Precio Regular (Bs.)</label>
                                    <input type="number" step="0.01" 
                                        name="conceptos[__INDEX__][precio_regular]" 
                                        class="form-control precio-regular-input" 
                                        value="0" placeholder="Precio regular" readonly>
                                    <small class="text-muted d-block">Precio del plan principal</small>
                                </div>
                                <div class="col-12 mt-2">
                                    <label class="form-label fs-12 mb-1">Descuento (Bs.)</label>
                                    <input type="number" step="0.01" 
                                        name="conceptos[__INDEX__][descuento_bs]" 
                                        class="form-control descuento-bs-input" 
                                        value="0" placeholder="Descuento en Bs." min="0">
                                    <small class="text-muted d-block">Monto a descontar</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Resumen de cálculo (solo para promociones) -->
                <div class="row mt-3 promo-calculo-resumen" style="display: none;">
                    <div class="col-12">
                        <div class="alert alert-success py-2 mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="ri-calculator-line me-2"></i>
                                    <span class="fw-semibold">Resumen:</span>
                                </div>
                                <div>
                                    <span class="me-3">
                                        <small class="text-muted">Precio regular:</small>
                                        <strong class="text-dark precio-regular-display">Bs. 0.00</strong>
                                    </span>
                                    <span class="me-3">
                                        <small class="text-muted">Descuento:</small>
                                        <strong class="text-danger descuento-display">-Bs. 0.00</strong>
                                    </span>
                                    <span>
                                        <small class="text-muted">Precio final:</small>
                                        <strong class="text-success precio-final-display">Bs. 0.00</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>
@endsection

@push('style')
    <style>
        /* Estilos personalizados para una apariencia más profesional */
        .card {
            border-radius: 0.75rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }

        .border-3 {
            border-width: 3px !important;
        }

        .avatar-sm {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-xs {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fs-11 {
            font-size: 0.75rem !important;
        }

        .fs-12 {
            font-size: 0.8125rem !important;
        }

        .fs-13 {
            font-size: 0.875rem !important;
        }

        .fs-14 {
            font-size: 0.9375rem !important;
        }

        .fs-16 {
            font-size: 1rem !important;
        }

        .fs-18 {
            font-size: 1.125rem !important;
        }

        .table th {
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.05);
        }

        .promo-fields {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 3px solid #ffc107;
        }

        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
        }

        .modal-content {
            border-radius: 1rem;
        }

        .modal-header {
            border-radius: 1rem 1rem 0 0;
        }

        .edit-conceptos-form {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-top: 1rem;
            border: 1px solid #e2e8f0;
        }

        /* Animación para mostrar/ocultar formulario */
        .edit-conceptos-form.show {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Estilos para badges */
        .badge {
            border-radius: 0.375rem;
            font-weight: 500;
        }

        /* Colores personalizados para planes */
        .bg-primary.bg-opacity-10 {
            background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
        }

        .bg-success.bg-opacity-10 {
            background-color: rgba(var(--bs-success-rgb), 0.1) !important;
        }

        .bg-info.bg-opacity-10 {
            background-color: rgba(var(--bs-info-rgb), 0.1) !important;
        }

        .bg-warning.bg-opacity-10 {
            background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
        }

        .bg-danger.bg-opacity-10 {
            background-color: rgba(var(--bs-danger-rgb), 0.1) !important;
        }

        .bg-secondary.bg-opacity-10 {
            background-color: rgba(var(--bs-secondary-rgb), 0.1) !important;
        }

        .bg-dark.bg-opacity-10 {
            background-color: rgba(var(--bs-dark-rgb), 0.1) !important;
        }
    </style>

    <style>
        /* Badges para promociones */
        .badge-promocion {
            background: linear-gradient(135deg, #ffd700 0%, #ffa500 100%);
            color: #000;
            font-weight: 600;
            letter-spacing: 0.5px;
            border: 1px solid #ff9900;
        }

        .badge-vigente {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .badge-no-vigente {
            background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%);
            color: white;
        }

        /* Tooltips mejorados */
        .tooltip-inner {
            max-width: 300px;
            padding: 8px 12px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Estilos para la tabla de conceptos */
        .table th {
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        /* Resaltar descuentos */
        .descuento-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(220, 53, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        /* Para planes promocionales */
        .card.promocional {
            border-left: 5px solid #ffc107;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.1);
        }

        .card.promocional .card-header {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.15) 0%, rgba(255, 193, 7, 0.05) 100%);
        }
    </style>
@endpush

@push('script')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            const CONCEPTOS = @json($conceptos);
            const PLANES_CON_INSCRIPCIONES = @json($planesConInscripciones);
            const OFERTA_ID = {{ $oferta->id }};
            const TOKEN = "{{ csrf_token() }}";

            // URLs para las peticiones AJAX
            const URL_ACTUALIZAR_PLAN = "{{ route('admin.ofertas.actualizar-plan-pago') }}";
            const URL_ELIMINAR_PLAN = "{{ route('admin.ofertas.eliminar-plan-pago') }}";
            const URL_AGREGAR_PLAN = "{{ route('admin.ofertas.agregar-plan-pago') }}";
            const URL_OBTENER_PRECIO_PRINCIPAL = "{{ route('admin.ofertas.obtener-precio-principal') }}";

            // Variables globales para el modal
            let conceptoIndexNuevo = 0;

            // ============================================
            // 1. CÓDIGO PARA EL MODAL DE NUEVO PLAN
            // ============================================

            // Inicializar tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // LIMPIAR COMPLETAMENTE el modal al abrir
            $('#modalNuevoPlan').on('show.bs.modal', function() {
                // Reiniciar el índice
                conceptoIndexNuevo = 0;

                // Limpiar cualquier concepto existente
                $('#conceptos-nuevo-plan').empty();

                // Limpiar formulario
                $('#formNuevoPlan')[0].reset();

                // Ocultar campos de promoción
                $('#fechas_promocion_container').hide();
                $('#info-plan-principal').hide();

                // Quitar validación de fechas (se agregarán después si es promoción)
                $('#fecha_inicio_promocion, #fecha_fin_promocion').prop('required', false);

                // Verificar plan principal
                verificarPlanPrincipal();
            });

            // Verificar si ya existe un plan principal registrado
            function verificarPlanPrincipal() {
                $.ajax({
                    url: "{{ route('admin.ofertas.verificar-plan-principal') }}",
                    type: 'POST',
                    data: {
                        _token: TOKEN,
                        oferta_id: OFERTA_ID
                    },
                    success: function(res) {
                        if (!res.existe) {
                            $('#info-plan-principal').html(
                                '<i class="ri-alert-line me-2"></i>' +
                                '<strong>Advertencia:</strong> No se ha registrado un plan principal para esta oferta. ' +
                                'Es recomendable registrar primero un plan principal para poder crear promociones.'
                            ).removeClass('alert-info').addClass('alert-warning');
                        }
                    }
                });
            }

            // Control para mostrar/ocultar campos de promoción en modal NUEVO
            $('#es_promocion').on('change', function() {
                const esPromocion = $(this).is(':checked');
                const fechasContainer = $('#fechas_promocion_container');
                const infoPrincipal = $('#info-plan-principal');

                if (esPromocion) {
                    fechasContainer.slideDown(300);
                    infoPrincipal.slideDown(300);
                    $('#fecha_inicio_promocion, #fecha_fin_promocion').prop('required', true);

                    // Mostrar campos de promoción en todos los conceptos existentes
                    $('.promo-fields-concepto').slideDown(300);
                    $('.promo-calculo-resumen').slideDown(300);

                    // Hacer que los campos de promoción sean requeridos
                    $('.precio-regular-input, .descuento-bs-input').each(function() {
                        $(this).prop('required', true);
                    });
                } else {
                    fechasContainer.slideUp(300);
                    infoPrincipal.slideUp(300);
                    $('#fecha_inicio_promocion, #fecha_fin_promocion').prop('required', false);

                    // Ocultar campos de promoción
                    $('.promo-fields-concepto').slideUp(300);
                    $('.promo-calculo-resumen').slideUp(300);

                    // Quitar requerido de los campos de promoción
                    $('.precio-regular-input, .descuento-bs-input').each(function() {
                        $(this).prop('required', false);
                    });
                }
            });

            // Agregar un nuevo concepto en modal NUEVO
            $(document).on('click', '#add-concepto-nuevo', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const template = $('#template-concepto').html();
                const html = template
                    .replace(/__INDEX__/g, conceptoIndexNuevo)
                    .replace(/__NUM__/g, conceptoIndexNuevo + 1);

                $('#conceptos-nuevo-plan').append(html);

                // Si es promoción, mostrar campos de promoción
                const esPromocion = $('#es_promocion').is(':checked');
                const conceptoItem = $('#conceptos-nuevo-plan .concepto-item').last();

                if (esPromocion) {
                    conceptoItem.find('.promo-fields-concepto').slideDown(300);
                    conceptoItem.find('.promo-calculo-resumen').slideDown(300);

                    // Hacer que los campos de promoción sean requeridos
                    conceptoItem.find('.precio-regular-input, .descuento-bs-input').prop('required', true);
                } else {
                    // Si no es promoción, quitar el atributo required
                    conceptoItem.find('.precio-regular-input, .descuento-bs-input').prop('required', false);
                }

                conceptoIndexNuevo++;
            });

            // Eliminar un concepto en modal NUEVO
            $(document).on('click', '.btn-remove-concepto', function() {
                const card = $(this).closest('.concepto-item');
                card.fadeOut(300, function() {
                    $(this).remove();
                    // Ajustar índices de los conceptos restantes
                    reindexarConceptos();
                });
            });

            // Función para reindexar conceptos después de eliminar
            function reindexarConceptos() {
                conceptoIndexNuevo = 0;
                $('#conceptos-nuevo-plan .concepto-item').each(function(index) {
                    const newIndex = index;
                    const $this = $(this);

                    // Actualizar atributos data-index
                    $this.attr('data-index', newIndex);

                    // Actualizar nombres de campos del formulario
                    $this.find('[name*="conceptos["]').each(function() {
                        const name = $(this).attr('name');
                        const newName = name.replace(/conceptos\[\d+\]/, `conceptos[${newIndex}]`);
                        $(this).attr('name', newName);
                    });

                    // Actualizar etiquetas
                    $this.find('.card-header span').text(`Concepto #${newIndex + 1}`);

                    conceptoIndexNuevo++;
                });
            }

            // Cuando se selecciona un concepto, cargar precio del plan principal (solo para promociones)
            $(document).on('change', '.concepto-select', function() {
                const conceptoItem = $(this).closest('.concepto-item');
                const conceptoId = $(this).val();
                const esPromocion = $('#es_promocion').is(':checked');

                if (esPromocion && conceptoId) {
                    // Mostrar loader
                    conceptoItem.find('.precio-regular-input').val('Cargando...');

                    $.ajax({
                        url: URL_OBTENER_PRECIO_PRINCIPAL,
                        type: 'POST',
                        data: {
                            _token: TOKEN,
                            oferta_id: OFERTA_ID,
                            concepto_id: conceptoId
                        },
                        success: function(res) {
                            if (res.success && res.precio_regular) {
                                const precioRegular = parseFloat(res.precio_regular);
                                conceptoItem.find('.precio-regular-input').val(precioRegular
                                    .toFixed(2));
                                conceptoItem.find('.precio-regular-display').text('Bs. ' +
                                    precioRegular.toFixed(2));

                                // Establecer el precio regular como base
                                conceptoItem.find('.monto-input').val(precioRegular.toFixed(2));

                                // Calcular precio final
                                calcularPrecioFinal(conceptoItem);
                            } else {
                                conceptoItem.find('.precio-regular-input').val('0.00');
                                conceptoItem.find('.precio-regular-display').text('Bs. 0.00');
                                conceptoItem.find('.monto-input').val('0.00');
                                calcularPrecioFinal(conceptoItem);
                            }
                        },
                        error: function() {
                            conceptoItem.find('.precio-regular-input').val('0.00');
                            conceptoItem.find('.precio-regular-display').text('Bs. 0.00');
                            conceptoItem.find('.monto-input').val('0.00');
                            calcularPrecioFinal(conceptoItem);
                        }
                    });
                }
            });

            // Calcular precio final cuando cambia el descuento
            $(document).on('input', '.descuento-bs-input', function() {
                const conceptoItem = $(this).closest('.concepto-item');
                calcularPrecioFinal(conceptoItem);
            });

            // Calcular precio final cuando cambia el precio regular
            $(document).on('input', '.precio-regular-input', function() {
                const conceptoItem = $(this).closest('.concepto-item');
                calcularPrecioFinal(conceptoItem);
            });

            // Función para calcular precio final
            function calcularPrecioFinal(conceptoItem) {
                const precioRegular = parseFloat(conceptoItem.find('.precio-regular-input').val()) || 0;
                const descuentoBs = parseFloat(conceptoItem.find('.descuento-bs-input').val()) || 0;
                const precioFinal = Math.max(0, precioRegular - descuentoBs);

                // Actualizar displays
                conceptoItem.find('.precio-regular-display').text('Bs. ' + precioRegular.toFixed(2));
                conceptoItem.find('.descuento-display').text('-Bs. ' + descuentoBs.toFixed(2));
                conceptoItem.find('.precio-final-display').text('Bs. ' + precioFinal.toFixed(2));

                // Actualizar campo de precio final
                conceptoItem.find('.monto-input').val(precioFinal.toFixed(2));
            }

            // Validar fechas de promoción
            function validarFechasPromocion() {
                if (!$('#es_promocion').is(':checked')) return true;

                const inicio = $('#fecha_inicio_promocion').val();
                const fin = $('#fecha_fin_promocion').val();

                if (!inicio || !fin) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fechas requeridas',
                        text: 'Para una promoción debe especificar las fechas de inicio y fin',
                        customClass: {
                            confirmButton: 'btn btn-warning'
                        }
                    });
                    return false;
                }

                if (new Date(fin) < new Date(inicio)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fechas inválidas',
                        text: 'La fecha de fin debe ser posterior a la fecha de inicio',
                        customClass: {
                            confirmButton: 'btn btn-warning'
                        }
                    });
                    return false;
                }

                return true;
            }

            // Validar formulario antes de enviar en modal NUEVO
            $('#formNuevoPlan').on('submit', function(e) {
                e.preventDefault();

                const submitBtn = $('#btn-guardar-plan');
                const originalText = submitBtn.html();

                // Validar fechas si es promoción
                if (!validarFechasPromocion()) {
                    return;
                }

                // Validar que haya al menos un concepto
                if ($('#conceptos-nuevo-plan .concepto-item').length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Conceptos requeridos',
                        text: 'Debe agregar al menos un concepto al plan',
                        customClass: {
                            confirmButton: 'btn btn-warning'
                        }
                    });
                    return;
                }

                const esPromocion = $('#es_promocion').is(':checked');
                let conceptosValidos = true;
                let mensajesError = [];

                // Validar cada concepto
                $('#conceptos-nuevo-plan .concepto-item').each(function(index) {
                    const conceptoItem = $(this);
                    const conceptoId = conceptoItem.find('.concepto-select').val();
                    const nCuotas = conceptoItem.find('.n-cuotas-input').val();
                    let monto = conceptoItem.find('.monto-input').val();

                    // Quitar la clase de error primero
                    conceptoItem.removeClass('border-danger');

                    // Validaciones básicas (siempre requeridas)
                    if (!conceptoId || !nCuotas || parseInt(nCuotas) < 1) {
                        conceptosValidos = false;
                        conceptoItem.addClass('border-danger');
                        mensajesError.push(
                            `Concepto ${index + 1}: Datos básicos incompletos (Concepto y N° Cuotas son requeridos)`
                            );
                    }

                    // Validar monto según si es promoción o no
                    if (esPromocion) {
                        const precioRegular = parseFloat(conceptoItem.find('.precio-regular-input')
                            .val()) || 0;
                        const descuentoBs = parseFloat(conceptoItem.find('.descuento-bs-input')
                        .val()) || 0;

                        // Para promociones, validar precio regular y descuento
                        if (precioRegular <= 0) {
                            conceptosValidos = false;
                            conceptoItem.addClass('border-danger');
                            mensajesError.push(
                                `Concepto ${index + 1}: El precio regular debe ser mayor a 0`);
                        }

                        if (descuentoBs < 0) {
                            conceptosValidos = false;
                            conceptoItem.addClass('border-danger');
                            mensajesError.push(
                                `Concepto ${index + 1}: El descuento no puede ser negativo`);
                        }

                        // Calcular el monto automáticamente para promociones
                        monto = (precioRegular - descuentoBs).toFixed(2);
                        conceptoItem.find('.monto-input').val(monto);

                        // Validar que el precio final sea positivo
                        if (parseFloat(monto) <= 0) {
                            conceptosValidos = false;
                            conceptoItem.addClass('border-danger');
                            mensajesError.push(
                                `Concepto ${index + 1}: El precio final debe ser mayor a 0 (Precio Regular - Descuento)`
                                );
                        }
                    } else {
                        // Para planes no promocionales, validar el monto directamente
                        if (!monto || parseFloat(monto) <= 0) {
                            conceptosValidos = false;
                            conceptoItem.addClass('border-danger');
                            mensajesError.push(
                                `Concepto ${index + 1}: El monto total debe ser mayor a 0`);
                        }
                    }
                });

                if (!conceptosValidos) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Datos incompletos o inválidos',
                        html: mensajesError.join('<br>'),
                        customClass: {
                            confirmButton: 'btn btn-warning'
                        }
                    });
                    return;
                }

                // Si todo está bien, proceder a enviar el formulario
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-2"></i> Guardando...');

                const formData = new FormData(this);

                // Añadir campos que puedan faltar
                $('#conceptos-nuevo-plan .concepto-item').each(function(index) {
                    const card = $(this);
                    const esPromocion = $('#es_promocion').is(':checked');

                    // Si es promoción, asegurar que los campos estén presentes
                    if (esPromocion) {
                        const precioRegular = card.find('.precio-regular-input').val() || '0';
                        const descuentoBs = card.find('.descuento-bs-input').val() || '0';
                        const montoFinal = (parseFloat(precioRegular) - parseFloat(descuentoBs))
                            .toFixed(2);

                        formData.set(`conceptos[${index}][precio_regular]`, precioRegular);
                        formData.set(`conceptos[${index}][descuento_bs]`, descuentoBs);
                        formData.set(`conceptos[${index}][pago_bs]`, montoFinal);
                    } else {
                        // Si no es promoción, enviar valores por defecto para campos de promoción
                        formData.set(`conceptos[${index}][precio_regular]`, '0');
                        formData.set(`conceptos[${index}][descuento_bs]`, '0');
                        // El monto final ya está en el campo pago_bs
                    }
                });

                // Enviar formulario
                $.ajax({
                    url: URL_AGREGAR_PLAN,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: res.msg,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                $('#modalNuevoPlan').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: res.msg,
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al guardar el plan.';
                        if (xhr.responseJSON && xhr.responseJSON.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg,
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Validar que fecha fin sea mayor a fecha inicio
            $('#fecha_inicio_promocion').on('change', function() {
                const inicio = $(this).val();
                const fin = $('#fecha_fin_promocion').val();

                if (fin && new Date(fin) < new Date(inicio)) {
                    $('#fecha_fin_promocion').val('');
                }

                $('#fecha_fin_promocion').attr('min', inicio);
            });

            // Limpiar completamente al cerrar el modal NUEVO
            $('#modalNuevoPlan').on('hidden.bs.modal', function() {
                $('#formNuevoPlan')[0].reset();
                $('#conceptos-nuevo-plan').empty();
                $('#fechas_promocion_container').hide();
                $('#info-plan-principal').hide();
                conceptoIndexNuevo = 0;

                // Quitar todas las clases de error
                $('.concepto-item').removeClass('border-danger');
            });

            // ============================================
            // 2. CÓDIGO PARA LA EDICIÓN DE PLANES EXISTENTES
            // ============================================

            // Mostrar/ocultar formulario de edición
            $(document).on('click', '.edit-plan-btn', function() {
                const planId = $(this).data('plan-id');
                const formContainer = $(`#edit-form-${planId}`);

                formContainer.removeClass('d-none');
                formContainer.addClass('show');
                $(this).hide();

                // Hacer scroll suave al formulario
                $('html, body').animate({
                    scrollTop: formContainer.offset().top - 100
                }, 300);
            });

            $(document).on('click', '.cancel-edit-btn', function() {
                const planId = $(this).data('plan-id');
                const formContainer = $(`#edit-form-${planId}`);

                formContainer.removeClass('show');
                setTimeout(() => {
                    formContainer.addClass('d-none');
                    $(`.edit-plan-btn[data-plan-id="${planId}"]`).show();
                }, 300);
            });

            // Agregar concepto a un plan existente
            $(document).on('click', '.add-concepto-btn', function(e) {
                e.preventDefault();
                const planId = $(this).data('plan-id');
                const esPromocion = $(this).data('es-promocion') === 'true';
                const container = $(this).closest('.plan-form').find('.conceptos-container');
                const index = container.find('.card').length;

                const html = `
                <div class="card mb-3 border">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-${esPromocion ? '3' : '4'}">
                                <label class="form-label fw-semibold">Concepto *</label>
                                <select name="conceptos[${index}][concepto_id]" class="form-select" required>
                                    <option value="">Seleccione concepto</option>
                                    ${CONCEPTOS.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('')}
                                </select>
                            </div>
                            
                            <div class="col-md-${esPromocion ? '2' : '3'}">
                                <label class="form-label fw-semibold">N° Cuotas *</label>
                                <input type="number" name="conceptos[${index}][n_cuotas]" 
                                       class="form-control" value="1" min="1" required>
                            </div>
                            
                            ${esPromocion ? `
                                    <!-- Precio Regular -->
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Precio Regular (Bs.) *</label>
                                        <input type="number" step="0.01" 
                                            name="conceptos[${index}][precio_regular]" 
                                            class="form-control precio-regular-input" 
                                            value="0" min="0" required>
                                    </div>
                                    
                                    <!-- Descuento en Bs. -->
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Descuento (Bs.)</label>
                                        <input type="number" step="0.01" 
                                            name="conceptos[${index}][descuento_bs]" 
                                            class="form-control descuento-bs-input" 
                                            value="0" min="0">
                                    </div>
                                ` : ''}
                            
                            <!-- Precio Final (Bs.) -->
                            <div class="col-md-${esPromocion ? '2' : '3'}">
                                <label class="form-label fw-semibold">Precio Final (Bs.) *</label>
                                <input type="number" step="0.01" 
                                    name="conceptos[${index}][pago_bs]" 
                                    class="form-control pago-bs-input" 
                                    value="0" min="0" required
                                    ${esPromocion ? 'readonly' : ''}>
                                <small class="text-muted">
                                    ${esPromocion ? 'Calculado automáticamente (Precio Regular - Descuento)' : 'Monto total del concepto'}
                                </small>
                            </div>
                            
                            <div class="col-md-1 text-center">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-concepto-form-btn"
                                        data-index="${index}" data-plan-id="${planId}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                container.append(html);
            });

            // Eliminar concepto en formulario de edición
            $(document).on('click', '.remove-concepto-form-btn', function() {
                const card = $(this).closest('.card');
                card.fadeOut(300, function() {
                    $(this).remove();
                });
            });

            // Eliminar concepto desde la tabla
            $(document).on('click', '.remove-concepto-btn', function() {
                const conceptoItem = $(this).closest('.concepto-item');
                const planId = $(this).data('plan-id');

                Swal.fire({
                    title: '¿Eliminar concepto?',
                    text: "Esta acción eliminará el concepto del plan",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        conceptoItem.fadeOut(300, function() {
                            $(this).remove();
                            Swal.fire({
                                icon: 'success',
                                title: 'Concepto eliminado',
                                text: 'El concepto ha sido eliminado del plan',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        });
                    }
                });
            });

            // Validar formulario antes de enviar en edición
            function validarFormulario(form) {
                let isValid = true;
                let errorMessages = [];

                form.find('.conceptos-container .card').each(function(index) {
                    const card = $(this);
                    const conceptoSelect = card.find('select[name*="concepto_id"]');
                    const nCuotas = card.find('input[name*="n_cuotas"]');
                    const monto = card.find('input[name*="pago_bs"]');

                    if (!conceptoSelect.val()) {
                        isValid = false;
                        conceptoSelect.addClass('is-invalid');
                        errorMessages.push(`Concepto ${index + 1}: Debe seleccionar un concepto`);
                    } else {
                        conceptoSelect.removeClass('is-invalid');
                    }

                    if (!nCuotas.val() || parseInt(nCuotas.val()) < 1) {
                        isValid = false;
                        nCuotas.addClass('is-invalid');
                        errorMessages.push(`Concepto ${index + 1}: Número de cuotas inválido`);
                    } else {
                        nCuotas.removeClass('is-invalid');
                    }

                    if (!monto.val() || parseFloat(monto.val()) <= 0) {
                        isValid = false;
                        monto.addClass('is-invalid');
                        errorMessages.push(`Concepto ${index + 1}: Monto inválido`);
                    } else {
                        monto.removeClass('is-invalid');
                    }
                });

                return {
                    isValid,
                    errorMessages
                };
            }

            // Guardar cambios de un plan específico
            $(document).on('submit', '.plan-form', function(e) {
                e.preventDefault();

                const form = $(this);
                const planId = form.data('plan-id');
                const tieneInscripciones = form.data('tiene-inscripciones') === 'true';
                const esPromocion = form.data('es-promocion') === 'true';
                const submitBtn = form.find('.save-plan-btn');
                const originalText = submitBtn.html();

                if (tieneInscripciones) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Plan con inscripciones',
                        text: 'Este plan tiene inscripciones registradas y no puede ser modificado.',
                        customClass: {
                            confirmButton: 'btn btn-warning'
                        },
                        buttonsStyling: false
                    });
                    return;
                }

                // Validación especial para promociones en edición
                if (esPromocion) {
                    let promocionValida = true;
                    let mensajesPromo = [];

                    form.find('.conceptos-container .card').each(function(index) {
                        const card = $(this);
                        const precioRegular = parseFloat(card.find('.precio-regular-input')
                        .val()) || 0;
                        const descuentoBs = parseFloat(card.find('.descuento-bs-input').val()) || 0;
                        const montoFinal = parseFloat(card.find('.pago-bs-input').val()) || 0;

                        if (precioRegular <= 0) {
                            promocionValida = false;
                            card.find('.precio-regular-input').addClass('is-invalid');
                            mensajesPromo.push(
                                `Concepto ${index + 1}: Precio regular debe ser mayor a 0`);
                        } else {
                            card.find('.precio-regular-input').removeClass('is-invalid');
                        }

                        if (descuentoBs < 0) {
                            promocionValida = false;
                            card.find('.descuento-bs-input').addClass('is-invalid');
                            mensajesPromo.push(
                                `Concepto ${index + 1}: Descuento no puede ser negativo`);
                        } else {
                            card.find('.descuento-bs-input').removeClass('is-invalid');
                        }

                        if (montoFinal <= 0) {
                            promocionValida = false;
                            card.find('.pago-bs-input').addClass('is-invalid');
                            mensajesPromo.push(
                                `Concepto ${index + 1}: Precio final debe ser mayor a 0`);
                        } else {
                            card.find('.pago-bs-input').removeClass('is-invalid');
                        }
                    });

                    if (!promocionValida) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Errores en promoción',
                            html: mensajesPromo.join('<br>'),
                            customClass: {
                                confirmButton: 'btn btn-warning'
                            },
                            buttonsStyling: false
                        });
                        return;
                    }
                }

                const validacion = validarFormulario(form);
                if (!validacion.isValid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Errores de validación',
                        html: validacion.errorMessages.join('<br>'),
                        customClass: {
                            confirmButton: 'btn btn-warning'
                        },
                        buttonsStyling: false
                    });
                    return;
                }

                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-2"></i> Guardando...');

                const formData = new FormData(form[0]);

                // Para promociones en edición, asegurar que los cálculos estén correctos
                if (esPromocion) {
                    form.find('.conceptos-container .card').each(function(index) {
                        const card = $(this);
                        const precioRegular = parseFloat(card.find('.precio-regular-input')
                        .val()) || 0;
                        const descuentoBs = parseFloat(card.find('.descuento-bs-input').val()) || 0;
                        const montoFinal = Math.max(0, precioRegular - descuentoBs);

                        // Actualizar el valor en el formData
                        formData.set(`conceptos[${index}][pago_bs]`, montoFinal.toFixed(2));
                    });
                }

                $.ajax({
                    url: URL_ACTUALIZAR_PLAN,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: res.msg,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: res.msg,
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                },
                                buttonsStyling: false
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al guardar los cambios.';
                        if (xhr.responseJSON && xhr.responseJSON.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg,
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        });
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Eliminar plan completo
            $(document).on('click', '.delete-plan-btn', function() {
                const planId = $(this).data('plan-id');
                const planCard = $(`#plan-${planId}`);
                const planName = planCard.find('.card-header h5').text().trim();

                Swal.fire({
                    title: '¿Eliminar plan completo?',
                    html: `Está a punto de eliminar el plan: <strong>${planName}</strong><br><br>
                           <div class="alert alert-danger border-0 bg-danger bg-opacity-10">
                               <i class="ri-alert-line me-2"></i>
                               Esta acción eliminará todos los conceptos asociados y no se puede deshacer.
                           </div>`,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: URL_ELIMINAR_PLAN,
                            type: 'POST',
                            data: {
                                _token: TOKEN,
                                oferta_id: OFERTA_ID,
                                plan_pago_id: planId
                            },
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Éxito!',
                                        text: res.msg,
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: res.msg,
                                        customClass: {
                                            confirmButton: 'btn btn-danger'
                                        },
                                        buttonsStyling: false
                                    });
                                }
                            },
                            error: function(xhr) {
                                let errorMsg = 'Error al eliminar el plan.';
                                if (xhr.responseJSON && xhr.responseJSON.msg) {
                                    errorMsg = xhr.responseJSON.msg;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMsg,
                                    customClass: {
                                        confirmButton: 'btn btn-danger'
                                    },
                                    buttonsStyling: false
                                });
                            }
                        });
                    }
                });
            });

            // Calcular precio final en formulario de edición
            $(document).on('input', '.precio-regular-input, .descuento-bs-input', function() {
                const row = $(this).closest('.row');
                const precioRegular = parseFloat(row.find('.precio-regular-input').val()) || 0;
                const descuentoBs = parseFloat(row.find('.descuento-bs-input').val()) || 0;
                const precioFinal = Math.max(0, precioRegular - descuentoBs);

                row.find('.pago-bs-input').val(precioFinal.toFixed(2));
            });

            // Inicializar tooltips para badges de promoción
            $(document).on('mouseenter', '.badge[data-bs-toggle="tooltip"]', function() {
                const tooltip = new bootstrap.Tooltip(this);
                tooltip.show();
            });

            // Actualizar la apariencia de las tarjetas según si son promocionales
            $(document).ready(function() {
                $('.card').each(function() {
                    const hasPromoBadge = $(this).find('.badge:contains("PROMOCIÓN")').length > 0;
                    if (hasPromoBadge) {
                        $(this).addClass('promocional');
                    }
                });
            });

            // Estilo para spinner de carga
            const style = document.createElement('style');
            style.textContent = `
                .spin {
                    animation: spin 1s linear infinite;
                }
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
                .concepto-item.border-danger {
                    border-color: #dc3545 !important;
                    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
                }
                .is-invalid {
                    border-color: #dc3545 !important;
                    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
                }
            `;
            document.head.appendChild(style);
        });
    </script>
@endpush
