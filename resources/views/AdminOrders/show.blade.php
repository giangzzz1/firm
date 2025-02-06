@extends('LayoutAdmin.master')

@section('title', 'Chi tiết đơn hàng')

@section('content_admin')
    <div class="container mt-4">
        <h1 class="text-center mb-4">Chi tiết đơn hàng #{{ $order->id }}</h1>

        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <p><strong>Mã giao dịch:</strong> {{ $order->transaction_hash }}</p>
                <p><strong>Khách hàng:</strong> {{ $order->user->email }}</p>
                <p><strong>Tổng số lượng:</strong> {{ $order->quantity }}</p>
                <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount, 2) }} USD</p>
                <p><strong>Trạng thái:</strong> 
                    @if ($order->status == 0)
                        <span class="badge bg-warning">Chờ thanh toán</span>
                    @elseif ($order->status == 1)
                        <span class="badge bg-success">Thành công</span>
                    @else
                        <span class="badge bg-danger">Thất bại/Đã hủy</span>
                    @endif
                </p>
                <p><strong>Thông báo:</strong> {{ $order->message ?? 'Không có' }}</p>
            </div>
        </div>

        <h3 class="mb-4">Danh sách chi tiết</h3>
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-dark">
                <tr>
                    <th>STT</th>
                    <th>Hinh ảnh</th>
                    <th>Tên vé</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderDetails as $detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="width: 100px"> 
                            <img src="{{ asset('storage/' . $detail->ticket->image) }}" width="40px" height="30px" class="ms-3">
                        </td>
                        <td>{{ $detail->ticket->name ?? 'Không rõ' }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->price, 2) }} USD</td>
                        <td>{{ number_format($detail->total, 2) }} USD</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('adminorders.index') }}" class="btn btn-secondary mt-4">Quay lại</a>
    </div>
@endsection
