<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'image',
        'startday',
        'enday',
        'price',
        'description',
        'quantity',
        'sell_quantity',
        'is_active',
        'nguoitochuc',
        'address',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_ticket', 'ticket_id', 'order_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
