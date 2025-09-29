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
                                        <a href="{{ route('superadmin_useradmin') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        <div class="page-header-icon"><i data-feather="edit"></i></div>
                                        Edit Admin Profile
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <div class="container-fluid px-4 mt-2">
                    @if (session('success'))
                    <div class="alert alert-success alert-sm py-2 px-3">
                        {{ session('success')}}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-xl-4">
                            <!-- Profile picture card-->
                            <div class="card mb-2 mb-xl-0">
                                <div class="card-header text-body">Profile Picture</div>
                                <div class="card-body text-center">
                                    <!-- Profile picture image-->
                                    <img class="img-account-profile image rounded-circle mb-2" style="width: 180px; height: 180px; object-fit: cover;" src="{{ $admin->photo ? asset('assets/users/admin/' . $admin->photo) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Profile Photo" />
                                    <!-- Profile picture help block-->
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <!-- Account details card-->
                            <div class="card mb-2">
                                <div class="card-header text-body">Profile Information</div>
                                <div class="card-body">
                                    <form action="{{ route('superadmin_edituseradmin_submit', $admin->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-2">
                                            <div class="col-md-12">

                                                <div class="form-floating mb-2">
                                                    <input class="form-control" id="employeenumber" name="employeenumber" type="text" value="{{ $admin->employeenumber }}" placeholder="Employee Number" />
                                                    <label for="employeenumber">Employee Number</label>
                                                </div>

                                                <div class="form-floating mb-2">
                                                    <input class="form-control" id="firstname" name="firstname" type="text" value="{{ $admin->firstname }}" placeholder="First Name" />
                                                    <label for="firstname">First Name</label>
                                                </div>

                                                <div class="form-floating mb-2">
                                                    <input class="form-control" id="middlename" name="middlename" type="text" value="{{ $admin->middlename }}" placeholder="Middle Name" />
                                                    <label for="middlename">Middle Name</label>
                                                </div>

                                                <div class="form-floating mb-2">
                                                    <input class="form-control" id="lastname" name="lastname" type="text" value="{{ $admin->lastname }}" placeholder="Last Name" />
                                                    <label for="lastname">Last Name</label>
                                                </div>

                                                <div class="form-floating mb-2">
                                                    <select class="form-select" id="region" name="region" aria-label="Select Region">
                                                        <option value="" disabled {{ !$selectedRegion ? 'selected' : '' }}>Select Region</option>
                                                        @foreach($regions as $region)
                                                        <option value="{{ $region }}" {{ $selectedRegion == $region ? 'selected' : '' }}>{{ $region }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="region">Region</label>
                                                </div>

                                                <div class="form-floating mb-2">
                                                    <input class="form-control" id="email" name="email" type="text" value="{{ $admin->email }}" placeholder="Email" />
                                                    <label for="email">Email</label>
                                                </div>

                                                <div class="form-floating mb-2">
                                                    <input class="form-control" id="contact" name="contact" type="text" value="{{ $admin->contact }}" placeholder="Contact Number" />
                                                    <label for="contact">Contact Number</label>
                                                </div>

                                                <label class="small mb-1 d-block">Account Status</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status" id="status_active" value="1" {{ $admin->isactive == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label text-body" for="status_active">
                                                        <span class="d-inline-flex align-items-center">
                                                            <i data-feather="check-circle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                            Active
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status" id="status_suspended" value="2" {{ $admin->isactive == 2 ? 'checked' : '' }}>
                                                    <label class="form-check-label text-body" for="status_suspended">
                                                        <span class="d-inline-flex align-items-center">
                                                            <i data-feather="alert-triangle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                            Suspended
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{ $admin->isactive == 0 ? 'checked' : '' }}>
                                                    <label class="form-check-label text-body" for="status_inactive">
                                                        <span class="d-inline-flex align-items-center">
                                                            <i data-feather="x-circle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                            Inactive
                                                        </span>
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button class="btn btn-outline-primary" type="button" id="confirmSaveBtn">
                                                <i data-feather="bookmark" class="me-1"></i>
                                                Save
                                            </button>
                                        </div>

                                        <!-- Confirmation Modal -->
                                        <div class="modal fade" id="confirmSaveModal" tabindex="-1" aria-labelledby="confirmSaveModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="confirmSaveModalLabel">Confirm Update</h5>
                                                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to save these changes?
                                                    </div>
                                                    <div class="modal-footer justify-content-center">
                                                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                            <i data-feather="x" class="me-1"></i>
                                                            Cancel
                                                        </button>
                                                        <button type="button" class="btn btn-outline-primary" id="modalSaveBtn">
                                                            <i data-feather="bookmark" class="me-1"></i>
                                                            Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                var confirmBtn = document.getElementById('confirmSaveBtn');
                                                var modalSaveBtn = document.getElementById('modalSaveBtn');
                                                var confirmModal = new bootstrap.Modal(document.getElementById('confirmSaveModal'));
                                                var form = confirmBtn.closest('form');

                                                confirmBtn.addEventListener('click', function(e) {
                                                    confirmModal.show();
                                                });

                                                modalSaveBtn.addEventListener('click', function() {
                                                    form.submit();
                                                });
                                            });
                                        </script>
                                    </form>
                                </div>
                            </div>
                            <div class="card mb-2">
                                <div class="card-header text-body">Application Access Control</div>
                                <div class="card-body">
                                    <form action="{{ route('superadmin_editadminappaccess_submit', isset($applications_access) ? $applications_access->id : '') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-1">
                                            <div class="col-md-12">
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
                </div>
            </main>

            @include('superadmin.partials.footer')
</body>

</html>