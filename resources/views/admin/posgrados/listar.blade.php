@extends('admin.dashboard')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            @if (Auth::guard('web')->user()->can('posgrados.registrar'))
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrar">
                    Registrar Posgrado
                </button>
            @endif
            <!-- Modal Registrar -->
            <div class="modal fade" id="registrar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Registrar Posgrado</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addForm" class="forms-sample">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Nombre del Posgrado *</label>
                                            <input type="text" id="nombre_registro" name="nombre" class="form-control">
                                            <div id="feedback_registro" class="mt-2" style="font-size: 0.875em;"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Área *</label>
                                            <select name="area_id" id="area_id_registro" class="form-control" required>
                                                <option value="">Seleccione un área</option>
                                                @foreach ($areas as $area)
                                                    <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Tipo *</label>
                                            <select name="tipo_id" id="tipo_id_registro" class="form-control" required>
                                                <option value="">Seleccione un tipo</option>
                                                @foreach ($tipos as $tipo)
                                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Convenio *</label>
                                            <select name="convenio_id" id="convenio_id_registro" class="form-control"
                                                required>
                                                <option value="">Seleccione un convenio</option>
                                                @foreach ($convenios as $convenio)
                                                    <option value="{{ $convenio->id }}">{{ $convenio->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Créditos *</label>
                                            <input type="number" name="creditaje" class="form-control" min="0"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Carga Horaria (horas) *</label>
                                            <input type="number" name="carga_horaria" class="form-control" min="0"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="form-label">Duración *</label>
                                            <input type="number" name="duracion_numero" class="form-control" min="1"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Unidad</label>
                                            <select name="duracion_unidad" class="form-control" required>
                                                <option value="Días">Días</option>
                                                <option value="Meses">Meses</option>
                                                <option value="Años">Años</option>
                                                <option value="Semanas">Semanas</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Activo</label><br>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="estado_registro"
                                                    checked>
                                                <label class="form-check-label" for="estado_registro">Sí</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Dirigido a *</label>
                                            <textarea name="dirigido" class="form-control" rows="2" required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Objetivo *</label>
                                            <textarea name="objetivo" class="form-control" rows="3" required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success addBtn" disabled>
                                            Registrar Posgrado
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-0 bg-light-subtle">
                    <h5 class="card-title mb-0">Filtros y Búsqueda</h5>
                </div>
                <div class="card-body border border-dashed border-secondary-subtle rounded-2">
                    <div class="row g-3">
                        <!-- Buscador -->
                        <div class="col-md-6 col-lg-4">
                            <label for="searchInput" class="form-label visually-hidden">Buscar</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ri-search-line"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control"
                                    placeholder="Buscar posgrado...">
                            </div>
                        </div>

                        <!-- Filtro Área -->
                        <div class="col-md-6 col-lg-2">
                            <label for="filtroArea" class="form-label visually-hidden">Área</label>
                            <select id="filtroArea" class="form-select">
                                <option value="">Todas las áreas</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro Tipo -->
                        <div class="col-md-6 col-lg-2">
                            <label for="filtroTipo" class="form-label visually-hidden">Tipo</label>
                            <select id="filtroTipo" class="form-select">
                                <option value="">Todos los tipos</option>
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro Convenio -->
                        <div class="col-md-6 col-lg-2">
                            <label for="filtroConvenio" class="form-label visually-hidden">Convenio</label>
                            <select id="filtroConvenio" class="form-select">
                                <option value="">Todos los convenios</option>
                                @foreach ($convenios as $convenio)
                                    <option value="{{ $convenio->id }}">{{ $convenio->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Botón de limpiar (opcional pero útil) -->
                        <div class="col-md-6 col-lg-2 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-secondary w-100" id="clearFilters">
                                <i class="ri-refresh-line me-1"></i>Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultados -->
            <div id="results-container">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>N°</th>
                                <th>Nombre</th>
                                <th>Convenio</th>
                                <th>Área</th>
                                <th>Tipo</th>
                                <th>Duración</th>
                                <th>Carga Horaria</th>
                                <th>Créditos</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        @include('admin.posgrados.partials.table-body')
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3" id="pagination-container">
                    {{ $posgrados->links('pagination::bootstrap-5') }}
                </div>
            </div>

            <!-- Modal Editar -->
            <div class="modal fade modificar" tabindex="-1" aria-labelledby="modificarLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modificarLabel">Editar Posgrado</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editForm" class="forms-sample">
                                @csrf
                                <input type="hidden" name="id" id="id_edicion">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Nombre del Posgrado *</label>
                                            <input type="text" id="nombre_edicion" name="nombre"
                                                class="form-control">
                                            <div id="feedback_edicion" class="mt-2" style="font-size: 0.875em;"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Área *</label>
                                            <select name="area_id" id="area_id_edicion" class="form-control" required>
                                                <option value="">Seleccione un área</option>
                                                @foreach ($areas as $area)
                                                    <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Tipo *</label>
                                            <select name="tipo_id" id="tipo_id_edicion" class="form-control" required>
                                                <option value="">Seleccione un tipo</option>
                                                @foreach ($tipos as $tipo)
                                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Convenio *</label>
                                            <select name="convenio_id" id="convenio_id_edicion" class="form-control"
                                                required>
                                                <option value="">Seleccione un convenio</option>
                                                @foreach ($convenios as $convenio)
                                                    <option value="{{ $convenio->id }}">{{ $convenio->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Créditos *</label>
                                            <input type="number" name="creditaje" id="creditaje_edicion"
                                                class="form-control" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Carga Horaria (horas) *</label>
                                            <input type="number" name="carga_horaria" id="carga_horaria_edicion"
                                                class="form-control" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="form-label">Duración *</label>
                                            <input type="number" name="duracion_numero" id="duracion_numero_edicion"
                                                class="form-control" min="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Unidad</label>
                                            <select name="duracion_unidad" id="duracion_unidad_edicion"
                                                class="form-control" required>
                                                <option value="Días">Días</option>
                                                <option value="Meses">Meses</option>
                                                <option value="Años">Años</option>
                                                <option value="Semanas">Semanas</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Activo</label><br>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="estado_edicion">
                                                <label class="form-check-label" for="estado_edicion">Sí</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Dirigido a *</label>
                                            <textarea name="dirigido" id="dirigido_edicion" class="form-control" rows="2" required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Objetivo *</label>
                                            <textarea name="objetivo" id="objetivo_edicion" class="form-control" rows="3" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-warning editBtnSubmit" disabled>
                                            Actualizar Posgrado
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Eliminar -->
            <div class="modal fade eliminar" tabindex="-1" aria-labelledby="eliminarLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="eliminarLabel">Confirmar Eliminación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            ¿Está seguro de eliminar el posgrado <strong id="nombre_eliminar"></strong>?
                            <input type="hidden" id="id_eliminar">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal: Registrar Oferta Académica -->
            <div class="modal fade" id="modalRegistrarOferta" tabindex="-1" aria-labelledby="modalRegistrarOfertaLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalRegistrarOfertaLabel">Registrar Oferta Académica</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <form id="ofertaForm" class="forms-sample">
                                @csrf
                                <input type="hidden" name="posgrado_id" id="oferta_posgrado_id">

                                <!-- Sede -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Sede *</label>
                                        <select id="sede_id" class="form-control" required>
                                            <option value="">Seleccione una sede</option>
                                            @foreach ($sedes as $sede)
                                                <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Sucursal (loaded dynamically) -->
                                    <div class="col-md-4">
                                        <label class="form-label">Sucursal *</label>
                                        <select id="sucursale_id" name="sucursale_id" class="form-control" required
                                            disabled>
                                            <option value="">Seleccione una sede primero</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Modalidad *</label>
                                        <select name="modalidade_id" class="form-control" required>
                                            <option value="">Seleccione</option>
                                            @foreach ($modalidades as $m)
                                                <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Modalidad -->
                                <div class="row mb-3">

                                    <div class="col-md-3">
                                        <label class="form-label">Código *</label>
                                        <input type="text" name="codigo" id="codigo" class="form-control"
                                            required>
                                        <div id="feedback_codigo" class="mt-1" style="font-size: 0.875em;"></div>
                                    </div>
                                    <!-- Programa (text input + auto-create) -->
                                    <div class="col-md-6">
                                        <label class="form-label">Programa *</label>
                                        <input type="text" id="programa_nombre" class="form-control"
                                            placeholder="Nombre del programa" value="" required>
                                        <input type="hidden" name="programa_id" id="programa_id">
                                        <div id="programa_feedback" class="mt-1" style="font-size: 0.875em;"></div>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Gestión *</label>
                                        <input type="text" name="gestion" id="gestion" class="form-control"
                                            required>
                                    </div>
                                </div>

                                <!-- Responsables -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Responsable Académico *</label>
                                        <select name="responsable_academico_cargo_id" class="form-control" required>
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
                                    <div class="col-md-4">
                                        <label class="form-label">Responsable Marketing *</label>
                                        <select name="responsable_marketing_cargo_id" class="form-control" required>
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
                                    <div class="col-md-4">
                                        <label class="form-label">Color de la oferta</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <input type="color" id="color_registro" name="color"
                                                class="form-control form-control-color shadow-none p-1" value="#ccc"
                                                title="Selecciona un color distintivo para esta oferta">
                                            <span id="preview_registro" class="rounded-circle border d-inline-block"
                                                style="width: 32px; height: 32px; background-color: #ccc;"></span>
                                        </div>
                                        <small class="form-text text-muted">Se usará en calendarios y tarjetas.</small>
                                    </div>
                                </div>

                                <!-- Other fields (you can add more as needed) -->
                                <div class="row mb-3">

                                    <div class="col-md-4">
                                        <label class="form-label">Fecha Inicio Inscripciones *</label>
                                        <input type="date" name="fecha_inicio_inscripciones"
                                            id="fecha_inicio_inscripciones" class="form-control" required>
                                        <div id="error_fecha_inicio_inscripciones" class="text-danger mt-1"
                                            style="font-size: 0.875em;">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Fecha Inicio Programa *</label>
                                        <input type="date" name="fecha_inicio_programa" id="fecha_inicio_programa"
                                            class="form-control" required>
                                        <div id="error_fecha_inicio_programa" class="text-danger mt-1"
                                            style="font-size: 0.875em;">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Fecha Fin Programa *</label>
                                        <input type="date" name="fecha_fin_programa" id="fecha_fin_programa"
                                            class="form-control" required>
                                        <div id="error_fecha_fin_programa" class="text-danger mt-1"
                                            style="font-size: 0.875em;">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">

                                    <div class="col-md-3">
                                        <label class="form-label">N° Sesiones por módulo</label>
                                        <input type="number" name="cantidad_sesiones" id="n_sesiones"
                                            class="form-control" min="1" value="1">
                                        <div id="error_cantidad_sesiones" class="text-danger mt-1"
                                            style="font-size: 0.875em;">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">N° Módulos</label>
                                        <input type="number" name="n_modulos" id="n_modulos" class="form-control"
                                            min="1" value="1">
                                        <div id="error_n_modulos" class="text-danger mt-1" style="font-size: 0.875em;">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Versión</label>
                                        <input type="text" name="version" id="version" class="form-control"
                                            value="1">
                                        <div id="feedback_version" class="mt-1" style="font-size: 0.875em;"></div>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Grupo</label>
                                        <input type="text" name="grupo" id="grupo" class="form-control"
                                            value="1">
                                        <div id="feedback_grupo" class="mt-1" style="font-size: 0.875em;"></div>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Nota mínima</label>
                                        <input type="number" name="nota_minima" id="nota_minima" class="form-control"
                                            value="61" min="1" max="100">
                                        <div id="error_nota_minima" class="text-danger mt-1" style="font-size: 0.875em;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Imágenes: Portada y Certificado -->
                                <div class="row mb-4">
                                    <!-- Portada -->
                                    <div class="col-md-6">
                                        <div class="card h-100">
                                            <div class="card-header bg-primary text-white">
                                                <i class="ri-image-line me-1"></i> Portada del Programa
                                            </div>
                                            <div class="card-body text-center">
                                                <div class="mb-3">
                                                    <img id="preview_portada" src="#"
                                                        alt="Vista previa de portada" class="img-fluid rounded shadow-sm"
                                                        style="max-height: 180px; object-fit: contain; display: none;">
                                                    <div id="placeholder_portada" class="text-muted py-4"
                                                        style="display: block;">
                                                        <i class="ri-upload-cloud-2-line fs-1"></i><br>
                                                        <small>Seleccione una imagen para previsualizar</small>
                                                    </div>
                                                </div>
                                                <input type="file" name="portada" id="portada_input"
                                                    class="form-control" accept="image/png,image/jpeg,image/jpg">
                                                <div class="form-text">Formatos permitidos: PNG, JPG, JPEG (máx. 2 MB)
                                                </div>
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
                                                    <img id="preview_certificado" src="#"
                                                        alt="Vista previa de certificado"
                                                        class="img-fluid rounded shadow-sm"
                                                        style="max-height: 180px; object-fit: contain; display: none;">
                                                    <div id="placeholder_certificado" class="text-muted py-4"
                                                        style="display: block;">
                                                        <i class="ri-upload-cloud-2-line fs-1"></i><br>
                                                        <small>Seleccione una imagen para previsualizar</small>
                                                    </div>
                                                </div>
                                                <input type="file" name="certificado" id="certificado_input"
                                                    class="form-control" accept="image/png,image/jpeg,image/jpg">
                                                <div class="form-text">Formatos permitidos: PNG, JPG, JPEG (máx. 2 MB)
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- =================== MÓDULOS =================== -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="border-bottom pb-2"><i class="ri-book-open-line me-1"></i> Módulos del
                                            Programa</h6>
                                        <p class="text-muted small">Se generarán automáticamente según el número de módulos
                                            indicado.</p>
                                        <div id="modulos-container">
                                            <!-- Los módulos se generarán aquí con JS -->
                                        </div>
                                    </div>
                                </div>


                                <!-- Submit -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success" id="submitOfertaBtn" disabled>
                                        Registrar Oferta
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const PLANES_PAGOS = @json($planesPagos); // Debe ser array de objetos {id, nombre}
        const CONCEPTOS = @json($conceptos); // Debe ser array de objetos {id, nombre}
    </script>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            let debounceTimer;

            // === VALIDACIÓN ÚNICA Y CORRECTA ===
            function validateFormRegistro() {
                const nombre = $('#nombre_registro').val().trim();
                const areaId = $('#area_id_registro').val();
                const tipoId = $('#tipo_id_registro').val();
                const convenioId = $('#convenio_id_registro').val();
                const creditaje = $('input[name="creditaje"]').val();
                const carga = $('input[name="carga_horaria"]').val();
                const duracion = $('input[name="duracion_numero"]').val();
                const dirigido = $('textarea[name="dirigido"]').val().trim();
                const objetivo = $('textarea[name="objetivo"]').val().trim();

                const allFilled = nombre && areaId && tipoId && convenioId &&
                    creditaje !== '' && carga !== '' && duracion !== '' &&
                    dirigido && objetivo;

                const submitBtn = $('.addBtn');
                const feedback = $('#feedback_registro');

                if (!allFilled) {
                    submitBtn.prop('disabled', true);
                    feedback.removeClass('text-success text-danger').text('');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.posgrados.verificar') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre,
                            area_id: areaId,
                            tipo_id: tipoId,
                            convenio_id: convenioId
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.removeClass('text-success').addClass('text-danger')
                                    .text('⚠️ Ya existe un posgrado con esta combinación.');
                                submitBtn.prop('disabled', true);
                            } else {
                                feedback.removeClass('text-danger').addClass('text-success')
                                    .text('✅ Combinación disponible.');
                                submitBtn.prop('disabled', false);
                            }
                        },
                        error: function() {
                            feedback.removeClass('text-success').addClass('text-danger')
                                .text('❌ Error al verificar.');
                            submitBtn.prop('disabled', true);
                        }
                    });
                }, 400);
            }

            // Escuchar TODOS los campos
            $('#nombre_registro, #area_id_registro, #tipo_id_registro, #convenio_id_registro, input[name="creditaje"], input[name="carga_horaria"], input[name="duracion_numero"], textarea[name="dirigido"], textarea[name="objetivo"]')
                .on('input change', validateFormRegistro);

            $('.addBtn').prop('disabled', true);

            // === ENVÍO DE REGISTRO ===
            $('#addForm').submit(function(e) {
                e.preventDefault();
                const estadoVal = $('#estado_registro').is(':checked') ? 'activo' : 'inactivo';
                $('input[name="estado"]').remove();
                $(this).append(`<input type="hidden" name="estado" value="${estadoVal}">`);

                $('.addBtn').prop('disabled', true);
                $.ajax({
                    url: "{{ route('admin.posgrados.registrar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        alert(res.msg);
                        if (res.success) location.reload();
                        else $('.addBtn').prop('disabled', false);
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar.';
                        if (xhr.responseJSON?.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        alert(errorMsg);
                        $('.addBtn').prop('disabled', false);
                    }
                });
            });

            // Búsqueda AJAX
            $('#searchInput').on('input', function() {
                const term = $(this).val().trim();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadResults(term);
                }, 300);
            });

            function loadResults(search = '') {
                $.ajax({
                    url: '{{ route('admin.posgrados.listar') }}',
                    data: {
                        search
                    },
                    success: function(res) {
                        $('#results-container table tbody').replaceWith(res.html);
                        $('#pagination-container').html(res.pagination);
                    }
                });
            }

            $(document).on('click', '#pagination-container .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                const search = $('#searchInput').val().trim();
                $.get(url, {
                    search
                }, function(res) {
                    $('#results-container table tbody').replaceWith(res.html);
                    $('#pagination-container').html(res.pagination);
                });
            });

            function loadResults(search = '', area = '', tipo = '', convenio = '') {
                $.ajax({
                    url: '{{ route('admin.posgrados.listar') }}',
                    data: {
                        search: search,
                        area_id: area,
                        tipo_id: tipo,
                        convenio_id: convenio
                    },
                    success: function(res) {
                        $('#results-container table tbody').replaceWith(res.html);
                        $('#pagination-container').html(res.pagination);
                    }
                });
            }

            // Escuchar cambios en todos los filtros
            $('#searchInput, #filtroArea, #filtroTipo, #filtroConvenio').on('input change', function() {
                const search = $('#searchInput').val().trim();
                const area = $('#filtroArea').val();
                const tipo = $('#filtroTipo').val();
                const convenio = $('#filtroConvenio').val();

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadResults(search, area, tipo, convenio);
                }, 300);
            });

            // También actualiza el paginado para incluir los filtros
            $(document).on('click', '#pagination-container .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                const search = $('#searchInput').val().trim();
                const area = $('#filtroArea').val();
                const tipo = $('#filtroTipo').val();
                const convenio = $('#filtroConvenio').val();

                $.get(url, {
                    search: search,
                    area_id: area,
                    tipo_id: tipo,
                    convenio_id: convenio
                }, function(res) {
                    $('#results-container table tbody').replaceWith(res.html);
                    $('#pagination-container').html(res.pagination);
                });
            });

            // === CARGAR DATOS EN MODAL DE EDICIÓN ===
            $(document).on('click', '.editBtn', function() {
                const posgrado = $(this).data('bs-obj');
                $('#id_edicion').val(posgrado.id);
                $('#nombre_edicion').val(posgrado.nombre);
                $('#area_id_edicion').val(posgrado.area_id);
                $('#tipo_id_edicion').val(posgrado.tipo_id);
                $('#convenio_id_edicion').val(posgrado.convenio_id);
                $('#creditaje_edicion').val(posgrado.creditaje);
                $('#carga_horaria_edicion').val(posgrado.carga_horaria);
                $('#duracion_numero_edicion').val(posgrado.duracion_numero);
                $('#duracion_unidad_edicion').val(posgrado.duracion_unidad);
                $('#dirigido_edicion').val(posgrado.dirigido);
                $('#objetivo_edicion').val(posgrado.objetivo);
                $('#estado_edicion').prop('checked', posgrado.estado === 'activo');
                $('#feedback_edicion').removeClass('text-success text-danger').text('');
                $('.editBtnSubmit').prop('disabled', true);
                validateFormEdicion(); // Validar inmediatamente
            });

            // === VALIDACIÓN EN TIEMPO REAL PARA EDICIÓN ===
            let debounceTimerEdit;

            function validateFormEdicion() {
                const nombre = $('#nombre_edicion').val().trim();
                const areaId = $('#area_id_edicion').val();
                const tipoId = $('#tipo_id_edicion').val();
                const convenioId = $('#convenio_id_edicion').val();
                const creditaje = $('#creditaje_edicion').val();
                const carga = $('#carga_horaria_edicion').val();
                const duracion = $('#duracion_numero_edicion').val();
                const dirigido = $('#dirigido_edicion').val().trim();
                const objetivo = $('#objetivo_edicion').val().trim();
                const allFilled = nombre && areaId && tipoId && convenioId &&
                    creditaje !== '' && carga !== '' && duracion !== '' &&
                    dirigido && objetivo;

                const submitBtn = $('.editBtnSubmit');
                const feedback = $('#feedback_edicion');

                if (!allFilled) {
                    submitBtn.prop('disabled', true);
                    feedback.removeClass('text-success text-danger').text('');
                    return;
                }

                clearTimeout(debounceTimerEdit);
                debounceTimerEdit = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.posgrados.verificar') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: $('#id_edicion').val(),
                            nombre: nombre,
                            area_id: areaId,
                            tipo_id: tipoId,
                            convenio_id: convenioId
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.removeClass('text-success').addClass('text-danger')
                                    .text('⚠️ Ya existe un posgrado con esta combinación.');
                                submitBtn.prop('disabled', true);
                            } else {
                                feedback.removeClass('text-danger').addClass('text-success')
                                    .text('✅ Combinación disponible.');
                                submitBtn.prop('disabled', false);
                            }
                        },
                        error: function() {
                            feedback.removeClass('text-success').addClass('text-danger')
                                .text('❌ Error al verificar.');
                            submitBtn.prop('disabled', true);
                        }
                    });
                }, 400);
            }

            // Escuchar cambios en el formulario de edición
            $('#nombre_edicion, #area_id_edicion, #tipo_id_edicion, #convenio_id_edicion, #creditaje_edicion, #carga_horaria_edicion, #duracion_numero_edicion, #dirigido_edicion, #objetivo_edicion, #duracion_unidad_edicion')
                .on('input change', validateFormEdicion);

            // === ENVÍO DE EDICIÓN ===
            $('#editForm').submit(function(e) {
                e.preventDefault();
                const estadoVal = $('#estado_edicion').is(':checked') ? 'activo' : 'inactivo';
                $('input[name="estado"]').remove();
                $(this).append(`<input type="hidden" name="estado" value="${estadoVal}">`);

                $('.editBtnSubmit').prop('disabled', true);
                $.ajax({
                    url: "{{ route('admin.posgrados.modificar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        alert(res.msg);
                        if (res.success) location.reload();
                        else $('.editBtnSubmit').prop('disabled', false);
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al actualizar.';
                        if (xhr.responseJSON?.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        alert(errorMsg);
                        $('.editBtnSubmit').prop('disabled', false);
                    }
                });
            });

            // === CARGAR DATOS EN MODAL DE ELIMINACIÓN ===
            $(document).on('click', '.deleteBtn', function() {
                const posgrado = $(this).data('bs-obj');
                $('#id_eliminar').val(posgrado.id);
                $('#nombre_eliminar').text(posgrado.nombre);
            });

            // === CONFIRMAR ELIMINACIÓN ===
            $('#confirmDelete').click(function() {
                const id = $('#id_eliminar').val();
                $.ajax({
                    url: "{{ route('admin.posgrados.eliminar') }}",
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(res) {
                        alert(res.msg);
                        if (res.success) location.reload();
                    },
                    error: function() {
                        alert('Error al eliminar el posgrado.');
                    }
                });
            });

            // === Load sucursales when sede changes ===
            $('#sede_id').on('change', function() {
                const sedeId = $(this).val();
                const sucursalSelect = $('#sucursale_id');
                sucursalSelect.empty().prop('disabled', true);
                if (!sedeId) {
                    sucursalSelect.append('<option value="">Seleccione una sede primero</option>');
                    return;
                }
                $.get("{{ route('admin.sucursales.por-sede') }}", {
                    sede_id: sedeId
                }, function(data) {
                    sucursalSelect.prop('disabled', false);
                    sucursalSelect.append('<option value="">Seleccione una sucursal</option>');
                    data.forEach(s => {
                        sucursalSelect.append(
                            `<option value="${s.id}">${s.nombre}</option>`);
                    });
                });
            });

            $('#color_registro').on('input', function() {
                const color = $(this).val();
                $('#preview_registro').css('background-color', color);
            });

            // === Handle Programa: check existence or create ===
            let programaDebounce;
            $('#programa_nombre').on('input', function() {
                const nombre = $(this).val().trim();
                $('#programa_id').val('');
                const feedback = $('#programa_feedback').removeClass('text-success text-danger').text('');
                $('#submitOfertaBtn').prop('disabled', true);

                if (!nombre) return;

                clearTimeout(programaDebounce);
                programaDebounce = setTimeout(() => {
                    $.post("{{ route('admin.programas.buscar-o-crear') }}", {
                        _token: "{{ csrf_token() }}",
                        nombre: nombre
                    }, function(res) {
                        if (res.id) {
                            $('#programa_id').val(res.id);
                            feedback.addClass('text-success').text(
                                '✅ Programa seleccionado.');
                            $('#submitOfertaBtn').prop('disabled', false);
                        } else {
                            feedback.addClass('text-danger').text(
                                '❌ Error al procesar programa.');
                        }
                    }).fail(() => {
                        feedback.addClass('text-danger').text('❌ Error de conexión.');
                    });
                }, 500);
            });

            // === VALIDACIÓN COMPLETA DE OFERTA ACADÉMICA ===
            let debounceCodigo;

            function validateOfertaForm() {
                let isValid = true;
                const codigo = $('#codigo').val().trim();
                const version = $('#version').val().trim();
                const grupo = $('#grupo').val().trim();
                const nModulos = parseInt($('#n_modulos').val()) || 0;
                const notaMinima = parseFloat($('#nota_minima').val()) || 0;

                // Limpiar todos los feedbacks
                $('#feedback_codigo, #feedback_version, #feedback_grupo')
                    .removeClass('text-success text-danger').text('');

                // === Validar código (requerido + único) ===
                if (!codigo) {
                    $('#feedback_codigo').addClass('text-danger').text('❌ El código es obligatorio.');
                    isValid = false;
                } else {
                    // Verificar unicidad con debounce
                    clearTimeout(debounceCodigo);
                    debounceCodigo = setTimeout(() => {
                        $.post("{{ route('admin.ofertas-academicas.verificar-codigo') }}", {
                            _token: "{{ csrf_token() }}",
                            codigo: codigo
                        }, function(res) {
                            if (res.exists) {
                                $('#feedback_codigo').addClass('text-danger').text(
                                    '❌ Código ya en uso.');
                                $('#submitOfertaBtn').prop('disabled', true);
                            } else {
                                $('#feedback_codigo').addClass('text-success').text(
                                    '✅ Código disponible.');
                                // Revalidar todo si es necesario
                                if (validateOfertaFormSync()) {
                                    $('#submitOfertaBtn').prop('disabled', false);
                                }
                            }
                        });
                    }, 400);
                    // Mientras se verifica, asumimos que podría ser válido (pero bloqueamos hasta confirmar)
                    $('#submitOfertaBtn').prop('disabled', true);
                }

                // === Validar versión ≥ 1 ===
                const versionNum = parseInt(version);
                if (!version || isNaN(versionNum) || versionNum < 1) {
                    $('#feedback_version').addClass('text-danger').text('❌ Debe ser ≥ 1.');
                    isValid = false;
                } else {
                    $('#feedback_version').addClass('text-success').text('✅ Válido.');
                }

                // === Validar grupo ≥ 1 ===
                const grupoNum = parseInt(grupo);
                if (!grupo || isNaN(grupoNum) || grupoNum < 1) {
                    $('#feedback_grupo').addClass('text-danger').text('❌ Debe ser ≥ 1.');
                    isValid = false;
                } else {
                    $('#feedback_grupo').addClass('text-success').text('✅ Válido.');
                }

                // === Validar n_modulos ≥ 1 ===
                if (nModulos < 1) {
                    isValid = false;
                }

                // === Validar nota mínima (0 < x ≤ 100) ===
                if (notaMinima <= 0 || notaMinima > 100 || isNaN(notaMinima)) {
                    isValid = false;
                }

                return isValid;
            }

            // Versión sincrónica (sin AJAX) para usar después de verificar código
            function validateOfertaFormSync() {
                const version = $('#version').val().trim();
                const grupo = $('#grupo').val().trim();
                const nModulos = parseInt($('#n_modulos').val()) || 0;
                const notaMinima = parseFloat($('#nota_minima').val()) || 0;
                const codigo = $('#codigo').val().trim();

                if (!codigo) return false;

                const v = parseInt(version);
                const g = parseInt(grupo);

                return (
                    codigo &&
                    !isNaN(v) && v >= 1 &&
                    !isNaN(g) && g >= 1 &&
                    nModulos >= 1 &&
                    notaMinima > 0 && notaMinima <= 100
                );
            }

            // Escuchar cambios
            $('#codigo, #version, #grupo, #n_modulos, #nota_minima')
                .on('input change', validateOfertaForm);

            // === Submit con FormData (por si en el futuro usas imágenes) ===
            $('#ofertaForm').submit(function(e) {
                e.preventDefault();

                if (!$('#programa_id').val()) {
                    alert('Por favor espere a que se valide el programa.');
                    return;
                }

                // Validación final sincrónica
                if (!validateOfertaFormSync()) {
                    alert('Corrija los errores antes de enviar.');
                    return;
                }

                const formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.ofertas-academicas.registrar') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        alert(res.msg);
                        if (res.success) {
                            $('#modalRegistrarOferta').modal('hide');
                            $('#ofertaForm')[0].reset();
                            // Limpiar feedbacks
                            $('#feedback_codigo, #feedback_version, #feedback_grupo')
                                .removeClass('text-success text-danger').text('');
                        }
                    },
                    error: function(xhr) {
                        let err = 'Error al registrar.';
                        if (xhr.responseJSON?.errors) {
                            err = Object.values(xhr.responseJSON.errors).flat().join('\n');
                        }
                        alert(err);
                    }
                });
            });

            // === Set Posgrado ID and auto-fill when opening modal ===
            $(document).on('click', '[data-bs-toggle="modal"][data-bs-target="#modalRegistrarOferta"]', function() {
                const id = $(this).data('posgrado-id');
                const nombre = $(this).data('posgrado-nombre');

                // Asignar ID del posgrado
                $('#oferta_posgrado_id').val(id);

                // Auto-completar el campo "Programa" con el nombre del posgrado
                $('#programa_nombre').val(nombre);

                // Si quieres que no se pueda editar el programa:
                // $('#programa_nombre').prop('readonly', true);

                // Auto-completar la gestión con el año actual
                const currentYear = new Date().getFullYear();
                $('#gestion').val(currentYear);

                // Disparar el evento input para que busque/creé el programa automáticamente
                $('#programa_nombre').trigger('input');
            });

            // Previsualización de imágenes: Portada
            document.getElementById('portada_input').addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById('preview_portada');
                const placeholder = document.getElementById('placeholder_portada');

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

            // Previsualización de imágenes: Certificado
            document.getElementById('certificado_input').addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById('preview_certificado');
                const placeholder = document.getElementById('placeholder_certificado');

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

            // === GENERAR MÓDULOS AUTOMÁTICAMENTE ===
            function generarModulos() {
                const nModulos = parseInt($('#n_modulos').val()) || 0;
                const container = $('#modulos-container');
                container.empty();

                if (nModulos < 1) {
                    container.html('<p class="text-muted">Ingrese un número válido de módulos (≥1).</p>');
                    return;
                }

                let html = '';
                for (let i = 1; i <= nModulos; i++) {
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
            }

            // Escuchar cambios en n_modulos
            $('#n_modulos').on('input change', generarModulos);

            // Generar inicialmente si ya hay valor
            $(document).ready(function() {
                generarModulos();
            });

            // === AGREGAR PLANES DE PAGO DINÁMICAMENTE ===
            let planIndex = 0;

            function addPlanPago() {
                const id = 'plan_' + planIndex++;
                let planesOptions = '<option value="">Seleccione plan</option>';
                PLANES_PAGOS.forEach(p => {
                    planesOptions += `<option value="${p.id}">${p.nombre}</option>`;
                });

                let conceptosOptions = '<option value="">Seleccione concepto</option>';
                CONCEPTOS.forEach(c => {
                    conceptosOptions += `<option value="${c.id}">${c.nombre}</option>`;
                });

                const html = `
    <div class="row mb-3 p-2 border rounded mb-2 bg-light" id="${id}">
        <div class="col-md-3">
            <label class="form-label">Plan de pago</label>
            <select name="planes[${id}][planes_pago_id]" class="form-control" required>
                ${planesOptions}
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Concepto</label>
            <select name="planes[${id}][concepto_id]" class="form-control" required>
                ${conceptosOptions}
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">N° Cuotas</label>
            <input type="number" name="planes[${id}][n_cuotas]" class="form-control" min="1" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Monto (Bs)</label>
            <input type="number" step="0.01" name="planes[${id}][pago_bs]" class="form-control" min="0" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-sm btn-outline-danger remove-plan" data-id="${id}">
                <i class="ri-delete-bin-line"></i>
            </button>
        </div>
    </div>`;

                $('#planes-pago-container').append(html);
            }

            $('#add-plan-pago').on('click', addPlanPago);

            $(document).on('click', '.remove-plan', function() {
                const id = $(this).data('id');
                $('#' + id).remove();
            });



            // === GENERAR PLANES DE PAGO AUTOMÁTICAMENTE ===
            function generarPlanesPago() {
                const container = $('#planes-pago-container');
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
                <button type="button" class="btn btn-sm btn-outline-primary add-concepto" data-plan-id="${plan.id}">
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

            // Ejecutar al cargar la página
            $(document).ready(function() {
                generarPlanesPago();
            });

            $(document).on('click', '.add-concepto', function() {
                const planId = $(this).data('plan-id');
                const container = $(`#conceptos_${planId}`);
                const conceptoIndex = container.children('.concepto-item')
                    .length; // Este es el índice del concepto dentro del plan

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
                <button type="button" class="btn btn-sm btn-outline-danger remove-concepto">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>`;

                container.append(html);
            });

        });
    </script>
    <script>
        document.getElementById('clearFilters').addEventListener('click', function() {
            // Redirige a la misma ruta pero sin parámetros de búsqueda/filtro
            window.location.href = window.location.pathname;
        });
    </script>
@endpush
