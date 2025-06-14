<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'discount', 'valid_from', 'valid_until', 'max_uses'];

    protected $casts = [
        'discount' => 'float',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
