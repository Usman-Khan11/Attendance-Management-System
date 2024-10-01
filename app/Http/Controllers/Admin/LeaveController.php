<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLeave;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $data['page_title'] = "Leaves";

        if ($request->ajax()) {
            $query = UserLeave::Query();
            $query = $query->with('user');
            $query = $query->latest()->get();
            return DataTables::of($query)->addIndexColumn()->make(true);
        }

        return view('admin.leave.index', $data);
    }

    public function create()
    {
        $data['page_title'] = "Add New Leave";
        $data['users'] = User::orderBy('name', 'asc')->get();
        return view('admin.leave.create', $data);
    }

    public function edit($id)
    {
        $data['page_title'] = "Edit Leave";
        $data['leave'] = UserLeave::where("id", $id)->first();
        $data['users'] = User::orderBy('name', 'asc')->get();
        return view('admin.leave.edit', $data);
    }

    public function delete($id)
    {
        UserLeave::where("id", $id)->delete();
        return back()->withSuccess('Leave deleted successfully.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'title' => 'nullable|string|max:255',
            'type' => 'required|string|max:50',
            // 'days' => 'required|string|max:10',
            // 'dates' => 'required|array',
            'year' => 'required|string|max:10',
            'from' => 'required|date',
            'to' => 'required|date',
        ]);

        $dates = getAllDates($request->from, $request->to);

        $leave = new UserLeave();
        $leave->user_id = $request->user_id;
        $leave->title = $request->title;
        $leave->type = $request->type;
        $leave->days = count($dates);
        $leave->dates = $dates;
        $leave->year = $request->year;

        if ($leave->save()) {
            return redirect()->route('admin.leave')->withSuccess('Leave added successfully.');
        }

        return back()->withError('Something went wrong.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'title' => 'nullable|string|max:255',
            'type' => 'required|string|max:50',
            'year' => 'required|string|max:10',
            'from' => 'required|date',
            'to' => 'required|date',
        ]);

        $dates = getAllDates($request->from, $request->to);

        $leave = UserLeave::find($request->id);
        $leave->user_id = $request->user_id;
        $leave->title = $request->title;
        $leave->type = $request->type;
        $leave->days = count($dates);
        $leave->dates = $dates;
        $leave->year = $request->year;
        $leave->status = $request->status;

        if ($leave->save()) {
            return redirect()->route('admin.leave')->withSuccess('Leave updated successfully.');
        }

        return back()->withError('Something went wrong.');
    }
}
