@extends('admin.dashboard')

@section('admin')
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">Historial de Recibos</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Recibos</li>
                        </ol>
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.recibos.exportar') . '?' . http_build_query(request()->query()) }}"
                        class="btn btn-success btn-sm">
                        <i class="ri-file-excel-line me-1"></i> Exportar Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card border">
        <div class="card-body">
            <form id="formFiltrosRecibos">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                            value="{{ request('fecha_inicio') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                            value="{{ request('fecha_fin') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="tipo_pago" class="form-label">Tipo de Pago</label>
                        <select class="form-select" id="tipo_pago" name="tipo_pago">
                            <option value="Todos">Todos</option>
                            <option value="Efectivo" {{ request('tipo_pago') == 'Efectivo' ? 'selected' : '' }}>Efectivo
                            </option>
                            <option value="Transferencia" {{ request('tipo_pago') == 'Transferencia' ? 'selected' : '' }}>
                                Transferencia</option>
                            <option value="Depósito" {{ request('tipo_pago') == 'Depósito' ? 'selected' : '' }}>Depósito
                            </option>
                            <option value="Tarjeta" {{ request('tipo_pago') == 'Tarjeta' ? 'selected' : '' }}>Tarjeta
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="recibo" class="form-label">N° Recibo</label>
                        <input type="text" class="form-control" id="recibo" name="recibo"
                            value="{{ request('recibo') }}" placeholder="UNIP-000000001">
                    </div>
                    <div class="col-md-2">
                        <label for="carnet" class="form-label">Carnet</label>
                        <input type="text" class="form-control" id="carnet" name="carnet"
                            value="{{ request('carnet') }}" placeholder="1234567">
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-search-line me-1"></i> Buscar
                        </button>
                        <button type="button" id="btnLimpiarFiltros" class="btn btn-light">
                            <i class="ri-refresh-line me-1"></i> Limpiar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mt-3" id="estadisticasContainer">
        @include('admin.recibos.partials.estadisticas', ['estadisticas' => $estadisticas])
    </div>

    <!-- Tabla de recibos -->
    <div class="card border mt-3">
        <div class="card-body">
            <div class="table-responsive" id="tablaRecibosContainer">
                @include('admin.recibos.partials.table-body', ['recibos' => $recibos])
            </div>
        </div>
    </div>

    <!-- Modal para ver detalle -->
    @include('admin.recibos.partials.modal-detalle')
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Función para cargar recibos con filtros
            function cargarRecibos() {
                var formData = $('#formFiltrosRecibos').serialize();

                $.ajax({
                    url: "{{ route('admin.recibos.filtrados') }}",
                    type: "GET",
                    data: formData,
                    success: function(response) {
                        $('#tablaRecibosContainer').html(response.html);
                        // Actualizar estadísticas si vienen en la respuesta
                        if (response.estadisticas) {
                            // Actualizar estadísticas dinámicamente
                            actualizarEstadisticas(response.estadisticas);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        Swal.fire('Error', 'No se pudieron cargar los recibos', 'error');
                    }
                });
            }

            // Función para actualizar estadísticas
            function actualizarEstadisticas(estadisticas) {
                $('#estadisticasContainer').html(`
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">Total Recibos</p>
                                    <h4 class="mt-4 ff-secondary fw-semibold">${estadisticas.total_recibos}</h4>
                                </div>
                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-2">
                                            <i class="ri-file-text-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">Monto Total</p>
                                    <h4 class="mt-4 ff-secondary fw-semibold">${formatMoney(estadisticas.total_monto)} Bs</h4>
                                </div>
                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-success-subtle text-success rounded-circle fs-2">
                                            <i class="ri-money-dollar-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">Efectivo</p>
                                    <h4 class="mt-4 ff-secondary fw-semibold">${formatMoney(estadisticas.total_efectivo)} Bs</h4>
                                </div>
                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-2">
                                            <i class="ri-bank-card-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">Transferencias</p>
                                    <h4 class="mt-4 ff-secondary fw-semibold">${formatMoney(estadisticas.total_transferencia)} Bs</h4>
                                </div>
                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-info-subtle text-info rounded-circle fs-2">
                                            <i class="ri-exchange-funds-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            }

            // Formatear dinero
            function formatMoney(amount) {
                return parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }

            // Enviar formulario de filtros
            $('#formFiltrosRecibos').on('submit', function(e) {
                e.preventDefault();
                cargarRecibos();
            });

            // Limpiar filtros
            $('#btnLimpiarFiltros').on('click', function() {
                $('#formFiltrosRecibos')[0].reset();
                cargarRecibos();
            });

            // Paginación
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: "GET",
                    data: $('#formFiltrosRecibos').serialize(),
                    success: function(response) {
                        $('#tablaRecibosContainer').html(response.html);
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                    },
                    error: function(xhr) {
                        console.error(xhr);
                    }
                });
            });

            // Ver detalle de pago
            $(document).on('click', '.btn-ver-detalle', function() {
                var pagoId = $(this).data('pago-id');
                console.log('Intentando cargar detalle del pago ID:', pagoId);

                // Mostrar loading en el modal
                $('#detallePagoContenido').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                    <p class="mt-3">Cargando detalles del pago...</p>
                </div>
            `);

                // Mostrar modal
                $('#modalDetallePago').modal('show');

                // Cargar detalles del pago
                $.ajax({
                    url: "/admin/estudiantes/pago/" + pagoId + "/detalle",
                    type: "GET",
                    dataType: 'json',
                    success: function(response) {
                        console.log('Respuesta del servidor:', response);

                        if (response.success) {
                            // Construir HTML con los datos del pago
                            var html = construirHTMLDetalle(response);
                            $('#detallePagoContenido').html(html);
                        } else {
                            $('#detallePagoContenido').html(`
                            <div class="alert alert-danger">
                                <i class="ri-error-warning-line me-2"></i>
                                ${response.msg || 'Error al cargar los detalles del pago'}
                            </div>
                        `);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error en AJAX:', xhr.status, error);
                        console.error('Respuesta completa:', xhr.responseText);

                        var errorMsg = 'Error al conectar con el servidor';

                        if (xhr.status === 404) {
                            errorMsg = 'La ruta no fue encontrada (404)';
                        } else if (xhr.status === 500) {
                            errorMsg = 'Error interno del servidor (500)';
                        } else if (xhr.responseText) {
                            try {
                                var jsonResponse = JSON.parse(xhr.responseText);
                                errorMsg = jsonResponse.msg || errorMsg;
                            } catch (e) {
                                errorMsg = xhr.responseText.substring(0, 100) + '...';
                            }
                        }

                        $('#detallePagoContenido').html(`
                        <div class="alert alert-danger">
                            <i class="ri-error-warning-line me-2"></i>
                            ${errorMsg}<br>
                            <small>Status: ${xhr.status}</small>
                        </div>
                    `);
                    }
                });
            });

            // Función para construir el HTML del detalle
            function construirHTMLDetalle(response) {
                var pago = response.pago;
                var estudiante = response.estudiante;
                var cuotas = response.cuotas;

                var html = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">Información del Recibo</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="fw-medium">N° Recibo:</td>
                                <td class="text-end"><span class="badge bg-primary">${pago.recibo || 'N/A'}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Fecha de Pago:</td>
                                <td class="text-end">${formatDate(pago.fecha_pago)}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Tipo de Pago:</td>
                                <td class="text-end"><span class="badge bg-info">${pago.tipo_pago || 'N/A'}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Monto Total:</td>
                                <td class="text-end"><span class="fw-bold text-success">${formatMoney(pago.pago_bs)} Bs</span></td>
                            </tr>
            `;

                if (pago.descuento_bs > 0) {
                    html += `
                    <tr>
                        <td class="fw-medium">Descuento:</td>
                        <td class="text-end"><span class="text-warning">-${formatMoney(pago.descuento_bs)} Bs</span></td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Neto Pagado:</td>
                        <td class="text-end"><span class="fw-bold text-primary">${formatMoney(pago.pago_bs - pago.descuento_bs)} Bs</span></td>
                    </tr>
                `;
                }

                html += `
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">Información del Participante</h6>
                        <table class="table table-sm table-borderless">
            `;

                if (estudiante && estudiante.persona) {
                    html += `
                    <tr>
                        <td class="fw-medium">Estudiante:</td>
                        <td class="text-end">${estudiante.persona.nombres} ${estudiante.persona.apellido_paterno}</td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Carnet:</td>
                        <td class="text-end">${estudiante.persona.carnet}</td>
                    </tr>
                `;
                } else {
                    html += `
                    <tr>
                        <td colspan="2" class="text-center text-muted">Información del estudiante no disponible</td>
                    </tr>
                `;
                }

                html += `
                        </table>
                    </div>
                </div>
            `;

                // Agregar información de cuotas si existen
                if (cuotas && cuotas.length > 0) {
                    html += `
                    <hr>
                    <h6 class="mb-3">Cuotas Pagadas</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Cuota</th>
                                    <th>Programa</th>
                                    <th class="text-end">Monto Pagado</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                    cuotas.forEach(function(cuotaPago) {
                        var cuota = cuotaPago.cuota;
                        var programa = cuota && cuota.inscripcion && cuota.inscripcion.ofertaAcademica ?
                            cuota.inscripcion.ofertaAcademica.programa : null;

                        html += `
                        <tr>
                            <td>${cuota ? cuota.nombre + ' (' + cuota.n_cuota + ')' : 'N/A'}</td>
                            <td>${programa ? programa.nombre : 'N/A'}</td>
                            <td class="text-end">${formatMoney(cuotaPago.pago_bs)} Bs</td>
                        </tr>
                    `;
                    });

                    html += `
                            </tbody>
                        </table>
                    </div>
                `;
                }

                // Agregar detalles de pago (métodos de pago)
                if (pago.detalles && pago.detalles.length > 0) {
                    html += `
                    <hr>
                    <h6 class="mb-3">Detalles de Pago</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tipo de Pago</th>
                                    <th class="text-end">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                    pago.detalles.forEach(function(detalle) {
                        html += `
                        <tr>
                            <td>${detalle.tipo_pago || 'N/A'}</td>
                            <td class="text-end">${formatMoney(detalle.pago_bs)} Bs</td>
                        </tr>
                    `;
                    });

                    html += `
                            </tbody>
                        </table>
                    </div>
                `;
                }

                return html;
            }

            // Función para formatear dinero
            function formatMoney(amount) {
                if (!amount) return '0.00';
                return parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }

            // Función para formatear fecha
            function formatDate(dateString) {
                if (!dateString) return 'N/A';
                try {
                    var date = new Date(dateString);
                    if (isNaN(date.getTime())) return 'Fecha inválida';
                    return date.toLocaleDateString('es-BO') + ' ' + date.toLocaleTimeString('es-BO');
                } catch (e) {
                    return 'Fecha inválida';
                }
            }

            // Función para formatear fecha
            function formatDate(dateString) {
                if (!dateString) return 'N/A';
                var date = new Date(dateString);
                return date.toLocaleDateString('es-BO') + ' ' + date.toLocaleTimeString('es-BO');
            }

            // Imprimir detalle
            $('#btnImprimirDetalle').on('click', function() {
                var contenido = $('#detallePagoContenido').html();
                var ventana = window.open('', '_blank');
                ventana.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Detalle del Recibo</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 20px; }
                        .header h4 { color: #2c3e50; }
                        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f8f9fa; }
                        .total { font-weight: bold; color: #2c3e50; }
                        .text-end { text-align: right; }
                        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
                        .bg-primary { background-color: #3b7ddd; color: white; }
                        .bg-info { background-color: #39afd1; color: white; }
                        .text-success { color: #00a854; }
                        .text-warning { color: #f0ad4e; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h4>Detalle del Recibo</h4>
                        <p>Generado el: ${new Date().toLocaleDateString('es-BO')} ${new Date().toLocaleTimeString('es-BO')}</p>
                    </div>
                    ${contenido}
                    <script>
                        window.onload = function() {
                            window.print();
                        }
                    <\/script>
                </body>
                </html>
            `);
                ventana.document.close();
            });
        });
    </script>
@endpush
