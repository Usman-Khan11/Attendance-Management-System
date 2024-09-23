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
            <form action="{{ route('admin.user.store') }}" method="POST" class="row">
                @csrf
                <div class="form-group mb-3 col-md-4">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}">
                </div>

                <div class="form-group mb-3 col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}">
                </div>

                <div class="form-group mb-3 col-md-4">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" id="address" value="{{ old('address') }}">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="number" name="phone" class="form-control" id="phone" value="{{ old('phone') }}">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="in_time" class="form-label">In Time</label>
                    <input type="time" name="in_time" class="form-control" id="in_time" value="{{ old('in_time') }}">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="out_time" class="form-label">Out Time</label>
                    <input type="time" name="out_time" class="form-control" id="out_time" value="{{ old('out_time') }}">
                </div>

                <!-- <div class="form-group mb-3 col-md-2">
                    <label for="hours" class="form-label">Total Hours</label>
                    <input type="number" step="any" name="hours" class="form-control" id="hours" value="{{ old('hours') }}">
                </div> -->

                <div class="form-group mb-3 col-md-3">
                    <label for="join_date" class="form-label">Join Date</label>
                    <input type="date" name="join_date" class="form-control" id="join_date" value="{{ old('join_date') }}">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="resign_date" class="form-label">Resign Date</label>
                    <input type="date" name="resign_date" class="form-control" id="resign_date" value="{{ old('resign_date') }}">
                </div>

                <div class="form-group mb-3 col-md-6">
                    <label for="restday" class="form-label">Rest Days</label>
                    <select class="form-select select2" name="restday[]" multiple>
                        <option @if(is_array(old('restday')) && in_array("Monday", old('restday'))) selected @endif value="Monday">Monday</option>
                        <option @if(is_array(old('restday')) && in_array("Tuesday", old('restday'))) selected @endif value="Tuesday">Tuesday</option>
                        <option @if(is_array(old('restday')) && in_array("Wednesday", old('restday'))) selected @endif value="Wednesday">Wednesday</option>
                        <option @if(is_array(old('restday')) && in_array("Thursday", old('restday'))) selected @endif value="Thursday">Thursday</option>
                        <option @if(is_array(old('restday')) && in_array("Friday", old('restday'))) selected @endif value="Friday">Friday</option>
                        <option @if(is_array(old('restday')) && in_array("Saturday", old('restday'))) selected @endif value="Saturday">Saturday</option>
                        <option @if(is_array(old('restday')) && in_array("Sunday", old('restday'))) selected @endif value="Sunday">Sunday</option>
                    </select>
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username" value="{{ old('username') }}">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" value="">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="annual_leave" class="form-label">Annual Leave Quota</label>
                    <input type="number" name="annual_leave" class="form-control" id="annual_leave" value="{{ general()->annual_leave }}">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="emergency_leave" class="form-label">Emergency Leave Quota</label>
                    <input type="number" name="emergency_leave" class="form-control" id="emergency_leave" value="{{ general()->emergency_leave }}">
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