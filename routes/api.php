<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransacaoController;

Route::prefix('v1')->group(function () {
    // Transações
    Route::get('/transacoes', [TransacaoController::class, 'index']);
    Route::post('/transacoes', [TransacaoController::class, 'store']);
    Route::get('/transacoes/{id}', [TransacaoController::class, 'show']);
    Route::put('/transacoes/{id}', [TransacaoController::class, 'update']);
    Route::delete('/transacoes/{id}', [TransacaoController::class, 'destroy']);
});
