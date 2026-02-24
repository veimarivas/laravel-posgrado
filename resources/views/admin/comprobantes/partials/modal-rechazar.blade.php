<!-- Modal Rechazar Comprobante -->
<div class="modal fade" id="modalRechazar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rechazar Comprobante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formRechazar">
                @csrf
                <input type="hidden" id="rechazar_comprobante_id" name="comprobante_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="motivo_rechazo" class="form-label">Motivo del rechazo *</label>
                        <textarea class="form-control" id="motivo_rechazo" name="motivo_rechazo" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Rechazar Comprobante</button>
                </div>
            </form>
        </div>
    </div>
</div>
