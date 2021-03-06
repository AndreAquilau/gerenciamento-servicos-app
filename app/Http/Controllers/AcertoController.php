<?php

namespace App\Http\Controllers;

use App\Models\Acerto;
use App\Models\User;
use App\Models\Contrato;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Redirect;
use Inertia\Inertia;
class AcertoController extends Controller
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
        $acertos = $this->findAllByEmpresaAcerto($query);

        return $this->view("Comissao/Index", ["query" => $query, "acertosAll" => $acertos]);
    }

    public function index()
    {
        $comissoes = Contrato::all()
        ->where('status', '=', '1')
        ->where('valor', '>', '0.00');

        return ['comissoes' => $comissoes];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    public function cancelar()
    {
        $body = json_decode(file_get_contents('php://input'), true);

        $query = $body["query"];

        $acerto=  $body["acerto"];

        Acerto::where("id", "=", $acerto["id"])
        ->where("contrato_id", "=", $acerto["contrato_id"])
        ->update($acerto);

        return Redirect::route('comissoes',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'] ]);
    }

    public function findAllByEmpresaAcerto($query) {

        $body = json_decode(file_get_contents('php://input'), true);

        $acertos = DB::table('users')
        ->join('empresas', 'users.empresa_id', '=', 'empresas.id')
        ->join('contratos', 'users.id', '=', 'contratos.user_id')
        ->join('correntistas', 'contratos.correntista_id', '=', 'correntistas.id')
        ->join('colaboradors', 'contratos.colaborador_id', '=', 'colaboradors.id')
        ->join('recebes', 'contratos.id', '=', 'recebes.contrato_id')
        ->join('acertos', 'recebes.id', '=', 'acertos.recebe_id')
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
            'acertos.id AS acerto_id',
            'acertos.valor_colaborador AS acerto_valor_colaborador',
            'acertos.valor_empresa AS acerto_valor_empresa',
            'acertos.pago AS acerto_pago',
            'acertos.restante AS acerto_restante',
            'acertos.total AS acerto_total',
            'acertos.desconto AS acerto_desconto',
            'acertos.acrescimo AS acerto_acrescimo',
            'acertos.status AS acerto_status',
            'acertos.data_de_pagamento AS acerto_data_de_pagamento',
            'acertos.data_de_emissao AS acerto_data_de_emissao',
            'acertos.created_at AS acerto_created_at',
            'acertos.updated_at AS acerto_updated_at',
            'acertos.user_id AS acerto_user_id',
            'acertos.recebe_id AS acerto_recebe_id',
            'acertos.contrato_id AS acerto_contrato_id',
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
        ->orderBy('acertos.created_at', 'desc')
        ->get();

        return $acertos;
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
     * @param  \App\Models\Acerto  $acerto
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $acerto = Acerto::findOrFail($id);

        return ['acerto' => $acerto];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Acerto  $acerto
     * @return \Illuminate\Http\Response
     */
    public function edit(Acerto $acerto)
    {
        //
    }

    public function update()
    {
        $body = json_decode(file_get_contents('php://input'), true);

        $query = $body["query"];

        DB::transaction(function () use ($body) {

            $acerto = $body["acerto"];

            $query = $body["query"];

            $restante = $acerto["acerto_valor_colaborador"] - $acerto["acerto_pago"];

            Acerto::where("id", "=", $acerto["acerto_id"])
            ->where("contrato_id", "=", $acerto["acerto_contrato_id"])
            ->update([
                "valor_colaborador" => $acerto["acerto_valor_colaborador"],
                "valor_empresa" => $acerto["acerto_valor_empresa"],
                "pago" => $acerto["acerto_pago"],
                "total" => $acerto["acerto_total"],
                "status" => true,
                "restante" => $restante,
                "user_id" => $acerto["acerto_user_id"],
                "contrato_id" => $acerto["acerto_contrato_id"],
                "data_de_pagamento" => $acerto["acerto_data_de_pagamento"],
                "updated_at" => $acerto['acerto_data_de_pagamento'],
            ]);

            if($restante > 0.00) {
                Acerto::create([
                    "valor_colaborador" => $restante,
                    "valor_empresa" => 0.00,
                    "pago" => 0.00,
                    "total" => $restante,
                    "status" => false,
                    "restante" => $restante,
                    "user_id" => $query["user_id"],
                    "contrato_id" => $acerto["acerto_contrato_id"],
                    "data_de_pagamento" => null,
                    "data_de_emissao" => $acerto["acerto_data_de_pagamento"],
                    "created_at" => $acerto["acerto_data_de_pagamento"],
                    "updated_at" => $acerto["acerto_data_de_pagamento"],
                    "recebe_id" => $acerto["acerto_recebe_id"],
                ]);

            }

        });



        $acertos = $this->findAllByEmpresaAcerto($query);

        return $this->view("Comissao/Index", ["query" => $query, "acertosAll" => $acertos]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Acerto  $acerto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $body = json_decode(file_get_contents('php://input'), true);

        //busca a empresa do usuario que esta tentando deletar um acerto em sua empresa
        $user_id_empresa = User::findOrFail(["id" => $body["user_id"]]);

        $acerto_user_id = Acerto::findOrFail(["id" => $id]);

        $acerto_empresa_id = User::findOrFail(["id" =>  $acerto_user_id[0]->user_id]);


        //verificar se os usuarios sao da mesma empresa
        if($acerto_empresa_id[0]->empresa_id == $user_id_empresa[0]->empresa_id ) {
            $res= Acerto::where('id', '=',  $id)->delete();

            return [
                "acerto" => $res,
                "response" => $acerto_empresa_id[0]->empresa_id == $user_id_empresa[0]->empresa_id
            ];

        } else {
            return ["acerto" => [],  "response" => false];
        }
    }
}
