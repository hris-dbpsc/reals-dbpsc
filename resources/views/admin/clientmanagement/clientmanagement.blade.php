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
                                <div class="col-auto mt-2">
                                    <h2 class="page-header-title text-body d-flex align-items-center" style="font-size:1.5rem;">
                                        <span class="page-header-icon me-2 text-body d-flex justify-content-center align-items-center" style="width:28px; height:28px;">
                                            <i data-feather="users" style="width:30px; height:30px;"></i>
                                        </span>
                                        Client Management
                                    </h2>
                                    <div class="page-header-subtitle text-body mt-2" style="font-size: 0.95rem;">Manage Clients Information</div>
                                </div>
                                <div class="col-auto mt-4">
                                    <a href="{{ route('admin_dashboard') }}" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:50px; height:50px; padding:0;">
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
                        <div class="col-xl-6 mb-2">
                            <!-- Dashboard example card 1-->
                            <a class="card lift h-100 shadow-lg border-0 position-relative" href="{{ route('admin_clients') }}">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-primary mb-1" data-feather="users" style="width: 50px; height: 50px;"></i>
                                            <span class="badge bg-light text-primary ms-2 position-absolute top-0 end-0 mt-2 me-2" style="font-size: 1.2rem; padding: 0.6em 1em;">{{ \App\Models\Client::where('isactive', 1)->count() }}</span>
                                            <h3 class="fw-bold text-body">Clients</h3>
                                            <div class="position-relative d-inline-block w-100">
                                                <div class="text-muted small mt-1">View All Clients</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-6 mb-2">
                            <!-- Dashboard example card 2-->
                            <a class="card lift h-100 shadow-lg border-0 position-relative" href="{{ route('admin_branches') }}">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-success mb-1" data-feather="list" style="width: 50px; height: 50px;"></i>
                                            <span class="badge bg-light text-secondary ms-2 position-absolute top-0 end-0 mt-2 me-2" style="font-size: 1.2rem; padding: 0.6em 1em;">{{ \App\Models\Branches::where('isactive', 1)->count() }}</span>
                                            <h3 class="fw-bold text-body">Branches</h3>
                                            <div class="position-relative d-inline-block w-100">
                                                <div class="text-muted small mt-1">View All Branches</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="container-fluid px-4">
                   
                    <div class="card mb-2">
                        <div class="card-header">
                            <h5 class="mb-0 text-danger">Government Clients: {{ $govCount }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($govClients as $client)
                                <div class="col-xl-3 mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-lg">
                                            @if($client->clientphoto)
                                            <img class="avatar-img img-fluid" src="{{ asset('assets/clients/' . $client->clientphoto) }}" alt="{{ $client->clientshortname }}" />
                                            @else
                                            <img class="avatar-img img-fluid" src="{{ asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="{{ $client->clientshortname }}" />
                                            @endif
                                        </div>
                                        <div class="ms-3">
                                            <div class="fs-4 text-body fw-500">{{ $client->clientshortname }}</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card mb-2">
                        <div class="card-header">
                            <h5 class="mb-0 text-primary">Private Clients: {{ $privateCount }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($privateClients as $client)
                                <div class="col-xl-3 mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-lg">
                                            @if($client->clientphoto)
                                            <img class="avatar-img img-fluid" src="{{ asset('assets/clients/' . $client->clientphoto) }}" alt="{{ $client->clientshortname }}" />
                                            @else
                                            <img class="avatar-img img-fluid" src="{{ asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="{{ $client->clientshortname }}" />
                                            @endif
                                        </div>
                                        <div class="ms-3">
                                            <div class="fs-4 text-body fw-500">{{ $client->clientshortname }}</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            @include('admin.partials.admin_footer')
</body>

</html>