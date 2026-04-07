@forelse ($conceptos as $n => $concepto)
    <tr data-nombre="{{ strtolower($concepto->nombre) }}">
        <td class="text-center text-muted">{{ $conceptos->firstItem() + $n }}</td>
        <td>
            <div class="concepto-name-cell">
                <div class="concepto-avatar">
                    <i class="ri-file-list-3-line"></i>
                </div>
                <div class="concepto-name-text">
                    <h6>{{ $concepto->nombre }}</h6>
                </div>
            </div>
        </td>
        <td>
            <span class="status-badge active">
                <i class="ri-check-line"></i> Activo
            </span>
        </td>
        <td class="text-center">
            <div class="d-flex align-items-center justify-content-center gap-6">
                @if (Auth::guard('web')->user()->can('conceptos.pagos.editar'))
                    <button type="button" title="Editar Concepto" class="action-btn edit editBtn"
                        data-bs-obj='@json($concepto)' data-bs-toggle="modal"
                        data-bs-target="#modalModificar">
                        <i class="ri-edit-line"></i>
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('conceptos.pagos.eliminar'))
                    <button type="button" title="Eliminar Concepto" class="action-btn delete deleteBtn"
                        data-bs-obj='@json($concepto)' data-bs-toggle="modal"
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
                <p>No se tienen registros de Conceptos de Pago</p>
            </div>
        </td>
    </tr>
@endforelse
