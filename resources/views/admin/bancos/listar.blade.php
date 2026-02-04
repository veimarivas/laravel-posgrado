@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Gestión de Bancos</h4>
                    @if (Auth::guard('web')->user()->can('bancos.registrar'))
                        <div class="page-title-right">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrar">
                                <i class="ri-add-line align-middle me-1"></i> Registrar Banco
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
                                        placeholder="Buscar banco...">
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
                        <h5 class="card-title mb-0">Lista de Bancos Registrados</h5>
                    </div>
                    <div class="card-body">
                        <div id="results-container">
                            <div class="table-responsive">
                                <table class="table table-hover table-centered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">ID</th>
                                            <th>Logo</th>
                                            <th>Nombre del Banco</th>
                                            <th>Código</th>
                                            <th>Color</th>
                                            <th>N° Cuentas</th>
                                            <th width="15%" class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    @include('admin.bancos.partials.table-body')
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Mostrando <span id="showing-count">{{ $bancos->firstItem() ?? 0 }}</span>
                                    a <span id="to-count">{{ $bancos->lastItem() ?? 0 }}</span>
                                    de <span id="total-count">{{ $bancos->total() ?? 0 }}</span> registros
                                </div>
                                <div id="pagination-container">
                                    {{ $bancos->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Banco -->
    <div class="modal fade" id="registrar" tabindex="-1" aria-labelledby="registrarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary bg-soft">
                    <h5 class="modal-title text-white" id="registrarLabel">
                        <i class="ri-add-line me-2"></i>Registrar Nuevo Banco
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm" class="forms-sample">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Nombre del Banco <span class="text-danger">*</span></label>
                                    <input type="text" id="nombre_registro" name="nombre" class="form-control"
                                        placeholder="Ej: Banco Nacional de Bolivia" required>
                                    <div id="feedback_nombre_registro" class="form-text"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Código <span class="text-danger">*</span></label>
                                    <input type="text" id="codigo_registro" name="codigo" class="form-control"
                                        placeholder="Ej: BNB" required>
                                    <div id="feedback_codigo_registro" class="form-text"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Color</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" class="form-control form-control-color" id="color_registro"
                                            name="color" value="#0d6efd" title="Elige un color">
                                        <span id="colorPreviewRegistro" class="d-inline-block"
                                            style="width: 24px; height: 24px; border: 1px solid #ddd; border-radius: 4px;"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Logo (URL)</label>
                                    <input type="text" id="logo_registro" name="logo" class="form-control"
                                        placeholder="https://ejemplo.com/logo.png">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="addForm" class="btn btn-primary addBtn" disabled>
                        <i class="ri-save-line me-1"></i> Registrar Banco
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modificar Banco -->
    <div class="modal fade" id="modalModificar" tabindex="-1" aria-labelledby="modalModificarLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning bg-soft">
                    <h5 class="modal-title" id="modalModificarLabel">
                        <i class="ri-edit-line me-2"></i>Modificar Banco
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateForm" class="forms-sample">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="bancoId">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Nombre del Banco <span class="text-danger">*</span></label>
                                    <input type="text" id="nombre_edicion" name="nombre" class="form-control"
                                        required>
                                    <div id="feedback_nombre_edicion" class="form-text"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Código <span class="text-danger">*</span></label>
                                    <input type="text" id="codigo_edicion" name="codigo" class="form-control"
                                        required>
                                    <div id="feedback_codigo_edicion" class="form-text"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Color</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" class="form-control form-control-color" id="color_edicion"
                                            name="color" value="#0d6efd" title="Elige un color">
                                        <span id="colorPreviewEdicion" class="d-inline-block"
                                            style="width: 24px; height: 24px; border: 1px solid #ddd; border-radius: 4px;"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Logo (URL)</label>
                                    <input type="text" id="logo_edicion" name="logo" class="form-control">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="updateForm" class="btn btn-warning updateBtn" disabled>
                        <i class="ri-save-line me-1"></i> Actualizar Banco
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar Banco -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger bg-soft">
                    <h5 class="modal-title text-white" id="modalEliminarLabel">
                        <i class="ri-delete-bin-line me-2"></i>Eliminar Banco
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
                            <h5>¿Está seguro de eliminar este banco?</h5>
                            <p class="text-muted">Esta acción no se puede deshacer y puede afectar a las cuentas bancarias
                                asociadas.</p>
                            <div class="alert alert-warning mt-3" id="warning-cuentas" style="display:none;">
                                <i class="ri-alert-line me-1"></i> <strong>Advertencia:</strong> Este banco tiene cuentas
                                bancarias asociadas. Deberá eliminar primero todas sus cuentas para poder eliminar este
                                banco.
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

    <!-- Modal Ver Banco -->
    <div class="modal fade" id="modalVerBanco" tabindex="-1" aria-labelledby="modalVerBancoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info bg-soft">
                    <h5 class="modal-title text-white" id="modalVerBancoLabel">
                        <i class="ri-eye-line me-2"></i>Detalle del Banco
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="bancoDetalleContent">
                        <!-- Cargado dinámicamente -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            let debounceTimer;

            // Resetear formulario de registro
            function resetAddForm() {
                $('#addForm')[0].reset();
                $('#color_registro').val('#0d6efd');
                $('#colorPreviewRegistro').css('background-color', '#0d6efd');
                $('#feedback_nombre_registro, #feedback_codigo_registro').removeClass('text-success text-danger')
                    .text('');
                $('.addBtn').prop('disabled', true).html('<i class="ri-save-line me-1"></i> Registrar Banco');
            }

            // Evento cuando se cierra el modal de registro
            $('#registrar').on('hidden.bs.modal', resetAddForm);

            // Preview de color en registro
            $('#color_registro').on('input', function() {
                $('#colorPreviewRegistro').css('background-color', $(this).val());
            });

            // Preview de color en edición
            $('#color_edicion').on('input', function() {
                $('#colorPreviewEdicion').css('background-color', $(this).val());
            });

            // Validar nombre y código en tiempo real (Registro)
            $('#nombre_registro, #codigo_registro').on('input', function() {
                const nombre = $('#nombre_registro').val().trim();
                const codigo = $('#codigo_registro').val().trim();
                const feedbackNombre = $('#feedback_nombre_registro');
                const feedbackCodigo = $('#feedback_codigo_registro');
                const submitBtn = $('.addBtn');

                // Limpiar mensajes
                feedbackNombre.removeClass('text-success text-danger').text('');
                feedbackCodigo.removeClass('text-success text-danger').text('');

                if (nombre.length === 0 || codigo.length === 0) {
                    submitBtn.prop('disabled', true);
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.bancos.verificar') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre,
                            codigo: codigo
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedbackNombre.addClass('text-danger').text(
                                    '⚠️ El nombre o código ya está registrado.');
                                feedbackCodigo.addClass('text-danger').text(
                                    '⚠️ El nombre o código ya está registrado.');
                                submitBtn.prop('disabled', true);
                            } else {
                                feedbackNombre.addClass('text-success').text(
                                    '✅ Nombre disponible.');
                                feedbackCodigo.addClass('text-success').text(
                                    '✅ Código disponible.');
                                submitBtn.prop('disabled', false);
                            }
                        },
                        error: function() {
                            feedbackNombre.addClass('text-danger').text(
                                '❌ Error al verificar.');
                            submitBtn.prop('disabled', true);
                        }
                    });
                }, 300);
            });

            // Registro de banco
            $('#addForm').submit(function(e) {
                e.preventDefault();
                const submitBtn = $('.addBtn');
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Registrando...');

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('admin.bancos.registrar') }}",
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
                                '<i class="ri-save-line me-1"></i> Registrar Banco');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar el banco.';
                        if (xhr.responseJSON?.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        showNotification('error', errorMsg);
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-save-line me-1"></i> Registrar Banco');
                    }
                });
            });

            // Editar banco - abrir modal
            $(document).on('click', '.editBtn', function() {
                var data = $(this).data('bs-obj');
                $('#bancoId').val(data.id);
                $('#nombre_edicion').val(data.nombre);
                $('#codigo_edicion').val(data.codigo);
                $('#color_edicion').val(data.color || '#0d6efd');
                $('#colorPreviewEdicion').css('background-color', data.color || '#0d6efd');
                $('#logo_edicion').val(data.logo || '');

                // Resetear feedback
                $('#feedback_nombre_edicion, #feedback_codigo_edicion').removeClass(
                    'text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
            });

            // Validar en tiempo real para edición
            $('#nombre_edicion, #codigo_edicion').on('input', function() {
                const nombre = $('#nombre_edicion').val().trim();
                const codigo = $('#codigo_edicion').val().trim();
                const id = $('#bancoId').val();
                const feedbackNombre = $('#feedback_nombre_edicion');
                const feedbackCodigo = $('#feedback_codigo_edicion');
                const submitBtn = $('.updateBtn');

                feedbackNombre.removeClass('text-success text-danger').text('');
                feedbackCodigo.removeClass('text-success text-danger').text('');

                if (nombre.length === 0 || codigo.length === 0) {
                    submitBtn.prop('disabled', true);
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.bancos.verificaredicion') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre,
                            codigo: codigo,
                            id: id
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedbackNombre.addClass('text-danger').text(
                                    '⚠️ El nombre o código ya está registrado.');
                                feedbackCodigo.addClass('text-danger').text(
                                    '⚠️ El nombre o código ya está registrado.');
                                submitBtn.prop('disabled', true);
                            } else {
                                feedbackNombre.addClass('text-success').text(
                                    '✅ Nombre disponible.');
                                feedbackCodigo.addClass('text-success').text(
                                    '✅ Código disponible.');
                                submitBtn.prop('disabled', false);
                            }
                        },
                        error: function() {
                            feedbackNombre.addClass('text-danger').text(
                                '❌ Error al verificar.');
                            submitBtn.prop('disabled', true);
                        }
                    });
                }, 300);
            });

            // Actualizar banco
            $('#updateForm').submit(function(e) {
                e.preventDefault();
                const submitBtn = $('.updateBtn');
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Actualizando...');

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('admin.bancos.modificar') }}",
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
                                '<i class="ri-save-line me-1"></i> Actualizar Banco');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al actualizar el banco.';
                        if (xhr.responseJSON?.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        showNotification('error', errorMsg);
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-save-line me-1"></i> Actualizar Banco');
                    }
                });
            });

            // Eliminar banco - abrir modal
            $(document).on('click', '.deleteBtn', function() {
                var data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);

                // Resetear estado
                $('#warning-cuentas').hide();
                $('.btnDelete').prop('disabled', false).removeClass('disabled');

                // Verificar si el banco tiene cuentas
                $.ajax({
                    url: "{{ route('admin.bancos.ver', ':id') }}".replace(':id', data.id),
                    type: "GET",
                    success: function(response) {
                        const tieneCuentas = response.cuentas && response.cuentas.length > 0;
                        const warningElement = $('#warning-cuentas');

                        if (tieneCuentas) {
                            warningElement.show();
                            $('.btnDelete').prop('disabled', true).addClass('disabled');
                            warningElement.html(
                                `<i class="ri-alert-line me-1"></i> <strong>Advertencia:</strong> Este banco tiene ${response.cuentas.length} cuenta${response.cuentas.length > 1 ? 's' : ''} bancaria${response.cuentas.length > 1 ? 's' : ''} asociada${response.cuentas.length > 1 ? 's' : ''}. Deberá eliminar primero todas sus cuentas para poder eliminar este banco.`
                            );
                        } else {
                            warningElement.hide();
                            $('.btnDelete').prop('disabled', false).removeClass('disabled');
                        }
                    },
                    error: function() {
                        $('#warning-cuentas').hide();
                        $('.btnDelete').prop('disabled', false).removeClass('disabled');
                    }
                });
            });

            // Confirmar eliminación de banco
            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                const submitBtn = $('.btnDelete');
                const bancoId = $('#eliminarId').val();

                if (!bancoId || bancoId <= 0) {
                    showNotification('error', 'ID de banco no válido');
                    return;
                }

                if (submitBtn.prop('disabled')) {
                    showNotification('error',
                        'No se puede eliminar el banco porque tiene cuentas asociadas.');
                    return;
                }

                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Eliminando...');

                var formData = new FormData();
                formData.append('id', bancoId);
                formData.append('_token', "{{ csrf_token() }}");
                formData.append('_method', 'DELETE');

                $.ajax({
                    url: "{{ route('admin.bancos.eliminar') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            showNotification('success', res.msg);
                            $('#modalEliminar').modal('hide');
                            loadResults($('#searchInput').val().trim());
                        } else {
                            showNotification('error', res.msg ||
                                'No se pudo eliminar el banco');
                            submitBtn.prop('disabled', false).html(
                                '<i class="ri-delete-bin-line me-1"></i> Sí, Eliminar');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al eliminar el banco.';
                        if (xhr.responseJSON?.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        showNotification('error', errorMsg);
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-delete-bin-line me-1"></i> Sí, Eliminar');
                    }
                });
            });

            // Ver detalle del banco
            $(document).on('click', '.viewBtn', function() {
                var data = $(this).data('bs-obj');
                const modal = $('#modalVerBanco');
                const content = $('#bancoDetalleContent');

                // Mostrar loading
                content.html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3">Cargando información del banco...</p>
            </div>
        `);

                modal.modal('show');

                $.ajax({
                    url: "{{ route('admin.bancos.ver', ':id') }}".replace(':id', data.id),
                    type: "GET",
                    success: function(response) {
                        let cuentasHtml = '';
                        if (response.cuentas && response.cuentas.length > 0) {
                            cuentasHtml = response.cuentas.map(cuenta => `
                        <div class="border rounded p-3 mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">${cuenta.numero_cuenta}</h6>
                                    <p class="mb-0 text-muted small">
                                        <span class="badge bg-${cuenta.activa ? 'success' : 'secondary'}">${cuenta.activa ? 'Activa' : 'Inactiva'}</span>
                                        <span class="ms-2">${cuenta.tipo_cuenta} - ${cuenta.moneda}</span>
                                        <span class="ms-2">Saldo: ${formatCurrency(cuenta.saldo_actual, cuenta.moneda)}</span>
                                    </p>
                                    <p class="mb-0 small">${cuenta.sucursal?.nombre || 'Sin sucursal'}</p>
                                </div>
                            </div>
                        </div>
                    `).join('');
                        } else {
                            cuentasHtml = `
                        <div class="text-center py-4">
                            <i class="ri-bank-card-line fs-3 text-muted"></i>
                            <p class="text-muted mt-2">No hay cuentas bancarias registradas</p>
                        </div>
                    `;
                        }

                        content.html(`
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="mb-4">
                                ${response.banco.logo ? `<img src="${response.banco.logo}" alt="Logo" class="img-fluid rounded" style="max-height: 100px;">` : 
                                `<div class="avatar-lg mx-auto mb-3" style="background-color: ${response.banco.color || '#0d6efd'}; border-radius: 10px;">
                                            <div class="avatar-title text-white fs-2">${response.banco.nombre.charAt(0)}</div>
                                        </div>`}
                            </div>
                            <div class="d-flex align-items-center justify-content-center mb-3">
                                <div class="me-2" style="width: 20px; height: 20px; background-color: ${response.banco.color || '#0d6efd'}; border-radius: 4px;"></div>
                                <span class="text-muted">Color identificador</span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 class="mb-3">${response.banco.nombre}</h4>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label text-muted">Código</label>
                                    <p class="fw-medium">${response.banco.codigo}</p>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted">Total Cuentas</label>
                                    <p class="fw-medium">${response.cuentas?.length || 0}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-4">
                        <h5 class="mb-3">Cuentas Bancarias</h5>
                        <div class="cuentas-container" style="max-height: 300px; overflow-y: auto;">
                            ${cuentasHtml}
                        </div>
                    </div>
                `);
                    },
                    error: function() {
                        content.html(`
                    <div class="text-center py-5">
                        <i class="ri-error-warning-line fs-1 text-danger"></i>
                        <p class="mt-3">Error al cargar la información del banco</p>
                    </div>
                `);
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

            function formatCurrency(amount, currency) {
                const formatter = new Intl.NumberFormat('es-BO', {
                    style: 'currency',
                    currency: currency === 'USD' ? 'USD' : 'BOB',
                    minimumFractionDigits: 2
                });
                return formatter.format(amount);
            }

            function loadResults(search = '') {
                $.ajax({
                    url: '{{ route('admin.bancos.listar') }}',
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
