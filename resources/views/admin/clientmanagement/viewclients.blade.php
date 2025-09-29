<!DOCTYPE html>
<html lang="en">
@include('admin.partials.admin_header')

<body class="nav-fixed">
    @include('admin.partials.admin_topnav')
    <div id="layoutSidenav">
        @include('admin.partials.admin_sidenav')
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-2">
                    <div class="container-fluid px-4">
                        <div class="page-header-content">
                            <div class="row align-items-center justify-content-between pt-3">
                                <div class="col-auto mb-3">
                                    <h1 class="page-header-title text-body d-flex align-items-center">
                                        <a href="{{ route('admin_clients') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        <div class="page-header-icon"><i data-feather="users" style="width:25px; height:25px;"></i></div>
                                        Client Information
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
                            <div class="card mb-2 mb-xl-0">
                                <div class="card-header text-body">Client Photo</div>
                                <div class="card-body text-center">
                                    <!-- Profile picture image-->
                                    <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 200px; height: 200px; margin: auto;">
                                        <img src="{{ isset($client) && $client->clientphoto ? asset('assets/clients/' . $client->clientphoto) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Photo" width="200" height="200" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                        <span style="position: absolute; bottom: 12px; right: 12px; width: 32px; height: 32px; background: {{ $client->isactive == 1 ? '#28a745' : '#dc3545' }}; border: 2px solid #fff; border-radius: 50%; display: block;"></span>
                                    </div>
                                    <!-- Profile picture help block-->
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <!-- Account details card-->
                            <div class="card mb-2">
                                <div class="card-header text-body">Client Details</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-md">
                                            <tbody>
                                                <tr>
                                                    <th>Client</th>
                                                    <td>{{ $client->clientshortname }} - {{ $client->clientname }} </td>
                                                </tr>
                                                <tr>
                                                    <th>Type</th>
                                                    <td>
                                                        @if($client->clienttype === 'Government')
                                                        <span class="text-danger d-inline-flex align-items-center gap-1">
                                                            <span>{{ $client->clienttype }}</span>
                                                        </span>
                                                        @elseif($client->clienttype === 'Private')
                                                        <span class="text-primary d-inline-flex align-items-center gap-1">
                                                            <span>{{ $client->clienttype }}</span>
                                                        </span>
                                                        @else
                                                        <span class="text-secondary d-inline-flex align-items-center gap-1">
                                                            <span>{{ $client->clienttype }}</span>
                                                        </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        @if($client->isactive == 1)
                                                        <span class="text-body d-inline-flex align-items-center gap-1">
                                                            <i data-feather="check-circle"></i>
                                                            <span>Active</span>
                                                        </span>
                                                        @else
                                                        <span class="text-body d-inline-flex align-items-center gap-1">
                                                            <i data-feather="x-circle" class="me-1"></i>
                                                            <span>Inactive</span>
                                                        </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Contact</th>
                                                    <td>{{ $client->clientcontact ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Contact Person</th>
                                                    <td>{{ $client->clientcontactperson ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email</th>
                                                    <td>{{ $client->clientemail ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Address</th>
                                                    <td>{{ $client->clientaddress ?? '-' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        
                                        <div class="d-flex flex-column flex-xl-row align-items-stretch justify-content-center text-center gap-2">
                                            @if(!empty($client->clientgeolocation))
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($client->clientgeolocation) }}" target="_blank" class="btn btn-outline-primary">
                                                <i data-feather="map-pin" class="me-1"></i>
                                                view in map
                                            </a>
                                            @else
                                            <span class="text-muted">No geolocation available</span>
                                            @endif

                                            @if(!empty($client->clientstreetview))
                                            <a href="{{ $client->clientstreetview }}" target="_blank" class="btn btn-outline-primary">
                                                <i data-feather="map-pin" class="me-1"></i>
                                                view in streetview
                                            </a>
                                            @else
                                            <span class="text-muted">No street view available</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            @include('admin.partials.admin_footer')
</body>

</html>