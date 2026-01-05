@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-dark">Gestión de Universidades</h4>
                        <p class="text-muted mb-0">Administra las universidades disponibles</p>
                    </div>

                    @if (Auth::guard('web')->user()->can('universidades.registrar'))
                        <div class="page-title-right">
                            <button type="button" class="btn btn-primary btn-lg waves-effect waves-light"
                                data-bs-toggle="modal" data-bs-target="#registrar">
                                <i class="ri-add-line align-bottom me-1"></i> Nueva Universidad
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Filters & Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card border border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-primary bg-opacity-10">
                                    <i class="ri-building-line fs-24 text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Universidades</p>
                                <h4 class="mb-0" id="totalUniversidadesCounter">{{ $universidades->total() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="flex-grow-1">
                                <div class="search-box position-relative">
                                    <input type="text" id="searchInput" class="form-control search form-control-lg ps-5"
                                        placeholder="Buscar por nombre o sigla..." value="{{ request('search') ?? '' }}">
                                    <i
                                        class="ri-search-line search-icon position-absolute top-50 start-0 translate-middle-y text-muted ms-3"></i>
                                </div>
                            </div>
                            <div>
                                <button type="button" id="clearFilters" class="btn btn-outline-secondary btn-lg">
                                    <i class="ri-refresh-line align-bottom me-1"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Results Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border border-light shadow-sm">
                    <div class="card-header border-bottom-dashed d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fw-bold">Listado de Universidades</h5>
                        <div class="dropdown">
                            <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-equalizer-line align-bottom me-1"></i> Vista
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item active" href="#"><i
                                            class="ri-list-check align-bottom me-2"></i> Tabla</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ri-grid-line align-bottom me-2"></i>
                                        Cuadrícula</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                <thead class="table-light text-muted fw-semibold">
                                    <tr>
                                        <th class="px-3 py-3" width="5%">#</th>
                                        <th class="px-3 py-3">Nombre</th>
                                        <th class="px-3 py-3">Sigla</th>
                                        <th class="px-3 py-3 text-center" width="15%">Estado</th>
                                        <th class="px-3 py-3 text-end" width="15%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="universidadesTableBody">
                                    @include('admin.universidades.partials.table-body')
                                </tbody>
                            </table>
                        </div>
                        @if ($universidades->total() > 0)
                            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                                <div class="results-count text-muted">
                                    Mostrando <span class="fw-medium">{{ $universidades->firstItem() }}</span> a
                                    <span class="fw-medium">{{ $universidades->lastItem() }}</span> de
                                    <span class="fw-medium">{{ $universidades->total() }}</span> resultados
                                </div>
                                <div class="pagination-container">
                                    {{ $universidades->appends(request()->input())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar -->
    <div class="modal fade" id="registrar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="registrarLabel">
                        <i class="ri-add-line me-2 align-bottom"></i>Registrar Nueva Universidad
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="addForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="nombre_registro" class="form-label fw-medium">Nombre <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="nombre_registro" name="nombre"
                                        class="form-control form-control-lg"
                                        placeholder="Ej: Universidad Mayor de San Andrés" required>
                                    <div class="invalid-feedback">Por favor ingresa el nombre de la universidad</div>
                                    <small id="feedback_nombre_registro" class="form-text mt-1"></small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="sigla_registro" class="form-label fw-medium">Sigla <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="sigla_registro" name="sigla"
                                        class="form-control form-control-lg" placeholder="Ej: UMSA" required>
                                    <div class="invalid-feedback">Por favor ingresa la sigla</div>
                                    <small id="feedback_sigla_registro" class="form-text mt-1"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg addBtn" disabled>
                            <i class="ri-save-3-line me-1 align-bottom"></i>
                            <span class="submit-text">Registrar Universidad</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Modificar -->
    <div class="modal fade" id="modalModificar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-warning-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-warning" id="modalModificarLabel">
                        <i class="ri-edit-line me-2 align-bottom"></i>Editar Universidad
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="universidadId">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="nombre_edicion" class="form-label fw-medium">Nombre <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" id="nombre_edicion"
                                        name="nombre" required>
                                    <div class="invalid-feedback">Por favor ingresa el nombre de la universidad</div>
                                    <small id="feedback_nombre_edicion" class="form-text mt-1"></small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="sigla_edicion" class="form-label fw-medium">Sigla <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" id="sigla_edicion"
                                        name="sigla" required>
                                    <div class="invalid-feedback">Por favor ingresa la sigla</div>
                                    <small id="feedback_sigla_edicion" class="form-text mt-1"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning btn-lg updateBtn" disabled>
                            <i class="ri-refresh-line me-1 align-bottom"></i>
                            <span class="submit-text">Actualizar Universidad</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-danger" id="modalEliminarLabel">
                        <i class="ri-delete-bin-line me-2 align-bottom"></i>Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="eliminarId">
                    <div class="modal-body p-4 text-center">
                        <div class="mb-4">
                            <div class="avatar-xl mx-auto">
                                <div class="avatar-title bg-danger bg-opacity-10 text-danger rounded-circle fs-2xl">
                                    <i class="ri-alert-line"></i>
                                </div>
                            </div>
                        </div>
                        <h4 class="mb-3 fw-bold">¿Estás seguro de eliminar esta universidad?</h4>
                        <p class="text-muted mb-0">Esta acción no se puede deshacer. Si hay estudios asociados a esta
                            universidad, deberás actualizarlos manualmente.</p>
                        <div class="mt-3 p-3 bg-light rounded">
                            <small class="text-danger fw-medium">Advertencia:</small>
                            <small class="text-muted">Se recomienda actualizar los estudios asociados antes de eliminar
                                esta universidad.</small>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger btn-lg btnDelete">
                            <i class="ri-delete-bin-line me-1 align-bottom"></i>
                            <span class="submit-text">Sí, Eliminar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        /* Responsive Table */
        .table-nowrap td,
        .table-nowrap th {
            white-space: nowrap;
        }

        /* Action buttons */
        .btn-action {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .btn-action i {
            font-size: 1rem;
            line-height: 1;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Empty state */
        .empty-state {
            padding: 3rem 1.5rem;
            text-align: center;
        }

        .empty-icon {
            font-size: 4rem;
            color: #adb5bd;
            margin-bottom: 1rem;
        }

        /* Pagination */
        .pagination {
            --bs-pagination-border-radius: 6px;
            --bs-pagination-hover-bg: var(--bs-primary);
            --bs-pagination-hover-color: #fff;
        }

        /* Mobile optimization */
        @media (max-width: 767.98px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 10px;
            }

            .page-title-box {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 15px;
            }

            .d-flex.justify-content-between.align-items-center {
                flex-direction: column;
                gap: 12px;
            }

            .search-box {
                width: 100%;
            }

            .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .btn-group .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        /* Loading spinner */
        .btn .spin {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Toast notifications - Posición superior derecha */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999999;
        }

        .toast {
            min-width: 300px;
            max-width: 350px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 10px;
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Asegurar que los toasts estén por encima de los modales */
        .toast-container {
            z-index: 999999 !important;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            let debounceTimer;
            let isProcessing = false;

            // Inicializar tooltips
            $('[data-bs-toggle="tooltip"]').each(function() {
                new bootstrap.Tooltip(this);
            });

            // Resetear formularios cuando se cierran los modales
            $('#registrar, #modalModificar, #modalEliminar').on('hidden.bs.modal', function() {
                if (this.id === 'registrar') {
                    $('#addForm')[0].reset();
                    $('#feedback_nombre_registro, #feedback_sigla_registro').removeClass(
                        'text-success text-danger').text('');
                    $('.addBtn').prop('disabled', true);
                    $('#addForm').removeClass('was-validated');
                } else if (this.id === 'modalModificar') {
                    $('#updateForm')[0].reset();
                    $('#feedback_nombre_edicion, #feedback_sigla_edicion').removeClass(
                        'text-success text-danger').text('');
                    $('.updateBtn').prop('disabled', true);
                    $('#updateForm').removeClass('was-validated');
                } else if (this.id === 'modalEliminar') {
                    $('#deleteForm')[0].reset();
                }
            });

            // Verificar nombre y sigla al registrar
            $('#nombre_registro, #sigla_registro').on('input', function() {
                const nombre = $('#nombre_registro').val().trim();
                const sigla = $('#sigla_registro').val().trim();
                const feedbackNombre = $('#feedback_nombre_registro');
                const feedbackSigla = $('#feedback_sigla_registro');
                const submitBtn = $('.addBtn');
                const inputNombre = $('#nombre_registro');
                const inputSigla = $('#sigla_registro');

                // Validación básica
                let nombreValido = false;
                let siglaValido = false;

                if (nombre.length === 0) {
                    feedbackNombre.removeClass('text-success text-danger').text('');
                    inputNombre.removeClass('is-valid is-invalid');
                } else if (nombre.length < 3) {
                    feedbackNombre.addClass('text-danger').text(
                        'El nombre debe tener al menos 3 caracteres');
                    inputNombre.addClass('is-invalid').removeClass('is-valid');
                } else {
                    nombreValido = true;
                }

                if (sigla.length === 0) {
                    feedbackSigla.removeClass('text-success text-danger').text('');
                    inputSigla.removeClass('is-valid is-invalid');
                } else if (sigla.length < 2) {
                    feedbackSigla.addClass('text-danger').text('La sigla debe tener al menos 2 caracteres');
                    inputSigla.addClass('is-invalid').removeClass('is-valid');
                } else {
                    siglaValido = true;
                }

                if (!nombreValido || !siglaValido) {
                    submitBtn.prop('disabled', true);
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.universidades.verificar') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre,
                            sigla: sigla
                        },
                        beforeSend: function() {
                            feedbackNombre.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                            );
                            feedbackSigla.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                            );
                        },
                        success: function(res) {
                            if (res.nombre_exists) {
                                feedbackNombre.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Este nombre ya existe'
                                );
                                inputNombre.addClass('is-invalid').removeClass(
                                    'is-valid');
                                nombreValido = false;
                            } else {
                                feedbackNombre.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Nombre disponible'
                                );
                                inputNombre.addClass('is-valid').removeClass(
                                    'is-invalid');
                                nombreValido = true;
                            }

                            if (res.sigla_exists) {
                                feedbackSigla.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Esta sigla ya existe'
                                );
                                inputSigla.addClass('is-invalid').removeClass(
                                    'is-valid');
                                siglaValido = false;
                            } else {
                                feedbackSigla.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Sigla disponible'
                                );
                                inputSigla.addClass('is-valid').removeClass(
                                    'is-invalid');
                                siglaValido = true;
                            }

                            submitBtn.prop('disabled', !(nombreValido && siglaValido));
                        },
                        error: function() {
                            feedbackNombre.addClass('text-danger').html(
                                '<i class="ri-alert-line me-1"></i> Error al verificar el nombre'
                            );
                            feedbackSigla.addClass('text-danger').html(
                                '<i class="ri-alert-line me-1"></i> Error al verificar la sigla'
                            );
                            submitBtn.prop('disabled', true);
                            inputNombre.addClass('is-invalid').removeClass('is-valid');
                            inputSigla.addClass('is-invalid').removeClass('is-valid');
                        }
                    });
                }, 500);
            });

            // Verificar nombre y sigla al editar
            $('#nombre_edicion, #sigla_edicion').on('input', function() {
                const nombre = $('#nombre_edicion').val().trim();
                const sigla = $('#sigla_edicion').val().trim();
                const feedbackNombre = $('#feedback_nombre_edicion');
                const feedbackSigla = $('#feedback_sigla_edicion');
                const submitBtn = $('.updateBtn');
                const inputNombre = $('#nombre_edicion');
                const inputSigla = $('#sigla_edicion');
                const id = $('#universidadId').val();

                // Validación básica
                let nombreValido = false;
                let siglaValido = false;

                if (nombre.length === 0) {
                    feedbackNombre.removeClass('text-success text-danger').text('');
                    inputNombre.removeClass('is-valid is-invalid');
                } else if (nombre.length < 3) {
                    feedbackNombre.addClass('text-danger').text(
                        'El nombre debe tener al menos 3 caracteres');
                    inputNombre.addClass('is-invalid').removeClass('is-valid');
                } else {
                    nombreValido = true;
                }

                if (sigla.length === 0) {
                    feedbackSigla.removeClass('text-success text-danger').text('');
                    inputSigla.removeClass('is-valid is-invalid');
                } else if (sigla.length < 2) {
                    feedbackSigla.addClass('text-danger').text('La sigla debe tener al menos 2 caracteres');
                    inputSigla.addClass('is-invalid').removeClass('is-valid');
                } else {
                    siglaValido = true;
                }

                if (!nombreValido || !siglaValido) {
                    submitBtn.prop('disabled', true);
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.universidades.verificar-edicion') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre,
                            sigla: sigla,
                            id: id
                        },
                        beforeSend: function() {
                            feedbackNombre.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                            );
                            feedbackSigla.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                            );
                        },
                        success: function(res) {
                            if (res.nombre_exists) {
                                feedbackNombre.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Este nombre ya existe'
                                );
                                inputNombre.addClass('is-invalid').removeClass(
                                    'is-valid');
                                nombreValido = false;
                            } else {
                                feedbackNombre.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Nombre disponible'
                                );
                                inputNombre.addClass('is-valid').removeClass(
                                    'is-invalid');
                                nombreValido = true;
                            }

                            if (res.sigla_exists) {
                                feedbackSigla.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Esta sigla ya existe'
                                );
                                inputSigla.addClass('is-invalid').removeClass(
                                    'is-valid');
                                siglaValido = false;
                            } else {
                                feedbackSigla.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Sigla disponible'
                                );
                                inputSigla.addClass('is-valid').removeClass(
                                    'is-invalid');
                                siglaValido = true;
                            }

                            submitBtn.prop('disabled', !(nombreValido && siglaValido));
                        },
                        error: function() {
                            feedbackNombre.addClass('text-danger').html(
                                '<i class="ri-alert-line me-1"></i> Error al verificar el nombre'
                            );
                            feedbackSigla.addClass('text-danger').html(
                                '<i class="ri-alert-line me-1"></i> Error al verificar la sigla'
                            );
                            submitBtn.prop('disabled', true);
                            inputNombre.addClass('is-invalid').removeClass('is-valid');
                            inputSigla.addClass('is-invalid').removeClass('is-valid');
                        }
                    });
                }, 500);
            });

            // Editar universidad
            $(document).on('click', '.editBtn', function() {
                const data = $(this).data('bs-obj');
                $('#universidadId').val(data.id);
                $('#nombre_edicion').val(data.nombre);
                $('#sigla_edicion').val(data.sigla);

                // Reset y activar verificación
                $('#feedback_nombre_edicion, #feedback_sigla_edicion').removeClass(
                    'text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
                $('#updateForm').removeClass('was-validated');
                $('#nombre_edicion, #sigla_edicion').removeClass('is-valid is-invalid');

                // Forzar verificación
                setTimeout(() => {
                    $('#nombre_edicion').trigger('input');
                    $('#sigla_edicion').trigger('input');
                }, 100);
            });

            // Eliminar universidad
            $(document).on('click', '.deleteBtn', function() {
                const data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);
            });

            // REGISTRAR UNIVERSIDAD
            $('#addForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                const form = $(this)[0];
                if (!form.checkValidity()) {
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }

                isProcessing = true;
                const submitBtn = $('.addBtn');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...');

                $.ajax({
                    url: "{{ route('admin.universidades.registrar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            // Cerrar modal primero
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'registrar'));
                            modal.hide();

                            // Mostrar toast
                            showToast('success', res.msg);

                            // Recargar datos después de 500ms
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 500);
                        } else {
                            showToast('error', res.msg || 'Error al registrar la universidad');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar la universidad. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors) {
                            const errors = xhr.responseJSON.errors;
                            if (errors.nombre) {
                                errorMsg = errors.nombre[0];
                            } else if (errors.sigla) {
                                errorMsg = errors.sigla[0];
                            }
                        } else if (xhr.responseJSON?.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        showToast('error', errorMsg);
                    },
                    complete: function() {
                        isProcessing = false;
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // ACTUALIZAR UNIVERSIDAD
            $('#updateForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                const form = $(this)[0];
                if (!form.checkValidity()) {
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }

                isProcessing = true;
                const submitBtn = $('.updateBtn');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...');

                $.ajax({
                    url: "{{ route('admin.universidades.modificar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            // CERRAR MODAL CORRECTAMENTE
                            var modalEl = document.getElementById('modalModificar');
                            var modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) {
                                modal.hide();
                            } else {
                                $('#modalModificar').modal('hide');
                            }

                            // Mostrar toast
                            showToast('success', res.msg);

                            // Recargar datos inmediatamente
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al actualizar la universidad');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg =
                            'Error al actualizar la universidad. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors) {
                            const errors = xhr.responseJSON.errors;
                            if (errors.nombre) {
                                errorMsg = errors.nombre[0];
                            } else if (errors.sigla) {
                                errorMsg = errors.sigla[0];
                            }
                        } else if (xhr.responseJSON?.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        showToast('error', errorMsg);
                    },
                    complete: function() {
                        isProcessing = false;
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // ELIMINAR UNIVERSIDAD
            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                isProcessing = true;
                const submitBtn = $('.btnDelete');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...');

                $.ajax({
                    url: "{{ route('admin.universidades.eliminar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            // CERRAR MODAL CORRECTAMENTE
                            var modalEl = document.getElementById('modalEliminar');
                            var modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) {
                                modal.hide();
                            } else {
                                $('#modalEliminar').modal('hide');
                            }

                            // Mostrar toast
                            showToast('success', res.msg);

                            // Recargar datos inmediatamente
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al eliminar la universidad');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al eliminar la universidad. Intenta nuevamente.';
                        if (xhr.responseJSON?.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        showToast('error', errorMsg);
                    },
                    complete: function() {
                        isProcessing = false;
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // FUNCIÓN PARA CARGAR RESULTADOS
            function loadResults(search = '') {
                if (isProcessing) return;
                isProcessing = true;

                $.ajax({
                    url: '{{ route('admin.universidades.listar') }}',
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#universidadesTableBody').html(`
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2 text-muted">Cargando resultados...</p>
                            </td>
                        </tr>
                    `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#universidadesTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium">${response.from || 0}</span> a 
                                 <span class="fw-medium">${response.to || 0}</span> de 
                                 <span class="fw-medium">${response.total}</span> resultados`
                                );

                                // Actualizar contador
                                $('#totalUniversidadesCounter').text(response.total);
                            }

                            // Re-inicializar tooltips
                            $('[data-bs-toggle="tooltip"]').each(function() {
                                if (this._tooltip) {
                                    this._tooltip.dispose();
                                }
                                new bootstrap.Tooltip(this);
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading results:', xhr);
                        showToast('error', 'Error al cargar los resultados');
                        $('#universidadesTableBody').html(`
                        <tr>
                            <td colspan="5" class="text-center py-5 text-danger">
                                <i class="ri-error-warning-line display-5"></i>
                                <p class="mt-2">Error al cargar los datos</p>
                            </td>
                        </tr>
                    `);
                    },
                    complete: function() {
                        isProcessing = false;
                    }
                });
            }

            // Búsqueda por texto
            $('#searchInput').on('input', function() {
                const searchTerm = $(this).val().trim();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadResults(searchTerm);
                }, 500);
            });

            // Limpiar filtros
            $('#clearFilters').on('click', function() {
                $('#searchInput').val('');
                loadResults();
            });

            // Manejar paginación
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                if (isProcessing) return;

                const url = $(this).attr('href');
                const search = $('#searchInput').val().trim();

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#universidadesTableBody').html(`
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2 text-muted">Cargando página...</p>
                            </td>
                        </tr>
                    `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#universidadesTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium">${response.from || 0}</span> a 
                                 <span class="fw-medium">${response.to || 0}</span> de 
                                 <span class="fw-medium">${response.total}</span> resultados`
                                );

                                $('#totalUniversidadesCounter').text(response.total);
                            }

                            // Actualizar URL sin recargar
                            const newUrl = url + (search ? '&search=' + encodeURIComponent(
                                search) : '');
                            window.history.pushState({}, '', newUrl);
                        }
                    },
                    error: function() {
                        showToast('error', 'Error al cargar la página');
                    }
                });
            });

            // FUNCIÓN TOAST MEJORADA - COLORES UNIFORMES
            function showToast(type, message) {
                // Configuraciones por tipo
                const config = {
                    success: {
                        icon: 'ri-checkbox-circle-fill',
                        bgClass: 'bg-success',
                        title: 'Éxito'
                    },
                    error: {
                        icon: 'ri-close-circle-fill',
                        bgClass: 'bg-danger',
                        title: 'Error'
                    },
                    warning: {
                        icon: 'ri-alert-fill',
                        bgClass: 'bg-warning',
                        title: 'Advertencia'
                    },
                    info: {
                        icon: 'ri-information-fill',
                        bgClass: 'bg-info',
                        title: 'Información'
                    }
                };

                const toastConfig = config[type] || config.info;
                const toastId = 'toast-' + Date.now();

                // Crear toast con estilo uniforme
                const toastHtml = `
                <div id="${toastId}" class="toast ${toastConfig.bgClass} text-white" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header ${toastConfig.bgClass} text-white border-bottom-0">
                        <i class="${toastConfig.icon} me-2"></i>
                        <strong class="me-auto">${toastConfig.title}</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body d-flex align-items-center">
                        <i class="${toastConfig.icon} me-2 fs-5"></i>
                        <span class="flex-grow-1">${message}</span>
                    </div>
                </div>
            `;

                // Añadir al contenedor
                let container = document.getElementById('toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'toast-container';
                    container.className = 'toast-container position-fixed top-0 end-0 p-3';
                    document.body.appendChild(container);
                }

                container.insertAdjacentHTML('afterbegin', toastHtml);

                // Mostrar toast
                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement, {
                    autohide: true,
                    delay: 3000
                });

                toast.show();

                // Eliminar cuando se oculte
                toastElement.addEventListener('hidden.bs.toast', function() {
                    this.remove();
                });
            }

            // Validación Bootstrap
            (function() {
                'use strict';
                const forms = document.querySelectorAll('.needs-validation');
                Array.from(forms).forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            })();
        });
    </script>
@endpush
