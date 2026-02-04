@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('admin.cuentas-bancarias.listar') }}" class="btn btn-light me-2">
                            <i class="ri-arrow-left-line"></i>
                        </a>
                        <div>
                            <h4 class="mb-0">Detalle de Cuenta Bancaria</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('admin.cuentas-bancarias.listar') }}">Cuentas Bancarias</a></li>
                                    <li class="breadcrumb-item active">Detalle</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="page-title-right">
                        @if (Auth::guard('web')->user()->can('cuentas-bancarias.editar'))
                            <button type="button" class="btn btn-warning editBtn" data-bs-toggle="modal"
                                data-bs-target="#modalModificar" data-bs-obj='@json($cuenta)'>
                                <i class="ri-edit-line me-1"></i> Editar
                            </button>
                        @endif
                        @if (Auth::guard('web')->user()->can('cuentas-bancarias.eliminar'))
                            <button type="button" class="btn btn-danger deleteBtn" data-bs-toggle="modal"
                                data-bs-target="#modalEliminar" data-bs-obj='@json($cuenta)'>
                                <i class="ri-delete-bin-line me-1"></i> Eliminar
                            </button>
                        @endif

                        <!-- Botón para Nueva Transferencia -->
                        @if (Auth::guard('web')->user()->can('cuentas-bancarias.transferir'))
                            <button type="button" class="btn btn-primary ms-1" data-bs-toggle="modal"
                                data-bs-target="#modalTransferencia" data-cuenta-id="{{ $cuenta->id }}">
                                <i class="ri-exchange-line me-1"></i> Transferir
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @php
            $simbolo =
                [
                    'BS' => 'Bs',
                    'USD' => '$',
                    'EUR' => '€',
                ][$cuenta->moneda] ?? $cuenta->moneda;
        @endphp

        <!-- Tarjetas de Resumen Mejoradas -->
        <div class="row">
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card card-height-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="text-muted mb-3">Estado</h5>
                                @if ($cuenta->activa)
                                    <span class="badge bg-success fs-6">Activa</span>
                                @else
                                    <span class="badge bg-secondary fs-6">Inactiva</span>
                                @endif
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-soft-primary text-primary rounded fs-3">
                                        <i class="ri-bank-card-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card card-height-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="text-muted mb-3">Saldo Actual</h5>
                                <h2 class="mb-0 {{ $cuenta->saldo_actual >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $simbolo }} {{ number_format($cuenta->saldo_actual, 2) }}
                                </h2>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-soft-success text-success rounded fs-3">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card card-height-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="text-muted mb-3">Saldo Conciliado</h5>
                                <h2 class="mb-0">
                                    {{ $simbolo }} {{ number_format($cuenta->saldoConciliado(), 2) }}
                                </h2>
                                <p class="text-muted mb-0">Última conciliación</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-soft-info text-info rounded fs-3">
                                        <i class="ri-check-double-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card card-height-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="text-muted mb-3">Total Pagos</h5>
                                <h2 class="mb-0">{{ $totalPagos }}</h2>
                                <p class="text-muted mb-0">Registrados</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-soft-warning text-warning rounded fs-3">
                                        <i class="ri-file-list-3-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card card-height-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="text-muted mb-3">Total Depositado</h5>
                                <h2 class="mb-0">
                                    {{ $simbolo }} {{ number_format($totalDepositado, 2) }}
                                </h2>
                                <p class="text-muted mb-0">Histórico</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-soft-purple text-purple rounded fs-3">
                                        <i class="ri-wallet-3-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card card-height-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="text-muted mb-3">Transferencias</h5>
                                <h2 class="mb-0">
                                    {{ $transferenciasEnviadas->count() + $transferenciasRecibidas->count() }}</h2>
                                <p class="text-muted mb-0">Env: {{ $transferenciasEnviadas->count() }} | Rec:
                                    {{ $transferenciasRecibidas->count() }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-soft-danger text-danger rounded fs-3">
                                        <i class="ri-exchange-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pestañas para diferentes secciones -->
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#informacion" role="tab">
                            <i class="ri-information-line me-1"></i> Información
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#movimientos" role="tab">
                            <i class="ri-exchange-dollar-line me-1"></i> Movimientos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#transferencias" role="tab">
                            <i class="ri-swap-line me-1"></i> Transferencias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#depositos" role="tab">
                            <i class="ri-bank-card-2-line me-1"></i> Depósitos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#conciliaciones" role="tab">
                            <i class="ri-file-check-line me-1"></i> Conciliaciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#pagos" role="tab">
                            <i class="ri-money-dollar-box-line me-1"></i> Pagos
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Pestaña de Información -->
                    <div class="tab-pane active" id="informacion" role="tabpanel">
                        <div class="row">
                            <div class="col-xl-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="ri-information-line me-2"></i>Información de la Cuenta
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label class="form-label text-muted">Banco</label>
                                                    <div class="d-flex align-items-center">
                                                        @if (isset($cuenta->banco->logo) && $cuenta->banco->logo)
                                                            <img src="{{ $cuenta->banco->logo }}" alt="Logo"
                                                                class="rounded me-3"
                                                                style="width: 40px; height: 40px; object-fit: contain;">
                                                        @else
                                                            <div class="rounded me-3"
                                                                style="width: 40px; height: 40px; background-color: {{ $cuenta->banco->color ?? '#0d6efd' }};">
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h5 class="mb-0">
                                                                {{ $cuenta->banco->nombre ?? 'No especificado' }}</h5>
                                                            <p class="text-muted mb-0">Código:
                                                                {{ $cuenta->banco->codigo ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label class="form-label text-muted">Sucursal</label>
                                                    <div>
                                                        <h5 class="mb-0">
                                                            {{ $cuenta->sucursal->nombre ?? 'Sin sucursal' }}</h5>
                                                        @if ($cuenta->sucursal)
                                                            <p class="text-muted mb-0">
                                                                {{ $cuenta->sucursal->direccion ?? '' }}<br>
                                                                @if (isset($cuenta->sucursal->sede))
                                                                    <small>Sede:
                                                                        {{ $cuenta->sucursal->sede->nombre ?? 'Sin sede' }}</small>
                                                                @endif
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label class="form-label text-muted">Número de Cuenta</label>
                                                    <h5 class="mb-0">{{ $cuenta->numero_cuenta }}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label class="form-label text-muted">Tipo de Cuenta</label>
                                                    <h5 class="mb-0">
                                                        @php
                                                            $tipos = [
                                                                'ahorro' => 'Ahorro',
                                                                'corriente' => 'Corriente',
                                                                'moneda_extranjera' => 'Moneda Extranjera',
                                                            ];
                                                            $tipo =
                                                                $tipos[$cuenta->tipo_cuenta] ?? $cuenta->tipo_cuenta;
                                                        @endphp
                                                        <span class="badge bg-info">{{ $tipo }}</span>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label class="form-label text-muted">Moneda</label>
                                                    <h5 class="mb-0">
                                                        <span class="badge bg-light text-dark">{{ $simbolo }}
                                                            ({{ $cuenta->moneda }})</span>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label class="form-label text-muted">Saldo Inicial</label>
                                                    <h5 class="mb-0">
                                                        {{ $simbolo }} {{ number_format($cuenta->saldo_inicial, 2) }}
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($cuenta->descripcion)
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="mb-4">
                                                        <label class="form-label text-muted">Descripción</label>
                                                        <p class="mb-0">{{ $cuenta->descripcion }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="ri-bar-chart-line me-2"></i>Estadísticas
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <label class="form-label text-muted">Movimientos por Mes</label>
                                            @if ($movimientosPorMes->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-sm mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Mes</th>
                                                                <th class="text-end">Cantidad</th>
                                                                <th class="text-end">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($movimientosPorMes as $estadistica)
                                                                <tr>
                                                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $estadistica->mes)->format('M Y') }}
                                                                    </td>
                                                                    <td class="text-end">{{ $estadistica->cantidad }}</td>
                                                                    <td class="text-end">
                                                                        {{ $simbolo }}
                                                                        {{ number_format($estadistica->total, 2) }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="text-center py-3">
                                                    <i class="ri-bar-chart-line text-muted display-6"></i>
                                                    <p class="text-muted mt-2">No hay movimientos registrados</p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label text-muted">Información de Registro</label>
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2">
                                                    <i class="ri-calendar-line text-primary me-2"></i>
                                                    <small>Creada:
                                                        {{ $cuenta->created_at ? $cuenta->created_at->format('d/m/Y H:i') : 'N/A' }}</small>
                                                </li>
                                                <li>
                                                    <i class="ri-refresh-line text-primary me-2"></i>
                                                    <small>Actualizada:
                                                        {{ $cuenta->updated_at ? $cuenta->updated_at->format('d/m/Y H:i') : 'N/A' }}</small>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pestaña de Movimientos -->
                    <div class="tab-pane" id="movimientos" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <i class="ri-exchange-dollar-line me-2"></i>Movimientos Bancarios
                                    </h5>
                                    <button class="btn btn-sm btn-outline-primary" id="filtrarMovimientosBtn">
                                        <i class="ri-filter-line me-1"></i> Filtrar
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @if ($movimientos->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
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
                                                @foreach ($movimientos as $movimiento)
                                                    <tr>
                                                        <td>{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>
                                                        <td>
                                                            @php
                                                                $tiposMovimientos = [
                                                                    'deposito' => 'Depósito',
                                                                    'retiro' => 'Retiro',
                                                                    'transferencia_envio' => 'Envío',
                                                                    'transferencia_recepcion' => 'Recepción',
                                                                    'pago' => 'Pago',
                                                                    'ajuste' => 'Ajuste',
                                                                    'correccion' => 'Corrección',
                                                                ];
                                                                $clasesMovimientos = [
                                                                    'deposito' => 'success',
                                                                    'retiro' => 'danger',
                                                                    'transferencia_envio' => 'warning',
                                                                    'transferencia_recepcion' => 'info',
                                                                    'pago' => 'primary',
                                                                    'ajuste' => 'secondary',
                                                                    'correccion' => 'dark',
                                                                ];
                                                            @endphp
                                                            <span
                                                                class="badge bg-{{ $clasesMovimientos[$movimiento->tipo_movimiento] ?? 'secondary' }}">
                                                                {{ $tiposMovimientos[$movimiento->tipo_movimiento] ?? $movimiento->tipo_movimiento }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $movimiento->descripcion }}</td>
                                                        <td>
                                                            @if ($movimiento->referencia)
                                                                @php
                                                                    $modelo = class_basename(
                                                                        $movimiento->referencia_type,
                                                                    );
                                                                    $modelosMostrar = [
                                                                        'Pago' => 'Pago',
                                                                        'TransferenciaBancaria' => 'Transferencia',
                                                                        'Deposito' => 'Depósito',
                                                                        'Caja' => 'Caja',
                                                                    ];
                                                                @endphp
                                                                <span class="badge bg-light text-dark">
                                                                    {{ $modelosMostrar[$modelo] ?? $modelo }}
                                                                    #{{ $movimiento->referencia_id }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary">Manual</span>
                                                            @endif
                                                        </td>
                                                        <td
                                                            class="text-end {{ $movimiento->monto >= 0 ? 'text-success' : 'text-danger' }}">
                                                            {{ $simbolo }} {{ number_format($movimiento->monto, 2) }}
                                                        </td>
                                                        <td class="text-end">{{ $simbolo }}
                                                            {{ number_format($movimiento->saldo_anterior, 2) }}</td>
                                                        <td class="text-end">{{ $simbolo }}
                                                            {{ number_format($movimiento->saldo_posterior, 2) }}</td>
                                                        <td>
                                                            @if ($movimiento->conciliado)
                                                                <span class="badge bg-success">
                                                                    <i class="ri-check-line"></i>
                                                                    {{ $movimiento->fecha_conciliacion?->format('d/m/Y') }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary">Pendiente</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $movimiento->usuario?->name ?? 'Sistema' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-3">
                                        {{ $movimientos->links() }}
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-4">
                                            <div class="avatar-title bg-soft-info text-info rounded-circle">
                                                <i class="ri-exchange-dollar-line fs-2"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-muted">No hay movimientos registrados</h5>
                                        <p class="text-muted mb-0">Esta cuenta no tiene movimientos registrados.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Pestaña de Transferencias -->
                    <div class="tab-pane" id="transferencias" role="tabpanel">
                        <ul class="nav nav-pills nav-justified mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#transferencias-enviadas"
                                    role="tab">
                                    Enviadas ({{ $transferenciasEnviadas->count() }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#transferencias-recibidas"
                                    role="tab">
                                    Recibidas ({{ $transferenciasRecibidas->count() }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#transferencias-correccion"
                                    role="tab">
                                    Correcciones ({{ $transferenciasCorreccion->count() }})
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Transferencias Enviadas -->
                            <div class="tab-pane active" id="transferencias-enviadas" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="ri-arrow-up-line me-2"></i>Transferencias Enviadas
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($transferenciasEnviadas->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-hover align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Destino</th>
                                                            <th>Banco Destino</th>
                                                            <th class="text-end">Monto</th>
                                                            <th>Tipo</th>
                                                            <th>Estado</th>
                                                            <th>Usuario</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($transferenciasEnviadas as $transferencia)
                                                            <tr>
                                                                <td>{{ $transferencia->fecha_transferencia->format('d/m/Y') }}
                                                                </td>
                                                                <td>
                                                                    {{ $transferencia->cuentaDestino->numero_cuenta }}
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        {{ $transferencia->cuentaDestino->sucursal->nombre ?? '' }}
                                                                    </small>
                                                                </td>
                                                                <td>{{ $transferencia->cuentaDestino->banco->nombre }}</td>
                                                                <td class="text-end text-danger">
                                                                    <strong>{{ $simbolo }}
                                                                        {{ number_format($transferencia->monto, 2) }}</strong>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-{{ $transferencia->tipo_transferencia == 'correccion' ? 'warning' : 'info' }}">
                                                                        {{ $transferencia->tipo_transferencia_formateado }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $estadosClases = [
                                                                            'pendiente' => 'warning',
                                                                            'procesada' => 'success',
                                                                            'cancelada' => 'danger',
                                                                            'conciliada' => 'info',
                                                                        ];
                                                                    @endphp
                                                                    <span
                                                                        class="badge bg-{{ $estadosClases[$transferencia->estado] ?? 'secondary' }}">
                                                                        {{ $transferencia->estado }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ $transferencia->usuario?->name ?? 'N/A' }}</td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-outline-info"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#modalDetalleTransferencia"
                                                                        data-transferencia-id="{{ $transferencia->id }}">
                                                                        <i class="ri-eye-line"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <div class="avatar-lg mx-auto mb-4">
                                                    <div class="avatar-title bg-soft-warning text-warning rounded-circle">
                                                        <i class="ri-arrow-up-line fs-2"></i>
                                                    </div>
                                                </div>
                                                <h5 class="text-muted">No hay transferencias enviadas</h5>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Transferencias Recibidas -->
                            <div class="tab-pane" id="transferencias-recibidas" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="ri-arrow-down-line me-2"></i>Transferencias Recibidas
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($transferenciasRecibidas->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-hover align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Origen</th>
                                                            <th>Banco Origen</th>
                                                            <th class="text-end">Monto</th>
                                                            <th>Tipo</th>
                                                            <th>Estado</th>
                                                            <th>Usuario</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($transferenciasRecibidas as $transferencia)
                                                            <tr>
                                                                <td>{{ $transferencia->fecha_transferencia->format('d/m/Y') }}
                                                                </td>
                                                                <td>
                                                                    {{ $transferencia->cuentaOrigen->numero_cuenta }}
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        {{ $transferencia->cuentaOrigen->sucursal->nombre ?? '' }}
                                                                    </small>
                                                                </td>
                                                                <td>{{ $transferencia->cuentaOrigen->banco->nombre }}</td>
                                                                <td class="text-end text-success">
                                                                    <strong>{{ $simbolo }}
                                                                        {{ number_format($transferencia->monto, 2) }}</strong>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-{{ $transferencia->tipo_transferencia == 'correccion' ? 'warning' : 'info' }}">
                                                                        {{ $transferencia->tipo_transferencia_formateado }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-{{ $estadosClases[$transferencia->estado] ?? 'secondary' }}">
                                                                        {{ $transferencia->estado }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ $transferencia->usuario?->name ?? 'N/A' }}</td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-outline-info"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#modalDetalleTransferencia"
                                                                        data-transferencia-id="{{ $transferencia->id }}">
                                                                        <i class="ri-eye-line"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <div class="avatar-lg mx-auto mb-4">
                                                    <div class="avatar-title bg-soft-warning text-warning rounded-circle">
                                                        <i class="ri-arrow-down-line fs-2"></i>
                                                    </div>
                                                </div>
                                                <h5 class="text-muted">No hay transferencias recibidas</h5>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Transferencias de Corrección -->
                            <div class="tab-pane" id="transferencias-correccion" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="ri-refresh-line me-2"></i>Transferencias de Corrección
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($transferenciasCorreccion->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-hover align-middle mb-0">
                                                    <thead class="table-light">
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
                                                        @foreach ($transferenciasCorreccion as $transferencia)
                                                            <tr>
                                                                <td>{{ $transferencia->fecha_transferencia->format('d/m/Y') }}
                                                                </td>
                                                                <td>
                                                                    @if ($transferencia->pago)
                                                                        <span class="badge bg-light text-dark">
                                                                            Pago #{{ $transferencia->pago->recibo }}
                                                                        </span>
                                                                    @else
                                                                        <span class="badge bg-secondary">N/A</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    {{ $transferencia->cuentaDestino->numero_cuenta }}
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        {{ $transferencia->cuentaDestino->banco->nombre }}
                                                                    </small>
                                                                </td>
                                                                <td class="text-end text-warning">
                                                                    <strong>{{ $simbolo }}
                                                                        {{ number_format($transferencia->monto, 2) }}</strong>
                                                                </td>
                                                                <td>
                                                                    <small>{{ Str::limit($transferencia->motivo_correccion, 50) }}</small>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-{{ $estadosClases[$transferencia->estado] ?? 'secondary' }}">
                                                                        {{ $transferencia->estado }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ $transferencia->usuario?->name ?? 'N/A' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <div class="avatar-lg mx-auto mb-4">
                                                    <div class="avatar-title bg-soft-warning text-warning rounded-circle">
                                                        <i class="ri-refresh-line fs-2"></i>
                                                    </div>
                                                </div>
                                                <h5 class="text-muted">No hay transferencias de corrección</h5>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pestaña de Depósitos -->
                    <div class="tab-pane" id="depositos" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="ri-bank-card-2-line me-2"></i>Depósitos desde Cajas
                                </h5>
                            </div>
                            <div class="card-body">
                                @if ($depositos->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Caja</th>
                                                    <th class="text-end">Monto</th>
                                                    <th>Comprobante</th>
                                                    <th>Estado</th>
                                                    <th>Usuario</th>
                                                    <th>Descripción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($depositos as $deposito)
                                                    <tr>
                                                        <td>{{ $deposito->fecha_deposito->format('d/m/Y') }}</td>
                                                        <td>
                                                            {{ $deposito->caja->nombre ?? 'N/A' }}
                                                            <br>
                                                            <small class="text-muted">
                                                                {{ $deposito->caja->sucursal->nombre ?? '' }}
                                                            </small>
                                                        </td>
                                                        <td class="text-end text-success">
                                                            <strong>{{ $simbolo }}
                                                                {{ number_format($deposito->monto, 2) }}</strong>
                                                        </td>
                                                        <td>
                                                            @if ($deposito->comprobante)
                                                                <span
                                                                    class="badge bg-light text-dark">{{ $deposito->comprobante }}</span>
                                                            @else
                                                                <span class="badge bg-secondary">Sin comprobante</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $estadosDepositos = [
                                                                    'pendiente' => 'warning',
                                                                    'confirmado' => 'success',
                                                                    'cancelado' => 'danger',
                                                                ];
                                                            @endphp
                                                            <span
                                                                class="badge bg-{{ $estadosDepositos[$deposito->estado] ?? 'secondary' }}">
                                                                {{ $deposito->estado }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $deposito->user?->name ?? 'N/A' }}</td>
                                                        <td>
                                                            <small>{{ Str::limit($deposito->descripcion, 50) }}</small>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-4">
                                            <div class="avatar-title bg-soft-info text-info rounded-circle">
                                                <i class="ri-bank-card-2-line fs-2"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-muted">No hay depósitos registrados</h5>
                                        <p class="text-muted mb-0">No se han realizado depósitos desde cajas a esta cuenta.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Pestaña de Conciliaciones -->
                    <div class="tab-pane" id="conciliaciones" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <i class="ri-file-check-line me-2"></i>Conciliaciones Bancarias
                                    </h5>
                                    @if (Auth::guard('web')->user()->can('cuentas-bancarias.conciliar'))
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#modalNuevaConciliacion">
                                            <i class="ri-add-line me-1"></i> Nueva Conciliación
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                @if ($conciliaciones->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Período</th>
                                                    <th class="text-end">Saldo Libros</th>
                                                    <th class="text-end">Saldo Extracto</th>
                                                    <th class="text-end">Diferencia</th>
                                                    <th>Estado</th>
                                                    <th>Usuario</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($conciliaciones as $conciliacion)
                                                    <tr>
                                                        <td>
                                                            {{ $conciliacion->fecha_inicio->format('d/m/Y') }} -
                                                            {{ $conciliacion->fecha_fin->format('d/m/Y') }}
                                                        </td>
                                                        <td class="text-end">{{ $simbolo }}
                                                            {{ number_format($conciliacion->saldo_libros, 2) }}</td>
                                                        <td class="text-end">{{ $simbolo }}
                                                            {{ number_format($conciliacion->saldo_extracto, 2) }}</td>
                                                        <td
                                                            class="text-end {{ $conciliacion->diferencia >= 0 ? 'text-success' : 'text-danger' }}">
                                                            <strong>{{ $simbolo }}
                                                                {{ number_format($conciliacion->diferencia, 2) }}</strong>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $estadosConciliaciones = [
                                                                    'pendiente' => 'warning',
                                                                    'en_proceso' => 'info',
                                                                    'conciliada' => 'success',
                                                                    'cerrada' => 'secondary',
                                                                ];
                                                            @endphp
                                                            <span
                                                                class="badge bg-{{ $estadosConciliaciones[$conciliacion->estado] ?? 'secondary' }}">
                                                                {{ $conciliacion->estado }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $conciliacion->usuario?->name ?? 'N/A' }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-info"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalDetalleConciliacion"
                                                                data-conciliacion-id="{{ $conciliacion->id }}">
                                                                <i class="ri-eye-line"></i>
                                                            </button>
                                                            @if ($conciliacion->estado == 'pendiente' || $conciliacion->estado == 'en_proceso')
                                                                <button class="btn btn-sm btn-outline-warning ms-1"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalEditarConciliacion"
                                                                    data-conciliacion-id="{{ $conciliacion->id }}">
                                                                    <i class="ri-edit-line"></i>
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-4">
                                            <div class="avatar-title bg-soft-info text-info rounded-circle">
                                                <i class="ri-file-check-line fs-2"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-muted">No hay conciliaciones registradas</h5>
                                        <p class="text-muted mb-0">Esta cuenta no tiene conciliaciones bancarias
                                            registradas.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Pestaña de Pagos -->
                    <div class="tab-pane" id="pagos" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <i class="ri-money-dollar-box-line me-2"></i>Historial de Pagos
                                        <span class="badge bg-primary ms-2">{{ $totalPagos }}</span>
                                    </h5>
                                    @if ($totalPagos > 0)
                                        <div>
                                            <a href="#" class="btn btn-outline-primary btn-sm">
                                                <i class="ri-download-line me-1"></i> Exportar
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                @if ($pagosRecientes->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Estudiante</th>
                                                    <th>Concepto</th>
                                                    <th class="text-end">Monto</th>
                                                    <th>N° Comprobante</th>
                                                    <th>Tipo Pago</th>
                                                    <th>Estado</th>
                                                    <th class="text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pagosRecientes as $pago)
                                                    <tr>
                                                        <td>
                                                            @if ($pago->fecha_pago)
                                                                {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                                                                <br>
                                                                <small
                                                                    class="text-muted">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('H:i') }}</small>
                                                            @else
                                                                <span class="text-muted">Fecha no registrada</span>
                                                            @endif
                                                        </td>
                                                        {{-- En la pestaña de Pagos, cambia esta sección: --}}
                                                        <td>
                                                            @if (isset($pago->estudiante) && isset($pago->estudiante->persona))
                                                                {{ $pago->estudiante->persona->nombres ?? '' }}
                                                                {{ $pago->estudiante->persona->apellido_paterno ?? '' }}
                                                                <br>
                                                                <small
                                                                    class="text-muted">{{ $pago->estudiante->persona->carnet ?? '' }}</small>
                                                            @else
                                                                <span class="text-muted">No disponible</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (isset($pago->inscripcion) && isset($pago->inscripcion->ofertaAcademica))
                                                                {{ $pago->inscripcion->ofertaAcademica->programa->nombre ?? 'Programa' }}
                                                            @else
                                                                {{ $pago->concepto ?? 'Pago' }}
                                                            @endif
                                                        </td>
                                                        <td class="text-end">
                                                            <strong
                                                                class="{{ $pago->pago_bs >= 0 ? 'text-success' : 'text-danger' }}">
                                                                {{ $simbolo }}
                                                                {{ number_format($pago->pago_bs, 2) }}
                                                            </strong>
                                                        </td>
                                                        <td>
                                                            @if ($pago->n_comprobante)
                                                                <span
                                                                    class="badge bg-light text-dark">{{ $pago->n_comprobante }}</span>
                                                            @else
                                                                <span class="badge bg-secondary">Sin comprobante</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $tiposPago = [
                                                                    'transferencia' => 'Transferencia',
                                                                    'efectivo' => 'Efectivo',
                                                                    'deposito' => 'Depósito',
                                                                ];
                                                            @endphp
                                                            <span
                                                                class="badge bg-info">{{ $tiposPago[$pago->tipo_pago] ?? $pago->tipo_pago }}</span>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $estadosPago = [
                                                                    'registrado' => 'primary',
                                                                    'depositado' => 'success',
                                                                    'conciliado' => 'info',
                                                                    'anulado' => 'danger',
                                                                ];
                                                            @endphp
                                                            <span
                                                                class="badge bg-{{ $estadosPago[$pago->estado] ?? 'secondary' }}">
                                                                {{ $pago->estado ?? 'registrado' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="btn-group btn-group-sm">
                                                                <button type="button" class="btn btn-outline-info"
                                                                    data-bs-toggle="tooltip" title="Ver Detalle">
                                                                    <i class="ri-eye-line"></i>
                                                                </button>
                                                                @if (isset($pago->recibo_url))
                                                                    <a href="{{ $pago->recibo_url }}" target="_blank"
                                                                        class="btn btn-outline-primary"
                                                                        data-bs-toggle="tooltip" title="Descargar Recibo">
                                                                        <i class="ri-download-line"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if ($totalPagos > 10)
                                        <div class="text-center mt-3">
                                            <a href="#" class="btn btn-outline-primary">
                                                <i class="ri-eye-line me-1"></i> Ver todos los pagos
                                                ({{ $totalPagos }})
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-4">
                                            <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                <i class="ri-file-list-3-line fs-2"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-muted">No hay pagos registrados</h5>
                                        <p class="text-muted mb-0">Esta cuenta no tiene pagos registrados.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales adicionales -->
    @include('admin.cuentas_bancarias.modals.transferencia')

    @include('admin.cuentas_bancarias.modals.detalle_transferencia')


    <style>
        .card-height-100 {
            height: calc(100% - 1rem);
        }

        .avatar-sm {
            width: 3rem;
            height: 3rem;
        }

        .avatar-lg {
            width: 4rem;
            height: 4rem;
        }

        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            font-weight: 500;
            padding: 12px 20px;
        }

        .nav-tabs-custom .nav-link.active {
            border-bottom: 3px solid #405189;
            color: #405189;
            background-color: transparent;
        }

        .badge.bg-soft-purple {
            background-color: rgba(118, 109, 244, 0.15);
            color: #766df4;
        }
    </style>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Manejar botón de edición
            $('.editBtn').on('click', function() {
                var data = $(this).data('bs-obj');
                // Rellenar formulario de edición
                $('#cuentaId').val(data.id);
                $('#banco_id_edicion').val(data.banco_id);
                $('#sucursale_id_edicion').val(data.sucursale_id);
                $('#numero_cuenta_edicion').val(data.numero_cuenta);
                $('#tipo_cuenta_edicion').val(data.tipo_cuenta);
                $('#moneda_edicion').val(data.moneda);
                $('#descripcion_edicion').val(data.descripcion || '');
                $('#saldo_inicial_edicion').val(data.saldo_inicial);
                $('#activa_edicion').prop('checked', data.activa == 1 || data.activa === true);
                $('#feedback_numero_cuenta_edicion').removeClass('text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
            });

            // Manejar botón de eliminación
            $('.deleteBtn').on('click', function() {
                var data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);
                $('#warning-pagos').hide();
                $('.btnDelete').prop('disabled', false);
            });

            // Modal de transferencia
            $('#modalTransferencia').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var cuentaId = button.data('cuenta-id');
                $('#cuenta_origen_id').val(cuentaId);
            });

            // Cargar cuentas destino para transferencia
            $('#cargarCuentasDestino').on('click', function() {
                var bancoId = $('#banco_destino_id').val();
                var sucursalId = $('#sucursal_destino_id').val();

                if (bancoId && sucursalId) {
                    $.ajax({
                        url: '{{ route('admin.cuentas-bancarias.por-banco-sucursal') }}',
                        method: 'GET',
                        data: {
                            banco_id: bancoId,
                            sucursale_id: sucursalId,
                            excluir: $('#cuenta_origen_id').val()
                        },
                        success: function(response) {
                            $('#cuenta_destino_id').empty().append(
                                '<option value="">Seleccionar cuenta...</option>');
                            $.each(response, function(index, cuenta) {
                                $('#cuenta_destino_id').append('<option value="' +
                                    cuenta.id + '">' +
                                    cuenta.numero_cuenta + ' - ' + cuenta
                                    .tipo_cuenta + ' (' + cuenta.moneda + ')' +
                                    '</option>');
                            });
                        }
                    });
                }
            });

            // Recargar después de editar o eliminar
            $('#modalModificar').on('hidden.bs.modal', function(e) {
                if ($(e.target).hasClass('show')) {
                    location.reload();
                }
            });

            $('#modalEliminar').on('hidden.bs.modal', function(e) {
                if (!$(e.target).hasClass('show')) {
                    setTimeout(function() {
                        window.location.href = "{{ route('admin.cuentas-bancarias.listar') }}";
                    }, 1000);
                }
            });

            // Mostrar/ocultar detalles de transferencia
            $('[data-bs-target="#modalDetalleTransferencia"]').on('click', function() {
                var transferenciaId = $(this).data('transferencia-id');
                // Cargar detalles de la transferencia
                $.ajax({
                    url: '/admin/transferencias/' + transferenciaId + '/detalle',
                    method: 'GET',
                    success: function(response) {
                        $('#modalDetalleTransferencia .modal-body').html(response);
                    }
                });
            });

            // Mostrar/ocultar detalles de conciliación
            $('[data-bs-target="#modalDetalleConciliacion"]').on('click', function() {
                var conciliacionId = $(this).data('conciliacion-id');
                // Cargar detalles de la conciliación
                $.ajax({
                    url: '/admin/conciliaciones/' + conciliacionId + '/detalle',
                    method: 'GET',
                    success: function(response) {
                        $('#modalDetalleConciliacion .modal-body').html(response);
                    }
                });
            });
        });
    </script>
@endpush
