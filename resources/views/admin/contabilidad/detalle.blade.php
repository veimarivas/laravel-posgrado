@extends('admin.dashboard')

@section('admin')
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">Detalle Contable - {{ $estudiante->persona->nombres ?? 'Participante' }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.contabilidad.buscar') }}">Contabilidad</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $estudiante->persona->carnet ?? '' }}</li>
                        </ol>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.contabilidad.buscar') }}" class="btn btn-light btn-sm">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Volver a Buscar
                    </a>
                    <a href="{{ route('admin.estudiantes.detalle', $estudiante->id) }}" class="btn btn-info btn-sm">
                        <i class="ri-user-line align-middle me-1"></i> Ver Perfil Completo
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Info -->
    <div class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-lg">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="ri-user-3-line fs-2"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-1">{{ $estudiante->persona->nombres }}
                                        {{ $estudiante->persona->apellido_paterno }}
                                        {{ $estudiante->persona->apellido_materno }}</h4>
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        <span class="badge bg-secondary">{{ $estudiante->persona->carnet }}</span>
                                        <span class="badge bg-info">{{ $estudiante->persona->correo }}</span>
                                        <span class="badge bg-light text-dark">{{ $estudiante->persona->celular }}</span>
                                    </div>
                                    <p class="mb-0 text-muted">
                                        {{ $estudiante->persona->direccion ?? 'Sin dirección' }} •
                                        {{ $estudiante->persona->ciudad->nombre ?? 'N/A' }},
                                        {{ $estudiante->persona->ciudad->departamento->nombre ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-md-end mt-3 mt-md-0">
                                @php
                                    $totalDeuda = 0;
                                    $totalPagado = 0;
                                    foreach ($estudiante->inscripciones as $inscripcion) {
                                        foreach ($inscripcion->cuotas as $cuota) {
                                            $totalPagado += $cuota->pago_total_bs - $cuota->pago_pendiente_bs;
                                            $totalDeuda += $cuota->pago_pendiente_bs;
                                        }
                                    }
                                @endphp
                                <h4 class="text-success">{{ number_format($totalPagado, 2) }} Bs</h4>
                                <p class="text-muted mb-1">Total Pagado</p>
                                <h4 class="text-danger">{{ number_format($totalDeuda, 2) }} Bs</h4>
                                <p class="text-muted mb-0">Total Deuda</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Programas y cuotas -->
    <div class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header border-bottom bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fs-16">Programas y Estado de Cuotas</h5>
                        <span class="badge bg-primary">{{ $estudiante->inscripciones->count() }} Programas</span>
                    </div>
                </div>
                <div class="card-body">
                    @if ($estudiante->inscripciones->count() > 0)
                        <div class="accordion accordion-flush" id="accordionContable">
                            @foreach ($estudiante->inscripciones->sortByDesc('fecha_registro') as $index => $inscripcion)
                                @php
                                    $oferta = $inscripcion->ofertaAcademica;
                                    $programa = $oferta->programa ?? null;
                                    $cuotas = $inscripcion->cuotas_ordenadas ?? $inscripcion->cuotas;

                                    // Calcular estadísticas por programa
                                    $deudaPrograma = 0;
                                    $pagadoPrograma = 0;
                                    $cuotasTotales = $cuotas->count();
                                    $cuotasPagadas = 0;
                                    $cuotasPendientes = 0;

                                    foreach ($cuotas as $cuota) {
                                        $deudaPrograma += $cuota->pago_pendiente_bs;
                                        $pagadoPrograma += $cuota->pago_total_bs - $cuota->pago_pendiente_bs;

                                        if ($cuota->pago_terminado == 'si') {
                                            $cuotasPagadas++;
                                        } else {
                                            $cuotasPendientes++;
                                        }
                                    }

                                    $totalPrograma = $deudaPrograma + $pagadoPrograma;
                                    $porcentajePagado =
                                        $totalPrograma > 0 ? ($pagadoPrograma / $totalPrograma) * 100 : 0;
                                    $cuotasPendientes = $cuotas->where('pago_terminado', '!=', 'si')->count();
                                @endphp

                                <div class="accordion-item border-bottom">
                                    <h2 class="accordion-header" id="contableHeading{{ $index }}">
                                        <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#contableCollapse{{ $index }}"
                                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                            aria-controls="contableCollapse{{ $index }}">
                                            <div class="d-flex justify-content-between w-100 me-3">
                                                <div>
                                                    <h6 class="mb-0">
                                                        {{ $programa->nombre ?? 'Programa no especificado' }}</h6>
                                                    <small class="text-muted">
                                                        {{ $oferta->modalidad->nombre ?? '' }} •
                                                        {{ $oferta->sucursal->nombre ?? '' }} •
                                                        {{ $inscripcion->planesPago->nombre ?? '' }}
                                                    </small>
                                                </div>
                                                <div class="text-end">
                                                    <div class="d-flex flex-column align-items-end">
                                                        <span class="badge bg-success mb-1">
                                                            Pagado: {{ number_format($pagadoPrograma, 2) }} Bs
                                                        </span>
                                                        <span class="badge bg-danger mb-1">
                                                            Deuda: {{ number_format($deudaPrograma, 2) }} Bs
                                                        </span>
                                                        <div class="progress mt-1" style="width: 100px; height: 6px;">
                                                            <div class="progress-bar bg-success" role="progressbar"
                                                                style="width: {{ $porcentajePagado }}%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="contableCollapse{{ $index }}"
                                        class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                        aria-labelledby="contableHeading{{ $index }}"
                                        data-bs-parent="#accordionContable">
                                        <div class="accordion-body p-4">
                                            {{-- Barra acción multi-cuota --}}
                                            @if ($cuotasPendientes > 0)
                                                <div class="d-flex align-items-center justify-content-between bg-light border rounded px-3 py-2 mb-3">
                                                    <span class="small text-muted">
                                                        <i class="ri-list-check-2 me-1"></i>
                                                        {{ $cuotasPendientes }} cuota(s) pendiente(s) en este programa
                                                    </span>
                                                    <button type="button"
                                                        class="btn btn-sm btn-primary btn-pagar-multiple"
                                                        data-estudiante-id="{{ $estudiante->id }}">
                                                        <i class="ri-stack-line me-1"></i>Pagar Múltiples Cuotas
                                                    </button>
                                                </div>
                                            @endif
                                            <!-- Cuotas del programa -->
                                            <div class="table-responsive">
                                                <table class="table table-hover align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Cuota</th>
                                                            <th class="text-end">Total (Bs)</th>
                                                            <th class="text-end">Pagado (Bs)</th>
                                                            <th class="text-end">Pendiente (Bs)</th>
                                                            <th>Fecha Pago</th>
                                                            <th>Estado</th>
                                                            <th class="text-center">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($cuotas as $cuota)
                                                            @php
                                                                $pagado =
                                                                    $cuota->pago_total_bs - $cuota->pago_pendiente_bs;
                                                            @endphp
                                                            <tr
                                                                class="{{ $cuota->pago_terminado == 'si' ? 'table-success' : 'table-warning' }}">
                                                                <td>{{ $cuota->n_cuota }}</td>
                                                                <td>
                                                                    <div class="fw-medium">{{ $cuota->nombre }}</div>
                                                                    @if ($cuota->pagos_cuotas->count() > 0)
                                                                        <small
                                                                            class="text-muted">{{ $cuota->pagos_cuotas->count() }}
                                                                            pago(s)</small>
                                                                    @endif
                                                                </td>
                                                                <td class="text-end fw-bold">
                                                                    {{ number_format($cuota->pago_total_bs, 2) }}</td>
                                                                <td class="text-end text-success">
                                                                    {{ number_format($pagado, 2) }}</td>
                                                                <td class="text-end text-danger">
                                                                    {{ number_format($cuota->pago_pendiente_bs, 2) }}</td>
                                                                <td>
                                                                    @if ($cuota->fecha_pago)
                                                                        {{ \Carbon\Carbon::parse($cuota->fecha_pago)->format('d/m/Y') }}
                                                                    @else
                                                                        <span class="text-muted">Por definir</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($cuota->pago_terminado == 'si')
                                                                        <span class="badge bg-success">Pagado</span>
                                                                    @else
                                                                        <span class="badge bg-warning">Pendiente</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($cuota->pago_pendiente_bs > 0)
                                                                        <button
                                                                            class="btn btn-sm btn-success btn-pagar-cuota"
                                                                            data-cuota-id="{{ $cuota->id }}"
                                                                            data-estudiante-id="{{ $estudiante->id }}">
                                                                            <i class="ri-money-dollar-circle-line me-1"></i>
                                                                            Pagar
                                                                        </button>
                                                                    @endif
                                                                    @if ($cuota->pagos_cuotas->count() > 0)
                                                                        <button
                                                                            class="btn btn-sm btn-info btn-ver-recibos mt-1"
                                                                            data-cuota-id="{{ $cuota->id }}"
                                                                            data-cuota-nombre="{{ $cuota->nombre }}">
                                                                            <i class="ri-receipt-line me-1"></i> Recibos
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        <tr class="table-light">
                                                            <td colspan="2" class="fw-bold">Totales del Programa:</td>
                                                            <td class="text-end fw-bold">
                                                                {{ number_format($totalPrograma, 2) }} Bs</td>
                                                            <td class="text-end fw-bold text-success">
                                                                {{ number_format($pagadoPrograma, 2) }} Bs</td>
                                                            <td class="text-end fw-bold text-danger">
                                                                {{ number_format($deudaPrograma, 2) }} Bs</td>
                                                            <td colspan="3"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-light text-secondary rounded-circle">
                                    <i class="ri-graduation-cap-line fs-2"></i>
                                </div>
                            </div>
                            <h5 class="mb-2">No hay programas inscritos</h5>
                            <p class="text-muted mb-0">El participante no está inscrito en ningún programa.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Totales finales -->
    @if ($estudiante->inscripciones->count() > 0)
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="card border border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">{{ number_format($totalPagado + $totalDeuda, 2) }} Bs</h5>
                                <small class="text-muted">Monto Total General</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-success-subtle text-success rounded">
                                        <i class="ri-checkbox-circle-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">{{ number_format($totalPagado, 2) }} Bs</h5>
                                <small class="text-muted">Total Pagado</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border border-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-danger-subtle text-danger rounded">
                                        <i class="ri-alert-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">{{ number_format($totalDeuda, 2) }} Bs</h5>
                                <small class="text-muted">Total Deuda</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modales -->
    @include('admin.estudiantes.partials.modal-pagar-cuota')
    @include('admin.contabilidad.partials.modal-pagar-contabilidad')
    @include('admin.estudiantes.partials.modal-recibos-cuota')
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ── togglePcFields debe ser global (llamada desde onchange="" inline) ──
        var pcCuotasData = {};

        function togglePcFields() {
            var tipo = document.getElementById('pc_tipo_pago').value;
            document.getElementById('pc_campo_caja').style.display        = 'none';
            document.getElementById('pc_campo_cuenta').style.display      = 'none';
            document.getElementById('pc_campo_comprobante').style.display  = 'none';
            document.getElementById('pc_caja_id').removeAttribute('required');
            document.getElementById('pc_cuenta_id').removeAttribute('required');
            document.getElementById('pc_n_comprobante').removeAttribute('required');

            if (tipo === 'Efectivo') {
                document.getElementById('pc_campo_caja').style.display = 'block';
                document.getElementById('pc_caja_id').setAttribute('required', 'required');
            } else if (['Transferencia', 'Depósito', 'Tarjeta'].includes(tipo)) {
                document.getElementById('pc_campo_cuenta').style.display      = 'block';
                document.getElementById('pc_campo_comprobante').style.display  = 'block';
                document.getElementById('pc_cuenta_id').setAttribute('required', 'required');
                document.getElementById('pc_n_comprobante').setAttribute('required', 'required');
            }
        }

        $(document).ready(function () {

            // ── Acordeón ─────────────────────────────────────────────────────
            $('.accordion-button').on('click', function () {
                $(this).toggleClass('collapsed');
            });

            // ── Helpers multi-cuota ──────────────────────────────────────────
            function pcActualizarResumen() {
                var monto     = parseFloat($('#pc_monto_pago').val()) || 0;
                var descuento = parseFloat($('#pc_descuento').val()) || 0;
                var total     = Math.max(0, monto - descuento);
                var pendSel   = parseFloat($('#pc_pendiente_total').text()) || 0;
                var pct       = pendSel > 0 ? Math.min(100, (total / pendSel) * 100) : 0;

                $('#pc_res_monto').text(monto.toFixed(2) + ' Bs');
                $('#pc_res_desc').text(descuento.toFixed(2) + ' Bs');
                $('#pc_res_total').text(total.toFixed(2) + ' Bs');
                $('#pc_progreso').css('width', pct.toFixed(1) + '%');
                $('#pc_txt_progreso').text(pct.toFixed(1) + '% del total seleccionado');
            }

            function pcActualizarTotales() {
                var total = 0, count = 0;
                $('.pc-cuota-check:checked').each(function () {
                    total += pcCuotasData[$(this).val()] || 0;
                    count++;
                });
                $('#pc_pendiente_total').text(total.toFixed(2));
                $('#totalSelBadge').text(total.toFixed(2) + ' Bs');
                $('#pc_monto_pago').val(total.toFixed(2));
                $('#pc_res_cuotas').text(count);
                pcActualizarResumen();
            }

            function pcCargarCuotas(estudianteId, preseleccionarId) {
                $('#listaCuotasPago').html('<div class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm me-2"></div>Cargando...</div>');
                pcCuotasData = {};

                $.ajax({
                    url: '/admin/estudiantes/' + estudianteId + '/cuotas-pendientes',
                    type: 'GET',
                    success: function (response) {
                        if (!response.success || !response.programas || response.programas.length === 0) {
                            $('#listaCuotasPago').html('<div class="alert alert-warning mb-0">No hay cuotas pendientes.</div>');
                            return;
                        }

                        var tipoBadgeClass = {
                            'Matrícula':    'bg-primary text-white',
                            'Colegiatura':  'bg-success text-white',
                            'Certificación':'bg-warning text-dark',
                            'Otros':        'bg-secondary text-white',
                        };

                        var html = '';
                        response.programas.forEach(function (prog) {
                            html += '<div class="prog-header"><i class="ri-graduation-cap-line me-1"></i>' + prog.programa + '</div>';
                            prog.cuotas.forEach(function (cuota) {
                                pcCuotasData[cuota.id] = cuota.pendiente_bs;
                                var checked  = (preseleccionarId && cuota.id == preseleccionarId) ? 'checked' : '';
                                var selCls   = checked ? 'selected' : '';
                                var badgeCls = tipoBadgeClass[cuota.tipo] || 'bg-secondary text-white';
                                html += '<label class="cuota-check-item ' + selCls + '" for="pcCuota_' + cuota.id + '">' +
                                    '<input class="pc-cuota-check" type="checkbox" id="pcCuota_' + cuota.id + '" value="' + cuota.id + '" ' + checked + '>' +
                                    '<div class="flex-grow-1 min-w-0">' +
                                        '<div class="fw-medium" style="font-size:.84rem;">' + cuota.nombre + '</div>' +
                                        '<div class="text-muted" style="font-size:.75rem;">Pendiente: <strong>' + cuota.pendiente_bs.toFixed(2) + ' Bs</strong></div>' +
                                    '</div>' +
                                    '<span class="cuota-tipo-badge ' + badgeCls + '">' + cuota.tipo + '</span>' +
                                '</label>';
                            });
                        });

                        $('#listaCuotasPago').html(html);
                        pcActualizarTotales();
                    },
                    error: function () {
                        $('#listaCuotasPago').html('<div class="alert alert-danger mb-0">Error al cargar cuotas.</div>');
                    }
                });
            }

            // ── Eventos lista de cuotas ──────────────────────────────────────
            $(document).on('change', '.pc-cuota-check', function () {
                $(this).closest('.cuota-check-item').toggleClass('selected', this.checked);
                pcActualizarTotales();
            });

            $('#btnSeleccionarTodas').on('click', function () {
                $('.pc-cuota-check').prop('checked', true).closest('.cuota-check-item').addClass('selected');
                pcActualizarTotales();
            });

            $('#btnDeseleccionarTodas').on('click', function () {
                $('.pc-cuota-check').prop('checked', false).closest('.cuota-check-item').removeClass('selected');
                pcActualizarTotales();
            });

            $(document).on('input', '#pc_monto_pago, #pc_descuento', pcActualizarResumen);

            // ── Abrir modal cuota individual ─────────────────────────────────
            var scSaldoPendiente = 0;

            function scActualizarResumen() {
                var monto     = parseFloat($('#monto_pago').val()) || 0;
                var descuento = parseFloat($('#descuento').val()) || 0;
                if (monto > scSaldoPendiente) { monto = scSaldoPendiente; $('#monto_pago').val(monto.toFixed(2)); }
                if (descuento > monto)        { descuento = monto;        $('#descuento').val(descuento.toFixed(2)); }
                var total = Math.max(0, monto - descuento);
                var pct   = scSaldoPendiente > 0 ? Math.min(100, (monto / scSaldoPendiente) * 100) : 0;
                $('#resumen-monto').text(monto.toFixed(2) + ' Bs');
                $('#resumen-descuento').text(descuento.toFixed(2) + ' Bs');
                $('#resumen-total').text(total.toFixed(2) + ' Bs');
                $('#progreso-pago').css('width', pct.toFixed(1) + '%');
                $('#texto-progreso').text(pct.toFixed(1) + '% del saldo pendiente');
            }

            $(document).on('input', '#monto_pago, #descuento', scActualizarResumen);

            $(document).on('click', '.btn-pagar-cuota', function () {
                var cuotaId      = $(this).data('cuota-id');
                var estudianteId = $(this).data('estudiante-id');

                $('#formPagarCuota')[0].reset();
                $('#cuota_id').val(cuotaId);
                $('#estudiante_id').val(estudianteId);
                $('#fecha_pago').val(new Date().toISOString().split('T')[0]);
                togglePaymentFields();
                $('#modalPagarCuota').modal('show');

                $.ajax({
                    url: '/admin/estudiantes/' + estudianteId + '/cuota/' + cuotaId,
                    type: 'GET',
                    success: function (r) {
                        if (r.success) {
                            var total     = parseFloat(r.cuota.pago_total_bs)    || 0;
                            var pendiente = parseFloat(r.cuota.pago_pendiente_bs) || 0;
                            var pagado    = parseFloat(r.cuota.saldo_pagado)      || 0;
                            scSaldoPendiente = pendiente;

                            $('#info-cuota-nombre').text(r.cuota.nombre);
                            $('#info-cuota-programa').text(r.cuota.programa);
                            $('#info-cuota-total').text(total.toFixed(2));
                            $('#info-cuota-pagado').text(pagado.toFixed(2));
                            $('#info-cuota-pendiente').text(pendiente.toFixed(2));
                            $('#maximo_pago').text(pendiente.toFixed(2));
                            $('#monto_pago').val(pendiente.toFixed(2)).attr('max', pendiente);
                            scActualizarResumen();
                        } else {
                            $('#modalPagarCuota').modal('hide');
                            Swal.fire('Error', r.msg || 'No se pudo cargar la cuota.', 'error');
                        }
                    },
                    error: function () {
                        $('#modalPagarCuota').modal('hide');
                        Swal.fire('Error', 'No se pudo cargar la información de la cuota.', 'error');
                    }
                });
            });

            // ── Submit cuota individual ──────────────────────────────────────
            $(document).on('submit', '#formPagarCuota', function (e) {
                e.preventDefault();
                var estudianteId = $('#estudiante_id').val();
                var tipo = $('#tipo_pago').val();

                if (!tipo) { Swal.fire('Atención', 'Seleccione el tipo de pago.', 'warning'); return; }
                if (tipo === 'Efectivo' && !$('#caja_id').val()) {
                    Swal.fire('Atención', 'Seleccione una caja.', 'warning'); return;
                }
                if (['Transferencia', 'Depósito', 'Tarjeta'].includes(tipo) && !$('#cuenta_bancaria_id').val()) {
                    Swal.fire('Atención', 'Seleccione una cuenta bancaria.', 'warning'); return;
                }

                var $btn = $(this).find('[type=submit]');
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Procesando...');

                $.ajax({
                    url: '/admin/estudiantes/' + estudianteId + '/pagar-cuota',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (r) {
                        if (r.success) {
                            $('#modalPagarCuota').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: '¡Pago Registrado!',
                                html: '<b>Recibo:</b> ' + r.recibo +
                                      '<br><a href="' + r.pdf_url + '" target="_blank" class="btn btn-sm btn-primary mt-2">' +
                                      '<i class="ri-download-line me-1"></i>Descargar Recibo PDF</a>',
                                confirmButtonText: 'Cerrar'
                            }).then(function () { location.reload(); });
                        } else {
                            Swal.fire('Error', r.msg, 'error');
                        }
                    },
                    error: function (xhr) {
                        var msg = 'Error al registrar el pago.';
                        if (xhr.responseJSON) msg = xhr.responseJSON.msg || msg;
                        Swal.fire('Error', msg, 'error');
                    },
                    complete: function () {
                        $btn.prop('disabled', false).html('<i class="ri-checkbox-circle-line me-1"></i>Registrar Pago');
                    }
                });
            });

            // ── Abrir modal multi-cuota ──────────────────────────────────────
            $(document).on('click', '.btn-pagar-multiple', function () {
                var estudianteId = $(this).data('estudiante-id');

                $('#pc_estudiante_id').val(estudianteId);
                $('#formPagarContabilidad')[0].reset();
                $('#pc_tipo_pago').val('');
                togglePcFields();
                pcCargarCuotas(estudianteId, null);
                $('#modalPagarContabilidad').modal('show');
            });

            // ── Registrar pago ───────────────────────────────────────────────
            $('#btnRegistrarPagoContabilidad').on('click', function () {
                var cuotaIds = [];
                $('.pc-cuota-check:checked').each(function () { cuotaIds.push($(this).val()); });

                if (cuotaIds.length === 0) {
                    Swal.fire('Atención', 'Debe seleccionar al menos una cuota.', 'warning'); return;
                }
                var tipo = $('#pc_tipo_pago').val();
                if (!tipo) {
                    Swal.fire('Atención', 'Debe seleccionar el tipo de pago.', 'warning'); return;
                }
                if (tipo === 'Efectivo' && !$('#pc_caja_id').val()) {
                    Swal.fire('Atención', 'Debe seleccionar una caja.', 'warning'); return;
                }
                if (['Transferencia', 'Depósito', 'Tarjeta'].includes(tipo) && !$('#pc_cuenta_id').val()) {
                    Swal.fire('Atención', 'Debe seleccionar una cuenta bancaria.', 'warning'); return;
                }
                var monto = parseFloat($('#pc_monto_pago').val()) || 0;
                if (monto <= 0) {
                    Swal.fire('Atención', 'El monto debe ser mayor a cero.', 'warning'); return;
                }

                var estudianteId = $('#pc_estudiante_id').val();
                var formData = new FormData(document.getElementById('formPagarContabilidad'));
                cuotaIds.forEach(function (id) { formData.append('cuota_ids[]', id); });

                var btn = $(this);
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Registrando...');

                $.ajax({
                    url: '/admin/estudiantes/' + estudianteId + '/pagar-multiples-cuotas',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Pago Registrado!',
                                html: '<b>Recibo:</b> ' + response.recibo +
                                      '<br><a href="' + response.pdf_url + '" target="_blank" class="btn btn-sm btn-primary mt-2">' +
                                      '<i class="ri-download-line me-1"></i>Descargar Recibo PDF</a>',
                                confirmButtonText: 'Cerrar'
                            }).then(function () {
                                $('#modalPagarContabilidad').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.msg || 'No se pudo registrar el pago.', 'error');
                        }
                    },
                    error: function (xhr) {
                        var msg = 'Error al registrar el pago.';
                        if (xhr.responseJSON) msg = xhr.responseJSON.msg || xhr.responseJSON.message || msg;
                        Swal.fire('Error', msg, 'error');
                    },
                    complete: function () {
                        btn.prop('disabled', false).html('<i class="ri-checkbox-circle-line me-1"></i>Registrar Pago');
                    }
                });
            });

            // ── Botón Recibos ────────────────────────────────────────────────
            $(document).on('click', '.btn-ver-recibos', function () {
                var cuotaId     = $(this).data('cuota-id');
                var cuotaNombre = $(this).data('cuota-nombre');

                $('#modalRecibosTitle').text('Recibos de: ' + cuotaNombre);
                $('#modalRecibosCuota').modal('show');

                $('#contenidoRecibos').html(
                    '<div class="text-center py-5">' +
                    '<div class="spinner-border text-primary" role="status"></div>' +
                    '<p class="mt-2">Cargando recibos...</p></div>'
                );

                $.ajax({
                    url: '/admin/estudiantes/cuota/' + cuotaId + '/recibos',
                    type: 'GET',
                    success: function (response) {
                        if (response.success) {
                            $('#contenidoRecibos').html(response.html);
                        } else {
                            $('#contenidoRecibos').html('<div class="alert alert-danger">' + (response.msg || 'Error') + '</div>');
                        }
                    },
                    error: function () {
                        $('#contenidoRecibos').html('<div class="alert alert-danger">Error al cargar los recibos.</div>');
                    }
                });
            });

        }); // end ready
    </script>
@endpush
