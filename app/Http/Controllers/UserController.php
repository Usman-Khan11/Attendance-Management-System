<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
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
    public function home(Request $request)
    {
        $data["page_title"] = "Dashboard";

        if ($request->ajax()) {
            $from = $request->from_date ? Carbon::parse($request->from_date)->startOfDay() : Carbon::now()->startOfMonth();
            $to = $request->to_date ? Carbon::parse($request->to_date)->endOfDay() : Carbon::now()->endOfMonth();
            $userId = auth()->user()->id;

            $res['present_this_month'] = UserAttendence::where('user_id', $userId)
                ->whereBetween('created_at', [$from, $to])
                ->whereIn('status', [1, 5])
                ->count();

            $res['absent_this_month'] = UserAttendence::where('user_id', $userId)
                ->whereBetween('created_at', [$from, $to])
                ->where('status', 0)
                ->count();

            $res['leave_this_month'] = UserAttendence::where('user_id', $userId)
                ->whereBetween('created_at', [$from, $to])
                ->where('status', 3)
                ->count();

            $res['holidays_this_month'] = UserAttendence::where('user_id', $userId)
                ->whereBetween('created_at', [$from, $to])
                ->where('status', 2)
                ->count();

            $res['restdays_this_month'] = UserAttendence::where('user_id', $userId)
                ->whereBetween('created_at', [$from, $to])
                ->where('status', 4)
                ->count();

            $res['hours'] = formatNumber(UserAttendence::where('user_id', $userId)
                ->whereBetween('created_at', [$from, $to])
                ->sum('hours'));

            $res['total_hours'] = formatNumber(UserAttendence::where('user_id', $userId)
                ->whereBetween('created_at', [$from, $to])
                ->sum('total_hours'));

            $res['total_late_markins'] = UserAttendence::where('user_id', $userId)
                ->whereBetween('created_at', [$from, $to])
                ->where('in_status', 'Late In')
                ->count();

            $res['points'] = '-' . UserAttendence::where('user_id', $userId)
                ->whereBetween('created_at', [$from, $to])
                ->sum('points');

            return $res;
        }

        return view('user.dashboard', $data);
    }

    public function my_attendance(Request $request)
    {
        $data["page_title"] = "My Attendance";
        $userId = auth()->user()->id;

        if ($request->ajax()) {
            $from = $request->from_date . ' 00:00:00';
            $to = $request->to_date . ' 23:59:59';

            $query = UserAttendence::where('user_id', $userId)
                ->where('created_at', '>=', $from)->where('created_at', '<=', $to)
                ->latest();

            return DataTables::of($query)
                ->addColumn('date', function ($row) {
                    return showDate($row->created_at);
                })
                ->addColumn('in_time', function ($row) {
                    return showTime($row->in_time);
                })
                ->addColumn('out_time', function ($row) {
                    return showTime($row->out_time);
                })
                ->addColumn('hours', function ($row) {
                    return formatNumber($row->hours) . ' hrs';
                })
                ->addColumn('in_out_status', function ($row) {
                    $status = '';

                    if (!empty($row->in_status)) {
                        $status .= attendanceStatus($row->in_status);
                    }

                    if (!empty($status)) {
                        $status .= ' / ';
                    }

                    if (!empty($row->out_status)) {
                        $status .= attendanceStatus($row->out_status);
                    }

                    return $status;
                })
                ->addColumn('points', function ($row) {
                    return $row->points;
                })
                ->addColumn('status_badge', function ($row) {
                    if ($row->status == 0) {
                        return '<span class="badge bg-danger">Absent</span>';
                    } else if ($row->status == 1) {
                        return '<span class="badge bg-success">Present</span>';
                    } else if ($row->status == 2) {
                        return '<span class="badge bg-info">Public Holiday</span>';
                    } else if ($row->status == 3) {
                        return '<span class="badge bg-warning">Leave</span>';
                    } else if ($row->status == 4) {
                        return '<span class="badge bg-info">Rest Day</span>';
                    } else if ($row->status == 5) {
                        return '<span class="badge bg-danger">Markout Missing</span>';
                    } else {
                        return '-';
                    }
                })
                ->rawColumns(['in_out_status', 'status_badge'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function mark_attendence(Request $request)
    {
        $type = $request->type ?? '';
        $date = date('Y-m-d');
        $time = strtotime(date("H:i:s"));
        $day  = date('l');
        $user_id = auth()->id();
        $ip_info = getIpInfo();
        $setting = GeneralSetting::firstOrFail();

        if (empty($user_id) || empty($type)) {
            return response()->json([
                'success' => 0,
                'message' => 'Something went wrong.'
            ]);
        }

        $schedule = auth()->user()->user_schedule;
        if (!$schedule) {
            return response()->json([
                'success' => 0,
                'message' => 'Your schedule not assigned.'
            ]);
        }

        // Employee schedule
        $in_time     = $schedule->in_time;
        $out_time    = $schedule->out_time;
        $total_hours = $schedule->hours;
        $rest_days   = $schedule->restday;

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
            $date = date('Y-m-d', strtotime('-1 day'));
            $day  = date('l', strtotime('-1 day'));
        }

        //Check Public Holiday
        $holiday = PublicHoilday::where('date', $date)->first();
        if ($holiday) {
            return response()->json([
                'success' => 1,
                'message' => "Today is public holiday ($holiday->name)."
            ]);
        }

        // Check Rest Day
        if (in_array($day, $rest_days)) {
            // return response()->json([
            //     'success' => 1,
            //     'message' => 'Today is your restday.'
            // ]);
        }

        $attendance = UserAttendence::where('user_id', $user_id)
            ->whereBetween('created_at', [$date . ' 00:00:00', $date . ' 23:59:59'])
            ->first();

        if ($attendance) {
            if (!empty($attendance->in_status) && !empty($attendance->out_status)) {
                return response()->json([
                    'success' => 1,
                    'message' => 'You have already marked attendance today.'
                ]);
            }

            if ($attendance->status != 1) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Your attendance is marked as ' . UserAttendence::STATUS[$attendance->status] . ' for today.'
                ]);
            }
        }

        // Clock in
        if ($type == 'clock_in') {
            // Check if already clock in
            if ($attendance) {
                return response()->json([
                    'success' => 1,
                    'message' => 'You have already marked in today.'
                ]);
            }

            $time_1 = strtotime($in_time . " +15 minutes");
            $time_2 = strtotime($in_time . " -15 minutes");
            $time_3 = strtotime($in_time . " +30 minutes");
            $points = 0;

            if ($time > $time_1) {
                $in_status = 'Late In';
            } else if ($time <= $time_1 && $time >= $time_2) {
                $in_status = 'In Time';
            } else if ($time < $time_2) {
                $in_status = 'Early In';
            } else {
                $in_status = '';
            }

            if ($time > $time_3) {
                $points = 1;
            }

            UserAttendence::create([
                'user_id'     => $user_id,
                'in_time'     => now(),
                'in_status'   => $in_status,
                'status'      => 1,
                'points'      => $points,
                'in_ip'       => $ip_info['ip'],
                'in_lat'      => $ip_info['latitude'],
                'in_lng'      => $ip_info['longitude'],
                'total_hours' => $total_hours
            ]);

            return response()->json([
                'success' => 1,
                'message' => 'Mark in successfully.'
            ]);
        }

        // Clock out
        if ($type == 'clock_out') {
            //Check if clock in
            if (!$attendance) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Please mark in first.'
                ]);
            }

            // Check if already clock out
            if (isset($attendance->out_time) && !empty($attendance->out_time)) {
                return response()->json([
                    'success' => 1,
                    'message' => 'You have already marked out today.'
                ]);
            }

            $clock_in_time = strtotime($attendance->in_time);
            $shift_out = strtotime(date('Y-m-d', $clock_in_time) . ' ' . $out_time);

            // detect night shift
            if ($shift_out <= $clock_in_time) {
                $shift_out = strtotime('+1 day', $shift_out);
            }

            $time_1 = strtotime('+15 minutes', $shift_out);
            $time_2 = strtotime('-15 minutes', $shift_out);

            if ($time > $time_1) {
                $out_status = 'Late Out';
            } else if ($time <= $time_1 && $time >= $time_2) {
                $out_status = 'On Time';
            } else if ($time < $time_2) {
                $out_status = 'Early Out';
            } else {
                $out_status = '';
            }

            $attendance->out_time     = now();
            $attendance->out_status   = $out_status;
            $attendance->hours        = calculateTotalHour($attendance->in_time, now());
            $attendance->out_ip       = $ip_info['ip'];
            $attendance->out_lat      = $ip_info['latitude'];
            $attendance->out_lng      = $ip_info['longitude'];
            $attendance->is_completed = 1;
            $attendance->save();

            return response()->json([
                'success' => 1,
                'message' => 'Mark out successfully.'
            ]);
        }

        return response()->json([
            'success' => 0,
            'message' => 'Something went wrong try again.'
        ]);
    }

    public function public_holidays(Request $request)
    {
        $user = auth()->user();
        $data['page_title'] = "Public Holidays";

        if ($request->ajax()) {
            $query = PublicHoilday::whereDate('date', '>=', $user->created_at)->orderBy('date', 'desc');

            return DataTables::of($query)
                ->addColumn('date', function ($row) {
                    return showDate($row->date);
                })
                ->addIndexColumn()
                ->make(true);
        }

        return view('user.public_holiday.index', $data);
    }

    public function leaves(Request $request)
    {
        $data['page_title'] = "Leaves";

        if ($request->ajax()) {
            $query = UserLeave::Query();
            $query = $query->where('user_id', auth()->user()->id);
            $query = $query->latest()->get();
            return DataTables::of($query)->addIndexColumn()->make(true);
        }

        return view('user.leave.index', $data);
    }
}
