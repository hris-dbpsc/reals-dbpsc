<!DOCTYPE html>
<html lang="en">
@include('superadmin.partials.header')

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
                                        <a href="{{ route('superadmin_clientmanagement') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        <div class="page-header-icon"><i data-feather="users" style="width:25px; height:25px;"></i></div>
                                        Client List
                                    </h1>
                                </div>
                                <div class="col-12 col-xl-auto mb-2">
                                    <div class="d-flex flex-column flex-xl-row align-items-stretch align-items-xl-center justify-content-xl-end text-center text-xl-start">
                                        <a class="btn btn-outline-primary mb-2 mb-xl-0 me-0 me-xl-2" href="{{ route('superadmin_addclient') }}">
                                            <i class="me-1" data-feather="plus"></i>
                                            Add Client
                                        </a>
                                        <a class="btn btn-outline-success mb-2 mb-xl-0 me-0 me-xl-2" href="#" data-bs-toggle="modal" data-bs-target="#importCsvModal">
                                            <i class="me-1" data-feather="upload"></i>
                                            Import
                                        </a>
                                        <a class="btn btn-outline-primary mb-2 mb-xl-0 me-0 me-xl-2" href="#" data-bs-toggle="modal" data-bs-target="#exportCsvModal">
                                            <i class="me-1" data-feather="download"></i>
                                            Export
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
                                                        <button type="button" class="btn btn-outline-danger d-inline-flex align-items-center" data-bs-dismiss="modal">
                                                            <i data-feather="x" class="me-1"></i>
                                                            Cancel
                                                        </button>
                                                        <a href="{{ route('superadmin_clients_export') }}" class="btn btn-outline-primary d-inline-flex align-items-center" id="exportCsvBtn">
                                                            <i data-feather="download" class="me-1"></i>
                                                            Export
                                                        </a>
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
                                                            <div class="mb-2">
                                                                <input class="form-control" type="file" id="csv_file" name="csv_file" accept=".csv" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-outline-danger d-flex align-items-center" data-bs-dismiss="modal">
                                                                <i data-feather="x" class="me-1"></i>
                                                                Cancel
                                                            </button>
                                                            <button type="submit" class="btn btn-outline-success d-flex align-items-center">
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
                            <div class="alert alert-success alert-sm py-1 px-2">
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
                                    @foreach ($activeClients as $client)
                                    <tr>
                                        <td>
                                            <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; margin: auto;">
                                                <img src="{{ $client->clientphoto ? asset('assets/clients/' . $client->clientphoto) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Photo" width="40" height="40" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                                <span style="position: absolute; bottom: 2px; right: 2px; width: 10px; height: 10px; background: #28a745; border: 2px solid #fff; border-radius: 50%; display: block;"></span>
                                            </div>
                                        </td>
                                        <td>{{ $client->clientshortname }}</td>
                                        <td>{{ $client->clientname }}</td>
                                        <td>
                                            @if($client->clienttype === 'Government')
                                            <span class="text-danger">Government</span>
                                            @else
                                            <span class="text-primary">Private</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Client Actions">
                                                <a class="btn btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_viewclients', $client->id) }}">
                                                    <i data-feather="eye" class="me-1" style="width: 2em; height: 2em;"></i>
                                                </a>
                                                <a class="btn btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_editclient', $client->id) }}">
                                                    <i data-feather="edit" class="me-1" style="width: 2em; height: 2em;"></i>
                                                </a>
                                                <button type="button" class="btn btn-xs d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $client->id }}">
                                                    <i data-feather="trash-2" class="me-1" style="width: 2em; height: 2em;"></i>
                                                </button>
                                            </div>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $client->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $client->id }}" aria-hidden="true" style="z-index: 1080;">
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
                                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                                <i data-feather="x" class="me-1"></i>
                                                                Cancel
                                                            </button>
                                                            <form action="{{ route('superadmin_softdeleteclient', $client->id) }}" method="POST" style="display:inline;">
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
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Centered Button to Show Inactive Clients -->
                            <div class="text-center my-4">
                                <button class="btn btn-light text-body" id="showInactiveBtn">
                                    <i data-feather="eye" class="me-1"></i>
                                    Show Inactive Clients
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- Inactive Clients Table (hidden by default) -->
                    <div id="inactiveClientsTable" style="display:none;">
                        <div class="card">
                            <div class="card-header text-body">INACTIVE CLIENTS</div>
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
                                        @foreach ($inactiveClients as $client)
                                        <tr>
                                            <td>
                                                <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; margin: auto;">
                                                    <img src="{{ $client->clientphoto ? asset('assets/clients/' . $client->clientphoto) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Photo" width="40" height="40" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                                    <span style="position: absolute; bottom: 2px; right: 2px; width: 10px; height: 10px; background:rgb(251, 0, 0); border: 2px solid #fff; border-radius: 50%; display: block;"></span>
                                                </div>
                                            </td>
                                            <td>{{ $client->clientshortname }}</td>
                                            <td>{{ $client->clientname }}</td>
                                            <td>
                                                @if($client->clienttype === 'Government')
                                                <span class="text-danger">Government</span>
                                                @else
                                                <span class="text-primary">Private</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Client Actions">
                                                    <a class="btn btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_viewclients', $client->id) }}">
                                                        <i data-feather="eye" class="me-1" style="width: 2em; height: 2em;"></i>
                                                    </a>
                                                    <a class="btn btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_editclient', $client->id) }}">
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
            @include('superadmin.partials.footer')
        </div>
    </div>
</body>

</html>