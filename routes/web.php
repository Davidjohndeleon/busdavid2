<?php
use App\Http\Controllers\PassengerReportController; //temporary for frontend only
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CheckpointController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::get('/admin/buses', [AdminController::class, 'manageBuses'])->name('admin.manage.buses');
        Route::post('/admin/buses', [AdminController::class, 'addBus'])->name('admin.add.bus');
        Route::get('/admin/buses/{bus}/edit', [AdminController::class, 'editBus'])->name('admin.edit.bus');
        Route::post('/admin/buses/{bus}/edit', [AdminController::class, 'updateBus'])->name('admin.update.bus');
        Route::delete('/admin/buses/{id}', [AdminController::class, 'deleteBus'])->name('admin.delete.bus');

        Route::get('/admin/register_driver', [AdminController::class, 'showRegisterDriverForm'])->name('admin.register.driver.form');
        Route::post('/admin/register_driver', [AdminController::class, 'registerDriver'])->name('admin.register.driver');

        Route::get('/admin/schedules', [AdminController::class, 'manageSchedules'])->name('admin.schedules');
        Route::post('/admin/schedules', [AdminController::class, 'addSchedule'])->name('admin.add.schedule');
        Route::get('/admin/schedules/{schedule}/edit', [AdminController::class, 'editSchedule'])->name('admin.edit.schedule');
        Route::post('/admin/schedules/{schedule}/edit', [AdminController::class, 'updateSchedule'])->name('admin.update.schedule');
        Route::delete('/admin/schedules/{schedule}', [AdminController::class, 'deleteSchedule'])->name('admin.delete.schedule');
    });

    Route::middleware(['auth'])->group(function () {
        Route::resource('drivers', DriverController::class);
        Route::get('/driver/qrcode', [DriverController::class, 'generateQRCode'])->name('driver.qrcode');
    });

    Route::middleware('role:checkpoint')->group(function () {
        Route::get('/checkpoint/scan/{driverId}', [CheckpointController::class, 'scanQRCode']);
    });

    Route::middleware('role:passenger')->group(function () {
        Route::get('/passenger/schedules', [PassengerController::class, 'viewBusSchedules']);
    });

    //temporary route for frontend passenger
    Route::middleware(['auth'])->group(function () {
        Route::get('/passenger/report', [PassengerReportController::class, 'showReportForm'])->name('passenger.report.form');
        Route::post('/passenger/report', [PassengerReportController::class, 'submitReport'])->name('passenger.report.submit');
    });
});

require __DIR__.'/auth.php';
