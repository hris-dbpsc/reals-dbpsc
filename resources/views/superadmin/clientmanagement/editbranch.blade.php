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
                                        <a href="{{ route('superadmin_branches') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        <div class="page-header-icon"><i data-feather="users" style="width:25px; height:25px;"></i></div>
                                        Edit Branch
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
                            <!-- Profile picture card-->
                            <div class="card mb-4 mb-xl-0">
                                <div class="card-header text-body">Branch Photo</div>
                                <div class="card-body text-center">
                                    <!-- Profile picture image-->
                                    @if($branch->clientphoto)
                                    <img class="img-account-profile mb-2" src="{{ $branch->clientphoto ? asset('assets/clients/' . $branch->clientphoto) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Client Profile Photo" />
                                    @else
                                    <img class="img-account-profile rounded-circle mb-2" src="{{ asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Photo" style="object-fit:cover; display:block; margin:auto;" width="150" height="150" />
                                    @endif
                                    <!-- Profile picture help block-->
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <!-- Account details card-->
                            <div class="card mb-2">
                                <div class="card-header text-body">Branch Information</div>
                                <div class="card-body">
                                    <form action="{{ route('superadmin_editbranch_submit', $branch->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-12">
                                                <div class="form-floating mb-1">
                                                    <input class="form-control" id="clientname" name="clientname" type="text" value="{{ $branch->clientname }}" required placeholder="Client Name">
                                                    <label for="clientname">Client Name</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <input class="form-control" id="branchname" name="branchname" type="text" value="{{ $branch->branchname }}" required placeholder="Branch">
                                                    <label for="branchname">Branch</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <input class="form-control" id="branchcontact" name="branchcontact" type="text" value="{{ ltrim($branch->branchcontact, '+63') }}" required pattern="^9\d{9}$" title="Enter a valid Philippine cellphone number (e.g. 9171234567)" placeholder="Contact Number">
                                                    <label for="branchcontact">Contact Number</label>
                                                </div>
                                                <div class="form-floating mb-1">
                                                    <input class="form-control" id="branchcontactperson" name="branchcontactperson" type="text" value="{{ $branch->branchcontactperson }}" required placeholder="Contact Person">
                                                    <label for="branchcontactperson">Contact Person</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <input class="form-control" id="branchaddress" name="branchaddress" type="text" value="{{ $branch->branchaddress }}" required placeholder="Address">
                                                    <label for="branchaddress">Address</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <select class="form-control" name="branchregion" id="branchregion" required>
                                                        <option value="" disabled {{ !$selectedRegion ? 'selected' : '' }}>Select Region</option>
                                                        @foreach($regions as $region)
                                                        <option value="{{ $region }}" {{ $selectedRegion == $region ? 'selected' : '' }}>{{ $region }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="branchregion">Region</label>
                                                </div>
                                                <div class="form-floating mb-1">
                                                    <select class="form-control" name="branchprovince" id="branchprovince" required>
                                                        <option value="" disabled {{ !$selectedProvince ? 'selected' : '' }}>Select Province</option>
                                                        @if($selectedRegion && isset($provincesByRegion[$selectedRegion]))
                                                            @foreach($provincesByRegion[$selectedRegion] as $province)
                                                                <option value="{{ $province }}" {{ $selectedProvince == $province ? 'selected' : '' }}>{{ $province }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <label for="branchprovince">Province</label>
                                                </div>
                                                <div class="form-floating mb-1">
                                                    <select class="form-control" name="branchcity" id="branchcity" required>
                                                        <option value="" disabled {{ !$selectedCity ? 'selected' : '' }}>Select City</option>
                                                        @if($selectedProvince && isset($citiesByProvince[$selectedProvince]))
                                                            @foreach($citiesByProvince[$selectedProvince] as $city)
                                                                <option value="{{ $city }}" {{ $selectedCity == $city ? 'selected' : '' }}>{{ $city }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <label for="branchcity">City</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <input class="form-control" name="branchgeolocation" id="branchgeolocation" type="text" value="{{ $branch->branchgeolocation }}" required placeholder="Branch Geolocation">
                                                    <label for="branchgeolocation">Branch Geolocation</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <input class="form-control" name="branchstreetview" id="branchstreetview" type="text" value="{{ $branch->branchstreetview }}" required placeholder="Branch Streetview">
                                                    <label for="branchstreetview">Branch Streetview</label>
                                                </div>

                                                <label class="small mb-1 d-block">Client Status</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status" id="status_active" value="1" {{ $branch->isactive == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label text-body" for="status_active">
                                                        <span class="d-inline-flex align-items-center">
                                                            <i data-feather="check-circle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                            Active
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{ $branch->isactive == 0 ? 'checked' : '' }}>
                                                    <label class="form-check-label text-body" for="status_inactive">
                                                        <span class="d-inline-flex align-items-center">
                                                            <i data-feather="x-circle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                            Inactive
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Submit button-->
                                        <div class="d-flex align-items-center justify-content-between mt-2 mb-0">
                                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#confirmSaveModal">
                                                <i data-feather="bookmark" class="me-1"></i>
                                                Save Changes
                                            </button>
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
                                                document.getElementById('modalSaveBtn').addEventListener('click', function() {
                                                    this.closest('.modal').classList.remove('show');
                                                    document.querySelector('.modal-backdrop').remove();
                                                    this.closest('form').submit();
                                                });
                                            </script>
                                        </div>
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

    <!-- Place this at the bottom of the file, just before </body> -->
    <script>
    // Pass provinces and cities data from PHP to JS
    var provincesByRegion = {!! json_encode($provincesByRegion) !!};
    var citiesByProvince = {!! json_encode($citiesByProvince) !!};

    function populateProvinces(region, selectedProvince = '') {
        const provinceSelect = document.getElementById('branchprovince');
        provinceSelect.innerHTML = '<option value="" disabled>Select Province</option>';
        if (provincesByRegion[region]) {
            provincesByRegion[region].forEach(function(province) {
                const selected = province === selectedProvince ? 'selected' : '';
                provinceSelect.innerHTML += `<option value="${province}" ${selected}>${province}</option>`;
            });
        }
        // Trigger city population
        populateCities(provinceSelect.value, '');
    }

    function populateCities(province, selectedCity = '') {
        const citySelect = document.getElementById('branchcity');
        citySelect.innerHTML = '<option value="" disabled>Select City</option>';
        if (citiesByProvince[province]) {
            citiesByProvince[province].forEach(function(city) {
                const selected = city === selectedCity ? 'selected' : '';
                citySelect.innerHTML += `<option value="${city}" ${selected}>${city}</option>`;
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const regionSelect = document.getElementById('branchregion');
        const provinceSelect = document.getElementById('branchprovince');
        const citySelect = document.getElementById('branchcity');
        // Initial population
        if (regionSelect.value) {
            populateProvinces(regionSelect.value, "{{ $selectedProvince }}");
        }
        if (provinceSelect.value) {
            populateCities(provinceSelect.value, "{{ $selectedCity }}");
        }
        regionSelect.addEventListener('change', function() {
            populateProvinces(this.value, '');
        });
        provinceSelect.addEventListener('change', function() {
            populateCities(this.value, '');
        });
    });
    </script>
</body>

</html>