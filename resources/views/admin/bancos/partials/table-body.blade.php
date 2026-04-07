@forelse ($bancos as $n => $banco)
    @php
        $bancoData = [
            'id' => $banco->id,
            'nombre' => $banco->nombre,
            'codigo' => $banco->codigo,
            'color' => $banco->color,
            'logo' => $banco->logo,
            'cuentas_count' => $banco->cuentas_count ?? 0,
            'created_at' => $banco->created_at
        ];
    @endphp
    <tr data-nombre="{{ strtolower($banco->nombre) }}">
        <td class="text-center">{{ $bancos->firstItem() + $n }}</td>
        <td>
            @if ($banco->logo)
                <div class="banco-logo" style="background: {{ $banco->color }}">
                    <img src="{{ $banco->logo }}" alt="Logo">
                </div>
            @else
                <div class="banco-logo" style="background-color: {{ $banco->color }}">
                    <span class="text-white fw-bold">{{ substr($banco->nombre, 0, 1) }}</span>
                </div>
            @endif
        </td>
        <td>
            <div class="banco-name-text">
                <h6>{{ $banco->nombre }}</h6>
                <small>Registrado: {{ $banco->created_at->format('d/m/Y') }}</small>
            </div>
        </td>
        <td><span class="badge-codigo">{{ $banco->codigo }}</span></td>
        <td>
            <div class="color-indicator">
                <div class="color-swatch" style="background-color: {{ $banco->color }}"></div>
                <span class="small text-muted">{{ $banco->color ?: 'Sin color' }}</span>
            </div>
        </td>
        <td class="text-center">
            <span class="badge-cuentas">{{ $banco->cuentas_count ?? 0 }}</span>
        </td>
        <td class="text-center">
            <div class="d-flex justify-content-center gap-1">
                @if (Auth::guard('web')->user()->can('bancos.ver'))
                    <a href="{{ route('admin.bancos.detalle', $banco->id) }}" class="action-btn view"
                        title="Ver Detalles">
                        <i class="ri-eye-line"></i>
                    </a>
                @endif
                @if (Auth::guard('web')->user()->can('bancos.editar'))
                    <button type="button" class="action-btn edit editBtn" title="Editar Banco"
                        data-bs-toggle="modal" data-bs-target="#modalModificar"
                        data-bs-obj='@json($bancoData)'>
                        <i class="ri-edit-line"></i>
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('bancos.eliminar'))
                    <button type="button" class="action-btn delete deleteBtn" title="Eliminar Banco"
                        data-bs-toggle="modal" data-bs-target="#modalEliminar"
                        data-bs-obj='@json($bancoData)'>
                        <i class="ri-delete-bin-line"></i>
                    </button>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-4">
            <div class="empty-state">
                <i class="ri-bank-line display-4"></i>
                <p>No se tienen registros de Bancos</p>
            </div>
        </td>
    </tr>
@endforelse
