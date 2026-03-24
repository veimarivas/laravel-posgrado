@extends('admin.dashboard')
@section('admin')

    @php
        $simbolo = ['BS' => 'Bs', 'USD' => '$', 'EUR' => '€'][$cuenta->moneda] ?? $cuenta->moneda;
        $bancoColor = $cuenta->banco->color ?? '#405189';
        $tiposPagoConfig = [
            'Efectivo' => ['label' => 'Efectivo', 'color' => 'success', 'icon' => 'ri-money-dollar-circle-line'],
            'Transferencia' => ['label' => 'Transferencia', 'color' => 'info', 'icon' => 'ri-bank-transfer-line'],
            'Depósito' => ['label' => 'Depósito', 'color' => 'primary', 'icon' => 'ri-bank-card-2-line'],
            'Tarjeta' => ['label' => 'Tarjeta', 'color' => 'warning', 'icon' => 'ri-bank-card-line'],
        ];
    @endphp

    <style>
        .cb-hero {
            background: linear-gradient(135deg, {{ $bancoColor }}ee 0%, {{ $bancoColor }}99 100%);
            border-radius: 12px;
            color: #fff;
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .cb-hero::before {
            content: '';
            position: absolute;
            right: -40px;
            top: -40px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
        }

        .cb-hero::after {
            content: '';
            position: absolute;
            right: 60px;
            bottom: -60px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
        }

        .cb-hero .saldo-value {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -.5px;
        }

        .cb-hero .saldo-label {
            font-size: .78rem;
            opacity: .8;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .cb-hero .divider-v {
            width: 1px;
            background: rgba(255, 255, 255, .25);
            align-self: stretch;
        }

        .stat-card {
            border-radius: 10px;
            border: 1px solid #e9ebec;
            transition: box-shadow .2s;
            height: 100%;
        }

        .stat-card:hover {
            box-shadow: 0 4px 18px rgba(0, 0, 0, .08);
        }

        .stat-card .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .stat-card .stat-value {
            font-size: 1.3rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .stat-card .stat-label {
            font-size: .75rem;
            color: #878a99;
        }

        .tipo-card {
            border-radius: 10px;
            border-left: 4px solid;
            background: #fff;
            padding: .9rem 1rem;
            height: 100%;
        }

        .tipo-card .tc-amount {
            font-size: 1.15rem;
            font-weight: 700;
        }

        .tipo-card .tc-label {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #878a99;
        }

        .tipo-card .tc-count {
            font-size: .78rem;
            color: #878a99;
        }

        .nav-pills-custom .nav-link {
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 500;
            font-size: .875rem;
            color: #495057;
            border: 1px solid transparent;
        }

        .nav-pills-custom .nav-link.active {
            background: #405189;
            color: #fff;
            border-color: #405189;
        }

        .nav-pills-custom .nav-link:not(.active):hover {
            background: #f3f6f9;
            border-color: #dee2e6;
        }

        .info-row {
            display: flex;
            gap: .5rem;
            padding: .55rem 0;
            border-bottom: 1px solid #f0f0f0;
            align-items: center;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row .info-label {
            font-size: .78rem;
            color: #878a99;
            min-width: 130px;
        }

        .info-row .info-value {
            font-size: .875rem;
            font-weight: 500;
        }

        .table-modern thead th {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #878a99;
            font-weight: 600;
            border-bottom: 2px solid #e9ebec;
            padding: .65rem .75rem;
            background: #f8f9fa;
        }

        .table-modern tbody td {
            padding: .65rem .75rem;
            font-size: .875rem;
            vertical-align: middle;
        }

        .table-modern tbody tr:hover {
            background: #f8faff;
        }

        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-state .empty-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.6rem;
        }

        .badge-tipo {
            font-size: .72rem;
            padding: .3em .65em;
            border-radius: 5px;
            font-weight: 600;
        }

        /* Garantizar legibilidad en badges sólidos */
        .badge.bg-warning  { color: #5a3e00 !important; }
        .badge.bg-light    { color: #343a40 !important; }

        .timeline-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 5px;
        }
    </style>

    <div class="container-fluid">

        {{-- ───────────────────────── BREADCRUMB ───────────────────────── --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.cuentas-bancarias.listar') }}" class="btn btn-light btn-sm">
                    <i class="ri-arrow-left-line"></i>
                </a>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.cuentas-bancarias.listar') }}">Cuentas
                                Bancarias</a></li>
                        <li class="breadcrumb-item active">Detalle</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                @if (Auth::guard('web')->user()->can('cuentas-bancarias.editar'))
                    <button class="btn btn-sm btn-warning editBtn" data-bs-toggle="modal" data-bs-target="#modalModificar"
                        data-bs-obj='@json($cuenta)'>
                        <i class="ri-edit-line me-1"></i>Editar
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('cuentas-bancarias.transferir'))
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTransferencia"
                        data-cuenta-id="{{ $cuenta->id }}">
                        <i class="ri-exchange-line me-1"></i>Transferir
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('cuentas-bancarias.eliminar'))
                    <button class="btn btn-sm btn-outline-danger deleteBtn" data-bs-toggle="modal"
                        data-bs-target="#modalEliminar" data-bs-obj='@json($cuenta)'>
                        <i class="ri-delete-bin-line"></i>
                    </button>
                @endif
            </div>
        </div>

        {{-- ───────────────────────── HERO CARD ───────────────────────── --}}
        <div class="cb-hero mb-4">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                {{-- Logo/Ícono banco --}}
                <div class="flex-shrink-0">
                    @if (isset($cuenta->banco->logo) && $cuenta->banco->logo)
                        <img src="{{ $cuenta->banco->logo }}" alt="Logo"
                            style="width:56px;height:56px;object-fit:contain;background:#fff;border-radius:10px;padding:6px;">
                    @else
                        <div
                            style="width:56px;height:56px;background:rgba(255,255,255,.2);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="ri-bank-line" style="font-size:1.8rem;"></i>
                        </div>
                    @endif
                </div>
                {{-- Banco + cuenta --}}
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h5 class="mb-0 text-white fw-bold">{{ $cuenta->banco->nombre ?? 'Sin banco' }}</h5>
                        @if ($cuenta->activa)
                            <span class="badge" style="background:rgba(255,255,255,.25);font-size:.72rem;">Activa</span>
                        @else
                            <span class="badge" style="background:rgba(0,0,0,.25);font-size:.72rem;">Inactiva</span>
                        @endif
                    </div>
                    <div class="d-flex gap-3 flex-wrap" style="opacity:.9;font-size:.85rem;">
                        <span><i class="ri-bank-card-line me-1"></i>Nº {{ $cuenta->numero_cuenta }}</span>
                        <span><i class="ri-building-line me-1"></i>{{ $cuenta->sucursal->nombre ?? 'Sin sucursal' }}</span>
                        <span><i class="ri-money-dollar-circle-line me-1"></i>{{ $cuenta->moneda }}
                            @php
                                $tipos = [
                                    'ahorro' => 'Ahorro',
                                    'corriente' => 'Corriente',
                                    'moneda_extranjera' => 'Moneda Extranjera',
                                ];
                            @endphp
                            · {{ $tipos[$cuenta->tipo_cuenta] ?? $cuenta->tipo_cuenta }}
                        </span>
                    </div>
                </div>
                {{-- Saldo --}}
                <div class="divider-v d-none d-md-block mx-2"></div>
                <div class="text-end">
                    <div class="saldo-label">Saldo Actual</div>
                    <div class="saldo-value">{{ $simbolo }} {{ number_format($cuenta->saldo_actual, 2) }}</div>
                    <div style="font-size:.78rem;opacity:.8;">Inicial: {{ $simbolo }}
                        {{ number_format($cuenta->saldo_inicial, 2) }}</div>
                </div>
            </div>
        </div>

        {{-- ───────────────────────── TARJETAS STATS ───────────────────────── --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-sm-6">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="ri-money-dollar-circle-line"></i>
                        </div>
                        <div>
                            <div class="stat-label">Total Recaudado</div>
                            <div class="stat-value text-success">{{ $simbolo }}
                                {{ number_format($totalDepositado, 2) }}</div>
                            <div class="stat-label mt-1">{{ $totalPagos }} pago{{ $totalPagos != 1 ? 's' : '' }}
                                registrado{{ $totalPagos != 1 ? 's' : '' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="ri-check-double-line"></i>
                        </div>
                        <div>
                            <div class="stat-label">Saldo Conciliado</div>
                            <div class="stat-value text-info">{{ $simbolo }}
                                {{ number_format($cuenta->saldoConciliado(), 2) }}</div>
                            <div class="stat-label mt-1">Última conciliación</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="ri-exchange-line"></i>
                        </div>
                        <div>
                            <div class="stat-label">Transferencias</div>
                            <div class="stat-value">
                                {{ $transferenciasEnviadas->count() + $transferenciasRecibidas->count() }}</div>
                            <div class="stat-label mt-1">
                                <span class="text-danger">↑ {{ $transferenciasEnviadas->count() }} env.</span>
                                &nbsp;<span class="text-success">↓ {{ $transferenciasRecibidas->count() }} rec.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="ri-bank-card-2-line"></i>
                        </div>
                        <div>
                            <div class="stat-label">Depósitos de Caja</div>
                            <div class="stat-value">{{ $depositos->count() }}</div>
                            <div class="stat-label mt-1">{{ $simbolo }}
                                {{ number_format($depositos->sum('monto'), 2) }} total</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ───────────────────────── TABS ───────────────────────── --}}
        <div class="card">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <ul class="nav nav-pills-custom gap-1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-info" role="tab">
                            <i class="ri-information-line me-1"></i>Información
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-pagos" role="tab">
                            <i class="ri-money-dollar-box-line me-1"></i>Pagos
                            <span class="badge bg-primary ms-1" style="font-size:.68rem;">{{ $totalPagos }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-movimientos" role="tab">
                            <i class="ri-exchange-dollar-line me-1"></i>Movimientos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-transferencias" role="tab">
                            <i class="ri-swap-line me-1"></i>Transferencias
                            <span class="badge bg-secondary ms-1" style="font-size:.68rem;">
                                {{ $transferenciasEnviadas->count() + $transferenciasRecibidas->count() }}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-depositos" role="tab">
                            <i class="ri-bank-card-2-line me-1"></i>Depósitos
                            <span class="badge bg-secondary ms-1"
                                style="font-size:.68rem;">{{ $depositos->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-conciliaciones" role="tab">
                            <i class="ri-file-check-line me-1"></i>Conciliaciones
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body tab-content p-4">

                {{-- ══════════ TAB INFORMACIÓN ══════════ --}}
                <div class="tab-pane active" id="tab-info" role="tabpanel">
                    <div class="row g-4">
                        {{-- Datos de la cuenta --}}
                        <div class="col-lg-7">
                            <h6 class="text-uppercase text-muted small fw-semibold mb-3">
                                <i class="ri-information-line me-1"></i>Datos de la Cuenta
                            </h6>
                            <div class="info-row">
                                <span class="info-label"><i class="ri-bank-line me-2 text-muted"></i>Banco</span>
                                <span class="info-value">
                                    <span class="d-inline-block me-2"
                                        style="width:10px;height:10px;border-radius:50%;background:{{ $bancoColor }};"></span>
                                    {{ $cuenta->banco->nombre ?? '—' }}
                                    @if ($cuenta->banco->codigo ?? false)
                                        <small class="text-muted">({{ $cuenta->banco->codigo }})</small>
                                    @endif
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label"><i class="ri-hashtag me-2 text-muted"></i>N° Cuenta</span>
                                <span class="info-value font-monospace">{{ $cuenta->numero_cuenta }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label"><i class="ri-building-line me-2 text-muted"></i>Sucursal</span>
                                <span class="info-value">
                                    {{ $cuenta->sucursal->nombre ?? '—' }}
                                    @if ($cuenta->sucursal && isset($cuenta->sucursal->sede))
                                        <small class="text-muted d-block">Sede:
                                            {{ $cuenta->sucursal->sede->nombre ?? '' }}</small>
                                    @endif
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label"><i class="ri-file-list-line me-2 text-muted"></i>Tipo de
                                    Cuenta</span>
                                <span class="info-value">
                                    <span class="badge bg-info bg-opacity-15 text-info">
                                        {{ $tipos[$cuenta->tipo_cuenta] ?? $cuenta->tipo_cuenta }}
                                    </span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label"><i class="ri-currency-line me-2 text-muted"></i>Moneda</span>
                                <span class="info-value">
                                    <span class="badge bg-light text-dark border">{{ $simbolo }} —
                                        {{ $cuenta->moneda }}</span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label"><i class="ri-money-dollar-circle-line me-2 text-muted"></i>Saldo
                                    Inicial</span>
                                <span class="info-value">{{ $simbolo }}
                                    {{ number_format($cuenta->saldo_inicial, 2) }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label"><i class="ri-toggle-line me-2 text-muted"></i>Estado</span>
                                <span class="info-value">
                                    @if ($cuenta->activa)
                                        <span class="badge bg-success"><i
                                                class="ri-checkbox-circle-line me-1"></i>Activa</span>
                                    @else
                                        <span class="badge bg-secondary">Inactiva</span>
                                    @endif
                                </span>
                            </div>
                            @if ($cuenta->descripcion)
                                <div class="info-row">
                                    <span class="info-label"><i
                                            class="ri-file-text-line me-2 text-muted"></i>Descripción</span>
                                    <span class="info-value text-muted">{{ $cuenta->descripcion }}</span>
                                </div>
                            @endif
                            <div class="info-row">
                                <span class="info-label"><i class="ri-calendar-line me-2 text-muted"></i>Creada</span>
                                <span class="info-value small text-muted">
                                    {{ $cuenta->created_at ? $cuenta->created_at->format('d/m/Y H:i') : '—' }}
                                </span>
                            </div>
                        </div>

                        {{-- Actividad por mes --}}
                        <div class="col-lg-5">
                            <h6 class="text-uppercase text-muted small fw-semibold mb-3">
                                <i class="ri-bar-chart-line me-1"></i>Actividad por Mes
                            </h6>
                            @if ($movimientosPorMes->count() > 0)
                                @php $maxTotal = $movimientosPorMes->max('total') ?: 1; @endphp
                                <div class="d-flex flex-column gap-2">
                                    @foreach ($movimientosPorMes->take(6) as $est)
                                        @php
                                            $pct = min(100, round((abs($est->total) / abs($maxTotal)) * 100));
                                            $mesLabel = \Carbon\Carbon::createFromFormat(
                                                'Y-m',
                                                $est->mes,
                                            )->translatedFormat('M Y');
                                        @endphp
                                        <div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="small">{{ $mesLabel }}</span>
                                                <span class="small fw-medium">
                                                    {{ $simbolo }} {{ number_format($est->total, 2) }}
                                                    <small class="text-muted">({{ $est->cantidad }})</small>
                                                </span>
                                            </div>
                                            <div class="progress" style="height:6px;border-radius:3px;">
                                                <div class="progress-bar bg-primary" style="width:{{ $pct }}%">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-icon bg-light text-muted"><i class="ri-bar-chart-line"></i></div>
                                    <p class="text-muted small mb-0">Sin actividad registrada</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ══════════ TAB PAGOS ══════════ --}}
                <div class="tab-pane" id="tab-pagos" role="tabpanel">

                    {{-- Tarjetas por tipo de pago --}}
                    @if ($totalesPorTipo->count() > 0)
                        <div class="row g-3 mb-4">
                            @foreach ($tiposPagoConfig as $tipo => $cfg)
                                @php $dato = $totalesPorTipo->get($tipo); @endphp
                                @if ($dato)
                                    <div class="col-xl-3 col-sm-6">
                                        <div class="tipo-card border-{{ $cfg['color'] }}">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <i class="{{ $cfg['icon'] }} text-{{ $cfg['color'] }} fs-5"></i>
                                                <span class="tc-label">{{ $cfg['label'] }}</span>
                                            </div>
                                            <div class="tc-amount text-{{ $cfg['color'] }}">
                                                {{ $simbolo }} {{ number_format($dato->total, 2) }}
                                            </div>
                                            <div class="tc-count">{{ $dato->cantidad }}
                                                pago{{ $dato->cantidad != 1 ? 's' : '' }}</div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            {{-- Total general --}}
                            <div class="col-xl-3 col-sm-6">
                                <div class="tipo-card border-dark"
                                    style="border-left-color:#343a40!important;background:#f8f9fa;">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="ri-pie-chart-2-line text-dark fs-5"></i>
                                        <span class="tc-label">Total General</span>
                                    </div>
                                    <div class="tc-amount text-dark">
                                        {{ $simbolo }} {{ number_format($totalDepositado, 2) }}
                                    </div>
                                    <div class="tc-count">{{ $totalPagos }} pago{{ $totalPagos != 1 ? 's' : '' }} en
                                        total</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Tabla de pagos --}}
                    @if ($pagosRecientes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Recibo</th>
                                        <th>Estudiante</th>
                                        <th>Programa</th>
                                        <th class="text-end">Monto</th>
                                        <th>N° Comprobante</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pagosRecientes as $pago)
                                        @php
                                            $tipoCfg = $tiposPagoConfig[$pago->tipo_pago] ?? [
                                                'label' => $pago->tipo_pago,
                                                'color' => 'secondary',
                                                'icon' => 'ri-question-line',
                                            ];
                                            $estadosPago = [
                                                'completado' => 'success',
                                                'registrado' => 'primary',
                                                'depositado' => 'info',
                                                'conciliado' => 'teal',
                                                'anulado' => 'danger',
                                            ];
                                            $estadoColor = $estadosPago[$pago->estado] ?? 'secondary';
                                        @endphp
                                        <tr>
                                            <td>
                                                <span
                                                    class="fw-medium">{{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : '—' }}</span>
                                            </td>
                                            <td>
                                                <code class="bg-light px-2 py-1 rounded small">{{ $pago->recibo }}</code>
                                            </td>
                                            <td>
                                                @if (isset($pago->estudiante->persona))
                                                    <span class="fw-medium">{{ $pago->estudiante->persona->nombres }}
                                                        {{ $pago->estudiante->persona->apellido_paterno }}</span>
                                                    <br><small
                                                        class="text-muted">{{ $pago->estudiante->persona->carnet }}</small>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($pago->inscripcion->ofertaAcademica->programa))
                                                    <small>{{ $pago->inscripcion->ofertaAcademica->programa->nombre }}</small>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <span class="fw-bold text-success">{{ $simbolo }}
                                                    {{ number_format($pago->pago_bs, 2) }}</span>
                                                @if ($pago->descuento_bs > 0)
                                                    <br><small class="text-muted">Desc: -{{ $simbolo }}
                                                        {{ number_format($pago->descuento_bs, 2) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($pago->n_comprobante)
                                                    <span
                                                        class="badge bg-light text-dark border">{{ $pago->n_comprobante }}</span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-tipo bg-{{ $tipoCfg['color'] }} text-white">
                                                    <i class="{{ $tipoCfg['icon'] }} me-1"></i>{{ $tipoCfg['label'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $estadoColor }} text-white">
                                                    {{ ucfirst($pago->estado ?? 'registrado') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light fw-semibold">
                                        <td colspan="4" class="text-end text-muted small">Subtotal mostrado (últimos
                                            {{ $pagosRecientes->count() }}):</td>
                                        <td class="text-end text-success">{{ $simbolo }}
                                            {{ number_format($pagosRecientes->sum('pago_bs'), 2) }}</td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @if ($totalPagos > 10)
                            <div class="text-center mt-3 py-2 bg-light rounded small text-muted">
                                <i class="ri-information-line me-1"></i>
                                Mostrando los últimos 10 de <strong>{{ $totalPagos }}</strong> pagos.
                                Total general: <strong class="text-success">{{ $simbolo }}
                                    {{ number_format($totalDepositado, 2) }}</strong>
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <div class="empty-icon bg-primary bg-opacity-10 text-primary"><i
                                    class="ri-file-list-3-line"></i></div>
                            <h6 class="text-muted">Sin pagos registrados</h6>
                            <p class="text-muted small mb-0">Esta cuenta aún no tiene pagos asociados.</p>
                        </div>
                    @endif
                </div>

                {{-- ══════════ TAB MOVIMIENTOS ══════════ --}}
                <div class="tab-pane" id="tab-movimientos" role="tabpanel">
                    @if ($movimientos->count() > 0)
                        @php
                            $tiposMovConfig = [
                                'deposito' => ['label' => 'Depósito', 'color' => 'success', 'sign' => '+'],
                                'retiro' => ['label' => 'Retiro', 'color' => 'danger', 'sign' => '-'],
                                'transferencia_envio' => ['label' => 'Envío', 'color' => 'warning', 'sign' => '-'],
                                'transferencia_recepcion' => ['label' => 'Recepción', 'color' => 'info', 'sign' => '+'],
                                'pago' => ['label' => 'Pago', 'color' => 'primary', 'sign' => '+'],
                                'ajuste' => ['label' => 'Ajuste', 'color' => 'secondary', 'sign' => '±'],
                                'correccion' => ['label' => 'Corrección', 'color' => 'dark', 'sign' => '±'],
                            ];
                        @endphp
                        <div class="table-responsive">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Descripción</th>
                                        <th>Referencia</th>
                                        <th class="text-end">Monto</th>
                                        <th class="text-end">Saldo Anterior</th>
                                        <th class="text-end">Saldo Posterior</th>
                                        <th>Conciliado</th>
                                        <th>Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movimientos as $mov)
                                        @php
                                            $mc = $tiposMovConfig[$mov->tipo_movimiento] ?? [
                                                'label' => $mov->tipo_movimiento,
                                                'color' => 'secondary',
                                                'sign' => '',
                                            ];
                                            $esPositivo = $mov->monto >= 0;
                                        @endphp
                                        <tr>
                                            <td><span class="small">{{ $mov->created_at->format('d/m/Y') }}<br><span
                                                        class="text-muted">{{ $mov->created_at->format('H:i') }}</span></span>
                                            </td>
                                            <td>
                                                <span class="badge badge-tipo bg-{{ $mc['color'] }} text-white">
                                                    {{ $mc['label'] }}
                                                </span>
                                            </td>
                                            <td><small class="text-muted">{{ Str::limit($mov->descripcion, 45) }}</small>
                                            </td>
                                            <td>
                                                @if ($mov->referencia)
                                                    @php
                                                        $modelosMostrar = [
                                                            'Pago' => 'Pago',
                                                            'TransferenciaBancaria' => 'Transf.',
                                                            'Deposito' => 'Depósito',
                                                            'Caja' => 'Caja',
                                                        ];
                                                        $modelo = class_basename($mov->referencia_type);
                                                    @endphp
                                                    <span class="badge bg-light text-dark border small">
                                                        {{ $modelosMostrar[$modelo] ?? $modelo }}
                                                        #{{ $mov->referencia_id }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">Manual</span>
                                                @endif
                                            </td>
                                            <td
                                                class="text-end fw-bold {{ $esPositivo ? 'text-success' : 'text-danger' }}">
                                                {{ $esPositivo ? '+' : '-' }} {{ $simbolo }}
                                                {{ number_format(abs($mov->monto), 2) }}
                                            </td>
                                            <td class="text-end text-muted small">{{ $simbolo }}
                                                {{ number_format($mov->saldo_anterior, 2) }}</td>
                                            <td class="text-end fw-medium small">{{ $simbolo }}
                                                {{ number_format($mov->saldo_posterior, 2) }}</td>
                                            <td>
                                                @if ($mov->conciliado)
                                                    <span class="badge bg-success text-white">
                                                        <i class="ri-check-line"></i>
                                                        {{ $mov->fecha_conciliacion?->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary text-white">Pendiente</span>
                                                @endif
                                            </td>
                                            <td><small class="text-muted">{{ $mov->usuario?->name ?? 'Sistema' }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">{{ $movimientos->links() }}</div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon bg-info bg-opacity-10 text-info"><i
                                    class="ri-exchange-dollar-line"></i></div>
                            <h6 class="text-muted">Sin movimientos</h6>
                            <p class="text-muted small mb-0">Esta cuenta no tiene movimientos bancarios registrados.</p>
                        </div>
                    @endif
                </div>

                {{-- ══════════ TAB TRANSFERENCIAS ══════════ --}}
                <div class="tab-pane" id="tab-transferencias" role="tabpanel">
                    <ul class="nav nav-pills gap-1 mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active py-1 px-3" data-bs-toggle="tab" href="#transf-enviadas">
                                <i class="ri-arrow-up-circle-line me-1 text-danger"></i>Enviadas
                                <span class="badge bg-danger ms-1">{{ $transferenciasEnviadas->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-1 px-3" data-bs-toggle="tab" href="#transf-recibidas">
                                <i class="ri-arrow-down-circle-line me-1 text-success"></i>Recibidas
                                <span class="badge bg-success ms-1">{{ $transferenciasRecibidas->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-1 px-3" data-bs-toggle="tab" href="#transf-correccion">
                                <i class="ri-refresh-line me-1 text-warning"></i>Correcciones
                                <span class="badge bg-warning ms-1">{{ $transferenciasCorreccion->count() }}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        {{-- Enviadas --}}
                        <div class="tab-pane active" id="transf-enviadas">
                            @if ($transferenciasEnviadas->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-modern mb-0">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Destino</th>
                                                <th>Banco Destino</th>
                                                <th class="text-end">Monto</th>
                                                <th>Tipo</th>
                                                <th>Estado</th>
                                                <th>Usuario</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($transferenciasEnviadas as $t)
                                                @php
                                                    $estadosClases = [
                                                        'pendiente' => 'warning',
                                                        'procesada' => 'success',
                                                        'cancelada' => 'danger',
                                                        'conciliada' => 'info',
                                                    ];
                                                @endphp
                                                <tr>
                                                    <td>{{ $t->fecha_transferencia->format('d/m/Y') }}</td>
                                                    <td>
                                                        <span
                                                            class="fw-medium font-monospace small">{{ $t->cuentaDestino->numero_cuenta }}</span>
                                                        <br><small
                                                            class="text-muted">{{ $t->cuentaDestino->sucursal->nombre ?? '' }}</small>
                                                    </td>
                                                    <td>{{ $t->cuentaDestino->banco->nombre }}</td>
                                                    <td class="text-end fw-bold text-danger">
                                                        -{{ $simbolo }} {{ number_format($t->monto, 2) }}
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $t->tipo_transferencia == 'correccion' ? 'warning' : 'info' }} text-white">
                                                            {{ $t->tipo_transferencia_formateado }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $estadosClases[$t->estado] ?? 'secondary' }} text-white">
                                                            {{ ucfirst($t->estado) }}
                                                        </span>
                                                    </td>
                                                    <td><small class="text-muted">{{ $t->usuario?->name ?? '—' }}</small>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-secondary py-0 px-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalDetalleTransferencia"
                                                            data-transferencia-id="{{ $t->id }}">
                                                            <i class="ri-eye-line"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-icon bg-danger bg-opacity-10 text-danger"><i
                                            class="ri-arrow-up-line"></i></div>
                                    <p class="text-muted small mb-0">No hay transferencias enviadas</p>
                                </div>
                            @endif
                        </div>
                        {{-- Recibidas --}}
                        <div class="tab-pane" id="transf-recibidas">
                            @if ($transferenciasRecibidas->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-modern mb-0">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Origen</th>
                                                <th>Banco Origen</th>
                                                <th class="text-end">Monto</th>
                                                <th>Tipo</th>
                                                <th>Estado</th>
                                                <th>Usuario</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($transferenciasRecibidas as $t)
                                                @php $estadosClases = ['pendiente'=>'warning','procesada'=>'success','cancelada'=>'danger','conciliada'=>'info']; @endphp
                                                <tr>
                                                    <td>{{ $t->fecha_transferencia->format('d/m/Y') }}</td>
                                                    <td>
                                                        <span
                                                            class="fw-medium font-monospace small">{{ $t->cuentaOrigen->numero_cuenta }}</span>
                                                        <br><small
                                                            class="text-muted">{{ $t->cuentaOrigen->sucursal->nombre ?? '' }}</small>
                                                    </td>
                                                    <td>{{ $t->cuentaOrigen->banco->nombre }}</td>
                                                    <td class="text-end fw-bold text-success">
                                                        +{{ $simbolo }} {{ number_format($t->monto, 2) }}
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info text-white">{{ $t->tipo_transferencia_formateado }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $estadosClases[$t->estado] ?? 'secondary' }} text-white">
                                                            {{ ucfirst($t->estado) }}
                                                        </span>
                                                    </td>
                                                    <td><small class="text-muted">{{ $t->usuario?->name ?? '—' }}</small>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-secondary py-0 px-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalDetalleTransferencia"
                                                            data-transferencia-id="{{ $t->id }}">
                                                            <i class="ri-eye-line"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-icon bg-success bg-opacity-10 text-success"><i
                                            class="ri-arrow-down-line"></i></div>
                                    <p class="text-muted small mb-0">No hay transferencias recibidas</p>
                                </div>
                            @endif
                        </div>
                        {{-- Correcciones --}}
                        <div class="tab-pane" id="transf-correccion">
                            @if ($transferenciasCorreccion->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-modern mb-0">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Pago Corregido</th>
                                                <th>Destino</th>
                                                <th class="text-end">Monto</th>
                                                <th>Motivo</th>
                                                <th>Estado</th>
                                                <th>Usuario</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($transferenciasCorreccion as $t)
                                                @php $estadosClases = ['pendiente'=>'warning','procesada'=>'success','cancelada'=>'danger','conciliada'=>'info']; @endphp
                                                <tr>
                                                    <td>{{ $t->fecha_transferencia->format('d/m/Y') }}</td>
                                                    <td>
                                                        @if ($t->pago)
                                                            <code
                                                                class="bg-light px-2 py-1 rounded small">{{ $t->pago->recibo }}</code>
                                                        @else
                                                            <span class="text-muted small">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="small font-monospace">{{ $t->cuentaDestino->numero_cuenta }}</span>
                                                        <br><small
                                                            class="text-muted">{{ $t->cuentaDestino->banco->nombre }}</small>
                                                    </td>
                                                    <td class="text-end fw-bold text-warning">
                                                        {{ $simbolo }} {{ number_format($t->monto, 2) }}
                                                    </td>
                                                    <td><small
                                                            class="text-muted">{{ Str::limit($t->motivo_correccion, 50) }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $estadosClases[$t->estado] ?? 'secondary' }} text-white">
                                                            {{ ucfirst($t->estado) }}
                                                        </span>
                                                    </td>
                                                    <td><small class="text-muted">{{ $t->usuario?->name ?? '—' }}</small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-icon bg-warning bg-opacity-10 text-warning"><i
                                            class="ri-refresh-line"></i></div>
                                    <p class="text-muted small mb-0">No hay transferencias de corrección</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ══════════ TAB DEPÓSITOS ══════════ --}}
                <div class="tab-pane" id="tab-depositos" role="tabpanel">
                    @if ($depositos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Caja</th>
                                        <th>Sucursal</th>
                                        <th class="text-end">Monto</th>
                                        <th>Comprobante</th>
                                        <th>Estado</th>
                                        <th>Usuario</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($depositos as $dep)
                                        @php
                                            $estadosDepositos = [
                                                'pendiente' => 'warning',
                                                'confirmado' => 'success',
                                                'cancelado' => 'danger',
                                            ];
                                            $ec = $estadosDepositos[$dep->estado] ?? 'secondary';
                                        @endphp
                                        <tr>
                                            <td>{{ $dep->fecha_deposito->format('d/m/Y') }}</td>
                                            <td><span class="fw-medium">{{ $dep->caja->nombre ?? '—' }}</span></td>
                                            <td><small
                                                    class="text-muted">{{ $dep->caja->sucursal->nombre ?? '—' }}</small>
                                            </td>
                                            <td class="text-end fw-bold text-success">
                                                +{{ $simbolo }} {{ number_format($dep->monto, 2) }}
                                            </td>
                                            <td>
                                                @if ($dep->comprobante)
                                                    <span
                                                        class="badge bg-light text-dark border">{{ $dep->comprobante }}</span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $ec }} text-white">
                                                    {{ ucfirst($dep->estado) }}
                                                </span>
                                            </td>
                                            <td><small class="text-muted">{{ $dep->user?->name ?? '—' }}</small></td>
                                            <td><small class="text-muted">{{ Str::limit($dep->descripcion, 40) }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-semibold small text-muted">Total depósitos:
                                        </td>
                                        <td class="text-end fw-bold text-success">{{ $simbolo }}
                                            {{ number_format($depositos->sum('monto'), 2) }}</td>
                                        <td colspan="4"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon bg-primary bg-opacity-10 text-primary"><i
                                    class="ri-bank-card-2-line"></i></div>
                            <h6 class="text-muted">Sin depósitos</h6>
                            <p class="text-muted small mb-0">No se han realizado depósitos desde cajas a esta cuenta.</p>
                        </div>
                    @endif
                </div>

                {{-- ══════════ TAB CONCILIACIONES ══════════ --}}
                <div class="tab-pane" id="tab-conciliaciones" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 text-muted small text-uppercase fw-semibold">
                            <i class="ri-file-check-line me-1"></i>Conciliaciones Bancarias
                        </h6>
                        @if (Auth::guard('web')->user()->can('cuentas-bancarias.conciliar'))
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalNuevaConciliacion">
                                <i class="ri-add-line me-1"></i>Nueva Conciliación
                            </button>
                        @endif
                    </div>
                    @if ($conciliaciones->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>Período</th>
                                        <th class="text-end">Saldo Libros</th>
                                        <th class="text-end">Saldo Extracto</th>
                                        <th class="text-end">Diferencia</th>
                                        <th>Estado</th>
                                        <th>Usuario</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($conciliaciones as $con)
                                        @php
                                            $estadosCon = [
                                                'pendiente' => 'warning',
                                                'en_proceso' => 'info',
                                                'conciliada' => 'success',
                                                'cerrada' => 'secondary',
                                            ];
                                            $ec = $estadosCon[$con->estado] ?? 'secondary';
                                        @endphp
                                        <tr>
                                            <td>
                                                <span
                                                    class="fw-medium small">{{ $con->fecha_inicio->format('d/m/Y') }}</span>
                                                <span class="text-muted small"> → </span>
                                                <span
                                                    class="fw-medium small">{{ $con->fecha_fin->format('d/m/Y') }}</span>
                                            </td>
                                            <td class="text-end">{{ $simbolo }}
                                                {{ number_format($con->saldo_libros, 2) }}</td>
                                            <td class="text-end">{{ $simbolo }}
                                                {{ number_format($con->saldo_extracto, 2) }}</td>
                                            <td
                                                class="text-end fw-bold {{ $con->diferencia >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $con->diferencia >= 0 ? '+' : '' }}{{ $simbolo }}
                                                {{ number_format($con->diferencia, 2) }}
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $ec }} text-white">
                                                    {{ ucfirst(str_replace('_', ' ', $con->estado)) }}
                                                </span>
                                            </td>
                                            <td><small class="text-muted">{{ $con->usuario?->name ?? '—' }}</small></td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button class="btn btn-sm btn-outline-secondary py-0 px-2"
                                                        data-bs-toggle="modal" data-bs-target="#modalDetalleConciliacion"
                                                        data-conciliacion-id="{{ $con->id }}">
                                                        <i class="ri-eye-line"></i>
                                                    </button>
                                                    @if (in_array($con->estado, ['pendiente', 'en_proceso']))
                                                        <button class="btn btn-sm btn-outline-warning py-0 px-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditarConciliacion"
                                                            data-conciliacion-id="{{ $con->id }}">
                                                            <i class="ri-edit-line"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon bg-info bg-opacity-10 text-info"><i class="ri-file-check-line"></i>
                            </div>
                            <h6 class="text-muted">Sin conciliaciones</h6>
                            <p class="text-muted small mb-0">Esta cuenta no tiene conciliaciones bancarias registradas.</p>
                        </div>
                    @endif
                </div>

            </div>{{-- /tab-content --}}
        </div>{{-- /card --}}
    </div>{{-- /container --}}

    @include('admin.cuentas_bancarias.modals.transferencia')
    @include('admin.cuentas_bancarias.modals.detalle_transferencia')

@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Tooltips
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

            // Editar
            $('.editBtn').on('click', function() {
                var d = $(this).data('bs-obj');
                $('#cuentaId').val(d.id);
                $('#banco_id_edicion').val(d.banco_id);
                $('#sucursale_id_edicion').val(d.sucursale_id);
                $('#numero_cuenta_edicion').val(d.numero_cuenta);
                $('#tipo_cuenta_edicion').val(d.tipo_cuenta);
                $('#moneda_edicion').val(d.moneda);
                $('#descripcion_edicion').val(d.descripcion || '');
                $('#saldo_inicial_edicion').val(d.saldo_inicial);
                $('#activa_edicion').prop('checked', d.activa == 1 || d.activa === true);
                $('#feedback_numero_cuenta_edicion').removeClass('text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
            });

            // Eliminar
            $('.deleteBtn').on('click', function() {
                var d = $(this).data('bs-obj');
                $('#eliminarId').val(d.id);
                $('#warning-pagos').hide();
                $('.btnDelete').prop('disabled', false);
            });

            // Transferencia
            $('#modalTransferencia').on('show.bs.modal', function(e) {
                $('#cuenta_origen_id').val($(e.relatedTarget).data('cuenta-id'));
            });

            $('#cargarCuentasDestino').on('click', function() {
                var bancoId = $('#banco_destino_id').val();
                var sucursalId = $('#sucursal_destino_id').val();
                if (bancoId && sucursalId) {
                    $.get('{{ route('admin.cuentas-bancarias.por-banco-sucursal') }}', {
                        banco_id: bancoId,
                        sucursale_id: sucursalId,
                        excluir: $('#cuenta_origen_id').val()
                    }, function(res) {
                        $('#cuenta_destino_id').empty().append(
                            '<option value="">Seleccionar cuenta...</option>');
                        $.each(res, function(i, c) {
                            $('#cuenta_destino_id').append(
                                `<option value="${c.id}">${c.numero_cuenta} - ${c.tipo_cuenta} (${c.moneda})</option>`
                            );
                        });
                    });
                }
            });

            // Detalle transferencia
            $(document).on('click', '[data-bs-target="#modalDetalleTransferencia"]', function() {
                var id = $(this).data('transferencia-id');
                $.get('/admin/transferencias/' + id + '/detalle', function(res) {
                    $('#modalDetalleTransferencia .modal-body').html(res);
                });
            });

            // Detalle conciliación
            $(document).on('click', '[data-bs-target="#modalDetalleConciliacion"]', function() {
                var id = $(this).data('conciliacion-id');
                $.get('/admin/conciliaciones/' + id + '/detalle', function(res) {
                    $('#modalDetalleConciliacion .modal-body').html(res);
                });
            });

            // Recargar tras editar
            $('#modalModificar').on('hidden.bs.modal', function(e) {
                if ($(e.target).hasClass('show')) location.reload();
            });

            $('#modalEliminar').on('hidden.bs.modal', function(e) {
                if (!$(e.target).hasClass('show')) {
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.cuentas-bancarias.listar') }}";
                    }, 800);
                }
            });
        });
    </script>
@endpush
