<tbody>
    @forelse($posgrados as $index => $p)
        <tr>
            <td class="text-center numero-fila">{{ $posgrados->firstItem() + $index }}</td>
            <td>
                <div class="posgrado-nombre" title="{{ $p->nombre }}">
                    {{ $p->nombre }}
                </div>
            </td>
            <td class="text-center convenio-cell">
                <span class="d-inline-block"
                    style="max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                    title="{{ $p->convenio?->nombre ?? 'Sin convenio' }}">
                    {{ $p->convenio?->nombre ?? '—' }}
                </span>
            </td>
            <td class="text-center area-cell">
                <span class="d-inline-block"
                    style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                    title="{{ $p->area?->nombre ?? 'Sin área' }}">
                    {{ $p->area?->nombre ?? '—' }}
                </span>
            </td>
            <td class="text-center tipo-cell">
                <span class="d-inline-block"
                    style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                    title="{{ $p->tipo?->nombre ?? 'Sin tipo' }}">
                    {{ $p->tipo?->nombre ?? '—' }}
                </span>
            </td>
            <td class="text-center duracion-cell">
                {{ $p->duracion_numero }}
                {{ strtolower($p->duracion_unidad) }}
            </td>
            <td class="text-center">
                <span class="badge bg-info-subtle text-info">
                    {{ $p->carga_horaria }}h
                </span>
            </td>
            <td class="text-center">
                <span class="badge bg-primary-subtle text-primary">
                    {{ $p->creditaje }}
                </span>
            </td>
            <td class="text-center">
                <span class="badge bg-{{ $p->estado == 'activo' ? 'success' : 'danger' }}">
                    {{ $p->estado == 'activo' ? 'Activo' : 'Inactivo' }}
                </span>
            </td>
            <td class="text-center">
                <div class="d-flex flex-wrap justify-content-center gap-1">
                    @if (Auth::guard('web')->user()->can('posgrados.ver'))
                        <a href="{{ route('admin.posgrados.ver', $p->id) }}" title="Ver Ofertas Académicas"
                            class="btn btn-primary btn-icon">
                            <i data-feather="eye"></i>
                        </a>
                    @endif

                    @if ($p->estado == 'activo')
                        @if (Auth::guard('web')->user()->can('ofertas.academicas.registrar'))
                            <button type="button" class="btn btn-info btn-icon" data-posgrado-id="{{ $p->id }}"
                                data-posgrado-nombre="{{ $p->nombre }}" data-bs-toggle="modal"
                                data-bs-target="#modalRegistrarOferta" title="Registrar Oferta Académica">
                                <i data-feather="plus-circle"></i>
                            </button>
                        @endif
                    @endif

                    @if (Auth::guard('web')->user()->can('posgrados.editar'))
                        <button type="button" title="Editar Posgrado" class="btn btn-warning btn-icon editBtn"
                            data-bs-obj='@json($p)' data-bs-toggle="modal"
                            data-bs-target=".modificar">
                            <i data-feather="edit"></i>
                        </button>
                    @endif

                    @if (Auth::guard('web')->user()->can('posgrados.eliminar'))
                        <button type="button" title="Eliminar Posgrado" class="btn btn-danger btn-icon deleteBtn"
                            data-bs-obj='@json($p)' data-bs-toggle="modal"
                            data-bs-target=".eliminar">
                            <i data-feather="trash-2"></i>
                        </button>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="10" class="text-center py-4">
                <div class="text-muted">
                    <i class="ri-inbox-line fs-48"></i>
                    <p class="mt-2 mb-0">No hay posgrados registrados</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
