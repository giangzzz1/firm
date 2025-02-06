<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaZml28PAk06p+ni8V+DhjL8Ync3j2cE9l+9JJyBZaqG6nT7zVu2nCXsKlp" crossorigin="anonymous">

    <!-- Bootstrap Bundle with Popper (JS) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"
        integrity="sha384-QF1/Jztwu3m8JHsBCtx8gVtZo1xxi5htqF6Epu6p1Cwoi24kzO/fd01N8rVYgXmP" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="{{ asset('login/css.css') }}">
    <title>@yield('title')</title>
</head>

<body>
    <div class="wrapper fadeInDown">
        <div class="container text-center" style="color: red">
            @if ($errors->any())
                <div class="alert alert-danger text-center">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info">
                    {{ session('info') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
        <div id="formContent">
            <!-- Tabs Titles -->
            <a href="{{ route('login.form') }}">
                <h2 class="underlineHover {{ Route::currentRouteName() === 'login.form' ? 'selected' : '' }}">
                    Sign In
                </h2>
            </a>
            <a href="{{ route('register.form') }}">
                <h2 class="underlineHover {{ Route::currentRouteName() === 'register.form' ? 'selected' : '' }}">
                    Sign Up
                </h2>
            </a>

            <!-- Login Form -->
            @yield('content')

            <!-- Remind Password -->
            <div id="formFooter">
                <a class="underlineHover {{ Route::currentRouteName() === 'forgot.form' ? 'selected' : '' }}"
                    href="{{ route('forgot.form') }}">
                    Forgot Password?
                </a>
            </div>
        </div>
    </div>


</body>

</html>
