@forelse ($conceptos as $n => $concepto)
    <tr data-nombre="{{ strtolower($concepto->nombre) }}">
        <td class="text-center">{{ $conceptos->firstItem() + $n }}</td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="ri-money-dollar-circle-line"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0">{{ $concepto->nombre }}</h6>
                    <p class="text-muted mb-0 small">ID: {{ $concepto->id }}</p>
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
                @if (Auth::guard('web')->user()->can('conceptos.pagos.editar'))
                    <button type="button" title="Editar Concepto de pago" class="btn btn-warning btn-sm editBtn"
                        data-bs-obj='@json($concepto)' data-bs-toggle="modal"
                        data-bs-target="#modalModificar">
                        <i class="ri-edit-line"></i>
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('conceptos.pagos.eliminar'))
                    <button type="button" title="Eliminar Concepto de pago" class="btn btn-danger btn-sm deleteBtn"
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
        <td colspan="4" class="text-center py-4">
            <div class="text-muted">
                <i class="ri-inbox-line display-4"></i>
                <p class="mt-2">No se tienen registros de Conceptos de Pago</p>
            </div>
        </td>
    </tr>
@endforelse
