<table class="table table-hover align-middle mb-0">
    <thead class="table-light">
        <tr>
            <th width="10%">ID</th>
            <th width="15%">Fecha</th>
            <th width="20%">Estudiante</th>
            <th width="20%">Programa</th>
            <th width="10%">Cuotas</th>
            <th width="10%">Estado</th>
            <th width="15%">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($comprobantes as $comp)
            @php
                $estudiante = $comp->inscripcion->estudiante->persona ?? null;
                $programa = $comp->inscripcion->ofertaAcademica->programa ?? null;
                $colorEstado =
                    [
                        'pendiente' => 'warning',
                        'verificado' => 'success',
                        'rechazado' => 'danger',
                    ][$comp->estado] ?? 'secondary';
            @endphp
            <tr>
                <td>#{{ $comp->id }}</td>
                <td>{{ $comp->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @if ($estudiante)
                        <span class="fw-medium">{{ $estudiante->nombres }} {{ $estudiante->apellido_paterno }}</span>
                        <br>
                        <small class="text-muted">{{ $estudiante->carnet }}</small>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>{{ $programa->nombre ?? 'N/A' }}</td>
                <td>{{ $comp->cuotas->count() }} cuota(s)</td>
                <td>
                    <span class="badge bg-{{ $colorEstado }}">{{ ucfirst($comp->estado) }}</span>
                </td>
                <td>
                    <a href="{{ Storage::url($comp->archivo) }}" target="_blank" class="btn btn-sm btn-info"
                        title="Ver comprobante">
                        <i class="ri-eye-line"></i>
                    </a>
                    @if ($comp->estado == 'pendiente')
                        <button class="btn btn-sm btn-success btn-verificar" data-id="{{ $comp->id }}"
                            title="Verificar y registrar pago">
                            <i class="ri-check-line"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-rechazar" data-id="{{ $comp->id }}"
                            title="Rechazar">
                            <i class="ri-close-line"></i>
                        </button>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-5">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-light text-secondary rounded-circle">
                            <i class="ri-file-text-line fs-2"></i>
                        </div>
                    </div>
                    <h5 class="mb-2">No se encontraron comprobantes</h5>
                    <p class="text-muted mb-0">Prueba con otros filtros</p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if ($comprobantes instanceof \Illuminate\Pagination\LengthAwarePaginator && $comprobantes->hasPages())
    <div class="mt-3" id="pagination-links">
        {{ $comprobantes->links('pagination::bootstrap-5') }}
    </div>
@endif
