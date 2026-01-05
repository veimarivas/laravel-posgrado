@extends('admin.dashboard')

@section('admin')
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">Área Contable - Búsqueda de Participantes</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Contabilidad</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Búsqueda por carnet -->
    <div class="card border">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="text-center mb-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <i class="ri-search-line fs-2"></i>
                            </div>
                        </div>
                        <h5 class="mb-2">Buscar Participante</h5>
                        <p class="text-muted mb-4">Ingrese el número de carnet del participante para ver su detalle
                            financiero</p>
                    </div>

                    <div class="mb-3">
                        <label for="carnet" class="form-label">Carnet de Identidad</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="carnet" placeholder="Ej: 1234567" autofocus>
                            <button class="btn btn-primary" type="button" id="btnBuscarCarnet">
                                <i class="ri-search-line me-1"></i> Buscar
                            </button>
                        </div>
                        <div class="form-text">Ingrese el número completo del carnet</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resultado de búsqueda -->
    <div id="resultadoBusqueda" class="mt-3">
        <!-- Aquí se cargará el resultado -->
    </div>

    <!-- Alertas -->
    <div id="alertContainer" class="mt-3"></div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Buscar por carnet
            $('#btnBuscarCarnet').on('click', function() {
                var carnet = $('#carnet').val().trim();

                if (!carnet) {
                    mostrarAlerta('Por favor ingrese un número de carnet', 'warning');
                    return;
                }

                // Mostrar loading
                $('#resultadoBusqueda').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Buscando...</span>
                    </div>
                    <p class="mt-3">Buscando participante...</p>
                </div>
            `);

                $.ajax({
                    url: "{{ route('admin.contabilidad.verificar-carnet') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        carnet: carnet
                    },
                    success: function(response) {
                        if (response.success) {
                            // Mostrar información resumida y botón para ver detalle
                            $('#resultadoBusqueda').html(`
                            <div class="card border border-primary">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-9">
                                            <h5 class="mb-1">${response.estudiante.nombre_completo}</h5>
                                            <div class="row mt-3">
                                                <div class="col-md-4">
                                                    <p class="mb-1"><strong>Carnet:</strong> ${response.estudiante.carnet}</p>
                                                    <p class="mb-1"><strong>Correo:</strong> ${response.estudiante.correo || 'N/A'}</p>
                                                    <p class="mb-0"><strong>Celular:</strong> ${response.estudiante.celular || 'N/A'}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="mb-1"><strong>Programas:</strong> ${response.estudiante.total_programas}</p>
                                                    <p class="mb-1"><strong>Pagado:</strong> <span class="text-success">${formatMoney(response.estudiante.total_pagado)} Bs</span></p>
                                                    <p class="mb-0"><strong>Deuda:</strong> <span class="text-danger">${formatMoney(response.estudiante.total_deuda)} Bs</span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-end">
                                            <a href="${response.redirect}" class="btn btn-primary btn-lg">
                                                <i class="ri-eye-line me-2"></i> Ver Detalle Completo
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                        } else {
                            $('#resultadoBusqueda').html('');
                            mostrarAlerta(response.msg, 'danger');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        $('#resultadoBusqueda').html('');
                        mostrarAlerta('Error al buscar el carnet', 'danger');
                    }
                });
            });

            // Permitir buscar al presionar Enter
            $('#carnet').on('keypress', function(e) {
                if (e.which === 13) {
                    $('#btnBuscarCarnet').click();
                }
            });

            function formatMoney(amount) {
                return parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }

            function mostrarAlerta(mensaje, tipo) {
                $('#alertContainer').html(`
                <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                    <i class="ri-alert-line me-2"></i> ${mensaje}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);

                // Auto-ocultar después de 5 segundos
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 5000);
            }
        });
    </script>
@endpush
