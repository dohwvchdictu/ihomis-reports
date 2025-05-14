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
                    <div class="d-flex justify-content-start mb-3 mx-3 align-items-end">
                        <div class="d-flex flex-wrap align-items-end ms-4" style="gap: 10px;">
                            <div>
                                <input type="text" wire:model.live="searchTerm" class="form-control"
                                    placeholder="Search" size="30">
                                <div wire:loading.delay wire:target="searchTerm" class="mt-1">
                                    <div class="la-line-spin-clockwise-fade-rotating la-dark la-sm">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </div>
                            </div>

                            {{-- From Date --}}
                            <div>
                                <label for="fromDate" class="mb-1">From:</label>
                                <div wire:ignore class="input-group date" id="fromDate" data-target-input="nearest"
                                    data-fromdate="@this">
                                    <input type="text" id="fromDateInput" class="form-control datetimepicker-input"
                                        data-target="#fromDate" style="width: 110px;" />
                                    <div class="input-group-append" data-target="#fromDate"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>

                            {{-- To Date --}}
                            <div>
                                <label for="toDate" class="mb-1">To:</label>
                                <div wire:ignore class="input-group date" id="toDate" data-target-input="nearest"
                                    data-todate="@this">
                                    <input type="text" id="toDateInput" class="form-control datetimepicker-input"
                                        data-target="#toDate" style="width: 110px;" />
                                    <div class="input-group-append" data-target="#toDate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Filter Button --}}
                            <div>
                                <button class="btn btn-primary" wire:click="filter" wire:loading.attr="disabled">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </div>

                        </div>

                        <!-- Spacer (No need for col here, use flex spacing instead) -->
                        <div class="flex-grow-1"></div>
                        {{-- Export Button --}}
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-warning" wire:click="exportExcel" wire:loading.attr="disabled"
                                style="width: 90px;">
                                <i class="fas fa-file-excel"></i> Export
                            </button>
                        </div>
                    </div>


                    {{-- Table --}}
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
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

                            {{-- Pagination --}}
                            @if ($datas)
                                <div class="card-footer d-flex justify-content-end">
                                    {{ $datas->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
