<!DOCTYPE html>
<html lang="en">
@include('superadmin.header')

<body class="nav-fixed">
    @include('superadmin.topnav')
    <div id="layoutSidenav">
        @include('superadmin.sidenav')
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
                                        Add Admin
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <div class="container-fluid px-4 mt-2">
                    <div class="row">
                        <div class="col-xl-12">
                            <!-- Account details card-->
                            <div class="card mb-2">
                                <div class="card-header text-body">Admin Details</div>
                                <div class="card-body">
                                    <form action="{{ route('superadmin_addadmin_submit')}}" method="POST">
                                        @csrf

                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-2">
                                            <div class="col-md-4 form-floating mb-2 mb-md-0">
                                                <input class="form-control" name="lastname" type="text" placeholder="Last name" required>
                                                <label for="lastname">Last name</label>
                                            </div>
                                            <div class="col-md-4 form-floating mb-2 mb-md-0">
                                                <input class="form-control" name="firstname" type="text" placeholder="First name" required>
                                                <label for="firstname">First name</label>
                                            </div>
                                            <div class="col-md-4 form-floating">
                                                <input class="form-control" name="middlename" type="text" placeholder="Middle name" required>
                                                <label for="middlename">Middle name</label>
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-2">
                                            <div class="col-md-4 form-floating mb-2 mb-md-0">
                                                <input class="form-control" name="employeenumber" type="text" placeholder="Employee number" required>
                                                <label for="employeenumber">Employee number</label>
                                            </div>
                                            <div class="col-md-4 form-floating mb-2 mb-md-0">
                                                <input class="form-control" name="email" type="email" placeholder="Email address" required>
                                                <label for="email">Email address</label>
                                            </div>
                                            <div class="col-md-4 form-floating">
                                                <input class="form-control" name="contact" type="tel" placeholder="Contact Number" required>
                                                <label for="contact">Contact Number</label>
                                            </div>
                                        </div>

                                        <!-- Submit button-->
                                        <!-- Add Admin Button triggers modal -->
                                        <button class="btn bg-light text-primary" type="button" data-bs-toggle="modal" data-bs-target="#confirmAddAdminModal">
                                            <i class="me-1" data-feather="plus"></i>
                                            Add Admin
                                        </button>

                                        <!-- Confirmation Modal -->
                                        <div class="modal fade" id="confirmAddAdminModal" tabindex="-1" aria-labelledby="confirmAddAdminModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="confirmAddAdminModalLabel">Confirm Add Admin</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to add this Admin?
                                                    </div>
                                                    <div class="modal-footer justify-content-center">
                                                        <button type="button" class="btn bg-light text-danger" data-bs-dismiss="modal">
                                                            <i class="me-1" data-feather="x"></i>
                                                            Cancel
                                                        </button>
                                                        <button type="submit" class="btn bg-light text-primary">
                                                            <i class="me-1" data-feather="plus"></i>
                                                            Add Admin
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

            @include('superadmin.footer')
</body>

</html>