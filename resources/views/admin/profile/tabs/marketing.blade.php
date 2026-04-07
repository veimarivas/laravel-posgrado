{{-- Filtros --}}
<div class="mkt-filters-card mb-4">
    <div class="mkt-filters-header">
        <i class="ri-filter-3-line"></i>
        <span>Filtros Avanzados</span>
    </div>
    <div class="mkt-filters-body">
        <form id="marketingFilterForm">
            <div class="row g-2 align-items-end">
                <div class="col-xl-2 col-md-3 col-sm-6">
                    <label class="mkt-label">Año</label>
                    <select name="year" id="marketingYear" class="mkt-select">
                        @for ($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-xl-2 col-md-3 col-sm-6">
                    <label class="mkt-label">Mes</label>
                    <select name="month" id="marketingMonth" class="mkt-select">
                        <option value="todos">Todos los meses</option>
                        @php $meses=[1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre']; @endphp
                        @foreach ($meses as $k => $m)
                            <option value="{{ $k }}">{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-2 col-md-3 col-sm-6">
                    <label class="mkt-label">Estado</label>
                    <select name="estado" id="marketingEstado" class="mkt-select">
                        <option value="">Todos</option>
                        <option value="Inscrito">Inscrito</option>
                        <option value="Pre-Inscrito">Pre-Inscrito</option>
                    </select>
                </div>
                <div class="col-xl-3 col-md-3 col-sm-6">
                    <label class="mkt-label">Programa</label>
                    <select name="programa_id" id="marketingPrograma" class="mkt-select">
                        <option value="">Todos los programas</option>
                        @foreach (\App\Models\Programa::orderBy('nombre')->get() as $programa)
                            <option value="{{ $programa->id }}">{{ $programa->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-2 col-md-8 col-sm-8">
                    <label class="mkt-label">Buscar</label>
                    <div class="mkt-search-group">
                        <i class="ri-search-line"></i>
                        <input type="text" name="search" id="marketingSearch" class="mkt-search-input" placeholder="Nombre o carnet...">
                    </div>
                </div>
                <div class="col-xl-1 col-md-4 col-sm-4 d-flex align-items-end gap-1">
                    <button type="submit" id="applyMarketingFilter" class="mkt-btn-filter flex-grow-1">
                        <i class="ri-filter-line"></i>
                    </button>
                    <button type="button" id="resetMarketingFilter" class="mkt-btn-reset">
                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="row g-2 mb-4">
    <div class="col-md-3">
        <div class="mkt-stat-card">
            <div class="mkt-stat-body">
                <div class="flex-grow-1">
                    <div class="mkt-stat-value" id="totalInscripcionesCard">0</div>
                    <p class="mkt-stat-label">Total Inscripciones</p>
                </div>
                <div class="mkt-stat-icon" style="background:rgba(15,118,110,0.1);color:var(--conc-primary);">
                    <i class="ri-user-add-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mkt-stat-card">
            <div class="mkt-stat-body">
                <div class="flex-grow-1">
                    <div class="mkt-stat-value" id="totalInscritosCard">0</div>
                    <p class="mkt-stat-label">Inscritos</p>
                </div>
                <div class="mkt-stat-icon" style="background:rgba(16,185,129,0.1);color:var(--conc-success);">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mkt-stat-card">
            <div class="mkt-stat-body">
                <div class="flex-grow-1">
                    <div class="mkt-stat-value" id="totalPreInscritosCard">0</div>
                    <p class="mkt-stat-label">Pre-Inscritos</p>
                </div>
                <div class="mkt-stat-icon" style="background:rgba(245,158,11,0.1);color:var(--conc-accent);">
                    <i class="ri-time-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mkt-stat-card">
            <div class="mkt-stat-body">
                <div class="flex-grow-1">
                    <div class="mkt-stat-value" style="font-size:1.1rem;" id="periodoActualCard">-</div>
                    <p class="mkt-stat-label">Período Actual</p>
                </div>
                <div class="mkt-stat-icon" style="background:rgba(100,116,139,0.1);color:var(--conc-text-muted);">
                    <i class="ri-calendar-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="mkt-chart-card">
            <div class="mkt-chart-header">
                <h5 class="mkt-chart-title">
                    <i class="ri-bar-chart-line"></i>
                    <span id="chartTitle">Inscripciones por Mes ({{ date('Y') }})</span>
                </h5>
                <div class="dropdown">
                    <button class="mkt-dropdown-btn" type="button" data-bs-toggle="dropdown">
                        <i class="ri-more-2-line"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" id="exportChart">
                                <i class="ri-download-line me-2"></i> Exportar
                            </a></li>
                    </ul>
                </div>
            </div>
            <div class="mkt-chart-body">
                <div class="chart-container" style="height:300px;">
                    <canvas id="marketingChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mkt-chart-card">
            <div class="mkt-chart-header">
                <h5 class="mkt-chart-title">
                    <i class="ri-pie-chart-line"></i> Top 5 Programas
                </h5>
            </div>
            <div class="mkt-chart-body">
                <div class="chart-container" style="height:300px;">
                    <canvas id="programasChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Inscripciones -->
<div class="mkt-table-card">
    <div class="mkt-table-header">
        <h5 class="mkt-table-title">
            <i class="ri-list-check"></i> Lista de Inscripciones
            <span id="tableCount" class="mkt-badge">0</span>
        </h5>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="mkt-btn-outline dropdown-toggle" type="button" data-bs-toggle="dropdown">
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
            <button id="refreshMarketing" class="mkt-btn-outline">
                <i class="ri-refresh-line"></i>
            </button>
        </div>
    </div>
    <div class="mkt-table-body">
        <div id="marketingTableContainer">
            <div class="text-center py-5">
                <div class="spinner-border" role="status" style="color:var(--conc-primary);">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2" style="color:var(--conc-text-muted);">Cargando datos de marketing...</p>
            </div>
        </div>
        <div id="marketingPagination" class="mt-3"></div>
    </div>
</div>

<!-- Sección de documentos de inscritos -->
<div class="mkt-table-card mt-4">
    <div class="mkt-table-header">
        <h5 class="mkt-table-title">
            <i class="ri-file-list-line"></i> Estado de Documentos y Pagos de Inscritos
            <span id="documentosCount" class="mkt-badge">0</span>
        </h5>
        <div class="d-flex gap-2">
            <div class="mkt-search-group" style="width:250px;">
                <i class="ri-search-line"></i>
                <input type="text" class="mkt-search-input" id="searchDocumentos" placeholder="Buscar estudiante...">
            </div>
            <button id="searchDocumentosBtn" class="mkt-btn-outline">
                <i class="ri-search-line"></i>
            </button>
            <button id="loadDocumentosBtn" class="mkt-btn-primary btn-sm">
                <i class="ri-refresh-line me-1"></i> Cargar
            </button>
        </div>
    </div>
    <div class="mkt-table-body">
        <div id="documentosTableContainer">
            <div class="text-center py-5">
                <div class="spinner-border" role="status" style="color:var(--conc-primary);">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2" style="color:var(--conc-text-muted);">Cargando estado de documentos y pagos...</p>
            </div>
        </div>
        <div id="documentosPagination" class="mt-3"></div>
    </div>
</div>

<!-- Modal para subir comprobante de pago -->
<div class="modal fade modal-conc" id="modalSubirRespaldo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-upload-cloud-line me-2"></i>Subir Comprobante de Pago
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSubirRespaldo" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="inscripcione_id" id="respaldo_inscripcione_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="conc-form-label">Estudiante</label>
                        <p class="form-control-plaintext fw-medium" id="respaldo_estudiante"></p>
                    </div>
                    <div class="mb-3">
                        <label class="conc-form-label">Programa</label>
                        <p class="form-control-plaintext" id="respaldo_programa"></p>
                    </div>
                    <div class="mb-3">
                        <label class="conc-form-label">Cuotas a las que aplica el pago *</label>
                        <div id="cuotasCheckboxContainer">
                            <div style="color:var(--conc-text-muted);">Cargando cuotas pendientes...</div>
                        </div>
                        <div class="form-text">Seleccione una o varias cuotas. Solo se muestran cuotas con saldo pendiente.</div>
                    </div>
                    <div class="mb-3">
                        <label for="archivo" class="conc-form-label">Comprobante *</label>
                        <input type="file" class="conc-form-control" name="archivo" id="respaldo_archivo"
                            accept=".pdf,.jpg,.jpeg,.png" required>
                        <div class="form-text">Formatos: PDF, JPG, PNG. Máx: 5MB.</div>
                    </div>
                    <div class="mb-3">
                        <label for="observaciones" class="conc-form-label">Observaciones</label>
                        <textarea class="conc-form-control" name="observaciones" id="respaldo_observaciones" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-sm" id="btnSubirRespaldo"
                            style="background:var(--conc-primary);color:white;">
                        <i class="ri-upload-cloud-line me-1"></i> Subir Comprobante
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
