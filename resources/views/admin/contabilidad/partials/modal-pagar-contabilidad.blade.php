<style>
    .cuota-check-item {
        display: flex; align-items: center; gap: .6rem;
        padding: .45rem .6rem; border-radius: 7px;
        border: 1px solid #e9ebec; margin-bottom: .3rem;
        cursor: pointer; transition: background .15s;
    }
    .cuota-check-item:hover { background: #f8f9fa; }
    .cuota-check-item.selected { background: #e8f4fd; border-color: #90caf9; }
    .cuota-check-item input[type=checkbox] { width: 16px; height: 16px; flex-shrink: 0; cursor: pointer; }
    .cuota-tipo-badge {
        font-size: .65rem; font-weight: 700; padding: .15rem .4rem;
        border-radius: 4px; white-space: nowrap;
    }
    .prog-header {
        font-size: .72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .05em; color: #405189;
        padding: .3rem .5rem; background: #eef2ff; border-radius: 6px;
        margin-bottom: .4rem; margin-top: .6rem;
    }
    .prog-header:first-child { margin-top: 0; }
    .resumen-pago-grad {
        background: linear-gradient(135deg, #405189 0%, #0ab39c 100%);
        border-radius: 10px; padding: .8rem 1rem; color: #fff;
    }
    .resumen-pago-grad .lbl { font-size: .7rem; opacity: .85; }
    .resumen-pago-grad .val { font-size: 1rem; font-weight: 700; }
    .total-sel-badge {
        font-size: .9rem; font-weight: 700; padding: .35rem .8rem;
        border-radius: 6px; background: #e8f4fd; color: #1a6fa8;
    }
</style>

<div class="modal fade" id="modalPagarContabilidad" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title mb-0">
                        <i class="ri-money-dollar-circle-line text-primary me-2"></i>Registrar Pago
                    </h5>
                    <div class="text-muted small">Selecciona una o varias cuotas para pagar en un solo recibo</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body pt-2">

                {{-- Cobrador --}}
                @if (isset($cobrador) && $cobrador)
                    <div class="d-flex align-items-center gap-2 rounded border border-success-subtle bg-success-subtle px-3 py-2 mb-3">
                        <div class="avatar-xs flex-shrink-0">
                            <div class="avatar-title bg-success rounded-circle fs-6">
                                <i class="ri-user-star-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold text-success" style="font-size:.88rem;">
                                {{ $cobrador->nombres }} {{ $cobrador->apellido_paterno }} {{ $cobrador->apellido_materno ?? '' }}
                            </div>
                            <div class="text-muted" style="font-size:.75rem;">Cobrador — {{ $cobrador->cargo }}</div>
                        </div>
                        <span class="badge bg-success">Identificado</span>
                    </div>
                @else
                    <div class="d-flex align-items-center gap-2 rounded border border-warning-subtle bg-warning-subtle px-3 py-2 mb-3">
                        <i class="ri-alert-line text-warning fs-5 flex-shrink-0"></i>
                        <div class="small text-warning-emphasis">
                            No se pudo identificar al cobrador. Verifique que su usuario tenga un cargo vigente asignado.
                        </div>
                    </div>
                @endif

                <div class="row g-3">

                    {{-- IZQUIERDA: Lista de cuotas --}}
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-semibold small text-uppercase text-muted">
                                <i class="ri-file-list-3-line me-1"></i>Cuotas Pendientes
                            </span>
                            <div class="d-flex gap-1 align-items-center">
                                <button type="button" class="btn btn-outline-primary btn-xs px-2 py-1" id="btnSeleccionarTodas" style="font-size:.75rem;">
                                    Todas
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-xs px-2 py-1" id="btnDeseleccionarTodas" style="font-size:.75rem;">
                                    Ninguna
                                </button>
                                <span class="total-sel-badge ms-1" id="totalSelBadge">0.00 Bs</span>
                            </div>
                        </div>

                        <div id="listaCuotasPago" style="max-height:380px; overflow-y:auto;">
                            <div class="text-center py-4 text-muted">
                                <div class="spinner-border spinner-border-sm me-2"></div>Cargando cuotas...
                            </div>
                        </div>
                    </div>

                    {{-- DERECHA: Formulario --}}
                    <div class="col-md-6">
                        <form id="formPagarContabilidad">
                            @csrf
                            <input type="hidden" id="pc_estudiante_id" name="estudiante_id" value="{{ $estudiante->id }}">

                            <div class="border rounded p-3">
                                <div class="fw-semibold small text-uppercase text-muted mb-3">
                                    <i class="ri-money-dollar-circle-line me-1"></i>Datos del Pago
                                </div>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label form-label-sm mb-1">Monto a Pagar (Bs) *</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm"
                                            id="pc_monto_pago" name="monto_pago" required placeholder="0.00">
                                        <div class="form-text small">
                                            Pendiente sel.: <span id="pc_pendiente_total" class="text-danger fw-bold">0.00</span> Bs
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label form-label-sm mb-1">Descuento (Bs)</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm"
                                            id="pc_descuento" name="descuento" value="0" placeholder="0.00">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label form-label-sm mb-1">Tipo de Pago *</label>
                                        <select class="form-select form-select-sm" id="pc_tipo_pago" name="tipo_pago" required onchange="togglePcFields()">
                                            <option value="">Seleccionar...</option>
                                            <option value="Efectivo">💵 Efectivo</option>
                                            <option value="Transferencia">🏦 Transferencia</option>
                                            <option value="Depósito">📥 Depósito</option>
                                            <option value="Tarjeta">💳 Tarjeta</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label form-label-sm mb-1">Fecha de Pago *</label>
                                        <input type="date" class="form-control form-control-sm"
                                            id="pc_fecha_pago" name="fecha_pago" value="{{ date('Y-m-d') }}" required>
                                    </div>

                                    {{-- Caja --}}
                                    <div class="col-12" id="pc_campo_caja" style="display:none;">
                                        <label class="form-label form-label-sm mb-1">Caja *</label>
                                        <select class="form-select form-select-sm" id="pc_caja_id" name="caja_id">
                                            <option value="">Seleccionar caja...</option>
                                            @foreach (\App\Models\Caja::where('activa', true)->with('sucursal')->get() as $caja)
                                                <option value="{{ $caja->id }}">
                                                    {{ $caja->nombre }} — {{ $caja->sucursal->nombre ?? 'Sin sucursal' }}
                                                    ({{ number_format($caja->saldo_actual, 2) }} Bs)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Cuenta bancaria --}}
                                    <div class="col-12" id="pc_campo_cuenta" style="display:none;">
                                        <label class="form-label form-label-sm mb-1">Cuenta Bancaria *</label>
                                        <select class="form-select form-select-sm" id="pc_cuenta_id" name="cuenta_bancaria_id">
                                            <option value="">Seleccionar cuenta...</option>
                                            @foreach (\App\Models\CuentasBancarias::where('activa', true)->with('banco')->get() as $cuenta)
                                                <option value="{{ $cuenta->id }}">
                                                    {{ $cuenta->banco->nombre ?? 'Sin banco' }} — {{ $cuenta->numero_cuenta }}
                                                    ({{ $cuenta->moneda }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- N° Comprobante --}}
                                    <div class="col-12" id="pc_campo_comprobante" style="display:none;">
                                        <label class="form-label form-label-sm mb-1">N° Comprobante *</label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="pc_n_comprobante" name="n_comprobante" placeholder="Ej: TRF-0012345">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label form-label-sm mb-1">Observaciones</label>
                                        <textarea class="form-control form-control-sm" id="pc_observaciones"
                                            name="observaciones" rows="2" placeholder="Opcional..."></textarea>
                                    </div>
                                </div>

                                {{-- Resumen --}}
                                <div class="resumen-pago-grad mt-3">
                                    <div class="d-flex align-items-center justify-content-around">
                                        <div class="text-center">
                                            <div class="lbl">Monto</div>
                                            <div class="val" id="pc_res_monto">0.00 Bs</div>
                                        </div>
                                        <div style="width:1px;height:36px;background:rgba(255,255,255,.3);"></div>
                                        <div class="text-center">
                                            <div class="lbl">Descuento</div>
                                            <div class="val" id="pc_res_desc">0.00 Bs</div>
                                        </div>
                                        <div style="width:1px;height:36px;background:rgba(255,255,255,.3);"></div>
                                        <div class="text-center">
                                            <div class="lbl">Total</div>
                                            <div class="val" id="pc_res_total">0.00 Bs</div>
                                        </div>
                                        <div style="width:1px;height:36px;background:rgba(255,255,255,.3);"></div>
                                        <div class="text-center">
                                            <div class="lbl">Cuotas sel.</div>
                                            <div class="val" id="pc_res_cuotas">0</div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="progress" style="height:6px;background:rgba(255,255,255,.2);border-radius:3px;">
                                            <div class="progress-bar bg-white" id="pc_progreso" style="width:0%;border-radius:3px;"></div>
                                        </div>
                                        <div class="text-center mt-1" style="font-size:.72rem;opacity:.9;">
                                            <span id="pc_txt_progreso">0% del total seleccionado</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btnRegistrarPagoContabilidad">
                    <i class="ri-checkbox-circle-line me-1"></i>Registrar Pago
                </button>
            </div>
        </div>
    </div>
</div>


