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
                        <a href="{{ route('admin.board') }}" class="btn btn-primary">Go Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.board.update') }}" method="POST" class="row">
                    <input type="hidden" name="id" value="{{ $board->id }}">
                    @csrf
                    <div class="form-group mb-3 col-md-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="name"
                            value="{{ $board->name }}">
                    </div>

                    <div class="form-group mb-3 col-md-9">
                        <label for="date" class="form-label">Users</label>
                        <select name="users[]" class="select2 form-select" multiple>
                            @foreach ($users as $user)
                                <option {{ $board->users->contains($user->id) ? 'selected' : '' }}
                                    value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mt-2">
                        <button type="submit" class="btn btn-primary w-100 d-block">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
