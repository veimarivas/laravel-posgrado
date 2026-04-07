@extends('admin.dashboard')
@section('admin')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap');

        :root {
            --plane-primary: #0f766e;
            --plane-primary-light: #f0fdfa;
            --plane-primary-dark: #0d5f59;
            --plane-accent: #f59e0b;
            --plane-accent-light: #fffbeb;
            --plane-surface: #f8fafc;
            --plane-border: #e2e8f0;
            --plane-text: #1e293b;
            --plane-text-muted: #64748b;
            --plane-success: #10b981;
            --plane-danger: #ef4444;
            --plane-info: #3b82f6;
            --plane-warning: #f59e0b;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.05);
            --shadow-lg: 0 10px 25px -3px rgba(0,0,0,0.08), 0 4px 6px -4px rgba(0,0,0,0.04);
        }

        .planes-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--plane-text);
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Page Header */
        .planes-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 28px;
            padding: 24px 28px;
            background: linear-gradient(135deg, var(--plane-primary) 0%, var(--plane-primary-dark) 100%);
            border-radius: var(--radius-lg);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .planes-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .planes-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 20%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .planes-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.65rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1;
            color: white;
        }

        .planes-header h1 i {
            color: white;
        }

        .planes-header p {
            margin: 4px 0 0;
            opacity: 0.85;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
            color: white;
        }

        .btn-new-plan {
            background: white;
            color: var(--plane-primary);
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

        .btn-new-plan:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: var(--plane-primary-light);
        }

        /* Stats Cards */
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
            border: 1px solid var(--plane-border);
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

        .stat-icon.total { background: var(--plane-primary-light); color: var(--plane-primary); }
        .stat-icon.enabled { background: #ecfdf5; color: var(--plane-success); }
        .stat-icon.featured { background: var(--plane-accent-light); color: var(--plane-accent); }
        .stat-icon.promo { background: #eff6ff; color: var(--plane-info); }

        .stat-info p {
            margin: 0;
            font-size: 0.8rem;
            color: var(--plane-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 500;
        }

        .stat-info h3 {
            margin: 2px 0 0;
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--plane-text);
        }

        /* Filter Bar */
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
            border: 1px solid var(--plane-border);
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
            color: var(--plane-text-muted);
            font-size: 1.1rem;
        }

        .search-wrapper input {
            width: 100%;
            padding: 10px 14px 10px 42px;
            border: 1px solid var(--plane-border);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s ease;
            background: var(--plane-surface);
        }

        .search-wrapper input:focus {
            outline: none;
            border-color: var(--plane-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
            background: white;
        }

        .filter-pills {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .filter-pill {
            padding: 8px 16px;
            border: 1px solid var(--plane-border);
            border-radius: 50px;
            background: white;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--plane-text-muted);
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .filter-pill:hover {
            border-color: var(--plane-primary);
            color: var(--plane-primary);
        }

        .filter-pill.active {
            background: var(--plane-primary);
            color: white;
            border-color: var(--plane-primary);
        }

        .btn-clear-filters {
            padding: 8px 16px;
            border: 1px solid var(--plane-border);
            border-radius: var(--radius-sm);
            background: white;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--plane-text-muted);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-clear-filters:hover {
            background: var(--plane-surface);
            color: var(--plane-text);
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--plane-border);
            overflow: hidden;
        }

        .table-card-header {
            padding: 18px 24px;
            border-bottom: 1px dashed var(--plane-border);
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

        /* TABLE - No horizontal scroll */
        .table-responsive {
            overflow-x: visible !important;
        }

        .planes-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .planes-table thead th {
            background: var(--plane-surface);
            padding: 12px 16px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--plane-text-muted);
            border-bottom: 1px solid var(--plane-border);
            white-space: normal;
            vertical-align: middle;
        }

        .planes-table tbody tr {
            transition: background 0.15s ease;
        }

        .planes-table tbody tr:hover {
            background: var(--plane-primary-light);
        }

        .planes-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--plane-border);
            vertical-align: middle;
            white-space: normal;
            font-size: 0.88rem;
        }

        .planes-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Plan name cell */
        .plan-name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .plan-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--plane-primary-light);
            color: var(--plane-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .plan-name-text h6 {
            margin: 0;
            font-weight: 600;
            font-size: 0.92rem;
            color: var(--plane-text);
        }

        /* Status badges */
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

        .status-badge.yes { background: #ecfdf5; color: #059669; }
        .status-badge.no { background: #f1f5f9; color: #94a3b8; }
        .status-badge.featured { background: var(--plane-accent-light); color: #d97706; }
        .status-badge.promo-active { background: #ecfdf5; color: #059669; }
        .status-badge.promo-upcoming { background: #eff6ff; color: #2563eb; }
        .status-badge.promo-expired { background: #fef2f2; color: #dc2626; }
        .status-badge.promo-na { background: #f1f5f9; color: #94a3b8; }

        /* Action buttons */
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

        /* Pagination */
        .table-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--plane-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            background: var(--plane-surface);
        }

        .table-footer .results-count {
            font-size: 0.85rem;
            color: var(--plane-text-muted);
        }

        .pagination .page-link {
            border-radius: var(--radius-sm) !important;
            border: 1px solid var(--plane-border);
            color: var(--plane-text-muted);
            font-size: 0.85rem;
            padding: 6px 12px;
            margin: 0 2px;
        }

        .pagination .page-item.active .page-link {
            background: var(--plane-primary);
            border-color: var(--plane-primary);
            color: white;
        }

        .pagination .page-link:hover {
            background: var(--plane-primary-light);
            border-color: var(--plane-primary);
            color: var(--plane-primary);
        }

        /* Empty state */
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
            color: var(--plane-text-muted);
            margin: 0;
        }

        /* Modal styling */
        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .modal-header {
            border-bottom: 1px solid var(--plane-border);
            padding: 16px 24px;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            border-top: 1px solid var(--plane-border);
            padding: 16px 24px;
        }

        .form-control, .form-select {
            border-radius: var(--radius-sm);
            border: 1px solid var(--plane-border);
            padding: 10px 14px;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: var(--plane-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        }

        .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--plane-text);
            margin-bottom: 6px;
        }

        .form-check-input:checked {
            background-color: var(--plane-primary);
            border-color: var(--plane-primary);
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .planes-header {
                padding: 20px;
            }
            .planes-header h1 {
                font-size: 1.35rem;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 767.98px) {
            .planes-header {
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
            .filter-pills {
                justify-content: flex-start;
            }
            .table-footer {
                flex-direction: column;
                align-items: center;
            }
            .planes-table thead th,
            .planes-table tbody td {
                padding: 10px 10px;
                font-size: 0.8rem;
            }
            .plan-avatar {
                width: 32px;
                height: 32px;
                font-size: 0.9rem;
            }
            .plan-name-text h6 {
                font-size: 0.82rem;
            }
            .status-badge {
                font-size: 0.68rem;
                padding: 3px 8px;
            }
        }

        /* Toast */
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
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
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
            to { transform: rotate(360deg); }
        }

        .form-switch .form-check-input {
            width: 2.8em;
            height: 1.4em;
        }
    </style>

    <div class="container-fluid planes-page">
        <!-- Page Header -->
        <div class="planes-header">
            <div>
                <h1><i class="ri-stack-line me-2"></i>Gestión de Planes de Pago</h1>
                <p>Administra los planes de pago disponibles para los estudiantes</p>
            </div>
            @if (Auth::guard('web')->user()->can('planes.pagos.registrar'))
                <button type="button" class="btn btn-new-plan" data-bs-toggle="modal" data-bs-target="#registrar">
                    <i class="ri-add-line me-1"></i> Nuevo Plan
                </button>
            @endif
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total"><i class="ri-money-dollar-circle-line"></i></div>
                <div class="stat-info">
                    <p>Total Planes</p>
                    <h3 id="totalPlanesCounter">{{ $planes->total() }}</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon enabled"><i class="ri-checkbox-circle-line"></i></div>
                <div class="stat-info">
                    <p>Habilitados</p>
                    <h3 id="totalHabilitados">0</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon featured"><i class="ri-star-line"></i></div>
                <div class="stat-info">
                    <p>Principal</p>
                    <h3 id="totalPrincipales">0</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon promo"><i class="ri-percent-line"></i></div>
                <div class="stat-info">
                    <p>Promociones</p>
                    <h3 id="totalPromociones">0</h3>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <div class="search-wrapper">
                <i class="ri-search-line"></i>
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar plan de pago..." value="{{ request('search') ?? '' }}">
            </div>
            <div class="filter-pills">
                <span class="filter-pill active" data-filter="all">Todos</span>
                <span class="filter-pill" data-filter="habilitado">Habilitados</span>
                <span class="filter-pill" data-filter="principal">Principal</span>
                <span class="filter-pill" data-filter="promocion">Promociones</span>
                <span class="filter-pill" data-filter="vigente">Vigentes</span>
            </div>
            <button type="button" id="clearFilters" class="btn-clear-filters">
                <i class="ri-refresh-line me-1"></i> Limpiar
            </button>
        </div>

        <!-- Table -->
        <div class="table-card">
            <div class="table-card-header">
                <h5><i class="ri-list-check me-2 text-muted"></i>Listado de Planes de Pago</h5>
            </div>
            <div class="table-responsive">
                <table class="planes-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Plan</th>
                            <th width="14%">Estado</th>
                            <th width="14%">Promoción</th>
                            <th width="18%">Vigencia</th>
                            <th width="16%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="planesTableBody">
                        @include('admin.planes.partials.table-body')
                    </tbody>
                </table>
            </div>
            @if ($planes->total() > 0)
                <div class="table-footer">
                    <div class="results-count">
                        Mostrando <span class="fw-medium">{{ $planes->firstItem() }}</span> a
                        <span class="fw-medium">{{ $planes->lastItem() }}</span> de
                        <span class="fw-medium">{{ $planes->total() }}</span> resultados
                    </div>
                    <div class="pagination-container">
                        {{ $planes->appends(request()->input())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Registrar -->
    <div class="modal fade" id="registrar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--plane-primary-light);">
                    <h5 class="modal-title" style="color: var(--plane-primary); font-weight: 600;">
                        <i class="ri-add-line me-2"></i>Registrar Nuevo Plan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre_registro" class="form-label">Nombre del Plan <span class="text-danger">*</span></label>
                            <input type="text" id="nombre_registro" name="nombre" class="form-control" placeholder="Ej: Plan Básico, Plan Premium" required>
                            <div class="invalid-feedback">Por favor ingresa el nombre del plan</div>
                            <small id="feedback_registro" class="form-text mt-1"></small>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="habilitado_registro" name="habilitado" value="1" checked>
                                    <label class="form-check-label" for="habilitado_registro">Habilitado</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="principal_registro" name="principal" value="1">
                                    <label class="form-check-label" for="principal_registro">Principal</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="es_promocion_registro" name="es_promocion" value="1">
                                    <label class="form-check-label" for="es_promocion_registro">Es Promoción</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-3" id="fechas_promocion_container" style="display: none;">
                            <div class="border rounded p-3" style="background: var(--plane-surface);">
                                <h6 class="mb-3" style="font-weight: 600;">Datos de la Promoción</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="fecha_inicio_promocion_registro" class="form-label">Fecha Inicio <span class="text-danger">*</span></label>
                                            <input type="date" id="fecha_inicio_promocion_registro" name="fecha_inicio_promocion" class="form-control">
                                            <div class="invalid-feedback">Por favor ingresa la fecha de inicio</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="fecha_fin_promocion_registro" class="form-label">Fecha Fin <span class="text-danger">*</span></label>
                                            <input type="date" id="fecha_fin_promocion_registro" name="fecha_fin_promocion" class="form-control">
                                            <div class="invalid-feedback">Por favor ingresa la fecha de fin</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn addBtn" style="background: var(--plane-primary); color: white;" disabled>
                            <i class="ri-save-3-line me-1"></i> Registrar Plan
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
                <div class="modal-header" style="background: var(--plane-accent-light);">
                    <h5 class="modal-title" style="color: #b45309; font-weight: 600;">
                        <i class="ri-edit-line me-2"></i>Editar Plan de Pago
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="planeId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre_edicion" class="form-label">Nombre del Plan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre_edicion" name="nombre" required>
                            <div class="invalid-feedback">Por favor ingresa el nombre del plan</div>
                            <small id="feedback_edicion" class="form-text mt-1"></small>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="habilitado_edicion" name="habilitado" value="1">
                                    <label class="form-check-label" for="habilitado_edicion">Habilitado</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="principal_edicion" name="principal" value="1">
                                    <label class="form-check-label" for="principal_edicion">Principal</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="es_promocion_edicion" name="es_promocion" value="1">
                                    <label class="form-check-label" for="es_promocion_edicion">Es Promoción</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-3" id="fechas_promocion_container_edicion">
                            <div class="border rounded p-3" style="background: var(--plane-surface);">
                                <h6 class="mb-3" style="font-weight: 600;">Datos de la Promoción</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="fecha_inicio_promocion_edicion" class="form-label">Fecha Inicio <span class="text-danger">*</span></label>
                                            <input type="date" id="fecha_inicio_promocion_edicion" name="fecha_inicio_promocion" class="form-control">
                                            <div class="invalid-feedback">Por favor ingresa la fecha de inicio</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="fecha_fin_promocion_edicion" class="form-label">Fecha Fin <span class="text-danger">*</span></label>
                                            <input type="date" id="fecha_fin_promocion_edicion" name="fecha_fin_promocion" class="form-control">
                                            <div class="invalid-feedback">Por favor ingresa la fecha de fin</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn updateBtn" style="background: var(--plane-accent); color: white;" disabled>
                            <i class="ri-refresh-line me-1"></i> Actualizar Plan
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
                            <div style="width: 64px; height: 64px; margin: 0 auto; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="ri-alert-line" style="font-size: 1.8rem; color: #dc2626;"></i>
                            </div>
                        </div>
                        <h5 style="font-weight: 600;">¿Estás seguro de eliminar este plan?</h5>
                        <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btnDelete" style="background: var(--plane-danger); color: white;">
                            <i class="ri-delete-bin-line me-1"></i> Sí, Eliminar
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

        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            let debounceTimer;
            let isProcessing = false;
            let currentFilter = 'all';

            // Inicializar tooltips
            $('[data-bs-toggle="tooltip"]').each(function() {
                new bootstrap.Tooltip(this);
            });

            // Resetear formularios cuando se cierran los modales
            $('#registrar, #modalModificar, #modalEliminar').on('hidden.bs.modal', function() {
                if (this.id === 'registrar') {
                    $('#addForm')[0].reset();
                    $('#habilitado_registro').prop('checked', true);
                    $('#fechas_promocion_container').hide();
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

            // Manejar checkbox "Es Promoción" en registro
            $('#es_promocion_registro').change(function() {
                if ($(this).is(':checked')) {
                    $('#fechas_promocion_container').slideDown();
                    $('#fecha_inicio_promocion_registro').prop('required', true);
                    $('#fecha_fin_promocion_registro').prop('required', true);
                } else {
                    $('#fechas_promocion_container').slideUp();
                    $('#fecha_inicio_promocion_registro').prop('required', false);
                    $('#fecha_fin_promocion_registro').prop('required', false);
                }
            });

            // Manejar checkbox "Es Promoción" en edición
            $('#es_promocion_edicion').change(function() {
                if ($(this).is(':checked')) {
                    $('#fechas_promocion_container_edicion').slideDown();
                    $('#fecha_inicio_promocion_edicion').prop('required', true);
                    $('#fecha_fin_promocion_edicion').prop('required', true);
                } else {
                    $('#fechas_promocion_container_edicion').slideUp();
                    $('#fecha_inicio_promocion_edicion').prop('required', false);
                    $('#fecha_fin_promocion_registro').prop('required', false);
                }
            });

            // Verificar nombre al registrar
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

                if (nombre.length > 100) {
                    feedback.addClass('text-danger').text('El nombre no puede exceder los 100 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.planes.verificar') }}",
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
                                    '<i class="ri-error-warning-line me-1"></i> Este plan ya existe'
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

            // Verificar nombre al editar
            $('#nombre_edicion').on('input', function() {
                const nombre = $(this).val().trim();
                const feedback = $('#feedback_edicion');
                const submitBtn = $('.updateBtn');
                const id = $('#planeId').val();
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

                if (nombre.length > 100) {
                    feedback.addClass('text-danger').text('El nombre no puede exceder los 100 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.planes.verificaredicion') }}",
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
                                    '<i class="ri-error-warning-line me-1"></i> Este plan ya existe'
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

            // Editar plan - VERSIÓN MEJORADA
            $(document).on('click', '.editBtn', function() {
                const data = $(this).data('bs-obj');

                $('#planeId').val(data.id);
                $('#nombre_edicion').val(data.nombre);
                $('#habilitado_edicion').prop('checked', data.habilitado == 1);
                $('#principal_edicion').prop('checked', data.principal == 1);
                $('#es_promocion_edicion').prop('checked', data.es_promocion == 1);

                // Función para formatear fecha
                const formatDateForInput = (dateString) => {
                    if (!dateString) return '';

                    // Si ya está en formato YYYY-MM-DD
                    if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
                        return dateString;
                    }

                    // Si está en formato ISO (con T)
                    if (dateString.includes('T')) {
                        return dateString.split('T')[0];
                    }

                    // Si está en formato con espacio
                    if (dateString.includes(' ')) {
                        return dateString.split(' ')[0];
                    }

                    // Intentar crear Date object
                    try {
                        const date = new Date(dateString);
                        if (!isNaN(date.getTime())) {
                            return date.toISOString().split('T')[0];
                        }
                    } catch (e) {
                        console.error('Error formateando fecha:', e);
                    }

                    return '';
                };

                // Manejar fechas de promoción
                if (data.es_promocion == 1) {
                    // Mostrar el contenedor de fechas
                    $('#fechas_promocion_container_edicion').show();

                    // Formatear y establecer fechas
                    const fechaInicio = formatDateForInput(data.fecha_inicio_promocion);
                    const fechaFin = formatDateForInput(data.fecha_fin_promocion);

                    $('#fecha_inicio_promocion_edicion').val(fechaInicio);
                    $('#fecha_fin_promocion_edicion').val(fechaFin);

                    // Hacer requeridos los campos si es promoción
                    $('#fecha_inicio_promocion_edicion').prop('required', true);
                    $('#fecha_fin_promocion_edicion').prop('required', true);
                } else {
                    // Ocultar y limpiar campos si no es promoción
                    $('#fechas_promocion_container_edicion').hide();
                    $('#fecha_inicio_promocion_edicion').val('');
                    $('#fecha_fin_promocion_edicion').val('');
                    $('#fecha_inicio_promocion_edicion').prop('required', false);
                    $('#fecha_fin_promocion_edicion').prop('required', false);
                }

                // Reset y activar verificación
                $('#feedback_edicion').removeClass('text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
                $('#updateForm').removeClass('was-validated');
                $('#nombre_edicion').removeClass('is-valid is-invalid');

                // Forzar verificación
                setTimeout(() => {
                    $('#nombre_edicion').trigger('input');
                }, 100);
            });

            // Función para manejar el cambio del checkbox "Es Promoción" en edición
            $(document).ready(function() {
                // Esta función ya debería existir, asegurarse de que funcione
                $('#es_promocion_edicion').change(function() {
                    if ($(this).is(':checked')) {
                        $('#fechas_promocion_container_edicion').slideDown();
                        $('#fecha_inicio_promocion_edicion').prop('required', true);
                        $('#fecha_fin_promocion_edicion').prop('required', true);
                    } else {
                        $('#fechas_promocion_container_edicion').slideUp();
                        $('#fecha_inicio_promocion_edicion').prop('required', false);
                        $('#fecha_fin_promocion_edicion').prop('required', false);
                    }
                });
            });

            // Eliminar plan
            $(document).on('click', '.deleteBtn', function() {
                const data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);
            });

            // REGISTRAR PLAN
            $('#addForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                const form = $(this)[0];
                if (!form.checkValidity()) {
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }

                // Validar fechas si es promoción
                if ($('#es_promocion_registro').is(':checked')) {
                    const fechaInicio = $('#fecha_inicio_promocion_registro').val();
                    const fechaFin = $('#fecha_fin_promocion_registro').val();

                    if (!fechaInicio || !fechaFin) {
                        showToast('error', 'Debe ingresar ambas fechas para la promoción');
                        return;
                    }

                    if (new Date(fechaFin) <= new Date(fechaInicio)) {
                        showToast('error', 'La fecha de fin debe ser posterior a la fecha de inicio');
                        return;
                    }
                }

                isProcessing = true;
                const submitBtn = $('.addBtn');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...');

                $.ajax({
                    url: "{{ route('admin.planes.registrar') }}",
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
                                loadResults($('#searchInput').val().trim(),
                                    currentFilter);
                            }, 500);
                        } else {
                            showToast('error', res.msg || 'Error al registrar el plan');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar el plan. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).flat().join(
                                '<br>');
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

            // ACTUALIZAR PLAN
            $('#updateForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                const form = $(this)[0];
                if (!form.checkValidity()) {
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }

                // Validar fechas si es promoción
                if ($('#es_promocion_edicion').is(':checked')) {
                    const fechaInicio = $('#fecha_inicio_promocion_edicion').val();
                    const fechaFin = $('#fecha_fin_promocion_edicion').val();

                    if (!fechaInicio || !fechaFin) {
                        showToast('error', 'Debe ingresar ambas fechas para la promoción');
                        return;
                    }

                    if (new Date(fechaFin) <= new Date(fechaInicio)) {
                        showToast('error', 'La fecha de fin debe ser posterior a la fecha de inicio');
                        return;
                    }
                }

                isProcessing = true;
                const submitBtn = $('.updateBtn');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...');

                $.ajax({
                    url: "{{ route('admin.planes.modificar') }}",
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
                                loadResults($('#searchInput').val().trim(),
                                    currentFilter);
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al actualizar el plan');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al actualizar el plan. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).flat().join(
                                '<br>');
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

            // ELIMINAR PLAN
            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                isProcessing = true;
                const submitBtn = $('.btnDelete');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...');

                $.ajax({
                    url: "{{ route('admin.planes.eliminar') }}",
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
                                loadResults($('#searchInput').val().trim(),
                                    currentFilter);
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al eliminar el plan');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al eliminar el plan. Intenta nuevamente.';
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
            function loadResults(search = '', filter = 'all') {
                if (isProcessing) return;
                isProcessing = true;

                $.ajax({
                    url: '{{ route('admin.planes.listar') }}',
                    method: 'GET',
                    data: {
                        search: search,
                        filter: filter
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#planesTableBody').html(`
                        <tr>
                            <td colspan="6" class="text-center py-5">
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
                            $('#planesTableBody').html(response.html);

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
                                $('#totalPlanesCounter').text(response.total);
                                $('#totalHabilitados').text(response.habilitados || 0);
                                $('#totalPrincipales').text(response.principales || 0);
                                $('#totalPromociones').text(response.promociones || 0);
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
                        $('#planesTableBody').html(`
                        <tr>
                            <td colspan="6" class="text-center py-5 text-danger">
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
                    loadResults(searchTerm, currentFilter);
                }, 500);
            });

            // Filtros
            $(document).on('click', '.filter-pill', function(e) {
                e.preventDefault();
                currentFilter = $(this).data('filter');
                $('.filter-pill').removeClass('active');
                $(this).addClass('active');
                loadResults($('#searchInput').val().trim(), currentFilter);
            });

            // Limpiar filtros
            $('#clearFilters').on('click', function() {
                $('#searchInput').val('');
                currentFilter = 'all';
                $('.filter-pill').removeClass('active');
                $('.filter-pill[data-filter="all"]').addClass('active');
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
                        search: search,
                        filter: currentFilter
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#planesTableBody').html(`
                        <tr>
                            <td colspan="6" class="text-center py-5">
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
                            $('#planesTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium">${response.from || 0}</span> a 
                                 <span class="fw-medium">${response.to || 0}</span> de 
                                 <span class="fw-medium">${response.total}</span> resultados`
                                );

                                $('#totalPlanesCounter').text(response.total);
                                $('#totalHabilitados').text(response.habilitados || 0);
                                $('#totalPrincipales').text(response.principales || 0);
                                $('#totalPromociones').text(response.promociones || 0);
                            }

                            // Actualizar URL sin recargar
                            const newUrl = url + (search ? '&search=' + encodeURIComponent(
                                search) : '') + (currentFilter !== 'all' ? '&filter=' +
                                currentFilter : '');
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

            // Inicializar contadores
            setTimeout(() => {
                $.ajax({
                    url: '{{ route('admin.planes.listar') }}',
                    method: 'GET',
                    data: {
                        stats: true
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.stats) {
                            $('#totalHabilitados').text(response.stats.habilitados || 0);
                            $('#totalPrincipales').text(response.stats.principales || 0);
                            $('#totalPromociones').text(response.stats.promociones || 0);
                        }
                    }
                });
            }, 1000);
        });
    </script>
@endpush
