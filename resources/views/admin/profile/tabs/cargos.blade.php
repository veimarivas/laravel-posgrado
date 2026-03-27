@if (auth()->user()->persona->trabajador && auth()->user()->persona->trabajador->trabajadores_cargos->count() > 0)

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.84rem;">
            <thead>
                <tr style="background:#f8f9fa;">
                    <th class="border-0 py-3 px-3 text-muted fw-semibold" style="font-size:.7rem;width:30%;">CARGO</th>
                    <th class="border-0 py-3 text-muted fw-semibold"       style="font-size:.7rem;width:28%;">SUCURSAL</th>
                    <th class="border-0 py-3 text-muted fw-semibold text-center" style="font-size:.7rem;width:13%;">ESTADO</th>
                    <th class="border-0 py-3 text-muted fw-semibold"       style="font-size:.7rem;width:19%;">FECHAS</th>
                    <th class="border-0 py-3 text-muted fw-semibold text-center" style="font-size:.7rem;width:10%;">PRINCIPAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach (auth()->user()->persona->trabajador->trabajadores_cargos as $cargo)
                    <tr style="{{ $cargo->principal ? 'background:rgba(13,110,253,.04);' : '' }}">

                        {{-- Cargo --}}
                        <td class="px-3 py-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-xs flex-shrink-0">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded">
                                        <i class="ri-briefcase-line fs-14"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $cargo->cargo->nombre ?? 'N/A' }}</div>
                                    @if (in_array($cargo->cargo_id, [2, 3, 6]))
                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill" style="font-size:.65rem;">Marketing</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Sucursal --}}
                        <td class="py-2">
                            @if ($cargo->sucursal)
                                <div class="fw-medium">{{ $cargo->sucursal->nombre ?? 'N/A' }}</div>
                                @if ($cargo->sucursal->sede)
                                    <div class="text-muted" style="font-size:.75rem;">
                                        <i class="ri-building-line me-1"></i>{{ $cargo->sucursal->sede->nombre }}
                                    </div>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Estado --}}
                        <td class="text-center py-2">
                            <span class="badge {{ $cargo->estado === 'Vigente' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary border border-secondary-subtle' }} rounded-pill" style="font-size:.72rem;">
                                <i class="ri-{{ $cargo->estado === 'Vigente' ? 'checkbox-circle' : 'close-circle' }}-line me-1"></i>{{ $cargo->estado }}
                            </span>
                        </td>

                        {{-- Fechas --}}
                        <td class="py-2">
                            <div style="font-size:.8rem;">
                                <div class="text-muted" style="font-size:.7rem;">INICIO</div>
                                <div class="fw-medium">{{ $cargo->fecha_ingreso ? \Carbon\Carbon::parse($cargo->fecha_ingreso)->format('d/m/Y') : '—' }}</div>
                                @if ($cargo->fecha_termino)
                                    <div class="text-muted mt-1" style="font-size:.7rem;">FIN</div>
                                    <div class="fw-medium text-danger">{{ \Carbon\Carbon::parse($cargo->fecha_termino)->format('d/m/Y') }}</div>
                                @endif
                            </div>
                        </td>

                        {{-- Principal --}}
                        <td class="text-center py-2">
                            @if ($cargo->principal)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size:.7rem;">
                                    <i class="ri-star-fill me-1"></i>Principal
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@else
    <div class="text-center py-5">
        <div class="avatar-lg mx-auto mb-3">
            <div class="avatar-title bg-light text-secondary rounded-circle">
                <i class="ri-briefcase-line fs-2"></i>
            </div>
        </div>
        <h5 class="mb-1">No tienes cargos asignados</h5>
        <p class="text-muted small mb-0">Contacta con el administrador para asignarte un cargo.</p>
    </div>
@endif
