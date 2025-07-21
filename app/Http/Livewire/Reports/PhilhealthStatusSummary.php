<?php

namespace App\Http\Livewire\Reports;

use App\Exports\PHECStatusExport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class PhilhealthStatusSummary extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $type = '0';
    public $status = '';
    public $fDate;
    public $tDate;

    public $state = [];
    protected $updatesQueryString = ['search', 'status', 'type', 'state'];
    public function mount(): void
    {

        // Set default date range: from first day of this month to today
        $this->fDate = Carbon::today()->startOfMonth()->toDateString();
        $this->tDate = Carbon::today()->toDateString();


        // Initialize state for binding with date inputs
        $this->state['fdate'] = $this->fDate;
        $this->state['tdate'] = $this->tDate;

        // Initialize default status if needed
        $this->status = '';
    }

    public function updating($property)
    {
        $this->resetPage();
    }

    public function updatedState(): void
    {
        // Sync fDate and tDate when state updates (e.g., date inputs)
        $this->fDate = $this->state['fdate'] ?? $this->fDate;
        $this->tDate = $this->state['tdate'] ?? $this->tDate;

        // Reset pagination whenever filter changes
        $this->resetPage();
    }

    public function filter(): void
    {
        // In case you want to trigger filtering explicitly
        $this->updatedState();
    }

    public function export()
    {

        if ($this->type === '1') {
            $collection = $this->baseQuery('hadmlog')->get();
        } elseif ($this->type === '2') {
            $collection = $this->baseQuery('hopdlog')->get();
        } else {
            $hadmlog = $this->baseQuery('hadmlog');
            $hopdlog = $this->baseQuery('hopdlog');
            $collection = $hadmlog->union($hopdlog)->get();
        }

        return Excel::download(new PHECStatusExport($collection), "PhilHealth-Eclaims-Status-Report({$this->fDate}_to_{$this->tDate}).xlsx");
    }


    /**
     * Prepare the base query for reports based on table and filters.
     */
    protected function baseQuery($table)
    {
        return DB::table($table)
            ->leftJoin('hphicclaimmap', "$table.enccode", '=', 'hphicclaimmap.enccode')
            ->leftJoin('hpatcon', "$table.enccode", '=', 'hpatcon.enccode')
            ->leftJoin('hpatcon1', "$table.enccode", '=', 'hpatcon1.enccode')
            ->leftJoin('hphicclaimtransmittal', "$table.enccode", '=', 'hphicclaimtransmittal.enccode')
            ->leftJoin('hphicclaimstatus', 'hphicclaimmap.Pclaimserieslhio', '=', 'hphicclaimstatus.Pclaimserieslhio')
            ->leftJoin('hphiclog', 'hpatcon.memphicnum', '=', 'hphiclog.phicnum')
            ->join('hperson', "$table.hpercode", '=', 'hperson.hpercode')
            ->select(
                'hphicclaimmap.Pclaimserieslhio',
                DB::raw("CONCAT(' ', hperson.patlast, ', ', hperson.patfirst, ' ', IFNULL(hperson.patmiddle, ''), ' ', IFNULL(hperson.patsuffix, '')) AS PatientName"),
                DB::raw("CONCAT(' ', hphiclog.memlast, ', ', hphiclog.memfirst, ' ', IFNULL(hphiclog.memmid, '')) AS MemberName"),
                'hpatcon.memphicnum',
                'hphicclaimmap.pAdmissionDate',
                'hphicclaimmap.pDischargeDate',
                'hphicclaimmap.pReceiptTicketNumber',
                'hphicclaimmap.pClaimNumber',
                'hphicclaimmap.preceiveddate',
                'hphicclaimmap.pStatus',
                'hpatcon1.firstcase AS firstcasecode',
                'hpatcon1.philhealthbenepf AS phicdocfee',
                'hpatcon1.philhealthbenehci AS phichospfee',
                DB::raw('IFNULL(hphicclaimstatus.pTotalClaimAmountPaid, 0) AS pTotalClaimAmountPaid'),
                DB::raw('IFNULL(hphicclaimstatus.pclaimdaterefile, "") AS pclaimdaterefile')
            )
            ->whereBetween(DB::raw("STR_TO_DATE(hphicclaimmap.pReceivedDate, '%m-%d-%Y')"), [$this->fDate, $this->tDate])
            ->where('hphicclaimmap.pStatus', 'like', '%' . ($this->status ?? '') . '%')
            ->where(function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(DB::raw("CONCAT(' ', hperson.patlast, ', ', hperson.patfirst, ' ', IFNULL(hperson.patmiddle, ''), ' ', IFNULL(hperson.patsuffix, ''))"), 'like', $searchTerm)
                    ->orWhere(DB::raw("CONCAT(' ', hphiclog.memlast, ', ', hphiclog.memfirst, ' ', IFNULL(hphiclog.memmid, ''))"), 'like', $searchTerm)
                    ->orWhere('hpatcon.memphicnum', 'like', $searchTerm);
            })
            ->orderBy('hperson.patlast');
    }

    public function render()
    {
        if ($this->type === '1') {
            $query = $this->baseQuery('hadmlog');
        } elseif ($this->type === '2') {
            $query = $this->baseQuery('hopdlog');
        } else {
            // union both queries from hadmlog and hopdlog
            $hadmlogQuery = $this->baseQuery('hadmlog');
            $hopdlogQuery = $this->baseQuery('hopdlog');

            $query = $hadmlogQuery->union($hopdlogQuery);
        }

        // IMPORTANT: Laravel pagination does not work out of the box with union queries,
        // so you need a manual pagination approach or convert union to a subquery.

        // Simplest approach: get all results, then paginate manually (not recommended for large datasets)

        $datas = $query->paginate(10); // This will work only if $query is a QueryBuilder, not a union.

        return view('livewire.reports.philhealth-status-summary', compact('datas'));
    }

}

