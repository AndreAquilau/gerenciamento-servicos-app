<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Redirect;

class ColaboradorController extends Controller
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
     * @param  \Illuminate\Http\Request  $request () -> request: {empresa_id: "3", user_id: "2"}
     * @return \Illuminate\Http\Response
     */

    public function renderPage(Request $request)
    {

        $datas = $this->findAllByEmpresa($request);

        return $this->view("Colaborador/Index", ["colaboradoresAllByEmpresa" => $datas['colaboradores'], "query" => $request]);
    }

    public function findAllByEmpresa($request) {

        $body = json_decode(file_get_contents('php://input'), true);

        $colaboradores = DB::table('users')
        ->join('colaboradors', 'users.id', '=', 'colaboradors.user_id')
        ->join('empresas', 'users.empresa_id', '=', 'empresas.id')
        ->select('colaboradors.*')
        ->where('empresas.id', '=', $request['empresa_id'])
        ->get();

        return ['colaboradores' => $colaboradores];
    }

    public function index()
    {

        $colaboradores = Colaborador::all();



        return ['colaboradores' => $colaboradores];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $body = json_decode(file_get_contents('php://input'), true);
        $query = $body['query'];

        $colaborador = Colaborador::create([
            'nome_completo' => $body['nome_completo'],
            'rg' => $body['rg'],
            'rua' => $body['rua'],
            'registro' => $body['registro'],
            'created_at' => now(),
            'updated_at' => now(),
            'cpf_cnpj' =>  $body['cpf_cnpj'],
            'comissao' =>  $body['comissao'],
            'experiencia' =>  $body['experiencia'],
            'telefone' =>  $body['telefone'],
            'bairro' => $body['bairro'],
            'numero' =>  $body['numero'],
            'cidade' =>  $body['cidade'],
            'estado' =>  $body['estado'],
            'user_id' => $body['user_id'],
            'inativo' => $body['inativo'],
        ]);

        $datas = $this->findAllByEmpresa($query);

        return Redirect::route('colaboradores',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'] ]);
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
     * @param  \App\Models\Colaborador  $colaborador
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response= Colaborador::findOrFail($id);

        return ['colaborador' => $response];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Colaborador  $colaborador
     * @return \Illuminate\Http\Response
     */
    public function edit(Colaborador $colaborador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Colaborador  $colaborador
     * @return \Illuminate\Http\Response
     */

    public function update()
    {
        $body = json_decode(file_get_contents('php://input'), true);

        $query = $body['query'];
        $colaborador = $body['colaborador'];

        $res = Colaborador::where('id', '=', $colaborador['id'])
            ->update($colaborador);

        return Redirect::route('colaboradores',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'] ]);
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request
     */
    public function destroy($colaborador_id, $user_id)
    {

        $body = json_decode(file_get_contents('php://input'), true);

        //busca a empresa do usuario que esta tentando deletar um usuario em sua empresa
        $user_id_empresa = User::findOrFail(["id" => $user_id]);

        $colaborador_user_id = Colaborador::findOrFail(["id" => $colaborador_id]);

        $colaborador_empresa_id = User::findOrFail(["id" =>  $colaborador_user_id[0]->user_id]);

        //verificar se os usuarios sao da mesma empresa
        if($colaborador_empresa_id[0]->empresa_id == $user_id_empresa[0]->empresa_id ) {

            $res= Colaborador::where('id', '=',  $colaborador_id)->delete();

            $query = [
                'empresa_id' => $user_id_empresa[0]->empresa_id,
                "user_id" => $user_id,
            ];

            return Redirect::route('colaboradores',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'] ]);

        } else {
            $query = [
                'empresa_id' => $user_id_empresa[0]->empresa_id,
                "user_id" => $user_id,
            ];

            return Redirect::route('colaboradores',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'] ]);
        }
    }
}
