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
                            <!-- Profile picture image-->
                            <img id="profilePhotoPreview" class="img-account-profile rounded-circle mb-2" src="{{ asset('assets/users/superadmin/superadmin_1_1754891349.jpg') }}" alt="Profile Photo" style="width: 250px; height: 250px; object-fit: cover;" />
                            <!-- Profile picture help block-->
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <!-- Account details card-->
                    <div class="card mb-2">
                        <div class="card-header text-body">Profile Information</div>
                        <div class="card-body">
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
</div>
<!-- Ensure all modals have unique IDs and are Bootstrap 5.3 compatible. No inline JS. -->