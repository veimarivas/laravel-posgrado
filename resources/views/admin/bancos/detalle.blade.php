@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Breadcrumb y acciones -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('admin.bancos.listar') }}" class="btn btn-sm btn-light me-2">
                            <i class="ri-arrow-left-line"></i>
                        </a>
                        <h4 class="mb-0">Detalle del Banco</h4>
                    </div>
                    <div class="page-title-right">
                        @if (Auth::guard('web')->user()->can('bancos.editar'))
                            <a href="javascript:void(0)" class="btn btn-warning me-2 editBtn" data-bs-toggle="modal"
                                data-bs-target="#modalModificar" data-bs-obj='@json($banco)'>
                                <i class="ri-edit-line me-1"></i> Editar
                            </a>
                        @endif
                        <a href="{{ route('admin.bancos.listar') }}" class="btn btn-secondary">
                            <i class="ri-list-check me-1"></i> Volver a Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información principal del banco -->
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            @if ($banco->logo)
                                <img src="{{ $banco->logo }}" alt="Logo {{ $banco->nombre }}" class="img-fluid rounded"
                                    style="max-height: 120px;">
                            @else
                                <div class="avatar-lg mx-auto" style="background-color: {{ $banco->color ?? '#0d6efd' }};">
                                    <div class="avatar-title text-white fs-2">
                                        {{ substr($banco->nombre, 0, 1) }}
                                    </div>
                                </div>
                            @endif
                            <h3 class="mt-3 mb-1">{{ $banco->nombre }}</h3>
                            <p class="text-muted">Código: <span class="badge bg-secondary">{{ $banco->codigo }}</span></p>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td><strong>ID:</strong></td>
                                        <td>{{ $banco->id }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Color:</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded me-2"
                                                    style="width: 20px; height: 20px; background-color: {{ $banco->color ?? '#0d6efd' }};">
                                                </div>
                                                <span>{{ $banco->color ?? 'Sin color' }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fecha Registro:</strong></td>
                                        <td>{{ $banco->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Última Actualización:</strong></td>
                                        <td>{{ $banco->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas rápidas -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Estadísticas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="text-primary mb-1">{{ $estadisticas['total_cuentas'] }}</h3>
                                    <p class="text-muted mb-0">Cuentas Totales</p>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="text-success mb-1">{{ $estadisticas['cuentas_activas'] }}</h3>
                                    <p class="text-muted mb-0">Cuentas Activas</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="text-warning mb-1">{{ $estadisticas['cuentas_inactivas'] }}</h3>
                                    <p class="text-muted mb-0">Cuentas Inactivas</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="text-info mb-1">{{ number_format($estadisticas['total_pagos'], 0) }}</h3>
                                    <p class="text-muted mb-0">Pagos Registrados</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <!-- Cuentas por moneda -->
                @foreach ($cuentas_por_moneda as $moneda => $cuentas)
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Cuentas en {{ $moneda }}</h5>
                            <span class="badge bg-primary">{{ $cuentas->count() }} cuentas</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>N° Cuenta</th>
                                            <th>Tipo</th>
                                            <th>Sucursal</th>
                                            <th>Saldo Actual</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cuentas as $cuenta)
                                            <tr>
                                                <td>
                                                    <strong>{{ $cuenta->numero_cuenta }}</strong>
                                                    @if ($cuenta->descripcion)
                                                        <br><small class="text-muted">{{ $cuenta->descripcion }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $cuenta->tipo_cuenta_formateado }}</td>
                                                <td>{{ $cuenta->sucursal->nombre ?? 'Sin sucursal' }}</td>
                                                <td>{!! $cuenta->saldo_actual_formateado !!}</td>
                                                <td>{!! $cuenta->estado_badge !!}</td>
                                                <td>
                                                    <a href="{{ route('admin.cuentas-bancarias.ver', ['id' => $cuenta->id]) }}"
                                                        class="btn btn-sm btn-info" title="Ver detalles de cuenta">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Saldo total por moneda -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Resumen de Saldos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($cuentas_por_moneda as $moneda => $cuentas)
                                <div class="col-md-4">
                                    <div class="card border-primary mb-3">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $moneda }}</h5>
                                            @php
                                                $saldo_total = $cuentas->sum('saldo_actual');
                                                $simbolo = $moneda == 'USD' ? '$' : 'Bs';
                                            @endphp
                                            <h3 class="card-text {{ $saldo_total >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $simbolo }} {{ number_format($saldo_total, 2) }}
                                            </h3>
                                            <p class="text-muted">{{ $cuentas->count() }} cuenta(s)</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Información Adicional</h5>
                    </div>
                    <div class="card-body">
                        @if ($banco->logo)
                            <div class="mb-3">
                                <h6>Logo del Banco:</h6>
                                <img src="{{ $banco->logo }}" alt="Logo" class="img-thumbnail"
                                    style="max-height: 80px;">
                            </div>
                        @endif

                        @if ($estadisticas['total_cuentas'] == 0)
                            <div class="alert alert-info">
                                <i class="ri-information-line me-2"></i>
                                Este banco no tiene cuentas bancarias registradas.
                                <a href="{{ route('admin.cuentas-bancarias.listar') }}" class="alert-link">
                                    Registrar una cuenta bancaria
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .avatar-lg {
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            // Botón para editar desde la vista de detalle
            $('.editBtn').on('click', function() {
                var data = $(this).data('bs-obj');
                $('#bancoId').val(data.id);
                $('#nombre_edicion').val(data.nombre);
                $('#codigo_edicion').val(data.codigo);
                $('#color_edicion').val(data.color || '#0d6efd');
                $('#colorPreviewEdicion').css('background-color', data.color || '#0d6efd');
                $('#logo_edicion').val(data.logo || '');

                // Resetear feedback
                $('#feedback_nombre_edicion, #feedback_codigo_edicion').removeClass(
                    'text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
            });

            // Actualizar y redirigir a la misma vista
            $('#updateForm').submit(function(e) {
                e.preventDefault();
                const submitBtn = $('.updateBtn');
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Actualizando...');

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('admin.bancos.modificar') }}",
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        if (res.success) {
                            showNotification('success', res.msg);
                            $('#modalModificar').modal('hide');
                            // Recargar la página para ver los cambios
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showNotification('error', res.msg);
                            submitBtn.prop('disabled', false).html(
                                '<i class="ri-save-line me-1"></i> Actualizar Banco');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al actualizar el banco.';
                        if (xhr.responseJSON?.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        showNotification('error', errorMsg);
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-save-line me-1"></i> Actualizar Banco');
                    }
                });
            });
        });
    </script>
@endpush
