<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Solana\RpcClient\PublicKey;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    public function getWalletBalance($walletAddress)
    {
        try {
            // Kiểm tra nếu ví rỗng hoặc không hợp lệ
            if (empty($walletAddress)) {
                return 'Địa chỉ ví không hợp lệ.';
            }

            // Chuyển sang devnet để test
            $rpcEndpoint = "https://api.devnet.solana.com";

            // Payload yêu cầu lấy số dư
            $payload = [
                "jsonrpc" => "2.0",
                "id" => 1,
                "method" => "getBalance",
                "params" => [$walletAddress]
            ];

            // Gửi request đến Solana Devnet
            $response = Http::post($rpcEndpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['result']['value'])) {
                    $balanceLamport = $data['result']['value'];
                    $balanceSOL = $balanceLamport / 1000000000; // Chuyển Lamport sang SOL

                    // Thêm airdrop SOL test nếu số dư bằng 0
                    if ($balanceSOL == 0) {
                        $this->requestAirdrop($walletAddress);
                        // Đợi một chút để airdrop được xác nhận
                        sleep(2);
                        // Lấy lại số dư mới
                        $response = Http::post($rpcEndpoint, $payload);
                        if ($response->successful()) {
                            $data = $response->json();
                            $balanceLamport = $data['result']['value'] ?? 0;
                            $balanceSOL = $balanceLamport / 1000000000;
                        }
                    }

                    return [
                        'status' => 'success',
                        'balance' => number_format($balanceSOL, 2),
                        'network' => 'devnet'
                    ];
                }

                return [
                    'status' => 'error',
                    'message' => 'Không thể lấy số dư từ RPC.',
                    'network' => 'devnet'
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Lỗi kết nối đến Solana Devnet.',
                'network' => 'devnet'
            ];
        } catch (\Exception $e) {
            Log::error("Lỗi khi lấy số dư ví devnet: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'network' => 'devnet'
            ];
        }
    }

    // Thêm function để request airdrop SOL test
    private function requestAirdrop($walletAddress)
    {
        try {
            $rpcEndpoint = "https://api.devnet.solana.com";

            // Request 1 SOL (1 billion lamports)
            $payload = [
                "jsonrpc" => "2.0",
                "id" => 1,
                "method" => "requestAirdrop",
                "params" => [
                    $walletAddress,
                    1000000000 // 1 SOL in lamports
                ]
            ];

            $response = Http::post($rpcEndpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['result'])) {
                    // Signature của transaction airdrop
                    $signature = $data['result'];

                    // Đợi transaction được confirm
                    $this->confirmTransaction($signature);

                    Log::info("Airdrop thành công cho ví: " . $walletAddress);
                    return true;
                }
            }

            Log::error("Không thể thực hiện airdrop");
            return false;
        } catch (\Exception $e) {
            Log::error("Lỗi khi thực hiện airdrop: " . $e->getMessage());
            return false;
        }
    }

    // Thêm function để confirm transaction
    private function confirmTransaction($signature)
    {
        try {
            $rpcEndpoint = "https://api.devnet.solana.com";

            $payload = [
                "jsonrpc" => "2.0",
                "id" => 1,
                "method" => "confirmTransaction",
                "params" => [$signature]
            ];

            $maxRetries = 10;
            $retryCount = 0;

            while ($retryCount < $maxRetries) {
                $response = Http::post($rpcEndpoint, $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['result']['value']) && $data['result']['value']) {
                        return true;
                    }
                }

                $retryCount++;
                sleep(1);
            }

            return false;
        } catch (\Exception $e) {
            Log::error("Lỗi khi confirm transaction: " . $e->getMessage());
            return false;
        }
    }

    public function index()
    {
        try {
            // Lấy ví của Admin
            $adminWallet = User::where('role', 2)->value('wallet');

            // Sử dụng giá trị mặc định nếu không có ví Admin
            if (!$adminWallet) {
                $adminWallet = 'Ví admin không tồn tại';
            }

            $user = auth()->user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
            }

            // Lấy giỏ hàng của người dùng
            $cart = Cart::where('user_id', $user->id)->first();
            $cartItems = $cart ? CartItem::where('cart_id', $cart->id)->with('ticket')->get() : collect();
            $totalPrice = $cartItems->sum('total');

            // Kiểm tra ví người dùng
            $hasWallet = !is_null($user->wallet);
            $walletBalance = $hasWallet ? $this->getWalletBalance($user->wallet) : 'Ví chưa kết nối';

            // Trả dữ liệu về view
            return view('carts.checkout', compact('user', 'adminWallet', 'cartItems', 'totalPrice', 'hasWallet', 'walletBalance'));
        } catch (\Exception $e) {
            // Log lỗi để dễ dàng debug
            Log::error("Lỗi trong phương thức index: " . $e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi, vui lòng thử lại sau.');
        }
    }

    public function store(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return redirect()->route('carts.index')->with('error', 'Vui lòng đăng nhập để thực hiện hành động này.');
            }

            $userPublicKey = $request->input('userPublicKey');
            $cartItems = $request->input('cartItems');
            $totalAmount = $request->input('totalAmount');
            $adminWallet = $request->input('adminWallet');
            $transactionHash = $request->input('transactionHash');

            // Kiểm tra dữ liệu đầu vào
            if (empty($userPublicKey) || empty($cartItems) || empty($totalAmount) || empty($transactionHash)) {
                return redirect()->route('carts.index')->with('error', 'Thông tin đơn hàng không đầy đủ.');
            }

            // Tạo bản ghi order
            $order = Order::create([
                'user_id' => $user->id,
                'transaction_hash' => $transactionHash,
                'quantity' => collect($cartItems)->sum('quantity'),
                'total_amount' => $totalAmount,
                'status' => 1, // Đơn hàng hoàn thành
            ]);

            // Lưu chi tiết đơn hàng
            foreach ($cartItems as $item) {
                if (isset($item['ticket_id'], $item['quantity'], $item['price'], $item['total'])) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'ticket_id' => $item['ticket_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $item['total'],
                    ]);

                    // Cập nhật số lượng trong bảng tickets
                    $ticket = Ticket::find($item['ticket_id']);
                    if ($ticket) {
                        $ticket->decrement('quantity', $item['quantity']); // Giảm số lượng tồn kho
                        $ticket->increment('sell_quantity', $item['quantity']); // Tăng số lượng đã bán
                    } else {
                        Log::warning('Không tìm thấy ticket với ID: ' . $item['ticket_id']);
                    }
                } else {
                    Log::warning('Dữ liệu sản phẩm không hợp lệ: ' . json_encode($item));
                }
            }

            // Xóa giỏ hàng sau khi đặt hàng thành công
            $cart = Cart::where('user_id', $user->id)->first();
            if ($cart) {
                CartItem::where('cart_id', $cart->id)->delete();
            }

            return redirect()->route('carts.index')->with('success', 'Đặt hàng thành công.');
        } catch (\Exception $e) {
            // Log lỗi để kiểm tra
            Log::error("Lỗi trong phương thức store: " . $e->getMessage());
            return redirect()->route('carts.index')->with('error', 'Đã xảy ra lỗi khi lưu đơn hàng: ' . $e->getMessage());
        }
    }
}
