@if ($cuota->pagos_cuotas->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Recibo</th>
                    <th>Monto (Bs)</th>
                    <th>Fecha</th>
                    <th>Tipo de Pago</th>
                    <th>Descuento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cuota->pagos_cuotas as $pagoCuota)
                    @php
                        $pago = $pagoCuota->pago;
                        $totalDetalles = $pago->detalles->sum('pago_bs');
                    @endphp
                    <tr>
                        <td>
                            <span class="badge bg-primary">{{ $pago->recibo ?? 'N/A' }}</span>
                        </td>
                        <td class="text-success fw-bold">
                            {{ number_format($pagoCuota->pago_bs, 2) }}
                        </td>
                        <td>
                            @if ($pago->fecha_pago)
                                {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $pago->tipo_pago ?? 'N/A' }}</span>
                        </td>
                        <td>
                            @if ($pago->descuento_bs > 0)
                                <span class="text-warning">-{{ number_format($pago->descuento_bs, 2) }}</span>
                            @else
                                <span class="text-muted">Sin descuento</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.estudiantes.descargar-recibo', $pago->id) }}"
                                class="btn btn-sm btn-primary" target="_blank">
                                <i class="ri-download-line me-1"></i> PDF
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info">
        No hay recibos registrados para esta cuota.
    </div>
@endif
