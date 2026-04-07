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

<div class="row g-3">

    {{-- Datos personales --}}
    <div class="col-lg-6">
        <div class="data-card">
            <div class="data-card-header">
                <div class="data-card-icon bg-primary-subtle text-primary">
                    <i class="ri-user-line"></i>
                </div>
                <h6 class="data-card-title">Datos Personales</h6>
            </div>
            <div class="data-card-body">
                @foreach ($camposPersonal as $campo)
                    <div class="data-row">
                        <div class="data-row-icon bg-{{ $campo['color'] }}-subtle text-{{ $campo['color'] }}">
                            <i class="{{ $campo['icon'] }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="data-row-label">{{ $campo['label'] }}</div>
                            <div class="data-row-value">{{ $campo['value'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Contacto --}}
    <div class="col-lg-6">
        <div class="data-card">
            <div class="data-card-header">
                <div class="data-card-icon bg-info-subtle text-info">
                    <i class="ri-contacts-line"></i>
                </div>
                <h6 class="data-card-title">Información de Contacto</h6>
            </div>
            <div class="data-card-body">
                @foreach ($camposContacto as $campo)
                    <div class="data-row">
                        <div class="data-row-icon bg-{{ $campo['color'] }}-subtle text-{{ $campo['color'] }}">
                            <i class="{{ $campo['icon'] }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="data-row-label">{{ $campo['label'] }}</div>
                            <div class="data-row-value">{{ $campo['value'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
