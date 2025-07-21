<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TelemedicineMasterlistExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
            'Patient Name',
            'Date of Consultation',
            'Age',
            'Type of Service',
            'Chief Complaint',
            'Clinical Diagnosis',
            'Final Diagnosis',
            'Attending Provider',
        ];
    }

    public function map($row): array
    {
        return [
            $row->PatientName,
            $row->DateofConsultation,
            $row->Age,
            $row->TypeofService,
            $row->ChiefComplaint,
            $row->ClinicalDiagnosis,
            $row->FinalDiagnosis,
            $row->AttendingProvider,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setBold(true);

        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}
