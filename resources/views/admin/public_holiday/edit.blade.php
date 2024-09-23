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
                    <a href="{{ route('admin.public_holiday') }}" class="btn btn-primary">Go Back</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.public_holiday.update') }}" method="POST" class="row">
                <input type="hidden" name="id" value="{{ $public_holiday->id }}">
                @csrf
                <div class="form-group mb-3 col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ $public_holiday->name }}">
                </div>

                <div class="form-group mb-3 col-md-6">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" id="date" value="{{ $public_holiday->date }}">
                </div>

                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-primary w-100 d-block">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection