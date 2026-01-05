@extends('admin.dashboard')

@section('admin')
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">Detalle del Estudiante</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.estudiantes.listar') }}">Estudiantes</a></li>
                            <li class="breadcrumb-item active">{{ $estudiante->persona->nombres ?? 'Estudiante' }}</li>
                        </ol>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.estudiantes.listar') }}" class="btn btn-light btn-sm">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Volver
                    </a>
                    <a href="{{ route('admin.estudiantes.editar', $estudiante->id) }}" class="btn btn-warning btn-sm">
                        <i class="ri-edit-line align-middle me-1"></i> Editar
                    </a>
                    <button class="btn btn-primary btn-sm" onclick="window.print()">
                        <i class="ri-printer-line align-middle me-1"></i> Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Info -->
    @include('admin.estudiantes.partials.detalle-header')

    <!-- Pestañas de Información -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#tab-personal" role="tab">
                                <i class="ri-user-3-line align-middle me-1"></i> Personal
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-documentos" role="tab">
                                <i class="ri-file-text-line align-middle me-1"></i> Documentos
                                @php
                                    $documentosPendientes = [];
                                    if (
                                        !$estudiante->persona->carnet_verificado &&
                                        $estudiante->persona->fotografia_carnet
                                    ) {
                                        $documentosPendientes[] = 'Carnet';
                                    }
                                    if (
                                        !$estudiante->persona->certificado_nacimiento_verificado &&
                                        $estudiante->persona->fotografia_certificado_nacimiento
                                    ) {
                                        $documentosPendientes[] = 'Certificado Nacimiento';
                                    }
                                    $estudioPrincipal = $estudiante->persona->estudios->where('principal', 1)->first();
                                    if ($estudioPrincipal) {
                                        if (
                                            !$estudioPrincipal->documento_academico_verificado &&
                                            $estudioPrincipal->documento_academico
                                        ) {
                                            $documentosPendientes[] = 'Documento Académico';
                                        }
                                        if (
                                            !$estudioPrincipal->documento_provision_verificado &&
                                            $estudioPrincipal->documento_provision_nacional
                                        ) {
                                            $documentosPendientes[] = 'Prov. Nacional';
                                        }
                                    }
                                @endphp
                                @if (count($documentosPendientes) > 0)
                                    <span class="badge bg-danger ms-1">{{ count($documentosPendientes) }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-academico" role="tab">
                                <i class="ri-graduation-cap-line align-middle me-1"></i> Académico
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-financiero" role="tab">
                                <i class="ri-money-dollar-circle-line align-middle me-1"></i> Financiero
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-pagos" role="tab">
                                <i class="ri-history-line align-middle me-1"></i> Historial de Pagos
                                @if ($pagosEstudiante && $pagosEstudiante->count() > 0)
                                    <span class="badge bg-primary ms-1">{{ $pagosEstudiante->count() }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Pestaña 1: Personal -->
                        <div class="tab-pane fade show active" id="tab-personal" role="tabpanel">
                            @include('admin.estudiantes.partials.detalle-personal')
                        </div>

                        <!-- Pestaña 2: Documentos -->
                        <div class="tab-pane fade" id="tab-documentos" role="tabpanel">
                            @include('admin.estudiantes.partials.detalle-documentos')
                        </div>

                        <!-- Pestaña 3: Académico -->
                        <div class="tab-pane fade" id="tab-academico" role="tabpanel">
                            @include('admin.estudiantes.partials.detalle-academico')
                        </div>

                        <!-- Pestaña 4: Financiero -->
                        <div class="tab-pane fade" id="tab-financiero" role="tabpanel">
                            @include('admin.estudiantes.partials.detalle-financiero')
                        </div>

                        <!-- Pestaña 5: Historial -->
                        <div class="tab-pane fade" id="tab-pagos" role="tabpanel">
                            @include('admin.estudiantes.partials.detalle-historial-pagos')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales -->
    @include('admin.estudiantes.partials.modales-subida-documentos')
    @include('admin.estudiantes.partials.modal-preview-documento')
    @include('admin.estudiantes.partials.modal-eliminacion')
@endsection

@push('script')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @include('admin.estudiantes.partials.scripts-documentos')
    @include('admin.estudiantes.partials.scripts-tabs')

    @include('admin.estudiantes.partials.estilos-tabs')
    @include('admin.estudiantes.partials.scripts-pagos')
@endpush
{{-- En estudiantes.detalle.blade.php --}}
@push('modales')
    <!-- Modal para Pagar Cuota -->
    <div class="modal fade" id="modalPagarCuota" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formPagarCuota">
                    @csrf
                    <input type="hidden" id="cuota_id" name="cuota_id">
                    <input type="hidden" id="estudiante_id" name="estudiante_id">

                    <div class="modal-body">
                        <!-- Contenido del modal -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Cuota:</strong> <span id="info-cuota-nombre"></span>
                                            </p>
                                            <p class="mb-1"><strong>Programa:</strong> <span
                                                    id="info-cuota-programa"></span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Total Cuota:</strong> <span id="info-cuota-total"
                                                    class="text-primary"></span> Bs</p>
                                            <p class="mb-1"><strong>Saldo Pendiente:</strong> <span
                                                    id="info-cuota-pendiente" class="text-danger"></span> Bs</p>
                                            <p class="mb-0"><strong>Saldo Pagado:</strong> <span id="info-cuota-pagado"
                                                    class="text-success"></span> Bs</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="monto_pago" class="form-label">Monto a Pagar (Bs) *</label>
                                    <input type="number" step="0.01" class="form-control" id="monto_pago"
                                        name="monto_pago" required>
                                    <div class="form-text">Máximo: <span id="maximo_pago"
                                            class="text-danger fw-bold">0.00</span> Bs</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="descuento" class="form-label">Descuento (Bs)</label>
                                    <input type="number" step="0.01" class="form-control" id="descuento"
                                        name="descuento" value="0">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tipo_pago" class="form-label">Tipo de Pago *</label>
                                    <select class="form-select" id="tipo_pago" name="tipo_pago" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Depósito">Depósito</option>
                                        <option value="Tarjeta">Tarjeta</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_pago" class="form-label">Fecha de Pago *</label>
                                    <input type="date" class="form-control" id="fecha_pago" name="fecha_pago"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
                                </div>
                            </div>

                            <!-- Resumen del pago en tiempo real -->
                            <div class="col-12">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">Resumen del Pago</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <p class="mb-1 text-muted">Monto a Pagar</p>
                                                <h5 class="text-primary" id="resumen-monto">0.00 Bs</h5>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-1 text-muted">Descuento</p>
                                                <h5 class="text-warning" id="resumen-descuento">0.00 Bs</h5>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-1 text-muted">Total a Pagar</p>
                                                <h5 class="text-success" id="resumen-total">0.00 Bs</h5>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success" id="progreso-pago"
                                                        role="progressbar" style="width: 0%"></div>
                                                </div>
                                                <small class="text-muted mt-1 d-block text-center">
                                                    <span id="texto-progreso">0% del saldo pendiente</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Registrar Pago</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar recibo -->
    <div class="modal fade" id="modalReciboGenerado" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pago Registrado Exitosamente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="ri-checkbox-circle-line text-success display-4"></i>
                    </div>
                    <h4 class="text-success mb-3">¡Pago Registrado!</h4>
                    <p class="mb-1">Recibo N°: <strong id="recibo-numero"></strong></p>
                    <p class="mb-1">Monto: <strong id="recibo-monto"></strong> Bs</p>
                    <p class="mb-3">Fecha: <strong id="recibo-fecha"></strong></p>
                    <div class="alert alert-info">
                        <i class="ri-information-line me-1"></i>
                        El recibo se ha generado correctamente. Puede descargarlo ahora.
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="#" id="descargar-recibo" class="btn btn-primary" target="_blank">
                        <i class="ri-download-line me-1"></i> Descargar Recibo
                    </a>
                    <button type="button" class="btn btn-success" onclick="location.reload()">
                        <i class="ri-refresh-line me-1"></i> Actualizar Página
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush
