<div class="modal fade" id="modalPagarCuota" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formPagarCuota">
                @csrf
                <input type="hidden" id="cuota_id" name="cuota_id">
                <input type="hidden" id="estudiante_id" name="estudiante_id">

                <div class="modal-body">
                    <!-- Información de la cuota -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Cuota:</strong> <span id="info-cuota-nombre"></span>
                                        </p>
                                        <p class="mb-1"><strong>Programa:</strong> <span
                                                id="info-cuota-programa"></span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Total Cuota:</strong> <span id="info-cuota-total"
                                                class="text-primary"></span> Bs</p>
                                        <p class="mb-1"><strong>Saldo Pendiente:</strong> <span
                                                id="info-cuota-pendiente" class="text-danger"></span> Bs</p>
                                        <p class="mb-0"><strong>Saldo Pagado:</strong> <span id="info-cuota-pagado"
                                                class="text-success"></span> Bs</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="monto_pago" class="form-label">Monto a Pagar (Bs) *</label>
                                <input type="number" step="0.01" class="form-control" id="monto_pago"
                                    name="monto_pago" required>
                                <div class="form-text">
                                    Máximo: <span id="maximo_pago" class="text-danger fw-bold">0.00</span> Bs
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="descuento" class="form-label">Descuento (Bs)</label>
                                <input type="number" step="0.01" class="form-control" id="descuento"
                                    name="descuento" value="0">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_pago" class="form-label">Tipo de Pago *</label>
                                <select class="form-select" id="tipo_pago" name="tipo_pago" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Depósito">Depósito</option>
                                    <option value="Tarjeta">Tarjeta</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_pago" class="form-label">Fecha de Pago *</label>
                                <input type="date" class="form-control" id="fecha_pago" name="fecha_pago"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
                            </div>
                        </div>

                        <!-- Resumen del pago en tiempo real -->
                        <div class="col-12">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">Resumen del Pago</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted">Monto a Pagar</p>
                                            <h5 class="text-primary" id="resumen-monto">0.00 Bs</h5>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted">Descuento</p>
                                            <h5 class="text-warning" id="resumen-descuento">0.00 Bs</h5>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted">Total a Pagar</p>
                                            <h5 class="text-success" id="resumen-total">0.00 Bs</h5>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-success" id="progreso-pago"
                                                    role="progressbar" style="width: 0%"></div>
                                            </div>
                                            <small class="text-muted mt-1 d-block text-center">
                                                <span id="texto-progreso">0% del saldo pendiente</span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar Pago</button>
                </div>
            </form>
        </div>
    </div>
</div>
