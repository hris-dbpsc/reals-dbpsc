<!DOCTYPE html>
<html lang="en">
@include('superadmin.header')

<body class="nav-fixed">
@include('superadmin.topnav')
<div id="layoutSidenav">
    @include('superadmin.sidenav')
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                <div class="container-fluid px-4">
                    <div class="page-header-content pt-4">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mt-4">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="map"></i></div>
                                    Locator
                                </h1>
                                <div class="page-header-subtitle">A Geographic Information System Application</div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-fluid px-4">
                <div class="card mt-n10">
                    <div class="card-header">GIS</div>
                    <div class="card-body">
                        <script src="https://maps.googleapis.com/maps/api/js?key=callback=initMap"
                            type="text/javascript"></script>
                        <div id="map" style="width: 100%; height: 650px;"></div>
                        <script type="text/javascript">
                            @php
                                $locations = $branches->map(function($branch) {
                                    $geoloc = explode(',', $branch->branchgeoloc);
                                    return [
                                        $branch->branchname,
                                        isset($geoloc[0]) ? floatval($geoloc[0]) : 0,
                                        isset($geoloc[1]) ? floatval($geoloc[1]) : 0,
                                        0,
                                        $branch->branchstreetview,
                                    ];
                                });
                            @endphp
                            var locations = {!! json_encode($locations) !!};
                            var map = new google.maps.Map(document.getElementById('map'), {
                                zoom: 8,
                                center: new google.maps.LatLng(14.691835, 120.963921),
                                mapTypeId: google.maps.MapTypeId.ROADMAP
                            });

                            var infowindow = new google.maps.InfoWindow();

                            var marker, i;

                            for (i = 0; i < locations.length; i++) {

                                // FOR IMAGE MARKER
                                var image = "{{ asset('assets/img/mapmarker.svg') }}";
                                // FOR IMAGE MARKER
                                marker = new google.maps.Marker({
                                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                                    map: map,
                                    // FOR IMAGE MARKER
                                    icon: image,
                                    // FOR IMAGE MARKER
                                    // animation: google.maps.Animation.DROP,
                                    animation: google.maps.Animation.BOUNCE,
                                    url: locations[i][4]

                                });

                                google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
                                    return function() {
                                        infowindow.setContent(locations[i][0]);
                                        infowindow.open(map, marker);
                                    }
                                })(marker, i));

                                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                                    return function() {
                                        infowindow.setContent(locations[i][0]);
                                        infowindow.open(map, marker);
                                        // window.location.href = this.url;
                                        window.open(this.url, '_blank', "toolbar=yes,scrollbars=yes,resizable=yes,width=700,height=500");
                                    }
                                })(marker, i));

                            }
                        </script>
                    </div>
                </div>
            </div>
        </main>
        @include('superadmin.footer')
        </body>

</html>