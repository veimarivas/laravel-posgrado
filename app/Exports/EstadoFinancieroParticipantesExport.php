<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class EstadoFinancieroParticipantesExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithEvents, ShouldAutoSize
{
    protected $participantes;
    protected $sede;
    protected $sucursal;
    protected $oferta;

    public function __construct($participantes, $sede, $sucursal, $oferta)
    {
        $this->participantes = $participantes;
        $this->sede = $sede;
        $this->sucursal = $sucursal;
        $this->oferta = $oferta;
    }

    public function collection()
    {
        // Transformamos los datos para Excel
        return collect($this->participantes)->map(function ($participante) {
            return [
                '#' => '', // Se llenará automáticamente
                'APELLIDO PATERNO' => $participante['apellido_paterno'] ?? '',
                'APELLIDO MATERNO' => $participante['apellido_materno'] ?? '',
                'NOMBRES' => $participante['nombres'] ?? '',
                'CARNET' => $participante['carnet'],
                'PLAN DE PAGO' => $participante['plan_pago'],
                'VENDEDOR' => $participante['vendedor'] ?? 'N/A',
                // NUEVAS COLUMNAS
                'FECHA INSCRIPCIÓN' => isset($participante['fecha_inscripcion']) ?
                    \Carbon\Carbon::parse($participante['fecha_inscripcion'])->format('d/m/Y') : '',
                'PROFESIÓN' => $participante['profesion'] ?? 'No registrado',
                'CELULAR' => $participante['celular'] ?? 'Sin celular',
                'CORREO' => $participante['correo'] ?? 'Sin correo',
                // FIN NUEVAS COLUMNAS
                'TOTAL PLAN (Bs)' => $participante['total_plan'],

                // MATRÍCULA
                'MATRÍCULA TOTAL (Bs)' => $participante['conceptos']['Matrícula']['total'] ?? 0,
                'MATRÍCULA PAGADO (Bs)' => $participante['conceptos']['Matrícula']['pagado'] ?? 0,
                'MATRÍCULA PENDIENTE (Bs)' => $participante['conceptos']['Matrícula']['pendiente'] ?? 0,
                'MATRÍCULA N° CUOTAS' => $participante['conceptos']['Matrícula']['n_cuotas'] ?? 0,

                // COLEGIATURA
                'COLEGIATURA TOTAL (Bs)' => $participante['conceptos']['Colegiatura']['total'] ?? 0,
                'COLEGIATURA PAGADO (Bs)' => $participante['conceptos']['Colegiatura']['pagado'] ?? 0,
                'COLEGIATURA PENDIENTE (Bs)' => $participante['conceptos']['Colegiatura']['pendiente'] ?? 0,
                'COLEGIATURA N° CUOTAS' => $participante['conceptos']['Colegiatura']['n_cuotas'] ?? 0,

                // CERTIFICACIÓN
                'CERTIFICACIÓN TOTAL (Bs)' => $participante['conceptos']['Certificación']['total'] ?? 0,
                'CERTIFICACIÓN PAGADO (Bs)' => $participante['conceptos']['Certificación']['pagado'] ?? 0,
                'CERTIFICACIÓN PENDIENTE (Bs)' => $participante['conceptos']['Certificación']['pendiente'] ?? 0,
                'CERTIFICACIÓN N° CUOTAS' => $participante['conceptos']['Certificación']['n_cuotas'] ?? 0,

                // TOTALES
                'TOTAL PAGADO (Bs)' => $participante['total_pagado'],
                'SALDO DEUDA (Bs)' => $participante['saldo'],
                '% PAGADO' => $participante['porcentaje_pagado']
            ];
        });
    }

    public function headings(): array
    {
        // Encabezado principal
        return [
            ['REPORTE DE ESTADO FINANCIERO DE PARTICIPANTES'],
            ['Sede: ' . $this->sede . ' | Sucursal: ' . $this->sucursal . ' | Oferta: ' . $this->oferta],
            ['Fecha de generación: ' . now()->format('d/m/Y H:i')],
            [''], // Línea en blanco
            [
                '#',
                'APELLIDO PATERNO',
                'APELLIDO MATERNO',
                'NOMBRES',
                'CARNET',
                'PLAN DE PAGO',
                'VENDEDOR',
                // NUEVAS COLUMNAS
                'FECHA INSCRIPCIÓN',
                'PROFESIÓN',
                'CELULAR',
                'CORREO',
                // FIN NUEVAS COLUMNAS
                'TOTAL PLAN (Bs)',
                'MATRÍCULA TOTAL (Bs)',
                'MATRÍCULA PAGADO (Bs)',
                'MATRÍCULA PENDIENTE (Bs)',
                'MATRÍCULA N° CUOTAS',
                'COLEGIATURA TOTAL (Bs)',
                'COLEGIATURA PAGADO (Bs)',
                'COLEGIATURA PENDIENTE (Bs)',
                'COLEGIATURA N° CUOTAS',
                'CERTIFICACIÓN TOTAL (Bs)',
                'CERTIFICACIÓN PAGADO (Bs)',
                'CERTIFICACIÓN PENDIENTE (Bs)',
                'CERTIFICACIÓN N° CUOTAS',
                'TOTAL PAGADO (Bs)',
                'SALDO DEUDA (Bs)',
                '% PAGADO'
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Configurar anchos de columna para las nuevas columnas
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(25);

        // Ajustar rangos para las nuevas columnas (ahora hasta AA)
        $sheet->getStyle('A1:AA1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '2C3E50']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'ECF0F1']
            ]
        ]);

        $sheet->getStyle('A2:AA2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => '34495E']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        $sheet->getStyle('A3:AA3')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 10,
                'color' => ['rgb' => '7F8C8D']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        $sheet->getStyle('A5:AA5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2C3E50']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '34495E']
                ]
            ]
        ]);

        // Combinar celdas del título
        $sheet->mergeCells('A1:AA1');
        $sheet->mergeCells('A2:AA2');
        $sheet->mergeCells('A3:AA3');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 4,   // #
            'B' => 15,  // APELLIDO PATERNO
            'C' => 15,  // APELLIDO MATERNO
            'D' => 20,  // NOMBRES
            'E' => 15,  // CARNET
            'F' => 20,  // PLAN DE PAGO
            'G' => 20,  // VENDEDOR
            // NUEVAS COLUMNAS
            'H' => 15,  // FECHA INSCRIPCIÓN
            'I' => 20,  // PROFESIÓN
            'J' => 15,  // CELULAR
            'K' => 25,  // CORREO
            // FIN NUEVAS COLUMNAS
            'L' => 15,  // TOTAL PLAN

            'M' => 18,  // MATRÍCULA TOTAL
            'N' => 18,  // MATRÍCULA PAGADO
            'O' => 18,  // MATRÍCULA PENDIENTE
            'P' => 15,  // MATRÍCULA CUOTAS

            'Q' => 18,  // COLEGIATURA TOTAL
            'R' => 18,  // COLEGIATURA PAGADO
            'S' => 18,  // COLEGIATURA PENDIENTE
            'T' => 15,  // COLEGIATURA CUOTAS

            'U' => 18,  // CERTIFICACIÓN TOTAL
            'V' => 18,  // CERTIFICACIÓN PAGADO
            'W' => 18,  // CERTIFICACIÓN PENDIENTE
            'X' => 15,  // CERTIFICACIÓN CUOTAS

            'Y' => 18,  // TOTAL PAGADO
            'Z' => 18,  // SALDO DEUDA
            'AA' => 12, // % PAGADO
        ];
    }

    public function title(): string
    {
        return 'Estado Financiero';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Agregar números de fila automáticamente
                $totalRows = count($this->participantes) + 5;

                for ($i = 6; $i <= $totalRows; $i++) {
                    $sheet->setCellValue("A{$i}", $i - 5);
                }

                // Aplicar estilos a los datos
                $dataStartRow = 6;
                $dataEndRow = $totalRows;

                // Bordes para todas las celdas de datos
                $sheet->getStyle("A{$dataStartRow}:AA{$dataEndRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'BDC3C7']
                        ]
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // Formato de moneda para columnas numéricas
                $currencyColumns = ['L', 'M', 'N', 'O', 'Q', 'R', 'S', 'U', 'V', 'W', 'Y', 'Z'];
                foreach ($currencyColumns as $col) {
                    $sheet->getStyle("{$col}{$dataStartRow}:{$col}{$dataEndRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.00');
                }

                // Formato de porcentaje para columna AA
                $sheet->getStyle("AA{$dataStartRow}:AA{$dataEndRow}")
                    ->getNumberFormat()
                    ->setFormatCode('0.00%');

                // COLORES POR CONCEPTO (actualizado para nuevas columnas)

                // Información personal - Azul claro (columnas B-K)
                $sheet->getStyle("B{$dataStartRow}:K{$dataEndRow}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E8F4FD']
                    ]
                ]);

                // Matrícula - Azul más oscuro
                $sheet->getStyle("M{$dataStartRow}:P{$dataEndRow}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D6EAF8']
                    ]
                ]);

                // Colegiatura - Verde claro
                $sheet->getStyle("Q{$dataStartRow}:T{$dataEndRow}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D5F4E6']
                    ]
                ]);

                // Certificación - Amarillo claro
                $sheet->getStyle("U{$dataStartRow}:X{$dataEndRow}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEF9E7']
                    ]
                ]);

                // COLORES POR ESTADO DE PAGO

                // Pagado - Verde
                $pagadoColumns = ['N', 'R', 'V', 'Y'];
                foreach ($pagadoColumns as $col) {
                    $sheet->getStyle("{$col}{$dataStartRow}:{$col}{$dataEndRow}")->applyFromArray([
                        'font' => [
                            'color' => ['rgb' => '27AE60']
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'D5F4E6']
                        ]
                    ]);
                }

                // Pendiente - Rojo
                $pendienteColumns = ['O', 'S', 'W', 'Z'];
                foreach ($pendienteColumns as $col) {
                    $sheet->getStyle("{$col}{$dataStartRow}:{$col}{$dataEndRow}")->applyFromArray([
                        'font' => [
                            'color' => ['rgb' => 'E74C3C']
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FDEDEC']
                        ]
                    ]);
                }

                // COLOR POR PORCENTAJE DE PAGO
                $porcentajeCol = 'AA';
                for ($row = $dataStartRow; $row <= $dataEndRow; $row++) {
                    $value = $sheet->getCell("{$porcentajeCol}{$row}")->getValue() * 100; // Convertir a porcentaje

                    if ($value >= 100) {
                        $color = '27AE60'; // Verde
                        $bgColor = 'D5F4E6';
                    } elseif ($value >= 80) {
                        $color = '2ECC71'; // Verde claro
                        $bgColor = 'E8F6F3';
                    } elseif ($value >= 60) {
                        $color = 'F39C12'; // Amarillo
                        $bgColor = 'FEF9E7';
                    } elseif ($value >= 40) {
                        $color = 'E67E22'; // Naranja
                        $bgColor = 'FBEEE6';
                    } else {
                        $color = 'E74C3C'; // Rojo
                        $bgColor = 'FDEDEC';
                    }

                    $sheet->getStyle("{$porcentajeCol}{$row}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => $color]
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $bgColor]
                        ]
                    ]);
                }

                // Agregar totales al final
                $totalRow = $dataEndRow + 2;

                // Fila de totales
                $sheet->setCellValue("B{$totalRow}", "TOTALES GENERALES");
                $sheet->getStyle("B{$totalRow}")->getFont()->setBold(true);

                // Calcular totales
                $totales = [
                    'total_plan' => 0,
                    'matricula_total' => 0,
                    'matricula_pagado' => 0,
                    'matricula_pendiente' => 0,
                    'colegiatura_total' => 0,
                    'colegiatura_pagado' => 0,
                    'colegiatura_pendiente' => 0,
                    'certificacion_total' => 0,
                    'certificacion_pagado' => 0,
                    'certificacion_pendiente' => 0,
                    'total_pagado' => 0,
                    'saldo_deuda' => 0,
                ];

                foreach ($this->participantes as $participante) {
                    $totales['total_plan'] += $participante['total_plan'];
                    $totales['matricula_total'] += $participante['conceptos']['Matrícula']['total'] ?? 0;
                    $totales['matricula_pagado'] += $participante['conceptos']['Matrícula']['pagado'] ?? 0;
                    $totales['matricula_pendiente'] += $participante['conceptos']['Matrícula']['pendiente'] ?? 0;
                    $totales['colegiatura_total'] += $participante['conceptos']['Colegiatura']['total'] ?? 0;
                    $totales['colegiatura_pagado'] += $participante['conceptos']['Colegiatura']['pagado'] ?? 0;
                    $totales['colegiatura_pendiente'] += $participante['conceptos']['Colegiatura']['pendiente'] ?? 0;
                    $totales['certificacion_total'] += $participante['conceptos']['Certificación']['total'] ?? 0;
                    $totales['certificacion_pagado'] += $participante['conceptos']['Certificación']['pagado'] ?? 0;
                    $totales['certificacion_pendiente'] += $participante['conceptos']['Certificación']['pendiente'] ?? 0;
                    $totales['total_pagado'] += $participante['total_pagado'];
                    $totales['saldo_deuda'] += $participante['saldo'];
                }

                // Escribir totales (ajustar columnas)
                $sheet->setCellValue("L{$totalRow}", $totales['total_plan']);
                $sheet->setCellValue("M{$totalRow}", $totales['matricula_total']);
                $sheet->setCellValue("N{$totalRow}", $totales['matricula_pagado']);
                $sheet->setCellValue("O{$totalRow}", $totales['matricula_pendiente']);
                $sheet->setCellValue("Q{$totalRow}", $totales['colegiatura_total']);
                $sheet->setCellValue("R{$totalRow}", $totales['colegiatura_pagado']);
                $sheet->setCellValue("S{$totalRow}", $totales['colegiatura_pendiente']);
                $sheet->setCellValue("U{$totalRow}", $totales['certificacion_total']);
                $sheet->setCellValue("V{$totalRow}", $totales['certificacion_pagado']);
                $sheet->setCellValue("W{$totalRow}", $totales['certificacion_pendiente']);
                $sheet->setCellValue("Y{$totalRow}", $totales['total_pagado']);
                $sheet->setCellValue("Z{$totalRow}", $totales['saldo_deuda']);

                // Calcular porcentaje general
                $porcentajeGeneral = $totales['total_plan'] > 0 ?
                    ($totales['total_pagado'] / $totales['total_plan']) * 100 : 0;
                $sheet->setCellValue("AA{$totalRow}", $porcentajeGeneral / 100);

                // Estilo para la fila de totales
                $sheet->getStyle("B{$totalRow}:AA{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2C3E50']
                    ],
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '34495E']
                        ]
                    ]
                ]);

                // Formato de moneda para totales
                $sheet->getStyle("L{$totalRow}:Z{$totalRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');
                $sheet->getStyle("AA{$totalRow}")
                    ->getNumberFormat()
                    ->setFormatCode('0.00%');

                // Congelar paneles (fijar encabezados)
                $sheet->freezePane('A6');
            }
        ];
    }
}
