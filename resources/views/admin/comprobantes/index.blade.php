@extends('admin.dashboard')

@section('admin')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

    :root {
        --comp-primary: #0f766e;
        --comp-primary-light: #f0fdfa;
        --comp-primary-dark: #0d5f59;
        --comp-accent: #7c3aed;
        --comp-accent-light: #f5f3ff;
        --comp-surface: #f8fafc;
        --comp-border: #e2e8f0;
        --comp-text: #1e293b;
        --comp-text-muted: #64748b;
        --comp-success: #059669;
        --comp-success-light: #ecfdf5;
        --comp-warning: #d97706;
        --comp-warning-light: #fffbeb;
        --comp-danger: #dc2626;
        --comp-danger-light: #fef2f2;
        --comp-info: #0891b2;
        --comp-info-light: #ecfeff;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md: 0 4px 8px -2px rgba(0,0,0,0.08), 0 2px 4px -2px rgba(0,0,0,0.04);
        --shadow-lg: 0 10px 25px -4px rgba(0,0,0,0.1), 0 4px 8px -4px rgba(0,0,0,0.06);
    }

    .comprobantes-page {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--comp-text);
        animation: compFadeIn 0.5s ease-out;
    }

    @keyframes compFadeIn {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Page Header */
    .comprobantes-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding: 22px 28px;
        background: linear-gradient(135deg, var(--comp-primary) 0%, var(--comp-primary-dark) 100%);
        border-radius: var(--radius-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .comprobantes-header::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -8%;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(124,58,237,0.12) 0%, transparent 70%);
        border-radius: 50%;
    }

    .comprobantes-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 20%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        border-radius: 50%;
    }

    .comprobantes-header h1 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.55rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.02em;
        position: relative;
        z-index: 1;
        color: white;
    }

    .comprobantes-header p {
        margin: 4px 0 0;
        opacity: 0.8;
        font-size: 0.88rem;
        position: relative;
        z-index: 1;
        color: white;
    }

    /* Filters Card */
    .filtros-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--comp-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .filtros-card .card-header {
        padding: 14px 22px;
        border-bottom: 1px dashed var(--comp-border);
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filtros-card .card-header i {
        color: var(--comp-accent);
    }

    .filtros-card .card-body {
        padding: 16px 22px 18px;
    }

    .filtros-card .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--comp-text-muted);
        margin-bottom: 5px;
    }

    .filtros-card .form-control,
    .filtros-card .form-select {
        border-radius: var(--radius-sm);
        border: 1px solid var(--comp-border);
        padding: 8px 12px;
        font-size: 0.85rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--comp-surface);
        transition: all 0.2s ease;
    }

    .filtros-card .form-control:focus,
    .filtros-card .form-select:focus {
        outline: none;
        border-color: var(--comp-primary);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        background: white;
    }

    .btn-filtrar {
        background: var(--comp-primary);
        color: white;
        border: none;
        padding: 8px 18px;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.25s ease;
    }

    .btn-filtrar:hover {
        background: var(--comp-primary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
        color: white;
    }

    /* Table Card */
    .tabla-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--comp-border);
        overflow: hidden;
        margin-top: 20px;
    }

    .tabla-card-header {
        padding: 16px 24px;
        border-bottom: 1px dashed var(--comp-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }

    .tabla-card-header h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin: 0;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tabla-card-header h5 i {
        color: var(--comp-primary);
    }

    /* Table */
    .comprobantes-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .comprobantes-table thead th {
        background: var(--comp-surface);
        padding: 12px 16px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--comp-text-muted);
        border-bottom: 1px solid var(--comp-border);
        white-space: normal;
        vertical-align: middle;
    }

    .comprobantes-table tbody tr {
        transition: background 0.15s ease;
    }

    .comprobantes-table tbody tr:hover {
        background: var(--comp-primary-light);
    }

    .comprobantes-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--comp-border);
        vertical-align: middle;
        white-space: normal;
        font-size: 0.86rem;
    }

    .comprobantes-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ID cell */
    .comp-id {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 0.88rem;
        color: var(--comp-text);
        background: var(--comp-surface);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 32px;
        border-radius: var(--radius-sm);
    }

    /* Date cell */
    .date-cell .date-main {
        font-weight: 600;
        font-size: 0.86rem;
    }

    .date-cell .date-time {
        color: var(--comp-text-muted);
        font-size: 0.75rem;
    }

    /* Student cell */
    .student-cell .student-name {
        font-weight: 600;
        font-size: 0.86rem;
    }

    .student-cell .student-carnet {
        color: var(--comp-text-muted);
        font-size: 0.75rem;
    }

    /* Cuotas cell */
    .cuotas-badge {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--comp-primary);
        background: var(--comp-primary-light);
        padding: 4px 12px;
        border-radius: 50px;
        display: inline-block;
    }

    /* Estado badge */
    .estado-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 14px;
        border-radius: 50px;
        font-size: 0.73rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        white-space: nowrap;
    }

    .estado-badge.pendiente {
        background: var(--comp-warning-light);
        color: var(--comp-warning);
    }

    .estado-badge.verificado {
        background: var(--comp-success-light);
        color: var(--comp-success);
    }

    .estado-badge.rechazado {
        background: var(--comp-danger-light);
        color: var(--comp-danger);
    }

    .estado-badge .estado-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    /* Action buttons */
    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: var(--radius-sm);
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .action-btn.view {
        background: var(--comp-info-light);
        color: var(--comp-info);
        border-color: #a5f3fc;
    }

    .action-btn.view:hover {
        background: var(--comp-info);
        color: white;
        transform: translateY(-1px);
    }

    .action-btn.verify {
        background: var(--comp-success-light);
        color: var(--comp-success);
        border-color: #a7f3d0;
    }

    .action-btn.verify:hover {
        background: var(--comp-success);
        color: white;
        transform: translateY(-1px);
    }

    .action-btn.reject {
        background: var(--comp-danger-light);
        color: var(--comp-danger);
        border-color: #fecaca;
    }

    .action-btn.reject:hover {
        background: var(--comp-danger);
        color: white;
        transform: translateY(-1px);
    }

    /* Empty state */
    .empty-state {
        padding: 52px 24px;
        text-align: center;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 16px;
        background: var(--comp-surface);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state-icon i {
        font-size: 2.2rem;
        color: #cbd5e1;
    }

    .empty-state h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        color: var(--comp-text);
        margin-bottom: 4px;
    }

    .empty-state p {
        color: var(--comp-text-muted);
        font-size: 0.88rem;
        margin: 0;
    }

    /* Pagination */
    .table-footer {
        padding: 14px 24px;
        border-top: 1px solid var(--comp-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: var(--comp-surface);
    }

    .table-footer .results-count {
        font-size: 0.82rem;
        color: var(--comp-text-muted);
    }

    .pagination .page-link {
        border-radius: var(--radius-sm) !important;
        border: 1px solid var(--comp-border);
        color: var(--comp-text-muted);
        font-size: 0.82rem;
        padding: 6px 12px;
        margin: 0 2px;
        transition: all 0.2s ease;
    }

    .pagination .page-item.active .page-link {
        background: var(--comp-primary);
        border-color: var(--comp-primary);
        color: white;
    }

    .pagination .page-link:hover {
        background: var(--comp-primary-light);
        border-color: var(--comp-primary);
        color: var(--comp-primary);
    }

    /* Modals */
    .modal-comp .modal-content {
        border: none;
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .modal-comp .modal-header {
        background: linear-gradient(135deg, var(--comp-primary) 0%, var(--comp-primary-dark) 100%);
        color: white;
        border-bottom: none;
        padding: 18px 24px;
    }

    .modal-comp .modal-header h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-comp .modal-body {
        padding: 22px 24px;
    }

    .modal-comp .modal-footer {
        border-top: 1px solid var(--comp-border);
        padding: 14px 24px;
        background: var(--comp-surface);
    }

    /* Reject modal */
    .modal-reject .modal-header {
        background: linear-gradient(135deg, var(--comp-danger) 0%, #b91c1c 100%);
    }

    .reject-icon-circle {
        width: 64px;
        height: 64px;
        margin: 0 auto 16px;
        background: var(--comp-danger-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .reject-icon-circle i {
        font-size: 1.8rem;
        color: var(--comp-danger);
    }

    /* Loading */
    .detalle-loading {
        text-align: center;
        padding: 48px 24px;
    }

    .detalle-loading .spinner-border {
        width: 2.5rem;
        height: 2.5rem;
        color: var(--comp-primary);
    }

    .detalle-loading p {
        color: var(--comp-text-muted);
        margin-top: 12px;
        font-size: 0.9rem;
    }

    /* Verification form styles */
    .comp-preview-card {
        border: 1px solid var(--comp-border);
        border-radius: var(--radius-md);
        padding: 16px;
        background: var(--comp-surface);
    }

    .comp-preview-card .section-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--comp-text-muted);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .comp-preview-card .section-label i {
        color: var(--comp-accent);
    }

    .comp-info-card {
        border: 1px solid var(--comp-border);
        border-radius: var(--radius-md);
        padding: 16px;
        background: white;
        height: 100%;
    }

    .comp-info-card .info-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--comp-text-muted);
        margin-bottom: 6px;
    }

    .cuotas-scroll-area {
        border: 1px solid var(--comp-border);
        border-radius: var(--radius-md);
        padding: 16px;
        background: white;
        max-height: 280px;
        overflow-y: auto;
    }

    .cuotas-scroll-area::-webkit-scrollbar {
        width: 6px;
    }

    .cuotas-scroll-area::-webkit-scrollbar-track {
        background: var(--comp-surface);
        border-radius: 3px;
    }

    .cuotas-scroll-area::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .cuotas-scroll-area::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .cuota-group-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
        padding: 0 4px;
    }

    .cuota-group-header .group-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .cuota-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: var(--radius-sm);
        transition: background 0.15s ease;
        cursor: pointer;
    }

    .cuota-item:hover {
        background: var(--comp-surface);
    }

    .cuota-item + .cuota-item {
        border-top: 1px solid var(--comp-border);
    }

    .cuota-item .cuota-name {
        font-weight: 600;
        font-size: 0.84rem;
    }

    .cuota-item .cuota-detail {
        font-size: 0.73rem;
        color: var(--comp-text-muted);
    }

    .form-card {
        border: 1px solid var(--comp-border);
        border-radius: var(--radius-md);
        padding: 16px;
        background: white;
    }

    .form-card .section-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--comp-text-muted);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-card .section-label i {
        color: var(--comp-accent);
    }

    .cobrador-alert {
        border-radius: var(--radius-sm);
        padding: 10px 14px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
    }

    .cobrador-alert i {
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .cobrador-alert .cobrador-name {
        font-weight: 600;
    }

    .cobrador-alert .cobrador-cargo {
        font-size: 0.75rem;
        opacity: 0.8;
    }

    .form-label-sm {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--comp-text);
        margin-bottom: 4px;
    }

    .form-control-sm-custom {
        border-radius: var(--radius-sm);
        border: 1px solid var(--comp-border);
        padding: 6px 10px;
        font-size: 0.84rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--comp-surface);
        transition: all 0.2s ease;
    }

    .form-control-sm-custom:focus {
        outline: none;
        border-color: var(--comp-primary);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        background: white;
    }

    /* Responsive */
    @media (max-width: 767.98px) {
        .comprobantes-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .filtros-card .card-body {
            padding: 14px 16px;
        }
        .tabla-card-header {
            padding: 14px 16px;
        }
        .table-footer {
            flex-direction: column;
            align-items: center;
        }
    }
