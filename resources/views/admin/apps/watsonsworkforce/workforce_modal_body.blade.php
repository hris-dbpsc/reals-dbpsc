<dl class="row mb-0">
    <dd class="col-sm-12 text-center"><strong>{{ $workforce->requesttype }}</strong></dd>
    <dt class="col-sm-3">Request By</dt>
    <dd class="col-sm-9">{{ $workforce->requestby }}</dd>
    <dt class="col-sm-3">Date</dt>
    <dd class="col-sm-9">{{ $workforce->requestdate }}</dd>
    <dt class="col-sm-3">Status</dt>
    <dd class="col-sm-9">{{ $workforce->status }}</dd>
    @if(!empty($workforce->branchtarget))
    <dt class="col-sm-3">Branch</dt>
    <dd class="col-sm-9">{{ $workforce->branchtarget }}</dd>
    @endif
    @if(!empty($workforce->branchtransferfrom))
    <dt class="col-sm-3">Transfer From</dt>
    <dd class="col-sm-9">{{ $workforce->branchtransferfrom }}</dd>
    @endif
    @if(!empty($workforce->branchtransferto))
    <dt class="col-sm-3">Transfer To</dt>
    <dd class="col-sm-9">{{ $workforce->branchtransferto }}</dd>
    @endif
    @if(!empty($workforce->employeestransferred))
    <dt class="col-sm-3">Employee Transferred</dt>
    <dd class="col-sm-9">{{ $workforce->employeestransferred }}</dd>
    @endif
    @if(!empty($workforce->reshuffle_number))
    <dt class="col-sm-3">Reshuffle Number</dt>
    <dd class="col-sm-9">{{ $workforce->reshuffle_number }}</dd>
    @endif
    @if(!empty($workforce->employeesreshuffled) || !empty($workforce->branchreshufflefrom) || !empty($workforce->branchreshuffleto))
    <dt class="col-sm-3">Reshuffle Details</dt>
    <dd class="col-sm-9">
        @php
        $employees = is_array($workforce->employeesreshuffled) ? $workforce->employeesreshuffled : (empty($workforce->employeesreshuffled) ? [] : explode(',', $workforce->employeesreshuffled));
        $froms = is_array($workforce->branchreshufflefrom) ? $workforce->branchreshufflefrom : (empty($workforce->branchreshufflefrom) ? [] : explode(',', $workforce->branchreshufflefrom));
        $tos = is_array($workforce->branchreshuffleto) ? $workforce->branchreshuffleto : (empty($workforce->branchreshuffleto) ? [] : explode(',', $workforce->branchreshuffleto));
        $max = max(count($employees), count($froms), count($tos));
        @endphp
        @if($max > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Employee Reshuffled</th>
                        <th>Reshuffle From</th>
                        <th>Reshuffle To</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < $max; $i++)
                        <tr>
                        <td>{{ isset($employees[$i]) ? trim($employees[$i]) : '' }}</td>
                        <td>{{ isset($froms[$i]) ? trim($froms[$i]) : '' }}</td>
                        <td>{{ isset($tos[$i]) ? trim($tos[$i]) : '' }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        @endif
    </dd>
    @endif
    @if(!empty($workforce->clientremarks))
    <dt class="col-sm-3">Client Remarks</dt>
    <dd class="col-sm-9">{{ !empty($workforce->clientremarks) ? $workforce->clientremarks : '-' }}</dd>
    @endif
    @if(!empty($workforce->acknowledgedby))
    <dt class="col-sm-3">Acknowledged by:</dt>
    <dd class="col-sm-9">{{ $workforce->acknowledgedby }} {{ $workforce->acknowledgeddate }}</dd>
    @endif
    @if(!empty($workforce->assignedto))
    <dt class="col-sm-3">Assigned to:</dt>
    <dd class="col-sm-9">
        {{ !empty($workforce->assigned_admin_name) ? $workforce->assigned_admin_name : $workforce->admin_name }}
        {{ $workforce->assigneddate }}
    </dd>
    @endif
    @if(!empty($workforce->acremarks))
    <dt class="col-sm-3">Area Coordinator Remarks</dt>
    <dd class="col-sm-9">{{ $workforce->acremarks }}</dd>
    @endif
    @if(!empty($workforce->attendedby))
    <dt class="col-sm-3">Attended by:</dt>
    <dd class="col-sm-9">{{ !empty($workforce->admin_name) ? $workforce->admin_name : '-' }} {{ $workforce->attendeddate }}</dd>
    @endif
    @if(!empty($workforce->adminremarks))
    <dt class="col-sm-3">Admin Remarks</dt>
    <dd class="col-sm-9">{{ $workforce->adminremarks }}</dd>
    @endif
</dl>
