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
                                    <div class="page-header-icon"><i data-feather="edit"></i></div>
                                    Edit Client
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="{{ route('superadmin_clients') }}">
                                    <i class="me-1" data-feather="arrow-left"></i>
                                    Back to Client List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-fluid px-4 mt-4">
                <div class="row">
                    <div class="col-xl-4">
                        <!-- Client profile picture card-->
                        <div class="card mb-4 mb-xl-0">
                            <div class="card-header">Client Profile Picture</div>
                            <div class="card-body text-center">
                                <form action="{{ route('superadmin_editclient_uploadprofilepicture', $client->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <!-- Profile picture image-->
                                    <img id="profilePhotoPreview" class="img-account-profile mb-2" src="{{ $client->clientphoto ? asset('assets/clients/' . $client->clientphoto) : asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Client Profile Photo" />
                                    <!-- Profile picture help block-->
                                    <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                                    <!-- Profile picture upload button-->
                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                        <div class="btn-group" role="group" aria-label="Profile Photo Actions">
                                            <label class="btn btn-sm btn-secondary mb-0">
                                                <input type="file" name="photo" accept="image/png, image/jpeg" style="display:none;" onchange="previewPhoto(this)">
                                                Upload Photo
                                            </label>
                                            <button type="button" class="btn btn-sm btn-success" id="confirmPhotoBtn">Save Profile Picture</button>
                                        </div>

                                        <!-- Confirmation Modal for Profile Picture -->
                                        <div class="modal fade" id="confirmPhotoModal" tabindex="-1" aria-labelledby="confirmPhotoModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="confirmPhotoModalLabel">Confirm Profile Picture</h5>
                                                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to save this profile picture?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="btn-group" role="group" aria-label="Photo Modal Actions">
                                                            <div class="btn-group" role="group" aria-label="Photo Modal Actions">
                                                                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="button" class="btn btn-success btn-sm" id="modalPhotoSaveBtn">Yes, Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                var confirmPhotoBtn = document.getElementById('confirmPhotoBtn');
                                                var modalPhotoSaveBtn = document.getElementById('modalPhotoSaveBtn');
                                                var confirmPhotoModal = new bootstrap.Modal(document.getElementById('confirmPhotoModal'));
                                                var photoForm = confirmPhotoBtn.closest('form');

                                                confirmPhotoBtn.addEventListener('click', function(e) {
                                                    confirmPhotoModal.show();
                                                });

                                                modalPhotoSaveBtn.addEventListener('click', function() {
                                                    photoForm.submit();
                                                });
                                            });
                                        </script>
                                    </div>
                                </form>
                                <script>
                                    function previewPhoto(input) {
                                        if (input.files && input.files[0]) {
                                            var reader = new FileReader();
                                            reader.onload = function(e) {
                                                document.getElementById('profilePhotoPreview').src = e.target.result;
                                            }
                                            reader.readAsDataURL(input.files[0]);
                                        }
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <!-- Account details card-->
                        <div class="card mb-4">
                            <div class="card-header">Client Information</div>
                            <div class="card-body">
                                <form action="{{ route('superadmin_editclients_submit', $client->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <!-- Form Row-->
                                    <div class="row gx-3 mb-3">
                                        <div class="col-md-12">
                                            <div class="form-floating mb-1">
                                                <input class="form-control" id="clientname" name="clientname" type="text" value="{{ $client->clientname }}" placeholder="Client Name" />
                                                <label for="clientname">Client Name</label>
                                            </div>

                                            <div class="form-floating mb-1">
                                                <input class="form-control" id="clientshortname" name="clientshortname" type="text" value="{{ $client->clientshortname }}" placeholder="Short Name" />
                                                <label for="clientshortname">Short Name</label>
                                            </div>

                                            <div class="form-floating mb-1">
                                                <select class="form-control" id="clienttype" name="clienttype" aria-label="Client Type">
                                                    <option value="Government" {{ $client->clienttype == 'Government' ? 'selected' : '' }}>Government</option>
                                                    <option value="Private" {{ $client->clienttype == 'Private' ? 'selected' : '' }}>Private</option>
                                                </select>
                                                <label for="clienttype">Client Type</label>
                                            </div>

                                            <div class="form-floating mb-1">
                                                <input class="form-control" id="clientgeolocation" name="clientgeolocation" type="text" value="{{ $client->clientgeolocation }}" placeholder="Geolocation" />
                                                <label for="clientgeolocation">Geolocation</label>
                                            </div>

                                            <div class="form-floating mb-1">
                                                <input class="form-control" id="clientstreetview" name="clientstreetview" type="text" value="{{ $client->clientstreetview }}" placeholder="Streetview" />
                                                <label for="clientstreetview">Streetview</label>
                                            </div>

                                            <label class="small mb-1 d-block">Client Status</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="status" id="status_active" value="1" {{ $client->isactive == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status_active">
                                                    <span class="badge bg-primary">Active</span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{ $client->isactive == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status_inactive">
                                                    <span class="badge bg-danger">Inactive</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#confirmSaveModal">Save Changes</button>
                                    </div>

                                    <!-- Confirmation Modal -->
                                    <div class="modal fade" id="confirmSaveModal" tabindex="-1" aria-labelledby="confirmSaveModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmSaveModalLabel">Confirm Update</h5>
                                                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to save these changes?
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="btn-group" role="group" aria-label="Save Modal Actions">
                                                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-primary btn-sm" id="modalSaveBtn">Yes, Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        document.getElementById('modalSaveBtn').addEventListener('click', function() {
                                            this.closest('.modal').classList.remove('show');
                                            document.querySelector('.modal-backdrop').remove();
                                            this.closest('form').submit();
                                        });
                                    </script>


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