<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investimento extends Model
{
    protected $table = 'investimentos';

    protected $fillable = [
        'nome',
        'data',
        'valor',
        'moeda',
        'tipo'
    ];

    protected function casts(): array
    {
        return [
            'data'  => 'date',
            'valor' => 'decimal:2',
        ];
    }
}
