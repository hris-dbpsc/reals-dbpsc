<!-- Sidebar for desktop -->
<nav class="sidebar d-none d-md-flex flex-column align-items-start py-4 h-100" style="min-width: 200px; max-width: 300px; min-height: 100vh; font-size: 1.25rem;">
    <a class="nav-link active" id="nav-home" style="padding: 1.25rem 2rem;"><i data-feather="home" style="width: 32px; height: 32px;"></i> <span class="d-none d-lg-inline">Home</span></a>
    <a class="nav-link" id="nav-apps" style="padding: 1.25rem 2rem;"><i data-feather="grid" style="width: 32px; height: 32px;"></i> <span class="d-none d-lg-inline">Apps</span></a>
    <a class="nav-link" id="nav-profile" style="padding: 1.25rem 2rem;">
        <span class="avatar-holder me-2" style="display:inline-block;width:40px;height:40px;vertical-align:middle;">
            <img src="{{ asset('assets/users/superadmin/superadmin_1_1754891349.jpg') }}" alt="Avatar" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
        </span>
        <span class="d-none d-lg-inline">Profile</span>
    </a>
    <div class="flex-grow-1"></div> <!-- Spacer to push content to top -->
    <!-- Dark Mode Toggle Button (Desktop only) -->
    <div class="w-100 px-3 py-2">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="darkModeSwitchDesktop">
            <label class="form-check-label small" for="darkModeSwitchDesktop" id="darkModeSwitchLabelDesktop" style="font-size:0.9em;">Dark Mode</label>
        </div>
    </div>
    <div class="sidenav-footer w-100 px-3 py-2 border-top d-none d-md-block bg-light" style="font-family:'Segoe UI',Arial,sans-serif;font-size:0.95rem;">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle" style="font-size:0.9em;color:#6c757d;">
                Logged in as:
                <span class="badge bg-light text-body">Superadmin</span>
            </div>
            <div class="sidenav-footer-title" style="font-weight:600;font-size:1em;">John Doe</div>
            <div class="text-muted" style="font-size:0.8em;font-style:italic;">johndoe@example.com</div>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                    <i data-feather="log-out" style="width: 16px; height: 16px; vertical-align: middle;" class="me-1"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>
<!-- Bottom bar for mobile (no dark mode toggle, only navigation) -->
<nav class="bottom-bar d-md-none d-flex" style="height: 70px; font-size: 1.15rem;">
    <a class="nav-link active d-flex flex-column align-items-center justify-content-center flex-fill text-center" id="bottom-home" style="height: 100%;">
        <i data-feather="home" style="width: 32px; height: 32px;"></i>
    </a>
    <a class="nav-link d-flex flex-column align-items-center justify-content-center flex-fill text-center" id="bottom-apps" style="height: 100%;">
        <i data-feather="grid" style="width: 32px; height: 32px;"></i>
    </a>
    <a class="nav-link d-flex flex-column align-items-center justify-content-center flex-fill text-center" id="bottom-profile" style="height: 100%;">
        <span class="avatar-holder" style="display:inline-block;width:40px;height:40px;">
            <img src="{{ asset('assets/users/superadmin/superadmin_1_1754891349.jpg') }}" alt="Avatar" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
        </span>
    </a>
</nav>
<!-- No inline JS: all logic is now in the footer partial. -->
