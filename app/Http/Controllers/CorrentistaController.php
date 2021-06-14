<?php

namespace App\Http\Controllers;

use App\Models\Correntista;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Redirect;

class CorrentistaController extends Controller
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

    /*
    * @param  \Illuminate\Http\Request  $request () -> request: {empresa_id: "3", user_id: "2"}
    * @return \Illuminate\Http\Response
    */
    public function renderPage(Request $request)
    {
        $datas = $this->findAllByEmpresa($request);

        return $this->view("Correntista/Index", ["correntistasAllByEmpresa" => $datas['correntistas'], "query" => $request]);
    }

    public function index()
    {

        $correntistas = Correntista::all();

        return ['correntistas' => $correntistas];
    }

    public function findAllByEmpresa($request) {

        $body = json_decode(file_get_contents('php://input'), true);

        $correntistas = DB::table('users')
        ->join('correntistas', 'users.id', '=', 'correntistas.user_id')
        ->join('empresas', 'users.empresa_id', '=', 'empresas.id')
        ->select('correntistas.*')
        ->where('empresas.id', '=', $request['empresa_id'])
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
        $body = json_decode(file_get_contents('php://input'), true);
        $query = $body['query'];

        $correntista = Correntista::create([
            'nome' => $body['nome'],
            'rua' => $body['rua'],
            'created_at' => now(),
            'updated_at' => now(),
            'cpf' =>  $body['cpf'],
            'cnpj' =>  $body['cnpj'],
            'telefone' =>  $body['telefone'],
            'bairro' => $body['bairro'],
            'numero' =>  $body['numero'],
            'cidade' =>  $body['cidade'],
            'estado' =>  $body['estado'],
            'user_id' => $body['user_id'],
        ]);


        //return ["correntista" => $correntista];
        $datas = $this->findAllByEmpresa($query);

        return Redirect::route('correntistas',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'] ]);
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
     * @param  \App\Models\Correntista  $correntista
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $correntista = Correntista::findOrFail($id);;

        return ['correntista' => $correntista];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Correntista  $correntista
     * @return \Illuminate\Http\Response
     */
    public function edit(Correntista $correntista)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Correntista  $correntista
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $body = json_decode(file_get_contents('php://input'), true);
        $query = $body['query'];
        $correntista = $body['correntista'];

        $res = Correntista::where('id', '=', $correntista['id'])
            ->update($correntista);

        return Redirect::route('correntistas',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'] ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Correntista  $correntista
     * @return \Illuminate\Http\Response
     */
    public function destroy($correntista_id, $user_id)
    {
        $body = json_decode(file_get_contents('php://input'), true);

        //busca a empresa do usuario que esta tentando deletar um usuario em sua empresa
        $user_id_empresa = User::findOrFail(["id" => $user_id]);

        $colaborador_user_id = Correntista::findOrFail(["id" => $correntista_id]);

        $colaborador_empresa_id = User::findOrFail(["id" =>  $colaborador_user_id[0]->user_id]);

        //verificar se os usuarios sao da mesma empresa
        if($colaborador_empresa_id[0]->empresa_id == $user_id_empresa[0]->empresa_id ) {

            $res= Correntista::where('id', '=',  $correntista_id)->delete();

            $query = [
                'empresa_id' => $user_id_empresa[0]->empresa_id,
                "user_id" => $user_id,
            ];

            return Redirect::route('correntistas',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'] ]);

        } else {
            $query = [
                'empresa_id' => $user_id_empresa[0]->empresa_id,
                "user_id" => $user_id,
            ];

            return Redirect::route('correntistas',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'] ]);
        }

    }
}
