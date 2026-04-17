@extends('admin.dashboard')

@section('admin')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

        :root {
            --cont-primary: #0d5f59;
            --cont-primary-light: #f0f5fa;
            --cont-primary-dark: #021e35;
            --cont-accent: #5ec9b1;
            --cont-accent-light: #e8faf5;
            --cont-accent-dark: #3ba893;
            --cont-surface: #f8fafc;
            --cont-surface-2: #ffffff;
            --cont-border: #e2e8f0;
            --cont-text: #1e293b;
            --cont-text-muted: #64748b;
            --cont-danger: #ef4444;
            --cont-danger-light: #fef2f2;
            --cont-warning: #f59e0b;
            --cont-warning-light: #fffbeb;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --shadow-sm: 0 1px 2px rgba(3, 42, 74, 0.04), 0 1px 3px rgba(3, 42, 74, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(3, 42, 74, 0.06), 0 2px 4px -2px rgba(3, 42, 74, 0.04);
            --shadow-lg: 0 10px 15px -3px rgba(3, 42, 74, 0.08), 0 4px 6px -4px rgba(3, 42, 74, 0.04);
            --shadow-xl: 0 20px 25px -5px rgba(3, 42, 74, 0.1), 0 8px 10px -6px rgba(3, 42, 74, 0.06);
        }

        [data-bs-theme="dark"] {
            --cont-surface: #1e1e2d;
            --cont-surface-2: #212229;
            --cont-border: #2d2d3a;
            --cont-text: #e9ecef;
            --cont-text-muted: #9ca3af;
            --cont-primary-light: rgba(94, 201, 177, 0.12);
        }

        .deudas-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--cont-text);
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .deudas-header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 28px;
            padding: 32px 36px;
            background: linear-gradient(135deg, var(--cont-primary) 0%, var(--cont-primary-dark) 100%);
            border-radius: var(--radius-xl);
            color: white;
            overflow: hidden;
        }

        .deudas-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(94, 201, 177, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .deudas-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(94, 201, 177, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .deudas-header-content {
            position: relative;
            z-index: 1;
        }


        .deudas-header h1 {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
        }

        .deudas-header h1 i {
            font-size: 1.5rem;
            color: var(--cont-accent);
        }

        .deudas-header p {
            margin: 8px 0 0;
            opacity: 0.85;
            font-size: 0.95rem;
            font-weight: 400;
        }

        .deudas-header-meta {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            padding: 12px 20px;
            border-radius: var(--radius-md);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .deudas-header-meta .date {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .deudas-header-meta i {
            color: var(--cont-accent);
            margin-right: 6px;
        }

        .nav-tabs-custom {
            margin-bottom: 24px;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .nav-tabs-custom .nav-tabs {
            display: flex;
            gap: 6px;
            border: none;
            min-width: max-content;
        }

        .nav-tabs-custom .nav-item {
            margin-bottom: 0;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            border-radius: var(--radius-md);
            color: var(--cont-text-muted);
            font-weight: 500;
            font-size: 0.9rem;
            padding: 14px 24px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--cont-surface);
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .nav-tabs-custom .nav-link:hover {
            color: var(--cont-accent-dark);
            background: var(--cont-accent-light);
            transform: translateY(-2px);
        }

        .nav-tabs-custom .nav-link.active {
            color: white;
            background: linear-gradient(135deg, var(--cont-accent) 0%, var(--cont-accent-dark) 100%);
            box-shadow: 0 4px 12px rgba(94, 201, 177, 0.35);
            transform: translateY(-2px);
        }

        .tab-badge {
            background: rgba(239, 68, 68, 0.15);
            color: var(--cont-danger);
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .nav-tabs-custom .nav-link.active .tab-badge {
            background: rgba(255, 255, 255, 0.25);
            color: white;
        }

        .tab-content-wrapper {
            background: var(--cont-surface-2);
            border-radius: var(--radius-xl);
            padding: 24px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--cont-border);
        }

        .tab-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--cont-border);
        }

        .tab-section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--cont-text);
        }

        .tab-section-title i {
            color: var(--cont-accent);
            font-size: 1.35rem;
        }

        .tab-section-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .total-badge {
            background: linear-gradient(135deg, var(--cont-primary) 0%, var(--cont-primary-dark) 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-whatsapp-all {
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
        }

        .btn-whatsapp-all:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 211, 102, 0.4);
            color: white;
        }

        .btn-whatsapp-all i {
            font-size: 1.1rem;
        }

        .estudiante-card {
            background: var(--cont-surface-2);
            border-radius: var(--radius-lg);
            margin-bottom: 16px;
            padding: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--cont-border);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.4s ease-out;
            animation-fill-mode: both;
        }

        .estudiante-card:nth-child(1) {
            animation-delay: 0.05s;
        }

        .estudiante-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .estudiante-card:nth-child(3) {
            animation-delay: 0.15s;
        }

        .estudiante-card:nth-child(4) {
            animation-delay: 0.2s;
        }

        .estudiante-card:nth-child(5) {
            animation-delay: 0.25s;
        }

        .estudiante-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--cont-danger) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .estudiante-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-3px);
            border-color: var(--cont-accent-light);
        }

        .estudiante-card:hover::before {
            opacity: 1;
        }

        .estudiante-main {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .estudiante-avatar {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, var(--cont-primary) 0%, var(--cont-accent-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .estudiante-info-group {
            flex: 1;
            min-width: 200px;
        }

        .estudiante-nombre {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--cont-text);
            margin-bottom: 4px;
        }

        .estudiante-celular {
            color: var(--cont-text-muted);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .estudiante-celular i {
            color: var(--cont-accent);
        }

        .estudiante-deuda-amount {
            text-align: right;
            margin-top: 8px;
        }

        .deuda-amount-label {
            font-size: 0.75rem;
            color: var(--cont-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .deuda-amount-value {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--cont-danger);
        }

        .estudiante-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 10px 16px;
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .btn-whatsapp-single {
            background: #25D366;
            color: white;
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25);
        }

        .btn-whatsapp-single:hover {
            background: #128C7E;
            color: white;
            box-shadow: 0 6px 16px rgba(37, 211, 102, 0.35);
        }

        .btn-comprobante {
            background: var(--cont-warning-light);
            color: #b45309;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .btn-comprobante:hover {
            background: #fef3c7;
            color: #92400e;
            transform: translateY(-2px);
        }

        .btn-ver-cuotas {
            background: linear-gradient(135deg, var(--cont-accent) 0%, var(--cont-accent-dark) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(94, 201, 177, 0.3);
        }

        .btn-ver-cuotas:hover {
            color: white;
            box-shadow: 0 6px 16px rgba(94, 201, 177, 0.4);
        }

        .estudiante-cuotas-resumen {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--cont-border);
        }

        .cuota-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .cuota-badge.retrasada {
            background: rgba(239, 68, 68, 0.1);
            color: var(--cont-danger);
            border: 1px solid rgba(239, 68, 68, 0.15);
        }

        .cuota-badge.retrasada i {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .cuota-badge.total {
            background: var(--cont-primary-light);
            color: var(--cont-primary);
        }

        .empty-state {
            text-align: center;
            padding: 80px 40px;
            background: var(--cont-surface-2);
            border-radius: var(--radius-xl);
            border: 1px solid var(--cont-border);
        }

        .empty-state-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--cont-accent-light) 0%, rgba(94, 201, 177, 0.05) 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        .empty-state-icon i {
            font-size: 3rem;
            color: var(--cont-accent);
        }

        .empty-state h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--cont-text);
            margin-bottom: 8px;
        }

        .empty-state p {
            color: var(--cont-text-muted);
            font-size: 1rem;
        }

        .modal-header-custom {
            background: linear-gradient(135deg, var(--cont-primary) 0%, var(--cont-primary-dark) 100%);
            color: #fff;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
            border: none;
            padding: 20px 24px;
        }

        .modal-header-custom .modal-title {
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-cuota-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            margin-bottom: 12px;
            background: var(--cont-surface);
            border-radius: var(--radius-md);
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .modal-cuota-item:hover {
            border-color: var(--cont-accent-light);
            background: var(--cont-surface-2);
        }

        .cuota-num {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--cont-accent) 0%, var(--cont-accent-dark) 100%);
            color: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 600;
            margin-right: 14px;
            flex-shrink: 0;
        }

        .cuota-info-group {
            flex: 1;
        }

        .cuota-nombre {
            font-weight: 600;
            color: var(--cont-text);
            font-size: 0.95rem;
        }

        .cuota-fecha {
            font-size: 0.85rem;
            margin-top: 2px;
        }

        .cuota-fecha-retrasada {
            color: var(--cont-danger);
            font-weight: 600;
        }

        .cuota-fecha-pendiente {
            color: var(--cont-warning);
            font-weight: 500;
        }

        .cuota-monto {
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--cont-danger);
        }

        .modal-resumen {
            background: linear-gradient(135deg, var(--cont-primary-light) 0%, rgba(94, 201, 177, 0.05) 100%);
            padding: 20px;
            border-radius: var(--radius-md);
            margin-top: 16px;
            border: 1px solid var(--cont-accent-light);
        }

        .comp-preview-section {
            background: var(--cont-surface);
            padding: 20px;
            border-radius: var(--radius-lg);
            border: 1px solid var(--cont-border);
        }

        .comp-preview-section h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            color: var(--cont-text);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .comp-preview-section h6 i {
            color: var(--cont-accent);
        }

        .cuota-group-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
            padding: 0 4px;
        }

        .cuota-group-header .group-title {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--cont-text);
        }

        .cuota-group-header .group-total {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--cont-text-muted);
        }

        .cuota-check-item {
            padding: 12px 16px;
            border-bottom: 1px solid var(--cont-border);
            display: flex;
            align-items: center;
            transition: all 0.2s;
            background: var(--cont-surface-2);
        }

        .cuota-check-item:last-child {
            border-bottom: none;
        }

        .cuota-check-item:hover {
            background: var(--cont-surface);
        }

        .cuota-check-item .cuota-info {
            margin-left: 12px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .cuota-check-item .cuota-info strong {
            color: var(--cont-text);
        }

        .reject-icon-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .reject-icon-circle i {
            font-size: 2.5rem;
            color: var(--cont-danger);
        }

        .form-control:focus {
            border-color: var(--cont-accent);
            box-shadow: 0 0 0 3px rgba(94, 201, 177, 0.15);
        }

        .cobrador-alert {
            border-radius: var(--radius-md);
            padding: 12px 16px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
        }

        .cobrador-alert i {
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .cobrador-alert .cobrador-name {
            font-weight: 600;
        }

        .cobrador-alert .cobrador-cargo {
            font-size: 0.8rem;
            opacity: 0.85;
        }

        .form-label-sm {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--cont-text-muted);
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .form-control-sm, .form-select-sm {
            border-radius: var(--radius-sm);
            border: 1px solid var(--cont-border);
            padding: 8px 12px;
            font-size: 0.875rem;
            background: var(--cont-surface);
            transition: all 0.2s;
        }

        .form-control-sm:focus, .form-select-sm:focus {
            outline: none;
            border-color: var(--cont-accent);
            box-shadow: 0 0 0 3px rgba(94, 201, 177, 0.15);
            background: white;
        }

        .cuotas-scroll-area {
            border: 1px solid var(--cont-border);
            border-radius: var(--radius-lg);
            padding: 16px;
            background: white;
            max-height: 300px;
            overflow-y: auto;
        }

        .cuotas-scroll-area::-webkit-scrollbar {
            width: 6px;
        }

        .cuotas-scroll-area::-webkit-scrollbar-track {
            background: var(--cont-surface);
            border-radius: 3px;
        }

        .cuotas-scroll-area::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .cuota-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            transition: background 0.15s;
            cursor: pointer;
        }

        .cuota-item:hover {
            background: var(--cont-surface);
        }

        .cuota-item + .cuota-item {
            border-top: 1px solid var(--cont-border);
        }

        .cuota-item .cuota-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--cont-text);
        }

        .cuota-item .cuota-detail {
            font-size: 0.75rem;
            color: var(--cont-text-muted);
        }

        .comp-preview-section h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--cont-text);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .comp-preview-section h6 i {
            color: var(--cont-accent);
        }

        .comp-preview-card {
            border: 1px solid var(--cont-border);
            border-radius: var(--radius-lg);
            padding: 16px;
            background: var(--cont-surface);
        }

        .comp-preview-card .section-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--cont-text-muted);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .comp-preview-card .section-label i {
            color: var(--cont-accent);
        }

        .comp-info-card {
            border: 1px solid var(--cont-border);
            border-radius: var(--radius-lg);
            padding: 16px;
            background: white;
            height: 100%;
        }

        .comp-info-card .section-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--cont-text-muted);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .comp-info-card .section-label i {
            color: var(--cont-accent);
        }

        .comp-info-card .info-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--cont-text-muted);
            margin-bottom: 6px;
        }

        .comp-info-card strong {
            font-size: 0.88rem;
            color: var(--cont-text);
        }

        .comp-info-card span {
            font-size: 0.84rem;
            color: var(--cont-text);
        }

        .form-card {
            border: 1px solid var(--cont-border);
            border-radius: var(--radius-lg);
            padding: 16px;
            background: white;
        }

        .form-card .section-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--cont-text-muted);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-card .section-label i {
            color: var(--cont-accent);
        }

        .comp-details p {
            font-size: 0.85rem;
            margin-bottom: 4px;
        }

        @media (max-width: 768px) {
            .deudas-header {
                flex-direction: column;
                align-items: flex-start;
                padding: 24px;
            }

            .deudas-header-meta {
                width: 100%;
            }

            .tab-section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .estudiante-main {
                flex-direction: column;
            }

            .estudiante-actions {
                width: 100%;
            }

            .btn-action {
                flex: 1;
                justify-content: center;
            }
        }
    </style>

    <div class="container-fluid deudas-page">
        <div class="deudas-header">
            <div class="deudas-header-content">
                <h1><i class="ri-alert-line"></i>Cuotas Retrasadas</h1>
                <p>Participantes con cuotas retrasadas por oferta académica</p>
            </div>
            <div class="deudas-header-meta">
                <div class="date"><i class="ri-calendar-line"></i> {{ now()->format('d/m/Y') }}</div>
            </div>
        </div>

        @if (empty($resultados))
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
                <h3>No hay cuotas retrasadas</h3>
                <p>Todos los participantes están al día con sus pagos</p>
            </div>
        @else
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs" id="ofertasTabs" role="tablist">
                    @foreach ($resultados as $index => $oferta)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                id="tab-oferta-{{ $oferta['oferta_id'] }}" data-bs-toggle="tab"
                                data-bs-target="#content-oferta-{{ $oferta['oferta_id'] }}" type="button" role="tab">
                                <span class="tab-oferta-name">{{ Str::limit($oferta['oferta_nombre'], 25) }}</span>
                                <span class="tab-badge">{{ $oferta['total_estudiantes'] }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="tab-content" id="ofertasTabsContent">
                @foreach ($resultados as $index => $oferta)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                        id="content-oferta-{{ $oferta['oferta_id'] }}" role="tabpanel">
                        <div class="tab-content-wrapper">
                            <div class="tab-section-header">
                                <h5 class="tab-section-title">
                                    <i class="ri-book-2-line"></i>{{ $oferta['oferta_nombre'] }}
                                </h5>
                                <div class="tab-section-actions">
                                    <div class="total-badge">
                                        <i class="ri-money-dollar-circle-line"></i>
                                        Total: Bs {{ number_format($oferta['total_monto'], 2) }}
                                    </div>
                                    <button class="btn-whatsapp-all"
                                        onclick="enviarWhatsAppOferta({{ $oferta['oferta_id'] }})">
                                        <i class="ri-whatsapp-line"></i> Enviar a todos
                                    </button>
                                </div>
                            </div>

                            @foreach ($oferta['estudiantes'] as $estudiante)
                                <div class="estudiante-card" data-estudiante-id="{{ $estudiante['estudiante_id'] }}">
                                    <div class="estudiante-main">
                                        <div class="estudiante-avatar">
                                            {{ substr($estudiante['nombre'], 0, 1) }}
                                        </div>
                                        <div class="estudiante-info-group">
                                            <div class="estudiante-nombre">{{ $estudiante['nombre'] }}</div>
                                            @if ($estudiante['celular'])
                                                <div class="estudiante-celular">
                                                    <i class="ri-phone-line"></i> {{ $estudiante['celular'] }}
                                                </div>
                                            @endif
                                            <div class="estudiante-deuda-amount">
                                                <div class="deuda-amount-label">Total Adeudado</div>
                                                <div class="deuda-amount-value">Bs
                                                    {{ number_format($estudiante['monto_total'], 2) }}</div>
                                            </div>
                                        </div>
                                        <div class="estudiante-actions">
                                            @if ($estudiante['celular'])
                                                <button class="btn-action btn-whatsapp-single"
                                                    onclick="enviarWhatsApp({{ $oferta['oferta_id'] }}, {{ $estudiante['estudiante_id'] }})"
                                                    title="Enviar mensaje WhatsApp">
                                                    <i class="ri-whatsapp-line"></i>
                                                </button>
                                            @endif
                                            @if ($estudiante['tiene_comprobantes'])
                                                <button class="btn-action btn-comprobante"
                                                    onclick="verComprobantes({{ $oferta['oferta_id'] }}, {{ $estudiante['estudiante_id'] }})"
                                                    title="Ver comprobantes">
                                                    <i class="ri-file-check-line"></i>
                                                    ({{ count($estudiante['comprobantes']) }})
                                                </button>
                                            @endif
                                            <button class="btn-action btn-ver-cuotas"
                                                onclick="abrirModalCuotas({{ $oferta['oferta_id'] }}, {{ $estudiante['estudiante_id'] }})">
                                                <i class="ri-eye-line"></i> Ver ({{ $estudiante['retrasadas'] }})
                                            </button>
                                        </div>
                                    </div>
                                    <div class="estudiante-cuotas-resumen">
                                        <span class="cuota-badge retrasada">
                                            <i class="ri-alert-line"></i> {{ $estudiante['retrasadas'] }} retrasada(s)
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Modal para mostrar cuotas -->
    <div class="modal fade" id="modalCuotas" tabindex="-1" aria-labelledby="modalCuotasLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title" id="modalCuotasLabel">
                        <i class="ri-calendar-line me-2"></i>Cuotas del Participante
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalCuotasBody"></div>
                <div class="modal-footer"
                    style="background: var(--cont-surface); border-top: 1px solid var(--cont-border);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para verificar comprobante -->
    <div class="modal fade modal-comp" id="modalVerificar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title">
                        <i class="ri-shield-check-line me-2"></i>Verificar Comprobante y Registrar Pago
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer"
                    style="background: var(--cont-surface); border-top: 1px solid var(--cont-border);"></div>
            </div>
        </div>
    </div>

    <!-- Modal para rechazar comprobante -->
    <div class="modal fade modal-comp modal-reject" id="modalRechazar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, var(--cont-danger) 0%, #dc2626 100%); color: white;">
                    <h5 class="modal-title">
                        <i class="ri-close-circle-line me-2"></i>Rechazar Comprobante
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="formRechazar">
                    @csrf
                    <input type="hidden" id="rechazar_comprobante_id" name="comprobante_id">
                    <div class="modal-body text-center p-4">
                        <div class="reject-icon-circle">
                            <i class="ri-alert-line"></i>
                        </div>
                        <h5 style="font-family:'Outfit',sans-serif;font-weight:600;margin-bottom:8px;">¿Estás seguro de
                            rechazar este comprobante?</h5>
                        <p class="text-muted mb-3" style="font-size:0.9rem;">Esta acción notificará al estudiante sobre el
                            rechazo.</p>
                        <div class="text-start">
                            <label for="motivo_rechazo" class="form-label fw-semibold">Motivo del rechazo *</label>
                            <textarea class="form-control" id="motivo_rechazo" name="motivo_rechazo" rows="3" required
                                placeholder="Indica el motivo del rechazo..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center pb-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn" style="background: var(--cont-danger); color: white;">
                            <i class="ri-close-circle-line me-1"></i>Rechazar Comprobante
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            @php
                $cobradorComp = \Illuminate\Support\Facades\DB::table('users')
                    ->join('personas', 'users.persona_id', '=', 'personas.id')
                    ->join('trabajadores', 'personas.id', '=', 'trabajadores.persona_id')
                    ->join('trabajadores_cargos', 'trabajadores.id', '=', 'trabajadores_cargos.trabajadore_id')
                    ->join('cargos', 'trabajadores_cargos.cargo_id', '=', 'cargos.id')
                    ->where('users.id', auth()->id())
                    ->where('trabajadores_cargos.principal', 1)
                    ->where('trabajadores_cargos.estado', 'Vigente')
                    ->select('personas.nombres', 'personas.apellido_paterno', 'cargos.nombre as cargo')
                    ->first();
            @endphp
            var cobradorData = @json($cobradorComp);

            const deudasData = @json($resultados);
            let modalCuotasInstance = null;
            let modalVerificarInstance = null;
            let modalRechazarInstance = null;

            document.addEventListener('DOMContentLoaded', function() {
                modalCuotasInstance = new bootstrap.Modal(document.getElementById('modalCuotas'));
                modalVerificarInstance = new bootstrap.Modal(document.getElementById('modalVerificar'));
                modalRechazarInstance = new bootstrap.Modal(document.getElementById('modalRechazar'));

                document.getElementById('formRechazar').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const comprobanteId = document.getElementById('rechazar_comprobante_id').value;
                    const motivo = document.getElementById('motivo_rechazo').value;

                    fetch('/admin/comprobante/' + comprobanteId + '/rechazar', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                motivo_rechazo: motivo
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showToast('success', 'Comprobante rechazado correctamente');
                                modalRechazarInstance.hide();
                                location.reload();
                            } else {
                                showToast('error', data.message || 'Error al rechazar');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('error', 'Error al rechazar');
                        });
                });
            });

            function showToast(type, message) {
                var config = {
                    success: { icon: 'ri-checkbox-circle-fill', bgClass: 'bg-success', title: 'Exito' },
                    error: { icon: 'ri-close-circle-fill', bgClass: 'bg-danger', title: 'Error' },
                    warning: { icon: 'ri-alert-fill', bgClass: 'bg-warning', title: 'Advertencia' },
                    info: { icon: 'ri-information-fill', bgClass: 'bg-info', title: 'Informacion' }
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

            // ======================== FUNCIÓN PARA ABRIR MODAL DE CUOTAS ========================
            function abrirModalCuotas(ofertaId, estudianteId) {
                let oferta = deudasData.find(o => o.oferta_id === ofertaId);
                if (!oferta) {
                    alert('No se encontraron datos de la oferta');
                    return;
                }
                let estudiante = oferta.estudiantes.find(e => e.estudiante_id === estudianteId);
                if (!estudiante) {
                    alert('No se encontraron datos del estudiante');
                    return;
                }

                const cuotas = estudiante.cuotas;
                if (!cuotas || cuotas.length === 0) {
                    document.getElementById('modalCuotasBody').innerHTML =
                        '<div class="alert alert-info">No hay cuotas registradas.</div>';
                    modalCuotasInstance.show();
                    return;
                }

                let html = '';
                cuotas.forEach(cuota => {
                    const fecha = new Date(cuota.fecha_pago);
                    const fechaFormateada = fecha.toLocaleDateString('es-ES', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    const estadoClass = cuota.estado === 'retrasada' ? 'cuota-fecha-retrasada' :
                        'cuota-fecha-pendiente';
                    const estadoTexto = cuota.estado === 'retrasada' ? 'RETRASADA' : 'PENDIENTE';
                    html += `
                    <div class="modal-cuota-item">
                        <div class="d-flex align-items-center">
                            <span class="cuota-num">${cuota.n_cuota}</span>
                            <div class="cuota-info-group">
                                <div class="cuota-nombre">${escapeHtml(cuota.nombre)}</div>
                                <small class="cuota-fecha ${estadoClass}">${estadoTexto} - Vence: ${fechaFormateada}</small>
                            </div>
                        </div>
                        <div class="cuota-monto">Bs ${cuota.monto_bs.toFixed(2)}</div>
                    </div>
                `;
                });

                const totalRetrasadas = cuotas.filter(c => c.estado === 'retrasada').length;
                const totalMonto = cuotas.reduce((sum, c) => sum + c.monto_bs, 0);
                html += `
                    <div class="modal-resumen">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong style="color: var(--cont-text);">${totalRetrasadas} cuota(s) retrasada(s)</strong>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span style="color: var(--cont-text-muted); font-size: 0.9rem;">Total adeudado:</span>
                                <span style="font-family: 'Outfit', sans-serif; font-size: 1.25rem; font-weight: 700; color: var(--cont-danger);">Bs ${totalMonto.toFixed(2)}</span>
                            </div>
                        </div>
                    </div>`;

                document.getElementById('modalCuotasBody').innerHTML = html;
                modalCuotasInstance.show();
            }

            // ======================== FUNCIÓN PARA VER COMPROBANTES ========================
            function verComprobantes(ofertaId, estudianteId) {
                let oferta = deudasData.find(o => o.oferta_id === ofertaId);
                if (!oferta) return;
                let estudiante = oferta.estudiantes.find(e => e.estudiante_id === estudianteId);
                if (!estudiante || !estudiante.comprobantes || estudiante.comprobantes.length === 0) return;

                const comprobanteId = estudiante.comprobantes[0].id; // Tomamos el primero (puedes adaptar)

                // Mostrar loading en el modal
                const modalBody = document.querySelector('#modalVerificar .modal-body');
                const modalFooter = document.querySelector('#modalVerificar .modal-footer');
                modalBody.innerHTML =
                    '<div class="text-center p-4"><div class="spinner-border text-primary"></div><p class="mt-2">Cargando comprobante...</p></div>';
                modalFooter.innerHTML = '';

                fetch('/admin/comprobante/' + comprobanteId + '/detalle')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            mostrarFormularioVerificacion(data);
                        } else {
                            modalBody.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        modalBody.innerHTML = '<div class="alert alert-danger">Error al cargar los datos.</div>';
                    });

                modalVerificarInstance.show();
            }

            function mostrarFormularioVerificacion(data) {
                const comp = data.comprobante;
                const estudiante = data.estudiante;
                const programa = data.programa;
                const archivoUrl = data.archivo_url;
                const ext = archivoUrl.split('?')[0].split('.').pop().toLowerCase();
                let previewHtml = '';
                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                    previewHtml = `<a href="${archivoUrl}" target="_blank">
                    <img src="${archivoUrl}" class="img-fluid rounded border" style="max-height:260px;width:100%;object-fit:contain;cursor:pointer;" title="Clic para ampliar">
                </a>`;
                } else if (ext === 'pdf') {
                    previewHtml = `<embed src="${archivoUrl}" type="application/pdf" width="100%" height="260px" class="rounded border">
                    <div class="mt-1 text-end"><a href="${archivoUrl}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="ri-external-link-line me-1"></i>Abrir PDF</a></div>`;
                } else {
                    previewHtml = `<div class="d-flex align-items-center justify-content-center border rounded bg-light" style="height:80px;">
                    <a href="${archivoUrl}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="ri-download-line me-1"></i>Descargar archivo</a>
                </div>`;
                }

                const iconosPorDefecto = {
                    'Matricula': 'ri-graduation-cap-line',
                    'Matrícula': 'ri-graduation-cap-line',
                    'Colegiatura': 'ri-book-open-line',
                    'Certificacion': 'ri-award-line',
                    'Certificación': 'ri-award-line',
                    'Otros': 'ri-file-list-line'
                };
                const ordenPreferido = ['Matricula', 'Matrícula', 'Colegiatura', 'Certificacion', 'Certificación', 'Otros'];

                function buildGrupos(cuotas) {
                    let grupos = {};
                    cuotas.forEach(c => {
                        let tipo = c.tipo || 'Otros';
                        if (!grupos[tipo]) grupos[tipo] = [];
                        grupos[tipo].push(c);
                    });
                    return grupos;
                }

                function renderGrupo(grupos, checked) {
                    let html = '';
                    // Obtener las claves existentes en el orden preferido
                    const tiposExistentes = Object.keys(grupos).sort((a, b) => {
                        const idxA = ordenPreferido.indexOf(a);
                        const idxB = ordenPreferido.indexOf(b);
                        const ordenA = idxA === -1 ? 999 : idxA;
                        const ordenB = idxB === -1 ? 999 : idxB;
                        return ordenA - ordenB;
                    });
                    
                    tiposExistentes.forEach(tipo => {
                        if (!grupos[tipo] || grupos[tipo].length === 0) return;
                        const icono = iconosPorDefecto[tipo] || 'ri-file-list-line';
                        const total = grupos[tipo].reduce((s, c) => s + Number(c.pago_pendiente_bs || c.pendiente_bs || 0), 0);
                        html += `<div class="mb-2">
                        <div class="cuota-group-header">
                            <div class="group-title"><i class="${icono}" style="color:var(--cont-accent);"></i> <span>${tipo}</span></div>
                            <div class="group-total">Pendiente: ${Number(total || 0).toFixed(2)} Bs</div>
                        </div>`;
                        grupos[tipo].forEach(cuota => {
                            const uid = checked ? 'cuota_' + cuota.id : 'cuota_ex_' + cuota.id;
                            const pendiente = Number(cuota.pago_pendiente_bs || cuota.pendiente_bs || 0);
                            const totalCuota = Number(cuota.pago_total_bs || cuota.monto_bs || 0);
                            html += `
                        <label class="cuota-item">
                            <input class="form-check-input cuota-checkbox flex-shrink-0" type="checkbox" 
                                name="cuota_ids[]" value="${cuota.id}" id="${uid}" ${checked ? 'checked' : ''}>
                            <div class="flex-grow-1 min-w-0">
                                <span class="cuota-name">${escapeHtml(cuota.nombre)}</span>
                                <span class="cuota-detail">Pendiente: <strong>${pendiente.toFixed(2)} Bs</strong> / Total: ${totalCuota.toFixed(2)} Bs</span>
                            </div>
                        </label>`;
                        });
                        html += `</div>`;
                    });
                    return html;
                }

                const cuotasAsociadas = data.cuotas;
                const cuotasPendientes = data.cuotas_pendientes;
                const gruposAsociados = buildGrupos(cuotasAsociadas);
                const gruposPendientes = buildGrupos(cuotasPendientes);

                let cuotasHtml = renderGrupo(gruposAsociados, true);
                if (cuotasPendientes && cuotasPendientes.length > 0) {
                    cuotasHtml += `<div class="border-top pt-2 mt-2">
                        <p class="text-muted small mb-2"><i class="ri-add-circle-line me-1"></i>Otras cuotas pendientes (opcional):</p>
                        ${renderGrupo(gruposPendientes, false)}
                    </div>`;
                }

                const cobradorHtml = cobradorData
                    ? `<div class="cobrador-alert" style="background:var(--cont-success-light);color:#059669;">
                        <i class="ri-user-star-line"></i>
                        <div>
                            <div class="cobrador-name">${cobradorData.nombres} ${cobradorData.apellido_paterno}</div>
                            <div class="cobrador-cargo">Cobrador - ${cobradorData.cargo}</div>
                        </div>
                      </div>`
                    : `<div class="cobrador-alert" style="background:var(--cont-warning-light);color:#b45309;">
                        <i class="ri-alert-line"></i>
                        <div>No se pudo identificar al cobrador. Verifique su cargo vigente.</div>
                       </div>`;

                const modalBody = document.querySelector('#modalVerificar .modal-body');
                modalBody.innerHTML = `
                <form id="formVerificarPago">
                    @csrf
                    <input type="hidden" name="comprobante_id" value="${comp.id}">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <div class="comp-preview-card mb-2">
                                <div class="section-label"><i class="ri-image-line"></i>Comprobante</div>
                                ${previewHtml}
                            </div>
                            <div class="comp-info-card">
                                <div class="section-label" style="margin-bottom:10px;"><i class="ri-user-line"></i>Estudiante</div>
                                <div class="mb-2">
                                    <div class="info-label">Nombre</div>
                                    <strong style="font-size:.88rem;">${estudiante.nombre}</strong>
                                </div>
                                <div class="mb-2">
                                    <div class="info-label">Carnet</div>
                                    <span style="font-size:.84rem;">${estudiante.carnet || 'Sin carnet'}</span>
                                </div>
                                <div>
                                    <div class="info-label">Programa</div>
                                    <span style="font-size:.84rem;">${programa}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="cuotas-scroll-area mb-3">
                                <div class="section-label" style="margin-bottom:10px;"><i class="ri-file-list-3-line"></i>Cuotas a las que aplica el pago</div>
                                ${cuotasHtml}
                            </div>

                            <div class="form-card">
                                <div class="section-label"><i class="ri-money-dollar-circle-line"></i>Datos del Pago</div>
                                ${cobradorHtml}
                                <div class="row g-2 mt-2">
                                    <div class="col-6">
                                        <label class="form-label-sm">Monto a pagar (Bs) *</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm" id="monto_pago" name="monto_pago" value="${parseFloat(comp.monto).toFixed(2)}" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label-sm">Descuento (Bs)</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm" id="descuento_bs" name="descuento_bs" value="0">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label-sm">Tipo de pago *</label>
                                        <select class="form-select form-select-sm" id="tipo_pago" name="tipo_pago" required>
                                            <option value="">Seleccionar</option>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="Deposito">Deposito</option>
                                            <option value="Tarjeta">Tarjeta</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label-sm">Fecha de pago *</label>
                                        <input type="date" class="form-control form-control-sm" id="fecha_pago" name="fecha_pago" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-12" id="campo_caja" style="display:none;">
                                        <label class="form-label-sm">Caja *</label>
                                        <select class="form-select form-select-sm" id="caja_id" name="caja_id">
                                            <option value="">-- Seleccionar caja --</option>
                                            @foreach (\App\Models\Caja::where('activa', true)->with('sucursal')->get() as $caja)
                                                <option value="{{ $caja->id }}">
                                                    {{ $caja->nombre }} - {{ $caja->sucursal->nombre ?? 'Sin sucursal' }} (Saldo: {{ number_format($caja->saldo_actual, 2) }} Bs)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12" id="campo_cuenta_bancaria" style="display:none;">
                                        <label class="form-label-sm">Cuenta Bancaria *</label>
                                        <select class="form-select form-select-sm" id="cuenta_bancaria_id" name="cuenta_bancaria_id">
                                            <option value="">-- Seleccionar cuenta --</option>
                                            @foreach (\App\Models\CuentasBancarias::where('activa', true)->with('banco')->get() as $cuenta)
                                                <option value="{{ $cuenta->id }}">
                                                    {{ $cuenta->banco->nombre ?? 'Sin banco' }} - {{ $cuenta->numero_cuenta }} ({{ $cuenta->moneda }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12" id="campo_comprobante" style="display:none;">
                                        <label class="form-label-sm">Nro Comprobante</label>
                                        <input type="text" class="form-control form-control-sm" id="n_comprobante" name="n_comprobante" placeholder="Ej: TRF-0012345">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label-sm">Observaciones</label>
                                        <textarea class="form-control form-control-sm" id="observaciones" name="observaciones" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>`;

                // Agregar eventos a los checkboxes "Seleccionar todos"
                const selectAllPend = document.getElementById('selectAllPendientes');
                const selectAllAsoc = document.getElementById('selectAllAsociadas');
                if (selectAllPend) {
                    selectAllPend.addEventListener('change', function(e) {
                        document.querySelectorAll('input[name="cuota_ids[]"]').forEach(chk => chk.checked = e.target.checked);
                    });
                }
                if (selectAllAsoc) {
                    selectAllAsoc.addEventListener('change', function(e) {
                        document.querySelectorAll('input[name="cuota_ids[]"]').forEach(chk => chk.checked = e.target.checked);
                    });
                }

                // Evento para cambiar tipo de pago
                document.getElementById('tipo_pago').addEventListener('change', togglePaymentFields);
                togglePaymentFields();

                const footer = document.querySelector('#modalVerificar .modal-footer');
                footer.innerHTML = `
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-sm" id="btnConfirmarVerificar"
                            style="background:var(--cont-accent);color:white;">
                        <i class="ri-checkbox-circle-line me-1"></i>Verificar y Registrar Pago
                    </button>`;

                document.getElementById('btnConfirmarVerificar').addEventListener('click', function() {
                    procesarVerificacion(comp.id);
                });
            }

            function togglePaymentFields() {
                var tipoPago = document.getElementById('tipo_pago').value;
                document.getElementById('campo_caja').style.display = 'none';
                document.getElementById('campo_cuenta_bancaria').style.display = 'none';
                document.getElementById('campo_comprobante').style.display = 'none';

                document.getElementById('caja_id').required = false;
                document.getElementById('cuenta_bancaria_id').required = false;
                document.getElementById('n_comprobante').required = false;

                if (tipoPago === 'Efectivo') {
                    document.getElementById('campo_caja').style.display = 'block';
                    document.getElementById('caja_id').required = true;
                } else if (['Transferencia', 'Deposito', 'Tarjeta'].includes(tipoPago)) {
                    document.getElementById('campo_cuenta_bancaria').style.display = 'block';
                    document.getElementById('campo_comprobante').style.display = 'block';
                    document.getElementById('cuenta_bancaria_id').required = true;
                    document.getElementById('n_comprobante').required = true;
                }
            }

            function procesarVerificacion(comprobanteId) {
                const cuotaIds = [];
                document.querySelectorAll('.cuota-checkbox:checked').forEach(function(cb) {
                    cuotaIds.push(cb.value);
                });

                if (cuotaIds.length === 0) {
                    showToast('warning', 'Debe seleccionar al menos una cuota');
                    return;
                }

                const tipoPago = document.getElementById('tipo_pago').value;
                if (!tipoPago) {
                    showToast('warning', 'Debe seleccionar el tipo de pago');
                    return;
                }

                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('comprobante_id', comprobanteId);
                // Redondear correctamente el monto a 2 decimales
                const montoOriginal = parseFloat(document.getElementById('monto_pago').value);
                const montoRedondeado = Math.round(montoOriginal * 100) / 100;
                formData.append('monto_pago', montoRedondeado.toFixed(2));
                formData.append('descuento_bs', Math.round(parseFloat(document.getElementById('descuento_bs').value || 0) * 100) / 100);
                formData.append('tipo_pago', tipoPago);
                formData.append('fecha_pago', document.getElementById('fecha_pago').value);
                formData.append('observaciones', document.getElementById('observaciones').value);

                cuotaIds.forEach(function(id) {
                    formData.append('cuota_ids[]', id);
                });

                if (tipoPago === 'Efectivo') {
                    if (!document.getElementById('caja_id').value) {
                        showToast('warning', 'Debe seleccionar una caja');
                        return;
                    }
                    formData.append('caja_id', document.getElementById('caja_id').value);
                } else if (['Transferencia', 'Deposito', 'Tarjeta'].includes(tipoPago)) {
                    if (!document.getElementById('cuenta_bancaria_id').value) {
                        showToast('warning', 'Debe seleccionar una cuenta bancaria');
                        return;
                    }
                    formData.append('cuenta_bancaria_id', document.getElementById('cuenta_bancaria_id').value);
                    formData.append('n_comprobante', document.getElementById('n_comprobante').value);
                }

                fetch('/admin/comprobante/' + comprobanteId + '/verificar', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', data.message);
                            modalVerificarInstance.hide();
                            location.reload();
                        } else {
                            showToast('error', data.message || 'Error al registrar');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', 'Error al procesar');
                    });
            }

            // ======================== FUNCIONES WHATSAPP ========================
            function formatFechaLarga(fechaStr) {
                const fecha = new Date(fechaStr + 'T00:00:00');
                const dia = fecha.getDate();
                const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre',
                    'octubre', 'noviembre', 'diciembre'
                ];
                const mes = meses[fecha.getMonth()];
                const anio = fecha.getFullYear();
                return `${dia} de ${mes} del ${anio}`;
            }

            function enviarWhatsApp(ofertaId, estudianteId) {
                let oferta = deudasData.find(o => o.oferta_id === ofertaId);
                if (!oferta) return;
                let estudiante = oferta.estudiantes.find(e => e.estudiante_id === estudianteId);
                if (!estudiante || !estudiante.celular) return;

                const conceptosOrden = ['Matricula', 'Colegiatura', 'Certificacion', 'Certificación'];
                const grupos = {};

                estudiante.cuotas.forEach(cuota => {
                    if (cuota.estado !== 'retrasada') return;
                    let concepto = cuota.nombre || 'Otro';
                    for (let c of conceptosOrden) {
                        if (concepto.toLowerCase().includes(c.toLowerCase())) {
                            concepto = c;
                            break;
                        }
                    }
                    if (!grupos[concepto]) grupos[concepto] = [];
                    grupos[concepto].push(cuota);
                });

                if (Object.keys(grupos).length === 0) {
                    alert('No tiene cuotas retrasadas para enviar');
                    return;
                }

                let mensaje =
                    `Hola *${estudiante.nombre}*, le informamos que tiene las siguientes cuotas *RETRASADAS* en *${oferta.oferta_nombre}*:\n\n`;
                for (const [concepto, cuotas] of Object.entries(grupos)) {
                    mensaje += `*${concepto}:*\n`;
                    cuotas.forEach(cuota => {
                        mensaje +=
                            `• Cuota ${cuota.n_cuota}: Bs ${cuota.monto_bs.toFixed(2)} - ${formatFechaLarga(cuota.fecha_pago)}\n`;
                    });
                    const subtotal = cuotas.reduce((sum, c) => sum + c.monto_bs, 0);
                    mensaje += `  Subtotal: Bs ${subtotal.toFixed(2)}\n\n`;
                }
                const totalRetrasadas = estudiante.cuotas.filter(c => c.estado === 'retrasada').reduce((sum, c) => sum + c
                    .monto_bs, 0);
                mensaje += `*TOTAL RETRASADO: Bs ${totalRetrasadas.toFixed(2)}*\n\n`;
                mensaje +=
                    `Favor realizar el pago lo antes posible para evitar complicaciones.\n\nSaludos cordiales\n*UNIP - Área Contable*`;

                const celularLimpio = estudiante.celular.replace(/\D/g, '');
                const urlWhatsApp = `https://wa.me/591${celularLimpio}?text=${encodeURIComponent(mensaje)}`;
                window.open(urlWhatsApp, '_blank');
            }

            function enviarWhatsAppOferta(ofertaId) {
                let oferta = deudasData.find(o => o.oferta_id === ofertaId);
                if (!oferta) return;

                let enviados = 0,
                    sinCelular = 0;
                oferta.estudiantes.forEach(estudiante => {
                    if (!estudiante.celular || estudiante.celular.trim() === '') {
                        sinCelular++;
                        return;
                    }
                    if (estudiante.retrasadas === 0) return;

                    const conceptosOrden = ['Matricula', 'Colegiatura', 'Certificacion', 'Certificación'];
                    const grupos = {};
                    estudiante.cuotas.forEach(cuota => {
                        if (cuota.estado !== 'retrasada') return;
                        let concepto = cuota.nombre || 'Otro';
                        for (let c of conceptosOrden) {
                            if (concepto.toLowerCase().includes(c.toLowerCase())) {
                                concepto = c;
                                break;
                            }
                        }
                        if (!grupos[concepto]) grupos[concepto] = [];
                        grupos[concepto].push(cuota);
                    });
                    if (Object.keys(grupos).length === 0) return;

                    let mensaje =
                        `Hola *${estudiante.nombre}*, le informamos que tiene las siguientes cuotas *RETRASADAS* en *${oferta.oferta_nombre}*:\n\n`;
                    for (const [concepto, cuotas] of Object.entries(grupos)) {
                        mensaje += `*${concepto}:*\n`;
                        cuotas.forEach(cuota => {
                            mensaje +=
                                `• Cuota ${cuota.n_cuota}: Bs ${cuota.monto_bs.toFixed(2)} - ${formatFechaLarga(cuota.fecha_pago)}\n`;
                        });
                        const subtotal = cuotas.reduce((sum, c) => sum + c.monto_bs, 0);
                        mensaje += `  Subtotal: Bs ${subtotal.toFixed(2)}\n\n`;
                    }
                    const totalRetrasadas = estudiante.cuotas.filter(c => c.estado === 'retrasada').reduce((sum, c) =>
                        sum + c.monto_bs, 0);
                    mensaje += `*TOTAL RETRASADO: Bs ${totalRetrasadas.toFixed(2)}*\n\n`;
                    mensaje +=
                        `Favor realizar el pago lo antes posible para evitar complicaciones.\n\nSaludos cordiales\n*UNIP - Área Contable*`;

                    const celularLimpio = estudiante.celular.replace(/\D/g, '');
                    const urlWhatsApp = `https://wa.me/591${celularLimpio}?text=${encodeURIComponent(mensaje)}`;
                    window.open(urlWhatsApp, '_blank');
                    enviados++;
                });

                if (enviados === 0 && sinCelular > 0) {
                    alert('No se encontraron participantes con número de celular en esta oferta');
                } else if (enviados > 0) {
                    alert(
                        `Se abrió ${enviados} mensaje(s) de WhatsApp para ${oferta.oferta_nombre}${sinCelular > 0 ? ', ' + sinCelular + ' sin celular' : ''}`
                    );
                } else {
                    alert('No hay participantes con cuotas retrasadas en esta oferta');
                }
            }

            // Función auxiliar para escapar HTML
            function escapeHtml(str) {
                if (!str) return '';
                return str.replace(/[&<>]/g, function(m) {
                    if (m === '&') return '&amp;';
                    if (m === '<') return '&lt;';
                    if (m === '>') return '&gt;';
                    return m;
                });
            }
        </script>
    @endpush
@endsection
