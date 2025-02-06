<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'total_amount',
        'status',
        'message',
        'transaction_hash'
    ];
    // Mối quan hệ nhiều-nhiều với Ticket
    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'order_ticket', 'order_id', 'ticket_id');
    }

    // Mối quan hệ một-nhiều với OrderDetail
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
