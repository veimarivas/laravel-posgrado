<!-- Modal Agregar Plan de Pago (Fase 2) -->
<div class="modal fade" id="modalAgregarPlanPago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Nuevo Plan de Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addPlanPagoForm">
                    @csrf
                    <input type="hidden" name="oferta_id" id="plan_oferta_id">

                    <div class="mb-3">
                        <label class="form-label">Plan de Pago *</label>
                        <select name="planes_pago_id" id="plan_planes_pago_id" class="form-control" required>
                            <option value="">Seleccione un plan disponible</option>
                            <!-- Se llenará dinámicamente -->
                        </select>
                    </div>

                    <div id="plan_conceptos_container"></div>

                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-outline-primary" id="addConceptoBtn">➕ Agregar
                            Concepto</button>
                        <button type="submit" class="btn btn-info mt-2">Guardar Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
