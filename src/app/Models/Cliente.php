<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_cliente';
    protected $fillable = [
        'nome',
        'endereco',
        'email',
        'senha'
    ];

    public function pfisica() { return $this->hasOne(PFísica::class, 'id_cliente'); }
    public function pjuridica() { return $this->hasOne(PJuridica::class, 'id_cliente'); }
    public function compras(){
        return $this->hasMany(Compra::class, 'id_cliente');
    }
}
