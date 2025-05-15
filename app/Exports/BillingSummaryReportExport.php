<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BillingSummaryReportExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithStyles
{

    protected $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function collection()
    {
        return $this->collection;
    }

    public function headings(): array
    {
        return [
            'Hospital No.',
            'Patient Name',
            'Admission',
            'Discharge',
            'Room Board',
            'Medicines',
            'Miscellaneous',
            'Supplies',
            'Radiology',
            'Laboratory',
            'NBB',
            'Philhealth Benefit HCI',
            'Total Actual Charges HCI',
            'Discount HCI',
            'Total Actual Charges PF',
            'Discount PF',
            'Philhealth Benefit PF',
            'Session 1',
            'Session 2',
            'First Case',
            'First Case Rate',
            'Second Case',
            'Second Case Rate',

        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'P' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'Q' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'R' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'S' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'U' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'W' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    public function map($row): array
    {
        return [
            $row->HospitalCode,
            $row->PatientName,
            $row->Admission,
            $row->Discharge,
            $row->RoomBoard,
            $row->Medicines,
            $row->Miscellaneous,
            $row->Supplies,
            $row->Radiology,
            $row->Laboratory,
            $row->nbb,
            $row->philhealthbenehci,
            $row->ptotalactualchargeshci,
            $row->pdiscounthci,
            $row->ptotalactualchargespf,
            $row->pdiscountpf,
            $row->philhealthbenepf,
            $row->session1,
            $row->session2,
            $row->firstcase,
            $row->amt1,
            $row->secondcase,
            $row->amt2,
        ];
    }


    public function styles(Worksheet $sheet)
    {
        // Bold first row (header)
        $sheet->getStyle('1')->getFont()->setBold(true);

        // Auto size columns A to S (or however many columns you have)
        foreach (range('A', 'S') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}
