@if ($tipo == 'edit')
    <div class="row mb-4">
        <!-- Portada -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <i class="ri-image-line me-1"></i> Portada del Programa
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img id="edit_preview_portada" src="#" alt="Vista previa"
                            class="img-fluid rounded shadow-sm"
                            style="max-height: 180px; object-fit: contain; display: none;">
                        <div id="edit_placeholder_portada" class="text-muted py-4" style="display: block;">
                            <i class="ri-upload-cloud-2-line fs-1"></i><br>
                            <small>Seleccione una imagen para previsualizar</small>
                        </div>
                    </div>
                    <input type="file" name="portada" id="edit_portada_input" class="form-control"
                        accept="image/png,image/jpeg,image/jpg">
                    <div class="form-text">Formatos: PNG, JPG (máx. 2 MB)</div>
                </div>
            </div>
        </div>
        <!-- Certificado -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <i class="ri-certificate-line me-1"></i> Diseño del Certificado
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img id="edit_preview_certificado" src="#" alt="Vista previa"
                            class="img-fluid rounded shadow-sm"
                            style="max-height: 180px; object-fit: contain; display: none;">
                        <div id="edit_placeholder_certificado" class="text-muted py-4" style="display: block;">
                            <i class="ri-upload-cloud-2-line fs-1"></i><br>
                            <small>Seleccione una imagen para previsualizar</small>
                        </div>
                    </div>
                    <input type="file" name="certificado" id="edit_certificado_input" class="form-control"
                        accept="image/png,image/jpeg,image/jpg">
                    <div class="form-text">Formatos: PNG, JPG (máx. 2 MB)</div>
                </div>
            </div>
        </div>
    </div>
@elseif($tipo == 'fase2')
    <div class="row mb-4">
        <!-- Portada -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <i class="ri-image-line me-1"></i> Portada del Programa
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img id="f2_preview_portada" src="#" alt="Vista previa"
                            class="img-fluid rounded shadow-sm"
                            style="max-height: 180px; object-fit: contain; display: none;">
                        <div id="f2_placeholder_portada" class="text-muted py-4" style="display: block;">
                            <i class="ri-upload-cloud-2-line fs-1"></i><br>
                            <small>Seleccione una imagen para previsualizar</small>
                        </div>
                    </div>
                    <input type="file" name="portada" id="f2_portada_input" class="form-control"
                        accept="image/png,image/jpeg,image/jpg">
                    <div class="form-text">Formatos: PNG, JPG (máx. 2 MB)</div>
                </div>
            </div>
        </div>
        <!-- Certificado -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <i class="ri-certificate-line me-1"></i> Diseño del Certificado
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img id="f2_preview_certificado" src="#" alt="Vista previa"
                            class="img-fluid rounded shadow-sm"
                            style="max-height: 180px; object-fit: contain; display: none;">
                        <div id="f2_placeholder_certificado" class="text-muted py-4" style="display: block;">
                            <i class="ri-upload-cloud-2-line fs-1"></i><br>
                            <small>Seleccione una imagen para previsualizar</small>
                        </div>
                    </div>
                    <input type="file" name="certificado" id="f2_certificado_input" class="form-control"
                        accept="image/png,image/jpeg,image/jpg">
                    <div class="form-text">Formatos: PNG, JPG (máx. 2 MB)</div>
                </div>
            </div>
        </div>
    </div>
@endif
