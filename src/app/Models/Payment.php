<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'external_id',
        'payer_name',
        'amount',
        'description',
        'pix_payload',
        'pix_transaction_id',
        'payment',
        'end_to_end_id',
        'reference',
        'status',
        'paid_at',
        'expiration_at',
    ];

    protected $casts = [
        'pix_payload' => 'array',
        'paid_at' => 'datetime',
        'expiration_at' => 'datetime',
    ];

    /**
     * Relação com o pedido associado.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
