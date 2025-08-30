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
                                        <a href="{{ route('superadmin_clientmanagement') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        <div class="page-header-icon"><i data-feather="list" style="width:25px; height:25px;"></i></div>
                                        Branch List
                                    </h1>
                                </div>
                                <div class="col-12 col-xl-auto mb-2">
                                    <div class="d-flex flex-column flex-xl-row align-items-stretch align-items-xl-center justify-content-xl-end text-center text-xl-start">
                                        <a class="btn btn-outline-primary mb-2 mb-xl-0 me-0 me-xl-2" href="{{ route('superadmin_addbranch') }}">
                                            <i class="me-1" data-feather="plus"></i>
                                            Add Branch
                                        </a>
                                        <a class="btn btn-outline-success mb-2 mb-xl-0 me-0 me-xl-2" href="#" data-bs-toggle="modal" data-bs-target="#importCsvModal">
                                            <i class="me-1" data-feather="upload"></i>
                                            Import</span>
                                        </a>
                                        <a class="btn btn-outline-primary mb-2 mb-xl-0 me-0 me-xl-2" href="" data-bs-toggle="modal" data-bs-target="#exportCsvModal">
                                            <i class="me-1" data-feather="download"></i>
                                            Export</span>
                                        </a>
                                        <!-- Export CSV Confirmation Modal -->
                                        <div class="modal fade" id="exportCsvModal" tabindex="-1" aria-labelledby="exportCsvModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('superadmin_branches_export') }}" method="GET">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exportCsvModalLabel">Export Branches List</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-floating">
                                                                <select class="form-select" id="clientname" name="clientname" required>
                                                                    <option value="ALL CLIENTS">ALL CLIENTS</option>
                                                                    @foreach($clients->sortBy('clientshortname') as $client)
                                                                    <option value="{{ $client->clientshortname }}">{{ $client->clientshortname }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <label for="clientname">Select Client</label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                                <i data-feather="x" class="me-1"></i>
                                                                Cancel
                                                            </button>
                                                            <button type="submit" class="btn btn-outline-primary" id="exportCsvBtn">
                                                                <i data-feather="download" class="me-1"></i>
                                                                Export
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const exportBtn = document.getElementById('exportCsvBtn');
                                                if (exportBtn) {
                                                    exportBtn.addEventListener('click', function(e) {
                                                        const modal = bootstrap.Modal.getInstance(document.getElementById('exportCsvModal'));
                                                        if (modal) {
                                                            modal.hide();
                                                        }
                                                    });
                                                    exportBtn.addEventListener('click', function() {
                                                        // Show success message after export
                                                        const successMessage = document.createElement('div');
                                                        successMessage.className = 'alert alert-success alert-sm py-1 px-2 mt-3';
                                                        successMessage.textContent = 'Branches downloaded successfully.';
                                                        document.querySelector('.container-fluid').appendChild(successMessage);
                                                        setTimeout(() => {
                                                            successMessage.remove();
                                                        }, 5000); // Remove after 5 seconds
                                                    });
                                                }
                                            });
                                        </script>
                                        <!-- Import CSV Modal -->
                                        <div class="modal fade" id="importCsvModal" tabindex="-1" aria-labelledby="importCsvModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('superadmin_branches_import') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="importCsvModalLabel">Import Branches via CSV</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <input class="form-control" type="file" id="csv_file" name="csv_file" accept=".csv" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                                <i data-feather="x" class="me-1"></i>
                                                                Cancel
                                                            </button>
                                                            <button type="submit" class="btn btn-outline-success">
                                                                <i data-feather="upload" class="me-1"></i>
                                                                Upload
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </header>
                <!-- Main page content-->
                <div class="container-fluid px-4">
                    <div class="card">

                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                            @endif
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>PHOTO</th>
                                        <th>CLIENT</th>
                                        <th>BRANCH</th>
                                        <th>REGION</th>
                                        <th>TYPE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>PHOTO</th>
                                        <th>CLIENT</th>
                                        <th>BRANCH</th>
                                        <th>REGION</th>
                                        <th>TYPE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($branches->sortBy('clientname') as $branch)
                                    @if($branch->isactive == 1)
                                    <tr>
                                        <td>
                                            @if($branch->clientphoto)
                                            <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 70px; height: 70px; margin: auto;">
                                                <img src="{{ $branch->clientphoto ? asset('assets/clients/' . $branch->clientphoto) : asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Photo" width="55" height="55" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                                <span style="position: absolute; bottom: 7px; right: 7px; width: 14px; height: 14px; background: #28a745; border: 2px solid #fff; border-radius: 50%; display: block; z-index: 2;"></span>
                                            </div>
                                            @else
                                            <img src="{{ asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Photo" width="50" height="50" style="object-fit:cover; display:block; margin:auto;">
                                            @endif
                                        </td>
                                        <td>{{ $branch->clientname }}</td>
                                        <td>{{ $branch->branchname }}</td>
                                        <td>{{ $branch->branchregion }}</td>
                                        <td>
                                            @if($client->clienttype === 'Government')
                                            <span class="text-danger">Government</span>
                                            @else
                                            <span class="text-primary">Private</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Client Actions">
                                                <a class="btn btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_viewbranch', $branch->id) }}">
                                                    <i data-feather="eye" class="me-1" style="width: 2em; height: 2em;"></i>
                                                </a>
                                                <a class="btn btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_editbranch', $branch->id) }}">
                                                    <i data-feather="edit" class="me-1" style="width: 2em; height: 2em;"></i>
                                                </a>
                                                <button type="button" class="btn btn-xs d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $branch->id }}">
                                                    <i data-feather="trash-2" class="me-1" style="width: 2em; height: 2em;"></i>
                                                </button>
                                            </div>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $branch->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $branch->id }}" aria-hidden="true" style="z-index: 1080;">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel{{ $branch->id }}">Confirm Deletion</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            Are you sure you want to delete this branch?
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                                <i data-feather="x" class="me-1"></i>
                                                                Cancel
                                                            </button>
                                                            <form action="{{ route('superadmin_softdeletebranch', $branch->id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-outline-primary">
                                                                    <i data-feather="trash-2" class="me-1"></i>
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Centered Button to Show Inactive Clients -->
                            <div class="text-center my-4">
                                <button class="btn btn-light text-body" id="showInactiveBtn">
                                    <i data-feather="eye" class="me-1"></i>
                                    Show Inactive Branches
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- Inactive Clients Table (hidden by default) -->
                    <div id="inactiveBranchesTable" style="display:none;">
                        <div class="card">
                            <div class="card-header text-body">INACTIVE BRANCHES</div>
                            <div class="card-body">
                                <table id="inactiveDatatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>PHOTO</th>
                                            <th>CLIENT</th>
                                            <th>BRANCH</th>
                                            <th>REGION</th>
                                            <th>TYPE</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>PHOTO</th>
                                            <th>CLIENT</th>
                                            <th>BRANCH</th>
                                            <th>REGION</th>
                                            <th>TYPE</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($branches->where('isactive', 0) as $branch)
                                        <tr>
                                            <td>
                                                <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 70px; height: 70px; margin: auto;">
                                                    <img src="{{ $branch->clientphoto ? asset('assets/clients/' . $branch->clientphoto) : asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Photo" width="55" height="55" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                                    <span style="position: absolute; bottom: 7px; right: 7px; width: 14px; height: 14px; background:rgb(255, 0, 0); border: 2px solid #fff; border-radius: 50%; display: block; z-index: 2;"></span>
                                                </div>
                                            </td>
                                            <td>{{ $branch->clientname }}</td>
                                            <td>{{ $branch->branchname }}</td>
                                            <td>{{ $branch->branchregion }}</td>
                                            <td>
                                                @if($client->clienttype === 'Government')
                                                <span class="text-danger">Government</span>
                                                @else
                                                <span class="text-primary">Private</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Branch Actions">
                                                    <a class="btn btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_viewbranch', $branch->id) }}">
                                                        <i data-feather="eye" class="me-1" style="width: 2em; height: 2em;"></i>
                                                    </a>
                                                    <a class="btn btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_editbranch', $branch->id) }}">
                                                        <i data-feather="edit" class="me-1" style="width: 2em; height: 2em;"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        // Initialize datatable for inactive branches
                                        if (document.getElementById('inactiveDatatablesSimple')) {
                                            new simpleDatatables.DataTable(document.getElementById('inactiveDatatablesSimple'));
                                        }
                                    });
                                </script>
                            </div>
                            <script>
                                document.getElementById('showInactiveBtn').addEventListener('click', function() {
                                    var table = document.getElementById('inactiveBranchesTable');
                                    if (table.style.display === 'none') {
                                        table.style.display = 'block';
                                        this.innerHTML = '<i data-feather="eye-off" class="me-1"></i> Hide Inactive Branches';
                                        feather.replace();
                                    } else {
                                        table.style.display = 'none';
                                        this.innerHTML = '<i data-feather="eye" class="me-1"></i> Show Inactive Branches';
                                        feather.replace();
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
        </div>
        </main>
    </div>
    @include('superadmin.footer')
</body>

</html>