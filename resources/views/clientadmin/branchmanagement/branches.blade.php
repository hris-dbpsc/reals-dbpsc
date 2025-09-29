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
                                        <a href="{{ route('admin_clientmanagement') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        <div class="page-header-icon"><i data-feather="list" style="width:25px; height:25px;"></i></div>
                                        Branch List
                                    </h1>
                                </div>
                                
                            </div>
                        </div>
                </header>
                <!-- Main page content-->
                <div class="container-fluid px-4">
                    <div class="card">

                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success alert-sm py-1 px-2">
                                {{ session('success') }}
                            </div>
                            @endif
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>PHOTO</th>
                                        <th>CLIENT</th>
                                        <th>BRANCH</th>
                                        <th>REGION</th>
                                        <th>TYPE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>PHOTO</th>
                                        <th>CLIENT</th>
                                        <th>BRANCH</th>
                                        <th>REGION</th>
                                        <th>TYPE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($clientactiveBranches as $branch)
                                    <tr>
                                        <td>
                                            @if($branch->client && $branch->client->clientphoto)
                                            <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 45px; height: 45px; margin: auto;">
                                                <img src="{{ asset('assets/clients/' . $branch->client->clientphoto) }}" alt="Photo" width="35" height="35" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                                <span style="position: absolute; bottom: 4px; right: 4px; width: 10px; height: 10px; background: #28a745; border: 2px solid #fff; border-radius: 50%; display: block; z-index: 2;"></span>
                                            </div>
                                            @endif
                                        </td>
                                        <td>{{ $branch->clientname }}</td>
                                        <td>{{ $branch->branchname }}</td>
                                        <td>{{ $branch->branchregion }}</td>
                                        <td>
                                            @if($branch->client && $branch->client->clienttype === 'Government')
                                            <span class="text-danger">Government</span>
                                            @else
                                            <span class="text-primary">Private</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Client Actions">
                                                <a class="btn btn-xs d-inline-flex align-items-center" href="{{ route('clientadmin_viewbranch', $branch->id) }}">
                                                    <i data-feather="eye" class="me-1" style="width: 2em; height: 2em;"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Centered Button to Show Inactive Clients -->
                            <div class="text-center my-4">
                                <button class="btn btn-light text-body" id="showInactiveBtn">
                                    <i data-feather="eye" class="me-1"></i>
                                    Show Inactive Branches
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- Inactive Clients Table (hidden by default) -->
                    <div id="inactiveBranchesTable" style="display:none;">
                        <div class="card">
                            <div class="card-header text-body">INACTIVE BRANCHES</div>
                            <div class="card-body">
                                <table id="inactiveDatatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>PHOTO</th>
                                            <th>CLIENT</th>
                                            <th>BRANCH</th>
                                            <th>REGION</th>
                                            <th>TYPE</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>PHOTO</th>
                                            <th>CLIENT</th>
                                            <th>BRANCH</th>
                                            <th>REGION</th>
                                            <th>TYPE</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($clientinactiveBranches as $branch)
                                        <tr>
                                            <td>
                                                <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 45px; height: 45px; margin: auto;">
                                                    <img src="{{ asset('assets/clients/' . $branch->client->clientphoto) }}" alt="Photo" width="35" height="35" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                                    <span style="position: absolute; bottom: 4px; right: 4px; width: 10px; height: 10px; background:rgb(255, 0, 0); border: 2px solid #fff; border-radius: 50%; display: block; z-index: 2;"></span>
                                                </div>
                                            </td>
                                            <td>{{ $branch->clientname }}</td>
                                            <td>{{ $branch->branchname }}</td>
                                            <td>{{ $branch->branchregion }}</td>
                                            <td>
                                                @if($branch->client->clienttype === 'Government')
                                                <span class="text-danger">Government</span>
                                                @else
                                                <span class="text-primary">Private</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Branch Actions">
                                                    <a class="btn btn-xs d-inline-flex align-items-center" href="{{ route('clientadmin_viewbranch', $branch->id) }}">
                                                        <i data-feather="eye" class="me-1" style="width: 2em; height: 2em;"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        // Initialize datatable for inactive branches
                                        if (document.getElementById('inactiveDatatablesSimple')) {
                                            new simpleDatatables.DataTable(document.getElementById('inactiveDatatablesSimple'));
                                        }
                                    });
                                </script>
                            </div>
                            <script>
                                document.getElementById('showInactiveBtn').addEventListener('click', function() {
                                    var table = document.getElementById('inactiveBranchesTable');
                                    if (table.style.display === 'none') {
                                        table.style.display = 'block';
                                        this.innerHTML = '<i data-feather="eye-off" class="me-1"></i> Hide Inactive Branches';
                                        feather.replace();
                                    } else {
                                        table.style.display = 'none';
                                        this.innerHTML = '<i data-feather="eye" class="me-1"></i> Show Inactive Branches';
                                        feather.replace();
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
        </div>
        </main>
    </div>
    @include('clientadmin.partials.client_footer')
</body>

</html>