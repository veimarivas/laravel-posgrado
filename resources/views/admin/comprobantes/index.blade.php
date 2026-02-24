@extends('admin.dashboard')

@section('admin')
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">Comprobantes de Pago</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Comprobantes</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card border">
        <div class="card-body">
            <form id="formFiltrosComprobantes">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="pendiente">Pendientes</option>
                            <option value="verificado">Verificados</option>
                            <option value="rechazado">Rechazados</option>
                            <option value="todos">Todos</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                    </div>
                    <div class="col-md-2">
                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                    </div>
                    <div class="col-md-4">
                        <label for="search" class="form-label">Buscar</label>
                        <input type="text" class="form-control" id="search" name="search"
                            placeholder="Estudiante, carnet, programa...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line me-1"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de comprobantes -->
    <div class="card border mt-3">
        <div class="card-body">
            <div class="table-responsive" id="tablaComprobantesContainer">
                @include('admin.comprobantes.partials.table-body', ['comprobantes' => collect()])
            </div>
        </div>
    </div>

    <!-- Modales -->
    @include('admin.comprobantes.partials.modal-verificar')
    @include('admin.comprobantes.partials.modal-rechazar')
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if (typeof Swal === 'undefined') {
                var script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                document.head.appendChild(script);
            }
            // Cargar datos iniciales
            cargarComprobantes();

            // Evento submit del filtro
            $('#formFiltrosComprobantes').on('submit', function(e) {
                e.preventDefault();
                cargarComprobantes();
            });

            // Función para cargar comprobantes vía AJAX
            function cargarComprobantes() {
                var data = $('#formFiltrosComprobantes').serialize();

                $.ajax({
                    url: "{{ route('admin.comprobantes.datos') }}",
                    type: "GET",
                    data: data,
                    success: function(response) {
                        $('#tablaComprobantesContainer').html(response.html);
                        // Si hay paginación, reasignar eventos
                        if (response.pagination) {
                            $('#pagination-links').html(response.pagination);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        Swal.fire('Error', 'No se pudieron cargar los comprobantes', 'error');
                    }
                });
            }

            // Eventos de paginación (delegación)
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: "GET",
                    data: $('#formFiltrosComprobantes').serialize(),
                    success: function(response) {
                        $('#tablaComprobantesContainer').html(response.html);
                    },
                    error: function(xhr) {
                        console.error(xhr);
                    }
                });
            });

            // Ver detalle y abrir modal de verificación
            $(document).on('click', '.btn-verificar', function() {
                var comprobanteId = $(this).data('id');
                abrirModalVerificar(comprobanteId);
            });

            // Abrir modal de rechazo
            $(document).on('click', '.btn-rechazar', function() {
                var comprobanteId = $(this).data('id');
                $('#rechazar_comprobante_id').val(comprobanteId);
                $('#modalRechazar').modal('show');
            });

            // Función para abrir modal de verificar y cargar datos
            function abrirModalVerificar(id) {
                $('#modalVerificar').modal('show');
                $('#modalVerificar .modal-body').html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3">Cargando datos del comprobante...</p>
                    </div>
                `);

                $.ajax({
                    url: `/admin/comprobante/${id}/detalle`,
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            mostrarFormularioVerificacion(response);
                        } else {
                            $('#modalVerificar .modal-body').html(`
                                <div class="alert alert-danger">${response.message}</div>
                            `);
                        }
                    },
                    error: function(xhr) {
                        $('#modalVerificar .modal-body').html(`
                            <div class="alert alert-danger">Error al cargar los datos.</div>
                        `);
                    }
                });
            }

            function mostrarFormularioVerificacion(data) {
                console.log('Datos recibidos:', data); // Para depurar

                var comp = data.comprobante;
                var estudiante = data.estudiante;
                var programa = data.programa;
                var cuotasAsociadas = data.cuotas; // cuotas ya vinculadas al comprobante
                var cuotasPendientes = data.cuotas_pendientes; // otras cuotas pendientes (opcional)
                var archivoUrl = data.archivo_url;

                // Combinar cuotas para mostrar en checkboxes (sugiero mostrar todas las pendientes de la inscripción,
                // pero preseleccionar las que ya están asociadas al comprobante)
                var todasLasCuotas = cuotasAsociadas; // Simplificamos: solo las asociadas

                // Construir checkboxes
                var cuotasHtml = '';
                todasLasCuotas.forEach(function(cuota) {
                    cuotasHtml += `
            <div class="form-check">
                <input class="form-check-input cuota-checkbox" type="checkbox" name="cuota_ids[]" value="${cuota.id}" id="cuota_${cuota.id}" checked>
                <label class="form-check-label" for="cuota_${cuota.id}">
                    ${cuota.nombre} (Cuota ${cuota.n_cuota}) - Pendiente: ${cuota.pago_pendiente_bs} Bs
                </label>
            </div>
        `;
                });

                // Si hay cuotas pendientes adicionales, podríamos mostrarlas también (opcional)
                if (cuotasPendientes && cuotasPendientes.length > 0) {
                    cuotasHtml +=
                        '<hr><p class="text-muted">Otras cuotas pendientes (puede seleccionar para incluir en este pago):</p>';
                    cuotasPendientes.forEach(function(cuota) {
                        cuotasHtml += `
                <div class="form-check">
                    <input class="form-check-input cuota-checkbox" type="checkbox" name="cuota_ids[]" value="${cuota.id}" id="cuota_extra_${cuota.id}">
                    <label class="form-check-label" for="cuota_extra_${cuota.id}">
                        ${cuota.nombre} (Cuota ${cuota.n_cuota}) - Pendiente: ${cuota.pago_pendiente_bs} Bs
                    </label>
                </div>
            `;
                    });
                }

                // Construir el formulario
                var html = `
        <div class="row">
            <div class="col-md-6">
                <h6>Estudiante: ${estudiante.nombre}</h6>
                <p>Carnet: ${estudiante.carnet}</p>
                <p>Programa: ${programa}</p>
                <p>Archivo: <a href="${archivoUrl}" target="_blank" class="btn btn-sm btn-primary">Ver comprobante</a></p>
            </div>
            <div class="col-md-6">
                <h6>Cuotas asociadas:</h6>
                ${cuotasHtml}
            </div>
        </div>
        <hr>
        <form id="formVerificarPago">
            @csrf
            <input type="hidden" name="comprobante_id" value="${comp.id}">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="monto_pago" class="form-label">Monto a pagar (Bs) *</label>
                        <input type="number" step="0.01" class="form-control" id="monto_pago" name="monto_pago" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="descuento" class="form-label">Descuento (Bs)</label>
                        <input type="number" step="0.01" class="form-control" id="descuento_bs" name="descuento_bs" value="0">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tipo_pago" class="form-label">Tipo de pago *</label>
                        <select class="form-select" id="tipo_pago" name="tipo_pago" required>
                            <option value="">Seleccionar</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Transferencia">Transferencia</option>
                            <option value="Depósito">Depósito</option>
                            <option value="Tarjeta">Tarjeta</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="fecha_pago" class="form-label">Fecha de pago *</label>
                        <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="col-md-6" id="campo_caja" style="display: none;">
                    <div class="mb-3">
                        <label for="caja_id" class="form-label">Caja *</label>
                        <select class="form-select" id="caja_id" name="caja_id">
                            <option value="">Seleccionar caja...</option>
                            @foreach (\App\Models\Caja::where('activa', true)->with('sucursal')->get() as $caja)
                                <option value="{{ $caja->id }}">
                                    {{ $caja->nombre }} - {{ $caja->sucursal->nombre ?? 'Sin sucursal' }} (Saldo: {{ number_format($caja->saldo_actual, 2) }} Bs)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6" id="campo_cuenta_bancaria" style="display: none;">
                    <div class="mb-3">
                        <label for="cuenta_bancaria_id" class="form-label">Cuenta Bancaria *</label>
                        <select class="form-select" id="cuenta_bancaria_id" name="cuenta_bancaria_id">
                            <option value="">Seleccionar cuenta...</option>
                            @foreach (\App\Models\CuentasBancarias::where('activa', true)->with('banco')->get() as $cuenta)
                                <option value="{{ $cuenta->id }}">
                                    {{ $cuenta->banco->nombre ?? 'Sin banco' }} - {{ $cuenta->numero_cuenta }} ({{ $cuenta->moneda }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6" id="campo_comprobante" style="display: none;">
                    <div class="mb-3">
                        <label for="n_comprobante" class="form-label">N° Comprobante *</label>
                        <input type="text" class="form-control" id="n_comprobante" name="n_comprobante" placeholder="Ej: TRF-0012345">
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </form>
    `;

                $('#modalVerificar .modal-body').html(html);
                $('#modalVerificar .modal-footer').html(`
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" id="btnConfirmarVerificar">Verificar y Registrar Pago</button>
    `);

                // Reasignar eventos para mostrar campos según tipo de pago
                $('#tipo_pago').on('change', togglePaymentFields);
                togglePaymentFields(); // inicializar

                $('#btnConfirmarVerificar').off('click').on('click', function() {
                    var formData = $('#formVerificarPago').serializeArray();

                    // Obtener IDs de las cuotas seleccionadas (checkboxes marcados)
                    var cuotaIds = [];
                    $('.cuota-checkbox:checked').each(function() {
                        cuotaIds.push($(this).val());
                    });

                    // Validar que haya al menos una cuota seleccionada
                    if (cuotaIds.length === 0) {
                        Swal.fire('Error', 'Debe seleccionar al menos una cuota', 'warning');
                        return;
                    }

                    // Agregar cada ID como campo independiente con nombre cuota_ids[]
                    cuotaIds.forEach(function(id) {
                        formData.push({
                            name: 'cuota_ids[]',
                            value: id
                        });
                    });

                    // Agregar campos según tipo de pago
                    var tipoPago = $('#tipo_pago').val();
                    if (tipoPago === 'Efectivo') {
                        formData.push({
                            name: 'caja_id',
                            value: $('#caja_id').val()
                        });
                    } else if (['Transferencia', 'Depósito', 'Tarjeta'].includes(tipoPago)) {
                        formData.push({
                            name: 'cuenta_bancaria_id',
                            value: $('#cuenta_bancaria_id').val()
                        });
                        formData.push({
                            name: 'n_comprobante',
                            value: $('#n_comprobante').val()
                        });
                    }

                    // Enviar AJAX
                    $.ajax({
                        url: `/admin/comprobante/${comp.id}/verificar`,
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Éxito', response.message, 'success');
                                $('#modalVerificar').modal('hide');
                                cargarComprobantes();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            var errors = xhr.responseJSON.errors;
                            var msg = 'Error al procesar';
                            if (errors) {
                                msg = Object.values(errors).flat().join('<br>');
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            Swal.fire('Error', msg, 'error');
                        }
                    });
                });
            }

            // Función para mostrar/ocultar campos según tipo de pago (copiada de tu modal de pago)
            function togglePaymentFields() {
                var tipoPago = $('#tipo_pago').val();
                $('#campo_caja').hide();
                $('#campo_cuenta_bancaria').hide();
                $('#campo_comprobante').hide();

                $('#caja_id').prop('required', false);
                $('#cuenta_bancaria_id').prop('required', false);
                $('#n_comprobante').prop('required', false);

                if (tipoPago === 'Efectivo') {
                    $('#campo_caja').show();
                    $('#caja_id').prop('required', true);
                } else if (['Transferencia', 'Depósito', 'Tarjeta'].includes(tipoPago)) {
                    $('#campo_cuenta_bancaria').show();
                    $('#campo_comprobante').show();
                    $('#cuenta_bancaria_id').prop('required', true);
                    $('#n_comprobante').prop('required', true);
                }
            }

            // Rechazar comprobante
            $('#formRechazar').on('submit', function(e) {
                e.preventDefault();
                var comprobanteId = $('#rechazar_comprobante_id').val();
                var motivo = $('#motivo_rechazo').val();

                $.ajax({
                    url: `/admin/comprobante/${comprobanteId}/rechazar`,
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        motivo_rechazo: motivo
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Éxito', response.message, 'success');
                            $('#modalRechazar').modal('hide');
                            cargarComprobantes();
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var msg = 'Error al rechazar';
                        if (errors) {
                            msg = Object.values(errors).flat().join('<br>');
                        }
                        Swal.fire('Error', msg, 'error');
                    }
                });
            });
        });
    </script>
@endpush
