<!DOCTYPE html>
<html lang="en">
@include('admin.partials.admin_header')

<body class="nav-fixed">
    @include('admin.partials.admin_topnav')
    <div id="layoutSidenav">
        @include('admin.partials.admin_sidenav')
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
                                        <div class="page-header-icon"><i data-feather="users" style="width:25px; height:25px;"></i></div>
                                        Client List
                                    </h1>
                                </div>
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
                                        <th>CLIENT</th>
                                        <th>TYPE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>PHOTO</th>
                                        <th>CLIENT</th>
                                        <th>CLIENT</th>
                                        <th>TYPE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($activeClients as $client)
                                    <tr>
                                        <td>
                                            <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; margin: auto;">
                                                <img src="{{ $client->clientphoto ? asset('assets/clients/' . $client->clientphoto) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Photo" width="40" height="40" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                                <span style="position: absolute; bottom: 2px; right: 2px; width: 10px; height: 10px; background: #28a745; border: 2px solid #fff; border-radius: 50%; display: block;"></span>
                                            </div>
                                        </td>
                                        <td>{{ $client->clientshortname }}</td>
                                        <td>{{ $client->clientname }}</td>
                                        <td>
                                            @if($client->clienttype === 'Government')
                                            <span class="text-danger">Government</span>
                                            @else
                                            <span class="text-primary">Private</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Client Actions">
                                                <a class="btn btn-xs d-inline-flex align-items-center" href="{{ route('admin_viewclients', $client->id) }}">
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
                                    Show Inactive Clients
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- Inactive Clients Table (hidden by default) -->
                    <div id="inactiveClientsTable" style="display:none;">
                        <div class="card">
                            <div class="card-header text-body">INACTIVE CLIENTS</div>
                            <div class="card-body">
                                <table id="inactiveDatatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>PHOTO</th>
                                            <th>CLIENT</th>
                                            <th>CLIENT</th>
                                            <th>TYPE</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>PHOTO</th>
                                            <th>CLIENT</th>
                                            <th>CLIENT</th>
                                            <th>TYPE</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($inactiveClients as $client)
                                        <tr>
                                            <td>
                                                <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; margin: auto;">
                                                    <img src="{{ $client->clientphoto ? asset('assets/clients/' . $client->clientphoto) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Photo" width="40" height="40" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                                    <span style="position: absolute; bottom: 2px; right: 2px; width: 10px; height: 10px; background:rgb(251, 0, 0); border: 2px solid #fff; border-radius: 50%; display: block;"></span>
                                                </div>
                                            </td>
                                            <td>{{ $client->clientshortname }}</td>
                                            <td>{{ $client->clientname }}</td>
                                            <td>
                                                @if($client->clienttype === 'Government')
                                                <span class="text-danger">Government</span>
                                                @else
                                                <span class="text-primary">Private</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Client Actions">
                                                    <a class="btn btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_viewclients', $client->id) }}">
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
                                        // Initialize datatable for inactive clients
                                        if (document.getElementById('inactiveDatatablesSimple')) {
                                            new simpleDatatables.DataTable(document.getElementById('inactiveDatatablesSimple'));
                                        }
                                    });
                                </script>
                            </div>
                            <script>
                                document.getElementById('showInactiveBtn').addEventListener('click', function() {
                                    var table = document.getElementById('inactiveClientsTable');
                                    if (table.style.display === 'none') {
                                        table.style.display = 'block';
                                        this.innerHTML = '<i data-feather="eye-off" class="me-1"></i> Hide Inactive Clients';
                                        feather.replace();
                                    } else {
                                        table.style.display = 'none';
                                        this.innerHTML = '<i data-feather="eye" class="me-1"></i> Show Inactive Clients';
                                        feather.replace();
                                    }
                                });
                            </script>
                        </div>
                    </div>
            </main>
            @include('admin.partials.admin_footer')
        </div>
    </div>
</body>

</html>