</style>

<div class="container-fluid comprobantes-page">
    <!-- Page Header -->
    <div class="comprobantes-header">
        <div>
            <h1><i class="ri-shield-check-line me-2"></i>Comprobantes de Pago</h1>
            <p>Verifica y gestiona los comprobantes de pago enviados por los estudiantes</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filtros-card">
        <div class="card-header">
            <i class="ri-filter-3-line"></i>
            <span>Filtros de búsqueda</span>
        </div>
        <div class="card-body">
            <form id="formFiltrosComprobantes">
                <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="pendiente">Pendientes</option>
                            <option value="verificado">Verificados</option>
                            <option value="rechazado">Rechazados</option>
                            <option value="todos">Todos</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Buscar</label>
                        <input type="text" class="form-control" id="search" name="search"
                            placeholder="Estudiante, carnet, programa...">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn-filtrar w-100">
                            <i class="ri-search-line me-1"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de comprobantes -->
    <div class="tabla-card">
        <div class="tabla-card-header">
            <h5>
                <i class="ri-list-check"></i>
                Listado de Comprobantes
            </h5>
        </div>
        <div id="tablaComprobantesContainer">
            @include('admin.comprobantes.partials.table-body', ['comprobantes' => collect()])
        </div>
    </div>

    <!-- Modales -->
    @include('admin.comprobantes.partials.modal-verificar')
    @include('admin.comprobantes.partials.modal-rechazar')
