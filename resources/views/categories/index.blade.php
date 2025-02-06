@extends('LayoutAdmin.master')

@section('title')
    Danh sách danh mục
@endsection

@section('content_admin')
    <h1 class="text-center">Danh sách danh mục</h1>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('categories.create') }}" class="btn btn-outline-success mb-3">Thêm mới</a>
    </div>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th scope="col">Số lượng vé</th>
                <th scope="col">Tạo</th>
                <th scope="col">Sửa</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->tickets_count > 0 ? $category->tickets_count : 'N/A' }}</td>

                    <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $category->updated_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-outline-warning mb-3">Edit</a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger mb-3"
                                onclick="return confirm('Bạn có chắc muốn xóa không?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $data->links() }} <!-- Hiển thị phân trang nếu có -->
    </div>
@endsection
