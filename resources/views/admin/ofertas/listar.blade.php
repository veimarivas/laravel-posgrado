@extends('admin.dashboard')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Ofertas Académicas</li>
            <li class="breadcrumb-item active">Listado</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-lg-12">
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
            <div id="results-container">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle" style="min-width: 1100px;">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th width="20">N°</th>
                                <th width="60">Código</th>
                                <th width="120">Programa</th>
                                <th width="80">N° Módulos</th>
                                <th width="50">Convenio</th>
                                <th width="80">Modalidad</th>
                                <th width="80">Inicio - Fin</th>
                                <th width="70">Inscritos</th>
                                <th width="50">Fase</th>
                                <th width="200">Acciones</th>
                            </tr>
                        </thead>
                        @include('admin.ofertas.partials.table-body')
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3" id="pagination-container">
                    {{ $ofertas->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Oferta Académica -->
    <div class="modal fade" id="modalEditarOferta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Oferta Académica</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editOfertaForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="edit_oferta_id">

                        <!-- Sede y Sucursal -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Sede *</label>
                                <select id="edit_sede_id" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($sedes as $sede)
                                        <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sucursal *</label>
                                <select name="sucursale_id" id="edit_sucursale_id" class="form-control" required disabled>
                                    <option value="">Seleccione sede primero</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Modalidad *</label>
                                <select name="modalidade_id" id="edit_modalidade_id" class="form-control" required>
                                    @foreach ($modalidades as $m)
                                        <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Código, Programa, Gestión -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Código *</label>
                                <input type="text" name="codigo" id="edit_codigo" class="form-control" required>
                                <div id="edit_feedback_codigo" class="mt-1" style="font-size: 0.875em;"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Programa *</label>
                                <input type="text" id="edit_programa_nombre" class="form-control"
                                    placeholder="Nombre del programa" required>
                                <input type="hidden" name="programa_id" id="edit_programa_id">
                                <div id="edit_programa_feedback" class="mt-1" style="font-size: 0.875em;"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Gestión *</label>
                                <input type="text" name="gestion" id="edit_gestion" class="form-control" required>
                            </div>
                        </div>

                        <!-- Responsables -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Responsable Académico *</label>
                                <select name="responsable_academico_cargo_id" id="edit_responsable_academico_cargo_id"
                                    class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($trabajadoresAcademicos as $ta)
                                        <option value="{{ $ta->id }}">
                                            {{ $ta->trabajador->persona->nombres }}
                                            {{ $ta->trabajador->persona->apellido_paterno }}
                                            ({{ $ta->cargo->nombre }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Responsable Marketing *</label>
                                <select name="responsable_marketing_cargo_id" id="edit_responsable_marketing_cargo_id"
                                    class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($trabajadoresMarketing as $tm)
                                        <option value="{{ $tm->id }}">
                                            {{ $tm->trabajador->persona->nombres }}
                                            {{ $tm->trabajador->persona->apellido_paterno }}
                                            ({{ $tm->cargo->nombre }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Color -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Color de la oferta</label>
                                <div class="d-flex align-items-center gap-3">
                                    <input type="color" id="edit_color" name="color"
                                        class="form-control form-control-color shadow-none p-1" value="#ccc">
                                    <span id="edit_preview_color" class="rounded-circle border d-inline-block"
                                        style="width: 32px; height: 32px; background-color: #ccc;"></span>
                                </div>
                                <small class="form-text text-muted">Se usará en calendarios y tarjetas.</small>
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Fecha Inicio Inscripciones *</label>
                                <input type="date" name="fecha_inicio_inscripciones"
                                    id="edit_fecha_inicio_inscripciones" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fecha Inicio Programa *</label>
                                <input type="date" name="fecha_inicio_programa" id="edit_fecha_inicio_programa"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fecha Fin Programa *</label>
                                <input type="date" name="fecha_fin_programa" id="edit_fecha_fin_programa"
                                    class="form-control" required>
                            </div>
                        </div>

                        <!-- Otros campos -->
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label class="form-label">N° Sesiones</label>
                                <input type="number" name="cantidad_sesiones" id="edit_cantidad_sesiones"
                                    class="form-control" min="1" value="1">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">N° Módulos</label>
                                <input type="number" name="n_modulos" id="edit_n_modulos" class="form-control"
                                    min="1" value="1">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Versión</label>
                                <input type="text" name="version" id="edit_version" class="form-control"
                                    value="1">
                                <div id="edit_feedback_version" class="mt-1" style="font-size: 0.875em;"></div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Grupo</label>
                                <input type="text" name="grupo" id="edit_grupo" class="form-control"
                                    value="1">
                                <div id="edit_feedback_grupo" class="mt-1" style="font-size: 0.875em;"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Nota mínima</label>
                                <input type="number" name="nota_minima" id="edit_nota_minima" class="form-control"
                                    min="1" max="100" value="61">
                            </div>
                        </div>

                        <!-- Imágenes -->
                        <div class="row mb-4">
                            <!-- Portada -->
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-primary text-white">
                                        <i class="ri-image-line me-1"></i> Portada del Programa
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <img id="edit_preview_portada" src="#" alt="Vista previa"
                                                class="img-fluid rounded shadow-sm"
                                                style="max-height: 180px; object-fit: contain; display: none;">
                                            <div id="edit_placeholder_portada" class="text-muted py-4"
                                                style="display: block;">
                                                <i class="ri-upload-cloud-2-line fs-1"></i><br>
                                                <small>Seleccione una imagen para previsualizar</small>
                                            </div>
                                        </div>
                                        <input type="file" name="portada" id="edit_portada_input"
                                            class="form-control" accept="image/png,image/jpeg,image/jpg">
                                        <div class="form-text">Formatos: PNG, JPG (máx. 2 MB)</div>
                                    </div>
                                </div>
                            </div>
                            <!-- Certificado -->
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-success text-white">
                                        <i class="ri-certificate-line me-1"></i> Diseño del Certificado
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <img id="edit_preview_certificado" src="#" alt="Vista previa"
                                                class="img-fluid rounded shadow-sm"
                                                style="max-height: 180px; object-fit: contain; display: none;">
                                            <div id="edit_placeholder_certificado" class="text-muted py-4"
                                                style="display: block;">
                                                <i class="ri-upload-cloud-2-line fs-1"></i><br>
                                                <small>Seleccione una imagen para previsualizar</small>
                                            </div>
                                        </div>
                                        <input type="file" name="certificado" id="edit_certificado_input"
                                            class="form-control" accept="image/png,image/jpeg,image/jpg">
                                        <div class="form-text">Formatos: PNG, JPG (máx. 2 MB)</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MÓDULOS -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2"><i class="ri-book-open-line me-1"></i> Módulos del Programa
                                </h6>
                                <div id="edit_modulos-container"></div>
                            </div>
                        </div>

                        <!-- PLANES DE PAGO -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2"><i class="ri-money-dollar-circle-line me-1"></i> Planes de
                                    Pago</h6>
                                <div id="edit_planes-pago-container"></div>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-warning" id="submitEditOfertaBtn">Actualizar
                                Oferta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Fase 2 -->
    <div class="modal fade" id="modalEditarFase2" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Oferta (Fase 2)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editFase2Form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="f2_oferta_id">

                        <!-- Responsables -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Responsable Académico *</label>
                                <select name="responsable_academico_cargo_id" id="f2_responsable_academico_cargo_id"
                                    class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($trabajadoresAcademicos as $ta)
                                        <option value="{{ $ta->id }}">
                                            {{ $ta->trabajador->persona->nombres }}
                                            {{ $ta->trabajador->persona->apellido_paterno }}
                                            ({{ $ta->cargo->nombre }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Responsable Marketing *</label>
                                <select name="responsable_marketing_cargo_id" id="f2_responsable_marketing_cargo_id"
                                    class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($trabajadoresMarketing as $tm)
                                        <option value="{{ $tm->id }}">
                                            {{ $tm->trabajador->persona->nombres }}
                                            {{ $tm->trabajador->persona->apellido_paterno }}
                                            ({{ $tm->cargo->nombre }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Fecha Inicio Inscripciones *</label>
                                <input type="date" name="fecha_inicio_inscripciones"
                                    id="f2_fecha_inicio_inscripciones" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fecha Inicio Programa *</label>
                                <input type="date" name="fecha_inicio_programa" id="f2_fecha_inicio_programa"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fecha Fin Programa *</label>
                                <input type="date" name="fecha_fin_programa" id="f2_fecha_fin_programa"
                                    class="form-control" required>
                            </div>
                        </div>

                        <!-- Color -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Color de la oferta</label>
                                <div class="d-flex align-items-center gap-3">
                                    <input type="color" id="f2_color" name="color"
                                        class="form-control form-control-color shadow-none p-1" value="#ccc">
                                    <span id="f2_preview_color" class="rounded-circle border d-inline-block"
                                        style="width: 32px; height: 32px; background-color: #ccc;"></span>
                                </div>
                                <small class="form-text text-muted">Se usará en calendarios y tarjetas.</small>
                            </div>
                        </div>

                        <!-- Imágenes -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-primary text-white">
                                        <i class="ri-image-line me-1"></i> Portada del Programa
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <img id="f2_preview_portada" src="#" alt="Vista previa"
                                                class="img-fluid rounded shadow-sm"
                                                style="max-height: 180px; object-fit: contain; display: none;">
                                            <div id="f2_placeholder_portada" class="text-muted py-4"
                                                style="display: block;">
                                                <i class="ri-upload-cloud-2-line fs-1"></i><br>
                                                <small>Seleccione una imagen para previsualizar</small>
                                            </div>
                                        </div>
                                        <input type="file" name="portada" id="f2_portada_input" class="form-control"
                                            accept="image/png,image/jpeg,image/jpg">
                                        <div class="form-text">Formatos: PNG, JPG (máx. 2 MB)</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-success text-white">
                                        <i class="ri-certificate-line me-1"></i> Diseño del Certificado
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <img id="f2_preview_certificado" src="#" alt="Vista previa"
                                                class="img-fluid rounded shadow-sm"
                                                style="max-height: 180px; object-fit: contain; display: none;">
                                            <div id="f2_placeholder_certificado" class="text-muted py-4"
                                                style="display: block;">
                                                <i class="ri-upload-cloud-2-line fs-1"></i><br>
                                                <small>Seleccione una imagen para previsualizar</small>
                                            </div>
                                        </div>
                                        <input type="file" name="certificado" id="f2_certificado_input"
                                            class="form-control" accept="image/png,image/jpeg,image/jpg">
                                        <div class="form-text">Formatos: PNG, JPG (máx. 2 MB)</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-warning">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Plan de Pago (Fase 2) -->
    <div class="modal fade" id="modalAgregarPlanPago" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Nuevo Plan de Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addPlanPagoForm">
                        @csrf
                        <input type="hidden" name="oferta_id" id="plan_oferta_id">

                        <div class="mb-3">
                            <label class="form-label">Plan de Pago *</label>
                            <select name="planes_pago_id" id="plan_planes_pago_id" class="form-control" required>
                                <option value="">Seleccione un plan disponible</option>
                                <!-- Se llenará dinámicamente -->
                            </select>
                        </div>

                        <div id="plan_conceptos_container"></div>

                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-outline-primary" id="addConceptoBtn">➕ Agregar
                                Concepto</button>
                            <button type="submit" class="btn btn-info mt-2">Guardar Plan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Inscribir Estudiante -->
    <div class="modal fade" id="modalInscribirEstudiante" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Inscribir Estudiante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Paso 1: Verificación de Carnet -->
                    <div id="paso-carnet-inscripcion">
                        <div class="mb-3">
                            <label class="form-label">Carnet *</label>
                            <input type="text" id="carnet_inscripcion" class="form-control"
                                placeholder="Ingrese carnet">
                            <div id="mensaje-verificacion-inscripcion" class="mt-2"></div>
                        </div>
                        <div class="text-end">
                            <!-- Este botón se habilitará solo si la persona NO existe -->
                            <button type="button" class="btn btn-secondary" id="btn-nueva-persona-inscripcion" disabled>
                                Registrar nueva persona
                            </button>
                        </div>
                    </div>

                    <!-- Paso 2: Confirmar Registro como Estudiante (si persona existe pero no es estudiante) -->
                    <form id="formConfirmarEstudiante" style="display:none;">
                        @csrf
                        <input type="hidden" name="persona_id" id="persona_id_confirmar">
                        <p>¿Desea registrar a <strong id="nombre_persona_confirmar"></strong> como estudiante?</p>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary"
                                id="btn-volver-carnet-confirmar">Volver</button>
                            <button type="submit" class="btn btn-success">Confirmar Registro</button>
                        </div>
                    </form>

                    <!-- Paso 3: Formulario de Nueva Persona (si persona no existe) -->
                    <form id="formNuevaPersonaInscripcion" style="display:none;" class="row g-3">
                        @csrf
                        <!-- Carnet -->
                        <div class="col-md-4">
                            <label class="form-label">Carnet *</label>
                            <input type="text" name="carnet" class="form-control" id="carnet_nuevo_inscripcion">
                            <div id="feedback_carnet_nuevo_inscripcion" class="mt-1" style="font-size:0.875em;"></div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Expedido</label>
                            <select name="expedido" class="form-select">
                                <option value="">Seleccionar</option>
                                @foreach (['Lp', 'Or', 'Pt', 'Cb', 'Ch', 'Tj', 'Be', 'Sc', 'Pn'] as $e)
                                    <option value="{{ $e }}">{{ $e }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Sexo y estado civil -->
                        <div class="col-md-3">
                            <label class="form-label">Sexo *</label>
                            <select name="sexo" class="form-select" required>
                                <option value="">Seleccionar</option>
                                <option value="Hombre">Hombre</option>
                                <option value="Mujer">Mujer</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado Civil *</label>
                            <select name="estado_civil" class="form-select" required>
                                <option value="">Seleccionar</option>
                                <option value="Soltero(a)">Soltero(a)</option>
                                <option value="Casado(a)">Casado(a)</option>
                                <option value="Divorciado(a)">Divorciado(a)</option>
                                <option value="Viudo(a)">Viudo(a)</option>
                            </select>
                        </div>
                        <!-- Nombres y apellidos -->
                        <div class="col-md-4">
                            <label class="form-label">Nombres *</label>
                            <input type="text" name="nombres" class="form-control" id="nombres_nuevo_inscripcion">
                            <div id="feedback_nombres_nuevo_inscripcion" class="text-danger mt-1"
                                style="font-size:0.875em;"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellido Paterno</label>
                            <input type="text" name="apellido_paterno" class="form-control"
                                id="paterno_nuevo_inscripcion">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellido Materno</label>
                            <input type="text" name="apellido_materno" class="form-control"
                                id="materno_nuevo_inscripcion">
                            <div id="feedback_apellidos_nuevo_inscripcion" class="text-danger mt-1"
                                style="font-size:0.875em;"></div>
                        </div>
                        <!-- Contacto -->
                        <div class="col-md-6">
                            <label class="form-label">Correo *</label>
                            <input type="email" name="correo" class="form-control" id="correo_nuevo_inscripcion">
                            <div id="feedback_correo_nuevo_inscripcion" class="mt-1" style="font-size:0.875em;"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="form-control"
                                id="fecha_nac_nuevo_inscripcion">
                            <div id="edad_calculada_nuevo_inscripcion" class="mt-1" style="font-size:0.875em;"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Celular *</label>
                            <input type="text" name="celular" class="form-control" id="celular_nuevo_inscripcion">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                        <!-- DEPARTAMENTO -->
                        <div class="col-md-3">
                            <label class="form-label">Departamento *</label>
                            <select name="departamento_id" id="departamento_nuevo_inscripcion" class="form-select"
                                required>
                                <option value="">Seleccionar</option>
                                @foreach ($departamentos as $d)
                                    <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- CIUDAD -->
                        <div class="col-md-3">
                            <label class="form-label">Ciudad *</label>
                            <select name="ciudade_id" id="ciudad_nuevo_inscripcion" class="form-select" required
                                disabled>
                                <option value="">Primero seleccione un departamento</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Dirección</label>
                            <textarea name="direccion" class="form-control"></textarea>
                        </div>
                        <!-- Estudios Académicos -->
                        <div class="col-12 mt-3">
                            <h6>Estudios Académicos</h6>
                            <div id="estudios-container-nuevo-incripcion">
                                <div class="estudio-item-nuevo row mb-2">
                                    <div class="col-md-3">
                                        <select class="form-select grado-select-nuevo" name="estudios[0][grado]">
                                            <option value="">Grado</option>
                                            @foreach ($grados as $g)
                                                <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select profesion-select-nuevo" name="estudios[0][profesion]"
                                            disabled>
                                            <option value="">Profesión</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select universidad-select-nuevo"
                                            name="estudios[0][universidad]" disabled>
                                            <option value="">Universidad</option>
                                            @foreach ($universidades as $u)
                                                <option value="{{ $u->id }}">{{ $u->nombre }}
                                                    ({{ $u->sigla }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-success btn-sm add-estudio-nuevo">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-3">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary"
                                    id="btn-volver-carnet2-incripcion">Volver</button>
                                <button type="submit" class="btn btn-primary" id="btn-guardar-nueva-persona-incripcion"
                                    disabled>Registrar como Estudiante</button>
                            </div>
                        </div>
                    </form>

                    <!-- Paso 4: Formulario de Inscripción (una vez que se tiene el estudiante) -->
                    <form id="formInscripcion" style="display:none;">
                        @csrf
                        <input type="hidden" name="oferta_id" id="oferta_id_inscripcion">
                        <input type="hidden" name="estudiante_id" id="estudiante_id_inscripcion">

                        <div class="mb-3">
                            <label class="form-label">Estado *</label>
                            <select name="estado" class="form-select" id="estado_inscripcion" required>
                                <option value="">Seleccione...</option>
                                <option value="Pre-Inscrito">Pre-Inscrito</option>
                                <option value="Inscrito">Inscrito</option>
                            </select>
                        </div>

                        <div id="planes-pago-section" style="display:none;">
                            <div class="mb-3">
                                <label class="form-label">Plan de Pago *</label>
                                <select name="planes_pago_id" class="form-select" id="planes_pago_select">
                                    <option value="">Seleccione un plan</option>
                                    <!-- Se llenará dinámicamente -->
                                </select>
                            </div>
                        </div>

                        <!-- Sección de vista previa de cuotas -->
                        <div id="cuotas-preview-section" style="display:none;">
                            <h6 class="mt-3">Vista Previa de Cuotas</h6>
                            <div id="cuotas-preview-container"></div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-outline-secondary" id="generar-cuotas-btn">Generar
                                    Cuotas</button>
                                <button type="button" class="btn btn-success" id="confirmar-cuotas-btn"
                                    style="display:none;">Confirmar Cuotas</button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary"
                                id="btn-volver-estudiante-incripcion">Volver</button>
                            <button type="submit" class="btn btn-success">Registrar Inscripción</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ver Planes de Pago -->
    <div class="modal fade" id="modalVerPlanesPago" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-money-dollar-circle-line me-2"></i>
                        Planes de Pago - <span id="planes_oferta_codigo"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3" id="loadingPlanes">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando planes de pago...</p>
                    </div>

                    <div id="planesPagoContainer" style="display: none;">
                        <!-- Aquí se cargarán los planes dinámicamente -->
                    </div>

                    <div id="sinPlanes" class="text-center py-5" style="display: none;">
                        <i class="ri-inbox-line fs-1 text-muted"></i>
                        <h5 class="mt-3 text-muted">No hay planes de pago registrados</h5>
                        <p class="text-muted">Esta oferta académica no tiene planes de pago configurados.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Planes de Pago -->
    <div class="modal fade" id="modalEditarPlanesPago" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-edit-line me-2"></i>
                        Editar Planes de Pago - <span id="editar_planes_oferta_codigo"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3" id="loadingEditarPlanes">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando planes de pago para edición...</p>
                    </div>

                    <div id="editarPlanesContainer" style="display: none;">
                        <!-- Aquí se cargarán los planes dinámicamente con controles de edición -->
                    </div>

                    <div id="sinPlanesEditar" class="text-center py-5" style="display: none;">
                        <i class="ri-inbox-line fs-1 text-muted"></i>
                        <h5 class="mt-3 text-muted">No hay planes de pago registrados</h5>
                        <p class="text-muted">Esta oferta académica no tiene planes de pago configurados.</p>
                        <button type="button" class="btn btn-info addPlanPagoBtnFromEdit" data-oferta-id=""
                            data-bs-dismiss="modal">
                            <i class="ri-add-line me-1"></i> Agregar Primer Plan
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" id="guardarCambiosPlanesBtn" style="display: none;">
                        <i class="ri-save-line me-1"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <style>
        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        <style>.plan-card {
            border-left: 4px solid var(--bs-primary);
            transition: transform 0.2s ease;
        }

        .plan-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .concepto-item {
            border-bottom: 1px solid #f0f0f0;
            padding: 0.75rem 0;
        }

        .concepto-item:last-child {
            border-bottom: none;
        }

        .cuota-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .monto-total {
            font-weight: 600;
            color: var(--bs-success);
        }

        .plan-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
        }

        .plan-card {
            border-left: 4px solid var(--bs-primary);
            transition: transform 0.2s ease;
        }

        .plan-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .concepto-item {
            border-bottom: 1px solid #f0f0f0;
            padding: 0.75rem 0;
        }

        .concepto-item:last-child {
            border-bottom: none;
        }

        .cuota-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .monto-total {
            font-weight: 600;
            color: var(--bs-success);
        }

        .plan-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
        }

        /* Estilos para el acordeón */
        .accordion-button:not(.collapsed) {
            background-color: rgba(var(--bs-primary-rgb), 0.1);
            color: var(--bs-primary);
        }

        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
        }

        .table-group-divider {
            border-top-color: var(--bs-primary);
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .card-header {
            border-bottom: none;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .table tfoot tr {
            background-color: #f8f9fa;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        /* Estilos específicos para el primer plan (destacado) */
        .border-primary {
            border-width: 2px !important;
        }

        /* Para planes adicionales */
        .border-secondary {
            border-width: 1px !important;
        }

        /* Estilos para los filtros */
        .card-body.border-dashed {
            border-style: dashed !important;
        }

        .form-select {
            background-color: #fff;
            border: 1px solid #ced4da;
            transition: border-color 0.15s ease-in-out;
        }

        .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        /* Estilos generales para la tabla */
        .table-hover tbody tr:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        /* Estilos para botones */
        .btn-sm {
            border-radius: 4px !important;
            font-weight: 500 !important;
        }

        /* Colores personalizados para botones */
        .btn-teal {
            background: linear-gradient(135deg, #20c997 0%, #1ba87e 100%);
            border: none;
            color: white;
        }

        .btn-purple {
            background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
            border: none;
            color: white;
        }

        .btn-orange {
            background: linear-gradient(135deg, #fd7e14 0%, #e56b00 100%);
            border: none;
            color: white;
        }

        .btn-indigo {
            background: linear-gradient(135deg, #4B0082 0%, #3a0063 100%);
            border: none;
            color: white;
        }

        /* Badges mejorados */
        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        /* Efectos hover para botones */
        .btn-teal:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-purple:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-orange:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-indigo:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* Estilos para las celdas */
        .table>tbody>tr>td {
            vertical-align: middle;
            padding: 12px 8px;
        }

        /* Estilos para las imágenes */
        .table img {
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Mejora visual para la paginación */
        .pagination .page-link {
            border-radius: 4px;
            margin: 0 3px;
            font-weight: 500;
        }

        /* Estilos para el estado vacío */
        .table tbody tr td[colspan] {
            background-color: #f8f9fa;
        }

        /* Estilos para la imagen del convenio */
        .convenio-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e9ecef;
        }

        /* Estilos para el modal de edición de planes */
        .input-group-sm .input-group-text {
            font-size: 0.875rem;
        }

        .concepto-select-editar,
        .n-cuotas-editar,
        .monto-cuota-editar {
            min-width: 100px;
        }

        .eliminarConceptoBtn:hover {
            background-color: #dc3545;
            color: white;
        }

        .agregarConceptoPlanBtn:hover {
            background-color: #198754;
            color: white;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Configuración global de Toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
    </script>
    <script>
        const PLANES_PAGOS = @json($planesPagos);
        const CONCEPTOS = @json($conceptos);
    </script>
    <script>
        function refreshFeather() {
            if (typeof window.feather !== 'undefined') {
                window.feather.replace();
            }
        }

        $(document).ready(function() {
            refreshFeather();

            // Función para depurar el HTML recibido
            function debugTableHTML(html) {
                console.log('HTML recibido:', html);

                // Crear un elemento temporal para analizar la estructura
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;

                // Contar filas y columnas
                const rows = tempDiv.querySelectorAll('tr');
                console.log(`Número de filas: ${rows.length}`);

                rows.forEach((row, index) => {
                    const cols = row.querySelectorAll('td, th');
                    console.log(`Fila ${index + 1}: ${cols.length} columnas`);

                    cols.forEach((col, colIndex) => {
                        console.log(`  Col ${colIndex + 1}: ${col.innerHTML.substring(0, 50)}...`);
                    });
                });
            }

            // Modifica la función loadResults para incluir depuración
            function loadResults() {
                const params = {
                    sucursale_id: $('#filtroSucursal').val() || '',
                    convenio_id: $('#filtroConvenio').val() || '',
                    fase_id: $('#filtroFase').val() || '',
                    modalidade_id: $('#filtroModalidad').val() || '',
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: '{{ route('admin.ofertas.listar') }}',
                    type: 'GET',
                    data: params,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    // En la función success del AJAX, reemplazar toda la tabla
                    success: function(res) {
                        if (res.success) {
                            // Crear una nueva tabla completa con el HTML recibido
                            const newTable = `
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle" style="min-width: 1100px;">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th width="20">N°</th>
                            <th width="60">Código</th>
                            <th width="120">Programa</th>
                            <th width="80">N° Módulos</th>
                            <th width="50">Convenio</th>
                            <th width="80">Modalidad</th>
                            <th width="80">Inicio - Fin</th>
                            <th width="70">Inscritos</th>
                            <th width="50">Fase</th>
                            <th width="200">Acciones</th>
                        </tr>
                    </thead>
                    ${res.html}
                </table>
            </div>
        `;

                            // Reemplazar solo la tabla
                            $('#results-container .table-responsive').replaceWith(newTable);
                            $('#pagination-container').html(res.pagination);
                            refreshFeather();
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                    }
                });
            }

            // Agrega este estilo CSS para el indicador de carga
            $(document).ready(function() {
                // Agregar estilos para el loading
                $('head').append(`
        <style>
            .loading {
                opacity: 0.7;
                pointer-events: none;
                position: relative;
            }
            .loading::after {
                content: 'Cargando...';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                padding: 10px 20px;
                border-radius: 5px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                z-index: 1000;
            }
        </style>
    `);
            });

            // Event listeners for filters
            $('#filtroSucursal, #filtroConvenio, #filtroFase, #filtroModalidad').on('change', function() {
                loadResults();
            });

            $('#clearFilters').on('click', function() {
                $('#filtroSucursal').val('');
                $('#filtroConvenio').val('');
                $('#filtroFase').val('');
                $('#filtroModalidad').val('');
                loadResults();
            });

            // Handle pagination clicks
            $(document).on('click', '#pagination-container .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');

                // Extract query parameters
                const urlObj = new URL(url);
                const page = urlObj.searchParams.get('page');

                // Get current filter values
                const sucursale_id = $('#filtroSucursal').val() || '';
                const convenio_id = $('#filtroConvenio').val() || '';
                const fase_id = $('#filtroFase').val() || '';
                const modalidade_id = $('#filtroModalidad').val() || '';

                $.ajax({
                    url: url,
                    data: {
                        sucursale_id: sucursale_id,
                        convenio_id: convenio_id,
                        fase_id: fase_id,
                        modalidade_id: modalidade_id,
                        page: page
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(res) {
                        if (typeof res === 'object' && res.html) {
                            $('#results-container table tbody').html(res.html);
                            $('#pagination-container').html(res.pagination);
                        } else {
                            $('#results-container').html($(res).find('#results-container')
                                .html());
                            $('#pagination-container').html($(res).find('#pagination-container')
                                .html());
                        }
                        refreshFeather();
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al cargar la página'
                        });
                    }
                });
            });

            // === CAMBIAR FASE ===
            $(document).on('click', '.change-phase', function() {
                const btn = $(this);
                const id = btn.data('oferta-id');
                const dir = btn.data('direction');

                // Mostrar loading en el botón
                const originalHtml = btn.html();
                btn.html('<i class="ri-loader-4-line spin"></i>');
                btn.prop('disabled', true);

                $.ajax({
                    url: `/admin/ofertas/${id}/cambiar-fase`,
                    method: 'POST',
                    data: {
                        direction: dir,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.success) {
                            // Mostrar toast de éxito
                            mostrarToast('success', res.msg || 'Fase cambiada exitosamente.');

                            // Actualizar la fila
                            const row = btn.closest('tr');
                            row.find('.badge.text-white').text(res.fase_nombre).css(
                                'background-color', res.fase_color);
                            row.css('background-color', res.bg_color);
                            row.find('td:last-child').html(res.acciones_html);
                            refreshFeather();
                        } else {
                            mostrarToast('error', res.msg || 'Error al cambiar la fase.');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errorMsg = xhr.responseJSON?.msg ||
                                'No se puede cambiar la fase.';

                            // Mostrar modal de error con SweetAlert o similar
                            Swal.fire({
                                icon: 'warning',
                                title: 'Validación Requerida',
                                html: errorMsg,
                                confirmButtonText: 'Entendido',
                                showCancelButton: errorMsg.includes('plan de pago'),
                                cancelButtonText: 'Ver Planes',
                                showCloseButton: true,
                                customClass: {
                                    confirmButton: 'btn btn-primary',
                                    cancelButton: 'btn btn-info'
                                }
                            }).then((result) => {
                                if (result.dismiss === 'cancel') {
                                    // Si el usuario hace clic en "Ver Planes"
                                    const ofertaId = id;
                                    const ofertaCodigo = btn.closest('tr').find(
                                        'td:nth-child(2)').text();

                                    // Abrir modal de planes de pago
                                    $('#planes_oferta_codigo').text(ofertaCodigo);
                                    $('#loadingPlanes').show();
                                    $('#planesPagoContainer').hide();
                                    $('#sinPlanes').hide();
                                    $('#modalVerPlanesPago').modal('show');

                                    // Cargar planes
                                    $.ajax({
                                        url: `/admin/ofertas/${ofertaId}/planes-pago`,
                                        method: 'GET',
                                        success: function(res) {
                                            $('#loadingPlanes').hide();
                                            if (res.success && res.planes
                                                .length > 0) {
                                                renderizarPlanesPago(res
                                                    .planes);
                                                $('#planesPagoContainer')
                                                    .show();
                                            } else {
                                                $('#sinPlanes').show();
                                            }
                                        },
                                        error: function() {
                                            $('#loadingPlanes').hide();
                                            $('#sinPlanes').show();
                                        }
                                    });
                                }
                            });
                        } else {
                            mostrarToast('error', 'Error al cambiar la fase.');
                        }
                    },
                    complete: function() {
                        // Restaurar botón
                        btn.html(originalHtml);
                        btn.prop('disabled', false);
                    }
                });
            });

            // Función para mostrar toast (necesitas incluir SweetAlert o usar otro método)
            function mostrarToast(icon, message) {
                if (typeof Toast !== 'undefined') {
                    Toast.fire({
                        icon: icon,
                        title: message
                    });
                } else if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: icon,
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else {
                    alert(message); // Fallback simple
                }
            }

            // === CARGAR DATOS EN MODAL DE EDICIÓN ===
            $(document).on('click', '.editOfertaBtn', function() {
                const id = $(this).data('oferta-id');
                $.get(`/admin/ofertas/${id}/editar`, function(oferta) {
                    // Campos básicos
                    $('#edit_oferta_id').val(oferta.id);
                    $('#edit_codigo').val(oferta.codigo);
                    $('#edit_gestion').val(oferta.gestion);
                    $('#edit_n_modulos').val(oferta.n_modulos || 1);
                    $('#edit_cantidad_sesiones').val(oferta.cantidad_sesiones || 1);
                    $('#edit_version').val(oferta.version || '1');
                    $('#edit_grupo').val(oferta.grupo || '1');
                    $('#edit_nota_minima').val(oferta.nota_minima || 61);
                    $('#edit_fecha_inicio_inscripciones').val(oferta.fecha_inicio_inscripciones ||
                        '');
                    $('#edit_fecha_inicio_programa').val(oferta.fecha_inicio_programa || '');
                    $('#edit_fecha_fin_programa').val(oferta.fecha_fin_programa || '');
                    $('#edit_modalidade_id').val(oferta.modalidade_id);
                    $('#edit_responsable_academico_cargo_id').val(oferta
                        .responsable_academico_cargo_id);
                    $('#edit_responsable_marketing_cargo_id').val(oferta
                        .responsable_marketing_cargo_id);
                    $('#edit_programa_nombre').val(oferta.programa?.nombre || '');
                    $('#edit_programa_id').val(oferta.programa_id);
                    const color = oferta.color || '#cccccc';
                    $('#edit_color').val(color);
                    $('#edit_preview_color').css('background-color', color);

                    // Limpiar placeholders de imágenes
                    $('#edit_preview_portada, #edit_preview_certificado').hide();
                    $('#edit_placeholder_portada, #edit_placeholder_certificado').show();

                    // Sede y sucursal
                    $('#edit_sede_id').val(oferta.sucursal?.sede_id).trigger('change');
                    setTimeout(() => {
                        $('#edit_sucursale_id').val(oferta.sucursale_id);
                    }, 300);

                    // Módulos
                    let modHtml = '';
                    if (oferta.modulos?.length) {
                        oferta.modulos.forEach(m => {
                            modHtml += `
                            <div class="row mb-2">
                                <div class="col-md-1">
                                    <input type="hidden" name="modulos[${m.n_modulo}][n_modulo]" value="${m.n_modulo}">
                                    <span class="form-control-plaintext">${m.n_modulo}</span>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="modulos[${m.n_modulo}][nombre]" class="form-control" value="${m.nombre}" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="modulos[${m.n_modulo}][fecha_inicio]" class="form-control" value="${m.fecha_inicio?.split(' ')[0] || ''}" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="modulos[${m.n_modulo}][fecha_fin]" class="form-control" value="${m.fecha_fin?.split(' ')[0] || ''}" required>
                                </div>
                            </div>`;
                        });
                    }
                    $('#edit_modulos-container').html(modHtml ||
                        '<p class="text-muted">Sin módulos.</p>');

                    // Planes de pago
                    let planesHtml = '';
                    const agrupados = {};
                    if (oferta.plan_concepto) {
                        oferta.plan_concepto.forEach(pc => {
                            if (!agrupados[pc.planes_pago_id]) {
                                agrupados[pc.planes_pago_id] = {
                                    nombre: PLANES_PAGOS.find(p => p.id == pc
                                            .planes_pago_id)?.nombre || 'Plan ' + pc
                                        .planes_pago_id,
                                    conceptos: []
                                };
                            }
                            agrupados[pc.planes_pago_id].conceptos.push(pc);
                        });
                    }
                    Object.entries(agrupados).forEach(([planId, data]) => {
                        planesHtml += `
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between">
                                <strong>${data.nombre}</strong>
                                <button type="button" class="btn btn-sm btn-outline-primary add-concepto-edit" data-plan-id="${planId}">➕ Concepto</button>
                            </div>
                            <div class="card-body conceptos-container" id="conceptos_edit_${planId}">
                                ${data.conceptos.map((pc, idx) => `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <div class="row mb-2 concepto-item">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <div class="col-md-5">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <select name="planes[${planId}][${idx}][concepto_id]" class="form-control" required>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ${CONCEPTOS.map(c => `<option value="${c.id}" ${c.id == pc.concepto_id ? 'selected' : ''}>${c.nombre}</option>`).join('')}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </select>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <div class="col-md-2">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <input type="number" name="planes[${planId}][${idx}][n_cuotas]" class="form-control" value="${pc.n_cuotas}" min="1" required>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <div class="col-md-3">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <input type="number" step="0.01" name="planes[${planId}][${idx}][pago_bs]" class="form-control" value="${pc.pago_bs}" min="0" required>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <div class="col-md-2 d-flex align-items-end">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <button type="button" class="btn btn-sm btn-outline-danger remove-concepto">🗑️</button>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                `).join('')}
                            </div>
                        </div>`;
                    });
                    $('#edit_planes-pago-container').html(planesHtml ||
                        '<p class="text-muted">Sin planes de pago.</p>');

                    $('#modalEditarOferta').modal('show');
                });
            });

            // Previsualización de imágenes en edición
            document.getElementById('edit_portada_input').addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById('edit_preview_portada');
                const placeholder = document.getElementById('edit_placeholder_portada');
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        preview.src = event.target.result;
                        preview.style.display = 'block';
                        placeholder.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.style.display = 'none';
                    placeholder.style.display = 'block';
                }
            });

            document.getElementById('edit_certificado_input').addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById('edit_preview_certificado');
                const placeholder = document.getElementById('edit_placeholder_certificado');
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        preview.src = event.target.result;
                        preview.style.display = 'block';
                        placeholder.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.style.display = 'none';
                    placeholder.style.display = 'block';
                }
            });

            // Color preview
            $('#edit_color').on('input', function() {
                const color = $(this).val();
                $('#edit_preview_color').css('background-color', color);
            });

            // Sede → Sucursal en edición
            $('#edit_sede_id').on('change', function() {
                const sedeId = $(this).val();
                const sel = $('#edit_sucursale_id');
                sel.empty().prop('disabled', true);
                if (!sedeId) {
                    sel.append('<option value="">Seleccione sede primero</option>');
                    return;
                }
                $.get("{{ route('admin.sucursales.por-sede') }}", {
                    sede_id: sedeId
                }, function(data) {
                    sel.prop('disabled', false).append(
                        '<option value="">Seleccione sucursal</option>');
                    data.forEach(s => sel.append(`<option value="${s.id}">${s.nombre}</option>`));
                });
            });

            // Validación de código único en edición
            let editDebounceCodigo;
            $('#edit_codigo').on('input', function() {
                const codigo = $(this).val().trim();
                const id = $('#edit_oferta_id').val();
                if (!codigo) return;
                clearTimeout(editDebounceCodigo);
                editDebounceCodigo = setTimeout(() => {
                    $.post("{{ route('admin.ofertas-academicas.verificar-codigo') }}", {
                        _token: '{{ csrf_token() }}',
                        codigo: codigo,
                        exclude_id: id
                    }, function(res) {
                        const fb = $('#edit_feedback_codigo');
                        if (res.exists) {
                            fb.removeClass('text-success').addClass('text-danger').text(
                                '❌ Código ya en uso.');
                        } else {
                            fb.removeClass('text-danger').addClass('text-success').text(
                                '✅ Disponible.');
                        }
                    });
                }, 400);
            });

            // Generar módulos al cambiar n_modulos
            $('#edit_n_modulos').on('input change', function() {
                const n = parseInt($(this).val()) || 0;
                const container = $('#edit_modulos-container');
                container.empty();
                if (n < 1) {
                    container.html('<p class="text-muted">Ingrese ≥1 módulo.</p>');
                    return;
                }
                let html = '';
                for (let i = 1; i <= n; i++) {
                    html += `
                    <div class="row mb-2">
                        <div class="col-md-1">
                            <input type="hidden" name="modulos[${i}][n_modulo]" value="${i}">
                            <span class="form-control-plaintext">${i}</span>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="modulos[${i}][nombre]" class="form-control" placeholder="Nombre del módulo ${i}" required>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="modulos[${i}][fecha_inicio]" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="modulos[${i}][fecha_fin]" class="form-control" required>
                        </div>
                    </div>`;
                }
                container.html(html);
            });

            // Conceptos dinámicos en edición
            $(document).on('click', '.add-concepto-edit', function() {
                const planId = $(this).data('plan-id');
                const container = $(`#conceptos_edit_${planId}`);
                const idx = container.children('.concepto-item').length;
                let opts = '<option value="">Concepto</option>';
                CONCEPTOS.forEach(c => opts += `<option value="${c.id}">${c.nombre}</option>`);
                const html = `
                <div class="row mb-2 concepto-item">
                    <div class="col-md-5">
                        <select name="planes[${planId}][${idx}][concepto_id]" class="form-control" required>${opts}</select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="planes[${planId}][${idx}][n_cuotas]" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" name="planes[${planId}][${idx}][pago_bs]" class="form-control" min="0" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-concepto">🗑️</button>
                    </div>
                </div>`;
                container.append(html);
            });

            $(document).on('click', '.remove-concepto', function() {
                $(this).closest('.concepto-item').remove();
            });

            // Submit edición
            $('#editOfertaForm').submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                // 👇 Agrega esto temporalmente para depurar
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }
                $.ajax({
                    url: "{{ route('admin.ofertas.actualizar') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        alert(res.msg);
                        if (res.success) {
                            $('#modalEditarOferta').modal('hide');
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.msg || 'Error al actualizar.');
                    }
                });
            });
        });

        // === CARGAR DATOS EN MODAL FASE 2 ===
        $(document).on('click', '.editFase2Btn', function() {
            const id = $(this).data('oferta-id');
            $.get(`/admin/ofertas/${id}/editar`, function(oferta) {
                $('#f2_oferta_id').val(oferta.id);
                $('#f2_responsable_academico_cargo_id').val(oferta.responsable_academico_cargo_id);
                $('#f2_responsable_marketing_cargo_id').val(oferta.responsable_marketing_cargo_id);
                $('#f2_fecha_inicio_inscripciones').val(oferta.fecha_inicio_inscripciones?.split(' ')[0] ||
                    '');
                $('#f2_fecha_inicio_programa').val(oferta.fecha_inicio_programa?.split(' ')[0] || '');
                $('#f2_fecha_fin_programa').val(oferta.fecha_fin_programa?.split(' ')[0] || '');

                // Limpiar imágenes
                $('#f2_preview_portada, #f2_preview_certificado').hide();
                $('#f2_placeholder_portada, #f2_placeholder_certificado').show();

                $('#modalEditarFase2').modal('show');
            });
        });

        // Previsualización imágenes Fase 2
        $('#f2_portada_input').on('change', function(e) {
            const file = e.target.files[0];
            const preview = $('#f2_preview_portada');
            const placeholder = $('#f2_placeholder_portada');
            if (file) {
                const reader = new FileReader();
                reader.onload = () => {
                    preview.attr('src', reader.result).show();
                    placeholder.hide();
                };
                reader.readAsDataURL(file);
            } else {
                preview.hide();
                placeholder.show();
            }
        });
        $('#f2_certificado_input').on('change', function(e) {
            const file = e.target.files[0];
            const preview = $('#f2_preview_certificado');
            const placeholder = $('#f2_placeholder_certificado');
            if (file) {
                const reader = new FileReader();
                reader.onload = () => {
                    preview.attr('src', reader.result).show();
                    placeholder.hide();
                };
                reader.readAsDataURL(file);
            } else {
                preview.hide();
                placeholder.show();
            }
        });

        // Submit Fase 2
        $('#editFase2Form').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            $.ajax({
                url: "{{ route('admin.ofertas.actualizar') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    alert(res.msg);
                    if (res.success) {
                        $('#modalEditarFase2').modal('hide');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.msg || 'Error al actualizar.');
                }
            });
        });

        // === AGREGAR PLAN DE PAGO ===
        // === Cargar planes NO usados en la oferta ===
        $(document).on('click', '.addPlanPagoBtn', function() {
            const ofertaId = $(this).data('oferta-id');
            $('#plan_oferta_id').val(ofertaId);

            // Obtener planes ya usados en esta oferta
            $.get(`/admin/ofertas/${ofertaId}/datos`, function(oferta) {
                const usados = new Set();
                if (oferta.plan_concepto && Array.isArray(oferta.plan_concepto)) {
                    oferta.plan_concepto.forEach(pc => usados.add(pc.planes_pago_id));
                }

                // Llenar select con planes NO usados
                let opts = '<option value="">Seleccione un plan</option>';
                PLANES_PAGOS.forEach(plan => {
                    if (!usados.has(plan.id)) {
                        opts += `<option value="${plan.id}">${plan.nombre}</option>`;
                    }
                });
                $('#plan_planes_pago_id').html(opts);

                // Limpiar conceptos
                $('#plan_conceptos_container').empty();
                $('#modalAgregarPlanPago').modal('show');
            });
        });

        // Agregar concepto dinámico
        $('#addConceptoBtn').on('click', function() {
            const idx = $('#plan_conceptos_container .concepto-item').length;
            let opts = '<option value="">Concepto</option>';
            CONCEPTOS.forEach(c => opts += `<option value="${c.id}">${c.nombre}</option>`);
            const html = `
    <div class="row mb-2 concepto-item">
        <div class="col-md-5">
            <select name="conceptos[${idx}][concepto_id]" class="form-control" required>${opts}</select>
        </div>
        <div class="col-md-2">
            <input type="number" name="conceptos[${idx}][n_cuotas]" class="form-control" min="1" value="1" required>
        </div>
        <div class="col-md-3">
            <input type="number" step="0.01" name="conceptos[${idx}][pago_bs]" class="form-control" min="0" value="0" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-sm btn-outline-danger remove-concepto">🗑️</button>
        </div>
    </div>`;
            $('#plan_conceptos_container').append(html);
        });

        $(document).on('click', '.remove-concepto', function() {
            $(this).closest('.concepto-item').remove();
        });

        // Submit nuevo plan
        $('#addPlanPagoForm').submit(function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            $.post("{{ route('admin.ofertas.agregar-plan-pago') }}", formData)
                .done(function(res) {
                    alert(res.msg);
                    if (res.success) {
                        $('#modalAgregarPlanPago').modal('hide');
                        location.reload();
                    }
                })
                .fail(function(xhr) {
                    alert(xhr.responseJSON?.msg || 'Error al guardar el plan.');
                });
        });

        // === CARGAR DATOS EN MODAL FASE 2 ===
        // === FASE 2: Cargar y actualizar ===
        $(document).on('click', '.editFase2Btn', function() {
            const id = $(this).data('oferta-id');
            $.get(`/admin/ofertas/${id}/editar`, function(oferta) {
                $('#f2_oferta_id').val(oferta.id);
                $('#f2_responsable_academico_cargo_id').val(oferta.responsable_academico_cargo_id);
                $('#f2_responsable_marketing_cargo_id').val(oferta.responsable_marketing_cargo_id);
                $('#f2_fecha_inicio_inscripciones').val(oferta.fecha_inicio_inscripciones?.split(' ')[0] ||
                    '');
                $('#f2_fecha_inicio_programa').val(oferta.fecha_inicio_programa?.split(' ')[0] || '');
                $('#f2_fecha_fin_programa').val(oferta.fecha_fin_programa?.split(' ')[0] || '');
                const color = oferta.color || '#cccccc';
                $('#f2_color').val(color);
                $('#f2_preview_color').css('background-color', color);

                $('#f2_preview_portada, #f2_preview_certificado').hide();
                $('#f2_placeholder_portada, #f2_placeholder_certificado').show();

                $('#modalEditarFase2').modal('show');
            });
        });

        $('#f2_color').on('input', function() {
            $('#f2_preview_color').css('background-color', $(this).val());
        });

        $('#f2_portada_input').on('change', function(e) {
            const file = e.target.files[0];
            const preview = $('#f2_preview_portada');
            const placeholder = $('#f2_placeholder_portada');
            if (file) {
                const reader = new FileReader();
                reader.onload = () => {
                    preview.attr('src', reader.result).show();
                    placeholder.hide();
                };
                reader.readAsDataURL(file);
            } else {
                preview.hide();
                placeholder.show();
            }
        });

        $('#f2_certificado_input').on('change', function(e) {
            const file = e.target.files[0];
            const preview = $('#f2_preview_certificado');
            const placeholder = $('#f2_placeholder_certificado');
            if (file) {
                const reader = new FileReader();
                reader.onload = () => {
                    preview.attr('src', reader.result).show();
                    placeholder.hide();
                };
                reader.readAsDataURL(file);
            } else {
                preview.hide();
                placeholder.show();
            }
        });

        $('#editFase2Form').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            $.ajax({
                url: "{{ route('admin.ofertas.fase2.actualizar') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    alert(res.msg);
                    if (res.success) {
                        $('#modalEditarFase2').modal('hide');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.msg || 'Error al actualizar en fase 2.');
                }
            });
        });
    </script>
    <script>
        // === INSCRIPCIÓN DE ESTUDIANTE ===
        // Variables para el debounce
        let debounceTimerInscripcion;
        let lastCarnetValue = '';

        $(document).on('click', '.inscribirEstudianteBtn', function() {
            const ofertaId = $(this).data('oferta-id');
            $('#oferta_id_inscripcion').val(ofertaId);

            // Resetear todo el modal
            $('#carnet_inscripcion').val('');
            $('#mensaje-verificacion-inscripcion').html('');
            $('#paso-carnet-inscripcion').show();
            $('#formInscripcion, #formConfirmarEstudiante, #formNuevaPersonaInscripcion').hide();
            $('#btn-nueva-persona-inscripcion').prop('disabled', true);

            $('#modalInscribirEstudiante').modal('show');
        });

        // Verificación de carnet en inscripción CON DEBOUNCE
        $('#carnet_inscripcion').on('input', function() {
            const carnet = $(this).val().trim();
            lastCarnetValue = carnet;

            // Limpiar mensajes anteriores
            $('#mensaje-verificacion-inscripcion').html('');

            // Ocultar todos los formularios excepto el paso del carnet
            $('#formInscripcion, #formConfirmarEstudiante, #formNuevaPersonaInscripcion').hide();
            $('#paso-carnet-inscripcion').show();

            if (!carnet) {
                $('#btn-nueva-persona-inscripcion').prop('disabled', true);
                return;
            }

            // Aplicar debounce - esperar 500ms después de la última tecla
            clearTimeout(debounceTimerInscripcion);

            debounceTimerInscripcion = setTimeout(() => {
                // Verificar si el valor no ha cambiado durante el debounce
                if ($('#carnet_inscripcion').val().trim() !== lastCarnetValue) {
                    return;
                }

                // Mostrar indicador de carga
                $('#mensaje-verificacion-inscripcion').html(
                    '<div class="text-info"><i class="ri-loader-4-line spin"></i> Verificando carnet...</div>'
                );

                $.post("{{ route('admin.estudiantes.verificar-carnet') }}", {
                    _token: "{{ csrf_token() }}",
                    carnet: carnet
                }, function(res) {
                    if (res.exists) {
                        if (res.is_student) {
                            const ofertaId = $('#oferta_id_inscripcion').val();
                            // Verificar si ya está inscrito
                            $.post("{{ route('admin.inscripciones.verificar-inscripcion-existente') }}", {
                                _token: "{{ csrf_token() }}",
                                estudiante_id: res.estudiante_id,
                                oferta_id: ofertaId
                            }, function(verif) {
                                if (verif.exists) {
                                    $('#mensaje-verificacion-inscripcion').html(
                                        '<div class="alert alert-warning">⚠️ Esta persona ya está inscrita o pre-inscrita en esta oferta académica.</div>'
                                    );
                                    $('#btn-nueva-persona-inscripcion').prop('disabled',
                                        true);
                                } else {
                                    $('#mensaje-verificacion-inscripcion').html(
                                        '<div class="alert alert-success">✅ Persona encontrada y registrada como estudiante.</div>'
                                    );
                                    $('#estudiante_id_inscripcion').val(res.estudiante_id);
                                    $('#formInscripcion').show();
                                    $('#paso-carnet-inscripcion, #formConfirmarEstudiante, #formNuevaPersonaInscripcion')
                                        .hide();
                                    cargarPlanesPago(res.estudiante_id, ofertaId);
                                }
                            }).fail(function() {
                                $('#mensaje-verificacion-inscripcion').html(
                                    '<div class="alert alert-danger">Error al verificar inscripción existente.</div>'
                                );
                            });
                        } else {
                            // Persona existe pero no es estudiante
                            $('#mensaje-verificacion-inscripcion').html(
                                '<div class="alert alert-info">👤 Persona registrada pero no es estudiante.</div>'
                            );
                            $('#persona_id_confirmar').val(res.persona.id);
                            $('#nombre_persona_confirmar').text(res.persona.nombre_completo);
                            $('#formConfirmarEstudiante').show();
                            $('#paso-carnet-inscripcion, #formNuevaPersonaInscripcion, #formInscripcion')
                                .hide();
                            $('#btn-nueva-persona-inscripcion').prop('disabled', true);
                        }
                    } else {
                        // Persona no existe
                        $('#mensaje-verificacion-inscripcion').html(
                            '<div class="alert alert-danger">❌ Persona no registrada en el sistema.</div>'
                        );
                        // Mostrar botón para nueva persona
                        $('#btn-nueva-persona-inscripcion').prop('disabled', false);
                        // NO mostramos automáticamente el formulario - solo habilitamos el botón
                    }
                }).fail(function() {
                    $('#mensaje-verificacion-inscripcion').html(
                        '<div class="alert alert-danger">Error al verificar el carnet.</div>'
                    );
                });
            }, 500); // 500ms de debounce
        });

        // Función para cargar los planes de pago disponibles para la oferta
        function cargarPlanesPago(estudianteId, ofertaId) {
            $.get(`/admin/ofertas/${ofertaId}/datos`, function(oferta) {
                let planes = new Set();
                if (oferta.plan_concepto) {
                    oferta.plan_concepto.forEach(pc => planes.add(pc.planes_pago_id));
                }
                let opts = '<option value="">Seleccione</option>';
                PLANES_PAGOS.filter(p => planes.has(p.id)).forEach(p => {
                    opts += `<option value="${p.id}">${p.nombre}</option>`;
                });
                $('#planes_pago_select').html(opts);
            });
        }

        // Evento para el botón "Registrar nueva persona"
        $('#btn-nueva-persona-inscripcion').on('click', function() {
            // Solo mostrar formulario cuando se haga clic en el botón
            $('#paso-carnet-inscripcion').hide();
            $('#formNuevaPersonaInscripcion').show();

            // Prellenar el carnet en el formulario de nueva persona
            const carnet = $('#carnet_inscripcion').val().trim();
            if (carnet) {
                $('#carnet_nuevo_inscripcion').val(carnet);
                // Disparar validación del carnet
                $('#carnet_nuevo_inscripcion').trigger('input');
            }
        });

        // Evento para volver desde el formulario de nueva persona
        $('#btn-volver-carnet2-incripcion').on('click', function() {
            $('#formNuevaPersonaInscripcion').hide();
            $('#paso-carnet-inscripcion').show();
            $('#carnet_inscripcion').val('');
            $('#mensaje-verificacion-inscripcion').html('');
            $('#btn-nueva-persona-inscripcion').prop('disabled', true);
        });

        // Evento para volver desde el formulario de confirmar estudiante
        $('#btn-volver-carnet-confirmar').on('click', function() {
            $('#formConfirmarEstudiante').hide();
            $('#paso-carnet-inscripcion').show();
            $('#carnet_inscripcion').val('');
            $('#mensaje-verificacion-inscripcion').html('');
            $('#btn-nueva-persona-inscripcion').prop('disabled', true);
        });

        // Evento para volver desde el formulario de inscripción
        $('#btn-volver-estudiante-incripcion').on('click', function() {
            $('#formInscripcion').hide();
            // Si venimos de una persona existente pero no estudiante, volver a ese paso
            if ($('#persona_id_confirmar').val()) {
                $('#formConfirmarEstudiante').show();
            } else {
                // Si venimos de una persona nueva, volver a ese paso
                $('#formNuevaPersonaInscripcion').show();
            }
        });

        // Evento para cambiar el estado de inscripción
        $('#estado_inscripcion').on('change', function() {
            const estado = $(this).val();
            if (estado === 'Inscrito') {
                $('#planes-pago-section').show();
                // Limpiar la vista previa de cuotas
                $('#cuotas-preview-container').empty();
                $('#confirmar-cuotas-btn').hide();
                $('#generar-cuotas-btn').show();
            } else {
                $('#planes-pago-section').hide();
                $('#cuotas-preview-container').empty();
                $('#confirmar-cuotas-btn').hide();
                $('#generar-cuotas-btn').hide();
            }
        });

        // Evento para mostrar el botón "Generar Cuotas" al seleccionar un plan de pago
        $('#planes_pago_select').on('change', function() {
            const estado = $('#estado_inscripcion').val();
            const planId = $(this).val();
            if (estado === 'Inscrito' && planId) {
                $('#cuotas-preview-section').show();
                $('#cuotas-preview-container').empty();
                $('#confirmar-cuotas-btn').hide();
                $('#generar-cuotas-btn').show();
            } else if (!planId) {
                $('#cuotas-preview-section').hide();
                $('#generar-cuotas-btn').hide();
                $('#confirmar-cuotas-btn').hide();
            }
        });

        // Evento para generar la vista previa de cuotas
        $('#generar-cuotas-btn').on('click', function() {
            const estado = $('#estado_inscripcion').val();
            const planId = $('#planes_pago_select').val();
            const ofertaId = $('#oferta_id_inscripcion').val();

            if (estado !== 'Inscrito' || !planId) {
                alert('Por favor, seleccione un plan de pago válido.');
                return;
            }

            // Mostrar spinner
            $('#cuotas-preview-container').html(
                '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>'
            );

            $.ajax({
                url: "{{ route('admin.inscripciones.generar-cuotas-preview') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    oferta_id: ofertaId,
                    planes_pago_id: planId
                },
                success: function(res) {
                    if (res.success) {
                        renderizarCuotasPreview(res.cuotas_preview);
                        $('#confirmar-cuotas-btn').show();
                        $('#generar-cuotas-btn').hide();
                    } else {
                        $('#cuotas-preview-container').html(
                            `<div class="alert alert-danger">${res.msg}</div>`);
                    }
                },
                error: function(xhr) {
                    $('#cuotas-preview-container').html(
                        `<div class="alert alert-danger">Error al generar la vista previa de cuotas.</div>`
                    );
                }
            });
        });

        // Función para renderizar la vista previa de las cuotas (versión corregida)
        // Función para renderizar la vista previa de las cuotas (versión final)
        function renderizarCuotasPreview(cuotas) {
            let html = `
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>N° Cuota</th>
                        <th>Monto por Cuota</th>
                        <th>Fecha de Pago</th>
                    </tr>
                </thead>
                <tbody>
    `;

            cuotas.forEach(cuota => {
                html += `
            <tr>
                <td>${cuota.concepto_nombre}</td>
                <td>${cuota.n_cuota}</td>
                <td>
                    <input type="number" class="form-control" value="${cuota.pago_total_bs}" readonly>
                </td>
                <td>
                    <input type="date" class="form-control fecha-pago-input" 
                           value="${cuota.fecha_pago}" 
                           data-concepto-id="${cuota.concepto_id}" 
                           data-n-cuota="${cuota.n_cuota}">
                </td>
            </tr>
        `;
            });

            html += `
                </tbody>
            </table>
        </div>
    `;

            $('#cuotas-preview-container').html(html);
        }

        // Evento para generar fechas automáticamente (por concepto)
        $(document).on('click', '.auto-fechas-btn', function() {
            const conceptoId = $(this).data('concepto-id');
            const cuotas = $(`.fecha-pago-input[data-concepto-id="${conceptoId}"]`);
            const primeraFecha = $(cuotas[0]).val();
            const fechaBase = new Date(primeraFecha);

            cuotas.each(function(i, input) {
                const fecha = new Date(fechaBase);
                fecha.setMonth(fecha.getMonth() + i);
                $(input).val(fecha.toISOString().split('T')[0]);
            });
        });

        // Evento para confirmar la creación de las cuotas
        // Evento para confirmar la creación de las cuotas
        $('#confirmar-cuotas-btn').on('click', function() {
            const estado = $('#estado_inscripcion').val();
            const planId = $('#planes_pago_select').val();
            const ofertaId = $('#oferta_id_inscripcion').val();
            const estudianteId = $('#estudiante_id_inscripcion').val();

            // Recoger las fechas de pago y los montos
            const cuotasData = [];
            $('#cuotas-preview-container tbody tr').each(function() {
                const conceptoId = $(this).find('.fecha-pago-input').data('concepto-id');
                const nCuota = $(this).find('.fecha-pago-input').data('n-cuota');
                const fechaPago = $(this).find('.fecha-pago-input').val();
                const montoPorCuota = parseFloat($(this).find('input[type="number"]').val());

                cuotasData.push({
                    concepto_id: conceptoId,
                    n_cuota: nCuota,
                    fecha_pago: fechaPago,
                    monto_bs: montoPorCuota
                });
            });

            $.ajax({
                url: "{{ route('admin.inscripciones.confirmar-cuotas') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    oferta_id: ofertaId,
                    estudiante_id: estudianteId,
                    planes_pago_id: planId,
                    cuotas_data: cuotasData
                },
                success: function(res) {
                    alert(res.msg);
                    if (res.success) {
                        $('#modalInscribirEstudiante').modal('hide');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.msg || 'Error al confirmar las cuotas.');
                }
            });
        });

        // Submit del formulario de confirmar estudiante
        $('#formConfirmarEstudiante').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('admin.estudiantes.registrar') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        // Ahora que es estudiante, mostrar el formulario de inscripción
                        $('#estudiante_id_inscripcion').val($('#persona_id_confirmar').val());
                        $('#formInscripcion').show();
                        $('#formConfirmarEstudiante').hide();
                        cargarPlanesPago($('#persona_id_confirmar').val(), $('#oferta_id_inscripcion')
                            .val());
                    } else {
                        alert(res.msg || 'Error al registrar como estudiante.');
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.msg || 'Error al registrar como estudiante.');
                }
            });
        });

        // Submit del formulario de nueva persona
        $('#formNuevaPersonaInscripcion').submit(function(e) {
            e.preventDefault();
            // Validaciones adicionales (opcional, puedes reutilizar las del original)
            if (!validarApellidosNuevoInscripcion()) return;
            if ($('#fecha_nac_nuevo_inscripcion').val() && !calcularEdadNuevoInscripcion()) return;

            $.ajax({
                url: "{{ route('admin.estudiantes.registrar-persona-estudiante') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        // Obtener el ID del estudiante recién creado
                        // Asumiendo que el controlador devuelve el ID del estudiante en res.student_id
                        // Si no, necesitarás modificar el controlador para devolverlo.
                        $('#estudiante_id_inscripcion').val(res
                            .student_id); // <-- Requiere modificación en el controlador
                        $('#formInscripcion').show();
                        $('#formNuevaPersonaInscripcion').hide();
                        cargarPlanesPago(res.student_id, $('#oferta_id_inscripcion').val());
                    } else {
                        alert(res.msg || 'Error al registrar la persona y estudiante.');
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON?.errors || {};
                    if (errors.carnet) $('#feedback_carnet_nuevo_inscripcion').addClass('text-danger')
                        .text(errors.carnet[0]);
                    if (errors.correo) $('#feedback_correo_nuevo_inscripcion').addClass('text-danger')
                        .text(errors.correo[0]);
                    if (errors.apellidos) $('#feedback_apellidos_nuevo_inscripcion').text(errors
                        .apellidos[0]);
                    checkFormNuevaPersonaInscripcion();
                }
            });
        });

        // Submit del formulario de inscripción (solo para Pre-Inscrito)
        $('#formInscripcion').submit(function(e) {
            e.preventDefault();
            const estado = $('#estado_inscripcion').val();
            if (estado === 'Inscrito') {
                // Si es Inscrito, no enviar aquí, sino usar el botón de generar cuotas
                alert('Para "Inscrito", por favor genere y confirme las cuotas primero.');
                return;
            }

            $.ajax({
                url: "{{ route('admin.inscripciones.registrar') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    alert(res.msg);
                    if (res.success) {
                        $('#modalInscribirEstudiante').modal('hide');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.msg || 'Error al registrar la inscripción.');
                }
            });
        });

        // === VALIDACIONES PARA EL FORMULARIO DE NUEVA PERSONA ===
        function validarApellidosNuevoInscripcion() {
            const p = $('#paterno_nuevo_inscripcion').val().trim();
            const m = $('#materno_nuevo_inscripcion').val().trim();
            if (!p && !m) {
                $('#feedback_apellidos_nuevo_inscripcion').text('Debe ingresar al menos un apellido.');
                return false;
            } else {
                $('#feedback_apellidos_nuevo_inscripcion').text('');
                return true;
            }
        }

        function calcularEdadNuevoInscripcion() {
            const fecha = $('#fecha_nac_nuevo_inscripcion').val();
            if (!fecha) {
                $('#edad_calculada_nuevo_inscripcion').text('');
                return true;
            }
            const hoy = new Date();
            const nac = new Date(fecha);
            let edad = hoy.getFullYear() - nac.getFullYear();
            const mes = hoy.getMonth() - nac.getMonth();
            if (mes < 0 || (mes === 0 && hoy.getDate() < nac.getDate())) edad--;
            if (edad < 18) {
                $('#edad_calculada_nuevo_inscripcion').addClass('text-danger').text('⚠️ Debe tener al menos 18 años.');
                return false;
            } else {
                $('#edad_calculada_nuevo_inscripcion').removeClass('text-danger').text(`Edad: ${edad} años`);
                return true;
            }
        }

        function checkFormNuevaPersonaInscripcion() {
            const carnetOk = $('#feedback_carnet_nuevo_inscripcion').hasClass('text-success');
            const correoOk = $('#feedback_correo_nuevo_inscripcion').hasClass('text-success');
            const nombres = $('#nombres_nuevo_inscripcion').val().trim();
            const celular = $('#celular_nuevo_inscripcion').val().trim();
            const ciudade = $('#ciudad_nuevo_inscripcion').val();
            const sexo = $('select[name="sexo"]').val();
            const ecivil = $('select[name="estado_civil"]').val();
            const apellidosOk = validarApellidosNuevoInscripcion();
            const edadOk = !$('#fecha_nac_nuevo_inscripcion').val() || calcularEdadNuevoInscripcion();
            const enabled = carnetOk && correoOk && nombres && celular && ciudade && sexo && ecivil &&
                apellidosOk && edadOk;
            $('#btn-guardar-nueva-persona-incripcion').prop('disabled', !enabled);
        }

        // === DINÁMICA DE ESTUDIOS (NUEVA PERSONA) ===
        $(document).on('change', '.grado-select-nuevo', function() {
            const row = $(this).closest('.estudio-item-nuevo');
            const gradoId = $(this).val();
            if (!gradoId) {
                row.find('.profesion-select-nuevo, .universidad-select-nuevo').prop('disabled', true)
                    .html('<option value="">Profesión</option>');
                row.find('.universidad-select-nuevo').html('<option value="">Universidad</option>');
                return;
            }
            let htmlProf = '<option value="">Profesión</option>';
            @foreach ($profesiones as $p)
                htmlProf += `<option value="{{ $p->id }}">{{ $p->nombre }}</option>`;
            @endforeach
            row.find('.profesion-select-nuevo').html(htmlProf).prop('disabled', false);
            let htmlUni = '<option value="">Universidad</option>';
            @foreach ($universidades as $u)
                htmlUni +=
                    `<option value="{{ $u->id }}">{{ $u->nombre }} ({{ $u->sigla }})</option>`;
            @endforeach
            row.find('.universidad-select-nuevo').html(htmlUni).prop('disabled', false);
        });

        $(document).on('click', '.add-estudio-nuevo', function() {
            const index = $('.estudio-item-nuevo').length;
            let html = `
            <div class="estudio-item-nuevo row mb-2">
                <div class="col-md-3">
                    <select class="form-select grado-select-nuevo" name="estudios[${index}][grado]">
                        <option value="">Grado</option>
                        @foreach ($grados as $g)
                            <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select profesion-select-nuevo" name="estudios[${index}][profesion]" disabled>
                        <option value="">Profesión</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select universidad-select-nuevo" name="estudios[${index}][universidad]" disabled>
                        <option value="">Universidad</option>
                        @foreach ($universidades as $u)
                            <option value="{{ $u->id }}">{{ $u->nombre }} ({{ $u->sigla }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-estudio-nuevo">−</button>
                </div>
            </div>`;
            $('#estudios-container-nuevo-incripcion').append(html);
        });

        $(document).on('click', '.remove-estudio-nuevo', function() {
            $(this).closest('.estudio-item-nuevo').remove();
        });

        // === EVENTOS PARA VALIDACIÓN EN TIEMPO REAL ===
        $('#carnet_nuevo_inscripcion').on('input', function() {
            const carnet = $(this).val().trim();
            if (!carnet) {
                $('#feedback_carnet_nuevo_inscripcion').removeClass('text-success text-danger').text('');
                return;
            }
            clearTimeout(debounceCarnet);
            debounceCarnet = setTimeout(() => {
                $.post("{{ route('admin.personas.verificar-carnet') }}", {
                    _token: "{{ csrf_token() }}",
                    carnet: carnet
                }, function(res) {
                    if (res.exists) {
                        $('#feedback_carnet_nuevo_inscripcion').removeClass('text-success')
                            .addClass('text-danger').text('❌ Carnet ya en uso.');
                    } else {
                        $('#feedback_carnet_nuevo_inscripcion').removeClass('text-danger').addClass(
                            'text-success').text('✅ Disponible.');
                    }
                    checkFormNuevaPersonaInscripcion();
                });
            }, 400);
        });

        $('#correo_nuevo_inscripcion').on('input', function() {
            const correo = $(this).val().trim();
            if (!correo) {
                $('#feedback_correo_nuevo_inscripcion').removeClass('text-success text-danger').text('');
                return;
            }
            clearTimeout(debounceCorreo);
            debounceCorreo = setTimeout(() => {
                $.post("{{ route('admin.personas.verificar-correo') }}", {
                    _token: "{{ csrf_token() }}",
                    correo: correo
                }, function(res) {
                    if (res.exists) {
                        $('#feedback_correo_nuevo_inscripcion').removeClass('text-success')
                            .addClass('text-danger').text('❌ Correo ya en uso.');
                    } else {
                        $('#feedback_correo_nuevo_inscripcion').removeClass('text-danger').addClass(
                            'text-success').text('✅ Disponible.');
                    }
                    checkFormNuevaPersonaInscripcion();
                });
            }, 400);
        });

        $('#nombres_nuevo_inscripcion').on('input', function() {
            checkFormNuevaPersonaInscripcion();
        });

        $('#paterno_nuevo_inscripcion, #materno_nuevo_inscripcion').on('input', function() {
            validarApellidosNuevoInscripcion();
            checkFormNuevaPersonaInscripcion();
        });

        $('#celular_nuevo_inscripcion').on('input', function() {
            checkFormNuevaPersonaInscripcion();
        });

        $('#fecha_nac_nuevo_inscripcion').on('input', function() {
            calcularEdadNuevoInscripcion();
            checkFormNuevaPersonaInscripcion();
        });

        $('#ciudad_nuevo_inscripcion').on('change', function() {
            checkFormNuevaPersonaInscripcion();
        });

        $('#departamento_nuevo_inscripcion').on('change', function() {
            const deptoId = $(this).val();
            llenarCiudadesPorDepartamento(deptoId, $('#ciudad_nuevo_inscripcion'));
            $('#ciudad_nuevo_inscripcion').val('');
            checkFormNuevaPersonaInscripcion();
        });

        // Función auxiliar para llenar ciudades
        function llenarCiudadesPorDepartamento(departamentoId, selectElement) {
            selectElement.empty();
            if (!departamentoId) {
                selectElement.append('<option value="">Primero seleccione un departamento</option>');
                selectElement.prop('disabled', true);
                return;
            }
            const ciudadesFiltradas = ciudadesConDepartamento.filter(c => c.departamento_id == departamentoId);
            if (ciudadesFiltradas.length === 0) {
                selectElement.append('<option value="">Sin ciudades</option>');
            } else {
                selectElement.append('<option value="">Seleccionar ciudad</option>');
                ciudadesFiltradas.forEach(c => {
                    selectElement.append(`<option value="${c.id}">${c.nombre}</option>`);
                });
            }
            selectElement.prop('disabled', false);
        }

        // Inicializar variables globales
        let debounceCarnet = null;
        let debounceCorreo = null;
        const ciudadesConDepartamento = @json($ciudades->map(fn($c) => ['id' => $c->id, 'nombre' => $c->nombre, 'departamento_id' => $c->departamento_id]));
        const grados = @json($grados->pluck('nombre', 'id'));
        const profesiones = @json($profesiones->pluck('nombre', 'id'));
        const universidades = @json($universidades->map(fn($u) => ['id' => $u->id, 'nombre' => $u->nombre, 'sigla' => $u->sigla]));

        // Inicializar validaciones
        $('#departamento_nuevo_inscripcion').trigger('change');
    </script>
    <script>
        // === VER PLANES DE PAGO ===
        $(document).on('click', '.verPlanesPagoBtn', function() {
            const ofertaId = $(this).data('oferta-id');
            const ofertaCodigo = $(this).data('oferta-codigo');

            // Actualizar título del modal
            $('#planes_oferta_codigo').text(ofertaCodigo);

            // Mostrar loading, ocultar otros
            $('#loadingPlanes').show();
            $('#planesPagoContainer').hide();
            $('#sinPlanes').hide();

            // Abrir modal
            $('#modalVerPlanesPago').modal('show');

            // Obtener planes de pago via AJAX
            $.ajax({
                url: `/admin/ofertas/${ofertaId}/planes-pago`,
                method: 'GET',
                success: function(res) {
                    $('#loadingPlanes').hide();

                    if (res.success && res.planes.length > 0) {
                        renderizarPlanesPago(res.planes);
                        $('#planesPagoContainer').show();
                    } else {
                        $('#sinPlanes').show();
                    }
                },
                error: function(xhr) {
                    $('#loadingPlanes').hide();
                    $('#sinPlanes').show();
                    $('#sinPlanes').html(`
                <i class="ri-error-warning-line fs-1 text-danger"></i>
                <h5 class="mt-3 text-danger">Error al cargar planes</h5>
                <p class="text-muted">No se pudieron cargar los planes de pago.</p>
            `);
                }
            });
        });

        // Función simplificada para renderizar planes de pago
        // Función simplificada para renderizar planes de pago (modal Ver)
        function renderizarPlanesPago(planes) {
            let html = '';

            if (planes.length === 0) {
                html = `
        <div class="text-center py-5">
            <i class="ri-inbox-line fs-1 text-muted"></i>
            <h5 class="mt-3 text-muted">No hay planes de pago registrados</h5>
            <p class="text-muted">Esta oferta académica no tiene planes de pago configurados.</p>
        </div>
        `;
            } else {
                planes.forEach((plan, index) => {
                    let totalPlan = 0;

                    html += `
            <div class="card mb-4 ${index === 0 ? 'border-primary' : 'border-secondary'}">
                <div class="card-header ${index === 0 ? 'bg-primary' : 'bg-secondary'} text-white">
                    <h6 class="mb-0 d-flex justify-content-between align-items-center">
                        <span>
                            <i class="ri-bank-card-line me-2"></i>
                            ${plan.nombre}
                        </span>
                        <span class="badge bg-light ${index === 0 ? 'text-primary' : 'text-secondary'}">
                            ${plan.conceptos.length} concepto(s)
                        </span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th class="text-center">N° Cuotas</th>
                                    <th class="text-end">Total del Concepto (Bs)</th>
                                </tr>
                            </thead>
                            <tbody>`;

                    plan.conceptos.forEach((concepto) => {
                        const totalConcepto = parseFloat(concepto.total_concepto.replace(',', '')) || 0;
                        totalPlan += totalConcepto;

                        html += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <i class="ri-price-tag-3-line ${index === 0 ? 'text-primary' : 'text-secondary'}"></i>
                            </div>
                            <span>${concepto.concepto_nombre}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge ${index === 0 ? 'bg-primary' : 'bg-secondary'}">
                            ${concepto.n_cuotas}
                        </span>
                    </td>
                    <td class="text-end fw-bold ${index === 0 ? 'text-primary' : 'text-secondary'}">
                        ${totalConcepto.toFixed(2)}
                    </td>
                </tr>
                `;
                    });

                    html += `
                            </tbody>
                            <tfoot>
                                <tr class="border-top">
                                    <td colspan="2" class="text-end fw-bold">Total Inversión:</td>
                                    <td class="text-end fw-bold ${index === 0 ? 'text-success' : 'text-dark'}">
                                        ${totalPlan.toFixed(2)} Bs
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            `;
                });
            }

            $('#planesPagoContainer').html(html);

            // Refrescar feather icons
            if (typeof window.feather !== 'undefined') {
                window.feather.replace();
            }
        }

        // Refrescar feather icons cuando se abra el modal
        $('#modalVerPlanesPago').on('shown.bs.modal', function() {
            if (typeof window.feather !== 'undefined') {
                window.feather.replace();
            }
        });
    </script>
    <script>
        // === EDICIÓN DE PLANES DE PAGO ===
        $(document).on('click', '.editarPlanesPagoBtn', function() {
            const ofertaId = $(this).data('oferta-id');
            const ofertaCodigo = $(this).data('oferta-codigo');

            // Actualizar título del modal
            $('#editar_planes_oferta_codigo').text(ofertaCodigo);

            // Mostrar loading, ocultar otros
            $('#loadingEditarPlanes').show();
            $('#editarPlanesContainer').hide();
            $('#sinPlanesEditar').hide();
            $('#guardarCambiosPlanesBtn').hide();

            // Guardar el ID de la oferta en el botón de guardar
            $('#guardarCambiosPlanesBtn').data('oferta-id', ofertaId);

            // Abrir modal
            $('#modalEditarPlanesPago').modal('show');

            // Obtener datos de la oferta para edición
            $.ajax({
                url: `/admin/ofertas/${ofertaId}/datos`,
                method: 'GET',
                success: function(oferta) {
                    $('#loadingEditarPlanes').hide();

                    if (oferta.plan_concepto && oferta.plan_concepto.length > 0) {
                        renderizarPlanesParaEdicion(oferta.plan_concepto);
                        $('#editarPlanesContainer').show();
                        $('#guardarCambiosPlanesBtn').show();
                    } else {
                        $('#sinPlanesEditar').show();
                        $('#sinPlanesEditar .addPlanPagoBtnFromEdit').data('oferta-id', ofertaId);
                    }
                },
                error: function(xhr) {
                    $('#loadingEditarPlanes').hide();
                    $('#sinPlanesEditar').show();
                    $('#sinPlanesEditar').html(`
                <i class="ri-error-warning-line fs-1 text-danger"></i>
                <h5 class="mt-3 text-danger">Error al cargar datos</h5>
                <p class="text-muted">No se pudieron cargar los planes de pago para edición.</p>
            `);
                }
            });
        });

        // Función para renderizar planes de pago para edición (VERSIÓN CORREGIDA)
        function renderizarPlanesParaEdicion(planConcepto) {
            // Agrupar por plan de pago
            const planesAgrupados = {};

            planConcepto.forEach(pc => {
                const planId = pc.planes_pago_id;
                const planNombre = pc.plan_pago?.nombre || `Plan ${planId}`;

                if (!planesAgrupados[planId]) {
                    planesAgrupados[planId] = {
                        nombre: planNombre,
                        conceptos: []
                    };
                }

                // Guardar el ID del plan_concepto para edición individual
                planesAgrupados[planId].conceptos.push({
                    id: pc.id, // ID del plan_concepto (relación entre oferta y concepto)
                    concepto_id: pc.concepto_id,
                    concepto_nombre: pc.concepto?.nombre || 'Sin concepto',
                    n_cuotas: pc.n_cuotas,
                    pago_bs: pc.pago_bs
                });
            });

            let html = '';
            Object.entries(planesAgrupados).forEach(([planId, planData], planIndex) => {
                html += `
        <div class="card mb-4 plan-editar-card" data-plan-id="${planId}">
            <div class="card-header ${planIndex === 0 ? 'bg-primary' : 'bg-secondary'} text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center w-100">
                    <i class="ri-bank-card-line me-2"></i>
                    <strong class="plan-nombre-display">${planData.nombre}</strong>
                    <input type="hidden" class="plan-nombre-hidden" value="${planData.nombre}">
                    <span class="badge bg-light ${planIndex === 0 ? 'text-primary' : 'text-secondary'} ms-2">
                        ${planData.conceptos.length} concepto(s)
                    </span>
                </div>
                <button type="button" class="btn btn-sm btn-success agregarConceptoPlanBtn" data-plan-id="${planId}">
                    <i class="ri-add-line"></i> Concepto
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="40%">Concepto</th>
                                <th width="20%">N° Cuotas</th>
                                <th width="25%">Monto por Cuota (Bs)</th>
                                <th width="15%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="conceptos_plan_${planId}">`;

                planData.conceptos.forEach((concepto) => {
                    html += `
                            <tr class="concepto-item-editar" data-concepto-id="${concepto.concepto_id}" data-plan-concepto-id="${concepto.id || ''}">
                                <td>
                                    <select class="form-control form-control-sm concepto-select-editar">
                                        <option value="">Seleccionar concepto</option>
                                        ${CONCEPTOS.map(c => 
                                            `<option value="${c.id}" ${c.id == concepto.concepto_id ? 'selected' : ''}>${c.nombre}</option>`
                                        ).join('')}
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm n-cuotas-editar" 
                                           value="${concepto.n_cuotas}" min="1">
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" class="form-control monto-cuota-editar" 
                                               value="${concepto.pago_bs}" step="0.01" min="0">
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-danger eliminarConceptoBtn">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>`;
                });

                html += `
                        </tbody>
                    </table>
                </div>
            </div>
        </div>`;
            });

            $('#editarPlanesContainer').html(html);
        }

        // Evento para agregar un nuevo concepto a un plan (VERSIÓN CORREGIDA)
        $(document).on('click', '.agregarConceptoPlanBtn', function() {
            const planId = $(this).data('plan-id');
            const tbody = $(`#conceptos_plan_${planId}`);
            const newIndex = tbody.find('tr').length;

            // Obtener el nombre del plan para mostrar
            const planNombre = $(this).closest('.plan-editar-card').find('.plan-nombre-display').text();

            const html = `
    <tr class="concepto-item-editar" data-concepto-id="" data-plan-concepto-id="new">
        <td>
            <select class="form-control form-control-sm concepto-select-editar">
                <option value="">Seleccionar concepto</option>
                ${CONCEPTOS.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('')}
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm n-cuotas-editar" value="1" min="1">
        </td>
        <td>
            <div class="input-group input-group-sm">
                <span class="input-group-text">Bs</span>
                <input type="number" class="form-control monto-cuota-editar" value="0" step="0.01" min="0">
            </div>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger eliminarConceptoBtn">
                <i class="ri-delete-bin-line"></i>
            </button>
        </td>
    </tr>`;

            tbody.append(html);
        });

        // Evento para eliminar un concepto
        $(document).on('click', '.eliminarConceptoBtn', function() {
            $(this).closest('tr').remove();
        });

        // Evento para guardar los cambios en los planes de pago (VERSIÓN CORREGIDA)
        $('#guardarCambiosPlanesBtn').on('click', function() {
            const ofertaId = $(this).data('oferta-id');

            // Validar que la oferta esté en fase 2
            if (!confirm('¿Está seguro de guardar los cambios en los planes de pago?')) {
                return;
            }

            // Recopilar datos de los planes
            const planesData = [];

            $('.plan-editar-card').each(function() {
                const planId = $(this).data('plan-id');
                const planNombre = $(this).find('.plan-nombre-hidden').val();
                const conceptos = [];

                $(this).find('.concepto-item-editar').each(function() {
                    const conceptoId = $(this).find('.concepto-select-editar').val();
                    const nCuotas = $(this).find('.n-cuotas-editar').val();
                    const pagoBs = $(this).find('.monto-cuota-editar').val();
                    const planConceptoId = $(this).data(
                        'plan-concepto-id'); // ID del plan_concepto o 'new'

                    if (conceptoId && nCuotas && pagoBs) {
                        conceptos.push({
                            plan_concepto_id: planConceptoId, // Enviar ID si existe
                            concepto_id: conceptoId,
                            n_cuotas: nCuotas,
                            pago_bs: pagoBs
                        });
                    }
                });

                if (conceptos.length > 0) {
                    planesData.push({
                        planes_pago_id: planId, // ID del plan de pago existente
                        plan_nombre: planNombre, // Nombre del plan (para referencia)
                        conceptos: conceptos
                    });
                }
            });

            if (planesData.length === 0) {
                mostrarToast('warning', 'Debe agregar al menos un plan de pago con conceptos.');
                return;
            }

            // Validar que no haya conceptos duplicados en el mismo plan
            let hasDuplicates = false;
            planesData.forEach(plan => {
                const conceptosIds = plan.conceptos.map(c => c.concepto_id);
                const uniqueIds = [...new Set(conceptosIds)];
                if (conceptosIds.length !== uniqueIds.length) {
                    hasDuplicates = true;
                }
            });

            if (hasDuplicates) {
                mostrarToast('error', 'No puede haber conceptos duplicados en el mismo plan de pago.');
                return;
            }

            // Mostrar indicador de carga
            const originalText = $(this).html();
            $(this).html('<i class="ri-loader-4-line spin"></i> Guardando...').prop('disabled', true);

            // Enviar datos al backend (ahora con plan_id en lugar de crear nuevo)
            $.ajax({
                url: `/admin/ofertas/${ofertaId}/actualizar-planes-pago`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    planes: planesData
                },
                success: function(res) {
                    $('#guardarCambiosPlanesBtn').html(originalText).prop('disabled', false);

                    if (res.success) {
                        mostrarToast('success', res.msg);
                        $('#modalEditarPlanesPago').modal('hide');

                        // Actualizar la tabla de ofertas
                        setTimeout(() => {
                            loadResults();
                        }, 1000);
                    } else {
                        mostrarToast('error', res.msg || 'Error al actualizar los planes de pago.');
                    }
                },
                error: function(xhr) {
                    $('#guardarCambiosPlanesBtn').html(originalText).prop('disabled', false);

                    if (xhr.status === 422) {
                        mostrarToast('error', xhr.responseJSON?.msg ||
                            'Validación fallida. Verifique los datos.');
                    } else {
                        mostrarToast('error', 'Error al actualizar los planes de pago.');
                    }
                }
            });
        });

        // Botón para agregar plan desde el modal de "sin planes"
        $(document).on('click', '.addPlanPagoBtnFromEdit', function() {
            const ofertaId = $(this).data('oferta-id');
            $('#modalEditarPlanesPago').modal('hide');

            setTimeout(() => {
                $(`.addPlanPagoBtn[data-oferta-id="${ofertaId}"]`).click();
            }, 300);
        });

        // Función para mostrar toast
        function mostrarToast(icon, message) {
            if (typeof Toast !== 'undefined') {
                Toast.fire({
                    icon: icon,
                    title: message
                });
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: icon,
                    title: message,
                    showConfirmButton: false,
                    timer: 3000
                });
            } else {
                alert(message);
            }
        }
    </script>
@endpush
