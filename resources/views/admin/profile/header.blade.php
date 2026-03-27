@php
    $persona    = auth()->user()->persona;
    $rolNombre  = auth()->user()->roles->first()->name ?? 'Usuario';
    $totalCargos = $persona->trabajador?->trabajadores_cargos->where('estado','Vigente')->count() ?? 0;
@endphp

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Admin</a></li>
                <li class="breadcrumb-item active">Mi Perfil</li>
            </ol>
        </nav>
        <h4 class="mb-0 fw-semibold">
            <i class="ri-user-line me-2 text-primary"></i>Mi Perfil
        </h4>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
            <i class="ri-shield-user-line me-1"></i>{{ $rolNombre }}
        </span>
        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
            <i class="ri-briefcase-line me-1"></i>{{ $totalCargos }} cargo(s) activo(s)
        </span>
    </div>
</div>
