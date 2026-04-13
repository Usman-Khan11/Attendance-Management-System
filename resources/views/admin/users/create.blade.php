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
                <form action="{{ route('admin.user.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" id="name"
                                value="{{ old('name') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email"
                                value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="number" name="phone" class="form-control" id="phone"
                                value="{{ old('phone') }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" id="address"
                                value="{{ old('address') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="in_time" class="form-label">In Time</label>
                            <input type="time" name="in_time" class="form-control" id="in_time"
                                value="{{ old('in_time') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="out_time" class="form-label">Out Time</label>
                            <input type="time" name="out_time" class="form-control" id="out_time"
                                value="{{ old('out_time') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="join_date" class="form-label">Join Date</label>
                            <input type="date" name="join_date" class="form-control" id="join_date"
                                value="{{ old('join_date') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="resign_date" class="form-label">Resign Date</label>
                            <input type="date" name="resign_date" class="form-control" id="resign_date"
                                value="{{ old('resign_date') }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="restday" class="form-label">Rest Days</label>
                            <select class="form-select select2" name="restday[]" multiple>
                                @foreach (\App\Models\UserSchedule::DAYS as $day)
                                    <option @if (in_array($day, old('restday', []))) selected @endif value="{{ $day }}">
                                        {{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" id="username"
                                value="{{ old('username') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="password" value="">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="annual_leave" class="form-label">Annual Leave Quota</label>
                            <input type="number" name="annual_leave" class="form-control" id="annual_leave"
                                value="{{ general()->annual_leave }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="emergency_leave" class="form-label">Emergency Leave Quota</label>
                            <input type="number" name="emergency_leave" class="form-control" id="emergency_leave"
                                value="{{ general()->emergency_leave }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="avatar" class="form-label">Profile Image</label>
                            <input type="file" name="avatar" class="form-control" id="avatar">
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100 d-block">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
