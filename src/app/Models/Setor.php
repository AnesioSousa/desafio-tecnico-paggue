<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;
    protected $fillable = ['descricao', 'imagem', 'qtd_assentos', 'tem_cobertura'];

    public function eventos() {
        return $this->belongsToMany(Evento::class, 'composicoes', 'id_setor', 'id_evento');
    }
}
