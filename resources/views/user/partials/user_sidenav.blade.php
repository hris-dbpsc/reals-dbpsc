<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <!-- Sidenav Menu Heading (Core)-->
                <a class="nav-link" href="{{ route('user_dashboard') }}">
                </a>
                <!-- Sidenav Accordion (Dashboard)-->
                <a class="nav-link" href="{{ route('user_dashboard') }}">
                    <div class="nav-link-icon"><i data-feather="activity"></i></div>
                    Dashboard
                </a>

                <!-- Applications-->
                <a class="nav-link" href="{{ route('user_apps') }}">
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
                        <a class="nav-link" href="{{ route('user_timeoff') }}">
                            <div class="nav-link-icon"><i data-feather="calendar"></i></div>
                            TimeOff
                        </a>
                        <a class="nav-link" href="{{ route('user_timelog') }}">
                            <div class="nav-link-icon"><i data-feather="clock"></i></div>
                            TimeLog
                        </a>
                        <a class="nav-link" href="{{ route('user_payslip') }}">
                            <div class="nav-link-icon"><i data-feather="book"></i></div>
                            Payslip
                        </a>
                        <a class="nav-link" href="#!">
                            <div class="nav-link-icon"><i data-feather="message-square"></i></div>
                            WorkChat
                        </a>
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
                        {{ ucfirst(Auth::guard('user')->user()->role ?? 'user') }}
                    </span>
                </div>
                <div class="sidenav-footer-title">
                    {{ Auth::guard('user')->user()->firstname }} {{ Auth::guard('user')->user()->lastname }}

                </div>
                <div class="text-muted" style="font-size: 0.8em; font-style: italic;">
                    {{ Auth::guard('user')->user()->email }}
                </div>
                <div class="text-muted" style="font-size: 0.8em;">
                    <!-- Device: {{ php_uname('n') }}<br> -->
                </div>
            </div>
        </div>
    </nav>
</div>