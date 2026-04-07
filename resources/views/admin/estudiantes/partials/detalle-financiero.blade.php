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

    {{-- Summary Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="est-stat-card">
                <div class="est-stat-body">
                    <div class="flex-grow-1">
                        <div class="est-stat-value" style="color:var(--est-primary);">{{ $totalCuotas }}</div>
                        <p class="est-stat-label">Total Cuotas</p>
                    </div>
                    <div class="est-stat-icon" style="background:var(--est-primary-light);color:var(--est-primary);">
                        <i class="ri-stack-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="est-stat-card">
                <div class="est-stat-body">
                    <div class="flex-grow-1">
                        <div class="est-stat-value" style="color:var(--est-success);">{{ $cuotasPagadas }}</div>
                        <p class="est-stat-label">Pagadas</p>
                    </div>
                    <div class="est-stat-icon" style="background:var(--est-success-light);color:var(--est-success);">
                        <i class="ri-checkbox-circle-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="est-stat-card">
                <div class="est-stat-body">
                    <div class="flex-grow-1">
                        <div class="est-stat-value" style="color:var(--est-warning);">{{ $cuotasPendientes }}</div>
                        <p class="est-stat-label">Pendientes</p>
                    </div>
                    <div class="est-stat-icon" style="background:var(--est-warning-light);color:var(--est-warning);">
                        <i class="ri-time-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="est-stat-card">
                <div class="est-stat-body">
                    <div class="flex-grow-1">
                        <div class="est-stat-value" style="color:var(--est-danger);">{{ number_format($totalDeudaGeneral, 0) }} Bs</div>
                        <p class="est-stat-label">Deuda Total</p>
                    </div>
                    <div class="est-stat-icon" style="background:var(--est-danger-light);color:var(--est-danger);">
                        <i class="ri-bill-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="est-stat-card mb-4" style="padding:18px 22px;">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span style="font-size:0.78rem;font-weight:600;color:var(--est-text);">
                <i class="ri-money-dollar-circle-line me-1" style="color:var(--est-primary);"></i>Estado financiero general
            </span>
            <span class="estado-badge-est {{ $colorEstadoFinanciero === 'success' ? 'pagado' : ($colorEstadoFinanciero === 'warning' ? 'pendiente' : 'no-subido') }}">
                {{ number_format($porcentajePagado, 1) }}% pagado
            </span>
        </div>
        <div style="height:8px;border-radius:4px;background:var(--est-border);overflow:hidden;">
            <div class="bg-{{ $colorEstadoFinanciero }}" style="height:100%;width:{{ $porcentajePagado }}%;border-radius:4px;transition:width 0.6s ease;"></div>
        </div>
    </div>

    {{-- Accordion per program --}}
    <div class="est-accordion" id="accordionFinanciero">
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

                $avatarColors = ['#0f766e','#10b981','#0891b2','#f59e0b','#ef4444','#8b5cf6'];
                $avatarColor  = $avatarColors[$index % count($avatarColors)];
                $inicial      = strtoupper(mb_substr($programa->nombre ?? 'P', 0, 1, 'UTF-8'));
            @endphp

            <div class="est-accordion-item color-{{ $colorEstadoInscripcion }}">
                <h2 class="accordion-header" id="financieroHeading{{ $index }}">
                    <button class="est-accordion-btn {{ $index > 0 ? 'collapsed' : '' }}"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#financieroCollapse{{ $index }}"
                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-controls="financieroCollapse{{ $index }}">

                        <div class="est-prog-avatar" style="background:{{ $avatarColor }};">{{ $inicial }}</div>

                        <div class="est-prog-info">
                            <h6 class="est-prog-name">{{ $programa->nombre ?? 'Programa no especificado' }}</h6>
                            <div class="est-prog-meta">
                                <span class="est-prog-meta-item"><i class="ri-calendar-2-line"></i>{{ $oferta->gestion ?? 'N/A' }}</span>
                                <span class="est-prog-meta-item"><i class="ri-file-list-3-line"></i>{{ optional($inscripcion->planesPago)->nombre ?? 'N/A' }}</span>
                                <span class="est-prog-meta-item"><i class="ri-stack-line"></i>{{ $cuotasPagadasInscripcion }}/{{ $cuotasTotales }} cuotas</span>
                            </div>
                        </div>

                        <div class="est-prog-right">
                            <div class="est-prog-badges">
                                <span class="est-prog-badge-paid">{{ number_format($pagadoInscripcion, 2) }} Bs</span>
                                <span class="est-prog-badge-debt">{{ number_format($deudaInscripcion, 2) }} Bs</span>
                            </div>
                            <div class="est-prog-progress">
                                <div class="est-prog-progress-header">
                                    <span class="est-prog-progress-label">Avance</span>
                                    <span class="est-prog-progress-value" style="color:var(--est-{{ $colorEstadoInscripcion }});">{{ number_format($porcentajePagadoInscripcion, 0) }}%</span>
                                </div>
                                <div class="est-prog-progress-bar">
                                    <div class="est-prog-progress-fill bg-{{ $colorEstadoInscripcion }}" style="width:{{ $porcentajePagadoInscripcion }}%"></div>
                                </div>
                            </div>
                        </div>

                    </button>
                </h2>

                <div id="financieroCollapse{{ $index }}"
                     class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                     aria-labelledby="financieroHeading{{ $index }}">
                    <div class="est-accordion-body">

                        @if ($cuotasPendientesInscripcion > 0)
                            <div class="multi-pay-bar">
                                <span class="multi-pay-text">
                                    <i class="ri-list-check-2" style="color:var(--est-primary);"></i>
                                    <strong>{{ $cuotasPendientesInscripcion }}</strong> cuota(s) con saldo pendiente
                                </span>
                                <button type="button"
                                        class="btn-multi-pay btn-pagar-multiple"
                                        data-estudiante-id="{{ $estudiante->id }}">
                                    <i class="ri-stack-line"></i> Pagar Múltiples
                                </button>
                            </div>
                        @endif

                        {{-- Program Summary --}}
                        <div class="prog-summary">
                            <div class="prog-summary-stats">
                                <div class="prog-summary-stat">
                                    <div class="ps-label">Total Programa</div>
                                    <div class="ps-value">{{ number_format($pagadoInscripcion + $deudaInscripcion, 2) }} Bs</div>
                                </div>
                                <div class="prog-summary-stat">
                                    <div class="ps-label">Pagado</div>
                                    <div class="ps-value" style="color:var(--est-success);">{{ number_format($pagadoInscripcion, 2) }} Bs</div>
                                </div>
                                <div class="prog-summary-stat">
                                    <div class="ps-label">Pendiente</div>
                                    <div class="ps-value" style="color:var(--est-danger);">{{ number_format($deudaInscripcion, 2) }} Bs</div>
                                </div>
                            </div>
                        </div>

                        {{-- Concept Breakdown --}}
                        @php
                            $conceptos = [];
                            foreach ($cuotasOrdenadas as $cuota) {
                                $tipo = $cuota->tipo ?? 'Otros';
                                if (!isset($conceptos[$tipo])) {
                                    $conceptos[$tipo] = ['total' => 0, 'pagado' => 0, 'pendiente' => 0, 'cuotas' => 0];
                                }
                                $conceptos[$tipo]['total'] += $cuota->pago_total_bs;
                                $conceptos[$tipo]['pagado'] += ($cuota->pago_total_bs - $cuota->pago_pendiente_bs);
                                $conceptos[$tipo]['pendiente'] += $cuota->pago_pendiente_bs;
                                $conceptos[$tipo]['cuotas']++;
                            }
                            $conceptoIcons = [
                                'Matrícula'     => 'ri-graduation-cap-line',
                                'Colegiatura'   => 'ri-book-open-line',
                                'Certificación' => 'ri-award-line',
                                'Otros'         => 'ri-file-list-line',
                            ];
                        @endphp

                        @if (count($conceptos) > 1)
                            <div class="concept-section">
                                <div class="concept-header">
                                    <div class="concept-title">
                                        <i class="ri-pie-chart-2-line"></i>
                                        Desglose por Concepto
                                    </div>
                                </div>
                                <div class="row g-2">
                                    @foreach ($conceptos as $nombre => $datos)
                                        @php
                                            $cIcon = $conceptoIcons[$nombre] ?? 'ri-file-list-line';
                                            $cPct = $datos['total'] > 0 ? ($datos['pagado'] / $datos['total']) * 100 : 0;
                                            $cColor = match(true) {
                                                $cPct == 100 => 'success',
                                                $cPct >= 75  => 'primary',
                                                $cPct >= 50  => 'warning',
                                                default      => 'danger',
                                            };
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="concept-stat" style="border-left:3px solid var(--est-{{ $cColor }});">
                                                <div class="concept-header" style="margin-bottom:6px;">
                                                    <div class="concept-title" style="font-size:0.82rem;">
                                                        <i class="{{ $cIcon }}"></i>{{ $nombre }}
                                                    </div>
                                                    <div class="concept-badges">
                                                        <span class="concept-badge-paid">{{ number_format($datos['pagado'], 2) }}</span>
                                                        <span class="concept-badge-debt">{{ number_format($datos['pendiente'], 2) }}</span>
                                                    </div>
                                                </div>
                                                <div class="concept-stats" style="grid-template-columns:repeat(3,1fr);">
                                                    <div class="concept-stat" style="background:var(--est-surface);border:none;">
                                                        <div class="concept-stat-label">Total</div>
                                                        <div class="concept-stat-value">{{ number_format($datos['total'], 2) }} Bs</div>
                                                    </div>
                                                    <div class="concept-stat" style="background:var(--est-surface);border:none;">
                                                        <div class="concept-stat-label">Pagado</div>
                                                        <div class="concept-stat-value" style="color:var(--est-success);">{{ number_format($datos['pagado'], 2) }} Bs</div>
                                                    </div>
                                                    <div class="concept-stat" style="background:var(--est-surface);border:none;">
                                                        <div class="concept-stat-label">Pendiente</div>
                                                        <div class="concept-stat-value" style="color:var(--est-danger);">{{ number_format($datos['pendiente'], 2) }} Bs</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Cuotas Table --}}
                        @if ($cuotas->count() > 0)
                            <div class="table-responsive">
                                <table class="cuotas-table">
                                    <thead>
                                        <tr>
                                            <th style="width:5%;text-align:center;">#</th>
                                            <th style="width:26%;">Cuota</th>
                                            <th style="width:12%;text-align:center;">Total</th>
                                            <th style="width:12%;text-align:center;">Pagado</th>
                                            <th style="width:12%;text-align:center;">Pendiente</th>
                                            <th style="width:13%;text-align:center;">Fecha</th>
                                            <th style="width:11%;text-align:center;">Estado</th>
                                            <th style="width:9%;text-align:center;">Acción</th>
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
                                                $pctCuota        = $cuota->pago_total_bs > 0
                                                    ? ($pagado / $cuota->pago_total_bs) * 100 : 0;
                                            @endphp
                                            <tr class="{{ $estado == 'si' ? 'row-pagado' : ($pagado > 0 ? 'row-parcial' : '') }}">
                                                <td style="text-align:center;">
                                                    <span class="cuota-num-badge">{{ $cuota->n_cuota }}</span>
                                                </td>
                                                <td>
                                                    <div class="cuota-name">{{ $cuota->nombre }}</div>
                                                    @if ($tienePagos)
                                                        <div class="cuota-payments">
                                                            <i class="ri-receipt-line me-1"></i>{{ $pagosRealizados->count() }} pago(s)
                                                        </div>
                                                    @endif
                                                    <div class="cuota-progress">
                                                        <div class="cuota-progress-fill {{ $estado == 'si' ? 'bg-success' : 'bg-warning' }}"
                                                             style="width:{{ $pctCuota }}%"></div>
                                                    </div>
                                                </td>
                                                <td style="text-align:center;font-weight:600;">{{ number_format($cuota->pago_total_bs, 2) }}</td>
                                                <td style="text-align:center;font-weight:600;color:var(--est-success);">{{ number_format($pagado, 2) }}</td>
                                                <td style="text-align:center;">
                                                    @if ($cuota->pago_pendiente_bs > 0)
                                                        <span style="font-weight:600;color:var(--est-danger);">{{ number_format($cuota->pago_pendiente_bs, 2) }}</span>
                                                    @else
                                                        <span style="color:var(--est-text-muted);">—</span>
                                                    @endif
                                                </td>
                                                <td style="text-align:center;color:var(--est-text-muted);font-size:0.8rem;">
                                                    @if ($fechaPago)
                                                        {{ \Carbon\Carbon::parse($fechaPago)->format('d/m/Y') }}
                                                    @else
                                                        <span style="color:var(--est-text-muted);">—</span>
                                                    @endif
                                                </td>
                                                <td style="text-align:center;">
                                                    @if ($estado == 'si')
                                                        <span class="estado-badge-cont pagado"><i class="ri-check-line"></i>Pagado</span>
                                                    @else
                                                        <span class="estado-badge-cont pendiente"><i class="ri-time-line"></i>Pendiente</span>
                                                    @endif
                                                </td>
                                                <td style="text-align:center;">
                                                    <div class="d-flex gap-1 justify-content-center">
                                                        @if ($cuota->pago_pendiente_bs > 0)
                                                            <button class="cuota-action-btn pay btn-pagar-cuota"
                                                                    data-cuota-id="{{ $cuota->id }}"
                                                                    data-estudiante-id="{{ $estudiante->id }}"
                                                                    title="Pagar cuota">
                                                                <i class="ri-money-dollar-circle-line"></i>
                                                            </button>
                                                        @endif
                                                        @if ($tienePagos)
                                                            <button class="cuota-action-btn receipts btn-ver-recibos"
                                                                    data-cuota-id="{{ $cuota->id }}"
                                                                    data-cuota-nombre="{{ $cuota->nombre }}"
                                                                    title="Ver recibos ({{ $pagosRealizados->count() }})">
                                                                <i class="ri-receipt-line"></i>
                                                                <span class="receipt-count">{{ $pagosRealizados->count() }}</span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" style="text-align:right;color:var(--est-text-muted);font-size:0.78rem;">Totales del programa:</td>
                                            <td style="text-align:center;font-weight:700;">{{ number_format($pagadoInscripcion + $deudaInscripcion, 2) }}</td>
                                            <td style="text-align:center;font-weight:700;color:var(--est-success);">{{ number_format($pagadoInscripcion, 2) }}</td>
                                            <td style="text-align:center;font-weight:700;color:var(--est-danger);">{{ number_format($deudaInscripcion, 2) }}</td>
                                            <td colspan="3"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="est-empty-state">
                                <div class="est-empty-state-icon"><i class="ri-money-dollar-circle-line"></i></div>
                                <h5>No hay cuotas registradas</h5>
                                <p>Este programa no tiene cuotas generadas aún.</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Footer Summary --}}
    <div class="footer-summary">
        <div class="footer-stat">
            <div class="footer-stat-icon" style="background:var(--est-primary-light);color:var(--est-primary);">
                <i class="ri-money-dollar-circle-line"></i>
            </div>
            <div>
                <div class="footer-stat-value">{{ number_format($totalPagadoGeneral + $totalDeudaGeneral, 2) }} Bs</div>
                <div class="footer-stat-label">Monto Total General</div>
            </div>
        </div>
        <div class="footer-stat">
            <div class="footer-stat-icon" style="background:var(--est-success-light);color:var(--est-success);">
                <i class="ri-checkbox-circle-line"></i>
            </div>
            <div>
                <div class="footer-stat-value" style="color:var(--est-success);">{{ number_format($totalPagadoGeneral, 2) }} Bs</div>
                <div class="footer-stat-label">Total Pagado</div>
            </div>
        </div>
        <div class="footer-stat">
            <div class="footer-stat-icon" style="background:var(--est-danger-light);color:var(--est-danger);">
                <i class="ri-alert-line"></i>
            </div>
            <div>
                <div class="footer-stat-value" style="color:var(--est-danger);">{{ number_format($totalDeudaGeneral, 2) }} Bs</div>
                <div class="footer-stat-label">Total Deuda</div>
            </div>
        </div>
    </div>

@else
    <div class="est-empty-state">
        <div class="est-empty-state-icon"><i class="ri-money-dollar-circle-line"></i></div>
        <h5>No hay información financiera</h5>
        <p>El estudiante no está inscrito en ningún programa.</p>
        <a href="{{ route('admin.inscripciones.registrar') }}" class="btn btn-sm mt-3" style="background:var(--est-primary);color:white;">
            <i class="ri-user-add-line me-1"></i> Realizar Inscripción
        </a>
    </div>
@endif
