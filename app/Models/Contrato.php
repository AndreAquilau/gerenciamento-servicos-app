<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_de_fechamento',
        'data_de_emissao',
        'data_de_vencimento',
        'status',
        'valor',
        'valor_avista',
        'valor_parcelado',
        'descricao_do_servico',
        'quantidade_parcela',
        'desconto',
        'acrescimo',
        'percentual_comissao_colaborador',
        'created_at' ,
        'updated_at',
        'user_id',
        'colaborador_id',
        'correntista_id',
    ];
}
