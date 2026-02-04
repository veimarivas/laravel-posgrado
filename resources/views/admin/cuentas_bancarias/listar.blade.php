@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Gestión de Cuentas Bancarias</h4>
                    @if (Auth::guard('web')->user()->can('cuentas-bancarias.registrar'))
                        <div class="page-title-right">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrar">
                                <i class="ri-add-line align-middle me-1"></i> Registrar Cuenta
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Filters Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-10">
                                <div class="search-box">
                                    <input type="text" id="searchInput" class="form-control"
                                        placeholder="Buscar por número de cuenta, banco o sucursal...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="clearFilters" class="btn btn-outline-secondary w-100">
                                    <i class="ri-refresh-line me-1"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Results Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Lista de Cuentas Bancarias Registradas</h5>
                    </div>
                    <div class="card-body">
                        <div id="results-container">
                            <div class="table-responsive">
                                <table class="table table-hover table-centered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">ID</th>
                                            <th>Número de Cuenta</th>
                                            <th>Banco</th>
                                            <th>Sucursal</th>
                                            <th>Tipo</th>
                                            <th>Moneda</th>
                                            <th>Saldo Actual</th>
                                            <th>Estado</th>
                                            <th width="15%" class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    @include('admin.cuentas_bancarias.partials.table-body')
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Mostrando <span id="showing-count">{{ $cuentas->firstItem() ?? 0 }}</span>
                                    a <span id="to-count">{{ $cuentas->lastItem() ?? 0 }}</span>
                                    de <span id="total-count">{{ $cuentas->total() ?? 0 }}</span> registros
                                </div>
                                <div id="pagination-container">
                                    {{ $cuentas->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Cuenta Bancaria -->
    <div class="modal fade" id="registrar" tabindex="-1" aria-labelledby="registrarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary bg-soft">
                    <h5 class="modal-title text-white" id="registrarLabel">
                        <i class="ri-add-line me-2"></i>Registrar Nueva Cuenta Bancaria
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm" class="forms-sample">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Banco <span class="text-danger">*</span></label>
                                    <select class="form-control" id="banco_id_registro" name="banco_id" required>
                                        <option value="">Seleccionar Banco</option>
                                        <!-- Cargado dinámicamente -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Sucursal <span class="text-danger">*</span></label>
                                    <select class="form-control" id="sucursale_id_registro" name="sucursale_id" required>
                                        <option value="">Seleccionar Sucursal</option>
                                        <!-- Cargado dinámicamente -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Número de Cuenta <span class="text-danger">*</span></label>
                                    <input type="text" id="numero_cuenta_registro" name="numero_cuenta"
                                        class="form-control" placeholder="Ej: 1234567890" required>
                                    <div id="feedback_numero_cuenta_registro" class="form-text"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Cuenta <span class="text-danger">*</span></label>
                                    <select class="form-control" id="tipo_cuenta_registro" name="tipo_cuenta" required>
                                        <option value="">Seleccionar Tipo</option>
                                        <option value="ahorro">Ahorro</option>
                                        <option value="corriente">Corriente</option>
                                        <option value="moneda_extranjera">Moneda Extranjera</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Moneda <span class="text-danger">*</span></label>
                                    <select class="form-control" id="moneda_registro" name="moneda" required>
                                        <option value="">Seleccionar Moneda</option>
                                        <option value="BS">Bolivianos (BS)</option>
                                        <option value="USD">Dólares (USD)</option>
                                        <option value="EUR">Euros (EUR)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcion_registro" name="descripcion" rows="2"
                                        placeholder="Descripción opcional..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Saldo Inicial <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" id="saldo_inicial_registro"
                                        name="saldo_inicial" class="form-control" placeholder="0.00" required>
                                </div>
                            </div>
                            <!-- En el formulario de registro -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Estado</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="activa" value="0">
                                        <!-- Campo oculto con valor 0 -->
                                        <input class="form-check-input" type="checkbox" id="activa_registro"
                                            name="activa" value="1" checked>
                                        <label class="form-check-label" for="activa_registro">Activa</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="addForm" class="btn btn-primary addBtn" disabled>
                        <i class="ri-save-line me-1"></i> Registrar Cuenta
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modificar Cuenta Bancaria -->
    <div class="modal fade" id="modalModificar" tabindex="-1" aria-labelledby="modalModificarLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning bg-soft">
                    <h5 class="modal-title" id="modalModificarLabel">
                        <i class="ri-edit-line me-2"></i>Modificar Cuenta Bancaria
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateForm" class="forms-sample">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="cuentaId">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Banco <span class="text-danger">*</span></label>
                                    <select class="form-control" id="banco_id_edicion" name="banco_id" required>
                                        <option value="">Seleccionar Banco</option>
                                        <!-- Cargado dinámicamente -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Sucursal <span class="text-danger">*</span></label>
                                    <select class="form-control" id="sucursale_id_edicion" name="sucursale_id" required>
                                        <option value="">Seleccionar Sucursal</option>
                                        <!-- Cargado dinámicamente -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Número de Cuenta <span class="text-danger">*</span></label>
                                    <input type="text" id="numero_cuenta_edicion" name="numero_cuenta"
                                        class="form-control" required>
                                    <div id="feedback_numero_cuenta_edicion" class="form-text"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Cuenta <span class="text-danger">*</span></label>
                                    <select class="form-control" id="tipo_cuenta_edicion" name="tipo_cuenta" required>
                                        <option value="">Seleccionar Tipo</option>
                                        <option value="ahorro">Ahorro</option>
                                        <option value="corriente">Corriente</option>
                                        <option value="moneda_extranjera">Moneda Extranjera</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Moneda <span class="text-danger">*</span></label>
                                    <select class="form-control" id="moneda_edicion" name="moneda" required>
                                        <option value="">Seleccionar Moneda</option>
                                        <option value="BS">Bolivianos (BS)</option>
                                        <option value="USD">Dólares (USD)</option>
                                        <option value="EUR">Euros (EUR)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcion_edicion" name="descripcion" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Saldo Inicial <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" id="saldo_inicial_edicion"
                                        name="saldo_inicial" class="form-control" required>
                                </div>
                            </div>
                            <!-- En el formulario de edición -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Estado</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="activa" value="0">
                                        <!-- Campo oculto con valor 0 -->
                                        <input class="form-check-input" type="checkbox" id="activa_edicion"
                                            name="activa" value="1">
                                        <label class="form-check-label" for="activa_edicion">Activa</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="updateForm" class="btn btn-warning updateBtn" disabled>
                        <i class="ri-save-line me-1"></i> Actualizar Cuenta
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar Cuenta Bancaria -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger bg-soft">
                    <h5 class="modal-title text-white" id="modalEliminarLabel">
                        <i class="ri-delete-bin-line me-2"></i>Eliminar Cuenta Bancaria
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="deleteForm" class="forms-sample">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" id="eliminarId">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-4">
                                <div class="avatar-title bg-danger bg-soft text-danger rounded-circle">
                                    <i class="ri-error-warning-line fs-2"></i>
                                </div>
                            </div>
                            <h5>¿Está seguro de eliminar esta cuenta bancaria?</h5>
                            <p class="text-muted">Esta acción no se puede deshacer.</p>
                            <div class="alert alert-warning mt-3" id="warning-pagos" style="display:none;">
                                <i class="ri-alert-line me-1"></i> <strong>Advertencia:</strong> Esta cuenta tiene pagos
                                asociados y no puede ser eliminada.
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="deleteForm" class="btn btn-danger btnDelete">
                        <i class="ri-delete-bin-line me-1"></i> Sí, Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .search-box {
            position: relative;
        }

        .search-box .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #74788d;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(85, 110, 230, 0.04);
        }

        .bg-soft {
            background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
        }

        .bg-warning.bg-soft {
            background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
        }

        .bg-danger.bg-soft {
            background-color: rgba(var(--bs-danger-rgb), 0.1) !important;
        }

        .bg-success.bg-soft {
            background-color: rgba(var(--bs-success-rgb), 0.1) !important;
        }

        .avatar-lg {
            height: 5rem;
            width: 5rem;
        }

        .avatar-title {
            align-items: center;
            display: flex;
            height: 100%;
            justify-content: center;
            width: 100%;
        }

        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .toast {
            min-width: 250px;
        }
    </style>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            let debounceTimer;

            // Llamar a las funciones al cargar la página
            cargarBancos();
            cargarSucursales();

            // También carga los bancos y sucursales cuando se abre el modal de edición
            $('#modalModificar').on('show.bs.modal', function() {
                // Asegurarse de que los selects estén cargados
                if ($('#banco_id_edicion').children().length <= 1) {
                    cargarBancos();
                }
                if ($('#sucursale_id_edicion').children().length <= 1) {
                    cargarSucursales();
                }
            });

            // Carga bancos y sucursales cuando se abre el modal de registro
            $('#registrar').on('show.bs.modal', function() {
                // Asegurarse de que los selects estén cargados
                if ($('#banco_id_registro').children().length <= 1) {
                    cargarBancos();
                }
                if ($('#sucursale_id_registro').children().length <= 1) {
                    cargarSucursales();
                }
            });

            // Cargar bancos y sucursales en los selects
            function cargarBancos() {
                $.ajax({
                    url: "{{ route('admin.cuentas-bancarias.obtener-bancos') }}",
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            let options = '<option value="">Seleccionar Banco</option>';
                            response.bancos.forEach(banco => {
                                options +=
                                    `<option value="${banco.id}">${banco.nombre} (${banco.codigo})</option>`;
                            });
                            $('#banco_id_registro, #banco_id_edicion').html(options);
                        } else {
                            console.error('Error al cargar bancos:', response.message);
                            showNotification('error', 'Error al cargar la lista de bancos');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error en la petición de bancos:', xhr);
                        showNotification('error', 'Error al cargar la lista de bancos');
                    }
                });
            }

            function cargarSucursales() {
                $.ajax({
                    url: "{{ route('admin.cuentas-bancarias.obtener-sucursales') }}",
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            let options = '<option value="">Seleccionar Sucursal</option>';
                            response.sucursales.forEach(sucursal => {
                                options +=
                                    `<option value="${sucursal.id}">${sucursal.nombre} - ${sucursal.direccion}</option>`;
                            });
                            $('#sucursale_id_registro, #sucursale_id_edicion').html(options);
                        } else {
                            console.error('Error al cargar sucursales:', response.message);
                            showNotification('error', 'Error al cargar la lista de sucursales');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error en la petición de sucursales:', xhr);
                        showNotification('error', 'Error al cargar la lista de sucursales');
                    }
                });
            }



            // Resetear formulario de registro
            function resetAddForm() {
                $('#addForm')[0].reset();
                $('#feedback_numero_cuenta_registro').removeClass('text-success text-danger').text('');
                $('.addBtn').prop('disabled', true).html('<i class="ri-save-line me-1"></i> Registrar Cuenta');
            }

            // Evento cuando se cierra el modal de registro
            $('#registrar').on('hidden.bs.modal', resetAddForm);

            // Validar número de cuenta en tiempo real (Registro)
            $('#numero_cuenta_registro').on('input', function() {
                const numeroCuenta = $(this).val().trim();
                const bancoId = $('#banco_id_registro').val();
                const sucursalId = $('#sucursale_id_registro').val();
                const feedback = $('#feedback_numero_cuenta_registro');
                const submitBtn = $('.addBtn');

                feedback.removeClass('text-success text-danger').text('');

                if (numeroCuenta.length === 0 || !bancoId || !sucursalId) {
                    submitBtn.prop('disabled', true);
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.cuentas-bancarias.verificar') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            banco_id: bancoId,
                            sucursale_id: sucursalId,
                            numero_cuenta: numeroCuenta
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').text(
                                    '⚠️ Esta cuenta ya está registrada en este banco y sucursal.'
                                );
                                submitBtn.prop('disabled', true);
                            } else {
                                feedback.addClass('text-success').text(
                                    '✅ Número de cuenta disponible.');
                                validarFormularioRegistro();
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').text(
                                '❌ Error al verificar la cuenta.');
                            submitBtn.prop('disabled', true);
                        }
                    });
                }, 300);
            });

            // Validar formulario de registro completo
            function validarFormularioRegistro() {
                const bancoId = $('#banco_id_registro').val();
                const sucursalId = $('#sucursale_id_registro').val();
                const numeroCuenta = $('#numero_cuenta_registro').val().trim();
                const tipoCuenta = $('#tipo_cuenta_registro').val();
                const moneda = $('#moneda_registro').val();
                const saldoInicial = $('#saldo_inicial_registro').val();
                const submitBtn = $('.addBtn');

                let valido = true;

                if (!bancoId || !sucursalId || !numeroCuenta || !tipoCuenta || !moneda || !saldoInicial) {
                    valido = false;
                }

                submitBtn.prop('disabled', !valido);
            }

            // Escuchar cambios en los campos del formulario de registro
            $('#banco_id_registro, #sucursale_id_registro, #tipo_cuenta_registro, #moneda_registro, #saldo_inicial_registro')
                .on('change', validarFormularioRegistro);
            $('#saldo_inicial_registro').on('input', validarFormularioRegistro);

            // Registro de cuenta bancaria
            $('#addForm').submit(function(e) {
                e.preventDefault();
                const submitBtn = $('.addBtn');
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Registrando...');

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('admin.cuentas-bancarias.registrar') }}",
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        if (res.success) {
                            showNotification('success', res.msg);
                            $('#registrar').modal('hide');
                            loadResults($('#searchInput').val().trim());
                            resetAddForm();
                        } else {
                            showNotification('error', res.msg);
                            submitBtn.prop('disabled', false).html(
                                '<i class="ri-save-line me-1"></i> Registrar Cuenta');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar la cuenta bancaria.';
                        if (xhr.responseJSON?.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        showNotification('error', errorMsg);
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-save-line me-1"></i> Registrar Cuenta');
                    }
                });
            });

            // Editar cuenta bancaria - abrir modal
            $(document).on('click', '.editBtn', function() {
                var data = $(this).data('bs-obj');
                $('#cuentaId').val(data.id);
                $('#banco_id_edicion').val(data.banco_id);
                $('#sucursale_id_edicion').val(data.sucursale_id);
                $('#numero_cuenta_edicion').val(data.numero_cuenta);
                $('#tipo_cuenta_edicion').val(data.tipo_cuenta);
                $('#moneda_edicion').val(data.moneda);
                $('#descripcion_edicion').val(data.descripcion || '');
                $('#saldo_inicial_edicion').val(data.saldo_inicial);
                $('#activa_edicion').prop('checked', data.activa);

                // Resetear feedback
                $('#feedback_numero_cuenta_edicion').removeClass('text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
            });

            // Validar número de cuenta en tiempo real (Edición)
            $('#numero_cuenta_edicion').on('input', function() {
                const numeroCuenta = $(this).val().trim();
                const bancoId = $('#banco_id_edicion').val();
                const sucursalId = $('#sucursale_id_edicion').val();
                const cuentaId = $('#cuentaId').val();
                const feedback = $('#feedback_numero_cuenta_edicion');
                const submitBtn = $('.updateBtn');

                feedback.removeClass('text-success text-danger').text('');

                if (numeroCuenta.length === 0 || !bancoId || !sucursalId) {
                    submitBtn.prop('disabled', true);
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.cuentas-bancarias.verificar') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            banco_id: bancoId,
                            sucursale_id: sucursalId,
                            numero_cuenta: numeroCuenta,
                            id: cuentaId
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').text(
                                    '⚠️ Esta cuenta ya está registrada en este banco y sucursal.'
                                );
                                submitBtn.prop('disabled', true);
                            } else {
                                feedback.addClass('text-success').text(
                                    '✅ Número de cuenta disponible.');
                                validarFormularioEdicion();
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').text(
                                '❌ Error al verificar la cuenta.');
                            submitBtn.prop('disabled', true);
                        }
                    });
                }, 300);
            });

            // Validar formulario de edición completo
            function validarFormularioEdicion() {
                const bancoId = $('#banco_id_edicion').val();
                const sucursalId = $('#sucursale_id_edicion').val();
                const numeroCuenta = $('#numero_cuenta_edicion').val().trim();
                const tipoCuenta = $('#tipo_cuenta_edicion').val();
                const moneda = $('#moneda_edicion').val();
                const saldoInicial = $('#saldo_inicial_edicion').val();
                const submitBtn = $('.updateBtn');

                let valido = true;

                if (!bancoId || !sucursalId || !numeroCuenta || !tipoCuenta || !moneda || !saldoInicial) {
                    valido = false;
                }

                submitBtn.prop('disabled', !valido);
            }

            // Escuchar cambios en los campos del formulario de edición
            $('#banco_id_edicion, #sucursale_id_edicion, #tipo_cuenta_edicion, #moneda_edicion, #saldo_inicial_edicion')
                .on('change', validarFormularioEdicion);
            $('#saldo_inicial_edicion').on('input', validarFormularioEdicion);

            // Actualizar cuenta bancaria
            $('#updateForm').submit(function(e) {
                e.preventDefault();
                const submitBtn = $('.updateBtn');
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Actualizando...');

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('admin.cuentas-bancarias.modificar') }}",
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        if (res.success) {
                            showNotification('success', res.msg);
                            $('#modalModificar').modal('hide');
                            loadResults($('#searchInput').val().trim());
                        } else {
                            showNotification('error', res.msg);
                            submitBtn.prop('disabled', false).html(
                                '<i class="ri-save-line me-1"></i> Actualizar Cuenta');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al actualizar la cuenta bancaria.';
                        if (xhr.responseJSON?.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        showNotification('error', errorMsg);
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-save-line me-1"></i> Actualizar Cuenta');
                    }
                });
            });

            // Eliminar cuenta bancaria - abrir modal
            $(document).on('click', '.deleteBtn', function() {
                var data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);

                // Resetear estado
                $('#warning-pagos').hide();
                $('.btnDelete').prop('disabled', false).removeClass('disabled');
            });

            // Confirmar eliminación de cuenta bancaria
            // Eliminar cuenta bancaria - AJAX
            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                const submitBtn = $('.btnDelete');
                const cuentaId = $('#eliminarId').val();

                if (!cuentaId || cuentaId <= 0) {
                    showNotification('error', 'ID de cuenta no válido');
                    return;
                }

                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Eliminando...'
                );

                // Crear objeto de datos simple (no FormData)
                var data = {
                    id: cuentaId,
                    _token: "{{ csrf_token() }}"
                };

                console.log('Enviando datos para eliminar:', data); // Para debug

                $.ajax({
                    url: "{{ route('admin.cuentas-bancarias.eliminar') }}",
                    type: "POST",
                    data: data,
                    dataType: 'json',
                    success: function(res) {
                        console.log('Respuesta eliminar:', res); // Para debug
                        if (res.success) {
                            showNotification('success', res.msg);
                            $('#modalEliminar').modal('hide');
                            loadResults($('#searchInput').val().trim());
                        } else {
                            // Mostrar advertencia si tiene pagos asociados
                            if (res.msg && res.msg.includes('pagos asociados')) {
                                $('#warning-pagos').show();
                                $('#warning-pagos').html(
                                    `<i class="ri-alert-line me-1"></i> <strong>Advertencia:</strong> ${res.msg}`
                                );
                            } else {
                                showNotification('error', res.msg ||
                                    'No se pudo eliminar la cuenta bancaria');
                            }
                            submitBtn.prop('disabled', false).html(
                                '<i class="ri-delete-bin-line me-1"></i> Sí, Eliminar');
                        }
                    },
                    error: function(xhr) {
                        console.log('Error eliminar:', xhr); // Para debug
                        let errorMsg = 'Error al eliminar la cuenta bancaria.';
                        if (xhr.responseJSON?.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        } else if (xhr.responseJSON?.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.status === 405) {
                            errorMsg = 'Método no permitido. Verifica la ruta.';
                        }
                        showNotification('error', errorMsg);
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-delete-bin-line me-1"></i> Sí, Eliminar');
                    }
                });
            });

            // Funciones auxiliares
            function showNotification(type, message) {
                let toastContainer = $('#toast-container');
                if (toastContainer.length === 0) {
                    toastContainer = $(
                        '<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999"></div>'
                    );
                    $('body').append(toastContainer);
                }

                const toast = $(`
            <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `);

                toastContainer.append(toast);
                const bsToast = new bootstrap.Toast(toast[0]);
                bsToast.show();

                toast.on('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }

            function loadResults(search = '') {
                $.ajax({
                    url: '{{ route('admin.cuentas-bancarias.listar') }}',
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#results-container .table-responsive table').find('tbody').replaceWith(
                            response.html);
                        $('#pagination-container').html(response.pagination);
                        if (response.total !== undefined) {
                            $('#showing-count').text(response.from || 0);
                            $('#to-count').text(response.to || 0);
                            $('#total-count').text(response.total || 0);
                        }
                        initTooltips();
                    },
                    error: function() {
                        showNotification('error', 'Error al cargar los resultados');
                    }
                });
            }

            function initTooltips() {
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipTriggerList.forEach(tooltipTriggerEl => {
                    if (tooltipTriggerEl._tooltip) {
                        tooltipTriggerEl._tooltip.dispose();
                    }
                });
                tooltipTriggerList.forEach(tooltipTriggerEl => {
                    new bootstrap.Tooltip(tooltipTriggerEl, {
                        container: 'body',
                        trigger: 'hover'
                    });
                });
            }

            // Búsqueda
            $('#searchInput').on('input', function() {
                const searchTerm = $(this).val().trim();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadResults(searchTerm);
                }, 300);
            });

            // Limpiar filtros
            $('#clearFilters').on('click', function() {
                $('#searchInput').val('');
                loadResults();
            });

            // Manejar paginación
            $(document).on('click', '#pagination-container .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                const search = $('#searchInput').val().trim();

                if (!url) return;

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#results-container .table-responsive table').find('tbody')
                            .replaceWith(response.html);
                        $('#pagination-container').html(response.pagination);
                        if (response.total !== undefined) {
                            $('#showing-count').text(response.from || 0);
                            $('#to-count').text(response.to || 0);
                            $('#total-count').text(response.total || 0);
                        }
                        initTooltips();
                    },
                    error: function() {
                        showNotification('error', 'Error al cargar la página');
                    }
                });
            });

            // Inicializar tooltips
            initTooltips();
        });
    </script>
@endpush
