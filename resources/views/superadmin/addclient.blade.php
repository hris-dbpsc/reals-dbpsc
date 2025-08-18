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
                                    Add Client
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="{{ route('superadmin_clients') }}">
                                    <i class="me-1" data-feather="arrow-left"></i>
                                    Back to Client List
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
                            <div class="card-header">Client Information</div>
                            <div class="card-body">
                                <form action="{{ route('superadmin_addclient_submit') }}" method="POST">
                                    @csrf
                                    <!-- Form Row 1-->
                                    <div class="row gx-3 mb-1">
                                        <div class="col-md-5 form-floating mb-1 mb-md-0">
                                            <input class="form-control" name="clientname" type="text" placeholder="Client Name" required id="clientname">
                                            <label for="clientname">Client Name</label>
                                        </div>
                                        <div class="col-md-5 form-floating mb-1 mb-md-0">
                                            <input class="form-control" name="clientshortname" type="text" placeholder="Client Short Name" required id="clientshortname">
                                            <label for="clientshortname">Client Short Name</label>
                                        </div>
                                        <div class="col-md-2 form-floating mb-1  mb-md-0">
                                            <select class="form-control" name="clienttype" id="clienttype" aria-label="Client Type" required>
                                                <option value="Government">Government</option>
                                                <option value="Private">Private</option>
                                            </select>
                                            <label for="clienttype">Client Type</label>
                                        </div>
                                    </div>
                                    <!-- Submit button-->
                                    <div class="d-flex align-items-center justify-content-between mt-3 mb-0">
                                        <!-- Add Client Button triggers modal -->
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#confirmAddClientModal">
                                            Add Client
                                        </button>

                                        <!-- Confirmation Modal -->
                                        <div class="modal fade" id="confirmAddClientModal" tabindex="-1" aria-labelledby="confirmAddClientModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="confirmAddClientModalLabel">Confirm Add Client</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to add this client?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="btn-group" role="group" aria-label="Confirm Add Client">
                                                            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary btn-sm">Add Client</button>
                                                        </div>
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