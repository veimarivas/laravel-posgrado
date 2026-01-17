<script>
    // === EDICIÓN DE PLANES DE PAGO ===
    $(document).on('click', '.editarPlanesPagoBtn', function() {
        const ofertaId = $(this).data('oferta-id');
        const ofertaCodigo = $(this).data('oferta-codigo');

        // Actualizar título del modal
        $('#editar_planes_oferta_codigo').text(ofertaCodigo);

        // Mostrar loading, ocultar otros
        $('#loadingEditarPlanes').show();
        $('#editarPlanesContainer').hide();
        $('#sinPlanesEditar').hide();
        $('#guardarCambiosPlanesBtn').hide();

        // Guardar el ID de la oferta en el botón de guardar
        $('#guardarCambiosPlanesBtn').data('oferta-id', ofertaId);

        // Abrir modal
        $('#modalEditarPlanesPago').modal('show');

        // Obtener datos de la oferta para edición
        $.ajax({
            url: `/admin/ofertas/${ofertaId}/datos`,
            method: 'GET',
            success: function(oferta) {
                $('#loadingEditarPlanes').hide();

                if (oferta.plan_concepto && oferta.plan_concepto.length > 0) {
                    renderizarPlanesParaEdicion(oferta.plan_concepto);
                    $('#editarPlanesContainer').show();
                    $('#guardarCambiosPlanesBtn').show();
                } else {
                    $('#sinPlanesEditar').show();
                    $('#sinPlanesEditar .addPlanPagoBtnFromEdit').data('oferta-id', ofertaId);
                }
            },
            error: function(xhr) {
                $('#loadingEditarPlanes').hide();
                $('#sinPlanesEditar').show();
                $('#sinPlanesEditar').html(`
                <i class="ri-error-warning-line fs-1 text-danger"></i>
                <h5 class="mt-3 text-danger">Error al cargar datos</h5>
                <p class="text-muted">No se pudieron cargar los planes de pago para edición.</p>
            `);
            }
        });
    });

    // Función para renderizar planes de pago para edición (VERSIÓN CORREGIDA)
    function renderizarPlanesParaEdicion(planConcepto) {
        // Agrupar por plan de pago
        const planesAgrupados = {};

        planConcepto.forEach(pc => {
            const planId = pc.planes_pago_id;
            const planNombre = pc.plan_pago?.nombre || `Plan ${planId}`;

            if (!planesAgrupados[planId]) {
                planesAgrupados[planId] = {
                    nombre: planNombre,
                    conceptos: []
                };
            }

            // Guardar el ID del plan_concepto para edición individual
            planesAgrupados[planId].conceptos.push({
                id: pc.id, // ID del plan_concepto (relación entre oferta y concepto)
                concepto_id: pc.concepto_id,
                concepto_nombre: pc.concepto?.nombre || 'Sin concepto',
                n_cuotas: pc.n_cuotas,
                pago_bs: pc.pago_bs
            });
        });

        let html = '';
        Object.entries(planesAgrupados).forEach(([planId, planData], planIndex) => {
            html += `
        <div class="card mb-4 plan-editar-card" data-plan-id="${planId}">
            <div class="card-header ${planIndex === 0 ? 'bg-primary' : 'bg-secondary'} text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center w-100">
                    <i class="ri-bank-card-line me-2"></i>
                    <strong class="plan-nombre-display">${planData.nombre}</strong>
                    <input type="hidden" class="plan-nombre-hidden" value="${planData.nombre}">
                    <span class="badge bg-light ${planIndex === 0 ? 'text-primary' : 'text-secondary'} ms-2">
                        ${planData.conceptos.length} concepto(s)
                    </span>
                </div>
                <button type="button" class="btn btn-sm btn-success agregarConceptoPlanBtn" data-plan-id="${planId}">
                    <i class="ri-add-line"></i> Concepto
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="40%">Concepto</th>
                                <th width="20%">N° Cuotas</th>
                                <th width="25%">Monto por Cuota (Bs)</th>
                                <th width="15%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="conceptos_plan_${planId}">`;

            planData.conceptos.forEach((concepto) => {
                html += `
                            <tr class="concepto-item-editar" data-concepto-id="${concepto.concepto_id}" data-plan-concepto-id="${concepto.id || ''}">
                                <td>
                                    <select class="form-control form-control-sm concepto-select-editar">
                                        <option value="">Seleccionar concepto</option>
                                        ${CONCEPTOS.map(c => 
                                            `<option value="${c.id}" ${c.id == concepto.concepto_id ? 'selected' : ''}>${c.nombre}</option>`
                                        ).join('')}
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm n-cuotas-editar" 
                                           value="${concepto.n_cuotas}" min="1">
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" class="form-control monto-cuota-editar" 
                                               value="${concepto.pago_bs}" step="0.01" min="0">
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-danger eliminarConceptoBtn">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>`;
            });

            html += `
                        </tbody>
                    </table>
                </div>
            </div>
        </div>`;
        });

        $('#editarPlanesContainer').html(html);
    }

    // Evento para agregar un nuevo concepto a un plan (VERSIÓN CORREGIDA)
    $(document).on('click', '.agregarConceptoPlanBtn', function() {
        const planId = $(this).data('plan-id');
        const tbody = $(`#conceptos_plan_${planId}`);
        const newIndex = tbody.find('tr').length;

        // Obtener el nombre del plan para mostrar
        const planNombre = $(this).closest('.plan-editar-card').find('.plan-nombre-display').text();

        const html = `
    <tr class="concepto-item-editar" data-concepto-id="" data-plan-concepto-id="new">
        <td>
            <select class="form-control form-control-sm concepto-select-editar">
                <option value="">Seleccionar concepto</option>
                ${CONCEPTOS.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('')}
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm n-cuotas-editar" value="1" min="1">
        </td>
        <td>
            <div class="input-group input-group-sm">
                <span class="input-group-text">Bs</span>
                <input type="number" class="form-control monto-cuota-editar" value="0" step="0.01" min="0">
            </div>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger eliminarConceptoBtn">
                <i class="ri-delete-bin-line"></i>
            </button>
        </td>
    </tr>`;

        tbody.append(html);
    });

    // Evento para eliminar un concepto
    $(document).on('click', '.eliminarConceptoBtn', function() {
        $(this).closest('tr').remove();
    });

    // Evento para guardar los cambios en los planes de pago (VERSIÓN CORREGIDA)
    $('#guardarCambiosPlanesBtn').on('click', function() {
        const ofertaId = $(this).data('oferta-id');

        // Validar que la oferta esté en fase 2
        if (!confirm('¿Está seguro de guardar los cambios en los planes de pago?')) {
            return;
        }

        // Recopilar datos de los planes
        const planesData = [];

        $('.plan-editar-card').each(function() {
            const planId = $(this).data('plan-id');
            const planNombre = $(this).find('.plan-nombre-hidden').val();
            const conceptos = [];

            $(this).find('.concepto-item-editar').each(function() {
                const conceptoId = $(this).find('.concepto-select-editar').val();
                const nCuotas = $(this).find('.n-cuotas-editar').val();
                const pagoBs = $(this).find('.monto-cuota-editar').val();
                const planConceptoId = $(this).data(
                    'plan-concepto-id'); // ID del plan_concepto o 'new'

                if (conceptoId && nCuotas && pagoBs) {
                    conceptos.push({
                        plan_concepto_id: planConceptoId, // Enviar ID si existe
                        concepto_id: conceptoId,
                        n_cuotas: nCuotas,
                        pago_bs: pagoBs
                    });
                }
            });

            if (conceptos.length > 0) {
                planesData.push({
                    planes_pago_id: planId, // ID del plan de pago existente
                    plan_nombre: planNombre, // Nombre del plan (para referencia)
                    conceptos: conceptos
                });
            }
        });

        if (planesData.length === 0) {
            mostrarToast('warning', 'Debe agregar al menos un plan de pago con conceptos.');
            return;
        }

        // Validar que no haya conceptos duplicados en el mismo plan
        let hasDuplicates = false;
        planesData.forEach(plan => {
            const conceptosIds = plan.conceptos.map(c => c.concepto_id);
            const uniqueIds = [...new Set(conceptosIds)];
            if (conceptosIds.length !== uniqueIds.length) {
                hasDuplicates = true;
            }
        });

        if (hasDuplicates) {
            mostrarToast('error', 'No puede haber conceptos duplicados en el mismo plan de pago.');
            return;
        }

        // Mostrar indicador de carga
        const originalText = $(this).html();
        $(this).html('<i class="ri-loader-4-line spin"></i> Guardando...').prop('disabled', true);

        // Enviar datos al backend (ahora con plan_id en lugar de crear nuevo)
        $.ajax({
            url: `/admin/ofertas/${ofertaId}/actualizar-planes-pago`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                planes: planesData
            },
            success: function(res) {
                $('#guardarCambiosPlanesBtn').html(originalText).prop('disabled', false);

                if (res.success) {
                    mostrarToast('success', res.msg);
                    $('#modalEditarPlanesPago').modal('hide');

                    // Actualizar la tabla de ofertas
                    setTimeout(() => {
                        loadResults();
                    }, 1000);
                } else {
                    mostrarToast('error', res.msg || 'Error al actualizar los planes de pago.');
                }
            },
            error: function(xhr) {
                $('#guardarCambiosPlanesBtn').html(originalText).prop('disabled', false);

                if (xhr.status === 422) {
                    mostrarToast('error', xhr.responseJSON?.msg ||
                        'Validación fallida. Verifique los datos.');
                } else {
                    mostrarToast('error', 'Error al actualizar los planes de pago.');
                }
            }
        });
    });

    // Botón para agregar plan desde el modal de "sin planes"
    $(document).on('click', '.addPlanPagoBtnFromEdit', function() {
        const ofertaId = $(this).data('oferta-id');
        $('#modalEditarPlanesPago').modal('hide');

        setTimeout(() => {
            $(`.addPlanPagoBtn[data-oferta-id="${ofertaId}"]`).click();
        }, 300);
    });
</script>
