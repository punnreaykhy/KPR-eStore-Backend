<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['status', 'user_email'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
