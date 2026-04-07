<script>
    // === INSCRIPCIÓN DE ESTUDIANTE - MEJORADO ===
    $(document).on('click', '.inscribirEstudianteBtn', function() {
        const ofertaId = $(this).data('oferta-id');
        $('#oferta_id_inscripcion').val(ofertaId);

        // Resetear todo el modal
        $('#carnet_inscripcion').val('');
        $('#mensaje-verificacion-inscripcion').html('');
        $('#paso-carnet-inscripcion').show();
        $('#formInscripcion, #formConfirmarEstudiante, #formNuevaPersonaInscripcion').hide();
        $('#btn-nueva-persona-inscripcion').prop('disabled', true).css({ opacity: 0.5, cursor: 'not-allowed' });

        // Resetear controles
        $('#estado_inscripcion').val('');
        $('#planes_pago_select').html('<option value="">Seleccione un plan</option>');
        $('#adelanto_bs').val('');
        $('#cuotas-preview-container').empty();

        $('#modalInscribirEstudiante').modal('show');
    });

    // Verificación de carnet en inscripción CON DEBOUNCE
    $('#carnet_inscripcion').on('input', function() {
        const carnet = $(this).val().trim();
        lastCarnetValue = carnet;

        // Limpiar mensajes anteriores
        $('#mensaje-verificacion-inscripcion').html('');

        // Ocultar todos los formularios excepto el paso del carnet
        $('#formInscripcion, #formConfirmarEstudiante, #formNuevaPersonaInscripcion').hide();
        $('#paso-carnet-inscripcion').show();

        if (!carnet) {
            $('#btn-nueva-persona-inscripcion').prop('disabled', true).css({ opacity: 0.5, cursor: 'not-allowed' });
            return;
        }

        // Aplicar debounce
        clearTimeout(debounceTimerInscripcion);

        debounceTimerInscripcion = setTimeout(() => {
            if ($('#carnet_inscripcion').val().trim() !== lastCarnetValue) {
                return;
            }

            $('#mensaje-verificacion-inscripcion').html(
                '<div class="text-center text-muted" style="font-size:0.82rem;"><i class="ri-loader-4-line spin me-1"></i>Verificando carnet...</div>'
            );

            $.post("{{ route('admin.estudiantes.verificar-carnet') }}", {
                _token: "{{ csrf_token() }}",
                carnet: carnet
            }, function(res) {
                if (res.exists) {
                    if (res.is_student) {
                        const ofertaId = $('#oferta_id_inscripcion').val();
                        $.post("{{ route('admin.inscripciones.verificar-inscripcion-existente') }}", {
                            _token: "{{ csrf_token() }}",
                            estudiante_id: res.estudiante_id,
                            oferta_id: ofertaId
                        }, function(verif) {
                            if (verif.exists) {
                                $('#mensaje-verificacion-inscripcion').html(
                                    '<div class="text-center"><div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background:#fef3c7;color:#d97706;font-size:0.82rem;font-weight:500;"><i class="ri-alert-line"></i>Esta persona ya está inscrita en esta oferta</div></div>'
                                );
                                $('#btn-nueva-persona-inscripcion').prop('disabled', true).css({ opacity: 0.5, cursor: 'not-allowed' });
                            } else {
                                $('#mensaje-verificacion-inscripcion').html(
                                    '<div class="text-center"><div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background:#dcfce7;color:#16a34a;font-size:0.82rem;font-weight:500;"><i class="ri-checkbox-circle-line"></i>Estudiante encontrado — continuando...</div></div>'
                                );
                                $('#estudiante_id_inscripcion').val(res.estudiante_id);
                                setTimeout(() => {
                                    $('#formInscripcion').show();
                                    $('#paso-carnet-inscripcion, #formConfirmarEstudiante, #formNuevaPersonaInscripcion').hide();
                                    cargarPlanesPago(res.estudiante_id, ofertaId);
                                }, 600);
                            }
                        }).fail(function() {
                            $('#mensaje-verificacion-inscripcion').html(
                                '<div class="text-center"><div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background:#fef2f2;color:#dc2626;font-size:0.82rem;font-weight:500;"><i class="ri-error-warning-line"></i>Error al verificar inscripción</div></div>'
                            );
                        });
                    } else {
                        $('#mensaje-verificacion-inscripcion').html(
                            '<div class="text-center"><div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background:#dbeafe;color:#2563eb;font-size:0.82rem;font-weight:500;"><i class="ri-user-line"></i>Persona encontrada — confirmar como estudiante</div></div>'
                        );
                        $('#persona_id_confirmar').val(res.persona.id);
                        $('#nombre_persona_confirmar').text(res.persona.nombre_completo);
                        $('#formConfirmarEstudiante').show();
                        $('#paso-carnet-inscripcion, #formNuevaPersonaInscripcion, #formInscripcion').hide();
                        $('#btn-nueva-persona-inscripcion').prop('disabled', true).css({ opacity: 0.5, cursor: 'not-allowed' });
                    }
                } else {
                    $('#mensaje-verificacion-inscripcion').html(
                        '<div class="text-center"><div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background:#fef2f2;color:#dc2626;font-size:0.82rem;font-weight:500;"><i class="ri-user-unfollow-line"></i>Persona no encontrada en el sistema</div></div>'
                    );
                    $('#btn-nueva-persona-inscripcion').prop('disabled', false).css({ opacity: 1, cursor: 'pointer' });
                }
            }).fail(function() {
                $('#mensaje-verificacion-inscripcion').html(
                    '<div class="text-center"><div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background:#fef2f2;color:#dc2626;font-size:0.82rem;font-weight:500;"><i class="ri-error-warning-line"></i>Error al verificar el carnet</div></div>'
                );
            });
        }, 500);
    });

    // Función para cargar los planes de pago
    function cargarPlanesPago(estudianteId, ofertaId) {
        $('#planes_pago_select').html('<option value="">Cargando planes...</option>');

        $.ajax({
            url: `/admin/ofertas/${ofertaId}/planes-inscripcion`,
            method: 'GET',
            success: function(res) {
                if (res.success && res.planes.length > 0) {
                    let opts = '<option value="">Seleccione un plan</option>';
                    res.planes.forEach(plan => {
                        const esPromo = parseInt(plan.es_promocion) === 1;
                        let label = plan.nombre;
                        if (esPromo) {
                            const fin = plan.fecha_fin_promocion ? new Date(plan.fecha_fin_promocion).toLocaleDateString() : '';
                            label += ` 🏷️ (Promoción hasta ${fin})`;
                        }
                        opts += `<option value="${plan.id}">${label}</option>`;
                    });
                    $('#planes_pago_select').html(opts);
                } else {
                    $('#planes_pago_select').html('<option value="">No hay planes disponibles</option>');
                    mostrarToast('warning', 'No hay planes de pago disponibles para esta oferta');
                }
            },
            error: function(xhr) {
                console.error('Error al cargar planes de pago:', xhr);
                $('#planes_pago_select').html('<option value="">Error al cargar planes</option>');
                mostrarToast('error', 'Error al cargar los planes de pago');
            }
        });
    }

    // Botón "Registrar nueva persona"
    $('#btn-nueva-persona-inscripcion').on('click', function() {
        $('#paso-carnet-inscripcion').hide();
        $('#formNuevaPersonaInscripcion').show();

        const carnet = $('#carnet_inscripcion').val().trim();
        if (carnet) {
            $('#carnet_nuevo_inscripcion').val(carnet);
            $('#carnet_nuevo_inscripcion').trigger('input');
        }
    });

    // Volver desde nueva persona
    $('#btn-volver-carnet2-incripcion').on('click', function() {
        $('#formNuevaPersonaInscripcion').hide();
        $('#paso-carnet-inscripcion').show();
        $('#carnet_inscripcion').val('');
        $('#mensaje-verificacion-inscripcion').html('');
        $('#btn-nueva-persona-inscripcion').prop('disabled', true).css({ opacity: 0.5, cursor: 'not-allowed' });
    });

    // Volver desde confirmar estudiante
    $('#btn-volver-carnet-confirmar').on('click', function() {
        $('#formConfirmarEstudiante').hide();
        $('#paso-carnet-inscripcion').show();
        $('#carnet_inscripcion').val('');
        $('#mensaje-verificacion-inscripcion').html('');
        $('#btn-nueva-persona-inscripcion').prop('disabled', true).css({ opacity: 0.5, cursor: 'not-allowed' });
    });

    // Confirmar registro como estudiante
    $('#btn-confirmar-estudiante').on('click', function() {
        const personaId = $('#persona_id_confirmar').val();
        const ofertaId = $('#oferta_id_inscripcion').val();

        $.ajax({
            url: "{{ route('admin.estudiantes.registrar') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                persona_id: personaId
            },
            success: function(res) {
                if (res.success) {
                    $('#estudiante_id_inscripcion').val(personaId);
                    $('#formInscripcion').show();
                    $('#formConfirmarEstudiante').hide();
                    cargarPlanesPago(personaId, ofertaId);
                } else {
                    mostrarToast('error', res.msg || 'Error al registrar como estudiante.');
                }
            },
            error: function(xhr) {
                mostrarToast('error', xhr.responseJSON?.msg || 'Error al registrar como estudiante.');
            }
        });
    });

    // Volver desde inscripción
    $('#btn-volver-estudiante-incripcion').on('click', function() {
        $('#formInscripcion').hide();
        if ($('#persona_id_confirmar').val()) {
            $('#formConfirmarEstudiante').show();
        } else {
            $('#formNuevaPersonaInscripcion').show();
        }
    });

    // Cambio de estado de inscripción
    $('#estado_inscripcion').on('change', function() {
        const estado = $(this).val();
        const planSeleccionado = $('#planes_pago_select').val();

        if (estado === 'Inscrito') {
            $('#adelanto-section').hide();
            $('#cuotas-preview-section').show();
            $('#cuotas-preview-container').empty();
            $('#confirmar-cuotas-btn').hide();
            $('#generar-cuotas-btn').show().prop('disabled', !planSeleccionado);
        } else if (estado === 'Pre-Inscrito') {
            $('#adelanto-section').show();
            $('#cuotas-preview-section').hide();
            $('#cuotas-preview-container').empty();
            $('#generar-cuotas-btn').hide();
            $('#confirmar-cuotas-btn').hide();
        } else {
            $('#adelanto-section, #cuotas-preview-section').hide();
            $('#generar-cuotas-btn, #confirmar-cuotas-btn').hide();
        }
    });

    // Cambio de plan de pago
    $('#planes_pago_select').on('change', function() {
        const estado = $('#estado_inscripcion').val();
        const planId = $(this).val();

        if (estado === 'Inscrito' && planId) {
            $('#cuotas-preview-section').show();
            $('#cuotas-preview-container').empty();
            $('#confirmar-cuotas-btn').hide();
            $('#generar-cuotas-btn').show().prop('disabled', false);
        } else if (estado === 'Pre-Inscrito') {
            $('#generar-cuotas-btn').hide();
            $('#confirmar-cuotas-btn').hide();
        }
    });

    // Generar vista previa de cuotas
    $('#generar-cuotas-btn').on('click', function() {
        const planId = $('#planes_pago_select').val();
        const ofertaId = $('#oferta_id_inscripcion').val();

        if (!planId) {
            mostrarToast('warning', 'Seleccione un plan de pago primero.');
            return;
        }

        $('#cuotas-preview-container').html(
            '<div class="text-center py-3"><div class="spinner-border" style="color:var(--ofertas-primary);width:1.5rem;height:1.5rem;" role="status"></div><p class="text-muted mt-2 mb-0 small">Generando cuotas...</p></div>'
        );

        $.ajax({
            url: "{{ route('admin.inscripciones.generar-cuotas-preview') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                oferta_id: ofertaId,
                planes_pago_id: planId
            },
            success: function(res) {
                if (res.success) {
                    renderizarCuotasPreview(res.cuotas_preview);
                    $('#confirmar-cuotas-btn').show();
                    $('#generar-cuotas-btn').hide();
                } else {
                    $('#cuotas-preview-container').html(
                        `<div class="text-center py-3"><div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background:#fef2f2;color:#dc2626;font-size:0.82rem;"><i class="ri-error-warning-line"></i>${res.msg}</div></div>`
                    );
                }
            },
            error: function() {
                $('#cuotas-preview-container').html(
                    '<div class="text-center py-3"><div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background:#fef2f2;color:#dc2626;font-size:0.82rem;"><i class="ri-error-warning-line"></i>Error al generar la vista previa</div></div>'
                );
            }
        });
    });

    // Renderizar cuotas preview
    function renderizarCuotasPreview(cuotas) {
        let html = `
        <div class="table-responsive">
            <table class="table table-sm" style="font-size:0.82rem;">
                <thead>
                    <tr style="background:var(--ofertas-surface);">
                        <th style="padding:8px 12px;font-size:0.7rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:var(--ofertas-text-muted);border-bottom:1px solid var(--ofertas-border);">CONCEPTO</th>
                        <th style="padding:8px 12px;font-size:0.7rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:var(--ofertas-text-muted);border-bottom:1px solid var(--ofertas-border);text-align:center;">N°</th>
                        <th style="padding:8px 12px;font-size:0.7rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:var(--ofertas-text-muted);border-bottom:1px solid var(--ofertas-border);text-align:end;">MONTO</th>
                        <th style="padding:8px 12px;font-size:0.7rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:var(--ofertas-text-muted);border-bottom:1px solid var(--ofertas-border);text-align:center;">FECHA PAGO</th>
                    </tr>
                </thead>
                <tbody>`;

        cuotas.forEach(cuota => {
            html += `
                <tr>
                    <td style="padding:8px 12px;border-bottom:1px solid var(--ofertas-border);font-weight:500;">${cuota.concepto_nombre}</td>
                    <td style="padding:8px 12px;border-bottom:1px solid var(--ofertas-border);text-align:center;">
                        <span class="badge rounded-pill" style="background:var(--ofertas-primary-light);color:var(--ofertas-primary);font-size:0.72rem;font-weight:600;">${cuota.n_cuota}</span>
                    </td>
                    <td style="padding:8px 12px;border-bottom:1px solid var(--ofertas-border);text-align:end;">
                        <input type="number" class="form-control form-control-sm text-end fw-bold" value="${cuota.pago_total_bs}" readonly
                            style="border-radius:var(--radius-sm);border:1px solid var(--ofertas-border);font-size:0.82rem;background:var(--ofertas-surface);">
                    </td>
                    <td style="padding:8px 12px;border-bottom:1px solid var(--ofertas-border);text-align:center;">
                        <input type="date" class="form-control form-control-sm fecha-pago-input"
                            value="${cuota.fecha_pago}"
                            data-concepto-id="${cuota.concepto_id}"
                            data-n-cuota="${cuota.n_cuota}"
                            style="border-radius:var(--radius-sm);border:1px solid var(--ofertas-border);font-size:0.82rem;">
                    </td>
                </tr>`;
        });

        html += `</tbody></table></div>`;
        $('#cuotas-preview-container').html(html);
    }

    // Confirmar cuotas
    $('#confirmar-cuotas-btn').on('click', function() {
        const ofertaId = $('#oferta_id_inscripcion').val();
        const estudianteId = $('#estudiante_id_inscripcion').val();
        const planId = $('#planes_pago_select').val();

        const cuotasData = [];
        $('#cuotas-preview-container tbody tr').each(function() {
            const conceptoId = $(this).find('.fecha-pago-input').data('concepto-id');
            const nCuota = $(this).find('.fecha-pago-input').data('n-cuota');
            const fechaPago = $(this).find('.fecha-pago-input').val();
            const montoPorCuota = parseFloat($(this).find('input[type="number"]').val());

            cuotasData.push({
                concepto_id: conceptoId,
                n_cuota: nCuota,
                fecha_pago: fechaPago,
                monto_bs: montoPorCuota
            });
        });

        $.ajax({
            url: "{{ route('admin.inscripciones.confirmar-cuotas') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                oferta_id: ofertaId,
                estudiante_id: estudianteId,
                planes_pago_id: planId,
                estado: 'Inscrito',
                cuotas_data: cuotasData
            },
            success: function(res) {
                mostrarToast(res.success ? 'success' : 'error', res.msg);
                if (res.success) {
                    $('#modalInscribirEstudiante').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr) {
                mostrarToast('error', xhr.responseJSON?.msg || 'Error al confirmar la inscripción.');
            }
        });
    });

    // Submit form inscripción (Pre-Inscrito)
    $('#formInscripcion').submit(function(e) {
        e.preventDefault();

        const estado = $('#estado_inscripcion').val();
        const planId = $('#planes_pago_select').val();
        const ofertaId = $('#oferta_id_inscripcion').val();
        const estudianteId = $('#estudiante_id_inscripcion').val();

        if (!planId) {
            mostrarToast('warning', 'Seleccione un plan de pago.');
            return;
        }

        if (estado === 'Inscrito') {
            mostrarToast('warning', 'Para "Inscrito", genere y confirme las cuotas primero.');
            return;
        }

        if (!ofertaId || !estudianteId) {
            mostrarToast('error', 'Datos incompletos. Intente nuevamente.');
            return;
        }

        const btn = $('#btn-registrar-inscripcion');
        const originalHtml = btn.html();
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Registrando...');

        const data = {
            _token: "{{ csrf_token() }}",
            oferta_id: ofertaId,
            estudiante_id: estudianteId,
            estado: estado,
            planes_pago_id: planId
        };

        if (estado === 'Pre-Inscrito') {
            data.adelanto_bs = parseFloat($('#adelanto_bs').val()) || 0;
        }

        $.ajax({
            url: "{{ route('admin.inscripciones.registrar') }}",
            method: 'POST',
            data: data,
            success: function(res) {
                mostrarToast(res.success ? 'success' : 'error', res.msg);
                if (res.success) {
                    $('#modalInscribirEstudiante').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error al registrar la inscripción.';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.msg) {
                        errorMsg = xhr.responseJSON.msg;
                    } else if (xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat();
                        errorMsg = errors.join('. ');
                    }
                }
                mostrarToast('error', errorMsg);
            },
            complete: function() {
                btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    // Submit form nueva persona
    $('#formNuevaPersonaInscripcion').submit(function(e) {
        e.preventDefault();
        
        if (!validarApellidosNuevoInscripcion()) return;
        if ($('#fecha_nac_nuevo_inscripcion').val() && !calcularEdadNuevoInscripcion()) return;

        const btn = $('#btn-guardar-nueva-persona-incripcion');
        const originalHtml = btn.html();
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Registrando...');

        $.ajax({
            url: "{{ route('admin.estudiantes.registrar-persona-estudiante') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.success) {
                    $('#estudiante_id_inscripcion').val(res.student_id);
                    $('#formInscripcion').show();
                    $('#formNuevaPersonaInscripcion').hide();
                    cargarPlanesPago(res.student_id, $('#oferta_id_inscripcion').val());
                } else {
                    mostrarToast('error', res.msg || 'Error al registrar la persona.');
                    btn.prop('disabled', false).html(originalHtml);
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error al registrar la persona.';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.msg) {
                        errorMsg = xhr.responseJSON.msg;
                    } else if (xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat();
                        errorMsg = errors.join('. ');
                        if (xhr.responseJSON.errors.carnet) $('#feedback_carnet_nuevo_inscripcion').addClass('text-danger').text('❌ ' + xhr.responseJSON.errors.carnet[0]);
                        if (xhr.responseJSON.errors.correo) $('#feedback_correo_nuevo_inscripcion').addClass('text-danger').text('❌ ' + xhr.responseJSON.errors.correo[0]);
                        if (xhr.responseJSON.errors.apellidos) $('#feedback_apellidos_nuevo_inscripcion').text('⚠️ ' + xhr.responseJSON.errors.apellidos[0]);
                    }
                }
                mostrarToast('error', errorMsg);
                btn.prop('disabled', false).html(originalHtml);
                checkFormNuevaPersonaInscripcion();
            }
        });
    });

    // === VALIDACIONES TIEMPO REAL NUEVA PERSONA ===
    function validarApellidosNuevoInscripcion() {
        const p = $('#paterno_nuevo_inscripcion').val().trim();
        const m = $('#materno_nuevo_inscripcion').val().trim();
        if (!p && !m) {
            $('#feedback_apellidos_nuevo_inscripcion').text('⚠️ Ingrese al menos un apellido');
            return false;
        } else {
            $('#feedback_apellidos_nuevo_inscripcion').text('');
            return true;
        }
    }

    function calcularEdadNuevoInscripcion() {
        const fecha = $('#fecha_nac_nuevo_inscripcion').val();
        if (!fecha) {
            $('#edad_calculada_nuevo_inscripcion').text('').removeClass('text-danger text-success');
            return true;
        }
        const hoy = new Date();
        const nac = new Date(fecha);
        let edad = hoy.getFullYear() - nac.getFullYear();
        const mes = hoy.getMonth() - nac.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < nac.getDate())) edad--;
        if (edad < 18) {
            $('#edad_calculada_nuevo_inscripcion').addClass('text-danger').removeClass('text-success').text('⚠️ Debe tener al menos 18 años');
            return false;
        } else {
            $('#edad_calculada_nuevo_inscripcion').removeClass('text-danger').addClass('text-success').text(`✅ Edad: ${edad} años`);
            return true;
        }
    }

    function checkFormNuevaPersonaInscripcion() {
        const carnetVal = $('#carnet_nuevo_inscripcion').val().trim();
        const correoVal = $('#correo_nuevo_inscripcion').val().trim();
        // Si el carnet está vacío, no está ok. Si tiene valor y no tiene error, está ok.
        const carnetOk = carnetVal && !$('#feedback_carnet_nuevo_inscripcion').hasClass('text-danger');
        const correoOk = correoVal && !$('#feedback_correo_nuevo_inscripcion').hasClass('text-danger');
        const nombres = $('#nombres_nuevo_inscripcion').val().trim();
        const celular = $('#celular_nuevo_inscripcion').val().trim();
        const ciudade = $('#ciudad_nuevo_inscripcion').val();
        const sexo = $('select[name="sexo"]').val();
        const ecivil = $('select[name="estado_civil"]').val();
        const apellidosOk = validarApellidosNuevoInscripcion();
        const edadOk = !$('#fecha_nac_nuevo_inscripcion').val() || calcularEdadNuevoInscripcion();
        const enabled = carnetOk && correoOk && nombres && celular && ciudade && sexo && ecivil && apellidosOk && edadOk;
        const btn = $('#btn-guardar-nueva-persona-incripcion');
        btn.prop('disabled', !enabled);
        if (enabled) {
            btn.css({ opacity: 1, cursor: 'pointer' });
        } else {
            btn.css({ opacity: 0.5, cursor: 'not-allowed' });
        }
    }

    // Validación carnet nueva persona
    $('#carnet_nuevo_inscripcion').on('input', function() {
        const carnet = $(this).val().trim();
        if (!carnet) {
            $('#feedback_carnet_nuevo_inscripcion').removeClass('text-success text-danger').text('');
            checkFormNuevaPersonaInscripcion();
            return;
        }
        clearTimeout(debounceCarnet);
        debounceCarnet = setTimeout(() => {
            $.post("{{ route('admin.personas.verificar-carnet') }}", {
                _token: "{{ csrf_token() }}",
                carnet: carnet
            }, function(res) {
                if (res.exists) {
                    $('#feedback_carnet_nuevo_inscripcion').removeClass('text-success').addClass('text-danger').text('❌ Carnet ya en uso');
                } else {
                    $('#feedback_carnet_nuevo_inscripcion').removeClass('text-danger').addClass('text-success').text('✅ Disponible');
                }
                checkFormNuevaPersonaInscripcion();
            }).fail(function() {
                $('#feedback_carnet_nuevo_inscripcion').removeClass('text-success text-danger').text('');
                checkFormNuevaPersonaInscripcion();
            });
        }, 400);
    });

    // Validación correo nueva persona
    $('#correo_nuevo_inscripcion').on('input', function() {
        const correo = $(this).val().trim();
        if (!correo) {
            $('#feedback_correo_nuevo_inscripcion').removeClass('text-success text-danger').text('');
            checkFormNuevaPersonaInscripcion();
            return;
        }
        clearTimeout(debounceCorreo);
        debounceCorreo = setTimeout(() => {
            $.post("{{ route('admin.personas.verificar-correo') }}", {
                _token: "{{ csrf_token() }}",
                correo: correo
            }, function(res) {
                if (res.exists) {
                    $('#feedback_correo_nuevo_inscripcion').removeClass('text-success').addClass('text-danger').text('❌ Correo ya en uso');
                } else {
                    $('#feedback_correo_nuevo_inscripcion').removeClass('text-danger').addClass('text-success').text('✅ Disponible');
                }
                checkFormNuevaPersonaInscripcion();
            }).fail(function() {
                $('#feedback_correo_nuevo_inscripcion').removeClass('text-success text-danger').text('');
                checkFormNuevaPersonaInscripcion();
            });
        }, 400);
    });

    $('#nombres_nuevo_inscripcion, #paterno_nuevo_inscripcion, #materno_nuevo_inscripcion, #celular_nuevo_inscripcion').on('input', function() {
        checkFormNuevaPersonaInscripcion();
    });

    $('select[name="sexo"], select[name="estado_civil"]').on('change', function() {
        checkFormNuevaPersonaInscripcion();
    });

    $('#fecha_nac_nuevo_inscripcion').on('input', function() {
        calcularEdadNuevoInscripcion();
        checkFormNuevaPersonaInscripcion();
    });

    $('#ciudad_nuevo_inscripcion').on('change', function() {
        checkFormNuevaPersonaInscripcion();
    });

    $('#departamento_nuevo_inscripcion').on('change', function() {
        const deptoId = $(this).val();
        llenarCiudadesPorDepartamento(deptoId, $('#ciudad_nuevo_inscripcion'));
        $('#ciudad_nuevo_inscripcion').val('');
        checkFormNuevaPersonaInscripcion();
    });

    function llenarCiudadesPorDepartamento(departamentoId, selectElement) {
        selectElement.empty();
        if (!departamentoId) {
            selectElement.append('<option value="">Primero seleccione un departamento</option>');
            selectElement.prop('disabled', true);
            return;
        }
        const ciudadesFiltradas = ciudadesConDepartamento.filter(c => c.departamento_id == departamentoId);
        if (ciudadesFiltradas.length === 0) {
            selectElement.append('<option value="">Sin ciudades</option>');
        } else {
            selectElement.append('<option value="">Seleccionar ciudad</option>');
            ciudadesFiltradas.forEach(c => {
                selectElement.append(`<option value="${c.id}">${c.nombre}</option>`);
            });
        }
        selectElement.prop('disabled', false);
    }

    $('#departamento_nuevo_inscripcion').trigger('change');

    // === ESTUDIOS ===
    $(document).on('change', '.grado-select-nuevo', function() {
        const row = $(this).closest('.estudio-item-nuevo');
        const gradoId = $(this).val();
        if (!gradoId) {
            row.find('.profesion-select-nuevo, .universidad-select-nuevo').prop('disabled', true)
                .html('<option value="">Profesión</option>');
            row.find('.universidad-select-nuevo').html('<option value="">Universidad</option>');
            return;
        }
        let htmlProf = '<option value="">Profesión</option>';
        @foreach ($profesiones as $p)
            htmlProf += `<option value="{{ $p->id }}">{{ $p->nombre }}</option>`;
        @endforeach
        row.find('.profesion-select-nuevo').html(htmlProf).prop('disabled', false);
        let htmlUni = '<option value="">Universidad</option>';
        @foreach ($universidades as $u)
            htmlUni += `<option value="{{ $u->id }}">{{ $u->nombre }} ({{ $u->sigla }})</option>`;
        @endforeach
        row.find('.universidad-select-nuevo').html(htmlUni).prop('disabled', false);
    });

    $(document).on('click', '.add-estudio-nuevo', function() {
        const index = $('.estudio-item-nuevo').length;
        let html = `
        <div class="estudio-item-nuevo row g-2 mb-2 align-items-end">
            <div class="col-md-3">
                <select class="form-select form-select-sm grado-select-nuevo" name="estudios[${index}][grado]" style="border-radius:var(--radius-sm);border:1px solid var(--ofertas-border);">
                    <option value="">Grado</option>
                    @foreach ($grados as $g)
                        <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select form-select-sm profesion-select-nuevo" name="estudios[${index}][profesion]" disabled style="border-radius:var(--radius-sm);border:1px solid var(--ofertas-border);">
                    <option value="">Profesión</option>
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select form-select-sm universidad-select-nuevo" name="estudios[${index}][universidad]" disabled style="border-radius:var(--radius-sm);border:1px solid var(--ofertas-border);">
                    <option value="">Universidad</option>
                    @foreach ($universidades as $u)
                        <option value="{{ $u->id }}">{{ $u->nombre }} ({{ $u->sigla }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm btn-danger remove-estudio-nuevo" style="border-radius:var(--radius-sm);padding:4px 8px;">−</button>
            </div>
        </div>`;
        $('#estudios-container-nuevo-incripcion').append(html);
    });

    $(document).on('click', '.remove-estudio-nuevo', function() {
        $(this).closest('.estudio-item-nuevo').remove();
    });

    // Convertir pre-inscrito
    $(document).on('click', '.convertir-inscrito-btn', function() {
        const inscripcionId = $(this).data('inscripcion-id');
        const ofertaId = $(this).data('oferta-id');
        const planPagoId = $(this).data('plan-pago-id');

        if (!inscripcionId || !ofertaId || !planPagoId) {
            Swal.fire({ title: 'Error', text: 'Faltan datos necesarios.', icon: 'error', confirmButtonColor: '#d33' });
            return;
        }

        Swal.fire({
            title: '¿Convertir a Inscrito?',
            html: 'Se generarán las cuotas según el plan seleccionado.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, convertir',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745',
            preConfirm: () => {
                return $.ajax({
                    url: "{{ route('admin.inscripciones.convertir-pre-inscrito', ['inscripcion' => '__id__']) }}".replace('__id__', inscripcionId),
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        inscripcion_id: inscripcionId,
                        oferta_id: ofertaId,
                        planes_pago_id: planPagoId
                    }
                }).then(r => { if (!r.success) throw new Error(r.msg); return r; })
                  .catch(e => Swal.showValidationMessage(`Error: ${e.responseJSON?.msg || e.message}`));
            }
        }).then(result => {
            if (result.isConfirmed) {
                Swal.fire('¡Éxito!', result.value.msg, 'success').then(() => location.reload());
            }
        });
    });
</script>
