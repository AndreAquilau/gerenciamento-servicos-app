<?php

namespace App\Http\Controllers;

use App\Models\Recebe;
use Illuminate\Http\Request;
use App\Models\Acerto;
use App\Models\User;
use App\Models\Contrato;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Redirect;
use Inertia\Inertia;

class RecebeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view($view, $data = [])
    {
        return Inertia::render($view, $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */

    public function renderPage(Request $query)
    {
        $recebementos = $this->findAllByEmpresaRecebimento($query);

        return $this->view("Recebimento/Index", ["query" => $query, "recebimentosAll" => $recebementos]);
    }

    public function findAllByEmpresaRecebimento($query) {

        $body = json_decode(file_get_contents('php://input'), true);

        $recebementos = DB::table('users')
        ->join('contratos', 'users.id', '=', 'contratos.user_id')
        ->join('empresas', 'users.empresa_id', '=', 'empresas.id')
        ->join('correntistas', 'contratos.correntista_id', '=', 'correntistas.id')
        ->join('colaboradors', 'contratos.colaborador_id', '=', 'colaboradors.id')
        ->join('recebes', 'contratos.id', '=', 'recebes.contrato_id')
        ->select(
            'contratos.id AS contrato_id',
            'contratos.data_de_fechamento AS contrato_data_de_fechamento',
            'contratos.data_de_emissao AS contrato_data_de_emissao',
            'contratos.data_de_vencimento AS contrato_data_de_vencimento',
            'contratos.status AS contrato_status',
            'contratos.valor AS contrato_valor',
            'contratos.valor_avista AS contrato_valor_avista',
            'contratos.valor_parcelado AS contrato_valor_parcelado',
            'contratos.quantidade_parcela AS contrato_quantidade_parcela',
            'contratos.desconto AS contrato_desconto',
            'contratos.acrescimo AS contrato_acrescimo',
            'contratos.descricao_do_servico AS contrato_descricao_do_servico',
            'contratos.percentual_comissao_colaborador AS contrato_percentual_comissao_colaborador',
            'contratos.created_at AS contrato_created_at',
            'contratos.updated_at AS contrato_updated_at',
            'contratos.user_id AS contrato_user_id',
            'contratos.colaborador_id AS contrato_colaborador_id',
            'contratos.correntista_id AS contrato_correntista_id',
            'colaboradors.id AS colaborador_id',
            'colaboradors.nome_completo AS colaborador_nome_completo',
            'colaboradors.cpf_cnpj AS colaborador_cpf_cnpj',
            'colaboradors.registro AS colaborador_registro',
            'colaboradors.rg AS colaborador_rg',
            'colaboradors.comissao AS colaborador_comissao',
            'colaboradors.telefone AS colaborador_telefone',
            'colaboradors.bairro AS colaborador_bairro',
            'colaboradors.rua AS colaborador_rua',
            'colaboradors.cidade AS colaborador_cidade',
            'colaboradors.numero AS colaborador_numero',
            'colaboradors.estado AS colaborador_estado',
            'colaboradors.experiencia AS colaborador_experiencia',
            'colaboradors.inativo AS colaborador_inativo',
            'colaboradors.user_id AS colaborador_user_id',
            'correntistas.id AS correntista_id',
            'correntistas.nome AS correntista_nome',
            'correntistas.cpf AS correntista_cpf',
            'correntistas.cnpj AS correntista_cnpj',
            'correntistas.telefone AS correntista_telefone',
            'correntistas.bairro AS correntista_bairro',
            'correntistas.rua AS correntista_rua',
            'correntistas.cidade AS correntista_cidade',
            'correntistas.numero AS correntista_numero',
            'correntistas.estado AS correntista_estado',
            'correntistas.inativo AS correntista_inativo',
            'correntistas.user_id AS correntista_user_id',
            'recebes.id AS recebe_id' ,
            'recebes.documento AS recebe_documento' ,
            'recebes.ordem_documento AS recebe_ordem_documento',
            'recebes.ordem_documento_final AS recebe_ordem_documento_final',
            'recebes.desconto AS recebe_desconto',
            'recebes.acrescimo AS recebe_acrescimo',
            'recebes.pago AS recebe_pago',
            'recebes.restante AS recebe_restante',
            'recebes.total AS recebe_total',
            'recebes.status AS recebe_status',
            'recebes.data_de_emissao AS recebe_data_de_emissao',
            'recebes.data_de_vencimento AS recebe_data_de_vencimento',
            'recebes.data_de_pagamento AS recebe_data_de_pagamento',
            'recebes.created_at AS recebe_created_at',
            'recebes.updated_at AS recebe_updated_at',
            'recebes.user_id AS recebe_user_id' ,
            'recebes.acerto_id AS recebe_acerto_id',
            'recebes.contrato_id AS recebe_contrato_id',
        )
        ->where([
            ['empresas.id', '=', $query['empresa_id']],
        ])
        ->orderBy('recebes.created_at', 'desc')
        ->get();



        return $recebementos;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recebe  $recebe
     * @return \Illuminate\Http\Response
     */
    public function show(Recebe $recebe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Recebe  $recebe
     * @return \Illuminate\Http\Response
     */
    public function edit(Recebe $recebe)
    {
        //
    }

    public function update()
    {
        $body = json_decode(file_get_contents('php://input'), true);
        $query = $body['query'];

        DB::transaction(function () use ($body) {

            $contratoCreate = $body['acerto'];

            $receber = $body["recebimento"];

            if((float)$receber["recebe_restante"] > 0.00){
                Recebe::create([
                    "documento" => $receber["recebe_documento"] + 1,
                    "ordem_documento" => $receber["recebe_ordem_documento"] + 1,
                    "ordem_documento_final" => null,
                    "data_de_fechamento" => null,
                    "desconto" => 0.00,
                    "acrescimo" => 0.00,
                    "pago" => 0.00,
                    "restante" => $receber["recebe_restante"],
                    "total" => $receber["recebe_restante"],
                    "status" => false,
                    "data_de_emissao" => $receber["recebe_data_de_pagamento"],
                    "data_de_vencimento" => $receber["recebe_data_de_pagamento"],
                    "created_at" => $receber["recebe_data_de_pagamento"],
                    "updated_at" => $receber["recebe_data_de_pagamento"],
                    "user_id" => $receber["recebe_user_id"],
                    "contrato_id" => $receber["recebe_contrato_id"],
                    "acerto_id" => null,
                ]);

                Recebe::where('contrato_id', '=', $receber['recebe_contrato_id'])
                ->update([
                    "ordem_documento_final" => $receber['recebe_ordem_documento_final'] + 1,
                ]);
            }

            $acerto = Acerto::create($contratoCreate);

            Recebe::where('id', '=', $receber['recebe_id'])
            ->update([
                "pago" => $receber['recebe_pago'],
                "restante" => $receber['recebe_restante'],
                "status" => $receber['recebe_status'],
                "data_de_pagamento" => $receber['recebe_data_de_pagamento'],
                "updated_at" => $receber['recebe_updated_at'],
                "acerto_id" => $acerto["id"],
            ]);

        });

        $recebementos = $this->findAllByEmpresaRecebimento($query);

        return $this->view("Recebimento/Index", ["query" => $query, "recebimentosAll" => $recebementos]);
    }

    public function cancelar()
    {


        $body = json_decode(file_get_contents('php://input'), true);

        $query = $body["query"];

        DB::transaction(function () use ($body) {

            $receber =  $body["recebimento"];

            Recebe::where('id', '=', $receber['recebe_id'])
            ->update([
                "pago" => $receber['recebe_pago'],
                "restante" => $receber['recebe_restante'],
                "updated_at" => $receber['recebe_updated_at'],
                "status" => $receber['recebe_status'],
                "data_de_pagamento" => null,
            ]);

            Acerto::where("recebe_id", "=", $receber['recebe_id'])->delete();
        });
        return Redirect::route('recebimentos',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'] ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recebe  $recebe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recebe $recebe)
    {
        //
    }
}
