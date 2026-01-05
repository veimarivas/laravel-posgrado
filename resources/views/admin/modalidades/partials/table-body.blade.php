<tbody id="nacionalidadesTableBody">
    @forelse ($modalidades as $n => $modalidad)
        <tr data-nombre="{{ strtolower($modalidad->nombre) }}">
            <td class="text-center">{{ $modalidades->firstItem() + $n }}</td>
            <td>{{ $modalidad->nombre }}</td>
            <td class="text-center">
                <a href="{{ route('admin.modalidades.ver', $modalidad->id) }}" title="Ver Área"
                    class="btn btn-primary btn-icon editBtn">
                    <i data-feather="eye"></i>
                </a>
                <button type="button" title="Editar Datosde la modalidad" class="btn btn-warning btn-icon editBtn"
                    data-bs-obj='@json($modalidad)' data-bs-toggle="modal" data-bs-target=".modificar">
                    <i data-feather="edit"></i>
                </button>
                <button type="button" title="Eliminar Modalidad" class="btn btn-danger btn-icon deleteBtn"
                    data-bs-obj='@json($modalidad)' data-bs-toggle="modal" data-bs-target=".eliminar">
                    <i data-feather="delete"></i>
                </button>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="text-center">No se tienen registros de modalidades</td>
        </tr>
    @endforelse
</tbody>
