<?php
/*   __________________________________________________
    |  Obfuscated by YAK Pro - Php Obfuscator  2.0.16  |
    |              on 2025-07-22 06:50:08              |
    |    GitHub: https://github.com/pk-fr/yakpro-po    |
    |__________________________________________________|
*/
 namespace App\Http\Livewire\Reports; use App\Exports\BillingSummaryReportExport; use Illuminate\Support\Carbon; use Illuminate\Support\Facades\DB; use Livewire\Component; use Livewire\WithPagination; use Maatwebsite\Excel\Facades\Excel; class BillingSummaryReport extends Component { use WithPagination; protected $paginationTheme = 'bootstrap'; public $search = ''; public $fDate; public $tDate; public $state = array(); protected $updatesQueryString = array('search', 'state'); public function mount() : void { $this->fDate = Carbon::today()->startOfMonth()->toDateString(); $this->tDate = Carbon::today()->toDateString(); $this->state['fdate'] = $this->fDate; $this->state['tdate'] = $this->tDate; } public function updating($property) { $this->resetPage(); } public function updatedState() : void { $this->fDate = $this->state['fdate'] ?? $this->fDate; $this->tDate = $this->state['tdate'] ?? $this->tDate; $this->resetPage(); } public function filter() : void { $this->updatedState(); } public function export() { $collection = $this->baseQuery()->get(); return Excel::download(new BillingSummaryReportExport($collection), "Billing-Summary-Report({$this->fDate}_to_{$this->tDate}).xlsx"); } protected function baseQuery() { return DB::table('hadmlog')->join('hperson', 'hperson.hpercode', '=', 'hadmlog.hpercode')->join('hphcont', 'hphcont.enccode', '=', 'hadmlog.enccode')->leftJoin('hpatcon', 'hpatcon.enccode', '=', 'hadmlog.enccode')->leftJoin('hpatcon1', 'hpatcon1.enccode', '=', 'hadmlog.enccode')->leftJoin(DB::raw('
        (SELECT hpatchrg.enccode, SUM(hpatchrg.pcchrgamt) AS MI
         FROM hpatchrg
         WHERE hpatchrg.chargcode = "MISC"
         GROUP BY hpatchrg.enccode) AS M
    '), 'M.enccode', '=', 'hadmlog.enccode')->leftJoin(DB::raw('
        (SELECT hrqd.enccode, SUM(hrqd.pcchrgamt) AS SUP
         FROM hrqd
         GROUP BY hrqd.enccode) AS S
    '), 'S.enccode', '=', 'hadmlog.enccode')->leftJoin(DB::raw('
        (SELECT hdocord.enccode, IFNULL(SUM(hdocord.pcchrgamt), 0) AS rad
         FROM hdocord
         WHERE hdocord.pcchrgcod LIKE "r%"
         GROUP BY hdocord.enccode) AS R
    '), 'R.enccode', '=', 'hadmlog.enccode')->leftJoin(DB::raw('
        (SELECT hdocord.enccode, IFNULL(SUM(hdocord.pcchrgamt), 0) AS lab
         FROM hdocord
         WHERE hdocord.pcchrgcod LIKE "l%"
         GROUP BY hdocord.enccode) AS L
    '), 'L.enccode', '=', 'hadmlog.enccode')->select(['hadmlog.hpercode AS HospitalCode', DB::raw("CONCAT(' ', hperson.patlast, ', ', hperson.patfirst, ' ', IFNULL(hperson.patmiddle, ''), ' ', IFNULL(hperson.patsuffix, '')) AS PatientName"), 'hadmlog.admdate AS Admission', 'hadmlog.disdate AS Discharge', 'hphcont.totchrm AS RoomBoard', 'hphcont.totchdm AS Medicines', DB::raw('M.MI AS Miscellaneous'), DB::raw('S.SUP AS Supplies'), DB::raw('R.rad AS Radiology'), DB::raw('L.lab AS Laboratory'), 'hpatcon.nbb', 'hpatcon1.philhealthbenehci', 'hpatcon1.pdiscounthci', 'hpatcon1.ptotalactualchargeshci', 'hpatcon1.ptotalactualchargespf', 'hpatcon1.pdiscountpf', 'hpatcon1.philhealthbenepf', 'hpatcon1.session2', 'hpatcon1.session1', 'hpatcon1.secondcase', 'hpatcon1.amt2', 'hpatcon1.firstcase', 'hpatcon1.amt1'])->whereBetween('hadmlog.admdate', [$this->fDate, $this->tDate])->where(function ($query) { $searchTerm = '%' . $this->search . '%'; $query->where(DB::raw("CONCAT(hperson.patlast, ', ', hperson.patfirst, ' ', hperson.patmiddle)"), 'LIKE', $searchTerm)->orWhere('hadmlog.hpercode', 'LIKE', $searchTerm); })->where('hadmlog.dispcode', '=', 'disch'); } public function render() { $query = $this->baseQuery(); $datas = $query->paginate(10); return view('livewire.reports.billing-summary-report', compact('datas')); } }
