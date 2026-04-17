@extends('admin.dashboard')

@section('admin')
    @php
        $primaryColor = '#032a4a';
        $accentColor = '#5ec9b1';
        $accentLight = '#e8faf5';
        $accentDark = '#3ba893';
    @endphp

    <style>
        :root {
            --plan-primary: {{ $primaryColor }};
            --plan-accent: {{ $accentColor }};
            --plan-accent-light: {{ $accentLight }};
            --plan-accent-dark: {{ $accentDark }};
            --plan-surface: #f8fafc;
            --plan-surface-2: #ffffff;
            --plan-border: #e2e8f0;
            --plan-text: #1e293b;
            --plan-text-muted: #64748b;
            --plan-danger: #ef4444;
            --plan-warning: #f59e0b;
            --plan-success: #10b981;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --shadow-sm: 0 1px 2px rgba(3, 42, 74, 0.04), 0 1px 3px rgba(3, 42, 74, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(3, 42, 74, 0.06), 0 2px 4px -2px rgba(3, 42, 74, 0.04);
            --shadow-lg: 0 10px 15px -3px rgba(3, 42, 74, 0.08), 0 4px 6px -4px rgba(3, 42, 74, 0.04);
        }

        .planes-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--plan-text);
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .page-header {
            position: relative;
            background: linear-gradient(135deg, var(--plan-primary) 0%, #021e35 100%);
            border-radius: var(--radius-xl);
            padding: 32px 36px;
            color: white;
            margin-bottom: 28px;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(94, 201, 177, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(94, 201, 177, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .page-header-content {
            position: relative;
            z-index: 1;
        }

        .page-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header h1 i {
            color: var(--plan-accent);
        }

        .page-header-subtitle {
            font-size: 0.95rem;
            opacity: 0.9;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .page-header-meta {
            position: relative;
            z-index: 1;
        }

        .header-badge {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            padding: 10px 18px;
            border-radius: var(--radius-md);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
        }

        .header-badge i {
            color: var(--plan-accent);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr; }
        }

        .stat-card {
            background: var(--plan-surface-2);
            border-radius: var(--radius-lg);
            padding: 24px;
            border: 1px solid var(--plan-border);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card.inscritos::before { background: var(--plan-accent); }
        .stat-card.esperado::before { background: var(--plan-success); }
        .stat-card.recaudado::before { background: var(--plan-warning); }
        .stat-card.deuda::before { background: var(--plan-danger); }

        .stat-card-content {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--plan-text-muted);
            margin-bottom: 8px;
        }

        .stat-value {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .stat-card.inscritos .stat-value { color: var(--plan-accent-dark); }
        .stat-card.esperado .stat-value { color: var(--plan-success); }
        .stat-card.recaudado .stat-value { color: var(--plan-warning); }
        .stat-card.deuda .stat-value { color: var(--plan-danger); }

        .stat-subtitle {
            font-size: 0.8rem;
            color: var(--plan-text-muted);
            margin-top: 4px;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-card.inscritos .stat-icon { background: var(--plan-accent-light); color: var(--plan-accent-dark); }
        .stat-card.esperado .stat-icon { background: rgba(16, 185, 129, 0.1); color: var(--plan-success); }
        .stat-card.recaudado .stat-icon { background: rgba(245, 158, 11, 0.1); color: var(--plan-warning); }
        .stat-card.deuda .stat-icon { background: rgba(239, 68, 68, 0.1); color: var(--plan-danger); }

        .stat-progress {
            margin-top: 12px;
            height: 6px;
            background: var(--plan-border);
            border-radius: 3px;
            overflow: hidden;
        }

        .stat-progress-bar {
            height: 100%;
            border-radius: 3px;
            transition: width 1s ease-out;
        }

        .stat-card.esperado .stat-progress-bar { background: var(--plan-success); }
        .stat-card.recaudado .stat-progress-bar { background: var(--plan-warning); }
        .stat-card.deuda .stat-progress-bar { background: var(--plan-danger); }

        /* Section Header */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--plan-text);
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
        }

        .section-title i {
            color: var(--plan-accent);
            font-size: 1.35rem;
        }

        .section-subtitle {
            font-size: 0.9rem;
            color: var(--plan-text-muted);
            margin-top: 4px;
        }

        .btn-new-plan {
            background: linear-gradient(135deg, var(--plan-accent) 0%, var(--plan-accent-dark) 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(94, 201, 177, 0.3);
        }

        .btn-new-plan:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(94, 201, 177, 0.4);
            color: white;
        }

        .btn-new-plan i {
            font-size: 1.1rem;
        }

        /* Plan Card */
        .plan-card {
            background: var(--plan-surface-2);
            border-radius: var(--radius-xl);
            border: 1px solid var(--plan-border);
            margin-bottom: 24px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeInUp 0.4s ease-out;
            animation-fill-mode: both;
        }

        .plan-card:nth-child(1) { animation-delay: 0.05s; }
        .plan-card:nth-child(2) { animation-delay: 0.1s; }
        .plan-card:nth-child(3) { animation-delay: 0.15s; }
        .plan-card:nth-child(4) { animation-delay: 0.2s; }
        .plan-card:nth-child(5) { animation-delay: 0.25s; }

        .plan-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
            border-color: var(--plan-accent-light);
        }

        .plan-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            background: linear-gradient(135deg, var(--plan-primary) 0%, #021e35 100%);
            color: white;
        }

        .plan-card-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .plan-avatar {
            width: 52px;
            height: 52px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .plan-avatar i {
            font-size: 1.5rem;
            color: var(--plan-accent);
        }

        .plan-name {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .plan-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .plan-meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .plan-meta-item i {
            color: var(--plan-accent);
        }

        .plan-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .badge-promocion {
            background: linear-gradient(135deg, #ffd700 0%, #ffa500 100%);
            color: #000;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-vigente {
            background: var(--plan-success);
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-con-inscritos {
            background: rgba(245, 158, 11, 0.2);
            color: #b45309;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-bloqueado {
            background: rgba(100, 116, 139, 0.2);
            color: #475569;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-delete-plan {
            background: rgba(239, 68, 68, 0.1);
            color: var(--plan-danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-delete-plan:hover {
            background: var(--plan-danger);
            color: white;
        }

        .plan-card-body {
            padding: 24px;
        }

        /* Table Styles */
        .plan-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .plan-table thead th {
            background: var(--plan-surface);
            padding: 14px 16px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--plan-text-muted);
            border-bottom: 1px solid var(--plan-border);
            text-align: left;
        }

        .plan-table thead th:first-child {
            border-radius: var(--radius-sm) 0 0 0;
        }

        .plan-table thead th:last-child {
            border-radius: 0 var(--radius-sm) 0 0;
        }

        .plan-table tbody td {
            padding: 16px;
            border-bottom: 1px solid var(--plan-border);
            vertical-align: middle;
        }

        .plan-table tbody tr:last-child td {
            border-bottom: none;
        }

        .plan-table tbody tr:hover {
            background: var(--plan-surface);
        }

        .concepto-name {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: var(--plan-text);
        }

        .concepto-icon {
            width: 36px;
            height: 36px;
            background: var(--plan-accent-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .concepto-icon i {
            color: var(--plan-accent-dark);
        }

        .badge-cuotas {
            background: var(--plan-accent-light);
            color: var(--plan-accent-dark);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .precio-regular {
            font-weight: 600;
            color: var(--plan-text-muted);
            text-decoration: line-through;
        }

        .badge-descuento {
            background: rgba(239, 68, 68, 0.1);
            color: var(--plan-danger);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .precio-final {
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--plan-accent-dark);
        }

        .plan-table-footer {
            background: var(--plan-surface);
            padding: 16px 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            border-top: 1px solid var(--plan-border);
        }

        .plan-total-label {
            font-weight: 600;
            color: var(--plan-text);
        }

        .plan-total-value {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--plan-accent-dark);
        }

        .plan-card-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 20px;
            border-top: 1px solid var(--plan-border);
            margin-top: 20px;
        }

        .btn-edit-plan {
            background: var(--plan-accent-light);
            color: var(--plan-accent-dark);
            border: 1px solid rgba(94, 201, 177, 0.3);
            padding: 10px 20px;
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-edit-plan:hover {
            background: var(--plan-accent);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 40px;
            background: var(--plan-surface-2);
            border-radius: var(--radius-xl);
            border: 2px dashed var(--plan-border);
        }

        .empty-state-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--plan-accent-light) 0%, rgba(94, 201, 177, 0.05) 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        .empty-state-icon i {
            font-size: 3.5rem;
            color: var(--plan-accent);
        }

        .empty-state h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--plan-text);
            margin-bottom: 12px;
        }

        .empty-state p {
            color: var(--plan-text-muted);
            font-size: 1rem;
            max-width: 500px;
            margin: 0 auto 24px;
        }

        /* Modal Styles */
        .modal-header-custom {
            background: linear-gradient(135deg, var(--plan-primary) 0%, #021e35 100%);
            color: white;
            border: none;
            padding: 24px;
        }

        .modal-header-custom .modal-title {
            color: white;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
        }

        .modal-body-custom {
            padding: 24px;
        }

        .modal-section-card {
            background: var(--plan-surface-2);
            border-radius: var(--radius-lg);
            border: 1px solid var(--plan-border);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .modal-section-header {
            background: linear-gradient(135deg, var(--plan-primary) 0%, #021e35 100%);
            color: white;
            padding: 14px 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-section-header i {
            color: var(--plan-accent);
        }

        .modal-section-body {
            padding: 20px;
        }

        .form-label-custom {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--plan-text);
            margin-bottom: 8px;
        }

        .form-select-custom, .form-control-custom {
            display: block;
            width: 100%;
            border: 1px solid var(--plan-border);
            border-radius: var(--radius-sm);
            padding: 10px 14px;
            font-size: 0.9rem;
            transition: all 0.2s;
            background-color: #fff;
        }

        .form-select-custom {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 12px;
            padding-right: 36px;
        }

        .form-select-custom:focus, .form-control-custom:focus {
            border-color: var(--plan-accent);
            box-shadow: 0 0 0 3px rgba(94, 201, 177, 0.15);
            outline: none;
        }

        .modal-footer-custom {
            background: var(--plan-surface);
            padding: 16px 24px;
            border-top: 1px solid var(--plan-border);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn-cancel-modal {
            background: var(--plan-surface);
            color: var(--plan-text-muted);
            border: 1px solid var(--plan-border);
            padding: 10px 20px;
            border-radius: var(--radius-md);
            font-weight: 600;
        }

        .btn-cancel-modal:hover {
            background: var(--plan-border);
        }

        .btn-save-modal {
            background: linear-gradient(135deg, var(--plan-accent) 0%, var(--plan-accent-dark) 100%);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: var(--radius-md);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(94, 201, 177, 0.3);
        }

        .btn-save-modal:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(94, 201, 177, 0.4);
            color: white;
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 24px;
            }

            .page-header h1 {
                font-size: 1.35rem;
            }

            .page-header-subtitle {
                flex-direction: column;
                gap: 8px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .plan-card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .plan-card-actions {
                flex-direction: column;
            }

            .btn-edit-plan, .btn-delete-plan {
                width: 100%;
                justify-content: center;
            }
        }

        .modal-header-icon {
            width: 48px;
            height: 48px;
            background: rgba(94, 201, 177, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-header-icon i {
            font-size: 1.5rem;
            color: var(--plan-accent);
        }

        .form-hint {
            font-size: 0.8rem;
            color: var(--plan-text-muted);
            margin-top: 4px;
        }

        .alert-navy {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: var(--radius-sm);
            padding: 12px 16px;
        }

        .btn-add-concepto {
            background: var(--plan-accent-light);
            color: var(--plan-accent-dark);
            border: 1px solid rgba(94, 201, 177, 0.3);
            padding: 6px 14px;
            border-radius: var(--radius-sm);
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
        }

        .btn-add-concepto:hover {
            background: var(--plan-accent);
            color: white;
        }

        .info-alert {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            color: var(--plan-text);
            font-size: 0.85rem;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .info-alert i {
            color: #3b82f6;
            font-size: 1.1rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .warning-alert {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            color: var(--plan-warning);
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .warning-alert i {
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .conceptos-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .concepto-card {
            background: var(--plan-surface);
            border-radius: var(--radius-md);
            border: 1px solid var(--plan-border);
            overflow: hidden;
            animation: fadeInUp 0.3s ease-out;
        }

        .concepto-card-header {
            background: var(--plan-surface-2);
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--plan-border);
        }

        .concepto-label {
            font-weight: 600;
            color: var(--plan-text);
            font-size: 0.9rem;
        }

        .btn-remove-concepto {
            background: rgba(239, 68, 68, 0.1);
            color: var(--plan-danger);
            border: none;
            padding: 6px 10px;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
        }

        .btn-remove-concepto:hover {
            background: var(--plan-danger);
            color: white;
        }

        .concepto-card-body {
            padding: 16px;
        }

        .promo-inner {
            background: rgba(94, 201, 177, 0.05);
            border: 1px solid rgba(94, 201, 177, 0.2);
            border-radius: var(--radius-sm);
            padding: 12px;
        }

        .promo-summary {
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: var(--radius-sm);
            padding: 14px 16px;
        }

        .promo-summary i {
            color: var(--plan-success);
        }

        .promo-summary-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .promo-summary-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .promo-summary-label {
            font-size: 0.75rem;
            color: var(--plan-text-muted);
        }

        .promo-summary-value {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .text-success {
            color: var(--plan-success) !important;
        }

        .text-danger {
            color: var(--plan-danger) !important;
        }

        .promo-toggle-container {
            background: var(--plan-surface);
            border-radius: var(--radius-md);
            padding: 16px 20px;
        }

        .form-check-input:checked {
            background-color: var(--plan-accent);
            border-color: var(--plan-accent);
        }

        .form-check-input:focus {
            border-color: var(--plan-accent);
            box-shadow: 0 0 0 3px rgba(94, 201, 177, 0.15);
        }
    </style>

    <div class="container-fluid planes-page">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-4">
                <div class="page-header-content">
                    <h1><i class="ri-money-dollar-circle-fill"></i>Planes de Pago</h1>
                    <div class="page-header-subtitle">
                        <span><i class="ri-bookmark-line"></i> {{ $oferta->programa->nombre ?? 'Sin programa' }}</span>
                        <span><i class="ri-bank-card-line"></i> {{ $oferta->posgrado->convenio->nombre ?? 'Sin convenio' }}</span>
                    </div>
                </div>
                <div class="page-header-meta">
                    <span class="header-badge">
                        <i class="ri-hashtag"></i> {{ $oferta->codigo }}
                    </span>
                    <span class="header-badge ms-2">
                        <i class="ri-building-line"></i> {{ $oferta->sucursal->sede->nombre ?? 'Sin sede' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card inscritos">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-label">Total Inscritos</div>
                        <div class="stat-value">{{ $informacionFinanciera['total_inscritos'] }}</div>
                        <div class="stat-subtitle">Participantes activos</div>
                    </div>
                    <div class="stat-icon">
                        <i class="ri-group-line" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card esperado">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-label">Total Esperado</div>
                        <div class="stat-value">Bs {{ number_format($informacionFinanciera['total_esperado'], 2) }}</div>
                        <div class="stat-subtitle">Deuda total asignada</div>
                    </div>
                    <div class="stat-icon">
                        <i class="ri-money-dollar-circle-line" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card recaudado">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-label">Total Recaudado</div>
                        <div class="stat-value">Bs {{ number_format($informacionFinanciera['total_recaudado'], 2) }}</div>
                        <div class="stat-subtitle">
                            {{ $informacionFinanciera['total_esperado'] > 0 ? round(($informacionFinanciera['total_recaudado'] / $informacionFinanciera['total_esperado']) * 100, 1) : 0 }}% del total
                        </div>
                        <div class="stat-progress">
                            <div class="stat-progress-bar" style="width: {{ $informacionFinanciera['total_esperado'] > 0 ? round(($informacionFinanciera['total_recaudado'] / $informacionFinanciera['total_esperado']) * 100, 1) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="ri-bank-card-line" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card deuda">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-label">Deuda Pendiente</div>
                        <div class="stat-value">Bs {{ number_format($informacionFinanciera['total_deuda'], 2) }}</div>
                        <div class="stat-subtitle">
                            {{ $informacionFinanciera['total_esperado'] > 0 ? round(($informacionFinanciera['total_deuda'] / $informacionFinanciera['total_esperado']) * 100, 1) : 0 }}% del total
                        </div>
                        <div class="stat-progress">
                            <div class="stat-progress-bar" style="width: {{ $informacionFinanciera['total_esperado'] > 0 ? round(($informacionFinanciera['total_deuda'] / $informacionFinanciera['total_esperado']) * 100, 1) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="ri-alarm-warning-line" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Header -->
        <div class="section-header">
            <div>
                <h2 class="section-title">
                    <i class="ri-bank-card-2-line"></i>
                    Planes de Pago Configurados
                </h2>
                <p class="section-subtitle">Gestiona y configura los planes de pago disponibles para esta oferta académica</p>
            </div>
            @if (count($planesDisponibles) > 0)
                <button type="button" class="btn-new-plan" id="add-new-plan-btn" data-bs-toggle="modal" data-bs-target="#modalNuevoPlan">
                    <i class="ri-add-circle-fill"></i> Nuevo Plan
                </button>
            @else
                <button type="button" class="btn btn-secondary" disabled>
                    <i class="ri-add-circle-line me-2"></i> Todos los planes asignados
                </button>
            @endif
        </div>

        <!-- Plans Container -->
        <div id="planes-container">
            @if (count($planesAgrupados) == 0)
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="ri-inbox-line"></i>
                    </div>
                    <h3>No hay planes de pago configurados</h3>
                    <p>Comience agregando un nuevo plan de pago para habilitar las inscripciones en esta oferta académica.</p>
                    @if (count($planesDisponibles) > 0)
                        <button type="button" class="btn-new-plan" data-bs-toggle="modal" data-bs-target="#modalNuevoPlan">
                            <i class="ri-add-circle-fill"></i> Agregar Primer Plan
                        </button>
                    @endif
                </div>
            @else
                @foreach ($planesAgrupados as $planId => $planData)
                    @php
                        $plan = $planData['plan'];
                        $conceptosPlan = $planData['conceptos'];
                        $tieneInscripciones = in_array($plan->id, $planesConInscripciones);
                        $totalPlan = $planData['total_plan'];
                        $esPromocion = $planData['es_promocion'];
                        $fechaInicioPromocion = $planData['fecha_inicio_promocion'];
                        $fechaFinPromocion = $planData['fecha_fin_promocion'];
                        $promocionVigente = $planData['promocion_vigente'];
                    @endphp

                    <div class="plan-card" id="plan-{{ $plan->id }}" data-plan-id="{{ $plan->id }}">
                        <div class="plan-card-header">
                            <div class="plan-card-info">
                                <div class="plan-avatar">
                                    <i class="ri-bank-card-2-line"></i>
                                </div>
                                <div>
                                    <div class="plan-name">{{ $plan->nombre }}</div>
                                    <div class="plan-meta">
                                        <span class="plan-meta-item">
                                            <i class="ri-list-check-2"></i> {{ $conceptosPlan->count() }} concepto(s)
                                        </span>
                                        <span class="plan-meta-item">
                                            <i class="ri-money-dollar-circle-line"></i> Total: Bs. {{ number_format($totalPlan, 2) }}
                                        </span>
                                        @if ($esPromocion)
                                            <span class="plan-meta-item">
                                                <i class="ri-calendar-event-line"></i>
                                                {{ date('d/m/Y', strtotime($fechaInicioPromocion)) }} - {{ date('d/m/Y', strtotime($fechaFinPromocion)) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="plan-badges">
                                    @if ($esPromocion)
                                        <span class="badge-promocion">
                                            <i class="ri-flashlight-fill"></i> PROMOCIÓN
                                            @if ($promocionVigente)
                                                <span class="badge-vigente">VIGENTE</span>
                                            @else
                                                <span class="badge-bloqueado">NO VIGENTE</span>
                                            @endif
                                        </span>
                                    @endif
                                    @if ($tieneInscripciones)
                                        <span class="badge-con-inscritos">
                                            <i class="ri-user-follow-line"></i> Con Inscritos
                                        </span>
                                    @endif
                                </div>
                                @if (!$tieneInscripciones)
                                    <button type="button" class="btn-delete-plan delete-plan-btn" data-plan-id="{{ $plan->id }}">
                                        <i class="ri-delete-bin-line"></i> Eliminar
                                    </button>
                                @else
                                    <span class="badge-bloqueado">
                                        <i class="ri-lock-line"></i> Bloqueado
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="plan-card-body">
                            <table class="plan-table">
                                <thead>
                                    <tr>
                                        <th width="30%">Concepto</th>
                                        <th width="15%" class="text-center">N° Cuotas</th>
                                        @if ($esPromocion)
                                            <th width="15%" class="text-center">Precio Regular</th>
                                            <th width="15%" class="text-center">Descuento</th>
                                        @endif
                                        <th width="{{ $esPromocion ? '15%' : '25%' }}" class="text-center">Precio Final</th>
                                        <th width="10%" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($conceptosPlan as $index => $concepto)
                                        @php
                                            $precioRegular = $concepto->precio_regular ?? 0;
                                            $descuentoBs = $concepto->descuento_bs ?? 0;
                                            $pagoBs = $concepto->pago_bs ?? 0;
                                        @endphp
                                        <tr class="concepto-item" data-index="{{ $index }}">
                                            <td>
                                                <div class="concepto-name">
                                                    <div class="concepto-icon">
                                                        <i class="ri-price-tag-3-line"></i>
                                                    </div>
                                                    {{ $concepto->concepto->nombre ?? 'Sin nombre' }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge-cuotas">{{ $concepto->n_cuotas }} cuota(s)</span>
                                            </td>
                                            @if ($esPromocion)
                                                <td class="text-center">
                                                    <span class="precio-regular">Bs. {{ number_format($precioRegular, 2) }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($descuentoBs > 0)
                                                        <span class="badge-descuento">- Bs. {{ number_format($descuentoBs, 2) }}</span>
                                                    @else
                                                        <span class="text-muted">Sin descuento</span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td class="text-center">
                                                <span class="precio-final">Bs. {{ number_format($pagoBs, 2) }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if (!$tieneInscripciones)
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-concepto-btn" data-index="{{ $index }}" data-plan-id="{{ $plan->id }}">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                @else
                                                    <i class="ri-lock-line text-muted"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="{{ $esPromocion ? '6' : '4' }}">
                                            <div class="plan-table-footer">
                                                <span class="plan-total-label">Total del Plan:</span>
                                                <span class="plan-total-value">Bs. {{ number_format($totalPlan, 2) }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>

                            <!-- Edit Form (Hidden by default) -->
                            <div class="edit-conceptos-form d-none" id="edit-form-{{ $plan->id }}">
                                <form class="plan-form" data-plan-id="{{ $plan->id }}" data-tiene-inscripciones="{{ $tieneInscripciones ? 'true' : 'false' }}" data-es-promocion="{{ $esPromocion ? 'true' : 'false' }}">
                                    @csrf
                                    <input type="hidden" name="oferta_id" value="{{ $oferta->id }}">
                                    <input type="hidden" name="plan_pago_id" value="{{ $plan->id }}">
                                    <input type="hidden" name="es_promocion" value="{{ $esPromocion ? '1' : '0' }}">
                                    @if ($esPromocion)
                                        <input type="hidden" name="fecha_inicio_promocion" value="{{ $fechaInicioPromocion }}">
                                        <input type="hidden" name="fecha_fin_promocion" value="{{ $fechaFinPromocion }}">
                                    @endif
                                    <div class="conceptos-container mb-3">
                                        @foreach ($conceptosPlan as $index => $concepto)
                                            <div class="card mb-3 border">
                                                <div class="card-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-{{ $esPromocion ? '3' : '4' }}">
                                                            <label class="form-label fw-semibold">Concepto *</label>
                                                            <select name="conceptos[{{ $index }}][concepto_id]" class="form-select" {{ $tieneInscripciones ? 'disabled' : 'required' }}>
                                                                <option value="">Seleccione concepto</option>
                                                                @foreach ($conceptos as $c)
                                                                    <option value="{{ $c->id }}" {{ $c->id == $concepto->concepto_id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-{{ $esPromocion ? '2' : '3' }}">
                                                            <label class="form-label fw-semibold">N° Cuotas *</label>
                                                            <input type="number" name="conceptos[{{ $index }}][n_cuotas]" class="form-control" value="{{ $concepto->n_cuotas }}" min="1" {{ $tieneInscripciones ? 'readonly' : 'required' }}>
                                                        </div>
                                                        @if ($esPromocion)
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Precio Regular (Bs.)</label>
                                                                <input type="number" step="0.01" name="conceptos[{{ $index }}][precio_regular]" class="form-control precio-regular-input" value="{{ number_format($concepto->precio_regular ?? 0, 2, '.', '') }}" readonly>
                                                                <small class="text-muted">Precio del plan principal</small>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Descuento (Bs.)</label>
                                                                <input type="number" step="0.01" name="conceptos[{{ $index }}][descuento_bs]" class="form-control descuento-bs-input" value="{{ number_format($concepto->descuento_bs ?? 0, 2, '.', '') }}" min="0" {{ $tieneInscripciones ? 'readonly' : '' }}>
                                                                <small class="text-muted descuento-max-hint" style="font-size: 0.75rem;"></small>
                                                            </div>
                                                        @endif
                                                        <div class="col-md-{{ $esPromocion ? '2' : '3' }}">
                                                            <label class="form-label fw-semibold">Precio Final (Bs.) *</label>
                                                            <input type="number" step="0.01" name="conceptos[{{ $index }}][pago_bs]" class="form-control pago-bs-input" value="{{ number_format($concepto->pago_bs ?? 0, 2, '.', '') }}" min="0" {{ $tieneInscripciones ? 'readonly' : 'required' }} {{ $esPromocion ? 'readonly' : '' }}>
                                                            <small class="text-muted">
                                                                @if ($esPromocion)
                                                                    Calculado automáticamente
                                                                @else
                                                                    Monto total del concepto
                                                                @endif
                                                            </small>
                                                        </div>
                                                        <div class="col-md-1 d-flex align-items-end justify-content-center">
                                                            @if (!$tieneInscripciones)
                                                                <button type="button" class="btn btn-outline-danger btn-sm remove-concepto-form-btn" data-index="{{ $index }}" data-plan-id="{{ $plan->id }}">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if (!$tieneInscripciones)
                                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                            <button type="button" class="btn-edit-plan add-concepto-btn" data-plan-id="{{ $plan->id }}" data-es-promocion="{{ $esPromocion ? 'true' : 'false' }}">
                                                <i class="ri-add-line"></i> Agregar Concepto
                                            </button>
                                            <div>
                                                <button type="button" class="btn btn-outline-secondary cancel-edit-btn me-2" data-plan-id="{{ $plan->id }}">Cancelar</button>
                                                <button type="submit" class="btn btn-success save-plan-btn" data-plan-id="{{ $plan->id }}">
                                                    <i class="ri-save-line me-1"></i> Guardar Cambios
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </form>
                            </div>

                            @if (!$tieneInscripciones)
                                <div class="plan-card-actions">
                                    <button type="button" class="btn-edit-plan edit-plan-btn" data-plan-id="{{ $plan->id }}">
                                        <i class="ri-edit-2-line"></i> Editar Plan
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Modal para nuevo plan -->
    <div class="modal fade" id="modalNuevoPlan" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-xl); overflow: hidden;">
                <div class="modal-header-custom" style="background: linear-gradient(135deg, var(--plan-primary) 0%, #021e35 100%);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="modal-header-icon" style="width: 48px; height: 48px; background: rgba(94, 201, 177, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-add-circle-fill" style="font-size: 1.5rem; color: var(--plan-accent);"></i>
                        </div>
                        <div>
                            <h5 class="modal-title" style="color: white; font-family: 'Outfit', sans-serif; font-weight: 600; margin: 0;">
                                Agregar Nuevo Plan de Pago
                            </h5>
                            <p style="color: rgba(255,255,255,0.7); margin: 4px 0 0 0; font-size: 0.85rem;">
                                Configure los detalles del nuevo plan
                            </p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="formNuevoPlan">
                    @csrf
                    <input type="hidden" name="oferta_id" value="{{ $oferta->id }}">

                    <div class="modal-body-custom">
                        <!-- Sección 1: Selección del plan -->
                        <div class="modal-section-card">
                            <div class="modal-section-header">
                                <i class="ri-bank-card-2-line"></i>
                                Seleccionar Plan de Pago
                            </div>
                            <div class="modal-section-body">
                                <label class="form-label-custom">Plan de Pago *</label>
                                <select name="planes_pago_id" class="form-select form-select-custom" id="selectPlanPago" required>
                                    <option value="">Seleccione un tipo de plan</option>
                                    @foreach ($planesDisponibles as $plan)
                                        <option value="{{ $plan->id }}" data-nombre="{{ $plan->nombre }}"
                                            data-principal="{{ $plan->principal }}">
                                            {{ $plan->nombre }}
                                            @if ($plan->principal)
                                                <span class="badge-vigente ms-2">Plan Principal</span>
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @if (count($planesDisponibles) == 0)
                                    <div class="alert-navy mt-3" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: var(--radius-sm); padding: 12px 16px;">
                                        <i class="ri-information-line me-2" style="color: var(--plan-warning);"></i>
                                        <span style="color: var(--plan-warning);">No hay planes disponibles para agregar.</span>
                                    </div>
                                @else
                                    <div class="form-hint mt-2">
                                        <i class="ri-information-line me-1"></i>
                                        @if (count($planesAgrupados) == 0)
                                            Solo se muestran planes principales habilitados.
                                        @else
                                            Solo se muestran planes habilitados.
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Sección 2: Configuración de Promoción -->
                        <div class="modal-section-card">
                            <div class="modal-section-header">
                                <i class="ri-flashlight-line"></i>
                                Configuración de Promoción
                            </div>
                            <div class="modal-section-body">
                                <div class="promo-toggle-container" style="background: var(--plan-surface); border-radius: var(--radius-md); padding: 16px 20px; margin-bottom: 16px;">
                                    <div class="form-check form-switch d-flex align-items-center gap-3">
                                        <input type="checkbox" class="form-check-input" id="es_promocion" name="es_promocion" value="1" style="width: 48px; height: 24px; cursor: pointer;">
                                        <div>
                                            <label class="form-check-label fw-semibold" for="es_promocion" style="cursor: pointer;">
                                                ¿Este plan es una promoción con descuento?
                                            </label>
                                            <div class="form-hint mt-1">
                                                Marque esta opción si este plan tiene precios promocionales
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="fechas_promocion_container" class="row g-3" style="display: none;">
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Fecha Inicio Promoción *</label>
                                        <input type="date" name="fecha_inicio_promocion" id="fecha_inicio_promocion" class="form-control-custom">
                                        <div class="form-hint">Fecha desde la cual la promoción estará activa</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Fecha Fin Promoción *</label>
                                        <input type="date" name="fecha_fin_promocion" id="fecha_fin_promocion" class="form-control-custom">
                                        <div class="form-hint">Fecha hasta la cual la promoción estará vigente</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 3: Conceptos del Plan -->
                        <div class="modal-section-card">
                            <div class="modal-section-header">
                                <i class="ri-list-check-2"></i>
                                Conceptos del Plan
                                <button type="button" class="btn-add-concepto ms-auto" id="add-concepto-nuevo">
                                    <i class="ri-add-line"></i> Agregar
                                </button>
                            </div>
                            <div class="modal-section-body">
                                <div id="info-plan-principal" class="info-alert" style="display: none;">
                                    <i class="ri-information-line"></i>
                                    Este plan es promocional. Los precios regulares se cargarán automáticamente desde el plan principal.
                                </div>

                                <div id="conceptos-nuevo-plan" class="conceptos-list">
                                </div>

                                <div class="warning-alert mt-3">
                                    <i class="ri-alert-line"></i>
                                    Recuerde que cada plan debe tener al menos un concepto configurado.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer-custom">
                        <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-save-modal" id="btn-guardar-plan">
                            <i class="ri-save-line"></i> Guardar Nuevo Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Plantilla para un concepto (usada en JavaScript) -->
    <script type="text/template" id="template-concepto">
        <div class="concepto-card" data-index="__INDEX__">
            <div class="concepto-card-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="concepto-number" style="width: 28px; height: 28px; background: var(--plan-accent-light); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <span style="color: var(--plan-accent-dark); font-weight: 700; font-size: 0.8rem;">__NUM__</span>
                    </div>
                    <span class="concepto-label">Concepto</span>
                </div>
                <button type="button" class="btn-remove-concepto">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
            <div class="concepto-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label-custom">Concepto *</label>
                        <select name="conceptos[__INDEX__][concepto_id]" class="form-select-custom concepto-select" required>
                            <option value="">Seleccione concepto</option>
                            @foreach ($conceptos as $concepto)
                                <option value="{{ $concepto->id }}">{{ $concepto->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label-custom">N° Cuotas *</label>
                        <input type="number" name="conceptos[__INDEX__][n_cuotas]" class="form-control-custom n-cuotas-input" value="1" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-custom">Monto Total (Bs.) *</label>
                        <input type="number" step="0.01" name="conceptos[__INDEX__][pago_bs]" class="form-control-custom monto-input" value="0" min="0" required>
                        <div class="form-hint">Precio final del concepto</div>
                    </div>
                    <div class="col-md-3">
                        <div class="promo-fields-concepto" style="display: none;">
                            <div class="promo-inner">
                                <div>
                                    <label class="form-label-custom" style="font-size: 0.75rem;">Precio Regular (Bs.)</label>
                                    <input type="number" step="0.01" name="conceptos[__INDEX__][precio_regular]" class="form-control-custom precio-regular-input" value="0" readonly>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label-custom" style="font-size: 0.75rem;">Descuento (Bs.)</label>
                                    <input type="number" step="0.01" name="conceptos[__INDEX__][descuento_bs]" class="form-control-custom descuento-bs-input" value="0" min="0">
                                    <small class="form-hint descuento-max-hint" style="font-size: 0.7rem; color: #6b7280;"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="promo-calculo-resumen row mt-3" style="display: none;">
                    <div class="col-12">
                        <div class="promo-summary">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="ri-calculator-line"></i>
                                <span class="fw-semibold">Resumen del cálculo</span>
                            </div>
                            <div class="promo-summary-row">
                                <span class="promo-summary-item">
                                    <span class="promo-summary-label">Precio regular:</span>
                                    <span class="promo-summary-value precio-regular-display">Bs. 0.00</span>
                                </span>
                                <span class="promo-summary-item">
                                    <span class="promo-summary-label">Descuento:</span>
                                    <span class="promo-summary-value text-danger descuento-display">-Bs. 0.00</span>
                                </span>
                                <span class="promo-summary-item">
                                    <span class="promo-summary-label">Precio final:</span>
                                    <span class="promo-summary-value text-success precio-final-display">Bs. 0.00</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>
@endsection

@push('style')
    <style>
        /* Estilos personalizados para una apariencia más profesional */
        .card {
            border-radius: 0.75rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }

        .border-3 {
            border-width: 3px !important;
        }

        .avatar-sm {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-xs {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fs-11 {
            font-size: 0.75rem !important;
        }

        .fs-12 {
            font-size: 0.8125rem !important;
        }

        .fs-13 {
            font-size: 0.875rem !important;
        }

        .fs-14 {
            font-size: 0.9375rem !important;
        }

        .fs-16 {
            font-size: 1rem !important;
        }

        .fs-18 {
            font-size: 1.125rem !important;
        }

        .table th {
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.05);
        }

        .promo-fields {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 3px solid #ffc107;
        }

        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
        }

        .modal-content {
            border-radius: 1rem;
        }

        .modal-header {
            border-radius: 1rem 1rem 0 0;
        }

        .edit-conceptos-form {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-top: 1rem;
            border: 1px solid #e2e8f0;
        }

        /* Animación para mostrar/ocultar formulario */
        .edit-conceptos-form.show {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Estilos para badges */
        .badge {
            border-radius: 0.375rem;
            font-weight: 500;
        }

        /* Colores personalizados para planes */
        .bg-primary.bg-opacity-10 {
            background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
        }

        /* Estilos para el modal mejorado */
        .modal-header {
            border-radius: calc(1rem - 1px) calc(1rem - 1px) 0 0 !important;
        }

        .modal-content {
            border-radius: 1rem;
            overflow: hidden;
        }

        .modal-footer {
            border-top: 1px solid #e2e8f0;
        }

        .text-teal {
            color: #0f766e;
        }

        .btn-outline-teal {
            border-color: #0f766e;
            color: #0f766e;
        }

        .btn-outline-teal:hover {
            background-color: #0f766e;
            border-color: #0f766e;
            color: white;
        }

        /* Hover effects para cards en modal */
        .modal .card {
            border-radius: 0.75rem;
        }

        .modal .card:hover {
            transform: none;
        }

        /* Animación de entrada del modal */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out, opacity 0.3s ease-out;
        }

        .modal.fade.show .modal-dialog {
            transform: translateY(0);
            opacity: 1;
        }

        .bg-success.bg-opacity-10 {
            background-color: rgba(var(--bs-success-rgb), 0.1) !important;
        }

        .bg-info.bg-opacity-10 {
            background-color: rgba(var(--bs-info-rgb), 0.1) !important;
        }

        .bg-warning.bg-opacity-10 {
            background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
        }

        .bg-danger.bg-opacity-10 {
            background-color: rgba(var(--bs-danger-rgb), 0.1) !important;
        }

        .bg-secondary.bg-opacity-10 {
            background-color: rgba(var(--bs-secondary-rgb), 0.1) !important;
        }

        .bg-dark.bg-opacity-10 {
            background-color: rgba(var(--bs-dark-rgb), 0.1) !important;
        }
    </style>

    <style>
        /* Badges para promociones */
        .badge-promocion {
            background: linear-gradient(135deg, #ffd700 0%, #ffa500 100%);
            color: #000;
            font-weight: 600;
            letter-spacing: 0.5px;
            border: 1px solid #ff9900;
        }

        .badge-vigente {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .badge-no-vigente {
            background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%);
            color: white;
        }

        /* Tooltips mejorados */
        .tooltip-inner {
            max-width: 300px;
            padding: 8px 12px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Estilos para la tabla de conceptos */
        .table th {
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        /* Resaltar descuentos */
        .descuento-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(220, 53, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        /* Para planes promocionales */
        .card.promocional {
            border-left: 5px solid #ffc107;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.1);
        }

        .card.promocional .card-header {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.15) 0%, rgba(255, 193, 7, 0.05) 100%);
        }
    </style>
@endpush

@push('script')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            const CONCEPTOS = @json($conceptos);
            const PLANES_CON_INSCRIPCIONES = @json($planesConInscripciones);
            const OFERTA_ID = {{ $oferta->id }};
            const TOKEN = "{{ csrf_token() }}";

            // URLs para las peticiones AJAX
            const URL_ACTUALIZAR_PLAN = "{{ route('admin.ofertas.actualizar-plan-pago') }}";
            const URL_ELIMINAR_PLAN = "{{ route('admin.ofertas.eliminar-plan-pago') }}";
            const URL_AGREGAR_PLAN = "{{ route('admin.ofertas.agregar-plan-pago') }}";
            const URL_OBTENER_PRECIO_PRINCIPAL = "{{ route('admin.ofertas.obtener-precio-principal') }}";

            // Variables globales para el modal
            let conceptoIndexNuevo = 0;

            // ============================================
            // 1. CÓDIGO PARA EL MODAL DE NUEVO PLAN
            // ============================================

            // Inicializar tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // LIMPIAR COMPLETAMENTE el modal al abrir
            $('#modalNuevoPlan').on('show.bs.modal', function() {
                // Reiniciar el índice
                conceptoIndexNuevo = 0;

                // Limpiar cualquier concepto existente
                $('#conceptos-nuevo-plan').empty();

                // Limpiar formulario
                $('#formNuevoPlan')[0].reset();

                // Ocultar campos de promoción
                $('#fechas_promocion_container').hide();
                $('#info-plan-principal').hide();

                // Quitar validación de fechas (se agregarán después si es promoción)
                $('#fecha_inicio_promocion, #fecha_fin_promocion').prop('required', false);

                // Verificar plan principal
                verificarPlanPrincipal();
            });

            // Verificar si ya existe un plan principal registrado
            function verificarPlanPrincipal() {
                $.ajax({
                    url: "{{ route('admin.ofertas.verificar-plan-principal') }}",
                    type: 'POST',
                    data: {
                        _token: TOKEN,
                        oferta_id: OFERTA_ID
                    },
                    success: function(res) {
                        if (!res.existe) {
                            $('#info-plan-principal').html(
                                '<i class="ri-alert-line me-2"></i>' +
                                '<strong>Advertencia:</strong> No se ha registrado un plan principal para esta oferta. ' +
                                'Es recomendable registrar primero un plan principal para poder crear promociones.'
                            ).removeClass('alert-info').addClass('alert-warning');
                        }
                    }
                });
            }

            // Control para mostrar/ocultar campos de promoción en modal NUEVO
            $('#es_promocion').on('change', function() {
                const esPromocion = $(this).is(':checked');
                const fechasContainer = $('#fechas_promocion_container');
                const infoPrincipal = $('#info-plan-principal');

                if (esPromocion) {
                    fechasContainer.slideDown(300);
                    infoPrincipal.slideDown(300);
                    $('#fecha_inicio_promocion, #fecha_fin_promocion').prop('required', true);

                    // Mostrar campos de promoción en todos los conceptos existentes
                    $('.promo-fields-concepto').slideDown(300);
                    $('.promo-calculo-resumen').slideDown(300);

                    // Hacer que los campos de promoción sean requeridos
                    $('.precio-regular-input, .descuento-bs-input').each(function() {
                        $(this).prop('required', true);
                    });
                } else {
                    fechasContainer.slideUp(300);
                    infoPrincipal.slideUp(300);
                    $('#fecha_inicio_promocion, #fecha_fin_promocion').prop('required', false);

                    // Ocultar campos de promoción
                    $('.promo-fields-concepto').slideUp(300);
                    $('.promo-calculo-resumen').slideUp(300);

                    // Quitar requerido de los campos de promoción
                    $('.precio-regular-input, .descuento-bs-input').each(function() {
                        $(this).prop('required', false);
                    });
                }
            });

            // Agregar un nuevo concepto en modal NUEVO
            $(document).on('click', '#add-concepto-nuevo', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const template = $('#template-concepto').html();
                const html = template
                    .replace(/__INDEX__/g, conceptoIndexNuevo)
                    .replace(/__NUM__/g, conceptoIndexNuevo + 1);

                $('#conceptos-nuevo-plan').append(html);

                // Si es promoción, mostrar campos de promoción
                const esPromocion = $('#es_promocion').is(':checked');
                const conceptoItem = $('#conceptos-nuevo-plan .concepto-card').last();

                if (esPromocion) {
                    conceptoItem.find('.promo-fields-concepto').slideDown(300);
                    conceptoItem.find('.promo-calculo-resumen').slideDown(300);

                    // Hacer que los campos de promoción sean requeridos
                    conceptoItem.find('.precio-regular-input, .descuento-bs-input').prop('required', true);
                } else {
                    // Si no es promoción, quitar el atributo required
                    conceptoItem.find('.precio-regular-input, .descuento-bs-input').prop('required', false);
                }

                conceptoIndexNuevo++;
            });

            // Eliminar un concepto en modal NUEVO
            $(document).on('click', '.btn-remove-concepto', function() {
                const card = $(this).closest('.concepto-card');
                card.fadeOut(300, function() {
                    $(this).remove();
                    // Ajustar índices de los conceptos restantes
                    reindexarConceptos();
                });
            });

            // Función para reindexar conceptos después de eliminar
            function reindexarConceptos() {
                conceptoIndexNuevo = 0;
                $('#conceptos-nuevo-plan .concepto-card').each(function(index) {
                    const newIndex = index;
                    const $this = $(this);

                    // Actualizar atributos data-index
                    $this.attr('data-index', newIndex);

                    // Actualizar nombres de campos del formulario
                    $this.find('[name*="conceptos["]').each(function() {
                        const name = $(this).attr('name');
                        const newName = name.replace(/conceptos\[\d+\]/, `conceptos[${newIndex}]`);
                        $(this).attr('name', newName);
                    });

                    // Actualizar etiquetas de número
                    $this.find('.concepto-number span').text(newIndex + 1);

                    conceptoIndexNuevo++;
                });
            }

            // Cuando se selecciona un concepto, cargar precio del plan principal (solo para promociones)
            $(document).on('change', '.concepto-select', function() {
                const conceptoItem = $(this).closest('.concepto-card');
                const conceptoId = $(this).val();
                const esPromocion = $('#es_promocion').is(':checked');

                if (esPromocion && conceptoId) {
                    // Mostrar loader
                    conceptoItem.find('.precio-regular-input').val('Cargando...');

                    $.ajax({
                        url: URL_OBTENER_PRECIO_PRINCIPAL,
                        type: 'POST',
                        data: {
                            _token: TOKEN,
                            oferta_id: OFERTA_ID,
                            concepto_id: conceptoId
                        },
                        success: function(res) {
                            if (res.success && res.precio_regular) {
                                const precioRegular = parseFloat(res.precio_regular);
                                conceptoItem.find('.precio-regular-input').val(precioRegular
                                    .toFixed(2));
                                conceptoItem.find('.precio-regular-display').text('Bs. ' +
                                    precioRegular.toFixed(2));

                                // Establecer el precio regular como base
                                conceptoItem.find('.monto-input').val(precioRegular.toFixed(2));

                                // Calcular precio final
                                calcularPrecioFinal(conceptoItem);
                            } else {
                                conceptoItem.find('.precio-regular-input').val('0.00');
                                conceptoItem.find('.precio-regular-display').text('Bs. 0.00');
                                conceptoItem.find('.monto-input').val('0.00');
                                calcularPrecioFinal(conceptoItem);
                            }
                        },
                        error: function() {
                            conceptoItem.find('.precio-regular-input').val('0.00');
                            conceptoItem.find('.precio-regular-display').text('Bs. 0.00');
                            conceptoItem.find('.monto-input').val('0.00');
                            calcularPrecioFinal(conceptoItem);
                        }
                    });
                }
            });

            // Calcular precio final cuando cambia el descuento
            $(document).on('input', '.descuento-bs-input', function() {
                const conceptoItem = $(this).closest('.concepto-card');
                calcularPrecioFinal(conceptoItem);
            });

            // Calcular precio final cuando cambia el precio regular
            $(document).on('input', '.precio-regular-input', function() {
                const conceptoItem = $(this).closest('.concepto-card');
                calcularPrecioFinal(conceptoItem);
            });

            // Función para calcular precio final
            function calcularPrecioFinal(conceptoItem) {
                const precioRegular = parseFloat(conceptoItem.find('.precio-regular-input').val()) || 0;
                const descuentoInput = conceptoItem.find('.descuento-bs-input');
                let descuentoBs = parseFloat(descuentoInput.val()) || 0;
                
                // Limitar el descuento al precio regular máximo
                if (descuentoBs > precioRegular) {
                    descuentoBs = precioRegular;
                    descuentoInput.val(descuentoBs.toFixed(2));
                }
                
                // Establecer el máximo del campo descuento y mostrar hint
                descuentoInput.attr('max', precioRegular.toFixed(2));
                if (precioRegular > 0) {
                    conceptoItem.find('.descuento-max-hint').text(`Máximo: Bs. ${precioRegular.toFixed(2)}`);
                }
                
                const precioFinal = Math.round(Math.max(0, precioRegular - descuentoBs) * 100) / 100;

                // Actualizar displays
                conceptoItem.find('.precio-regular-display').text('Bs. ' + precioRegular.toFixed(2));
                conceptoItem.find('.descuento-display').text('-Bs. ' + descuentoBs.toFixed(2));
                conceptoItem.find('.precio-final-display').text('Bs. ' + precioFinal.toFixed(2));

                // Actualizar campo de precio final
                conceptoItem.find('.monto-input').val(precioFinal.toFixed(2));
            }

            // Validar fechas de promoción
            function validarFechasPromocion() {
                if (!$('#es_promocion').is(':checked')) return true;

                const inicio = $('#fecha_inicio_promocion').val();
                const fin = $('#fecha_fin_promocion').val();

                if (!inicio || !fin) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fechas requeridas',
                        text: 'Para una promoción debe especificar las fechas de inicio y fin',
                        customClass: {
                            confirmButton: 'btn btn-warning'
                        }
                    });
                    return false;
                }

                if (new Date(fin) < new Date(inicio)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fechas inválidas',
                        text: 'La fecha de fin debe ser posterior a la fecha de inicio',
                        customClass: {
                            confirmButton: 'btn btn-warning'
                        }
                    });
                    return false;
                }

                return true;
            }

            // Validar formulario antes de enviar en modal NUEVO
            $('#formNuevoPlan').on('submit', function(e) {
                e.preventDefault();

                const submitBtn = $('#btn-guardar-plan');
                const originalText = submitBtn.html();

                // Validar fechas si es promoción
                if (!validarFechasPromocion()) {
                    return;
                }

                // Validar que haya al menos un concepto
                if ($('#conceptos-nuevo-plan .concepto-card').length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Conceptos requeridos',
                        text: 'Debe agregar al menos un concepto al plan',
                        customClass: {
                            confirmButton: 'btn btn-warning'
                        }
                    });
                    return;
                }

                const esPromocion = $('#es_promocion').is(':checked');
                let conceptosValidos = true;
                let mensajesError = [];

                // Validar cada concepto
                $('#conceptos-nuevo-plan .concepto-card').each(function(index) {
                    const conceptoItem = $(this);
                    const conceptoId = conceptoItem.find('.concepto-select').val();
                    const nCuotas = conceptoItem.find('.n-cuotas-input').val();
                    let monto = conceptoItem.find('.monto-input').val();

                    // Quitar la clase de error primero
                    conceptoItem.removeClass('border-danger');

                    // Validaciones básicas (siempre requeridas)
                    if (!conceptoId || !nCuotas || parseInt(nCuotas) < 1) {
                        conceptosValidos = false;
                        conceptoItem.addClass('border-danger');
                        mensajesError.push(
                            `Concepto ${index + 1}: Datos básicos incompletos (Concepto y N° Cuotas son requeridos)`
                            );
                    }

                    // Validar monto según si es promoción o no
                    if (esPromocion) {
                        const precioRegular = parseFloat(conceptoItem.find('.precio-regular-input')
                            .val()) || 0;
                        const descuentoBs = parseFloat(conceptoItem.find('.descuento-bs-input')
                        .val()) || 0;

                        // Para promociones, validar precio regular y descuento
                        if (precioRegular <= 0) {
                            conceptosValidos = false;
                            conceptoItem.addClass('border-danger');
                            mensajesError.push(
                                `Concepto ${index + 1}: El precio regular debe ser mayor a 0`);
                        }

                        if (descuentoBs < 0) {
                            conceptosValidos = false;
                            conceptoItem.addClass('border-danger');
                            mensajesError.push(
                                `Concepto ${index + 1}: El descuento no puede ser negativo`);
                        }

                        if (descuentoBs > precioRegular) {
                            conceptosValidos = false;
                            conceptoItem.addClass('border-danger');
                            mensajesError.push(
                                `Concepto ${index + 1}: El descuento no puede exceder el precio regular`);
                        }

                        // Calcular el monto automáticamente para promociones
                        monto = Math.round((precioRegular - descuentoBs) * 100) / 100;
                        conceptoItem.find('.monto-input').val(monto.toFixed(2));

                        // Validar que el precio final sea positivo
                        if (parseFloat(monto) < 0) {
                            conceptosValidos = false;
                            conceptoItem.addClass('border-danger');
                            mensajesError.push(
                                `Concepto ${index + 1}: El precio final no puede ser negativo (Precio Regular - Descuento)`
                                );
                        }
                    } else {
                        // Para planes no promocionales, validar el monto directamente
                        if (!monto || parseFloat(monto) <= 0) {
                            conceptosValidos = false;
                            conceptoItem.addClass('border-danger');
                            mensajesError.push(
                                `Concepto ${index + 1}: El monto total debe ser mayor a 0`);
                        }
                    }
                });

                if (!conceptosValidos) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Datos incompletos o inválidos',
                        html: mensajesError.join('<br>'),
                        customClass: {
                            confirmButton: 'btn btn-warning'
                        }
                    });
                    return;
                }

                // Si todo está bien, proceder a enviar el formulario
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-2"></i> Guardando...');

                const formData = new FormData(this);

                // Añadir campos que puedan faltar
                $('#conceptos-nuevo-plan .concepto-card').each(function(index) {
                    const card = $(this);
                    const esPromocion = $('#es_promocion').is(':checked');

                    // Si es promoción, asegurar que los campos estén presentes
                    if (esPromocion) {
                        const precioRegular = parseFloat(card.find('.precio-regular-input').val()) || 0;
                        const descuentoBs = parseFloat(card.find('.descuento-bs-input').val()) || 0;
                        const montoFinal = Math.round((precioRegular - descuentoBs) * 100) / 100;

                        formData.set(`conceptos[${index}][precio_regular]`, precioRegular.toFixed(2));
                        formData.set(`conceptos[${index}][descuento_bs]`, descuentoBs.toFixed(2));
                        formData.set(`conceptos[${index}][pago_bs]`, montoFinal.toFixed(2));
                    } else {
                        // Si no es promoción, enviar valores por defecto para campos de promoción
                        const montoOriginal = card.find('.monto-input').val();
                        const montoFinal = Math.round(parseFloat(montoOriginal) * 100) / 100;
                        
                        formData.set(`conceptos[${index}][precio_regular]`, '0.00');
                        formData.set(`conceptos[${index}][descuento_bs]`, '0.00');
                        formData.set(`conceptos[${index}][pago_bs]`, montoFinal.toFixed(2));
                    }
                });

                // Enviar formulario
                $.ajax({
                    url: URL_AGREGAR_PLAN,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: res.msg,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                $('#modalNuevoPlan').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: res.msg,
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al guardar el plan.';
                        if (xhr.responseJSON && xhr.responseJSON.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg,
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Validar que fecha fin sea mayor a fecha inicio
            $('#fecha_inicio_promocion').on('change', function() {
                const inicio = $(this).val();
                const fin = $('#fecha_fin_promocion').val();

                if (fin && new Date(fin) < new Date(inicio)) {
                    $('#fecha_fin_promocion').val('');
                }

                $('#fecha_fin_promocion').attr('min', inicio);
            });

            // Limpiar completamente al cerrar el modal NUEVO
            $('#modalNuevoPlan').on('hidden.bs.modal', function() {
                $('#formNuevoPlan')[0].reset();
                $('#conceptos-nuevo-plan').empty();
                $('#fechas_promocion_container').hide();
                $('#info-plan-principal').hide();
                conceptoIndexNuevo = 0;

                // Quitar todas las clases de error
                $('.concepto-card').removeClass('border-danger');
            });

            // ============================================
            // 2. CÓDIGO PARA LA EDICIÓN DE PLANES EXISTENTES
            // ============================================

            // Mostrar/ocultar formulario de edición
            $(document).on('click', '.edit-plan-btn', function() {
                const planId = $(this).data('plan-id');
                const formContainer = $(`#edit-form-${planId}`);

                formContainer.removeClass('d-none');
                formContainer.addClass('show');
                $(this).hide();

                // Forzar la actualización de los campos de precio en promociones
                setTimeout(() => {
                    formContainer.find('.card').each(function() {
                        const esPromocion = formContainer.find('input[name="es_promocion"]').val() === '1';
                        
                        if (esPromocion) {
                            const precioRegular = parseFloat($(this).find('.precio-regular-input').val()) || 0;
                            const descuentoInput = $(this).find('.descuento-bs-input');
                            let descuentoBs = parseFloat(descuentoInput.val()) || 0;
                            
                            // Limitar el descuento al precio regular
                            if (descuentoBs > precioRegular) {
                                descuentoBs = precioRegular;
                                descuentoInput.val(descuentoBs.toFixed(2));
                            }
                            
                            // Establecer el máximo del descuento y mostrar hint
                            descuentoInput.attr('max', precioRegular.toFixed(2));
                            if (precioRegular > 0) {
                                $(this).find('.descuento-max-hint').text(`Máximo: Bs. ${precioRegular.toFixed(2)}`);
                            }
                            
                            const precioFinal = Math.round(Math.max(0, precioRegular - descuentoBs) * 100) / 100;
                            $(this).find('.pago-bs-input').val(precioFinal.toFixed(2));
                        }
                        // Para planes no promocionales, el valor ya está configurado desde Blade
                    });
                }, 100);

                // Hacer scroll suave al formulario
                $('html, body').animate({
                    scrollTop: formContainer.offset().top - 100
                }, 300);
            });

            $(document).on('click', '.cancel-edit-btn', function() {
                const planId = $(this).data('plan-id');
                const formContainer = $(`#edit-form-${planId}`);

                formContainer.removeClass('show');
                setTimeout(() => {
                    formContainer.addClass('d-none');
                    $(`.edit-plan-btn[data-plan-id="${planId}"]`).show();
                }, 300);
            });

            // Agregar concepto a un plan existente
            $(document).on('click', '.add-concepto-btn', function(e) {
                e.preventDefault();
                const planId = $(this).data('plan-id');
                const esPromocion = $(this).data('es-promocion') === 'true';
                const container = $(this).closest('.plan-form').find('.conceptos-container');
                const index = container.find('.card').length;

                const html = `
                <div class="card mb-3 border">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-${esPromocion ? '3' : '4'}">
                                <label class="form-label fw-semibold">Concepto *</label>
                                <select name="conceptos[${index}][concepto_id]" class="form-select" required>
                                    <option value="">Seleccione concepto</option>
                                    ${CONCEPTOS.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('')}
                                </select>
                            </div>
                            
                            <div class="col-md-${esPromocion ? '2' : '3'}">
                                <label class="form-label fw-semibold">N° Cuotas *</label>
                                <input type="number" name="conceptos[${index}][n_cuotas]" 
                                       class="form-control" value="1" min="1" required>
                            </div>
                            
                            ${esPromocion ? `
                                    <!-- Precio Regular -->
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Precio Regular (Bs.)</label>
                                        <input type="number" step="0.01" 
                                            name="conceptos[${index}][precio_regular]" 
                                            class="form-control precio-regular-input" 
                                            value="0" readonly>
                                        <small class="text-muted">Precio del plan principal</small>
                                    </div>
                                    
                                    <!-- Descuento en Bs. -->
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Descuento (Bs.)</label>
                                        <input type="number" step="0.01" 
                                            name="conceptos[${index}][descuento_bs]" 
                                            class="form-control descuento-bs-input" 
                                            value="0" min="0">
                                        <small class="text-muted descuento-max-hint" style="font-size: 0.75rem;"></small>
                                    </div>
                                ` : ''}
                            
                            <!-- Precio Final (Bs.) -->
                            <div class="col-md-${esPromocion ? '2' : '3'}">
                                <label class="form-label fw-semibold">Precio Final (Bs.) *</label>
                                <input type="number" step="0.01" 
                                    name="conceptos[${index}][pago_bs]" 
                                    class="form-control pago-bs-input" 
                                    value="0" min="0" required
                                    ${esPromocion ? 'readonly' : ''}>
                                <small class="text-muted">
                                    ${esPromocion ? 'Calculado automáticamente (Precio Regular - Descuento)' : 'Monto total del concepto'}
                                </small>
                            </div>
                            
                            <div class="col-md-1 text-center">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-concepto-form-btn"
                                        data-index="${index}" data-plan-id="${planId}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                container.append(html);
            });

            // Eliminar concepto en formulario de edición
            $(document).on('click', '.remove-concepto-form-btn', function() {
                const card = $(this).closest('.card');
                card.fadeOut(300, function() {
                    $(this).remove();
                });
            });

            // Eliminar concepto desde la tabla
            $(document).on('click', '.remove-concepto-btn', function() {
                const conceptoItem = $(this).closest('.concepto-item');
                const planId = $(this).data('plan-id');

                Swal.fire({
                    title: '¿Eliminar concepto?',
                    text: "Esta acción eliminará el concepto del plan",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        conceptoItem.fadeOut(300, function() {
                            $(this).remove();
                            Swal.fire({
                                icon: 'success',
                                title: 'Concepto eliminado',
                                text: 'El concepto ha sido eliminado del plan',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        });
                    }
                });
            });

            // Validar formulario antes de enviar en edición
            function validarFormulario(form) {
                let isValid = true;
                let errorMessages = [];

                form.find('.conceptos-container .card').each(function(index) {
                    const card = $(this);
                    const conceptoSelect = card.find('select[name*="concepto_id"]');
                    const nCuotas = card.find('input[name*="n_cuotas"]');
                    const monto = card.find('input[name*="pago_bs"]');

                    if (!conceptoSelect.val()) {
                        isValid = false;
                        conceptoSelect.addClass('is-invalid');
                        errorMessages.push(`Concepto ${index + 1}: Debe seleccionar un concepto`);
                    } else {
                        conceptoSelect.removeClass('is-invalid');
                    }

                    if (!nCuotas.val() || parseInt(nCuotas.val()) < 1) {
                        isValid = false;
                        nCuotas.addClass('is-invalid');
                        errorMessages.push(`Concepto ${index + 1}: Número de cuotas inválido`);
                    } else {
                        nCuotas.removeClass('is-invalid');
                    }

                    if (!monto.val() || parseFloat(monto.val()) <= 0) {
                        isValid = false;
                        monto.addClass('is-invalid');
                        errorMessages.push(`Concepto ${index + 1}: Monto inválido`);
                    } else {
                        monto.removeClass('is-invalid');
                    }
                });

                return {
                    isValid,
                    errorMessages
                };
            }

            // Guardar cambios de un plan específico
            $(document).on('submit', '.plan-form', function(e) {
                e.preventDefault();

                const form = $(this);
                const planId = form.data('plan-id');
                const tieneInscripciones = form.data('tiene-inscripciones') === 'true';
                const esPromocion = form.data('es-promocion') === 'true';
                const submitBtn = form.find('.save-plan-btn');
                const originalText = submitBtn.html();

                if (tieneInscripciones) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Plan con inscripciones',
                        text: 'Este plan tiene inscripciones registradas y no puede ser modificado.',
                        customClass: {
                            confirmButton: 'btn btn-warning'
                        },
                        buttonsStyling: false
                    });
                    return;
                }

                // Validación especial para promociones en edición
                if (esPromocion) {
                    let promocionValida = true;
                    let mensajesPromo = [];

                    form.find('.conceptos-container .card').each(function(index) {
                        const card = $(this);
                        const precioRegular = parseFloat(card.find('.precio-regular-input')
                        .val()) || 0;
                        const descuentoBs = parseFloat(card.find('.descuento-bs-input').val()) || 0;
                        const montoFinal = parseFloat(card.find('.pago-bs-input').val()) || 0;

                        if (precioRegular <= 0) {
                            promocionValida = false;
                            card.find('.precio-regular-input').addClass('is-invalid');
                            mensajesPromo.push(
                                `Concepto ${index + 1}: Precio regular debe ser mayor a 0`);
                        } else {
                            card.find('.precio-regular-input').removeClass('is-invalid');
                        }

                        if (descuentoBs < 0) {
                            promocionValida = false;
                            card.find('.descuento-bs-input').addClass('is-invalid');
                            mensajesPromo.push(
                                `Concepto ${index + 1}: Descuento no puede ser negativo`);
                        } else {
                            card.find('.descuento-bs-input').removeClass('is-invalid');
                        }

                        if (descuentoBs > precioRegular) {
                            promocionValida = false;
                            card.find('.descuento-bs-input').addClass('is-invalid');
                            mensajesPromo.push(
                                `Concepto ${index + 1}: El descuento no puede exceder el precio regular`);
                        }

                        if (montoFinal < 0) {
                            promocionValida = false;
                            card.find('.pago-bs-input').addClass('is-invalid');
                            mensajesPromo.push(
                                `Concepto ${index + 1}: Precio final debe ser mayor a 0`);
                        } else {
                            card.find('.pago-bs-input').removeClass('is-invalid');
                        }
                    });

                    if (!promocionValida) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Errores en promoción',
                            html: mensajesPromo.join('<br>'),
                            customClass: {
                                confirmButton: 'btn btn-warning'
                            },
                            buttonsStyling: false
                        });
                        return;
                    }
                }

                const validacion = validarFormulario(form);
                if (!validacion.isValid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Errores de validación',
                        html: validacion.errorMessages.join('<br>'),
                        customClass: {
                            confirmButton: 'btn btn-warning'
                        },
                        buttonsStyling: false
                    });
                    return;
                }

                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-2"></i> Guardando...');

                const formData = new FormData(form[0]);

                // Para promociones en edición, asegurar que los cálculos estén correctos
                if (esPromocion) {
                    form.find('.conceptos-container .card').each(function(index) {
                        const card = $(this);
                        const precioRegular = parseFloat(card.find('.precio-regular-input')
                        .val()) || 0;
                        const descuentoBs = parseFloat(card.find('.descuento-bs-input').val()) || 0;
                        const montoFinal = Math.round(Math.max(0, precioRegular - descuentoBs) * 100) / 100;

                        // Actualizar el valor en el formData
                        formData.set(`conceptos[${index}][pago_bs]`, montoFinal.toFixed(2));
                    });
                }

                $.ajax({
                    url: URL_ACTUALIZAR_PLAN,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: res.msg,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: res.msg,
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                },
                                buttonsStyling: false
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al guardar los cambios.';
                        if (xhr.responseJSON && xhr.responseJSON.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg,
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        });
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Eliminar plan completo
            $(document).on('click', '.delete-plan-btn', function() {
                const planId = $(this).data('plan-id');
                const planCard = $(`#plan-${planId}`);
                const planName = planCard.find('.card-header h5').text().trim();

                Swal.fire({
                    title: '¿Eliminar plan completo?',
                    html: `Está a punto de eliminar el plan: <strong>${planName}</strong><br><br>
                           <div class="alert alert-danger border-0 bg-danger bg-opacity-10">
                               <i class="ri-alert-line me-2"></i>
                               Esta acción eliminará todos los conceptos asociados y no se puede deshacer.
                           </div>`,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: URL_ELIMINAR_PLAN,
                            type: 'POST',
                            data: {
                                _token: TOKEN,
                                oferta_id: OFERTA_ID,
                                plan_pago_id: planId
                            },
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Éxito!',
                                        text: res.msg,
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: res.msg,
                                        customClass: {
                                            confirmButton: 'btn btn-danger'
                                        },
                                        buttonsStyling: false
                                    });
                                }
                            },
                            error: function(xhr) {
                                let errorMsg = 'Error al eliminar el plan.';
                                if (xhr.responseJSON && xhr.responseJSON.msg) {
                                    errorMsg = xhr.responseJSON.msg;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMsg,
                                    customClass: {
                                        confirmButton: 'btn btn-danger'
                                    },
                                    buttonsStyling: false
                                });
                            }
                        });
                    }
                });
            });

            // Calcular precio final en formulario de edición
            $(document).on('input', '.precio-regular-input, .descuento-bs-input', function() {
                const row = $(this).closest('.row');
                const precioRegular = parseFloat(row.find('.precio-regular-input').val()) || 0;
                const descuentoInput = row.find('.descuento-bs-input');
                let descuentoBs = parseFloat(descuentoInput.val()) || 0;
                
                // Limitar el descuento al precio regular máximo
                if (descuentoBs > precioRegular) {
                    descuentoBs = precioRegular;
                    descuentoInput.val(descuentoBs.toFixed(2));
                }
                
                // Establecer el máximo del campo descuento y mostrar hint
                descuentoInput.attr('max', precioRegular.toFixed(2));
                if (precioRegular > 0) {
                    row.find('.descuento-max-hint').text(`Máximo: Bs. ${precioRegular.toFixed(2)}`);
                }
                
                const precioFinal = Math.round(Math.max(0, precioRegular - descuentoBs) * 100) / 100;
                row.find('.pago-bs-input').val(precioFinal.toFixed(2));
            });

            // Inicializar tooltips para badges de promoción
            $(document).on('mouseenter', '.badge[data-bs-toggle="tooltip"]', function() {
                const tooltip = new bootstrap.Tooltip(this);
                tooltip.show();
            });

            // Actualizar la apariencia de las tarjetas según si son promocionales
            $(document).ready(function() {
                $('.card').each(function() {
                    const hasPromoBadge = $(this).find('.badge:contains("PROMOCIÓN")').length > 0;
                    if (hasPromoBadge) {
                        $(this).addClass('promocional');
                    }
                });
            });

            // Estilo para spinner de carga
            const style = document.createElement('style');
            style.textContent = `
                .spin {
                    animation: spin 1s linear infinite;
                }
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
                .concepto-item.border-danger,
                .concepto-card.border-danger {
                    border-color: #dc3545 !important;
                    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
                }
                .is-invalid {
                    border-color: #dc3545 !important;
                    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
                }
                
                /* Estilos para campos de edición de promoción */
                .edit-conceptos-form .precio-regular-input,
                .edit-conceptos-form .descuento-bs-input,
                .edit-conceptos-form .pago-bs-input {
                    background-color: #fff;
                    color: #1e293b;
                    font-weight: 500;
                }
                
                .edit-conceptos-form .precio-regular-input[readonly] {
                    background-color: #fef3c7 !important;
                    border-color: #fcd34d !important;
                    color: #92400e;
                    font-style: italic;
                }
                
                .edit-conceptos-form .pago-bs-input[readonly] {
                    background-color: #d1fae5 !important;
                    border-color: #6ee7b7 !important;
                    color: #065f46;
                }
            `;
            document.head.appendChild(style);
        });
    </script>
@endpush
