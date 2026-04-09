@extends('admin.dashboard')
@section('admin')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap');

        :root {
            --banco-primary: #0d9488;
            --banco-primary-light: #f0fdfa;
            --banco-primary-dark: #0f766e;
            --banco-accent: #f59e0b;
            --banco-surface: #f8fafc;
            --banco-border: #e2e8f0;
            --banco-text: #1e293b;
            --banco-text-muted: #64748b;
            --banco-success: #10b981;
            --banco-danger: #ef4444;
            --banco-info: #3b82f6;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 25px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
        }

        .bancos-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--banco-text);
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

        .bancos-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 28px;
            padding: 24px 28px;
            background: linear-gradient(135deg, var(--banco-primary) 0%, var(--banco-primary-dark) 100%);
            border-radius: var(--radius-lg);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .bancos-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .bancos-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 20%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .bancos-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.65rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1;
            color: white;
        }

        .bancos-header h1 i {
            color: white;
        }

        .bancos-header p {
            margin: 4px 0 0;
            opacity: 0.85;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
            color: white;
        }

        .btn-new-banco {
            background: white;
            color: var(--banco-primary);
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

        .btn-new-banco:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: var(--banco-primary-light);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border-radius: var(--radius-md);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--banco-border);
            transition: all 0.25s ease;
        }

        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .stat-icon.total {
            background: var(--banco-primary-light);
            color: var(--banco-primary);
        }

        .stat-icon.active {
            background: #ecfdf5;
            color: var(--banco-success);
        }

        .stat-icon.accounts {
            background: #eff6ff;
            color: var(--banco-info);
        }

        .stat-info p {
            margin: 0;
            font-size: 0.8rem;
            color: var(--banco-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 500;
        }

        .stat-info h3 {
            margin: 2px 0 0;
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--banco-text);
        }

        .filter-bar {
            background: white;
            border-radius: var(--radius-md);
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--banco-border);
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
            color: var(--banco-text-muted);
            font-size: 1.1rem;
        }

        .search-wrapper input {
            width: 100%;
            padding: 10px 14px 10px 42px;
            border: 1px solid var(--banco-border);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s ease;
            background: var(--banco-surface);
        }

        .search-wrapper input:focus {
            outline: none;
            border-color: var(--banco-primary);
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
            background: white;
        }

        .btn-clear-filters {
            padding: 8px 16px;
            border: 1px solid var(--banco-border);
            border-radius: var(--radius-sm);
            background: white;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--banco-text-muted);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-clear-filters:hover {
            background: var(--banco-surface);
            color: var(--banco-text);
        }

        .table-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--banco-border);
            overflow: hidden;
        }

        .table-card-header {
            padding: 18px 24px;
            border-bottom: 1px dashed var(--banco-border);
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

        .bancos-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .bancos-table thead th {
            background: var(--banco-surface);
            padding: 12px 16px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--banco-text-muted);
            border-bottom: 1px solid var(--banco-border);
            white-space: normal;
            vertical-align: middle;
        }

        .bancos-table tbody tr {
            transition: background 0.15s ease;
        }

        .bancos-table tbody tr:hover {
            background: var(--banco-primary-light);
        }

        .bancos-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--banco-border);
            vertical-align: middle;
            white-space: normal;
            font-size: 0.88rem;
        }

        .bancos-table tbody tr:last-child td {
            border-bottom: none;
        }

        .banco-logo-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .banco-logo {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
            overflow: hidden;
        }

        .banco-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .banco-name-text h6 {
            margin: 0;
            font-weight: 600;
            font-size: 0.92rem;
            color: var(--banco-text);
        }

        .banco-name-text small {
            font-size: 0.75rem;
            color: var(--banco-text-muted);
        }

        .color-indicator {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .color-swatch {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            flex-shrink: 0;
        }

        .badge-codigo {
            background: var(--banco-surface);
            color: var(--banco-text);
            font-weight: 600;
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 4px;
            border: 1px solid var(--banco-border);
        }

        .badge-cuentas {
            background: var(--banco-primary-light);
            color: var(--banco-primary);
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 4px;
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

        .action-btn.view {
            background: var(--banco-primary-light);
            color: var(--banco-primary);
            border-color: var(--banco-primary-light);
        }

        .action-btn.view:hover {
            background: var(--banco-primary);
            color: white;
            transform: translateY(-1px);
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
            border-top: 1px solid var(--banco-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            background: var(--banco-surface);
        }

        .table-footer .results-count {
            font-size: 0.85rem;
            color: var(--banco-text-muted);
        }

        .pagination .page-link {
            border-radius: var(--radius-sm) !important;
            border: 1px solid var(--banco-border);
            color: var(--banco-text-muted);
            font-size: 0.85rem;
            padding: 6px 12px;
            margin: 0 2px;
        }

        .pagination .page-item.active .page-link {
            background: var(--banco-primary);
            border-color: var(--banco-primary);
            color: white;
        }

        .pagination .page-link:hover {
            background: var(--banco-primary-light);
            border-color: var(--banco-primary);
            color: var(--banco-primary);
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
            color: var(--banco-text-muted);
            margin: 0;
        }

        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .modal-header {
            border-bottom: 1px solid var(--banco-border);
            padding: 16px 24px;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            border-top: 1px solid var(--banco-border);
            padding: 16px 24px;
        }

        .form-control,
        .form-select {
            border-radius: var(--radius-sm);
            border: 1px solid var(--banco-border);
            padding: 10px 14px;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: var(--banco-primary);
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }

        .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--banco-text);
            margin-bottom: 6px;
        }

        .form-check-input:checked {
            background-color: var(--banco-primary);
            border-color: var(--banco-primary);
        }

        @media (max-width: 991.98px) {
            .bancos-header {
                padding: 20px;
            }

            .bancos-header h1 {
                font-size: 1.35rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 767.98px) {
            .bancos-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .table-footer {
                flex-direction: column;
                align-items: center;
            }

            .bancos-table thead th,
            .bancos-table tbody td {
                padding: 10px 10px;
                font-size: 0.8rem;
            }

            .banco-logo {
                width: 32px;
                height: 32px;
                font-size: 0.9rem;
            }

            .banco-name-text h6 {
                font-size: 0.82rem;
            }
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
    </style>

    <div class="container-fluid bancos-page">
        <!-- Page Header -->
        <div class="bancos-header">
            <div>
                <h1><i class="ri-bank-line me-2"></i>Gestión de Bancos</h1>
                <p>Administra los bancos y cuentas bancarias registradas</p>
            </div>
            @if (Auth::guard('web')->user()->can('bancos.registrar'))
                <button type="button" class="btn btn-new-banco" data-bs-toggle="modal" data-bs-target="#registrar">
                    <i class="ri-add-line me-1"></i> Nuevo Banco
                </button>
            @endif
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total"><i class="ri-building-4-line"></i></div>
                <div class="stat-info">
                    <p>Total Bancos</p>
                    <h3 id="totalBancosCounter">{{ $totalBancos }}</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon active"><i class="ri-checkbox-circle-line"></i></div>
                <div class="stat-info">
                    <p>Con Cuentas</p>
                    <h3 id="bancosConCuentas">{{ $bancosConCuentas }}</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon accounts"><i class="ri-bank-card-line"></i></div>
                <div class="stat-info">
                    <p>Total Cuentas</p>
                    <h3 id="totalCuentas">{{ $totalCuentas }}</h3>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <div class="search-wrapper">
                <i class="ri-search-line"></i>
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar banco..."
                    value="{{ request('search') ?? '' }}">
            </div>
            <button type="button" id="clearFilters" class="btn-clear-filters">
                <i class="ri-refresh-line me-1"></i> Limpiar
            </button>
        </div>

        <!-- Table -->
        <div class="table-card">
            <div class="table-card-header">
                <h5><i class="ri-list-check me-2 text-muted"></i>Listado de Bancos Registrados</h5>
            </div>
            <div class="table-responsive">
                <table class="bancos-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Logo</th>
                            <th>Banco</th>
                            <th>Código</th>
                            <th>Color</th>
                            <th>Cuentas</th>
                            <th width="15%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="bancosTableBody">
                        @include('admin.bancos.partials.table-body')
                    </tbody>
                </table>
            </div>
            @if ($bancos->total() > 0)
                <div class="table-footer">
                    <div class="results-count">
                        Mostrando <span class="fw-medium">{{ $bancos->firstItem() }}</span> a
                        <span class="fw-medium">{{ $bancos->lastItem() }}</span> de
                        <span class="fw-medium">{{ $bancos->total() }}</span> resultados
                    </div>
                    <div class="pagination-container">
                        {{ $bancos->appends(request()->input())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
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
            let isProcessing = false;

            // Inicializar tooltips
            function initTooltips() {
                $('[data-bs-toggle="tooltip"]').each(function() {
                    if (this._tooltip) {
                        this._tooltip.dispose();
                    }
                    new bootstrap.Tooltip(this, {
                        container: 'body',
                        trigger: 'hover'
                    });
                });
            }
            initTooltips();

            // Resetear formulario de registro
            function resetAddForm() {
                $('#addForm')[0].reset();
                $('#color_registro').val('#0d6efd');
                $('#colorPreviewRegistro').css('background-color', '#0d6efd');
                $('#feedback_nombre_registro, #feedback_codigo_registro').removeClass('text-success text-danger')
                    .text('');
                $('.addBtn').prop('disabled', true).html('<i class="ri-save-line me-1"></i> Registrar Banco');
            }

            $('#registrar').on('hidden.bs.modal', resetAddForm);

            // Preview de color
            $('#color_registro').on('input', function() {
                $('#colorPreviewRegistro').css('background-color', $(this).val());
            });
            $('#color_edicion').on('input', function() {
                $('#colorPreviewEdicion').css('background-color', $(this).val());
            });

            // Validar nombre/código en registro
            $('#nombre_registro, #codigo_registro').on('input', function() {
                const nombre = $('#nombre_registro').val().trim();
                const codigo = $('#codigo_registro').val().trim();
                const feedbackNombre = $('#feedback_nombre_registro');
                const feedbackCodigo = $('#feedback_codigo_registro');
                const submitBtn = $('.addBtn');

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

            // Registro
            $('#addForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;
                const submitBtn = $('.addBtn');
                isProcessing = true;
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Registrando...');

                $.ajax({
                    url: "{{ route('admin.bancos.registrar') }}",
                    type: "POST",
                    data: $(this).serialize(),
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
                    },
                    complete: function() {
                        isProcessing = false;
                    }
                });
            });

            // Editar - abrir modal
            $(document).on('click', '.editBtn', function() {
                var data = $(this).data('bs-obj');
                $('#bancoId').val(data.id);
                $('#nombre_edicion').val(data.nombre);
                $('#codigo_edicion').val(data.codigo);
                $('#color_edicion').val(data.color || '#0d6efd');
                $('#colorPreviewEdicion').css('background-color', data.color || '#0d6efd');
                $('#logo_edicion').val(data.logo || '');

                $('#feedback_nombre_edicion, #feedback_codigo_edicion').removeClass(
                    'text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
            });

            // Validar edición
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

            // Actualizar
            $('#updateForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;
                const submitBtn = $('.updateBtn');
                isProcessing = true;
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Actualizando...');

                $.ajax({
                    url: "{{ route('admin.bancos.modificar') }}",
                    type: "POST",
                    data: $(this).serialize(),
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
                    },
                    complete: function() {
                        isProcessing = false;
                    }
                });
            });

            // Eliminar - abrir modal
            $(document).on('click', '.deleteBtn', function() {
                var data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);
                $('#warning-cuentas').hide();
                $('.btnDelete').prop('disabled', false).removeClass('disabled');

                $.ajax({
                    url: "{{ route('admin.bancos.ver', ':id') }}".replace(':id', data.id),
                    type: "GET",
                    success: function(response) {
                        const tieneCuentas = response.cuentas && response.cuentas.length > 0;
                        if (tieneCuentas) {
                            $('#warning-cuentas').show().html(
                                `<i class="ri-alert-line me-1"></i> <strong>Advertencia:</strong> Este banco tiene ${response.cuentas.length} cuenta(s) asociada(s). Debe eliminarlas primero.`
                            );
                            $('.btnDelete').prop('disabled', true).addClass('disabled');
                        } else {
                            $('#warning-cuentas').hide();
                            $('.btnDelete').prop('disabled', false).removeClass('disabled');
                        }
                    },
                    error: function() {
                        $('#warning-cuentas').hide();
                        $('.btnDelete').prop('disabled', false).removeClass('disabled');
                    }
                });
            });

            // Confirmar eliminación
            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;
                const submitBtn = $('.btnDelete');
                const bancoId = $('#eliminarId').val();

                if (!bancoId || submitBtn.prop('disabled')) {
                    showNotification('error',
                        'No se puede eliminar el banco porque tiene cuentas asociadas.');
                    return;
                }

                isProcessing = true;
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Eliminando...');

                $.ajax({
                    url: "{{ route('admin.bancos.eliminar') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "DELETE",
                        id: bancoId
                    },
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
                        if (xhr.responseJSON?.msg) errorMsg = xhr.responseJSON.msg;
                        showNotification('error', errorMsg);
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-delete-bin-line me-1"></i> Sí, Eliminar');
                    },
                    complete: function() {
                        isProcessing = false;
                    }
                });
            });

            // Ver detalle
            $(document).on('click', '.viewBtn', function() {
                var data = $(this).data('bs-obj');
                const modal = $('#modalVerBanco');
                const content = $('#bancoDetalleContent');
                content.html(
                    `<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-3">Cargando...</p></div>`
                    );
                modal.modal('show');

                $.ajax({
                    url: "{{ route('admin.bancos.ver', ':id') }}".replace(':id', data.id),
                    type: "GET",
                    success: function(response) {
                        let cuentasHtml = response.cuentas?.length ? response.cuentas.map(c => `
                            <div class="border rounded p-3 mb-2">
                                <h6>${c.numero_cuenta}</h6>
                                <p class="mb-0 small">${c.tipo_cuenta} - ${c.moneda} | Saldo: ${formatCurrency(c.saldo_actual, c.moneda)}</p>
                                <p class="mb-0 small">${c.sucursal?.nombre || 'Sin sucursal'}</p>
                            </div>
                        `).join('') : '<p class="text-muted">No hay cuentas registradas</p>';
                        content.html(`
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    ${response.banco.logo ? `<img src="${response.banco.logo}" style="max-height:80px">` : `<div class="avatar-lg mx-auto mb-3" style="background:${response.banco.color};border-radius:10px"><div class="avatar-title text-white fs-2">${response.banco.nombre.charAt(0)}</div></div>`}
                                    <div class="mt-2"><span class="badge" style="background:${response.banco.color}"> </span> ${response.banco.color || 'Sin color'}</div>
                                </div>
                                <div class="col-md-8">
                                    <h4>${response.banco.nombre}</h4>
                                    <p>Código: ${response.banco.codigo}</p>
                                    <p>Cuentas: ${response.cuentas?.length || 0}</p>
                                </div>
                            </div>
                            <hr>
                            <h5>Cuentas Bancarias</h5>
                            <div style="max-height:300px;overflow-y:auto">${cuentasHtml}</div>
                        `);
                    },
                    error: function() {
                        content.html(
                            `<div class="text-center py-5 text-danger"><i class="ri-error-warning-line fs-1"></i><p>Error al cargar</p></div>`
                            );
                    }
                });
            });

            // Función para cargar resultados (CORREGIDA)
            function loadResults(search = '') {
                if (isProcessing) return;
                isProcessing = true;
                $.ajax({
                    url: "{{ route('admin.bancos.listar') }}",
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#bancosTableBody').html(`
                            <tr><td colspan="7" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2 text-muted">Cargando resultados...</p>
                            </td></tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#bancosTableBody').html(response.html);
                        }
                        if (response.pagination) {
                            $('.pagination-container').html(response.pagination);
                        }
                        if (response.total !== undefined) {
                            $('.results-count').html(
                                `Mostrando <span class="fw-medium">${response.from || 0}</span> a 
                                 <span class="fw-medium">${response.to || 0}</span> de 
                                 <span class="fw-medium">${response.total}</span> resultados`
                            );
                            $('#totalBancosCounter').text(response.total);
                        }
                        if (response.stats) {
                            $('#totalBancosCounter').text(response.stats.totalBancos || response.total);
                            $('#bancosConCuentas').text(response.stats.bancosConCuentas || 0);
                            $('#totalCuentas').text(response.stats.totalCuentas || 0);
                        }
                        initTooltips();
                    },
                    error: function() {
                        showNotification('error', 'Error al cargar los resultados');
                        $('#bancosTableBody').html(`
                            <tr><td colspan="7" class="text-center py-5 text-danger">
                                <i class="ri-error-warning-line display-5"></i>
                                <p class="mt-2">Error al cargar los datos</p>
                            </td></tr>
                        `);
                    },
                    complete: function() {
                        isProcessing = false;
                    }
                });
            }

            // Búsqueda con debounce
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

            // Paginación AJAX
            $(document).on('click', '.pagination-container .pagination a', function(e) {
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
                    beforeSend: function() {
                        $('#bancosTableBody').html(`
                            <tr><td colspan="7" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2 text-muted">Cargando página...</p>
                            </td></tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) $('#bancosTableBody').html(response.html);
                        if (response.pagination) $('.pagination-container').html(response
                            .pagination);
                        if (response.total !== undefined) {
                            $('.results-count').html(
                                `Mostrando <span class="fw-medium">${response.from || 0}</span> a 
                                 <span class="fw-medium">${response.to || 0}</span> de 
                                 <span class="fw-medium">${response.total}</span> resultados`
                            );
                        }
                        initTooltips();
                        window.history.pushState({}, '', url + (search ? '&search=' +
                            encodeURIComponent(search) : ''));
                    },
                    error: function() {
                        showNotification('error', 'Error al cargar la página');
                    }
                });
            });

            function showNotification(type, message) {
                let toastContainer = $('#toast-container');
                if (toastContainer.length === 0) {
                    toastContainer = $(
                        '<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999"></div>'
                        );
                    $('body').append(toastContainer);
                }
                const toast = $(`
                    <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">${message}</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
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
                return new Intl.NumberFormat('es-BO', {
                    style: 'currency',
                    currency: currency === 'USD' ? 'USD' : 'BOB'
                }).format(amount);
            }
        });
    </script>
@endpush
