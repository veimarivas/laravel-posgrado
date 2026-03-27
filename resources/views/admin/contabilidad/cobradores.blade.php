@extends('admin.dashboard')

@section('admin')
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">Reporte de Cobradores</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.contabilidad.buscar') }}">Contabilidad</a></li>
                            <li class="breadcrumb-item active">Cobradores</li>
                        </ol>
                    </div>
                </div>
                <a href="{{ route('admin.contabilidad.buscar') }}" class="btn btn-light btn-sm">
                    <i class="ri-arrow-left-line me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card border mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.contabilidad.cobradores') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label form-label-sm mb-1">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm"
                        value="{{ $fechaDesde }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-sm mb-1">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm"
                        value="{{ $fechaHasta }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-sm mb-1">Tipo de Pago</label>
                    <select name="tipo_pago" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="Efectivo"      {{ $tipoPago === 'Efectivo'      ? 'selected' : '' }}>💵 Efectivo</option>
                        <option value="Transferencia" {{ $tipoPago === 'Transferencia' ? 'selected' : '' }}>🏦 Transferencia</option>
                        <option value="Depósito"      {{ $tipoPago === 'Depósito'      ? 'selected' : '' }}>📥 Depósito</option>
                        <option value="Tarjeta"       {{ $tipoPago === 'Tarjeta'       ? 'selected' : '' }}>💳 Tarjeta</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="ri-filter-3-line me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('admin.contabilidad.cobradores') }}" class="btn btn-light btn-sm">
                        <i class="ri-refresh-line"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    @php
        $rangoLabel = $fechaDesde === $fechaHasta
            ? \Carbon\Carbon::parse($fechaDesde)->format('d/m/Y')
            : \Carbon\Carbon::parse($fechaDesde)->format('d/m/Y') . ' — ' . \Carbon\Carbon::parse($fechaHasta)->format('d/m/Y');
    @endphp
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-2">
            <div class="card border h-100 mb-0">
                <div class="card-body p-3 text-center">
                    <div class="avatar-sm mx-auto mb-2">
                        <div class="avatar-title bg-primary-subtle text-primary rounded">
                            <i class="ri-receipt-line fs-5"></i>
                        </div>
                    </div>
                    <h5 class="mb-0">{{ number_format($totales['pagos']) }}</h5>
                    <small class="text-muted">Pagos</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border border-primary h-100 mb-0">
                <div class="card-body p-3 text-center">
                    <div class="avatar-sm mx-auto mb-2">
                        <div class="avatar-title bg-primary text-white rounded">
                            <i class="ri-money-dollar-circle-line fs-5"></i>
                        </div>
                    </div>
                    <h5 class="mb-0 text-primary">{{ number_format($totales['total'], 2) }}</h5>
                    <small class="text-muted">Total Bs</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border border-success h-100 mb-0">
                <div class="card-body p-3 text-center">
                    <div class="avatar-sm mx-auto mb-2">
                        <div class="avatar-title bg-success text-white rounded">
                            <i class="ri-money-cny-circle-line fs-5"></i>
                        </div>
                    </div>
                    <h5 class="mb-0 text-success">{{ number_format($totales['efectivo'], 2) }}</h5>
                    <small class="text-muted">Efectivo</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border border-info h-100 mb-0">
                <div class="card-body p-3 text-center">
                    <div class="avatar-sm mx-auto mb-2">
                        <div class="avatar-title bg-info text-white rounded">
                            <i class="ri-bank-line fs-5"></i>
                        </div>
                    </div>
                    <h5 class="mb-0 text-info">{{ number_format($totales['transferencia'], 2) }}</h5>
                    <small class="text-muted">Transferencia</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border border-warning h-100 mb-0">
                <div class="card-body p-3 text-center">
                    <div class="avatar-sm mx-auto mb-2">
                        <div class="avatar-title bg-warning text-dark rounded">
                            <i class="ri-inbox-archive-line fs-5"></i>
                        </div>
                    </div>
                    <h5 class="mb-0 text-warning">{{ number_format($totales['deposito'], 2) }}</h5>
                    <small class="text-muted">Depósito</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border border-secondary h-100 mb-0">
                <div class="card-body p-3 text-center">
                    <div class="avatar-sm mx-auto mb-2">
                        <div class="avatar-title bg-secondary text-white rounded">
                            <i class="ri-bank-card-line fs-5"></i>
                        </div>
                    </div>
                    <h5 class="mb-0 text-secondary">{{ number_format($totales['tarjeta'], 2) }}</h5>
                    <small class="text-muted">Tarjeta</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de cobradores -->
    <div class="card border">
        <div class="card-header border-bottom bg-light d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fs-16">
                <i class="ri-user-star-line me-2 text-primary"></i>Cobros por Trabajador
            </h5>
            <span class="badge bg-light text-dark border">
                <i class="ri-calendar-line me-1"></i>{{ $rangoLabel }}
            </span>
        </div>
        <div class="card-body p-0">
            @if ($cobradores->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Cobrador</th>
                                <th>Cargo</th>
                                <th class="text-center">Pagos</th>
                                <th class="text-end">Efectivo (Bs)</th>
                                <th class="text-end">Transferencia (Bs)</th>
                                <th class="text-end">Depósito (Bs)</th>
                                <th class="text-end">Tarjeta (Bs)</th>
                                <th class="text-end fw-bold">Total (Bs)</th>
                                <th class="text-center">Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cobradores as $i => $cobrador)
                                <tr>
                                    <td class="ps-3 text-muted">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-xs flex-shrink-0">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-6">
                                                    {{ strtoupper(substr($cobrador->nombre_completo, 0, 1)) }}
                                                </div>
                                            </div>
                                            <span class="fw-medium">{{ $cobrador->nombre_completo }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-secondary-subtle text-secondary">{{ $cobrador->cargo }}</span></td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary">{{ $cobrador->total_pagos }}</span>
                                    </td>
                                    <td class="text-end">
                                        @if ($cobrador->efectivo_bs > 0)
                                            <span class="text-success fw-medium">{{ number_format($cobrador->efectivo_bs, 2) }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if ($cobrador->transferencia_bs > 0)
                                            <span class="text-info fw-medium">{{ number_format($cobrador->transferencia_bs, 2) }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if ($cobrador->deposito_bs > 0)
                                            <span class="text-warning fw-medium">{{ number_format($cobrador->deposito_bs, 2) }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if ($cobrador->tarjeta_bs > 0)
                                            <span class="text-secondary fw-medium">{{ number_format($cobrador->tarjeta_bs, 2) }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-primary fs-6">{{ number_format($cobrador->total_bs, 2) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-ver-detalle"
                                            data-tc-id="{{ $cobrador->tc_id }}"
                                            data-nombre="{{ $cobrador->nombre_completo }}"
                                            data-fecha-desde="{{ $fechaDesde }}"
                                            data-fecha-hasta="{{ $fechaHasta }}"
                                            data-tipo-pago="{{ $tipoPago }}">
                                            <i class="ri-eye-line me-1"></i>Ver
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="3" class="ps-3">TOTALES</td>
                                <td class="text-center">{{ number_format($totales['pagos']) }}</td>
                                <td class="text-end text-success">{{ number_format($totales['efectivo'], 2) }}</td>
                                <td class="text-end text-info">{{ number_format($totales['transferencia'], 2) }}</td>
                                <td class="text-end text-warning">{{ number_format($totales['deposito'], 2) }}</td>
                                <td class="text-end text-secondary">{{ number_format($totales['tarjeta'], 2) }}</td>
                                <td class="text-end text-primary fs-6">{{ number_format($totales['total'], 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-light text-secondary rounded-circle">
                            <i class="ri-user-star-line fs-2"></i>
                        </div>
                    </div>
                    <h5 class="mb-1">Sin registros</h5>
                    <p class="text-muted mb-0">No hay cobros registrados para el periodo seleccionado.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal detalle cobrador -->
    <div class="modal fade" id="modalDetalleCobrador" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title mb-0">
                            <i class="ri-list-check-2 text-primary me-2"></i>
                            Detalle de cobros: <span id="detalleNombreCobrador"></span>
                        </h5>
                        <div class="text-muted small" id="detalleRangoCobrador"></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-2" id="contenidoDetalleCobrador">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary"></div>
                        <p class="mt-2 text-muted">Cargando...</p>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
$(document).ready(function () {

    $(document).on('click', '.btn-ver-detalle', function () {
        var tcId       = $(this).data('tc-id');
        var nombre     = $(this).data('nombre');
        var fechaDesde = $(this).data('fecha-desde');
        var fechaHasta = $(this).data('fecha-hasta');
        var tipoPago   = $(this).data('tipo-pago');
        var rangoLabel = fechaDesde === fechaHasta ? fechaDesde : fechaDesde + ' → ' + fechaHasta;

        $('#detalleNombreCobrador').text(nombre);
        $('#detalleRangoCobrador').text(rangoLabel + (tipoPago ? ' · ' + tipoPago : ''));
        $('#contenidoDetalleCobrador').html(
            '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Cargando...</p></div>'
        );
        $('#modalDetalleCobrador').modal('show');

        $.ajax({
            url: '/admin/contabilidad/cobradores/' + tcId + '/detalle',
            type: 'GET',
            data: { fecha_desde: fechaDesde, fecha_hasta: fechaHasta, tipo_pago: tipoPago },
            success: function (r) {
                if (!r.success || r.pagos.length === 0) {
                    $('#contenidoDetalleCobrador').html(
                        '<div class="alert alert-warning mb-0">No se encontraron pagos para este cobrador en el periodo seleccionado.</div>'
                    );
                    return;
                }

                var tipoBadge = {
                    'Efectivo':      'bg-success',
                    'Transferencia': 'bg-info',
                    'Depósito':      'bg-warning text-dark',
                    'Tarjeta':       'bg-secondary',
                };

                var totalMonto = 0;
                var rows = '';
                r.pagos.forEach(function (p, idx) {
                    totalMonto += parseFloat(p.pago_bs) || 0;
                    var badgeCls = tipoBadge[p.tipo_pago] || 'bg-secondary';
                    var fecha = p.fecha_pago ? p.fecha_pago.substring(0, 10).split('-').reverse().join('/') : '—';
                    rows += '<tr>' +
                        '<td class="ps-3 text-muted small">' + (idx + 1) + '</td>' +
                        '<td><span class="badge bg-primary-subtle text-primary">' + p.recibo + '</span></td>' +
                        '<td>' +
                            '<div class="fw-medium small">' + p.estudiante + '</div>' +
                            '<div class="text-muted" style="font-size:.75rem;">' + p.carnet + '</div>' +
                        '</td>' +
                        '<td class="small">' + p.programa + '</td>' +
                        '<td class="text-center"><span class="badge bg-secondary-subtle text-secondary">' + p.n_cuotas + '</span></td>' +
                        '<td class="text-center"><span class="badge ' + badgeCls + '">' + p.tipo_pago + '</span></td>' +
                        '<td class="text-end fw-bold text-primary">' + parseFloat(p.pago_bs).toFixed(2) + '</td>' +
                        '<td class="text-muted small">' + fecha + '</td>' +
                    '</tr>';
                });

                var html =
                    '<div class="table-responsive">' +
                    '<table class="table table-hover align-middle mb-0">' +
                    '<thead class="table-light">' +
                    '<tr>' +
                        '<th class="ps-3">#</th>' +
                        '<th>Recibo</th>' +
                        '<th>Estudiante</th>' +
                        '<th>Programa</th>' +
                        '<th class="text-center">Cuotas</th>' +
                        '<th class="text-center">Tipo</th>' +
                        '<th class="text-end">Monto (Bs)</th>' +
                        '<th>Fecha</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>' + rows + '</tbody>' +
                    '<tfoot class="table-light fw-bold">' +
                    '<tr>' +
                        '<td colspan="6" class="ps-3">TOTAL</td>' +
                        '<td class="text-end text-primary">' + totalMonto.toFixed(2) + ' Bs</td>' +
                        '<td></td>' +
                    '</tr>' +
                    '</tfoot>' +
                    '</table></div>';

                $('#contenidoDetalleCobrador').html(html);
            },
            error: function () {
                $('#contenidoDetalleCobrador').html(
                    '<div class="alert alert-danger mb-0">Error al cargar el detalle.</div>'
                );
            }
        });
    });

});
</script>
@endpush
