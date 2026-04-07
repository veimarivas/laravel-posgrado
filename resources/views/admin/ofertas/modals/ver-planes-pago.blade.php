{{-- Modal Ver Planes de Pago --}}
<div class="modal fade" id="modalVerPlanesPago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow" style="border-radius: var(--radius-lg); overflow: hidden;">

            <div class="modal-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #0f766e 0%, #0d5f59 100%); color: #fff;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 42px; height: 42px; background: rgba(255,255,255,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="ri-bank-card-line" style="font-size: 1.3rem; color: #fff;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-semibold" style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; color: #fff;">Planes de Pago</h5>
                        <div style="opacity: 0.85; font-size: 0.78rem; color: #fff;">
                            Oferta: <span id="planes_oferta_codigo" class="fw-semibold" style="font-family: monospace; background: rgba(255,255,255,0.2); padding: 1px 8px; border-radius: 4px; color: #fff;"></span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-3" style="background: var(--ofertas-surface);">

                {{-- Loading --}}
                <div class="text-center py-5" id="loadingPlanes">
                    <div class="spinner-border" style="color: var(--ofertas-primary);"></div>
                    <p class="mt-2 text-muted small">Cargando planes de pago...</p>
                </div>

                {{-- Planes container --}}
                <div id="planesPagoContainer" style="display:none;"></div>

                {{-- Sin planes --}}
                <div id="sinPlanes" class="text-center py-5" style="display:none;">
                    <div style="width: 72px; height: 72px; margin: 0 auto 16px; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="ri-inbox-line" style="font-size: 2rem; color: #ef4444;"></i>
                    </div>
                    <h5 class="mb-1" style="font-family: 'Outfit', sans-serif;">No hay planes de pago</h5>
                    <p class="text-muted small mb-0">Esta oferta académica no tiene planes de pago configurados.</p>
                </div>

            </div>

            <div class="modal-footer border-0 py-3 px-4" style="background: white; border-top: 1px solid var(--ofertas-border) !important;">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: var(--radius-sm); padding: 8px 20px;">
                    <i class="ri-close-line me-1"></i>Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
