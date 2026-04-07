<!-- Modal para enlace y QR (simple) -->
<div class="modal fade modal-conc" id="enlaceModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-share-forward-line me-2"></i>Tu Enlace Personalizado
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Programa info -->
                <div class="enlace-modal-card">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <div class="enlace-info-section">
                                <div class="enlace-info-header">
                                    <div class="enlace-info-icon">
                                        <i class="ri-graduation-cap-line"></i>
                                    </div>
                                    <div>
                                        <div class="enlace-info-title">Programa</div>
                                    </div>
                                </div>
                                <div id="modalProgramaInfo"></div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="enlace-qr-section">
                                <div class="enlace-qr-badge">
                                    <i class="ri-qr-scan-2-line"></i>
                                </div>
                                <div id="modalQRCode" class="enlace-qr-image"></div>
                                <p class="enlace-qr-hint">Escanea para acceder</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enlace -->
                <div class="enlace-url-card">
                    <div class="enlace-url-header">
                        <i class="ri-link"></i>
                        <span>Tu enlace personalizado para tu cargo</span>
                    </div>
                    <div class="enlace-url-box">
                        <input type="text" class="enlace-url-input" id="modalEnlace" readonly>
                        <button class="enlace-url-copy" id="copyEnlaceBtn">
                            <i class="ri-file-copy-line"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-enlace-modal btn-enlace-close" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i> Cerrar
                </button>
                <a href="#" id="visitLinkBtn" target="_blank" class="btn-enlace-modal btn-enlace-open">
                    <i class="ri-global-line"></i> Abrir Enlace
                </a>
            </div>
        </div>
    </div>
</div>
