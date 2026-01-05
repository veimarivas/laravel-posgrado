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
    @include('admin.estudiantes.partials.modal-recibo-generado')
    @include('admin.estudiantes.partials.modal-recibos-cuota')
@endsection

@push('script')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts para pagos (reutilizar de estudiantes) -->
    @include('admin.estudiantes.partials.scripts-pagos')

    <script>
        $(document).ready(function() {
            // Asegurar que los acordeones funcionen
            $('.accordion-button').on('click', function() {
                $(this).toggleClass('collapsed');
            });
        });
    </script>
@endpush
