@if ($pagosEstudiante && $pagosEstudiante->count() > 0)
    <div class="card border">
        <div class="card-header border-bottom bg-light">
            <h5 class="card-title mb-0 fs-16">Historial de Pagos</h5>
            <div class="text-muted small">Total de pagos: {{ $pagosEstudiante->count() }}</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="15%">Recibo</th>
                            <th width="15%">Fecha</th>
                            <th width="20%">Monto Total</th>
                            <th width="15%">Tipo de Pago</th>
                            <th width="15%">Descuento</th>
                            <th width="10%">Estado</th>
                            <th width="10%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pagosEstudiante as $pago)
                            @php
                                $totalPagado = $pago->pago_bs ?? 0;
                                $descuento = $pago->descuento_bs ?? 0;
                                $montoNeto = $totalPagado - $descuento;
                            @endphp
                            <tr>
                                <td>
                                    <span class="badge bg-primary">{{ $pago->recibo ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if ($pago->fecha_pago)
                                        {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-success">{{ number_format($totalPagado, 2) }} Bs</div>
                                    @if ($descuento > 0)
                                        <small class="text-muted">Neto: {{ number_format($montoNeto, 2) }} Bs</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $pago->tipo_pago ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if ($descuento > 0)
                                        <span class="text-warning fw-bold">-{{ number_format($descuento, 2) }} Bs</span>
                                    @else
                                        <span class="text-muted">Sin descuento</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="ri-check-line me-1"></i> Completado
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info btn-ver-detalle-pago"
                                        data-pago-id="{{ $pago->id }}">
                                        <i class="ri-eye-line me-1"></i> Ver
                                    </button>
                                    <a href="{{ route('admin.estudiantes.descargar-recibo', $pago->id) }}"
                                        class="btn btn-sm btn-primary mt-1" target="_blank">
                                        <i class="ri-download-line me-1"></i> PDF
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="2" class="text-end fw-bold">Totales:</td>
                            <td class="fw-bold text-success">{{ number_format($pagosEstudiante->sum('pago_bs'), 2) }} Bs
                            </td>
                            <td colspan="2" class="text-end fw-bold">Total Descuentos:</td>
                            <td class="fw-bold text-warning">
                                -{{ number_format($pagosEstudiante->sum('descuento_bs'), 2) }} Bs</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light">
            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted">
                        <i class="ri-information-line me-1"></i>
                        Mostrando {{ $pagosEstudiante->count() }} pagos registrados
                    </small>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        Monto total neto recibido:
                        <strong class="text-success">
                            {{ number_format($pagosEstudiante->sum('pago_bs') - $pagosEstudiante->sum('descuento_bs'), 2) }}
                            Bs
                        </strong>
                    </small>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="card border">
        <div class="card-body text-center py-5">
            <div class="avatar-lg mx-auto mb-3">
                <div class="avatar-title bg-light text-secondary rounded-circle">
                    <i class="ri-history-line fs-2"></i>
                </div>
            </div>
            <h5 class="mb-2">No hay pagos registrados</h5>
            <p class="text-muted mb-0">El estudiante aún no ha realizado ningún pago.</p>
        </div>
    </div>
@endif

<!-- Modal para ver detalle de pago -->
<div class="modal fade" id="modalDetallePago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle del Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoDetallePago">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnImprimirDetalle">
                    <i class="ri-printer-line me-1"></i> Imprimir
                </button>
            </div>
        </div>
    </div>
</div>
