<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingresso extends Model
{
    use HasFactory;
    protected $fillable = ['id_evento', 'id_lote', 'serial', 'data_venda'];

    public function evento() {
        return $this->belongsTo(Evento::class, 'id_evento');
    }

    public function lote() {
        return $this->belongsTo(Lote::class, 'id_lote');
    }

    public function compras() {
        return $this->belongsToMany(Compra::class, 'ingresso_compra', 'id_ingresso', 'id_compra');
    }

    public function cupom() {
        return $this->hasOne(Cupom::class, 'id_ingresso');
    }
}
