@extends('LayoutAdmin.master')

@section('title', 'Danh sách giao dịch')

@section('content_admin')
    <div class="container">
        <h1 class="my-4 mt-3 text-center">Danh sách giao dịch</h1>

        <!-- Hiển thị thông báo thành công hoặc lỗi -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Bảng hiển thị các bản ghi -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Điểm</th>
                        <th>Trạng thái</th>
                        <th>Thông báo</th>
                        <th>Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($exchanges as $exchange)
                        <tr>
                            <td>{{ $exchange->transaction_hash }}</td>
                            <td>{{ $exchange->user->email ?? $exchange->user->fullname }}</td>
                            <td>{{ $exchange->point }}</td>
                            <td>
                                @if ($exchange->status == 1)
                                    <span class="badge bg-success">Thành công</span>
                                @elseif ($exchange->status == 2)
                                    <span class="badge bg-danger">Thất bại</span>
                                @else
                                    <span class="badge bg-warning">Đang chờ</span>
                                @endif
                            </td>
                            <td>{{ $exchange->message }}</td>
                            <td>{{ $exchange->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Hiển thị phân trang -->
        <div class="d-flex justify-content-center">
            {{ $exchanges->links() }}
        </div>
    </div>
@endsection
