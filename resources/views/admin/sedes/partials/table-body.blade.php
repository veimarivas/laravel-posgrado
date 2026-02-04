<tbody id="sedesTableBody">
    @forelse ($sedes as $n => $sede)
        <tr data-nombre="{{ strtolower($sede->nombre) }}">
            <td class="text-center">{{ $sedes->firstItem() + $n }}</td>
            <td>{{ $sede->nombre }}</td>
            <td>
                <div class="sucursales-container">
                    @if ($sede->sucursales->count() > 0)
                        @foreach ($sede->sucursales as $sucursal)
                            @php
                                $color = preg_match('/^#[0-9A-Fa-f]{6}$/', $sucursal->color)
                                    ? $sucursal->color
                                    : '#6c757d';
                            @endphp
                            <div class="d-flex align-items-center p-2 mb-1 border rounded"
                                style="background-color: {{ $color }}15; border-left: 3px solid {{ $color }};">
                                <div class="flex-grow-1">
                                    <span class="fw-medium"
                                        style="color: {{ $color }};">{{ $sucursal->nombre }}</span>
                                    <div class="text-muted small mt-1">{{ $sucursal->direccion }}</div>
                                </div>
                                <div class="btn-group ms-2">
                                    @if (Auth::guard('web')->user()->can('sucursales.ver'))
                                        <a href="{{ route('admin.sucursales.ver', $sucursal->id) }}"
                                            class="btn btn-sm btn-soft-info" title="Ver Sucursal"
                                            data-bs-toggle="tooltip">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('sucursales.editar'))
                                        <button type="button" class="btn btn-sm btn-soft-primary editSucursalBtn"
                                            title="Editar Sucursal" data-bs-toggle="modal"
                                            data-bs-target="#modalEditarSucursal"
                                            data-bs-obj='@json($sucursal)'>
                                            <i class="ri-edit-line"></i>
                                        </button>
                                    @endif
                                    @if (Auth::guard('web')->user()->can('sucursales.eliminar'))
                                        <button type="button" class="btn btn-sm btn-soft-danger deleteSucursalBtn"
                                            title="Eliminar Sucursal" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminarSucursal"
                                            data-bs-obj='@json($sucursal)'>
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="ri-store-2-line fs-3 text-muted"></i>
                            <p class="text-muted mt-1 mb-0">Sin sucursales registradas</p>
                        </div>
                    @endif
                </div>
                <div class="mt-2 text-end">
                    @if (Auth::guard('web')->user()->can('sucursales.registrar'))
                        <button type="button" class="btn btn-sm btn-soft-info addSucursalBtn" title="Agregar Sucursal"
                            data-sede-id="{{ $sede->id }}" data-bs-toggle="modal"
                            data-bs-target="#modalAgregarSucursal">
                            <i class="ri-add-circle-line me-1"></i>Agregar Sucursal
                        </button>
                    @endif
                </div>
            </td>
            <td class="text-center">
                <div class="d-flex justify-content-center gap-1">
                    @if (Auth::guard('web')->user()->can('sedes.ver'))
                        <a href="{{ route('admin.sedes.ver', $sede->id) }}" title="Ver Sede"
                            class="btn btn-sm btn-primary" data-bs-toggle="tooltip">
                            <i class="ri-eye-line"></i>
                        </a>
                    @endif
                    @if (Auth::guard('web')->user()->can('sedes.editar'))
                        <button type="button" class="btn btn-sm btn-warning editBtn" title="Editar Sede"
                            data-bs-toggle="modal" data-bs-target="#modalModificar"
                            data-bs-obj='@json($sede)'>
                            <i class="ri-edit-line"></i>
                        </button>
                    @endif
                    @if (Auth::guard('web')->user()->can('sedes.eliminar'))
                        <button type="button" class="btn btn-sm btn-danger deleteBtn" title="Eliminar Sede"
                            data-bs-toggle="modal" data-bs-target="#modalEliminar"
                            data-bs-obj='@json($sede)'>
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
                    <p class="mt-2">No se tienen registros de Sedes</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
