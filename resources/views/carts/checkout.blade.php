@extends('LayoutClients.master')

@section('title')
    Carts
@endsection

@section('content_client')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0"><a href="{{ route('index') }}">Home</a> <span class="mx-2 mb-0">/</span> <a
                        href="{{ route('carts.index') }}">Cart</a> <span class="mx-2 mb-0">/</span> <strong
                        class="text-black">Checkout</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section text-center">
        <div class="container">
            <div class="row">
                <!-- Your Order Section -->
                <div class="col-md-8 offset-md-2">
                    <h2 class="h3 mb-5 text-black" style="font-size: 2.5rem;">Your Order</h2>
                    <div class="p-4 p-lg-5 border" style="background-color: #f9f9f9;">
                        <ul class="list-unstyled">
                            <li class="d-flex mb-4">
                                <span class="text-black" style="font-size: 1.25rem; font-weight: bold;">Product</span>
                                <span class="text-black ml-auto" style="font-size: 1.25rem; font-weight: bold;">Total</span>
                            </li>
                
                            <!-- Loop through the cart items -->
                            @php 
                                $totalAmount = 0; 
                                try {
                            @endphp
                            @foreach ($cartItems as $item)
                                @php
                                    // Ensure we're working with numeric values
                                    $quantity = is_numeric($item->quantity) ? floatval($item->quantity) : 0;
                                    $price = is_numeric($item->ticket->price) ? floatval($item->ticket->price) : 0;
                                    $itemTotal = $quantity * $price;
                                    $totalAmount += $itemTotal;
                                @endphp
                                <li class="d-flex mb-4 align-items-center">
                                    <div class="d-flex align-items-center" style="width: 60%;">
                                        <img src="{{ asset('storage/' . $item->ticket->image) }}"
                                            alt="{{ $item->ticket->name }}"
                                            style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px;">
                                        <span class="text-black"
                                            style="font-size: 1.125rem;">{{ $item->ticket->name }}</span>
                                        <span class="text-muted ml-3">x{{ $quantity }}</span>
                                    </div>
                                    <span class="text-black ml-auto"
                                        style="font-size: 1.125rem;">${{ number_format((float)$itemTotal, 2) }}</span>
                                </li>
                            @endforeach
                
                            <!-- Total Amount -->
                            <li class="d-flex mb-4">
                                <span class="text-black" style="font-size: 1.25rem; font-weight: bold;">Total Amount</span>
                                <span class="text-black ml-auto" style="font-size: 1.25rem; font-weight: bold;">
                                    ${{ number_format((float)$totalAmount, 2) }}
                                </span>
                            </li>
                            @php
                                } catch (\Exception $e) {
                                    // Log the error
                                    \Log::error('Error in checkout calculation: ' . $e->getMessage());
                                    $totalAmount = 0;
                                }
                            @endphp
                        </ul>
                
                        <!-- Payment Option: Phantom Wallet -->
                        @if ($hasWallet)
                            <div class="form-group mb-4">
                                <label for="phantom-wallet" class="d-flex align-items-center" style="font-size: 1.125rem;">
                                    <input type="radio" id="phantom-wallet" name="payment_method" value="phantom"
                                        style="margin-right: 10px;">
                                    Pay with Phantom Wallet
                                </label>
                                <div id="phantom-balance" class="mt-3">
                                    <p style="font-size: 1.125rem;">Your Solana balance: <span
                                            id="sol-balance">{{ is_numeric($walletBalance) ? number_format((float)$walletBalance, 2) : '0.00' }} SOL</span></p>
                                </div>
                            </div>
                        @else
                            <div class="form-group mb-4">
                                <p class="text-danger" style="font-size: 1.125rem;">
                                    Please connect your Phantom Wallet to proceed with this payment option.
                                    <a href="{{ route('wallet.index') }}" class="btn btn-link btn-sm"
                                        style="font-size: 1.125rem;"
                                        onclick="return confirm('chuyển đến trang kết nối ví')">Connect Wallet</a>
                                </p>
                            </div>
                        @endif
                
                        <!-- Proceed to Checkout Button -->
                        <div class="form-group">
                            <a href="#" id="proceed-to-checkout" class="btn btn-primary btn-lg py-3 btn-block"
                                style="font-size: 1.25rem;">Proceed To Checkout</a>
                        </div>
                    </div>
                </div>
                <!-- End of Your Order Section -->
            </div>
        </div>
    </div>

    <script>
       document.querySelector('#proceed-to-checkout').addEventListener('click', async function(e) {
                e.preventDefault();

                // Check if Phantom is installed
                if (!window.solana || !window.solana.isPhantom) {
                    alert('Bạn cần cài đặt Phantom Wallet để thanh toán.');
                    return;
                }

                const provider = window.solana;

                try {
                    // Validate provider connection
                    let publicKey;
                    try {
                        const resp = await provider.connect();
                        publicKey = resp.publicKey;
                        if (!publicKey) {
                            throw new Error('Không thể kết nối với ví Phantom');
                        }
                    } catch (connError) {
                        console.error('Connection error:', connError);
                        alert('Không thể kết nối với ví Phantom. Vui lòng thử lại.');
                        return;
                    }

                    const userPublicKey = publicKey.toString();
                    const adminWallet = "{{ $adminWallet }}";

                    // Validate both public keys

                    console.log(userPublicKey)
                    console.log(adminWallet)
                    let sender, recipient;
                    try {
                        // Check if the keys are in the correct format
                        if (!userPublicKey || !adminWallet ) {
                            throw new Error(' Địa chỉ không tồn tại');
                        }

                        sender = new solanaWeb3.PublicKey(userPublicKey);
                        recipient = new solanaWeb3.PublicKey(adminWallet);

                        // Validate that both keys are actually on the Solana network
                        if (!solanaWeb3.PublicKey.isOnCurve(sender.toBytes())) {
                            throw new Error('Public key người gửi không hợp lệ');
                        }
                        if (!solanaWeb3.PublicKey.isOnCurve(recipient.toBytes())) {
                            throw new Error('Public key người nhận không hợp lệ');
                        }
                    } catch (e) {
                        console.error('Public key validation error:', e);
                        alert("Public key không hợp lệ: " + e.message);
                        return;
                    }

                    // Calculate amount
                    const totalAmount = parseFloat(@json($totalAmount));
                    const solAmount = totalAmount / 250; // Tỷ giá USD/SOL

                    if (isNaN(solAmount) || solAmount <= 0) {
                        alert("Tổng tiền thanh toán không hợp lệ.");
                        return;
                    }

                    // Connect to Solana network and check balance
                    const connection = new solanaWeb3.Connection(solanaWeb3.clusterApiUrl('testnet'), 'confirmed');
                    
                    let balance;
                    try {
                        balance = await connection.getBalance(sender);
                    } catch (balanceError) {
                        console.error('Balance check error:', balanceError);
                        alert("Không thể kiểm tra số dư. Vui lòng thử lại.");
                        return;
                    }

                    if (balance < solanaWeb3.LAMPORTS_PER_SOL * solAmount) {
                        alert(`Không đủ SOL để thực hiện giao dịch. Cần: ${solAmount} SOL, Hiện có: ${balance / solanaWeb3.LAMPORTS_PER_SOL} SOL`);
                        return;
                    }

                    // Create and send transaction
                    try {
                        const { blockhash } = await connection.getLatestBlockhash();
                        const transaction = new solanaWeb3.Transaction({
                            recentBlockhash: blockhash,
                            feePayer: sender
                        });

                        const transferInstruction = solanaWeb3.SystemProgram.transfer({
                            fromPubkey: sender,
                            toPubkey: recipient,
                            lamports: Math.floor(solanaWeb3.LAMPORTS_PER_SOL * solAmount)
                        });

                        transaction.add(transferInstruction);

                        const signedTransaction = await provider.signTransaction(transaction);
                        const txId = await connection.sendRawTransaction(signedTransaction.serialize(), {
                            skipPreflight: false,
                            preflightCommitment: 'confirmed'
                        });

                        // Wait for confirmation
                        const confirmation = await connection.confirmTransaction(txId, 'confirmed');
                        if (confirmation.value.err) {
                            throw new Error('Giao dịch thất bại: ' + confirmation.value.err);
                        }

                        // Send to backend
                        const response = await fetch("{{ route('orders.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                userPublicKey: userPublicKey,
                                cartItems: @json($cartItems),
                                totalAmount: totalAmount,
                                adminWallet: adminWallet,
                                transactionHash: txId
                            })
                        });

                        if (response.redirected) {
                            window.location.href = response.url;
                        } else {
                            const result = await response.json();
                            if (result.success) {
                                alert("Thanh toán thành công! Đơn hàng của bạn đã được xử lý.");
                                window.location.href = "{{ route('carts.index') }}";
                            } else {
                                throw new Error(result.message || "Thanh toán thất bại");
                            }
                        }
                    } catch (txError) {
                        console.error('Transaction error:', txError);
                        alert("Lỗi trong quá trình giao dịch: " + txError.message);
                        return;
                    }

                } catch (error) {
                    console.error("Error during transaction:", error);
                    alert("Có lỗi xảy ra trong quá trình thanh toán: " + error.message);
                }
            });
    </script>
@endsection
