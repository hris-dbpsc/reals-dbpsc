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
                                            <i data-feather="grid" style="width:30px; height:30px;"></i>
                                        </span>
                                        Applications
                                    </h2>
                                    <div class="page-header-subtitle text-body mt-2" style="font-size: 0.95rem;">Explore and manage all available applications in one place.</div>
                                </div>
                                <div class="col-auto mt-4">
                                    <a href="{{ route('superadmin_dashboard') }}" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:50px; height:50px; padding:0;">
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
                        <div class="col-xl-3 mb-2">
                            <a class="card lift h-100 shadow-lg border-0 position-relative" href="{{ route('superadmin_people') }}">
                                <div class="card-body d-flex justify-content-center flex-column text-center">
                                    <div class="d-flex align-items-center justify-content-center flex-column">
                                        <div class="me-3">
                                            <i class="feather text-cyan mb-1" data-feather="user" style="width: 64px; height: 64px;"></i>
                                            <h3 class="fw-bold text-body">People</h3>
                                            <div class="text-muted small mt-1">Employee Information System</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="position-absolute top-0 end-0 p-2">
                                    <i class="feather text-grey" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="top" title="View Employee Information"></i>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 mb-2">
                            <a class="card lift h-100 shadow-lg border-0 position-relative" href="{{ route('superadmin_watsonsworkforce') }}">
                                <div class="card-body d-flex justify-content-center flex-column text-center">
                                    <div class="d-flex align-items-center justify-content-center flex-column">
                                        <div class="me-3">
                                            <i class="feather text-pink mb-1" data-feather="users" style="width: 64px; height: 64px;"></i>
                                            <h3 class="fw-bold text-body">WorkForce</h3>
                                            <div class="text-muted small mt-1">Workforce Management System</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="position-absolute top-0 end-0 p-2">
                                    <i class="feather text-grey" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="top" title="Manage your workforce efficiently"></i>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 mb-2">
                            <a class="card lift h-100 shadow-lg border-0 position-relative" href="{{ route('superadmin_timeoff') }}">
                                <div class="card-body d-flex justify-content-center flex-column text-center">
                                    <div class="d-flex align-items-center justify-content-center flex-column">
                                        <div class="me-3">
                                            <i class="feather text-orange mb-1" data-feather="calendar" style="width: 64px; height: 64px;"></i>
                                            <h3 class="fw-bold text-body">TimeOff</h3>
                                            <div class="text-muted small mt-1">Leave Management System</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="position-absolute top-0 end-0 p-2">
                                    <i class="feather text-grey" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="top" title="Manage Employee Leave"></i>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 mb-2">
                            <a class="card lift h-100 shadow-lg border-0 position-relative" href="{{ route('superadmin_locator') }}">
                                <div class="card-body d-flex justify-content-center flex-column text-center">
                                    <div class="d-flex align-items-center justify-content-center flex-column">
                                        <div class="me-3">
                                            <i class="feather text-green mb-1" data-feather="map-pin" style="width: 64px; height: 64px;"></i>
                                            <h3 class="fw-bold text-body">Locator</h3>
                                            <div class="text-muted small mt-1">Geographic Information System</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="position-absolute top-0 end-0 p-2">
                                    <i class="feather text-grey" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="top" title="Locate Branches and Employees Geographically"></i>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 mb-2">
                            <a class="card lift h-100 shadow-lg border-0 position-relative" href="{{ route('superadmin_timelog') }}">
                                <div class="card-body d-flex justify-content-center flex-column text-center">
                                    <div class="d-flex align-items-center justify-content-center flex-column">
                                        <div class="me-3">
                                            <i class="feather text-yellow mb-1" data-feather="clock" style="width: 64px; height: 64px;"></i>
                                            <h3 class="fw-bold text-body">TimeLog</h3>
                                            <div class="text-muted small mt-1">Attendance Management System</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="position-absolute top-0 end-0 p-2">
                                    <i class="feather text-grey" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="top" title="Attendance Monitoring System"></i>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 mb-2">
                            <a class="card lift h-100 shadow-lg border-0 position-relative" href="{{ route('superadmin_payslip') }}">
                                <div class="card-body d-flex justify-content-center flex-column text-center">
                                    <div class="d-flex align-items-center justify-content-center flex-column">
                                        <div class="me-3">
                                            <i class="feather text-danger mb-1" data-feather="book" style="width: 64px; height: 64px;"></i>
                                            <h3 class="fw-bold text-body">Payslip</h3>
                                            <div class="text-muted small mt-1">Payslip Management System</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="position-absolute top-0 end-0 p-2">
                                    <i class="feather text-grey" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="top" title="Manage Employee Payslip"></i>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 mb-2">
                            <a class="card lift h-100 shadow-lg border-0 position-relative" href="#!">
                                <div class="card-body d-flex justify-content-center flex-column text-center">
                                    <div class="d-flex align-items-center justify-content-center flex-column">
                                        <div class="me-3">
                                            <i class="feather text-blue mb-1" data-feather="message-square" style="width: 64px; height: 64px;"></i>
                                            <h3 class="fw-bold text-body">WorkChat</h3>
                                            <span class="badge bg-warning rounded-pill px-3 py-2">Recommended by ExeComm</span>
                                            <div class="text-muted small mt-1">Real-time Messaging Platform</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            </main>
            @include('superadmin.partials.footer')
</body>

</html>