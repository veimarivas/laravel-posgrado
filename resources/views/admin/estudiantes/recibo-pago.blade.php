<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago - {{ $pago->recibo }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }

        .comprobante-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            position: relative;
        }

        /* Encabezado con logo */
        .header-institucion {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 15px;
        }

        .logo-container {
            flex: 0 0 120px;
            margin-right: 20px;
            background-color: #e8e8e8;
            border-radius: 8px;
            padding: 6px;
        }

        .logo-img {
            max-width: 120px;
            max-height: 80px;
            object-fit: contain;
        }

        .logo-placeholder {
            width: 120px;
            height: 80px;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #999;
            border: 1px dashed #ccc;
        }

        .institucion-info {
            flex: 1;
            text-align: center;
        }

        .nombre-institucion {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .lema-institucion {
            font-size: 14px;
            color: #666;
            font-style: italic;
            margin-bottom: 10px;
        }

        .sistema {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }

        /* Información del comprobante */
        .info-comprobante {
            margin-bottom: 20px;
        }

        .sede {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .sede-label {
            font-weight: bold;
        }

        .comprobante-numero {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            padding: 5px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        .info-pago {
            margin-bottom: 15px;
            font-size: 14px;
        }

        /* Datos del estudiante y programa */
        .datos-programa {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-left: 4px solid #2c3e50;
        }

        .programa-titulo {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .datos-estudiante {
            font-size: 13px;
            line-height: 1.6;
        }

        /* Tabla de conceptos */
        .conceptos-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 12px;
        }

        .conceptos-table th {
            background-color: #2c3e50;
            color: white;
            padding: 8px 5px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .conceptos-table td {
            padding: 8px 5px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .conceptos-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .total-row {
            background-color: #e9ecef !important;
            font-weight: bold;
        }

        /* Totales */
        .totales {
            margin-top: 20px;
            text-align: right;
            padding: 10px;
            background-color: #f8f9fa;
            border-top: 2px solid #dee2e6;
        }

        .total-monto {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }

        .total-letras {
            font-size: 13px;
            margin-top: 10px;
            text-align: center;
            font-style: italic;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 4px;
        }

        /* Firmas */
        .firmas {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .firma-section {
            display: inline-block;
            width: 48%;
            vertical-align: top;
            text-align: center;
        }

        .linea-firma {
            width: 200px;
            border-top: 1px solid #333;
            margin: 20px auto 5px;
        }

        .nombre-firma {
            font-weight: bold;
            margin-top: 5px;
        }

        .cargo-firma {
            font-size: 11px;
            color: #666;
        }

        /* Responsive para impresión */
        @media print {
            body {
                padding: 10px;
                font-size: 11px;
            }

            .comprobante-container {
                border: none;
                padding: 10px;
            }

            .no-print {
                display: none;
            }

            .logo-img {
                max-width: 100px;
                max-height: 60px;
            }

            .logo-placeholder {
                width: 100px;
                height: 60px;
                font-size: 8px;
            }
        }

        @media screen and (max-width: 768px) {
            .header-institucion {
                flex-direction: column;
                text-align: center;
            }

            .logo-container {
                margin-right: 0;
                margin-bottom: 15px;
            }
        }

        /* Utilidades */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .mt-3 {
            margin-top: 15px;
        }

        .mb-3 {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="comprobante-container">
        <!-- Encabezado de la institución con logo -->
        <div class="header-institucion">
            <div class="logo-container">
                @php
                    // DomPDF requiere ruta absoluta del sistema (no URL HTTP)
                    $logoPath = public_path('backend/assets/images/logo.png');
                    $logoBase64 = file_exists($logoPath)
                        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
                        : null;
                @endphp

                @if ($logoBase64)
                    <img src="{{ $logoBase64 }}" class="logo-img">
                @else
                    <div class="logo-placeholder">
                        <div style="font-size: 14px; font-weight: bold; color: #2c3e50;">UNIP</div>
                        <div style="font-size: 10px; color: #666;">Universidad de Postgrado</div>
                    </div>
                @endif
            </div>

            <div class="institucion-info">
                <div class="nombre-institucion">UNIDAD DE PROFESIONALES</div>
                <div class="lema-institucion">UNIP BOLIVIA SRL</div>
                <div class="sistema">SISTEMA DE PAGOS</div>
            </div>
        </div>

        @php
            $sede = $cuota?->inscripcion?->ofertaAcademica?->sucursal?->nombre ?? 'Sede no especificada';
            $programa = $cuota?->inscripcion?->ofertaAcademica?->programa?->nombre ?? 'Programa no especificado';
            $total = $pago->pago_bs - ($pago->descuento_bs ?? 0);
        @endphp

        <!-- Información del comprobante -->
        <div class="info-comprobante">
            <div class="sede">
                <span class="sede-label">Lugar / Sede:</span> {{ $sede }}
            </div>
            <div class="comprobante-numero text-center">
                COMPROBANTE N° {{ $pago->recibo }}
            </div>
            <div class="info-pago">
                <span class="bold">Forma de Pago:</span> {{ $pago->tipo_pago }}
                &nbsp;&nbsp;
                <span class="bold">Fecha:</span> {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
            </div>
        </div>

        <!-- Datos del programa y estudiante -->
        <div class="datos-programa">
            <div class="programa-titulo">PROGRAMA</div>
            <div class="mb-3">{{ $programa }}</div>

            <div class="datos-estudiante">
                @if ($estudiante && $estudiante->persona)
                    <div>
                        <span class="bold">Estudiante:</span>
                        {{ $estudiante->persona->nombres }}
                        {{ $estudiante->persona->apellido_paterno }}
                        {{ $estudiante->persona->apellido_materno ?? '' }}
                        - {{ $estudiante->persona->carnet }}
                    </div>
                    <div>
                        <span class="bold">Señor(a) Depositante:</span>
                        {{ $estudiante->persona->nombres }} {{ $estudiante->persona->apellido_paterno }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Tabla de conceptos -->
        <table class="conceptos-table">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>N° de Cuota</th>
                    <th>Cantidad</th>
                    <th>Monto (Bs)</th>
                    <th>Subtotal (Bs)</th>
                    <th>Unidad Medida</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pagosCuotas as $pc)
                    @php $c = $pc->cuota; @endphp
                    <tr>
                        <td>{{ $c?->nombre ?? 'N/A' }}</td>
                        <td>{{ $c?->n_cuota ?? '-' }}</td>
                        <td>1</td>
                        <td>{{ number_format($pc->pago_bs, 2) }}</td>
                        <td>{{ number_format($pc->pago_bs, 2) }}</td>
                        <td>Cuota</td>
                    </tr>
                @endforeach
                @if (($pago->descuento_bs ?? 0) > 0)
                    <tr>
                        <td>Descuento</td>
                        <td>-</td>
                        <td>1</td>
                        <td>-{{ number_format($pago->descuento_bs, 2) }}</td>
                        <td>-{{ number_format($pago->descuento_bs, 2) }}</td>
                        <td>Descuento</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td colspan="4" class="text-right bold">Total (Bolivianos)</td>
                    <td colspan="2" class="bold">{{ number_format($total, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Total en letras -->
        <div class="total-letras">
            @php
                // Función para convertir número a letras
                function numeroALetras($numero)
                {
                    $unidades = ['', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve'];
                    $decenas = [
                        'diez',
                        'once',
                        'doce',
                        'trece',
                        'catorce',
                        'quince',
                        'dieciséis',
                        'diecisiete',
                        'dieciocho',
                        'diecinueve',
                    ];
                    $decenas2 = [
                        '',
                        '',
                        'veinte',
                        'treinta',
                        'cuarenta',
                        'cincuenta',
                        'sesenta',
                        'setenta',
                        'ochenta',
                        'noventa',
                    ];
                    $centenas = [
                        '',
                        'ciento',
                        'doscientos',
                        'trescientos',
                        'cuatrocientos',
                        'quinientos',
                        'seiscientos',
                        'setecientos',
                        'ochocientos',
                        'novecientos',
                    ];

                    $entero = floor($numero);
                    $decimal = round(($numero - $entero) * 100);

                    if ($entero == 0) {
                        return 'cero';
                    }
                    if ($entero == 1) {
                        return 'uno';
                    }

                    $letras = '';

                    // Miles
                    if ($entero >= 1000) {
                        $miles = floor($entero / 1000);
                        if ($miles == 1) {
                            $letras .= 'mil ';
                        } else {
                            $letras .= numeroALetras($miles) . ' mil ';
                        }
                        $entero %= 1000;
                    }

                    // Centenas
                    if ($entero >= 100) {
                        $centena = floor($entero / 100);
                        if ($entero == 100) {
                            $letras .= 'cien ';
                        } else {
                            $letras .= $centenas[$centena] . ' ';
                        }
                        $entero %= 100;
                    }

                    // Decenas
                    if ($entero >= 10 && $entero < 20) {
                        $letras .= $decenas[$entero - 10] . ' ';
                        $entero = 0;
                    } elseif ($entero >= 20) {
                        $decena = floor($entero / 10);
                        $letras .= $decenas2[$decena];
                        $entero %= 10;
                        if ($entero > 0) {
                            $letras .= ' y ';
                        }
                    }

                    // Unidades
                    if ($entero > 0) {
                        $letras .= $unidades[$entero] . ' ';
                    }

                    return trim($letras);
                }

                $total = $pago->pago_bs - $pago->descuento_bs;
                $letras = numeroALetras($total);
            @endphp

            Son: {{ ucfirst($letras) }} bolivianos {{ number_format($total, 2) }}/00
        </div>

        <!-- Firmas -->
        <div class="firmas">
            <div class="firma-section">
                <div class="linea-firma"></div>
                @if (!empty($cobrador))
                    <div class="nombre-firma">
                        {{ $cobrador->nombres }} {{ $cobrador->apellido_paterno }}
                        {{ $cobrador->apellido_materno ?? '' }}
                    </div>
                    <div class="cargo-firma">{{ $cobrador->cargo }}</div>
                @else
                    <div class="nombre-firma">Emisor</div>
                    <div class="cargo-firma">Auxiliar Contable</div>
                @endif
            </div>

            <div class="firma-section">
                <div class="linea-firma"></div>
                <div class="nombre-firma">Depositante</div>
                @if ($estudiante && $estudiante->persona)
                    <div class="cargo-firma">{{ $estudiante->persona->nombres }}
                        {{ $estudiante->persona->apellido_paterno }}</div>
                    <div class="cargo-firma mt-3">N° Doc: {{ $estudiante->persona->carnet }}</div>
                @endif
            </div>
        </div>

        <!-- Información adicional -->
        <div class="mt-3 text-center" style="font-size: 10px; color: #666;">
            Comprobante generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} |
            Sistema UNIP
        </div>
    </div>

    <script>
        // Verificar si el logo se cargó correctamente
        document.addEventListener('DOMContentLoaded', function() {
            var logoImg = document.querySelector('.logo-img');
            var logoPlaceholder = document.getElementById('logo-placeholder');

            // Verificar si la imagen no se cargó (usando el evento onerror ya está en el HTML)
            // Además, verificar si la imagen tiene dimensiones (si falló la carga, width y height serán 0)
            if (logoImg && (logoImg.naturalWidth === 0 || logoImg.naturalHeight === 0)) {
                logoImg.style.display = 'none';
                if (logoPlaceholder) {
                    logoPlaceholder.style.display = 'flex';
                }
            }

            // Opcional: auto-imprimir al cargar (descomentar si se desea)
            // window.print();
        });
    </script>
</body>

</html>
