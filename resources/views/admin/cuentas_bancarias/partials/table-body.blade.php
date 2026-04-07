@forelse ($cuentas as $n => $cuenta)
    <tr>
        <td class="text-center fw-medium">{{ $cuentas->firstItem() + $n }}</td>
        <td>
            <div class="cuenta-name-cell">
                <div class="cuenta-avatar">
                    <i class="ri-bank-card-line"></i>
                </div>
                <div class="cuenta-name-text">
                    <h6>{{ $cuenta->numero_cuenta }}</h6>
                    <small>{{ $cuenta->descripcion ?: 'Sin descripción' }}</small>
                </div>
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center">
                @if ($cuenta->banco->logo)
                    <img src="{{ $cuenta->banco->logo }}" alt="Logo" class="rounded me-2"
                        style="width: 24px; height: 24px; object-fit: contain;">
                @else
                    <div class="rounded me-2 d-flex align-items-center justify-content-center text-white banco-color-box"
                        style="width: 24px; height: 24px; font-size: 12px;">
                        <i class="ri-bank-line"></i>
                    </div>
                @endif
                <div>
                    <div class="fw-medium">{{ $cuenta->banco->nombre }}</div>
                    <small class="text-muted">{{ $cuenta->banco->codigo }}</small>
                </div>
            </div>
        </td>
        <td>
            @if ($cuenta->sucursal)
                <div class="fw-medium">{{ $cuenta->sucursal->nombre }}</div>
                <small class="text-muted">{{ Str::limit($cuenta->sucursal->direccion, 25) }}</small>
            @else
                <span class="text-muted">Sin sucursal</span>
            @endif
        </td>
        <td>
            <span class="status-badge {{ $cuenta->tipo_cuenta }}">
                @switch($cuenta->tipo_cuenta)
                    @case('ahorro')
                        <i class="ri-savings-line me-1"></i>Ahorro
                    @break
                    @case('corriente')
                        <i class="ri-briefcase-line me-1"></i>Corriente
                    @break
                    @case('moneda_extranjera')
                        <i class="ri-global-line me-1"></i>Extranjera
                    @break
                    @default
                        {{ ucfirst(str_replace('_', ' ', $cuenta->tipo_cuenta)) }}
                @endswitch
            </span>
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
            <span class="currency-badge">{{ $symbol }}</span>
        </td>
        <td>
            <div class="fw-bold {{ $cuenta->saldo_actual >= 0 ? 'text-success' : 'text-danger' }}">
                {{ $symbol }} {{ number_format($cuenta->saldo_actual, 2) }}
            </div>
            <small class="text-muted">Inicial: {{ $symbol }} {{ number_format($cuenta->saldo_inicial, 2) }}</small>
        </td>
        <td>
            @if ($cuenta->activa)
                <span class="status-badge active">
                    <i class="ri-checkbox-circle-fill me-1"></i>Activa
                </span>
            @else
                <span class="status-badge inactive">
                    <i class="ri-close-circle-fill me-1"></i>Inactiva
                </span>
            @endif
        </td>
        <td class="text-center">
            <div class="d-flex justify-content-center gap-1">
                @if (Auth::guard('web')->user()->can('cuentas-bancarias.ver'))
                    <a href="{{ route('admin.cuentas-bancarias.ver', ['id' => $cuenta->id]) }}"
                        class="btn btn-sm" style="background: var(--plane-primary-light); color: var(--plane-primary);"
                        title="Ver Detalles" data-bs-toggle="tooltip">
                        <i class="ri-eye-line"></i>
                    </a>
                @endif

                @if (Auth::guard('web')->user()->can('cuentas-bancarias.editar'))
                    <button type="button" class="action-btn edit" title="Editar Cuenta"
                        data-bs-toggle="modal" data-bs-target="#modalModificar"
                        data-bs-obj='@json($cuenta)'>
                        <i class="ri-edit-line"></i>
                    </button>
                @endif

                @if (Auth::guard('web')->user()->can('cuentas-bancarias.eliminar'))
                    <button type="button" class="action-btn delete" title="Eliminar Cuenta"
                        data-bs-toggle="modal" data-bs-target="#modalEliminar"
                        data-bs-obj='@json($cuenta)'>
                        <i class="ri-delete-bin-line"></i>
                    </button>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="text-center py-5">
            <div class="empty-state">
                <i class="ri-bank-card-line"></i>
                <p class="mt-2">No se tienen registros de Cuentas Bancarias</p>
            </div>
        </td>
    </tr>
@endforelse
