<ul class="nav nav-tabs nav-justified mb-0" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#personal" role="tab">
            <i class="ri-user-line me-1"></i>
            <span class="d-none d-md-inline">Información Personal</span>
            <span class="d-md-none">Personal</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#cargos" role="tab">
            <i class="ri-briefcase-line me-1"></i>
            <span class="d-none d-md-inline">Mis Cargos</span>
            <span class="d-md-none">Cargos</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#estudios" role="tab">
            <i class="ri-graduation-cap-line me-1"></i>
            <span class="d-none d-md-inline">Estudios</span>
            <span class="d-md-none">Estudios</span>
        </a>
    </li>
    @if ($tieneMarketing)
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#marketing" role="tab">
                <i class="ri-bar-chart-line me-1"></i>
                <span class="d-none d-md-inline">Marketing</span>
                <span class="d-md-none">Marketing</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#ofertas-activas" role="tab">
                <i class="ri-gift-line me-1"></i>
                <span class="d-none d-md-inline">Ofertas Activas</span>
                <span class="d-md-none">Ofertas</span>
            </a>
        </li>
    @endif
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#password" role="tab">
            <i class="ri-lock-password-line me-1"></i>
            <span class="d-none d-md-inline">Cambiar Contraseña</span>
            <span class="d-md-none">Contraseña</span>
        </a>
    </li>
</ul>
