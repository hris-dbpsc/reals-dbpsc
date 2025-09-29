<!DOCTYPE html>
<html lang="en">

@include('user.partials.user_header')

<body class="nav-fixed">
    @include('user.partials.user_topnav')
    <div id="layoutSidenav">
        @include('user.partials.user_sidenav')
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header bg-gray-200 pb-10">
                    <div class="container-fluid px-4">
                        <div class="page-header-content pt-4">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mt-4">
                                    <h1 class="page-header-title">
                                        <div class="page-header-icon"><i data-feather="user"></i></div>
                                        {{ Auth::guard('user')->user()->firstname }} {{ Auth::guard('user')->user()->lastname }}
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

                        <div class="col-lg-6 col-xl-6 mb-4">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Apps</div>
                                            <div class="text-lg fw-bold">4</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="grid"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="{{ route('user_apps') }}">View Applications</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-6 mb-4">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Pending Requests</div>
                                            <div class="text-lg fw-bold">{{ $timeOffPending }}</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="message-circle"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="{{ route('user_alltimeoff', ['status' => 'pending']) }}">View Requests</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-lg-6 mb-4">
                            <!-- Area chart example-->
                            <div class="card mb-4">
                                <div class="card-header">APPS</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-3 mb-2">
                                            <!-- Dashboard example card 1-->
                                            <a class="card lift h-100" href="{{ route('user_timeoff') }}">
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
                                        <div class="col-xl-3 mb-2">
                                            <!-- Dashboard example card 1-->
                                            <a class="card lift h-100" href="{{ route('user_timelog') }}">
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

                                        <div class="col-xl-3 mb-2">
                                            <!-- Dashboard example card 1-->
                                            <a class="card lift h-100" href="{{ route('user_payslip') }}">
                                                <div class="card-body d-flex justify-content-center flex-column">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="me-3">
                                                            <i class="feather-xl text-danger" data-feather="book"></i>
                                                            <h5>Payslip</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-xl-3 mb-2">
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
                                    </div>
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
                                    <a class="stretched-link" href="{{ route('user_timeoff') }}">
                                        <div class="text-xs d-flex align-items-center justify-content-between">
                                            View More Data
                                            <i class="fas fa-long-arrow-alt-right"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>


                    <!-- Example DataTable for Dashboard Demo-->


                </div>
            </main>
            @include('user.partials.user_footer')

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