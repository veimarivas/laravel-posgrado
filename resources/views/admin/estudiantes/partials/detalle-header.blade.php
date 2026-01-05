<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h2 class="card-title mb-1">{{ $estudiante->persona->nombres ?? '' }}
                            {{ $estudiante->persona->apellido_paterno ?? '' }}</h2>
                        <p class="text-muted mb-0">
                            <i class="ri-id-card-line align-middle me-1"></i>
                            {{ $estudiante->persona->carnet ?? 'Sin carnet' }} |
                            <i class="ri-mail-line align-middle me-1 ms-2"></i>
                            {{ $estudiante->persona->correo ?? 'Sin correo' }}
                        </p>
                    </div>
                    <div class="avatar-xxl position-relative">
                        @if ($estudiante->persona->fotografia)
                            <img src="{{ asset('storage/' . $estudiante->persona->fotografia) }}"
                                class="avatar-title bg-primary-subtle text-primary rounded-circle"
                                style="object-fit: cover; width: 100%; height: 100%;">
                        @else
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <span class="fs-2">{{ substr($estudiante->persona->nombres ?? 'E', 0, 1) }}</span>
                            </div>
                        @endif

                        <!-- Badge de estado general de documentos -->
                        @php
                            $documentosCompletos = 0;
                            $documentosTotales = 0;
                            $documentosPendientes = [];

                            // Verificar documentos de persona
                            if ($estudiante->persona->fotografia_carnet) {
                                $documentosTotales++;
                                if ($estudiante->persona->carnet_verificado == 1) {
                                    $documentosCompletos++;
                                } else {
                                    $documentosPendientes[] = 'Carnet';
                                }
                            }

                            if ($estudiante->persona->fotografia_certificado_nacimiento) {
                                $documentosTotales++;
                                if ($estudiante->persona->certificado_nacimiento_verificado == 1) {
                                    $documentosCompletos++;
                                } else {
                                    $documentosPendientes[] = 'Certificado Nacimiento';
                                }
                            }

                            // Verificar documentos de estudios principales
                            $estudioPrincipal = $estudiante->persona->estudios->where('principal', 1)->first();
                            if ($estudioPrincipal) {
                                if ($estudioPrincipal->documento_academico) {
                                    $documentosTotales++;
                                    if ($estudioPrincipal->documento_academico_verificado == 1) {
                                        $documentosCompletos++;
                                    } else {
                                        $documentosPendientes[] = 'Documento Académico';
                                    }
                                }

                                if ($estudioPrincipal->documento_provision_nacional) {
                                    $documentosTotales++;
                                    if ($estudioPrincipal->documento_provision_verificado == 1) {
                                        $documentosCompletos++;
                                    } else {
                                        $documentosPendientes[] = 'Prov. Nacional';
                                    }
                                }
                            }

                            $porcentajeDocumentos =
                                $documentosTotales > 0 ? ($documentosCompletos / $documentosTotales) * 100 : 0;
                            $colorEstado = match (true) {
                                $porcentajeDocumentos == 100 => 'success',
                                $porcentajeDocumentos >= 50 => 'warning',
                                default => 'danger',
                            };
                        @endphp

                        <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-{{ $colorEstado }}">
                            {{ $documentosCompletos }}/{{ $documentosTotales }}
                            <span class="visually-hidden">documentos verificados</span>
                        </span>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="border rounded p-3 text-center">
                            <h4 class="text-primary mb-1">{{ $estudiante->inscripciones->count() }}</h4>
                            <p class="text-muted mb-0 fs-12">Programas</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="border rounded p-3 text-center">
                            <h4 class="text-success mb-1">
                                {{ $estudiante->inscripciones->where('estado', 'Inscrito')->count() }}
                            </h4>
                            <p class="text-muted mb-0 fs-12">Activos</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="border rounded p-3 text-center">
                            <h4 class="text-info mb-1">
                                {{ $estudiante->persona->estudios->count() }}
                            </h4>
                            <p class="text-muted mb-0 fs-12">Estudios</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="border rounded p-3 text-center">
                            <h4 class="text-warning mb-1">
                                @php
                                    $totalDeuda = 0;
                                    foreach ($estudiante->inscripciones as $inscripcion) {
                                        $totalDeuda += $inscripcion->cuotas->sum('pago_pendiente_bs');
                                    }
                                @endphp
                                {{ number_format($totalDeuda, 2) }} Bs
                            </h4>
                            <p class="text-muted mb-0 fs-12">Deuda Total</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="border rounded p-3 text-center">
                            <h4 class="text-danger mb-1">
                                @php
                                    $edad = $estudiante->persona->fecha_nacimiento
                                        ? \Carbon\Carbon::parse($estudiante->persona->fecha_nacimiento)->age
                                        : '--';
                                @endphp
                                {{ $edad }}
                            </h4>
                            <p class="text-muted mb-0 fs-12">Edad</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="border rounded p-3 text-center">
                            <h4 class="text-purple mb-1">
                                {{ $estudiante->persona->celular ?? '--' }}
                            </h4>
                            <p class="text-muted mb-0 fs-12">Celular</p>
                        </div>
                    </div>
                </div>

                <!-- Progreso de documentos -->
                @if ($documentosTotales > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Progreso de Documentación</h6>
                                <span class="badge bg-{{ $colorEstado }}">
                                    {{ number_format($porcentajeDocumentos, 0) }}% Completado
                                </span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $colorEstado }}" role="progressbar"
                                    style="width: {{ $porcentajeDocumentos }}%">
                                </div>
                            </div>
                            @if (count($documentosPendientes) > 0)
                                <small class="text-muted mt-1 d-block">
                                    Pendientes: {{ implode(', ', $documentosPendientes) }}
                                </small>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
