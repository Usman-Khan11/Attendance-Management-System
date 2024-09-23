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
                    <a href="{{ route('admin.user.create') }}" class="btn btn-primary">Create New User</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table stripe mt-3" id="my_table"></table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        var datatable = $('#my_table').DataTable({
            select: {
                style: 'api'
            },
            "processing": true,
            "searching": true,
            "serverSide": true,
            "lengthChange": false,
            "ordering": false,
            "pageLength": '{{ general()->page_length }}',
            "scrollX": true,
            "ajax": {
                "url": "{{ route('admin.user') }}",
                "type": "get",
                "data": function(d) {},
            },
            columns: [{
                    data: "DT_RowIndex",
                    title: "S.no",
                },
                {
                    data: 'name',
                    title: 'Name'
                },
                {
                    data: 'phone',
                    title: 'Phone'
                },
                {
                    data: 'email',
                    title: 'Email'
                },
                {
                    data: 'username',
                    title: 'username'
                },
                {
                    title: 'In Time',
                    "render": function(data, type, full, meta) {
                        if (full.user_schedule) {
                            let inTime = full.user_schedule.in_time;
                            let [hours, minutes] = inTime.split(":");

                            let date = new Date();
                            date.setHours(hours, minutes);
                            let formattedTime = date.toLocaleTimeString('en-US', {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            });

                            return formattedTime;
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    title: 'Out Time',
                    "render": function(data, type, full, meta) {
                        if (full.user_schedule) {
                            let outTime = full.user_schedule.out_time;
                            let [hours, minutes] = outTime.split(":");

                            let date = new Date();
                            date.setHours(hours, minutes);
                            let formattedTime = date.toLocaleTimeString('en-US', {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            });

                            return formattedTime;
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    title: 'Total Hour',
                    "render": function(data, type, full, meta) {
                        if (full.user_schedule) {
                            return parseFloat(full.user_schedule.hours).toFixed(2);
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    title: 'Join Date',
                    "render": function(data, type, full, meta) {
                        if (full.user_schedule) {
                            let dateTime = full.user_schedule.join_date;
                            let date = new Date(dateTime);
                            let options = {
                                month: 'short',
                                day: '2-digit',
                                year: 'numeric'
                            };
                            let formattedDateTime = date.toLocaleString('en-US', options);

                            return formattedDateTime;
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    title: 'Status',
                    "render": function(data, type, full, meta) {
                        if (full.status == 1) {
                            return `<span class="badge bg-success">Active</span>`;
                        } else {
                            return `<span class="badge bg-danger">In-active</span>`;
                        }
                    }
                },
                {
                    title: 'Actions',
                    "render": function(data, type, full, meta) {
                        let view = `<a href="/admin/user/show/${full.id}" class="btn btn-info btn-sm">View</a> `;
                        let login = `<a href="/admin/user/login/${full.id}" class="btn btn-primary btn-sm">Logins</a> `;
                        let edit = `<a href="/admin/user/edit/${full.id}" class="btn btn-warning btn-sm">Edit</a> `;
                        let del = `<a onclick="return checkDelete()" href="/admin/user/delete/${full.id}" class="btn btn-danger btn-sm">Delete</a> `;

                        return view + login + edit + del;
                    }
                }
            ]
        });
    });
</script>
@endpush