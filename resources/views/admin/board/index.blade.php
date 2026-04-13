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
                        <a href="{{ route('admin.board.create') }}" class="btn btn-primary">Add New Board</a>
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
                processing: true,
                searching: true,
                serverSide: true,
                lengthChange: true,
                ordering: false,
                pageLength: Number('{{ general()->page_length }}'),
                scrollX: true,
                ajax: {
                    url: "{{ route('admin.board') }}",
                    type: "get",
                    data: function(d) {},
                },
                columns: [{
                        data: "DT_RowIndex",
                        title: "S.no",
                        searchable: false
                    },
                    {
                        title: 'Name',
                        data: 'name'
                    },
                    {
                        title: 'Members',
                        data: 'members',
                        searchable: false
                    },
                    {
                        title: 'Created At',
                        data: 'date',
                        searchable: false
                    },
                    {
                        title: 'Actions',
                        data: 'action',
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
