<!-- Script para Convertir Pre-Inscrito a Inscrito - Diseño Premium -->
<script>
    $(document).on('click', '.convertir-inscrito-btn', function() {
        const btn = $(this);
        const inscripcionId = btn.data('inscripcion-id');
        const ofertaId = btn.data('oferta-id');
        const planPagoId = btn.data('plan-pago-id');
        const url = "{{ route('admin.inscripciones.convertir-pre-inscrito', ['inscripcion' => '__id__']) }}"
            .replace('__id__', inscripcionId);
        const estudianteNombre = btn.closest('tr').find('td:nth-child(2) .fw-medium').text() ||
            btn.closest('tr').find('td:nth-child(2) span').text() ||
            'el estudiante';

        Swal.fire({
            html: `
                <div class="text-center py-2">
                    <div class="mb-3">
                        <div class="avatar-xl mx-auto d-flex align-items-center justify-content-center rounded-circle" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0);">
                            <i class="ri-check-double-line text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h4 class="fw-semibold mb-2">Convertir a Inscrito</h4>
                    <p class="text-muted mb-0">¿Está seguro de convertir a <strong class="text-dark">${estudianteNombre}</strong> de Pre-Inscrito a Inscrito?</p>
                    <div class="mt-3 p-3 rounded-3" style="background: #f0fdf4;">
                        <p class="mb-1 fs-12 text-muted"><i class="ri-information-line me-1"></i> Se generarán las cuotas automáticamente</p>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="ri-check-line me-1"></i> Sí, convertir',
            cancelButtonText: '<i class="ri-close-line me-1"></i> Cancelar',
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#64748b',
            buttonsStyling: true,
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        oferta_id: ofertaId,
                        planes_pago_id: planPagoId
                    }
                }).then(response => {
                    if (!response.success) {
                        throw new Error(response.msg || 'Error en la conversión');
                    }
                    return response;
                }).catch(error => {
                    let errorMsg = 'Error desconocido';
                    if (error.responseJSON && error.responseJSON.msg) {
                        errorMsg = error.responseJSON.msg;
                    } else if (error.message && error.message !== 'Error en la conversión') {
                        errorMsg = error.message;
                    }
                    Swal.showValidationMessage(`Error: ${errorMsg}`);
                    throw error;
                });
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                Swal.fire({
                    html: `
                        <div class="text-center py-3">
                            <div class="mb-3">
                                <div class="avatar-xl mx-auto d-flex align-items-center justify-content-center rounded-circle" style="background: linear-gradient(135deg, #16a34a, #22c55e);">
                                    <i class="ri-check-line text-white" style="font-size: 2.5rem;"></i>
                                </div>
                            </div>
                            <h4 class="fw-semibold text-success mb-2">¡Conversión Exitosa!</h4>
                            <p class="text-muted mb-2">${result.value.msg || 'Pre-inscripción convertida a inscripción exitosamente.'}</p>
                            <div class="mt-3 p-3 rounded-3" style="background: #f0fdf4;">
                                <p class="mb-0 fs-13"><i class="ri-user-line me-1 text-success"></i> <strong>${result.value.estudiante || 'Estudiante'}</strong></p>
                            </div>
                        </div>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: '<i class="ri-check-line me-1"></i> Aceptar',
                    confirmButtonColor: '#16a34a',
                    timer: 5000,
                    timerProgressBar: true
                }).then(() => {
                    location.reload();
                });
            }
        }).catch(error => {
            console.error('Error en el proceso:', error);
        });
    });
</script>

<style>
    .swal2-popup {
        font-family: 'Outfit', sans-serif !important;
        border-radius: 16px !important;
    }
    .swal2-confirm {
        border-radius: 8px !important;
        padding: 0.6rem 1.5rem !important;
    }
    .swal2-cancel {
        border-radius: 8px !important;
        padding: 0.6rem 1.5rem !important;
    }
</style>