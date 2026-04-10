<!-- Pestaña 2: Participantes - Diseño Premium -->
<div class="tab-pane fade" id="tab-participantes" role="tabpanel">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header border-0 bg-transparent py-3 px-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="card-title mb-0 fw-semibold">
                            <i class="ri-user-search-line align-middle me-2" style="color: var(--dash-primary);"></i>
                            Detalle de Participantes Inscritos
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge fs-12" style="background: var(--dash-primary-light); color: var(--dash-primary);">
                                {{ count($detalleParticipantes) }} {{ count($detalleParticipantes) == 1 ? 'participante' : 'participantes' }}
                            </span>
                            <a href="{{ route('admin.ofertas.exportar-detalle-participantes', $oferta->id) }}"
                                class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" style="border-radius: 8px;">
                                <i class="ri-download-line"></i>
                                <span class="d-none d-sm-inline">Exportar Excel</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if (count($detalleParticipantes) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 0.8rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-2 py-2 fw-semibold text-center">#</th>
                                        <th class="px-2 py-2 fw-semibold">Carnet</th>
                                        <th class="px-2 py-2 fw-semibold">Apellidos</th>
                                        <th class="px-2 py-2 fw-semibold">Nombres</th>
                                        <th class="px-2 py-2 fw-semibold">Correo</th>
                                        <th class="px-2 py-2 fw-semibold">Celular</th>
                                        <th class="px-2 py-2 fw-semibold">Ubicación</th>
                                        <th class="px-2 py-2 fw-semibold">Profesión</th>
                                        <th class="px-2 py-2 fw-semibold text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detalleParticipantes as $index => $participante)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="px-2 py-2">
                                                <span class="badge" style="background: #e0f2fe; color: #0284c7;">
                                                    {{ $participante['carnet'] }}
                                                </span>
                                            </td>
                                            <td class="px-2 py-2">
                                                {{ $participante['apellido_paterno'] }} {{ $participante['apellido_materno'] }}
                                            </td>
                                            <td class="px-2 py-2">
                                                <strong>{{ $participante['nombres'] }}</strong>
                                            </td>
                                            <td class="px-2 py-2">
                                                @if ($participante['correo'] && $participante['correo'] !== 'Sin correo')
                                                    <a href="mailto:{{ $participante['correo'] }}" class="text-decoration-none text-primary">
                                                        <i class="ri-mail-line me-1"></i>{{ Str::limit($participante['correo'], 20) }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-2 py-2">
                                                @if ($participante['celular'] && $participante['celular'] !== 'Sin celular')
                                                    <a href="tel:{{ $participante['celular'] }}" class="text-decoration-none text-success">
                                                        <i class="ri-phone-line me-1"></i>{{ $participante['celular'] }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-2 py-2">
                                                <span class="badge fs-10" style="background: #f0fdf4; color: #16a34a;">
                                                    {{ $participante['departamento'] }}
                                                </span>
                                                <span class="text-muted fs-9 d-block">{{ $participante['ciudad'] }}</span>
                                            </td>
                                            <td class="px-2 py-2">
                                                <span>{{ $participante['profesion'] }}</span>
                                                <div class="text-muted fs-9">{{ $participante['grado_academico'] }}</div>
                                            </td>
                                            <td class="px-2 py-2 text-center">
                                                <a href="{{ route('admin.estudiantes.detalle', $participante['estudiante_id']) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Ver detalle" style="border-radius: 6px;">
                                                    <i class="ri-eye-line fs-11"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="ri-user-line text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                            </div>
                            <h5 class="text-muted mb-1">No hay participantes inscritos</h5>
                            <p class="text-muted fs-12">Los estudiantes inscritos aparecerán aquí</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fs-9 { font-size: 0.65rem; }
    .table th {
        font-size: 0.7rem !important;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        background: #f8fafc !important;
    }
    .table td {
        vertical-align: middle;
        padding: 0.5rem !important;
    }
    .table tbody tr:hover {
        background-color: #f8fafc;
    }
    .btn-outline-primary {
        color: var(--dash-primary);
        border-color: var(--dash-primary);
    }
    .btn-outline-primary:hover {
        background: var(--dash-primary);
        color: white;
    }
</style>