<!-- Scripts para Acciones (Eliminar, Transferir, Cambiar Plan) - Diseño Premium -->
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let inscripcionATransferir = null;
        let estudianteATransferir = null;

        // Eliminar inscripción
        $(document).on('click', '.btn-eliminar', function() {
            const inscripcionId = $(this).data('inscripcion-id');
            const estudiante = $(this).data('estudiante');
            $('#estudianteEliminarNombre').text(estudiante);
            $('#inscripcionEliminarId').val(inscripcionId);
            $('#modalEliminar').modal('show');
        });

        $('#confirmarEliminarBtn').click(function() {
            const inscripcionId = $('#inscripcionEliminarId').val();
            const url = '{{ route('admin.ofertas.inscripciones.eliminar', [$oferta->id, '__id__']) }}'
                .replace('__id__', inscripcionId);

            $.ajax({
                url: url,
                type: 'DELETE',
                beforeSend: function() {
                    $('#confirmarEliminarBtn').prop('disabled', true).html(
                        '<i class="ri-loader-4-line ri-spin"></i> Eliminando...');
                },
                success: function(response) {
                    if (response.success) {
                        $('#modalEliminar').modal('hide');
                        Swal.fire({
                            html: `
                                <div class="text-center py-3">
                                    <div class="mb-3">
                                        <div class="avatar-xl mx-auto d-flex align-items-center justify-content-center rounded-circle" style="background: linear-gradient(135deg, #fee2e2, #fecaca);">
                                            <i class="ri-delete-bin-line text-danger" style="font-size: 2.5rem;"></i>
                                        </div>
                                    </div>
                                    <h4 class="fw-semibold text-danger mb-2">¡Eliminado!</h4>
                                    <p class="text-muted mb-0">${response.msg || 'Inscripción eliminada correctamente.'}</p>
                                </div>
                            `,
                            showConfirmButton: true,
                            confirmButtonText: '<i class="ri-check-line me-1"></i> Aceptar',
                            confirmButtonColor: '#dc2626',
                            timer: 3000,
                            timerProgressBar: true
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    $('#confirmarEliminarBtn').prop('disabled', false).html('<i class="ri-delete-bin-line me-1"></i> Eliminar');
                    let errorMsg = 'Error al eliminar la inscripción';
                    if (xhr.responseJSON && xhr.responseJSON.msg) {
                        errorMsg = xhr.responseJSON.msg;
                    }
                    Swal.fire({
                        html: `
                            <div class="text-center py-3">
                                <div class="mb-3">
                                    <div class="avatar-xl mx-auto d-flex align-items-center justify-content-center rounded-circle" style="background: linear-gradient(135deg, #fee2e2, #fecaca);">
                                        <i class="ri-error-warning-line text-danger" style="font-size: 2.5rem;"></i>
                                    </div>
                                </div>
                                <h4 class="fw-semibold text-danger mb-2">Error</h4>
                                <p class="text-muted mb-0">${errorMsg}</p>
                            </div>
                        `,
                        showConfirmButton: true,
                        confirmButtonText: '<i class="ri-close-line me-1"></i> Cerrar',
                        confirmButtonColor: '#64748b'
                    });
                },
                complete: function() {
                    $('#confirmarEliminarBtn').prop('disabled', false).html('<i class="ri-delete-bin-line me-1"></i> Eliminar');
                }
            });
        });

        // Transferir inscripción
        $(document).on('click', '.btn-transferir', function(e) {
            e.preventDefault();
            const estudiante = $(this).data('estudiante');
            const estudianteId = $(this).data('estudiante-id');
            const inscripcionId = $(this).data('inscripcion-id');
            const carnet = $(this).closest('tr').find('.badge').text();

            $('#estudianteTransferirNombre').val(estudiante);
            $('#estudianteTransferirCarnet').val(carnet);
            $('#inscripcionTransferirId').val(inscripcionId);
            $('#estudianteTransferirId').val(estudianteId);

            inscripcionATransferir = inscripcionId;
            estudianteATransferir = estudianteId;
            cargarOfertasDisponibles();
            $('#modalTransferir').modal('show');
        });

        function cargarOfertasDisponibles() {
            Swal.fire({
                html: `
                    <div class="text-center py-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title rounded-circle bg-primary-subtle">
                                <i class="ri-loader-4-line text-primary ri-spin fs-24"></i>
                            </div>
                        </div>
                        <h5 class="fw-medium">Cargando ofertas</h5>
                        <p class="text-muted mb-0 fs-13">Buscando ofertas disponibles...</p>
                    </div>
                `,
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            $.ajax({
                url: '{{ route('admin.ofertas.disponibles-transferencia') }}',
                type: 'GET',
                data: {
                    current_oferta_id: '{{ $oferta->id }}',
                    sucursal_id: '{{ $oferta->sucursale_id }}'
                },
                success: function(response) {
                    Swal.close();
                    if (response.success && response.ofertas.length > 0) {
                        let options = '<option value="">Seleccione una oferta</option>';
                        response.ofertas.forEach(oferta => {
                            const fechaInicio = new Date(oferta.fecha_inicio_programa).toLocaleDateString('es-BO');
                            options += `<option value="${oferta.id}" data-programa="${oferta.programa.nombre}" data-sucursal="${oferta.sucursal.nombre}" data-modalidad="${oferta.modalidad.nombre}" data-fecha="${fechaInicio}">
                                ${oferta.codigo} - ${oferta.programa.nombre} (${oferta.sucursal.nombre})
                            </option>`;
                        });
                        $('#nuevaOfertaSelect').html(options).prop('disabled', false);
                    } else {
                        Swal.fire({
                            html: `
                                <div class="text-center py-3">
                                    <div class="avatar-lg mx-auto mb-3">
                                        <div class="avatar-title rounded-circle bg-warning-subtle">
                                            <i class="ri-information-line text-warning fs-24"></i>
                                        </div>
                                    </div>
                                    <h5 class="fw-medium">No hay ofertas disponibles</h5>
                                    <p class="text-muted mb-0 fs-13">No hay ofertas disponibles para transferencia en esta sucursal.</p>
                                </div>
                            `,
                            showConfirmButton: true,
                            confirmButtonText: '<i class="ri-check-line me-1"></i> Aceptar',
                            confirmButtonColor: '#f59e0b'
                        }).then(() => { $('#modalTransferir').modal('hide'); });
                        $('#nuevaOfertaSelect').html('<option value="">No hay ofertas disponibles</option>').prop('disabled', true);
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        html: `
                            <div class="text-center py-3">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title rounded-circle bg-danger-subtle">
                                        <i class="ri-error-warning-line text-danger fs-24"></i>
                                    </div>
                                </div>
                                <h5 class="fw-medium text-danger">Error</h5>
                                <p class="text-muted mb-0 fs-13">Error al cargar las ofertas disponibles.</p>
                            </div>
                        `,
                        showConfirmButton: true,
                        confirmButtonText: '<i class="ri-close-line me-1"></i> Cerrar',
                        confirmButtonColor: '#64748b'
                    });
                }
            });
        }

        $(document).on('change', '#nuevaOfertaSelect', function() {
            const ofertaId = $(this).val();
            if (!ofertaId) {
                $('#planPagoSelect').prop('disabled', true).html('<option value="">Primero seleccione una oferta</option>');
                $('#planDetallesCard').hide();
                $('#confirmarTransferirBtn').prop('disabled', true);
                return;
            }
            cargarPlanesDePago(ofertaId);
        });

        function cargarPlanesDePago(ofertaId) {
            Swal.fire({
                html: `
                    <div class="text-center py-3">
                        <div class="avatar-lg mx-auto mb-2">
                            <div class="avatar-title rounded-circle bg-primary-subtle">
                                <i class="ri-loader-4-line text-primary ri-spin fs-20"></i>
                            </div>
                        </div>
                        <p class="text-muted mb-0 fs-12">Cargando planes...</p>
                    </div>
                `,
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            $.ajax({
                url: '/admin/ofertas/' + ofertaId + '/planes-transferencia',
                type: 'GET',
                success: function(response) {
                    Swal.close();
                    if (response.success && response.planes.length > 0) {
                        let options = '<option value="">Seleccione un plan de pago</option>';
                        response.planes.forEach(plan => {
                            options += `<option value="${plan.id}" data-conceptos='${JSON.stringify(plan.conceptos)}'>${plan.nombre}</option>`;
                        });
                        $('#planPagoSelect').html(options).prop('disabled', false);
                    } else {
                        $('#planPagoSelect').html('<option value="">No hay planes disponibles</option>').prop('disabled', true);
                    }
                    $('#planDetallesCard').hide();
                    $('#confirmarTransferirBtn').prop('disabled', true);
                },
                error: function() {
                    Swal.close();
                    $('#planPagoSelect').html('<option value="">Error al cargar</option>');
                }
            });
        }

        $(document).on('change', '#planPagoSelect', function() {
            const planId = $(this).val();
            const conceptos = $(this).find(':selected').data('conceptos');
            if (!planId || !conceptos) {
                $('#planDetallesCard').hide();
                $('#confirmarTransferirBtn').prop('disabled', true);
                return;
            }
            mostrarDetallesPlan(conceptos);
            $('#confirmarTransferirBtn').prop('disabled', false);
        });

        function mostrarDetallesPlan(conceptos) {
            let html = '<div class="table-responsive"><table class="table table-sm table-bordered mb-0" style="font-size: 0.8rem;">';
            html += '<thead class="table-light"><tr><th>Concepto</th><th class="text-center">Cuotas</th><th class="text-end">Monto</th><th class="text-end">Total</th></tr></thead><tbody>';
            let totalGeneral = 0;
            conceptos.forEach(concepto => {
                const montoCuota = parseFloat(concepto.monto_por_cuota.replace(',', '')) || 0;
                const totalConcepto = parseFloat(concepto.total_concepto.replace(',', '')) || 0;
                totalGeneral += totalConcepto;
                html += `<tr><td>${concepto.concepto_nombre}</td><td class="text-center">${concepto.n_cuotas}</td><td class="text-end">${concepto.monto_por_cuota}</td><td class="text-end fw-semibold">${concepto.total_concepto}</td></tr>`;
            });
            html += `<tr class="table-light"><td colspan="3" class="text-end fw-bold">Total del Plan:</td><td class="text-end fw-bold text-primary">${totalGeneral.toFixed(2)} Bs</td></tr>`;
            html += '</tbody></table></div>';
            $('#planDetallesBody').html(html);
            $('#planDetallesCard').show();
        }

        $('#confirmarTransferirBtn').click(function() {
            const ofertaId = $('#nuevaOfertaSelect').val();
            const planId = $('#planPagoSelect').val();
            const observacion = $('#observacionTransferencia').val();

            if (!ofertaId || !planId) {
                Swal.fire({
                    html: `
                        <div class="text-center py-3">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title rounded-circle bg-warning-subtle">
                                    <i class="ri-alert-line text-warning fs-24"></i>
                                </div>
                            </div>
                            <h5 class="fw-medium">Campos incompletos</h5>
                            <p class="text-muted mb-0 fs-13">Por favor, seleccione una oferta y un plan de pago.</p>
                        </div>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: '<i class="ri-check-line me-1"></i> Aceptar',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }

            const url = '/admin/ofertas/{{ $oferta->id }}/inscripciones/' + inscripcionATransferir + '/transferir';

            Swal.fire({
                html: `
                    <div class="text-center py-3">
                        <div class="mb-3">
                            <div class="avatar-xl mx-auto d-flex align-items-center justify-content-center rounded-circle" style="background: linear-gradient(135deg, #e0f2fe, #bae6fd);">
                                <i class="ri-exchange-line text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <h4 class="fw-semibold mb-3">¿Confirmar transferencia?</h4>
                        <div class="text-start p-3 rounded-3" style="background: #f8fafc;">
                            <p class="mb-1"><strong>Estudiante:</strong> ${$('#estudianteTransferirNombre').val()}</p>
                            <p class="mb-1"><strong>Nueva Oferta:</strong> ${$('#nuevaOfertaSelect option:selected').text()}</p>
                            <p class="mb-0"><strong>Plan de Pago:</strong> ${$('#planPagoSelect option:selected').text()}</p>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="ri-check-line me-1"></i> Sí, transferir',
                cancelButtonText: '<i class="ri-close-line me-1"></i> Cancelar',
                confirmButtonColor: '#0891b2',
                cancelButtonColor: '#64748b',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    realizarTransferencia(url, ofertaId, planId, observacion);
                }
            });
        });

        function realizarTransferencia(url, ofertaId, planId, observacion) {
            Swal.fire({
                html: `
                    <div class="text-center py-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title rounded-circle bg-primary-subtle">
                                <i class="ri-loader-4-line text-primary ri-spin fs-24"></i>
                            </div>
                        </div>
                        <h5 class="fw-medium">Procesando transferencia</h5>
                        <p class="text-muted mb-0 fs-13">Por favor espere...</p>
                    </div>
                `,
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            $.ajax({
                url: url,
                type: 'POST',
                data: { nueva_oferta_id: ofertaId, nuevo_plan_pago_id: planId, observacion: observacion },
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        Swal.fire({
                            html: `
                                <div class="text-center py-3">
                                    <div class="mb-3">
                                        <div class="avatar-xl mx-auto d-flex align-items-center justify-content-center rounded-circle" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0);">
                                            <i class="ri-check-line text-success" style="font-size: 2.5rem;"></i>
                                        </div>
                                    </div>
                                    <h4 class="fw-semibold text-success mb-2">¡Transferencia Exitosa!</h4>
                                    <p class="text-muted mb-0">${response.msg || 'Inscripción transferida correctamente.'}</p>
                                </div>
                            `,
                            showConfirmButton: true,
                            confirmButtonText: '<i class="ri-check-line me-1"></i> Aceptar',
                            confirmButtonColor: '#16a34a',
                            timer: 4000,
                            timerProgressBar: true
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            html: `
                                <div class="text-center py-3">
                                    <div class="avatar-lg mx-auto mb-3">
                                        <div class="avatar-title rounded-circle bg-danger-subtle">
                                            <i class="ri-error-warning-line text-danger fs-24"></i>
                                        </div>
                                    </div>
                                    <h5 class="fw-medium text-danger">Error</h5>
                                    <p class="text-muted mb-0">${response.msg || 'Error al transferir la inscripción.'}</p>
                                </div>
                            `,
                            showConfirmButton: true,
                            confirmButtonText: '<i class="ri-close-line me-1"></i> Cerrar',
                            confirmButtonColor: '#64748b'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    let mensaje = 'Error al transferir la inscripción';
                    if (xhr.status === 403) {
                        mensaje = 'No tiene permisos para realizar esta acción.';
                    } else if (xhr.responseJSON && xhr.responseJSON.msg) {
                        mensaje = xhr.responseJSON.msg;
                    }
                    Swal.fire({
                        html: `
                            <div class="text-center py-3">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title rounded-circle bg-danger-subtle">
                                        <i class="ri-error-warning-line text-danger fs-24"></i>
                                    </div>
                                </div>
                                <h5 class="fw-medium text-danger">Error</h5>
                                <p class="text-muted mb-0">${mensaje}</p>
                            </div>
                        `,
                        showConfirmButton: true,
                        confirmButtonText: '<i class="ri-close-line me-1"></i> Cerrar',
                        confirmButtonColor: '#64748b'
                    });
                }
            });
        }

        function reorganizarNumerosFilas() {
            $('#tablaGestion tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        $('#modalTransferir').on('hidden.bs.modal', function() {
            $('#nuevaOfertaSelect').val('');
            $('#planPagoSelect').val('').prop('disabled', true);
            $('#observacionTransferencia').val('');
            $('#planDetallesCard').hide();
            $('#confirmarTransferirBtn').prop('disabled', true);
            inscripcionATransferir = null;
            estudianteATransferir = null;
        });

        // Cambiar Plan de Pago
        function cargarPlanesParaCambio(ofertaId, planActualId) {
            $('#nuevo_plan_pago_select').html('<option value="">Cargando planes...</option>');

            $.ajax({
                url: `/admin/ofertas/${ofertaId}/planes-inscripcion`,
                method: 'GET',
                success: function(res) {
                    if (res.success && res.planes.length > 0) {
                        let options = '<option value="">Seleccione un plan</option>';
                        res.planes.forEach(plan => {
                            let promocionInfo = '';
                            if (plan.es_promocion == 1) {
                                const fin = plan.fecha_fin_promocion ? new Date(plan.fecha_fin_promocion).toLocaleDateString() : '';
                                promocionInfo = ` 🏷️ (Promoción hasta ${fin})`;
                            }
                            const selected = (plan.id == planActualId) ? 'selected' : '';
                            options += `<option value="${plan.id}" ${selected}>${plan.nombre}${promocionInfo}</option>`;
                        });
                        $('#nuevo_plan_pago_select').html(options);
                    } else {
                        $('#nuevo_plan_pago_select').html('<option value="">No hay planes disponibles</option>');
                    }
                },
                error: function(xhr) {
                    $('#nuevo_plan_pago_select').html('<option value="">Error al cargar planes</option>');
                }
            });
        }

        $(document).on('click', '.cambiar-plan-pago-btn', function() {
            const inscripcionId = $(this).data('inscripcion-id');
            const ofertaId = $(this).data('oferta-id');
            const planActualId = $(this).data('plan-actual-id');
            const planActualNombre = $(this).data('plan-actual-nombre');
            const atrasoActual = $(this).data('atraso-actual') || 0;

            $('#cambiar_plan_inscripcion_id').val(inscripcionId);
            $('#cambiar_plan_oferta_id').val(ofertaId);
            $('#plan_actual_nombre').val(planActualNombre);
            $('#cambiar_plan_adelanto_bs').val(atrasoActual);

            cargarPlanesParaCambio(ofertaId, planActualId);
            $('#modalCambiarPlanPago').modal('show');
        });

        $('#btnConfirmarCambioPlan').on('click', function() {
            const form = $('#formCambiarPlanPago');
            const formData = form.serialize();
            const adelInput = $('#cambiar_plan_adelanto_bs');
            const adelVal = parseFloat(adelInput.val());

            if (adelInput.val() !== '' && (isNaN(adelVal) || adelVal < 0)) {
                Swal.fire({
                    html: `
                        <div class="text-center py-3">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title rounded-circle bg-danger-subtle">
                                    <i class="ri-error-warning-line text-danger fs-24"></i>
                                </div>
                            </div>
                            <h5 class="fw-medium text-danger">Valor inválido</h5>
                            <p class="text-muted mb-0 fs-13">Por favor ingrese un valor válido para el adelanto.</p>
                        </div>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: '<i class="ri-check-line me-1"></i> Aceptar',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }

            Swal.fire({
                html: `
                    <div class="text-center py-3">
                        <div class="mb-3">
                            <div class="avatar-xl mx-auto d-flex align-items-center justify-content-center rounded-circle" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                                <i class="ri-exchange-dollar-line text-warning" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <h4 class="fw-semibold mb-3">¿Confirmar cambio de plan?</h4>
                        <div class="text-start p-3 rounded-3" style="background: #f8fafc;">
                            <p class="mb-1"><strong>Plan Actual:</strong> ${$('#plan_actual_nombre').val()}</p>
                            <p class="mb-0"><strong>Nuevo Plan:</strong> ${$('#nuevo_plan_pago_select option:selected').text()}</p>
                            ${adelVal > 0 ? `<p class="mb-0 mt-2 text-success"><i class="ri-money-dollar-circle-line me-1"></i> Adelanto: <strong>${adelVal.toFixed(2)} Bs</strong></p>` : ''}
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="ri-check-line me-1"></i> Sí, cambiar',
                cancelButtonText: '<i class="ri-close-line me-1"></i> Cancelar',
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#64748b',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.inscripciones.cambiar-plan-pago') }}",
                        method: 'POST',
                        data: formData,
                        beforeSend: function() {
                            $('#btnConfirmarCambioPlan').prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i> Procesando...');
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    html: `
                                        <div class="text-center py-3">
                                            <div class="mb-3">
                                                <div class="avatar-xl mx-auto d-flex align-items-center justify-content-center rounded-circle" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0);">
                                                    <i class="ri-check-line text-success" style="font-size: 2.5rem;"></i>
                                                </div>
                                            </div>
                                            <h4 class="fw-semibold text-success mb-2">¡Cambio exitoso!</h4>
                                            <p class="text-muted mb-0">${response.msg || 'Plan de pago actualizado correctamente.'}</p>
                                        </div>
                                    `,
                                    showConfirmButton: true,
                                    confirmButtonText: '<i class="ri-check-line me-1"></i> Aceptar',
                                    confirmButtonColor: '#16a34a',
                                    timer: 4000
                                }).then(() => {
                                    $('#modalCambiarPlanPago').modal('hide');
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    html: `
                                        <div class="text-center py-3">
                                            <div class="avatar-lg mx-auto mb-3">
                                                <div class="avatar-title rounded-circle bg-danger-subtle">
                                                    <i class="ri-error-warning-line text-danger fs-24"></i>
                                                </div>
                                            </div>
                                            <h5 class="fw-medium text-danger">Error</h5>
                                            <p class="text-muted mb-0">${response.msg || 'Error al cambiar el plan de pago.'}</p>
                                        </div>
                                    `,
                                    showConfirmButton: true,
                                    confirmButtonText: '<i class="ri-close-line me-1"></i> Cerrar',
                                    confirmButtonColor: '#64748b'
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                html: `
                                    <div class="text-center py-3">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title rounded-circle bg-danger-subtle">
                                                <i class="ri-error-warning-line text-danger fs-24"></i>
                                            </div>
                                        </div>
                                        <h5 class="fw-medium text-danger">Error</h5>
                                        <p class="text-muted mb-0">${xhr.responseJSON?.msg || 'Error al cambiar el plan de pago.'}</p>
                                    </div>
                                `,
                                showConfirmButton: true,
                                confirmButtonText: '<i class="ri-close-line me-1"></i> Cerrar',
                                confirmButtonColor: '#64748b'
                            });
                        },
                        complete: function() {
                            $('#btnConfirmarCambioPlan').prop('disabled', false).html('<i class="ri-check-line me-1"></i> Confirmar Cambio');
                        }
                    });
                }
            });
        });
    });
</script>

<style>
    .swal2-popup {
        font-family: 'Outfit', sans-serif !important;
        border-radius: 16px !important;
    }
    .swal2-confirm, .swal2-cancel {
        border-radius: 8px !important;
        padding: 0.6rem 1.5rem !important;
    }
    .swal2-html-container {
        padding: 1rem !important;
    }
</style>