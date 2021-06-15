<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acerto extends Model
{
    use HasFactory;

    protected $fillable = [
        'valor_colaborador',
        'valor_empresa',
        'pago',
        'restante',
        'total',
        'acrescimo',
        'desconto',
        'status',
        'data_de_emissao',
        'data_de_pagamento',
        'created_at',
        'updated_at',
        'user_id',
        'recebe_id',
        'contrato_id',
    ];
}
