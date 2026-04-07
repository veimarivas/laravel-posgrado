@php
    $persona = auth()->user()->persona;
    $fotoUrl = $persona->fotografia
        ? asset($persona->fotografia)
        : asset('backend/assets/images/users/user-dummy-img.jpg');

    $cargoPrincipal = $persona->trabajador
        ?->trabajadores_cargos->where('principal', 1)->where('estado', 'Vigente')->first()
        ?->cargo->nombre ?? 'Sin cargo asignado';

    $tieneMarketing   = false;
    $cargosMarketingIds = [];

    if ($persona->trabajador) {
        $cargosMarketingIds = $persona->trabajador->trabajadores_cargos
            ->whereIn('cargo_id', [2, 3, 6])
            ->where('estado', 'Vigente')
            ->pluck('id');
        $tieneMarketing = $cargosMarketingIds->count() > 0;
    }
@endphp

<div class="col-xl-3 col-lg-3">

    {{-- Tarjeta de perfil --}}
    <div class="profile-sidebar-card mb-3">

        {{-- Banner superior --}}
        <div class="profile-sidebar-banner"></div>

        <div class="profile-sidebar-body">

            {{-- Avatar --}}
            <div class="profile-avatar-wrapper">
                <img id="profileAvatar"
                     src="{{ $fotoUrl }}"
                     alt="Avatar"
                     class="profile-avatar"
                     onerror="this.src='{{ asset('backend/assets/images/users/user-dummy-img.jpg') }}'">
                <button class="profile-avatar-btn"
                        data-bs-toggle="modal" data-bs-target="#uploadFotoModal"
                        title="Cambiar foto">
                    <i class="ri-camera-line"></i>
                </button>
            </div>

            {{-- Nombre --}}
            <h5 id="profileName" class="profile-name">
                {{ $persona->nombres ?? 'Usuario' }} {{ $persona->apellido_paterno ?? '' }}
            </h5>
            <p id="profileCargo" class="profile-cargo">
                {{ $cargoPrincipal }}
            </p>
            <span class="profile-role-badge">
                <i class="ri-shield-user-line"></i>{{ auth()->user()->roles->first()->name ?? 'Usuario' }}
            </span>

            {{-- Mini badges --}}
            <div class="profile-mini-badges">
                <span class="profile-mini-badge">
                    <i class="ri-id-card-line"></i>{{ $persona->carnet ?? 'Sin CI' }}
                </span>
                @if($persona->sexo)
                    <span class="profile-mini-badge">
                        <i class="ri-genderless-line"></i>{{ $persona->sexo }}
                    </span>
                @endif
                @if($persona->fecha_nacimiento)
                    <span class="profile-mini-badge">
                        <i class="ri-cake-line"></i>{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->age }} años
                    </span>
                @endif
            </div>

            {{-- Contacto --}}
            <div class="profile-contact-section">
                @foreach([
                    ['icon'=>'ri-mail-line',    'color'=>'primary',   'label'=>'Correo',     'value'=> $persona->correo   ?? null],
                    ['icon'=>'ri-phone-line',   'color'=>'success',   'label'=>'Celular',    'value'=> $persona->celular  ?? null],
                    ['icon'=>'ri-map-pin-line', 'color'=>'info',      'label'=>'Ubicación',  'value'=> (optional($persona->ciudad)->nombre ?? null)
                        ? optional($persona->ciudad)->nombre . (optional(optional($persona->ciudad)->departamento)->nombre ? ', '.optional($persona->ciudad->departamento)->nombre : '')
                        : null],
                ] as $item)
                    <div class="profile-contact-item">
                        <div class="profile-contact-icon bg-{{ $item['color'] }}-subtle text-{{ $item['color'] }}">
                            <i class="{{ $item['icon'] }}"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="profile-contact-label">{{ $item['label'] }}</div>
                            <div class="profile-contact-value text-truncate">
                                {{ $item['value'] ?: '—' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Info rápida --}}
    <div class="quick-info-card">
        <div class="quick-info-header">
            <i class="ri-information-line"></i>
            <span>Información Rápida</span>
        </div>
        <div class="card-body p-0">
            @foreach([
                ['label'=>'Cargos activos',  'icon'=>'ri-briefcase-line',     'value'=> $persona->trabajador?->trabajadores_cargos->where('estado','Vigente')->count() ?? 0],
                ['label'=>'Estudios',         'icon'=>'ri-graduation-cap-line','value'=> $persona->estudios->count()],
                ['label'=>'Miembro desde',    'icon'=>'ri-calendar-check-line','value'=> auth()->user()->created_at->format('d/m/Y')],
            ] as $item)
                <div class="quick-info-item">
                    <div class="qi-left">
                        <i class="{{ $item['icon'] }}"></i>
                        <span class="qi-label">{{ $item['label'] }}</span>
                    </div>
                    <span class="qi-value">{{ $item['value'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

</div>
