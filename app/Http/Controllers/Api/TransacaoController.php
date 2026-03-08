<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transacao;
use App\Http\Requests\TransacaoRequest;

class TransacaoController extends Controller
{
    public function index()
    {
        $transacoes = Transacao::all();
        return response()->json($transacoes);
    }

    public function store(TransacaoRequest $request)
    {
        $transacao = Transacao::create($request->validated());
        return response()->json($transacao, 201);
    }

    public function show($id)
    {
        $transacao = Transacao::findOrFail($id);
        return response()->json($transacao);
    }

    public function update(TransacaoRequest $request, $id)
    {
        $transacao = Transacao::findOrFail($id);
        $transacao->update($request->validated());
        return response()->json($transacao);
    }

    public function destroy($id)
    {
        $transacao = Transacao::findOrFail($id);
        $transacao->delete();
        return response()->json(null, 204);
    }
}
