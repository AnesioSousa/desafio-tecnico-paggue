<?php
//routes/api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutorController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\SetorController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\IngressoController;
use App\Http\Controllers\CupomController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'v1'], function(){
    // PRODUTOR CRUD
    Route::apiResource('produtores', ProdutorController::class)->parameters([
        'produtores' => 'produtor'
    ]);

    // EVENTO CRUD
    Route::apiResource('eventos', EventoController::class);

    // SETOR CRUD
    Route::apiResource('setores', SetorController::class);

    // LOTE CRUD
    Route::apiResource('lotes', LoteController::class);

    // INGRESSO CRUD
    Route::apiResource('ingressos', IngressoController::class);

    // CUPOM DE DESCONTO CRUD
    Route::apiResource('cupons', CupomController::class)->withoutMiddleware('auth:sanctum');
    });