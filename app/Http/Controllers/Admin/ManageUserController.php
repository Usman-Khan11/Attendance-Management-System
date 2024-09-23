<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLeave;
use App\Models\UserLogin;
use App\Models\UserSchedule;
use Illuminate\Http\Request;
use App\Models\UserAttendence;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class ManageUserController extends Controller
{
    public function index(Request $request)
    {
        $data['page_title'] = "All Users";

        if ($request->ajax()) {
            $query = User::Query();
            $query = $query->with('user_schedule');
            $query = $query->orderBy('name')->get();
            return DataTables::of($query)->addIndexColumn()->make(true);
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            // User
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:100|unique:users',
            'address' => 'nullable|string',
            'username' => 'required|string|max:100|unique:users',
            'password' => 'required|string|min:6',

            // User Schedule 
            'in_time' => 'required|max:20',
            'out_time' => 'required|max:20',
            'join_date' => 'nullable|date|before_or_equal:today',
            'resign_date' => 'nullable|date|after_or_equal:join_date',
            // 'hours' => 'required|numeric|min:0|max:99999999999999.9999',
            'restday' => 'nullable|array'
        ]);

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
        $validated = $request->validate([
            // User
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:20',
            'email' => ['required', 'email', 'max:100', Rule::unique('users')->ignore($request->id)],
            'address' => 'nullable|string',
            'username' => ['required', 'string', 'max:100', Rule::unique('users')->ignore($request->id)],
            'password' => 'nullable|string|min:6',

            // User Schedule 
            'in_time' => 'required|max:20',
            'out_time' => 'required|max:20',
            'join_date' => 'nullable|date|before_or_equal:today',
            'resign_date' => 'nullable|date|after_or_equal:join_date',
            // 'hours' => 'required|numeric|min:0|max:99999999999999.9999',
            'restday' => 'nullable|array'
        ]);

        $user = User::find($request->id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->username = $request->username;
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $directory = 'assets/uploads/users_avatar/';
            $path = $file->move($directory, $filename);
            $user->avatar = $path;
        }

        if ($user->save()) {
            $user_schedule = UserSchedule::where('user_id', $user->id)->first();
            $user_schedule->in_time = $request->in_time;
            $user_schedule->out_time = $request->out_time;
            $user_schedule->join_date = $request->join_date;
            $user_schedule->resign_date = $request->resign_date;
            $user_schedule->hours = calculateTotalHour($request->in_time, $request->out_time);
            $user_schedule->restday = $request->restday;
            $user_schedule->annual_leave = $request->annual_leave;
            $user_schedule->emergency_leave = $request->emergency_leave;
            $user_schedule->save();

            return redirect()->route('admin.user')->withSuccess('User updated successfully.');
        }

        return back()->withError('Something went wrong.');
    }
}
