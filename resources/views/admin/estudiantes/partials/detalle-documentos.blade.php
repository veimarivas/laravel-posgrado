<div class="row">
    <div class="col-lg-6">
        <!-- Documentos Personales -->
        <div class="card border">
            <div class="card-header border-bottom bg-light">
                <h5 class="card-title mb-0 fs-16">Documentos Personales</h5>
            </div>
            <div class="card-body">
                <!-- Carnet de Identidad -->
                <div class="documento-item mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Carnet de Identidad</h6>
                        <div>
                            @if ($estudiante->persona->fotografia_carnet)
                                @if ($estudiante->persona->carnet_verificado == 1)
                                    <span class="badge bg-success">Verificado</span>
                                @else
                                    <span class="badge bg-warning">Pendiente
                                        Verificación</span>
                                @endif
                            @else
                                <span class="badge bg-danger">No Subido</span>
                            @endif
                        </div>
                    </div>

                    @if ($estudiante->persona->fotografia_carnet)
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="border rounded p-3 text-center bg-light">
                                    <i class="ri-file-pdf-line display-4 text-danger mb-2"></i>
                                    <p class="mb-1">Carnet.pdf</p>
                                    <small class="text-muted">
                                        Subido:
                                        {{ \Carbon\Carbon::parse($estudiante->persona->updated_at)->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-outline-primary preview-documento"
                                        data-url="{{ asset('storage/' . $estudiante->persona->fotografia_carnet) }}">
                                        <i class="ri-eye-line me-1"></i> Previsualizar
                                    </button>
                                    <a href="{{ asset('storage/' . $estudiante->persona->fotografia_carnet) }}"
                                        class="btn btn-outline-success" download>
                                        <i class="ri-download-line me-1"></i> Descargar
                                    </a>
                                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalSubirCarnet">
                                        <i class="ri-upload-line me-1"></i> Reemplazar
                                    </button>
                                    @if ($estudiante->persona->carnet_verificado == 0)
                                        <button type="button" class="btn btn-outline-danger btn-verificar-carnet"
                                            data-id="{{ $estudiante->id }}" data-tipo="carnet">
                                            <i class="ri-check-line me-1"></i> Verificar
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-outline-secondary btn-verificar-carnet"
                                            data-id="{{ $estudiante->id }}" data-tipo="carnet">
                                            <i class="ri-close-line me-1"></i> No Verificar
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="ri-alert-line me-1"></i> El documento de carnet
                                    no ha sido subido.
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalSubirCarnet">
                                    <i class="ri-upload-line me-1"></i> Subir Carnet
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Certificado de Nacimiento -->
                <div class="documento-item">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Certificado de Nacimiento</h6>
                        <div>
                            @if ($estudiante->persona->fotografia_certificado_nacimiento)
                                @if ($estudiante->persona->certificado_nacimiento_verificado == 1)
                                    <span class="badge bg-success">Verificado</span>
                                @else
                                    <span class="badge bg-warning">Pendiente
                                        Verificación</span>
                                @endif
                            @else
                                <span class="badge bg-danger">No Subido</span>
                            @endif
                        </div>
                    </div>

                    @if ($estudiante->persona->fotografia_certificado_nacimiento)
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="border rounded p-3 text-center bg-light">
                                    <i class="ri-file-pdf-line display-4 text-danger mb-2"></i>
                                    <p class="mb-1">Certificado_Nacimiento.pdf</p>
                                    <small class="text-muted">
                                        Subido:
                                        {{ \Carbon\Carbon::parse($estudiante->persona->updated_at)->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-outline-primary preview-documento"
                                        data-url="{{ asset('storage/' . $estudiante->persona->fotografia_certificado_nacimiento) }}">
                                        <i class="ri-eye-line me-1"></i> Previsualizar
                                    </button>
                                    <a href="{{ asset('storage/' . $estudiante->persona->fotografia_certificado_nacimiento) }}"
                                        class="btn btn-outline-success" download>
                                        <i class="ri-download-line me-1"></i> Descargar
                                    </a>
                                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalSubirCertificadoNacimiento">
                                        <i class="ri-upload-line me-1"></i> Reemplazar
                                    </button>
                                    @if ($estudiante->persona->certificado_nacimiento_verificado == 0)
                                        <button type="button" class="btn btn-outline-danger btn-verificar-documento"
                                            data-id="{{ $estudiante->id }}" data-tipo="certificado_nacimiento">
                                            <i class="ri-check-line me-1"></i> Verificar
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-outline-secondary btn-verificar-documento"
                                            data-id="{{ $estudiante->id }}" data-tipo="certificado_nacimiento">
                                            <i class="ri-close-line me-1"></i> No Verificar
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="ri-alert-line me-1"></i> El certificado de
                                    nacimiento no ha sido subido.
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalSubirCertificadoNacimiento">
                                    <i class="ri-upload-line me-1"></i> Subir Certificado
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <!-- Documentos Académicos -->
        @php
            $estudioPrincipal = $estudiante->persona->estudios->where('principal', 1)->first();
        @endphp

        @if ($estudioPrincipal)
            <div class="card border">
                <div class="card-header border-bottom bg-light">
                    <h5 class="card-title mb-0 fs-16">Documentos Académicos (Estudio Principal)
                    </h5>
                    <small class="text-muted">{{ $estudioPrincipal->grado_academico->nombre ?? '' }}
                        - {{ $estudioPrincipal->profesion->nombre ?? '' }}</small>
                </div>
                <div class="card-body">
                    <!-- Documento Académico -->
                    <div class="documento-item mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Documento Académico</h6>
                            <div>
                                @if ($estudioPrincipal->documento_academico)
                                    @if ($estudioPrincipal->documento_academico_verificado == 1)
                                        <span class="badge bg-success">Verificado</span>
                                    @else
                                        <span class="badge bg-warning">Pendiente
                                            Verificación</span>
                                    @endif
                                @else
                                    <span class="badge bg-danger">No Subido</span>
                                @endif
                            </div>
                        </div>

                        @if ($estudioPrincipal->documento_academico)
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="border rounded p-3 text-center bg-light">
                                        <i class="ri-file-pdf-line display-4 text-danger mb-2"></i>
                                        <p class="mb-1">Documento_Academico.pdf</p>
                                        <small class="text-muted">
                                            Subido:
                                            {{ \Carbon\Carbon::parse($estudioPrincipal->updated_at)->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-outline-primary preview-documento"
                                            data-url="{{ asset('storage/' . $estudioPrincipal->documento_academico) }}">
                                            <i class="ri-eye-line me-1"></i> Previsualizar
                                        </button>
                                        <a href="{{ asset('storage/' . $estudioPrincipal->documento_academico) }}"
                                            class="btn btn-outline-success" download>
                                            <i class="ri-download-line me-1"></i> Descargar
                                        </a>
                                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                                            data-bs-target="#modalSubirTituloAcademico">
                                            <i class="ri-upload-line me-1"></i> Reemplazar
                                        </button>
                                        @if ($estudioPrincipal->documento_academico_verificado == 0)
                                            <button type="button"
                                                class="btn btn-outline-danger btn-verificar-documento"
                                                data-id="{{ $estudiante->id }}" data-tipo="documento_academico">
                                                <i class="ri-check-line me-1"></i> Verificar
                                            </button>
                                        @else
                                            <button type="button"
                                                class="btn btn-outline-secondary btn-verificar-documento"
                                                data-id="{{ $estudiante->id }}" data-tipo="documento_academico">
                                                <i class="ri-close-line me-1"></i> No Verificar
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="ri-alert-line me-1"></i> El documento
                                        académico no ha sido subido.
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalSubirTituloAcademico">
                                        <i class="ri-upload-line me-1"></i> Subir Documento
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Provisión Nacional -->
                    <div class="documento-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Provisión Nacional</h6>
                            <div>
                                @if ($estudioPrincipal->documento_provision_nacional)
                                    @if ($estudioPrincipal->documento_provision_verificado == 1)
                                        <span class="badge bg-success">Verificado</span>
                                    @else
                                        <span class="badge bg-warning">Pendiente
                                            Verificación</span>
                                    @endif
                                @else
                                    <span class="badge bg-danger">No Subido</span>
                                @endif
                            </div>
                        </div>

                        @if ($estudioPrincipal->documento_provision_nacional)
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="border rounded p-3 text-center bg-light">
                                        <i class="ri-file-pdf-line display-4 text-danger mb-2"></i>
                                        <p class="mb-1">Provision_Nacional.pdf</p>
                                        <small class="text-muted">
                                            Subido:
                                            {{ \Carbon\Carbon::parse($estudioPrincipal->updated_at)->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-outline-primary preview-documento"
                                            data-url="{{ asset('storage/' . $estudioPrincipal->documento_provision_nacional) }}">
                                            <i class="ri-eye-line me-1"></i> Previsualizar
                                        </button>
                                        <a href="{{ asset('storage/' . $estudioPrincipal->documento_provision_nacional) }}"
                                            class="btn btn-outline-success" download>
                                            <i class="ri-download-line me-1"></i> Descargar
                                        </a>
                                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                                            data-bs-target="#modalSubirProvisionNacional">
                                            <i class="ri-upload-line me-1"></i> Reemplazar
                                        </button>
                                        @if ($estudioPrincipal->documento_provision_verificado == 0)
                                            <button type="button"
                                                class="btn btn-outline-danger btn-verificar-documento"
                                                data-id="{{ $estudiante->id }}" data-tipo="provision_nacional">
                                                <i class="ri-check-line me-1"></i> Verificar
                                            </button>
                                        @else
                                            <button type="button"
                                                class="btn btn-outline-secondary btn-verificar-documento"
                                                data-id="{{ $estudiante->id }}" data-tipo="provision_nacional">
                                                <i class="ri-close-line me-1"></i> No Verificar
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="ri-alert-line me-1"></i> El documento de
                                        provisión nacional no ha sido subido.
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalSubirProvisionNacional">
                                        <i class="ri-upload-line me-1"></i> Subir Documento
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="card border">
                <div class="card-body text-center py-5">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-light text-secondary rounded-circle">
                            <i class="ri-file-text-line fs-2"></i>
                        </div>
                    </div>
                    <h5 class="mb-2">No hay estudio principal</h5>
                    <p class="text-muted mb-0">El estudiante no tiene un estudio marcado como
                        principal</p>
                    <a href="{{ route('admin.estudiantes.editar', $estudiante->id) }}" class="btn btn-primary mt-3">
                        <i class="ri-edit-line me-1"></i> Editar Estudios
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
