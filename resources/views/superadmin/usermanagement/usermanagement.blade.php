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
                                            <i data-feather="user" style="width:30px; height:30px;"></i>
                                        </span>
                                        User Management
                                    </h2>
                                    <div class="page-header-subtitle text-body mt-2" style="font-size: 0.95rem;">Manage Users Information</div>
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
                            <!-- Dashboard example card 1-->
                            <a class="card lift lift-sm h-100 shadow-sm border-0 position-relative" href="{{ route('superadmin_usersuperadmin') }}">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-primary mb-1" data-feather="user" style="width: 50px; height: 50px;"></i>
                                            <span class="badge bg-light text-primary ms-2 position-absolute top-0 end-0 mt-2 me-2" style="font-size: 1.2rem; padding: 0.6em 1em;">{{ $superadminCount }}</span>
                                            <h3 class="fw-bold text-body">Superadmin</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 mb-2">
                            <!-- Dashboard example card 2-->
                            <a class="card lift lift-sm h-100 shadow-sm border-0 position-relative" href="{{ route('superadmin_useradmin') }}">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-secondary mb-1" data-feather="user" style="width: 50px; height: 50px;"></i>
                                            <span class="badge bg-light text-secondary ms-2 position-absolute top-0 end-0 mt-2 me-2" style="font-size: 1.2rem; padding: 0.6em 1em;">{{ $adminCount }}</span>
                                            <h3 class="fw-bold text-body">Admin</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 mb-2">
                            <!-- Dashboard example card 1-->
                            <a class="card lift lift-sm h-100 shadow-sm border-0 position-relative" href="{{ route('superadmin_userclientadmin') }}">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-success mb-1" data-feather="user" style="width: 50px; height: 50px;"></i>
                                            <span class="badge bg-light text-success ms-2 position-absolute top-0 end-0 mt-2 me-2" style="font-size: 1.2rem; padding: 0.6em 1em;">{{ $clientadminCount }}</span>
                                            <h3 class="fw-bold text-body">Client Admin</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 mb-2">
                            <!-- Dashboard example card 2-->
                            <a class="card lift lift-sm h-100 shadow-sm border-0 position-relative" href="{{ route('superadmin_useremployee') }}">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-info mb-1" data-feather="users" style="width: 50px; height: 50px;"></i>
                                            <span class="badge bg-light text-info ms-2 position-absolute top-0 end-0 mt-2 me-2" style="font-size: 1.2rem; padding: 0.6em 1em;">{{ $employeeCount }}</span>
                                            <h3 class="fw-bold text-body">Employee</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Main page content-->
                <div class="container-fluid px-4">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="me-3">
                                    <div class="h1 text-body">System Administrators:</div>
                                </div>
                                <div class="text-body">{{ $totalActive }} Active Administrators</div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="fs-5 text-body">Superadmins:</div>
                            <div class="row">
                                @foreach($activeSuperadmins as $superadmin)
                                <div class="col-lg-3 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-lg">
                                            @if($superadmin->photo)
                                            <img class="avatar-img img-fluid" src="{{ asset('assets/users/superadmin/' . $superadmin->photo) }}" alt="{{ $superadmin->firstname }}" />
                                            @else
                                            <img class="avatar-img img-fluid" src="{{ asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="{{ $superadmin->firstname }}" />
                                            @endif
                                        </div>
                                        <div class="ms-3">
                                            <div class="fs-4 text-body fw-500">{{ $superadmin->firstname }}</div>
                                            <span class="badge bg-light text-primary small">superadmin</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="fs-5 text-body">Admins:</div>
                            <div class="row">
                                @foreach($activeAdmins as $admin)
                                <div class="col-lg-3 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-lg">
                                            @if($admin->photo)
                                            <img class="avatar-img img-fluid" src="{{ asset('assets/users/admin/' . $admin->photo) }}" alt="{{ $admin->firstname }}" />
                                            @else
                                            <img class="avatar-img img-fluid" src="{{ asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="{{ $admin->firstname }}" />
                                            @endif
                                        </div>
                                        <div class="ms-3">
                                            <div class="fs-4 text-body fw-500">{{ $admin->firstname }}</div>
                                            <span class="badge bg-light text-secondary small">{{ $admin->role }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="fs-5 text-body">Client Admins:</div>
                            <div class="row">
                                @foreach($activeClientadmins as $clientadmin)
                                <div class="col-lg-3 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-lg">
                                            @if($clientadmin->photo)
                                            <img class="avatar-img img-fluid" src="{{ asset('assets/users/clientadmin/' . $clientadmin->photo) }}" alt="{{ $clientadmin->firstname }}" />
                                            @else
                                            <img class="avatar-img img-fluid" src="{{ asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="{{ $clientadmin->firstname }}" />
                                            @endif
                                        </div>
                                        <div class="ms-3">
                                            <div class="fs-4 text-body fw-500">{{ $clientadmin->firstname }} {{ $clientadmin->lastname }}</div>
                                            <span class="badge bg-light text-success small">
                                                {{ $clientMap[$clientadmin->clientid] ?? 'Unknown' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            @include('superadmin.partials.footer')
</body>

</html>