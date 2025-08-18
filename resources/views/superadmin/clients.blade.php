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
                                <h1 class="page-header-title d-flex align-items-center">
                                    <a href="{{ url()->previous() }}" class="btn btn-link p-0 me-2" title="Back">
                                        <i data-feather="arrow-left-circle"></i>
                                    </a>
                                    <div class="page-header-icon"><i data-feather="globe"></i></div>
                                    Client List
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3 d-flex flex-wrap align-items-center gap-2">
                                <a class="btn btn-sm btn-light text-primary" href="{{ route('superadmin_addclient') }}">
                                    <i class="me-1" data-feather="plus"></i>
                                    <span class="d-none d-sm-inline">Add New Client</span>
                                </a>
                                <a class="btn btn-sm btn-success ms-0 ms-sm-2 d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#importCsvModal">
                                    <i class="me-1" data-feather="upload"></i>
                                    <span class="d-none d-sm-inline small">Import Client</span>
                                </a>
                                <a class="btn btn-sm btn-primary ms-0 ms-sm-2 d-flex align-items-center" href="" data-bs-toggle="modal" data-bs-target="#exportCsvModal">
                                    <i class="me-1" data-feather="download"></i>
                                    <span class="d-none d-sm-inline small">Export Client</span>
                                </a>
                                <!-- Export CSV Confirmation Modal -->
                                <div class="modal fade" id="exportCsvModal" tabindex="-1" aria-labelledby="exportCsvModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exportCsvModalLabel">Export Clients List</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                Are you sure you want to export the Client list?
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <div class="btn-group" role="group" aria-label="Export Actions">
                                                    <button type="button" class="btn btn-danger btn-sm d-inline-flex align-items-center" data-bs-dismiss="modal">
                                                        <i data-feather="x-circle" class="me-1"></i>
                                                        Cancel
                                                    </button>
                                                    <a href="{{ route('superadmin_clients_export') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center" id="exportCsvBtn">
                                                        <i data-feather="download" class="me-1"></i>
                                                        Export
                                                    </a>
                                                </div>
                                            </div>
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
                                                successMessage.textContent = 'Clients downloaded successfully.';
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
                                            <form action="{{ route('superadmin_clients_import') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="importCsvModalLabel">Import Clients via CSV</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <input class="form-control" type="file" id="csv_file" name="csv_file" accept=".csv" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="btn-group" role="group" aria-label="Import Actions">
                                                        <button type="button" class="btn btn-danger btn-sm d-inline-flex align-items-center" data-bs-dismiss="modal">
                                                            <i data-feather="x-circle" class="me-1"></i>
                                                            Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-success btn-sm d-inline-flex align-items-center">
                                                            <i data-feather="upload" class="me-1"></i>
                                                            Upload
                                                        </button>
                                                    </div>
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
                                    <th>CLIENT</th>
                                    <th>TYPE</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>PHOTO</th>
                                    <th>CLIENT</th>
                                    <th>CLIENT</th>
                                    <th>TYPE</th>
                                    <th>ACTION</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($clients->where('isactive', 1)->sortBy('clientshortname') as $client)
                                <tr>
                                    <td>
                                        <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 70px; height: 70px; margin: auto;">
                                            <img src="{{ $client->clientphoto ? asset('assets/clients/' . $client->clientphoto) : asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Photo" width="70" height="70" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                            <span style="position: absolute; bottom: 4px; right: 4px; width: 16px; height: 16px; background: #28a745; border: 2px solid #fff; border-radius: 50%; display: block;"></span>
                                        </div>
                                    </td>
                                    <td>{{ $client->clientshortname }}</td>
                                    <td>{{ $client->clientname }}</td>
                                    <td>
                                        @if($client->clienttype === 'Government')
                                        <span class="badge bg-danger">Government</span>
                                        @elseif($client->clienttype === 'Private')
                                        <span class="badge bg-primary">Private</span>
                                        @else
                                        <span class="badge bg-secondary">{{ ucfirst($client->clienttype) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Client Actions">
                                            <a class="btn btn-success btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_viewclients', $client->id) }}">
                                                <i data-feather="eye" class="me-1"></i>
                                                View
                                            </a>
                                            <a class="btn btn-primary btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_editclients', $client->id) }}">
                                                <i data-feather="edit" class="me-1"></i>
                                                Edit
                                            </a>
                                            <button type="button" class="btn btn-danger btn-xs d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $client->id }}">
                                                <i data-feather="trash-2"></i>
                                                Delete
                                            </button>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $client->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $client->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $client->id }}">Confirm Deletion</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        Are you sure you want to delete this client?
                                                    </div>
                                                    <div class="modal-footer justify-content-center">
                                                        <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('superadmin_softdeleteclient', $client->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Centered Button to Show Inactive Clients -->
                        <div class="text-center my-4">
                            <button class="btn btn-light btn-sm" id="showInactiveBtn">
                                <i data-feather="eye" class="me-1"></i>
                                Show Inactive Clients
                            </button>
                        </div>
                    </div>
                </div>


                <!-- Inactive Clients Table (hidden by default) -->
                <div id="inactiveClientsTable" style="display:none;">
                    <div class="card">
                        <div class="card-header">INACTIVE CLIENTS</div>
                        <div class="card-body">
                            <table id="inactiveDatatablesSimple">
                            <thead>
                                <tr>
                                    <th>PHOTO</th>
                                    <th>CLIENT</th>
                                    <th>CLIENT</th>
                                    <th>TYPE</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>PHOTO</th>
                                    <th>CLIENT</th>
                                    <th>CLIENT</th>
                                    <th>TYPE</th>
                                    <th>ACTION</th>
                                </tr>
                            </tfoot>
                                <tbody>
                                    @foreach ($clients->where('isactive', 0)->sortBy('clientshortname') as $client)
                                    <tr>
                                        <td>
                                            <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 70px; height: 70px; margin: auto;">
                                                <img src="{{ $client->clientphoto ? asset('assets/clients/' . $client->clientphoto) : asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Photo" width="70" height="70" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                                <span style="position: absolute; bottom: 4px; right: 4px; width: 16px; height: 16px; background:rgb(251, 0, 0); border: 2px solid #fff; border-radius: 50%; display: block;"></span>
                                            </div>
                                        </td>
                                        <td>{{ $client->clientshortname }}</td>
                                        <td>{{ $client->clientname }}</td>
                                        <td>
                                            @if($client->clienttype === 'Government')
                                            <span class="badge bg-danger">Government</span>
                                            @elseif($client->clienttype === 'Private')
                                            <span class="badge bg-primary">Private</span>
                                            @else
                                            <span class="badge bg-secondary">{{ ucfirst($client->clienttype) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Client Actions">
                                                <a class="btn btn-success btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_viewclients', $client->id) }}">
                                                    <i data-feather="eye" class="me-1"></i>
                                                    View
                                                </a>
                                                <a class="btn btn-primary btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_editclients', $client->id) }}">
                                                    <i data-feather="edit" class="me-1"></i>
                                                    Edit
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Initialize datatable for inactive clients
                                    if (document.getElementById('inactiveDatatablesSimple')) {
                                        new simpleDatatables.DataTable(document.getElementById('inactiveDatatablesSimple'));
                                    }
                                });
                            </script>
                        </div>
                        <script>
                            document.getElementById('showInactiveBtn').addEventListener('click', function() {
                                var table = document.getElementById('inactiveClientsTable');
                                if (table.style.display === 'none') {
                                    table.style.display = 'block';
                                    this.innerHTML = '<i data-feather="eye-off" class="me-1"></i> Hide Inactive Clients';
                                    feather.replace();
                                } else {
                                    table.style.display = 'none';
                                    this.innerHTML = '<i data-feather="eye" class="me-1"></i> Show Inactive Clients';
                                    feather.replace();
                                }
                            });
                        </script>
                    </div>
                </div>
        </main>


        @include('superadmin.footer')
        </body>

</html>