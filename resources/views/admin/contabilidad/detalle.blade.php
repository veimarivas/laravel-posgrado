@extends('admin.dashboard')

@section('admin')

    @php
        $persona = $estudiante->persona;
        $totalDeuda   = 0;
        $totalPagado  = 0;
        $totalCuotas  = 0;
        $cuotasPagTot = 0;
        $cuotasPenTot = 0;
        foreach ($estudiante->inscripciones as $ins) {
            foreach ($ins->cuotas as $c) {
                $totalCuotas++;
                $totalPagado += $c->pago_total_bs - $c->pago_pendiente_bs;
                $totalDeuda  += $c->pago_pendiente_bs;
                if ($c->pago_terminado == 'si') { $cuotasPagTot++; } else { $cuotasPenTot++; }
            }
        }
        $pctGlobal = ($totalPagado + $totalDeuda) > 0
            ? ($totalPagado / ($totalPagado + $totalDeuda)) * 100 : 0;
        $colorGlobal = match(true) {
            $pctGlobal == 100 => 'success',
            $pctGlobal >= 75  => 'primary',
            $pctGlobal >= 50  => 'warning',
            default           => 'danger',
        };
    @endphp

    {{-- Page title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">Detalle Contable</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.contabilidad.buscar') }}">Contabilidad</a></li>
                        <li class="breadcrumb-item active">{{ $persona->carnet ?? '' }}</li>
                    </ol>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.contabilidad.buscar') }}" class="btn btn-light btn-sm">
                        <i class="ri-arrow-left-line me-1"></i>Volver
                    </a>
                    <a href="{{ route('admin.estudiantes.detalle', $estudiante->id) }}" class="btn btn-info btn-sm">
                        <i class="ri-user-line me-1"></i>Ver Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Header del participante --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">

                {{-- Avatar + datos personales --}}
                <div class="col-md-7">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-lg flex-shrink-0">
                            <div class="avatar-title bg-primary text-white rounded-3 fw-bold fs-2">
                                {{ strtoupper(mb_substr($persona->nombres ?? 'P', 0, 1, 'UTF-8')) }}
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-semibold">
                                {{ $persona->nombres }}
                                {{ $persona->apellido_paterno }}
                                {{ $persona->apellido_materno }}
                            </h4>
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                <span class="badge bg-secondary rounded-pill">
                                    <i class="ri-id-card-line me-1"></i>{{ $persona->carnet }}
                                </span>
                                @if($persona->correo)
                                <span class="badge bg-info rounded-pill">
                                    <i class="ri-mail-line me-1"></i>{{ $persona->correo }}
                                </span>
                                @endif
                                @if($persona->celular)
                                <span class="badge bg-light text-dark border rounded-pill">
                                    <i class="ri-phone-line me-1"></i>{{ $persona->celular }}
                                </span>
                                @endif
                            </div>
                            <div class="text-muted small">
                                <i class="ri-map-pin-line me-1"></i>
                                {{ $persona->direccion ?? 'Sin dirección' }}
                                @if($persona->ciudad)
                                    · {{ $persona->ciudad->nombre ?? '' }},
                                    {{ optional($persona->ciudad->departamento)->nombre ?? '' }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Resumen financiero rápido --}}
                <div class="col-md-5">
                    <div class="rounded-3 border p-3" style="background:#f8f9fa;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-medium">Estado financiero general</span>
                            <span class="badge bg-{{ $colorGlobal }} rounded-pill">{{ number_format($pctGlobal, 1) }}%</span>
                        </div>
                        <div class="progress rounded-pill mb-3" style="height:8px;">
                            <div class="progress-bar bg-{{ $colorGlobal }}" style="width:{{ $pctGlobal }}%"></div>
                        </div>
                        <div class="row g-2 text-center">
                            <div class="col-4">
                                <div class="fw-bold text-success">{{ number_format($totalPagado, 2) }}</div>
                                <div class="text-muted" style="font-size:.7rem;">Bs Pagado</div>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold text-danger">{{ number_format($totalDeuda, 2) }}</div>
                                <div class="text-muted" style="font-size:.7rem;">Bs Pendiente</div>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold text-primary">{{ $totalCuotas }}</div>
                                <div class="text-muted" style="font-size:.7rem;">Cuotas</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Programas y cuotas --}}
    @if ($estudiante->inscripciones->count() > 0)

        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h5 class="mb-0 fw-semibold">Programas y Estado de Cuotas</h5>
                <p class="text-muted small mb-0">
                    {{ $estudiante->inscripciones->count() }} programa(s) ·
                    {{ $cuotasPagTot }} pagadas · {{ $cuotasPenTot }} pendientes
                </p>
            </div>
            <span class="badge bg-primary rounded-pill px-3 py-2 fs-12">
                <i class="ri-graduation-cap-line me-1"></i>{{ $estudiante->inscripciones->count() }} Programas
            </span>
        </div>

        <div class="accordion" id="accordionContable" style="display:flex; flex-direction:column; gap:.75rem;">
            @foreach ($estudiante->inscripciones->sortByDesc('fecha_registro') as $index => $inscripcion)
                @php
                    $oferta   = $inscripcion->ofertaAcademica;
                    $programa = $oferta->programa ?? null;
                    $cuotas   = $inscripcion->cuotas_ordenadas ?? $inscripcion->cuotas;

                    $deudaPrograma    = 0;
                    $pagadoPrograma   = 0;
                    $cuotasTotales    = $cuotas->count();
                    $cuotasPagadas    = 0;
                    $cuotasPendientes = 0;

                    foreach ($cuotas as $cuota) {
                        $deudaPrograma  += $cuota->pago_pendiente_bs;
                        $pagadoPrograma += $cuota->pago_total_bs - $cuota->pago_pendiente_bs;
                        if ($cuota->pago_terminado == 'si') { $cuotasPagadas++; } else { $cuotasPendientes++; }
                    }

                    $totalPrograma    = $deudaPrograma + $pagadoPrograma;
                    $porcentajePagado = $totalPrograma > 0 ? ($pagadoPrograma / $totalPrograma) * 100 : 0;
                    $cuotasPendientes = $cuotas->where('pago_terminado', '!=', 'si')->count();

                    $colorProg = match(true) {
                        $porcentajePagado == 100 => 'success',
                        $porcentajePagado >= 75  => 'primary',
                        $porcentajePagado >= 50  => 'warning',
                        default                  => 'danger',
                    };
                    $avatarColors = ['bg-primary','bg-success','bg-info','bg-warning','bg-danger'];
                    $avatarColor  = $avatarColors[$index % count($avatarColors)];
                    $inicial      = strtoupper(mb_substr($programa->nombre ?? 'P', 0, 1, 'UTF-8'));
                @endphp

                <div class="accordion-item border-0 rounded-3 overflow-hidden"
                     style="box-shadow:0 2px 8px rgba(0,0,0,.08); border-left:4px solid var(--bs-{{ $colorProg }}) !important;">

                    {{-- Header --}}
                    <h2 class="accordion-header" id="contableHeading{{ $index }}">
                        <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }} py-3 px-4"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#contableCollapse{{ $index }}"
                                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-controls="contableCollapse{{ $index }}"
                                style="background:transparent;">
                            <div class="d-flex align-items-center w-100 me-2 gap-3">

                                {{-- Avatar inicial --}}
                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title {{ $avatarColor }} text-white rounded-2 fw-bold fs-16">
                                        {{ $inicial }}
                                    </div>
                                </div>

                                {{-- Info principal --}}
                                <div class="flex-grow-1 min-w-0">
                                    <h6 class="mb-1 fw-semibold text-truncate">
                                        {{ $programa->nombre ?? 'Programa no especificado' }}
                                    </h6>
                                    <div class="d-flex flex-wrap align-items-center gap-3 text-muted" style="font-size:.77rem;">
                                        @if(optional($oferta->modalidad)->nombre)
                                        <span>
                                            <i class="ri-book-open-line me-1 text-primary"></i>
                                            {{ $oferta->modalidad->nombre }}
                                        </span>
                                        @endif
                                        @if(optional($oferta->sucursal)->nombre)
                                        <span>
                                            <i class="ri-map-pin-line me-1 text-primary"></i>
                                            {{ $oferta->sucursal->nombre }}
                                        </span>
                                        @endif
                                        <span>
                                            <i class="ri-file-list-3-line me-1 text-primary"></i>
                                            {{ optional($inscripcion->planesPago)->nombre ?? 'N/A' }}
                                        </span>
                                        <span>
                                            <i class="ri-stack-line me-1 text-primary"></i>
                                            {{ $cuotasPagadas }}/{{ $cuotasTotales }} cuotas
                                        </span>
                                    </div>
                                </div>

                                {{-- Derecha: montos + barra --}}
                                <div class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                                    <div class="d-flex gap-1">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size:.72rem;">
                                            {{ number_format($pagadoPrograma, 2) }} Bs
                                        </span>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size:.72rem;">
                                            {{ number_format($deudaPrograma, 2) }} Bs
                                        </span>
                                    </div>
                                    <div style="width:100px;">
                                        <div class="d-flex justify-content-between" style="font-size:.65rem;">
                                            <span class="text-muted">Avance</span>
                                            <span class="fw-semibold text-{{ $colorProg }}">{{ number_format($porcentajePagado, 0) }}%</span>
                                        </div>
                                        <div class="progress rounded-pill" style="height:5px;">
                                            <div class="progress-bar bg-{{ $colorProg }}" style="width:{{ $porcentajePagado }}%"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </button>
                    </h2>

                    {{-- Body --}}
                    <div id="contableCollapse{{ $index }}"
                         class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                         aria-labelledby="contableHeading{{ $index }}">
                        <div class="accordion-body p-0">

                            {{-- Barra "Pagar Múltiples" --}}
                            @if ($cuotasPendientes > 0)
                                <div class="px-4 py-2 border-top border-bottom d-flex align-items-center justify-content-between"
                                     style="background:#f0f8ff;">
                                    <span class="small text-muted">
                                        <i class="ri-list-check-2 me-1 text-primary"></i>
                                        <strong>{{ $cuotasPendientes }}</strong> cuota(s) con saldo pendiente
                                    </span>
                                    <button type="button"
                                            class="btn btn-sm btn-primary btn-pagar-multiple"
                                            data-estudiante-id="{{ $estudiante->id }}">
                                        <i class="ri-stack-line me-1"></i>Pagar Múltiples Cuotas
                                    </button>
                                </div>
                            @endif

                            {{-- Resumen financiero del programa --}}
                            <div class="px-4 py-3 bg-light border-bottom">
                                <div class="row g-2">
                                    <div class="col-4">
                                        <div class="text-muted" style="font-size:.7rem;">TOTAL PROGRAMA</div>
                                        <div class="fw-bold fs-6">{{ number_format($totalPrograma, 2) }} Bs</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-muted" style="font-size:.7rem;">PAGADO</div>
                                        <div class="fw-bold fs-6 text-success">{{ number_format($pagadoPrograma, 2) }} Bs</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-muted" style="font-size:.7rem;">PENDIENTE</div>
                                        <div class="fw-bold fs-6 text-danger">{{ number_format($deudaPrograma, 2) }} Bs</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tabla de cuotas --}}
                            <div class="px-3 py-3">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" style="font-size:.84rem;">
                                        <thead>
                                            <tr style="background:#f8f9fa;">
                                                <th width="5%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">#</th>
                                                <th width="26%" class="border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">CUOTA</th>
                                                <th width="12%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">TOTAL</th>
                                                <th width="12%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">PAGADO</th>
                                                <th width="12%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">PENDIENTE</th>
                                                <th width="13%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">FECHA</th>
                                                <th width="11%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">ESTADO</th>
                                                <th width="9%" class="text-center border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">ACCIÓN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cuotas as $cuota)
                                                @php
                                                    $pagado       = $cuota->pago_total_bs - $cuota->pago_pendiente_bs;
                                                    $tienePagos   = $cuota->pagos_cuotas->count() > 0;
                                                    $pctCuota     = $cuota->pago_total_bs > 0
                                                        ? ($pagado / $cuota->pago_total_bs) * 100 : 0;
                                                @endphp
                                                <tr>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-dark border">{{ $cuota->n_cuota }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="fw-medium">{{ $cuota->nombre }}</div>
                                                        @if ($tienePagos)
                                                            <div class="text-muted" style="font-size:.72rem;">
                                                                <i class="ri-receipt-line me-1"></i>{{ $cuota->pagos_cuotas->count() }} pago(s)
                                                            </div>
                                                        @endif
                                                        <div class="progress mt-1 rounded-pill" style="height:3px;">
                                                            <div class="progress-bar {{ $cuota->pago_terminado == 'si' ? 'bg-success' : 'bg-warning' }}"
                                                                 style="width:{{ $pctCuota }}%"></div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center fw-medium">{{ number_format($cuota->pago_total_bs, 2) }}</td>
                                                    <td class="text-center">
                                                        <span class="text-success fw-medium">{{ number_format($pagado, 2) }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($cuota->pago_pendiente_bs > 0)
                                                            <span class="text-danger fw-medium">{{ number_format($cuota->pago_pendiente_bs, 2) }}</span>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center text-muted small">
                                                        @if ($cuota->fecha_pago)
                                                            {{ \Carbon\Carbon::parse($cuota->fecha_pago)->format('d/m/Y') }}
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($cuota->pago_terminado == 'si')
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                                <i class="ri-check-line me-1"></i>Pagado
                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                                                <i class="ri-time-line me-1"></i>Pendiente
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center gap-1">
                                                            @if ($cuota->pago_pendiente_bs > 0)
                                                                <button class="btn btn-sm btn-success btn-pagar-cuota"
                                                                        data-cuota-id="{{ $cuota->id }}"
                                                                        data-estudiante-id="{{ $estudiante->id }}"
                                                                        title="Pagar cuota"
                                                                        style="padding:.2rem .5rem;">
                                                                    <i class="ri-money-dollar-circle-line"></i>
                                                                </button>
                                                            @endif
                                                            @if ($tienePagos)
                                                                <button class="btn btn-sm btn-info btn-ver-recibos"
                                                                        data-cuota-id="{{ $cuota->id }}"
                                                                        data-cuota-nombre="{{ $cuota->nombre }}"
                                                                        title="Ver recibos ({{ $cuota->pagos_cuotas->count() }})"
                                                                        style="padding:.2rem .5rem;">
                                                                    <i class="ri-receipt-line"></i>
                                                                    <span class="badge bg-white text-info ms-1" style="font-size:.65rem;">{{ $cuota->pagos_cuotas->count() }}</span>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="background:#f8f9fa;">
                                                <td colspan="2" class="text-end fw-semibold text-muted small py-2">Totales del programa:</td>
                                                <td class="text-center fw-bold py-2">{{ number_format($totalPrograma, 2) }}</td>
                                                <td class="text-center fw-bold text-success py-2">{{ number_format($pagadoPrograma, 2) }}</td>
                                                <td class="text-center fw-bold text-danger py-2">{{ number_format($deudaPrograma, 2) }}</td>
                                                <td colspan="3"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Footer resumen general --}}
        <div class="mt-3 p-3 rounded-3 border bg-light d-flex flex-wrap gap-4">
            <div class="d-flex align-items-center gap-2">
                <div class="avatar-xs">
                    <div class="avatar-title bg-primary-subtle text-primary rounded">
                        <i class="ri-money-dollar-circle-line fs-14"></i>
                    </div>
                </div>
                <div>
                    <div class="fw-semibold lh-1">{{ number_format($totalPagado + $totalDeuda, 2) }} Bs</div>
                    <div class="text-muted" style="font-size:.72rem;">Monto Total General</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="avatar-xs">
                    <div class="avatar-title bg-success-subtle text-success rounded">
                        <i class="ri-checkbox-circle-line fs-14"></i>
                    </div>
                </div>
                <div>
                    <div class="fw-semibold lh-1 text-success">{{ number_format($totalPagado, 2) }} Bs</div>
                    <div class="text-muted" style="font-size:.72rem;">Total Pagado</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="avatar-xs">
                    <div class="avatar-title bg-danger-subtle text-danger rounded">
                        <i class="ri-alert-line fs-14"></i>
                    </div>
                </div>
                <div>
                    <div class="fw-semibold lh-1 text-danger">{{ number_format($totalDeuda, 2) }} Bs</div>
                    <div class="text-muted" style="font-size:.72rem;">Total Deuda</div>
                </div>
            </div>
        </div>

    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="avatar-lg mx-auto mb-3">
                    <div class="avatar-title bg-light text-secondary rounded-circle">
                        <i class="ri-graduation-cap-line fs-2"></i>
                    </div>
                </div>
                <h5 class="mb-2">No hay programas inscritos</h5>
                <p class="text-muted mb-0">El participante no está inscrito en ningún programa.</p>
            </div>
        </div>
    @endif

    {{-- Modales --}}
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
