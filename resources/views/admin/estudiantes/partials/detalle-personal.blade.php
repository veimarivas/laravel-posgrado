<div class="row">
    <div class="col-lg-6">
        <div class="card border">
            <div class="card-header border-bottom bg-light">
                <h5 class="card-title mb-0 fs-16">Datos Personales</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-medium" width="40%">Carnet de Identidad:</td>
                                <td class="text-end">
                                    <span class="badge bg-secondary">{{ $estudiante->persona->carnet ?? 'N/A' }}</span>
                                    <span class="ms-2">{{ $estudiante->persona->expedido ?? '' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Nombres Completos:</td>
                                <td class="text-end">{{ $estudiante->persona->nombres ?? '' }}
                                    {{ $estudiante->persona->apellido_paterno ?? '' }}
                                    {{ $estudiante->persona->apellido_materno ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Sexo:</td>
                                <td class="text-end">{{ $estudiante->persona->sexo ?? 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Estado Civil:</td>
                                <td class="text-end">
                                    {{ $estudiante->persona->estado_civil ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Fecha de Nacimiento:</td>
                                <td class="text-end">
                                    @if ($estudiante->persona->fecha_nacimiento)
                                        {{ \Carbon\Carbon::parse($estudiante->persona->fecha_nacimiento)->format('d/m/Y') }}
                                        ({{ \Carbon\Carbon::parse($estudiante->persona->fecha_nacimiento)->age }}
                                        años)
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Estudios Realizados -->
        @if ($estudiante->persona->estudios->count() > 0)
            <div class="card border mt-3">
                <div class="card-header border-bottom bg-light">
                    <h5 class="card-title mb-0 fs-16">Estudios Realizados</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Grado Académico</th>
                                    <th>Profesión</th>
                                    <th>Universidad</th>
                                    <th>Estado</th>
                                    <th>Principal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estudiante->persona->estudios as $estudio)
                                    <tr>
                                        <td>
                                            <div>
                                                {{ $estudio->grado_academico->nombre ?? 'N/A' }}
                                            </div>
                                            @if ($estudio->principal)
                                                <small class="text-success">Estudio
                                                    Principal</small>
                                            @endif
                                        </td>
                                        <td>{{ $estudio->profesion->nombre ?? 'N/A' }}</td>
                                        <td>
                                            <div>{{ $estudio->universidad->nombre ?? 'N/A' }}
                                            </div>
                                            <small class="text-muted">{{ $estudio->universidad->sigla ?? '' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $estudio->estado ?? 'Concluido' }}</span>
                                        </td>
                                        <td>
                                            @if ($estudio->principal)
                                                <span class="badge bg-primary">Sí</span>
                                            @else
                                                <span class="badge bg-light text-dark">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-6">
        <div class="card border">
            <div class="card-header border-bottom bg-light">
                <h5 class="card-title mb-0 fs-16">Información de Contacto</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-medium" width="40%">Correo Electrónico:</td>
                                <td class="text-end">
                                    <a href="mailto:{{ $estudiante->persona->correo }}" class="text-primary">
                                        {{ $estudiante->persona->correo ?? 'N/A' }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Celular:</td>
                                <td class="text-end">
                                    {{ $estudiante->persona->celular ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Teléfono:</td>
                                <td class="text-end">
                                    {{ $estudiante->persona->telefono ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Dirección:</td>
                                <td class="text-end">
                                    {{ $estudiante->persona->direccion ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Ubicación:</td>
                                <td class="text-end">
                                    {{ $estudiante->persona->ciudad->nombre ?? 'N/A' }},
                                    {{ $estudiante->persona->ciudad->departamento->nombre ?? '' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Información del Registro -->
        <div class="card border mt-3">
            <div class="card-header border-bottom bg-light">
                <h5 class="card-title mb-0 fs-16">Información del Registro</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-medium" width="50%">Fecha de Registro:</td>
                                <td class="text-end">
                                    {{ \Carbon\Carbon::parse($estudiante->created_at)->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Última Actualización:</td>
                                <td class="text-end">
                                    {{ \Carbon\Carbon::parse($estudiante->updated_at)->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">ID del Estudiante:</td>
                                <td class="text-end">
                                    <span class="badge bg-dark">{{ $estudiante->id }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">ID de Persona:</td>
                                <td class="text-end">
                                    <span class="badge bg-dark">{{ $estudiante->persona->id }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
