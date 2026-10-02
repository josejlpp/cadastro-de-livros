<?php

namespace Biblioteca\Simulador\Infrastructure\Persistence;

use App\Models\Simulacao as SimulacaoModel;
use Biblioteca\Simulador\Domain\Entity\Simulacao;
use Biblioteca\Simulador\Domain\Persistence\Dao\SimulacaoDao;
use Illuminate\Support\Str;

class SimulacaoDaoEloquent implements SimulacaoDao
{
    public function adicionar(Simulacao $simulacao): void
    {
        $contato = $simulacao->contato;
        $perfil = $simulacao->perfil;
        $resultado = $simulacao->resultado;

        $simulacaoModel = SimulacaoModel::create([
            'uuid' => (string) Str::uuid(),
            'nome' => $contato->nome,
            'email' => $contato->email,
            'telefone' => $contato->telefone,
            'empresa' => $contato->empresa,
            'cnpj' => $contato->cnpj,
            'regime' => $perfil->regime->value,
            'segmento' => $perfil->segmento->value,
            'faturamento_mensal' => $perfil->faturamentoMensal,
            'folha_pagamento_mensal' => $perfil->folhaPagamentoMensal,
            'energia_mensal' => $perfil->energiaMensal,
            'combustivel_mensal' => $perfil->combustivelMensal,
            'icms_mensal' => $perfil->icmsMensal,
            'iss_mensal' => $perfil->issMensal,
            'percentual_monofasico' => $perfil->percentualMonofasico,
            'meses' => $perfil->meses,
            'total_minimo' => $resultado->totalMinimo(),
            'total_maximo' => $resultado->totalMaximo(),
            'resultado' => $resultado->toArray(),
        ]);

        $simulacao->adicionarIdentificacao($simulacaoModel->id, $simulacaoModel->uuid);
    }
}
