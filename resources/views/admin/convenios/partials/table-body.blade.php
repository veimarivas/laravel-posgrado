@forelse ($convenios as $n => $convenio)
    <tr data-nombre="{{ strtolower($convenio->nombre) }}" data-sigla="{{ strtolower($convenio->sigla ?? '') }}">
        <td class="text-center">{{ $convenios->firstItem() + $n }}</td>
        <td>
            <div class="d-flex justify-content-center">
                @if ($convenio->imagen)
                    <img src="{{ asset($convenio->imagen) }}" class="w-100 convenio-img" alt="Imagen convenio">
                @else
                    <div class="avatar-sm">
                        <div class="avatar-title bg-secondary bg-opacity-10 text-secondary rounded-circle">
                            <i class="ri-image-line"></i>
                        </div>
                    </div>
                @endif
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="mb-0">{{ $convenio->nombre }}</h6>
                </div>
            </div>
        </td>
        <td>
            @if ($convenio->sigla)
                <span class="badge bg-success">{{ $convenio->sigla }}</span>
            @else
                <span class="text-muted small">Sin sigla</span>
            @endif
        </td>
        <td class="text-center">
            <div class="btn-group" role="group">
                @if (Auth::guard('web')->user()->can('convenios.editar'))
                    <button type="button" title="Editar Convenio" class="btn btn-warning btn-sm editBtn"
                        data-bs-obj='@json($convenio)' data-bs-toggle="modal"
                        data-bs-target="#modalModificar">
                        <i class="ri-edit-line"></i>
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('convenios.eliminar'))
                    <button type="button" title="Eliminar Convenio" class="btn btn-danger btn-sm deleteBtn"
                        data-bs-obj='@json($convenio)' data-bs-toggle="modal"
                        data-bs-target="#modalEliminar">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-4">
            <div class="text-muted">
                <i class="ri-inbox-line display-4"></i>
                <p class="mt-2">No se tienen registros de Convenios</p>
            </div>
        </td>
    </tr>
@endforelse
