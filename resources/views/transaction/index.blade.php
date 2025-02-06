
@extends('LayoutAdmin.master')

@section('title')
    Tra cứu
@endsection

@section('content_admin')

    <div class="container">
        <h2>Tìm kiếm giao dịch</h2>
        <form action="/transaction" method="POST">
            @csrf
            <div class="form-group">
                <label for="transaction_hash">Nhập Transaction Hash:</label>
                <input type="text" id="transaction_hash" name="transaction_hash" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </form>
    </div>
@endsection
