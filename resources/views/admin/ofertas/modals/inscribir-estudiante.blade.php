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
                        <input type="text" id="carnet_inscripcion" class="form-control" placeholder="Ingrese carnet">
                        <div id="mensaje-verificacion-inscripcion" class="mt-2"></div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" id="btn-nueva-persona-inscripcion" disabled>
                            Registrar nueva persona
                        </button>
                    </div>
                </div>

                <!-- Paso 2: Confirmar Registro como Estudiante -->
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

                <!-- Paso 4: Formulario de Inscripción -->
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

                    <!-- Plan de Pago (obligatorio para ambos estados) -->
                    <div id="planes-pago-section">
                        <div class="mb-3">
                            <label class="form-label">Plan de Pago *</label>
                            <select name="planes_pago_id" class="form-select" id="planes_pago_select" required>
                                <option value="">Seleccione un plan</option>
                                <!-- Se llenará dinámicamente -->
                            </select>
                        </div>
                    </div>

                    <!-- Adelanto (solo para Pre-Inscrito, opcional) -->
                    <div id="adelanto-section" style="display:none;" class="mb-3">
                        <label class="form-label">Adelanto (Bs) <small class="text-muted">(Opcional)</small></label>
                        <input type="number" step="0.01" name="adelanto_bs" id="adelanto_bs"
                            class="form-control" placeholder="Ingrese el monto del adelanto (opcional)"
                            min="0">
                        <small class="text-muted">Si ingresa un adelanto, el estado será automáticamente
                            "Pre-Inscrito"</small>
                    </div>

                    <!-- Sección de vista previa de cuotas (solo para Inscrito) -->
                    <div id="cuotas-preview-section" style="display:none;">
                        <h6 class="mt-3">Vista Previa de Cuotas</h6>
                        <div id="cuotas-preview-container"></div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-outline-secondary" id="generar-cuotas-btn">
                                Generar Vista Previa
                            </button>
                            <button type="button" class="btn btn-success" id="confirmar-cuotas-btn"
                                style="display:none;">Confirmar Inscripción</button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-secondary"
                            id="btn-volver-estudiante-incripcion">Volver</button>
                        <button type="submit" class="btn btn-success" id="btn-registrar-inscripcion">
                            Registrar Inscripción
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
