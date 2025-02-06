@extends('LayoutAdmin.master')

@section('title')
    Đổi mật khẩu
@endsection

@section('content_admin')

<div class="container">
    <div class="d-flex nav nav-pills">
        <a href="{{route('admin.edit')}}" class="nav-link bg-light">Hồ sơ của tôi</a>
        <a href="{{route('admin.changepass.form')}}" class="nav-link bg-light">Cập nhật mật khẩu</a>
    </div>

    <h1 class="text-center m-5">Đổi mật khẩu</h1>

    <form action="{{ route('admin.password.change') }}" method="POST">
        @csrf

        <div>
            <label for="current_password">Mật khẩu hiện tại</label>
            <input type="password" class="form-control mb-3" name="current_password" id="current_password" required value="{{old('current_password')}}">
        </div>

        <div>
            <label for="new_password">Mật khẩu mới</label>
            <input type="password" class="form-control mb-3" name="new_password" id="new_password" required>
        </div>

        <div>
            <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
            <input type="password" class="form-control mb-5" name="new_password_confirmation" id="new_password_confirmation" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success text-center">Đổi mật khẩu</button>
            <a href="{{route('admin.dashboard')}}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>

@endsection
