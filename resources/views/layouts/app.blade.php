<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>iHOMIS Reports</title>
    <link rel="icon" href="{{ asset('storage/images/favicon.ico') }}" type="image/x-icon">



    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('backend/plugins/toastr/toastr.min.css') }}">

    <link rel="stylesheet" type="text/css"
        href="{{ asset('backend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <style>
        table th,
        table td {
            white-space: nowrap;
            /* Prevent text from wrapping */
            overflow: hidden;
            /* Ensure overflowing content is hidden */
            text-overflow: ellipsis;
            /* Add ellipsis (...) for overflowing text */
        }

        table th {
            text-align: left;
            /* Optional: centers the header text */
        }

        /* Optional: Add some padding to the table to account for the spinner */
        .table-responsive {
            position: relative;
        }
    </style>

    <livewire:styles />
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        @include('layouts.partials.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('layouts.partials.aside')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper position-relative">
            {{ $slot }}
        </div>

        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        @include('layouts.partials.footer')
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('backend/dist/js/adminlte.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('backend/plugins/toastr/toastr.min.js') }}"></script>

    <script type="text/javascript" src="https://unpkg.com/moment"></script>

    <script type="text/javascript"
        src="{{ asset('backend/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            toastr.options = {
                "positionClass": "toast-bottom-right",
                "progressBar": true,
            }

            window.addEventListener('hide-form', event => {
                $('#form').modal('hide');
                toastr.success(event.detail.message, 'Success!');
            })

        });
    </script>

    <script>
        window.addEventListener('show-form', event => {
            $('#form').modal('show');
        })

        window.addEventListener('show-delete-modal', event => {
            $('#confirmationModal').modal('show');
        })

        window.addEventListener('hide-delete-modal', event => {
            $('#confirmationModal').modal('hide');
            toastr.success(event.detail.message, 'Success!');
        })
    </script>

    <script>
        $(document).ready(function() {
            var d = new Date()
            var yr = d.getFullYear();
            var month = d.getMonth() + 1;

            if (month < 10) {
                month = '0' + month
            }
            var date = d.getDate();
            if (date < 10) {
                date = '0' + date
            }
            var c_date = month + "-" + date + "-" + yr;
            var f_date = month + "-" + "01" + "-" + yr;

            document.getElementById('fromDateInput').value = f_date;
            document.getElementById('toDateInput').value = c_date;

            $('#fromDate').datetimepicker({
                format: 'MM-DD-YYYY'
            });

            $('#fromDate').on("change.datetimepicker", function(e) {
                let fdate = $(this).data('fromdate');
                eval(fdate).set('state.fdate', $('#fromDateInput').val());
            });

            $('#toDate').datetimepicker({
                format: 'MM-DD-YYYY'
            });

            $('#toDate').on("change.datetimepicker", function(e) {
                let fdate = $(this).data('todate');
                eval(fdate).set('state.tdate', $('#toDateInput').val());
            });

        });
    </script>

    <livewire:scripts />
    @stack('scripts')

</body>

</html>
