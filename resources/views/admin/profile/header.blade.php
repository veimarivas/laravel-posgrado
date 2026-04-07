@php
    $persona    = auth()->user()->persona;
    $rolNombre  = auth()->user()->roles->first()->name ?? 'Usuario';
    $totalCargos = $persona->trabajador?->trabajadores_cargos->where('estado','Vigente')->count() ?? 0;
@endphp

<div class="profile-header">
    <div>
        <h1><i class="ri-user-line me-2"></i>Mi Perfil</h1>
        <p>Gestiona tu información personal, cargos y configuración de cuenta</p>
    </div>
    <div class="profile-badges">
        <span class="profile-badge">
            <i class="ri-shield-user-line"></i>{{ $rolNombre }}
        </span>
        <span class="profile-badge">
            <i class="ri-briefcase-line"></i>{{ $totalCargos }} cargo(s) activo(s)
        </span>
    </div>
</div>
