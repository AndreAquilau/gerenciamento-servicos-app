<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'fantasia',
        'razao_social',
        'inscricao_estadual',
        'cpf',
        'cnpj',
        'telefone',
        'celular',
        'bairro',
        'cidade',
        'numero',
        'estado',
        'cep',
        'rua',
        'email',
        'created_at',
        'updated_at',
    ];
}
