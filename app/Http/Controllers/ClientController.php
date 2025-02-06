<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Http\Request;


class ClientController extends Controller
{
    public function index()
    {
        $tickets = Ticket::latest()->paginate(3);
        $ticketss = Ticket::orderBy('price', 'desc')->paginate(3);

        return view('shop.index', compact('tickets', 'ticketss'));
    }

    public function shop(Request $request)
    {
        // Lấy tất cả các danh mục với số lượng vé
        $categories = Category::withCount('tickets')->get();

        // Khởi tạo query vé
        $ticketsQuery = Ticket::query();

        // Lọc theo category 
        if ($request->has('category') && $request->category != 'all') {
            $ticketsQuery->where('category_id', $request->category);
        }
        // Lọc theo giá
        if ($request->has('price_min') && $request->has('price_max')) {
            $ticketsQuery->whereBetween('price', [$request->price_min, $request->price_max]);
        }
        // Lọc theo sắp xếp
        if ($request->has('sort_by')) {
            $sortBy = $request->sort_by;
            if ($sortBy == 'price_low_high') {
                $ticketsQuery->orderBy('price', 'asc');
            } elseif ($sortBy == 'price_high_low') {
                $ticketsQuery->orderBy('price', 'desc');
            } elseif ($sortBy == 'name_az') {
                $ticketsQuery->orderBy('name', 'asc');
            } elseif ($sortBy == 'name_za') {
                $ticketsQuery->orderBy('name', 'desc');
            }
        }

        // Lấy vé từ query
        $tickets = $ticketsQuery->paginate(6);

        return view('shop.shop', compact('categories', 'tickets'));
    }


    public function contact()
    {
        return view('about.contact');
    }
    public function about()
    {
        return view('about.about');
    }
}
