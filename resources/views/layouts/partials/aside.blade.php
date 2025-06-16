<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #3c4b64 ;">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="{{ asset('backend/dist/img/iHOMIS.png') }}" alt="iHOMIS Logo"
            class="brand-image img-thumbnail elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light" style="color: white">iHOMIS Reports</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('backend/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div> --}}

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt "></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.appointments') }}"
                        class="nav-link {{ request()->is('admin/appointments') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>
                            Appointments
                        </p>
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.users') }}"
                        class="nav-link {{ request()->is('admin/users') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li> --}}
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li
                    class="nav-item has-treeview {{ request()->is('reports/philhealthestatussummary') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Philhealth Eclaims
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ml-2">
                        <li class="nav-item">
                            <a href="{{ route('reports.philhealthestatussummary') }}"
                                class="nav-link {{ request()->is('reports/philhealthestatussummary') ? 'active' : '' }}"
                                style="{{ request()->is('reports/philhealthestatussummary') ? 'background-color: #10B981; color: #ffffff;' : '' }}">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Status Summary</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li
                    class="nav-item has-treeview {{ request()->is('reports/billing-summary-report') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Billing
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ml-2">
                        <li class="nav-item">
                            <a href="{{ route('reports.billingsummaryreport') }}"
                                class="nav-link {{ request()->is('reports/billing-summary-report') ? 'active' : '' }}"
                                style="{{ request()->is('reports/billing-summary-report') ? 'background-color: #10B981; color: #ffffff;' : '' }}">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Summary Report</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li
                    class="nav-item has-treeview {{ request()->is('reports/no-of-patients-and-consultations-encoded') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            UHC
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ml-2">
                        <li class="nav-item">
                            <a href="{{ route('reports.NoOfPatientsAndConsultationsEncoded') }}"
                                class="nav-link {{ request()->is('reports/no-of-patients-and-consultations-encoded') ? 'active' : '' }}"
                                style="{{ request()->is('reports/no-of-patients-and-consultations-encoded') ? 'background-color: #10B981; color: #ffffff;' : '' }}">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Patients & Consultations</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="nav-item has-treeview {{ request()->is('reports/telemedicine-masterlist') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Telemedicine
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ml-2">
                        <li class="nav-item">
                            <a href="{{ route('reports.TelemedicineMasterlist') }}"
                                class="nav-link {{ request()->is('reports/telemedicine-masterlist') ? 'active' : '' }}"
                                style="{{ request()->is('reports/telemedicine-masterlist') ? 'background-color: #10B981; color: #ffffff;' : '' }}">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Masterlist</p>
                            </a>
                        </li>
                    </ul>
                </li>


                {{-- <li class="nav-item">
                    <a class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li> --}}
            </ul>
        </nav>


    </div>
    <!-- /.sidebar -->
</aside>
