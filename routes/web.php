<?php

use App\Http\Controllers\AmenityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Society Management System (PWA)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/quick-login/{role}', [AuthController::class, 'quickLogin'])->name('quick-login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/offline', function () {
    return view('pwa.offline');
})->name('offline');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Society Members Directory
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::post('/members', [MemberController::class, 'store'])->name('members.store');
    Route::post('/members/{id}/update', [MemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('members.destroy');

    // Maintenance & Billing
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::post('/maintenance/generate', [MaintenanceController::class, 'generate'])->name('maintenance.generate');
    Route::post('/maintenance/{id}/pay', [MaintenanceController::class, 'pay'])->name('maintenance.pay');
    Route::get('/maintenance/{id}/receipt', [MaintenanceController::class, 'receipt'])->name('maintenance.receipt');

    // Digital Notice Board
    Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
    Route::post('/notices', [NoticeController::class, 'store'])->name('notices.store');
    Route::delete('/notices/{id}', [NoticeController::class, 'destroy'])->name('notices.destroy');

    // Complaints & Helpdesk
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::post('/complaints/{id}/update', [ComplaintController::class, 'update'])->name('complaints.update');

    // Visitor Gate Pass & Guard Operations
    Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index');
    Route::post('/visitors', [VisitorController::class, 'store'])->name('visitors.store');
    Route::post('/visitors/{id}/check-in', [VisitorController::class, 'checkIn'])->name('visitors.check-in');
    Route::post('/visitors/{id}/check-out', [VisitorController::class, 'checkOut'])->name('visitors.check-out');

    // Amenity & Facility Bookings
    Route::get('/amenities', [AmenityController::class, 'index'])->name('amenities.index');
    Route::post('/amenities/book', [AmenityController::class, 'book'])->name('amenities.book');
    Route::post('/amenities/{id}/cancel', [AmenityController::class, 'cancel'])->name('amenities.cancel');
});
