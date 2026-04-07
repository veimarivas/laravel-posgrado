<div class="modal fade modal-comp modal-reject" id="modalRechazar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-close-circle-line me-2"></i>Rechazar Comprobante
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formRechazar">
                @csrf
                <input type="hidden" id="rechazar_comprobante_id" name="comprobante_id">
                <div class="modal-body text-center">
                    <div class="reject-icon-circle">
                        <i class="ri-alert-line"></i>
                    </div>
                    <h5 style="font-family:'Outfit',sans-serif;font-weight:600;margin-bottom:4px;">¿Estás seguro de rechazar este comprobante?</h5>
                    <p class="text-muted mb-3" style="font-size:.88rem;">Esta acción notificará al estudiante sobre el rechazo.</p>
                    <div class="text-start">
                        <label for="motivo_rechazo" class="form-label-sm" style="font-size:.75rem;font-weight:600;color:var(--comp-text);margin-bottom:4px;">Motivo del rechazo *</label>
                        <textarea class="form-control form-control-sm-custom" id="motivo_rechazo" name="motivo_rechazo" rows="3" required
                                  placeholder="Indica el motivo del rechazo..."></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-sm" style="background:var(--comp-danger);color:white;">
                        <i class="ri-close-circle-line me-1"></i>Rechazar Comprobante
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
