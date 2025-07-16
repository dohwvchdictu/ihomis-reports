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
use App\Http\Livewire\LoginPage;
use Illuminate\Support\Facades\Route;

// 👇 Public Login Route
Route::get('/login', LoginPage::class)->name('login');

// 👇 Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {

    Route::get('/', PhilhealthStatusSummary::class)->name('reports.home');

    Route::get('admin/dashboard', DashboardController::class)->name('admin.dashboard');
    Route::get('reports/philhealthestatussummary', PhilhealthStatusSummary::class)->name('reports.philhealthestatussummary');
    Route::get('reports/billing-summary-report', BillingSummaryReport::class)->name('reports.billingsummaryreport');
    Route::get('reports/no-of-patients-and-consultations-encoded', NoOfPatientsAndConsultationsEncoded::class)->name('reports.NoOfPatientsAndConsultationsEncoded');
    Route::get('reports/telemedicine-masterlist', TelemedicineMasterlist::class)->name('reports.TelemedicineMasterlist');

    // Route::get('admin/users', ListUsers::class)->name('admin.users');
});
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
