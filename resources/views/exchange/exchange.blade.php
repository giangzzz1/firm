@extends('LayoutUser.master')

@section('title')
    Đổi điểm
@endsection

@section('content')
    <h1>Exchange Points to Solana</h1>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form id="exchange-form" action="{{ route('exchange.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="points">Points to Exchange (minimum 10,000 points):</label>
            <input type="number" name="points" id="points" class="form-control" required min="10000">
        </div>
        <button type="submit" id="proceed-to-checkout" class="btn btn-primary mt-3">Exchange</button>
    </form>


    <h3 class="mt-5 mb-5">Lịch sử đổi điểm</h3>
    <!-- Bảng hiển thị các bản ghi -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Điểm</th>
                    <th>Trạng thái</th>
                    <th>Thông báo</th>
                    <th>Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($exchanges as $exchange)
                    <tr>
                        <td>
                            <span class="transaction-hash" data-full="{{ $exchange->transaction_hash }}">
                                {{ substr($exchange->transaction_hash, 0, 3) }}...{{ substr($exchange->transaction_hash, -3) }}
                            </span>
                        </td>
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

    <!-- Thêm CSS và JavaScript -->
    <style>
        .transaction-hash {
            cursor: pointer;
            display: inline-block;
            position: relative;
        }

        .transaction-hash:hover::after {
            content: attr(data-full);
            position: absolute;
            top: 100%;
            left: 0;
            white-space: nowrap;
            background-color: #f8f9fa;
            padding: 5px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }
    </style>

    <script>
        // Script để sao chép mã giao dịch khi người dùng click vào
        document.querySelectorAll('.transaction-hash').forEach(item => {
            item.addEventListener('click', function() {
                const transactionHash = item.getAttribute('data-full');
                navigator.clipboard.writeText(transactionHash).then(() => {
                    alert('Mã giao dịch đã được sao chép!');
                }).catch(err => {
                    console.error('Lỗi sao chép mã giao dịch: ', err);
                });
            });
        });
    </script>

    <!-- Hiển thị phân trang -->
    <div class="d-flex justify-content-center">
        {{ $exchanges->links() }}
    </div>

    <script>
        const adminWallet = @json($adminWallet);
        const userWallet = @json($userWallet);

        console.log("Admin Wallet:", adminWallet);
        console.log("User Wallet:", userWallet);

        document.querySelector('#proceed-to-checkout').addEventListener('click', async function(e) {
            e.preventDefault();

            const pointsInput = document.querySelector('#points');
            const points = parseInt(pointsInput.value, 10);

            // Validate points input
            if (isNaN(points) || points < 10000) {
                alert('You must exchange at least 10,000 points.');
                return;
            }

            // Check if Phantom Wallet is installed
            if (!window.solana || !window.solana.isPhantom) {
                alert('Phantom Wallet is required for the transaction.');
                return;
            }

            const provider = window.solana;

            try {
                // Connect Phantom Wallet
                const resp = await provider.connect();
                const userPublicKey = resp.publicKey.toString();

                if (!adminWallet || !userPublicKey) {
                    alert('Invalid wallet addresses.');
                    return;
                }

                // Calculate amounts
                const usdAmount = points / 10000; // 10,000 points = 1 USD
                const solAmount = usdAmount / 250; // Assuming 1 SOL = 250 USD

                console.log(`Exchanging ${points} points -> ${usdAmount} USD -> ${solAmount.toFixed(6)} SOL`);

                // Establish Solana connection
                const connection = new solanaWeb3.Connection(solanaWeb3.clusterApiUrl('devnet'), 'confirmed');

                // Validate balances
                const adminBalance = await connection.getBalance(new solanaWeb3.PublicKey(adminWallet));
                const requiredLamports = Math.ceil(solanaWeb3.LAMPORTS_PER_SOL * solAmount);

                if (adminBalance < requiredLamports) {
                    alert(`Admin wallet has insufficient balance. Required: ${solAmount} SOL.`);
                    return;
                }

                // Create transaction
                const transaction = new solanaWeb3.Transaction().add(
                    solanaWeb3.SystemProgram.transfer({
                        fromPubkey: new solanaWeb3.PublicKey(adminWallet),
                        toPubkey: new solanaWeb3.PublicKey(userPublicKey),
                        lamports: requiredLamports,
                    })
                );

                // Fetch recent blockhash
                const {
                    blockhash
                } = await connection.getLatestBlockhash('confirmed');
                transaction.recentBlockhash = blockhash;
                transaction.feePayer = new solanaWeb3.PublicKey(adminWallet);

                // Sign and send transaction
                const signedTransaction = await provider.signTransaction(transaction);
                const txId = await connection.sendRawTransaction(signedTransaction.serialize(), {
                    skipPreflight: false,
                    preflightCommitment: 'confirmed',
                });

                console.log('Transaction ID:', txId);

                // Confirm transaction
                const confirmation = await connection.confirmTransaction(txId, 'confirmed');
                if (confirmation.value.err) {
                    throw new Error('Transaction failed on the blockchain.');
                }

                alert('Giao dịch thành công');

                // Submit transaction details to backend
                const form = document.getElementById('exchange-form');
                const transactionInput = document.createElement('input');
                transactionInput.type = 'hidden';
                transactionInput.name = 'transaction_id';
                transactionInput.value = txId;
                form.appendChild(transactionInput);

                form.submit();

            } catch (error) {
                console.error('Transaction error:', error);
                alert('Transaction failed: ' + error.message);
            }
        });
    </script>
@endsection
