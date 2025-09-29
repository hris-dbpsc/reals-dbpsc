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
                                <div class="col-auto mb-3">
                                    <h1 class="page-header-title text-body d-flex align-items-center">
                                        <a href="{{ route('superadmin_appaccess') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        <div class="page-header-icon"><i data-feather="edit" style="width:25px; height:25px;"></i></div>
                                        Edit App Access
                                    </h1>
                                </div>
                            </div>
                        </div>
                </header>
                <!-- Main page content-->
                <div class="container-fluid px-4">
                    <div class="row">
                        <div class="col-xl-12">
                            <!-- Account details card-->
                            <div class="card mb-4">
                                <div class="card-header text-body">Access Details</div>
                                <div class="card-body">
                                    <form action="{{ route('superadmin_editappaccess_submit', isset($applications_access) ? $applications_access->id : '') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-1">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-1">
                                                    @if(isset($applications_access) && isset($applications_access->clientid))
                                                    @php
                                                    $client = $clients->firstWhere('id', $applications_access->clientid);
                                                    $hasAccess = $client ? \App\Models\ApplicationsAccess::where('clientid', $client->id)->exists() : false;
                                                    @endphp
                                                    <input type="hidden" name="clientid" value="{{ $client ? $client->id : '' }}">
                                                    <input type="text" class="form-control" value="{{ $client ? $client->clientname : '' }}" readonly>
                                                    <label for="clientid">Client Name</label>
                                                    @else
                                                    <input type="text" class="form-control" value="" readonly placeholder="No client selected">
                                                    <label for="appaccessid">Client Name</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @csrf
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-1">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-1">
                                                    <label class="mb-2 fw-bold">Select Application Access</label>
                                                    <div class="row">
                                                        @foreach($applications as $application)
                                                        @php
                                                        $field = 'app_' . $application->id;
                                                        $checked = '';
                                                        if(isset($applications_access) && isset($applications_access->$field)) {
                                                        $checked = ($applications_access->$field == 1) ? 'checked' : '';
                                                        }
                                                        @endphp
                                                        <div class="col-md-6 mb-3">
                                                            <div class="card border shadow-sm p-2 h-100">
                                                                <div class="form-check form-switch d-flex align-items-center">
                                                                    <input class="form-check-input me-2" type="checkbox" name="app_{{ $application->id }}" id="app_{{ $application->id }}" value="1" {{ $checked }}>
                                                                    <label class="form-check-label fw-semibold" for="app_{{ $application->id }}">
                                                                        {{ $application->appname }}
                                                                    </label>
                                                                </div>
                                                                @if(!empty($application->applabel))
                                                                <div class="small text-muted ms-2 mt-1 fst-italic" style="font-size: 0.85em;">
                                                                    {{ $application->applabel }}
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="form-text mt-2">
                                                        <i class="text-primary" data-feather="info"></i>
                                                        Toggle the switches to grant or revoke access for each application.
                                                    </div>
                                                </div>
                                            </div>
                                            @csrf
                                            <!-- Submit button-->
                                            <div class="d-flex align-items-center justify-content-between mt-2 mb-0">
                                                <!-- Add Branch Button triggers modal -->
                                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#confirmAddBranchModal">
                                                    <i data-feather="bookmark" class="me-1"></i>
                                                    Save
                                                </button>
                                            </div>

                                            <!-- Confirmation Modal -->
                                            <div class="modal fade" id="confirmAddBranchModal" tabindex="-1" aria-labelledby="confirmAddAccessModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="confirmAddAccessModalLabel">Confirm Update Access</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to update this access?
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                                <i data-feather="x" class="me-1"></i>
                                                                Cancel
                                                            </button>
                                                            <button type="submit" class="btn btn-outline-primary">
                                                                <i data-feather="bookmark" class="me-1"></i>
                                                                Save
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            </main>
            @include('superadmin.partials.footer')
        </div>
</body>

</html>