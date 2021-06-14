<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Correntista;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Redirect;

class DashBoardController extends Controller
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
        return $this->view("Dashboard", ["teste" => $request]);
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
