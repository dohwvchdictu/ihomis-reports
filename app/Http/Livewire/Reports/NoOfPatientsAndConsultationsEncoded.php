<?php

namespace App\Http\Livewire\Reports;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NoOfPatientsAndConsultationsEncoded extends Component
{
    public $datas = [];
    public $month;
    public $year;

    public function mount()
    {
        $this->month = now()->format('m'); // Returns "05" for May
        $this->year = now()->format('Y');
        $this->loadData();
    }


    public function loadData()
    {
        // Apply the filters based on the selected month and year
        $totalSub = DB::table('hopdlog')
            ->select('hpercode', 'enccode')
            ->whereMonth('opddate', $this->month)
            ->whereYear('opddate', $this->year)
            ->unionAll(
                DB::table('herlog')->select('hpercode', 'enccode')
                    ->whereMonth('erdate', $this->month)
                    ->whereYear('erdate', $this->year)
            )
            ->unionAll(
                DB::table('hadmlog')->select('hpercode', 'enccode')
                    ->whereMonth('admdate', $this->month)
                    ->whereYear('admdate', $this->year)
            );

        $totalQuery = DB::table(DB::raw("({$totalSub->toSql()}) as all_logs"))
            ->mergeBindings($totalSub)
            ->selectRaw("'Total' AS Department, COUNT(DISTINCT hpercode) AS No_of_Patients_Encoded, COUNT(enccode) AS No_of_Consultations_Encoded");

        $opdQuery = DB::table('hopdlog')
            ->selectRaw("'OPD' AS Department, COUNT(DISTINCT hpercode) AS No_of_Patients_Encoded, COUNT(enccode) AS No_of_Consultations_Encoded")
            ->whereMonth('opddate', $this->month)
            ->whereYear('opddate', $this->year);

        $erQuery = DB::table('herlog')
            ->selectRaw("'ER' AS Department, COUNT(DISTINCT hpercode) AS No_of_Patients_Encoded, COUNT(enccode) AS No_of_Consultations_Encoded")
            ->whereMonth('erdate', $this->month)
            ->whereYear('erdate', $this->year);

        $admQuery = DB::table('hadmlog')
            ->selectRaw("'ADM' AS Department, COUNT(DISTINCT hpercode) AS No_of_Patients_Encoded, COUNT(enccode) AS No_of_Consultations_Encoded")
            ->whereMonth('admdate', $this->month)
            ->whereYear('admdate', $this->year);

        $this->datas = $opdQuery
            ->unionAll($erQuery)
            ->unionAll($admQuery)
            ->unionAll($totalQuery)
            ->get();
    }

    // Call this method when the filter button is clicked
    public function filterData()
    {
        $this->loadData();  // Reload data based on the selected filters
    }

    public function render()
    {
        return view('livewire.reports.no-of-patients-and-consultations-encoded');
    }
}

