@forelse ($personas as $p)
    @php
        $personaConEstudios = $p->toArray();
        $personaConEstudios['estudios'] = $p->estudios
            ->map(function ($e) {
                return [
                    'id' => $e->id,
                    'grado' => $e->grados_academico_id,
                    'profesion' => $e->profesione_id,
                    'universidad' => $e->universidade_id,
                    'estado' => $e->estado,
                    'documento' => $e->documento,
                    'grado_nombre' => $e->grado_academico->nombre ?? '', // CAMBIADO
                    'profesion_nombre' => $e->profesion->nombre ?? '', // CAMBIADO
                    'universidad_nombre' => $e->universidad->nombre ?? '', // CAMBIADO
                ];
            })
            ->toArray();

        // Datos de ubicación
        $personaConEstudios['ciudad'] = $p->ciudad ? $p->ciudad->nombre : '';
        $personaConEstudios['departamento'] =
            $p->ciudad && $p->ciudad->departamento ? $p->ciudad->departamento->nombre : '';
        $personaConEstudios['departamento_id'] =
            $p->ciudad && $p->ciudad->departamento ? $p->ciudad->departamento->id : '';
        $personaConEstudios['ciudade_id'] = $p->ciudade_id;
    @endphp

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
                    <h6 class="mb-0">{{ $p->carnet }}</h6>
                    <p class="text-muted mb-0 small">
                        @if ($p->expedido)
                            Exp: {{ $p->expedido }}
                        @endif
                    </p>
                </div>
            </div>
        </td>
        <td class="px-3 py-3">
            <div>
                <h6 class="mb-1 fw-medium">
                    {{ $p->apellido_paterno }} {{ $p->apellido_materno }}, {{ $p->nombres }}
                </h6>
                <p class="text-muted mb-0 small">
                    @if ($p->sexo)
                        <i class="ri-genderless-line me-1"></i>{{ $p->sexo }} •
                    @endif
                    @if ($p->estado_civil)
                        <i class="ri-heart-line me-1"></i>{{ $p->estado_civil }}
                    @endif
                </p>
                @if ($p->fecha_nacimiento)
                    <p class="text-muted mb-0 small">
                        <i class="ri-cake-line me-1"></i>
                        {{ \Carbon\Carbon::parse($p->fecha_nacimiento)->age }} años
                        ({{ \Carbon\Carbon::parse($p->fecha_nacimiento)->format('d/m/Y') }})
                    </p>
                @endif
            </div>
        </td>
        <td class="px-3 py-3">
            <div>
                <p class="mb-1">
                    <i class="ri-mail-line me-1 text-primary"></i>
                    <a href="mailto:{{ $p->correo }}" class="text-reset">{{ $p->correo }}</a>
                </p>
                <p class="mb-1">
                    <i class="ri-phone-line me-1 text-success"></i>
                    {{ $p->celular }}
                </p>
                @if ($p->telefono)
                    <p class="mb-0">
                        <i class="ri-phone-fill me-1 text-secondary"></i>
                        {{ $p->telefono }}
                    </p>
                @endif
            </div>
        </td>
        <td class="px-3 py-3">
            @if ($p->ciudad)
                <div class="mb-1">
                    <i class="ri-map-pin-line me-1 text-info"></i>
                    <span class="fw-medium">{{ $p->ciudad->nombre }}</span>
                    @if ($p->ciudad->departamento)
                        <span class="text-muted">({{ $p->ciudad->departamento->nombre }})</span>
                    @endif
                </div>
            @endif
            @if ($p->direccion)
                <p class="mb-0 text-muted small">
                    <i class="ri-home-line me-1"></i>{{ Str::limit($p->direccion, 30) }}
                </p>
            @endif
            @if ($p->estudios->count() > 0)
                <div class="mt-2">
                    <span class="badge bg-info-subtle text-info">
                        <i class="ri-book-line me-1"></i>{{ $p->estudios->count() }} estudio(s)
                    </span>
                </div>
            @endif
        </td>
        <td class="px-3 py-3 text-center">
            <div class="btn-group" role="group">
                @if (Auth::guard('web')->user()->can('personas.editar'))
                    <button type="button" title="Editar Persona" class="btn btn-warning btn-sm editBtn"
                        data-bs-obj='@json($personaConEstudios)' data-bs-toggle="modal"
                        data-bs-target="#modalModificar">
                        <i class="ri-edit-line"></i>
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('personas.eliminar'))
                    <button type="button" title="Eliminar Persona" class="btn btn-danger btn-sm deleteBtn"
                        data-bs-obj='@json($p)' data-bs-toggle="modal"
                        data-bs-target="#modalEliminar">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-5">
            <div class="text-muted">
                <i class="ri-user-unfollow-line display-4"></i>
                <p class="mt-2 fw-medium">No se encontraron personas</p>
                <p class="small">Intenta con otra búsqueda o registra una nueva persona</p>
            </div>
        </td>
    </tr>
@endforelse
