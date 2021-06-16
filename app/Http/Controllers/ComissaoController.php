<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Acerto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;

class ComissaoController extends Controller
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


    public function renderPage()
    {
        return $this->view("Comissao/Index");
    }

    public function index()
    {
        $comissoes = Contrato::all()
        ->where('status', '=', '1')
        ->where('valor', '>', '0.00');

        return ['comissoes' => $comissoes];
    }

    public function cancelar()
    {
        $body = json_decode(file_get_contents('php://input'), true);

        $query = $body["query"];

        $acerto=  $body["acerto"];

        Acerto::where("id", "=", $acerto["acerto_id"])
        ->where("contrato_id", "=", $acerto["acerto_contrato_id"])
        ->update($acerto);

        return Redirect::route('comissoes',['empresa_id' => $query['empresa_id'], "user_id" => $query['user_id'] ]);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
