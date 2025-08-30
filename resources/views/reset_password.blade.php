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
                                    <i data-feather="refresh-cw" class="me-2 align-middle"></i> Reset password
                                </h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('reset_password_submit', [$token,$email]) }}" method="POST" id="resetPasswordForm">
                                    @csrf
                                    <div class="mb-3">
                                        <div class="form-floating mb-2 position-relative d-flex align-items-center" style="min-height: 58px;">
                                            <input class="form-control form-control-solid pe-5" type="password" name="password" id="password" placeholder="Password" required>
                                            <label for="password" class="text-gray-600 small">Password</label>
                                            <span class="position-absolute end-0 me-3 d-flex align-items-center" style="height: 100%;">
                                                <i data-feather="lock" class="feather-icon"></i>
                                            </span>
                                        </div>
                                        @error('password')
                                        <div class="alert alert-danger alert-sm py-1 px-2 small mt-2">{{ $message }}</div>
                                        @enderror
                                        <div class="form-floating mb-2 position-relative d-flex align-items-center" style="min-height: 58px;">
                                            <input class="form-control form-control-solid pe-5" type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                                            <label for="confirm_password" class="text-gray-600 small">Confirm Password</label>
                                            <span class="position-absolute end-0 me-3 d-flex align-items-center" style="height: 100%;">
                                                <i data-feather="lock" class="feather-icon"></i>
                                            </span>
                                        </div>
                                        @error('confirm_password')
                                        <div class="alert alert-danger alert-sm py-1 px-2 small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mt-2 mb-0">
                                        <div class="w-100 d-flex justify-content-center">
                                            <button id="openConfirmModalBtn" class="btn btn-primary d-flex align-items-center w-100 justify-content-center" type="button" data-bs-toggle="modal" data-bs-target="#confirmResetModal">
                                                <i data-feather="refresh-cw" class="me-2"></i> Reset password
                                            </button>
                                        </div>
                                        <!-- Confirmation Modal -->
                                        <div class="modal fade" id="confirmResetModal" tabindex="-1" aria-labelledby="confirmResetModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="confirmResetModalLabel">Confirm Reset</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        Are you sure you want to reset your password?
                                                    </div>
                                                    <div class="modal-footer justify-content-center">
                                                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                            <i data-feather="x" class="me-2"></i> Cancel
                                                        </button>
                                                        <button type="button" class="btn btn-outline-primary" id="confirmResetBtn">
                                                            <i data-feather="check" class="me-2"></i> Reset
                                                        </button>
                                                    </div>
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
        // Modal confirm button triggers form submit
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('resetPasswordForm');
            const confirmBtn = document.getElementById('confirmResetBtn');
            if (form && confirmBtn) {
                confirmBtn.addEventListener('click', function() {
                    form.submit();
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>