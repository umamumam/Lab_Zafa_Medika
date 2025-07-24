<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('loginku/fonts/icomoon/style.css') }}">

    <link rel="stylesheet" href="{{ asset('loginku/css/owl.carousel.min.css') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('loginku/css/bootstrap.min.css') }}">

    <!-- Style -->
    <link rel="stylesheet" href="{{ asset('loginku/css/style.css') }}">

    <title>Lab Zafa Medika</title>
</head>

<body>


    <div class="d-lg-flex half">
        <div class="bg order-1 order-md-2" style="background-image: url('{{ asset('loginku/images/bg1.png') }}');">
        </div>
        <div class="contents order-2 order-md-1">

            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-7">
                        <h3>Login to <br><strong>Laboratorium Klinik Zafa Medika</strong></h3>
                        <p class="mb-4">Jl. STM, Kel. Komet. Kec. Banjarbaru Utara, Kota Banjarbaru, Kalimantan Selatan, 70714</p>
                        <br>
                        <form action="{{ route('login') }}" method="post">
                            @csrf
                            <div class="form-group first">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" placeholder="your-username" id="username"
                                    name="username" value="{{ old('username') }}" required autofocus>
                                @error('username')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group last mb-3">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" placeholder="Your Password" id="password"
                                    name="password" required autocomplete="current-password">
                                @error('password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="d-flex mb-5 align-items-center">
                                <label class="control control--checkbox mb-0">
                                    <span class="caption">Remember me</span>
                                    <input type="checkbox" name="remember" id="remember_me" />
                                    <div class="control__indicator"></div>
                                </label>
                                <span class="ml-auto">
                                    @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="forgot-pass">Forgot Password</a>
                                    @endif
                                </span>
                            </div>

                            <input type="submit" value="Log In" class="btn btn-block btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>



    <script src="{{ asset('loginku/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('loginku/js/popper.min.js') }}"></script>
    <script src="{{ asset('loginku/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('loginku/js/main.js') }}"></script>
</body>

</html>
