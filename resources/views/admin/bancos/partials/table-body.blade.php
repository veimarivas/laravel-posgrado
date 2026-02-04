<tbody id="bancosTableBody">
    @forelse ($bancos as $n => $banco)
        <tr data-nombre="{{ strtolower($banco->nombre) }}">
            <td class="text-center">{{ $bancos->firstItem() + $n }}</td>
            <td>
                @if ($banco->logo)
                    <img src="{{ $banco->logo }}" alt="Logo" class="rounded"
                        style="width: 40px; height: 40px; object-fit: contain;">
                @else
                    <div class="rounded d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px; background-color: {{ $banco->color ?? '#0d6efd' }};">
                        <span class="text-white fw-bold">{{ substr($banco->nombre, 0, 1) }}</span>
                    </div>
                @endif
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-2"
                        style="width: 12px; height: 12px; background-color: {{ $banco->color ?? '#0d6efd' }}; border-radius: 2px;">
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $banco->nombre }}</h6>
                        <small class="text-muted">Registrado: {{ $banco->created_at->format('d/m/Y') }}</small>
                    </div>
                </div>
            </td>
            <td><span class="badge bg-secondary">{{ $banco->codigo }}</span></td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="rounded me-2"
                        style="width: 20px; height: 20px; background-color: {{ $banco->color ?? '#0d6efd' }};"></div>
                    <span class="small">{{ $banco->color ?? 'Sin color' }}</span>
                </div>
            </td>
            <td class="text-center">
                <span class="badge bg-info">{{ $banco->cuentas_count ?? 0 }}</span>
            </td>
            <td class="text-center">
                <div class="d-flex justify-content-center gap-1">
                    @if (Auth::guard('web')->user()->can('bancos.ver'))
                        <a href="{{ route('admin.bancos.detalle', $banco->id) }}" class="btn btn-sm btn-info"
                            title="Ver Detalles">
                            <i class="ri-eye-line"></i>
                        </a>
                    @endif
                    @if (Auth::guard('web')->user()->can('bancos.editar'))
                        <button type="button" class="btn btn-sm btn-warning editBtn" title="Editar Banco"
                            data-bs-toggle="modal" data-bs-target="#modalModificar"
                            data-bs-obj='@json($banco)'>
                            <i class="ri-edit-line"></i>
                        </button>
                    @endif
                    @if (Auth::guard('web')->user()->can('bancos.eliminar'))
                        <button type="button" class="btn btn-sm btn-danger deleteBtn" title="Eliminar Banco"
                            data-bs-toggle="modal" data-bs-target="#modalEliminar"
                            data-bs-obj='@json($banco)'>
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center py-4">
                <div class="text-muted">
                    <i class="ri-bank-line display-4"></i>
                    <p class="mt-2">No se tienen registros de Bancos</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
