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
        --cont-warning: #f59e0b;
        --cont-warning-light: #fffbeb;
        --cont-info: #0891b2;
        --cont-info-light: #ecfeff;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
    }

    .deudas-page {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--cont-text);
    }

    .deudas-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding: 20px 28px;
        background: linear-gradient(135deg, var(--cont-danger) 0%, #b91c1c 100%);
        border-radius: var(--radius-lg);
        color: white;
    }

    .deudas-header h1 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .deudas-header p {
        margin: 4px 0 0;
        opacity: 0.9;
        font-size: 0.9rem;
    }

    .nav-tabs-custom {
        border-bottom: 2px solid var(--cont-border);
        margin-bottom: 24px;
        overflow-x: auto;
        white-space: nowrap;
        display: flex;
        flex-wrap: nowrap;
        gap: 4px;
        padding-bottom: 0;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: var(--cont-text-muted);
        font-weight: 600;
        padding: 14px 24px;
        transition: all 0.2s;
        background: var(--cont-surface);
        border-radius: 12px 12px 0 0;
        margin-bottom: -2px;
        white-space: nowrap;
    }

    .nav-tabs-custom .nav-link:hover {
        color: var(--cont-primary);
        background: var(--cont-primary-light);
    }

    .nav-tabs-custom .nav-link.active {
        color: var(--cont-primary);
        border-bottom-color: var(--cont-primary);
        background: white;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
    }

    .tab-badge {
        background: var(--cont-danger);
        color: white;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        margin-left: 8px;
    }

    .tab-oferta-name {
        font-size: 0.95rem;
    }

    .nav-tabs-custom .nav-link.active .tab-badge {
        background: var(--cont-primary);
    }

    .estudiante-card {
        background: white;
        border-radius: var(--radius-md);
        margin-bottom: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        padding: 16px 20px;
        transition: all 0.2s;
    }

    .estudiante-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .estudiante-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .estudiante-nombre {
        font-weight: 600;
        font-size: 1rem;
    }

    .estudiante-celular {
        color: var(--cont-text-muted);
        font-size: 0.85rem;
    }

    .cuotas-resumen {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .cuota-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .cuota-badge.pendiente {
        background: var(--cont-warning-light);
        color: #b45309;
    }

    .cuota-badge.retrasada {
        background: var(--cont-danger-light);
        color: var(--cont-danger);
    }

    .btn-ver-cuotas {
        background: var(--cont-primary);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: var(--radius-sm);
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-ver-cuotas:hover {
        background: var(--cont-primary-dark);
    }

    .btn-whatsapp {
        padding: 8px 12px;
        font-size: 1.1rem;
    }

    .btn-whatsapp:hover {
        background: #128c7e;
    }

    .btn-comprobante {
        padding: 8px 12px;
        font-size: 0.85rem;
        color: #000;
    }

    .btn-comprobante:hover {
        background: #e0a800;
        color: #000;
    }

    .modal-header-custom {
        background: linear-gradient(135deg, var(--cont-primary) 0%, var(--cont-primary-dark) 100%);
        color: #fff;
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }

    .modal-header-custom .modal-title {
        color: #fff;
    }

    .modal-cuota-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid var(--cont-border);
    }

    .modal-cuota-item:last-child {
        border-bottom: none;
    }

    .cuota-num {
        width: 28px;
        height: 28px;
        background: var(--cont-primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
        margin-right: 10px;
    }

    .cuota-fecha-retrasada {
        color: var(--cont-danger);
        font-weight: 600;
    }

    .cuota-fecha-pendiente {
        color: var(--cont-warning);
        font-weight: 500;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--cont-text-muted);
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 16px;
        opacity: 0.3;
    }
</style>

<div class="container-fluid deudas-page">
    <div class="deudas-header">
        <div>
            <h1><i class="ri-alert-line me-2"></i>Cuotas Retrasadas</h1>
            <p>Participantes con cuotas retrasadas por oferta académica</p>
        </div>
        <div style="opacity: 0.9; font-size: 0.85rem;">
            <i class="ri-calendar-line me-1"></i> Fecha actual: {{ now()->format('d/m/Y') }}
        </div>
    </div>

    @if(empty($resultados))
        <div class="empty-state">
            <i class="ri-checkbox-circle-line"></i>
            <h3>No hay cuotas retrasadas</h3>
            <p>Todos los participantes están al día con sus pagos</p>
        </div>
    @else
        <ul class="nav nav-tabs-custom" id="ofertasTabs" role="tablist">
            @foreach($resultados as $index => $oferta)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                            id="tab-oferta-{{ $oferta['oferta_id'] }}" 
                            data-bs-toggle="tab" 
                            data-bs-target="#content-oferta-{{ $oferta['oferta_id'] }}" 
                            type="button" 
                            role="tab">
                        <span class="tab-oferta-name">{{ Str::limit($oferta['oferta_nombre'], 25) }}</span>
                        <span class="tab-badge">{{ $oferta['total_estudiantes'] }}</span>
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content" id="ofertasTabsContent">
            @foreach($resultados as $index => $oferta)
                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                     id="content-oferta-{{ $oferta['oferta_id'] }}" 
                     role="tabpanel">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <h5 style="margin: 0; color: var(--cont-text);">
                            <i class="ri-book-2-line me-2"></i>{{ $oferta['oferta_nombre'] }}
                        </h5>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge bg-warning text-dark">
                                Total: Bs {{ number_format($oferta['total_monto'], 2) }}
                            </span>
                            <button class="btn btn-sm btn-success" onclick="enviarWhatsAppOferta({{ $oferta['oferta_id'] }})" style="background: #25D366; border-color: #25D366;">
                                <i class="ri-whatsapp-line me-1"></i> Enviar a todos
                            </button>
                        </div>
                    </div>
                    
                    @foreach($oferta['estudiantes'] as $estudiante)
                        <div class="estudiante-card">
                            <div class="estudiante-info">
                                <div>
                                    <div class="estudiante-nombre">{{ $estudiante['nombre'] }}</div>
                                    @if($estudiante['celular'])
                                        <div class="estudiante-celular">
                                            <i class="ri-phone-line"></i> {{ $estudiante['celular'] }}
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex gap-2">
                                    @if($estudiante['celular'])
                                        <button class="btn btn-success btn-whatsapp" onclick="enviarWhatsApp({{ $oferta['oferta_id'] }}, {{ $estudiante['estudiante_id'] }})" title="Enviar mensaje WhatsApp">
                                            <i class="ri-whatsapp-line"></i>
                                        </button>
                                    @endif
                                    @if($estudiante['tiene_comprobantes'])
                                        <button class="btn btn-warning btn-comprobante" onclick="verComprobantes({{ $oferta['oferta_id'] }}, {{ $estudiante['estudiante_id'] }})" title="Ver comprobantes">
                                            <i class="ri-file-check-line"></i> ({{ count($estudiante['comprobantes']) }})
                                        </button>
                                    @endif
                                    <button class="btn-ver-cuotas" onclick="abrirModalCuotas({{ $oferta['oferta_id'] }}, {{ $estudiante['estudiante_id'] }})">
                                        <i class="ri-eye-line me-1"></i> Ver cuotas ({{ $estudiante['retrasadas'] }})
                                    </button>
                                </div>
                            </div>
                            <div class="cuotas-resumen">
                                <span class="cuota-badge retrasada">
                                    <i class="ri-alert-line"></i> {{ $estudiante['retrasadas'] }} retrasada(s)
                                </span>
                                <span class="cuota-badge" style="background: var(--cont-surface);">
                                    Total: Bs {{ number_format($estudiante['monto_total'], 2) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="modal fade" id="modalCuotas" tabindex="-1" aria-labelledby="modalCuotasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="modalCuotasLabel">
                    <i class="ri-calendar-line me-2"></i>Cuotas del Participante
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalCuotasBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-comp" id="modalVerificar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--cont-primary) 0%, var(--cont-primary-dark) 100%); color: white;">
                <h5 class="modal-title" style="color: white;">
                    <i class="ri-shield-check-line"></i>
                    Verificar Comprobante y Registrar Pago
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-comp modal-reject" id="modalRechazar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--cont-danger); color: white;">
                <h5 class="modal-title" style="color: white;">
                    <i class="ri-close-circle-line me-2"></i>Rechazar Comprobante
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formRechazar">
                @csrf
                <input type="hidden" id="rechazar_comprobante_id" name="comprobante_id">
                <div class="modal-body text-center">
                    <div class="reject-icon-circle">
                        <i class="ri-alert-line"></i>
                    </div>
                    <h5 style="font-family:'Outfit',sans-serif;font-weight:600;margin-bottom:4px;">¿Estás seguro de rechazar este comprobante?</h5>
                    <p class="text-muted mb-3" style="font-size:.88rem;">Esta acción notificará al estudiante sobre el rechazo.</p>
                    <div class="text-start">
                        <label for="motivo_rechazo" class="form-label">Motivo del rechazo *</label>
                        <textarea class="form-control" id="motivo_rechazo" name="motivo_rechazo" rows="3" required
                                  placeholder="Indica el motivo del rechazo..."></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-sm" style="background:var(--cont-danger);color:white;">
                        <i class="ri-close-circle-line me-1"></i>Rechazar Comprobante
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
    const deudasData = @json($resultados);
    let modalCuotas = null;
    let modalVerificar = null;
    let modalRechazar = null;

    document.addEventListener('DOMContentLoaded', function() {
        modalCuotas = new bootstrap.Modal(document.getElementById('modalCuotas'));
        modalVerificar = new bootstrap.Modal(document.getElementById('modalVerificar'));
        modalRechazar = new bootstrap.Modal(document.getElementById('modalRechazar'));
    });

    function verComprobantes(ofertaId, estudianteId) {
        let oferta = null;
        let estudiante = null;

        for (let i = 0; i < deudasData.length; i++) {
            if (deudasData[i].oferta_id === ofertaId) {
                oferta = deudasData[i];
                for (let j = 0; j < oferta.estudiantes.length; j++) {
                    if (oferta.estudiantes[j].estudiante_id === estudianteId) {
                        estudiante = oferta.estudiantes[j];
                        break;
                    }
                }
                break;
            }
        }

        if (!oferta || !estudiante || !estudiante.comprobantes || estudiante.comprobantes.length === 0) return;

        document.getElementById('modalVerificar').querySelector('.modal-body').innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary"></div><p class="mt-2">Cargando comprobante...</p></div>';
        document.getElementById('modalVerificar').querySelector('.modal-footer').innerHTML = '';
        
        const comprobanteId = estudiante.comprobantes[0].id;
        
        fetch('/admin/comprobante/' + comprobanteId + '/detalle')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarFormularioVerificacion(data);
            } else {
                document.getElementById('modalVerificar').querySelector('.modal-body').innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modalVerificar').querySelector('.modal-body').innerHTML = '<div class="alert alert-danger">Error al cargar los datos.</div>';
        });

        modalVerificar.show();
    }

    function mostrarFormularioVerificacion(data) {
        var comp = data.comprobante;
        var archivoUrl = data.archivo_url;

        var ext = archivoUrl.split('?')[0].split('.').pop().toLowerCase();
        var previewHtml;
        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
            previewHtml = `<a href="${archivoUrl}" target="_blank">
                <img src="${archivoUrl}" class="img-fluid rounded border" style="max-height:260px;width:100%;object-fit:contain;cursor:pointer;" title="Clic para ampliar">
            </a>`;
        } else if (ext === 'pdf') {
            previewHtml = `<embed src="${archivoUrl}" type="application/pdf" width="100%" height="260px" class="rounded border">
                <div class="mt-1 text-end">
                    <a href="${archivoUrl}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="ri-external-link-line me-1"></i>Abrir PDF
                    </a>
                </div>`;
        } else {
            previewHtml = `<div class="d-flex align-items-center justify-content-center border rounded bg-light" style="height:80px;">
                <a href="${archivoUrl}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="ri-download-line me-1"></i>Descargar archivo
                </a>
            </div>`;
        }

        var iconos = {
            'Matrícula':    'ri-graduation-cap-line',
            'Colegiatura':  'ri-book-open-line',
            'Certificación':'ri-award-line',
            'Otros':        'ri-file-list-line',
        };
        var orden = ['Matrícula', 'Colegiatura', 'Certificación', 'Otros'];

        function buildGrupos(cuotas) {
            var g = {};
            cuotas.forEach(function(c) {
                var t = c.tipo || 'Otros';
                if (!g[t]) g[t] = [];
                g[t].push(c);
            });
            return g;
        }

        function renderGrupo(grupos, checked) {
            var html = '';
            orden.forEach(function(tipo) {
                if (!grupos[tipo] || grupos[tipo].length === 0) return;
                var icono = iconos[tipo] || 'ri-file-list-line';
                var total = grupos[tipo].reduce(function(s, c) { return s + c.pendiente_bs; }, 0);
                html += `<div class="mb-2">
                    <div class="cuota-group-header">
                        <div class="group-title">
                            <i class="${icono}" style="color:var(--comp-accent);"></i>
                            <span>${tipo}</span>
                        </div>
                        <div class="group-total">Total: Bs ${total.toFixed(2)}</div>
                    </div>`;
                grupos[tipo].forEach(function(cuota) {
                    html += `
                    <div class="form-check cuota-check-item">
                        <input class="form-check-input" type="checkbox" name="cuotas[]" 
                            value="${cuota.id}" id="cuota_${cuota.id}" 
                            ${checked ? 'checked' : ''}>
                        <label class="form-check-label" for="cuota_${cuota.id}">
                            <span class="cuota-num">${cuota.n_cuota}</span>
                            <span class="cuota-info">
                                <strong>${cuota.nombre}</strong>
                                <span class="text-danger">Bs ${cuota.pendiente_bs.toFixed(2)}</span>
                                <small class="text-muted">Vence: ${cuota.fecha_pago}</small>
                            </span>
                        </label>
                    </div>`;
                });
                html += `</div>`;
            });
            return html;
        }

        var cuotasAsociadas = data.cuotas;
        var cuotasPendientes = data.cuotas_pendientes;
        
        var gruposAsociados = buildGrupos(cuotasAsociadas);
        var gruposPendientes = buildGrupos(cuotasPendientes);

        document.getElementById('modalVerificar').querySelector('.modal-body').innerHTML = `
            <div class="row">
                <div class="col-md-5">
                    <div class="comp-preview-section">
                        <h6><i class="ri-file-image-line me-2"></i>Comprobante</h6>
                        ${previewHtml}
                        <div class="comp-details mt-2">
                            <p><strong>Monto:</strong> Bs ${parseFloat(comp.monto).toFixed(2)}</p>
                            <p><strong>Fecha de Pago:</strong> ${comp.fecha_pago}</p>
                            <p><strong>Estudiante:</strong> ${data.estudiante.nombre}</p>
                            <p><strong>Programa:</strong> ${data.programa}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <h6><i class="ri-file-list-line me-2"></i>Seleccionar Cuotas a Pagar</h6>
                    <div class="alert alert-info">
                        <i class="ri-information-line me-1"></i> 
                        Total pendiente: <strong>Bs ${data.total_pendiente.toFixed(2)}</strong>
                    </div>
                    <div class="cuotas-scroll">
                        <div class="mb-3">
                            <div class="cuota-group-header">
                                <div class="group-title">
                                    <input type="checkbox" id="selectAllPendientes" checked>
                                    <span class="ms-1 fw-bold">Cuotas Pendientes</span>
                                </div>
                            </div>
                            ${renderGrupo(gruposPendientes, true)}
                        </div>
                        ${Object.keys(gruposAsociados).length > 0 ? `
                        <div class="mb-3">
                            <div class="cuota-group-header">
                                <div class="group-title">
                                    <input type="checkbox" id="selectAllAsociadas">
                                    <span class="ms-1 fw-bold">Ya Asociadas</span>
                                </div>
                            </div>
                            ${renderGrupo(gruposAsociados, false)}
                        </div>` : ''}
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Monto a Registrar</label>
                        <input type="number" class="form-control" id="monto_registrar" 
                            value="${Math.min(comp.monto, data.total_pendiente)}" 
                            step="0.01" min="0" max="${comp.monto}">
                        <small class="text-muted">Monto del comprobante: Bs ${parseFloat(comp.monto).toFixed(2)}</small>
                    </div>
                </div>
            </div>
        `);

        var footerHtml = `
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-success" onclick="procesarVerificacion(${comp.id})">
                <i class="ri-check-line me-1"></i> Verificar y Registrar Pago
            </button>
        `;
        $('#modalVerificar .modal-footer').html(footerHtml);

        $('#selectAllPendientes').change(function() {
            $('input[name="cuotas[]"]').prop('checked', $(this).prop('checked'));
        });
        $('#selectAllAsociadas').change(function() {
            $('input[name="cuotas[]"]').prop('checked', $(this).prop('checked'));
        });
    }

    function procesarVerificacion(comprobanteId) {
        var cuotasSeleccionadas = [];
        $('input[name="cuotas[]"]:checked').each(function() {
            cuotasSeleccionadas.push($(this).val());
        });

        if (cuotasSeleccionadas.length === 0) {
            alert('Seleccione al menos una cuota');
            return;
        }

        var monto = $('#monto_registrar').val();
        
        var formData = new FormData();
        formData.append('cuotas', JSON.stringify(cuotasSeleccionadas));
        formData.append('monto', monto);

        fetch('/admin/comprobante/' + comprobanteId + '/verificar', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pago registrado correctamente');
                location.reload();
            } else {
                alert(data.message || 'Error al registrar');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar');
        });
    }

    function rechazarComprobante(comprobanteId) {
        $('#rechazar_comprobante_id').val(comprobanteId);
        $('#motivo_rechazo').val('');
        modalRechazar.show();
    }

    $('#formRechazar').on('submit', function(e) {
        e.preventDefault();
        
        var comprobanteId = $('#rechazar_comprobante_id').val();
        var motivo = $('#motivo_rechazo').val();
        
        fetch('/admin/comprobante/' + comprobanteId + '/rechazar', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ motivo_rechazo: motivo })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Comprobante rechazado');
                modalRechazar.hide();
                location.reload();
            } else {
                alert(data.message || 'Error al rechazar');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al rechazar');
        });
    });

    function formatFechaLarga(fechaStr) {
        const fechaDate = new Date(fechaStr + 'T00:00:00');
        const dia = fechaDate.getDate();
        const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        const mes = meses[fechaDate.getMonth()];
        const anio = fechaDate.getFullYear();
        return dia + ' de ' + mes + ' del ' + anio;
    }

    function enviarWhatsApp(ofertaId, estudianteId) {
        let oferta = null;
        let estudiante = null;

        for (let i = 0; i < deudasData.length; i++) {
            if (deudasData[i].oferta_id === ofertaId) {
                oferta = deudasData[i];
                for (let j = 0; j < oferta.estudiantes.length; j++) {
                    if (oferta.estudiantes[j].estudiante_id === estudianteId) {
                        estudiante = oferta.estudiantes[j];
                        break;
                    }
                }
                break;
            }
        }

        if (!oferta || !estudiante || !estudiante.celular) return;

        const conceptosOrden = ['Matricula', 'Colegiatura', 'Certificacion', 'Certificación'];
        const grupos = {};
        
        estudiante.cuotas.forEach(function(cuota) {
            if (cuota.estado !== 'retrasada') return;
            
            let concepto = cuota.nombre || 'Otro';
            conceptosOrden.forEach(function(c) {
                if (concepto.toLowerCase().includes(c.toLowerCase())) {
                    concepto = c;
                    return;
                }
            });
            if (!grupos[concepto]) {
                grupos[concepto] = [];
            }
            grupos[concepto].push(cuota);
        });

        const tieneRetrasadas = Object.keys(grupos).some(key => grupos[key].length > 0);
        
        if (!tieneRetrasadas) {
            alert('No tiene cuotas retrasadas para enviar');
            return;
        }

        let mensaje = `Hola *${estudiante.nombre}*, le informamos que tiene las siguientes cuotas *RETRASADAS* en *${oferta.oferta_nombre}*:\n\n`;

        for (const [concepto, cuotas] of Object.entries(grupos)) {
            if (cuotas.length === 0) continue;
            mensaje += `*${concepto}:*\n`;
            cuotas.forEach(function(cuota) {
                mensaje += `• Cuota ${cuota.n_cuota}: Bs ${cuota.monto_bs.toFixed(2)} - ${formatFechaLarga(cuota.fecha_pago)}\n`;
            });
            const subtotal = cuotas.reduce((sum, c) => sum + c.monto_bs, 0);
            mensaje += `  Subtotal: Bs ${subtotal.toFixed(2)}\n\n`;
        }

        const totalRetrasadas = estudiante.cuotas
            .filter(c => c.estado === 'retrasada')
            .reduce((sum, c) => sum + c.monto_bs, 0);

        mensaje += `*TOTAL RETRASADO: Bs ${totalRetrasadas.toFixed(2)}*\n\n`;
        mensaje += `Favor realizar el pago lo antes posible para evitar complicaciones.\n\n`;
        mensaje += `Saludos cordiales\n*UNIP - Área Contable*`;

        const celularLimpio = estudiante.celular.replace(/\D/g, '');
        const urlWhatsApp = `https://wa.me/591${celularLimpio}?text=${encodeURIComponent(mensaje)}`;
        window.open(urlWhatsApp, '_blank');
    }

    function enviarWhatsAppOferta(ofertaId) {
        let oferta = null;
        for (let i = 0; i < deudasData.length; i++) {
            if (deudasData[i].oferta_id === ofertaId) {
                oferta = deudasData[i];
                break;
            }
        }
        
        if (!oferta) return;

        let enviados = 0;
        let sinCelular = 0;

        oferta.estudiantes.forEach(function(estudiante) {
            if (!estudiante.celular || estudiante.celular.trim() === '') {
                sinCelular++;
                return;
            }

            if (estudiante.retrasadas === 0) return;

            const conceptosOrden = ['Matricula', 'Colegiatura', 'Certificacion', 'Certificación'];
            const grupos = {};
            
            estudiante.cuotas.forEach(function(cuota) {
                if (cuota.estado !== 'retrasada') return;
                
                let concepto = cuota.nombre || 'Otro';
                conceptosOrden.forEach(function(c) {
                    if (concepto.toLowerCase().includes(c.toLowerCase())) {
                        concepto = c;
                        return;
                    }
                });
                if (!grupos[concepto]) {
                    grupos[concepto] = [];
                }
                grupos[concepto].push(cuota);
            });

            const tieneRetrasadas = Object.keys(grupos).some(key => grupos[key].length > 0);
            if (!tieneRetrasadas) return;

            let mensaje = `Hola *${estudiante.nombre}*, le informamos que tiene las siguientes cuotas *RETRASADAS* en *${oferta.oferta_nombre}*:\n\n`;

            for (const [concepto, cuotas] of Object.entries(grupos)) {
                if (cuotas.length === 0) continue;
                mensaje += `*${concepto}:*\n`;
                cuotas.forEach(function(cuota) {
                    mensaje += `• Cuota ${cuota.n_cuota}: Bs ${cuota.monto_bs.toFixed(2)} - ${formatFechaLarga(cuota.fecha_pago)}\n`;
                });
                const subtotal = cuotas.reduce((sum, c) => sum + c.monto_bs, 0);
                mensaje += `  Subtotal: Bs ${subtotal.toFixed(2)}\n\n`;
            }

            const totalRetrasadas = estudiante.cuotas
                .filter(c => c.estado === 'retrasada')
                .reduce((sum, c) => sum + c.monto_bs, 0);

            mensaje += `*TOTAL RETRASADO: Bs ${totalRetrasadas.toFixed(2)}*\n\n`;
            mensaje += `Favor realizar el pago lo antes posible para evitar complicaciones.\n\n`;
            mensaje += `Saludos cordiales\n*UNIP - Área Contable*`;

            const celularLimpio = estudiante.celular.replace(/\D/g, '');
            const urlWhatsApp = `https://wa.me/591${celularLimpio}?text=${encodeURIComponent(mensaje)}`;
            window.open(urlWhatsApp, '_blank');
            enviados++;
        });

        if (enviados === 0 && sinCelular > 0) {
            alert('No se encontraron participantes con número de celular en esta oferta');
        } else if (enviados > 0) {
            alert(`Se abrió ${enviados} mensaje(s) de WhatsApp para ${oferta.oferta_nombre}${sinCelular > 0 ? ', ' + sinCelular + ' sin celular' : ''}`);
        } else {
            alert('No hay participantes con cuotas retrasadas en esta oferta');
        }
    }

    function enviarTodosWhatsApp() {
        let enviados = 0;
        let sinCelular = 0;
        
        deudasData.forEach(function(oferta) {
            oferta.estudiantes.forEach(function(estudiante) {
                if (!estudiante.celular || estudiante.celular.trim() === '') {
                    sinCelular++;
                    return;
                }

                if (estudiante.retrasadas === 0) return;

                const conceptosOrden = ['Matricula', 'Colegiatura', 'Certificacion', 'Certificación'];
                const grupos = {};
                
                estudiante.cuotas.forEach(function(cuota) {
                    if (cuota.estado !== 'retrasada') return;
                    
                    let concepto = cuota.nombre || 'Otro';
                    conceptosOrden.forEach(function(c) {
                        if (concepto.toLowerCase().includes(c.toLowerCase())) {
                            concepto = c;
                            return;
                        }
                    });
                    if (!grupos[concepto]) {
                        grupos[concepto] = [];
                    }
                    grupos[concepto].push(cuota);
                });

                const tieneRetrasadas = Object.keys(grupos).some(key => grupos[key].length > 0);
                if (!tieneRetrasadas) return;

                let mensaje = `Hola *${estudiante.nombre}*, le informamos que tiene las siguientes cuotas *RETRASADAS* en *${oferta.oferta_nombre}*:\n\n`;

                for (const [concepto, cuotas] of Object.entries(grupos)) {
                    if (cuotas.length === 0) continue;
                    mensaje += `*${concepto}:*\n`;
                    cuotas.forEach(function(cuota) {
                        mensaje += `• Cuota ${cuota.n_cuota}: Bs ${cuota.monto_bs.toFixed(2)} - ${formatFechaLarga(cuota.fecha_pago)}\n`;
                    });
                    const subtotal = cuotas.reduce((sum, c) => sum + c.monto_bs, 0);
                    mensaje += `  Subtotal: Bs ${subtotal.toFixed(2)}\n\n`;
                }

                const totalRetrasadas = estudiante.cuotas
                    .filter(c => c.estado === 'retrasada')
                    .reduce((sum, c) => sum + c.monto_bs, 0);

                mensaje += `*TOTAL RETRASADO: Bs ${totalRetrasadas.toFixed(2)}*\n\n`;
                mensaje += `Favor realizar el pago lo antes posible para evitar complicaciones.\n\n`;
                mensaje += `Saludos cordiales\n*UNIP - Área Contable*`;

                const celularLimpio = estudiante.celular.replace(/\D/g, '');
                const urlWhatsApp = `https://wa.me/591${celularLimpio}?text=${encodeURIComponent(mensaje)}`;
                window.open(urlWhatsApp, '_blank');
                enviados++;
            });
        });

        if (enviados === 0 && sinCelular > 0) {
            alert('No se encontraron participantes con número de celular');
        } else if (enviados > 0) {
            alert(`Se abrió ${enviados} mensaje(s) de WhatsApp${sinCelular > 0 ? ', ' + sinCelular + ' sin celular' : ''}`);
        }
    }
</script>
@endpush

@endsection
