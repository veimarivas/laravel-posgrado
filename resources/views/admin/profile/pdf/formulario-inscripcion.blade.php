<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de {{ $inscripcion->estado }} -
        {{ $inscripcion->ofertaAcademica->programa->nombre ?? 'Programa' }}</title>
    <style>
        /* Reset completo y optimización para PDF */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Helvetica', Arial, sans-serif;
            font-size: 9px;
            line-height: 1.2;
            color: #000;
            background-color: #ffffff;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Contenedor principal - A4 optimizado */
        .container {
            width: 100%;
            max-width: 190mm;
            /* Reducido de 210mm */
            margin: 0 auto;
            padding: 8mm;
            /* Reducido de 15mm */
        }

        /* Encabezado compacto */
        .header {
            border-bottom: 2px solid #1a365d;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 5px;
        }

        .logo-section {
            width: 25%;
        }

        .logo {
            max-width: 120px;
            /* Reducido de 180px */
            height: auto;
        }

        .title-section {
            width: 73%;
            text-align: center;
        }

        .title-section h1 {
            color: #1a365d;
            font-size: 14px;
            /* Reducido de 22px */
            margin-bottom: 3px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .title-section h2 {
            color: #e53e3e;
            font-size: 11px;
            /* Reducido de 18px */
            margin-bottom: 5px;
            font-weight: 600;
        }

        .estado-badge {
            display: inline-block;
            padding: 3px 8px;
            background-color: {{ $inscripcion->estado == 'Inscrito' ? '#38a169' : '#d69e2e' }};
            color: white;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 5px;
        }

        /* Grid de información compacto */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(45%, 1fr));
            gap: 8px;
            margin-bottom: 12px;
        }

        .info-item {
            margin-bottom: 4px;
        }

        .info-label {
            font-weight: 600;
            color: #4a5568;
            display: block;
            margin-bottom: 1px;
            font-size: 8px;
            text-transform: uppercase;
        }

        .info-value {
            color: #2d3748;
            font-size: 9px;
            padding: 4px 6px;
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 2px;
            min-height: 22px;
            display: flex;
            align-items: center;
        }

        /* Secciones */
        .section {
            margin-bottom: 10px;
            page-break-inside: avoid;
        }

        .section-title {
            color: #2d3748;
            background-color: #edf2f7;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 8px;
            padding: 5px 8px;
            border-left: 3px solid #4299e1;
            text-transform: uppercase;
        }

        /* Tablas optimizadas */
        .table-compact {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin-bottom: 8px;
        }

        .table-compact th {
            background-color: #2d3748;
            color: white;
            padding: 5px 6px;
            text-align: left;
            font-weight: 600;
            font-size: 8px;
        }

        .table-compact td {
            padding: 4px 6px;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-compact tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Tabla de planes de pago SUPER compacta */
        .table-ultra-compact {
            width: 100%;
            border-collapse: collapse;
            font-size: 7px;
            margin-bottom: 6px;
        }

        .table-ultra-compact th {
            background-color: #4a5568;
            color: white;
            padding: 3px 4px;
            text-align: left;
            font-weight: 600;
            font-size: 7px;
        }

        .table-ultra-compact td {
            padding: 3px 4px;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-ultra-compact tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Módulos en formato compacto */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-bottom: 10px;
        }

        .module-item {
            background-color: #ebf8ff;
            border: 1px solid #bee3f8;
            border-radius: 3px;
            padding: 6px 8px;
            font-size: 8px;
        }

        .module-number {
            display: inline-block;
            background-color: #3182ce;
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            text-align: center;
            line-height: 18px;
            font-weight: bold;
            margin-right: 6px;
            font-size: 8px;
        }

        /* Plan de pagos compacto */
        .plan-summary {
            background-color: #f0fff4;
            border: 1px solid #9ae6b4;
            border-radius: 3px;
            padding: 8px;
            margin-bottom: 8px;
        }

        .plan-header {
            background-color: #2d3748;
            color: white;
            padding: 5px 8px;
            border-radius: 2px;
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 6px;
        }

        .cuota-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dashed #cbd5e0;
            padding: 3px 0;
            font-size: 8px;
        }

        /* Firmas compactas */
        .signatures-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 15px;
            margin-bottom: 10px;
        }

        .signature-box {
            text-align: center;
            padding: 10px;
            border: 1px solid #cbd5e0;
            border-radius: 3px;
            background-color: #f8fafc;
            font-size: 8px;
        }

        .signature-line {
            border-top: 1px solid #4a5568;
            width: 80%;
            margin: 8px auto;
        }

        /* Footer optimizado */
        .footer {
            margin-top: 12px;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #718096;
            font-size: 7px;
        }

        /* Utilidades optimizadas */
        .text-xs {
            font-size: 7px;
        }

        .text-sm {
            font-size: 8px;
        }

        .text-md {
            font-size: 9px;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mb-1 {
            margin-bottom: 4px;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .mb-3 {
            margin-bottom: 12px;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .p-1 {
            padding: 4px;
        }

        .p-2 {
            padding: 8px;
        }

        .border-b {
            border-bottom: 1px solid #e2e8f0;
        }

        /* Colores para estados */
        .bg-success {
            background-color: #c6f6d5;
        }

        .bg-warning {
            background-color: #feebc8;
        }

        .bg-info {
            background-color: #bee3f8;
        }

        /* Control de saltos de página */
        .page-break-before {
            page-break-before: always;
        }

        .keep-together {
            page-break-inside: avoid;
        }

        .break-after {
            page-break-after: always;
        }

        /* Para evitar que las tablas se partan */
        table {
            page-break-inside: avoid;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        td {
            page-break-inside: avoid;
        }

        /* Anchos fijos para tablas */
        .w-10 {
            width: 10%;
        }

        .w-15 {
            width: 15%;
        }

        .w-20 {
            width: 20%;
        }

        .w-25 {
            width: 25%;
        }

        .w-30 {
            width: 30%;
        }

        .w-40 {
            width: 40%;
        }

        .w-50 {
            width: 50%;
        }

        .w-60 {
            width: 60%;
        }

        .w-70 {
            width: 70%;
        }

        .w-80 {
            width: 80%;
        }

        .w-90 {
            width: 90%;
        }

        .w-100 {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- ENCABEZADO COMPACTO -->
        <div class="header">
            <div class="header-content">
                <div class="logo-section">
                    @if (file_exists($logo_path))
                        <img src="{{ $logo_path }}" alt="Logo" class="logo">
                    @else
                        <div class="text-bold text-md">INSTITUCIÓN</div>
                    @endif
                </div>
                <div class="title-section">
                    <h1>FORMULARIO DE {{ strtoupper($inscripcion->estado) }}</h1>
                    <h2>{{ strtoupper($inscripcion->ofertaAcademica->programa->nombre ?? 'PROGRAMA') }}</h2>
                    <div class="estado-badge">{{ $inscripcion->estado }}</div>
                    <div class="text-xs">Generado: {{ $fecha_actual }} {{ $hora_actual }}</div>
                </div>
            </div>

            <!-- Información rápida en línea -->
            <div class="info-grid mt-1">
                <div class="info-item">
                    <span class="info-label">Código Oferta</span>
                    <div class="info-value">{{ $inscripcion->ofertaAcademica->codigo ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Sede - Sucursal</span>
                    <div class="info-value">
                        {{ $inscripcion->ofertaAcademica->sucursal->sede->nombre ?? 'N/A' }} -
                        {{ $inscripcion->ofertaAcademica->sucursal->nombre ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 1: DATOS DEL ESTUDIANTE (COMPACTA) -->
        <div class="section keep-together">
            <div class="section-title">1. DATOS DEL ESTUDIANTE</div>

            <div class="info-grid">
                <!-- Fila 1 -->
                <div class="info-item">
                    <span class="info-label">Nombre Completo</span>
                    <div class="info-value text-bold">
                        {{ $inscripcion->estudiante->persona->nombres }}
                        {{ $inscripcion->estudiante->persona->apellido_paterno }}
                        {{ $inscripcion->estudiante->persona->apellido_materno }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">CI / Carnet</span>
                    <div class="info-value">
                        {{ $inscripcion->estudiante->persona->carnet }}
                        {{ $inscripcion->estudiante->persona->expedido ? '(' . $inscripcion->estudiante->persona->expedido . ')' : '' }}
                    </div>
                </div>

                <!-- Fila 2 -->
                <div class="info-item">
                    <span class="info-label">Fecha Nacimiento / Edad</span>
                    <div class="info-value">
                        @if ($inscripcion->estudiante->persona->fecha_nacimiento)
                            {{ \Carbon\Carbon::parse($inscripcion->estudiante->persona->fecha_nacimiento)->format('d/m/Y') }}
                            ({{ \Carbon\Carbon::parse($inscripcion->estudiante->persona->fecha_nacimiento)->age }}
                            años)
                        @else
                            No registrada
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Sexo / Estado Civil</span>
                    <div class="info-value">
                        {{ $inscripcion->estudiante->persona->sexo ?? 'N/A' }} /
                        {{ $inscripcion->estudiante->persona->estado_civil ?? 'N/A' }}
                    </div>
                </div>

                <!-- Fila 3 -->
                <div class="info-item">
                    <span class="info-label">Correo Electrónico</span>
                    <div class="info-value">{{ $inscripcion->estudiante->persona->correo ?? 'No registrado' }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Teléfono / Celular</span>
                    <div class="info-value">
                        {{ $inscripcion->estudiante->persona->telefono ? $inscripcion->estudiante->persona->telefono . ' / ' : '' }}
                        {{ $inscripcion->estudiante->persona->celular ?? 'No registrado' }}
                    </div>
                </div>

                <!-- Fila 4 -->
                <div class="info-item" style="grid-column: span 2;">
                    <span class="info-label">Dirección / Ciudad</span>
                    <div class="info-value">
                        {{ $inscripcion->estudiante->persona->direccion ?? 'No registrada' }}
                        @if ($inscripcion->estudiante->persona->ciudad)
                            - {{ $inscripcion->estudiante->persona->ciudad->nombre }}
                            @if ($inscripcion->estudiante->persona->ciudad->departamento)
                                ({{ $inscripcion->estudiante->persona->ciudad->departamento->nombre }})
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: INFORMACIÓN DEL PROGRAMA (COMPACTA) -->
        <div class="section keep-together">
            <div class="section-title">2. INFORMACIÓN DEL PROGRAMA</div>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Programa Académico</span>
                    <div class="info-value text-bold">{{ $inscripcion->ofertaAcademica->programa->nombre ?? 'N/A' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Modalidad</span>
                    <div class="info-value">{{ $inscripcion->ofertaAcademica->modalidad->nombre ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Gestión / Grupo</span>
                    <div class="info-value">
                        {{ $inscripcion->ofertaAcademica->gestion ?? date('Y') }}
                        @if ($inscripcion->ofertaAcademica->grupo)
                            - Grupo {{ $inscripcion->ofertaAcademica->grupo }}
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha Inscripción</span>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($inscripcion->fecha_registro)->format('d/m/Y H:i') }}</div>
                </div>
                <div class="info-item" style="grid-column: span 2;">
                    <span class="info-label">Asesor Responsable</span>
                    <div class="info-value">
                        {{ $inscripcion->trabajador_cargo->trabajador->persona->nombres ?? 'N/A' }}
                        {{ $inscripcion->trabajador_cargo->trabajador->persona->apellido_paterno ?? '' }}
                        ({{ $inscripcion->trabajador_cargo->cargo->nombre ?? 'Asesor' }})
                    </div>
                </div>
            </div>
        </div>

        @if ($inscripcion->estado == 'Inscrito')
            <!-- SECCIÓN 3: MÓDULOS (SOLO INSCRITOS - COMPACTO) -->
            <div class="section keep-together">
                <div class="section-title">3. MÓDULOS DEL PROGRAMA</div>

                @if ($inscripcion->ofertaAcademica->modulos && $inscripcion->ofertaAcademica->modulos->count() > 0)
                    <table class="table-compact">
                        <thead>
                            <tr>
                                <th class="w-10">N°</th>
                                <th class="w-50">Nombre del Módulo</th>
                                <th class="w-20">Inicio</th>
                                <th class="w-20">Fin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inscripcion->ofertaAcademica->modulos->sortBy('n_modulo') as $modulo)
                                <tr>
                                    <td class="text-center">{{ $modulo->n_modulo }}</td>
                                    <td>{{ Str::limit($modulo->nombre, 40) }}</td>
                                    <td>{{ $modulo->fecha_inicio ? \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y') : 'Pend.' }}
                                    </td>
                                    <td>{{ $modulo->fecha_fin ? \Carbon\Carbon::parse($modulo->fecha_fin)->format('d/m/Y') : 'Pend.' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="info-value p-1 text-center bg-warning">
                        No hay módulos configurados
                    </div>
                @endif
            </div>

            <!-- SECCIÓN 4: PLAN DE PAGOS (SOLO INSCRITOS - SUPER COMPACTO) -->
            <div class="section keep-together">
                <div class="section-title">4. PLAN DE PAGOS</div>

                @if (isset($planesConFechas[$inscripcion->planes_pago_id]))
                    @php $plan = $planesConFechas[$inscripcion->planes_pago_id]; @endphp

                    <div class="plan-summary mb-2">
                        <div class="plan-header">{{ $plan['nombre'] }}</div>

                        @foreach ($plan['conceptos'] as $concepto)
                            <div class="mb-1">
                                <div class="text-bold text-sm border-b pb-1">
                                    {{ $concepto['nombre'] }} -
                                    {{ $concepto['n_cuotas'] }} cuota{{ $concepto['n_cuotas'] > 1 ? 's' : '' }} -
                                    Total: {{ number_format($concepto['monto_total'], 0, ',', '.') }} Bs
                                </div>

                                @if ($concepto['n_cuotas'] <= 6)
                                    <!-- Mostrar todas las cuotas si son 6 o menos -->
                                    @for ($i = 0; $i < $concepto['n_cuotas']; $i++)
                                        <div class="cuota-row">
                                            <span>Cuota {{ $i + 1 }}
                                                ({{ $concepto['fechas_pago'][$i] ?? 'Pend.' }})
                                            </span>
                                            <span
                                                class="text-bold">{{ number_format($concepto['montos_cuotas'][$i] ?? 0, 0, ',', '.') }}
                                                Bs</span>
                                        </div>
                                    @endfor
                                @else
                                    <!-- Mostrar solo las primeras 3 y resumen -->
                                    @for ($i = 0; $i < 3; $i++)
                                        <div class="cuota-row">
                                            <span>Cuota {{ $i + 1 }}
                                                ({{ $concepto['fechas_pago'][$i] ?? 'Pend.' }})</span>
                                            <span
                                                class="text-bold">{{ number_format($concepto['montos_cuotas'][$i] ?? 0, 0, ',', '.') }}
                                                Bs</span>
                                        </div>
                                    @endfor
                                    <div class="cuota-row text-center text-xs text-bold">
                                        ... y {{ $concepto['n_cuotas'] - 3 }} cuotas más
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <!-- Total del plan -->
                        @php
                            $totalPlan = 0;
                            foreach ($plan['conceptos'] as $concepto) {
                                $totalPlan += $concepto['monto_total'];
                            }
                        @endphp
                        <div class="cuota-row text-bold"
                            style="background-color: #e6fffa; border-top: 2px solid #38b2ac;">
                            <span>TOTAL PLAN DE PAGOS</span>
                            <span>{{ number_format($totalPlan, 0, ',', '.') }} Bs</span>
                        </div>
                    </div>
                @else
                    <div class="info-value p-1 text-center bg-warning">
                        No hay plan de pagos configurado
                    </div>
                @endif
            </div>
        @endif

        <!-- SECCIÓN 5: OBSERVACIONES COMPACTA -->
        <div class="section keep-together">
            <div class="section-title">5. OBSERVACIONES</div>
            <div class="info-value p-2" style="min-height: 40px; font-size: 8px;">
                {{ $inscripcion->observacion ?? 'Sin observaciones registradas.' }}
            </div>
        </div>

        <!-- SECCIÓN 6: FIRMAS COMPACTAS -->
        <div class="section keep-together">
            <div class="section-title">6. AUTORIZACIONES Y FIRMAS</div>

            <div class="signatures-grid">
                <!-- Estudiante -->
                <div class="signature-box">
                    <div class="text-bold mb-1">ESTUDIANTE</div>
                    <div class="text-xs mb-1">
                        {{ $inscripcion->estudiante->persona->nombres }}
                        {{ $inscripcion->estudiante->persona->apellido_paterno }}
                    </div>
                    <div class="signature-line"></div>
                    <div class="text-xs mt-1">Firma y aclaración</div>
                </div>

                <!-- Asesor -->
                <div class="signature-box">
                    <div class="text-bold mb-1">ASESOR</div>
                    <div class="text-xs mb-1">
                        {{ $inscripcion->trabajador_cargo->trabajador->persona->nombres ?? 'N/A' }}
                        {{ $inscripcion->trabajador_cargo->trabajador->persona->apellido_paterno ?? '' }}
                    </div>
                    <div class="text-xs" style="color: #718096;">
                        {{ $inscripcion->trabajador_cargo->cargo->nombre ?? 'Asesor' }}
                    </div>
                    <div class="signature-line"></div>
                    <div class="text-xs mt-1">Firma y sello</div>
                </div>

                <!-- Coordinación -->
                <div class="signature-box">
                    <div class="text-bold mb-1">COORDINACIÓN ACADÉMICA</div>
                    <div class="text-xs mb-1">Autorización y control</div>
                    <div class="signature-line"></div>
                    <div class="text-xs mt-1">Firma, sello y fecha</div>
                </div>
            </div>
        </div>

        <!-- FOOTER SUPER COMPACTO -->
        <div class="footer">
            <div class="text-bold mb-1">SISTEMA DE GESTIÓN ACADÉMICA</div>
            <div class="text-xs">
                ID Inscripción: {{ $inscripcion->id }} |
                Oferta: {{ $inscripcion->ofertaAcademica->codigo ?? 'N/A' }} |
                CI: {{ $inscripcion->estudiante->persona->carnet }} |
                Página 1/1
            </div>
            <div class="text-xs mt-1" style="color: #a0aec0;">
                Documento generado automáticamente - Válido para fines administrativos
            </div>
        </div>
    </div>
</body>

</html>
