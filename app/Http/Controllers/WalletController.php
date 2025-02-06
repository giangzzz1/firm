<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WalletController extends Controller
{
    public function getWalletBalance($walletAddress)
    {
        try {
            // Địa chỉ RPC của Solana Mainnet
            $rpcEndpoint = "https://api.mainnet-beta.solana.com";

            // Payload yêu cầu lấy số dư
            $payload = [
                "jsonrpc" => "2.0",
                "id" => 1,
                "method" => "getBalance",
                "params" => [$walletAddress] // Địa chỉ ví Phantom
            ];

            // Gửi request đến Solana RPC để lấy số dư
            $response = Http::post($rpcEndpoint, $payload);
            $data = $response->json();

            // Kiểm tra kết quả và chuyển đổi từ Lamport sang SOL
            if (isset($data['result']['value'])) {
                $balanceLamport = $data['result']['value'];
                $balanceSOL = $balanceLamport / 1000000000; // Chuyển Lamport sang SOL
                return number_format($balanceSOL, 2); // Trả về số dư SOL
            } else {
                return 'Không thể lấy số dư ví.';
            }
        } catch (\Exception $e) {
            return 'Có lỗi khi kết nối đến Solana RPC API: ' . $e->getMessage();
        }
    }

    public function index()
    {

        $walletAddress = Auth::user()->wallet;
        if ($walletAddress) {
            $balance = $this->getWalletBalance($walletAddress);
        } else {
            $balance = null;
        }

        // Trả về view và gửi dữ liệu ví cùng với số dư
        return view('wallet.wallet', compact('walletAddress', 'balance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'wallet' => 'required|string|unique:users,wallet',
        ]);

        /**
         * @var User $user
         */
        $user = Auth::user();
        $user->wallet = $request->wallet;
        $user->save();

        return back()->with('success', 'Wallet address saved successfully.');
    }

    public function destroy($userId)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        if ($user->id == $userId) {
            // Xóa địa chỉ ví khỏi cơ sở dữ liệu
            $user->wallet = null;
            $user->save();

            // Quay lại trang trước đó và hiển thị thông báo thành công
            return redirect()->back()->with('success', 'Wallet disconnected successfully.');
        }

        // Trả về thông báo lỗi nếu hành động không được ủy quyền
        return redirect()->back()->with('error', 'Unauthorized action.');
    }
}
