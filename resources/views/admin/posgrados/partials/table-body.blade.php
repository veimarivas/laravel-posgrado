<tbody>
    @forelse($posgrados as $index => $p)
        <tr>
            <td class="text-center">{{ $posgrados->firstItem() + $index }}</td>
            <td>{{ $p->nombre }}</td>
            <td class="text-center">{{ $p->convenio?->nombre ?? '—' }}</td>
            <td class="text-center">{{ $p->area?->nombre ?? '—' }}</td>
            <td class="text-center">{{ $p->tipo?->nombre ?? '—' }}</td>
            <td class="text-center">
                {{ $p->duracion_numero }}
                {{ $p->duracion_unidad }}
            </td>
            <td class="text-center">{{ $p->carga_horaria }} horas</td>
            <td class="text-center">{{ $p->creditaje }}</td>
            <td class="text-center">
                <span class="badge bg-{{ $p->estado == 'activo' ? 'success' : 'danger' }}">
                    {{ ucfirst($p->estado) }}
                </span>
            </td>
            <td class="text-center">
                @if (Auth::guard('web')->user()->can('posgrados.ver'))
                    <a href="{{ route('admin.posgrados.ver', $p->id) }}" title="Ver Ofertas Académicas"
                        class="btn btn-primary btn-icon">
                        <i data-feather="eye"></i>
                    </a>
                @endif
                @if ($p->estado == 'activo')
                    @if (Auth::guard('web')->user()->can('ofertas.academicas.registrar'))
                        {{-- En la sección de acciones de la tabla --}}
                        <button type="button" class="btn btn-info btn-icon" data-posgrado-id="{{ $p->id }}"
                            data-posgrado-nombre="{{ $p->nombre }}" data-bs-toggle="modal"
                            data-bs-target="#modalRegistrarOferta" title="Registrar Oferta Académica">
                            <i data-feather="plus-circle"></i>
                        </button>
                    @endif
                @endif
                @if (Auth::guard('web')->user()->can('posgrados.editar'))
                    <button type="button" title="Editar Datos Convenio" class="btn btn-warning btn-icon editBtn"
                        data-bs-obj='@json($p)' data-bs-toggle="modal" data-bs-target=".modificar">
                        <i data-feather="edit"></i>
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('posgrados.eliminar'))
                    <button type="button" title="Eliminar Convenio" class="btn btn-danger btn-icon deleteBtn"
                        data-bs-obj='@json($p)' data-bs-toggle="modal" data-bs-target=".eliminar">
                        <i data-feather="delete"></i>
                    </button>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="10" class="text-center">No hay posgrados registrados.</td>
        </tr>
    @endforelse
</tbody>
