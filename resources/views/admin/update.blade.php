@extends('LayoutAdmin.master')

@section('title')
    Cập nhật tài khoản
@endsection

@section('content_admin')

<div class="container">
    <div class="d-flex nav nav-pills">
        <a href="{{route('admin.edit')}}" class="nav-link bg-light">Hồ sơ của tôi</a>
        <a href="{{route('admin.changepass.form')}}" class="nav-link bg-light">Cập nhật mật khẩu</a>
    </div>

    <h1 class="text-center m-5">Cập nhật tài khoản</h1>

    <div class="container">
        <form action="{{ route('admin.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="fullname">Full Name</label>
            <input type="text" class="form-control mb-3" name="fullname" id="fullname" value="{{ old('fullname', Auth::user()->fullname) }}">
    
            <label for="birth_day">Birth Day</label>
            <input type="date" class="form-control mb-3" name="birth_day" id="birth_day" value="{{ old('birth_day', Auth::user()->birth_day) }}">
    
            <label for="phone">Phone</label>
            <input type="text" class="form-control mb-3" name="phone" id="phone" value="{{ old('phone', Auth::user()->phone) }}">
    
            <label for="email">Email</label>
            <input type="email" class="form-control mb-3" id="email" value="{{ old('email', Auth::user()->email) }}" disabled>
            <input type="hidden" name="email" value="{{ old('email', Auth::user()->email) }}">
            
            <label for="address">Address</label>
            <input type="text" class="form-control mb-3" name="address" id="address" value="{{ old('address', Auth::user()->address) }}">
            
            <label for="avatar" class="mt-3">Avatar</label>
            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" width="100px" class="ms-3 mt-3">

            <input type="file" class="form-control mb-3 mt-3" name="avatar" id="avatar">
    
            <div class="text-center m-3">
                <button type="submit" class="btn btn-success">Cập nhật</button>
                <a href="{{route('user.dashboard')}}" class="btn btn-secondary">Quay lai</a>
            </div>
            
        </form>
    </div>
</div>

@endsection
