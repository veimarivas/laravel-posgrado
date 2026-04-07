@extends('admin.dashboard')

@section('admin')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

    :root {
        --cont-primary: #0f766e;
        --cont-primary-light: #f0fdfa;
        --cont-primary-dark: #0d5f59;
        --cont-accent: #f59e0b;
        --cont-accent-light: #fffbeb;
        --cont-surface: #f8fafc;
        --cont-border: #e2e8f0;
        --cont-text: #1e293b;
        --cont-text-muted: #64748b;
        --cont-success: #10b981;
        --cont-success-light: #ecfdf5;
        --cont-danger: #ef4444;
        --cont-danger-light: #fef2f2;
        --cont-info: #0891b2;
        --cont-info-light: #ecfeff;
        --cont-warning: #f59e0b;
        --cont-warning-light: #fffbeb;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md: 0 4px 8px -2px rgba(0,0,0,0.08), 0 2px 4px -2px rgba(0,0,0,0.04);
        --shadow-lg: 0 10px 25px -4px rgba(0,0,0,0.1), 0 4px 8px -4px rgba(0,0,0,0.06);
    }

    .cont-detalle-page {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--cont-text);
        animation: contFadeIn 0.5s ease-out;
    }

    @keyframes contFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Page Header */
    .cont-detalle-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding: 20px 28px;
        background: linear-gradient(135deg, var(--cont-primary) 0%, var(--cont-primary-dark) 100%);
        border-radius: var(--radius-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .cont-detalle-header::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -5%;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(245,158,11,0.12) 0%, transparent 70%);
        border-radius: 50%;
    }

    .cont-detalle-header h1 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.02em;
        position: relative;
        z-index: 1;
        color: white;
    }

    .cont-detalle-header p {
        margin: 4px 0 0;
        opacity: 0.8;
        font-size: 0.85rem;
        position: relative;
        z-index: 1;
        color: white;
    }

    .cont-header-actions {
        display: flex;
        gap: 8px;
        position: relative;
        z-index: 1;
    }

    .btn-cont-header {
        background: rgba(255,255,255,0.15);
        color: white;
        border: 1px solid rgba(255,255,255,0.25);
        padding: 8px 18px;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.82rem;
        transition: all 0.25s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        backdrop-filter: blur(4px);
    }

    .btn-cont-header:hover {
        background: white;
        color: var(--cont-primary);
        border-color: white;
        transform: translateY(-1px);
    }

    /* Student Card */
    .student-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--cont-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .student-card-body {
        padding: 24px;
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .student-avatar {
        width: 64px;
        height: 64px;
        border-radius: var(--radius-md);
        background: linear-gradient(135deg, var(--cont-primary) 0%, var(--cont-primary-dark) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.5rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.2);
    }

    .student-name {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.2rem;
        color: var(--cont-text);
        margin: 0 0 6px;
    }

    .student-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 6px;
    }

    .student-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.72rem;
        font-weight: 600;
        background: var(--cont-surface);
        color: var(--cont-text-muted);
        border: 1px solid var(--cont-border);
    }

    .student-address {
        font-size: 0.8rem;
        color: var(--cont-text-muted);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Financial Summary */
    .fin-summary {
        background: var(--cont-surface);
        border-radius: var(--radius-md);
        border: 1px solid var(--cont-border);
        padding: 18px 20px;
    }

    .fin-summary-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .fin-summary-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--cont-text-muted);
    }

    .fin-summary-pct {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 0.88rem;
        padding: 3px 12px;
        border-radius: 50px;
    }

    .fin-summary-pct.val-success { background: var(--cont-success-light); color: var(--cont-success); }
    .fin-summary-pct.val-primary { background: var(--cont-primary-light); color: var(--cont-primary); }
    .fin-summary-pct.val-warning { background: var(--cont-warning-light); color: var(--cont-warning); }
    .fin-summary-pct.val-danger { background: var(--cont-danger-light); color: var(--cont-danger); }

    .fin-summary-bar {
        height: 8px;
        border-radius: 4px;
        background: var(--cont-border);
        overflow: hidden;
        margin-bottom: 14px;
    }

    .fin-summary-bar-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.6s ease;
    }

    .fin-summary-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .fin-stat {
        text-align: center;
    }

    .fin-stat-value {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1rem;
        line-height: 1.2;
    }

    .fin-stat-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--cont-text-muted);
        font-weight: 600;
    }

    /* Section Header */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
    }

    .section-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 1.05rem;
        color: var(--cont-text);
        margin: 0;
    }

    .section-subtitle {
        font-size: 0.82rem;
        color: var(--cont-text-muted);
        margin: 2px 0 0;
    }

    .section-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        background: var(--cont-primary-light);
        color: var(--cont-primary);
        border: 1px solid rgba(15, 118, 110, 0.15);
    }

    /* Accordion */
    .cont-accordion {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .cont-accordion-item {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--cont-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        position: relative;
        transition: all 0.25s ease;
    }

    .cont-accordion-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
    }

    .cont-accordion-item.color-success::before { background: var(--cont-success); }
    .cont-accordion-item.color-primary::before { background: var(--cont-primary); }
    .cont-accordion-item.color-warning::before { background: var(--cont-warning); }
    .cont-accordion-item.color-danger::before { background: var(--cont-danger); }

    .cont-accordion-item:hover {
        box-shadow: var(--shadow-md);
    }

    .cont-accordion-btn {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 16px 20px;
        background: transparent;
        border: none;
        cursor: pointer;
        gap: 14px;
        transition: background 0.2s ease;
    }

    .cont-accordion-btn:hover {
        background: var(--cont-surface);
    }

    .cont-accordion-btn.collapsed {
        background: transparent;
    }

    .prog-avatar {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        color: white;
        flex-shrink: 0;
    }

    .prog-info {
        flex: 1;
        min-width: 0;
    }

    .prog-name {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--cont-text);
        margin: 0 0 4px;
    }

    .prog-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 0.75rem;
        color: var(--cont-text-muted);
    }

    .prog-meta-item {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .prog-meta-item i {
        color: var(--cont-primary);
    }

    .prog-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 6px;
        flex-shrink: 0;
    }

    .prog-badges {
        display: flex;
        gap: 6px;
    }

    .prog-badge-paid {
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        background: var(--cont-success-light);
        color: var(--cont-success);
    }

    .prog-badge-debt {
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        background: var(--cont-danger-light);
        color: var(--cont-danger);
    }

    .prog-progress {
        width: 110px;
    }

    .prog-progress-header {
        display: flex;
        justify-content: space-between;
        font-size: 0.65rem;
        margin-bottom: 3px;
    }

    .prog-progress-label {
        color: var(--cont-text-muted);
        font-weight: 600;
    }

    .prog-progress-value {
        font-weight: 700;
    }

    .prog-progress-bar {
        height: 5px;
        border-radius: 3px;
        background: var(--cont-border);
        overflow: hidden;
    }

    .prog-progress-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.6s ease;
    }

    .cont-accordion-body {
        border-top: 1px solid var(--cont-border);
    }

    /* Multi-pay bar */
    .multi-pay-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        background: var(--cont-primary-light);
        border-top: 1px solid var(--cont-border);
        border-bottom: 1px solid var(--cont-border);
    }

    .multi-pay-text {
        font-size: 0.82rem;
        color: var(--cont-text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .multi-pay-text strong {
        color: var(--cont-primary);
    }

    .btn-multi-pay {
        background: var(--cont-primary);
        color: white;
        border: none;
        padding: 7px 16px;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.82rem;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-multi-pay:hover {
        background: var(--cont-primary-dark);
        transform: translateY(-1px);
        color: white;
    }

    /* Program Summary */
    .prog-summary {
        padding: 14px 20px;
        background: var(--cont-surface);
        border-bottom: 1px solid var(--cont-border);
    }

    .prog-summary-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .prog-summary-stat .ps-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--cont-text-muted);
        font-weight: 700;
    }

    .prog-summary-stat .ps-value {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 0.95rem;
    }

    /* Concept Cards */
    .concept-section {
        padding: 16px 20px;
        border-bottom: 1px solid var(--cont-border);
    }

    .concept-section:last-child {
        border-bottom: none;
    }

    .concept-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 10px;
    }

    .concept-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.88rem;
        color: var(--cont-text);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .concept-title i {
        color: var(--cont-accent);
    }

    .concept-badges {
        display: flex;
        gap: 6px;
    }

    .concept-badge-paid {
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.68rem;
        font-weight: 600;
        background: var(--cont-success-light);
        color: var(--cont-success);
    }

    .concept-badge-debt {
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.68rem;
        font-weight: 600;
        background: var(--cont-danger-light);
        color: var(--cont-danger);
    }

    .concept-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    .concept-stat {
        text-align: center;
        padding: 8px 10px;
        border-radius: var(--radius-sm);
        background: white;
        border: 1px solid var(--cont-border);
    }

    .concept-stat-label {
        font-size: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--cont-text-muted);
        font-weight: 700;
    }

    .concept-stat-value {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 0.85rem;
    }

    /* Cuotas Table */
    .cuotas-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .cuotas-table thead th {
        background: var(--cont-surface);
        padding: 10px 14px;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--cont-text-muted);
        border-bottom: 1px solid var(--cont-border);
    }

    .cuotas-table tbody td {
        padding: 12px 14px;
        border-bottom: 1px solid var(--cont-border);
        vertical-align: middle;
        font-size: 0.84rem;
    }

    .cuotas-table tbody tr:last-child td {
        border-bottom: none;
    }

    .cuotas-table tbody tr:hover {
        background: var(--cont-primary-light);
    }

    .cuotas-table tbody tr.row-pagado {
        background: var(--cont-success-light);
    }

    .cuotas-table tbody tr.row-pagado:hover {
        background: #d1fae5;
    }

    .cuotas-table tbody tr.row-parcial {
        background: var(--cont-warning-light);
    }

    .cuotas-table tbody tr.row-parcial:hover {
        background: #fef3c7;
    }

    .cuotas-table tfoot td {
        padding: 10px 14px;
        background: var(--cont-surface);
        font-weight: 700;
        font-size: 0.82rem;
        border-top: 2px solid var(--cont-border);
    }

    .cuota-num-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 28px;
        border-radius: var(--radius-sm);
        background: var(--cont-surface);
        color: var(--cont-text-muted);
        font-weight: 700;
        font-size: 0.78rem;
        border: 1px solid var(--cont-border);
    }

    .cuota-name {
        font-weight: 600;
        font-size: 0.86rem;
    }

    .cuota-payments {
        font-size: 0.72rem;
        color: var(--cont-text-muted);
    }

    .cuota-progress {
        height: 3px;
        border-radius: 2px;
        background: var(--cont-border);
        overflow: hidden;
        margin-top: 4px;
    }

    .cuota-progress-fill {
        height: 100%;
        border-radius: 2px;
        transition: width 0.4s ease;
    }

    .estado-badge-cont {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .estado-badge-cont.pagado {
        background: var(--cont-success-light);
        color: var(--cont-success);
    }

    .estado-badge-cont.pendiente {
        background: var(--cont-warning-light);
        color: var(--cont-warning);
    }

    .cuota-action-btn {
        width: 30px;
        height: 30px;
        border-radius: var(--radius-sm);
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.88rem;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .cuota-action-btn.pay {
        background: var(--cont-success-light);
        color: var(--cont-success);
        border-color: rgba(16, 185, 129, 0.2);
    }

    .cuota-action-btn.pay:hover {
        background: var(--cont-success);
        color: white;
        transform: translateY(-1px);
    }

    .cuota-action-btn.receipts {
        background: var(--cont-info-light);
        color: var(--cont-info);
        border-color: rgba(8, 145, 178, 0.2);
        position: relative;
    }

    .cuota-action-btn.receipts:hover {
        background: var(--cont-info);
        color: white;
        transform: translateY(-1px);
    }

    .cuota-action-btn.receipts .receipt-count {
        position: absolute;
        top: -6px;
        right: -6px;
        background: var(--cont-info);
        color: white;
        font-size: 0.55rem;
        font-weight: 700;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid white;
    }

    /* Footer Summary */
    .footer-summary {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        padding: 18px 24px;
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--cont-border);
        box-shadow: var(--shadow-sm);
        margin-top: 20px;
    }

    .footer-stat {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .footer-stat-icon {
        width: 38px;
        height: 38px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .footer-stat-value {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 0.95rem;
        line-height: 1.2;
    }

    .footer-stat-label {
        font-size: 0.68rem;
        color: var(--cont-text-muted);
        font-weight: 600;
    }

    /* Empty State */
    .empty-state-cont {
        padding: 56px 24px;
        text-align: center;
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--cont-border);
        box-shadow: var(--shadow-sm);
    }

    .empty-state-cont-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 16px;
        background: var(--cont-surface);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state-cont-icon i {
        font-size: 2rem;
        color: #cbd5e1;
    }

    .empty-state-cont h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        color: var(--cont-text);
        margin-bottom: 4px;
    }

    .empty-state-cont p {
        color: var(--cont-text-muted);
        font-size: 0.85rem;
        margin: 0;
    }

    /* Modals */
    .modal-cont .modal-content {
        border: none;
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .modal-cont .modal-header {
        background: linear-gradient(135deg, var(--cont-primary) 0%, var(--cont-primary-dark) 100%);
        color: white;
        border-bottom: none;
        padding: 18px 24px;
    }

    .modal-cont .modal-header h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        color: white;
    }

    .modal-cont .modal-body {
        padding: 22px 24px;
    }

    .modal-cont .modal-footer {
        border-top: 1px solid var(--cont-border);
        padding: 14px 24px;
        background: var(--cont-surface);
    }

    /* Toast */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 999999 !important;
    }

    /* Responsive */
    @media (max-width: 767.98px) {
        .cont-detalle-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .student-info {
            flex-direction: column;
            text-align: center;
        }
        .student-badges {
            justify-content: center;
        }
        .fin-summary-stats {
            grid-template-columns: 1fr;
        }
        .prog-summary-stats {
            grid-template-columns: 1fr;
        }
        .cont-accordion-btn {
            flex-direction: column;
            align-items: flex-start;
        }
        .prog-right {
            align-items: flex-start;
            width: 100%;
        }
        .prog-progress {
            width: 100%;
        }
        .footer-summary {
            flex-direction: column;
        }
    }
