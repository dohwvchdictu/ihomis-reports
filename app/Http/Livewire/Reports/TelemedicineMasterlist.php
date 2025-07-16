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
        $searchTerm = '%' . $this->search . '%';

        return DB::table('hopdlog')
            ->join('hperson', 'hopdlog.hpercode', '=', 'hperson.hpercode')
            ->join('htypser', 'hopdlog.tscode', '=', 'htypser.tscode')
            ->leftJoin('hencdiag as final_diag', function ($join) {

                $join->on('hopdlog.enccode', '=', 'final_diag.enccode')
                    ->where('final_diag.tdcode', 'FINDX')
                    ->where('final_diag.primediag', 'Y');
            })
            ->leftJoin(DB::raw('(
    SELECT d1.enccode, d1.diagtext
    FROM hencdiag d1
    JOIN (
        SELECT enccode, MAX(encdate) AS max_encdate
        FROM hencdiag
        WHERE tdcode = "CLIDI"
        GROUP BY enccode
    ) d2 ON d1.enccode = d2.enccode AND d1.encdate = d2.max_encdate
    WHERE d1.tdcode = "CLIDI"
) as clinical_diag'), 'hopdlog.enccode', '=', 'clinical_diag.enccode')


            ->join('hmrhisto', 'hopdlog.enccode', '=', 'hmrhisto.enccode')
            ->join('hprovider', 'hopdlog.licno', '=', 'hprovider.licno')
            ->join('hpersonal', 'hprovider.employeeid', '=', 'hpersonal.employeeid')
            ->select([
                DB::raw("CONCAT(TRIM(IFNULL(hperson.patfirst, '')), ' ', TRIM(IFNULL(hperson.patmiddle, '')), ' ', TRIM(IFNULL(hperson.patlast, ''))) AS PatientName"),
                'hopdlog.opddate AS DateofConsultation',
                DB::raw('FLOOR(hopdlog.patage) AS Age'),
                'htypser.tsdesc AS TypeofService',
                'hmrhisto.history AS ChiefComplaint',
                'clinical_diag.diagtext AS ClinicalDiagnosis',
                'final_diag.diagtext AS FinalDiagnosis',
                DB::raw("CONCAT(TRIM(IFNULL(hpersonal.firstname, '')), ' ', TRIM(IFNULL(hpersonal.middlename, '')), ' ', TRIM(IFNULL(hpersonal.lastname, ''))) AS AttendingProvider"),
            ])
            ->whereRaw('DATE(hopdlog.opddate) BETWEEN ? AND ?', [$this->fDate, $this->tDate])
            ->whereRaw('LOWER(hmrhisto.histype) = ?', ['çompl'])
            ->where(function ($query) {
                $query->whereNotNull('clinical_diag.diagtext')
                    ->orWhereNotNull('final_diag.diagtext');
            })

            ->when($this->search, function ($query) use ($searchTerm) {
                $query->where(function ($query) use ($searchTerm) {
                    $query->where(DB::raw("CONCAT(TRIM(IFNULL(hperson.patfirst, '')), ' ', TRIM(IFNULL(hperson.patmiddle, '')), ' ', TRIM(IFNULL(hperson.patlast, '')))"), 'LIKE', $searchTerm)
                        ->orWhere(DB::raw("CONCAT(TRIM(IFNULL(hpersonal.firstname, '')), ' ', TRIM(IFNULL(hpersonal.middlename, '')), ' ', TRIM(IFNULL(hpersonal.lastname, '')))"), 'LIKE', $searchTerm);
                });
            })
            ->orderBy('hopdlog.opddate');
    }


    public function render()
    {
        $query = $this->baseQuery();
        $datas = $query->paginate(10);

        return view('livewire.reports.telemedicine-masterlist', compact('datas'));
    }
}

