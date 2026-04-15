<script>
    // Función para ejecutar el cambio de fase después de la confirmación
    function ejecutarCambioFase(id, dir, btn, row) {
        const originalHtml = btn.html();
        btn.html('<i class="ri-loader-4-line spin"></i>');
        btn.prop('disabled', true);

        $.ajax({
            url: `/admin/ofertas/${id}/cambiar-fase`,
            method: 'POST',
            data: {
                direction: dir,
                confirmed: true,
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.success) {
                    mostrarToast('success', res.msg || 'Fase cambiada exitosamente.');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    mostrarToast('error', res.msg || 'Error al cambiar la fase.');
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
    }

    $(document).on('click', '.change-phase', function() {
        const btn = $(this);
        const id = btn.data('oferta-id');
        const dir = btn.data('direction');
        const row = btn.closest('tr');

        // Primera llamada para verificar si necesita confirmación
        $.ajax({
            url: `/admin/ofertas/${id}/cambiar-fase`,
            method: 'POST',
            data: {
                direction: dir,
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.success && res.confirm_required) {
                    // Mostrar modal de confirmación según el tipo
                    let titulo = '';
                    let confirmButtonText = 'Confirmar';
                    let confirmButtonClass = 'btn btn-primary';

                    if (res.confirm_type === 'fase1_to_2') {
                        titulo = 'Aprobar Oferta Académica';
                        confirmButtonText = 'Sí, aprobar';
                        confirmButtonClass = 'btn btn-success';
                    } else if (res.confirm_type === 'fase2_to_3') {
                        titulo = 'Pasar a Fase de Inscripciones';
                        confirmButtonText = 'Sí, continuar';
                        confirmButtonClass = 'btn btn-primary';
                    } else if (res.confirm_type === 'fase3_to_4') {
                        titulo = 'Pasar a Fase de Desarrollo';
                        confirmButtonText = 'Sí, pasar a desarrollo';
                        confirmButtonClass = 'btn btn-warning';
                    }

                    Swal.fire({
                        icon: 'info',
                        title: titulo,
                        html: res.msg,
                        showCancelButton: true,
                        confirmButtonText: confirmButtonText,
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: res.confirm_type === 'fase3_to_4' ? '#f59e0b' : '#0d9488',
                        cancelButtonColor: '#6c757d',
                        customClass: {
                            confirmButton: confirmButtonClass,
                            cancelButton: 'btn btn-secondary'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            ejecutarCambioFase(id, dir, btn, row);
                        }
                    });
                } else if (res.success) {
                    // No requiere confirmación, cambiar directamente
                    mostrarToast('success', res.msg || 'Fase cambiada exitosamente.');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    // Error sin confirmación
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
                                const ofertaCodigo = row.find('td:nth-child(2)').text().trim();
                                $('#planes_oferta_codigo').text(ofertaCodigo);
                                $('#loadingPlanes').show();
                                $('#planesPagoContainer').hide();
                                $('#sinPlanes').hide();
                                $('#modalVerPlanesPago').modal('show');

                                $.ajax({
                                    url: `/admin/ofertas/${id}/planes-pago`,
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
            }
        });
    });

    function inicializarEventosOfertas() {
        $('.change-phase').off('click');
        $('.editOfertaBtn, .editFase2Btn, .verPlanesPagoBtn, .inscribirEstudianteBtn').off('click');
    }

    $(document).ready(function() {
        inicializarEventosOfertas();
    });
</script>
