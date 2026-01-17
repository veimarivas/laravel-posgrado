<script>
    // === AGREGAR PLAN DE PAGO ===
    // === Cargar planes NO usados en la oferta ===
    $(document).on('click', '.addPlanPagoBtn', function() {
        const ofertaId = $(this).data('oferta-id');
        $('#plan_oferta_id').val(ofertaId);

        // Obtener planes ya usados en esta oferta
        $.get(`/admin/ofertas/${ofertaId}/datos`, function(oferta) {
            const usados = new Set();
            if (oferta.plan_concepto && Array.isArray(oferta.plan_concepto)) {
                oferta.plan_concepto.forEach(pc => usados.add(pc.planes_pago_id));
            }

            // Llenar select con planes NO usados
            let opts = '<option value="">Seleccione un plan</option>';
            PLANES_PAGOS.forEach(plan => {
                if (!usados.has(plan.id)) {
                    opts += `<option value="${plan.id}">${plan.nombre}</option>`;
                }
            });
            $('#plan_planes_pago_id').html(opts);

            // Limpiar conceptos
            $('#plan_conceptos_container').empty();
            $('#modalAgregarPlanPago').modal('show');
        });
    });

    // Agregar concepto dinámico
    $('#addConceptoBtn').on('click', function() {
        const idx = $('#plan_conceptos_container .concepto-item').length;
        let opts = '<option value="">Concepto</option>';
        CONCEPTOS.forEach(c => opts += `<option value="${c.id}">${c.nombre}</option>`);
        const html = `
        <div class="row mb-2 concepto-item">
            <div class="col-md-5">
                <select name="conceptos[${idx}][concepto_id]" class="form-control" required>${opts}</select>
            </div>
            <div class="col-md-2">
                <input type="number" name="conceptos[${idx}][n_cuotas]" class="form-control" min="1" value="1" required>
            </div>
            <div class="col-md-3">
                <input type="number" step="0.01" name="conceptos[${idx}][pago_bs]" class="form-control" min="0" value="0" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-outline-danger remove-concepto">🗑️</button>
            </div>
        </div>`;
        $('#plan_conceptos_container').append(html);
    });

    $(document).on('click', '.remove-concepto', function() {
        $(this).closest('.concepto-item').remove();
    });

    // Submit nuevo plan
    $('#addPlanPagoForm').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.post("{{ route('admin.ofertas.agregar-plan-pago') }}", formData)
            .done(function(res) {
                alert(res.msg);
                if (res.success) {
                    $('#modalAgregarPlanPago').modal('hide');
                    location.reload();
                }
            })
            .fail(function(xhr) {
                alert(xhr.responseJSON?.msg || 'Error al guardar el plan.');
            });
    });
</script>
