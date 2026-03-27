@php
    $persona = auth()->user()->persona;

    $camposPersonal = [
        ['icon'=>'ri-user-3-line',      'color'=>'primary',   'label'=>'Nombre completo',
         'value'=> trim(($persona->nombres ?? '').' '.($persona->apellido_paterno ?? '').' '.($persona->apellido_materno ?? '')) ?: '—'],
        ['icon'=>'ri-id-card-line',     'color'=>'secondary', 'label'=>'Carnet de identidad',
         'value'=> ($persona->carnet ?? '—') . ($persona->expedido ? ' ('.$persona->expedido.')' : '')],
        ['icon'=>'ri-genderless-line',  'color'=>'info',      'label'=>'Sexo',
         'value'=> $persona->sexo ?? '—'],
        ['icon'=>'ri-heart-line',       'color'=>'danger',    'label'=>'Estado civil',
         'value'=> $persona->estado_civil ?? '—'],
        ['icon'=>'ri-cake-line',        'color'=>'warning',   'label'=>'Fecha de nacimiento',
         'value'=> $persona->fecha_nacimiento
             ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y')
               .' ('.\Carbon\Carbon::parse($persona->fecha_nacimiento)->age.' años)'
             : '—'],
    ];

    $camposContacto = [
        ['icon'=>'ri-mail-line',       'color'=>'primary',  'label'=>'Correo electrónico',
         'value'=> $persona->correo    ?? '—'],
        ['icon'=>'ri-smartphone-line', 'color'=>'success',  'label'=>'Celular',
         'value'=> $persona->celular   ?? '—'],
        ['icon'=>'ri-phone-line',      'color'=>'info',     'label'=>'Teléfono',
         'value'=> $persona->telefono  ?? '—'],
        ['icon'=>'ri-home-2-line',     'color'=>'warning',  'label'=>'Dirección',
         'value'=> $persona->direccion ?? '—'],
        ['icon'=>'ri-map-pin-2-line',  'color'=>'danger',   'label'=>'Ciudad / Departamento',
         'value'=> optional($persona->ciudad)->nombre
             ? optional($persona->ciudad)->nombre.', '.(optional(optional($persona->ciudad)->departamento)->nombre ?? '')
             : '—'],
    ];
@endphp

<div class="row g-4">

    {{-- Datos personales --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2 py-3">
                <div class="avatar-xs">
                    <div class="avatar-title bg-primary-subtle text-primary rounded">
                        <i class="ri-user-line fs-14"></i>
                    </div>
                </div>
                <h6 class="mb-0 fw-semibold">Datos Personales</h6>
            </div>
            <div class="card-body p-0">
                @foreach ($camposPersonal as $i => $campo)
                    <div class="d-flex align-items-center gap-3 px-3 py-3 {{ $i < count($camposPersonal)-1 ? 'border-bottom' : '' }}">
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

    {{-- Contacto --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2 py-3">
                <div class="avatar-xs">
                    <div class="avatar-title bg-info-subtle text-info rounded">
                        <i class="ri-contacts-line fs-14"></i>
                    </div>
                </div>
                <h6 class="mb-0 fw-semibold">Información de Contacto</h6>
            </div>
            <div class="card-body p-0">
                @foreach ($camposContacto as $i => $campo)
                    <div class="d-flex align-items-center gap-3 px-3 py-3 {{ $i < count($camposContacto)-1 ? 'border-bottom' : '' }}">
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
