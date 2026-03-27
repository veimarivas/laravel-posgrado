@if (auth()->user()->persona->estudios->count() > 0)

    <div class="row g-3">
        @foreach (auth()->user()->persona->estudios as $estudio)
            @php
                $estadoColor = match($estudio->estado) {
                    'Concluido' => 'success',
                    'En curso'  => 'warning',
                    default     => 'danger',
                };
            @endphp
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden"
                     style="border-left:4px solid var(--bs-{{ $estadoColor }})!important;">
                    <div class="card-body p-0">

                        {{-- Header --}}
                        <div class="d-flex align-items-center gap-3 px-3 py-3 border-bottom" style="background:#fafafa;">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-{{ $estadoColor }}-subtle text-{{ $estadoColor }} rounded-2">
                                    <i class="ri-graduation-cap-line fs-18"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold text-truncate">
                                    {{ $estudio->grado_academico->nombre ?? 'N/A' }}
                                </div>
                                <div class="text-muted" style="font-size:.75rem;">
                                    {{ $estudio->profesion->nombre ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                                <span class="badge bg-{{ $estadoColor }}-subtle text-{{ $estadoColor }} border border-{{ $estadoColor }}-subtle rounded-pill" style="font-size:.7rem;">
                                    {{ $estudio->estado }}
                                </span>
                                @if ($estudio->principal)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size:.65rem;">
                                        <i class="ri-star-fill me-1"></i>Principal
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Detalle --}}
                        <div class="px-3 py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="avatar-xs flex-shrink-0">
                                    <div class="avatar-title bg-secondary-subtle text-secondary rounded">
                                        <i class="ri-building-line fs-13"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-muted" style="font-size:.7rem;">UNIVERSIDAD</div>
                                    <div class="fw-medium small">
                                        {{ $estudio->universidad->nombre ?? 'N/A' }}
                                        @if(optional($estudio->universidad)->sigla)
                                            <span class="text-muted">({{ $estudio->universidad->sigla }})</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

@else
    <div class="text-center py-5">
        <div class="avatar-lg mx-auto mb-3">
            <div class="avatar-title bg-light text-secondary rounded-circle">
                <i class="ri-graduation-cap-line fs-2"></i>
            </div>
        </div>
        <h5 class="mb-1">No tienes estudios registrados</h5>
        <p class="text-muted small mb-0">Registra tus estudios en la sección de administración.</p>
    </div>
@endif
