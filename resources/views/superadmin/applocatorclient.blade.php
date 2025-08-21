<!DOCTYPE html>
<html lang="en">
@include('superadmin.header')
@include('superadmin.topnav')


<div id="layoutSidenav">
    @include('superadmin.sidenav')
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
                                    Client Locator
                                    </h1>
                                    <div class="page-header-subtitle text-muted mt-2">A Geographic Information System Application</div>
                            </div>
                            <div class="col-auto mt-4">
                                <a href="{{ route('superadmin_applocator') }}" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:50px; height:50px; padding:0;">
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
                    <div class="col-xl-6 mb-2">
                        <a class="card lift h-100 shadow-lg border-0 position-relative" href="javascript:void(0);" id="viewAllClientsBtn" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                            <div class="card-body d-flex justify-content-center flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                        <i class="feather text-primary mb-1" data-feather="list" style="width: 64px; height: 64px;"></i>
                                        <h3 class="fw-bold text-body">View All Clients</h3>
                                        <div class="position-relative d-inline-block w-100">
                                            <div class="text-muted small  mt-1">Locate all Clients</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-6 mb-2">
                        <a class="card lift h-100 shadow-lg border-0 position-relative" href="javascript:void(0);" id="searchClientBtn" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                            <div class="card-body d-flex justify-content-center flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                        <i class="feather text-success mb-1" data-feather="search" style="width: 64px; height: 64px;"></i>
                                        <h3 class="fw-bold text-body">Search Client</h3>
                                        <div class="position-relative d-inline-block w-100">
                                            <div class="text-muted small mt-1">Locate a Client</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Load Google Maps API once only -->
            <script src="https://maps.googleapis.com/maps/api/js?key=callback=initMap"></script>

            <!-- ALL CLIENTS MAP -->
            <div class="container-fuild px-4" id="mapContainer" style="display:none;">
                <div class="card">
                    <div class="card-header text-body">ALL CLIENTS</div>
                    <div class="card-body">
                        <div id="map1" style="width: 100%; height: 650px;"></div>
                        @php
                        $locations = $clients->map(function($client) {
                        $geoloc = explode(',', $client->clientgeolocation);
                        return [
                        $client->clientname,
                        isset($geoloc[0]) ? floatval($geoloc[0]) : 0,
                        isset($geoloc[1]) ? floatval($geoloc[1]) : 0,
                        0,
                        $client->clientstreetview,
                        ];
                        });
                        @endphp
                        <script>
                            var locations = {!! json_encode($locations) !!};

                            function initMap1() {
                                var map = new google.maps.Map(document.getElementById('map1'), {
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
                                    origin: new google.maps.Point(0, 0),
                                    anchor: new google.maps.Point(10, 20)
                                };

                                for (let i = 0; i < locations.length; i++) {
                                    let marker = new google.maps.Marker({
                                        position: {
                                            lat: locations[i][1],
                                            lng: locations[i][2]
                                        },
                                        map: map,
                                        icon: markerImage,
                                        url: locations[i][4]
                                    });

                                    marker.addListener('mouseover', function() {
                                        infowindow.setContent(locations[i][0]);
                                        infowindow.open(map, marker);
                                    });

                                    marker.addListener('click', function() {
                                        infowindow.setContent(locations[i][0]);
                                        infowindow.open(map, marker);
                                        window.open(this.url, '_blank',
                                            "toolbar=yes,scrollbars=yes,resizable=yes,width=700,height=500");
                                    });
                                }
                            }
                        </script>
                    </div>
                </div>
            </div>
            <!-- END ALL CLIENTS MAP -->

            <!-- SEARCH CLIENT MAP -->
            <div class="container-fluid px-4" id="mapContainer2" style="display:none;">
                <div class="card">
                    <div class="card-header text-body">
                        <div class="d-flex justify-content-center align-items-center" style="height: 40px;">
                            <span id="selectedClientDisplay" class="text-body" style="font-size: 1.2rem; padding: 0.5rem 1rem;"></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="map2" style="width: 100%; height: 650px;"></div>
                        @php
                        $locations2 = $clients->map(function($client) {
                        $geoloc2 = explode(',', $client->clientgeolocation);
                        return [
                        $client->clientshortname, // use clientshortname here
                        isset($geoloc2[0]) ? floatval($geoloc2[0]) : 0,
                        isset($geoloc2[1]) ? floatval($geoloc2[1]) : 0,
                        0,
                        $client->clientstreetview,
                        ];
                        });
                        @endphp
                        <script>
                            var locations2 = {!!json_encode($locations2) !!};
                            var selectedClientName = null;

                            function initMap2() {
                                var centerLat = 12.8797;
                                var centerLng = 121.7740;
                                var zoomLevel = 13;
                                // Find the selected client and set center if found
                                if (selectedClientName) {
                                    for (let i = 0; i < locations2.length; i++) {
                                        if (locations2[i][0] === selectedClientName) {
                                            centerLat = locations2[i][1];
                                            centerLng = locations2[i][2];
                                            break;
                                        }
                                    }
                                }
                                var map = new google.maps.Map(document.getElementById('map2'), {
                                    zoom: zoomLevel,
                                    center: {
                                        lat: centerLat,
                                        lng: centerLng
                                    },
                                    mapTypeId: google.maps.MapTypeId.ROADMAP
                                });
                                var infowindow = new google.maps.InfoWindow();
                                var markerImage = {
                                    url: "{{ asset('assets/img/mapmarker.svg') }}",
                                    scaledSize: new google.maps.Size(35, 35),
                                    origin: new google.maps.Point(0, 0),
                                    anchor: new google.maps.Point(10, 20)
                                };
                                for (let i = 0; i < locations2.length; i++) {
                                    if (selectedClientName && locations2[i][0] !== selectedClientName) continue;
                                    let marker = new google.maps.Marker({
                                        position: {
                                            lat: locations2[i][1],
                                            lng: locations2[i][2]
                                        },
                                        map: map,
                                        icon: markerImage,
                                        url: locations2[i][4]
                                    });
                                    marker.addListener('mouseover', function() {
                                        infowindow.setContent(locations2[i][0]);
                                        infowindow.open(map, marker);
                                    });
                                    marker.addListener('click', function() {
                                        infowindow.setContent(locations2[i][0]);
                                        infowindow.open(map, marker);
                                        window.open(this.url, '_blank', "toolbar=yes,scrollbars=yes,resizable=yes,width=700,height=500");
                                    });
                                }
                            }
                        </script>

                    </div>
                </div>
            </div>
            <!-- END SEARCH CLIENT MAP -->

            <script>
                // Initialize both maps after page load
                window.onload = function() {
                    initMap1();
                    initMap2();
                };
            </script>


            <div class="container-fluid px-4" id="clientBranchesTable" style="display:none;">
                <div class="card">
                    <div class="card-header text-body">ALL CLIENTS</div>
                    <div class="card-body">
                        <table id="datatablesSimpleSearchClient">
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
                                        <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 54px; height: 54px; margin: auto;">
                                            <img src="{{ $client->clientphoto ? asset('assets/clients/' . $client->clientphoto) : asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Photo" width="48" height="48" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                            <span style="position: absolute; bottom: 4px; right: 4px; width: 13px; height: 13px; background: #28a745; border: 2px solid #fff; border-radius: 50%; display: block;"></span>
                                        </div>
                                    </td>
                                    <td>{{ $client->clientshortname }}</td>
                                    <td>{{ $client->clientname }}</td>
                                    <td>
                                        @if($client->clienttype === 'Government')
                                        <span class="text-danger d-inline-flex align-items-center gap-1">
                                            <span>{{ $client->clienttype }}</span>
                                        </span>
                                        @else
                                        <span class="text-primary d-inline-flex align-items-center gap-1">
                                            <span>{{ $client->clienttype }}</span>
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <button class="btn bg-light text-primary viewClientBtn" type="button" data-clientname="{{ $client->clientshortname }}" @if(is_null($client->clientgeolocation)) disabled @endif >
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


            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Existing code...
                    const clientBranchesTable = new simpleDatatables.DataTable(document.getElementById('datatablesSimpleSearchClient'));

                    function attachViewClientBtnListeners() {
                        document.querySelectorAll('.viewClientBtn').forEach(function(btn) {
                            btn.addEventListener('click', function() {
                                var clientName = this.getAttribute('data-clientname');
                                selectedClientName = clientName;
                                document.getElementById('clientBranchesTable').style.display = 'none';
                                document.getElementById('mapContainer').style.display = 'none';
                                document.getElementById('mapContainer2').style.display = 'block';
                                // Clear previous map
                                document.getElementById('map2').innerHTML = '';
                                // Display the selected client shortname after the map
                                document.getElementById('selectedClientDisplay').textContent = clientName;
                                // Re-initialize map2 with the selected client only
                                setTimeout(function() {
                                    initMap2();
                                }, 100);
                            });
                        });
                    }

                    // Initial attach
                    attachViewClientBtnListeners();

                    // Re-attach listeners and re-render feather icons after table redraw
                    clientBranchesTable.on('datatable.page', function() {
                        feather.replace();
                        attachViewClientBtnListeners();
                    });
                    clientBranchesTable.on('datatable.search', function() {
                        feather.replace();
                        attachViewClientBtnListeners();
                    });
                    clientBranchesTable.on('datatable.sort', function() {
                        feather.replace();
                        attachViewClientBtnListeners();
                    });

                    document.getElementById('viewAllClientsBtn').addEventListener('click', function() {
                        document.getElementById('clientBranchesTable').style.display = 'none';
                        document.getElementById('mapContainer').style.display = 'block';
                        document.getElementById('mapContainer2').style.display = 'none';
                    });

                    document.getElementById('searchClientBtn').addEventListener('click', function() {
                        document.getElementById('clientBranchesTable').style.display = 'block';
                        document.getElementById('mapContainer').style.display = 'none';
                        document.getElementById('mapContainer2').style.display = 'none';
                        // Re-render feather icons and re-attach listeners in case table is shown again
                        feather.replace();
                        attachViewClientBtnListeners();
                    });
                });
            </script>

        </main>
        @include('superadmin.footer')
        </body>

</html>