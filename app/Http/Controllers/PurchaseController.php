<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{

    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login'); // Hoặc chuyển hướng đến trang đăng nhập
        }

        // Lấy đơn hàng của người dùng hiện tại
        $orders = Order::with(['orderDetails.ticket'])
            ->where('user_id', auth()->id()) // Lọc theo user_id của người dùng hiện tại
            ->orderBy('created_at', 'desc') // Sắp xếp theo ngày tạo đơn hàng mới nhất
            ->get();

        // Kiểm tra vé còn hạn hay không


        return view('donmua.index', compact('orders'));
    }
}
