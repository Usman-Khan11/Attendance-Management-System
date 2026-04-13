<?php

namespace App\Console\Commands;

use App\Models\GeneralSetting;
use App\Models\PublicHoilday;
use App\Models\User;
use App\Models\UserAttendence;
use Illuminate\Console\Command;

class Attendence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:attendence';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark attendence of absent, holiday, leave';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $setting = GeneralSetting::first();
        $users = User::where('status', 1)->with('user_schedule')->get();
        $date  = date("Y-m-d");
        $day   = date('l');

        foreach ($users as $key => $user) {
            if (!$user->user_schedule) {
                continue;
            }

            $now       = time();
            $in_time   = $user->user_schedule->in_time;
            $out_time  = $user->user_schedule->out_time;
            $rest_days = $user->user_schedule->restday;
            $total_hours = $user->user_schedule->hours;

            $half_day = $setting->half_day[$day]['status'] ?? 0;
            $half_in_time = $setting->half_day[$day]['in_time'] ?? null;
            $half_out_time = $setting->half_day[$day]['out_time'] ?? null;

            if ($half_day == 1 && $half_in_time && $half_out_time) {
                $in_time  = $half_in_time;
                $out_time = $half_out_time;

                $total_hours = calculateTotalHour($date . " " . $in_time, $date . " " . $out_time);
            }

            $shift_start = strtotime($date . " " . $in_time);
            $shift_end   = strtotime($date . " " . $out_time);

            if ($shift_end <= $shift_start) {
                $shift_end = strtotime("+1 day", $shift_end);
                $attendance_date = date('Y-m-d', strtotime('-1 day'));
            } else {
                $attendance_date = $date;
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
                markUserAttendence($user->id, $holiday->name, 2, 1);
            }

            // Rest Day
            else if (in_array($day, $rest_days) && $has_attendance == 0) {
                markUserAttendence($user->id, date("l"), 4, 1);
            }

            // Absent
            else if ($has_attendance == 0) {
                markUserAttendence($user->id, '', 0, 1, $total_hours);
            }
        }
    }
}
