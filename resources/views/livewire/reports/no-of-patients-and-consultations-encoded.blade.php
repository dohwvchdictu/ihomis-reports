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
                        <li class="breadcrumb-item active">No. of Patients and Consultations Encoded</li>
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
                    <div class="d-flex justify-content-start mb-3 mx-3 align-items-end">
                        <div class="d-flex flex-wrap align-items-end ms-4" style="gap: 10px;">
                            <!-- Month Dropdown -->
                            <div>
                                <label class="form-label">Month</label>
                                <select class="form-control" wire:model.defer="state.month" wire:loading.attr="disabled"
                                    wire:target="filter" style="width: 120px;">
                                    @foreach (range(1, 12) as $m)
                                        <option value="{{ sprintf('%02d', $m) }}">
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Year Dropdown -->
                            <div>
                                <label class="form-label">Year</label>
                                <select class="form-control" wire:model.defer="state.year" wire:loading.attr="disabled"
                                    wire:target="filter" style="width: 80px;">
                                    @foreach (range(now()->year, 2020) as $y)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Button -->
                            <div>
                                <label class="form-label d-block">&nbsp;</label>
                                <button class="btn btn-primary" wire:click="filter" wire:loading.attr="disabled"
                                    wire:target="filter" style="width: 80px;"><i class="fas fa-filter"></i>
                                    Filter</button>
                            </div>
                        </div>

                        <!-- Spacer (No need for col here, use flex spacing instead) -->
                        <div class="flex-grow-1"></div>

                        <!-- Export Button aligned right -->
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-warning" wire:click="export" wire:loading.attr="disabled"
                                wire:target="filter" style="width: 90px;">
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
                                                <th scope="col" class="px-4 py-1">Department</th>
                                                <th scope="col" class="py-1">No. of Patients Encoded</th>
                                                <th scope="col" class="py-1">No. of Consultations Encoded</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($datas as $data)
                                                <tr @if (isset($data->Department) && $data->Department === 'Total') style="font-weight: bold;" @endif>
                                                    <td>{{ $data->Department ?? 'N/A' }}</td>
                                                    <td>{{ isset($data->No_of_Patients_Encoded) ? number_format($data->No_of_Patients_Encoded) : 'N/A' }}
                                                    </td>
                                                    <td>{{ isset($data->No_of_Consultations_Encoded) ? number_format($data->No_of_Consultations_Encoded) : 'N/A' }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">No results found</td>
                                                </tr>
                                            @endforelse
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
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
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
