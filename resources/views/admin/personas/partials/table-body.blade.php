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
                    'grado_nombre' => $e->grado_academico->nombre ?? '',
                    'profesion_nombre' => $e->profesion->nombre ?? '',
                    'universidad_nombre' => $e->universidad->nombre ?? '',
                ];
            })
            ->toArray();

        $personaConEstudios['ciudad'] = $p->ciudad ? $p->ciudad->nombre : '';
        $personaConEstudios['departamento'] =
            $p->ciudad && $p->ciudad->departamento ? $p->ciudad->departamento->nombre : '';
        $personaConEstudios['departamento_id'] =
            $p->ciudad && $p->ciudad->departamento ? $p->ciudad->departamento->id : '';
        $personaConEstudios['ciudade_id'] = $p->ciudade_id;

        // Avatar initials + color
        $initials = strtoupper(
            substr($p->nombres ?? '', 0, 1) .
            substr($p->apellido_paterno ?? $p->apellido_materno ?? '', 0, 1)
        );
        $colors = ['#667eea','#20c997','#fd7e14','#e83e8c','#6f42c1','#0dcaf0','#198754'];
        $avatarColor = $colors[$p->id % count($colors)];
    @endphp

    <tr>
        {{-- Carnet --}}
        <td class="px-4 py-3">
            <div class="d-flex align-items-center gap-3">
                <div class="persona-avatar flex-shrink-0"
                     style="background:{{ $avatarColor }}1a;color:{{ $avatarColor }};">
                    {{ $initials ?: '?' }}
                </div>
                <div>
                    <span class="fw-semibold d-block" style="font-size:.88rem;">{{ $p->carnet }}</span>
                    @if ($p->expedido)
                        <span class="text-muted" style="font-size:.74rem;">Exp: {{ $p->expedido }}</span>
                    @endif
                </div>
            </div>
        </td>

        {{-- Nombre Completo --}}
        <td class="px-4 py-3">
            <span class="fw-semibold d-block" style="font-size:.9rem;">
                {{ trim(($p->apellido_paterno ?? '') . ' ' . ($p->apellido_materno ?? '')) }},
                {{ $p->nombres }}
            </span>
            <div class="d-flex flex-wrap gap-1 mt-1">
                @if ($p->sexo)
                    <span class="badge rounded-pill" style="font-size:.7rem;background:rgba(102,126,234,.12);color:#667eea;">
                        <i class="ri-genderless-line me-1"></i>{{ $p->sexo }}
                    </span>
                @endif
                @if ($p->estado_civil)
                    <span class="badge rounded-pill" style="font-size:.7rem;background:rgba(232,62,140,.1);color:#e83e8c;">
                        {{ $p->estado_civil }}
                    </span>
                @endif
                @if ($p->fecha_nacimiento)
                    <span class="badge rounded-pill" style="font-size:.7rem;background:rgba(32,201,151,.1);color:#20c997;">
                        <i class="ri-cake-line me-1"></i>{{ \Carbon\Carbon::parse($p->fecha_nacimiento)->age }} años
                    </span>
                @endif
            </div>
        </td>

        {{-- Contacto --}}
        <td class="px-4 py-3">
            <div class="d-flex flex-column gap-1">
                <div class="d-flex align-items-center gap-2" style="font-size:.84rem;">
                    <i class="ri-mail-line" style="color:#667eea;font-size:.9rem;"></i>
                    <a href="mailto:{{ $p->correo }}" class="text-reset">{{ $p->correo }}</a>
                </div>
                <div class="d-flex align-items-center gap-2" style="font-size:.84rem;">
                    <i class="ri-smartphone-line" style="color:#20c997;font-size:.9rem;"></i>
                    <span>{{ $p->celular }}</span>
                </div>
                @if ($p->telefono)
                    <div class="d-flex align-items-center gap-2" style="font-size:.84rem;">
                        <i class="ri-phone-line" style="color:#6c757d;font-size:.9rem;"></i>
                        <span class="text-muted">{{ $p->telefono }}</span>
                    </div>
                @endif
            </div>
        </td>

        {{-- Información --}}
        <td class="px-4 py-3">
            @if ($p->ciudad)
                <div class="d-flex align-items-center gap-1 mb-1" style="font-size:.84rem;">
                    <i class="ri-map-pin-line" style="color:#0dcaf0;font-size:.9rem;"></i>
                    <span class="fw-medium">{{ $p->ciudad->nombre }}</span>
                    @if ($p->ciudad->departamento)
                        <span class="text-muted">({{ $p->ciudad->departamento->nombre }})</span>
                    @endif
                </div>
            @endif
            @if ($p->direccion)
                <div class="d-flex align-items-center gap-1 mb-1" style="font-size:.82rem;">
                    <i class="ri-home-4-line text-muted" style="font-size:.85rem;"></i>
                    <span class="text-muted">{{ Str::limit($p->direccion, 30) }}</span>
                </div>
            @endif
            @if ($p->estudios->count() > 0)
                <span class="badge rounded-pill mt-1" style="font-size:.7rem;background:rgba(111,66,193,.1);color:#6f42c1;">
                    <i class="ri-book-2-line me-1"></i>{{ $p->estudios->count() }} estudio(s)
                </span>
            @endif
        </td>

        {{-- Acciones --}}
        <td class="px-4 py-3 text-center">
            <div class="d-flex align-items-center justify-content-center gap-1">
                @if (Auth::guard('web')->user()->can('personas.editar'))
                    <button type="button" title="Editar Persona"
                        class="btn btn-action editBtn"
                        style="background:rgba(253,126,20,.1);border:1px solid rgba(253,126,20,.2);color:#fd7e14;"
                        data-bs-obj='@json($personaConEstudios)'
                        data-bs-toggle="modal" data-bs-target="#modalModificar">
                        <i class="ri-edit-line"></i>
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('personas.eliminar'))
                    <button type="button" title="Eliminar Persona"
                        class="btn btn-action deleteBtn"
                        style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);color:#ef4444;"
                        data-bs-obj='@json($p)'
                        data-bs-toggle="modal" data-bs-target="#modalEliminar">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-5">
            <div class="d-flex flex-column align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:60px;height:60px;background:rgba(102,126,234,.08);">
                    <i class="ri-user-unfollow-line" style="font-size:1.6rem;color:#667eea;opacity:.6;"></i>
                </div>
                <p class="fw-semibold mb-0 text-muted" style="font-size:.9rem;">No se encontraron personas</p>
                <p class="mb-0 text-muted" style="font-size:.8rem;">Intenta con otra búsqueda o registra una nueva persona</p>
            </div>
        </td>
    </tr>
@endforelse
