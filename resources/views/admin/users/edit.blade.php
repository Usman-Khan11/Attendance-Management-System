@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-8 text-start">
                    <h4 class="fw-bold">{{ $page_title }}</h4>
                </div>
                <div class="col-4 text-end">
                    <a href="{{ route('admin.user') }}" class="btn btn-primary">Go Back</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.user.update') }}" method="POST" class="row">
                <input type="hidden" name="id" value="{{ $user->id }}">
                @csrf
                <div class="form-group mb-3 col-md-4">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ $user->name }}">
                </div>

                <div class="form-group mb-3 col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" value="{{ $user->email }}">
                </div>

                <div class="form-group mb-3 col-md-4">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" id="address" value="{{ $user->address }}">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="number" name="phone" class="form-control" id="phone" value="{{ $user->phone }}">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="in_time" class="form-label">In Time</label>
                    <input type="time" name="in_time" class="form-control" id="in_time" value="{{ @$user->user_schedule->in_time }}">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="out_time" class="form-label">Out Time</label>
                    <input type="time" name="out_time" class="form-control" id="out_time" value="{{ @$user->user_schedule->out_time }}">
                </div>

                <!-- <div class="form-group mb-3 col-md-2">
                    <label for="hours" class="form-label">Total Hours</label>
                    <input type="number" step="any" name="hours" class="form-control" id="hours" value="{{ @$user->user_schedule->hours }}">
                </div> -->

                <div class="form-group mb-3 col-md-3">
                    <label for="join_date" class="form-label">Join Date</label>
                    <input type="date" name="join_date" class="form-control" id="join_date" value="{{ @$user->user_schedule->join_date }}">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="resign_date" class="form-label">Resign Date</label>
                    <input type="date" name="resign_date" class="form-control" id="resign_date" value="{{ @$user->user_schedule->resign_date }}">
                </div>

                <div class="form-group mb-3 col-md-6">
                    <label for="restday" class="form-label">Rest Days</label>
                    <select class="form-select select2" name="restday[]" multiple>
                        <option @if(in_array("Monday", @$user->user_schedule->restday)) selected @endif value="Monday">Monday</option>
                        <option @if(in_array("Tuesday", @$user->user_schedule->restday)) selected @endif value="Tuesday">Tuesday</option>
                        <option @if(in_array("Wednesday", @$user->user_schedule->restday)) selected @endif value="Wednesday">Wednesday</option>
                        <option @if(in_array("Thursday", @$user->user_schedule->restday)) selected @endif value="Thursday">Thursday</option>
                        <option @if(in_array("Friday", @$user->user_schedule->restday)) selected @endif value="Friday">Friday</option>
                        <option @if(in_array("Saturday", @$user->user_schedule->restday)) selected @endif value="Saturday">Saturday</option>
                        <option @if(in_array("Sunday", @$user->user_schedule->restday)) selected @endif value="Sunday">Sunday</option>
                    </select>
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username" value="{{ $user->username }}">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" value="">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="annual_leave" class="form-label">Annual Leave Quota</label>
                    <input type="number" name="annual_leave" class="form-control" id="annual_leave" value="{{ @$user->user_schedule->annual_leave }}">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="emergency_leave" class="form-label">Emergency Leave Quota</label>
                    <input type="number" name="emergency_leave" class="form-control" id="emergency_leave" value="{{ @$user->user_schedule->emergency_leave }}">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="avatar" class="form-label">Profile Image</label>
                    <input type="file" name="avatar" class="form-control" id="avatar">
                </div>

                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-primary w-100 d-block">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection