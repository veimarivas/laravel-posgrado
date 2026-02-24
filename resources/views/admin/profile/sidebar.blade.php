<!-- Perfil Lateral -->
<div class="col-xl-3 col-lg-3">
    <div class="card profile-card mb-4">
        <div class="card-body text-center p-4">
            <!-- Sección del Avatar - Actualizada -->
            <div class="position-relative d-inline-block mb-3">
                @php
                    // Si hay una ruta guardada en la base de datos, usamos asset() para crear la URL completa
                    $fotoUrl = auth()->user()->persona->fotografia
                        ? asset(auth()->user()->persona->fotografia)
                        : asset('backend/assets/images/users/user-dummy-img.jpg');
                @endphp

                <img id="profileAvatar" src="{{ $fotoUrl }}" class="profile-avatar" alt="Avatar"
                    onerror="this.src='{{ asset('backend/assets/images/users/user-dummy-img.jpg') }}'">

                <div class="position-absolute bottom-0 end-0">
                    <!-- Botón para cambiar foto -->
                    <button class="btn btn-primary btn-sm rounded-circle shadow" data-bs-toggle="modal"
                        data-bs-target="#uploadFotoModal" data-bs-tooltip="tooltip" title="Cambiar foto">
                        <i class="ri-camera-line"></i>
                    </button>
                </div>
            </div>

            <h4 id="profileName" class="mb-2">
                {{ auth()->user()->persona->nombres ?? 'Usuario' }}
                {{ auth()->user()->persona->apellido_paterno ?? '' }}
            </h4>

            <p id="profileCargo" class="text-primary fw-medium mb-3">
                @php
                    $cargoPrincipal =
                        auth()
                            ->user()
                            ->persona->trabajador->trabajadores_cargos->where('principal', 1)
                            ->where('estado', 'Vigente')
                            ->first()->cargo->nombre ?? 'Sin cargo asignado';
                @endphp
                {{ $cargoPrincipal }}
            </p>

            <!-- Badges de información -->
            <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                <span class="badge bg-info-subtle text-info">
                    <i class="ri-id-card-line me-1"></i>
                    {{ auth()->user()->persona->carnet ?? 'Sin carnet' }}
                </span>
                <span class="badge bg-secondary-subtle text-secondary">
                    <i class="ri-genderless-line me-1"></i>
                    {{ auth()->user()->persona->sexo ?? '' }}
                </span>
                @if (auth()->user()->persona->fecha_nacimiento)
                    <span class="badge bg-warning-subtle text-warning">
                        <i class="ri-cake-line me-1"></i>
                        {{ \Carbon\Carbon::parse(auth()->user()->persona->fecha_nacimiento)->age }} años
                    </span>
                @endif
            </div>

            <!-- Información de contacto -->
            <div class="list-group list-group-flush">
                <div class="list-group-item border-0 px-0 py-2">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-mail-line text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <small class="text-muted d-block">Correo</small>
                            <span class="fw-medium">{{ auth()->user()->persona->correo ?? 'Sin correo' }}</span>
                        </div>
                    </div>
                </div>

                <div class="list-group-item border-0 px-0 py-2">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-phone-line text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <small class="text-muted d-block">Celular</small>
                            <span class="fw-medium">{{ auth()->user()->persona->celular ?? 'Sin celular' }}</span>
                        </div>
                    </div>
                </div>

                <div class="list-group-item border-0 px-0 py-2">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-map-pin-line text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <small class="text-muted d-block">Ubicación</small>
                            <span class="fw-medium">
                                {{ auth()->user()->persona->ciudad->nombre ?? '' }}
                                @if (auth()->user()->persona->ciudad && auth()->user()->persona->ciudad->departamento)
                                    ({{ auth()->user()->persona->ciudad->departamento->nombre }})
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mini Dashboard Marketing -->
    @php
        $tieneMarketing = false;
        $cargosMarketingIds = [];

        if (auth()->user()->persona->trabajador) {
            $cargosMarketingIds = auth()
                ->user()
                ->persona->trabajador->trabajadores_cargos->whereIn('cargo_id', [2, 3, 6])
                ->where('estado', 'Vigente')
                ->pluck('id');

            $tieneMarketing = $cargosMarketingIds->count() > 0;
        }
    @endphp

    <!-- Información rápida -->
    <div class="card">
        <div class="card-body">
            <h6 class="card-title mb-3">
                <i class="ri-information-line me-2"></i> Información Rápida
            </h6>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Cargos Activos:</span>
                <span class="fw-medium">
                    {{ auth()->user()->persona->trabajador->trabajadores_cargos->where('estado', 'Vigente')->count() ?? 0 }}
                </span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Estudios:</span>
                <span class="fw-medium">
                    {{ auth()->user()->persona->estudios->count() ?? 0 }}
                </span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Miembro desde:</span>
                <span class="fw-medium">
                    {{ auth()->user()->created_at->format('d/m/Y') }}
                </span>
            </div>
        </div>
    </div>
</div>
