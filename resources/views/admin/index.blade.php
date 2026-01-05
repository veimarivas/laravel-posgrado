@extends('admin.dashboard')
@section('title', 'Dashboard - Ranking de Inscripciones')
@section('admin')

    <style>
        .ranking-card {
            position: relative;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            will-change: transform;
        }

        .ranking-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.18);
            z-index: 10;
        }

        /* Efecto de brillo al hacer hover */
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

        }

        .ranking-card:hover::before {
            opacity: 1;
            left: 120%;
        }

        /* Medalla centrada y atractiva */
        .medal-display {
            font-weight: bold;
            color: #555;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Responsive */
        @media (max-width: 767px) {
            .ranking-card {
                margin-bottom: 1.2rem;
            }

            .ranking-card:hover {
                transform: translateY(-6px) scale(1.01);
            }
        }



        /* Añadir un pequeño retraso progresivo para que entren en secuencia */
        .ranking-card:nth-child(1) .animate-on-load {
            animation-delay: 0.2s;
        }

        .ranking-card:nth-child(2) .animate-on-load {
            animation-delay: 0.4s;
        }

        .ranking-card:nth-child(3) .animate-on-load {
            animation-delay: 0.6s;
        }

        /* Asegurar que no se vea antes de la animación */
        .animate-on-load {
            opacity: 0;
            transform: scale(0.5);
        }

        .medal-emoji {
            transition: transform 0.3s ease, text-shadow 0.3s ease;
            display: inline-block;
        }

        .ranking-card:hover .medal-emoji {
            transform: scale(1.2) rotate(5deg);
            text-shadow: 0 0 8px rgba(255, 215, 0, 0.7),
                0 0 16px rgba(255, 215, 0, 0.5);
        }

        /* Estilo común para tarjetas de gráficos */
        .chart-card {
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .chart-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            border-color: #d0d0d0;
        }

        /* Mejorar la legibilidad de las leyendas de Chart.js */
        .chartjs-tooltip {
            background: rgba(255, 255, 255, 0.95) !important;
            border: 1px solid #e0e0e0 !important;
            border-radius: 8px !important;
            padding: 8px !important;
            font-size: 13px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        /* Asegurar que los gráficos no se desborden */
        canvas {
            max-width: 100%;
            height: auto !important;
        }

        /* Ajustes responsivos para gráficos */
        @media (max-width: 767px) {
            .chart-card .card-body {
                padding: 0.75rem !important;
            }

            /* Altura reducida en móviles para gráfico de barras */
            #graficoBarrasSucursales {
                height: 300px !important;
            }

            /* Centrar el gráfico circular en móviles */
            .chart-card .card-body {
                justify-content: flex-start !important;
                align-items: center !important;
                padding-top: 1rem !important;
            }
        }

        .ranking-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .ranking-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.18);
        }

        .ranking-card::before {
            /* brillo */
        }

        .ranking-card:hover::before {
            opacity: 1;
            left: 120%;
        }



        .ranking-card:nth-child(1) .animate-on-load {
            animation-delay: 0.2s;
        }

        .ranking-card:nth-child(2) .animate-on-load {
            animation-delay: 0.4s;
        }

        .ranking-card:nth-child(3) .animate-on-load {
            animation-delay: 0.6s;
        }

        .medal-emoji {
            transition: transform 0.3s ease;
        }

        .ranking-card:hover .medal-emoji {
            transform: scale(1.2) rotate(5deg);
            text-shadow: 0 0 8px rgba(255, 215, 0, 0.7);
        }

        .chart-card {
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        canvas {
            max-width: 100%;
            height: auto !important;
        }

        @media (max-width: 767px) {
            /* responsive */
        }

        /* Estilo moderno para todas las tablas del dashboard */
        .ranking-table {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            background: #fff;
        }

        .ranking-table thead th {
            background: linear-gradient(to bottom, #f8f9fa, #e9ecef);
            font-weight: 600;
            color: #495057;
            padding: 1rem;
            text-align: center;
        }

        .ranking-table tbody td {
            padding: 0.9rem;
            vertical-align: middle;
        }

        .ranking-table tbody tr:hover {
            background-color: #f8f9ff;
            transition: background-color 0.2s ease;
        }

        .ranking-table.table-bordered {
            border: 1px solid #e0e0e0 !important;
        }

        .ranking-table.table-bordered th,
        .ranking-table.table-bordered td {
            border-color: #eaeaea !important;
        }

        /* Hover en medallas dentro de tablas */
        .medal-emoji {
            transition: transform 0.3s ease, text-shadow 0.3s ease;
            display: inline-block;
        }

        .medal-emoji:hover {
            transform: scale(1.2) rotate(5deg);
            text-shadow:
                0 0 8px rgba(255, 215, 0, 0.7),
                0 0 16px rgba(255, 215, 0, 0.5);
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

        .ranking-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            text-decoration: none !important;
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
        }

        .ranking-card:hover::before {
            opacity: 1;
            left: 120%;
            pointer-events: none;
        }

        /* Solo deshabilitar pointer-events en el pseudo-elemento de brillo */
        .ranking-card::before {
            pointer-events: none;
        }

        /* ===== TABLA DE RANKING GENERAL - ESTILOS MEJORADOS ===== */
        .ranking-table-container {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            background: white;
            transition: box-shadow 0.3s ease;
        }

        .ranking-table-container:hover {
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
        }

        .ranking-section-header {
            background: linear-gradient(120deg, #f5f7fa 0%, #e4e7f1 100%);
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid #e0e4f0;
        }

        .ranking-section-header h5 {
            font-weight: 700;
            color: #2d3748;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .ranking-section-header h5 i {
            color: #4361ee;
        }

        .ranking-table {
            margin-bottom: 0;
            min-width: 800px;
        }

        .ranking-table thead {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
        }

        .ranking-table thead th {
            font-weight: 600;
            padding: 1.1rem 0.9rem;
            text-align: center;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
            border: none;
            position: relative;
        }

        .ranking-table thead th:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 15%;
            bottom: 15%;
            width: 1px;
            background: rgba(255, 255, 255, 0.3);
        }

        .ranking-table tbody tr {
            border-bottom: 1px solid #edf0f7;
            transition: all 0.25s ease;
        }

        .ranking-table tbody tr:last-child {
            border-bottom: none;
        }

        .ranking-table tbody tr:hover {
            background-color: #f8fafd;
            transform: translateX(5px);
        }

        /* Estilos para los top 3 en la tabla */
        .ranking-table tbody tr.top-1 {
            background-color: rgba(255, 215, 0, 0.08);
            position: relative;
        }

        .ranking-table tbody tr.top-1::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px;
            background: linear-gradient(to bottom, #ffd700, #ffcc00);
        }

        .ranking-table tbody tr.top-2 {
            background-color: rgba(192, 192, 192, 0.08);
        }

        .ranking-table tbody tr.top-3 {
            background-color: rgba(205, 127, 50, 0.08);
        }

        .ranking-table tbody td {
            padding: 0.9rem 0.8rem;
            vertical-align: middle;
            font-size: 0.92rem;
            color: #4a5568;
        }

        .ranking-table tbody td:first-child {
            font-weight: 600;
            text-align: center;
            width: 60px;
        }

        .ranking-table tbody td:nth-child(2) {
            font-weight: 500;
            color: #2d3748;
        }

        .ranking-table tbody td:nth-child(3) {
            font-weight: 700;
            color: #2b6cb0;
        }

        .ranking-table tbody tr.top-1 td:nth-child(3),
        .ranking-table tbody tr.top-2 td:nth-child(3),
        .ranking-table tbody tr.top-3 td:nth-child(3) {
            color: #e53e3e;
        }

        .ranking-table tbody td.text-center {
            font-weight: 500;
        }

        .ranking-table.table-sm tbody td {
            padding: 0.7rem 0.6rem;
        }

        .medal-cell {
            min-width: 60px;
            font-size: 1.3rem;
            line-height: 1;
        }

        /* ===== TABLA DE RANKING POR SUCURSAL - ESTILOS MEJORADOS ===== */
        .branch-ranking-section {
            margin-top: 2rem;
        }

        .branch-header {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.2rem;
            background: linear-gradient(120deg, #f0f4ff, #e6edff);
            border-left: 4px solid #4361ee;
            margin: 1.2rem 0 0.8rem;
            border-radius: 0 8px 8px 0;
        }

        .branch-header h6 {
            margin: 0;
            font-weight: 700;
            color: #2b3c60;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .branch-header h6 i {
            color: #4361ee;
        }

        .branch-table-container {
            margin-bottom: 1.5rem;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .branch-table-container:last-child {
            margin-bottom: 0;
        }

        .subtle-header {
            background-color: #f8fafc;
            font-weight: 600;
            color: #4a5568;
        }

        /* ===== EFECTOS ADICIONALES ===== */
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
            color: #4361ee;
            font-weight: 500;
            font-size: 1.1rem;
        }

        /* ===== RESPONSIVE MEJORADO ===== */
        @media (max-width: 991px) {
            .ranking-table {
                min-width: 700px;
            }

            .ranking-table tbody tr:hover {
                transform: none;
            }
        }

        @media (max-width: 767px) {
            .ranking-table {
                min-width: 600px;
                font-size: 0.85rem;
            }

            .ranking-table thead th,
            .ranking-table tbody td {
                padding: 0.7rem 0.5rem;
            }

            .ranking-table tbody td:first-child {
                width: 50px;
            }

            .branch-header {
                padding: 0.7rem 1rem;
            }

            .ranking-section-header,
            .branch-header h6 {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .ranking-table {
                min-width: 500px;
                font-size: 0.8rem;
            }

            .ranking-section-header h5,
            .branch-header h6 {
                font-size: 0.95rem;
            }

            .medal-cell {
                font-size: 1.1rem;
            }
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Dashboard</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Inicio</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-md-12">
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label for="mes" class="form-label">Mes</label>
                    <select name="mes" id="mes" class="form-select">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == $mes ? 'selected' : '' }}>
                                {{ Carbon\Carbon::createFromDate($gestion, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="gestion" class="form-label">Gestión</label>
                    <select name="gestion" id="gestion" class="form-select">
                        @for ($g = 2020; $g <= date('Y'); $g++)
                            <option value="{{ $g }}" {{ $g == $gestion ? 'selected' : '' }}>
                                {{ $g }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="sucursal" class="form-label">Sucursal (opcional)</label>
                    <select name="sucursal" id="sucursal" class="form-select">
                        <option value="">Todas las sucursales</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}" {{ $sucursal->id == $sucursalId ? 'selected' : '' }}>
                                {{ $sucursal->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" id="applyFilters" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Título del ranking -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 id="rankingTitle">Inscripciones mes de {{ $nombreMes }} {{ $gestion }}</h5>
            <a href="{{ route('admin.dashboard') }}?mes={{ $mes }}&gestion={{ $gestion }}&view=complete"
                class="text-primary">Ir al tablero general 📊</a>
        </div>
    </div>

    <!-- Contenedores principales con ID para actualización dinámica -->
    <div id="top3-and-chart-section">
        @if ($rankingGeneralTop3->isEmpty())
            <div class="col-12 text-center">
                <p class="text-muted">No hay inscripciones registradas para este período.</p>
            </div>
        @else
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="row g-4 justify-content-center" id="top3-container">
                        @foreach ($rankingGeneralTop3 as $index => $persona)
                            @php
                                $gradients = [
                                    'gold' => 'linear-gradient(135deg, #fff9c4, #ffecb3)',
                                    'silver' => 'linear-gradient(135deg, #f5f5f5, #e0e0e0)',
                                    'bronze' => 'linear-gradient(135deg, #ffe0b2, #d7ccc8)',
                                ];
                                $borders = ['gold' => '#FFD700', 'silver' => '#C0C0C0', 'bronze' => '#CD7F32'];
                                $rank = $index + 1;
                                $medalType = $index === 0 ? 'gold' : ($index === 1 ? 'silver' : 'bronze');
                                $gradient = $gradients[$medalType];
                                $borderColor = $borders[$medalType];
                                $medalEmoji = match ($index) {
                                    0 => '🥇',
                                    1 => '🥈',
                                    2 => '🥉',
                                };
                            @endphp
                            <div class="col-md-4">
                                <a href="{{ route('admin.vendedor.inscripciones', ['personaId' => $persona->id]) }}"
                                    class="ranking-card position-relative overflow-hidden rounded-4 h-100 d-block text-decoration-none text-dark"
                                    style="background: {{ $gradient }}; border: 2px solid {{ $borderColor }}; box-shadow: 0 4px 12px rgba(0,0,0,0.08); cursor: pointer;">
                                    <div class="card-body text-center py-4 px-3">
                                        <div class="position-relative d-inline-block mb-2">
                                            <img src="{{ $persona->avatar }}" ... onerror="...">
                                        </div>
                                        <div class="medal-display my-2 animate-on-load">
                                            <span class="h2 medal-emoji">{{ $medalEmoji }}</span>
                                        </div>
                                        <h5 class="mb-1 fw-bold">{{ $persona->nombre_completo }}</h5>
                                        <p class="text-muted small mb-2">{{ $persona->total_inscripciones }} inscripciones
                                        </p>
                                        <div class="mt-2 small">
                                            <small class="text-muted d-block mb-1">Desglose:</small>
                                            @foreach ($tipos as $tipoNombre)
                                                <div class="d-flex justify-content-between px-2">
                                                    <span>{{ $tipoNombre }}:</span>
                                                    <strong>{{ $persona->desglose[$tipoNombre] ?? 0 }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </a>

                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="chart-card rounded-4 shadow-sm h-100 d-flex flex-column"
                        style="background: linear-gradient(to bottom, #ffffff, #fafafa); border: 1px solid #eaeaea;">
                        <div class="card-header border-0 py-3 text-center">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="ri-pie-chart-2-line me-1 text-primary"></i> Distribución por Tipo
                            </h6>
                            <small class="text-muted">{{ $nombreMes }} {{ $gestion }}</small>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center flex-grow-1 p-3">
                            <div style="width: 100%; max-width: 280px; height: 280px;">
                                <canvas id="graficoTipos"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div id="bar-chart-section">
        @if (!empty($graficoBarrasData['sucursales']))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card chart-card rounded-4 shadow-sm"
                        style="background: linear-gradient(to bottom, #ffffff, #fafafa); border: 1px solid #eaeaea;">
                        <div class="card-header border-0 py-3">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="ri-bar-chart-horizontal-line me-1 text-primary"></i> Inscripciones por Sucursal y
                                Tipo
                            </h6>
                            <small class="text-muted">{{ $nombreMes }} {{ $gestion }}</small>
                        </div>
                        <div class="card-body p-3">
                            <div style="height: 380px; width: 100%;">
                                <canvas id="graficoBarrasSucursales"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        <i class="ri-bar-chart-line"></i> No hay datos suficientes...
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div id="full-ranking-section" class="ranking-table-container">
        <div class="ranking-section-header">
            <h5><i class="ri-trophy-line"></i> Ranking General con Desglose</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover ranking-table">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Persona</th>
                            <th>Total</th>
                            @foreach ($tipos as $tipoNombre)
                                <th>{{ $tipoNombre }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rankingGeneralCompleto as $index => $persona)
                            @php
                                $medalEmoji = match ($index) {
                                    0 => '🥇',
                                    1 => '🥈',
                                    2 => '🥉',
                                    default => null,
                                };
                                $rowClass = $index < 3 ? 'top-' . ($index + 1) : '';
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td class="text-center align-middle">
                                    @if ($medalEmoji)
                                        <div class="medal-cell">{{ $medalEmoji }}</div>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </td>
                                <td class="align-middle">{{ $persona->nombre_completo }}</td>
                                <td class="text-center align-middle"><strong>{{ $persona->total_inscripciones }}</strong>
                                </td>
                                @foreach ($tipos as $tipoNombre)
                                    <td class="text-center align-middle">{{ $persona->desglose[$tipoNombre] ?? 0 }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="branch-ranking-section" class="branch-ranking-section">
        <div class="ranking-section-header">
            <h5><i class="ri-building-line"></i> Ranking por Sucursal</h5>
        </div>
        <div class="card-body">
            @if ($rankingPorSucursal->isEmpty())
                <div class="text-center py-5">
                    <i class="ri-emotion-sad-line" style="font-size: 3rem; color: #cbd5e0;"></i>
                    <p class="text-muted mt-3" style="font-size: 1.1rem;">No hay datos disponibles para mostrar en este
                        período.</p>
                </div>
            @else
                @foreach ($rankingPorSucursal as $sucursalNombre => $personas)
                    <div class="branch-table-container">
                        <div class="branch-header">
                            <h6><i class="ri-map-pin-2-line"></i> {{ $sucursalNombre }}</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered ranking-table">
                                <thead class="subtle-header">
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Persona</th>
                                        <th>Total</th>
                                        @foreach ($tipos as $tipoNombre)
                                            <th>{{ $tipoNombre }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($personas as $index => $persona)
                                        @php
                                            $medalEmoji = match ($index) {
                                                0 => '🥇',
                                                1 => '🥈',
                                                2 => '🥉',
                                                default => null,
                                            };
                                            $rowClass = $index < 3 ? 'top-' . ($index + 1) : '';
                                        @endphp
                                        <tr class="{{ $rowClass }}">
                                            <td class="text-center align-middle">
                                                @if ($medalEmoji)
                                                    <div class="medal-cell">{{ $medalEmoji }}</div>
                                                @else
                                                    {{ $index + 1 }}
                                                @endif
                                            </td>
                                            <td class="align-middle">{{ $persona->nombre_completo }}</td>
                                            <td class="text-center align-middle">
                                                <strong>{{ $persona->total_inscripciones }}</strong>
                                            </td>
                                            @foreach ($tipos as $tipoNombre)
                                                <td class="text-center align-middle">
                                                    {{ $persona->desglose[$tipoNombre] ?? 0 }}</td>
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

            // Inicializar gráficos desde la primera carga
            let pieChart = null;
            let barChart = null;

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
                    sucursal: urlParams.get('sucursal') || ''
                };
            }

            function applyFiltersFromURL() {
                const filters = getFiltersFromURL();
                document.getElementById('mes').value = filters.mes;
                document.getElementById('gestion').value = filters.gestion;
                document.getElementById('sucursal').value = filters.sucursal || '';
                updateDashboard(filters);
            }

            function updateDashboard(filters) {
                // Actualizar URL
                updateURL(filters);
                // Mostrar loading
                document.getElementById('top3-and-chart-section').innerHTML = loadingIndicator;
                document.getElementById('bar-chart-section').innerHTML = loadingIndicator;
                document.getElementById('full-ranking-section').innerHTML = loadingIndicator;
                document.getElementById('branch-ranking-section').innerHTML = loadingIndicator;

                axios.get("{{ route('admin.dashboard.data') }}", {
                        params: filters
                    })
                    .then(response => {
                        const data = response.data;
                        const tipoNombres = Object.values(data.tipos); // ✅ Corrección clave

                        // 1. Top 3
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
                            <div class="chart-card rounded-4 shadow-sm h-100 d-flex flex-column"
                                style="background: linear-gradient(to bottom, #ffffff, #fafafa); border: 1px solid #eaeaea;">
                                <div class="card-header border-0 py-3 text-center">
                                    <h6 class="fw-bold text-dark mb-0">
                                        <i class="ri-pie-chart-2-line me-1 text-primary"></i> Distribución por Tipo
                                    </h6>
                                    <small class="text-muted">${data.nombreMes} ${data.gestion}</small>
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center flex-grow-1 p-3">
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
                            // 👇 Mensaje amigable cuando no hay datos para el Top 3
                            document.getElementById('top3-and-chart-section').innerHTML = `
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="ri-information-line me-2"></i> No hay inscripciones registradas para este período.
                            </div>
                        </div>
                    </div>
                `;
                        }

                        // 2. Gráficos
                        updatePieChart(data.graficoPorTipo);
                        updateBarChart(data.graficoBarrasData, data.nombreMes, data.gestion);

                        // 3. Tablas
                        if (data.rankingGeneralCompleto && data.rankingGeneralCompleto.length > 0) {
                            document.getElementById('full-ranking-section').innerHTML = `
            <div class="ranking-table-container">
                <div class="ranking-section-header">
                    <h5><i class="ri-trophy-line"></i> Ranking General con Desglose</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover ranking-table">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 30%;">Persona</th>
                                    <th style="width: 10%;">Total</th>
                                    ${tipoNombres.map(t => `<th style="width: 15%;">${t}</th>`).join('')}
                                </tr>
                            </thead>
                            <tbody>
                                ${renderFullRanking(data.rankingGeneralCompleto, tipoNombres)}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
                        } else {
                            document.getElementById('full-ranking-section').innerHTML = `
                <div class="ranking-table-container">
                    <div class="ranking-section-header">
                        <h5><i class="ri-trophy-line"></i> Ranking General con Desglose</h5>
                    </div>
                    <div class="card-body text-center py-5">
                        <i class="ri-emotion-sad-line" style="font-size: 3rem; color: #cbd5e0;"></i>
                        <p class="text-muted mt-3" style="font-size: 1.1rem;">No hay inscripciones registradas para este período.</p>
                    </div>
                </div>
            `;
                        }

                        if (data.rankingPorSucursal && Object.keys(data.rankingPorSucursal).length > 0) {
                            document.getElementById('branch-ranking-section').innerHTML = `
                <div class="branch-ranking-section">
                    <div class="ranking-section-header">
                        <h5><i class="ri-building-line"></i> Ranking por Sucursal</h5>
                    </div>
                    <div class="card-body">
                        ${renderBranchRanking(data.rankingPorSucursal, tipoNombres)}
                    </div>
                </div>
            `;
                        } else {
                            document.getElementById('branch-ranking-section').innerHTML = `
                <div class="branch-ranking-section">
                    <div class="ranking-section-header">
                        <h5><i class="ri-building-line"></i> Ranking por Sucursal</h5>
                    </div>
                    <div class="card-body text-center py-5">
                        <i class="ri-emotion-sad-line" style="font-size: 3rem; color: #cbd5e0;"></i>
                        <p class="text-muted mt-3" style="font-size: 1.1rem;">No hay datos disponibles para mostrar en este período.</p>
                    </div>
                </div>
            `;
                        }

                    })
                    .catch(error => {
                        // 👇 Solo mostrar alerta si realmente hay un error (HTTP 500, 404, timeout, etc.)
                        console.error('Error en la petición AJAX:', error);
                        // Opcional: Mostrar un mensaje más específico en la UI
                        document.getElementById('top3-and-chart-section').innerHTML = `
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-danger text-center">
                            <i class="ri-error-warning-line me-2"></i> No existen datos para mostrar.
                        </div>
                    </div>
                </div>
            `;
                        document.getElementById('bar-chart-section').innerHTML = `
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-danger text-center">
                            <i class="ri-error-warning-line me-2"></i> No existen datos para mostrar.
                        </div>
                    </div>
                </div>
            `;
                        document.getElementById('full-ranking-section').innerHTML = `
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="text-center">Ranking General con Desglose</h5>
                    </div>
                    <div class="card-body text-center">
                        <p class="text-muted"><i class="ri-error-warning-line me-2"></i> No existen datos para mostrar.</p>
                    </div>
                </div>
            `;
                        document.getElementById('branch-ranking-section').innerHTML = `
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0 text-center">Ranking por Sucursal</h5>
                            </div>
                            <div class="card-body text-center">
                                <p class="text-muted"><i class="ri-error-warning-line me-2"></i> No existen datos para mostrar.</p>
                            </div>
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

                const html = ranking.slice(0, 3).map((persona, index) => `
<div class="col-md-4">
    <a href="/admin/vendedor/inscripciones/${persona.id}" 
       class="ranking-card position-relative overflow-hidden rounded-4 h-100 d-block text-decoration-none"
       style="background: ${gradients[index]}; border: 2px solid ${borders[index]}; box-shadow: 0 4px 12px rgba(0,0,0,0.08); cursor: pointer; display: block;">
        <div class="card-body text-center py-4 px-3">
            <div class="position-relative d-inline-block mb-2">
                <img src="${persona.avatar || '{{ asset('images/default-avatar.png') }}'}"
                     alt="${persona.nombre_completo}"
                     class="rounded-circle border"
                     style="width: 90px; height: 90px; object-fit: cover; border: 3px solid ${borders[index]};">
            </div>
            <div class="medal-display my-2">
                <span class="h2 medal-emoji animate-on-load" data-index="${index}">${medalEmojis[index]}</span>
            </div>
            <h5 class="mb-1 fw-bold text-dark">${persona.nombre_completo}</h5>
            <p class="text-muted small mb-2">${persona.total_inscripciones} inscripciones</p>
            <div class="mt-2 small">
                <small class="text-muted d-block mb-1">Desglose:</small>
                ${tipoNombres.map(tipo => 
                    `<div class="d-flex justify-content-between px-2">
                                                                                                    <span>${tipo}:</span>
                                                                                                    <strong>${persona.desglose[tipo] ?? 0}</strong>
                                                                                                </div>`
                ).join('')}
            </div>
        </div>
    </a>
</div>
`).join('');

                // Devolver el HTML + una función para activar animaciones después
                return {
                    html,
                    activateAnimations: function() {
                        // Forzar reflow y reiniciar animación
                        setTimeout(() => {
                            document.querySelectorAll('.animate-on-load').forEach((el, i) => {
                                el.style.animation = 'none';
                                el.offsetHeight; // Trigger reflow
                                el.style.animation = null;
                                el.style.animationDelay = `${0.2 + (i * 0.2)}s`;
                            });
                        }, 50);
                    }
                };
            }

            function renderFullRanking(ranking, tipoNombres) {
                return ranking.map((persona, idx) => {
                    let medalHtml = '';
                    if (idx === 0) medalHtml = '<span class="h5 medal-emoji">🥇</span>';
                    else if (idx === 1) medalHtml = '<span class="h5 medal-emoji">🥈</span>';
                    else if (idx === 2) medalHtml = '<span class="h5 medal-emoji">🥉</span>';

                    return `
            <tr>
                <td class="text-center" style="width: 5%;">${medalHtml || (idx + 1)}</td>
                <td style="width: 30%;">${persona.nombre_completo}</td>
                <td class="text-center" style="width: 10%;"><strong>${persona.total_inscripciones}</strong></td>
                ${tipoNombres.map(tipo => 
                    `<td class="text-center" style="width: 15%;">${persona.desglose[tipo] ?? 0}</td>`
                ).join('')}
            </tr>
        `;
                }).join('');
            }

            function renderBranchRanking(data, tipoNombres) {
                if (Object.keys(data).length === 0) {
                    return '<p class="text-center text-muted">No hay datos disponibles.</p>';
                }

                return Object.entries(data).map(([sucursal, personas]) => {
                    const tableRows = personas.map((persona, idx) => {
                        let medalHtml = '';
                        if (idx === 0) medalHtml = '<span class="h5 medal-emoji">🥇</span>';
                        else if (idx === 1) medalHtml = '<span class="h5 medal-emoji">🥈</span>';
                        else if (idx === 2) medalHtml = '<span class="h5 medal-emoji">🥉</span>';

                        return `
                <tr>
                    <td class="text-center" style="width: 5%;">${medalHtml || (idx + 1)}</td>
                    <td style="width: 30%;">${persona.nombre_completo}</td>
                    <td class="text-center" style="width: 10%;"><strong>${persona.total_inscripciones}</strong></td>
                    ${tipoNombres.map(tipo => 
                        `<td class="text-center" style="width: 15%;">${persona.desglose[tipo] ?? 0}</td>`
                    ).join('')}
                </tr>
            `;
                    }).join('');

                    return `
            <div class="branch-table-container">
                <div class="branch-header">
                    <h6><i class="ri-map-pin-2-line"></i> ${sucursal}</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered ranking-table">
                        <thead class="subtle-header">
                            <tr class="text-center">
                                <th style="width: 5%;">#</th>
                                <th style="width: 30%;">Persona</th>
                                <th style="width: 10%;">Total</th>
                                ${tipoNombres.map(t => `<th style="width: 15%;">${t}</th>`).join('')}
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
                const ctx = document.getElementById('graficoTipos').getContext('2d');
                if (pieChart) pieChart.destroy();
                const labels = Object.keys(data);
                const values = Object.values(data);
                const backgroundColors = ['#4361ee', '#3f37c9', '#4cc9f0', '#4895ef', '#560bad', '#7209b7',
                    '#f72585', '#b5179e', '#3a0ca3', '#560bad'
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

                // Verificar si data es válido y tiene las propiedades necesarias
                if (!data || !Array.isArray(data.sucursales) || data.sucursales.length === 0) {
                    container.innerHTML = `
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        <i class="ri-bar-chart-line"></i> No hay datos suficientes...
                    </div>
                </div>
            </div>
        `;
                    return;
                }

                // Renderizar el contenedor del gráfico
                container.innerHTML = `
        <div class="row mb-4">
            <div class="col-12">
                <div class="card chart-card rounded-4 shadow-sm"
                    style="background: linear-gradient(to bottom, #ffffff, #fafafa); border: 1px solid #eaeaea;">
                    <div class="card-header border-0 py-3">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="ri-bar-chart-horizontal-line me-1 text-primary"></i> Inscripciones por Sucursal y Tipo
                        </h6>
                        <small class="text-muted">${nombreMes} ${gestion}</small>
                    </div>
                    <div class="card-body p-3">
                        <div style="height: 380px; width: 100%;">
                            <canvas id="graficoBarrasSucursales"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

                const ctx = document.getElementById('graficoBarrasSucursales').getContext('2d');
                if (barChart) barChart.destroy();

                const sucursales = data.sucursales;
                const tipos = data.tipos;
                const valores = data.valores;

                // Asegurar que tipos y valores sean válidos
                if (!Array.isArray(tipos) || tipos.length === 0) {
                    console.warn("No hay tipos definidos para el gráfico.");
                    return;
                }

                const coloresBase = ['#4361ee', '#3f37c9', '#4cc9f0', '#4895ef', '#560bad', '#7209b7', '#f72585',
                    '#b5179e', '#3a0ca3', '#560bad'
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

            // Eventos
            applyBtn.addEventListener('click', () => {
                const formData = new FormData(filterForm);
                const filters = Object.fromEntries(formData);
                updateDashboard(filters);
            });

            // Soporte para botón "atrás" del navegador
            window.addEventListener('popstate', (event) => {
                if (event.state) {
                    applyFiltersFromURL();
                }
            });

            // Inicializar desde URL al cargar
            applyFiltersFromURL();
        });
    </script>
@endpush
