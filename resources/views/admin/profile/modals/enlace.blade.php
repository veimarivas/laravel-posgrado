<!-- Modal para enlace y QR (simple) -->
<div class="modal fade" id="enlaceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enlace Personalizado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">Información del Programa</h6>
                        <div id="modalProgramaInfo"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <h6 class="mb-3">Código QR</h6>
                        <div id="modalQRCode" class="mb-3"></div>
                        <small class="text-muted">Escanea para acceder directamente</small>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label fw-medium">Enlace Personalizado</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="modalEnlace" readonly>
                        <button class="btn btn-outline-primary" id="copyEnlaceBtn">
                            <i class="ri-file-copy-line"></i> Copiar
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" id="visitLinkBtn" target="_blank" class="btn btn-primary">
                    <i class="ri-external-link-line me-1"></i> Visitar Enlace
                </a>
            </div>
        </div>
    </div>
</div>
