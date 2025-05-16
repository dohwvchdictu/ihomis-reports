<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NoOfPatientsAndConsultationsExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Department',
            'No of Patients Encoded',
            'No of Consultations Encoded',
        ];
    }
    public function columnFormats(): array
    {
        return [
            'B' => '#,##0',
            'C' => '#,##0',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Auto size columns A to S
        foreach (range('A', 'S') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Apply styles to headers and first column
        return [
            1 => [ // First row (header)
                'font' => ['bold' => true],
                'alignment' => ['wrapText' => true, 'horizontal' => 'center'],
            ],
            'A' => ['font' => ['bold' => true]], // First column
        ];
    }

}
