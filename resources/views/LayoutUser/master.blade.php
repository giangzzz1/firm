<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Nhúng Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('user/style.css') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-I5fwlQDtE5YPkGPrXQckgFdMUWdUJHEZ2r09Fz9zogTzNoL7fEYDQ5vFcN8K5Wfg" crossorigin="anonymous">

    <script src="https://unpkg.com/@solana/web3.js@1.41.0/lib/index.iife.js"></script>
    
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background-color: #f5f7fa;
    margin: 0;
    padding: 0;
}

/* Container */
.container {
    max-width: 1200px;
    margin: auto;
}

/* Menu container */
.menu-container {
    width: 280px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    background-color: #ffffff;
    padding: 20px;
}

.menu-container .nav-profile-text {
    padding: 15px;
    background-color: #e3f2fd;
    border-radius: 8px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    text-decoration: none;
    transition: background-color 0.3s ease-in-out;
}

.menu-container .nav-profile-text:hover {
    background-color: #d1e9ff;
}

.img-profile {
    border: 3px solid #4caf50;
    margin-right: 15px;
}

.menu-container .menu {
    padding: 0;
    margin: 0;
}

.menu-container .menu li {
    list-style: none;
    margin-bottom: 10px;
}

.menu-container .menu a {
    color: #555;
    font-weight: 600;
    display: block;
    padding: 10px 15px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.menu-container .menu a:hover {
    background-color: #f4f8fb;
    color: #333;
}

.menu-container .dropdown-btn {
    border: none;
    background: none;
    font-weight: 600;
    color: #555;
    padding: 10px 15px;
    text-align: left;
    width: 100%;
    cursor: pointer;
    outline: none;
    transition: background-color 0.3s ease;
}

.menu-container .dropdown-btn:hover {
    background-color: #f4f8fb;
    color: #333;
}

.menu-container .dropdown-content {
    padding-left: 15px;
    display: none;
}

.menu-container .dropdown-content li a {
    font-weight: normal;
    color: #777;
    padding: 5px 0;
}

.menu-container .dropdown-content li a:hover {
    color: #000;
}

/* Nút đăng xuất */
.menu-container form button {
    width: 100%;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    color: white;
    text-align: left;
    padding: 12px 15px;
    background-color: #e53935;
    transition: background-color 0.3s;
}

.menu-container form button:hover {
    background-color: #d32f2f;
}

/* Phần Content */
.content-container {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    margin-left: 20px;
    padding: 20px;
}

.alert {
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    padding: 15px;
}

.alert-danger {
    background-color: #ffebee;
    border: 1px solid #f44336;
    color: #c62828;
}

.alert-success {
    background-color: #e8f5e9;
    border: 1px solid #4caf50;
    color: #2e7d32;
}

.alert-info {
    background-color: #e3f2fd;
    border: 1px solid #2196f3;
    color: #1565c0;
}

    </style>

</head>

<body class="container mt-5">
    <div class="d-flex">
        <!-- Phần Menu -->
        <div class="menu-container bg-light p-3">
            <a href="http://127.0.0.1:8000/user/dashboard">
                <div class="nav-profile-text d-flex align-items-center">

                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" width="50px" alt="profile"
                        class="img-profile rounded-circle" />
                    <span class="login-status online"></span>

                    <div class="ms-2">
                        <span class="d-block" style="color: cadetblue">Xin chào</span>
                        <span
                            style="color: purple">{{ Auth::user()->fullname ?? (Auth::user()->email ?? Auth::user()->username) }}</span>
                    </div>
                </div>
            </a>
            <ul class="menu list-unstyled">
                <li class="dropdown">
                    <button class="dropdown-btn btn btn-light w-100 text-start" onclick="toggleDropdown(this)">
                        <i class="icon-user"></i> Tài Khoản Của Tôi
                    </button>
                    <ul class="dropdown-content list-unstyled ps-3" style="display: block;"> <!-- Đặt display: block -->
                        <li><a href="{{ route('user.edit') }}">Hồ Sơ</a></li>
                        <li><a href="{{ route('user.changepass.form') }}">Đổi Mật Khẩu</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('wallet.index') }}" class="btn btn-light w-100 text-start">Kết nối ví</a></li>
                <li><a href="{{ route('donmua.index') }}" class="btn btn-light w-100 text-start">Đơn Mua</a></li>
                <li><a href="{{ route('diemdanh.index') }}" class="btn btn-light w-100 text-start">Điểm danh</a></li>
                <li><a href="{{ route('exchange.index') }}" class="btn btn-light w-100 text-start">Đổi điểm</a></li>
                <li><a href="{{ route('vecuaban.index') }}" class="btn btn-light w-100 text-start">Vé của tôi</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn badge bg-danger ms-3 mt-2"
                            onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')">Đăng xuất</button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Phần Content -->
        <div class="content-container p-4 flex-grow-1">
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

            @yield('content')
        </div>
    </div>


    <!-- Nhúng Bootstrap JavaScript -->
    <script src="{{ asset('user/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
