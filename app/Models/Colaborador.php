<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_completo',
        'cpf_cnpj',
        'registro',
        'rua',
        'rg',
        'comissao',
        'telefone',
        'bairro',
        'cidade',
        'numero',
        'estado',
        'experiencia',
        'inativo',
        'user_id',
        'created_at',
        'updated_at',
    ];
}
