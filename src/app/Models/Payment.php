<?php
// src/models/Payment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'pix_payload', 'pix_transaction_id', 'status'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
