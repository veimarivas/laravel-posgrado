@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-0">Detalle de Sucursal</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.sedes.listar') }}">Sedes</a></li>
                                <li class="breadcrumb-item active">Sucursal: {{ $sucursal->nombre }}</li>
                            </ol>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('admin.sedes.listar') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de la Sucursal -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Información de la Sucursal</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Nombre:</strong></label>
                                    <p>{{ $sucursal->nombre }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Sede:</strong></label>
                                    <p>{{ $sucursal->sede->nombre ?? 'No asignada' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Dirección:</strong></label>
                                    <p>{{ $sucursal->direccion }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Color:</strong></label>
                                    <div class="d-flex align-items-center">
                                        <div class="color-preview me-2"
                                            style="width: 20px; height: 20px; background-color: {{ $sucursal->color }}; border: 1px solid #ddd;">
                                        </div>
                                        <span>{{ $sucursal->color }}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Coordenadas:</strong></label>
                                    <p>
                                        @if ($sucursal->latitud && $sucursal->longitud)
                                            Lat: {{ $sucursal->latitud }}, Long: {{ $sucursal->longitud }}
                                        @else
                                            No registradas
                                        @endif
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Estado:</strong></label>
                                    <p>
                                        <span class="badge bg-success">Activa</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cuentas Bancarias -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Cuentas Bancarias</h5>
                        @if (Auth::guard('web')->user()->can('cuentas-bancarias.registrar'))
                            <a href="{{ route('admin.cuentas-bancarias.listar') }}" class="btn btn-primary btn-sm">
                                <i class="ri-add-line me-1"></i> Agregar Cuenta
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if ($sucursal->cuentas_bancarias && $sucursal->cuentas_bancarias->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Banco</th>
                                            <th>Número de Cuenta</th>
                                            <th>Tipo</th>
                                            <th>Moneda</th>
                                            <th>Saldo Actual</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sucursal->cuentas_bancarias as $cuenta)
                                            <tr>
                                                <td>{{ $cuenta->banco->nombre ?? 'N/A' }}</td>
                                                <td>{{ $cuenta->numero_cuenta }}</td>
                                                <td>
                                                    @if ($cuenta->tipo_cuenta == 'ahorro')
                                                        <span class="badge bg-info">Ahorro</span>
                                                    @elseif($cuenta->tipo_cuenta == 'corriente')
                                                        <span class="badge bg-primary">Corriente</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $cuenta->tipo_cuenta }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $cuenta->moneda }}</td>
                                                <td>
                                                    <strong
                                                        class="{{ $cuenta->saldo_actual >= 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ $cuenta->moneda == 'BS' ? 'Bs' : '$' }}
                                                        {{ number_format($cuenta->saldo_actual, 2) }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    @if ($cuenta->activa)
                                                        <span class="badge bg-success">Activa</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactiva</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.cuentas-bancarias.ver', $cuenta->id) }}"
                                                        class="btn btn-sm btn-info" title="Ver Detalle">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ri-bank-line fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No hay cuentas bancarias registradas para esta sucursal.</p>
                                @if (Auth::guard('web')->user()->can('cuentas-bancarias.registrar'))
                                    <a href="{{ route('admin.cuentas-bancarias.listar') }}" class="btn btn-primary mt-2">
                                        <i class="ri-add-line me-1"></i> Registrar Cuenta Bancaria
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Cajas -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Cajas</h5>
                        @if (Auth::guard('web')->user()->can('cajas.registrar'))
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalRegistrarCaja">
                                <i class="ri-add-line me-1"></i> Registrar Caja
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if ($sucursal->cajas && $sucursal->cajas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Responsable</th>
                                            <th>Descripción</th>
                                            <th>Moneda</th>
                                            <th>Saldo Inicial</th>
                                            <th>Saldo Actual</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sucursal->cajas as $caja)
                                            <tr>
                                                <td>{{ $caja->nombre }}</td>
                                                <td>
                                                    {{ $caja->responsable->trabajador->persona->nombres ?? 'N/A' }}
                                                    {{ $caja->responsable->trabajador->persona->apellido_paterno ?? '' }}
                                                </td>
                                                <td>{{ $caja->descripcion ?: 'Sin descripción' }}</td>
                                                <td>{{ $caja->moneda }}</td>
                                                <td>
                                                    {{ $caja->moneda == 'BS' ? 'Bs' : '$' }}
                                                    {{ number_format($caja->saldo_inicial, 2) }}
                                                </td>
                                                <td>
                                                    <strong
                                                        class="{{ $caja->saldo_actual >= 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ $caja->moneda == 'BS' ? 'Bs' : '$' }}
                                                        {{ number_format($caja->saldo_actual, 2) }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    @if ($caja->activa)
                                                        <span class="badge bg-success">Activa</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactiva</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-info verCajaBtn"
                                                        title="Ver Detalle" data-id="{{ $caja->id }}">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                    @if (Auth::guard('web')->user()->can('cajas.editar'))
                                                        <a href="#" class="btn btn-sm btn-warning" title="Editar">
                                                            <i class="ri-edit-line"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ri-cash-line fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No hay cajas registradas para esta sucursal.</p>
                                @if (Auth::guard('web')->user()->can('cajas.registrar'))
                                    <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal"
                                        data-bs-target="#modalRegistrarCaja">
                                        <i class="ri-add-line me-1"></i> Registrar Caja
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Caja -->
    @if (Auth::guard('web')->user()->can('cajas.registrar'))
        <div class="modal fade" id="modalRegistrarCaja" tabindex="-1" aria-labelledby="modalRegistrarCajaLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary bg-soft">
                        <h5 class="modal-title text-white" id="modalRegistrarCajaLabel">
                            <i class="ri-add-line me-2"></i>Registrar Nueva Caja
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formRegistrarCaja" class="forms-sample">
                            @csrf
                            <input type="hidden" name="sucursale_id" value="{{ $sucursal->id }}">

                            <div class="mb-3">
                                <label class="form-label">Nombre de la Caja <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" class="form-control"
                                    placeholder="Ej: Caja Principal" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="2" placeholder="Descripción de la caja..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Responsable <span class="text-danger">*</span></label>
                                <select name="responsable_id" class="form-control select2" required>
                                    <option value="">Seleccionar responsable...</option>
                                    @foreach ($trabajadores as $trabajador)
                                        @if ($trabajador && $trabajador['id'] && $trabajador['text'])
                                            <option value="{{ $trabajador['id'] }}">
                                                {{ $trabajador['text'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @if (count($trabajadores) > 0)
                                    <small class="text-muted">Se muestran solo trabajadores con cargos autorizados para
                                        caja</small>
                                @else
                                    <small class="text-danger">No hay trabajadores con cargos autorizados para caja en esta
                                        sucursal</small>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Moneda <span class="text-danger">*</span></label>
                                <select name="moneda" class="form-control" required>
                                    <option value="BS">Bolivianos (Bs)</option>
                                    <option value="USD">Dólares (USD)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Saldo Inicial <span class="text-danger">*</span></label>
                                <input type="number" name="saldo_inicial" class="form-control" min="0"
                                    step="0.01" placeholder="0.00" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" form="formRegistrarCaja" class="btn btn-primary" id="btnRegistrarCaja">
                            <i class="ri-save-line me-1"></i> Registrar Caja
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Ver Caja -->
    <div class="modal fade" id="modalVerCaja" tabindex="-1" aria-labelledby="modalVerCajaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info bg-soft">
                    <h5 class="modal-title text-white" id="modalVerCajaLabel">
                        <i class="ri-eye-line me-2"></i>Detalle de Caja
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="cajaDetalleContent">
                    <!-- Se cargará dinámicamente -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .color-preview {
            border-radius: 4px;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .bg-success-light {
            background-color: rgba(40, 199, 111, 0.15) !important;
        }

        .bg-danger-light {
            background-color: rgba(234, 84, 85, 0.15) !important;
        }

        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Inicializar Select2 cuando el modal se muestra
            $('#modalRegistrarCaja').on('shown.bs.modal', function() {
                $('.select2').select2({
                    placeholder: "Seleccionar responsable...",
                    allowClear: true,
                    dropdownParent: $('#modalRegistrarCaja')
                });
            });

            // Limpiar Select2 cuando se cierra el modal
            $('#modalRegistrarCaja').on('hidden.bs.modal', function() {
                $('.select2').select2('destroy');
            });

            // Registrar caja
            $('#formRegistrarCaja').submit(function(e) {
                e.preventDefault();
                const btn = $('#btnRegistrarCaja');
                const sucursalId = {{ $sucursal->id }};

                // Validar que se haya seleccionado un responsable
                const responsableId = $('select[name="responsable_id"]').val();
                if (!responsableId) {
                    showNotification('error', 'Debe seleccionar un responsable para la caja.');
                    return;
                }

                btn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Registrando...');

                const formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('admin.sucursales.registrar-caja', ':id') }}".replace(':id',
                        sucursalId),
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            showNotification('success', response.msg);
                            $('#modalRegistrarCaja').modal('hide');

                            // Recargar la página después de un breve delay
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showNotification('error', response.msg);
                            btn.prop('disabled', false).html(
                                '<i class="ri-save-line me-1"></i> Registrar Caja');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar la caja.';

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            // Mostrar el primer error
                            const firstError = Object.values(errors)[0];
                            errorMsg = Array.isArray(firstError) ? firstError[0] : firstError;

                            // Resaltar campos con error
                            Object.keys(errors).forEach(field => {
                                const input = $(`[name="${field}"]`);
                                if (input.length) {
                                    input.addClass('is-invalid');
                                    const feedback = input.next('.invalid-feedback');
                                    if (feedback.length === 0) {
                                        input.after(
                                            `<div class="invalid-feedback">${errors[field][0]}</div>`
                                        );
                                    }
                                }
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }

                        showNotification('error', errorMsg);
                        btn.prop('disabled', false).html(
                            '<i class="ri-save-line me-1"></i> Registrar Caja');
                    }
                });
            });

            // Limpiar errores cuando se cierra el modal
            $('#modalRegistrarCaja').on('hidden.bs.modal', function() {
                // Limpiar errores
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                // Resetear formulario
                $('#formRegistrarCaja')[0].reset();

                // Resetear select
                $('select[name="responsable_id"]').val('');
            });

            // Función para mostrar notificaciones
            function showNotification(type, message) {
                let toastContainer = $('#toast-container');
                if (toastContainer.length === 0) {
                    toastContainer = $(
                        '<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999"></div>'
                    );
                    $('body').append(toastContainer);
                }

                const toast = $(`
                <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `);

                toastContainer.append(toast);
                const bsToast = new bootstrap.Toast(toast[0]);
                bsToast.show();

                toast.on('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }
        });
    </script>
@endpush
