<style>
    .pago-section-title {
        font-size: .7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: #6c757d; margin-bottom: .4rem;
    }
    .info-pago-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: .3rem 0; border-bottom: 1px solid #f0f0f0; font-size: .85rem;
    }
    .info-pago-row:last-child { border-bottom: none; }
    .resumen-box {
        background: linear-gradient(135deg, #405189 0%, #0ab39c 100%);
        border-radius: 10px; padding: 1rem; color: #fff;
    }
    .resumen-box .res-label { font-size: .72rem; opacity: .85; margin-bottom: .2rem; }
    .resumen-box .res-value { font-size: 1.1rem; font-weight: 700; }
    .resumen-box .divider { width: 1px; background: rgba(255,255,255,.3); margin: 0 .5rem; }
</style>

<div class="modal fade" id="modalPagarCuota" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title mb-0">
                        <i class="ri-money-dollar-circle-line text-primary me-2"></i>Registrar Pago
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formPagarCuota">
                @csrf
                <input type="hidden" id="cuota_id" name="cuota_id">
                <input type="hidden" id="estudiante_id" name="estudiante_id">

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
                        {{-- Columna izquierda: info cuota + campos pago --}}
                        <div class="col-md-6">

                            {{-- Info cuota --}}
                            <div class="border rounded p-2 mb-3 bg-light">
                                <div class="pago-section-title"><i class="ri-file-list-3-line me-1"></i>Cuota</div>
                                <div class="info-pago-row">
                                    <span class="text-muted">Nombre</span>
                                    <span class="fw-medium text-end" id="info-cuota-nombre" style="max-width:60%;"></span>
                                </div>
                                <div class="info-pago-row">
                                    <span class="text-muted">Programa</span>
                                    <span class="text-end small" id="info-cuota-programa" style="max-width:60%;"></span>
                                </div>
                                <div class="info-pago-row">
                                    <span class="text-muted">Total cuota</span>
                                    <span class="fw-bold text-primary"><span id="info-cuota-total">0.00</span> Bs</span>
                                </div>
                                <div class="info-pago-row">
                                    <span class="text-muted">Saldo pagado</span>
                                    <span class="fw-bold text-success"><span id="info-cuota-pagado">0.00</span> Bs</span>
                                </div>
                                <div class="info-pago-row">
                                    <span class="text-muted">Pendiente</span>
                                    <span class="fw-bold text-danger"><span id="info-cuota-pendiente">0.00</span> Bs</span>
                                </div>
                            </div>

                            {{-- Monto y descuento --}}
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="form-label form-label-sm mb-1">Monto a Pagar (Bs) *</label>
                                    <input type="number" step="0.01" class="form-control form-control-sm"
                                        id="monto_pago" name="monto_pago" required placeholder="0.00">
                                    <div class="form-text">
                                        Máximo: <span id="maximo_pago" class="text-danger fw-bold">0.00</span> Bs
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label form-label-sm mb-1">Descuento (Bs)</label>
                                    <input type="number" step="0.01" class="form-control form-control-sm"
                                        id="descuento" name="descuento" value="0" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        {{-- Columna derecha: tipo pago + campos condicionales --}}
                        <div class="col-md-6">
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="form-label form-label-sm mb-1">Tipo de Pago *</label>
                                    <select class="form-select form-select-sm" id="tipo_pago" name="tipo_pago"
                                        required onchange="togglePaymentFields()">
                                        <option value="">Seleccionar...</option>
                                        <option value="Efectivo">💵 Efectivo</option>
                                        <option value="Transferencia">🏦 Transferencia</option>
                                        <option value="Depósito">📥 Depósito</option>
                                        <option value="Tarjeta">💳 Tarjeta</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label form-label-sm mb-1">Fecha de Pago *</label>
                                    <input type="date" class="form-control form-control-sm"
                                        id="fecha_pago" name="fecha_pago" value="{{ date('Y-m-d') }}" required>
                                </div>

                                {{-- Caja (solo Efectivo) --}}
                                <div class="col-12" id="campo_caja" style="display:none;">
                                    <label class="form-label form-label-sm mb-1">Caja *</label>
                                    <select class="form-select form-select-sm" id="caja_id" name="caja_id">
                                        <option value="">Seleccionar caja...</option>
                                        @foreach (\App\Models\Caja::where('activa', true)->with('sucursal')->get() as $caja)
                                            <option value="{{ $caja->id }}">
                                                {{ $caja->nombre }} — {{ $caja->sucursal->nombre ?? 'Sin sucursal' }}
                                                ({{ number_format($caja->saldo_actual, 2) }} Bs)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Cuenta bancaria (Transferencia/Depósito/Tarjeta) --}}
                                <div class="col-12" id="campo_cuenta_bancaria" style="display:none;">
                                    <label class="form-label form-label-sm mb-1">Cuenta Bancaria *</label>
                                    <select class="form-select form-select-sm" id="cuenta_bancaria_id" name="cuenta_bancaria_id">
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
                                <div class="col-12" id="campo_comprobante" style="display:none;">
                                    <label class="form-label form-label-sm mb-1">N° Comprobante *</label>
                                    <input type="text" class="form-control form-control-sm"
                                        id="n_comprobante" name="n_comprobante" placeholder="Ej: TRF-0012345">
                                </div>

                                {{-- Observaciones --}}
                                <div class="col-12">
                                    <label class="form-label form-label-sm mb-1">Observaciones</label>
                                    <textarea class="form-control form-control-sm" id="observaciones"
                                        name="observaciones" rows="2" placeholder="Opcional..."></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Resumen en tiempo real --}}
                        <div class="col-12">
                            <div class="resumen-box">
                                <div class="d-flex align-items-center justify-content-around">
                                    <div class="text-center">
                                        <div class="res-label">Monto</div>
                                        <div class="res-value" id="resumen-monto">0.00 Bs</div>
                                    </div>
                                    <div class="divider" style="height:40px;"></div>
                                    <div class="text-center">
                                        <div class="res-label">Descuento</div>
                                        <div class="res-value" id="resumen-descuento">0.00 Bs</div>
                                    </div>
                                    <div class="divider" style="height:40px;"></div>
                                    <div class="text-center">
                                        <div class="res-label">Total a Pagar</div>
                                        <div class="res-value" id="resumen-total">0.00 Bs</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="progress" style="height:8px; background:rgba(255,255,255,.2); border-radius:4px;">
                                        <div class="progress-bar bg-white" id="progreso-pago" role="progressbar" style="width:0%;border-radius:4px;"></div>
                                    </div>
                                    <div class="text-center mt-1" style="font-size:.75rem; opacity:.9;">
                                        <span id="texto-progreso">0% del saldo pendiente</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
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
