<!DOCTYPE html>
<html lang="en">
@include('superadmin..partials.header')

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
                                        <a href="{{ route('superadmin_userclientadmin') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        <div class="page-header-icon"><i data-feather="user-plus" style="width:25px; height:25px;"></i></div>
                                        Add Client Admin
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <div class="container-fluid px-4    ">
                    <div class="row">
                        <div class="col-xl-12">
                            <!-- Account details card-->
                            <div class="card mb-2">
                                <div class="card-header text-body">Client Admin Details</div>
                                <div class="card-body">
                                    <form action="{{ route('superadmin_addclientadmin_submit')}}" method="POST">
                                        @csrf

                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-2">
                                            
                                            <div class="col-md-4 form-floating">
                                                <input class="form-control" id="lastname" name="lastname" type="text" placeholder="Last name" required>
                                                <label for="lastname">Last name</label>
                                            </div>

                                            <div class="col-md-4 form-floating mb-2 mb-md-0">
                                                <input class="form-control" id="firstname" name="firstname" type="text" placeholder="First name" required>
                                                <label for="firstname">First name</label>
                                            </div>

                                            <div class="col-md-4 form-floating mb-2 mb-md-0">
                                                <input class="form-control" id="middlename" name="middlename" type="text" placeholder="Middle name" required>
                                                <label for="middlename">Middle name</label>
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-2">
                                            <div class="col-md-4 form-floating mb-2 mb-md-0">
                                                <select class="form-select" id="clientid" name="clientid" required>
                                                    <option value="" disabled selected>Select Client Name</option>
                                                    @foreach($clients as $client)
                                                        <option value="{{ $client->id }}">{{ $client->clientname }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="clientid">Client Name</label>
                                            </div>
                                            <div class="col-md-4 form-floating mb-2 mb-md-0">
                                                <input class="form-control" id="email" name="email" type="email" placeholder="Email address" required>
                                                <label for="email">Email address</label>
                                            </div>
                                            <div class="col-md-4 form-floating">
                                                <input class="form-control" id="contact" name="contact" type="tel" placeholder="Contact Number" required>
                                                <label for="contact">Contact Number</label>
                                            </div>
                                        </div>

                                        <!-- Submit button-->
                                        <!-- Add Client Admin Button triggers modal -->
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#confirmAddClientAdminModal">
                                            <i class="me-1" data-feather="plus"></i> 
                                            Add Client Admin
                                        </button>

                                        <!-- Confirmation Modal -->
                                        <div class="modal fade" id="confirmAddClientAdminModal" tabindex="-1" aria-labelledby="confirmAddClientAdminModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="confirmAddClientAdminModalLabel">Confirm Add Client Admin</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to add this Client Admin?
                                                    </div>
                                                    <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                                <i class="me-1" data-feather="x"></i>
                                                                Cancel
                                                            </button>
                                                            <button type="submit" class="btn btn-outline-primary" id="addClientadminBtn" onclick="disableAddClientadminBtn(this)">
                                                                <i class="me-1" data-feather="plus"></i>Add Client Admin
                                                            </button>
                                                            <script>
                                                                function disableAddClientadminBtn(btn) {
                                                                    btn.disabled = true;
                                                                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Processing...';
                                                                    btn.form.submit();
                                                                }
                                                            </script>
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