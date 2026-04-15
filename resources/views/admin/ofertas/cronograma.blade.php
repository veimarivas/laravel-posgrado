@extends('admin.dashboard')

@push('style')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap');

        :root {
            --crono-primary: #0d9488;
            --crono-primary-light: #e6fffa;
            --crono-primary-dark: #0f766e;
            --crono-accent: #f59e0b;
            --crono-surface: #f8fafc;
            --crono-border: #e2e8f0;
            --crono-text: #1e293b;
            --crono-text-muted: #64748b;
            --crono-success: #10b981;
            --crono-danger: #ef4444;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
        }

        .crono-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--crono-text);
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .crono-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
            padding: 24px 28px;
            background: linear-gradient(135deg, var(--crono-primary) 0%, var(--crono-primary-dark) 100%);
            border-radius: var(--radius-lg);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .crono-header::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -5%;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .crono-header::after {
            content: '';
            position: absolute;
            bottom: -25%;
            left: 15%;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .crono-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1;
            color: white;
        }

        .crono-header .breadcrumb {
            position: relative;
            z-index: 1;
        }

        .crono-header .breadcrumb a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
        }

        .crono-header .breadcrumb a:hover { color: white; }

        .crono-header .breadcrumb-item.active { color: rgba(255, 255, 255, 0.9); }

        .crono-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--crono-border);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .crono-card:hover { box-shadow: var(--shadow-md); }

        .crono-card-header {
            padding: 16px 20px;
            border-bottom: 1px dashed var(--crono-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--crono-surface);
        }

        .crono-card-header h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            margin: 0;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .crono-card-header h6 i { color: var(--crono-primary); }

        .filtro-label {
            background: var(--crono-primary-light);
            color: var(--crono-primary);
            border: 1px solid var(--crono-primary);
            font-size: 0.72rem;
            padding: 4px 10px;
            border-radius: 50px;
        }

        .filtro-group {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filtro-group .form-group {
            flex: 1;
            min-width: 200px;
        }

        .filtro-group .form-label {
            font-weight: 500;
            font-size: 0.82rem;
            color: var(--crono-text);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filtro-group .form-label i {
            color: var(--crono-primary);
            font-size: 0.9rem;
        }

        .filtro-group .form-select {
            border: 1px solid var(--crono-border);
            border-radius: var(--radius-sm);
            padding: 8px 12px;
            font-size: 0.85rem;
            transition: all 0.15s ease;
            width: 100%;
        }

        .filtro-group .form-select:focus {
            border-color: var(--crono-primary);
            box-shadow: 0 0 0 3px var(--crono-primary-light);
            outline: none;
        }

        .filtro-group .form-select:disabled {
            background: var(--crono-surface);
            cursor: not-allowed;
        }

        .oferta-card {
            background: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--crono-border);
            transition: all 0.2s ease;
            cursor: pointer;
            overflow: hidden;
        }

        .oferta-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .oferta-card.seleccionada {
            border-color: var(--crono-primary);
            box-shadow: 0 0 0 2px var(--crono-primary-light);
        }

        .oferta-card .oferta-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--crono-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .oferta-card .oferta-badge {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .oferta-card .oferta-body {
            padding: 16px;
        }

        .oferta-card .oferta-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 8px;
            color: var(--crono-text);
        }

        .oferta-card .oferta-meta {
            font-size: 0.75rem;
            color: var(--crono-text-muted);
        }

        .oferta-card .oferta-footer {
            padding: 12px 16px;
            background: var(--crono-surface);
            border-top: 1px solid var(--crono-border);
        }

        .oferta-card .btn-ver-crono {
            width: 100%;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-weight: 500;
            font-size: 0.8rem;
            transition: all 0.2s ease;
        }

        .oferta-card .btn-ver-crono:not(.btn-primary) {
            background: white;
            border: 1px solid var(--crono-border);
            color: var(--crono-text-muted);
        }

        .oferta-card .btn-ver-crono:not(.btn-primary):hover {
            background: var(--crono-border);
            color: var(--crono-text);
        }

        .oferta-card .btn-ver-crono.btn-primary {
            background: var(--crono-primary);
            border-color: var(--crono-primary);
            color: white;
        }

        .oferta-card .btn-ver-crono.btn-primary:hover {
            background: var(--crono-primary-dark);
            border-color: var(--crono-primary-dark);
        }

        .section-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            color: var(--crono-text);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i { color: var(--crono-primary); }

        .empty-state {
            padding: 40px 20px;
            text-align: center;
        }

        .empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 12px;
        }

        .empty-state p {
            color: var(--crono-text-muted);
            margin: 0;
            font-size: 0.85rem;
        }

        /* FullCalendar Custom Styles */
        .fc-event {
            border-radius: 6px !important;
            padding: 2px 6px !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            border-left: 3px solid rgba(0, 0, 0, 0.2) !important;
        }
        
        .fc-event-title { font-weight: 600 !important; }
        .fc-daygrid-event { padding: 2px 4px !important; }
        
        .fc .fc-toolbar-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--crono-text);
        }
        
        .fc .fc-button {
            background: var(--crono-surface) !important;
            border: 1px solid var(--crono-border) !important;
            color: var(--crono-text-muted) !important;
            font-weight: 500 !important;
            font-size: 0.8rem !important;
            padding: 6px 12px !important;
            border-radius: var(--radius-sm) !important;
            transition: all 0.15s ease !important;
        }
        
        .fc .fc-button:hover {
            background: var(--crono-border) !important;
            color: var(--crono-text) !important;
        }
        
        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active {
            background: var(--crono-primary) !important;
            border-color: var(--crono-primary) !important;
            color: white !important;
        }
        
        .fc .fc-daygrid-day-number {
            font-size: 0.85rem;
            color: var(--crono-text-muted);
        }
        
        .fc .fc-col-header-cell-cushion {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--crono-text-muted);
            text-transform: uppercase;
        }
        
        .fc-theme-standard td, .fc-theme-standard th {
            border-color: var(--crono-border) !important;
        }
        
        .fc-day-today {
            background: var(--crono-primary-light) !important;
        }

        /* Modal Styles */
        .detalle-modal .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .detalle-modal .modal-header {
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }

        .detalle-modal .modal-header h5 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detalle-modal .modal-header h5 i {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .detalle-modal .modal-body { padding: 24px; }
        
        .detalle-modal .detail-row {
            display: flex;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid var(--crono-surface);
        }

        .detalle-modal .detail-row:last-child { border-bottom: none; }

        .detalle-modal .detail-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--crono-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 100px;
            flex-shrink: 0;
        }

        .detalle-modal .detail-value {
            font-size: 0.9rem;
            color: var(--crono-text);
            flex: 1;
        }

        .detalle-modal .estado-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .detalle-modal .btn {
            border-radius: var(--radius-sm);
            font-weight: 500;
            font-size: 0.85rem;
            padding: 8px 16px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        @media (max-width: 767.98px) {
            .crono-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .filtro-group {
                flex-direction: column;
            }
            .filtro-group .form-group {
                width: 100%;
            }
        }
    </style>
@endpush

@section('admin')
    <div class="crono-page">
        {{-- Header --}}
        <div class="crono-header">
            <div>
                <ol class="breadcrumb mb-2" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.ofertas.listar') }}">Ofertas</a></li>
                    <li class="breadcrumb-item active">Cronograma</li>
                </ol>
                <h1><i class="ri-calendar-check-line me-2"></i>Cronograma de Ofertas Académicas</h1>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="crono-card mb-4">
            <div class="crono-card-header">
                <h6><i class="ri-filter-3-line"></i>Filtros</h6>
                <span id="filtro-actual" class="filtro-label" style="display: none;"></span>
            </div>
            <div class="card-body">
                <div class="filtro-group">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="ri-map-pin-line"></i>Sede
                        </label>
                        <select id="filtro-sede" class="form-select">
                            <option value="">Todas las sedes</option>
                            @foreach ($sedes as $sede)
                                <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="ri-store-line"></i>Sucursal
                        </label>
                        <select id="filtro-sucursal" class="form-select" disabled>
                            <option value="">Todas las sucursales</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Listado de Ofertas --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="section-title mb-0">
                        <i class="ri-book-2-line"></i>Ofertas Académicas
                    </h5>
                    <span id="contador-ofertas" class="badge bg-light text-dark"></span>
                </div>
                <div id="lista-ofertas" class="row g-3">
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="ri-map-pin-line"></i>
                            <p>Selecciona una sede o sucursal para ver ofertas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Calendario --}}
        <div class="crono-card">
            <div class="crono-card-header">
                <h6><i class="ri-calendar-line"></i>Calendario de Sesiones</h6>
            </div>
            <div class="card-body p-3">
                <div id="calendar-cronograma"></div>
            </div>
        </div>
    </div>

    <!-- Modal Detalle de Horario -->
    <div class="modal fade detalle-modal" id="modalDetalleHorario" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: #6f42c1;">
                    <h5 class="text-white">
                        <i class="ri-calendar-event-line"></i>
                        Detalles de la Sesión
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-book-2-line"></i> Módulo</div>
                                <div class="detail-value fw-semibold" id="detalle-modulo"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-graduation-cap-line"></i> Oferta</div>
                                <div class="detail-value" id="detalle-oferta"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-user-line"></i> Docente</div>
                                <div class="detail-value" id="detalle-docente">—</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-user-star-line"></i> Responsable</div>
                                <div class="detail-value" id="detalle-responsable"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-briefcase-line"></i> Cargo</div>
                                <div class="detail-value" id="detalle-cargo"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-calendar-line"></i> Estado</div>
                                <div class="detail-value"><span id="detalle-estado" class="estado-badge"></span></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-calendar-check-line"></i> Fecha</div>
                                <div class="detail-value" id="detalle-fecha"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-time-line"></i> Hora</div>
                                <div class="detail-value" id="detalle-hora"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-map-pin-line"></i> Sede</div>
                                <div class="detail-value" id="detalle-sede"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-store-line"></i> Sucursal</div>
                                <div class="detail-value" id="detalle-sucursal"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-row">
                                <div class="detail-label"><i class="ri-mail-line"></i> Cuenta</div>
                                <div class="detail-value" id="detalle-cuenta">—</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('backend/assets/libs/fullcalendar/index.global.min.js') }}"></script>
    <script>
        let calendarCronograma;
        let ofertaSeleccionadaId = null;

        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar-cronograma');
            calendarCronograma = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día'
                },
                editable: false,
                droppable: false,
                events: [],
                eventDidMount: function(info) {
                    info.el.style.cursor = 'pointer';
                    
                    // Si hay una oferta seleccionada, usar color del módulo; si no, usar color de la oferta
                    let colorFondo;
                    let textColor = '#ffffff';
                    
                    if (ofertaSeleccionadaId) {
                        // Usar color del módulo
                        colorFondo = info.event.extendedProps.modulo_color || '#cccccc';
                    } else {
                        // Usar color de la oferta académica
                        colorFondo = info.event.extendedProps.color_oferta || '#cccccc';
                    }
                    
                    // Determinar color de texto según luminosidad
                    const isLight = isColorLight(colorFondo);
                    textColor = isLight ? '#212529' : '#ffffff';
                    
                    info.el.style.backgroundColor = colorFondo;
                    info.el.style.borderColor = colorFondo;
                    
                    const titleEl = info.el.querySelector('.fc-event-title') || info.el.querySelector('span') || info.el;
                    if (titleEl) titleEl.style.color = textColor;
                },
                eventClick: function(info) {
                    const props = info.event.extendedProps;
                    document.getElementById('detalle-modulo').textContent = props.modulo_nombre || '—';
                    document.getElementById('detalle-oferta').textContent = props.oferta_nombre || '—';
                    document.getElementById('detalle-docente').textContent = props.docente || 'Sin docente';
                    document.getElementById('detalle-responsable').textContent = props.responsable || 'Sin responsable';
                    document.getElementById('detalle-cargo').textContent = props.cargo || '—';
                    document.getElementById('detalle-sede').textContent = props.sede || '—';
                    document.getElementById('detalle-sucursal').textContent = props.sucursal || '—';
                    document.getElementById('detalle-cuenta').textContent = props.cuenta_notificacion || '—';
                    
                    const startStr = props.start || '';
                    const endStr = props.end || '';
                    
                    let fechaMostrar = '—';
                    let horaMostrar = '—';
                    
                    if (startStr) {
                        const startParts = startStr.split('T');
                        if (startParts.length >= 1) {
                            const fechaRaw = startParts[0];
                            const fechaObj = new Date(fechaRaw + 'T00:00:00');
                            fechaMostrar = fechaObj.toLocaleDateString('es-ES', { 
                                weekday: 'long', 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric' 
                            });
                            
                            if (startParts[1]) {
                                const horaInicio = startParts[1].substring(0, 5);
                                if (endStr) {
                                    const endParts = endStr.split('T');
                                    const horaFin = endParts[1] ? endParts[1].substring(0, 5) : horaInicio;
                                    horaMostrar = `${horaInicio} - ${horaFin}`;
                                } else {
                                    horaMostrar = horaInicio;
                                }
                            }
                        }
                    }
                    
                    document.getElementById('detalle-fecha').textContent = fechaMostrar;
                    document.getElementById('detalle-hora').textContent = horaMostrar;
                    
                    const estadoBadge = document.getElementById('detalle-estado');
                    const estado = props.estado || 'Confirmado';
                    const colores = {
                        'Confirmado': { bg: '#d1fae5', color: '#065f46', label: '✅ Confirmado' },
                        'Desarrollado': { bg: '#e0e7ff', color: '#3730a3', label: '✔️ Desarrollado' },
                        'Postergado': { bg: '#fee2e2', color: '#7f1d1d', label: '⏸️ Postergado' }
                    };
                    const c = colores[estado] || colores['Confirmado'];
                    estadoBadge.style.backgroundColor = c.bg;
                    estadoBadge.style.color = c.color;
                    estadoBadge.textContent = c.label;
                    
                    const modal = new bootstrap.Modal(document.getElementById('modalDetalleHorario'));
                    modal.show();
                }
            });
            calendarCronograma.render();

            function isColorLight(hexColor) {
                const hex = hexColor.replace('#', '');
                const r = parseInt(hex.substr(0, 2), 16);
                const g = parseInt(hex.substr(2, 2), 16);
                const b = parseInt(hex.substr(4, 2), 16);
                const brightness = (r * 299 + g * 587 + b * 114) / 1000;
                return brightness > 155;
            }

            // Función para cargar eventos del calendario
            function cargarEventos() {
                const sedeId = document.getElementById('filtro-sede').value;
                const sucursalId = document.getElementById('filtro-sucursal').value;
                const ofertaId = ofertaSeleccionadaId;

                let url = '{{ route('admin.ofertas.cronograma.eventos') }}?';
                if (ofertaId) {
                    url += `oferta_id=${ofertaId}`;
                } else if (sucursalId) {
                    url += `sucursale_id=${sucursalId}`;
                } else if (sedeId) {
                    url += `sede_id=${sedeId}`;
                }

                fetch(url)
                    .then(res => res.json())
                    .then(eventos => {
                        calendarCronograma.getEvents().forEach(e => e.remove());
                        
                        const sedeId = document.getElementById('filtro-sede').value;
                        const sucursalId = document.getElementById('filtro-sucursal').value;
                        const ofertaId = ofertaSeleccionadaId;
                        
                        eventos.forEach(e => {
                            // Modificar título según el nivel de filtro
                            if (ofertaId) {
                                // Ya tiene título con módulo y responsable
                                calendarCronograma.addEvent(e);
                            } else if (sucursalId) {
                                // Mostrar nombre de la oferta
                                e.title = e.extendedProps.oferta_nombre || e.title;
                                calendarCronograma.addEvent(e);
                            } else if (sedeId) {
                                // Mostrar nombre de la sucursal
                                e.title = e.extendedProps.sucursal || e.title;
                                calendarCronograma.addEvent(e);
                            } else {
                                // Mostrar nombre de la sede
                                e.title = e.extendedProps.sede || e.title;
                                calendarCronograma.addEvent(e);
                            }
                        });
                    });
            }

            // Función para cargar el listado de ofertas académicas
            function cargarListadoOfertas() {
                const sedeId = document.getElementById('filtro-sede').value;
                const sucursalId = document.getElementById('filtro-sucursal').value;

                let url = '{{ route('admin.ofertas.filtradas') }}?';
                if (sucursalId) {
                    url += `sucursale_id=${sucursalId}`;
                } else if (sedeId) {
                    url += `sede_id=${sedeId}`;
                } else {
                    document.getElementById('lista-ofertas').innerHTML =
                        '<div class="col-12"><em>Selecciona una sede o sucursal.</em></div>';
                    return;
                }

                fetch(url)
                    .then(res => res.json())
                    .then(ofertas => {
                        const container = document.getElementById('lista-ofertas');
                        const contador = document.getElementById('contador-ofertas');
                        
                        if (ofertas.length === 0) {
                            container.innerHTML = `
                                <div class="col-12">
                                    <div class="empty-state">
                                        <i class="ri-inbox-line"></i>
                                        <p>No hay ofertas académicas disponibles.</p>
                                    </div>
                                </div>
                            `;
                            contador.textContent = '';
                        } else {
                            contador.textContent = `${ofertas.length} oferta${ofertas.length !== 1 ? 's' : ''}`;
                            container.innerHTML = ofertas.map(oferta => {
                                const color = oferta.color || '#cccccc';
                                const isLight = isColorLight(color);
                                const textColor = isLight ? '#212529' : '#ffffff';
                                return `
            <div class="col-md-6 col-lg-4">
                <div class="oferta-card" data-oferta-id="${oferta.id}">
                    <div class="oferta-header">
                        <span class="oferta-badge" style="background-color: ${color};"></span>
                        <span class="badge bg-light text-dark">${oferta.codigo || ''}</span>
                    </div>
                    <div class="oferta-body">
                        <div class="oferta-title">${oferta.programa?.nombre || 'Sin programa'}</div>
                        <div class="oferta-meta">
                            <i class="ri-store-line me-1"></i>${oferta.sucursal?.nombre || 'Sin sucursal'}<br>
                            <i class="ri-map-pin-line me-1"></i>${oferta.sucursal?.sede?.nombre || '—'}
                        </div>
                    </div>
                    <div class="oferta-footer">
                        <button class="btn btn-sm ver-oferta-btn" data-oferta-id="${oferta.id}">
                            <i class="ri-calendar-line me-1"></i>Ver cronograma
                        </button>
                    </div>
                </div>
            </div>
        `;
                            }).join('');
                        }
                    });
            }

            // Listeners
            document.getElementById('filtro-sede').addEventListener('change', function() {
                ofertaSeleccionadaId = null;
                const sedeId = this.value;
                const sucursalSelect = document.getElementById('filtro-sucursal');
                sucursalSelect.innerHTML = '<option value="">Todas las sucursales</option>';
                sucursalSelect.disabled = !sedeId;

                if (sedeId) {
                    fetch(`{{ route('admin.sucursales.por-sede') }}?sede_id=${sedeId}`)
                        .then(res => res.json())
                        .then(data => {
                            data.forEach(s => {
                                const opt = document.createElement('option');
                                opt.value = s.id;
                                opt.textContent = s.nombre;
                                sucursalSelect.appendChild(opt);
                            });
                        });
                }
                cargarEventos();
                cargarListadoOfertas();
            });

            document.getElementById('filtro-sucursal').addEventListener('change', function() {
                ofertaSeleccionadaId = null;
                cargarEventos();
                cargarListadoOfertas();
            });

            // Listener único para las ofertas
            document.getElementById('lista-ofertas').addEventListener('click', function(e) {
                const btn = e.target.closest('.ver-oferta-btn');
                if (btn) {
                    const ofertaId = btn.dataset.ofertaId;
                    const card = btn.closest('.oferta-card');

                    if (ofertaSeleccionadaId === ofertaId) {
                        ofertaSeleccionadaId = null;
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-outline-primary');
                        btn.innerHTML = '<i class="ri-calendar-line me-1"></i>Ver cronograma';
                        card.classList.remove('seleccionada');
                    } else {
                        document.querySelectorAll('.oferta-card.seleccionada').forEach(c => {
                            c.classList.remove('seleccionada');
                            const b = c.querySelector('.ver-oferta-btn');
                            b.classList.remove('btn-primary');
                            b.classList.add('btn-outline-primary');
                            b.innerHTML = '<i class="ri-calendar-line me-1"></i>Ver cronograma';
                        });
                        ofertaSeleccionadaId = ofertaId;
                        btn.classList.remove('btn-outline-primary');
                        btn.classList.add('btn-primary');
                        btn.innerHTML = '<i class="ri-check-line me-1"></i>Mostrando';
                        card.classList.add('seleccionada');
                    }

                    cargarEventos();
                }
            });

            // Carga inicial (sin filtro)
            cargarEventos();
        });
    </script>
@endpush
