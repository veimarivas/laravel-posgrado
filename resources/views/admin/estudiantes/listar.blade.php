@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-dark">Gestión de Estudiantes</h4>
                        <p class="text-muted mb-0">Administra el registro de estudiantes en el sistema</p>
                    </div>

                    @if (Auth::guard('web')->user()->can('estudiantes.registrar'))
                        <div class="page-title-right">
                            <button type="button" class="btn btn-primary btn-lg waves-effect waves-light"
                                data-bs-toggle="modal" data-bs-target="#registrarEstudianteModal">
                                <i class="ri-user-add-line align-bottom me-1"></i> Nuevo Estudiante
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
                                    <i class="ri-graduation-cap-line fs-24 text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Estudiantes</p>
                                <h4 class="mb-0" id="totalEstudiantesCounter">{{ $estudiantes->total() }}</h4>
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
                                        placeholder="Buscar por nombre, carnet..." value="{{ request('search') ?? '' }}">
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
                        <h5 class="card-title mb-0 fw-bold">Listado de Estudiantes</h5>
                        <div class="dropdown">
                            <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-equalizer-line align-bottom me-1"></i> Vista
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item active" href="#"><i
                                            class="ri-list-check align-bottom me-2"></i> Tabla</a></li>
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
                                        <th class="px-3 py-3 text-center" width="20%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="estudiantesTableBody">
                                    @include('admin.estudiantes.partials.table-body')
                                </tbody>
                            </table>
                        </div>
                        @if ($estudiantes->total() > 0)
                            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                                <div class="results-count text-muted">
                                    Mostrando <span class="fw-medium">{{ $estudiantes->firstItem() }}</span> a
                                    <span class="fw-medium">{{ $estudiantes->lastItem() }}</span> de
                                    <span class="fw-medium">{{ $estudiantes->total() }}</span> resultados
                                </div>
                                <div class="pagination-container">
                                    {{ $estudiantes->appends(request()->input())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Estudiante -->
    <div class="modal fade" id="registrarEstudianteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="registrarLabel">
                        <i class="ri-user-add-line me-2 align-bottom"></i>Registrar Nuevo Estudiante
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Paso 1: Buscar por Carnet -->
                    <div id="paso-carnet">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto">
                                <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                    <i class="ri-search-line fs-2"></i>
                                </div>
                            </div>
                            <h5 class="mt-3">Buscar Persona por Carnet</h5>
                            <p class="text-muted">Ingresa el carnet para verificar si la persona ya está registrada</p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">Carnet <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" id="carnet_buscar" class="form-control form-control-lg"
                                    placeholder="Ej: 1234567">
                                <button class="btn btn-primary btn-lg" type="button" id="btn-buscar-carnet">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                            </div>
                            <div id="mensaje-verificacion" class="mt-2"></div>
                        </div>

                        <!-- Resultado de la búsqueda -->
                        <div id="resultado-busqueda" style="display: none;">
                            <div class="alert" id="alert-mensaje">
                                <i class="ri-information-line me-2"></i>
                                <span id="mensaje-texto"></span>
                            </div>

                            <div id="opciones-busqueda" class="mt-3">
                                <!-- Se llenará dinámicamente según el resultado -->
                            </div>
                        </div>
                    </div>

                    <!-- Formulario para nueva persona -->
                    <form id="formNuevaPersona" style="display:none;" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="carnet_original" id="carnet_original">
                        <!-- Este formulario será cargado dinámicamente -->
                    </form>

                    <!-- Confirmar registro de persona existente -->
                    <div id="confirmar-registro-existente" style="display:none;">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto">
                                <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                                    <i class="ri-user-follow-line fs-2"></i>
                                </div>
                            </div>
                            <h5 class="mt-3">Registrar Persona como Estudiante</h5>
                            <div class="alert alert-info">
                                <i class="ri-information-line me-2"></i>
                                La persona <strong id="nombre_persona_existente"></strong> con carnet
                                <strong id="carnet_mostrar"></strong> será registrada como estudiante.
                            </div>
                            <div class="mt-4">
                                <button type="button" class="btn btn-secondary btn-lg" id="btn-volver-carnet-existente">
                                    <i class="ri-arrow-left-line me-1"></i> Volver
                                </button>
                                <button type="button" class="btn btn-primary btn-lg"
                                    id="btn-confirmar-registro-existente">
                                    <i class="ri-user-add-line me-1"></i> Confirmar Registro
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ver Estudiante -->
    <div class="modal fade" id="modalVerEstudiante" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-info-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-info" id="modalVerLabel">
                        <i class="ri-eye-line me-2 align-bottom"></i>Ver Estudiante
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="modalVerContenido">
                    <!-- Se cargará dinámicamente -->
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-soft-secondary btn-lg" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Estudiante -->
    <div class="modal fade" id="modalEditarEstudiante" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-warning-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-warning" id="modalEditarLabel">
                        <i class="ri-edit-line me-2 align-bottom"></i>Editar Estudiante
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="modalEditarContenido">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2 text-muted">Cargando información del estudiante...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar -->
    <div class="modal fade" id="modalEliminarEstudiante" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-danger" id="modalEliminarLabel">
                        <i class="ri-delete-bin-line me-2 align-bottom"></i>Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="deleteFormEstudiante">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="eliminarIdEstudiante">
                    <div class="modal-body p-4 text-center">
                        <div class="mb-4">
                            <div class="avatar-xl mx-auto">
                                <div class="avatar-title bg-danger bg-opacity-10 text-danger rounded-circle fs-2xl">
                                    <i class="ri-alert-line"></i>
                                </div>
                            </div>
                        </div>
                        <h4 class="mb-3 fw-bold">¿Estás seguro de eliminar este estudiante?</h4>
                        <p class="text-muted mb-0">Esta acción solo eliminará el registro como estudiante, no los datos
                            personales.</p>
                        <div class="mt-3 p-3 bg-light rounded">
                            <small class="text-danger fw-medium">Advertencia:</small>
                            <small class="text-muted">El estudiante no podrá ser inscrito en ofertas académicas hasta que
                                sea registrado nuevamente.</small>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger btn-lg btnDeleteEstudiante">
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

        .estudio-item-nuevo {
            transition: all 0.3s ease;
        }

        .estudio-item-nuevo:hover {
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        .opcion-busqueda {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .opcion-busqueda:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
            let estudioNuevoCounter = 1;
            let personaIdExistente = null;

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

            // Resetear formularios al cerrar modal
            $('#registrarEstudianteModal').on('hidden.bs.modal', function() {
                resetModalRegistro();
            });

            function resetModalRegistro() {
                $('#paso-carnet').show();
                $('#formNuevaPersona, #confirmar-registro-existente').hide();
                $('#carnet_buscar').val('');
                $('#mensaje-verificacion').html('');
                $('#resultado-busqueda').hide();
                $('#formNuevaPersona')[0]?.reset();
                $('#formNuevaPersona').removeClass('was-validated');
                personaIdExistente = null;
            }

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

            function crearFilaEstudioNuevo(index) {
                return `
            <div class="estudio-item-nuevo row mb-3 border rounded p-3 bg-light">
                <div class="col-md-3">
                    <label class="form-label fw-medium">Grado Académico</label>
                    <select class="form-select grado-select-nuevo" name="estudios[${index}][grado]">
                        <option value="">Seleccionar</option>
                        ${grados.map(g => `<option value="${g.id}">${g.nombre}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">Profesión</label>
                    <select class="form-select profesion-select-nuevo" name="estudios[${index}][profesion]" disabled>
                        <option value="">Seleccionar</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">Universidad</label>
                    <select class="form-select universidad-select-nuevo" name="estudios[${index}][universidad]" disabled>
                        <option value="">Seleccionar</option>
                        ${universidades.map(u => `<option value="${u.id}">${u.nombre} (${u.sigla})</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-estudio-nuevo">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>`;
            }

            function crearFormularioNuevaPersona(carnet) {
                return `
            <div class="text-center mb-4">
                <div class="avatar-lg mx-auto">
                    <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                        <i class="ri-user-add-line fs-2"></i>
                    </div>
                </div>
                <h5 class="mt-3">Registrar Nueva Persona como Estudiante</h5>
                <p class="text-muted">Completa los datos de la nueva persona</p>
            </div>
            <form id="formNuevaPersonaInterno" class="needs-validation" novalidate>
            <!-- AGREGAR ESTA LÍNEA CON EL TOKEN CSRF -->
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="carnet_original" id="carnet_original" value="${carnet}">
            <div class="row g-3">
                <!-- Datos Personales -->
                <div class="col-lg-12 mb-3">
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="ri-user-3-line me-2"></i>Datos Personales
                    </h6>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Carnet <span class="text-danger">*</span></label>
                        <input type="text" name="carnet" class="form-control form-control-lg"
                            id="carnet_nuevo" value="${carnet}" required>
                        <div class="invalid-feedback">Por favor ingresa el carnet</div>
                        <small id="feedback_carnet_nuevo" class="form-text mt-1"></small>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Expedido</label>
                        <select name="expedido" class="form-select form-select-lg">
                            <option value="">Seleccionar</option>
                            ${['Lp', 'Or', 'Pt', 'Cb', 'Ch', 'Tj', 'Be', 'Sc', 'Pn'].map(e => `<option value="${e}">${e}</option>`).join('')}
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombres <span class="text-danger">*</span></label>
                        <input type="text" name="nombres" class="form-control form-control-lg"
                            id="nombres_nuevo" placeholder="Ej: Juan Carlos" required>
                        <div class="invalid-feedback">Por favor ingresa los nombres</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Apellido Paterno</label>
                        <input type="text" name="apellido_paterno" class="form-control form-control-lg"
                            id="paterno_nuevo" placeholder="Ej: Pérez">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Apellido Materno</label>
                        <input type="text" name="apellido_materno" class="form-control form-control-lg"
                            id="materno_nuevo" placeholder="Ej: González">
                        <small id="feedback_apellidos_nuevo" class="form-text text-danger mt-1"></small>
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
                        <label class="form-label fw-medium">Estado Civil <span class="text-danger">*</span></label>
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
                        <label class="form-label fw-medium">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" name="correo" class="form-control form-control-lg"
                            id="correo_nuevo" placeholder="ejemplo@email.com" required>
                        <div class="invalid-feedback">Por favor ingresa un correo válido</div>
                        <small id="feedback_correo_nuevo" class="form-text mt-1"></small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Celular <span class="text-danger">*</span></label>
                        <input type="text" name="celular" class="form-control form-control-lg"
                            id="celular_nuevo" placeholder="Ej: 70012345" required>
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
                        <select class="form-select form-select-lg" id="departamento_nuevo">
                            <option value="">Seleccionar</option>
                            ${departamentos.map(d => `<option value="${d.id}">${d.nombre}</option>`).join('')}
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Ciudad</label>
                        <select name="ciudade_id" class="form-select form-select-lg" id="ciudad_nuevo">
                            <option value="">Primero seleccione un departamento</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control form-control-lg"
                            id="fecha_nac_nuevo" max="{{ now()->subYears(18)->format('Y-m-d') }}">
                        <small id="edad_calculada_nuevo" class="form-text mt-1"></small>
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
                        <button type="button" class="btn btn-outline-primary btn-sm add-estudio-nuevo">
                            <i class="ri-add-line me-1"></i>Agregar Estudio
                        </button>
                    </div>
                    <div id="estudios-container-nuevo">
                        ${crearFilaEstudioNuevo(0)}
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary btn-lg" id="btn-volver-carnet-nuevo">
                        <i class="ri-arrow-left-line me-1"></i> Volver
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg" id="btn-guardar-nueva-persona" disabled>
                        <i class="ri-save-line me-1"></i> Registrar como Estudiante
                    </button>
                </div>
            </div></form>`;
            }

            // === BÚSQUEDA DE CARNET ===
            $('#btn-buscar-carnet').on('click', buscarCarnet);
            $('#carnet_buscar').on('keypress', function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    buscarCarnet();
                }
            });

            function buscarCarnet() {
                const carnet = $('#carnet_buscar').val().trim();
                if (!carnet) {
                    $('#mensaje-verificacion').html(
                        `<div class="alert alert-warning">Por favor ingresa un carnet</div>`
                    );
                    return;
                }

                $('#mensaje-verificacion').html(
                    `<div class="text-center">
                    <div class="spinner-border spinner-border-sm text-primary"></div>
                    <span class="ms-2">Verificando carnet...</span>
                </div>`
                );

                $.ajax({
                    url: "{{ route('admin.estudiantes.verificar-carnet') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        carnet: carnet
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#resultado-busqueda').show();

                        if (res.is_student) {
                            // Ya es estudiante
                            $('#alert-mensaje').removeClass('alert-info alert-success').addClass(
                                'alert-warning');
                            $('#mensaje-texto').html(`
                            <strong>${res.persona.nombre_completo}</strong> con carnet <strong>${res.persona.carnet}</strong> ya está registrado como estudiante.
                        `);
                            $('#opciones-busqueda').html(`
                            <div class="text-center">
                                <p class="text-muted">No es necesario registrar nuevamente.</p>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        `);
                        } else if (res.exists) {
                            // Existe pero no es estudiante
                            $('#alert-mensaje').removeClass('alert-warning alert-success').addClass(
                                'alert-info');
                            $('#mensaje-texto').html(`
                            <strong>${res.persona.nombre_completo}</strong> con carnet <strong>${res.persona.carnet}</strong> está registrado pero no es estudiante.
                        `);
                            $('#opciones-busqueda').html(`
                            <div class="text-center">
                                <button type="button" class="btn btn-primary btn-lg" id="btn-registrar-existente" 
                                    data-persona-id="${res.persona.id}" 
                                    data-persona-nombre="${res.persona.nombre_completo}"
                                    data-persona-carnet="${res.persona.carnet}">
                                    <i class="ri-user-add-line me-1"></i> Registrar como Estudiante
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-lg ms-2" id="btn-volver-busqueda">
                                    <i class="ri-arrow-left-line me-1"></i> Volver
                                </button>
                            </div>
                        `);
                        } else {
                            // No existe la persona
                            $('#alert-mensaje').removeClass('alert-warning alert-info').addClass(
                                'alert-success');
                            $('#mensaje-texto').html(`
                            El carnet <strong>${carnet}</strong> no está registrado en el sistema.
                        `);
                            $('#opciones-busqueda').html(`
                            <div class="text-center">
                                <button type="button" class="btn btn-primary btn-lg" id="btn-crear-nueva-persona" data-carnet="${carnet}">
                                    <i class="ri-user-add-line me-1"></i> Crear Nueva Persona
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-lg ms-2" id="btn-volver-busqueda">
                                    <i class="ri-arrow-left-line me-1"></i> Volver
                                </button>
                            </div>
                        `);
                        }
                    },
                    error: function(jqXHR) {
                        let errorMsg = 'Error al verificar el carnet';
                        if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                            errorMsg = jqXHR.responseJSON.message;
                        }

                        $('#resultado-busqueda').show();
                        $('#alert-mensaje').removeClass().addClass('alert alert-danger');
                        $('#mensaje-texto').text(errorMsg);
                        $('#opciones-busqueda').html('');
                    }
                });
            }

            // === NAVEGACIÓN ENTRE PASOS ===
            $(document).on('click', '#btn-volver-busqueda', function() {
                $('#resultado-busqueda').hide();
                $('#mensaje-verificacion').html('');
            });

            $(document).on('click', '#btn-crear-nueva-persona', function() {
                const carnet = $(this).data('carnet');
                $('#carnet_original').val(carnet);
                $('#paso-carnet').hide();
                $('#formNuevaPersona').html(crearFormularioNuevaPersona(carnet)).show();
                inicializarValidacionesPersona();
            });

            $(document).on('click', '#btn-registrar-existente', function() {
                personaIdExistente = $(this).data('persona-id');
                const nombre = $(this).data('persona-nombre');
                const carnet = $(this).data('persona-carnet');

                $('#paso-carnet').hide();
                $('#resultado-busqueda').hide();
                $('#confirmar-registro-existente').show();
                $('#nombre_persona_existente').text(nombre);
                $('#carnet_mostrar').text(carnet);
            });

            $(document).on('click', '#btn-volver-carnet-existente', function() {
                $('#confirmar-registro-existente').hide();
                $('#paso-carnet').show();
                personaIdExistente = null;
            });

            $(document).on('click', '#btn-volver-carnet-nuevo', function() {
                $('#formNuevaPersona').hide().html('');
                $('#paso-carnet').show();
                estudioNuevoCounter = 1;
            });

            // === VALIDACIONES NUEVA PERSONA ===
            function inicializarValidacionesPersona() {

                // Evento para departamento → ciudad
                $('#departamento_nuevo').on('change', function() {
                    const deptoId = $(this).val();
                    llenarCiudadesPorDepartamento(deptoId, $('#ciudad_nuevo'));
                    $('#ciudad_nuevo').val('');
                    checkFormNuevaPersona();
                });

                // Evento para grado → habilitar profesiones y universidades
                $(document).on('change', '.grado-select-nuevo', function() {
                    const row = $(this).closest('.estudio-item-nuevo');
                    const gradoId = $(this).val();

                    if (!gradoId) {
                        row.find('.profesion-select-nuevo, .universidad-select-nuevo').prop('disabled',
                                true)
                            .html('<option value="">Seleccionar</option>');
                        return;
                    }

                    // Habilitar y llenar profesiones
                    let htmlProf = '<option value="">Seleccionar</option>';
                    profesiones.forEach(p => {
                        htmlProf += `<option value="${p.id}">${p.nombre}</option>`;
                    });
                    row.find('.profesion-select-nuevo').html(htmlProf).prop('disabled', false);

                    // Habilitar universidades
                    row.find('.universidad-select-nuevo').prop('disabled', false);

                    checkFormNuevaPersona();
                });

                // Agregar estudio
                $(document).on('click', '.add-estudio-nuevo', function() {
                    $('#estudios-container-nuevo').append(crearFilaEstudioNuevo(estudioNuevoCounter));
                    estudioNuevoCounter++;
                });

                // Eliminar estudio
                $(document).on('click', '.remove-estudio-nuevo', function() {
                    if ($('.estudio-item-nuevo').length > 1) {
                        $(this).closest('.estudio-item-nuevo').remove();
                    } else {
                        showToast('warning', 'Debe mantener al menos un estudio');
                    }
                });

                // Validación de apellidos
                function validarApellidosNuevo() {
                    const p = $('#paterno_nuevo').val().trim();
                    const m = $('#materno_nuevo').val().trim();
                    if (!p && !m) {
                        $('#feedback_apellidos_nuevo').text('⚠️ Debe ingresar al menos un apellido.').removeClass(
                            'text-success').addClass('text-danger');
                        return false;
                    } else {
                        $('#feedback_apellidos_nuevo').text('');
                        return true;
                    }
                }

                // Calcular edad
                function calcularEdadNuevo() {
                    const fecha = $('#fecha_nac_nuevo').val();
                    if (!fecha) {
                        $('#edad_calculada_nuevo').text('').removeClass('text-danger text-success');
                        return true;
                    }

                    const hoy = new Date();
                    const nac = new Date(fecha);
                    let edad = hoy.getFullYear() - nac.getFullYear();
                    const mes = hoy.getMonth() - nac.getMonth();

                    if (mes < 0 || (mes === 0 && hoy.getDate() < nac.getDate())) edad--;

                    if (edad < 18) {
                        $('#edad_calculada_nuevo').addClass('text-danger').removeClass('text-success').text(
                            '⚠️ Debe tener al menos 18 años.');
                        return false;
                    } else {
                        $('#edad_calculada_nuevo').addClass('text-success').removeClass('text-danger').text(
                            `✅ Edad: ${edad} años`);
                        return true;
                    }
                }

                // Verificar campo único
                function verificarCampoNuevo(value, type = 'carnet') {
                    const route = type === 'carnet' ?
                        "{{ route('admin.personas.verificar-carnet') }}" :
                        "{{ route('admin.personas.verificar-correo') }}";

                    return new Promise((resolve) => {
                        $.post(route, {
                            _token: "{{ csrf_token() }}",
                            [type]: value
                        }, function(res) {
                            resolve(res.exists);
                        }).fail(() => resolve(true));
                    });
                }

                // Validar formulario completo
                function checkFormNuevaPersona() {
                    const carnetOk = $('#feedback_carnet_nuevo').hasClass('text-success') || false;
                    const correoOk = $('#feedback_correo_nuevo').hasClass('text-success') || false;
                    const nombres = $('#nombres_nuevo').val().trim();
                    const celular = $('#celular_nuevo').val().trim();
                    const sexo = $('select[name="sexo"]').val();
                    const ecivil = $('select[name="estado_civil"]').val();
                    const apellidosOk = validarApellidosNuevo();
                    const edadOk = !$('#fecha_nac_nuevo').val() || calcularEdadNuevo();

                    const enabled = carnetOk && correoOk && nombres && celular && sexo && ecivil && apellidosOk &&
                        edadOk;

                    $('#btn-guardar-nueva-persona').prop('disabled', !enabled);
                    console.log('Estado del formulario:', {
                        carnetOk,
                        correoOk,
                        nombres,
                        celular,
                        sexo,
                        ecivil,
                        apellidosOk,
                        edadOk,
                        enabled
                    });
                    return enabled;
                }

                // Eventos para validaciones en tiempo real
                $('#carnet_nuevo').on('input', function() {
                    const value = $(this).val().trim();
                    const feedback = $('#feedback_carnet_nuevo');

                    if (!value) {
                        feedback.text('').removeClass('text-success text-danger');
                        checkFormNuevaPersona();
                        return;
                    }

                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(async () => {
                        const exists = await verificarCampoNuevo('carnet', value);
                        if (exists) {
                            feedback.addClass('text-danger').removeClass('text-success').html(
                                '<i class="ri-error-warning-line me-1"></i> Este carnet ya está registrado'
                            );
                        } else {
                            feedback.addClass('text-success').removeClass('text-danger').html(
                                '<i class="ri-checkbox-circle-line me-1"></i> Carnet disponible'
                            );
                        }
                        checkFormNuevaPersona();
                    }, 500);
                });

                $('#correo_nuevo').on('input', function() {
                    const value = $(this).val().trim();
                    const feedback = $('#feedback_correo_nuevo');
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    if (!value) {
                        feedback.text('').removeClass('text-success text-danger');
                        checkFormNuevaPersona();
                        return;
                    }

                    if (!emailRegex.test(value)) {
                        feedback.addClass('text-danger').removeClass('text-success').html(
                            '<i class="ri-error-warning-line me-1"></i> Formato de correo inválido');
                        checkFormNuevaPersona();
                        return;
                    }

                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(async () => {
                        const exists = await verificarCampoNuevo('correo', value);
                        if (exists) {
                            feedback.addClass('text-danger').removeClass('text-success').html(
                                '<i class="ri-error-warning-line me-1"></i> Este correo ya está registrado'
                            );
                        } else {
                            feedback.addClass('text-success').removeClass('text-danger').html(
                                '<i class="ri-checkbox-circle-line me-1"></i> Correo disponible'
                            );
                        }
                        checkFormNuevaPersona();
                    }, 500);
                });

                $('#fecha_nac_nuevo').on('change', function() {
                    calcularEdadNuevo();
                    checkFormNuevaPersona();
                });

                // Añade eventos para todos los campos requeridos
                $('#nombres_nuevo, #paterno_nuevo, #materno_nuevo, #celular_nuevo, select[name="sexo"], select[name="estado_civil"], #ciudad_nuevo')
                    .on('input change', function() {
                        checkFormNuevaPersona();
                    });

                // Calcular edad inicial si hay valor
                if ($('#fecha_nac_nuevo').val()) {
                    calcularEdadNuevo();
                }

                // Validar apellidos inicialmente
                validarApellidosNuevo();

                // Verificar el carnet inicial (ya que viene prellenado)
                if ($('#carnet_nuevo').val().trim()) {
                    $('#carnet_nuevo').trigger('input');
                }

                // Verificar estado inicial del formulario
                setTimeout(() => {
                    checkFormNuevaPersona();
                }, 100);
            }

            // === REGISTRAR PERSONA EXISTENTE COMO ESTUDIANTE ===
            $('#btn-confirmar-registro-existente').on('click', function() {
                if (isProcessing) return;

                isProcessing = true;
                const btn = $(this);
                const originalHtml = btn.html();

                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...');

                $.ajax({
                    url: "{{ route('admin.estudiantes.registrar') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        persona_id: personaIdExistente
                    },
                    success: function(res) {
                        if (res.success) {
                            closeModalAndCleanup('registrarEstudianteModal');
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 500);
                        } else {
                            showToast('error', res.msg || 'Error al registrar el estudiante');
                        }
                    },
                    error: function(xhr) {
                        showToast('error', 'Error al registrar el estudiante');
                    },
                    complete: function() {
                        isProcessing = false;
                        btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // === REGISTRAR NUEVA PERSONA COMO ESTUDIANTE ===
            $('#formNuevaPersona').on('submit', function(e) {
                e.preventDefault();

                if (isProcessing) return;
                isProcessing = true;

                const btn = $('#btn-guardar-nueva-persona');
                const originalHtml = btn.html();

                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...');

                $.ajax({
                    url: "{{ route('admin.estudiantes.registrar-persona-estudiante') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            closeModalAndCleanup('registrarEstudianteModal');
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 500);
                        } else {
                            showToast('error', res.msg || 'Error al registrar el estudiante');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar la persona y estudiante';
                        if (xhr.responseJSON?.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMsg = 'Errores de validación:<br>';
                            for (const field in errors) {
                                errorMsg += `• ${errors[field][0]}<br>`;
                            }
                            // Muestra errores específicos en los campos
                            if (errors.carnet) {
                                $('#feedback_carnet_nuevo').addClass('text-danger').text(errors
                                    .carnet[0]);
                            }
                            if (errors.correo) {
                                $('#feedback_correo_nuevo').addClass('text-danger').text(errors
                                    .correo[0]);
                            }
                            if (errors.nombres) {
                                $('#nombres_nuevo').addClass('is-invalid').next(
                                    '.invalid-feedback').text(errors.nombres[0]);
                            }
                            // Agrega más campos según necesites
                        } else if (xhr.responseJSON?.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        showToast('error', errorMsg);

                        // Para ver el error completo en consola
                        console.log('Error completo:', xhr.responseJSON);
                    },
                    complete: function() {
                        isProcessing = false;
                        btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // === VER ESTUDIANTE ===
            $(document).on('click', '.viewBtnEstudiante', function() {
                const data = $(this).data('bs-obj');
                const persona = data.persona || {};

                let html = `
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="mb-3">Información del Estudiante</h5>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Carnet:</strong> ${persona.carnet || ''}</p>
                                        <p><strong>Nombres:</strong> ${persona.nombres || ''}</p>
                                        <p><strong>Apellidos:</strong> ${persona.apellido_paterno || ''} ${persona.apellido_materno || ''}</p>
                                        <p><strong>Sexo:</strong> ${persona.sexo || ''}</p>
                                        <p><strong>Estado Civil:</strong> ${persona.estado_civil || ''}</p>
                                        <p><strong>Fecha Nacimiento:</strong> ${persona.fecha_nacimiento ? new Date(persona.fecha_nacimiento).toLocaleDateString() : ''}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Correo:</strong> ${persona.correo || ''}</p>
                                        <p><strong>Celular:</strong> ${persona.celular || ''}</p>
                                        <p><strong>Teléfono:</strong> ${persona.telefono || ''}</p>
                                        <p><strong>Dirección:</strong> ${persona.direccion || ''}</p>
                                        <p><strong>Ciudad:</strong> ${persona.ciudad || ''}</p>
                                        <p><strong>Departamento:</strong> ${persona.departamento || ''}</p>
                                    </div>
                                </div>
                                ${persona.estudios && persona.estudios.length > 0 ? `
                                                                        <hr>
                                                                        <h6 class="mt-3">Estudios Realizados</h6>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-sm">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Grado</th>
                                                                                        <th>Profesión</th>
                                                                                        <th>Universidad</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    ${persona.estudios.map(estudio => `
                                                <tr>
                                                    <td>${estudio.grado_nombre || ''}</td>
                                                    <td>${estudio.profesion_nombre || ''}</td>
                                                    <td>${estudio.universidad_nombre || ''}</td>
                                                </tr>
                                            `).join('')}
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;

                $('#modalVerContenido').html(html);
            });

            // === EDITAR ESTUDIANTE ===
            $(document).on('click', '.editBtnEstudiante', function() {
                const data = $(this).data('bs-obj');
                const personaId = data.persona?.id;

                if (!personaId) {
                    showToast('error', 'No se pudo obtener la información de la persona');
                    return;
                }

                // Cargar formulario de edición
                cargarFormularioEdicion(personaId);
            });

            function cargarFormularioEdicion(personaId) {
                $.ajax({
                    url: "{{ route('admin.personas.ver', ['id' => '__ID__']) }}".replace('__ID__',
                        personaId),
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const persona = response.persona;
                            const formHtml = generarFormularioEdicionEstudiante(persona);
                            $('#modalEditarContenido').html(formHtml);
                            inicializarFormularioEdicionEstudiante(persona);
                        } else {
                            $('#modalEditarContenido').html(`
                            <div class="text-center text-danger">
                                <i class="ri-error-warning-line display-4"></i>
                                <p class="mt-2">Error al cargar los datos del estudiante</p>
                            </div>
                        `);
                        }
                    },
                    error: function() {
                        $('#modalEditarContenido').html(`
                        <div class="text-center text-danger">
                            <i class="ri-error-warning-line display-4"></i>
                            <p class="mt-2">Error al cargar los datos del estudiante</p>
                        </div>
                    `);
                    }
                });
            }

            function generarFormularioEdicionEstudiante(persona) {
                // Esta función es similar a la de personas.listar pero simplificada
                // Solo para demostración - deberías adaptar el formulario completo
                return `
                <form id="formEditarEstudiante" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="id" value="${persona.id}">
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Carnet</label>
                                <input type="text" name="carnet" class="form-control" value="${persona.carnet}" readonly>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Nombres</label>
                                <input type="text" name="nombres" class="form-control" value="${persona.nombres}">
                            </div>
                        </div>
                        <!-- Agregar más campos según necesites -->
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                    </div>
                </form>
            `;
            }

            function inicializarFormularioEdicionEstudiante(persona) {
                // Inicializar validaciones para el formulario de edición
                $('#formEditarEstudiante').on('submit', function(e) {
                    e.preventDefault();

                    if (isProcessing) return;
                    isProcessing = true;

                    const btn = $(this).find('button[type="submit"]');
                    const originalHtml = btn.html();

                    btn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...');

                    $.ajax({
                        url: "{{ route('admin.personas.modificar') }}",
                        type: "POST",
                        data: $(this).serialize(),
                        success: function(res) {
                            if (res.success) {
                                closeModalAndCleanup('modalEditarEstudiante');
                                showToast('success', res.msg);
                                setTimeout(() => {
                                    loadResults($('#searchInput').val().trim());
                                }, 300);
                            } else {
                                showToast('error', res.msg ||
                                    'Error al actualizar el estudiante');
                            }
                        },
                        error: function(xhr) {
                            showToast('error', 'Error al actualizar el estudiante');
                        },
                        complete: function() {
                            isProcessing = false;
                            btn.prop('disabled', false).html(originalHtml);
                        }
                    });
                });
            }

            // === ELIMINAR ESTUDIANTE ===
            $(document).on('click', '.deleteBtnEstudiante', function() {
                const data = $(this).data('bs-obj');
                $('#eliminarIdEstudiante').val(data.id);
            });

            $('#deleteFormEstudiante').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                isProcessing = true;
                const submitBtn = $('.btnDeleteEstudiante');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...'
                );

                $.ajax({
                    url: "{{ route('admin.estudiantes.eliminar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            closeModalAndCleanup('modalEliminarEstudiante');
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al eliminar el estudiante');
                        }
                    },
                    error: function(xhr) {
                        showToast('error', 'Error al eliminar el estudiante');
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
                    url: '{{ route('admin.estudiantes.listar') }}',
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#estudiantesTableBody').html(`
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
                            $('#estudiantesTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium">${response.from || 0}</span> a 
                                 <span class="fw-medium">${response.to || 0}</span> de 
                                 <span class="fw-medium">${response.total}</span> resultados`
                                );

                                $('#totalEstudiantesCounter').text(response.total);
                            }

                            $('[data-bs-toggle="tooltip"]').each(function() {
                                if (this._tooltip) {
                                    this._tooltip.dispose();
                                }
                                new bootstrap.Tooltip(this);
                            });
                        }
                    },
                    error: function() {
                        showToast('error', 'Error al cargar los resultados. Intenta nuevamente.');
                        $('#estudiantesTableBody').html(`
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

            // === PAGINACIÓN ===
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                if (isProcessing) return;

                let url = $(this).attr('href');
                const search = $('#searchInput').val().trim();

                if (search) {
                    const separator = url.includes('?') ? '&' : '?';
                    url = url + separator + 'search=' + encodeURIComponent(search);
                }

                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        $('#estudiantesTableBody').html(`
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
                            $('#estudiantesTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium">${response.from || 0}</span> a 
                                 <span class="fw-medium">${response.to || 0}</span> de 
                                 <span class="fw-medium">${response.total}</span> resultados`
                                );

                                $('#totalEstudiantesCounter').text(response.total);
                            }

                            window.history.pushState({}, '', url);
                        }
                    },
                    error: function() {
                        showToast('error', 'Error al cargar la página');
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

                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');
            }

            // Prevenir que se abran múltiples modales
            $(document).on('show.bs.modal', '.modal', function() {
                $('.modal.show').modal('hide');
            });
        });
    </script>
@endpush
