<!DOCTYPE html>
<html lang="en">
@include('superadmin.partials.header')

<body class="nav-fixed">
    @include('superadmin.partials.topnav')
    <div id="layoutSidenav">
        @include('superadmin.partials.sidenav')
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-2">
                    <div class="container-fluid px-4">
                        <div class="page-header-content">
                            <div class="row align-items-center justify-content-between pt-3">
                                <div class="col-auto mb-3">
                                    <h1 class="page-header-title text-body d-flex align-items-center">
                                        <a href="{{ route('superadmin_dashboard') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        <div class="page-header-icon"><i data-feather="link-2" style="width:25px; height:25px;"></i></div>
                                        Apps Access
                                    </h1>
                                </div>
                                <div class="col-12 col-xl-auto mb-3">
                                    <div class="d-flex flex-column flex-xl-row align-items-stretch align-items-xl-center justify-content-xl-end text-center text-xl-start">
                                        <a class="btn btn-outline-primary mb-2 mb-xl-0 me-0 me-xl-2" href="{{ route('superadmin_addappaccess') }}">
                                            <i class="me-1" data-feather="plus"></i>Add App Access
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                </header>
                <!-- Main page content-->
                <div class="container-fluid px-4">
                    @if (session('success'))
                    <div class="alert alert-success alert-sm py-1 px-2">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>PHOTO</th>
                                        <th>CLIENT</th>
                                        <th>TYPE</th>
                                        <th>APPS ACCESS</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>PHOTO</th>
                                        <th>CLIENT</th>
                                        <th>TYPE</th>
                                        <th>APPS ACCESS</th>
                                        <th>ACTION</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($clients as $client)
                                    @php $access = $accesses[$client->id] ?? null; @endphp
                                    <tr>
                                        <td>{{ $client->id }}</td>
                                        <td>
                                            <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 50px; height: 50px; margin: auto;">
                                                <img src="{{ $client->clientphoto ? asset('assets/clients/' . $client->clientphoto) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Photo" width="48" height="48" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                                <span style="position: absolute; bottom: 2px; right: 2px; width: 12px; height: 12px; background: #28a745; border: 2px solid #fff; border-radius: 50%; display: block;"></span>
                                            </div>
                                        </td>
                                        <td>{{ $client->clientname }}</td>
                                        <td>{{ $client->clienttype }}</td>
                                        <td>
                                            @if($access)
                                            @foreach($allApplications as $application)
                                            @php $accessField = 'app_' . $application->id; @endphp
                                            @if(!empty($access->$accessField))
                                            <span class="badge bg-primary mb-1" style="font-size: .8rem; padding: 0.5em 1em;">{{ $application->appname }}</span>
                                            @endif
                                            @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if($access)
                                            <a class="btn btn-xs d-inline-flex align-items-center"
                                                href="{{ route('superadmin_editappaccess', ['id' => $access->id]) }}">
                                                <i data-feather="edit" class="me-1" style="width: 2em; height: 2em;"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            @include('superadmin.partials.footer')
        </div>
</body>

</html>