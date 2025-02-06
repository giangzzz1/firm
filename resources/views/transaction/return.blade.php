@extends('LayoutAdmin.master')

@section('title')
    Tra cứu Giao Dịch
@endsection

@section('content_admin')
    @php
        // Giả sử giá SOL hiện tại là 20 USD (lấy từ API hoặc giá cố định)
        $solInUsd = 250;

        // Chuyển đổi lamports sang SOL
        $solAmount = $transactionDetails['lamports'] / 1000000000;

        // Chuyển đổi SOL sang USD
        $usdAmount = $solAmount * $solInUsd;
    @endphp

    <div class="container mt-5">
        <h2 class="mb-4">Thông Tin Giao Dịch</h2>

        <!-- Tạo một bảng đẹp với Bootstrap và thêm thanh cuộn ngang -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Thông Tin Chi Tiết Giao Dịch</h4>
            </div>
            <div class="card-body">
                <!-- Thêm class table-responsive để hiển thị thanh cuộn ngang khi bảng quá rộng -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Mã giao dịchdịch</th>
                                <th>Sender</th>
                                <th>Receivers</th>
                                <th>LAMPORTS</th>
                                <th>Block Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $transactionDetails['transactionHash'] }}</td>
                                <td>{{ $transactionDetails['sender'] }}</td>
                                <td>
                                    @foreach ($transactionDetails['receivers'] as $receiver)
                                        <div>{{ $receiver }}</div>
                                    @endforeach
                                </td>
                                <td>{{$solAmount}}-SOL ~ {{$usdAmount}}-USD</td>
                                <td>{{ date('Y-m-d H:i:s', $transactionDetails['blockTime']) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Thêm nút quay lại -->
        <div class="mt-3">
            <a href="/transaction" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
@endsection
