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
                    <a href="{{ route('admin.user') }}" class="btn btn-primary">Go Back</a>
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
                "url": "{{ route('admin.user.login', $user->id) }}",
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
                            month: 'short', // 'Aug'
                            day: '2-digit', // '08'
                            year: 'numeric', // '2024'
                            hour: '2-digit', // '06'
                            minute: '2-digit', // '15'
                            second: '2-digit', // '55'
                            hour12: true // 'pm' for 12-hour format
                        };
                        let formattedDateTime = date.toLocaleString('en-US', options);

                        return formattedDateTime;
                    }
                },
                {
                    title: 'Username',
                    "render": function(data, type, full, meta) {
                        if (full.user) {
                            return full.user.username;
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    title: 'IP',
                    "render": function(data, type, full, meta) {
                        return full.user_ip;
                    }
                },
                {
                    title: 'Location',
                    "render": function(data, type, full, meta) {
                        return full.location;
                    }
                },
                {
                    title: 'Browser',
                    "render": function(data, type, full, meta) {
                        return full.browser;
                    }
                },
                {
                    title: 'OS',
                    "render": function(data, type, full, meta) {
                        return full.os;
                    }
                }
            ]
        });
    });
</script>
@endpush