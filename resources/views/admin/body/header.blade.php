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

                <div class="dropdown d-md-none topbar-head-dropdown header-item">
                    <button type="button"
                        class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle"
                        id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="bx bx-search fs-22"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                        aria-labelledby="page-header-search-dropdown">
                        <form class="p-3">
                            <div class="form-group m-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search ..."
                                        aria-label="Recipient's username">
                                    <button class="btn btn-primary" type="submit"><i
                                            class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button"
                        class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle light-dark-mode">
                        <i class='bx bx-moon fs-22'></i>
                    </button>
                </div>



                @php
                    $id = Auth::user()->id;
                    $profileData = App\Models\User::find($id);

                    // Obtener la URL de la foto de perfil
                    $fotoUrl =
                        isset($profileData->persona->fotografia) && $profileData->persona->fotografia
                            ? asset($profileData->persona->fotografia)
                            : asset('backend/assets/images/users/user-dummy-img.jpg');
                @endphp

                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <!-- Imagen de perfil dinámica con manejo de errores -->
                            <img class="rounded-circle header-profile-user" src="{{ $fotoUrl }}"
                                alt="Foto de perfil de {{ $profileData->name }}"
                                onerror="this.onerror=null; this.src='{{ asset('backend/assets/images/users/user-dummy-img.jpg') }}'">
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
                        <!-- Header con nombre completo -->
                        <h6 class="dropdown-header">
                            @if (isset($profileData->persona))
                                {{ $profileData->persona->apellido_paterno ?? '' }}
                                {{ $profileData->persona->apellido_materno ?? '' }},
                                {{ $profileData->persona->nombres ?? 'Usuario' }}
                            @else
                                {{ $profileData->name }}
                            @endif
                        </h6>

                        <!-- Enlace al perfil -->
                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                            <i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i>
                            <span class="align-middle">Perfil</span>
                        </a>

                        <!-- Enlace para salir -->
                        <a class="dropdown-item" href="{{ route('admin.logout') }}">
                            <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                            <span class="align-middle" data-key="t-logout">Salir</span>
                        </a>
                    </div>
                </div>

                <style>
                    /* Estilos adicionales para mejorar la apariencia */
                    .header-profile-user {
                        width: 32px;
                        height: 32px;
                        object-fit: cover;
                        border: 2px solid rgba(255, 255, 255, 0.2);
                        transition: all 0.3s ease;
                    }

                    .topbar-user:hover .header-profile-user {
                        border-color: rgba(10, 179, 156, 0.5);
                    }

                    .user-name-text {
                        color: #495057;
                        font-weight: 500;
                    }

                    .user-name-sub-text {
                        color: #878a99;
                    }

                    /* Para tema oscuro del header (si aplica) */
                    [data-topbar="dark"] .user-name-text {
                        color: #ffffff;
                    }

                    [data-topbar="dark"] .user-name-sub-text {
                        color: rgba(255, 255, 255, 0.7);
                    }

                    /* Ajustes responsive */
                    @media (max-width: 1199.98px) {
                        .header-profile-user {
                            width: 28px;
                            height: 28px;
                        }
                    }

                    /* Efecto de hover en el botón */
                    .btn.material-shadow-none:hover {
                        background-color: rgba(0, 0, 0, 0.05);
                    }

                    [data-topbar="dark"] .btn.material-shadow-none:hover {
                        background-color: rgba(255, 255, 255, 0.1);
                    }
                </style>
            </div>
        </div>
    </div>
</header>
