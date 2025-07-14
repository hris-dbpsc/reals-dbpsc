<!DOCTYPE html>
<html lang="en">
@include('home.header')

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11">
                            <!--  login form-->
                            <div class="card my-5">
                                <div class="card-body p-5 text-center">
                                    <!--  login links-->
                                    <img src="{{ asset('assets/img/favicon.png') }}" alt="DBPSC Logo" class="mb-3" style="width: 75px;">
                                    <div class="h3 fw-light mb-3">DBPSC - REALS</div>
                                    <div class="small text-muted">Admin Login Page</div>
                                </div>
                                <hr class="my-0" />
                                <div class="card-body p-5">
                                    <!-- Login form-->
                                    <form>
                                        <!-- Form Group (email address)-->
                                        <div class="mb-3">
                                            <label class="text-gray-600 small" for="emailExample">Employee Number</label>
                                            <input class="form-control form-control-solid" type="text" placeholder="" aria-label="Email Address" aria-describedby="emailExample" />
                                        </div>
                                        <!-- Form Group (password)-->
                                        <div class="mb-3">
                                            <label class="text-gray-600 small" for="passwordExample">Password</label>
                                            <input class="form-control form-control-solid" type="password" placeholder="" aria-label="Password" aria-describedby="passwordExample" />
                                        </div>
                                        <!-- Form Group (forgot password link)-->
                                        <div class="mb-3"><a class="small" href="auth-password-social.html">Forgot your password?</a></div>
                                        <!-- Form Group (login box)-->
                                        <div class="d-flex align-items-center justify-content-between mb-0">
                                            <div class="form-check">
                                                <input class="form-check-input" id="checkRememberPassword" type="checkbox" value="" />
                                                <label class="form-check-label" for="checkRememberPassword">Remember password</label>
                                            </div>
                                            <a class="btn btn-primary" href="dashboard-1.html">Login</a>
                                        </div>
                                    </form>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
</body>

</html>