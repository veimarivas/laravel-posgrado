@php
    $rowNumber = $loop->iteration;
    $fase      = $oferta->fase;
    $faseColor = ($fase->n_fase ?? 0) == 4 ? '#f97316' : ($fase->color ?? '#cccccc');
@endphp

<tr id="oferta-{{ $oferta->id }}" style="transition:background .15s;">

    {{-- N° --}}
    <td class="text-center">
        <span class="text-muted fw-semibold" style="font-size:.78rem;">{{ $rowNumber }}</span>
    </td>

    {{-- OFERTA (Código + Programa + Sede + Gestión) --}}
    <td>
        <div class="oferta-name-cell">
            <div class="oferta-avatar">
                <i class="ri-graduation-cap-line"></i>
            </div>
            <div class="oferta-name-text">
                <h6>{{ $oferta->programa->nombre ?? 'Sin programa' }}</h6>
                <div class="oferta-meta">
                    <span class="oferta-code-badge">{{ $oferta->codigo }}</span>
                    <span class="oferta-sucursal">
                        <i class="ri-building-2-line me-1"></i>{{ $oferta->sucursal->sede->nombre ?? 'Sin sede' }} · {{ $oferta->sucursal->nombre ?? 'Sin sucursal' }}
                    </span>
                </div>
                <div class="oferta-gestion">
                    <i class="ri-calendar-line me-1"></i>Gestión {{ $oferta->gestion ?? date('Y') }}
                    @if ($oferta->version) <span class="ms-1">v{{ $oferta->version }}</span> @endif
                    @if ($oferta->grupo)   <span class="ms-1">G{{ $oferta->grupo }}</span>   @endif
                </div>
            </div>
        </div>
    </td>

    {{-- MÓDULOS --}}
    <td class="text-center">
        <span class="badge-modulos">{{ $oferta->n_modulos ?? 0 }}</span>
    </td>

    {{-- CONVENIO --}}
    <td>
        <div class="convenio-cell">
            @if ($oferta->posgrado->convenio->imagen ?? false)
                <img src="{{ asset($oferta->posgrado->convenio->imagen) }}"
                     alt="{{ $oferta->posgrado->convenio->sigla ?? '' }}"
                     class="convenio-img-small">
            @else
                <div class="convenio-placeholder">
                    <i class="ri-building-2-line"></i>
                </div>
            @endif
            <div class="convenio-name-text">
                @if ($oferta->posgrado->convenio->sigla ?? false)
                    <div class="convenio-sigla">{{ $oferta->posgrado->convenio->sigla }}</div>
                @endif
                <div class="convenio-full-name" title="{{ $oferta->posgrado->convenio->nombre ?? 'Sin convenio' }}">
                    {{ $oferta->posgrado->convenio->nombre ?? 'Sin convenio' }}
                </div>
            </div>
        </div>
    </td>

    {{-- MODALIDAD --}}
    <td class="text-center">
        <span class="badge-modalidad">{{ $oferta->modalidad->nombre ?? 'Sin modalidad' }}</span>
    </td>

    {{-- FECHAS --}}
    <td class="text-center">
        <div class="fecha-inicio">{{ \Carbon\Carbon::parse($oferta->fecha_inicio_programa)->format('d/m/Y') }}</div>
        <div class="fecha-fin">{{ \Carbon\Carbon::parse($oferta->fecha_fin_programa)->format('d/m/Y') }}</div>
        <span class="badge-inscripcion">
            <i class="ri-bookmark-line me-1"></i>{{ \Carbon\Carbon::parse($oferta->fecha_inicio_inscripciones)->format('d/m/Y') }}
        </span>
    </td>

    {{-- INSCRITOS --}}
    <td class="text-center">
        <span class="badge-inscritos">
            <i class="ri-user-follow-line me-1"></i>{{ $oferta->totalInscritos() }}
        </span>
        @if ($oferta->totalPreInscritos() > 0)
            <div class="badge-preinscritos">
                <span class="badge-pre-count">
                    <i class="ri-user-add-line me-1"></i>{{ $oferta->totalPreInscritos() }} pre
                </span>
            </div>
        @endif
    </td>

    {{-- FASE --}}
    <td class="text-center">
        <span class="badge-fase" style="background-color:{{ $faseColor }};">
            {{ $fase->nombre ?? 'Sin fase' }}
        </span>
    </td>

    {{-- ACCIONES --}}
    <td>
        <div class="actions-cell">
            @include('admin.ofertas.partials.acciones-celda', ['oferta' => $oferta])
        </div>
    </td>

</tr>
