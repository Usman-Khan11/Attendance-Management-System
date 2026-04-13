<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use App\Models\UserAttendence;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function home(Request $request)
    {
        $data['page_title'] = "Dashboard";
        $from = date("Y-m-d") . " 00:00:00";
        $to = date("Y-m-d") . " 23:59:59";

        if ($request->ajax()) {
            $query = UserAttendence::with('user')
                ->where('created_at', '>=', $from)->where('created_at', '<=', $to)
                ->latest();

            return DataTables::of($query)
                ->addColumn('user', function ($row) {
                    return $row->user->name ?? '-';
                })
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

        return view('admin.dashboard', $data);
    }

    public function general_setting()
    {
        $data['page_title'] = 'Edit General Setting';
        $data['setting'] = GeneralSetting::firstOrFail();
        return view('admin.general_setting', $data);
    }

    public function general_setting_update(Request $request)
    {
        $request->validate([
            'sitename' => 'required|string|max:150',
        ]);

        $general_setting = GeneralSetting::firstOrFail();
        $general_setting->fill($request->all());
        $general_setting->save();

        return back()->withSuccess('Setting updated successfully!');
    }
}
