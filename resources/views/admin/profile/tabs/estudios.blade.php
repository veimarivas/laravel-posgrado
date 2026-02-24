@if (auth()->user()->persona->estudios->count() > 0)
    <div class="row">
        @foreach (auth()->user()->persona->estudios as $estudio)
            <div class="col-md-6 mb-3">
                <div class="card border">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="avatar-md">
                                    <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded">
                                        <i class="ri-graduation-cap-line display-5"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">
                                    {{ $estudio->grado_academico->nombre ?? 'N/A' }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="ri-award-line me-1"></i>
                                    {{ $estudio->profesion->nombre ?? 'N/A' }}
                                </p>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ri-building-line me-2 text-muted"></i>
                                    <span class="fw-medium">{{ $estudio->universidad->nombre ?? 'N/A' }}</span>
                                </div>
                                <span
                                    class="badge {{ $estudio->estado == 'Concluido' ? 'bg-success' : ($estudio->estado == 'En curso' ? 'bg-warning' : 'bg-danger') }} badge-status">
                                    {{ $estudio->estado }}
                                </span>
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
                <i class="ri-graduation-cap-line display-4"></i>
            </div>
        </div>
        <h5 class="text-muted">No tienes estudios registrados</h5>
        <p class="text-muted mb-0">Registra tus estudios en la sección de administración.</p>
    </div>
@endif
