<?php

namespace App\Http\Livewire\Reports;

use App\Exports\BillingSummaryReportExport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class BillingSummaryReport extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $searchTerm = '';
    public $fDate;
    public $tDate;
    protected $data;

    public $state = [];

    public function __construct()
    {
        $this->fDate = Carbon::now()->startOfMonth()->format('m-d-Y');
        $this->tDate = Carbon::now()->format('m-d-Y');
    }

    public function exportExcel()
    {
        $frDate = Carbon::createFromFormat('m-d-Y', $this->fDate)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('m-d-Y', $this->tDate)->format('Y-m-d');
        $srchTerm = $this->searchTerm;
        return (new BillingSummaryReportExport($frDate, $toDate, $srchTerm))
            ->download('Billing-Summary-Report(' . $this->fDate . ' to ' . $this->tDate . ').xlsx');
    }
    public function filter()
    {
        $this->fDate = $this->state['fdate'];
        $this->tDate = $this->state['tdate'];
    }


    public function loadData($frDate, $toDate, $srchTerm)
    {
        //  dd($frDate,$toDate);
        // $frDate = '11-01-2022';
        // $toDate = '11-30-2022';

        $frDate = Carbon::createFromFormat('m-d-Y', $frDate)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('m-d-Y', $toDate)->format('Y-m-d');


        $this->data = DB::table('hadmlog')
            ->Join('hperson', 'hadmlog.hpercode', '=', 'hperson.hpercode')
            ->Join('hphcont', 'hadmlog.enccode', '=', 'hphcont.enccode')
            ->leftJoin('hpatcon', 'hadmlog.enccode', '=', 'hpatcon.enccode')
            ->leftJoin('hpatcon1', 'hadmlog.enccode', '=', 'hpatcon1.enccode')
            ->leftJoin(DB::raw('(SELECT enccode, SUM(pcchrgamt) AS MI FROM hpatchrg WHERE chargcode = "MISC" GROUP BY enccode) AS M'), 'M.enccode', '=', 'hadmlog.enccode')
            ->leftJoin(DB::raw('(SELECT hrqd.enccode, SUM(hrqd.pcchrgamt) as SUP FROM hrqd GROUP BY hrqd.enccode) AS S'), 'S.enccode', '=', 'hadmlog.enccode')
            ->leftJoin(DB::raw('(SELECT hdocord.enccode, IFNULL(SUM(hdocord.pcchrgamt),0) AS rad FROM hdocord WHERE hdocord.pcchrgcod LIKE "r%" GROUP BY hdocord.enccode) as R'), 'R.enccode', '=', 'hadmlog.enccode')
            ->leftJoin(DB::raw('(SELECT hdocord.enccode, IFNULL(SUM(hdocord.pcchrgamt),0) AS lab FROM hdocord WHERE hdocord.pcchrgcod LIKE "l%" GROUP BY hdocord.enccode) as L'), 'L.enccode', '=', 'hadmlog.enccode')

            ->select([
                'hadmlog.hpercode AS HospitalCode'
                ,
                DB::raw('CONCAT(hperson.patlast, ", ",hperson.patfirst," ",hperson.patmiddle) as PatientName')
                ,
                DB::raw("DATE_FORMAT(hadmlog.admdate, '%m-%d-%Y') AS Admission")
                ,
                DB::raw("DATE_FORMAT(hadmlog.disdate, '%m-%d-%Y') AS Discharge")
                ,
                DB::raw('IFNULL(hphcont.totchrm, 0) as RoomBoard')
                ,
                DB::raw('IFNULL(hphcont.totchdm, 0) as Medicines')
                ,
                DB::raw('IFNULL(M.MI, 0) as Miscellaneous')
                ,
                DB::raw('IFNULL(S.SUP, 0) as Supplies')
                ,
                DB::raw('IFNULL(R.RAD,0) as Radiology')
                ,
                DB::raw('IFNULL(L.LAB,0) as Laboratory')
                ,
                DB::raw('IFNULL(hpatcon.nbb,"") as nbb')
                ,
                DB::raw('IFNULL(hpatcon1.philhealthbenehci, 0) as philhealthbenehci')
                ,
                DB::raw('IFNULL(hpatcon1.pdiscounthci, 0) as pdiscounthci')
                ,
                DB::raw('IFNULL(hpatcon1.ptotalactualchargeshci, 0) as ptotalactualchargeshci')
                ,
                DB::raw('IFNULL(hpatcon1.ptotalactualchargespf, 0) as ptotalactualchargespf')
                ,
                DB::raw('IFNULL(hpatcon1.pdiscountpf, 0) as pdiscountpf')
                ,
                DB::raw('IFNULL(hpatcon1.philhealthbenepf, 0) as philhealthbenepf')
                ,
                DB::raw('IFNULL(hpatcon1.session2,0) as session2')
                ,
                DB::raw('IFNULL(hpatcon1.session1,0) as session1')
                ,
                DB::raw('IFNULL(hpatcon1.firstcase,"") as firstcase')
                ,
                DB::raw('IFNULL(hpatcon1.amt1, 0) as amt1')
                ,
                DB::raw('IFNULL(hpatcon1.secondcase,"") as secondcase')
                ,
                DB::raw('IFNULL(hpatcon1.amt2,0) as amt2')
            ])
            ->whereRaw("DATE_FORMAT(hadmlog.admdate, '%Y-%m-%d') BETWEEN '" . $frDate . "' AND '" . $toDate . "'")
            ->where(function ($query) use ($srchTerm) {
                $query->where(DB::raw('CONCAT(hperson.patlast, ", ",hperson.patfirst," ",hperson.patmiddle)'), 'LIKE', '%' . $srchTerm . '%')
                    ->orWhere('hadmlog.hpercode', 'like', '%' . $srchTerm . '%');
            })
            ->where('hadmlog.dispcode', '=', 'disch')
            ->paginate(15);

    }

    public function render()
    {

        $frDate = $this->fDate;
        $toDate = $this->tDate;
        $srchTerm = $this->searchTerm;

        $this->loadData($frDate, $toDate, $srchTerm);
        // dd($this->data);

        // $data = collect($data)->whereBetween('pReceivedDate',['01-01-2021,01-02-2021']);
        // dd($frDate);
        // $this->data = collect($this->data)->paginate(15);
        return view(
            'livewire.reports.billing-summary-report',
            [
                'datas' => $this->data
            ]
        );
    }
}
