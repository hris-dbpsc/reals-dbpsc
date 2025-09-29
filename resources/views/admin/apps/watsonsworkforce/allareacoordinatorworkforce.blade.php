<div class="container-fluid px-4 mt-2">
    @if (session('success'))
    <div class="alert alert-success alert-sm py-1 px-2">
        {{ session('success') }}
    </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card mb-2" id="allrequest">
                <div class="card-header text-body d-flex justify-content-between align-items-center">
                    <span>REQUEST DETAILS</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatablesSimple" class="table table-sm table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Request Type</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Acknowledged by</th>
                                    <th>Attended by</th>
                                    <th>Area Coordinator Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Request Type</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Acknowledged by</th>
                                    <th>Attended by</th>
                                    <th>Area Coordinator Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($workforces_areacoordinator as $workforce)
                                <tr>
                                        <td>{{ $workforce->id }}</td>
                                        <td>{{ $workforce->requesttype }}</td>
                                        <td>{{ $workforce->requestdate }}
                                            @if($workforce->tat_days !== null)
                                            <span class="{{ $workforce->tat_class }}" style="font-size:0.85em;">({{ $workforce->tat_days }} day{{ $workforce->tat_days !== 1 ? 's' : '' }})</span>
                                            @else
                                            <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                            $status = strtolower($workforce->status);
                                            $badgeClass = 'text-danger';
                                            switch ($status) {
                                            case 'pending':
                                            $badgeClass = 'text-warning';
                                            break;
                                            case 'attended':
                                            $badgeClass = 'text-primary';
                                            break;
                                            case 'completed':
                                            $badgeClass = 'text-success';
                                            break;
                                            }
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ ucfirst($workforce->status) }}</span>
                                        </td>
                                        <td>{{ $workforce->acknowledgedby }}<span class="text-muted small">{{ $workforce->acknowledgeddate }}</span></td>
                                        <td>
                                            {{ $workforce->admin_name }}
                                            <span class="text-muted small">{{ $workforce->attendeddate }}</span>
                                        </td>
                                        <td>{{ $workforce->acremarks }}</td>
                                        <td>
                                            <a class="btn btn-xs" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#areacoordinator_viewPendingRequestModal{{ $workforce->id }}" title="View Request">
                                                <i data-feather="eye" style="width:1.5em;height:1.5em;"></i>
                                            </a>
                                            @if(strtolower($workforce->status) === 'pending')
                                            <button type="button" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#areacoordinator_attendRequestModal{{ $workforce->id }}" title="Act">
                                                <i data-feather="edit" style="width:1.5em;height:1.5em;"></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- MODALS --}}
    @foreach($workforces as $workforce)
    {{-- View Modal --}}
    <div class="modal fade" id="areacoordinator_viewPendingRequestModal{{ $workforce->id }}" tabindex="-1" aria-labelledby="areacoordinator_viewPendingRequestModal{{ $workforce->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="areacoordinator_viewPendingRequestModalLabel{{ $workforce->id }}">{{ $workforce->id }}: {{ $workforce->requesttype }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            @include('admin.apps.watsonsworkforce.workforce_modal_body', ['workforce' => $workforce, 'mode' => 'view'])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Attend Modal --}}
    @if(strtolower($workforce->status) === 'pending' && !empty($workforce->id))
    <div class="modal fade" id="areacoordinator_attendRequestModal{{ $workforce->id }}" tabindex="-1" aria-labelledby="areacoordinator_attendRequestModalLabel{{ $workforce->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form action="{{ route('areacoordinator_watsons_attendworkforce', $workforce->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="areacoordinator_attendRequestModalLabel{{ $workforce->id }}">Attend Request #{{ $workforce->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                @include('admin.apps.watsonsworkforce.workforce_modal_body', ['workforce' => $workforce, 'mode' => 'attend'])
                                <dl class="row mb-0">
                                    <dt class="col-sm-3">Area Coordinator Remarks</dt>
                                    <dd class="col-sm-9">
                                        <textarea name="areacoordinator_remarks" class="form-control" rows="5" placeholder="Enter area coordinator remarks">{{ old('areacoordinator_remarks', $workforce->acremarks ?? '') }}</textarea>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                            <i data-feather="x" class="me-1" style="width:1.2em;height:1.2em;"></i> Close
                        </button>
                        <button type="submit" class="btn btn-outline-primary" id="confirmBtn{{ $workforce->id }}" onclick="disableconfirmBtn(this)">
                            <i data-feather="check-circle" class="me-1" style="width:1.2em;height:1.2em;"></i> Confirm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @endforeach