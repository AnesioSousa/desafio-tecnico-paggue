<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    use HasFactory;

    protected $fillable = ['id_ingresso', 'serial', 'desconto', 'data_consumo'];

    public function ingresso() {
        return $this->belongsTo(Ingresso::class, 'id_ingresso');
    }
}
