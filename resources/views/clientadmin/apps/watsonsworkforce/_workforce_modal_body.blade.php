{{-- Shared modal body for Watsons Workforce modals (clientadmin) --}}
<dl class="row mb-0">
    <dt class="col-sm-3">Request Type</dt>
    <dd class="col-sm-9">{{ $workforce->requesttype }}</dd>
    <dt class="col-sm-3">Request By</dt>
    <dd class="col-sm-9">{{ $workforce->requestby }}</dd>
    <dt class="col-sm-3">Date</dt>
    <dd class="col-sm-9">
        @if($isEdit)
            {{ \Carbon\Carbon::parse($workforce->requestdate)->format('M d, Y') }}
        @else
            {{ $workforce->requestdate }}
        @endif
    </dd>
    <dt class="col-sm-3">Status</dt>
    <dd class="col-sm-9">
        @if($isEdit)
            <span class="form-control-plaintext">{{ $workforce->status }}</span>
            <input type="hidden" name="status" value="{{ $workforce->status }}">
        @else
            {{ $workforce->status }}
        @endif
    </dd>
    @if(!empty($workforce->branchtarget))
    <dt class="col-sm-3">Branch</dt>
    <dd class="col-sm-9">
        @if($isEdit)
            <select name="branchtarget" class="form-control">
                <option value="">Select Branch</option>
                @foreach($branches as $branch)
                <option value="{{ $branch->branchname }}" {{ $workforce->branchtarget == $branch->branchname ? 'selected' : '' }}>{{ $branch->branchname }}</option>
                @endforeach
            </select>
        @else
            {{ $workforce->branchtarget }}
        @endif
    </dd>
    @endif
    @if(!empty($workforce->branchtransferfrom))
    <dt class="col-sm-3">Transfer From</dt>
    <dd class="col-sm-9">
        @if($isEdit)
            <select name="branchtransferfrom" class="form-control">
                <option value="">Select Branch</option>
                @foreach($branches as $branch)
                <option value="{{ $branch->branchname }}" {{ $workforce->branchtransferfrom == $branch->branchname ? 'selected' : '' }}>{{ $branch->branchname }}</option>
                @endforeach
            </select>
        @else
            {{ $workforce->branchtransferfrom }}
        @endif
    </dd>
    @endif
    @if(!empty($workforce->branchtransferto))
    <dt class="col-sm-3">Transfer To</dt>
    <dd class="col-sm-9">
        @if($isEdit)
            <select name="branchtransferto" class="form-control">
                <option value="">Select Branch</option>
                @foreach($branches as $branch)
                <option value="{{ $branch->branchname }}" {{ $workforce->branchtransferto == $branch->branchname ? 'selected' : '' }}>{{ $branch->branchname }}</option>
                @endforeach
            </select>
        @else
            {{ $workforce->branchtransferto }}
        @endif
    </dd>
    @endif
    @if(!empty($workforce->employeestransferred))
    <dt class="col-sm-3">Employee Transferred</dt>
    <dd class="col-sm-9">
        @if($isEdit)
            <input type="text" name="employeestransferred" class="form-control" value="{{ $workforce->employeestransferred }}">
        @else
            {{ $workforce->employeestransferred }}
        @endif
    </dd>
    @endif
    @if(!empty($workforce->reshuffle_number))
    <dt class="col-sm-3">Reshuffle Number</dt>
    <dd class="col-sm-9">
        @if($isEdit)
            <input type="text" name="reshuffle_number" class="form-control" value="{{ $workforce->reshuffle_number }}">
        @else
            {{ $workforce->reshuffle_number }}
        @endif
    </dd>
    @endif
    @if(!empty($workforce->reshuffle_details))
    <dt class="col-sm-3">Reshuffle Details</dt>
    <dd class="col-sm-9">
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
                    @foreach($workforce->reshuffle_details as $i => $detail)
                    <tr>
                        <td>
                            @if($isEdit)
                                <input type="text" name="employeesreshuffled[]" class="form-control form-control-sm" value="{{ $detail['employee'] }}">
                            @else
                                {{ $detail['employee'] }}
                            @endif
                        </td>
                        <td>
                            @if($isEdit)
                                <select name="branchreshufflefrom[]" class="form-control form-control-sm">
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                    <option value="{{ $branch->branchname }}" {{ $detail['from'] == $branch->branchname ? 'selected' : '' }}>{{ $branch->branchname }}</option>
                                    @endforeach
                                </select>
                            @else
                                {{ $detail['from'] }}
                            @endif
                        </td>
                        <td>
                            @if($isEdit)
                                <select name="branchreshuffleto[]" class="form-control form-control-sm">
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                    <option value="{{ $branch->branchname }}" {{ $detail['to'] == $branch->branchname ? 'selected' : '' }}>{{ $branch->branchname }}</option>
                                    @endforeach
                                </select>
                            @else
                                {{ $detail['to'] }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </dd>
    @endif
    @if(!empty($workforce->clientremarks))
    <dt class="col-sm-3">Remarks</dt>
    <dd class="col-sm-9">
        @if($isEdit)
            <textarea class="form-control" name="clientremarks" rows="3">{{ $workforce->clientremarks }}</textarea>
        @else
            {{ !empty($workforce->clientremarks) ? $workforce->clientremarks : '-' }}
        @endif
    </dd>
    @endif
    @if(!empty($workforce->acknowledgedby))
    <dt class="col-sm-3">Acknowledged by:</dt>
    <dd class="col-sm-9">{{ $workforce->acknowledgedby }} {{ $workforce->acknowledgeddate }}</dd>
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
