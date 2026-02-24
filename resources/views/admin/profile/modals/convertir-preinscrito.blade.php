<!-- Modal para convertir Pre-Inscrito a Inscrito -->
<div class="modal fade" id="convertirModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-user-add-line me-2"></i>Convertir a Inscrito
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Información del estudiante -->
                <div class="card border-primary mb-4">
                    <div class="card-header bg-primary bg-opacity-10">
                        <h6 class="mb-0">Información del Estudiante</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Estudiante:</strong>
                                    <span id="convertirEstudianteNombre" class="ms-2">-</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Carnet:</strong>
                                    <span id="convertirEstudianteCarnet" class="ms-2 badge bg-info">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Programa:</strong>
                                    <span id="convertirProgramaNombre" class="ms-2">-</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Estado Actual:</strong>
                                    <span class="badge bg-warning ms-2">Pre-Inscrito</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advertencia -->
                <div class="alert alert-info mb-4">
                    <i class="ri-information-line me-2"></i>
                    <strong>Importante:</strong> Al convertir a inscrito, se generarán automáticamente:
                    <ul class="mb-0 mt-2">
                        <li>Matriculaciones en todos los módulos del programa</li>
                        <li>Cuotas de pago según el plan seleccionado</li>
                        <li>El estado cambiará a "Inscrito" permanentemente</li>
                    </ul>
                </div>

                <!-- Selección de Plan de Pago -->
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="ri-money-dollar-circle-line me-2"></i>Seleccionar Plan de Pago
                        <span class="text-danger">*</span>
                    </h6>
                    <div id="planesPagoConversionContainer">
                        <!-- Aquí se cargarán los planes de pago -->
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="mb-4">
                    <label for="observacionConversion" class="form-label">
                        <i class="ri-chat-1-line me-2"></i>Observaciones (Opcional)
                    </label>
                    <textarea class="form-control" id="observacionConversion" rows="3"
                        placeholder="Agregar observaciones sobre la conversión..."></textarea>
                    <div class="form-text">Esta observación quedará registrada en la inscripción.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="confirmarConversionBtn">
                    <i class="ri-check-double-line me-1"></i>Confirmar Conversión
                </button>
            </div>
        </div>
    </div>
</div>
