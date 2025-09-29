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
                                        <a href="{{ route('admin_branches') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        <div class="page-header-icon"><i data-feather="list" style="width:25px; height:25px;"></i></div>
                                        Branch Information
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
                                    <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 200px; height: 200px; margin: auto;">
                                        <img src="{{ $branch->clientphoto ? asset('assets/clients/' . $branch->clientphoto) : asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Photo" width="200" height="200" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                        <span style="position: absolute; bottom: 12px; right: 12px; width: 32px; height: 32px; background: {{ $branch->isactive == 1 ? '#28a745' : '#dc3545' }}; border: 2px solid #fff; border-radius: 50%; display: block;"></span>
                                    </div>
                                    @else
                                    <img class="img-account-profile rounded-circle mb-2" src="{{ asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Photo" style="object-fit:cover; display:block; margin:auto;" width="150" height="150" />
                                    @endif
                                    <!-- Profile picture help block-->
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <!-- Account details card-->
                            <div class="card mb-2">
                                <div class="card-header text-body">Branch Details</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-md">
                                            <tbody>
                                                <tr>
                                                    <th>Client</th>
                                                    <td>{{ $branch->clientname ?? $branch->clientid }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Branch</th>
                                                    <td>{{ $branch->branchname }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Contact Number</th>
                                                    <td>{{ $branch->branchcontact }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Contact Person</th>
                                                    <td>{{ $branch->branchcontactperson }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Address</th>
                                                    <td>{{ $branch->branchaddress }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Region</th>
                                                    <td>{{ $branch->branchregion }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Province</th>
                                                    <td>{{ $branch->branchprovince }}</td>
                                                </tr>
                                                <tr>
                                                    <th>City</th>
                                                    <td>{{ $branch->branchcity }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Type</th>
                                                    <td>
                                                        @if($branch->clienttype === 'Government')
                                                        <span class="text-danger d-inline-flex align-items-center gap-1">
                                                            <span>{{ $branch->clienttype }}</span>
                                                        </span>
                                                        @else
                                                        <span class="text-primary d-inline-flex align-items-center gap-1">
                                                            <span>{{ $branch->clienttype }}</span>
                                                        </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        @if($branch->isactive == 1)
                                                        <span class="text-success d-inline-flex align-items-center gap-1">
                                                            <i data-feather="check-circle"></i>
                                                            <span>Active</span>
                                                        </span>
                                                        @else
                                                        <span class="text-danger d-inline-flex align-items-center gap-1">
                                                            <i data-feather="x-circle" class="me-1"></i>
                                                            <span>Inactive</span>
                                                        </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="d-flex flex-column flex-xl-row align-items-stretch justify-content-center text-center gap-2">
                                            @if(!empty($branch->branchgeolocation))
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($branch->branchgeolocation) }}" target="_blank" class="btn bg-light text-body">
                                                <i data-feather="map-pin" class="me-1"></i>
                                                view in map
                                            </a>
                                            @else
                                            <span class="text-muted">No geolocation available</span>
                                            @endif

                                            @if(!empty($branch->branchstreetview))
                                            <a href="{{ $branch->branchstreetview }}" target="_blank" class="btn bg-light text-body">
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