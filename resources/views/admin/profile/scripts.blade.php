<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Variables globales
        let marketingChart = null;
        let programasChart = null;
        let currentPage = 1;
        let filters = {
            year: new Date().getFullYear(),
            month: 'todos',
            programa_id: '',
            estado: '',
            search: ''
        };

        // Variables para ofertas activas
        let ofertasCurrentPage = 1;
        let ofertasFilters = {
            search: '',
            sucursal_id: '',
            per_page: 10
        };

        // Inicializar tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        // ==============================
        // FUNCIONALIDADES DE MARKETING
        // ==============================

        // Cargar datos de marketing cuando se muestra el tab
        $(document).on('shown.bs.tab', 'a[href="#marketing"]', function() {
            if (!marketingChart) {
                loadMarketingData();
            }
        });

        // Cargar mini dashboard si existe marketing
        @if ($tieneMarketing)
            loadMiniDashboard();
        @endif

        // Función para cargar mini dashboard
        function loadMiniDashboard() {
            $.ajax({
                url: '{{ route('admin.profile.marketing.estadisticas') }}',
                method: 'GET',
                data: {
                    year: new Date().getFullYear(),
                    month: 'todos'
                },
                success: function(response) {
                    if (response.success) {
                        $('#miniTotalInscripciones').text(response.resumen.total);
                        $('#miniInscritos').text(response.resumen.inscritos);
                        $('#miniPreInscritos').text(response.resumen.pre_inscritos);
                    }
                }
            });
        }

        // Aplicar filtros de marketing
        $('#applyMarketingFilter').on('click', function(e) {
            e.preventDefault();
            applyMarketingFilters();
        });

        // También aplicar filtros al enviar el formulario
        $('#marketingFilterForm').on('submit', function(e) {
            e.preventDefault();
            applyMarketingFilters();
        });

        // Función para aplicar filtros
        function applyMarketingFilters() {
            filters = {
                year: $('#marketingYear').val(),
                month: $('#marketingMonth').val(),
                programa_id: $('#marketingPrograma').val(),
                estado: $('#marketingEstado').val(),
                search: $('#marketingSearch').val()
            };
            currentPage = 1;
            loadMarketingData();
        }

        // Limpiar filtros
        $('#resetMarketingFilter').on('click', function() {
            $('#marketingYear').val(new Date().getFullYear());
            $('#marketingMonth').val('todos');
            $('#marketingPrograma').val('');
            $('#marketingEstado').val('');
            $('#marketingSearch').val('');

            filters = {
                year: new Date().getFullYear(),
                month: 'todos',
                programa_id: '',
                estado: '',
                search: ''
            };
            currentPage = 1;
            loadMarketingData();
        });

        // Refrescar datos
        $('#refreshMarketing').on('click', function() {
            loadMarketingData(currentPage);
        });

        // Función para cargar datos de marketing
        function loadMarketingData(page = 1) {
            currentPage = page;

            // Mostrar loading en la tabla
            $('#marketingTableContainer').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando datos de marketing...</p>
                </div>
            `);

            // Actualizar título del gráfico
            updateChartTitle();

            // Cargar estadísticas y gráficos
            $.ajax({
                url: '{{ route('admin.profile.marketing.estadisticas') }}',
                method: 'GET',
                data: filters,
                success: function(response) {
                    if (response.success) {
                        updateMarketingCharts(response.grafico, response.programas);
                        updateMarketingSummary(response.resumen);
                    }
                },
                error: function(xhr) {
                    console.error('Error al cargar estadísticas:', xhr);
                    showToast('error', 'Error al cargar las estadísticas');
                }
            });

            // Cargar tabla de inscripciones
            $.ajax({
                url: '{{ route('admin.profile.marketing.inscripciones-filtradas') }}',
                method: 'GET',
                data: {
                    ...filters,
                    page: page
                },
                success: function(response) {
                    if (response.success) {
                        renderMarketingTable(response.inscripciones, response.pagination);
                    } else {
                        showToast('error', response.message || 'Error al cargar las inscripciones');
                    }
                },
                error: function(xhr) {
                    console.error('Error al cargar inscripciones:', xhr);
                    showToast('error', 'Error al cargar las inscripciones');
                }
            });
        }

        // Actualizar título del gráfico
        function updateChartTitle() {
            let title = 'Inscripciones por Mes';
            if (filters.month !== 'todos') {
                const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                ];
                title += ` - ${meses[filters.month - 1]}`;
            }
            title += ` (${filters.year})`;
            $('#chartTitle').text(title);
        }

        // Actualizar gráficos
        // Actualizar gráficos
        function updateMarketingCharts(graficoData, programasData) {
            // Gráfico de barras - Inscripciones por mes
            const ctx = document.getElementById('marketingChart').getContext('2d');
            if (marketingChart) marketingChart.destroy();

            // Validar y preparar datos del gráfico de barras
            let meses = [];
            let inscritos = [];
            let pre_inscritos = [];

            if (graficoData && graficoData.meses) {
                meses = graficoData.meses;
                inscritos = graficoData.inscritos || Array(meses.length).fill(0);
                pre_inscritos = graficoData.pre_inscritos || Array(meses.length).fill(0);
            } else {
                // Datos por defecto si no hay información
                meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                inscritos = Array(12).fill(0);
                pre_inscritos = Array(12).fill(0);
            }

            marketingChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: meses,
                    datasets: [{
                            label: 'Inscritos',
                            data: inscritos,
                            backgroundColor: 'rgba(40, 167, 69, 0.7)',
                            borderColor: 'rgba(40, 167, 69, 1)',
                            borderWidth: 1,
                            borderRadius: 4
                        },
                        {
                            label: 'Pre-Inscritos',
                            data: pre_inscritos,
                            backgroundColor: 'rgba(255, 193, 7, 0.7)',
                            borderColor: 'rgba(255, 193, 7, 1)',
                            borderWidth: 1,
                            borderRadius: 4
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
                                usePointStyle: true,
                                padding: 15
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0,
                                callback: function(value) {
                                    return Math.floor(value);
                                }
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Gráfico de dona - Top programas
            const ctx2 = document.getElementById('programasChart').getContext('2d');
            if (programasChart) programasChart.destroy();

            // Validar datos del gráfico de dona
            let labels = [];
            let data = [];

            if (programasData && programasData.length > 0) {
                labels = programasData.map(p => p.programa_nombre || 'Sin nombre');
                data = programasData.map(p => p.total || 0);
            } else {
                // Mostrar mensaje en lugar de gráfico vacío
                labels = ['Sin datos disponibles'];
                data = [1];
            }

            // Colores para el gráfico
            const backgroundColors = [
                '#4361ee', '#3a0ca3', '#7209b7', '#f72585', '#4cc9f0',
                '#4895ef', '#560bad', '#b5179e', '#3f37c9', '#480ca8'
            ];

            programasChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: labels.length === 1 && labels[0] ===
                            'Sin datos disponibles' ? ['#e9ecef'] : backgroundColors.slice(0,
                                data.length),
                        borderWidth: 1,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                usePointStyle: true,
                                color: labels.length === 1 && labels[0] === 'Sin datos disponibles' ?
                                    '#6c757d' : undefined
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    if (labels.length === 1 && labels[0] ===
                                        'Sin datos disponibles') {
                                        return 'Sin datos disponibles';
                                    }
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.raw;
                                    const percentage = Math.round((value / total) * 100);
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Actualizar resumen
        function updateMarketingSummary(resumen) {
            $('#totalInscripcionesCard').text(resumen.total || 0);
            $('#totalInscritosCard').text(resumen.inscritos || 0);
            $('#totalPreInscritosCard').text(resumen.pre_inscritos || 0);

            let periodo = '';
            if (filters.month !== 'todos') {
                const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                ];
                periodo = `${meses[filters.month - 1]} ${filters.year}`;
            } else {
                periodo = `Año ${filters.year}`;
            }
            $('#periodoActualCard').text(periodo);
        }

        // Renderizar tabla de inscripciones
        function renderMarketingTable(inscripciones, pagination) {
            if (!inscripciones.data || inscripciones.data.length === 0) {
                $('#marketingTableContainer').html(`
                    <div class="text-center py-5">
                        <i class="ri-emotion-sad-line display-4 text-muted"></i>
                        <h5 class="mt-3 text-muted">No se encontraron inscripciones</h5>
                        <p class="text-muted">Intenta con otros filtros de búsqueda</p>
                    </div>
                `);
                $('#marketingPagination').html('');
                $('#tableCount').text('0');
                return;
            }

            $('#tableCount').text(pagination.total);

            let html = `
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">Estudiante</th>
                                <th width="15%">Programa</th>
                                <th width="10%">Sede - Sucursal</th>
                                <th width="12%">Plan de Pago</th>  <!-- NUEVA COLUMNA -->
                                <th width="8%">Estado</th>
                                <th width="10%">Fecha</th>
                                <th width="10%" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            inscripciones.data.forEach((inscripcion, index) => {
                const estudiante = inscripcion.estudiante?.persona;
                const programa = inscripcion.oferta_academica?.programa;
                const sucursal = inscripcion.oferta_academica?.sucursal;
                const sede = sucursal?.sede;
                const planPago = inscripcion.planes_pago; // Obtener el plan de pago
                const fecha = new Date(inscripcion.fecha_registro);
                const rowNumber = (pagination.current_page - 1) * pagination.per_page + index + 1;

                // Generar botones de acción (sin cambios)
                let accionesHtml = `
    <div class="d-flex flex-wrap gap-1 justify-content-center">
        <a href="/admin/profile/marketing/inscripcion/${inscripcion.id}/formulario-pdf"
           class="btn btn-sm btn-outline-primary"
           data-bs-toggle="tooltip"
           title="Generar Formulario PDF"
           target="_blank">
            <i class="ri-file-text-line me-1"></i>PDF
        </a>
        <a href="/admin/estudiantes/detalle/${inscripcion.estudiante_id}"
           class="btn btn-sm btn-outline-info"
           data-bs-toggle="tooltip"
           title="Ver detalles del estudiante">
            <i class="ri-eye-line"></i>
        </a>
    `;

                // Para Pre-Inscritos, agregar el botón de conversión
                if (inscripcion.estado === 'Pre-Inscrito') {
                    accionesHtml += `
        <button class="btn btn-sm btn-success btn-convertir-inscrito"
                data-inscripcion-id="${inscripcion.id}"
                data-oferta-id="${inscripcion.oferta_academica.id}"
                data-estudiante-nombre="${estudiante?.nombres || 'N/A'} ${estudiante?.apellido_paterno || ''}"
                data-estudiante-carnet="${estudiante?.carnet || 'N/A'}"
                data-programa-nombre="${programa?.nombre || 'N/A'}"
                data-bs-toggle="tooltip"
                title="Convertir a Inscrito">
            <i class="ri-user-add-line"></i>
        </button>
        `;
                } else {
                    // Para Inscritos, agregar botón para ver cuotas
                    accionesHtml += `
        <a href="/admin/inscripciones/${inscripcion.id}/cuotas"
           class="btn btn-sm btn-outline-info"
           data-bs-toggle="tooltip"
           title="Ver cuotas de pago">
            <i class="ri-money-dollar-circle-line"></i>
        </a>
        `;
                }
                accionesHtml += `</div>`;

                html += `
    <tr class="inscription-row">
        <td class="fw-semibold text-muted">${rowNumber}</td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-2">
                    <div class="avatar-xs">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="ri-user-line"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0">${estudiante?.nombres || 'N/A'} ${estudiante?.apellido_paterno || ''}</h6>
                    <p class="text-muted mb-0 small">
                        <i class="ri-id-card-line me-1"></i>
                        ${estudiante?.carnet || 'N/A'}
                    </p>
                </div>
            </div>
        </td>
        <td>
            <span class="badge programa-badge">
                <i class="ri-book-line me-1"></i>
                ${programa?.nombre || 'N/A'}
            </span>
        </td>
        <td>
            <div>
                <span class="badge sede-badge mb-1">
                    <i class="ri-building-line me-1"></i>
                    ${sede?.nombre || 'N/A'}
                </span>
                <br>
                <small class="text-muted">${sucursal?.nombre || 'N/A'}</small>
            </div>
        </td>
        <td>
            ${planPago ? `
                        <span class="badge bg-primary-subtle text-primary" data-bs-toggle="tooltip" title="Plan de pago seleccionado">
                            <i class="ri-money-dollar-circle-line me-1"></i>
                            ${planPago.nombre || 'Sin nombre'}
                        </span>
                    ` : `
                        <span class="badge bg-secondary-subtle text-secondary">
                            <i class="ri-information-line me-1"></i>
                            No asignado
                        </span>
                    `}
        </td>
        <td>
            <span class="badge ${inscripcion.estado === 'Inscrito' ? 'bg-success' : 'bg-warning'} badge-status">
                ${inscripcion.estado}
            </span>
        </td>
        <td>
            <small class="text-muted">${fecha.toLocaleDateString('es-ES')}</small>
            <br>
            <small class="text-muted">${fecha.toLocaleTimeString('es-ES', {hour: '2-digit', minute:'2-digit'})}</small>
        </td>
        <td class="text-center">
            ${accionesHtml}
        </td>
    </tr>
    `;
            });

            html += `
                    </tbody>
                </table>
            </div>
            `;

            $('#marketingTableContainer').html(html);
            renderMarketingPagination(pagination);

            // Re-inicializar tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        }

        // Renderizar paginación
        function renderMarketingPagination(pagination) {
            if (pagination.last_page <= 1) {
                $('#marketingPagination').html('');
                return;
            }

            let html = `
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="text-muted">
                        Mostrando <span class="fw-medium">${pagination.from}</span> a 
                        <span class="fw-medium">${pagination.to}</span> de 
                        <span class="fw-medium">${pagination.total}</span> resultados
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
            `;

            // Botón anterior
            if (pagination.current_page > 1) {
                html += `
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="${pagination.current_page - 1}">
                            <i class="ri-arrow-left-s-line"></i>
                        </a>
                    </li>
                `;
            } else {
                html += `
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="ri-arrow-left-s-line"></i>
                        </span>
                    </li>
                `;
            }

            // Números de página
            let startPage = Math.max(1, pagination.current_page - 2);
            let endPage = Math.min(pagination.last_page, pagination.current_page + 2);

            for (let i = startPage; i <= endPage; i++) {
                if (i === pagination.current_page) {
                    html += `
                        <li class="page-item active">
                            <span class="page-link">${i}</span>
                        </li>
                    `;
                } else {
                    html += `
                        <li class="page-item">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `;
                }
            }

            // Botón siguiente
            if (pagination.current_page < pagination.last_page) {
                html += `
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="${pagination.current_page + 1}">
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                    </li>
                `;
            } else {
                html += `
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </li>
                `;
            }

            html += `
                        </ul>
                    </nav>
                </div>
            `;

            $('#marketingPagination').html(html);
        }

        // ==============================
        // FUNCIONALIDADES DE OFERTAS ACTIVAS
        // ==============================

        // Cargar ofertas activas cuando se muestra el tab
        $(document).on('shown.bs.tab', 'a[href="#ofertas-activas"]', function() {
            loadOfertasActivas();
        });

        // Refrescar ofertas
        $('#refreshOfertas').on('click', function() {
            loadOfertasActivas();
        });

        // Aplicar filtros
        $('#applyFilters').on('click', function() {
            ofertasFilters.search = $('#searchOfertas').val();
            ofertasFilters.sucursal_id = $('#filterSucursal').val();
            ofertasFilters.per_page = $('#itemsPerPage').val();
            ofertasCurrentPage = 1;
            loadOfertasActivas();
        });

        // Cambiar items por página
        $('#itemsPerPage').on('change', function() {
            ofertasFilters.per_page = $(this).val();
            ofertasCurrentPage = 1;
            loadOfertasActivas();
        });

        // Buscar al presionar Enter
        $('#searchOfertas').on('keypress', function(e) {
            if (e.which === 13) {
                $('#applyFilters').click();
            }
        });

        // Función para cargar ofertas activas
        function loadOfertasActivas() {
            showLoading('#ofertasContainer');

            $.ajax({
                url: '{{ route('admin.profile.marketing.ofertas-activas') }}',
                method: 'GET',
                data: {
                    ...ofertasFilters,
                    page: ofertasCurrentPage
                },
                success: function(response) {
                    console.log('Respuesta exitosa:', response);
                    if (response.success) {
                        renderOfertasTable(response.ofertas);
                        updateOfertasStats(response);
                        renderOfertasPagination(response.ofertas);
                        updateCargoInfo(response.cargo_principal);
                    } else {
                        console.error('Error en respuesta:', response);
                        showToast('error', response.message || 'Error al cargar las ofertas');
                        $('#ofertasContainer').html(`
                            <div class="alert alert-danger">
                                <h5>Error: ${response.message || 'Desconocido'}</h5>
                                <p>${response.debug ? JSON.stringify(response.debug) : ''}</p>
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', xhr, status, error);
                    showToast('error', 'Error de conexión: ' + error);
                    $('#ofertasContainer').html(`
                        <div class="alert alert-danger">
                            <h5>Error ${xhr.status}: ${xhr.statusText}</h5>
                            <p>${xhr.responseText ? xhr.responseText.substring(0, 200) : 'Sin respuesta del servidor'}</p>
                            <p><strong>URL:</strong> {{ route('admin.profile.marketing.ofertas-activas') }}</p>
                        </div>
                    `);
                }
            });
        }

        // Renderizar tabla de ofertas
        function renderOfertasTable(ofertas) {
            let html = '';

            if (ofertas.data.length === 0) {
                html = `
                    <div class="text-center py-5">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-light text-secondary rounded-circle">
                                <i class="ri-search-line display-4"></i>
                            </div>
                        </div>
                        <h5 class="text-muted">No se encontraron ofertas activas</h5>
                        <p class="text-muted mb-0">No hay ofertas académicas en fase de inscripciones en este momento.</p>
                    </div>
                `;
            } else {
                html = `
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="20%">Código</th>
                                    <th width="25%">Programa</th>
                                    <th width="15%">Sucursal</th>
                                    <th width="15%">Modalidad</th>
                                    <th width="15%">Fechas</th>
                                    <th width="10%" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                ofertas.data.forEach(oferta => {
                    const fechas = `
                        Inscripciones:<br>
                        <small>${oferta.fecha_inicio_formateada || 'Sin fecha'} - ${oferta.fecha_fin_formateada || 'Sin fecha'}</small>
                    `;

                    // Obtener el ID del cargo principal para el enlace
                    const cargoPrincipalId =
                        '{{ auth()->user()->persona->trabajador->trabajadores_cargos->where('principal', 1)->where('estado', 'Vigente')->first()->id ?? 0 }}';

                    html += `
                        <tr class="oferta-card">
                            <td>
                                <strong class="text-primary">${oferta.codigo || 'N/A'}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <strong>${oferta.programa_nombre || 'Sin programa'}</strong>
                                        <br>
                                        <small class="text-muted">${oferta.version ? 'v' + oferta.version : ''} ${oferta.grupo ? 'Grupo ' + oferta.grupo : ''}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge sucursal-badge programa-tag">
                                    ${oferta.sucursal_nombre || 'Sin sucursal'}
                                </span>
                                ${oferta.sede_nombre ? '<br><small class="text-muted">' + oferta.sede_nombre + '</small>' : ''}
                            </td>
                            <td>
                                <span class="badge bg-light text-dark programa-tag">
                                    ${oferta.modalidad_nombre || 'Sin modalidad'}
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    ${fechas}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <button class="btn btn-sm btn-outline-primary btn-ver-enlace" 
                                            data-oferta-id="${oferta.id}"
                                            data-enlace="${oferta.enlace_personalizado || '#'}"
                                            data-programa="${oferta.programa_nombre || 'Programa'}"
                                            data-sucursal="${oferta.sucursal_nombre || 'Sin sucursal'}"
                                            data-modalidad="${oferta.modalidad_nombre || 'Sin modalidad'}"
                                            data-qr="${oferta.enlace_qr || ''}">
                                        <i class="ri-link"></i>
                                    </button>
                                    <button class="btn btn-sm btn-plan-generator btn-generar-con-plan"
                                            data-oferta-id="${oferta.id}"
                                            data-oferta-codigo="${oferta.codigo}"
                                            data-programa="${oferta.programa_nombre}"
                                            data-asesor-id="${cargoPrincipalId}">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                html += `
                        </tbody>
                    </table>
                </div>
                `;
            }

            $('#ofertasContainer').html(html);
            attachEnlaceEvents();
            attachPlanEnlaceEvents();
        }

        // Adjuntar eventos a los botones de enlace con plan
        function attachPlanEnlaceEvents() {
            $('.btn-generar-con-plan').on('click', function() {
                const ofertaId = $(this).data('oferta-id');
                const asesorId = $(this).data('asesor-id');
                const ofertaCodigo = $(this).data('oferta-codigo');
                const programa = $(this).data('programa');

                // Cargar información en el modal
                $('#modalOfertaTitulo').text(`Oferta: ${ofertaCodigo}`);
                $('#modalOfertaCodigo').text(ofertaCodigo);
                $('#modalOfertaPrograma').text(programa);
                $('#modalOfertaAsesor').text('{{ auth()->user()->persona->nombres ?? 'Asesor' }}');

                // Guardar datos para uso posterior
                $('#enlacePlanModal').data('oferta-id', ofertaId);
                $('#enlacePlanModal').data('asesor-id', asesorId);

                // Cargar planes de pago
                cargarPlanesPagoParaEnlace(ofertaId);

                // Mostrar modal
                $('#enlacePlanModal').modal('show');
            });
        }

        // Función para cargar planes de pago para el modal de enlace
        function cargarPlanesPagoParaEnlace(ofertaId) {
            $('#planesPagoEnlaceContainer').html(`
        <div class="text-center py-3">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2 text-muted">Cargando planes de pago disponibles...</p>
        </div>
    `);

            $('#enlaceGeneradoContainer').hide();
            $('#visitPlanLinkBtn').hide();

            $.ajax({
                url: '/admin/profile/marketing/oferta/' + ofertaId + '/planes-pago',
                method: 'GET',
                success: function(response) {
                    if (response.success && response.planes.length > 0) {
                        let html = '<div class="row">';
                        response.planes.forEach((plan, index) => {
                            let totalPlan = plan.conceptos.reduce((sum, c) => sum +
                                parseFloat(c.pago_bs), 0);
                            html += `
                        <div class="col-md-6 mb-3">
                            <div class="plan-card plan-option" data-plan-id="${plan.id}">
                                <div class="plan-header">
                                    <h6 class="mb-0 text-white">${plan.nombre}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="plan-price">${totalPlan.toFixed(2)} Bs</div>
                                    <ul class="plan-feature-list mt-3">
                                        ${plan.conceptos.map(c => 
                                            `<li><i class="ri-checkbox-circle-line text-success me-2"></i>${c.concepto_nombre}: ${c.n_cuotas} cuotas</li>`
                                        ).join('')}
                                    </ul>
                                    <div class="text-center mt-3">
                                        <button class="btn btn-outline-primary btn-sm btn-seleccionar-plan" 
                                                data-plan-id="${plan.id}"
                                                data-plan-nombre="${plan.nombre}">
                                            Seleccionar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                        });
                        html += '</div>';
                        $('#planesPagoEnlaceContainer').html(html);

                        $('.btn-seleccionar-plan').on('click', function(e) {
                            e.stopPropagation();
                            const planId = $(this).data('plan-id');
                            const planNombre = $(this).data('plan-nombre');
                            // Recuperar ofertaId y asesorId del modal
                            const ofertaId = $('#enlacePlanModal').data('oferta-id');
                            const asesorId = $('#enlacePlanModal').data('asesor-id');
                            $('.plan-option').removeClass('selected');
                            $(this).closest('.plan-option').addClass('selected');
                            generarEnlaceConPlan(ofertaId, asesorId, planId, planNombre);
                        });
                    } else {
                        $('#planesPagoEnlaceContainer').html(`
                    <div class="alert alert-warning">
                        <i class="ri-alert-line me-2"></i>
                        No hay planes de pago disponibles para esta oferta.
                    </div>
                `);
                    }
                },
                error: function(xhr) {
                    console.error('Error al cargar planes de pago:', xhr);
                    $('#planesPagoEnlaceContainer').html(`
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line me-2"></i>
                    Error al cargar los planes de pago. Intente nuevamente.
                </div>
            `);
                }
            });
        }

        // Función para generar enlace con plan
        function generarEnlaceConPlan(ofertaId, asesorId, planId, planNombre) {
            // Construir el enlace
            const baseUrl = window.location.origin;
            const enlace = `${baseUrl}/oferta/${ofertaId}/asesor/${asesorId}?plan_pago=${planId}`;

            // Mostrar el enlace en el modal
            $('#linkText').text(enlace);
            $('#enlaceGeneradoContainer').show();

            // Generar QR
            const qrUrl =
                `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(enlace)}`;
            $('#qrCodeContainer').html(`<img src="${qrUrl}" alt="QR Code" class="img-fluid">`);

            // Configurar botón de visitar
            $('#visitPlanLinkBtn').attr('href', enlace).show();

            // Configurar botón de copiar
            $('#copyGeneratedLink').off('click').on('click', function() {
                navigator.clipboard.writeText(enlace).then(function() {
                    const originalText = $(this).html();
                    $(this).html('<i class="ri-check-line"></i>');
                    setTimeout(() => {
                        $(this).html(originalText);
                    }, 2000);
                    showToast('success', 'Enlace copiado al portapapeles');
                }.bind(this));
            });

            // Opcional: mostrar un mensaje de éxito
            showToast('success', `Enlace generado con el plan: ${planNombre}`);
        }

        // Actualizar estadísticas de ofertas
        function updateOfertasStats(response) {
            $('#totalOfertas').text(response.ofertas.total || 0);
            $('#ofertasCount').text(response.ofertas.total || 0);

            // Contar programas únicos
            const programasUnicos = [...new Set(response.ofertas.data.map(o => o.programa_id))];
            $('#totalProgramas').text(programasUnicos.length);

            // Contar sucursales únicas
            const sucursalesUnicas = [...new Set(response.ofertas.data.map(o => o.sucursale_id))];
            $('#totalSucursales').text(sucursalesUnicas.length);
        }

        // Actualizar información del cargo
        function updateCargoInfo(cargo) {
            $('#cargoActual').text(cargo.cargo_nombre || '-');
        }

        // Renderizar paginación
        function renderOfertasPagination(ofertas) {
            if (ofertas.last_page <= 1) {
                $('#ofertasPagination').html('');
                return;
            }

            let html = `
                <nav>
                    <ul class="pagination justify-content-center">
            `;

            // Botón anterior
            html += `
                <li class="page-item ${ofertas.current_page === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${ofertas.current_page - 1}">
                        <i class="ri-arrow-left-s-line"></i>
                    </a>
                </li>
            `;

            // Números de página
            for (let i = 1; i <= ofertas.last_page; i++) {
                if (i === 1 || i === ofertas.last_page || (i >= ofertas.current_page - 2 && i <= ofertas
                        .current_page + 2)) {
                    html += `
                        <li class="page-item ${ofertas.current_page === i ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `;
                } else if (i === ofertas.current_page - 3 || i === ofertas.current_page + 3) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }

            // Botón siguiente
            html += `
                <li class="page-item ${ofertas.current_page === ofertas.last_page ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${ofertas.current_page + 1}">
                        <i class="ri-arrow-right-s-line"></i>
                    </a>
                </li>
            `;

            html += `
                    </ul>
                </nav>
            `;

            $('#ofertasPagination').html(html);

            // Eventos de paginación
            $('#ofertasPagination .page-link').on('click', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (page && page !== ofertasCurrentPage) {
                    ofertasCurrentPage = page;
                    loadOfertasActivas();
                }
            });
        }

        // Adjuntar eventos a los botones de enlace
        function attachEnlaceEvents() {
            $('.btn-ver-enlace').on('click', function() {
                const enlace = $(this).data('enlace');
                const programa = $(this).data('programa');
                const sucursal = $(this).data('sucursal');
                const modalidad = $(this).data('modalidad');
                const qr = $(this).data('qr');

                $('#modalProgramaInfo').html(`
                    <div class="mb-2">
                        <strong>Programa:</strong> ${programa}
                    </div>
                    <div class="mb-2">
                        <strong>Sucursal:</strong> ${sucursal}
                    </div>
                    <div class="mb-2">
                        <strong>Modalidad:</strong> ${modalidad}
                    </div>
                    <div class="mb-2">
                        <strong>Enlace único personalizado para tu cargo</strong>
                    </div>
                `);

                if (qr) {
                    $('#modalQRCode').html(
                        `<img src="${qr}" alt="QR Code" class="img-fluid" style="max-width: 150px;">`
                    );
                } else {
                    $('#modalQRCode').html('<div class="text-muted">No se pudo generar QR</div>');
                }

                $('#modalEnlace').val(enlace);
                $('#visitLinkBtn').attr('href', enlace);

                $('#enlaceModal').modal('show');
            });

            // Copiar enlace al portapapeles
            $('#copyEnlaceBtn').on('click', function() {
                const enlaceInput = $('#modalEnlace');
                enlaceInput.select();
                document.execCommand('copy');

                // Cambiar temporalmente el texto del botón
                const originalText = $(this).html();
                $(this).html('<i class="ri-check-line"></i> Copiado');

                setTimeout(() => {
                    $(this).html(originalText);
                }, 2000);

                showToast('success', 'Enlace copiado al portapapeles');
            });
        }

        // ==============================
        // MODAL PARA CONVERTIR PRE-INSCRITO
        // ==============================

        // Evento para abrir el modal de conversión
        $(document).on('click', '.btn-convertir-inscrito', function() {
            const inscripcionId = $(this).data('inscripcion-id');
            const ofertaId = $(this).data('oferta-id');
            const estudianteNombre = $(this).data('estudiante-nombre');
            const estudianteCarnet = $(this).data('estudiante-carnet');
            const programaNombre = $(this).data('programa-nombre');

            // Guardar datos en el modal
            $('#convertirModal').data('inscripcion-id', inscripcionId);
            $('#convertirModal').data('oferta-id', ofertaId);

            // Mostrar información básica
            $('#convertirEstudianteNombre').text(estudianteNombre);
            $('#convertirEstudianteCarnet').text(estudianteCarnet);
            $('#convertirProgramaNombre').text(programaNombre);

            // Resetear estado del modal
            $('#confirmarConversionBtn').prop('disabled', false)
                .html('<i class="ri-check-double-line me-1"></i> Confirmar Conversión');

            // Cargar planes de pago usando el oferta-id
            cargarPlanesPagoOferta(ofertaId);

            // Mostrar modal
            $('#convertirModal').modal('show');
        });

        // Función mejorada para cargar planes de pago
        function cargarPlanesPagoOferta(ofertaId) {
            const container = $('#convertirModal').find('#planesPagoConversionContainer');
            container.html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2 text-muted">Cargando planes de pago disponibles...</p>
        </div>
    `);

            $.ajax({
                url: '/admin/profile/marketing/oferta/' + ofertaId + '/planes-pago',
                method: 'GET',
                success: function(response) {
                    if (response.success && response.planes.length > 0) {
                        let html = '';
                        response.planes.forEach((plan, index) => {
                            let conceptosHtml = '';
                            let totalMonto = 0;
                            plan.conceptos.forEach(concepto => {
                                const totalConcepto = Math.round(parseFloat(concepto
                                    .pago_bs));
                                conceptosHtml += `
                            <tr>
                                <td>${concepto.concepto_nombre}</td>
                                <td class="text-center">${concepto.n_cuotas}</td>
                                <td class="text-end">${totalConcepto.toLocaleString('es-BO')} Bs</td>
                                <td class="text-end">${concepto.monto_por_cuota} Bs</td>
                            </tr>
                        `;
                                totalMonto += totalConcepto;
                            });

                            html += `
                        <div class="card mb-3 plan-pago-card ${index === 0 ? 'border-primary' : ''}" data-plan-id="${plan.id}">
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input plan-radio"
                                           type="radio"
                                           name="plan_pago"
                                           id="plan_${plan.id}"
                                           value="${plan.id}"
                                           ${index === 0 ? 'checked' : ''}>
                                    <label class="form-check-label fw-bold" for="plan_${plan.id}">
                                        ${plan.nombre}
                                    </label>
                                    <span class="badge bg-primary float-end">
                                        Total: ${totalMonto.toLocaleString('es-BO')} Bs
                                    </span>
                                </div>
                                <div class="mt-3">
                                    <h6 class="mb-2 text-muted">Detalles de Cuotas:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Concepto</th>
                                                    <th class="text-center">Cuotas</th>
                                                    <th class="text-end">Total</th>
                                                    <th class="text-end">Monto/Cuota</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${conceptosHtml}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                        });
                        container.html(html);
                        container.find('.plan-radio').on('change', function() {
                            $('.plan-pago-card').removeClass('border-primary');
                            $(this).closest('.plan-pago-card').addClass('border-primary');
                        });
                    } else {
                        container.html(`
                    <div class="alert alert-warning">
                        <i class="ri-alert-line me-2"></i>
                        No hay planes de pago configurados para esta oferta.
                        <div class="mt-2">
                            <small>Contacte al administrador para configurar los planes de pago.</small>
                        </div>
                    </div>
                `);
                        $('#confirmarConversionBtn').prop('disabled', true).html(
                            '<i class="ri-forbid-line me-1"></i> No hay planes disponibles');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error al cargar los planes de pago.';
                    container.html(`
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line me-2"></i>
                    ${errorMsg}
                </div>
            `);
                    $('#confirmarConversionBtn').prop('disabled', true).html(
                        '<i class="ri-forbid-line me-1"></i> Error al cargar planes');
                }
            });
        }

        // Cambiar estilo al seleccionar plan
        $(document).on('click', '.plan-radio', function() {
            $('.plan-pago-card').removeClass('border-primary');
            $(this).closest('.plan-pago-card').addClass('border-primary');
        });

        // Confirmar conversión con mejor manejo de errores
        $('#confirmarConversionBtn').on('click', function() {
            const btn = $(this);
            const originalText = btn.html();
            const inscripcionId = $('#convertirModal').data('inscripcion-id');
            const planPagoId = $('input[name="plan_pago"]:checked').val();
            const observacion = $('#observacionConversion').val();

            if (!planPagoId) {
                showToast('error', 'Por favor selecciona un plan de pago para continuar');
                return;
            }

            // Deshabilitar botón y mostrar loading
            btn.prop('disabled', true).html(`
        <span class="spinner-border spinner-border-sm me-1"></span>
        Generando inscripción, matriculaciones y cuotas...
    `);

            $.ajax({
                url: '/admin/profile/marketing/convertir-inscrito',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    inscripcion_id: inscripcionId,
                    plan_pago_id: planPagoId,
                    observacion: observacion
                },
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);

                        // Cerrar modal después de un breve retraso
                        setTimeout(() => {
                            $('#convertirModal').modal('hide');

                            // Recargar la tabla de inscripciones
                            loadMarketingData(currentPage);

                            // Resetear formulario
                            $('input[name="plan_pago"]').prop('checked', false);
                            $('#observacionConversion').val('');
                            $('.plan-pago-card').removeClass('border-primary');
                        }, 1500);
                    } else {
                        showToast('error', response.message ||
                            'Error al convertir la inscripción');
                        btn.prop('disabled', false).html(originalText);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Error al procesar la solicitud';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                            '<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showToast('error', errorMessage);
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });

        // ==============================
        // FUNCIONES AUXILIARES COMUNES
        // ==============================

        // Manejar clic en paginación de marketing
        $(document).on('click', '#marketingPagination .page-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (page) {
                loadMarketingData(page);
            }
        });

        // Función para mostrar toast
        function showToast(type, message) {
            // Usar Toastr si está disponible
            if (typeof toastr !== 'undefined') {
                toastr[type](message);
            }
            // Usar SweetAlert si está disponible
            else if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });

                Toast.fire({
                    icon: type,
                    title: message
                });
            }
            // Fallback a alert nativo
            else {
                alert(`${type.toUpperCase()}: ${message}`);
            }
        }

        // Funciones auxiliares
        function showLoading(selector) {
            $(selector).html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando ofertas activas...</p>
                </div>
            `);
        }

        function hideLoading(selector) {
            // Se maneja en cada función específica
        }

        // Exportar a PDF
        $('#exportPDF').on('click', function(e) {
            e.preventDefault();
            alert('Funcionalidad de exportar a PDF en desarrollo');
        });

        // Exportar a Excel
        $('#exportExcel').on('click', function(e) {
            e.preventDefault();
            alert('Funcionalidad de exportar a Excel en desarrollo');
        });

        // Exportar gráfico
        $('#exportChart').on('click', function(e) {
            e.preventDefault();
            alert('Funcionalidad de exportar gráfico en desarrollo');
        });

        // Exportar ofertas
        $('#exportOfertasCSV').on('click', function(e) {
            e.preventDefault();
            exportOfertas('csv');
        });

        $('#exportOfertasPDF').on('click', function(e) {
            e.preventDefault();
            exportOfertas('pdf');
        });

        function exportOfertas(format) {
            const params = new URLSearchParams({
                ...ofertasFilters,
                format: format,
                _token: '{{ csrf_token() }}'
            });

            window.open('{{ route('admin.profile.marketing.ofertas-activas') }}?' + params.toString(),
                '_blank');
        }

        // ==============================
        // SUBIDA DE FOTOGRAFÍA
        // ==============================

        // Vista previa de la imagen antes de subir
        $('#fotografia').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').show();
                    $('#previewImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // Subir foto
        $('#submitFotoBtn').on('click', function() {
            const formData = new FormData($('#uploadFotoForm')[0]);
            const btn = $(this);
            const originalText = btn.html();

            // Deshabilitar botón y mostrar loading
            btn.prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                Subiendo...
            `);

            $.ajax({
                url: '{{ route('admin.profile.upload-foto') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Actualizar imagen en la vista
                        $('#profileAvatar').attr('src', response.foto_url);

                        // Mostrar notificación de éxito
                        showToast('success', response.message);

                        // Cerrar modal
                        $('#uploadFotoModal').modal('hide');

                        // Resetear formulario
                        $('#uploadFotoForm')[0].reset();
                        $('#imagePreview').hide();
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Error al subir la imagen';

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = xhr.responseJSON.errors.fotografia ?
                            xhr.responseJSON.errors.fotografia[0] :
                            errorMessage;
                    }

                    showToast('error', errorMessage);
                },
                complete: function() {
                    // Restaurar botón
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Cerrar modal al hacer click fuera
        $('#uploadFotoModal').on('hidden.bs.modal', function() {
            $('#uploadFotoForm')[0].reset();
            $('#imagePreview').hide();
        });

        // ==============================
        // CAMBIO DE CONTRASEÑA
        // ==============================

        // Mostrar/ocultar contraseña
        $(document).on('click', '.toggle-password', function() {
            const input = $(this).closest('.input-group').find('input');
            const icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
            } else {
                input.attr('type', 'password');
                icon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
            }
        });

        // Validar fortaleza de contraseña
        $('#new_password').on('keyup', function() {
            const password = $(this).val();
            const strengthBar = $('#passwordStrengthBar');
            const strengthText = $('#passwordStrengthText');

            // Inicializar puntuación
            let score = 0;

            // Longitud mínima
            if (password.length >= 8) score += 20;
            if (password.length >= 12) score += 10;

            // Contiene números
            if (/\d/.test(password)) score += 20;

            // Contiene letras
            if (/[a-zA-Z]/.test(password)) score += 20;

            // Contiene mayúsculas y minúsculas
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score += 20;

            // Contiene caracteres especiales
            if (/[^a-zA-Z0-9]/.test(password)) score += 10;

            // Actualizar barra y texto
            strengthBar.css('width', score + '%');

            if (score < 40) {
                strengthBar.removeClass('bg-warning bg-success').addClass('bg-danger');
                strengthText.text('Débil').removeClass('text-warning text-success').addClass(
                    'text-danger');
            } else if (score < 70) {
                strengthBar.removeClass('bg-danger bg-success').addClass('bg-warning');
                strengthText.text('Media').removeClass('text-danger text-success').addClass(
                    'text-warning');
            } else {
                strengthBar.removeClass('bg-danger bg-warning').addClass('bg-success');
                strengthText.text('Fuerte').removeClass('text-danger text-warning').addClass(
                    'text-success');
            }

            // Validar que tenga letras y números
            const hasLetter = /[a-zA-Z]/.test(password);
            const hasNumber = /\d/.test(password);

            if (password.length > 0) {
                if (!hasLetter || !hasNumber) {
                    strengthText.text('Debe contener letras y números');
                }
            }
        });

        // Validar coincidencia de contraseñas
        $('#new_password_confirmation').on('keyup', function() {
            const password = $('#new_password').val();
            const confirmPassword = $(this).val();
            const matchText = $('#passwordMatch');

            if (confirmPassword.length === 0) {
                matchText.text('Confirma tu nueva contraseña').removeClass('text-success text-danger');
                return;
            }

            if (password === confirmPassword) {
                matchText.text('Las contraseñas coinciden').removeClass('text-danger').addClass(
                    'text-success');
            } else {
                matchText.text('Las contraseñas no coinciden').removeClass('text-success').addClass(
                    'text-danger');
            }
        });

        // Enviar formulario de cambio de contraseña
        $('#changePasswordForm').on('submit', function(e) {
            e.preventDefault();

            const btn = $('#changePasswordBtn');
            const originalText = btn.html();

            // Validar contraseña
            const newPassword = $('#new_password').val();
            const hasLetter = /[a-zA-Z]/.test(newPassword);
            const hasNumber = /\d/.test(newPassword);

            if (newPassword.length < 8) {
                showToast('error', 'La contraseña debe tener al menos 8 caracteres');
                return;
            }

            if (!hasLetter || !hasNumber) {
                showToast('error', 'La contraseña debe contener letras y números');
                return;
            }

            if ($('#new_password').val() !== $('#new_password_confirmation').val()) {
                showToast('error', 'Las contraseñas no coinciden');
                return;
            }

            // Deshabilitar botón y mostrar loading
            btn.prop('disabled', true).html(`
        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
        Actualizando...
    `);

            $.ajax({
                url: '{{ route('admin.profile.change-password') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                        $('#changePasswordForm')[0].reset();
                        $('#passwordStrengthBar').css('width', '0%');
                        $('#passwordStrengthText').text('');
                        $('#passwordMatch').text('');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessages = '';

                        $.each(errors, function(key, value) {
                            errorMessages += value[0] + '<br>';
                        });

                        showToast('error', errorMessages);
                    } else {
                        showToast('error', 'Error al actualizar la contraseña');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });

        // ============================================
        // APARTADO: DOCUMENTOS DE INSCRITOS
        // ============================================
        let docsCurrentPage = 1;
        let docsSearch = '';

        // Cargar documentos cuando se muestra la pestaña de marketing
        $(document).on('shown.bs.tab', 'a[href="#marketing"]', function() {
            loadDocumentosData();
        });

        // Botón para cargar documentos manualmente
        $('#loadDocumentosBtn').on('click', function() {
            loadDocumentosData();
        });

        // Búsqueda en documentos (al presionar Enter)
        $('#searchDocumentos').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                docsSearch = $(this).val();
                docsCurrentPage = 1;
                loadDocumentosData();
            }
        });

        // Búsqueda al hacer clic en el botón de búsqueda (si tienes uno)
        $('#searchDocumentosBtn').on('click', function() {
            docsSearch = $('#searchDocumentos').val();
            docsCurrentPage = 1;
            loadDocumentosData();
        });

        function loadDocumentosData(page = 1) {
            docsCurrentPage = page;

            // Mostrar loading
            $('#documentosTableContainer').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2 text-muted">Cargando estado de documentos...</p>
        </div>
    `);

            $.ajax({
                url: '{{ route('admin.profile.marketing.inscritos-documentos') }}',
                method: 'GET',
                data: {
                    search: docsSearch,
                    page: page,
                    per_page: 10
                },
                success: function(response) {
                    if (response.success) {
                        renderDocumentosTable(response.inscripciones);
                        renderDocumentosPagination(response.pagination);
                    } else {
                        showToast('error', response.message || 'Error al cargar los documentos');
                        $('#documentosTableContainer').html(`
                    <div class="alert alert-danger">${response.message || 'Error desconocido'}</div>
                `);
                    }
                },
                error: function(xhr) {
                    console.error('Error al cargar documentos:', xhr);
                    showToast('error', 'Error de conexión al cargar documentos');
                    $('#documentosTableContainer').html(`
                <div class="alert alert-danger">Error al cargar los datos. Intente nuevamente.</div>
            `);
                }
            });
        }

        function renderDocumentosTable(paginator) {
            const data = paginator.data;
            if (!data || data.length === 0) {
                $('#documentosTableContainer').html(`
            <div class="text-center py-5">
                <i class="ri-emotion-sad-line display-4 text-muted"></i>
                <h5 class="mt-3 text-muted">No hay inscritos con documentos pendientes</h5>
                <p class="text-muted">Los estudiantes con estado "Inscrito" aparecerán aquí.</p>
            </div>
        `);
                $('#documentosCount').text('0');
                return;
            }

            $('#documentosCount').text(paginator.total);

            let html = `
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Estudiante</th>
                        <th>Programa</th>
                        <th>Sede/Sucursal</th>
                        <th>Carnet</th>
                        <th>Cert. Nacimiento</th>
                        <th>Doc. Académico</th>
                        <th>Provisión Nacional</th>
                        <th>Estado</th>
                        <th>Pago Inicial</th>
                        <th>Comprobante</th> <!-- Nueva columna -->
                    </tr>
                </thead>
                <tbody>
    `;

            data.forEach((item, index) => {
                const rowNumber = (paginator.current_page - 1) * paginator.per_page + index + 1;
                const docs = item.documentos || {};

                const badge = (estado) => {
                    switch (estado) {
                        case 'verificado':
                            return '<span class="badge bg-success" title="Documento verificado"><i class="ri-check-line"></i> Verificado</span>';
                        case 'pendiente':
                            return '<span class="badge bg-warning text-dark" title="Documento pendiente de verificación"><i class="ri-time-line"></i> Pendiente</span>';
                        case 'sin_archivo':
                            return '<span class="badge bg-secondary" title="Sin archivo subido"><i class="ri-close-line"></i> Sin archivo</span>';
                        default:
                            return '<span class="badge bg-light text-dark">Desconocido</span>';
                    }
                };

                const todosVerificados = docs.carnet === 'verificado' &&
                    docs.certificado_nacimiento === 'verificado' &&
                    docs.documento_academico === 'verificado' &&
                    docs.documento_provision === 'verificado';

                const estadoGeneral = todosVerificados ?
                    '<span class="badge bg-success">Completos</span>' :
                    '<span class="badge bg-danger">Incompletos</span>';

                const pagoInicialBadge = item.pagos_iniciales_completos ?
                    '<span class="badge bg-success"><i class="ri-check-line"></i> Completos</span>' :
                    '<span class="badge bg-warning text-dark"><i class="ri-time-line"></i> Pendiente</span>';

                // Botón para subir comprobante
                const botonSubir = `
            <button class="btn btn-sm btn-outline-primary btn-subir-respaldo"
                    data-inscripcion-id="${item.id}"
                    data-estudiante="${item.estudiante_nombre}"
                    data-programa="${item.programa_nombre}">
                <i class="ri-upload-line"></i> Subir
            </button>
        `;

                html += `
            <tr class="${todosVerificados ? 'table-success' : 'table-warning'}">
                <td>${rowNumber}</td>
                <td>
                    <strong>${item.estudiante_nombre || 'N/A'}</strong>
                    <br>
                    <small class="text-muted">${item.estudiante_carnet || 'Sin carnet'}</small>
                </td>
                <td>${item.programa_nombre || 'N/A'}</td>
                <td>
                    ${item.sede_nombre || 'N/A'}
                    <br>
                    <small>${item.sucursal_nombre || ''}</small>
                </td>
                <td>${badge(docs.carnet)}</td>
                <td>${badge(docs.certificado_nacimiento)}</td>
                <td>${badge(docs.documento_academico)}</td>
                <td>${badge(docs.documento_provision)}</td>
                <td>${estadoGeneral}</td>
                <td>${pagoInicialBadge}</td>
                <td>${botonSubir}</td>
            </tr>
        `;
            });

            html += `
                </tbody>
            </table>
        </div>
    `;

            $('#documentosTableContainer').html(html);
        }

        // Abrir modal de subir comprobante
        $(document).on('click', '.btn-subir-respaldo', function() {
            const inscripcionId = $(this).data('inscripcion-id');
            const estudiante = $(this).data('estudiante');
            const programa = $(this).data('programa');

            $('#respaldo_inscripcione_id').val(inscripcionId);
            $('#respaldo_estudiante').text(estudiante);
            $('#respaldo_programa').text(programa);
            $('#cuotasCheckboxContainer').html(
                '<div class="text-muted">Cargando cuotas pendientes...</div>');

            // Cargar cuotas de la inscripción
            $.ajax({
                url: `/admin/profile/marketing/inscripcion/${inscripcionId}/cuotas`,
                method: 'GET',
                success: function(response) {
                    if (response.success && response.cuotas.length > 0) {
                        let html = '<div class="list-group">';
                        response.cuotas.forEach(cuota => {
                            html += `
                        <label class="list-group-item">
                            <input class="form-check-input me-2" type="checkbox" name="cuota_ids[]" value="${cuota.id}">
                            <strong>${cuota.nombre}</strong> (Cuota ${cuota.n_cuota})
                            <br>
                            <small class="text-muted">Pendiente: ${cuota.pendiente} / Total: ${cuota.total}</small>
                        </label>
                    `;
                        });
                        html += '</div>';
                        $('#cuotasCheckboxContainer').html(html);
                    } else {
                        $('#cuotasCheckboxContainer').html(
                            '<div class="alert alert-warning">No hay cuotas con saldo pendiente.</div>'
                        );
                    }
                },
                error: function() {
                    $('#cuotasCheckboxContainer').html(
                        '<div class="alert alert-danger">Error al cargar cuotas.</div>');
                }
            });

            // Resetear formulario
            $('#formSubirRespaldo')[0].reset();
            $('#respaldo_archivo').val('');

            $('#modalSubirRespaldo').modal('show');
        });

        // Enviar formulario de subida
        $('#formSubirRespaldo').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const btn = $('#btnSubirRespaldo');
            const originalText = btn.html();

            btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Subiendo...');

            $.ajax({
                url: '{{ route('admin.profile.marketing.subir-respaldo') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                        $('#modalSubirRespaldo').modal('hide');
                        // Opcional: recargar la tabla de documentos para mostrar algún cambio
                        // loadDocumentosData(docsCurrentPage);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error al subir el comprobante';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    showToast('error', errorMsg);
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });

        function renderDocumentosPagination(pagination) {
            if (pagination.last_page <= 1) {
                $('#documentosPagination').html('');
                return;
            }

            let html = `
        <nav>
            <ul class="pagination justify-content-center">
    `;

            // Botón anterior
            html += `
        <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${pagination.current_page - 1}">
                <i class="ri-arrow-left-s-line"></i>
            </a>
        </li>
    `;

            // Números de página
            for (let i = 1; i <= pagination.last_page; i++) {
                if (i === 1 || i === pagination.last_page || (i >= pagination.current_page - 2 && i <=
                        pagination.current_page + 2)) {
                    html += `
                <li class="page-item ${pagination.current_page === i ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
                } else if (i === pagination.current_page - 3 || i === pagination.current_page + 3) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }

            // Botón siguiente
            html += `
        <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${pagination.current_page + 1}">
                <i class="ri-arrow-right-s-line"></i>
            </a>
        </li>
    `;

            html += `
            </ul>
        </nav>
    `;

            $('#documentosPagination').html(html);

            // Eventos de paginación
            $('#documentosPagination .page-link').on('click', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (page && page !== docsCurrentPage) {
                    loadDocumentosData(page);
                }
            });
        }

        // Evento para abrir modal de subir comprobante
        $(document).on('click', '.btn-subir-comprobante', function() {
            const inscripcionId = $(this).data('inscripcion-id');
            const estudiante = $(this).data('estudiante');
            const programa = $(this).data('programa');

            $('#respaldo_inscripcion_id').val(inscripcionId);
            $('#respaldo_programa').text(programa);
            $('#respaldo_estudiante').text(estudiante);

            // Limpiar select y cargar cuotas de esta inscripción
            $('#respaldo_cuota_select').html('<option value="">Cargando cuotas...</option>');

            $.ajax({
                url: '/admin/profile/marketing/inscripcion/' + inscripcionId + '/cuotas',
                method: 'GET',
                success: function(response) {
                    let options =
                        '<option value="">-- Seleccionar cuota específica (opcional) --</option>';
                    response.cuotas.forEach(cuota => {
                        options +=
                            `<option value="${cuota.id}">${cuota.nombre} - ${cuota.n_cuota}° cuota - ${cuota.pago_pendiente_bs} Bs pendientes</option>`;
                    });
                    $('#respaldo_cuota_select').html(options);
                },
                error: function() {
                    $('#respaldo_cuota_select').html(
                        '<option value="">Error al cargar cuotas</option>');
                }
            });

            $('#modalSubirComprobante').modal('show');
        });

        $('#formSubirComprobante').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let btn = $('#btnSubirComprobante');
            let originalText = btn.html();

            btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Subiendo...');

            $.ajax({
                url: '{{ route('admin.profile.marketing.subir-respaldo') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                        $('#modalSubirComprobante').modal('hide');
                        $('#formSubirComprobante')[0].reset();
                        // Opcional: recargar la tabla para mostrar el estado actualizado
                        // cargarDocumentos(docsCurrentPage);
                    } else {
                        showToast('error', response.message ||
                            'Error al subir comprobante');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error al subir el comprobante';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors).flat().join(
                            '<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    showToast('error', errorMsg);
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });
    });
</script>
