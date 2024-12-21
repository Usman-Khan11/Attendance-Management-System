@extends('user.layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xl-12 mb-4 col-lg-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-md-0 mb-2">
                                <h5 class="card-title mb-0">Welcome {{ auth()->user()->name }}!</h5>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="form-group row g-1">
                                    <label class="col-md-3 col-3 col-form-label">From:</label>
                                    <div class="col-md-9 col-9">
                                        <input type="date" class="form-control" name="from">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="form-group row g-1">
                                    <label class="col-md-2 col-2 col-form-label">To:</label>
                                    <div class="col-md-10 col-10">
                                        <input type="date" class="form-control" name="to">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-12 mt-2 mt-md-0">
                                <button type="button" class="btn btn-success d-block w-100" id="filter">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-success me-3 p-2">
                                        <i class="ti ti-check ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ number_format($present_this_month) }}</h5>
                                        <small>Presents</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                        <i class="ti ti-ban ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ number_format($absent_this_month) }}</h5>
                                        <small>Absents</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-info me-3 p-2">
                                        <i class="ti ti-clock ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ number_format($hours, 2) }}</h5>
                                        <small>Hours</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                        <i class="ti ti-clock ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ number_format($total_hours, 2) }}</h5>
                                        <small>Total Hours</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                        <i class="ti ti-bell ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ number_format($total_late_markins) }}</h5>
                                        <small>Late Markin</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-warning me-3 p-2">
                                        <i class="ti ti-arrow-right ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ number_format($total_late_markouts) }}</h5>
                                        <small>Late Markout</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-warning me-3 p-2">
                                        <i class="fa fa-angle-double-left ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ number_format($total_early_markins) }}</h5>
                                        <small>Early Markin</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                        <i class="fa fa-angle-double-right ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ number_format($total_early_markouts) }}</h5>
                                        <small>Early Markout</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-success me-3 p-2">
                                        <i class="ti ti-check ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ number_format($total_on_time_markins) }}</h5>
                                        <small>OnTime Markin</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-success me-3 p-2">
                                        <i class="ti ti-check ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ number_format($total_on_time_markouts) }}</h5>
                                        <small>OnTime Markout</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-12 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                        <i class="ti ti-dashboard ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ round($overall_attendance_progess, 2) }}%</h5>
                                        <small>Overall Progress</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12 mb-4 col-lg-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center g-1">
                            <div class="col-md-4 mb-md-0 mb-2">
                                <h5 class="card-title mb-0">My Attendance</h5>
                            </div>
                            <div class="col-md-4 col-6">
                                <button onclick="markIn(this)" type="button" class="btn btn-primary d-block w-100">
                                    Mark In
                                </button>
                            </div>
                            <div class="col-md-4 col-6">
                                <button onclick="markOut(this)" type="button" class="btn btn-warning d-block w-100">
                                    Mark Out
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            <table class="table stripe" id="attendence_table"></table>
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
            datatable = $('#attendence_table').DataTable({
                select: {
                    style: 'api'
                },
                "processing": true,
                "searching": false,
                "serverSide": true,
                "lengthChange": false,
                "ordering": false,
                "pageLength": '{{ general()->page_length }}',
                "scrollX": true,
                "ajax": {
                    "url": "{{ route('user.my_attendance') }}",
                    "type": "get",
                    "data": function(d) {},
                },
                columns: [{
                        data: "DT_RowIndex",
                        title: "S.no",
                    },
                    {
                        title: 'Date',
                        "render": function(data, type, full, meta) {
                            let dateTime = full.created_at;
                            let date = new Date(dateTime);
                            let options = {
                                month: 'short',
                                day: '2-digit',
                                year: 'numeric'
                            };
                            let formattedDateTime = date.toLocaleString('en-US', options);

                            return formattedDateTime;
                        }
                    },
                    {
                        title: 'In Time',
                        "render": function(data, type, full, meta) {
                            let dateTime = full.in_time;
                            if (!dateTime) {
                                return '-';
                            }
                            let date = new Date(dateTime);
                            let options = {
                                // month: 'short',
                                // day: '2-digit',
                                // year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit',
                                hour12: true
                            };
                            let formattedDateTime = date.toLocaleString('en-US', options);
                            let status = attendanceStatus(full.in_status);

                            return formattedDateTime + '<br/>' + status;
                        }
                    },
                    {
                        title: 'Out Time',
                        "render": function(data, type, full, meta) {
                            let dateTime = full.out_time;
                            if (!dateTime) {
                                return '-';
                            }
                            let date = new Date(dateTime);
                            let options = {
                                // month: 'short',
                                // day: '2-digit',
                                // year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit',
                                hour12: true
                            };
                            let formattedDateTime = date.toLocaleString('en-US', options);
                            let status = attendanceStatus(full.out_status);

                            return formattedDateTime + '<br/>' + status;
                        }
                    },
                    {
                        title: 'Hours',
                        "render": function(data, type, full, meta) {
                            let hours = full.hours;
                            return hours + ' hrs';
                        }
                    },
                    {
                        title: 'Remark',
                        "render": function(data, type, full, meta) {
                            let remarks = full.remarks;
                            return remarks;
                        }
                    },
                    {
                        title: 'Status',
                        "render": function(data, type, full, meta) {
                            if (full.status == 0) {
                                return `<span class="badge bg-danger">Absent</span>`;
                            } else if (full.status == 1) {
                                return `<span class="badge bg-success">Present</span>`;
                            } else if (full.status == 2) {
                                return `<span class="badge bg-info">Public Holiday</span>`;
                            } else if (full.status == 3) {
                                return `<span class="badge bg-warning">Leave</span>`;
                            } else if (full.status == 4) {
                                return `<span class="badge bg-info">Rest Day</span>`;
                            } else if (full.status == 5) {
                                return `<span class="badge bg-danger">Markout Missing</span>`;
                            }
                        }
                    }
                ]
            });
        });

        function markIn(e) {
            $(e).attr("disabled", true);
            $(e).html(`<i class="fa fa-spinner fa-spin fa-lg"></i>`);

            $.post("{{ route('user.mark_in') }}", {
                _token: "{{ csrf_token() }}"
            }, function(res) {
                if (res.success) {
                    iziToast.success({
                        message: res.success,
                        position: "topRight"
                    });

                    datatable.ajax.reload();
                } else if (res.error) {
                    iziToast.error({
                        message: res.error,
                        position: "topRight"
                    });
                }

                $(e).attr("disabled", false);
                $(e).html(`Mark In`);
            })
        }

        function markOut(e) {
            $(e).attr("disabled", true);
            $(e).html(`<i class="fa fa-spinner fa-spin fa-lg"></i>`);

            $.post("{{ route('user.mark_out') }}", {
                _token: "{{ csrf_token() }}"
            }, function(res) {
                if (res.success) {
                    iziToast.success({
                        message: res.success,
                        position: "topRight"
                    });

                    datatable.ajax.reload();
                } else if (res.error) {
                    iziToast.error({
                        message: res.error,
                        position: "topRight"
                    });
                }

                $(e).attr("disabled", false);
                $(e).html(`Mark Out`);
            })
        }
    </script>
@endpush
