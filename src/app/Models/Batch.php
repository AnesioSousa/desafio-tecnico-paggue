<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;
    protected $fillable = ['sector_id', 'start_date', 'end_date', 'price', 'total_quantity'];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}