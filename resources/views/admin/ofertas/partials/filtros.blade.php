<div class="filter-bar">
    {{-- Sucursal --}}
    <div class="filter-group">
        <label for="filtroSucursal">Sucursal</label>
        <select id="filtroSucursal">
            <option value="">Todas las sucursales</option>
            @foreach ($sucursales as $sucursal)
                <option value="{{ $sucursal->id }}">
                    {{ $sucursal->sede->nombre ?? 'Sin sede' }} - {{ $sucursal->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Convenio --}}
    <div class="filter-group">
        <label for="filtroConvenio">Convenio</label>
        <select id="filtroConvenio">
            <option value="">Todos los convenios</option>
            @foreach ($convenios as $convenio)
                <option value="{{ $convenio->id }}">{{ $convenio->nombre }}</option>
            @endforeach
        </select>
    </div>

    {{-- Fase --}}
    <div class="filter-group">
        <label for="filtroFase">Fase</label>
        <select id="filtroFase">
            <option value="">Todas las fases</option>
            @foreach ($fases as $fase)
                <option value="{{ $fase->id }}">{{ $fase->nombre }}</option>
            @endforeach
        </select>
    </div>

    {{-- Modalidad --}}
    <div class="filter-group">
        <label for="filtroModalidad">Modalidad</label>
        <select id="filtroModalidad">
            <option value="">Todas las modalidades</option>
            @foreach ($modalidades as $modalidad)
                <option value="{{ $modalidad->id }}">{{ $modalidad->nombre }}</option>
            @endforeach
        </select>
    </div>

    {{-- Gestión --}}
    <div class="filter-group">
        <label for="filtroGestion">Gestión</label>
        <select id="filtroGestion">
            <option value="">Todas las gestiones</option>
            @foreach ($gestiones as $gestion)
                <option value="{{ $gestion }}" {{ $gestion == date('Y') ? 'selected' : '' }}>
                    {{ $gestion }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Botón Limpiar --}}
    <div>
        <button type="button" class="btn-clear-filters" id="clearFilters">
            <i class="ri-refresh-line me-1"></i>Limpiar
        </button>
    </div>
</div>
