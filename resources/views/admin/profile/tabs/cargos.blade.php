@if (auth()->user()->persona->trabajador && auth()->user()->persona->trabajador->trabajadores_cargos->count() > 0)

    <div class="table-responsive">
        <table class="cargo-table">
            <thead>
                <tr>
                    <th style="width:30%;">Cargo</th>
                    <th style="width:28%;">Sucursal</th>
                    <th style="width:13%;text-align:center;">Estado</th>
                    <th style="width:19%;">Fechas</th>
                    <th style="width:10%;text-align:center;">Principal</th>
                </tr>
            </thead>
            <tbody>
                @foreach (auth()->user()->persona->trabajador->trabajadores_cargos as $cargo)
                    <tr class="{{ $cargo->principal ? 'row-principal' : '' }}">

                        {{-- Cargo --}}
                        <td>
                            <div class="d-flex align-items-center gap-10">
                                <div class="data-row-icon bg-primary-subtle text-primary" style="width:34px;height:34px;">
                                    <i class="ri-briefcase-line"></i>
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
                        <td>
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
                        <td style="text-align:center;">
                            <span class="estado-badge-profile {{ $cargo->estado === 'Vigente' ? 'vigente' : 'inactivo' }}">
                                <i class="ri-{{ $cargo->estado === 'Vigente' ? 'checkbox-circle' : 'close-circle' }}-line"></i>
                                {{ $cargo->estado }}
                            </span>
                        </td>

                        {{-- Fechas --}}
                        <td>
                            <div style="font-size:.8rem;">
                                <div class="text-muted" style="font-size:.68rem;text-transform:uppercase;letter-spacing:.04em;font-weight:600;">Inicio</div>
                                <div class="fw-medium">{{ $cargo->fecha_ingreso ? \Carbon\Carbon::parse($cargo->fecha_ingreso)->format('d/m/Y') : '—' }}</div>
                                @if ($cargo->fecha_termino)
                                    <div class="text-muted mt-1" style="font-size:.68rem;text-transform:uppercase;letter-spacing:.04em;font-weight:600;">Fin</div>
                                    <div class="fw-medium text-danger">{{ \Carbon\Carbon::parse($cargo->fecha_termino)->format('d/m/Y') }}</div>
                                @endif
                            </div>
                        </td>

                        {{-- Principal --}}
                        <td style="text-align:center;">
                            @if ($cargo->principal)
                                <span class="principal-badge">
                                    <i class="ri-star-fill"></i>Principal
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
    <div class="empty-state-profile">
        <div class="empty-state-profile-icon">
            <i class="ri-briefcase-line"></i>
        </div>
        <h5>No tienes cargos asignados</h5>
        <p class="text-muted small mb-0">Contacta con el administrador para asignarte un cargo.</p>
    </div>
@endif
