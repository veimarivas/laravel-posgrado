@extends('admin.dashboard')
@push('style')
<style>
/* ── vermodulos ── */
.modulo-item { transition: all .15s ease; border-radius: 8px; cursor: pointer; }
.modulo-item:hover { transform: translateX(3px); box-shadow: 0 2px 8px rgba(0,0,0,.12); }
.modulo-item.activo { outline: 3px solid rgba(255,255,255,.8); outline-offset: -2px; box-shadow: 0 0 0 3px rgba(0,0,0,.25); transform: translateX(3px); }
.modulo-action-btn { width:26px; height:26px; display:inline-flex; align-items:center; justify-content:center;
    border-radius:50%; background:rgba(255,255,255,.25); border:none; color:inherit; transition:background .15s; font-size:.85rem; }
.modulo-action-btn:hover { background:rgba(255,255,255,.45); }
.modal-header-colored { border-radius: 8px 8px 0 0; }
.step-indicator { display:flex; align-items:center; gap:6px; margin-bottom:16px; }
.step-dot { width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center;
    font-size:.75rem; font-weight:700; flex-shrink:0; }
.step-dot.active { background:#0d6efd; color:#fff; }
.step-dot.done   { background:#198754; color:#fff; }
.step-dot.idle   { background:#e9ecef; color:#6c757d; }
.step-line { flex:1; height:2px; background:#e9ecef; }
.step-line.done  { background:#198754; }
.estado-badge { display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:20px; font-size:.78rem; font-weight:600; }
/* ── Toast ── */
#toastContainer { position:fixed; top:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:8px; pointer-events:none; }
.app-toast { min-width:280px; max-width:380px; padding:12px 16px; border-radius:10px; box-shadow:0 4px 20px rgba(0,0,0,.15);
    display:flex; align-items:flex-start; gap:10px; pointer-events:all; font-size:.85rem; animation:toastIn .25s ease; }
.app-toast.hiding { animation:toastOut .3s ease forwards; }
.app-toast-icon { font-size:1.1rem; flex-shrink:0; margin-top:1px; }
.app-toast-body { flex:1; line-height:1.4; }
.app-toast-close { background:none; border:none; padding:0; opacity:.6; cursor:pointer; font-size:1rem; line-height:1; }
.app-toast-close:hover { opacity:1; }
.app-toast.success { background:#d1fae5; color:#065f46; border-left:4px solid #10b981; }
.app-toast.danger  { background:#fee2e2; color:#7f1d1d; border-left:4px solid #ef4444; }
.app-toast.warning { background:#fef9c3; color:#713f12; border-left:4px solid #f59e0b; }
.app-toast.info    { background:#e0f2fe; color:#0c4a6e; border-left:4px solid #0ea5e9; }
@keyframes toastIn  { from { opacity:0; transform:translateX(30px); } to { opacity:1; transform:translateX(0); } }
@keyframes toastOut { from { opacity:1; transform:translateX(0); } to { opacity:0; transform:translateX(30px); } }
</style>
@endpush
@section('admin')
    {{-- Header --}}
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <ol class="breadcrumb mb-1" style="font-size:.8rem;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.ofertas.listar') }}" class="text-decoration-none">Ofertas</a></li>
                <li class="breadcrumb-item active text-muted">Módulos</li>
            </ol>
            <h4 class="mb-1 fw-bold">Módulos y Horarios</h4>
            <div class="d-flex align-items-center gap-3 text-muted" style="font-size:.83rem;">
                <span><i class="ri-map-pin-line me-1"></i>{{ $oferta->sucursal->sede->nombre ?? '' }} — {{ $oferta->sucursal->nombre ?? '' }}</span>
                <span><i class="ri-book-2-line me-1"></i>{{ $oferta->programa->nombre ?? '' }}</span>
                <span class="badge rounded-pill px-2"
                    style="background:{{ $oferta->color }}18;color:{{ $oferta->color }};border:1px solid {{ $oferta->color }}40;font-size:.72rem;">
                    {{ $oferta->codigo }}
                </span>
            </div>
        </div>
        <a href="{{ route('admin.ofertas.dashboard', $oferta->id) }}" class="btn btn-outline-primary btn-sm align-self-start">
            <i class="ri-dashboard-line me-1"></i>Dashboard
        </a>
    </div>

    <div class="row g-3">
        {{-- Panel de módulos --}}
        <div class="col-xl-3 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header border-bottom bg-transparent py-2 px-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold" style="font-size:.85rem;">
                            <i class="ri-stack-line me-2 text-primary"></i>Módulos
                        </h6>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size:.72rem;">
                            {{ $oferta->modulos->count() }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-2" id="external-events">
                    @forelse ($oferta->modulos as $i => $modulo)
                        @php
                            $isLight = in_array(strtolower($modulo->color), ['#ffffff','#fff','#f8f9fa','#ffffffff']);
                            $textColor = $isLight ? '#212529' : '#ffffff';
                        @endphp
                        <div class="modulo-item mb-2 px-3 py-2 external-event"
                             data-modulo-id="{{ $modulo->id }}"
                             style="background:{{ $modulo->color }}; color:{{ $textColor }};">
                            {{-- Fila 1: número + nombre --}}
                            <div class="d-flex align-items-center gap-2 w-100">
                                <span class="d-flex align-items-center justify-content-center rounded-circle fw-bold flex-shrink-0"
                                      style="width:20px;height:20px;background:rgba(255,255,255,.25);font-size:.65rem;">{{ $i + 1 }}</span>
                                <span class="fw-semibold text-truncate" style="font-size:.81rem;min-width:0;" title="{{ $modulo->nombre }}">{{ $modulo->nombre }}</span>
                            </div>
                            {{-- Fila 2: docente + botones --}}
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <div style="font-size:.68rem;opacity:.8;min-width:0;" class="text-truncate me-1">
                                    @if ($modulo->docente)
                                        <i class="ri-user-line me-1"></i>{{ optional($modulo->docente->persona)->nombres }} {{ optional($modulo->docente->persona)->apellido_paterno }}
                                    @else
                                        <span style="opacity:.6;font-style:italic;">Sin docente</span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                    <button class="modulo-action-btn asignar-horarios"
                                        title="Asignar Horarios"
                                        data-modulo-id="{{ $modulo->id }}"
                                        data-sesiones="{{ $oferta->cantidad_sesiones }}"
                                        data-bs-toggle="modal" data-bs-target="#modalAsignarHorarios">
                                        <i class="ri-calendar-2-line"></i>
                                    </button>
                                    <button class="modulo-action-btn asignar-docente"
                                        title="Asignar Docente"
                                        data-modulo-id="{{ $modulo->id }}"
                                        data-docente-id="{{ $modulo->docente_id }}"
                                        data-bs-toggle="modal" data-bs-target="#modalAsignarDocente">
                                        <i class="ri-user-follow-line"></i>
                                    </button>
                                    <button class="modulo-action-btn cambiar-color-modulo"
                                        title="Cambiar Color"
                                        data-modulo-id="{{ $modulo->id }}"
                                        data-color="{{ $modulo->color }}"
                                        data-bs-toggle="modal" data-bs-target="#modalColorModulo">
                                        <i class="ri-palette-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="ri-inbox-line fs-2 d-block mb-1"></i>
                            <span style="font-size:.82rem;">Sin módulos registrados</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Calendario --}}
        <div class="col-xl-9 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-bottom bg-transparent py-2 px-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold" style="font-size:.85rem;">
                            <i class="ri-calendar-line me-2 text-primary"></i>Calendario de Sesiones
                            <span id="filtroLabel" class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill ms-2" style="font-size:.72rem;display:none;"></span>
                        </h6>
                        <button id="btnVerTodos" class="btn btn-outline-secondary btn-sm" style="display:none;font-size:.78rem;" title="Mostrar todos los módulos">
                            <i class="ri-layout-grid-line me-1"></i>Ver todos
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Asignar Horarios -->
    <div class="modal fade" id="modalAsignarHorarios" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0">
                <div class="modal-header modal-header-colored py-3" style="background:linear-gradient(135deg,#0d6efd,#4361ee);">
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25" style="width:36px;height:36px;">
                            <i class="ri-calendar-2-line text-white fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-white fw-bold">Asignar Horarios</h6>
                            <small class="text-white-50">Configure las sesiones del módulo</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="formAsignarHorarios">
                        @csrf
                        <input type="hidden" id="modulo_id" name="modulo_id">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.83rem;">
                                    <i class="ri-user-star-line me-1 text-primary"></i>Responsable
                                </label>
                                <select name="trabajadores_cargo_id" class="form-select form-select-sm">
                                    <option value="">Seleccionar responsable...</option>
                                    @foreach ($trabajadoresCargos as $tc)
                                        <option value="{{ $tc->id }}">
                                            {{ optional($tc->trabajador->persona)->nombres ?? 'Sin nombre' }}
                                            {{ optional($tc->trabajador->persona)->apellido_paterno ?? '' }}
                                            — {{ $tc->cargo->nombre ?? 'Sin cargo' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.83rem;">
                                    <i class="ri-mail-line me-1 text-primary"></i>Cuenta de Notificación
                                </label>
                                <select name="sucursales_cuenta_id" class="form-select form-select-sm">
                                    <option value="">Seleccionar cuenta...</option>
                                    @foreach ($sucursalesCuentas as $sc)
                                        <option value="{{ $sc->id }}">{{ $sc->cuenta->correo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="border-top pt-3">
                            <p class="fw-semibold mb-3" style="font-size:.83rem;">
                                <i class="ri-time-line me-1 text-primary"></i>Sesiones programadas
                            </p>
                            <div id="horarios-container"></div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-3 pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="ri-save-line me-1"></i>Guardar Horarios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Asignar Docente -->
    <div class="modal fade" id="modalAsignarDocente" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0">
                <div class="modal-header modal-header-colored py-3" style="background:linear-gradient(135deg,#198754,#20c997);">
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25" style="width:36px;height:36px;">
                            <i class="ri-user-follow-line text-white fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-white fw-bold">Asignar Docente</h6>
                            <small class="text-white-50">Busque o registre el docente del módulo</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="modulo_id_docente">
                    <input type="hidden" id="docente_seleccionado_id">
                    <input type="hidden" id="persona_id_no_docente">

                    <!-- Paso 1: Buscar por carnet -->
                    <div id="paso-buscar-docente">
                        <label class="form-label fw-semibold" style="font-size:.83rem;">
                            <i class="ri-id-card-line me-1 text-success"></i>Carnet del docente *
                        </label>
                        <div class="input-group input-group-sm mb-2">
                            <span class="input-group-text bg-light"><i class="ri-search-line text-muted"></i></span>
                            <input type="text" id="carnet_docente" class="form-control" placeholder="Ingrese número de carnet...">
                        </div>
                        <div id="mensaje-verificacion-docente" class="mt-2"></div>
                        <div class="text-end mt-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-registrar-nueva-persona-docente" disabled>
                                <i class="ri-user-add-line me-1"></i>Registrar nueva persona
                            </button>
                        </div>
                    </div>

                    <!-- Paso 2: Confirmar asignación (existe y es docente) -->
                    <form id="formAsignarDocenteExistente" style="display:none;">
                        <div class="alert alert-success border-0 d-flex align-items-center gap-2 py-2">
                            <i class="ri-checkbox-circle-line fs-5"></i>
                            <span>¿Asignar a <strong id="nombre_docente_existente"></strong> como docente de este módulo?</span>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-volver-buscar-docente">
                                <i class="ri-arrow-left-line me-1"></i>Volver
                            </button>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="ri-user-follow-line me-1"></i>Asignar Docente
                            </button>
                        </div>
                    </form>

                    <!-- Paso 3: Registrar como docente (existe pero no es docente) -->
                    <form id="formRegistrarComoDocente" style="display:none;">
                        <div class="alert alert-warning border-0 d-flex align-items-center gap-2 py-2">
                            <i class="ri-information-line fs-5"></i>
                            <span>¿Registrar a <strong id="nombre_persona_no_docente"></strong> como docente y asignarlo?</span>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-volver-buscar-docente2">
                                <i class="ri-arrow-left-line me-1"></i>Volver
                            </button>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="ri-user-add-line me-1"></i>Registrar como Docente
                            </button>
                        </div>
                    </form>

                    <!-- Paso 4: Registrar nueva persona + docente -->
                    <form id="formNuevaPersonaDocente" class="row g-3" style="display:none;">
                        @csrf
                        <div class="col-md-4">
                            <label class="form-label">Carnet *</label>
                            <input type="text" name="carnet" class="form-control" id="carnet_nuevo_docente">
                            <div id="feedback_carnet_docente" class="mt-1" style="font-size:0.875em;"></div>
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
                        <div class="col-md-4">
                            <label class="form-label">Nombres *</label>
                            <input type="text" name="nombres" class="form-control" id="nombres_nuevo_docente">
                            <div id="feedback_nombres_docente" class="text-danger mt-1" style="font-size:0.875em;"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellido Paterno</label>
                            <input type="text" name="apellido_paterno" class="form-control"
                                id="paterno_nuevo_docente">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellido Materno</label>
                            <input type="text" name="apellido_materno" class="form-control"
                                id="materno_nuevo_docente">
                            <div id="feedback_apellidos_docente" class="text-danger mt-1" style="font-size:0.875em;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo *</label>
                            <input type="email" name="correo" class="form-control" id="correo_nuevo_docente">
                            <div id="feedback_correo_docente" class="mt-1" style="font-size:0.875em;"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="form-control"
                                id="fecha_nac_nuevo_docente">
                            <div id="edad_calculada_nuevo_docente" class="mt-1" style="font-size:0.875em;"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Celular *</label>
                            <input type="text" name="celular" class="form-control" id="celular_nuevo_docente">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Departamento *</label>
                            <select name="departamento_id" class="form-select" id="depto_docente" required>
                                <option value="">Seleccionar</option>
                                @foreach ($departamentos as $d)
                                    <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ciudad *</label>
                            <select name="ciudade_id" class="form-select" id="ciudad_docente" required disabled>
                                <option value="">Primero seleccione depto</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Dirección</label>
                            <textarea name="direccion" class="form-control"></textarea>
                        </div>
                        <!-- Estudios Académicos -->
                        <div class="col-12 mt-3">
                            <h6 class="fw-semibold mb-2" style="font-size:.85rem;"><i class="ri-graduation-cap-line me-1 text-primary"></i>Estudios Académicos</h6>
                            <div id="estudios-container-docente">
                                <div class="estudio-item-docente row mb-2">
                                    <div class="col-md-3">
                                        <select class="form-select grado-select-docente" name="estudios[0][grado]">
                                            <option value="">Grado</option>
                                            @foreach ($grados as $g)
                                                <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select profesion-select-docente" name="estudios[0][profesion]"
                                            disabled>
                                            <option value="">Profesión</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select universidad-select-docente"
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
                                        <button type="button"
                                            class="btn btn-success btn-sm add-estudio-docente">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="d-flex justify-content-between pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-volver-buscar-docente3">
                                    <i class="ri-arrow-left-line me-1"></i>Volver
                                </button>
                                <button type="submit" class="btn btn-success btn-sm" id="btn-guardar-nueva-persona-docente" disabled>
                                    <i class="ri-user-add-line me-1"></i>Registrar como Docente
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detalle de Horario -->
    <div class="modal fade" id="modalDetalleHorario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header modal-header-colored py-3" style="background:linear-gradient(135deg,#6f42c1,#a855f7);">
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25" style="width:36px;height:36px;">
                            <i class="ri-calendar-event-line text-white fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-white fw-bold">Detalles de la Sesión</h6>
                            <small class="text-white-50">Información del horario registrado</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                <i class="ri-book-2-line text-primary fs-5"></i>
                                <div>
                                    <p class="mb-0 text-muted" style="font-size:.7rem;text-transform:uppercase;font-weight:600;">Módulo</p>
                                    <span class="fw-semibold" id="detalle-modulo" style="font-size:.9rem;"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center justify-content-center rounded bg-purple-subtle flex-shrink-0" style="width:34px;height:34px;background:rgba(111,66,193,.12);">
                                    <i class="ri-graduation-cap-line" style="color:#6f42c1;"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted" style="font-size:.7rem;text-transform:uppercase;font-weight:600;">Docente</p>
                                    <span style="font-size:.83rem;" id="detalle-docente">—</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center justify-content-center rounded bg-success-subtle text-success flex-shrink-0" style="width:34px;height:34px;">
                                    <i class="ri-user-line"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted" style="font-size:.7rem;text-transform:uppercase;font-weight:600;">Responsable</p>
                                    <span style="font-size:.83rem;" id="detalle-responsable"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center justify-content-center rounded bg-info-subtle text-info flex-shrink-0" style="width:34px;height:34px;">
                                    <i class="ri-briefcase-line"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted" style="font-size:.7rem;text-transform:uppercase;font-weight:600;">Cargo</p>
                                    <span style="font-size:.83rem;" id="detalle-cargo"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center justify-content-center rounded bg-warning-subtle text-warning flex-shrink-0" style="width:34px;height:34px;">
                                    <i class="ri-calendar-line"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted" style="font-size:.7rem;text-transform:uppercase;font-weight:600;">Fecha</p>
                                    <span style="font-size:.83rem;" id="detalle-fecha"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center justify-content-center rounded bg-primary-subtle text-primary flex-shrink-0" style="width:34px;height:34px;">
                                    <i class="ri-time-line"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted" style="font-size:.7rem;text-transform:uppercase;font-weight:600;">Hora</p>
                                    <span style="font-size:.83rem;" id="detalle-hora"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between p-2 rounded border">
                                <span class="text-muted" style="font-size:.8rem;">Estado actual:</span>
                                <span id="detalle-estado" class="fw-bold"></span>
                            </div>
                        </div>
                    </div>
                    {{-- Panel: editar estado --}}
                    <div id="editar-estado-form" class="mt-3 p-3 border rounded bg-light" style="display:none;">
                        <p class="fw-semibold mb-2" style="font-size:.83rem;"><i class="ri-edit-line me-1 text-warning"></i>Cambiar Estado</p>
                        <select id="nuevo-estado-select" class="form-select form-select-sm mb-2">
                            <option value="Confirmado">✅ Confirmado</option>
                            <option value="Desarrollado">✔️ Desarrollado</option>
                            <option value="Postergado">⏸️ Postergado</option>
                        </select>
                        <div class="d-flex gap-2">
                            <button id="guardar-estado-btn" class="btn btn-warning btn-sm"><i class="ri-save-line me-1"></i>Guardar</button>
                            <button id="cancelar-estado-btn" class="btn btn-outline-secondary btn-sm">Cancelar</button>
                        </div>
                    </div>

                    {{-- Panel: editar horario y responsable --}}
                    <div id="editar-horario-form" class="mt-3 p-3 border rounded bg-light" style="display:none;">
                        <p class="fw-semibold mb-2" style="font-size:.83rem;"><i class="ri-pencil-line me-1 text-primary"></i>Editar Horario y Responsable</p>
                        <div class="row g-2 mb-2">
                            <div class="col-12">
                                <label class="form-label fw-semibold mb-1" style="font-size:.78rem;">Fecha</label>
                                <input type="date" id="edit-horario-fecha" class="form-control form-control-sm">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold mb-1" style="font-size:.78rem;">Hora Inicio</label>
                                <input type="time" id="edit-horario-inicio" class="form-control form-control-sm">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold mb-1" style="font-size:.78rem;">Hora Fin</label>
                                <input type="time" id="edit-horario-fin" class="form-control form-control-sm">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold mb-1" style="font-size:.78rem;">Responsable</label>
                                <select id="edit-horario-responsable" class="form-select form-select-sm">
                                    <option value="">Sin responsable</option>
                                    @foreach ($trabajadoresCargos as $tc)
                                        <option value="{{ $tc->id }}">
                                            {{ optional($tc->trabajador->persona)->nombres ?? '' }}
                                            {{ optional($tc->trabajador->persona)->apellido_paterno ?? '' }}
                                            — {{ $tc->cargo->nombre ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold mb-1" style="font-size:.78rem;">Cuenta de Notificación</label>
                                <select id="edit-horario-cuenta" class="form-select form-select-sm">
                                    <option value="">Sin cuenta</option>
                                    @foreach ($sucursalesCuentas as $sc)
                                        <option value="{{ $sc->id }}">{{ $sc->cuenta->correo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button id="guardar-horario-btn" class="btn btn-primary btn-sm"><i class="ri-save-line me-1"></i>Guardar</button>
                            <button id="cancelar-horario-btn" class="btn btn-outline-secondary btn-sm">Cancelar</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-2 gap-1">
                    <button type="button" class="btn btn-outline-primary btn-sm" id="btn-editar-horario">
                        <i class="ri-pencil-line me-1"></i>Editar Horario
                    </button>
                    <button type="button" class="btn btn-outline-warning btn-sm" id="btn-editar-estado">
                        <i class="ri-edit-line me-1"></i>Estado
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm d-none" id="btn-reprogramar-sesion">
                        <i class="ri-calendar-2-line me-1"></i>Reprogramar
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cambiar Color de Módulo -->
    <div class="modal fade" id="modalColorModulo" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content border-0">
                <div class="modal-header modal-header-colored py-3" style="background:linear-gradient(135deg,#fd7e14,#ffc107);">
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25" style="width:36px;height:36px;">
                            <i class="ri-palette-line text-white fs-5"></i>
                        </div>
                        <h6 class="mb-0 text-white fw-bold">Color del Módulo</h6>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <p class="text-muted mb-3" style="font-size:.83rem;">Seleccione el color de identificación para este módulo en el calendario</p>
                    <input type="color" id="color-picker" class="form-control form-control-color mx-auto mb-3"
                        value="#cccccc" title="Elige un color" style="width:80px;height:50px;cursor:pointer;">
                    <div id="color-preview" class="rounded mx-auto d-flex align-items-center justify-content-center fw-semibold"
                         style="width:120px;height:40px;border:2px solid #dee2e6;font-size:.8rem;color:#fff;">
                        Vista previa
                    </div>
                    <input type="hidden" id="modulo-id-color">
                </div>
                <div class="modal-footer border-0 bg-light py-2 justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-sm" id="guardar-color-btn">
                        <i class="ri-save-line me-1"></i>Guardar Color
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer"></div>

    <!-- Modal Reprogramar Sesión -->
    <div class="modal fade" id="modalReprogramarSesion" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header modal-header-colored py-3" style="background:linear-gradient(135deg,#0dcaf0,#0d6efd);">
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25" style="width:36px;height:36px;">
                            <i class="ri-calendar-2-line text-white fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-white fw-bold">Reprogramar Sesión</h6>
                            <small class="text-white-50">Establezca la nueva fecha y horario</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-light border d-flex align-items-center gap-2 py-2 mb-3">
                        <i class="ri-calendar-event-line text-warning fs-5"></i>
                        <div>
                            <small class="text-muted d-block" style="font-size:.7rem;text-transform:uppercase;font-weight:600;">Sesión original</small>
                            <span class="fw-semibold" id="reprogramar-fecha-original" style="font-size:.85rem;"></span>
                        </div>
                    </div>
                    <input type="hidden" id="horario-original-id">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.83rem;">
                            <i class="ri-calendar-line me-1 text-primary"></i>Nueva Fecha *
                        </label>
                        <input type="date" id="reprogramar-fecha" class="form-control form-control-sm" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold" style="font-size:.83rem;">
                                <i class="ri-time-line me-1 text-success"></i>Hora Inicio *
                            </label>
                            <input type="time" id="reprogramar-hora-inicio" class="form-control form-control-sm" value="19:00" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold" style="font-size:.83rem;">
                                <i class="ri-time-line me-1 text-danger"></i>Hora Fin *
                            </label>
                            <input type="time" id="reprogramar-hora-fin" class="form-control form-control-sm" value="22:00" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-2 justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-sm" id="guardar-reprogramacion-btn">
                        <i class="ri-calendar-check-line me-1"></i>Reprogramar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('backend/assets/libs/fullcalendar/index.global.min.js') }}"></script>
    <script>
        // ── Toast ──────────────────────────────────────────────────────────
        function showToast(message, type = 'success') {
            const icons = { success:'ri-checkbox-circle-line', danger:'ri-error-warning-line', warning:'ri-alert-line', info:'ri-information-line' };
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `app-toast ${type}`;
            toast.innerHTML = `
                <i class="${icons[type] || icons.info} app-toast-icon"></i>
                <span class="app-toast-body">${message}</span>
                <button class="app-toast-close" onclick="this.closest('.app-toast').remove()">×</button>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('hiding');
                setTimeout(() => toast.remove(), 320);
            }, 4000);
        }
        // ───────────────────────────────────────────────────────────────────

        let calendar;
        let eventoActivo = null;
        let todosLosEventos = [];
        let moduloFiltroActivo = null;

        function filtrarCalendario(moduloId, nombreModulo) {
            moduloFiltroActivo = moduloId;
            calendar.removeAllEvents();
            todosLosEventos
                .filter(e => e.extendedProps.modulo_id == moduloId)
                .forEach(e => calendar.addEvent(e));
            document.getElementById('filtroLabel').textContent = nombreModulo;
            document.getElementById('filtroLabel').style.display = '';
            document.getElementById('btnVerTodos').style.display = '';
            document.querySelectorAll('.modulo-item').forEach(el => {
                el.classList.toggle('activo', el.dataset.moduloId == moduloId);
            });
        }

        function mostrarTodosLosEventos() {
            moduloFiltroActivo = null;
            calendar.removeAllEvents();
            todosLosEventos.forEach(e => calendar.addEvent(e));
            document.getElementById('filtroLabel').style.display = 'none';
            document.getElementById('btnVerTodos').style.display = 'none';
            document.querySelectorAll('.modulo-item').forEach(el => el.classList.remove('activo'));
        }

        document.addEventListener("DOMContentLoaded", function() {
            var calendarEl = document.getElementById("calendar");
            var eventos = [];
            @foreach ($oferta->modulos as $modulo)
                @foreach ($modulo->horarios as $horario)
                    @php
                        $fechaStr = \Carbon\Carbon::parse($horario->fecha)->format('Y-m-d');
                        $horaIni  = substr($horario->hora_inicio, 0, 5);
                        $horaFin  = substr($horario->hora_fin, 0, 5);
                        $start = $fechaStr . 'T' . $horaIni;
                        $end   = $fechaStr . 'T' . $horaFin;
                        $responsable = optional($horario->trabajador_cargo)->trabajador?->persona?->nombres . ' ' . optional($horario->trabajador_cargo)->trabajador?->persona?->apellido_paterno ?? 'Sin responsable';
                        $cargo = optional($horario->trabajador_cargo)->cargo?->nombre ?? '';
                        $estado = $horario->estado ?? 'Confirmado';
                        $estadoLabel = match ($estado) {
                            'Confirmado' => '[✅ Confirmado]',
                            'Desarrollado' => '[✔️ Desarrollado]',
                            'Postergado' => '[⏸️ Postergado]',
                            default => '',
                        };
                        $title = $modulo->nombre . ' - ' . $responsable . ' (' . $cargo . ') ' . $estadoLabel;
                    @endphp
                    eventos.push({
                        title: "{{ addslashes($title) }}",
                        start: "{{ $start }}",
                        end: "{{ $end }}",
                        className: 'text-with',
                        extendedProps: {
                            modulo_id: {{ $modulo->id }},
                            horario_id: {{ $horario->id }},
                            responsable: "{{ addslashes($responsable) }}",
                            cargo: "{{ addslashes($cargo) }}",
                            estado: "{{ $estado }}",
                            color_modulo: "{{ $modulo->color }}",
                            trabajadores_cargo_id: {{ $horario->trabajadores_cargo_id ?? 'null' }},
                            sucursales_cuenta_id: {{ $horario->sucursales_cuenta_id ?? 'null' }},
                            docente: "{{ addslashes(optional($modulo->docente?->persona)->nombres . ' ' . optional($modulo->docente?->persona)->apellido_paterno) }}"
                        }
                    });
                @endforeach
            @endforeach

            calendar = new FullCalendar.Calendar(calendarEl, {
                locale: "es",
                initialView: "dayGridMonth",
                headerToolbar: {
                    left: "prev,next today",
                    center: "title",
                    right: "dayGridMonth,timeGridWeek,timeGridDay",
                },
                buttonText: {
                    today: "Hoy",
                    month: "Mes",
                    week: "Semana",
                    day: "Día",
                },
                editable: false,
                droppable: false,
                events: eventos,
                eventDidMount: function(info) {
                    // re-aplicar color al módulo activo si se redibujan eventos
                    if (moduloFiltroActivo && info.event.extendedProps.modulo_id != moduloFiltroActivo) {
                        info.el.style.display = 'none';
                    }
                    info.el.style.cursor = 'pointer';
                    if (info.event.extendedProps.color_modulo) {
                        const colorFondo = info.event.extendedProps.color_modulo;
                        info.el.style.backgroundColor = colorFondo;
                        info.el.style.borderColor = colorFondo;
                    }
                    const estado = info.event.extendedProps.estado;
                    let colorTexto = '#000000';
                    if (estado === 'Confirmado') colorTexto = '#16A3DB';
                    else if (estado === 'Desarrollado') colorTexto = '#3804D9';
                    else if (estado === 'Postergado') colorTexto = '#D91404';
                    const titleEl = info.el.querySelector('.fc-event-title') || info.el.querySelector(
                        'span') || info.el;
                    if (titleEl) titleEl.style.color = colorTexto;
                },
                eventClick: function(info) {
                    const props = info.event.extendedProps;
                    const start = new Date(info.event.start);
                    const end = new Date(info.event.end || info.event.start);
                    const fechaStr = start.toLocaleDateString('es-ES', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                    const horaStr =
                        `${info.event.start.toTimeString().slice(0,5)} - ${info.event.end?.toTimeString().slice(0,5) || '??'}`;
                    document.getElementById('detalle-modulo').textContent = info.event.title.split(
                        ' - ')[0] || 'Sin módulo';
                    document.getElementById('detalle-docente').textContent    = props.docente    || '—';
                    document.getElementById('detalle-responsable').textContent = props.responsable || '—';
                    document.getElementById('detalle-cargo').textContent       = props.cargo      || '—';
                    document.getElementById('detalle-fecha').textContent = fechaStr;
                    document.getElementById('detalle-hora').textContent = horaStr;
                    const estadoEl = document.getElementById('detalle-estado');
                    estadoEl.textContent = props.estado || '—';
                    estadoEl.className = 'fw-bold';
                    estadoEl.classList.remove('text-success', 'text-secondary', 'text-warning');
                    if (props.estado === 'Confirmado') estadoEl.classList.add('text-success');
                    else if (props.estado === 'Desarrollado') estadoEl.classList.add('text-secondary');
                    else if (props.estado === 'Postergado') estadoEl.classList.add('text-warning');

                    const btnReprogramar = document.getElementById('btn-reprogramar-sesion');
                    if (props.estado === 'Postergado') {
                        btnReprogramar.classList.remove('d-none');
                    } else {
                        btnReprogramar.classList.add('d-none');
                    }
                    eventoActivo = info.event;
                    // Resetear paneles de edición
                    document.getElementById('editar-estado-form').style.display = 'none';
                    document.getElementById('editar-horario-form').style.display = 'none';
                    // Pre-cargar valores en el form de edición de horario
                    document.getElementById('edit-horario-fecha').value      = info.event.start ? info.event.start.toISOString().substring(0, 10) : '';
                    document.getElementById('edit-horario-inicio').value     = info.event.start ? info.event.start.toTimeString().slice(0,5) : '';
                    document.getElementById('edit-horario-fin').value        = info.event.end   ? info.event.end.toTimeString().slice(0,5)   : '';
                    document.getElementById('edit-horario-responsable').value = props.trabajadores_cargo_id || '';
                    document.getElementById('edit-horario-cuenta').value      = props.sucursales_cuenta_id  || '';
                    new bootstrap.Modal(document.getElementById('modalDetalleHorario')).show();
                },
            });
            todosLosEventos = [...eventos];
            calendar.render();

            // === FILTRO POR MÓDULO ===
            document.getElementById('btnVerTodos').addEventListener('click', mostrarTodosLosEventos);

            document.querySelectorAll('.modulo-item').forEach(function(item) {
                item.addEventListener('click', function(e) {
                    // Ignorar si el click fue en un botón de acción
                    if (e.target.closest('.modulo-action-btn')) return;
                    const moduloId = this.dataset.moduloId;
                    const nombreModulo = this.querySelector('.fw-semibold.text-truncate')?.textContent?.trim() || 'Módulo';
                    if (moduloFiltroActivo == moduloId) {
                        mostrarTodosLosEventos();
                    } else {
                        filtrarCalendario(moduloId, nombreModulo);
                    }
                });
            });

            // === LISTENERS DE MODAL DETALLE ===
            // ── Editar Horario y Responsable ──────────────────────────────
            document.getElementById('btn-editar-horario').addEventListener('click', function() {
                document.getElementById('editar-estado-form').style.display = 'none';
                const form = document.getElementById('editar-horario-form');
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            });
            document.getElementById('cancelar-horario-btn').addEventListener('click', function() {
                document.getElementById('editar-horario-form').style.display = 'none';
            });
            document.getElementById('guardar-horario-btn').addEventListener('click', async function() {
                if (!eventoActivo) return;
                const horarioId  = eventoActivo.extendedProps.horario_id;
                const moduloId   = eventoActivo.extendedProps.modulo_id;
                const fecha      = document.getElementById('edit-horario-fecha').value;
                const horaInicio = document.getElementById('edit-horario-inicio').value;
                const horaFin    = document.getElementById('edit-horario-fin').value;
                const respId     = document.getElementById('edit-horario-responsable').value;
                const cuentaId   = document.getElementById('edit-horario-cuenta').value;
                if (!fecha || !horaInicio || !horaFin) {
                    showToast('Complete fecha y horas.', 'warning'); return;
                }
                try {
                    const res = await $.ajax({
                        url: "{{ route('admin.horarios.actualizar') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: horarioId,
                            fecha,
                            hora_inicio: horaInicio,
                            hora_fin: horaFin,
                            trabajadores_cargo_id: respId || null,
                            sucursales_cuenta_id: cuentaId || null,
                        }
                    });
                    if (res.success) {
                        // Actualizar labels en el modal
                        document.getElementById('detalle-docente').textContent     = res.docente     || '—';
                        document.getElementById('detalle-responsable').textContent = res.responsable || '—';
                        document.getElementById('detalle-cargo').textContent       = res.cargo       || '—';
                        const nuevaFechaStr = new Date(fecha + 'T00:00:00').toLocaleDateString('es-ES', { year:'numeric', month:'short', day:'numeric' });
                        document.getElementById('detalle-fecha').textContent = nuevaFechaStr;
                        document.getElementById('detalle-hora').textContent  = horaInicio + ' - ' + horaFin;
                        // Sincronizar todosLosEventos y calendario
                        todosLosEventos = todosLosEventos.filter(e => e.extendedProps.modulo_id != moduloId);
                        res.eventos.forEach(e => todosLosEventos.push(e));
                        calendar.getEvents().forEach(ev => { if (ev.extendedProps.modulo_id == moduloId) ev.remove(); });
                        if (!moduloFiltroActivo || moduloFiltroActivo == moduloId) {
                            res.eventos.forEach(e => calendar.addEvent(e));
                        }
                        // Actualizar eventoActivo con los nuevos valores del extendedProps
                        const eventoActualizado = res.eventos.find(e => e.extendedProps.horario_id == horarioId);
                        if (eventoActualizado) {
                            document.getElementById('edit-horario-responsable').value = eventoActualizado.extendedProps.trabajadores_cargo_id || '';
                            document.getElementById('edit-horario-cuenta').value      = eventoActualizado.extendedProps.sucursales_cuenta_id  || '';
                        }
                        document.getElementById('editar-horario-form').style.display = 'none';
                        showToast(res.msg || 'Horario actualizado.');
                    }
                } catch (err) {
                    showToast('Error al actualizar el horario.', 'danger');
                }
            });
            // ─────────────────────────────────────────────────────────────

            document.getElementById('btn-editar-estado').addEventListener('click', function() {
                document.getElementById('editar-estado-form').style.display = 'block';
                document.getElementById('editar-horario-form').style.display = 'none';
                document.getElementById('nuevo-estado-select').value = document.getElementById(
                    'detalle-estado').textContent.trim();
                const estadoActual = document.getElementById('detalle-estado').textContent.trim();
                const btnReprogramar = document.getElementById('btn-reprogramar-sesion');
                if (estadoActual === 'Postergado') {
                    btnReprogramar.classList.remove('d-none');
                } else {
                    btnReprogramar.classList.add('d-none');
                }
            });
            document.getElementById('cancelar-estado-btn').addEventListener('click', function() {
                document.getElementById('editar-estado-form').style.display = 'none';
            });
            document.getElementById('guardar-estado-btn').addEventListener('click', async function() {
                if (!eventoActivo) return;
                const nuevoEstado = document.getElementById('nuevo-estado-select').value;
                const horarioId = eventoActivo.extendedProps.horario_id;
                const moduloId = eventoActivo.extendedProps.modulo_id;
                try {
                    await $.ajax({
                        url: "{{ route('admin.horarios.actualizar-estado') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: horarioId,
                            estado: nuevoEstado
                        }
                    });
                    const nuevosEventos = await $.get(
                        "{{ route('admin.modulos.obtener-eventos', ':id') }}".replace(':id',
                            moduloId));
                    calendar.getEvents().forEach(event => {
                        if (event.extendedProps.modulo_id == moduloId) event.remove();
                    });
                    nuevosEventos.forEach(eventData => calendar.addEvent(eventData));
                    const estadoEl = document.getElementById('detalle-estado');
                    estadoEl.textContent = nuevoEstado;
                    estadoEl.className = 'fw-bold';
                    estadoEl.classList.remove('text-success', 'text-secondary', 'text-warning');
                    if (nuevoEstado === 'Confirmado') estadoEl.classList.add('text-success');
                    else if (nuevoEstado === 'Desarrollado') estadoEl.classList.add('text-secondary');
                    else if (nuevoEstado === 'Postergado') estadoEl.classList.add('text-warning');

                    const btnReprogramar = document.getElementById('btn-reprogramar-sesion');
                    if (nuevoEstado === 'Postergado') {
                        btnReprogramar.classList.remove('d-none');
                    } else {
                        btnReprogramar.classList.add('d-none');
                    }
                    document.getElementById('editar-estado-form').style.display = 'none';
                    showToast('Estado actualizado correctamente.');
                } catch (err) {
                    showToast('Error al actualizar el estado.', 'danger');
                    console.error(err);
                }
            });

            document.getElementById('btn-reprogramar-sesion').addEventListener('click', function() {
                if (!eventoActivo) return;
                const props = eventoActivo.extendedProps;
                const start = new Date(eventoActivo.start);
                const fechaOriginalStr = start.toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
                document.getElementById('reprogramar-fecha-original').textContent =
                    `${fechaOriginalStr} (${props.hora_inicio} - ${props.hora_fin})`;
                document.getElementById('horario-original-id').value = props.horario_id;
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('reprogramar-fecha').valueAsDate = tomorrow;
                const reprogramarModal = new bootstrap.Modal(document.getElementById(
                    'modalReprogramarSesion'));
                reprogramarModal.show();
            });

            document.getElementById('guardar-reprogramacion-btn').addEventListener('click', async function() {
                if (!eventoActivo) return;
                const horarioOriginalId = document.getElementById('horario-original-id').value;
                const nuevaFecha = document.getElementById('reprogramar-fecha').value;
                const nuevaHoraInicio = document.getElementById('reprogramar-hora-inicio').value;
                const nuevaHoraFin = document.getElementById('reprogramar-hora-fin').value;
                const moduloId = eventoActivo.extendedProps.modulo_id;
                if (!horarioOriginalId || !nuevaFecha || !nuevaHoraInicio || !nuevaHoraFin) {
                    showToast('Por favor, complete todos los campos.', 'warning');
                    return;
                }
                try {
                    const res = await $.ajax({
                        url: "{{ route('admin.horarios.reprogramar') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            horario_original_id: horarioOriginalId,
                            fecha: nuevaFecha,
                            hora_inicio: nuevaHoraInicio,
                            hora_fin: nuevaHoraFin
                        }
                    });
                    if (res.success) {
                        const nuevosEventos = await $.get(
                            "{{ route('admin.modulos.obtener-eventos', ':id') }}".replace(':id',
                                moduloId));
                        calendar.getEvents().forEach(event => {
                            if (event.extendedProps.modulo_id == moduloId) event.remove();
                        });
                        nuevosEventos.forEach(eventData => calendar.addEvent(eventData));
                        $('#modalReprogramarSesion').modal('hide');
                        $('#modalDetalleHorario').modal('hide');
                        showToast(res.msg || 'Sesión reprogramada correctamente.');
                    }
                } catch (err) {
                    showToast('Error al reprogramar la sesión.', 'danger');
                    console.error(err);
                }
            });
        });

        // === CARGAR CIUDADES POR DEPARTAMENTO (DOCENTE) ===
        $('#depto_docente').on('change', function() {
            const deptoId = $(this).val();
            const ciudadSelect = $('#ciudad_docente');
            ciudadSelect.empty().prop('disabled', true);
            if (!deptoId) return;
            @foreach ($ciudades as $c)
                if ({{ $c['departamento_id'] }} == deptoId) {
                    ciudadSelect.append(`<option value="{{ $c['id'] }}">{{ $c['nombre'] }}</option>`);
                }
            @endforeach
            ciudadSelect.prop('disabled', false);
        });

        // === ASIGNAR DOCENTE ===
        let moduloIdGlobal = null;
        $(document).on('click', '.asignar-docente', function() {
            const moduloId = $(this).data('modulo-id');
            const docenteId = $(this).data('docente-id');
            moduloIdGlobal = moduloId;
            $('#modulo_id_docente').val(moduloId);

            // Reset completo del modal a estado inicial
            $('#formAsignarDocenteExistente, #formRegistrarComoDocente, #formNuevaPersonaDocente').hide();
            $('#paso-buscar-docente').show();
            $('#carnet_docente').val('');
            $('#mensaje-verificacion-docente').html('');
            $('#btn-registrar-nueva-persona-docente').prop('disabled', true);

            if (docenteId) {
                const nombre = $(this).closest('.external-event').find('.mt-1 .text-truncate').text().trim();
                $('#mensaje-verificacion-docente').html(`
                    <div class="alert alert-info border-0 d-flex align-items-center gap-2 py-2">
                        <i class="ri-user-check-line fs-5 text-info"></i>
                        <div style="font-size:.85rem;">Docente actual: <strong>${nombre || 'Asignado'}</strong></div>
                        <button type="button" class="btn btn-sm btn-warning ms-auto" id="btn-cambiar-docente">
                            <i class="ri-refresh-line me-1"></i>Cambiar
                        </button>
                    </div>
                `);
            }
        });

        $(document).on('click', '#btn-cambiar-docente', function() {
            $('#mensaje-verificacion-docente').html('');
        });

        let debounceDocente;
        $('#carnet_docente').on('input', function() {
            const carnet = $(this).val().trim();
            $('#mensaje-verificacion-docente').html('');
            $('#btn-registrar-nueva-persona-docente').prop('disabled', true);
            if (!carnet) return;

            clearTimeout(debounceDocente);
            debounceDocente = setTimeout(() => {
                $.post("{{ route('admin.docentes.verificar-carnet') }}", {
                    _token: "{{ csrf_token() }}",
                    carnet: carnet
                }).done(function(res) {
                    if (res.is_docente) {
                        $('#mensaje-verificacion-docente').html(
                            `<div class="alert alert-success">${res.message}<br><strong>${res.persona.nombre_completo}</strong></div>`
                        );
                        $('#docente_seleccionado_id').val(res.docente_id);
                        $('#nombre_docente_existente').text(res.persona.nombre_completo);
                        $('#paso-buscar-docente, #formRegistrarComoDocente, #formNuevaPersonaDocente')
                            .hide();
                        $('#formAsignarDocenteExistente').show();
                    } else if (res.exists) {
                        $('#mensaje-verificacion-docente').html(
                            `<div class="alert alert-warning">${res.message}<br><strong>${res.persona.nombre_completo}</strong></div>`
                        );
                        $('#persona_id_no_docente').val(res.persona.id);
                        $('#nombre_persona_no_docente').text(res.persona.nombre_completo);
                        $('#paso-buscar-docente, #formAsignarDocenteExistente, #formNuevaPersonaDocente')
                            .hide();
                        $('#formRegistrarComoDocente').show();
                    } else {
                        $('#mensaje-verificacion-docente').html(
                            `<div class="alert alert-danger">${res.message}</div>`);
                        $('#btn-registrar-nueva-persona-docente').prop('disabled', false);
                        $('#formAsignarDocenteExistente, #formRegistrarComoDocente').hide();
                    }
                }).fail(function() {
                    $('#mensaje-verificacion-docente').html(
                        `<div class="alert alert-danger">Error al verificar el carnet.</div>`);
                });
            }, 400);
        });

        $('#btn-registrar-nueva-persona-docente').on('click', function() {
            $('#paso-buscar-docente, #formAsignarDocenteExistente, #formRegistrarComoDocente').hide();
            $('#formNuevaPersonaDocente').show();
        });

        $('#btn-volver-buscar-docente, #btn-volver-buscar-docente2, #btn-volver-buscar-docente3').on('click', function() {
            $('#formAsignarDocenteExistente, #formRegistrarComoDocente, #formNuevaPersonaDocente').hide();
            $('#paso-buscar-docente').show();
            $('#carnet_docente').val('');
            $('#mensaje-verificacion-docente').html('');
        });

        // === VALIDACIÓN PARA NUEVA PERSONA DOCENTE ===
        function validarApellidosDocente() {
            const p = $('#paterno_nuevo_docente').val().trim();
            const m = $('#materno_nuevo_docente').val().trim();
            if (!p && !m) {
                $('#feedback_apellidos_docente').text('Debe ingresar al menos un apellido.');
                return false;
            } else {
                $('#feedback_apellidos_docente').text('');
                return true;
            }
        }

        function calcularEdadDocente() {
            const fecha = $('#fecha_nac_nuevo_docente').val();
            if (!fecha) {
                $('#edad_calculada_nuevo_docente').text('');
                return true;
            }
            const hoy = new Date();
            const nac = new Date(fecha);
            let edad = hoy.getFullYear() - nac.getFullYear();
            const mes = hoy.getMonth() - nac.getMonth();
            if (mes < 0 || (mes === 0 && hoy.getDate() < nac.getDate())) edad--;
            $('#edad_calculada_nuevo_docente').text(`Edad: ${edad} años`);
            return true;
        }

        function checkFormNuevaPersonaDocente() {
            const carnetOk = $('#feedback_carnet_docente').hasClass('text-success');
            const correoOk = $('#feedback_correo_docente').hasClass('text-success');
            const nombres = $('#nombres_nuevo_docente').val().trim();
            const celular = $('#celular_nuevo_docente').val().trim();
            const ciudade = $('select[name="ciudade_id"]').val();
            const sexo = $('select[name="sexo"]').val();
            const ecivil = $('select[name="estado_civil"]').val();
            const apellidosOk = validarApellidosDocente();
            const edadOk = !$('#fecha_nac_nuevo_docente').val() || calcularEdadDocente();
            const enabled = carnetOk && correoOk && nombres && celular && ciudade && sexo && ecivil && apellidosOk;
            $('#btn-guardar-nueva-persona-docente').prop('disabled', !enabled);
        }

        $('#formNuevaPersonaDocente input, #formNuevaPersonaDocente select').on('input change',
            checkFormNuevaPersonaDocente);
        $('#fecha_nac_nuevo_docente').on('change', checkFormNuevaPersonaDocente);

        $('#carnet_nuevo_docente').on('blur', function() {
            const carnet = $(this).val().trim();
            if (!carnet) return;
            $.post("{{ route('admin.docentes.verificar-carnet') }}", {
                _token: "{{ csrf_token() }}",
                carnet: carnet
            }).done(function(res) {
                if (!res.exists) {
                    $('#feedback_carnet_docente').removeClass('text-danger').addClass('text-success').text(
                        'Carnet disponible.');
                } else {
                    $('#feedback_carnet_docente').removeClass('text-success').addClass('text-danger').text(
                        'Carnet ya registrado.');
                }
            }).fail(function() {
                $('#feedback_carnet_docente').removeClass('text-success').addClass('text-danger').text(
                    'Error al verificar.');
            });
        });

        $('#correo_nuevo_docente').on('blur', function() {
            const correo = $(this).val().trim();
            if (!correo) return;
            $.post("{{ route('admin.personas.verificar-correo') }}", {
                _token: "{{ csrf_token() }}",
                correo: correo
            }).done(function(res) {
                if (!res.exists) {
                    $('#feedback_correo_docente').removeClass('text-danger').addClass('text-success').text(
                        'Correo disponible.');
                } else {
                    $('#feedback_correo_docente').removeClass('text-success').addClass('text-danger').text(
                        'Correo ya registrado.');
                }
            }).fail(function() {
                $('#feedback_correo_docente').removeClass('text-success').addClass('text-danger').text(
                    'Error al verificar.');
            });
        });

        // === DINÁMICA DE ESTUDIOS ===
        $(document).on('change', '.grado-select-docente', function() {
            const row = $(this).closest('.estudio-item-docente');
            const gradoId = $(this).val();
            if (!gradoId) {
                row.find('.profesion-select-docente, .universidad-select-docente').prop('disabled', true)
                    .html('<option value="">Profesión</option>');
                row.find('.universidad-select-docente').html('<option value="">Universidad</option>');
                return;
            }
            let htmlProf = '<option value="">Profesión</option>';
            @foreach ($profesiones as $p)
                htmlProf += `<option value="{{ $p->id }}">{{ $p->nombre }}</option>`;
            @endforeach
            row.find('.profesion-select-docente').html(htmlProf).prop('disabled', false);
            let htmlUni = '<option value="">Universidad</option>';
            @foreach ($universidades as $u)
                htmlUni +=
                    `<option value="{{ $u->id }}">{{ $u->nombre }} ({{ $u->sigla }})</option>`;
            @endforeach
            row.find('.universidad-select-docente').html(htmlUni).prop('disabled', false);
        });

        $(document).on('click', '.add-estudio-docente', function() {
            const index = $('.estudio-item-docente').length;
            let html = `
                <div class="estudio-item-docente row mb-2">
                    <div class="col-md-3">
                        <select class="form-select grado-select-docente" name="estudios[${index}][grado]">
                            <option value="">Grado</option>
                            @foreach ($grados as $g)
                                <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select profesion-select-docente" name="estudios[${index}][profesion]" disabled>
                            <option value="">Profesión</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select universidad-select-docente" name="estudios[${index}][universidad]" disabled>
                            <option value="">Universidad</option>
                            @foreach ($universidades as $u)
                                <option value="{{ $u->id }}">{{ $u->nombre }} ({{ $u->sigla }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-estudio-docente">−</button>
                    </div>
                </div>`;
            $('#estudios-container-docente').append(html);
        });

        $(document).on('click', '.remove-estudio-docente', function() {
            $(this).closest('.estudio-item-docente').remove();
        });

        // === VALIDACIÓN Y ENVÍO DEL FORMULARIO NUEVA PERSONA DOCENTE ===
        $('#formNuevaPersonaDocente').submit(function(e) {
            e.preventDefault();
            if (!validarApellidosDocente()) return;
            if ($('#fecha_nac_nuevo_docente').val() && !calcularEdadDocente()) return;

            let estudiosValidos = true;
            $('.estudio-item-docente').each(function() {
                const g = $(this).find('.grado-select-docente').val();
                const p = $(this).find('.profesion-select-docente').val();
                const u = $(this).find('.universidad-select-docente').val();
                if (g || p || u) {
                    if (!g || !p || !u) {
                        estudiosValidos = false;
                        return false;
                    }
                }
            });
            if (!estudiosValidos) {
                showToast('Si agrega estudios, debe completar Grado, Profesión y Universidad.', 'warning');
                return;
            }

            $.post("{{ route('admin.docentes.registrar-persona-y-docente') }}", $(this).serialize())
                .done(function(res) {
                    if (res.success) {
                        $.post("{{ route('admin.modulos.asignar-docente') }}", {
                            _token: "{{ csrf_token() }}",
                            modulo_id: moduloIdGlobal,
                            docente_id: res.docente_id
                        }).done(function(asignRes) {
                            if (asignRes.success) {
                                actualizarDocenteEnPanel(moduloIdGlobal, asignRes.docente_id, asignRes.docente_nombre);
                                showToast('Docente registrado y asignado correctamente.');
                                $('#modalAsignarDocente').modal('hide');
                            }
                        });
                    }
                })
                .fail(function(xhr) {
                    const errors = xhr.responseJSON?.errors || {};
                    if (errors.carnet) $('#feedback_carnet_docente').addClass('text-danger').text(errors.carnet[
                        0]);
                    if (errors.correo) $('#feedback_correo_docente').addClass('text-danger').text(errors.correo[
                        0]);
                    if (errors.apellidos) $('#feedback_apellidos_docente').text(errors.apellidos[0]);
                    if (errors.fecha_nacimiento) $('#edad_calculada_nuevo_docente').addClass('text-danger')
                        .text(errors.fecha_nacimiento[0]);
                    checkFormNuevaPersonaDocente();
                });
        });

        $('#formRegistrarComoDocente').submit(function(e) {
            e.preventDefault();
            const personaId = $('#persona_id_no_docente').val();
            $.post("{{ route('admin.docentes.registrar') }}", {
                _token: "{{ csrf_token() }}",
                persona_id: personaId
            }).done(function(res) {
                if (res.success) {
                    $.post("{{ route('admin.modulos.asignar-docente') }}", {
                        _token: "{{ csrf_token() }}",
                        modulo_id: moduloIdGlobal,
                        docente_id: res.docente_id
                    }).done(function(asignRes) {
                        if (asignRes.success) {
                            const nombre = $('#nombre_persona_no_docente').text();
                            actualizarDocenteEnPanel(moduloIdGlobal, res.docente_id, nombre);
                            showToast('Docente registrado y asignado correctamente.');
                            $('#modalAsignarDocente').modal('hide');
                        }
                    });
                }
            }).fail(function(xhr) {
                showToast(xhr.responseJSON?.msg || 'Error al registrar como docente.', 'danger');
            });
        });

        $('#formAsignarDocenteExistente').submit(function(e) {
            e.preventDefault();
            const docenteId = $('#docente_seleccionado_id').val();
            $.post("{{ route('admin.modulos.asignar-docente') }}", {
                _token: "{{ csrf_token() }}",
                modulo_id: moduloIdGlobal,
                docente_id: docenteId
            }).done(function(res) {
                if (res.success) {
                    const nombre = $('#nombre_docente_existente').text();
                    actualizarDocenteEnPanel(moduloIdGlobal, docenteId, nombre);
                    showToast('Docente asignado correctamente.');
                    $('#modalAsignarDocente').modal('hide');
                }
            }).fail(function() {
                showToast('Error al asignar docente.', 'danger');
            });
        });

        // === HELPER: actualizar docente en el panel lateral ===
        function actualizarDocenteEnPanel(moduloId, docenteId, nombre) {
            const btn = $(`.asignar-docente[data-modulo-id="${moduloId}"]`);
            btn.data('docente-id', docenteId).attr('data-docente-id', docenteId);
            btn.closest('.external-event')
               .find('.mt-1 .text-truncate')
               .html(`<i class="ri-user-line me-1"></i>${nombre}`);
        }

        // === RESTO: ASIGNAR HORARIOS, COLOR, ETC. ===
        $(document).on('click', '.asignar-horarios', function() {
            const moduloId = $(this).data('modulo-id');
            const sesionesDefault = $(this).data('sesiones') || 1;
            $('#modulo_id').val(moduloId);
            $('#horarios-container').empty();
            $('#formAsignarHorarios button[type="submit"]').prop('disabled', true).text('Cargando...');
            $.get("{{ route('admin.modulos.obtener-horarios', ':id') }}".replace(':id', moduloId))
                .done(function(data) {
                    $('#formAsignarHorarios select[name="trabajadores_cargo_id"]').val(data
                        .trabajadores_cargo_id || '').trigger('change');
                    $('#formAsignarHorarios select[name="sucursales_cuenta_id"]').val(data
                        .sucursales_cuenta_id || '').trigger('change');
                    let html = `<h6>Sesiones (${data.cantidad_sesiones || sesionesDefault})</h6>`;
                    if (data.horarios.length > 0) {
                        data.horarios.forEach((h, i) => {
                            // Normalizar: fecha → YYYY-MM-DD, horas → HH:MM
                            const fecha     = (h.fecha     || '').substring(0, 10);
                            const horaIni   = (h.hora_inicio || '').substring(0, 5);
                            const horaFin   = (h.hora_fin    || '').substring(0, 5);
                            html += `
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Fecha *</label>
                            <input type="date" name="horarios[${i}][fecha]" class="form-control" value="${fecha}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Hora Inicio *</label>
                            <input type="time" name="horarios[${i}][hora_inicio]" class="form-control" value="${horaIni}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Hora Fin *</label>
                            <input type="time" name="horarios[${i}][hora_fin]" class="form-control" value="${horaFin}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado *</label>
                            <select name="horarios[${i}][estado]" class="form-select" required>
                                <option value="Confirmado" ${h.estado === 'Confirmado' ? 'selected' : ''}>Confirmado</option>
                                <option value="Desarrollado" ${h.estado === 'Desarrollado' ? 'selected' : ''}>Desarrollado</option>
                                <option value="Postergado" ${h.estado === 'Postergado' ? 'selected' : ''}>Postergado</option>
                            </select>
                        </div>
                    </div>`;
                        });
                    } else {
                        for (let i = 0; i < sesionesDefault; i++) {
                            html += `
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Fecha *</label>
                            <input type="date" name="horarios[${i}][fecha]" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Hora Inicio *</label>
                            <input type="time" name="horarios[${i}][hora_inicio]" value="19:00" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Hora Fin *</label>
                            <input type="time" name="horarios[${i}][hora_fin]" value="22:00" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado *</label>
                            <select name="horarios[${i}][estado]" class="form-select" required>
                                <option value="Confirmado">Confirmado</option>
                                <option value="Desarrollado">Desarrollado</option>
                                <option value="Postergado">Postergado</option>
                            </select>
                        </div>
                    </div>`;
                        }
                    }
                    $('#horarios-container').html(html);
                    $('#formAsignarHorarios button[type="submit"]').prop('disabled', false).text(
                        'Guardar Horarios');
                })
                .fail(function() {
                    showToast('Error al cargar los horarios.', 'danger');
                    $('#formAsignarHorarios button[type="submit"]').prop('disabled', false).text(
                        'Guardar Horarios');
                });
        });

        $('#formAsignarHorarios').submit(function(e) {
            e.preventDefault();
            const moduloId = $('#modulo_id').val();
            $.ajax({
                url: "{{ route('admin.modulos.asignar-horarios') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        // Actualizar todosLosEventos
                        todosLosEventos = todosLosEventos.filter(e => e.extendedProps.modulo_id != moduloId);
                        res.eventos.forEach(e => todosLosEventos.push(e));
                        // Actualizar calendario (respetando filtro activo)
                        calendar.getEvents().forEach(event => {
                            if (event.extendedProps.modulo_id == moduloId) event.remove();
                        });
                        if (!moduloFiltroActivo || moduloFiltroActivo == moduloId) {
                            res.eventos.forEach(eventData => calendar.addEvent(eventData));
                            // Navegar al mes del primer horario para que sean visibles
                            if (res.eventos.length > 0) {
                                calendar.gotoDate(res.eventos[0].start.substring(0, 10));
                            }
                        }
                        $('#modalAsignarHorarios').modal('hide');
                        showToast(res.msg || 'Horarios guardados correctamente.');
                    } else {
                        showToast(res.msg || 'Error desconocido.', 'danger');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error al guardar. Verifique los datos.';
                    if (xhr.responseJSON?.msg) errorMsg = xhr.responseJSON.msg;
                    else if (xhr.responseJSON?.message) errorMsg = xhr.responseJSON.message;
                    showToast(errorMsg, 'danger');
                    console.error(xhr.responseText);
                }
            });
        });

        $(document).on('click', '.cambiar-color-modulo', function() {
            const moduloId = $(this).data('modulo-id');
            const colorActual = $(this).data('color') || '#cccccc';
            $('#modulo-id-color').val(moduloId);
            $('#color-picker').val(colorActual);
            $('#color-preview').css('background-color', colorActual);
        });

        $('#color-picker').on('input', function() {
            $('#color-preview').css('background-color', $(this).val());
        });

        $('#guardar-color-btn').on('click', function() {
            const moduloId = $('#modulo-id-color').val();
            const nuevoColor = $('#color-picker').val();
            if (!moduloId || !nuevoColor) return;
            $.ajax({
                url: "{{ route('admin.modulos.actualizar-color') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: moduloId,
                    color: nuevoColor
                },
                success: function(res) {
                    if (res.success) {
                        $(`.cambiar-color-modulo[data-modulo-id="${moduloId}"]`)
                            .closest('.external-event')
                            .css({
                                'background-color': nuevoColor,
                                'color': (nuevoColor !== '#FFFFFF' && nuevoColor !== '#ffffff') ?
                                    '#FFFFFF' : '#000000'
                            });
                        $.get("{{ route('admin.modulos.obtener-eventos', ':id') }}".replace(':id',
                                moduloId))
                            .done(function(nuevosEventos) {
                                // Sincronizar todosLosEventos
                                todosLosEventos = todosLosEventos.filter(e => e.extendedProps.modulo_id != moduloId);
                                nuevosEventos.forEach(e => todosLosEventos.push(e));
                                // Actualizar calendario
                                calendar.getEvents().forEach(event => {
                                    if (event.extendedProps.modulo_id == moduloId) event.remove();
                                });
                                if (!moduloFiltroActivo || moduloFiltroActivo == moduloId) {
                                    nuevosEventos.forEach(eventData => calendar.addEvent(eventData));
                                }
                            });
                        $('#modalColorModulo').modal('hide');
                        showToast('Color del módulo actualizado correctamente.');
                    } else {
                        showToast('Error al actualizar el color.', 'danger');
                    }
                },
                error: function() {
                    showToast('Error de conexión. Intente nuevamente.', 'danger');
                }
            });
        });
    </script>
@endpush
