<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <button type="button"
                    class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger material-shadow-none"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>

            <div class="d-flex align-items-center">
                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button"
                        class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle light-dark-mode"
                        id="theme-toggle" title="Cambiar modo oscuro">
                        <i class='bx bx-moon fs-22' id="theme-icon"></i>
                    </button>
                </div>

                @php
                    $id = Auth::user()->id;
                    $profileData = App\Models\User::find($id);
                    $fotoUrl =
                        isset($profileData->persona->fotografia) && $profileData->persona->fotografia
                            ? asset($profileData->persona->fotografia)
                            : asset('backend/assets/images/users/user-dummy-img.jpg');
                @endphp

                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="{{ $fotoUrl }}"
                                alt="Foto de perfil">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
                                    {{ $profileData->name }}
                                </span>
                                <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">
                                    {{ $profileData->role }}
                                </span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-header user-dropdown-header">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <img class="rounded-circle" src="{{ $fotoUrl }}" alt="Foto" width="40" height="40">
                                <div>
                                    @if (isset($profileData->persona))
                                        <div class="fw-semibold">{{ $profileData->persona->apellido_paterno ?? '' }}
                                            {{ $profileData->persona->apellido_materno ?? '' }},
                                            {{ $profileData->persona->nombres ?? 'Usuario' }}
                                        </div>
                                    @else
                                        <div class="fw-semibold">{{ $profileData->name }}</div>
                                    @endif
                                    <div class="text-muted fs-12">{{ $profileData->role }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                            <i class="ri-user-settings-line align-middle me-2 fs-16"></i>
                            <span class="align-middle">Perfil</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}">
                            <i class="ri-logout-box-r-line align-middle me-2 fs-16"></i>
                            <span class="align-middle">Cerrar Sesión</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
    /* ===== HEADER BASE ===== */
    #page-topbar {
        background-color: var(--header-bg, #ffffff);
        border-bottom: 1px solid var(--header-border, #e9ebec);
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    /* ===== HAMBURGER ===== */
    .hamburger-icon span {
        display: block;
        width: 20px;
        height: 2px;
        background-color: var(--header-text, #495057);
        margin: 5px 0;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .hamburger-icon span:nth-child(2) {
        width: 16px;
    }

    #topnav-hamburger-icon:hover .hamburger-icon span {
        background-color: var(--sidebar-accent, #0ab39c);
    }

    #topnav-hamburger-icon:hover .hamburger-icon span:nth-child(2) {
        width: 20px;
    }

    /* ===== THEME TOGGLE ===== */
    #theme-toggle {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    #theme-toggle:hover {
        transform: scale(1.08);
        background-color: rgba(10, 179, 156, 0.1) !important;
    }

    #theme-toggle:hover i {
        color: var(--sidebar-accent, #0ab39c) !important;
    }

    #theme-toggle i {
        transition: all 0.3s ease;
    }

    /* ===== PROFILE USER ===== */
    .header-profile-user {
        width: 36px;
        height: 36px;
        object-fit: cover;
        border: 2px solid rgba(10, 179, 156, 0.25);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .topbar-user:hover .header-profile-user {
        border-color: var(--sidebar-accent, #0ab39c);
        box-shadow: 0 0 0 3px rgba(10, 179, 156, 0.12);
    }

    .user-name-text {
        color: var(--header-text, #334155);
        font-weight: 600;
        font-size: 0.85rem;
        transition: color 0.3s ease;
    }

    .user-name-sub-text {
        color: var(--header-text, #94a3b8);
        font-size: 0.72rem;
        opacity: 0.8;
        transition: color 0.3s ease;
    }

    /* ===== DROPDOWN ===== */
    .dropdown-menu {
        border: 1px solid var(--header-border, #e2e8f0);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08), 0 4px 10px rgba(0, 0, 0, 0.04);
        border-radius: 12px;
        overflow: hidden;
        padding: 0;
        min-width: 220px;
        transition: all 0.2s ease;
    }

    .user-dropdown-header {
        background: linear-gradient(135deg, rgba(10, 179, 156, 0.08), rgba(10, 179, 156, 0.03));
        padding: 14px 16px;
        border-bottom: 1px solid var(--header-border, #e2e8f0);
    }

    .user-dropdown-header .rounded-circle {
        border: 2px solid rgba(10, 179, 156, 0.2);
    }

    .dropdown-item {
        padding: 10px 16px;
        font-size: 0.85rem;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
    }

    .dropdown-item:hover {
        background: rgba(10, 179, 156, 0.06);
        color: var(--sidebar-accent, #0ab39c);
        padding-left: 20px;
    }

    .dropdown-item.text-danger:hover {
        background: rgba(239, 68, 68, 0.06);
        color: #ef4444;
        padding-left: 20px;
    }

    .dropdown-divider {
        border-color: var(--header-border, #e2e8f0);
        margin: 0;
    }

    /* ===== DARK MODE ===== */
    [data-bs-theme="dark"] #page-topbar {
        background-color: var(--header-bg, #212229) !important;
        border-bottom-color: var(--header-border, #2d2d3a) !important;
    }

    [data-bs-theme="dark"] .hamburger-icon span {
        background-color: #e9ecef;
    }

    [data-bs-theme="dark"] .user-name-text {
        color: #e9ecef !important;
    }

    [data-bs-theme="dark"] .user-name-sub-text {
        color: #9ca3af !important;
    }

    [data-bs-theme="dark"] .btn-ghost-secondary {
        color: #e9ecef !important;
    }

    [data-bs-theme="dark"] .btn-ghost-secondary:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    [data-bs-theme="dark"] .header-profile-user {
        border-color: rgba(255, 255, 255, 0.25);
    }

    [data-bs-theme="dark"] .topbar-user:hover .header-profile-user {
        border-color: var(--sidebar-accent, #0ab39c);
        box-shadow: 0 0 0 3px rgba(10, 179, 156, 0.15);
    }

    [data-bs-theme="dark"] .dropdown-menu {
        background-color: var(--card-bg, #212229);
        border-color: var(--card-border, #2d2d3a);
    }

    [data-bs-theme="dark"] .dropdown-item {
        color: #e9ecef;
    }

    [data-bs-theme="dark"] .user-dropdown-header {
        background: linear-gradient(135deg, rgba(10, 179, 156, 0.12), rgba(10, 179, 156, 0.05));
        border-bottom-color: var(--card-border, #2d2d3a);
    }

    [data-bs-theme="dark"] .dropdown-divider {
        border-color: var(--card-border, #2d2d3a);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1199.98px) {
        .header-profile-user {
            width: 32px;
            height: 32px;
        }
        .user-name-text,
        .user-name-sub-text {
            display: none !important;
        }
    }

    @media (max-width: 991px) {
        .header-profile-user {
            width: 30px;
            height: 30px;
        }
    }
</style>
