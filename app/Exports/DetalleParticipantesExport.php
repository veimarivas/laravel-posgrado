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

class DetalleParticipantesExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithTitle,
    WithEvents,
    ShouldAutoSize
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
                "#" => "", // Se llenará automáticamente
                "CARNÉ/CI" => $participante["carnet"],
                "EXPEDIDO" => $participante["expedido"] ?? "N/A",
                "APELLIDO PATERNO" => $participante["apellido_paterno"] ?? "",
                "APELLIDO MATERNO" => $participante["apellido_materno"] ?? "",
                "NOMBRES" => $participante["nombres"] ?? "",
                "CORREO ELECTRÓNICO" => $participante["correo"] ?? "Sin correo",
                "DIRECCIÓN" => $participante["direccion"] ?? "Sin dirección",
                "CELULAR" => $participante["celular"] ?? "Sin celular",
                "TELÉFONO" => $participante["telefono"] ?? "Sin teléfono",
                "CIUDAD" => $participante["ciudad"] ?? "No registrada",
                "DEPARTAMENTO" =>
                    $participante["departamento"] ?? "No registrado",
                "PROFESIÓN" => $participante["profesion"] ?? "No registrado",
                "GRADO ACADÉMICO" =>
                    $participante["grado_academico"] ?? "No registrado",
                "INSTITUCIÓN" =>
                    $participante["institucion"] ?? "No especificada",
                "N° DE ESTUDIOS" => count($participante["estudios"] ?? []),
                "ESTUDIANTE ID" => $participante["estudiante_id"] ?? "N/A",
            ];
        });
    }

    public function headings(): array
    {
        // Encabezado principal
        return [
            ["REPORTE DE DETALLE DE PARTICIPANTES"],
            [
                "Sede: " .
                $this->sede .
                " | Sucursal: " .
                $this->sucursal .
                " | Oferta: " .
                $this->oferta,
            ],
            ["Fecha de generación: " . now()->format("d/m/Y H:i")],
            [""], // Línea en blanco
            [
                "#",
                "CARNÉ/CI",
                "EXPEDIDO",
                "APELLIDO PATERNO",
                "APELLIDO MATERNO",
                "NOMBRES",
                "CORREO ELECTRÓNICO",
                "DIRECCIÓN",
                "CELULAR",
                "TELÉFONO",
                "CIUDAD",
                "DEPARTAMENTO",
                "PROFESIÓN",
                "GRADO ACADÉMICO",
                "INSTITUCIÓN",
                "N° DE ESTUDIOS",
                "ESTUDIANTE ID",
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Configurar anchos de columna
        $sheet->getColumnDimension("A")->setWidth(4);
        $sheet->getColumnDimension("B")->setWidth(15);
        $sheet->getColumnDimension("C")->setWidth(10);
        $sheet->getColumnDimension("D")->setWidth(20);
        $sheet->getColumnDimension("E")->setWidth(20);
        $sheet->getColumnDimension("F")->setWidth(25);
        $sheet->getColumnDimension("G")->setWidth(25);
        $sheet->getColumnDimension("H")->setWidth(30);
        $sheet->getColumnDimension("I")->setWidth(15);
        $sheet->getColumnDimension("J")->setWidth(15);
        $sheet->getColumnDimension("K")->setWidth(20);
        $sheet->getColumnDimension("L")->setWidth(20);
        $sheet->getColumnDimension("M")->setWidth(25);
        $sheet->getColumnDimension("N")->setWidth(20);
        $sheet->getColumnDimension("O")->setWidth(25);
        $sheet->getColumnDimension("P")->setWidth(15);
        $sheet->getColumnDimension("Q")->setWidth(12);

        // Estilo para el título principal (fila 1)
        $sheet->getStyle("A1:Q1")->applyFromArray([
            "font" => [
                "bold" => true,
                "size" => 16,
                "color" => ["rgb" => "2C3E50"],
            ],
            "alignment" => [
                "horizontal" => Alignment::HORIZONTAL_CENTER,
                "vertical" => Alignment::VERTICAL_CENTER,
            ],
            "fill" => [
                "fillType" => Fill::FILL_SOLID,
                "startColor" => ["rgb" => "ECF0F1"],
            ],
        ]);

        // Estilo para información de sede (fila 2)
        $sheet->getStyle("A2:Q2")->applyFromArray([
            "font" => [
                "bold" => true,
                "size" => 12,
                "color" => ["rgb" => "34495E"],
            ],
            "alignment" => [
                "horizontal" => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Estilo para fecha (fila 3)
        $sheet->getStyle("A3:Q3")->applyFromArray([
            "font" => [
                "italic" => true,
                "size" => 10,
                "color" => ["rgb" => "7F8C8D"],
            ],
            "alignment" => [
                "horizontal" => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Estilo para encabezados de columnas (fila 5)
        $sheet->getStyle("A5:Q5")->applyFromArray([
            "font" => [
                "bold" => true,
                "size" => 11,
                "color" => ["rgb" => "FFFFFF"],
            ],
            "alignment" => [
                "horizontal" => Alignment::HORIZONTAL_CENTER,
                "vertical" => Alignment::VERTICAL_CENTER,
                "wrapText" => true,
            ],
            "fill" => [
                "fillType" => Fill::FILL_SOLID,
                "startColor" => ["rgb" => "2C3E50"],
            ],
            "borders" => [
                "allBorders" => [
                    "borderStyle" => Border::BORDER_THIN,
                    "color" => ["rgb" => "34495E"],
                ],
            ],
        ]);

        // Combinar celdas del título
        $sheet->mergeCells("A1:Q1");
        $sheet->mergeCells("A2:Q2");
        $sheet->mergeCells("A3:Q3");
    }

    public function columnWidths(): array
    {
        return [
            "A" => 4, // #
            "B" => 15, // CARNÉ/CI
            "C" => 10, // EXPEDIDO
            "D" => 20, // APELLIDO PATERNO
            "E" => 20, // APELLIDO MATERNO
            "F" => 25, // NOMBRES
            "G" => 25, // CORREO
            "H" => 30, // DIRECCIÓN
            "I" => 15, // CELULAR
            "J" => 15, // TELÉFONO
            "K" => 20, // CIUDAD
            "L" => 20, // DEPARTAMENTO
            "M" => 25, // PROFESIÓN
            "N" => 20, // GRADO ACADÉMICO
            "O" => 25, // INSTITUCIÓN
            "P" => 15, // N° DE ESTUDIOS
            "Q" => 12, // ESTUDIANTE ID
        ];
    }

    public function title(): string
    {
        return "Detalle Participantes";
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Agregar números de fila automáticamente
                $totalRows = count($this->participantes) + 5; // +5 por las filas de encabezado

                for ($i = 6; $i <= $totalRows; $i++) {
                    $sheet->setCellValue("A{$i}", $i - 5);
                }

                // Aplicar estilos a los datos
                $dataStartRow = 6;
                $dataEndRow = $totalRows;

                // Bordes para todas las celdas de datos
                $sheet
                    ->getStyle("A{$dataStartRow}:Q{$dataEndRow}")
                    ->applyFromArray([
                        "borders" => [
                            "allBorders" => [
                                "borderStyle" => Border::BORDER_THIN,
                                "color" => ["rgb" => "BDC3C7"],
                            ],
                        ],
                        "alignment" => [
                            "vertical" => Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                // Alineación específica por columnas
                $sheet
                    ->getStyle("A{$dataStartRow}:A{$dataEndRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet
                    ->getStyle("B{$dataStartRow}:C{$dataEndRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet
                    ->getStyle("I{$dataStartRow}:J{$dataEndRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet
                    ->getStyle("P{$dataStartRow}:P{$dataEndRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet
                    ->getStyle("Q{$dataStartRow}:Q{$dataEndRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // COLORES POR GRUPO DE INFORMACIÓN

                // Información personal - Azul claro
                $sheet
                    ->getStyle("B{$dataStartRow}:F{$dataEndRow}")
                    ->applyFromArray([
                        "fill" => [
                            "fillType" => Fill::FILL_SOLID,
                            "startColor" => ["rgb" => "E8F4FD"],
                        ],
                    ]);

                // Contacto - Verde claro
                $sheet
                    ->getStyle("G{$dataStartRow}:J{$dataEndRow}")
                    ->applyFromArray([
                        "fill" => [
                            "fillType" => Fill::FILL_SOLID,
                            "startColor" => ["rgb" => "E8F6F3"],
                        ],
                    ]);

                // Ubicación - Amarillo claro
                $sheet
                    ->getStyle("K{$dataStartRow}:L{$dataEndRow}")
                    ->applyFromArray([
                        "fill" => [
                            "fillType" => Fill::FILL_SOLID,
                            "startColor" => ["rgb" => "FEF9E7"],
                        ],
                    ]);

                // Formación académica - Lila claro
                $sheet
                    ->getStyle("M{$dataStartRow}:O{$dataEndRow}")
                    ->applyFromArray([
                        "fill" => [
                            "fillType" => Fill::FILL_SOLID,
                            "startColor" => ["rgb" => "F4ECF7"],
                        ],
                    ]);

                // Estadísticas - Gris claro
                $sheet
                    ->getStyle("P{$dataStartRow}:Q{$dataEndRow}")
                    ->applyFromArray([
                        "fill" => [
                            "fillType" => Fill::FILL_SOLID,
                            "startColor" => ["rgb" => "F8F9F9"],
                        ],
                    ]);

                // COLORES ESPECIALES

                // Correos - Azul
                $sheet
                    ->getStyle("G{$dataStartRow}:G{$dataEndRow}")
                    ->applyFromArray([
                        "font" => [
                            "color" => ["rgb" => "2980B9"],
                        ],
                    ]);

                // Celulares/Teléfonos - Verde
                $sheet
                    ->getStyle("I{$dataStartRow}:J{$dataEndRow}")
                    ->applyFromArray([
                        "font" => [
                            "color" => ["rgb" => "27AE60"],
                        ],
                    ]);

                // Ciudad/Departamento - Naranja
                $sheet
                    ->getStyle("K{$dataStartRow}:L{$dataEndRow}")
                    ->applyFromArray([
                        "font" => [
                            "color" => ["rgb" => "E67E22"],
                        ],
                    ]);

                // PROFESIÓN - COLOR POR TIPO (ejemplo)
                $profesionCol = "M";
                for ($row = $dataStartRow; $row <= $dataEndRow; $row++) {
                    $profesion = $sheet
                        ->getCell("{$profesionCol}{$row}")
                        ->getValue();

                    // Mapeo de colores por tipo de profesión (puedes ajustar esto)
                    if (
                        strpos(strtolower($profesion), "ingeniero") !== false ||
                        strpos(strtolower($profesion), "ingeniería") !== false
                    ) {
                        $color = "3498DB"; // Azul para ingenieros
                        $bgColor = "EBF5FB";
                    } elseif (
                        strpos(strtolower($profesion), "licenciado") !==
                            false ||
                        strpos(strtolower($profesion), "licenciatura") !== false
                    ) {
                        $color = "2ECC71"; // Verde para licenciados
                        $bgColor = "EAF8F1";
                    } elseif (
                        strpos(strtolower($profesion), "máster") !== false ||
                        strpos(strtolower($profesion), "master") !== false
                    ) {
                        $color = "9B59B6"; // Púrpura para máster
                        $bgColor = "F4ECF7";
                    } elseif (
                        strpos(strtolower($profesion), "doctor") !== false
                    ) {
                        $color = "E74C3C"; // Rojo para doctores
                        $bgColor = "FDEDEC";
                    } else {
                        $color = "34495E"; // Gris oscuro por defecto
                        $bgColor = "F8F9F9";
                    }

                    $sheet->getStyle("{$profesionCol}{$row}")->applyFromArray([
                        "font" => [
                            "bold" => true,
                            "color" => ["rgb" => $color],
                        ],
                        "fill" => [
                            "fillType" => Fill::FILL_SOLID,
                            "startColor" => ["rgb" => $bgColor],
                        ],
                    ]);
                }

                // COLOR POR NÚMERO DE ESTUDIOS
                $estudiosCol = "P";
                for ($row = $dataStartRow; $row <= $dataEndRow; $row++) {
                    $numEstudios = $sheet
                        ->getCell("{$estudiosCol}{$row}")
                        ->getValue();

                    if ($numEstudios >= 3) {
                        $color = "27AE60"; // Verde para 3+ estudios
                        $bgColor = "D5F4E6";
                    } elseif ($numEstudios == 2) {
                        $color = "F39C12"; // Amarillo para 2 estudios
                        $bgColor = "FEF9E7";
                    } elseif ($numEstudios == 1) {
                        $color = "3498DB"; // Azul para 1 estudio
                        $bgColor = "EBF5FB";
                    } else {
                        $color = "95A5A6"; // Gris para 0 estudios
                        $bgColor = "F8F9F9";
                    }

                    $sheet->getStyle("{$estudiosCol}{$row}")->applyFromArray([
                        "font" => [
                            "bold" => true,
                            "color" => ["rgb" => $color],
                        ],
                        "fill" => [
                            "fillType" => Fill::FILL_SOLID,
                            "startColor" => ["rgb" => $bgColor],
                        ],
                        "alignment" => [
                            "horizontal" => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                }

                // Agregar resumen estadístico al final
                $totalRow = $dataEndRow + 2;

                // Fila de totales
                $sheet->setCellValue("A{$totalRow}", "RESUMEN ESTADÍSTICO");
                $sheet
                    ->getStyle("A{$totalRow}")
                    ->getFont()
                    ->setBold(true)
                    ->setSize(12);
                $sheet->mergeCells("A{$totalRow}:Q{$totalRow}");

                // Estadísticas
                $totalParticipantes = count($this->participantes);
                $conCorreo = count(
                    array_filter($this->participantes, function ($p) {
                        return !empty($p["correo"]) &&
                            $p["correo"] !== "Sin correo";
                    }),
                );
                $conCelular = count(
                    array_filter($this->participantes, function ($p) {
                        return !empty($p["celular"]) &&
                            $p["celular"] !== "Sin celular";
                    }),
                );

                // Agrupar por departamento
                $departamentos = [];
                foreach ($this->participantes as $p) {
                    $depto = $p["departamento"] ?? "Sin departamento";
                    if (!isset($departamentos[$depto])) {
                        $departamentos[$depto] = 0;
                    }
                    $departamentos[$depto]++;
                }

                // Obtener departamento con más participantes
                arsort($departamentos);
                $topDepartamento = key($departamentos) ?: "N/A";
                $totalTopDepto = current($departamentos) ?: 0;

                // Filas de estadísticas
                $statsRow1 = $totalRow + 1;
                $statsRow2 = $totalRow + 2;
                $statsRow3 = $totalRow + 3;
                $statsRow4 = $totalRow + 4;

                $sheet->setCellValue(
                    "A{$statsRow1}",
                    "Total de participantes:",
                );
                $sheet->setCellValue("B{$statsRow1}", $totalParticipantes);

                $sheet->setCellValue("D{$statsRow1}", "Con correo registrado:");
                $sheet->setCellValue("E{$statsRow1}", $conCorreo);
                $sheet->setCellValue(
                    "F{$statsRow1}",
                    "(" .
                        round(($conCorreo / $totalParticipantes) * 100, 1) .
                        "%)",
                );

                $sheet->setCellValue(
                    "H{$statsRow1}",
                    "Con celular registrado:",
                );
                $sheet->setCellValue("I{$statsRow1}", $conCelular);
                $sheet->setCellValue(
                    "J{$statsRow1}",
                    "(" .
                        round(($conCelular / $totalParticipantes) * 100, 1) .
                        "%)",
                );

                $sheet->setCellValue(
                    "A{$statsRow2}",
                    "Departamento principal:",
                );
                $sheet->setCellValue("B{$statsRow2}", $topDepartamento);
                $sheet->setCellValue(
                    "C{$statsRow2}",
                    $totalTopDepto . " participantes",
                );

                $sheet->setCellValue("E{$statsRow2}", "Total departamentos:");
                $sheet->setCellValue("F{$statsRow2}", count($departamentos));

                // Promedio de estudios por participante
                $totalEstudios = array_sum(
                    array_map(function ($p) {
                        return count($p["estudios"] ?? []);
                    }, $this->participantes),
                );
                $promedioEstudios =
                    $totalParticipantes > 0
                        ? $totalEstudios / $totalParticipantes
                        : 0;

                $sheet->setCellValue(
                    "A{$statsRow3}",
                    "Total estudios registrados:",
                );
                $sheet->setCellValue("B{$statsRow3}", $totalEstudios);

                $sheet->setCellValue(
                    "D{$statsRow3}",
                    "Promedio de estudios por participante:",
                );
                $sheet->setCellValue(
                    "E{$statsRow3}",
                    round($promedioEstudios, 1),
                );

                // Profesiones más comunes
                $profesiones = [];
                foreach ($this->participantes as $p) {
                    $prof = $p["profesion"] ?? "No registrado";
                    if (!isset($profesiones[$prof])) {
                        $profesiones[$prof] = 0;
                    }
                    $profesiones[$prof]++;
                }
                arsort($profesiones);
                $topProfesion = key($profesiones) ?: "N/A";
                $totalTopProf = current($profesiones) ?: 0;

                $sheet->setCellValue("A{$statsRow4}", "Profesión más común:");
                $sheet->setCellValue("B{$statsRow4}", $topProfesion);
                $sheet->setCellValue(
                    "C{$statsRow4}",
                    $totalTopProf . " participantes",
                );

                // Estilo para el resumen
                $resumenStyle = [
                    "font" => ["bold" => true],
                    "fill" => [
                        "fillType" => Fill::FILL_SOLID,
                        "startColor" => ["rgb" => "F8F9F9"],
                    ],
                    "borders" => [
                        "allBorders" => [
                            "borderStyle" => Border::BORDER_THIN,
                            "color" => ["rgb" => "BDC3C7"],
                        ],
                    ],
                ];

                $sheet->getStyle("A{$totalRow}:Q{$totalRow}")->applyFromArray([
                    "fill" => [
                        "fillType" => Fill::FILL_SOLID,
                        "startColor" => ["rgb" => "2C3E50"],
                    ],
                    "font" => [
                        "color" => ["rgb" => "FFFFFF"],
                        "bold" => true,
                        "size" => 12,
                    ],
                    "alignment" => [
                        "horizontal" => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $sheet
                    ->getStyle("A{$statsRow1}:Q{$statsRow4}")
                    ->applyFromArray($resumenStyle);

                // Ajustar altura de filas para estadísticas
                $sheet->getRowDimension($totalRow)->setRowHeight(30);

                // Congelar paneles (fijar encabezados)
                $sheet->freezePane("A6");
            },
        ];
    }
}
