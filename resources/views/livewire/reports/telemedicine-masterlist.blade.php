<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- Optional Header Title -->
                    <!-- <h1 class="m-0 text-dark">Appointments</h1> -->
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- Optional Breadcrumb -->
                        {{-- <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Reports</li> --}}
                        <li class="breadcrumb-item active">Telemedicine Masterlist</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Filter Section -->
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
                    <!-- Data Table -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <!-- Table hidden during loading -->
                                <div wire:loading.remove>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr class="text-sm">
                                                <th scope="col" class="px-4 py-1">Patient Name</th>
                                                <th scope="col" class="py-1">Date of Consultation</th>
                                                <th scope="col" class="py-1">Age</th>
                                                <th scope="col" class="py-1">Type of Service</th>
                                                <th scope="col" class="py-1">Chief Complaint</th>
                                                <th scope="col" class="py-1">Clinical Diagnosis</th>
                                                <th scope="col" class="py-1">Final Diagnosis</th>
                                                <th scope="col" class="py-1">Attending Provider</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($datas && $datas->count())
                                                @foreach ($datas as $ddata)
                                                    <tr>
                                                        <td>{{ $ddata->PatientName }}</td>
                                                        <td>{{ $ddata->DateofConsultation }}</td>
                                                        <td>{{ $ddata->Age }}</td>
                                                        <td>{{ $ddata->TypeofService }}</td>
                                                        <td>{{ $ddata->ChiefComplaint }}</td>
                                                        <td>{{ $ddata->ClinicalDiagnosis }}</td>
                                                        <td>{{ $ddata->FinalDiagnosis }}</td>
                                                        <td>{{ $ddata->AttendingProvider }}</td>
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
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
