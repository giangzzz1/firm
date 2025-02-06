<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\User;
use Illuminate\Http\Request;

class AdminExchangeController extends Controller
{
    public function index(Request $request)
    {
        // Lấy tất cả bản ghi với phân trang (5 bản ghi mỗi trang)
        $exchanges = Exchange::with('user') // Nếu bạn muốn lấy thông tin người dùng liên quan
            ->paginate(5);

        return view('exchange.admin', compact('exchanges'));
    }
}
