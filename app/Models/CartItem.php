<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'ticket_id', 'quantity', 'price', 'total'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);  // Một mục giỏ hàng thuộc về một giỏ hàng
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);

    }
}
