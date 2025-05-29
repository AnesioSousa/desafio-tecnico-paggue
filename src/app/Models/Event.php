<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'producer_id',
        'title',
        'description',
        'banner_url',
        'date',
        'start_time',
        'end_time',
        'city',
        'venue'
    ];
    public function producer()
    {
        return $this->belongsTo(User::class, 'producer_id');
    }

    public function sectors()
    {
        return $this->hasMany(Sector::class);
    }
}
