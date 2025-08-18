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
                                <h1 class="page-header-title animate__animated animate__fadeInDown d-flex align-items-center">
                                    <div class="page-header-icon me-2 bg-primary text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                        <i data-feather="grid" style="width: 30px; height: 30px;"></i>
                                    </div>
                                    Applications
                                </h1>
                                <p class="page-header-subtitle animate__animated animate__zoomIn text-muted mt-2">Explore and manage all available applications in one place.</p>
                            </div>
                            <div class="col-auto mt-4">
                                <a href="{{ route('superadmin_dashboard') }}" class="btn btn-sm btn-light text-primary shadow-sm">
                                    <i data-feather="arrow-left" class="me-1"></i> Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-xl px-4 mt-n10">
                <div class="row">
                    <div class="col-xl-3 mb-4">
                        <a class="card lift h-100 shadow-lg border-0 position-relative" href="#!" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                            <div class="card-body d-flex justify-content-center flex-column text-center">
                                <div class="d-flex align-items-center justify-content-center flex-column">
                                    <div class="me-3">
                                        <i class="feather text-cyan mb-1 animate__animated animate__zoomIn" data-feather="user" style="width: 64px; height: 64px;"></i>
                                        <h3 class="fw-bold animate__animated animate__fadeInUp">People</h3>
                                        <span class="badge bg-primary rounded-pill px-3 py-2 animate__animated animate__zoomIn">Coming Soon</span>
                                        <div class="text-muted small mt-1 animate__animated animate__fadeInUp">Employee Information System</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 mb-4">
                        <a class="card lift h-100 shadow-lg border-0 position-relative" href="#!" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                            <div class="card-body d-flex justify-content-center flex-column text-center">
                                <div class="d-flex align-items-center justify-content-center flex-column">
                                    <div class="me-3">
                                        <i class="feather text-pink mb-1 animate__animated animate__zoomIn" data-feather="users" style="width: 64px; height: 64px;"></i>
                                        <h3 class="fw-bold animate__animated animate__zoomIn">WorkForce</h3>
                                        <span class="badge bg-primary rounded-pill px-3 py-2 animate__animated animate__zoomIn">Coming Soon</span>
                                        <div class="text-muted small mt-1 animate__animated animate__fadeInUp">Workforce Management System</div>
                                    </div>
                                </div>
                            </div>
                            <div class="position-absolute top-0 end-0 p-2">
                                <i class="feather text-grey" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="top" title="Manage your workforce efficiently"></i>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 mb-4">
                        <a class="card lift h-100 shadow-lg border-0 position-relative" href="#!" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                            <div class="card-body d-flex justify-content-center flex-column text-center">
                                <div class="d-flex align-items-center justify-content-center flex-column">
                                    <div class="me-3">
                                        <i class="feather text-orange mb-1 animate__animated animate__zoomIn" data-feather="calendar" style="width: 64px; height: 64px;"></i>
                                        <h3 class="fw-bold animate__animated animate__zoomIn">TimeOff</h3>
                                        <span class="badge bg-primary rounded-pill px-3 py-2 animate__animated animate__zoomIn">Coming Soon</span>
                                        <div class="text-muted small mt-1 animate__animated animate__fadeInUp">Leave Management System</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 mb-4">
                        <a class="card lift h-100 shadow-lg border-0 position-relative" href="{{ route('superadmin_applocator') }}" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                            <div class="card-body d-flex justify-content-center flex-column text-center">
                                <div class="d-flex align-items-center justify-content-center flex-column">
                                    <div class="me-3">
                                        <i class="feather text-green mb-1 animate__animated animate__zoomIn" data-feather="map-pin" style="width: 64px; height: 64px;"></i>
                                        <h3 class="fw-bold animate__animated animate__zoomIn">Locator</h3>
                                        <span class="badge bg-success rounded-pill px-3 py-2 animate__animated animate__zoomIn">Development Ongoing</span>
                                        <div class="text-muted small mt-1 animate__animated animate__fadeInUp">Geographic Information System</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 mb-4">
                        <a class="card lift h-100 shadow-lg border-0 position-relative" href="#!" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                            <div class="card-body d-flex justify-content-center flex-column text-center">
                                <div class="d-flex align-items-center justify-content-center flex-column">
                                    <div class="me-3">
                                        <i class="feather text-blue mb-1 animate__animated animate__zoomIn" data-feather="message-square" style="width: 64px; height: 64px;"></i>
                                        <h3 class="fw-bold animate__animated animate__zoomIn">WorkChat</h3>
                                        <span class="badge bg-warning rounded-pill px-3 py-2 animate__animated animate__zoomIn">Recommended by ExeComm</span>
                                        <div class="text-muted small mt-1 animate__animated animate__fadeInUp">Real-time Messaging Platform</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 mb-4">
                        <a class="card lift h-100 shadow-lg border-0 position-relative" href="#!" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                            <div class="card-body d-flex justify-content-center flex-column text-center">
                                <div class="d-flex align-items-center justify-content-center flex-column">
                                    <div class="me-3">
                                        <i class="feather text-blue mb-1 animate__animated animate__zoomIn" data-feather="clock" style="width: 64px; height: 64px;"></i>
                                        <h3 class="fw-bold animate__animated animate__zoomIn">TimeLog</h3>
                                        <span class="badge bg-warning rounded-pill px-3 py-2 animate__animated animate__zoomIn">Recommended by ExeComm</span>
                                        <div class="text-muted small mt-1 animate__animated animate__fadeInUp">Attendance Management System</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <script>
                    function highlightCard(card) {
                        card.classList.add('shadow', 'bg-light', 'animate__animated', 'animate__pulse');
                    }

                    function resetCard(card) {
                        card.classList.remove('shadow', 'bg-light', 'animate__animated', 'animate__pulse');
                    }
                </script>
                
            </div>
            
        </main>
        @include('superadmin.footer')
        </body>

</html>