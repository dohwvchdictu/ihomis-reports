<?php

namespace App\Http\Livewire\Reports;

use App\Exports\PHECStatusExport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
// use Illuminate\Support\Facades\Cache;

class PhilhealthStatusSummary extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $type = '0';
    public $status;
    public $fDate;
    public $tDate;

    public $state = [];

    public function mount()
    {
        $today = Carbon::today();
        $this->fDate = $today->copy()->startOfMonth()->format('Y-m-d'); // Changed to Y-m-d for native date comparison
        $this->tDate = $today->format('Y-m-d');

        // Assign to the state array for binding to date inputs
        $this->state['fdate'] = $this->fDate;
        $this->state['tdate'] = $this->tDate;
    }

    public function filter()
    {
        $this->fDate = $this->state['fdate'] ?? $this->fDate;
        $this->tDate = $this->state['tdate'] ?? $this->tDate;
        $this->resetPage();
    }

    public function export()
    {
        return (new PHECStatusExport(
            $this->fDate,
            $this->tDate,
            $this->search,
            $this->type,
            $this->status
        ))->download("PhilHealth-Eclaims-Status-Report({$this->fDate} to {$this->tDate}).xlsx");
    }

    protected function baseQuery($table)
    {
        return DB::table($table)
            ->join('hperson', "{$table}.hpercode", '=', 'hperson.hpercode')
            ->leftJoin('hphicclaimmap', "{$table}.enccode", '=', 'hphicclaimmap.enccode')
            ->leftJoin('hpatcon', "{$table}.enccode", '=', 'hpatcon.enccode')
            ->leftJoin('hpatcon1', "{$table}.enccode", '=', 'hpatcon1.enccode')
            ->leftJoin('hphicclaimtransmittal', "{$table}.enccode", '=', 'hphicclaimtransmittal.enccode')
            ->leftJoin('hphicclaimstatus', 'hphicclaimmap.Pclaimserieslhio', '=', 'hphicclaimstatus.Pclaimserieslhio')
            ->leftJoin('hphiclog', 'hpatcon.memphicnum', '=', 'hphiclog.phicnum')
            ->select(
                'hphicclaimmap.Pclaimserieslhio',
                DB::raw("CONCAT(hperson.patlast, ', ', hperson.patfirst, ' ', hperson.patmiddle) AS PatientName"),
                DB::raw("CONCAT(hphiclog.memlast, ', ', hphiclog.memfirst, ' ', hphiclog.memmid) AS MemberName"),
                'hpatcon.memphicnum',
                'hphicclaimmap.pAdmissionDate',
                'hphicclaimmap.pDischargeDate',
                'hphicclaimmap.pReceiptTicketNumber',
                'hphicclaimmap.pClaimNumber',
                'hphicclaimmap.preceiveddate',
                'hphicclaimmap.pStatus',
                'hpatcon1.firstcase AS firstcasecode',
                'hpatcon1.philhealthbenepf AS phicdocfee',
                'hpatcon1.philhealthbenehci AS phichosfee',
                DB::raw('IFNULL(hphicclaimstatus.pTotalClaimAmountPaid, 0) AS pTotalClaimAmountPaid'),
                DB::raw('IFNULL(hphicclaimstatus.pclaimdaterefile, "") AS pclaimdaterefile')
            )
            ->whereBetween(DB::raw("STR_TO_DATE(hphicclaimmap.pReceivedDate, '%m-%d-%Y')"), [$this->fDate, $this->tDate])
            ->where('hphicclaimmap.pStatus', 'like', '%' . $this->status . '%')
            ->where(function ($query) {
                $query->where(DB::raw("CONCAT(hperson.patlast, ', ', hperson.patfirst, ' ', hperson.patmiddle)"), 'like', '%' . $this->search . '%')
                    ->orWhere(DB::raw("CONCAT(hphiclog.memlast, ', ', hphiclog.memfirst, ' ', hphiclog.memmid)"), 'like', '%' . $this->search . '%')
                    ->orWhere('hpatcon.memphicnum', 'like', '%' . $this->search . '%');
            })
            ->orderBy('hperson.patlast');
    }

    public function render()
    {
        if ($this->type === '1') {
            $table = 'hadmlog';
        } elseif ($this->type === '2') {
            $table = 'hopdlog';
        } else {
            $table = 'hadmlog'; // default
        }


        // Optional caching block
        // $cacheKey = "phec_{$this->fDate}_{$this->tDate}_{$this->search}_{$this->type}_{$this->status}_page_" . $this->page;
        // $datas = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($table) {
        //     return $this->baseQuery($table)->paginate(10);
        // });

        $datas = $this->baseQuery($table)->paginate(10);

        return view('livewire.reports.philhealth-status-summary', compact('datas'));
    }
}
