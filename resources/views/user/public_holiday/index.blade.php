@extends('user.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-8 text-start">
                    <h4 class="fw-bold">{{ $page_title }}</h4>
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
                "url": "{{ route('user.public_holidays') }}",
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
                    title: 'Date',
                    "render": function(data, type, full, meta) {
                        let dateTime = full.date;
                        let date = new Date(dateTime);
                        let options = {
                            month: 'short',
                            day: '2-digit',
                            year: 'numeric'
                        };
                        let formattedDateTime = date.toLocaleString('en-US', options);

                        return formattedDateTime;
                    }
                }
            ]
        });
    });
</script>
@endpush