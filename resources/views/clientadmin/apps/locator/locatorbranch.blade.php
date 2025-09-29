<!DOCTYPE html>
<html lang="en">
@include('clientadmin.partials.client_header')

<body class="nav-fixed">
    @include('clientadmin.partials.client_topnav')

    <div id="layoutSidenav">
        @include('clientadmin.partials.client_sidenav')
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header bg-gray-200 pb-10">
                    <div class="container-fluid px-4">
                        <div class="page-header-content pt-4">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mt-2">
                                    <h2 class="page-header-title text-body d-flex align-items-center" style="font-size:1.5rem;">
                                        <span class="page-header-icon me-2 text-body d-flex justify-content-center align-items-center" style="width:28px; height:28px;">
                                            <i data-feather="map-pin" style="width:30px; height:30px;"></i>
                                        </span>
                                        Branch Locator
                                    </h2>
                                    <div class="page-header-subtitle text-body mt-2">A Geographic Information System Application</div>
                                </div>
                                <div class="col-auto mt-4">
                                    <a href="{{ route('clientadmin_locator') }}" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:50px; height:50px; padding:0;">
                                        <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <div class="container-fluid px-4 mt-n10">
                    <div class="row">
                        <!-- CARD 1 -->
                        <div class="col-6 col-md-6 mb-2 d-flex">
                            <a class="card lift h-100 w-100" href="javascript:void(0);" id="viewAllBranchesBtn" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-secondary mb-1" data-feather="list" style="width: 64px; height: 64px;"></i>
                                            <h3 class="fw-bold text-body">View All Branches</h3>
                                            <div class="text-muted small mt-1">Locate all Branches</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-6 mb-2">
                            <a class="card lift h-100" href="javascript:void(0);" id="viewBranchBtn" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-success mb-1" data-feather="search" style="width: 64px; height: 64px;"></i>
                                            <h3 class="fw-bold text-body">Search Branch</h3>
                                            <div class="text-muted small mt-1">Locate a Branch</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ALL BRANCHES MAP -->
                <div class="container-fluid px-4 mb-2" id="mapContainer" style="display:none;">
                    <div class="card">
                        <div class="card-header text-body">ALL BRANCHES</div>
                        <div class="card-body">
                            <div id="mapAllBranches" style="width: 100%; height: 650px;"></div>
                        </div>
                    </div>
                </div>
                <!-- Table containers, hidden by default -->
                <div class="container-fluid px-4" id="allBranchesTable" style="display:none;">
                    <div class="card">
                        <div class="card-header text-body">SEARCH BRANCH</div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>PHOTO</th>
                                        <th>CLIENT</th>
                                        <th>BRANCH</th>
                                        <th>REGION</th>
                                        <th>TYPE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>PHOTO</th>
                                        <th>CLIENT</th>
                                        <th>BRANCH</th>
                                        <th>REGION</th>
                                        <th>TYPE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($branches->where('isactive', 1)->sortBy('clientname') as $branch)
                                    <tr>
                                        <td>
                                            <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 50px; height: 50px; margin: auto;">
                                                <img src="{{ $branch->clientphoto ? asset('assets/clients/' . $client->clientphoto) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Photo" width="50" height="50" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                                <span style="position: absolute; bottom: 2px; right: 2px; width: 12px; height: 12px; background: #28a745; border: 2px solid #fff; border-radius: 50%; display: block;"></span>
                                            </div>
                                        </td>
                                        <td>{{ $branch->clientname }}</td>
                                        <td>{{ $branch->branchname }}</td>
                                        <td>{{ $branch->branchregion }}</td>
                                        <td>
                                            @if($branch->clienttype === 'Government')
                                            <span class="text-danger">Government</span>
                                            @else
                                            <span class="text-primary">Private</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <button class="btn  btn-outline-primary viewBranchBtn" type="button"
                                                    data-branchname="{{ $branch->branchname }}"
                                                    data-clientname="{{ $branch->clientname }}"
                                                    data-geolocation="{{ $branch->branchgeolocation }}"
                                                    data-streetview="{{ $branch->branchstreetview }}"
                                                    @if(empty($branch->branchgeolocation)) disabled @endif>
                                                    <i data-feather="map-pin"></i> VIEW
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- SINGLE BRANCH MAP -->
                <div class="container-fluid px-4 mb-2" id="singleBranchMapContainer" style="display:none;">
                    <div class="card">
                        <div class="card-header text-body">
                            <div class="d-flex justify-content-center align-items-center" style="height: 40px;">
                                <a id="singleBranchMapHeader" class="btn btn-outline-primary" style="font-size: 1.5rem; padding: 0.5rem 1rem;" onclick="if(this.textContent){window.open('{{ route('clientadmin_locatordata', ['branch' => 'BRANCH_PLACEHOLDER']) }}'.replace('BRANCH_PLACEHOLDER', encodeURIComponent(this.textContent)), '_blank', 'toolbar=no,scrollbars=yes,resizable=no,location=no');} return false;"></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="singleBranchMap" style="width: 100%; height: 650px;"></div>
                        </div>
                    </div>
                </div>
                <!-- END SINGLE BRANCH MAP -->

                <!-- Centralized Scripts -->
                @if(!empty($apiKey) && !empty($apiKey->api_key))
                <script src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey->api_key }}&callback=initBranchMaps" async defer></script>
                @else
                <div class="alert alert-danger mt-3">
                    Google Maps API key is missing or invalid. Please contact the administrator.
                </div>
                @endif
                <script>
                    var allBranches = {!! json_encode($branches->where('isactive', 1)->values()) !!};
                    var allUsers = {!! json_encode($users ?? []) !!};

                    function renderAllBranchesMap() {
                        var map = new google.maps.Map(document.getElementById('mapAllBranches'), {
                            zoom: 6,
                            center: {
                                lat: 12.8797,
                                lng: 121.7740
                            },
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        });
                        var infowindow = new google.maps.InfoWindow();
                        var markerImage = {
                            url: "{{ asset('assets/img/mapmarker.svg') }}",
                            scaledSize: new google.maps.Size(15, 15),
                            anchor: new google.maps.Point(10, 20)
                        };
                        allBranches.forEach(function(branch) {
                            if (!branch.branchgeolocation) return;
                            var coords = branch.branchgeolocation.split(',');
                            var lat = parseFloat(coords[0]);
                            var lng = parseFloat(coords[1]);
                            var marker = new google.maps.Marker({
                                position: {
                                    lat: lat,
                                    lng: lng
                                },
                                map: map,
                                icon: markerImage,
                                url: branch.branchstreetview
                            });
                            marker.addListener('mouseover', function() {
                                infowindow.setContent(branch.branchname);
                                infowindow.open(map, marker);
                            });
                            marker.addListener('click', function() {
                                infowindow.setContent(branch.branchname);
                                infowindow.open(map, marker);
                                if (branch.branchstreetview) window.open(branch.branchstreetview, '_blank', "toolbar=yes,scrollbars=yes,resizable=yes,width=700,height=500");
                            });
                        });
                    }

                    function renderSingleBranchMap(geolocation, branchName, streetView) {
                        var coords = geolocation.split(',');
                        var lat = parseFloat(coords[0]);
                        var lng = parseFloat(coords[1]);
                        var map = new google.maps.Map(document.getElementById('singleBranchMap'), {
                            zoom: 15,
                            center: {
                                lat: lat,
                                lng: lng
                            },
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        });
                        var infowindow = new google.maps.InfoWindow();
                        var markerImage = {
                            url: "{{ asset('assets/img/mapmarker.svg') }}",
                            scaledSize: new google.maps.Size(40, 40),
                            anchor: new google.maps.Point(15, 30)
                        };
                        var marker = new google.maps.Marker({
                            position: {
                                lat: lat,
                                lng: lng
                            },
                            map: map,
                            icon: markerImage,
                            url: streetView
                        });
                        marker.addListener('mouseover', function() {
                            infowindow.setContent(branchName);
                            infowindow.open(map, marker);
                        });
                        marker.addListener('click', function() {
                            infowindow.setContent(branchName);
                            infowindow.open(map, marker);
                            if (streetView) window.open(streetView, '_blank', "toolbar=yes,scrollbars=yes,resizable=yes,width=700,height=500");
                        });
                    }

                    document.addEventListener('DOMContentLoaded', function() {
                        const allBranchesTable = new simpleDatatables.DataTable(document.getElementById('datatablesSimple'));

                        function hideAllMapViews() {
                            document.getElementById('allBranchesTable').style.display = 'none';
                            document.getElementById('mapContainer').style.display = 'none';
                            document.getElementById('singleBranchMapContainer').style.display = 'none';
                        }

                        function attachViewBranchBtnListeners() {
                            document.querySelectorAll('.viewBranchBtn').forEach(function(btn) {
                                btn.addEventListener('click', function() {
                                    var branchName = this.getAttribute('data-branchname');
                                    var clientName = this.getAttribute('data-clientname');
                                    var geolocation = this.getAttribute('data-geolocation');
                                    var streetView = this.getAttribute('data-streetview');
                                    hideAllMapViews();
                                    document.getElementById('singleBranchMapContainer').style.display = 'block';
                                    document.getElementById('singleBranchMapHeader').innerHTML =
                                        '<i data-feather="eye" style="width:1.5rem;height:1.5rem;vertical-align:middle;margin-right:0.5rem;"></i>' +
                                        branchName;
                                    feather.replace();
                                    document.getElementById('singleBranchMap').innerHTML = '';
                                    setTimeout(function() {
                                        renderSingleBranchMap(geolocation, branchName, streetView);
                                    }, 100);
                                });
                            });
                        }
                        // Initial attach
                        attachViewBranchBtnListeners();
                        // Re-attach listeners after table redraws
                        allBranchesTable.on('datatable.page', function() {
                            feather.replace();
                            attachViewBranchBtnListeners();
                        });
                        allBranchesTable.on('datatable.search', function() {
                            feather.replace();
                            attachViewBranchBtnListeners();
                        });
                        allBranchesTable.on('datatable.sort', function() {
                            feather.replace();
                            attachViewBranchBtnListeners();
                        });
                        // Main nav buttons
                        document.getElementById('viewAllBranchesBtn').addEventListener('click', function() {
                            hideAllMapViews();
                            document.getElementById('mapContainer').style.display = 'block';
                            document.getElementById('mapAllBranches').innerHTML = '';
                            setTimeout(renderAllBranchesMap, 100);
                        });
                        document.getElementById('viewBranchBtn').addEventListener('click', function() {
                            hideAllMapViews();
                            document.getElementById('allBranchesTable').style.display = 'block';
                        });
                    });
                    // Google Maps API callback
                    function initBranchMaps() {
                        renderAllBranchesMap();
                    }
                </script>
            </main>
            @include('clientadmin.partials.client_footer')
        </div>
    </div>
</body>

</html>