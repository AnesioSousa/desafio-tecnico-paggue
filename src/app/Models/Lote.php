<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;
    protected $fillable = ['data_inicio', 'data_vencimento'];

    public function ingressos() {
        return $this->hasMany(Ingresso::class, 'id_lote');
    }
}
