<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acerto extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_de_pagamento',
        'valor_colaborador',
        'valor_empresa',
        'pago',
        'restante',
        'status',
        'total',
        'contrato_id',
        'user_id',
        'created_at',
        'updated_at'
    ];
}
