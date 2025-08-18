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
                                    <div class="page-header-icon"><i data-feather="plus"></i></div>
                                    Add Branch
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="{{ route('superadmin_branches') }}">
                                    <i class="me-1" data-feather="arrow-left"></i>
                                    Back to Branch List
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
                            <div class="card-header">Branch Details</div>
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
                                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                        <!-- Add Branch Button triggers modal -->
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#confirmAddBranchModal">
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
                                                <div class="modal-footer">
                                                    <div class="btn-group" role="group" aria-label="Confirm Add Branch">
                                                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary btn-sm">Yes, Add Branch</button>
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