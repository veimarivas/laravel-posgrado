<script>
    $(document).ready(function() {
        let saldoPendiente = 0;
        let montoMaximo = 0;

        // Abrir modal para pagar cuota
        $(document).on('click', '.btn-pagar-cuota', function() {
            console.log('Botón pagar clickeado');

            const cuotaId = $(this).data('cuota-id');
            const estudianteId = $(this).data('estudiante-id');
            console.log('Cuota ID:', cuotaId, 'Estudiante ID:', estudianteId);

            // Resetear formulario
            $('#formPagarCuota')[0].reset();
            $('#cuota_id').val(cuotaId);
            $('#estudiante_id').val(estudianteId);

            // Obtener datos de la cuota
            $.ajax({
                url: `/admin/estudiantes/${estudianteId}/cuota/${cuotaId}`,
                type: 'GET',
                beforeSend: function() {
                    // Mostrar modal con carga
                    $('#modalPagarCuota').modal('show');
                    $('#modalPagarCuota .modal-body').html(`
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2">Cargando información de la cuota...</p>
                        </div>
                    `);
                },
                // En la función success del AJAX, convierte los valores a números:
                success: function(response) {
                    if (response.success) {
                        const cuota = response.cuota;

                        // CONVIERTE TODOS LOS VALORES A NÚMEROS
                        const pagoTotal = parseFloat(cuota.pago_total_bs) || 0;
                        const pagoPendiente = parseFloat(cuota.pago_pendiente_bs) || 0;
                        const saldoPagado = parseFloat(cuota.saldo_pagado) || 0;

                        saldoPendiente = pagoPendiente;
                        montoMaximo = saldoPendiente;

                        // Actualizar información de la cuota (usando números)
                        $('#info-cuota-nombre').text(cuota.nombre + ' (Cuota ' + cuota
                            .n_cuota + ')');
                        $('#info-cuota-programa').text(cuota.programa);
                        $('#info-cuota-total').text(pagoTotal.toFixed(2));
                        $('#info-cuota-pendiente').text(pagoPendiente.toFixed(2));
                        $('#info-cuota-pagado').text(saldoPagado.toFixed(2));
                        $('#maximo_pago').text(pagoPendiente.toFixed(2));

                        // Configurar monto máximo
                        $('#monto_pago').attr('max', pagoPendiente);
                        $('#monto_pago').attr('min', 0.01);

                        // Restaurar formulario
                        $('#modalPagarCuota .modal-body').html(`
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Cuota:</strong> <span id="info-cuota-nombre">${cuota.nombre} (Cuota ${cuota.n_cuota})</span></p>
                                <p class="mb-1"><strong>Programa:</strong> <span id="info-cuota-programa">${cuota.programa}</span></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Total Cuota:</strong> <span id="info-cuota-total" class="text-primary">${pagoTotal.toFixed(2)}</span> Bs</p>
                                <p class="mb-1"><strong>Saldo Pendiente:</strong> <span id="info-cuota-pendiente" class="text-danger">${pagoPendiente.toFixed(2)}</span> Bs</p>
                                <p class="mb-0"><strong>Saldo Pagado:</strong> <span id="info-cuota-pagado" class="text-success">${saldoPagado.toFixed(2)}</span> Bs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="monto_pago" class="form-label">Monto a Pagar (Bs) *</label>
                        <input type="number" step="0.01" class="form-control" id="monto_pago" name="monto_pago" required min="0.01" max="${pagoPendiente}">
                        <div class="form-text">Máximo: <span id="maximo_pago" class="text-danger fw-bold">${pagoPendiente.toFixed(2)}</span> Bs</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="descuento" class="form-label">Descuento (Bs)</label>
                        <input type="number" step="0.01" class="form-control" id="descuento" name="descuento" value="0" min="0">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tipo_pago" class="form-label">Tipo de Pago *</label>
                        <select class="form-select" id="tipo_pago" name="tipo_pago" required>
                            <option value="">Seleccionar...</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Transferencia">Transferencia</option>
                            <option value="Depósito">Depósito</option>
                            <option value="Tarjeta">Tarjeta</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="fecha_pago" class="form-label">Fecha de Pago *</label>
                        <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" value="${new Date().toISOString().split('T')[0]}" required>
                    </div>
                </div>

                <div class="col-12">
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
                    </div>
                </div>

                <!-- Resumen del pago en tiempo real -->
                <div class="col-12">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">Resumen del Pago</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <p class="mb-1 text-muted">Monto a Pagar</p>
                                    <h5 class="text-primary" id="resumen-monto">0.00 Bs</h5>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1 text-muted">Descuento</p>
                                    <h5 class="text-warning" id="resumen-descuento">0.00 Bs</h5>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1 text-muted">Total a Pagar</p>
                                    <h5 class="text-success" id="resumen-total">0.00 Bs</h5>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" id="progreso-pago" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <small class="text-muted mt-1 d-block text-center">
                                        <span id="texto-progreso">0% del saldo pendiente</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);

                        // Inicializar eventos para el nuevo contenido
                        $('#monto_pago, #descuento').on('input', function() {
                            actualizarResumenPago();
                        });

                        // Actualizar resumen inicial
                        actualizarResumenPago();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.msg
                        });
                        $('#modalPagarCuota').modal('hide');
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cargar la información de la cuota.'
                    });
                    $('#modalPagarCuota').modal('hide');
                }
            });
        });


        // Ver recibos de una cuota
        $(document).on('click', '.btn-ver-recibos', function() {
            const cuotaId = $(this).data('cuota-id');
            const cuotaNombre = $(this).data('cuota-nombre');

            $('#modalRecibosTitle').text('Recibos de: ' + cuotaNombre);
            $('#modalRecibosCuota').modal('show');

            $.ajax({
                url: `/admin/estudiantes/cuota/${cuotaId}/recibos`,
                type: 'GET',
                beforeSend: function() {
                    $('#contenidoRecibos').html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando recibos...</p>
                    </div>
                `);
                },
                success: function(response) {
                    if (response.success) {
                        $('#contenidoRecibos').html(response.html);
                    } else {
                        $('#contenidoRecibos').html(`
                        <div class="alert alert-danger">
                            ${response.msg}
                        </div>
                    `);
                    }
                },
                error: function() {
                    $('#contenidoRecibos').html(`
                    <div class="alert alert-danger">
                        Error al cargar los recibos.
                    </div>
                `);
                }
            });
        });

        // Ver detalle de pago en el historial
        $(document).on('click', '.btn-ver-detalle-pago', function() {
            const pagoId = $(this).data('pago-id');

            $('#modalDetallePago').modal('show');

            $.ajax({
                url: `/admin/estudiantes/pago/${pagoId}/detalle`,
                type: 'GET',
                beforeSend: function() {
                    $('#contenidoDetallePago').html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando detalles del pago...</p>
                    </div>
                `);
                },
                success: function(response) {
                    if (response.success) {
                        const pago = response.pago;
                        const cuotas = response.cuotas;
                        const estudiante = response.estudiante;

                        let html = `
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Información del Recibo</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Recibo:</strong> <span class="badge bg-primary">${pago.recibo}</span></p>
                                        <p><strong>Fecha:</strong> ${new Date(pago.fecha_pago).toLocaleDateString('es-ES', { 
                                            day: '2-digit', 
                                            month: '2-digit', 
                                            year: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        })}</p>
                                        <p><strong>Tipo de Pago:</strong> <span class="badge bg-secondary">${pago.tipo_pago}</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Resumen Financiero</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Monto Total:</strong> <span class="text-success fw-bold">${parseFloat(pago.pago_bs).toFixed(2)} Bs</span></p>
                                        <p><strong>Descuento:</strong> <span class="text-warning">-${parseFloat(pago.descuento_bs).toFixed(2)} Bs</span></p>
                                        <p><strong>Monto Neto:</strong> <span class="text-primary fw-bold">${(parseFloat(pago.pago_bs) - parseFloat(pago.descuento_bs)).toFixed(2)} Bs</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Cuotas Pagadas</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Cuota</th>
                                                <th>Nombre</th>
                                                <th class="text-end">Monto Pagado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                    `;

                        cuotas.forEach(cuota => {
                            html += `
                            <tr>
                                <td>${cuota.cuota.n_cuota}</td>
                                <td>${cuota.cuota.nombre}</td>
                                <td class="text-end text-success">${parseFloat(cuota.pago_bs).toFixed(2)} Bs</td>
                            </tr>
                        `;
                        });

                        html += `
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-light">
                                                <td colspan="2" class="text-end fw-bold">Total:</td>
                                                <td class="text-end fw-bold text-success">${parseFloat(pago.pago_bs).toFixed(2)} Bs</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        ${estudiante ? `
                        <div class="mt-3">
                            <div class="alert alert-info">
                                <i class="ri-user-line me-1"></i>
                                <strong>Estudiante:</strong> ${estudiante.persona.nombres} ${estudiante.persona.apellido_paterno}
                            </div>
                        </div>
                        ` : ''}
                    `;

                        $('#contenidoDetallePago').html(html);
                    } else {
                        $('#contenidoDetallePago').html(`
                        <div class="alert alert-danger">
                            ${response.msg}
                        </div>
                    `);
                    }
                },
                error: function() {
                    $('#contenidoDetallePago').html(`
                    <div class="alert alert-danger">
                        Error al cargar los detalles del pago.
                    </div>
                `);
                }
            });
        });

        // Imprimir detalle de pago
        $('#btnImprimirDetalle').on('click', function() {
            const contenido = $('#contenidoDetallePago').html();
            const ventana = window.open('', '_blank');
            ventana.document.write(`
            <html>
                <head>
                    <title>Detalle de Pago</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .text-success { color: #28a745; }
                        .text-warning { color: #ffc107; }
                        .text-primary { color: #007bff; }
                        .fw-bold { font-weight: bold; }
                        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f8f9fa; }
                    </style>
                </head>
                <body>
                    ${contenido}
                    <script>
                        window.onload = function() { window.print(); }
                    <\/script>
                </body>
            </html>
        `);
            ventana.document.close();
        });

        // Calcular en tiempo real
        $(document).on('input', '#monto_pago, #descuento', function() {
            actualizarResumenPago();
        });

        function actualizarResumenPago() {
            const monto = parseFloat($('#monto_pago').val()) || 0;
            const descuento = parseFloat($('#descuento').val()) || 0;

            // Validar que el monto no exceda el saldo pendiente
            if (monto > saldoPendiente) {
                $('#monto_pago').val(saldoPendiente);
                actualizarResumenPago();
                return;
            }

            // Calcular total
            const total = monto - descuento;
            const totalFinal = total > 0 ? total : 0;

            // Actualizar resumen
            $('#resumen-monto').text(monto.toFixed(2) + ' Bs');
            $('#resumen-descuento').text(descuento.toFixed(2) + ' Bs');
            $('#resumen-total').text(totalFinal.toFixed(2) + ' Bs');

            // Actualizar barra de progreso
            const porcentaje = saldoPendiente > 0 ? (monto / saldoPendiente) * 100 : 0;
            $('#progreso-pago').css('width', porcentaje + '%');
            $('#texto-progreso').text(porcentaje.toFixed(1) + '% del saldo pendiente');
        }

        // Enviar formulario de pago
        $(document).on('submit', '#formPagarCuota', function(e) {
            e.preventDefault();

            const formData = $(this).serialize();
            const estudianteId = $('#estudiante_id').val();

            $.ajax({
                url: `/admin/estudiantes/${estudianteId}/pagar-cuota`,
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#modalPagarCuota .btn-primary').html(
                        '<span class="spinner-border spinner-border-sm me-1"></span> Procesando...'
                    ).prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        $('#modalPagarCuota').modal('hide');

                        // Mostrar modal con recibo generado
                        $('#recibo-numero').text(response.recibo);
                        $('#recibo-monto').text(parseFloat($('#monto_pago').val()).toFixed(
                            2));
                        $('#recibo-fecha').text($('#fecha_pago').val());
                        $('#descargar-recibo').attr('href', response.pdf_url);

                        $('#modalReciboGenerado').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.msg
                        });
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error al registrar el pago';
                    if (xhr.responseJSON && xhr.responseJSON.msg) {
                        errorMsg = xhr.responseJSON.msg;
                    } else if (xhr.status === 422) {
                        errorMsg =
                            'Error de validación. Por favor, verifica los datos ingresados.';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMsg
                    });
                },
                complete: function() {
                    $('#modalPagarCuota .btn-primary').html('Registrar Pago').prop(
                        'disabled', false);
                }
            });
        });

        // Descargar recibo
        $(document).on('click', '#descargar-recibo', function() {
            $('#modalReciboGenerado').modal('hide');
        });

        // Cerrar modal de recibo y recargar página
        $('#modalReciboGenerado').on('hidden.bs.modal', function() {
            setTimeout(() => {
                location.reload();
            }, 500);
        });


    });
</script>
