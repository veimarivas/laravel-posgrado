@forelse ($grados as $n => $grado)
    <tr data-nombre="{{ strtolower($grado->nombre) }}">
        <td class="text-center">{{ $grados->firstItem() + $n }}</td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="ri-graduation-cap-line"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0">{{ $grado->nombre }}</h6>
                    <p class="text-muted mb-0 small">ID: {{ $grado->id }}</p>
                </div>
            </div>
        </td>
        <td class="text-center">
            <span class="badge bg-success-subtle text-success">
                <i class="ri-check-line align-bottom me-1"></i> Activo
            </span>
        </td>
        <td class="text-center">
            <div class="btn-group" role="group">
                @if (Auth::guard('web')->user()->can('grados.academicos.editar'))
                    <button type="button" title="Editar Grado Académico" class="btn btn-warning btn-sm editBtn"
                        data-bs-obj='@json($grado)' data-bs-toggle="modal"
                        data-bs-target="#modalModificar">
                        <i class="ri-edit-line"></i>
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('grados.academicos.eliminar'))
                    <button type="button" title="Eliminar Grado Académico" class="btn btn-danger btn-sm deleteBtn"
                        data-bs-obj='@json($grado)' data-bs-toggle="modal"
                        data-bs-target="#modalEliminar">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center py-4">
            <div class="text-muted">
                <i class="ri-inbox-line display-4"></i>
                <p class="mt-2">No se tienen registros de Grados Académicos</p>
            </div>
        </td>
    </tr>
@endforelse
