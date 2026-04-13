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
use App\Http\Controllers\BoardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('user.login');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/', [AdminLoginController::class, 'login'])->name('login');
    Route::get('logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'home'])->name('home');

        // General Settings
        Route::get('/general-setting', [AdminController::class, 'general_setting'])->name('general_setting');
        Route::post('/general-setting/update', [AdminController::class, 'general_setting_update'])->name('general_setting.update');

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

        // Board 
        Route::get('/boards', [BoardController::class, 'index'])->name('board');
        Route::get('/board/create', [BoardController::class, 'create'])->name('board.create');
        Route::get('/board/edit/{id}', [BoardController::class, 'edit'])->name('board.edit');
        Route::get('/board/view/{id}', [BoardController::class, 'view'])->name('board.view');
        Route::get('/board/delete/{id}', [BoardController::class, 'delete'])->name('board.delete');
        Route::post('/board/store', [BoardController::class, 'store'])->name('board.store');
        Route::post('/board/update', [BoardController::class, 'update'])->name('board.update');

        Route::post('/board/{id}/add/list', [BoardController::class, 'add_list'])->name('board.add_list');
        Route::post('/board/{id}/update/list/position', [BoardController::class, 'update_list_position'])->name('board.update_list_position');
    });
});


Route::name('user.')->group(function () {
    Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserLoginController::class, 'login']);
    Route::get('/logout', [UserLoginController::class, 'logout'])->name('logout');
});


Route::name('user.')->prefix('user')->middleware(['auth', 'checkStatus'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'home'])->name('home');
    Route::get('/my-attendance', [UserController::class, 'my_attendance'])->name('my_attendance');

    Route::post('/mark-attendence', [UserController::class, 'mark_attendence'])->name('mark_attendence');

    Route::get('/public-holidays', [UserController::class, 'public_holidays'])->name('public_holidays');
    Route::get('/leaves', [UserController::class, 'leaves'])->name('leaves');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/username/update', [ProfileController::class, 'username_update'])->name('profile.username.update');
    Route::post('/profile/password/update', [ProfileController::class, 'password_update'])->name('profile.password.update');
});

Route::get('/import', function () {
    $data = json_decode(file_get_contents(public_path('attendence.json')), true)[2]['data'] ?? [];
    $array = [];

    foreach ($data as $key => $value) {
        $in_time = !empty($value['m_intime']) ? date('Y-m-d H:i:s', strtotime($value['m_intime'])) : '';
        $out_time = !empty($value['m_outtime']) ? date('Y-m-d H:i:s', strtotime($value['m_outtime'])) : '';
        $status =  $value['m_pastatus'];
        $is_completed = 1;

        $schedule = \App\Models\UserSchedule::where('user_id', $value['a_aid'])->first();

        if ($status == 0) {
            $status = 0;
            $value['points'] = 4;
        } else  if ($status == 1) {
            $status = 1;
        } else if ($status == 2) {
            $status = 2;
        } else if ($status == 3) {
            $status = 4;
        } else if ($status == 4) {
            $status = 3;
        }

        if (!empty($in_time) && empty($out_time)) {
            $value['m_outstatus'] = 'Markout Missing';
            $status = 5;
            $value['points'] = 1;
        }

        $created_at = $in_time;
        $updated_at = $out_time;

        if (empty($created_at)) {
            $created_at = date('Y-m-d H:i:s', strtotime($value['m_date'] . ' ' . date('H:i:s')));
        }

        if (empty($updated_at)) {
            $updated_at = $created_at;
        }

        $array[$key] = [
            'id'           => $value['m_id'],
            'user_id'      => $value['a_aid'],
            'in_time'      => $in_time,
            'out_time'     => $out_time,
            'in_status'    => $value['m_instatus'],
            'out_status'   => $value['m_outstatus'],
            'hours'        => $value['m_totalhours'],
            'total_hours'  => $schedule->hours ?? 0,
            'remarks'      => $value['m_remarks'],
            'status'       => $status,
            'points'       => $value['points'],
            'is_completed' => $is_completed,
            'created_at'   => $created_at,
            'updated_at'   => $updated_at
        ];
    }

    \App\Models\UserAttendence::insert($array);

    return $array;
});
