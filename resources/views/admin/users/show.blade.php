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
            <table class="table table-bordered">
                <tr>
                    <th width="20%">
                        <h6 class="mb-0">ID</h6>
                    </th>
                    <td width="80%">{{ $user->id }}</td>
                </tr>
                <tr>
                    <th width="20%">
                        <h6 class="mb-0">Name</h6>
                    </th>
                    <td width="80%">{{ $user->name }}</td>
                </tr>
                <tr>
                    <th width="20%">
                        <h6 class="mb-0">Email</h6>
                    </th>
                    <td width="80%">{{ $user->email }}</td>
                </tr>
                <tr>
                    <th width="20%">
                        <h6 class="mb-0">Username</h6>
                    </th>
                    <td width="80%">{{ $user->username }}</td>
                </tr>
                <tr>
                    <th width="20%">
                        <h6 class="mb-0">Phone</h6>
                    </th>
                    <td width="80%">{{ $user->phone }}</td>
                </tr>
                <tr>
                    <th width="20%">
                        <h6 class="mb-0">Address</h6>
                    </th>
                    <td width="80%">{{ $user->address }}</td>
                </tr>
                <tr>
                    <th width="20%">
                        <h6 class="mb-0">In Time</h6>
                    </th>
                    <td width="80%">{{ date("h:i A", strtotime($user->user_schedule->in_time)) }}</td>
                </tr>
                <tr>
                    <th width="20%">
                        <h6 class="mb-0">Out Time</h6>
                    </th>
                    <td width="80%">{{ date("h:i A", strtotime($user->user_schedule->out_time)) }}</td>
                </tr>
                <tr>
                    <th width="20%">
                        <h6 class="mb-0">Total Hour</h6>
                    </th>
                    <td width="80%">{{ number_format($user->user_schedule->hours, 2) }}</td>
                </tr>
                <tr>
                    <th width="20%">
                        <h6 class="mb-0">Join Date</h6>
                    </th>
                    <td width="80%">{{ date("M d, Y", strtotime($user->user_schedule->join_date)) }}</td>
                </tr>
                <tr>
                    <th width="20%">
                        <h6 class="mb-0">Resign Date</h6>
                    </th>
                    <td width="80%">{{ (!empty($user->user_schedule->resign_date)) ? date("M d, Y", strtotime($user->user_schedule->resign_date)) : '-' }}</td>
                </tr>
                <tr>
                    <th width="20%">
                        <h6 class="mb-0">Rest Days</h6>
                    </th>
                    <td width="80%">
                        @foreach($user->user_schedule->restday as $key => $restday)
                        @if($key > 0){{ ' | ' }}@endif
                        {{ $restday }}
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th width="20%">
                        <h6 class="mb-0">Annual Leave</h6>
                    </th>
                    <td width="80%">{{ $user->user_schedule->annual_leave }}</td>
                </tr>
                <tr>
                    <th width="20%">
                        <h6 class="mb-0">Emergency Leave</h6>
                    </th>
                    <td width="80%">{{ $user->user_schedule->emergency_leave }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection