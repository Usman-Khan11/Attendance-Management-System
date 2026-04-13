<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLeave;
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
        $this->date  = date("Y-m-d");
    }

    public function cron()
    {
        foreach ($this->users as $key => $user) {
            $now       = time();
            $in_time   = $user->user_schedule->in_time;
            $out_time  = $user->user_schedule->out_time;
            $rest_days = json_decode($user->user_schedule->restday);

            $shift_start = strtotime($this->date . " " . $in_time);
            $shift_end   = strtotime($this->date . " " . $out_time);

            if ($shift_end <= $shift_start) {
                $shift_end = strtotime("+1 day", $shift_end);
                $attendance_date = date('Y-m-d', strtotime('-1 day'));
            } else {
                $attendance_date = $this->date;
            }

            $day = date('l', strtotime($attendance_date));
            $shift_end_grace = strtotime("+30 minutes", $shift_end);

            if ($now <= $shift_end_grace) {
                continue;
            }

            $attendance = UserAttendence::where('user_id', $user->id)->orderBy('id', 'DESC')->first();
            $has_attendance = 0;

            if ($attendance && !empty($attendance->created_at)) {
                $att_date = $attendance->created_at;

                if ($att_date >= $attendance_date . ' 00:00:00' && $att_date <= $attendance_date . ' 23:59:59') {
                    $has_attendance = 1;
                }
            }

            // Public Holiday
            $holiday = PublicHoilday::where('date', $attendance_date)->first();
            if ($holiday && $has_attendance == 0) {
                $this->markUserAttendence($user->id, $holiday->name, 2, 1);
            }

            // Rest Day
            else if (in_array($day, $rest_days) && $has_attendance == 0) {
                $this->markUserAttendence($user->id, date("l"), 4, 1);
            }

            // Absent
            else if ($has_attendance == 0) {
                $this->markUserAttendence($user->id, '', 0, 1);
            }
        }

        return "Cron executed...";
    }

    public function markMarkoutMissing()
    {
        $attendences = UserAttendence::with('user')
            ->where('is_completed', 0)
            ->where('created_at', '<=', date('Y-m-d H:i:s', strtotime('-25 hours')))
            ->take(10)
            ->get();

        foreach ($attendences as $key => $attendence) {
            $attendence->status = 5;
            $attendence->is_completed = 1;
            $attendence->save();
        }

        return "Cron executed...";
    }

    // public function markLeaveDay()
    // {
    //     $leaves = UserLeave::where('dates', 'like', "%$this->date%")->where('status', 1)->get();
    //     foreach ($leaves as $key => $leave) {
    //         $check = UserAttendence::where('user_id', $leave->user_id)
    //             ->whereBetween('created_at', [$this->date . ' 00:00:00', $this->date . ' 23:59:59'])
    //             ->count();

    //         if ($check == 0) {
    //             $this->markUserAttendence($leave->user_id, $leave->title, 3, 1);

    //             if ($this->date == $leave->inactive_date) {
    //                 $leave->status = 0;
    //                 $leave->save();
    //             }
    //         }
    //     }
    // }

    private function markUserAttendence($user_id, $remarks, $status, $is_completed = 0)
    {
        $attendence = new UserAttendence();
        $attendence->user_id = $user_id;
        $attendence->remarks =  $remarks ?? null;
        $attendence->status  = $status;
        $attendence->is_completed = $is_completed;
        $attendence->save();
    }
}
