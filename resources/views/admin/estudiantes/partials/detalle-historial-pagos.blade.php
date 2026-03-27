@if ($pagosEstudiante && $pagosEstudiante->count() > 0)
    @php
        $totalMonto      = $pagosEstudiante->sum('pago_bs');
        $totalDescuentos = $pagosEstudiante->sum('descuento_bs');
        $totalNeto       = $totalMonto - $totalDescuentos;

        $tipoBadge = [
            'Efectivo'      => ['cls' => 'bg-success',          'icon' => 'ri-money-dollar-circle-line'],
            'Transferencia' => ['cls' => 'bg-info',             'icon' => 'ri-bank-line'],
            'Depósito'      => ['cls' => 'bg-primary',          'icon' => 'ri-building-line'],
            'Tarjeta'       => ['cls' => 'bg-warning text-dark','icon' => 'ri-bank-card-line'],
        ];
    @endphp

    {{-- Stats rápidas --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="rounded-3 border p-3 text-center h-100">
                <div class="fw-bold fs-4 text-primary mb-0">{{ $pagosEstudiante->count() }}</div>
                <div class="text-muted" style="font-size:.72rem;">Total Pagos</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="rounded-3 border p-3 text-center h-100" style="border-color:#198754!important;background:#f0fff5;">
                <div class="fw-bold text-success mb-0" style="font-size:1.1rem;">{{ number_format($totalMonto, 2) }}</div>
                <div class="text-muted" style="font-size:.72rem;">Bs Cobrado</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="rounded-3 border p-3 text-center h-100" style="border-color:#ffc107!important;background:#fffdf0;">
                <div class="fw-bold text-warning mb-0" style="font-size:1.1rem;">{{ number_format($totalDescuentos, 2) }}</div>
                <div class="text-muted" style="font-size:.72rem;">Bs Descuento</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="rounded-3 border p-3 text-center h-100" style="border-color:#0d6efd!important;background:#f0f5ff;">
                <div class="fw-bold text-primary mb-0" style="font-size:1.1rem;">{{ number_format($totalNeto, 2) }}</div>
                <div class="text-muted" style="font-size:.72rem;">Bs Neto</div>
            </div>
        </div>
    </div>

    {{-- Tabla de pagos --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:.84rem;">
                    <thead>
                        <tr style="background:#f8f9fa;">
                            <th width="18%" class="border-0 py-3 px-3 text-muted fw-semibold" style="font-size:.7rem;">RECIBO</th>
                            <th width="16%" class="border-0 py-3 text-muted fw-semibold" style="font-size:.7rem;">FECHA</th>
                            <th width="15%" class="text-center border-0 py-3 text-muted fw-semibold" style="font-size:.7rem;">TIPO</th>
                            <th width="14%" class="text-center border-0 py-3 text-muted fw-semibold" style="font-size:.7rem;">MONTO</th>
                            <th width="13%" class="text-center border-0 py-3 text-muted fw-semibold" style="font-size:.7rem;">DESCUENTO</th>
                            <th width="13%" class="text-center border-0 py-3 text-muted fw-semibold" style="font-size:.7rem;">NETO</th>
                            <th width="11%" class="text-center border-0 py-3 text-muted fw-semibold" style="font-size:.7rem;">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pagosEstudiante as $pago)
                            @php
                                $totalPagado = $pago->pago_bs ?? 0;
                                $descuento   = $pago->descuento_bs ?? 0;
                                $montoNeto   = $totalPagado - $descuento;
                                $tb = $tipoBadge[$pago->tipo_pago] ?? ['cls' => 'bg-secondary', 'icon' => 'ri-money-line'];
                            @endphp
                            <tr>
                                <td class="px-3">
                                    <div class="fw-semibold text-primary" style="font-size:.82rem;">{{ $pago->recibo ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    @if ($pago->fecha_pago)
                                        <div class="fw-medium">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</div>
                                        <div class="text-muted" style="font-size:.72rem;">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('H:i') }}</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $tb['cls'] }} rounded-pill px-2">
                                        <i class="{{ $tb['icon'] }} me-1"></i>{{ $pago->tipo_pago ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-success">{{ number_format($totalPagado, 2) }}</span>
                                </td>
                                <td class="text-center">
                                    @if ($descuento > 0)
                                        <span class="text-warning fw-medium">-{{ number_format($descuento, 2) }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="fw-semibold text-primary">{{ number_format($montoNeto, 2) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn btn-sm btn-outline-info btn-ver-detalle-pago"
                                                data-pago-id="{{ $pago->id }}"
                                                title="Ver detalle"
                                                style="padding:.2rem .5rem;">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <a href="{{ route('admin.estudiantes.descargar-recibo', $pago->id) }}"
                                           class="btn btn-sm btn-outline-success"
                                           target="_blank"
                                           title="Descargar PDF"
                                           style="padding:.2rem .5rem;">
                                            <i class="ri-download-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#f8f9fa;">
                            <td colspan="3" class="text-end fw-semibold text-muted small py-2 px-3">Totales:</td>
                            <td class="text-center fw-bold text-success py-2">{{ number_format($totalMonto, 2) }}</td>
                            <td class="text-center fw-bold text-warning py-2">-{{ number_format($totalDescuentos, 2) }}</td>
                            <td class="text-center fw-bold text-primary py-2">{{ number_format($totalNeto, 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

@else
    <div class="text-center py-5">
        <div class="avatar-lg mx-auto mb-3">
            <div class="avatar-title bg-light text-secondary rounded-circle">
                <i class="ri-history-line fs-2"></i>
            </div>
        </div>
        <h5 class="mb-1">No hay pagos registrados</h5>
        <p class="text-muted mb-0 small">El estudiante aún no ha realizado ningún pago.</p>
    </div>
@endif

{{-- Modal detalle de pago --}}
<div class="modal fade" id="modalDetallePago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom bg-primary text-white py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-white bg-opacity-25 text-white rounded-2">
                            <i class="ri-receipt-line fs-18"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-semibold">Detalle del Recibo</h5>
                        <div class="opacity-75" style="font-size:.75rem;">Información completa del pago</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="contenidoDetallePago">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2 text-muted small">Cargando...</p>
                </div>
            </div>
            <div class="modal-footer border-top bg-light gap-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cerrar
                </button>
                <a href="#" id="btnDescargarRecibo" class="btn btn-success" target="_blank">
                    <i class="ri-download-line me-1"></i>Descargar PDF
                </a>
                <button type="button" class="btn btn-outline-secondary" id="btnImprimirDetalle">
                    <i class="ri-printer-line me-1"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>
