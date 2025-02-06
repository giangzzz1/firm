<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_hash',
        'point',
        'status',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
