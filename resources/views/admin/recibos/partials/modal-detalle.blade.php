<div class="modal fade modal-recibo" id="modalDetallePago" tabindex="-1" aria-labelledby="modalDetallePagoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetallePagoLabel">
                    <i class="ri-receipt-line"></i>
                    Detalle del Recibo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detallePagoContenido">
                    <div class="detalle-loading">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p>Cargando detalles del pago...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cerrar
                </button>
                <button type="button" class="btn btn-sm" id="btnImprimirDetalle"
                        style="background: var(--rec-primary); color: white;">
                    <i class="ri-printer-line me-1"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>
