<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile-First Dashboard</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- Feather Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous"></script>
    <style>
        /* Sidebar styles */
        @media (min-width: 992px) {
            .sidebar {
                min-width: 220px;
                max-width: 260px;
                height: 100vh;
                position: fixed;
                left: 0;
                top: 0;
                background: var(--bs-body-bg);
                border-right: 1px solid var(--bs-border-color-translucent);
                display: flex;
                flex-direction: column;
                z-index: 1030;
                transition: all 0.3s;
            }

            .main-content {
                margin-left: 220px;
            }
        }

        @media (max-width: 991.98px) {
            .sidebar {
                display: none !important;
            }

            .bottom-nav {
                display: flex !important;
            }

            .main-content {
                margin-left: 0;
                padding-bottom: 70px;
            }
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.5rem 0.5rem 0.5rem 0.5rem;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1.1rem;
            color: var(--bs-body-color);
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
            transition: background 0.2s, color 0.2s;
            width: 100%;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .sidebar .nav-link.active,
        .bottom-nav .nav-link.active {
            background: #0d6efd;
            /* Bootstrap primary color */
            color: #fff !important;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.08);
        }

        .sidebar .nav-link.active span,
        .sidebar .nav-link.active i,
        .bottom-nav .nav-link.active span,
        .bottom-nav .nav-link.active i {
            color: #fff !important;
        }

        .sidebar .user-info {
            border-top: 1px solid var(--bs-border-color-translucent);
            padding-top: 1rem;
            margin-top: 1rem;
        }

        /* Bottom nav styles */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--bs-body-bg);
            border-top: 1px solid var(--bs-border-color-translucent);
            z-index: 1030;
            justify-content: space-around;
            padding: 0.5rem 0;
        }

        .bottom-nav .nav-link {
            color: var(--bs-body-color);
            font-size: 1.1rem;
            flex-direction: column;
            gap: 0.2rem;
            border-radius: 0.375rem;
            transition: background 0.2s, color 0.2s;
            width: 100%;
            padding-left: 0;
            padding-right: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .bottom-nav .nav-link.active {
            color: #fff !important;
            background: #0d6efd;
            box-shadow: 0 -2px 8px rgba(13, 110, 253, 0.08);
        }

        .bottom-nav .nav-link.active span,
        .bottom-nav .nav-link.active i {
            color: #fff !important;
        }

        .bottom-nav .dropdown-menu {
            min-width: 8rem;
        }

        .darkmode-switch {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        a.card {
            text-decoration: none !important;
        }

        a.card h3,
        a.card .text-body {
            text-decoration: none !important;
        }
    </style>
</head>

<body>
    <!-- Sidebar (Desktop) -->
    <nav class="sidebar d-none d-lg-flex">
        <div>
            <!-- Navigation -->
            <a href="#" class="nav-link active py-3" id="nav-home"><i data-feather="home" class="me-2"></i> <span>Home</span></a>
            <a href="#" class="nav-link py-3" id="nav-apps"><i data-feather="grid" class="me-2"></i> <span>Apps</span></a>
            <a href="#" class="nav-link py-3" id="nav-profile"><i data-feather="user" class="me-2"></i> <span>Profile</span></a>
        </div>
        <div>
            <div class="darkmode-switch">
                <div class="form-check form-switch w-100 d-flex justify-content-center">
                    <input class="form-check-input" type="checkbox" id="darkModeSwitchDesktop">
                    <label class="form-check-label ms-2" for="darkModeSwitchDesktop"><i data-feather="moon"></i></label>
                </div>
            </div>
            <div class="user-info mt-3">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Avatar" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                    <div>
                        <div class="fw-semibold">John Doe</div>
                        <div class="text-muted small">johndoe@example.com</div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-2" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i data-feather="log-out" class="me-1"></i> Logout
                </button>
            </div>
        </div>
    </nav>


    <!-- Bottom Navigation (Mobile) -->
    <nav class="bottom-nav d-lg-none d-flex">
        <a href="#" class="nav-link active flex-fill text-center fs-6 py-2" id="bottom-home"><i data-feather="home"></i><span class="small">Home</span></a>
        <a href="#" class="nav-link flex-fill text-center fs-6 py-2" id="bottom-apps"><i data-feather="grid"></i><span class="small">Apps</span></a>
        <a href="#" class="nav-link flex-fill text-center fs-6 py-2" id="bottom-profile"><i data-feather="user"></i><span class="small">Profile</span></a>
    </nav>


    <!-- Main Content -->
    <main class="main-content px-4 mt-2 py-4 mb-5">
        <div id="page-home">
            <h2 class="mb-2">Dashboard</h2>
            <div class="card mb-3">
                <div class="card-body">Welcome to the Home page!</div>
            </div>
        </div>

        <div id="page-apps" style="display:none;">
            <h2 class="mb-2">Apps</h2>
            <div class="card mb-3 px-4">
                <div class="card-body opacity-25"></div>
                <div class="row">
                    <div class="col-xl-3 mb-4">
                        <a class="card lift h-100 shadow-lg border-0 position-relative" href="#!">
                            <div class="card-body d-flex justify-content-center flex-column text-center">
                                <div class="d-flex align-items-center justify-content-center flex-column">
                                    <div class="me-3">
                                        <i class="feather mb-1 text-warning" data-feather="calendar" style="width: 64px; height: 64px;"></i>
                                        <h3 class="text-body">TimeOff</h3>
                                        <div class="text-muted small mt-1">Leave Management System</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 mb-4">
                        <a class="card lift h-100 shadow-lg border-0 position-relative" href="#!">
                            <div class="card-body d-flex justify-content-center flex-column text-center">
                                <div class="d-flex align-items-center justify-content-center flex-column">
                                    <div class="me-3">
                                        <i class="feather mb-1 text-success" data-feather="clock" style="width: 64px; height: 64px;"></i>
                                        <h3 class="text-body">TimeLog</h3>
                                        <div class="text-muted small mt-1">Attendance Management System</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 mb-4">
                        <a class="card lift h-100 shadow-lg border-0 position-relative" href="#!">
                            <div class="card-body d-flex justify-content-center flex-column text-center">
                                <div class="d-flex align-items-center justify-content-center flex-column">
                                    <div class="me-3">
                                        <i class="feather mb-1 text-primary" data-feather="message-square" style="width: 64px; height: 64px;"></i>
                                        <h3 class="text-body">WorkChat</h3>
                                        <div class="text-muted small mt-1">Real-time Messaging Platform</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div id="page-profile" style="display:none;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="mb-2">Profile</h2>
                <!-- Dark mode toggle for mobile profile -->
                <div class="form-check form-switch d-lg-none">
                    <input class="form-check-input" type="checkbox" id="darkModeSwitchMobile">
                    <label class="form-check-label" for="darkModeSwitchMobile" title="Toggle dark mode">
                        <i data-feather="moon"></i>
                    </label>
                </div>
            </div>
            <div class="card mb-3 px-4">
                <div class="card-body"></div>
                <div class="row">
                    <div class="col-xl-4">
                        <!-- Profile picture card-->
                        <div class="card mb-2 mb-xl-0">
                            <div class="card-header text-body">Profile Picture</div>
                            <div class="card-body text-center">
                                <form action="" method="POST" enctype="multipart/form-data" id="profilePhotoForm">
                                    @csrf
                                    @method('PUT')
                                    <!-- Profile picture image with cropping/zoom -->
                                    <div class="position-relative d-flex flex-column align-items-center mb-2">
                                        <img id="profilePhotoPreview" class="img-account-profile rounded-circle mb-2" src="" alt="Profile Photo" style="object-fit: cover; width: 220px; height: 220px;" />
                                        <button type="button" class="btn btn-sm btn-close position-absolute top-0 end-0 bg-white" id="removePhotoBtn" title="Remove selected image" style="display:none;"></button>
                                    </div>
                                    <div class="small font-italic text-muted mb-2">JPG or PNG no larger than 5 MB. You can zoom/crop before saving.</div>
                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                        <label class="btn btn-outline-success me-2 mb-0">
                                            <input type="file" name="photo" id="photoInput" accept="image/png, image/jpeg" style="display:none;">
                                            <i data-feather="upload" class="me-1"></i> Upload
                                        </label>
                                        <button type="button" class="btn btn-outline-primary" id="confirmPhotoBtn">
                                            <i data-feather="bookmark" class="me-1"></i> Save
                                        </button>
                                    </div>
                                    <!-- Modal for cropping/zooming -->
                                    <div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="cropperModalLabel">Adjust Profile Photo</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <div style="width: 300px; height: 300px; margin: 0 auto; position: relative;">
                                                        <img id="cropperImage" style="max-width:100%; max-height:300px; display:block;" />
                                                        <!-- Circle overlay -->
                                                        <div id="circleOverlay" style="position:absolute;top:0;left:0;width:300px;height:300px;pointer-events:none;z-index:10;border-radius:50%;box-shadow:0 0 0 9999px rgba(0,0,0,0.5),0 0 0 2px #fff inset;"></div>
                                                    </div>
                                                    <!-- Zoom controls -->
                                                    <div class="d-flex justify-content-center align-items-center mt-3">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm me-2" id="zoomOutBtn"><i data-feather="zoom-out"></i></button>
                                                        <input type="range" min="0.1" max="3" step="0.01" value="1" id="zoomRange" style="width:120px;">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="zoomInBtn"><i data-feather="zoom-in"></i></button>
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                        <i data-feather="x" class="me-1"></i>
                                                        Cancel
                                                    </button>
                                                    <button type="button" class="btn btn-outline-primary" id="cropImageBtn">
                                                        <i data-feather="scissors" class="me-1"></i>
                                                        Crop & Use
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Confirmation Modal for Profile Picture -->
                                    <div class="modal fade" id="confirmPhotoModal" tabindex="-1" aria-labelledby="confirmPhotoModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmPhotoModalLabel">Confirm Profile Picture</h5>
                                                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">Are you sure you want to save this profile picture?</div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                        <i data-feather="upload" class="me-1"></i> Cancel
                                                    </button>
                                                    <button type="button" class="btn btn-outline-primary" id="modalPhotoSaveBtn">
                                                        <i data-feather="bookmark" class="me-1"></i> Yes
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!-- Cropper.js (CDN) -->
                                <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
                                <script>
                                    let cropper, cropperModal, confirmPhotoModal;
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const photoInput = document.getElementById('photoInput');
                                        const profilePhotoPreview = document.getElementById('profilePhotoPreview');
                                        const cropperImage = document.getElementById('cropperImage');
                                        const cropImageBtn = document.getElementById('cropImageBtn');
                                        const removePhotoBtn = document.getElementById('removePhotoBtn');
                                        const confirmPhotoBtn = document.getElementById('confirmPhotoBtn');
                                        const modalPhotoSaveBtn = document.getElementById('modalPhotoSaveBtn');
                                        const photoForm = document.getElementById('profilePhotoForm');
                                        const zoomInBtn = document.getElementById('zoomInBtn');
                                        const zoomOutBtn = document.getElementById('zoomOutBtn');
                                        const zoomRange = document.getElementById('zoomRange');
                                        cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));
                                        confirmPhotoModal = new bootstrap.Modal(document.getElementById('confirmPhotoModal'));

                                        // Show cropper when file selected
                                        photoInput.addEventListener('change', function(e) {
                                            if (e.target.files && e.target.files[0]) {
                                                const reader = new FileReader();
                                                reader.onload = function(ev) {
                                                    cropperImage.src = ev.target.result;
                                                    cropperModal.show();
                                                };
                                                reader.readAsDataURL(e.target.files[0]);
                                            }
                                        });

                                        // Initialize cropper when modal shown
                                        document.getElementById('cropperModal').addEventListener('shown.bs.modal', function() {
                                            cropper = new Cropper(cropperImage, {
                                                aspectRatio: 1,
                                                viewMode: 1,
                                                autoCropArea: 1,
                                                movable: true,
                                                zoomable: true,
                                                rotatable: false,
                                                scalable: false,
                                                cropBoxResizable: true,
                                                dragMode: 'move',
                                                guides: false,
                                                highlight: false,
                                                cropBoxMovable: false,
                                                cropBoxResizable: false,
                                                background: false,
                                                ready() {
                                                    // Make crop box a circle visually
                                                    const cropBox = document.querySelector('.cropper-crop-box');
                                                    if (cropBox) cropBox.style.borderRadius = '50%';
                                                    const viewBox = document.querySelector('.cropper-view-box');
                                                    if (viewBox) viewBox.style.borderRadius = '50%';
                                                    // Set initial zoom range value
                                                    if (cropper) zoomRange.value = cropper.getData().scaleX || 1;
                                                },
                                                zoom(event) {
                                                    // Sync range input with cropper zoom
                                                    zoomRange.value = cropper.getImageData().scaleX;
                                                }
                                            });
                                        });
                                        document.getElementById('cropperModal').addEventListener('hidden.bs.modal', function() {
                                            if (cropper) {
                                                cropper.destroy();
                                                cropper = null;
                                            }
                                        });

                                        // Crop and set preview
                                        cropImageBtn.addEventListener('click', function() {
                                            if (cropper) {
                                                const canvas = cropper.getCroppedCanvas({
                                                    width: 400,
                                                    height: 400
                                                });
                                                profilePhotoPreview.src = canvas.toDataURL('image/png');
                                                cropperModal.hide();
                                                removePhotoBtn.style.display = '';
                                            }
                                        });

                                        // Remove selected image
                                        removePhotoBtn.addEventListener('click', function() {
                                            profilePhotoPreview.src = '{{ asset('
                                            assets / assets / img / demo / user - placeholder.svg ') }}';
                                            photoInput.value = '';
                                            removePhotoBtn.style.display = 'none';
                                        });

                                        // Show remove button if preview is not default
                                        if (profilePhotoPreview.src !== '{{ asset('
                                            assets / assets / img / demo / user - placeholder.svg ') }}') {
                                            removePhotoBtn.style.display = '';
                                        }

                                        // Save button triggers confirmation modal
                                        confirmPhotoBtn.addEventListener('click', function(e) {
                                            confirmPhotoModal.show();
                                        });
                                        // Confirm save
                                        modalPhotoSaveBtn.addEventListener('click', function() {
                                            // If cropped, convert dataURL to file and append to form
                                            if (profilePhotoPreview.src.startsWith('data:image')) {
                                                fetch(profilePhotoPreview.src)
                                                    .then(res => res.blob())
                                                    .then(blob => {
                                                        const file = new File([blob], 'profile.png', {
                                                            type: 'image/png'
                                                        });
                                                        const dt = new DataTransfer();
                                                        dt.items.add(file);
                                                        photoInput.files = dt.files;
                                                        photoForm.submit();
                                                    });
                                            } else {
                                                photoForm.submit();
                                            }
                                        });

                                        // Zoom in/out buttons
                                        zoomInBtn.addEventListener('click', function() {
                                            if (cropper) cropper.zoom(0.1);
                                        });
                                        zoomOutBtn.addEventListener('click', function() {
                                            if (cropper) cropper.zoom(-0.1);
                                        });
                                        // Zoom range slider
                                        zoomRange.addEventListener('input', function() {
                                            if (cropper) {
                                                const currentZoom = cropper.getImageData().scaleX;
                                                const targetZoom = parseFloat(zoomRange.value);
                                                cropper.zoomTo(targetZoom);
                                            }
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <!-- Account details card-->
                        <div class="card mb-2">
                            <div class="card-header text-body">Profile Information</div>
                            <div class="card-body">
                                <form action="" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <!-- Form Row-->
                                    <div class="row gx-3 mb-2">
                                        <div class="col-md-12">
                                            <div class="form-floating mb-2">
                                                <input class="form-control" id="firstname" name="firstname" type="text" placeholder="First Name" value="" />
                                                <label for="firstname">First Name</label>
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
                                        <button class="btn btn-outline-primary" type="button" id="confirmSaveBtn">
                                            <i data-feather="bookmark" class="me-1"></i>
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
                                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                        <i data-feather="x" class="me-1"></i>
                                                        Cancel
                                                    </button>
                                                    <button type="button" class="btn btn-outline-primary" id="modalSaveBtn">
                                                        <i data-feather="bookmark" class="me-1"></i>
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
                                                form.submit();
                                            });
                                        });
                                    </script>
                                </form>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header text-body">Change Password</div>
                            <div class="card-body">
                                <form action="" method="POST">
                                    @csrf
                                    <div class="modal-body">

                                        <div class="form-floating mb-2">
                                            <input type="password" class="form-control" id="oldpassword" name="oldpassword" placeholder="Old Password" required>
                                            <label for="oldpassword">Old Password</label>
                                        </div>
                                        <div class="form-floating mb-2">
                                            <input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="New Password" required>
                                            <label for="newpassword">New Password</label>
                                        </div>
                                        <div class="form-floating mb-2">
                                            <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required>
                                            <label for="confirmpassword">Confirm New Password</label>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-primary" type="button" id="confirmChangePasswordBtn">
                                        <i data-feather="key" class="me-1"></i>
                                        Change Password
                                    </button>

                                    <!-- Confirmation Modal for Change Password -->
                                    <div class="modal fade" id="confirmChangePasswordModal" tabindex="-1" aria-labelledby="confirmChangePasswordModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmChangePasswordModalLabel">Confirm Password Change</h5>
                                                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to change your password?
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                        <i data-feather="x" class="me-1"></i>
                                                        Cancel
                                                    </button>
                                                    <button type="button" class="btn btn-outline-primary" id="modalChangePasswordSaveBtn">
                                                        <i data-feather="key" class="me-1"></i>
                                                        Change Password
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            var confirmChangePasswordBtn = document.getElementById('confirmChangePasswordBtn');
                                            var modalChangePasswordSaveBtn = document.getElementById('modalChangePasswordSaveBtn');
                                            var confirmChangePasswordModal = new bootstrap.Modal(document.getElementById('confirmChangePasswordModal'));
                                            var changePasswordForm = confirmChangePasswordBtn.closest('form');

                                            confirmChangePasswordBtn.addEventListener('click', function(e) {
                                                confirmChangePasswordModal.show();
                                            });

                                            modalChangePasswordSaveBtn.addEventListener('click', function() {
                                                changePasswordForm.submit();
                                            });
                                        });
                                    </script>
                                </form>
                            </div>
                        </div><!-- Mobile Profile Logout Button -->
                        <div class="d-lg-none justify-content-center">
                            <button type="button" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                <i data-feather="log-out" class="me-1"></i> Logout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>


    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Are you sure you want to logout?</div>
                <div class="modal-footer justify-content-center align-items-center">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i data-feather="x-circle" class="me-1"></i> Cancel</button>
                    <button type="button" class="btn btn-primary" id="logoutConfirmBtn">
                        <i data-feather="log-out" class="me-1"></i> Logout</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        feather.replace();
        // Navigation logic
        function showPage(page) {
            document.querySelectorAll('.main-content > div[id^="page-"]').forEach(el => el.style.display = 'none');
            var pageEl = document.getElementById('page-' + page);
            if (pageEl) pageEl.style.display = '';
            document.querySelectorAll('.sidebar .nav-link, .bottom-nav .nav-link').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('#nav-' + page + ', #bottom-' + page).forEach(el => el.classList.add('active'));
        }
        document.getElementById('nav-home').onclick = document.getElementById('bottom-home').onclick = function() {
            showPage('home');
        };
        document.getElementById('nav-apps').onclick = document.getElementById('bottom-apps').onclick = function() {
            showPage('apps');
        };
        document.getElementById('nav-profile').onclick = document.getElementById('bottom-profile').onclick = function() {
            showPage('profile');
        };
        // Profile dropdown in mobile
        document.getElementById('bottom-profile').addEventListener('show.bs.dropdown', function() {
            showPage('profile');
        });
        // Dark mode logic
        function setDarkMode(isDark) {
            document.documentElement.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
            localStorage.setItem('bsTheme', isDark ? 'dark' : 'light');
        }

        function syncDarkModeSwitch() {
            var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            var switchDesktop = document.getElementById('darkModeSwitchDesktop');
            var switchMobile = document.getElementById('darkModeSwitchMobile');
            if (switchDesktop) switchDesktop.checked = isDark;
            if (switchMobile) switchMobile.checked = isDark;
        }
        document.addEventListener('DOMContentLoaded', function() {
            // Set theme from localStorage
            var theme = localStorage.getItem('bsTheme') || 'light';
            setDarkMode(theme === 'dark');
            syncDarkModeSwitch();
            var switchDesktop = document.getElementById('darkModeSwitchDesktop');
            var switchMobile = document.getElementById('darkModeSwitchMobile');
            if (switchDesktop) {
                switchDesktop.addEventListener('change', function() {
                    setDarkMode(switchDesktop.checked);
                    syncDarkModeSwitch();
                });
            }
            if (switchMobile) {
                switchMobile.addEventListener('change', function() {
                    setDarkMode(switchMobile.checked);
                    syncDarkModeSwitch();
                });
            }
            // Logout modal logic
            var logoutConfirmBtn = document.getElementById('logoutConfirmBtn');
            if (logoutConfirmBtn) {
                logoutConfirmBtn.addEventListener('click', function() {
                    // Replace with actual logout logic
                    window.location.href = '/logout';
                });
            }
        });
    </script>
</body>

</html>