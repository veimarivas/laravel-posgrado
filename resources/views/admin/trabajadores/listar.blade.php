@extends('admin.dashboard')
@section('admin')
    @if (Auth::guard('web')->user()->can('trabajadores.registrar'))
        <nav class="page-breadcrumb">
            <ol class="breadcrumb">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrarTrabajador">
                    <i class="bi bi-person-plus"></i> Registrar Trabajador
                </button>
            </ol>
        </nav>
    @endif
    <!-- Modal Registrar Trabajador -->
    <div class="modal fade" id="registrarTrabajador" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Trabajador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Paso 1: Ingresar carnet -->
                    <div id="paso-carnet">
                        <div class="mb-3">
                            <label class="form-label">Carnet de la persona *</label>
                            <input type="text" id="carnet_trabajador" class="form-control" placeholder="Ingrese carnet">
                            <div id="mensaje-verificacion" class="mt-2"></div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" id="btn-registrar-nueva" disabled>
                                <i class="bi bi-person-plus"></i> Registrar nueva persona
                            </button>
                        </div>
                    </div>
                    <!-- Paso 2: Formulario para persona existente (pero no trabajador) -->
                    <form id="formRegistrarTrabajador" style="display:none;" class="row g-3">
                        @csrf
                        <input type="hidden" name="persona_id" id="persona_id_input">
                        <div class="col-12 mb-3">
                            <h5 class="border-bottom pb-2">Asignar Cargo a <strong id="nombre_persona"
                                    class="text-primary"></strong></h5>
                        </div>
                        <!-- Único bloque de asignación de cargo -->
                        <div id="cargo-container" class="row mb-3 p-2 border rounded bg-light">
                            <!-- En el modal de nuevo cargo, cambia el select de cargos: -->
                            <!-- En el formulario #formRegistrarTrabajador, cambia esto: -->
                            <div class="col-md-4">
                                <label class="form-label">Cargo *</label>
                                <select class="form-select cargo-select" name="cargo_id" required>
                                    <option value="">Seleccionar cargo</option>
                                    @foreach ($cargos as $cargo)
                                        <option value="{{ $cargo->id }}" data-tipo="{{ $cargo->nombre }}">
                                            {{ $cargo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4" id="sucursal_container">
                                <label class="form-label">Sucursal *</label>
                                <select class="form-select sucursal-select" name="sucursal_id" required>
                                    <option value="">Seleccionar sucursal</option>
                                    @foreach ($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}">{{ $sucursal->sede->nombre }} -
                                            {{ $sucursal->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fecha Ingreso *</label>
                                <input type="date" class="form-control fecha-ingreso" name="fecha_ingreso" required>
                            </div>
                        </div>
                        <!-- Mensaje informativo -->
                        <div id="mensaje-logica" class="alert alert-info d-none mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <span id="mensaje-logica-text"></span>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary" id="btn-volver-carnet">
                                <i class="bi bi-arrow-left"></i> Volver
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Confirmar Registro
                            </button>
                        </div>
                    </form>
                    <!-- Paso 3: Formulario de nueva persona con cargo -->
                    <form id="formNuevaPersona" style="display:none;" class="row g-3">
                        @csrf
                        <!-- Carnet -->
                        <div class="col-md-4">
                            <label class="form-label">Carnet *</label>
                            <input type="text" name="carnet" class="form-control" id="carnet_nuevo">
                            <div id="feedback_carnet_nuevo" class="mt-1" style="font-size:0.875em;"></div>
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
                            <input type="text" name="nombres" class="form-control" id="nombres_nuevo">
                            <div id="feedback_nombres_nuevo" class="text-danger mt-1" style="font-size:0.875em;"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellido Paterno</label>
                            <input type="text" name="apellido_paterno" class="form-control" id="paterno_nuevo">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellido Materno</label>
                            <input type="text" name="apellido_materno" class="form-control" id="materno_nuevo">
                            <div id="feedback_apellidos_nuevo" class="text-danger mt-1" style="font-size:0.875em;"></div>
                        </div>
                        <!-- Contacto -->
                        <div class="col-md-6">
                            <label class="form-label">Correo *</label>
                            <input type="email" name="correo" class="form-control" id="correo_nuevo">
                            <div id="feedback_correo_nuevo" class="mt-1" style="font-size:0.875em;"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="form-control" id="fecha_nac_nuevo">
                            <div id="edad_calculada_nuevo" class="mt-1" style="font-size:0.875em;"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Celular *</label>
                            <input type="text" name="celular" class="form-control" id="celular_nuevo">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                        <!-- DEPARTAMENTO -->
                        <div class="col-md-3">
                            <label class="form-label">Departamento *</label>
                            <select name="departamento_id" id="departamento_nuevo" class="form-select" required>
                                <option value="">Seleccionar</option>
                                @foreach ($departamentos as $d)
                                    <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- CIUDAD -->
                        <div class="col-md-3">
                            <label class="form-label">Ciudad *</label>
                            <select name="ciudade_id" id="ciudad_nuevo" class="form-select" required disabled>
                                <option value="">Primero seleccione un departamento</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Dirección</label>
                            <textarea name="direccion" class="form-control"></textarea>
                        </div>
                        <!-- Cargos -->
                        <div class="col-12 mt-3">
                            <h5 class="border-bottom pb-2">Asignar Cargo</h5>
                            <!-- Único bloque de asignación de cargo -->
                            <div id="cargo-container-nuevo" class="row mb-3 p-2 border rounded bg-light">
                                <!-- En el modal de nuevo cargo, cambia el select de cargos: -->
                                <div class="col-md-4">
                                    <label class="form-label">Cargo *</label>
                                    <select class="form-select cargo-select-nuevo" name="cargo_id" required>
                                        <option value="">Seleccionar cargo</option>
                                        @foreach ($cargos as $cargo)
                                            <option value="{{ $cargo->id }}" data-tipo="{{ $cargo->nombre }}">
                                                {{ $cargo->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4" id="sucursal_container_nueva">
                                    <label class="form-label">Sucursal *</label>
                                    <select class="form-select sucursal-select-nuevo" name="sucursal_id" required>
                                        <option value="">Seleccionar sucursal</option>
                                        @foreach ($sucursales as $sucursal)
                                            <option value="{{ $sucursal->id }}">{{ $sucursal->sede->nombre }} -
                                                {{ $sucursal->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Fecha Ingreso *</label>
                                    <input type="date" class="form-control fecha-ingreso-nuevo" name="fecha_ingreso"
                                        required>
                                </div>
                            </div>
                            <!-- Mensaje informativo -->
                            <div id="mensaje-logica-nuevo" class="alert alert-info d-none mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                <span id="mensaje-logica-text-nuevo"></span>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-3">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" id="btn-volver-carnet2">
                                    <i class="bi bi-arrow-left"></i> Volver
                                </button>
                                <button type="submit" class="btn btn-primary" id="btn-guardar-nueva-persona" disabled>
                                    <i class="bi bi-person-check"></i> Registrar como Trabajador
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar nuevo cargo a trabajador existente -->
    <div class="modal fade" id="modalNuevoCargo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Nuevo Cargo a <span id="nombre-trabajador-cargo"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formNuevoCargo" class="row g-3">
                        @csrf
                        <input type="hidden" name="trabajador_id" id="trabajador_id_cargo">

                        <!-- En el modal de nuevo cargo, cambia el select de cargos: -->
                        <div class="col-md-4">
                            <label class="form-label">Cargo *</label>
                            <select class="form-select" name="cargo_id" id="cargo_id_nuevo" required>
                                <option value="">Seleccionar cargo</option>
                                @foreach ($cargos as $cargo)
                                    <option value="{{ $cargo->id }}" data-tipo="{{ $cargo->nombre }}">
                                        {{ $cargo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4" id="sucursal_container_nuevo_cargo">
                            <label class="form-label">Sucursal *</label>
                            <select class="form-select" name="sucursal_id" id="sucursal_id_nueva" required>
                                <option value="">Primero seleccione un cargo</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha Ingreso *</label>
                            <input type="date" class="form-control" name="fecha_ingreso" id="fecha_ingreso_nueva"
                                required>
                        </div>

                        <div id="mensaje-verificacion-cargo" class="col-12 alert alert-warning d-none"></div>

                        <div class="col-12 text-center mt-3">
                            <button type="submit" class="btn btn-success" id="btn-asignar-cargo">
                                <i class="bi bi-plus-circle"></i> Asignar Nuevo Cargo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Trabajadores -->
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Lista de Trabajadores</h4>
                        <div class="input-group" style="width:250px;">
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="80">Foto</th>
                                    <th>Carnet</th>
                                    <th>Nombre Completo</th>
                                    <th>Cargos y Sucursales</th>
                                    <th width="15%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($trabajadores as $t)
                                    @php
                                        $tieneCargoPrincipal = $t->trabajadores_cargos->contains('principal', 1);
                                    @endphp
                                    <tr class="{{ $tieneCargoPrincipal ? 'table-warning' : '' }}"
                                        @if ($tieneCargoPrincipal) style="background-color: #fff3cd !important;" 
                                        title="Tiene cargo principal" @endif>
                                        <td class="text-center">
                                            @if ($t->persona->fotografia)
                                                <img src="{{ asset($t->persona->fotografia) }}" alt="Foto"
                                                    class="rounded-circle"
                                                    style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #dee2e6;">
                                            @else
                                                @if ($t->persona->sexo == 'Hombre')
                                                    <img src="{{ asset('frontend/assets/img/personal/hombre.png') }}"
                                                        alt="Foto" class="rounded-circle"
                                                        style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #dee2e6;">
                                                @else
                                                    <img src="{{ asset('frontend/assets/img/personal/mujer.png') }}"
                                                        alt="Foto" class="rounded-circle"
                                                        style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #dee2e6;">
                                                @endif
                                            @endif
                                            @if (Auth::guard('web')->user()->can('personas.cambiar.foto'))
                                                <!-- Botón para cambiar foto -->
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary cambiar-foto-btn"
                                                    data-id="{{ $t->persona->id }}"
                                                    data-foto="{{ $t->persona->fotografia }}" data-bs-toggle="modal"
                                                    data-bs-target="#modalFoto">
                                                    <i class="bi bi-camera"></i> Foto
                                                </button>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $t->persona->carnet }}</strong>
                                            @if ($t->persona->expedido)
                                                <br><small class="text-muted">{{ $t->persona->expedido }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $t->persona->apellido_paterno }}
                                                {{ $t->persona->apellido_materno }}</strong><br>
                                            <span class="text-muted">{{ $t->persona->nombres }}</span><br>
                                            <small class="text-info">
                                                <i class="bi bi-envelope"></i> {{ $t->persona->correo }}
                                            </small>
                                            @if ($tieneCargoPrincipal)
                                                <br><span class="badge bg-warning text-dark mt-1">
                                                    <i class="bi bi-star-fill"></i> Cargo Principal
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $cargosAgrupados = $t->trabajadores_cargos->groupBy('cargo_id');
                                            @endphp

                                            @foreach ($cargosAgrupados as $cargoId => $asignaciones)
                                                @php
                                                    $cargo = $asignaciones->first()->cargo;
                                                    $colorEstado = [
                                                        'Vigente' => 'success',
                                                        'No Vigente' => 'secondary',
                                                    ];
                                                @endphp

                                                <div
                                                    class="cargo-group mb-3 p-3 border rounded {{ $asignaciones->contains('principal', 1) ? 'border-warning bg-light-warning' : '' }}">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="mb-0 text-primary">
                                                            <i class="bi bi-briefcase me-1"></i>
                                                            {{ $cargo->nombre }}
                                                            @if ($asignaciones->contains('principal', 1))
                                                                <span class="badge bg-warning text-dark ms-2">
                                                                    <i class="bi bi-star-fill"></i> Principal
                                                                </span>
                                                            @endif
                                                        </h6>
                                                    </div>

                                                    <div class="sucursales-list">
                                                        @foreach ($asignaciones as $asignacion)
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded {{ $asignacion->principal ? 'border-warning' : '' }}">
                                                                <div class="flex-grow-1">
                                                                    {{-- Mostrar información de sucursal o indicar que es gerencial --}}
                                                                    @if ($asignacion->sucursal)
                                                                        <strong>{{ $asignacion->sucursal->nombre }}</strong>
                                                                        @if ($asignacion->principal)
                                                                            <span
                                                                                class="badge bg-warning text-dark ms-1">Principal</span>
                                                                        @endif
                                                                        <br>
                                                                        <small class="text-muted">
                                                                            <i class="bi bi-building me-1"></i>
                                                                            {{ $asignacion->sucursal->sede->nombre }}
                                                                        </small><br>
                                                                    @else
                                                                        <strong class="text-success">Gerencial (Todas las
                                                                            sucursales)</strong>
                                                                        @if ($asignacion->principal)
                                                                            <span
                                                                                class="badge bg-warning text-dark ms-1">Principal</span>
                                                                        @endif
                                                                        <br>
                                                                        <small class="text-muted">
                                                                            <i class="bi bi-building me-1"></i>
                                                                            Todas las sedes
                                                                        </small><br>
                                                                    @endif

                                                                    <small class="text-muted">
                                                                        <i class="bi bi-calendar me-1"></i>
                                                                        Desde:
                                                                        {{ \Carbon\Carbon::parse($asignacion->fecha_ingreso)->format('d/m/Y') }}
                                                                        @if ($asignacion->fecha_termino)
                                                                            - Hasta:
                                                                            {{ \Carbon\Carbon::parse($asignacion->fecha_termino)->format('d/m/Y') }}
                                                                        @endif
                                                                    </small>
                                                                </div>
                                                                <div class="ms-2">
                                                                    <span
                                                                        class="badge bg-{{ $colorEstado[$asignacion->estado] ?? 'secondary' }} mb-2">
                                                                        {{ $asignacion->estado }}
                                                                    </span>
                                                                    <br>
                                                                    @if (Auth::guard('web')->user()->can('trabajadores.cambiar.estado'))
                                                                        <!-- Botón para cambiar estado -->
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-primary change-status-btn"
                                                                            data-id="{{ $asignacion->id }}"
                                                                            data-estado="{{ $asignacion->estado }}"
                                                                            title="Cambiar estado">
                                                                            <i
                                                                                class="bi bi-toggle-{{ $asignacion->estado === 'Vigente' ? 'on' : 'off' }}"></i>
                                                                        </button>
                                                                    @endif
                                                                    @if (Auth::guard('web')->user()->can('trabajadores.cambiar.principal'))
                                                                        <!-- Botón para cambiar principal -->
                                                                        <button type="button"
                                                                            class="btn btn-sm {{ $asignacion->principal ? 'btn-warning' : 'btn-outline-warning' }} change-principal-btn mt-1"
                                                                            data-id="{{ $asignacion->id }}"
                                                                            data-principal="{{ $asignacion->principal }}"
                                                                            data-cargo-id="{{ $cargo->id }}"
                                                                            data-trabajador-id="{{ $t->id }}"
                                                                            title="{{ $asignacion->principal ? 'Quitar como principal' : 'Marcar como principal' }}">
                                                                            <i
                                                                                class="bi bi-star{{ $asignacion->principal ? '-fill' : '' }}"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if ($t->trabajadores_cargos->isEmpty())
                                                <div class="text-center text-muted py-2">
                                                    <i class="bi bi-info-circle"></i> Sin cargos asignados
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($t->persona->usuario)
                                                <button type="button" class="btn btn-success btn-sm mb-1 w-100" disabled>
                                                    <i class="bi bi-check-circle"></i> Tiene Usuario
                                                </button>
                                            @else
                                                @if (Auth::guard('web')->user()->can('usuarios.registrar'))
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm mb-1 w-100 crear-usuario-btn"
                                                        data-id="{{ $t->id }}"
                                                        data-nombre="{{ $t->persona->apellido_paterno }} {{ $t->persona->apellido_materno }}, {{ $t->persona->nombres }}"
                                                        data-carnet="{{ $t->persona->carnet }}" data-bs-toggle="modal"
                                                        data-bs-target="#modalCrearUsuario">
                                                        <i class="bi bi-person-plus"></i> Crear Usuario
                                                    </button>
                                                @endif
                                            @endif

                                            @if (Auth::guard('web')->user()->can('trabajadores.asignar.cargo'))
                                                <button type="button"
                                                    class="btn btn-info btn-sm mb-1 w-100 nuevo-cargo-btn"
                                                    data-id="{{ $t->id }}"
                                                    data-nombre="{{ $t->persona->apellido_paterno }} {{ $t->persona->apellido_materno }}, {{ $t->persona->nombres }}"
                                                    data-bs-toggle="modal" data-bs-target="#modalNuevoCargo">
                                                    <i class="bi bi-plus-circle"></i> Nuevo Cargo
                                                </button>
                                            @endif
                                            @if (Auth::guard('web')->user()->can('trabajadores.eliminar'))
                                                <button type="button" class="btn btn-danger btn-sm w-100 delete-btn"
                                                    data-id="{{ $t->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#modalEliminar">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="alert alert-info mb-0">
                                                <i class="bi bi-info-circle"></i> No hay trabajadores registrados
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        {{ $trabajadores->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Eliminar -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Eliminar Trabajador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEliminarTrabajador">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" id="trabajador_id_eliminar">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> ¿Está seguro de eliminar este trabajador?
                            Se eliminarán todos sus cargos y su registro como trabajador, pero no sus datos personales.
                        </div>
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash"></i> Eliminar definitivamente
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cambiar Estado Cargo -->
    <div class="modal fade" id="modalCambiarEstado" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Estado del Cargo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="mensaje-cambio-estado"></div>
                    <form id="formCambiarEstado">
                        @csrf
                        <input type="hidden" name="id" id="cargo_id_estado">
                        <div class="mb-3">
                            <label class="form-label">Nuevo Estado *</label>
                            <select class="form-select" name="estado" id="nuevo_estado" required>
                                <option value="Vigente">Vigente</option>
                                <option value="No Vigente">No Vigente</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Actualizar Estado
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Subir/Cambiar Fotografía -->
    <div class="modal fade" id="modalFoto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Subir Fotografía</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formFoto" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="persona_id" id="persona_id_foto">

                        <div class="mb-3 text-center">
                            <div id="foto-preview" class="mb-3">
                                <img id="foto-actual" src="" alt="Foto actual" class="rounded-circle"
                                    style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #dee2e6; display: none;">
                            </div>

                            <div class="mb-3">
                                <label for="fotografia" class="form-label">Seleccionar imagen</label>
                                <input type="file" class="form-control" id="fotografia" name="fotografia"
                                    accept="image/*" required>
                                <div class="form-text">
                                    Formatos permitidos: JPG, PNG, JPEG. Tamaño máximo: 2MB
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> La imagen se redimensionará automáticamente a 300x300
                                píxeles.
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload"></i> Subir Fotografía
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cambiar Estado Principal -->
    <div class="modal fade" id="modalCambiarPrincipal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Cargo Principal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="mensaje-cambio-principal"></div>
                    <form id="formCambiarPrincipal">
                        @csrf
                        <input type="hidden" name="id" id="cargo_id_principal">
                        <input type="hidden" name="principal" id="nuevo_principal">
                        <input type="hidden" name="cargo_id" id="cargo_tipo_id">
                        <input type="hidden" name="trabajador_id" id="trabajador_principal_id">

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <span id="texto-confirmacion-principal"></span>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="bi bi-check-circle"></i> Confirmar Cambio
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear Usuario -->
    <div class="modal fade" id="modalCrearUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Cuenta de Usuario para <span id="nombre-trabajador-usuario"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Se creará una cuenta de usuario para este trabajador. Los datos personales se tomarán
                        automáticamente de su registro.
                    </div>

                    <form id="formCrearUsuario" class="row g-3">
                        @csrf
                        <input type="hidden" name="trabajador_id" id="trabajador_id_usuario">

                        <div class="col-md-6">
                            <label class="form-label">Carnet de Identidad</label>
                            <input type="text" class="form-control" id="carnet_trabajador_usuario" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_completo_usuario" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Correo Electrónico *</label>
                            <input type="email" name="email" class="form-control" id="email_usuario" required>
                            <div id="feedback_email_usuario" class="mt-1" style="font-size:0.875em;"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contraseña *</label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" id="password_usuario"
                                    required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div id="feedback_password_usuario" class="mt-1" style="font-size:0.875em;"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirmar Contraseña *</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                id="password_confirmation_usuario" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Rol *</label>
                            <select name="role" class="form-select" id="role_usuario" required>
                                <option value="">Seleccionar rol</option>
                            </select>
                            <div id="mensaje-rol-usuario" class="mt-1 text-muted" style="font-size:0.875em;">
                                Selecciona un rol para definir los permisos del usuario
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Estado *</label>
                            <select name="estado" class="form-select" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo" selected>Inactivo (requiere activación)</option>
                            </select>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Importante:</strong> Comunica las credenciales al trabajador después de crear la
                                cuenta. Se recomienda cambiar la contraseña en el primer inicio de sesión.
                            </div>
                        </div>

                        <div class="col-12 text-center mt-3">
                            <button type="submit" class="btn btn-success" id="btn-crear-usuario">
                                <i class="bi bi-person-check"></i> Crear Cuenta de Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            let debounceTimer;

            // === FUNCIONALIDAD PARA CREAR USUARIOS ===
            let rolesDisponibles = [];

            // Cargar roles disponibles
            function cargarRolesDisponibles() {
                $.get("{{ route('admin.trabajadores.roles-disponibles') }}", function(res) {
                    rolesDisponibles = res.roles;
                    const select = $('#role_usuario');
                    select.empty().append('<option value="">Seleccionar rol</option>');
                    res.roles.forEach(rol => {
                        select.append(
                            `<option value="${rol.name}">${rol.name.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</option>`
                        );
                    });
                }).fail(function() {
                    console.error('Error al cargar roles disponibles');
                });
            }

            // Verificar disponibilidad de email
            function verificarEmailDisponibilidad() {
                const email = $('#email_usuario').val().trim();
                const feedback = $('#feedback_email_usuario');

                feedback.removeClass('text-success text-danger').text('');

                if (!email) {
                    $('#btn-crear-usuario').prop('disabled', true);
                    return;
                }

                if (!/^\S+@\S+\.\S+$/.test(email)) {
                    feedback.addClass('text-danger').text('⚠️ Formato de correo inválido');
                    $('#btn-crear-usuario').prop('disabled', true);
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.post("{{ route('admin.personas.verificar-correo') }}", {
                        _token: "{{ csrf_token() }}",
                        correo: email
                    }, function(res) {
                        if (res.exists) {
                            feedback.addClass('text-danger').text(
                                '⚠️ Este correo ya está registrado');
                            $('#btn-crear-usuario').prop('disabled', true);
                        } else {
                            feedback.addClass('text-success').text('✅ Correo disponible');
                            verificarFormularioCompleto();
                        }
                    }).fail(function(xhr) {
                        const errorMsg = xhr.responseJSON?.msg || 'Error al verificar el correo';
                        feedback.addClass('text-danger').text(`❌ ${errorMsg}`);
                        $('#btn-crear-usuario').prop('disabled', true);
                    });
                }, 300);
            }

            // Validación de contraseña
            function validarContrasenas() {
                const password = $('#password_usuario').val();
                const confirmation = $('#password_confirmation_usuario').val();
                const feedback = $('#feedback_password_usuario');

                feedback.removeClass('text-success text-danger').text('');

                if (password.length > 0 && password.length < 8) {
                    feedback.addClass('text-danger').text('⚠️ La contraseña debe tener al menos 8 caracteres');
                    $('#btn-crear-usuario').prop('disabled', true);
                    return;
                }

                if (password && confirmation && password !== confirmation) {
                    feedback.addClass('text-danger').text('⚠️ Las contraseñas no coinciden');
                    $('#btn-crear-usuario').prop('disabled', true);
                    return;
                }

                if (password && confirmation && password === confirmation && password.length >= 8) {
                    feedback.addClass('text-success').text('✅ Contraseñas coinciden');
                    verificarFormularioCompleto();
                } else {
                    $('#btn-crear-usuario').prop('disabled', true);
                }
            }

            // Mostrar/Ocultar contraseña
            function togglePasswordVisibility() {
                const passwordField = $('#password_usuario');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                $(this).html(type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>');
            }

            // Verificar si el formulario está completo
            function verificarFormularioCompleto() {
                const emailOk = $('#feedback_email_usuario').hasClass('text-success');
                const passwordOk = $('#feedback_password_usuario').hasClass('text-success');
                const role = $('#role_usuario').val();

                const formCompleto = emailOk && passwordOk && role;
                $('#btn-crear-usuario').prop('disabled', !formCompleto);

                // Debug para identificar problemas
                console.log('Estado del formulario:', {
                    emailOk: emailOk,
                    passwordOk: passwordOk,
                    role: role,
                    formCompleto: formCompleto
                });
            }

            // Verificar si ya tiene usuario
            function verificarSiTieneUsuario(trabajadorId) {
                $.post("{{ route('admin.trabajadores.verificar-usuario') }}", {
                    _token: "{{ csrf_token() }}",
                    trabajador_id: trabajadorId
                }, function(res) {
                    if (res.exists) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Usuario existente',
                            html: `Este trabajador ya tiene una cuenta de usuario asociada:<br>
                           <strong>Email:</strong> ${res.usuario.email}<br>
                           <strong>Rol:</strong> ${res.usuario.role}<br>
                           <strong>Estado:</strong> ${res.usuario.estado}`,
                            confirmButtonText: 'Aceptar'
                        });
                        $('#modalCrearUsuario').modal('hide');
                    }
                }).fail(function(xhr) {
                    const errorMsg = xhr.responseJSON?.msg || 'Error al verificar usuario existente';
                    console.error(errorMsg);
                });
            }

            // === EVENTOS PARA CREAR USUARIO ===
            $('#email_usuario').on('input', verificarEmailDisponibilidad);

            $('#password_usuario, #password_confirmation_usuario').on('input', validarContrasenas);

            $('#togglePassword').click(togglePasswordVisibility);

            $('#role_usuario').on('change', verificarFormularioCompleto);

            $(document).on('click', '.crear-usuario-btn', function() {
                const trabajadorId = $(this).data('id');
                const nombreTrabajador = $(this).data('nombre');
                const carnet = $(this).data('carnet');

                $('#trabajador_id_usuario').val(trabajadorId);
                $('#nombre-trabajador-usuario').text(nombreTrabajador);
                $('#carnet_trabajador_usuario').val(carnet);
                $('#nombre_completo_usuario').val(nombreTrabajador);

                // Limpiar campos del formulario
                $('#email_usuario, #password_usuario, #password_confirmation_usuario').val('');
                $('#feedback_email_usuario, #feedback_password_usuario').removeClass(
                    'text-success text-danger').text('');
                $('#btn-crear-usuario').prop('disabled', true);

                // Verificar si ya tiene usuario
                verificarSiTieneUsuario(trabajadorId);
            });

            // Cargar roles al abrir el modal
            $('#modalCrearUsuario').on('shown.bs.modal', function() {
                cargarRolesDisponibles();
            });

            // Enviar formulario de creación de usuario
            $('#formCrearUsuario').submit(function(e) {
                e.preventDefault();
                // Validaciones adicionales
                const password = $('#password_usuario').val();
                const role = $('#role_usuario').val();

                if (password.length < 8) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Contraseña inválida',
                        text: 'La contraseña debe tener al menos 8 caracteres',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }

                if (!role) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Rol no seleccionado',
                        text: 'Por favor, seleccione un rol para el usuario',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Confirmar creación de usuario',
                    html: `¿Está seguro de crear una cuenta de usuario para:<br>
                   <strong>${$('#nombre-trabajador-usuario').text()}</strong><br>
                   con el rol de <strong>${role.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</strong>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, crear usuario',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.trabajadores.registrar-usuario') }}",
                            type: "POST",
                            data: $(this).serialize(),
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Éxito!',
                                        html: `Usuario creado correctamente:<br>
                                       <strong>Email:</strong> ${res.usuario.email}<br>
                                       <strong>Rol:</strong> ${res.usuario.role}<br>
                                       <strong>Estado:</strong> ${res.usuario.estado}<br><br>
                                       <div class="alert alert-warning">
                                           <i class="bi bi-exclamation-triangle"></i>
                                           <strong>Importante:</strong> Comunica las credenciales al trabajador. 
                                           La contraseña inicial es la que ingresaste en el formulario.
                                       </div>`,
                                        confirmButtonText: 'Aceptar'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: res.msg ||
                                            'Error al crear el usuario',
                                        confirmButtonText: 'Aceptar'
                                    });
                                }
                            },
                            error: function(xhr) {
                                const errorMsg = xhr.responseJSON?.msg ||
                                    xhr.responseJSON?.errors?.email?.[0] ||
                                    'Error al crear el usuario';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMsg,
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        });
                    }
                });
            });

            // === RESTO DEL CÓDIGO EXISTENTE (CONSTANTES, FUNCIONES Y EVENTOS) ===
            // [MANTÉN TODO EL CÓDIGO EXISTENTE DESDE LAS CONSTANTES HASTA EL FINAL]
            // === CONSTANTES Y CATÁLOGOS ===
            const CARGO_ENCARGADO_ACADEMICO = 'Encargado Académico';
            const CARGO_EJECUTIVO_MARKETING = 'Ejecutivo de Marketing';
            const CARGO_EJECUTIVO_CONTABLE = 'Ejecutivo Contable';
            const CARGO_ASESOR_MARKETING = 'Asesor de Marketing';
            const CARGO_GERENCIALES = ['Director Académico', 'Gerente de Marketing',
                'Director Financiera Contable'
            ];
            const ciudadesConDepartamento = @json($ciudades->map(fn($c) => ['id' => $c->id, 'nombre' => $c->nombre, 'departamento_id' => $c->departamento_id]));
            const cargos = @json($cargos->pluck('nombre', 'id'));
            const sucursales = @json($sucursales->map(fn($s) => ['id' => $s->id, 'nombre' => $s->nombre, 'sede' => $s->sede->nombre]));
            // Cargar nombres de sucursales para mensajes
            const sucursalesData = @json(
                $sucursales->mapWithKeys(function ($s) {
                        return [$s->id => $s->sede->nombre . ' - ' . $s->nombre];
                    })->toArray());
            // === FUNCIONES PRINCIPALES ===
            /**
             * Llena el select de ciudades basado en el departamento seleccionado
             */
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
            /**
             * Valida que al menos un apellido esté presente
             */
            function validarApellidosNuevo() {
                const p = $('#paterno_nuevo').val().trim();
                const m = $('#materno_nuevo').val().trim();
                if (!p && !m) {
                    $('#feedback_apellidos_nuevo').text('Debe ingresar al menos un apellido.');
                    return false;
                } else {
                    $('#feedback_apellidos_nuevo').text('');
                    return true;
                }
            }
            /**
             * Calcula la edad y valida que sea mayor de 18 años
             */
            function calcularEdadNuevo() {
                const fecha = $('#fecha_nac_nuevo').val();
                if (!fecha) {
                    $('#edad_calculada_nuevo').text('');
                    return true;
                }
                const hoy = new Date();
                const nac = new Date(fecha);
                let edad = hoy.getFullYear() - nac.getFullYear();
                const mes = hoy.getMonth() - nac.getMonth();
                if (mes < 0 || (mes === 0 && hoy.getDate() < nac.getDate())) edad--;
                if (edad < 18) {
                    $('#edad_calculada_nuevo').addClass('text-danger').text('⚠️ Debe tener al menos 18 años.');
                    return false;
                } else {
                    $('#edad_calculada_nuevo').removeClass('text-danger').text(`Edad: ${edad} años`);
                    return true;
                }
            }
            /**
             * Verifica si el formulario de nueva persona está completo y válido
             */
            function checkFormNuevaPersona() {
                const carnetOk = $('#feedback_carnet_nuevo').hasClass('text-success');
                const correoOk = $('#feedback_correo_nuevo').hasClass('text-success');
                const nombres = $('#nombres_nuevo').val().trim();
                const celular = $('#celular_nuevo').val().trim();
                const ciudade = $('#ciudad_nuevo').val();
                const sexo = $('select[name="sexo"]').val();
                const ecivil = $('select[name="estado_civil"]').val();
                const apellidosOk = validarApellidosNuevo();
                const edadOk = !$('#fecha_nac_nuevo').val() || calcularEdadNuevo();
                const enabled = carnetOk && correoOk && nombres && celular && ciudade && sexo && ecivil &&
                    apellidosOk && edadOk;
                $('#btn-guardar-nueva-persona').prop('disabled', !enabled);
            }
            /**
             * Muestra u oculta el campo de sucursal según el tipo de cargo
             */
            function toggleSucursalSelect(cargoNombre, isNuevo = false) {
                let sucursalContainer, sucursalSelect;
                if (isNuevo) {
                    sucursalContainer = $('#sucursal_container_nueva');
                    sucursalSelect = $('#sucursal_id_nueva');
                } else {
                    sucursalContainer = $('#sucursal_container_nuevo');
                    sucursalSelect = $('.sucursal-select');
                }
                if (CARGO_GERENCIALES.includes(cargoNombre)) {
                    sucursalContainer.hide();
                    sucursalSelect.prop('required', false);
                    sucursalSelect.val(''); // Limpiar selección
                } else {
                    sucursalContainer.show();
                    sucursalSelect.prop('required', true);
                }
            }
            /**
             * Muestra u oculta el campo de sucursal en el modal de nuevo cargo
             */
            function toggleSucursalNuevoCargo(cargoNombre) {
                const sucursalContainer = $('#sucursal_container_nuevo_cargo') || $('#sucursal_id_nueva').closest(
                    '.col-md-4');
                const sucursalSelect = $('#sucursal_id_nueva');
                if (CARGO_GERENCIALES.includes(cargoNombre)) {
                    sucursalContainer.hide();
                    sucursalSelect.prop('required', false);
                    sucursalSelect.val(''); // Limpiar selección
                    // Mostrar mensaje informativo
                    $('#mensaje-verificacion-cargo')
                        .removeClass('d-none alert-danger alert-warning')
                        .addClass('alert-info')
                        .html(
                            `<i class="bi bi-info-circle"></i> Los cargos gerenciales no requieren asignación de sucursal específica.`
                        );
                } else {
                    sucursalContainer.show();
                    sucursalSelect.prop('required', true);
                    // Limpiar mensaje
                    $('#mensaje-verificacion-cargo').addClass('d-none').html('');
                }
            }
            // === EVENTOS PRINCIPALES ===
            // Cambio de departamento para ciudades
            $('#departamento_nuevo').on('change', function() {
                const deptoId = $(this).val();
                llenarCiudadesPorDepartamento(deptoId, $('#ciudad_nuevo'));
                $('#ciudad_nuevo').val('');
                checkFormNuevaPersona();
            });
            // Cambio en los campos de validación para nueva persona
            $('#nombres_nuevo, #paterno_nuevo, #materno_nuevo, #celular_nuevo, #fecha_nac_nuevo, #carnet_nuevo, #correo_nuevo')
                .on('input', checkFormNuevaPersona);
            $('select[name="sexo"], select[name="estado_civil"], #ciudad_nuevo').on('change',
                checkFormNuevaPersona);
            // Evento para cambio de cargo en todos los formularios
            $(document).on('change', '.cargo-select, .cargo-select-nuevo, #cargo_id_nuevo', function() {
                const cargoSelect = $(this);
                const cargoNombre = cargoSelect.find('option:selected').data('tipo');
                const isNuevo = cargoSelect.hasClass('cargo-select-nuevo');
                // Determinar qué formulario se está usando
                if (cargoSelect.attr('id') === 'cargo_id_nuevo') {
                    // Es el modal de nuevo cargo
                    toggleSucursalNuevoCargo(cargoNombre);
                } else {
                    // Es el formulario principal o de nueva persona
                    toggleSucursalSelect(cargoNombre, isNuevo);
                }
                // Actualizar mensajes informativos
                actualizarMensajeLogica(cargoNombre, isNuevo, cargoSelect.attr('id') === 'cargo_id_nuevo');
            });
            /**
             * Actualiza el mensaje informativo según el cargo seleccionado
             */
            function actualizarMensajeLogica(cargoNombre, isNuevo, isModalNuevoCargo = false) {
                let mensajeContainer, mensajeText;
                if (isModalNuevoCargo) {
                    mensajeContainer = $('#mensaje-verificacion-cargo');
                    if (CARGO_GERENCIALES.includes(cargoNombre)) {
                        mensajeContainer.removeClass('d-none alert-danger alert-warning')
                            .addClass('alert-info')
                            .html(
                                `<i class="bi bi-info-circle"></i> Los cargos gerenciales no requieren asignación de sucursal específica.`
                            );
                    } else {
                        mensajeContainer.addClass('d-none').html('');
                    }
                    return;
                }
                if (isNuevo) {
                    mensajeContainer = $('#mensaje-logica-nuevo');
                    mensajeText = $('#mensaje-logica-text-nuevo');
                } else {
                    mensajeContainer = $('#mensaje-logica');
                    mensajeText = $('#mensaje-logica-text');
                }
                if (cargoNombre) {
                    mensajeContainer.removeClass('d-none');
                    if (CARGO_GERENCIALES.includes(cargoNombre)) {
                        mensajeText.html(`
                    Se registrará como <strong>${cargoNombre}</strong> sin sucursal específica 
                    (acceso a todas las sucursales de su área).
                `);
                    } else if (cargoNombre === CARGO_ENCARGADO_ACADEMICO || cargoNombre ===
                        CARGO_EJECUTIVO_CONTABLE) {
                        mensajeText.html(`
                    Se registrará <strong>únicamente</strong> como <strong>${cargoNombre}</strong> 
                    en la sucursal seleccionada con cargo principal.
                `);
                    } else if (cargoNombre === CARGO_EJECUTIVO_MARKETING) {
                        mensajeText.html(`
                    Se registrará como <strong>${CARGO_EJECUTIVO_MARKETING}</strong> en la sucursal seleccionada (principal), 
                    y automáticamente como <strong>${CARGO_ASESOR_MARKETING}</strong> en las demás sucursales.
                `);
                    } else if (cargoNombre === CARGO_ASESOR_MARKETING) {
                        mensajeText.html(`
                    Se registrará como <strong>${CARGO_ASESOR_MARKETING}</strong> en la sucursal seleccionada (principal), 
                    y automáticamente como <strong>${CARGO_ASESOR_MARKETING}</strong> en las demás sucursales.
                `);
                    }
                } else {
                    mensajeContainer.addClass('d-none');
                }
            }
            // === VERIFICACIÓN DE CARNET ===
            $('#carnet_trabajador').on('input', function() {
                const carnet = $(this).val().trim();
                $('#mensaje-verificacion').html('');
                $('#formRegistrarTrabajador').hide();
                $('#btn-registrar-nueva').prop('disabled', true);
                if (!carnet) {
                    return;
                }
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.post("{{ route('admin.trabajadores.verificar-carnet') }}", {
                        _token: "{{ csrf_token() }}",
                        carnet: carnet
                    }, function(res) {
                        if (res.is_worker) {
                            $('#mensaje-verificacion').html(
                                `<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> ${res.message}</div>`
                            );
                        } else if (res.exists) {
                            $('#mensaje-verificacion').html(
                                `<div class="alert alert-info"><i class="bi bi-person"></i> ${res.message}<br><strong>${res.persona.nombre_completo}</strong></div>`
                            );
                            $('#persona_id_input').val(res.persona.id);
                            $('#nombre_persona').text(res.persona.nombre_completo);
                            $('.fecha-ingreso').val(new Date().toISOString().split('T')[0]);
                            $('#formRegistrarTrabajador').show();
                        } else {
                            $('#mensaje-verificacion').html(
                                `<div class="alert alert-danger"><i class="bi bi-x-circle"></i> ${res.message}</div>`
                            );
                            $('#btn-registrar-nueva').prop('disabled', false);
                        }
                    }).fail(function() {
                        $('#mensaje-verificacion').html(
                            `<div class="alert alert-danger"><i class="bi bi-x-circle"></i> Error al verificar el carnet.</div>`
                        );
                        $('#btn-registrar-nueva').prop('disabled', true);
                    });
                }, 400);
            });
            // === BOTONES DE NAVEGACIÓN ===
            $('#btn-registrar-nueva').on('click', function() {
                $('#paso-carnet, #formRegistrarTrabajador').hide();
                $('#formNuevaPersona').show();
                $('.fecha-ingreso-nuevo').val(new Date().toISOString().split('T')[0]);
            });
            $('#btn-volver-carnet, #btn-volver-carnet2').on('click', function() {
                $('#formRegistrarTrabajador, #formNuevaPersona').hide();
                $('#paso-carnet').show();
                $('#carnet_trabajador').val('');
                $('#mensaje-verificacion').html('');
            });
            // === FORMULARIO DE TRABAJADOR EXISTENTE ===
            $('#formRegistrarTrabajador').submit(function(e) {
                e.preventDefault();
                const cargoId = $('.cargo-select').val();
                const cargoNombre = $('.cargo-select option:selected').data('tipo');
                const sucursalId = !CARGO_GERENCIALES.includes(cargoNombre) ? $('.sucursal-select').val() :
                    null;
                const fechaIngreso = $('.fecha-ingreso').val();
                // Validaciones
                if (!cargoId || !fechaIngreso) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor, complete todos los campos requeridos.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }
                if (!CARGO_GERENCIALES.includes(cargoNombre) && !sucursalId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor, seleccione una sucursal.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }
                // Validar fecha
                const fechaActual = new Date();
                const fechaIngresoDate = new Date(fechaIngreso);
                if (fechaIngresoDate > fechaActual) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fecha inválida',
                        text: 'La fecha de ingreso no puede ser posterior a la fecha actual.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }
                // Confirmar con el usuario
                let confirmMessage = '';
                if (CARGO_GERENCIALES.includes(cargoNombre)) {
                    confirmMessage =
                        `Se registrará como <strong>${cargoNombre}</strong> sin sucursal específica (acceso a todas las sucursales).`;
                } else if (cargoNombre === CARGO_ENCARGADO_ACADEMICO || cargoNombre ===
                    CARGO_EJECUTIVO_CONTABLE) {
                    confirmMessage =
                        `Se registrará únicamente como ${cargoNombre} en la sucursal seleccionada.`;
                } else if (cargoNombre === CARGO_EJECUTIVO_MARKETING) {
                    confirmMessage =
                        `Se registrará como ${CARGO_EJECUTIVO_MARKETING} en la sucursal seleccionada (principal), y automáticamente como ${CARGO_ASESOR_MARKETING} en las demás sucursales.`;
                } else if (cargoNombre === CARGO_ASESOR_MARKETING) {
                    confirmMessage =
                        `Se registrará como ${CARGO_ASESOR_MARKETING} en la sucursal seleccionada (principal), y automáticamente como ${CARGO_ASESOR_MARKETING} en las demás sucursales.`;
                }
                Swal.fire({
                    title: 'Confirmar registro',
                    html: confirmMessage,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, registrar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.trabajadores.registrar') }}",
                            type: "POST",
                            data: $(this).serialize(),
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Éxito!',
                                        text: res.msg,
                                        confirmButtonText: 'Aceptar'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: res.msg ||
                                            'Error al registrar el trabajador',
                                        confirmButtonText: 'Aceptar'
                                    });
                                }
                            },
                            error: function(xhr) {
                                const msg = xhr.responseJSON?.msg ||
                                    'Error al registrar. Verifique los datos.';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: msg,
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        });
                    }
                });
            });
            // === FORMULARIO DE NUEVA PERSONA ===
            $('#formNuevaPersona').submit(function(e) {
                e.preventDefault();
                // Validación de apellidos y edad
                if (!validarApellidosNuevo()) return;
                if ($('#fecha_nac_nuevo').val() && !calcularEdadNuevo()) return;
                const cargoId = $('.cargo-select-nuevo').val();
                const cargoNombre = $('.cargo-select-nuevo option:selected').data('tipo');
                const sucursalId = !CARGO_GERENCIALES.includes(cargoNombre) ? $('.sucursal-select-nuevo')
                    .val() : null;
                const fechaIngreso = $('.fecha-ingreso-nuevo').val();
                // Validaciones
                if (!cargoId || !fechaIngreso) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor, complete todos los campos requeridos.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }
                if (!CARGO_GERENCIALES.includes(cargoNombre) && !sucursalId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor, seleccione una sucursal.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }
                // Validar fecha
                const fechaActual = new Date();
                const fechaIngresoDate = new Date(fechaIngreso);
                if (fechaIngresoDate > fechaActual) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fecha inválida',
                        text: 'La fecha de ingreso no puede ser posterior a la fecha actual.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }
                // Confirmar con el usuario
                let confirmMessage = '';
                if (CARGO_GERENCIALES.includes(cargoNombre)) {
                    confirmMessage =
                        `Se registrará como <strong>${cargoNombre}</strong> sin sucursal específica (acceso a todas las sucursales).`;
                } else if (cargoNombre === CARGO_ENCARGADO_ACADEMICO || cargoNombre ===
                    CARGO_EJECUTIVO_CONTABLE) {
                    confirmMessage =
                        `Se registrará únicamente como ${cargoNombre} en la sucursal seleccionada.`;
                } else if (cargoNombre === CARGO_EJECUTIVO_MARKETING) {
                    confirmMessage =
                        `Se registrará como ${CARGO_EJECUTIVO_MARKETING} en la sucursal seleccionada (principal), y automáticamente como ${CARGO_ASESOR_MARKETING} en las demás sucursales.`;
                } else if (cargoNombre === CARGO_ASESOR_MARKETING) {
                    confirmMessage =
                        `Se registrará como ${CARGO_ASESOR_MARKETING} en la sucursal seleccionada (principal), y automáticamente como ${CARGO_ASESOR_MARKETING} en las demás sucursales.`;
                }
                Swal.fire({
                    title: 'Confirmar registro',
                    html: confirmMessage,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, registrar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.trabajadores.registrar-persona-trabajador') }}",
                            type: "POST",
                            data: $(this).serialize(),
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Éxito!',
                                        text: res.msg,
                                        confirmButtonText: 'Aceptar'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: res.msg ||
                                            'Error al registrar el trabajador',
                                        confirmButtonText: 'Aceptar'
                                    });
                                }
                            },
                            error: function(xhr) {
                                const errors = xhr.responseJSON?.errors || {};
                                if (errors.carnet) $('#feedback_carnet_nuevo').addClass(
                                    'text-danger').text(errors.carnet[0]);
                                if (errors.correo) $('#feedback_correo_nuevo').addClass(
                                    'text-danger').text(errors.correo[0]);
                                if (errors.apellidos) $('#feedback_apellidos_nuevo')
                                    .text(errors.apellidos[0]);
                                checkFormNuevaPersona();
                                const msg = xhr.responseJSON?.msg ||
                                    'Error al registrar. Verifique los datos.';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: msg,
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        });
                    }
                });
            });
            // === BÚSQUEDA ===
            $('#searchInput').on('input', function() {
                const search = $(this).val().trim();
                window.location.href = "{{ route('admin.trabajadores.listar') }}?search=" +
                    encodeURIComponent(search);
            });
            // === NUEVO CARGO A TRABAJADOR EXISTENTE ===
            $(document).on('click', '.nuevo-cargo-btn', function() {
                const trabajadorId = $(this).data('id');
                const nombreTrabajador = $(this).data('nombre');
                $('#trabajador_id_cargo').val(trabajadorId);
                $('#nombre-trabajador-cargo').text(nombreTrabajador);
                // Resetear los selects
                $('#cargo_id_nuevo').val('').prop('disabled', false);
                $('#sucursal_id_nueva').prop('disabled', true).html(
                    '<option value="">Primero seleccione un cargo</option>');
                $('#mensaje-verificacion-cargo').addClass('d-none').html('');
                $('#fecha_ingreso_nueva').val(new Date().toISOString().split('T')[0]);
            });
            // Evento para cuando se cambia el cargo en el modal de nuevo cargo
            $(document).on('change', '#cargo_id_nuevo', function() {
                const cargoId = $(this).val();
                const trabajadorId = $('#trabajador_id_cargo').val();
                const cargoNombre = $(this).find('option:selected').data('tipo');
                if (!cargoId || !trabajadorId) {
                    $('#sucursal_id_nueva').prop('disabled', true).html(
                        '<option value="">Seleccione un cargo primero</option>');
                    return;
                }
                // Mostrar/ocultar campo de sucursal
                toggleSucursalNuevoCargo(cargoNombre);
                // Si es cargo gerencial, no cargar sucursales
                if (CARGO_GERENCIALES.includes(cargoNombre)) {
                    $('#sucursal_id_nueva').prop('disabled', true).html(
                        '<option value="">No aplica para cargos gerenciales</option>');
                    return;
                }
                // Mostrar mensaje informativo
                const mensajeContainer = $('#mensaje-verificacion-cargo');
                mensajeContainer.removeClass('d-none alert-danger alert-warning').addClass('alert-info');
                mensajeContainer.html(
                    `<i class="bi bi-info-circle"></i> Cargando sucursales disponibles...`);
                // Obtener sucursales disponibles para este cargo y trabajador
                $.get("{{ route('admin.trabajadores.sucursales-disponibles') }}", {
                    trabajador_id: trabajadorId,
                    cargo_id: cargoId,
                    _token: "{{ csrf_token() }}"
                }, function(res) {
                    if (res.sucursales.length === 0) {
                        $('#sucursal_id_nueva').prop('disabled', true).html(
                            '<option value="">No hay sucursales disponibles</option>');
                        mensajeContainer.removeClass('alert-info').addClass('alert-warning');
                        mensajeContainer.html(
                            `<i class="bi bi-exclamation-triangle"></i> No hay sucursales disponibles. El trabajador ya tiene el cargo <strong>${cargoNombre}</strong> asignado en todas las sucursales.`
                        );
                    } else {
                        let options = '<option value="">Seleccionar sucursal</option>';
                        res.sucursales.forEach(s => {
                            options +=
                                `<option value="${s.id}">${s.sede.nombre} - ${s.nombre}</option>`;
                        });
                        $('#sucursal_id_nueva').prop('disabled', false).html(options);
                        mensajeContainer.removeClass('alert-warning alert-danger').addClass(
                            'alert-info');
                        mensajeContainer.html(
                            `<i class="bi bi-info-circle"></i> Se mostrarán las sucursales donde el trabajador no tenga el cargo <strong>${cargoNombre}</strong> asignado.`
                        );
                    }
                }).fail(function(xhr, status, error) {
                    $('#sucursal_id_nueva').prop('disabled', true).html(
                        '<option value="">Error al cargar sucursales</option>');
                    mensajeContainer.removeClass('alert-info').addClass('alert-danger');
                    mensajeContainer.html(
                        '<i class="bi bi-exclamation-triangle"></i> Error al cargar las sucursales disponibles.'
                    );
                });
            });
            // Asignar nuevo cargo
            $('#formNuevoCargo').submit(function(e) {
                e.preventDefault();
                const cargoId = $('#cargo_id_nuevo').val();
                const cargoNombre = $('#cargo_id_nuevo option:selected').data('tipo');
                const sucursalId = !CARGO_GERENCIALES.includes(cargoNombre) ? $('#sucursal_id_nueva')
                    .val() : null;
                const fechaIngreso = $('#fecha_ingreso_nueva').val();
                // Validaciones
                if (!cargoId || !fechaIngreso) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor, complete todos los campos requeridos.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }
                if (!CARGO_GERENCIALES.includes(cargoNombre) && !sucursalId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor, seleccione una sucursal.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }
                // Validar fecha
                const fechaActual = new Date();
                const fechaIngresoDate = new Date(fechaIngreso);
                if (fechaIngresoDate > fechaActual) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fecha inválida',
                        text: 'La fecha de ingreso no puede ser posterior a la fecha actual.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }
                // Confirmar asignación
                let confirmMessage = '';
                if (CARGO_GERENCIALES.includes(cargoNombre)) {
                    confirmMessage =
                        `Se asignará como <strong>${cargoNombre}</strong> sin sucursal específica (acceso a todas las sucursales).`;
                } else {
                    confirmMessage =
                        `Se asignará como <strong>${cargoNombre}</strong> en la sucursal seleccionada.`;
                }
                Swal.fire({
                    title: 'Confirmar asignación de nuevo cargo',
                    html: confirmMessage,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, asignar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.trabajadores.asignar-nuevo-cargo') }}",
                            type: "POST",
                            data: $(this).serialize(),
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Éxito!',
                                        text: res.msg,
                                        confirmButtonText: 'Aceptar'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: res.msg,
                                        confirmButtonText: 'Aceptar'
                                    });
                                }
                            },
                            error: function(xhr) {
                                const msg = xhr.responseJSON?.msg ||
                                    'Error al asignar el nuevo cargo.';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: msg,
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        });
                    }
                });
            });
            // === CAMBIAR ESTADO DE CARGO ===
            $(document).on('click', '.change-status-btn', function() {
                const cargoId = $(this).data('id');
                const estadoActual = $(this).data('estado');
                $('#cargo_id_estado').val(cargoId);
                $('#nuevo_estado').val(estadoActual === 'Vigente' ? 'No Vigente' : 'Vigente');
                if (estadoActual === 'Vigente') {
                    $('#mensaje-cambio-estado').html(
                        '<div class="alert alert-warning">' +
                        '<i class="bi bi-exclamation-triangle"></i> Al cambiar a "No Vigente", se registrará la fecha de término actual.' +
                        '</div>'
                    );
                } else {
                    $('#mensaje-cambio-estado').html(
                        '<div class="alert alert-info">' +
                        '<i class="bi bi-info-circle"></i> Al cambiar a "Vigente", se eliminará la fecha de término.' +
                        '</div>'
                    );
                }
                $('#modalCambiarEstado').modal('show');
            });
            $('#formCambiarEstado').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.trabajadores.cargo.actualizar-estado') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: res.msg,
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: res.msg,
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON?.msg ||
                            'Error al actualizar el estado del cargo.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg,
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });
            // === SUBIR/CAMBIAR FOTOGRAFÍA ===
            $(document).on('click', '.cambiar-foto-btn', function() {
                const personaId = $(this).data('id');
                const fotoActual = $(this).data('foto');
                $('#persona_id_foto').val(personaId);
                if (fotoActual) {
                    $('#foto-actual').attr('src', fotoActual).show();
                } else {
                    $('#foto-actual').hide();
                }
                $('#fotografia').val('');
            });
            $('#fotografia').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#foto-actual').attr('src', e.target.result).show();
                    }
                    reader.readAsDataURL(file);
                }
            });
            $('#formFoto').submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                Swal.fire({
                    title: 'Subiendo fotografía...',
                    text: 'Por favor espere',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    url: "{{ route('admin.trabajadores.subir-foto') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        Swal.close();
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: res.msg,
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                $('#modalFoto').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: res.msg,
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        const msg = xhr.responseJSON?.msg || 'Error al subir la fotografía.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg,
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });
            // === CAMBIAR ESTADO PRINCIPAL DE CARGO ===
            $(document).on('click', '.change-principal-btn', function() {
                const cargoAsignacionId = $(this).data('id');
                const principalActual = $(this).data('principal');
                const cargoId = $(this).data('cargo-id');
                const trabajadorId = $(this).data('trabajador-id');
                const nuevoPrincipal = principalActual == 1 ? 0 : 1;
                $('#cargo_id_principal').val(cargoAsignacionId);
                $('#nuevo_principal').val(nuevoPrincipal);
                $('#cargo_tipo_id').val(cargoId);
                $('#trabajador_principal_id').val(trabajadorId);
                if (nuevoPrincipal == 1) {
                    $('#texto-confirmacion-principal').text(
                        '¿Está seguro de marcar este cargo como principal? ' +
                        'Esto quitará el estado principal de cualquier otro cargo del mismo tipo.'
                    );
                } else {
                    $('#texto-confirmacion-principal').text(
                        '¿Está seguro de quitar este cargo como principal?');
                }
                $('#modalCambiarPrincipal').modal('show');
            });
            $('#formCambiarPrincipal').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.trabajadores.cargo.actualizar-principal') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: res.msg,
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: res.msg,
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON?.msg ||
                            'Error al actualizar el estado principal del cargo.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg,
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });
            // === ELIMINAR TRABAJADOR ===
            $(document).on('click', '.delete-btn', function() {
                $('#trabajador_id_eliminar').val($(this).data('id'));
            });
            $('#formEliminarTrabajador').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.trabajadores.eliminar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Eliminado!',
                                text: res.msg,
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: res.msg || 'Error al eliminar el trabajador',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al eliminar el trabajador.',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });
            // === INICIALIZAR FECHAS Y ESTADO INICIAL ===
            $('.fecha-ingreso, .fecha-ingreso-nuevo, #fecha_ingreso_nueva').val(new Date().toISOString().split('T')[
                0]);
            // Inicializar estado de los formularios
            $('.cargo-select').trigger('change');
            $('.cargo-select-nuevo').trigger('change');
            $('#cargo_id_nuevo').trigger('change');

            // Cargar roles disponibles al inicio
            cargarRolesDisponibles();

            // Debug inicial para verificar elementos
            console.log('Elementos del formulario de usuario:', {
                email: $('#email_usuario').length,
                password: $('#password_usuario').length,
                rol: $('#role_usuario').length,
                boton: $('#btn-crear-usuario').length
            });
        });
    </script>
@endpush
