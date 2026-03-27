@php
    $rowNumber = $loop->iteration;
    $fase      = $oferta->fase;
    $faseColor = $fase->color ?? '#cccccc';
    $bgColor   = \App\Helpers\ViewHelper::hexToRgb($faseColor, 0.06);
@endphp

<tr id="oferta-{{ $oferta->id }}" style="background-color:{{ $bgColor }};transition:background .2s;">

    {{-- N° --}}
    <td class="text-center px-3">
        <span class="text-muted fw-semibold" style="font-size:.8rem;">{{ $rowNumber }}</span>
    </td>

    {{-- CÓDIGO --}}
    <td class="text-center">
        <span class="badge bg-dark rounded-2" style="font-size:.75rem;letter-spacing:.4px;">
            {{ $oferta->codigo }}
        </span>
    </td>

    {{-- PROGRAMA --}}
    <td class="py-2">
        <div class="fw-semibold" style="font-size:.85rem;color:#343a40;line-height:1.3;">
            {{ $oferta->programa->nombre ?? 'Sin programa' }}
        </div>
        <div class="text-muted mt-1" style="font-size:.72rem;">
            <i class="ri-building-2-line me-1"></i>{{ $oferta->sucursal->sede->nombre ?? 'Sin sede' }} · {{ $oferta->sucursal->nombre ?? 'Sin sucursal' }}
        </div>
        <div class="text-muted" style="font-size:.7rem;">
            <i class="ri-calendar-line me-1"></i>Gestión {{ $oferta->gestion ?? date('Y') }}
            @if ($oferta->version) <span class="ms-1">v{{ $oferta->version }}</span> @endif
            @if ($oferta->grupo)   <span class="ms-1">G{{ $oferta->grupo }}</span>   @endif
        </div>
    </td>

    {{-- MÓDULOS --}}
    <td class="text-center">
        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill">
            {{ $oferta->n_modulos ?? 0 }}
        </span>
    </td>

    {{-- CONVENIO --}}
    <td class="py-2">
        <div class="d-flex align-items-center gap-2">
            @if ($oferta->posgrado->convenio->imagen ?? false)
                <img src="{{ asset($oferta->posgrado->convenio->imagen) }}"
                     alt="{{ $oferta->posgrado->convenio->sigla ?? '' }}"
                     class="rounded-1 flex-shrink-0"
                     style="width:28px;height:28px;object-fit:cover;border:1px solid #dee2e6;">
            @else
                <div class="rounded-1 bg-light d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:28px;height:28px;">
                    <i class="ri-building-2-line text-muted" style="font-size:.7rem;"></i>
                </div>
            @endif
            <div class="min-w-0">
                @if ($oferta->posgrado->convenio->sigla ?? false)
                    <div class="fw-semibold" style="font-size:.78rem;">{{ $oferta->posgrado->convenio->sigla }}</div>
                @endif
                <div class="text-muted text-truncate" style="font-size:.7rem;max-width:85px;">
                    {{ $oferta->posgrado->convenio->nombre ?? 'Sin convenio' }}
                </div>
            </div>
        </div>
    </td>

    {{-- MODALIDAD --}}
    <td class="text-center">
        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill" style="font-size:.72rem;">
            {{ $oferta->modalidad->nombre ?? 'Sin modalidad' }}
        </span>
    </td>

    {{-- FECHAS --}}
    <td class="text-center py-2">
        <div class="fw-medium" style="font-size:.8rem;color:#2e8b57;">
            {{ \Carbon\Carbon::parse($oferta->fecha_inicio_programa)->format('d/m/Y') }}
        </div>
        <div class="text-muted" style="font-size:.72rem;">
            {{ \Carbon\Carbon::parse($oferta->fecha_fin_programa)->format('d/m/Y') }}
        </div>
        <div class="mt-1">
            <span class="badge bg-info bg-opacity-10 text-info" style="font-size:.68rem;">
                <i class="ri-bookmark-line me-1"></i>{{ \Carbon\Carbon::parse($oferta->fecha_inicio_inscripciones)->format('d/m/Y') }}
            </span>
        </div>
    </td>

    {{-- INSCRITOS --}}
    <td class="text-center">
        <span class="badge bg-success bg-gradient rounded-pill px-2" style="font-size:.8rem;">
            <i class="ri-user-follow-line me-1"></i>{{ $oferta->totalInscritos() }}
        </span>
        @if ($oferta->totalPreInscritos() > 0)
            <div class="mt-1">
                <span class="badge bg-secondary rounded-pill px-2" style="font-size:.72rem;">
                    <i class="ri-user-add-line me-1"></i>{{ $oferta->totalPreInscritos() }}
                </span>
            </div>
        @endif
    </td>

    {{-- FASE --}}
    <td class="text-center fase-celda">
        <span class="badge text-white rounded-pill fase-badge" style="background-color:{{ $faseColor }};font-size:.78rem;">
            {{ $fase->nombre ?? 'Sin fase' }}
        </span>
        <div class="text-muted mt-1" style="font-size:.7rem;">Fase {{ $fase->n_fase ?? '0' }}</div>
    </td>

    {{-- ACCIONES --}}
    <td class="py-2 px-2 acciones-celda">
        @include('admin.ofertas.partials.acciones-celda', ['oferta' => $oferta])
    </td>

</tr>
