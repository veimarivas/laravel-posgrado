@forelse ($fases as $n => $fase)
    <tr>
        <td class="text-center">{{ $fases->firstItem() + $n }}</td>
        <td class="text-center">{{ $fase->n_fase }}</td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="ri-list-check"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0">{{ $fase->nombre }}</h6>
                    <p class="text-muted mb-0 small">ID: {{ $fase->id }}</p>
                </div>
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <div class="me-2"
                    style="width: 20px; height: 20px; background-color: {{ $fase->color }}; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <span class="text-muted">{{ $fase->color }}</span>
            </div>
        </td>
        <td class="text-center">
            <span class="badge bg-success-subtle text-success">
                <i class="ri-check-line align-bottom me-1"></i> Activo
            </span>
        </td>
        <td class="text-center">
            <div class="btn-group" role="group">
                @if (Auth::guard('web')->user()->can('fases.editar'))
                    <button type="button" title="Editar Fase" class="btn btn-warning btn-sm editBtn"
                        data-bs-obj='@json($fase)' data-bs-toggle="modal"
                        data-bs-target="#modalModificar">
                        <i class="ri-edit-line"></i>
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('fases.eliminar'))
                    <button type="button" title="Eliminar Fase" class="btn btn-danger btn-sm deleteBtn"
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
        <td colspan="6" class="text-center py-4">
            <div class="text-muted">
                <i class="ri-inbox-line display-4"></i>
                <p class="mt-2">No se tienen registros de Fases</p>
            </div>
        </td>
    </tr>
@endforelse
