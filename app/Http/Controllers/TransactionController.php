<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{

    public function index()
    {
        return view('transaction.index');
    }

    public function return(Request $request)
    {
        $transactionHash = $request->input('transaction_hash');

        // Gọi phương thức lấy chi tiết giao dịch từ Solana
        $transactionDetails = $this->getTransactionDetailsDevnet($transactionHash);

        if ($transactionDetails) {
            return view('transaction.return', compact('transactionDetails'));
        } else {
            return back()->withErrors(['transaction_hash' => 'Không tìm thấy giao dịch với mã hash này.']);
        }
    }

    // Phương thức lấy thông tin giao dịch (giống như bạn đã làm)
    public function getTransactionDetailsDevnet($transactionHash)
    {
        try {
            $rpcEndpoint = "https://api.testnet.solana.com";
            $payload = [
                "jsonrpc" => "2.0",
                "id" => 1,
                "method" => "getTransaction",
                "params" => [$transactionHash, "json"]
            ];

            $response = Http::post($rpcEndpoint, $payload);
            $data = $response->json();

            if (isset($data['result'])) {
                $transaction = $data['result'];
                $sender = $transaction['transaction']['message']['accountKeys'][0] ?? null;

                // Kiểm tra và lấy địa chỉ ví của người nhận từ 'postTokenBalances'
                $receivers = [];
                foreach ($transaction['meta']['postTokenBalances'] ?? [] as $balance) {
                    // Lọc các ví thực tế (địa chỉ không phải là địa chỉ hệ thống)
                    if (!in_array($balance['owner'], ['11111111111111111111111111111111', 'ComputeBudget111111111111111111111111111111'])) {
                        $receivers[] = $balance['owner'];
                    }
                }

                // Nếu không có thông tin từ 'postTokenBalances', thử lấy từ 'accountKeys'
                if (empty($receivers)) {
                    foreach ($transaction['transaction']['message']['accountKeys'] as $key) {
                        // Lọc các địa chỉ ví hệ thống
                        if ($key != $sender && !in_array($key, ['11111111111111111111111111111111', 'ComputeBudget111111111111111111111111111111'])) {
                            $receivers[] = $key;
                        }
                    }
                }

                // Chuyển đổi lamports thành SOL
                $solAmount = $transaction['meta']['postBalances'][0] / 1000000000;  // 1 SOL = 1,000,000,000 lamports

                return [
                    'transactionHash' => $transactionHash,
                    'sender' => $sender,
                    'receivers' => $receivers,
                    'lamports' => $transaction['meta']['postBalances'][0] ?? null,
                    'solAmount' => $solAmount,
                    'blockTime' => $transaction['blockTime'] ?? null,
                ];
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
