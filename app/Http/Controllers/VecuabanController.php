<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VecuabanController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login'); // Chuyển hướng đến trang đăng nhập nếu chưa đăng nhập
        }

        // Lấy các đơn hàng của người dùng hiện tại và chỉ lấy các vé còn hạn
        $orders = Order::with(['orderDetails.ticket'])
            ->where('user_id', auth()->id()) // Lọc theo user_id của người dùng hiện tại
            ->orderBy('created_at', 'desc') // Sắp xếp theo ngày tạo đơn hàng mới nhất
            ->get()
            ->filter(function ($order) {
                // Lọc đơn hàng có vé còn hạn
                return $order->orderDetails->every(function ($detail) {
                    $ticket = $detail->ticket;
                    // Kiểm tra vé còn hạn (ngày kết thúc phải lớn hơn ngày hiện tại)
                    return $ticket && Carbon::now()->isBefore(Carbon::parse($ticket->enday));
                });
            });

        return view('vecuaban.index', compact('orders'));
    }
}
