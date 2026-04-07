{{-- Header --}}
<div class="ofertas-header-section">
    <div>
        <h5 class="ofertas-title"><i class="ri-gift-line"></i>Ofertas Académicas Activas</h5>
        <p class="ofertas-subtitle">Programas en inscripciones — genera enlaces personalizados</p>
    </div>
    <div>
        <button id="refreshOfertas" class="mkt-btn-primary btn-sm">
            <i class="ri-refresh-line me-1"></i>Actualizar
        </button>
    </div>
</div>

{{-- Filtros --}}
<div class="mkt-filters-card mb-3">
    <div class="mkt-filters-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="mkt-label">Buscar</label>
                <div class="mkt-search-group">
                    <i class="ri-search-line"></i>
                    <input type="text" class="mkt-search-input" id="searchOfertas" placeholder="Código o nombre de programa...">
                </div>
            </div>
            <div class="col-md-5">
                <label class="mkt-label">Sucursal</label>
                <select class="mkt-select" id="filterSucursal">
                    <option value="">Todas las sucursales</option>
                    @foreach (\App\Models\Sucursale::all() as $sucursal)
                        <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button id="applyFilters" class="mkt-btn-primary btn-sm w-100">
                    <i class="ri-filter-line me-1"></i>Filtrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Contador y estadísticas -->
<div class="row g-2 mb-4">
    <div class="col-md-3">
        <div class="mkt-stat-card">
            <div class="mkt-stat-body">
                <div class="flex-grow-1">
                    <div class="mkt-stat-value" id="totalOfertas">0</div>
                    <p class="mkt-stat-label">Ofertas Activas</p>
                </div>
                <div class="mkt-stat-icon" style="background:rgba(8,145,178,0.1);color:var(--conc-info);">
                    <i class="ri-gift-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mkt-stat-card">
            <div class="mkt-stat-body">
                <div class="flex-grow-1">
                    <div class="mkt-stat-value" id="totalProgramas">0</div>
                    <p class="mkt-stat-label">Programas Diferentes</p>
                </div>
                <div class="mkt-stat-icon" style="background:rgba(16,185,129,0.1);color:var(--conc-success);">
                    <i class="ri-book-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mkt-stat-card">
            <div class="mkt-stat-body">
                <div class="flex-grow-1">
                    <div class="mkt-stat-value" id="totalSucursales">0</div>
                    <p class="mkt-stat-label">Sucursales</p>
                </div>
                <div class="mkt-stat-icon" style="background:rgba(245,158,11,0.1);color:var(--conc-accent);">
                    <i class="ri-building-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mkt-stat-card">
            <div class="mkt-stat-body">
                <div class="flex-grow-1">
                    <div class="mkt-stat-value" style="font-size:1.1rem;" id="cargoActual">-</div>
                    <p class="mkt-stat-label">Cargo Principal</p>
                </div>
                <div class="mkt-stat-icon" style="background:rgba(15,118,110,0.1);color:var(--conc-primary);">
                    <i class="ri-briefcase-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lista de ofertas -->
<div class="mkt-table-card">
    <div class="mkt-table-header">
        <h5 class="mkt-table-title">
            <i class="ri-list-check"></i> Lista de Ofertas
            <span id="ofertasCount" class="mkt-badge">0</span>
        </h5>
        <div class="d-flex gap-2">
            <div class="d-flex align-items-center gap-1">
                <span class="mkt-label" style="margin-bottom:0;">Mostrar</span>
                <select class="mkt-select" id="itemsPerPage" style="width:70px;">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>
    <div class="mkt-table-body">
        <div id="ofertasContainer">
            <div class="text-center py-5">
                <div class="spinner-border" role="status" style="color:var(--conc-primary);">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2" style="color:var(--conc-text-muted);">Cargando ofertas activas...</p>
            </div>
        </div>
        <div id="ofertasPagination" class="mt-3"></div>
    </div>
</div>
