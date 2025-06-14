<?php
// src/app/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;


    protected $fillable = [
        'order_id',
        'batch_id',
        'quantity',
        'coupon_id',
        'unit_price',
        'discount_value',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}

