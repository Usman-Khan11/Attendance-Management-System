@extends('user.layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 text-start">
                        <h4 class="fw-bold mb-0">{{ $page_title }}</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="number" name="phone" class="form-control"
                                    value="{{ old('phone', $user->phone) }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control"
                                    value="{{ old('address', $user->address) }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Designation</label>
                                <input type="text" name="designation" class="form-control"
                                    value="{{ old('designation', $user->designation) }}">
                            </div>
                        </div>

                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0">Change Username</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('user.profile.username.update') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <div class="row g-md-3 g-0 align-items-center">
                                    <div class="col-md-3 col-12">
                                        <label class="form-label">Old Username</label>
                                    </div>
                                    <div class="col-md-9 col-12">
                                        <input type="text" name="old_username" class="form-control" required
                                            value="{{ old('old_username') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <div class="row g-md-3 g-0 align-items-center">
                                    <div class="col-md-3 col-12">
                                        <label class="form-label">New Username</label>
                                    </div>
                                    <div class="col-md-9 col-12">
                                        <input type="text" name="new_username" class="form-control" required
                                            value="{{ old('new_username') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <div class="row g-md-3 g-0 align-items-center">
                                    <div class="col-md-3 col-12">
                                        <label class="form-label">Confirm New Username</label>
                                    </div>
                                    <div class="col-md-9 col-12">
                                        <input type="text" name="new_username_confirmation" class="form-control" required
                                            value="{{ old('new_username_confirmation') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0">Change Password</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('user.profile.password.update') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <div class="row g-md-3 g-0 align-items-center">
                                    <div class="col-md-3 col-12">
                                        <label class="form-label">Old Password</label>
                                    </div>
                                    <div class="col-md-9 col-12">
                                        <input type="password" name="old_password" class="form-control" required
                                            value="{{ old('old_password') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <div class="row g-md-3 g-0 align-items-center">
                                    <div class="col-md-3 col-12">
                                        <label class="form-label">New Password</label>
                                    </div>
                                    <div class="col-md-9 col-12">
                                        <input type="password" name="new_password" class="form-control" required
                                            value="{{ old('new_password') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <div class="row g-md-3 g-0 align-items-center">
                                    <div class="col-md-3 col-12">
                                        <label class="form-label">Confirm New Password</label>
                                    </div>
                                    <div class="col-md-9 col-12">
                                        <input type="password" name="new_password_confirmation" class="form-control"
                                            required value="{{ old('new_password_confirmation') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
