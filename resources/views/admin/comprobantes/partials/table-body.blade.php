<table class="comprobantes-table">
    <thead>
        <tr>
            <th style="width:8%">ID</th>
            <th style="width:14%">Fecha</th>
            <th style="width:20%">Estudiante</th>
            <th style="width:22%">Programa</th>
            <th style="width:10%">Cuotas</th>
            <th style="width:11%">Estado</th>
            <th style="width:15%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($comprobantes as $comp)
            @php
                $estudiante = $comp->inscripcion->estudiante->persona ?? null;
                $programa = $comp->inscripcion->ofertaAcademica->programa ?? null;
            @endphp
            <tr>
                <td>
                    <span class="comp-id">#{{ $comp->id }}</span>
                </td>
                <td>
                    <div class="date-cell">
                        <div class="date-main">{{ $comp->created_at->format('d/m/Y') }}</div>
                        <div class="date-time">{{ $comp->created_at->format('H:i') }}</div>
                    </div>
                </td>
                <td>
                    @if ($estudiante)
                        <div class="student-cell">
                            <span class="student-name">{{ $estudiante->nombres }} {{ $estudiante->apellido_paterno }}</span>
                            <span class="student-carnet">{{ $estudiante->carnet }}</span>
                        </div>
                    @else
                        <span class="text-muted small">N/A</span>
                    @endif
                </td>
                <td>{{ $programa->nombre ?? 'N/A' }}</td>
                <td>
                    <span class="cuotas-badge">{{ $comp->cuotas->count() }} cuota(s)</span>
                </td>
                <td>
                    @php
                        $estadoCls = match($comp->estado) {
                            'pendiente' => 'pendiente',
                            'verificado' => 'verificado',
                            'rechazado' => 'rechazado',
                            default => 'pendiente',
                        };
                        $estadoIcon = match($comp->estado) {
                            'pendiente' => 'ri-time-line',
                            'verificado' => 'ri-checkbox-circle-line',
                            'rechazado' => 'ri-close-circle-line',
                            default => 'ri-time-line',
                        };
                    @endphp
                    <span class="estado-badge {{ $estadoCls }}">
                        <span class="estado-dot"></span>
                        {{ ucfirst($comp->estado) }}
                    </span>
                </td>
                <td class="text-center">
                    <div class="d-flex gap-1 justify-content-center">
                        <a href="{{ Storage::url($comp->archivo) }}" target="_blank"
                           class="action-btn view"
                           title="Ver comprobante">
                            <i class="ri-eye-line"></i>
                        </a>
                        @if ($comp->estado == 'pendiente')
                            <button class="action-btn verify btn-verificar" data-id="{{ $comp->id }}"
                                    title="Verificar y registrar pago">
                                <i class="ri-check-line"></i>
                            </button>
                            <button class="action-btn reject btn-rechazar" data-id="{{ $comp->id }}"
                                    title="Rechazar">
                                <i class="ri-close-line"></i>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="ri-shield-check-line"></i>
                        </div>
                        <h5>No se encontraron comprobantes</h5>
                        <p>Prueba con otros filtros</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if ($comprobantes instanceof \Illuminate\Pagination\LengthAwarePaginator && $comprobantes->hasPages())
    <div class="table-footer">
        <div class="results-count">
            Mostrando <span class="fw-medium">{{ $comprobantes->firstItem() }}</span> a
            <span class="fw-medium">{{ $comprobantes->lastItem() }}</span> de
            <span class="fw-medium">{{ $comprobantes->total() }}</span> resultados
        </div>
        <div id="pagination-links">
            {{ $comprobantes->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
