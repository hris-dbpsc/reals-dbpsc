<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <!-- Sidenav Menu Heading (Core)-->
                <a class="nav-link" href="{{ route('admin_dashboard') }}">
                </a>
                <!-- Sidenav Accordion (Dashboard)-->
                <a class="nav-link" href="{{ route('admin_dashboard') }}">
                    <div class="nav-link-icon"><i data-feather="activity"></i></div>
                    Dashboard
                </a>
                <!-- Sidenav User Management -->
                <a class="nav-link" href="{{ route('admin_useremployee') }}">
                    <div class="sidenav-menu-heading">User Management</div>
                </a>
                <!-- Sidenav Accordion (Pages)-->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                    <div class="nav-link-icon"><i data-feather="user"></i></div>
                    View Users
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePages" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                        <!-- Nested Sidenav Accordion (Pages -> Account)-->
                        <a class="nav-link" href="{{ route('admin_useremployee') }}">
                            <div class="nav-link-icon"><i data-feather="user"></i></div>
                            Users
                        </a>
                    </nav>
                </div>
                <!-- Sidenav Client Management-->
                <a class="nav-link" href="{{ route('admin_clientmanagement') }}">
                    <div class="sidenav-menu-heading">Client Management</div>
                </a>
                <!-- Sidenav Accordion (Layout)-->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#viewclients" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="nav-link-icon"><i data-feather="users"></i></div>
                    View Clients
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="viewclients" data-bs-parent="#accordionSidenavPagesMenu">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin_clients') }}">
                            <div class="nav-link-icon"><i data-feather="users"></i></div>
                            Clients
                        </a>
                        <a class="nav-link" href="{{ route('admin_branches') }}">
                            <div class="nav-link-icon"><i data-feather="list"></i></div>
                            Branches
                        </a>
                    </nav>
                </div>
                <!-- Applications-->
                <a class="nav-link" href="{{ route('admin_apps') }}">
                    <div class="sidenav-menu-heading">Applications</div>
                </a>
                <!-- Sidenav Accordion (Layout)-->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#viewapps" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="nav-link-icon"><i data-feather="grid"></i></div>
                    View Apps
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="viewapps" data-bs-parent="#accordionSidenavPagesMenu">
                    <nav class="sidenav-menu-nested nav">
                        <!-- Sidenav Link (Charts)-->
                        @if(!empty($canAccessPeopleApp))
                        <a class="nav-link" href="{{ route('admin_people') }}">
                            <div class="nav-link-icon"><i data-feather="user"></i></div>
                            People
                        </a>
                        @endif
                        @if(!empty($canAccessWatsonsWorkforceApp))
                        <a class="nav-link" href="{{ route('admin_watsonsworkforce') }}">
                            <div class="nav-link-icon"><i data-feather="users"></i></div>
                            WorkForce
                        </a>
                        @endif
                        @if(!empty($canAccessTimeoffApp))
                        <a class="nav-link" href="{{ route('admin_timeoff') }}">
                            <div class="nav-link-icon"><i data-feather="calendar"></i></div>
                            TimeOff
                        </a>
                        @endif
                        @if(!empty($canAccessLocatorApp))
                        <a class="nav-link" href="{{ route('admin_locator') }}">
                            <div class="nav-link-icon"><i data-feather="map-pin"></i></div>
                            Locator
                        </a>
                        @endif
                        @if(!empty($canAccessWorkchatApp))
                        <a class="nav-link" href="#!">
                            <div class="nav-link-icon"><i data-feather="message-square"></i></div>
                            WorkChat
                        </a>
                        @endif
                        @if(!empty($canAccessTimelogApp))
                        <a class="nav-link" href="{{ route('admin_timelog') }}">
                            <div class="nav-link-icon"><i data-feather="clock"></i></div>
                            TimeLog
                        </a>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
        <!-- Sidenav Footer-->
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">
                    Logged in as:
                    <span class="badge bg-light text-body">
                        {{ ucfirst(Auth::guard('admin')->user()->role ?? 'Admin') }}
                    </span>
                </div>
                <div class="sidenav-footer-title">
                    {{ Auth::guard('admin')->user()->firstname }} {{ Auth::guard('admin')->user()->lastname }}

                </div>
                <div class="text-muted" style="font-size: 0.8em; font-style: italic;">
                    {{ Auth::guard('admin')->user()->email }}
                </div>
                <div class="text-muted" style="font-size: 0.8em;">
                    <!-- Device: {{ php_uname('n') }}<br> -->
                </div>
            </div>
        </div>
    </nav>
</div>