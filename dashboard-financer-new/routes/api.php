<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransacaoController;
use App\Http\Controllers\Api\InvestimentoController;
use App\Http\Controllers\Api\AvaliacaoController;

Route::prefix('v1')->group(function () {
    // Transações
    Route::get('/transacoes', [TransacaoController::class, 'index']);
    Route::post('/transacoes', [TransacaoController::class, 'store']);
    Route::get('/transacoes/{id}', [TransacaoController::class, 'show']);
    Route::put('/transacoes/{id}', [TransacaoController::class, 'update']);
    Route::delete('/transacoes/{id}', [TransacaoController::class, 'destroy']);

    // Avaliação IA
    Route::post('/avaliacao/investimentos', [AvaliacaoController::class, 'investimentos']);

    // Investimentos
    Route::get('/investimentos', [InvestimentoController::class, 'index']);
    Route::post('/investimentos', [InvestimentoController::class, 'store']);
    Route::get('/investimentos/{id}', [InvestimentoController::class, 'show']);
    Route::put('/investimentos/{id}', [InvestimentoController::class, 'update']);
    Route::delete('/investimentos/{id}', [InvestimentoController::class, 'destroy']);
});
