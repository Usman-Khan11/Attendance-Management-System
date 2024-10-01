<div class="row">
    <div class="col-12 text_div">
        <h5 class="text-center mb-0 mt-3 pt-1"><b style="text-transform: uppercase; color:#e67d27;">{{ $user->name }}</b> Monthly Statement From
            <b style="color:#e67d27;">{{ date("M d, Y", strtotime($from)) }}</b> To <b style="color:#e67d27;">{{ date("M d, Y", strtotime($to)) }}</b>
        </h5>
    </div>

    <div class="col-12">
        <table class="table table-bordered mt-3 text-center">
            <tbody>
                <tr>
                    <td>
                        <h6 class="text-primary mb-0">TOTAL HOURS</h6>
                    </td>
                    <td>
                        <h6 class="text-primary mb-0">WORKING HOURS</h6>
                    </td>
                    <td>
                        <h6 class="text-primary mb-0">PRESENT DAYS</h6>
                    </td>
                    <td>
                        <h6 class="text-primary mb-0">ABSENT DAYS</h6>
                    </td>
                    <td>
                        <h6 class="text-primary mb-0">LATE IN</h6>
                    </td>
                    <td>
                        <h6 class="text-primary mb-0">REST DAYS</h6>
                    </td>
                    <td>
                        <h6 class="text-primary mb-0">PUBLIC HOLIDAY</h6>
                    </td>
                    <td>
                        <h6 class="text-primary mb-0">LEAVE DAYS</h6>
                    </td>
                </tr>
                <tr>
                    <td><b>{{ number_format($total_hours, 2) }} Hrs</b></td>
                    <td><b>{{ number_format($working_hours, 2) }} Hrs</b></td>
                    <td><b>{{ number_format($present_days) }}</b></td>
                    <td><b>{{ number_format($absent_days) }}</b></td>
                    <td><b>{{ number_format($late_in) }}</b></td>
                    <td><b>{{ number_format($rest_days) }}</b></td>
                    <td><b>{{ number_format($public_holidays) }}</b></td>
                    <td><b>{{ number_format($leave_days) }}</b></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-12">
        <table class="table table-bordered mt-3 text-center">
            <div class="col-12">
                <tr>
                    <td>
                        <h6 class="text-primary mb-0">DATE</h6>
                    </td>
                    <td>
                        <h6 class="text-primary mb-0">IN TIME</h6>
                    </td>
                    <td>
                        <h6 class="text-primary mb-0">OUT TIME</h6>
                    </td>
                    <td>
                        <h6 class="text-primary mb-0">HOURS</h6>
                    </td>
                    <td>
                        <h6 class="text-primary mb-0">REMARKS</h6>
                    </td>
                    <td>
                        <h6 class="text-primary mb-0">IN/OUT STATUS</h6>
                    </td>
                    <td>
                        <h6 class="text-primary mb-0">STATUS</h6>
                    </td>
                <tr>
            </div>
            @if($query)
            <div class="col-12">
                @foreach($query as $value)
                <tr>
                    <td>{{ date('M d, Y', strtotime($value->created_at)) }}</td>
                    <td>
                        @if(!empty($value->in_time))
                        {{ date('H:i a', strtotime($value->in_time)) }} &nbsp;
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        @if(!empty($value->out_time))
                        {{ date('H:i a', strtotime($value->out_time)) }} &nbsp;
                        @else
                        -
                        @endif
                    </td>
                    <td>{{ number_format($value->hours, 2) }}</td>
                    <td>{{ $value->remarks }}</td>
                    <td>
                        @if(!empty($value->in_time) && !empty($value->out_time))
                        @php echo attendanceStatus($value->in_status); @endphp
                        /
                        @php echo attendanceStatus($value->out_status); @endphp
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        @if ($value->status == 0)
                        <span class="badge bg-danger">Absent</span>
                        @elseif ($value->status == 1)
                        <span class="badge bg-success">Present</span>
                        @elseif ($value->status == 2)
                        <span class="badge bg-info">Public Holiday</span>
                        @elseif ($value->status == 3)
                        <span class="badge bg-warning">Leave</span>
                        @elseif ($value->status == 4)
                        <span class="badge bg-info">Rest Day</span>
                        @elseif ($value->status == 5)
                        <span class="badge bg-danger">Markout Missing</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </div>
            @endif
        </table>
    </div>
</div>