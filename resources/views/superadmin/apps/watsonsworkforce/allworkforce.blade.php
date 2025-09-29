<!DOCTYPE html>
<html lang="en">
@include('superadmin.partials.header')

<body class="nav-fixed">
    @include('superadmin.partials.topnav')
    <div id="layoutSidenav">
        @include('superadmin.partials.sidenav')
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-2">
                    <div class="container-fluid px-4">
                        <div class="page-header-content">
                            <div class="row align-items-center justify-content-between pt-3">
                                <div class="col-auto mb-2">
                                    <h1 class="page-header-title text-body d-flex align-items-center">
                                        <a href="{{ route('superadmin_watsonsworkforce') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        All
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
                                                        {{ $workforce->admin_name }}<br><span class="text-muted small">{{ $workforce->attendeddate }}</span>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-xs" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#viewPendingRequestModal{{ $workforce->id }}" title="View Request">
                                                            <i data-feather="eye" style="width:1.5em;height:1.5em;"></i>
                                                        </a>
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
    @include('superadmin.partials.footer')
</body>

@foreach($workforces as $workforce)
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
                        @include('superadmin.apps.watsonsworkforce._workforce_modal_body', ['workforce' => $workforce, 'branches' => $branches, 'isEdit' => false])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

</html>