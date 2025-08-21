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
                                            Admins
                                        </h1>
                                    </div>
                                    <div class="col-12 col-xl-auto mb-3">
                                        <a class="btn btn-light text-primary mb-2 mb-xl-0 me-0 me-xl-2" href="{{ route('superadmin_addadmin') }}">
                                            <i class="me-1" data-feather="plus"></i>
                                            Add Admin
                                        </a>
                                        <a class="btn btn-light text-primary mb-2 mb-xl-0 me-0 me-xl-2" data-bs-toggle="modal" data-bs-target="#exportCsvModal">
                                            <i class="me-1" data-feather="download"></i>Export Admins
                                        </a>

                                        <!-- Export CSV Confirmation Modal -->
                                        <div class="modal fade" id="exportCsvModal" tabindex="-1" aria-labelledby="exportCsvModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exportCsvModalLabel">Export Admin List</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        Are you sure you want to export the Admin list?
                                                    </div>
                                                    <div class="modal-footer justify-content-center">
                                                        <button type="button" class="btn btn-light text-danger" data-bs-dismiss="modal">
                                                            <i data-feather="x" class="me-1"></i>
                                                            Cancel
                                                        </button>
                                                        <a href="{{ route('superadmin_export_useradmin') }}" class="btn btn-light text-primary" id="exportCsvBtn">
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
                                                        successMessage.textContent = 'Admin downloaded successfully.';
                                                        document.querySelector('.container-fluid').appendChild(successMessage);
                                                        setTimeout(() => {
                                                            successMessage.remove();
                                                        }, 5000); // Remove after 5 seconds
                                                    });
                                                }
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                    <!-- Main page content-->
                    <div class="container-fluid px-4">
                        @if (session('success'))
                        <div class="alert alert-success alert-sm py-1 px-2">
                            {{ session('success') }}
                        </div>
                        @endif
                        <div class="card">
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>PHOTO</th>
                                            <th>EMPLOYEE</th>
                                            <th>LAST NAME</th>
                                            <th>FIRST NAME</th>
                                            <th>MIDDLE NAME</th>
                                            <th>EMAIL</th>
                                            <th>CONTACT</th>
                                            <th>STATUS</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>PHOTO</th>
                                            <th>EMPLOYEE</th>
                                            <th>LAST NAME</th>
                                            <th>FIRST NAME</th>
                                            <th>MIDDLE NAME</th>
                                            <th>EMAIL</th>
                                            <th>CONTACT</th>
                                            <th>STATUS</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($admins->sortBy([
                                        function ($a, $b) {
                                        $order = [1, 2, 0];
                                        $aIndex = array_search($a->isactive, $order);
                                        $bIndex = array_search($b->isactive, $order);
                                        if ($aIndex === $bIndex) {
                                        return strcmp($a->lastname, $b->lastname); // fallback to lastname asc
                                        }
                                        return $aIndex <=> $bIndex;
                                            }, ['lastname', 'asc']
                                            ]) as $admin)
                                            <tr>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <img class="img-fluid rounded-circle" style="width: 48px; height: 48px; object-fit: cover;" src="{{ $admin->photo ? asset('assets/users/admin/' . $admin->photo) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Admin Photo" />
                                                    </div>
                                                </td>
                                                <td>{{ $admin->employeenumber }}</td>
                                                <td>{{ $admin->lastname }}</td>
                                                <td>{{ $admin->firstname }}</td>
                                                <td>{{ $admin->middlename }}</td>
                                                <td>{{ $admin->email }}</td>
                                                <td>{{ $admin->contact }}</td>
                                                <td>
                                                    @if($admin->isactive == 1)
                                                    <span class="badge bg-light text-dark d-inline-flex align-items-center">
                                                        <i data-feather="check-circle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                        Active
                                                    </span>
                                                    @elseif($admin->isactive == 0)
                                                    <span class="badge bg-light text-dark d-inline-flex align-items-center">
                                                        <i data-feather="trash-2" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                        Inactive
                                                    </span>
                                                    @elseif($admin->isactive == 2)
                                                    <span class="badge bg-light text-dark d-inline-flex align-items-center">
                                                        <i data-feather="alert-triangle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                        Suspended
                                                    </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a class="btn btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_edituseradmin', $admin->id) }}">
                                                        <i data-feather="edit" class="me-1" style="width: 2em; height: 2em;"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-xs d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#suspendModal{{ $admin->id }}">
                                                        <i data-feather="alert-triangle" style="width: 2em; height: 2em;"></i>
                                                    </button>
                                                    <div class="modal fade" id="suspendModal{{ $admin->id }}" tabindex="-1" aria-labelledby="suspendModalLabel{{ $admin->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="suspendModalLabel{{ $admin->id }}">Confirm Suspension</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    Are you sure you want to suspend this admin?
                                                                </div>
                                                                <div class="modal-footer justify-content-center">
                                                                    <button type="button" class="btn btn-light text-danger" data-bs-dismiss="modal">
                                                                        <i data-feather="x" class="me-1"></i>
                                                                        Cancel
                                                                        <i data-feather="x" class="me-1"></i></button>
                                                                    <form action="{{ route('superadmin_adminsuspend', $admin->id) }}" method="POST" style="display:inline;">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <button type="submit" class="btn btn-light text-primary">
                                                                            <i data-feather="alert-triangle" class="me-1"></i>
                                                                            Suspend
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-xs d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $admin->id }}">
                                                        <i data-feather="trash-2" style="width: 2em; height: 2em;"></i>
                                                    </button>
                                                    <div class="modal fade" id="deleteModal{{ $admin->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $admin->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="deleteModalLabel{{ $admin->id }}">Confirm Deactivation</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    Are you sure you want to deactivate this admin?
                                                                </div>
                                                                <div class="modal-footer content-center">
                                                                    <button type="button" class="btn btn-light text-danger" data-bs-dismiss="modal">
                                                                        <i data-feather="x" class="me-1"></i>
                                                                        Cancel
                                                                    </button>
                                                                    <form action="{{ route('superadmin_adminsoftdelete', $admin->id) }}" method="POST" style="display:inline;">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <button type="submit" class="btn btn-light text-primary">
                                                                            <i data-feather="trash-2" class="me-1"></i>
                                                                            Deactivate
                                                                        </button>
                                                                    </form>
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