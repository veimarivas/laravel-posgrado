<tbody id="cuentasTableBody">
    @forelse ($cuentas as $n => $cuenta)
        <tr>
            <td class="text-center">{{ $cuentas->firstItem() + $n }}</td>
            <td>
                <div class="fw-medium">{{ $cuenta->numero_cuenta }}</div>
                <small class="text-muted">{{ $cuenta->descripcion ?: 'Sin descripción' }}</small>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    @if ($cuenta->banco->logo)
                        <img src="{{ $cuenta->banco->logo }}" alt="Logo" class="rounded me-2"
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
                @if ($cuenta->sucursal)
                    <div class="fw-medium">{{ $cuenta->sucursal->nombre }}</div>
                    <small class="text-muted">{{ $cuenta->sucursal->direccion }}</small>
                @else
                    <span class="text-muted">Sin sucursal</span>
                @endif
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
                <div class="fw-bold {{ $cuenta->saldo_actual >= 0 ? 'text-success' : 'text-danger' }}">
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
                    <!-- Botón para Ver -->
                    @if (Auth::guard('web')->user()->can('cuentas-bancarias.ver'))
                        <a href="{{ route('admin.cuentas-bancarias.ver', ['id' => $cuenta->id]) }}"
                            class="btn btn-sm btn-info" title="Ver Detalles" data-bs-toggle="tooltip">
                            <i class="ri-eye-line"></i>
                        </a>
                    @endif

                    <!-- Botones existentes de Editar y Eliminar -->
                    @if (Auth::guard('web')->user()->can('cuentas-bancarias.editar'))
                        <button type="button" class="btn btn-sm btn-warning editBtn" title="Editar Cuenta"
                            data-bs-toggle="modal" data-bs-target="#modalModificar"
                            data-bs-obj='@json($cuenta)'>
                            <i class="ri-edit-line"></i>
                        </button>
                    @endif

                    @if (Auth::guard('web')->user()->can('cuentas-bancarias.eliminar'))
                        <button type="button" class="btn btn-sm btn-danger deleteBtn" title="Eliminar Cuenta"
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
            <td colspan="9" class="text-center py-4">
                <div class="text-muted">
                    <i class="ri-bank-card-line display-4"></i>
                    <p class="mt-2">No se tienen registros de Cuentas Bancarias</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
