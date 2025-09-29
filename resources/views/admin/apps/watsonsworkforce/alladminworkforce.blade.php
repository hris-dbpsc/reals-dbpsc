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
                                        <th>Assigned to</th>
                                        <th>Attended by</th>
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
                                        <th>Assigned to</th>
                                        <th>Attended by</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($workforces as $workforce)
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
                                        <td>{{ $workforce->acknowledgedby }}<br><span class="text-muted small">{{ $workforce->acknowledgeddate }}</span></td>
                                        <td>
                                            {{ $workforce->assigned_admin_name }}
                                            <span class="text-muted small">{{ $workforce->assigneddate }}</span>
                                            @if(!empty($workforce->acremarks))
                                            <span class="text-muted small"><hr>{{ $workforce->acremarks }} {{ $workforce->acremarksdate }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $workforce->admin_name }}<br>
                                            <span class="text-muted small">{{ $workforce->attendeddate }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center gap-1" style="min-width:140px;">
                                                <a class="btn btn-xs" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#viewPendingRequestModal{{ $workforce->id }}" title="View Request">
                                                    <i data-feather="eye" style="width:1.5em;height:1.5em;"></i>
                                                </a>
                                                @if(strtolower($workforce->status) === 'pending')
                                                    @if(strtolower($workforce->acknowledged) == 0)
                                                        <button type="button" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#acknowledgeRequestModal{{ $workforce->id }}" title="Acknowledge Request">
                                                            <i data-feather="thumbs-up" style="width:1.5em;height:1.5em;"></i>
                                                        </button>
                                                    @else
                                                        <span style="width:34px;display:inline-block;"></span>
                                                    @endif
                                                    @if(empty($workforce->assignedto))
                                                        <button type="button" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#assignRequestModal{{ $workforce->id }}" title="Assign to Area Coordinator">
                                                            <i data-feather="send" style="width:1.5em;height:1.5em;"></i>
                                                        </button>
                                                    @else
                                                        <span style="width:34px;display:inline-block;"></span>
                                                    @endif
                                                    <button type="button" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#attendRequestModal{{ $workforce->id }}" title="Act">
                                                        <i data-feather="edit" style="width:1.5em;height:1.5em;"></i>
                                                    </button>
                                                @else
                                                    <span style="width:34px;display:inline-block;"></span>
                                                    <span style="width:34px;display:inline-block;"></span>
                                                    <span style="width:34px;display:inline-block;"></span>
                                                @endif
                                            </div>
                                        </td>    </tr>
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
    <div class="modal fade" id="viewPendingRequestModal{{ $workforce->id }}" tabindex="-1" aria-labelledby="viewPendingRequestModalLabel{{ $workforce->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewPendingRequestModalLabel{{ $workforce->id }}">{{ $workforce->id }}: {{ $workforce->requesttype }}</h5>
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
    
    {{-- Acknowledge Modal --}}
    @if(strtolower($workforce->status) === 'pending' && strtolower($workforce->acknowledged) == 0 && !empty($workforce->id))
    <div class="modal fade" id="acknowledgeRequestModal{{ $workforce->id }}" tabindex="-1" aria-labelledby="acknowledgeRequestModalLabel{{ $workforce->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin_watsons_acknowledgeworkforce', $workforce->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title" id="acknowledgeRequestModalLabel{{ $workforce->id }}">Request acknowledged</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to acknowledge this request?
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                            <i data-feather="x" class="me-1" style="width:1.2em;height:1.2em;"></i>
                            Close
                        </button>
                        <button type="submit" class="btn btn-outline-primary">
                            <i data-feather="thumbs-up" class="me-1" style="width:1.2em;height:1.2em;"></i>
                            Yes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Assign Modal --}}
    @if(strtolower($workforce->status) === 'pending' && empty($workforce->assignedto) && !empty($workforce->id))
    <div class="modal fade" id="assignRequestModal{{ $workforce->id }}" tabindex="-1" aria-labelledby="assignRequestModalLabel{{ $workforce->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form action="{{ route('admin_watsons_assignworkforce', $workforce->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignRequestModalLabel{{ $workforce->id }}">Assign Request #{{ $workforce->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                @include('admin.apps.watsonsworkforce.workforce_modal_body', ['workforce' => $workforce, 'mode' => 'assign'])
                                <dl class="row mb-0">
                                    <dt class="col-sm-3">Assign to Area Coordinator</dt>
                                    <dd class="col-sm-9">
                                        <select name="areacoordinator" class="form-control">
                                            <option value="">Select Area Coordinator</option>
                                            @foreach($admins as $admin)
                                            <option value="{{ $admin->id }}" {{ $workforce->areacoordinator == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->lastname }}, {{ $admin->firstname }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                            <i data-feather="x" class="me-1" style="width:1.2em;height:1.2em;"></i> Close
                        </button>
                        <button type="submit" class="btn btn-outline-primary" id="assignBtn{{ $workforce->id }}" onclick="disableassignBtn(this)">
                            <i data-feather="send" class="me-1" style="width:1.2em;height:1.2em;"></i> Assign
                        </button>
                        <script>
                            function disableassignBtn(btn) {
                                btn.disabled = true;
                                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Processing...';
                                btn.form.submit();
                            }
                        </script>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    
    {{-- Attend Modal --}}
    @if(strtolower($workforce->status) === 'pending' && !empty($workforce->id))
    <div class="modal fade" id="attendRequestModal{{ $workforce->id }}" tabindex="-1" aria-labelledby="attendRequestModalLabel{{ $workforce->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form action="{{ route('admin_watsons_attendworkforce', $workforce->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="attendRequestModalLabel{{ $workforce->id }}">Attend Request #{{ $workforce->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                @include('admin.apps.watsonsworkforce.workforce_modal_body', ['workforce' => $workforce, 'mode' => 'attend'])
                                <dl class="row mb-0">
                                    <dt class="col-sm-3">Admin Remarks</dt>
                                    <dd class="col-sm-9">
                                        <textarea name="adminremarks" class="form-control" rows="5" placeholder="Enter admin remarks">{{ old('adminremarks', $workforce->acremarks ?? '') }}</textarea>
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
                        <script>
                            function disableconfirmBtn(btn) {
                                btn.disabled = true;
                                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Processing...';
                                btn.form.submit();
                            }
                        </script>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @endforeach