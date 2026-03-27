@extends('admin.dashboard')

@section('admin')

<style>
    .stat-recibo {
        border-radius: 10px;
        border: 1px solid #e9ebec;
        background: #fff;
        padding: 1rem 1.2rem;
        display: flex;
        align-items: center;
        gap: .9rem;
    }
    .stat-recibo .stat-icon {
        width: 46px; height: 46px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; flex-shrink: 0;
    }
    .stat-recibo .stat-label { font-size: .75rem; color: #6c757d; margin-bottom: .15rem; }
    .stat-recibo .stat-value { font-size: 1.1rem; font-weight: 700; line-height: 1.2; }
    .filtros-card { border-radius: 10px; }
    .tabla-recibos-card { border-radius: 10px; }
    .pagination-wrapper .pagination { margin-bottom: 0; }
    .pagination-wrapper .page-link { padding: .25rem .55rem; font-size: .8rem; }
    .pagination-wrapper .page-item.active .page-link { background-color: #405189; border-color: #405189; }
</style>

    <!-- Page Title -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-1">Historial de Recibos</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Recibos</li>
            </ol>
        </div>
        <a href="{{ route('admin.recibos.exportar') . '?' . http_build_query(request()->query()) }}"
           class="btn btn-success btn-sm mt-2 mt-sm-0">
            <i class="ri-file-excel-line me-1"></i> Exportar Excel
        </a>
    </div>

    <!-- Estadísticas -->
    <div class="row g-2 mb-3" id="estadisticasContainer">
        @include('admin.recibos.partials.estadisticas', ['estadisticas' => $estadisticas])
    </div>

    <!-- Filtros -->
    <div class="card border filtros-card mb-3">
        <div class="card-header py-2 bg-transparent d-flex align-items-center gap-2">
            <i class="ri-filter-3-line text-primary"></i>
            <span class="fw-semibold">Filtros de búsqueda</span>
        </div>
        <div class="card-body py-2">
            <form id="formFiltrosRecibos">
                <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label form-label-sm mb-1">Fecha inicio</label>
                        <input type="date" class="form-control form-control-sm" id="fecha_inicio" name="fecha_inicio"
                            value="{{ request('fecha_inicio') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label form-label-sm mb-1">Fecha fin</label>
                        <input type="date" class="form-control form-control-sm" id="fecha_fin" name="fecha_fin"
                            value="{{ request('fecha_fin') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label form-label-sm mb-1">Tipo de pago</label>
                        <select class="form-select form-select-sm" id="tipo_pago" name="tipo_pago">
                            <option value="Todos">Todos</option>
                            <option value="Efectivo"      {{ request('tipo_pago') == 'Efectivo'      ? 'selected' : '' }}>Efectivo</option>
                            <option value="Transferencia" {{ request('tipo_pago') == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                            <option value="Depósito"      {{ request('tipo_pago') == 'Depósito'      ? 'selected' : '' }}>Depósito</option>
                            <option value="Tarjeta"       {{ request('tipo_pago') == 'Tarjeta'       ? 'selected' : '' }}>Tarjeta</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label form-label-sm mb-1">N° Recibo</label>
                        <input type="text" class="form-control form-control-sm" id="recibo" name="recibo"
                            value="{{ request('recibo') }}" placeholder="UNIP-000000001">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label form-label-sm mb-1">Carnet</label>
                        <input type="text" class="form-control form-control-sm" id="carnet" name="carnet"
                            value="{{ request('carnet') }}" placeholder="1234567">
                    </div>
                    <div class="col-md-1 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm w-100" title="Buscar">
                            <i class="ri-search-line"></i>
                        </button>
                        <button type="button" id="btnLimpiarFiltros" class="btn btn-light btn-sm w-100" title="Limpiar">
                            <i class="ri-refresh-line"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de recibos -->
    <div class="card border tabla-recibos-card">
        <div class="card-header py-2 bg-transparent d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="ri-receipt-line text-primary"></i>
                <span class="fw-semibold">Listado de Recibos</span>
                <span class="badge bg-primary-subtle text-primary ms-1">más recientes primero</span>
            </div>
        </div>
        <div class="card-body p-0" id="tablaRecibosContainer">
            @include('admin.recibos.partials.table-body', ['recibos' => $recibos])
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
                var perPage  = $('#perPageSelect').val();
                if (perPage) formData += '&per_page=' + perPage;

                $.ajax({
                    url: "{{ route('admin.recibos.filtrados') }}",
                    type: 'GET',
                    data: formData,
                    success: function(response) {
                        $('#tablaRecibosContainer').html(response.html);
                        if (response.estadisticas) actualizarEstadisticas(response.estadisticas);
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        Swal.fire('Error', 'No se pudieron cargar los recibos', 'error');
                    }
                });
            }

            // Función para actualizar estadísticas
            function actualizarEstadisticas(estadisticas) {
                var stats = [
                    { label: 'Total Recibos',   value: estadisticas.total_recibos,       unit: '',   icon: 'ri-file-text-line',        cls: 'bg-primary-subtle text-primary',  valCls: 'text-primary' },
                    { label: 'Monto Total',      value: formatMoney(estadisticas.total_monto),       unit: 'Bs', icon: 'ri-money-dollar-circle-line', cls: 'bg-dark-subtle text-dark',        valCls: '' },
                    { label: 'Efectivo',         value: formatMoney(estadisticas.total_efectivo),     unit: 'Bs', icon: 'ri-money-dollar-circle-line', cls: 'bg-success-subtle text-success',  valCls: 'text-success' },
                    { label: 'Transferencia',    value: formatMoney(estadisticas.total_transferencia),unit: 'Bs', icon: 'ri-bank-transfer-line',       cls: 'bg-info-subtle text-info',        valCls: 'text-info' },
                    { label: 'Depósito',         value: formatMoney(estadisticas.total_deposito||0),  unit: 'Bs', icon: 'ri-bank-card-2-line',         cls: 'bg-primary-subtle text-primary',  valCls: 'text-primary' },
                    { label: 'Tarjeta',          value: formatMoney(estadisticas.total_tarjeta||0),   unit: 'Bs', icon: 'ri-bank-card-line',           cls: 'bg-warning-subtle text-warning',  valCls: '' },
                ];
                var html = '';
                stats.forEach(function(s) {
                    html += `<div class="col-xl-2 col-md-4 col-6">
                        <div class="stat-recibo">
                            <div class="stat-icon ${s.cls}"><i class="${s.icon}"></i></div>
                            <div>
                                <div class="stat-label">${s.label}</div>
                                <div class="stat-value ${s.valCls}">${s.value} <small class="fw-normal">${s.unit}</small></div>
                            </div>
                        </div>
                    </div>`;
                });
                $('#estadisticasContainer').html(html);
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

            // Cambio de registros por página
            $(document).on('change', '#perPageSelect', function() {
                cargarRecibos();
            });

            // Paginación AJAX (delegado para los links generados dinámicamente)
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                if (!url || url === '#') return;

                var extraData = $('#formFiltrosRecibos').serialize();
                var perPage = $('#perPageSelect').val();
                if (perPage) extraData += '&per_page=' + perPage;

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: extraData,
                    success: function(response) {
                        $('#tablaRecibosContainer').html(response.html);
                        if (response.estadisticas) actualizarEstadisticas(response.estadisticas);
                        $('html, body').animate({ scrollTop: $('#tablaRecibosContainer').offset().top - 80 }, 300);
                    },
                    error: function(xhr) { console.error(xhr); }
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

                // Guardar pagoId en el botón imprimir para usarlo en el click
                $('#btnImprimirDetalle').data('pago-id', pagoId);

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

                // Colores por tipo de pago
                var tipoBadge = {
                    'Efectivo':      'bg-success',
                    'Transferencia': 'bg-info',
                    'Depósito':      'bg-primary',
                    'Tarjeta':       'bg-warning text-dark',
                };
                var tipoBadgeClass = tipoBadge[pago.tipo_pago] || 'bg-secondary';

                // ── Cabecera: recibo + monto destacado ──────────────────
                var html = `
                <div class="d-flex align-items-center justify-content-between mb-3 p-3 rounded" style="background:linear-gradient(135deg,#405189ee,#405189aa);color:#fff;">
                    <div>
                        <div class="small opacity-75 mb-1"><i class="ri-file-text-line me-1"></i>N° de Recibo</div>
                        <div class="fs-5 fw-bold">${pago.recibo || 'N/A'}</div>
                        <div class="small opacity-75 mt-1"><i class="ri-calendar-line me-1"></i>${formatDate(pago.fecha_pago)}</div>
                    </div>
                    <div class="text-end">
                        <div class="small opacity-75 mb-1">Monto pagado</div>
                        <div class="fs-4 fw-bold">${formatMoney(pago.pago_bs)} Bs</div>
                        ${pago.descuento_bs > 0 ? `<div class="small opacity-75">Descuento: -${formatMoney(pago.descuento_bs)} Bs</div>` : ''}
                        <span class="badge ${tipoBadgeClass} mt-1">${pago.tipo_pago || 'N/A'}</span>
                    </div>
                </div>`;

                // ── Tarjetas de info: estudiante | destino ───────────────
                html += `<div class="row g-2 mb-3">`;

                // Tarjeta estudiante
                html += `<div class="col-md-6">
                    <div class="border rounded p-2 h-100">
                        <div class="fw-semibold text-muted small text-uppercase mb-2">
                            <i class="ri-user-line me-1"></i>Participante
                        </div>`;
                if (estudiante && estudiante.persona) {
                    html += `
                        <div class="mb-1">
                            <span class="text-muted small d-block">Nombre</span>
                            <strong>${estudiante.persona.nombres} ${estudiante.persona.apellido_paterno} ${estudiante.persona.apellido_materno || ''}</strong>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Carnet</span>
                            <span class="badge bg-secondary">${estudiante.persona.carnet || 'N/A'}</span>
                        </div>`;
                } else {
                    html += `<div class="text-muted small">Información no disponible</div>`;
                }
                html += `</div></div>`;

                // Tarjeta programa (tomado de la primera cuota)
                var primeraCuota = cuotas && cuotas.length > 0 ? cuotas[0].cuota : null;
                // Laravel serializa relaciones camelCase a snake_case en JSON
                var oferta = primeraCuota && primeraCuota.inscripcion
                    ? (primeraCuota.inscripcion.oferta_academica || primeraCuota.inscripcion.ofertaAcademica || null)
                    : null;
                var nombrePrograma = oferta && oferta.programa ? oferta.programa.nombre : 'No disponible';

                html += `<div class="col-md-6">
                    <div class="border rounded p-2 h-100">
                        <div class="fw-semibold text-muted small text-uppercase mb-2">
                            <i class="ri-graduation-cap-line me-1"></i>Programa
                        </div>
                        <div>
                            <span class="text-muted small d-block">Programa de Posgrado</span>
                            <strong class="small">${nombrePrograma}</strong>
                        </div>
                    </div>
                </div></div>`;

                // ── Cuotas pagadas ───────────────────────────────────────
                if (cuotas && cuotas.length > 0) {
                    html += `<div class="border rounded p-2 mb-2">
                        <div class="fw-semibold text-muted small text-uppercase mb-2">
                            <i class="ri-file-list-3-line me-1"></i>Cuotas incluidas en este pago
                        </div>
                        <div class="list-group list-group-flush">`;

                    cuotas.forEach(function(cuotaPago) {
                        var cuota = cuotaPago.cuota;
                        var ofertaCuota = cuota && cuota.inscripcion
                            ? (cuota.inscripcion.oferta_academica || cuota.inscripcion.ofertaAcademica || null)
                            : null;
                        var progCuota = ofertaCuota && ofertaCuota.programa ? ofertaCuota.programa.nombre : 'N/A';

                        html += `
                        <div class="list-group-item px-1 py-2 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fw-medium d-block">${cuota ? cuota.nombre : 'N/A'}</span>
                                <small class="text-muted"><i class="ri-graduation-cap-line me-1"></i>${progCuota}</small>
                            </div>
                            <span class="badge bg-success-subtle text-success fw-semibold">
                                ${formatMoney(cuotaPago.pago_bs)} Bs
                            </span>
                        </div>`;
                    });

                    html += `</div></div>`;
                }

                // ── Detalle de métodos de pago ───────────────────────────
                if (pago.detalles && pago.detalles.length > 0) {
                    html += `<div class="border rounded p-2">
                        <div class="fw-semibold text-muted small text-uppercase mb-2">
                            <i class="ri-bank-line me-1"></i>Métodos de pago
                        </div>
                        <div class="list-group list-group-flush">`;

                    pago.detalles.forEach(function(detalle) {
                        var badgeCls = tipoBadge[detalle.tipo_pago] || 'bg-secondary';
                        html += `
                        <div class="list-group-item px-1 py-2 d-flex align-items-center justify-content-between">
                            <span class="badge ${badgeCls}">${detalle.tipo_pago || 'N/A'}</span>
                            <span class="fw-medium">${formatMoney(detalle.pago_bs)} Bs</span>
                        </div>`;
                    });

                    html += `</div></div>`;
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

            // Imprimir / descargar recibo oficial
            $('#btnImprimirDetalle').on('click', function() {
                var pagoId = $(this).data('pago-id');
                if (pagoId) {
                    window.open('/admin/estudiantes/pago/' + pagoId + '/descargar-recibo', '_blank');
                }
            });
        });
    </script>
@endpush
