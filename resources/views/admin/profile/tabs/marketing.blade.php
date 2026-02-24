<!-- Filtros Mejorados -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="card-title mb-3">
            <i class="ri-filter-3-line me-2"></i> Filtros Avanzados
        </h6>
        <form id="marketingFilterForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="filter-group">
                        <label class="filter-label">Año</label>
                        <select name="year" id="marketingYear" class="form-select">
                            @for ($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="filter-group">
                        <label class="filter-label">Mes</label>
                        <select name="month" id="marketingMonth" class="form-select">
                            <option value="todos">Todos los meses</option>
                            @php
                                $meses = [
                                    1 => 'Enero',
                                    2 => 'Febrero',
                                    3 => 'Marzo',
                                    4 => 'Abril',
                                    5 => 'Mayo',
                                    6 => 'Junio',
                                    7 => 'Julio',
                                    8 => 'Agosto',
                                    9 => 'Septiembre',
                                    10 => 'Octubre',
                                    11 => 'Noviembre',
                                    12 => 'Diciembre',
                                ];
                            @endphp
                            @foreach ($meses as $key => $mes)
                                <option value="{{ $key }}">{{ $mes }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="filter-group">
                        <label class="filter-label">Programa</label>
                        <select name="programa_id" id="marketingPrograma" class="form-select">
                            <option value="">Todos los programas</option>
                            @foreach (\App\Models\Programa::orderBy('nombre')->get() as $programa)
                                <option value="{{ $programa->id }}">{{ $programa->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="filter-group">
                        <label class="filter-label">Estado</label>
                        <select name="estado" id="marketingEstado" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="Inscrito">Inscrito</option>
                            <option value="Pre-Inscrito">Pre-Inscrito</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="filter-group">
                        <label class="filter-label">Buscar estudiante</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-search-line"></i>
                            </span>
                            <input type="text" name="search" id="marketingSearch" class="form-control"
                                placeholder="Nombre, apellido o carnet...">
                        </div>
                    </div>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" id="applyMarketingFilter" class="btn btn-primary flex-grow-1">
                            <i class="ri-filter-line me-1"></i> Aplicar
                        </button>
                        <button type="button" id="resetMarketingFilter" class="btn btn-outline-secondary">
                            <i class="ri-refresh-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card border-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h3 class="mb-0" id="totalInscripcionesCard">0</h3>
                        <p class="text-muted mb-0">Total Inscripciones</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                            <i class="ri-user-add-line fs-24"></i>
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
                        <h3 class="mb-0" id="totalInscritosCard">0</h3>
                        <p class="text-muted mb-0">Inscritos</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-success bg-opacity-10 text-success">
                            <i class="ri-checkbox-circle-line fs-24"></i>
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
                        <h3 class="mb-0" id="totalPreInscritosCard">0</h3>
                        <p class="text-muted mb-0">Pre-Inscritos</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                            <i class="ri-time-line fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stats-card border-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0" id="periodoActualCard">-</h4>
                        <p class="text-muted mb-0">Período Actual</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-info bg-opacity-10 text-info">
                            <i class="ri-calendar-line fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-bar-chart-line me-1"></i>
                    <span id="chartTitle">Inscripciones por Mes ({{ date('Y') }})</span>
                </h5>
                <div class="dropdown">
                    <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button"
                        data-bs-toggle="dropdown">
                        <i class="ri-more-2-line"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" id="exportChart">
                                <i class="ri-download-line me-2"></i> Exportar
                            </a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 300px;">
                    <canvas id="marketingChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-pie-chart-line me-1"></i> Top 5 Programas
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 300px;">
                    <canvas id="programasChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Inscripciones -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="ri-list-check me-1"></i> Lista de Inscripciones
            <span id="tableCount" class="badge bg-primary ms-2">0</span>
        </h5>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                    data-bs-toggle="dropdown">
                    <i class="ri-download-line me-1"></i> Exportar
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" id="exportPDF">
                            <i class="ri-file-pdf-line me-2"></i> PDF
                        </a></li>
                    <li><a class="dropdown-item" href="#" id="exportExcel">
                            <i class="ri-file-excel-line me-2"></i> Excel
                        </a></li>
                </ul>
            </div>
            <button id="refreshMarketing" class="btn btn-outline-secondary btn-sm">
                <i class="ri-refresh-line"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div id="marketingTableContainer">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2 text-muted">Cargando datos de marketing...</p>
            </div>
        </div>
        <div id="marketingPagination" class="mt-3"></div>
    </div>
</div>

<!-- Sección de documentos de inscritos -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="ri-file-list-line me-1"></i> Estado de Documentos y Pagos de Inscritos
            <span id="documentosCount" class="badge bg-primary ms-2">0</span>
        </h5>
        <div class="d-flex gap-2">
            <div class="input-group input-group-sm" style="width: 250px;">
                <input type="text" class="form-control" id="searchDocumentos" placeholder="Buscar estudiante...">
                <button class="btn btn-outline-secondary" type="button" id="searchDocumentosBtn">
                    <i class="ri-search-line"></i>
                </button>
            </div>
            <button id="loadDocumentosBtn" class="btn btn-outline-primary btn-sm">
                <i class="ri-refresh-line"></i> Cargar
            </button>
        </div>
    </div>
    <div class="card-body">
        <div id="documentosTableContainer">
            <!-- Aquí se cargará la tabla vía AJAX -->
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2 text-muted">Cargando estado de documentos y pagos...</p>
            </div>
        </div>
        <div id="documentosPagination" class="mt-3"></div>
    </div>
</div>

<!-- Modal para subir comprobante de pago -->
<div class="modal fade" id="modalSubirRespaldo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subir Comprobante de Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSubirRespaldo" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="inscripcione_id" id="respaldo_inscripcione_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Estudiante</label>
                        <p class="form-control-plaintext" id="respaldo_estudiante"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Programa</label>
                        <p class="form-control-plaintext" id="respaldo_programa"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cuotas a las que aplica el pago *</label>
                        <div id="cuotasCheckboxContainer">
                            <div class="text-muted">Cargando cuotas pendientes...</div>
                        </div>
                        <div class="form-text">Seleccione una o varias cuotas. Solo se muestran cuotas con saldo
                            pendiente.</div>
                    </div>
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Comprobante *</label>
                        <input type="file" class="form-control" name="archivo" id="respaldo_archivo"
                            accept=".pdf,.jpg,.jpeg,.png" required>
                        <div class="form-text">Formatos: PDF, JPG, PNG. Máx: 5MB.</div>
                    </div>
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" id="respaldo_observaciones" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnSubirRespaldo">Subir Comprobante</button>
                </div>
            </form>
        </div>
    </div>
</div>
