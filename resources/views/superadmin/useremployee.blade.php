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
                                        <div class="page-header-icon"><i data-feather="user"></i></div>
                                        Employee List
                                    </h1>
                                </div>
                                <div class="col-12 col-xl-auto mb-3">
                                    <a class="btn btn-sm btn-success ms-2" href="#" data-bs-toggle="modal" data-bs-target="#importCsvModal">
                                        <i class="me-1" data-feather="upload"></i>
                                        <span class="small">Import Employee</span> </a>
                                    <a class="btn btn-sm btn-primary ms-2" href="#" data-bs-toggle="modal" data-bs-target="#exportCsvModal">
                                        <i class="me-1" data-feather="download"></i>
                                        <span class="small">Export Employee</span> </a>
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
                                                    <div class="modal-footer">
                                                        <div class="btn-group" role="group" aria-label="Export Actions">
                                                            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
                                                                <i data-feather="x-circle" class="me-1"></i>
                                                                Cancel
                                                            </button>
                                                            <button type="submit" class="btn btn-primary btn-sm" id="exportCsvBtn">
                                                                <i data-feather="download" class="me-1"></i>
                                                                Export
                                                            </button>
                                                        </div>
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
                                        <th>CLIENT</th>
                                        <th>BRANCH</th>
                                        <th>EMPLOYEE NUMBER</th>
                                        <th>POSITION</th>
                                        <th>LAST NAME</th>
                                        <th>FIRST NAME</th>
                                        <th>MIDDLE NAME</th>
                                        <th>EMAIL</th>
                                        <th>CONTACT</th>
                                        <th>ROLE</th>
                                        <th>STATUS</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>PHOTO</th>
                                        <th>CLIENT</th>
                                        <th>BRANCH</th>
                                        <th>EMPLOYEE NUMBER</th>
                                        <th>POSITION</th>
                                        <th>LAST NAME</th>
                                        <th>FIRST NAME</th>
                                        <th>MIDDLE NAME</th>
                                        <th>EMAIL</th>
                                        <th>CONTACT</th>
                                        <th>ROLE</th>
                                        <th>STATUS</th>
                                        <th>ACTION</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <img class="img-fluid rounded-circle" style="width: 48px; height: 48px; object-fit: cover;" src="{{ $user->photo ? asset('assets/users/admin/' . $user->photo) : asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Admin Photo" />
                                            </div>
                                        </td>
                                        <td>{{ $user->clientname }}</td>
                                        <td>{{ $user->branchname }}</td>
                                        <td>{{ $user->employeenumber }}</td>
                                        <td>{{ $user->position }}</td>
                                        <td>{{ $user->lastname }}</td>
                                        <td>{{ $user->firstname }}</td>
                                        <td>{{ $user->middlename }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->contact }}</td>
                                        <td><span class="badge bg-info">{{ $user->role }}</span></td>
                                        <td>
                                            @if($user->isactive == 1)
                                            <span class="badge bg-primary d-inline-flex align-items-center">
                                                <i data-feather="check-circle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                Active
                                            </span>
                                            @elseif($user->isactive == 0)
                                            <span class="badge bg-danger d-inline-flex align-items-center">
                                                <i data-feather="x-circle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                Inactive
                                            </span>
                                            @elseif($user->isactive == 2)
                                            <span class="badge bg-warning d-inline-flex align-items-center">
                                                <i data-feather="alert-triangle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                Suspended
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Superadmin Actions">
                                                <a class="btn btn-primary btn-xs d-inline-flex align-items-center rounded-start" href="{{ route('superadmin_editusersuperadmin', $user->id) }}">
                                                    <i data-feather="edit" class="me-1"></i>
                                                    Edit
                                                </a>
                                                <!-- Suspend Button triggers modal -->
                                                <button type="button" class="btn btn-warning btn-xs d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#suspendModal{{ $user->id }}">
                                                    <i data-feather="alert-triangle"></i>
                                                    Suspend
                                                </button>
                                                <!-- Suspend Modal -->
                                                <div class="modal fade" id="suspendModal{{ $user->id }}" tabindex="-1" aria-labelledby="suspendModalLabel{{ $user->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="suspendModalLabel{{ $user->id }}">Confirm Suspension</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                Are you sure you want to suspend this admin?
                                                            </div>
                                                            <div class="modal-footer justify-content-center">
                                                                <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                                <form action="{{ route('superadmin_suspend', $user->id) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="btn btn-warning btn-sm">Suspend</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Deactivate Button triggers modal -->
                                                <button type="button" class="btn btn-danger btn-xs d-inline-flex align-items-center rounded-end" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                                    <i data-feather="trash-2"></i>
                                                    Deactivate
                                                </button>
                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">Confirm Deactivation</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                Are you sure you want to deactivate this user?
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