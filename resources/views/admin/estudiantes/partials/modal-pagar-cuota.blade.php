<style>
    .pago-info-card {
        background: var(--est-surface);
        border-radius: var(--radius-md);
        border: 1px solid var(--est-border);
        padding: 16px;
        height: 100%;
    }

    .pago-info-title {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--est-text-muted);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pago-info-title i { color: var(--est-accent); }

    .pago-info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        border-bottom: 1px solid var(--est-border);
        font-size: 0.84rem;
    }

    .pago-info-row:last-child { border-bottom: none; }

    .pago-resumen-card {
        background: linear-gradient(135deg, var(--est-primary) 0%, var(--est-primary-dark) 100%);
        border-radius: var(--radius-md);
        padding: 18px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .pago-resumen-card::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -5%;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }

    .pago-res-label {
        font-size: 0.68rem;
        opacity: 0.75;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 2px;
        position: relative;
        z-index: 1;
    }

    .pago-res-value {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        position: relative;
        z-index: 1;
    }

    .pago-res-divider {
        width: 1px;
        height: 40px;
        background: rgba(255,255,255,0.2);
    }

    .pago-form-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--est-text);
        margin-bottom: 4px;
    }

    .pago-form-control {
        border-radius: var(--radius-sm);
        border: 1px solid var(--est-border);
        padding: 8px 12px;
        font-size: 0.85rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--est-surface);
        transition: all 0.2s ease;
    }

    .pago-form-control:focus {
        border-color: var(--est-primary);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        background: white;
    }

    .cobrador-alert {
        border-radius: var(--radius-sm);
        padding: 10px 14px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
    }

    .cobrador-alert .cobrador-name { font-weight: 600; }
    .cobrador-alert .cobrador-cargo { font-size: 0.75rem; opacity: 0.8; }
</style>

