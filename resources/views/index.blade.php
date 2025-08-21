<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | REALS - DBPSC</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
    @include('home.header')
</head>
<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11">
                            <div class="card my-5 shadow">
                                <div class="card-body p-5 text-center">
                                    <img src="{{ asset('assets/img/favicon.png') }}" alt="DBPSC Logo" class="mb-3" style="width: 75px;">
                                    <div class="h1 fw-bolder mb-3">REALS - DBPSC</div>
                                </div>
                                <hr class="my-0" />
                                <div class="card-body p-5">
                                    <!-- Show validation errors -->
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error')}}
                                        </div>
                                    @endif

                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success')}}
                                        </div>
                                    @endif

                                    <form action="{{ route('login_submit')}}" method="post" autocomplete="on" aria-label="Login form">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="text-gray-600 small" for="email">Email Address</label>
                                            <input 
                                                class="form-control form-control-solid" 
                                                type="email" 
                                                name="email" 
                                                id="email" 
                                                value="{{ old('email') }}" 
                                                required 
                                                autofocus 
                                                autocomplete="email"
                                                aria-describedby="emailHelp"
                                            >
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-gray-600 small" for="password">Password</label>
                                            <input 
                                                class="form-control form-control-solid" 
                                                type="password" 
                                                name="password" 
                                                id="password" 
                                                required 
                                                autocomplete="current-password"
                                            >
                                        </div>
                                        <div class="mb-3 text-end">
                                            <a class="small" href="{{ route('superadmin_forget_password')}}">Forgot your password?</a>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="form-check">
                                                <input 
                                                    class="form-check-input" 
                                                    id="remember" 
                                                    type="checkbox" 
                                                    name="remember" 
                                                    {{ old('remember') ? 'checked' : '' }}
                                                />
                                                <label class="form-check-label" for="remember">Remember me</label>
                                            </div>
                                            <button class="btn btn-primary px-4" type="submit" aria-label="Login">Login</button>
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
</body>
</html>