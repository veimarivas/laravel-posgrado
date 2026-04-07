@forelse ($planes as $n => $plan)
    @php
        $vigente = false;
        $estadoPromo = '';
        $clasePromo = '';
        if ($plan->es_promocion && $plan->fecha_inicio_promocion && $plan->fecha_fin_promocion) {
            $now = now();
            $inicio = \Carbon\Carbon::parse($plan->fecha_inicio_promocion);
            $fin = \Carbon\Carbon::parse($plan->fecha_fin_promocion);
            $vigente = $now->between($inicio, $fin);
            if (!$vigente) {
                if ($now->lt($inicio)) {
                    $estadoPromo = 'Próxima';
                    $clasePromo = 'promo-upcoming';
                } else {
                    $estadoPromo = 'Vencida';
                    $clasePromo = 'promo-expired';
                }
            } else {
                $estadoPromo = 'Vigente';
                $clasePromo = 'promo-active';
            }
        }

        $fecha_inicio_formatted = $plan->fecha_inicio_promocion ? $plan->fecha_inicio_promocion->format('Y-m-d') : null;
        $fecha_fin_formatted = $plan->fecha_fin_promocion ? $plan->fecha_fin_promocion->format('Y-m-d') : null;

        $planData = [
            'id' => $plan->id,
            'nombre' => $plan->nombre,
            'habilitado' => $plan->habilitado,
            'principal' => $plan->principal,
            'es_promocion' => $plan->es_promocion,
            'fecha_inicio_promocion' => $fecha_inicio_formatted,
            'fecha_fin_promocion' => $fecha_fin_formatted,
        ];
    @endphp

    <tr data-nombre="{{ strtolower($plan->nombre) }}" data-habilitado="{{ $plan->habilitado }}"
        data-principal="{{ $plan->principal }}" data-promocion="{{ $plan->es_promocion }}"
        data-vigente="{{ $vigente ? 1 : 0 }}">
        <td class="text-center text-muted">{{ $planes->firstItem() + $n }}</td>
        <td>
            <div class="plan-name-cell">
                <div class="plan-avatar">
                    <i class="ri-money-dollar-circle-line"></i>
                </div>
                <div class="plan-name-text">
                    <h6>{{ $plan->nombre }}</h6>
                </div>
            </div>
        </td>
        <td>
            <div class="d-flex flex-column gap-1">
                <span class="status-badge {{ $plan->habilitado ? 'yes' : 'no' }}">
                    <i class="ri-{{ $plan->habilitado ? 'check' : 'close' }}-line"></i>
                    {{ $plan->habilitado ? 'Habilitado' : 'Deshabilitado' }}
                </span>
                @if ($plan->principal)
                    <span class="status-badge featured">
                        <i class="ri-star-line"></i> Principal
                    </span>
                @endif
            </div>
        </td>
        <td>
            @if ($plan->es_promocion)
                <span class="status-badge promo-active">
                    <i class="ri-percent-line"></i> Sí
                </span>
            @else
                <span class="status-badge no">
                    <i class="ri-percent-line"></i> No
                </span>
            @endif
        </td>
        <td>
            @if ($plan->es_promocion && $plan->fecha_inicio_promocion && $plan->fecha_fin_promocion)
                <div class="d-flex flex-column gap-1">
                    <small class="text-muted">
                        <i class="ri-calendar-line me-1"></i>
                        {{ \Carbon\Carbon::parse($plan->fecha_inicio_promocion)->format('d/m/Y') }}
                        — {{ \Carbon\Carbon::parse($plan->fecha_fin_promocion)->format('d/m/Y') }}
                    </small>
                    <span class="status-badge {{ $clasePromo }}">
                        <i class="ri-{{ $vigente ? 'check-double' : 'time' }}-line"></i>
                        {{ $estadoPromo }}
                    </span>
                </div>
            @else
                <span class="status-badge promo-na">No aplica</span>
            @endif
        </td>
        <td class="text-center">
            <div class="d-flex align-items-center justify-content-center gap-6">
                @if (Auth::guard('web')->user()->can('planes.pagos.editar'))
                    <button type="button" title="Editar Plan" class="action-btn edit editBtn"
                        data-bs-obj='@json($planData)' data-bs-toggle="modal"
                        data-bs-target="#modalModificar">
                        <i class="ri-edit-line"></i>
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('planes.pagos.eliminar'))
                    <button type="button" title="Eliminar Plan" class="action-btn delete deleteBtn"
                        data-bs-obj='@json($plan)' data-bs-toggle="modal"
                        data-bs-target="#modalEliminar">
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
                <p>No se tienen registros de Planes de Pago</p>
            </div>
        </td>
    </tr>
@endforelse
