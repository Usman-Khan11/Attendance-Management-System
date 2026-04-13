<div class="row">
    <div class="col-12 text_div">
        <h4 class="text-center mb-0 mt-3 pt-1">
            <b style="text-transform: uppercase; color:#e67d27;">{{ $user->name }}</b>
            Monthly Statement From
            <b style="color:#e67d27;">{{ showDate($from) }}</b> To <b style="color:#e67d27;">{{ showDate($to) }}</b>
        </h4>
    </div>

    <div class="col-12">
        <table class="table table-bordered mt-3 text-center border-dark">
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
                    <td>
                        <h6 class="text-primary mb-0">POINTS</h6>
                    </td>
                </tr>
                <tr>
                    <td><b>{{ formatNumber($total_hours) }} Hrs</b></td>
                    <td><b>{{ formatNumber($working_hours) }} Hrs</b></td>
                    <td><b>{{ formatNumber($present_days) }}</b></td>
                    <td><b>{{ formatNumber($absent_days) }}</b></td>
                    <td><b>{{ formatNumber($late_in) }}</b></td>
                    <td><b>{{ formatNumber($rest_days) }}</b></td>
                    <td><b>{{ formatNumber($public_holidays) }}</b></td>
                    <td><b>{{ formatNumber($leave_days) }}</b></td>
                    <td><b>{{ formatNumber($points > 0 ? -$points : $points) }}</b></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-12">
        <table class="table table-bordered mt-3 text-center border-dark text-dark">
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
                        <h6 class="text-primary mb-0">POINT</h6>
                    </td>
                    <td>
                        <h6 class="text-primary mb-0">STATUS</h6>
                    </td>
                <tr>
            </div>
            @if ($query)
                <div class="col-12">
                    @foreach ($query as $value)
                        <tr>
                            <td>{{ showDate($value->created_at) }}</td>
                            <td>{{ showTime($value->in_time) }}</td>
                            <td>{{ showTime($value->out_time) }}</td>
                            <td>{{ formatNumber($value->hours, 2) }} hrs</td>
                            <td>{{ $value->remarks }}</td>
                            <td>
                                @if (!empty($value->in_status))
                                    {!! attendanceStatus($value->in_status) !!}
                                @else
                                    -
                                @endif
                                /
                                @if (!empty($value->out_status))
                                    {!! attendanceStatus($value->out_status) !!}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $value->points }}</td>
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
