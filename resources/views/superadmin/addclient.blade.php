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
                                        <a href="{{ route('superadmin_clients') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        <div class="page-header-icon"><i data-feather="users" style="width:25px; height:25px;"></i></div>
                                        Add Client
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
                                <div class="card-header text-body">Client Information</div>
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
                                        <div class="d-flex align-items-center justify-content-between mt-2 mb-0">
                                            <!-- Add Client Button triggers modal -->
                                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#confirmAddClientModal">
                                                <i data-feather="plus" class="me-1"></i>
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
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-outline-danger d-flex align-items-center" data-bs-dismiss="modal">
                                                                <i data-feather="x" class="me-1"></i>
                                                                Cancel
                                                            </button>
                                                            <button type="submit" class="btn btn-outline-primary d-flex align-items-center">
                                                                <i data-feather="plus" class="me-1"></i>
                                                                Add Client
                                                            </button>
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