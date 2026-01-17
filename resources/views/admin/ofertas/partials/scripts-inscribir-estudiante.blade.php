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
        $('#btn-nueva-persona-inscripcion').prop('disabled', true);

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
            $('#btn-nueva-persona-inscripcion').prop('disabled', true);
            return;
        }

        // Aplicar debounce - esperar 500ms después de la última tecla
        clearTimeout(debounceTimerInscripcion);

        debounceTimerInscripcion = setTimeout(() => {
            // Verificar si el valor no ha cambiado durante el debounce
            if ($('#carnet_inscripcion').val().trim() !== lastCarnetValue) {
                return;
            }

            // Mostrar indicador de carga
            $('#mensaje-verificacion-inscripcion').html(
                '<div class="text-info"><i class="ri-loader-4-line spin"></i> Verificando carnet...</div>'
            );

            $.post("{{ route('admin.estudiantes.verificar-carnet') }}", {
                _token: "{{ csrf_token() }}",
                carnet: carnet
            }, function(res) {
                if (res.exists) {
                    if (res.is_student) {
                        const ofertaId = $('#oferta_id_inscripcion').val();
                        // Verificar si ya está inscrito
                        $.post("{{ route('admin.inscripciones.verificar-inscripcion-existente') }}", {
                            _token: "{{ csrf_token() }}",
                            estudiante_id: res.estudiante_id,
                            oferta_id: ofertaId
                        }, function(verif) {
                            if (verif.exists) {
                                $('#mensaje-verificacion-inscripcion').html(
                                    '<div class="alert alert-warning">⚠️ Esta persona ya está inscrita o pre-inscrita en esta oferta académica.</div>'
                                );
                                $('#btn-nueva-persona-inscripcion').prop('disabled',
                                    true);
                            } else {
                                $('#mensaje-verificacion-inscripcion').html(
                                    '<div class="alert alert-success">✅ Persona encontrada y registrada como estudiante.</div>'
                                );
                                $('#estudiante_id_inscripcion').val(res.estudiante_id);
                                $('#formInscripcion').show();
                                $('#paso-carnet-inscripcion, #formConfirmarEstudiante, #formNuevaPersonaInscripcion')
                                    .hide();
                                cargarPlanesPago(res.estudiante_id, ofertaId);
                            }
                        }).fail(function() {
                            $('#mensaje-verificacion-inscripcion').html(
                                '<div class="alert alert-danger">Error al verificar inscripción existente.</div>'
                            );
                        });
                    } else {
                        // Persona existe pero no es estudiante
                        $('#mensaje-verificacion-inscripcion').html(
                            '<div class="alert alert-info">👤 Persona registrada pero no es estudiante.</div>'
                        );
                        $('#persona_id_confirmar').val(res.persona.id);
                        $('#nombre_persona_confirmar').text(res.persona.nombre_completo);
                        $('#formConfirmarEstudiante').show();
                        $('#paso-carnet-inscripcion, #formNuevaPersonaInscripcion, #formInscripcion')
                            .hide();
                        $('#btn-nueva-persona-inscripcion').prop('disabled', true);
                    }
                } else {
                    // Persona no existe
                    $('#mensaje-verificacion-inscripcion').html(
                        '<div class="alert alert-danger">❌ Persona no registrada en el sistema.</div>'
                    );
                    // Mostrar botón para nueva persona
                    $('#btn-nueva-persona-inscripcion').prop('disabled', false);
                    // NO mostramos automáticamente el formulario - solo habilitamos el botón
                }
            }).fail(function() {
                $('#mensaje-verificacion-inscripcion').html(
                    '<div class="alert alert-danger">Error al verificar el carnet.</div>'
                );
            });
        }, 500); // 500ms de debounce
    });

    // Función para cargar los planes de pago disponibles para la oferta
    function cargarPlanesPago(estudianteId, ofertaId) {
        // Mostrar indicador de carga
        $('#planes_pago_select').html('<option value="">Cargando planes...</option>');

        // Usar el nuevo endpoint para obtener planes filtrados
        $.ajax({
            url: `/admin/ofertas/${ofertaId}/planes-inscripcion`,
            method: 'GET',
            success: function(res) {
                if (res.success && res.planes.length > 0) {
                    let opts = '<option value="">Seleccione un plan</option>';
                    res.planes.forEach(plan => {
                        // Agregar indicador si es promoción
                        let promocionInfo = '';
                        if (plan.es_promocion == 1) {
                            const inicio = plan.fecha_inicio_promocion ?
                                new Date(plan.fecha_inicio_promocion).toLocaleDateString() : '';
                            const fin = plan.fecha_fin_promocion ?
                                new Date(plan.fecha_fin_promocion).toLocaleDateString() : '';
                            promocionInfo = ` 🏷️ (Promoción hasta ${fin})`;
                        }

                        opts +=
                            `<option value="${plan.id}">${plan.nombre}${promocionInfo}</option>`;
                    });
                    $('#planes_pago_select').html(opts);

                    // Mostrar información adicional si solo hay un plan
                    if (res.planes.length === 1) {
                        mostrarToast('info', 'Solo hay un plan disponible para esta oferta');
                    }
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

    // Evento para el botón "Registrar nueva persona"
    $('#btn-nueva-persona-inscripcion').on('click', function() {
        // Solo mostrar formulario cuando se haga clic en el botón
        $('#paso-carnet-inscripcion').hide();
        $('#formNuevaPersonaInscripcion').show();

        // Prellenar el carnet en el formulario de nueva persona
        const carnet = $('#carnet_inscripcion').val().trim();
        if (carnet) {
            $('#carnet_nuevo_inscripcion').val(carnet);
            // Disparar validación del carnet
            $('#carnet_nuevo_inscripcion').trigger('input');
        }
    });

    // Evento para volver desde el formulario de nueva persona
    $('#btn-volver-carnet2-incripcion').on('click', function() {
        $('#formNuevaPersonaInscripcion').hide();
        $('#paso-carnet-inscripcion').show();
        $('#carnet_inscripcion').val('');
        $('#mensaje-verificacion-inscripcion').html('');
        $('#btn-nueva-persona-inscripcion').prop('disabled', true);
    });

    // Evento para volver desde el formulario de confirmar estudiante
    $('#btn-volver-carnet-confirmar').on('click', function() {
        $('#formConfirmarEstudiante').hide();
        $('#paso-carnet-inscripcion').show();
        $('#carnet_inscripcion').val('');
        $('#mensaje-verificacion-inscripcion').html('');
        $('#btn-nueva-persona-inscripcion').prop('disabled', true);
    });

    // Evento para volver desde el formulario de inscripción
    $('#btn-volver-estudiante-incripcion').on('click', function() {
        $('#formInscripcion').hide();
        // Si venimos de una persona existente pero no estudiante, volver a ese paso
        if ($('#persona_id_confirmar').val()) {
            $('#formConfirmarEstudiante').show();
        } else {
            // Si venimos de una persona nueva, volver a ese paso
            $('#formNuevaPersonaInscripcion').show();
        }
    });

    // Evento para cambiar el estado de inscripción
    $('#estado_inscripcion').on('change', function() {
        const estado = $(this).val();
        const planSeleccionado = $('#planes_pago_select').val();

        if (estado === 'Inscrito') {
            // Para Inscrito: mostrar vista previa de cuotas
            $('#adelanto-section').hide();
            $('#cuotas-preview-section').show();
            $('#cuotas-preview-container').empty();
            $('#confirmar-cuotas-btn').hide();

            if (planSeleccionado) {
                $('#generar-cuotas-btn').show();
            } else {
                $('#generar-cuotas-btn').hide();
            }
        } else if (estado === 'Pre-Inscrito') {
            // Para Pre-Inscrito: mostrar campo de adelanto (opcional)
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

    // Evento para mostrar el botón "Generar Cuotas" al seleccionar un plan de pago
    $('#planes_pago_select').on('change', function() {
        const estado = $('#estado_inscripcion').val();
        const planId = $(this).val();

        if (estado === 'Inscrito' && planId) {
            $('#cuotas-preview-section').show();
            $('#cuotas-preview-container').empty();
            $('#confirmar-cuotas-btn').hide();
            $('#generar-cuotas-btn').show();
        } else if (estado === 'Pre-Inscrito') {
            $('#generar-cuotas-btn').hide();
            $('#confirmar-cuotas-btn').hide();
        }
    });

    // Función para confirmar cuotas (solo para "Inscrito")
    function confirmarCuotasInscrito() {
        const ofertaId = $('#oferta_id_inscripcion').val();
        const estudianteId = $('#estudiante_id_inscripcion').val();
        const planId = $('#planes_pago_select').val();
        const estado = 'Inscrito'; // Siempre Inscrito aquí

        // Recoger datos de cuotas
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
                estado: estado,
                cuotas_data: cuotasData
            },
            success: function(res) {
                alert(res.msg);
                if (res.success) {
                    $('#modalInscribirEstudiante').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.msg || 'Error al confirmar las cuotas.');
            }
        });
    }

    // Evento para generar la vista previa de cuotas
    $('#generar-cuotas-btn').on('click', function() {
        const estado = $('#estado_inscripcion').val();
        const planId = $('#planes_pago_select').val();
        const ofertaId = $('#oferta_id_inscripcion').val();

        if (estado !== 'Inscrito' || !planId) {
            alert('Por favor, seleccione un plan de pago válido.');
            return;
        }

        // Mostrar spinner
        $('#cuotas-preview-container').html(
            '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>'
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
                        `<div class="alert alert-danger">${res.msg}</div>`
                    );
                }
            },
            error: function(xhr) {
                $('#cuotas-preview-container').html(
                    `<div class="alert alert-danger">Error al generar la vista previa de cuotas.</div>`
                );
            }
        });
    });

    // Función para renderizar la vista previa de las cuotas (versión final)
    function renderizarCuotasPreview(cuotas) {
        let html = `
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>N° Cuota</th>
                        <th>Monto por Cuota</th>
                        <th>Fecha de Pago</th>
                    </tr>
                </thead>
                <tbody>
        `;

        cuotas.forEach(cuota => {
            html += `
            <tr>
                <td>${cuota.concepto_nombre}</td>
                <td>${cuota.n_cuota}</td>
                <td>
                    <input type="number" class="form-control" value="${cuota.pago_total_bs}" readonly>
                </td>
                <td>
                    <input type="date" class="form-control fecha-pago-input" 
                           value="${cuota.fecha_pago}" 
                           data-concepto-id="${cuota.concepto_id}" 
                           data-n-cuota="${cuota.n_cuota}">
                </td>
            </tr>
        `;
        });

        html += `
                </tbody>
            </table>
        </div>
        `;

        $('#cuotas-preview-container').html(html);
    }

    // Evento para confirmar la inscripción con cuotas
    $('#confirmar-cuotas-btn').on('click', function() {
        const estado = $('#estado_inscripcion').val();
        const planId = $('#planes_pago_select').val();
        const ofertaId = $('#oferta_id_inscripcion').val();
        const estudianteId = $('#estudiante_id_inscripcion').val();

        if (estado !== 'Inscrito') {
            alert('Esta función solo está disponible para inscripciones.');
            return;
        }

        // Recoger las fechas de pago y los montos
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

        // Enviar datos al backend
        $.ajax({
            url: "{{ route('admin.inscripciones.confirmar-cuotas') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                oferta_id: ofertaId,
                estudiante_id: estudianteId,
                planes_pago_id: planId,
                estado: estado,
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
                const errorMsg = xhr.responseJSON?.msg || 'Error al confirmar la inscripción.';
                mostrarToast('error', errorMsg);
            }
        });
    });

    // Submit del formulario de confirmar estudiante
    $('#formConfirmarEstudiante').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('admin.estudiantes.registrar') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.success) {
                    // Ahora que es estudiante, mostrar el formulario de inscripción
                    $('#estudiante_id_inscripcion').val($('#persona_id_confirmar').val());
                    $('#formInscripcion').show();
                    $('#formConfirmarEstudiante').hide();
                    cargarPlanesPago($('#persona_id_confirmar').val(), $('#oferta_id_inscripcion')
                        .val());
                } else {
                    alert(res.msg || 'Error al registrar como estudiante.');
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.msg || 'Error al registrar como estudiante.');
            }
        });
    });

    // Submit del formulario de nueva persona
    $('#formNuevaPersonaInscripcion').submit(function(e) {
        e.preventDefault();
        // Validaciones adicionales (opcional, puedes reutilizar las del original)
        if (!validarApellidosNuevoInscripcion()) return;
        if ($('#fecha_nac_nuevo_inscripcion').val() && !calcularEdadNuevoInscripcion()) return;

        $.ajax({
            url: "{{ route('admin.estudiantes.registrar-persona-estudiante') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.success) {
                    // Obtener el ID del estudiante recién creado
                    // Asumiendo que el controlador devuelve el ID del estudiante en res.student_id
                    // Si no, necesitarás modificar el controlador para devolverlo.
                    $('#estudiante_id_inscripcion').val(res
                        .student_id); // <-- Requiere modificación en el controlador
                    $('#formInscripcion').show();
                    $('#formNuevaPersonaInscripcion').hide();
                    cargarPlanesPago(res.student_id, $('#oferta_id_inscripcion').val());
                } else {
                    alert(res.msg || 'Error al registrar la persona y estudiante.');
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                if (errors.carnet) $('#feedback_carnet_nuevo_inscripcion').addClass('text-danger')
                    .text(errors.carnet[0]);
                if (errors.correo) $('#feedback_correo_nuevo_inscripcion').addClass('text-danger')
                    .text(errors.correo[0]);
                if (errors.apellidos) $('#feedback_apellidos_nuevo_inscripcion').text(errors
                    .apellidos[0]);
                checkFormNuevaPersonaInscripcion();
            }
        });
    });

    // Submit del formulario de inscripción MEJORADO
    $('#formInscripcion').submit(function(e) {
        e.preventDefault();

        // DEBUG: Mostrar la URL que se va a usar
        const url = "{{ route('admin.inscripciones.registrar') }}";
        console.log('URL de registro:', url);
        const estado = $('#estado_inscripcion').val();
        const planId = $('#planes_pago_select').val();
        const adelanto = $('#adelanto_bs').val();

        // Validaciones
        if (!planId) {
            alert('Por favor, seleccione un plan de pago.');
            return;
        }

        if (estado === 'Inscrito') {
            // Para Inscrito: usar confirmación de cuotas
            alert('Para "Inscrito", por favor genere y confirme las cuotas primero.');
            return;
        }

        // Para Pre-Inscrito: enviar directamente
        const formData = $(this).serialize();

        $.ajax({
            url: "{{ route('admin.inscripciones.registrar') }}",
            method: 'POST',
            data: formData,
            success: function(res) {
                mostrarToast(res.success ? 'success' : 'error', res.msg);
                if (res.success) {
                    $('#modalInscribirEstudiante').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr) {
                console.error('Error completo:', xhr);
                console.error('Status:', xhr.status);
                console.error('Response:', xhr.responseText);

                const errorMsg = xhr.responseJSON?.msg || 'Error al registrar la inscripción.';
                mostrarToast('error', errorMsg);
            }
        });
    });



    // === VALIDACIONES PARA EL FORMULARIO DE NUEVA PERSONA ===
    function validarApellidosNuevoInscripcion() {
        const p = $('#paterno_nuevo_inscripcion').val().trim();
        const m = $('#materno_nuevo_inscripcion').val().trim();
        if (!p && !m) {
            $('#feedback_apellidos_nuevo_inscripcion').text('Debe ingresar al menos un apellido.');
            return false;
        } else {
            $('#feedback_apellidos_nuevo_inscripcion').text('');
            return true;
        }
    }

    function calcularEdadNuevoInscripcion() {
        const fecha = $('#fecha_nac_nuevo_inscripcion').val();
        if (!fecha) {
            $('#edad_calculada_nuevo_inscripcion').text('');
            return true;
        }
        const hoy = new Date();
        const nac = new Date(fecha);
        let edad = hoy.getFullYear() - nac.getFullYear();
        const mes = hoy.getMonth() - nac.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < nac.getDate())) edad--;
        if (edad < 18) {
            $('#edad_calculada_nuevo_inscripcion').addClass('text-danger').text('⚠️ Debe tener al menos 18 años.');
            return false;
        } else {
            $('#edad_calculada_nuevo_inscripcion').removeClass('text-danger').text(`Edad: ${edad} años`);
            return true;
        }
    }

    function checkFormNuevaPersonaInscripcion() {
        const carnetOk = $('#feedback_carnet_nuevo_inscripcion').hasClass('text-success');
        const correoOk = $('#feedback_correo_nuevo_inscripcion').hasClass('text-success');
        const nombres = $('#nombres_nuevo_inscripcion').val().trim();
        const celular = $('#celular_nuevo_inscripcion').val().trim();
        const ciudade = $('#ciudad_nuevo_inscripcion').val();
        const sexo = $('select[name="sexo"]').val();
        const ecivil = $('select[name="estado_civil"]').val();
        const apellidosOk = validarApellidosNuevoInscripcion();
        const edadOk = !$('#fecha_nac_nuevo_inscripcion').val() || calcularEdadNuevoInscripcion();
        const enabled = carnetOk && correoOk && nombres && celular && ciudade && sexo && ecivil &&
            apellidosOk && edadOk;
        $('#btn-guardar-nueva-persona-incripcion').prop('disabled', !enabled);
    }

    // === DINÁMICA DE ESTUDIOS (NUEVA PERSONA) ===
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
            htmlUni +=
                `<option value="{{ $u->id }}">{{ $u->nombre }} ({{ $u->sigla }})</option>`;
        @endforeach
        row.find('.universidad-select-nuevo').html(htmlUni).prop('disabled', false);
    });

    $(document).on('click', '.add-estudio-nuevo', function() {
        const index = $('.estudio-item-nuevo').length;
        let html = `
        <div class="estudio-item-nuevo row mb-2">
            <div class="col-md-3">
                <select class="form-select grado-select-nuevo" name="estudios[${index}][grado]">
                    <option value="">Grado</option>
                    @foreach ($grados as $g)
                        <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select profesion-select-nuevo" name="estudios[${index}][profesion]" disabled>
                    <option value="">Profesión</option>
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select universidad-select-nuevo" name="estudios[${index}][universidad]" disabled>
                    <option value="">Universidad</option>
                    @foreach ($universidades as $u)
                        <option value="{{ $u->id }}">{{ $u->nombre }} ({{ $u->sigla }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-estudio-nuevo">−</button>
            </div>
        </div>`;
        $('#estudios-container-nuevo-incripcion').append(html);
    });

    $(document).on('click', '.remove-estudio-nuevo', function() {
        $(this).closest('.estudio-item-nuevo').remove();
    });

    // === EVENTOS PARA VALIDACIÓN EN TIEMPO REAL ===
    $('#carnet_nuevo_inscripcion').on('input', function() {
        const carnet = $(this).val().trim();
        if (!carnet) {
            $('#feedback_carnet_nuevo_inscripcion').removeClass('text-success text-danger').text('');
            return;
        }
        clearTimeout(debounceCarnet);
        debounceCarnet = setTimeout(() => {
            $.post("{{ route('admin.personas.verificar-carnet') }}", {
                _token: "{{ csrf_token() }}",
                carnet: carnet
            }, function(res) {
                if (res.exists) {
                    $('#feedback_carnet_nuevo_inscripcion').removeClass('text-success')
                        .addClass('text-danger').text('❌ Carnet ya en uso.');
                } else {
                    $('#feedback_carnet_nuevo_inscripcion').removeClass('text-danger').addClass(
                        'text-success').text('✅ Disponible.');
                }
                checkFormNuevaPersonaInscripcion();
            });
        }, 400);
    });

    $('#correo_nuevo_inscripcion').on('input', function() {
        const correo = $(this).val().trim();
        if (!correo) {
            $('#feedback_correo_nuevo_inscripcion').removeClass('text-success text-danger').text('');
            return;
        }
        clearTimeout(debounceCorreo);
        debounceCorreo = setTimeout(() => {
            $.post("{{ route('admin.personas.verificar-correo') }}", {
                _token: "{{ csrf_token() }}",
                correo: correo
            }, function(res) {
                if (res.exists) {
                    $('#feedback_correo_nuevo_inscripcion').removeClass('text-success')
                        .addClass('text-danger').text('❌ Correo ya en uso.');
                } else {
                    $('#feedback_correo_nuevo_inscripcion').removeClass('text-danger').addClass(
                        'text-success').text('✅ Disponible.');
                }
                checkFormNuevaPersonaInscripcion();
            });
        }, 400);
    });

    $('#nombres_nuevo_inscripcion').on('input', function() {
        checkFormNuevaPersonaInscripcion();
    });

    $('#paterno_nuevo_inscripcion, #materno_nuevo_inscripcion').on('input', function() {
        validarApellidosNuevoInscripcion();
        checkFormNuevaPersonaInscripcion();
    });

    $('#celular_nuevo_inscripcion').on('input', function() {
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

    // Función auxiliar para llenar ciudades
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

    // Inicializar validaciones
    $('#departamento_nuevo_inscripcion').trigger('change');

    // Busca el script para convertir pre-inscrito y actualízalo
    $(document).on('click', '.convertir-inscrito-btn', function() {
        const inscripcionId = $(this).data('inscripcion-id'); // Este debería ser el ID de la inscripción
        const ofertaId = $(this).data('oferta-id');
        const planPagoId = $(this).data('plan-pago-id');

        // Validar que tenemos todos los datos necesarios
        if (!inscripcionId || !ofertaId || !planPagoId) {
            Swal.fire({
                title: 'Error',
                text: 'Faltan datos necesarios para realizar la conversión.',
                icon: 'error',
                confirmButtonColor: '#d33'
            });
            return;
        }

        Swal.fire({
            title: '¿Convertir a Inscrito?',
            html: `¿Está seguro de convertir esta pre-inscripción a inscripción completa?<br><br>
               <strong>Se realizarán las siguientes acciones:</strong><br>
               1. Cambiar estado a "Inscrito"<br>
               2. Generar cuotas según el plan de pago<br>
               3. Matricular en todos los módulos<br>
               4. Aplicar adelanto si existe`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, convertir',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: "{{ route('admin.inscripciones.convertir-pre-inscrito', ['inscripcion' => '__id__']) }}"
                        .replace('__id__', inscripcionId),
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        inscripcion_id: inscripcionId,
                        oferta_id: ofertaId,
                        planes_pago_id: planPagoId
                    }
                }).then(response => {
                    if (!response.success) {
                        throw new Error(response.msg || 'Error en la conversión');
                    }
                    return response;
                }).catch(error => {
                    Swal.showValidationMessage(
                        `Error: ${error.responseJSON?.msg || error.message}`
                    );
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: result.value.msg,
                    icon: 'success',
                    confirmButtonColor: '#28a745',
                    showConfirmButton: true
                }).then(() => {
                    // Recargar la página para ver los cambios
                    location.reload();
                });
            }
        });
    });
</script>
