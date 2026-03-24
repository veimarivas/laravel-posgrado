{{-- resources/views/admin/sucursales/ver.blade.php --}}
@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Header con gradiente -->
        <div class="row">
            <div class="col-12">
                <div
                    class="page-title-box d-flex align-items-center justify-content-between bg-gradient-primary p-3 rounded-3 mb-3 shadow-sm">
                    <div>
                        <h4 class="mb-0 text-white">
                            <i class="ri-store-2-line me-2"></i>Detalle de Sucursal
                        </h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.sedes.listar') }}"
                                        class="text-white-50">Sedes</a></li>
                                <li class="breadcrumb-item active text-white">Sucursal: {{ $sucursal->nombre }}</li>
                            </ol>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('admin.sedes.listar') }}" class="btn btn-light btn-sm">
                            <i class="ri-arrow-left-line me-1"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de la Sucursal -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0">
                            <i class="ri-information-line me-2"></i>Información de la Sucursal
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="ri-building-line me-1"></i> Nombre:</label>
                                    <p class="mb-0">{{ $sucursal->nombre }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="ri-home-2-line me-1"></i> Sede:</label>
                                    <p class="mb-0">{{ $sucursal->sede->nombre ?? 'No asignada' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="ri-map-pin-line me-1"></i>
                                        Dirección:</label>
                                    <p class="mb-0">{{ $sucursal->direccion }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="ri-palette-line me-1"></i> Color:</label>
                                    <div class="d-flex align-items-center">
                                        <div class="color-preview me-2 rounded-circle"
                                            style="width: 20px; height: 20px; background-color: {{ $sucursal->color }}; border: 1px solid #ddd;">
                                        </div>
                                        <span>{{ $sucursal->color }}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="ri-map-2-line me-1"></i>
                                        Coordenadas:</label>
                                    <p class="mb-0">
                                        @if ($sucursal->latitud && $sucursal->longitud)
                                            Lat: {{ $sucursal->latitud }}, Long: {{ $sucursal->longitud }}
                                        @else
                                            No registradas
                                        @endif
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="ri-checkbox-circle-line me-1"></i>
                                        Estado:</label>
                                    <p class="mb-0">
                                        <span class="badge bg-success">Activa</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cuentas Bancarias -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-bank-card-line me-2"></i>Cuentas Bancarias
                        </h5>
                        @if (Auth::guard('web')->user()->can('cuentas-bancarias.registrar'))
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalRegistrarCuentaBancaria">
                                <i class="ri-add-line me-1"></i> Agregar Cuenta
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if ($sucursal->cuentas_bancarias && $sucursal->cuentas_bancarias->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Banco</th>
                                            <th>Número de Cuenta</th>
                                            <th>Tipo</th>
                                            <th>Moneda</th>
                                            <th>Saldo Actual</th>
                                            <th>Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sucursal->cuentas_bancarias as $cuenta)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($cuenta->banco->logo)
                                                            <img src="{{ $cuenta->banco->logo }}" alt="Logo"
                                                                class="rounded me-2"
                                                                style="width: 24px; height: 24px; object-fit: contain;">
                                                        @else
                                                            <div class="rounded me-2"
                                                                style="width: 24px; height: 24px; background-color: {{ $cuenta->banco->color ?? '#0d6efd' }};">
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="fw-medium">{{ $cuenta->banco->nombre }}</div>
                                                            <small class="text-muted">{{ $cuenta->banco->codigo }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-medium">{{ $cuenta->numero_cuenta }}</div>
                                                    <small
                                                        class="text-muted">{{ $cuenta->descripcion ?: 'Sin descripción' }}</small>
                                                </td>
                                                <td>
                                                    @php
                                                        $tipoColors = [
                                                            'ahorro' => 'info',
                                                            'corriente' => 'primary',
                                                            'moneda_extranjera' => 'warning',
                                                        ];
                                                        $color = $tipoColors[$cuenta->tipo_cuenta] ?? 'secondary';
                                                    @endphp
                                                    <span
                                                        class="badge bg-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $cuenta->tipo_cuenta)) }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $monedaSymbols = [
                                                            'BS' => 'Bs',
                                                            'USD' => '$',
                                                            'EUR' => '€',
                                                        ];
                                                        $symbol = $monedaSymbols[$cuenta->moneda] ?? $cuenta->moneda;
                                                    @endphp
                                                    <span class="badge bg-light text-dark">{{ $symbol }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <div
                                                        class="fw-bold {{ $cuenta->saldo_actual >= 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ $symbol }} {{ number_format($cuenta->saldo_actual, 2) }}
                                                    </div>
                                                    <small class="text-muted">Inicial: {{ $symbol }}
                                                        {{ number_format($cuenta->saldo_inicial, 2) }}</small>
                                                </td>
                                                <td>
                                                    @if ($cuenta->activa)
                                                        <span class="badge bg-success">Activa</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactiva</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-1">
                                                        @if (Auth::guard('web')->user()->can('cuentas-bancarias.ver'))
                                                            <a href="{{ route('admin.cuentas-bancarias.ver', $cuenta->id) }}"
                                                                class="btn btn-sm btn-info" title="Ver Detalle">
                                                                <i class="ri-eye-line"></i>
                                                            </a>
                                                        @endif
                                                        @if (Auth::guard('web')->user()->can('cuentas-bancarias.editar'))
                                                            <button type="button"
                                                                class="btn btn-sm btn-warning editCuentaBtn"
                                                                title="Editar Cuenta" data-bs-toggle="modal"
                                                                data-bs-target="#modalEditarCuentaBancaria"
                                                                data-cuenta='@json($cuenta)'>
                                                                <i class="ri-edit-line"></i>
                                                            </button>
                                                        @endif
                                                        @if (Auth::guard('web')->user()->can('cuentas-bancarias.eliminar'))
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger deleteCuentaBtn"
                                                                title="Eliminar Cuenta" data-bs-toggle="modal"
                                                                data-bs-target="#modalEliminarCuentaBancaria"
                                                                data-id="{{ $cuenta->id }}">
                                                                <i class="ri-delete-bin-line"></i>
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
                            <div class="text-center py-5">
                                <i class="ri-bank-line fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No hay cuentas bancarias registradas para esta sucursal.</p>
                                @if (Auth::guard('web')->user()->can('cuentas-bancarias.registrar'))
                                    <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal"
                                        data-bs-target="#modalRegistrarCuentaBancaria">
                                        <i class="ri-add-line me-1"></i> Registrar Cuenta Bancaria
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Cajas -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-cash-line me-2"></i>Cajas
                        </h5>
                        @if (Auth::guard('web')->user()->can('cajas.registrar'))
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalRegistrarCaja">
                                <i class="ri-add-line me-1"></i> Registrar Caja
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if ($sucursal->cajas && $sucursal->cajas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Responsable</th>
                                            <th>Descripción</th>
                                            <th>Moneda</th>
                                            <th>Saldo Inicial</th>
                                            <th>Saldo Actual</th>
                                            <th>Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sucursal->cajas as $caja)
                                            <tr>
                                                <td class="fw-medium">{{ $caja->nombre }}</td>
                                                <td>
                                                    {{ $caja->responsable->trabajador->persona->nombres ?? 'N/A' }}
                                                    {{ $caja->responsable->trabajador->persona->apellido_paterno ?? '' }}
                                                </td>
                                                <td>{{ $caja->descripcion ?: 'Sin descripción' }}</td>
                                                <td>
                                                    <span class="badge bg-light text-dark">{{ $caja->moneda }}</span>
                                                </td>
                                                <td>
                                                    {{ $caja->moneda == 'BS' ? 'Bs' : '$' }}
                                                    {{ number_format($caja->saldo_inicial, 2) }}
                                                </td>
                                                <td class="text-end">
                                                    <div
                                                        class="fw-bold {{ $caja->saldo_actual >= 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ $caja->moneda == 'BS' ? 'Bs' : '$' }}
                                                        {{ number_format($caja->saldo_actual, 2) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($caja->activa)
                                                        <span class="badge bg-success">Activa</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactiva</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <a href="#" class="btn btn-sm btn-info verCajaBtn"
                                                            title="Ver Detalle" data-id="{{ $caja->id }}">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                        @if (Auth::guard('web')->user()->can('cajas.editar'))
                                                            <a href="#" class="btn btn-sm btn-warning"
                                                                title="Editar">
                                                                <i class="ri-edit-line"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ri-cash-line fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No hay cajas registradas para esta sucursal.</p>
                                @if (Auth::guard('web')->user()->can('cajas.registrar'))
                                    <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal"
                                        data-bs-target="#modalRegistrarCaja">
                                        <i class="ri-add-line me-1"></i> Registrar Caja
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Cuenta Bancaria -->
    @if (Auth::guard('web')->user()->can('cuentas-bancarias.registrar'))
        <div class="modal fade" id="modalRegistrarCuentaBancaria" tabindex="-1"
            aria-labelledby="modalRegistrarCuentaBancariaLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary bg-soft">
                        <h5 class="modal-title text-white" id="modalRegistrarCuentaBancariaLabel">
                            <i class="ri-add-line me-2"></i>Registrar Nueva Cuenta Bancaria
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formRegistrarCuentaBancaria" class="forms-sample">
                            @csrf
                            <input type="hidden" name="sucursale_id" value="{{ $sucursal->id }}">

                            <div class="mb-3">
                                <label class="form-label">Banco <span class="text-danger">*</span></label>
                                <select class="form-control" id="banco_id_registro" name="banco_id" required>
                                    <option value="">Seleccionar Banco</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Número de Cuenta <span class="text-danger">*</span></label>
                                <input type="text" id="numero_cuenta_registro" name="numero_cuenta"
                                    class="form-control" placeholder="Ej: 1234567890" required>
                                <div id="feedback_numero_cuenta_registro" class="form-text"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tipo de Cuenta <span class="text-danger">*</span></label>
                                    <select class="form-control" id="tipo_cuenta_registro" name="tipo_cuenta" required>
                                        <option value="">Seleccionar Tipo</option>
                                        <option value="ahorro">Ahorro</option>
                                        <option value="corriente">Corriente</option>
                                        <option value="moneda_extranjera">Moneda Extranjera</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Moneda <span class="text-danger">*</span></label>
                                    <select class="form-control" id="moneda_registro" name="moneda" required>
                                        <option value="">Seleccionar Moneda</option>
                                        <option value="BS">Bolivianos (BS)</option>
                                        <option value="USD">Dólares (USD)</option>
                                        <option value="EUR">Euros (EUR)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion_registro" name="descripcion" rows="2"
                                    placeholder="Descripción opcional..."></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Saldo Inicial <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" id="saldo_inicial_registro"
                                        name="saldo_inicial" class="form-control" placeholder="0.00" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Estado</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="activa" value="0">
                                        <input class="form-check-input" type="checkbox" id="activa_registro"
                                            name="activa" value="1" checked>
                                        <label class="form-check-label" for="activa_registro">Activa</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" form="formRegistrarCuentaBancaria" class="btn btn-primary addCuentaBtn"
                            disabled>
                            <i class="ri-save-line me-1"></i> Registrar Cuenta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Editar Cuenta Bancaria -->
    @if (Auth::guard('web')->user()->can('cuentas-bancarias.editar'))
        <div class="modal fade" id="modalEditarCuentaBancaria" tabindex="-1"
            aria-labelledby="modalEditarCuentaBancariaLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning bg-soft">
                        <h5 class="modal-title" id="modalEditarCuentaBancariaLabel">
                            <i class="ri-edit-line me-2"></i>Modificar Cuenta Bancaria
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEditarCuentaBancaria" class="forms-sample">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="cuenta_id_editar">
                            <input type="hidden" name="sucursale_id" value="{{ $sucursal->id }}">

                            <div class="mb-3">
                                <label class="form-label">Banco <span class="text-danger">*</span></label>
                                <select class="form-control" id="banco_id_editar" name="banco_id" required>
                                    <option value="">Seleccionar Banco</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Número de Cuenta <span class="text-danger">*</span></label>
                                <input type="text" id="numero_cuenta_editar" name="numero_cuenta"
                                    class="form-control" required>
                                <div id="feedback_numero_cuenta_editar" class="form-text"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tipo de Cuenta <span class="text-danger">*</span></label>
                                    <select class="form-control" id="tipo_cuenta_editar" name="tipo_cuenta" required>
                                        <option value="">Seleccionar Tipo</option>
                                        <option value="ahorro">Ahorro</option>
                                        <option value="corriente">Corriente</option>
                                        <option value="moneda_extranjera">Moneda Extranjera</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Moneda <span class="text-danger">*</span></label>
                                    <select class="form-control" id="moneda_editar" name="moneda" required>
                                        <option value="">Seleccionar Moneda</option>
                                        <option value="BS">Bolivianos (BS)</option>
                                        <option value="USD">Dólares (USD)</option>
                                        <option value="EUR">Euros (EUR)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion_editar" name="descripcion" rows="2"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Saldo Inicial <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" id="saldo_inicial_editar"
                                        name="saldo_inicial" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Estado</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="activa" value="0">
                                        <input class="form-check-input" type="checkbox" id="activa_editar"
                                            name="activa" value="1">
                                        <label class="form-check-label" for="activa_editar">Activa</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" form="formEditarCuentaBancaria" class="btn btn-warning updateCuentaBtn"
                            disabled>
                            <i class="ri-save-line me-1"></i> Actualizar Cuenta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Eliminar Cuenta Bancaria -->
    @if (Auth::guard('web')->user()->can('cuentas-bancarias.eliminar'))
        <div class="modal fade" id="modalEliminarCuentaBancaria" tabindex="-1"
            aria-labelledby="modalEliminarCuentaBancariaLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger bg-soft">
                        <h5 class="modal-title text-white" id="modalEliminarCuentaBancariaLabel">
                            <i class="ri-delete-bin-line me-2"></i>Eliminar Cuenta Bancaria
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEliminarCuentaBancaria" class="forms-sample">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" id="eliminar_id">
                            <div class="text-center mb-4">
                                <div class="avatar-lg mx-auto mb-4">
                                    <div class="avatar-title bg-danger bg-soft text-danger rounded-circle">
                                        <i class="ri-error-warning-line fs-2"></i>
                                    </div>
                                </div>
                                <h5>¿Está seguro de eliminar esta cuenta bancaria?</h5>
                                <p class="text-muted">Esta acción no se puede deshacer.</p>
                                <div class="alert alert-warning mt-3" id="warning-pagos" style="display:none;">
                                    <i class="ri-alert-line me-1"></i> <strong>Advertencia:</strong> Esta cuenta tiene
                                    pagos
                                    asociados y no puede ser eliminada.
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" form="formEliminarCuentaBancaria"
                            class="btn btn-danger deleteCuentaConfirmBtn">
                            <i class="ri-delete-bin-line me-1"></i> Sí, Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Registrar Caja (existente, mejorado) -->
    @if (Auth::guard('web')->user()->can('cajas.registrar'))
        <div class="modal fade" id="modalRegistrarCaja" tabindex="-1" aria-labelledby="modalRegistrarCajaLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary bg-soft">
                        <h5 class="modal-title text-white" id="modalRegistrarCajaLabel">
                            <i class="ri-add-line me-2"></i>Registrar Nueva Caja
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formRegistrarCaja" class="forms-sample">
                            @csrf
                            <input type="hidden" name="sucursale_id" value="{{ $sucursal->id }}">

                            <div class="mb-3">
                                <label class="form-label">Nombre de la Caja <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" class="form-control"
                                    placeholder="Ej: Caja Principal" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="2" placeholder="Descripción de la caja..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Responsable <span class="text-danger">*</span></label>
                                <select name="responsable_id" class="form-control select2" required>
                                    <option value="">Seleccionar responsable...</option>
                                    @foreach ($trabajadores as $trabajador)
                                        @if ($trabajador && $trabajador['id'] && $trabajador['text'])
                                            <option value="{{ $trabajador['id'] }}">
                                                {{ $trabajador['text'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @if (count($trabajadores) > 0)
                                    <small class="text-muted">Se muestran solo trabajadores con cargos autorizados para
                                        caja</small>
                                @else
                                    <small class="text-danger">No hay trabajadores con cargos autorizados para caja en esta
                                        sucursal</small>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Moneda <span class="text-danger">*</span></label>
                                    <select name="moneda" class="form-control" required>
                                        <option value="BS">Bolivianos (Bs)</option>
                                        <option value="USD">Dólares (USD)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Saldo Inicial <span class="text-danger">*</span></label>
                                    <input type="number" name="saldo_inicial" class="form-control" min="0"
                                        step="0.01" placeholder="0.00" required>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" form="formRegistrarCaja" class="btn btn-primary" id="btnRegistrarCaja">
                            <i class="ri-save-line me-1"></i> Registrar Caja
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Ver Caja -->
    <div class="modal fade" id="modalVerCaja" tabindex="-1" aria-labelledby="modalVerCajaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info bg-soft">
                    <h5 class="modal-title text-white" id="modalVerCajaLabel">
                        <i class="ri-eye-line me-2"></i>Detalle de Caja
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="cajaDetalleContent">
                    <!-- Se cargará dinámicamente -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .color-preview {
            border-radius: 4px;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .bg-soft {
            background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
        }

        .bg-warning.bg-soft {
            background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
        }

        .bg-danger.bg-soft {
            background-color: rgba(var(--bs-danger-rgb), 0.1) !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #556ee6 0%, #364fc7 100%);
        }

        .avatar-lg {
            height: 5rem;
            width: 5rem;
        }

        .avatar-title {
            align-items: center;
            display: flex;
            height: 100%;
            justify-content: center;
            width: 100%;
        }

        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .toast {
            min-width: 250px;
        }
    </style>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            let debounceTimer;

            // -------------------- CUENTAS BANCARIAS: REGISTRO --------------------
            @if (Auth::guard('web')->user()->can('cuentas-bancarias.registrar'))
                // Cargar bancos al abrir el modal
                $('#modalRegistrarCuentaBancaria').on('show.bs.modal', function() {
                    cargarBancosRegistro();
                });

                function cargarBancosRegistro() {
                    $.ajax({
                        url: "{{ route('admin.cuentas-bancarias.obtener-bancos') }}",
                        type: "GET",
                        success: function(response) {
                            if (response.success) {
                                let options = '<option value="">Seleccionar Banco</option>';
                                response.bancos.forEach(banco => {
                                    options +=
                                        `<option value="${banco.id}">${banco.nombre} (${banco.codigo})</option>`;
                                });
                                $('#banco_id_registro').html(options);
                            } else {
                                console.error('Error al cargar bancos:', response.message);
                                showNotification('error', 'Error al cargar la lista de bancos');
                            }
                        },
                        error: function() {
                            showNotification('error', 'Error al cargar la lista de bancos');
                        }
                    });
                }

                // Validar número de cuenta en tiempo real
                $('#numero_cuenta_registro').on('input', function() {
                    const numeroCuenta = $(this).val().trim();
                    const bancoId = $('#banco_id_registro').val();
                    const sucursalId = '{{ $sucursal->id }}';
                    const feedback = $('#feedback_numero_cuenta_registro');
                    const submitBtn = $('.addCuentaBtn');

                    feedback.removeClass('text-success text-danger').text('');

                    if (numeroCuenta.length === 0 || !bancoId) {
                        submitBtn.prop('disabled', true);
                        return;
                    }

                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        $.ajax({
                            url: "{{ route('admin.cuentas-bancarias.verificar') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                banco_id: bancoId,
                                sucursale_id: sucursalId,
                                numero_cuenta: numeroCuenta
                            },
                            success: function(res) {
                                if (res.exists) {
                                    feedback.addClass('text-danger').text(
                                        '⚠️ Esta cuenta ya está registrada en este banco y sucursal.'
                                    );
                                    submitBtn.prop('disabled', true);
                                } else {
                                    feedback.addClass('text-success').text(
                                        '✅ Número de cuenta disponible.');
                                    validarFormularioRegistro();
                                }
                            },
                            error: function() {
                                feedback.addClass('text-danger').text(
                                    '❌ Error al verificar la cuenta.');
                                submitBtn.prop('disabled', true);
                            }
                        });
                    }, 300);
                });

                function validarFormularioRegistro() {
                    const bancoId = $('#banco_id_registro').val();
                    const numeroCuenta = $('#numero_cuenta_registro').val().trim();
                    const tipoCuenta = $('#tipo_cuenta_registro').val();
                    const moneda = $('#moneda_registro').val();
                    const saldoInicial = $('#saldo_inicial_registro').val();
                    const submitBtn = $('.addCuentaBtn');

                    let valido = true;

                    if (!bancoId || !numeroCuenta || !tipoCuenta || !moneda || !saldoInicial) {
                        valido = false;
                    }

                    submitBtn.prop('disabled', !valido);
                }

                $('#banco_id_registro, #tipo_cuenta_registro, #moneda_registro, #saldo_inicial_registro')
                    .on('change', validarFormularioRegistro);
                $('#saldo_inicial_registro').on('input', validarFormularioRegistro);

                // Submit registro
                $('#formRegistrarCuentaBancaria').submit(function(e) {
                    e.preventDefault();
                    const submitBtn = $('.addCuentaBtn');
                    submitBtn.prop('disabled', true).html(
                        '<i class="ri-loader-4-line spin me-1"></i> Registrando...');

                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('admin.cuentas-bancarias.registrar') }}",
                        type: "POST",
                        data: formData,
                        success: function(res) {
                            if (res.success) {
                                showNotification('success', res.msg);
                                $('#modalRegistrarCuentaBancaria').modal('hide');
                                // Recargar la página para mostrar la nueva cuenta
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                showNotification('error', res.msg);
                                submitBtn.prop('disabled', false).html(
                                    '<i class="ri-save-line me-1"></i> Registrar Cuenta');
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Error al registrar la cuenta bancaria.';
                            if (xhr.responseJSON?.errors) {
                                errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                            }
                            showNotification('error', errorMsg);
                            submitBtn.prop('disabled', false).html(
                                '<i class="ri-save-line me-1"></i> Registrar Cuenta');
                        }
                    });
                });

                // Limpiar formulario al cerrar modal
                $('#modalRegistrarCuentaBancaria').on('hidden.bs.modal', function() {
                    $('#formRegistrarCuentaBancaria')[0].reset();
                    $('#feedback_numero_cuenta_registro').removeClass('text-success text-danger').text('');
                    $('.addCuentaBtn').prop('disabled', true);
                });
            @endif

            // -------------------- CUENTAS BANCARIAS: EDICIÓN --------------------
            @if (Auth::guard('web')->user()->can('cuentas-bancarias.editar'))
                let currentCuentaId = null;

                // Cargar bancos en el modal de edición
                $('#modalEditarCuentaBancaria').on('show.bs.modal', function() {
                    cargarBancosEdicion();
                });

                function cargarBancosEdicion() {
                    $.ajax({
                        url: "{{ route('admin.cuentas-bancarias.obtener-bancos') }}",
                        type: "GET",
                        success: function(response) {
                            if (response.success) {
                                let options = '<option value="">Seleccionar Banco</option>';
                                response.bancos.forEach(banco => {
                                    options +=
                                        `<option value="${banco.id}">${banco.nombre} (${banco.codigo})</option>`;
                                });
                                $('#banco_id_editar').html(options);
                            } else {
                                console.error('Error al cargar bancos:', response.message);
                                showNotification('error', 'Error al cargar la lista de bancos');
                            }
                        },
                        error: function() {
                            showNotification('error', 'Error al cargar la lista de bancos');
                        }
                    });
                }

                $(document).on('click', '.editCuentaBtn', function() {
                    const cuenta = $(this).data('cuenta');
                    currentCuentaId = cuenta.id;

                    $('#cuenta_id_editar').val(cuenta.id);
                    $('#banco_id_editar').val(cuenta.banco_id);
                    $('#numero_cuenta_editar').val(cuenta.numero_cuenta);
                    $('#tipo_cuenta_editar').val(cuenta.tipo_cuenta);
                    $('#moneda_editar').val(cuenta.moneda);
                    $('#descripcion_editar').val(cuenta.descripcion || '');
                    $('#saldo_inicial_editar').val(cuenta.saldo_inicial);
                    $('#activa_editar').prop('checked', cuenta.activa);

                    // Resetear feedback
                    $('#feedback_numero_cuenta_editar').removeClass('text-success text-danger').text('');
                    $('.updateCuentaBtn').prop('disabled', false);
                });

                // Validar número de cuenta en edición
                $('#numero_cuenta_editar').on('input', function() {
                    const numeroCuenta = $(this).val().trim();
                    const bancoId = $('#banco_id_editar').val();
                    const sucursalId = '{{ $sucursal->id }}';
                    const feedback = $('#feedback_numero_cuenta_editar');
                    const submitBtn = $('.updateCuentaBtn');

                    feedback.removeClass('text-success text-danger').text('');

                    if (numeroCuenta.length === 0 || !bancoId) {
                        submitBtn.prop('disabled', true);
                        return;
                    }

                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        $.ajax({
                            url: "{{ route('admin.cuentas-bancarias.verificar') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                banco_id: bancoId,
                                sucursale_id: sucursalId,
                                numero_cuenta: numeroCuenta,
                                id: currentCuentaId
                            },
                            success: function(res) {
                                if (res.exists) {
                                    feedback.addClass('text-danger').text(
                                        '⚠️ Esta cuenta ya está registrada en este banco y sucursal.'
                                    );
                                    submitBtn.prop('disabled', true);
                                } else {
                                    feedback.addClass('text-success').text(
                                        '✅ Número de cuenta disponible.');
                                    validarFormularioEdicion();
                                }
                            },
                            error: function() {
                                feedback.addClass('text-danger').text(
                                    '❌ Error al verificar la cuenta.');
                                submitBtn.prop('disabled', true);
                            }
                        });
                    }, 300);
                });

                function validarFormularioEdicion() {
                    const bancoId = $('#banco_id_editar').val();
                    const numeroCuenta = $('#numero_cuenta_editar').val().trim();
                    const tipoCuenta = $('#tipo_cuenta_editar').val();
                    const moneda = $('#moneda_editar').val();
                    const saldoInicial = $('#saldo_inicial_editar').val();
                    const submitBtn = $('.updateCuentaBtn');

                    let valido = true;

                    if (!bancoId || !numeroCuenta || !tipoCuenta || !moneda || !saldoInicial) {
                        valido = false;
                    }

                    submitBtn.prop('disabled', !valido);
                }

                $('#banco_id_editar, #tipo_cuenta_editar, #moneda_editar, #saldo_inicial_editar')
                    .on('change', validarFormularioEdicion);
                $('#saldo_inicial_editar').on('input', validarFormularioEdicion);

                // Submit edición
                $('#formEditarCuentaBancaria').submit(function(e) {
                    e.preventDefault();
                    const submitBtn = $('.updateCuentaBtn');
                    submitBtn.prop('disabled', true).html(
                        '<i class="ri-loader-4-line spin me-1"></i> Actualizando...');

                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('admin.cuentas-bancarias.modificar') }}",
                        type: "POST",
                        data: formData,
                        success: function(res) {
                            if (res.success) {
                                showNotification('success', res.msg);
                                $('#modalEditarCuentaBancaria').modal('hide');
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                showNotification('error', res.msg);
                                submitBtn.prop('disabled', false).html(
                                    '<i class="ri-save-line me-1"></i> Actualizar Cuenta');
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Error al actualizar la cuenta bancaria.';
                            if (xhr.responseJSON?.errors) {
                                errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                            }
                            showNotification('error', errorMsg);
                            submitBtn.prop('disabled', false).html(
                                '<i class="ri-save-line me-1"></i> Actualizar Cuenta');
                        }
                    });
                });
            @endif

            // -------------------- CUENTAS BANCARIAS: ELIMINACIÓN --------------------
            @if (Auth::guard('web')->user()->can('cuentas-bancarias.eliminar'))
                $(document).on('click', '.deleteCuentaBtn', function() {
                    const id = $(this).data('id');
                    $('#eliminar_id').val(id);
                    $('#warning-pagos').hide();
                    $('.deleteCuentaConfirmBtn').prop('disabled', false);
                });

                $('#formEliminarCuentaBancaria').submit(function(e) {
                    e.preventDefault();
                    const submitBtn = $('.deleteCuentaConfirmBtn');
                    const cuentaId = $('#eliminar_id').val();

                    if (!cuentaId || cuentaId <= 0) {
                        showNotification('error', 'ID de cuenta no válido');
                        return;
                    }

                    submitBtn.prop('disabled', true).html(
                        '<i class="ri-loader-4-line spin me-1"></i> Eliminando...');

                    var data = {
                        id: cuentaId,
                        _token: "{{ csrf_token() }}"
                    };

                    $.ajax({
                        url: "{{ route('admin.cuentas-bancarias.eliminar') }}",
                        type: "POST",
                        data: data,
                        dataType: 'json',
                        success: function(res) {
                            if (res.success) {
                                showNotification('success', res.msg);
                                $('#modalEliminarCuentaBancaria').modal('hide');
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                if (res.msg && res.msg.includes('pagos asociados')) {
                                    $('#warning-pagos').show();
                                    $('#warning-pagos').html(
                                        `<i class="ri-alert-line me-1"></i> <strong>Advertencia:</strong> ${res.msg}`
                                    );
                                } else {
                                    showNotification('error', res.msg ||
                                        'No se pudo eliminar la cuenta bancaria');
                                }
                                submitBtn.prop('disabled', false).html(
                                    '<i class="ri-delete-bin-line me-1"></i> Sí, Eliminar');
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Error al eliminar la cuenta bancaria.';
                            if (xhr.responseJSON?.msg) {
                                errorMsg = xhr.responseJSON.msg;
                            }
                            showNotification('error', errorMsg);
                            submitBtn.prop('disabled', false).html(
                                '<i class="ri-delete-bin-line me-1"></i> Sí, Eliminar');
                        }
                    });
                });
            @endif

            // -------------------- CAJAS: REGISTRO --------------------
            @if (Auth::guard('web')->user()->can('cajas.registrar'))
                // Inicializar Select2 cuando el modal se muestra
                $('#modalRegistrarCaja').on('shown.bs.modal', function() {
                    $('.select2').select2({
                        placeholder: "Seleccionar responsable...",
                        allowClear: true,
                        dropdownParent: $('#modalRegistrarCaja')
                    });
                });

                // Limpiar Select2 cuando se cierra el modal
                $('#modalRegistrarCaja').on('hidden.bs.modal', function() {
                    $('.select2').select2('destroy');
                });

                // Registrar caja
                $('#formRegistrarCaja').submit(function(e) {
                    e.preventDefault();
                    const btn = $('#btnRegistrarCaja');
                    const sucursalId = {{ $sucursal->id }};

                    const responsableId = $('select[name="responsable_id"]').val();
                    if (!responsableId) {
                        showNotification('error', 'Debe seleccionar un responsable para la caja.');
                        return;
                    }

                    btn.prop('disabled', true).html(
                        '<i class="ri-loader-4-line spin me-1"></i> Registrando...');

                    const formData = $(this).serialize();

                    $.ajax({
                        url: "{{ route('admin.sucursales.registrar-caja', ':id') }}".replace(':id',
                            sucursalId),
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                showNotification('success', response.msg);
                                $('#modalRegistrarCaja').modal('hide');
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            } else {
                                showNotification('error', response.msg);
                                btn.prop('disabled', false).html(
                                    '<i class="ri-save-line me-1"></i> Registrar Caja');
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Error al registrar la caja.';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                const errors = xhr.responseJSON.errors;
                                const firstError = Object.values(errors)[0];
                                errorMsg = Array.isArray(firstError) ? firstError[0] :
                                    firstError;
                            } else if (xhr.responseJSON && xhr.responseJSON.msg) {
                                errorMsg = xhr.responseJSON.msg;
                            }
                            showNotification('error', errorMsg);
                            btn.prop('disabled', false).html(
                                '<i class="ri-save-line me-1"></i> Registrar Caja');
                        }
                    });
                });

                // Limpiar errores al cerrar el modal
                $('#modalRegistrarCaja').on('hidden.bs.modal', function() {
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();
                    $('#formRegistrarCaja')[0].reset();
                    $('select[name="responsable_id"]').val('');
                });
            @endif

            // -------------------- FUNCIÓN DE NOTIFICACIÓN --------------------
            function showNotification(type, message) {
                let toastContainer = $('#toast-container');
                if (toastContainer.length === 0) {
                    toastContainer = $(
                        '<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999"></div>'
                    );
                    $('body').append(toastContainer);
                }

                const toast = $(`
                    <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                ${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                `);

                toastContainer.append(toast);
                const bsToast = new bootstrap.Toast(toast[0]);
                bsToast.show();

                toast.on('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }

            // -------------------- VER CAJA --------------------
            $(document).on('click', '.verCajaBtn', function() {
                const cajaId = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.cajas.ver', ':id') }}".replace(':id', cajaId),
                    type: "GET",
                    success: function(response) {
                        $('#cajaDetalleContent').html(response);
                        $('#modalVerCaja').modal('show');
                    },
                    error: function() {
                        showNotification('error', 'Error al cargar los detalles de la caja');
                    }
                });
            });
        });
    </script>
@endpush
