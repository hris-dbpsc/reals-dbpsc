<!DOCTYPE html>
<html lang="en">
@include('superadmin.header')
@include('superadmin.topnav')


<div id="layoutSidenav">
    @include('superadmin.sidenav')
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                <div class="container-xl px-4">
                    <div class="page-header-content pt-4">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mt-4">
                                <h1 class="page-header-title d-flex align-items-center">
                                    <div class="page-header-icon me-2 bg-primary text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                        <i data-feather="map-pin" style="width: 30px; height: 30px;"></i>
                                    </div>
                                    Branch Locator
                                </h1>
                                <div class="page-header-subtitle text-muted mt-2">A Geographic Information System Application</div>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="{{ route('superadmin_applocator') }}">
                                    <i class="me-1" data-feather="arrow-left"></i>
                                    Back to Locator
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-xl px-4 mt-n10">
                <div class="row">
                    <div class="col-xl-4 mb-2">
                        <a class="card lift h-100" href="javascript:void(0);" id="viewAllBranchesBtn" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                            <div class="card-body d-flex justify-content-center flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                        <i class="feather text-secondary mb-1" data-feather="list" style="width: 64px; height: 64px;"></i>
                                        <h3 class="fw-bold">View All Branches</h3>
                                        <div class="text-muted small mt-1">Locate all Branches</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-4 mb-2">
                        <a class="card lift h-100" href="javascript:void(0);" id="viewClientBranchesBtn" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                            <div class="card-body d-flex justify-content-center flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                        <i class="feather text-primary mb-1" data-feather="globe" style="width: 64px; height: 64px;"></i>
                                        <h3 class="fw-bold">View Per Client</h3>
                                        <div class="text-muted small mt-1">Locate Client Branches</div>
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
                                        <h3 class="fw-bold">Search Branch</h3>
                                        <div class="text-muted small mt-1">Locate a Branch</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <script>
                        var allBranches = {!! json_encode($branches->where('isactive', 1)->values()) !!};

                        function renderAllBranchesMap() {
                            var map = new google.maps.Map(document.getElementById('mapAllBranches'), {
                                zoom: 6,
                                center: { lat: 12.8797, lng: 121.7740 },
                                mapTypeId: google.maps.MapTypeId.ROADMAP
                            });
                            var infowindow = new google.maps.InfoWindow();
                            var markerImage = {
                                url: "{{ asset('assets/img/mapmarker.svg') }}",
                                scaledSize: new google.maps.Size(15, 15),
                                origin: new google.maps.Point(0, 0),
                                anchor: new google.maps.Point(10, 20)
                            };
                            allBranches.forEach(function(branch) {
                                if (!branch.branchgeolocation) return;
                                var coords = branch.branchgeolocation.split(',');
                                var lat = parseFloat(coords[0]);
                                var lng = parseFloat(coords[1]);
                                var marker = new google.maps.Marker({
                                    position: { lat: lat, lng: lng },
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
                    </script>
                </div>
            </div>
            <!-- Load Google Maps API once only -->
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOeXSCBI7MECVo71S4FvRchTP9wE_dnBI&callback=initMap"></script>

            <div class="container px-4" id="mapContainer" style="display:none;">
                <div class="card">
                    <div class="card-header">ALL BRANCHES</div>
                    <div class="card-body">
                        <div id="mapAllBranches" style="width: 100%; height: 650px;"></div>
                    </div>
                </div>
            </div>
            <!-- Table containers, hidden by default -->
            <div class="container px-4" id="allBranchesTable" style="display:none;">
                <div class="card">
                    <div class="card-header">SEARCH BRANCH</div>
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
                                        @if($branch->clientphoto)
                                        <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 70px; height: 70px; margin: auto;">
                                            <img src="{{ $branch->clientphoto ? asset('assets/clients/' . $branch->clientphoto) : asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Photo" width="55" height="55" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                            <span style="position: absolute; bottom: 7px; right: 7px; width: 14px; height: 14px; background: #28a745; border: 2px solid #fff; border-radius: 50%; display: block; z-index: 2;"></span>
                                        </div>
                                        @else
                                        <img src="{{ asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Photo" width="50" height="50" style="object-fit:cover; display:block; margin:auto;">
                                        @endif
                                    </td>
                                    <td>{{ $branch->clientname }}</td>
                                    <td>{{ $branch->branchname }}</td>
                                    <td>{{ $branch->branchregion }}</td>
                                    <td>
                                        @if($branch->clienttype === 'Government')
                                        <span class="badge bg-danger">Government</span>
                                        @elseif($branch->clienttype === 'Private')
                                        <span class="badge bg-primary">Private</span>
                                        @else
                                        <span class="badge bg-secondary">{{ ucfirst($branch->clienttype) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-xs btn-primary viewBranchBtn" type="button"
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

            <div class="container px-4" id="clientBranchesTable" style="display:none;">
                <div class="card">
                    <div class="card-header">ALL CLIENTS</div>
                    <div class="card-body">
                        <table id="datatablesSimpleClientBranches">
                            <thead>
                                <tr>
                                    <th>PHOTO</th>
                                    <th>CLIENT</th>
                                    <th>CLIENT</th>
                                    <th>TYPE</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>PHOTO</th>
                                    <th>CLIENT</th>
                                    <th>CLIENT</th>
                                    <th>TYPE</th>
                                    <th>ACTION</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($clients->where('isactive', 1)->sortBy('clientshortname') as $client)
                                <tr>
                                    <td>
                                        <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 70px; height: 70px; margin: auto;">
                                            <img src="{{ $client->clientphoto ? asset('assets/clients/' . $client->clientphoto) : asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Photo" width="70" height="70" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                            <span style="position: absolute; bottom: 4px; right: 4px; width: 16px; height: 16px; background: #28a745; border: 2px solid #fff; border-radius: 50%; display: block;"></span>
                                        </div>
                                    </td>
                                    <td>{{ $client->clientshortname }}</td>
                                    <td>{{ $client->clientname }}</td>
                                    <td>
                                        @if($client->clienttype === 'Government')
                                        <span class="badge bg-danger">Government</span>
                                        @elseif($client->clienttype === 'Private')
                                        <span class="badge bg-primary">Private</span>
                                        @else
                                        <span class="badge bg-secondary">{{ ucfirst($client->clienttype) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $hasBranches = $branches->where('clientshortname', $client->clientshortname)->where('isactive', 1)->count() > 0;
                                        @endphp
                                        <button class="btn btn-sm btn-primary viewPerClientBtn" type="button"
                                            data-clientshortname="{{ $client->clientshortname }}"
                                            @if(!$hasBranches) disabled @endif>
                                            <i data-feather="map-pin"></i> VIEW
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- SINGLE BRANCH MAP -->
            <div class="container px-4" id="singleBranchMapContainer" style="display:none;">
                <div class="card">
                    <div class="card-header" id="singleBranchMapHeader">BRANCH MAP</div>
                    <div class="card-body">
                        <div id="singleBranchMap" style="width: 100%; height: 650px;"></div>
                    </div>
                </div>
            </div>
            <!-- END SINGLE BRANCH MAP -->

            <!-- PER CLIENT BRANCHES MAP -->
            <div class="container px-4" id="perClientBranchesMapContainer" style="display:none;">
                <div class="card">
                    <div class="card-header" id="perClientBranchesMapHeader">CLIENT BRANCHES MAP</div>
                    <div class="card-body">
                        <div id="perClientBranchesMap" style="width: 100%; height: 650px;"></div>
                    </div>
                </div>
            </div>
            <!-- END PER CLIENT BRANCHES MAP -->

            <script>
            var allBranches = {!! json_encode($branches->where('isactive', 1)->values()) !!};

                // Make all branches available for per-client map (declare at top-level, outside any function)
                document.addEventListener('DOMContentLoaded', function() {
                    // Existing code...
                    const allBranchesTable = new simpleDatatables.DataTable(document.getElementById('datatablesSimple'));
                    const clientBranchesTable = new simpleDatatables.DataTable(document.getElementById('datatablesSimpleClientBranches'));


                    document.getElementById('viewAllBranchesBtn').addEventListener('click', function() {
                        document.getElementById('allBranchesTable').style.display = 'none';
                        document.getElementById('clientBranchesTable').style.display = 'none';
                        document.getElementById('mapContainer').style.display = 'block';
                        document.getElementById('singleBranchMapContainer').style.display = 'none';
                        document.getElementById('perClientBranchesMapContainer').style.display = 'none';
                        document.getElementById('mapAllBranches').innerHTML = '';
                        setTimeout(renderAllBranchesMap, 100);
                    });

                    document.getElementById('viewBranchBtn').addEventListener('click', function() {
                        document.getElementById('allBranchesTable').style.display = 'block';
                        document.getElementById('clientBranchesTable').style.display = 'none';
                        document.getElementById('mapContainer').style.display = 'none';
                    });

                    document.getElementById('viewClientBranchesBtn').addEventListener('click', function() {
                        document.getElementById('clientBranchesTable').style.display = 'block';
                        document.getElementById('allBranchesTable').style.display = 'none';
                        document.getElementById('mapContainer').style.display = 'none';
                    });

                    function renderSingleBranchMap(geolocation, branchName, streetView) {
                        const coords = geolocation.split(',');
                        const lat = parseFloat(coords[0]);
                        const lng = parseFloat(coords[1]);
                        const map = new google.maps.Map(document.getElementById('singleBranchMap'), {
                            zoom: 15,
                            center: { lat: lat, lng: lng },
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        });
                        const infowindow = new google.maps.InfoWindow();
                        const markerImage = {
                            url: "{{ asset('assets/img/mapmarker.svg') }}",
                            scaledSize: new google.maps.Size(30, 30),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(15, 30)
                        };
                        const marker = new google.maps.Marker({
                            position: { lat: lat, lng: lng },
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

                    function attachViewBranchBtnListeners() {
                        document.querySelectorAll('.viewBranchBtn').forEach(function(btn) {
                            btn.addEventListener('click', function() {
                                var branchName = this.getAttribute('data-branchname');
                                var clientName = this.getAttribute('data-clientname');
                                var geolocation = this.getAttribute('data-geolocation');
                                var streetView = this.getAttribute('data-streetview');
                                document.getElementById('allBranchesTable').style.display = 'none';
                                document.getElementById('clientBranchesTable').style.display = 'none';
                                document.getElementById('mapContainer').style.display = 'none';
                                document.getElementById('singleBranchMapContainer').style.display = 'block';
                                document.getElementById('singleBranchMapHeader').innerText = branchName + ' (' + clientName + ')';
                                document.getElementById('singleBranchMap').innerHTML = '';
                                setTimeout(function() { renderSingleBranchMap(geolocation, branchName, streetView); }, 100);
                            });
                        });
                    }

                    function renderPerClientBranchesMap(clientShortname) {
                        var filtered = allBranches.filter(function(branch) {
                            return branch.clientshortname === clientShortname;
                        });
                        if (filtered.length === 0) return;
                        var centerLat = 12.8797, centerLng = 121.7740;
                        if (filtered[0].branchgeolocation) {
                            var coords = filtered[0].branchgeolocation.split(',');
                            centerLat = parseFloat(coords[0]);
                            centerLng = parseFloat(coords[1]);
                        }
                        var map = new google.maps.Map(document.getElementById('perClientBranchesMap'), {
                            zoom: 10,
                            center: { lat: centerLat, lng: centerLng },
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        });
                        var infowindow = new google.maps.InfoWindow();
                        var markerImage = {
                            url: "{{ asset('assets/img/mapmarker.svg') }}",
                            scaledSize: new google.maps.Size(25, 25),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(12, 25)
                        };
                        filtered.forEach(function(branch) {
                            if (!branch.branchgeolocation) return;
                            var coords = branch.branchgeolocation.split(',');
                            var lat = parseFloat(coords[0]);
                            var lng = parseFloat(coords[1]);
                            var marker = new google.maps.Marker({
                                position: { lat: lat, lng: lng },
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

                    function attachViewPerClientBtnListeners() {
                        document.querySelectorAll('.viewPerClientBtn').forEach(function(btn) {
                            btn.addEventListener('click', function() {
                                var clientShortname = this.getAttribute('data-clientshortname');
                                document.getElementById('allBranchesTable').style.display = 'none';
                                document.getElementById('clientBranchesTable').style.display = 'none';
                                document.getElementById('mapContainer').style.display = 'none';
                                document.getElementById('singleBranchMapContainer').style.display = 'none';
                                document.getElementById('perClientBranchesMapContainer').style.display = 'block';
                                document.getElementById('perClientBranchesMapHeader').innerText = 'Branches for ' + clientShortname;
                                document.getElementById('perClientBranchesMap').innerHTML = '';
                                setTimeout(function() { renderPerClientBranchesMap(clientShortname); }, 100);
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
                });
            </script>





        </main>
        @include('superadmin.footer')
        </body>

</html>