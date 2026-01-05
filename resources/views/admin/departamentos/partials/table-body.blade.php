<tbody id="departamentosTableBody">
    @forelse ($departamentos as $n => $departamento)
        <tr data-nombre="{{ strtolower($departamento->nombre) }}">
            <td class="text-center">{{ $departamentos->firstItem() + $n }}</td>
            <td>{{ $departamento->nombre }}</td>
            <td>
                <div class="ciudades-container">
                    @if ($departamento->ciudades->count() > 0)
                        @foreach ($departamento->ciudades as $ciudad)
                            <div class="d-flex align-items-center p-2 mb-1 border rounded"
                                style="background-color: rgba(45, 206, 137, 0.15); border-left: 3px solid #2dce89;">
                                <div class="flex-grow-1">
                                    <span class="fw-medium" style="color: #2dce89;">{{ $ciudad->nombre }}</span>
                                </div>
                                <div class="btn-group ms-2">
                                    @if (Auth::guard('web')->user()->can('ciudades.editar'))
                                        <button type="button" class="btn btn-sm btn-soft-primary editCiudadBtn"
                                            title="Editar Ciudad" data-bs-toggle="modal"
                                            data-bs-target="#modalEditarCiudad"
                                            data-bs-obj='@json($ciudad)'>
                                            <i class="ri-edit-line"></i>
                                        </button>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('ciudades.eliminar'))
                                        <button type="button" class="btn btn-sm btn-soft-danger deleteCiudadBtn"
                                            title="Eliminar Ciudad" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminarCiudad"
                                            data-bs-obj='@json($ciudad)'>
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="ri-map-pin-line fs-3 text-muted"></i>
                            <p class="text-muted mt-1 mb-0">Sin ciudades registradas</p>
                        </div>
                    @endif
                </div>
                <div class="mt-2 text-end">
                    @if (Auth::guard('web')->user()->can('ciudades.registrar'))
                        <button type="button" class="btn btn-sm btn-soft-info addCiudadBtn" title="Agregar Ciudad"
                            data-departamento-id="{{ $departamento->id }}" data-bs-toggle="modal"
                            data-bs-target="#modalAgregarCiudad">
                            <i class="ri-add-circle-line me-1"></i>Agregar Ciudad
                        </button>
                    @endif
                </div>
            </td>
            <td class="text-center">
                <div class="d-flex justify-content-center gap-1">
                    @if (Auth::guard('web')->user()->can('departamentos.editar'))
                        <button type="button" class="btn btn-sm btn-warning editBtn" title="Editar Departamento"
                            data-bs-toggle="modal" data-bs-target="#modalModificar"
                            data-bs-obj='@json($departamento)'>
                            <i class="ri-edit-line"></i>
                        </button>
                    @endif
                    @if (Auth::guard('web')->user()->can('departamentos.eliminar'))
                        <button type="button" class="btn btn-sm btn-danger deleteBtn" title="Eliminar Departamento"
                            data-bs-toggle="modal" data-bs-target="#modalEliminar"
                            data-bs-obj='@json($departamento)'>
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
                    <p class="mt-2">No se tienen registros de Departamentos</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
