<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- <h1 class="m-0">Billing Summary Report</h1> --}}
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Billing Summary Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex align-items-center mx-4 mb-2 w-100">
                        <!-- Search Bar -->
                        <div class="form-group mr-3" style="width: 200px;">
                            <input type="text" class="form-control" id="searchBar" wire:model.debounce="search"
                                wire:loading.attr="disabled" wire:target="filter" placeholder="Search"
                                style="font-size: 14px;">
                        </div>

                        <!-- From Date -->
                        <label for="fromDate" class="mb-0" style="font-size: 16px;">From:</label>
                        <div class="form-group mr-2 ml-1" style="width: 120px;">
                            <input type="date" wire:model.defer="state.fdate" wire:loading.attr="disabled"
                                wire:target="filter" class="form-control" style="font-size: 14px;">
                        </div>

                        <!-- To Date -->
                        <label for="toDate" class="mb-0" style="font-size: 16px;">To:</label>
                        <div class="form-group mr-2 ml-1" style="width: 120px;">
                            <input type="date" wire:model.defer="state.tdate" wire:loading.attr="disabled"
                                wire:target="filter" class="form-control" style="font-size: 14px;">
                        </div>

                        <!-- Filter Button -->
                        <div class="form-group mr-4">
                            <button class="btn btn-primary" wire:click="filter" wire:loading.attr="disabled">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>

                        <!-- Spacer to push Export button to right -->
                        <div class="flex-grow-1"></div>

                        <!-- Export Button aligned right -->
                        <div class="form-group mr-lg-5">
                            <button class="btn btn-warning" wire:click="export" wire:loading.attr="disabled"
                                wire:target="filter">
                                <i class="fas fa-file-excel"></i> Export
                            </button>
                        </div>
                    </div>



                    {{-- Table --}}
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <!-- Table hidden during loading -->
                                <div wire:loading.remove>
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="px-4 py-1">Hospital No.</th>
                                                <th class="py-1">Patient Name</th>
                                                <th class="py-1">Admission</th>
                                                <th class="py-1">Discharge</th>
                                                <th class="py-1">Room Board</th>
                                                <th class="py-1">Medicines</th>
                                                <th class="py-1">Miscellaneous</th>
                                                <th class="py-1">Supplies</th>
                                                <th class="py-1">Radiology</th>
                                                <th class="py-1">Laboratory</th>
                                                <th class="py-1">NBB</th>
                                                <th class="py-1">Philhealth Benefit HCI</th>
                                                <th class="py-1">Total Actual Charges HCI</th>
                                                <th class="py-1">Discount HCI</th>
                                                <th class="py-1">Total Actual Charges PF</th>
                                                <th class="py-1">Discount PF</th>
                                                <th class="py-1">Philhealth Benefit PF</th>
                                                <th class="py-1">Session 1</th>
                                                <th class="py-1">Session 2</th>
                                                <th class="py-1">First Case</th>
                                                <th class="py-1">First Case Rate</th>
                                                <th class="py-1">Second Case</th>
                                                <th class="py-1">Second Case Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($datas && $datas->count())
                                                @foreach ($datas as $ddata)
                                                    <tr>
                                                        <td class="text-center">{{ $ddata->HospitalCode }}</td>
                                                        <td>{{ $ddata->PatientName }}</td>
                                                        <td class="text-center">{{ $ddata->Admission }}</td>
                                                        <td class="text-center">{{ $ddata->Discharge }}</td>
                                                        <td class="text-right">{{ number_format($ddata->RoomBoard, 2) }}
                                                        </td>
                                                        <td class="text-right">{{ number_format($ddata->Medicines, 2) }}
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($ddata->Miscellaneous, 2) }}
                                                        </td>
                                                        <td class="text-right">{{ number_format($ddata->Supplies, 2) }}
                                                        </td>
                                                        <td class="text-right">{{ number_format($ddata->Radiology, 2) }}
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($ddata->Laboratory, 2) }}
                                                        </td>
                                                        <td class="text-center">{{ $ddata->nbb }}</td>
                                                        <td class="text-right">
                                                            {{ number_format($ddata->philhealthbenehci, 2) }}
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($ddata->ptotalactualchargeshci, 2) }}
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($ddata->pdiscounthci, 2) }}
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($ddata->ptotalactualchargespf, 2) }}
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($ddata->pdiscountpf, 2) }}
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($ddata->philhealthbenepf, 2) }}
                                                        </td>
                                                        <td class="text-center">{{ $ddata->session1 }}</td>
                                                        <td class="text-center">{{ $ddata->session2 }}</td>
                                                        <td class="text-center">{{ $ddata->firstcase }}</td>
                                                        <td class="text-right">
                                                            {{ number_format($ddata->amt1, 2) }}
                                                        </td>
                                                        <td class="text-center">{{ $ddata->secondcase }}</td>
                                                        <td class="text-right">
                                                            {{ number_format($ddata->amt2, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="text-center">
                                                    <td colspan="23">No results found.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Show loading while fetching data -->
                                <div wire:loading wire:target="filter, search, status, type, state.fdate, state.tdate"
                                    class="text-center py-4 justify-content-center align-items-center"
                                    style="height: 100%; width: 100%;">
                                    <div class="spinner-border text-success" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p class="text-success mt-2">Loading data...</p>
                                </div>
                            </div>
                        </div>
                        <div class="pagination-summary mb-2 card-footer">
                            <div class="mb-2">
                                Showing {{ $datas->count() }} out of {{ $datas->total() }} results
                            </div>
                            <div>
                                {{ $datas->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div wire:loading.flex wire:target="export" class="justify-content-center align-items-center"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
           background-color: rgba(255, 255, 255, 0.8); z-index: 1050;">
            <div class="text-center">
                <div class="spinner-border text-warning" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Exporting...</span>
                </div>
                <p class="mt-3 font-weight-bold text-dark">Exporting data, please wait...</p>
            </div>
        </div>
    </div>
</div>
