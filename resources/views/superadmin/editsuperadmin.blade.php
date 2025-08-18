<!DOCTYPE html>
<html lang="en">
@include('superadmin.header')
@include('superadmin.topnav')
<div id="layoutSidenav">
    @include('superadmin.sidenav')
    <div id="layoutSidenav_content">

        <main>
            <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                <div class="container-xl px-4">
                    <div class="page-header-content">
                        <div class="row align-items-center justify-content-between pt-3">
                            <div class="col-auto mb-3">
                                <h1 class="page-header-title d-flex align-items-center">
                                    <a href="{{ url()->previous() }}" class="btn btn-link p-0 me-2" title="Back">
                                        <i data-feather="arrow-left-circle"></i>
                                    </a>
                                    <div class="page-header-icon"><i data-feather="edit"></i></div>
                                    Edit Profile
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-xl px-4 mt-4">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success')}}
                </div>
                @endif
                <div class="row">
                    <div class="col-xl-4">
                        <!-- Profile picture card-->
                        <div class="card mb-4 mb-xl-0">
                            <div class="card-header">Profile Picture</div>
                            <div class="card-body text-center">
                                <form action="{{ route('superadmin_editsuperadmin_uploadprofilepicture', Auth::guard('superadmin')->user()->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <!-- Profile picture image-->
                                    <img id="profilePhotoPreview" class="img-account-profile rounded-circle mb-2" src="{{ Auth::guard('superadmin')->user()->photo ? asset('assets/users/superadmin/' . Auth::guard('superadmin')->user()->photo) : asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Profile Photo" />
                                    <!-- Profile picture help block-->
                                    <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                                    <!-- Profile picture upload button-->
                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                        <div class="btn-group" role="group" aria-label="Profile Picture Actions">
                                            <label class="btn btn-sm btn-secondary mb-0" style="margin-right: 0;">
                                                <input type="file" name="photo" accept="image/png, image/jpeg" style="display:none;" onchange="previewPhoto(this)">
                                                Upload Photo
                                            </label>
                                            <button type="button" class="btn btn-sm btn-success" id="confirmPhotoBtn" style="margin-left: 0;">Save Profile Picture</button>
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
                                                            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-success btn-sm" id="modalPhotoSaveBtn">Yes, Save</button>
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
                            <div class="card-header">Profile Information</div>
                            <div class="card-body">
                                <form action="{{ route('superadmin_editsuperadmin', Auth::guard('superadmin')->user()->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <!-- Form Row-->
                                    <div class="row gx-3 mb-3">
                                        <div class="col-md-12">

                                            <div class="mb-3 d-flex justify-content-center align-items-center">
                                                <span class="badge bg-primary text-white" style="font-size: 1rem;">
                                                    Superadmin
                                                </span>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="firstname" name="firstname" type="text" placeholder="First Name" value="{{ Auth::guard('superadmin')->user()->firstname }}" />
                                                <label for="firstname">First Name</label>
                                            </div>

                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="middlename" name="middlename" type="text" placeholder="Middle Name" value="{{ Auth::guard('superadmin')->user()->middlename }}" />
                                                <label for="middlename">Middle Name</label>
                                            </div>

                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="lastname" name="lastname" type="text" placeholder="Last Name" value="{{ Auth::guard('superadmin')->user()->lastname }}" />
                                                <label for="lastname">Last Name</label>
                                            </div>

                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="email" name="email" type="text" placeholder="Email" value="{{ Auth::guard('superadmin')->user()->email }}" />
                                                <label for="email">Email</label>
                                            </div>

                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="contact" name="contact" type="text" placeholder="Contact Number" value="{{ Auth::guard('superadmin')->user()->contact }}" />
                                                <label for="contact">Contact Number</label>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                        <button class="btn btn-primary btn-sm" type="button" id="confirmSaveBtn">Save Changes</button>
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
                                                    <div class="btn-group" role="group" aria-label="Modal Actions">
                                                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-primary btn-sm" id="modalSaveBtn">Yes, Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            var confirmBtn = document.getElementById('confirmSaveBtn');
                                            var modalSaveBtn = document.getElementById('modalSaveBtn');
                                            var confirmModal = new bootstrap.Modal(document.getElementById('confirmSaveModal'));
                                            var form = confirmBtn.closest('form');

                                            confirmBtn.addEventListener('click', function(e) {
                                                confirmModal.show();
                                            });

                                            modalSaveBtn.addEventListener('click', function() {
                                                form.submit();
                                            });
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