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
                                        <a href="{{ route('superadmin_clients') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        <div class="page-header-icon"><i data-feather="users" style="width:25px; height:25px;"></i></div>
                                        Edit Client
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <div class="container-fluid px-4 mt-2">
                    <div class="row">
                        <div class="col-xl-4">
                            <!-- Client profile picture card-->
                            <div class="card mb-2 mb-xl-0">
                                <div class="card-header text-body">Client Profile Picture</div>
                                <div class="card-body text-center">
                                    <form id="profilePhotoForm" action="{{ route('superadmin_editclient_uploadprofilepicture', $client->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <!-- Profile picture image with cropping/zoom -->
                                        <div class="position-relative d-flex flex-column align-items-center mb-2">
                                            <img id="profilePhotoPreview" class="img-account-profile rounded-circle mb-2" src="{{ $client->clientphoto ? asset('assets/clients/' . $client->clientphoto) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Profile Photo" style="object-fit: cover; width: 220px; height: 220px;" />
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
                                                profilePhotoPreview.src = "{{ asset('assets/assets/img/demo/user-placeholder.svg') }}";
                                                photoInput.value = '';
                                                removePhotoBtn.style.display = 'none';
                                            });

                                            // Show remove button if preview is not default
                                            if (profilePhotoPreview.src !== "{{ asset('assets/assets/img/demo/user-placeholder.svg') }}") {
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
                            <div class="card mb-4">
                                <div class="card-header text-body">Client Information</div>
                                <div class="card-body">
                                    <form action="{{ route('superadmin_editclient_submit', $client->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-2">
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
                                                    <input class="form-control" id="clientcontact" name="clientcontact" type="text" value="{{ $client->clientcontact }}" placeholder="Contact" />
                                                    <label for="clientcontact">Contact</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <input class="form-control" id="clientcontactperson" name="clientcontactperson" type="text" value="{{ $client->clientcontactperson }}" placeholder="Contact Person" />
                                                    <label for="clientcontactperson">Contact Person</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <input class="form-control" id="clientemail" name="clientemail" type="email" value="{{ $client->clientemail }}" placeholder="Email" />
                                                    <label for="clientemail">Email</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <input class="form-control" id="clientaddress" name="clientaddress" type="text" value="{{ $client->clientaddress }}" placeholder="Address" />
                                                    <label for="clientaddress">Address</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <select class="form-control" name="clientregion" id="clientregion" required>
                                                        <option value="" disabled {{ !$selectedRegion ? 'selected' : '' }}>Select Region</option>
                                                        @foreach($regions as $region)
                                                        <option value="{{ $region }}" {{ $selectedRegion == $region ? 'selected' : '' }}>{{ $region }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="clientregion">Region</label>
                                                </div>
                                                <div class="form-floating mb-1">
                                                    <select class="form-control" name="clientprovince" id="clientprovince" required>
                                                        <option value="" disabled {{ !$selectedProvince ? 'selected' : '' }}>Select Province</option>
                                                        @if($selectedRegion && isset($provincesByRegion[$selectedRegion]))
                                                        @foreach($provincesByRegion[$selectedRegion] as $province)
                                                        <option value="{{ $province }}" {{ $selectedProvince == $province ? 'selected' : '' }}>{{ $province }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                    <label for="clientprovince">Province</label>
                                                </div>
                                                <div class="form-floating mb-1">
                                                    <select class="form-control" name="clientcity" id="clientcity" required>
                                                        <option value="" disabled {{ !$selectedCity ? 'selected' : '' }}>Select City</option>
                                                        @if($selectedProvince && isset($citiesByProvince[$selectedProvince]))
                                                        @foreach($citiesByProvince[$selectedProvince] as $city)
                                                        <option value="{{ $city }}" {{ $selectedCity == $city ? 'selected' : '' }}>{{ $city }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                    <label for="clientcity">City</label>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-floating mb-1">
                                                            <input class="form-control" id="clientcontractstart" name="clientcontractstart" type="date" value="{{ $client->clientcontractstart }}" placeholder="Contract Start" />
                                                            <label for="clientcontractstart">Contract Start</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-floating mb-1">
                                                            <input class="form-control" id="clientcontractend" name="clientcontractend" type="date" value="{{ $client->clientcontractend }}" placeholder="Contract End" />
                                                            <label for="clientcontractend">Contract End</label>
                                                        </div>
                                                    </div>
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
                                                    <label class="form-check-label text-body" for="status_active">
                                                        <span class="d-inline-flex align-items-center">
                                                            <i data-feather="check-circle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                            Active
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{ $client->isactive == 0 ? 'checked' : '' }}>
                                                    <label class="form-check-label text-body" for="status_inactive">
                                                        <span class="d-inline-flex align-items-center">
                                                            <i data-feather="x-circle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                            Inactive
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-2 mb-0">
                                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#confirmSaveModal">
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
                                                            <i data-feather="x-circle" class="me-1"></i>
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

            @include('superadmin.partials.footer')
        </div>
    </div>

    {{-- Place region/province/city dropdown script at the bottom for best practice --}}
    <script>
        const provincesByRegion = {
            !!json_encode($provincesByRegion) !!
        };
        const citiesByProvince = {
            !!json_encode($citiesByProvince) !!
        };
        const selectedProvince = @json($selectedProvince);
        const selectedCity = @json($selectedCity);
        document.addEventListener('DOMContentLoaded', function() {
            const regionSelect = document.getElementById('clientregion');
            const provinceSelect = document.getElementById('clientprovince');
            const citySelect = document.getElementById('clientcity');

            function populateProvinces(region, selectedProvinceVal = '') {
                provinceSelect.innerHTML = '<option value="" disabled>Select Province</option>';
                if (provincesByRegion[region]) {
                    provincesByRegion[region].forEach(function(province) {
                        const selected = province === selectedProvinceVal ? 'selected' : '';
                        provinceSelect.innerHTML += `<option value="${province}" ${selected}>${province}</option>`;
                    });
                }
                provinceSelect.dispatchEvent(new Event('change'));
            }

            function populateCities(province, selectedCityVal = '') {
                citySelect.innerHTML = '<option value="" disabled>Select City</option>';
                if (citiesByProvince[province]) {
                    citiesByProvince[province].forEach(function(city) {
                        const selected = city === selectedCityVal ? 'selected' : '';
                        citySelect.innerHTML += `<option value="${city}" ${selected}>${city}</option>`;
                    });
                }
            }
            regionSelect.addEventListener('change', function() {
                populateProvinces(this.value);
            });
            provinceSelect.addEventListener('change', function() {
                populateCities(this.value);
            });
            if (regionSelect.value) {
                populateProvinces(regionSelect.value, selectedProvince);
            }
            if (provinceSelect.value) {
                populateCities(provinceSelect.value, selectedCity);
            }
        });
    </script>
</body>

</html>