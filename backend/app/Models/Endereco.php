<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $fillable = [
        'endereco',
        'numero',
        'bairro',
        'complemento',
        'cidade',
        'uf',
    ];

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'id_endereco');
    }
}
