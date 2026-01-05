@extends('admin.dashboard')

@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.ofertas.listar') }}">Ofertas</a></li>
            <li class="breadcrumb-item active">Inscritos - {{ $oferta->codigo }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>Inscritos y Pre-Inscritos <small>({{ $oferta->programa?->nombre }})</small></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre Completo</th>
                                    <th>Carnet</th>
                                    <th>Estado</th>
                                    <th>Plan de Pago</th>
                                    <th>Fecha Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inscripciones as $insc)
                                    <tr>
                                        <td>
                                            {{ trim("{$insc->estudiante->persona->apellido_paterno} {$insc->estudiante->persona->apellido_materno}, {$insc->estudiante->persona->nombres}") }}
                                        </td>
                                        <td>{{ $insc->estudiante->persona->carnet }}</td>
                                        <td>
                                            @if ($insc->estado === 'Inscrito')
                                                <span class="badge bg-success">Inscrito</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pre-Inscrito</span>
                                            @endif
                                        </td>
                                        <td>{{ $insc->planesPago?->nombre ?? '—' }}</td>
                                        <td>{{ $insc->fecha_registro?->format('d/m/Y') }}</td>
                                        <td>
                                            @if ($insc->estado === 'Pre-Inscrito')
                                                <button type="button" class="btn btn-sm btn-primary convertir-a-inscrito"
                                                    data-inscripcion-id="{{ $insc->id }}"
                                                    data-oferta-id="{{ $oferta->id }}">
                                                    Convertir a Inscrito
                                                </button>
                                            @else
                                                <!-- Ver Módulos y Notas -->
                                                <button type="button" class="btn btn-sm btn-info ver-modulos-btn"
                                                    data-inscripcion-id="{{ $insc->id }}"
                                                    data-estudiante-nombre="{{ trim("{$insc->estudiante->persona->apellido_paterno} {$insc->estudiante->persona->apellido_materno}, {$insc->estudiante->persona->nombres}") }}">
                                                    <i data-feather="book-open"></i> Módulos
                                                </button>

                                                <!-- Ver Cuotas -->
                                                <button type="button" class="btn btn-sm btn-success ver-cuotas-btn"
                                                    data-inscripcion-id="{{ $insc->id }}"
                                                    data-estudiante-nombre="{{ trim("{$insc->estudiante->persona->apellido_paterno} {$insc->estudiante->persona->apellido_materno}, {$insc->estudiante->persona->nombres}") }}">
                                                    <i data-feather="dollar-sign"></i> Cuotas
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No hay inscripciones registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para seleccionar plan de pago y confirmar conversión -->
    <div class="modal fade" id="modalConvertirInscrito" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Convertir a Inscrito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <p><strong>Estudiante:</strong> <span id="nombre-estudiante"></span></p>
                    <input type="hidden" id="inscripcion-id">
                    <div class="mb-3">
                        <label>Seleccione Plan de Pago *</label>
                        <select id="planes-pago-convertir" class="form-select" required>
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btn-confirmar-conversion">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Módulos y Notas -->
    <div class="modal fade" id="modalVerModulos" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><i class="ri-book-open-line me-1"></i> Módulos - <span id="estudiante-nombre-modulos"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Módulo</th>
                                    <th>Nota Regular</th>
                                    <th>Nota Nivelación</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-modulos-body">
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Cuotas -->
    <div class="modal fade" id="modalVerCuotas" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><i class="ri-money-dollar-circle-line me-1"></i> Cuotas - <span
                            id="estudiante-nombre-cuotas"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="inscripcion_id_cuotas" value="">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th>Cuota</th>
                                    <th>Monto Total</th>
                                    <th>Pendiente</th>
                                    <th>Fecha Pago</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-cuotas-body">
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- ✅ Botón para registrar pago -->
                    <div class="mt-3 d-flex justify-content-between">
                        <button type="button" class="btn btn-success" id="btn-registrar-pago">
                            <i class="ri-add-line me-1"></i> Registrar Pago
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Registrar Pago -->
    <div class="modal fade" id="modalRegistrarPago" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-money-dollar-circle-line me-1"></i>
                        Registrar Pago – <span id="estudiante-nombre-pago"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formRegistrarPago">
                        @csrf
                        <input type="hidden" name="inscripcion_id" id="inscripcion_id_pago">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Pago *</label>
                                <input type="date" name="fecha_pago" class="form-control"
                                    value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Pago *</label>
                                <select name="tipo_pago" class="form-select" required>
                                    <option value="">Seleccionar</option>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="qr">QR</option>
                                    <option value="parcial">Parcial (Efectivo + QR)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Detalles para pago parcial -->
                        <div id="detalles-parciales" class="row mb-3" style="display:none;">
                            <div class="col-md-6">
                                <label>Efectivo (Bs) *</label>
                                <input type="number" step="0.01" name="detalle_efectivo" class="form-control"
                                    min="0">
                            </div>
                            <div class="col-md-6">
                                <label>QR (Bs) *</label>
                                <input type="number" step="0.01" name="detalle_qr" class="form-control"
                                    min="0">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label>Monto Total (Bs) *</label>
                                <input type="number" step="0.01" name="pago_bs" id="pago_bs_input"
                                    class="form-control" required min="0.01" placeholder="Ej: 400.00">
                            </div>
                            <div class="col-md-6">
                                <label>Descuento (Bs)</label>
                                <input type="number" step="0.01" name="descuento_bs" class="form-control"
                                    min="0">
                            </div>
                        </div>

                        <h6 class="mb-3">Cuotas Pendientes (opcional: seleccione para asignar manualmente)</h6>
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Concepto</th>
                                        <th>Cuota</th>
                                        <th>Total (Bs)</th>
                                        <th>Pendiente (Bs)</th>
                                        <th>Fecha Pago</th>
                                        <th>Seleccionar</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-cuotas-pendientes">
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Cargando...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Registrar Pago</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            let inscripcionId, ofertaId;

            $(document).on('click', '.convertir-a-inscrito', function() {
                inscripcionId = $(this).data('inscripcion-id');
                ofertaId = $(this).data('oferta-id');
                const nombre = $(this).closest('tr').find('td:first').text();

                $('#nombre-estudiante').text(nombre);
                $('#inscripcion-id').val(inscripcionId);

                // Cargar planes de pago asociados a la oferta
                $.get(`/admin/ofertas/${ofertaId}/datos`, function(oferta) {
                    const planesUsados = new Set();
                    if (oferta.plan_concepto) {
                        oferta.plan_concepto.forEach(pc => planesUsados.add(pc.planes_pago_id));
                    }
                    let opts = '<option value="">Seleccione un plan</option>';
                    @php
                        $planes = \App\Models\PlanesPago::all();
                    @endphp
                    const planesDisponibles = @json($planes);
                    planesDisponibles.forEach(p => {
                        if (planesUsados.has(p.id)) {
                            opts += `<option value="${p.id}">${p.nombre}</option>`;
                        }
                    });
                    $('#planes-pago-convertir').html(opts);
                });

                $('#modalConvertirInscrito').modal('show');
            });

            $('#btn-confirmar-conversion').on('click', function() {
                const planId = $('#planes-pago-convertir').val();
                if (!planId) {
                    alert('Debe seleccionar un plan de pago.');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.inscripciones.convertir-a-inscrito') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        inscripcion_id: inscripcionId,
                        planes_pago_id: planId
                    },
                    success: function(res) {
                        alert(res.msg);
                        if (res.success) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.msg || 'Error al convertir.');
                    }
                });
            });

            // === Ver Módulos y Registrar Notas ===
            $(document).on('click', '.ver-modulos-btn', function() {
                const inscripcionId = $(this).data('inscripcion-id');
                const nombre = $(this).data('estudiante-nombre');
                $('#estudiante-nombre-modulos').text(nombre);

                // Cargar módulos
                $.get(`/admin/inscripciones/${inscripcionId}/modulos-notas`, function(matriculaciones) {
                    let html = '';
                    if (matriculaciones.length === 0) {
                        html =
                            '<tr><td colspan="4" class="text-center">No hay módulos matriculados.</td></tr>';
                    } else {
                        matriculaciones.forEach(m => {
                            const modulo = m.modulo?.nombre || `Módulo ${m.modulo_id}`;
                            html += `
                <tr>
                    <td>${modulo}</td>
                    <td>
                        <input type="number" class="form-control nota-regular" 
                               value="${m.nota_regular || ''}" 
                               data-matriculacion-id="${m.id}" 
                               min="0" max="100" placeholder="0-100">
                    </td>
                    <td>
                        <input type="number" class="form-control nota-nivelacion" 
                               value="${m.nota_nivelacion || ''}" 
                               data-matriculacion-id="${m.id}" 
                               min="0" max="100" placeholder="0-100">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary guardar-nota"
                                data-matriculacion-id="${m.id}">
                            Guardar
                        </button>
                    </td>
                </tr>`;
                        });
                    }
                    $('#tabla-modulos-body').html(html);
                    refreshFeather();
                });
                $('#modalVerModulos').modal('show');
            });

            $(document).on('click', '.guardar-nota', function() {
                const id = $(this).data('matriculacion-id');
                const regular = $(`.nota-regular[data-matriculacion-id="${id}"]`).val();
                const nivelacion = $(`.nota-nivelacion[data-matriculacion-id="${id}"]`).val();

                $.ajax({
                    url: `/admin/inscripciones/${id}/registrar-nota`,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        nota_regular: regular || null,
                        nota_nivelacion: nivelacion || null
                    },
                    success: function(res) {
                        alert(res.msg);
                    },
                    error: function() {
                        alert('Error al guardar la nota.');
                    }
                });
            });

            // === Ver Cuotas ===
            // === Ver Cuotas ===
            $(document).on('click', '.ver-cuotas-btn', function() {
                const inscripcionId = $(this).data('inscripcion-id');
                const nombre = $(this).data('estudiante-nombre');
                $('#estudiante-nombre-cuotas').text(nombre);
                // 👇 AÑADE ESTA LÍNEA PARA ASIGNAR EL VALOR AL CAMPO OCULTO
                $('#inscripcion_id_cuotas').val(inscripcionId);
                $.get(`/admin/inscripciones/${inscripcionId}/cuotas-pendientes`, function(cuotas) {
                    let html = '';
                    if (cuotas.length === 0) {
                        html =
                            '<tr><td colspan="6" class="text-center">No hay cuotas pendientes.</td></tr>';
                    } else {
                        cuotas.forEach(c => {
                            const estado = c.pago_terminado === 'si' ?
                                '<span class="badge bg-success">Pagado</span>' :
                                '<span class="badge bg-warning text-dark">Pendiente</span>';
                            html += `
                <tr>
                    <td>${c.nombre}</td>
                    <td>${c.n_cuota}</td>
                    <td>${parseFloat(c.pago_total_bs).toFixed(2)}</td>
                    <td>${parseFloat(c.pago_pendiente_bs).toFixed(2)}</td>
                    <td>${c.fecha_pago}</td>
                    <td>${estado}</td>
                </tr>`;
                        });
                    }
                    $('#tabla-cuotas-body').html(html);
                });
                $('#modalVerCuotas').modal('show');
            });

            // === Abrir modal de pago ===
            $(document).on('click', '#btn-registrar-pago', function() {
                const inscripcionId = $('#inscripcion_id_cuotas').val();
                if (!inscripcionId) {
                    alert('Error: ID de inscripción no disponible.');
                    return;
                }
                const nombre = $('#estudiante-nombre-cuotas').text();
                $('#estudiante-nombre-pago').text(nombre);
                $('#inscripcion_id_pago').val(inscripcionId); // 👈 Clave: enviar inscripcion_id

                // Cargar cuotas pendientes para mostrarlas (solo para referencia)
                $.get(`/admin/inscripciones/${inscripcionId}/cuotas-pendientes`, function(cuotas) {
                    let html = '';
                    if (cuotas.length === 0) {
                        html =
                            '<tr><td colspan="6" class="text-center">No hay cuotas pendientes.</td></tr>';
                    } else {
                        cuotas.forEach(c => {
                            html += `
                <tr>
                    <td>${c.nombre}</td>
                    <td>${c.n_cuota}</td>
                    <td>${parseFloat(c.pago_total_bs).toFixed(2)}</td>
                    <td>${parseFloat(c.pago_pendiente_bs).toFixed(2)}</td>
                    <td>${c.fecha_pago}</td>
                    <td>
                        <input type="checkbox" class="cuota-seleccionada" 
                               data-cuota-id="${c.id}" 
                               data-monto-max="${c.pago_pendiente_bs}">
                    </td>
                </tr>`;
                        });
                    }
                    $('#tabla-cuotas-pendientes').html(html);
                }).fail(function(xhr) {
                    alert('Error al cargar las cuotas pendientes: ' + xhr.status + ' - ' + xhr
                        .statusText);
                });

                $('#modalRegistrarPago').modal('show');
            });

            // === Manejar tipo de pago parcial ===
            $(document).on('change', 'select[name="tipo_pago"]', function() {
                if ($(this).val() === 'parcial') {
                    $('#detalles-parciales').show();
                } else {
                    $('#detalles-parciales').hide();
                    $('input[name="detalle_efectivo"], input[name="detalle_qr"]').val('');
                }
            });

            // === Enviar formulario ===
            $('#formRegistrarPago').submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                // Obtener cuotas seleccionadas (si las hay)
                const cuotasSeleccionadas = [];
                $('.cuota-seleccionada:checked').each(function() {
                    const cuotaId = $(this).data('cuota-id');
                    const montoMax = parseFloat($(this).data('monto-max'));
                    cuotasSeleccionadas.push({
                        cuota_id: cuotaId,
                        monto: montoMax
                    });
                });

                // Solo adjuntar cuotas_seleccionadas si hay al menos una
                if (cuotasSeleccionadas.length > 0) {
                    formData.append('cuotas_seleccionadas', JSON.stringify(cuotasSeleccionadas));
                }

                // Validar monto total
                const montoTotal = parseFloat($('#pago_bs_input').val());
                if (isNaN(montoTotal) || montoTotal <= 0) {
                    alert('Ingrese un monto total válido.');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.pagos.registrar') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        alert(res.msg);
                        if (res.success) {
                            $('#modalRegistrarPago').modal('hide');
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.msg || xhr.responseJSON?.message ||
                            'Error al registrar el pago.';
                        alert('Error: ' + errorMsg);
                    }
                });
            });

            // Calcular total al seleccionar cuotas
            $(document).on('change', '.cuota-seleccionada', function() {
                let total = 0;
                $('.cuota-seleccionada:checked').each(function() {
                    const montoMax = parseFloat($(this).data('monto-max'));
                    total += montoMax;
                });
                $('#formRegistrarPago [name="pago_bs"]').val(total.toFixed(2));
            });


        });
    </script>
@endpush
