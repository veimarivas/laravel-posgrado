@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Gestión de Departamentos</h4>
                    @if (Auth::guard('web')->user()->can('departamentos.registrar'))
                        <div class="page-title-right">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrar">
                                <i class="ri-add-line align-middle me-1"></i> Registrar Departamento
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
                                        placeholder="Buscar departamento...">
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
                        <h5 class="card-title mb-0">Lista de Departamentos Registrados</h5>
                    </div>
                    <div class="card-body">
                        <div id="results-container">
                            <div class="table-responsive">
                                <table class="table table-hover table-centered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">ID</th>
                                            <th>Nombre del Departamento</th>
                                            <th width="35%">Ciudades</th>
                                            <th width="15%" class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    @include('admin.departamentos.partials.table-body')
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Mostrando <span id="showing-count">{{ $departamentos->firstItem() ?? 0 }}</span>
                                    a <span id="to-count">{{ $departamentos->lastItem() ?? 0 }}</span>
                                    de <span id="total-count">{{ $departamentos->total() ?? 0 }}</span> registros
                                </div>
                                <div id="pagination-container">
                                    {{ $departamentos->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Departamento -->
    <div class="modal fade" id="registrar" tabindex="-1" aria-labelledby="registrarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary bg-soft">
                    <h5 class="modal-title text-white" id="registrarLabel">
                        <i class="ri-add-line me-2"></i>Registrar Nuevo Departamento
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
                                    <label class="form-label">Nombre del Departamento <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="nombre_registro" name="nombre" class="form-control"
                                        placeholder="Ej: La Paz" required>
                                    <div id="feedback_registro" class="form-text"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="addForm" class="btn btn-primary addBtn" disabled>
                        <i class="ri-save-line me-1"></i> Registrar Departamento
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modificar Departamento -->
    <div class="modal fade" id="modalModificar" tabindex="-1" aria-labelledby="modalModificarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning bg-soft">
                    <h5 class="modal-title" id="modalModificarLabel">
                        <i class="ri-edit-line me-2"></i>Modificar Departamento
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateForm" class="forms-sample">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="departamentoId">
                        <div class="mb-3">
                            <label for="nombre_edicion" class="form-label">Nombre del Departamento <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre_edicion" name="nombre" required>
                            <div id="feedback_edicion" class="form-text"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="updateForm" class="btn btn-warning updateBtn" disabled>
                        <i class="ri-save-line me-1"></i> Actualizar Departamento
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar Departamento -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger bg-soft">
                    <h5 class="modal-title text-white" id="modalEliminarLabel">
                        <i class="ri-delete-bin-line me-2"></i>Eliminar Departamento
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
                            <h5>¿Está seguro de eliminar este departamento?</h5>
                            <p class="text-muted">Esta acción no se puede deshacer y puede afectar a las ciudades asociadas
                                a este departamento.</p>
                            <div class="alert alert-warning mt-3" id="warning-ciudades" style="display:none;">
                                <i class="ri-alert-line me-1"></i> <strong>Advertencia:</strong> Este departamento tiene
                                ciudades asociadas.
                                Deberá eliminar primero todas sus ciudades para poder eliminar este departamento.
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

    <!-- Modal Agregar Ciudad -->
    <div class="modal fade" id="modalAgregarCiudad" tabindex="-1" aria-labelledby="modalAgregarCiudadLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success bg-soft">
                    <h5 class="modal-title text-white" id="modalAgregarCiudadLabel">
                        <i class="ri-add-line me-2"></i>Registrar Ciudad
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarCiudad" class="forms-sample">
                        @csrf
                        <input type="hidden" id="departamento_id" name="departamento_id">
                        <div class="mb-3">
                            <label for="nombre_ciudad" class="form-label">Nombre de la ciudad <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre_ciudad" name="nombre" required>
                            <div id="feedback_nombre_ciudad" class="form-text"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formAgregarCiudad" class="btn btn-success" id="btnRegistrarCiudad"
                        disabled>
                        <i class="ri-save-line me-1"></i> Registrar Ciudad
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Ciudad -->
    <div class="modal fade" id="modalEditarCiudad" tabindex="-1" aria-labelledby="modalEditarCiudadLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning bg-soft">
                    <h5 class="modal-title" id="modalEditarCiudadLabel">
                        <i class="ri-edit-line me-2"></i>Editar Ciudad
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarCiudad" class="forms-sample">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="ciudad_id_edicion" name="id">
                        <input type="hidden" id="departamento_id_edicion" name="departamento_id">
                        <div class="mb-3">
                            <label for="nombre_ciudad_edicion" class="form-label">Nombre de la ciudad <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre_ciudad_edicion" name="nombre"
                                required>
                            <div id="feedback_nombre_ciudad_edicion" class="form-text"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formEditarCiudad" class="btn btn-warning" id="btnEditarCiudad"
                        disabled>
                        <i class="ri-save-line me-1"></i> Actualizar Ciudad
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar Ciudad -->
    <div class="modal fade" id="modalEliminarCiudad" tabindex="-1" aria-labelledby="modalEliminarCiudadLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger bg-soft">
                    <h5 class="modal-title text-white" id="modalEliminarCiudadLabel">
                        <i class="ri-delete-bin-line me-2"></i>Eliminar Ciudad
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="deleteCiudadForm" class="forms-sample">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" id="eliminarCiudadId">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-4">
                                <div class="avatar-title bg-danger bg-soft text-danger rounded-circle">
                                    <i class="ri-error-warning-line fs-2"></i>
                                </div>
                            </div>
                            <h5>¿Está seguro de eliminar esta ciudad?</h5>
                            <p class="text-muted">Esta acción no se puede deshacer.</p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="deleteCiudadForm" class="btn btn-danger btnDeleteCiudad">
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

        .ciudades-container {
            max-height: 250px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .btn-soft-primary {
            background-color: rgba(85, 110, 230, 0.15);
            color: #556ee6;
            border-color: rgba(85, 110, 230, 0.2);
        }

        .btn-soft-danger {
            background-color: rgba(234, 84, 85, 0.15);
            color: #ea5455;
            border-color: rgba(234, 84, 85, 0.2);
        }

        .btn-soft-info {
            background-color: rgba(45, 206, 137, 0.15);
            color: #2dce89;
            border-color: rgba(45, 206, 137, 0.2);
        }

        .ciudades-container::-webkit-scrollbar {
            width: 6px;
        }

        .ciudades-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .ciudades-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .ciudades-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
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

            // Función para resetear el formulario de registro
            function resetAddForm() {
                $('#addForm')[0].reset();
                $('#feedback_registro').removeClass('text-success text-danger').text('');
                $('.addBtn').prop('disabled', true).html(
                    '<i class="ri-save-line me-1"></i> Registrar Departamento');
            }

            // Evento cuando se cierra el modal de registro
            $('#registrar').on('hidden.bs.modal', function() {
                resetAddForm();
            });

            // Verifica si el nombre del departamento está en la BD (Registro)
            $('#nombre_registro').on('input', function() {
                const nombre = $(this).val().trim();
                const feedback = $('#feedback_registro');
                const submitBtn = $('.addBtn');

                feedback.removeClass('text-success text-danger').text('');

                if (nombre.length === 0) {
                    submitBtn.prop('disabled', true);
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.departamentos.verificar') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').text(
                                    '⚠️ El departamento ya está registrado.');
                                submitBtn.prop('disabled', true);
                            } else {
                                feedback.addClass('text-success').text(
                                    '✅ Nombre disponible.');
                                submitBtn.prop('disabled', false);
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').text(
                                '❌ Error al verificar el nombre.');
                            submitBtn.prop('disabled', true);
                        }
                    });
                }, 300);
            });

            // Registro de departamento
            $('#addForm').submit(function(e) {
                e.preventDefault();
                const submitBtn = $('.addBtn');
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Registrando...');

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('admin.departamentos.registrar') }}",
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
                                '<i class="ri-save-line me-1"></i> Registrar Departamento');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar el departamento.';
                        if (xhr.responseJSON?.errors?.nombre) {
                            errorMsg = xhr.responseJSON.errors.nombre[0];
                        } else if (xhr.responseJSON?.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        showNotification('error', errorMsg);
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-save-line me-1"></i> Registrar Departamento');
                    }
                });
            });

            // Editar departamento
            $(document).on('click', '.editBtn', function() {
                var data = $(this).data('bs-obj');
                $('#departamentoId').val(data.id);
                $('#nombre_edicion').val(data.nombre);
                $('#feedback_edicion').removeClass('text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
            });

            // Validación en tiempo real para el campo de EDICIÓN
            $('#nombre_edicion').on('input', function() {
                const nombre = $(this).val().trim();
                const feedback = $('#feedback_edicion');
                const submitBtn = $('.updateBtn');
                const id = $('#departamentoId').val();

                feedback.removeClass('text-success text-danger').text('');

                if (nombre.length === 0) {
                    submitBtn.prop('disabled', true);
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.departamentos.verificaredicion') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre,
                            id: id
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').text(
                                    '⚠️ Este departamento ya está registrado.');
                                submitBtn.prop('disabled', true);
                            } else {
                                feedback.addClass('text-success').text(
                                    '✅ Nombre disponible.');
                                submitBtn.prop('disabled', false);
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').text(
                                '❌ Error al verificar el nombre.');
                            submitBtn.prop('disabled', true);
                        }
                    });
                }, 300);
            });

            // Actualizar departamento
            $('#updateForm').submit(function(e) {
                e.preventDefault();
                const submitBtn = $('.updateBtn');
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Actualizando...');

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('admin.departamentos.modificar') }}",
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
                                '<i class="ri-save-line me-1"></i> Actualizar Departamento');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al actualizar el departamento.';
                        if (xhr.responseJSON?.errors?.nombre) {
                            errorMsg = xhr.responseJSON.errors.nombre[0];
                        } else if (xhr.responseJSON?.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        showNotification('error', errorMsg);
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-save-line me-1"></i> Actualizar Departamento');
                    }
                });
            });

            // Eliminar departamento
            $(document).on('click', '.deleteBtn', function() {
                var data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);

                $('#warning-ciudades').hide();
                $('.btnDelete').prop('disabled', false).removeClass('disabled');

                // Verificar si el departamento tiene ciudades
                $.ajax({
                    url: "{{ route('admin.departamentos.ver', ':id') }}".replace(':id', data.id),
                    type: "GET",
                    success: function(response) {
                        const tieneCiudades = response.ciudades && response.ciudades.length > 0;
                        const warningElement = $('#warning-ciudades');

                        if (tieneCiudades) {
                            warningElement.show();
                            $('.btnDelete').prop('disabled', true).addClass('disabled');
                            warningElement.html(
                                `<i class="ri-alert-line me-1"></i> <strong>Advertencia:</strong> Este departamento tiene ${response.ciudades.length} ciudad${response.ciudades.length > 1 ? 'es' : ''} asociadas. Deberá eliminar primero todas sus ciudades para poder eliminar este departamento.`
                            );
                        } else {
                            warningElement.hide();
                            $('.btnDelete').prop('disabled', false).removeClass('disabled');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error al verificar ciudades:', xhr);
                        $('#warning-ciudades').hide();
                        $('.btnDelete').prop('disabled', false).removeClass('disabled');
                    }
                });
            });

            // Confirmar eliminación de departamento
            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                const submitBtn = $('.btnDelete');
                const departamentoId = $('#eliminarId').val();

                if (!departamentoId || departamentoId <= 0) {
                    showNotification('error', 'ID de departamento no válido');
                    return;
                }

                if (submitBtn.prop('disabled')) {
                    showNotification('error',
                        'No se puede eliminar el departamento porque tiene ciudades asociadas.');
                    return;
                }

                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Eliminando...');

                var formData = new FormData();
                formData.append('id', departamentoId);
                formData.append('_token', "{{ csrf_token() }}");
                formData.append('_method', 'DELETE');

                $.ajax({
                    url: "{{ route('admin.departamentos.eliminar') }}",
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
                                'No se pudo eliminar el departamento');
                            submitBtn.prop('disabled', false).html(
                                '<i class="ri-delete-bin-line me-1"></i> Sí, Eliminar');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al eliminar el departamento.';
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.msg) {
                                errorMsg = xhr.responseJSON.msg;
                            } else if (xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.errors) {
                                const firstError = Object.values(xhr.responseJSON.errors)[0];
                                errorMsg = Array.isArray(firstError) ? firstError[0] :
                                    firstError;
                            }
                        } else if (xhr.status === 403) {
                            errorMsg = 'No tienes permisos para realizar esta acción.';
                        } else if (xhr.status === 404) {
                            errorMsg = 'El departamento no existe o ya ha sido eliminado.';
                        } else if (xhr.status === 419) {
                            errorMsg =
                                'Sesión expirada. Por favor, recargue la página e intente nuevamente.';
                        } else if (xhr.status === 500) {
                            errorMsg = 'Error interno del servidor. Contacte al administrador.';
                        }

                        console.error('Error eliminando departamento:', {
                            status: xhr.status,
                            response: xhr.responseJSON,
                            errorThrown: xhr.statusText
                        });

                        showNotification('error', errorMsg);
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-delete-bin-line me-1"></i> Sí, Eliminar');
                    }
                });
            });

            // CIUDADES
            // Abrir modal para agregar ciudad
            $(document).on('click', '.addCiudadBtn', function() {
                const departamentoId = $(this).data('departamento-id');
                $('#departamento_id').val(departamentoId);
                $('#formAgregarCiudad')[0].reset();
                $('#feedback_nombre_ciudad').text('');
                $('#btnRegistrarCiudad').prop('disabled', true);
            });

            // Abrir modal para editar ciudad
            $(document).on('click', '.editCiudadBtn', function(e) {
                e.preventDefault();
                const data = $(this).data('bs-obj');
                $('#ciudad_id_edicion').val(data.id);
                $('#departamento_id_edicion').val(data.departamento_id);
                $('#nombre_ciudad_edicion').val(data.nombre);
                $('#feedback_nombre_ciudad_edicion').text('');
                $('#btnEditarCiudad').prop('disabled', false);
            });

            // Abrir modal para eliminar ciudad
            $(document).on('click', '.deleteCiudadBtn', function(e) {
                e.preventDefault();
                const data = $(this).data('bs-obj');
                $('#eliminarCiudadId').val(data.id);
            });

            let debounceTimerCiudad;

            // Validación en tiempo real del nombre (único por departamento) para REGISTRO
            $('#nombre_ciudad').on('input', function() {
                validarCiudadRegistro();
            });

            // Validación en tiempo real del nombre (único por departamento) para EDICIÓN
            $('#nombre_ciudad_edicion').on('input', function() {
                validarCiudadEdicion();
            });

            function validarCiudadRegistro() {
                const nombre = $('#nombre_ciudad').val().trim();
                const departamentoId = $('#departamento_id').val();
                const feedback = $('#feedback_nombre_ciudad');
                const btn = $('#btnRegistrarCiudad');

                feedback.removeClass('text-success text-danger').text('');

                if (!nombre) {
                    feedback.addClass('text-danger').text('El nombre es obligatorio.');
                    btn.prop('disabled', true);
                    return;
                }

                clearTimeout(debounceTimerCiudad);
                debounceTimerCiudad = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.ciudades.verificar') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre,
                            departamento_id: departamentoId
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').text(
                                    '⚠️ Ya existe una ciudad con este nombre en este departamento.'
                                );
                                btn.prop('disabled', true);
                            } else {
                                feedback.addClass('text-success').text('✅ Nombre disponible.');
                                btn.prop('disabled', false);
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').text(
                                '❌ Error al verificar el nombre.');
                            btn.prop('disabled', true);
                        }
                    });
                }, 300);
            }

            function validarCiudadEdicion() {
                const nombre = $('#nombre_ciudad_edicion').val().trim();
                const departamentoId = $('#departamento_id_edicion').val();
                const ciudadId = $('#ciudad_id_edicion').val();
                const feedback = $('#feedback_nombre_ciudad_edicion');
                const btn = $('#btnEditarCiudad');

                feedback.removeClass('text-success text-danger').text('');

                if (!nombre) {
                    feedback.addClass('text-danger').text('El nombre es obligatorio.');
                    btn.prop('disabled', true);
                    return;
                }

                clearTimeout(debounceTimerCiudad);
                debounceTimerCiudad = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.ciudades.verificaredicion') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre,
                            departamento_id: departamentoId,
                            id: ciudadId
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').text(
                                    '⚠️ Ya existe una ciudad con este nombre en este departamento.'
                                );
                                btn.prop('disabled', true);
                            } else {
                                feedback.addClass('text-success').text('✅ Nombre disponible.');
                                btn.prop('disabled', false);
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').text(
                                '❌ Error al verificar el nombre.');
                            btn.prop('disabled', true);
                        }
                    });
                }, 300);
            }

            // Envío del formulario de REGISTRO de ciudad
            $('#formAgregarCiudad').submit(function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                const btn = $('#btnRegistrarCiudad');

                btn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Registrando...');

                $.ajax({
                    url: "{{ route('admin.ciudades.registrar') }}",
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        if (res.success) {
                            showNotification('success', res.msg);
                            $('#modalAgregarCiudad').modal('hide');
                            loadResults($('#searchInput').val().trim());
                        } else {
                            showNotification('error', res.msg);
                            btn.prop('disabled', false).html(
                                '<i class="ri-save-line me-1"></i> Registrar Ciudad');
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors || {};
                        if (errors.nombre) {
                            $('#feedback_nombre_ciudad').addClass('text-danger').text(errors
                                .nombre[0]);
                        }
                        btn.prop('disabled', false).html(
                            '<i class="ri-save-line me-1"></i> Registrar Ciudad');
                    }
                });
            });

            // Envío del formulario de EDICIÓN de ciudad
            $('#formEditarCiudad').submit(function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                const btn = $('#btnEditarCiudad');

                btn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Actualizando...');

                $.ajax({
                    url: "{{ route('admin.ciudades.modificar') }}",
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        if (res.success) {
                            showNotification('success', res.msg);
                            $('#modalEditarCiudad').modal('hide');
                            loadResults($('#searchInput').val().trim());
                        } else {
                            showNotification('error', res.msg);
                            btn.prop('disabled', false).html(
                                '<i class="ri-save-line me-1"></i> Actualizar Ciudad');
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors || {};
                        if (errors.nombre) {
                            $('#feedback_nombre_ciudad_edicion').addClass('text-danger').text(
                                errors.nombre[0]);
                        }
                        btn.prop('disabled', false).html(
                            '<i class="ri-save-line me-1"></i> Actualizar Ciudad');
                    }
                });
            });

            // Confirmar eliminación de ciudad
            $('#deleteCiudadForm').submit(function(e) {
                e.preventDefault();
                const submitBtn = $('.btnDeleteCiudad');
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Eliminando...');

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('admin.ciudades.eliminar') }}",
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        if (res.success) {
                            showNotification('success', res.msg);
                            $('#modalEliminarCiudad').modal('hide');
                            loadResults($('#searchInput').val().trim());
                        } else {
                            showNotification('error', res.msg);
                            submitBtn.prop('disabled', false).html(
                                '<i class="ri-delete-bin-line me-1"></i> Sí, Eliminar');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al eliminar la ciudad.';
                        if (xhr.responseJSON?.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        showNotification('error', errorMsg);
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-delete-bin-line me-1"></i> Sí, Eliminar');
                    }
                });
            });

            // Función para mostrar notificaciones
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
                    <div class="toast-body">
                        ${message}
                    </div>
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

            // Función para cargar resultados con filtros
            function loadResults(search = '') {
                $.ajax({
                    url: '{{ route('admin.departamentos.listar') }}',
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

            // Función para inicializar tooltips
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

            // Búsqueda por texto
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

            // Manejar clics en la paginación
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

            // Inicializar tooltips al cargar la página
            initTooltips();

            // Inicializar tooltips después de que se muestren modales
            $(document).on('shown.bs.modal', '.modal', function() {
                initTooltips();
            });

            // Manejo de errores para AJAX
            $(document).ajaxError(function(event, jqxhr, settings, exception) {
                console.error("AJAX Error:", {
                    url: settings.url,
                    type: settings.type,
                    data: settings.data,
                    status: jqxhr.status,
                    error: exception,
                    response: jqxhr.responseText
                });
                showNotification('error',
                    'Error de comunicación con el servidor. Por favor, inténtalo de nuevo.');
            });
        });
    </script>
@endpush
