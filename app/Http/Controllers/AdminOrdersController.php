<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrdersController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(5); // Hiển thị 10 đơn hàng mỗi trang
        return view('AdminOrders.index', compact('orders'));
    }

    // Hiển thị chi tiết một đơn hàng
    public function show($id)
    {
        $order = Order::with(['orderDetails.ticket'])->findOrFail($id); // Lấy đơn hàng cùng chi tiết
        return view('AdminOrders.show', compact('order'));
    }
}
