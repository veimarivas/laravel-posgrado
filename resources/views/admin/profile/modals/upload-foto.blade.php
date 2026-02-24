<!-- Modal para subir foto -->
<div class="modal fade" id="uploadFotoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Foto de Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="uploadFotoForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="fotografia" class="form-label">Seleccionar imagen</label>
                        <input type="file" class="form-control" id="fotografia" name="fotografia" accept="image/*"
                            required>
                        <div class="form-text">
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="submitFotoBtn">
                    <i class="ri-upload-cloud-line me-1"></i> Subir Foto
                </button>
            </div>
        </div>
    </div>
</div>
