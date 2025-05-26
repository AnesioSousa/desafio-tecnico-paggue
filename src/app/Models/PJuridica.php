<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PJuridica extends Model
{
    use HasFactory;
    protected $table = 'pjuridicas';
    protected $fillable = ['id_cliente', 'cnpj', 'inscricao_estadual'];

    public function cliente() {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
}
