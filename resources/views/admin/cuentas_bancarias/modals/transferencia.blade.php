<div class="modal fade" id="modalTransferencia" tabindex="-1" aria-labelledby="modalTransferenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTransferenciaLabel">Realizar Transferencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTransferencia">
                @csrf
                <input type="hidden" name="cuenta_origen_id" id="cuenta_origen_id" value="">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Banco Destino</label>
                            <select class="form-select" id="banco_destino_id" name="banco_destino_id" required>
                                <option value="">Seleccionar banco...</option>
                                @foreach ($bancos ?? [] as $banco)
                                    <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sucursal Destino</label>
                            <select class="form-select" id="sucursal_destino_id" name="sucursal_destino_id" required>
                                <option value="">Seleccionar sucursal...</option>
                                @foreach ($sucursales ?? [] as $sucursal)
                                    <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <button type="button" class="btn btn-outline-primary" id="cargarCuentasDestino">
                                <i class="ri-refresh-line me-1"></i> Cargar Cuentas
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cuenta Destino</label>
                            <select class="form-select" id="cuenta_destino_id" name="cuenta_destino_id" required>
                                <option value="">Seleccionar cuenta...</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Monto</label>
                            <input type="number" class="form-control" name="monto" step="0.01" min="0.01"
                                required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Transferencia</label>
                            <input type="date" class="form-control" name="fecha_transferencia" required
                                value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Transferencia</label>
                            <select class="form-select" name="tipo_transferencia" required>
                                <option value="interbancaria">Interbancaria</option>
                                <option value="intrabancaria">Mismo Banco</option>
                                <option value="correccion">Corrección</option>
                                <option value="ajuste">Ajuste</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Realizar Transferencia</button>
                </div>
            </form>
        </div>
    </div>
</div>
