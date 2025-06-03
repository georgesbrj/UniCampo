<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profissao extends Model
{
    protected $table = 'profissoes';

    protected $fillable = ['nome_profissao'];

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'id_profissao');
    }
}
