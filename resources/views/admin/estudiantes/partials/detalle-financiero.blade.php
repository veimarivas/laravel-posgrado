@if ($estudiante->inscripciones->count() > 0)
    @php
        $totalProgramas      = $estudiante->inscripciones->count();
        $totalDeudaGeneral   = 0;
        $totalPagadoGeneral  = 0;
        $totalCuotas         = 0;
        $cuotasPagadas       = 0;
        $cuotasPendientes    = 0;

        foreach ($estudiante->inscripciones as $inscripcion) {
            foreach ($inscripcion->cuotas as $cuota) {
                $totalCuotas++;
                $totalPagadoGeneral += $cuota->pago_total_bs - $cuota->pago_pendiente_bs;
                $totalDeudaGeneral  += $cuota->pago_pendiente_bs;
                if ($cuota->pago_terminado == 'si') { $cuotasPagadas++; } else { $cuotasPendientes++; }
            }
        }

        $porcentajePagado = ($totalPagadoGeneral + $totalDeudaGeneral) > 0
            ? ($totalPagadoGeneral / ($totalPagadoGeneral + $totalDeudaGeneral)) * 100
            : 0;

        $colorEstadoFinanciero = match (true) {
            $porcentajePagado == 100 => 'success',
            $porcentajePagado >= 75  => 'primary',
            $porcentajePagado >= 50  => 'warning',
            default                  => 'danger',
        };
    @endphp

    {{-- Resumen financiero global --}}
    <div class="rounded-3 border p-3 mb-4"
         style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h5 class="mb-0 fw-semibold">Resumen Financiero</h5>
                <p class="text-muted small mb-0">{{ $totalProgramas }} programa(s) · {{ $totalCuotas }} cuota(s)</p>
            </div>
            <span class="badge bg-{{ $colorEstadoFinanciero }} rounded-pill px-3 py-2 fs-12">
                {{ number_format($porcentajePagado, 1) }}% pagado
            </span>
        </div>

        {{-- Barra de progreso --}}
        <div class="progress rounded-pill mb-3" style="height:10px;">
            <div class="progress-bar bg-{{ $colorEstadoFinanciero }}"
                 style="width:{{ $porcentajePagado }}%"></div>
        </div>

        {{-- Stats 4 columnas --}}
        <div class="row g-2 text-center">
            <div class="col-6 col-md-3">
                <div class="rounded-2 border py-2 px-1">
                    <div class="fw-bold fs-5 text-primary">{{ $totalCuotas }}</div>
                    <div class="text-muted" style="font-size:.72rem;">Total Cuotas</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="rounded-2 border py-2 px-1" style="border-color:#198754!important;background:#f0fff5;">
                    <div class="fw-bold fs-5 text-success">{{ $cuotasPagadas }}</div>
                    <div class="text-muted" style="font-size:.72rem;">Pagadas</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="rounded-2 border py-2 px-1" style="border-color:#ffc107!important;background:#fffdf0;">
                    <div class="fw-bold fs-5 text-warning">{{ $cuotasPendientes }}</div>
                    <div class="text-muted" style="font-size:.72rem;">Pendientes</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="rounded-2 border py-2 px-1" style="border-color:#dc3545!important;background:#fff5f5;">
                    <div class="fw-bold fs-5 text-danger">{{ number_format($totalDeudaGeneral, 0) }}</div>
                    <div class="text-muted" style="font-size:.72rem;">Bs Pendiente</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Acordeón por programa --}}
    <div class="accordion" id="accordionFinanciero" style="display:flex; flex-direction:column; gap:.75rem;">
        @foreach ($estudiante->inscripciones->sortByDesc('fecha_registro') as $index => $inscripcion)
            @php
                $oferta   = $inscripcion->ofertaAcademica;
                $programa = $oferta->programa ?? null;
                $cuotas   = $inscripcion->cuotas ?? collect();
                $cuotasOrdenadas = $inscripcion->cuotas_ordenadas ?? $cuotas;

                $deudaInscripcion           = 0;
                $pagadoInscripcion          = 0;
                $cuotasTotales              = $cuotas->count();
                $cuotasPagadasInscripcion   = 0;
                $cuotasPendientesInscripcion = 0;

                foreach ($cuotas as $cuota) {
                    $deudaInscripcion  += $cuota->pago_pendiente_bs;
                    $pagadoInscripcion += $cuota->pago_total_bs - $cuota->pago_pendiente_bs;
                    if ($cuota->pago_terminado == 'si') { $cuotasPagadasInscripcion++; } else { $cuotasPendientesInscripcion++; }
                }

                $porcentajePagadoInscripcion = ($pagadoInscripcion + $deudaInscripcion) > 0
                    ? ($pagadoInscripcion / ($pagadoInscripcion + $deudaInscripcion)) * 100
                    : 0;

                $colorEstadoInscripcion = match (true) {
                    $porcentajePagadoInscripcion == 100 => 'success',
                    $porcentajePagadoInscripcion >= 75  => 'primary',
                    $porcentajePagadoInscripcion >= 50  => 'warning',
                    default                             => 'danger',
                };

                $avatarColors = ['bg-primary','bg-success','bg-info','bg-warning','bg-danger','bg-purple'];
                $avatarColor  = $avatarColors[$index % count($avatarColors)];
                $inicial      = strtoupper(mb_substr($programa->nombre ?? 'P', 0, 1, 'UTF-8'));
            @endphp

            <div class="accordion-item border-0 rounded-3 overflow-hidden"
                 style="box-shadow:0 2px 8px rgba(0,0,0,.08); border-left:4px solid var(--bs-{{ $colorEstadoInscripcion }}) !important;">

                {{-- Header --}}
                <h2 class="accordion-header" id="financieroHeading{{ $index }}">
                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }} py-3 px-4"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#financieroCollapse{{ $index }}"
                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-controls="financieroCollapse{{ $index }}"
                            style="background:transparent;">
                        <div class="d-flex align-items-center w-100 me-2 gap-3">

                            {{-- Avatar inicial --}}
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title {{ $avatarColor }} text-white rounded-2 fw-bold fs-16">
                                    {{ $inicial }}
                                </div>
                            </div>

                            {{-- Info principal --}}
                            <div class="flex-grow-1 min-w-0">
                                <h6 class="mb-1 fw-semibold text-truncate">
                                    {{ $programa->nombre ?? 'Programa no especificado' }}
                                </h6>
                                <div class="d-flex flex-wrap align-items-center gap-3 text-muted" style="font-size:.77rem;">
                                    <span>
                                        <i class="ri-calendar-2-line me-1 text-primary"></i>
                                        {{ $oferta->gestion ?? 'N/A' }}
                                    </span>
                                    <span>
                                        <i class="ri-file-list-3-line me-1 text-primary"></i>
                                        Plan: {{ optional($inscripcion->planesPago)->nombre ?? 'N/A' }}
                                    </span>
                                    <span>
                                        <i class="ri-stack-line me-1 text-primary"></i>
                                        {{ $cuotasPagadasInscripcion }}/{{ $cuotasTotales }} cuotas
                                    </span>
                                </div>
                            </div>

                            {{-- Derecha: montos + mini barra --}}
                            <div class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                                <div class="d-flex gap-1">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size:.72rem;">
                                        {{ number_format($pagadoInscripcion, 2) }} Bs
                                    </span>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size:.72rem;">
                                        {{ number_format($deudaInscripcion, 2) }} Bs
                                    </span>
                                </div>
                                <div style="width:100px;">
                                    <div class="d-flex justify-content-between" style="font-size:.65rem;">
                                        <span class="text-muted">Avance</span>
                                        <span class="fw-semibold text-{{ $colorEstadoInscripcion }}">{{ number_format($porcentajePagadoInscripcion, 0) }}%</span>
                                    </div>
                                    <div class="progress rounded-pill" style="height:5px;">
                                        <div class="progress-bar bg-{{ $colorEstadoInscripcion }}"
                                             style="width:{{ $porcentajePagadoInscripcion }}%"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </button>
                </h2>

                {{-- Body --}}
                <div id="financieroCollapse{{ $index }}"
                     class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                     aria-labelledby="financieroHeading{{ $index }}">
                    <div class="accordion-body p-0">

                        {{-- Barra "Pagar Múltiples" --}}
                        @if ($cuotasPendientesInscripcion > 0)
                            <div class="px-4 py-2 border-top border-bottom d-flex align-items-center justify-content-between"
                                 style="background:#f0f8ff;">
                                <span class="small text-muted">
                                    <i class="ri-list-check-2 me-1 text-primary"></i>
                                    <strong>{{ $cuotasPendientesInscripcion }}</strong> cuota(s) con saldo pendiente
                                </span>
                                <button type="button"
                                        class="btn btn-sm btn-primary btn-pagar-multiple"
                                        data-estudiante-id="{{ $estudiante->id }}">
                                    <i class="ri-stack-line me-1"></i>Pagar Múltiples Cuotas
                                </button>
                            </div>
                        @endif

                        {{-- Resumen financiero del programa --}}
                        <div class="px-4 py-3 bg-light border-bottom">
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="text-muted" style="font-size:.7rem;">TOTAL PROGRAMA</div>
                                    <div class="fw-bold fs-6">{{ number_format($pagadoInscripcion + $deudaInscripcion, 2) }} Bs</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted" style="font-size:.7rem;">PAGADO</div>
                                    <div class="fw-bold fs-6 text-success">{{ number_format($pagadoInscripcion, 2) }} Bs</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted" style="font-size:.7rem;">PENDIENTE</div>
                                    <div class="fw-bold fs-6 text-danger">{{ number_format($deudaInscripcion, 2) }} Bs</div>
                                </div>
                            </div>
                        </div>

                        {{-- Tabla de cuotas --}}
                        @if ($cuotas->count() > 0)
                            <div class="px-3 py-3">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" style="font-size:.84rem;">
                                        <thead>
                                            <tr style="background:#f8f9fa;">
                                                <th width="5%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">#</th>
                                                <th width="24%" class="border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">CUOTA</th>
                                                <th width="11%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">TOTAL</th>
                                                <th width="11%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">PAGADO</th>
                                                <th width="11%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">PENDIENTE</th>
                                                <th width="12%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">FECHA</th>
                                                <th width="10%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">ESTADO</th>
                                                <th width="8%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">TIPO</th>
                                                <th width="8%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">ACCIÓN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cuotasOrdenadas as $cuota)
                                                @php
                                                    $pagosRealizados = $cuota->pagos_cuotas ?? collect();
                                                    $tienePagos      = $pagosRealizados->count() > 0;
                                                    $pagado          = $cuota->pago_total_bs - $cuota->pago_pendiente_bs;
                                                    $estado          = $cuota->pago_terminado;
                                                    $fechaPago       = $cuota->fecha_pago;
                                                    $nombreLower     = mb_strtolower($cuota->nombre, 'UTF-8');

                                                    $tipoCuota = 'Regular';
                                                    $tipoColor = 'secondary';
                                                    if (str_contains($nombreLower, 'matricula') || str_contains($nombreLower, 'matrícula')) {
                                                        $tipoCuota = 'Matrícula'; $tipoColor = 'primary';
                                                    } elseif (str_contains($nombreLower, 'certificación') || str_contains($nombreLower, 'certificacion')) {
                                                        $tipoCuota = 'Certif.'; $tipoColor = 'info';
                                                    }

                                                    $pctCuota = $cuota->pago_total_bs > 0
                                                        ? ($pagado / $cuota->pago_total_bs) * 100
                                                        : 0;
                                                @endphp
                                                <tr>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-dark border">{{ $cuota->n_cuota }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="fw-medium">{{ $cuota->nombre }}</div>
                                                        @if ($pagosRealizados->count() > 0)
                                                            <div class="text-muted" style="font-size:.72rem;">
                                                                <i class="ri-receipt-line me-1"></i>{{ $pagosRealizados->count() }} pago(s)
                                                            </div>
                                                        @endif
                                                        {{-- Mini barra de avance --}}
                                                        <div class="progress mt-1 rounded-pill" style="height:3px;">
                                                            <div class="progress-bar {{ $estado == 'si' ? 'bg-success' : 'bg-warning' }}"
                                                                 style="width:{{ $pctCuota }}%"></div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center fw-medium">
                                                        {{ number_format($cuota->pago_total_bs, 2) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="text-success fw-medium">{{ number_format($pagado, 2) }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($cuota->pago_pendiente_bs > 0)
                                                            <span class="text-danger fw-medium">{{ number_format($cuota->pago_pendiente_bs, 2) }}</span>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center text-muted small">
                                                        @if ($fechaPago)
                                                            {{ \Carbon\Carbon::parse($fechaPago)->format('d/m/Y') }}
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($estado == 'si')
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                                <i class="ri-check-line me-1"></i>Pagado
                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                                                <i class="ri-time-line me-1"></i>Pendiente
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-{{ $tipoColor }}-subtle text-{{ $tipoColor }} border border-{{ $tipoColor }}-subtle"
                                                              style="font-size:.68rem;">{{ $tipoCuota }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center gap-1">
                                                            @if ($cuota->pago_pendiente_bs > 0)
                                                                <button class="btn btn-sm btn-success btn-pagar-cuota"
                                                                        data-cuota-id="{{ $cuota->id }}"
                                                                        data-estudiante-id="{{ $estudiante->id }}"
                                                                        title="Pagar esta cuota"
                                                                        style="padding:.2rem .5rem;">
                                                                    <i class="ri-money-dollar-circle-line"></i>
                                                                </button>
                                                            @endif
                                                            @if ($tienePagos)
                                                                <button class="btn btn-sm btn-info btn-ver-recibos"
                                                                        data-cuota-id="{{ $cuota->id }}"
                                                                        data-cuota-nombre="{{ $cuota->nombre }}"
                                                                        title="Ver recibos ({{ $pagosRealizados->count() }})"
                                                                        style="padding:.2rem .5rem;">
                                                                    <i class="ri-receipt-line"></i>
                                                                    <span class="badge bg-white text-info ms-1" style="font-size:.65rem;">{{ $pagosRealizados->count() }}</span>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="background:#f8f9fa;">
                                                <td colspan="2" class="text-end fw-semibold text-muted small py-2">Totales del programa:</td>
                                                <td class="text-center fw-bold py-2">{{ number_format($pagadoInscripcion + $deudaInscripcion, 2) }}</td>
                                                <td class="text-center fw-bold text-success py-2">{{ number_format($pagadoInscripcion, 2) }}</td>
                                                <td class="text-center fw-bold text-danger py-2">{{ number_format($deudaInscripcion, 2) }}</td>
                                                <td colspan="4"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5 px-4">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title bg-light text-secondary rounded-circle">
                                        <i class="ri-money-dollar-circle-line fs-2"></i>
                                    </div>
                                </div>
                                <h5 class="mb-1">No hay cuotas registradas</h5>
                                <p class="text-muted mb-0 small">Este programa no tiene cuotas generadas aún.</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Footer resumen general --}}
    <div class="mt-3 p-3 rounded-3 border bg-light d-flex flex-wrap gap-4">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar-xs">
                <div class="avatar-title bg-primary-subtle text-primary rounded">
                    <i class="ri-money-dollar-circle-line fs-14"></i>
                </div>
            </div>
            <div>
                <div class="fw-semibold lh-1">{{ number_format($totalPagadoGeneral + $totalDeudaGeneral, 2) }} Bs</div>
                <div class="text-muted" style="font-size:.72rem;">Monto Total General</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="avatar-xs">
                <div class="avatar-title bg-success-subtle text-success rounded">
                    <i class="ri-checkbox-circle-line fs-14"></i>
                </div>
            </div>
            <div>
                <div class="fw-semibold lh-1 text-success">{{ number_format($totalPagadoGeneral, 2) }} Bs</div>
                <div class="text-muted" style="font-size:.72rem;">Total Pagado</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="avatar-xs">
                <div class="avatar-title bg-danger-subtle text-danger rounded">
                    <i class="ri-alert-line fs-14"></i>
                </div>
            </div>
            <div>
                <div class="fw-semibold lh-1 text-danger">{{ number_format($totalDeudaGeneral, 2) }} Bs</div>
                <div class="text-muted" style="font-size:.72rem;">Total Deuda</div>
            </div>
        </div>
    </div>

    {{-- Modales incluidos desde detalle.blade.php vía @include --}}
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
