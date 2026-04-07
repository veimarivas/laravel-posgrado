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
                    <div style="width: 72px; height: 72px; margin: 0 auto 16px; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="ri-error-warning-line" style="font-size: 2rem; color: #ef4444;"></i>
                    </div>
                    <h5 class="text-danger mb-1" style="font-family: 'Outfit', sans-serif;">Error al cargar</h5>
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

    function formatMoney(amount) {
        if (!amount) return '0.00';
        const num = parseFloat(String(amount).replace(',', ''));
        return num.toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function isPromoActive(fIni, fFin) {
        if (!fIni || !fFin) return false;
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const start = new Date(fIni + 'T00:00:00');
        const end = new Date(fFin + 'T23:59:59');
        return today >= start && today <= end;
    }

    function renderizarPlanesPago(planes) {
        const paleta = ['#0f766e', '#2563eb', '#0891b2', '#7c3aed'];
        let html = '<div class="row g-3">';

        planes.forEach((plan, idx) => {
            const esPromo   = plan.es_promocion == 1;
            const fIni      = plan.fecha_inicio_promocion || null;
            const fFin      = plan.fecha_fin_promocion || null;
            const promoActiva = esPromo && fIni && fFin ? isPromoActive(fIni, fFin) : false;

            const color     = esPromo ? (promoActiva ? '#10b981' : '#ef4444') : paleta[idx % paleta.length];
            const colorLight = color + '15';
            const iconPlan  = esPromo ? 'ri-price-tag-3-line' : 'ri-bank-card-line';

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
                <div class="card border-0 shadow-sm h-100 overflow-hidden" style="border-radius: 12px; border-left: 4px solid ${color} !important;">

                    {{-- Cabecera del plan --}}
                    <div class="card-header border-0 py-3 px-3" style="background: ${colorLight};">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 38px; height: 38px; background: ${color}; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="${iconPlan}" style="font-size: 1.1rem; color: white;"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size: 0.9rem; color: #1e293b;">${plan.nombre}</div>
                                    ${esPromo && fIni ? `
                                    <div style="font-size: 0.72rem; color: ${promoActiva ? '#059669' : '#dc2626'};">
                                        <i class="ri-calendar-line me-1"></i>${fmtDate(fIni)} — ${fmtDate(fFin)}
                                        <span class="badge rounded-pill ms-1" style="background: ${promoActiva ? '#dcfce7' : '#fef2f2'}; color: ${promoActiva ? '#16a34a' : '#dc2626'}; font-size: 0.6rem; font-weight: 600;">
                                            ${promoActiva ? 'Vigente' : 'Expirada'}
                                        </span>
                                    </div>` : ''}
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-end gap-1">
                                ${esPromo ? `
                                <span class="badge rounded-pill" style="background: ${promoActiva ? '#dcfce7' : '#fef2f2'}; color: ${promoActiva ? '#16a34a' : '#dc2626'}; font-size: 0.68rem; font-weight: 600;">
                                    <i class="ri-price-tag-3-line me-1"></i>${promoActiva ? 'PROMO VIGENTE' : 'PROMO EXPIRADA'}
                                </span>` : `
                                <span class="badge rounded-pill" style="background: ${colorLight}; color: ${color}; font-size: 0.68rem; font-weight: 600;">Plan ${idx + 1}</span>`}
                                <span class="badge rounded-pill" style="background: #f1f5f9; color: #64748b; font-size: 0.62rem;">${plan.conceptos.length} concepto(s)</span>
                            </div>
                        </div>
                    </div>

                    {{-- Conceptos --}}
                    <div class="card-body p-0">`;

            plan.conceptos.forEach((c, cIdx) => {
                const tp      = parseFloat(String(c.total_concepto).replace(',', '')) || 0;
                const treg    = c.precio_regular ? parseFloat(String(c.precio_regular).replace(',', '')) : null;
                const dPct    = c.descuento_porcentaje;
                const dBs     = c.descuento_bs ? parseFloat(String(c.descuento_bs).replace(',', '')) : null;
                const rowBg   = cIdx % 2 === 0 ? '#fafbfc' : 'white';
                const iColor  = color;

                html += `
                        <div style="padding: 10px 14px; background: ${rowBg}; ${cIdx < plan.conceptos.length - 1 ? 'border-bottom: 1px solid #f1f5f9;' : ''}">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width: 0;">
                                    <div style="width: 28px; height: 28px; background: ${iColor}15; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="ri-price-tag-3-line" style="font-size: 0.8rem; color: ${iColor};"></i>
                                    </div>
                                    <div style="min-width: 0;">
                                        <div class="fw-medium" style="font-size: 0.82rem; color: #1e293b;">${c.concepto_nombre}</div>
                                    </div>
                                </div>
                                <div class="text-end flex-shrink-0 ms-2">
                                    <span class="badge rounded-pill" style="background: ${colorLight}; color: ${color}; font-size: 0.68rem; font-weight: 600;">
                                        ${c.n_cuotas}x ${c.monto_por_cuota} Bs
                                    </span>
                                    <div class="mt-1 fw-bold" style="font-size: 0.85rem; color: ${color};">${formatMoney(c.total_concepto)} Bs</div>
                                </div>
                            </div>
                        </div>`;
            });

            html += `
                    </div>

                    {{-- Footer con total --}}
                    <div style="padding: 12px 14px; background: #f8fafc; border-top: 1px dashed #e2e8f0;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted" style="font-size: 0.78rem;">Total Inversión:</span>
                                ${esPromo && ahorro > 0 ? `<span class="ms-2 badge rounded-pill" style="background: #dcfce7; color: #16a34a; font-size: 0.65rem; font-weight: 600;"><i class="ri-discount-percent-line me-1"></i>Ahorro: ${ahorro.toFixed(2)} Bs</span>` : ''}
                            </div>
                            <div class="text-end">
                                ${esPromo && ahorro > 0 ? `<div class="text-muted text-decoration-line-through" style="font-size: 0.72rem;">${formatMoney(totalRegular)} Bs</div>` : ''}
                                <div class="fw-bold" style="font-size: 1.05rem; color: ${color};">${formatMoney(totalPlan)} Bs</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>`;
        });

        html += '</div>';
        $('#planesPagoContainer').html(html);
    }
</script>
