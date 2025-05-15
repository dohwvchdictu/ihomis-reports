<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
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
        // Bold first row (header)
        $sheet->getStyle('1')->getFont()->setBold(true);

        // Auto size columns A to S (or however many columns you have)
        foreach (range('A', 'S') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}

