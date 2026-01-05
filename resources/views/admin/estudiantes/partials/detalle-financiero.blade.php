@if ($estudiante->inscripciones->count() > 0)
    <div class="card border">
        <div class="card-header border-bottom bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fs-16">Resumen Financiero</h5>
                @php
                    // Calcular totales generales
                    $totalProgramas = $estudiante->inscripciones->count();
                    $totalDeudaGeneral = 0;
                    $totalPagadoGeneral = 0;
                    $totalCuotas = 0;
                    $cuotasPagadas = 0;
                    $cuotasPendientes = 0;

                    foreach ($estudiante->inscripciones as $inscripcion) {
                        foreach ($inscripcion->cuotas as $cuota) {
                            $totalCuotas++;
                            $totalPagadoGeneral += $cuota->pago_total_bs - $cuota->pago_pendiente_bs;
                            $totalDeudaGeneral += $cuota->pago_pendiente_bs;

                            if ($cuota->pago_terminado == 'si') {
                                $cuotasPagadas++;
                            } else {
                                $cuotasPendientes++;
                            }
                        }
                    }

                    $porcentajePagado =
                        $totalPagadoGeneral > 0
                            ? ($totalPagadoGeneral / ($totalPagadoGeneral + $totalDeudaGeneral)) * 100
                            : 0;
                    $colorEstadoFinanciero = match (true) {
                        $porcentajePagado == 100 => 'success',
                        $porcentajePagado >= 75 => 'primary',
                        $porcentajePagado >= 50 => 'warning',
                        default => 'danger',
                    };
                @endphp
                <div>
                    <span class="badge bg-success">Pagado:
                        {{ number_format($totalPagadoGeneral, 2) }} Bs</span>
                    <span class="badge bg-danger ms-2">Deuda:
                        {{ number_format($totalDeudaGeneral, 2) }} Bs</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Resumen financiero general -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light border">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Estado Financiero General</h6>
                                <span class="badge bg-{{ $colorEstadoFinanciero }}">
                                    {{ number_format($porcentajePagado, 1) }}% Completado
                                </span>
                            </div>
                            <div class="progress" style="height: 12px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $porcentajePagado }}%">
                                </div>
                            </div>
                            <div class="row mt-3 text-center">
                                <div class="col-md-3 col-6 mb-2 mb-md-0">
                                    <div>
                                        <h5 class="text-primary mb-1">{{ $totalCuotas }}
                                        </h5>
                                        <small class="text-muted">Total Cuotas</small>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-2 mb-md-0">
                                    <div>
                                        <h5 class="text-success mb-1">{{ $cuotasPagadas }}
                                        </h5>
                                        <small class="text-muted">Cuotas Pagadas</small>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div>
                                        <h5 class="text-warning mb-1">{{ $cuotasPendientes }}
                                        </h5>
                                        <small class="text-muted">Cuotas Pendientes</small>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div>
                                        <h5 class="text-info mb-1">{{ $totalProgramas }}</h5>
                                        <small class="text-muted">Programas</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acordeón por programa -->
            <div class="accordion accordion-flush" id="accordionFinanciero">
                @foreach ($estudiante->inscripciones->sortByDesc('fecha_registro') as $index => $inscripcion)
                    @php
                        $oferta = $inscripcion->ofertaAcademica;
                        $programa = $oferta->programa ?? null;
                        $cuotas = $inscripcion->cuotas ?? collect();
                        $cuotasOrdenadas = $inscripcion->cuotas_ordenadas ?? $cuotas;

                        // Calcular estadísticas por inscripción
                        $deudaInscripcion = 0;
                        $pagadoInscripcion = 0;
                        $cuotasTotales = $cuotas->count();
                        $cuotasPagadasInscripcion = 0;
                        $cuotasPendientesInscripcion = 0;

                        foreach ($cuotas as $cuota) {
                            $deudaInscripcion += $cuota->pago_pendiente_bs;
                            $pagadoInscripcion += $cuota->pago_total_bs - $cuota->pago_pendiente_bs;

                            if ($cuota->pago_terminado == 'si') {
                                $cuotasPagadasInscripcion++;
                            } else {
                                $cuotasPendientesInscripcion++;
                            }
                        }

                        $porcentajePagadoInscripcion =
                            $pagadoInscripcion + $deudaInscripcion > 0
                                ? ($pagadoInscripcion / ($pagadoInscripcion + $deudaInscripcion)) * 100
                                : 0;

                        $colorEstadoInscripcion = match (true) {
                            $porcentajePagadoInscripcion == 100 => 'success',
                            $porcentajePagadoInscripcion >= 75 => 'primary',
                            $porcentajePagadoInscripcion >= 50 => 'warning',
                            default => 'danger',
                        };
                    @endphp

                    <div class="accordion-item border-bottom">
                        <h2 class="accordion-header" id="financieroHeading{{ $index }}">
                            <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#financieroCollapse{{ $index }}"
                                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-controls="financieroCollapse{{ $index }}">
                                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded">
                                                    <i class="ri-money-dollar-circle-line fs-16"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">
                                                {{ $programa->nombre ?? 'Programa no especificado' }}
                                            </h6>
                                            <div class="text-muted small">
                                                <span class="me-2">
                                                    <i class="ri-calendar-line me-1"></i>
                                                    {{ $oferta->gestion ?? 'N/A' }}
                                                </span>
                                                •
                                                <span class="mx-2">
                                                    Plan:
                                                    {{ $inscripcion->planesPago->nombre ?? 'N/A' }}
                                                </span>
                                                •
                                                <span class="ms-2">
                                                    Estado:
                                                    <span class="badge bg-{{ $colorEstadoInscripcion }}">
                                                        {{ number_format($porcentajePagadoInscripcion, 0) }}%
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="d-flex flex-column align-items-end">
                                            <span class="badge bg-success mb-1">
                                                Pagado:
                                                {{ number_format($pagadoInscripcion, 2) }} Bs
                                            </span>
                                            <span class="badge bg-danger mb-1">
                                                Deuda:
                                                {{ number_format($deudaInscripcion, 2) }} Bs
                                            </span>
                                            <div class="text-muted small">
                                                {{ $cuotasPagadasInscripcion }}/{{ $cuotasTotales }}
                                                cuotas
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="financieroCollapse{{ $index }}"
                            class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                            aria-labelledby="financieroHeading{{ $index }}"
                            data-bs-parent="#accordionFinanciero">
                            <div class="accordion-body p-4">
                                <!-- Resumen del programa -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card bg-light border">
                                            <div class="card-body p-3">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="d-flex flex-column">
                                                            <span class="text-muted small">Total
                                                                Programa</span>
                                                            <span class="fw-bold fs-5">
                                                                {{ number_format($pagadoInscripcion + $deudaInscripcion, 2) }}
                                                                Bs
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="d-flex flex-column">
                                                            <span class="text-muted small">Pagado</span>
                                                            <span class="fw-bold fs-5 text-success">
                                                                {{ number_format($pagadoInscripcion, 2) }}
                                                                Bs
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="d-flex flex-column">
                                                            <span class="text-muted small">Pendiente</span>
                                                            <span class="fw-bold fs-5 text-danger">
                                                                {{ number_format($deudaInscripcion, 2) }}
                                                                Bs
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($cuotas->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="8%" class="text-center">#
                                                    </th>
                                                    <th width="25%">Nombre Cuota</th>
                                                    <th width="12%" class="text-center">
                                                        Monto Total (Bs)</th>
                                                    <th width="12%" class="text-center">
                                                        Pagado (Bs)</th>
                                                    <th width="12%" class="text-center">
                                                        Pendiente (Bs)</th>
                                                    <th width="13%" class="text-center">
                                                        Fecha Pago</th>
                                                    <th width="10%" class="text-center">
                                                        Estado</th>
                                                    <th width="8%" class="text-center">
                                                        Tipo</th>
                                                    <th width="8%" class="text-center">
                                                        Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Reemplaza la sección de acciones de las cuotas con este código corregido -->

                                                @foreach ($cuotasOrdenadas as $cuota)
                                                    @php
                                                        $pagosRealizados = $cuota->pagos_cuotas ?? collect();
                                                        $tienePagos = $pagosRealizados->count() > 0;
                                                        $pagado = $cuota->pago_total_bs - $cuota->pago_pendiente_bs;
                                                        $estado = $cuota->pago_terminado;
                                                        $fechaPago = $cuota->fecha_pago;
                                                        $nombreLower = mb_strtolower($cuota->nombre, 'UTF-8');

                                                        // Determinar tipo de cuota
                                                        $tipoCuota = 'Regular';
                                                        $tipoColor = 'secondary';

                                                        if (
                                                            str_contains($nombreLower, 'matricula') ||
                                                            str_contains($nombreLower, 'matrícula')
                                                        ) {
                                                            $tipoCuota = 'Matrícula';
                                                            $tipoColor = 'primary';
                                                        } elseif (
                                                            str_contains($nombreLower, 'certificación') ||
                                                            str_contains($nombreLower, 'certificacion')
                                                        ) {
                                                            $tipoCuota = 'Certificación';
                                                            $tipoColor = 'info';
                                                        }
                                                    @endphp

                                                    <tr
                                                        class="{{ $estado == 'si' ? 'table-success' : 'table-warning' }}">
                                                        <td class="text-center fw-medium">
                                                            <span
                                                                class="badge bg-light text-dark">{{ $cuota->n_cuota }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="fw-medium">{{ $cuota->nombre }}</div>
                                                            @if ($pagosRealizados->count() > 0)
                                                                <small
                                                                    class="text-muted">{{ $pagosRealizados->count() }}
                                                                    pago(s) realizado(s)</small>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <span
                                                                class="fw-bold">{{ number_format($cuota->pago_total_bs, 2) }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span
                                                                class="text-success fw-medium">{{ number_format($pagado, 2) }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span
                                                                class="text-danger fw-medium">{{ number_format($cuota->pago_pendiente_bs, 2) }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($fechaPago)
                                                                {{ \Carbon\Carbon::parse($fechaPago)->format('d/m/Y') }}
                                                            @else
                                                                <span class="text-muted">Por definir</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($estado == 'si')
                                                                <span class="badge bg-success">
                                                                    <i class="ri-check-line me-1"></i> Pagado
                                                                </span>
                                                            @else
                                                                <span class="badge bg-warning">
                                                                    <i class="ri-time-line me-1"></i> Pendiente
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <span
                                                                class="badge bg-{{ $tipoColor }}">{{ $tipoCuota }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($cuota->pago_pendiente_bs > 0)
                                                                <button
                                                                    class="btn btn-sm btn-success btn-pagar-cuota mb-1"
                                                                    data-cuota-id="{{ $cuota->id }}"
                                                                    data-estudiante-id="{{ $estudiante->id }}">
                                                                    <i class="ri-money-dollar-circle-line me-1"></i>
                                                                    Pagar
                                                                </button>
                                                            @endif

                                                            @if ($tienePagos)
                                                                <button class="btn btn-sm btn-info btn-ver-recibos"
                                                                    data-cuota-id="{{ $cuota->id }}"
                                                                    data-cuota-nombre="{{ $cuota->nombre }}">
                                                                    <i class="ri-receipt-line me-1"></i> Recibos
                                                                    ({{ $pagosRealizados->count() }})
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <td colspan="2" class="text-end fw-bold">Totales:</td>
                                                    <td class="text-center fw-bold">
                                                        {{ number_format($pagadoInscripcion + $deudaInscripcion, 2) }}
                                                        Bs
                                                    </td>
                                                    <td class="text-center fw-bold text-success">
                                                        {{ number_format($pagadoInscripcion, 2) }}
                                                        Bs
                                                    </td>
                                                    <td class="text-center fw-bold text-danger">
                                                        {{ number_format($deudaInscripcion, 2) }}
                                                        Bs
                                                    </td>
                                                    <td colspan="3"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <!-- Leyenda mejorada -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="alert alert-light border small">
                                                <div class="d-flex flex-wrap gap-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success rounded-circle me-2"
                                                            style="width: 12px; height: 12px;">
                                                        </div>
                                                        <span>Cuota pagada completamente
                                                            (pago_terminado = 'si')
                                                        </span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-warning rounded-circle me-2"
                                                            style="width: 12px; height: 12px;">
                                                        </div>
                                                        <span>Cuota con pago pendiente
                                                            (pago_terminado = 'no')</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-primary me-2">Matrícula</span>
                                                        <span>Cuotas de matrícula (primero en la
                                                            lista)</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-secondary me-2">Regular</span>
                                                        <span>Cuotas regulares</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-info me-2">Certificación</span>
                                                        <span>Cuotas de certificación (últimas
                                                            en la lista)</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-light text-secondary rounded-circle">
                                                <i class="ri-money-dollar-circle-line fs-2"></i>
                                            </div>
                                        </div>
                                        <h5 class="mb-2">No hay cuotas registradas</h5>
                                        <p class="text-muted mb-0">Este programa no tiene
                                            cuotas generadas aún.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Resumen final -->
        <div class="card-footer bg-light">
            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary-subtle text-primary rounded">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">
                                {{ number_format($totalPagadoGeneral + $totalDeudaGeneral, 2) }}
                                Bs</h6>
                            <small class="text-muted">Monto Total General</small>
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
                            <h6 class="mb-0">{{ number_format($totalPagadoGeneral, 2) }} Bs
                            </h6>
                            <small class="text-muted">Total Pagado</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-danger-subtle text-danger rounded">
                                    <i class="ri-alert-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">{{ number_format($totalDeudaGeneral, 2) }} Bs
                            </h6>
                            <small class="text-muted">Total Deuda</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver recibos de una cuota -->
    <div class="modal fade" id="modalRecibosCuota" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRecibosTitle">Recibos de la Cuota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contenidoRecibos">
                    <!-- Contenido dinámico -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para Pagar Cuota -->
    <div class="modal fade" id="modalPagarCuota" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formPagarCuota">
                    @csrf
                    <input type="hidden" id="cuota_id" name="cuota_id">
                    <input type="hidden" id="estudiante_id" name="estudiante_id">

                    <div class="modal-body">
                        <!-- Información de la cuota -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Cuota:</strong> <span
                                                    id="info-cuota-nombre"></span></p>
                                            <p class="mb-1"><strong>Programa:</strong> <span
                                                    id="info-cuota-programa"></span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Total Cuota:</strong> <span
                                                    id="info-cuota-total" class="text-primary"></span> Bs</p>
                                            <p class="mb-1"><strong>Saldo Pendiente:</strong> <span
                                                    id="info-cuota-pendiente" class="text-danger"></span> Bs</p>
                                            <p class="mb-0"><strong>Saldo Pagado:</strong> <span
                                                    id="info-cuota-pagado" class="text-success"></span> Bs</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="monto_pago" class="form-label">Monto a Pagar (Bs) *</label>
                                    <input type="number" step="0.01" class="form-control" id="monto_pago"
                                        name="monto_pago" required>
                                    <div class="form-text">
                                        Máximo: <span id="maximo_pago" class="text-danger fw-bold">0.00</span> Bs
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="descuento" class="form-label">Descuento (Bs)</label>
                                    <input type="number" step="0.01" class="form-control" id="descuento"
                                        name="descuento" value="0">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tipo_pago" class="form-label">Tipo de Pago *</label>
                                    <select class="form-select" id="tipo_pago" name="tipo_pago" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Depósito">Depósito</option>
                                        <option value="Tarjeta">Tarjeta</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_pago" class="form-label">Fecha de Pago *</label>
                                    <input type="date" class="form-control" id="fecha_pago" name="fecha_pago"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
                                </div>
                            </div>

                            <!-- Resumen del pago en tiempo real -->
                            <div class="col-12">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">Resumen del Pago</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <p class="mb-1 text-muted">Monto a Pagar</p>
                                                <h5 class="text-primary" id="resumen-monto">0.00 Bs</h5>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-1 text-muted">Descuento</p>
                                                <h5 class="text-warning" id="resumen-descuento">0.00 Bs</h5>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-1 text-muted">Total a Pagar</p>
                                                <h5 class="text-success" id="resumen-total">0.00 Bs</h5>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success" id="progreso-pago"
                                                        role="progressbar" style="width: 0%"></div>
                                                </div>
                                                <small class="text-muted mt-1 d-block text-center">
                                                    <span id="texto-progreso">0% del saldo pendiente</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Registrar Pago</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar recibo -->
    <div class="modal fade" id="modalReciboGenerado" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pago Registrado Exitosamente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="ri-checkbox-circle-line text-success display-4"></i>
                    </div>
                    <h4 class="text-success mb-3">¡Pago Registrado!</h4>
                    <p class="mb-1">Recibo N°: <strong id="recibo-numero"></strong></p>
                    <p class="mb-1">Monto: <strong id="recibo-monto"></strong> Bs</p>
                    <p class="mb-3">Fecha: <strong id="recibo-fecha"></strong></p>
                    <div class="alert alert-info">
                        <i class="ri-information-line me-1"></i>
                        El recibo se ha generado correctamente. Puede descargarlo ahora.
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="#" id="descargar-recibo" class="btn btn-primary" target="_blank">
                        <i class="ri-download-line me-1"></i> Descargar Recibo
                    </a>
                    <button type="button" class="btn btn-success" onclick="location.reload()">
                        <i class="ri-refresh-line me-1"></i> Actualizar Página
                    </button>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="card border">
        <div class="card-body text-center py-5">
            <div class="avatar-lg mx-auto mb-3">
                <div class="avatar-title bg-light text-secondary rounded-circle">
                    <i class="ri-money-dollar-circle-line fs-2"></i>
                </div>
            </div>
            <h5 class="mb-2">No hay información financiera</h5>
            <p class="text-muted mb-0">El estudiante no está inscrito en ningún programa.</p>
            <a href="{{ route('admin.inscripciones.registrar') }}" class="btn btn-primary mt-3">
                <i class="ri-user-add-line me-1"></i> Realizar Inscripción
            </a>
        </div>
    </div>
@endif
