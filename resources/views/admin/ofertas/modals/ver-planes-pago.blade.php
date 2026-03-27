{{-- Modal Ver Planes de Pago --}}
<div class="modal fade" id="modalVerPlanesPago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">

            <div class="modal-header border-0 bg-primary text-white py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-white bg-opacity-25 text-white rounded-2">
                            <i class="ri-bank-card-line fs-18"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-semibold">Planes de Pago</h5>
                        <div class="opacity-75" style="font-size:.75rem;">
                            Oferta: <span id="planes_oferta_codigo" class="fw-semibold"></span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-3">

                {{-- Loading --}}
                <div class="text-center py-5" id="loadingPlanes">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2 text-muted small">Cargando planes de pago...</p>
                </div>

                {{-- Planes container --}}
                <div id="planesPagoContainer" style="display:none;"></div>

                {{-- Sin planes --}}
                <div id="sinPlanes" class="text-center py-5" style="display:none;">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-light text-secondary rounded-circle">
                            <i class="ri-inbox-line fs-2"></i>
                        </div>
                    </div>
                    <h5 class="mb-1">No hay planes de pago</h5>
                    <p class="text-muted small mb-0">Esta oferta académica no tiene planes de pago configurados.</p>
                </div>

            </div>

            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
