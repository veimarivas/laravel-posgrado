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
                            <input type="date" name="fecha_inicio_inscripciones" id="edit_fecha_inicio_inscripciones"
                                class="form-control" required>
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
                    @include('admin.ofertas.partials.imagenes-oferta', ['tipo' => 'edit'])

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
