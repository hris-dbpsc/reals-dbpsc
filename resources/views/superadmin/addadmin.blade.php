<!DOCTYPE html>
<html lang="en">
@include('superadmin.header')
@include('superadmin.topnav')
<div id="layoutSidenav">
    @include('superadmin.sidenav')
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                <div class="container-fluid px-4">
                    <div class="page-header-content">
                        <div class="row align-items-center justify-content-between pt-3">
                            <div class="col-auto mb-3">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="user-plus"></i></div>
                                    Add Admin
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="{{ route('superadmin_useradmin') }}">
                                    <i class="me-1" data-feather="arrow-left"></i>
                                    Back to Admin List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-fluid px-4 mt-4">
                <div class="row">
                    <div class="col-xl-12">
                        <!-- Account details card-->
                        <div class="card mb-4">
                            <div class="card-header">Admin Details</div>
                            <div class="card-body">
                                <form action="{{ route('superadmin_addadmin_submit')}}" method="POST">
                                    @csrf

                                    <!-- Form Row-->
                                    <div class="row gx-3 mb-3">
                                        <div class="col-md-4 form-floating mb-3 mb-md-0">
                                            <input class="form-control" name="lastname" type="text" placeholder="Last name" required>
                                            <label for="lastname">Last name</label>
                                        </div>
                                        <div class="col-md-4 form-floating mb-3 mb-md-0">
                                            <input class="form-control" name="firstname" type="text" placeholder="First name" required>
                                            <label for="firstname">First name</label>
                                        </div>
                                        <div class="col-md-4 form-floating">
                                            <input class="form-control" name="middlename" type="text" placeholder="Middle name" required>
                                            <label for="middlename">Middle name</label>
                                        </div>
                                    </div>
                                    <div class="row gx-3 mb-3">
                                        <div class="col-md-4 form-floating mb-3 mb-md-0">
                                            <input class="form-control" name="employeenumber" type="text" placeholder="Employee number" required>
                                            <label for="employeenumber">Employee number</label>
                                        </div>
                                        <div class="col-md-4 form-floating mb-3 mb-md-0">
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
                                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#confirmAddAdminModal">
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
                                                <div class="modal-footer">
                                                    <div class="btn-group" role="group" aria-label="Confirm Add Admin">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary btn-sm">Yes, Add Admin</button>
                                                    </div>
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