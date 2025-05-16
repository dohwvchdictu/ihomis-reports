<?php

namespace App\Http\Livewire\Reports;

use App\Exports\NoOfPatientsAndConsultationsExport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class NoOfPatientsAndConsultationsEncoded extends Component
{
    public $month;
    public $year;
    public $state = [];

    public function mount()
    {
        $today = Carbon::today();

        // Set default month and year
        $this->month = $today->format('m');
        $this->year = $today->format('Y');

        // Initialize state for binding with select inputs (e.g., dropdowns)
        $this->state['month'] = $this->month;
        $this->state['year'] = $this->year;
    }

    public function updatedState(): void
    {
        $this->month = $this->state['month'] ?? $this->month;
        $this->year = $this->state['year'] ?? $this->year;

    }

    public function filter(): void
    {
        // In case you want to trigger filtering explicitly
        $this->updatedState();
    }
    public function export()
    {
        $collection = $this->baseQuery()->get();

        return Excel::download(new NoOfPatientsAndConsultationsExport($collection), "No-of-Patients-and-Consultations-Encoded-Report({$this->month}-{$this->year}).xlsx");
    }
    protected function baseQuery()
    {
        // OPD
        $opdQuery = DB::table('hopdlog')
            ->selectRaw("'OPD' AS Department, COUNT(DISTINCT hpercode) AS No_of_Patients_Encoded, COUNT(enccode) AS No_of_Consultations_Encoded")
            ->whereMonth('opddate', $this->month)
            ->whereYear('opddate', $this->year);

        // ER
        $erQuery = DB::table('herlog')
            ->selectRaw("'ER' AS Department, COUNT(DISTINCT hpercode) AS No_of_Patients_Encoded, COUNT(enccode) AS No_of_Consultations_Encoded")
            ->whereMonth('erdate', $this->month)
            ->whereYear('erdate', $this->year);

        // ADM
        $admQuery = DB::table('hadmlog')
            ->selectRaw("'ADM' AS Department, COUNT(DISTINCT hpercode) AS No_of_Patients_Encoded, COUNT(enccode) AS No_of_Consultations_Encoded")
            ->whereMonth('admdate', $this->month)
            ->whereYear('admdate', $this->year);

        // Combined logs for TOTAL
        $subQuery = DB::table('hopdlog')
            ->select('hpercode', 'enccode')
            ->whereMonth('opddate', $this->month)
            ->whereYear('opddate', $this->year)
            ->unionAll(
                DB::table('herlog')
                    ->select('hpercode', 'enccode')
                    ->whereMonth('erdate', $this->month)
                    ->whereYear('erdate', $this->year)
            )
            ->unionAll(
                DB::table('hadmlog')
                    ->select('hpercode', 'enccode')
                    ->whereMonth('admdate', $this->month)
                    ->whereYear('admdate', $this->year)
            );

        // Total summary
        $totalQuery = DB::table(DB::raw("({$subQuery->toSql()}) as all_logs"))
            ->mergeBindings($subQuery)
            ->selectRaw("'Total' AS Department, COUNT(DISTINCT hpercode) AS No_of_Patients_Encoded, COUNT(enccode) AS No_of_Consultations_Encoded");

        // Return the unioned queries as a collection
        return $opdQuery
            ->unionAll($erQuery)
            ->unionAll($admQuery)
            ->unionAll($totalQuery); // <- returns a Laravel collection
    }

    public function render()
    {
        $datas = $this->baseQuery()->get();

        return view('livewire.reports.no-of-patients-and-consultations-encoded', compact('datas'));
    }
}