</div>

@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @php
            $cobradorComp = \Illuminate\Support\Facades\DB::table('users')
                ->join('personas', 'users.persona_id', '=', 'personas.id')
                ->join('trabajadores', 'personas.id', '=', 'trabajadores.persona_id')
                ->join('trabajadores_cargos', 'trabajadores.id', '=', 'trabajadores_cargos.trabajadore_id')
                ->join('cargos', 'trabajadores_cargos.cargo_id', '=', 'cargos.id')
                ->where('users.id', auth()->id())
                ->where('trabajadores_cargos.principal', 1)
                ->where('trabajadores_cargos.estado', 'Vigente')
                ->select('personas.nombres', 'personas.apellido_paterno', 'cargos.nombre as cargo')
                ->first();
        @endphp
        var cobrador = @json($cobradorComp);

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if (typeof Swal === 'undefined') {
                var script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                document.head.appendChild(script);
            }
            cargarComprobantes();

            $('#formFiltrosComprobantes').on('submit', function(e) {
                e.preventDefault();
                cargarComprobantes();
            });

            function cargarComprobantes() {
                var data = $('#formFiltrosComprobantes').serialize();

                $.ajax({
                    url: "{{ route('admin.comprobantes.datos') }}",
                    type: "GET",
                    data: data,
                    success: function(response) {
                        $('#tablaComprobantesContainer').html(response.html);
                        if (response.pagination) {
                            $('#pagination-links').html(response.pagination);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        showToast('error', 'No se pudieron cargar los comprobantes');
                    }
                });
            }

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: "GET",
                    data: $('#formFiltrosComprobantes').serialize(),
                    success: function(response) {
                        $('#tablaComprobantesContainer').html(response.html);
                    },
                    error: function(xhr) {
                        console.error(xhr);
                    }
                });
            });

            $(document).on('click', '.btn-verificar', function() {
                var comprobanteId = $(this).data('id');
                abrirModalVerificar(comprobanteId);
            });

            $(document).on('click', '.btn-rechazar', function() {
                var comprobanteId = $(this).data('id');
                $('#rechazar_comprobante_id').val(comprobanteId);
                $('#modalRechazar').modal('show');
            });

            function abrirModalVerificar(id) {
                $('#modalVerificar').modal('show');
                $('#modalVerificar .modal-body').html(`
                    <div class="detalle-loading">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p>Cargando datos del comprobante...</p>
                    </div>
                `);

                $.ajax({
                    url: `/admin/comprobante/${id}/detalle`,
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            mostrarFormularioVerificacion(response);
                        } else {
                            $('#modalVerificar .modal-body').html(`
                                <div class="alert alert-danger">${response.message}</div>
                            `);
                        }
                    },
                    error: function(xhr) {
                        $('#modalVerificar .modal-body').html(`
                            <div class="alert alert-danger">Error al cargar los datos.</div>
                        `);
                    }
                });
            }

            function mostrarFormularioVerificacion(data) {
                var comp = data.comprobante;
                var estudiante = data.estudiante;
                var programa = data.programa;
                var cuotasAsociadas = data.cuotas;
                var cuotasPendientes = data.cuotas_pendientes;
                var archivoUrl = data.archivo_url;

                var ext = archivoUrl.split('?')[0].split('.').pop().toLowerCase();
                var previewHtml;
                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                    previewHtml = `<a href="${archivoUrl}" target="_blank">
                        <img src="${archivoUrl}" class="img-fluid rounded border" style="max-height:260px;width:100%;object-fit:contain;cursor:pointer;" title="Clic para ampliar">
                    </a>`;
                } else if (ext === 'pdf') {
                    previewHtml = `<embed src="${archivoUrl}" type="application/pdf" width="100%" height="260px" class="rounded border">
                        <div class="mt-1 text-end">
                            <a href="${archivoUrl}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="ri-external-link-line me-1"></i>Abrir PDF
                            </a>
                        </div>`;
                } else {
                    previewHtml = `<div class="d-flex align-items-center justify-content-center border rounded bg-light" style="height:80px;">
                        <a href="${archivoUrl}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="ri-download-line me-1"></i>Descargar archivo
                        </a>
                    </div>`;
                }

                var iconos = {
                    'Matrícula':    'ri-graduation-cap-line',
                    'Colegiatura':  'ri-book-open-line',
                    'Certificación':'ri-award-line',
                    'Otros':        'ri-file-list-line',
                };
                var orden = ['Matrícula', 'Colegiatura', 'Certificación', 'Otros'];

                function buildGrupos(cuotas) {
                    var g = {};
                    cuotas.forEach(function(c) {
                        var t = c.tipo || 'Otros';
                        if (!g[t]) g[t] = [];
                        g[t].push(c);
                    });
                    return g;
                }

                function renderGrupo(grupos, checked) {
                    var html = '';
                    orden.forEach(function(tipo) {
                        if (!grupos[tipo] || grupos[tipo].length === 0) return;
                        var icono = iconos[tipo] || 'ri-file-list-line';
                        var total = grupos[tipo].reduce(function(s, c) { return s + c.pendiente_bs; }, 0);
                        html += `<div class="mb-2">
                            <div class="cuota-group-header">
                                <div class="group-title">
                                    <i class="${icono}" style="color:var(--comp-accent);"></i>
                                    <span>${tipo}</span>
                                </div>
                                <span class="cuotas-badge" style="font-size:.72rem;">Pendiente: ${total.toFixed(2)} Bs</span>
                            </div>
                            <div>`;
                        grupos[tipo].forEach(function(cuota) {
                            var uid = checked ? 'cuota_' + cuota.id : 'cuota_ex_' + cuota.id;
                            html += `
                            <label class="cuota-item">
                                <input class="form-check-input cuota-checkbox flex-shrink-0" type="checkbox"
                                    name="cuota_ids[]" value="${cuota.id}" id="${uid}" ${checked ? 'checked' : ''}>
                                <div class="flex-grow-1 min-w-0">
                                    <span class="cuota-name">${cuota.nombre}</span>
                                    <span class="cuota-detail">Pendiente: <strong>${parseFloat(cuota.pago_pendiente_bs).toFixed(2)} Bs</strong> / Total: ${parseFloat(cuota.pago_total_bs).toFixed(2)} Bs</span>
                                </div>
                            </label>`;
                        });
                        html += `</div></div>`;
                    });
                    return html;
                }

                var gruposAsociadas  = buildGrupos(cuotasAsociadas);
                var gruposPendientes = buildGrupos(cuotasPendientes);

                var cuotasHtml = renderGrupo(gruposAsociadas, true);

                if (cuotasPendientes && cuotasPendientes.length > 0) {
                    cuotasHtml += `<div class="border-top pt-2 mt-2">
                        <p class="text-muted small mb-2"><i class="ri-add-circle-line me-1"></i>Otras cuotas pendientes (opcional):</p>
                        ${renderGrupo(gruposPendientes, false)}
                    </div>`;
                }

                var html = `
                <div class="row g-3">
                    <div class="col-md-5">
                        <div class="comp-preview-card mb-2">
                            <div class="section-label"><i class="ri-image-line"></i>Comprobante</div>
                            ${previewHtml}
                        </div>
                        <div class="comp-info-card">
                            <div class="section-label" style="margin-bottom:10px;"><i class="ri-user-line"></i>Estudiante</div>
                            <div class="mb-2">
                                <div class="info-label">Nombre</div>
                                <strong style="font-size:.88rem;">${estudiante.nombre}</strong>
                            </div>
                            <div class="mb-2">
                                <div class="info-label">Carnet</div>
                                <span style="font-size:.84rem;">${estudiante.carnet}</span>
                            </div>
                            <div>
                                <div class="info-label">Programa</div>
                                <span style="font-size:.84rem;">${programa}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="cuotas-scroll-area mb-3">
                            <div class="section-label" style="margin-bottom:10px;"><i class="ri-file-list-3-line"></i>Cuotas a las que aplica el pago</div>
                            ${cuotasHtml}
                        </div>

                        <div class="form-card">
                            <div class="section-label"><i class="ri-money-dollar-circle-line"></i>Datos del Pago</div>
                            ${cobrador
                                ? `<div class="cobrador-alert" style="background:var(--comp-success-light);color:var(--comp-success);">
                                    <i class="ri-user-star-line"></i>
                                    <div>
                                        <div class="cobrador-name">${cobrador.nombres} ${cobrador.apellido_paterno}</div>
                                        <div class="cobrador-cargo">Cobrador — ${cobrador.cargo}</div>
                                    </div>
                                  </div>`
                                : `<div class="cobrador-alert" style="background:var(--comp-warning-light);color:var(--comp-warning);">
                                    <i class="ri-alert-line"></i>
                                    <div>No se pudo identificar al cobrador. Verifique su cargo vigente.</div>
                                   </div>`
                            }
                            <form id="formVerificarPago">
                                @csrf
                                <input type="hidden" name="comprobante_id" value="${comp.id}">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label-sm">Monto a pagar (Bs) *</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm-custom w-100" id="monto_pago" name="monto_pago" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label-sm">Descuento (Bs)</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm-custom w-100" id="descuento_bs" name="descuento_bs" value="0">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label-sm">Tipo de pago *</label>
                                        <select class="form-select form-select-sm form-control-sm-custom" id="tipo_pago" name="tipo_pago" required>
                                            <option value="">Seleccionar</option>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="Depósito">Depósito</option>
                                            <option value="Tarjeta">Tarjeta</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label-sm">Fecha de pago *</label>
                                        <input type="date" class="form-control form-control-sm-custom w-100" id="fecha_pago" name="fecha_pago" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-12" id="campo_caja" style="display:none;">
                                        <label class="form-label-sm">Caja *</label>
                                        <select class="form-select form-select-sm form-control-sm-custom" id="caja_id" name="caja_id">
                                            <option value="">— Seleccionar caja —</option>
                                            @foreach (\App\Models\Caja::where('activa', true)->with('sucursal')->get() as $caja)
                                                <option value="{{ $caja->id }}">
                                                    {{ $caja->nombre }} - {{ $caja->sucursal->nombre ?? 'Sin sucursal' }} (Saldo: {{ number_format($caja->saldo_actual, 2) }} Bs)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12" id="campo_cuenta_bancaria" style="display:none;">
                                        <label class="form-label-sm">Cuenta Bancaria *</label>
                                        <select class="form-select form-select-sm form-control-sm-custom" id="cuenta_bancaria_id" name="cuenta_bancaria_id">
                                            <option value="">— Seleccionar cuenta —</option>
                                            @foreach (\App\Models\CuentasBancarias::where('activa', true)->with('banco')->get() as $cuenta)
                                                <option value="{{ $cuenta->id }}">
                                                    {{ $cuenta->banco->nombre ?? 'Sin banco' }} - {{ $cuenta->numero_cuenta }} ({{ $cuenta->moneda }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12" id="campo_comprobante" style="display:none;">
                                        <label class="form-label-sm">N° Comprobante</label>
                                        <input type="text" class="form-control form-control-sm-custom w-100" id="n_comprobante" name="n_comprobante" placeholder="Ej: TRF-0012345">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label-sm">Observaciones</label>
                                        <textarea class="form-control form-control-sm-custom w-100" id="observaciones" name="observaciones" rows="2"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>`;

                $('#modalVerificar .modal-body').html(html);
                $('#modalVerificar .modal-footer').html(`
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-sm" id="btnConfirmarVerificar"
                            style="background:var(--comp-success);color:white;">
                        <i class="ri-checkbox-circle-line me-1"></i>Verificar y Registrar Pago
                    </button>`);

                $('#tipo_pago').on('change', togglePaymentFields);
                togglePaymentFields();

                $('#btnConfirmarVerificar').off('click').on('click', function() {
                    var formData = $('#formVerificarPago').serializeArray();
                    var cuotaIds = [];
                    $('.cuota-checkbox:checked').each(function() {
                        cuotaIds.push($(this).val());
                    });

                    if (cuotaIds.length === 0) {
                        showToast('warning', 'Debe seleccionar al menos una cuota');
                        return;
                    }

                    cuotaIds.forEach(function(id) {
                        formData.push({ name: 'cuota_ids[]', value: id });
                    });

                    var tipoPago = $('#tipo_pago').val();
                    if (!tipoPago) {
                        showToast('warning', 'Debe seleccionar el tipo de pago');
                        return;
                    }
                    if (tipoPago === 'Efectivo') {
                        if (!$('#caja_id').val()) {
                            showToast('warning', 'Debe seleccionar una caja');
                            return;
                        }
                        formData.push({ name: 'caja_id', value: $('#caja_id').val() });
                    } else if (['Transferencia', 'Depósito', 'Tarjeta'].includes(tipoPago)) {
                        if (!$('#cuenta_bancaria_id').val()) {
                            showToast('warning', 'Debe seleccionar una cuenta bancaria');
                            return;
                        }
                        formData.push({ name: 'cuenta_bancaria_id', value: $('#cuenta_bancaria_id').val() });
                        formData.push({ name: 'n_comprobante', value: $('#n_comprobante').val() });
                    }

                    $.ajax({
                        url: `/admin/comprobante/${comp.id}/verificar`,
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                showToast('success', response.message);
                                $('#modalVerificar').modal('hide');
                                cargarComprobantes();
                            } else {
                                showToast('error', response.message);
                            }
                        },
                        error: function(xhr) {
                            var errors = xhr.responseJSON.errors;
                            var msg = 'Error al procesar';
                            if (errors) {
                                msg = Object.values(errors).flat().join('<br>');
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            showToast('error', msg);
                        }
                    });
                });
            }

            function togglePaymentFields() {
                var tipoPago = $('#tipo_pago').val();
                $('#campo_caja').hide();
                $('#campo_cuenta_bancaria').hide();
                $('#campo_comprobante').hide();

                $('#caja_id').prop('required', false);
                $('#cuenta_bancaria_id').prop('required', false);
                $('#n_comprobante').prop('required', false);

                if (tipoPago === 'Efectivo') {
                    $('#campo_caja').show();
                    $('#caja_id').prop('required', true);
                } else if (['Transferencia', 'Depósito', 'Tarjeta'].includes(tipoPago)) {
                    $('#campo_cuenta_bancaria').show();
                    $('#campo_comprobante').show();
                    $('#cuenta_bancaria_id').prop('required', true);
                    $('#n_comprobante').prop('required', true);
                }
            }

            $('#formRechazar').on('submit', function(e) {
                e.preventDefault();
                var comprobanteId = $('#rechazar_comprobante_id').val();
                var motivo = $('#motivo_rechazo').val();

                $.ajax({
                    url: `/admin/comprobante/${comprobanteId}/rechazar`,
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        motivo_rechazo: motivo
                    },
                    success: function(response) {
                        if (response.success) {
                            showToast('success', response.message);
                            $('#modalRechazar').modal('hide');
                            cargarComprobantes();
                        } else {
                            showToast('error', response.message);
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var msg = 'Error al rechazar';
                        if (errors) {
                            msg = Object.values(errors).flat().join('<br>');
                        }
                        showToast('error', msg);
                    }
                });
            });

            function showToast(type, message) {
                var config = {
                    success: { icon: 'ri-checkbox-circle-fill', bgClass: 'bg-success', title: 'Éxito' },
                    error: { icon: 'ri-close-circle-fill', bgClass: 'bg-danger', title: 'Error' },
                    warning: { icon: 'ri-alert-fill', bgClass: 'bg-warning', title: 'Advertencia' },
                    info: { icon: 'ri-information-fill', bgClass: 'bg-info', title: 'Información' }
                };
                var toastConfig = config[type] || config.info;
                var toastId = 'toast-' + Date.now();
                var toastHtml = `
                    <div id="${toastId}" class="toast ${toastConfig.bgClass} text-white" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header ${toastConfig.bgClass} text-white border-bottom-0">
                            <i class="${toastConfig.icon} me-2"></i>
                            <strong class="me-auto">${toastConfig.title}</strong>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body d-flex align-items-center">
                            <i class="${toastConfig.icon} me-2 fs-5"></i>
                            <span class="flex-grow-1">${message}</span>
                        </div>
                    </div>`;
                var container = document.getElementById('toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'toast-container';
                    container.className = 'toast-container position-fixed top-0 end-0 p-3';
                    document.body.appendChild(container);
                }
                container.insertAdjacentHTML('afterbegin', toastHtml);
                var toastElement = document.getElementById(toastId);
                var toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 });
                toast.show();
                toastElement.addEventListener('hidden.bs.toast', function() { this.remove(); });
            }
        });
    </script>
@endpush
