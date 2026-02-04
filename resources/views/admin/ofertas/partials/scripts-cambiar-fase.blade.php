<script>
    $(document).on('click', '.change-phase', function() {
        const btn = $(this);
        const id = btn.data('oferta-id');
        const dir = btn.data('direction');
        const row = btn.closest('tr');

        const originalHtml = btn.html();
        btn.html('<i class="ri-loader-4-line spin"></i>');
        btn.prop('disabled', true);

        $.ajax({
            url: `/admin/ofertas/${id}/cambiar-fase`,
            method: 'POST',
            data: {
                direction: dir,
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.success) {
                    mostrarToast('success', res.msg || 'Fase cambiada exitosamente.');

                    // Si se devuelve HTML completo de la fila
                    if (res.fila_html) {
                        // Reemplazar la fila completa
                        row.replaceWith(res.fila_html);
                    } else {
                        // Actualizar solo los elementos necesarios

                        // 1. Actualizar fase
                        const faseCell = row.find('.fase-celda');
                        if (res.fase) {
                            faseCell.html(`
                            <div class="d-flex flex-column align-items-center gap-1">
                                <span class="badge text-white px-3 py-1 fase-badge"
                                    style="background-color: ${res.fase.color}; font-size: 0.85rem; min-width: 100px;">
                                    ${res.fase.nombre}
                                </span>
                                <small class="text-muted fase-numero" style="font-size: 0.75rem;">
                                    Fase ${res.fase.n_fase}
                                </small>
                            </div>
                        `);
                        }

                        // 2. Actualizar color de fondo de la fila
                        if (res.bg_color) {
                            row.css('background-color', res.bg_color);
                        }

                        // 3. Actualizar contador de inscritos
                        if (res.total_inscritos !== undefined) {
                            const inscritosBadge = row.find('.badge.bg-success');
                            if (inscritosBadge.length) {
                                inscritosBadge.html(
                                    `<i class="ri-user-follow-line me-1"></i>${res.total_inscritos}`
                                    );
                            }
                        }

                        // 4. Actualizar contador de pre-inscritos
                        if (res.total_preinscritos !== undefined) {
                            const preInscritosContainer = row.find('.badge.bg-secondary').closest(
                                'div');
                            if (res.total_preinscritos > 0) {
                                if (preInscritosContainer.length) {
                                    preInscritosContainer.find('.badge').html(
                                        `<i class="ri-user-add-line me-1"></i>${res.total_preinscritos}`
                                        );
                                } else {
                                    // Crear el badge si no existe
                                    const inscritosDiv = row.find('.d-flex.flex-column.gap-1');
                                    if (inscritosDiv.length) {
                                        inscritosDiv.append(`
                                        <div>
                                            <span class="badge bg-secondary bg-gradient rounded-pill px-3 py-1"
                                                style="font-size: 0.75rem; min-width: 50px;">
                                                <i class="ri-user-add-line me-1"></i>
                                                ${res.total_preinscritos}
                                            </span>
                                        </div>
                                    `);
                                    }
                                }
                            } else {
                                // Eliminar el badge si no hay pre-inscritos
                                preInscritosContainer.closest('div').remove();
                            }
                        }

                        // 5. Actualizar acciones
                        if (res.acciones_html) {
                            row.find('.acciones-celda').html(res.acciones_html);
                        }
                    }

                    // Reinicializar eventos y componentes
                    refreshFeather();
                    inicializarEventosOfertas();

                } else {
                    if (res.msg && res.msg.includes('plan de pago')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Validación Requerida',
                            html: res.msg,
                            confirmButtonText: 'Entendido',
                            cancelButtonText: 'Ver Planes',
                            showCancelButton: true,
                            showCloseButton: true,
                            customClass: {
                                confirmButton: 'btn btn-primary',
                                cancelButton: 'btn btn-info'
                            }
                        }).then((result) => {
                            if (result.dismiss === 'cancel') {
                                const ofertaId = id;
                                const ofertaCodigo = row.find('td:nth-child(2)').text()
                                    .trim();

                                $('#planes_oferta_codigo').text(ofertaCodigo);
                                $('#loadingPlanes').show();
                                $('#planesPagoContainer').hide();
                                $('#sinPlanes').hide();
                                $('#modalVerPlanesPago').modal('show');

                                $.ajax({
                                    url: `/admin/ofertas/${ofertaId}/planes-pago`,
                                    method: 'GET',
                                    success: function(res) {
                                        $('#loadingPlanes').hide();
                                        if (res.success && res.planes.length >
                                            0) {
                                            renderizarPlanesPago(res.planes);
                                            $('#planesPagoContainer').show();
                                        } else {
                                            $('#sinPlanes').show();
                                        }
                                    },
                                    error: function() {
                                        $('#loadingPlanes').hide();
                                        $('#sinPlanes').show();
                                    }
                                });
                            }
                        });
                    } else {
                        mostrarToast('error', res.msg || 'Error al cambiar la fase.');
                    }
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    mostrarToast('error', xhr.responseJSON?.msg || 'Error de validación.');
                } else {
                    mostrarToast('error', 'Error al cambiar la fase.');
                }
            },
            complete: function() {
                btn.html(originalHtml);
                btn.prop('disabled', false);
            }
        });
    });

    // Función para reinicializar eventos
    function inicializarEventosOfertas() {
        // Reasignar eventos a los botones de cambio de fase
        $('.change-phase').off('click').on('click', function() {
            // El código del evento ya está definido arriba
        });

        // Reasignar eventos a otros botones si es necesario
        $('.editOfertaBtn, .editFase2Btn, .verPlanesPagoBtn, .inscribirEstudianteBtn').off('click');
        // ... código para inicializar otros eventos ...
    }

    // Llamar al cargar la página
    $(document).ready(function() {
        inicializarEventosOfertas();
    });
</script>
