@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-dark">Gestión de Vendedores</h4>
                        <p class="text-muted mb-0">Administra los vendedores (Ejecutivo de Marketing, Asesor de Marketing,
                            Gerente de Marketing)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card border border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-primary bg-opacity-10">
                                    <i class="ri-user-line fs-24 text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Vendedores</p>
                                <h4 class="mb-0" id="totalVendedoresCounter">{{ $vendedores->total() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="flex-grow-1">
                                <div class="search-box position-relative">
                                    <input type="text" id="searchInput" class="form-control search form-control-lg ps-5"
                                        placeholder="Buscar por nombre, carnet, correo, celular..."
                                        value="{{ request('search') ?? '' }}">
                                    <i
                                        class="ri-search-line search-icon position-absolute top-50 start-0 translate-middle-y text-muted ms-3"></i>
                                </div>
                            </div>
                            <div>
                                <button type="button" id="clearFilters" class="btn btn-outline-secondary btn-lg">
                                    <i class="ri-refresh-line align-bottom me-1"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border border-light shadow-sm">
                    <div class="card-header border-bottom-dashed d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fw-bold">Listado de Vendedores</h5>
                        <div class="dropdown">
                            <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-equalizer-line align-bottom me-1"></i> Vista
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item active" href="#"><i
                                            class="ri-list-check align-bottom me-2"></i> Tabla</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                <thead class="table-light text-muted fw-semibold">
                                    <tr>
                                        <th class="px-3 py-3" width="10%">Carnet</th>
                                        <th class="px-3 py-3">Nombre Completo</th>
                                        <th class="px-3 py-3">Cargo y Sucursal</th>
                                        <th class="px-3 py-3">Contacto</th>
                                        <th class="px-3 py-3 text-center" width="15%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="vendedoresTableBody">
                                    @include('admin.trabajadores.vendedores.partials.table-body')
                                </tbody>
                            </table>
                        </div>
                        <!-- En la sección de Results Table, después de la tabla -->
                        @if ($vendedores->total() > 0)
                            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                                <div class="results-count text-muted">
                                    Mostrando <span class="fw-medium">{{ $vendedores->firstItem() }}</span> a
                                    <span class="fw-medium">{{ $vendedores->lastItem() }}</span> de
                                    <span class="fw-medium">{{ $vendedores->total() }}</span> resultados
                                </div>
                                <div class="pagination-container">
                                    {{ $vendedores->appends(request()->input())->links('pagination::bootstrap-5') }}
                                </div>
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
        .table-nowrap td,
        .table-nowrap th {
            white-space: nowrap;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .badge-cargo {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        @media (max-width: 767.98px) {
            .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }

            .card-header {
                flex-direction: column;
                gap: 10px;
            }

            .search-box {
                width: 100%;
            }
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999999;
        }

        .toast {
            min-width: 300px;
            max-width: 350px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 10px;
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            let debounceTimer;
            let isProcessing = false;

            // Configuración global de AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Función para manejar errores AJAX
            function handleAjaxError(xhr, textStatus, errorThrown) {
                console.error('AJAX Error:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    response: xhr.responseText
                });

                let errorMessage = 'Error al cargar los datos';

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        errorMessage = 'Errores de validación: ' + Object.values(errors).flat().join(', ');
                    }
                } else if (xhr.status === 500) {
                    errorMessage = 'Error interno del servidor';
                } else if (xhr.status === 404) {
                    errorMessage = 'Recurso no encontrado';
                }

                showToast('error', errorMessage);
            }

            // Inicializar tooltips
            $('[data-bs-toggle="tooltip"]').each(function() {
                new bootstrap.Tooltip(this);
            });

            // === FUNCIONES AUXILIARES ===
            function loadResults(search = '', page = 1) {
                if (isProcessing) return;
                isProcessing = true;

                // Mostrar spinner
                $('#vendedoresTableBody').html(`
        <tr>
            <td colspan="5" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2 text-muted">Cargando resultados...</p>
            </td>
        </tr>
        `);

                // Construir URL con parámetros
                let url = '{{ route('admin.vendedores.listar') }}';
                let params = new URLSearchParams();

                if (search) params.append('search', search);
                if (page > 1) params.append('page', page);

                const queryString = params.toString();
                if (queryString) {
                    url += '?' + queryString;
                }

                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.html) {
                            $('#vendedoresTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(`
                            Mostrando <span class="fw-medium">${response.from || 0}</span> a 
                            <span class="fw-medium">${response.to || 0}</span> de 
                            <span class="fw-medium">${response.total}</span> resultados
                        `);

                                $('#totalVendedoresCounter').text(response.total);
                            }

                            // Re-inicializar tooltips
                            $('[data-bs-toggle="tooltip"]').each(function() {
                                if (this._tooltip) {
                                    bootstrap.Tooltip.getInstance(this).dispose();
                                }
                                new bootstrap.Tooltip(this);
                            });
                        } else if (response.error) {
                            showToast('error', response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        handleAjaxError(xhr, status, error);

                        // Mostrar mensaje de error en la tabla
                        $('#vendedoresTableBody').html(`
                <tr>
                    <td colspan="5" class="text-center py-5 text-danger">
                        <i class="ri-error-warning-line display-5"></i>
                        <p class="mt-2">Error al cargar los datos</p>
                        <p class="small">Intenta recargar la página</p>
                    </td>
                </tr>
                `);
                    },
                    complete: function() {
                        isProcessing = false;
                    }
                });
            }

            // === BÚSQUEDA ===
            $('#searchInput').on('input', function() {
                const searchTerm = $(this).val().trim();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadResults(searchTerm);
                }, 500);
            });

            $('#clearFilters').on('click', function() {
                $('#searchInput').val('');
                loadResults();
            });

            // === PAGINACIÓN MEJORADA ===
            $(document).on('click', '.pagination-container a', function(e) {
                e.preventDefault();
                if (isProcessing) return;

                const url = $(this).attr('href');
                if (!url) return;

                // Extraer parámetros de la URL
                const urlObj = new URL(url, window.location.origin);
                const search = urlObj.searchParams.get('search') || '';
                const page = urlObj.searchParams.get('page') || 1;

                // Actualizar el campo de búsqueda si hay un parámetro de búsqueda
                if (search && $('#searchInput').val() !== search) {
                    $('#searchInput').val(search);
                }

                // Cargar resultados
                loadResults(search, page);

                // Actualizar URL del navegador
                window.history.pushState({}, '', url);
            });

            // Manejar el botón de retroceso/avance del navegador
            $(window).on('popstate', function() {
                const urlParams = new URLSearchParams(window.location.search);
                const search = urlParams.get('search') || '';
                const page = urlParams.get('page') || 1;

                $('#searchInput').val(search);
                loadResults(search, page);
            });

            // === FUNCIÓN TOAST ===
            function showToast(type, message) {
                const config = {
                    success: {
                        icon: 'ri-checkbox-circle-fill',
                        bgClass: 'bg-success',
                        title: 'Éxito'
                    },
                    error: {
                        icon: 'ri-close-circle-fill',
                        bgClass: 'bg-danger',
                        title: 'Error'
                    },
                    warning: {
                        icon: 'ri-alert-fill',
                        bgClass: 'bg-warning',
                        title: 'Advertencia'
                    },
                    info: {
                        icon: 'ri-information-fill',
                        bgClass: 'bg-info',
                        title: 'Información'
                    }
                };

                const toastConfig = config[type] || config.info;
                const toastId = 'toast-' + Date.now();

                const toastHtml = `
        <div id="${toastId}" class="toast ${toastConfig.bgClass} text-white" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header ${toastConfig.bgClass} text-white border-bottom-0">
                <i class="${toastConfig.icon} me-2"></i>
                <strong class="me-auto">${toastConfig.title}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body d-flex align-items-center">
                <i class="${toastConfig.icon} me-2 fs-5"></i>
                <span class="flex-grow-1">${message}</span>
            </div>
        </div>
        `;

                let container = document.getElementById('toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'toast-container';
                    container.className = 'toast-container position-fixed top-0 end-0 p-3';
                    document.body.appendChild(container);
                }

                container.insertAdjacentHTML('afterbegin', toastHtml);

                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement, {
                    autohide: true,
                    delay: 4000
                });

                toast.show();

                toastElement.addEventListener('hidden.bs.toast', function() {
                    this.remove();
                });
            }

            // Cargar datos iniciales
            loadResults();
        });
    </script>
@endpush
