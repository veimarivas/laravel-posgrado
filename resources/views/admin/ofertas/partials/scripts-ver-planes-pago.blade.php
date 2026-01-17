<script>
    // === VER PLANES DE PAGO ===
    $(document).on('click', '.verPlanesPagoBtn', function() {
        const ofertaId = $(this).data('oferta-id');
        const ofertaCodigo = $(this).data('oferta-codigo');

        // Actualizar título del modal
        $('#planes_oferta_codigo').text(ofertaCodigo);

        // Mostrar loading, ocultar otros
        $('#loadingPlanes').show();
        $('#planesPagoContainer').hide();
        $('#sinPlanes').hide();

        // Abrir modal
        $('#modalVerPlanesPago').modal('show');

        // Obtener planes de pago via AJAX
        $.ajax({
            url: `/admin/ofertas/${ofertaId}/planes-pago`,
            method: 'GET',
            success: function(res) {
                $('#loadingPlanes').hide();

                if (res.success && res.planes.length > 0) {
                    renderizarPlanesPago(res.planes);
                    $('#planesPagoContainer').show();
                } else {
                    $('#sinPlanes').show();
                }
            },
            error: function(xhr) {
                $('#loadingPlanes').hide();
                $('#sinPlanes').show();
                $('#sinPlanes').html(`
                <i class="ri-error-warning-line fs-1 text-danger"></i>
                <h5 class="mt-3 text-danger">Error al cargar planes</h5>
                <p class="text-muted">No se pudieron cargar los planes de pago.</p>
            `);
            }
        });
    });

    // Función simplificada para renderizar planes de pago (modal Ver)
    function renderizarPlanesPago(planes) {
        let html = '';

        if (planes.length === 0) {
            html = `
        <div class="text-center py-5">
            <i class="ri-inbox-line fs-1 text-muted"></i>
            <h5 class="mt-3 text-muted">No hay planes de pago registrados</h5>
            <p class="text-muted">Esta oferta académica no tiene planes de pago configurados.</p>
        </div>
        `;
        } else {
            planes.forEach((plan, index) => {
                let totalPlan = 0;

                html += `
            <div class="card mb-4 ${index === 0 ? 'border-primary' : 'border-secondary'}">
                <div class="card-header ${index === 0 ? 'bg-primary' : 'bg-secondary'} text-white">
                    <h6 class="mb-0 d-flex justify-content-between align-items-center">
                        <span>
                            <i class="ri-bank-card-line me-2"></i>
                            ${plan.nombre}
                        </span>
                        <span class="badge bg-light ${index === 0 ? 'text-primary' : 'text-secondary'}">
                            ${plan.conceptos.length} concepto(s)
                        </span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th class="text-center">N° Cuotas</th>
                                    <th class="text-end">Total del Concepto (Bs)</th>
                                </tr>
                            </thead>
                            <tbody>`;

                plan.conceptos.forEach((concepto) => {
                    const totalConcepto = parseFloat(concepto.total_concepto.replace(',', '')) || 0;
                    totalPlan += totalConcepto;

                    html += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <i class="ri-price-tag-3-line ${index === 0 ? 'text-primary' : 'text-secondary'}"></i>
                            </div>
                            <span>${concepto.concepto_nombre}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge ${index === 0 ? 'bg-primary' : 'bg-secondary'}">
                            ${concepto.n_cuotas}
                        </span>
                    </td>
                    <td class="text-end fw-bold ${index === 0 ? 'text-primary' : 'text-secondary'}">
                        ${totalConcepto.toFixed(2)}
                    </td>
                </tr>
                `;
                });

                html += `
                            </tbody>
                            <tfoot>
                                <tr class="border-top">
                                    <td colspan="2" class="text-end fw-bold">Total Inversión:</td>
                                    <td class="text-end fw-bold ${index === 0 ? 'text-success' : 'text-dark'}">
                                        ${totalPlan.toFixed(2)} Bs
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            `;
            });
        }

        $('#planesPagoContainer').html(html);

        // Refrescar feather icons
        if (typeof window.feather !== 'undefined') {
            window.feather.replace();
        }
    }

    // Refrescar feather icons cuando se abra el modal
    $('#modalVerPlanesPago').on('shown.bs.modal', function() {
        if (typeof window.feather !== 'undefined') {
            window.feather.replace();
        }
    });
</script>
