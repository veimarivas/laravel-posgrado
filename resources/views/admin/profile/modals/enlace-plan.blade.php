<!-- Modal para enlace con plan de pago -->
<div class="modal fade modal-conc" id="enlacePlanModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-coupon-line me-2"></i>
                    Generar Enlace con Plan de Pago
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Info de la oferta -->
                <div class="plan-modal-card">
                    <div class="plan-modal-header">
                        <div class="plan-modal-icon">
                            <i class="ri-price-tag-3-line"></i>
                        </div>
                        <div class="plan-modal-info">
                            <div class="plan-modal-label">Oferta Académica</div>
                            <div class="plan-modal-value" id="modalOfertaCodigo">-</div>
                        </div>
                    </div>
                    <div class="plan-modal-divider"></div>
                    <div class="plan-modal-body">
                        <div class="plan-modal-field">
                            <div class="plan-modal-field-icon"><i class="ri-graduation-cap-line"></i></div>
                            <div>
                                <div class="plan-modal-field-label">Programa</div>
                                <div class="plan-modal-field-value" id="modalOfertaPrograma">-</div>
                            </div>
                        </div>
                        <div class="plan-modal-field">
                            <div class="plan-modal-field-icon"><i class="ri-user-star-line"></i></div>
                            <div>
                                <div class="plan-modal-field-label">Asesor</div>
                                <div class="plan-modal-field-value" id="modalOfertaAsesor">-</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selección de plan -->
                <div class="plan-select-section">
                    <div class="plan-select-header">
                        <div class="plan-select-icon">
                            <i class="ri-stack-line"></i>
                        </div>
                        <div>
                            <div class="plan-select-title">Selecciona un Plan de Pago</div>
                            <div class="plan-select-subtitle">Elige el plan que se incluirá en el enlace</div>
                        </div>
                    </div>
                    <div id="planesPagoEnlaceContainer">
                        <div class="plan-loading">
                            <div class="plan-loading-spinner"></div>
                            <p>Cargando planes de pago disponibles...</p>
                        </div>
                    </div>
                </div>

                <!-- Enlace generado -->
                <div class="plan-result-section" id="enlaceGeneradoContainer" style="display: none;">
                    <div class="plan-result-header">
                        <div class="plan-result-icon">
                            <i class="ri-checkbox-circle-line"></i>
                        </div>
                        <div>
                            <div class="plan-result-title">Enlace Generado con Éxito</div>
                            <div class="plan-result-subtitle">Comparte este enlace con el estudiante</div>
                        </div>
                    </div>
                    <div class="plan-result-link">
                        <div class="plan-result-url" id="linkText">-</div>
                        <button class="plan-result-copy" id="copyGeneratedLink" title="Copiar enlace">
                            <i class="ri-file-copy-line"></i>
                        </button>
                    </div>
                    <div class="plan-result-qr">
                        <div class="plan-qr-label">
                            <i class="ri-qr-scan-2-line"></i> Código QR
                        </div>
                        <div id="qrCodeContainer" class="plan-qr-box"></div>
                        <p class="plan-qr-hint">Escanea para acceder al formulario con el plan seleccionado</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-enlace-modal btn-enlace-close" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i> Cerrar
                </button>
                <a href="#" id="visitPlanLinkBtn" target="_blank" class="btn-enlace-modal btn-enlace-open" style="display:none;">
                    <i class="ri-global-line"></i> Abrir Enlace
                </a>
            </div>
        </div>
    </div>
</div>
