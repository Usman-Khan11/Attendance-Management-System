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
                $check = UserAttendence::where('user_id', $user->id)
                    ->whereBetween('created_at', [$this->date . ' 00:00:00', $this->date . ' 23:59:59'])
                    ->count();

                if ($check == 0) {
                    $this->markUserAttendence($user->id, @$holiday->name, 2, 1);
                }
            }
        }
    }

    public function markRestDay()
    {
        foreach ($this->users as $key => $user) {
            $restDay = $user->user_schedule->restday;
            $check = UserAttendence::where('user_id', $user->id)
                ->whereBetween('created_at', [$this->date . ' 00:00:00', $this->date . ' 23:59:59'])
                ->count();

            if ($check == 0 && in_array(date("l"), $restDay)) {
                $this->markUserAttendence($user->id, date("l"), 4, 1);
            }
        }
    }

    public function markMarkoutMissing()
    {
        $attendences = UserAttendence::with('user', 'user.user_schedule')
            ->where('is_completed', 0)
            ->take(10)
            ->get();

        foreach ($attendences as $key => $value) {
            $created_at_date = date("Y-m-d", strtotime($value->created_at));
            $time_1 = strtotime($value->created_at);
            $time_2 = strtotime("$created_at_date {$value->user->user_schedule->out_time} +4 hours");

            if ($value->in_time && empty($value->out_time) && $value->status == 1 && $time_1 > $time_2) {
                $attendence =  $value;
                $attendence->status = 5;
                $attendence->is_completed = 1;
                $attendence->save();
            }
        }
    }

    public function markLeaveDay()
    {
        $leaves = UserLeave::where('dates', 'like', "%$this->date%")->where('status', 1)->get();
        foreach ($leaves as $key => $leave) {
            $check = UserAttendence::where('user_id', $leave->user_id)
                ->whereBetween('created_at', [$this->date . ' 00:00:00', $this->date . ' 23:59:59'])
                ->count();

            if ($check == 0) {
                $this->markUserAttendence($leave->user_id, $leave->title, 3, 1);

                if ($this->date == $leave->inactive_date) {
                    $leave->status = 0;
                    $leave->save();
                }
            }
        }
    }

    private function markUserAttendence($user_id, $remarks, $status, $is_completed = 0)
    {
        $attendence = new UserAttendence();
        $attendence->user_id = $user_id;
        $attendence->remarks = $remarks ? $remarks : null;
        $attendence->status = $status;
        $attendence->is_completed = $is_completed;
        $attendence->save();
    }
}
