<script>
    $(document).on('click', '.change-phase', function() {
        const btn = $(this);
        const id = btn.data('oferta-id');
        const dir = btn.data('direction');

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
                    const row = btn.closest('tr');
                    row.find('.badge.text-white').text(res.fase_nombre).css('background-color', res
                        .fase_color);
                    row.css('background-color', res.bg_color);
                    row.find('td:last-child').html(res.acciones_html);
                    refreshFeather();
                } else {
                    mostrarToast('error', res.msg || 'Error al cambiar la fase.');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errorMsg = xhr.responseJSON?.msg || 'No se puede cambiar la fase.';

                    Swal.fire({
                        icon: 'warning',
                        title: 'Validación Requerida',
                        html: errorMsg,
                        confirmButtonText: 'Entendido',
                        showCancelButton: errorMsg.includes('plan de pago'),
                        cancelButtonText: 'Ver Planes',
                        showCloseButton: true,
                        customClass: {
                            confirmButton: 'btn btn-primary',
                            cancelButton: 'btn btn-info'
                        }
                    }).then((result) => {
                        if (result.dismiss === 'cancel') {
                            const ofertaId = id;
                            const ofertaCodigo = btn.closest('tr').find('td:nth-child(2)')
                                .text();

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
                                    if (res.success && res.planes.length > 0) {
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
                    mostrarToast('error', 'Error al cambiar la fase.');
                }
            },
            complete: function() {
                btn.html(originalHtml);
                btn.prop('disabled', false);
            }
        });
    });
</script>
