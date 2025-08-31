<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile-First Dashboard</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- Feather Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous"></script>
    <style>
        /* Sidebar styles */
        @media (min-width: 992px) {
            .sidebar {
                min-width: 220px;
                max-width: 260px;
                height: 100vh;
                position: fixed;
                left: 0; top: 0;
                background: var(--bs-body-bg);
                border-right: 1px solid var(--bs-border-color-translucent);
                display: flex;
                flex-direction: column;
                z-index: 1030;
                transition: all 0.3s;
            }
            .main-content {
                margin-left: 220px;
            }
        }
        @media (max-width: 991.98px) {
            .sidebar {
                display: none !important;
            }
            .bottom-nav {
                display: flex !important;
            }
            .main-content {
                margin-left: 0;
                padding-bottom: 70px;
            }
        }
        .sidebar {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.5rem 0.5rem 0.5rem 0.5rem;
        }
        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1.1rem;
            color: var(--bs-body-color);
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
            transition: background 0.2s, color 0.2s;
        }
        .sidebar .nav-link.active, .bottom-nav .nav-link.active {
            background: var(--bs-primary-bg-subtle);
            color: var(--bs-primary) !important;
        }
        .sidebar .user-info {
            border-top: 1px solid var(--bs-border-color-translucent);
            padding-top: 1rem;
            margin-top: 1rem;
        }
        /* Bottom nav styles */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: var(--bs-body-bg);
            border-top: 1px solid var(--bs-border-color-translucent);
            z-index: 1030;
            justify-content: space-around;
            padding: 0.5rem 0;
        }
        .bottom-nav .nav-link {
            color: var(--bs-body-color);
            font-size: 1.1rem;
            flex-direction: column;
            gap: 0.2rem;
            border-radius: 0.375rem;
            transition: background 0.2s, color 0.2s;
        }
        .bottom-nav .nav-link.active {
            color: var(--bs-primary) !important;
            background: var(--bs-primary-bg-subtle);
        }
        .bottom-nav .dropdown-menu {
            min-width: 8rem;
        }
        .darkmode-switch {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar (Desktop) -->
    <nav class="sidebar d-none d-lg-flex">
        <div>
            <!-- Navigation -->
            <a href="#" class="nav-link active" id="nav-home"><i data-feather="home"></i> <span>Home</span></a>
            <a href="#" class="nav-link" id="nav-apps"><i data-feather="grid"></i> <span>Apps</span></a>
            <a href="#" class="nav-link" id="nav-profile"><i data-feather="user"></i> <span>Profile</span></a>
        </div>
        <div>
            <div class="darkmode-switch">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="darkModeSwitchDesktop">
                    <label class="form-check-label" for="darkModeSwitchDesktop">Dark Mode</label>
                </div>
            </div>
            <div class="user-info mt-3">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Avatar" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                    <div>
                        <div class="fw-semibold">John Doe</div>
                        <div class="text-muted small">johndoe@example.com</div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-2" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i data-feather="log-out" class="me-1"></i> Logout
                </button>
            </div>
        </div>
    </nav>
    <!-- Bottom Navigation (Mobile) -->
    <nav class="bottom-nav d-lg-none d-flex">
        <a href="#" class="nav-link active flex-fill text-center" id="bottom-home"><i data-feather="home"></i><span class="small">Home</span></a>
        <a href="#" class="nav-link flex-fill text-center" id="bottom-apps"><i data-feather="grid"></i><span class="small">Apps</span></a>
        <div class="nav-item dropdown flex-fill text-center">
            <a href="#" class="nav-link dropdown-toggle" id="bottom-profile" data-bs-toggle="dropdown" aria-expanded="false">
                <i data-feather="user"></i><span class="small">Profile</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bottom-profile">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><button class="dropdown-item d-flex align-items-center gap-2" type="button" data-bs-toggle="modal" data-bs-target="#logoutModal"><i data-feather="log-out"></i> Logout</button></li>
            </ul>
        </div>
    </nav>
    <!-- Main Content -->
    <main class="main-content container-fluid py-4">
        <h1 class="mb-4">Dashboard</h1>
        <div id="page-home">
            <div class="card mb-3"><div class="card-body">Welcome to the Home page!</div></div>
        </div>
        <div id="page-apps" style="display:none;">
            <div class="card mb-3"><div class="card-body">Apps content goes here.</div></div>
        </div>
        <div id="page-profile" style="display:none;">
            <div class="card mb-3"><div class="card-body">Profile content goes here.</div></div>
        </div>
    </main>
    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Are you sure you want to logout?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="logoutConfirmBtn">Logout</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        feather.replace();
        // Navigation logic
        function showPage(page) {
            document.querySelectorAll('.main-content > div[id^="page-"]').forEach(el => el.style.display = 'none');
            var pageEl = document.getElementById('page-' + page);
            if (pageEl) pageEl.style.display = '';
            document.querySelectorAll('.sidebar .nav-link, .bottom-nav .nav-link').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('#nav-' + page + ', #bottom-' + page).forEach(el => el.classList.add('active'));
        }
        document.getElementById('nav-home').onclick = document.getElementById('bottom-home').onclick = function() { showPage('home'); };
        document.getElementById('nav-apps').onclick = document.getElementById('bottom-apps').onclick = function() { showPage('apps'); };
        document.getElementById('nav-profile').onclick = function() { showPage('profile'); };
        // Profile dropdown in mobile
        document.getElementById('bottom-profile').addEventListener('show.bs.dropdown', function() { showPage('profile'); });
        // Dark mode logic
        function setDarkMode(isDark) {
            document.documentElement.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
            localStorage.setItem('bsTheme', isDark ? 'dark' : 'light');
        }
        function syncDarkModeSwitch() {
            var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            var switchDesktop = document.getElementById('darkModeSwitchDesktop');
            if (switchDesktop) switchDesktop.checked = isDark;
        }
        document.addEventListener('DOMContentLoaded', function() {
            // Set theme from localStorage
            var theme = localStorage.getItem('bsTheme') || 'light';
            setDarkMode(theme === 'dark');
            syncDarkModeSwitch();
            var switchDesktop = document.getElementById('darkModeSwitchDesktop');
            if (switchDesktop) {
                switchDesktop.addEventListener('change', function() {
                    setDarkMode(switchDesktop.checked);
                });
            }
            // Logout modal logic
            var logoutConfirmBtn = document.getElementById('logoutConfirmBtn');
            if (logoutConfirmBtn) {
                logoutConfirmBtn.addEventListener('click', function() {
                    // Replace with actual logout logic
                    window.location.href = '/logout';
                });
            }
        });
    </script>
</body>
</html>
