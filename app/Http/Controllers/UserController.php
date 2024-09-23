<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserLeave;
use App\Models\UserSchedule;
use Illuminate\Http\Request;
use App\Models\PublicHoilday;
use App\Models\UserAttendence;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function home()
    {
        $data["page_title"] = "Dashboard";
        return view('user.dashboard', $data);
    }

    public function my_attendance(Request $request)
    {
        $data["page_title"] = "My Attendance";
        $userId = auth()->user()->id;
        $from = date("Y-m") . "-01 00:00:00";
        $to = date("Y-m") . "-31 23:59:59";

        if ($request->ajax()) {
            $query = UserAttendence::Query();
            $query = $query->where('user_id', $userId);
            $query = $query->where('created_at', '>=', $from)->where('created_at', '<=', $to);
            $query = $query->latest()->get();
            return DataTables::of($query)->addIndexColumn()->make(true);
        }
    }

    public function mark_in(Request $request)
    {
        $date = date("Y-m-d");
        $yesterday = date('Y-m-d', strtotime($date . '-1 days'));
        $userId = auth()->user()->id;
        $user_schedule = UserSchedule::where('user_id', $userId)->first();
        if ($user_schedule) {
            $in_time = $user_schedule->in_time;
            $out_time = $user_schedule->out_time;

            $time = strtotime(date("H:i"));
            $time_1 = strtotime(date("H:i", strtotime($in_time . " +15 minutes")));
            $time_2 = strtotime(date("H:i", strtotime($in_time . " -15 minutes")));

            // Check today is public holiday
            $check = PublicHoilday::where('date', $date)->first();
            if ($check) {
                return ["error" => "Today is public holiday ($check->name)."];
            }

            // Check today is rest day
            if (in_array(date("l"), $user_schedule->restday)) {
                return ["error" => "Today is your restday."];
            }

            // Check today markin
            $check = UserAttendence::where('user_id', $userId)->where('created_at', '>=', $date . ' 00:00:00')->where('created_at', '<=', $date . ' 23:59:59')->first();
            if ($check && $check->in_time) {
                return ["error" => "Already marked in."];
            }

            $attendence = new UserAttendence();
            $attendence->user_id = $userId;
            $attendence->in_time = Carbon::now()->format('Y-m-d H:i:s');
            $attendence->status = 1;

            if ($time > $time_1) {
                $attendence->in_status = "Late In";
            } else if ($time <= $time_1 && $time >= $time_2) {
                $attendence->in_status = "In Time";
            } else if ($time < $time_2) {
                $attendence->in_status = "Early In";
            }

            if ($attendence->save()) {
                return ["success" => "Marked in successfully."];
            }

            return ["error" => "Something went wrong."];
        }

        return ["error" => "Something went wrong."];
    }

    public function mark_out(Request $request)
    {
        $date = date("Y-m-d");
        $userId = auth()->user()->id;
        $user_schedule = UserSchedule::where('user_id', $userId)->first();
        $attendence = UserAttendence::where('user_id', $userId)->where('created_at', '>=', $date . ' 00:00:00')->where('created_at', '<=', $date . ' 23:59:59')->first();

        if ($user_schedule && $attendence && $attendence->status == 1) {
            $in_time = $user_schedule->in_time;
            $out_time = $user_schedule->out_time;

            $time = strtotime(date("H:i"));
            $time_1 = strtotime(date("H:i", strtotime($out_time . " +15 minutes")));
            $time_2 = strtotime(date("H:i", strtotime($out_time . " -15 minutes")));

            // check today markout
            $check = UserAttendence::where('user_id', $userId)->where('created_at', '>=', $date . ' 00:00:00')->where('created_at', '<=', $date . ' 23:59:59')->first();
            if ($check && $check->out_time) {
                return ["error" => "Already marked out."];
            }

            $attendence->out_time = Carbon::now()->format('Y-m-d H:i:s');
            $attendence->hours = calculateTotalHour($attendence->in_time, $attendence->out_time);

            if ($time > $time_1) {
                $attendence->out_status = "Late Out";
            } else if ($time <= $time_1 && $time >= $time_2) {
                $attendence->out_status = "On Time";
            } else if ($time < $time_2) {
                $attendence->out_status = "Early Out";
            }

            if ($attendence->save()) {
                return ["success" => "Marked out successfully."];
            }
        }

        return ["error" => "Please Markin First to proceed."];
    }
}
