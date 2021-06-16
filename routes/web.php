<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CorrentistaController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\ComissaoController;
use App\Http\Controllers\AcertoController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\RecebeController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// DASHBOARD
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard',  [DashBoardController::class, 'renderPage'])->name('dashboard');


//COMISSAO
Route::middleware(['auth:sanctum', 'verified'])->get('/comissoes',  [AcertoController::class, 'renderPage'])->name('comissoes');
Route::middleware(['auth:sanctum', 'verified'])->put('/comissoes',  [AcertoController::class, 'update'])->name('comissao.update');
Route::middleware(['auth:sanctum', 'verified'])->put('/comissoe/cancelar',  [AcertoController::class, 'cancelar'])->name('comissao.cancelar');


//CORRENTISTA
Route::middleware(['auth:sanctum', 'verified'])->get('/correntistas', [CorrentistaController::class, 'renderPage'])->name('correntistas');

Route::middleware(['auth:sanctum', 'verified'])->post('/correntista', [CorrentistaController::class, 'create'])->name('correntista.create');

Route::middleware(['auth:sanctum', 'verified'])->put('/correntista', [CorrentistaController::class, 'update'])->name('correntista.update');

Route::middleware(['auth:sanctum', 'verified'])->delete('/correntista/{correntista_id}/user/{user_id}', [CorrentistaController::class, 'destroy'])->name('correntista.delete');


// COLABORADORES
Route::middleware(['auth:sanctum', 'verified'])->get('/colaboradores', [ColaboradorController::class, 'renderPage'])->name('colaboradores');

Route::middleware(['auth:sanctum', 'verified'])->post('/colaborador', [ColaboradorController::class, 'create'])->name('colaborador.create');

Route::middleware(['auth:sanctum', 'verified'])->put('/colaborador', [ColaboradorController::class, 'update'])->name('colaborador.update');

Route::middleware(['auth:sanctum', 'verified'])->delete('/colaborador/{colaborador_id}/user/{user_id}', [ColaboradorController::class, 'destroy'])->name('colaborador.delete');


// CONTRATO
Route::middleware(['auth:sanctum', 'verified'])->get('/contratos', [ContratoController::class, 'renderPage'])->name('contratos');

Route::middleware(['auth:sanctum', 'verified'])->get('/contrato/emissao', [ContratoController::class, 'renderPageCreate'])->name('contrato.emissao');

Route::middleware(['auth:sanctum', 'verified'])->post('/contrato/emissao', [ContratoController::class, 'create'])->name('contrato.create');

Route::middleware(['auth:sanctum', 'verified'])->put('/contrato/emissao', [ContratoController::class, 'update'])->name('contrato.updateCreate');

Route::middleware(['auth:sanctum', 'verified'])->put('/contrato/emissao/aprovado', [ContratoController::class, 'aprovarCreate'])->name('contrato.aprovar');

Route::middleware(['auth:sanctum', 'verified'])->put('/contrato/emissao/cancelado', [ContratoController::class, 'cancelar'])->name('contrato.cancelar');

Route::middleware(['auth:sanctum', 'verified'])->get('/contrato/edit', [ContratoController::class, 'renderPageEdit'])->name('contrato.edit');

Route::middleware(['auth:sanctum', 'verified'])->put('/contrato/edit/aprovado', [ContratoController::class, 'aprovarEdit'])->name('contrato.aprovarEdit');

Route::middleware(['auth:sanctum', 'verified'])->put('/contrato/edit/cancelado', [ContratoController::class, 'cancelarEdit'])->name('contrato.cancelarEdit');

Route::middleware(['auth:sanctum', 'verified'])->put('/contrato/edit', [ContratoController::class, 'update'])->name('contrato.update');

Route::middleware(['auth:sanctum', 'verified'])->delete('/contrato/{contrato_id}/user/{user_id}', [ContratoController::class, 'destroy'])->name('contrato.delete');



//EMPRESA
Route::middleware(['auth:sanctum', 'verified'])->get('/empresa', [EmpresaController::class, 'renderPage'] )->name('empresa');
Route::middleware(['auth:sanctum', 'verified'])->put('/empresa', [EmpresaController::class, 'update'] )->name('empresa.update');


//USUARIOS
Route::middleware(['auth:sanctum', 'verified'])->get('/usuarios', [UserController::class, 'renderPage'] )->name('usuarios');

Route::middleware(['auth:sanctum', 'verified'])->put('/usuarios', [UserController::class, 'updateInfo'] )->name('usuario.update');

Route::middleware(['auth:sanctum', 'verified'])->post('/usuarios', [UserController::class, 'create'] )->name('usuario.create');

//RECEBIMENTOS
Route::middleware(['auth:sanctum', 'verified'])->get('/recebimentos', [RecebeController::class, 'renderPage'] )->name('recebimentos');
Route::middleware(['auth:sanctum', 'verified'])->put('/recebimentos', [RecebeController::class, 'update'] )->name('recebimento.update');
//Route::middleware(['auth:sanctum', 'verified'])->put('/recebimento/cancelar', [RecebeController::class, 'cancelar'] )->name('recebimento.update');
