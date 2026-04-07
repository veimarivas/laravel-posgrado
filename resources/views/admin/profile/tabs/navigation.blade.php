<div class="profile-tabs" role="tablist">
    <button class="profile-tab active" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
        <i class="ri-user-line"></i>
        <span>Información Personal</span>
    </button>
    <button class="profile-tab" data-bs-toggle="tab" data-bs-target="#cargos" type="button" role="tab">
        <i class="ri-briefcase-line"></i>
        <span>Mis Cargos</span>
    </button>
    <button class="profile-tab" data-bs-toggle="tab" data-bs-target="#estudios" type="button" role="tab">
        <i class="ri-graduation-cap-line"></i>
        <span>Estudios</span>
    </button>
    @if ($tieneMarketing)
        <button class="profile-tab" data-bs-toggle="tab" data-bs-target="#marketing" type="button" role="tab">
            <i class="ri-bar-chart-line"></i>
            <span>Marketing</span>
        </button>
        <button class="profile-tab" data-bs-toggle="tab" data-bs-target="#ofertas-activas" type="button" role="tab">
            <i class="ri-gift-line"></i>
            <span>Ofertas Activas</span>
        </button>
    @endif
    <button class="profile-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">
        <i class="ri-lock-password-line"></i>
        <span>Contraseña</span>
    </button>
</div>
