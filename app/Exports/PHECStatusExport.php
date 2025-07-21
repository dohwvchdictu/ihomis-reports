<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;



class PHECStatusExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithStyles
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
            'Claim Series LHIO',
            'Patient Lastname',
            'Patient Firstname',
            'Patient Middlename',
            'Member Lastname',
            'Member Firstname',
            'Member Middlename',
            'PHIC Number',
            'Admission Date',
            'Discharge Date',
            'Receipt Ticket Number',
            'Claim Number',
            'Received Date',
            'Status',
            'First Case Code',
            'Doctor Fee',
            'Hospital Fee',
            'Total Claim Amount Paid',
            'Claim Date Refile',
        ];
    }
    public function map($row): array
    {
        // Assuming $row->PatientName like " Lastname, Firstname Middlename "
        // and $row->MemberName like " Lastname, Firstname Middlename "
        // We'll split these into parts.

        // Helper function to split name: " Lastname, Firstname Middlename "
        $splitName = function ($fullName) {
            $fullName = trim($fullName);
            $lastname = $firstname = $middlename = '';

            if (str_contains($fullName, ',')) {
                [$lastname, $rest] = explode(',', $fullName, 2);
                $lastname = trim($lastname);

                // Split first and middle names
                $parts = preg_split('/\s+/', trim($rest));
                $firstname = $parts[0] ?? '';
                $middlename = $parts[1] ?? '';
            } else {
                // fallback if format unexpected
                $parts = preg_split('/\s+/', $fullName);
                $lastname = $parts[0] ?? '';
                $firstname = $parts[1] ?? '';
                $middlename = $parts[2] ?? '';
            }

            return [$lastname, $firstname, $middlename];
        };

        [$pLast, $pFirst, $pMid] = $splitName($row->PatientName);
        [$mLast, $mFirst, $mMid] = $splitName($row->MemberName);

        return [
            $row->Pclaimserieslhio,
            $pLast,
            $pFirst,
            $pMid,
            $mLast,
            $mFirst,
            $mMid,
            $row->memphicnum,
            $row->pAdmissionDate,
            $row->pDischargeDate,
            $row->pReceiptTicketNumber,
            $row->pClaimNumber,
            $row->preceiveddate,
            $row->pStatus,
            $row->firstcasecode,
            $row->phicdocfee,
            $row->phichospfee,
            $row->pTotalClaimAmountPaid,
            $row->pclaimdaterefile,
        ];
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
            'K' => NumberFormat::FORMAT_NUMBER,
            'L' => NumberFormat::FORMAT_NUMBER,
            'P' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'Q' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'R' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bold first row (headers)
        $sheet->getStyle('1')->getFont()->setBold(true);

        // Auto-size columns A to S
        foreach (range('A', 'S') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set alignment
        $alignmentCenter = ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]];
        $alignmentRight = ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]];
        $alignmentLeft = ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]];

        // Centered columns (adjust as needed)
        $sheet->getStyle('A')->applyFromArray($alignmentCenter); // Claim Series LHIO
        $sheet->getStyle('H')->applyFromArray($alignmentCenter); // PHIC Number
        $sheet->getStyle('I')->applyFromArray($alignmentCenter); // Admission Date
        $sheet->getStyle('J')->applyFromArray($alignmentCenter);
        $sheet->getStyle('L')->applyFromArray($alignmentLeft); // Discharge Date
        $sheet->getStyle('M')->applyFromArray($alignmentCenter); // Received Date
        $sheet->getStyle('N')->applyFromArray($alignmentCenter); // Status
        $sheet->getStyle('O')->applyFromArray($alignmentCenter); // First Case Code
        $sheet->getStyle('S')->applyFromArray($alignmentCenter); // Claim Date Refile

        // Right-aligned columns for numeric money
        $sheet->getStyle('P')->applyFromArray($alignmentRight); // Doctor Fee
        $sheet->getStyle('Q')->applyFromArray($alignmentRight); // Hospital Fee
        $sheet->getStyle('R')->applyFromArray($alignmentRight); // Total Claim Amount Paid
    }

}

