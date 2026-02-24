<!-- Page Title -->
<div class="row mb-4">
    <div class="col-12">
        <div
            class="page-title-box d-sm-flex align-items-center justify-content-between bg-primary bg-opacity-10 rounded-3 p-3">
            <div>
                <h4 class="mb-1 fw-bold text-primary">
                    <i class="ri-user-line me-2"></i>Mi Perfil
                </h4>
                <p class="text-muted mb-0">Gestiona tu información personal y consulta tus actividades</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary">
                    <i class="ri-shield-user-line me-1"></i>
                    {{ auth()->user()->roles->first()->name ?? 'Usuario' }}
                </span>
            </div>
        </div>
    </div>
</div>
