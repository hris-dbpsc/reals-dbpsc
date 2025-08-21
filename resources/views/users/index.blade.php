<!DOCTYPE html>
<html lang="en">
@include('home.header')

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4 min-vh-100 d-flex align-items-center justify-content-center">
                    <div class="row justify-content-center w-100">
                        <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11">
                            <!-- Enhanced Header Section -->
                            <div class="card my-5 shadow-lg border-0" >
                                <div class="card-body p-4 text-center">
                                    <img src="{{ asset('assets/img/favicon.png') }}" alt="DBPSC Logo" class="mb-2" style="width: 70px; height: 70px; object-fit: cover;">
                                    <div class="h1 fw-bold mb-1 text-primary" style="letter-spacing: 2px;">
                                        <span>REALS | DBPSC</span>
                                    </div>
                                    <div class="text-muted" style="font-size: 0.75rem; margin-bottom: 0.5rem;">
                                        Real-time Employee Assignment and Locator System
                                    </div>
                                    <div class="mb-1">
                                        <span class="badge bg-primary bg-opacity-10 text-primary fw-normal d-inline-flex align-items-center" style="font-size: 0.85rem; padding: 0.3em 0.6em;">
                                            <i data-feather="shield" style="width: 0.9em; height: 0.9em; margin-right: 0.3em; vertical-align: middle;"></i>
                                            <span style="vertical-align: middle;">Secure Access</span>
                                        </span>
                                    </div>
                                </div>
                                <hr class="my-0" />
                                <div class="card-body p-4">
                                    <!-- Login form-->
                                    <!-- Add animation classes to the form container -->
                                    <div id="loginFormWrapper">
                                        <form>
                                            <!-- Form Group (email address)-->
                                            <div class="form-floating mb-2 position-relative">
                                                <input class="form-control form-control-solid pe-5" type="text" id="employeeNumber" placeholder="Employee Number" aria-label="Employee Number" />
                                                <label for="employeeNumber" class="text-gray-600 small">Username</label>
                                                <span class="position-absolute top-50 end-0 translate-middle-y me-3">
                                                    <i data-feather="user"></i>
                                                </span>
                                            </div>
                                            <!-- Form Group (password)-->
                                            <div class="form-floating mb-2 position-relative">
                                                <input class="form-control form-control-solid pe-5" type="password" id="passwordExample" placeholder="Password" aria-label="Password" />
                                                <label for="passwordExample" class="text-gray-600 small">Password</label>
                                                <span class="position-absolute top-50 end-0 translate-middle-y me-3">
                                                    <i data-feather="lock"></i>
                                                </span>
                                            </div>
                                            <!-- Form Group (forgot password link)-->
                                            <div class="mb-2"><a class="small" href="auth-password-social.html">Forgot your password?</a></div>
                                            <!-- Form Group (login box)-->
                                            <script src="https://unpkg.com/feather-icons"></script>
                                            <script>
                                                feather.replace();
                                            </script>
                                            <div class="d-flex align-items-center justify-content-between mb-0">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="checkRememberPassword" type="checkbox" value="" />
                                                    <label class="form-check-label" for="checkRememberPassword">Remember password</label>
                                                </div>
                                                <a class="btn btn-primary d-inline-flex align-items-center" href="dashboard-1.html">
                                                    <i data-feather="log-in" class="me-1"></i> Login
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- Animate.css CDN -->
                                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
                                </div>
                                <hr class="my-0" />
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            @include('home.footer')
        </div>
    </div>
</body>

</html>