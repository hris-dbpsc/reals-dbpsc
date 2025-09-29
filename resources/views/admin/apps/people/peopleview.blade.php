<!DOCTYPE html>
<html lang="en">
@include('admin.partials.admin_header')

<body class="nav-fixed">
    @include('admin.partials.admin_topnav')
    <div id="layoutSidenav">
        @include('admin.partials.admin_sidenav')
        <div id="layoutSidenav_content">
            <main>
                @if(isset($user))
                <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-2">
                    <div class="container-fluid px-4">
                        <div class="page-header-content">
                            <div class="row align-items-center justify-content-between pt-3">
                                <div class="col-auto mb-3">
                                    <h1 class="page-header-title text-body d-flex align-items-center">
                                        <a href="javascript:void(0);" onclick="window.close();" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center me-3" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="x" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        <div class="page-header-icon"><i data-feather="user" style="width:25px; height:25px;"></i></div>
                                        Employee Information
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <div class="container-fluid px-4 mt-2">
                    <div class="row">
                        <div class="col-xl-4">
                            <!-- Profile picture card-->
                            <div class="card mb-2 mb-xl-0">
                                <div class="card-header text-body">User Photo</div>
                                <div class="card-body text-center">
                                    <!-- Profile picture image-->
                                    <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 200px; height: 200px; margin: auto;">
                                        @php
                                        $userPhotoPath = isset($user) && $user->employeenumber ? 'assets/users/users/' . $user->employeenumber . '.jpg' : null;
                                        $userPhotoExists = $userPhotoPath && file_exists(public_path($userPhotoPath));
                                        @endphp
                                        <img src="{{ $userPhotoExists ? asset($userPhotoPath) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Photo" width="200" height="200" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                        <span style="position: absolute; bottom: 12px; right: 12px; width: 32px; height: 32px; {{ isset($user) && $user->isactive == 1 ? 'background:#28a745;' : 'background:#dc3545;' }} border: 2px solid #fff; border-radius: 50%; display: block;"></span>
                                    </div>
                                    <!-- Profile picture help block-->
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <!-- Account details card-->
                            <div class="card mb-2">
                                <div class="card-header text-body">User Details</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped table-sm align-middle mb-0">
                                            <tbody>
                                                <tr>
                                                    <th class="bg-light text-end" style="width: 180px;">Name</th>
                                                    <td>
                                                        <strong>{{ $user->lastname }}, {{ $user->firstname }} {{ $user->middlename }}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">Position</th>
                                                    <td>{{ $user->position }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">Position Category</th>
                                                    <td>{{ $user->positioncategory }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">Client</th>
                                                    <td>{{ $user->clientname }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">Branch</th>
                                                    <td>{{ $user->branchname }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">Department</th>
                                                    <td>{{ $user->departmentname }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">Date of Birth</th>
                                                    <td>
                                                        @if($user->dateofbirth)
                                                        {{ \Carbon\Carbon::parse($user->dateofbirth)->format('F d, Y') }}
                                                        <span class="badge bg-success ms-2">{{ \Carbon\Carbon::parse($user->dateofbirth)->age }} yrs old</span>
                                                        @else
                                                        <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">Civil Status</th>
                                                    <td>{{ $user->civilstatus }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">Contact</th>
                                                    <td>
                                                        @if($user->contact)
                                                        <a href="tel:{{ $user->contact }}" class="text-decoration-none">
                                                            <i data-feather="phone" class="me-1"></i>{{ $user->contact }}
                                                        </a>
                                                        @else
                                                        <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">Email</th>
                                                    <td>
                                                        @if($user->email)
                                                        <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                                            <i data-feather="mail" class="me-1"></i>{{ $user->email }}
                                                        </a>
                                                        @else
                                                        <span class="text-muted"></span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">Address</th>
                                                    <td>{{ $user->address }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">Region</th>
                                                    <td>{{ $user->region }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">Start Date</th>
                                                    <td>
                                                        @if($user->startdate)
                                                        {{ \Carbon\Carbon::parse($user->startdate)->format('F d, Y') }}
                                                        @else
                                                        <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">Hire Type</th>
                                                    <td>{{ $user->hiretype }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">Wage Type</th>
                                                    <td>{{ $user->wagetype }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">TIN</th>
                                                    <td>{{ $user->tin }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">SSS #</th>
                                                    <td>{{ $user->sss }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">PAGIBIG #</th>
                                                    <td>{{ $user->pagibig }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">PHILHEALTH #</th>
                                                    <td>{{ $user->philhealth }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-end">Status</th>
                                                    <td>
                                                        @if($user->isactive == 1)
                                                        <i data-feather="check-circle" class="text-success me-1"></i> Active
                                                        @else
                                                        <i data-feather="x-circle" class="text-danger me-1"></i> Inactive
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="container-fluid px-4 mt-2">
                    <div class="alert alert-danger">User not found.</div>
                </div>
                @endif
            </main>

            @include('admin.partials.admin_footer')
</body>

</html>