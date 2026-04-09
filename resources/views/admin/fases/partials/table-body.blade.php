@forelse ($fases as $n => $fase)
    <tr>
        <td class="text-center text-muted">{{ $fases->firstItem() + $n }}</td>
        <td class="text-center">
            <span class="fase-numero">{{ $fase->n_fase }}</span>
        </td>
        <td>
            <div class="fase-name-cell">
                <div class="fase-avatar">
                    <i class="ri-list-check-3"></i>
                </div>
                <div class="fase-name-text">
                    <h6>{{ $fase->nombre }}</h6>
                </div>
            </div>
        </td>
        <td>
            <div class="color-swatch">
                <div class="color-box" style="background-color: {{ $fase->color }};"></div>
                <span class="text-muted small">{{ $fase->color }}</span>
            </div>
        </td>
        <td>
            <span class="status-badge active">
                <i class="ri-check-line"></i> Activo
            </span>
        </td>
        <td class="text-center">
            <div class="d-flex align-items-center justify-content-center gap-2">
                @if (Auth::guard('web')->user()->can('fases.editar'))
                    <button type="button" title="Editar Fase" class="action-btn edit editBtn"
                        data-bs-obj='@json($fase)' data-bs-toggle="modal"
                        data-bs-target="#modalModificar">
                        <i class="ri-edit-line"></i>
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('fases.eliminar'))
                    <button type="button" title="Eliminar Fase" class="action-btn delete deleteBtn"
                        data-bs-obj='@json($fase)' data-bs-toggle="modal"
                        data-bs-target="#modalEliminar">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6">
            <div class="empty-state">
                <i class="ri-inbox-line"></i>
                <p>No se tienen registros de Fases</p>
            </div>
        </td>
    </tr>
@endforelse
