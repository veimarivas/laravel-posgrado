<tbody>
    @forelse ($ofertas as $key => $oferta)
        @php
            $fase = $oferta->fase;
            $faseColor = $fase->color ?? '#cccccc';
            $bgColor = \App\Helpers\ViewHelper::hexToRgb($faseColor, 0.08);
            // Calcular el número de fila
            $rowNumber = ($ofertas->currentPage() - 1) * $ofertas->perPage() + $loop->iteration;
        @endphp

        <tr style="background-color: {{ $bgColor }}; transition: all 0.3s ease;">
            <!-- COLUMNA N° -->
            <td class="text-center fw-semibold" style="background-color: rgba(0,0,0,0.02);">
                {{ $rowNumber }}
            </td>

            <!-- COLUMNA CÓDIGO -->
            <td class="text-center">
                <span class="badge bg-dark bg-gradient" style="font-size: 0.85em; letter-spacing: 0.5px;">
                    {{ $oferta->codigo }}
                </span>
            </td>

            <!-- COLUMNA PROGRAMA -->
            <td>
                <div class="d-flex align-items-center">
                    <div>
                        <div class="fw-semibold" style="color: #495057; font-size: 0.95rem;">
                            {{ $oferta->programa->nombre ?? 'Sin programa' }}
                        </div>
                        <small class="text-muted d-block" style="font-size: 0.8rem;">
                            <i class="ri-building-2-line me-1"></i>
                            {{ $oferta->sucursal->sede->nombre ?? 'Sin sede' }} -
                            {{ $oferta->sucursal->nombre ?? 'Sin sucursal' }}
                        </small>
                    </div>
                </div>
            </td>

            <!-- COLUMNA N° MÓDULOS -->
            <td class="text-center">
                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                    {{ $oferta->n_modulos ?? 0 }}
                </span>
            </td>

            <!-- COLUMNA CONVENIO -->
            <td>
                <div class="d-flex align-items-center">
                    @if ($oferta->posgrado->convenio->imagen ?? false)
                        <img src="{{ asset($oferta->posgrado->convenio->imagen) }}"
                            alt="{{ $oferta->posgrado->convenio->sigla ?? 'Convenio' }}" class="rounded-circle me-2"
                            style="width: 40px; height: 40px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2"
                            style="width: 40px; height: 40px;">
                            <i class="ri-building-2-line text-muted"></i>
                        </div>
                    @endif
                    <div>
                        <div class="fw-medium" style="font-size: 0.9rem;">
                            {{ $oferta->posgrado->convenio->nombre ?? 'Sin convenio' }}
                        </div>
                        @if ($oferta->posgrado->convenio->sigla ?? false)
                            <small class="text-muted" style="font-size: 0.8rem;">
                                {{ $oferta->posgrado->convenio->sigla }}
                            </small>
                        @endif
                    </div>
                </div>
            </td>

            <!-- COLUMNA MODALIDAD -->
            <td class="text-center">
                <span
                    class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1">
                    {{ $oferta->modalidad->nombre ?? 'Sin modalidad' }}
                </span>
            </td>

            <!-- COLUMNA FECHAS -->
            <td>
                <div class="text-center">
                    <div class="mt-1">
                        <small class="badge bg-info bg-opacity-10 text-info">
                            <i class="ri-calendar-event-line me-1"></i>
                            {{ \Carbon\Carbon::parse($oferta->fecha_inicio_inscripciones)->format('d/m/Y') }}
                        </small>
                    </div>
                    <div class="fw-medium" style="color: #2e8b57; font-size: 0.9rem;">
                        {{ \Carbon\Carbon::parse($oferta->fecha_inicio_programa)->format('d/m/Y') }}
                    </div>
                    <small class="text-muted" style="font-size: 0.8rem;">
                        {{ \Carbon\Carbon::parse($oferta->fecha_fin_programa)->format('d/m/Y') }}
                    </small>
                </div>
            </td>

            <!-- COLUMNA INSCRITOS -->
            <td class="text-center">
                <div class="d-flex flex-column gap-1">
                    <div>
                        <span class="badge bg-success bg-gradient rounded-pill px-3 py-1"
                            style="font-size: 0.85rem; min-width: 50px;">
                            <i class="ri-user-follow-line me-1"></i>
                            {{ $oferta->totalInscritos() }}
                        </span>
                    </div>
                    @if ($oferta->totalPreInscritos() > 0)
                        <div>
                            <span class="badge bg-secondary bg-gradient rounded-pill px-3 py-1"
                                style="font-size: 0.75rem; min-width: 50px;">
                                <i class="ri-user-add-line me-1"></i>
                                {{ $oferta->totalPreInscritos() }}
                            </span>
                        </div>
                    @endif
                </div>
            </td>

            <!-- COLUMNA FASE -->
            <td class="text-center">
                <div class="d-flex flex-column align-items-center gap-1">
                    <span class="badge text-white px-3 py-1"
                        style="background-color: {{ $faseColor }}; font-size: 0.85rem; min-width: 100px;">
                        {{ $fase->nombre ?? 'Sin fase' }}
                    </span>
                    <small class="text-muted" style="font-size: 0.75rem;">
                        Fase {{ $fase->n_fase ?? '0' }}
                    </small>
                </div>
            </td>

            <!-- COLUMNA ACCIONES -->
            <td class="py-2">
                @include('admin.ofertas.partials.acciones-celda', ['oferta' => $oferta])
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="10" class="text-center py-4">
                <div class="d-flex flex-column align-items-center">
                    <i class="ri-inbox-line fs-1 text-muted mb-2"></i>
                    <h5 class="text-muted">No se encontraron ofertas académicas</h5>
                    <p class="text-muted">Intenta cambiar los filtros o crear una nueva oferta</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
