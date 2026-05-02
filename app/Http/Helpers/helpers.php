<?php

use Carbon\Carbon;
use App\Models\GeneralSetting;
use App\Models\UserAttendence;
use Illuminate\Support\Facades\Http;
use Jenssegers\Agent\Agent;

function menuActive($routeName, $type = null)
{
    if ($type == 3) {
        $class = 'side-menu-open';
    } elseif ($type == 2) {
        $class = 'sidebar-submenu__open';
    } else {
        $class = 'active';
    }
    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) {
                return $class;
            }
        }
    } elseif (request()->routeIs($routeName)) {
        return $class;
    }
}

function diffForHumans($date)
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->diffForHumans();
}

function calculateTotalHour($in, $out)
{
    if (empty($in) || empty($out)) {
        return 0;
    }

    $start_time = strtotime($in);
    $end_time = strtotime($out);
    $diff_in_seconds = $end_time - $start_time;
    $total_hours = $diff_in_seconds / 3600;
    return $total_hours;
}

function general()
{
    return GeneralSetting::first();
}

function getAllDates($from, $to)
{
    if (empty($from) || empty($to)) {
        return [];
    }

    $startDate = strtotime($from);
    $endDate = strtotime($to);
    $datesArray = [];

    for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate = strtotime('+1 day', $currentDate)) {
        $datesArray[] = date('Y-m-d', $currentDate);
    }

    return $datesArray;
}

function attendanceStatus($status)
{
    $badgeClass = null;

    if (in_array($status, ["Late In", "Early Out"])) {
        $badgeClass = 'bg-danger';
    } else if (in_array($status, ["In Time", "On Time"])) {
        $badgeClass = 'bg-success';
    } else if (in_array($status, ["Early In", "Late Out"])) {
        $badgeClass = "bg-warning";
    }

    $status = '<span class="badge fw-bold bg-glow ' . $badgeClass . '">' . $status . '</span>';
    return $status;
}

function showDate($date, $format = 'd/M/Y')
{
    if (!$date) {
        return '';
    }

    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->translatedFormat($format);
}

function showTime($date, $format = 'h:i A')
{
    if (!$date) {
        return '';
    }

    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->translatedFormat($format);
}

function showDateTime($date, $format = 'd/M/Y h:i A')
{
    if (!$date) {
        return '';
    }

    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->translatedFormat($format);
}

function markUserAttendence($user_id, $remarks, $status, $is_completed = 0, $total_hours = 0)
{
    $attendence = new UserAttendence();
    $attendence->user_id = $user_id;
    $attendence->remarks =  $remarks ?? null;
    $attendence->status  = $status;
    $attendence->is_completed = $is_completed;
    $attendence->total_hours = $total_hours;

    if ($status == 0) {
        $attendence->points = 4;
    }

    $attendence->save();
}

function getUserIP()
{
    $ip = '';

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ipList[0]);
    } else if (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

function getIpInfo($ip = null)
{
    if (empty($ip)) {
        $ip = getUserIP();
    }

    try {
        $agent = new Agent();

        $response = Http::get("https://api.ipbase.com/v1/json/{$ip}");
        $data = $response->object();

        return [
            'ip'           => $ip,
            'city'         => $data->city ?? null,
            'state'        => $data->region_name ?? null,
            'browser'      => $agent->browser(),
            'os'           => $agent->platform(),
            'longitude'    => $data->longitude ?? null,
            'latitude'     => $data->latitude ?? null,
            'country'      => $data->country_name ?? null,
            'country_code' => $data->country_code ?? null,
            'timezone'     => $data->time_zone ?? null,
            'zipcode'      => $data->zip_code ?? null,
            'location'     => implode(' - ', array_filter([
                $data->city ?? null,
                $data->region_name ?? null,
                $data->zip_code ?? null
            ]))
        ];
    } catch (\Exception $e) {
        return null;
    }
}

function formatNumber($number)
{
    $decimal = 2;
    $number = round($number, $decimal);

    return ($number == (int)$number) ? number_format($number, 0) : number_format($number, $decimal);
}

function getMapLink($lat, $lng)
{
    return "https://www.google.com/maps?q={$lat},{$lng}";
}

function getDays()
{
    return ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
}
