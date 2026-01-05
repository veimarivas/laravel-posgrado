@extends('admin.dashboard')

@section('admin')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Cronograma General de Ofertas Académicas</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Ofertas</a></li>
                    <li class="breadcrumb-item active">Cronograma</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <label class="form-label">Sede</label>
            <select id="filtro-sede" class="form-select">
                <option value="">Todas las sedes</option>
                @foreach ($sedes as $sede)
                    <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Sucursal</label>
            <select id="filtro-sucursal" class="form-select" disabled>
                <option value="">Todas las sucursales</option>
            </select>
        </div>
    </div>

    {{-- ✅ AQUÍ VA EL LISTADO DE OFERTAS --}}
    <div class="row mt-4">
        <div class="col-12">
            <h5>Ofertas académicas filtradas:</h5>
            <div id="lista-ofertas" class="row g-3">
                <div class="col-12"><em>Selecciona una sede o sucursal para ver ofertas.</em></div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id="calendar-cronograma"></div>
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
                    // 👇 Usa el color de la oferta académica
                    const colorFondo = info.event.extendedProps.color_oferta || '#cccccc';
                    info.el.style.backgroundColor = colorFondo;
                    info.el.style.borderColor = colorFondo;

                    const estado = info.event.extendedProps.estado;
                    let colorTexto = '#000000';
                    if (estado === 'Confirmado') colorTexto = '#16A3DB';
                    else if (estado === 'Desarrollado') colorTexto = '#3804D9';
                    else if (estado === 'Postergado') colorTexto = '#D91404';

                    const titleEl = info.el.querySelector('.fc-event-title') || info.el.querySelector(
                        'span') || info.el;
                    if (titleEl) titleEl.style.color = colorTexto;
                },
                eventClick: function(info) {
                    alert(
                        `Oferta: ${info.event.extendedProps.oferta_nombre}\nMódulo: ${info.event.extendedProps.modulo_nombre}`
                    );
                }
            });
            calendarCronograma.render();

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
                        eventos.forEach(e => calendarCronograma.addEvent(e));
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
                        if (ofertas.length === 0) {
                            container.innerHTML =
                                '<div class="col-12"><em>No hay ofertas académicas.</em></div>';
                        } else {
                            container.innerHTML = ofertas.map(oferta => {
                                const color = oferta.color || '#cccccc';
                                const textColor = (color === '#ffffff' || color === '#FFFFFF') ?
                                    '#000000' : '#ffffff';
                                return `
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 border rounded shadow-sm"
                style="border-top: 4px solid ${color};">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title fw-bold mb-0">${oferta.programa?.nombre || 'Sin programa'}</h6>
                        <span class="badge rounded-pill"
                            style="background-color: ${color}; color: ${textColor}; min-width: 24px; text-align: center;">
                            ●
                        </span>
                    </div>
                    <p class="text-muted small mb-2">
                        ${oferta.sucursal?.nombre || 'Sin sucursal'}<br>
                        <small>Sede: ${oferta.sucursal?.sede?.nombre || '—'}</small>
                    </p>
                    <button class="btn btn-sm btn-outline-primary mt-auto ver-oferta-btn"
                        data-oferta-id="${oferta.id}">
                        Ver cronograma
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
                if (e.target.classList.contains('ver-oferta-btn')) {
                    const ofertaId = e.target.dataset.ofertaId;

                    if (ofertaSeleccionadaId === ofertaId) {
                        ofertaSeleccionadaId = null;
                        e.target.classList.replace('btn-primary', 'btn-outline-primary');
                        e.target.textContent = 'Ver cronograma';
                    } else {
                        // Deseleccionar anterior
                        document.querySelectorAll('.ver-oferta-btn.btn-primary').forEach(btn => {
                            btn.classList.replace('btn-primary', 'btn-outline-primary');
                            btn.textContent = 'Ver cronograma';
                        });
                        ofertaSeleccionadaId = ofertaId;
                        e.target.classList.replace('btn-outline-primary', 'btn-primary');
                        e.target.textContent = 'Mostrando...';
                    }

                    cargarEventos(); // Filtra el calendario
                }
            });

            // Carga inicial (vacía)
            cargarEventos();
            cargarListadoOfertas();
        });
    </script>
@endpush
