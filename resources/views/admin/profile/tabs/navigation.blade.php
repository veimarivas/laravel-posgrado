<ul class="nav nav-tabs card-header-tabs mb-0 px-3" role="tablist">
    <li class="nav-item">
        <a class="nav-link active py-3" data-bs-toggle="tab" href="#personal" role="tab">
            <i class="ri-user-line me-1"></i>
            <span class="d-none d-sm-inline">Información Personal</span>
            <span class="d-sm-none">Personal</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link py-3" data-bs-toggle="tab" href="#cargos" role="tab">
            <i class="ri-briefcase-line me-1"></i>
            <span class="d-none d-sm-inline">Mis Cargos</span>
            <span class="d-sm-none">Cargos</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link py-3" data-bs-toggle="tab" href="#estudios" role="tab">
            <i class="ri-graduation-cap-line me-1"></i>
            <span>Estudios</span>
        </a>
    </li>
    @if ($tieneMarketing)
        <li class="nav-item">
            <a class="nav-link py-3" data-bs-toggle="tab" href="#marketing" role="tab">
                <i class="ri-bar-chart-line me-1"></i>
                <span class="d-none d-sm-inline">Marketing</span>
                <span class="d-sm-none">Mkt</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link py-3" data-bs-toggle="tab" href="#ofertas-activas" role="tab">
                <i class="ri-gift-line me-1"></i>
                <span class="d-none d-sm-inline">Ofertas Activas</span>
                <span class="d-sm-none">Ofertas</span>
            </a>
        </li>
    @endif
    <li class="nav-item">
        <a class="nav-link py-3" data-bs-toggle="tab" href="#password" role="tab">
            <i class="ri-lock-password-line me-1"></i>
            <span class="d-none d-sm-inline">Contraseña</span>
            <span class="d-sm-none">Clave</span>
        </a>
    </li>
</ul>
