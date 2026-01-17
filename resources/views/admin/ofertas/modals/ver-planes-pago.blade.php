<!-- Modal Ver Planes de Pago -->
<div class="modal fade" id="modalVerPlanesPago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-money-dollar-circle-line me-2"></i>
                    Planes de Pago - <span id="planes_oferta_codigo"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3" id="loadingPlanes">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando planes de pago...</p>
                </div>

                <div id="planesPagoContainer" style="display: none;">
                    <!-- Aquí se cargarán los planes dinámicamente -->
                </div>

                <div id="sinPlanes" class="text-center py-5" style="display: none;">
                    <i class="ri-inbox-line fs-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">No hay planes de pago registrados</h5>
                    <p class="text-muted">Esta oferta académica no tiene planes de pago configurados.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
