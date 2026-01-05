@forelse ($estudiantes as $e)
    @php
        $estudianteData = $e->toArray();
        $persona = $e->persona;
        $estudianteData['persona'] = $persona ? $persona->toArray() : [];

        if ($persona) {
            $estudianteData['persona']['ciudad'] = $persona->ciudad ? $persona->ciudad->nombre : '';
            $estudianteData['persona']['departamento'] =
                $persona->ciudad && $persona->ciudad->departamento ? $persona->ciudad->departamento->nombre : '';
            $estudianteData['persona']['departamento_id'] =
                $persona->ciudad && $persona->ciudad->departamento ? $persona->ciudad->departamento->id : '';
            $estudianteData['persona']['ciudade_id'] = $persona->ciudade_id;

            $estudianteData['persona']['estudios'] = $persona->estudios
                ->map(function ($estudio) {
                    return [
                        'id' => $estudio->id,
                        'grado' => $estudio->grados_academico_id,
                        'profesion' => $estudio->profesione_id,
                        'universidad' => $estudio->universidade_id,
                        'grado_nombre' => $estudio->grado_academico->nombre ?? '',
                        'profesion_nombre' => $estudio->profesion->nombre ?? '',
                        'universidad_nombre' => $estudio->universidad->nombre ?? '',
                    ];
                })
                ->toArray();
        }
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
                    <h6 class="mb-0">{{ $persona->carnet ?? '' }}</h6>
                    <p class="text-muted mb-0 small">
                        @if ($persona->expedido ?? '')
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
                    {{ $persona->nombres ?? '' }}
                </h6>
                <p class="text-muted mb-0 small">
                    @if ($persona->sexo ?? '')
                        <i class="ri-genderless-line me-1"></i>{{ $persona->sexo }} •
                    @endif
                    @if ($persona->estado_civil ?? '')
                        <i class="ri-heart-line me-1"></i>{{ $persona->estado_civil }}
                    @endif
                </p>
                @if ($persona->fecha_nacimiento ?? '')
                    <p class="text-muted mb-0 small">
                        <i class="ri-cake-line me-1"></i>
                        {{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->age }} años
                        ({{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') }})
                    </p>
                @endif
            </div>
        </td>
        <td class="px-3 py-3">
            <div>
                @if ($persona->correo ?? '')
                    <p class="mb-1">
                        <i class="ri-mail-line me-1 text-primary"></i>
                        <a href="mailto:{{ $persona->correo }}" class="text-reset">{{ $persona->correo }}</a>
                    </p>
                @endif
                @if ($persona->celular ?? '')
                    <p class="mb-1">
                        <i class="ri-phone-line me-1 text-success"></i>
                        {{ $persona->celular }}
                    </p>
                @endif
                @if ($persona->telefono ?? '')
                    <p class="mb-0">
                        <i class="ri-phone-fill me-1 text-secondary"></i>
                        {{ $persona->telefono }}
                    </p>
                @endif
            </div>
        </td>
        <td class="px-3 py-3">
            @if (($persona->ciudad ?? '') && ($estudianteData['persona']['ciudad'] ?? ''))
                <div class="mb-1">
                    <i class="ri-map-pin-line me-1 text-info"></i>
                    <span class="fw-medium">{{ $estudianteData['persona']['ciudad'] }}</span>
                    @if ($estudianteData['persona']['departamento'] ?? '')
                        <span class="text-muted">({{ $estudianteData['persona']['departamento'] }})</span>
                    @endif
                </div>
            @endif
            @if ($persona->direccion ?? '')
                <p class="mb-0 text-muted small">
                    <i class="ri-home-line me-1"></i>{{ Str::limit($persona->direccion, 30) }}
                </p>
            @endif
            @if (isset($estudianteData['persona']['estudios']) && count($estudianteData['persona']['estudios']) > 0)
                <div class="mt-2">
                    <span class="badge bg-info-subtle text-info">
                        <i class="ri-book-line me-1"></i>{{ count($estudianteData['persona']['estudios']) }} estudio(s)
                    </span>
                </div>
            @endif
        </td>
        <td class="px-3 py-3 text-center">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.estudiantes.detalle', $e->id) }}" title="Ver Estudiante"
                    class="btn btn-info btn-sm">
                    <i class="ri-eye-line"></i>
                </a>

                @if (Auth::guard('web')->user()->can('estudiantes.editar'))
                    <button type="button" title="Editar Estudiante" class="btn btn-warning btn-sm editBtnEstudiante"
                        data-bs-obj='@json($estudianteData)' data-bs-toggle="modal"
                        data-bs-target="#modalEditarEstudiante">
                        <i class="ri-edit-line"></i>
                    </button>
                @endif

                @if (Auth::guard('web')->user()->can('estudiantes.eliminar'))
                    <button type="button" title="Eliminar Estudiante" class="btn btn-danger btn-sm deleteBtnEstudiante"
                        data-bs-obj='@json($estudianteData)' data-bs-toggle="modal"
                        data-bs-target="#modalEliminarEstudiante">
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
                <p class="mt-2 fw-medium">No se encontraron estudiantes</p>
                <p class="small">Intenta con otra búsqueda o registra un nuevo estudiante</p>
            </div>
        </td>
    </tr>
@endforelse
