<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('vender/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vender/confirm/js/notify.js') }}"></script>
    <script src="{{ asset('vender/toastr/build/toastr.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('vender/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vender/confirm/css/notify.css') }}" rel="stylesheet">
    <link href="{{ asset('vender/fontawesome/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('vender/toastr/build/toastr.min.css') }}" rel="stylesheet">
    <title>@yield('title', 'bitinterview')</title>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a class="navbar-brand" href="{{ url('/') }}">Bitinterview</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            @if (Route::has('login'))
            <ul class="navbar-nav ml-auto">
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>

                @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                </li>
                @endif
            @endauth
            </ul>
        @endif
        </div>
    </nav>
    @yield('content')
    @if (session('msg'))
        @if (session('ok'))
            <script>toastr.success('{{ session("msg") }}')</script>
        @else
            <script>toastr.error('{{ session("msg") }}')</script>
        @endif
    @endif
    @yield('script')
</body>
</html>