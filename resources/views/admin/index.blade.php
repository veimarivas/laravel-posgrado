@extends('admin.dashboard')
@section('title', 'Dashboard - Ranking de Inscripciones')
@section('admin')

    <style>
        :root {
            --dash-primary: #0f766e;
            --dash-primary-light: #f0fdfa;
            --dash-primary-dark: #0d5f59;
            --dash-accent: #f59e0b;
            --dash-accent-light: #fffbeb;
            --dash-surface: #f8fafc;
            --dash-border: #e2e8f0;
            --dash-text: #1e293b;
            --dash-text-muted: #64748b;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 25px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
        }

        .dashboard-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--dash-text);
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

        /* ===== PAGE HEADER ===== */
        .dashboard-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 28px;
            padding: 24px 28px;
            background: linear-gradient(135deg, var(--dash-primary) 0%, var(--dash-primary-dark) 100%);
            border-radius: var(--radius-lg);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .dashboard-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 20%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .dashboard-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.65rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1;
            color: white;
        }

        .dashboard-header .breadcrumb-custom {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .dashboard-header .breadcrumb-custom a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.2s;
        }

        .dashboard-header .breadcrumb-custom a:hover {
            color: white;
        }

        .dashboard-header .breadcrumb-custom .separator {
            opacity: 0.5;
        }

        .dashboard-header .breadcrumb-custom .current {
            color: white;
            font-weight: 500;
        }

        /* ===== FILTER BAR ===== */
        .filter-bar {
            background: white;
            border-radius: var(--radius-lg);
            padding: 20px 24px;
            margin-bottom: 28px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--dash-border);
        }

        .filter-bar-row {
            display: flex;
            align-items: flex-end;
            gap: 16px;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 180px;
        }

        .filter-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--dash-text-muted);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .filter-select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--dash-border);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--dash-text);
            background: var(--dash-surface);
            transition: all 0.2s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--dash-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
            background-color: white;
        }

        .btn-apply-filters {
            padding: 10px 28px;
            background: var(--dash-primary);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all 0.25s ease;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-apply-filters:hover {
            background: var(--dash-primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-apply-filters:active {
            transform: translateY(0);
        }

        /* ===== CHART CARD ===== */
        .chart-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--dash-border);
            overflow: hidden;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .chart-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .chart-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--dash-border);
            text-align: center;
        }

        .chart-card-header h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            margin: 0;
            font-size: 1rem;
            color: var(--dash-text);
        }

        .chart-card-header small {
            color: var(--dash-text-muted);
            font-size: 0.8rem;
        }

        .chart-card-body {
            padding: 16px;
        }

        canvas {
            max-width: 100%;
            height: auto !important;
        }

        /* ===== TABLE CARD ===== */
        .table-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--dash-border);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .table-card-header {
            padding: 16px 24px;
            border-bottom: 1px solid var(--dash-border);
            display: flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--dash-primary) 0%, var(--dash-primary-dark) 100%);
        }

        .table-card-header h5 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            margin: 0;
            font-size: 1.05rem;
            color: white;
        }

        .table-card-header h5 i {
            color: rgba(255, 255, 255, 0.85);
        }

        .dash-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .dash-table thead th {
            background: var(--dash-surface);
            padding: 11px 14px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--dash-text-muted);
            border-bottom: 2px solid var(--dash-border);
            text-align: center;
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .dash-table thead th:nth-child(2) {
            text-align: left;
        }

        .dash-table tbody tr {
            transition: background 0.15s ease;
        }

        .dash-table tbody tr:nth-child(even):not(.top-1):not(.top-2):not(.top-3) {
            background: rgba(248, 250, 252, 0.8);
        }

        .dash-table tbody tr:hover {
            background: var(--dash-primary-light) !important;
        }

        .dash-table tbody td {
            padding: 11px 14px;
            border-bottom: 1px solid var(--dash-border);
            vertical-align: middle;
            text-align: center;
            font-size: 0.875rem;
            color: var(--dash-text);
        }

        .dash-table tbody tr:last-child td {
            border-bottom: none;
        }

        .dash-table tbody td:nth-child(2) {
            text-align: left;
        }

        .dash-table tbody tr.top-1 {
            background: linear-gradient(90deg, rgba(255, 215, 0, 0.1) 0%, rgba(255, 215, 0, 0.03) 100%);
        }

        .dash-table tbody tr.top-2 {
            background: linear-gradient(90deg, rgba(192, 192, 192, 0.12) 0%, rgba(192, 192, 192, 0.03) 100%);
        }

        .dash-table tbody tr.top-3 {
            background: linear-gradient(90deg, rgba(205, 127, 50, 0.09) 0%, rgba(205, 127, 50, 0.02) 100%);
        }

        .rank-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            background: var(--dash-surface);
            border: 1px solid var(--dash-border);
            border-radius: 50%;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--dash-text-muted);
        }

        .person-name {
            font-weight: 600;
            color: var(--dash-text);
            text-decoration: none;
            transition: color 0.2s;
        }

        .person-name:hover {
            color: var(--dash-primary);
        }

        .total-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            padding: 4px 12px;
            background: linear-gradient(135deg, var(--dash-primary), var(--dash-primary-dark));
            color: white;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .tipo-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            padding: 3px 10px;
            border-radius: var(--radius-sm);
            font-size: 0.82rem;
            font-weight: 600;
            background: var(--dash-surface);
            color: var(--dash-text-muted);
            border: 1px solid var(--dash-border);
        }

        .tipo-chip.has-data {
            background: rgba(15, 118, 110, 0.08);
            color: var(--dash-primary);
            border-color: rgba(15, 118, 110, 0.25);
        }

        .convenio-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 9px;
            background: rgba(245, 158, 11, 0.09);
            border: 1px solid rgba(245, 158, 11, 0.28);
            border-radius: 20px;
            font-size: 0.75rem;
            color: #92400e;
            white-space: nowrap;
        }

        .convenio-badge .conv-count {
            font-weight: 700;
            color: #b45309;
        }

        .convenio-cell {
            line-height: 1.9;
            text-align: left !important;
        }

        .medal-cell {
            font-size: 1.3rem;
            line-height: 1;
        }

        .medal-emoji {
            transition: transform 0.3s ease, text-shadow 0.3s ease;
            display: inline-block;
        }

        .medal-emoji:hover {
            transform: scale(1.2) rotate(5deg);
            text-shadow: 0 0 8px rgba(255, 215, 0, 0.7), 0 0 16px rgba(255, 215, 0, 0.5);
        }

        /* ===== BRANCH RANKING ===== */
        .branch-ranking-section {
            margin-top: 8px;
        }

        .branch-table-container {
            margin-bottom: 20px;
        }

        .branch-table-container:last-child {
            margin-bottom: 0;
        }

        .branch-header {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            background: var(--dash-primary-light);
            border-left: 4px solid var(--dash-primary);
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
            margin-bottom: 0;
        }

        .branch-header h6 {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            color: var(--dash-text);
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .branch-header h6 i {
            color: var(--dash-primary);
        }

        /* ===== PODIUM ===== */
        .podium-container {
            position: relative;
            padding: 20px 0;
            z-index: 1;
        }

        .podium-place {
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: all 0.3s ease;
        }

        .podium-card {
            position: relative;
            z-index: 3;
            width: 100%;
            transition: all 0.3s ease;
        }

        .ranking-card {
            position: relative;
            overflow: hidden;
            border-radius: var(--radius-md);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            will-change: transform;
        }

        .ranking-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.18);
            z-index: 10;
        }

        .ranking-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 60px;
            height: 200%;
            background: rgba(255, 255, 255, 0.5);
            transform: rotate(30deg);
            transition: opacity 0.6s ease-out, left 0.8s ease-out;
            opacity: 0;
            z-index: 1;
            pointer-events: none;
        }

        .ranking-card:hover::before {
            opacity: 1;
            left: 120%;
        }

        .podium-hover:hover {
            transform: translateY(-10px) !important;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2) !important;
            z-index: 10;
        }

        .podium-first .ranking-card {
            box-shadow: 0 10px 25px rgba(255, 215, 0, 0.4) !important;
        }

        .medal-badge {
            animation: float 3s ease-in-out infinite;
        }

        .podium-first .medal-badge {
            animation: float 3s ease-in-out infinite;
            animation-delay: 0.2s;
        }

        .podium-second .medal-badge {
            animation: float 3s ease-in-out infinite;
            animation-delay: 0.4s;
        }

        .podium-third .medal-badge {
            animation: float 3s ease-in-out infinite;
            animation-delay: 0.6s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .position-place-label {
            font-size: 0.8rem;
            letter-spacing: 1px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .bg-gold {
            background: linear-gradient(135deg, #FFD700, #FFA500) !important;
        }

        .bg-silver {
            background: linear-gradient(135deg, #C0C0C0, #A0A0A0) !important;
        }

        .bg-bronze {
            background: linear-gradient(135deg, #CD7F32, #8B4513) !important;
        }

        .ranking-card img {
            transition: all 0.3s ease;
        }

        .ranking-card:hover img {
            transform: scale(1.05);
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }

            50% {
                transform: scale(1.1);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .animate-on-load {
            animation: bounceIn 0.8s cubic-bezier(0.215, 0.61, 0.355, 1) forwards;
            opacity: 0;
            transform: scale(0.5);
        }

        /* ===== LOADING / EMPTY STATES ===== */
        .table-loading {
            position: relative;
            min-height: 200px;
        }

        .table-loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.85);
            z-index: 10;
        }

        .table-loading::before {
            content: 'Cargando datos...';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 11;
            color: var(--dash-primary);
            font-weight: 500;
            font-size: 1.1rem;
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
            color: var(--dash-text-muted);
            margin: 0;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991px) {
            .ranking-card {
                height: 220px !important;
            }

            .podium-first .ranking-card {
                height: 250px !important;
                transform: translateY(-15px) !important;
            }

            .podium-third .ranking-card {
                height: 200px !important;
            }

            .podium-hover:hover {
                transform: translateY(-5px) !important;
            }

            .podium-first .podium-hover:hover {
                transform: translateY(-20px) !important;
            }

            .ranking-card img {
                width: 70px !important;
                height: 70px !important;
            }

            .podium-first .ranking-card img {
                width: 80px !important;
                height: 80px !important;
            }

            .medal-badge div {
                width: 40px !important;
                height: 40px !important;
            }

            .podium-first .medal-badge div {
                width: 50px !important;
                height: 50px !important;
            }

            .dash-table tbody tr:hover {
                transform: none;
            }
        }

        @media (max-width: 767px) {
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                padding: 20px;
            }

            .dashboard-header h1 {
                font-size: 1.35rem;
            }

            .filter-bar {
                padding: 16px;
            }

            .filter-bar-row {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                min-width: 100%;
            }

            .btn-apply-filters {
                width: 100%;
                justify-content: center;
            }

            .podium-container {
                padding: 10px 0;
            }

            .ranking-card {
                height: 200px !important;
            }

            .podium-first .ranking-card {
                height: 230px !important;
                transform: translateY(-10px) !important;
            }

            .podium-third .ranking-card {
                height: 180px !important;
            }

            .position-place-label {
                font-size: 0.7rem;
                padding: 0.2rem 0.8rem !important;
            }

            .ranking-card img {
                width: 60px !important;
                height: 60px !important;
            }

            .podium-first .ranking-card img {
                width: 70px !important;
                height: 70px !important;
            }

            .chart-card .card-body {
                padding: 12px !important;
            }

            #graficoBarrasSucursales {
                height: 300px !important;
            }

            .dash-table {
                font-size: 0.85rem;
            }

            .dash-table thead th,
            .dash-table tbody td {
                padding: 10px 8px;
            }

            .branch-header {
                padding: 10px 16px;
            }
        }

        @media (max-width: 576px) {
            .dash-table {
                font-size: 0.8rem;
            }

            .table-card-header h5,
            .branch-header h6 {
                font-size: 0.95rem;
            }

            .medal-cell {
                font-size: 1.1rem;
            }
        }
    </style>

    <div class="dashboard-page">
        <!-- Page Header -->
        <div class="dashboard-header">
            <div>
                <h1><i class="ri-dashboard-line me-2"></i>Dashboard - Ranking de Inscripciones</h1>
                <div class="breadcrumb-custom mt-2">
                    <a href="{{ url('/admin/dashboard') }}">Inicio</a>
                    <span class="separator">/</span>
                    <span class="current">Dashboard</span>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <form id="filterForm">
                <div class="filter-bar-row">
                    <div class="filter-group">
                        <label for="mes">Mes</label>
                        <select name="mes" id="mes" class="filter-select">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == $mes ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::createFromDate($gestion, $m, 1)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="gestion">Gestión</label>
                        <select name="gestion" id="gestion" class="filter-select">
                            @for ($g = 2020; $g <= date('Y'); $g++)
                                <option value="{{ $g }}" {{ $g == $gestion ? 'selected' : '' }}>
                                    {{ $g }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="sucursal">Sucursal (opcional)</label>
                        <select name="sucursal" id="sucursal" class="filter-select">
                            <option value="">Todas las sucursales</option>
                            @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}" {{ $sucursal->id == $sucursalId ? 'selected' : '' }}>
                                    {{ $sucursal->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="convenio">Convenio (opcional)</label>
                        <select name="convenio" id="convenio" class="filter-select">
                            <option value="">Todos los convenios</option>
                            @foreach ($convenios as $conv)
                                <option value="{{ $conv->id }}" {{ $conv->id == $convenioId ? 'selected' : '' }}>
                                    {{ $conv->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="button" id="applyFilters" class="btn-apply-filters">
                            <i class="ri-filter-line"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Ranking Title -->
        <div class="mb-4">
            <h5 id="rankingTitle"
                style="font-family: 'Outfit', sans-serif; font-weight: 600; color: var(--dash-text); margin-bottom: 4px;">
                Inscripciones mes de {{ $nombreMes }} {{ $gestion }}
            </h5>
            <a href="{{ route('admin.dashboard') }}?mes={{ $mes }}&gestion={{ $gestion }}&view=complete"
                style="color: var(--dash-primary); font-weight: 500; font-size: 0.9rem; text-decoration: none;">
                Ir al tablero general <i class="ri-bar-chart-line"></i>
            </a>
        </div>

        <!-- Top 3 Podium + Pie Chart -->
        <div id="top3-and-chart-section">
            @if ($rankingGeneralTop3->isEmpty())
                <div class="table-card">
                    <div class="empty-state">
                        <i class="ri-emotion-sad-line"></i>
                        <p class="text-muted mt-3">No hay inscripciones registradas para este período.</p>
                    </div>
                </div>
            @else
                <div class="row mb-5 mt-4">
                    <div class="col-md-8">
                        <div class="podium-container">
                            <div class="row align-items-end justify-content-center g-4">

                                <!-- Segundo Lugar (Izquierda) -->
                                <div class="col-md-4">
                                    <div class="podium-place podium-second h-100 d-flex flex-column justify-content-end">
                                        <div class="podium-card">
                                            <a href="{{ route('admin.vendedor.inscripciones', ['personaId' => $rankingGeneralTop3[1]->id ?? 0]) }}"
                                                class="ranking-card d-block text-decoration-none text-dark podium-hover overflow-hidden"
                                                style="background: linear-gradient(135deg, #f5f5f5, #e0e0e0); border: 2px solid #C0C0C0; box-shadow: 0 6px 15px rgba(192, 192, 192, 0.3); transition: all 0.4s ease; height: 240px;">
                                                <div
                                                    class="card-body text-center py-3 px-3 d-flex flex-column justify-content-between h-100">
                                                    <div>
                                                        <div class="position-relative d-inline-block mb-2">
                                                            @if (isset($rankingGeneralTop3[1]) &&
                                                                    $rankingGeneralTop3[1]->fotografia &&
                                                                    file_exists(public_path($rankingGeneralTop3[1]->fotografia)))
                                                                <img src="{{ asset($rankingGeneralTop3[1]->fotografia) }}"
                                                                    alt="{{ $rankingGeneralTop3[1]->nombre_completo ?? '' }}"
                                                                    class="rounded-circle border shadow"
                                                                    style="width: 90px; height: 90px; object-fit: cover; border: 3px solid #C0C0C0 !important;">
                                                            @elseif (isset($rankingGeneralTop3[1]))
                                                                <img src="{{ $rankingGeneralTop3[1]->avatar ?? asset('backend/assets/images/hombre.png') }}"
                                                                    alt="{{ $rankingGeneralTop3[1]->nombre_completo ?? '' }}"
                                                                    class="rounded-circle border shadow"
                                                                    style="width: 90px; height: 90px; object-fit: cover; border: 3px solid #C0C0C0 !important;">
                                                            @endif
                                                        </div>
                                                        @if (isset($rankingGeneralTop3[1]))
                                                            <h6 class="mb-1 fw-bold">
                                                                {{ $rankingGeneralTop3[1]->nombre_completo ?? '' }}</h6>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="position-relative mt-2">
                                                            <div
                                                                class="position-place-label bg-silver text-white rounded-pill py-1 px-3 d-inline-block">
                                                                <strong>2° LUGAR</strong>
                                                            </div>
                                                        </div>
                                                        <p class="text-muted small mb-0 mt-2">
                                                            {{ $rankingGeneralTop3[1]->total_inscripciones ?? 0 }}
                                                            inscripciones</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="medal-badge position-relative text-center mt-2">
                                            <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm"
                                                style="width: 50px; height: 50px; border: 2px solid #C0C0C0;">
                                                <span class="h4 mb-0">🥈</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Primer Lugar (Centro) -->
                                <div class="col-md-4">
                                    <div class="podium-place podium-first h-100 d-flex flex-column justify-content-end">
                                        <div class="podium-card">
                                            <a href="{{ route('admin.vendedor.inscripciones', ['personaId' => $rankingGeneralTop3[0]->id]) }}"
                                                class="ranking-card d-block text-decoration-none text-dark podium-hover overflow-hidden"
                                                style="background: linear-gradient(135deg, #fff9c4, #ffecb3); border: 2px solid #FFD700; box-shadow: 0 10px 25px rgba(255, 215, 0, 0.4); transform: translateY(-20px); transition: all 0.4s ease; height: 270px;">
                                                <div
                                                    class="card-body text-center py-3 px-3 d-flex flex-column justify-content-between h-100">
                                                    <div>
                                                        <div class="position-relative d-inline-block mb-2">
                                                            @if ($rankingGeneralTop3[0]->fotografia && file_exists(public_path($rankingGeneralTop3[0]->fotografia)))
                                                                <img src="{{ asset($rankingGeneralTop3[0]->fotografia) }}"
                                                                    alt="{{ $rankingGeneralTop3[0]->nombre_completo }}"
                                                                    class="rounded-circle border shadow-lg"
                                                                    style="width: 100px; height: 100px; object-fit: cover; border: 4px solid #FFD700 !important;">
                                                            @else
                                                                <img src="{{ $rankingGeneralTop3[0]->avatar }}"
                                                                    alt="{{ $rankingGeneralTop3[0]->nombre_completo }}"
                                                                    class="rounded-circle border shadow-lg"
                                                                    style="width: 100px; height: 100px; object-fit: cover; border: 4px solid #FFD700 !important;">
                                                            @endif
                                                        </div>
                                                        <h5 class="mb-1 fw-bold">
                                                            {{ $rankingGeneralTop3[0]->nombre_completo }}</h5>
                                                    </div>
                                                    <div>
                                                        <div class="position-relative mt-2">
                                                            <div
                                                                class="position-place-label bg-gold text-white rounded-pill py-2 px-4 d-inline-block">
                                                                <strong>1° LUGAR</strong>
                                                            </div>
                                                        </div>
                                                        <p class="text-muted mb-0 mt-2">
                                                            {{ $rankingGeneralTop3[0]->total_inscripciones }} inscripciones
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="medal-badge position-relative text-center mt-2">
                                            <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-lg"
                                                style="width: 60px; height: 60px; border: 3px solid #FFD700;">
                                                <span class="h3 mb-0">🥇</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tercer Lugar (Derecha) -->
                                <div class="col-md-4">
                                    <div class="podium-place podium-third h-100 d-flex flex-column justify-content-end">
                                        <div class="podium-card">
                                            <a href="{{ route('admin.vendedor.inscripciones', ['personaId' => $rankingGeneralTop3[2]->id ?? 0]) }}"
                                                class="ranking-card d-block text-decoration-none text-dark podium-hover overflow-hidden"
                                                style="background: linear-gradient(135deg, #ffe0b2, #d7ccc8); border: 2px solid #CD7F32; box-shadow: 0 6px 15px rgba(205, 127, 50, 0.3); transition: all 0.4s ease; height: 220px;">
                                                <div
                                                    class="card-body text-center py-3 px-3 d-flex flex-column justify-content-between h-100">
                                                    <div>
                                                        <div class="position-relative d-inline-block mb-2">
                                                            @if (isset($rankingGeneralTop3[2]) &&
                                                                    $rankingGeneralTop3[2]->fotografia &&
                                                                    file_exists(public_path($rankingGeneralTop3[2]->fotografia)))
                                                                <img src="{{ asset($rankingGeneralTop3[2]->fotografia) }}"
                                                                    alt="{{ $rankingGeneralTop3[2]->nombre_completo ?? '' }}"
                                                                    class="rounded-circle border shadow"
                                                                    style="width: 90px; height: 90px; object-fit: cover; border: 3px solid #CD7F32 !important;">
                                                            @elseif (isset($rankingGeneralTop3[2]))
                                                                <img src="{{ $rankingGeneralTop3[2]->avatar ?? asset('backend/assets/images/hombre.png') }}"
                                                                    alt="{{ $rankingGeneralTop3[2]->nombre_completo ?? '' }}"
                                                                    class="rounded-circle border shadow"
                                                                    style="width: 90px; height: 90px; object-fit: cover; border: 3px solid #CD7F32 !important;">
                                                            @endif
                                                        </div>
                                                        @if (isset($rankingGeneralTop3[2]))
                                                            <h6 class="mb-1 fw-bold">
                                                                {{ $rankingGeneralTop3[2]->nombre_completo ?? '' }}</h6>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="position-relative mt-2">
                                                            <div
                                                                class="position-place-label bg-bronze text-white rounded-pill py-1 px-3 d-inline-block">
                                                                <strong>3° LUGAR</strong>
                                                            </div>
                                                        </div>
                                                        <p class="text-muted small mb-0 mt-2">
                                                            {{ $rankingGeneralTop3[2]->total_inscripciones ?? 0 }}
                                                            inscripciones</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="medal-badge position-relative text-center mt-2">
                                            <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm"
                                                style="width: 50px; height: 50px; border: 2px solid #CD7F32;">
                                                <span class="h4 mb-0">🥉</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Pie Chart -->
                    <div class="col-md-4">
                        <div class="chart-card h-100 d-flex flex-column">
                            <div class="chart-card-header">
                                <h6><i class="ri-pie-chart-2-line me-1" style="color: var(--dash-primary);"></i>
                                    Distribución por Tipo</h6>
                                <small>{{ $nombreMes }} {{ $gestion }}</small>
                            </div>
                            <div class="chart-card-body d-flex align-items-center justify-content-center flex-grow-1">
                                <div style="width: 100%; max-width: 280px; height: 280px;">
                                    <canvas id="graficoTipos"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Bar Chart -->
        <div id="bar-chart-section">
            @if (!empty($graficoBarrasData['sucursales']))
                <div class="chart-card mb-4">
                    <div class="chart-card-header">
                        <h6><i class="ri-bar-chart-horizontal-line me-1" style="color: var(--dash-primary);"></i>
                            Inscripciones por Sucursal y Tipo</h6>
                        <small>{{ $nombreMes }} {{ $gestion }}</small>
                    </div>
                    <div class="chart-card-body">
                        <div style="height: 380px; width: 100%;">
                            <canvas id="graficoBarrasSucursales"></canvas>
                        </div>
                    </div>
                </div>
            @else
                <div class="table-card mb-4">
                    <div class="empty-state">
                        <i class="ri-bar-chart-line"></i>
                        <p class="text-muted mt-3">No hay datos suficientes para mostrar el gráfico de barras.</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Chart por Convenio (solo cuando mostrarConvenio = true) -->
        @if ($mostrarConvenio && !empty($graficoPorConvenio['convenios']))
            <div id="convenio-chart-section">
                <div class="chart-card mb-4">
                    <div class="chart-card-header">
                        <h6><i class="ri-hand-heart-line me-1" style="color: var(--dash-primary);"></i> Inscripciones por
                            Convenio</h6>
                        <small>{{ $nombreMes }} {{ $gestion }}</small>
                    </div>
                    <div class="chart-card-body">
                        <div style="height: 300px; width: 100%;">
                            <canvas id="graficoConvenios"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Full Ranking Table -->
        <div id="full-ranking-section" class="table-card">
            <div class="table-card-header">
                <h5><i class="ri-trophy-line me-2"></i>Ranking General con Desglose</h5>
            </div>
            <div class="table-responsive">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 25%;">Persona</th>
                            @if ($mostrarConvenio)
                                <th style="width: 20%;">Convenio</th>
                            @endif
                            <th style="width: 10%;">Total</th>
                            @foreach ($tipos as $tipoNombre)
                                <th>{{ $tipoNombre }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rankingGeneralCompleto as $index => $persona)
                            @php
                                $medals = ['🥇', '🥈', '🥉'];
                                $medalEmoji = $medals[$index] ?? null;
                                $rowClass = $index < 3 ? 'top-' . ($index + 1) : '';
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td class="align-middle">
                                    @if ($medalEmoji)
                                        <div class="medal-cell"><span class="medal-emoji">{{ $medalEmoji }}</span></div>
                                    @else
                                        <span class="rank-num">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('admin.vendedor.inscripciones', ['personaId' => $persona->id]) }}" class="person-name">
                                        {{ $persona->nombre_completo }}
                                    </a>
                                </td>
                                @if ($mostrarConvenio)
                                    <td class="align-middle convenio-cell">
                                        @if($persona->convenios_detalle)
                                            @foreach($persona->convenios_detalle as $convNombre => $convCantidad)
                                                <span class="convenio-badge">{{ $convNombre }}: <span class="conv-count">{{ $convCantidad }}</span></span>@if(!$loop->last)<br>@endif
                                            @endforeach
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                @endif
                                <td class="align-middle"><span class="total-pill">{{ $persona->total_inscripciones }}</span></td>
                                @foreach ($tipos as $tipoNombre)
                                    @php $tipVal = $persona->desglose[$tipoNombre] ?? 0; @endphp
                                    <td class="align-middle"><span class="tipo-chip {{ $tipVal > 0 ? 'has-data' : '' }}">{{ $tipVal }}</span></td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Branch Ranking -->
        <div id="branch-ranking-section" class="branch-ranking-section">
            <div class="table-card">
                <div class="table-card-header">
                    <h5><i class="ri-building-line me-2"></i>Ranking por Sucursal</h5>
                </div>
                <div style="padding: 20px;">
                    @if ($rankingPorSucursal->isEmpty())
                        <div class="empty-state">
                            <i class="ri-emotion-sad-line"></i>
                            <p class="text-muted mt-3">No hay datos disponibles para mostrar en este período.</p>
                        </div>
                    @else
                        @foreach ($rankingPorSucursal as $sucursalNombre => $personas)
                            <div class="branch-table-container">
                                <div class="branch-header">
                                    <h6><i class="ri-map-pin-2-line"></i> {{ $sucursalNombre }}</h6>
                                </div>
                                <div class="table-responsive">
                                    <table class="dash-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%;">#</th>
                                                <th style="width: 25%;">Persona</th>
                                                @if ($mostrarConvenio)
                                                    <th style="width: 18%;">Convenio</th>
                                                @endif
                                                <th style="width: 8%;">Total</th>
                                                @foreach ($tipos as $tipoNombre)
                                                    <th>{{ $tipoNombre }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($personas as $index => $persona)
                                                @php
                                                    $medals = ['🥇', '🥈', '🥉'];
                                                    $medalEmoji = $medals[$index] ?? null;
                                                    $rowClass = $index < 3 ? 'top-' . ($index + 1) : '';
                                                @endphp
                                                <tr class="{{ $rowClass }}">
                                                    <td class="align-middle">
                                                        @if ($medalEmoji)
                                                            <div class="medal-cell"><span class="medal-emoji">{{ $medalEmoji }}</span></div>
                                                        @else
                                                            <span class="rank-num">{{ $index + 1 }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">
                                                        <a href="{{ route('admin.vendedor.inscripciones', ['personaId' => $persona->id]) }}" class="person-name">
                                                            {{ $persona->nombre_completo }}
                                                        </a>
                                                    </td>
                                                    @if ($mostrarConvenio)
                                                        <td class="align-middle convenio-cell">
                                                            @if($persona->convenios_detalle)
                                                                @foreach($persona->convenios_detalle as $convNombre => $convCantidad)
                                                                    <span class="convenio-badge">{{ $convNombre }}: <span class="conv-count">{{ $convCantidad }}</span></span>@if(!$loop->last)<br>@endif
                                                                @endforeach
                                                            @else
                                                                <span class="text-muted">—</span>
                                                            @endif
                                                        </td>
                                                    @endif
                                                    <td class="align-middle"><span class="total-pill">{{ $persona->total_inscripciones }}</span></td>
                                                    @foreach ($tipos as $tipoNombre)
                                                        @php $tipVal = $persona->desglose[$tipoNombre] ?? 0; @endphp
                                                        <td class="align-middle"><span class="tipo-chip {{ $tipVal > 0 ? 'has-data' : '' }}">{{ $tipVal }}</span></td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filterForm');
            const applyBtn = document.getElementById('applyFilters');
            const loadingIndicator =
                `<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>`;

            let pieChart = null;
            let barChart = null;
            let convenioChart = null;

            function updateURL(filters) {
                const url = new URL(window.location);
                Object.keys(filters).forEach(key => {
                    if (filters[key] !== '' && filters[key] !== null) {
                        url.searchParams.set(key, filters[key]);
                    } else {
                        url.searchParams.delete(key);
                    }
                });
                window.history.pushState(filters, '', url);
            }

            function getFiltersFromURL() {
                const urlParams = new URLSearchParams(window.location.search);
                return {
                    mes: urlParams.get('mes') || {{ Carbon\Carbon::now()->month }},
                    gestion: urlParams.get('gestion') || {{ Carbon\Carbon::now()->year }},
                    sucursal: urlParams.get('sucursal') || '',
                    convenio: urlParams.get('convenio') || ''
                };
            }

            function applyFiltersFromURL() {
                const filters = getFiltersFromURL();
                document.getElementById('mes').value = filters.mes;
                document.getElementById('gestion').value = filters.gestion;
                document.getElementById('sucursal').value = filters.sucursal || '';
                document.getElementById('convenio').value = filters.convenio || '';
                updateDashboard(filters);
            }

            function updateDashboard(filters) {
                updateURL(filters);

                // Destruir gráficos ANTES de remover sus canvas del DOM
                if (pieChart) { pieChart.destroy(); pieChart = null; }
                if (barChart) { barChart.destroy(); barChart = null; }
                if (convenioChart) { convenioChart.destroy(); convenioChart = null; }
                const convenioSectionBefore = document.getElementById('convenio-chart-section');
                if (convenioSectionBefore) convenioSectionBefore.remove();

                document.getElementById('top3-and-chart-section').innerHTML = loadingIndicator;
                document.getElementById('bar-chart-section').innerHTML = loadingIndicator;
                document.getElementById('full-ranking-section').innerHTML = loadingIndicator;
                document.getElementById('branch-ranking-section').innerHTML = loadingIndicator;

                axios.get("{{ route('admin.dashboard.data') }}", {
                        params: filters
                    })
                    .then(response => {
                        const data = response.data;
                        const tipoNombres = Object.values(data.tipos);

                        if (data.rankingGeneralTop3 && data.rankingGeneralTop3.length > 0) {
                            const top3Render = renderTop3(data.rankingGeneralTop3, tipoNombres);
                            document.getElementById('top3-and-chart-section').innerHTML = `
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="row g-4 justify-content-center" id="top3-container">
                                ${top3Render.html}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="chart-card h-100 d-flex flex-column">
                                <div class="chart-card-header">
                                    <h6><i class="ri-pie-chart-2-line me-1" style="color: var(--dash-primary);"></i> Distribución por Tipo</h6>
                                    <small>${data.nombreMes} ${data.gestion}</small>
                                </div>
                                <div class="chart-card-body d-flex align-items-center justify-content-center flex-grow-1">
                                    <div style="width: 100%; max-width: 280px; height: 280px;">
                                        <canvas id="graficoTipos"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                            top3Render.activateAnimations();
                        } else {
                            document.getElementById('top3-and-chart-section').innerHTML = `
                    <div class="table-card">
                        <div class="empty-state">
                            <i class="ri-information-line"></i>
                            <p class="text-muted mt-3">No hay inscripciones registradas para este período.</p>
                        </div>
                    </div>
                `;
                        }

// Actualizar título del período
                        const titleEl = document.getElementById('rankingTitle');
                        if (titleEl) titleEl.textContent = `Inscripciones mes de ${data.nombreMes} ${data.gestion}`;

                        updatePieChart(data.graficoPorTipo);
                        updateBarChart(data.graficoBarrasData, data.nombreMes, data.gestion);

                        // Gráfico por Convenio: solo cuando todos los convenios están seleccionados
                        if (data.mostrarConvenio && data.graficoPorConvenio && Object.values(data.graficoPorConvenio).some(v => v > 0)) {
                            const barSection = document.getElementById('bar-chart-section');
                            barSection.insertAdjacentHTML('afterend', `
                    <div id="convenio-chart-section">
                        <div class="chart-card mb-4">
                            <div class="chart-card-header">
                                <h6><i class="ri-hand-heart-line me-1" style="color: var(--dash-primary);"></i> Inscripciones por Convenio</h6>
                                <small>${data.nombreMes} ${data.gestion}</small>
                            </div>
                            <div class="chart-card-body">
                                <div style="height: 300px; width: 100%;">
                                    <canvas id="graficoConvenios"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>`);
                            updateConvenioChart(data.graficoPorConvenio);
                        }

const mostrarConvenio = data.mostrarConvenio;

                        if (data.rankingGeneralCompleto && data.rankingGeneralCompleto.length > 0) {
                            const convenioHeader = mostrarConvenio ? `<th style="width: 18%;">Convenio</th>` : '';
                            document.getElementById('full-ranking-section').innerHTML = `
            <div class="table-card">
                <div class="table-card-header">
                    <h5><i class="ri-trophy-line me-2"></i>Ranking General con Desglose</h5>
                </div>
                <div class="table-responsive">
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 25%;">Persona</th>
                                ${convenioHeader}
                                <th style="width: 10%;">Total</th>
                                ${tipoNombres.map(t => `<th>${t}</th>`).join('')}
                            </tr>
                        </thead>
                        <tbody>
                            ${renderFullRanking(data.rankingGeneralCompleto, tipoNombres, mostrarConvenio)}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
                        } else {
                            document.getElementById('full-ranking-section').innerHTML = `
                <div class="table-card">
                    <div class="table-card-header">
                        <h5><i class="ri-trophy-line me-2"></i>Ranking General con Desglose</h5>
                    </div>
                    <div class="empty-state">
                        <i class="ri-emotion-sad-line"></i>
                        <p class="text-muted mt-3">No hay inscripciones registradas para este período.</p>
                    </div>
                </div>
            `;
                        }

                        if (data.rankingPorSucursal && Object.keys(data.rankingPorSucursal).length > 0) {
                            document.getElementById('branch-ranking-section').innerHTML = `
                <div class="branch-ranking-section">
                    <div class="table-card">
                        <div class="table-card-header">
                            <h5><i class="ri-building-line me-2"></i>Ranking por Sucursal</h5>
                        </div>
                        <div style="padding: 20px;">
                            ${renderBranchRanking(data.rankingPorSucursal, tipoNombres, mostrarConvenio)}
                        </div>
                    </div>
                </div>
            `;
                        } else {
                            document.getElementById('branch-ranking-section').innerHTML = `
                <div class="branch-ranking-section">
                    <div class="table-card">
                        <div class="table-card-header">
                            <h5><i class="ri-building-line me-2"></i>Ranking por Sucursal</h5>
                        </div>
                        <div class="empty-state">
                            <i class="ri-emotion-sad-line"></i>
                            <p class="text-muted mt-3">No hay datos disponibles para mostrar en este período.</p>
                        </div>
                    </div>
                </div>
            `;
                        }

                    })
                    .catch(error => {
                        console.error('Error en la petición AJAX:', error);
                        document.getElementById('top3-and-chart-section').innerHTML = `
                <div class="table-card">
                    <div class="empty-state">
                        <i class="ri-error-warning-line" style="color: #ef4444;"></i>
                        <p class="text-muted mt-3" style="color: #ef4444;">No existen datos para mostrar.</p>
                    </div>
                </div>
            `;
                        document.getElementById('bar-chart-section').innerHTML = `
                <div class="table-card">
                    <div class="empty-state">
                        <i class="ri-error-warning-line" style="color: #ef4444;"></i>
                        <p class="text-muted mt-3" style="color: #ef4444;">No existen datos para mostrar.</p>
                    </div>
                </div>
            `;
                        document.getElementById('full-ranking-section').innerHTML = `
                <div class="table-card">
                    <div class="table-card-header">
                        <h5><i class="ri-trophy-line me-2"></i>Ranking General con Desglose</h5>
                    </div>
                    <div class="empty-state">
                        <i class="ri-error-warning-line" style="color: #ef4444;"></i>
                        <p class="text-muted mt-3" style="color: #ef4444;">No existen datos para mostrar.</p>
                    </div>
                </div>
            `;
                        document.getElementById('branch-ranking-section').innerHTML = `
                <div class="branch-ranking-section">
                    <div class="table-card">
                        <div class="table-card-header">
                            <h5><i class="ri-building-line me-2"></i>Ranking por Sucursal</h5>
                        </div>
                        <div class="empty-state">
                            <i class="ri-error-warning-line" style="color: #ef4444;"></i>
                            <p class="text-muted mt-3" style="color: #ef4444;">No existen datos para mostrar.</p>
                        </div>
                    </div>
                </div>
            `;
                    });
            }

            function renderTop3(ranking, tipoNombres) {
                const medalEmojis = ['🥇', '🥈', '🥉'];
                const borders = ['#FFD700', '#C0C0C0', '#CD7F32'];
                const gradients = [
                    'linear-gradient(135deg, #fff9c4, #ffecb3)',
                    'linear-gradient(135deg, #f5f5f5, #e0e0e0)',
                    'linear-gradient(135deg, #ffe0b2, #d7ccc8)'
                ];
                const cardHeights = ['270px', '240px', '220px'];
                const imageSizes = ['100px', '90px', '90px'];
                const borderWidths = ['4px', '3px', '3px'];
                const medalSizes = ['h3', 'h4', 'h4'];
                const positionLabels = ['1° LUGAR', '2° LUGAR', '3° LUGAR'];
                const bgColors = ['bg-gold', 'bg-silver', 'bg-bronze'];
                const headingSizes = ['h5', 'h6', 'h6'];
                const podiumClasses = ['podium-first', 'podium-second', 'podium-third'];
                const transformY = ['-20px', '0', '0'];

                const podioOrder = [1, 0, 2];

                const podioCards = podioOrder.map((rankIndex, displayIndex) => {
                    if (!ranking[rankIndex]) return '';

                    const persona = ranking[rankIndex];
                    const index = rankIndex;

                    return `
<div class="col-md-4">
    <div class="podium-place ${podiumClasses[index]} h-100 d-flex flex-column justify-content-end">
        <div class="podium-card">
            <a href="/admin/vendedor/inscripciones/${persona.id}" 
               class="ranking-card d-block text-decoration-none text-dark podium-hover overflow-hidden"
               style="background: ${gradients[index]}; border: 2px solid ${borders[index]}; box-shadow: ${index === 0 ? '0 10px 25px rgba(255, 215, 0, 0.4)' : index === 1 ? '0 6px 15px rgba(192, 192, 192, 0.3)' : '0 6px 15px rgba(205, 127, 50, 0.3)'}; transform: translateY(${transformY[index]}); transition: all 0.4s ease; height: ${cardHeights[index]};">
                <div class="card-body text-center py-3 px-3 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="position-relative d-inline-block mb-2">
                            <img src="${persona.fotografia ? ('{{ asset('') }}' + persona.fotografia) : persona.avatar}"
                                 alt="${persona.nombre_completo}"
                                 class="rounded-circle border ${index === 0 ? 'shadow-lg' : 'shadow'}"
                                 style="width: ${imageSizes[index]}; height: ${imageSizes[index]}; object-fit: cover; border: ${borderWidths[index]} solid ${borders[index]} !important;">
                        </div>
                        <${headingSizes[index]} class="mb-1 fw-bold">${persona.nombre_completo}</${headingSizes[index]}>
                    </div>
                    <div>
                        <div class="position-relative mt-2">
                            <div class="position-place-label ${bgColors[index]} text-white rounded-pill ${index === 0 ? 'py-2 px-4' : 'py-1 px-3'} d-inline-block">
                                <strong>${positionLabels[index]}</strong>
                            </div>
                        </div>
                        <p class="text-muted ${index === 0 ? '' : 'small'} mb-0 mt-2">${persona.total_inscripciones} inscripciones</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="medal-badge position-relative text-center mt-2">
            <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center ${index === 0 ? 'shadow-lg' : 'shadow-sm'}" style="width: ${index === 0 ? '60px' : '50px'}; height: ${index === 0 ? '60px' : '50px'}; border: ${index === 0 ? '3px' : '2px'} solid ${borders[index]};">
                <span class="${medalSizes[index]} mb-0">${medalEmojis[index]}</span>
            </div>
        </div>
    </div>
</div>
        `;
                }).join('');

                return {
                    html: `
<div class="podium-container">
    <div class="row align-items-end justify-content-center g-4">
        ${podioCards}
    </div>
</div>
        `,
                    activateAnimations: function() {
                        setTimeout(() => {
                            document.querySelectorAll('.medal-badge').forEach((el, i) => {
                                el.style.animation = 'none';
                                el.offsetHeight;
                                el.style.animation = null;
                                el.style.animationDelay = `${0.2 + (i * 0.2)}s`;
                            });
                        }, 100);
                    }
                };
            }

function renderFullRanking(ranking, tipoNombres, mostrarConvenio = false) {
                return ranking.map((persona, idx) => {
                    const medals = ['🥇', '🥈', '🥉'];
                    const rankHtml = idx < 3
                        ? `<div class="medal-cell"><span class="medal-emoji">${medals[idx]}</span></div>`
                        : `<span class="rank-num">${idx + 1}</span>`;
                    const rowClass = idx < 3 ? `top-${idx + 1}` : '';

                    let conveniosHtml = '';
                    if (mostrarConvenio && persona.convenios_detalle) {
                        const badges = Object.entries(persona.convenios_detalle)
                            .map(([nombre, cantidad]) =>
                                `<span class="convenio-badge">${nombre}: <span class="conv-count">${cantidad}</span></span>`)
                            .join('<br>');
                        conveniosHtml = `<td class="align-middle convenio-cell">${badges || '<span class="text-muted">—</span>'}</td>`;
                    } else if (mostrarConvenio) {
                        conveniosHtml = '<td class="align-middle"><span class="text-muted">—</span></td>';
                    }

                    return `
                <tr class="${rowClass}">
                    <td class="align-middle">${rankHtml}</td>
                    <td class="align-middle"><a href="/admin/vendedor/inscripciones/${persona.id}" class="person-name">${persona.nombre_completo}</a></td>
                    ${conveniosHtml}
                    <td class="align-middle"><span class="total-pill">${persona.total_inscripciones}</span></td>
                    ${tipoNombres.map(tipo => {
                        const val = persona.desglose[tipo] ?? 0;
                        return `<td class="align-middle"><span class="tipo-chip ${val > 0 ? 'has-data' : ''}">${val}</span></td>`;
                    }).join('')}
                </tr>
            `;
                }).join('');
            }

            function renderBranchRanking(data, tipoNombres, mostrarConvenio = false) {
                if (Object.keys(data).length === 0) {
                    return '<p class="text-center text-muted">No hay datos disponibles.</p>';
                }

                const medals = ['🥇', '🥈', '🥉'];

                return Object.entries(data).map(([sucursal, personas]) => {
                    const tableRows = personas.map((persona, idx) => {
                        const rankHtml = idx < 3
                            ? `<div class="medal-cell"><span class="medal-emoji">${medals[idx]}</span></div>`
                            : `<span class="rank-num">${idx + 1}</span>`;
                        const rowClass = idx < 3 ? `top-${idx + 1}` : '';

                        let conveniosHtml = '';
                        if (mostrarConvenio && persona.convenios_detalle) {
                            const badges = Object.entries(persona.convenios_detalle)
                                .map(([nombre, cantidad]) =>
                                    `<span class="convenio-badge">${nombre}: <span class="conv-count">${cantidad}</span></span>`)
                                .join('<br>');
                            conveniosHtml = `<td class="align-middle convenio-cell">${badges || '<span class="text-muted">—</span>'}</td>`;
                        } else if (mostrarConvenio) {
                            conveniosHtml = '<td class="align-middle"><span class="text-muted">—</span></td>';
                        }

                        return `
                    <tr class="${rowClass}">
                        <td class="align-middle">${rankHtml}</td>
                        <td class="align-middle"><a href="/admin/vendedor/inscripciones/${persona.id}" class="person-name">${persona.nombre_completo}</a></td>
                        ${conveniosHtml}
                        <td class="align-middle"><span class="total-pill">${persona.total_inscripciones}</span></td>
                        ${tipoNombres.map(tipo => {
                            const val = persona.desglose[tipo] ?? 0;
                            return `<td class="align-middle"><span class="tipo-chip ${val > 0 ? 'has-data' : ''}">${val}</span></td>`;
                        }).join('')}
                    </tr>
                `;
                    }).join('');

                    const convenioHeader = mostrarConvenio ? `<th style="width: 18%;">Convenio</th>` : '';

                    return `
            <div class="branch-table-container">
                <div class="branch-header">
                    <h6><i class="ri-map-pin-2-line"></i> ${sucursal}</h6>
                </div>
                <div class="table-responsive">
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 25%;">Persona</th>
                                ${convenioHeader}
                                <th style="width: 8%;">Total</th>
                                ${tipoNombres.map(t => `<th>${t}</th>`).join('')}
                            </tr>
                        </thead>
                        <tbody>
                            ${tableRows}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
                }).join('');
            }

            function updatePieChart(data) {
                const canvas = document.getElementById('graficoTipos');
                if (!canvas) return;
                const ctx = canvas.getContext('2d');
                const labels = Object.keys(data);
                const values = Object.values(data);
                const backgroundColors = ['#0f766e', '#0d5f59', '#14b8a6', '#2dd4bf', '#f59e0b', '#d97706',
                    '#ef4444', '#f97316', '#8b5cf6', '#6366f1'
                ];
                pieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels,
                        datasets: [{
                            data: values,
                            backgroundColor: backgroundColors,
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: window.innerWidth >= 768
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                        const pct = ((ctx.raw / total) * 100).toFixed(1);
                                        return `${ctx.label}: ${ctx.raw} (${pct}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function updateBarChart(data, nombreMes, gestion) {
                const container = document.getElementById('bar-chart-section');

                if (!data || !Array.isArray(data.sucursales) || data.sucursales.length === 0) {
                    container.innerHTML = `
            <div class="table-card mb-4">
                <div class="empty-state">
                    <i class="ri-bar-chart-line"></i>
                    <p class="text-muted mt-3">No hay datos suficientes para mostrar el gráfico de barras.</p>
                </div>
            </div>
        `;
                    return;
                }

                container.innerHTML = `
        <div class="chart-card mb-4">
            <div class="chart-card-header">
                <h6><i class="ri-bar-chart-horizontal-line me-1" style="color: var(--dash-primary);"></i> Inscripciones por Sucursal y Tipo</h6>
                <small>${nombreMes} ${gestion}</small>
            </div>
            <div class="chart-card-body">
                <div style="height: 380px; width: 100%;">
                    <canvas id="graficoBarrasSucursales"></canvas>
                </div>
            </div>
        </div>
    `;

                const ctx = document.getElementById('graficoBarrasSucursales').getContext('2d');

                const sucursales = data.sucursales;
                const tipos = data.tipos;
                const valores = data.valores;

                if (!Array.isArray(tipos) || tipos.length === 0) {
                    console.warn("No hay tipos definidos para el gráfico.");
                    return;
                }

                const coloresBase = ['#0f766e', '#0d5f59', '#14b8a6', '#2dd4bf', '#f59e0b', '#d97706', '#ef4444',
                    '#f97316', '#8b5cf6', '#6366f1'
                ];

                const datasets = tipos.map((tipo, idx) => ({
                    label: tipo,
                    data: sucursales.map(s => parseInt(valores[s]?.[tipo] || 0, 10)),
                    backgroundColor: coloresBase[idx % coloresBase.length],
                    borderRadius: 4,
                    borderSkipped: false
                }));

                const isMobile = window.innerWidth < 768;

                barChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: sucursales,
                        datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 12,
                                    padding: 10,
                                    font: {
                                        size: isMobile ? 11 : 12
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    autoSkip: true,
                                    maxRotation: isMobile ? 45 : 0,
                                    font: {
                                        size: isMobile ? 10 : 12
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Sucursales',
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    font: {
                                        size: isMobile ? 10 : 12
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Nº de Inscripciones',
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });
            }

            function updateConvenioChart(data) {
                const canvas = document.getElementById('graficoConvenios');
                if (!canvas) return;

                const ctx = canvas.getContext('2d');
                const convenios = Object.keys(data);
                const valores = Object.values(data);

                const coloresBase = ['#0f766e', '#0d5f59', '#14b8a6', '#2dd4bf', '#f59e0b', '#d97706', '#ef4444',
                    '#f97316', '#8b5cf6', '#6366f1'
                ];

                convenioChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: convenios,
                        datasets: [{
                            label: 'Inscripciones',
                            data: valores,
                            backgroundColor: convenios.map((_, idx) => coloresBase[idx % coloresBase
                                .length]),
                            borderRadius: 6,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    autoSkip: false,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            applyBtn.addEventListener('click', () => {
                const formData = new FormData(filterForm);
                const filters = Object.fromEntries(formData);
                updateDashboard(filters);
            });

            window.addEventListener('popstate', (event) => {
                if (event.state) {
                    applyFiltersFromURL();
                }
            });

            applyFiltersFromURL();
        });
    </script>
@endpush
