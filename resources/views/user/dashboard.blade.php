@extends('user.layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-md-3 g-2 align-items-center">
                            <div class="col-md-6">
                                <h5 class="card-title mb-0">Welcome {{ auth()->user()->name }}!</h5>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group row g-1">
                                    <label class="col-md-3 col-3 col-form-label">From:</label>
                                    <div class="col-md-9 col-9">
                                        <input type="date" class="form-control" name="from" id="from_date"
                                            value="{{ date('Y-m-01') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group row g-1">
                                    <label class="col-md-3 col-3 col-form-label">To:</label>
                                    <div class="col-md-9 col-9">
                                        <input type="date" class="form-control" name="to" id="to_date"
                                            value="{{ date('Y-m-t') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <button type="button" onclick="filter()" class="btn btn-success d-block w-100">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0">
                            <h5 class="mb-0 me-2 present_this_month">0</h5>
                            <small>Present Days</small>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-success rounded-pill p-2">
                                <i class="far fa-check-circle ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0">
                            <h5 class="mb-0 me-2 absent_this_month">0</h5>
                            <small>Absent Days</small>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-danger rounded-pill p-2">
                                <i class="far fa-times-circle ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0">
                            <h5 class="mb-0 me-2 leave_this_month">0</h5>
                            <small>Leave Days</small>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-warning rounded-pill p-2">
                                <i class="far fa-calendar-minus ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0">
                            <h5 class="mb-0 me-2 holidays_this_month">0</h5>
                            <small>Public Holidays</small>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-secondary rounded-pill p-2">
                                <i class="fa fa-umbrella-beach ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0">
                            <h5 class="mb-0 me-2 restdays_this_month">0</h5>
                            <small>Rest Days</small>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-info rounded-pill p-2">
                                <i class="fa fa-bed ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0">
                            <h5 class="mb-0 me-2 total_late_markins">0</h5>
                            <small>Late Markin</small>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-danger rounded-pill p-2">
                                <i class="fa fa-exclamation-circle ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0">
                            <h5 class="mb-0 me-2 hours">0</h5>
                            <small>Working Hours</small>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-primary rounded-pill p-2">
                                <i class="far fa-hourglass-half ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0">
                            <h5 class="mb-0 me-2 total_hours">0</h5>
                            <small>Total Hours</small>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-primary rounded-pill p-2">
                                <i class="fa fa-stopwatch ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0">
                            <h5 class="mb-0 me-2 points">0</h5>
                            <small>Points Status</small>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-warning rounded-pill p-2">
                                <i class="fa fa-star ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center g-2">
                            <div class="col-md-8">
                                <h5 class="card-title mb-0">My Attendance</h5>
                            </div>
                            <div class="col-md-2 col-6">
                                <button onclick="markAttendence(this, 'clock_in')" type="button"
                                    class="btn btn-primary d-block w-100">
                                    Mark In
                                </button>
                            </div>
                            <div class="col-md-2 col-6">
                                <button onclick="markAttendence(this, 'clock_out')" type="button"
                                    class="btn btn-warning d-block w-100">
                                    Mark Out
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover" id="attendence_table"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let datatable;

        $(document).ready(function() {
            filter();

            datatable = $('#attendence_table').DataTable({
                select: {
                    style: 'api'
                },
                processing: true,
                searching: true,
                serverSide: true,
                lengthChange: true,
                ordering: false,
                pageLength: Number('{{ general()->page_length }}'),
                scrollX: true,
                ajax: {
                    url: "{{ route('user.my_attendance') }}",
                    type: "get",
                    data: function(d) {
                        d.from_date = $("#from_date").val();
                        d.to_date = $("#to_date").val();
                    },
                },
                columns: [{
                        title: 'Date',
                        name: 'created_at',
                        data: 'date'
                    },
                    {
                        title: 'In Time',
                        data: 'in_time'
                    },
                    {
                        title: 'Out Time',
                        data: 'out_time'
                    },
                    {
                        title: 'Hours',
                        data: 'hours'
                    },
                    {
                        title: 'In/Out Status',
                        data: 'in_out_status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        title: 'Remark',
                        data: 'remarks'
                    },
                    {
                        title: 'Points',
                        data: 'points'
                    },
                    {
                        title: 'Status',
                        name: 'status',
                        data: 'status_badge',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });

        function markAttendence(e, type) {
            const text = $(e).text();

            $.ajax({
                url: "{{ route('user.mark_attendence') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    type
                },
                beforeSend: function() {
                    $(e).prop("disabled", true);
                    $(e).html(`<i class="fa fa-spinner fa-spin fa-lg"></i>`);
                },
                success: function(response) {
                    if (response.success == 1) {
                        notify('success', response.message);
                    } else {
                        notify('error', response.message);
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        $.each(errors, function(key, value) {
                            notify('error', value[0]);
                        });
                    } else {
                        notify('error', 'Request failed');
                    }
                },
                complete: function() {
                    $(e).prop("disabled", false);
                    $(e).html(text);

                    filter();
                }
            });
        }

        function filter() {
            let from_date = $("#from_date").val();
            let to_date = $("#to_date").val();

            $.get("{{ route('user.home') }}", {
                from_date,
                to_date
            }, function(res) {
                Object.entries(res).forEach(([key, value]) => {
                    $(`.${key}`).text(value);
                });

                datatable.ajax.reload();
            })
        }
    </script>
@endpush
