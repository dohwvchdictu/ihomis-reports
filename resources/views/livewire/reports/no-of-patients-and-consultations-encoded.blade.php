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
                                <select class="form-control" wire:model="month" style="width: 120px;">
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
                                <select class="form-control" wire:model="year" style="width: 80px;">
                                    @foreach (range(now()->year, 2020) as $y)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Button -->
                            <div>
                                <label class="form-label d-block">&nbsp;</label>
                                <button class="btn btn-primary" wire:click="loadData" style="width: 80px;"><i
                                        class="fas fa-filter"></i> Filter</button>
                            </div>
                        </div>

                        <!-- Spacer (No need for col here, use flex spacing instead) -->
                        <div class="flex-grow-1"></div>

                        <!-- Export Button aligned right -->
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-warning" wire:click="export" wire:loading.attr="disabled"
                                style="width: 90px;">
                                <i class="fas fa-file-excel"></i> Export
                            </button>
                        </div>
                    </div>


                    <!-- Data Table -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
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
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
