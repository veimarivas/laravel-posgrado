@extends('admin.dashboard')
@section('admin')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Sede: {{ $oferta->sucursal->sede->nombre }} - Sucursal: {{ $oferta->sucursal->nombre }}
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Ofertas académicas</a></li>
                        <li class="breadcrumb-item active">{{ $oferta->programa->nombre }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card card-h-100">
                        <div class="card-body">

                            <div id="external-events">
                                <br />
                                <p class="text-muted text-center">Módulos registrados al programa</p>
                                @foreach ($oferta->modulos as $modulo)
                                    <div class="external-event d-flex justify-content-between align-items-center px-2 py-1 mb-2"
                                        style="background-color: {{ $modulo->color }}; color: {{ $modulo->color != '#FFFFFF' && $modulo->color != '#ffffff' ? '#FFFFFF' : '#000000' }};">
                                        <span>{{ $modulo->nombre }}</span>
                                        <div>
                                            <!-- Ícono Asignar Horarios -->
                                            <a href="javascript:void(0);" class="text-primary me-2 asignar-horarios"
                                                title="Asignar Horarios" data-modulo-id="{{ $modulo->id }}"
                                                data-sesiones="{{ $oferta->cantidad_sesiones }}" data-bs-toggle="modal"
                                                data-bs-target="#modalAsignarHorarios">
                                                <i class="ri-calendar-2-line fs-5"></i>
                                            </a>
                                            <!-- Ícono Cambiar Color -->
                                            <a href="javascript:void(0);" class="text-muted me-2 cambiar-color-modulo"
                                                title="Cambiar Color" data-modulo-id="{{ $modulo->id }}"
                                                data-color="{{ $modulo->color }}" data-bs-toggle="modal"
                                                data-bs-target="#modalColorModulo">
                                                <i class="ri-paint-brush-line fs-5"></i>
                                            </a>
                                            <!-- Ícono Asignar Docente -->
                                            <a href="javascript:void(0);" class="text-success me-2 asignar-docente"
                                                title="Asignar Docente" data-modulo-id="{{ $modulo->id }}"
                                                data-docente-id="{{ $modulo->docente_id }}" data-bs-toggle="modal"
                                                data-bs-target="#modalAsignarDocente">
                                                <i class="ri-user-follow-line fs-5"></i>
                                            </a>
                                            <!-- Ícono Ver -->
                                            <a href="javascript:void(0);" class="text-info me-2" title="Ver"
                                                data-bs-toggle="modal" data-bs-target="#modalVerModulo">
                                                <i class="ri-eye-line fs-5"></i>
                                            </a>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col-->
                <div class="col-xl-9">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!--end row-->
            <div style="clear: both"></div>
        </div>
    </div>
    <!-- end row-->

    <!-- Modal Asignar Horarios -->
    <div class="modal fade" id="modalAsignarHorarios" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Asignar Horarios al Módulo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formAsignarHorarios">
                        @csrf
                        <input type="hidden" id="modulo_id" name="modulo_id">
                        <!-- Selector de responsable -->
                        <div class="mb-3">
                            <label class="form-label">Responsable</label>
                            <select name="trabajadores_cargo_id" class="form-select">
                                <option value="">Seleccionar...</option>
                                @foreach ($trabajadoresCargos as $tc)
                                    <option value="{{ $tc->id }}">
                                        {{ optional($tc->trabajador->persona)->nombres ?? 'Sin nombre' }}
                                        {{ optional($tc->trabajador->persona)->apellido_paterno ?? '' }}
                                        - {{ $tc->cargo->nombre ?? 'Sin cargo' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Selector de cuenta -->
                        <div class="mb-3">
                            <label class="form-label">Cuenta</label>
                            <select name="sucursales_cuenta_id" class="form-select">
                                <option value="">Seleccionar...</option>
                                @foreach ($sucursalesCuentas as $sc)
                                    <option value="{{ $sc->id }}">
                                        {{ $sc->cuenta->correo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Contenedor dinámico de sesiones -->
                        <div id="horarios-container"></div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">Guardar Horarios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Asignar Docente -->
    <div class="modal fade" id="modalAsignarDocente" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Asignar Docente al Módulo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modulo_id_docente">
                    <input type="hidden" id="docente_seleccionado_id">
                    <input type="hidden" id="persona_id_no_docente">

                    <!-- Paso 1: Buscar por carnet -->
                    <div id="paso-buscar-docente">
                        <div class="mb-3">
                            <label class="form-label">Carnet del docente *</label>
                            <input type="text" id="carnet_docente" class="form-control" placeholder="Ingrese carnet">
                            <div id="mensaje-verificacion-docente" class="mt-2"></div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" id="btn-registrar-nueva-persona-docente"
                                disabled>
                                Registrar nueva persona
                            </button>
                        </div>
                    </div>

                    <!-- Paso 2: Confirmar asignación (existe y es docente) -->
                    <form id="formAsignarDocenteExistente" style="display:none;">
                        <p>¿Asignar a <strong id="nombre_docente_existente"></strong> como docente de este módulo?</p>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary"
                                id="btn-volver-buscar-docente">Volver</button>
                            <button type="submit" class="btn btn-success">Asignar Docente</button>
                        </div>
                    </form>

                    <!-- Paso 3: Registrar como docente (existe pero no es docente) -->
                    <form id="formRegistrarComoDocente" style="display:none;">
                        <p>¿Registrar a <strong id="nombre_persona_no_docente"></strong> como docente y asignarlo?</p>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary"
                                id="btn-volver-buscar-docente2">Volver</button>
                            <button type="submit" class="btn btn-primary">Registrar como Docente</button>
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
                            <h6>Estudios Académicos</h6>
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
                        <div class="col-12 text-center mt-3">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary"
                                    id="btn-volver-buscar-docente3">Volver</button>
                                <button type="submit" class="btn btn-primary" id="btn-guardar-nueva-persona-docente"
                                    disabled>
                                    Registrar como Docente
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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles del Horario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Módulo:</strong> <span id="detalle-modulo"></span></p>
                    <p><strong>Responsable:</strong> <span id="detalle-responsable"></span></p>
                    <p><strong>Cargo:</strong> <span id="detalle-cargo"></span></p>
                    <p><strong>Fecha:</strong> <span id="detalle-fecha"></span></p>
                    <p><strong>Hora:</strong> <span id="detalle-hora"></span></p>
                    <p><strong>Estado:</strong> <span id="detalle-estado" class="fw-bold"></span></p>
                    <div id="editar-estado-form" class="mt-3" style="display:none;">
                        <label class="form-label">Cambiar estado:</label>
                        <select id="nuevo-estado-select" class="form-select mb-2">
                            <option value="Confirmado">Confirmado</option>
                            <option value="Desarrollado">Desarrollado</option>
                            <option value="Postergado">Postergado</option>
                        </select>
                        <button id="guardar-estado-btn" class="btn btn-success btn-sm">Guardar</button>
                        <button id="cancelar-estado-btn" class="btn btn-secondary btn-sm ms-1">Cancelar</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="btn-editar-estado">Editar Estado</button>
                    <button type="button" class="btn btn-info d-none" id="btn-reprogramar-sesion">Reprogramar
                        Sesión</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cambiar Color de Módulo -->
    <div class="modal fade" id="modalColorModulo" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Color del Módulo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <input type="color" id="color-picker" class="form-control form-control-color mx-auto mb-3"
                        value="#cccccc" title="Elige un color">
                    <div id="color-preview" style="width: 100px; height: 50px; margin: 0 auto; border: 1px solid #ccc;">
                    </div>
                    <input type="hidden" id="modulo-id-color">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="guardar-color-btn">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reprogramar Sesión -->
    <div class="modal fade" id="modalReprogramarSesion" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reprogramar Sesión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Sesión original:</strong> <span id="reprogramar-fecha-original"></span></p>
                    <input type="hidden" id="horario-original-id">
                    <div class="mb-3">
                        <label class="form-label">Nueva Fecha *</label>
                        <input type="date" id="reprogramar-fecha" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Hora Inicio *</label>
                            <input type="time" id="reprogramar-hora-inicio" class="form-control" value="19:00"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hora Fin *</label>
                            <input type="time" id="reprogramar-hora-fin" class="form-control" value="22:00"
                                required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="guardar-reprogramacion-btn">Reprogramar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('backend/assets/libs/fullcalendar/index.global.min.js') }}"></script>
    <script>
        let calendar;
        let eventoActivo = null;

        document.addEventListener("DOMContentLoaded", function() {
            var calendarEl = document.getElementById("calendar");
            var eventos = [];
            @foreach ($oferta->modulos as $modulo)
                @foreach ($modulo->horarios as $horario)
                    @php
                        $start = $horario->fecha . 'T' . $horario->hora_inicio;
                        $end = $horario->fecha . 'T' . $horario->hora_fin;
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
                        title: `{{ addslashes($title) }}`,
                        start: `{{ $start }}`,
                        end: `{{ $end }}`,
                        className: 'text-with',
                        extendedProps: {
                            modulo_id: {{ $modulo->id }},
                            horario_id: {{ $horario->id }},
                            responsable: `{{ $responsable }}`,
                            cargo: `{{ $cargo }}`,
                            estado: `{{ $estado }}`,
                            color_modulo: `{{ $modulo->color }}`
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
                    document.getElementById('detalle-responsable').textContent = props.responsable ||
                        '—';
                    document.getElementById('detalle-cargo').textContent = props.cargo || '—';
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
                    new bootstrap.Modal(document.getElementById('modalDetalleHorario')).show();
                },
            });
            calendar.render();

            // === LISTENERS DE MODAL DETALLE ===
            document.getElementById('btn-editar-estado').addEventListener('click', function() {
                document.getElementById('editar-estado-form').style.display = 'block';
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
                    alert('Estado actualizado correctamente.');
                } catch (err) {
                    alert('Error al actualizar el estado.');
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
                    alert('Por favor, complete todos los campos.');
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
                        alert(res.msg);
                    }
                } catch (err) {
                    alert('Error al reprogramar la sesión.');
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

            if (docenteId) {
                $('#mensaje-verificacion-docente').html(`
                    <div class="alert alert-info">
                        Docente actual: <strong>${$(this).closest('.external-event').find('span').text()}</strong><br>
                        <button type="button" class="btn btn-sm btn-warning mt-2" id="btn-cambiar-docente">Cambiar Docente</button>
                    </div>
                `);
                $('#paso-buscar-docente input').val('');
                $('#formAsignarDocenteExistente, #formRegistrarComoDocente, #formNuevaPersonaDocente').hide();
            } else {
                $('#mensaje-verificacion-docente').html('');
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
                alert('Si agrega estudios, debe completar Grado, Profesión y Universidad.');
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
                                alert('Docente registrado y asignado correctamente.');
                                $(`.asignar-docente[data-modulo-id="${moduloIdGlobal}"]`)
                                    .closest('.external-event')
                                    .find('span')
                                    .text(asignRes.docente_nombre);
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
                            alert('Docente registrado y asignado correctamente.');
                            $('#modalAsignarDocente').modal('hide');
                        }
                    });
                }
            }).fail(function(xhr) {
                alert(xhr.responseJSON?.msg || 'Error al registrar como docente.');
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
                    alert('Docente asignado correctamente.');
                    $('#modalAsignarDocente').modal('hide');
                }
            }).fail(function() {
                alert('Error al asignar docente.');
            });
        });

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
                            html += `
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Fecha *</label>
                            <input type="date" name="horarios[${i}][fecha]" class="form-control" value="${h.fecha}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Hora Inicio *</label>
                            <input type="time" name="horarios[${i}][hora_inicio]" class="form-control" value="${h.hora_inicio}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Hora Fin *</label>
                            <input type="time" name="horarios[${i}][hora_fin]" class="form-control" value="${h.hora_fin}" required>
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
                    alert('Error al cargar los horarios.');
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
                        calendar.getEvents().forEach(event => {
                            if (event.extendedProps.modulo_id == moduloId) event.remove();
                        });
                        res.eventos.forEach(eventData => calendar.addEvent(eventData));
                        $('#modalAsignarHorarios').modal('hide');
                        alert(res.msg);
                    } else {
                        alert(res.msg || 'Error desconocido.');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error al guardar. Verifique los datos.';
                    if (xhr.responseJSON?.msg) errorMsg = xhr.responseJSON.msg;
                    else if (xhr.responseJSON?.message) errorMsg = xhr.responseJSON.message;
                    alert(errorMsg);
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
                                calendar.getEvents().forEach(event => {
                                    if (event.extendedProps.modulo_id == moduloId) event
                                        .remove();
                                });
                                nuevosEventos.forEach(eventData => calendar.addEvent(eventData));
                            });
                        $('#modalColorModulo').modal('hide');
                        alert('Color actualizado correctamente.');
                    } else {
                        alert('Error al actualizar el color.');
                    }
                },
                error: function() {
                    alert('Error de conexión. Intente nuevamente.');
                }
            });
        });
    </script>
@endpush
