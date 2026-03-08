<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Investimento;
use Illuminate\Support\Facades\Http;

class AvaliacaoController extends Controller
{
    public function investimentos()
    {
        $apiKey = env('ANTHROPIC_API_KEY');
        $context = env('IA_CONTEXT', 'Você é um consultor financeiro especializado em investimentos.');

        if (empty($apiKey)) {
            return response()->json(['error' => 'ANTHROPIC_API_KEY não configurada.'], 500);
        }

        $investimentos = Investimento::orderBy('data', 'desc')->get();

        if ($investimentos->isEmpty()) {
            return response()->json(['error' => 'Nenhum investimento encontrado.'], 404);
        }

        $resumo = $investimentos->groupBy('tipo')->map(fn($grupo, $tipo) => [
            'tipo'       => $tipo,
            'quantidade' => $grupo->count(),
            'total_brl'  => $grupo->where('moeda', 'BRL')->sum('valor'),
            'total_usd'  => $grupo->where('moeda', 'USD')->sum('valor'),
        ])->values();

        $lista = $investimentos->map(fn($i) => "{$i->nome} ({$i->tipo}) — {$i->moeda} {$i->valor} em {$i->data->format('d/m/Y')}")->join("\n");

        $prompt = <<<TEXT
        Analise a seguinte carteira de investimentos e forneça uma avaliação detalhada:

        RESUMO POR TIPO:
        {$resumo->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)}

        LISTA COMPLETA:
        {$lista}

        Por favor, avalie:
        1. Diversificação da carteira
        2. Pontos positivos
        3. Riscos identificados
        4. Sugestões de melhoria
        TEXT;

        $response = Http::withHeaders([
            'x-api-key'         => $apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model'      => 'claude-sonnet-4-6',
            'max_tokens' => 1024,
            'system'     => $context,
            'messages'   => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Erro ao contactar a IA.'], 502);
        }

        $text = $response->json('content.0.text', '');

        return response()->json(['avaliacao' => $text]);
    }
}
