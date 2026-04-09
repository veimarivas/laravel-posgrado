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
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 25px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
        }

        .cuentas-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--plane-text);
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

        .cuentas-header {
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

        .cuentas-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .cuentas-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 20%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .cuentas-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.65rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1;
            color: white;
        }

        .cuentas-header h1 i {
            color: white;
        }

        .cuentas-header p {
            margin: 4px 0 0;
            opacity: 0.85;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
            color: white;
        }

        .btn-new-cuenta {
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

        .btn-new-cuenta:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: var(--plane-primary-light);
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

        .stat-icon.total {
            background: var(--plane-primary-light);
            color: var(--plane-primary);
        }

        .stat-icon.active {
            background: #ecfdf5;
            color: var(--plane-success);
        }

        .stat-icon.inactive {
            background: #fef2f2;
            color: var(--plane-danger);
        }

        .stat-icon.balance {
            background: var(--plane-accent-light);
            color: var(--plane-accent);
        }

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

        .table-responsive {
            overflow-x: visible !important;
        }

        .cuentas-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .cuentas-table thead th {
            background: var(--plane-surface);
            padding: 12px 16px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--plane-text-muted);
            border-bottom: 1px solid var(--plane-border);
            vertical-align: middle;
        }

        .cuentas-table tbody tr {
            transition: background 0.15s ease;
        }

        .cuentas-table tbody tr:hover {
            background: var(--plane-primary-light);
        }

        .cuentas-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--plane-border);
            vertical-align: middle;
            font-size: 0.88rem;
        }

        .cuentas-table tbody tr:last-child td {
            border-bottom: none;
        }

        .cuenta-name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cuenta-avatar {
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

        .cuenta-name-text h6 {
            margin: 0;
            font-weight: 600;
            font-size: 0.92rem;
            color: var(--plane-text);
        }

        .cuenta-name-text small {
            color: var(--plane-text-muted);
            font-size: 0.75rem;
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

        .status-badge.inactive {
            background: #fef2f2;
            color: #dc2626;
        }

        .status-badge.ahorro {
            background: #eff6ff;
            color: #2563eb;
        }

        .status-badge.corriente {
            background: #f3e8ff;
            color: #9333ea;
        }

        .status-badge.moneda_extranjera {
            background: #fef3c7;
            color: #d97706;
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
            background: var(--plane-accent-light);
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

        .form-control,
        .form-select {
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

        .form-switch .form-check-input {
            width: 2.8em;
            height: 1.4em;
        }

        .currency-badge {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .banco-color-box {
            background-color: var(--plane-primary);
        }

        @media (max-width: 991.98px) {
            .cuentas-header {
                padding: 20px;
            }

            .cuentas-header h1 {
                font-size: 1.35rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 767.98px) {
            .cuentas-header {
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

            .cuentas-table thead th,
            .cuentas-table tbody td {
                padding: 10px;
                font-size: 0.8rem;
            }

            .cuenta-avatar {
                width: 32px;
                height: 32px;
                font-size: 0.9rem;
            }

            .cuenta-name-text h6 {
                font-size: 0.82rem;
            }

            .status-badge {
                font-size: 0.68rem;
                padding: 3px 8px;
            }
        }
    </style>

    <div class="container-fluid cuentas-page">
        <div class="cuentas-header">
            <div>
                <h1><i class="ri-bank-line me-2"></i>Gestión de Cuentas Bancarias</h1>
                <p>Administra las cuentas bancarias de la institución</p>
            </div>
            @if (Auth::guard('web')->user()->can('cuentas-bancarias.registrar'))
                <button type="button" class="btn btn-new-cuenta" data-bs-toggle="modal" data-bs-target="#registrar">
                    <i class="ri-add-line me-1"></i> Nueva Cuenta
                </button>
            @endif
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total"><i class="ri-file-list-3-line"></i></div>
                <div class="stat-info">
                    <p>Total Cuentas</p>
                    <h3 id="totalCuentasCounter">{{ $cuentas->total() }}</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon active"><i class="ri-checkbox-circle-line"></i></div>
                <div class="stat-info">
                    <p>Cuentas Activas</p>
                    <h3 id="totalActivas">0</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon inactive"><i class="ri-close-circle-line"></i></div>
                <div class="stat-info">
                    <p>Cuentas Inactivas</p>
                    <h3 id="totalInactivas">0</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon balance"><i class="ri-wallet-3-line"></i></div>
                <div class="stat-info">
                    <p>Saldo Total</p>
                    <h3 class="currency-badge" id="totalSaldo">0.00</h3>
                </div>
            </div>
        </div>

        <div class="filter-bar">
            <div class="search-wrapper">
                <i class="ri-search-line"></i>
                <input type="text" id="searchInput" class="form-control"
                    placeholder="Buscar por número de cuenta, banco o sucursal..." value="{{ request('search') ?? '' }}">
            </div>
            <div class="filter-pills">
                <span class="filter-pill active" data-filter="all">Todos</span>
                <span class="filter-pill" data-filter="activa">Activas</span>
                <span class="filter-pill" data-filter="inactiva">Inactivas</span>
                <span class="filter-pill" data-filter="ahorro">Ahorro</span>
                <span class="filter-pill" data-filter="corriente">Corriente</span>
            </div>
            <button type="button" id="clearFilters" class="btn-clear-filters">
                <i class="ri-refresh-line me-1"></i> Limpiar
            </button>
        </div>

        <div class="table-card">
            <div class="table-card-header">
                <h5><i class="ri-list-check me-2 text-muted"></i>Listado de Cuentas Bancarias</h5>
            </div>
            <div class="table-responsive">
                <table class="cuentas-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Número de Cuenta</th>
                            <th>Banco</th>
                            <th>Sucursal</th>
                            <th width="12%">Tipo</th>
                            <th width="10%">Moneda</th>
                            <th width="15%">Saldo</th>
                            <th width="10%">Estado</th>
                            <th width="12%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuentasTableBody">
                        @include('admin.cuentas_bancarias.partials.table-body')
                    </tbody>
                </table>
            </div>
            @if ($cuentas->total() > 0)
                <div class="table-footer">
                    <div class="results-count">
                        Mostrando <span class="fw-medium" id="showing-count">{{ $cuentas->firstItem() }}</span> a
                        <span class="fw-medium" id="to-count">{{ $cuentas->lastItem() }}</span> de
                        <span class="fw-medium" id="total-count">{{ $cuentas->total() }}</span> resultados
                    </div>
                    <div class="pagination-container">
                        {{ $cuentas->appends(request()->input())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="registrar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--plane-primary-light);">
                    <h5 class="modal-title" style="color: var(--plane-primary); font-weight: 600;">
                        <i class="ri-add-line me-2"></i>Registrar Nueva Cuenta Bancaria
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="banco_id_registro" class="form-label">Banco <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="banco_id_registro" name="banco_id" required>
                                    <option value="">Seleccionar Banco</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="sucursale_id_registro" class="form-label">Sucursal <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="sucursale_id_registro" name="sucursale_id" required>
                                    <option value="">Seleccionar Sucursal</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-12">
                                <label for="numero_cuenta_registro" class="form-label">Número de Cuenta <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="numero_cuenta_registro" name="numero_cuenta"
                                    class="form-control" placeholder="Ej: 1234567890" required>
                                <small id="feedback_numero_cuenta_registro" class="form-text mt-1"></small>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="tipo_cuenta_registro" class="form-label">Tipo de Cuenta <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="tipo_cuenta_registro" name="tipo_cuenta" required>
                                    <option value="">Seleccionar Tipo</option>
                                    <option value="ahorro">Ahorro</option>
                                    <option value="corriente">Corriente</option>
                                    <option value="moneda_extranjera">Moneda Extranjera</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="moneda_registro" class="form-label">Moneda <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="moneda_registro" name="moneda" required>
                                    <option value="">Seleccionar Moneda</option>
                                    <option value="BS">Bolivianos (BS)</option>
                                    <option value="USD">Dólares (USD)</option>
                                    <option value="EUR">Euros (EUR)</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="saldo_inicial_registro" class="form-label">Saldo Inicial <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" id="saldo_inicial_registro"
                                    name="saldo_inicial" class="form-control" placeholder="0.00" required>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check form-switch mt-4">
                                    <input type="hidden" name="activa" value="0">
                                    <input class="form-check-input" type="checkbox" id="activa_registro" name="activa"
                                        value="1" checked>
                                    <label class="form-check-label" for="activa_registro">Cuenta Activa</label>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-12">
                                <label for="descripcion_registro" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion_registro" name="descripcion" rows="2"
                                    placeholder="Descripción opcional..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn addBtn" style="background: var(--plane-primary); color: white;"
                            disabled>
                            <i class="ri-save-3-line me-1"></i> Registrar Cuenta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalModificar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--plane-accent-light);">
                    <h5 class="modal-title" style="color: #b45309; font-weight: 600;">
                        <i class="ri-edit-line me-2"></i>Editar Cuenta Bancaria
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="cuentaId">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="banco_id_edicion" class="form-label">Banco <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="banco_id_edicion" name="banco_id" required>
                                    <option value="">Seleccionar Banco</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="sucursale_id_edicion" class="form-label">Sucursal <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="sucursale_id_edicion" name="sucursale_id" required>
                                    <option value="">Seleccionar Sucursal</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-12">
                                <label for="numero_cuenta_edicion" class="form-label">Número de Cuenta <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="numero_cuenta_edicion" name="numero_cuenta"
                                    class="form-control" required>
                                <small id="feedback_numero_cuenta_edicion" class="form-text mt-1"></small>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="tipo_cuenta_edicion" class="form-label">Tipo de Cuenta <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="tipo_cuenta_edicion" name="tipo_cuenta" required>
                                    <option value="">Seleccionar Tipo</option>
                                    <option value="ahorro">Ahorro</option>
                                    <option value="corriente">Corriente</option>
                                    <option value="moneda_extranjera">Moneda Extranjera</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="moneda_edicion" class="form-label">Moneda <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="moneda_edicion" name="moneda" required>
                                    <option value="">Seleccionar Moneda</option>
                                    <option value="BS">Bolivianos (BS)</option>
                                    <option value="USD">Dólares (USD)</option>
                                    <option value="EUR">Euros (EUR)</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="saldo_inicial_edicion" class="form-label">Saldo Inicial <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" id="saldo_inicial_edicion"
                                    name="saldo_inicial" class="form-control" required>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check form-switch mt-4">
                                    <input type="hidden" name="activa" value="0">
                                    <input class="form-check-input" type="checkbox" id="activa_edicion" name="activa"
                                        value="1">
                                    <label class="form-check-label" for="activa_edicion">Cuenta Activa</label>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-12">
                                <label for="descripcion_edicion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion_edicion" name="descripcion" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn updateBtn"
                            style="background: var(--plane-accent); color: white;" disabled>
                            <i class="ri-refresh-line me-1"></i> Actualizar Cuenta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                        <h5 style="font-weight: 600;">¿Estás seguro de eliminar esta cuenta bancaria?</h5>
                        <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
                        <div class="alert alert-warning mt-3" id="warning-pagos" style="display: none;">
                            <i class="ri-alert-line me-1"></i> <strong>Advertencia:</strong> Esta cuenta tiene pagos
                            asociados y no puede ser eliminada.
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btnDelete"
                            style="background: var(--plane-danger); color: white;">
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
            let currentFilter = 'all';

            function cargarBancos() {
                $.ajax({
                    url: "{{ route('admin.cuentas-bancarias.obtener-bancos') }}",
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            let options = '<option value="">Seleccionar Banco</option>';
                            response.bancos.forEach(banco => {
                                options += '<option value="' + banco.id + '">' + banco.nombre +
                                    ' (' + banco.codigo + ')</option>';
                            });
                            $('#banco_id_registro, #banco_id_edicion').html(options);
                        }
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
                                options += '<option value="' + sucursal.id + '">' + sucursal
                                    .nombre + ' - ' + sucursal.direccion + '</option>';
                            });
                            $('#sucursale_id_registro, #sucursale_id_edicion').html(options);
                        }
                    }
                });
            }

            cargarBancos();
            cargarSucursales();

            $('#modalModificar, #registrar').on('show.bs.modal', function() {
                if ($('#banco_id_edicion').children().length <= 1) cargarBancos();
                if ($('#sucursale_id_edicion').children().length <= 1) cargarSucursales();
            });

            $('#registrar, #modalModificar, #modalEliminar').on('hidden.bs.modal', function() {
                if (this.id === 'registrar') {
                    $('#addForm')[0].reset();
                    $('#activa_registro').prop('checked', true);
                    $('#feedback_numero_cuenta_registro').removeClass('text-success text-danger').text('');
                    $('.addBtn').prop('disabled', true);
                } else if (this.id === 'modalModificar') {
                    $('#updateForm')[0].reset();
                    $('#feedback_numero_cuenta_edicion').removeClass('text-success text-danger').text('');
                    $('.updateBtn').prop('disabled', true);
                } else if (this.id === 'modalEliminar') {
                    $('#warning-pagos').hide();
                    $('#deleteForm')[0].reset();
                }
            });

            function validarFormularioRegistro() {
                const bancoId = $('#banco_id_registro').val();
                const sucursalId = $('#sucursale_id_registro').val();
                const numeroCuenta = $('#numero_cuenta_registro').val().trim();
                const tipoCuenta = $('#tipo_cuenta_registro').val();
                const moneda = $('#moneda_registro').val();
                const saldoInicial = $('#saldo_inicial_registro').val();
                const submitBtn = $('.addBtn');
                const feedback = $('#feedback_numero_cuenta_registro');

                let valido = bancoId && sucursalId && numeroCuenta && tipoCuenta && moneda && saldoInicial && !
                    feedback.hasClass('text-danger');
                submitBtn.prop('disabled', !valido);
            }

            $('#banco_id_registro, #sucursale_id_registro, #tipo_cuenta_registro, #moneda_registro, #saldo_inicial_registro')
                .on('change', validarFormularioRegistro);
            $('#saldo_inicial_registro').on('input', validarFormularioRegistro);

            $('#numero_cuenta_registro').on('input', function() {
                const numeroCuenta = $(this).val().trim();
                const bancoId = $('#banco_id_registro').val();
                const sucursalId = $('#sucursale_id_registro').val();
                const feedback = $('#feedback_numero_cuenta_registro');

                feedback.removeClass('text-success text-danger').text('');

                if (numeroCuenta.length === 0 || !bancoId || !sucursalId) {
                    validarFormularioRegistro();
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
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
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Esta cuenta ya existe'
                                    );
                                $('.addBtn').prop('disabled', true);
                            } else {
                                feedback.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Número disponible'
                                    );
                                validarFormularioRegistro();
                            }
                        }
                    });
                }, 300);
            });

            $('#addForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;
                isProcessing = true;
                const submitBtn = $('.addBtn');
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Registrando...');

                $.ajax({
                    url: "{{ route('admin.cuentas-bancarias.registrar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            showNotification('success', res.msg);
                            $('#registrar').modal('hide');
                            loadResults();
                        } else {
                            showNotification('error', res.msg);
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar la cuenta bancaria.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        showNotification('error', errorMsg);
                    },
                    complete: function() {
                        isProcessing = false;
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-save-3-line me-1"></i> Registrar Cuenta');
                    }
                });
            });

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
                $('#feedback_numero_cuenta_edicion').removeClass('text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
            });

            function validarFormularioEdicion() {
                const bancoId = $('#banco_id_edicion').val();
                const sucursalId = $('#sucursale_id_edicion').val();
                const numeroCuenta = $('#numero_cuenta_edicion').val().trim();
                const tipoCuenta = $('#tipo_cuenta_edicion').val();
                const moneda = $('#moneda_edicion').val();
                const saldoInicial = $('#saldo_inicial_edicion').val();
                const submitBtn = $('.updateBtn');
                const feedback = $('#feedback_numero_cuenta_edicion');

                let valido = bancoId && sucursalId && numeroCuenta && tipoCuenta && moneda && saldoInicial && !
                    feedback.hasClass('text-danger');
                submitBtn.prop('disabled', !valido);
            }

            $('#banco_id_edicion, #sucursale_id_edicion, #tipo_cuenta_edicion, #moneda_edicion, #saldo_inicial_edicion')
                .on('change', validarFormularioEdicion);
            $('#saldo_inicial_edicion').on('input', validarFormularioEdicion);

            $('#numero_cuenta_edicion').on('input', function() {
                const numeroCuenta = $(this).val().trim();
                const bancoId = $('#banco_id_edicion').val();
                const sucursalId = $('#sucursale_id_edicion').val();
                const cuentaId = $('#cuentaId').val();
                const feedback = $('#feedback_numero_cuenta_edicion');

                feedback.removeClass('text-success text-danger').text('');

                if (numeroCuenta.length === 0 || !bancoId || !sucursalId) {
                    validarFormularioEdicion();
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
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
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Esta cuenta ya existe'
                                    );
                                $('.updateBtn').prop('disabled', true);
                            } else {
                                feedback.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Número disponible'
                                    );
                                validarFormularioEdicion();
                            }
                        }
                    });
                }, 300);
            });

            $('#updateForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;
                isProcessing = true;
                const submitBtn = $('.updateBtn');
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Actualizando...');

                $.ajax({
                    url: "{{ route('admin.cuentas-bancarias.modificar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            showNotification('success', res.msg);
                            $('#modalModificar').modal('hide');
                            loadResults();
                        } else {
                            showNotification('error', res.msg);
                        }
                    },
                    error: function() {
                        showNotification('error', 'Error al actualizar la cuenta bancaria.');
                    },
                    complete: function() {
                        isProcessing = false;
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-refresh-line me-1"></i> Actualizar Cuenta');
                    }
                });
            });

            $(document).on('click', '.deleteBtn', function() {
                var data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);
                $('#warning-pagos').hide();
                $('.btnDelete').prop('disabled', false);
            });

            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;
                isProcessing = true;
                const submitBtn = $('.btnDelete');
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Eliminando...');

                $.ajax({
                    url: "{{ route('admin.cuentas-bancarias.eliminar') }}",
                    type: "POST",
                    data: {
                        id: $('#eliminarId').val(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        if (res.success) {
                            showNotification('success', res.msg);
                            $('#modalEliminar').modal('hide');
                            loadResults();
                        } else {
                            if (res.msg && res.msg.indexOf('pagos') !== -1) {
                                $('#warning-pagos').show().html(
                                    '<i class="ri-alert-line me-1"></i> <strong>Advertencia:</strong> ' +
                                    res.msg);
                            } else {
                                showNotification('error', res.msg || 'No se pudo eliminar');
                            }
                        }
                    },
                    error: function() {
                        showNotification('error', 'Error al eliminar la cuenta bancaria.');
                    },
                    complete: function() {
                        isProcessing = false;
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-delete-bin-line me-1"></i> Sí, Eliminar');
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

                const toast = $(
                    '<div class="toast align-items-center text-white bg-' + type + ' border-0" role="alert">' +
                    '<div class="d-flex">' +
                    '<div class="toast-body">' + message + '</div>' +
                    '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>' +
                    '</div>' +
                    '</div>'
                );

                toastContainer.append(toast);
                const bsToast = new bootstrap.Toast(toast[0]);
                bsToast.show();
                toast.on('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }

            function loadResults() {
                if (isProcessing) return;
                isProcessing = true;
                var search = $('#searchInput').val().trim();
                $.ajax({
                    url: "{{ route('admin.cuentas-bancarias.listar') }}",
                    method: "GET",
                    data: {
                        search: search,
                        filter: currentFilter
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#cuentasTableBody').html(`
                            <tr><td colspan="9" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2 text-muted">Cargando resultados...</p>
                            </td></tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#cuentasTableBody').html(response.html);
                        }
                        if (response.pagination) {
                            $('.pagination-container').html(response.pagination);
                        }
                        if (response.total !== undefined) {
                            $('#totalCuentasCounter').text(response.total);
                            $('#showing-count').text(response.from || 0);
                            $('#to-count').text(response.to || 0);
                            $('#total-count').text(response.total);
                        }
                        // Actualizar estadísticas si vienen en la respuesta
                        if (response.stats) {
                            $('#totalActivas').text(response.stats.activas || 0);
                            $('#totalInactivas').text(response.stats.inactivas || 0);
                            $('#totalSaldo').text(response.stats.saldo_total || '0.00');
                        }
                        initTooltips();
                    },
                    error: function() {
                        showNotification('error', 'Error al cargar los resultados');
                        $('#cuentasTableBody').html(`
                            <tr><td colspan="9" class="text-center py-5 text-danger">
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

            function initTooltips() {
                $('[data-bs-toggle="tooltip"]').each(function() {
                    if (this._tooltip) this._tooltip.dispose();
                });
                $('[data-bs-toggle="tooltip"]').tooltip({
                    container: 'body'
                });
            }

            $('#searchInput').on('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    loadResults();
                }, 500);
            });

            $('#clearFilters').on('click', function() {
                $('#searchInput').val('');
                currentFilter = 'all';
                $('.filter-pill').removeClass('active').filter('[data-filter="all"]').addClass('active');
                loadResults();
            });

            $('.filter-pill').on('click', function() {
                currentFilter = $(this).data('filter');
                $('.filter-pill').removeClass('active');
                $(this).addClass('active');
                loadResults();
            });

            $(document).on('click', '.pagination-container .pagination a', function(e) {
                e.preventDefault();
                if (isProcessing) return;
                var url = $(this).attr('href');
                if (!url) return;

                $.ajax({
                    url: url,
                    method: "GET",
                    data: {
                        search: $('#searchInput').val().trim(),
                        filter: currentFilter
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#cuentasTableBody').html(`
                            <tr><td colspan="9" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2 text-muted">Cargando página...</p>
                            </td></tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#cuentasTableBody').html(response.html);
                        }
                        if (response.pagination) {
                            $('.pagination-container').html(response.pagination);
                        }
                        if (response.total !== undefined) {
                            $('#totalCuentasCounter').text(response.total);
                            $('#showing-count').text(response.from || 0);
                            $('#to-count').text(response.to || 0);
                            $('#total-count').text(response.total);
                        }
                        initTooltips();
                        window.history.pushState({}, '', url);
                    },
                    error: function() {
                        showNotification('error', 'Error al cargar la página');
                    }
                });
            });

            initTooltips();
        });
    </script>
@endpush
