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
                                    Superadmin List
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="{{ route('superadmin_addsuperadmin') }}">
                                    <i class="me-1" data-feather="user-plus"></i>
                                    Add New Superadmin
                                </a>
                                <a class="btn btn-sm btn-primary ms-2" href="#" data-bs-toggle="modal" data-bs-target="#exportCsvModal">
                                    <i class="me-1" data-feather="download"></i>
                                </a>
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
                                                <div class="btn-group" role="group" aria-label="Export Actions">
                                                    <button type="button" class="btn btn-danger btn-sm d-inline-flex align-items-center" data-bs-dismiss="modal">
                                                        <i data-feather="x-circle" class="me-1"></i>
                                                        Cancel
                                                    </button>
                                                    <a href="{{ route('superadmin_export_usersuperadmin') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center" id="exportCsvBtn">
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
                                                successMessage.textContent = 'Superadmin downloaded successfully.';
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
                                    <th><span style="display: inline-flex; align-items: center;">PHOTO <i data-feather="image" style="margin-left: 4px;"></i></span></th>
                                    <th><span style="display: inline-flex; align-items: center;">LAST NAME <i data-feather="info" style="margin-left: 4px;"></i></span></th>
                                    <th><span style="display: inline-flex; align-items: center;">FIRST NAME <i data-feather="info" style="margin-left: 4px;"></i></span></th>
                                    <th><span style="display: inline-flex; align-items: center;">MIDDLE NAME <i data-feather="info" style="margin-left: 4px;"></i></span></th>
                                    <th><span style="display: inline-flex; align-items: center;">EMAIL <i data-feather="mail" style="margin-left: 4px;"></i></span></th>
                                    <th><span style="display: inline-flex; align-items: center;">CONTACT <i data-feather="phone" style="margin-left: 4px;"></i></span></th>
                                    <th><span style="display: inline-flex; align-items: center;">ROLE <i data-feather="shield" style="margin-left: 4px;"></i></span></th>
                                    <th><span style="display: inline-flex; align-items: center;">STATUS <i data-feather="activity" style="margin-left: 4px;"></i></span></th>
                                    <th><span style="display: inline-flex; align-items: center;">ACTION <i data-feather="settings" style="margin-left: 4px;"></i></span></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th><span style="display: inline-flex; align-items: center;">PHOTO <i data-feather="image" style="margin-left: 4px;"></i></span></th>
                                    <th><span style="display: inline-flex; align-items: center;">LAST NAME <i data-feather="info" style="margin-left: 4px;"></i></span></th>
                                    <th><span style="display: inline-flex; align-items: center;">FIRST NAME <i data-feather="info" style="margin-left: 4px;"></i></span></th>
                                    <th><span style="display: inline-flex; align-items: center;">MIDDLE NAME <i data-feather="info" style="margin-left: 4px;"></i></span></th>
                                    <th><span style="display: inline-flex; align-items: center;">EMAIL <i data-feather="mail" style="margin-left: 4px;"></i></span></th>
                                    <th><span style="display: inline-flex; align-items: center;">CONTACT <i data-feather="phone" style="margin-left: 4px;"></i></span></th>
                                    <th><span style="display: inline-flex; align-items: center;">ROLE <i data-feather="shield" style="margin-left: 4px;"></i></span></th>
                                    <th><span style="display: inline-flex; align-items: center;">STATUS <i data-feather="activity" style="margin-left: 4px;"></i></span></th>
                                    <th><span style="display: inline-flex; align-items: center;">ACTION <i data-feather="settings" style="margin-left: 4px;"></i></span></th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($superadmins->sortBy([
                                function ($a, $b) {
                                $order = [1, 2, 0];
                                $aIndex = array_search($a->isactive, $order);
                                $bIndex = array_search($b->isactive, $order);
                                if ($aIndex === $bIndex) {
                                return strcmp($a->lastname, $b->lastname); // fallback to lastname asc
                                }
                                return $aIndex <=> $bIndex;
                                    }, ['lastname', 'asc']
                                    ]) as $superadmin)
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <img class="img-fluid rounded-circle" style="width: 48px; height: 48px; object-fit: cover;" src="{{ $superadmin->photo ? asset('assets/users/superadmin/' . $superadmin->photo) : asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Superadmin Photo" />
                                            </div>
                                        </td>
                                        <td>{{ $superadmin->lastname }}</td>
                                        <td>{{ $superadmin->firstname }}</td>
                                        <td>{{ $superadmin->middlename }}</td>
                                        <td>{{ $superadmin->email }}</td>
                                        <td>{{ $superadmin->contact }}</td>
                                        <td><span class="badge bg-primary">{{ $superadmin->role }}</span></td>
                                        <td>
                                            @if($superadmin->isactive == 1)
                                            <span class="badge bg-primary d-inline-flex align-items-center">
                                                <i data-feather="check-circle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                Active
                                            </span>
                                            @elseif($superadmin->isactive == 0)
                                            <span class="badge bg-danger d-inline-flex align-items-center">
                                                <i data-feather="x-circle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                Inactive
                                            </span>
                                            @elseif($superadmin->isactive == 2)
                                            <span class="badge bg-warning d-inline-flex align-items-center">
                                                <i data-feather="alert-triangle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                Suspended
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Superadmin Actions">
                                                <a class="btn btn-primary btn-xs d-inline-flex align-items-center rounded-start" href="{{ route('superadmin_editusersuperadmin', $superadmin->id) }}" >
                                                    <i data-feather="edit" class="me-1" style="width: 2em; height: 2em;"></i>
                                                </a>
                                                <!-- Suspend Button triggers modal -->
                                                <button type="button" class="btn btn-warning btn-xs d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#suspendModal{{ $superadmin->id }}">
                                                    <i data-feather="alert-triangle" style="width: 2em; height: 2em;"></i>
                                                </button>
                                                <!-- Suspend Modal -->
                                                <div class="modal fade" id="suspendModal{{ $superadmin->id }}" tabindex="-1" aria-labelledby="suspendModalLabel{{ $superadmin->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="suspendModalLabel{{ $superadmin->id }}">Confirm Suspension</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                Are you sure you want to suspend this superadmin?
                                                            </div>
                                                            <div class="modal-footer justify-content-center">
                                                                <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                                <form action="{{ route('superadmin_suspend', $superadmin->id) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="btn btn-warning btn-sm">Suspend</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Deactivate Button triggers modal -->
                                                <button type="button" class="btn btn-danger btn-xs d-inline-flex align-items-center rounded-end" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $superadmin->id }}">
                                                    <i data-feather="trash-2" style="width: 2em; height: 2em;"></i>
                                                </button>
                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal{{ $superadmin->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $superadmin->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel{{ $superadmin->id }}">Confirm Deactivation</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                Are you sure you want to deactivate this superadmin?
                                                            </div>
                                                            <div class="modal-footer justify-content-center">
                                                                <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                                <form action="{{ route('superadmin_softdelete', $superadmin->id) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="btn btn-danger btn-sm">Deactivate</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
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