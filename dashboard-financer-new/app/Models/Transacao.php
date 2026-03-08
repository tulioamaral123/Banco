<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    protected $table = 'transacoes';

    protected $fillable = [
        'nome',
        'categoria',
        'data',
        'valor',
    ];

    protected function casts(): array
    {
        return [
            'data'  => 'date',
            'valor' => 'decimal:2',
        ];
    }
}
