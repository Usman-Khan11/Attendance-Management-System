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
                    <a href="{{ route('admin.leave') }}" class="btn btn-primary">Go Back</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.leave.store') }}" method="POST" class="row">
                @csrf
                <div class="form-group mb-3 col-md-4">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="user_id" class="form-label">User</label>
                    <select name="user_id" class="form-select select2" id="user_id">
                        <option value="" selected disabled>Select User</option>
                        @foreach($users as $user)
                        <option @if(old('user_id')==$user->id) selected @endif value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="type" class="form-label">Leave Type</label>
                    <select name="type" class="form-select select2" id="type">
                        <option @if(old('type')=="Annual Leave" ) selected @endif value="Annual Leave">Annual Leave</option>
                        <option @if(old('type')=="Emergency Leave" ) selected @endif value="Emergency Leave">Emergency Leave</option>
                    </select>
                </div>

                <div class="form-group mb-3 col-md-2">
                    <label for="year" class="form-label">Year</label>
                    <input type="number" name="year" class="form-control" id="year" value="{{ old('year', date('Y')) }}">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="from" class="form-label">From</label>
                    <input type="date" name="from" class="form-control" id="from" value="{{ old('from') }}">
                </div>

                <div class="form-group mb-3 col-md-3">
                    <label for="to" class="form-label">To</label>
                    <input type="date" name="to" class="form-control" id="to" value="{{ old('to') }}">
                </div>

                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-primary w-100 d-block">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection