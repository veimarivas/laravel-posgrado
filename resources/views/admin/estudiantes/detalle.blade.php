@extends('admin.dashboard')

@section('admin')
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">Detalle del Estudiante</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.estudiantes.listar') }}">Estudiantes</a></li>
                        <li class="breadcrumb-item active">{{ $estudiante->persona->nombres ?? 'Estudiante' }}</li>
                    </ol>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.estudiantes.listar') }}" class="btn btn-light btn-sm">
                        <i class="ri-arrow-left-line me-1"></i>Volver
                    </a>
                    <a href="{{ route('admin.contabilidad.estudiante', $estudiante->id) }}" class="btn btn-outline-success btn-sm">
                        <i class="ri-money-dollar-circle-line me-1"></i>Ver Contabilidad
                    </a>
                    <a href="{{ route('admin.estudiantes.editar', $estudiante->id) }}" class="btn btn-warning btn-sm">
                        <i class="ri-edit-line me-1"></i>Editar
                    </a>
                    <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                        <i class="ri-printer-line me-1"></i>Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Info -->
    @include('admin.estudiantes.partials.detalle-header')

    <!-- Pestañas de Información -->
    @php
        $docPend = [];
        if (!$estudiante->persona->carnet_verificado && $estudiante->persona->fotografia_carnet)
            $docPend[] = 'Carnet';
        if (!$estudiante->persona->certificado_nacimiento_verificado && $estudiante->persona->fotografia_certificado_nacimiento)
            $docPend[] = 'Cert. Nacimiento';
        $epTab = $estudiante->persona->estudios->where('principal', 1)->first();
        if ($epTab) {
            if (!$epTab->documento_academico_verificado && $epTab->documento_academico) $docPend[] = 'Doc. Académico';
            if (!$epTab->documento_provision_verificado && $epTab->documento_provision_nacional) $docPend[] = 'Prov. Nacional';
        }
    @endphp

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom px-4 pt-3 pb-0">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active d-flex align-items-center gap-2 pb-3" data-bs-toggle="tab" href="#tab-personal" role="tab">
                                <i class="ri-user-3-line"></i>
                                <span class="d-none d-sm-inline">Personal</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link d-flex align-items-center gap-2 pb-3" data-bs-toggle="tab" href="#tab-documentos" role="tab">
                                <i class="ri-file-text-line"></i>
                                <span class="d-none d-sm-inline">Documentos</span>
                                @if (count($docPend) > 0)
                                    <span class="badge bg-danger rounded-pill" style="font-size:.65rem;">{{ count($docPend) }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link d-flex align-items-center gap-2 pb-3" data-bs-toggle="tab" href="#tab-academico" role="tab">
                                <i class="ri-graduation-cap-line"></i>
                                <span class="d-none d-sm-inline">Académico</span>
                                @if ($estudiante->inscripciones->count() > 0)
                                    <span class="badge bg-primary-subtle text-primary rounded-pill" style="font-size:.65rem;">{{ $estudiante->inscripciones->count() }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link d-flex align-items-center gap-2 pb-3" data-bs-toggle="tab" href="#tab-financiero" role="tab">
                                <i class="ri-money-dollar-circle-line"></i>
                                <span class="d-none d-sm-inline">Financiero</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link d-flex align-items-center gap-2 pb-3" data-bs-toggle="tab" href="#tab-pagos" role="tab">
                                <i class="ri-history-line"></i>
                                <span class="d-none d-sm-inline">Historial</span>
                                @if ($pagosEstudiante && $pagosEstudiante->count() > 0)
                                    <span class="badge bg-success-subtle text-success rounded-pill" style="font-size:.65rem;">{{ $pagosEstudiante->count() }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-personal" role="tabpanel">
                            @include('admin.estudiantes.partials.detalle-personal')
                        </div>
                        <div class="tab-pane fade" id="tab-documentos" role="tabpanel">
                            @include('admin.estudiantes.partials.detalle-documentos')
                        </div>
                        <div class="tab-pane fade" id="tab-academico" role="tabpanel">
                            @include('admin.estudiantes.partials.detalle-academico')
                        </div>
                        <div class="tab-pane fade" id="tab-financiero" role="tabpanel">
                            @include('admin.estudiantes.partials.detalle-financiero')
                        </div>
                        <div class="tab-pane fade" id="tab-pagos" role="tabpanel">
                            @include('admin.estudiantes.partials.detalle-historial-pagos')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales documentos -->
    @include('admin.estudiantes.partials.modales-subida-documentos')
    @include('admin.estudiantes.partials.modal-preview-documento')
    @include('admin.estudiantes.partials.modal-eliminacion')
    <!-- Modales pagos -->
    @include('admin.estudiantes.partials.modal-pagar-cuota')
    @include('admin.contabilidad.partials.modal-pagar-contabilidad')
    @include('admin.estudiantes.partials.modal-recibos-cuota')
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @include('admin.estudiantes.partials.scripts-documentos')
    @include('admin.estudiantes.partials.scripts-tabs')
    @include('admin.estudiantes.partials.estilos-tabs')

    <script>
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

            // ── Abrir modal cuota individual ─────────────────────────────────
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
                            var total     = parseFloat(r.cuota.pago_total_bs)     || 0;
                            var pendiente = parseFloat(r.cuota.pago_pendiente_bs) || 0;
                            var pagado    = parseFloat(r.cuota.saldo_pagado)       || 0;
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
                if (tipo === 'Efectivo' && !$('#caja_id').val()) { Swal.fire('Atención', 'Seleccione una caja.', 'warning'); return; }
                if (['Transferencia', 'Depósito', 'Tarjeta'].includes(tipo) && !$('#cuenta_bancaria_id').val()) { Swal.fire('Atención', 'Seleccione una cuenta bancaria.', 'warning'); return; }
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
                                icon: 'success', title: '¡Pago Registrado!',
                                html: '<b>Recibo:</b> ' + r.recibo +
                                      '<br><a href="' + r.pdf_url + '" target="_blank" class="btn btn-sm btn-primary mt-2">' +
                                      '<i class="ri-download-line me-1"></i>Descargar Recibo PDF</a>',
                                confirmButtonText: 'Cerrar'
                            }).then(function () { location.reload(); });
                        } else { Swal.fire('Error', r.msg, 'error'); }
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

            // ── Helpers multi-cuota ──────────────────────────────────────────
            function pcActualizarResumen() {
                var monto = parseFloat($('#pc_monto_pago').val()) || 0;
                var desc  = parseFloat($('#pc_descuento').val()) || 0;
                var total = Math.max(0, monto - desc);
                var pend  = parseFloat($('#pc_pendiente_total').text()) || 0;
                var pct   = pend > 0 ? Math.min(100, (total / pend) * 100) : 0;
                $('#pc_res_monto').text(monto.toFixed(2) + ' Bs');
                $('#pc_res_desc').text(desc.toFixed(2) + ' Bs');
                $('#pc_res_total').text(total.toFixed(2) + ' Bs');
                $('#pc_progreso').css('width', pct.toFixed(1) + '%');
                $('#pc_txt_progreso').text(pct.toFixed(1) + '% del total seleccionado');
            }

            function pcActualizarTotales() {
                var total = 0, count = 0;
                $('.pc-cuota-check:checked').each(function () { total += pcCuotasData[$(this).val()] || 0; count++; });
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
                    success: function (res) {
                        if (!res.success || !res.programas || res.programas.length === 0) {
                            $('#listaCuotasPago').html('<div class="alert alert-warning mb-0">No hay cuotas pendientes.</div>');
                            return;
                        }
                        var badge = { 'Matrícula':'bg-primary text-white','Colegiatura':'bg-success text-white','Certificación':'bg-warning text-dark','Otros':'bg-secondary text-white' };
                        var html = '';
                        res.programas.forEach(function (prog) {
                            html += '<div class="prog-header"><i class="ri-graduation-cap-line me-1"></i>' + prog.programa + '</div>';
                            prog.cuotas.forEach(function (c) {
                                pcCuotasData[c.id] = c.pendiente_bs;
                                var chk = (preseleccionarId && c.id == preseleccionarId) ? 'checked' : '';
                                var sel = chk ? 'selected' : '';
                                var bc  = badge[c.tipo] || 'bg-secondary text-white';
                                html += '<label class="cuota-check-item ' + sel + '" for="pcCuota_' + c.id + '">' +
                                    '<input class="pc-cuota-check" type="checkbox" id="pcCuota_' + c.id + '" value="' + c.id + '" ' + chk + '>' +
                                    '<div class="flex-grow-1 min-w-0"><div class="fw-medium" style="font-size:.84rem;">' + c.nombre + '</div>' +
                                    '<div class="text-muted" style="font-size:.75rem;">Pendiente: <strong>' + c.pendiente_bs.toFixed(2) + ' Bs</strong></div></div>' +
                                    '<span class="cuota-tipo-badge ' + bc + '">' + c.tipo + '</span></label>';
                            });
                        });
                        $('#listaCuotasPago').html(html);
                        pcActualizarTotales();
                    },
                    error: function () { $('#listaCuotasPago').html('<div class="alert alert-danger mb-0">Error al cargar cuotas.</div>'); }
                });
            }

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

            $('#btnRegistrarPagoContabilidad').on('click', function () {
                var cuotaIds = [];
                $('.pc-cuota-check:checked').each(function () { cuotaIds.push($(this).val()); });
                if (cuotaIds.length === 0) { Swal.fire('Atención', 'Seleccione al menos una cuota.', 'warning'); return; }
                var tipo = $('#pc_tipo_pago').val();
                if (!tipo) { Swal.fire('Atención', 'Seleccione el tipo de pago.', 'warning'); return; }
                if (tipo === 'Efectivo' && !$('#pc_caja_id').val()) { Swal.fire('Atención', 'Seleccione una caja.', 'warning'); return; }
                if (['Transferencia','Depósito','Tarjeta'].includes(tipo) && !$('#pc_cuenta_id').val()) { Swal.fire('Atención', 'Seleccione una cuenta bancaria.', 'warning'); return; }
                var monto = parseFloat($('#pc_monto_pago').val()) || 0;
                if (monto <= 0) { Swal.fire('Atención', 'El monto debe ser mayor a cero.', 'warning'); return; }

                var estudianteId = $('#pc_estudiante_id').val();
                var formData = new FormData(document.getElementById('formPagarContabilidad'));
                cuotaIds.forEach(function (id) { formData.append('cuota_ids[]', id); });
                var btn = $(this);
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Registrando...');
                $.ajax({
                    url: '/admin/estudiantes/' + estudianteId + '/pagar-multiples-cuotas',
                    type: 'POST', data: formData, processData: false, contentType: false,
                    success: function (r) {
                        if (r.success) {
                            Swal.fire({
                                icon: 'success', title: '¡Pago Registrado!',
                                html: '<b>Recibo:</b> ' + r.recibo +
                                      '<br><a href="' + r.pdf_url + '" target="_blank" class="btn btn-sm btn-primary mt-2">' +
                                      '<i class="ri-download-line me-1"></i>Descargar Recibo PDF</a>',
                                confirmButtonText: 'Cerrar'
                            }).then(function () { $('#modalPagarContabilidad').modal('hide'); location.reload(); });
                        } else { Swal.fire('Error', r.msg || 'No se pudo registrar el pago.', 'error'); }
                    },
                    error: function (xhr) {
                        var msg = 'Error al registrar el pago.';
                        if (xhr.responseJSON) msg = xhr.responseJSON.msg || xhr.responseJSON.message || msg;
                        Swal.fire('Error', msg, 'error');
                    },
                    complete: function () { btn.prop('disabled', false).html('<i class="ri-checkbox-circle-line me-1"></i>Registrar Pago'); }
                });
            });

            // ── Botón Recibos ────────────────────────────────────────────────
            $(document).on('click', '.btn-ver-recibos', function () {
                var cuotaId     = $(this).data('cuota-id');
                var cuotaNombre = $(this).data('cuota-nombre');
                $('#modalRecibosTitle').text('Recibos de: ' + cuotaNombre);
                $('#modalRecibosCuota').modal('show');
                $('#contenidoRecibos').html('<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Cargando...</p></div>');
                $.ajax({
                    url: '/admin/estudiantes/cuota/' + cuotaId + '/recibos',
                    type: 'GET',
                    success: function (r) {
                        $('#contenidoRecibos').html(r.success ? r.html : '<div class="alert alert-danger">' + (r.msg || 'Error') + '</div>');
                    },
                    error: function () { $('#contenidoRecibos').html('<div class="alert alert-danger">Error al cargar los recibos.</div>'); }
                });
            });

            // ── Ver detalle de pago (historial) ──────────────────────────────
            $(document).on('click', '.btn-ver-detalle-pago', function () {
                var pagoId = $(this).data('pago-id');
                $('#modalDetallePago').modal('show');
                $('#contenidoDetallePago').html(
                    '<div class="text-center py-5">' +
                    '<div class="spinner-border text-primary"></div>' +
                    '<p class="mt-2 text-muted small">Cargando...</p></div>'
                );

                $.ajax({
                    url: '/admin/estudiantes/pago/' + pagoId + '/detalle',
                    type: 'GET',
                    success: function (r) {
                        if (!r.success) {
                            $('#contenidoDetallePago').html('<div class="alert alert-danger">' + (r.msg || 'Error') + '</div>');
                            return;
                        }
                        var p       = r.pago;
                        var cuotas  = r.cuotas;
                        var est     = r.estudiante;
                        var monto   = parseFloat(p.pago_bs || 0);
                        var desc    = parseFloat(p.descuento_bs || 0);
                        var neto    = monto - desc;

                        var tipoBadge = { 'Efectivo':'bg-success', 'Transferencia':'bg-info', 'Depósito':'bg-primary', 'Tarjeta':'bg-warning text-dark' };
                        var tbCls = tipoBadge[p.tipo_pago] || 'bg-secondary';

                        var fecha = p.fecha_pago
                            ? new Date(p.fecha_pago).toLocaleString('es-ES', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' })
                            : '—';

                        // Estudiante banner
                        var estHtml = est
                            ? '<div class="px-4 py-2 border-bottom d-flex align-items-center gap-2" style="background:#f0f5ff;">' +
                              '<i class="ri-user-line text-primary"></i>' +
                              '<span class="small fw-medium">' + est.persona.nombres + ' ' + est.persona.apellido_paterno + '</span>' +
                              '<span class="badge bg-secondary ms-auto rounded-pill" style="font-size:.7rem;">' + est.persona.carnet + '</span>' +
                              '</div>'
                            : '';

                        // Cuotas rows
                        var cuotasRows = '';
                        cuotas.forEach(function (c) {
                            cuotasRows +=
                                '<tr>' +
                                '<td class="text-center"><span class="badge bg-light text-dark border">' + c.cuota.n_cuota + '</span></td>' +
                                '<td class="fw-medium small">' + c.cuota.nombre + '</td>' +
                                '<td class="text-end fw-semibold text-success">' + parseFloat(c.pago_bs).toFixed(2) + ' Bs</td>' +
                                '</tr>';
                        });

                        var html =
                            estHtml +
                            '<div class="p-4">' +

                            // Recibo + fecha destacados
                            '<div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">' +
                            '<div>' +
                            '<div class="text-muted" style="font-size:.7rem;">NÚMERO DE RECIBO</div>' +
                            '<div class="fw-bold fs-5 text-primary">' + p.recibo + '</div>' +
                            '</div>' +
                            '<div class="text-end">' +
                            '<div class="text-muted" style="font-size:.7rem;">FECHA</div>' +
                            '<div class="fw-medium small">' + fecha + '</div>' +
                            '</div>' +
                            '</div>' +

                            // Info de pago + montos en 2 cols
                            '<div class="row g-3 mb-4">' +

                            // Tipo + comprobante
                            '<div class="col-md-6">' +
                            '<div class="rounded-3 border p-3 h-100">' +
                            '<div class="text-muted mb-2" style="font-size:.7rem;">TIPO DE PAGO</div>' +
                            '<span class="badge ' + tbCls + ' rounded-pill px-3 py-2 fs-13">' +
                            '<i class="ri-money-dollar-circle-line me-1"></i>' + p.tipo_pago +
                            '</span>' +
                            (p.n_comprobante
                                ? '<div class="mt-3"><div class="text-muted" style="font-size:.7rem;">N° COMPROBANTE</div>' +
                                  '<div class="fw-medium small">' + p.n_comprobante + '</div></div>'
                                : '') +
                            '</div>' +
                            '</div>' +

                            // Resumen financiero
                            '<div class="col-md-6">' +
                            '<div class="rounded-3 border p-3 h-100">' +
                            '<div class="text-muted mb-2" style="font-size:.7rem;">RESUMEN FINANCIERO</div>' +
                            '<div class="d-flex justify-content-between small mb-1">' +
                            '<span class="text-muted">Monto cobrado</span>' +
                            '<span class="fw-semibold text-success">' + monto.toFixed(2) + ' Bs</span>' +
                            '</div>' +
                            (desc > 0
                                ? '<div class="d-flex justify-content-between small mb-1">' +
                                  '<span class="text-muted">Descuento</span>' +
                                  '<span class="fw-semibold text-warning">-' + desc.toFixed(2) + ' Bs</span>' +
                                  '</div>'
                                : '') +
                            '<div class="d-flex justify-content-between small pt-2 border-top mt-2">' +
                            '<span class="fw-semibold">Total neto</span>' +
                            '<span class="fw-bold text-primary">' + neto.toFixed(2) + ' Bs</span>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +

                            // Cuotas pagadas
                            '<div class="mb-1"><div class="text-muted" style="font-size:.7rem;">CUOTAS INCLUIDAS EN ESTE PAGO</div></div>' +
                            '<div class="table-responsive">' +
                            '<table class="table table-hover align-middle mb-0" style="font-size:.84rem;">' +
                            '<thead><tr style="background:#f8f9fa;">' +
                            '<th width="8%" class="border-0 py-2 text-center text-muted fw-semibold" style="font-size:.7rem;">#</th>' +
                            '<th class="border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">CUOTA</th>' +
                            '<th width="20%" class="border-0 py-2 text-end text-muted fw-semibold" style="font-size:.7rem;">MONTO</th>' +
                            '</tr></thead>' +
                            '<tbody>' + cuotasRows + '</tbody>' +
                            '<tfoot><tr style="background:#f8f9fa;">' +
                            '<td colspan="2" class="text-end fw-semibold text-muted small py-2">Total:</td>' +
                            '<td class="text-end fw-bold text-success py-2">' + monto.toFixed(2) + ' Bs</td>' +
                            '</tr></tfoot>' +
                            '</table></div>' +
                            '</div>';

                        $('#contenidoDetallePago').html(html);
                        // Actualizar botón descarga en footer
                        $('#btnDescargarRecibo').attr('href', '/admin/estudiantes/descargar-recibo/' + pagoId);
                    },
                    error: function () {
                        $('#contenidoDetallePago').html('<div class="alert alert-danger m-3">Error al cargar los detalles del pago.</div>');
                    }
                });
            });

            // Imprimir detalle de pago
            $('#btnImprimirDetalle').on('click', function () {
                var contenido = $('#contenidoDetallePago').html();
                var ventana = window.open('', '_blank');
                ventana.document.write('<html><head><title>Detalle de Pago</title>' +
                    '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">' +
                    '</head><body class="p-4">' + contenido + '</body></html>');
                ventana.document.close();
                ventana.print();
            });

        }); // end ready
    </script>
