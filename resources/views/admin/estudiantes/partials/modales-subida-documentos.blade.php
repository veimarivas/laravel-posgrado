<!-- Modal para subir/reemplazar Carnet -->
<div class="modal fade" id="modalSubirCarnet" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subir Carnet de Identidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSubirCarnet" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="carnet_pdf" class="form-label">Seleccionar archivo PDF</label>
                        <input type="file" class="form-control" id="carnet_pdf" name="carnet_pdf" accept=".pdf"
                            required>
                        <div class="form-text">Tamaño máximo: 2MB. Solo se permiten archivos PDF.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para subir/reemplazar Certificado de Nacimiento -->
<div class="modal fade" id="modalSubirCertificadoNacimiento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subir Certificado de Nacimiento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSubirCertificadoNacimiento" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="certificado_nacimiento_pdf" class="form-label">Seleccionar archivo PDF</label>
                        <input type="file" class="form-control" id="certificado_nacimiento_pdf"
                            name="certificado_nacimiento_pdf" accept=".pdf" required>
                        <div class="form-text">Tamaño máximo: 2MB. Solo se permiten archivos PDF.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para subir/reemplazar Título Académico -->
<div class="modal fade" id="modalSubirTituloAcademico" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subir Título Académico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSubirTituloAcademico" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="titulo_academico_pdf" class="form-label">Seleccionar archivo PDF</label>
                        <input type="file" class="form-control" id="titulo_academico_pdf" name="titulo_academico_pdf"
                            accept=".pdf" required>
                        <div class="form-text">Tamaño máximo: 2MB. Solo se permiten archivos PDF.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para subir/reemplazar Provisión Nacional -->
<div class="modal fade" id="modalSubirProvisionNacional" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subir Título de Provisión Nacional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSubirProvisionNacional" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="provision_nacional_pdf" class="form-label">Seleccionar archivo PDF</label>
                        <input type="file" class="form-control" id="provision_nacional_pdf"
                            name="provision_nacional_pdf" accept=".pdf" required>
                        <div class="form-text">Tamaño máximo: 2MB. Solo se permiten archivos PDF.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir</button>
                </div>
            </form>
        </div>
    </div>
</div>
