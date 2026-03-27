<div class="card border-0 shadow-sm mb-3">
    <div class="card-header border-0 py-2 px-3 d-flex align-items-center gap-2" style="background:#f8f9fa;">
        <i class="ri-filter-3-line text-primary fs-16"></i>
        <span class="fw-semibold small">Filtrar Ofertas Académicas</span>
    </div>
    <div class="card-body py-2 px-3">
        <div class="row g-2 align-items-end">

            <!-- Sucursal -->
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <label for="filtroSucursal" class="form-label mb-1 text-muted fw-semibold" style="font-size:.7rem;">SUCURSAL</label>
                <select id="filtroSucursal" class="form-select form-select-sm">
                    <option value="">Todas las sucursales</option>
                    @foreach ($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}">
                            {{ $sucursal->sede->nombre ?? 'Sin sede' }} - {{ $sucursal->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Convenio -->
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <label for="filtroConvenio" class="form-label mb-1 text-muted fw-semibold" style="font-size:.7rem;">CONVENIO</label>
                <select id="filtroConvenio" class="form-select form-select-sm">
                    <option value="">Todos los convenios</option>
                    @foreach ($convenios as $convenio)
                        <option value="{{ $convenio->id }}">{{ $convenio->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Fase -->
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
                <label for="filtroFase" class="form-label mb-1 text-muted fw-semibold" style="font-size:.7rem;">FASE</label>
                <select id="filtroFase" class="form-select form-select-sm">
                    <option value="">Todas las fases</option>
                    @foreach ($fases as $fase)
                        <option value="{{ $fase->id }}">{{ $fase->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Modalidad -->
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
                <label for="filtroModalidad" class="form-label mb-1 text-muted fw-semibold" style="font-size:.7rem;">MODALIDAD</label>
                <select id="filtroModalidad" class="form-select form-select-sm">
                    <option value="">Todas las modalidades</option>
                    @foreach ($modalidades as $modalidad)
                        <option value="{{ $modalidad->id }}">{{ $modalidad->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Gestión -->
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
                <label for="filtroGestion" class="form-label mb-1 text-muted fw-semibold" style="font-size:.7rem;">GESTIÓN</label>
                <select id="filtroGestion" class="form-select form-select-sm">
                    <option value="">Todas las gestiones</option>
                    @foreach ($gestiones as $gestion)
                        <option value="{{ $gestion }}" {{ $gestion == date('Y') ? 'selected' : '' }}>
                            {{ $gestion }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Botón Limpiar -->
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 d-flex align-items-end">
                <button type="button" class="btn btn-outline-secondary btn-sm w-100" id="clearFilters">
                    <i class="ri-refresh-line me-1"></i>Limpiar filtros
                </button>
            </div>

        </div>
    </div>
</div>
