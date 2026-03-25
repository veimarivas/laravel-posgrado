<div class="modal fade" id="modalDetallePago" tabindex="-1" aria-labelledby="modalDetallePagoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="ri-receipt-line fs-4"></i>
                    <h5 class="modal-title mb-0" id="modalDetallePagoLabel">Detalle del Recibo</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div id="detallePagoContenido">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3 text-muted">Cargando detalles del pago...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cerrar
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="btnImprimirDetalle">
                    <i class="ri-printer-line me-1"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>
