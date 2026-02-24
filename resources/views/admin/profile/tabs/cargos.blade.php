@if (auth()->user()->persona->trabajador && auth()->user()->persona->trabajador->trabajadores_cargos->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th width="25%">Cargo</th>
                    <th width="25%">Sucursal</th>
                    <th width="15%">Estado</th>
                    <th width="20%">Fechas</th>
                    <th width="15%" class="text-center">Principal</th>
                </tr>
            </thead>
            <tbody>
                @foreach (auth()->user()->persona->trabajador->trabajadores_cargos as $cargo)
                    <tr class="{{ $cargo->principal ? 'table-primary' : '' }}">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded">
                                            <i class="ri-briefcase-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <strong>{{ $cargo->cargo->nombre ?? 'N/A' }}</strong>
                                    @if (in_array($cargo->cargo_id, [2, 3, 6]))
                                        <span class="badge bg-info ms-1 badge-status">Marketing</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if ($cargo->sucursal)
                                <div>
                                    <strong>{{ $cargo->sucursal->nombre ?? 'N/A' }}</strong>
                                    @if ($cargo->sucursal->sede)
                                        <br>
                                        <small class="text-muted">{{ $cargo->sucursal->sede->nombre }}</small>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <span
                                class="badge {{ $cargo->estado == 'Vigente' ? 'bg-success' : 'bg-secondary' }} badge-status">
                                {{ $cargo->estado }}
                            </span>
                        </td>
                        <td>
                            <div class="small">
                                <div class="text-muted">Inicio:</div>
                                <strong>{{ $cargo->fecha_ingreso ? \Carbon\Carbon::parse($cargo->fecha_ingreso)->format('d/m/Y') : 'N/A' }}</strong>
                                @if ($cargo->fecha_termino)
                                    <div class="text-muted mt-1">Fin:</div>
                                    <strong>{{ \Carbon\Carbon::parse($cargo->fecha_termino)->format('d/m/Y') }}</strong>
                                @endif
                            </div>
                        </td>
                        <td class="text-center">
                            @if ($cargo->principal)
                                <span class="badge bg-primary badge-status">
                                    <i class="ri-star-fill"></i> Principal
                                </span>
                            @else
                                <span class="text-muted">-</span>
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
                <i class="ri-briefcase-line display-4"></i>
            </div>
        </div>
        <h5 class="text-muted">No tienes cargos asignados</h5>
        <p class="text-muted mb-0">Contacta con el administrador para asignarte un cargo.</p>
    </div>
@endif
