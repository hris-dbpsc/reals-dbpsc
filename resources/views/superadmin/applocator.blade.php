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
                                        <i data-feather="map-pin" style="width: 30px; height: 30px;"></i>
                                    </div>
                                    Locator
                                </h1>
                                <div class="page-header-subtitle animate__animated animate__fadeInUp text-muted mt-2">A Geographic Information System Application</div>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="{{ route('superadmin_apps') }}">
                                    <i class="me-1" data-feather="arrow-left"></i>
                                    Back to Applications
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-xl px-4 mt-n10">
                <div class="row">
                    <div class="col-xl-4 mb-4">
                        <!-- Dashboard example card 1-->
                        <a class="card lift h-100 shadow-lg border-0 position-relative" href="{{ route('superadmin_applocatorclient') }}" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                            <div class="card-body d-flex justify-content-center flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                        <i class="feather text-primary mb-1 animate__animated animate__zoomIn" data-feather="globe" style="width: 64px; height: 64px;"></i>
                                        <h3 class="fw-bold animate__animated animate__zoomIn">Client Locator</h3>
                                        <div class="position-relative d-inline-block w-100">
                                            <div class="text-muted small mt-1 animate__animated animate__fadeInUp">Locate all Clients</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-4 mb-4">
                        <!-- Dashboard example card 2-->
                        <a class="card lift h-100 shadow-lg border-0 position-relative" href="{{ route('superadmin_applocatorbranch') }}" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                            <div class="card-body d-flex justify-content-center flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                        <i class="feather text-secondary mb-1 animate__animated animate__zoomIn"  data-feather="list" style="width: 64px; height: 64px;"></i>
                                        <h3 class="fw-bold animate__animated animate__zoomIn">Branch Locator</h3>
                                        <div class="position-relative d-inline-block w-100">
                                            <div class="text-muted small mt-1 animate__animated animate__fadeInUp">Locate all Branches</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-4 mb-4">
                        <!-- Dashboard example card 3-->
                        <a class="card lift h-100" href="{{ route('superadmin_applocatoremployee') }}" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                            <div class="card-body d-flex justify-content-center flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                         <i class="feather text-success mb-1 animate__animated animate__zoomIn" data-feather="users" style="width: 64px; height: 64px;"></i>
                                        <h3 class="fw-bold animate__animated animate__zoomIn">Employee Locator</h3>
                                        <div class="position-relative d-inline-block w-100">
                                            <div class="text-muted small mt-1 animate__animated animate__fadeInUp">Locate all Employees</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
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
            </div>
        </main>
        @include('superadmin.footer')
        </body>

</html>