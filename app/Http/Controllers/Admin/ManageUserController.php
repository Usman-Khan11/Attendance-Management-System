<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLeave;
use App\Models\UserLogin;
use App\Models\UserSchedule;
use Illuminate\Http\Request;
use App\Models\UserAttendence;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class ManageUserController extends Controller
{
    public function index(Request $request)
    {
        $data['page_title'] = "All Users";

        if ($request->ajax()) {
            $query = User::with('user_schedule')
                ->orderBy('name');

            return DataTables::of($query)
                ->addColumn('in_time', function ($row) {
                    return showTime($row->user_schedule->in_time ?? '');
                })
                ->addColumn('out_time', function ($row) {
                    return showTime($row->user_schedule->out_time ?? '');
                })
                ->addColumn('hours', function ($row) {
                    return round($row->user_schedule->hours ?? 0, 2) . ' hrs';
                })
                ->addColumn('join_date', function ($row) {
                    return showDate($row->user_schedule->join_date ?? '');
                })
                ->addColumn('status_badge', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">In-Active</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '<a href="' . route('admin.user.edit', $row->id) . '" class="btn btn-sm btn-warning">Edit</a>';
                    $btn .= '<a href="' . route('admin.user.delete', $row->id) . '" class="btn btn-sm btn-danger" onclick="return checkDelete()">Delete</a>';

                    return "<div class='btn-group' role='group'>$btn</div>";
                })
                ->rawColumns(['in_time', 'out_time', 'status_badge', 'action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.users.index', $data);
    }

    public function create()
    {
        $data['page_title'] = "Add New User";
        return view('admin.users.create', $data);
    }

    public function edit($id)
    {
        $data['page_title'] = "Edit User";
        $data['user'] = User::where("id", $id)->with('user_schedule')->first();
        return view('admin.users.edit', $data);
    }

    public function show($id)
    {
        $data['user'] = User::where("id", $id)->with('user_schedule')->first();
        $data['page_title'] = $data['user']->name . ' Details';
        return view('admin.users.show', $data);
    }

    public function login(Request $request, $id)
    {
        $data['user'] = User::where("id", $id)->with('user_schedule')->first();
        $data['page_title'] = $data['user']->name . ' Login Details';

        if ($request->ajax()) {
            $query = UserLogin::Query();
            $query = $query->where('user_id', $id);
            $query = $query->with('user');
            $query = $query->latest()->get();
            return DataTables::of($query)->addIndexColumn()->make(true);
        }

        return view('admin.users.login', $data);
    }

    public function delete($id)
    {
        User::where("id", $id)->delete();
        UserSchedule::where("user_id", $id)->delete();
        UserAttendence::where("user_id", $id)->delete();
        UserLeave::where("user_id", $id)->delete();
        UserLogin::where("user_id", $id)->delete();

        return back()->withSuccess('User deleted successfully.');
    }

    private function user_validate($request)
    {
        $rule = [
            // User
            'name'     => 'required|string|max:150',
            'phone'    => 'nullable|string|max:20',
            'email'    => 'required|email|unique:users,email,' . $request->id,
            'address'  => 'nullable|string|max:500',
            'username' => 'required|string|min:3|max:30|unique:users,username,' . $request->id,

            // User Schedule 
            'in_time'     => 'required|date_format:H:i',
            'out_time'    => 'required|date_format:H:i',
            'join_date'   => 'nullable|date|before_or_equal:today',
            'resign_date' => 'nullable|date|after_or_equal:join_date',
            'restday'     => 'nullable|array'
        ];

        if (isset($request->password)) {
            $rule['password'] = 'required|string|min:3|max:20';
        }

        $request->validate($rule);
    }

    public function store(Request $request)
    {
        $this->user_validate($request);

        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $directory = 'assets/uploads/users_avatar/';
            $path = $file->move($directory, $filename);
            $user->avatar = $path;
        }

        if ($user->save()) {
            $user_schedule = new UserSchedule();
            $user_schedule->user_id = $user->id;
            $user_schedule->in_time = $request->in_time;
            $user_schedule->out_time = $request->out_time;
            $user_schedule->join_date = $request->join_date;
            $user_schedule->resign_date = $request->resign_date;
            $user_schedule->hours = calculateTotalHour($request->in_time, $request->out_time);
            $user_schedule->restday = $request->restday;
            $user_schedule->annual_leave = $request->annual_leave;
            $user_schedule->emergency_leave = $request->emergency_leave;
            $user_schedule->save();

            return redirect()->route('admin.user')->withSuccess('User added successfully.');
        }

        return back()->withError('Something went wrong.');
    }

    public function update(Request $request)
    {
        $this->user_validate($request);

        $user = User::find($request->id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->username = $request->username;
        $user->status = $request->status ?? 0;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $directory = 'assets/uploads/users_avatar/';
            $path = $file->move($directory, $filename);
            $user->avatar = $path;
        }

        if ($user->save()) {
            UserSchedule::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'in_time'         => $request->in_time,
                    'out_time'        => $request->out_time,
                    'join_date'       => $request->join_date,
                    'resign_date'     => $request->resign_date,
                    'hours'           => calculateTotalHour($request->in_time, $request->out_time),
                    'restday'         => $request->restday,
                    'annual_leave'    => $request->annual_leave,
                    'emergency_leave' => $request->emergency_leave
                ]
            );

            return redirect()->route('admin.user')->withSuccess('User updated successfully.');
        }

        return back()->withError('Something went wrong.');
    }
}
