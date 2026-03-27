<table class="table table-hover align-middle mb-0">
    <thead class="table-light">
        <tr>
            <th style="width:18%">Recibo</th>
            <th style="width:12%">Fecha</th>
            <th style="width:22%">Estudiante</th>
            <th style="width:28%">Programa</th>
            <th style="width:10%">Monto</th>
            <th style="width:10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($recibos as $pago)
            @php
                $pagoCuota  = $pago->pagos_cuotas->first();
                $cuota      = $pagoCuota?->cuota;
                $inscripcion = $cuota?->inscripcion;
                $estudiante = $inscripcion?->estudiante;
                $persona    = $estudiante?->persona;
                $oferta     = $inscripcion?->ofertaAcademica;
                $programa   = $oferta?->programa;

                $tipoBadge = [
                    'Efectivo'      => 'bg-success',
                    'Transferencia' => 'bg-info',
                    'Depósito'      => 'bg-primary',
                    'Tarjeta'       => 'bg-warning text-dark',
                ];
                $badgeCls = $tipoBadge[$pago->tipo_pago] ?? 'bg-secondary';
            @endphp
            <tr>
                {{-- Recibo + tipo --}}
                <td>
                    <div class="fw-bold text-primary" style="font-size:.85rem;">{{ $pago->recibo }}</div>
                    <span class="badge {{ $badgeCls }} mt-1" style="font-size:.7rem;">
                        {{ $pago->tipo_pago ?? 'N/A' }}
                    </span>
                </td>

                {{-- Fecha --}}
                <td>
                    <div class="fw-medium">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</div>
                    <div class="text-muted" style="font-size:.75rem;">
                        {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('H:i') }}
                    </div>
                </td>

                {{-- Estudiante con link --}}
                <td>
                    @if ($persona && $estudiante)
                        <a href="{{ route('admin.estudiantes.detalle', $estudiante->id) }}"
                           class="text-body text-decoration-none fw-medium d-block"
                           title="Ver detalle del estudiante">
                            {{ $persona->nombres }}
                            {{ $persona->apellido_paterno }}
                            <i class="ri-external-link-line text-primary ms-1" style="font-size:.75rem;"></i>
                        </a>
                        <div class="mt-1">
                            <span class="badge bg-secondary" style="font-size:.7rem;">{{ $persona->carnet }}</span>
                        </div>
                    @else
                        <span class="text-muted small">N/A</span>
                    @endif
                </td>

                {{-- Programa con link a oferta --}}
                <td>
                    @if ($programa && $oferta)
                        <a href="{{ route('admin.ofertas.dashboard', $oferta->id) }}"
                           class="text-body text-decoration-none d-block"
                           style="font-size:.82rem; line-height:1.3;"
                           title="Ver dashboard de la oferta">
                            {{ $programa->nombre }}
                            <i class="ri-external-link-line text-success ms-1" style="font-size:.72rem;"></i>
                        </a>
                    @else
                        <span class="text-muted small">N/A</span>
                    @endif
                </td>

                {{-- Monto --}}
                <td>
                    <div class="fw-bold text-success">{{ number_format($pago->pago_bs, 2) }} Bs</div>
                    @if (($pago->descuento_bs ?? 0) > 0)
                        <div class="text-warning" style="font-size:.75rem;">
                            Desc: -{{ number_format($pago->descuento_bs, 2) }} Bs
                        </div>
                    @endif
                </td>

                {{-- Acciones --}}
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary btn-ver-detalle"
                            data-pago-id="{{ $pago->id }}"
                            title="Ver detalle">
                        <i class="ri-eye-line"></i>
                    </button>
                    <a href="{{ route('admin.estudiantes.descargar-recibo', $pago->id) }}"
                       class="btn btn-sm btn-outline-success ms-1"
                       target="_blank"
                       title="Descargar recibo PDF">
                        <i class="ri-download-line"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center py-5">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-light text-secondary rounded-circle">
                            <i class="ri-file-text-line fs-2"></i>
                        </div>
                    </div>
                    <h5 class="mb-1">No se encontraron recibos</h5>
                    <p class="text-muted mb-0 small">Intenta cambiar los filtros de búsqueda</p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex align-items-center justify-content-between px-3 py-2 border-top bg-light flex-wrap gap-2">
    {{-- Info de registros --}}
    <div class="text-muted small">
        @if ($recibos->total() > 0)
            Mostrando <strong>{{ $recibos->firstItem() }}</strong> – <strong>{{ $recibos->lastItem() }}</strong>
            de <strong>{{ $recibos->total() }}</strong> recibos
        @else
            Sin resultados
        @endif
    </div>

    {{-- Selector por página + paginación --}}
    <div class="d-flex align-items-center gap-3 flex-wrap">
        {{-- Per-page --}}
        <div class="d-flex align-items-center gap-1">
            <span class="text-muted small">Mostrar</span>
            <select class="form-select form-select-sm" id="perPageSelect" style="width:70px;">
                @foreach ([10, 20, 50, 100] as $opt)
                    <option value="{{ $opt }}" {{ $recibos->perPage() == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>

        {{-- Links --}}
        @if ($recibos->hasPages())
            <div class="pagination-wrapper">
                {{ $recibos->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
