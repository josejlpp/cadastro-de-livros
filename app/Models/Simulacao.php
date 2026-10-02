<?php

namespace App\Models;

use Biblioteca\Simulador\Domain\Enum\RegimeTributario;
use Biblioteca\Simulador\Domain\Enum\Segmento;
use Illuminate\Database\Eloquent\Model;

class Simulacao extends Model
{
    protected $table = 'simulacoes';

    protected $fillable = [
        'uuid',
        'nome',
        'email',
        'telefone',
        'empresa',
        'cnpj',
        'regime',
        'segmento',
        'faturamento_mensal',
        'folha_pagamento_mensal',
        'energia_mensal',
        'combustivel_mensal',
        'icms_mensal',
        'iss_mensal',
        'percentual_monofasico',
        'meses',
        'total_minimo',
        'total_maximo',
        'resultado',
    ];

    protected function casts(): array
    {
        return [
            'regime' => RegimeTributario::class,
            'segmento' => Segmento::class,
            'faturamento_mensal' => 'float',
            'folha_pagamento_mensal' => 'float',
            'energia_mensal' => 'float',
            'combustivel_mensal' => 'float',
            'icms_mensal' => 'float',
            'iss_mensal' => 'float',
            'percentual_monofasico' => 'float',
            'total_minimo' => 'float',
            'total_maximo' => 'float',
            'resultado' => 'array',
        ];
    }
}
