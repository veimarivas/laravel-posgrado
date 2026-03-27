@php $persona = $estudiante->persona; @endphp

<div class="row g-4">

    {{-- Columna izquierda: Datos personales + Estudios --}}
    <div class="col-lg-6">

        {{-- Datos personales --}}
        <div class="card border-0 shadow-sm h-auto">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2 py-3">
                <div class="avatar-xs">
                    <div class="avatar-title bg-primary-subtle text-primary rounded">
                        <i class="ri-user-line fs-14"></i>
                    </div>
                </div>
                <h6 class="mb-0 fw-semibold">Datos Personales</h6>
            </div>
            <div class="card-body p-0">
                @php
                    $camposPersonal = [
                        ['icon' => 'ri-id-card-line',      'color' => 'secondary', 'label' => 'Carnet',
                         'value' => ($persona->carnet ?? 'N/A') . ($persona->expedido ? ' (' . $persona->expedido . ')' : '')],
                        ['icon' => 'ri-user-3-line',        'color' => 'primary',   'label' => 'Nombre completo',
                         'value' => trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '')) ?: 'N/A'],
                        ['icon' => 'ri-genderless-line',    'color' => 'info',      'label' => 'Sexo',
                         'value' => $persona->sexo ?? 'N/A'],
                        ['icon' => 'ri-heart-line',         'color' => 'danger',    'label' => 'Estado civil',
                         'value' => $persona->estado_civil ?? 'N/A'],
                        ['icon' => 'ri-cake-line',          'color' => 'warning',   'label' => 'Fecha de nacimiento',
                         'value' => $persona->fecha_nacimiento
                             ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') . ' (' . \Carbon\Carbon::parse($persona->fecha_nacimiento)->age . ' años)'
                             : 'N/A'],
                    ];
                @endphp
                @foreach ($camposPersonal as $i => $campo)
                    <div class="d-flex align-items-center gap-3 px-3 py-3 {{ $i < count($camposPersonal) - 1 ? 'border-bottom' : '' }}">
                        <div class="avatar-xs flex-shrink-0">
                            <div class="avatar-title bg-{{ $campo['color'] }}-subtle text-{{ $campo['color'] }} rounded">
                                <i class="{{ $campo['icon'] }} fs-14"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted" style="font-size:.72rem;">{{ strtoupper($campo['label']) }}</div>
                            <div class="fw-medium small">{{ $campo['value'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Estudios realizados --}}
        @if ($persona->estudios->count() > 0)
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2 py-3">
                    <div class="avatar-xs">
                        <div class="avatar-title bg-success-subtle text-success rounded">
                            <i class="ri-graduation-cap-line fs-14"></i>
                        </div>
                    </div>
                    <h6 class="mb-0 fw-semibold">Estudios Realizados</h6>
                    <span class="badge bg-primary rounded-pill ms-auto">{{ $persona->estudios->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @foreach ($persona->estudios as $i => $estudio)
                        <div class="d-flex align-items-center gap-3 px-3 py-3 {{ $i < $persona->estudios->count() - 1 ? 'border-bottom' : '' }}">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title {{ $estudio->principal ? 'bg-primary text-white' : 'bg-light text-secondary' }} rounded-2">
                                    <i class="ri-school-line fs-16"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="fw-semibold small text-truncate">
                                        {{ optional($estudio->profesion)->nombre ?? 'N/A' }}
                                    </span>
                                    @if ($estudio->principal)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size:.65rem;">Principal</span>
                                    @endif
                                </div>
                                <div class="text-muted" style="font-size:.75rem;">
                                    <i class="ri-building-line me-1"></i>
                                    {{ optional($estudio->universidad)->nombre ?? 'N/A' }}
                                    @if(optional($estudio->universidad)->sigla)
                                        ({{ $estudio->universidad->sigla }})
                                    @endif
                                </div>
                                <div class="text-muted" style="font-size:.72rem;">
                                    {{ optional($estudio->grado_academico)->nombre ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size:.7rem;">
                                    {{ $estudio->estado ?? 'Concluido' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    {{-- Columna derecha: Contacto + Registro --}}
    <div class="col-lg-6">

        {{-- Información de contacto --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2 py-3">
                <div class="avatar-xs">
                    <div class="avatar-title bg-info-subtle text-info rounded">
                        <i class="ri-contacts-line fs-14"></i>
                    </div>
                </div>
                <h6 class="mb-0 fw-semibold">Información de Contacto</h6>
            </div>
            <div class="card-body p-0">
                @php
                    $camposContacto = [
                        ['icon' => 'ri-mail-line',      'color' => 'info',      'label' => 'Correo electrónico',
                         'value' => $persona->correo ?? null,  'link' => 'mailto:' . ($persona->correo ?? '')],
                        ['icon' => 'ri-smartphone-line','color' => 'success',   'label' => 'Celular',
                         'value' => $persona->celular ?? null, 'link' => null],
                        ['icon' => 'ri-phone-line',     'color' => 'primary',   'label' => 'Teléfono',
                         'value' => $persona->telefono ?? null,'link' => null],
                        ['icon' => 'ri-map-pin-2-line', 'color' => 'danger',    'label' => 'Dirección',
                         'value' => $persona->direccion ?? null,'link' => null],
                        ['icon' => 'ri-building-2-line','color' => 'warning',   'label' => 'Ciudad',
                         'value' => (optional($persona->ciudad)->nombre ?? null)
                             ? (optional($persona->ciudad)->nombre . ', ' . (optional(optional($persona->ciudad)->departamento)->nombre ?? ''))
                             : null,
                         'link' => null],
                    ];
                @endphp
                @foreach ($camposContacto as $i => $campo)
                    <div class="d-flex align-items-center gap-3 px-3 py-3 {{ $i < count($camposContacto) - 1 ? 'border-bottom' : '' }}">
                        <div class="avatar-xs flex-shrink-0">
                            <div class="avatar-title bg-{{ $campo['color'] }}-subtle text-{{ $campo['color'] }} rounded">
                                <i class="{{ $campo['icon'] }} fs-14"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted" style="font-size:.72rem;">{{ strtoupper($campo['label']) }}</div>
                            @if ($campo['value'])
                                @if ($campo['link'])
                                    <a href="{{ $campo['link'] }}" class="fw-medium small text-primary">{{ $campo['value'] }}</a>
                                @else
                                    <div class="fw-medium small">{{ $campo['value'] }}</div>
                                @endif
                            @else
                                <div class="text-muted small fst-italic">No registrado</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Información del registro --}}
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2 py-3">
                <div class="avatar-xs">
                    <div class="avatar-title bg-secondary-subtle text-secondary rounded">
                        <i class="ri-settings-3-line fs-14"></i>
                    </div>
                </div>
                <h6 class="mb-0 fw-semibold">Información del Registro</h6>
            </div>
            <div class="card-body p-0">
                @php
                    $camposRegistro = [
                        ['icon' => 'ri-calendar-check-line', 'color' => 'primary',   'label' => 'Fecha de registro',
                         'value' => \Carbon\Carbon::parse($estudiante->created_at)->format('d/m/Y H:i')],
                        ['icon' => 'ri-refresh-line',         'color' => 'warning',   'label' => 'Última actualización',
                         'value' => \Carbon\Carbon::parse($estudiante->updated_at)->format('d/m/Y H:i')],
                        ['icon' => 'ri-hashtag',              'color' => 'dark',       'label' => 'ID Estudiante',
                         'value' => '#' . $estudiante->id],
                        ['icon' => 'ri-fingerprint-line',     'color' => 'secondary', 'label' => 'ID Persona',
                         'value' => '#' . $persona->id],
                    ];
                @endphp
                @foreach ($camposRegistro as $i => $campo)
                    <div class="d-flex align-items-center gap-3 px-3 py-3 {{ $i < count($camposRegistro) - 1 ? 'border-bottom' : '' }}">
                        <div class="avatar-xs flex-shrink-0">
                            <div class="avatar-title bg-{{ $campo['color'] }}-subtle text-{{ $campo['color'] }} rounded">
                                <i class="{{ $campo['icon'] }} fs-14"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted" style="font-size:.72rem;">{{ strtoupper($campo['label']) }}</div>
                            <div class="fw-medium small">{{ $campo['value'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
