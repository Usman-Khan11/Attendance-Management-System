<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAttendence;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AttendenceController extends Controller
{
    public function index() {}

    public function summary(Request $request)
    {
        $data['page_title'] = "Summary";
        $data['users'] = User::orderBy('name')->get();

        return view('admin.attendence.summary', $data);
    }

    public function summary_get(Request $request)
    {
        if ($request->ajax()) {
            $data["from"] = $request->from;
            $data["to"] = $request->to;
            $query = null;

            if (isset($request->user) && isset($request->from) && isset($request->to)) {
                $data["user"] = User::find($request->user);
                $query = UserAttendence::where('user_id', $request->user)
                    ->whereBetween('created_at', [$request->from . " 00:00:00", $request->to . " 23:59:59"]);

                $data["working_hours"] = $query->sum('hours');
                $data["present_days"] = $query->clone()->whereIn('status', [1, 5])->count();
                $data["absent_days"] = $query->clone()->where('status', 0)->count();
                $data["rest_days"] = $query->clone()->where('status', 4)->count();
                $data["public_holidays"] = $query->clone()->where('status', 2)->count();
                $data["leave_days"] = $query->clone()->where('status', 3)->count();
                $data["late_in"] = $query->clone()->where('in_status', 'LIKE', '%Late In%')->count();

                $data["total_hours"] = $query->clone()->whereIn('status', [0, 1, 5])->count();
                $data["total_hours"] = $data["total_hours"] * @$data["user"]->user_schedule->hours;

                $data["query"] = $query->with('user')->latest()->get();
            }

            return view('admin.attendence.summary_get', $data);
        }
    }
}
