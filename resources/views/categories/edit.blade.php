@extends('LayoutAdmin.master')

@section('title')
    Sửa danh mục
@endsection


@section('content_admin')
    <h1 class="text-center"> Sửa danh mục</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.update', $category->id) }}" method="POST" class="mt-3">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{route('categories.index')}}" class="btn btn-secondary">Quay lại</a>
        </div>
   
    </form>
@endsection
