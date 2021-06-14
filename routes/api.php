<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CorrentistaController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\ComissaoController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AcertoController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/empresa/{empresa_id}', [UserController::class, 'findAllByEmpresa']);
Route::get('/user/{id}', [UserController::class, 'show']);
Route::post('/user', [UserController::class, 'create']);
Route::put('/user/{id}', [UserController::class, 'update']);
Route::delete('/user/{id}', [UserController::class, 'destroy']);

Route::get('/empresas', [EmpresaController::class, 'index'] );
Route::get('/empresa/{id}', [EmpresaController::class, 'show'] );
Route::post('/empresa', [EmpresaController::class, 'create'] );
Route::put('/empresa/{id}', [EmpresaController::class, 'update'] );
Route::delete('/empresa/{id}', [EmpresaController::class, 'destroy'] );

Route::get('/correntistas', [CorrentistaController::class, 'index'] );
Route::get('/correntistas/empresa/{empresa_id}', [CorrentistaController::class, 'findAllByEmpresa'] );
Route::get('/correntista/{id}', [CorrentistaController::class, 'show'] );
Route::post('/correntista', [CorrentistaController::class, 'create'] );
Route::put('/correntista/{id}', [CorrentistaController::class, 'update'] );
Route::delete('/correntista/{id}', [CorrentistaController::class, 'destroy'] );

Route::get('/colaboradores', [ColaboradorController::class, 'index'] );
Route::get('/colaboradores/empresa/{empresa_id}', [ColaboradorController::class, 'findAllByEmpresa']);
Route::get('/colaborador/{id}', [ColaboradorController::class, 'show'] );
Route::get('/colaborador/{id}', [ColaboradorController::class, 'show'] );
Route::post('/colaborador', [ColaboradorController::class, 'create'] );
Route::put('/colaborador/{id}', [ColaboradorController::class, 'update'] );
Route::delete('/colaborador/{id}', [ColaboradorController::class, 'destroy'] );


Route::get('/contratos', [ContratoController::class, 'index'] );
Route::get('/contrato/{id}', [ContratoController::class, 'show'] );
Route::get('/contratos/empresa/{empresa_sid}', [ContratoController::class, 'findAllByEmpresa'] );
Route::post('/contrato', [ContratoController::class, 'create'] );
Route::put('/contrato/{id}', [ContratoController::class, 'update'] );
Route::delete('/contrato/{id}', [ContratoController::class, 'destroy'] );

Route::get('/acertos', [AcertoController::class, 'index']);
Route::get('/acerto/{id}', [AcertoController::class, 'show']);
Route::post('/acerto', [AcertoController::class, 'create']);
Route::put('/acerto/{id}', [AcertoController::class, 'update']);
Route::delete('/acerto/{id}', [AcertoController::class, 'destroy']);
*/

