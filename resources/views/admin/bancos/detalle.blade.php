@extends('admin.dashboard')
@section('admin')

@php
    $bancoColor  = $banco->color ?? '#405189';
    $inicial     = strtoupper(substr($banco->nombre, 0, 1));
    $totalSaldo  = $banco->cuentas->sum('saldo_actual');
    $simboloTotal = 'Bs';

    $tiposCuenta = [
        'ahorro'            => 'Ahorro',
        'corriente'         => 'Corriente',
        'moneda_extranjera' => 'Moneda Extranjera',
    ];
    $simbolos = ['BS' => 'Bs', 'USD' => '$', 'EUR' => '€'];
@endphp

<style>
    .banco-hero {
        background: linear-gradient(135deg, {{ $bancoColor }}ee 0%, {{ $bancoColor }}88 100%);
        border-radius: 12px;
        color: #fff;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .banco-hero::before {
        content: '';
        position: absolute; right: -50px; top: -50px;
        width: 200px; height: 200px; border-radius: 50%;
        background: rgba(255,255,255,.07);
    }
    .banco-hero::after {
        content: '';
        position: absolute; right: 80px; bottom: -60px;
        width: 130px; height: 130px; border-radius: 50%;
        background: rgba(255,255,255,.05);
    }
    .banco-logo-box {
        width: 64px; height: 64px; border-radius: 12px;
        background: rgba(255,255,255,.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; font-weight: 700; flex-shrink: 0;
        overflow: hidden;
    }
    .banco-logo-box img { width: 100%; height: 100%; object-fit: contain; padding: 6px; }

    .stat-mini {
        border-radius: 10px; border: 1px solid #e9ebec;
        padding: .9rem 1.1rem; height: 100%;
        transition: box-shadow .2s;
    }
    .stat-mini:hover { box-shadow: 0 4px 16px rgba(0,0,0,.07); }
    .stat-mini .sm-icon {
        width: 40px; height: 40px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0;
    }
    .stat-mini .sm-val  { font-size: 1.3rem; font-weight: 700; line-height: 1.2; }
    .stat-mini .sm-lbl  { font-size: .73rem; color: #878a99; }

    .moneda-tab .nav-link {
        border-radius: 8px 8px 0 0; padding: 8px 18px;
        font-weight: 500; font-size: .875rem; color: #495057;
        border: 1px solid transparent; border-bottom: none;
    }
    .moneda-tab .nav-link.active {
        background: #fff; border-color: #dee2e6; color: #405189; font-weight: 600;
    }

    .table-cb thead th {
        font-size: .71rem; text-transform: uppercase; letter-spacing: .5px;
        color: #878a99; font-weight: 600; background: #f8f9fa;
        border-bottom: 2px solid #e9ebec; padding: .6rem .75rem;
    }
    .table-cb tbody td { padding: .65rem .75rem; font-size: .875rem; vertical-align: middle; }
    .table-cb tbody tr:hover { background: #f8faff; }

    .saldo-card {
        border-radius: 10px; border-left: 4px solid {{ $bancoColor }};
        background: #fff; padding: .9rem 1.1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.06);
    }

    .info-item { display: flex; gap: .5rem; align-items: flex-start; padding: .5rem 0; border-bottom: 1px solid #f0f0f0; }
    .info-item:last-child { border-bottom: none; }
    .info-item .ii-label { font-size: .75rem; color: #878a99; min-width: 110px; padding-top: 2px; }
    .info-item .ii-val   { font-size: .875rem; font-weight: 500; }
</style>

<div class="container-fluid">

    {{-- BREADCRUMB + ACCIONES --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.bancos.listar') }}" class="btn btn-light btn-sm">
                <i class="ri-arrow-left-line"></i>
            </a>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.bancos.listar') }}">Bancos</a></li>
                    <li class="breadcrumb-item active">{{ $banco->nombre }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @if (Auth::guard('web')->user()->can('cuentas-bancarias.registrar'))
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaCuenta">
                    <i class="ri-add-line me-1"></i>Nueva Cuenta
                </button>
            @endif
            @if (Auth::guard('web')->user()->can('bancos.editar'))
                <button type="button" class="btn btn-sm btn-warning editBtn"
                    data-bs-toggle="modal" data-bs-target="#modalModificar" data-bs-obj='@json($banco)'>
                    <i class="ri-edit-line me-1"></i>Editar
                </button>
            @endif
        </div>
    </div>

    {{-- HERO --}}
    <div class="banco-hero">
        <div class="d-flex align-items-center gap-4 flex-wrap">
            <div class="banco-logo-box">
                @if ($banco->logo)
                    <img src="{{ $banco->logo }}" alt="{{ $banco->nombre }}">
                @else
                    {{ $inicial }}
                @endif
            </div>
            <div class="flex-grow-1">
                <h4 class="mb-1 text-white fw-bold">{{ $banco->nombre }}</h4>
                <div class="d-flex gap-3 flex-wrap" style="opacity:.85;font-size:.85rem;">
                    <span><i class="ri-hashtag me-1"></i>Código: <strong>{{ $banco->codigo }}</strong></span>
                    <span><i class="ri-bank-card-line me-1"></i>{{ $estadisticas['total_cuentas'] }} cuenta{{ $estadisticas['total_cuentas'] != 1 ? 's' : '' }}</span>
                    <span><i class="ri-checkbox-circle-line me-1"></i>{{ $estadisticas['cuentas_activas'] }} activa{{ $estadisticas['cuentas_activas'] != 1 ? 's' : '' }}</span>
                </div>
            </div>
            @if ($banco->color)
                <div class="text-end" style="opacity:.9;">
                    <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;">Color</div>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <div style="width:22px;height:22px;border-radius:50%;background:#fff;opacity:.9;"></div>
                        <span style="font-size:.85rem;">{{ $banco->color }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- STATS --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="stat-mini">
                <div class="d-flex align-items-center gap-3">
                    <div class="sm-icon bg-primary bg-opacity-10 text-primary">
                        <i class="ri-bank-card-line"></i>
                    </div>
                    <div>
                        <div class="sm-lbl">Total Cuentas</div>
                        <div class="sm-val">{{ $estadisticas['total_cuentas'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-mini">
                <div class="d-flex align-items-center gap-3">
                    <div class="sm-icon bg-success bg-opacity-10 text-success">
                        <i class="ri-checkbox-circle-line"></i>
                    </div>
                    <div>
                        <div class="sm-lbl">Activas</div>
                        <div class="sm-val text-success">{{ $estadisticas['cuentas_activas'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-mini">
                <div class="d-flex align-items-center gap-3">
                    <div class="sm-icon bg-warning bg-opacity-10 text-warning">
                        <i class="ri-pause-circle-line"></i>
                    </div>
                    <div>
                        <div class="sm-lbl">Inactivas</div>
                        <div class="sm-val text-warning">{{ $estadisticas['cuentas_inactivas'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-mini">
                <div class="d-flex align-items-center gap-3">
                    <div class="sm-icon bg-info bg-opacity-10 text-info">
                        <i class="ri-file-list-3-line"></i>
                    </div>
                    <div>
                        <div class="sm-lbl">Pagos Registrados</div>
                        <div class="sm-val text-info">{{ number_format($estadisticas['total_pagos'], 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- COLUMNA IZQUIERDA: info + saldos por moneda --}}
        <div class="col-lg-4">

            {{-- Info del banco --}}
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-semibold text-muted small text-uppercase">
                        <i class="ri-information-line me-1"></i>Información del Banco
                    </h6>
                </div>
                <div class="card-body py-2">
                    <div class="info-item">
                        <span class="ii-label"><i class="ri-hashtag me-1 text-muted"></i>Código</span>
                        <span class="ii-val"><span class="badge bg-secondary text-white">{{ $banco->codigo }}</span></span>
                    </div>
                    <div class="info-item">
                        <span class="ii-label"><i class="ri-palette-line me-1 text-muted"></i>Color</span>
                        <span class="ii-val d-flex align-items-center gap-2">
                            <span style="display:inline-block;width:16px;height:16px;border-radius:4px;background:{{ $bancoColor }};border:1px solid #dee2e6;"></span>
                            {{ $banco->color ?? '—' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="ii-label"><i class="ri-calendar-line me-1 text-muted"></i>Registrado</span>
                        <span class="ii-val small text-muted">{{ $banco->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="ii-label"><i class="ri-refresh-line me-1 text-muted"></i>Actualizado</span>
                        <span class="ii-val small text-muted">{{ $banco->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- Saldos por moneda --}}
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-semibold text-muted small text-uppercase">
                        <i class="ri-money-dollar-circle-line me-1"></i>Saldo Total por Moneda
                    </h6>
                </div>
                <div class="card-body d-flex flex-column gap-3">
                    @forelse ($cuentas_por_moneda as $moneda => $cuentas)
                        @php
                            $saldo   = $cuentas->sum('saldo_actual');
                            $simbolo = $simbolos[$moneda] ?? $moneda;
                        @endphp
                        <div class="saldo-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small text-muted mb-1">{{ $moneda }}
                                        <span class="badge bg-light text-dark border ms-1">{{ $cuentas->count() }} cta{{ $cuentas->count() != 1 ? 's' : '' }}</span>
                                    </div>
                                    <div class="fw-bold fs-5 {{ $saldo >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $simbolo }} {{ number_format($saldo, 2) }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ $cuentas->where('activa', true)->count() }} activa{{ $cuentas->where('activa', true)->count() != 1 ? 's' : '' }} /
                                        {{ $cuentas->where('activa', false)->count() }} inactiva{{ $cuentas->where('activa', false)->count() != 1 ? 's' : '' }}
                                    </div>
                                </div>
                                <div style="width:40px;height:40px;border-radius:50%;background:{{ $bancoColor }}22;display:flex;align-items:center;justify-content:center;">
                                    <i class="ri-currency-line" style="color:{{ $bancoColor }};font-size:1.2rem;"></i>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">
                            <i class="ri-bank-card-line display-6 d-block mb-2"></i>
                            Sin cuentas registradas
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: cuentas por moneda con tabs --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold text-muted small text-uppercase">
                        <i class="ri-bank-card-2-line me-1"></i>Cuentas Bancarias
                    </h6>
                    @if (Auth::guard('web')->user()->can('cuentas-bancarias.registrar'))
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaCuenta">
                            <i class="ri-add-line me-1"></i>Nueva Cuenta
                        </button>
                    @endif
                </div>

                @if ($cuentas_por_moneda->count() > 0)
                    {{-- Tabs por moneda --}}
                    <div class="card-header bg-white pt-0 pb-0 border-top-0">
                        <ul class="nav moneda-tab border-bottom-0" role="tablist">
                            @foreach ($cuentas_por_moneda as $moneda => $cuentas)
                                <li class="nav-item">
                                    <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                        data-bs-toggle="tab" href="#moneda-{{ $moneda }}" role="tab">
                                        {{ $moneda }}
                                        <span class="badge bg-primary ms-1" style="font-size:.65rem;">{{ $cuentas->count() }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="tab-content">
                        @foreach ($cuentas_por_moneda as $moneda => $cuentas)
                            @php $simbolo = $simbolos[$moneda] ?? $moneda; @endphp
                            <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="moneda-{{ $moneda }}" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-cb mb-0">
                                        <thead>
                                            <tr>
                                                <th>N° Cuenta</th>
                                                <th>Tipo</th>
                                                <th>Sucursal</th>
                                                <th class="text-end">Saldo Actual</th>
                                                <th>Estado</th>
                                                <th class="text-center">Ver</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cuentas as $cuenta)
                                                @php
                                                    $esActiva = $cuenta->activa;
                                                    $saldo    = $cuenta->saldo_actual;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <span class="fw-medium font-monospace">{{ $cuenta->numero_cuenta }}</span>
                                                        @if ($cuenta->descripcion)
                                                            <br><small class="text-muted">{{ $cuenta->descripcion }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info text-white" style="font-size:.7rem;">
                                                            {{ $tiposCuenta[$cuenta->tipo_cuenta] ?? $cuenta->tipo_cuenta }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="small">{{ $cuenta->sucursal->nombre ?? '—' }}</span>
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="fw-bold {{ $saldo >= 0 ? 'text-success' : 'text-danger' }}">
                                                            {{ $simbolo }} {{ number_format($saldo, 2) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if ($esActiva)
                                                            <span class="badge bg-success text-white" style="font-size:.7rem;">
                                                                <i class="ri-checkbox-circle-line me-1"></i>Activa
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary text-white" style="font-size:.7rem;">Inactiva</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('admin.cuentas-bancarias.ver', ['id' => $cuenta->id]) }}"
                                                            class="btn btn-sm btn-outline-primary py-0 px-2" title="Ver detalle">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <td colspan="3" class="text-end text-muted small fw-semibold">Total {{ $moneda }}:</td>
                                                <td class="text-end fw-bold {{ $cuentas->sum('saldo_actual') >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $simbolo }} {{ number_format($cuentas->sum('saldo_actual'), 2) }}
                                                </td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="card-body text-center py-5">
                        <div style="width:64px;height:64px;border-radius:50%;background:#f3f6f9;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                            <i class="ri-bank-card-line text-muted" style="font-size:1.6rem;"></i>
                        </div>
                        <h6 class="text-muted">Sin cuentas registradas</h6>
                        <p class="text-muted small mb-3">Este banco aún no tiene cuentas bancarias asociadas.</p>
                        @if (Auth::guard('web')->user()->can('cuentas-bancarias.registrar'))
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaCuenta">
                                <i class="ri-add-line me-1"></i>Registrar primera cuenta
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>

    </div>{{-- /row --}}
</div>{{-- /container --}}

{{-- ═══════════ MODAL REGISTRAR CUENTA ═══════════ --}}
<div class="modal fade" id="modalNuevaCuenta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, {{ $bancoColor }}dd, {{ $bancoColor }}99); color:#fff;">
                <h5 class="modal-title fw-semibold">
                    <i class="ri-bank-card-line me-2"></i>Nueva Cuenta — {{ $banco->nombre }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addCuentaForm">
                    @csrf
                    {{-- Banco pre-seleccionado y oculto --}}
                    <input type="hidden" name="banco_id" value="{{ $banco->id }}">

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label form-label-sm">Sucursal <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="nc_sucursale_id" name="sucursale_id" required>
                                <option value="">— Seleccionar sucursal —</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label form-label-sm">Número de Cuenta <span class="text-danger">*</span></label>
                            <input type="text" id="nc_numero_cuenta" name="numero_cuenta"
                                class="form-control form-control-sm" placeholder="Ej: 1234567890" required>
                            <div id="nc_feedback" class="form-text small"></div>
                        </div>
                        <div class="col-6">
                            <label class="form-label form-label-sm">Tipo de Cuenta <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="nc_tipo_cuenta" name="tipo_cuenta" required>
                                <option value="">— Seleccionar —</option>
                                <option value="ahorro">Ahorro</option>
                                <option value="corriente">Corriente</option>
                                <option value="moneda_extranjera">Moneda Extranjera</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label form-label-sm">Moneda <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="nc_moneda" name="moneda" required>
                                <option value="">— Seleccionar —</option>
                                <option value="BS">Bolivianos (BS)</option>
                                <option value="USD">Dólares (USD)</option>
                                <option value="EUR">Euros (EUR)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label form-label-sm">Saldo Inicial <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" id="nc_saldo_inicial"
                                name="saldo_inicial" class="form-control form-control-sm" placeholder="0.00" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label form-label-sm">Estado</label>
                            <div class="form-check form-switch mt-2">
                                <input type="hidden" name="activa" value="0">
                                <input class="form-check-input" type="checkbox" id="nc_activa" name="activa" value="1" checked>
                                <label class="form-check-label small" for="nc_activa">Activa</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label form-label-sm">Descripción</label>
                            <textarea class="form-control form-control-sm" name="descripcion" rows="2"
                                placeholder="Descripción opcional..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="addCuentaForm" class="btn btn-sm btn-primary addCuentaBtn" disabled>
                    <i class="ri-save-line me-1"></i>Registrar Cuenta
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Modificar Banco --}}
<div class="modal fade" id="modalModificar" tabindex="-1" aria-labelledby="modalModificarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning bg-soft">
                <h5 class="modal-title" id="modalModificarLabel">
                    <i class="ri-edit-line me-2"></i>Modificar Banco
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateForm" class="forms-sample">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="bancoId">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Nombre del Banco <span class="text-danger">*</span></label>
                                <input type="text" id="nombre_edicion" name="nombre" class="form-control" required>
                                <div id="feedback_nombre_edicion" class="form-text"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Código <span class="text-danger">*</span></label>
                                <input type="text" id="codigo_edicion" name="codigo" class="form-control" required>
                                <div id="feedback_codigo_edicion" class="form-text"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Color</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" class="form-control form-control-color" id="color_edicion"
                                        name="color" value="#0d6efd" title="Elige un color">
                                    <span id="colorPreviewEdicion" class="d-inline-block"
                                        style="width:24px;height:24px;border:1px solid #ddd;border-radius:4px;"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Logo (URL)</label>
                                <input type="text" id="logo_edicion" name="logo" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="updateForm" class="btn btn-warning updateBtn" disabled>
                    <i class="ri-save-line me-1"></i> Actualizar Banco
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
    <style>
        .spin { animation: spin .8s linear infinite; display: inline-block; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
@endpush

@push('script')
<script>
$(document).ready(function () {

    // ── Editar banco ──────────────────────────────────────────
    $('.editBtn').on('click', function () {
        var d = $(this).data('bs-obj');
        $('#bancoId').val(d.id);
        $('#nombre_edicion').val(d.nombre);
        $('#codigo_edicion').val(d.codigo);
        $('#color_edicion').val(d.color || '#0d6efd');
        $('#colorPreviewEdicion').css('background-color', d.color || '#0d6efd');
        $('#logo_edicion').val(d.logo || '');
        $('#feedback_nombre_edicion, #feedback_codigo_edicion').removeClass('text-success text-danger').text('');
        $('.updateBtn').prop('disabled', false);
    });

    $('#updateForm').submit(function (e) {
        e.preventDefault();
        const btn = $('.updateBtn');
        btn.prop('disabled', true).html('<i class="ri-loader-4-line spin me-1"></i> Actualizando...');
        $.ajax({
            url: "{{ route('admin.bancos.modificar') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    showNotification('success', res.msg);
                    $('#modalModificar').modal('hide');
                    setTimeout(() => location.reload(), 900);
                } else {
                    showNotification('error', res.msg);
                    btn.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Actualizar Banco');
                }
            },
            error: function (xhr) {
                let msg = xhr.responseJSON?.errors ? Object.values(xhr.responseJSON.errors)[0][0] : 'Error al actualizar.';
                showNotification('error', msg);
                btn.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Actualizar Banco');
            }
        });
    });

    // ── Nueva Cuenta Bancaria ─────────────────────────────────

    // Cargar sucursales al abrir el modal
    $('#modalNuevaCuenta').on('show.bs.modal', function () {
        if ($('#nc_sucursale_id').children().length <= 1) {
            cargarSucursalesNc();
        }
        resetNcForm();
    });

    function cargarSucursalesNc() {
        $.ajax({
            url: "{{ route('admin.cuentas-bancarias.obtener-sucursales') }}",
            type: "GET",
            success: function (res) {
                if (res.success) {
                    let opts = '<option value="">— Seleccionar sucursal —</option>';
                    res.sucursales.forEach(s => {
                        opts += `<option value="${s.id}">${s.nombre}${s.direccion ? ' — ' + s.direccion : ''}</option>`;
                    });
                    $('#nc_sucursale_id').html(opts);
                }
            }
        });
    }

    function resetNcForm() {
        $('#addCuentaForm')[0].reset();
        $('#nc_activa').prop('checked', true);
        $('#nc_feedback').removeClass('text-success text-danger').text('');
        $('.addCuentaBtn').prop('disabled', true).html('<i class="ri-save-line me-1"></i>Registrar Cuenta');
    }

    function validarNcForm() {
        const ok = $('#nc_sucursale_id').val() && $('#nc_numero_cuenta').val().trim() &&
                   $('#nc_tipo_cuenta').val() && $('#nc_moneda').val() && $('#nc_saldo_inicial').val();
        $('.addCuentaBtn').prop('disabled', !ok);
    }

    $('#nc_sucursale_id, #nc_tipo_cuenta, #nc_moneda').on('change', validarNcForm);
    $('#nc_saldo_inicial').on('input', validarNcForm);

    // Verificar número de cuenta en tiempo real
    var debounceTimer;
    $('#nc_numero_cuenta').on('input', function () {
        const num      = $(this).val().trim();
        const sucursal = $('#nc_sucursale_id').val();
        const feedback = $('#nc_feedback');

        feedback.removeClass('text-success text-danger').text('');
        $('.addCuentaBtn').prop('disabled', true);

        if (!num || !sucursal) return;

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            $.ajax({
                url: "{{ route('admin.cuentas-bancarias.verificar') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    banco_id: {{ $banco->id }},
                    sucursale_id: sucursal,
                    numero_cuenta: num
                },
                success: function (res) {
                    if (res.exists) {
                        feedback.addClass('text-danger').text('⚠️ Esta cuenta ya está registrada.');
                    } else {
                        feedback.addClass('text-success').text('✅ Número disponible.');
                        validarNcForm();
                    }
                },
                error: function () {
                    feedback.addClass('text-danger').text('❌ Error al verificar.');
                }
            });
        }, 300);
    });

    // Enviar formulario
    $('#addCuentaForm').submit(function (e) {
        e.preventDefault();
        const btn = $('.addCuentaBtn');
        btn.prop('disabled', true).html('<i class="ri-loader-4-line spin me-1"></i> Registrando...');

        $.ajax({
            url: "{{ route('admin.cuentas-bancarias.registrar') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    showNotification('success', res.msg ?? 'Cuenta registrada correctamente.');
                    $('#modalNuevaCuenta').modal('hide');
                    setTimeout(() => location.reload(), 900);
                } else {
                    showNotification('error', res.msg ?? 'Error al registrar.');
                    btn.prop('disabled', false).html('<i class="ri-save-line me-1"></i>Registrar Cuenta');
                }
            },
            error: function (xhr) {
                let msg = xhr.responseJSON?.errors ? Object.values(xhr.responseJSON.errors)[0][0] : 'Error al registrar la cuenta.';
                showNotification('error', msg);
                btn.prop('disabled', false).html('<i class="ri-save-line me-1"></i>Registrar Cuenta');
            }
        });
    });
});
</script>
@endpush
