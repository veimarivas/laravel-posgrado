<div class="modal fade" id="modalReciboGenerado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pago Registrado Exitosamente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="ri-checkbox-circle-line text-success display-4"></i>
                </div>
                <h4 class="text-success mb-3">¡Pago Registrado!</h4>
                <p class="mb-1">Recibo N°: <strong id="recibo-numero"></strong></p>
                <p class="mb-1">Monto: <strong id="recibo-monto"></strong> Bs</p>
                <p class="mb-3">Fecha: <strong id="recibo-fecha"></strong></p>
                <div class="alert alert-info">
                    <i class="ri-information-line me-1"></i>
                    El recibo se ha generado correctamente. Puede descargarlo ahora.
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" id="descargar-recibo" class="btn btn-primary" target="_blank">
                    <i class="ri-download-line me-1"></i> Descargar Recibo
                </a>
                <button type="button" class="btn btn-success" onclick="location.reload()">
                    <i class="ri-refresh-line me-1"></i> Actualizar Página
                </button>
            </div>
        </div>
    </div>
</div>
