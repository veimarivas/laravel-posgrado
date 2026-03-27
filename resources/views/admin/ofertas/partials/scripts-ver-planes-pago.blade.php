<script>
    // === VER PLANES DE PAGO ===
    $(document).on('click', '.verPlanesPagoBtn', function() {
        const ofertaId     = $(this).data('oferta-id');
        const ofertaCodigo = $(this).data('oferta-codigo');

        $('#planes_oferta_codigo').text(ofertaCodigo);
        $('#loadingPlanes').show();
        $('#planesPagoContainer').hide().empty();
        $('#sinPlanes').hide();

        $('#modalVerPlanesPago').modal('show');

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
            error: function() {
                $('#loadingPlanes').hide();
                $('#sinPlanes').html(`
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                            <i class="ri-error-warning-line fs-2"></i>
                        </div>
                    </div>
                    <h5 class="text-danger mb-1">Error al cargar</h5>
                    <p class="text-muted small mb-0">No se pudieron cargar los planes de pago.</p>
                `).show();
            }
        });
    });

    function fmtDate(d) {
        if (!d) return '?';
        const parts = d.split('-');
        return parts.length === 3 ? `${parts[2]}/${parts[1]}/${parts[0]}` : d;
    }

    function renderizarPlanesPago(planes) {
        const paleta = ['primary', 'success', 'info', 'dark'];
        let html = '<div class="row g-3">';

        planes.forEach((plan, idx) => {
            // Detectar si algún concepto es promoción
            const esPromo   = plan.conceptos.some(c => c.es_promocion);
            const promoVig  = plan.conceptos.some(c => c.promocion_vigente);
            const promoConc = plan.conceptos.find(c => c.es_promocion);
            const fIni      = promoConc?.fecha_inicio_promocion;
            const fFin      = promoConc?.fecha_fin_promocion;

            const color     = esPromo ? 'warning'  : paleta[idx % paleta.length];
            const iconPlan  = esPromo ? 'ri-price-tag-3-line' : 'ri-bank-card-line';
            const borderClr = esPromo ? '#fd7e14'  : `var(--bs-${paleta[idx % paleta.length]})`;
            const headBg    = esPromo ? '#fffbf0'  : '#f8f9fa';

            // Totales
            let totalPlan = 0, totalRegular = 0;
            plan.conceptos.forEach(c => {
                const tp = parseFloat(String(c.total_concepto).replace(',', '')) || 0;
                const tr = c.precio_regular
                    ? (parseFloat(String(c.precio_regular).replace(',', '')) || tp)
                    : tp;
                totalPlan    += tp;
                totalRegular += tr;
            });
            const ahorro = totalRegular - totalPlan;

            html += `
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden"
                     style="border-left:4px solid ${borderClr}!important;">

                    {{-- Cabecera del plan --}}
                    <div class="card-header border-0 py-3 px-3 d-flex align-items-center justify-content-between"
                         style="background:${headBg};">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-${color}-subtle text-${color} rounded-2">
                                    <i class="${iconPlan} fs-18"></i>
                                </div>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.9rem;">${plan.nombre}</div>
                                ${esPromo && fIni ? `
                                <div class="text-muted" style="font-size:.72rem;">
                                    <i class="ri-calendar-line me-1"></i>${fmtDate(fIni)} — ${fmtDate(fFin)}
                                </div>` : ''}
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-1">
                            ${esPromo ? `
                            <span class="badge bg-warning text-dark rounded-pill" style="font-size:.7rem;">
                                <i class="ri-price-tag-3-line me-1"></i>PROMOCIÓN
                            </span>
                            <span class="badge ${promoVig
                                ? 'bg-success-subtle text-success border border-success-subtle'
                                : 'bg-danger-subtle text-danger border border-danger-subtle'
                            } rounded-pill" style="font-size:.65rem;">
                                ${promoVig ? 'Vigente' : 'No vigente'}
                            </span>` : `
                            <span class="badge bg-${color}-subtle text-${color} border border-${color}-subtle rounded-pill"
                                  style="font-size:.7rem;">Plan ${idx + 1}</span>`}
                            <span class="badge bg-light text-muted border rounded-pill"
                                  style="font-size:.65rem;">${plan.conceptos.length} concepto(s)</span>
                        </div>
                    </div>

                    {{-- Tabla de conceptos --}}
                    <div class="card-body p-0">
                        <table class="table align-middle mb-0" style="font-size:.82rem;">
                            <thead>
                                <tr style="background:#fafafa;">
                                    <th class="border-0 py-2 px-3 text-muted fw-semibold" style="font-size:.7rem;">CONCEPTO</th>
                                    <th class="border-0 py-2 text-center text-muted fw-semibold" style="font-size:.7rem;">CUOTAS</th>
                                    <th class="border-0 py-2 pe-3 text-end text-muted fw-semibold" style="font-size:.7rem;">IMPORTE</th>
                                </tr>
                            </thead>
                            <tbody>`;

            plan.conceptos.forEach(c => {
                const tp      = parseFloat(String(c.total_concepto).replace(',', '')) || 0;
                const treg    = c.precio_regular ? parseFloat(String(c.precio_regular).replace(',', '')) : null;
                const dPct    = c.descuento_porcentaje;
                const dBs     = c.descuento_bs ? parseFloat(String(c.descuento_bs).replace(',', '')) : null;
                const rowBg   = c.es_promocion ? 'background:#fffbf0;' : '';
                const iColor  = c.es_promocion ? 'text-warning' : `text-${color}`;
                const badgeC  = c.es_promocion
                    ? 'bg-warning-subtle text-warning border border-warning-subtle'
                    : `bg-${color}-subtle text-${color} border border-${color}-subtle`;

                html += `
                                <tr style="${rowBg}">
                                    <td class="px-3 py-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ri-price-tag-3-line ${iColor}" style="font-size:.85rem;flex-shrink:0;"></i>
                                            <div>
                                                <div class="fw-medium">${c.concepto_nombre}</div>
                                                ${c.es_promocion ? `
                                                <div style="font-size:.7rem;" class="d-flex align-items-center gap-1 mt-1 flex-wrap">
                                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill" style="font-size:.65rem;">Promo</span>
                                                    ${c.fecha_inicio_promocion ? `<span class="text-muted"><i class="ri-calendar-line me-1"></i>${fmtDate(c.fecha_inicio_promocion)} → ${fmtDate(c.fecha_fin_promocion)}</span>` : ''}
                                                    ${dPct ? `<span class="badge bg-danger rounded-pill" style="font-size:.65rem;">-${dPct}%</span>` : ''}
                                                </div>` : ''}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center py-2">
                                        <span class="badge ${badgeC} rounded-pill" style="font-size:.72rem;">
                                            ${c.n_cuotas}x ${c.monto_por_cuota} Bs
                                        </span>
                                    </td>
                                    <td class="text-end pe-3 py-2">
                                        ${treg && c.es_promocion ? `<div class="text-muted text-decoration-line-through" style="font-size:.74rem;">${c.precio_regular} Bs</div>` : ''}
                                        <div class="fw-bold ${c.es_promocion ? 'text-warning' : `text-${color}`}">${c.total_concepto} Bs</div>
                                        ${dBs && dBs > 0 && c.es_promocion ? `<div class="text-success" style="font-size:.7rem;"><i class="ri-arrow-down-line me-1"></i>Ahorro ${c.descuento_bs} Bs</div>` : ''}
                                    </td>
                                </tr>`;
            });

            html += `
                            </tbody>
                            <tfoot>
                                <tr style="background:#f8f9fa;">
                                    <td colspan="2" class="text-end fw-semibold text-muted small py-2 px-3">
                                        ${esPromo && ahorro > 0 ? `<span class="text-success me-2 fw-normal"><i class="ri-discount-percent-line me-1"></i>Ahorro: ${ahorro.toFixed(2)} Bs</span>` : ''}
                                        Total Inversión:
                                    </td>
                                    <td class="text-end pe-3 py-2">
                                        ${esPromo && ahorro > 0 ? `<div class="text-muted text-decoration-line-through" style="font-size:.74rem;">${totalRegular.toFixed(2)} Bs</div>` : ''}
                                        <div class="fw-bold text-${color}" style="font-size:.95rem;">${totalPlan.toFixed(2)} Bs</div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>`;
        });

        html += '</div>';
        $('#planesPagoContainer').html(html);
    }
</script>
