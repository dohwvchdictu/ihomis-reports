<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1 class="m-0 text-dark">Appointments</h1> -->
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        {{-- <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Reports</li> --}}
                        <li class="breadcrumb-item active">Philhealth-Status-Summary</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->

        </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">

                    <div class="d-flex align-items-center mx-4 mb-2 w-100">
                        <!-- Search Bar -->
                        <div class="form-group mr-3" style="width: 500px;">
                            <input type="text" class="form-control" id="searchBar" wire:model.debounce="search"
                                wire:loading.attr="disabled" wire:target="filter" placeholder="Search"
                                style="font-size: 14px;">
                        </div>

                        <label for="status" style="font-size: 16px;">Status:</label>
                        <div class="form-group w-10 mr-2 ml-1">
                            <select wire:model.defer="status" wire:loading.attr="disabled" wire:target="filter"
                                class="form-control" style="flex: 1; width: 130px; font-size: 14px;">
                                <option value="">ALL</option>
                                <option value="WITH CHEQUE">WITH CHEQUE</option>
                                <option value="IN PROCESS">IN PROCESS</option>
                                <option value="RETURN">RETURN</option>
                                <option value="DENIED">DENIED</option>
                            </select>
                        </div>

                        <label for="type" style="font-size: 16px;">Type:</label>
                        <div class="form-group mr-2 ml-1" style="width: 230px;">
                            <select wire:model.defer="type" wire:loading.attr="disabled" wire:target="filter"
                                class="form-control" style="font-size: 14px;">
                                <option value="0">ALL</option>
                                <option value="1">ADMISSION</option>
                                <option value="2">OPD</option>
                            </select>
                        </div>

                        <!-- From Date -->
                        <label for="fromDate" style="font-size: 16px;">From:</label>
                        <div class="form-group mr-2 ml-1" style="width: 120px;">
                            <input type="date" wire:model.defer="state.fdate" wire:loading.attr="disabled"
                                wire:target="filter" class="form-control" style="font-size: 14px;">
                        </div>

                        <!-- To Date -->
                        <label for="toDate" style="font-size: 16px;">To:</label>
                        <div class="form-group mr-2 ml-1" style="width: 120px;">
                            <input type="date" wire:model.defer="state.tdate" wire:loading.attr="disabled"
                                wire:target="filter" class="form-control" style="font-size: 14px;">
                        </div>

                        <!-- Filter Button -->
                        <div class="form-group w-25 mr-4">
                            <button class="btn btn-primary w-30 mr-4" wire:click="filter" wire:loading.attr="disabled">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>

                        <!-- Export Button -->
                        <div class="form-group w-25">
                            <button class="btn btn-warning w-30 mr-4" wire:click="export" wire:loading.attr="disabled"
                                wire:target="filter">
                                <i class="fas fa-file-excel"></i> Export
                            </button>
                        </div>
                    </div>

                    <!-- Table Card -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <!-- Table displayed when not loading -->
                                <div wire:loading.remove>
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="px-4 py-1">Claim Series No.</th>
                                                <th class="py-1">Patient Name</th>
                                                <th class="py-1">Member Name</th>
                                                <th class="py-1">Member PIN</th>
                                                <th class="py-1">Date of Admission</th>
                                                <th class="py-1">Date of Discharge</th>
                                                <th class="py-1">Receipt No.</th>
                                                <th class="py-1">Claim No.</th>
                                                <th class="py-1">Receive Date</th>
                                                <th class="py-1">Claim Status</th>
                                                <th class="py-1">First Case Code</th>
                                                <th class="py-1">Professional Fee</th>
                                                <th class="py-1">HCI Fee</th>
                                                <th class="py-1">Total Claim Amount Paid</th>
                                                <th class="py-1">Refile Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($datas as $data)
                                                <tr style="white-space: nowrap;">
                                                    <td class="text-center">{{ $data->Pclaimserieslhio }}</td>
                                                    <td>{{ $data->PatientName }}</td>
                                                    <td>{{ $data->MemberName }}</td>
                                                    <td class="text-center">{{ $data->memphicnum }}</td>
                                                    <td class="text-center">{{ $data->pAdmissionDate }}</td>
                                                    <td class="text-center">{{ $data->pDischargeDate }}</td>
                                                    <td>{{ $data->pReceiptTicketNumber }}</td>
                                                    <td>{{ $data->pClaimNumber }}</td>
                                                    <td class="text-center">{{ $data->preceiveddate }}</td>
                                                    <td class="text-center">{{ $data->pStatus }}</td>
                                                    <td class="text-center">{{ $data->firstcasecode }}</td>
                                                    <td class="text-right">{{ number_format($data->phicdocfee, 2) }}
                                                    </td>
                                                    <td class="text-right">{{ number_format($data->phichospfee, 2) }}
                                                    </td>
                                                    <td class="text-right">
                                                        {{ number_format($data->pTotalClaimAmountPaid, 2) }}</td>
                                                    <td class="text-center">{{ $data->pclaimdaterefile }}</td>
                                                </tr>
                                            @empty
                                                <tr class="text-center">
                                                    <td colspan="15">No results found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Loading during filter/search -->
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

        <!-- Export loading overlay (initially hidden) -->
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
    <!-- /.content -->

</div>
