<!-- Modales - Diseño Premium -->

<!-- Modal para Eliminar Inscripción -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-lg d-flex align-items-center justify-content-center rounded-circle bg-danger-subtle">
                        <i class="ri-alert-line text-danger fs-24"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-semibold">Confirmar Eliminación</h5>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-dark mb-3">¿Está seguro de eliminar la inscripción de <strong id="estudianteEliminarNombre"></strong>?</p>
                <div class="alert border-0 mb-0" style="background: linear-gradient(135deg, #fef2f2, #fee2e2); border-radius: 10px;">
                    <div class="d-flex align-items-start gap-2">
                        <i class="ri-error-warning-line text-danger mt-1"></i>
                        <div>
                            <strong class="text-danger">Advertencia:</strong>
                            <p class="mb-0 text-muted fs-13">Esta acción eliminará todas las cuotas, pagos y notas asociadas a esta inscripción. Esta acción no se puede deshacer.</p>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="inscripcionEliminarId">
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 8px;">Cancelar</button>
                <button type="button" class="btn btn-danger px-4" id="confirmarEliminarBtn" style="border-radius: 8px;">
                    <i class="ri-delete-bin-line me-1"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Transferir Inscripción -->
<div class="modal fade" id="modalTransferir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-lg d-flex align-items-center justify-content-center rounded-circle" style="background: linear-gradient(135deg, var(--dash-primary), #0d9488);">
                        <i class="ri-exchange-line text-white fs-20"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-semibold">Transferir Inscripción</h5>
                        <p class="text-muted mb-0 fs-13">Mover estudiante a otra oferta académica</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-medium fs-13">Estudiante</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ri-user-line text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="estudianteTransferirNombre" readonly style="background: #f8fafc;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium fs-13">Carnet</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ri-id-card-line text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="estudianteTransferirCarnet" readonly style="background: #f8fafc;">
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-medium fs-13">Nueva Oferta Académica <span class="text-danger">*</span></label>
                        <select class="form-select" id="nuevaOfertaSelect" style="border-radius: 8px;">
                            <option value="">Seleccione una oferta</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium fs-13">Plan de Pago <span class="text-danger">*</span></label>
                        <select class="form-select" id="planPagoSelect" disabled style="border-radius: 8px; background: #f8fafc;">
                            <option value="">Primero seleccione una oferta</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium fs-13">Observación</label>
                    <textarea class="form-control" id="observacionTransferencia" rows="2" placeholder="Motivo de la transferencia..." style="border-radius: 8px;"></textarea>
                </div>

                <div class="card border-0" id="planDetallesCard" style="display: none; background: linear-gradient(135deg, #f0fdfa, #ccfbf1); border-radius: 12px;">
                    <div class="card-header border-0 bg-transparent pt-3">
                        <h6 class="mb-0 fw-semibold" style="color: var(--dash-primary);">
                            <i class="ri-file-list-3-line align-middle me-2"></i>
                            Detalles del Plan de Pago
                        </h6>
                    </div>
                    <div class="card-body pt-0" id="planDetallesBody">
                    </div>
                </div>

                <input type="hidden" id="inscripcionTransferirId">
                <input type="hidden" id="estudianteTransferirId">
            </div>
            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 8px;">Cancelar</button>
                <button type="button" class="btn px-4" id="confirmarTransferirBtn" disabled style="border-radius: 8px; background: var(--dash-primary); color: white;">
                    <i class="ri-check-line me-1"></i> Confirmar Transferencia
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Cambiar Plan de Pago -->
<div class="modal fade" id="modalCambiarPlanPago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-lg d-flex align-items-center justify-content-center rounded-circle" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="ri-exchange-dollar-line text-white fs-20"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-semibold">Cambiar Plan de Pago</h5>
                        <p class="text-muted mb-0 fs-13">Modificar plan y registrar adelanto</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCambiarPlanPago">
                    @csrf
                    <input type="hidden" name="inscripcion_id" id="cambiar_plan_inscripcion_id">
                    <input type="hidden" name="oferta_id" id="cambiar_plan_oferta_id">

                    <div class="mb-3">
                        <label class="form-label fw-medium fs-13">Plan Actual</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ri-bookmark-line text-muted"></i>
                            </span>
                            <input type="text" id="plan_actual_nombre" class="form-control border-start-0" readonly style="background: #f8fafc;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium fs-13">Nuevo Plan de Pago <span class="text-danger">*</span></label>
                        <select name="nuevo_plan_pago_id" class="form-select" id="nuevo_plan_pago_select" required style="border-radius: 8px;">
                            <option value="">Cargando planes...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium fs-13">
                            <i class="ri-money-dollar-circle-line me-1 text-success"></i>
                            Adelanto (Bs)
                        </label>
                        <div class="input-group" style="border-radius: 8px; overflow: hidden;">
                            <span class="input-group-text" style="background: #f8fafc; border-color: #e2e8f0;">
                                Bs
                            </span>
                            <input type="number" step="0.01" min="0" name="adelanto_bs" id="cambiar_plan_adelanto_bs" class="form-control" placeholder="0.00" value="0.00" style="border-left: 0;">
                        </div>
                        <small class="text-muted fs-12 mt-1 d-block">Monto adicional como adelanto (dejar en 0 si no aplica)</small>
                    </div>

                    <div class="alert d-flex align-items-start gap-2" style="background: linear-gradient(135deg, #e0f2fe, #bae6fd); border-radius: 10px; border: none;">
                        <i class="ri-information-line text-info mt-1"></i>
                        <span class="fs-13 text-dark">Solo se pueden seleccionar planes de pago disponibles para esta oferta.</span>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 8px;">Cancelar</button>
                <button type="button" class="btn px-4" id="btnConfirmarCambioPlan" style="border-radius: 8px; background: var(--dash-primary); color: white;">
                    <i class="ri-check-line me-1"></i> Confirmar Cambio
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-content {
        font-family: 'Outfit', sans-serif;
    }
    .btn-close:focus {
        box-shadow: none;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--dash-primary);
        box-shadow: 0 0 0 3px rgba(10, 179, 156, 0.15);
    }
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>