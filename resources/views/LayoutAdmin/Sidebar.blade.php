<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile dropdown no-arrow">
            <a href="#" class="nav-link d-flex align-items-center" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="nav-profile-image">
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="avatar" class="img-profile rounded-circle"
                        style="width: 80px; height: 35px;" /> <!-- Thay đổi kích thước ở đây -->
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">
                        {{ Auth::user()->fullname ?? Auth::user()->email }}
                    </span>

                    <span style="color: green">
                        @if (Auth::user()->role == 1)
                            Manager
                        @elseif (Auth::user()->role == 2)
                            Admin
                        @endif
                    </span>


                </div>
                <!-- Dropdown toggle icon -->
                <span class="mdi mdi-dots-vertical mdi-24px ms-3"></span>
            </a>

            <!-- Dropdown menu -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item bg-red text-center" href="{{route('admin.edit')}}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item bg-yellow" href="#" data-toggle="modal" data-target="#logoutModal">
                    <form action="{{route('logout')}}" method="POST" class=" text-center">
                        @csrf
                        <button type="submit" class="btn badge bg-danger"
                            onclick="return confirm('chắc chắn đằng xuất')">Log Out</button>
                    </form>
                </a>
            </div>
        </li>

        <li class="nav-item">

            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <span class="menu-title">Trang chủ</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('categories.index') }}">
                <span class="menu-title">Danh mục</span>
                <i class="mdi mdi-tshirt-crew menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('tickets.index') }}">
                <span class="menu-title">Vé Xem phim </span>
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('adminorders.index')}}">
                <span class="menu-title">Đơn hàng</span>
                <i class="mdi mdi-clipboard menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('managers.index') }}">
                <span class="menu-title">Account</span>
                <i class="mdi mdi-account menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('Adminwallet.index')}}">
                <span class="menu-title">Ví Thanh toán</span>
                <i class="mdi mdi-comment menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/adminexchange">
                <span class="menu-title">Lịch sử đổi điểm</span>
                <i class="mdi mdi-format-color-fill menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/transaction">
                <span class="menu-title">Tra cứu giao dịch</span>
                <i class="mdi mdi-format-size menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>
