<!-- Profile Page Section -->
<div id="page-profile" style="display:none;">
    <!-- Profile Header -->
    <div class="d-flex align-items-center mb-3 gap-2">
        <i data-feather="user" style="width:32px;height:32px;"></i>
        <h1 class="mb-0">Profile</h1>
    </div>
    <!-- Profile Card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row g-3 mt-3">
                <!-- Profile Content (inlined from body-profile-content.blade.php) -->
                <div class="col-xl-4">
                    <div class="card mb-2 mb-xl-0">
                        <div class="card-header text-body">Profile Picture</div>
                        <div class="card-body text-center">
                            <form>
                                <!-- Profile picture image-->
                                <img id="profilePhotoPreview" class="img-account-profile rounded-circle mb-2" src="{{ asset('assets/users/superadmin/superadmin_1_1754891349.jpg') }}" alt="Profile Photo" />
                                <!-- Profile picture help block-->
                                <div class="small font-italic text-muted mb-2">JPG or PNG no larger than 5 MB</div>
                                <!-- Profile picture upload button-->
                                <div class="d-flex flex-row align-items-center justify-content-center">
                                    <label class="btn btn-light text-success me-2">
                                        <input type="file" name="photo" accept="image/png, image/jpeg" style="display:none;" onchange="previewPhoto(this)">
                                        <i data-feather="upload" class="me-1"></i>
                                        Upload
                                    </label>
                                    <button type="button" class="btn btn-light text-primary" id="confirmPhotoBtn">
                                        <i data-feather="save" class="me-1"></i>
                                        Save
                                    </button>
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
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-light text-danger" data-bs-dismiss="modal">
                                                        <i data-feather="upload" class="me-1"></i>
                                                        Cancel
                                                    </button>
                                                    <button type="button" class="btn btn-light text-primary" id="modalPhotoSaveBtn">
                                                        <i data-feather="save" class="me-1"></i>
                                                        Yes
                                                    </button>
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
                                                // Simulate form submit
                                                confirmPhotoModal.hide();
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
                    <div class="card mb-2">
                        <div class="card-header text-body">Profile Information</div>
                        <div class="card-body">
                            <form>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-2">
                                    <div class="col-md-12">
                                        <div class="form-floating mb-2">
                                            <input class="form-control" id="firstname" name="firstname" type="text" placeholder="First Name" value="" />
                                            <label for="firstname">Symon</label>
                                        </div>
                                        <div class="form-floating mb-2">
                                            <input class="form-control" id="middlename" name="middlename" type="text" placeholder="Middle Name" value="" />
                                            <label for="middlename">Middle Name</label>
                                        </div>
                                        <div class="form-floating mb-2">
                                            <input class="form-control" id="lastname" name="lastname" type="text" placeholder="Last Name" value="" />
                                            <label for="lastname">Last Name</label>
                                        </div>
                                        <div class="form-floating mb-2">
                                            <input class="form-control" id="email" name="email" type="text" placeholder="Email" value="" />
                                            <label for="email">Email</label>
                                        </div>
                                        <div class="form-floating mb-2">
                                            <input class="form-control" id="contact" name="contact" type="text" placeholder="Contact Number" value="" />
                                            <label for="contact">Contact Number</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-2 mb-0">
                                    <button class="btn btn-light text-primary" type="button" id="confirmSaveBtn">
                                        <i data-feather="save" class="me-1"></i>
                                        Save Changes
                                    </button>
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
                                            <div class="modal-footer justify-content-center">
                                                <button type="button" class="btn btn-light text-danger" data-bs-dismiss="modal">
                                                    <i data-feather="x" class="me-1"></i>
                                                    Cancel
                                                </button>
                                                <button type="button" class="btn btn-light text-primary" id="modalSaveBtn">
                                                    <i data-feather="save" class="me-1"></i>
                                                    Save
                                                </button>
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
                                            // Simulate form submit
                                            confirmModal.hide();
                                        });
                                    });
                                </script>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-block d-md-none mt-4">
        <a class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" href="" role="button">
            <i data-feather="log-out" class="me-2"></i>
            Logout
        </a>
    </div>
    <!-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.feather) {
                feather.replace();
            }
        });
    </script> -->
</div>