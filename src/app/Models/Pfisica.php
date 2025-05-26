<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pfisica extends Model
{
    use HasFactory;
    protected $table = 'pfisicas';
    protected $fillable = [
        'id_cliente',
        'cpf',
        'sexo',
        'data_nascimento'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id');
    }
}
