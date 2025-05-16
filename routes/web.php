<?php

use App\Exports\BillingSummaryReportExport;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Livewire\Admin\Appointments\CreateAppointmentForm;
use App\Http\Livewire\Admin\Appointments\ListAppointments;
use App\Http\Livewire\Reports\BillingSummaryReport;
use App\Http\Livewire\Reports\NoOfPatientsAndConsultationsEncoded;
use App\Http\Livewire\Reports\PhilhealthStatusSummary;
use App\Http\Livewire\Admin\Users\ListUsers;
use App\Http\Livewire\Reports\TelemedicineMasterlist;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', PhilhealthStatusSummary::class)->name('reports.home');

Route::get('admin/dashboard', DashboardController::class)->name('admin.dashboard');

Route::get('admin/users', ListUsers::class)->name('admin.users');

Route::get('admin/appointments', ListAppointments::class)->name('admin.appointments');

Route::get('admin/appointments/create', CreateAppointmentForm::class)->name('admin.appointments.create');

Route::get('reports/philhealthestatussummary', PhilhealthStatusSummary::class)->name('reports.philhealthestatussummary');

Route::get('reports/billing-summary-report', BillingSummaryReport::class)->name('reports.billingsummaryreport');

Route::get('reports/no-of-patients-and-consultations-encoded', NoOfPatientsAndConsultationsEncoded::class)->name('reports.NoOfPatientsAndConsultationsEncoded');

Route::get('reports/telemedicine-masterlist', TelemedicineMasterlist::class)->name('reports.TelemedicineMasterlist');
