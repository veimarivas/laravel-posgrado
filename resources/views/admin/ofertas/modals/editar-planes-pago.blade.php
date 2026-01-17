<!-- Modal Editar Planes de Pago -->
<div class="modal fade" id="modalEditarPlanesPago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-edit-line me-2"></i>
                    Editar Planes de Pago - <span id="editar_planes_oferta_codigo"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3" id="loadingEditarPlanes">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando planes de pago para edición...</p>
                </div>

                <div id="editarPlanesContainer" style="display: none;">
                    <!-- Aquí se cargarán los planes dinámicamente con controles de edición -->
                </div>

                <div id="sinPlanesEditar" class="text-center py-5" style="display: none;">
                    <i class="ri-inbox-line fs-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">No hay planes de pago registrados</h5>
                    <p class="text-muted">Esta oferta académica no tiene planes de pago configurados.</p>
                    <button type="button" class="btn btn-info addPlanPagoBtnFromEdit" data-oferta-id=""
                        data-bs-dismiss="modal">
                        <i class="ri-add-line me-1"></i> Agregar Primer Plan
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="guardarCambiosPlanesBtn" style="display: none;">
                    <i class="ri-save-line me-1"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>
