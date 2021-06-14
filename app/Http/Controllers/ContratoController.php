<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Redirect;
use App\Models\Acerto;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $contratos = Contrato::all();

        return ['contratos' => $contratos];
    }

    public function view($view, $data = [])
    {
        return Inertia::render($view, $data);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request () -> request: {empresa_id: "3", user_id: "2"}
     * @return \Illuminate\Http\Response
     */

    public function renderPage(Request $query)
    {
        $contratos = $this->findAllByEmpresaContrato($query);

        return $this->view("Contrato/Index", ["contratosAll" => $contratos, "query" => $query]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request () -> request: {empresa_id: "3", user_id: "2"}
     * @return \Illuminate\Http\Response
     */
    public function renderPageCreate(Request $query)
    {

        $colaboradores = $this->findAllColaboradorByEmpresa($query);
        $correntistas = $this->findAllCorrentistaByEmpresa($query);

        return $this->view("Contrato/Create", [
            "colaboradoresAll" => $colaboradores['colaboradores'],
            "correntistasAll" => $correntistas['correntistas'],
            "query" => $query,
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request () -> request: {empresa_id: "3", user_id: "2"}
     * @return \Illuminate\Http\Response
     */
    public function renderPageEdit(Request $query)
    {
        $body = json_decode(file_get_contents('php://input'), true);

        $dataAll = $this->findByIdContratoByEmpresa($query);

        $colaboradores = $this->findAllColaboradorByEmpresa($query);

        $correntistas = $this->findAllCorrentistaByEmpresa($query);

        $data = $dataAll[0];

        $colaborador = [
            "bairro" => $data->colaborador_bairro,
            "cidade"=>  $data->colaborador_cidade,
            "comissao"=>  $data->colaborador_comissao,
            "cpf_cnpj"=>  $data->colaborador_cpf_cnpj,
            "estado"=>  $data->colaborador_estado,
            "experiencia"=>  $data->colaborador_experiencia,
            "id"=>  $data->colaborador_id,
            "inativo"=>  $data->colaborador_inativo,
            "nome_completo"=>  $data->colaborador_nome_completo,
            "numero"=>  $data->colaborador_numero,
            "registro"=>  $data->colaborador_registro,
            "rg"=>  $data->colaborador_rg,
            "rua"=>  $data->colaborador_rua,
            "telefone"=>  $data->colaborador_telefone,
            "user_id"=>  $data->colaborador_user_id
        ];

        $correntista = [
            "cidade"=>  $data->correntista_cidade,
            "cnpj"=>  $data->correntista_cnpj,
            "cpf"=>  $data->correntista_cpf,
            "estado"=>  $data->correntista_estado,
            "id"=>  $data->correntista_id,
            "inativo"=>  $data->correntista_inativo,
            "nome"=>  $data->correntista_nome,
            "numero"=>  $data->correntista_numero,
            "rua"=>  $data->correntista_rua,
            "telefone"=>  $data->correntista_telefone,
            "user_id"=>  $data->correntista_user_id
        ];

        $contrato = [
            "acerto_id" => $data->contrato_acerto_id,
            "acerto_pago"=>  $data->contrato_acerto_pago,
            "colaborador_id"=>  $data->contrato_colaborador_id,
            "correntista_id"=>  $data->contrato_correntista_id,
            "data_de_emissao"=>  $data->contrato_data_de_emissao,
            "data_de_fechamento"=>  $data->contrato_data_de_fechamento,
            "descricao_do_servico"=>  $data->contrato_descricao_do_servico,
            "id"=>  $data->contrato_id,
            "percentual_comissao_colaborador"=>  $data->contrato_percentual_comissao_colaborador,
            "status"=>  $data->contrato_status,
            "user_id"=>  $data->contrato_user_id,
            "valor"=>  $data->contrato_valor,
        ];

        return $this->view("Contrato/Show", [
            "colaboradorEdit" => $colaborador,
            "contratoEdit" => $contrato,
            "correntistaEdit" => $correntista,
            "query" => $query,
            "colaboradoresAll" => $colaboradores['colaboradores'],
            "correntistasAll" => $correntistas['correntistas'],
        ]);
    }

    public function findByIdContratoByEmpresa($query) {

        $contrato = DB::table('users')
        ->join('contratos', 'users.id', '=', 'contratos.user_id')
        ->join('empresas', 'users.empresa_id', '=', 'empresas.id')
        ->join('correntistas', 'contratos.correntista_id', '=', 'correntistas.id')
        ->join('colaboradors', 'contratos.colaborador_id', '=', 'colaboradors.id')
        ->select(
            'contratos.id AS contrato_id',
            'contratos.data_de_fechamento AS contrato_data_de_fechamento',
            'contratos.data_de_emissao AS contrato_data_de_emissao',
            'contratos.status AS contrato_status',
            'contratos.descricao_do_servico AS contrato_descricao_do_servico',
            'contratos.valor AS contrato_valor',
            'contratos.acerto_pago AS contrato_acerto_pago',
            'contratos.percentual_comissao_colaborador AS contrato_percentual_comissao_colaborador',
            'contratos.user_id AS contrato_user_id',
            'contratos.acerto_id AS contrato_acerto_id',
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
        )
        ->where([
            ['contratos.id', '=', $query['contrato_id']],
        ])
        ->orderBy('contratos.data_de_emissao', 'desc')
        ->get();

        return $contrato;
    }

    public function findAllByEmpresaContrato($query) {

        $body = json_decode(file_get_contents('php://input'), true);

        $contratos = DB::table('users')
        ->join('contratos', 'users.id', '=', 'contratos.user_id')
        ->join('empresas', 'users.empresa_id', '=', 'empresas.id')
        ->join('correntistas', 'contratos.correntista_id', '=', 'correntistas.id')
        ->join('colaboradors', 'contratos.colaborador_id', '=', 'colaboradors.id')
        ->select(
            'contratos.id AS contrato_id',
            'contratos.data_de_fechamento AS contrato_data_de_fechamento',
            'contratos.data_de_emissao AS contrato_data_de_emissao',
            'contratos.status AS contrato_status',
            'contratos.descricao_do_servico AS contrato_descricao_do_servico',
            'contratos.valor AS contrato_valor',
            'contratos.acerto_pago AS contrato_acerto_pago',
            'contratos.percentual_comissao_colaborador AS contrato_percentual_comissao_colaborador',
            'contratos.user_id AS contrato_user_id',
            'contratos.acerto_id AS contrato_acerto_id',
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
        )
        ->where([
            ['empresas.id', '=', $query['empresa_id']],
        ])
        ->orderBy('contratos.data_de_emissao', 'desc')
        ->get();

        return $contratos;
    }

    public function findAllColaboradorByEmpresa($query) {

        $body = json_decode(file_get_contents('php://input'), true);

        $colaboradores = DB::table('users')
        ->join('colaboradors', 'users.id', '=', 'colaboradors.user_id')
        ->join('empresas', 'users.empresa_id', '=', 'empresas.id')
        ->select('colaboradors.*')
        ->where('empresas.id', '=', $query['empresa_id'])
        ->get();

        return ['colaboradores' => $colaboradores];
    }

    public function findAllCorrentistaByEmpresa($query) {

        $body = json_decode(file_get_contents('php://input'), true);

        $correntistas = DB::table('users')
        ->join('correntistas', 'users.id', '=', 'correntistas.user_id')
        ->join('empresas', 'users.empresa_id', '=', 'empresas.id')
        ->select('correntistas.*')
        ->where('empresas.id', '=', $query['empresa_id'])
        ->get();

        return ['correntistas' => $correntistas];
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $request = json_decode(file_get_contents('php://input'), true);
        $query = $request['query'];
        $body = $request['contrato'];

        $contrato = Contrato::create([
            'data_de_fechamento' => $body['data_de_fechamento'],
            'data_de_emissao' => $body['data_de_emissao'],
            'status' => $body['status'],
            'descricao_do_servico' => $body['descricao_do_servico'],
            'created_at' => $body['data_de_emissao'],
            'updated_at' => $body['data_de_emissao'],
            'valor' =>  $body['valor'],
            'acerto_pago' =>  $body['acerto_pago'],
            'percentual_comissao_colaborador' =>  $body['percentual_comissao_colaborador'],
            'colaborador_id' =>  $body['colaborador_id'],
            'correntista_id' => $body['correntista_id'],
            'user_id' => $body['user_id'],
        ]);

        $colaboradores = $this->findAllColaboradorByEmpresa($query);
        $correntistas = $this->findAllCorrentistaByEmpresa($query);

        return $this->view("Contrato/Create", [
            "colaboradoresAll" => $colaboradores['colaboradores'],
            "correntistasAll" => $correntistas['correntistas'],
            "contratoCreated" => $contrato,
            "query" => $query,
        ]);
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
     * @param  \App\Models\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contrato = Contrato::findOrFail($id);

        return ['contrato' => $contrato];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function edit(Contrato $contrato)
    {

    }

    public function aprovarCreate()
    {
        $body = json_decode(file_get_contents('php://input'), true);
        $query = $body['query'];

        DB::transaction(function () use ($body) {
            $query = $body['query'];

            $contratoUpdate = $body['contrato'];

            $valor_empresa = ($contratoUpdate['valor'] * ((100.00 - $contratoUpdate['percentual_comissao_colaborador'])/100));
            $valor_colaborador = ($contratoUpdate['valor'] * (($contratoUpdate['percentual_comissao_colaborador'])/100));
            $total = $contratoUpdate['valor'];

            $acerto = Acerto::create([
                "valor_colaborador" => $valor_colaborador,
                "valor_empresa" => $valor_empresa,
                "pago" => 0.00,
                "total" => $total,
                "status" => false,
                "restante" => $valor_colaborador,
                "user_id" => $query["user_id"],
                "contrato_id" => $contratoUpdate['id'],
                "data_de_pagamento" => null,
                "created_at" => $contratoUpdate['data_de_fechamento'],
                "updated_at" => $contratoUpdate['data_de_fechamento'],
            ]);

            $contratoUpdate["acerto_id"] = $acerto["id"];

            $contratoUpdate["data_de_fechamento"] = $contratoUpdate['updated_at'];

            $update_contrato = Contrato::where('id', '=', $contratoUpdate['id'])
            ->update($contratoUpdate);

        });


        return Redirect::route('contratos',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'] ]);

    }

    public function aprovarEdit()
    {
        $body = json_decode(file_get_contents('php://input'), true);
        $query = $body['query'];

        DB::transaction(function () use ($body) {
            $query = $body['query'];

            $contratoUpdate = $body['contrato'];
            $acertoCreate = $body['acerto'];

            $valor_empresa = ($contratoUpdate['valor'] * ((100.00 - $contratoUpdate['percentual_comissao_colaborador'])/100));
            $valor_colaborador = ($contratoUpdate['valor'] * (($contratoUpdate['percentual_comissao_colaborador'])/100));
            $total = $contratoUpdate['valor'];

            $acerto = Acerto::create([
                "valor_colaborador" => $valor_colaborador,
                "valor_empresa" => $valor_empresa,
                "pago" => 0.00,
                "restante" => $valor_colaborador,
                "total" => $total,
                "status" => false,
                "user_id" => $query["user_id"],
                "contrato_id" => $contratoUpdate['id'],
                "data_de_pagamento" => null,
                "created_at" => $acertoCreate['created_at'],
                "updated_at" => $acertoCreate['updated_at'],
            ]);

            $contratoUpdate["acerto_id"] = $acerto["id"];
            $contratoUpdate["data_de_fechamento"] =  $acertoCreate['updated_at'];

            $update_contrato = Contrato::where('id', '=', $contratoUpdate['id'])
            ->update($contratoUpdate);

        });


        return Redirect::route('contrato.edit',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'], "contrato_id" => $query["contrato_id"] ]);

    }

    public function cancelar()
    {
        $body = json_decode(file_get_contents('php://input'), true);
        $query = $body['query'];

        DB::transaction(function () use ($body) {
            $query = $body['query'];

            $contratoUpdate = $body['contrato'];

            Acerto::where('contrato_id', '=', $contratoUpdate["id"])->delete();

            $contratoUpdate["acerto_id"] = null;
            $contratoUpdate["acerto_pago"] = null;
            $contratoUpdate["data_de_fechamento"] = null;
            $contratoUpdate["status"] = false;


            $update_contrato = Contrato::where('id', '=', $contratoUpdate['id'])
            ->update($contratoUpdate);

        });

        return Redirect::route('contratos',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'] ]);
    }

    public function cancelarEdit()
    {
        $body = json_decode(file_get_contents('php://input'), true);
        $query = $body['query'];

        DB::transaction(function () use ($body) {
            $query = $body['query'];

            $contratoUpdate = $body['contrato'];

            Acerto::where('contrato_id', '=', $contratoUpdate["id"])->delete();

            $contratoUpdate["acerto_id"] = null;
            $contratoUpdate["acerto_pago"] = null;
            $contratoUpdate["data_de_fechamento"] = null;
            $contratoUpdate["status"] = false;


            $update_contrato = Contrato::where('id', '=', $contratoUpdate['id'])
            ->update($contratoUpdate);

        });

        return Redirect::route('contrato.edit',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'], "contrato_id" => $query["contrato_id"] ]);
    }

    public function update()
    {
        $body = json_decode(file_get_contents('php://input'), true);

        $query = $body['query'];

        $contratoUpdate = $body['contrato'];

        $res = Contrato::where('id', '=', $contratoUpdate['id'])
        ->update($contratoUpdate);

        $colaboradores = $this->findAllColaboradorByEmpresa($query);

        $correntistas = $this->findAllCorrentistaByEmpresa($query);

        $dataAll = $this->findByIdContratoByEmpresa($query);

        $data = $dataAll[0];

        $colaborador = [
            "bairro" => $data->colaborador_bairro,
            "cidade"=>  $data->colaborador_cidade,
            "comissao"=>  $data->colaborador_comissao,
            "cpf_cnpj"=>  $data->colaborador_cpf_cnpj,
            "estado"=>  $data->colaborador_estado,
            "experiencia"=>  $data->colaborador_experiencia,
            "id"=>  $data->colaborador_id,
            "inativo"=>  $data->colaborador_inativo,
            "nome_completo"=>  $data->colaborador_nome_completo,
            "numero"=>  $data->colaborador_numero,
            "registro"=>  $data->colaborador_registro,
            "rg"=>  $data->colaborador_rg,
            "rua"=>  $data->colaborador_rua,
            "telefone"=>  $data->colaborador_telefone,
            "user_id"=>  $data->colaborador_user_id
        ];

        $correntista = [
            "cidade"=>  $data->correntista_cidade,
            "cnpj"=>  $data->correntista_cnpj,
            "cpf"=>  $data->correntista_cpf,
            "estado"=>  $data->correntista_estado,
            "id"=>  $data->correntista_id,
            "inativo"=>  $data->correntista_inativo,
            "nome"=>  $data->correntista_nome,
            "numero"=>  $data->correntista_numero,
            "rua"=>  $data->correntista_rua,
            "telefone"=>  $data->correntista_telefone,
            "user_id"=>  $data->correntista_user_id
        ];

        $contrato = [
            "acerto_id" => $data->contrato_acerto_id,
            "acerto_pago"=>  $data->contrato_acerto_pago,
            "colaborador_id"=>  $data->contrato_colaborador_id,
            "correntista_id"=>  $data->contrato_correntista_id,
            "data_de_emissao"=>  $data->contrato_data_de_emissao,
            "data_de_fechamento"=>  $data->contrato_data_de_fechamento,
            "descricao_do_servico"=>  $data->contrato_descricao_do_servico,
            "id"=>  $data->contrato_id,
            "percentual_comissao_colaborador"=>  $data->contrato_percentual_comissao_colaborador,
            "status"=>  $data->contrato_status,
            "user_id"=>  $data->contrato_user_id,
            "valor"=>  $data->contrato_valor,
        ];

        return $this->view("Contrato/Show", [
            "colaboradorEdit" => $colaborador,
            "contratoEdit" => $contrato,
            "correntistaEdit" => $correntista,
            "query" => $query,
            "colaboradoresAll" => $colaboradores['colaboradores'],
            "correntistasAll" => $correntistas['correntistas'],
        ]);
    }

      /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request
     */
    public function destroy($contrato_id, $user_id)
    {
        $body = json_decode(file_get_contents('php://input'), true);

        $user_id_empresa = User::findOrFail(["id" => $user_id]);

        $contrato_user_id = Contrato::findOrFail(["id" => $contrato_id]);

        $contrato_empresa_id = User::findOrFail(["id" =>  $contrato_user_id[0]->user_id]);

        //verificar se os usuarios sao da mesma empresa
        if($contrato_empresa_id[0]->empresa_id == $user_id_empresa[0]->empresa_id ) {

            $res= Contrato::where('id', '=',  $contrato_id)->delete();

            $query = [
                'empresa_id' => $user_id_empresa[0]->empresa_id,
                "user_id" => $user_id,
            ];

            return Redirect::route('contratos',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'] ]);

        } else {
            $query = [
                'empresa_id' => $user_id_empresa[0]->empresa_id,
                "user_id" => $user_id,
            ];

            return Redirect::route('contratos',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'] ]);
        }
    }
}
