<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class PHECStatusExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    /**
     * @return Collection
     */

    use Exportable;

    public $from_date;
    public $to_date;
    public $search;
    public $type;
    public $status;

    function __construct($from_date, $to_date, $search, $type, $status)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->search = $search;
        $this->type = $type;
        $this->status = $status;
    }

    public function collection()
    {
        $frDate = $this->from_date;
        $toDate = $this->to_date;
        $search = $this->search;
        $type = $this->type;
        $status = $this->status;

        $baseQuery = function ($table) use ($frDate, $toDate, $status, $search) {
            return DB::table($table)
                ->leftJoin('hphicclaimmap', "{$table}.enccode", '=', 'hphicclaimmap.enccode')
                ->leftJoin('hpatcon', "{$table}.enccode", '=', 'hpatcon.enccode')
                ->leftJoin('hpatcon1', "{$table}.enccode", '=', 'hpatcon1.enccode')
                ->leftJoin('hphicclaimtransmittal', "{$table}.enccode", '=', 'hphicclaimtransmittal.enccode')
                ->join('hperson', "{$table}.hpercode", '=', 'hperson.hpercode')
                ->leftJoin('hphiclog', 'hpatcon.memphicnum', '=', 'hphiclog.phicnum')
                ->select(
                    'hphicclaimmap.Pclaimserieslhio',
                    'hperson.patlast',
                    'hperson.patfirst',
                    'hperson.patmiddle',
                    'hphiclog.memlast',
                    'hphiclog.memfirst',
                    'hphiclog.memmid',
                    'hpatcon.memphicnum',
                    'hphicclaimmap.pAdmissionDate',
                    'hphicclaimmap.pDischargeDate',
                    'hphicclaimmap.pReceiptTicketNumber',
                    'hphicclaimmap.pClaimNumber',
                    'hphicclaimmap.preceiveddate',
                    'hphicclaimmap.pStatus',
                    DB::raw('(SELECT firstcase FROM hpatcon1 WHERE hpatcon1.enccode = hphicclaimmap.enccode) AS firstcasecode'),
                    DB::raw('(SELECT philhealthbenepf FROM hpatcon1 WHERE hpatcon1.enccode = hphicclaimmap.enccode) AS phicdocfee'),
                    DB::raw('(SELECT philhealthbenehci FROM hpatcon1 WHERE hpatcon1.enccode = hphicclaimmap.enccode) AS phichosfee'),
                    DB::raw('(SELECT IFNULL(pTotalClaimAmountPaid, 0) FROM hphicclaimstatus WHERE hphicclaimstatus.Pclaimserieslhio = hphicclaimmap.Pclaimserieslhio) AS pTotalClaimAmountPaid'),
                    DB::raw('(SELECT IFNULL(hphicclaimstatus.pclaimdaterefile, "") FROM hphicclaimstatus WHERE hphicclaimstatus.Pclaimserieslhio = hphicclaimmap.Pclaimserieslhio) AS pclaimdaterefile')
                )
                ->whereRaw("STR_TO_DATE(hphicclaimmap.pReceivedDate, '%m-%d-%Y') BETWEEN STR_TO_DATE(?, '%m-%d-%Y') AND STR_TO_DATE(?, '%m-%d-%Y')", [$frDate, $toDate])
                ->where(function ($query) use ($search) {
                    $query->where(DB::raw('CONCAT(hperson.patlast, ", ", hperson.patfirst, " ", hperson.patmiddle)'), 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw('CONCAT(hphiclog.memlast, ", ", hphiclog.memfirst, " ", hphiclog.memmid)'), 'LIKE', '%' . $search . '%')
                        ->orWhere('hpatcon.memphicnum', 'like', '%' . $search . '%');
                })
                ->where('hphicclaimmap.pStatus', 'like', '%' . $status . '%')
                ->orderBy('hperson.patlast');
        };

        // Apply pagination to the query directly instead of fetching all data
        if ($type == '0') {
            $this->data = $baseQuery('hadmlog')->union($baseQuery('hopdlog'))->orderBy('patlast')->get();
        } elseif ($type == '1') {
            $this->data = $baseQuery('hadmlog')->get();  // Paginate for ADM
        } elseif ($type == '2') {
            $this->data = $baseQuery('hopdlog')->get();  // Paginate for OPD
        }

        return $this->data;

    }

    public function headings(): array
    {
        return [
            'Claim Series No.',
            'Patient Lastname',
            'Patient Firstname',
            'Patient Middlename',
            'Member Lastname',
            'Member Firstname',
            'Member Middlename',
            'Member PIN',
            'Date of Admission',
            'Date of Discharge',
            'Receipt No.',
            'Claim No.',
            'Recieve Date',
            'Claim Status',
            'First Case Code',
            'Professiona Fee',
            'HCI Fee',
            'Total Claim Amount Paid',
            'Refile Date'

        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setBold(true);
    }
}

