<?php

use Carbon\Carbon;
?>
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
                                        Completed Request
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <!-- All Request Container -->
                <div class="container-fluid px-4 mt-2">
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
                                                    <th>Acknowledged By</th>
                                                    <th>Attended By</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Request Type</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Acknowledged By</th>
                                                    <th>Attended By</th>
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
                                                        <span class="badge text-success">{{ $workforce->status }}</span>
                                                    </td>
                                                    <td>{{ $workforce->acknowledgedby }}<span class="text-muted small">{{ $workforce->acknowledgeddate }}</span></td>
                                                    <td>{{ $workforce->admin_name }}<span class="text-muted small">{{ $workforce->attendeddate }}</span></td>
                                                    <td>
                                                        <a class="btn btn-xs" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#viewPendingRequestModal{{ $workforce->id }}">
                                                            <i data-feather="eye" style="width:1.2em;height:1.2em;"></i>
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
        </div>
    </div>
    </div>
    </div>
    </div>
    </main>

    </div>
    </div>
    @include('clientadmin.partials.client_footer')

    <!-- MODALS -->
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
    @endforeach
</body>


</html>