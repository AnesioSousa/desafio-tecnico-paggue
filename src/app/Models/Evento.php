<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;
    protected $fillable = ['id_produtor', 'data', 'cidade', 'local', 'hora_inicio', 'hora_fim'];

    public function produtor() {
        return $this->belongsTo(Produtor::class, 'id_produtor');
    }

    public function ingressos() {
        return $this->hasMany(Ingresso::class, 'id_evento');
    }

    public function setores() {
        return $this->belongsToMany(Setor::class, 'composicoes', 'id_evento', 'id_setor');
    }
}
