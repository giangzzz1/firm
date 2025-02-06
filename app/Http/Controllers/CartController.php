<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        /**
         * @var User $user
         */

        $user = auth()->user();
        $cart = $user->cart()->first();

        if (!$cart) {
            $cart = Cart::create(['user_id' => $user->id]);
        }

        $cartItems = CartItem::with('ticket')->where('cart_id', $cart->id)->get();
        $totalQuantity = $cartItems->sum('quantity');
        $subtotal = $cartItems->sum(function ($item) {
            return $item->ticket ? $item->quantity * $item->ticket->price : 0;
        });

        // Call getSolanaPrice from the other controller
        $otherController = app(\App\Http\Controllers\TicketController::class);
        $solRate = $otherController->getSolanaPrice();

        return view('carts.carts', compact('cartItems', 'totalQuantity', 'subtotal', 'solRate'));
    }


    public function addToCart(Request $request)
    {
        $ticketId = $request->ticket_id;

        if (!$ticketId) {
            return redirect()->back()->with('error', 'Ticket ID is required!');
        }

        $ticket = Ticket::find($ticketId);

        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket not found!');
        }

        $user = auth()->user();
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['user_id' => $user->id]
        );

        // Kiểm tra xem sản phẩm đã có trong giỏ chưa
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('ticket_id', $ticket->id)
            ->first();

        if ($cartItem) {
            // Nếu có thì tăng số lượng
            $cartItem->quantity += 1;
        } else {
            // Nếu chưa có thì tạo mới
            $cartItem = new CartItem([
                'cart_id' => $cart->id,
                'ticket_id' => $ticket->id,
                'quantity' => 1,
                'price' => $ticket->price,
            ]);
        }

        $cartItem->total = $cartItem->quantity * $cartItem->price;
        $cartItem->save();

        return redirect()->back()->with('success', 'Ticket added to cart successfully!');
    }

    public function update(Request $request, $cartItemId)
    {
        // Lấy CartItem và kiểm tra
        $cartItem = CartItem::findOrFail($cartItemId);
        $newQuantity = $request->input('quantity');

        // Cập nhật số lượng
        $cartItem->update(['quantity' => $newQuantity]);

        // Tính toán lại tổng tiền của giỏ hàng
        $cart = $cartItem->cart;

        // Tải lại các items và tính toán tổng tiền
        $cartItems = CartItem::with('ticket')->where('cart_id', $cart->id)->get();
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->ticket->price;
        });

        return response()->json([
            'success' => true,
            'subtotal' => $subtotal,
            'subtotalFormatted' => number_format($subtotal, 0, ',', '.') . ' VNĐ',
            'total' => $subtotal // Cập nhật total (nếu cần)
        ]);
    }

    public function destroy($id)
    {
        // Lấy sản phẩm cần xóa và kiểm tra
        $cartItem = CartItem::find($id);

        if ($cartItem && $cartItem->cart->user_id == auth()->id()) {
            $cartItem->delete();

            // Cập nhật tổng giỏ hàng
            $subtotal = CartItem::where('cart_id', $cartItem->cart->id)->sum(DB::raw('quantity * price'));

            return response()->json(['success' => true, 'subtotal' => $subtotal]);
        }

        return response()->json(['success' => false, 'message' => 'Item not found or not owned by the user']);
    }
}
