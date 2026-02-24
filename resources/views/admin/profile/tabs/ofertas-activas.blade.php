<!-- Header de la sección -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-1">
                    <i class="ri-gift-line me-2"></i>Ofertas Académicas Activas
                </h5>
                <p class="text-muted mb-0">Programas en fase de inscripciones - Genera enlaces personalizados</p>
            </div>
            <div class="d-flex gap-2">
                <button id="refreshOfertas" class="btn btn-outline-primary btn-sm">
                    <i class="ri-refresh-line"></i> Actualizar
                </button>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                        data-bs-toggle="dropdown">
                        <i class="ri-download-line me-1"></i> Exportar
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" id="exportOfertasCSV">CSV</a></li>
                        <li><a class="dropdown-item" href="#" id="exportOfertasPDF">PDF</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="ri-search-line"></i>
                    </span>
                    <input type="text" class="form-control" id="searchOfertas"
                        placeholder="Buscar por código o nombre de programa...">
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="filterSucursal">
                    <option value="">Todas las sucursales</option>
                    @foreach (\App\Models\Sucursale::all() as $sucursal)
                        <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button id="applyFilters" class="btn btn-primary w-100">
                    <i class="ri-filter-line me-1"></i> Filtrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Contador y estadísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card border-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h3 class="mb-0" id="totalOfertas">0</h3>
                        <p class="text-muted mb-0">Ofertas Activas</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-info bg-opacity-10 text-info">
                            <i class="ri-gift-line fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card border-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h3 class="mb-0" id="totalProgramas">0</h3>
                        <p class="text-muted mb-0">Programas Diferentes</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-success bg-opacity-10 text-success">
                            <i class="ri-book-line fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card border-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h3 class="mb-0" id="totalSucursales">0</h3>
                        <p class="text-muted mb-0">Sucursales</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                            <i class="ri-building-line fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card border-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0" id="cargoActual">-</h4>
                        <p class="text-muted mb-0">Cargo Principal</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                            <i class="ri-briefcase-line fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lista de ofertas -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="ri-list-check me-1"></i> Lista de Ofertas
            <span id="ofertasCount" class="badge bg-primary ms-2">0</span>
        </h5>
        <div class="d-flex gap-2">
            <div class="input-group input-group-sm" style="width: 200px;">
                <span class="input-group-text">Mostrar</span>
                <select class="form-select" id="itemsPerPage">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="ofertasContainer">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2 text-muted">Cargando ofertas activas...</p>
            </div>
        </div>
        <div id="ofertasPagination" class="mt-3"></div>
    </div>
</div>
