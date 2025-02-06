<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function getChartData()
    {
        // Doanh thu theo ticket_id
        $revenueData = OrderDetail::select('ticket_id', DB::raw('SUM(total) as total_revenue'))->groupBy('ticket_id')->orderBy('total_revenue', 'desc')->get();

        // Số lượng vé bán ra theo ticket_id
        $quantityData = OrderDetail::select('ticket_id', DB::raw('SUM(quantity) as total_quantity'))->groupBy('ticket_id')->orderBy('total_quantity', 'desc')->get();
        // dd($revenueData, $quantityData);
        return view('admin.dashboard', compact('revenueData', 'quantityData'));
    }
}
