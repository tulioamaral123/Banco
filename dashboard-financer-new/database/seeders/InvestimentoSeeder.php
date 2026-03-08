<?php

namespace Database\Seeders;

use App\Models\Investimento;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class InvestimentoSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('pt_BR');

        $tipos = [
            'Ações'          => ['PETR4', 'VALE3', 'ITUB4', 'BBDC4', 'WEGE3', 'MGLU3', 'BBAS3', 'EGIE3'],
            'FII'            => ['HGLG11', 'KNRI11', 'XPML11', 'VISC11', 'BCFF11', 'MXRF11'],
            'Tesouro Direto' => ['Tesouro Selic 2029', 'Tesouro IPCA+ 2035', 'Tesouro Prefixado 2027'],
            'CDB'            => ['CDB Banco Inter', 'CDB Nubank', 'CDB BTG', 'CDB XP'],
            'Criptomoeda'    => ['Bitcoin', 'Ethereum', 'Solana', 'BNB'],
        ];

        $moedas = [
            'Ações'          => 'BRL',
            'FII'            => 'BRL',
            'Tesouro Direto' => 'BRL',
            'CDB'            => 'BRL',
            'Criptomoeda'    => 'USD',
        ];

        for ($i = 0; $i < 50; $i++) {
            $tipo = $faker->randomElement(array_keys($tipos));
            $nome = $faker->randomElement($tipos[$tipo]);

            Investimento::create([
                'nome'  => $nome,
                'data'  => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                'valor' => $faker->randomFloat(2, 100, 50000),
                'moeda' => $moedas[$tipo],
                'tipo'  => $tipo,
            ]);
        }
    }
}
