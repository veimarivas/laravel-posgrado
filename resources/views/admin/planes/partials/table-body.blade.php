@forelse ($planes as $n => $plan)
    @php
        $vigente = false;
        if ($plan->es_promocion && $plan->fecha_inicio_promocion && $plan->fecha_fin_promocion) {
            $now = now();
            $inicio = \Carbon\Carbon::parse($plan->fecha_inicio_promocion);
            $fin = \Carbon\Carbon::parse($plan->fecha_fin_promocion);
            $vigente = $now->between($inicio, $fin);
        }

        // Formatear fechas para el JSON
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
        <td class="text-center">{{ $planes->firstItem() + $n }}</td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="ri-money-dollar-circle-line"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0">{{ $plan->nombre }}</h6>
                </div>
            </div>
        </td>
        <td class="text-center">
            @if ($plan->habilitado)
                <span class="badge bg-success-subtle text-success">
                    <i class="ri-check-line align-bottom me-1"></i> Sí
                </span>
            @else
                <span class="badge bg-danger-subtle text-danger">
                    <i class="ri-close-line align-bottom me-1"></i> No
                </span>
            @endif
        </td>
        <td class="text-center">
            @if ($plan->principal)
                <span class="badge bg-info-subtle text-info">
                    <i class="ri-star-line align-bottom me-1"></i> Sí
                </span>
            @else
                <span class="badge bg-secondary-subtle text-secondary">
                    <i class="ri-star-line align-bottom me-1"></i> No
                </span>
            @endif
        </td>
        <td class="text-center">
            @if ($plan->es_promocion)
                <span class="badge bg-warning-subtle text-warning">
                    <i class="ri-percent-line align-bottom me-1"></i> Sí
                </span>
            @else
                <span class="badge bg-secondary-subtle text-secondary">
                    <i class="ri-percent-line align-bottom me-1"></i> No
                </span>
            @endif
        </td>
        <td class="text-center">
            @if ($plan->es_promocion && $plan->fecha_inicio_promocion && $plan->fecha_fin_promocion)
                <small class="text-muted">
                    {{ \Carbon\Carbon::parse($plan->fecha_inicio_promocion)->format('d/m/Y') }}<br>
                    {{ \Carbon\Carbon::parse($plan->fecha_fin_promocion)->format('d/m/Y') }}
                </small>
            @else
                <span class="text-muted">-</span>
            @endif
        </td>
        <td class="text-center">
            @if ($plan->es_promocion && $plan->fecha_inicio_promocion && $plan->fecha_fin_promocion)
                @if ($vigente)
                    <span class="badge bg-success">
                        <i class="ri-check-double-line align-bottom me-1"></i> Vigente
                    </span>
                @else
                    @php
                        $now = now();
                        $inicio = \Carbon\Carbon::parse($plan->fecha_inicio_promocion);
                        if ($now->lt($inicio)) {
                            $estado = 'Próxima';
                            $clase = 'bg-info';
                        } else {
                            $estado = 'Vencida';
                            $clase = 'bg-danger';
                        }
                    @endphp
                    <span class="badge {{ $clase }}">
                        <i class="ri-time-line align-bottom me-1"></i> {{ $estado }}
                    </span>
                @endif
            @else
                <span class="badge bg-secondary">No aplica</span>
            @endif
        </td>
        <td class="text-center">
            <div class="btn-group" role="group">
                @if (Auth::guard('web')->user()->can('planes.pagos.editar'))
                    <button type="button" title="Editar Plan" class="btn btn-warning btn-sm editBtn"
                        data-bs-obj='@json($planData)' data-bs-toggle="modal"
                        data-bs-target="#modalModificar">
                        <i class="ri-edit-line"></i>
                    </button>
                @endif
                @if (Auth::guard('web')->user()->can('planes.pagos.eliminar'))
                    <button type="button" title="Eliminar Plan" class="btn btn-danger btn-sm deleteBtn"
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
        <td colspan="8" class="text-center py-4">
            <div class="text-muted">
                <i class="ri-inbox-line display-4"></i>
                <p class="mt-2">No se tienen registros de Planes de Pago</p>
            </div>
        </td>
    </tr>
@endforelse
