@if (isset($estadisticas))
    <div class="col-xl-4 col-md-4 col-6">
        <div class="stat-recibo">
            <div class="stat-icon bg-primary-subtle text-primary">
                <i class="ri-file-text-line"></i>
            </div>
            <div>
                <div class="stat-label">Total Recibos</div>
                <div class="stat-value text-primary">{{ $estadisticas['total_recibos'] }}</div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 col-6">
        <div class="stat-recibo">
            <div class="stat-icon bg-dark-subtle text-dark">
                <i class="ri-money-dollar-circle-line"></i>
            </div>
            <div>
                <div class="stat-label">Monto Total</div>
                <div class="stat-value">{{ number_format($estadisticas['total_monto'], 2) }} <small
                        class="fw-normal">Bs</small></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 col-6">
        <div class="stat-recibo">
            <div class="stat-icon bg-success-subtle text-success">
                <i class="ri-money-dollar-circle-line"></i>
            </div>
            <div>
                <div class="stat-label">Efectivo</div>
                <div class="stat-value text-success">{{ number_format($estadisticas['total_efectivo'], 2) }} <small
                        class="fw-normal">Bs</small></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 col-6">
        <div class="stat-recibo">
            <div class="stat-icon bg-info-subtle text-info">
                <i class="ri-bank-transfer-line"></i>
            </div>
            <div>
                <div class="stat-label">Transferencia</div>
                <div class="stat-value text-info">{{ number_format($estadisticas['total_transferencia'], 2) }} <small
                        class="fw-normal">Bs</small></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 col-6">
        <div class="stat-recibo">
            <div class="stat-icon bg-primary-subtle text-primary">
                <i class="ri-bank-card-2-line"></i>
            </div>
            <div>
                <div class="stat-label">Depósito</div>
                <div class="stat-value text-primary">{{ number_format($estadisticas['total_deposito'] ?? 0, 2) }} <small
                        class="fw-normal">Bs</small></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 col-6">
        <div class="stat-recibo">
            <div class="stat-icon bg-warning-subtle text-warning">
                <i class="ri-bank-card-line"></i>
            </div>
            <div>
                <div class="stat-label">Tarjeta</div>
                <div class="stat-value" style="color:#5a3e00;">
                    {{ number_format($estadisticas['total_tarjeta'] ?? 0, 2) }} <small class="fw-normal">Bs</small>
                </div>
            </div>
        </div>
    </div>
@endif
