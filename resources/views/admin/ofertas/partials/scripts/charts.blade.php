<!-- Scripts para Gráficos Mejorados -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {
        // Configurar gráficos cuando se activa una pestaña
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            window.dispatchEvent(new Event('resize'));
        });

        Chart.defaults.font.family = "'Outfit', sans-serif";
        Chart.defaults.color = '#64748b';

        // Gráfico de inscripciones por mes - LINE con datos en gráfico
        const ctx1 = document.getElementById('inscripcionesChart');
        if (ctx1) {
            const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
            const inscritosData = @json(array_column($inscripcionesPorMes, 'Inscrito'));
            const preInscritosData = @json(array_column($inscripcionesPorMes, 'Pre-Inscrito'));
            const conAdelantoData = @json(array_column($inscripcionesPorMes, 'Inscrito-Con-Adelanto'));

            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: meses,
                    datasets: [{
                            label: 'Inscritos',
                            data: inscritosData,
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22, 163, 74, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#16a34a',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5
                        },
                        {
                            label: 'Pre-Inscritos',
                            data: preInscritosData,
                            borderColor: '#d97706',
                            backgroundColor: 'rgba(217, 119, 6, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#d97706',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5
                        },
                        {
                            label: 'Con Adelanto',
                            data: conAdelantoData,
                            borderColor: '#0891b2',
                            backgroundColor: 'rgba(8, 145, 178, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#0891b2',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: { size: 10 }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 11 },
                            padding: 10,
                            cornerRadius: 6,
                            displayColors: true
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#e2e8f0' },
                            ticks: { stepSize: 1, font: { size: 10 } }
                        }
                    },
                    interaction: { intersect: false, mode: 'index' }
                }
            });
        }

        // Gráfico de distribución por estado - DOUGHNUT
        const ctx2 = document.getElementById('estadoChart');
        if (ctx2) {
            const totalInscritos = {{ $totalInscritos }};
            const totalPreInscritos = {{ $totalPreInscritos }};
            const totalConAdelanto = {{ $totalInscritosConAdelanto }};
            const totalGeneral = totalInscritos + totalPreInscritos + totalConAdelanto;

            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Inscritos', 'Pre-Inscritos', 'Con Adelanto'],
                    datasets: [{
                        data: [totalInscritos, totalPreInscritos, totalConAdelanto],
                        backgroundColor: [
                            'rgba(22, 163, 74, 0.9)',
                            'rgba(217, 119, 6, 0.9)',
                            'rgba(8, 145, 178, 0.9)'
                        ],
                        borderColor: ['#16a34a', '#d97706', '#0891b2'],
                        borderWidth: 2,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 10, usePointStyle: true, font: { size: 9 } }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleFont: { size: 12 },
                            bodyFont: { size: 11 },
                            padding: 8,
                            cornerRadius: 6,
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed;
                                    const percentage = totalGeneral > 0 ? ((value / totalGeneral) * 100).toFixed(1) : 0;
                                    return context.label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Gráfico de distribución por sexo
        const ctx3 = document.getElementById('sexoChart');
        if (ctx3) {
            const hombres = {{ $hombres }};
            const mujeres = {{ $mujeres }};
            const totalSexo = hombres + mujeres;

            new Chart(ctx3, {
                type: 'doughnut',
                data: {
                    labels: ['Hombres', 'Mujeres'],
                    datasets: [{
                        data: [hombres, mujeres],
                        backgroundColor: [
                            'rgba(37, 99, 235, 0.9)',
                            'rgba(232, 62, 140, 0.9)'
                        ],
                        borderColor: ['#2563eb', '#e83e8c'],
                        borderWidth: 2,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                font: { size: 11 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed;
                                    const percentage = totalSexo > 0 ? ((value / totalSexo) * 100).toFixed(1) : 0;
                                    return context.label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                },
                plugins: [{
                    id: 'textCenterSexo',
                    beforeDraw: function(chart) {
                        const { width, height, ctx } = chart;
                        ctx.restore();
                        ctx.font = "bold 24px Outfit";
                        ctx.fillStyle = "#0f172a";
                        ctx.textAlign = "center";
                        ctx.textBaseline = "middle";
                        ctx.fillText(totalSexo, width / 2, height / 2 - 8);
                        ctx.font = "11px Outfit";
                        ctx.fillStyle = "#64748b";
                        ctx.fillText("Est.", width / 2, height / 2 + 12);
                        ctx.save();
                    }
                }]
            });
        }

        // Gráfico de distribución por departamento
        const ctx4 = document.getElementById('departamentosChart');
        if (ctx4) {
            const departamentosLabels = @json(array_keys($estadisticasDemograficas['topDepartamentos']));
            const departamentosData = @json(array_values($estadisticasDemograficas['topDepartamentos']));
            const departamentosColors = [
                'rgba(37, 99, 235, 0.9)',
                'rgba(22, 163, 74, 0.9)',
                'rgba(217, 119, 6, 0.9)',
                'rgba(220, 38, 38, 0.9)',
                'rgba(139, 92, 246, 0.9)'
            ];
            const totalDepto = departamentosData.reduce((a, b) => a + b, 0);

            new Chart(ctx4, {
                type: 'doughnut',
                data: {
                    labels: departamentosLabels,
                    datasets: [{
                        data: departamentosData,
                        backgroundColor: departamentosColors,
                        borderColor: departamentosColors.map(color => color.replace('0.9', '1')),
                        borderWidth: 2,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                font: { size: 11 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed;
                                    const percentage = totalDepto > 0 ? ((value / totalDepto) * 100).toFixed(1) : 0;
                                    return context.label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        // Gráfico de ingresos por concepto
        const ingresosConceptoChart = document.getElementById('ingresosConceptoChart');
        if (ingresosConceptoChart) {
            const conceptos = @json(array_keys($resumenPorConcepto));
            const totales = @json(array_column($resumenPorConcepto, 'total'));
            const totalConcepto = totales.reduce((a, b) => a + b, 0);
            const backgroundColors = [
                'rgba(37, 99, 235, 0.9)',
                'rgba(22, 163, 74, 0.9)',
                'rgba(217, 119, 6, 0.9)'
            ];

            new Chart(ingresosConceptoChart, {
                type: 'doughnut',
                data: {
                    labels: conceptos,
                    datasets: [{
                        data: totales,
                        backgroundColor: backgroundColors,
                        borderColor: backgroundColors.map(color => color.replace('0.9', '1')),
                        borderWidth: 2,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed;
                                    const percentage = totalConcepto > 0 ? ((value / totalConcepto) * 100).toFixed(1) : 0;
                                    return context.label + ': Bs ' + value.toLocaleString('es-BO') + ' (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    cutout: '55%'
                }
            });
        }

        // Gráfico de cobranza por concepto - BAR
        const cobranzaConceptoChart = document.getElementById('cobranzaConceptoChart');
        if (cobranzaConceptoChart) {
            const conceptos = @json(array_keys($resumenPorConcepto));
            const pagado = @json(array_column($resumenPorConcepto, 'pagado'));
            const pendiente = @json(array_column($resumenPorConcepto, 'pendiente'));

            new Chart(cobranzaConceptoChart, {
                type: 'bar',
                data: {
                    labels: conceptos,
                    datasets: [{
                            label: 'Pagado (Bs)',
                            data: pagado,
                            backgroundColor: 'rgba(22, 163, 74, 0.8)',
                            borderColor: '#16a34a',
                            borderWidth: 1,
                            borderRadius: 6,
                            barPercentage: 0.6
                        },
                        {
                            label: 'Pendiente (Bs)',
                            data: pendiente,
                            backgroundColor: 'rgba(220, 38, 38, 0.8)',
                            borderColor: '#dc2626',
                            borderWidth: 1,
                            borderRadius: 6,
                            barPercentage: 0.6
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
                                padding: 15,
                                font: { size: 11 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label;
                                    const value = context.parsed.y;
                                    const total = pagado[context.dataIndex] + pendiente[context.dataIndex];
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return label + ': Bs ' + value.toLocaleString('es-BO') + ' (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#e2e8f0' },
                            ticks: {
                                callback: function(value) {
                                    return 'Bs ' + value.toLocaleString('es-BO');
                                },
                                font: { size: 10 }
                            }
                        }
                    },
                    interaction: { intersect: false, mode: 'index' }
                }
            });
        }

        // Manejar clic en módulos
        $(document).on('click', '.modulo-header, .modulo-cell', function() {
            const moduloId = $(this).data('modulo-id');
            const ofertaId = $(this).data('oferta-id');
            if (moduloId && ofertaId) {
                window.location.href = '/admin/ofertas/' + ofertaId + '/modulo/' + moduloId + '/detalle';
            }
        });

        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                html: true,
                placement: 'left'
            });
        });
    });
</script>