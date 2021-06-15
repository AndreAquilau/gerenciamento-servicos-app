<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recebe extends Model
{
    use HasFactory;

    protected $fillable = [
        'documento' ,
        'ordem_documento',
        'ordem_documento_final',
        'desconto',
        'acrescimo',
        'pago',
        'restante',
        'total',
        'status',
        'data_de_emissao',
        'data_de_vencimento',
        'data_de_pagamento',
        'created_at',
        'updated_at',
        'user_id',
        'acerto_id',
        'contrato_id',
    ];
}
