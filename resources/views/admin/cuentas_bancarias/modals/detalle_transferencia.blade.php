@if (isset($transferencia))
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <h6>Información de Origen</h6>
                <p><strong>Cuenta:</strong> {{ $transferencia->cuentaOrigen->numero_cuenta ?? 'N/A' }}</p>
                <p><strong>Banco:</strong> {{ $transferencia->cuentaOrigen->banco->nombre ?? 'N/A' }}</p>
                <p><strong>Sucursal:</strong> {{ $transferencia->cuentaOrigen->sucursal->nombre ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <h6>Información de Destino</h6>
                <p><strong>Cuenta:</strong> {{ $transferencia->cuentaDestino->numero_cuenta ?? 'N/A' }}</p>
                <p><strong>Banco:</strong> {{ $transferencia->cuentaDestino->banco->nombre ?? 'N/A' }}</p>
                <p><strong>Sucursal:</strong> {{ $transferencia->cuentaDestino->sucursal->nombre ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <h6>Detalles de la Transferencia</h6>
                <p><strong>Monto:</strong> {{ number_format($transferencia->monto, 2) }} {{ $transferencia->moneda }}
                </p>
                <p><strong>Tipo:</strong> {{ $transferencia->tipo_transferencia_formateado }}</p>
                <p><strong>Fecha:</strong> {{ $transferencia->fecha_transferencia->format('d/m/Y') }}</p>
                <p><strong>Estado:</strong> <span
                        class="badge bg-{{ $transferencia->estado == 'procesada' ? 'success' : 'warning' }}">{{ $transferencia->estado }}</span>
                </p>
            </div>
            <div class="col-md-6">
                <h6>Información Adicional</h6>
                <p><strong>Descripción:</strong> {{ $transferencia->descripcion ?? 'Sin descripción' }}</p>
                <p><strong>Usuario:</strong> {{ $transferencia->usuario->name ?? 'Sistema' }}</p>
                <p><strong>Fecha de creación:</strong> {{ $transferencia->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        @if ($transferencia->pago)
            <div class="row mt-3">
                <div class="col-12">
                    <h6>Pago Asociado</h6>
                    <p><strong>Recibo:</strong> {{ $transferencia->pago->recibo ?? 'N/A' }}</p>
                    <p><strong>Motivo de corrección:</strong> {{ $transferencia->motivo_correccion ?? 'N/A' }}</p>
                </div>
            </div>
        @endif
    </div>
@else
    <div class="alert alert-warning">
        No se encontró información de la transferencia
    </div>
@endif
