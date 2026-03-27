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
    <div class="card border-0 shadow-sm mb-3 overflow-hidden">

        {{-- Banner superior --}}
        <div style="height:75px;background:linear-gradient(135deg,#0d6efd 0%,#6f42c1 100%);"></div>

        <div class="card-body text-center pt-0 pb-4 px-3">

            {{-- Avatar --}}
            <div class="position-relative d-inline-block" style="margin-top:-48px;">
                <img id="profileAvatar"
                     src="{{ $fotoUrl }}"
                     alt="Avatar"
                     class="rounded-circle shadow"
                     style="width:96px;height:96px;object-fit:cover;border:4px solid #fff;"
                     onerror="this.src='{{ asset('backend/assets/images/users/user-dummy-img.jpg') }}'">
                <button class="btn btn-primary btn-sm rounded-circle position-absolute shadow"
                        style="width:28px;height:28px;padding:0;bottom:2px;right:2px;"
                        data-bs-toggle="modal" data-bs-target="#uploadFotoModal"
                        title="Cambiar foto">
                    <i class="ri-camera-line" style="font-size:.7rem;"></i>
                </button>
            </div>

            {{-- Nombre --}}
            <h5 id="profileName" class="mb-0 mt-2 fw-semibold">
                {{ $persona->nombres ?? 'Usuario' }} {{ $persona->apellido_paterno ?? '' }}
            </h5>
            <p id="profileCargo" class="text-primary fw-medium mb-2" style="font-size:.85rem;">
                {{ $cargoPrincipal }}
            </p>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill mb-3" style="font-size:.72rem;">
                <i class="ri-shield-user-line me-1"></i>{{ auth()->user()->roles->first()->name ?? 'Usuario' }}
            </span>

            {{-- Mini badges --}}
            <div class="d-flex flex-wrap justify-content-center gap-1 mb-3">
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill" style="font-size:.7rem;">
                    <i class="ri-id-card-line me-1"></i>{{ $persona->carnet ?? 'Sin CI' }}
                </span>
                @if($persona->sexo)
                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill" style="font-size:.7rem;">
                        <i class="ri-genderless-line me-1"></i>{{ $persona->sexo }}
                    </span>
                @endif
                @if($persona->fecha_nacimiento)
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill" style="font-size:.7rem;">
                        <i class="ri-cake-line me-1"></i>{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->age }} años
                    </span>
                @endif
            </div>

            {{-- Contacto --}}
            <div class="border-top pt-3 text-start">
                @foreach([
                    ['icon'=>'ri-mail-line',    'color'=>'primary', 'label'=>'Correo',     'value'=> $persona->correo   ?? null],
                    ['icon'=>'ri-phone-line',   'color'=>'success', 'label'=>'Celular',    'value'=> $persona->celular  ?? null],
                    ['icon'=>'ri-map-pin-line', 'color'=>'info',    'label'=>'Ubicación',  'value'=> (optional($persona->ciudad)->nombre ?? null)
                        ? optional($persona->ciudad)->nombre . (optional(optional($persona->ciudad)->departamento)->nombre ? ', '.optional($persona->ciudad->departamento)->nombre : '')
                        : null],
                ] as $item)
                    <div class="d-flex align-items-center gap-2 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="avatar-xs flex-shrink-0">
                            <div class="avatar-title bg-{{ $item['color'] }}-subtle text-{{ $item['color'] }} rounded">
                                <i class="{{ $item['icon'] }} fs-14"></i>
                            </div>
                        </div>
                        <div class="min-w-0">
                            <div class="text-muted" style="font-size:.68rem;">{{ strtoupper($item['label']) }}</div>
                            <div class="fw-medium text-truncate" style="font-size:.82rem;">
                                {{ $item['value'] ?: '—' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Info rápida --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 py-2 px-3" style="background:#f8f9fa;">
            <span class="fw-semibold small"><i class="ri-information-line me-1 text-primary"></i>Información Rápida</span>
        </div>
        <div class="card-body p-0">
            @foreach([
                ['label'=>'Cargos activos',  'icon'=>'ri-briefcase-line',     'color'=>'primary',  'value'=> $persona->trabajador?->trabajadores_cargos->where('estado','Vigente')->count() ?? 0],
                ['label'=>'Estudios',         'icon'=>'ri-graduation-cap-line','color'=>'success',  'value'=> $persona->estudios->count()],
                ['label'=>'Miembro desde',    'icon'=>'ri-calendar-check-line','color'=>'secondary','value'=> auth()->user()->created_at->format('d/m/Y')],
            ] as $i => $item)
                <div class="d-flex align-items-center justify-content-between px-3 py-2 {{ $i < 2 ? 'border-bottom' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="{{ $item['icon'] }} text-{{ $item['color'] }}" style="font-size:.85rem;"></i>
                        <span class="text-muted small">{{ $item['label'] }}</span>
                    </div>
                    <span class="fw-semibold small">{{ $item['value'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

</div>
