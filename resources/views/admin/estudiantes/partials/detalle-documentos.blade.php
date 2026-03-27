@php
    $persona          = $estudiante->persona;
    $estudioPrincipal = $persona->estudios->where('principal', 1)->first();

    // Helper para calcular estado de un documento
    $estadoDoc = function ($archivo, $verificado) {
        if (!$archivo)     return ['label' => 'No subido',  'cls' => 'danger',  'icon' => 'ri-close-circle-line'];
        if ($verificado)   return ['label' => 'Verificado', 'cls' => 'success', 'icon' => 'ri-checkbox-circle-line'];
        return              ['label' => 'Sin verificar','cls' => 'warning', 'icon' => 'ri-time-line'];
    };

    $docs = [
        // Documentos personales
        [
            'grupo'      => 'Documentos Personales',
            'nombre'     => 'Carnet de Identidad',
            'icono'      => 'ri-id-card-line',
            'archivo'    => $persona->fotografia_carnet,
            'verificado' => $persona->carnet_verificado,
            'filename'   => 'carnet.pdf',
            'fecha'      => $persona->updated_at,
            'modal_up'   => 'modalSubirCarnet',
            'btn_class'  => 'btn-verificar-carnet',
            'data_tipo'  => 'carnet',
            'url'        => $persona->fotografia_carnet ? asset('storage/' . $persona->fotografia_carnet) : null,
        ],
        [
            'grupo'      => 'Documentos Personales',
            'nombre'     => 'Certificado de Nacimiento',
            'icono'      => 'ri-file-paper-line',
            'archivo'    => $persona->fotografia_certificado_nacimiento,
            'verificado' => $persona->certificado_nacimiento_verificado,
            'filename'   => 'certificado_nacimiento.pdf',
            'fecha'      => $persona->updated_at,
            'modal_up'   => 'modalSubirCertificadoNacimiento',
            'btn_class'  => 'btn-verificar-documento',
            'data_tipo'  => 'certificado_nacimiento',
            'url'        => $persona->fotografia_certificado_nacimiento ? asset('storage/' . $persona->fotografia_certificado_nacimiento) : null,
        ],
        // Documentos académicos
        [
            'grupo'      => 'Documentos Académicos',
            'nombre'     => 'Documento Académico',
            'icono'      => 'ri-graduation-cap-line',
            'archivo'    => $estudioPrincipal?->documento_academico,
            'verificado' => $estudioPrincipal?->documento_academico_verificado,
            'filename'   => 'documento_academico.pdf',
            'fecha'      => $estudioPrincipal?->updated_at,
            'modal_up'   => 'modalSubirTituloAcademico',
            'btn_class'  => 'btn-verificar-documento',
            'data_tipo'  => 'documento_academico',
            'url'        => $estudioPrincipal?->documento_academico ? asset('storage/' . $estudioPrincipal->documento_academico) : null,
            'sin_estudio'=> !$estudioPrincipal,
        ],
        [
            'grupo'      => 'Documentos Académicos',
            'nombre'     => 'Provisión Nacional',
            'icono'      => 'ri-government-line',
            'archivo'    => $estudioPrincipal?->documento_provision_nacional,
            'verificado' => $estudioPrincipal?->documento_provision_verificado,
            'filename'   => 'provision_nacional.pdf',
            'fecha'      => $estudioPrincipal?->updated_at,
            'modal_up'   => 'modalSubirProvisionNacional',
            'btn_class'  => 'btn-verificar-documento',
            'data_tipo'  => 'provision_nacional',
            'url'        => $estudioPrincipal?->documento_provision_nacional ? asset('storage/' . $estudioPrincipal->documento_provision_nacional) : null,
            'sin_estudio'=> !$estudioPrincipal,
        ],
    ];
@endphp

{{-- Resumen de estado de documentos --}}
@php
    $totalDocs    = 0;
    $verificados  = 0;
    $sinVerificar = 0;
    $noSubidos    = 0;
    foreach ($docs as $d) {
        if ($d['sin_estudio'] ?? false) continue;
        $totalDocs++;
        if (!$d['archivo'])      { $noSubidos++; }
        elseif ($d['verificado']) { $verificados++; }
        else                     { $sinVerificar++; }
    }
    $pctDocs  = $totalDocs > 0 ? ($verificados / $totalDocs) * 100 : 0;
    $colorBar = match(true) {
        $pctDocs == 100 => 'success',
        $pctDocs >= 50  => 'warning',
        default         => 'danger',
    };
@endphp

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="rounded-3 border p-3 text-center">
            <div class="fw-bold fs-4 text-primary mb-0">{{ $totalDocs }}</div>
            <div class="text-muted" style="font-size:.72rem;">Total</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="rounded-3 border p-3 text-center" style="border-color:#198754!important;background:#f0fff5;">
            <div class="fw-bold fs-4 text-success mb-0">{{ $verificados }}</div>
            <div class="text-muted" style="font-size:.72rem;">Verificados</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="rounded-3 border p-3 text-center" style="border-color:#ffc107!important;background:#fffdf0;">
            <div class="fw-bold fs-4 text-warning mb-0">{{ $sinVerificar }}</div>
            <div class="text-muted" style="font-size:.72rem;">Sin verificar</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="rounded-3 border p-3 text-center" style="border-color:#dc3545!important;background:#fff5f5;">
            <div class="fw-bold fs-4 text-danger mb-0">{{ $noSubidos }}</div>
            <div class="text-muted" style="font-size:.72rem;">No subidos</div>
        </div>
    </div>
