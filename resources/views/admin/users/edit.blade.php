@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <form method="post" action="{{ route('admin.user.update') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $user->id }}">

            <div class="card mb-2">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="fw-bold m-0">{{ $page_title }}</h4>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('admin.user') }}" class="btn btn-primary">Go Back</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-2">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="p-3 bg-white text-center">
                                <div class="avatar avatar-xl mx-auto">
                                    <img src="{{ asset($user->avatar) }}" alt="profile-image" class="rounded-circle">
                                </div>
                                <div class="mt-3">
                                    <h5 class="mb-0">{{ $user->name }}</h5>
                                    <small>
                                        Created At:
                                        <strong>{{ showDate($user->created_at) }}</strong>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-2">
                        <div class="card-body">
                            <h5 class="mb-2 text-center">User information</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Username <span class="fw-bold">{{ $user->username }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Status
                                    @if ($user->status == 1)
                                        <span class="badge badge-pill bg-success">Active</span>
                                    @elseif ($user->status == 2)
                                        <span class="badge badge-pill bg-warning">Pending Verification</span>
                                    @else
                                        <span class="badge badge-pill bg-danger">In-Active</span>
                                    @endif
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    In Time <span class="fw-bold">{{ showTime($user->user_schedule->in_time ?? '') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Out Time <span
                                        class="fw-bold">{{ showTime($user->user_schedule->out_time ?? '') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Hours <span
                                        class="fw-bold">{{ round($user->user_schedule->hours ?? 0, 2) . ' hrs' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card mt-2">
                        <div class="card-body">
                            <h5 class="mb-2 text-center">User action</h5>
                            <a href="{{ route('admin.user.show', $user->id) }}" class="btn btn-info w-100 d-block mt-2">
                                View Details
                            </a>
                            <a href="{{ route('admin.user.login', $user->id) }}"
                                class="btn btn-warning w-100 d-block mt-2">
                                Login Logs
                            </a>
                            <a href="" class="btn btn-primary w-100 d-block mt-2">
                                Login as User
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-2">User Profile Info</h5>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            value="{{ old('name', $user->name) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" id="email"
                                            value="{{ old('email', $user->email) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="number" name="phone" class="form-control" id="phone"
                                            value="{{ old('phone', $user->phone) }}">
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" name="address" class="form-control" id="address"
                                            value="{{ old('address', $user->address) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" id="username"
                                            value="{{ old('username', $user->username) }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="in_time" class="form-label">In Time</label>
                                        <input type="time" name="in_time" class="form-control" id="in_time"
                                            value="{{ old('in_time', $user->user_schedule->in_time ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="out_time" class="form-label">Out Time</label>
                                        <input type="time" name="out_time" class="form-control" id="out_time"
                                            value="{{ old('out_time', $user->user_schedule->out_time ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="join_date" class="form-label">Join Date</label>
                                        <input type="date" name="join_date" class="form-control" id="join_date"
                                            value="{{ old('join_date', $user->user_schedule->join_date ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="resign_date" class="form-label">Resign Date</label>
                                        <input type="date" name="resign_date" class="form-control" id="resign_date"
                                            value="{{ old('resign_date', $user->user_schedule->resign_date ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="annual_leave" class="form-label">Annual Leave Quota</label>
                                        <input type="number" name="annual_leave" class="form-control" id="annual_leave"
                                            value="{{ old('annual_leave', $user->user_schedule->annual_leave ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="emergency_leave" class="form-label">Emergency Leave Quota</label>
                                        <input type="number" name="emergency_leave" class="form-control"
                                            id="emergency_leave"
                                            value="{{ old('emergency_leave', $user->user_schedule->emergency_leave ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="restday" class="form-label">Rest Days</label>
                                        <select class="form-select select2" name="restday[]" multiple>
                                            @foreach (\App\Models\UserSchedule::DAYS as $day)
                                                <option @if (in_array($day, old('restday', $user->user_schedule->restday ?? []))) selected @endif
                                                    value="{{ $day }}">{{ $day }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="restday" class="form-label">Status</label>
                                        <select class="form-select select2" name="status">
                                            <option @if (old('status', $user->status) == 1) selected @endif value="1">Active
                                            </option>
                                            <option @if (old('status', $user->status) == 0) selected @endif value="0">
                                                In-Active</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="avatar" class="form-label">Profile Image</label>
                                        <input type="file" name="avatar" class="form-control" id="avatar">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
