    <!DOCTYPE html>
    <html lang="en">
    @include('clientadmin.partials.client_header')

    <body class="nav-fixed">
        @include('clientadmin.partials.client_topnav')
        <div id="layoutSidenav">
            @include('clientadmin.partials.client_sidenav')
            <div id="layoutSidenav_content">
                <main>
                    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-2">
                        <div class="container-fluid px-4">
                            <div class="page-header-content">
                                <div class="row align-items-center justify-content-between pt-3">
                                    <div class="col-auto mb-3">
                                        <h1 class="page-header-title text-body d-flex align-items-center">
                                            <a href="{{ route('admin_useremployee') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                                <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                            </a>
                                            <div class="page-header-icon"><i data-feather="user" style="width:25px; height:25px;"></i></div>
                                            Employees
                                        </h1>
                                    </div>
                                   
                                </div>
                            </div>
                    </header>
                    <!-- Main page content-->
                    <div class="container-fluid px-4">
                        @switch(true)
                        @case(session('success'))
                        <div class="alert alert-success alert-sm py-1 px-2">
                            {{ session('success') }}
                        </div>
                        @break

                        @case(session('import_error'))
                        <div class="alert alert-danger alert-sm py-1 px-2">
                            {{ session('import_error') }}
                        </div>
                        @break
                        @endswitch
                        <div class="card">
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>PHOTO</th>
                                            <th>CLIENT</th>
                                            <th>BRANCH</th>
                                            <th>EMPLOYEE NUMBER</th>
                                            <th>POSITION</th>
                                            <th>LAST NAME</th>
                                            <th>FIRST NAME</th>
                                            <th>MIDDLE NAME</th>
                                            <th>STATUS</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>PHOTO</th>
                                            <th>CLIENT</th>
                                            <th>BRANCH</th>
                                            <th>EMPLOYEE NUMBER</th>
                                            <th>POSITION</th>
                                            <th>LAST NAME</th>
                                            <th>FIRST NAME</th>
                                            <th>MIDDLE NAME</th>
                                            <th>STATUS</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <img class="img-fluid rounded-circle" style="width: 48px; height: 48px; object-fit: cover;"
                                                        src="{{ file_exists(public_path('assets/users/users/' . $user->employeenumber . '.jpg')) ? asset('assets/users/users/' . $user->employeenumber . '.jpg') : asset('assets/assets/img/demo/user-placeholder.svg') }}"
                                                        alt="{{ $user->employeenumber ? $user->lastname . ', ' . $user->firstname : 'User photo placeholder' }}" />
                                                </div>
                                            </td>
                                            <td>{{ $user->client->clientname }}</td>
                                            <td>{{ $user->branchname }}</td>
                                            <td>{{ $user->employeenumber }}</td>
                                            <td>{{ $user->position }}</td>
                                            <td>{{ $user->lastname }}</td>
                                            <td>{{ $user->firstname }}</td>
                                            <td>{{ $user->middlename }}</td>
                                            <td>
                                                <span class="{{ $user->isactive == 1 ? 'text-success' : 'text-danger' }}">
                                                    {{ $user->isactive == 1 ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td></td>
                                                
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                @include('clientadmin.partials.client_footer')
            </div>
    </body>

    </html>