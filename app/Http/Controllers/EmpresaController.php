<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Carbon;
class EmpresaController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function renderPage(Request $query)
    {
        $empresa = $this->show($query["empresa_id"]);

        return $this->view("Empresa/Index", ["empresaShow" => $empresa, "query" => $query]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresas = Empresa::all();

        return ["empresas" => $empresas];
    }

    public function create()
    {
        $body = json_decode(file_get_contents('php://input'), true);

        $empresa = Empresa::create([
            'fantasia' => $body['fantasia'],
            'razao_social' => $body['razao_social'],
            'created_at' => now(),
            'updated_at' => now(),
            'inscricao_estadual' => $body['inscricao_estadual'],
            'cpf' =>  $body['cpf'],
            'cnpj' =>  $body['cnpj'],
            'telefone' =>  $body['telefone'],
            'celular' =>  $body['celular'],
            'bairro' => $body['bairro'],
            'rua' => $body['rua'],
            'numero' =>  $body['numero'],
            'cidade' =>  $body['cidade'],
            'estado' =>  $body['estado'],
            'cep' =>  $body['cep'],
            'email' => $body['email'],
        ]);

        return ["empresa" => $response];
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

    public function show($id)
    {
        $empresa = Empresa::findOrFail($id);

        return $empresa;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresa $empresa)
    {
        //
    }


    public function update()
    {
        $body = json_decode(file_get_contents('php://input'), true);

        $empresaUpdate = $body["empresa"];

        $query = $body["query"];

        $res = Empresa::where('id', '=', $empresaUpdate["id"])
            ->update($empresaUpdate);

        $empresa = $this->show($empresaUpdate["id"]);

        return $this->view("Empresa/Index", ["empresaShow" => $empresa, "query" => $query]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $body = json_decode(file_get_contents('php://input'), true);

        $user_id_empresa = User::findOrFail(["id" => $body["user_id"]]);

        $empresa = Empresa::where('id', '=',  $user_id_empresa[0]->empresa_id)
            ->delete();

        return ["empresa" => $empresa];
    }
}
