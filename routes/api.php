<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\PainelController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/painel', [PainelController::class, 'view']);
Route::get('/painel/relatorio', [PainelController::class, 'indicadoresParaJson']);
Route::get('/painel/variacao', [PainelController::class, 'variacaoIndicadores']);
Route::get('/painel/variacao/json', [PainelController::class, 'variacaoParaJson']);