</style>

@php
    $persona = $estudiante->persona;
    $totalDeuda   = 0;
    $totalPagado  = 0;
    $totalCuotas  = 0;
    $cuotasPagTot = 0;
    $cuotasPenTot = 0;
    foreach ($estudiante->inscripciones as $ins) {
        foreach ($ins->cuotas as $c) {
            $totalCuotas++;
            $totalPagado += $c->pago_total_bs - $c->pago_pendiente_bs;
            $totalDeuda  += $c->pago_pendiente_bs;
            if ($c->pago_terminado == 'si') { $cuotasPagTot++; } else { $cuotasPenTot++; }
        }
    }
    $pctGlobal = ($totalPagado + $totalDeuda) > 0
        ? ($totalPagado / ($totalPagado + $totalDeuda)) * 100 : 0;
    $colorGlobal = match(true) {
        $pctGlobal == 100 => 'success',
        $pctGlobal >= 75  => 'primary',
        $pctGlobal >= 50  => 'warning',
        default           => 'danger',
    };
@endphp

<div class="container-fluid cont-detalle-page">
    <!-- Page Header -->
    <div class="cont-detalle-header">
        <div>
            <h1><i class="ri-calculator-line me-2"></i>Detalle Contable</h1>
            <p>{{ $persona->nombres }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</p>
        </div>
        <div class="cont-header-actions">
            <a href="{{ route('admin.contabilidad.buscar') }}" class="btn-cont-header">
                <i class="ri-arrow-left-line"></i> Volver
            </a>
            <a href="{{ route('admin.estudiantes.detalle', $estudiante->id) }}" class="btn-cont-header">
                <i class="ri-user-line"></i> Ver Perfil
            </a>
        </div>
    </div>

    <!-- Student Card -->
    <div class="student-card">
        <div class="student-card-body">
            <div class="row align-items-center g-3">
                <div class="col-md-7">
                    <div class="student-info">
                        <div class="student-avatar">
                            {{ strtoupper(mb_substr($persona->nombres ?? 'P', 0, 1, 'UTF-8')) }}
                        </div>
                        <div>
                            <h4 class="student-name">
                                {{ $persona->nombres }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                            </h4>
                            <div class="student-badges">
                                <span class="student-badge"><i class="ri-id-card-line"></i>{{ $persona->carnet }}</span>
                                @if($persona->correo)
                                <span class="student-badge"><i class="ri-mail-line"></i>{{ $persona->correo }}</span>
                                @endif
                                @if($persona->celular)
                                <span class="student-badge"><i class="ri-phone-line"></i>{{ $persona->celular }}</span>
                                @endif
                            </div>
                            <div class="student-address">
                                <i class="ri-map-pin-line"></i>
                                {{ $persona->direccion ?? 'Sin dirección' }}
                                @if($persona->ciudad)
                                    · {{ $persona->ciudad->nombre ?? '' }},
                                    {{ optional($persona->ciudad->departamento)->nombre ?? '' }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="fin-summary">
                        <div class="fin-summary-header">
                            <span class="fin-summary-label">Estado financiero general</span>
                            <span class="fin-summary-pct val-{{ $colorGlobal }}">{{ number_format($pctGlobal, 1) }}%</span>
                        </div>
                        <div class="fin-summary-bar">
                            <div class="fin-summary-bar-fill bg-{{ $colorGlobal }}" style="width:{{ $pctGlobal }}%"></div>
                        </div>
                        <div class="fin-summary-stats">
                            <div class="fin-stat">
                                <div class="fin-stat-value" style="color:var(--cont-success);">{{ number_format($totalPagado, 2) }}</div>
                                <div class="fin-stat-label">Bs Pagado</div>
                            </div>
                            <div class="fin-stat">
                                <div class="fin-stat-value" style="color:var(--cont-danger);">{{ number_format($totalDeuda, 2) }}</div>
                                <div class="fin-stat-label">Bs Pendiente</div>
                            </div>
                            <div class="fin-stat">
                                <div class="fin-stat-value" style="color:var(--cont-primary);">{{ $totalCuotas }}</div>
                                <div class="fin-stat-label">Cuotas</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Programs and Cuotas -->
    @if ($estudiante->inscripciones->count() > 0)

        <div class="section-header">
            <div>
                <h5 class="section-title">Programas y Estado de Cuotas</h5>
                <p class="section-subtitle">
                    {{ $estudiante->inscripciones->count() }} programa(s) ·
                    {{ $cuotasPagTot }} pagadas · {{ $cuotasPenTot }} pendientes
                </p>
            </div>
            <span class="section-badge">
                <i class="ri-graduation-cap-line"></i>{{ $estudiante->inscripciones->count() }} Programas
            </span>
        </div>

        <div class="cont-accordion" id="accordionContable">
            @foreach ($estudiante->inscripciones->sortByDesc('fecha_registro') as $index => $inscripcion)
                @php
                    $oferta   = $inscripcion->ofertaAcademica;
                    $programa = $oferta->programa ?? null;
                    $cuotas   = $inscripcion->cuotas_ordenadas ?? $inscripcion->cuotas;

                    $deudaPrograma    = 0;
                    $pagadoPrograma   = 0;
                    $cuotasTotales    = $cuotas->count();
                    $cuotasPagadas    = 0;
                    $cuotasPendientes = 0;

                    foreach ($cuotas as $cuota) {
                        $deudaPrograma  += $cuota->pago_pendiente_bs;
                        $pagadoPrograma += $cuota->pago_total_bs - $cuota->pago_pendiente_bs;
                        if ($cuota->pago_terminado == 'si') { $cuotasPagadas++; }
                    }

                    $totalPrograma    = $deudaPrograma + $pagadoPrograma;
                    $porcentajePagado = $totalPrograma > 0 ? ($pagadoPrograma / $totalPrograma) * 100 : 0;
                    $cuotasPendientes = $cuotas->where('pago_terminado', '!=', 'si')->count();

                    $colorProg = match(true) {
                        $porcentajePagado == 100 => 'success',
                        $porcentajePagado >= 75  => 'primary',
                        $porcentajePagado >= 50  => 'warning',
                        default                  => 'danger',
                    };
                    $avatarColors = ['#0f766e','#10b981','#0891b2','#f59e0b','#ef4444'];
                    $avatarColor  = $avatarColors[$index % count($avatarColors)];
                    $inicial      = strtoupper(mb_substr($programa->nombre ?? 'P', 0, 1, 'UTF-8'));
                @endphp

                <div class="cont-accordion-item color-{{ $colorProg }}">
                    <h2 class="accordion-header" id="contableHeading{{ $index }}">
                        <button class="cont-accordion-btn {{ $index > 0 ? 'collapsed' : '' }}"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#contableCollapse{{ $index }}"
                                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-controls="contableCollapse{{ $index }}">

                            <div class="prog-avatar" style="background:{{ $avatarColor }};">{{ $inicial }}</div>

                            <div class="prog-info">
                                <h6 class="prog-name">{{ $programa->nombre ?? 'Programa no especificado' }}</h6>
                                <div class="prog-meta">
                                    @if(optional($oferta->modalidad)->nombre)
                                    <span class="prog-meta-item"><i class="ri-book-open-line"></i>{{ $oferta->modalidad->nombre }}</span>
                                    @endif
                                    @if(optional($oferta->sucursal)->nombre)
                                    <span class="prog-meta-item"><i class="ri-map-pin-line"></i>{{ $oferta->sucursal->nombre }}</span>
                                    @endif
                                    <span class="prog-meta-item"><i class="ri-file-list-3-line"></i>{{ optional($inscripcion->planesPago)->nombre ?? 'N/A' }}</span>
                                    <span class="prog-meta-item"><i class="ri-stack-line"></i>{{ $cuotasPagadas }}/{{ $cuotasTotales }} cuotas</span>
                                </div>
                            </div>

                            <div class="prog-right">
                                <div class="prog-badges">
                                    <span class="prog-badge-paid">{{ number_format($pagadoPrograma, 2) }} Bs</span>
                                    <span class="prog-badge-debt">{{ number_format($deudaPrograma, 2) }} Bs</span>
                                </div>
                                <div class="prog-progress">
                                    <div class="prog-progress-header">
                                        <span class="prog-progress-label">Avance</span>
                                        <span class="prog-progress-value" style="color:var(--cont-{{ $colorProg }});">{{ number_format($porcentajePagado, 0) }}%</span>
                                    </div>
                                    <div class="prog-progress-bar">
                                        <div class="prog-progress-fill bg-{{ $colorProg }}" style="width:{{ $porcentajePagado }}%"></div>
                                    </div>
                                </div>
                            </div>

                        </button>
                    </h2>

                    <div id="contableCollapse{{ $index }}"
                         class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                         aria-labelledby="contableHeading{{ $index }}">
                        <div class="cont-accordion-body">

                            @if ($cuotasPendientes > 0)
                                <div class="multi-pay-bar">
                                    <span class="multi-pay-text">
                                        <i class="ri-list-check-2" style="color:var(--cont-primary);"></i>
                                        <strong>{{ $cuotasPendientes }}</strong> cuota(s) con saldo pendiente
                                    </span>
                                    <button type="button"
                                            class="btn-multi-pay btn-pagar-multiple"
                                            data-estudiante-id="{{ $estudiante->id }}">
                                        <i class="ri-stack-line"></i> Pagar Múltiples
                                    </button>
                                </div>
                            @endif

                            <div class="prog-summary">
                                <div class="prog-summary-stats">
                                    <div class="prog-summary-stat">
                                        <div class="ps-label">Total Programa</div>
                                        <div class="ps-value">{{ number_format($totalPrograma, 2) }} Bs</div>
                                    </div>
                                    <div class="prog-summary-stat">
                                        <div class="ps-label">Pagado</div>
                                        <div class="ps-value" style="color:var(--cont-success);">{{ number_format($pagadoPrograma, 2) }} Bs</div>
                                    </div>
                                    <div class="prog-summary-stat">
                                        <div class="ps-label">Pendiente</div>
                                        <div class="ps-value" style="color:var(--cont-danger);">{{ number_format($deudaPrograma, 2) }} Bs</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Desglose por conceptos --}}
                            @php
                                $conceptos = [];
                                foreach ($cuotas as $cuota) {
                                    $tipo = $cuota->tipo ?? 'Otros';
                                    if (!isset($conceptos[$tipo])) {
                                        $conceptos[$tipo] = ['total' => 0, 'pagado' => 0, 'pendiente' => 0, 'cuotas' => 0];
                                    }
                                    $conceptos[$tipo]['total'] += $cuota->pago_total_bs;
                                    $conceptos[$tipo]['pagado'] += ($cuota->pago_total_bs - $cuota->pago_pendiente_bs);
                                    $conceptos[$tipo]['pendiente'] += $cuota->pago_pendiente_bs;
                                    $conceptos[$tipo]['cuotas']++;
                                }
                                $conceptoIcons = [
                                    'Matrícula'    => 'ri-graduation-cap-line',
                                    'Colegiatura'  => 'ri-book-open-line',
                                    'Certificación'=> 'ri-award-line',
                                    'Otros'        => 'ri-file-list-line',
                                ];
                            @endphp

                            @if (count($conceptos) > 1)
                                <div class="concept-section">
                                    <div class="concept-header">
                                        <div class="concept-title">
                                            <i class="ri-pie-chart-2-line"></i>
                                            Desglose por Concepto
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        @foreach ($conceptos as $nombre => $datos)
                                            @php
                                                $cIcon = $conceptoIcons[$nombre] ?? 'ri-file-list-line';
                                                $cPct = $datos['total'] > 0 ? ($datos['pagado'] / $datos['total']) * 100 : 0;
                                                $cColor = match(true) {
                                                    $cPct == 100 => 'success',
                                                    $cPct >= 75  => 'primary',
                                                    $cPct >= 50  => 'warning',
                                                    default      => 'danger',
                                                };
                                            @endphp
                                            <div class="col-md-6">
                                                <div class="concept-stat" style="border-left:3px solid var(--cont-{{ $cColor }});">
                                                    <div class="concept-header" style="margin-bottom:6px;">
                                                        <div class="concept-title" style="font-size:0.82rem;">
                                                            <i class="{{ $cIcon }}"></i>{{ $nombre }}
                                                        </div>
                                                        <div class="concept-badges">
                                                            <span class="concept-badge-paid">{{ number_format($datos['pagado'], 2) }}</span>
                                                            <span class="concept-badge-debt">{{ number_format($datos['pendiente'], 2) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="concept-stats" style="grid-template-columns:repeat(3,1fr);">
                                                        <div class="concept-stat" style="background:var(--cont-surface);border:none;">
                                                            <div class="concept-stat-label">Total</div>
                                                            <div class="concept-stat-value">{{ number_format($datos['total'], 2) }} Bs</div>
                                                        </div>
                                                        <div class="concept-stat" style="background:var(--cont-surface);border:none;">
                                                            <div class="concept-stat-label">Pagado</div>
                                                            <div class="concept-stat-value" style="color:var(--cont-success);">{{ number_format($datos['pagado'], 2) }} Bs</div>
                                                        </div>
                                                        <div class="concept-stat" style="background:var(--cont-surface);border:none;">
                                                            <div class="concept-stat-label">Pendiente</div>
                                                            <div class="concept-stat-value" style="color:var(--cont-danger);">{{ number_format($datos['pendiente'], 2) }} Bs</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="cuotas-table">
                                    <thead>
                                        <tr>
                                            <th style="width:5%;text-align:center;">#</th>
                                            <th style="width:26%;">Cuota</th>
                                            <th style="width:12%;text-align:center;">Total</th>
                                            <th style="width:12%;text-align:center;">Pagado</th>
                                            <th style="width:12%;text-align:center;">Pendiente</th>
                                            <th style="width:13%;text-align:center;">Fecha</th>
                                            <th style="width:11%;text-align:center;">Estado</th>
                                            <th style="width:9%;text-align:center;">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cuotas as $cuota)
                                            @php
                                                $pagado       = $cuota->pago_total_bs - $cuota->pago_pendiente_bs;
                                                $tienePagos   = $cuota->pagos_cuotas->count() > 0;
                                                $pctCuota     = $cuota->pago_total_bs > 0
                                                    ? ($pagado / $cuota->pago_total_bs) * 100 : 0;
                                            @endphp
                                            <tr class="{{ $cuota->pago_terminado == 'si' ? 'row-pagado' : ($pagado > 0 ? 'row-parcial' : '') }}">
                                                <td style="text-align:center;">
                                                    <span class="cuota-num-badge">{{ $cuota->n_cuota }}</span>
                                                </td>
                                                <td>
                                                    <div class="cuota-name">{{ $cuota->nombre }}</div>
                                                    @if ($tienePagos)
                                                        <div class="cuota-payments">
                                                            <i class="ri-receipt-line me-1"></i>{{ $cuota->pagos_cuotas->count() }} pago(s)
                                                        </div>
                                                    @endif
                                                    <div class="cuota-progress">
                                                        <div class="cuota-progress-fill {{ $cuota->pago_terminado == 'si' ? 'bg-success' : 'bg-warning' }}"
                                                             style="width:{{ $pctCuota }}%"></div>
                                                    </div>
                                                </td>
                                                <td style="text-align:center;font-weight:600;">{{ number_format($cuota->pago_total_bs, 2) }}</td>
                                                <td style="text-align:center;font-weight:600;color:var(--cont-success);">{{ number_format($pagado, 2) }}</td>
                                                <td style="text-align:center;">
                                                    @if ($cuota->pago_pendiente_bs > 0)
                                                        <span style="font-weight:600;color:var(--cont-danger);">{{ number_format($cuota->pago_pendiente_bs, 2) }}</span>
                                                    @else
                                                        <span style="color:var(--cont-text-muted);">—</span>
                                                    @endif
                                                </td>
                                                <td style="text-align:center;color:var(--cont-text-muted);font-size:0.8rem;">
                                                    @if ($cuota->fecha_pago)
                                                        {{ \Carbon\Carbon::parse($cuota->fecha_pago)->format('d/m/Y') }}
                                                    @else
                                                        <span style="color:var(--cont-text-muted);">—</span>
                                                    @endif
                                                </td>
                                                <td style="text-align:center;">
                                                    @if ($cuota->pago_terminado == 'si')
                                                        <span class="estado-badge-cont pagado"><i class="ri-check-line"></i>Pagado</span>
                                                    @else
                                                        <span class="estado-badge-cont pendiente"><i class="ri-time-line"></i>Pendiente</span>
                                                    @endif
                                                </td>
                                                <td style="text-align:center;">
                                                    <div class="d-flex gap-1 justify-content-center">
                                                        @if ($cuota->pago_pendiente_bs > 0)
                                                            <button class="cuota-action-btn pay btn-pagar-cuota"
                                                                    data-cuota-id="{{ $cuota->id }}"
                                                                    data-estudiante-id="{{ $estudiante->id }}"
                                                                    title="Pagar cuota">
                                                                <i class="ri-money-dollar-circle-line"></i>
                                                            </button>
                                                        @endif
                                                        @if ($tienePagos)
                                                            <button class="cuota-action-btn receipts btn-ver-recibos"
                                                                    data-cuota-id="{{ $cuota->id }}"
                                                                    data-cuota-nombre="{{ $cuota->nombre }}"
                                                                    title="Ver recibos ({{ $cuota->pagos_cuotas->count() }})">
                                                                <i class="ri-receipt-line"></i>
                                                                <span class="receipt-count">{{ $cuota->pagos_cuotas->count() }}</span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" style="text-align:right;color:var(--cont-text-muted);font-size:0.78rem;">Totales del programa:</td>
                                            <td style="text-align:center;font-weight:700;">{{ number_format($totalPrograma, 2) }}</td>
                                            <td style="text-align:center;font-weight:700;color:var(--cont-success);">{{ number_format($pagadoPrograma, 2) }}</td>
                                            <td style="text-align:center;font-weight:700;color:var(--cont-danger);">{{ number_format($deudaPrograma, 2) }}</td>
                                            <td colspan="3"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Footer Summary -->
        <div class="footer-summary">
            <div class="footer-stat">
                <div class="footer-stat-icon" style="background:var(--cont-primary-light);color:var(--cont-primary);">
                    <i class="ri-money-dollar-circle-line"></i>
                </div>
                <div>
                    <div class="footer-stat-value">{{ number_format($totalPagado + $totalDeuda, 2) }} Bs</div>
                    <div class="footer-stat-label">Monto Total General</div>
                </div>
            </div>
            <div class="footer-stat">
                <div class="footer-stat-icon" style="background:var(--cont-success-light);color:var(--cont-success);">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
                <div>
                    <div class="footer-stat-value" style="color:var(--cont-success);">{{ number_format($totalPagado, 2) }} Bs</div>
                    <div class="footer-stat-label">Total Pagado</div>
                </div>
            </div>
            <div class="footer-stat">
                <div class="footer-stat-icon" style="background:var(--cont-danger-light);color:var(--cont-danger);">
                    <i class="ri-alert-line"></i>
                </div>
                <div>
                    <div class="footer-stat-value" style="color:var(--cont-danger);">{{ number_format($totalDeuda, 2) }} Bs</div>
                    <div class="footer-stat-label">Total Deuda</div>
                </div>
            </div>
        </div>

    @else
        <div class="empty-state-cont">
            <div class="empty-state-cont-icon">
                <i class="ri-graduation-cap-line"></i>
            </div>
            <h5>No hay programas inscritos</h5>
            <p>El participante no está inscrito en ningún programa.</p>
        </div>
    @endif

    {{-- Modales --}}
    @include('admin.estudiantes.partials.modal-pagar-cuota')
    @include('admin.contabilidad.partials.modal-pagar-contabilidad')
    @include('admin.estudiantes.partials.modal-recibos-cuota')

</div>

@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var pcCuotasData = {};

        function togglePcFields() {
            var tipo = document.getElementById('pc_tipo_pago').value;
            document.getElementById('pc_campo_caja').style.display        = 'none';
            document.getElementById('pc_campo_cuenta').style.display      = 'none';
            document.getElementById('pc_campo_comprobante').style.display  = 'none';
            document.getElementById('pc_caja_id').removeAttribute('required');
            document.getElementById('pc_cuenta_id').removeAttribute('required');
            document.getElementById('pc_n_comprobante').removeAttribute('required');

            if (tipo === 'Efectivo') {
                document.getElementById('pc_campo_caja').style.display = 'block';
                document.getElementById('pc_caja_id').setAttribute('required', 'required');
            } else if (['Transferencia', 'Depósito', 'Tarjeta'].includes(tipo)) {
                document.getElementById('pc_campo_cuenta').style.display      = 'block';
                document.getElementById('pc_campo_comprobante').style.display  = 'block';
                document.getElementById('pc_cuenta_id').setAttribute('required', 'required');
                document.getElementById('pc_n_comprobante').setAttribute('required', 'required');
            }
        }

        $(document).ready(function () {
            $('.accordion-button').on('click', function () {
                $(this).toggleClass('collapsed');
            });

            function pcActualizarResumen() {
                var monto     = parseFloat($('#pc_monto_pago').val()) || 0;
                var descuento = parseFloat($('#pc_descuento').val()) || 0;
                var total     = Math.max(0, monto - descuento);
                var pendSel   = parseFloat($('#pc_pendiente_total').text()) || 0;
                var pct       = pendSel > 0 ? Math.min(100, (total / pendSel) * 100) : 0;

                $('#pc_res_monto').text(monto.toFixed(2) + ' Bs');
                $('#pc_res_desc').text(descuento.toFixed(2) + ' Bs');
                $('#pc_res_total').text(total.toFixed(2) + ' Bs');
                $('#pc_progreso').css('width', pct.toFixed(1) + '%');
                $('#pc_txt_progreso').text(pct.toFixed(1) + '% del total seleccionado');
            }

            function pcActualizarTotales() {
                var total = 0, count = 0;
                $('.pc-cuota-check-input:checked').each(function () {
                    total += pcCuotasData[$(this).val()] || 0;
                    count++;
                });
                $('#pc_pendiente_total').text(total.toFixed(2));
                $('#totalSelBadge').text(total.toFixed(2) + ' Bs');
                $('#pc_monto_pago').val(total.toFixed(2));
                $('#pc_res_cuotas').text(count);
                pcActualizarResumen();
            }

            function pcCargarCuotas(estudianteId, preseleccionarId) {
                $('#listaCuotasPago').html('<div class="pc-empty-state"><div class="spinner-border" role="status" style="color:var(--cont-primary);width:24px;height:24px;"></div><p class="mt-2 mb-0">Cargando cuotas pendientes...</p></div>');
                pcCuotasData = {};

                $.ajax({
                    url: '/admin/estudiantes/' + estudianteId + '/cuotas-pendientes',
                    type: 'GET',
                    success: function (response) {
                        if (!response.success || !response.programas || response.programas.length === 0) {
                            $('#listaCuotasPago').html('<div class="pc-empty-state"><i class="ri-file-list-3-line d-block"></i><p class="mb-0">No hay cuotas pendientes.</p></div>');
                            return;
                        }

                        var tipoClsMap = {
                            'Matrícula':    'tipo-matricula',
                            'Colegiatura':  'tipo-colegiatura',
                            'Certificación':'tipo-certificacion',
                            'Otros':        'tipo-otros',
                        };

                        var html = '';
                        response.programas.forEach(function (prog) {
                            var progTotal = prog.cuotas.reduce(function(s, c) { return s + c.pendiente_bs; }, 0);
                            html += '<div class="pc-prog-group">';
                            html += '<div class="pc-prog-group-header">';
                            html += '<span class="pc-prog-group-title"><i class="ri-graduation-cap-line"></i>' + prog.programa + '</span>';
                            html += '<span class="pc-prog-group-total">' + progTotal.toFixed(2) + ' Bs</span>';
                            html += '</div>';

                            prog.cuotas.forEach(function (cuota) {
                                pcCuotasData[cuota.id] = cuota.pendiente_bs;
                                var checked  = (preseleccionarId && cuota.id == preseleccionarId) ? 'checked' : '';
                                var selCls   = checked ? 'selected' : '';
                                var tipoCls  = tipoClsMap[cuota.tipo] || 'tipo-otros';
                                html += '<div class="pc-cuota-item ' + selCls + '" data-cuota-id="' + cuota.id + '">';
                                html += '<input class="pc-cuota-check-input" type="checkbox" value="' + cuota.id + '" ' + checked + '>';
                                html += '<div class="pc-cuota-check"><i class="ri-check-line"></i></div>';
                                html += '<div class="pc-cuota-info">';
                                html += '<div class="pc-cuota-name">' + cuota.nombre + '</div>';
                                html += '<div class="pc-cuota-meta">';
                                html += '<span class="pc-cuota-pending">Pendiente: ' + cuota.pendiente_bs.toFixed(2) + ' Bs</span>';
                                html += '<span class="pc-cuota-tipo ' + tipoCls + '">' + cuota.tipo + '</span>';
                                html += '</div></div>';
                                html += '<div class="pc-cuota-amount">' + cuota.pendiente_bs.toFixed(2) + '</div>';
                                html += '</div>';
                            });
                            html += '</div>';
                        });

                        $('#listaCuotasPago').html(html);
                        pcActualizarTotales();
                    },
                    error: function () {
                        $('#listaCuotasPago').html('<div class="alert alert-danger mb-0">Error al cargar cuotas.</div>');
                    }
                });
            }

            $(document).on('change', '.pc-cuota-check-input', function () {
                $(this).closest('.pc-cuota-item').toggleClass('selected', this.checked);
                pcActualizarTotales();
            });

            $(document).on('click', '.pc-cuota-item', function(e) {
                if (e.target.type !== 'checkbox') {
                    var cb = $(this).find('.pc-cuota-check-input');
                    cb.prop('checked', !cb.prop('checked'));
                    $(this).toggleClass('selected', cb.prop('checked'));
                    pcActualizarTotales();
                }
            });

            $('#btnSeleccionarTodas').on('click', function () {
                $('.pc-cuota-check-input').prop('checked', true).closest('.pc-cuota-item').addClass('selected');
                pcActualizarTotales();
            });

            $('#btnDeseleccionarTodas').on('click', function () {
                $('.pc-cuota-check-input').prop('checked', false).closest('.pc-cuota-item').removeClass('selected');
                pcActualizarTotales();
            });

            $(document).on('input', '#pc_monto_pago, #pc_descuento', pcActualizarResumen);

            var scSaldoPendiente = 0;

            function scActualizarResumen() {
                var monto     = parseFloat($('#monto_pago').val()) || 0;
                var descuento = parseFloat($('#descuento').val()) || 0;
                if (monto > scSaldoPendiente) { monto = scSaldoPendiente; $('#monto_pago').val(monto.toFixed(2)); }
                if (descuento > monto)        { descuento = monto;        $('#descuento').val(descuento.toFixed(2)); }
                var total = Math.max(0, monto - descuento);
                var pct   = scSaldoPendiente > 0 ? Math.min(100, (monto / scSaldoPendiente) * 100) : 0;
                $('#resumen-monto').text(monto.toFixed(2) + ' Bs');
                $('#resumen-descuento').text(descuento.toFixed(2) + ' Bs');
                $('#resumen-total').text(total.toFixed(2) + ' Bs');
                $('#progreso-pago').css('width', pct.toFixed(1) + '%');
                $('#texto-progreso').text(pct.toFixed(1) + '% del saldo pendiente');
            }

            $(document).on('input', '#monto_pago, #descuento', scActualizarResumen);

            $(document).on('click', '.btn-pagar-cuota', function () {
                var cuotaId      = $(this).data('cuota-id');
                var estudianteId = $(this).data('estudiante-id');

                $('#formPagarCuota')[0].reset();
                $('#cuota_id').val(cuotaId);
                $('#estudiante_id').val(estudianteId);
                $('#fecha_pago').val(new Date().toISOString().split('T')[0]);
                togglePaymentFields();
                $('#modalPagarCuota').modal('show');

                $.ajax({
                    url: '/admin/estudiantes/' + estudianteId + '/cuota/' + cuotaId,
                    type: 'GET',
                    success: function (r) {
                        if (r.success) {
                            var total     = parseFloat(r.cuota.pago_total_bs)    || 0;
                            var pendiente = parseFloat(r.cuota.pago_pendiente_bs) || 0;
                            var pagado    = parseFloat(r.cuota.saldo_pagado)      || 0;
                            scSaldoPendiente = pendiente;

                            $('#info-cuota-nombre').text(r.cuota.nombre);
                            $('#info-cuota-programa').text(r.cuota.programa);
                            $('#info-cuota-total').text(total.toFixed(2));
                            $('#info-cuota-pagado').text(pagado.toFixed(2));
                            $('#info-cuota-pendiente').text(pendiente.toFixed(2));
                            $('#maximo_pago').text(pendiente.toFixed(2));
                            $('#monto_pago').val(pendiente.toFixed(2)).attr('max', pendiente);
                            scActualizarResumen();
                        } else {
                            $('#modalPagarCuota').modal('hide');
                            Swal.fire('Error', r.msg || 'No se pudo cargar la cuota.', 'error');
                        }
                    },
                    error: function () {
                        $('#modalPagarCuota').modal('hide');
                        Swal.fire('Error', 'No se pudo cargar la información de la cuota.', 'error');
                    }
                });
            });

            $(document).on('submit', '#formPagarCuota', function (e) {
                e.preventDefault();
                var estudianteId = $('#estudiante_id').val();
                var tipo = $('#tipo_pago').val();

                if (!tipo) { Swal.fire('Atención', 'Seleccione el tipo de pago.', 'warning'); return; }
                if (tipo === 'Efectivo' && !$('#caja_id').val()) {
                    Swal.fire('Atención', 'Seleccione una caja.', 'warning'); return;
                }
                if (['Transferencia', 'Depósito', 'Tarjeta'].includes(tipo) && !$('#cuenta_bancaria_id').val()) {
                    Swal.fire('Atención', 'Seleccione una cuenta bancaria.', 'warning'); return;
                }

                var $btn = $(this).find('[type=submit]');
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Procesando...');

                $.ajax({
                    url: '/admin/estudiantes/' + estudianteId + '/pagar-cuota',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (r) {
                        if (r.success) {
                            $('#modalPagarCuota').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: '¡Pago Registrado!',
                                html: '<b>Recibo:</b> ' + r.recibo +
                                      '<br><a href="' + r.pdf_url + '" target="_blank" class="btn btn-sm btn-primary mt-2">' +
                                      '<i class="ri-download-line me-1"></i>Descargar Recibo PDF</a>',
                                confirmButtonText: 'Cerrar'
                            }).then(function () { location.reload(); });
                        } else {
                            Swal.fire('Error', r.msg, 'error');
                        }
                    },
                    error: function (xhr) {
                        var msg = 'Error al registrar el pago.';
                        if (xhr.responseJSON) msg = xhr.responseJSON.msg || msg;
                        Swal.fire('Error', msg, 'error');
                    },
                    complete: function () {
                        $btn.prop('disabled', false).html('<i class="ri-checkbox-circle-line me-1"></i>Registrar Pago');
                    }
                });
            });

            $(document).on('click', '.btn-pagar-multiple', function () {
                var estudianteId = $(this).data('estudiante-id');

                $('#pc_estudiante_id').val(estudianteId);
                $('#formPagarContabilidad')[0].reset();
                $('#pc_tipo_pago').val('');
                togglePcFields();
                pcCargarCuotas(estudianteId, null);
                $('#modalPagarContabilidad').modal('show');
            });

            $('#btnRegistrarPagoContabilidad').on('click', function () {
                var cuotaIds = [];
                $('.pc-cuota-check-input:checked').each(function () { cuotaIds.push($(this).val()); });

                if (cuotaIds.length === 0) {
                    Swal.fire('Atención', 'Debe seleccionar al menos una cuota.', 'warning'); return;
                }
                var tipo = $('#pc_tipo_pago').val();
                if (!tipo) {
                    Swal.fire('Atención', 'Debe seleccionar el tipo de pago.', 'warning'); return;
                }
                if (tipo === 'Efectivo' && !$('#pc_caja_id').val()) {
                    Swal.fire('Atención', 'Debe seleccionar una caja.', 'warning'); return;
                }
                if (['Transferencia', 'Depósito', 'Tarjeta'].includes(tipo) && !$('#pc_cuenta_id').val()) {
                    Swal.fire('Atención', 'Debe seleccionar una cuenta bancaria.', 'warning'); return;
                }
                var monto = parseFloat($('#pc_monto_pago').val()) || 0;
                if (monto <= 0) {
                    Swal.fire('Atención', 'El monto debe ser mayor a cero.', 'warning'); return;
                }

                var estudianteId = $('#pc_estudiante_id').val();
                var formData = new FormData(document.getElementById('formPagarContabilidad'));
                cuotaIds.forEach(function (id) { formData.append('cuota_ids[]', id); });

                var btn = $(this);
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Registrando...');

                $.ajax({
                    url: '/admin/estudiantes/' + estudianteId + '/pagar-multiples-cuotas',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Pago Registrado!',
                                html: '<b>Recibo:</b> ' + response.recibo +
                                      '<br><a href="' + response.pdf_url + '" target="_blank" class="btn btn-sm btn-primary mt-2">' +
                                      '<i class="ri-download-line me-1"></i>Descargar Recibo PDF</a>',
                                confirmButtonText: 'Cerrar'
                            }).then(function () {
                                $('#modalPagarContabilidad').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.msg || 'No se pudo registrar el pago.', 'error');
                        }
                    },
                    error: function (xhr) {
                        var msg = 'Error al registrar el pago.';
                        if (xhr.responseJSON) msg = xhr.responseJSON.msg || xhr.responseJSON.message || msg;
                        Swal.fire('Error', msg, 'error');
                    },
                    complete: function () {
                        btn.prop('disabled', false).html('<i class="ri-checkbox-circle-line me-1"></i>Registrar Pago');
                    }
                });
            });

            $(document).on('click', '.btn-ver-recibos', function () {
                var cuotaId     = $(this).data('cuota-id');
                var cuotaNombre = $(this).data('cuota-nombre');

                $('#modalRecibosTitle').text('Recibos de: ' + cuotaNombre);
                $('#modalRecibosCuota').modal('show');

                $('#contenidoRecibos').html(
                    '<div class="text-center py-5">' +
                    '<div class="spinner-border text-primary" role="status"></div>' +
                    '<p class="mt-2">Cargando recibos...</p></div>'
                );

                $.ajax({
                    url: '/admin/estudiantes/cuota/' + cuotaId + '/recibos',
                    type: 'GET',
                    success: function (response) {
                        if (response.success) {
                            $('#contenidoRecibos').html(response.html);
                        } else {
                            $('#contenidoRecibos').html('<div class="alert alert-danger">' + (response.msg || 'Error') + '</div>');
                        }
                    },
                    error: function () {
                        $('#contenidoRecibos').html('<div class="alert alert-danger">Error al cargar los recibos.</div>');
                    }
                });
            });
        });
    </script>
@endpush
