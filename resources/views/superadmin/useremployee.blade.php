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
                                            <a href="{{ route('superadmin_usermanagement') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                                <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                            </a>
                                            <div class="page-header-icon"><i data-feather="user" style="width:25px; height:25px;"></i></div>
                                            Employees
                                        </h1>
                                    </div>
                                    <div class="col-12 col-xl-auto mb-3">
                                        <div class="d-flex flex-column flex-xl-row align-items-stretch align-items-xl-center justify-content-xl-end text-center text-xl-start">
                                            <a class="btn btn-light text-success mb-2 mb-xl-0 me-0 me-xl-2" href="#" data-bs-toggle="modal" data-bs-target="#importCsvModal">
                                                <i class="me-1" data-feather="upload"></i>Import
                                            </a>
                                            <a class="btn btn-light text-primary mb-2 mb-xl-0 me-0 me-xl-2" href="#" data-bs-toggle="modal" data-bs-target="#exportCsvModal">
                                                <i class="me-1" data-feather="download"></i>Export
                                            </a>
                                            <!-- Export CSV Modal -->
                                            <div class="modal fade" id="exportCsvModal" tabindex="-1" aria-labelledby="exportCsvModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <form action="{{ route('superadmin_useremployee_export') }}" method="GET">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exportCsvModalLabel">Export Employees</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
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
                                                            </div>
                                                            <div class="modal-footer justify-content-center">
                                                                <button type="button" class="btn btn-light text-danger" data-bs-dismiss="modal">
                                                                    <i data-feather="x" class="me-1"></i>
                                                                    Cancel
                                                                </button>
                                                                <button type="submit" class="btn btn-light text-primary" id="exportCsvBtn">
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
                                                            successMessage.textContent = 'Employee downloaded successfully.';
                                                            document.querySelector('.container-fluid').appendChild(successMessage);
                                                            setTimeout(() => {
                                                                successMessage.remove();
                                                            }, 5000); // Remove after 5 seconds
                                                        });
                                                    }
                                                });
                                            </script>
                                        </div>
                                        <!-- Import CSV Modal -->
                                        <div class="modal fade" id="importCsvModal" tabindex="-1" aria-labelledby="importCsvModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('superadmin_useremployee_import') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="importCsvModalLabel">Import Employees via CSV</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <input class="form-control" type="file" id="csv_file" name="csv_file" accept=".csv" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-light text-danger" data-bs-dismiss="modal">
                                                                <i data-feather="x" class="me-1"></i>
                                                                Cancel
                                                            </button>
                                                            <button type="submit" class="btn btn-light text-primary">
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
                    </header>
                    <!-- Main page content-->
                    <div class="container-fluid px-4">
                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success')}}
                        </div>
                        @endif
                        <div class="card">
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>PHOTO</th>
                                            <th>BRANCH</th>
                                            <th>EMPLOYEE NUMBER</th>
                                            <th>POSITION</th>
                                            <th>LAST NAME</th>
                                            <th>FIRST NAME</th>
                                            <th>MIDDLE NAME</th>
                                            <th>EMAIL</th>
                                            <th>CONTACT</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>PHOTO</th>
                                            <th>BRANCH</th>
                                            <th>EMPLOYEE NUMBER</th>
                                            <th>POSITION</th>
                                            <th>LAST NAME</th>
                                            <th>FIRST NAME</th>
                                            <th>MIDDLE NAME</th>
                                            <th>EMAIL</th>
                                            <th>CONTACT</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <img class="img-fluid rounded-circle" style="width: 48px; height: 48px; object-fit: cover;" src="{{ $user->photo ? asset('assets/users/admin/' . $user->photo) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Admin Photo" />
                                                </div>
                                            </td>
                                            <td>{{ $user->branchname }}</td>
                                            <td>{{ $user->employeenumber }}</td>
                                            <td>{{ $user->position }}</td>
                                            <td>{{ $user->lastname }}</td>
                                            <td>{{ $user->firstname }}</td>
                                            <td>{{ $user->middlename }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->contact }}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Superadmin Actions">
                                                    <a class="btn btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_editusersuperadmin', $user->id) }}">
                                                        <i data-feather="edit" class="me-1" style="width: 2em; height: 2em;"></i>
                                                    </a>
                                                    <!-- Deactivate Button triggers modal -->
                                                    <button type="button" class="btn btn-xs d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                                        <i data-feather="trash-2" style="width: 2em; height: 2em;"></i>
                                                    </button>
                                                    <!-- Delete Modal -->
                                                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">Confirm Deactivation</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    Are you sure you want to deactivate this employee?
                                                                </div>
                                                                <div class="modal-footer justify-content-center">
                                                                    <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                                    <form action="{{ route('superadmin_softdelete', $user->id) }}" method="POST" style="display:inline;">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <button type="submit" class="btn btn-danger btn-sm">Deactivate</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                @include('superadmin.footer')
            </div>
    </body>

    </html>