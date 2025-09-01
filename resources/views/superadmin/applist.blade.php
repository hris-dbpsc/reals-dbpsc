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
                                            <a href="{{ route('superadmin_dashboard') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                                <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                            </a>
                                            <div class="page-header-icon"><i data-feather="list" style="width:25px; height:25px;"></i></div>
                                            Apps List
                                        </h1>
                                    </div>
                                    <div class="col-12 col-xl-auto mb-3">
                                        <div class="d-flex flex-column flex-xl-row align-items-stretch align-items-xl-center justify-content-xl-end text-center text-xl-start">
                                            <a class="btn btn-outline-primary mb-2 mb-xl-0 me-0 me-xl-2" href="{{ route('superadmin_addapplication') }}">
                                                <i class="me-1" data-feather="plus"></i>Add Application
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
                                            <th>APP NAME</th>
                                            <th>APP LABEL</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>APP NAME</th>
                                            <th>APP LABEL</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach($applications as $application)
                                        <tr>
                                            <td>{{ $application->id }}</td>
                                            <td>{{ $application->appname }}</td>
                                            <td>{{ $application->applabel }}</td>
                                            <td>
                                                <a class="btn btn-xs d-inline-flex align-items-center" href="{{ route('superadmin_editapplication', $application->id) }}">
                                                    <i data-feather="edit" class="me-1" style="width: 2em; height: 2em;"></i>
                                                </a>
                                                <button type="button" class="btn btn-xs d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $application->id }}">
                                                    <i data-feather="trash-2" style="width: 2em; height: 2em;"></i>
                                                </button>
                                                <div class="modal fade" id="deleteModal{{ $application->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $application->id }}" aria-hidden="true" style="z-index: 1080;">
                                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel{{ $application->id }}">Confirm Deactivation</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                Are you sure you want to deactivate this application?
                                                            </div>
                                                            <div class="modal-footer justify-content-center">
                                                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                                    <i data-feather="x" class="me-1"></i>
                                                                    Cancel
                                                                </button>
                                                                <form action="{{ route('superadmin_softdeleteapplication', $application->id) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="btn btn-outline-primary">
                                                                        <i data-feather="trash-2" class="me-1"></i>
                                                                        Deactivate
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
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