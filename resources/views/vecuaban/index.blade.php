@extends('LayoutUser.master')

@section('title')
    Vé của bạn
@endsection

@section('content')
    <div class="container mt-4">
        <h1 class="text-center mb-4">Danh sách vé của bạn</h1>

        @if ($orders->isEmpty())
            <div class="alert alert-warning text-center">
                Không có đơn hàng nào.
            </div>
        @else
            @foreach ($orders as $order)
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($order->orderDetails as $detail)
                                @php
                                    $ticket = $detail->ticket;
                                    $isExpired = \Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($ticket->enday));
                                @endphp

                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        @if ($ticket->image)
                                            <img src="{{ asset('storage/' . $ticket->image) }}" alt="Ticket Image"
                                                class="img-thumbnail"
                                                style="width: 120px; height: 120px; margin-right: 20px;">
                                        @else
                                            <img src="{{ asset('images/default-ticket.png') }}" alt="Default Ticket"
                                                class="img-thumbnail"
                                                style="width: 50px; height: 50px; margin-right: 15px;">
                                        @endif
                                        <div class="order-info">
                                            <h4 class="mt-4 mb-2 text-info font-weight-bold">
                                                <span class="badge bg-info">Tên phim:</span>
                                                {{ $ticket->name ?? 'Không rõ' }}
                                            </h4>
                                            <div class="mb-3">
                                                <strong>Ngày bắt đầu:</strong>
                                                <span class="text-muted">
                                                    {{ \Carbon\Carbon::parse($ticket->startday)->format('d/m/Y') }}
                                                </span>
                                            </div>

                                            <div>
                                                <strong>Ngày kết thúc:</strong>
                                                <span class="text-muted">
                                                    {{ \Carbon\Carbon::parse($ticket->enday)->format('d/m/Y') }}
                                                </span>
                                            </div>

                                            @if ($isExpired)
                                                <div class="alert alert-danger mt-3">
                                                    Vé đã hết hạn: {{ $ticket->name }}
                                                </div>
                                            @else
                                                <div class="alert alert-success mt-3">
                                                    Vé còn hạn: {{ $ticket->name }}
                                                </div>
                                            @endif
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
