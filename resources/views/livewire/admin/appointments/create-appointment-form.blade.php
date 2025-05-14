<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1 class="m-0 text-dark">Appointments</h1> -->
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Appointments</li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form wire:submit.prevent="createAppointment">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Add New Appointment</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="client">Client:</label>
                                            <select wire:model.defer="state.clien_id" class="form-control"
                                                wire:model.defer="state.clien_id">
                                                <option value="">Select Client</option>
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date and Appointment Time in one row -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date:</label>
                                            <div class="input-group date" id="appointmentDate"
                                                data-target-input="nearest" data-appointmentdate="@this">
                                                <input wire:model.defer="state.date" type="text"
                                                    class="form-control datetimepicker-input"
                                                    data-target="#appointmentDate">
                                                <div class="input-group-append" data-target="#appointmentDate"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Appointment Time:</label>
                                            <div class="input-group date" id="appointmentTime"
                                                data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input"
                                                    data-target="#appointmentTime">
                                                <div class="input-group-append" data-target="#appointmentTime"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="far fa-calendar-alt"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group px-2" style="display: flex; align-items: center; width: auto;">
                                    <label style="margin-right: 5px; font-size: 14px;">From:</label>
                                    <div wire:ignore class="input-group date" id="pherFdate" data-target-input="nearest"
                                        data-pherfdate="@this">
                                        <input type="text" class="form-control datetimepicker-input"
                                            data-target="#pherFdate" id="pherFdateInput"
                                            style="width: 99px; font-size: 16px;">
                                        <div class="input-group-append" data-target="#pherFdate"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text" style="font-size: 14px;">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="note">Note:</label>
                                            <textarea wire:model.defer="state.note" class="form-control"
                                                id="note"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="button" class="btn btn-secondary"><i
                                        class="fa fa-times mr-1"></i>Cancel</button>
                                <button type="submit" class="btn btn-primary"><i
                                        class="fa fa-save mr-1"></i>Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>