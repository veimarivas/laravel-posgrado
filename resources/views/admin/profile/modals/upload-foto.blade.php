<!-- Modal para subir foto -->
<div class="modal fade modal-profile" id="uploadFotoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-camera-line me-2"></i>Cambiar Foto de Perfil
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="uploadFotoForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="fotografia" class="form-label fw-semibold" style="font-size:.85rem;">Seleccionar imagen</label>
                        <input type="file" class="form-control" id="fotografia" name="fotografia" accept="image/*"
                            required style="border-radius:var(--radius-sm);border:1px solid var(--prof-border);">
                        <div class="form-text" style="font-size:.75rem;">
                            Formatos permitidos: JPG, PNG, GIF, WEBP. Tamaño máximo: 5MB
                        </div>
                    </div>

                    <div id="imagePreview" class="text-center mb-3" style="display: none;">
                        <img id="previewImage" src="#" alt="Vista previa" class="img-fluid rounded"
                            style="max-height: 200px;">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-sm" id="submitFotoBtn"
                        style="background:var(--prof-primary);color:white;">
                    <i class="ri-upload-cloud-line me-1"></i> Subir Foto
                </button>
            </div>
        </div>
    </div>
</div>
