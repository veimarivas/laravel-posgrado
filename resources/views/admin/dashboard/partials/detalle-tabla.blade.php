@if ($inscripciones->isEmpty())
    <div class="alert alert-info text-center">
        <i class="ri-information-line me-2"></i> No hay inscripciones registradas.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Estado</th>
                    <th>Estudiante</th>
                    <th>Tipo Programa</th>
                    <th>Programa</th>
                    <th>Sucursal</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inscripciones as $insc)
                    <tr>
                        <td>
                            <span class="badge bg-{{ $insc->estado === 'Inscrito' ? 'success' : 'warning' }} text-dark">
                                {{ $insc->estado }}
                            </span>
                        </td>
                        <td>
                            @php
                                $est = optional($insc->estudiante)->persona;
                            @endphp
                            {{ $est ? trim("{$est->nombres} {$est->apellido_paterno}") : '—' }}
                        </td>
                        <td>{{ optional($insc->ofertaAcademica->posgrado->tipo)->nombre ?? '—' }}</td>
                        <td>{{ optional($insc->ofertaAcademica->programa)->nombre ?? '—' }}</td>
                        <td>{{ optional($insc->ofertaAcademica->sucursal)->nombre ?? '—' }}</td>
                        <td>{{ $insc->fecha_registro->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
