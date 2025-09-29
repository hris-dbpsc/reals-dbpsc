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
                                            <i data-feather="calendar" style="width:30px; height:30px;"></i>
                                        </span>
                                        TimeOff
                                    </h2>
                                    <div class="page-header-subtitle text-body mt-2">Leave Management System</div>
                                </div>
                                <div class="col-auto mt-4">
                                    <a href="{{ route('superadmin_apps') }}" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:50px; height:50px; padding:0;">
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
                        @if (session('success'))
                        <div class="alert alert-success alert-sm py-1 px-2">
                            {{ session('success') }}
                        </div>
                        @endif

                        <div class="col-xl-3 mb-2">
                            <!-- CARD 2-->
                            <a class="card lift lift-sm h-100" href="{{ route('superadmin_alltimeoff') }}?status=all">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-primary mb-1" data-feather="arrow-down-circle" style="width: 64px; height: 64px;"></i>
                                            <span class="badge bg-light text-primary ms-2 position-absolute top-0 end-0 mt-2 me-2" style="font-size: 1.2rem; padding: 0.6em 1em;">
                                                {{ $timeOffCountAll }}
                                            </span>
                                            <h3 class="fw-bold text-body">All Leave</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 mb-2">
                            <!-- CARD 3-->
                            <a class="card lift lift-sm h-100" href="{{ route('superadmin_alltimeoff') }}?status=pending">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-warning mb-1" data-feather="alert-circle" style="width: 64px; height: 64px;"></i>
                                            <span class="badge bg-light text-primary ms-2 position-absolute top-0 end-0 mt-2 me-2" style="font-size: 1.2rem; padding: 0.6em 1em;">
                                                {{ $timeOffCountPending }}
                                            </span>
                                            <h3 class="fw-bold text-body">Pending</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 mb-2">
                            <!-- CARD 4-->
                            <a class="card lift lift-sm h-100" href="{{ route('superadmin_alltimeoff') }}?status=approved">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-success mb-1" data-feather="check-circle" style="width: 64px; height: 64px;"></i>
                                            <span class="badge bg-light text-primary ms-2 position-absolute top-0 end-0 mt-2 me-2" style="font-size: 1.2rem; padding: 0.6em 1em;">
                                                {{ $timeOffCountApproved }}
                                            </span>
                                            <h3 class="fw-bold text-body">Approved</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 mb-2">
                            <!-- CARD 4-->
                            <a class="card lift lift-sm h-100" href="{{ route('superadmin_alltimeoff') }}?status=disapproved">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-danger mb-1" data-feather="x-circle" style="width: 64px; height: 64px;"></i>
                                            <span class="badge bg-light text-primary ms-2 position-absolute top-0 end-0 mt-2 me-2" style="font-size: 1.2rem; padding: 0.6em 1em;">
                                                {{ $timeOffCountDisapproved }}
                                            </span>
                                            <h3 class="fw-bold text-body">Disapproved</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <!-- <a href="" class="btn btn-outline-primary d-flex align-items-center">
                                <i data-feather="download" class="me-2"></i>
                                Export
                            </a> -->
                        </div>
                    </div>
                </div>

            </main>
            @include('superadmin.partials.footer')
</body>


</html>