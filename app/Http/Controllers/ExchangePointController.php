<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Exhange_point;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangePointController extends Controller
{

    public function index(Request $request)
    {
        $exchanges = Exchange::with('user') // Nếu bạn muốn lấy thông tin người dùng liên quan
        ->where('user_id', auth()->id()) // Lọc theo ID của người dùng đang đăng nhập
        ->paginate(5);

        // Lấy ví admin (giả định chỉ có một admin)
        $adminWallet = User::where('role', 2)->value('wallet');

        // Kiểm tra ví người dùng đang đăng nhập
        $user = auth()->user();
        if (!$user || !$user->wallet) {
            return redirect()->back()->with('error', 'Ví người dùng chưa được thiết lập.');
        }

        $userWallet = $user->wallet;

        // Truyền dữ liệu ra view
        return view('exchange.exchange', compact('adminWallet', 'userWallet', 'exchanges'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'points' => 'required|integer|min:10000',
        ]);

        $user = auth()->user();
        $points = $request->points;
        $transactionId = $request->input('transaction_id');

        if ($user->point < $points) {
            return back()->with('error', 'You do not have enough points to exchange.');
        }

        // Calculate USD and SOL amounts
        $usdAmount = $points / 10000; // 10,000 points = 1 USD
        $solAmount = $usdAmount / 250; // Assuming 1 SOL = 250 USD

        try {
            
            /** @var User $user */
            // Deduct points from user
            $user->point -= $points;
            $user->save();

            // Record transaction
            Exchange::create([
                'transaction_hash' => $transactionId,
                'user_id' => $user->id,
                'point' => $points,
                'status' => 1, // Success
                'message' => "Exchanged {$points} points to {$solAmount} SOL.",
            ]);

            return back()->with('success', "Successfully exchanged {$points} points for {$solAmount} SOL.");
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while processing your request: ' . $e->getMessage());
        }
    }
}
