@extends('admin.dashboard')
@section('admin')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap');

        :root {
            --tipo-primary: #0f766e;
            --tipo-primary-light: #f0fdfa;
            --tipo-primary-dark: #0d5f59;
            --tipo-accent: #f59e0b;
            --tipo-accent-light: #fffbeb;
            --tipo-surface: #f8fafc;
            --tipo-border: #e2e8f0;
            --tipo-text: #1e293b;
            --tipo-text-muted: #64748b;
            --tipo-success: #10b981;
            --tipo-danger: #ef4444;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }

        .tipos-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--tipo-text);
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Page Header */
        .tipos-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 28px;
            padding: 24px 28px;
            background: linear-gradient(135deg, var(--tipo-primary) 0%, var(--tipo-primary-dark) 100%);
            border-radius: var(--radius-lg);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .tipos-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .tipos-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 20%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .tipos-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.65rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1;
            color: white;
        }

        .tipos-header h1 i {
            color: white;
        }

        .tipos-header p {
            margin: 4px 0 0;
            opacity: 0.85;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
            color: white;
        }

        .btn-new-tipo {
            background: white;
            color: var(--tipo-primary);
            border: none;
            padding: 10px 24px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.25s ease;
            box-shadow: var(--shadow-sm);
            position: relative;
            z-index: 1;
        }

        .btn-new-tipo:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: var(--tipo-primary-light);
        }

        /* Search Bar */
        .search-bar {
            background: white;
            border-radius: var(--radius-md);
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--tipo-border);
        }

        .search-wrapper {
            flex: 1;
            min-width: 200px;
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--tipo-text-muted);
            font-size: 1.1rem;
        }

        .search-wrapper input {
            width: 100%;
            padding: 10px 14px 10px 42px;
            border: 1px solid var(--tipo-border);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s ease;
            background: var(--tipo-surface);
        }

        .search-wrapper input:focus {
            outline: none;
            border-color: var(--tipo-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
            background: white;
        }

        .btn-clear {
            padding: 10px 20px;
            border: 1px solid var(--tipo-border);
            border-radius: var(--radius-sm);
            background: white;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--tipo-text-muted);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-clear:hover {
            background: var(--tipo-surface);
            color: var(--tipo-text);
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--tipo-border);
            overflow: hidden;
        }

        .table-card-header {
            padding: 18px 24px;
            border-bottom: 1px dashed var(--tipo-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-card-header h5 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            margin: 0;
            font-size: 1.05rem;
        }

        .table-responsive {
            overflow-x: visible !important;
        }

        .tipos-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .tipos-table thead th {
            background: var(--tipo-surface);
            padding: 12px 16px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--tipo-text-muted);
            border-bottom: 1px solid var(--tipo-border);
            white-space: normal;
            vertical-align: middle;
        }

        .tipos-table tbody tr {
            transition: background 0.15s ease;
        }

        .tipos-table tbody tr:hover {
            background: var(--tipo-primary-light);
        }

        .tipos-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--tipo-border);
            vertical-align: middle;
            white-space: normal;
            font-size: 0.88rem;
        }

        .tipos-table tbody tr:last-child td {
            border-bottom: none;
        }

        .tipo-name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .tipo-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--tipo-primary-light);
            color: var(--tipo-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .tipo-name-text h6 {
            margin: 0;
            font-weight: 600;
            font-size: 0.92rem;
            color: var(--tipo-text);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-badge.active {
            background: #ecfdf5;
            color: #059669;
        }

        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: var(--radius-sm);
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .action-btn.edit {
            background: #fffbeb;
            color: #d97706;
            border-color: #fde68a;
        }

        .action-btn.edit:hover {
            background: #fde68a;
            transform: translateY(-1px);
        }

        .action-btn.delete {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .action-btn.delete:hover {
            background: #fecaca;
            transform: translateY(-1px);
        }

        .table-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--tipo-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            background: var(--tipo-surface);
        }

        .table-footer .results-count {
            font-size: 0.85rem;
            color: var(--tipo-text-muted);
        }

        .pagination .page-link {
            border-radius: var(--radius-sm) !important;
            border: 1px solid var(--tipo-border);
            color: var(--tipo-text-muted);
            font-size: 0.85rem;
            padding: 6px 12px;
            margin: 0 2px;
        }

        .pagination .page-item.active .page-link {
            background: var(--tipo-primary);
            border-color: var(--tipo-primary);
            color: white;
        }

        .pagination .page-link:hover {
            background: var(--tipo-primary-light);
            border-color: var(--tipo-primary);
            color: var(--tipo-primary);
        }

        .empty-state {
            padding: 48px 24px;
            text-align: center;
        }

        .empty-state i {
            font-size: 3.5rem;
            color: #cbd5e1;
            margin-bottom: 12px;
        }

        .empty-state p {
            color: var(--tipo-text-muted);
            margin: 0;
        }

        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .modal-header {
            border-bottom: 1px solid var(--tipo-border);
            padding: 16px 24px;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            border-top: 1px solid var(--tipo-border);
            padding: 16px 24px;
        }

        .form-control {
            border-radius: var(--radius-sm);
            border: 1px solid var(--tipo-border);
            padding: 10px 14px;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: var(--tipo-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        }

        .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--tipo-text);
            margin-bottom: 6px;
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999999 !important;
        }

        .toast {
            min-width: 300px;
            max-width: 350px;
            border-radius: var(--radius-md);
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

        @media (max-width: 767.98px) {
            .tipos-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .table-footer {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>

    <div class="container-fluid tipos-page">
        <!-- Page Header -->
        <div class="tipos-header">
            <div>
                <h1><i class="ri-stack-line me-2"></i>Gestión de Tipos de Programas</h1>
                <p>Administra los tipos de programas disponibles</p>
            </div>
            @if (Auth::guard('web')->user()->can('tipos.programas.registrar'))
                <button type="button" class="btn-new-tipo" data-bs-toggle="modal" data-bs-target="#registrar">
                    <i class="ri-add-line me-1"></i> Nuevo Tipo
                </button>
            @endif
        </div>

        <!-- Search Bar -->
        <div class="search-bar">
            <div class="search-wrapper">
                <i class="ri-search-line"></i>
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar tipo de programa..."
                    value="{{ request('search') ?? '' }}">
            </div>
            <button type="button" id="clearFilters" class="btn-clear">
                <i class="ri-refresh-line me-1"></i> Limpiar
            </button>
        </div>

        <!-- Table -->
        <div class="table-card">
            <div class="table-card-header">
                <h5><i class="ri-list-check me-2 text-muted"></i>Listado de Tipos de Programas</h5>
            </div>
            <div class="table-responsive">
                <table class="tipos-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Nombre del Tipo</th>
                            <th width="15%">Estado</th>
                            <th width="16%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tiposTableBody">
                        @include('admin.tipos.partials.table-body')
                    </tbody>
                </table>
            </div>
            @if ($tipos->total() > 0)
                <div class="table-footer">
                    <div class="results-count">
                        Mostrando <span class="fw-medium">{{ $tipos->firstItem() }}</span> a
                        <span class="fw-medium">{{ $tipos->lastItem() }}</span> de
                        <span class="fw-medium">{{ $tipos->total() }}</span> resultados
                    </div>
                    <div class="pagination-container">
                        {{ $tipos->appends(request()->input())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Registrar -->
    <div class="modal fade" id="registrar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--tipo-primary-light);">
                    <h5 class="modal-title" style="color: var(--tipo-primary); font-weight: 600;">
                        <i class="ri-add-line me-2"></i>Registrar Nuevo Tipo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre_registro" class="form-label">Nombre del Tipo <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="nombre_registro" name="nombre" class="form-control"
                                placeholder="Ej: Maestría, Diplomado, Especialidad" required>
                            <div class="invalid-feedback">Por favor ingresa el nombre del tipo</div>
                            <small id="feedback_registro" class="form-text mt-1"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn addBtn" style="background: var(--tipo-primary); color: white;"
                            disabled>
                            <i class="ri-save-3-line me-1"></i> Registrar Tipo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Modificar -->
    <div class="modal fade" id="modalModificar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--tipo-accent-light);">
                    <h5 class="modal-title" style="color: #b45309; font-weight: 600;">
                        <i class="ri-edit-line me-2"></i>Editar Tipo de Programa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="id" id="tipoId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre_edicion" class="form-label">Nombre del Tipo <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre_edicion" name="nombre" required>
                            <div class="invalid-feedback">Por favor ingresa el nombre del tipo</div>
                            <small id="feedback_edicion" class="form-text mt-1"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn updateBtn"
                            style="background: var(--tipo-accent); color: white;" disabled>
                            <i class="ri-refresh-line me-1"></i> Actualizar Tipo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: #fef2f2;">
                    <h5 class="modal-title" style="color: #dc2626; font-weight: 600;">
                        <i class="ri-delete-bin-line me-2"></i>Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteForm">
                    @csrf
                    <input type="hidden" name="id" id="eliminarId">
                    <div class="modal-body text-center">
                        <div class="mb-3">
                            <div
                                style="width: 64px; height: 64px; margin: 0 auto; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="ri-alert-line" style="font-size: 1.8rem; color: #dc2626;"></i>
                            </div>
                        </div>
                        <h5 style="font-weight: 600;">¿Estás seguro de eliminar este tipo?</h5>
                        <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
                        <div class="mt-3 p-3 bg-light rounded">
                            <small class="text-danger fw-medium">Advertencia:</small>
                            <small class="text-muted">Se recomienda actualizar los registros asociados antes de eliminar
                                este tipo.</small>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btnDelete"
                            style="background: var(--tipo-danger); color: white;">
                            <i class="ri-delete-bin-line me-1"></i> Sí, Eliminar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            let debounceTimer;
            let isProcessing = false;

            $('[data-bs-toggle="tooltip"]').each(function() {
                new bootstrap.Tooltip(this);
            });

            $('#registrar, #modalModificar, #modalEliminar').on('hidden.bs.modal', function() {
                if (this.id === 'registrar') {
                    $('#addForm')[0].reset();
                    $('#feedback_registro').removeClass('text-success text-danger').text('');
                    $('.addBtn').prop('disabled', true);
                    $('#addForm').removeClass('was-validated');
                } else if (this.id === 'modalModificar') {
                    $('#updateForm')[0].reset();
                    $('#feedback_edicion').removeClass('text-success text-danger').text('');
                    $('.updateBtn').prop('disabled', true);
                    $('#updateForm').removeClass('was-validated');
                } else if (this.id === 'modalEliminar') {
                    $('#deleteForm')[0].reset();
                }
            });

            $('#nombre_registro').on('input', function() {
                const nombre = $(this).val().trim();
                const feedback = $('#feedback_registro');
                const submitBtn = $('.addBtn');
                const input = $(this);

                if (nombre.length === 0) {
                    feedback.removeClass('text-success text-danger').text('');
                    submitBtn.prop('disabled', true);
                    input.removeClass('is-valid is-invalid');
                    return;
                }

                if (nombre.length < 2) {
                    feedback.addClass('text-danger').text('El nombre debe tener al menos 2 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                if (nombre.length > 100) {
                    feedback.addClass('text-danger').text('El nombre no puede exceder los 100 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.tipos.verificar') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre
                        },
                        beforeSend: function() {
                            feedback.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                                );
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Este tipo ya existe'
                                    );
                                submitBtn.prop('disabled', true);
                                input.addClass('is-invalid').removeClass('is-valid');
                            } else {
                                feedback.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Nombre disponible'
                                    );
                                submitBtn.prop('disabled', false);
                                input.addClass('is-valid').removeClass('is-invalid');
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').html(
                                '<i class="ri-alert-line me-1"></i> Error al verificar el nombre'
                                );
                            submitBtn.prop('disabled', true);
                            input.addClass('is-invalid').removeClass('is-valid');
                        }
                    });
                }, 500);
            });

            $('#nombre_edicion').on('input', function() {
                const nombre = $(this).val().trim();
                const feedback = $('#feedback_edicion');
                const submitBtn = $('.updateBtn');
                const id = $('#tipoId').val();
                const input = $(this);

                if (nombre.length === 0) {
                    feedback.removeClass('text-success text-danger').text('');
                    submitBtn.prop('disabled', true);
                    input.removeClass('is-valid is-invalid');
                    return;
                }

                if (nombre.length < 2) {
                    feedback.addClass('text-danger').text('El nombre debe tener al menos 2 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                if (nombre.length > 100) {
                    feedback.addClass('text-danger').text('El nombre no puede exceder los 100 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.tipos.verificaredicion') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre,
                            id: id
                        },
                        beforeSend: function() {
                            feedback.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                                );
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Este tipo ya existe'
                                    );
                                submitBtn.prop('disabled', true);
                                input.addClass('is-invalid').removeClass('is-valid');
                            } else {
                                feedback.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Nombre disponible'
                                    );
                                submitBtn.prop('disabled', false);
                                input.addClass('is-valid').removeClass('is-invalid');
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').html(
                                '<i class="ri-alert-line me-1"></i> Error al verificar el nombre'
                                );
                            submitBtn.prop('disabled', true);
                            input.addClass('is-invalid').removeClass('is-valid');
                        }
                    });
                }, 500);
            });

            $(document).on('click', '.editBtn', function() {
                const data = $(this).data('bs-obj');
                $('#tipoId').val(data.id);
                $('#nombre_edicion').val(data.nombre);
                $('#feedback_edicion').removeClass('text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
                $('#updateForm').removeClass('was-validated');
                $('#nombre_edicion').removeClass('is-valid is-invalid');
                setTimeout(() => {
                    $('#nombre_edicion').trigger('input');
                }, 100);
            });

            $(document).on('click', '.deleteBtn', function() {
                const data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);
            });

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
                    url: "{{ route('admin.tipos.registrar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'registrar'));
                            modal.hide();
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 500);
                        } else {
                            showToast('error', res.msg || 'Error al registrar el tipo');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar el tipo. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors?.nombre) errorMsg = xhr.responseJSON.errors
                            .nombre[0];
                        else if (xhr.responseJSON?.msg) errorMsg = xhr.responseJSON.msg;
                        showToast('error', errorMsg);
                    },
                    complete: function() {
                        isProcessing = false;
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

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
                    url: "{{ route('admin.tipos.modificar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            var modalEl = document.getElementById('modalModificar');
                            var modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();
                            else $('#modalModificar').modal('hide');
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al actualizar el tipo');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al actualizar el tipo. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors?.nombre) errorMsg = xhr.responseJSON.errors
                            .nombre[0];
                        else if (xhr.responseJSON?.msg) errorMsg = xhr.responseJSON.msg;
                        showToast('error', errorMsg);
                    },
                    complete: function() {
                        isProcessing = false;
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;
                isProcessing = true;
                const submitBtn = $('.btnDelete');
                const originalHtml = submitBtn.html();
                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...');
                $.ajax({
                    url: "{{ route('admin.tipos.eliminar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            var modalEl = document.getElementById('modalEliminar');
                            var modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();
                            else $('#modalEliminar').modal('hide');
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al eliminar el tipo');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al eliminar el tipo. Intenta nuevamente.';
                        if (xhr.responseJSON?.msg) errorMsg = xhr.responseJSON.msg;
                        showToast('error', errorMsg);
                    },
                    complete: function() {
                        isProcessing = false;
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            function loadResults(search = '') {
                if (isProcessing) return;
                isProcessing = true;
                $.ajax({
                    url: '{{ route('admin.tipos.listar') }}',
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#tiposTableBody').html(`
                            <tr><td colspan="4" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>
                                <p class="mt-2 text-muted">Cargando resultados...</p>
                            </td></tr>`);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#tiposTableBody').html(response.html);
                            if (response.pagination) $('.pagination-container').html(response
                                .pagination);
                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium">${response.from || 0}</span> a 
                                     <span class="fw-medium">${response.to || 0}</span> de 
                                     <span class="fw-medium">${response.total}</span> resultados`
                                );
                                $('#totalTiposCounter').text(response.total);
                            }
                            $('[data-bs-toggle="tooltip"]').each(function() {
                                if (this._tooltip) this._tooltip.dispose();
                                new bootstrap.Tooltip(this);
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading results:', xhr);
                        showToast('error', 'Error al cargar los resultados');
                        $('#tiposTableBody').html(`
                            <tr><td colspan="4" class="text-center py-5 text-danger">
                                <i class="ri-error-warning-line display-5"></i>
                                <p class="mt-2">Error al cargar los datos</p>
                            </td></tr>`);
                    },
                    complete: function() {
                        isProcessing = false;
                    }
                });
            }

            $('#searchInput').on('input', function() {
                const searchTerm = $(this).val().trim();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadResults(searchTerm);
                }, 500);
            });

            $('#clearFilters').on('click', function() {
                $('#searchInput').val('');
                loadResults();
            });

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
                        $('#tiposTableBody').html(`
                            <tr><td colspan="4" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>
                                <p class="mt-2 text-muted">Cargando página...</p>
                            </td></tr>`);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#tiposTableBody').html(response.html);
                            if (response.pagination) $('.pagination-container').html(response
                                .pagination);
                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium">${response.from || 0}</span> a 
                                     <span class="fw-medium">${response.to || 0}</span> de 
                                     <span class="fw-medium">${response.total}</span> resultados`
                                );
                                $('#totalTiposCounter').text(response.total);
                            }
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

            function showToast(type, message) {
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
                    </div>`;
                let container = document.getElementById('toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'toast-container';
                    container.className = 'toast-container position-fixed top-0 end-0 p-3';
                    document.body.appendChild(container);
                }
                container.insertAdjacentHTML('afterbegin', toastHtml);
                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement, {
                    autohide: true,
                    delay: 3000
                });
                toast.show();
                toastElement.addEventListener('hidden.bs.toast', function() {
                    this.remove();
                });
            }

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
