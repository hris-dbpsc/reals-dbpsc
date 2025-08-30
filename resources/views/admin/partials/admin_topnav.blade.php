<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" id="sidenavAccordion">
    <!-- Sidenav Toggle Button-->
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle"><i data-feather="menu" style="width:25px; height:25px;"></i></button>
    <!-- Navbar Brand-->
    <!-- * * Tip * * You can use text or an image for your navbar brand.-->
    <!-- * * * * * * When using an image, we recommend the SVG format.-->
    <!-- * * * * * * Dimensions: Maximum height: 32px, maximum width: 240px-->
    <a class="navbar-brand pe-3 ps-4 ps-lg-2" href="{{ route('admin_dashboard') }}">REALS - DBPSC</a>
    <!-- Navbar Items-->
    <ul class="navbar-nav align-items-center ms-auto">
        <!-- User Dropdown-->
        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="img-fluid" src="{{ Auth::guard('admin')->user()->photo ? asset('assets/users/admin/' . Auth::guard('admin')->user()->photo) : asset('assets/assets/img/demo/user-placeholder.svg') }}" />
            </a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow dropdown-menu-animation" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" src="{{ Auth::guard('admin')->user()->photo ? asset('assets/users/admin/' . Auth::guard('admin')->user()->photo) : asset('assets/assets/img/demo/user-placeholder.svg') }}" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name">{{ Auth::guard('admin')->user()->firstname }} {{ Auth::guard('admin')->user()->lastname }}</div>
                        <div class="dropdown-user-details-email">{{ Auth::guard('admin')->user()->email }}</div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('admin_profile', Auth::guard('admin')->user()->id) }}">
                    <div class="dropdown-item-icon"><i data-feather="settings"></i></div>
                    Account
                </a>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>


<!-- Logout Confirmation Modal (move outside nav and dropdown) -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to logout?
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                    <i data-feather="x" class="me-1"></i>
                    Cancel
                </button>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">
                        <i data-feather="log-out" class="me-1"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Password Change Feedback Modal -->
@if(session('success2') || session('error') || $errors->any())
<div class="modal fade" id="passwordFeedbackModal" tabindex="-1" aria-labelledby="passwordFeedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordFeedbackModalLabel">
                    @if(session('success2'))
                    Success
                    @elseif(session('error') || $errors->any())
                    Error
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(session('success2'))
                <div class="alert alert-success mb-0">{{ session('success2') }}</div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger mb-0">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger mb-0">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var feedbackModal = new bootstrap.Modal(document.getElementById('passwordFeedbackModal'));
        feedbackModal.show();
    });
</script>
@endif