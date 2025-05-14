<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BillingSummaryReportExport implements FromCollection,WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    /**
        /**
     * @return Collection
     */
    
        use Exportable;
    
        public $from_date;
        public $to_date;
        public $srchTerm;
    
        function __construct($from_date,$to_date,$srchTerm){
                $this->from_date = $from_date; 
                $this->to_date = $to_date;
                $this->srchTerm = $srchTerm;
        }
    
        public function collection()
        {
            $srchTerm = $this->srchTerm;

            $data = DB::table('hadmlog')
            ->Join('hperson', 'hadmlog.hpercode', '=', 'hperson.hpercode')
            ->Join('hphcont', 'hadmlog.enccode', '=', 'hphcont.enccode')
            ->leftJoin('hpatcon', 'hadmlog.enccode', '=', 'hpatcon.enccode')
            ->leftJoin('hpatcon1', 'hadmlog.enccode', '=', 'hpatcon1.enccode')
            ->leftJoin(DB::raw('(SELECT enccode, SUM(pcchrgamt) AS MI FROM hpatchrg WHERE chargcode = "MISC" GROUP BY enccode) AS M'), 'M.enccode', '=', 'hadmlog.enccode')
            ->leftJoin(DB::raw('(SELECT hrqd.enccode, SUM(hrqd.pcchrgamt) as SUP FROM hrqd GROUP BY hrqd.enccode) AS S'), 'S.enccode', '=', 'hadmlog.enccode')
            ->leftJoin(DB::raw('(SELECT hdocord.enccode, IFNULL(SUM(hdocord.pcchrgamt),0) AS rad FROM hdocord WHERE hdocord.pcchrgcod LIKE "r%" GROUP BY hdocord.enccode) as R'), 'R.enccode', '=', 'hadmlog.enccode')
            ->leftJoin(DB::raw('(SELECT hdocord.enccode, IFNULL(SUM(hdocord.pcchrgamt),0) AS lab FROM hdocord WHERE hdocord.pcchrgcod LIKE "l%" GROUP BY hdocord.enccode) as L'), 'L.enccode', '=', 'hadmlog.enccode')
            
            ->select(['hadmlog.hpercode AS HospitalCode'
            ,DB::raw('CONCAT(hperson.patlast, ", ",hperson.patfirst," ",hperson.patmiddle) as PatientName')
            ,DB::raw("DATE_FORMAT(hadmlog.admdate, '%m-%d-%Y') AS Admission")
            ,DB::raw("DATE_FORMAT(hadmlog.disdate, '%m-%d-%Y') AS Discharge")
            ,DB::raw('IFNULL(hphcont.totchrm, 0) as RoomBoard')
            ,DB::raw('IFNULL(hphcont.totchdm, 0) as Medicines')
            ,DB::raw('IFNULL(M.MI, 0) as Miscellaneous')
            ,DB::raw('IFNULL(S.SUP, 0) as Supplies')
            ,DB::raw('IFNULL(R.RAD,0) as Radiology')
            ,DB::raw('IFNULL(L.LAB,0) as Laboratory')
            ,DB::raw('IFNULL(hpatcon.nbb,"") as nbb')
            ,DB::raw('IFNULL(hpatcon1.philhealthbenehci, 0) as philhealthbenehci')
            ,DB::raw('IFNULL(hpatcon1.ptotalactualchargeshci, 0) as ptotalactualchargeshci')
            ,DB::raw('IFNULL(hpatcon1.pdiscounthci, 0) as pdiscounthci')
            ,DB::raw('IFNULL(hpatcon1.ptotalactualchargespf, 0) as ptotalactualchargespf')
            ,DB::raw('IFNULL(hpatcon1.pdiscountpf, 0) as pdiscountpf')
            ,DB::raw('IFNULL(hpatcon1.philhealthbenepf, 0) as philhealthbenepf')
            ,DB::raw('IFNULL(hpatcon1.session1,0) as session1')
            ,DB::raw('IFNULL(hpatcon1.session2,0) as session2')
            ,DB::raw('IFNULL(hpatcon1.firstcase,"") as firstcase')
            ,DB::raw('IFNULL(hpatcon1.amt1, 0) as amt1')
            ,DB::raw('IFNULL(hpatcon1.secondcase,"") as secondcase')
            ,DB::raw('IFNULL(hpatcon1.amt2,0) as amt2')
            ])
            ->whereRaw("DATE_FORMAT(hadmlog.admdate, '%Y-%m-%d') BETWEEN '" .$this->from_date. "' AND '" .$this->to_date. "'")
            ->where(function($query) use ($srchTerm){
                $query->where(DB::raw('CONCAT(hperson.patlast, ", ",hperson.patfirst," ",hperson.patmiddle)'), 'LIKE', '%'.$srchTerm.'%')
                ->orWhere('hadmlog.hpercode', 'like', '%'.$srchTerm.'%');
            })
            ->where('hadmlog.dispcode', '=', 'disch')
            ->get();
            
            return $data;
            // echo $fromDate;
            // dd($data);
            
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
  
    public function styles(Worksheet $sheet){
        $sheet->getStyle('1')->getFont()->setBold(true);
    }
}
