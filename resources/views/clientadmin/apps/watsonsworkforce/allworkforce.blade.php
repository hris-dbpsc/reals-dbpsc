<!DOCTYPE html>
<html lang="en">
@include('clientadmin.partials.client_header')

<body class="nav-fixed">
    @include('clientadmin.partials.client_topnav')
    <div id="layoutSidenav">
        @include('clientadmin.partials.client_sidenav')
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-2">
                    <div class="container-fluid px-4">
                        <div class="page-header-content">
                            <div class="row align-items-center justify-content-between pt-3">
                                <div class="col-auto mb-2">
                                    <h1 class="page-header-title text-body d-flex align-items-center">
                                        <a href="{{ route('clientadmin_watsons_workforce') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        All Request
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <!-- All Request Container -->
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
                                                        <span style="font-size:0.85em;{{ $workforce->tat_class }}">({{ $workforce->tat_days }} day{{ $workforce->tat_days !== 1 ? 's' : '' }})</span>
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
                                                    <td class="text-nowrap">
                                                        <a class="btn btn-xs" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#viewPendingRequestModal{{ $workforce->id }}" title="View Request">
                                                            <i data-feather="eye" style="width:1.5em;height:1.5em;"></i>
                                                        </a>
                                                        @if(strtolower($workforce->status) !== 'cancelled' && strtolower($workforce->status) !== 'completed')
                                                            @if(!empty($workforce->status) && strtolower($workforce->status) !== 'attended')
                                                                <a class="btn btn-xs" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editRequestModal{{ $workforce->id }}" title="Edit Request">
                                                                    <i data-feather="edit" style="width:1.5em;height:1.5em;"></i>
                                                                </a>
                                                            @else
                                                                <span class="btn btn-xs invisible"><i data-feather="edit" style="width:1.5em;height:1.5em;"></i></span>
                                                            @endif
                                                            <button type="button" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#completedRequestModal{{ $workforce->id }}" title="Request Completed">
                                                                <i data-feather="check-circle" style="width:1.5em;height:1.5em;"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#cancelRequestModal{{ $workforce->id }}" title="Cancel Request">
                                                                <i data-feather="x-circle" style="width:1.5em;height:1.5em;"></i>
                                                            </button>
                                                        @else
                                                            <span class="btn btn-xs invisible"><i data-feather="edit" style="width:1.5em;height:1.5em;"></i></span>
                                                            <span class="btn btn-xs invisible"><i data-feather="check-circle" style="width:1.5em;height:1.5em;"></i></span>
                                                            <span class="btn btn-xs invisible"><i data-feather="x-circle" style="width:1.5em;height:1.5em;"></i></span>
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
            </main>

        </div>
    </div>
    @include('clientadmin.partials.client_footer')
</body>

{{-- MODALS --}}
@foreach($workforces as $workforce)
<!-- View Modal -->
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
                        @include('clientadmin.apps.watsonsworkforce._workforce_modal_body', ['workforce' => $workforce, 'branches' => $branches, 'isEdit' => false])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Edit Modal -->
<div class="modal fade" id="editRequestModal{{ $workforce->id }}" tabindex="-1" aria-labelledby="editRequestModalLabel{{ $workforce->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('clientadmin_watsons_updateworkforce', $workforce->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title" id="editRequestModalLabel{{ $workforce->id }}">Edit Request #{{ $workforce->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            @include('clientadmin.apps.watsonsworkforce._workforce_modal_body', ['workforce' => $workforce, 'branches' => $branches, 'isEdit' => true])
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                        <i data-feather="x-circle" class="me-1" style="width:1.2em;height:1.2em;"></i> Close
                    </button>
                    <button type="submit" class="btn btn-outline-primary">
                        <i data-feather="bookmark" class="me-1" style="width:1.2em;height:1.2em;"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Completed Modal -->
<div class="modal fade" id="completedRequestModal{{ $workforce->id }}" tabindex="-1" aria-labelledby="completedRequestModalLabel{{ $workforce->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completedRequestModalLabel{{ $workforce->id }}">Request completed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to mark this request as completed?
            </div>
            <div class="modal-footer justify-content-center">
                <form action="{{ route('clientadmin_watsons_iscompletedworkforce', $workforce->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                        <i data-feather="x" class="me-1" style="width:1.2em;height:1.2em;"></i> Close
                    </button>
                    <button type="submit" class="btn btn-outline-primary">
                        <i data-feather="check-circle" class="me-1" style="width:1.2em;height:1.2em;"></i> Yes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Cancel Modal -->
<div class="modal fade" id="cancelRequestModal{{ $workforce->id }}" tabindex="-1" aria-labelledby="cancelRequestModalLabel{{ $workforce->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelRequestModalLabel{{ $workforce->id }}">Cancel Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to cancel this request?
            </div>
            <div class="modal-footer justify-content-center">
                <form action="{{ route('clientadmin_watsons_cancelworkforce', $workforce->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                        <i data-feather="x" class="me-1" style="width:1.2em;height:1.2em;"></i> Close
                    </button>
                    <button type="submit" class="btn btn-outline-primary">
                        <i data-feather="trash-2" class="me-1" style="width:1.2em;height:1.2em;"></i> Yes, Cancel
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

</html>