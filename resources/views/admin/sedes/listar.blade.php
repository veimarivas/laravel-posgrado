@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Gestión de Sedes</h4>
                    @if (Auth::guard('web')->user()->can('sedes.registrar'))
                        <div class="page-title-right">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrar">
                                <i class="ri-add-line align-middle me-1"></i> Registrar Sede
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
                                        placeholder="Buscar sede...">
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
                        <h5 class="card-title mb-0">Lista de Sedes Registradas</h5>
                    </div>
                    <div class="card-body">
                        <div id="results-container">
                            <div class="table-responsive">
                                <table class="table table-hover table-centered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">ID</th>
                                            <th>Nombre de la Sede</th>
                                            <th width="35%">Sucursales</th>
                                            <th width="15%" class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    @include('admin.sedes.partials.table-body')
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Mostrando <span id="showing-count">{{ $sedes->firstItem() ?? 0 }}</span>
                                    a <span id="to-count">{{ $sedes->lastItem() ?? 0 }}</span>
                                    de <span id="total-count">{{ $sedes->total() ?? 0 }}</span> registros
                                </div>
                                <div id="pagination-container">
                                    {{ $sedes->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Registrar Sede -->
    <div class="modal fade" id="registrar" tabindex="-1" aria-labelledby="registrarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary bg-soft">
                    <h5 class="modal-title text-white" id="registrarLabel">
                        <i class="ri-add-line me-2"></i>Registrar Nueva Sede
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
                                    <label class="form-label">Nombre de la Sede <span class="text-danger">*</span></label>
                                    <input type="text" id="nombre_registro" name="nombre" class="form-control"
                                        placeholder="Ej: Sede Central" required>
                                    <div id="feedback_registro" class="form-text"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="addForm" class="btn btn-primary addBtn" disabled>
                        <i class="ri-save-line me-1"></i> Registrar Sede
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Modificar Sede -->
    <!-- Modal Modificar Sede -->
    <div class="modal fade" id="modalModificar" tabindex="-1" aria-labelledby="modalModificarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning bg-soft">
                    <h5 class="modal-title" id="modalModificarLabel">
                        <i class="ri-edit-line me-2"></i>Modificar Sede
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateForm" class="forms-sample">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="sedeId">
                        <!-- Añadir este campo oculto -->
                        <input type="hidden" name="ciudade_id" id="ciudade_id_edicion">
                        <div class="mb-3">
                            <label for="nombre_edicion" class="form-label">Nombre de la Sede <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre_edicion" name="nombre" required>
                            <div id="feedback_edicion" class="form-text"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="updateForm" class="btn btn-warning updateBtn" disabled>
                        <i class="ri-save-line me-1"></i> Actualizar Sede
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Eliminar Sede -->
    <!-- Modal Eliminar Sede -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger bg-soft">
                    <h5 class="modal-title text-white" id="modalEliminarLabel">
                        <i class="ri-delete-bin-line me-2"></i>Eliminar Sede
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
                            <h5>¿Está seguro de eliminar esta sede?</h5>
                            <p class="text-muted">Esta acción no se puede deshacer y puede afectar a las sucursales
                                asociadas a esta sede.</p>
                            <div class="alert alert-warning mt-3" id="warning-sucursales" style="display:none;">
                                <i class="ri-alert-line me-1"></i> <strong>Advertencia:</strong> Esta sede tiene sucursales
                                asociadas.
                                Deberá eliminar primero todas sus sucursales para poder eliminar esta sede.
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
    <!-- Modal Agregar Sucursal -->
    <div class="modal fade" id="modalAgregarSucursal" tabindex="-1" aria-labelledby="modalAgregarSucursalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success bg-soft">
                    <h5 class="modal-title text-white" id="modalAgregarSucursalLabel">
                        <i class="ri-add-line me-2"></i>Registrar Sucursal
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarSucursal" class="forms-sample">
                        @csrf
                        <input type="hidden" id="sede_id" name="sede_id">
                        <div class="mb-3">
                            <label for="nombre_sucursal" class="form-label">Nombre de la sucursal <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre_sucursal" name="nombre" required>
                            <div id="feedback_nombre_sucursal" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="direccion_sucursal" class="form-label">Dirección <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="direccion_sucursal" name="direccion"
                                required>
                            <div id="feedback_direccion_sucursal" class="form-text"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="latitud_sucursal" class="form-label">Latitud</label>
                                    <input type="number" step="any" class="form-control" id="latitud_sucursal"
                                        name="latitud" placeholder="Ej: -17.789">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="longitud_sucursal" class="form-label">Longitud</label>
                                    <input type="number" step="any" class="form-control" id="longitud_sucursal"
                                        name="longitud" placeholder="Ej: -63.157">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="color_sucursal" class="form-label">Color <span
                                    class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" class="form-control form-control-color" id="color_sucursal"
                                    name="color" value="#0d6efd" title="Elige un color">
                                <span id="colorPreview" class="d-inline-block"
                                    style="width: 24px; height: 24px; border: 1px solid #ddd; border-radius: 4px;"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formAgregarSucursal" class="btn btn-success" id="btnRegistrarSucursal"
                        disabled>
                        <i class="ri-save-line me-1"></i> Registrar Sucursal
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Editar Sucursal -->
    <div class="modal fade" id="modalEditarSucursal" tabindex="-1" aria-labelledby="modalEditarSucursalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning bg-soft">
                    <h5 class="modal-title" id="modalEditarSucursalLabel">
                        <i class="ri-edit-line me-2"></i>Editar Sucursal
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarSucursal" class="forms-sample">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="sucursal_id_edicion" name="id">
                        <input type="hidden" id="sede_id_edicion" name="sede_id">
                        <div class="mb-3">
                            <label for="nombre_sucursal_edicion" class="form-label">Nombre de la sucursal <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre_sucursal_edicion" name="nombre"
                                required>
                            <div id="feedback_nombre_sucursal_edicion" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="direccion_sucursal_edicion" class="form-label">Dirección <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="direccion_sucursal_edicion" name="direccion"
                                required>
                            <div id="feedback_direccion_sucursal_edicion" class="form-text"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="latitud_sucursal_edicion" class="form-label">Latitud</label>
                                    <input type="number" step="any" class="form-control"
                                        id="latitud_sucursal_edicion" name="latitud" placeholder="Ej: -17.789">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="longitud_sucursal_edicion" class="form-label">Longitud</label>
                                    <input type="number" step="any" class="form-control"
                                        id="longitud_sucursal_edicion" name="longitud" placeholder="Ej: -63.157">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="color_sucursal_edicion" class="form-label">Color <span
                                    class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" class="form-control form-control-color" id="color_sucursal_edicion"
                                    name="color" value="#0d6efd" title="Elige un color">
                                <span id="colorPreviewEdicion" class="d-inline-block"
                                    style="width: 24px; height: 24px; border: 1px solid #ddd; border-radius: 4px;"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formEditarSucursal" class="btn btn-warning" id="btnEditarSucursal"
                        disabled>
                        <i class="ri-save-line me-1"></i> Actualizar Sucursal
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Eliminar Sucursal -->
    <div class="modal fade" id="modalEliminarSucursal" tabindex="-1" aria-labelledby="modalEliminarSucursalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger bg-soft">
                    <h5 class="modal-title text-white" id="modalEliminarSucursalLabel">
                        <i class="ri-delete-bin-line me-2"></i>Eliminar Sucursal
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="deleteSucursalForm" class="forms-sample">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" id="eliminarSucursalId">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-4">
                                <div class="avatar-title bg-danger bg-soft text-danger rounded-circle">
                                    <i class="ri-error-warning-line fs-2"></i>
                                </div>
                            </div>
                            <h5>¿Está seguro de eliminar esta sucursal?</h5>
                            <p class="text-muted">Esta acción no se puede deshacer.</p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="deleteSucursalForm" class="btn btn-danger btnDeleteSucursal">
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

        .sucursales-container {
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

        .sucursales-container::-webkit-scrollbar {
            width: 6px;
        }

        .sucursales-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .sucursales-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .sucursales-container::-webkit-scrollbar-thumb:hover {
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
                $('.addBtn').prop('disabled', true).html('<i class="ri-save-line me-1"></i> Registrar Sede');
            }

            // Evento cuando se cierra el modal de registro
            $('#registrar').on('hidden.bs.modal', function() {
                resetAddForm();
            });

            // Verifica si el nombre de la sede está en la BD (Registro)
            $('#nombre_registro').on('input', function() {
                const nombre = $(this).val().trim();
                const feedback = $('#feedback_registro');
                const submitBtn = $('.addBtn');

                // Limpiar el mensaje previo
                feedback.removeClass('text-success text-danger').text('');

                if (nombre.length === 0) {
                    submitBtn.prop('disabled', true);
                    return;
                }

                // Cancelar la petición anterior si el usuario sigue escribiendo
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.sedes.verificar') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').text(
                                    '⚠️ La sede ya está registrada.');
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

            // Registro de sede
            $('#addForm').submit(function(e) {
                e.preventDefault();
                const submitBtn = $('.addBtn');
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Registrando...');

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('admin.sedes.registrar') }}",
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
                                '<i class="ri-save-line me-1"></i> Registrar Sede');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar la sede.';
                        if (xhr.responseJSON?.errors?.nombre) {
                            errorMsg = xhr.responseJSON.errors.nombre[0];
                        } else if (xhr.responseJSON?.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        showNotification('error', errorMsg);
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-save-line me-1"></i> Registrar Sede');
                    }
                });
            });

            // Editar sede - delegamos el evento ya que los botones se cargan dinámicamente
            $(document).on('click', '.editBtn', function() {
                var data = $(this).data('bs-obj');
                console.log('Datos sede para editar:', data);
                $('#sedeId').val(data.id);
                $('#nombre_edicion').val(data.nombre);
                // Añadir esta línea para establecer el ciudade_id
                $('#ciudade_id_edicion').val(data.ciudade_id);
                // Resetear feedback y habilitar botón
                $('#feedback_edicion').removeClass('text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
            });

            // Validación en tiempo real para el campo de EDICIÓN
            $('#nombre_edicion').on('input', function() {
                const nombre = $(this).val().trim();
                const feedback = $('#feedback_edicion');
                const submitBtn = $('.updateBtn');
                const id = $('#sedeId').val();

                // Limpiar el mensaje previo
                feedback.removeClass('text-success text-danger').text('');

                if (nombre.length === 0) {
                    submitBtn.prop('disabled', true);
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.sedes.verificaredicion') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre,
                            id: id
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').text(
                                    '⚠️ Esta sede ya está registrada.');
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

            // Actualizar sede
            $('#updateForm').submit(function(e) {
                e.preventDefault();
                const submitBtn = $('.updateBtn');
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Actualizando...');

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('admin.sedes.modificar') }}",
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
                                '<i class="ri-save-line me-1"></i> Actualizar Sede');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al actualizar la sede.';
                        if (xhr.responseJSON?.errors?.nombre) {
                            errorMsg = xhr.responseJSON.errors.nombre[0];
                        } else if (xhr.responseJSON?.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        showNotification('error', errorMsg);
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-save-line me-1"></i> Actualizar Sede');
                    }
                });
            });

            // Eliminar sede - delegamos el evento
            // Eliminar sede - delegamos el evento
            $(document).on('click', '.deleteBtn', function() {
                var data = $(this).data('bs-obj');
                console.log('Datos sede para eliminar:', data);
                $('#eliminarId').val(data.id);

                // Resetear estado del modal
                $('#warning-sucursales').hide();
                $('.btnDelete').prop('disabled', false).removeClass('disabled');

                // Verificar si la sede tiene sucursales
                $.ajax({
                    url: "{{ route('admin.sedes.ver', ':id') }}".replace(':id', data.id),
                    type: "GET",
                    success: function(response) {
                        const tieneSucursales = response.sucursales && response.sucursales
                            .length > 0;
                        const warningElement = $('#warning-sucursales');

                        if (tieneSucursales) {
                            warningElement.show();
                            $('.btnDelete').prop('disabled', true).addClass('disabled');
                            warningElement.html(
                                `<i class="ri-alert-line me-1"></i> <strong>Advertencia:</strong> Esta sede tiene ${response.sucursales.length} sucursal${response.sucursales.length > 1 ? 'es' : ''} asociadas. Deberá eliminar primero todas sus sucursales para poder eliminar esta sede.`
                            );
                        } else {
                            warningElement.hide();
                            $('.btnDelete').prop('disabled', false).removeClass('disabled');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error al verificar sucursales:', xhr);
                        // Si hay error en la verificación, permitir intentar eliminar
                        $('#warning-sucursales').hide();
                        $('.btnDelete').prop('disabled', false).removeClass('disabled');
                    }
                });
            });

            // Confirmar eliminación de sede - VERSIÓN CORREGIDA
            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                const submitBtn = $('.btnDelete');
                const sedeId = $('#eliminarId').val();

                // Validación final de seguridad
                if (!sedeId || sedeId <= 0) {
                    showNotification('error', 'ID de sede no válido');
                    return;
                }

                // Si el botón está deshabilitado (tiene sucursales), no proceder
                if (submitBtn.prop('disabled')) {
                    showNotification('error',
                        'No se puede eliminar la sede porque tiene sucursales asociadas.');
                    return;
                }

                // Mostrar estado de eliminación
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Eliminando...');

                // Enviar la petición - USANDO FORM DATA EN LUGAR DE JSON
                var formData = new FormData();
                formData.append('id', sedeId);
                formData.append('_token', "{{ csrf_token() }}");
                formData.append('_method', 'DELETE');

                $.ajax({
                    url: "{{ route('admin.sedes.eliminar') }}",
                    type: "POST", // Usar POST para FormData
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            showNotification('success', res.msg);
                            $('#modalEliminar').modal('hide');
                            loadResults($('#searchInput').val().trim());
                        } else {
                            showNotification('error', res.msg || 'No se pudo eliminar la sede');
                            submitBtn.prop('disabled', false).html(
                                '<i class="ri-delete-bin-line me-1"></i> Sí, Eliminar');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al eliminar la sede.';

                        // Analizar el error con más detalle
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
                            errorMsg = 'La sede no existe o ya ha sido eliminada.';
                        } else if (xhr.status === 419) {
                            errorMsg =
                                'Sesión expirada. Por favor, recargue la página e intente nuevamente.';
                        } else if (xhr.status === 500) {
                            errorMsg = 'Error interno del servidor. Contacte al administrador.';
                        }

                        console.error('Error eliminando sede:', {
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

            // SUCURSALES
            // Abrir modal para agregar sucursal
            $(document).on('click', '.addSucursalBtn', function() {
                const sedeId = $(this).data('sede-id');
                console.log('Sede ID para sucursal:', sedeId);
                $('#sede_id').val(sedeId);
                $('#formAgregarSucursal')[0].reset();
                $('#feedback_nombre_sucursal, #feedback_direccion_sucursal').text('');
                $('#btnRegistrarSucursal').prop('disabled', true);
                // Inicializar preview de color
                $('#color_sucursal').val('#0d6efd'); // Color por defecto
                $('#colorPreview').css('background-color', '#0d6efd');
            });

            // Abrir modal para editar sucursal
            $(document).on('click', '.editSucursalBtn', function(e) {
                e.preventDefault();
                const data = $(this).data('bs-obj');
                console.log('Datos sucursal para editar:', data);

                $('#sucursal_id_edicion').val(data.id);
                $('#sede_id_edicion').val(data.sede_id);
                $('#nombre_sucursal_edicion').val(data.nombre);
                $('#direccion_sucursal_edicion').val(data.direccion || '');
                $('#latitud_sucursal_edicion').val(data.latitud || '');
                $('#longitud_sucursal_edicion').val(data.longitud || '');
                $('#color_sucursal_edicion').val(data.color || '#0d6efd');
                $('#colorPreviewEdicion').css('background-color', data.color || '#0d6efd');

                $('#feedback_nombre_sucursal_edicion, #feedback_direccion_sucursal_edicion').text('');
                $('#btnEditarSucursal').prop('disabled', false);
            });

            // Abrir modal para eliminar sucursal
            $(document).on('click', '.deleteSucursalBtn', function(e) {
                e.preventDefault();
                const data = $(this).data('bs-obj');
                console.log('Datos sucursal para eliminar:', data);
                $('#eliminarSucursalId').val(data.id);
            });

            let debounceTimerSucursal;

            // Validación en tiempo real del nombre (único por sede) para REGISTRO
            $('#nombre_sucursal').on('input', function() {
                validarSucursalRegistro();
            });

            // Validación en tiempo real del nombre (único por sede) para EDICIÓN
            $('#nombre_sucursal_edicion').on('input', function() {
                validarSucursalEdicion();
            });

            // Preview de color para registro
            $('#color_sucursal').on('input', function() {
                $('#colorPreview').css('background-color', $(this).val());
            });

            // Preview de color para edición
            $('#color_sucursal_edicion').on('input', function() {
                $('#colorPreviewEdicion').css('background-color', $(this).val());
            });

            function validarSucursalRegistro() {
                const nombre = $('#nombre_sucursal').val().trim();
                const sedeId = $('#sede_id').val();
                const feedback = $('#feedback_nombre_sucursal');
                const btn = $('#btnRegistrarSucursal');

                feedback.removeClass('text-success text-danger').text('');

                if (!nombre) {
                    feedback.addClass('text-danger').text('El nombre es obligatorio.');
                    btn.prop('disabled', true);
                    return;
                }

                clearTimeout(debounceTimerSucursal);
                debounceTimerSucursal = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.sucursales.verificar') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre,
                            sede_id: sedeId
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').text(
                                    '⚠️ Ya existe una sucursal con este nombre en esta sede.'
                                );
                                btn.prop('disabled', true);
                            } else {
                                feedback.addClass('text-success').text('✅ Nombre disponible.');
                                validarFormularioSucursalRegistro();
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

            function validarSucursalEdicion() {
                const nombre = $('#nombre_sucursal_edicion').val().trim();
                const sedeId = $('#sede_id_edicion').val();
                const sucursalId = $('#sucursal_id_edicion').val();
                const feedback = $('#feedback_nombre_sucursal_edicion');
                const btn = $('#btnEditarSucursal');

                feedback.removeClass('text-success text-danger').text('');

                if (!nombre) {
                    feedback.addClass('text-danger').text('El nombre es obligatorio.');
                    btn.prop('disabled', true);
                    return;
                }

                clearTimeout(debounceTimerSucursal);
                debounceTimerSucursal = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.sucursales.verificar') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre,
                            sede_id: sedeId,
                            id: sucursalId
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').text(
                                    '⚠️ Ya existe una sucursal con este nombre en esta sede.'
                                );
                                btn.prop('disabled', true);
                            } else {
                                feedback.addClass('text-success').text('✅ Nombre disponible.');
                                validarFormularioSucursalEdicion();
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

            // Validación inmediata de dirección para REGISTRO
            $('#direccion_sucursal').on('input', validarFormularioSucursalRegistro);

            // Validación inmediata de dirección para EDICIÓN
            $('#direccion_sucursal_edicion').on('input', validarFormularioSucursalEdicion);

            function validarFormularioSucursalRegistro() {
                const nombre = $('#nombre_sucursal').val().trim();
                const direccion = $('#direccion_sucursal').val().trim();
                const feedbackNombre = $('#feedback_nombre_sucursal');
                const feedbackDireccion = $('#feedback_direccion_sucursal');
                const btn = $('#btnRegistrarSucursal');

                let valido = true;

                if (!nombre) {
                    feedbackNombre.addClass('text-danger').text('El nombre es obligatorio.');
                    valido = false;
                } else if (feedbackNombre.text().includes('Ya existe')) {
                    valido = false;
                }

                if (!direccion) {
                    feedbackDireccion.addClass('text-danger').text('La dirección es obligatoria.');
                    valido = false;
                } else {
                    feedbackDireccion.text('');
                }

                btn.prop('disabled', !valido);
            }

            function validarFormularioSucursalEdicion() {
                const nombre = $('#nombre_sucursal_edicion').val().trim();
                const direccion = $('#direccion_sucursal_edicion').val().trim();
                const feedbackNombre = $('#feedback_nombre_sucursal_edicion');
                const feedbackDireccion = $('#feedback_direccion_sucursal_edicion');
                const btn = $('#btnEditarSucursal');

                let valido = true;

                if (!nombre) {
                    feedbackNombre.addClass('text-danger').text('El nombre es obligatorio.');
                    valido = false;
                } else if (feedbackNombre.text().includes('Ya existe')) {
                    valido = false;
                }

                if (!direccion) {
                    feedbackDireccion.addClass('text-danger').text('La dirección es obligatoria.');
                    valido = false;
                } else {
                    feedbackDireccion.text('');
                }

                btn.prop('disabled', !valido);
            }

            // Envío del formulario de REGISTRO de sucursal
            $('#formAgregarSucursal').submit(function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                const btn = $('#btnRegistrarSucursal');

                btn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Registrando...');

                $.ajax({
                    url: "{{ route('admin.sucursales.registrar') }}",
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        if (res.success) {
                            showNotification('success', res.msg);
                            $('#modalAgregarSucursal').modal('hide');
                            loadResults($('#searchInput').val().trim());
                        } else {
                            showNotification('error', res.msg);
                            btn.prop('disabled', false).html(
                                '<i class="ri-save-line me-1"></i> Registrar Sucursal');
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors || {};
                        if (errors.nombre) {
                            $('#feedback_nombre_sucursal').addClass('text-danger').text(errors
                                .nombre[0]);
                        }
                        if (errors.direccion) {
                            $('#feedback_direccion_sucursal').addClass('text-danger').text(
                                errors.direccion[0]);
                        }
                        if (errors.color) {
                            showNotification('error', 'Color inválido.');
                        }
                        btn.prop('disabled', false).html(
                            '<i class="ri-save-line me-1"></i> Registrar Sucursal');
                    }
                });
            });

            // Envío del formulario de EDICIÓN de sucursal
            $('#formEditarSucursal').submit(function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                const btn = $('#btnEditarSucursal');

                btn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Actualizando...');

                $.ajax({
                    url: "{{ route('admin.sucursales.modificar') }}",
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        if (res.success) {
                            showNotification('success', res.msg);
                            $('#modalEditarSucursal').modal('hide');
                            loadResults($('#searchInput').val().trim());
                        } else {
                            showNotification('error', res.msg);
                            btn.prop('disabled', false).html(
                                '<i class="ri-save-line me-1"></i> Actualizar Sucursal');
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors || {};
                        if (errors.nombre) {
                            $('#feedback_nombre_sucursal_edicion').addClass('text-danger').text(
                                errors.nombre[0]);
                        }
                        if (errors.direccion) {
                            $('#feedback_direccion_sucursal_edicion').addClass('text-danger')
                                .text(errors.direccion[0]);
                        }
                        btn.prop('disabled', false).html(
                            '<i class="ri-save-line me-1"></i> Actualizar Sucursal');
                    }
                });
            });

            // Confirmar eliminación de sucursal
            $('#deleteSucursalForm').submit(function(e) {
                e.preventDefault();
                const submitBtn = $('.btnDeleteSucursal');
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Eliminando...');

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('admin.sucursales.eliminar') }}",
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        if (res.success) {
                            showNotification('success', res.msg);
                            $('#modalEliminarSucursal').modal('hide');
                            loadResults($('#searchInput').val().trim());
                        } else {
                            showNotification('error', res.msg);
                            submitBtn.prop('disabled', false).html(
                                '<i class="ri-delete-bin-line me-1"></i> Sí, Eliminar');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al eliminar la sucursal.';
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

                // Remover el toast después de que se oculte
                toast.on('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }

            // Función para cargar resultados con filtros
            function loadResults(search = '') {
                $.ajax({
                    url: '{{ route('admin.sedes.listar') }}',
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Actualizar la tabla y paginación
                        $('#results-container .table-responsive table').find('tbody').replaceWith(
                            response.html);
                        $('#pagination-container').html(response.pagination);
                        // Actualizar contadores
                        if (response.total !== undefined) {
                            $('#showing-count').text(response.from || 0);
                            $('#to-count').text(response.to || 0);
                            $('#total-count').text(response.total || 0);
                        }
                        // Inicializar tooltips después de cargar contenido nuevo
                        initTooltips();
                    },
                    error: function() {
                        showNotification('error', 'Error al cargar los resultados');
                    }
                });
            }

            // Función para inicializar tooltips
            function initTooltips() {
                // Eliminar tooltips existentes
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipTriggerList.forEach(tooltipTriggerEl => {
                    if (tooltipTriggerEl._tooltip) {
                        tooltipTriggerEl._tooltip.dispose();
                    }
                });

                // Inicializar nuevos tooltips
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
                        // Actualizar contadores
                        if (response.total !== undefined) {
                            $('#showing-count').text(response.from || 0);
                            $('#to-count').text(response.to || 0);
                            $('#total-count').text(response.total || 0);
                        }
                        // Inicializar tooltips
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

            // Agregar este código después de la función initTooltips()
            function verificarSucursalesAntesEliminar(sedeId) {
                $.ajax({
                    url: "{{ route('admin.sedes.ver', ':id') }}".replace(':id', sedeId),
                    type: "GET",
                    success: function(response) {
                        const tieneSucursales = response.sucursales && response.sucursales.length > 0;
                        const warningElement = $('#warning-sucursales');

                        if (tieneSucursales) {
                            warningElement.show();
                            $('.btnDelete').prop('disabled', true).addClass('disabled');
                        } else {
                            warningElement.hide();
                            $('.btnDelete').prop('disabled', false).removeClass('disabled');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error al verificar sucursales:', xhr);
                        $('.btnDelete').prop('disabled', false).removeClass('disabled');
                    }
                });
            }



            // Agregar manejo de errores para AJAX
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
