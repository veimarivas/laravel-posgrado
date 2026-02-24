<!-- Modal para enlace con plan de pago -->
<div class="modal fade" id="enlacePlanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="ri-money-dollar-circle-line me-2"></i>
                    Enlace con Plan de Pago
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Información de la oferta -->
                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 me-3">
                            <i class="ri-information-line fs-4"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="alert-heading" id="modalOfertaTitulo">Oferta Académica</h6>
                            <p class="mb-1"><strong>Código:</strong> <span id="modalOfertaCodigo">-</span></p>
                            <p class="mb-1"><strong>Programa:</strong> <span id="modalOfertaPrograma">-</span></p>
                            <p class="mb-0"><strong>Asesor:</strong> <span id="modalOfertaAsesor">-</span></p>
                        </div>
                    </div>
                </div>

                <!-- Selección de plan de pago -->
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="ri-money-dollar-circle-line me-2"></i>
                        Selecciona un Plan de Pago
                    </h6>
                    <div id="planesPagoEnlaceContainer">
                        <div class="text-center py-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2 text-muted">Cargando planes de pago disponibles...</p>
                        </div>
                    </div>
                </div>

                <!-- Enlace generado -->
                <div class="mb-4" id="enlaceGeneradoContainer" style="display: none;">
                    <h6 class="mb-3">
                        <i class="ri-link me-2"></i>
                        Enlace Generado
                    </h6>
                    <div class="generated-link mb-3" id="generatedLink">
                        <div class="d-flex justify-content-between align-items-center">
                            <span id="linkText">-</span>
                            <button class="btn btn-sm btn-outline-primary" id="copyGeneratedLink">
                                <i class="ri-file-copy-line"></i>
                            </button>
                        </div>
                    </div>

                    <!-- QR Code -->
                    <div class="text-center mt-3">
                        <h6 class="mb-2">
                            <i class="ri-qr-code-line me-2"></i>
                            Código QR
                        </h6>
                        <div id="qrCodeContainer" class="mb-3">
                            <!-- QR se insertará aquí -->
                        </div>
                        <small class="text-muted">Escanea el código para acceder al formulario con el plan de pago
                            seleccionado</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" id="visitPlanLinkBtn" target="_blank" class="btn btn-primary" style="display: none;">
                    <i class="ri-external-link-line me-1"></i> Visitar Enlace
                </a>
            </div>
        </div>
    </div>
</div>
