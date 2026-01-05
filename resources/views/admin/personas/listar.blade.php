@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-dark">Gestión de Personas</h4>
                        <p class="text-muted mb-0">Administra el registro de personas en el sistema</p>
                    </div>

                    @if (Auth::guard('web')->user()->can('personas.registrar'))
                        <div class="page-title-right">
                            <button type="button" class="btn btn-primary btn-lg waves-effect waves-light"
                                data-bs-toggle="modal" data-bs-target="#registrar">
                                <i class="ri-user-add-line align-bottom me-1"></i> Nueva Persona
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Filters & Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card border border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-primary bg-opacity-10">
                                    <i class="ri-user-line fs-24 text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Personas</p>
                                <h4 class="mb-0" id="totalPersonasCounter">{{ $personas->total() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="flex-grow-1">
                                <div class="search-box position-relative">
                                    <input type="text" id="searchInput" class="form-control search form-control-lg ps-5"
                                        placeholder="Buscar por nombre, carnet, correo, celular..."
                                        value="{{ request('search') ?? '' }}">
                                    <i
                                        class="ri-search-line search-icon position-absolute top-50 start-0 translate-middle-y text-muted ms-3"></i>
                                </div>
                            </div>
                            <div>
                                <button type="button" id="clearFilters" class="btn btn-outline-secondary btn-lg">
                                    <i class="ri-refresh-line align-bottom me-1"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border border-light shadow-sm">
                    <div class="card-header border-bottom-dashed d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fw-bold">Listado de Personas</h5>
                        <div class="dropdown">
                            <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-equalizer-line align-bottom me-1"></i> Vista
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item active" href="#"><i
                                            class="ri-list-check align-bottom me-2"></i> Tabla</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ri-grid-line align-bottom me-2"></i>
                                        Cuadrícula</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                <thead class="table-light text-muted fw-semibold">
                                    <tr>
                                        <th class="px-3 py-3" width="10%">Carnet</th>
                                        <th class="px-3 py-3">Nombre Completo</th>
                                        <th class="px-3 py-3">Contacto</th>
                                        <th class="px-3 py-3">Información</th>
                                        <th class="px-3 py-3 text-center" width="15%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="personasTableBody">
                                    @include('admin.personas.partials.table-body')
                                </tbody>
                            </table>
                        </div>
                        @if ($personas->total() > 0)
                            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                                <div class="results-count text-muted">
                                    Mostrando <span class="fw-medium">{{ $personas->firstItem() }}</span> a
                                    <span class="fw-medium">{{ $personas->lastItem() }}</span> de
                                    <span class="fw-medium">{{ $personas->total() }}</span> resultados
                                </div>
                                <div class="pagination-container">
                                    {{ $personas->appends(request()->input())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar -->
    <div class="modal fade" id="registrar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="registrarLabel">
                        <i class="ri-user-add-line me-2 align-bottom"></i>Registrar Nueva Persona
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="addForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <!-- Datos Personales -->
                            <div class="col-lg-12 mb-3">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="ri-user-3-line me-2"></i>Datos Personales
                                </h6>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Carnet <span class="text-danger">*</span></label>
                                    <input type="text" name="carnet" class="form-control form-control-lg"
                                        id="carnet_registro" placeholder="Ej: 1234567" required>
                                    <div class="invalid-feedback">Por favor ingresa el carnet</div>
                                    <small id="feedback_carnet" class="form-text mt-1"></small>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Expedido</label>
                                    <select name="expedido" class="form-select form-select-lg">
                                        <option value="">Seleccionar</option>
                                        @foreach (['Lp', 'Or', 'Pt', 'Cb', 'Ch', 'Tj', 'Be', 'Sc', 'Pn'] as $e)
                                            <option value="{{ $e }}">{{ $e }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" name="nombres" class="form-control form-control-lg"
                                        id="nombres_registro" placeholder="Ej: Juan Carlos" required>
                                    <div class="invalid-feedback">Por favor ingresa los nombres</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Apellido Paterno</label>
                                    <input type="text" name="apellido_paterno" class="form-control form-control-lg"
                                        id="paterno_registro" placeholder="Ej: Pérez">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Apellido Materno</label>
                                    <input type="text" name="apellido_materno" class="form-control form-control-lg"
                                        id="materno_registro" placeholder="Ej: González">
                                    <small id="feedback_apellidos" class="form-text text-danger mt-1"></small>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Sexo <span class="text-danger">*</span></label>
                                    <select name="sexo" class="form-select form-select-lg" required>
                                        <option value="">Seleccionar</option>
                                        <option value="Hombre">Hombre</option>
                                        <option value="Mujer">Mujer</option>
                                    </select>
                                    <div class="invalid-feedback">Por favor selecciona el sexo</div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Estado Civil <span
                                            class="text-danger">*</span></label>
                                    <select name="estado_civil" class="form-select form-select-lg" required>
                                        <option value="">Seleccionar</option>
                                        <option value="Soltero(a)">Soltero(a)</option>
                                        <option value="Casado(a)">Casado(a)</option>
                                        <option value="Divorciado(a)">Divorciado(a)</option>
                                        <option value="Viudo(a)">Viudo(a)</option>
                                    </select>
                                    <div class="invalid-feedback">Por favor selecciona el estado civil</div>
                                </div>
                            </div>

                            <!-- Contacto -->
                            <div class="col-lg-12 mb-3 mt-4">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="ri-phone-line me-2"></i>Datos de Contacto
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Correo Electrónico <span
                                            class="text-danger">*</span></label>
                                    <input type="email" name="correo" class="form-control form-control-lg"
                                        id="correo_registro" placeholder="ejemplo@email.com" required>
                                    <div class="invalid-feedback">Por favor ingresa un correo válido</div>
                                    <small id="feedback_correo" class="form-text mt-1"></small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Celular <span class="text-danger">*</span></label>
                                    <input type="text" name="celular" class="form-control form-control-lg"
                                        id="celular_registro" placeholder="Ej: 70012345" required>
                                    <div class="invalid-feedback">Por favor ingresa el celular</div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Teléfono</label>
                                    <input type="text" name="telefono" class="form-control form-control-lg"
                                        placeholder="Ej: 2365123">
                                </div>
                            </div>

                            <!-- Departamento y Ciudad -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Departamento</label>
                                    <select class="form-select form-select-lg" id="departamento_registro">
                                        <option value="">Seleccionar</option>
                                        @foreach ($departamentos as $d)
                                            <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Ciudad</label>
                                    <select name="ciudade_id" class="form-select form-select-lg" id="ciudad_registro">
                                        <option value="">Primero seleccione un departamento</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Fecha de Nacimiento</label>
                                    <input type="date" name="fecha_nacimiento" class="form-control form-control-lg"
                                        id="fecha_nac_registro" max="{{ now()->subYears(18)->format('Y-m-d') }}">
                                    <small id="edad_calculada" class="form-text mt-1"></small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Dirección</label>
                                    <textarea name="direccion" class="form-control" rows="2" placeholder="Dirección completa"></textarea>
                                </div>
                            </div>

                            <!-- Estudios -->
                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold text-primary mb-0">
                                        <i class="ri-book-line me-2"></i>Estudios Realizados
                                    </h6>
                                    <button type="button" class="btn btn-outline-primary btn-sm add-estudio">
                                        <i class="ri-add-line me-1"></i>Agregar Estudio
                                    </button>
                                </div>
                                <div id="estudios-container">
                                    <div class="estudio-item row mb-3 border rounded p-3 bg-light">
                                        <div class="col-md-3">
                                            <label class="form-label fw-medium">Grado Académico</label>
                                            <select class="form-select grado-select" name="estudios[0][grado]">
                                                <option value="">Seleccionar</option>
                                                @foreach ($grados as $g)
                                                    <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-medium">Profesión</label>
                                            <select class="form-select profesion-select" name="estudios[0][profesion]"
                                                disabled>
                                                <option value="">Seleccionar</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-medium">Universidad</label>
                                            <select class="form-select universidad-select" name="estudios[0][universidad]"
                                                disabled>
                                                <option value="">Seleccionar</option>
                                                @foreach ($universidades as $u)
                                                    <option value="{{ $u->id }}">{{ $u->nombre }}
                                                        ({{ $u->sigla }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-estudio">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg addBtn" disabled>
                            <i class="ri-user-add-line me-1 align-bottom"></i>
                            <span class="submit-text">Registrar Persona</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Modificar -->
    <div class="modal fade" id="modalModificar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-warning-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-warning" id="modalModificarLabel">
                        <i class="ri-edit-line me-2 align-bottom"></i>Editar Persona
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="id" id="personaId">

                    <div class="modal-body p-4">
                        <!-- El contenido será cargado dinámicamente por JavaScript -->
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2 text-muted">Cargando información de la persona...</p>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning btn-lg updateBtn" disabled>
                            <i class="ri-refresh-line me-1 align-bottom"></i>
                            <span class="submit-text">Actualizar Persona</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-danger" id="modalEliminarLabel">
                        <i class="ri-delete-bin-line me-2 align-bottom"></i>Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="eliminarId">
                    <div class="modal-body p-4 text-center">
                        <div class="mb-4">
                            <div class="avatar-xl mx-auto">
                                <div class="avatar-title bg-danger bg-opacity-10 text-danger rounded-circle fs-2xl">
                                    <i class="ri-alert-line"></i>
                                </div>
                            </div>
                        </div>
                        <h4 class="mb-3 fw-bold">¿Estás seguro de eliminar esta persona?</h4>
                        <p class="text-muted mb-0">Esta acción no se puede deshacer. Se eliminarán todos los datos
                            asociados a esta persona.</p>
                        <div class="mt-3 p-3 bg-light rounded">
                            <small class="text-danger fw-medium">Advertencia:</small>
                            <small class="text-muted">Si la persona tiene registros relacionados (trabajador, docente,
                                usuario, etc.), deberás actualizarlos primero.</small>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger btn-lg btnDelete">
                            <i class="ri-delete-bin-line me-1 align-bottom"></i>
                            <span class="submit-text">Sí, Eliminar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .table-nowrap td,
        .table-nowrap th {
            white-space: nowrap;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .estudio-item {
            transition: all 0.3s ease;
        }

        .estudio-item:hover {
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        @media (max-width: 767.98px) {
            .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }

            .card-header {
                flex-direction: column;
                gap: 10px;
            }

            .search-box {
                width: 100%;
            }
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999999;
        }

        .toast {
            min-width: 300px;
            max-width: 350px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 10px;
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            let debounceTimer;
            let isProcessing = false;
            let estudioCounter = 1;
            let estudioEdicionCounter = 0;

            // Datos de catálogos
            const ciudadesConDepartamento = @json($ciudades->map(fn($c) => ['id' => $c->id, 'nombre' => $c->nombre, 'departamento_id' => $c->departamento_id]));
            const grados = @json($grados);
            const profesiones = @json($profesiones);
            const universidades = @json($universidades);
            const departamentos = @json($departamentos);

            // Configuración global de AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                statusCode: {
                    500: function() {
                        showToast('error',
                            'Error interno del servidor. Por favor, intente nuevamente.');
                    },
                    404: function() {
                        showToast('error', 'Recurso no encontrado.');
                    },
                    403: function() {
                        showToast('error', 'No tiene permisos para realizar esta acción.');
                    },
                    422: function(xhr) {
                        const errors = xhr.responseJSON?.errors;
                        if (errors) {
                            let errorMsg = 'Errores de validación:<br>';
                            for (const field in errors) {
                                errorMsg += `• ${errors[field][0]}<br>`;
                            }
                            showToast('error', errorMsg);
                        } else {
                            showToast('error', 'Error de validación.');
                        }
                    }
                }
            });

            // Inicializar tooltips
            $('[data-bs-toggle="tooltip"]').each(function() {
                new bootstrap.Tooltip(this);
            });

            // Resetear formularios al cerrar modales
            $('#registrar, #modalModificar, #modalEliminar').on('hidden.bs.modal', function() {
                if (this.id === 'registrar') {
                    $('#addForm')[0].reset();
                    $('#ciudad_registro').prop('disabled', true).html(
                        '<option value="">Primero seleccione un departamento</option>');
                    $('.addBtn').prop('disabled', true);
                    $('#addForm').removeClass('was-validated');
                    $('#feedback_carnet, #feedback_correo, #feedback_apellidos, #edad_calculada').text('')
                        .removeClass('text-success text-danger');
                    $('#estudios-container').html(crearFilaEstudio(0));
                    estudioCounter = 1;
                } else if (this.id === 'modalModificar') {
                    $('#updateForm')[0].reset();
                    $('.updateBtn').prop('disabled', true);
                    $('#updateForm').removeClass('was-validated');
                }
            });

            // === FUNCIONES AUXILIARES ===
            function llenarCiudadesPorDepartamento(departamentoId, selectElement, ciudadSeleccionada = null) {
                selectElement.empty();
                if (!departamentoId) {
                    selectElement.append('<option value="">Primero seleccione un departamento</option>');
                    selectElement.prop('disabled', true);
                    return;
                }
                const ciudadesFiltradas = ciudadesConDepartamento.filter(c => c.departamento_id == departamentoId);
                if (ciudadesFiltradas.length === 0) {
                    selectElement.append('<option value="">Sin ciudades disponibles</option>');
                } else {
                    selectElement.append('<option value="">Seleccionar ciudad</option>');
                    ciudadesFiltradas.forEach(c => {
                        const selected = ciudadSeleccionada == c.id ? 'selected' : '';
                        selectElement.append(`<option value="${c.id}" ${selected}>${c.nombre}</option>`);
                    });
                }
                selectElement.prop('disabled', false);
            }

            function crearFilaEstudio(index) {
                return `
                <div class="estudio-item row mb-3 border rounded p-3 bg-light">
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Grado Académico</label>
                        <select class="form-select grado-select" name="estudios[${index}][grado]">
                            <option value="">Seleccionar</option>
                            ${grados.map(g => `<option value="${g.id}">${g.nombre}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Profesión</label>
                        <select class="form-select profesion-select" name="estudios[${index}][profesion]" disabled>
                            <option value="">Seleccionar</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Universidad</label>
                        <select class="form-select universidad-select" name="estudios[${index}][universidad]" disabled>
                            <option value="">Seleccionar</option>
                            ${universidades.map(u => `<option value="${u.id}">${u.nombre} (${u.sigla})</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-estudio">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>`;
            }

            function crearFilaEstudioEdicion(index, estudio = null) {
                const estudioId = estudio ? estudio.id : '';
                const gradoId = estudio ? (estudio.grados_academico_id || estudio.grado) : '';
                const profesionId = estudio ? (estudio.profesione_id || estudio.profesion) : '';
                const universidadId = estudio ? (estudio.universidade_id || estudio.universidad) : '';

                return `
                <div class="estudio-item-edicion row mb-3 border rounded p-3 bg-light" data-index="${index}">
                    ${estudioId ? `<input type="hidden" name="estudios_edit[${index}][id]" value="${estudioId}">` : ''}
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Grado Académico</label>
                        <select class="form-select grado-select-edicion" name="estudios_edit[${index}][grado]">
                            <option value="">Seleccionar</option>
                            ${grados.map(g => 
                                `<option value="${g.id}" ${gradoId == g.id ? 'selected' : ''}>${g.nombre}</option>`
                            ).join('')}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Profesión</label>
                        <select class="form-select profesion-select-edicion" name="estudios_edit[${index}][profesion]" 
                            ${gradoId ? '' : 'disabled'}>
                            <option value="">Seleccionar</option>
                            ${profesiones.map(p => 
                                `<option value="${p.id}" ${profesionId == p.id ? 'selected' : ''}>${p.nombre}</option>`
                            ).join('')}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Universidad</label>
                        <select class="form-select universidad-select-edicion" name="estudios_edit[${index}][universidad]" 
                            ${gradoId ? '' : 'disabled'}>
                            <option value="">Seleccionar</option>
                            ${universidades.map(u => 
                                `<option value="${u.id}" ${universidadId == u.id ? 'selected' : ''}>${u.nombre} (${u.sigla})</option>`
                            ).join('')}
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-estudio-edicion">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>`;
            }

            // === EVENTOS DEPARTAMENTO → CIUDAD (REGISTRO) ===
            $('#departamento_registro').on('change', function() {
                const deptoId = $(this).val();
                llenarCiudadesPorDepartamento(deptoId, $('#ciudad_registro'));
                $('#ciudad_registro').val('');
                checkFormCompleto();
            });

            // === VALIDACIONES REGISTRO ===
            function calcularEdad() {
                const fecha = $('#fecha_nac_registro').val();
                if (!fecha) {
                    $('#edad_calculada').text('').removeClass('text-danger text-success');
                    return true;
                }

                const hoy = new Date();
                const nac = new Date(fecha);
                let edad = hoy.getFullYear() - nac.getFullYear();
                const mes = hoy.getMonth() - nac.getMonth();

                if (mes < 0 || (mes === 0 && hoy.getDate() < nac.getDate())) edad--;

                if (edad < 18) {
                    $('#edad_calculada').addClass('text-danger').text('⚠️ Debe tener al menos 18 años.');
                    return false;
                } else {
                    $('#edad_calculada').addClass('text-success').text(`✅ Edad: ${edad} años`);
                    return true;
                }
            }

            function validarApellidos() {
                const p = $('#paterno_registro').val().trim();
                const m = $('#materno_registro').val().trim();
                if (!p && !m) {
                    $('#feedback_apellidos').text('⚠️ Debe ingresar al menos un apellido.');
                    return false;
                } else {
                    $('#feedback_apellidos').text('');
                    return true;
                }
            }

            function checkFormCompleto() {
                const carnetOk = $('#feedback_carnet').hasClass('text-success') || false;
                const correoOk = $('#feedback_correo').hasClass('text-success') || false;
                const nombres = $('#nombres_registro').val().trim();
                const celular = $('#celular_registro').val().trim();
                const sexo = $('select[name="sexo"]').val();
                const ecivil = $('select[name="estado_civil"]').val();
                const apellidosOk = validarApellidos();
                const edadOk = !$('#fecha_nac_registro').val() || calcularEdad();

                const enabled = carnetOk && correoOk && nombres && celular && sexo && ecivil &&
                    apellidosOk && edadOk;

                $('.addBtn').prop('disabled', !enabled);
                return enabled;
            }

            // === VERIFICACIÓN EN TIEMPO REAL (REGISTRO) ===
            function verificarCampo(field, value, id = null, type = 'carnet') {
                const route = type === 'carnet' ?
                    "{{ route('admin.personas.verificar-carnet') }}" :
                    "{{ route('admin.personas.verificar-correo') }}";

                return new Promise((resolve) => {
                    $.post(route, {
                        _token: "{{ csrf_token() }}",
                        [type]: value,
                        id: id
                    }, function(res) {
                        resolve(res.exists);
                    }).fail(() => resolve(true));
                });
            }

            $('#carnet_registro').on('input', function() {
                const value = $(this).val().trim();
                const feedback = $('#feedback_carnet');

                if (!value) {
                    feedback.text('').removeClass('text-success text-danger');
                    checkFormCompleto();
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(async () => {
                    const exists = await verificarCampo(this, value, null, 'carnet');
                    if (exists) {
                        feedback.addClass('text-danger').html(
                            '<i class="ri-error-warning-line me-1"></i> Este carnet ya está registrado'
                        );
                    } else {
                        feedback.addClass('text-success').html(
                            '<i class="ri-checkbox-circle-line me-1"></i> Carnet disponible'
                        );
                    }
                    checkFormCompleto();
                }, 500);
            });

            $('#correo_registro').on('input', function() {
                const value = $(this).val().trim();
                const feedback = $('#feedback_correo');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!value) {
                    feedback.text('').removeClass('text-success text-danger');
                    checkFormCompleto();
                    return;
                }

                if (!emailRegex.test(value)) {
                    feedback.addClass('text-danger').html(
                        '<i class="ri-error-warning-line me-1"></i> Formato de correo inválido');
                    checkFormCompleto();
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(async () => {
                    const exists = await verificarCampo(this, value, null, 'correo');
                    if (exists) {
                        feedback.addClass('text-danger').html(
                            '<i class="ri-error-warning-line me-1"></i> Este correo ya está registrado'
                        );
                    } else {
                        feedback.addClass('text-success').html(
                            '<i class="ri-checkbox-circle-line me-1"></i> Correo disponible'
                        );
                    }
                    checkFormCompleto();
                }, 500);
            });

            $('#fecha_nac_registro').on('change', function() {
                calcularEdad();
                checkFormCompleto();
            });

            $('#nombres_registro, #paterno_registro, #materno_registro, #celular_registro, #ciudad_registro, select[name="sexo"], select[name="estado_civil"]')
                .on('input change', checkFormCompleto);

            // === DINÁMICA DE ESTUDIOS (REGISTRO) ===
            $(document).on('change', '.grado-select', function() {
                const row = $(this).closest('.estudio-item');
                const gradoId = $(this).val();

                if (!gradoId) {
                    row.find('.profesion-select, .universidad-select').prop('disabled', true)
                        .html('<option value="">Seleccionar</option>');
                    return;
                }

                // Habilitar y llenar profesiones
                let htmlProf = '<option value="">Seleccionar</option>';
                profesiones.forEach(p => {
                    htmlProf += `<option value="${p.id}">${p.nombre}</option>`;
                });
                row.find('.profesion-select').html(htmlProf).prop('disabled', false);

                // Habilitar universidades
                row.find('.universidad-select').prop('disabled', false);
            });

            $(document).on('click', '.add-estudio', function() {
                $('#estudios-container').append(crearFilaEstudio(estudioCounter));
                estudioCounter++;
            });

            $(document).on('click', '.remove-estudio', function() {
                if ($('.estudio-item').length > 1) {
                    $(this).closest('.estudio-item').remove();
                } else {
                    showToast('warning', 'Debe mantener al menos un estudio');
                }
            });

            // === REGISTRAR PERSONA ===
            $('#addForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                if (!checkFormCompleto()) {
                    showToast('warning', 'Complete todos los campos requeridos correctamente');
                    return;
                }

                isProcessing = true;
                const submitBtn = $('.addBtn');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...'
                );

                $.ajax({
                    url: "{{ route('admin.personas.registrar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            // Cerrar modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'registrar'));
                            modal.hide();

                            // Mostrar toast
                            showToast('success', res.msg);

                            // Recargar datos
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 500);
                        } else {
                            showToast('error', res.msg || 'Error al registrar la persona');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar la persona. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors) {
                            const errors = xhr.responseJSON.errors;
                            if (errors.carnet) {
                                $('#feedback_carnet').addClass('text-danger').text(errors
                                    .carnet[0]);
                            }
                            if (errors.correo) {
                                $('#feedback_correo').addClass('text-danger').text(errors
                                    .correo[0]);
                            }
                            if (errors.apellidos) {
                                $('#feedback_apellidos').text(errors.apellidos[0]);
                            }
                            errorMsg = 'Por favor corrige los errores en el formulario.';
                        }
                        showToast('error', errorMsg);
                    },
                    complete: function() {
                        isProcessing = false;
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // === EDITAR PERSONA ===
            $(document).on('click', '.editBtn', function() {
                const data = $(this).data('bs-obj');
                const personaId = data.id;

                // Mostrar el modal con spinner
                const modal = new bootstrap.Modal(document.getElementById('modalModificar'));
                modal.show();

                // Cargar los datos de la persona
                cargarDatosPersona(personaId);
            });

            function cargarDatosPersona(id) {
                $.ajax({
                    url: "{{ route('admin.personas.ver', ['id' => '__ID__']) }}".replace('__ID__', id),
                    method: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        $('#modalModificar .modal-body').html(`
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2 text-muted">Cargando información de la persona...</p>
                        </div>
                    `);
                    },
                    success: function(response) {
                        if (response.success) {
                            const persona = response.persona;
                            // Generar el formulario con los datos
                            const formHtml = generarFormularioEdicion(persona);
                            $('#modalModificar .modal-body').html(formHtml);
                            // Inicializar eventos y validaciones
                            inicializarFormularioEdicion(persona);
                        } else {
                            $('#modalModificar .modal-body').html(`
                            <div class="text-center text-danger">
                                <i class="ri-error-warning-line display-4"></i>
                                <p class="mt-2">Error al cargar los datos de la persona</p>
                            </div>
                        `);
                        }
                    },
                    error: function() {
                        $('#modalModificar .modal-body').html(`
                        <div class="text-center text-danger">
                            <i class="ri-error-warning-line display-4"></i>
                            <p class="mt-2">Error al cargar los datos de la persona</p>
                        </div>
                    `);
                    }
                });
            }

            function generarFormularioEdicion(persona) {
                // Mapear expedidos
                const expedidos = ['Lp', 'Or', 'Pt', 'Cb', 'Ch', 'Tj', 'Be', 'Sc', 'Pn'];
                const expedidoOptions = expedidos.map(e =>
                    `<option value="${e}" ${persona.expedido === e ? 'selected' : ''}>${e}</option>`
                ).join('');

                // Estudios
                const estudios = persona.estudios || [];
                let estudiosHtml = '';
                if (estudios.length > 0) {
                    estudios.forEach((estudio, index) => {
                        estudiosHtml += crearFilaEstudioEdicion(index, estudio);
                    });
                    estudioEdicionCounter = estudios.length;
                } else {
                    estudiosHtml = crearFilaEstudioEdicion(0);
                    estudioEdicionCounter = 1;
                }

                // Datos de ubicación
                const departamentoId = persona.departamento_id || '';
                const ciudadId = persona.ciudade_id || '';

                return `
                <div class="row g-3">
                    <!-- Datos Personales -->
                    <div class="col-lg-12 mb-3">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="ri-user-3-line me-2"></i>Datos Personales
                        </h6>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Carnet <span class="text-danger">*</span></label>
                            <input type="text" name="carnet" class="form-control form-control-lg" 
                                id="carnet_edicion" value="${persona.carnet || ''}" placeholder="Ej: 1234567" required>
                            <div class="invalid-feedback">Por favor ingresa el carnet</div>
                            <small id="feedback_carnet_edicion" class="form-text mt-1"></small>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Expedido</label>
                            <select name="expedido" class="form-select form-select-lg" id="expedido_edicion">
                                <option value="">Seleccionar</option>
                                ${expedidoOptions}
                            </select>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Nombres <span class="text-danger">*</span></label>
                            <input type="text" name="nombres" class="form-control form-control-lg" 
                                id="nombres_edicion" value="${persona.nombres || ''}" placeholder="Ej: Juan Carlos" required>
                            <div class="invalid-feedback">Por favor ingresa los nombres</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Apellido Paterno</label>
                            <input type="text" name="apellido_paterno" class="form-control form-control-lg" 
                                id="paterno_edicion" value="${persona.apellido_paterno || ''}" placeholder="Ej: Pérez">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Apellido Materno</label>
                            <input type="text" name="apellido_materno" class="form-control form-control-lg" 
                                id="materno_edicion" value="${persona.apellido_materno || ''}" placeholder="Ej: González">
                            <small id="feedback_apellidos_edicion" class="form-text text-danger mt-1"></small>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Sexo <span class="text-danger">*</span></label>
                            <select name="sexo" class="form-select form-select-lg" id="sexo_edicion" required>
                                <option value="">Seleccionar</option>
                                <option value="Hombre" ${persona.sexo === 'Hombre' ? 'selected' : ''}>Hombre</option>
                                <option value="Mujer" ${persona.sexo === 'Mujer' ? 'selected' : ''}>Mujer</option>
                            </select>
                            <div class="invalid-feedback">Por favor selecciona el sexo</div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Estado Civil <span class="text-danger">*</span></label>
                            <select name="estado_civil" class="form-select form-select-lg" id="estado_civil_edicion" required>
                                <option value="">Seleccionar</option>
                                <option value="Soltero(a)" ${persona.estado_civil === 'Soltero(a)' ? 'selected' : ''}>Soltero(a)</option>
                                <option value="Casado(a)" ${persona.estado_civil === 'Casado(a)' ? 'selected' : ''}>Casado(a)</option>
                                <option value="Divorciado(a)" ${persona.estado_civil === 'Divorciado(a)' ? 'selected' : ''}>Divorciado(a)</option>
                                <option value="Viudo(a)" ${persona.estado_civil === 'Viudo(a)' ? 'selected' : ''}>Viudo(a)</option>
                            </select>
                            <div class="invalid-feedback">Por favor selecciona el estado civil</div>
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="col-lg-12 mb-3 mt-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="ri-phone-line me-2"></i>Datos de Contacto
                        </h6>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" name="correo" class="form-control form-control-lg" 
                                id="correo_edicion" value="${persona.correo || ''}" placeholder="ejemplo@email.com" required>
                            <div class="invalid-feedback">Por favor ingresa un correo válido</div>
                            <small id="feedback_correo_edicion" class="form-text mt-1"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Celular <span class="text-danger">*</span></label>
                            <input type="text" name="celular" class="form-control form-control-lg" 
                                id="celular_edicion" value="${persona.celular || ''}" placeholder="Ej: 70012345" required>
                            <div class="invalid-feedback">Por favor ingresa el celular</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Teléfono</label>
                            <input type="text" name="telefono" class="form-control form-control-lg" 
                                id="telefono_edicion" value="${persona.telefono || ''}" placeholder="Ej: 2365123">
                        </div>
                    </div>

                    <!-- Departamento y Ciudad -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Departamento</label>
                            <select class="form-select form-select-lg" id="departamento_edicion">
                                <option value="">Seleccionar</option>
                                ${departamentos.map(d => 
                                    `<option value="${d.id}" ${departamentoId == d.id ? 'selected' : ''}>${d.nombre}</option>`
                                ).join('')}
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Ciudad</label>
                            <select name="ciudade_id" class="form-select form-select-lg" id="ciudad_edicion">
                                <option value="">Seleccionar</option>
                                ${ciudadesConDepartamento.map(c => 
                                    `<option value="${c.id}" ${ciudadId == c.id ? 'selected' : ''}>${c.nombre}</option>`
                                ).join('')}
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="form-control form-control-lg" 
                                id="fecha_nac_edicion" max="{{ now()->subYears(18)->format('Y-m-d') }}" 
                                value="${persona.fecha_nacimiento_formatted || ''}">
                            <small id="edad_calculada_edicion" class="form-text mt-1"></small>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Dirección</label>
                            <textarea name="direccion" class="form-control" id="direccion_edicion" rows="2" placeholder="Dirección completa">${persona.direccion || ''}</textarea>
                        </div>
                    </div>

                    <!-- Estudios -->
                    <div class="col-12 mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-primary mb-0">
                                <i class="ri-book-line me-2"></i>Estudios Realizados
                            </h6>
                            <button type="button" class="btn btn-outline-primary btn-sm add-estudio-edicion">
                                <i class="ri-add-line me-1"></i>Agregar Estudio
                            </button>
                        </div>
                        <div id="estudios-container-edicion">
                            ${estudiosHtml}
                        </div>
                    </div>
                </div>`;
            }

            function inicializarFormularioEdicion(persona) {
                // Establecer el ID de la persona
                $('#personaId').val(persona.id);

                // Configurar departamento y ciudad
                const departamentoId = persona.departamento_id || '';
                const ciudadId = persona.ciudade_id || '';

                // Llenar departamento y habilitar/llenar ciudades
                $('#departamento_edicion').val(departamentoId);
                if (departamentoId) {
                    llenarCiudadesPorDepartamento(departamentoId, $('#ciudad_edicion'), ciudadId);
                }

                // Evento para cambio de departamento en edición
                $('#departamento_edicion').on('change', function() {
                    const deptoId = $(this).val();
                    llenarCiudadesPorDepartamento(deptoId, $('#ciudad_edicion'));
                    checkFormEdicionCompleto();
                });

                // Marcar carnet y correo como válidos inicialmente (ya que son los originales)
                $('#feedback_carnet_edicion').addClass('text-success').html(
                    '<i class="ri-checkbox-circle-line me-1"></i> Carnet original (válido)'
                );
                $('#feedback_correo_edicion').addClass('text-success').html(
                    '<i class="ri-checkbox-circle-line me-1"></i> Correo original (válido)'
                );

                // Eventos para verificación de carnet y correo en edición - solo si cambian
                let carnetOriginal = persona.carnet;
                let correoOriginal = persona.correo;

                $('#carnet_edicion').on('input', function() {
                    const currentValue = $(this).val().trim();
                    if (currentValue === carnetOriginal) {
                        $('#feedback_carnet_edicion').removeClass('text-danger').addClass('text-success')
                            .html('<i class="ri-checkbox-circle-line me-1"></i> Carnet original (válido)');
                    } else {
                        verificarCampoEdicion('carnet', currentValue, persona.id);
                    }
                    checkFormEdicionCompleto();
                });

                $('#correo_edicion').on('input', function() {
                    const currentValue = $(this).val().trim();
                    if (currentValue === correoOriginal) {
                        $('#feedback_correo_edicion').removeClass('text-danger').addClass('text-success')
                            .html('<i class="ri-checkbox-circle-line me-1"></i> Correo original (válido)');
                    } else {
                        verificarCampoEdicion('correo', currentValue, persona.id);
                    }
                    checkFormEdicionCompleto();
                });

                // Evento para fecha de nacimiento en edición
                $('#fecha_nac_edicion').on('change', function() {
                    calcularEdadEdicion();
                    checkFormEdicionCompleto();
                });
                // Calcular edad al inicializar si hay fecha
                if ($('#fecha_nac_edicion').val()) {
                    calcularEdadEdicion();
                }

                // Eventos para validación de apellidos en edición
                $('#paterno_edicion, #materno_edicion').on('input', function() {
                    validarApellidosEdicion();
                    checkFormEdicionCompleto();
                });

                // Otros eventos de validación
                $('#nombres_edicion, #celular_edicion, #sexo_edicion, #estado_civil_edicion')
                    .on('input change', checkFormEdicionCompleto);

                // Inicializar estudios en edición
                inicializarEstudiosEdicion();

                // Verificar estado inicial después de un pequeño delay
                setTimeout(() => {
                    calcularEdadEdicion();
                    validarApellidosEdicion();
                    checkFormEdicionCompleto();
                }, 300);
            }

            // Función corregida para verificar campos en edición
            function verificarCampoEdicion(field, value, personaId) {
                const route = field === 'carnet' ?
                    "{{ route('admin.personas.verificar-carnet') }}" :
                    "{{ route('admin.personas.verificar-correo') }}";
                const feedbackId = field === 'carnet' ? '#feedback_carnet_edicion' : '#feedback_correo_edicion';
                const feedback = $(feedbackId);

                if (!value) {
                    feedback.text('').removeClass('text-success text-danger');
                    checkFormEdicionCompleto();
                    return;
                }

                // Validación básica para correo
                if (field === 'correo') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        feedback.addClass('text-danger').removeClass('text-success').html(
                            '<i class="ri-error-warning-line me-1"></i> Formato de correo inválido');
                        checkFormEdicionCompleto();
                        return;
                    }
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: route,
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            [field]: value,
                            id: personaId
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').removeClass('text-success')
                                    .html(
                                        '<i class="ri-error-warning-line me-1"></i> Ya registrado'
                                    );
                            } else {
                                feedback.addClass('text-success').removeClass('text-danger')
                                    .html(
                                        '<i class="ri-checkbox-circle-line me-1"></i> Disponible'
                                    );
                            }
                            checkFormEdicionCompleto();
                        },
                        error: function() {
                            feedback.addClass('text-danger').removeClass('text-success').html(
                                '<i class="ri-error-warning-line me-1"></i> Error al verificar'
                            );
                        }
                    });
                }, 500);
            }

            // Función corregida para inicializar estudios en edición
            function inicializarEstudiosEdicion() {
                estudioEdicionCounter = $('#estudios-container-edicion .estudio-item-edicion').length;

                // Evento para agregar estudio en edición
                $(document).on('click', '.add-estudio-edicion', function() {
                    const container = $('#estudios-container-edicion');
                    const nuevoHtml = crearFilaEstudioEdicion(estudioEdicionCounter);
                    container.append(nuevoHtml);
                    estudioEdicionCounter++;
                });

                // Evento para eliminar estudio en edición
                $(document).on('click', '.remove-estudio-edicion', function() {
                    const container = $('#estudios-container-edicion');
                    if (container.find('.estudio-item-edicion').length > 1) {
                        $(this).closest('.estudio-item-edicion').remove();
                    } else {
                        showToast('warning', 'Debe mantener al menos un estudio');
                    }
                });

                // Habilitar selects de estudios existentes si ya tienen grado seleccionado
                $('.grado-select-edicion').each(function() {
                    const row = $(this).closest('.estudio-item-edicion');
                    const gradoId = $(this).val();
                    if (gradoId) {
                        row.find('.profesion-select-edicion, .universidad-select-edicion').prop('disabled',
                            false);
                    }
                });

                // Evento para cambio de grado en estudios de edición
                $(document).on('change', '.grado-select-edicion', function() {
                    const row = $(this).closest('.estudio-item-edicion');
                    const gradoId = $(this).val();

                    if (!gradoId) {
                        row.find('.profesion-select-edicion, .universidad-select-edicion')
                            .prop('disabled', true).val('');
                        return;
                    }

                    row.find('.profesion-select-edicion, .universidad-select-edicion').prop('disabled',
                        false);
                });
            }

            function calcularEdadEdicion() {
                const fecha = $('#fecha_nac_edicion').val();
                if (!fecha) {
                    $('#edad_calculada_edicion').text('').removeClass('text-danger text-success');
                    return true; // Si no hay fecha, es válido (campo no requerido)
                }

                const hoy = new Date();
                const nac = new Date(fecha);
                let edad = hoy.getFullYear() - nac.getFullYear();
                const mes = hoy.getMonth() - nac.getMonth();

                if (mes < 0 || (mes === 0 && hoy.getDate() < nac.getDate())) edad--;

                if (edad < 18) {
                    $('#edad_calculada_edicion').addClass('text-danger').removeClass('text-success')
                        .text('⚠️ Debe tener al menos 18 años.');
                    return false;
                } else {
                    $('#edad_calculada_edicion').addClass('text-success').removeClass('text-danger')
                        .text(`✅ Edad: ${edad} años`);
                    return true;
                }
            }

            function validarApellidosEdicion() {
                const p = $('#paterno_edicion').val().trim();
                const m = $('#materno_edicion').val().trim();
                if (!p && !m) {
                    $('#feedback_apellidos_edicion').text('⚠️ Debe ingresar al menos un apellido.');
                    return false;
                } else {
                    $('#feedback_apellidos_edicion').text('');
                    return true;
                }
            }

            function checkFormEdicionCompleto() {
                const carnetOk = $('#feedback_carnet_edicion').hasClass('text-success') || false;
                const correoOk = $('#feedback_correo_edicion').hasClass('text-success') || false;
                const nombres = $('#nombres_edicion').val().trim();
                const celular = $('#celular_edicion').val().trim();
                const sexo = $('#sexo_edicion').val();
                const ecivil = $('#estado_civil_edicion').val();
                const apellidosOk = validarApellidosEdicion();
                const edadOk = !$('#fecha_nac_edicion').val() || calcularEdadEdicion();

                const enabled = carnetOk && correoOk && nombres && celular && sexo && ecivil && apellidosOk &&
                    edadOk;

                $('.updateBtn').prop('disabled', !enabled);
                return enabled;
            }

            // === ACTUALIZAR PERSONA ===
            $('#updateForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                if (!checkFormEdicionCompleto()) {
                    showToast('warning', 'Complete todos los campos requeridos correctamente');
                    return;
                }

                isProcessing = true;
                const submitBtn = $('.updateBtn');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...'
                );

                $.ajax({
                    url: "{{ route('admin.personas.modificar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            // Cerrar modal
                            closeModalAndCleanup('modalModificar');

                            // Mostrar toast
                            showToast('success', res.msg);

                            // Recargar datos
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al actualizar la persona');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al actualizar la persona. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors) {
                            const errors = xhr.responseJSON.errors;
                            if (errors.carnet) {
                                $('#feedback_carnet_edicion').addClass('text-danger').text(
                                    errors.carnet[0]);
                            }
                            if (errors.correo) {
                                $('#feedback_correo_edicion').addClass('text-danger').text(
                                    errors.correo[0]);
                            }
                            if (errors.apellidos) {
                                $('#feedback_apellidos_edicion').text(errors.apellidos[0]);
                            }
                            errorMsg = 'Por favor corrige los errores en el formulario.';
                        }
                        showToast('error', errorMsg);
                    },
                    complete: function() {
                        isProcessing = false;
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // === ELIMINAR PERSONA ===
            $(document).on('click', '.deleteBtn', function() {
                const data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);
            });

            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                isProcessing = true;
                const submitBtn = $('.btnDelete');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...'
                );

                $.ajax({
                    url: "{{ route('admin.personas.eliminar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            // Cerrar modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'modalEliminar'));
                            modal.hide();

                            // Mostrar toast
                            showToast('success', res.msg);

                            // Recargar datos
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al eliminar la persona');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al eliminar la persona. Intenta nuevamente.';
                        if (xhr.responseJSON?.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        showToast('error', errorMsg);
                    },
                    complete: function() {
                        isProcessing = false;
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // === BÚSQUEDA Y PAGINACIÓN ===
            function loadResults(search = '') {
                if (isProcessing) return;
                isProcessing = true;

                $.ajax({
                    url: '{{ route('admin.personas.listar') }}',
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#personasTableBody').html(`
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2 text-muted">Cargando resultados...</p>
                            </td>
                        </tr>
                    `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#personasTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium">${response.from || 0}</span> a 
                                 <span class="fw-medium">${response.to || 0}</span> de 
                                 <span class="fw-medium">${response.total}</span> resultados`
                                );

                                $('#totalPersonasCounter').text(response.total);
                            }

                            // Re-inicializar tooltips
                            $('[data-bs-toggle="tooltip"]').each(function() {
                                if (this._tooltip) {
                                    this._tooltip.dispose();
                                }
                                new bootstrap.Tooltip(this);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al cargar resultados:', error);
                        showToast('error', 'Error al cargar los resultados. Intenta nuevamente.');

                        $('#personasTableBody').html(`
                        <tr>
                            <td colspan="5" class="text-center py-5 text-danger">
                                <i class="ri-error-warning-line display-5"></i>
                                <p class="mt-2">Error al cargar los datos</p>
                            </td>
                        </tr>
                    `);
                    },
                    complete: function() {
                        isProcessing = false;
                    }
                });
            }

            $('#searchInput').on('input', function() {
                const searchTerm = $(this).val().trim();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadResults(searchTerm);
                }, 500);
            });

            $('#clearFilters').on('click', function() {
                $('#searchInput').val('');
                loadResults();
            });

            // === PAGINACIÓN CORREGIDA ===
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                if (isProcessing) return;

                let url = $(this).attr('href');
                const search = $('#searchInput').val().trim();

                // Agregar parámetro de búsqueda a la URL si existe
                if (search) {
                    const separator = url.includes('?') ? '&' : '?';
                    url = url + separator + 'search=' + encodeURIComponent(search);
                }

                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        $('#personasTableBody').html(`
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando página...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#personasTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium">${response.from || 0}</span> a 
                                     <span class="fw-medium">${response.to || 0}</span> de 
                                     <span class="fw-medium">${response.total}</span> resultados`
                                );

                                $('#totalPersonasCounter').text(response.total);
                            }

                            // Actualizar URL en el navegador sin recargar
                            window.history.pushState({}, '', url);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error en paginación:', error);
                        showToast('error', 'Error al cargar la página: ' + (xhr.responseJSON
                            ?.message || 'Intenta nuevamente'));

                        // Recargar la página como fallback
                        setTimeout(() => {
                            window.location.href = url;
                        }, 2000);
                    }
                });
            });

            // === FUNCIÓN TOAST ===
            function showToast(type, message) {
                const config = {
                    success: {
                        icon: 'ri-checkbox-circle-fill',
                        bgClass: 'bg-success',
                        title: 'Éxito'
                    },
                    error: {
                        icon: 'ri-close-circle-fill',
                        bgClass: 'bg-danger',
                        title: 'Error'
                    },
                    warning: {
                        icon: 'ri-alert-fill',
                        bgClass: 'bg-warning',
                        title: 'Advertencia'
                    },
                    info: {
                        icon: 'ri-information-fill',
                        bgClass: 'bg-info',
                        title: 'Información'
                    }
                };

                const toastConfig = config[type] || config.info;
                const toastId = 'toast-' + Date.now();

                const toastHtml = `
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
                </div>
            `;

                let container = document.getElementById('toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'toast-container';
                    container.className = 'toast-container position-fixed top-0 end-0 p-3';
                    document.body.appendChild(container);
                }

                container.insertAdjacentHTML('afterbegin', toastHtml);

                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement, {
                    autohide: true,
                    delay: 3000
                });

                toast.show();

                toastElement.addEventListener('hidden.bs.toast', function() {
                    this.remove();
                });
            }

            // === VALIDACIÓN BOOTSTRAP ===
            (function() {
                'use strict';
                const forms = document.querySelectorAll('.needs-validation');
                Array.from(forms).forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            })();

            // Función para cerrar modales y limpiar backdrop
            function closeModalAndCleanup(modalId) {
                const modalElement = document.getElementById(modalId);
                if (modalElement) {
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    } else {
                        const modal = new bootstrap.Modal(modalElement);
                        modal.hide();
                    }
                }

                // Remover backdrop si existe
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');
            }

            // Eventos para limpiar backdrop cuando se cierran los modales
            $('#modalModificar, #registrar, #modalEliminar').on('hidden.bs.modal', function() {
                // Remover backdrop si queda alguno
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');

                // Habilitar el scroll
                $('html').css('overflow', 'auto');
            });

            // Prevenir que se abran múltiples modales
            $(document).on('show.bs.modal', '.modal', function() {
                // Cerrar cualquier modal abierto
                $('.modal.show').modal('hide');
            });
        });
    </script>
@endpush
