<!DOCTYPE html>
<html lang="en">
@include('home.header')

<body class="bg-primary min-vh-100 d-flex flex-column">
    <div class="flex-grow-1 d-flex flex-column justify-content-center">
        <main class="flex-grow-1 d-flex align-items-center justify-content-center w-100">
            <div class="container-xl px-4 d-flex align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="row justify-content-center w-100">
                    <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11">
                        <div class="card shadow-lg border-0 rounded-lg my-5">
                            <div class="card-header justify-content-center">
                                <h3 class="fw-light my-4 d-inline align-middle">
                                    <i data-feather="lock" class="me-2 align-middle"></i> Password Recovery
                                </h3>
                            </div>
                            <div class="card-body p-4 text-center">
                                <div class="small mb-2 text-muted">Enter your email address and we will send you a link to reset your password.</div>
                                <form id="forgetPasswordForm" action="{{ route('forget_password_submit') }}" method="POST">
                                    @csrf
                                    <div class="form-floating mb-2 position-relative">
                                        <input class="form-control form-control-solid pe-5" type="text" name="email" id="emailExample" placeholder="Email Address" required value="{{ old('email') }}">
                                        <label for="emailExample" class="text-gray-600 small">Email address</label>
                                        <span class="position-absolute top-50 end-0 translate-middle-y me-3">
                                            <i data-feather="mail"></i>
                                        </span>
                                    </div>
                                    @if(session('success'))
                                    <div class="alert alert-success alert-sm py-2 px-3 small">
                                        {{ session('success') }}
                                    </div>
                                    @endif
                                    @if(session('error'))
                                    <div class="alert alert-danger alert-sm py-2 px-3 small">
                                        {{ session('error') }}
                                    </div>
                                    @endif
                                    @error('email')
                                    <div class="alert alert-danger alert-sm py-2 px-3 small">{{ $message }}</div>
                                    @enderror
                                    <div class="d-flex align-items-center justify-content-between mt-2 mb-0">
                                        <div class="btn-group w-100 px-1">
                                            <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#confirmResetModal">
                                                <i data-feather="refresh-cw" class="me-2"></i> Reset password
                                            </button>
                                            <a class="btn btn-outline-primary" href="{{ route('index') }}">
                                                <i data-feather="log-in" class="me-2"></i> Return to Login
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Modal -->
                                    <div class="modal fade" id="confirmResetModal" tabindex="-1" aria-labelledby="confirmResetModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmResetModalLabel">Confirm Password Reset</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to reset your password? A link will be sent to your email address.
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                        <i data-feather="x" class="me-1"></i> Cancel
                                                    </button>
                                                    <button type="submit" class="btn btn-outline-primary" id="confirmResetBtn">
                                                        <span id="confirmResetBtnText">
                                                            <i data-feather="check" class="me-1" id="confirmResetBtnIcon"></i>
                                                            <span id="confirmResetBtnLabel">Confirm</span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <div id="layoutAuthentication_footer" class="mt-auto w-100">
            @include('home.footer')
        </div>
    </div>
    <script>
        feather.replace();
        // Modal confirm logic and spinner overlay
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('forgetPasswordForm');
            const confirmBtn = document.getElementById('confirmResetBtn');
            const allButtons = form ? form.querySelectorAll('button') : [];
            if (form && confirmBtn) {
                form.addEventListener('submit', function(e) {
                    // Disable all buttons in the form
                    allButtons.forEach(btn => btn.disabled = true);
                    // Create and show a fullscreen spinner overlay
                    if (!document.getElementById('fullscreenSpinnerOverlay')) {
                        const overlay = document.createElement('div');
                        overlay.id = 'fullscreenSpinnerOverlay';
                        overlay.style.position = 'fixed';
                        overlay.style.top = 0;
                        overlay.style.left = 0;
                        overlay.style.width = '100vw';
                        overlay.style.height = '100vh';
                        overlay.style.background = 'rgba(255,255,255,0.7)';
                        overlay.style.display = 'flex';
                        overlay.style.alignItems = 'center';
                        overlay.style.justifyContent = 'center';
                        overlay.style.zIndex = 9999;
                        const spinner = document.createElement('div');
                        spinner.className = 'spinner-border text-primary';
                        spinner.style.width = '3rem';
                        spinner.style.height = '3rem';
                        spinner.setAttribute('role', 'status');
                        spinner.setAttribute('aria-hidden', 'true');
                        overlay.appendChild(spinner);
                        document.body.appendChild(overlay);
                    }
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>