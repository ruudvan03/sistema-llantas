<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class InventarioExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithEvents
{
    protected $productos;

    public function __construct(Collection $productos)
    {
        $this->productos = $productos;
    }

    /**
     * Los datos. Empiezan en la fila 4 (dejamos 1-3 para el título).
     */
    public function collection()
    {
        return $this->productos->map(function ($p) {
            return [
                'marca'   => $p->marca,
                'medida'  => $p->medida,
                'desc'    => $p->descripcion,
                'stock'   => (int) ($p->stock_cantidad ?? 0),
                'precio'  => (float) $p->precio_publico,
            ];
        });
    }

    /**
     * Encabezados de columna.
     */
    public function headings(): array
    {
        return ['MARCA', 'MEDIDA', 'DESCRIPCIÓN', 'STOCK', 'PRECIO PÚBLICO'];
    }

    public function title(): string
    {
        return 'Inventario';
    }

    /**
     * Ancho de cada columna.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 28,
            'B' => 18,
            'C' => 45,
            'D' => 12,
            'E' => 18,
        ];
    }

    /**
     * Estilo de la fila de encabezados (la que tiene MARCA, MEDIDA...).
     */
    public function styles(Worksheet $sheet)
    {
        // Maatwebsite pone los headings en la fila 1 por defecto,
        // pero como insertamos el título arriba (evento), los movemos.
        return [];
    }

    /**
     * Evento que se dispara después de generar la hoja:
     * aquí insertamos el título de empresa y damos formato.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $totalFilas = $this->productos->count() + 3; // +3 por las filas de título y encabezado

                // 1. Insertar 2 filas arriba para el título de la empresa
                $sheet->insertNewRowBefore(1, 2);

                // 2. Título de empresa (fila 1)
                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', 'LLANTAS ECONÓMICAS CHALCO');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setRGB('FFFFFF');
                $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('0F0F0F');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // 3. Subtítulo (fila 2)
                $sheet->mergeCells('A2:E2');
                $sheet->setCellValue('A2', 'Reporte de Inventario — ' . now()->format('d/m/Y H:i'));
                $sheet->getStyle('A2')->getFont()->setSize(10)->getColor()->setRGB('FFFFFF');
                $sheet->getStyle('A2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D32030');
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getRowDimension(2)->setRowHeight(20);

                // 4. Fila de encabezados de columna (ahora es la fila 3)
                $sheet->getStyle('A3:E3')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                $sheet->getStyle('A3:E3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1A1A1A');
                $sheet->getStyle('A3:E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension(3)->setRowHeight(22);

                // 5. Bordes en toda la tabla de datos
                $ultimaFila = $this->productos->count() + 3;
                $rango = 'A3:E' . $ultimaFila;
                $sheet->getStyle($rango)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('DDDDDD');

                // 6. Formato de moneda en la columna de precio (E)
                $sheet->getStyle('E4:E' . $ultimaFila)->getNumberFormat()->setFormatCode('"$"#,##0.00');

                // 7. Centrar la columna de stock (D)
                $sheet->getStyle('D4:D' . $ultimaFila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // 8. Filas alternadas (gris claro) para legibilidad
                for ($i = 4; $i <= $ultimaFila; $i++) {
                    if ($i % 2 == 0) {
                        $sheet->getStyle('A' . $i . ':E' . $i)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F7F7F7');
                    }
                }
            },
        ];
    }
}