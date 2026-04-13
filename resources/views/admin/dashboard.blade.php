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
                processing: true,
                searching: true,
                serverSide: true,
                lengthChange: true,
                ordering: false,
                pageLength: Number('{{ general()->page_length }}'),
                scrollX: true,
                ajax: {
                    url: "{{ route('admin.home') }}",
                    type: "get",
                    data: function(d) {},
                },
                columns: [{
                        title: 'User',
                        name: 'user.name',
                        data: 'user'
                    },
                    {
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
                        title: 'Points',
                        data: 'points'
                    },
                    {
                        title: 'Remark',
                        data: 'remarks'
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
    </script>
@endpush
