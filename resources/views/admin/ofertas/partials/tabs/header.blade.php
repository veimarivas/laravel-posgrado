<!-- Encabezado de la Oferta - Diseño Compacto -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 py-2" style="border-bottom: 1px solid #e5e7eb;">
    <div class="d-flex align-items-center gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="text-muted text-decoration-none fs-11">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.ofertas.listar') }}" class="text-muted text-decoration-none fs-11">Ofertas</a>
                </li>
                <li class="breadcrumb-item active fw-semibold text-dark fs-11">{{ $oferta->codigo }}</li>
            </ol>
        </nav>
        <span class="badge fs-9 px-2 py-1 rounded-pill"
            style="background: {{ $oferta->color }}20; color: {{ $oferta->color }}; border: 1px solid {{ $oferta->color }}40;">
            <i class="ri-bookmark-fill align-middle me-1"></i> {{ $oferta->fase->nombre ?? 'Sin fase' }}
        </span>
    </div>
    
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.ofertas.vermodulos', $oferta->id) }}" 
            class="btn btn-sm d-flex align-items-center gap-1" 
            style="background: var(--dash-primary-light); color: var(--dash-primary); border-radius: 6px; font-size: 0.7rem;">
            <i class="ri-calendar-2-line"></i>
            <span class="d-none d-md-inline">Módulos</span>
        </a>
        <a href="{{ route('admin.ofertas.planes-pago', $oferta->id) }}" 
            class="btn btn-sm d-flex align-items-center gap-1" 
            style="background: var(--dash-primary-light); color: var(--dash-primary); border-radius: 6px; font-size: 0.7rem;">
            <i class="ri-money-dollar-circle-line"></i>
            <span class="d-none d-md-inline">Planes</span>
        </a>
    </div>
</div>