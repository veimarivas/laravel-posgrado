@forelse ($tipos as $n => $tipo)
    <tr>
        <td class="text-center text-muted">{{ $tipos->firstItem() + $n }}</td>
        <td>
            <div class="tipo-name-cell">
                <div class="tipo-avatar">
                    <i class="ri-stack-line"></i>
                </div>
                <div class="tipo-name-text">
                    <h6>{{ $tipo->nombre }}</h6>
                    <p class="text-muted mb-0 small">ID: {{ $tipo->id }}</p>
                </div>
            </div>
        </td>
        <td>
            <span class="status-badge active">
                <i class="ri-check-line"></i> Activo
            </span>
        </td>
        <td class="text-center">
            <div class="d-flex align-items-center justify-content-center gap-2">
                @if (Auth::guard('web')->user()->can('tipos.programas.editar'))
                    <button type="button" title="Editar Tipo" class="action-btn edit editBtn"
                        data-bs-obj='@json($tipo)' data-bs-toggle="modal"
                        data-bs-target="#modalModificar">
                        <i class="ri-edit-line"></i>
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('tipos.programas.eliminar'))
                    <button type="button" title="Eliminar Tipo" class="action-btn delete deleteBtn"
                        data-bs-obj='@json($tipo)' data-bs-toggle="modal"
                        data-bs-target="#modalEliminar">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4">
            <div class="empty-state">
                <i class="ri-inbox-line"></i>
                <p>No se tienen registros de Tipos de Programas</p>
            </div>
        </td>
    </tr>
@endforelse
