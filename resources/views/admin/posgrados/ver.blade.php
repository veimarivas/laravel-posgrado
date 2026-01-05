@extends('admin.dashboard')

@section('admin')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Detalles del Posgrado</h4>
                        <p class="text-muted mb-0">Información completa del programa académico</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.posgrados.listar') }}" class="btn btn-light btn-sm">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Volver
                        </a>
                        <button class="btn btn-info btn-sm" onclick="window.print()">
                            <i class="ri-printer-line align-middle me-1"></i> Imprimir
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Información del Posgrado -->
                    <div class="row mb-4">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div
                                        class="avatar-lg rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center">
                                        <i class="ri-book-3-line fs-24"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h3 class="mb-1">{{ $posgrado->nombre }}</h3>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span
                                            class="badge bg-{{ $posgrado->estado == 'activo' ? 'success' : 'danger' }}-subtle text-{{ $posgrado->estado == 'activo' ? 'success' : 'danger' }} fs-11">
                                            {{ ucfirst($posgrado->estado) }}
                                        </span>
                                        <span class="badge bg-info-subtle text-info fs-11">
                                            {{ $posgrado->creditaje }} Créditos
                                        </span>
                                        <span class="badge bg-warning-subtle text-warning fs-11">
                                            {{ $posgrado->carga_horaria }} Horas
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles en cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-height-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded fs-17">
                                                    <i class="ri-map-pin-2-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="text-muted mb-1">Área</h6>
                                            <h5 class="mb-0">{{ $posgrado->area?->nombre ?? 'No asignado' }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card card-height-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-success-subtle text-success rounded fs-17">
                                                    <i class="ri-contract-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="text-muted mb-1">Tipo</h6>
                                            <h5 class="mb-0">{{ $posgrado->tipo?->nombre ?? 'No asignado' }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card card-height-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-warning-subtle text-warning rounded fs-17">
                                                    <i class="ri-handshake-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="text-muted mb-1">Convenio</h6>
                                            <h5 class="mb-0">{{ $posgrado->convenio?->nombre ?? 'No asignado' }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card card-height-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-info-subtle text-info rounded fs-17">
                                                    <i class="ri-timer-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="text-muted mb-1">Duración</h6>
                                            <h5 class="mb-0">{{ $posgrado->duracion_numero }}
                                                {{ $posgrado->duracion_unidad }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional en tabs -->
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#descripcion"
                                                role="tab">
                                                <i class="ri-file-text-line align-middle me-1"></i> Descripción
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#objetivos" role="tab">
                                                <i class="ri-target-line align-middle me-1"></i> Objetivos
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#audiencia" role="tab">
                                                <i class="ri-group-line align-middle me-1"></i> Dirigido a
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="descripcion" role="tabpanel">
                                            <p class="mb-0">{{ $posgrado->descripcion ?? 'Sin descripción disponible' }}
                                            </p>
                                        </div>
                                        <div class="tab-pane" id="objetivos" role="tabpanel">
                                            <p class="mb-0">{{ $posgrado->objetivo ?? 'Sin objetivos definidos' }}</p>
                                        </div>
                                        <div class="tab-pane" id="audiencia" role="tabpanel">
                                            <p class="mb-0">{{ $posgrado->dirigido ?? 'No especificado' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ofertas Académicas -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Ofertas Académicas</h5>
                                    <span class="badge bg-primary rounded-pill">{{ $ofertas->count() }} ofertas</span>
                                </div>
                                <div class="card-body">
                                    @if ($ofertas->isEmpty())
                                        <div class="text-center py-5">
                                            <div class="avatar-lg mx-auto mb-4">
                                                <div class="avatar-title bg-light text-secondary rounded-circle">
                                                    <i class="ri-book-open-line fs-24"></i>
                                                </div>
                                            </div>
                                            <h5 class="text-muted">No hay ofertas académicas registradas</h5>
                                            <p class="text-muted mb-4">Este posgrado aún no tiene ofertas académicas
                                                activas.</p>
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col" class="text-center">Código</th>
                                                        <th scope="col" class="text-center">Sede/Sucursal</th>
                                                        <th scope="col" class="text-center">Modalidad</th>
                                                        <th scope="col" class="text-center">Gestión</th>
                                                        <th scope="col" class="text-center">Período</th>
                                                        <th scope="col" class="text-center">Fase</th>
                                                        <th scope="col" class="text-center">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($ofertas as $oferta)
                                                        <tr>
                                                            <td class="text-center">
                                                                <span
                                                                    class="fw-semibold text-primary">{{ $oferta->codigo }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <div
                                                                    class="d-flex align-items-center justify-content-center">
                                                                    <div class="flex-shrink-0 me-2">
                                                                        <div class="avatar-xs">
                                                                            <div class="avatar-title rounded-circle"
                                                                                style="background-color: {{ $oferta->sucursal->color }}; opacity: 0.2;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 text-start">
                                                                        <span
                                                                            class="fw-medium">{{ $oferta->sucursal?->sede->nombre ?? '—' }}</span>
                                                                        <p class="text-muted mb-0 fs-11">
                                                                            {{ $oferta->sucursal?->nombre ?? '—' }}</p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge bg-secondary-subtle text-secondary">
                                                                    {{ $oferta->modalidad?->nombre ?? '—' }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="fw-semibold">{{ $oferta->gestion }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="d-flex flex-column">
                                                                    <small class="text-success">
                                                                        <i
                                                                            class="ri-calendar-event-line align-middle me-1"></i>
                                                                        {{ \Carbon\Carbon::parse($oferta->fecha_inicio_programa)->format('d/m/Y') }}
                                                                    </small>
                                                                    <small class="text-muted">a</small>
                                                                    <small class="text-primary">
                                                                        <i
                                                                            class="ri-calendar-check-line align-middle me-1"></i>
                                                                        {{ \Carbon\Carbon::parse($oferta->fecha_fin_programa)->format('d/m/Y') }}
                                                                    </small>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge text-white"
                                                                    style="background-color: {{ $oferta->fase->color }};">
                                                                    {{ $oferta->fase?->nombre ?? '—' }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="hstack gap-2 justify-content-center">
                                                                    <a href="{{ route('admin.ofertas.inscritos', $oferta->id) }}"
                                                                        class="btn btn-sm btn-soft-info"
                                                                        title="Ver inscritos" data-bs-toggle="tooltip">
                                                                        <i class="ri-eye-line"></i>
                                                                    </a>
                                                                    <a href="{{ route('admin.ofertas.editar', $oferta->id) }}"
                                                                        class="btn btn-sm btn-soft-warning" title="Editar"
                                                                        data-bs-toggle="tooltip">
                                                                        <i class="ri-pencil-line"></i>
                                                                    </a>
                                                                    <a href="{{ route('admin.ofertas.vermodulos', $oferta->id) }}"
                                                                        class="btn btn-sm btn-soft-success"
                                                                        title="Ver módulos" data-bs-toggle="tooltip">
                                                                        <i class="ri-list-check"></i>
                                                                    </a>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-soft-secondary"
                                                                        title="Agregar Planes de Pago"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#modalAgregarPlanesPago"
                                                                        data-oferta-id="{{ $oferta->id }}"
                                                                        data-oferta-nombre="{{ $oferta->programa->nombre ?? 'Sin nombre' }}"
                                                                        data-oferta-codigo="{{ $oferta->codigo }}">
                                                                        <i class="ri-money-dollar-circle-line"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Estadísticas -->
                                        <div class="row mt-4">
                                            <div class="col-lg-12">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-3 col-sm-6">
                                                                <div class="text-center">
                                                                    <h4 class="text-primary mb-1">{{ $ofertas->count() }}
                                                                    </h4>
                                                                    <p class="text-muted mb-0">Total Ofertas</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 col-sm-6">
                                                                <div class="text-center">
                                                                    <h4 class="text-success mb-1">
                                                                        {{ $ofertas->where('fase_id', 1)->count() }}
                                                                    </h4>
                                                                    <p class="text-muted mb-0">En Planificación</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 col-sm-6">
                                                                <div class="text-center">
                                                                    <h4 class="text-warning mb-1">
                                                                        {{ $ofertas->where('fase_id', 2)->count() }}
                                                                    </h4>
                                                                    <p class="text-muted mb-0">En Ejecución</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 col-sm-6">
                                                                <div class="text-center">
                                                                    <h4 class="text-danger mb-1">
                                                                        {{ $ofertas->where('fase_id', 3)->count() }}
                                                                    </h4>
                                                                    <p class="text-muted mb-0">Finalizadas</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para Agregar Planes de Pago a Oferta Existente -->
    <div class="modal fade" id="modalAgregarPlanesPago" tabindex="-1" aria-labelledby="modalAgregarPlanesPagoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarPlanesPagoLabel">Agregar Planes de Pago a Oferta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarPlanesPago" class="forms-sample">
                        @csrf
                        <input type="hidden" name="oferta_id" id="oferta_id_planes">

                        <div class="row mb-3">
                            <div class="col-12">
                                <h6>Oferta: <span id="nombre_oferta_planes"></span></h6>
                                <p class="text-muted small">Código: <span id="codigo_oferta_planes"></span></p>
                            </div>
                        </div>

                        <!-- =================== PLANES DE PAGO =================== -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0"><i class="ri-money-dollar-circle-line me-1"></i> Planes de Pago
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-primary" id="add-plan-pago-offer">
                                        <i class="ri-add-line"></i> Agregar Plan
                                    </button>
                                </div>
                                <div id="planes-pago-container-offer">
                                    <!-- Se generarán dinámicamente -->
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">
                                Guardar Planes de Pago
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Inicializar tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    <script>
        // En posgrados.ver.blade.php o en un script separado
        $(document).ready(function() {
            const PLANES_PAGOS = @json($planesPagos);
            const CONCEPTOS = @json($conceptos);
            // Configurar modal de planes de pago
            $('#modalAgregarPlanesPago').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var ofertaId = button.data('oferta-id');
                var ofertaNombre = button.data('oferta-nombre');
                var ofertaCodigo = button.data('oferta-codigo');

                var modal = $(this);
                modal.find('#oferta_id_planes').val(ofertaId);
                modal.find('#nombre_oferta_planes').text(ofertaNombre);
                modal.find('#codigo_oferta_planes').text(ofertaCodigo);

                // Limpiar y generar formulario de planes
                generarPlanesPagoParaOferta();
            });

            // Generar formulario de planes de pago
            function generarPlanesPagoParaOferta() {
                const container = $('#planes-pago-container-offer');
                container.empty();

                if (!PLANES_PAGOS || PLANES_PAGOS.length === 0) {
                    container.html('<p class="text-muted">No hay planes de pago definidos.</p>');
                    return;
                }

                PLANES_PAGOS.forEach(plan => {
                    const planId = 'plan_' + plan.id;
                    let html = `
                <div class="card mb-3" id="${planId}">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>${plan.nombre}</strong>
                        <button type="button" class="btn btn-sm btn-outline-primary add-concepto-offer" data-plan-id="${plan.id}">
                            <i class="ri-add-line"></i> Agregar concepto
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="conceptos-container" id="conceptos_${plan.id}">
                            <!-- Los conceptos se agregarán aquí -->
                        </div>
                    </div>
                </div>`;
                    container.append(html);
                });
            }

            // Agregar concepto a plan
            $(document).on('click', '.add-concepto-offer', function() {
                const planId = $(this).data('plan-id');
                const container = $(`#conceptos_${planId}`);
                const conceptoIndex = container.children('.concepto-item').length;

                let conceptosOptions = '<option value="">Seleccione concepto</option>';
                CONCEPTOS.forEach(c => {
                    conceptosOptions += `<option value="${c.id}">${c.nombre}</option>`;
                });

                const html = `
            <div class="row mb-2 concepto-item">
                <div class="col-md-5">
                    <select name="planes[${planId}][${conceptoIndex}][concepto_id]" class="form-control" required>
                        ${conceptosOptions}
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="planes[${planId}][${conceptoIndex}][n_cuotas]" class="form-control" min="1" required placeholder="Cuotas">
                </div>
                <div class="col-md-3">
                    <input type="number" step="0.01" name="planes[${planId}][${conceptoIndex}][pago_bs]" class="form-control" min="0" required placeholder="Monto Bs">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-concepto-offer">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>`;

                container.append(html);
            });

            // Eliminar concepto
            $(document).on('click', '.remove-concepto-offer', function() {
                $(this).closest('.concepto-item').remove();
            });

            // Enviar formulario de planes de pago
            $('#formAgregarPlanesPago').submit(function(e) {
                e.preventDefault();

                const ofertaId = $('#oferta_id_planes').val();
                const formData = $(this).serialize();

                $.ajax({
                    url: `/admin/ofertas/${ofertaId}/agregar-planes-pago`,
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        alert(res.msg);
                        if (res.success) {
                            $('#modalAgregarPlanesPago').modal('hide');
                            $('#formAgregarPlanesPago')[0].reset();
                        }
                    },
                    error: function(xhr) {
                        let err = 'Error al registrar planes de pago.';
                        if (xhr.responseJSON?.errors) {
                            err = Object.values(xhr.responseJSON.errors).flat().join('\n');
                        }
                        alert(err);
                    }
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .card-height-100 {
            height: 100px;
        }

        .avatar-lg {
            width: 70px;
            height: 70px;
        }

        .fs-11 {
            font-size: 11px !important;
        }

        .fs-24 {
            font-size: 24px !important;
        }

        .table-nowrap th,
        .table-nowrap td {
            white-space: nowrap;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
        }

        .nav-tabs-custom .nav-link.active {
            color: #405189;
            border-bottom: 2px solid #405189;
        }
    </style>
@endpush
