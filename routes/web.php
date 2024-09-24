<?php

use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\AttendenceController;
use App\Http\Controllers\Admin\ManageUserController;
use App\Http\Controllers\Admin\PublicHolidayController;

// User Controllers
use App\Http\Controllers\Auth\LoginController as UserLoginController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\UserController;

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
    return redirect()->route('user.login');
});

Route::get('/cron', [CronController::class, 'cron'])->name('cron');


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/', [AdminLoginController::class, 'login'])->name('login');
    Route::get('logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'home'])->name('home');

        // User 
        Route::get('/users', [ManageUserController::class, 'index'])->name('user');
        Route::get('/user/create', [ManageUserController::class, 'create'])->name('user.create');
        Route::get('/user/edit/{id}', [ManageUserController::class, 'edit'])->name('user.edit');
        Route::get('/user/show/{id}', [ManageUserController::class, 'show'])->name('user.show');
        Route::get('/user/login/{id}', [ManageUserController::class, 'login'])->name('user.login');
        Route::get('/user/delete/{id}', [ManageUserController::class, 'delete'])->name('user.delete');
        Route::post('/user/store', [ManageUserController::class, 'store'])->name('user.store');
        Route::post('/user/update', [ManageUserController::class, 'update'])->name('user.update');

        // Public Holiday 
        Route::get('/public-holidays', [PublicHolidayController::class, 'index'])->name('public_holiday');
        Route::get('/public-holiday/create', [PublicHolidayController::class, 'create'])->name('public_holiday.create');
        Route::get('/public-holiday/edit/{id}', [PublicHolidayController::class, 'edit'])->name('public_holiday.edit');
        Route::get('/public-holiday/delete/{id}', [PublicHolidayController::class, 'delete'])->name('public_holiday.delete');
        Route::post('/public-holiday/store', [PublicHolidayController::class, 'store'])->name('public_holiday.store');
        Route::post('/public-holiday/update', [PublicHolidayController::class, 'update'])->name('public_holiday.update');

        // Leaves 
        Route::get('/leaves', [LeaveController::class, 'index'])->name('leave');
        Route::get('/leave/create', [LeaveController::class, 'create'])->name('leave.create');
        Route::get('/leave/edit/{id}', [LeaveController::class, 'edit'])->name('leave.edit');
        Route::get('/leave/delete/{id}', [LeaveController::class, 'delete'])->name('leave.delete');
        Route::post('/leave/store', [LeaveController::class, 'store'])->name('leave.store');
        Route::post('/leave/update', [LeaveController::class, 'update'])->name('leave.update');

        // Attendence 
        Route::get('/attendences', [AttendenceController::class, 'index'])->name('attendence');
        Route::get('/attendence/summary', [AttendenceController::class, 'summary'])->name('attendence.summary');
        Route::get('/attendence/summary/get', [AttendenceController::class, 'summary_get'])->name('attendence.summary.get');
    });
});


Route::name('user.')->group(function () {
    Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserLoginController::class, 'login']);
    Route::get('/logout', [UserLoginController::class, 'logout'])->name('logout');
});


Route::name('user.')->prefix('user')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::middleware(['checkStatus'])->group(function () {
            Route::get('/dashboard', [UserController::class, 'home'])->name('home');
            Route::get('/my-attendance', [UserController::class, 'my_attendance'])->name('my_attendance');

            Route::post('/mark-in', [UserController::class, 'mark_in'])->name('mark_in');
            Route::post('/mark-out', [UserController::class, 'mark_out'])->name('mark_out');
        });
    });
});
