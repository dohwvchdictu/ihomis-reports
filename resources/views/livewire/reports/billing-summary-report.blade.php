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
                                placeholder="Search" style="font-size: 14px;">
                        </div>

                        <!-- From Date -->
                        <label for="fromDate" class="mb-0" style="font-size: 16px;">From:</label>
                        <div class="form-group mr-2 ml-1" style="width: 120px;">
                            <input type="date" wire:model.defer="state.fdate" class="form-control"
                                style="font-size: 14px;">
                        </div>

                        <!-- To Date -->
                        <label for="toDate" class="mb-0" style="font-size: 16px;">To:</label>
                        <div class="form-group mr-2 ml-1" style="width: 120px;">
                            <input type="date" wire:model.defer="state.tdate" class="form-control"
                                style="font-size: 14px;">
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
                            <button class="btn btn-warning" wire:click="export" wire:loading.attr="disabled">
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
                                                        <td>{{ $ddata->HospitalCode }}</td>
                                                        <td>{{ $ddata->PatientName }}</td>
                                                        <td>{{ $ddata->Admission }}</td>
                                                        <td>{{ $ddata->Discharge }}</td>
                                                        <td>{{ $ddata->RoomBoard }}</td>
                                                        <td>{{ $ddata->Medicines }}</td>
                                                        <td>{{ $ddata->Miscellaneous }}</td>
                                                        <td>{{ $ddata->Supplies }}</td>
                                                        <td>{{ $ddata->Radiology }}</td>
                                                        <td>{{ $ddata->Laboratory }}</td>
                                                        <td>{{ $ddata->nbb }}</td>
                                                        <td>{{ $ddata->philhealthbenehci }}</td>
                                                        <td>{{ $ddata->ptotalactualchargeshci }}</td>
                                                        <td>{{ $ddata->pdiscounthci }}</td>
                                                        <td>{{ $ddata->ptotalactualchargespf }}</td>
                                                        <td>{{ $ddata->pdiscountpf }}</td>
                                                        <td>{{ $ddata->philhealthbenepf }}</td>
                                                        <td>{{ $ddata->session1 }}</td>
                                                        <td>{{ $ddata->session2 }}</td>
                                                        <td>{{ $ddata->firstcase }}</td>
                                                        <td>{{ $ddata->amt1 }}</td>
                                                        <td>{{ $ddata->secondcase }}</td>
                                                        <td>{{ $ddata->amt2 }}</td>
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
                                <div wire:loading class="text-center py-4 justify-content-center align-items-center"
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
    </div>
</div>
</div>
