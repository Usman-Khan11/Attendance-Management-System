<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserLeave;
use App\Models\UserSchedule;
use Illuminate\Http\Request;
use App\Models\PublicHoilday;
use App\Models\UserAttendence;

class CronController extends Controller
{
    protected $users;
    protected $date;

    public function __construct()
    {
        $this->users = User::where('status', 1)->with('user_schedule')->get();
        $this->date = date("Y-m-d");
    }

    public function cron()
    {
        $this->markMarkoutMissing();
        $this->markLeaveDay();
        $this->markPublicHoliday();
        $this->markRestDay();

        return "Cron executed...";
    }

    public function markPublicHoliday()
    {
        $holiday = PublicHoilday::where('date', $this->date)->first();
        if ($holiday) {
            foreach ($this->users as $key => $user) {
                $check = UserAttendence::where('user_id', $user->id)->where('created_at', '>=', $this->date . ' 00:00:00')->where('created_at', '<=', $this->date . ' 23:59:59')->count();
                if ($check == 0) {
                    $attendence = new UserAttendence();
                    $attendence->user_id = $user->id;
                    $attendence->remarks = @$holiday->name;
                    $attendence->status = 2;
                    $attendence->save();
                }
            }
        }
    }

    public function markRestDay()
    {
        foreach ($this->users as $key => $user) {
            $restDay = $user->user_schedule->restday;
            $check = UserAttendence::where('user_id', $user->id)->where('created_at', '>=', $this->date . ' 00:00:00')->where('created_at', '<=', $this->date . ' 23:59:59')->count();
            if ($check == 0 && in_array(date("l"), $restDay)) {
                $attendence = new UserAttendence();
                $attendence->user_id = $user->id;
                $attendence->remarks = date("l");
                $attendence->status = 4;
                $attendence->save();
            }
        }
    }

    public function markMarkoutMissing()
    {
        foreach ($this->users as $key => $user) {
            $time_1 = strtotime(date("H:i"));
            $time_2 = strtotime(date("H:i", strtotime($user->user_schedule->out_time . " +6 hours")));
            $check = UserAttendence::where('user_id', $user->id)->latest()->first();
            if ($check && $check->in_time && empty($check->out_time) && $check->status = 1) {
                $attendence =  UserAttendence::find($check->id);
                $attendence->status = 5;
                $attendence->save();
            }
        }
    }

    public function markLeaveDay()
    {
        $leaves = UserLeave::where('dates', 'like', "%$this->date%")->where('status', 1)->get();
        foreach ($leaves as $key => $leave) {
            $check = UserAttendence::where('user_id', $leave->user_id)->where('created_at', '>=', $this->date . ' 00:00:00')->where('created_at', '<=', $this->date . ' 23:59:59')->count();
            if ($check == 0) {
                $attendence = new UserAttendence();
                $attendence->user_id = $leave->user_id;
                $attendence->remarks = $leave->title;
                $attendence->status = 3;
                $attendence->save();

                if ($this->date == $leave->inactive_date) {
                    $leave->status = 0;
                    $leave->save();
                }
            }
        }
    }
}
