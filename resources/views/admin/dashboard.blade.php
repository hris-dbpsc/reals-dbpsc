<!DOCTYPE html>
<html lang="en">

@include('admin.partials.admin_header')

<body class="nav-fixed">
    @include('admin.partials.admin_topnav')
    <div id="layoutSidenav">
        @include('admin.partials.admin_sidenav')
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header bg-gray-200 pb-10">
                    <div class="container-fluid px-4">
                        <div class="page-header-content pt-4">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mt-4">
                                    <h1 class="page-header-title">
                                        <div class="page-header-icon"><i data-feather="user"></i></div>
                                        {{ Auth::guard('admin')->user()->firstname }} {{ Auth::guard('admin')->user()->lastname }}
                                    </h1>
                                    <div class="page-header-subtitle">Welcome to your Dashboard</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <div class="container-fluid px-4 mt-n10">

                    <div class="row">
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Employees</div>
                                            <div class="text-lg fw-bold">{{ $employeeCount }}</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="users"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="{{ route('admin_useremployee') }}">Employee Management</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Clients</div>
                                            <div class="text-lg fw-bold">{{ $clientCount }}</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="globe"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="{{ route('admin_clientmanagement') }}">Client Management</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Apps</div>
                                            <div class="text-lg fw-bold">{{ $totalAppAccess }}</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="grid"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="{{ route('admin_apps') }}">View Applications</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Pending Requests</div>
                                            <div class="text-lg fw-bold">{{ $totalPending }}</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="message-circle"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="{{ route('admin_apps') }}">View Requests</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <!-- Area chart example-->
                            <div class="card mb-4">
                                <div class="card-header d-flex align-items-center">
                                    <i data-feather="users" class="me-2"></i>
                                    <span>Watsons Workforce Insights</span>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area"><canvas id="WatsonsWorkforceAreaChart" width="100%" height="30"></canvas></div>
                                </div>
                                <div class="card-footer position-relative">
                                    <a class="stretched-link" href="{{ route('admin_watsonsworkforce') }}">
                                        <div class="text-xs d-flex align-items-center justify-content-between">
                                            View More Data
                                            <i class="fas fa-long-arrow-alt-right"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6 mb-4">
                            <!-- Bar chart example-->
                            <div class="card h-100">
                                <div class="card-header d-flex align-items-center">
                                    <i data-feather="calendar" class="me-2"></i>
                                    <span>TimeOff Insights</span>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <div class="chart-bar"><canvas id="timeOffChart" width="100%" height="30"></canvas></div>
                                </div>
                                <div class="card-footer position-relative">
                                    <a class="stretched-link" href="{{ route('admin_timeoff') }}">
                                        <div class="text-xs d-flex align-items-center justify-content-between">
                                            View More Data
                                            <i class="fas fa-long-arrow-alt-right"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">APPS</div>
                        <div class="card-body">
                            <div class="row">
                                @if(!empty($canAccessPeopleApp))
                                <div class="col-xl-2 mb-2">
                                    <!-- Dashboard example card 1-->
                                    <a class="card lift h-100" href="{{ route('admin_people') }}">
                                        <div class="card-body d-flex justify-content-center flex-column">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="me-3">
                                                    <i class="feather-xl text-info" data-feather="user"></i>
                                                    <h5>People</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                                @if(!empty($canAccessWatsonsWorkforceApp))
                                <div class="col-xl-2 mb-2">
                                    <!-- Dashboard example card 1-->
                                    <a class="card lift h-100" href="{{ route('admin_watsonsworkforce') }}">
                                        <div class="card-body d-flex justify-content-center flex-column">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="me-3">
                                                    <i class="feather-xl text-pink" data-feather="users"></i>
                                                    <h5>WatsonsWorkForce</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                                @if(!empty($canAccessTimeoffApp))
                                <div class="col-xl-2 mb-2">
                                    <!-- Dashboard example card 1-->
                                    <a class="card lift h-100" href="{{ route('admin_timeoff') }}">
                                        <div class="card-body d-flex justify-content-center flex-column">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="me-3">
                                                    <i class="feather-xl text-orange" data-feather="calendar"></i>
                                                    <h5>TimeOff</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                                @if(!empty($canAccessLocatorApp))
                                <div class="col-xl-2 mb-2">
                                    <!-- Dashboard example card 1-->
                                    <a class="card lift h-100" href="{{ route('admin_locator') }}">
                                        <div class="card-body d-flex justify-content-center flex-column">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="me-3">
                                                    <i class="feather-xl text-green" data-feather="map-pin"></i>
                                                    <h5>Locator</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                                @if(!empty($canAccessTimelogApp))
                                <div class="col-xl-2 mb-2">
                                    <!-- Dashboard example card 1-->
                                    <a class="card lift h-100" href="{{ route('admin_timelog') }}">
                                        <div class="card-body d-flex justify-content-center flex-column">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="me-3">
                                                    <i class="feather-xl text-yellow" data-feather="clock"></i>
                                                    <h5>TimeLog</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                                @if(!empty($canAccessWorkChatApp))
                                <div class="col-xl-2 mb-2">
                                    <!-- Dashboard example card 1-->
                                    <a class="card lift h-100" href="#!">
                                        <div class="card-body d-flex justify-content-center flex-column">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="me-3">
                                                    <i class="feather-xl text-blue" data-feather="message-square"></i>
                                                    <span class="badge bg-primary ms-2" style="font-size: 0.7em;">Coming soon</span>
                                                    <h5>WorkChat</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Example DataTable for Dashboard Demo-->

                    <div class="card mb-4">
                        <div class="card-header">ADMINS</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xxl-6">
                                    <div class="row">
                                        <div class="col-xl-6 col-xxl-12">
                                            <!-- Team members / people dashboard card example-->
                                            <div class="card mb-4">
                                                <div class="card-header">Admins</div>
                                                <div class="card-body">
                                                    <!-- Item 1-->
                                                    @foreach($activeAdmins as $admin)
                                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                                        <div class="d-flex align-items-center flex-shrink-0 me-3">
                                                            <div class="avatar avatar-xl me-3 bg-gray-200">
                                                                @if($admin->photo)
                                                                <img class="avatar-img img-fluid" src="{{ asset('assets/users/admin/' . $admin->photo) }}" alt="" />
                                                                @else
                                                                <img class="avatar-img img-fluid" src="{{ asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="" />
                                                                @endif
                                                            </div>
                                                            <div class="d-flex flex-column fw-bold">
                                                                <a class="text-dark line-height-normal mb-1" href="#!">{{ $admin->firstname }} {{ $admin->lastname }}</a>
                                                                <div class="small text-muted line-height-normal">{{ $admin->role ?? 'Admin' }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-6">
                                    <div class="row">
                                        <div class="col-xl-6 col-xxl-12">
                                            <!-- Team members / people dashboard card example-->
                                            <div class="card mb-4">
                                                <div class="card-header">Client Admins</div>
                                                <div class="card-body">
                                                    <!-- Item 1-->
                                                    @foreach($activeClientadmins as $clientadmin)
                                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                                        <div class="d-flex align-items-center flex-shrink-0 me-3">
                                                            <div class="avatar avatar-xl me-3 bg-gray-200">
                                                                @if($clientadmin->photo)
                                                                <img class="avatar-img img-fluid" src="{{ asset('assets/users/clientadmin/' . $clientadmin->photo) }}" alt="" />
                                                                @else
                                                                <img class="avatar-img img-fluid" src="{{ asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="" />
                                                                @endif
                                                            </div>
                                                            <div class="d-flex flex-column fw-bold">
                                                                <a class="text-dark line-height-normal mb-1" href="#!">{{ $clientadmin->firstname }} {{ $clientadmin->lastname }}</a>
                                                                <div class="small text-muted line-height-normal">{{ $clientadmin->role ?? 'Client Admin' }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            @include('admin.partials.admin_footer')
            <script>
                // Inject server-provided monthly labels and data for immediate chart rendering
                window.WatsonsWorkforceArea = {
                    labels: {!! json_encode($watsonLabels ?? []) !!},
                    data: {!! json_encode($watsonData ?? []) !!}
                };

                // Inject server-provided TimeOff bar chart data for immediate rendering
                window.TimeOffBar = {
                    labels: {!! json_encode($timeOffLabels ?? []) !!},
                    data: {!! json_encode($timeOffData ?? []) !!}
                };
            </script>
        </div>
    </div>
</body>

</html>