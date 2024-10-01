@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xl-12 mb-4 col-lg-12 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between mb-1">
                        <h5 class="card-title mb-0">Today Attendance</h5>
                    </div>
                </div>
                <div class="card-body">
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
                "url": "{{ route('admin.home') }}",
                "type": "get",
                "data": function(d) {},
            },
            columns: [{
                    data: "DT_RowIndex",
                    title: "S.no",
                },
                {
                    title: 'Username',
                    "render": function(data, type, full, meta) {
                        if (full.user) {
                            return full.user.name;
                        } else {
                            return '-';
                        }
                    }
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
</script>
@endpush