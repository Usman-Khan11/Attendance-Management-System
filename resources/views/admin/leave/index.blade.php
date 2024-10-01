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
                    <a href="{{ route('admin.leave.create') }}" class="btn btn-primary">Add New Leave</a>
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
                "url": "{{ route('admin.leave') }}",
                "type": "get",
                "data": function(d) {},
            },
            columns: [{
                    data: "DT_RowIndex",
                    title: "S.no",
                },
                {
                    data: 'title',
                    title: 'title'
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
                    data: 'days',
                    title: 'days'
                },
                {
                    data: 'year',
                    title: 'year'
                },
                {
                    data: 'type',
                    title: 'type'
                },
                {
                    title: 'Created At',
                    "render": function(data, type, full, meta) {
                        let dateTime = full.created_at;
                        let date = new Date(dateTime);
                        let options = {
                            month: 'short',
                            day: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            hour12: true
                        };
                        let formattedDateTime = date.toLocaleString('en-US', options);

                        return formattedDateTime;
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
                        let edit = `<a href="/admin/leave/edit/${full.id}" class="btn btn-warning btn-sm">Edit</a> `;
                        let del = `<a onclick="return checkDelete()" href="/admin/leave/delete/${full.id}" class="btn btn-danger btn-sm">Delete</a> `;

                        return edit + del;
                    }
                }
            ]
        });
    });
</script>
@endpush