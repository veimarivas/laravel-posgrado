<!-- Modal para previsualizar documentos -->
<div class="modal fade" id="modalPreviewDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Previsualización del Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="height: 70vh;">
                <iframe id="pdfPreview" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" id="downloadPdf" class="btn btn-primary" download>
                    <i class="ri-download-line me-1"></i> Descargar
                </a>
            </div>
        </div>
    </div>
</div>
