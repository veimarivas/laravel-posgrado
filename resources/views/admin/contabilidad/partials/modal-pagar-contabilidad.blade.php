<style>
    /* Cuotas Panel */
    .pc-cuotas-panel {
        background: var(--cont-surface);
        border-radius: var(--radius-md);
        border: 1px solid var(--cont-border);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .pc-cuotas-panel-header {
        padding: 14px 18px;
        background: white;
        border-bottom: 1px solid var(--cont-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }

    .pc-cuotas-panel-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.88rem;
        color: var(--cont-text);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pc-cuotas-panel-title i { color: var(--cont-primary); }

    .pc-cuotas-panel-actions {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pc-btn-sm {
        background: white;
        color: var(--cont-text-muted);
        border: 1px solid var(--cont-border);
        padding: 5px 10px;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.72rem;
        transition: all 0.2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .pc-btn-sm:hover {
        background: var(--cont-primary-light);
        color: var(--cont-primary);
        border-color: var(--cont-primary);
    }

    .pc-selected-badge {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 0.82rem;
        padding: 4px 12px;
        border-radius: var(--radius-sm);
        background: var(--cont-primary-light);
        color: var(--cont-primary);
        border: 1px solid rgba(15, 118, 110, 0.15);
    }

    /* Cuotas List */
    .pc-cuotas-list {
        flex: 1;
        overflow-y: auto;
        padding: 14px;
    }

    .pc-cuotas-list::-webkit-scrollbar { width: 5px; }
    .pc-cuotas-list::-webkit-scrollbar-track { background: transparent; }
    .pc-cuotas-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

    /* Program Group */
    .pc-prog-group {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--cont-border);
        overflow: hidden;
        margin-bottom: 10px;
    }

    .pc-prog-group:last-child { margin-bottom: 0; }

    .pc-prog-group-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        background: var(--cont-surface);
        border-bottom: 1px solid var(--cont-border);
    }

    .pc-prog-group-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.78rem;
        color: var(--cont-text);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pc-prog-group-title i { color: var(--cont-accent); font-size: 0.9rem; }

    .pc-prog-group-total {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 0.75rem;
        color: var(--cont-danger);
        background: var(--cont-danger-light);
        padding: 2px 8px;
        border-radius: 50px;
    }

    /* Cuota Item */
    .pc-cuota-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-bottom: 1px solid var(--cont-border);
        cursor: pointer;
        transition: all 0.2s ease;
        background: white;
    }

    .pc-cuota-item:last-child { border-bottom: none; }

    .pc-cuota-item:hover {
        background: var(--cont-primary-light);
    }

    .pc-cuota-item.selected {
        background: var(--cont-primary-light);
    }

    .pc-cuota-item.selected .pc-cuota-check {
        background: var(--cont-primary);
        border-color: var(--cont-primary);
        box-shadow: 0 0 0 2px rgba(15, 118, 110, 0.15);
    }

    .pc-cuota-item.selected .pc-cuota-check i { opacity: 1; }

    .pc-cuota-check {
        width: 20px;
        height: 20px;
        border-radius: 5px;
        border: 2px solid var(--cont-border);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s ease;
        background: white;
    }

    .pc-cuota-check i {
        font-size: 0.7rem;
        color: white;
        opacity: 0;
        transition: opacity 0.15s ease;
    }

    .pc-cuota-item input[type=checkbox] {
        position: absolute;
        opacity: 0;
        width: 1px;
        height: 1px;
        overflow: hidden;
        clip: rect(0,0,0,0);
    }

    .pc-cuota-info { flex: 1; min-width: 0; }

    .pc-cuota-name {
        font-weight: 600;
        font-size: 0.82rem;
        color: var(--cont-text);
        margin-bottom: 3px;
    }

    .pc-cuota-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.72rem;
    }

    .pc-cuota-pending { color: var(--cont-danger); font-weight: 600; }

    .pc-cuota-tipo {
        padding: 1px 8px;
        border-radius: 50px;
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .pc-cuota-tipo.tipo-matricula { background: var(--cont-primary-light); color: var(--cont-primary); }
    .pc-cuota-tipo.tipo-colegiatura { background: var(--cont-success-light); color: var(--cont-success); }
    .pc-cuota-tipo.tipo-certificacion { background: var(--cont-warning-light); color: var(--cont-warning); }
    .pc-cuota-tipo.tipo-otros { background: #f1f5f9; color: var(--cont-text-muted); }

    .pc-cuota-amount {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 0.88rem;
        color: var(--cont-text);
        flex-shrink: 0;
        text-align: right;
    }

    .pc-empty-state {
        text-align: center;
        padding: 40px 16px;
        color: var(--cont-text-muted);
    }

    .pc-empty-state i { font-size: 2rem; color: #cbd5e1; margin-bottom: 8px; }

    /* Form Panel */
    .pc-form-panel {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--cont-border);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .pc-form-panel-header {
        padding: 12px 16px;
        background: var(--cont-surface);
        border-bottom: 1px solid var(--cont-border);
    }

    .pc-form-panel-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--cont-text);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pc-form-panel-title i { color: var(--cont-primary); }

    .pc-form-panel-body {
        padding: 16px;
        flex: 1;
        overflow-y: auto;
    }

    .pc-form-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--cont-text);
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .pc-form-control {
        border-radius: var(--radius-sm);
        border: 1px solid var(--cont-border);
        padding: 8px 12px;
        font-size: 0.85rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--cont-surface);
        transition: all 0.2s ease;
    }

    .pc-form-control:focus {
        border-color: var(--cont-primary);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        background: white;
    }

    /* Resumen */
    .pc-resumen-card {
        background: linear-gradient(135deg, var(--cont-primary) 0%, var(--cont-primary-dark) 100%);
        border-radius: var(--radius-md);
        padding: 16px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .pc-resumen-card::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -5%;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }

    .pc-res-label {
        font-size: 0.62rem;
        opacity: 0.75;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 2px;
        position: relative;
        z-index: 1;
    }

    .pc-res-value {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 0.95rem;
        position: relative;
        z-index: 1;
    }

    .pc-res-divider {
        width: 1px;
        height: 36px;
        background: rgba(255,255,255,0.2);
    }

    .pc-cobrador-alert {
        border-radius: var(--radius-sm);
        padding: 10px 14px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
    }

    .pc-cobrador-name { font-weight: 600; }
    .pc-cobrador-cargo { font-size: 0.75rem; opacity: 0.8; }
