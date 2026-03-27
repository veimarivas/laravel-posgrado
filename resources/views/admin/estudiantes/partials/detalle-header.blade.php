@php
    $persona = $estudiante->persona;

    // Documentos
    $documentosCompletos = 0;
    $documentosTotales   = 0;
    $documentosPendientes = [];

    if ($persona->fotografia_carnet) {
        $documentosTotales++;
        if ($persona->carnet_verificado == 1) { $documentosCompletos++; }
        else { $documentosPendientes[] = 'Carnet'; }
    }
    if ($persona->fotografia_certificado_nacimiento) {
        $documentosTotales++;
        if ($persona->certificado_nacimiento_verificado == 1) { $documentosCompletos++; }
        else { $documentosPendientes[] = 'Cert. Nacimiento'; }
    }
    $estudioPrincipal = $persona->estudios->where('principal', 1)->first();
    if ($estudioPrincipal) {
        if ($estudioPrincipal->documento_academico) {
            $documentosTotales++;
            if ($estudioPrincipal->documento_academico_verificado == 1) { $documentosCompletos++; }
            else { $documentosPendientes[] = 'Doc. Académico'; }
        }
        if ($estudioPrincipal->documento_provision_nacional) {
            $documentosTotales++;
            if ($estudioPrincipal->documento_provision_verificado == 1) { $documentosCompletos++; }
            else { $documentosPendientes[] = 'Prov. Nacional'; }
        }
    }

    $porcentajeDocumentos = $documentosTotales > 0 ? ($documentosCompletos / $documentosTotales) * 100 : 0;
    $colorDoc = match(true) {
        $porcentajeDocumentos == 100 => 'success',
        $porcentajeDocumentos >= 50  => 'warning',
        default                      => 'danger',
    };

    // Financiero
    $totalDeuda   = 0;
    $totalPagado  = 0;
    foreach ($estudiante->inscripciones as $ins) {
        foreach ($ins->cuotas as $c) {
            $totalPagado += $c->pago_total_bs - $c->pago_pendiente_bs;
            $totalDeuda  += $c->pago_pendiente_bs;
        }
    }

    $edad = $persona->fecha_nacimiento
        ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->age
        : null;

    $inscritos = $estudiante->inscripciones->where('estado', 'Inscrito')->count();
@endphp

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="row align-items-start g-4">

            {{-- Avatar + info principal --}}
            <div class="col-md-7">
                <div class="d-flex align-items-start gap-3">

                    {{-- Avatar --}}
                    <div class="position-relative flex-shrink-0">
                        @if ($persona->fotografia)
                            <img src="{{ asset('storage/' . $persona->fotografia) }}"
                                 class="rounded-3 object-fit-cover"
                                 style="width:72px;height:72px;">
                        @else
                            <div class="rounded-3 bg-primary d-flex align-items-center justify-content-center fw-bold text-white"
                                 style="width:72px;height:72px;font-size:1.8rem;">
                                {{ strtoupper(mb_substr($persona->nombres ?? 'E', 0, 1, 'UTF-8')) }}
                            </div>
                        @endif
                        {{-- Badge documentos --}}
                        @if ($documentosTotales > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-{{ $colorDoc }}"
                                  style="font-size:.65rem;"
                                  title="{{ $documentosCompletos }}/{{ $documentosTotales }} docs verificados">
                                {{ $documentosCompletos }}/{{ $documentosTotales }}
                            </span>
                        @endif
                    </div>

                    {{-- Nombre y badges --}}
                    <div class="flex-grow-1">
                        <h4 class="mb-1 fw-semibold">
                            {{ $persona->nombres }}
                            {{ $persona->apellido_paterno }}
                            {{ $persona->apellido_materno }}
                        </h4>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <span class="badge bg-secondary rounded-pill">
                                <i class="ri-id-card-line me-1"></i>{{ $persona->carnet ?? 'Sin carnet' }}
                                @if($persona->expedido) ({{ $persona->expedido }}) @endif
                            </span>
                            @if($persona->correo)
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill">
                                    <i class="ri-mail-line me-1"></i>{{ $persona->correo }}
                                </span>
                            @endif
                            @if($persona->celular)
                                <span class="badge bg-light text-dark border rounded-pill">
                                    <i class="ri-phone-line me-1"></i>{{ $persona->celular }}
                                </span>
                            @endif
                        </div>
                        @if($persona->direccion || optional($persona->ciudad)->nombre)
                            <div class="text-muted small">
                                <i class="ri-map-pin-line me-1"></i>
                                {{ $persona->direccion ?? '' }}
                                @if(optional($persona->ciudad)->nombre)
                                    · {{ $persona->ciudad->nombre }},
                                    {{ optional($persona->ciudad->departamento)->nombre ?? '' }}
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Stats rápidas --}}
            <div class="col-md-5">
                <div class="row g-2">
                    <div class="col-4">
                        <div class="rounded-2 border p-2 text-center h-100">
                            <div class="fw-bold fs-5 text-primary mb-0">{{ $estudiante->inscripciones->count() }}</div>
                            <div class="text-muted" style="font-size:.7rem;">Programas</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="rounded-2 border p-2 text-center h-100" style="border-color:#198754!important;background:#f0fff5;">
                            <div class="fw-bold fs-5 text-success mb-0">{{ $inscritos }}</div>
                            <div class="text-muted" style="font-size:.7rem;">Activos</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="rounded-2 border p-2 text-center h-100" style="border-color:#dc3545!important;background:#fff5f5;">
                            <div class="fw-bold text-danger mb-0" style="font-size:.88rem;">{{ number_format($totalDeuda, 0) }}</div>
                            <div class="text-muted" style="font-size:.7rem;">Bs Deuda</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="rounded-2 border p-2 text-center h-100">
                            <div class="fw-bold fs-5 text-info mb-0">{{ $persona->estudios->count() }}</div>
                            <div class="text-muted" style="font-size:.7rem;">Estudios</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="rounded-2 border p-2 text-center h-100">
                            <div class="fw-bold fs-5 text-warning mb-0">{{ $edad ?? '—' }}</div>
                            <div class="text-muted" style="font-size:.7rem;">Años</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="rounded-2 border p-2 text-center h-100">
                            <div class="fw-semibold mb-0 text-truncate" style="font-size:.82rem;">{{ $persona->sexo ?? '—' }}</div>
                            <div class="text-muted" style="font-size:.7rem;">Sexo</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Barra de documentación --}}
        @if ($documentosTotales > 0)
            <div class="mt-4 pt-3 border-top">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small fw-medium">
                        <i class="ri-file-check-line me-1"></i>Documentación
                    </span>
                    <div class="d-flex align-items-center gap-2">
                        @if (count($documentosPendientes) > 0)
                            <span class="text-muted small">Pendientes: {{ implode(', ', $documentosPendientes) }}</span>
                        @endif
                        <span class="badge bg-{{ $colorDoc }} rounded-pill">
                            {{ $documentosCompletos }}/{{ $documentosTotales }} verificados
                        </span>
                    </div>
                </div>
                <div class="progress rounded-pill" style="height:6px;">
                    <div class="progress-bar bg-{{ $colorDoc }}" style="width:{{ $porcentajeDocumentos }}%"></div>
                </div>
            </div>
        @endif
    </div>
</div>
