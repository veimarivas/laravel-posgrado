@if (isset($estadisticas))
    <div class="col-xl-2 col-md-4 col-6" style="animation: recFadeIn 0.4s ease-out 0s both;">
        <div class="stat-card stat-total">
            <div class="stat-icon icon-total">
                <i class="ri-file-text-line"></i>
            </div>
            <div>
                <div class="stat-label">Total Recibos</div>
                <div class="stat-value val-primary">{{ $estadisticas['total_recibos'] }}</div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6" style="animation: recFadeIn 0.4s ease-out 0.06s both;">
        <div class="stat-card stat-monto">
            <div class="stat-icon icon-monto">
                <i class="ri-money-dollar-circle-line"></i>
            </div>
            <div>
                <div class="stat-label">Monto Total</div>
                <div class="stat-value">{{ number_format($estadisticas['total_monto'], 2) }} <small
                        class="fw-normal" style="font-size:.7rem;opacity:.7;">Bs</small></div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6" style="animation: recFadeIn 0.4s ease-out 0.12s both;">
        <div class="stat-card stat-efectivo">
            <div class="stat-icon icon-efectivo">
                <i class="ri-money-dollar-circle-line"></i>
            </div>
            <div>
                <div class="stat-label">Efectivo</div>
                <div class="stat-value val-success">{{ number_format($estadisticas['total_efectivo'], 2) }} <small
                        class="fw-normal" style="font-size:.7rem;opacity:.7;">Bs</small></div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6" style="animation: recFadeIn 0.4s ease-out 0.18s both;">
        <div class="stat-card stat-transferencia">
            <div class="stat-icon icon-transferencia">
                <i class="ri-bank-transfer-line"></i>
            </div>
            <div>
                <div class="stat-label">Transferencia</div>
                <div class="stat-value val-info">{{ number_format($estadisticas['total_transferencia'], 2) }} <small
                        class="fw-normal" style="font-size:.7rem;opacity:.7;">Bs</small></div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6" style="animation: recFadeIn 0.4s ease-out 0.24s both;">
        <div class="stat-card stat-deposito">
            <div class="stat-icon icon-deposito">
                <i class="ri-bank-card-2-line"></i>
            </div>
            <div>
                <div class="stat-label">Depósito</div>
                <div class="stat-value val-primary">{{ number_format($estadisticas['total_deposito'] ?? 0, 2) }} <small
                        class="fw-normal" style="font-size:.7rem;opacity:.7;">Bs</small></div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6" style="animation: recFadeIn 0.4s ease-out 0.30s both;">
        <div class="stat-card stat-tarjeta">
            <div class="stat-icon icon-tarjeta">
                <i class="ri-bank-card-line"></i>
            </div>
            <div>
                <div class="stat-label">Tarjeta</div>
                <div class="stat-value val-warning">
                    {{ number_format($estadisticas['total_tarjeta'] ?? 0, 2) }} <small class="fw-normal" style="font-size:.7rem;opacity:.7;">Bs</small>
                </div>
            </div>
        </div>
    </div>
@endif
