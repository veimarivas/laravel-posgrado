@extends('admin.dashboard')
@push('style')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap');

        :root {
            --modulos-primary: #0d9488;
            --modulos-primary-light: #e6fffa;
            --modulos-primary-dark: #0f766e;
            --modulos-accent: #f59e0b;
            --modulos-accent-light: #fffbeb;
            --modulos-surface: #f8fafc;
            --modulos-border: #e2e8f0;
            --modulos-text: #1e293b;
            --modulos-text-muted: #64748b;
            --modulos-success: #10b981;
            --modulos-danger: #ef4444;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
        }

        .vermodulos-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--modulos-text);
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

        .vermodulos-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
            padding: 24px 28px;
            background: linear-gradient(135deg, var(--modulos-primary) 0%, var(--modulos-primary-dark) 100%);
            border-radius: var(--radius-lg);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .vermodulos-header::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -5%;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .vermodulos-header::after {
            content: '';
            position: absolute;
            bottom: -25%;
            left: 15%;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .vermodulos-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1;
            color: white;
        }

        .vermodulos-header .breadcrumb {
            position: relative;
            z-index: 1;
        }

        .vermodulos-header .breadcrumb a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
        }

        .vermodulos-header .breadcrumb a:hover {
            color: white;
        }

        .vermodulos-header .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.9);
        }

        .vermodulos-header .header-info {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .vermodulos-header .info-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .vermodulos-header .badge-oferta {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-size: 0.72rem;
            padding: 4px 10px;
        }

        .btn-dashboard {
            background: white;
            color: var(--modulos-primary);
            border: none;
            padding: 8px 18px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.25s ease;
            box-shadow: var(--shadow-sm);
            position: relative;
            z-index: 1;
        }

        .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: rgba(255, 255, 255, 0.95);
            color: var(--modulos-primary);
        }

        .vermodulos-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--modulos-border);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .vermodulos-card:hover {
            box-shadow: var(--shadow-md);
        }

        .vermodulos-card-header {
            padding: 16px 20px;
            border-bottom: 1px dashed var(--modulos-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--modulos-surface);
        }

        .vermodulos-card-header h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            margin: 0;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .vermodulos-card-header h6 i {
            color: var(--modulos-primary);
        }

        .badge-count {
            background: var(--modulos-primary-light);
            color: var(--modulos-primary);
            border: 1px solid var(--modulos-primary);
            font-size: 0.72rem;
            padding: 3px 8px;
        }

        .modulo-item {
            transition: all 0.2s ease;
            border-radius: var(--radius-md);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .modulo-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: rgba(255, 255, 255, 0.4);
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .modulo-item:hover {
            transform: translateX(4px);
            box-shadow: var(--shadow-md);
        }

        .modulo-item:hover::before {
            opacity: 1;
        }

        .modulo-item.activo {
            outline: 3px solid rgba(255, 255, 255, 0.8);
            outline-offset: -2px;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.15);
            transform: translateX(4px);
        }

        .modulo-item.activo::before {
            opacity: 1;
            background: white;
        }

        .modulo-number {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .modulo-name {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .modulo-docente {
            font-size: 0.75rem;
            opacity: 0.85;
        }

        .modulo-docente.empty {
            font-style: italic;
            opacity: 0.6;
        }

        .modulo-actions {
            display: flex;
            gap: 4px;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .modulo-item:hover .modulo-actions {
            opacity: 1;
        }

        .modulo-panel {
            overflow-y: auto;
        }

        .modulo-panel::-webkit-scrollbar {
            width: 6px;
        }

        .modulo-panel::-webkit-scrollbar-track {
            background: var(--modulos-surface);
            border-radius: 3px;
        }

        .modulo-panel::-webkit-scrollbar-thumb {
            background: var(--modulos-border);
            border-radius: 3px;
        }

        .modulo-panel::-webkit-scrollbar-thumb:hover {
            background: var(--modulos-text-muted);
        }

        .modulo-action-btn {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: inherit;
            transition: all 0.15s ease;
            font-size: 0.85rem;
        }

        .modulo-action-btn:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: scale(1.1);
        }

        .empty-modules {
            padding: 40px 20px;
            text-align: center;
        }

        .empty-modules i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 12px;
        }

        .empty-modules p {
            color: var(--modulos-text-muted);
            margin: 0;
            font-size: 0.85rem;
        }

        .min-width-0 {
            min-width: 0;
        }

        .btn-ver-todos {
            background: var(--modulos-primary);
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: var(--radius-sm);
            font-weight: 500;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-ver-todos:hover {
            background: var(--modulos-primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .filtro-label {
            background: var(--modulos-primary-light);
            color: var(--modulos-primary);
            border: 1px solid var(--modulos-primary);
            font-size: 0.72rem;
            padding: 4px 10px;
            border-radius: 50px;
        }

        .fc-event {
            border-radius: 6px !important;
            padding: 2px 6px !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            border-left: 3px solid rgba(0, 0, 0, 0.2) !important;
        }

        .fc-event-title {
            font-weight: 600 !important;
        }

        .fc-daygrid-event {
            padding: 2px 4px !important;
        }

        .fc .fc-toolbar-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--modulos-text);
        }

        .fc .fc-button {
            background: var(--modulos-surface) !important;
            border: 1px solid var(--modulos-border) !important;
            color: var(--modulos-text-muted) !important;
            font-weight: 500 !important;
            font-size: 0.8rem !important;
            padding: 6px 12px !important;
            border-radius: var(--radius-sm) !important;
            transition: all 0.15s ease !important;
        }

        .fc .fc-button:hover {
            background: var(--modulos-border) !important;
            color: var(--modulos-text) !important;
        }

        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active {
            background: var(--modulos-primary) !important;
            border-color: var(--modulos-primary) !important;
            color: white !important;
        }

        .fc .fc-daygrid-day-number {
            font-size: 0.85rem;
            color: var(--modulos-text-muted);
        }

        .fc .fc-col-header-cell-cushion {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--modulos-text-muted);
            text-transform: uppercase;
        }

        .fc-theme-standard td,
        .fc-theme-standard th {
            border-color: var(--modulos-border) !important;
        }

        .fc-day-today {
            background: var(--modulos-primary-light) !important;
        }

        #calendar {
            position: relative;
            z-index: 1;
        }

        #calendar .fc-event {
            cursor: pointer !important;
            pointer-events: auto !important;
            position: relative;
            z-index: 10 !important;
        }

        #calendar .fc-event:hover {
            z-index: 20 !important;
        }

        .fc-daygrid-event {
            cursor: pointer !important;
            pointer-events: auto !important;
        }

        .fc-h-event {
            cursor: pointer !important;
            pointer-events: auto !important;
        }

        .modal-backdrop {
            z-index: 1050 !important;
        }

        body.modal-open {
            overflow: auto !important;
        }

        .modal.show {
            display: flex !important;
        }

        .modal-header-colored {
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 16px;
        }

        .step-dot {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .step-dot.active {
            background: #0d6efd;
            color: #fff;
        }

        .step-dot.done {
            background: #198754;
            color: #fff;
        }

        .step-dot.idle {
            background: #e9ecef;
            color: #6c757d;
        }

        .step-line {
            flex: 1;
            height: 2px;
            background: #e9ecef;
        }

        .step-line.done {
            background: #198754;
        }

        .estado-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }

        #toastContainer {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9000;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }

        .app-toast {
            min-width: 280px;
            max-width: 380px;
            padding: 12px 16px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: flex-start;
            gap: 10px;
            pointer-events: all;
            font-size: 0.85rem;
            animation: toastIn 0.25s ease;
        }

        .app-toast.hiding {
            animation: toastOut 0.3s ease forwards;
        }

        .app-toast-icon {
            font-size: 1.1rem;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .app-toast-body {
            flex: 1;
            line-height: 1.4;
        }

        .app-toast-close {
            background: none;
            border: none;
            padding: 0;
            opacity: 0.6;
            cursor: pointer;
            font-size: 1rem;
            line-height: 1;
        }

        .app-toast-close:hover {
            opacity: 1;
        }

        .app-toast.success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .app-toast.danger {
            background: #fee2e2;
            color: #7f1d1d;
            border-left: 4px solid #ef4444;
        }

        .app-toast.warning {
            background: #fef9c3;
            color: #713f12;
            border-left: 4px solid #f59e0b;
        }

        .app-toast.info {
            background: #e0f2fe;
            color: #0c4a6e;
            border-left: 4px solid #0ea5e9;
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes toastOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(30px);
            }
        }

        .modal.vermodulos-modal {
            background: rgba(0, 0, 0, 0.5);
        }

        .modal.vermodulos-modal.show {
            display: flex !important;
        }

        .vermodulos-modal .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .vermodulos-modal .modal-header {
            border-bottom: 1px dashed var(--modulos-border);
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .vermodulos-modal .modal-header h5 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .vermodulos-modal .modal-header h5 i {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .vermodulos-modal .modal-body {
            padding: 24px;
        }

        .vermodulos-modal .modal-footer {
            border-top: 1px dashed var(--modulos-border);
            padding: 16px 24px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            background: var(--modulos-surface);
        }

        .vermodulos-modal .modal-title-icon {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .vermodulos-modal .form-label {
            font-weight: 500;
            font-size: 0.82rem;
            color: var(--modulos-text);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .vermodulos-modal .form-label i {
            color: var(--modulos-primary);
            font-size: 0.9rem;
        }

        .vermodulos-modal .form-control,
        .vermodulos-modal .form-select {
            border: 1px solid var(--modulos-border);
            border-radius: var(--radius-sm);
            padding: 8px 12px;
            font-size: 0.85rem;
            transition: all 0.15s ease;
        }

        .vermodulos-modal .form-control:focus,
        .vermodulos-modal .form-select:focus {
            border-color: var(--modulos-primary);
            box-shadow: 0 0 0 3px var(--modulos-primary-light);
        }

        .vermodulos-modal .btn {
            border-radius: var(--radius-sm);
            font-weight: 500;
            font-size: 0.85rem;
            padding: 8px 16px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
        }

        .vermodulos-modal .btn-primary {
            background: var(--modulos-primary);
            border-color: var(--modulos-primary);
        }

        .vermodulos-modal .btn-primary:hover {
            background: var(--modulos-primary-dark);
            border-color: var(--modulos-primary-dark);
        }

        .vermodulos-modal .btn-secondary {
            background: var(--modulos-surface);
            border: 1px solid var(--modulos-border);
            color: var(--modulos-text-muted);
        }

        .vermodulos-modal .btn-secondary:hover {
            background: var(--modulos-border);
            color: var(--modulos-text);
        }

        .vermodulos-modal .section-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--modulos-text);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--modulos-border);
        }

        .vermodulos-modal .section-title i {
            color: var(--modulos-primary);
        }

        .vermodulos-modal .detail-row {
            display: flex;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid var(--modulos-surface);
        }

        .vermodulos-modal .detail-row:last-child {
            border-bottom: none;
        }

        .vermodulos-modal .detail-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--modulos-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 100px;
            flex-shrink: 0;
        }

        .vermodulos-modal .detail-value {
            font-size: 0.9rem;
            color: var(--modulos-text);
            flex: 1;
        }

        .vermodulos-modal .estado-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        @media (max-width: 767.98px) {
            .vermodulos-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .vermodulos-header .header-info {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endpush
@section('admin')
    <div class="vermodulos-page">
        {{-- Header --}}
        <div class="vermodulos-header">
            <div>
                <ol class="breadcrumb mb-2" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.ofertas.listar') }}">Ofertas</a></li>
                    <li class="breadcrumb-item active">Módulos</li>
                </ol>
                <h1><i class="ri-calendar-check-line me-2"></i>Módulos y Horarios</h1>
                <div class="header-info mt-2">
                    <div class="info-item">
                        <i class="ri-map-pin-line"></i>
                        <span>{{ $oferta->sucursal->sede->nombre ?? '' }} — {{ $oferta->sucursal->nombre ?? '' }}</span>
                    </div>
                    <div class="info-item">
                        <i class="ri-book-2-line"></i>
                        <span>{{ $oferta->programa->nombre ?? '' }}</span>
                    </div>
                    <span class="badge badge-oferta rounded-pill">
                        {{ $oferta->codigo }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.ofertas.dashboard', $oferta->id) }}" class="btn-dashboard">
                <i class="ri-dashboard-line me-1"></i>Dashboard
            </a>
        </div>

        <div class="row g-4">
            {{-- Panel de módulos --}}
            <div class="col-lg-4 col-md-5">
                <div class="vermodulos-card h-100">
                    <div class="vermodulos-card-header">
                        <h6>
                            <i class="ri-stack-line"></i>Módulos
                        </h6>
                        <span class="badge badge-count rounded-pill">
                            {{ $oferta->modulos->count() }}
                        </span>
                    </div>
                    <div class="card-body p-3 modulo-panel" id="external-events">
                        @forelse ($oferta->modulos as $i => $modulo)
                            @php
                                $isLight = in_array(strtolower($modulo->color), [
                                    '#ffffff',
                                    '#fff',
                                    '#f8f9fa',
                                    '#ffffffff',
                                ]);
                                $textColor = $isLight ? '#212529' : '#ffffff';
                            @endphp
                            <div class="modulo-item mb-3 px-3 py-3 external-event" data-modulo-id="{{ $modulo->id }}"
                                style="background:{{ $modulo->color }};color:{{ $textColor }};">
                                <div class="d-flex align-items-start gap-3">
                                    <span class="modulo-number">{{ $i + 1 }}</span>
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="modulo-name text-truncate" title="{{ $modulo->nombre }}">
                                            {{ $modulo->nombre }}</div>
                                        <div
                                            class="modulo-docente mt-1 @empty(!$modulo->docente) empty @endempty">
                                            @if ($modulo->docente)
                                                <i class="ri-user-check-line me-1 text-success"></i>
                                                <span
                                                    class="text-success">{{ optional($modulo->docente->persona)->nombres }}
                                                    {{ optional($modulo->docente->persona)->apellido_paterno }}</span>
                                            @else
                                                <i class="ri-user-add-line me-1"></i>Sin docente
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modulo-actions flex-shrink-0">
                                        <button type="button" class="modulo-action-btn asignar-horarios"
                                            title="Asignar Horarios" data-modulo-id="{{ $modulo->id }}"
                                            data-sesiones="{{ $oferta->cantidad_sesiones }}">
                                            <i class="ri-calendar-2-line"></i>
                                        </button>
                                        <button type="button" class="modulo-action-btn asignar-docente"
                                            title="Asignar Docente" data-modulo-id="{{ $modulo->id }}"
                                            data-docente-id="{{ $modulo->docente_id }}">
                                            <i class="ri-user-follow-line"></i>
                                        </button>
                                        <button type="button" class="modulo-action-btn cambiar-color-modulo"
                                            title="Cambiar Color" data-modulo-id="{{ $modulo->id }}"
                                            data-color="{{ $modulo->color }}">
                                            <i class="ri-palette-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-modules">
                                <i class="ri-inbox-line"></i>
                                <p>Sin módulos registrados</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Calendario --}}
            <div class="col-lg-8 col-md-7">
                <div class="vermodulos-card">
                    <div class="vermodulos-card-header">
                        <h6>
                            <i class="ri-calendar-line"></i>Calendario de Sesiones
                            <span id="filtroLabel" class="filtro-label rounded-pill ms-2" style="display: none;"></span>
                        </h6>
                        <button id="btnVerTodos" class="btn-ver-todos" style="display:none;"
                            title="Mostrar todos los módulos">
                            <i class="ri-layout-grid-line"></i>Ver todos
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Asignar Horarios -->
    <div class="modal fade vermodulos-modal" id="modalAsignarHorarios" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--modulos-primary);">
                    <h5 class="text-white">
                        <i class="ri-calendar-2-line modal-title-icon"></i>
                        Asignar Horarios
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formAsignarHorarios">
                        @csrf
                        <input type="hidden" id="modulo_id" name="modulo_id">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="ri-user-star-line"></i>Responsable
                                </label>
                                <select name="trabajadores_cargo_id" class="form-select">
                                    <option value="">Seleccionar responsable...</option>
                                    @foreach ($trabajadoresCargos as $tc)
                                        <option value="{{ $tc->id }}">
                                            {{ optional($tc->trabajador->persona)->nombres ?? 'Sin nombre' }}
                                            {{ optional($tc->trabajador->persona)->apellido_paterno ?? '' }}
                                            — {{ $tc->cargo->nombre ?? 'Sin cargo' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="ri-mail-line"></i>Cuenta de Notificación
                                </label>
                                <select name="sucursales_cuenta_id" class="form-select">
                                    <option value="">Seleccionar cuenta...</option>
                                    @foreach ($sucursalesCuentas as $sc)
                                        <option value="{{ $sc->id }}">{{ $sc->cuenta->correo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="section-title mt-4">
                            <i class="ri-time-line"></i>Sesiones programadas
                        </div>
                        <div id="horarios-container" class="mb-4"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="ri-close-line"></i>Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line"></i>Guardar Horarios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Asignar Docente -->
    <div class="modal fade vermodulos-modal" id="modalAsignarDocente" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--modulos-success);">
                    <h5 class="text-white">
                        <i class="ri-user-follow-line modal-title-icon"></i>
                        Asignar Docente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modulo_id_docente">
                    <input type="hidden" id="docente_seleccionado_id">
                    <input type="hidden" id="persona_id_no_docente">

                    <!-- Paso 1: Buscar por carnet -->
                    <div id="paso-buscar-docente" class="p-3">
                        <div class="section-title">
                            <i class="ri-search-line"></i>Buscar docente por carnet
                        </div>
                        <label class="form-label">
                            <i class="ri-id-card-line"></i>Número de carnet *
                        </label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="ri-id-card-line"></i></span>
                            <input type="text" id="carnet_docente" class="form-control" placeholder="Ej: 1234567">
                        </div>
                        <div id="mensaje-verificacion-docente" class="mb-3"></div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                id="btn-registrar-nueva-persona-docente" disabled>
                                <i class="ri-user-add-line"></i>Registrar nueva persona
                            </button>
                        </div>
                    </div>

                    <!-- Paso 2: Confirmar asignación (existe y es docente) -->
                    <form id="formAsignarDocenteExistente" style="display:none;">
                        <div class="alert alert-success d-flex align-items-center gap-2">
                            <i class="ri-checkbox-circle-line fs-5"></i>
                            <span>¿Asignar a <strong id="nombre_docente_existente"></strong> como docente de este
                                módulo?</span>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary" id="btn-volver-buscar-docente">
                                <i class="ri-arrow-left-line"></i>Volver
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="ri-user-follow-line"></i>Asignar Docente
                            </button>
                        </div>
                    </form>

                    <!-- Paso 3: Registrar como docente (existe pero no es docente) -->
                    <form id="formRegistrarComoDocente" style="display:none;">
                        <div class="alert alert-warning d-flex align-items-center gap-2">
                            <i class="ri-information-line fs-5"></i>
                            <span>¿Registrar a <strong id="nombre_persona_no_docente"></strong> como docente y
                                asignarlo?</span>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary" id="btn-volver-buscar-docente2">
                                <i class="ri-arrow-left-line"></i>Volver
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-user-add-line"></i>Registrar como Docente
                            </button>
                        </div>
                    </form>

                    <!-- Paso 4: Registrar nueva persona + docente -->
                    <form id="formNuevaPersonaDocente" class="p-3" style="display:none;">
                        @csrf
                        <div class="section-title">
                            <i class="ri-user-add-line"></i>Registrar nueva persona como docente
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Carnet *</label>
                                <input type="text" name="carnet" class="form-control" id="carnet_nuevo_docente"
                                    readonly>
                                <div id="feedback_carnet_docente" class="text-success mt-1 small"></div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Expedido</label>
                                <select name="expedido" class="form-select">
                                    <option value="">Seleccionar</option>
                                    @foreach (['Lp', 'Or', 'Pt', 'Cb', 'Ch', 'Tj', 'Be', 'Sc', 'Pn'] as $e)
                                        <option value="{{ $e }}">{{ $e }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Sexo *</label>
                                <select name="sexo" class="form-select" required>
                                    <option value="">Seleccionar</option>
                                    <option value="Hombre">Hombre</option>
                                    <option value="Mujer">Mujer</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Estado Civil *</label>
                                <select name="estado_civil" class="form-select" required>
                                    <option value="">Seleccionar</option>
                                    <option value="Soltero(a)">Soltero(a)</option>
                                    <option value="Casado(a)">Casado(a)</option>
                                    <option value="Divorciado(a)">Divorciado(a)</option>
                                    <option value="Viudo(a)">Viudo(a)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nombres *</label>
                                <input type="text" name="nombres" class="form-control" id="nombres_nuevo_docente"
                                    placeholder="Nombres completos">
                                <div id="feedback_nombres_docente" class="text-danger mt-1 small"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Apellido Paterno</label>
                                <input type="text" name="apellido_paterno" class="form-control"
                                    id="paterno_nuevo_docente" placeholder="Apellido paterno">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Apellido Materno</label>
                                <input type="text" name="apellido_materno" class="form-control"
                                    id="materno_nuevo_docente" placeholder="Apellido materno">
                                <div id="feedback_apellidos_docente" class="text-danger mt-1 small"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Correo *</label>
                                <input type="email" name="correo" class="form-control" id="correo_nuevo_docente"
                                    placeholder="correo@ejemplo.com">
                                <div id="feedback_correo_docente" class="text-success mt-1 small"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" class="form-control"
                                    id="fecha_nac_nuevo_docente">
                                <div id="edad_calculada_nuevo_docente" class="mt-1 small text-muted"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Celular *</label>
                                <input type="text" name="celular" class="form-control" id="celular_nuevo_docente"
                                    placeholder="67890123">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="telefono" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Departamento *</label>
                                <select name="departamento_id" class="form-select" id="depto_docente" required>
                                    <option value="">Seleccionar</option>
                                    @foreach ($departamentos as $d)
                                        <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Ciudad *</label>
                                <select name="ciudade_id" class="form-select" id="ciudad_docente" required disabled>
                                    <option value="">Primero seleccione depto</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Dirección</label>
                                <textarea name="direccion" class="form-control"></textarea>
                            </div>
                            <!-- Estudios Académicos -->
                            <div class="col-12 mt-4">
                                <div class="section-title">
                                    <i class="ri-graduation-cap-line"></i>Estudios Académicos (opcional)
                                </div>
                                <div id="estudios-container-docente">
                                    <div class="estudio-item-docente row g-2 align-items-end mb-2">
                                        <div class="col-md-3">
                                            <label class="form-label small">Grado</label>
                                            <select class="form-select form-select-sm grado-select-docente"
                                                name="estudios[0][grado]">
                                                <option value="">Seleccionar</option>
                                                @foreach ($grados as $g)
                                                    <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Profesión</label>
                                            <select class="form-select form-select-sm profesion-select-docente"
                                                name="estudios[0][profesion]" disabled>
                                                <option value="">Seleccionar grado primero</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Universidad</label>
                                            <select class="form-select form-select-sm universidad-select-docente"
                                                name="estudios[0][universidad]" disabled>
                                                <option value="">Seleccionar</option>
                                                @foreach ($universidades as $u)
                                                    <option value="{{ $u->id }}">{{ $u->nombre }}
                                                        ({{ $u->sigla }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button"
                                                class="btn btn-outline-success btn-sm add-estudio-docente"
                                                title="Agregar estudio">
                                                <i class="ri-add-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-between pt-3 border-top">
                                    <button type="button" class="btn btn-secondary" id="btn-volver-buscar-docente3">
                                        <i class="ri-arrow-left-line"></i>Volver
                                    </button>
                                    <button type="submit" class="btn btn-success" id="btn-guardar-nueva-persona-docente"
                                        disabled>
                                        <i class="ri-user-add-line"></i>Registrar como Docente
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detalle de Horario -->
    <div class="modal fade vermodulos-modal" id="modalDetalleHorario" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: #6f42c1;">
                    <h5 class="text-white">
                        <i class="ri-calendar-event-line modal-title-icon"></i>
                        Detalles de la Sesión
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-book-2-line"></i> Módulo</div>
                                <div class="detail-value fw-semibold" id="detalle-modulo"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-graduation-cap-line"></i> Docente</div>
                                <div class="detail-value" id="detalle-docente">—</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-user-line"></i> Responsable</div>
                                <div class="detail-value" id="detalle-responsable"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-briefcase-line"></i> Cargo</div>
                                <div class="detail-value" id="detalle-cargo"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-calendar-line"></i> Estado</div>
                                <div class="detail-value"><span id="detalle-estado" class="estado-badge"></span></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-calendar-check-line"></i> Fecha</div>
                                <div class="detail-value" id="detalle-fecha"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-time-line"></i> Hora</div>
                                <div class="detail-value" id="detalle-hora"></div>
                            </div>
                        </div>
                    </div>
                    {{-- Panel: editar estado --}}
                    <div id="editar-estado-form" class="mt-4 p-3 rounded"
                        style="display:none; background: var(--modulos-surface);">
                        <p class="section-title"><i class="ri-edit-line"></i>Cambiar Estado</p>
                        <div class="row g-2 align-items-end">
                            <div class="col-8">
                                <select id="nuevo-estado-select" class="form-select">
                                    <option value="Confirmado">Confirmado</option>
                                    <option value="Desarrollado">Desarrollado</option>
                                    <option value="Postergado">Postergado</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <button id="guardar-estado-btn" class="btn btn-warning w-100"><i
                                        class="ri-save-line"></i>Guardar</button>
                            </div>
                        </div>
                        <button id="cancelar-estado-btn" class="btn btn-secondary btn-sm mt-2">Cancelar</button>
                    </div>

                    {{-- Panel: editar horario y responsable --}}
                    <div id="editar-horario-form" class="mt-3 p-3 rounded"
                        style="display:none; background: var(--modulos-surface);">
                        <p class="section-title"><i class="ri-pencil-line"></i>Editar Horario y Responsable</p>
                        <div class="row g-2 mb-2">
                            <div class="col-md-4">
                                <label class="form-label">Fecha</label>
                                <input type="date" id="edit-horario-fecha" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Hora Inicio</label>
                                <input type="time" id="edit-horario-inicio" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Hora Fin</label>
                                <input type="time" id="edit-horario-fin" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Responsable</label>
                                <select id="edit-horario-responsable" class="form-select">
                                    <option value="">Sin responsable</option>
                                    @foreach ($trabajadoresCargos as $tc)
                                        <option value="{{ $tc->id }}">
                                            {{ optional($tc->trabajador->persona)->nombres ?? '' }}
                                            {{ optional($tc->trabajador->persona)->apellido_paterno ?? '' }}
                                            — {{ $tc->cargo->nombre ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cuenta de Notificación</label>
                                <select id="edit-horario-cuenta" class="form-select">
                                    <option value="">Sin cuenta</option>
                                    @foreach ($sucursalesCuentas as $sc)
                                        <option value="{{ $sc->id }}">{{ $sc->cuenta->correo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button id="guardar-horario-btn" class="btn btn-primary"><i
                                    class="ri-save-line"></i>Guardar</button>
                            <button id="cancelar-horario-btn" class="btn btn-secondary">Cancelar</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" id="btn-editar-horario">
                        <i class="ri-pencil-line"></i>Editar Horario
                    </button>
                    <button type="button" class="btn btn-outline-warning" id="btn-editar-estado">
                        <i class="ri-edit-line"></i>Estado
                    </button>
                    <button type="button" class="btn btn-outline-info d-none" id="btn-reprogramar-sesion">
                        <i class="ri-calendar-2-line"></i>Reprogramar
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cambiar Color de Módulo -->
    <div class="modal fade vermodulos-modal" id="modalColorModulo" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--modulos-accent);">
                    <h5 class="text-white">
                        <i class="ri-palette-line modal-title-icon"></i>
                        Color del Módulo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="text-muted mb-3">Seleccione el color de identificación para este
                        módulo en el calendario</p>
                    <input type="color" id="color-picker" class="form-control form-control-color mx-auto mb-3"
                        value="#cccccc" title="Elige un color" style="width:80px;height:50px;cursor:pointer;">
                    <div id="color-preview"
                        class="rounded mx-auto d-flex align-items-center justify-content-center fw-semibold"
                        style="width:120px;height:40px;border:2px solid #dee2e6;font-size:.8rem;color:#fff;">
                        Vista previa
                    </div>
                    <input type="hidden" id="modulo-id-color">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" id="guardar-color-btn">
                        <i class="ri-save-line"></i>Guardar Color
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer"></div>

    <!-- Modal Reprogramar Sesión -->
    <div class="modal fade vermodulos-modal" id="modalReprogramarSesion" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #0dcaf0;">
                    <h5 class="text-white">
                        <i class="ri-calendar-2-line modal-title-icon"></i>
                        Reprogramar Sesión
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-light d-flex align-items-center gap-2 mb-3">
                        <i class="ri-calendar-event-line fs-5 text-warning"></i>
                        <div>
                            <small class="text-muted d-block"
                                style="font-size:.7rem;text-transform:uppercase;font-weight:600;">Sesión original</small>
                            <span class="fw-semibold" id="reprogramar-fecha-original"></span>
                        </div>
                    </div>
                    <input type="hidden" id="horario-original-id">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ri-calendar-line"></i>Nueva Fecha *
                        </label>
                        <input type="date" id="reprogramar-fecha" class="form-control" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold" style="font-size:.83rem;">
                                <i class="ri-time-line me-1 text-success"></i>Hora Inicio *
                            </label>
                            <input type="time" id="reprogramar-hora-inicio" class="form-control form-control-sm"
                                value="19:00" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold" style="font-size:.83rem;">
                                <i class="ri-time-line me-1 text-danger"></i>Hora Fin *
                            </label>
                            <input type="time" id="reprogramar-hora-fin" class="form-control form-control-sm"
                                value="22:00" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-2 justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-sm" id="guardar-reprogramacion-btn">
                        <i class="ri-calendar-check-line me-1"></i>Reprogramar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('backend/assets/libs/fullcalendar/index.global.min.js') }}"></script>
    <script>
        // Mapa para almacenar instancias de modales Bootstrap
        const bootstrapModals = {};

        // Función para cerrar modal usando Bootstrap API
        function hideModalManual(modalId) {
            const modalEl = document.getElementById(modalId);
            if (bootstrapModals[modalId]) {
                bootstrapModals[modalId].hide();
                delete bootstrapModals[modalId];
            }
            if (modalEl) {
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.setAttribute('aria-hidden', 'true');
            }
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
            document.body.style.position = '';
        }

        // Función para mostrar modal usando Bootstrap API
        function showModalManual(modalId) {
            const modalEl = document.getElementById(modalId);
            if (!modalEl) return;

            // Cerrar cualquier instancia previa
            if (bootstrapModals[modalId]) {
                bootstrapModals[modalId].hide();
                delete bootstrapModals[modalId];
            }

            // Limpiar estado previo
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
            // Forzar reflow
            void modalEl.offsetHeight;

            // Crear nueva instancia
            bootstrapModals[modalId] = new bootstrap.Modal(modalEl, {
                backdrop: 'static',
                keyboard: true,
                focus: true
            });

            bootstrapModals[modalId].show();

            // Adjuntar listeners de cierre al modal
            modalEl.addEventListener('hidden.bs.modal', function() {
                delete bootstrapModals[modalId];
            }, {
                once: true
            });

            // Adjuntar click al botón X del modal específico
            const closeBtn = modalEl.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.onclick = function(e) {
                    e.preventDefault();
                    hideModalManual(modalId);
                };
            }
        }

        // ── Toast ──────────────────────────────────────────────────────────
        function showToast(message, type = 'success') {
            const icons = {
                success: 'ri-checkbox-circle-line',
                danger: 'ri-error-warning-line',
                warning: 'ri-alert-line',
                info: 'ri-information-line'
            };
            const container = document.getElementById('toastContainer');
            if (!container) return;
            // Limpiar toasts anteriores del container
            container.innerHTML = '';
            const toast = document.createElement('div');
            toast.className = `app-toast ${type}`;
            toast.innerHTML = `
                <i class="${icons[type] || icons.info} app-toast-icon"></i>
                <span class="app-toast-body">${message}</span>
                <button class="app-toast-close" onclick="this.closest('.app-toast').remove()">×</button>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('hiding');
                setTimeout(() => toast.remove(), 320);
            }, 4000);
        }
        // ───────────────────────────────────────────────────────────────────

        let calendar;
        let eventoActivo = null;
        let todosLosEventos = [];
        let moduloFiltroActivo = null;

        function filtrarCalendario(moduloId, nombreModulo) {
            moduloFiltroActivo = moduloId;
            calendar.removeAllEvents();
            todosLosEventos
                .filter(e => e.extendedProps.modulo_id == moduloId)
                .forEach(e => calendar.addEvent(e));
            document.getElementById('filtroLabel').textContent = nombreModulo;
            document.getElementById('filtroLabel').style.display = '';
            document.getElementById('btnVerTodos').style.display = '';
            document.querySelectorAll('.modulo-item').forEach(el => {
                el.classList.toggle('activo', el.dataset.moduloId == moduloId);
            });
        }

        function mostrarTodosLosEventos() {
            moduloFiltroActivo = null;
            calendar.removeAllEvents();
            todosLosEventos.forEach(e => calendar.addEvent(e));
            document.getElementById('filtroLabel').style.display = 'none';
            document.getElementById('btnVerTodos').style.display = 'none';
            document.querySelectorAll('.modulo-item').forEach(el => el.classList.remove('activo'));
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Limpiar cualquier residuo de modales al cargar
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.position = '';
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
            document.querySelectorAll('.modal.show').forEach(m => {
                m.classList.remove('show');
                m.style.display = 'none';
            });

            var calendarEl = document.getElementById("calendar");
            var eventos = [];
            @foreach ($oferta->modulos as $modulo)
                @foreach ($modulo->horarios as $horario)
                    @php
                        $fechaStr = \Carbon\Carbon::parse($horario->fecha)->format('Y-m-d');
                        $horaIni = substr($horario->hora_inicio, 0, 5);
                        $horaFin = substr($horario->hora_fin, 0, 5);
                        $start = $fechaStr . 'T' . $horaIni;
                        $end = $fechaStr . 'T' . $horaFin;
                        $responsable = optional($horario->trabajador_cargo)->trabajador?->persona?->nombres . ' ' . optional($horario->trabajador_cargo)->trabajador?->persona?->apellido_paterno ?? 'Sin responsable';
                        $cargo = optional($horario->trabajador_cargo)->cargo?->nombre ?? '';
                        $estado = $horario->estado ?? 'Confirmado';
                        $title = $modulo->nombre;
                    @endphp
                    eventos.push({
                        title: "{{ addslashes($title) }}",
                        start: "{{ $start }}",
                        end: "{{ $end }}",
                        className: 'text-with',
                        extendedProps: {
                            modulo_id: {{ $modulo->id }},
                            horario_id: {{ $horario->id }},
                            responsable: "{{ addslashes($responsable) }}",
                            cargo: "{{ addslashes($cargo) }}",
                            estado: "{{ $estado }}",
                            color_modulo: "{{ $modulo->color }}",
                            trabajadores_cargo_id: {{ $horario->trabajadores_cargo_id ?? 'null' }},
                            sucursales_cuenta_id: {{ $horario->sucursales_cuenta_id ?? 'null' }},
                            docente: "{{ addslashes(optional($modulo->docente?->persona)->nombres . ' ' . optional($modulo->docente?->persona)->apellido_paterno) }}"
                        }
                    });
                @endforeach
            @endforeach

            calendar = new FullCalendar.Calendar(calendarEl, {
                locale: "es",
                initialView: "dayGridMonth",
                headerToolbar: {
                    left: "prev,next today",
                    center: "title",
                    right: "",
                },
                buttonText: {
                    today: "Hoy",
                },
                editable: false,
                droppable: false,
                events: eventos,
                eventDidMount: function(info) {
                    if (moduloFiltroActivo && info.event.extendedProps.modulo_id !=
                        moduloFiltroActivo) {
                        info.el.style.display = 'none';
                    }
                    info.el.style.cursor = 'pointer';
                    if (info.event.extendedProps.color_modulo) {
                        const colorFondo = info.event.extendedProps.color_modulo;
                        info.el.style.backgroundColor = colorFondo;
                        info.el.style.borderColor = colorFondo;
                    }
                    const estado = info.event.extendedProps.estado;
                    let colorTexto = '#000000';
                    if (estado === 'Confirmado') colorTexto = '#16A3DB';
                    else if (estado === 'Desarrollado') colorTexto = '#3804D9';
                    else if (estado === 'Postergado') colorTexto = '#D91404';
                    const titleEl = info.el.querySelector('.fc-event-title') || info.el.querySelector(
                        'span') || info.el;
                    if (titleEl) titleEl.style.color = colorTexto;
                },
                eventClick: function(info) {
                    const props = info.event.extendedProps;
                    const colorModulo = props.color_modulo || '#6f42c1';
                    const modalEl = document.getElementById('modalDetalleHorario');
                    const modalHeader = modalEl.querySelector('.modal-header');
                    modalHeader.style.background = colorModulo;
                    const start = new Date(info.event.start);
                    const end = new Date(info.event.end || info.event.start);
                    const fechaStr = start.toLocaleDateString('es-ES', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                    const horaStr =
                        `${info.event.start.toTimeString().slice(0,5)} - ${info.event.end?.toTimeString().slice(0,5) || '??'}`;
                    document.getElementById('detalle-modulo').textContent = info.event.title.split(
                        ' - ')[0] || 'Sin módulo';
                    document.getElementById('detalle-docente').textContent = props.docente || '—';
                    document.getElementById('detalle-responsable').textContent = props.responsable ||
                        '—';
                    document.getElementById('detalle-cargo').textContent = props.cargo || '—';
                    document.getElementById('detalle-fecha').textContent = fechaStr;
                    document.getElementById('detalle-hora').textContent = horaStr;
                    const estadoEl = document.getElementById('detalle-estado');
                    estadoEl.textContent = props.estado || '—';
                    estadoEl.className = 'fw-bold';
                    estadoEl.classList.remove('text-success', 'text-secondary', 'text-warning');
                    if (props.estado === 'Confirmado') estadoEl.classList.add('text-success');
                    else if (props.estado === 'Desarrollado') estadoEl.classList.add('text-secondary');
                    else if (props.estado === 'Postergado') estadoEl.classList.add('text-warning');

                    const btnReprogramar = document.getElementById('btn-reprogramar-sesion');
                    if (props.estado === 'Postergado') {
                        btnReprogramar.classList.remove('d-none');
                    } else {
                        btnReprogramar.classList.add('d-none');
                    }
                    eventoActivo = info.event;
                    // Resetear paneles de edición
                    document.getElementById('editar-estado-form').style.display = 'none';
                    document.getElementById('editar-horario-form').style.display = 'none';
                    // Pre-cargar valores en el form de edición de horario
                    document.getElementById('edit-horario-fecha').value = info.event.start ? info.event
                        .start.toISOString().substring(0, 10) : '';
                    document.getElementById('edit-horario-inicio').value = info.event.start ? info.event
                        .start.toTimeString().slice(0, 5) : '';
                    document.getElementById('edit-horario-fin').value = info.event.end ? info.event.end
                        .toTimeString().slice(0, 5) : '';
                    document.getElementById('edit-horario-responsable').value = props
                        .trabajadores_cargo_id || '';
                    document.getElementById('edit-horario-cuenta').value = props.sucursales_cuenta_id ||
                        '';
                    showModalManual('modalDetalleHorario');
                },
            });
            todosLosEventos = [...eventos];
            calendar.render();

            // === FILTRO POR MÓDULO ===
            document.getElementById('btnVerTodos').addEventListener('click', mostrarTodosLosEventos);

            document.querySelectorAll('.modulo-item').forEach(function(item) {
                item.addEventListener('click', function(e) {
                    if (e.target.closest('.modulo-actions')) return;
                    const moduloId = this.dataset.moduloId;
                    const nombreModulo = this.querySelector('.modulo-name')?.textContent?.trim() ||
                        'Módulo';
                    if (moduloFiltroActivo == moduloId) {
                        mostrarTodosLosEventos();
                    } else {
                        filtrarCalendario(moduloId, nombreModulo);
                    }
                });
            });

            // === LISTENERS DE MODAL DETALLE ===
            document.getElementById('btn-editar-horario').addEventListener('click', function() {
                document.getElementById('editar-estado-form').style.display = 'none';
                const form = document.getElementById('editar-horario-form');
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            });
            document.getElementById('cancelar-horario-btn').addEventListener('click', function() {
                document.getElementById('editar-horario-form').style.display = 'none';
            });
            document.getElementById('guardar-horario-btn').addEventListener('click', async function() {
                if (!eventoActivo) return;
                const horarioId = eventoActivo.extendedProps.horario_id;
                const moduloId = eventoActivo.extendedProps.modulo_id;
                const fecha = document.getElementById('edit-horario-fecha').value;
                const horaInicio = document.getElementById('edit-horario-inicio').value;
                const horaFin = document.getElementById('edit-horario-fin').value;
                const respId = document.getElementById('edit-horario-responsable').value;
                const cuentaId = document.getElementById('edit-horario-cuenta').value;
                if (!fecha || !horaInicio || !horaFin) {
                    showToast('Complete fecha y horas.', 'warning');
                    return;
                }
                try {
                    const res = await $.ajax({
                        url: "{{ route('admin.horarios.actualizar') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: horarioId,
                            fecha,
                            hora_inicio: horaInicio,
                            hora_fin: horaFin,
                            trabajadores_cargo_id: respId || null,
                            sucursales_cuenta_id: cuentaId || null,
                        }
                    });
                    if (res.success) {
                        document.getElementById('detalle-docente').textContent = res.docente || '—';
                        document.getElementById('detalle-responsable').textContent = res.responsable ||
                            '—';
                        document.getElementById('detalle-cargo').textContent = res.cargo || '—';
                        const nuevaFechaStr = new Date(fecha + 'T00:00:00').toLocaleDateString(
                        'es-ES', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                        document.getElementById('detalle-fecha').textContent = nuevaFechaStr;
                        document.getElementById('detalle-hora').textContent = horaInicio + ' - ' +
                            horaFin;
                        todosLosEventos = todosLosEventos.filter(e => e.extendedProps.modulo_id !=
                            moduloId);
                        res.eventos.forEach(e => todosLosEventos.push(e));
                        calendar.getEvents().forEach(ev => {
                            if (ev.extendedProps.modulo_id == moduloId) ev.remove();
                        });
                        if (!moduloFiltroActivo || moduloFiltroActivo == moduloId) {
                            res.eventos.forEach(e => calendar.addEvent(e));
                        }
                        const eventoActualizado = res.eventos.find(e => e.extendedProps.horario_id ==
                            horarioId);
                        if (eventoActualizado) {
                            document.getElementById('edit-horario-responsable').value =
                                eventoActualizado.extendedProps.trabajadores_cargo_id || '';
                            document.getElementById('edit-horario-cuenta').value = eventoActualizado
                                .extendedProps.sucursales_cuenta_id || '';
                        }
                        document.getElementById('editar-horario-form').style.display = 'none';
                        showToast(res.msg || 'Horario actualizado.');
                    }
                } catch (err) {
                    showToast('Error al actualizar el horario.', 'danger');
                }
            });

            document.getElementById('btn-editar-estado').addEventListener('click', function() {
                document.getElementById('editar-estado-form').style.display = 'block';
                document.getElementById('editar-horario-form').style.display = 'none';
                document.getElementById('nuevo-estado-select').value = document.getElementById(
                    'detalle-estado').textContent.trim();
                const estadoActual = document.getElementById('detalle-estado').textContent.trim();
                const btnReprogramar = document.getElementById('btn-reprogramar-sesion');
                if (estadoActual === 'Postergado') {
                    btnReprogramar.classList.remove('d-none');
                } else {
                    btnReprogramar.classList.add('d-none');
                }
            });
            document.getElementById('cancelar-estado-btn').addEventListener('click', function() {
                document.getElementById('editar-estado-form').style.display = 'none';
            });
            document.getElementById('guardar-estado-btn').addEventListener('click', async function() {
                if (!eventoActivo) return;
                const nuevoEstado = document.getElementById('nuevo-estado-select').value;
                const horarioId = eventoActivo.extendedProps.horario_id;
                const moduloId = eventoActivo.extendedProps.modulo_id;
                try {
                    await $.ajax({
                        url: "{{ route('admin.horarios.actualizar-estado') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: horarioId,
                            estado: nuevoEstado
                        }
                    });
                    const nuevosEventos = await $.get(
                        "{{ route('admin.modulos.obtener-eventos', ':id') }}".replace(':id',
                            moduloId));
                    calendar.getEvents().forEach(event => {
                        if (event.extendedProps.modulo_id == moduloId) event.remove();
                    });
                    nuevosEventos.forEach(eventData => calendar.addEvent(eventData));
                    const estadoEl = document.getElementById('detalle-estado');
                    estadoEl.textContent = nuevoEstado;
                    estadoEl.className = 'fw-bold';
                    estadoEl.classList.remove('text-success', 'text-secondary', 'text-warning');
                    if (nuevoEstado === 'Confirmado') estadoEl.classList.add('text-success');
                    else if (nuevoEstado === 'Desarrollado') estadoEl.classList.add('text-secondary');
                    else if (nuevoEstado === 'Postergado') estadoEl.classList.add('text-warning');

                    const btnReprogramar = document.getElementById('btn-reprogramar-sesion');
                    if (nuevoEstado === 'Postergado') {
                        btnReprogramar.classList.remove('d-none');
                    } else {
                        btnReprogramar.classList.add('d-none');
                    }
                    document.getElementById('editar-estado-form').style.display = 'none';
                    showToast('Estado actualizado correctamente.');
                } catch (err) {
                    showToast('Error al actualizar el estado.', 'danger');
                    console.error(err);
                }
            });

            document.getElementById('btn-reprogramar-sesion').addEventListener('click', function() {
                if (!eventoActivo) return;
                const props = eventoActivo.extendedProps;
                const start = new Date(eventoActivo.start);
                const fechaOriginalStr = start.toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
                document.getElementById('reprogramar-fecha-original').textContent =
                    `${fechaOriginalStr} (${props.hora_inicio} - ${props.hora_fin})`;
                document.getElementById('horario-original-id').value = props.horario_id;
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('reprogramar-fecha').valueAsDate = tomorrow;
                showModalManual('modalReprogramarSesion');
            });

            document.getElementById('guardar-reprogramacion-btn').addEventListener('click', async function() {
                if (!eventoActivo) return;
                const horarioOriginalId = document.getElementById('horario-original-id').value;
                const nuevaFecha = document.getElementById('reprogramar-fecha').value;
                const nuevaHoraInicio = document.getElementById('reprogramar-hora-inicio').value;
                const nuevaHoraFin = document.getElementById('reprogramar-hora-fin').value;
                const moduloId = eventoActivo.extendedProps.modulo_id;
                if (!horarioOriginalId || !nuevaFecha || !nuevaHoraInicio || !nuevaHoraFin) {
                    showToast('Por favor, complete todos los campos.', 'warning');
                    return;
                }
                try {
                    const res = await $.ajax({
                        url: "{{ route('admin.horarios.reprogramar') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            horario_original_id: horarioOriginalId,
                            fecha: nuevaFecha,
                            hora_inicio: nuevaHoraInicio,
                            hora_fin: nuevaHoraFin
                        }
                    });
                    if (res.success) {
                        const nuevosEventos = await $.get(
                            "{{ route('admin.modulos.obtener-eventos', ':id') }}".replace(':id',
                                moduloId));
                        calendar.getEvents().forEach(event => {
                            if (event.extendedProps.modulo_id == moduloId) event.remove();
                        });
                        nuevosEventos.forEach(eventData => calendar.addEvent(eventData));
                        hideModalManual('modalReprogramarSesion');
                        hideModalManual('modalDetalleHorario');
                        showToast(res.msg || 'Sesión reprogramada correctamente.');
                    }
                } catch (err) {
                    showToast('Error al reprogramar la sesión.', 'danger');
                    console.error(err);
                }
            });
        });

        // === CARGAR CIUDADES POR DEPARTAMENTO (DOCENTE) ===
        $('#depto_docente').on('change', function() {
            const deptoId = $(this).val();
            const ciudadSelect = $('#ciudad_docente');
            ciudadSelect.empty().prop('disabled', true);
            if (!deptoId) return;
            @foreach ($ciudades as $c)
                if ({{ $c['departamento_id'] }} == deptoId) {
                    ciudadSelect.append(`<option value="{{ $c['id'] }}">{{ $c['nombre'] }}</option>`);
                }
            @endforeach
            ciudadSelect.prop('disabled', false);
        });

        // === ASIGNAR DOCENTE ===
        let moduloIdGlobal = null;
        $(document).on('click', '.asignar-docente', function() {
            const moduloId = $(this).data('modulo-id');
            const docenteId = $(this).data('docente-id');
            moduloIdGlobal = moduloId;
            $('#modulo_id_docente').val(moduloId);

            // Reset completo del modal a estado inicial
            $('#formAsignarDocenteExistente, #formRegistrarComoDocente, #formNuevaPersonaDocente').hide();
            $('#paso-buscar-docente').show();
            $('#carnet_docente').val('');
            $('#mensaje-verificacion-docente').html('');
            $('#btn-registrar-nueva-persona-docente').prop('disabled', true);

            if (docenteId) {
                const nombre = $(this).closest('.external-event').find('.mt-1 .text-truncate').text().trim();
                $('#mensaje-verificacion-docente').html(`
                    <div class="alert alert-info border-0 d-flex align-items-center gap-2 py-2">
                        <i class="ri-user-check-line fs-5 text-info"></i>
                        <div style="font-size:.85rem;">Docente actual: <strong>${nombre || 'Asignado'}</strong></div>
                        <button type="button" class="btn btn-sm btn-warning ms-auto" id="btn-cambiar-docente">
                            <i class="ri-refresh-line me-1"></i>Cambiar
                        </button>
                    </div>
                `);
            }

            showModalManual('modalAsignarDocente');
        });

        $(document).on('click', '#btn-cambiar-docente', function() {
            $('#mensaje-verificacion-docente').html('');
        });

        let debounceDocente;
        $('#carnet_docente').on('input', function() {
            const carnet = $(this).val().trim();
            $('#mensaje-verificacion-docente').html('');
            $('#btn-registrar-nueva-persona-docente').prop('disabled', true);
            if (!carnet) return;

            clearTimeout(debounceDocente);
            debounceDocente = setTimeout(() => {
                $.post("{{ route('admin.docentes.verificar-carnet') }}", {
                    _token: "{{ csrf_token() }}",
                    carnet: carnet
                }).done(function(res) {
                    if (res.is_docente) {
                        $('#mensaje-verificacion-docente').html(
                            `<div class="alert alert-success">${res.message}<br><strong>${res.persona.nombre_completo}</strong></div>`
                        );
                        $('#docente_seleccionado_id').val(res.docente_id);
                        $('#nombre_docente_existente').text(res.persona.nombre_completo);
                        $('#paso-buscar-docente, #formRegistrarComoDocente, #formNuevaPersonaDocente')
                            .hide();
                        $('#formAsignarDocenteExistente').show();
                    } else if (res.exists) {
                        $('#mensaje-verificacion-docente').html(
                            `<div class="alert alert-warning">${res.message}<br><strong>${res.persona.nombre_completo}</strong></div>`
                        );
                        $('#persona_id_no_docente').val(res.persona.id);
                        $('#nombre_persona_no_docente').text(res.persona.nombre_completo);
                        $('#paso-buscar-docente, #formAsignarDocenteExistente, #formNuevaPersonaDocente')
                            .hide();
                        $('#formRegistrarComoDocente').show();
                    } else {
                        $('#mensaje-verificacion-docente').html(
                            `<div class="alert alert-danger">${res.message}</div>`);
                        $('#btn-registrar-nueva-persona-docente').prop('disabled', false);
                        $('#formAsignarDocenteExistente, #formRegistrarComoDocente').hide();
                    }
                }).fail(function() {
                    $('#mensaje-verificacion-docente').html(
                        `<div class="alert alert-danger">Error al verificar el carnet.</div>`);
                });
            }, 400);
        });

        $('#btn-registrar-nueva-persona-docente').on('click', function() {
            const carnetBusqueda = $('#carnet_docente').val().trim();
            $('#carnet_nuevo_docente').val(carnetBusqueda).prop('readonly', true);
            $('#paso-buscar-docente, #formAsignarDocenteExistente, #formRegistrarComoDocente').hide();
            $('#formNuevaPersonaDocente').show();
            $('#nombres_nuevo_docente').focus();
        });

        $('#btn-volver-buscar-docente, #btn-volver-buscar-docente2, #btn-volver-buscar-docente3').on('click', function() {
            $('#formAsignarDocenteExistente, #formRegistrarComoDocente, #formNuevaPersonaDocente').hide();
            $('#paso-buscar-docente').show();
            $('#carnet_docente').val('');
            $('#mensaje-verificacion-docente').html('');
        });

        // === VALIDACIÓN PARA NUEVA PERSONA DOCENTE ===
        function validarApellidosDocente() {
            const p = $('#paterno_nuevo_docente').val().trim();
            const m = $('#materno_nuevo_docente').val().trim();
            if (!p && !m) {
                $('#feedback_apellidos_docente').text('Debe ingresar al menos un apellido.');
                return false;
            } else {
                $('#feedback_apellidos_docente').text('');
                return true;
            }
        }

        function calcularEdadDocente() {
            const fecha = $('#fecha_nac_nuevo_docente').val();
            if (!fecha) {
                $('#edad_calculada_nuevo_docente').text('');
                return true;
            }
            const hoy = new Date();
            const nac = new Date(fecha);
            let edad = hoy.getFullYear() - nac.getFullYear();
            const mes = hoy.getMonth() - nac.getMonth();
            if (mes < 0 || (mes === 0 && hoy.getDate() < nac.getDate())) edad--;
            $('#edad_calculada_nuevo_docente').text(`Edad: ${edad} años`);
            return true;
        }

        function checkFormNuevaPersonaDocente() {
            const carnet = $('#carnet_nuevo_docente').val().trim();
            const nombres = $('#nombres_nuevo_docente').val().trim();
            const celular = $('#celular_nuevo_docente').val().trim();
            const ciudade = $('select[name="ciudade_id"]').val();
            const sexo = $('select[name="sexo"]').val();
            const ecivil = $('select[name="estado_civil"]').val();
            const correo = $('#correo_nuevo_docente').val().trim();
            const apellidosOk = validarApellidosDocente();
            const edadOk = !$('#fecha_nac_nuevo_docente').val() || calcularEdadDocente();
            const enabled = carnet && nombres && celular && ciudade && sexo && ecivil && correo && apellidosOk && edadOk;
            $('#btn-guardar-nueva-persona-docente').prop('disabled', !enabled);
        }

        $('#formNuevaPersonaDocente input, #formNuevaPersonaDocente select').on('input change',
            checkFormNuevaPersonaDocente);
        $('#fecha_nac_nuevo_docente').on('change', checkFormNuevaPersonaDocente);

        $('#carnet_nuevo_docente').on('blur', function() {
            const carnet = $(this).val().trim();
            if (!carnet) return;
            $.post("{{ route('admin.docentes.verificar-carnet') }}", {
                _token: "{{ csrf_token() }}",
                carnet: carnet
            }).done(function(res) {
                if (!res.exists) {
                    $('#feedback_carnet_docente').removeClass('text-danger').addClass('text-success').text(
                        'Carnet disponible.');
                } else {
                    $('#feedback_carnet_docente').removeClass('text-success').addClass('text-danger').text(
                        'Carnet ya registrado.');
                }
            }).fail(function() {
                $('#feedback_carnet_docente').removeClass('text-success').addClass('text-danger').text(
                    'Error al verificar.');
            });
        });

        $('#correo_nuevo_docente').on('blur', function() {
            const correo = $(this).val().trim();
            if (!correo) return;
            $.post("{{ route('admin.personas.verificar-correo') }}", {
                _token: "{{ csrf_token() }}",
                correo: correo
            }).done(function(res) {
                if (!res.exists) {
                    $('#feedback_correo_docente').removeClass('text-danger').addClass('text-success').text(
                        'Correo disponible.');
                } else {
                    $('#feedback_correo_docente').removeClass('text-success').addClass('text-danger').text(
                        'Correo ya registrado.');
                }
            }).fail(function() {
                $('#feedback_correo_docente').removeClass('text-success').addClass('text-danger').text(
                    'Error al verificar.');
            });
        });

        // === DINÁMICA DE ESTUDIOS ===
        $(document).on('change', '.grado-select-docente', function() {
            const row = $(this).closest('.estudio-item-docente');
            const gradoId = $(this).val();
            if (!gradoId) {
                row.find('.profesion-select-docente, .universidad-select-docente').prop('disabled', true)
                    .html('<option value="">Profesión</option>');
                row.find('.universidad-select-docente').html('<option value="">Universidad</option>');
                return;
            }
            let htmlProf = '<option value="">Profesión</option>';
            @foreach ($profesiones as $p)
                htmlProf += `<option value="{{ $p->id }}">{{ $p->nombre }}</option>`;
            @endforeach
            row.find('.profesion-select-docente').html(htmlProf).prop('disabled', false);
            let htmlUni = '<option value="">Universidad</option>';
            @foreach ($universidades as $u)
                htmlUni +=
                    `<option value="{{ $u->id }}">{{ $u->nombre }} ({{ $u->sigla }})</option>`;
            @endforeach
            row.find('.universidad-select-docente').html(htmlUni).prop('disabled', false);
        });

        $(document).on('click', '.add-estudio-docente', function() {
            const index = $('.estudio-item-docente').length;
            let html = `
                <div class="estudio-item-docente row g-2 align-items-end mb-2">
                    <div class="col-md-3">
                        <label class="form-label small">Grado</label>
                        <select class="form-select form-select-sm grado-select-docente" name="estudios[${index}][grado]">
                            <option value="">Seleccionar</option>
                            @foreach ($grados as $g)
                                <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Profesión</label>
                        <select class="form-select form-select-sm profesion-select-docente" name="estudios[${index}][profesion]" disabled>
                            <option value="">Seleccionar grado primero</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select universidad-select-docente" name="estudios[${index}][universidad]" disabled>
                            <option value="">Universidad</option>
                            @foreach ($universidades as $u)
                                <option value="{{ $u->id }}">{{ $u->nombre }} ({{ $u->sigla }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-estudio-docente">−</button>
                    </div>
                </div>`;
            $('#estudios-container-docente').append(html);
        });

        $(document).on('click', '.remove-estudio-docente', function() {
            $(this).closest('.estudio-item-docente').remove();
        });

        // === VALIDACIÓN Y ENVÍO DEL FORMULARIO NUEVA PERSONA DOCENTE ===
        $('#formNuevaPersonaDocente').submit(function(e) {
            e.preventDefault();
            const carnet = $('#carnet_nuevo_docente').val();
            if (!carnet) {
                showToast('Ingrese el número de carnet.', 'warning');
                return;
            }
            if (!validarApellidosDocente()) return;
            if ($('#fecha_nac_nuevo_docente').val() && !calcularEdadDocente()) return;

            let estudiosValidos = true;
            $('.estudio-item-docente').each(function() {
                const g = $(this).find('.grado-select-docente').val();
                const p = $(this).find('.profesion-select-docente').val();
                const u = $(this).find('.universidad-select-docente').val();
                if (g || p || u) {
                    if (!g || !p || !u) {
                        estudiosValidos = false;
                        return false;
                    }
                }
            });
            if (!estudiosValidos) {
                showToast('Si agrega estudios, debe completar Grado, Profesión y Universidad.', 'warning');
                return;
            }

            $.post("{{ route('admin.docentes.registrar-persona-y-docente') }}", $(this).serialize())
                .done(function(res) {
                    if (res.success) {
                        $.post("{{ route('admin.modulos.asignar-docente') }}", {
                            _token: "{{ csrf_token() }}",
                            modulo_id: moduloIdGlobal,
                            docente_id: res.docente_id
                        }).done(function(asignRes) {
                            if (asignRes.success) {
                                actualizarDocenteEnPanel(moduloIdGlobal, asignRes.docente_id, asignRes
                                    .docente_nombre);
                                showToast(res.msg || 'Docente registrado y asignado correctamente.');
                                hideModalManual('modalAsignarDocente');
                            }
                        }).fail(function() {
                            showToast('Error al asignar el docente.', 'danger');
                        });
                    } else {
                        showToast(res.msg || 'Error al registrar.', 'danger');
                    }
                })
                .fail(function(xhr) {
                    const errors = xhr.responseJSON?.errors || {};
                    if (errors.carnet) $('#feedback_carnet_docente').addClass('text-danger').text(errors.carnet[
                        0]);
                    if (errors.correo) $('#feedback_correo_docente').addClass('text-danger').text(errors.correo[
                        0]);
                    if (errors.apellidos) $('#feedback_apellidos_docente').text(errors.apellidos[0]);
                    if (errors.fecha_nacimiento) $('#edad_calculada_nuevo_docente').addClass('text-danger')
                        .text(errors.fecha_nacimiento[0]);
                    checkFormNuevaPersonaDocente();
                });
        });

        $('#formRegistrarComoDocente').submit(function(e) {
            e.preventDefault();
            const personaId = $('#persona_id_no_docente').val();
            $.post("{{ route('admin.docentes.registrar') }}", {
                _token: "{{ csrf_token() }}",
                persona_id: personaId
            }).done(function(res) {
                if (res.success) {
                    $.post("{{ route('admin.modulos.asignar-docente') }}", {
                        _token: "{{ csrf_token() }}",
                        modulo_id: moduloIdGlobal,
                        docente_id: res.docente_id
                    }).done(function(asignRes) {
                        if (asignRes.success) {
                            const nombre = $('#nombre_persona_no_docente').text();
                            actualizarDocenteEnPanel(moduloIdGlobal, res.docente_id, nombre);
                            showToast('Docente registrado y asignado correctamente.');
                            hideModalManual('modalAsignarDocente');
                        }
                    });
                }
            }).fail(function(xhr) {
                showToast(xhr.responseJSON?.msg || 'Error al registrar como docente.', 'danger');
            });
        });

        $('#formAsignarDocenteExistente').submit(function(e) {
            e.preventDefault();
            const docenteId = $('#docente_seleccionado_id').val();
            $.post("{{ route('admin.modulos.asignar-docente') }}", {
                _token: "{{ csrf_token() }}",
                modulo_id: moduloIdGlobal,
                docente_id: docenteId
            }).done(function(res) {
                if (res.success) {
                    const nombre = $('#nombre_docente_existente').text();
                    actualizarDocenteEnPanel(moduloIdGlobal, docenteId, nombre);
                    showToast('Docente asignado correctamente.');
                    hideModalManual('modalAsignarDocente');
                }
            }).fail(function() {
                showToast('Error al asignar docente.', 'danger');
            });
        });

        function actualizarDocenteEnPanel(moduloId, docenteId, nombre) {
            const item = $(`.modulo-item[data-modulo-id="${moduloId}"]`);
            if (item.length) {
                item.find('.modulo-docente')
                    .removeClass('empty')
                    .html(`<i class="ri-user-check-line me-1"></i>${nombre}`);
                item.find('.asignar-docente')
                    .data('docente-id', docenteId)
                    .attr('data-docente-id', docenteId);
            }
        }

        // === ASIGNAR HORARIOS ===
        $(document).on('click', '.asignar-horarios', function() {
            const moduloId = $(this).data('modulo-id');
            const sesionesDefault = $(this).data('sesiones') || 1;
            $('#modulo_id').val(moduloId);
            $('#horarios-container').empty();
            $('#formAsignarHorarios button[type="submit"]').prop('disabled', true).text('Cargando...');
            $.get("{{ route('admin.modulos.obtener-horarios', ':id') }}".replace(':id', moduloId))
                .done(function(data) {
                    $('#formAsignarHorarios select[name="trabajadores_cargo_id"]').val(data
                        .trabajadores_cargo_id || '').trigger('change');
                    $('#formAsignarHorarios select[name="sucursales_cuenta_id"]').val(data
                        .sucursales_cuenta_id || '').trigger('change');
                    let html = `<h6>Sesiones (${data.cantidad_sesiones || sesionesDefault})</h6>`;
                    if (data.horarios.length > 0) {
                        data.horarios.forEach((h, i) => {
                            const fecha = (h.fecha || '').substring(0, 10);
                            const horaIni = (h.hora_inicio || '').substring(0, 5);
                            const horaFin = (h.hora_fin || '').substring(0, 5);
                            html += `
                    <div class="row g-2 align-items-end mb-3">
                        <div class="col-md-5">
                            <label class="form-label">Fecha *</label>
                            <input type="date" name="horarios[${i}][fecha]" class="form-control" value="${fecha}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Hora Inicio *</label>
                            <input type="time" name="horarios[${i}][hora_inicio]" class="form-control" value="${horaIni}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Hora Fin *</label>
                            <input type="time" name="horarios[${i}][hora_fin]" class="form-control" value="${horaFin}" required>
                        </div>
                        <div class="col-md-1">
                            <input type="hidden" name="horarios[${i}][estado]" value="Confirmado">
                        </div>
                    </div>`;
                        });
                    } else {
                        for (let i = 0; i < sesionesDefault; i++) {
                            html += `
                    <div class="row g-2 align-items-end mb-3">
                        <div class="col-md-5">
                            <label class="form-label">Fecha *</label>
                            <input type="date" name="horarios[${i}][fecha]" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Hora Inicio *</label>
                            <input type="time" name="horarios[${i}][hora_inicio]" value="19:00" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Hora Fin *</label>
                            <input type="time" name="horarios[${i}][hora_fin]" value="22:00" class="form-control" required>
                        </div>
                        <div class="col-md-1">
                            <input type="hidden" name="horarios[${i}][estado]" value="Confirmado">
                        </div>
                    </div>`;
                        }
                    }
                    $('#horarios-container').html(html);
                    $('#formAsignarHorarios button[type="submit"]').prop('disabled', false).text(
                        'Guardar Horarios');
                    showModalManual('modalAsignarHorarios');
                })
                .fail(function() {
                    showToast('Error al cargar los horarios.', 'danger');
                    $('#formAsignarHorarios button[type="submit"]').prop('disabled', false).text(
                        'Guardar Horarios');
                });
        });

        $('#formAsignarHorarios').submit(function(e) {
            e.preventDefault();
            const moduloId = $('#modulo_id').val();
            $.ajax({
                url: "{{ route('admin.modulos.asignar-horarios') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        todosLosEventos = todosLosEventos.filter(e => e.extendedProps.modulo_id !=
                            moduloId);
                        res.eventos.forEach(e => todosLosEventos.push(e));
                        calendar.getEvents().forEach(event => {
                            if (event.extendedProps.modulo_id == moduloId) event.remove();
                        });
                        if (!moduloFiltroActivo || moduloFiltroActivo == moduloId) {
                            res.eventos.forEach(eventData => calendar.addEvent(eventData));
                            if (res.eventos.length > 0) {
                                calendar.gotoDate(res.eventos[0].start.substring(0, 10));
                            }
                        }
                        hideModalManual('modalAsignarHorarios');
                        showToast(res.msg || 'Horarios guardados correctamente.');
                    } else {
                        showToast(res.msg || 'Error desconocido.', 'danger');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error al guardar. Verifique los datos.';
                    if (xhr.responseJSON?.msg) errorMsg = xhr.responseJSON.msg;
                    else if (xhr.responseJSON?.message) errorMsg = xhr.responseJSON.message;
                    showToast(errorMsg, 'danger');
                    console.error(xhr.responseText);
                }
            });
        });

        // === CAMBIAR COLOR MÓDULO ===
        $(document).on('click', '.cambiar-color-modulo', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const moduloId = $(this).data('modulo-id');
            const colorActual = $(this).data('color') || '#cccccc';
            $('#modulo-id-color').val(moduloId);
            $('#color-picker').val(colorActual);
            $('#color-preview').css('background-color', colorActual);
            showModalManual('modalColorModulo');
        });

        $('#color-picker').on('input', function() {
            $('#color-preview').css('background-color', $(this).val());
        });

        $('#guardar-color-btn').on('click', function() {
            const moduloId = $('#modulo-id-color').val();
            const nuevoColor = $('#color-picker').val();
            if (!moduloId || !nuevoColor) return;
            $.ajax({
                url: "{{ route('admin.modulos.actualizar-color') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: moduloId,
                    color: nuevoColor
                },
                success: function(res) {
                    if (res.success) {
                        $(`.cambiar-color-modulo[data-modulo-id="${moduloId}"]`)
                            .closest('.external-event')
                            .css({
                                'background-color': nuevoColor,
                                'color': (nuevoColor !== '#FFFFFF' && nuevoColor !== '#ffffff') ?
                                    '#FFFFFF' : '#000000'
                            });
                        $.get("{{ route('admin.modulos.obtener-eventos', ':id') }}".replace(':id',
                                moduloId))
                            .done(function(nuevosEventos) {
                                todosLosEventos = todosLosEventos.filter(e => e.extendedProps
                                    .modulo_id != moduloId);
                                nuevosEventos.forEach(e => todosLosEventos.push(e));
                                calendar.getEvents().forEach(event => {
                                    if (event.extendedProps.modulo_id == moduloId) event
                                        .remove();
                                });
                                if (!moduloFiltroActivo || moduloFiltroActivo == moduloId) {
                                    nuevosEventos.forEach(eventData => calendar.addEvent(
                                    eventData));
                                }
                            });
                        hideModalManual('modalColorModulo');
                        showToast('Color del módulo actualizado correctamente.');
                    } else {
                        showToast('Error al actualizar el color.', 'danger');
                    }
                },
                error: function() {
                    showToast('Error de conexión. Intente nuevamente.', 'danger');
                }
            });
        });
    </script>
@endpush
