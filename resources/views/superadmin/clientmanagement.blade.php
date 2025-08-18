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
                                        <i data-feather="globe" style="width: 30px; height: 30px;"></i>
                                    </div>
                                    Client Management
                                </h1>
                                <div class="page-header-subtitle animate__animated animate__zoomIn text-muted mt-2">Manage Client Information</div>
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
                    <div class="col-xl-6 mb-4">
                        <!-- Dashboard example card 1-->
                        <a class="card lift h-100 shadow-lg border-0 position-relative" href="{{ route('superadmin_clients') }}" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                            <div class="card-body d-flex justify-content-center flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                        <i class="feather text-primary mb-1 animate__animated animate__zoomIn" data-feather="globe" style="width: 64px; height: 64px;"></i>
                                        <span class="badge bg-primary ms-2 position-absolute top-0 end-0 mt-2 me-2" style="font-size: 1.2rem; padding: 0.6em 1em;">{{ \App\Models\Client::where('isactive', 1)->count() }}</span>
                                        <h3 class="fw-bold animate__animated animate__zoomIn">Clients</h3>
                                        <div class="position-relative d-inline-block w-100">
                                            <div class="text-muted small mt-1 animate__animated animate__fadeInUp">View All Clients</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-6 mb-4">
                        <!-- Dashboard example card 2-->
                        <a class="card lift h-100 shadow-lg border-0 position-relative" href="{{ route('superadmin_branches') }}" onmouseover="highlightCard(this)" onmouseout="resetCard(this)">
                            <div class="card-body d-flex justify-content-center flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                        <i class="feather text-secondary mb-1 animate__animated animate__zoomIn" data-feather="list" style="width: 64px; height: 64px;"></i>
                                        <span class="badge bg-secondary ms-2 position-absolute top-0 end-0 mt-2 me-2" style="font-size: 1.2rem; padding: 0.6em 1em;">{{ \App\Models\Branches::where('isactive', 1)->count() }}</span>
                                        <h3 class="fw-bold animate__animated animate__zoomIn">Branches</h3>
                                        <div class="position-relative d-inline-block w-100">
                                            <div class="text-muted small mt-1 animate__animated animate__fadeInUp">View All Branches</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
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
        </main>
        @include('superadmin.footer')
        </body>

</html>