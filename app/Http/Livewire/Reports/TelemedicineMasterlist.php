<?php

namespace App\Http\Livewire\Reports;

use App\Exports\NoOfPatientsAndConsultationsExport;
use App\Exports\TelemedicineMasterlistExport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class TelemedicineMasterlist extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $fDate;
    public $tDate;

    public $state = [];
    protected $updatesQueryString = ['state'];

    public function mount()
    {
        // Set default date range: from first day of this month to today
        $this->fDate = Carbon::today()->startOfMonth()->toDateString();
        $this->tDate = Carbon::today()->toDateString();

        // // Manually set your test date range
        // $this->fDate = '2025-01-01';
        // $this->tDate = '2025-01-15';

        // Initialize state for binding with date inputs
        $this->state['fdate'] = $this->fDate;
        $this->state['tdate'] = $this->tDate;
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
        $collection = $this->baseQuery()->get();

        return Excel::download(new TelemedicineMasterlistExport($collection), "Telemedicine-Masterlist-Report({$this->fDate}-{$this->tDate}).xlsx");
    }
    protected function baseQuery()
    {
        return DB::table('hopdlog')
            ->join('hperson', 'hopdlog.hpercode', '=', 'hperson.hpercode')
            ->join('htypser', 'hopdlog.tscode', '=', 'htypser.tscode')
            ->join('hencdiag', 'hopdlog.hpercode', '=', 'hencdiag.hpercode')
            ->join('hmrhisto', 'hopdlog.hpercode', '=', 'hmrhisto.hpercode')
            ->leftJoin('hdiag', 'hencdiag.diagcode', '=', 'hdiag.diagcode')
            ->join('hprovider', 'hopdlog.licno', '=', 'hprovider.licno')
            ->join('hpersonal', 'hprovider.employeeid', '=', 'hpersonal.employeeid')
            ->select([
                DB::raw("CONCAT(TRIM(IFNULL(hperson.patfirst, '')), ' ', TRIM(IFNULL(hperson.patmiddle, '')), ' ', TRIM(IFNULL(hperson.patlast, ''))) AS PatientName"),
                DB::raw("MAX(hopdlog.opddate) AS DateofConsultation"),
                DB::raw("MAX(hopdlog.patage) AS Age"),
                DB::raw("MAX(htypser.tsdesc) AS TypeofService"),
                DB::raw("MAX(hmrhisto.history) AS ChiefComplaint"),
                DB::raw("MAX(hdiag.diagdesc) AS Diagnosis"),
                DB::raw("MAX(CONCAT(TRIM(IFNULL(hpersonal.firstname, '')), ' ', TRIM(IFNULL(hpersonal.middlename, '')), ' ', TRIM(IFNULL(hpersonal.lastname, '')))) AS AttendingProvider"),
            ])
            ->groupBy('hopdlog.enccode')

            ->whereRaw('DATE(hopdlog.opddate) BETWEEN ? AND ?', [$this->fDate, $this->tDate])
            ->where('hmrhisto.histype', '=', 'ÇOMPL')
            ->whereNotNull('hopdlog.telemedstat')
            ->where(function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(DB::raw("CONCAT(TRIM(IFNULL(hperson.patfirst, '')), ' ', TRIM(IFNULL(hperson.patmiddle, '')), ' ', TRIM(IFNULL(hperson.patlast, '')))"), 'LIKE', $searchTerm)
                    ->orWhere(DB::raw("CONCAT(TRIM(IFNULL(hpersonal.firstname, '')), ' ', TRIM(IFNULL(hpersonal.middlename, '')), ' ', TRIM(IFNULL(hpersonal.lastname, '')))"), 'LIKE', $searchTerm);
            });

    }


    public function render()
    {
        $query = $this->baseQuery();
        $datas = $query->paginate(10);

        return view('livewire.reports.telemedicine-masterlist', compact('datas'));
    }
}

