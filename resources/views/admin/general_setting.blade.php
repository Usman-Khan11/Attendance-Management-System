@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <form method="post" action="{{ route('admin.general_setting.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="card-title m-0">{{ $page_title }}</h4>
                        </div>
                    </div>
                    <hr />
                </div>
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Site Name</label>
                                <input type="text" name="sitename" value="{{ old('sitename', $setting->sitename) }}"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">DataTable length</label>
                                <input type="number" name="page_length"
                                    value="{{ old('page_length', $setting->page_length) }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Primary Color</label>
                                <input type="color" name="primary_color"
                                    value="{{ old('primary_color', $setting->primary_color) }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Half Day Schedule</label>
                            <table class="table table-sm table-bordered border-dark text-center">
                                <thead>
                                    <tr>
                                        <td width="25%" class="fw-bolder">Day</td>
                                        <td width="30%" class="fw-bolder">In Time</td>
                                        <td width="30%" class="fw-bolder">Out Time</td>
                                        <td width="15%" class="fw-bolder">Status</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (getDays() as $day)
                                        <tr>
                                            <td>{{ $day }}</td>
                                            <td>
                                                <input type="time" name="half_day[{{ $day }}][in_time]"
                                                    value="{{ old("half_day.$day.in_time", $setting->half_day[$day]['in_time'] ?? '') }}"
                                                    class="form-control form-control-sm">
                                            </td>
                                            <td>
                                                <input type="time" name="half_day[{{ $day }}][out_time]"
                                                    value="{{ old("half_day.$day.out_time", $setting->half_day[$day]['out_time'] ?? '') }}"
                                                    class="form-control form-control-sm">
                                            </td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="switch-input" value="1"
                                                        {{ ($setting->half_day[$day]['status'] ?? 0) == 1 ? 'checked' : '' }}
                                                        name="half_day[{{ $day }}][status]">
                                                    <span class="switch-toggle-slider">
                                                        <span class="switch-on"></span>
                                                        <span class="switch-off"></span>
                                                    </span>
                                                </label>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary w-100 mt-4">Update Setting</button>
            </div>
        </form>
    </div>
@endsection
