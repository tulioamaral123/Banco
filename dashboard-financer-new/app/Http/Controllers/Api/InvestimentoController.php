<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Investimento;
use Illuminate\Http\Request;

class InvestimentoController extends Controller
{
    public function index()
    {
        return response()->json(Investimento::orderBy('data', 'desc')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'  => 'required|string|max:255',
            'data'  => 'required|date',
            'valor' => 'required|numeric|min:0',
            'moeda' => 'required|string|max:10',
            'tipo'  => 'required|string|max:100',
        ]);

        $investimento = Investimento::create($data);
        return response()->json($investimento, 201);
    }

    public function show($id)
    {
        return response()->json(Investimento::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $investimento = Investimento::findOrFail($id);

        $data = $request->validate([
            'nome'  => 'required|string|max:255',
            'data'  => 'required|date',
            'valor' => 'required|numeric|min:0',
            'moeda' => 'required|string|max:10',
            'tipo'  => 'required|string|max:100',
        ]);

        $investimento->update($data);
        return response()->json($investimento);
    }

    public function destroy($id)
    {
        Investimento::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
