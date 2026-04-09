@forelse ($areas as $n => $area)
    <tr data-nombre="{{ strtolower($area->nombre) }}">
        <td class="text-center text-muted">{{ $areas->firstItem() + $n }}</td>
        <td>
            <div class="area-name-cell">
                <div class="area-avatar">
                    <i class="ri-folder-2-line"></i>
                </div>
                <div class="area-name-text">
                    <h6>{{ $area->nombre }}</h6>
                    <p class="text-muted mb-0 small">ID: {{ $area->id }}</p>
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
                @if (Auth::guard('web')->user()->can('areas.editar'))
                    <button type="button" title="Editar Área" class="action-btn edit editBtn"
                        data-bs-obj='@json($area)' data-bs-toggle="modal"
                        data-bs-target="#modalModificar">
                        <i class="ri-edit-line"></i>
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('areas.eliminar'))
                    <button type="button" title="Eliminar Área" class="action-btn delete deleteBtn"
                        data-bs-obj='@json($area)' data-bs-toggle="modal"
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
                <p>No se tienen registros de Áreas</p>
            </div>
        </td>
    </tr>
@endforelse
