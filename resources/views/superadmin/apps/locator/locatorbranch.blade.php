<!DOCTYPE html>
<html lang="en">
@include('superadmin.partials.header')

<body class="nav-fixed">
    @include('superadmin.partials.topnav')

    <div id="layoutSidenav">
        @include('superadmin.partials.sidenav')
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
                                    <a href="{{ route('superadmin_locator') }}" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:50px; height:50px; padding:0;">
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
                        <div class="col-6 col-md-4 mb-2 d-flex">
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
                        <!-- CARD 2 -->
                        <div class="col-6 col-md-4 mb-2 d-flex">
                            <a class="card lift h-100 w-100" href="javascript:void(0);" id="viewClientBranchesBtn" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-primary mb-1" data-feather="users" style="width: 64px; height: 64px;"></i>
                                            <h3 class="fw-bold text-body">View Per Client</h3>
                                            <div class="text-muted small mt-1">Locate Clients Branches</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-4 mb-2">
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

                <div class="container-fluid px-4 mb-2" id="clientBranchesTable" style="display:none;">
                    <div class="card">
                        <div class="card-header text-body">ALL CLIENTS</div>
                        <div class="card-body">
                            <table id="datatablesSimpleClientBranches">
                                <thead>
                                    <tr>
                                        <th>PHOTO</th>
                                        <th>CLIENT</th>
                                        <th>CLIENT</th>
                                        <th>REGION</th>
                                        <th>TYPE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>PHOTO</th>
                                        <th>CLIENT</th>
                                        <th>CLIENT</th>
                                        <th>REGION</th>
                                        <th>TYPE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($clients->where('isactive', 1)->sortBy('clientshortname') as $client)
                                    <tr>
                                        <td>
                                            <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 50px; height: 50px; margin: auto;">
                                                <img src="{{ $client->clientphoto ? asset('assets/clients/' . $client->clientphoto) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Photo" width="50" height="50" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                                <span style="position: absolute; bottom: 2px; right: 2px; width: 12px; height: 12px; background: #28a745; border: 2px solid #fff; border-radius: 50%; display: block;"></span>
                                            </div>
                                        </td>
                                        <td>{{ $client->clientshortname }}</td>
                                        <td>{{ $client->clientname }}</td>
                                        <td>{{ $client->clientregion }}</td>
                                        <td>
                                            @if($client->clienttype === 'Government')
                                            <span class="text-danger">Government</span>
                                            @else
                                            <span class="text-primary">Private</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                            $hasBranches = $branches->where('clientid', $client->id)->where('isactive', 1)->count() > 0;
                                            @endphp
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-outline-primary viewPerClientBtn" type="button"
                                                    data-clientid="{{ $client->id }}" data-clientname="{{ $client->clientshortname }}"
                                                    @if(!$hasBranches) disabled @endif>
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
                                <a id="singleBranchMapHeader" class="btn btn-outline-primary" style="font-size: 1.5rem; padding: 0.5rem 1rem;" onclick="if(this.textContent){window.open('{{ route('superadmin_locatordata', ['branch' => 'BRANCH_PLACEHOLDER']) }}'.replace('BRANCH_PLACEHOLDER', encodeURIComponent(this.textContent)), '_blank', 'toolbar=no,scrollbars=yes,resizable=no,location=no');} return false;"></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="singleBranchMap" style="width: 100%; height: 650px;"></div>
                        </div>
                    </div>
                </div>
                <!-- END SINGLE BRANCH MAP -->

                <!-- PER CLIENT BRANCHES MAP -->
                <div class="container-fluid px-4 mb-2" id="perClientBranchesMapContainer" style="display:none;">
                    <div class="card">
                        <div class="card-header text-body">
                            <div class="d-flex justify-content-center align-items-center" style="height: 40px;">
                                <a id="perClientBranchesMapHeader" class="btn btn-outline-primary" style="font-size: 1.5rem; padding: 0.5rem 1rem;"
                                    onclick="if(window.selectedClientId){window.open('{{ route('superadmin_locatordata', ['client' => 'CLIENT_PLACEHOLDER']) }}'.replace('CLIENT_PLACEHOLDER', encodeURIComponent(window.selectedClientId)), '_blank', 'toolbar=no,scrollbars=yes,resizable=no,location=no');} return false;"></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="perClientBranchesMap" style="width: 100%; height: 650px;"></div>
                        </div>
                    </div>
                </div>
                <!-- END PER CLIENT BRANCHES MAP -->

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

                    function renderPerClientBranchesMap(clientid) {
                        var filtered = allBranches.filter(function(branch) {
                            return branch.clientid == clientid;
                        });
                        if (filtered.length === 0) return;
                        var centerLat = 12.8797,
                            centerLng = 121.7740;
                        if (filtered[0].branchgeolocation) {
                            var coords = filtered[0].branchgeolocation.split(',');
                            centerLat = parseFloat(coords[0]);
                            centerLng = parseFloat(coords[1]);
                        }
                        var map = new google.maps.Map(document.getElementById('perClientBranchesMap'), {
                            zoom: 6,
                            center: {
                                lat: centerLat,
                                lng: centerLng
                            },
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        });
                        var infowindow = new google.maps.InfoWindow();
                        var markerImage = {
                            url: "{{ asset('assets/img/mapmarker.svg') }}",
                            scaledSize: new google.maps.Size(12, 12),
                            anchor: new google.maps.Point(12, 25)
                        };
                        filtered.forEach(function(branch) {
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
                    // UI and DataTable logic
                    document.addEventListener('DOMContentLoaded', function() {
                        const allBranchesTable = new simpleDatatables.DataTable(document.getElementById('datatablesSimple'));
                        const clientBranchesTable = new simpleDatatables.DataTable(document.getElementById('datatablesSimpleClientBranches'));

                        function hideAllMapViews() {
                            document.getElementById('allBranchesTable').style.display = 'none';
                            document.getElementById('clientBranchesTable').style.display = 'none';
                            document.getElementById('mapContainer').style.display = 'none';
                            document.getElementById('singleBranchMapContainer').style.display = 'none';
                            document.getElementById('perClientBranchesMapContainer').style.display = 'none';
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

                        function attachViewPerClientBtnListeners() {
                            document.querySelectorAll('.viewPerClientBtn').forEach(function(btn) {
                                btn.addEventListener('click', function() {
                                    var clientid = this.getAttribute('data-clientid');
                                    var clientShortname = this.getAttribute('data-clientname');
                                    window.selectedClientId = clientid;
                                    window.selectedClientShortname = clientShortname;
                                    hideAllMapViews();
                                    document.getElementById('perClientBranchesMapContainer').style.display = 'block';
                                    document.getElementById('perClientBranchesMapHeader').innerHTML =
                                        '<i data-feather="eye" style="width:1.5rem;height:1.5rem;vertical-align:middle;margin-right:0.5rem;"></i>' +
                                        clientShortname;
                                    feather.replace();
                                    document.getElementById('perClientBranchesMap').innerHTML = '';
                                    setTimeout(function() {
                                        renderPerClientBranchesMap(clientid);
                                    }, 100);
                                });
                            });
                        }
                        // Initial attach
                        attachViewBranchBtnListeners();
                        attachViewPerClientBtnListeners();
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
                        clientBranchesTable.on('datatable.page', function() {
                            feather.replace();
                            attachViewPerClientBtnListeners();
                        });
                        clientBranchesTable.on('datatable.search', function() {
                            feather.replace();
                            attachViewPerClientBtnListeners();
                        });
                        clientBranchesTable.on('datatable.sort', function() {
                            feather.replace();
                            attachViewPerClientBtnListeners();
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
                        document.getElementById('viewClientBranchesBtn').addEventListener('click', function() {
                            hideAllMapViews();
                            document.getElementById('clientBranchesTable').style.display = 'block';
                        });
                    });
                    // Google Maps API callback
                    function initBranchMaps() {
                        renderAllBranchesMap();
                    }
                </script>
            </main>
            @include('superadmin.partials.footer')
        </div>
    </div>
</body>

</html>