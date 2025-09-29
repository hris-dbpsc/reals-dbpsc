<!DOCTYPE html>
<html lang="en">
@include('clientadmin.partials.client_header')

<body>
    <div id="layoutSidenav">
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
                                        Employee Location
                                    </h2>
                                    <div class="page-header-subtitle text-body mt-2">A Geographic Information System Application</div>
                                </div>

                                <div class="col-auto mt-4">
                                    <a href="javascript:void(0);" onclick="window.close();" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:50px; height:50px; padding:0;">
                                        <i data-feather="x" class="text-primary" style="width:40px; height:40px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->

                <div class="container-fluid px-4 mt-n10">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">LIST OF EMPLOYEE(S)</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Employee:</strong> {{ $user->lastname }}, {{ $user->firstname }} {{ $user->middlename }}<br>
                                <strong>Branch:</strong> {{ $user->branchname }}<br>
                                <strong>Position:</strong> {{ $user->position }}<br>
                            </div>
                            <div id="map2" style="width: 100%; height: 650px;"></div>
                            <script>
                                function initMap2() {
                                    // Default center: Philippines
                                    var centerLat = 12.8797;
                                    var centerLng = 121.7740;
                                    var zoomLevel = 6;
                                    var markerLat = null;
                                    var markerLng = null;
                                    @if($geolocation)
                                        @php
                                            $geo = explode(',', $geolocation);
                                            $lat = isset($geo[0]) ? floatval($geo[0]) : null;
                                            $lng = isset($geo[1]) ? floatval($geo[1]) : null;
                                        @endphp
                                        centerLat = {{ $lat ?? 12.8797 }};
                                        centerLng = {{ $lng ?? 121.7740 }};
                                        zoomLevel = 16;
                                        markerLat = {{ $lat ?? 'null' }};
                                        markerLng = {{ $lng ?? 'null' }};
                                    @endif
                                    // If markerLat/Lng is set, always center the map on the marker
                                    if (markerLat && markerLng) {
                                        centerLat = markerLat;
                                        centerLng = markerLng;
                                    }
                                    var map = new google.maps.Map(document.getElementById('map2'), {
                                        zoom: zoomLevel,
                                        center: { lat: centerLat, lng: centerLng },
                                        mapTypeId: google.maps.MapTypeId.ROADMAP
                                    });
                                    if (markerLat && markerLng) {
                                        var marker = new google.maps.Marker({
                                            position: { lat: markerLat, lng: markerLng },
                                            map: map,
                                            icon: {
                                                url: "{{ asset('assets/img/mapmarker.svg') }}",
                                                scaledSize: new google.maps.Size(35, 35),
                                                origin: new google.maps.Point(0, 0),
                                                anchor: new google.maps.Point(10, 20)
                                            },
                                            title: "{{ $user->lastname }}, {{ $user->firstname }}"
                                        });
                                        var infowindow = new google.maps.InfoWindow({
                                            content: `<strong>{{ $user->lastname }}, {{ $user->firstname }} {{ $user->middlename }}</strong><br>{{ $user->branchname }}<br>{{ $user->position }}`
                                        });
                                        marker.addListener('click', function() {
                                            infowindow.open(map, marker);
                                        });
                                        infowindow.open(map, marker);
                                    }
                                }
                            </script>

                            @if(!empty($apiKey) && !empty($apiKey->api_key))
                                <script src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey->api_key }}&callback=initMap2" async defer></script>
                            @else
                                <div class="alert alert-danger mt-3">
                                    Google Maps API key is missing or invalid. Please contact the administrator.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
            @include('clientadmin.partials.client_footer')
        </div>
</body>

</html>