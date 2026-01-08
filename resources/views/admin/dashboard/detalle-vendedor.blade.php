@extends('admin.dashboard')
@section('title', 'Detalle de Inscripciones – ' . $persona->nombre_completo)
@section('admin')
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-light: #818cf8;
            --primary-dark: #3730a3;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --danger-color: #ef4444;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
        }

        /* Tarjeta mejorada del asesor */
        .asesor-hero-updated {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            color: white;
            padding: 2.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .asesor-hero-updated::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
        }

        .asesor-avatar-updated {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .asesor-avatar-updated:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        /* Filtros en una sola fila */
        .filter-section-updated {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-100);
        }

        .filter-section-updated h5 {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 1.25rem;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }

        .filter-section-updated h5 i {
            margin-right: 0.5rem;
        }

        .filter-form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            display: block;
        }

        .filter-actions-updated {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        /* Estadísticas mejoradas */
        .stats-grid-updated {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card-updated {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .stat-card-updated:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        .stat-icon-updated {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            flex-shrink: 0;
        }

        .stat-icon-updated.primary {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary-color);
        }

        .stat-icon-updated.warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .stat-icon-updated.success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .stat-info-updated {
            flex: 1;
        }

        .stat-value-updated {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 0.25rem;
            color: var(--gray-900);
        }

        .stat-label-updated {
            font-size: 0.875rem;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Gráficos mejorados */
        .chart-card-updated {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-100);
            height: 100%;
        }

        .chart-card-updated .card-header {
            border-bottom: 1px solid var(--gray-100);
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .chart-card-updated h5 {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 1.1rem;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .chart-card-updated h5 i {
            margin-right: 0.5rem;
        }

        .chart-container-updated {
            height: 340px;
            position: relative;
        }

        /* Tabla mejorada */
        .table-section-updated {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-100);
            margin-top: 2rem;
        }

        .table-header-updated {
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--gray-100);
        }

        .table-header-updated h5 {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 1.1rem;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .table-header-updated h5 i {
            margin-right: 0.5rem;
        }

        .table-badge-updated {
            background: var(--primary-color);
            color: white;
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .table-responsive-updated {
            padding: 1.5rem;
        }

        .table-updated {
            margin-bottom: 0;
        }

        .table-updated thead th {
            font-weight: 600;
            color: var(--gray-700);
            border-bottom: 2px solid var(--gray-200);
            padding: 0.75rem 1rem;
        }

        .table-updated tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid var(--gray-100);
        }

        .table-updated tbody tr:hover {
            background-color: var(--gray-50);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-form-row {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                min-width: 100%;
            }

            .filter-actions-updated {
                justify-content: flex-end;
            }

            .stat-card-updated {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .stat-info-updated {
                width: 100%;
            }

            .chart-container-updated {
                height: 300px;
            }
        }

        @media (max-width: 576px) {
            .stat-value-updated {
                font-size: 1.75rem;
            }

            .table-header-updated {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
    </style>

    <div class="page-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h1>Dashboard de Ventas</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Asesores</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $persona->nombre_completo }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ url()->previous() }}" class="btn btn-outline-primary d-flex align-items-center">
                    <i class="ri-arrow-left-line me-1"></i> Volver
                </a>
                <button class="btn btn-primary d-flex align-items-center" onclick="window.print()">
                    <i class="ri-printer-line me-1"></i> Imprimir Reporte
                </button>
            </div>
        </div>
    </div>

    <!-- Tarjeta del asesor (se mantiene igual) -->
    <div class="asesor-hero-updated">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4">
            <div class="position-relative">
                @if ($persona->fotografia && file_exists(public_path($persona->fotografia)))
                    <img src="{{ asset($persona->fotografia) }}" alt="{{ $persona->nombre_completo }}"
                        class="asesor-avatar-updated">
                @else
                    <img src="{{ strpos(strtolower($persona->sexo ?? ''), 'hombre') !== false
                        ? asset('backend/assets/images/hombre.png')
                        : asset('backend/assets/images/mujer.png') }}"
                        alt="{{ $persona->nombre_completo }}" class="asesor-avatar-updated">
                @endif
            </div>

            <div class="asesor-info-updated text-center text-md-start flex-grow-1">
                <h2>{{ $persona->nombre_completo }}</h2>

                <div class="asesor-meta-updated">
                    @if ($persona->correo)
                        <div class="meta-item-updated">
                            <i class="ri-mail-line"></i>
                            <span>{{ $persona->correo }}</span>
                        </div>
                    @endif

                    @if ($persona->celular)
                        <div class="meta-item-updated">
                            <i class="ri-phone-line"></i>
                            <span>{{ $persona->celular }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas principales mejoradas -->
    <div class="stats-grid-updated">
        <div class="stat-card-updated">
            <div class="stat-icon-updated primary">
                <i class="ri-checkbox-circle-fill"></i>
            </div>
            <div class="stat-info-updated">
                <div class="stat-value-updated" id="totalInscritos">0</div>
                <div class="stat-label-updated">Inscritos Confirmados</div>
            </div>
        </div>
        <div class="stat-card-updated">
            <div class="stat-icon-updated warning">
                <i class="ri-time-line"></i>
            </div>
            <div class="stat-info-updated">
                <div class="stat-value-updated" id="totalPreInscritos">0</div>
                <div class="stat-label-updated">Pre-Inscritos</div>
            </div>
        </div>
        <div class="stat-card-updated">
            <div class="stat-icon-updated success">
                <i class="ri-bar-chart-fill"></i>
            </div>
            <div class="stat-info-updated">
                <div class="stat-value-updated" id="totalGeneral">0</div>
                <div class="stat-label-updated">Total Gestión</div>
            </div>
        </div>
        <div class="stat-card-updated">
            <div class="stat-icon-updated primary">
                <i class="ri-percent-line"></i>
            </div>
            <div class="stat-info-updated">
                <div class="stat-value-updated" id="conversionRate">0%</div>
                <div class="stat-label-updated">Tasa de Conversión</div>
            </div>
        </div>
    </div>

    <!-- Filtros en una sola fila -->
    <div class="filter-section-updated">
        <h5><i class="ri-filter-3-line"></i> Filtros Avanzados</h5>
        <form id="filterForm">
            @csrf
            <div class="filter-form-row">
                <div class="filter-group">
                    <label class="form-label">Gestión</label>
                    <select name="gestion" class="form-select">
                        <option value="">Todas las gestiones</option>
                        @for ($g = 2020; $g <= date('Y'); $g++)
                            <option value="{{ $g }}">{{ $g }}</option>
                        @endfor
                    </select>
                </div>
                <div class="filter-group">
                    <label class="form-label">Mes</label>
                    <select name="mes" class="form-select">
                        <option value="todos">Todos los meses</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">
                                {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="filter-actions-updated">
                    <button type="button" class="btn btn-primary" id="applyFilters">
                        <i class="ri-filter-line me-1"></i> Aplicar Filtros
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="resetFilters">
                        <i class="ri-refresh-line me-1"></i> Limpiar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Gráficos mejorados -->
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="chart-card-updated">
                <div class="card-header">
                    <h5><i class="ri-line-chart-line"></i> Inscripciones por Mes</h5>
                </div>
                <div class="chart-container-updated">
                    <canvas id="graficoMensual"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="chart-card-updated">
                <div class="card-header">
                    <h5><i class="ri-pie-chart-line"></i> Distribución por Tipo de Posgrado</h5>
                </div>
                <div class="chart-container-updated">
                    <canvas id="graficoPorTipo"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla mejorada -->
    <div class="table-section-updated">
        <div class="table-header-updated">
            <h5><i class="ri-table-line"></i> Registro de Ventas</h5>
            <div class="d-flex align-items-center gap-2">
                <span class="table-badge-updated" id="badgeTotal">0 registros</span>
            </div>
        </div>
        <div class="table-responsive-updated">
            <div id="tablaContainer">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-3 text-muted">Cargando datos...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const personaId = {{ $persona->id }};
            const urlData = `{{ route('admin.vendedor.data', ['personaId' => $persona->id]) }}`;
            let chartMensual = null;
            let chartTipo = null;

            function loadData(filters = {}) {
                document.getElementById('tablaContainer').innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3 text-muted">Cargando datos...</p>
                    </div>
                `;

                axios.get(urlData, {
                        params: filters
                    })
                    .then(function(response) {
                        const data = response.data;

                        // Actualizar estadísticas
                        document.getElementById('totalInscritos').textContent = data.inscritos || 0;
                        document.getElementById('totalPreInscritos').textContent = data.pre_inscritos || 0;
                        document.getElementById('totalGeneral').textContent = data.total || 0;
                        document.getElementById('badgeTotal').textContent = `${data.total || 0} registros`;

                        // Calcular tasa de conversión
                        const total = data.total || 1;
                        const inscritos = data.inscritos || 0;
                        const tasa = Math.round((inscritos / total) * 100);
                        document.getElementById('conversionRate').textContent = `${tasa}%`;

                        // Tabla
                        if (data.tablaHtml) {
                            document.getElementById('tablaContainer').innerHTML = data.tablaHtml;
                        } else {
                            document.getElementById('tablaContainer').innerHTML =
                                '<div class="text-center py-5 text-muted">No hay datos para mostrar</div>';
                        }

                        // Gráficos
                        renderGraficoMensual(data.graficoMeses || {});
                        renderGraficoPorTipo(data.graficoPorTipo || {});
                    })
                    .catch(function(error) {
                        console.error('Error:', error);
                        document.getElementById('tablaContainer').innerHTML = `
                            <div class="alert alert-danger text-center m-4">
                                <i class="ri-alert-line me-2"></i> Error al cargar los datos.
                            </div>
                        `;
                    });
            }

            function renderGraficoMensual(mesesData) {
                const ctx = document.getElementById('graficoMensual').getContext('2d');
                if (chartMensual) chartMensual.destroy();

                if (!mesesData || Object.keys(mesesData).length === 0) {
                    if (ctx.canvas.parentNode.querySelector('.no-data-message')) {
                        ctx.canvas.parentNode.querySelector('.no-data-message').style.display = 'block';
                    } else {
                        const noDataMsg = document.createElement('div');
                        noDataMsg.className = 'no-data-message text-center py-4';
                        noDataMsg.innerHTML = '<p class="text-muted">No hay datos para mostrar.</p>';
                        ctx.canvas.parentNode.appendChild(noDataMsg);
                    }
                    ctx.canvas.style.display = 'none';
                    return;
                }

                const noDataMsg = ctx.canvas.parentNode.querySelector('.no-data-message');
                if (noDataMsg) {
                    noDataMsg.style.display = 'none';
                }
                ctx.canvas.style.display = 'block';

                const labels = Object.values(mesesData).map(d => d.label);
                const inscritos = Object.values(mesesData).map(d => d.inscritos || 0);
                const preInscritos = Object.values(mesesData).map(d => d.pre_inscritos || 0);

                chartMensual = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                                label: 'Inscritos',
                                data: inscritos,
                                backgroundColor: '#4361ee',
                                borderRadius: 6
                            },
                            {
                                label: 'Pre-Inscritos',
                                data: preInscritos,
                                backgroundColor: '#ffa600',
                                borderRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    padding: 15
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: 'Mes'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f1f5f9'
                                },
                                title: {
                                    display: true,
                                    text: 'Cantidad'
                                },
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            function renderGraficoPorTipo(tipoData) {
                const ctx = document.getElementById('graficoPorTipo').getContext('2d');
                if (chartTipo) chartTipo.destroy();

                if (!tipoData || Object.values(tipoData).every(v => v === 0)) {
                    if (ctx.canvas.parentNode.querySelector('.no-data-message')) {
                        ctx.canvas.parentNode.querySelector('.no-data-message').style.display = 'block';
                    } else {
                        const noDataMsg = document.createElement('div');
                        noDataMsg.className = 'no-data-message text-center py-4';
                        noDataMsg.innerHTML = '<p class="text-muted">No hay datos para mostrar.</p>';
                        ctx.canvas.parentNode.appendChild(noDataMsg);
                    }
                    ctx.canvas.style.display = 'none';
                    return;
                }

                const noDataMsg = ctx.canvas.parentNode.querySelector('.no-data-message');
                if (noDataMsg) {
                    noDataMsg.style.display = 'none';
                }
                ctx.canvas.style.display = 'block';

                const labels = Object.keys(tipoData);
                const values = Object.values(tipoData);

                const colores = [
                    '#4361ee', '#3a0ca3', '#4cc9f0', '#f72585', '#b5179e',
                    '#560bad', '#7209b7', '#ff9e00', '#ff5400', '#9b5de5'
                ];

                chartTipo = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels,
                        datasets: [{
                            data: values,
                            backgroundColor: colores,
                            borderWidth: 3,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 12,
                                    padding: 12
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const pct = ((context.raw / total) * 100).toFixed(1);
                                        return `${context.label}: ${context.raw} (${pct}%)`;
                                    }
                                }
                            }
                        },
                        cutout: '65%'
                    }
                });
            }

            // Event Listeners
            document.getElementById('applyFilters').addEventListener('click', function() {
                const form = document.getElementById('filterForm');
                const formData = new FormData(form);
                const filters = {};
                formData.forEach((value, key) => {
                    filters[key] = value;
                });

                if (filters.mes === 'todos') delete filters.mes;
                loadData(filters);
            });

            document.getElementById('resetFilters').addEventListener('click', function() {
                document.getElementById('filterForm').reset();
                loadData();
            });

            loadData();
        });
    </script>
@endpush
