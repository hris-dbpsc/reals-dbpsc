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
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="map"></i></div>
                                    Employee Locator
                                </h1>
                                <div class="page-header-subtitle">A Geographic Information System Application</div>
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
                        <a class="card lift h-100" href="javascript:void(0);" id="viewAllClientsBtn">
                            <div class="card-body d-flex justify-content-center flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                        <div class="icons-org-join align-items-center mx-auto">
                                            <i class="icon-user text-primary" data-feather="users"></i>
                                        </div>
                                        <h3>View All Employees</h3>
                                        <div class="text-muted small">Locate all Employees</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-4 mb-2">
                        <a class="card lift h-100" href="javascript:void(0);" id="searchClientBtn">
                            <div class="card-body d-flex justify-content-center flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">                                        
                                        <div class="icons-org-join align-items-center mx-auto">
                                            <i class="icon-user text-secondary" data-feather="users"></i>
                                            <i class="icon-arrow fas fa-long-arrow-alt-right text-secondary"></i>
                                            <i class="icon-users text-secondary" data-feather="globe"></i>
                                        </div>
                                        <h3>Per Client</h3>
                                        <div class="text-muted small">Locate an Employee per Client</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-4 mb-2">
                        <a class="card lift h-100" href="javascript:void(0);" id="searchClientBtn">
                            <div class="card-body d-flex justify-content-center flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                        <div class="icons-org-join align-items-center mx-auto">
                                            <i class="icon-user text-info" data-feather="users"></i>
                                            <i class="icon-arrow fas fa-long-arrow-alt-right text-info"></i>
                                            <i class="icon-users text-info" data-feather="list"></i>
                                        </div>
                                        <h3>Per Branch</h3>
                                        <div class="text-muted small">Locate an Employee per Branch</div>  
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 mb-2">
                        <a class="card lift h-100" href="javascript:void(0);" id="viewAllClientsBtn">
                            <div class="card-body d-flex justify-content-center flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                        <div class="icons-org-join align-items-center mx-auto">
                                            <i class="icon-user text-teal" data-feather="users"></i>
                                            <i class="icon-arrow fas fa-long-arrow-alt-right text-teal"></i>
                                            <i class="icon-users text-teal" data-feather="map"></i>
                                        </div>
                                        <h3>Per Major Island</h3>
                                        <div class="text-muted small">Locate an Employee per Major Island</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-4 mb-2">
                        <a class="card lift h-100" href="javascript:void(0);" id="searchClientBtn">
                            <div class="card-body d-flex justify-content-center flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">                                        
                                        <div class="icons-org-join align-items-center mx-auto">
                                            <i class="icon-user text-cyan" data-feather="users"></i>
                                            <i class="icon-arrow fas fa-long-arrow-alt-right text-cyan"></i>
                                            <i class="icon-users text-cyan" data-feather="map"></i>
                                        </div>
                                        <h3>Per Region</h3>
                                        <div class="text-muted small">Locate an Employee per Region</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-4 mb-2">
                        <a class="card lift h-100" href="javascript:void(0);" id="searchClientBtn">
                            <div class="card-body d-flex justify-content-center flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                        <div class="icons-org-join align-items-center mx-auto">
                                            <i class="icon-user text-success" data-feather="search"></i>
                                        </div>
                                        <h3>Search Employee</h3>
                                        <div class="text-muted small">Locate an Employee</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="container px-4" id="mapContainer" style="display:none;">
                <div class="card">
                    <div class="card-header">ALL EMPLOYEES</div>
                    <div class="card-body">
                         <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAp3O0pSkOc_3t150Rpo2L2BewfRR7DBgM&callback=initMap"
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

            <div class="container px-4" id="clientBranchesTable" style="display:none;">
                <div class="card">
                    <div class="card-header">ALL CLIENTS</div>
                    <div class="card-body">
                        <table id="datatablesSimpleSearchClient">
                            <thead>
                                <tr>
                                    <th>CLIENT SHORTNAME</th>
                                    <th>CLIENT</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>CLIENT SHORTNAME</th>
                                    <th>CLIENT</th>
                                    <th>ACTION</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($clients as $client)
                                <tr>
                                    <td>{{ $client->clientshortname }}</td>
                                    <td>{{ $client->clientname }}</td>
                                    <td>
                                        <button class="btn btn-transparent-dark" type="button"><i data-feather="map-pin"></i> VIEW</button>
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


        document.getElementById('viewAllClientsBtn').addEventListener('click', function() {
            document.getElementById('clientBranchesTable').style.display = 'none';
            document.getElementById('mapContainer').style.display = 'block';
        });

        document.getElementById('searchClientBtn').addEventListener('click', function() {
            document.getElementById('clientBranchesTable').style.display = 'block';
            document.getElementById('mapContainer').style.display = 'none';
        });
    });
</script>

        </main>
        @include('superadmin.footer')
        </body>

</html>