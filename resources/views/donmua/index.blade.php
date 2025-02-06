@extends('LayoutUser.master')

@section('title')
    Đơn mua
@endsection

@section('content')
    <div class="container mt-4">
        <h1 class="text-center mb-4">Danh sách đơn mua</h1>

        @if ($orders->isEmpty())
            <div class="alert alert-warning text-center">
                Không có đơn hàng nào.
            </div>
        @else
            @foreach ($orders as $order)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Đơn mua #{{ $order->id }}</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($order->orderDetails as $detail)
                                <h4 class="mt-4 mb-2 text-primary font-weight-bold">
                                    <span class="badge bg-info">Tên phim:</span>
                                    {{ $detail->ticket->name ?? 'Không rõ' }}
                                </h4>

                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        @if ($detail->ticket->image)
                                            <img src="{{ asset('storage/' . $detail->ticket->image) }}" alt="Ticket Image"
                                                class="img-thumbnail"
                                                style="width: 120px; height: 120px; margin-right: 20px;">
                                        @else
                                            <img src="{{ asset('images/default-ticket.png') }}" alt="Default Ticket"
                                                class="img-thumbnail"
                                                style="width: 50px; height: 50px; margin-right: 15px;">
                                        @endif
                                        <div class="order-info">
                                            <div class="mb-3">
                                                <strong>Trạng thái thanh toán:</strong>
                                                @if ($order->status == 0)
                                                    <span class="badge bg-warning">Chờ thanh toán</span>
                                                @elseif ($order->status == 1)
                                                    <span class="badge bg-success">Đã thanh toán</span>
                                                @else
                                                    <span class="badge bg-danger">Thất bại/Đã hủy</span>
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <strong>Số lượng:</strong> <span
                                                    class="badge bg-success">{{ $detail->quantity }}</span>
                                            </div>

                                            <div class="mb-3">
                                                <strong>Giá:</strong> <span
                                                    class="text-primary">{{ number_format($detail->price, 2) }} USD</span>
                                            </div>

                                            <div class="mb-3">
                                                <strong>Tổng tiền:</strong> <span
                                                    class="text-success">{{ number_format($detail->total, 2) }} USD</span>
                                            </div>

                                            <div class="mb-3">
                                                <strong>Ngày bắt đầu:</strong>
                                                <span class="text-muted">
                                                    {{ $order->orderDetails->first()->ticket->startday ? \Carbon\Carbon::parse($order->orderDetails->first()->ticket->startday)->format('d/m/Y') : 'Không có' }}
                                                </span>
                                            </div>

                                            <div>
                                                <strong>Ngày kết thúc:</strong>
                                                <span class="text-muted">
                                                    {{ $order->orderDetails->first()->ticket->enday ? \Carbon\Carbon::parse($order->orderDetails->first()->ticket->enday)->format('d/m/Y') : 'Không có' }}
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
