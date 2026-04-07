<!-- Modal Inscribir Estudiante -->
<div class="modal fade" id="modalInscribirEstudiante" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow" style="border-radius: var(--radius-lg); overflow: hidden;">

            <div class="modal-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #0f766e 0%, #0d5f59 100%); color: #fff;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 42px; height: 42px; background: rgba(255,255,255,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="ri-user-add-line" style="font-size: 1.3rem; color: #fff;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-semibold" style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; color: #fff;">Inscribir Estudiante</h5>
                        <div style="opacity: 0.85; font-size: 0.78rem; color: #fff;">
                            Registra un estudiante en la oferta académica
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4" style="background: var(--ofertas-surface);">

                <!-- Paso 1: Verificación de Carnet -->
                <div id="paso-carnet-inscripcion">
                    <div class="text-center mb-4">
                        <div style="width: 64px; height: 64px; margin: 0 auto 12px; background: var(--ofertas-primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-id-card-line" style="font-size: 1.8rem; color: var(--ofertas-primary);"></i>
                        </div>
                        <h6 style="font-family: 'Outfit', sans-serif; font-weight: 600; color: var(--ofertas-text);">Buscar por Carnet</h6>
                        <p class="text-muted mb-0" style="font-size: 0.82rem;">Ingrese el número de carnet para buscar a la persona</p>
                    </div>
                    <div class="search-wrapper" style="position: relative; max-width: 400px; margin: 0 auto;">
                        <i class="ri-search-line" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--ofertas-text-muted); font-size: 1.1rem;"></i>
                        <input type="text" id="carnet_inscripcion" class="form-control" placeholder="Ingrese número de carnet"
                            style="padding: 12px 14px 12px 42px; border-radius: var(--radius-md); border: 1px solid var(--ofertas-border); font-size: 0.9rem; transition: all 0.2s ease;">
                    </div>
                    <div id="mensaje-verificacion-inscripcion" class="mt-3"></div>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-nueva-persona" id="btn-nueva-persona-inscripcion" disabled
                            style="background: var(--ofertas-primary); color: #fff; border: none; padding: 10px 24px; border-radius: var(--radius-md); font-weight: 600; font-size: 0.88rem; transition: all 0.25s ease; opacity: 0.5; cursor: not-allowed;">
                            <i class="ri-user-add-line me-1"></i> Registrar nueva persona
                        </button>
                    </div>
                </div>

                <!-- Paso 2: Confirmar Registro como Estudiante -->
                <div id="formConfirmarEstudiante" style="display:none;">
                    <div class="text-center mb-4">
                        <div style="width: 64px; height: 64px; margin: 0 auto 12px; background: #dbeafe; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-question-line" style="font-size: 1.8rem; color: #2563eb;"></i>
                        </div>
                        <h6 style="font-family: 'Outfit', sans-serif; font-weight: 600; color: var(--ofertas-text);">Confirmar Estudiante</h6>
                    </div>
                    <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md); overflow: hidden;">
                        <div class="card-body text-center p-4">
                            <p class="mb-0" style="font-size: 0.95rem;">¿Desea registrar a <strong id="nombre_persona_confirmar" style="color: var(--ofertas-primary);"></strong> como estudiante?</p>
                        </div>
                    </div>
                    <input type="hidden" id="persona_id_confirmar">
                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-light" id="btn-volver-carnet-confirmar"
                            style="border-radius: var(--radius-sm); padding: 8px 20px; border: 1px solid var(--ofertas-border);">
                            <i class="ri-arrow-left-line me-1"></i>Volver
                        </button>
                        <button type="button" class="btn btn-confirmar-estudiante" id="btn-confirmar-estudiante"
                            style="background: var(--ofertas-success); color: #fff; border: none; padding: 8px 20px; border-radius: var(--radius-sm); font-weight: 600;">
                            <i class="ri-check-line me-1"></i>Confirmar Registro
                        </button>
                    </div>
                </div>

                <!-- Paso 3: Formulario de Nueva Persona -->
                <form id="formNuevaPersonaInscripcion" style="display:none;">
                    @csrf
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <button type="button" class="btn btn-light btn-sm" id="btn-volver-carnet2-incripcion"
                            style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border); padding: 4px 12px;">
                            <i class="ri-arrow-left-line"></i>
                        </button>
                        <h6 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600; color: var(--ofertas-text);">Nueva Persona</h6>
                    </div>

                    <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md); overflow: hidden;">
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ofertas-text-muted);">Carnet *</label>
                                    <input type="text" name="carnet" class="form-control form-control-sm" id="carnet_nuevo_inscripcion"
                                        style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                    <div id="feedback_carnet_nuevo_inscripcion" style="font-size: 0.75rem; margin-top: 2px;"></div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ofertas-text-muted);">Expedido</label>
                                    <select name="expedido" class="form-select form-select-sm" style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                        <option value="">Seleccionar</option>
                                        @foreach (['Lp', 'Or', 'Pt', 'Cb', 'Ch', 'Tj', 'Be', 'Sc', 'Pn'] as $e)
                                            <option value="{{ $e }}">{{ $e }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ofertas-text-muted);">Sexo *</label>
                                    <select name="sexo" class="form-select form-select-sm" required style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                        <option value="">Seleccionar</option>
                                        <option value="Hombre">Hombre</option>
                                        <option value="Mujer">Mujer</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ofertas-text-muted);">Estado Civil *</label>
                                    <select name="estado_civil" class="form-select form-select-sm" required style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                        <option value="">Seleccionar</option>
                                        <option value="Soltero(a)">Soltero(a)</option>
                                        <option value="Casado(a)">Casado(a)</option>
                                        <option value="Divorciado(a)">Divorciado(a)</option>
                                        <option value="Viudo(a)">Viudo(a)</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ofertas-text-muted);">Nombres *</label>
                                    <input type="text" name="nombres" class="form-control form-control-sm" id="nombres_nuevo_inscripcion"
                                        style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                    <div id="feedback_nombres_nuevo_inscripcion" class="text-danger" style="font-size: 0.75rem; margin-top: 2px;"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ofertas-text-muted);">Apellido Paterno</label>
                                    <input type="text" name="apellido_paterno" class="form-control form-control-sm" id="paterno_nuevo_inscripcion"
                                        style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ofertas-text-muted);">Apellido Materno</label>
                                    <input type="text" name="apellido_materno" class="form-control form-control-sm" id="materno_nuevo_inscripcion"
                                        style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                    <div id="feedback_apellidos_nuevo_inscripcion" class="text-danger" style="font-size: 0.75rem; margin-top: 2px;"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ofertas-text-muted);">Correo *</label>
                                    <input type="email" name="correo" class="form-control form-control-sm" id="correo_nuevo_inscripcion"
                                        style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                    <div id="feedback_correo_nuevo_inscripcion" style="font-size: 0.75rem; margin-top: 2px;"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ofertas-text-muted);">Celular *</label>
                                    <input type="text" name="celular" class="form-control form-control-sm" id="celular_nuevo_inscripcion"
                                        style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ofertas-text-muted);">Fecha Nacimiento</label>
                                    <input type="date" name="fecha_nacimiento" class="form-control form-control-sm" id="fecha_nac_nuevo_inscripcion"
                                        style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                    <div id="edad_calculada_nuevo_inscripcion" style="font-size: 0.75rem; margin-top: 2px;"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ofertas-text-muted);">Departamento *</label>
                                    <select name="departamento_id" id="departamento_nuevo_inscripcion" class="form-select form-select-sm" required
                                        style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                        <option value="">Seleccionar</option>
                                        @foreach ($departamentos as $d)
                                            <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ofertas-text-muted);">Ciudad *</label>
                                    <select name="ciudade_id" id="ciudad_nuevo_inscripcion" class="form-select form-select-sm" required disabled
                                        style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                        <option value="">Primero seleccione departamento</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ofertas-text-muted);">Dirección</label>
                                    <input type="text" name="direccion" class="form-control form-control-sm"
                                        style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                </div>
                            </div>

                            <!-- Estudios Académicos -->
                            <div class="mt-3 pt-3" style="border-top: 1px dashed var(--ofertas-border);">
                                <h6 style="font-size: 0.82rem; font-weight: 600; color: var(--ofertas-text); margin-bottom: 8px;">
                                    <i class="ri-book-line me-1"></i>Estudios Académicos
                                </h6>
                                <div id="estudios-container-nuevo-incripcion">
                                    <div class="estudio-item-nuevo row g-2 mb-2 align-items-end">
                                        <div class="col-md-3">
                                            <label class="form-label" style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; color: var(--ofertas-text-muted);">Grado</label>
                                            <select class="form-select form-select-sm grado-select-nuevo" name="estudios[0][grado]"
                                                style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                                <option value="">Grado</option>
                                                @foreach ($grados as $g)
                                                    <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; color: var(--ofertas-text-muted);">Profesión</label>
                                            <select class="form-select form-select-sm profesion-select-nuevo" name="estudios[0][profesion]" disabled
                                                style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                                <option value="">Profesión</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; color: var(--ofertas-text-muted);">Universidad</label>
                                            <select class="form-select form-select-sm universidad-select-nuevo" name="estudios[0][universidad]" disabled
                                                style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                                <option value="">Universidad</option>
                                                @foreach ($universidades as $u)
                                                    <option value="{{ $u->id }}">{{ $u->nombre }} ({{ $u->sigla }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-sm btn-success add-estudio-nuevo"
                                                style="border-radius: var(--radius-sm); padding: 4px 8px;">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn" id="btn-guardar-nueva-persona-incripcion" disabled
                            style="background: var(--ofertas-primary); color: #fff; border: none; padding: 8px 24px; border-radius: var(--radius-sm); font-weight: 600; opacity: 0.5; cursor: not-allowed;">
                            <i class="ri-user-add-line me-1"></i>Registrar como Estudiante
                        </button>
                    </div>
                </form>

                <!-- Paso 4: Formulario de Inscripción -->
                <form id="formInscripcion" style="display:none;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <button type="button" class="btn btn-light btn-sm" id="btn-volver-estudiante-incripcion"
                            style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border); padding: 4px 12px;">
                            <i class="ri-arrow-left-line"></i>
                        </button>
                        <h6 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600; color: var(--ofertas-text);">Datos de Inscripción</h6>
                    </div>

                    <input type="hidden" name="oferta_id" id="oferta_id_inscripcion">
                    <input type="hidden" name="estudiante_id" id="estudiante_id_inscripcion">

                    <div class="row g-3">
                        <!-- Estado -->
                        <div class="col-md-6">
                            <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ofertas-text-muted);">Estado *</label>
                            <select name="estado" class="form-select" id="estado_inscripcion" required
                                style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                <option value="">Seleccione...</option>
                                <option value="Pre-Inscrito">Pre-Inscrito</option>
                                <option value="Inscrito">Inscrito</option>
                            </select>
                        </div>

                        <!-- Plan de Pago -->
                        <div class="col-md-6">
                            <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ofertas-text-muted);">Plan de Pago *</label>
                            <select name="planes_pago_id" class="form-select" id="planes_pago_select" required
                                style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                <option value="">Seleccione un plan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Adelanto (solo Pre-Inscrito) -->
                    <div id="adelanto-section" style="display:none;" class="mt-3">
                        <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md); border-left: 4px solid var(--ofertas-accent) !important; overflow: hidden;">
                            <div class="card-body p-3">
                                <h6 style="font-size: 0.85rem; font-weight: 600; color: var(--ofertas-text);">
                                    <i class="ri-money-dollar-circle-line me-1" style="color: var(--ofertas-accent);"></i>Adelanto (Bs)
                                </h6>
                                <input type="number" step="0.01" name="adelanto_bs" id="adelanto_bs"
                                    class="form-control" placeholder="0.00" value="0" min="0"
                                    style="border-radius: var(--radius-sm); border: 1px solid var(--ofertas-border);">
                                <small class="text-muted" style="font-size: 0.75rem;">Monto opcional que se registra como adelanto</small>
                            </div>
                        </div>
                    </div>

                    <!-- Vista previa de cuotas (solo Inscrito) -->
                    <div id="cuotas-preview-section" style="display:none;" class="mt-3">
                        <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md); border-left: 4px solid var(--ofertas-primary) !important; overflow: hidden;">
                            <div class="card-header border-0 py-3 px-3" style="background: var(--ofertas-primary-light);">
                                <h6 class="mb-0" style="font-size: 0.88rem; font-weight: 600; color: var(--ofertas-primary);">
                                    <i class="ri-list-check me-1"></i>Vista Previa de Cuotas
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div id="cuotas-preview-container"></div>
                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-light" id="generar-cuotas-btn"
                                        style="border-radius: var(--radius-sm); padding: 8px 20px; border: 1px solid var(--ofertas-border);">
                                        <i class="ri-eye-line me-1"></i>Generar Vista Previa
                                    </button>
                                    <button type="button" class="btn btn-success" id="confirmar-cuotas-btn"
                                        style="display:none; border-radius: var(--radius-sm); padding: 8px 20px; border: none; font-weight: 600;">
                                        <i class="ri-check-line me-1"></i>Confirmar Inscripción
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-success" id="btn-registrar-inscripcion"
                            style="border-radius: var(--radius-sm); padding: 8px 24px; border: none; font-weight: 600;">
                            <i class="ri-check-line me-1"></i>Registrar Inscripción
                        </button>
                    </div>
                </form>

            </div>

            <div class="modal-footer border-0 py-3 px-4" style="background: white; border-top: 1px solid var(--ofertas-border) !important;">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                    style="border-radius: var(--radius-sm); padding: 8px 20px;">
                    <i class="ri-close-line me-1"></i>Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
