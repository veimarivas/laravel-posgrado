<table class="table table-hover align-middle mb-0">
    <thead class="table-light">
        <tr>
            <th width="15%">Recibo</th>
            <th width="15%">Fecha Pago</th>
            <th width="25%">Estudiante</th>
            <th width="15%">Carnet</th>
            <th width="15%">Programa</th>
            <th width="10%">Monto</th>
            <th width="5%">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($recibos as $pago)
            @php
                $pagoCuota = $pago->pagos_cuotas->first();
                $cuota = $pagoCuota ? $pagoCuota->cuota : null;
                $inscripcion = $cuota ? $cuota->inscripcion : null;
                $estudiante = $inscripcion ? $inscripcion->estudiante : null;
                $persona = $estudiante ? $estudiante->persona : null;
                $programa = $inscripcion ? $inscripcion->ofertaAcademica->programa : null;
            @endphp
            <tr>
                <td>
                    <span class="fw-bold text-primary">{{ $pago->recibo }}</span>
                    <div class="small text-muted">
                        {{ $pago->tipo_pago }}
                    </div>
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                    <div class="small text-muted">
                        {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('H:i') }}
                    </div>
                </td>
                <td>
                    @if ($persona)
                        <div class="fw-medium">{{ $persona->nombres }}</div>
                        <div class="small">{{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</div>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>
                    @if ($persona)
                        <span class="badge bg-secondary">{{ $persona->carnet }}</span>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>
                    @if ($programa)
                        <div class="small">{{ $programa->nombre }}</div>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>
                    <div class="fw-bold text-success">{{ number_format($pago->pago_bs, 2) }} Bs</div>
                    @if ($pago->descuento_bs > 0)
                        <div class="small text-warning">Desc: {{ number_format($pago->descuento_bs, 2) }} Bs</div>
                    @endif
                </td>
                <td>
                    <button class="btn btn-sm btn-primary btn-ver-detalle" data-pago-id="{{ $pago->id }}">
                        <i class="ri-eye-line"></i>
                    </button>
                    <a href="{{ route('admin.estudiantes.descargar-recibo', $pago->id) }}"
                        class="btn btn-sm btn-success" target="_blank">
                        <i class="ri-download-line"></i>
                    </a>
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
                    <h5 class="mb-2">No se encontraron recibos</h5>
                    <p class="text-muted mb-0">Intenta cambiar los filtros de búsqueda</p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if ($recibos->hasPages())
    <div class="mt-3">
        {{ $recibos->links('pagination::bootstrap-5') }}
    </div>
@endif
