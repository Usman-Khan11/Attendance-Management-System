<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            $query = UserAttendence::Query();
            $query = $query->where('created_at', '>=', $from)->where('created_at', '<=', $to);
            $query = $query->with('user');
            $query = $query->latest()->get();
            return DataTables::of($query)->addIndexColumn()->make(true);
        }

        return view('admin.dashboard', $data);
    }
}
