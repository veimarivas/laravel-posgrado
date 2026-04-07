@if (auth()->user()->persona->estudios->count() > 0)

    <div class="row g-3">
        @foreach (auth()->user()->persona->estudios as $estudio)
            @php
                $estadoCls = match($estudio->estado) {
                    'Concluido' => 'concluido',
                    'En curso'  => 'en-curso',
                    default     => 'otro',
                };
            @endphp
            <div class="col-md-6">
                <div class="estudio-card estado-{{ $estadoCls }}">

                    {{-- Header --}}
                    <div class="estudio-header">
                        <div class="estudio-icon bg-{{ $estadoCls === 'concluido' ? 'success' : ($estadoCls === 'en-curso' ? 'warning' : 'danger') }}-subtle text-{{ $estadoCls === 'concluido' ? 'success' : ($estadoCls === 'en-curso' ? 'warning' : 'danger') }}">
                            <i class="ri-graduation-cap-line"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="estudio-name text-truncate">
                                {{ $estudio->grado_academico->nombre ?? 'N/A' }}
                            </div>
                            <div class="estudio-profession">
                                {{ $estudio->profesion->nombre ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="estudio-badges">
                            <span class="estado-badge-profile {{ $estadoCls === 'concluido' ? 'vigente' : ($estadoCls === 'en-curso' ? 'inactivo' : 'inactivo') }}" style="font-size:.7rem;">
                                {{ $estudio->estado }}
                            </span>
                            @if ($estudio->principal)
                                <span class="principal-badge" style="font-size:.65rem;">
                                    <i class="ri-star-fill"></i>Principal
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Detalle --}}
                    <div class="estudio-body">
                        <div class="estudio-university">
                            <i class="ri-building-line"></i>
                            <div>
                                <div class="uni-label">Universidad</div>
                                <div class="uni-name">
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
        @endforeach
    </div>

@else
    <div class="empty-state-profile">
        <div class="empty-state-profile-icon">
            <i class="ri-graduation-cap-line"></i>
        </div>
        <h5>No tienes estudios registrados</h5>
        <p class="text-muted small mb-0">Registra tus estudios en la sección de administración.</p>
    </div>
@endif