</div>

{{-- Barra de progreso global --}}
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-1">
        <span class="text-muted small">Progreso de documentación</span>
        <span class="badge bg-{{ $colorBar }} rounded-pill">{{ number_format($pctDocs, 0) }}% completado</span>
    </div>
    <div class="progress rounded-pill" style="height:8px;">
        <div class="progress-bar bg-{{ $colorBar }}" style="width:{{ $pctDocs }}%"></div>
    </div>
</div>

{{-- Grid de documentos --}}
<div class="row g-3">
    @foreach ($docs as $doc)
        @php
            $sinEstudio = $doc['sin_estudio'] ?? false;
            $estado     = $sinEstudio ? ['label' => 'Sin estudio', 'cls' => 'secondary', 'icon' => 'ri-question-line']
                                      : $estadoDoc($doc['archivo'], $doc['verificado']);
            $colorLeft  = match($estado['cls']) {
                'success'   => '#198754',
                'warning'   => '#ffc107',
                'danger'    => '#dc3545',
                default     => '#6c757d',
            };
        @endphp

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden"
                 style="border-left: 4px solid {{ $colorLeft }} !important;">
                <div class="card-body p-0">

                    {{-- Header del documento --}}
                    <div class="d-flex align-items-center gap-3 px-3 py-3 border-bottom"
                         style="background:#fafafa;">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-{{ $estado['cls'] }}-subtle text-{{ $estado['cls'] }} rounded-2">
                                <i class="{{ $doc['icono'] }} fs-18"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold small">{{ $doc['nombre'] }}</div>
                            <div class="text-muted" style="font-size:.7rem;">{{ $doc['grupo'] }}</div>
                        </div>
                        <span class="badge bg-{{ $estado['cls'] }}-subtle text-{{ $estado['cls'] }} border border-{{ $estado['cls'] }}-subtle rounded-pill px-2 flex-shrink-0" style="font-size:.7rem;">
                            <i class="{{ $estado['icon'] }} me-1"></i>{{ $estado['label'] }}
                        </span>
                    </div>

                    {{-- Cuerpo --}}
                    <div class="px-3 py-3">
                        @if ($sinEstudio)
                            <div class="text-center py-2">
                                <i class="ri-error-warning-line text-secondary fs-2 mb-2 d-block"></i>
                                <p class="text-muted small mb-2">Sin estudio principal registrado</p>
                                <a href="{{ route('admin.estudiantes.editar', $estudiante->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="ri-edit-line me-1"></i>Registrar estudio
                                </a>
                            </div>

                        @elseif ($doc['archivo'])
                            {{-- Archivo subido --}}
                            <div class="d-flex align-items-center gap-3 p-2 rounded-2 border mb-3"
                                 style="background:#f8f9fa;">
                                <div class="flex-shrink-0">
                                    <i class="ri-file-pdf-line fs-2 text-danger"></i>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-medium small text-truncate">{{ $doc['filename'] }}</div>
                                    <div class="text-muted" style="font-size:.72rem;">
                                        <i class="ri-calendar-line me-1"></i>
                                        Subido: {{ $doc['fecha'] ? \Carbon\Carbon::parse($doc['fecha'])->format('d/m/Y H:i') : '—' }}
                                    </div>
                                </div>
                                @if ($doc['verificado'])
                                    <i class="ri-shield-check-line text-success fs-4 flex-shrink-0"></i>
                                @else
                                    <i class="ri-shield-line text-warning fs-4 flex-shrink-0"></i>
                                @endif
                            </div>

                            {{-- Acciones --}}
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button"
                                        class="btn btn-sm btn-outline-primary preview-documento"
                                        data-url="{{ $doc['url'] }}">
                                    <i class="ri-eye-line me-1"></i>Ver
                                </button>
                                <a href="{{ $doc['url'] }}"
                                   class="btn btn-sm btn-outline-success"
                                   download>
                                    <i class="ri-download-line me-1"></i>Descargar
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-outline-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#{{ $doc['modal_up'] }}">
                                    <i class="ri-upload-line me-1"></i>Reemplazar
                                </button>
                                @if (!$doc['verificado'])
                                    <button type="button"
                                            class="btn btn-sm btn-success {{ $doc['btn_class'] }}"
                                            data-id="{{ $estudiante->id }}"
                                            data-tipo="{{ $doc['data_tipo'] }}">
                                        <i class="ri-check-line me-1"></i>Verificar
                                    </button>
                                @else
                                    <button type="button"
                                            class="btn btn-sm btn-outline-secondary {{ $doc['btn_class'] }}"
                                            data-id="{{ $estudiante->id }}"
                                            data-tipo="{{ $doc['data_tipo'] }}">
                                        <i class="ri-close-line me-1"></i>Quitar verificación
                                    </button>
                                @endif
                            </div>

                        @else
                            {{-- No subido --}}
                            <div class="text-center py-2">
                                <div class="rounded-2 border border-dashed p-3 mb-3"
                                     style="border-style:dashed!important; background:#fff9f9;">
                                    <i class="ri-upload-cloud-2-line text-danger fs-2 mb-1 d-block"></i>
                                    <p class="text-muted small mb-0">Documento no subido aún</p>
                                </div>
                                <button type="button"
                                        class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#{{ $doc['modal_up'] }}">
                                    <i class="ri-upload-line me-1"></i>Subir documento
                                </button>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    @endforeach
</div>
