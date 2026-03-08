<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transacao;
use Faker\Factory as Faker;

class TransacaoSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('pt_PT');

        $categorias = [
            'Alimentação' => ['Supermercado Pingo Doce', 'Continente', 'Mercadona', 'Restaurante', 'Padaria', 'Talho'],
            'Habitação' => ['Renda do Apartamento', 'Condomínio', 'Água', 'Luz', 'Gás', 'Internet'],
            'Lazer' => ['Netflix', 'Spotify', 'Cinema', 'Teatro', 'Ginásio', 'Restaurante'],
            'Transportes' => ['Combustível', 'Metro', 'Uber', 'Comboio', 'Estacionamento', 'Manutenção Carro'],
            'Saúde' => ['Farmácia', 'Consulta Médica', 'Dentista', 'Seguro de Saúde', 'Laboratório'],
            'Educação' => ['Livros', 'Curso Online', 'Material Escolar', 'Mensalidade'],
            'Receitas' => ['Salário', 'Freelance', 'Venda OLX', 'Renda', 'Investimentos'],
        ];

        for ($i = 0; $i < 1000; $i++) {
            $categoria = $faker->randomElement(array_keys($categorias));
            $nome = $faker->randomElement($categorias[$categoria]);
            
            // Se for receita, valor positivo, senão negativo
            if ($categoria === 'Receitas') {
                $valor = $faker->randomFloat(2, 100, 3000);
            } else {
                $valor = -$faker->randomFloat(2, 5, 500);
            }

            Transacao::create([
                'nome' => $nome,
                'categoria' => $categoria,
                'valor' => $valor,
                'data' => $faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            ]);
        }
    }
}