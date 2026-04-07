@extends('admin.dashboard')
@section('admin')
    <style>
        :root {
            --pos-primary: #0f766e;
            --pos-primary-light: #f0fdfa;
            --pos-primary-dark: #0d5f59;
            --pos-accent: #f59e0b;
            --pos-accent-light: #fffbeb;
            --pos-surface: #f8fafc;
            --pos-border: #e2e8f0;
            --pos-text: #1e293b;
            --pos-text-muted: #64748b;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.05);
        }

        .posgrados-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--pos-text);
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Page Header */
        .posgrados-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 28px;
            padding: 24px 28px;
            background: linear-gradient(135deg, var(--pos-primary) 0%, var(--pos-primary-dark) 100%);
            border-radius: var(--radius-lg);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .posgrados-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .posgrados-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.65rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1;
            color: white;
        }

        .posgrados-header h1 i { color: white; }

        .posgrados-header p {
            margin: 4px 0 0;
            opacity: 0.85;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
            color: white;
        }

        .btn-new-posgrado {
            background: white;
            color: var(--pos-primary);
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

        .btn-new-posgrado:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: var(--pos-primary-light);
        }

        /* Filter Bar */
        .filter-bar {
            background: white;
            border-radius: var(--radius-md);
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-end;
            gap: 12px;
            flex-wrap: wrap;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--pos-border);
        }

        .filter-group {
            flex: 1;
            min-width: 160px;
        }

        .filter-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--pos-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--pos-border);
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s ease;
            background: var(--pos-surface);
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: var(--pos-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
            background: white;
        }

        .search-wrapper {
            flex: 2;
            min-width: 220px;
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--pos-text-muted);
            font-size: 1rem;
            z-index: 2;
            pointer-events: none;
        }

        .search-wrapper input.search-input-custom {
            width: 100%;
            padding: 8px 12px 8px 36px;
            border: 1px solid var(--pos-border);
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s ease;
            background: var(--pos-surface);
            color: var(--pos-text);
        }

        .search-wrapper input.search-input-custom:focus {
            outline: none;
            border-color: var(--pos-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
            background: white;
        }

        .search-wrapper input.search-input-custom::placeholder {
            color: var(--pos-text-muted);
        }

        .btn-clear {
            padding: 8px 18px;
            border: 1px solid var(--pos-border);
            border-radius: var(--radius-sm);
            background: white;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--pos-text-muted);
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-clear:hover {
            background: var(--pos-surface);
            color: var(--pos-text);
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--pos-border);
            overflow: hidden;
        }

        .table-card-header {
            padding: 18px 24px;
            border-bottom: 1px dashed var(--pos-border);
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

        .posgrados-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .posgrados-table thead th {
            background: var(--pos-surface);
            padding: 12px 14px;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--pos-text-muted);
            border-bottom: 1px solid var(--pos-border);
            white-space: normal;
            vertical-align: middle;
        }

        .posgrados-table tbody tr {
            transition: background 0.15s ease;
        }

        .posgrados-table tbody tr:hover {
            background: var(--pos-primary-light);
        }

        .posgrados-table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--pos-border);
            vertical-align: middle;
            white-space: normal;
            font-size: 0.85rem;
        }

        .posgrados-table tbody tr:last-child td {
            border-bottom: none;
        }

        .posgrado-name-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .posgrado-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: var(--pos-primary-light);
            color: var(--pos-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .posgrado-name-text {
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--pos-text);
            line-height: 1.3;
        }

        .info-stacked {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .info-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .info-badge.convenio { background: #eff6ff; color: #2563eb; }
        .info-badge.area { background: #f0fdf4; color: #16a34a; }
        .info-badge.tipo { background: #fef3c7; color: #d97706; }

        .detail-stacked {
            display: flex;
            flex-direction: column;
            gap: 2px;
            font-size: 0.78rem;
        }

        .detail-row {
            display: flex;
            align-items: center;
            gap: 4px;
            color: var(--pos-text-muted);
        }

        .detail-row i {
            font-size: 0.85rem;
            width: 14px;
            text-align: center;
        }

        .detail-row strong {
            color: var(--pos-text);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.72rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-badge.active { background: #ecfdf5; color: #059669; }
        .status-badge.inactive { background: #fef2f2; color: #dc2626; }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .action-btn.view { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
        .action-btn.view:hover { background: #bfdbfe; transform: translateY(-1px); }
        .action-btn.offer { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
        .action-btn.offer:hover { background: #a7f3d0; transform: translateY(-1px); }
        .action-btn.edit { background: #fffbeb; color: #d97706; border-color: #fde68a; }
        .action-btn.edit:hover { background: #fde68a; transform: translateY(-1px); }
        .action-btn.delete { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
        .action-btn.delete:hover { background: #fecaca; transform: translateY(-1px); }

        .table-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--pos-border);
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--pos-surface);
        }

        .pagination .page-link {
            border-radius: var(--radius-sm) !important;
            border: 1px solid var(--pos-border);
            color: var(--pos-text-muted);
            font-size: 0.82rem;
            padding: 6px 12px;
            margin: 0 2px;
        }

        .pagination .page-item.active .page-link {
            background: var(--pos-primary);
            border-color: var(--pos-primary);
            color: white;
        }

        .pagination .page-link:hover {
            background: var(--pos-primary-light);
            border-color: var(--pos-primary);
            color: var(--pos-primary);
        }

        .empty-state {
            padding: 48px 24px;
            text-align: center;
        }

        .empty-state i { font-size: 3.5rem; color: #cbd5e1; margin-bottom: 12px; }
        .empty-state p { color: var(--pos-text-muted); margin: 0; }

        /* Modals */
        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .modal-header {
            border-bottom: 1px solid var(--pos-border);
            padding: 16px 24px;
        }

        .modal-body { padding: 24px; }
        .modal-footer {
            border-top: 1px solid var(--pos-border);
            padding: 16px 24px;
        }

        .form-control, .form-select {
            border-radius: var(--radius-sm);
            border: 1px solid var(--pos-border);
            padding: 8px 12px;
            font-size: 0.85rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--pos-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        }

        .form-label {
            font-weight: 500;
            font-size: 0.82rem;
            color: var(--pos-text);
            margin-bottom: 4px;
        }

        .form-check-input:checked {
            background-color: var(--pos-primary);
            border-color: var(--pos-primary);
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999999 !important;
        }

        @media (max-width: 991.98px) {
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-group { min-width: 100%; }
        }

        @media (max-width: 767.98px) {
            .posgrados-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .posgrados-table thead th,
            .posgrados-table tbody td {
                padding: 8px 8px;
                font-size: 0.78rem;
            }
            .posgrado-icon { width: 30px; height: 30px; font-size: 0.85rem; }
            .posgrado-name-text { font-size: 0.8rem; }
            .info-badge { font-size: 0.65rem; padding: 1px 6px; }
        }
    </style>

    <div class="container-fluid posgrados-page">
        <!-- Page Header -->
        <div class="posgrados-header">
            <div>
                <h1><i class="ri-graduation-cap-line me-2"></i>Gestión de Posgrados</h1>
                <p>Administra los programas de posgrado disponibles</p>
            </div>
            @if (Auth::guard('web')->user()->can('posgrados.registrar'))
                <button type="button" class="btn-new-posgrado" data-bs-toggle="modal" data-bs-target="#registrar">
                    <i class="ri-add-line me-1"></i> Nuevo Posgrado
                </button>
            @endif
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <div class="search-wrapper">
                <i class="ri-search-line"></i>
                <input type="text" id="searchInput" class="search-input-custom" placeholder="Buscar posgrado...">
            </div>
            <div class="filter-group">
                <label>Área</label>
                <select id="filtroArea">
                    <option value="">Todas las áreas</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Tipo</label>
                <select id="filtroTipo">
                    <option value="">Todos los tipos</option>
                    @foreach ($tipos as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Convenio</label>
                <select id="filtroConvenio">
                    <option value="">Todos los convenios</option>
                    @foreach ($convenios as $convenio)
                        <option value="{{ $convenio->id }}">{{ $convenio->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" id="clearFilters" class="btn-clear">
                <i class="ri-refresh-line me-1"></i> Limpiar
            </button>
        </div>

        <!-- Table -->
        <div class="table-card">
            <div class="table-card-header">
                <h5><i class="ri-list-check me-2 text-muted"></i>Listado de Posgrados</h5>
            </div>
            <div class="table-responsive">
                <table class="posgrados-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Posgrado</th>
                            <th width="22%">Información</th>
                            <th width="16%">Detalles</th>
                            <th width="10%">Estado</th>
                            <th width="18%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    @include('admin.posgrados.partials.table-body')
                </table>
            </div>
            <div class="table-footer" id="pagination-container">
                {{ $posgrados->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Modal Registrar -->
    <div class="modal fade" id="registrar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--pos-primary-light);">
                    <h5 class="modal-title" style="color: var(--pos-primary); font-weight: 600;">
                        <i class="ri-add-line me-2"></i>Registrar Nuevo Posgrado
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="addForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre del Posgrado <span class="text-danger">*</span></label>
                                <input type="text" id="nombre_registro" name="nombre" class="form-control">
                                <div id="feedback_registro" class="mt-1" style="font-size: 0.82em;"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Área <span class="text-danger">*</span></label>
                                <select name="area_id" id="area_id_registro" class="form-control" required>
                                    <option value="">Seleccione un área</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tipo <span class="text-danger">*</span></label>
                                <select name="tipo_id" id="tipo_id_registro" class="form-control" required>
                                    <option value="">Seleccione un tipo</option>
                                    @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Convenio <span class="text-danger">*</span></label>
                                <select name="convenio_id" id="convenio_id_registro" class="form-control" required>
                                    <option value="">Seleccione un convenio</option>
                                    @foreach ($convenios as $convenio)
                                        <option value="{{ $convenio->id }}">{{ $convenio->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Créditos <span class="text-danger">*</span></label>
                                <input type="number" name="creditaje" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Carga Horaria (h) <span class="text-danger">*</span></label>
                                <input type="number" name="carga_horaria" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Duración <span class="text-danger">*</span></label>
                                <input type="number" name="duracion_numero" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Unidad</label>
                                <select name="duracion_unidad" class="form-control" required>
                                    <option value="Días">Días</option>
                                    <option value="Meses">Meses</option>
                                    <option value="Años">Años</option>
                                    <option value="Semanas">Semanas</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Activo</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="estado_registro" checked>
                                    <label class="form-check-label" for="estado_registro">Sí</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Dirigido a <span class="text-danger">*</span></label>
                                <textarea name="dirigido" class="form-control" rows="2" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Objetivo <span class="text-danger">*</span></label>
                                <textarea name="objetivo" class="form-control" rows="2" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn addBtn" style="background: var(--pos-primary); color: white;" disabled>
                            <i class="ri-save-3-line me-1"></i> Registrar Posgrado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade modificar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--pos-accent-light);">
                    <h5 class="modal-title" style="color: #b45309; font-weight: 600;">
                        <i class="ri-edit-line me-2"></i>Editar Posgrado
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="editForm">
                    @csrf
                    <input type="hidden" name="id" id="id_edicion">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre del Posgrado <span class="text-danger">*</span></label>
                                <input type="text" id="nombre_edicion" name="nombre" class="form-control">
                                <div id="feedback_edicion" class="mt-1" style="font-size: 0.82em;"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Área <span class="text-danger">*</span></label>
                                <select name="area_id" id="area_id_edicion" class="form-control" required>
                                    <option value="">Seleccione un área</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tipo <span class="text-danger">*</span></label>
                                <select name="tipo_id" id="tipo_id_edicion" class="form-control" required>
                                    <option value="">Seleccione un tipo</option>
                                    @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Convenio <span class="text-danger">*</span></label>
                                <select name="convenio_id" id="convenio_id_edicion" class="form-control" required>
                                    <option value="">Seleccione un convenio</option>
                                    @foreach ($convenios as $convenio)
                                        <option value="{{ $convenio->id }}">{{ $convenio->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Créditos <span class="text-danger">*</span></label>
                                <input type="number" name="creditaje" id="creditaje_edicion" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Carga Horaria (h) <span class="text-danger">*</span></label>
                                <input type="number" name="carga_horaria" id="carga_horaria_edicion" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Duración <span class="text-danger">*</span></label>
                                <input type="number" name="duracion_numero" id="duracion_numero_edicion" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Unidad</label>
                                <select name="duracion_unidad" id="duracion_unidad_edicion" class="form-control" required>
                                    <option value="Días">Días</option>
                                    <option value="Meses">Meses</option>
                                    <option value="Años">Años</option>
                                    <option value="Semanas">Semanas</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Activo</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="estado_edicion">
                                    <label class="form-check-label" for="estado_edicion">Sí</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Dirigido a <span class="text-danger">*</span></label>
                                <textarea name="dirigido" id="dirigido_edicion" class="form-control" rows="2" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Objetivo <span class="text-danger">*</span></label>
                                <textarea name="objetivo" id="objetivo_edicion" class="form-control" rows="2" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn editBtnSubmit" style="background: var(--pos-accent); color: white;" disabled>
                            <i class="ri-refresh-line me-1"></i> Actualizar Posgrado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar -->
    <div class="modal fade eliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: #fef2f2;">
                    <h5 class="modal-title" style="color: #dc2626; font-weight: 600;">
                        <i class="ri-delete-bin-line me-2"></i>Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <div style="width: 64px; height: 64px; margin: 0 auto; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-alert-line" style="font-size: 1.8rem; color: #dc2626;"></i>
                        </div>
                    </div>
                    <h5 style="font-weight: 600;">¿Está seguro de eliminar el posgrado <strong id="nombre_eliminar"></strong>?</h5>
                    <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
                    <input type="hidden" id="id_eliminar">
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn" id="confirmDelete" style="background: #dc2626; color: white;">
                        <i class="ri-delete-bin-line me-1"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Registrar Oferta Académica -->
    <div class="modal fade" id="modalRegistrarOferta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background: #ecfdf5;">
                    <h5 class="modal-title" style="color: #059669; font-weight: 600;">
                        <i class="ri-book-open-line me-2"></i>Registrar Oferta Académica
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="ofertaForm">
                        @csrf
                        <input type="hidden" name="posgrado_id" id="oferta_posgrado_id">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Sede <span class="text-danger">*</span></label>
                                <select id="sede_id" class="form-control" required>
                                    <option value="">Seleccione una sede</option>
                                    @foreach ($sedes as $sede)
                                        <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sucursal <span class="text-danger">*</span></label>
                                <select id="sucursale_id" name="sucursale_id" class="form-control" required disabled>
                                    <option value="">Seleccione una sede primero</option>
                                </select>
                                <div id="sucursal_error" class="text-danger mt-1" style="font-size: 0.82em;"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Modalidad <span class="text-danger">*</span></label>
                                <select name="modalidade_id" id="modalidade_id" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($modalidades as $m)
                                        <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Código <span class="text-danger">*</span></label>
                                <input type="text" name="codigo" id="codigo" class="form-control" required>
                                <div id="feedback_codigo" class="mt-1" style="font-size: 0.82em;"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Programa <span class="text-danger">*</span></label>
                                <input type="text" id="programa_nombre" class="form-control" placeholder="Escriba para buscar o crear un programa" required>
                                <input type="hidden" name="programa_id" id="programa_id">
                                <div id="programa_feedback" class="mt-1" style="font-size: 0.82em;"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Gestión <span class="text-danger">*</span></label>
                                <input type="text" name="gestion" id="gestion" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Responsable Académico <span class="text-danger">*</span></label>
                                <select name="responsable_academico_cargo_id" id="responsable_academico_cargo_id" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($trabajadoresAcademicos as $ta)
                                        <option value="{{ $ta->id }}">
                                            {{ $ta->trabajador->persona->nombres }} {{ $ta->trabajador->persona->apellido_paterno }}
                                            ({{ $ta->cargo->nombre }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Responsable Marketing <span class="text-danger">*</span></label>
                                <select name="responsable_marketing_cargo_id" id="responsable_marketing_cargo_id" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($trabajadoresMarketing as $tm)
                                        <option value="{{ $tm->id }}">
                                            {{ $tm->trabajador->persona->nombres }} {{ $tm->trabajador->persona->apellido_paterno }}
                                            ({{ $tm->cargo->nombre }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Color</label>
                                <div class="d-flex align-items-center gap-3 mt-1">
                                    <input type="color" id="color_registro" name="color" class="form-control form-control-color shadow-none p-1" value="#ccc">
                                    <span id="preview_registro" class="rounded-circle border d-inline-block" style="width: 32px; height: 32px; background-color: #ccc;"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fecha Inicio Inscripciones <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_inicio_inscripciones" id="fecha_inicio_inscripciones" class="form-control" required>
                                <div id="error_fecha_inicio_inscripciones" class="text-danger mt-1" style="font-size: 0.82em;"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fecha Inicio Programa <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_inicio_programa" id="fecha_inicio_programa" class="form-control" required>
                                <div id="error_fecha_inicio_programa" class="text-danger mt-1" style="font-size: 0.82em;"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fecha Fin Programa <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_fin_programa" id="fecha_fin_programa" class="form-control" required>
                                <div id="error_fecha_fin_programa" class="text-danger mt-1" style="font-size: 0.82em;"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">N° Sesiones por módulo</label>
                                <input type="number" name="cantidad_sesiones" id="n_sesiones" class="form-control" min="1" value="1">
                                <div id="error_cantidad_sesiones" class="text-danger mt-1" style="font-size: 0.82em;"></div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">N° Módulos</label>
                                <input type="number" name="n_modulos" id="n_modulos" class="form-control" min="1" value="1">
                                <div id="error_n_modulos" class="text-danger mt-1" style="font-size: 0.82em;"></div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Versión</label>
                                <input type="text" name="version" id="version" class="form-control" value="1">
                                <div id="feedback_version" class="mt-1" style="font-size: 0.82em;"></div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Grupo</label>
                                <input type="text" name="grupo" id="grupo" class="form-control" value="1">
                                <div id="feedback_grupo" class="mt-1" style="font-size: 0.82em;"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Nota mínima</label>
                                <input type="number" name="nota_minima" id="nota_minima" class="form-control" value="61" min="1" max="100">
                                <div id="error_nota_minima" class="text-danger mt-1" style="font-size: 0.82em;"></div>
                            </div>
                            <!-- Imágenes -->
                            <div class="col-md-6">
                                <div class="card h-100" style="border: 1px solid var(--pos-border); border-radius: var(--radius-md);">
                                    <div class="card-header" style="background: var(--pos-primary-light); border-bottom: 1px solid var(--pos-border);">
                                        <i class="ri-image-line me-1" style="color: var(--pos-primary);"></i>
                                        <span style="color: var(--pos-primary); font-weight: 600; font-size: 0.85rem;">Portada del Programa</span>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <img id="preview_portada" src="#" alt="Vista previa" class="img-fluid rounded" style="max-height: 150px; object-fit: contain; display: none;">
                                            <div id="placeholder_portada" class="text-muted py-3">
                                                <i class="ri-upload-cloud-2-line fs-4"></i><br>
                                                <small>Seleccione una imagen</small>
                                            </div>
                                        </div>
                                        <input type="file" name="portada" id="portada_input" class="form-control" accept="image/png,image/jpeg,image/jpg">
                                        <div id="portada_error" class="text-danger mt-1" style="font-size: 0.82em;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100" style="border: 1px solid var(--pos-border); border-radius: var(--radius-md);">
                                    <div class="card-header" style="background: #ecfdf5; border-bottom: 1px solid var(--pos-border);">
                                        <i class="ri-certificate-line me-1" style="color: #059669;"></i>
                                        <span style="color: #059669; font-weight: 600; font-size: 0.85rem;">Diseño del Certificado</span>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <img id="preview_certificado" src="#" alt="Vista previa" class="img-fluid rounded" style="max-height: 150px; object-fit: contain; display: none;">
                                            <div id="placeholder_certificado" class="text-muted py-3">
                                                <i class="ri-upload-cloud-2-line fs-4"></i><br>
                                                <small>Seleccione una imagen</small>
                                            </div>
                                        </div>
                                        <input type="file" name="certificado" id="certificado_input" class="form-control" accept="image/png,image/jpeg,image/jpg">
                                        <div id="certificado_error" class="text-danger mt-1" style="font-size: 0.82em;"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Módulos -->
                            <div class="col-12">
                                <h6 style="font-weight: 600; border-bottom: 1px solid var(--pos-border); padding-bottom: 8px;">
                                    <i class="ri-book-open-line me-1" style="color: var(--pos-primary);"></i> Módulos del Programa
                                </h6>
                                <p class="text-muted small mb-3">Se generarán automáticamente según el número de módulos indicado.</p>
                                <div id="modulos-container"></div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <div id="oferta_validation_summary" class="mb-3" style="font-size: 0.85rem;"></div>
                            <button type="submit" class="btn" id="submitOfertaBtn" disabled style="background: var(--pos-primary); color: white;">
                                <i class="ri-save-3-line me-1"></i> Registrar Oferta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const PLANES_PAGOS = @json($planesPagos);
    const CONCEPTOS = @json($conceptos);
</script>
@endsection

@push('script')
<script>
$(document).ready(function() {
    let debounceTimer;

    function validateFormRegistro() {
        const nombre = $('#nombre_registro').val().trim();
        const areaId = $('#area_id_registro').val();
        const tipoId = $('#tipo_id_registro').val();
        const convenioId = $('#convenio_id_registro').val();
        const creditaje = $('input[name="creditaje"]').val();
        const carga = $('input[name="carga_horaria"]').val();
        const duracion = $('input[name="duracion_numero"]').val();
        const dirigido = $('textarea[name="dirigido"]').val().trim();
        const objetivo = $('textarea[name="objetivo"]').val().trim();
        const allFilled = nombre && areaId && tipoId && convenioId &&
            creditaje !== '' && carga !== '' && duracion !== '' && dirigido && objetivo;
        const submitBtn = $('.addBtn');
        const feedback = $('#feedback_registro');
        if (!allFilled) {
            submitBtn.prop('disabled', true);
            feedback.removeClass('text-success text-danger').text('');
            return;
        }
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            $.ajax({
                url: "{{ route('admin.posgrados.verificar') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    nombre: nombre,
                    area_id: areaId,
                    tipo_id: tipoId,
                    convenio_id: convenioId
                },
                success: function(res) {
                    if (res.exists) {
                        feedback.removeClass('text-success').addClass('text-danger').text('Ya existe un posgrado con esta combinación.');
                        submitBtn.prop('disabled', true);
                    } else {
                        feedback.removeClass('text-danger').addClass('text-success').text('Combinación disponible.');
                        submitBtn.prop('disabled', false);
                    }
                },
                error: function() {
                    feedback.removeClass('text-success').addClass('text-danger').text('Error al verificar.');
                    submitBtn.prop('disabled', true);
                }
            });
        }, 400);
    }

    $('#nombre_registro, #area_id_registro, #tipo_id_registro, #convenio_id_registro, input[name="creditaje"], input[name="carga_horaria"], input[name="duracion_numero"], textarea[name="dirigido"], textarea[name="objetivo"]')
        .on('input change', validateFormRegistro);

    $('.addBtn').prop('disabled', true);

    $('#addForm').submit(function(e) {
        e.preventDefault();
        const estadoVal = $('#estado_registro').is(':checked') ? 'activo' : 'inactivo';
        $('input[name="estado"]').remove();
        $(this).append(`<input type="hidden" name="estado" value="${estadoVal}">`);
        $('.addBtn').prop('disabled', true);
        const originalText = $('.addBtn').html();
        $('.addBtn').html('<span class="spinner-border spinner-border-sm me-2"></span>Registrando...');
        $.ajax({
            url: "{{ route('admin.posgrados.registrar') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(res) {
                if (res.success) {
                    showToast('success', res.msg);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('registrar'));
                    if (modal) modal.hide();
                    setTimeout(() => loadResults(), 500);
                } else {
                    showToast('error', res.msg || 'Error al registrar.');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error al registrar.';
                if (xhr.responseJSON?.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                }
                showToast('error', errorMsg);
            },
            complete: function() {
                $('.addBtn').prop('disabled', false).html(originalText);
            }
        });
    });

    // Filtros
    function loadResults(search = '', area = '', tipo = '', convenio = '') {
        $.ajax({
            url: '{{ route('admin.posgrados.listar') }}',
            data: {
                search: search,
                area_id: area,
                tipo_id: tipo,
                convenio_id: convenio
            },
            success: function(res) {
                $('.posgrados-table tbody').replaceWith(res.html);
                $('#pagination-container').html(res.pagination);
                if (typeof feather !== 'undefined') feather.replace();
            }
        });
    }

    $('#searchInput, #filtroArea, #filtroTipo, #filtroConvenio').on('input change', function() {
        const search = $('#searchInput').val().trim();
        const area = $('#filtroArea').val();
        const tipo = $('#filtroTipo').val();
        const convenio = $('#filtroConvenio').val();
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            loadResults(search, area, tipo, convenio);
        }, 300);
    });

    $('#clearFilters').on('click', function() {
        $('#searchInput').val('');
        $('#filtroArea').val('');
        $('#filtroTipo').val('');
        $('#filtroConvenio').val('');
        loadResults();
    });

    $(document).on('click', '#pagination-container .pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        if (!url) return;
        const search = $('#searchInput').val().trim();
        const area = $('#filtroArea').val();
        const tipo = $('#filtroTipo').val();
        const convenio = $('#filtroConvenio').val();
        const urlObj = new URL(url, window.location.origin);
        if (search) urlObj.searchParams.set('search', search);
        if (area) urlObj.searchParams.set('area_id', area);
        if (tipo) urlObj.searchParams.set('tipo_id', tipo);
        if (convenio) urlObj.searchParams.set('convenio_id', convenio);
        $.get(urlObj.toString(), function(res) {
            $('.posgrados-table tbody').replaceWith(res.html);
            $('#pagination-container').html(res.pagination);
            if (typeof feather !== 'undefined') feather.replace();
        });
    });

    // Editar
    $(document).on('click', '.editBtn', function() {
        const posgrado = $(this).data('bs-obj');
        $('#id_edicion').val(posgrado.id);
        $('#nombre_edicion').val(posgrado.nombre);
        $('#area_id_edicion').val(posgrado.area_id);
        $('#tipo_id_edicion').val(posgrado.tipo_id);
        $('#convenio_id_edicion').val(posgrado.convenio_id);
        $('#creditaje_edicion').val(posgrado.creditaje);
        $('#carga_horaria_edicion').val(posgrado.carga_horaria);
        $('#duracion_numero_edicion').val(posgrado.duracion_numero);
        $('#duracion_unidad_edicion').val(posgrado.duracion_unidad);
        $('#dirigido_edicion').val(posgrado.dirigido);
        $('#objetivo_edicion').val(posgrado.objetivo);
        $('#estado_edicion').prop('checked', posgrado.estado === 'activo');
        $('#feedback_edicion').removeClass('text-success text-danger').text('');
        $('.editBtnSubmit').prop('disabled', true);
    });

    // Validación edición
    function validateFormEdicion() {
        const nombre = $('#nombre_edicion').val().trim();
        const areaId = $('#area_id_edicion').val();
        const tipoId = $('#tipo_id_edicion').val();
        const convenioId = $('#convenio_id_edicion').val();
        const creditaje = $('#creditaje_edicion').val();
        const carga = $('#carga_horaria_edicion').val();
        const duracion = $('#duracion_numero_edicion').val();
        const dirigido = $('#dirigido_edicion').val().trim();
        const objetivo = $('#objetivo_edicion').val().trim();
        const allFilled = nombre && areaId && tipoId && convenioId &&
            creditaje !== '' && carga !== '' && duracion !== '' && dirigido && objetivo;
        const submitBtn = $('.editBtnSubmit');
        const feedback = $('#feedback_edicion');
        if (!allFilled) {
            submitBtn.prop('disabled', true);
            feedback.removeClass('text-success text-danger').text('');
            return;
        }
        submitBtn.prop('disabled', false);
        feedback.removeClass('text-success text-danger').text('');
    }

    $('#nombre_edicion, #area_id_edicion, #tipo_id_edicion, #convenio_id_edicion, #creditaje_edicion, #carga_horaria_edicion, #duracion_numero_edicion, #dirigido_edicion, #objetivo_edicion')
        .on('input change', validateFormEdicion);

    // Actualizar edición
    $('#editForm').submit(function(e) {
        e.preventDefault();
        const estadoVal = $('#estado_edicion').is(':checked') ? 'activo' : 'inactivo';
        $('input[name="estado"]').remove();
        $(this).append(`<input type="hidden" name="estado" value="${estadoVal}">`);
        $('.editBtnSubmit').prop('disabled', true);
        const originalText = $('.editBtnSubmit').html();
        $('.editBtnSubmit').html('<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...');
        $.ajax({
            url: "{{ route('admin.posgrados.modificar') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(res) {
                if (res.success) {
                    showToast('success', res.msg);
                    const modalEl = document.querySelector('.modificar.show');
                    const modal = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;
                    if (modal) modal.hide();
                    setTimeout(() => loadResults(), 500);
                } else {
                    showToast('error', res.msg || 'Error al actualizar.');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error al actualizar.';
                if (xhr.responseJSON?.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                }
                showToast('error', errorMsg);
            },
            complete: function() {
                $('.editBtnSubmit').prop('disabled', false).html(originalText);
            }
        });
    });

    // Eliminar
    $(document).on('click', '.deleteBtn', function() {
        const posgrado = $(this).data('bs-obj');
        $('#id_eliminar').val(posgrado.id);
        $('#nombre_eliminar').text(posgrado.nombre);
    });

    $('#confirmDelete').on('click', function() {
        const id = $('#id_eliminar').val();
        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...');
        $.ajax({
            url: "{{ route('admin.posgrados.eliminar') }}",
            type: "DELETE",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function(res) {
                if (res.success) {
                    showToast('success', res.msg);
                    const modalEl = document.querySelector('.eliminar.show');
                    const modal = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;
                    if (modal) modal.hide();
                    setTimeout(() => loadResults(), 500);
                } else {
                    showToast('error', res.msg || 'Error al eliminar.');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error al eliminar.';
                if (xhr.responseJSON?.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                }
                showToast('error', errorMsg);
            },
            complete: function() {
                $('#confirmDelete').prop('disabled', false).html('<i class="ri-delete-bin-line me-1"></i> Eliminar');
            }
        });
    });

    // === OFERTA ACADÉMICA ===
    // Sede → Sucursal
    $('#sede_id').on('change', function() {
        const sedeId = $(this).val();
        const sucursalSelect = $('#sucursale_id');
        if (!sedeId) {
            sucursalSelect.html('<option value="">Seleccione una sede primero</option>').prop('disabled', true);
            $('#sucursal_error').text('Seleccione una sede primero');
            return;
        }
        $('#sucursal_error').text('');
        sucursalSelect.html('<option value="">Cargando...</option>').prop('disabled', true);
        $.ajax({
            url: "{{ route('admin.sucursales.por-sede') }}",
            data: { sede_id: sedeId },
            success: function(res) {
                sucursalSelect.html('<option value="">Seleccione una sucursal</option>');
                if (res && res.length > 0) {
                    res.forEach(function(s) {
                        sucursalSelect.append(`<option value="${s.id}">${s.nombre}</option>`);
                    });
                    sucursalSelect.prop('disabled', false);
                } else {
                    sucursalSelect.html('<option value="">Sin sucursales disponibles</option>');
                }
                validateOfertaForm();
            },
            error: function() {
                sucursalSelect.html('<option value="">Error al cargar</option>');
            }
        });
    });

    $('#sucursale_id').on('change', function() {
        $('#sucursal_error').text($(this).val() ? '' : 'Seleccione una sucursal');
        validateOfertaForm();
    });

    // Color preview
    $('#color_registro').on('input', function() {
        $('#preview_registro').css('background-color', $(this).val());
        validateOfertaForm();
    });

    // Image previews
    $('#portada_input').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                $('#portada_error').text('La imagen no debe superar 2 MB');
                $(this).val('');
                return;
            }
            const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
            if (!allowedTypes.includes(file.type)) {
                $('#portada_error').text('Solo se permiten archivos PNG, JPG o JPEG');
                $(this).val('');
                return;
            }
            $('#portada_error').text('');
            const reader = new FileReader();
            reader.onload = function(ev) {
                $('#preview_portada').attr('src', ev.target.result).show();
                $('#placeholder_portada').hide();
            };
            reader.readAsDataURL(file);
        }
    });

    $('#certificado_input').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                $('#certificado_error').text('La imagen no debe superar 2 MB');
                $(this).val('');
                return;
            }
            const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
            if (!allowedTypes.includes(file.type)) {
                $('#certificado_error').text('Solo se permiten archivos PNG, JPG o JPEG');
                $(this).val('');
                return;
            }
            $('#certificado_error').text('');
            const reader = new FileReader();
            reader.onload = function(ev) {
                $('#preview_certificado').attr('src', ev.target.result).show();
                $('#placeholder_certificado').hide();
            };
            reader.readAsDataURL(file);
        }
    });

    // Modules generation
    $('#n_modulos').on('input', function() {
        const n = parseInt($(this).val()) || 0;
        const container = $('#modulos-container');
        container.empty();
        for (let i = 1; i <= n; i++) {
            container.append(`
                <div class="card mb-3" style="border: 1px solid var(--pos-border); border-radius: var(--radius-md);">
                    <div class="card-header" style="background: var(--pos-surface); border-bottom: 1px solid var(--pos-border);">
                        <span style="font-weight: 600; font-size: 0.85rem;">Módulo ${i}</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre del Módulo *</label>
                                <input type="text" name="modulos[${i-1}][nombre]" class="form-control modulo-nombre" data-index="${i-1}" required>
                                <input type="hidden" name="modulos[${i-1}][n_modulo]" value="${i}">
                                <div class="modulo-nombre-error text-danger mt-1" style="font-size: 0.82em;" data-index="${i-1}"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Inicio *</label>
                                <input type="date" name="modulos[${i-1}][fecha_inicio]" class="form-control modulo-inicio" data-index="${i-1}" required>
                                <div class="modulo-inicio-error text-danger mt-1" style="font-size: 0.82em;" data-index="${i-1}"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Fin *</label>
                                <input type="date" name="modulos[${i-1}][fecha_fin]" class="form-control modulo-fin" data-index="${i-1}" required>
                                <div class="modulo-fin-error text-danger mt-1" style="font-size: 0.82em;" data-index="${i-1}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        }
        validateOfertaForm();
    });

    // Evento para validar módulos dinámicos
    $(document).on('input change', '.modulo-nombre, .modulo-inicio, .modulo-fin', function() {
        validateModuloField($(this));
        validateOfertaForm();
    });

    function validateModuloField($field) {
        const index = $field.data('index');
        const val = $field.val().trim();
        const errorEl = $(`.modulo-${$field.hasClass('modulo-nombre') ? 'nombre' : $field.hasClass('modulo-inicio') ? 'inicio' : 'fin'}-error[data-index="${index}"]`);

        if (!val) {
            errorEl.text('Este campo es obligatorio');
            return false;
        }
        errorEl.text('');
        return true;
    }

    // Programa auto-create
    let programaDebounce;
    $('#programa_nombre').on('input', function() {
        const nombre = $(this).val().trim();
        const feedback = $('#programa_feedback');
        clearTimeout(programaDebounce);
        if (nombre.length < 3) {
            feedback.text('').removeClass('text-success text-danger');
            $('#programa_id').val('');
            validateOfertaForm();
            return;
        }
        programaDebounce = setTimeout(() => {
            $.ajax({
                url: "{{ route('admin.programas.buscar-o-crear') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}", nombre: nombre },
                success: function(res) {
                    if (res.programa && res.programa.id) {
                        $('#programa_id').val(res.programa.id);
                        if (res.exists) {
                            feedback.removeClass('text-danger').addClass('text-success').text('Programa existente: ' + res.programa.nombre);
                        } else {
                            feedback.removeClass('text-danger').addClass('text-success').text('Programa creado: ' + res.programa.nombre);
                        }
                    } else {
                        feedback.addClass('text-danger').text('No se pudo obtener el ID del programa.');
                        $('#programa_id').val('');
                    }
                    validateOfertaForm();
                },
                error: function(xhr) {
                    feedback.addClass('text-danger').text('Error al buscar programa.');
                    $('#programa_id').val('');
                    validateOfertaForm();
                }
            });
        }, 400);
    });

    // Código verification
    let codigoDebounce;
    let codigoValido = false;
    $('#codigo').on('input', function() {
        const codigo = $(this).val().trim();
        const feedback = $('#feedback_codigo');
        clearTimeout(codigoDebounce);
        if (!codigo) {
            feedback.text('');
            codigoValido = false;
            validateOfertaForm();
            return;
        }
        if (codigo.length < 2) {
            feedback.addClass('text-danger').text('El código debe tener al menos 2 caracteres');
            codigoValido = false;
            validateOfertaForm();
            return;
        }
        codigoDebounce = setTimeout(() => {
            $.ajax({
                url: "{{ route('admin.ofertas-academicas.verificar-codigo') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}", codigo: codigo },
                success: function(res) {
                    if (res.exists) {
                        feedback.removeClass('text-success').addClass('text-danger').text('Código ya existe.');
                        codigoValido = false;
                    } else {
                        feedback.removeClass('text-danger').addClass('text-success').text('Código disponible.');
                        codigoValido = true;
                    }
                    validateOfertaForm();
                },
                error: function() {
                    feedback.addClass('text-danger').text('Error al verificar código.');
                    codigoValido = false;
                    validateOfertaForm();
                }
            });
        }, 400);
    });

    // Fechas validation
    $('#fecha_inicio_inscripciones').on('change', function() {
        const val = $(this).val();
        const errorEl = $('#error_fecha_inicio_inscripciones');
        if (!val) { errorEl.text('Seleccione una fecha'); validateOfertaForm(); return; }
        const selected = new Date(val);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (selected <= today) {
            errorEl.text('La fecha debe ser posterior a hoy');
            validateOfertaForm();
            return;
        }
        errorEl.text('');
        // Resetear fechas dependientes
        const inicioProg = $('#fecha_inicio_programa').val();
        const finProg = $('#fecha_fin_programa').val();
        if (inicioProg && new Date(inicioProg) <= selected) {
            $('#fecha_inicio_programa').val('');
            $('#error_fecha_inicio_programa').text('');
        }
        if (finProg) {
            $('#fecha_fin_programa').val('');
            $('#error_fecha_fin_programa').text('');
        }
        validateOfertaForm();
    });

    $('#fecha_inicio_programa').on('change', function() {
        const val = $(this).val();
        const errorEl = $('#error_fecha_inicio_programa');
        if (!val) { errorEl.text('Seleccione una fecha'); validateOfertaForm(); return; }
        const inicioInsc = $('#fecha_inicio_inscripciones').val();
        if (inicioInsc && new Date(val) <= new Date(inicioInsc)) {
            errorEl.text('Debe ser posterior a la fecha de inicio de inscripciones');
            validateOfertaForm();
            return;
        }
        errorEl.text('');
        const finProg = $('#fecha_fin_programa').val();
        if (finProg && new Date(finProg) <= new Date(val)) {
            $('#fecha_fin_programa').val('');
            $('#error_fecha_fin_programa').text('');
        }
        validateOfertaForm();
    });

    $('#fecha_fin_programa').on('change', function() {
        const val = $(this).val();
        const errorEl = $('#error_fecha_fin_programa');
        if (!val) { errorEl.text('Seleccione una fecha'); validateOfertaForm(); return; }
        const inicioProg = $('#fecha_inicio_programa').val();
        if (inicioProg && new Date(val) <= new Date(inicioProg)) {
            errorEl.text('Debe ser posterior a la fecha de inicio del programa');
            validateOfertaForm();
            return;
        }
        errorEl.text('');
        validateOfertaForm();
    });

    // Validación en tiempo real para selects requeridos
    $('#modalidade_id, #responsable_academico_cargo_id, #responsable_marketing_cargo_id, #gestion').on('change', function() {
        validateOfertaForm();
    });

    // Validación numérica
    $('#n_sesiones, #n_modulos, #version, #grupo, #nota_minima').on('input', function() {
        const val = $(this).val().trim();
        let errorEl;
        if ($(this).attr('id') === 'nota_minima') {
            errorEl = $('#error_nota_minima');
            if (val && (parseInt(val) < 1 || parseInt(val) > 100)) {
                errorEl.text('Debe estar entre 1 y 100');
            } else {
                errorEl.text('');
            }
        }
        if ($(this).attr('id') === 'n_sesiones') {
            errorEl = $('#error_cantidad_sesiones');
            if (val && parseInt(val) < 1) {
                errorEl.text('Debe ser al menos 1');
            } else {
                errorEl.text('');
            }
        }
        if ($(this).attr('id') === 'n_modulos') {
            errorEl = $('#error_n_modulos');
            if (val && parseInt(val) < 1) {
                errorEl.text('Debe ser al menos 1');
            } else {
                errorEl.text('');
            }
        }
        validateOfertaForm();
    });

    // === VALIDACIÓN COMPLETA DEL FORMULARIO DE OFERTA ===
    let ofertaFormTouched = false;

    function validateOfertaForm() {
        let allValid = true;
        const errors = [];

        // Campos requeridos simples
        const requiredFields = [
            { id: '#sede_id', label: 'Sede' },
            { id: '#sucursale_id', label: 'Sucursal' },
            { id: '#modalidade_id', label: 'Modalidad' },
            { id: '#codigo', label: 'Código' },
            { id: '#gestion', label: 'Gestión' },
            { id: '#responsable_academico_cargo_id', label: 'Responsable Académico' },
            { id: '#responsable_marketing_cargo_id', label: 'Responsable Marketing' },
            { id: '#fecha_inicio_inscripciones', label: 'Fecha Inicio Inscripciones' },
            { id: '#fecha_inicio_programa', label: 'Fecha Inicio Programa' },
            { id: '#fecha_fin_programa', label: 'Fecha Fin Programa' },
            { id: '#color_registro', label: 'Color' },
        ];

        requiredFields.forEach(function(field) {
            const val = $(field.id).val();
            if (!val || (typeof val === 'string' && !val.trim())) {
                allValid = false;
                errors.push(field.label);
            }
        });

        // Programa: debe tener programa_nombre escrito Y programa_id numérico
        const progNombre = $('#programa_nombre').val().trim();
        const progId = $('#programa_id').val();
        if (!progNombre || !progId || progId === 'new') {
            allValid = false;
            errors.push('Programa');
        }

        // Código debe estar verificado
        if (!codigoValido) {
            allValid = false;
            if (!errors.includes('Código')) errors.push('Código');
        }

        // Validar módulos si existen
        const nModulos = parseInt($('#n_modulos').val()) || 0;
        for (let i = 0; i < nModulos; i++) {
            const nombre = $(`input[name="modulos[${i}][nombre]"]`).val();
            const inicio = $(`input[name="modulos[${i}][fecha_inicio]"]`).val();
            const fin = $(`input[name="modulos[${i}][fecha_fin]"]`).val();
            if (!nombre || !nombre.trim()) { allValid = false; errors.push(`Módulo ${i+1} - Nombre`); }
            if (!inicio) { allValid = false; errors.push(`Módulo ${i+1} - Fecha Inicio`); }
            if (!fin) { allValid = false; errors.push(`Módulo ${i+1} - Fecha Fin`); }
        }

        // Validar secuencia de fechas
        const fechaInsc = $('#fecha_inicio_inscripciones').val();
        const fechaInicioProg = $('#fecha_inicio_programa').val();
        const fechaFinProg = $('#fecha_fin_programa').val();
        if (fechaInsc && fechaInicioProg && new Date(fechaInicioProg) <= new Date(fechaInsc)) {
            allValid = false;
        }
        if (fechaInicioProg && fechaFinProg && new Date(fechaFinProg) <= new Date(fechaInicioProg)) {
            allValid = false;
        }

        // Validar nota mínima
        const notaMin = $('#nota_minima').val();
        if (notaMin && (parseInt(notaMin) < 1 || parseInt(notaMin) > 100)) {
            allValid = false;
        }

        // Actualizar botón y resumen
        if (allValid) {
            $('#submitOfertaBtn').prop('disabled', false);
            if (ofertaFormTouched) {
                $('#oferta_validation_summary').removeClass('text-danger').addClass('text-success')
                    .html('<i class="ri-checkbox-circle-line me-1"></i> Todos los campos son válidos. Puede registrar la oferta.');
            }
        } else {
            $('#submitOfertaBtn').prop('disabled', true);
            if (ofertaFormTouched && errors.length > 0) {
                $('#oferta_validation_summary').removeClass('text-success').addClass('text-danger')
                    .html(`<i class="ri-error-warning-line me-1"></i> Faltan validar: ${errors.join(', ')}`);
            } else if (ofertaFormTouched) {
                $('#oferta_validation_summary').removeClass('text-success').addClass('text-danger')
                    .html('<i class="ri-error-warning-line me-1"></i> Revise los campos marcados en rojo.');
            }
        }
    }

    // Escuchar cambios en todo el formulario
    $('#ofertaForm').on('input change', function() {
        ofertaFormTouched = true;
        validateOfertaForm();
    });

    // Oferta submit
    $('#ofertaForm').submit(function(e) {
        e.preventDefault();

        // Validación final antes de enviar
        validateOfertaForm();
        if ($('#submitOfertaBtn').prop('disabled')) {
            showToast('error', 'Complete todos los campos requeridos correctamente.');
            return;
        }

        const formData = new FormData(this);
        const portadaFile = $('#portada_input')[0].files[0];
        const certificadoFile = $('#certificado_input')[0].files[0];
        if (portadaFile) formData.append('portada', portadaFile);
        if (certificadoFile) formData.append('certificado', certificadoFile);

        $('#submitOfertaBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Registrando...');
        $.ajax({
            url: "{{ route('admin.ofertas-academicas.registrar') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.success) {
                    showToast('success', res.msg);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalRegistrarOferta'));
                    if (modal) modal.hide();
                    setTimeout(() => location.reload(), 500);
                } else {
                    showToast('error', res.msg || 'Error al registrar la oferta.');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error al registrar la oferta.';
                if (xhr.responseJSON?.errors) {
                    const errs = Object.entries(xhr.responseJSON.errors);
                    const details = errs.map(([field, messages]) => `${field}: ${messages[0]}`).join('. ');
                    errorMsg = details;
                }
                showToast('error', errorMsg);
            },
            complete: function() {
                $('#submitOfertaBtn').prop('disabled', false).html('<i class="ri-save-3-line me-1"></i> Registrar Oferta');
            }
        });
    });

    // Populate oferta modal when opening
    $(document).on('click', '[data-posgrado-id]', function() {
        const posgradoId = $(this).data('posgrado-id');
        const posgradoNombre = $(this).data('posgrado-nombre');
        $('#oferta_posgrado_id').val(posgradoId);
        $('#modalRegistrarOfertaLabel').html(`<i class="ri-book-open-line me-2"></i>Registrar Oferta: ${posgradoNombre}`);
        ofertaFormTouched = false;
        $('#oferta_validation_summary').text('');
        codigoValido = false;
    });

    // Toast function
    function showToast(type, message) {
        const config = {
            success: { icon: 'ri-checkbox-circle-fill', bgClass: 'bg-success', title: 'Éxito' },
            error: { icon: 'ri-close-circle-fill', bgClass: 'bg-danger', title: 'Error' },
            warning: { icon: 'ri-alert-fill', bgClass: 'bg-warning', title: 'Advertencia' },
            info: { icon: 'ri-information-fill', bgClass: 'bg-info', title: 'Información' }
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
            container.style.zIndex = '999999';
            document.body.appendChild(container);
        }
        container.insertAdjacentHTML('afterbegin', toastHtml);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 4000 });
        toast.show();
        toastElement.addEventListener('hidden.bs.toast', function() { this.remove(); });
    }
});
</script>
@endpush
