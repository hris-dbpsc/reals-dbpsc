    <!DOCTYPE html>
    <html lang="en">
    @include('superadmin.header')

    <body class="nav-fixed">
        @include('superadmin.topnav')
        <div id="layoutSidenav">
            @include('superadmin.sidenav')
            <div id="layoutSidenav_content">
                <main>
                    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-2">
                        <div class="container-fluid px-4">
                            <div class="page-header-content">
                                <div class="row align-items-center justify-content-between pt-3">
                                    <div class="col-auto mb-3">
                                        <h1 class="page-header-title text-body d-flex align-items-center">
                                            <a href="{{ route('superadmin_usermanagement') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                                <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                            </a>
                                            <div class="page-header-icon"><i data-feather="link-2" style="width:25px; height:25px;"></i></div>
                                            User Access and Permissions
                                        </h1>
                                    </div>
                                    <div class="col-12 col-xl-auto mb-3">
                                        <div class="d-flex flex-column flex-xl-row align-items-stretch align-items-xl-center justify-content-xl-end text-center text-xl-start">
                                            <a class="btn btn-outline-success mb-2 mb-xl-0 me-0 me-xl-2" href="#" data-bs-toggle="modal" data-bs-target="#importCsvModal">
                                                <i class="me-1" data-feather="upload"></i>button
                                            </a>
                                        </div>    
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
                        <div class="card">    <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ROLE</th>
                                            <th>NAME</th>
                                            <th>EMAIL</th>
                                            <th>PERMISSION</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th>ROLE</th>
                                            <th>NAME</th>
                                            <th>EMAIL</th>
                                            <th>PERMISSION</th>
                                            <th>PERMISSION</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                @include('superadmin.footer')
            </div>
    </body>

    </html>