</style>

<div class="modal fade modal-cont" id="modalPagarContabilidad" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-stack-line me-2"></i>Pago Múltiple de Cuotas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="text-muted" style="font-size:0.85rem;margin-top:-4px;margin-bottom:16px;">
                    Selecciona las cuotas que deseas pagar en un solo recibo
                </p>

                {{-- Cobrador --}}
                @if (isset($cobrador) && $cobrador)
                    <div class="pc-cobrador-alert" style="background:var(--cont-success-light);color:var(--cont-success);">
                        <i class="ri-user-star-line" style="font-size:1.1rem;"></i>
                        <div>
                            <div class="pc-cobrador-name">{{ $cobrador->nombres }} {{ $cobrador->apellido_paterno }} {{ $cobrador->apellido_materno ?? '' }}</div>
                            <div class="pc-cobrador-cargo">Cobrador — {{ $cobrador->cargo }}</div>
                        </div>
                    </div>
                @else
                    <div class="pc-cobrador-alert" style="background:var(--cont-warning-light);color:var(--cont-warning);">
                        <i class="ri-alert-line" style="font-size:1.1rem;"></i>
                        <div>No se pudo identificar al cobrador. Verifique su cargo vigente.</div>
                    </div>
                @endif

                <div class="row g-3">
                    {{-- Panel de cuotas --}}
                    <div class="col-md-6">
                        <div class="pc-cuotas-panel">
                            <div class="pc-cuotas-panel-header">
                                <span class="pc-cuotas-panel-title">
                                    <i class="ri-file-list-3-line"></i>Cuotas Pendientes
                                </span>
                                <div class="pc-cuotas-panel-actions">
                                    <button type="button" class="pc-btn-sm" id="btnSeleccionarTodas">
                                        <i class="ri-checkbox-line"></i>Todas
                                    </button>
                                    <button type="button" class="pc-btn-sm" id="btnDeseleccionarTodas">
                                        <i class="ri-indeterminate-circle-line"></i>Ninguna
                                    </button>
                                    <span class="pc-selected-badge" id="totalSelBadge">0.00 Bs</span>
                                </div>
                            </div>
                            <div class="pc-cuotas-list" id="listaCuotasPago">
                                <div class="pc-empty-state">
                                    <div class="spinner-border" role="status" style="color:var(--cont-primary);width:24px;height:24px;"></div>
                                    <p class="mt-2 mb-0">Cargando cuotas pendientes...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Panel del formulario --}}
                    <div class="col-md-6">
                        <div class="pc-form-panel">
                            <div class="pc-form-panel-header">
                                <span class="pc-form-panel-title">
                                    <i class="ri-money-dollar-circle-line"></i>Datos del Pago
                                </span>
                            </div>
                            <div class="pc-form-panel-body">
                                <form id="formPagarContabilidad">
                                    @csrf
                                    <input type="hidden" id="pc_estudiante_id" name="estudiante_id" value="{{ $estudiante->id }}">

                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="pc-form-label">Monto a Pagar (Bs)</label>
                                            <input type="number" step="0.01" class="pc-form-control w-100"
                                                id="pc_monto_pago" name="monto_pago" required placeholder="0.00">
                                            <div class="form-text" style="font-size:0.72rem;">
                                                Pendiente: <span id="pc_pendiente_total" class="fw-bold" style="color:var(--cont-danger);">0.00</span> Bs
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="pc-form-label">Descuento (Bs)</label>
                                            <input type="number" step="0.01" class="pc-form-control w-100"
                                                id="pc_descuento" name="descuento" value="0" placeholder="0.00">
                                        </div>
                                        <div class="col-6">
                                            <label class="pc-form-label">Tipo de Pago</label>
                                            <select class="form-select form-select-sm pc-form-control" id="pc_tipo_pago" name="tipo_pago" required onchange="togglePcFields()">
                                                <option value="">Seleccionar...</option>
                                                <option value="Efectivo">Efectivo</option>
                                                <option value="Transferencia">Transferencia</option>
                                                <option value="Depósito">Depósito</option>
                                                <option value="Tarjeta">Tarjeta</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label class="pc-form-label">Fecha de Pago</label>
                                            <input type="date" class="pc-form-control w-100"
                                                id="pc_fecha_pago" name="fecha_pago" value="{{ date('Y-m-d') }}" required>
                                        </div>

                                        <div class="col-12" id="pc_campo_caja" style="display:none;">
                                            <label class="pc-form-label">Caja</label>
                                            <select class="form-select form-select-sm pc-form-control" id="pc_caja_id" name="caja_id">
                                                <option value="">Seleccionar caja...</option>
                                                @foreach (\App\Models\Caja::where('activa', true)->with('sucursal')->get() as $caja)
                                                    <option value="{{ $caja->id }}">
                                                        {{ $caja->nombre }} — {{ $caja->sucursal->nombre ?? 'Sin sucursal' }}
                                                        ({{ number_format($caja->saldo_actual, 2) }} Bs)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12" id="pc_campo_cuenta" style="display:none;">
                                            <label class="pc-form-label">Cuenta Bancaria</label>
                                            <select class="form-select form-select-sm pc-form-control" id="pc_cuenta_id" name="cuenta_bancaria_id">
                                                <option value="">Seleccionar cuenta...</option>
                                                @foreach (\App\Models\CuentasBancarias::where('activa', true)->with('banco')->get() as $cuenta)
                                                    <option value="{{ $cuenta->id }}">
                                                        {{ $cuenta->banco->nombre ?? 'Sin banco' }} — {{ $cuenta->numero_cuenta }}
                                                        ({{ $cuenta->moneda }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12" id="pc_campo_comprobante" style="display:none;">
                                            <label class="pc-form-label">N° Comprobante</label>
                                            <input type="text" class="pc-form-control w-100"
                                                id="pc_n_comprobante" name="n_comprobante" placeholder="Ej: TRF-0012345">
                                        </div>

                                        <div class="col-12">
                                            <label class="pc-form-label">Observaciones</label>
                                            <textarea class="pc-form-control w-100" id="pc_observaciones"
                                                name="observaciones" rows="2" placeholder="Opcional..."></textarea>
                                        </div>
                                    </div>

                                    {{-- Resumen --}}
                                    <div class="pc-resumen-card mt-3">
                                        <div class="d-flex align-items-center justify-content-around">
                                            <div class="text-center">
                                                <div class="pc-res-label">Monto</div>
                                                <div class="pc-res-value" id="pc_res_monto">0.00 Bs</div>
                                            </div>
                                            <div class="pc-res-divider"></div>
                                            <div class="text-center">
                                                <div class="pc-res-label">Descuento</div>
                                                <div class="pc-res-value" id="pc_res_desc">0.00 Bs</div>
                                            </div>
                                            <div class="pc-res-divider"></div>
                                            <div class="text-center">
                                                <div class="pc-res-label">Total</div>
                                                <div class="pc-res-value" id="pc_res_total">0.00 Bs</div>
                                            </div>
                                            <div class="pc-res-divider"></div>
                                            <div class="text-center">
                                                <div class="pc-res-label">Cuotas</div>
                                                <div class="pc-res-value" id="pc_res_cuotas">0</div>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <div class="progress" style="height:6px;background:rgba(255,255,255,0.2);border-radius:3px;">
                                                <div class="progress-bar bg-white" id="pc_progreso" style="width:0%;border-radius:3px;"></div>
                                            </div>
                                            <div class="text-center mt-1" style="font-size:0.72rem;opacity:0.9;position:relative;z-index:1;">
                                                <span id="pc_txt_progreso">0% del total seleccionado</span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-sm" id="btnRegistrarPagoContabilidad"
                        style="background:var(--cont-primary);color:white;">
                    <i class="ri-checkbox-circle-line me-1"></i>Registrar Pago
                </button>
            </div>
        </div>
    </div>
</div>
