@extends('LayoutAdmin.master')

@section('title', 'Danh sách đơn hàng')

@section('content_admin')

    <h1 class="text-center mb-4">Danh sách đơn hàng</h1>
    @if ($orders->isEmpty())
        <div class="alert alert-warning text-center">Không có đơn hàng nào.</div>
    @else
        <div class="table-responsive"> <!-- Thêm div với class để cuộn -->
            <table class="table table-bordered table-hover text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Mã giao dịch</th>
                        <th>Khách hàng</th>
                        <th>Tổng số lượng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->transaction_hash }}</td>
                            <td>{{ $order->user->email }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ number_format($order->total_amount, 2) }} USD</td>
                            <td>
                                @if ($order->status == 0)
                                    <span class="badge bg-warning">Chờ thanh toán</span>
                                @elseif ($order->status == 1)
                                    <span class="badge bg-success">Thành công</span>
                                @else
                                    <span class="badge bg-danger">Thất bại/Đã hủy</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('adminorders.show', $order->id) }}" class="btn btn-info btn-sm">
                                    Xem chi tiết
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Hiển thị phân trang -->
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links('pagination::bootstrap-4') }}
        </div>
    @endif


    <style>
        .table-responsive {
            overflow-x: auto;
            /* Cho phép cuộn ngang */
            -webkit-overflow-scrolling: touch;
            /* Mượt hơn trên thiết bị di động */
            border: 1px solid #ddd;
            /* Tùy chọn: Viền để tách biệt bảng */
        }
    </style>
@endsection
