<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'data_nascimento',
        'tipo_pessoa',
        'cpf_cnpj',
        'email',
        'telefone',
        'id_endereco',
        'id_profissao',
        'status',
    ];

    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'id_endereco');
    }

    public function profissao()
    {
        return $this->belongsTo(Profissao::class, 'id_profissao');
    }
}
