<!DOCTYPE html>
<html lang="en">
@include('home.header')
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container-xl px-4">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <!-- Basic forgot password form-->
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header justify-content-center"><h3 class="fw-light my-4">Password Recovery</h3></div>
                                    <div class="card-body">
                                        <div class="small mb-3 text-muted">Enter your email address and we will send you a link to reset your password.</div>
                                        <!-- Forgot password form-->
                                        <form>
                                        @if($errors->any())
                                        @foreach ($errors->all() as $$errors)
                                            {{ $errors }}
                                        @endforeach
                                    @endif

                                    @if(session('success'))
                                        {{ session('success') }}
                                    @endif

                                    @if(session('error'))
                                        {{ session('error') }}
                                    @endif  

                                    <form action="{{ route('superadmin_forget_password_submit')}}" method="post">
                                        <!-- Form Group (email address)-->
                                         @csrf
                                        <div class="mb-3">
                                            <label class="text-gray-600 small" for="emailExample">Email Address</label>
                                            <input class="form-control form-control-solid" type="text" name="email">
                                        </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="{{ route('superadmin.index') }}">Return to login</a>
                                                <a class="btn btn-primary" type="submit">Reset Password</a>
                                            </div>
                                    </form>
                                    </div>
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
        <script src="js/scripts.js"></script>
    </body>
</html>
