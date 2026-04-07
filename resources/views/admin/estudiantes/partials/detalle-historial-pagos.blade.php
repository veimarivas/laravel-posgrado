@if ($pagosEstudiante && $pagosEstudiante->count() > 0)
    @php
        $totalMonto      = $pagosEstudiante->sum('pago_bs');
        $totalDescuentos = $pagosEstudiante->sum('descuento_bs');
        $totalNeto       = $totalMonto - $totalDescuentos;
    @endphp

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="est-stat-card">
                <div class="est-stat-body">
                    <div class="flex-grow-1">
                        <div class="est-stat-value" style="color:var(--est-primary);">{{ $pagosEstudiante->count() }}</div>
                        <p class="est-stat-label">Total Pagos</p>
                    </div>
                    <div class="est-stat-icon" style="background:var(--est-primary-light);color:var(--est-primary);">
                        <i class="ri-receipt-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="est-stat-card">
                <div class="est-stat-body">
                    <div class="flex-grow-1">
                        <div class="est-stat-value" style="color:var(--est-success);">{{ number_format($totalMonto, 2) }} Bs</div>
                        <p class="est-stat-label">Bs Cobrado</p>
                    </div>
                    <div class="est-stat-icon" style="background:var(--est-success-light);color:var(--est-success);">
                        <i class="ri-money-dollar-circle-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="est-stat-card">
                <div class="est-stat-body">
                    <div class="flex-grow-1">
                        <div class="est-stat-value" style="color:var(--est-warning);">{{ number_format($totalDescuentos, 2) }} Bs</div>
                        <p class="est-stat-label">Bs Descuento</p>
                    </div>
                    <div class="est-stat-icon" style="background:var(--est-warning-light);color:var(--est-warning);">
                        <i class="ri-discount-percent-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="est-stat-card">
                <div class="est-stat-body">
                    <div class="flex-grow-1">
                        <div class="est-stat-value" style="color:var(--est-info);">{{ number_format($totalNeto, 2) }} Bs</div>
                        <p class="est-stat-label">Bs Neto</p>
                    </div>
                    <div class="est-stat-icon" style="background:var(--est-info-light);color:var(--est-info);">
                        <i class="ri-wallet-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="est-stat-card" style="border-radius:var(--radius-lg);overflow:hidden;">
        <div class="tabla-card-header">
            <h5>
                <i class="ri-history-line"></i>
                Historial de Pagos
                <span class="badge-recibos-count">{{ $pagosEstudiante->count() }} registro(s)</span>
            </h5>
        </div>
        <div class="table-responsive">
            <table class="est-table">
                <thead>
                    <tr>
                        <th style="width:18%;">Recibo</th>
                        <th style="width:14%;">Fecha</th>
                        <th style="width:13%;text-align:center;">Tipo</th>
                        <th style="width:13%;text-align:center;">Monto</th>
                        <th style="width:12%;text-align:center;">Descuento</th>
                        <th style="width:12%;text-align:center;">Neto</th>
                        <th style="width:18%;text-align:center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pagosEstudiante as $pago)
                        @php
                            $totalPagado = $pago->pago_bs ?? 0;
                            $descuento   = $pago->descuento_bs ?? 0;
                            $montoNeto   = $totalPagado - $descuento;
                            $tipoCls = match($pago->tipo_pago ?? '') {
                                'Efectivo'      => 'efectivo',
                                'Transferencia' => 'transferencia',
                                'Depósito'      => 'deposito',
                                'Tarjeta'       => 'tarjeta',
                                default         => 'otro',
                            };
                        @endphp
                        <tr>
                            <td>
                                <div class="recibo-cell">
                                    <span class="recibo-number">{{ $pago->recibo ?? 'N/A' }}</span>
                                    <span class="tipo-badge {{ $tipoCls }}">{{ $pago->tipo_pago ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="date-cell">
                                    <div class="date-main">{{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : '—' }}</div>
                                    <div class="date-time">{{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('H:i') : '' }}</div>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <span class="estado-badge-est {{ $tipoCls }}">{{ $pago->tipo_pago ?? 'N/A' }}</span>
                            </td>
                            <td style="text-align:center;font-weight:600;color:var(--est-success);">{{ number_format($totalPagado, 2) }}</td>
                            <td style="text-align:center;">
                                @if ($descuento > 0)
                                    <span style="font-weight:600;color:var(--est-warning);">-{{ number_format($descuento, 2) }}</span>
                                @else
                                    <span style="color:var(--est-text-muted);">—</span>
                                @endif
                            </td>
                            <td style="text-align:center;font-weight:600;color:var(--est-primary);">{{ number_format($montoNeto, 2) }}</td>
                            <td style="text-align:center;">
                                <div class="d-flex gap-1 justify-content-center">
                                    <button class="est-action-btn view btn-ver-detalle-pago"
                                            data-pago-id="{{ $pago->id }}"
                                            title="Ver detalle">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <a href="{{ route('admin.estudiantes.descargar-recibo', $pago->id) }}"
                                       class="est-action-btn download"
                                       target="_blank"
                                       title="Descargar PDF">
                                        <i class="ri-download-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right;color:var(--est-text-muted);font-size:0.78rem;">Totales:</td>
                        <td style="text-align:center;font-weight:700;color:var(--est-success);">{{ number_format($totalMonto, 2) }}</td>
                        <td style="text-align:center;font-weight:700;color:var(--est-warning);">-{{ number_format($totalDescuentos, 2) }}</td>
                        <td style="text-align:center;font-weight:700;color:var(--est-primary);">{{ number_format($totalNeto, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

@else
    <div class="est-empty-state">
        <div class="est-empty-state-icon"><i class="ri-history-line"></i></div>
        <h5>No hay pagos registrados</h5>
        <p>El estudiante aún no ha realizado ningún pago.</p>
    </div>
@endif

{{-- Modal detalle de pago --}}
<div class="modal fade modal-est" id="modalDetallePago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-receipt-line me-2"></i>Detalle del Recibo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="contenidoDetallePago">
                <div class="text-center py-5">
                    <div class="spinner-border" role="status" style="color:var(--est-primary);"></div>
                    <p class="mt-2" style="color:var(--est-text-muted);">Cargando...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cerrar
                </button>
                <a href="#" id="btnDescargarRecibo" class="btn btn-sm" target="_blank"
                   style="background:var(--est-success);color:white;">
                    <i class="ri-download-line me-1"></i>Descargar PDF
                </a>
                <button type="button" class="btn btn-sm" id="btnImprimirDetalle"
                        style="background:var(--est-primary);color:white;">
                    <i class="ri-printer-line me-1"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>
