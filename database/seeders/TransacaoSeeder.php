<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transacao;

class TransacaoSeeder extends Seeder
{
    public function run()
    {
        Transacao::create([
            'nome' => 'Supermercado Pingo Doce',
            'categoria' => 'Alimentação',
            'valor' => -85.40,
            'data' => '2026-03-01',
        ]);

        Transacao::create([ 
            'nome' => 'Salário',
            'categoria' => 'Receitas',
            'valor' => 2500.00,
            'data' => '2026-03-01',
        ]);

        Transacao::create([
            'nome' => 'Netflix',
            'categoria' => 'Lazer',
            'valor' => -15.99,
            'data' => '2026-03-05',
        ]);
    }
}