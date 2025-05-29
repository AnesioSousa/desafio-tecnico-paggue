<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'payload', 'status', 'sent_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
