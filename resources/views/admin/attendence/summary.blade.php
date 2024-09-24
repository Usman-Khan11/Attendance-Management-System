@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-12 text-start">
                    <h4 class="fw-bold">{{ $page_title }}</h4>
                </div>

                <div class="col-md-3 text-start">
                    <div class="form-group">
                        <label>User</label>
                        <select id="user" class="select2">
                            <option value="" selected disabled>Select User</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3 text-start">
                    <div class="form-group">
                        <label>From</label>
                        <input type="date" id="from" class="form-control" />
                    </div>
                </div>

                <div class="col-md-3 text-start">
                    <div class="form-group">
                        <label>To</label>
                        <input type="date" id="to" class="form-control" />
                    </div>
                </div>

                <div class="col-md-3 text-start">
                    <div class="form-group">
                        <button type="button" id="filter_btn" class="btn btn-primary d-block w-100">Filter</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body"></div>
    </div>
</div>
@endsection

@push('script')
<script>
    $("#filter_btn").click(function() {
        let user = $("#user").val();
        let from = $("#from").val();
        let to = $("#to").val();

        if (user && from && to) {
            $.get("{{ route('admin.attendence.summary.get') }}", {
                _token: '{{ csrf_token() }}',
                user,
                from,
                to
            }, function(res) {
                if (res) {
                    $(".card-body").html(res)
                }
            })
        }
    })
</script>
@endpush