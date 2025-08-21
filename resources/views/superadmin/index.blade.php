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
                            <div class="card my-5 shadow-lg border-0">
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
                                    <!-- Login form-->
                                    <div id="loginFormWrapper">
                                        <form action="{{ route('superadmin_login_submit')}}" method="post">
                                            @csrf
                                            <!-- Form Group (email address)-->
                                            <div class="form-floating mb-2 position-relative">
                                                <input class="form-control form-control-solid pe-5" type="text" name="email" id="emailExample" placeholder="Email Address" required="" value="{{ old('email') }}">
                                                <label for="emailExample" class="text-gray-600 small">Username</label>
                                                <span class="position-absolute top-50 end-0 translate-middle-y me-3">
                                                    <i data-feather="user"></i>
                                                </span>
                                                @error('email')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <!-- Form Group (password)-->
                                            <div class="form-floating mb-2 position-relative">
                                                <input class="form-control form-control-solid pe-5" type="password" name="password" id="passwordExample" placeholder="Password" required="">
                                                <span class="position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;" onclick="togglePasswordVisibility()">
                                                    <i id="togglePasswordIcon" data-feather="eye"></i>
                                                </span>
                                                <script>
                                                    function togglePasswordVisibility() {
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
                                                    }
                                                </script>
                                                <label for="passwordExample" class="text-gray-600 small">Password</label>
                                                @error('password')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <!-- Form Group (forgot password link)-->
                                            <div class="mb-2">
                                                <a class="small" href="{{ route('superadmin_forget_password')}}">Forgot your password?</a>
                                            </div>
                                            <!-- Session Alerts -->
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
                                            <!-- Form Group (login box)-->
                                            <div class="d-flex align-items-center justify-content-between mb-0">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="checkRememberPassword" type="checkbox" name="remember" />
                                                    <label class="form-check-label" for="checkRememberPassword">Remember password</label>
                                                </div>
                                                <button class="btn btn-primary d-inline-flex align-items-center" type="submit">
                                                    <i data-feather="log-in" class="me-1"></i> Login
                                                </button>
                                            </div>
                                        </form>
                                    </div>
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
    <script>
        feather.replace();
    </script>
</body>

</html>