<!-- Apps Page Section -->
<div id="page-apps" style="display:none;">
    <!-- Apps Header -->
    <div class="d-flex align-items-center mb-3 gap-2">
        <i data-feather="grid" style="width:32px;height:32px;"></i>
        <h1 class="mb-0">Apps</h1>
    </div>
    <!-- Apps Card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <p class="mb-3"></p>
            <div class="row g-3">
                <!-- ...existing app cards... -->
                <!-- <div class="col-12 col-md-4 col-xl-3">? -->
                <!-- The Div Settings on Top will make the App Card 1 per row in mobile view -->
                <div class="col-6 col-md-4 col-xl-3">
                    <a class="card lift h-100 shadow-sm border-1 position-relative text-decoration-none" href="#">
                        <div class="card-body d-flex flex-column align-items-center text-center py-4">
                            <i class="feather text-cyan mb-2" data-feather="user" style="width:48px;height:48px;"></i>
                            <div class="fw-bold text-body">People</div>
                            <span class="badge bg-primary rounded-pill px-2 py-1 mt-1 small">Coming Soon</span>
                            <div class="text-muted small mt-1">Employee Info System</div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-xl-3">
                    <a class="card lift h-100 shadow-sm border-1 position-relative text-decoration-none" href="#">
                        <div class="card-body d-flex flex-column align-items-center text-center py-4">
                            <i class="feather text-pink mb-2" data-feather="users" style="width:48px;height:48px;"></i>
                            <div class="fw-bold text-body">WorkForce</div>
                            <span class="badge bg-primary rounded-pill px-2 py-1 mt-1 small">Coming Soon</span>
                            <div class="text-muted small mt-1">Workforce Management</div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-xl-3">
                    <a class="card lift h-100 shadow-sm border-1 position-relative text-decoration-none" href="#">
                        <div class="card-body d-flex flex-column align-items-center text-center py-4">
                            <i class="feather text-orange mb-2" data-feather="calendar" style="width:48px;height:48px;"></i>
                            <div class="fw-bold text-body">TimeOff</div>
                            <span class="badge bg-primary rounded-pill px-2 py-1 mt-1 small">Coming Soon</span>
                            <div class="text-muted small mt-1">Leave Management</div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-xl-3">
                    <a class="card lift h-100 shadow-sm border-1 position-relative text-decoration-none" href="">
                        <div class="card-body d-flex flex-column align-items-center text-center py-4">
                            <i class="feather text-green mb-2" data-feather="map-pin" style="width:48px;height:48px;"></i>
                            <div class="fw-bold text-body">Locator</div>
                            <span class="badge bg-success rounded-pill px-2 py-1 mt-1 small">Development Ongoing</span>
                            <div class="text-muted small mt-1">Geographical Info System</div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-xl-3">
                    <a class="card lift h-100 shadow-sm border-1 position-relative text-decoration-none" href="#">
                        <div class="card-body d-flex flex-column align-items-center text-center py-4">
                            <i class="feather text-blue mb-2" data-feather="message-square" style="width:48px;height:48px;"></i>
                            <div class="fw-bold text-body">WorkChat</div>
                            <span class="badge bg-warning rounded-pill px-2 py-1 mt-1 small">Recommended</span>
                            <div class="text-muted small mt-1">Messaging Platform</div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-xl-3">
                    <a class="card lift h-100 shadow-sm border-1 position-relative text-decoration-none" href="#">
                        <div class="card-body d-flex flex-column align-items-center text-center py-4">
                            <i class="feather text-blue mb-2" data-feather="clock" style="width:48px;height:48px;"></i>
                            <div class="fw-bold text-body">TimeLog</div>
                            <span class="badge bg-warning rounded-pill px-2 py-1 mt-1 small">Recommended</span>
                            <div class="text-muted small mt-1">Attendance Monitoring System</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// Maintain selected view/tab on refresh using localStorage
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        // Restore last selected page
        var lastPage = localStorage.getItem('admin_last_page') || 'home';
        showPage(lastPage);
        // Save page on nav click
        ['home','apps','profile'].forEach(function(page) {
            var nav = document.getElementById('nav-' + page);
            var bottom = document.getElementById('bottom-' + page);
            if(nav) nav.addEventListener('click', function(){ localStorage.setItem('admin_last_page', page); });
            if(bottom) bottom.addEventListener('click', function(){ localStorage.setItem('admin_last_page', page); });
        });
    });
})();
</script>
