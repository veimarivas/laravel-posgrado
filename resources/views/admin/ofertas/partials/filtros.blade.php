<div class="card">
    <div class="card-header border-0 bg-light-subtle">
        <h5 class="card-title mb-0">Filtrar Ofertas Académicas</h5>
    </div>
    <div class="card-body border border-dashed border-secondary-subtle rounded-2">
        <div class="row g-3">
            <!-- Sucursal -->
            <div class="col-md-4 col-lg-3">
                <label for="filtroSucursal" class="form-label">Sucursal</label>
                <select id="filtroSucursal" class="form-select">
                    <option value="">Todas las sucursales</option>
                    @foreach ($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}">
                            {{ $sucursal->sede->nombre ?? 'Sin sede' }} - {{ $sucursal->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Convenio -->
            <div class="col-md-4 col-lg-3">
                <label for="filtroConvenio" class="form-label">Convenio</label>
                <select id="filtroConvenio" class="form-select">
                    <option value="">Todos los convenios</option>
                    @foreach ($convenios as $convenio)
                        <option value="{{ $convenio->id }}">{{ $convenio->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Fase -->
            <div class="col-md-4 col-lg-2">
                <label for="filtroFase" class="form-label">Fase</label>
                <select id="filtroFase" class="form-select">
                    <option value="">Todas las fases</option>
                    @foreach ($fases as $fase)
                        <option value="{{ $fase->id }}">{{ $fase->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Modalidad -->
            <div class="col-md-4 col-lg-2">
                <label for="filtroModalidad" class="form-label">Modalidad</label>
                <select id="filtroModalidad" class="form-select">
                    <option value="">Todas las modalidades</option>
                    @foreach ($modalidades as $modalidad)
                        <option value="{{ $modalidad->id }}">{{ $modalidad->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Botón Limpiar -->
            <div class="col-md-4 col-lg-2 d-flex align-items-end">
                <button type="button" class="btn btn-outline-secondary w-100" id="clearFilters">
                    <i class="ri-refresh-line me-1"></i> Limpiar
                </button>
            </div>
        </div>
    </div>
</div>
