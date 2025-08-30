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
                                        <a href="{{ route('superadmin_branches') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        <div class="page-header-icon"><i data-feather="list" style="width:25px; height:25px;"></i></div>
                                        Add Branch
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
                            <div class="card mb-4">
                                <div class="card-header text-body">Branch Details</div>
                                <div class="card-body">
                                    <form action="{{ route('superadmin_addbranch_submit') }}" method="POST">
                                        @csrf
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-1">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-1">
                                                    <select class="form-select" name="clientname" id="clientname" onchange="updateClientShortName()" required>
                                                        @foreach ($clients as $client)
                                                        <option value="{{ $client->clientname }}" data-shortname="{{ $client->clientshortname }}" data-clienttype="{{ $client->clienttype }}">{{ $client->clientname }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="clienttype" id="clienttype" value="" required>
                                                    <script>
                                                        function updateClientShortName() {
                                                            var select = document.getElementById('clientname');
                                                            var selectedOption = select.options[select.selectedIndex];
                                                            var shortname = selectedOption.getAttribute('data-shortname');
                                                            var clienttype = selectedOption.getAttribute('data-clienttype');
                                                            document.getElementById('clientshortname').value = shortname;
                                                            document.getElementById('clienttype').value = clienttype;
                                                        }
                                                        // Initialize on page load
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            updateClientShortName();
                                                        });
                                                    </script>
                                                    <label for="clientname">CLIENT NAME</label>
                                                </div>
                                                <input type="hidden" name="clientshortname" id="clientshortname" value="">
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-1">
                                                    <input class="form-control" name="branchname" type="text" id="branchname" placeholder="Branch Name" required>
                                                    <label for="branchname">Branch Name</label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Submit button-->
                                        <div class="d-flex align-items-center justify-content-between mt-2 mb-0">
                                            <!-- Add Branch Button triggers modal -->
                                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#confirmAddBranchModal">
                                                <i data-feather="plus" class="me-1"></i>
                                                Add Branch
                                            </button>
                                        </div>

                                        <!-- Confirmation Modal -->
                                        <div class="modal fade" id="confirmAddBranchModal" tabindex="-1" aria-labelledby="confirmAddBranchModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="confirmAddBranchModalLabel">Confirm Add Branch</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to add this branch?
                                                    </div>
                                                    <div class="modal-footer justify-content-center">
                                                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                            <i data-feather="x" class="me-1"></i>
                                                            Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-outline-primary">
                                                            <i data-feather="plus" class="me-1"></i>
                                                            Add Branch
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