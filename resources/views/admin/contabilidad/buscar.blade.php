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
                <a href="{{ route('admin.contabilidad.cobradores') }}" class="btn btn-primary btn-sm">
                    <i class="ri-user-star-line me-1"></i>Reporte de Cobradores
                </a>
            </div>
        </div>
    </div>

    <!-- Búsqueda por carnet -->
    <div class="card border-0 shadow-sm">
        <div class="card-body py-4">
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-5">
                    <div class="text-center mb-4">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mx-auto mb-3"
                             style="width:64px;height:64px;">
                            <i class="ri-search-line fs-2"></i>
                        </div>
                        <h5 class="mb-1 fw-semibold">Buscar Participante</h5>
                        <p class="text-muted mb-0" style="font-size:.85rem;">
                            Escriba el carnet o nombre del participante — los resultados aparecen en tiempo real
                        </p>
                    </div>

                    <div class="mb-2">
                        <label for="carnet" class="form-label fw-semibold" style="font-size:.85rem;">
                            Carnet o Nombre
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ri-id-card-line text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0"
                                   id="carnet" placeholder="Ej: 1234567 o Juan Pérez" autofocus autocomplete="off">
                            <span class="input-group-text bg-white border-start-0" id="searchSpinner" style="display:none;">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            </span>
                            <button class="btn btn-primary" type="button" id="btnBuscarCarnet">
                                <i class="ri-search-line"></i>
                            </button>
                        </div>
                        <div class="form-text" id="searchHint">
                            <i class="ri-information-line me-1"></i>Escriba al menos 3 caracteres para buscar automáticamente
                        </div>
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
            let debounceTimer = null;
            let lastQuery = '';

            // ── Búsqueda en tiempo real ──────────────────────────────────
            $('#carnet').on('input', function() {
                const query = $(this).val().trim();

                clearTimeout(debounceTimer);
                $('#alertContainer').html('');

                if (query === lastQuery) return;

                if (query.length < 3) {
                    if (query.length === 0) {
                        $('#resultadoBusqueda').html('');
                        lastQuery = '';
                    }
                    $('#searchHint').html('<i class="ri-information-line me-1"></i>Escriba al menos 3 caracteres para buscar automáticamente');
                    return;
                }

                $('#searchHint').html('<i class="ri-time-line me-1"></i>Buscando...');

                debounceTimer = setTimeout(function() {
                    ejecutarBusqueda(query);
                }, 400);
            });

            // ── Botón buscar / Enter ──────────────────────────────────────
            $('#btnBuscarCarnet').on('click', function() {
                const query = $('#carnet').val().trim();
                if (!query) {
                    mostrarAlerta('Por favor ingrese un carnet o nombre', 'warning');
                    return;
                }
                clearTimeout(debounceTimer);
                ejecutarBusqueda(query);
            });

            $('#carnet').on('keypress', function(e) {
                if (e.which === 13) {
                    clearTimeout(debounceTimer);
                    const query = $(this).val().trim();
                    if (query) ejecutarBusqueda(query);
                }
            });

            // ── Función principal de búsqueda ─────────────────────────────
            function ejecutarBusqueda(query) {
                lastQuery = query;

                $('#searchSpinner').show();
                $('#btnBuscarCarnet').prop('disabled', true);

                $('#resultadoBusqueda').html(`
                    <div class="text-center py-4 text-muted" style="font-size:.88rem;">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                        Buscando <strong>${query}</strong>...
                    </div>
                `);

                $.ajax({
                    url: "{{ route('admin.contabilidad.verificar-carnet') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        carnet: query
                    },
                    success: function(response) {
                        $('#searchSpinner').hide();
                        $('#btnBuscarCarnet').prop('disabled', false);
                        $('#searchHint').html('<i class="ri-check-line me-1 text-success"></i>Búsqueda completada');

                        if (response.success) {
                            const e = response.estudiante;
                            const deudaColor = parseFloat(e.total_deuda) > 0 ? 'danger' : 'success';

                            $('#resultadoBusqueda').html(`
                                <div class="card border-0 shadow-sm" style="border-left:4px solid #0d6efd!important;">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary fw-bold flex-shrink-0"
                                                     style="width:52px;height:52px;font-size:1.2rem;">
                                                    ${e.nombre_completo.charAt(0).toUpperCase()}
                                                </div>
                                                <div>
                                                    <h5 class="mb-0 fw-bold">${e.nombre_completo}</h5>
                                                    <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill" style="font-size:.72rem;">
                                                            <i class="ri-id-card-line me-1"></i>${e.carnet}
                                                        </span>
                                                        ${e.correo ? `<span class="text-muted" style="font-size:.82rem;"><i class="ri-mail-line me-1"></i>${e.correo}</span>` : ''}
                                                        ${e.celular ? `<span class="text-muted" style="font-size:.82rem;"><i class="ri-phone-line me-1"></i>${e.celular}</span>` : ''}
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="${response.redirect}" class="btn btn-primary">
                                                <i class="ri-eye-line me-1"></i>Ver Detalle
                                            </a>
                                        </div>

                                        <hr class="my-3">

                                        <div class="row g-3">
                                            <div class="col-sm-4">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="d-flex align-items-center justify-content-center rounded bg-info-subtle text-info flex-shrink-0" style="width:36px;height:36px;">
                                                        <i class="ri-book-2-line"></i>
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 text-muted" style="font-size:.72rem;text-transform:uppercase;font-weight:600;">Programas</p>
                                                        <span class="fw-semibold">${e.total_programas}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="d-flex align-items-center justify-content-center rounded bg-success-subtle text-success flex-shrink-0" style="width:36px;height:36px;">
                                                        <i class="ri-money-dollar-circle-line"></i>
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 text-muted" style="font-size:.72rem;text-transform:uppercase;font-weight:600;">Total Pagado</p>
                                                        <span class="fw-semibold text-success">${formatMoney(e.total_pagado)} Bs</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="d-flex align-items-center justify-content-center rounded bg-${deudaColor}-subtle text-${deudaColor} flex-shrink-0" style="width:36px;height:36px;">
                                                        <i class="ri-bill-line"></i>
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 text-muted" style="font-size:.72rem;text-transform:uppercase;font-weight:600;">Deuda Pendiente</p>
                                                        <span class="fw-semibold text-${deudaColor}">${formatMoney(e.total_deuda)} Bs</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
                        } else {
                            $('#resultadoBusqueda').html(`
                                <div class="text-center py-4 text-muted">
                                    <i class="ri-search-line fs-2 d-block mb-2"></i>
                                    <span style="font-size:.88rem;">${response.msg || 'No se encontró ningún participante con ese dato'}</span>
                                </div>
                            `);
                        }
                    },
                    error: function(xhr) {
                        $('#searchSpinner').hide();
                        $('#btnBuscarCarnet').prop('disabled', false);
                        $('#resultadoBusqueda').html('');
                        mostrarAlerta('Error al realizar la búsqueda', 'danger');
                    }
                });
            }

            function formatMoney(amount) {
                return parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }

            function mostrarAlerta(mensaje, tipo) {
                $('#alertContainer').html(`
                    <div class="alert alert-${tipo} alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="ri-alert-line me-2"></i>${mensaje}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                setTimeout(function() { $('.alert').alert('close'); }, 5000);
            }
        });
    </script>
@endpush
