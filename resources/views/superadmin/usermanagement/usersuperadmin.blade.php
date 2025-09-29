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
                                        <a href="{{ route('superadmin_usermanagement') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        <div class="page-header-icon"><i data-feather="user" style="width:25px; height:25px;"></i></div>
                                        Superadmins
                                    </h1>
                                </div>
                                <div class="col-12 col-xl-auto mb-2">
                                    <div class="d-flex flex-column flex-xl-row align-items-stretch align-items-xl-center justify-content-xl-end text-center text-xl-start">
                                        <a class="btn btn-outline-primary mb-2 mb-xl-0 me-0 me-xl-2" href="{{ route('superadmin_addsuperadmin') }}">
                                            <i class="me-1" data-feather="plus"></i>
                                            Add Superadmin
                                        </a>
                                        <a class="btn btn-outline-primary mb-2 mb-xl-0 me-0 me-xl-2" data-bs-toggle="modal" data-bs-target="#exportCsvModal">
                                            <i class="me-1" data-feather="download"></i>Export Superadmins
                                        </a>
                                    </div>
                                    <!-- Export CSV Confirmation Modal -->
                                    <div class="modal fade" id="exportCsvModal" tabindex="-1" aria-labelledby="exportCsvModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exportCsvModalLabel">Export Superadmin List</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    Are you sure you want to export the Superadmin list?
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                        <i data-feather="x" class="me-1"></i>
                                                        Cancel
                                                    </button>
                                                    <a href="{{ route('superadmin_export_usersuperadmin') }}" class="btn btn-outline-primary" id="exportCsvBtn">
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
                                                    const successMessage = document.createElement('div');
                                                    successMessage.className = 'alert alert-success alert-sm py-1 px-2 mt-3';
                                                    successMessage.textContent = 'Superadmin downloaded successfully.';
                                                    document.querySelector('.container-fluid').appendChild(successMessage);
                                                    setTimeout(() => {
                                                        successMessage.remove();
                                                    }, 5000);
                                                });
                                            }
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <div class="container-fluid px-4">
                    @if (session('success'))
                    <div class="alert alert-success alert-sm py-1 px-2">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive" style="overflow: visible !important;">
                                <table id="datatablesSimple" class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>PHOTO</th>
                                            <th>NAME</th>
                                            <th>EMAIL</th>
                                            <th>STATUS</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>PHOTO</th>
                                            <th>NAME</th>
                                            <th>EMAIL</th>
                                            <th>STATUS</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($superadmins as $superadmin)
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <img class="img-fluid rounded-circle" style="width: 48px; height: 48px; object-fit: cover;" src="{{ $superadmin->photo ? asset('assets/users/superadmin/' . $superadmin->photo) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Superadmin Photo" />
                                                </div>
                                            </td>
                                            <td>{{ $superadmin->lastname }}, {{ $superadmin->firstname }} {{ $superadmin->middlename }}</td>
                                            <td>{{ $superadmin->email }}</td>
                                            <td>
                                                @if($superadmin->isactive == 1)
                                                <span class="badge bg-light text-dark d-inline-flex align-items-center">
                                                    <i data-feather="check-circle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                    Active
                                                </span>
                                                @elseif($superadmin->isactive == 0)
                                                <span class="badge bg-light text-dark d-inline-flex align-items-center">
                                                    <i data-feather="trash-2" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                    Inactive
                                                </span>
                                                @elseif($superadmin->isactive == 2)
                                                <span class="badge bg-light text-dark d-inline-flex align-items-center">
                                                    <i data-feather="alert-triangle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                    Suspended
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_editusersuperadmin', $superadmin->id) }}">
                                                    <i data-feather="edit" class="me-1" style="width: 2em; height: 2em;"></i>
                                                </a>
                                                @if($superadmin->isactive == 1)
                                                <button type="button" class="btn btn-xs d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#suspendModal{{ $superadmin->id }}">
                                                    <i data-feather="alert-triangle" style="width: 2em; height: 2em;"></i>
                                                </button>
                                                <div class="modal fade" id="suspendModal{{ $superadmin->id }}" tabindex="-1" aria-labelledby="suspendModalLabel{{ $superadmin->id }}" aria-hidden="true" style="z-index: 1080;">
                                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="suspendModalLabel{{ $superadmin->id }}">Confirm Suspension</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                Are you sure you want to suspend this superadmin?
                                                            </div>
                                                            <div class="modal-footer justify-content-center">
                                                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                                    <i data-feather="x" class="me-1"></i>
                                                                    Cancel
                                                                </button>
                                                                <form action="{{ route('superadmin_suspend', $superadmin->id) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="btn btn-outline-primary">
                                                                        <i data-feather="alert-triangle" class="me-1"></i>
                                                                        Suspend
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-xs d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $superadmin->id }}">
                                                    <i data-feather="trash-2" style="width: 2em; height: 2em;"></i>
                                                </button>
                                                <div class="modal fade" id="deleteModal{{ $superadmin->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $superadmin->id }}" aria-hidden="true" style="z-index: 1080;">
                                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel{{ $superadmin->id }}">Confirm Deactivation</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                Are you sure you want to deactivate this superadmin?
                                                            </div>
                                                            <div class="modal-footer justify-content-center">
                                                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                                    <i data-feather="x" class="me-1"></i>
                                                                    Cancel
                                                                </button>
                                                                <form action="{{ route('superadmin_softdelete', $superadmin->id) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="btn btn-outline-primary">
                                                                        <i data-feather="trash-2" class="me-1"></i>
                                                                        Deactivate
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            @include('superadmin.partials.footer')
        </div>
    </div>
</body>

</html>