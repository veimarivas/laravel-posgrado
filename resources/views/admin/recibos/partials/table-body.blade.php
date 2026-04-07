<table class="recibos-table">
    <thead>
        <tr>
            <th style="width:16%">Recibo</th>
            <th style="width:12%">Fecha</th>
            <th style="width:22%">Estudiante</th>
            <th style="width:28%">Programa</th>
            <th style="width:12%">Monto</th>
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

                $tipoCls = match($pago->tipo_pago ?? '') {
                    'Efectivo'      => 'efectivo',
                    'Transferencia' => 'transferencia',
                    'Depósito'      => 'deposito',
                    'Tarjeta'       => 'tarjeta',
                    default         => 'otro',
                };
            @endphp
            <tr>
                <td>
                    <div class="recibo-cell">
                        <span class="recibo-number">{{ $pago->recibo }}</span>
                        <span class="tipo-badge {{ $tipoCls }}">{{ $pago->tipo_pago ?? 'N/A' }}</span>
                    </div>
                </td>

                <td>
                    <div class="date-cell">
                        <div class="date-main">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</div>
                        <div class="date-time">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('H:i') }}</div>
                    </div>
                </td>

                <td>
                    @if ($persona && $estudiante)
                        <div class="student-cell">
                            <a href="{{ route('admin.estudiantes.detalle', $estudiante->id) }}"
                               title="Ver detalle del estudiante">
                                {{ $persona->nombres }} {{ $persona->apellido_paterno }}
                                <i class="ri-external-link-line text-primary ms-1"></i>
                            </a>
                            <div class="mt-1">
                                <span class="carnet-badge">{{ $persona->carnet }}</span>
                            </div>
                        </div>
                    @else
                        <span class="text-muted small">N/A</span>
                    @endif
                </td>

                <td>
                    @if ($programa && $oferta)
                        <div class="program-cell">
                            <a href="{{ route('admin.ofertas.dashboard', $oferta->id) }}"
                               title="Ver dashboard de la oferta">
                                {{ $programa->nombre }}
                                <i class="ri-external-link-line text-success ms-1" style="font-size:.72rem;"></i>
                            </a>
                        </div>
                    @else
                        <span class="text-muted small">N/A</span>
                    @endif
                </td>

                <td>
                    <div class="amount-cell">
                        <div class="amount-main">{{ number_format($pago->pago_bs, 2) }} Bs</div>
                        @if (($pago->descuento_bs ?? 0) > 0)
                            <div class="amount-discount">
                                Desc: -{{ number_format($pago->descuento_bs, 2) }} Bs
                            </div>
                        @endif
                    </div>
                </td>

                <td class="text-center">
                    <div class="d-flex gap-1 justify-content-center">
                        <button class="action-btn view btn-ver-detalle"
                                data-pago-id="{{ $pago->id }}"
                                title="Ver detalle">
                            <i class="ri-eye-line"></i>
                        </button>
                        <a href="{{ route('admin.estudiantes.descargar-recibo', $pago->id) }}"
                           class="action-btn download"
                           target="_blank"
                           title="Descargar recibo PDF">
                            <i class="ri-download-line"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="ri-file-text-line"></i>
                        </div>
                        <h5>No se encontraron recibos</h5>
                        <p>Intenta cambiar los filtros de búsqueda</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if ($recibos->total() > 0)
    <div class="table-footer">
        <div class="results-count">
            Mostrando <span class="fw-medium">{{ $recibos->firstItem() }}</span> a
            <span class="fw-medium">{{ $recibos->lastItem() }}</span> de
            <span class="fw-medium">{{ $recibos->total() }}</span> recibos
        </div>
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-1">
                <span class="text-muted small">Mostrar</span>
                <select class="form-select form-select-sm" id="perPageSelect" style="width:70px;">
                    @foreach ([10, 20, 50, 100] as $opt)
                        <option value="{{ $opt }}" {{ $recibos->perPage() == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>
            @if ($recibos->hasPages())
                <div class="pagination-wrapper">
                    {{ $recibos->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endif