<div class="modal fade modal-cont" id="modalPagarCuota" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-money-dollar-circle-line me-2"></i>Registrar Pago de Cuota
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formPagarCuota">
                @csrf
                <input type="hidden" id="cuota_id" name="cuota_id">
                <input type="hidden" id="estudiante_id" name="estudiante_id">

                <div class="modal-body">

                    {{-- Cobrador --}}
                    @if (isset($cobrador) && $cobrador)
                        <div class="cobrador-alert" style="background:var(--est-success-light);color:var(--est-success);">
                            <i class="ri-user-star-line" style="font-size:1.1rem;"></i>
                            <div>
                                <div class="cobrador-name">{{ $cobrador->nombres }} {{ $cobrador->apellido_paterno }} {{ $cobrador->apellido_materno ?? '' }}</div>
                                <div class="cobrador-cargo">Cobrador — {{ $cobrador->cargo }}</div>
                            </div>
                        </div>
                    @else
                        <div class="cobrador-alert" style="background:var(--est-warning-light);color:var(--est-warning);">
                            <i class="ri-alert-line" style="font-size:1.1rem;"></i>
                            <div>No se pudo identificar al cobrador. Verifique su cargo vigente.</div>
                        </div>
                    @endif

                    <div class="row g-3">
                        {{-- Info cuota --}}
                        <div class="col-md-5">
                            <div class="pago-info-card">
                                <div class="pago-info-title"><i class="ri-file-list-3-line"></i>Información de la Cuota</div>
                                <div class="pago-info-row">
                                    <span style="color:var(--est-text-muted);">Nombre</span>
                                    <span class="fw-medium text-end" id="info-cuota-nombre" style="max-width:55%;"></span>
                                </div>
                                <div class="pago-info-row">
                                    <span style="color:var(--est-text-muted);">Programa</span>
                                    <span class="text-end" id="info-cuota-programa" style="max-width:55%;font-size:0.8rem;"></span>
                                </div>
                                <div class="pago-info-row">
                                    <span style="color:var(--est-text-muted);">Total cuota</span>
                                    <span class="fw-bold" style="color:var(--est-primary);"><span id="info-cuota-total">0.00</span> Bs</span>
                                </div>
                                <div class="pago-info-row">
                                    <span style="color:var(--est-text-muted);">Saldo pagado</span>
                                    <span class="fw-bold" style="color:var(--est-success);"><span id="info-cuota-pagado">0.00</span> Bs</span>
                                </div>
                                <div class="pago-info-row">
                                    <span style="color:var(--est-text-muted);">Pendiente</span>
                                    <span class="fw-bold" style="color:var(--est-danger);"><span id="info-cuota-pendiente">0.00</span> Bs</span>
                                </div>
                            </div>
                        </div>

                        {{-- Formulario --}}
                        <div class="col-md-7">
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="pago-form-label">Monto a Pagar (Bs) *</label>
                                    <input type="number" step="0.01" class="pago-form-control w-100"
                                        id="monto_pago" name="monto_pago" required placeholder="0.00">
                                    <div class="form-text" style="font-size:0.75rem;">
                                        Máximo: <span id="maximo_pago" class="fw-bold" style="color:var(--est-danger);">0.00</span> Bs
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="pago-form-label">Descuento (Bs)</label>
                                    <input type="number" step="0.01" class="pago-form-control w-100"
                                        id="descuento" name="descuento" value="0" placeholder="0.00">
                                </div>
                                <div class="col-6">
                                    <label class="pago-form-label">Tipo de Pago *</label>
                                    <select class="form-select form-select-sm pago-form-control" id="tipo_pago" name="tipo_pago"
                                        required onchange="togglePaymentFields()">
                                        <option value="">Seleccionar...</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Depósito">Depósito</option>
                                        <option value="Tarjeta">Tarjeta</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="pago-form-label">Fecha de Pago *</label>
                                    <input type="date" class="pago-form-control w-100"
                                        id="fecha_pago" name="fecha_pago" value="{{ date('Y-m-d') }}" required>
                                </div>

                                <div class="col-12" id="campo_caja" style="display:none;">
                                    <label class="pago-form-label">Caja *</label>
                                    <select class="form-select form-select-sm pago-form-control" id="caja_id" name="caja_id">
                                        <option value="">Seleccionar caja...</option>
                                        @foreach (\App\Models\Caja::where('activa', true)->with('sucursal')->get() as $caja)
                                            <option value="{{ $caja->id }}">
                                                {{ $caja->nombre }} — {{ $caja->sucursal->nombre ?? 'Sin sucursal' }}
                                                ({{ number_format($caja->saldo_actual, 2) }} Bs)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12" id="campo_cuenta_bancaria" style="display:none;">
                                    <label class="pago-form-label">Cuenta Bancaria *</label>
                                    <select class="form-select form-select-sm pago-form-control" id="cuenta_bancaria_id" name="cuenta_bancaria_id">
                                        <option value="">Seleccionar cuenta...</option>
                                        @foreach (\App\Models\CuentasBancarias::where('activa', true)->with('banco')->get() as $cuenta)
                                            <option value="{{ $cuenta->id }}">
                                                {{ $cuenta->banco->nombre ?? 'Sin banco' }} — {{ $cuenta->numero_cuenta }}
                                                ({{ $cuenta->moneda }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12" id="campo_comprobante" style="display:none;">
                                    <label class="pago-form-label">N° Comprobante *</label>
                                    <input type="text" class="pago-form-control w-100"
                                        id="n_comprobante" name="n_comprobante" placeholder="Ej: TRF-0012345">
                                </div>

                                <div class="col-12">
                                    <label class="pago-form-label">Observaciones</label>
                                    <textarea class="pago-form-control w-100" id="observaciones"
                                        name="observaciones" rows="2" placeholder="Opcional..."></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Resumen --}}
                        <div class="col-12">
                            <div class="pago-resumen-card">
                                <div class="d-flex align-items-center justify-content-around">
                                    <div class="text-center">
                                        <div class="pago-res-label">Monto</div>
                                        <div class="pago-res-value" id="resumen-monto">0.00 Bs</div>
                                    </div>
                                    <div class="pago-res-divider"></div>
                                    <div class="text-center">
                                        <div class="pago-res-label">Descuento</div>
                                        <div class="pago-res-value" id="resumen-descuento">0.00 Bs</div>
                                    </div>
                                    <div class="pago-res-divider"></div>
                                    <div class="text-center">
                                        <div class="pago-res-label">Total a Pagar</div>
                                        <div class="pago-res-value" id="resumen-total">0.00 Bs</div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="progress" style="height:6px;background:rgba(255,255,255,0.2);border-radius:3px;">
                                        <div class="progress-bar bg-white" id="progreso-pago" role="progressbar" style="width:0%;border-radius:3px;"></div>
                                    </div>
                                    <div class="text-center mt-1" style="font-size:0.75rem;opacity:0.9;position:relative;z-index:1;">
                                        <span id="texto-progreso">0% del saldo pendiente</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-sm" style="background:var(--est-primary);color:white;">
                        <i class="ri-checkbox-circle-line me-1"></i>Registrar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePaymentFields() {
        const tipoPago = document.getElementById('tipo_pago').value;
        const campoCaja        = document.getElementById('campo_caja');
        const campoCuenta      = document.getElementById('campo_cuenta_bancaria');
        const campoComprobante = document.getElementById('campo_comprobante');

        campoCaja.style.display        = 'none';
        campoCuenta.style.display      = 'none';
        campoComprobante.style.display = 'none';

        document.getElementById('caja_id').removeAttribute('required');
        document.getElementById('cuenta_bancaria_id').removeAttribute('required');
        document.getElementById('n_comprobante').removeAttribute('required');

        if (tipoPago === 'Efectivo') {
            campoCaja.style.display = 'block';
            document.getElementById('caja_id').setAttribute('required', 'required');
        } else if (['Transferencia', 'Depósito', 'Tarjeta'].includes(tipoPago)) {
            campoCuenta.style.display      = 'block';
            campoComprobante.style.display = 'block';
            document.getElementById('cuenta_bancaria_id').setAttribute('required', 'required');
            document.getElementById('n_comprobante').setAttribute('required', 'required');
        }
    }
</script>
