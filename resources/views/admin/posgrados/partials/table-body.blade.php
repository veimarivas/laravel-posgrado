<tbody>
    @forelse($posgrados as $index => $p)
        <tr>
            <td class="text-center text-muted">{{ $posgrados->firstItem() + $index }}</td>
            <td>
                <div class="posgrado-name-cell">
                    <div class="posgrado-icon">
                        <i class="ri-graduation-cap-line"></i>
                    </div>
                    <div class="posgrado-name-text">{{ $p->nombre }}</div>
                </div>
            </td>
            <td>
                <div class="info-stacked">
                    @if ($p->convenio)
                        <span class="info-badge convenio">
                            <i class="ri-handshake-line"></i> {{ $p->convenio->nombre }}
                        </span>
                    @else
                        <span class="info-badge" style="background: #f1f5f9; color: #94a3b8;">
                            <i class="ri-handshake-line"></i> Sin convenio
                        </span>
                    @endif
                    @if ($p->area)
                        <span class="info-badge area">
                            <i class="ri-layout-grid-line"></i> {{ $p->area->nombre }}
                        </span>
                    @else
                        <span class="info-badge" style="background: #f1f5f9; color: #94a3b8;">
                            <i class="ri-layout-grid-line"></i> Sin área
                        </span>
                    @endif
                    @if ($p->tipo)
                        <span class="info-badge tipo">
                            <i class="ri-price-tag-3-line"></i> {{ $p->tipo->nombre }}
                        </span>
                    @endif
                </div>
            </td>
            <td>
                <div class="detail-stacked">
                    <div class="detail-row">
                        <i class="ri-time-line"></i>
                        <span><strong>{{ $p->duracion_numero }}</strong> {{ strtolower($p->duracion_unidad) }}</span>
                    </div>
                    <div class="detail-row">
                        <i class="ri-timer-line"></i>
                        <span><strong>{{ $p->carga_horaria }}h</strong> carga</span>
                    </div>
                    <div class="detail-row">
                        <i class="ri-star-line"></i>
                        <span><strong>{{ $p->creditaje }}</strong> créditos</span>
                    </div>
                </div>
            </td>
            <td class="text-center">
                <span class="status-badge {{ $p->estado == 'activo' ? 'active' : 'inactive' }}">
                    <i class="ri-{{ $p->estado == 'activo' ? 'check' : 'close' }}-line"></i>
                    {{ $p->estado == 'activo' ? 'Activo' : 'Inactivo' }}
                </span>
            </td>
            <td class="text-center">
                <div class="d-flex align-items-center justify-content-center gap-6">
                    @if (Auth::guard('web')->user()->can('posgrados.ver'))
                        <a href="{{ route('admin.posgrados.ver', $p->id) }}" title="Ver Ofertas"
                            class="action-btn view">
                            <i class="ri-eye-line"></i>
                        </a>
                    @endif
                    @if ($p->estado == 'activo' && Auth::guard('web')->user()->can('ofertas.academicas.registrar'))
                        <button type="button" class="action-btn offer" data-posgrado-id="{{ $p->id}}"
                            data-posgrado-nombre="{{ $p->nombre }}" data-bs-toggle="modal"
                            data-bs-target="#modalRegistrarOferta" title="Registrar Oferta">
                            <i class="ri-add-circle-line"></i>
                        </button>
                    @endif
                    @if (Auth::guard('web')->user()->can('posgrados.editar'))
                        <button type="button" title="Editar" class="action-btn edit editBtn"
                            data-bs-obj='@json($p)' data-bs-toggle="modal"
                            data-bs-target=".modificar">
                            <i class="ri-edit-line"></i>
                        </button>
                    @endif
                    @if (Auth::guard('web')->user()->can('posgrados.eliminar'))
                        <button type="button" title="Eliminar" class="action-btn delete deleteBtn"
                            data-bs-obj='@json($p)' data-bs-toggle="modal"
                            data-bs-target=".eliminar">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6">
                <div class="empty-state">
                    <i class="ri-inbox-line"></i>
                    <p>No hay posgrados registrados</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
