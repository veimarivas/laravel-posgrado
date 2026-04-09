@extends('admin.dashboard')
@section('admin')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap');

        :root {
            --fase-primary: #0f766e;
            --fase-primary-light: #f0fdfa;
            --fase-primary-dark: #0d5f59;
            --fase-accent: #f59e0b;
            --fase-accent-light: #fffbeb;
            --fase-surface: #f8fafc;
            --fase-border: #e2e8f0;
            --fase-text: #1e293b;
            --fase-text-muted: #64748b;
            --fase-success: #10b981;
            --fase-danger: #ef4444;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }

        .fases-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--fase-text);
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

        .fases-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 28px;
            padding: 24px 28px;
            background: linear-gradient(135deg, var(--fase-primary) 0%, var(--fase-primary-dark) 100%);
            border-radius: var(--radius-lg);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .fases-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .fases-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 20%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .fases-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.65rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1;
            color: white;
        }

        .fases-header h1 i {
            color: white;
        }

        .fases-header p {
            margin: 4px 0 0;
            opacity: 0.85;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
            color: white;
        }

        .btn-new-fase {
            background: white;
            color: var(--fase-primary);
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

        .btn-new-fase:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: var(--fase-primary-light);
        }

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
            border: 1px solid var(--fase-border);
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
            color: var(--fase-text-muted);
            font-size: 1.1rem;
        }

        .search-wrapper input {
            width: 100%;
            padding: 10px 14px 10px 42px;
            border: 1px solid var(--fase-border);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s ease;
            background: var(--fase-surface);
        }

        .search-wrapper input:focus {
            outline: none;
            border-color: var(--fase-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
            background: white;
        }

        .btn-clear {
            padding: 10px 20px;
            border: 1px solid var(--fase-border);
            border-radius: var(--radius-sm);
            background: white;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--fase-text-muted);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-clear:hover {
            background: var(--fase-surface);
            color: var(--fase-text);
        }

        .table-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--fase-border);
            overflow: hidden;
        }

        .table-card-header {
            padding: 18px 24px;
            border-bottom: 1px dashed var(--fase-border);
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

        .fases-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .fases-table thead th {
            background: var(--fase-surface);
            padding: 12px 16px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--fase-text-muted);
            border-bottom: 1px solid var(--fase-border);
            white-space: normal;
            vertical-align: middle;
        }

        .fases-table tbody tr {
            transition: background 0.15s ease;
        }

        .fases-table tbody tr:hover {
            background: var(--fase-primary-light);
        }

        .fases-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--fase-border);
            vertical-align: middle;
            font-size: 0.88rem;
        }

        .fases-table tbody tr:last-child td {
            border-bottom: none;
        }

        .fase-name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .fase-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--fase-primary-light);
            color: var(--fase-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .fase-name-text h6 {
            margin: 0;
            font-weight: 600;
            font-size: 0.92rem;
            color: var(--fase-text);
        }

        .fase-numero {
            font-weight: 600;
            color: var(--fase-primary);
        }

        .color-swatch {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .color-box {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            border: 1px solid var(--fase-border);
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
            border-top: 1px solid var(--fase-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            background: var(--fase-surface);
        }

        .table-footer .results-count {
            font-size: 0.85rem;
            color: var(--fase-text-muted);
        }

        .pagination .page-link {
            border-radius: var(--radius-sm) !important;
            border: 1px solid var(--fase-border);
            color: var(--fase-text-muted);
            font-size: 0.85rem;
            padding: 6px 12px;
            margin: 0 2px;
        }

        .pagination .page-item.active .page-link {
            background: var(--fase-primary);
            border-color: var(--fase-primary);
            color: white;
        }

        .pagination .page-link:hover {
            background: var(--fase-primary-light);
            border-color: var(--fase-primary);
            color: var(--fase-primary);
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
            color: var(--fase-text-muted);
            margin: 0;
        }

        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .modal-header {
            border-bottom: 1px solid var(--fase-border);
            padding: 16px 24px;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            border-top: 1px solid var(--fase-border);
            padding: 16px 24px;
        }

        .form-control,
        .form-select {
            border-radius: var(--radius-sm);
            border: 1px solid var(--fase-border);
            padding: 10px 14px;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: var(--fase-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        }

        .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--fase-text);
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
            .fases-header {
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

    <div class="container-fluid fases-page">
        <div class="fases-header">
            <div>
                <h1><i class="ri-list-check-3 me-2"></i>Gestión de Fases</h1>
                <p>Administra las fases disponibles para ofertas académicas</p>
            </div>
            @if (Auth::guard('web')->user()->can('fases.registrar'))
                <button type="button" class="btn-new-fase" data-bs-toggle="modal" data-bs-target="#registrar">
                    <i class="ri-add-line me-1"></i> Nueva Fase
                </button>
            @endif
        </div>

        <div class="search-bar">
            <div class="search-wrapper">
                <i class="ri-search-line"></i>
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar fase por nombre..."
                    value="{{ request('search') ?? '' }}">
            </div>
            <button type="button" id="clearFilters" class="btn-clear">
                <i class="ri-refresh-line me-1"></i> Limpiar
            </button>
        </div>

        <div class="table-card">
            <div class="table-card-header">
                <h5><i class="ri-list-check me-2 text-muted"></i>Listado de Fases</h5>
            </div>
            <div class="table-responsive">
                <table class="fases-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">N° Fase</th>
                            <th>Nombre</th>
                            <th width="15%">Color</th>
                            <th width="12%">Estado</th>
                            <th width="15%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="fasesTableBody">
                        @include('admin.fases.partials.table-body')
                    </tbody>
                </table>
            </div>
            @if ($fases->total() > 0)
                <div class="table-footer">
                    <div class="results-count">
                        Mostrando <span class="fw-medium">{{ $fases->firstItem() }}</span> a
                        <span class="fw-medium">{{ $fases->lastItem() }}</span> de
                        <span class="fw-medium">{{ $fases->total() }}</span> resultados
                    </div>
                    <div class="pagination-container">
                        {{ $fases->appends(request()->input())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Registrar -->
    <div class="modal fade" id="registrar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--fase-primary-light);">
                    <h5 class="modal-title" style="color: var(--fase-primary); font-weight: 600;">
                        <i class="ri-add-line me-2"></i>Registrar Nueva Fase
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre_registro" class="form-label">Nombre de la Fase <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="nombre_registro" name="nombre" class="form-control"
                                placeholder="Ej: Inscripciones, En curso, Finalizada" required>
                            <div class="invalid-feedback">Por favor ingresa el nombre de la fase</div>
                            <small id="feedback_registro" class="form-text mt-1"></small>
                        </div>
                        <div class="mb-3">
                            <label for="color_registro" class="form-label">Color <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" id="color_registro" name="color"
                                    class="form-control form-control-color" value="#0f766e"
                                    style="width: 60px; height: 38px; padding: 2px;">
                                <input type="text" class="form-control" id="color_text_registro" value="#0f766e" readonly
                                    style="flex: 1;">
                                <div class="color-preview" id="preview_registro"
                                    style="width: 38px; height: 38px; border-radius: var(--radius-sm); border: 1px solid var(--fase-border); background-color: #0f766e;">
                                </div>
                            </div>
                            <small class="form-text text-muted">Selecciona un color para identificar la fase</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn addBtn" style="background: var(--fase-primary); color: white;"
                            disabled>
                            <i class="ri-save-3-line me-1"></i> Registrar Fase
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
                <div class="modal-header" style="background: var(--fase-accent-light);">
                    <h5 class="modal-title" style="color: #b45309; font-weight: 600;">
                        <i class="ri-edit-line me-2"></i>Editar Fase
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="faseId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre_edicion" class="form-label">Nombre de la Fase <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre_edicion" name="nombre" required>
                            <div class="invalid-feedback">Por favor ingresa el nombre de la fase</div>
                            <small id="feedback_edicion" class="form-text mt-1"></small>
                        </div>
                        <div class="mb-3">
                            <label for="color_edicion" class="form-label">Color <span
                                    class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" id="color_edicion" name="color"
                                    class="form-control form-control-color"
                                    style="width: 60px; height: 38px; padding: 2px;">
                                <input type="text" class="form-control" id="color_text_edicion" readonly
                                    style="flex: 1;">
                                <div class="color-preview" id="preview_edicion"
                                    style="width: 38px; height: 38px; border-radius: var(--radius-sm); border: 1px solid var(--fase-border);">
                                </div>
                            </div>
                            <small class="form-text text-muted">Selecciona un color para identificar la fase</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn updateBtn"
                            style="background: var(--fase-accent); color: white;" disabled>
                            <i class="ri-refresh-line me-1"></i> Actualizar Fase
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
                    @method('DELETE')
                    <input type="hidden" name="id" id="eliminarId">
                    <div class="modal-body text-center">
                        <div class="mb-3">
                            <div
                                style="width: 64px; height: 64px; margin: 0 auto; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="ri-alert-line" style="font-size: 1.8rem; color: #dc2626;"></i>
                            </div>
                        </div>
                        <h5 style="font-weight: 600;">¿Estás seguro de eliminar esta fase?</h5>
                        <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
                        <div class="mt-3 p-3 bg-light rounded">
                            <small class="text-danger fw-medium">Advertencia:</small>
                            <small class="text-muted">Se recomienda actualizar las ofertas académicas asociadas antes de
                                eliminar esta fase.</small>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btnDelete"
                            style="background: var(--fase-danger); color: white;">
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
                    $('#color_registro').val('#0f766e');
                    $('#color_text_registro').val('#0f766e');
                    $('#preview_registro').css('background-color', '#0f766e');
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

            $('#color_registro').on('input', function() {
                var color = $(this).val();
                $('#color_text_registro').val(color);
                $('#preview_registro').css('background-color', color);
            });

            $('#color_edicion').on('input', function() {
                var color = $(this).val();
                $('#color_text_edicion').val(color);
                $('#preview_edicion').css('background-color', color);
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

                if (nombre.length < 3) {
                    feedback.addClass('text-danger').text('El nombre debe tener al menos 3 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.fases.verificar') }}",
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
                                    '<i class="ri-error-warning-line me-1"></i> Esta fase ya existe'
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
                const id = $('#faseId').val();
                const input = $(this);

                if (nombre.length === 0) {
                    feedback.removeClass('text-success text-danger').text('');
                    submitBtn.prop('disabled', true);
                    input.removeClass('is-valid is-invalid');
                    return;
                }

                if (nombre.length < 3) {
                    feedback.addClass('text-danger').text('El nombre debe tener al menos 3 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.fases.verificar') }}",
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
                                    '<i class="ri-error-warning-line me-1"></i> Esta fase ya existe'
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
                $('#faseId').val(data.id);
                $('#nombre_edicion').val(data.nombre);
                $('#color_edicion').val(data.color);
                $('#color_text_edicion').val(data.color);
                $('#preview_edicion').css('background-color', data.color);

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
                    url: "{{ route('admin.fases.registrar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            bootstrap.Modal.getInstance(document.getElementById('registrar'))
                                .hide();
                            showToast('success', res.msg);
                            setTimeout(() => loadResults($('#searchInput').val().trim()), 500);
                        } else {
                            showToast('error', res.msg || 'Error al registrar la fase');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar la fase.';
                        if (xhr.responseJSON?.errors?.nombre) errorMsg = xhr.responseJSON.errors
                            .nombre[0];
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
                    url: "{{ route('admin.fases.modificar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            bootstrap.Modal.getInstance(document.getElementById(
                                'modalModificar')).hide();
                            showToast('success', res.msg);
                            setTimeout(() => loadResults($('#searchInput').val().trim()), 300);
                        } else {
                            showToast('error', res.msg || 'Error al actualizar la fase');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al actualizar la fase.';
                        if (xhr.responseJSON?.errors?.nombre) errorMsg = xhr.responseJSON.errors
                            .nombre[0];
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
                    url: "{{ route('admin.fases.eliminar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            bootstrap.Modal.getInstance(document.getElementById(
                                'modalEliminar')).hide();
                            showToast('success', res.msg);
                            setTimeout(() => loadResults($('#searchInput').val().trim()), 300);
                        } else {
                            showToast('error', res.msg || 'Error al eliminar la fase');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al eliminar la fase.';
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
                    url: '{{ route('admin.fases.listar') }}',
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#fasesTableBody').html(
                            `<tr><td colspan="6" class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Cargando...</p></td></tr>`
                            );
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#fasesTableBody').html(response.html);
                            if (response.pagination) $('.pagination-container').html(response
                                .pagination);
                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium">${response.from || 0}</span> a <span class="fw-medium">${response.to || 0}</span> de <span class="fw-medium">${response.total}</span> resultados`
                                    );
                                $('#totalFasesCounter').text(response.total);
                            }
                            $('[data-bs-toggle="tooltip"]').each(function() {
                                if (this._tooltip) this._tooltip.dispose();
                                new bootstrap.Tooltip(this);
                            });
                        }
                    },
                    error: function() {
                        showToast('error', 'Error al cargar los resultados');
                    },
                    complete: function() {
                        isProcessing = false;
                    }
                });
            }

            $('#searchInput').on('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => loadResults($(this).val().trim()), 500);
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
                        $('#fasesTableBody').html(
                            `<tr><td colspan="6" class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Cargando página...</p></td></tr>`
                            );
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#fasesTableBody').html(response.html);
                            if (response.pagination) $('.pagination-container').html(response
                                .pagination);
                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium">${response.from || 0}</span> a <span class="fw-medium">${response.to || 0}</span> de <span class="fw-medium">${response.total}</span> resultados`
                                    );
                            }
                            window.history.pushState({}, '', url + (search ? '&search=' +
                                encodeURIComponent(search) : ''));
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
                };
                const tc = config[type] || config.info;
                const toastId = 'toast-' + Date.now();
                const toastHtml =
                    `<div id="${toastId}" class="toast ${tc.bgClass} text-white"><div class="toast-header ${tc.bgClass} text-white border-bottom-0"><i class="${tc.icon} me-2"></i><strong class="me-auto">${tc.title}</strong><button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button></div><div class="toast-body d-flex align-items-center"><i class="${tc.icon} me-2 fs-5"></i><span class="flex-grow-1">${message}</span></div></div>`;
                let container = $('#toast-container');
                if (!container.length) {
                    container = $(
                        '<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3"></div>'
                        );
                    $('body').append(container);
                }
                container.append(toastHtml);
                const toastEl = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastEl, {
                    autohide: true,
                    delay: 3000
                });
                toast.show();
                toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
            }

            (function() {
                'use strict';
                document.querySelectorAll('.needs-validation').forEach(form => {
                    form.addEventListener('submit', event => {
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
