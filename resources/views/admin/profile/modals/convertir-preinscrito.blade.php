<!-- Modal para convertir Pre-Inscrito a Inscrito -->
<div class="modal fade modal-profile" id="convertirModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow" style="border-radius: var(--radius-lg); overflow: hidden;">

            <div class="modal-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #0f766e 0%, #0d5f59 100%); color: #fff;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 42px; height: 42px; background: rgba(255,255,255,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="ri-user-add-line" style="font-size: 1.3rem; color: #fff;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-semibold" style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; color: #fff;">Convertir a Inscrito</h5>
                        <div style="opacity: 0.85; font-size: 0.78rem; color: #fff;">Cambia el estado de Pre-Inscrito a Inscrito</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4" style="background: #f8fafc;">

                <!-- Info del estudiante -->
                <div class="card border-0 shadow-sm mb-3" style="border-radius: var(--radius-md); overflow: hidden;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 48px; height: 48px; background: #f0fdfa; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="ri-user-line" style="font-size: 1.4rem; color: #0f766e;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold" style="font-size: 0.95rem; color: #1e293b;" id="convertirEstudianteNombre">-</div>
                                <div class="d-flex align-items-center gap-3 mt-1 flex-wrap">
                                    <span class="d-inline-flex align-items-center gap-1" style="font-size: 0.78rem; color: #64748b;">
                                        <i class="ri-id-card-line"></i>
                                        <span id="convertirEstudianteCarnet" class="fw-medium" style="font-family: monospace; background: #f1f5f9; padding: 1px 6px; border-radius: 4px;">-</span>
                                    </span>
                                    <span class="d-inline-flex align-items-center gap-1" style="font-size: 0.78rem; color: #64748b;">
                                        <i class="ri-book-line"></i>
                                        <span id="convertirProgramaNombre">-</span>
                                    </span>
                                </div>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <span class="badge rounded-pill" style="background: #fef3c7; color: #d97706; font-size: 0.72rem; font-weight: 600;">
                                    <i class="ri-time-line me-1"></i>Pre-Inscrito
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advertencia -->
                <div class="card border-0 shadow-sm mb-3" style="border-radius: var(--radius-md); border-left: 4px solid #3b82f6 !important; overflow: hidden;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start gap-2">
                            <div style="width: 32px; height: 32px; background: #dbeafe; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="ri-information-line" style="font-size: 1rem; color: #2563eb;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size: 0.85rem; color: #1e293b;">¿Qué sucederá al convertir?</div>
                                <ul class="mb-0 mt-1" style="font-size: 0.8rem; color: #64748b; padding-left: 16px;">
                                    <li>Se generarán matriculaciones en todos los módulos</li>
                                    <li>Se crearán las cuotas según el plan de pago seleccionado</li>
                                    <li>El estado cambiará a <strong class="text-success">Inscrito</strong> permanentemente</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selección de Plan de Pago -->
                <div class="card border-0 shadow-sm mb-3" style="border-radius: var(--radius-md); border-left: 4px solid #0f766e !important; overflow: hidden;">
                    <div class="card-header border-0 py-2 px-3" style="background: #f0fdfa;">
                        <h6 class="mb-0 d-flex align-items-center gap-2" style="font-size: 0.88rem; font-weight: 600; color: #0f766e;">
                            <i class="ri-money-dollar-circle-line"></i>Seleccionar Plan de Pago
                            <span class="text-danger">*</span>
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="planesPagoConversionContainer">
                            <div class="text-center text-muted py-3" style="font-size: 0.82rem;">
                                <div class="spinner-border spinner-border-sm me-2" style="color: #0f766e;"></div>
                                Cargando planes disponibles...
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md); overflow: hidden;">
                    <div class="card-body p-3">
                        <label for="observacionConversion" class="form-label fw-semibold mb-2" style="font-size: 0.82rem; color: #1e293b;">
                            <i class="ri-chat-1-line me-1"></i>Observaciones
                            <span class="text-muted fw-normal" style="font-size: 0.75rem;">(Opcional)</span>
                        </label>
                        <textarea class="form-control" id="observacionConversion" rows="2"
                            placeholder="Agregar observaciones sobre la conversión..."
                            style="border-radius: var(--radius-sm); border: 1px solid #e2e8f0; font-size: 0.85rem;"></textarea>
                    </div>
                </div>

            </div>

            <div class="modal-footer border-0 py-3 px-4" style="background: white; border-top: 1px solid #e2e8f0 !important;">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                    style="border-radius: var(--radius-sm); padding: 8px 20px; border: 1px solid #e2e8f0;">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" class="btn" id="confirmarConversionBtn"
                    style="background: #0f766e; color: white; border: none; padding: 8px 24px; border-radius: var(--radius-sm); font-weight: 600;">
                    <i class="ri-check-double-line me-1"></i>Confirmar Conversión
                </button>
            </div>

        </div>
    </div>
</div>
