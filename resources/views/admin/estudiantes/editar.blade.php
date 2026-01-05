@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-dark">Editar Estudiante</h4>
                        <p class="text-muted mb-0">Modifica la información del estudiante</p>
                    </div>
                    <div class="page-title-right">
                        <a href="{{ route('admin.estudiantes.listar') }}" class="btn btn-secondary btn-lg">
                            <i class="ri-arrow-left-line align-bottom me-1"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Edición -->
        <div class="row">
            <div class="col-12">
                <div class="card border border-light shadow-sm">
                    <div class="card-body p-4">
                        <form id="formEditarEstudiante" class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="id" value="{{ $estudiante->id }}">

                            <div class="row g-3">
                                <!-- Datos Personales -->
                                <div class="col-lg-12 mb-3">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="ri-user-3-line me-2"></i>Datos Personales
                                    </h6>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Carnet <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="carnet" class="form-control form-control-lg"
                                            value="{{ $estudiante->persona->carnet ?? '' }}" readonly>
                                        <small class="text-muted">El carnet no se puede modificar</small>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Expedido</label>
                                        <select name="expedido" class="form-select form-select-lg">
                                            <option value="">Seleccionar</option>
                                            @foreach (['Lp', 'Or', 'Pt', 'Cb', 'Ch', 'Tj', 'Be', 'Sc', 'Pn'] as $exp)
                                                <option value="{{ $exp }}"
                                                    {{ ($estudiante->persona->expedido ?? '') == $exp ? 'selected' : '' }}>
                                                    {{ $exp }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Nombres <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="nombres" class="form-control form-control-lg"
                                            value="{{ $estudiante->persona->nombres ?? '' }}" required>
                                        <div class="invalid-feedback">Por favor ingresa los nombres</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Apellido Paterno</label>
                                        <input type="text" name="apellido_paterno" class="form-control form-control-lg"
                                            value="{{ $estudiante->persona->apellido_paterno ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Apellido Materno</label>
                                        <input type="text" name="apellido_materno" class="form-control form-control-lg"
                                            value="{{ $estudiante->persona->apellido_materno ?? '' }}">
                                        <small id="feedback_apellidos" class="form-text text-danger mt-1"></small>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Sexo <span class="text-danger">*</span></label>
                                        <select name="sexo" class="form-select form-select-lg" required>
                                            <option value="">Seleccionar</option>
                                            <option value="Hombre"
                                                {{ ($estudiante->persona->sexo ?? '') == 'Hombre' ? 'selected' : '' }}>
                                                Hombre</option>
                                            <option value="Mujer"
                                                {{ ($estudiante->persona->sexo ?? '') == 'Mujer' ? 'selected' : '' }}>Mujer
                                            </option>
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
                                            <option value="Soltero(a)"
                                                {{ ($estudiante->persona->estado_civil ?? '') == 'Soltero(a)' ? 'selected' : '' }}>
                                                Soltero(a)</option>
                                            <option value="Casado(a)"
                                                {{ ($estudiante->persona->estado_civil ?? '') == 'Casado(a)' ? 'selected' : '' }}>
                                                Casado(a)</option>
                                            <option value="Divorciado(a)"
                                                {{ ($estudiante->persona->estado_civil ?? '') == 'Divorciado(a)' ? 'selected' : '' }}>
                                                Divorciado(a)</option>
                                            <option value="Viudo(a)"
                                                {{ ($estudiante->persona->estado_civil ?? '') == 'Viudo(a)' ? 'selected' : '' }}>
                                                Viudo(a)</option>
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
                                            value="{{ $estudiante->persona->correo ?? '' }}" required>
                                        <div class="invalid-feedback">Por favor ingresa un correo válido</div>
                                        <small id="feedback_correo" class="form-text mt-1"></small>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Celular <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="celular" class="form-control form-control-lg"
                                            value="{{ $estudiante->persona->celular ?? '' }}" required>
                                        <div class="invalid-feedback">Por favor ingresa el celular</div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Teléfono</label>
                                        <input type="text" name="telefono" class="form-control form-control-lg"
                                            value="{{ $estudiante->persona->telefono ?? '' }}">
                                    </div>
                                </div>

                                <!-- Departamento y Ciudad -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Departamento</label>
                                        <select class="form-select form-select-lg" id="departamento">
                                            <option value="">Seleccionar</option>
                                            @foreach ($departamentos as $depto)
                                                <option value="{{ $depto->id }}"
                                                    {{ ($estudiante->persona->ciudad->departamento_id ?? '') == $depto->id ? 'selected' : '' }}>
                                                    {{ $depto->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Ciudad <span
                                                class="text-danger">*</span></label>
                                        <select name="ciudade_id" class="form-select form-select-lg" id="ciudad"
                                            required>
                                            <option value="">Seleccionar ciudad</option>
                                            @foreach ($ciudades as $ciudad)
                                                <option value="{{ $ciudad->id }}"
                                                    {{ ($estudiante->persona->ciudade_id ?? '') == $ciudad->id ? 'selected' : '' }}>
                                                    {{ $ciudad->nombre }} ({{ $ciudad->departamento->nombre ?? '' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Por favor selecciona una ciudad</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Fecha de Nacimiento</label>
                                        <input type="date" name="fecha_nacimiento"
                                            class="form-control form-control-lg"
                                            value="{{ $estudiante->persona->fecha_nacimiento ?? '' }}"
                                            max="{{ now()->subYears(18)->format('Y-m-d') }}">
                                        <small id="edad_calculada" class="form-text mt-1"></small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Dirección</label>
                                        <textarea name="direccion" class="form-control" rows="2">{{ $estudiante->persona->direccion ?? '' }}</textarea>
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
                                        @if ($estudiante->persona->estudios->count() > 0)
                                            @foreach ($estudiante->persona->estudios as $index => $estudio)
                                                <div class="estudio-item row mb-3 border rounded p-3 bg-light">
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-medium">Grado Académico</label>
                                                        <select class="form-select grado-select"
                                                            name="estudios[{{ $index }}][grado]">
                                                            <option value="">Seleccionar</option>
                                                            @foreach ($grados as $grado)
                                                                <option value="{{ $grado->id }}"
                                                                    {{ $estudio->grados_academico_id == $grado->id ? 'selected' : '' }}>
                                                                    {{ $grado->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-medium">Profesión</label>
                                                        <select class="form-select profesion-select"
                                                            name="estudios[{{ $index }}][profesion]">
                                                            <option value="">Seleccionar</option>
                                                            @foreach ($profesiones as $prof)
                                                                <option value="{{ $prof->id }}"
                                                                    {{ $estudio->profesione_id == $prof->id ? 'selected' : '' }}>
                                                                    {{ $prof->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-medium">Universidad</label>
                                                        <select class="form-select universidad-select"
                                                            name="estudios[{{ $index }}][universidad]">
                                                            <option value="">Seleccionar</option>
                                                            @foreach ($universidades as $uni)
                                                                <option value="{{ $uni->id }}"
                                                                    {{ $estudio->universidade_id == $uni->id ? 'selected' : '' }}>
                                                                    {{ $uni->nombre }} ({{ $uni->sigla }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm remove-estudio">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="estudio-item row mb-3 border rounded p-3 bg-light">
                                                <div class="col-md-3">
                                                    <label class="form-label fw-medium">Grado Académico</label>
                                                    <select class="form-select grado-select" name="estudios[0][grado]">
                                                        <option value="">Seleccionar</option>
                                                        @foreach ($grados as $grado)
                                                            <option value="{{ $grado->id }}">{{ $grado->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-medium">Profesión</label>
                                                    <select class="form-select profesion-select"
                                                        name="estudios[0][profesion]">
                                                        <option value="">Seleccionar</option>
                                                        @foreach ($profesiones as $prof)
                                                            <option value="{{ $prof->id }}">{{ $prof->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-medium">Universidad</label>
                                                    <select class="form-select universidad-select"
                                                        name="estudios[0][universidad]">
                                                        <option value="">Seleccionar</option>
                                                        @foreach ($universidades as $uni)
                                                            <option value="{{ $uni->id }}">{{ $uni->nombre }}
                                                                ({{ $uni->sigla }})
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
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 mt-4">
                                    <div class="d-flex justify-content-end gap-3">
                                        <a href="{{ route('admin.estudiantes.listar') }}"
                                            class="btn btn-secondary btn-lg">
                                            <i class="ri-close-line me-1"></i> Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-lg" id="btn-guardar">
                                            <i class="ri-save-line me-1"></i> Guardar Cambios
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            $(document).ready(function() {
                let estudioCounter = {{ $estudiante->persona->estudios->count() ?? 1 }};
                let isProcessing = false;

                // Validar apellidos
                function validarApellidos() {
                    const p = $('input[name="apellido_paterno"]').val().trim();
                    const m = $('input[name="apellido_materno"]').val().trim();
                    if (!p && !m) {
                        $('#feedback_apellidos').text('⚠️ Debe ingresar al menos un apellido.').addClass('text-danger');
                        return false;
                    } else {
                        $('#feedback_apellidos').text('').removeClass('text-danger');
                        return true;
                    }
                }

                // Verificar correo único
                function verificarCorreoUnico(correo) {
                    return new Promise((resolve) => {
                        $.post("{{ route('admin.personas.verificar-edicion') }}", {
                            _token: "{{ csrf_token() }}",
                            correo: correo,
                            persona_id: {{ $estudiante->persona->id ?? 0 }}
                        }, function(res) {
                            resolve(!res.exists);
                        }).fail(() => resolve(false));
                    });
                }

                // Calcular edad
                function calcularEdad() {
                    const fecha = $('input[name="fecha_nacimiento"]').val();
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
                        $('#edad_calculada').addClass('text-danger').removeClass('text-success').text(
                            '⚠️ Debe tener al menos 18 años.');
                        return false;
                    } else {
                        $('#edad_calculada').addClass('text-success').removeClass('text-danger').text(
                            `✅ Edad: ${edad} años`);
                        return true;
                    }
                }

                // Crear fila de estudio
                function crearFilaEstudio(index) {
                    return `
                <div class="estudio-item row mb-3 border rounded p-3 bg-light">
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Grado Académico</label>
                        <select class="form-select grado-select" name="estudios[${index}][grado]">
                            <option value="">Seleccionar</option>
                            @foreach ($grados as $grado)
                                <option value="{{ $grado->id }}">{{ $grado->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Profesión</label>
                        <select class="form-select profesion-select" name="estudios[${index}][profesion]">
                            <option value="">Seleccionar</option>
                            @foreach ($profesiones as $prof)
                                <option value="{{ $prof->id }}">{{ $prof->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Universidad</label>
                        <select class="form-select universidad-select" name="estudios[${index}][universidad]">
                            <option value="">Seleccionar</option>
                            @foreach ($universidades as $uni)
                                <option value="{{ $uni->id }}">{{ $uni->nombre }} ({{ $uni->sigla }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-estudio">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>`;
                }

                // Eventos
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

                // Validar correo en tiempo real
                $('input[name="correo"]').on('blur', async function() {
                    const correo = $(this).val().trim();
                    const feedback = $('#feedback_correo');

                    if (!correo) {
                        feedback.text('').removeClass('text-success text-danger');
                        return;
                    }

                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(correo)) {
                        feedback.addClass('text-danger').removeClass('text-success').html(
                            '<i class="ri-error-warning-line me-1"></i> Formato de correo inválido');
                        return;
                    }

                    const disponible = await verificarCorreoUnico(correo);
                    if (disponible) {
                        feedback.addClass('text-success').removeClass('text-danger').html(
                            '<i class="ri-checkbox-circle-line me-1"></i> Correo disponible');
                    } else {
                        feedback.addClass('text-danger').removeClass('text-success').html(
                            '<i class="ri-error-warning-line me-1"></i> Este correo ya está registrado por otra persona'
                        );
                    }
                });

                // Calcular edad al cambiar fecha
                $('input[name="fecha_nacimiento"]').on('change', calcularEdad);

                // Validar formulario
                $('#formEditarEstudiante').on('submit', function(e) {
                    e.preventDefault();

                    if (isProcessing) return;
                    isProcessing = true;

                    const btn = $('#btn-guardar');
                    const originalHtml = btn.html();

                    btn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...'
                    );

                    // Validaciones
                    if (!validarApellidos()) {
                        showToast('error', 'Debe ingresar al menos un apellido');
                        btn.prop('disabled', false).html(originalHtml);
                        isProcessing = false;
                        return;
                    }

                    if (!calcularEdad()) {
                        showToast('error', 'El estudiante debe tener al menos 18 años');
                        btn.prop('disabled', false).html(originalHtml);
                        isProcessing = false;
                        return;
                    }

                    // Enviar datos
                    $.ajax({
                        url: "{{ route('admin.estudiantes.actualizar') }}",
                        type: "POST",
                        data: $(this).serialize(),
                        success: function(res) {
                            if (res.success) {
                                showToast('success', res.msg);
                                if (res.redirect) {
                                    setTimeout(() => {
                                        window.location.href = res.redirect;
                                    }, 1500);
                                }
                            } else {
                                showToast('error', res.msg);
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Error al actualizar el estudiante';
                            if (xhr.responseJSON?.errors) {
                                errorMsg = 'Errores de validación:<br>';
                                for (const field in xhr.responseJSON.errors) {
                                    errorMsg += `• ${xhr.responseJSON.errors[field][0]}<br>`;
                                }
                            }
                            showToast('error', errorMsg);
                        },
                        complete: function() {
                            isProcessing = false;
                            btn.prop('disabled', false).html(originalHtml);
                        }
                    });
                });

                // Función toast (debes tenerla definida o incluirla)
                function showToast(type, message) {
                    // Usar tu función toast existente
                    if (typeof window.showToast === 'function') {
                        window.showToast(type, message);
                    } else {
                        alert(message); // Fallback simple
                    }
                }

                // Calcular edad inicial
                calcularEdad();
            });
        </script>
    @endpush
@endsection
