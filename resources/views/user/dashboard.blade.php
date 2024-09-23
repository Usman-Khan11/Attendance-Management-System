@extends('user.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xl-12 mb-4 col-lg-12 col-12">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="card-title mb-0">Welcome {{ auth()->user()->name }}!</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                    <i class="ti ti-chart-pie-2 ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">230k</h5>
                                    <small>Present This Month</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-info me-3 p-2">
                                    <i class="ti ti-users ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">8.549k</h5>
                                    <small>Absent This Month</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                    <i class="ti ti-clock ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">1.423k</h5>
                                    <small>Hours</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-success me-3 p-2">
                                    <i class="ti ti-clock ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">$9745</h5>
                                    <small>Total Hours</small>
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
                    <div class="d-flex justify-content-between mb-1">
                        <h5 class="card-title mb-0">My Attendance</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="row">
                            <div class="col-6">
                                <button onclick="markIn(this)" type="button" class="btn btn-primary d-block w-100">Mark In</button>
                            </div>
                            <div class="col-6">
                                <button onclick="markOut(this)" type="button" class="btn btn-warning d-block w-100">Mark Out</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive text-nowrap mt-4">
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