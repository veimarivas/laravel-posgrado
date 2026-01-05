@forelse ($vendedores as $vendedor)
    @php
        $persona = $vendedor->persona;
        $cargoActivo = $vendedor->trabajadores_cargos
            ->whereIn('cargo_id', [2, 3, 6])
            ->where('estado', 'Vigente')
            ->sortByDesc('principal')
            ->first();
    @endphp

    @if ($persona)
        <tr>
            <td class="px-3 py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                <i class="ri-id-card-line"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $persona->carnet ?? 'N/A' }}</h6>
                        <p class="text-muted mb-0 small">
                            @if ($persona->expedido)
                                Exp: {{ $persona->expedido }}
                            @endif
                        </p>
                    </div>
                </div>
            </td>
            <td class="px-3 py-3">
                <div>
                    <h6 class="mb-1 fw-medium">
                        {{ $persona->apellido_paterno ?? '' }} {{ $persona->apellido_materno ?? '' }},
                        {{ $persona->nombres ?? 'Sin nombre' }}
                    </h6>
                    <p class="text-muted mb-0 small">
                        @if ($persona->sexo)
                            <i class="ri-genderless-line me-1"></i>{{ $persona->sexo }}
                        @endif
                        @if ($persona->fecha_nacimiento)
                            • <i
                                class="ri-cake-line me-1"></i>{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->age }}
                            años
                        @endif
                    </p>
                </div>
            </td>
            <td class="px-3 py-3">
                @if ($cargoActivo && $cargoActivo->cargo)
                    <div class="mb-2">
                        <span class="badge bg-primary badge-cargo">
                            <i class="ri-briefcase-line me-1"></i>
                            {{ $cargoActivo->cargo->nombre ?? 'Sin cargo' }}
                        </span>
                    </div>
                    @if ($cargoActivo->sucursal)
                        <p class="mb-0 text-muted small">
                            <i class="ri-building-line me-1"></i>
                            {{ $cargoActivo->sucursal->nombre ?? 'Sin sucursal' }}
                        </p>
                    @endif
                    @if ($cargoActivo->fecha_ingreso)
                        <p class="mb-0 text-muted small">
                            <i class="ri-calendar-line me-1"></i>
                            Desde: {{ \Carbon\Carbon::parse($cargoActivo->fecha_ingreso)->format('d/m/Y') }}
                        </p>
                    @endif
                @else
                    <span class="badge bg-secondary badge-cargo">Sin cargo asignado</span>
                @endif
            </td>
            <td class="px-3 py-3">
                <div>
                    <p class="mb-1">
                        <i class="ri-mail-line me-1 text-primary"></i>
                        @if ($persona->correo)
                            <a href="mailto:{{ $persona->correo }}" class="text-reset">{{ $persona->correo }}</a>
                        @else
                            <span class="text-muted">Sin correo</span>
                        @endif
                    </p>
                    <p class="mb-1">
                        <i class="ri-phone-line me-1 text-success"></i>
                        {{ $persona->celular ?? 'Sin celular' }}
                    </p>
                    @if ($persona->telefono)
                        <p class="mb-0">
                            <i class="ri-phone-fill me-1 text-secondary"></i>
                            {{ $persona->telefono }}
                        </p>
                    @endif
                </div>
            </td>
            <td class="px-3 py-3 text-center">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.vendedor.inscripciones', ['personaId' => $persona->id]) }}"
                        class="btn btn-info btn-sm" title="Ver Inscripciones">
                        <i class="ri-list-check"></i> Ver Inscripciones
                    </a>
                </div>
            </td>
        </tr>
    @endif
@empty
    <tr>
        <td colspan="5" class="text-center py-5">
            <div class="text-muted">
                <i class="ri-user-unfollow-line display-4"></i>
                <p class="mt-2 fw-medium">No se encontraron vendedores</p>
                <p class="small">Los vendedores deben tener los cargos: Ejecutivo de Marketing, Asesor de Marketing o
                    Gerente de Marketing</p>
            </div>
        </td>
    </tr>
@endforelse
