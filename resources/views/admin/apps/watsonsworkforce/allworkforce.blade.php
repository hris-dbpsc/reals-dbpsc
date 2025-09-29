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
                                <div class="col-auto mb-2">
                                    <h1 class="page-header-title text-body d-flex align-items-center">
                                        <a href="{{ route('admin_watsonsworkforce') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        All
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <!-- Admin All Request Container -->
                @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->role == 'admin')
                @include('admin.apps.watsonsworkforce.alladminworkforce')
                @endif
                <!-- Admin All Request Container -->

                <!-- Areacoordinator All Request Container -->
                @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->role == 'areacoordinator')
                @include('admin.apps.watsonsworkforce.allareacoordinatorworkforce')
                @endif
                <!-- Admin All Request Container -->
            </main>

        </div>
    </div>
    
    @include('admin.partials.admin_footer')
</body>

</html>