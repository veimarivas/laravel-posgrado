@extends('admin.dashboard')

@section('admin')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

    :root {
        --rec-primary: #1e3a5f;
        --rec-primary-light: #f0f4f8;
        --rec-primary-dark: #152a45;
        --rec-accent: #d97706;
        --rec-accent-light: #fffbeb;
        --rec-accent-soft: #fef3c7;
        --rec-surface: #f8fafc;
        --rec-border: #e2e8f0;
        --rec-text: #1e293b;
        --rec-text-muted: #64748b;
        --rec-success: #059669;
        --rec-success-light: #ecfdf5;
        --rec-info: #0891b2;
        --rec-info-light: #ecfeff;
        --rec-danger: #dc2626;
        --rec-danger-light: #fef2f2;
        --rec-warning: #d97706;
        --rec-warning-light: #fffbeb;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --radius-xl: 20px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md: 0 4px 8px -2px rgba(0,0,0,0.08), 0 2px 4px -2px rgba(0,0,0,0.04);
        --shadow-lg: 0 10px 25px -4px rgba(0,0,0,0.1), 0 4px 8px -4px rgba(0,0,0,0.06);
    }

    .recibos-page {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--rec-text);
        animation: recFadeIn 0.5s ease-out;
    }

    @keyframes recFadeIn {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Page Header */
    .recibos-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding: 22px 28px;
        background: linear-gradient(135deg, var(--rec-primary) 0%, var(--rec-primary-dark) 100%);
        border-radius: var(--radius-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .recibos-header::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -5%;
        width: 280px;
        height: 280px;
        background: radial-gradient(circle, rgba(217,119,6,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .recibos-header::after {
        content: '';
        position: absolute;
        bottom: -35%;
        left: 15%;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        border-radius: 50%;
    }

    .recibos-header h1 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.55rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.02em;
        position: relative;
        z-index: 1;
        color: white;
    }

    .recibos-header p {
        margin: 4px 0 0;
        opacity: 0.8;
        font-size: 0.88rem;
        position: relative;
        z-index: 1;
        color: white;
    }

    .btn-export-excel {
        background: white;
        color: var(--rec-success);
        border: none;
        padding: 9px 22px;
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.25s ease;
        box-shadow: var(--shadow-sm);
        position: relative;
        z-index: 1;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-export-excel:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        background: var(--rec-success-light);
        color: var(--rec-success);
    }

    /* Stat Cards */
    .stat-card {
        background: white;
        border-radius: var(--radius-md);
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1px solid var(--rec-border);
        transition: all 0.25s ease;
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
    }

    .stat-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        border-radius: 0 4px 4px 0;
        transition: width 0.25s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-card:hover::after {
        width: 6px;
    }

    .stat-card.stat-total::after { background: var(--rec-primary); }
    .stat-card.stat-monto::after { background: var(--rec-text); }
    .stat-card.stat-efectivo::after { background: var(--rec-success); }
    .stat-card.stat-transferencia::after { background: var(--rec-info); }
    .stat-card.stat-deposito::after { background: var(--rec-primary); }
    .stat-card.stat-tarjeta::after { background: var(--rec-warning); }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .stat-icon.icon-total { background: var(--rec-primary-light); color: var(--rec-primary); }
    .stat-icon.icon-monto { background: #f1f5f9; color: var(--rec-text); }
    .stat-icon.icon-efectivo { background: var(--rec-success-light); color: var(--rec-success); }
    .stat-icon.icon-transferencia { background: var(--rec-info-light); color: var(--rec-info); }
    .stat-icon.icon-deposito { background: var(--rec-primary-light); color: var(--rec-primary); }
    .stat-icon.icon-tarjeta { background: var(--rec-warning-light); color: var(--rec-warning); }

    .stat-label {
        font-size: 0.72rem;
        color: var(--rec-text-muted);
        margin-bottom: 2px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 600;
    }

    .stat-value {
        font-family: 'Outfit', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .stat-value.val-primary { color: var(--rec-primary); }
    .stat-value.val-success { color: var(--rec-success); }
    .stat-value.val-info { color: var(--rec-info); }
    .stat-value.val-warning { color: var(--rec-warning); }

    /* Filters Card */
    .filtros-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--rec-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .filtros-card .card-header {
        padding: 14px 22px;
        border-bottom: 1px dashed var(--rec-border);
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filtros-card .card-header i {
        color: var(--rec-accent);
    }

    .filtros-card .card-body {
        padding: 16px 22px 18px;
    }

    .filtros-card .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--rec-text-muted);
        margin-bottom: 5px;
    }

    .filtros-card .form-control,
    .filtros-card .form-select {
        border-radius: var(--radius-sm);
        border: 1px solid var(--rec-border);
        padding: 8px 12px;
        font-size: 0.85rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--rec-surface);
        transition: all 0.2s ease;
    }

    .filtros-card .form-control:focus,
    .filtros-card .form-select:focus {
        outline: none;
        border-color: var(--rec-primary);
        box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.1);
        background: white;
    }

    .btn-filtrar {
        background: var(--rec-primary);
        color: white;
        border: none;
        padding: 8px 18px;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.25s ease;
    }

    .btn-filtrar:hover {
        background: var(--rec-primary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
        color: white;
    }

    .btn-limpiar {
        background: white;
        color: var(--rec-text-muted);
        border: 1px solid var(--rec-border);
        padding: 8px 18px;
        border-radius: var(--radius-sm);
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    .btn-limpiar:hover {
        background: var(--rec-surface);
        color: var(--rec-text);
    }

    /* Table Card */
    .tabla-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--rec-border);
        overflow: hidden;
    }

    .tabla-card-header {
        padding: 16px 24px;
        border-bottom: 1px dashed var(--rec-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }

    .tabla-card-header h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin: 0;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tabla-card-header h5 i {
        color: var(--rec-primary);
    }

    .badge-recibos-count {
        background: var(--rec-primary-light);
        color: var(--rec-primary);
        font-size: 0.72rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 50px;
    }

    /* Table */
    .recibos-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .recibos-table thead th {
        background: var(--rec-surface);
        padding: 12px 16px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--rec-text-muted);
        border-bottom: 1px solid var(--rec-border);
        white-space: normal;
        vertical-align: middle;
    }

    .recibos-table tbody tr {
        transition: background 0.15s ease;
    }

    .recibos-table tbody tr:hover {
        background: var(--rec-primary-light);
    }

    .recibos-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--rec-border);
        vertical-align: middle;
        white-space: normal;
        font-size: 0.86rem;
    }

    .recibos-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Recibo cell */
    .recibo-cell {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .recibo-number {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 0.88rem;
        color: var(--rec-primary);
        letter-spacing: -0.01em;
    }

    .tipo-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
        width: fit-content;
    }

    .tipo-badge.efectivo { background: var(--rec-success-light); color: var(--rec-success); }
    .tipo-badge.transferencia { background: var(--rec-info-light); color: var(--rec-info); }
    .tipo-badge.deposito { background: var(--rec-primary-light); color: var(--rec-primary); }
    .tipo-badge.tarjeta { background: var(--rec-warning-light); color: var(--rec-warning); }
    .tipo-badge.otro { background: #f1f5f9; color: var(--rec-text-muted); }

    /* Date cell */
    .date-cell .date-main {
        font-weight: 600;
        font-size: 0.86rem;
    }

    .date-cell .date-time {
        color: var(--rec-text-muted);
        font-size: 0.75rem;
    }

    /* Student cell */
    .student-cell a {
        color: var(--rec-text);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.86rem;
        transition: color 0.2s ease;
    }

    .student-cell a:hover {
        color: var(--rec-primary);
    }

    .student-cell a i {
        opacity: 0;
        transition: opacity 0.2s ease;
        font-size: 0.72rem;
    }

    .student-cell a:hover i {
        opacity: 1;
    }

    .carnet-badge {
        background: #f1f5f9;
        color: var(--rec-text-muted);
        font-size: 0.68rem;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 50px;
    }

    /* Program cell */
    .program-cell a {
        color: var(--rec-text);
        text-decoration: none;
        font-size: 0.82rem;
        line-height: 1.35;
        transition: color 0.2s ease;
    }

    .program-cell a:hover {
        color: var(--rec-primary);
    }

    /* Amount cell */
    .amount-cell .amount-main {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 0.92rem;
        color: var(--rec-success);
    }

    .amount-cell .amount-discount {
        color: var(--rec-warning);
        font-size: 0.73rem;
        font-weight: 500;
    }

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
        text-decoration: none;
    }

    .action-btn.view {
        background: var(--rec-primary-light);
        color: var(--rec-primary);
        border-color: #c7d5e7;
    }

    .action-btn.view:hover {
        background: var(--rec-primary);
        color: white;
        transform: translateY(-1px);
    }

    .action-btn.download {
        background: var(--rec-success-light);
        color: var(--rec-success);
        border-color: #a7f3d0;
    }

    .action-btn.download:hover {
        background: var(--rec-success);
        color: white;
        transform: translateY(-1px);
    }

    /* Table footer */
    .table-footer {
        padding: 14px 24px;
        border-top: 1px solid var(--rec-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: var(--rec-surface);
    }

    .table-footer .results-count {
        font-size: 0.82rem;
        color: var(--rec-text-muted);
    }

    .pagination .page-link {
        border-radius: var(--radius-sm) !important;
        border: 1px solid var(--rec-border);
        color: var(--rec-text-muted);
        font-size: 0.82rem;
        padding: 6px 12px;
        margin: 0 2px;
        transition: all 0.2s ease;
    }

    .pagination .page-item.active .page-link {
        background: var(--rec-primary);
        border-color: var(--rec-primary);
        color: white;
    }

    .pagination .page-link:hover {
        background: var(--rec-primary-light);
        border-color: var(--rec-primary);
        color: var(--rec-primary);
    }

    #perPageSelect {
        border-radius: var(--radius-sm);
        border: 1px solid var(--rec-border);
        padding: 5px 8px;
        font-size: 0.82rem;
        background: white;
    }

    /* Empty state */
    .empty-state {
        padding: 52px 24px;
        text-align: center;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 16px;
        background: var(--rec-surface);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state-icon i {
        font-size: 2.2rem;
        color: #cbd5e1;
    }

    .empty-state h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        color: var(--rec-text);
        margin-bottom: 4px;
    }

    .empty-state p {
        color: var(--rec-text-muted);
        font-size: 0.88rem;
        margin: 0;
    }

    /* Modal */
    .modal-recibo .modal-content {
        border: none;
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .modal-recibo .modal-header {
        background: linear-gradient(135deg, var(--rec-primary) 0%, var(--rec-primary-dark) 100%);
        color: white;
        border-bottom: none;
        padding: 18px 24px;
    }

    .modal-recibo .modal-header h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-recibo .modal-body {
        padding: 22px 24px;
    }

    .modal-recibo .modal-footer {
        border-top: 1px solid var(--rec-border);
        padding: 14px 24px;
        background: var(--rec-surface);
    }

    /* Detail modal content */
    .detalle-header {
        background: linear-gradient(135deg, var(--rec-primary) 0%, var(--rec-primary-dark) 100%);
        border-radius: var(--radius-md);
        padding: 20px 22px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .detalle-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -5%;
        width: 160px;
        height: 160px;
        background: radial-gradient(circle, rgba(217,119,6,0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    .detalle-header .recibo-info {
        position: relative;
        z-index: 1;
    }

    .detalle-header .recibo-label {
        font-size: 0.72rem;
        opacity: 0.75;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 2px;
    }

    .detalle-header .recibo-num {
        font-family: 'Outfit', sans-serif;
        font-size: 1.3rem;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .detalle-header .recibo-date {
        font-size: 0.8rem;
        opacity: 0.8;
        margin-top: 4px;
    }

    .detalle-header .monto-info {
        text-align: right;
        position: relative;
        z-index: 1;
    }

    .detalle-header .monto-label {
        font-size: 0.72rem;
        opacity: 0.75;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 2px;
    }

    .detalle-header .monto-value {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .detalle-header .monto-discount {
        font-size: 0.78rem;
        opacity: 0.8;
    }

    .detalle-info-card {
        border: 1px solid var(--rec-border);
        border-radius: var(--radius-md);
        padding: 16px 18px;
        background: white;
        height: 100%;
    }

    .detalle-info-card .card-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--rec-text-muted);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .detalle-info-card .card-label i {
        color: var(--rec-accent);
    }

    .detalle-section {
        border: 1px solid var(--rec-border);
        border-radius: var(--radius-md);
        padding: 16px 18px;
        background: white;
        margin-bottom: 14px;
    }

    .detalle-section:last-child {
        margin-bottom: 0;
    }

    .detalle-section-title {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--rec-text-muted);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .detalle-section-title i {
        color: var(--rec-accent);
    }

    .cuota-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        border-radius: var(--radius-sm);
        transition: background 0.15s ease;
    }

    .cuota-item:hover {
        background: var(--rec-surface);
    }

    .cuota-item + .cuota-item {
        border-top: 1px solid var(--rec-border);
    }

    .cuota-name {
        font-weight: 600;
        font-size: 0.86rem;
    }

    .cuota-program {
        font-size: 0.75rem;
        color: var(--rec-text-muted);
    }

    .cuota-amount {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 0.88rem;
        color: var(--rec-success);
        background: var(--rec-success-light);
        padding: 4px 12px;
        border-radius: 50px;
    }

    .detalle-metodo-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
    }

    .detalle-metodo-item + .detalle-metodo-item {
        border-top: 1px solid var(--rec-border);
    }

    .detalle-metodo-amount {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.88rem;
    }

    /* Loading */
    .detalle-loading {
        text-align: center;
        padding: 48px 24px;
    }

    .detalle-loading .spinner-border {
        width: 2.5rem;
        height: 2.5rem;
        color: var(--rec-primary);
    }

    .detalle-loading p {
        color: var(--rec-text-muted);
        margin-top: 12px;
        font-size: 0.9rem;
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

    /* Spin animation */
    .spin {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinAnim 1s linear infinite;
    }

    @keyframes spinAnim {
        to { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 767.98px) {
        .recibos-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .filtros-card .card-body {
            padding: 14px 16px;
        }
        .tabla-card-header {
            padding: 14px 16px;
        }
        .table-footer {
            flex-direction: column;
            align-items: center;
        }
        .detalle-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .detalle-header .monto-info {
            text-align: left;
        }
    }
</style>

<div class="container-fluid recibos-page">
    <!-- Page Header -->
    <div class="recibos-header">
        <div>
            <h1><i class="ri-receipt-line me-2"></i>Historial de Recibos</h1>
            <p>Consulta y gestiona todos los recibos de pago registrados</p>
        </div>
        <a href="{{ route('admin.recibos.exportar') . '?' . http_build_query(request()->query()) }}"
           class="btn-export-excel">
            <i class="ri-file-excel-2-line"></i> Exportar Excel
        </a>
    </div>

    <!-- Estadísticas -->
    <div class="row g-2 mb-3" id="estadisticasContainer">
        @include('admin.recibos.partials.estadisticas', ['estadisticas' => $estadisticas])
    </div>

    <!-- Filtros -->
    <div class="filtros-card mb-3">
        <div class="card-header">
            <i class="ri-filter-3-line"></i>
            <span>Filtros de búsqueda</span>
        </div>
        <div class="card-body">
            <form id="formFiltrosRecibos">
                <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label">Fecha inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                            value="{{ request('fecha_inicio') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Fecha fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                            value="{{ request('fecha_fin') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipo de pago</label>
                        <select class="form-select" id="tipo_pago" name="tipo_pago">
                            <option value="Todos">Todos</option>
                            <option value="Efectivo"      {{ request('tipo_pago') == 'Efectivo'      ? 'selected' : '' }}>Efectivo</option>
                            <option value="Transferencia" {{ request('tipo_pago') == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                            <option value="Depósito"      {{ request('tipo_pago') == 'Depósito'      ? 'selected' : '' }}>Depósito</option>
                            <option value="Tarjeta"       {{ request('tipo_pago') == 'Tarjeta'       ? 'selected' : '' }}>Tarjeta</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">N° Recibo</label>
                        <input type="text" class="form-control" id="recibo" name="recibo"
                            value="{{ request('recibo') }}" placeholder="UNIP-000000001">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Carnet</label>
                        <input type="text" class="form-control" id="carnet" name="carnet"
                            value="{{ request('carnet') }}" placeholder="1234567">
                    </div>
                    <div class="col-md-1 d-flex gap-1">
                        <button type="submit" class="btn-filtrar w-100" title="Buscar">
                            <i class="ri-search-line"></i>
                        </button>
                        <button type="button" id="btnLimpiarFiltros" class="btn-limpiar w-100" title="Limpiar">
                            <i class="ri-refresh-line"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de recibos -->
    <div class="tabla-card">
        <div class="tabla-card-header">
            <h5>
                <i class="ri-list-check"></i>
                Listado de Recibos
                <span class="badge-recibos-count">más recientes primero</span>
            </h5>
        </div>
        <div id="tablaRecibosContainer">
            @include('admin.recibos.partials.table-body', ['recibos' => $recibos])
        </div>
    </div>

    <!-- Modal para ver detalle -->
    @include('admin.recibos.partials.modal-detalle')
</div>

@endsection

@push('script')
    <script>
        $(document).ready(function() {
            function cargarRecibos() {
                var formData = $('#formFiltrosRecibos').serialize();
                var perPage  = $('#perPageSelect').val();
                if (perPage) formData += '&per_page=' + perPage;

                $.ajax({
                    url: "{{ route('admin.recibos.filtrados') }}",
                    type: 'GET',
                    data: formData,
                    success: function(response) {
                        $('#tablaRecibosContainer').html(response.html);
                        if (response.estadisticas) actualizarEstadisticas(response.estadisticas);
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        showToast('error', 'No se pudieron cargar los recibos');
                    }
                });
            }

            function actualizarEstadisticas(estadisticas) {
                var stats = [
                    { label: 'Total Recibos',   value: estadisticas.total_recibos,       unit: '',   icon: 'ri-file-text-line',        cls: 'icon-total',     statCls: 'stat-total',    valCls: 'val-primary' },
                    { label: 'Monto Total',      value: formatMoney(estadisticas.total_monto),       unit: 'Bs', icon: 'ri-money-dollar-circle-line', cls: 'icon-monto',     statCls: 'stat-monto',    valCls: '' },
                    { label: 'Efectivo',         value: formatMoney(estadisticas.total_efectivo),     unit: 'Bs', icon: 'ri-money-dollar-circle-line', cls: 'icon-efectivo',  statCls: 'stat-efectivo', valCls: 'val-success' },
                    { label: 'Transferencia',    value: formatMoney(estadisticas.total_transferencia),unit: 'Bs', icon: 'ri-bank-transfer-line',       cls: 'icon-transferencia', statCls: 'stat-transferencia', valCls: 'val-info' },
                    { label: 'Depósito',         value: formatMoney(estadisticas.total_deposito||0),  unit: 'Bs', icon: 'ri-bank-card-2-line',         cls: 'icon-deposito',  statCls: 'stat-deposito', valCls: 'val-primary' },
                    { label: 'Tarjeta',          value: formatMoney(estadisticas.total_tarjeta||0),   unit: 'Bs', icon: 'ri-bank-card-line',           cls: 'icon-tarjeta',   statCls: 'stat-tarjeta',  valCls: 'val-warning' },
                ];
                var html = '';
                stats.forEach(function(s, i) {
                    html += `<div class="col-xl-2 col-md-4 col-6" style="animation: recFadeIn 0.4s ease-out ${i * 0.06}s both;">
                        <div class="stat-card ${s.statCls}">
                            <div class="stat-icon ${s.cls}"><i class="${s.icon}"></i></div>
                            <div>
                                <div class="stat-label">${s.label}</div>
                                <div class="stat-value ${s.valCls}">${s.value} <small class="fw-normal" style="font-size:.7rem;opacity:.7;">${s.unit}</small></div>
                            </div>
                        </div>
                    </div>`;
                });
                $('#estadisticasContainer').html(html);
            }

            function formatMoney(amount) {
                if (!amount) return '0.00';
                return parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }

            function formatDate(dateString) {
                if (!dateString) return 'N/A';
                try {
                    var date = new Date(dateString);
                    if (isNaN(date.getTime())) return 'Fecha inválida';
                    return date.toLocaleDateString('es-BO') + ' ' + date.toLocaleTimeString('es-BO');
                } catch (e) {
                    return 'Fecha inválida';
                }
            }

            function getTipoBadgeClass(tipo) {
                var map = {
                    'Efectivo': 'efectivo',
                    'Transferencia': 'transferencia',
                    'Depósito': 'deposito',
                    'Tarjeta': 'tarjeta'
                };
                return map[tipo] || 'otro';
            }

            $('#formFiltrosRecibos').on('submit', function(e) {
                e.preventDefault();
                cargarRecibos();
            });

            $('#btnLimpiarFiltros').on('click', function() {
                $('#formFiltrosRecibos')[0].reset();
                cargarRecibos();
            });

            $(document).on('change', '#perPageSelect', function() {
                cargarRecibos();
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                if (!url || url === '#') return;

                var extraData = $('#formFiltrosRecibos').serialize();
                var perPage = $('#perPageSelect').val();
                if (perPage) extraData += '&per_page=' + perPage;

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: extraData,
                    success: function(response) {
                        $('#tablaRecibosContainer').html(response.html);
                        if (response.estadisticas) actualizarEstadisticas(response.estadisticas);
                        $('html, body').animate({ scrollTop: $('#tablaRecibosContainer').offset().top - 80 }, 300);
                    },
                    error: function(xhr) { console.error(xhr); }
                });
            });

            $(document).on('click', '.btn-ver-detalle', function() {
                var pagoId = $(this).data('pago-id');

                $('#detallePagoContenido').html(`
                    <div class="detalle-loading">
                        <div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div>
                        <p>Cargando detalles del pago...</p>
                    </div>
                `);

                $('#btnImprimirDetalle').data('pago-id', pagoId);
                $('#modalDetallePago').modal('show');

                $.ajax({
                    url: "/admin/estudiantes/pago/" + pagoId + "/detalle",
                    type: "GET",
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            var html = construirHTMLDetalle(response);
                            $('#detallePagoContenido').html(html);
                        } else {
                            $('#detallePagoContenido').html(`
                                <div class="alert alert-danger">
                                    <i class="ri-error-warning-line me-2"></i>
                                    ${response.msg || 'Error al cargar los detalles del pago'}
                                </div>
                            `);
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMsg = 'Error al conectar con el servidor';
                        if (xhr.status === 404) {
                            errorMsg = 'La ruta no fue encontrada (404)';
                        } else if (xhr.status === 500) {
                            errorMsg = 'Error interno del servidor (500)';
                        } else if (xhr.responseText) {
                            try {
                                var jsonResponse = JSON.parse(xhr.responseText);
                                errorMsg = jsonResponse.msg || errorMsg;
                            } catch (e) {
                                errorMsg = xhr.responseText.substring(0, 100) + '...';
                            }
                        }
                        $('#detallePagoContenido').html(`
                            <div class="alert alert-danger">
                                <i class="ri-error-warning-line me-2"></i>
                                ${errorMsg}<br><small>Status: ${xhr.status}</small>
                            </div>
                        `);
                    }
                });
            });

            function construirHTMLDetalle(response) {
                var pago = response.pago;
                var estudiante = response.estudiante;
                var cuotas = response.cuotas;
                var tipoCls = getTipoBadgeClass(pago.tipo_pago);

                var html = `
                <div class="detalle-header">
                    <div class="recibo-info">
                        <div class="recibo-label"><i class="ri-file-text-line me-1"></i>N° de Recibo</div>
                        <div class="recibo-num">${pago.recibo || 'N/A'}</div>
                        <div class="recibo-date"><i class="ri-calendar-line me-1"></i>${formatDate(pago.fecha_pago)}</div>
                    </div>
                    <div class="monto-info">
                        <div class="monto-label">Monto pagado</div>
                        <div class="monto-value">${formatMoney(pago.pago_bs)} Bs</div>
                        ${pago.descuento_bs > 0 ? `<div class="monto-discount">Descuento: -${formatMoney(pago.descuento_bs)} Bs</div>` : ''}
                        <span class="tipo-badge ${tipoCls} mt-1" style="background:rgba(255,255,255,0.2);color:white;">${pago.tipo_pago || 'N/A'}</span>
                    </div>
                </div>`;

                html += `<div class="row g-2 mb-3">`;

                html += `<div class="col-md-6">
                    <div class="detalle-info-card">
                        <div class="card-label"><i class="ri-user-line"></i>Participante</div>`;
                if (estudiante && estudiante.persona) {
                    html += `
                        <div class="mb-2">
                            <div style="font-size:.72rem;color:var(--rec-text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">Nombre</div>
                            <strong style="font-size:.9rem;">${estudiante.persona.nombres} ${estudiante.persona.apellido_paterno} ${estudiante.persona.apellido_materno || ''}</strong>
                        </div>
                        <div>
                            <div style="font-size:.72rem;color:var(--rec-text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">Carnet</div>
                            <span class="carnet-badge">${estudiante.persona.carnet || 'N/A'}</span>
                        </div>`;
                } else {
                    html += `<div class="text-muted small">Información no disponible</div>`;
                }
                html += `</div></div>`;

                var primeraCuota = cuotas && cuotas.length > 0 ? cuotas[0].cuota : null;
                var oferta = primeraCuota && primeraCuota.inscripcion
                    ? (primeraCuota.inscripcion.oferta_academica || primeraCuota.inscripcion.ofertaAcademica || null)
                    : null;
                var nombrePrograma = oferta && oferta.programa ? oferta.programa.nombre : 'No disponible';

                html += `<div class="col-md-6">
                    <div class="detalle-info-card">
                        <div class="card-label"><i class="ri-graduation-cap-line"></i>Programa</div>
                        <div>
                            <div style="font-size:.72rem;color:var(--rec-text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">Programa de Posgrado</div>
                            <strong style="font-size:.88rem;">${nombrePrograma}</strong>
                        </div>
                    </div>
                </div></div>`;

                if (cuotas && cuotas.length > 0) {
                    html += `<div class="detalle-section">
                        <div class="detalle-section-title"><i class="ri-file-list-3-line"></i>Cuotas incluidas en este pago</div>
                        <div>`;

                    cuotas.forEach(function(cuotaPago) {
                        var cuota = cuotaPago.cuota;
                        var ofertaCuota = cuota && cuota.inscripcion
                            ? (cuota.inscripcion.oferta_academica || cuota.inscripcion.ofertaAcademica || null)
                            : null;
                        var progCuota = ofertaCuota && ofertaCuota.programa ? ofertaCuota.programa.nombre : 'N/A';

                        html += `
                        <div class="cuota-item">
                            <div>
                                <div class="cuota-name">${cuota ? cuota.nombre : 'N/A'}</div>
                                <div class="cuota-program"><i class="ri-graduation-cap-line me-1"></i>${progCuota}</div>
                            </div>
                            <span class="cuota-amount">${formatMoney(cuotaPago.pago_bs)} Bs</span>
                        </div>`;
                    });

                    html += `</div></div>`;
                }

                if (pago.detalles && pago.detalles.length > 0) {
                    html += `<div class="detalle-section">
                        <div class="detalle-section-title"><i class="ri-bank-line"></i>Métodos de pago</div>
                        <div>`;

                    pago.detalles.forEach(function(detalle) {
                        var dCls = getTipoBadgeClass(detalle.tipo_pago);
                        html += `
                        <div class="detalle-metodo-item">
                            <span class="tipo-badge ${dCls}">${detalle.tipo_pago || 'N/A'}</span>
                            <span class="detalle-metodo-amount">${formatMoney(detalle.pago_bs)} Bs</span>
                        </div>`;
                    });

                    html += `</div></div>`;
                }

                return html;
            }

            $('#btnImprimirDetalle').on('click', function() {
                var pagoId = $(this).data('pago-id');
                if (pagoId) {
                    window.open('/admin/estudiantes/pago/' + pagoId + '/descargar-recibo', '_blank');
                }
            });

            function showToast(type, message) {
                var config = {
                    success: { icon: 'ri-checkbox-circle-fill', bgClass: 'bg-success', title: 'Éxito' },
                    error: { icon: 'ri-close-circle-fill', bgClass: 'bg-danger', title: 'Error' },
                    warning: { icon: 'ri-alert-fill', bgClass: 'bg-warning', title: 'Advertencia' },
                    info: { icon: 'ri-information-fill', bgClass: 'bg-info', title: 'Información' }
                };
                var toastConfig = config[type] || config.info;
                var toastId = 'toast-' + Date.now();
                var toastHtml = `
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
                var container = document.getElementById('toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'toast-container';
                    container.className = 'toast-container position-fixed top-0 end-0 p-3';
                    document.body.appendChild(container);
                }
                container.insertAdjacentHTML('afterbegin', toastHtml);
                var toastElement = document.getElementById(toastId);
                var toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 });
                toast.show();
                toastElement.addEventListener('hidden.bs.toast', function() { this.remove(); });
            }
        });
    </script>
@endpush
