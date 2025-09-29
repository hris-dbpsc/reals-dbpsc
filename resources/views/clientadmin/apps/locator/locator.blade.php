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
                                        Locator
                                    </h2>
                                    <div class="page-header-subtitle text-body mt-2">Geographic Information System</div>
                                </div>

                                <div class="col-auto mt-4">
                                    <a href="{{ route('clientadmin_apps') }}" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:50px; height:50px; padding:0;">
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
                        <div class="col-xl-6 mb-4">
                            <!-- Dashboard example card 2-->
                            <a class="card lift lift-sm h-100" href="{{ route('clientadmin_locatorbranch') }}">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-secondary mb-1" data-feather="list" style="width: 64px; height: 64px;"></i>
                                            <h3 class="fw-bold text-body">Branch Locator</h3>
                                            <div class="position-relative d-inline-block w-100">
                                                <div class="text-muted small mt-1">Locate all Branches</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-6 mb-4">
                            <!-- Dashboard example card 3-->
                            <a class="card lift lift-sm h-100" href="{{ route('clientadmin_locatoremployee') }}">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-success mb-1" data-feather="users" style="width: 64px; height: 64px;"></i>
                                            <h3 class="fw-bold text-body">Employee Locator</h3>
                                            <div class="position-relative d-inline-block w-100">
                                                <div class="text-muted small mt-1">Locate all Employees</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </main>
            @include('clientadmin.partials.client_footer')
</body>

</html>