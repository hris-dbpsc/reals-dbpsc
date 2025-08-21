<!DOCTYPE html>
<html lang="en">
@include('superadmin.header')

<body class="nav-fixed">
    @include('superadmin.topnav')
    <div id="layoutSidenav">
        @include('superadmin.sidenav')
        <div id="layoutSidenav_content">

            <main>
                <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-2">
                        <div class="container-fluid px-4">
                            <div class="page-header-content">
                                <div class="row align-items-center justify-content-between pt-2">
                                    <div class="col-auto mb-3">
                                        <h1 class="page-header-title text-body d-flex align-items-center">
                                            <a href="{{ route('superadmin_userclientadmin') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
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
                        <div class="alert alert-success">
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
                                        <img class="img-account-profile image rounded-circle mb-2" style="width: 180px; height: 180px; object-fit: cover;" src="{{ $clientadmin->photo ? asset('assets/users/clientadmin/' . $clientadmin->photo) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Profile Photo" />
                                        <!-- Profile picture help block-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <!-- Account details card-->
                                <div class="card mb-2">
                                    <div class="card-header text-body">Profile Information</div>
                                    <div class="card-body">
                                        <form action="{{ route('superadmin_edituserclientadmin_submit', $clientadmin->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <!-- Form Row-->
                                            <div class="row gx-3 mb-3">
                                                <div class="col-md-12">
                                                    <div class="form-floating mb-2">
                                                        <select class="form-select" id="clientname" name="clientname" aria-label="Select Client Name">
                                                            <option value="{{ $clientadmin->clientname }}" selected>{{ $clientadmin->clientname }}</option>
                                                            @foreach($clients->sortBy('clientname') as $client)
                                                            @if($client->clientname != $clientadmin->clientname)
                                                            <option value="{{ $client->clientname }}">{{ $client->clientname }}</option>
                                                            @endif
                                                            @endforeach
                                                        </select>
                                                        <label for="clientname">Client Name</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input class="form-control" id="firstname" name="firstname" type="text" value="{{ $clientadmin->firstname }}" placeholder="First Name" />
                                                        <label for="firstname">First Name</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input class="form-control" id="middlename" name="middlename" type="text" value="{{ $clientadmin->middlename }}" placeholder="Middle Name" />
                                                        <label for="middlename">Middle Name</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input class="form-control" id="lastname" name="lastname" type="text" value="{{ $clientadmin->lastname }}" placeholder="Last Name" />
                                                        <label for="lastname">Last Name</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input class="form-control" id="email" name="email" type="text" value="{{ $clientadmin->email }}" placeholder="Email" />
                                                        <label for="email">Email</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input class="form-control" id="contact" name="contact" type="text" value="{{ $clientadmin->contact }}" placeholder="Contact Number" />
                                                        <label for="contact">Contact Number</label>
                                                    </div>

                                                    <label class="small mb-1 d-block">Account Status</label>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status" id="status_active" value="1" {{ $clientadmin->isactive == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label text-body" for="status_active">
                                                            <span class="d-inline-flex align-items-center">
                                                                <i data-feather="check-circle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                                Active
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status" id="status_suspended" value="2" {{ $clientadmin->isactive == 2 ? 'checked' : '' }}>
                                                        <label class="form-check-label text-body" for="status_suspended">
                                                            <span class="d-inline-flex align-items-center">
                                                                <i data-feather="alert-triangle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                                Suspended
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{ $clientadmin->isactive == 0 ? 'checked' : '' }}>
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
                                                <button class="btn btn-light text-primary" type="button" id="confirmSaveBtn">
                                                    <i data-feather="save" class="me-1"></i>
                                                    Save Changes
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
                                                            <button type="button" class="btn btn-light text-danger" data-bs-dismiss="modal">
                                                                <i data-feather="x" class="me-1"></i>
                                                                Cancel
                                                            </button>
                                                            <button type="button" class="btn btn-light text-primary" id="modalSaveBtn">
                                                                <i data-feather="save" class="me-1"></i>
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
                            </div>
                        </div>
                    </div>
            </main>

            @include('superadmin.footer')
</body>

</html>