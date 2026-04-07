@extends('admin.dashboard')

@section('admin')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

    :root {
        --cont-primary: #0f766e;
        --cont-primary-light: #f0fdfa;
        --cont-primary-dark: #0d5f59;
        --cont-accent: #f59e0b;
        --cont-accent-light: #fffbeb;
        --cont-surface: #f8fafc;
        --cont-border: #e2e8f0;
        --cont-text: #1e293b;
        --cont-text-muted: #64748b;
        --cont-success: #10b981;
        --cont-success-light: #ecfdf5;
        --cont-danger: #ef4444;
        --cont-danger-light: #fef2f2;
        --cont-info: #0891b2;
        --cont-info-light: #ecfeff;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md: 0 4px 8px -2px rgba(0,0,0,0.08), 0 2px 4px -2px rgba(0,0,0,0.04);
        --shadow-lg: 0 10px 25px -4px rgba(0,0,0,0.1), 0 4px 8px -4px rgba(0,0,0,0.06);
    }

    .contabilidad-page {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--cont-text);
        animation: contFadeIn 0.5s ease-out;
    }

    @keyframes contFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Page Header */
    .cont-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding: 20px 28px;
        background: linear-gradient(135deg, var(--cont-primary) 0%, var(--cont-primary-dark) 100%);
        border-radius: var(--radius-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .cont-header::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -5%;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(245,158,11,0.12) 0%, transparent 70%);
        border-radius: 50%;
    }

    .cont-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 20%;
        width: 180px;
        height: 180px;
        background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 70%);
        border-radius: 50%;
    }

    .cont-header h1 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.02em;
        position: relative;
        z-index: 1;
        color: white;
    }

    .cont-header p {
        margin: 4px 0 0;
        opacity: 0.8;
        font-size: 0.85rem;
        position: relative;
        z-index: 1;
        color: white;
    }

    .btn-cobradores {
        background: white;
        color: var(--cont-primary);
        border: none;
        padding: 9px 22px;
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.25s ease;
        box-shadow: var(--shadow-sm);
        position: relative;
        z-index: 1;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-cobradores:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        background: var(--cont-primary-light);
        color: var(--cont-primary);
    }

    /* Search Card */
    .search-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--cont-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .search-card-body {
        padding: 32px 28px;
    }

    .search-icon-wrapper {
        width: 72px;
        height: 72px;
        margin: 0 auto 16px;
        background: linear-gradient(135deg, var(--cont-primary) 0%, var(--cont-primary-dark) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.6rem;
        box-shadow: 0 8px 24px rgba(15, 118, 110, 0.25);
    }

    .search-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.2rem;
        color: var(--cont-text);
        margin-bottom: 4px;
    }

    .search-subtitle {
        color: var(--cont-text-muted);
        font-size: 0.85rem;
        margin-bottom: 20px;
    }

    .search-input-group {
        position: relative;
        display: flex;
        align-items: stretch;
        border-radius: var(--radius-md);
        border: 2px solid var(--cont-border);
        overflow: hidden;
        background: var(--cont-surface);
        transition: all 0.25s ease;
    }

    .search-input-group:focus-within {
        border-color: var(--cont-primary);
        box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.1);
        background: white;
    }

    .search-input-icon {
        display: flex;
        align-items: center;
        padding: 0 14px;
        color: var(--cont-text-muted);
        font-size: 1.1rem;
        background: transparent;
        border: none;
    }

    .search-input {
        flex: 1;
        border: none;
        background: transparent;
        padding: 14px 0;
        font-size: 0.95rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        outline: none;
        color: var(--cont-text);
    }

    .search-input::placeholder {
        color: #94a3b8;
    }

    .search-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 20px;
        background: var(--cont-primary);
        color: white;
        border: none;
        font-size: 1.05rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .search-btn:hover {
        background: var(--cont-primary-dark);
    }

    .search-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .search-hint {
        font-size: 0.78rem;
        color: var(--cont-text-muted);
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .search-hint i {
        font-size: 0.9rem;
    }

    .search-spinner {
        display: flex;
        align-items: center;
        padding: 0 12px;
    }

    .search-spinner .spinner-border {
        width: 18px;
        height: 18px;
        border-width: 2px;
        color: var(--cont-primary);
    }

    /* Result Card */
    .result-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--cont-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        position: relative;
    }

    .result-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(180deg, var(--cont-primary) 0%, var(--cont-primary-dark) 100%);
    }

    .result-card-body {
        padding: 24px;
    }

    .result-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }

    .result-student-info {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .result-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--cont-primary) 0%, var(--cont-primary-dark) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.3rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.2);
    }

    .result-name {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--cont-text);
        margin: 0 0 4px;
    }

    .result-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .result-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.72rem;
        font-weight: 600;
        background: var(--cont-surface);
        color: var(--cont-text-muted);
        border: 1px solid var(--cont-border);
    }

    .result-badge i {
        font-size: 0.82rem;
    }

    .btn-ver-detalle {
        background: linear-gradient(135deg, var(--cont-primary) 0%, var(--cont-primary-dark) 100%);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.88rem;
        transition: all 0.25s ease;
        box-shadow: 0 4px 14px rgba(15, 118, 110, 0.3);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-ver-detalle:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(15, 118, 110, 0.35);
        color: white;
    }

    .result-divider {
        border: none;
        height: 1px;
        background: var(--cont-border);
        margin: 0 0 20px;
    }

    /* Stat Items */
    .result-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .result-stat {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border-radius: var(--radius-md);
        border: 1px solid var(--cont-border);
        background: var(--cont-surface);
        transition: all 0.2s ease;
    }

    .result-stat:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .result-stat-icon {
        width: 42px;
        height: 42px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .result-stat-icon.icon-programs {
        background: var(--cont-info-light);
        color: var(--cont-info);
    }

    .result-stat-icon.icon-paid {
        background: var(--cont-success-light);
        color: var(--cont-success);
    }

    .result-stat-icon.icon-debt {
        background: var(--cont-danger-light);
        color: var(--cont-danger);
    }

    .result-stat-icon.icon-debt.no-debt {
        background: var(--cont-success-light);
        color: var(--cont-success);
    }

    .result-stat-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--cont-text-muted);
        font-weight: 700;
        margin-bottom: 2px;
    }

    .result-stat-value {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1rem;
    }

    .result-stat-value.val-success { color: var(--cont-success); }
    .result-stat-value.val-danger { color: var(--cont-danger); }
    .result-stat-value.val-text { color: var(--cont-text); }

    /* Empty State */
    .empty-state-cont {
        padding: 48px 24px;
        text-align: center;
    }

    .empty-state-cont-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 14px;
        background: var(--cont-surface);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state-cont-icon i {
        font-size: 2rem;
        color: #cbd5e1;
    }

    .empty-state-cont h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        color: var(--cont-text);
        margin-bottom: 4px;
    }

    .empty-state-cont p {
        color: var(--cont-text-muted);
        font-size: 0.85rem;
        margin: 0;
    }

    /* Loading */
    .cont-loading {
        text-align: center;
        padding: 40px 24px;
    }

    .cont-loading-spinner {
        width: 32px;
        height: 32px;
        border: 3px solid var(--cont-border);
        border-top-color: var(--cont-primary);
        border-radius: 50%;
        animation: spinAnim 0.8s linear infinite;
        margin: 0 auto 12px;
    }

    @keyframes spinAnim {
        to { transform: rotate(360deg); }
    }

    .cont-loading p {
        color: var(--cont-text-muted);
        font-size: 0.85rem;
        margin: 0;
    }

    /* Toast */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 999999 !important;
    }

    /* Responsive */
    @media (max-width: 767.98px) {
        .cont-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .result-stats {
            grid-template-columns: 1fr;
        }
        .result-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="container-fluid contabilidad-page">
    <!-- Page Header -->
    <div class="cont-header">
        <div>
            <h1><i class="ri-calculator-line me-2"></i>Área Contable</h1>
            <p>Busca y consulta la información financiera de los participantes</p>
        </div>
        <a href="{{ route('admin.contabilidad.cobradores') }}" class="btn-cobradores">
            <i class="ri-user-star-line"></i> Reporte de Cobradores
        </a>
    </div>

    <!-- Búsqueda -->
    <div class="search-card">
        <div class="search-card-body">
            <div class="text-center mb-4">
                <div class="search-icon-wrapper">
                    <i class="ri-search-line"></i>
                </div>
                <h5 class="search-title">Buscar Participante</h5>
                <p class="search-subtitle">Escriba el carnet o nombre del participante — los resultados aparecen en tiempo real</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="search-input-group">
                        <span class="search-input-icon">
                            <i class="ri-id-card-line"></i>
                        </span>
                        <input type="text" class="search-input"
                               id="carnet" placeholder="Ej: 1234567 o Juan Pérez" autofocus autocomplete="off">
                        <span class="search-spinner" id="searchSpinner" style="display:none;">
                            <div class="spinner-border" role="status"></div>
                        </span>
                        <button class="search-btn" type="button" id="btnBuscarCarnet">
                            <i class="ri-search-line"></i>
                        </button>
                    </div>
                    <div class="search-hint" id="searchHint">
                        <i class="ri-information-line"></i>
                        <span>Escriba al menos 3 caracteres para buscar automáticamente</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resultado de búsqueda -->
    <div id="resultadoBusqueda" class="mt-3"></div>

    <!-- Alertas -->
    <div id="alertContainer" class="mt-3"></div>
</div>

@endsection

@push('script')
    <script>
        $(document).ready(function() {
            let debounceTimer = null;
            let lastQuery = '';

            // Búsqueda en tiempo real
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
                    $('#searchHint').html('<i class="ri-information-line"></i><span>Escriba al menos 3 caracteres para buscar automáticamente</span>');
                    return;
                }

                $('#searchHint').html('<i class="ri-time-line"></i><span>Buscando...</span>');

                debounceTimer = setTimeout(function() {
                    ejecutarBusqueda(query);
                }, 400);
            });

            // Botón buscar / Enter
            $('#btnBuscarCarnet').on('click', function() {
                const query = $('#carnet').val().trim();
                if (!query) {
                    showToast('warning', 'Por favor ingrese un carnet o nombre');
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

            // Función principal de búsqueda
            function ejecutarBusqueda(query) {
                lastQuery = query;

                $('#searchSpinner').show();
                $('#btnBuscarCarnet').prop('disabled', true);

                $('#resultadoBusqueda').html(`
                    <div class="result-card">
                        <div class="cont-loading">
                            <div class="cont-loading-spinner"></div>
                            <p>Buscando <strong>${query}</strong>...</p>
                        </div>
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
                        $('#searchHint').html('<i class="ri-checkbox-circle-line" style="color:var(--cont-success);"></i><span>Búsqueda completada</span>');

                        if (response.success) {
                            const e = response.estudiante;
                            const deuda = parseFloat(e.total_deuda);
                            const deudaColor = deuda > 0 ? 'danger' : 'success';
                            const deudaIcon = deuda > 0 ? 'ri-bill-line' : 'ri-checkbox-circle-line';
                            const deudaLabel = deuda > 0 ? 'Deuda Pendiente' : 'Sin Deuda';
                            const noDebtCls = deuda > 0 ? '' : 'no-debt';

                            $('#resultadoBusqueda').html(`
                                <div class="result-card">
                                    <div class="result-card-body">
                                        <div class="result-header">
                                            <div class="result-student-info">
                                                <div class="result-avatar">${e.nombre_completo.charAt(0).toUpperCase()}</div>
                                                <div>
                                                    <h5 class="result-name">${e.nombre_completo}</h5>
                                                    <div class="result-meta">
                                                        <span class="result-badge"><i class="ri-id-card-line"></i>${e.carnet}</span>
                                                        ${e.correo ? `<span class="result-badge"><i class="ri-mail-line"></i>${e.correo}</span>` : ''}
                                                        ${e.celular ? `<span class="result-badge"><i class="ri-phone-line"></i>${e.celular}</span>` : ''}
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="${response.redirect}" class="btn-ver-detalle">
                                                <i class="ri-eye-line"></i> Ver Detalle
                                            </a>
                                        </div>

                                        <hr class="result-divider">

                                        <div class="result-stats">
                                            <div class="result-stat">
                                                <div class="result-stat-icon icon-programs">
                                                    <i class="ri-book-2-line"></i>
                                                </div>
                                                <div>
                                                    <div class="result-stat-label">Programas</div>
                                                    <div class="result-stat-value val-text">${e.total_programas}</div>
                                                </div>
                                            </div>
                                            <div class="result-stat">
                                                <div class="result-stat-icon icon-paid">
                                                    <i class="ri-money-dollar-circle-line"></i>
                                                </div>
                                                <div>
                                                    <div class="result-stat-label">Total Pagado</div>
                                                    <div class="result-stat-value val-success">${formatMoney(e.total_pagado)} Bs</div>
                                                </div>
                                            </div>
                                            <div class="result-stat">
                                                <div class="result-stat-icon icon-debt ${noDebtCls}">
                                                    <i class="${deudaIcon}"></i>
                                                </div>
                                                <div>
                                                    <div class="result-stat-label">${deudaLabel}</div>
                                                    <div class="result-stat-value val-${deudaColor}">${formatMoney(e.total_deuda)} Bs</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
                        } else {
                            $('#resultadoBusqueda').html(`
                                <div class="result-card">
                                    <div class="empty-state-cont">
                                        <div class="empty-state-cont-icon">
                                            <i class="ri-user-search-line"></i>
                                        </div>
                                        <h5>Participante no encontrado</h5>
                                        <p>${response.msg || 'No se encontró ningún participante con ese dato'}</p>
                                    </div>
                                </div>
                            `);
                        }
                    },
                    error: function(xhr) {
                        $('#searchSpinner').hide();
                        $('#btnBuscarCarnet').prop('disabled', false);
                        $('#resultadoBusqueda').html('');
                        showToast('error', 'Error al realizar la búsqueda');
                    }
                });
            }

            function formatMoney(amount) {
                return parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }

            // Toast function
            function showToast(type, message) {
                var config = {
                    success: { icon: 'ri-checkbox-circle-fill', bgClass: 'bg-success', title: 'Éxito' },
                    error: { icon: 'ri-close-circle-fill', bgClass: 'bg-danger', title: 'Error' },
                    warning: { icon: 'ri-alert-fill', bgClass: 'bg-warning', title: 'Advertencia' },
                    info: { icon: 'ri-information-fill', bgClass: 'bg-info', title: 'Información' }
                };
                var toastConfig = config[type] || config.info;
                var toastId = 'toast-' + Date.now();
                var toastHtml = `
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
                    </div>`;
                var container = document.getElementById('toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'toast-container';
                    container.className = 'toast-container position-fixed top-0 end-0 p-3';
                    container.style.zIndex = '999999';
                    document.body.appendChild(container);
                }
                container.insertAdjacentHTML('afterbegin', toastHtml);
                var toastElement = document.getElementById(toastId);
                var toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 });
                toast.show();
                toastElement.addEventListener('hidden.bs.toast', function() { this.remove(); });
            }
        });
    </script>
@endpush
