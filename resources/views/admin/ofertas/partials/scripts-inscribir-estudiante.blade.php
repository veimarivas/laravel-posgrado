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
            if (planSeleccionado) {
                generarVistaPreviaCuotas();
            }
        } else if (estado === 'Pre-Inscrito') {
            $('#adelanto-section').show();
            $('#cuotas-preview-section').hide();
            $('#cuotas-preview-container').empty();
            $('#generar-cuotas-btn, #confirmar-cuotas-btn').hide();
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
            generarVistaPreviaCuotas();
        } else if (estado === 'Pre-Inscrito') {
            $('#cuotas-preview-section').hide();
            $('#generar-cuotas-btn, #confirmar-cuotas-btn').hide();
        }
    });

    // Función para generar vista previa de cuotas automáticamente
    function generarVistaPreviaCuotas() {
        const planId = $('#planes_pago_select').val();
        const ofertaId = $('#oferta_id_inscripcion').val();

        if (!planId) return;

        $('#cuotas-preview-container').html(
            '<div class="text-center py-3"><div class="spinner-border" style="color:var(--ofertas-primary);width:1.5rem;height:1.5rem;" role="status"></div><p class="text-muted mt-2 mb-0 small">Generando cuotas...</p></div>'
        );
        $('#confirmar-cuotas-btn').hide();
        $('#generar-cuotas-btn').hide();

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
                    renderizarCuotasPreviewAgrupadas(res.cuotas_preview);
                    $('#confirmar-cuotas-btn').show();
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
    }

    // Renderizar cuotas agrupadas por concepto con herramientas avanzadas
    function renderizarCuotasPreviewAgrupadas(cuotas) {
        // Guardar datos originales para referencia
        window.cuotasOriginalData = JSON.parse(JSON.stringify(cuotas));
        
        const grouped = {};
        let totalGeneral = 0;
        
        cuotas.forEach(cuota => {
            const conceptoKey = `${cuota.concepto_id}-${cuota.concepto_nombre}`;
            if (!grouped[conceptoKey]) {
                grouped[conceptoKey] = {
                    concepto_id: cuota.concepto_id,
                    concepto_nombre: cuota.concepto_nombre,
                    n_cuotas_total: cuota.n_cuotas,
                    original_pago_total: cuota.pago_total_bs,
                    cuotas: [],
                    total_concepto: 0
                };
            }
            grouped[conceptoKey].cuotas.push(cuota);
            grouped[conceptoKey].total_concepto += cuota.pago_total_bs;
            totalGeneral += cuota.pago_total_bs;
        });

        // Extraer día de la primera fecha para el selector
        const primeraFecha = cuotas[0]?.fecha_pago || '';
        const diaActual = primeraFecha ? parseInt(primeraFecha.split('-')[2]) : 14;

        let html = `
            <div class="mb-3 p-2 rounded" style="background: var(--ofertas-primary-light); border-left: 3px solid var(--ofertas-primary);">
                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size: 0.85rem; font-weight: 600; color: var(--ofertas-primary);">TOTAL A PAGAR</span>
                    <span style="font-size: 1.1rem; font-weight: 700; color: var(--ofertas-primary);" id="total-general-cuotas">Bs. ${totalGeneral.toFixed(2)}</span>
                </div>
            </div>
            
            <div class="mb-3 p-2 rounded" style="background: var(--ofertas-surface); border: 1px solid var(--ofertas-border);">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label style="font-size: 0.7rem; font-weight: 600; text-transform: uppercase; color: var(--ofertas-text-muted);">Cambiar día en todas las fechas</label>
                        <input type="number" class="form-control form-control-sm" id="cambiar-dia-fecha" value="${diaActual}" min="1" max="31"
                            style="border-radius: 4px; border: 1px solid var(--ofertas-border);">
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-sm btn-primary" id="btn-aplicar-dia" style="border-radius: 4px; padding: 6px 12px;">
                            <i class="ri-calendar-line me-1"></i>Aplicar día
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">`;

        Object.values(grouped).forEach(grupo => {
            const tieneMultiplesCuotas = grupo.cuotas.length > 1;
            
            html += `
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: var(--ofertas-surface); border-bottom: 2px solid var(--ofertas-primary);">
                        <div class="d-flex align-items-center gap-2">
                            <span style="font-weight: 600; color: var(--ofertas-text);">${grupo.concepto_nombre}</span>
                            ${tieneMultiplesCuotas ? `
                                <div class="d-flex align-items-center gap-1">
                                    <input type="number" class="form-control form-control-sm" 
                                        id="monto-cuota-${grupo.concepto_id}"
                                        placeholder="Monto cuota" 
                                        min="0" step="0.01"
                                        style="width: 100px; border-radius: 4px; border: 1px solid var(--ofertas-border); font-size: 0.75rem;"
                                        data-concepto-id="${grupo.concepto_id}"
                                        data-total-concepto="${grupo.total_concepto}"
                                        data-n-cuotas="${grupo.n_cuotas_total}">
                                    <button type="button" class="btn btn-xs btn-outline-success btn-aplicar-monto" 
                                        data-concepto-id="${grupo.concepto_id}" 
                                        title="Aplicar monto a todas las cuotas"
                                        style="padding: 2px 6px; font-size: 0.7rem;">
                                        <i class="ri-check-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-xs btn-outline-warning btn-invertir-montos" 
                                        data-concepto-id="${grupo.concepto_id}" 
                                        title="Invertir montos entre cuotas" 
                                        style="padding: 2px 6px; font-size: 0.7rem;">
                                        <i class="ri-swap-line"></i> Invertir
                                    </button>
                                </div>
                            ` : ''}
                        </div>
                        <span style="font-weight: 600; color: var(--ofertas-success);" class="total-concepto-display" data-concepto-id="${grupo.concepto_id}">Bs. ${grupo.total_concepto.toFixed(2)} <span style="font-size: 0.75rem; color: var(--ofertas-text-muted);">(${grupo.n_cuotas_total} cuota${grupo.n_cuotas_total > 1 ? 's' : ''})</span></span>
                    </div>
                    <table class="table table-sm mb-0" style="font-size: 0.8rem;">
                        <thead>
                            <tr style="background: var(--ofertas-surface);">
                                <th style="padding: 6px 10px; font-size: 0.68rem; font-weight: 600; text-transform: uppercase; color: var(--ofertas-text-muted);">N°</th>
                                <th style="padding: 6px 10px; font-size: 0.68rem; font-weight: 600; text-transform: uppercase; color: var(--ofertas-text-muted); text-align: end;">MONTO (Bs)</th>
                                <th style="padding: 6px 10px; font-size: 0.68rem; font-weight: 600; text-transform: uppercase; color: var(--ofertas-text-muted); text-align: center;">FECHA PAGO</th>
                            </tr>
                        </thead>
                        <tbody class="cuotas-grupo-body" data-concepto-id="${grupo.concepto_id}">`;

            grupo.cuotas.forEach(cuota => {
                html += `
                    <tr>
                        <td style="padding: 6px 10px; text-align: center;">
                            <span class="badge rounded-pill" style="background: var(--ofertas-primary); color: white; font-size: 0.7rem; font-weight: 600;">${cuota.n_cuota}</span>
                        </td>
                        <td style="padding: 6px 10px; text-align: end;">
                            <input type="number" class="form-control form-control-sm text-end fw-bold monto-cuota-input" 
                                value="${cuota.pago_total_bs}" 
                                data-concepto-id="${cuota.concepto_id}"
                                data-n-cuota="${cuota.n_cuota}"
                                data-original-monto="${cuota.pago_total_bs}"
                                readonly
                                style="border-radius: 4px; border: 1px solid var(--ofertas-border); font-size: 0.8rem; background: var(--ofertas-surface); padding: 4px 8px; cursor: not-allowed;">
                        </td>
                        <td style="padding: 6px 10px; text-align: center;">
                            <input type="date" class="form-control form-control-sm fecha-pago-input fecha-pago-grupo"
                                value="${cuota.fecha_pago}"
                                data-concepto-id="${cuota.concepto_id}"
                                data-n-cuota="${cuota.n_cuota}"
                                style="border-radius: 4px; border: 1px solid var(--ofertas-border); font-size: 0.8rem; padding: 4px 8px;">
                        </td>
                    </tr>`;
            });

            html += `</tbody></table></div>`;
        });

        html += `</div>`;
        
        // Agregardiv para mensajes de error
        html += `<div id="cuotas-error-message" class="mt-2" style="display:none;"></div>`;
        
        $('#cuotas-preview-container').html(html);
        
        // Eventos para cambio de día
        $('#btn-aplicar-dia').on('click', function() {
            const nuevoDia = parseInt($('#cambiar-dia-fecha').val());
            if (nuevoDia < 1 || nuevoDia > 31) {
                mostrarToast('warning', 'El día debe estar entre 1 y 31');
                return;
            }
            
            $('.fecha-pago-input').each(function() {
                const fechaActual = $(this).val();
                if (fechaActual) {
                    const partes = fechaActual.split('-');
                    const nuevaFecha = `${partes[0]}-${partes[1].padStart(2, '0')}-${nuevoDia.toString().padStart(2, '0')}`;
                    $(this).val(nuevaFecha);
                }
            });
            
            mostrarToast('success', 'Días actualizados en todas las fechas');
        });
        
// Evento para aplicar monto a todas las cuotas de un concepto
        $('.btn-aplicar-monto').on('click', function() {
            const conceptoId = $(this).data('concepto-id');
            const inputMonto = $(`#monto-cuota-${conceptoId}`);
            const montoIngresado = parseFloat(inputMonto.val());
            
            if (isNaN(montoIngresado) || montoIngresado <= 0) {
                mostrarToast('warning', 'Ingrese un monto válido mayor a 0');
                return;
            }
            
            const totalConcepto = parseFloat(inputMonto.data('total-concepto'));
            const nCuotas = parseInt(inputMonto.data('n-cuotas'));
            
            if (montoIngresado > totalConcepto) {
                mostrarToast('warning', `El monto no puede ser mayor al total del concepto (Bs. ${totalConcepto.toFixed(2)})`);
                return;
            }
            
            const grupoBody = $(`.cuotas-grupo-body[data-concepto-id="${conceptoId}"]`);
            const inputs = grupoBody.find('.monto-cuota-input');
            
            // Calcular: las primeras n-1 cuotas reciben el monto ingresado
            // La última cuota recibe el resto para completar el total del concepto
            const cuotasConMonto = nCuotas - 1;
            const montoUltimaCuota = totalConcepto - (montoIngresado * cuotasConMonto);
            
            if (montoUltimaCuota <= 0) {
                mostrarToast('warning', `El monto mínimo por cuota debe ser mayor a Bs. ${(totalConcepto / nCuotas).toFixed(2)} para que ninguna cuota sea 0`);
                return;
            }
            
            inputs.each(function(index) {
                let montoFinal;
                if (index < cuotasConMonto) {
                    montoFinal = montoIngresado;
                } else {
                    montoFinal = montoUltimaCuota;
                }
                $(this).val(montoFinal.toFixed(2));
            });
            
actualizarTotalesPorConcepto();
            validarMontosCero();
            mostrarToast('success', `Cuotas actualizadas: ${cuotasConMonto} cuota(s) de Bs. ${montoIngresado.toFixed(2)} + 1 cuota de Bs. ${montoUltimaCuota.toFixed(2)}`);
        });
        
        // Eventos para cambio de monto (ya no editable, solo lectura)
        $('.monto-cuota-input').on('change', function() {
            actualizarTotalesPorConcepto();
            validarMontosCero();
        });
        
        // Inicializar validación
        validarMontosCero();
        
        // Evento para invertir montos
        $('.btn-invertir-montos').on('click', function() {
            const conceptoId = $(this).data('concepto-id');
            const grupoBody = $(`.cuotas-grupo-body[data-concepto-id="${conceptoId}"]`);
            const inputs = grupoBody.find('.monto-cuota-input');
            
            const valores = [];
            inputs.each(function() {
                valores.push(parseFloat($(this).val()) || 0);
            });
            
            valores.reverse();
            
            inputs.each(function(index) {
                $(this).val(valores[index].toFixed(2));
            });
            
            actualizarTotalesPorConcepto();
            validarMontosCero();
            mostrarToast('success', 'Montos invertidos correctamente');
        });
    }

    // Función para actualizar totales por concepto después de cambiar montos
    function actualizarTotalesPorConcepto() {
        $('.cuotas-grupo-body').each(function() {
            const conceptoId = $(this).data('concepto-id');
            let total = 0;
            
            $(this).find('.monto-cuota-input').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            
            const display = $(`.total-concepto-display[data-concepto-id="${conceptoId}"]`);
            const numCuotas = $(this).find('.monto-cuota-input').length;
            display.html(`Bs. ${total.toFixed(2)} <span style="font-size: 0.75rem; color: var(--ofertas-text-muted);">(${numCuotas} cuota${numCuotas > 1 ? 's' : ''})</span>`);
        });
        
        // Actualizar total general
        let totalGeneral = 0;
        $('.monto-cuota-input').each(function() {
            totalGeneral += parseFloat($(this).val()) || 0;
        });
        $('#total-general-cuotas').text(`Bs. ${totalGeneral.toFixed(2)}`);
    }

    // Función para validar que no haya cuotas con monto 0
    function validarMontosCero() {
        let tieneCero = false;
        let primerConceptoCero = '';
        
        $('.monto-cuota-input').each(function() {
            const monto = parseFloat($(this).val()) || 0;
            if (monto === 0) {
                tieneCero = true;
                const nombreConcepto = $(this).closest('.cuotas-grupo-body').data('concepto-id');
                if (!primerConceptoCero) {
                    primerConceptoCero = $(this).closest('.mb-4').find('span').text().split('Bs.')[0].trim();
                }
            }
        });
        
        const errorDiv = $('#cuotas-error-message');
        if (tieneCero) {
            errorDiv.html(`
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background:#fef2f2;color:#dc2626;font-size:0.8rem;">
                    <i class="ri-error-warning-line"></i> No se puede asignar monto 0 a ninguna cuota
                </div>
            `).show();
            $('#confirmar-cuotas-btn').prop('disabled', true).addClass('opacity-50');
        } else {
            errorDiv.hide();
            $('#confirmar-cuotas-btn').prop('disabled', false).removeClass('opacity-50');
        }
    }

    // Generar vista previa de cuotas (botón manual)
    $('#generar-cuotas-btn').on('click', function() {
        generarVistaPreviaCuotas();
    });

    // Confirmar cuotas - maneja la nueva estructura agrupada
    $('#confirmar-cuotas-btn').on('click', function() {
        const ofertaId = $('#oferta_id_inscripcion').val();
        const estudianteId = $('#estudiante_id_inscripcion').val();
        const planId = $('#planes_pago_select').val();

        // Validar que no haya montos en 0
        let tieneCero = false;
        $('.monto-cuota-input').each(function() {
            if (parseFloat($(this).val()) === 0) {
                tieneCero = true;
            }
        });
        
        if (tieneCero) {
            mostrarToast('error', 'No puede haber cuotas con monto 0. Verifique los montos.');
            return;
        }

        const cuotasData = [];
        
        // Recorrer cada grupo de cuotas por concepto
        $('.cuotas-grupo-body').each(function() {
            const conceptoId = $(this).data('concepto-id');
            
            $(this).find('tr').each(function() {
                const nCuota = $(this).find('.monto-cuota-input').data('n-cuota');
                const fechaPago = $(this).find('.fecha-pago-input').val();
                const montoPorCuota = parseFloat($(this).find('.monto-cuota-input').val());

                cuotasData.push({
                    concepto_id: conceptoId,
                    n_cuota: nCuota,
                    fecha_pago: fechaPago,
                    monto_bs: montoPorCuota
                });
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
                    
                    // Abrir modal de subir comprobante después de confirmar
                    if (res.inscripcion_id) {
                        const ofertaId = $('#oferta_id_inscripcion').val();
                        setTimeout(() => {
                            $.ajax({
                                url: '/admin/ofertas/' + ofertaId + '/info-inscripcion',
                                method: 'GET',
                                success: function(info) {
                                    if (info.success) {
                                        abrirModalSubirComprobanteOfertas(res.inscripcion_id, info.estudiante_nombre, info.programa_nombre);
                                    }
                                }
                            });
                        }, 300);
                    }
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
                console.error('Error al registrar persona:', xhr);
                console.error('Response:', xhr.responseText);
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
                        if (xhr.responseJSON.errors.celular) $('#celular_nuevo_inscripcion').addClass('is-invalid');
                        if (xhr.responseJSON.errors.ciudade_id) $('#ciudad_nuevo_inscripcion').addClass('is-invalid');
                        if (xhr.responseJSON.errors.sexo) $('select[name="sexo"]').addClass('is-invalid');
                        if (xhr.responseJSON.errors.estado_civil) $('select[name="estado_civil"]').addClass('is-invalid');
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

    // Función para abrir modal de subir comprobante en ofertas
    function abrirModalSubirComprobanteOfertas(inscripcionId, estudianteNombre, programaNombre) {
        // Crear el modal si no existe
        if (!$('#modalSubirComprobanteOfertas').length) {
            const modalHtml = `
<div class="modal fade" id="modalSubirComprobanteOfertas" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #0f766e 0%, #0d5f59 100%); color: white;">
                <h5 class="modal-title"><i class="ri-upload-cloud-line me-2"></i>Subir Comprobante de Pago</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formSubirComprobanteOfertas" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Estudiante</label>
                        <p class="form-control-plaintext fw-medium" id="comp_estudiante"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Programa</label>
                        <p class="form-control-plaintext" id="comp_programa"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cuotas a las que aplica el pago *</label>
                        <div id="comp_cuotas_checkbox"></div>
                        <input type="hidden" name="comp_cuota_ids" id="comp_cuota_ids">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comprobante *</label>
                        <input type="file" class="form-control" name="archivo" id="comp_archivo" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" id="comp_observaciones" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" style="background: #0f766e; border-color: #0f766e;">Subir Comprobante</button>
                </div>
            </form>
        </div>
    </div>
</div>`;
            $('body').append(modalHtml);
        }

        // Set values
        $('#comp_estudiante').text(estudianteNombre);
        $('#comp_programa').text(programaNombre);
        $('#comp_cuota_ids').val('');
        
        $('#comp_cuotas_checkbox').html('<div class="text-muted">Cargando...</div>');

        // Load cuotas
        $.ajax({
            url: `/admin/profile/marketing/inscripcion/${inscripcionId}/cuotas`,
            method: 'GET',
            success: function(response) {
                if (response.success && response.cuotas.length > 0) {
                    let html = '';
                    response.cuotas.forEach(cuota => {
                        html += `
<div class="form-check">
    <input class="form-check-input comp-cuota-checkbox" type="checkbox" 
        value="${cuota.id}" id="comp_cuota_${cuota.id}">
    <label class="form-check-label" for="comp_cuota_${cuota.id}">
        ${cuota.nombre} - <span class="text-success fw-semibold">Bs. ${parseFloat(cuota.pendiente_bs).toFixed(2)}</span>
    </label>
</div>`;
                    });
                    $('#comp_cuotas_checkbox').html(html);
                    
                    // Event to update hidden input
                    $('.comp-cuota-checkbox').on('change', function() {
                        const selected = [];
                        $('.comp-cuota-checkbox:checked').each(function() {
                            selected.push($(this).val());
                        });
                        $('#comp_cuota_ids').val(selected.join(','));
                    });
                } else {
                    $('#comp_cuotas_checkbox').html('<div class="text-muted">No hay cuotas pendientes</div>');
                }
            }
        });

        $('#comp_archivo').val('');
        $('#comp_observaciones').val('');

        $('#modalSubirComprobanteOfertas').modal('show');

        // Handle form submit
        $('#formSubirComprobanteOfertas').off('submit');
        $('#formSubirComprobanteOfertas').on('submit', function(e) {
            e.preventDefault();
            
            const cuotaIds = [];
            $('.comp-cuota-checkbox:checked').each(function() {
                cuotaIds.push($(this).val());
            });
            
            if (cuotaIds.length === 0) {
                mostrarToast('error', 'Debe seleccionar al menos una cuota');
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('inscripcione_id', inscripcionId);
            formData.append('observaciones', $('#comp_observaciones').val() || '');
            formData.append('archivo', $('#comp_archivo')[0].files[0]);
            cuotaIds.forEach(function(id) {
                formData.append('cuota_ids[]', id);
            });

            $.ajax({
                url: '{{ route("admin.profile.marketing.subir-respaldo") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        mostrarToast('success', response.message);
                        $('#modalSubirComprobanteOfertas').modal('hide');
                    } else {
                        mostrarToast('error', response.message);
                    }
                },
                error: function(xhr) {
                    mostrarToast('error', 'Error al subir el comprobante');
                }
            });
        });
    }
</script>
