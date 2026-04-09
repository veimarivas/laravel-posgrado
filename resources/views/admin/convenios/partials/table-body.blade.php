@forelse ($convenios as $n => $convenio)
    <tr>
        <td class="text-center text-muted">{{ $convenios->firstItem() + $n }}</td>
        <td>
            <div class="convenio-avatar">
                @if ($convenio->imagen)
                    <img src="{{ asset($convenio->imagen) }}" alt="{{ $convenio->nombre }}">
                @else
                    <i class="ri-handshake-line"></i>
                @endif
            </div>
        </td>
        <td>
            <div class="convenio-name-text">
                <h6>{{ $convenio->nombre }}</h6>
            </div>
        </td>
        <td>
            @if ($convenio->sigla)
                <span class="badge-sigla">{{ $convenio->sigla }}</span>
            @else
                <span class="text-muted small">—</span>
            @endif
        </td>
        <td class="text-center">
            <div class="d-flex align-items-center justify-content-center gap-2">
                @if (Auth::guard('web')->user()->can('convenios.editar'))
                    <button type="button" title="Editar Convenio" class="action-btn edit editBtn"
                        data-bs-obj='@json($convenio)' data-bs-toggle="modal"
                        data-bs-target="#modalModificar">
                        <i class="ri-edit-line"></i>
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('convenios.eliminar'))
                    <button type="button" title="Eliminar Convenio" class="action-btn delete deleteBtn"
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
        <td colspan="5">
            <div class="empty-state">
                <i class="ri-inbox-line"></i>
                <p>No se tienen registros de Convenios</p>
            </div>
        </td>
    </tr>
@endforelse
