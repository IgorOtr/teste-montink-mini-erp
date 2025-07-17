<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'total_price', 'shipping_price', 'cep', 'email'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

