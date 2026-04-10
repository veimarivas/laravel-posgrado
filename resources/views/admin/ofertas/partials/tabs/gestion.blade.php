<!-- Pestaña 6: Gestión - Diseño Premium -->
<div class="tab-pane fade" id="tab-gestion" role="tabpanel">
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-header border-0 bg-transparent py-3 px-3">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h5 class="card-title mb-1 fw-semibold">
                        <i class="ri-settings-3-line align-middle me-2" style="color: var(--dash-primary);"></i>
                        Gestión de Inscripciones
                    </h5>
                    <p class="text-muted mb-0 fs-12">Solo administradores pueden eliminar o transferir inscripciones</p>
                </div>
                <span class="badge fs-11" style="background: #fef3c7; color: #92400e;">
                    <i class="ri-alert-line me-1"></i> Administrador
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tablaGestion" style="font-size: 0.8rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="px-2 py-2 fw-semibold text-center" width="3%">#</th>
                            <th class="px-2 py-2 fw-semibold">Estudiante</th>
                            <th class="px-2 py-2 fw-semibold text-center" width="6%">Carnet</th>
                            <th class="px-2 py-2 fw-semibold text-center" width="8%">Plan</th>
                            <th class="px-2 py-2 fw-semibold text-center" width="8%">Estado</th>
                            <th class="px-2 py-2 fw-semibold text-center" width="6%">Fecha</th>
                            <th class="px-2 py-2 fw-semibold text-center" width="15%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($participantesFinanzas as $index => $participante)
                            @php
                                $inscripcion = $oferta->inscripciones->where('estudiante_id', $participante['estudiante_id'])->first();
                            @endphp
                            @if($inscripcion)
                            <tr data-inscripcion-id="{{ $inscripcion->id }}" data-estudiante-id="{{ $participante['estudiante_id'] }}">
                                <td class="px-2 py-2 text-center">{{ $index + 1 }}</td>
                                <td class="px-2 py-2">
                                    <a href="{{ route('admin.estudiantes.detalle', $participante['estudiante_id']) }}" class="text-decoration-none">
                                        <span class="fw-medium">{{ $participante['nombre_completo'] }}</span>
                                    </a>
                                </td>
                                <td class="px-2 py-2 text-center">
                                    <span class="badge fs-9 text-white" style="background: #16a34a;">{{ $participante['carnet'] }}</span>
                                </td>
                                <td class="px-2 py-2 text-center">
                                    <span class="badge fs-9" style="background: var(--dash-primary-light); color: var(--dash-primary);">
                                        {{ $participante['plan_pago'] }}
                                    </span>
                                </td>
                                <td class="px-2 py-2 text-center">
                                    @if($inscripcion->estado == 'Inscrito')
                                    <span class="badge fs-9" style="background: #dcfce7; color: #16a34a;">Inscrito</span>
                                    @elseif($inscripcion->estado == 'Pre-Inscrito')
                                    <span class="badge fs-9" style="background: #fef3c7; color: #d97706;">Pre-Inscrito</span>
                                    @else
                                    <span class="badge fs-9" style="background: #e0f2fe; color: #0891b2;">{{ $inscripcion->estado }}</span>
                                    @endif
                                </td>
                                <td class="px-2 py-2 text-center">
                                    <span class="text-muted fs-11">{{ $inscripcion->fecha_registro?->format('d/m/Y') }}</span>
                                </td>
                                <td class="px-2 py-2 text-center">
                                    @if($inscripcion->estado != 'Transferido')
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn btn-sm btn-outline-danger py-1 px-2 btn-eliminar"
                                            data-inscripcion-id="{{ $inscripcion->id }}"
                                            data-estudiante="{{ $participante['nombre_completo'] }}"
                                            title="Eliminar" style="border-radius: 6px;">
                                            <i class="ri-delete-bin-line fs-10"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary py-1 px-2 btn-transferir"
                                            data-inscripcion-id="{{ $inscripcion->id }}"
                                            data-estudiante="{{ $participante['nombre_completo'] }}"
                                            data-estudiante-id="{{ $participante['estudiante_id'] }}"
                                            title="Transferir" style="border-radius: 6px;">
                                            <i class="ri-exchange-line fs-10"></i>
                                        </button>
                                    </div>
                                    @else
                                    <span class="badge fs-9" style="background: #e0f2fe; color: #0891b2;">Transferido</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>