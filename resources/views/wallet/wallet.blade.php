@extends('LayoutUser.master')

@section('title')
    Connect Wallet
@endsection

@section('content')
    <div class="container">
        <h2 class="text-center">Connect Phantom Wallet</h2>

        <!-- Thông báo khi chưa kết nối ví -->
        @if(is_null(Auth::user()->wallet))
            <div class="text-center mt-3" style="color: red; font-size: 18px;">
                Bạn chưa kết nối ví, vui lòng kết nối để có thể thanh toán.
            </div>
            <!-- Nút kết nối ví -->
            <button id="connectWalletButton" class="btn btn-primary mt-3">Connect Wallet</button>
        @else
            <!-- Hiển thị khi đã kết nối ví -->
            <div id="connectedWalletInfo">
                <p class="mt-3" style="color: green; font-size: 18px;">
                    Wallet Address: {{ $walletAddress }}
                </p>

                <form action="{{ route('wallet.destroy', Auth::user()->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn ngắt kết nối ví không?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Disconnect Wallet</button>
                </form>

                <div id="walletBalances" class="mt-3">
                    <h5>Your Wallet Balances:</h5>
                    @if($balance)
                        <ul>
                            <li>SOL: {{ $balance }} SOL</li> <!-- Số dư từ API -->
                            <!-- Thêm các loại token khác nếu cần -->
                        </ul>
                    @else
                        <p>Không thể lấy số dư ví. Vui lòng thử lại sau.</p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <script>
        document.getElementById('connectWalletButton')?.addEventListener('click', async () => {
            if (window.solana && window.solana.isPhantom) {
                try {
                    const response = await window.solana.connect();
                    const walletAddress = response.publicKey.toString();

                    // Gửi ví đến backend bằng form ẩn
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('wallet.store') }}";

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'wallet';
                    input.value = walletAddress;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    form.appendChild(input);
                    form.appendChild(csrf);
                    document.body.appendChild(form);

                    form.submit();
                } catch (error) {
                    console.error('Connection failed', error);
                    alert('Failed to connect to Phantom wallet. Please try again.');
                }
            } else {
                alert('Phantom Wallet is not installed. Please install Phantom Wallet and try again.');
            }
        });
    </script>
@endsection
