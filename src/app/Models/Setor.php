<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;

    // tabela correta
    protected $table = 'setores';

    // chave primária “id_setor” em vez de “id”
    protected $primaryKey = 'id_setor';

    // caso o id_setor seja auto-incremento inteiro:
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'descricao',
        'imagem',
        'qtd_assentos',
        'tem_cobertura',
    ];

    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'composicoes', 'id_setor', 'id_evento');
    }
}

