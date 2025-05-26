<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;
    protected $fillable = ['id_cliente', 'data', 'metodo_pagamento'];

    public function cliente() {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function ingressos() {
        return $this->belongsToMany(Ingresso::class, 'ingresso_compra', 'id_compra', 'id_ingresso');
    }
}
