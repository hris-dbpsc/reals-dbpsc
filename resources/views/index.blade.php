<?php
// Redirect authenticated users to their dashboard before rendering the login page.
// This prevents rendering the login form when there is an existing session for any guard.
try {
    if (auth()->guard('superadmin')->check()) { redirect()->route('superadmin_dashboard')->send(); }
    if (auth()->guard('admin')->check()) { redirect()->route('admin_dashboard')->send(); }
    if (auth()->guard('clientadmin')->check()) { redirect()->route('clientadmin_dashboard')->send(); }
    if (auth()->guard('user')->check()) { redirect()->route('user_dashboard')->send(); }
} catch (\Throwable $e) {
    // If session/redirect fails for any reason, continue to render the page.
}
?>

<!DOCTYPE html>
<html lang="en">
@include('home.header')

<body class="bg-primary min-vh-100 d-flex flex-column">
    <div class="flex-grow-1 d-flex flex-column justify-content-center">
        <main class="flex-grow-1 d-flex align-items-center justify-content-center w-100">
            <div class="container-xl px-4 d-flex align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="row justify-content-center w-100">
                    <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11">
                        <div class="card my-5 shadow-lg border-0 mx-auto w-100 position-relative">
                            <div class="card-body p-4 text-center">
                                <img src="{{ asset('assets/img/favicon.png') }}" alt="DBPSC Logo" class="mb-2" style="width: 70px; height: 70px; object-fit: cover;">
                                <div class="h1 fw-bold mb-1 text-primary" style="letter-spacing: 2px;">
                                    <span>REALS - DBPSC</span>
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem; margin-bottom: 0.5rem;">
                                    Real-time Employee Assignment and Locator System
                                </div>
                                <div class="mb-1">
                                    <span class="badge bg-primary bg-opacity-10 text-primary fw-normal d-inline-flex align-items-center" style="font-size: 0.85rem; padding: 0.3em 0.6em;">
                                        <i data-feather="shield" style="width: 0.9em; height: 0.9em; margin-right: 0.3em; vertical-align: middle;"></i>
                                        <span>Secured Access</span>
                                    </span>
                                </div>
                            </div>
                            <hr class="my-0" />
                            <div class="card-body p-4">
                                <form action="{{ route('login_multiauth') }}" method="post" id="loginForm">
                                    @csrf
                                    <div class="form-floating mb-2 position-relative">
                                        <input class="form-control form-control-solid pe-5" type="text" name="username" placeholder="Username">
                                        <label for="username" class="text-gray-600 small">Username</label>
                                        <span class="position-absolute top-50 end-0 translate-middle-y me-3">
                                            <i data-feather="user"></i>
                                        </span>
                                    </div>
                                    <div class="form-floating mb-4 position-relative">
                                        <input class="form-control form-control-solid pe-5" type="password" name="password" id="passwordExample" placeholder="Password" required>
                                        <span class="position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;" id="togglePasswordBtn">
                                            <i id="togglePasswordIcon" data-feather="eye"></i>
                                        </span>
                                        <label for="passwordExample" class="text-gray-600 small">Password</label>
                                    </div>
                                    @if (session('error'))
                                    <div class="alert alert-danger py-2">
                                        {{ session('error')}}
                                    </div>
                                    @endif
                                    @if (session('success'))
                                    <div class="alert alert-success py-2">
                                        {{ session('success')}}
                                    </div>
                                    @endif
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" id="checkRememberPassword" type="checkbox" name="remember" />
                                            <label class="form-check-label" for="checkRememberPassword">Remember password</label>
                                        </div>
                                        <a class="small ms-2" href="{{ route('forget_password') }}">Forgot your password?</a>
                                    </div>
                                    <div class="d-flex justify-content-center mb-3">
                                        <button class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center shadow-lg" type="submit" style="font-weight: 600; font-size: 1.1rem;">
                                            <i data-feather="log-in" class="me-1"></i> Log in
                                        </button>
                                    </div>

                                </form>
                            </div>
                            <hr class="my-0" />
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <div id="layoutAuthentication_footer" class="position-fixed bottom-0 start-0 w-100">
            @include('home.footer')
        </div>
    </div>
    <script>
        feather.replace();
        // Password toggle logic
        document.getElementById('togglePasswordBtn').onclick = function() {
            const passwordInput = document.getElementById('passwordExample');
            const icon = document.getElementById('togglePasswordIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.setAttribute('data-feather', 'eye-off');
            } else {
                passwordInput.type = 'password';
                icon.setAttribute('data-feather', 'eye');
            }
            feather.replace();
        };
    </script>
</body>

</html>