@endpush
{{-- modales inline eliminados: ahora se incluyen via @include arriba --}}
{{-- @push('modales')
    <!-- Modal para Pagar Cuota -->
    <div class="modal fade" id="modalPagarCuota" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formPagarCuota">
                    @csrf
                    <input type="hidden" id="cuota_id" name="cuota_id">
                    <input type="hidden" id="estudiante_id" name="estudiante_id">

                    <div class="modal-body">
                        <!-- Contenido del modal -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Cuota:</strong> <span id="info-cuota-nombre"></span>
                                            </p>
                                            <p class="mb-1"><strong>Programa:</strong> <span
                                                    id="info-cuota-programa"></span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Total Cuota:</strong> <span id="info-cuota-total"
                                                    class="text-primary"></span> Bs</p>
                                            <p class="mb-1"><strong>Saldo Pendiente:</strong> <span
                                                    id="info-cuota-pendiente" class="text-danger"></span> Bs</p>
                                            <p class="mb-0"><strong>Saldo Pagado:</strong> <span id="info-cuota-pagado"
                                                    class="text-success"></span> Bs</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="monto_pago" class="form-label">Monto a Pagar (Bs) *</label>
                                    <input type="number" step="0.01" class="form-control" id="monto_pago"
                                        name="monto_pago" required>
                                    <div class="form-text">Máximo: <span id="maximo_pago"
                                            class="text-danger fw-bold">0.00</span> Bs</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="descuento" class="form-label">Descuento (Bs)</label>
                                    <input type="number" step="0.01" class="form-control" id="descuento"
                                        name="descuento" value="0">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tipo_pago" class="form-label">Tipo de Pago *</label>
                                    <select class="form-select" id="tipo_pago" name="tipo_pago" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Depósito">Depósito</option>
                                        <option value="Tarjeta">Tarjeta</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_pago" class="form-label">Fecha de Pago *</label>
                                    <input type="date" class="form-control" id="fecha_pago" name="fecha_pago"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
                                </div>
                            </div>

                            <!-- Resumen del pago en tiempo real -->
                            <div class="col-12">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">Resumen del Pago</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <p class="mb-1 text-muted">Monto a Pagar</p>
                                                <h5 class="text-primary" id="resumen-monto">0.00 Bs</h5>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-1 text-muted">Descuento</p>
                                                <h5 class="text-warning" id="resumen-descuento">0.00 Bs</h5>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-1 text-muted">Total a Pagar</p>
                                                <h5 class="text-success" id="resumen-total">0.00 Bs</h5>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success" id="progreso-pago"
                                                        role="progressbar" style="width: 0%"></div>
                                                </div>
                                                <small class="text-muted mt-1 d-block text-center">
                                                    <span id="texto-progreso">0% del saldo pendiente</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Registrar Pago</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar recibo -->
    <div class="modal fade" id="modalReciboGenerado" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pago Registrado Exitosamente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="ri-checkbox-circle-line text-success display-4"></i>
                    </div>
                    <h4 class="text-success mb-3">¡Pago Registrado!</h4>
                    <p class="mb-1">Recibo N°: <strong id="recibo-numero"></strong></p>
                    <p class="mb-1">Monto: <strong id="recibo-monto"></strong> Bs</p>
                    <p class="mb-3">Fecha: <strong id="recibo-fecha"></strong></p>
                    <div class="alert alert-info">
                        <i class="ri-information-line me-1"></i>
                        El recibo se ha generado correctamente. Puede descargarlo ahora.
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="#" id="descargar-recibo" class="btn btn-primary" target="_blank">
                        <i class="ri-download-line me-1"></i> Descargar Recibo
                    </a>
                    <button type="button" class="btn btn-success" onclick="location.reload()">
                        <i class="ri-refresh-line me-1"></i> Actualizar Página
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush --}}
